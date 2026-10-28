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
        $from = $this->input->get('from');
        if ($from === 'marketing' || $from === 'helpdesk') {
            $this->session->set_userdata('po_from_nav', $from);
            return $from;
        }

        $session_nav = $this->session->userdata('po_from_nav');
        if (!empty($session_nav)) {
            return $session_nav;
        }

        $referer = $this->input->server('HTTP_REFERER');
        if (!empty($referer)) {
            if (strpos($referer, 'marketing') !== false) {
                $this->session->set_userdata('po_from_nav', 'marketing');
                return 'marketing';
            } elseif (strpos($referer, 'helpdesk') !== false) {
                $this->session->set_userdata('po_from_nav', 'helpdesk');
                return 'helpdesk';
            }
        }

        // Jika pengguna divisi marketing, default ke marketing
        if (isTeamMarketing() && !isAdmin() && !isCRO()) {
            return 'marketing';
        }

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

        $status_proses_filter = $this->input->get('status_proses');
        if (!in_array($status_proses_filter, ['BELUM DI PROSES', 'PROSES SEBAGIAN', 'SUDAH DI PROSES', 'HOLD / INDENT', 'DITOLAK'])) {
            $status_proses_filter = null;
        }

        $sales_filter = $this->input->get('sales') ? intval($this->input->get('sales')) : null;
        $tgl_awal     = $this->input->get('tgl_awal');
        $tgl_akhir    = $this->input->get('tgl_akhir');

        $page_data['po_list']           = $this->md_po_system->getAllPo($status_filter, $status_proses_filter, $sales_filter, $tgl_awal, $tgl_akhir);
        $page_data['stats']             = $this->md_po_system->getStatistik();
        $page_data['cur_status']        = $status_filter;
        $page_data['cur_status_proses'] = $status_proses_filter;
        $page_data['cur_sales']         = $sales_filter;
        $page_data['cur_tgl_awal']      = $tgl_awal;
        $page_data['cur_tgl_akhir']     = $tgl_akhir;

        // Ambil list sales untuk filter/cetak
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
        // 1. Ambil data master customer dari Inventory -> Master Data -> Customer
        $this->db->select('id_customer, nama_customer, alamat_customer, contact');
        $this->db->from('customer');
        $this->db->where('status', 1);
        $this->db->order_by('nama_customer', 'ASC');
        $page_data['customer_list'] = $this->db->get()->result();
        $page_data['customers']     = $page_data['customer_list'];

        // Backup master pelanggan (pelanggan) jika sewaktu-waktu dibutuhkan
        $this->db->select('id_pelanggan, identitas_pelanggan, alamat, npwp, pengiriman_dokumen, alamat_pengiriman_dokumen, kontak');
        $this->db->from('pelanggan');
        $this->db->where('status', '1');
        $this->db->order_by('identitas_pelanggan', 'ASC');
        $page_data['pelanggan_list'] = $this->db->get()->result();

        // 2. Ambil master barang (barang) dari inventory
        $this->db->select('id_barang, nama_barang, tipe, kode_barang');
        $this->db->from('barang');
        $this->db->where('status', 1);
        $this->db->order_by('nama_barang', 'ASC');
        $page_data['barang_list'] = $this->db->get()->result();

        // 3. Ambil data sales/marketing
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

        $user = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['current_user'] = is_array($user) ? $user[0] : $user;

        $page_data['page_name']  = 'po/v_po_form';
        $page_data['page_title'] = 'Input PO Masuk';
        $page_data['page_desc']  = 'Penerimaan Dokumen Purchase Order Customer';
        $page_data['switch']     = $this->id_navbar();

        $this->load->view('index', $page_data);
    }

    /**
     * Simpan Data PO Baru
     */
    public function simpan()
    {
        $no_po           = trim($this->input->post('no_po'));
        $tanggal_po      = $this->input->post('tanggal_po');
        $tanggal_terima   = $this->input->post('tanggal_terima') ?: date('Y-m-d');
        
        $id_pelanggan    = $this->input->post('id_pelanggan') ?: null;
        $id_customer     = $this->input->post('id_customer') ?: null;
        $nama_customer   = trim($this->input->post('nama_customer'));
        $alamat_customer = trim($this->input->post('alamat_customer'));
        $npwp_customer   = trim($this->input->post('npwp_customer'));
        
        $alamat_pengiriman  = trim($this->input->post('alamat_pengiriman'));
        $pic_penerima_nama  = trim($this->input->post('pic_penerima_nama'));
        $pic_penerima_nohp  = trim($this->input->post('pic_penerima_nohp'));
        $pic_penagihan_nama = trim($this->input->post('pic_penagihan_nama'));
        $pic_penagihan_nohp = trim($this->input->post('pic_penagihan_nohp'));
        
        $sistem_pembayaran  = $this->input->post('sistem_pembayaran') ?: 'Cash';
        $cicilan_dp_persen  = floatval(str_replace(['.', ','], ['', '.'], $this->input->post('cicilan_dp_persen') ?? 0));
        $cicilan_kali       = intval($this->input->post('cicilan_kali') ?? 0);
        
        $sistem_perpajakan  = $this->input->post('sistem_perpajakan') ?: 'PPN';
        $no_npwp_ktp        = trim($this->input->post('no_npwp_ktp'));
        $info_pengiriman_faktur = $this->input->post('info_pengiriman_faktur') ?: 'DIMUKA';
        
        $dokumen_pembayaran_tipe   = $this->input->post('dokumen_pembayaran_tipe') ?: 'Hardfile';
        $dokumen_dilampirkan_raw   = $this->input->post('dokumen_dilampirkan');
        $dokumen_dilampirkan       = is_array($dokumen_dilampirkan_raw) ? implode(', ', $dokumen_dilampirkan_raw) : trim($dokumen_dilampirkan_raw ?? '');
        $alamat_pengiriman_dokumen = trim($this->input->post('alamat_pengiriman_dokumen'));
        
        $ongkir_ditanggung  = $this->input->post('ongkir_ditanggung') ?: 'PERUSAHAAN';
        $nominal_ongkir     = floatval(str_replace(['.', ','], ['', '.'], $this->input->post('nominal_ongkir') ?? 0));
        
        $link_po_customer   = trim($this->input->post('link_po_customer'));
        $link_penawaran     = trim($this->input->post('link_penawaran'));
        $link_approval_harga= trim($this->input->post('link_approval_harga'));
        
        $id_sales        = $this->input->post('id_sales');
        $nama_sales      = trim($this->input->post('nama_sales'));
        $keterangan      = trim($this->input->post('keterangan'));

        if (empty($no_po) || empty($tanggal_po)) {
            $this->session->set_flashdata('error', 'Nomor PO dan Tanggal PO wajib diisi!');
            redirect('po_system/tambah');
            return;
        }

        // Handle upload berkas atau link file PO
        $file_po = '';
        $link_external = !empty($link_po_customer) ? $link_po_customer : trim($this->input->post('link_file_po'));
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
        $items_raw          = $this->input->post('items');
        $nominal_subtotal   = 0;
        $items_data         = [];

        if (!empty($items_raw) && is_array($items_raw)) {
            foreach ($items_raw as $item) {
                $nama_barang = trim($item['nama_barang'] ?? '');
                if (empty($nama_barang)) continue;

                $id_barang   = !empty($item['id_barang']) ? intval($item['id_barang']) : null;
                $tipe_barang = trim($item['tipe_barang'] ?? '');
                $qty         = max(1, floatval($item['qty'] ?? 1));
                $satuan      = trim($item['satuan'] ?? 'Unit');
                $harga       = floatval(str_replace(['.', ','], ['', '.'], $item['harga'] ?? 0));
                $total       = $qty * $harga;
                $nominal_subtotal += $total;

                $items_data[] = [
                    'id_barang'    => $id_barang,
                    'nama_barang'  => $nama_barang,
                    'tipe_barang'  => $tipe_barang,
                    'qty'          => $qty,
                    'satuan'       => $satuan,
                    'harga_satuan' => $harga,
                    'total_harga'  => $total,
                    'catatan'      => trim($item['catatan'] ?? '')
                ];
            }
        }

        // Jika subtotal kosong tapi nominal_po manual diisi
        $nominal_manual = floatval(str_replace(['.', ','], ['', '.'], $this->input->post('nominal_po') ?? 0));
        if ($nominal_subtotal <= 0 && $nominal_manual > 0) {
            $nominal_subtotal = $nominal_manual;
        }

        // Hitung PPN / Pajak jika sistem perpajakan PPN atau ENDORSEMENT (PPh 11%)
        $nominal_ppn = 0;
        if (in_array($sistem_perpajakan, ['PPN', 'ENDORSEMENT'])) {
            $nominal_ppn = round($nominal_subtotal * 0.11, 2);
        }

        // Hitung Total Akhir = Subtotal + Pajak + Ongkir
        $nominal_total_akhir = $nominal_subtotal + $nominal_ppn + $nominal_ongkir;

        $user = $this->md_pengguna->getById(sessPenggunaId());
        $nama_pembuat = is_array($user) ? $user[0]->nama : ($user->nama ?? 'Marketing');

        $kode_masuk = $this->md_po_system->generateKodeMasuk();

        $data_insert = [
            'kode_masuk'               => $kode_masuk,
            'no_po'                    => $no_po,
            'tanggal_po'               => $tanggal_po,
            'tanggal_terima'           => $tanggal_terima,
            'id_pelanggan'             => $id_pelanggan,
            'id_customer'              => $id_customer,
            'nama_customer'            => $nama_customer,
            'alamat_customer'          => $alamat_customer,
            'npwp_customer'            => $npwp_customer,
            'alamat_pengiriman'        => $alamat_pengiriman,
            'pic_penerima_nama'        => $pic_penerima_nama,
            'pic_penerima_nohp'        => $pic_penerima_nohp,
            'pic_penagihan_nama'       => $pic_penagihan_nama,
            'pic_penagihan_nohp'       => $pic_penagihan_nohp,
            'sistem_pembayaran'        => $sistem_pembayaran,
            'cicilan_dp_persen'        => $cicilan_dp_persen,
            'cicilan_kali'             => $cicilan_kali,
            'sistem_perpajakan'        => $sistem_perpajakan,
            'no_npwp_ktp'              => $no_npwp_ktp,
            'info_pengiriman_faktur'   => $info_pengiriman_faktur,
            'dokumen_pembayaran_tipe'  => $dokumen_pembayaran_tipe,
            'dokumen_dilampirkan'      => $dokumen_dilampirkan,
            'alamat_pengiriman_dokumen'=> $alamat_pengiriman_dokumen,
            'ongkir_ditanggung'        => $ongkir_ditanggung,
            'nominal_subtotal'         => $nominal_subtotal,
            'nominal_ppn'              => $nominal_ppn,
            'nominal_ongkir'           => $nominal_ongkir,
            'nominal_total_akhir'      => $nominal_total_akhir,
            'nominal_po'               => $nominal_total_akhir ?: $nominal_subtotal,
            'link_po_customer'         => $link_po_customer,
            'link_penawaran'           => $link_penawaran,
            'link_approval_harga'      => $link_approval_harga,
            'file_po'                  => $file_po,
            'id_sales'                 => !empty($id_sales) ? $id_sales : sessPenggunaId(),
            'nama_sales'               => !empty($nama_sales) ? $nama_sales : $nama_pembuat,
            'keterangan'               => $keterangan,
            'status'                   => 'menunggu_kroscek',
            'status_proses'            => 'BELUM DI PROSES',
            'created_by'               => sessPenggunaId(),
            'created_at'               => date('Y-m-d H:i:s')
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

        // Notifikasi WA Progres Approval ke Grup Marketing
        $po_new = $this->md_po_system->getPoById($id_po);
        waPoProgressGroup([
            'po'            => $po_new,
            'stage'         => 'baru',
            'action_by'     => !empty($nama_sales) ? $nama_sales : $nama_pembuat,
            'action_status' => 'baru',
            'link'          => base_url('po_system/detail/' . encrypt($id_po))
        ]);

        $this->session->set_flashdata('success', 'Data PO No. ' . $no_po . ' (' . $kode_masuk . ') berhasil diinput dan sedang menunggu kroscek CRO!');
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
        $my_level   = is_array($user) ? $user[0]->level : ($user->level ?? '');
        $page_data['my_jabatan'] = $my_jabatan;

        // Otorisasi Tahapan Approval
        $page_data['can_cro']   = (isAdmin() || stripos($my_jabatan, 'CRO') !== false || stripos($my_jabatan, 'Customer Relationship') !== false);
        $page_data['can_acc']   = (isAdmin() || stripos($my_jabatan, 'Accounting') !== false || stripos($my_jabatan, 'Finance') !== false);
        $page_data['can_pajak'] = (isAdmin() || stripos($my_jabatan, 'Tax') !== false || stripos($my_jabatan, 'Pajak') !== false);
        $page_data['can_gm']    = (isAdmin() || stripos($my_jabatan, 'General Manager') !== false || stripos($my_jabatan, 'Direktur') !== false || stripos($my_jabatan, 'Director') !== false || $my_level == 'Hrd');
        $page_data['can_upload_doc'] = ($page_data['can_cro'] || $page_data['can_acc'] || $page_data['can_pajak'] || $page_data['can_gm'] || stripos($my_jabatan, 'Gudang') !== false || stripos($my_jabatan, 'Warehouse') !== false || stripos($my_jabatan, 'Logistik') !== false);
        $page_data['can_update_inden'] = ($page_data['can_cro'] || $page_data['can_gm'] || stripos($my_jabatan, 'Gudang') !== false || stripos($my_jabatan, 'Warehouse') !== false || stripos($my_jabatan, 'Logistik') !== false || stripos($my_jabatan, 'Accounting') !== false);
        $page_data['count_indent']     = $this->md_po_system->countIndentItems($id_po);
        $page_data['indent_items']     = $this->md_po_system->getIndentItemsByPoId($id_po);

        $page_data['page_name']  = 'po/v_po_detail';
        $page_data['page_title'] = 'Detail & Kroscek PO';
        $page_data['page_desc']  = 'PO No: ' . $po->no_po . ' (' . $po->kode_masuk . ')';
        $page_data['switch']     = $this->id_navbar();

        $this->load->view('index', $page_data);
    }

    /**
     * Verifikasi Tahap 1: Approval CRO
     */
    public function verifikasi_cro()
    {
        $id_encrypted = $this->input->post('id_po');
        $id_po        = decrypt($id_encrypted);
        $po           = $this->md_po_system->getPoById($id_po);

        if (!$po) {
            show_404();
            return;
        }

        $user = $this->md_pengguna->getById(sessPenggunaId());
        $nama_user  = is_array($user) ? $user[0]->nama : ($user->nama ?? 'CRO');
        $my_jabatan = is_array($user) ? $user[0]->jabatan : ($user->jabatan ?? '');
        $can_cro    = (isAdmin() || stripos($my_jabatan, 'CRO') !== false || stripos($my_jabatan, 'Customer Relationship') !== false);

        if (!$can_cro) {
            $this->session->set_flashdata('error', 'Hanya CRO atau Administrator yang memiliki hak akses untuk approval tahap CRO!');
            redirect('po_system/detail/' . $id_encrypted);
            return;
        }

        $cro_cek_data        = $this->input->post('cro_cek_data');
        $cro_cek_piutang     = $this->input->post('cro_cek_piutang');
        $cro_link_approval_po= trim($this->input->post('cro_link_approval_po'));
        $cro_cek_stok        = $this->input->post('cro_cek_stok');
        $cro_link_pjb        = trim($this->input->post('cro_link_pjb'));
        $cro_link_approval_po_doc = trim($this->input->post('cro_link_approval_po_doc'));
        $cro_catatan         = trim($this->input->post('cro_catatan'));
        $cro_status          = $this->input->post('cro_status');

        $status_po_baru = $po->status;
        $status_proses_baru = $po->status_proses;
        $ready_count = 0;
        $indent_count = 0;

        if ($cro_cek_stok === 'STOK TIDAK READY' || $cro_status === 'hold_indent') {
            $status_po_baru = 'revisi';
            $status_proses_baru = 'HOLD / INDENT';
            $cro_status = 'hold_indent';

            // Seluruh item ditandai inden
            $this->db->where('id_po', $id_po)->update('po_masuk_item', [
                'status_item'   => 'indent',
                'tanggal_ready' => null
            ]);
        } elseif ($cro_status === 'sesuai') {
            if ($cro_cek_stok === 'PROSES SEBAGIAN') {
                $status_proses_baru = 'PROSES SEBAGIAN';

                $item_statuses = $this->input->post('item_status');
                $item_tanggals = $this->input->post('item_tanggal');
                $item_catatans = $this->input->post('item_catatan');

                if (is_array($item_statuses)) {
                    foreach ($item_statuses as $id_item => $st) {
                        $st_clean = ($st === 'indent') ? 'indent' : 'ready';
                        $tgl = !empty($item_tanggals[$id_item]) ? $item_tanggals[$id_item] : ($st_clean === 'ready' ? date('Y-m-d') : null);
                        $cat = !empty($item_catatans[$id_item]) ? trim($item_catatans[$id_item]) : null;

                        $this->md_po_system->updateItem(intval($id_item), [
                            'status_item'   => $st_clean,
                            'tanggal_ready' => $tgl,
                            'catatan_item'  => $cat
                        ]);

                        if ($st_clean === 'ready') $ready_count++;
                        else $indent_count++;
                    }
                }
            } else {
                // STOK READY WAREHOUSE (Seluruh item siap kirim)
                $this->db->where('id_po', $id_po)->update('po_masuk_item', [
                    'status_item'   => 'ready',
                    'tanggal_ready' => date('Y-m-d')
                ]);
            }
            // Tetap status sesuai untuk tahap CRO
            $status_po_baru = 'sesuai';
        } elseif ($cro_status === 'ditolak') {
            $status_po_baru = 'ditolak';
            $status_proses_baru = 'DITOLAK';
        } elseif ($cro_status === 'revisi') {
            $status_po_baru = 'revisi';
        }

        $data_update = [
            'cro_cek_data'             => $cro_cek_data,
            'cro_cek_piutang'          => $cro_cek_piutang,
            'cro_link_approval_po'     => $cro_link_approval_po,
            'cro_cek_stok'             => $cro_cek_stok,
            'cro_link_pjb'             => $cro_link_pjb,
            'cro_link_approval_po_doc' => $cro_link_approval_po_doc,
            'cro_catatan'              => $cro_catatan,
            'cro_status'               => $cro_status,
            'cro_user_id'              => sessPenggunaId(),
            'cro_date'                 => date('Y-m-d H:i:s'),
            'status'                   => $status_po_baru,
            'status_proses'            => $status_proses_baru,
            'updated_at'               => date('Y-m-d H:i:s')
        ];

        $this->md_po_system->updatePo($id_po, $data_update);

        $logMsg = "Approval CRO oleh " . $nama_user . " (" . strtoupper($cro_status) . ") - Stok: " . $cro_cek_stok;
        if ($cro_cek_stok === 'PROSES SEBAGIAN') {
            $logMsg .= " (" . $ready_count . " Barang Ready, " . $indent_count . " Barang Inden)";
        }
        if (!empty($cro_catatan)) $logMsg .= " | Catatan: " . $cro_catatan;
        $this->md_po_system->addLog($id_po, $po->status, $status_po_baru, $logMsg, sessPenggunaId(), $nama_user);

        // Notifikasi WA Progres Approval ke Grup Marketing
        $po_updated = $this->md_po_system->getPoById($id_po);
        waPoProgressGroup([
            'po'            => $po_updated,
            'stage'         => 'cro',
            'action_by'     => $nama_user,
            'action_status' => $cro_status,
            'link'          => base_url('po_system/detail/' . $id_encrypted)
        ]);

        $this->session->set_flashdata('success', 'Verifikasi tahap CRO berhasil disimpan!');
        redirect('po_system/detail/' . $id_encrypted);
    }

    /**
     * Verifikasi Tahap 2: Approval Accounting
     */
    public function verifikasi_acc()
    {
        $id_encrypted = $this->input->post('id_po');
        $id_po        = decrypt($id_encrypted);
        $po           = $this->md_po_system->getPoById($id_po);

        if (!$po) {
            show_404();
            return;
        }

        $user = $this->md_pengguna->getById(sessPenggunaId());
        $nama_user  = is_array($user) ? $user[0]->nama : ($user->nama ?? 'Accounting');
        $my_jabatan = is_array($user) ? $user[0]->jabatan : ($user->jabatan ?? '');
        $can_acc    = (isAdmin() || stripos($my_jabatan, 'Accounting') !== false || stripos($my_jabatan, 'Finance') !== false);

        if (!$can_acc) {
            $this->session->set_flashdata('error', 'Hanya Tim Accounting/Finance atau Administrator yang berhak melakukan verifikasi ini!');
            redirect('po_system/detail/' . $id_encrypted);
            return;
        }

        if ($po->cro_status !== 'sesuai') {
            $statusLabel = ($po->cro_status === 'hold_indent') ? 'HOLD / INDENT (Stok Belum Ready)' : strtoupper($po->cro_status ?: 'Menunggu Kroscek');
            $this->session->set_flashdata('error', 'Tahap CRO belum disetujui (Status CRO: ' . $statusLabel . '). Verifikasi Accounting belum dapat dilakukan!');
            redirect('po_system/detail/' . $id_encrypted);
            return;
        }

        $acc_cek_harga         = $this->input->post('acc_cek_harga');
        $acc_sistem_harga      = $this->input->post('acc_sistem_harga');
        $acc_sistem_pembayaran = $this->input->post('acc_sistem_pembayaran');
        $acc_catatan           = trim($this->input->post('acc_catatan'));
        $acc_status            = $this->input->post('acc_status');

        $data_update = [
            'acc_cek_harga'         => $acc_cek_harga,
            'acc_sistem_harga'      => $acc_sistem_harga,
            'acc_sistem_pembayaran' => $acc_sistem_pembayaran,
            'acc_catatan'           => $acc_catatan,
            'acc_status'            => $acc_status,
            'acc_user_id'           => sessPenggunaId(),
            'acc_date'              => date('Y-m-d H:i:s'),
            'updated_at'            => date('Y-m-d H:i:s')
        ];

        if ($acc_status === 'ditolak') {
            $data_update['status'] = 'ditolak';
            $data_update['status_proses'] = 'DITOLAK';
        } elseif ($acc_status === 'revisi') {
            $data_update['status'] = 'revisi';
        }

        $this->md_po_system->updatePo($id_po, $data_update);

        $logMsg = "Approval Accounting oleh " . $nama_user . " (" . strtoupper($acc_status) . ")";
        if (!empty($acc_catatan)) $logMsg .= " | Catatan: " . $acc_catatan;
        $this->md_po_system->addLog($id_po, $po->status, $data_update['status'] ?? $po->status, $logMsg, sessPenggunaId(), $nama_user);

        // Notifikasi WA Progres Approval ke Grup Marketing
        $po_updated = $this->md_po_system->getPoById($id_po);
        waPoProgressGroup([
            'po'            => $po_updated,
            'stage'         => 'acc',
            'action_by'     => $nama_user,
            'action_status' => $acc_status,
            'link'          => base_url('po_system/detail/' . $id_encrypted)
        ]);

        $this->session->set_flashdata('success', 'Verifikasi tahap Accounting berhasil disimpan!');
        redirect('po_system/detail/' . $id_encrypted);
    }

    /**
     * Verifikasi Tahap 3: Approval Pajak
     */
    public function verifikasi_pajak()
    {
        $id_encrypted = $this->input->post('id_po');
        $id_po        = decrypt($id_encrypted);
        $po           = $this->md_po_system->getPoById($id_po);

        if (!$po) {
            show_404();
            return;
        }

        $user = $this->md_pengguna->getById(sessPenggunaId());
        $nama_user  = is_array($user) ? $user[0]->nama : ($user->nama ?? 'Pajak');
        $my_jabatan = is_array($user) ? $user[0]->jabatan : ($user->jabatan ?? '');
        $can_pajak  = (isAdmin() || stripos($my_jabatan, 'Tax') !== false || stripos($my_jabatan, 'Pajak') !== false);

        if (!$can_pajak) {
            $this->session->set_flashdata('error', 'Hanya Tim Pajak atau Administrator yang berhak melakukan verifikasi ini!');
            redirect('po_system/detail/' . $id_encrypted);
            return;
        }

        if ($po->acc_status !== 'sesuai') {
            $this->session->set_flashdata('error', 'Tahap Accounting belum disetujui (Status ACC: ' . strtoupper($po->acc_status ?: 'Menunggu') . '). Verifikasi Pajak belum dapat dilakukan!');
            redirect('po_system/detail/' . $id_encrypted);
            return;
        }

        $pajak_cek_pajak           = $this->input->post('pajak_cek_pajak');
        $pajak_sistem_perpajakan   = $this->input->post('pajak_sistem_perpajakan');
        $pajak_link_approval_faktur= trim($this->input->post('pajak_link_approval_faktur'));
        $pajak_catatan             = trim($this->input->post('pajak_catatan'));
        $pajak_status              = $this->input->post('pajak_status');

        $data_update = [
            'pajak_cek_pajak'           => $pajak_cek_pajak,
            'pajak_sistem_perpajakan'   => $pajak_sistem_perpajakan,
            'pajak_link_approval_faktur'=> $pajak_link_approval_faktur,
            'pajak_catatan'             => $pajak_catatan,
            'pajak_status'              => $pajak_status,
            'pajak_user_id'             => sessPenggunaId(),
            'pajak_date'                => date('Y-m-d H:i:s'),
            'updated_at'                => date('Y-m-d H:i:s')
        ];

        if ($pajak_status === 'ditolak') {
            $data_update['status'] = 'ditolak';
            $data_update['status_proses'] = 'DITOLAK';
        } elseif ($pajak_status === 'revisi') {
            $data_update['status'] = 'revisi';
        }

        $this->md_po_system->updatePo($id_po, $data_update);

        $logMsg = "Approval Pajak oleh " . $nama_user . " (" . strtoupper($pajak_status) . ")";
        if (!empty($pajak_catatan)) $logMsg .= " | Catatan: " . $pajak_catatan;
        $this->md_po_system->addLog($id_po, $po->status, $data_update['status'] ?? $po->status, $logMsg, sessPenggunaId(), $nama_user);

        // Notifikasi WA Progres Approval ke Grup Marketing
        $po_updated = $this->md_po_system->getPoById($id_po);
        waPoProgressGroup([
            'po'            => $po_updated,
            'stage'         => 'pajak',
            'action_by'     => $nama_user,
            'action_status' => $pajak_status,
            'link'          => base_url('po_system/detail/' . $id_encrypted)
        ]);

        $this->session->set_flashdata('success', 'Verifikasi tahap Pajak berhasil disimpan!');
        redirect('po_system/detail/' . $id_encrypted);
    }

    /**
     * Verifikasi Tahap 4: Approval General Manager (GM)
     */
    public function verifikasi_gm()
    {
        $id_encrypted = $this->input->post('id_po');
        $id_po        = decrypt($id_encrypted);
        $po           = $this->md_po_system->getPoById($id_po);

        if (!$po) {
            show_404();
            return;
        }

        $user = $this->md_pengguna->getById(sessPenggunaId());
        $nama_user  = is_array($user) ? $user[0]->nama : ($user->nama ?? 'GM');
        $my_jabatan = is_array($user) ? $user[0]->jabatan : ($user->jabatan ?? '');
        $my_level   = is_array($user) ? $user[0]->level : ($user->level ?? '');
        $can_gm     = (isAdmin() || stripos($my_jabatan, 'General Manager') !== false || stripos($my_jabatan, 'Direktur') !== false || stripos($my_jabatan, 'Director') !== false || $my_level == 'Hrd');

        if (!$can_gm) {
            $this->session->set_flashdata('error', 'Hanya General Manager atau Administrator yang berhak memberikan approval persetujuan final!');
            redirect('po_system/detail/' . $id_encrypted);
            return;
        }

        if ($po->pajak_status !== 'sesuai') {
            $this->session->set_flashdata('error', 'Tahap Pajak belum disetujui (Status Pajak: ' . strtoupper($po->pajak_status ?: 'Menunggu') . '). Persetujuan Final GM belum dapat dilakukan!');
            redirect('po_system/detail/' . $id_encrypted);
            return;
        }

        $gm_catatan = trim($this->input->post('gm_catatan'));
        $gm_status  = $this->input->post('gm_status');

        $sisa_inden = $this->md_po_system->countIndentItems($id_po);
        $status_proses_baru = ($po->cro_cek_stok === 'PROSES SEBAGIAN' && $sisa_inden > 0) ? 'PROSES SEBAGIAN' : 'SUDAH DI PROSES';
        $status_po_baru = 'sesuai';

        if ($gm_status === 'ditolak') {
            $status_po_baru = 'ditolak';
            $status_proses_baru = 'DITOLAK';
        }

        $data_update = [
            'gm_catatan'    => $gm_catatan,
            'gm_status'     => $gm_status,
            'gm_user_id'    => sessPenggunaId(),
            'gm_date'       => date('Y-m-d H:i:s'),
            'status'        => $status_po_baru,
            'status_proses' => $status_proses_baru,
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $this->md_po_system->updatePo($id_po, $data_update);

        $logMsg = "Approval GM oleh " . $nama_user . " (" . strtoupper($gm_status) . ") - Status Proses: " . $status_proses_baru;
        if (!empty($gm_catatan)) $logMsg .= " | Catatan: " . $gm_catatan;
        $this->md_po_system->addLog($id_po, $po->status, $status_po_baru, $logMsg, sessPenggunaId(), $nama_user);

        // Notifikasi WA Progres Approval ke Grup Marketing
        $po_updated = $this->md_po_system->getPoById($id_po);
        waPoProgressGroup([
            'po'            => $po_updated,
            'stage'         => 'gm',
            'action_by'     => $nama_user,
            'action_status' => $gm_status,
            'link'          => base_url('po_system/detail/' . $id_encrypted)
        ]);

        $this->session->set_flashdata('success', 'Approval General Manager berhasil disimpan!');
        redirect('po_system/detail/' . $id_encrypted);
    }

    /**
     * Simpan Dokumen Pemenuhan / Kelengkapan (Fulfillment Post-Approval GM)
     */
    public function simpan_dokumen()
    {
        $id_encrypted = $this->input->post('id_po');
        $id_po        = decrypt($id_encrypted);
        $po           = $this->md_po_system->getPoById($id_po);

        if (!$po) {
            show_404();
            return;
        }

        $user = $this->md_pengguna->getById(sessPenggunaId());
        $nama_user = is_array($user) ? $user[0]->nama : ($user->nama ?? 'Staf');
        $divisi    = $this->input->post('divisi');

        $data_update = ['updated_at' => date('Y-m-d H:i:s')];
        $detailDocText = '';

        if ($divisi === 'acc') {
            $data_update['doc_acc_invoice']    = trim($this->input->post('doc_acc_invoice'));
            $data_update['doc_acc_kwitansi']   = trim($this->input->post('doc_acc_kwitansi'));
            $data_update['doc_acc_lainnya']    = trim($this->input->post('doc_acc_lainnya'));
            $data_update['doc_acc_updated_at'] = date('Y-m-d H:i:s');
            $detailDocText = 'Invoice / Kwitansi / Berkas Pembayaran';
        } elseif ($divisi === 'gudang') {
            $data_update['doc_gudang_sj']           = trim($this->input->post('doc_gudang_sj'));
            $data_update['doc_gudang_resi']         = trim($this->input->post('doc_gudang_resi'));
            $data_update['doc_gudang_packing_list'] = trim($this->input->post('doc_gudang_packing_list'));
            $data_update['doc_gudang_sstb']         = trim($this->input->post('doc_gudang_sstb'));
            $data_update['doc_gudang_updated_at']   = date('Y-m-d H:i:s');
            $detailDocText = 'Surat Jalan (SJ) / Resi / Packing List / SSTB';
        } elseif ($divisi === 'pajak') {
            $data_update['doc_pajak_faktur']            = trim($this->input->post('doc_pajak_faktur'));
            $data_update['doc_pajak_bukti_potong']       = trim($this->input->post('doc_pajak_bukti_potong'));
            $data_update['doc_pajak_ppbj_endorsement']   = trim($this->input->post('doc_pajak_ppbj_endorsement'));
            $data_update['doc_pajak_updated_at']        = date('Y-m-d H:i:s');
            $detailDocText = 'Faktur Pajak / Bukti Potong / PPBJ';
        } elseif ($divisi === 'cro') {
            $data_update['doc_cro_digital_delivery'] = trim($this->input->post('doc_cro_digital_delivery'));
            $data_update['doc_cro_updated_at']       = date('Y-m-d H:i:s');
            $detailDocText = 'Bukti Pengiriman Dokumen Digital Customer';
        }

        $this->md_po_system->updatePo($id_po, $data_update);

        $this->md_po_system->addLog($id_po, $po->status, $po->status, "Pembaruan Dokumen " . strtoupper($divisi) . " (" . $detailDocText . ") oleh " . $nama_user, sessPenggunaId(), $nama_user);

        // Notifikasi WA update dokumen ke grup
        waPoUpdateDokumenGroup([
            'no_po'      => $po->no_po,
            'divisi'     => $divisi,
            'user_nama'  => $nama_user,
            'detail_doc' => $detailDocText,
            'link'       => base_url('po_system/detail/' . $id_encrypted)
        ]);

        $this->session->set_flashdata('success', 'Dokumen kelengkapan ' . strtoupper($divisi) . ' berhasil diperbarui!');
        redirect('po_system/detail/' . $id_encrypted);
    }

    /**
     * Update Status Pemenuhan Barang Inden menjadi Ready (Bertanggal)
     */
    public function update_barang_inden()
    {
        $id_encrypted = $this->input->post('id_po');
        $id_po        = decrypt($id_encrypted);
        $po           = $this->md_po_system->getPoById($id_po);

        if (!$po) {
            show_404();
            return;
        }

        $user = $this->md_pengguna->getById(sessPenggunaId());
        $nama_user  = is_array($user) ? $user[0]->nama : ($user->nama ?? 'Staf');
        $my_jabatan = is_array($user) ? $user[0]->jabatan : ($user->jabatan ?? '');
        $my_level   = is_array($user) ? $user[0]->level : ($user->level ?? '');
        $can_update = (isAdmin() || stripos($my_jabatan, 'CRO') !== false || stripos($my_jabatan, 'Gudang') !== false || stripos($my_jabatan, 'Warehouse') !== false || stripos($my_jabatan, 'Logistik') !== false || stripos($my_jabatan, 'Accounting') !== false || stripos($my_jabatan, 'General Manager') !== false || $my_level == 'Hrd');

        if (!$can_update) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses untuk memperbarui status pemenuhan barang!');
            redirect('po_system/detail/' . $id_encrypted);
            return;
        }

        $items_ready   = $this->input->post('items_ready'); // Array of id_item
        $tanggal_ready = $this->input->post('tanggal_ready') ?: date('Y-m-d');
        $catatan_inden = trim($this->input->post('catatan_inden'));

        if (empty($items_ready) || !is_array($items_ready)) {
            $this->session->set_flashdata('error', 'Silakan pilih setidaknya satu barang inden yang telah ready/tiba di gudang!');
            redirect('po_system/detail/' . $id_encrypted);
            return;
        }

        $updated_names = [];
        foreach ($items_ready as $id_item) {
            $id_item = intval($id_item);
            $item = $this->db->get_where('po_masuk_item', ['id_item' => $id_item, 'id_po' => $id_po])->row();
            if ($item) {
                $this->md_po_system->updateItem($id_item, [
                    'status_item'   => 'ready',
                    'tanggal_ready' => $tanggal_ready,
                    'catatan_item'  => !empty($catatan_inden) ? $catatan_inden : $item->catatan_item
                ]);
                $updated_names[] = $item->nama_barang . ' (' . (float)$item->qty . ' ' . $item->satuan . ')';
            }
        }

        // Cek sisa barang inden yang belum ready
        $sisa_inden = $this->md_po_system->countIndentItems($id_po);
        $status_proses_baru = $po->status_proses;

        // Jika semua barang sudah ready, otomatis selesaikan status_proses jika GM sudah approve atau PO sedang proses sebagian/hold
        if ($sisa_inden === 0) {
            if ($po->gm_status === 'sesuai' || in_array($po->status_proses, ['PROSES SEBAGIAN', 'HOLD / INDENT'])) {
                $status_proses_baru = 'SUDAH DI PROSES';
                $this->md_po_system->updatePo($id_po, [
                    'status_proses' => 'SUDAH DI PROSES',
                    'updated_at'    => date('Y-m-d H:i:s')
                ]);
            }
        }

        $tgl_formatted = date('d/m/Y', strtotime($tanggal_ready));
        $logMsg = "Update Pemenuhan Barang Inden ➔ READY (Tgl: " . $tgl_formatted . ") oleh " . $nama_user . ". Barang: " . implode(', ', $updated_names);
        if (!empty($catatan_inden)) $logMsg .= " | Catatan: " . $catatan_inden;
        if ($sisa_inden === 0) {
            $logMsg .= " [SEMUA BARANG SUDAH READY ➔ Status Proses: " . $status_proses_baru . "]";
        } else {
            $logMsg .= " [Sisa " . $sisa_inden . " barang inden masih menunggu]";
        }

        $this->md_po_system->addLog($id_po, $po->status, $po->status, $logMsg, sessPenggunaId(), $nama_user);

        $this->session->set_flashdata('success', 'Berhasil memperbarui ' . count($updated_names) . ' barang inden menjadi READY (Tgl: ' . $tgl_formatted . ')!');
        redirect('po_system/detail/' . $id_encrypted);
    }

    /**
     * Legacy / Quick Verifikasi fallback
     */
    public function verifikasi()
    {
        $id_encrypted = $this->input->post('id_po');
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

    /**
     * Cetak Laporan Rekap PO
     */
    public function cetak()
    {
        $status_filter        = $this->input->get('status');
        $status_proses_filter = $this->input->get('status_proses');
        $sales_filter         = $this->input->get('sales') ? intval($this->input->get('sales')) : null;
        $tgl_awal             = $this->input->get('tgl_awal');
        $tgl_akhir            = $this->input->get('tgl_akhir');

        $data['po_list']       = $this->md_po_system->getAllPo($status_filter, $status_proses_filter, $sales_filter, $tgl_awal, $tgl_akhir);
        $data['tgl_awal']      = $tgl_awal;
        $data['tgl_akhir']     = $tgl_akhir;
        $data['status_proses'] = $status_proses_filter;
        $data['status_kroscek']= $status_filter;

        if ($sales_filter) {
            $userSales = $this->md_pengguna->getById($sales_filter);
            $data['nama_sales'] = is_array($userSales) ? $userSales[0]->nama : ($userSales->nama ?? '-');
        } else {
            $data['nama_sales'] = 'Semua Marketing';
        }

        $this->load->view('pages/po/v_po_print', $data);
    }
}
