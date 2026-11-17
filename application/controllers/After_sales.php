<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mpdf\Mpdf;

/**
 * Controller After_sales
 * Sistem Manajemen Layanan Purna Jual (After Sales Service & Maintenance)
 */
class After_sales extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('session');

        if (!sessPenggunaId()) {
            redirect('login');
        }

        $this->load->model('md_after_sales');
        $this->load->model('md_pengguna');
        $this->load->helper('whatsapp_helper');
    }

    public function id_navbar()
    {
        $from = $this->input->get('from');
        if (in_array($from, ['marketing', 'helpdesk', 'accounting', 'inventory'], true)) {
            $this->session->set_userdata('after_sales_from_nav', $from);
            return $from;
        }

        $session_nav = $this->session->userdata('after_sales_from_nav');
        if (!empty($session_nav)) {
            return $session_nav;
        }

        return 'helpdesk';
    }

    /**
     * Halaman Utama - Daftar Tiket After Sales Service
     */
    public function index()
    {
        grantAccessFor('all');

        $filter = [
            'status'        => $this->input->get('status'),
            'jenis_layanan' => $this->input->get('jenis_layanan'),
            'id_teknisi'    => $this->input->get('id_teknisi'),
            'tgl_awal'      => $this->input->get('tgl_awal'),
            'tgl_akhir'     => $this->input->get('tgl_akhir'),
            'search'        => $this->input->get('q')
        ];

        $page_data['switch']      = $this->id_navbar();
        $page_data['counts']      = $this->md_after_sales->countByStatus();
        $page_data['tickets']     = $this->md_after_sales->getAll($filter);
        $page_data['teknisi_list']= $this->md_after_sales->getTeknisiList();
        $page_data['filter']      = $filter;
        $page_data['page_name']   = 'after_sales/v_after_sales_list';
        $page_data['page_title']  = 'After Sales Service';
        $page_data['page_desc']   = 'Manajemen Servis & Layanan Purna Jual';

        $this->load->view('index', $page_data);
    }

    /**
     * Halaman Tambah Tiket Servis Baru
     */
    public function tambah()
    {
        grantAccessFor('all');

        $nomorData = $this->md_after_sales->generateNomorTiket();

        $page_data['switch']       = $this->id_navbar();
        $page_data['nomor_tiket']  = $nomorData['nomor_tiket'];
        $page_data['nomor_urut']   = $nomorData['nomor_urut'];
        $page_data['pelanggan_list'] = $this->md_after_sales->getPelangganList();
        $page_data['teknisi_list'] = $this->md_after_sales->getTeknisiList();
        $page_data['page_name']    = 'after_sales/v_after_sales_form';
        $page_data['page_title']   = 'Input Tiket After Sales Baru';
        $page_data['page_desc']    = 'Formulir Pendaftaran Servis & Purna Jual';

        $this->load->view('index', $page_data);
    }

    /**
     * Simpan Data Tiket Baru
     */
    public function simpan()
    {
        grantAccessFor('all');

        $nomorTiket = $this->input->post('nomor_tiket');
        $idPelanggan = $this->input->post('id_pelanggan');
        $namaPelanggan = trim($this->input->post('nama_pelanggan'));
        $alamat = trim($this->input->post('alamat_pelanggan'));
        $picNama = trim($this->input->post('pic_nama'));
        $picKontak = trim($this->input->post('pic_kontak'));
        $jenisLayanan = $this->input->post('jenis_layanan');
        $namaAlat = trim($this->input->post('nama_alat'));
        $merkTipe = trim($this->input->post('merk_tipe'));
        $nomorSeri = trim($this->input->post('nomor_seri'));
        $keluhan = trim($this->input->post('keluhan'));
        $tglMasuk = $this->input->post('tgl_masuk') ?: date('Y-m-d');
        $idTeknisi = $this->input->post('id_teknisi') ?: null;
        $statusPembayaran = $this->input->post('status_pembayaran') ?: 'berbayar';

        if (empty($namaPelanggan) || empty($namaAlat)) {
            $this->session->set_flashdata('error', 'Nama pelanggan dan nama alat wajib diisi!');
            redirect('after_sales/tambah');
            return;
        }

        // Ambil nama teknisi jika dipilih
        $namaTeknisi = null;
        if (!empty($idTeknisi)) {
            $t = $this->db->get_where('pengguna', ['pengguna_id' => $idTeknisi])->row();
            if ($t) {
                $namaTeknisi = $t->nama;
            }
        }

        // Generate nomor tiket otomatis yang selalu fresh dan unik
        $nomorData = $this->md_after_sales->generateNomorTiket($tglMasuk);
        $nomorTiket = $nomorData['nomor_tiket'];
        $nomorUrut = $nomorData['nomor_urut'];

        // Parsing item sparepart / jasa
        $itemJenis = $this->input->post('item_jenis') ?: [];
        $itemDeskripsi = $this->input->post('item_deskripsi') ?: [];
        $itemQty = $this->input->post('item_qty') ?: [];
        $itemSatuan = $this->input->post('item_satuan') ?: [];
        $itemHarga = $this->input->post('item_harga') ?: [];

        $items = [];
        $biayaSparepart = 0;
        $biayaJasa = 0;

        for ($i = 0; $i < count($itemDeskripsi); $i++) {
            $deskripsi = trim($itemDeskripsi[$i] ?? '');
            if (empty($deskripsi)) continue;

            $jenis = ($itemJenis[$i] ?? 'sparepart') === 'jasa' ? 'jasa' : 'sparepart';
            $qty = (int)($itemQty[$i] ?? 1);
            if ($qty < 1) $qty = 1;
            $satuan = trim($itemSatuan[$i] ?? 'Pcs');
            $harga = (float)str_replace(['.', ','], ['', '.'], $itemHarga[$i] ?? 0);
            $subtotal = $qty * $harga;

            if ($jenis === 'jasa') {
                $biayaJasa += $subtotal;
            } else {
                $biayaSparepart += $subtotal;
            }

            $items[] = [
                'jenis'        => $jenis,
                'deskripsi'    => $deskripsi,
                'qty'          => $qty,
                'satuan'       => $satuan,
                'harga_satuan' => $harga,
                'subtotal'     => $subtotal,
            ];
        }

        $biayaTotal = $biayaSparepart + $biayaJasa;

        $insertData = [
            'nomor_tiket'       => $nomorTiket,
            'nomor_urut'        => $nomorUrut,
            'id_pelanggan'      => !empty($idPelanggan) ? $idPelanggan : null,
            'nama_pelanggan'    => $namaPelanggan,
            'alamat_pelanggan'  => $alamat,
            'pic_nama'          => $picNama,
            'pic_kontak'        => $picKontak,
            'jenis_layanan'     => $jenisLayanan,
            'nama_alat'         => $namaAlat,
            'merk_tipe'         => $merkTipe,
            'nomor_seri'        => $nomorSeri,
            'keluhan'           => $keluhan,
            'tgl_masuk'         => $tglMasuk,
            'id_teknisi'        => $idTeknisi,
            'nama_teknisi'      => $namaTeknisi,
            'status'            => 'menunggu_kroscek',
            'status_pembayaran' => $statusPembayaran,
            'biaya_sparepart'   => $biayaSparepart,
            'biaya_jasa'        => $biayaJasa,
            'biaya_total'       => $biayaTotal,
            'status_data'       => 1,
            'created_by'        => sessPenggunaId(),
            'created_at'        => date('Y-m-d H:i:s')
        ];

        $insertedId = $this->md_after_sales->insert($insertData, $items);

        if ($insertedId) {
            // Trigger Notifikasi WhatsApp Otomatis ke Grup Teknisi & Teknisi terkait
            try {
                if (function_exists('waAfterSalesBaru')) {
                    waAfterSalesBaru($insertData, $items);
                }
            } catch (\Throwable $e) {
                log_message('error', 'Gagal kirim WA After Sales: ' . $e->getMessage());
            }

            $this->session->set_flashdata('success', 'Tiket After Sales berhasil didaftarkan: ' . $nomorTiket);
            redirect('after_sales/detail/' . encrypt($insertedId));
        } else {
            $this->session->set_flashdata('error', 'Gagal mendaftarkan tiket after sales.');
            redirect('after_sales/tambah');
        }
    }

    /**
     * Halaman Detail & Status Alur Kerja
     */
    public function detail($encryptedId)
    {
        grantAccessFor('all');

        $id = decrypt($encryptedId);
        $ticket = $this->md_after_sales->getById($id);

        if (!$ticket) {
            show_404();
            return;
        }

        $page_data['switch']       = $this->id_navbar();
        $page_data['ticket']       = $ticket;
        $page_data['teknisi_list'] = $this->md_after_sales->getTeknisiList();
        $page_data['encrypted_id'] = $encryptedId;
        $page_data['page_name']    = 'after_sales/v_after_sales_detail';
        $page_data['page_title']   = 'Detail Layanan: ' . $ticket->nomor_tiket;
        $page_data['page_desc']    = 'Rincian Pengerjaan, Sparepart, & Log Tiket';

        $this->load->view('index', $page_data);
    }

    /**
     * Update Status / Transisi Alur Kerja
     */
    public function update_status()
    {
        grantAccessFor('all');

        try {
            $idRaw = (int)$this->input->post('id_raw');
            $encryptedId = $this->input->post('id_tiket');
            $id = !empty($idRaw) ? $idRaw : (!empty($encryptedId) ? (int)decrypt($encryptedId) : 0);
            $statusBaru = $this->input->post('status');
            $catatan = trim($this->input->post('catatan'));
            $idTeknisi = $this->input->post('id_teknisi');
            $tindakan = trim($this->input->post('tindakan_perbaikan'));

            $ticket = $this->md_after_sales->getById($id);
            if (!$ticket) {
                $this->session->set_flashdata('error', 'Tiket tidak ditemukan.');
                redirect('after_sales');
                return;
            }

            $updateData = [
                'status'     => $statusBaru,
                'updated_by' => function_exists('sessPenggunaId') ? sessPenggunaId() : 1,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if (!empty($tindakan)) {
                $updateData['tindakan_perbaikan'] = $tindakan;
            }

            if (!empty($idTeknisi)) {
                $updateData['id_teknisi'] = $idTeknisi;
                $t = $this->db->get_where('pengguna', ['pengguna_id' => $idTeknisi])->row();
                if ($t) {
                    $updateData['nama_teknisi'] = $t->nama;
                }
            }

            if ($statusBaru === 'proses') {
                $updateData['tgl_pengerjaan'] = date('Y-m-d H:i:s');
                if (empty($ticket->tgl_kroscek)) {
                    $updateData['tgl_kroscek'] = date('Y-m-d H:i:s');
                }
            } elseif ($statusBaru === 'selesai') {
                $updateData['tgl_selesai'] = date('Y-m-d H:i:s');
            }

            $this->md_after_sales->update($id, $updateData);

            // Catat di log
            $this->md_after_sales->insertLog([
                'after_sales_id' => $id,
                'status_lama'    => $ticket->status,
                'status_baru'    => $statusBaru,
                'keterangan'     => !empty($catatan) ? $catatan : "Status tiket diubah menjadi " . strtoupper(str_replace('_', ' ', $statusBaru)),
                'user_id'        => function_exists('sessPenggunaId') ? sessPenggunaId() : 1,
                'user_nama'      => function_exists('sessNamaPengguna') ? (sessNamaPengguna() ?: 'Administrator') : 'Administrator',
                'created_at'     => date('Y-m-d H:i:s')
            ]);

            // Trigger Notifikasi WhatsApp Update Status ke Grup Teknisi
            try {
                $updatedTicket = $this->md_after_sales->getById($id);
                if (function_exists('waAfterSalesUpdateStatus') && $updatedTicket) {
                    waAfterSalesUpdateStatus($updatedTicket, $statusBaru, $catatan);
                }
            } catch (\Throwable $e) {
                log_message('error', 'Gagal kirim WA Update Status After Sales: ' . $e->getMessage());
            }

            $this->session->set_flashdata('success', 'Status tiket berhasil diperbarui.');
            $targetUrl = !empty($encryptedId) ? 'after_sales/detail/' . $encryptedId : 'after_sales/detail/' . encrypt($id);
            redirect($targetUrl);
        } catch (\Throwable $t) {
            $this->session->set_flashdata('error', 'Gagal memperbarui status: ' . $t->getMessage());
            redirect('after_sales');
        }
    }

    /**
     * Update Item Sparepart / Jasa dari halaman detail
     */
    public function update_items()
    {
        grantAccessFor('all');

        $encryptedId = $this->input->post('id_tiket');
        $id = decrypt($encryptedId);

        $itemJenis = $this->input->post('item_jenis') ?: [];
        $itemDeskripsi = $this->input->post('item_deskripsi') ?: [];
        $itemQty = $this->input->post('item_qty') ?: [];
        $itemSatuan = $this->input->post('item_satuan') ?: [];
        $itemHarga = $this->input->post('item_harga') ?: [];

        $items = [];
        $biayaSparepart = 0;
        $biayaJasa = 0;

        for ($i = 0; $i < count($itemDeskripsi); $i++) {
            $deskripsi = trim($itemDeskripsi[$i] ?? '');
            if (empty($deskripsi)) continue;

            $jenis = ($itemJenis[$i] ?? 'sparepart') === 'jasa' ? 'jasa' : 'sparepart';
            $qty = (int)($itemQty[$i] ?? 1);
            if ($qty < 1) $qty = 1;
            $satuan = trim($itemSatuan[$i] ?? 'Pcs');
            $harga = (float)str_replace(['.', ','], ['', '.'], $itemHarga[$i] ?? 0);
            $subtotal = $qty * $harga;

            if ($jenis === 'jasa') {
                $biayaJasa += $subtotal;
            } else {
                $biayaSparepart += $subtotal;
            }

            $items[] = [
                'jenis'        => $jenis,
                'deskripsi'    => $deskripsi,
                'qty'          => $qty,
                'satuan'       => $satuan,
                'harga_satuan' => $harga,
                'subtotal'     => $subtotal,
            ];
        }

        $biayaTotal = $biayaSparepart + $biayaJasa;

        $updateData = [
            'biaya_sparepart' => $biayaSparepart,
            'biaya_jasa'      => $biayaJasa,
            'biaya_total'     => $biayaTotal,
            'updated_by'      => sessPenggunaId(),
            'updated_at'      => date('Y-m-d H:i:s')
        ];

        $this->md_after_sales->update($id, $updateData, $items);

        $this->md_after_sales->insertLog([
            'after_sales_id' => $id,
            'status_lama'    => null,
            'status_baru'    => 'update_item',
            'keterangan'     => "Memperbarui rincian sparepart & biaya jasa (Total: Rp " . number_format($biayaTotal, 0, ',', '.') . ")",
            'user_id'        => sessPenggunaId(),
            'user_nama'      => sessNamaPengguna(),
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Rincian sparepart & jasa berhasil disimpan.');
        redirect('after_sales/detail/' . $encryptedId);
    }

    /**
     * Cetak Berita Acara / Surat Layanan After Sales Service (mPDF)
     */
    public function cetak($encryptedId)
    {
        grantAccessFor('all');

        $id = decrypt($encryptedId);
        $ticket = $this->md_after_sales->getById($id);

        if (!$ticket) {
            show_404();
            return;
        }

        require_once APPPATH . 'vendor/autoload.php';

        ini_set('memory_limit', '512M');
        set_time_limit(120);

        $tempDir = APPPATH . 'cache/mpdf';
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0755, true);
        }

        $mpdf = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'margin_left'   => 12,
            'margin_right'  => 12,
            'margin_top'    => 54,
            'margin_bottom' => 20,
            'margin_header' => 5,
            'margin_footer' => 5,
            'default_font'  => 'dejavusans',
            'tempDir'       => $tempDir,
        ]);

        $kopAtas = img_to_base64('assets/img/kop_baru.jpg');
        $kopBawah = img_to_base64('assets/img/kop_surat_bawah.jpg');

        $headerHtml = '
        <div style="text-align:center;">
            <img src="' . $kopAtas . '" style="width:100%;" />
            <hr style="border:0; border-top:3px solid #1a237e; margin:3px 0;" />
            <hr style="border:0; border-top:1px solid #1a237e; margin:0;" />
        </div>';

        $footerHtml = '
        <div style="text-align:center;">
            <img src="' . $kopBawah . '" style="width:100%;" />
        </div>';

        $mpdf->SetHTMLHeader($headerHtml);
        $mpdf->SetHTMLFooter($footerHtml);
        $mpdf->shrink_tables_to_fit = 0;

        $data['ticket'] = $ticket;
        $html = $this->load->view('pages/after_sales/v_after_sales_print', $data, true);

        $mpdf->WriteHTML($html);

        $filename = 'Service_Report_' . str_replace('/', '_', $ticket->nomor_tiket) . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    /**
     * Hapus tiket (Soft delete)
     */
    public function hapus()
    {
        grantAccessFor(['Administrator']);

        $encryptedId = $this->input->post('id_tiket');
        $id = decrypt($encryptedId);

        if ($this->md_after_sales->delete($id)) {
            $this->session->set_flashdata('success', 'Tiket After Sales berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus tiket.');
        }

        redirect('after_sales');
    }
}
