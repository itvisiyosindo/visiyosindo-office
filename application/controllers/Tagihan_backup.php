<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tagihan extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
    $this->load->library('session');

    // Load Models
    $this->load->model('md_tagihan');
    $this->load->model('md_tracking');
    $this->load->model('md_tagihan_config');
    $this->load->model('md_pengguna');
    $this->load->helper('whatsapp_helper');
  }

  function id_navbar()
  {
    $id_navbar = "helpdesk";
    return $id_navbar;
  }

  // =========================================================================
  // PUBLIC VIEWS
  // =========================================================================

  public function index()
  {
    $all_data = $this->md_tagihan->getAllTagihan();
    $approval_configs = $this->md_tagihan_config->getAllConfig();

    $user = $this->md_pengguna->getById(sessPenggunaId());
    $my_jabatan = is_array($user) ? $user[0]->jabatan : $user->jabatan;
    $is_admin = ($this->session->userdata('role') == 'Administrator');

    $filter_type = $this->input->get('filter');
    $filtered_data = [];

    if (empty($filter_type)) {
      $filtered_data = $all_data;
    } else {
      foreach ($all_data as $row) {

        if ($filter_type == 'approved') {
          if ($row->status_approval == 5) $filtered_data[] = $row;
        } elseif ($filter_type == 'waiting') {
          // Map user jabatan to approval level
          $my_target_level = null;
          foreach ($approval_configs as $cfg) {
            if (strtolower(trim($cfg->role_label)) == strtolower(trim($my_jabatan))) {
              $my_target_level = $cfg->status_code;
              break;
            }
          }

          // Logic: Admin sees all pending; User sees specific level only
          $show_row = false;
          if ($is_admin && !in_array($row->status_approval, [5, 99])) {
            $show_row = true;
          } elseif ($my_target_level !== null && $row->status_approval == $my_target_level) {
            $show_row = true;
          }

          if ($show_row) $filtered_data[] = $row;
        }
      }
    }

    // Send Data to View
    $page_data['list_tagihan']         = $filtered_data;
    $page_data['approval_configs']     = $approval_configs;
    $page_data['current_user_jabatan'] = $my_jabatan;
    $page_data['filter_active']        = $filter_type;

    $page_data['switch']      = "helpdesk";
    $page_data['page_name']   = 'tagihan/v_list_tagihan';
    $page_data['page_title']  = 'Pengajuan Tagihan Ekspedisi';
    $page_data['page_desc']   = 'Data pengajuan tagihan ekspedisi yang masuk untuk proses approval';

    $this->load->view('index', $page_data);
  }

  public function my_submission()
  {
    // Ambil ID User Login
    // $id_user = sessPenggunaId();

    // Panggil Model Khusus User
    $page_data['list_tagihan']     = $this->md_tagihan->getAllTagihan();

    // Config View
    $page_data['switch']      = "helpdesk"; // Sesuaikan switch menu anda
    $page_data['page_name']   = 'tagihan/v_my_tagihan'; // View baru khusus pengaju
    $page_data['page_title']  = 'Pengajuan Tagihan Saya';
    $page_data['page_desc']   = 'Riwayat pengajuan tagihan ekspedisi anda';

    $this->load->view('index', $page_data);
  }

  public function detail($id_tagihan_encrypted)
  {
    $id_tagihan = decrypt($id_tagihan_encrypted);

    // 1. Ambil Data Detail Utama
    $data = $this->md_tagihan->getDetailFull($id_tagihan);
    if (!$data) show_404();

    // 2. [FIX] AMBIL DETAIL BARANG ARRAY (Agar tabel bisa diload)
    $this->load->model('md_detail_barang_keluar');
    $detail_barang_list = []; // Default kosong

    // Cek apakah ada ID Pengeluaran Barang (dari Model yang sudah diedit tadi)
    if (!empty($data->id_pengeluaran_barang)) {
      $detail_barang_list = $this->md_detail_barang_keluar->getByIdPengeluaranBarang($data->id_pengeluaran_barang);
    }

    // 3. Logic Fallback Nama Barang (String) - Jika di tracking kosong
    if (empty($data->nama_barang) || $data->nama_barang == '-') {
      $nama_barang_temp = [];
      if (!empty($detail_barang_list)) {
        foreach ($detail_barang_list as $item) $nama_barang_temp[] = $item->nama_barang;
        $data->nama_barang = implode(', ', $nama_barang_temp);
      }
    }

    // 4. Kirim Data ke View
    // [PENTING] Kirim variabel array ini agar View tidak error
    $page_data['detail_barang_keluar'] = $detail_barang_list;

    // [PENTING] Kirim sebagai 'tracking' juga karena View Anda memakai $tracking->nama_barang
    $page_data['tracking'] = $data;
    $page_data['detail']   = $data; // Tetap kirim 'detail' untuk kompatibilitas lain

    $page_data['tracking_history_log'] = $this->md_tagihan->getHistoryStatus($data->id_tracking);
    $page_data['approval_configs']     = $this->md_tagihan_config->getAllConfig();

    // Cek User Login
    $id_user = sessPenggunaId();
    $user    = $this->md_pengguna->getById($id_user);
    $page_data['current_user_jabatan'] = is_array($user) ? $user[0]->jabatan : $user->jabatan;

    // Cek Admin (Override)
    $role = $this->session->userdata('nama');
    $page_data['is_admin'] = ($role == 'Administrator');

    // Template Config
    $page_data['page_title'] = 'Detail Pengajuan Tagihan Ekspedisi';
    $page_data['page_desc']  = 'No. SJ: ' . $data->no_sj;
    $page_data['page_name']  = 'tagihan/v_detail_tagihan';
    $page_data['switch']     = 'helpdesk';

    $this->load->view('index', $page_data);
  }

  public function detailTracking($param1)
  {
    grantAccessFor('all');

    $page_data['switch']          = $this->id_navbar();
    $page_data['list_gudang']   = $this->md_tracking->getAllGudang();
    $page_data['list_cust']     = $this->md_tracking->getAllCustomer();
    $page_data['list_eks']      = $this->md_tracking->getAllEkspedisi();
    $page_data['data_tracking']    = $this->md_tracking->getByWhereID(['t.id_tracking' => decrypt($param1)]);
    $page_data['data_status']      = $this->md_tracking->getUpdateById(['t.id_tracking' => decrypt($param1)]);

    //Versi Terbaru
    $IDTRACK = $this->md_tracking->getByWhereID(['t.id_tracking' => decrypt($param1)]);
    /*if (!empty($IDTRACK)) {
            if ($IDTRACK[0]->id_tracking <= 165) {
                $page_data['page_name'] = 'tracking/v_detail_tracking_1';
            } else {
                $page_data['page_name'] = 'tracking/v_detail_tracking';   
            }
        }*/
    $page_data['page_name'] = 'tracking/v_detail_tracking';

    $page_data['detail_barang_keluar'] = $this->md_detail_barang_keluar->getByIdPengeluaranBarang($IDTRACK[0]->id_pengeluaran_barang);

    $page_data['page_title']    = 'Tracking Barang';
    $page_data['page_desc']      = 'Management Data Tracking Barang';
    $this->load->view('index', $page_data);
  }

  public function ajukan($id_tracking_encrypted)
  {
    $id_tracking = decrypt($id_tracking_encrypted);

    // Cek Existing
    $exists = $this->md_tagihan->checkExisting($id_tracking);
    if ($exists && !in_array($exists->status_approval, [0, 99])) {
      $this->session->set_flashdata('error_message', 'Data ini sedang dalam proses approval (Status: ' . $exists->status_approval . ').');
      redirect('tracking');
    }

    $tracking = $this->md_tagihan->getTrackingById($id_tracking);
    if (!$tracking) show_404();

    if (trim($tracking->nama_ekspedisi) == 'Diantarkan Langsung') {
      $this->session->set_flashdata('error_message', 'Pengiriman Internal tidak butuh tagihan.');
      redirect('tracking');
      return;
    }

    // Logic Barang (Sama seperti detail, agar konsisten)
    if (empty($tracking->nama_barang) || $tracking->id_tracking > 371) {
      $this->load->model('md_detail_barang_keluar');
      if (!empty($tracking->id_pengeluaran_barang)) {
        $items = $this->md_detail_barang_keluar->getByIdPengeluaranBarang($tracking->id_pengeluaran_barang);
        $nama_barang_list = [];
        if (!empty($items)) {
          foreach ($items as $item) $nama_barang_list[] = $item->nama_barang;
          $tracking->nama_barang = implode(', ', $nama_barang_list);
        } else {
          $tracking->nama_barang = '-';
        }
      }
    }

    $this->load->model('md_detail_barang_keluar');
    $detail_barang_list = []; // Default array kosong

    if (!empty($tracking->id_pengeluaran_barang)) {
      // Ambil data detail lengkap
      $detail_barang_list = $this->md_detail_barang_keluar->getByIdPengeluaranBarang($tracking->id_pengeluaran_barang);

      // Logic lama (implode nama barang) tetap biarkan untuk kebutuhan fallback
      if (empty($tracking->nama_barang) || $tracking->id_tracking > 371) {
        $nama_barang_temp = [];
        if (!empty($detail_barang_list)) {
          foreach ($detail_barang_list as $item) $nama_barang_temp[] = $item->nama_barang;
          $tracking->nama_barang = implode(', ', $nama_barang_temp);
        } else {
          $tracking->nama_barang = '-';
        }
      }
    }

    // [WAJIB TAMBAH] Kirim variabel ini ke View
    $page_data['detail_barang_keluar'] = $detail_barang_list;

    $page_data['tracking_history_log'] = $this->md_tagihan->getHistoryStatus($tracking->id_tracking);

    $page_data['tracking']    = $tracking;
    $page_data['is_revisi']   = ($exists) ? true : false;
    $page_data['data_lama']   = $exists;

    $page_data['switch']      = "helpdesk";
    $page_data['page_name']   = 'tagihan/v_form_pengajuan';
    $page_data['page_title']  = 'Form Pengajuan Tagihan Eskpedisi';
    $page_data['page_desc']   = 'No. SJ: ' . $tracking->no_sj;

    $this->load->view('index', $page_data);
  }

  public function submit_pengajuan()
  {
    // 1. Validasi Input ID Tracking
    $id_tracking = $this->input->post('id_tracking');
    if (!$id_tracking) ajaxReturnDie('error', 'ID Tracking tidak valid.');

    // -----------------------------------------------------------
    // [UPDATE] 1. TANGKAP DATA DARI FORM (Nilai & Tanggal & No Invoice)
    // -----------------------------------------------------------
    $nilai_tagihan   = $this->input->post('nilai_tagihan');
    $tanggal_invoice = $this->input->post('tanggal_invoice');
    $no_invoice      = $this->input->post('no_invoice'); // NEW: Nomor Invoice
    // -----------------------------------------------------------

    // 2. Update Biaya Real (Opsional: Jika ada input hidden biaya_real, jika tidak bisa diabaikan)
    $biaya_input = $this->input->post('biaya_real');
    if ($biaya_input) {
      $biaya_clean = str_replace('.', '', $biaya_input);
      $this->md_tagihan->updateTrackingBiaya($id_tracking, $biaya_clean);
    }

    // 3. Ambil Level Approval Aktif Pertama
    $firstConfig = $this->md_tagihan_config->getFirstActiveLevel();
    if (!$firstConfig) {
      ajaxReturnDie('error', 'Konfigurasi Approval belum diatur.');
    }

    $initialStatus = $firstConfig->status_code;
    $targetRole    = $firstConfig->role_label;
    $targetPhone   = $firstConfig->notif_number;

    // 4. Persiapan Data untuk Disimpan
    $is_revisi  = $this->input->post('is_revisi');
    $id_tagihan = $this->input->post('id_tagihan');

    $aksi_label = ($is_revisi == 'true') ? "Mengirim Revisi" : "Mengajukan Baru";

    $data = [
      'id_tracking'       => $id_tracking,
      'id_ekspedisi'      => $this->input->post('id_ekspedisi'),
      'no_invoice'        => $no_invoice,         // NEW: Nomor Invoice
      'link_invoice'      => $this->input->post('link_invoice'),
      'link_faktur_pajak' => $this->input->post('link_faktur_pajak'),
      'link_dokumen_lain' => $this->input->post('link_dokumen_lain'),

      // [UPDATE] MASUKKAN KE ARRAY DATA
      'nilai_tagihan'     => $nilai_tagihan,     // Masuk ke kolom nilai_tagihan
      'tanggal_invoice'   => $tanggal_invoice,   // Masuk ke kolom tanggal_invoice

      'status_approval'   => $initialStatus,
      'created_by'        => sessPenggunaId(),
      'catatan_revisi'    => null
    ];

    // 5. Transaksi Database
    $this->db->trans_begin();

    if ($is_revisi == 'true') {
      // Update data lama
      $this->md_tagihan->update($id_tagihan, $data);
      $target_id = $id_tagihan;
    } else {
      // Insert data baru
      $target_id = $this->md_tagihan->insert($data);
    }

    // 6. Persiapan WA
    $trackDetail     = $this->md_tagihan->getTrackingById($id_tracking);
    $nama_pengaju    = $this->session->userdata('nama') ?: 'User';

    // Generate List History
    $history_list = $this->_generate_approval_list($firstConfig->level_order, false);

    $waData = [
      'target_phone'    => $targetPhone,
      'target_role'     => $targetRole,
      'nama_pengaju'    => $nama_pengaju,
      'action_status'   => $aksi_label,
      'no_sj'           => $trackDetail->no_sj,
      'ekspedisi'       => $trackDetail->nama_ekspedisi ?? '-',
      'history_info'    => $history_list,
      'link_invoice'    => $data['link_invoice'],
      'link_url'        => base_url('tagihan'),
      'catatan'         => ''
    ];

    // 7. Kirim WA & Log
    $wa_status = waTagihanApproval($waData);
    $this->md_tagihan_config->logNotification(
      $target_id,
      $targetRole,
      $targetPhone,
      "Notif Pengajuan (" . $aksi_label . ") ke " . $targetRole
    );

    // 8. Finalisasi
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      ajaxReturnDie('error', 'Terjadi kesalahan Database. Data gagal disimpan.');
    } else {
      $this->db->trans_commit();
      $msg = ($wa_status) ? 'Pengajuan Berhasil & Notifikasi Terkirim.' : 'Pengajuan Berhasil, namun WA gagal terkirim.';
      ajaxReturnDie('success', $msg, base_url('tracking'));
    }
  }


  // =========================================================================
  // LOGIC APPROVAL (DINAMIS & ADMIN BYPASS)
  // =========================================================================
  public function process_approval()
  {
    $id_tagihan    = $this->input->post('id_tagihan');
    $aksi          = $this->input->post('aksi'); // setujui, tolak, revisi
    $catatan       = $this->input->post('catatan');
    $currentStatus = $this->input->post('current_status');
    $is_admin      = ($this->session->userdata('role') == 'Administrator');

    $detail = $this->md_tagihan->getDetailFull($id_tagihan);
    if (!$detail) ajaxReturnDie('error', 'Data tidak ditemukan');

    $hardcode_phone_pimpinan = '6289653049314';

    $nextStatus    = $currentStatus;
    $target_phone  = '';
    $target_role   = '';
    $action_msg    = '';
    $additional_phones = [];
    $history_list = "";

    $currentConfig = $this->md_tagihan_config->getConfigByStatus($currentStatus);

    if ($aksi == 'revisi' || $aksi == 'tolak') {
      $nextStatus = ($aksi == 'revisi') ? 0 : 99;
      $action_msg = ($aksi == 'revisi') ? '*Meminta REVISI*' : '*MENOLAK Pengajuan*';
      $approver_label = $currentConfig ? $currentConfig->role_label : 'Approver';
      $history_list   = " - " . $approver_label . " (*" . ucfirst($aksi) . "*)";

      if (!empty($detail->hp_pengaju)) {
        $target_phone = $detail->hp_pengaju;
        $target_role  = '_*' . $detail->jabatan_pengaju . '*_';
      }
      if (!empty($hardcode_phone_pimpinan)) {
        $additional_phones[] = $hardcode_phone_pimpinan;
      }
    } else {
      // --- APPROVE / SETUJUI ---
      $nextConfig = null;
      if ($currentConfig) {
        $nextConfig = $this->md_tagihan_config->getNextActiveLevel($currentConfig->level_order);
      } elseif ($is_admin) {
        if ($currentStatus == 0) $nextConfig = $this->md_tagihan_config->getFirstActiveLevel();
      }

      if ($nextConfig) {
        $nextStatus   = $nextConfig->status_code;
        $target_role  = $nextConfig->role_label;
        $target_phone = $nextConfig->notif_number;
        $action_msg   = 'MENYETUJUI (*Lanjut ke ' . $target_role . '*)';
        $history_list = $this->_generate_approval_list($nextConfig->level_order, false);
      } else {
        $nextStatus   = 5;
        $action_msg   = 'MENYETUJUI (*Status SELESAI*)';
        $target_role  = $detail->nama_pengaju;
        if (!empty($detail->hp_pengaju)) {
          $target_phone = $detail->hp_pengaju;
        }
        $history_list = $this->_generate_approval_list(null, true);
      }
    }

    $this->db->trans_begin();

    $dataUpdate = [
      'status_approval' => $nextStatus,
      'catatan_revisi'  => ($aksi != 'setujui') ? $catatan : null
    ];

    // --- LOGIKA SIMPAN LINK DOKUMEN ---
    // Mengambil input dari name="link_dokumen_lain" di Modal
    $input_link = $this->input->post('link_dokumen_lain');

    if (!empty($input_link)) {
      // Simpan ke kolom link_dokumen_lain (untuk dokumen pendukung lainnya)
      $dataUpdate['link_dokumen_lain'] = $input_link;
    }

    $this->md_tagihan->update($id_tagihan, $dataUpdate);

    // --- PREPARE WA DATA ---
    $waData = [
      'target_phone'    => $target_phone,
      'target_role'     => $target_role,
      'nama_pengaju'    => $this->session->userdata('nama'),
      'action_status'   => $action_msg,
      'no_sj'           => $detail->no_sj,
      'ekspedisi'       => $detail->nama_ekspedisi,
      'biaya_formatted' => "Rp " . number_format($detail->biaya_real, 0, ',', '.'),
      'history_info'    => $history_list,
      'link_invoice'    => $detail->link_invoice,
      'link_url'        => base_url('tagihan'),
      'catatan'         => $catatan
    ];

    if (!empty($target_phone)) {
      waTagihanApproval($waData);
      $this->md_tagihan_config->logNotification($id_tagihan, $target_role, $target_phone, $action_msg);
    }

    if (!empty($additional_phones) && ($aksi == 'revisi' || $aksi == 'tolak')) {
      foreach ($additional_phones as $phone) {
        $waData_add = $waData;
        $waData_add['target_phone'] = $phone;
        $waData_add['target_role']  = $detail->nama_pengaju;
        waTagihanApproval($waData_add);
        $this->md_tagihan_config->logNotification($id_tagihan, 'IT Support', $phone, $action_msg);
      }
    }

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      ajaxReturnDie('error', 'Database Error.');
    } else {
      $this->db->trans_commit();
      ajaxReturnDie('success', 'Proses Berhasil!', true);
    }
  }

  // =========================================================================
  // HELPER INTERNAL CONTROLLER (PRIVATE)
  // =========================================================================
  private function _generate_approval_list($target_level_order = null, $is_finish = false)
  {
    // 1. Ambil semua list jabatan approval yang aktif
    $configs = $this->md_tagihan_config->getAllActiveConfig();

    $message = "";

    foreach ($configs as $cfg) {
      // CASE A: Status SELESAI (Semua Disetujui)
      if ($is_finish) {
        $message .= " - " . $cfg->role_label . " (*Disetujui*)\n";
        continue;
      }

      // CASE B: Bandingkan level jabatan
      if ($cfg->level_order < $target_level_order) {
        // Level sudah lewat
        $message .= " - " . $cfg->role_label . " (*Disetujui*)\n";
      } elseif ($cfg->level_order == $target_level_order) {
        // Posisi Sekarang
        $message .= " - " . $cfg->role_label . " (*Menunggu*)\n";
      } else {
        // Level belum sampai (tampilkan nama saja)
        $message .= " - " . $cfg->role_label . "\n";
      }
    }

    return $message;
  }

  // =========================================================================
  // PRINT & EXPORT FUNCTIONS
  // =========================================================================

  /**
   * Print Single Tagihan Ekspedisi
   */
  public function print_tagihan($id_tagihan_encrypted)
  {
    grantAccessFor('all');

    $this->load->library('pdfgenerator');

    $id_tagihan = decrypt($id_tagihan_encrypted);

    // Ambil Data Detail Utama
    $data = $this->md_tagihan->getDetailFull($id_tagihan);
    if (!$data) show_404();

    // Ambil Detail Barang
    $this->load->model('md_detail_barang_keluar');
    $detail_barang_list = [];
    if (!empty($data->id_pengeluaran_barang)) {
      $detail_barang_list = $this->md_detail_barang_keluar->getByIdPengeluaranBarang($data->id_pengeluaran_barang);
    }

    // Send to View
    $page_data['detail'] = $data;
    $page_data['detail_barang_keluar'] = $detail_barang_list;

    // Generate PDF
    $file_pdf = 'Tagihan_Ekspedisi_' . ($data->no_sj ?? 'NoSJ');
    $paper = 'A4';
    $orientation = 'portrait';
    $html = $this->load->view('pages/v_print/v_print_tagihan_ekspedisi', $page_data, true);

    $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
  }

  /**
   * Print Rekap Tagihan Ekspedisi
   */
  public function print_rekap()
  {
    grantAccessFor('all');

    $this->load->library('pdfgenerator');

    // Get filters from GET parameters
    $filters = [
      'status' => $this->input->get('status'),
      'ekspedisi' => $this->input->get('ekspedisi'),
      'start_date' => $this->input->get('start_date'),
      'end_date' => $this->input->get('end_date')
    ];

    // Ambil data tagihan
    $list_tagihan = $this->md_tagihan->getAllTagihanForExport($filters);

    // Prepare filter labels
    $filter_ekspedisi = '';
    $filter_periode = '';

    if (!empty($filters['ekspedisi'])) {
      $this->load->model('md_tracking');
      $eks = $this->md_tracking->getEkspedisiById($filters['ekspedisi']);
      $filter_ekspedisi = $eks ? $eks->nama_ekspedisi : '';
    }

    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
      $filter_periode = date('d M Y', strtotime($filters['start_date'])) . ' - ' . date('d M Y', strtotime($filters['end_date']));
    } elseif (!empty($filters['start_date'])) {
      $filter_periode = 'Dari ' . date('d M Y', strtotime($filters['start_date']));
    } elseif (!empty($filters['end_date'])) {
      $filter_periode = 'Sampai ' . date('d M Y', strtotime($filters['end_date']));
    }

    $page_data['list_tagihan'] = $list_tagihan;
    $page_data['filter_ekspedisi'] = $filter_ekspedisi;
    $page_data['filter_periode'] = $filter_periode;

    // Generate PDF
    $file_pdf = 'Rekap_Tagihan_Ekspedisi_' . date('Y-m-d');
    $paper = 'A4';
    $orientation = 'landscape';
    $html = $this->load->view('pages/v_print/v_print_tagihan_ekspedisi_rekap', $page_data, true);

    $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
  }

  /**
   * Export Rekap Tagihan Ekspedisi ke Excel
   */
  public function export_excel()
  {
    grantAccessFor('all');

    // Get filters
    $filters = [
      'status' => $this->input->get('status'),
      'ekspedisi' => $this->input->get('ekspedisi'),
      'start_date' => $this->input->get('start_date'),
      'end_date' => $this->input->get('end_date')
    ];

    $list_tagihan = $this->md_tagihan->getAllTagihanForExport($filters);

    // Set headers for Excel
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Rekap_Tagihan_Ekspedisi_' . date('Y-m-d_His') . '.xls"');
    header('Cache-Control: max-age=0');

    // Output Excel
    echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">';
    echo '<head><meta charset="UTF-8"></head>';
    echo '<body>';
    echo '<table border="1">';
    echo '<tr><th colspan="8" style="font-size:16px; font-weight:bold; text-align:center;">REKAP TAGIHAN EKSPEDISI</th></tr>';
    echo '<tr><th colspan="8">Tanggal Export: ' . date('d F Y H:i') . '</th></tr>';
    echo '<tr><td colspan="8"></td></tr>';
    echo '<tr style="background-color:#C6DEFF; font-weight:bold;">';
    echo '<th>No</th>';
    echo '<th>No. Invoice</th>';
    echo '<th>No. SJ</th>';
    echo '<th>Ekspedisi</th>';
    echo '<th>Customer</th>';
    echo '<th>Tgl Invoice</th>';
    echo '<th>Nilai Tagihan</th>';
    echo '<th>Status</th>';
    echo '</tr>';

    $no = 1;
    $total = 0;
    foreach ($list_tagihan as $t) {
      $total += $t->nilai_tagihan ?? 0;
      $status = 'Proses';
      if ($t->status_approval == 5) $status = 'Approved';
      elseif ($t->status_approval == 99) $status = 'Ditolak';

      echo '<tr>';
      echo '<td>' . $no++ . '</td>';
      echo '<td>' . ($t->no_invoice ?? '-') . '</td>';
      echo '<td>' . ($t->no_sj ?? '-') . '</td>';
      echo '<td>' . ($t->nama_ekspedisi ?? '-') . '</td>';
      echo '<td>' . ($t->nama_customer ?? '-') . '</td>';
      echo '<td>' . (!empty($t->tanggal_invoice) ? date('d/m/Y', strtotime($t->tanggal_invoice)) : '-') . '</td>';
      echo '<td style="text-align:right;">' . number_format($t->nilai_tagihan ?? 0, 0, ',', '.') . '</td>';
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
}
