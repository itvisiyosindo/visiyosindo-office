<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spp extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->library('session');

      // Load Models
      $this->load->model('md_spp');
      $this->load->model('md_tagihan');
      $this->load->model('md_pengguna');
      $this->load->helper('whatsapp_helper');
      $this->load->helper('jatuh_tempo_helper');
   }

   function id_navbar()
   {
      return "helpdesk";
   }

   // =========================================================================
   // PUBLIC VIEWS
   // =========================================================================

   /**
    * Halaman List SPP
    */
   public function index()
   {
      grantAccessFor('all');

      $all_data = $this->md_spp->getAllSpp();
      $page_data['approval_configs'] = $this->md_spp->getAllConfig();

      // Cek User Login & Jabatan
      $id_user = sessPenggunaId();
      $user = $this->md_pengguna->getById($id_user);
      $page_data['current_user_jabatan'] = is_array($user) ? $user[0]->jabatan : $user->jabatan;

      // Cek Admin (Override) - sama seperti Tagihan.php
      $nama = $this->session->userdata('nama');
      $page_data['is_admin'] = ($nama == 'Administrator');

      // Filter Data
      $filter_type = $this->input->get('filter');
      $filtered_data = [];

      if (empty($filter_type)) {
         $filtered_data = $all_data;
      } else {
         foreach ($all_data as $row) {
            if ($filter_type == 'approved') {
               if ($row->status_approval == 5) $filtered_data[] = $row;
            } elseif ($filter_type == 'paid') {
               // Gunakan property_exists untuk menghindari error jika kolom tidak ada
               if (isset($row->status_bayar) && $row->status_bayar == 'sudah_dibayar') $filtered_data[] = $row;
            } elseif ($filter_type == 'waiting') {
               if ($row->status_approval > 0 && $row->status_approval < 5) $filtered_data[] = $row;
            }
         }
      }

      // Config View
      $page_data['list_spp']     = $filtered_data;
      $page_data['all_spp']      = $all_data; // Untuk kalkulasi statistik
      $page_data['filter_active'] = $filter_type;
      $page_data['switch']       = $this->id_navbar();
      $page_data['page_name']    = 'spp/v_list_spp';
      $page_data['page_title']   = 'Surat Permintaan Pembayaran (SPP)';
      $page_data['page_desc']    = 'Daftar pengajuan SPP untuk pembayaran tagihan ekspedisi';

      $this->load->view('index', $page_data);
   }

   /**
    * Halaman Pengajuan SPP Baru
    * Menampilkan list tagihan approved yang digroup by no_invoice
    */
   public function create()
   {
      grantAccessFor('all');

      // Ambil tagihan yang sudah approved dan belum diajukan SPP
      $page_data['grouped_tagihan'] = $this->md_spp->getApprovedTagihanGrouped();

      $page_data['switch']     = $this->id_navbar();
      $page_data['page_name']  = 'spp/v_create_spp';
      $page_data['page_title'] = 'Ajukan SPP Baru';
      $page_data['page_desc']  = 'Pilih invoice yang akan diajukan SPP';

      $this->load->view('index', $page_data);
   }

   /**
    * Halaman Detail SPP
    */
   public function detail($id_spp_encrypted)
   {
      grantAccessFor('all');

      $id_spp = decrypt($id_spp_encrypted);
      $spp = $this->md_spp->getById($id_spp);

      if (!$spp) show_404();

      $page_data['spp'] = $spp;
      $page_data['tagihan_list'] = $this->md_spp->getTagihanBySpp($id_spp);
      $page_data['approval_configs'] = $this->md_spp->getAllConfig();

      // Cek User Login
      $id_user = sessPenggunaId();
      $user = $this->md_pengguna->getById($id_user);
      $page_data['current_user_jabatan'] = is_array($user) ? $user[0]->jabatan : $user->jabatan;

      // Cek Admin (Override) - sama seperti Tagihan.php
      $nama = $this->session->userdata('nama');
      $page_data['is_admin'] = ($nama == 'Administrator');

      $page_data['switch']     = $this->id_navbar();
      $page_data['page_name']  = 'spp/v_detail_spp';
      $page_data['page_title'] = 'Detail SPP';
      $page_data['page_desc']  = 'No. SPP: ' . $spp->no_spp;

      $this->load->view('index', $page_data);
   }

   /**
    * Halaman Edit Admin SPP (Admin Only)
    * Admin dapat mengedit semua field termasuk status approval
    */
   public function edit_admin($id_spp_encrypted)
   {
      // Cek akses Admin
      $nama = $this->session->userdata('nama');
      if ($nama != 'Administrator') {
         $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
         redirect('spp');
      }

      $id_spp = decrypt($id_spp_encrypted);
      $spp = $this->md_spp->getById($id_spp);

      if (!$spp) show_404();

      // Ambil daftar tagihan yang sudah terkait dengan SPP ini
      $tagihan_in_spp = $this->md_spp->getTagihanBySpp($id_spp);

      // Ambil semua tagihan approved yang belum masuk SPP (untuk dropdown tambah tagihan)
      $this->load->model('md_tagihan');
      $all_approved = $this->md_tagihan->getAllTagihan();
      $available_tagihan = [];

      foreach ($all_approved as $t) {
         // Hanya tagihan yang approved (status 5) dan belum ada di SPP lain
         if ($t->status_approval == 5) {
            // Cek apakah sudah ada di spp_detail
            $check = $this->db->select('id_spp')
               ->from('spp_detail')
               ->where('id_tagihan', $t->id_tagihan)
               ->get()->row();

            // Jika belum ada di SPP manapun, atau ada di SPP ini (boleh muncul untuk referensi)
            if (!$check || $check->id_spp == $id_spp) {
               $available_tagihan[] = $t;
            }
         }
      }

      $page_data['spp'] = $spp;
      $page_data['tagihan_list'] = $tagihan_in_spp;
      $page_data['available_tagihan'] = $available_tagihan;
      $page_data['approval_configs'] = $this->md_spp->getAllConfig();

      $page_data['switch']     = $this->id_navbar();
      $page_data['page_name']  = 'spp/v_edit_admin_spp';
      $page_data['page_title'] = 'Edit SPP (Admin)';
      $page_data['page_desc']  = 'No. SPP: ' . $spp->no_spp;

      $this->load->view('index', $page_data);
   }

   /**
    * Process Update Admin SPP - REFACTORED VERSION
    * Fitur: Edit semua field termasuk no_invoice, ppn, diskon, dll
    */
   public function update_admin()
   {
      $nama = $this->session->userdata('nama');
      if ($nama != 'Administrator') {
         ajaxReturnDie('error', 'Anda tidak memiliki akses.');
      }

      $id_spp = decrypt($this->input->post('id_spp'));
      $spp = $this->md_spp->getById($id_spp);

      if (!$spp) {
         ajaxReturnDie('error', 'Data SPP tidak ditemukan.');
      }

      // Ambil input dari form
      $no_invoice = trim($this->input->post('no_invoice'));
      $status_approval = $this->input->post('status_approval');
      $diskon = intval(preg_replace('/[^0-9]/', '', $this->input->post('diskon')));
      $ppn = intval(preg_replace('/[^0-9]/', '', $this->input->post('ppn')));
      $nilai_bukti_potong = intval(preg_replace('/[^0-9]/', '', $this->input->post('nilai_bukti_potong')));
      $catatan = $this->input->post('catatan');
      $catatan_revisi = $this->input->post('catatan_revisi');
      $link_dokumen_spp = $this->input->post('link_dokumen_spp');
      $link_bukti_potong = $this->input->post('link_bukti_potong');

      // Validasi No. Invoice tidak boleh kosong
      if (empty($no_invoice)) {
         ajaxReturnDie('error', 'No. Invoice tidak boleh kosong.');
      }

      // --- RECALCULATE: Total Nilai dari Tagihan Terkait ---
      $tagihan_list = $this->md_spp->getTagihanBySpp($id_spp);
      $total_nilai = 0;
      $jumlah_tagihan = count($tagihan_list);

      foreach ($tagihan_list as $t) {
         $nilai_tagihan = $t->nilai_tagihan ?? 0;
         $biaya_asuransi = $t->biaya_asuransi ?? 0;
         $total_tagihan_item = $nilai_tagihan + $biaya_asuransi;
         $total_nilai += $total_tagihan_item;
      }

      // --- LOGIKA PERHITUNGAN LENGKAP ---
      // 1. Grand Total = (Total Nilai + PPN) - Diskon
      $grand_total = ($total_nilai + $ppn) - $diskon;
      if ($grand_total < 0) $grand_total = 0;

      // 2. Nilai Pembayaran = Grand Total - Bukti Potong
      $nilai_pembayaran = $grand_total - $nilai_bukti_potong;
      if ($nilai_pembayaran < 0) $nilai_pembayaran = 0;

      $dataUpdate = [
         'no_invoice' => $no_invoice,
         'total_nilai' => $total_nilai,
         'jumlah_tagihan' => $jumlah_tagihan,
         'status_approval' => $status_approval,
         'diskon' => $diskon,
         'ppn' => $ppn,
         'grand_total' => $grand_total,
         'nilai_bukti_potong' => $nilai_bukti_potong,
         'nilai_pembayaran' => $nilai_pembayaran,
         'catatan' => $catatan,
         'catatan_revisi' => $catatan_revisi,
         'link_dokumen_spp' => $link_dokumen_spp,
         'link_bukti_potong' => $link_bukti_potong,
         'updated_at' => date('Y-m-d H:i:s')
      ];

      $this->db->trans_begin();
      $this->md_spp->update($id_spp, $dataUpdate);

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         ajaxReturnDie('error', 'Gagal menyimpan perubahan.');
      } else {
         $this->db->trans_commit();
         addlog('Edit SPP (Admin)', 'Admin mengedit SPP No: ' . $spp->no_spp . ' - Total: Rp ' . number_format($total_nilai, 0, ',', '.'));
         ajaxReturnDie('success', 'SPP berhasil diperbarui! Total Nilai: Rp ' . number_format($total_nilai, 0, ',', '.'), base_url('spp/detail/' . encrypt($id_spp)));
      }
   }

   /**
    * Tambah Tagihan ke SPP (AJAX)
    * Admin bisa menambahkan tagihan yang sudah approved ke dalam SPP
    */
   public function add_tagihan_to_spp()
   {
      $nama = $this->session->userdata('nama');
      if ($nama != 'Administrator') {
         ajaxReturnDie('error', 'Anda tidak memiliki akses.');
      }

      $id_spp = decrypt($this->input->post('id_spp'));
      $id_tagihan = $this->input->post('id_tagihan');

      if (!$id_spp || !$id_tagihan) {
         ajaxReturnDie('error', 'Data tidak lengkap.');
      }

      // Cek apakah tagihan sudah ada di SPP lain
      $check = $this->db->select('id_spp')->from('spp_detail')
         ->where('id_tagihan', $id_tagihan)
         ->get()->row();

      if ($check && $check->id_spp != $id_spp) {
         ajaxReturnDie('error', 'Tagihan ini sudah terdaftar di SPP lain.');
      }

      // Cek apakah tagihan sudah ada di SPP ini
      if ($check && $check->id_spp == $id_spp) {
         ajaxReturnDie('error', 'Tagihan ini sudah ada dalam SPP.');
      }

      $this->db->trans_begin();

      // Insert ke spp_detail
      $this->db->insert('spp_detail', [
         'id_spp' => $id_spp,
         'id_tagihan' => $id_tagihan,
         'created_at' => date('Y-m-d H:i:s')
      ]);

      // Recalculate SPP totals
      $this->_recalculate_spp_totals($id_spp);

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         ajaxReturnDie('error', 'Gagal menambahkan tagihan.');
      } else {
         $this->db->trans_commit();
         ajaxReturnDie('success', 'Tagihan berhasil ditambahkan ke SPP.');
      }
   }

   /**
    * Hapus Tagihan dari SPP (AJAX)
    */
   public function remove_tagihan_from_spp()
   {
      $nama = $this->session->userdata('nama');
      if ($nama != 'Administrator') {
         ajaxReturnDie('error', 'Anda tidak memiliki akses.');
      }

      $id_spp = decrypt($this->input->post('id_spp'));
      $id_tagihan = $this->input->post('id_tagihan');

      if (!$id_spp || !$id_tagihan) {
         ajaxReturnDie('error', 'Data tidak lengkap.');
      }

      // Validasi: Minimal 1 tagihan harus tersisa
      $count = $this->db->from('spp_detail')->where('id_spp', $id_spp)->count_all_results();
      if ($count <= 1) {
         ajaxReturnDie('error', 'Tidak dapat menghapus. SPP harus memiliki minimal 1 tagihan.');
      }

      $this->db->trans_begin();

      // Hapus dari spp_detail
      $this->db->delete('spp_detail', [
         'id_spp' => $id_spp,
         'id_tagihan' => $id_tagihan
      ]);

      // Recalculate SPP totals
      $this->_recalculate_spp_totals($id_spp);

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         ajaxReturnDie('error', 'Gagal menghapus tagihan.');
      } else {
         $this->db->trans_commit();
         ajaxReturnDie('success', 'Tagihan berhasil dihapus dari SPP.');
      }
   }

   /**
    * Private Helper: Recalculate SPP Totals
    * Menghitung ulang total_nilai, jumlah_tagihan, grand_total, dan nilai_pembayaran
    */
   private function _recalculate_spp_totals($id_spp)
   {
      $tagihan_list = $this->md_spp->getTagihanBySpp($id_spp);
      $spp = $this->md_spp->getById($id_spp);

      $total_nilai = 0;
      $jumlah_tagihan = count($tagihan_list);

      foreach ($tagihan_list as $t) {
         $nilai_tagihan = $t->nilai_tagihan ?? 0;
         $biaya_asuransi = $t->biaya_asuransi ?? 0;
         $total_tagihan_item = $nilai_tagihan + $biaya_asuransi;
         $total_nilai += $total_tagihan_item;
      }

      $ppn = $spp->ppn ?? 0;
      $diskon = $spp->diskon ?? 0;
      $nilai_bukti_potong = $spp->nilai_bukti_potong ?? 0;

      // [BARU] Ambil biaya lainnya
      $biaya_lainnya = $spp->biaya_lainnya ?? 0;

      // Calculate grand_total (UPDATE RUMUS)
      $grand_total = ($total_nilai + $ppn + $biaya_lainnya) - $diskon;
      if ($grand_total < 0) $grand_total = 0;

      $nilai_pembayaran = $grand_total - $nilai_bukti_potong;
      if ($nilai_pembayaran < 0) $nilai_pembayaran = 0;

      // Update SPP
      $this->md_spp->update($id_spp, [
         'total_nilai' => $total_nilai,
         'jumlah_tagihan' => $jumlah_tagihan,
         'grand_total' => $grand_total,
         'nilai_pembayaran' => $nilai_pembayaran,
         'updated_at' => date('Y-m-d H:i:s')
      ]);
   }

   /**
    * Halaman Edit/Revisi SPP
    * Hanya bisa diakses jika status = 0 (revisi) dan user adalah pengaju atau admin
    */
   public function edit($id_spp_encrypted)
   {
      grantAccessFor('all');

      $id_spp = decrypt($id_spp_encrypted);
      $spp = $this->md_spp->getById($id_spp);

      if (!$spp) show_404();

      // Cek akses: hanya pengaju atau admin yang bisa edit
      $id_user = sessPenggunaId();
      $nama = $this->session->userdata('nama');
      $is_admin = ($nama == 'Administrator');

      // Hanya bisa edit jika status = 0 (revisi) atau 99 (ditolak, untuk ajukan ulang)
      if ($spp->status_approval != 0 && $spp->status_approval != 99) {
         $this->session->set_flashdata('error', 'SPP tidak dalam status revisi.');
         redirect('spp/detail/' . $id_spp_encrypted);
      }

      // Hanya pengaju atau admin yang bisa edit
      if ($spp->created_by != $id_user && !$is_admin) {
         $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk merevisi SPP ini.');
         redirect('spp/detail/' . $id_spp_encrypted);
      }

      $page_data['spp'] = $spp;
      $page_data['tagihan_list'] = $this->md_spp->getTagihanBySpp($id_spp);
      $page_data['is_admin'] = $is_admin;

      $page_data['switch']     = $this->id_navbar();
      $page_data['page_name']  = 'spp/v_edit_spp';
      $page_data['page_title'] = 'Revisi SPP';
      $page_data['page_desc']  = 'No. SPP: ' . $spp->no_spp;

      $this->load->view('index', $page_data);
   }

   /**
    * Submit Update/Revisi SPP
    */
   /**
    * Submit Update/Revisi SPP
    */
   public function update()
   {
      // 1. Ambil dan Dekripsi ID SPP (Gunakan decrypt untuk keamanan)
      $id_spp = decrypt($this->input->post('id_spp'));
      $catatan = $this->input->post('catatan');
      $link_dokumen = $this->input->post('link_dokumen_spp');
      $link_bukti_potong = $this->input->post('link_bukti_potong');

      // Ambil field bank info
      $nama_bank = $this->input->post('nama_bank');
      $no_rekening = $this->input->post('no_rekening');
      $atas_nama = $this->input->post('atas_nama');

      // Ambil input angka
      $ppn = $this->input->post('ppn');
      $biaya_lainnya = $this->input->post('biaya_lainnya');
      $nilai_bukti_potong = $this->input->post('nilai_bukti_potong');
      $diskon = $this->input->post('diskon');

      // 2. Sanitize & Validasi - GUNAKAN INTEGER
      $ppn = intval(preg_replace('/[^0-9]/', '', $ppn));
      $biaya_lainnya = intval(preg_replace('/[^0-9]/', '', $biaya_lainnya));
      $nilai_bukti_potong = intval(preg_replace('/[^0-9]/', '', $nilai_bukti_potong));
      $diskon = intval(preg_replace('/[^0-9]/', '', $diskon));

      if (!$id_spp) {
         ajaxReturnDie('error', 'ID SPP tidak valid.');
      }

      $spp = $this->md_spp->getById($id_spp);
      if (!$spp) {
         ajaxReturnDie('error', 'Data SPP tidak ditemukan.');
      }

      // 3. Logika Penentuan Status (Kembali ke Level Approval Pertama)
      $firstConfig = $this->md_spp->getFirstActiveLevel();
      $newStatus = $firstConfig ? $firstConfig->status_code : 1;
      $targetRole = $firstConfig ? $firstConfig->role_label : 'Approver';
      $targetPhone = $firstConfig ? $firstConfig->notif_number : '';

      // 4. GUNAKAN total_nilai dari database (TIDAK dihitung ulang)
      // Total tagihan sudah fixed saat create SPP, tidak berubah saat revisi
      $total_nilai = $spp->total_nilai;

      // 5. Logika Perhitungan: (Total + PPN + Biaya Lainnya) - Diskon
      $grand_total = ($total_nilai + $ppn + $biaya_lainnya) - $diskon;
      if ($grand_total < 0) $grand_total = 0;

      // 6. Nilai Pembayaran: Grand Total - Bukti Potong
      $nilai_pembayaran = $grand_total - $nilai_bukti_potong;
      if ($nilai_pembayaran < 0) $nilai_pembayaran = 0;

      $this->db->trans_begin();

      // 7. Update Data ke Database
      $dataUpdate = [
         'nama_bank' => $nama_bank,
         'no_rekening' => $no_rekening,
         'atas_nama' => $atas_nama,
         'total_nilai' => $total_nilai,
         'ppn' => $ppn,
         'biaya_lainnya' => $biaya_lainnya,
         'catatan' => $catatan,
         'link_dokumen_spp' => $link_dokumen,
         'link_bukti_potong' => $link_bukti_potong,
         'nilai_bukti_potong' => $nilai_bukti_potong,
         'diskon' => $diskon,
         'grand_total' => $grand_total,
         'nilai_pembayaran' => $nilai_pembayaran,
         'status_approval' => $newStatus, // Kembali ke antrian awal
         'catatan_revisi' => null,       // Reset catatan revisi dari atasan
         'updated_at' => date('Y-m-d H:i:s')
      ];

      $this->md_spp->update($id_spp, $dataUpdate);

      // 6. LOGIKA KIRIM WA (Notifikasi Perbaikan Revisi)
      if (!empty($targetPhone)) {
         $nama_pengaju = $this->session->userdata('nama') ?: 'User';
         $history_list = $this->_generateApprovalList($firstConfig->level_order, false);

         $pesan = "*Notifikasi Perbaikan SPP Ekspedisi*\n\n";
         $pesan .= "_Dear *" . $targetRole . "*_,\n\n";
         $pesan .= $nama_pengaju . " telah *MEMPERBAIKI REVISI* SPP:\n\n";
         $pesan .= "_No SPP : *" . $spp->no_spp . "*_\n";
         $pesan .= "_No Invoice : *" . $spp->no_invoice . "*_\n";
         $pesan .= "_Grand Total : *Rp " . number_format($grand_total, 0, ',', '.') . "*_\n";

         if (!empty($catatan)) {
            $pesan .= "_Catatan User : " . $catatan . "_\n";
         }

         $pesan .= "\nHistory Approval :\n" . $history_list . "\n";
         $pesan .= "Mohon agar dapat diperiksa kembali melalui Link: " . base_url('spp/detail/' . encrypt($id_spp)) . "\n\n";
         $pesan .= "_*Terima Kasih*_";

         $dataWa = [
            'devId' => hostWa('1'),
            'penerima' => $targetPhone,
            'pesan' => urlencode($pesan)
         ];

         sendWa($dataWa);
         $this->md_spp->logNotification($id_spp, $targetRole, $targetPhone, $pesan);
      }

      // 7. Finalisasi Transaction
      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         ajaxReturnDie('error', 'Gagal mengupdate data SPP.');
      } else {
         $this->db->trans_commit();
         addlog('Revisi SPP', 'User memperbaiki revisi SPP No: ' . $spp->no_spp);
         ajaxReturnDie('success', 'SPP berhasil diperbaiki dan diajukan ulang.', base_url('spp/detail/' . encrypt($id_spp)));
      }
   }

   /**
    * Preview tagihan berdasarkan no_invoice (AJAX)
    */
   public function preview_invoice()
   {
      $no_invoice = $this->input->post('no_invoice');
      $nama_ekspedisi = $this->input->post('nama_ekspedisi');

      if (!$no_invoice) {
         echo json_encode(['status' => false, 'message' => 'No Invoice tidak valid']);
         return;
      }

      $tagihan_list = $this->md_spp->getTagihanByNoInvoice($no_invoice, $nama_ekspedisi);
      $total = 0;
      foreach ($tagihan_list as $t) {
         // Gunakan total_tagihan jika ada, jika tidak hitung dari nilai_tagihan + biaya_asuransi
         $tagihan_amount = !empty($t->total_tagihan) ? $t->total_tagihan : ($t->nilai_tagihan + ($t->biaya_asuransi ?? 0));
         $total += $tagihan_amount;
      }

      echo json_encode([
         'status' => true,
         'data' => $tagihan_list,
         'total' => $total,
         'jumlah' => count($tagihan_list)
      ]);
   }

   // =========================================================================
   // SUBMIT SPP
   // =========================================================================

   /**
    * Submit Pengajuan SPP
    */
   public function submit()
   {
      $no_invoice = $this->input->post('no_invoice');
      $nama_ekspedisi = $this->input->post('nama_ekspedisi');
      $catatan = $this->input->post('catatan');
      $link_dokumen = $this->input->post('link_dokumen_spp');
      $link_bukti_potong = $this->input->post('link_bukti_potong');

      // --- AMBIL INPUT ANGKA ---
      $nilai_bukti_potong = $this->input->post('nilai_bukti_potong');
      $diskon = $this->input->post('diskon');
      $ppn = $this->input->post('ppn'); // INPUT PPN BARU

      // [BARU] Ambil input Biaya Lainnya
      $biaya_lainnya = $this->input->post('biaya_lainnya');

      // --- SANITIZE (Hapus karakter non-angka) - GUNAKAN INTEGER ---
      $nilai_bukti_potong = intval(preg_replace('/[^0-9]/', '', $nilai_bukti_potong));
      $diskon = intval(preg_replace('/[^0-9]/', '', $diskon));
      $ppn = intval(preg_replace('/[^0-9]/', '', $ppn));

      // [BARU] Sanitize Biaya Lainnya
      $biaya_lainnya = intval(preg_replace('/[^0-9]/', '', $biaya_lainnya));

      if (!$no_invoice) {
         ajaxReturnDie('error', 'No Invoice tidak valid.');
      }

      // Ambil tagihan
      $tagihan_list = $this->md_spp->getTagihanByNoInvoice($no_invoice, $nama_ekspedisi);
      if (empty($tagihan_list)) {
         ajaxReturnDie('error', 'Tidak ada tagihan tersedia.');
      }

      // Hitung Total Nilai Tagihan (Base Amount) - GUNAKAN INTEGER
      $total_nilai = 0;
      $jumlah_tagihan = count($tagihan_list);
      foreach ($tagihan_list as $t) {
         $tagihan_amount = intval($t->total_tagihan ?? 0);
         if ($tagihan_amount == 0) {
            $tagihan_amount = intval($t->nilai_tagihan ?? 0) + intval($t->biaya_asuransi ?? 0);
         }
         $total_nilai += $tagihan_amount;
      }

      // --- LOGIKA PERHITUNGAN UTAMA ---

      // 1. Grand Total = (Total Tagihan + PPN) - Diskon
      $grand_total = ($total_nilai + $ppn + $biaya_lainnya) - $diskon;
      if ($grand_total < 0) $grand_total = 0;

      // 2. Nilai Pembayaran = Grand Total - Bukti Potong
      $nilai_pembayaran = $grand_total - $nilai_bukti_potong;
      if ($nilai_pembayaran < 0) $nilai_pembayaran = 0;

      // Config Approval
      $firstConfig = $this->md_spp->getFirstActiveLevel();
      $initialStatus = $firstConfig ? $firstConfig->status_code : 1;
      $targetRole = $firstConfig ? $firstConfig->role_label : 'Approver';
      $targetPhone = $firstConfig ? $firstConfig->notif_number : '';

      $no_spp = $this->md_spp->generateNoSpp();

      $nama_bank   = $this->input->post('nama_bank');
      $no_rekening = $this->input->post('no_rekening');
      $atas_nama   = $this->input->post('atas_nama');

      // Prepare data SPP
      $data_spp = [
         'no_spp' => $no_spp,
         'no_invoice' => $no_invoice,
         'nama_bank'         => $nama_bank,
         'no_rekening'       => $no_rekening,
         'atas_nama'         => $atas_nama,
         'total_nilai' => $total_nilai, // Murni jumlah tagihan
         'ppn' => $ppn,                 // PPN disimpan terpisah
         'biaya_lainnya' => $biaya_lainnya,
         'diskon' => $diskon,
         'grand_total' => $grand_total,
         'jumlah_tagihan' => $jumlah_tagihan,
         'status_approval' => $initialStatus,
         'catatan' => $catatan,
         'link_dokumen_spp' => $link_dokumen,
         'link_bukti_potong' => $link_bukti_potong,
         'nilai_bukti_potong' => $nilai_bukti_potong,
         'nilai_pembayaran' => $nilai_pembayaran,
         'created_by' => sessPenggunaId()
      ];

      $this->db->trans_begin();
      $id_spp = $this->md_spp->insert($data_spp);

      foreach ($tagihan_list as $t) {
         $this->md_spp->insertDetail($id_spp, $t->id_tagihan);
         $this->md_spp->updateTagihanSpp($t->id_tagihan, $id_spp);
      }

      // Kirim Notifikasi WA
      if (!empty($targetPhone)) {
         $nama_pengaju = $this->session->userdata('nama') ?: 'User';
         $history_list = $this->_generateApprovalList($firstConfig->level_order, false);

         $pesan = "*Notifikasi Pengajuan SPP Ekspedisi*\n\n";
         $pesan .= "_Dear *" . $targetRole . "*_,\n\n";
         $pesan .= $nama_pengaju . " *MENGAJUKAN* SPP Ekspedisi:\n\n";
         $pesan .= "_No SPP : *" . $no_spp . "*_\n";
         $pesan .= "_No Invoice : *" . $no_invoice . "*_\n";
         $pesan .= "_Total Tagihan : *Rp " . number_format($total_nilai, 0, ',', '.') . "*_\n";

         if ($ppn > 0) $pesan .= "_PPN : *Rp " . number_format($ppn, 0, ',', '.') . "*_\n";
         if ($diskon > 0) $pesan .= "_Diskon : *-Rp " . number_format($diskon, 0, ',', '.') . "*_\n";

         $pesan .= "_Grand Total : *Rp " . number_format($grand_total, 0, ',', '.') . "*_\n";

         if ($nilai_bukti_potong > 0) {
            $pesan .= "_Bukti Potong : *(Rp " . number_format($nilai_bukti_potong, 0, ',', '.') . ")*_\n";
            $pesan .= "_Transfer Cash : *Rp " . number_format($nilai_pembayaran, 0, ',', '.') . "*_\n";
         }

         $pesan .= "History Approval :\n" . $history_list . "\n";
         $pesan .= "Mohon agar dapat diperiksa pada Office melalui Link: " . base_url('spp/detail/' . encrypt($id_spp)) . "\n\n";
         $pesan .= "_*Terima Kasih*_";

         $dataWa = [
            'devId' => hostWa('1'),
            'penerima' => $targetPhone,
            'pesan' => urlencode($pesan)
         ];
         sendWa($dataWa);
         $this->md_spp->logNotification($id_spp, $targetRole, $targetPhone, $pesan);
      }

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         ajaxReturnDie('error', 'Gagal menyimpan data SPP.');
      } else {
         $this->db->trans_commit();
         ajaxReturnDie('success', 'SPP berhasil diajukan dengan No: ' . $no_spp, base_url('spp'));
      }
   }

   // =========================================================================
   // APPROVAL PROCESS
   // =========================================================================

   /**
    * Process Approval SPP
    */
   //   public function process_approval()
   //   {
   //       $id_spp = $this->input->post('id_spp');
   //       $aksi = $this->input->post('aksi'); // setujui, tolak, revisi
   //       $catatan = $this->input->post('catatan');
   //       $currentStatus = $this->input->post('current_status');
   //       $is_admin = ($this->session->userdata('nama') == 'Administrator');

   //       $spp = $this->md_spp->getById($id_spp);
   //       if (!$spp) {
   //          ajaxReturnDie('error', 'Data SPP tidak ditemukan');
   //       }

   //       $nextStatus = $currentStatus;
   //       $target_phone = '';
   //       $target_role = '';
   //       $action_msg = '';
   //       $history_list = '';

   //       $currentConfig = $this->md_spp->getConfigByStatus($currentStatus);

   //       if ($aksi == 'revisi' || $aksi == 'tolak') {
   //          $nextStatus = ($aksi == 'revisi') ? 0 : 99;
   //          $action_msg = ($aksi == 'revisi') ? 'Meminta REVISI' : 'MENOLAK Pengajuan';

   //          // Generate history sampai level saat ini
   //          if ($currentConfig) {
   //             $history_list = $this->_generateApprovalList($currentConfig->level_order, false);
   //          }

   //          if (!empty($spp->hp_pengaju)) {
   //             $target_phone = $spp->hp_pengaju;
   //             $target_role = $spp->nama_pengaju;
   //          }
   //       } else {
   //          // APPROVE / SETUJUI
   //          $nextConfig = null;

   //          if ($currentConfig) {
   //             // Ada config untuk status saat ini, ambil level berikutnya
   //             $nextConfig = $this->md_spp->getNextActiveLevel($currentConfig->level_order);
   //          } elseif ($is_admin) {
   //             // Admin bypass: jika status 0 (revisi) atau tidak ada config, mulai dari awal
   //             if ($currentStatus == 0) {
   //               $nextConfig = $this->md_spp->getFirstActiveLevel();
   //             }
   //          }

   //          if ($nextConfig) {
   //             $nextStatus = $nextConfig->status_code;
   //             $target_role = $nextConfig->role_label;
   //             $target_phone = $nextConfig->notif_number;
   //             $action_msg = 'MENYETUJUI (Lanjut ke ' . $target_role . ')';
   //             $history_list = $this->_generateApprovalList($nextConfig->level_order, false);
   //          } else {
   //             $nextStatus = 5;
   //             $action_msg = 'MENYETUJUI (Status SELESAI)';
   //             $target_role = $spp->nama_pengaju;
   //             $history_list = $this->_generateApprovalList(null, true);
   //             if (!empty($spp->hp_pengaju)) {
   //               $target_phone = $spp->hp_pengaju;
   //             }
   //          }
   //       }

   //       $this->db->trans_begin();

   //       $dataUpdate = [
   //          'status_approval' => $nextStatus,
   //          'catatan_revisi' => ($aksi != 'setujui') ? $catatan : null
   //       ];

   //       $this->md_spp->update($id_spp, $dataUpdate);

   //       // Kirim WA notifikasi
   //       if (!empty($target_phone)) {
   //          $nama_approver = $this->session->userdata('nama');

   //          $pesan = "*Notifikasi Pengajuan SPP Ekspedisi*\n\n";
   //          $pesan .= "_Dear *" . $target_role . "*_,\n\n";
   //          $pesan .= $nama_approver . " *" . $action_msg . "* SPP Ekspedisi:\n\n";
   //          $pesan .= "_No SPP : *" . $spp->no_spp . "*_\n";
   //          $pesan .= "_No Invoice : *" . $spp->no_invoice . "*_\n";
   //          $pesan .= "_Total Nilai : *Rp " . number_format($spp->total_nilai, 0, ',', '.') . "*_\n";

   //          if (isset($history_list)) {
   //             $pesan .= "History Approval :\n" . $history_list . "\n";
   //          }

   //          if (!empty($catatan)) {
   //             $pesan .= "\nCatatan: _" . $catatan . "_\n";
   //          }

   //          $pesan .= "\nMohon agar dapat diperiksa pada Office melalui Link: " . base_url('spp/detail/' . encrypt($id_spp)) . "\n\n";
   //          $pesan .= "_*Terima Kasih*_";

   //          $dataWa = [
   //             'devId' => hostWa('1'),
   //             'penerima' => $target_phone,
   //             'pesan' => urlencode($pesan)
   //          ];
   //          sendWa($dataWa);

   //          $this->md_spp->logNotification($id_spp, $target_role, $target_phone, $pesan);
   //       }

   //       if ($this->db->trans_status() === FALSE) {
   //          $this->db->trans_rollback();
   //          ajaxReturnDie('error', 'Gagal update status SPP.');
   //       } else {
   //          $this->db->trans_commit();
   //          ajaxReturnDie('success', 'Status SPP berhasil diupdate.');
   //       }
   //   }

   /**
    * Process Approval SPP
    */
   public function process_approval()
   {
      $id_spp = $this->input->post('id_spp');
      $aksi = $this->input->post('aksi'); // setujui, tolak, revisi
      $catatan = $this->input->post('catatan');
      $currentStatus = $this->input->post('current_status');
      $is_admin = ($this->session->userdata('nama') == 'Administrator');

      $spp = $this->md_spp->getById($id_spp);
      if (!$spp) {
         ajaxReturnDie('error', 'Data SPP tidak ditemukan');
      }

      $nextStatus = $currentStatus;

      // Variabel Target Utama (Next Approver atau Pengaju)
      $target_phone = '';
      $target_role = '';

      // Variabel Target Kedua (Approval 1)
      $secondary_target_phone = '';
      $secondary_role_label = ''; // <--- Penampung Nama Role Approval 1

      $action_msg = '';
      $history_list = '';

      $currentConfig = $this->md_spp->getConfigByStatus($currentStatus);

      if ($aksi == 'revisi' || $aksi == 'tolak') {
         // --- LOGIKA REVISI / TOLAK ---
         $nextStatus = ($aksi == 'revisi') ? 0 : 99;
         $action_msg = ($aksi == 'revisi') ? 'Meminta REVISI' : 'MENOLAK Pengajuan';

         if ($currentConfig) {
            $history_list = $this->_generateApprovalList($currentConfig->level_order, false);
         }

         if (!empty($spp->hp_pengaju)) {
            $target_phone = $spp->hp_pengaju;
            $target_role = $spp->nama_pengaju; // Sapaan untuk Pengaju
         }
      } else {
         // --- LOGIKA APPROVE / SETUJUI ---
         $nextConfig = null;

         if ($currentConfig) {
            $nextConfig = $this->md_spp->getNextActiveLevel($currentConfig->level_order);
         } elseif ($is_admin) {
            if ($currentStatus == 0) {
               $nextConfig = $this->md_spp->getFirstActiveLevel();
            }
         }

         if ($nextConfig) {
            // MASIH ADA LEVEL SELANJUTNYA
            $nextStatus = $nextConfig->status_code;
            $target_role = $nextConfig->role_label; // Sapaan untuk Next Approver
            $target_phone = $nextConfig->notif_number;
            $action_msg = 'MENYETUJUI (Lanjut ke ' . $target_role . ')';
            $history_list = $this->_generateApprovalList($nextConfig->level_order, false);
         } else {
            // SUDAH FINAL (LEVEL TERAKHIR)
            $nextStatus = 5;
            $action_msg = 'MENYETUJUI (Status SELESAI)';

            // Target 1: Si Pengaju (Dinonaktifkan demi hemat kuota chat saat ACC semua)
            // $target_role = $spp->nama_pengaju;
            // if (!empty($spp->hp_pengaju)) {
            //    $target_phone = $spp->hp_pengaju;
            // }

            // Target 2: Approval Tahap 1
            $firstConfig = $this->md_spp->getFirstActiveLevel();
            if ($firstConfig && !empty($firstConfig->notif_number)) {
               $secondary_target_phone = $firstConfig->notif_number;
               $secondary_role_label = $firstConfig->role_label; // <--- Ambil Nama Role (misal: Finance/Manager)
            }

            $history_list = $this->_generateApprovalList(null, true);
         }
      }

      $this->db->trans_begin();

      $dataUpdate = [
         'status_approval' => $nextStatus,
         'catatan_revisi' => ($aksi != 'setujui') ? $catatan : null
      ];

      $this->md_spp->update($id_spp, $dataUpdate);

      // --- LOGIKA KIRIM WA (PERSONALIZED) ---

      if (!empty($target_phone) || !empty($secondary_target_phone)) {
         $nama_approver = $this->session->userdata('nama');

         // 1. Buat Body Pesan (Bagian isi yang sama untuk semua orang)
         $body_pesan = $nama_approver . " *" . $action_msg . "* SPP Ekspedisi:\n\n";
         $body_pesan .= "_No SPP : *" . $spp->no_spp . "*_\n";
         $body_pesan .= "_No Invoice : *" . $spp->no_invoice . "*_\n";
         $body_pesan .= "_Total Nilai : *Rp " . number_format($spp->total_nilai, 0, ',', '.') . "*_\n";

         if (isset($history_list)) {
            $body_pesan .= "History Approval :\n" . $history_list . "\n";
         }

         if (!empty($catatan)) {
            $body_pesan .= "\nCatatan: _" . $catatan . "_\n";
         }

         $body_pesan .= "\nMohon agar dapat diperiksa pada Office melalui Link: " . base_url('spp/detail/' . encrypt($id_spp)) . "\n\n";
         $body_pesan .= "_*Terima Kasih*_";


         // 2. Kirim ke Target Utama (Pengaju / Next Approver)
         if (!empty($target_phone)) {
            // Gabungkan Sapaan Target Utama + Body
            $pesan_utama = "*Notifikasi Pengajuan SPP Ekspedisi*\n\n";
            $pesan_utama .= "_Dear *" . $target_role . "*_,\n\n"; // <--- Sapaan Nama Pengaju/Next Role
            $pesan_utama .= $body_pesan;

            $dataWa = [
               'devId' => hostWa('1'),
               'penerima' => $target_phone,
               'pesan' => urlencode($pesan_utama)
            ];
            sendWa($dataWa);
            $this->md_spp->logNotification($id_spp, $target_role, $target_phone, $pesan_utama);
         }

         // 3. Kirim ke Target Kedua (Approval 1) - Hanya jika ada
         if (!empty($secondary_target_phone)) {
            // Gabungkan Sapaan Approval 1 + Body
            $pesan_sekunder = "*Notifikasi Penyelesaian SPP Ekspedisi*\n\n";
            $pesan_sekunder .= "_Dear *" . $secondary_role_label . "*_,\n\n"; // <--- Sapaan Nama Role Approval 1
            $pesan_sekunder .= $body_pesan;

            $dataWa2 = [
               'devId' => hostWa('1'),
               'penerima' => $secondary_target_phone,
               'pesan' => urlencode($pesan_sekunder)
            ];
            sendWa($dataWa2);
            // Log notifikasi dengan role yang sesuai
            $this->md_spp->logNotification($id_spp, $secondary_role_label, $secondary_target_phone, $pesan_sekunder);
         }
      }

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         ajaxReturnDie('error', 'Gagal update status SPP.');
      } else {
         $this->db->trans_commit();
         ajaxReturnDie('success', 'Status SPP berhasil diupdate.');
      }
   }

   // =========================================================================
   // CONFIG SPP
   // =========================================================================

   /**
    * Halaman Config Approval SPP
    */
   public function config()
   {
      // Hanya Admin atau ID 759
      if (!isAdmin() && sessPenggunaId() != '759') {
         redirect('dashboard');
      }

      $page_data['configs'] = $this->md_spp->getAllConfig();
      $page_data['logs'] = $this->md_spp->getAllLogs();

      $page_data['switch']     = $this->id_navbar();
      $page_data['page_name']  = 'spp/v_config_spp';
      $page_data['page_title'] = 'Konfigurasi Approval SPP';
      $page_data['page_desc']  = 'Pengaturan alur approval dan notifikasi SPP';

      $this->load->view('index', $page_data);
   }

   /**
    * Update Setting Config
    */
   public function update_setting()
   {
      if (!$this->input->is_ajax_request()) {
         exit('No direct script access allowed');
      }

      $id_config = $this->input->post('id_config');
      $data = [
         'role_label' => $this->input->post('role_label'),
         'notif_number' => $this->input->post('notif_number'),
         'can_edit' => $this->input->post('can_edit'),
         'is_active' => $this->input->post('is_active')
      ];

      $this->md_spp->updateConfig($id_config, $data);

      echo json_encode(['status' => 'success', 'message' => 'Konfigurasi berhasil diperbarui!']);
   }

   /**
    * Resend WA dari Log - REFACTORED
    * Mengambil data terkini dari database dan menggunakan template yang benar
    */
   public function resend_wa($id_log)
   {
      $log = $this->md_spp->getLogById($id_log);

      if (!$log) {
         $this->session->set_flashdata('error', 'Data log tidak ditemukan');
         redirect('spp/config');
         return;
      }

      // Ambil data SPP terkini dari database
      $spp = $this->md_spp->getById($log->id_spp);

      if (!$spp) {
         $this->session->set_flashdata('error', 'Data SPP tidak ditemukan atau sudah dihapus');
         redirect('spp/config');
         return;
      }

      // Tentukan action status berdasarkan status approval
      $action_msg = 'Mengajukan';
      if ($spp->status_approval == 0) {
         $action_msg = 'Mengirim Revisi';
      } elseif ($spp->status_approval == 99) {
         $action_msg = 'Menolak';
      } elseif ($spp->status_approval > 0 && $spp->status_approval < 5) {
         $action_msg = 'Menyetujui';
      } elseif ($spp->status_approval == 5) {
         $action_msg = 'Menyelesaikan Approval';
      }

      // Generate history approval list
      $history_list = "";

      if ($spp->status_approval == 5) {
         // Semua disetujui
         $history_list = $this->_generateApprovalList(null, true);
      } else {
         // Generate berdasarkan status saat ini
         $configs = $this->md_spp->getAllConfig();
         foreach ($configs as $cfg) {
            if (!$cfg->is_active) continue;

            if ($cfg->status_code < $spp->status_approval) {
               $history_list .= " - " . $cfg->role_label . " (*Disetujui*)\n";
            } elseif ($cfg->status_code == $spp->status_approval) {
               $history_list .= " - " . $cfg->role_label . " (*Menunggu*)\n";
            } else {
               $history_list .= " - " . $cfg->role_label . "\n";
            }
         }
      }

      // Buat pesan dengan template yang benar
      $pesan = "*Notifikasi Pengajuan SPP Ekspedisi*\n\n";
      $pesan .= "_Dear *" . $log->target_role . "*_,\n\n";
      $pesan .= ($spp->nama_pengaju ?? 'User') . " *" . $action_msg . "* SPP Ekspedisi:\n\n";
      $pesan .= "_No SPP : *" . $spp->no_spp . "*_\n";
      $pesan .= "_No Invoice : *" . $spp->no_invoice . "*_\n";
      $pesan .= "_Total Nilai : *Rp " . number_format($spp->total_nilai, 0, ',', '.') . "*_\n";
      $pesan .= "_Grand Total : *Rp " . number_format($spp->grand_total, 0, ',', '.') . "*_\n";

      if (!empty($history_list)) {
         $pesan .= "\nHistory Approval :\n" . $history_list . "\n";
      }

      if (!empty($spp->catatan_revisi)) {
         $pesan .= "\nCatatan: _" . $spp->catatan_revisi . "_\n";
      }

      $pesan .= "\nMohon agar dapat diperiksa pada Office melalui Link: " . base_url('spp/detail/' . encrypt($spp->id_spp)) . "\n\n";
      $pesan .= "_*Terima Kasih*_";

      // Siapkan Data WA
      $dataWa = [
         'devId' => hostWa('1'),
         'penerima' => $log->target_number,
         'pesan' => urlencode($pesan)
      ];

      // Kirim WA
      $result = sendWa($dataWa);

      // Update Counter Resend
      $this->md_spp->incrementResendCount($id_log);

      if ($result) {
         $this->session->set_flashdata('success', 'Notifikasi berhasil dikirim ulang ke ' . $log->target_number . ' dengan data terkini.');
      } else {
         $this->session->set_flashdata('warning', 'Notifikasi dikirim ke ' . $log->target_number . ' (mohon verifikasi manual).');
      }

      redirect('spp/config');
   }

   /**
    * Resend WA SPP dynamically based on current approval status
    */
   public function resend_wa_spp($id_spp_encrypted)
   {
      grantAccessFor('all');

      $id_spp = decrypt($id_spp_encrypted);
      $spp = $this->md_spp->getById($id_spp);

      if (!$spp) {
         $this->session->set_flashdata('error', 'Data SPP tidak ditemukan.');
         redirect('spp');
         return;
      }

      $status = $spp->status_approval;
      $nama_pengaju = $spp->nama_pengaju ?? 'User';
      $nama_user = $this->session->userdata('nama') ?: 'User';

      $sent_count = 0;
      $failed_count = 0;
      $recipients = [];

      if ($status == 0 || $status == 99) {
         // Kirim ke Pengaju
         if (!empty($spp->hp_pengaju)) {
            $target_phone = $spp->hp_pengaju;
            $target_role = $spp->nama_pengaju;
            $action_msg = ($status == 0) ? 'Meminta REVISI' : 'MENOLAK Pengajuan';

            $pesan = "*Notifikasi Pengajuan SPP Ekspedisi*\n\n";
            $pesan .= "_Dear *" . $target_role . "*_,\n\n";
            $pesan .= $nama_user . " *" . $action_msg . "* SPP Ekspedisi:\n\n";
            $pesan .= $this->_buildSppDetailsMessage($spp);

            if (!empty($spp->catatan_revisi)) {
               $pesan .= "\nCatatan: _" . $spp->catatan_revisi . "_\n";
            }

            $pesan .= "\nMohon agar dapat diperiksa pada Office melalui Link: " . base_url('spp/detail/' . encrypt($id_spp)) . "\n\n";
            $pesan .= "_*Terima Kasih*_";

            $dataWa = [
               'devId' => hostWa('1'),
               'penerima' => $target_phone,
               'pesan' => urlencode($pesan)
            ];

            sendWa($dataWa);
            $this->md_spp->logNotification($id_spp, $target_role, $target_phone, $pesan);

            $sent_count++;
            $recipients[] = $target_role . " (" . $target_phone . ")";
         }
      } elseif ($status >= 1 && $status <= 4) {
         // Kirim ke Approver Aktif
         $currentConfig = $this->md_spp->getConfigByStatus($status);
         if ($currentConfig && !empty($currentConfig->notif_number)) {
            $target_phone = $currentConfig->notif_number;
            $target_role = $currentConfig->role_label;
            $history_list = $this->_generateApprovalList($currentConfig->level_order, false);

            $pesan = "*Notifikasi Pengajuan SPP Ekspedisi*\n\n";
            $pesan .= "_Dear *" . $target_role . "*_,\n\n";
            $pesan .= $nama_pengaju . " *MENGAJUKAN* SPP Ekspedisi (Menunggu Approval Bapak/Ibu):\n\n";
            $pesan .= $this->_buildSppDetailsMessage($spp);

            if (!empty($history_list)) {
               $pesan .= "\nHistory Approval :\n" . $history_list . "\n";
            }

            if (!empty($spp->catatan)) {
               $pesan .= "\nCatatan: _" . $spp->catatan . "_\n";
            }

            $pesan .= "\nMohon agar dapat diperiksa pada Office melalui Link: " . base_url('spp/detail/' . encrypt($id_spp)) . "\n\n";
            $pesan .= "_*Terima Kasih*_";

            $dataWa = [
               'devId' => hostWa('1'),
               'penerima' => $target_phone,
               'pesan' => urlencode($pesan)
            ];

            sendWa($dataWa);
            $this->md_spp->logNotification($id_spp, $target_role, $target_phone, $pesan);

            $sent_count++;
            $recipients[] = $target_role . " (" . $target_phone . ")";
         }
      } elseif ($status == 5) {
         // Kirim ke Pengaju (Dinonaktifkan demi hemat kuota chat saat ACC semua)
         /*
         if (!empty($spp->hp_pengaju)) {
            $target_phone = $spp->hp_pengaju;
            $target_role = $spp->nama_pengaju;
            $history_list = $this->_generateApprovalList(null, true);

            $pesan = "*Notifikasi Pengajuan SPP Ekspedisi*\n\n";
            $pesan .= "_Dear *" . $target_role . "*_,\n\n";
            $pesan .= $nama_user . " *MENYETUJUI (Status SELESAI)* SPP Ekspedisi:\n\n";
            $pesan .= $this->_buildSppDetailsMessage($spp);

            if (!empty($history_list)) {
               $pesan .= "\nHistory Approval :\n" . $history_list . "\n";
            }

            $pesan .= "\nMohon agar dapat diperiksa pada Office melalui Link: " . base_url('spp/detail/' . encrypt($id_spp)) . "\n\n";
            $pesan .= "_*Terima Kasih*_";

            $dataWa = [
               'devId' => hostWa('1'),
               'penerima' => $target_phone,
               'pesan' => urlencode($pesan)
            ];

            sendWa($dataWa);
            $this->md_spp->logNotification($id_spp, $target_role, $target_phone, $pesan);

            $sent_count++;
            $recipients[] = $target_role . " (" . $target_phone . ")";
         }
         */

         // Kirim ke Approval Tahap 1 (Accounting/Tax)
         $firstConfig = $this->md_spp->getFirstActiveLevel();
         if ($firstConfig && !empty($firstConfig->notif_number)) {
            $target_phone2 = $firstConfig->notif_number;
            $target_role2 = $firstConfig->role_label;
            $history_list = $this->_generateApprovalList(null, true);

            $pesan2 = "*Notifikasi Penyelesaian SPP Ekspedisi*\n\n";
            $pesan2 .= "_Dear *" . $target_role2 . "*_,\n\n";
            $pesan2 .= $nama_user . " *MENYETUJUI (Status SELESAI)* SPP Ekspedisi:\n\n";
            $pesan2 .= $this->_buildSppDetailsMessage($spp);

            if (!empty($history_list)) {
               $pesan2 .= "\nHistory Approval :\n" . $history_list . "\n";
            }

            $pesan2 .= "\nMohon agar dapat diperiksa pada Office melalui Link: " . base_url('spp/detail/' . encrypt($id_spp)) . "\n\n";
            $pesan2 .= "_*Terima Kasih*_";

            $dataWa2 = [
               'devId' => hostWa('1'),
               'penerima' => $target_phone2,
               'pesan' => urlencode($pesan2)
            ];

            sendWa($dataWa2);
            $this->md_spp->logNotification($id_spp, $target_role2, $target_phone2, $pesan2);

            $sent_count++;
            $recipients[] = $target_role2 . " (" . $target_phone2 . ")";
         }
      }

      if ($sent_count > 0) {
         $recipient_list = implode(', ', $recipients);
         $this->session->set_flashdata('success', 'Berhasil mengirim ulang notifikasi WA ke: ' . $recipient_list);
      } else {
         $this->session->set_flashdata('error', 'Gagal mengirim ulang notifikasi WA (nomor tujuan kosong/tidak aktif).');
      }

      redirect('spp');
   }

   /**
    * Formats SPP financial values for WA body message
    */
   private function _buildSppDetailsMessage($spp)
   {
      $pesan = "_No SPP : *" . $spp->no_spp . "*_\n";
      $pesan .= "_No Invoice : *" . $spp->no_invoice . "*_\n";
      $pesan .= "_Total Tagihan : *Rp " . number_format($spp->total_nilai, 0, ',', '.') . "*_\n";

      if ($spp->ppn > 0) {
         $pesan .= "_PPN : *Rp " . number_format($spp->ppn, 0, ',', '.') . "*_\n";
      }
      if ($spp->biaya_lainnya > 0) {
         $pesan .= "_Biaya Lain : *Rp " . number_format($spp->biaya_lainnya, 0, ',', '.') . "*_\n";
      }
      if ($spp->diskon > 0) {
         $pesan .= "_Diskon : *-Rp " . number_format($spp->diskon, 0, ',', '.') . "*_\n";
      }

      $pesan .= "_Grand Total : *Rp " . number_format($spp->grand_total, 0, ',', '.') . "*_\n";

      if ($spp->nilai_bukti_potong > 0) {
         $pesan .= "_Bukti Potong : *(Rp " . number_format($spp->nilai_bukti_potong, 0, ',', '.') . ")*_\n";
         $pesan .= "_Transfer Cash : *Rp " . number_format($spp->nilai_pembayaran, 0, ',', '.') . "*_\n";
      }

      return $pesan;
   }


   // =========================================================================
   // HELPER: Generate Approval History List
   // =========================================================================
   private function _generateApprovalList($target_level_order = null, $is_finish = false)
   {
      $configs = $this->md_spp->getAllConfig();
      $message = "";

      foreach ($configs as $cfg) {
         if (!$cfg->is_active) continue;

         // CASE A: Status SELESAI (Semua Disetujui)
         if ($is_finish) {
            $message .= " - " . $cfg->role_label . " (*Disetujui*)\n";
            continue;
         }

         // CASE B: Bandingkan level jabatan
         if ($cfg->level_order < $target_level_order) {
            $message .= " - " . $cfg->role_label . " (*Disetujui*)\n";
         } elseif ($cfg->level_order == $target_level_order) {
            $message .= " - " . $cfg->role_label . " (*Menunggu*)\n";
         } else {
            $message .= " - " . $cfg->role_label . "\n";
         }
      }

      return $message;
   }

   // =========================================================================
   // PRINT & EXPORT FUNCTIONS
   // =========================================================================

   /**
    * Print Single SPP
    */
   public function print_spp($id_spp_encrypted)
   {
      grantAccessFor('all');

      $this->load->library('pdfgenerator');

      $id_spp = decrypt($id_spp_encrypted);
      $spp = $this->md_spp->getById($id_spp);

      if (!$spp) show_404();

      $page_data['spp'] = $spp;
      $page_data['tagihan_list'] = $this->md_spp->getTagihanBySpp($id_spp);

      // Generate PDF
      $file_pdf = 'SPP_' . ($spp->no_spp ?? 'NoSPP');
      $paper = 'A4';
      $orientation = 'portrait';
      $html = $this->load->view('pages/v_print/v_print_spp', $page_data, true);

      $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
   }

   /**
    * Print Rekap SPP
    */
   public function print_rekap()
   {
      grantAccessFor('all');

      $this->load->library('pdfgenerator');

      // Get filters from GET parameters
      $filters = [];
      $status = $this->input->get('status');
      if ($status !== null && $status !== '') {
         $filters['status'] = $status;
      }

      $start_date = $this->input->get('start_date');
      $end_date = $this->input->get('end_date');

      // Ambil data SPP
      $list_spp = $this->md_spp->getAllSppForExport($filters);

      // Filter by date if provided
      if (!empty($start_date) || !empty($end_date)) {
         $filtered = [];
         foreach ($list_spp as $spp) {
            $created = date('Y-m-d', strtotime($spp->created_at));
            if (!empty($start_date) && $created < $start_date) continue;
            if (!empty($end_date) && $created > $end_date) continue;
            $filtered[] = $spp;
         }
         $list_spp = $filtered;
      }

      // Prepare filter labels
      $filter_periode = '';
      if (!empty($start_date) && !empty($end_date)) {
         $filter_periode = date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date));
      } elseif (!empty($start_date)) {
         $filter_periode = 'Dari ' . date('d M Y', strtotime($start_date));
      } elseif (!empty($end_date)) {
         $filter_periode = 'Sampai ' . date('d M Y', strtotime($end_date));
      }

      $page_data['list_spp'] = $list_spp;
      $page_data['filter_periode'] = $filter_periode;

      // Generate PDF
      $file_pdf = 'Rekap_SPP_' . date('Y-m-d');
      $paper = 'A4';
      $orientation = 'landscape';
      $html = $this->load->view('pages/v_print/v_print_spp_rekap', $page_data, true);

      $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
   }

   /**
    * Export Rekap SPP ke Excel
    */
   public function export_excel()
   {
      grantAccessFor('all');

      // Get filters
      $filters = [];
      $status = $this->input->get('status');
      if ($status !== null && $status !== '') {
         $filters['status'] = $status;
      }

      $start_date = $this->input->get('start_date');
      $end_date = $this->input->get('end_date');

      $list_spp = $this->md_spp->getAllSppForExport($filters);

      // Filter by date
      if (!empty($start_date) || !empty($end_date)) {
         $filtered = [];
         foreach ($list_spp as $spp) {
            $created = date('Y-m-d', strtotime($spp->created_at));
            if (!empty($start_date) && $created < $start_date) continue;
            if (!empty($end_date) && $created > $end_date) continue;
            $filtered[] = $spp;
         }
         $list_spp = $filtered;
      }

      // Set headers for Excel
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="Rekap_SPP_' . date('Y-m-d_His') . '.xls"');
      header('Cache-Control: max-age=0');

      // Output Excel
      echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">';
      echo '<head><meta charset="UTF-8"></head>';
      echo '<body>';
      echo '<table border="1">';
      echo '<tr><th colspan="8" style="font-size:16px; font-weight:bold; text-align:center;">REKAP SURAT PERMINTAAN PEMBAYARAN (SPP)</th></tr>';
      echo '<tr><th colspan="8">Tanggal Export: ' . date('d F Y H:i') . '</th></tr>';
      echo '<tr><td colspan="8"></td></tr>';
      echo '<tr style="background-color:#C6DEFF; font-weight:bold;">';
      echo '<th>No</th>';
      echo '<th>No. SPP</th>';
      echo '<th>No. Invoice</th>';
      echo '<th>Tgl Pengajuan</th>';
      echo '<th>Pengaju</th>';
      echo '<th>Jml Tagihan</th>';
      echo '<th>Total Nilai</th>';
      echo '<th>Status</th>';
      echo '</tr>';

      $no = 1;
      $total = 0;
      foreach ($list_spp as $spp) {
         $total += $spp->total_nilai ?? 0;
         $status = 'Proses';
         if ($spp->status_approval == 5) $status = 'Approved';
         elseif ($spp->status_approval == 99) $status = 'Ditolak';
         elseif ($spp->status_approval == 0) $status = 'Revisi';

         echo '<tr>';
         echo '<td>' . $no++ . '</td>';
         echo '<td>' . ($spp->no_spp ?? '-') . '</td>';
         echo '<td>' . ($spp->no_invoice ?? '-') . '</td>';
         echo '<td>' . date('d/m/Y', strtotime($spp->created_at)) . '</td>';
         echo '<td>' . ($spp->nama_pengaju ?? '-') . '</td>';
         echo '<td style="text-align:center;">' . ($spp->jumlah_tagihan ?? 0) . '</td>';
         echo '<td style="text-align:right;">' . number_format($spp->total_nilai ?? 0, 0, ',', '.') . '</td>';
         echo '<td>' . $status . '</td>';
         echo '</tr>';
      }

      echo '<tr style="font-weight:bold; background-color:#f8f9fa;">';
      echo '<td colspan="6" style="text-align:right;">TOTAL:</td>';
      echo '<td style="text-align:right;">' . number_format($total, 0, ',', '.') . '</td>';
      echo '<td></td>';
      echo '</tr>';

      echo '</table>';
      echo '</body></html>';
   }

   /**
    * Upload Bukti Pembayaran SPP (Hanya untuk User ID 1 atau 106)
    * Aksi ini dilakukan setelah SPP Approved dan pembayaran telah dilakukan offline
    */
   public function upload_bukti_bayar()
   {
      grantAccessFor('all');

      // Validasi: Hanya user tertentu yang bisa upload
      $id_user_login = sessPenggunaId();
      if (!in_array($id_user_login, [1, 106])) {
         $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Akses Ditolak!</strong> Anda tidak memiliki akses untuk upload bukti pembayaran.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>');
         redirect(base_url('spp'));
         return;
      }

      $id_spp = decrypt($this->input->post('id_spp'));
      $link_bukti_bayar = $this->input->post('link_bukti_bayar');
      $keterangan_bayar = $this->input->post('keterangan_bayar');
      $tgl_bayar = $this->input->post('tgl_bayar');

      // Validasi input
      if (empty($link_bukti_bayar)) {
         $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Link bukti pembayaran wajib diisi.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>');
         redirect(base_url('spp/detail/' . encrypt($id_spp)));
         return;
      }

      // Update data SPP
      $data_spp = [
         'link_bukti_bayar' => $link_bukti_bayar,
         'keterangan_bayar' => $keterangan_bayar,
         'status_bayar' => 'sudah_dibayar',
         'tgl_bayar' => !empty($tgl_bayar) ? $tgl_bayar : date('Y-m-d'),
         'uploaded_by' => $id_user_login
      ];

      $this->md_spp->update($id_spp, $data_spp);

      // Update semua tagihan dalam SPP menjadi sudah dibayar
      $tagihan_list = $this->md_spp->getTagihanBySpp($id_spp);
      foreach ($tagihan_list as $tagihan) {
         $data_tagihan = [
            'status_bayar' => 'sudah_dibayar',
            'link_bukti_bayar' => $link_bukti_bayar,
            'keterangan_bayar' => $keterangan_bayar,
            'tgl_bayar' => !empty($tgl_bayar) ? $tgl_bayar : date('Y-m-d')
         ];
         $this->md_tagihan->update($tagihan->id_tagihan, $data_tagihan);
      }

      // Kirim Notifikasi ke Group Gudang
      $spp = $this->md_spp->getById($id_spp);
      $this->send_notification_bukti_bayar($spp, $link_bukti_bayar, $keterangan_bayar);

      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
         <strong>Berhasil!</strong> Bukti pembayaran berhasil diupload dan status pembayaran telah diperbarui.
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
         </button>
      </div>');
      redirect(base_url('spp/detail/' . encrypt($id_spp)));
   }

   /**
    * Kirim Notifikasi ke Group Gudang saat Upload Bukti Pembayaran
    */
   private function send_notification_bukti_bayar($spp, $link_bukti, $keterangan)
   {
      // Ambil info user yang upload
      $user_upload = $this->md_pengguna->getById(sessPenggunaId());
      $nama_uploader = is_array($user_upload) ? $user_upload[0]->nama : $user_upload->nama;

      // Format tanggal bayar
      $tgl_bayar_formatted = !empty($spp->tgl_bayar) ? date('d/m/Y', strtotime($spp->tgl_bayar)) : date('d/m/Y');

      // ============ PRODUCTION: Uncomment untuk kirim ke Group Gudang & Marketing ============
      // $this->notifWaBuktiBayarSppGroup(2, [
      //    'idPenerima1' => 'MARKETING PT. VYM',
      //    'idPenerima2' => 'Gudang PT. VYM',
      //    'namaPengaju' => $nama_uploader,
      //    'no_spp' => $spp->no_spp ?? '-',
      //    'no_invoice' => $spp->no_invoice ?? '-',
      //    'grand_total' => 'Rp ' . number_format($spp->grand_total ?? 0, 0, ',', '.'),
      //    'tgl_bayar' => $tgl_bayar_formatted,
      //    'link_bukti' => $link_bukti,
      //    'keterangan' => !empty($keterangan) ? $keterangan : 'Pembayaran telah dilakukan',
      //    'url' => base_url('spp/detail/' . encrypt($spp->id_spp))
      // ]);
      // ========================================================================================

      // ============ DEVELOPMENT: Testing ke nomor pribadi ============
      // Format nomor seperti approval tagihan: 628xxx (tanpa @c.us, akan ditambahkan di helper)
      $data_notif_test = [
         'target_phone'    => '082250091745',  // Nomor Testing (format 08xxx)
         'nama_pengaju'    => $nama_uploader,
         'no_spp'          => $spp->no_spp ?? '-',
         'no_invoice'      => $spp->no_invoice ?? '-',
         'grand_total'     => 'Rp ' . number_format($spp->grand_total ?? 0, 0, ',', '.'),
         'tgl_bayar'       => $tgl_bayar_formatted,
         'link_bukti'      => $link_bukti,
         'keterangan'      => !empty($keterangan) ? $keterangan : 'Pembayaran telah dilakukan',
         'url'             => base_url('spp/detail/' . encrypt($spp->id_spp))
      ];
      waBuktiBayarSppPersonal($data_notif_test);  // Fungsi baru untuk kirim ke personal
      // ===============================================================
   }

   /**
    * Update Bukti Pembayaran per Tagihan (dalam SPP)
    */
   public function update_bukti_bayar_tagihan()
   {
      grantAccessFor('all');

      // Validasi: Hanya user tertentu yang bisa upload
      $id_user_login = sessPenggunaId();
      if (!in_array($id_user_login, [1, 106])) {
         echo json_encode(['success' => false, 'message' => 'Akses ditolak!']);
         return;
      }

      $id_tagihan = decrypt($this->input->post('id_tagihan'));
      $link_bukti_bayar = $this->input->post('link_bukti_bayar');
      $keterangan_bayar = $this->input->post('keterangan_bayar');
      $tgl_bayar = $this->input->post('tgl_bayar');

      if (empty($link_bukti_bayar)) {
         echo json_encode(['success' => false, 'message' => 'Link bukti pembayaran wajib diisi!']);
         return;
      }

      // Update tagihan
      $data_tagihan = [
         'status_bayar' => 'sudah_dibayar',
         'link_bukti_bayar' => $link_bukti_bayar,
         'keterangan_bayar' => $keterangan_bayar,
         'tgl_bayar' => !empty($tgl_bayar) ? $tgl_bayar : date('Y-m-d')
      ];

      $this->md_tagihan->update($id_tagihan, $data_tagihan);

      echo json_encode(['success' => true, 'message' => 'Bukti pembayaran berhasil diupdate!']);
   }

   /**
    * Notifikasi WhatsApp ke Group (Pattern seperti Tracking.php)
    */
   public function notifWaBuktiBayarSppGroup($ulang, $detail)
   {
      for ($i = 1; $i <= $ulang; $i++) {
         if ($i == 1) {
            $idpenerima = $detail['idPenerima1'];
            $penerima = '_Team Marketing_';
         } else if ($i == 2) {
            $idpenerima = $detail['idPenerima2'];
            $penerima = '_Team Warehouse_';
         }

         $dataWa = [
            'noPenerima' => $idpenerima,
            'namaPenerima' => $penerima,
            'namaPengaju' => $detail['namaPengaju'],
            'no_spp' => $detail['no_spp'],
            'no_invoice' => $detail['no_invoice'],
            'grand_total' => $detail['grand_total'],
            'tgl_bayar' => $detail['tgl_bayar'],
            'link_bukti' => $detail['link_bukti'],
            'keterangan' => $detail['keterangan'],
            'url' => $detail['url']
         ];

         waBuktiBayarSpp($dataWa);
      }
   }
}
