<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Po_system extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('session');

        // Proteksi login
        if (!sessPenggunaId()) {
            redirect('login');
        }

        $this->load->model('md_po_system');
        $this->load->model('md_pengguna');
    }

    public function id_navbar()
    {
        return 'helpdesk';
    }

    /**
     * Halaman Utama: Daftar PO Masuk
     */
    public function index()
    {
        $status_filter = $this->input->get('status');
        if (!in_array($status_filter, ['menunggu_kroscek', 'sesuai', 'revisi', 'ditolak'])) {
            $status_filter = null;
        }

        $page_data['po_list']   = $this->md_po_system->getAllPo($status_filter);
        $page_data['stats']     = $this->md_po_system->getStatistik();
        $page_data['cur_status']= $status_filter;

        $user = $this->md_pengguna->getById(sessPenggunaId());
        $my_jabatan = is_array($user) ? $user[0]->jabatan : ($user->jabatan ?? '');
        $page_data['my_jabatan'] = $my_jabatan;
        $page_data['can_kroscek'] = (isAdmin() || isCRO() || stripos($my_jabatan, 'CRO') !== false);

        $page_data['page_name']  = 'po/v_po_list';
        $page_data['page_title'] = 'Daftar PO Masuk';
        $page_data['page_desc']  = 'Penerimaan & Kroscek PO Customer';
        $page_data['switch']     = $this->id_navbar();

        $this->load->view('index', $page_data);
    }

    /**
     * Form Input PO Baru
     */
    public function tambah()
    {
        // Ambil data customer untuk dropdown
        $this->db->select('id_customer, nama_customer, alamat_customer, contact');
        $this->db->from('customer');
        $this->db->where('status', 1);
        $this->db->order_by('nama_customer', 'ASC');
        $page_data['customers'] = $this->db->get()->result();

        // Ambil data sales/marketing
        $this->db->select('pengguna_id, nama, jabatan');
        $this->db->from('pengguna');
        $this->db->where('status', 1);
        $this->db->group_start();
        $this->db->like('jabatan', 'Marketing');
        $this->db->or_like('jabatan', 'Sales');
        $this->db->or_like('jabatan', 'Medical Representative');
        $this->db->group_end();
        $this->db->order_by('nama', 'ASC');
        $page_data['sales_list'] = $this->db->get()->result();

        $page_data['kode_masuk_auto'] = $this->md_po_system->generateKodeMasuk();

        $page_data['page_name']  = 'po/v_po_form';
        $page_data['page_title'] = 'Input PO Baru';
        $page_data['page_desc']  = 'Penerimaan Dokumen Purchase Order Masuk';
        $page_data['switch']     = $this->id_navbar();

        $this->load->view('index', $page_data);
    }

    /**
     * Simpan Data PO Baru
     */
    public function simpan()
    {
        $no_po          = trim($this->input->post('no_po'));
        $tanggal_po     = $this->input->post('tanggal_po');
        $tanggal_terima  = $this->input->post('tanggal_terima') ?: date('Y-m-d');
        $id_customer    = $this->input->post('id_customer');
        $nama_customer  = trim($this->input->post('nama_customer'));
        $alamat_customer= trim($this->input->post('alamat_customer'));
        $id_sales       = $this->input->post('id_sales');
        $nama_sales     = trim($this->input->post('nama_sales'));
        $keterangan     = trim($this->input->post('keterangan'));

        if (empty($no_po) || empty($tanggal_po)) {
            $this->session->set_flashdata('error', 'Nomor PO dan Tanggal PO wajib diisi!');
            redirect('po_system/tambah');
            return;
        }

        // Handle upload berkas atau link file PO
        $file_po = '';
        $link_external = trim($this->input->post('link_file_po'));
        if (!empty($link_external)) {
            $file_po = $link_external;
        } elseif (!empty($_FILES['file_po']['name'])) {
            $config['upload_path']   = './uploads/po/';
            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['max_size']      = 10240; // 10MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('file_po')) {
                $upload_data = $this->upload->data();
                $file_po = base_url('uploads/po/' . $upload_data['file_name']);
            } else {
                $this->session->set_flashdata('error', 'Upload Berkas Gagal: ' . $this->upload->display_errors('', ''));
                redirect('po_system/tambah');
                return;
            }
        }

        // Hitung total nominal dari item-item
        $items_raw  = $this->input->post('items');
        $nominal_po = 0;
        $items_data = [];

        if (!empty($items_raw) && is_array($items_raw)) {
            foreach ($items_raw as $item) {
                $nama_barang = trim($item['nama_barang'] ?? '');
                if (empty($nama_barang)) continue;

                $qty    = max(1, floatval($item['qty'] ?? 1));
                $satuan = trim($item['satuan'] ?? 'Unit');
                $harga  = floatval(str_replace(['.', ','], ['', '.'], $item['harga'] ?? 0));
                $total  = $qty * $harga;
                $nominal_po += $total;

                $items_data[] = [
                    'nama_barang'  => $nama_barang,
                    'qty'          => $qty,
                    'satuan'       => $satuan,
                    'harga_satuan' => $harga,
                    'total_harga'  => $total,
                    'catatan'      => trim($item['catatan'] ?? '')
                ];
            }
        }

        // Jika user menginput nominal manual jika tanpa rincian item
        $nominal_manual = floatval(str_replace(['.', ','], ['', '.'], $this->input->post('nominal_po') ?? 0));
        if ($nominal_po <= 0 && $nominal_manual > 0) {
            $nominal_po = $nominal_manual;
        }

        $user = $this->md_pengguna->getById(sessPenggunaId());
        $nama_pembuat = is_array($user) ? $user[0]->nama : ($user->nama ?? 'Admin');

        $data_insert = [
            'kode_masuk'     => $this->md_po_system->generateKodeMasuk(),
            'no_po'          => $no_po,
            'tanggal_po'     => $tanggal_po,
            'tanggal_terima' => $tanggal_terima,
            'id_customer'    => !empty($id_customer) ? $id_customer : null,
            'nama_customer'  => $nama_customer,
            'alamat_customer'=> $alamat_customer,
            'id_sales'       => !empty($id_sales) ? $id_sales : null,
            'nama_sales'     => $nama_sales,
            'nominal_po'     => $nominal_po,
            'file_po'        => $file_po,
            'keterangan'     => $keterangan,
            'status'         => 'menunggu_kroscek',
            'created_by'     => sessPenggunaId(),
            'created_at'     => date('Y-m-d H:i:s')
        ];

        $id_po = $this->md_po_system->insertPo($data_insert);

        // Insert items jika ada
        if (!empty($items_data)) {
            foreach ($items_data as &$it) {
                $it['id_po'] = $id_po;
            }
            $this->md_po_system->insertItems($items_data);
        }

        // Catat log
        $this->md_po_system->addLog($id_po, null, 'menunggu_kroscek', 'PO baru diinput oleh ' . $nama_pembuat, sessPenggunaId(), $nama_pembuat);

        $this->session->set_flashdata('success', 'Data PO No. ' . $no_po . ' berhasil diinput dan sedang menunggu kroscek CRO!');
        redirect('po_system/detail/' . encrypt($id_po));
    }

    /**
     * Halaman Detail & Kroscek PO
     */
    public function detail($id_encrypted)
    {
        $id_po = decrypt($id_encrypted);
        $po    = $this->md_po_system->getPoById($id_po);

        if (!$po) {
            show_404();
            return;
        }

        $page_data['po']         = $po;
        $page_data['items']      = $this->md_po_system->getItemsByPoId($id_po);
        $page_data['logs']       = $this->md_po_system->getLogsByPoId($id_po);
        $page_data['id_encrypt'] = $id_encrypted;

        $user = $this->md_pengguna->getById(sessPenggunaId());
        $my_jabatan = is_array($user) ? $user[0]->jabatan : ($user->jabatan ?? '');
        $page_data['my_jabatan'] = $my_jabatan;
        $page_data['can_kroscek'] = (isAdmin() || isCRO() || stripos($my_jabatan, 'CRO') !== false);

        $page_data['page_name']  = 'po/v_po_detail';
        $page_data['page_title'] = 'Detail & Kroscek PO';
        $page_data['page_desc']  = 'PO No: ' . $po->no_po;
        $page_data['switch']     = $this->id_navbar();

        $this->load->view('index', $page_data);
    }

    /**
     * Verifikasi Kroscek oleh CRO / Admin
     */
    public function verifikasi()
    {
        $id_encrypted = $this->input->post('id_po');
        $id_po        = decrypt($id_encrypted);
        $po           = $this->md_po_system->getPoById($id_po);

        if (!$po) {
            show_404();
            return;
        }

        $user = $this->md_pengguna->getById(sessPenggunaId());
        $my_jabatan = is_array($user) ? $user[0]->jabatan : ($user->jabatan ?? '');
        $can_kroscek = (isAdmin() || isCRO() || stripos($my_jabatan, 'CRO') !== false);

        if (!$can_kroscek) {
            $this->session->set_flashdata('error', 'Hanya CRO atau Administrator yang memiliki otoritas untuk memverifikasi kroscek PO!');
            redirect('po_system/detail/' . $id_encrypted);
            return;
        }

        $status_baru     = $this->input->post('status_kroscek');
        $catatan_kroscek = trim($this->input->post('catatan_kroscek'));

        if (!in_array($status_baru, ['sesuai', 'revisi', 'ditolak'])) {
            $this->session->set_flashdata('error', 'Status verifikasi kroscek tidak valid!');
            redirect('po_system/detail/' . $id_encrypted);
            return;
        }

        if (($status_baru == 'revisi' || $status_baru == 'ditolak') && empty($catatan_kroscek)) {
            $this->session->set_flashdata('error', 'Wajib mencantumkan catatan jika status ' . ($status_baru == 'revisi' ? 'Perlu Revisi' : 'Ditolak') . '!');
            redirect('po_system/detail/' . $id_encrypted);
            return;
        }

        $nama_verifier = is_array($user) ? $user[0]->nama : ($user->nama ?? 'CRO');

        $data_update = [
            'status'          => $status_baru,
            'catatan_kroscek' => $catatan_kroscek,
            'dikroscek_oleh'  => sessPenggunaId(),
            'tgl_kroscek'     => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s')
        ];

        $this->md_po_system->updatePo($id_po, $data_update);

        $label_status = [
            'sesuai'  => 'Sesuai / Disetujui',
            'revisi'  => 'Perlu Revisi',
            'ditolak' => 'Ditolak'
        ];

        $log_catatan = 'Kroscek oleh ' . $nama_verifier . ' (' . $my_jabatan . '): ' . $label_status[$status_baru];
        if (!empty($catatan_kroscek)) {
            $log_catatan .= " - Catatan: " . $catatan_kroscek;
        }

        $this->md_po_system->addLog($id_po, $po->status, $status_baru, $log_catatan, sessPenggunaId(), $nama_verifier);

        // Jika status baru adalah 'sesuai' (di-ACC CRO), kirim notifikasi ke grup WhatsApp MARKETING PT. VYM via Fonnte
        if ($status_baru == 'sesuai') {
            $nama_cust = !empty($po->nama_customer) ? $po->nama_customer : ($po->master_nama_customer ?? '-');
            waPoLolosKroscekGroup([
                'no_po'         => $po->no_po,
                'nama_customer' => $nama_cust,
                'nama_sales'    => $po->nama_sales,
                'nominal_po'    => $po->nominal_po,
                'verifier'      => $nama_verifier,
                'catatan'       => $catatan_kroscek,
                'link'          => base_url('po_system/detail/' . $id_encrypted)
            ]);
        }

        $this->session->set_flashdata('success', 'Status Kroscek PO berhasil diperbarui menjadi: ' . $label_status[$status_baru] . '!');
        redirect('po_system/detail/' . $id_encrypted);
    }

    /**
     * Hapus PO (Soft Delete)
     */
    public function hapus($id_encrypted)
    {
        $id_po = decrypt($id_encrypted);
        $po    = $this->md_po_system->getPoById($id_po);

        if (!$po) {
            show_404();
            return;
        }

        $this->md_po_system->deletePo($id_po);

        $user = $this->md_pengguna->getById(sessPenggunaId());
        $nama_user = is_array($user) ? $user[0]->nama : ($user->nama ?? 'Admin');
        $this->md_po_system->addLog($id_po, $po->status, 'deleted', 'PO dihapus oleh ' . $nama_user, sessPenggunaId(), $nama_user);

        $this->session->set_flashdata('success', 'Data PO No. ' . $po->no_po . ' berhasil dihapus.');
        redirect('po_system');
    }
}
