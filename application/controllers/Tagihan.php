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
    $this->load->helper('jatuh_tempo_helper');
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
        } elseif ($filter_type == 'paid') {
          // Filter untuk tagihan yang sudah dibayar
          if ($row->status_bayar == 'sudah_dibayar') $filtered_data[] = $row;
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
    $id_user = sessPenggunaId();
    $all_data = $this->md_tagihan->getAllTagihan();
    $approval_configs = $this->md_tagihan_config->getAllConfig();

    // Cek apakah user adalah Admin atau user ID 1
    $is_admin = (isAdmin() || $id_user == 1);

    // Filter berdasarkan user yang login (Admin bisa lihat semua, user lain hanya miliknya)
    $my_data = [];
    foreach ($all_data as $row) {
      if ($is_admin || $row->created_by == $id_user) {
        $my_data[] = $row;
      }
    }

    // Terapkan filter tambahan jika ada
    $filter_type = $this->input->get('filter');
    $filtered_data = [];

    if (empty($filter_type)) {
      $filtered_data = $my_data;
    } else {
      foreach ($my_data as $row) {
        if ($filter_type == 'approved') {
          if ($row->status_approval == 5) $filtered_data[] = $row;
        } elseif ($filter_type == 'paid') {
          if ($row->status_bayar == 'sudah_dibayar') $filtered_data[] = $row;
        } elseif ($filter_type == 'waiting') {
          // Status sedang menunggu approval (tidak termasuk 0, 5, 99)
          if (!in_array($row->status_approval, [0, 5, 99])) $filtered_data[] = $row;
        } elseif ($filter_type == 'revision') {
          // Status revisi (status_approval = 0)
          if ($row->status_approval == 0) $filtered_data[] = $row;
        } elseif ($filter_type == 'rejected') {
          // Status ditolak (status_approval = 99)
          if ($row->status_approval == 99) $filtered_data[] = $row;
        }
      }
    }

    // Ambil data user untuk tampilan jabatan
    $user = $this->md_pengguna->getById($id_user);
    $my_jabatan = is_array($user) ? $user[0]->jabatan : $user->jabatan;

    // Config View
    $page_data['list_tagihan']         = $filtered_data;
    $page_data['approval_configs']     = $approval_configs;
    $page_data['current_user_jabatan'] = $my_jabatan;
    $page_data['filter_active']        = $filter_type;
    $page_data['is_admin']             = $is_admin;
    $page_data['switch']               = "helpdesk";
    $page_data['page_name']            = 'tagihan/v_my_tagihan';
    $page_data['page_title']           = 'Pengajuan Tagihan ' . ($is_admin ? '(Semua)' : 'Saya');
    $page_data['page_desc']            = 'Riwayat pengajuan tagihan ekspedisi';

    $this->load->view('index', $page_data);
  }

  public function detail($id_tagihan_encrypted)
  {
    $id_tagihan = decrypt($id_tagihan_encrypted);

    // 1. Ambil Data Detail Utama
    $data = $this->md_tagihan->getDetailFull($id_tagihan);
    if (!$data) show_404();

    // 2. [FIX] AMBIL DETAIL BARANG ARRAY berdasarkan tipe tracking
    $detail_barang_list = []; // Default kosong
    $tracking_type = isset($data->tracking_type) ? $data->tracking_type : 'pengeluaran_barang';

    if ($tracking_type == 'pengiriman_stok') {
      // Load model untuk pengiriman stok
      $this->load->model('md_detail_barang_pengiriman_stok');
      if (!empty($data->id_pengiriman_stok)) {
        $detail_barang_list = $this->md_detail_barang_pengiriman_stok->getByIdPengirimanStok($data->id_pengiriman_stok);
      }
    } elseif ($tracking_type == 'serah_terima_barang') {
      // Load detail untuk serah terima barang
      if (!empty($data->id_serah_terima_barang)) {
        $detail_barang_list = $this->md_tracking->getDetailSerahTerimaBarangById($data->id_serah_terima_barang);
      }
    } else {
      // Default: Pengeluaran Barang
      $this->load->model('md_detail_barang_keluar');
      if (!empty($data->id_pengeluaran_barang)) {
        $detail_barang_list = $this->md_detail_barang_keluar->getByIdPengeluaranBarang($data->id_pengeluaran_barang);
      }
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
    $page_data['tracking_type'] = $tracking_type;

    // [PENTING] Kirim sebagai 'tracking' juga karena View Anda memakai $tracking->nama_barang
    $page_data['tracking'] = $data;
    $page_data['detail']   = $data; // Tetap kirim 'detail' untuk kompatibilitas lain

    $page_data['tracking_history_log'] = $this->md_tagihan->getHistoryStatus($data->id_tracking);
    $page_data['approval_configs']     = $this->md_tagihan_config->getAllConfig();

    // Cek User Login
    $id_user = sessPenggunaId();
    $user    = $this->md_pengguna->getById($id_user);
    $page_data['current_user_jabatan'] = is_array($user) ? $user[0]->jabatan : $user->jabatan;

    // Cek Admin (Override - hardcoded ID seperti tracking)
    $page_data['is_admin'] = in_array(sessPenggunaId(), [1, 106]);

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

  public function ajukan($id_tracking_encrypted = null)
  {
    // Jika tidak ada parameter, ambil dari flashdata (setelah revisi/tolak redirect)
    if (!$id_tracking_encrypted) {
      $this->session->set_flashdata('error_message', 'ID Tracking tidak valid.');
      redirect('tracking');
      return;
    }

    $id_tracking = decrypt($id_tracking_encrypted);
    $reset = $this->input->get('reset'); // Untuk ajukan ulang setelah ditolak

    // Cek Existing
    $exists = $this->md_tagihan->checkExisting($id_tracking);

    // ================================================================
    // VALIDASI: PREVENT DOUBLE SUBMISSION
    // Data yang sudah di-APPROVE (status_approval = 5) TIDAK BOLEH diajukan lagi
    // ================================================================
    if ($exists && $exists->status_approval == 5) {
      $this->session->set_flashdata('error_message', 'DATA SUDAH DI-APPROVE! Data tracking ini sudah final dan tidak bisa diajukan kembali. Jika perlu perubahan, hubungi Administrator.');
      redirect('tagihan/my_submission');
      return;
    }

    // Data yang sedang pending approval (status 1-4) tidak boleh diajukan ulang
    if ($exists && !in_array($exists->status_approval, [0, 99])) {
      $this->session->set_flashdata('error_message', 'DATA SEDANG DIPROSES! Pengajuan ini masih dalam proses approval. Status: ' . $exists->status_approval . '. Tunggu hingga selesai atau ditolak.');
      redirect('tagihan/my_submission');
    }

    // Jika reset=1 dan status 99, reset status menjadi 0
    // if ($reset == '1' && $exists && $exists->status_approval == 99) {
    //   $this->md_tagihan->update($exists->id_tagihan, [
    //     'status_approval' => 0,
    //     'catatan_revisi' => null
    //   ]);
    //   // Refresh data setelah reset
    //   $exists = $this->md_tagihan->checkExisting($id_tracking);
    // }

    $tracking = $this->md_tagihan->getTrackingById($id_tracking);
    if (!$tracking) show_404();

    if (trim($tracking->nama_ekspedisi) == 'Diantarkan Langsung') {
      $this->session->set_flashdata('error_message', 'Pengiriman Internal tidak butuh tagihan.');
      redirect('tracking');
      return;
    }

    // Tentukan tipe tracking
    $tracking_type = isset($tracking->tracking_type) ? $tracking->tracking_type : 'pengeluaran_barang';
    $detail_barang_list = []; // Default array kosong

    // Logic pengambilan detail barang berdasarkan tipe tracking
    if ($tracking_type == 'pengiriman_stok') {
      // Load model untuk pengiriman stok
      $this->load->model('md_detail_barang_pengiriman_stok');
      if (!empty($tracking->id_pengiriman_stok)) {
        $detail_barang_list = $this->md_detail_barang_pengiriman_stok->getByIdPengirimanStok($tracking->id_pengiriman_stok);

        // Update nama_barang untuk fallback
        if (!empty($detail_barang_list)) {
          $nama_barang_temp = [];
          foreach ($detail_barang_list as $item) $nama_barang_temp[] = $item->nama_barang;
          $tracking->nama_barang = implode(', ', $nama_barang_temp);
        } else {
          $tracking->nama_barang = '-';
        }
      }
    } elseif ($tracking_type == 'serah_terima_barang') {
      // Load detail untuk serah terima barang
      if (!empty($tracking->id_serah_terima_barang)) {
        $detail_barang_list = $this->md_tracking->getDetailSerahTerimaBarangById($tracking->id_serah_terima_barang);

        // Update nama_barang untuk fallback
        if (!empty($detail_barang_list)) {
          $nama_barang_temp = [];
          foreach ($detail_barang_list as $item) $nama_barang_temp[] = $item->nama_barang;
          $tracking->nama_barang = implode(', ', $nama_barang_temp);
        } else {
          $tracking->nama_barang = '-';
        }
      }
    } else {
      // Default: Pengeluaran Barang
      $this->load->model('md_detail_barang_keluar');
      if (!empty($tracking->id_pengeluaran_barang)) {
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
    }

    // [WAJIB TAMBAH] Kirim variabel ini ke View
    $page_data['detail_barang_keluar'] = $detail_barang_list;
    $page_data['tracking_type'] = $tracking_type;

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
    // 1. Validasi Input
    $id_tracking = $this->input->post('id_tracking');
    if (!$id_tracking) {
      echo json_encode(['status' => 'error', 'message' => 'ID Tracking tidak valid.']);
      return;
    }

    // 2. Sanitasi Input Nominal
    $nilai_input = str_replace(['.', ','], '', $this->input->post('nilai_tagihan'));
    $asuransi_input = str_replace(['.', ','], '', $this->input->post('biaya_asuransi'));

    $nilai_tagihan   = floatval($nilai_input);
    $biaya_asuransi  = !empty($asuransi_input) ? floatval($asuransi_input) : 0;
    $total_tagihan   = $nilai_tagihan + $biaya_asuransi;

    // 3. Update data pendukung ke table tracking_barang
    $link_resi = $this->input->post('link_resi');
    $update_tracking = ['biaya' => $nilai_tagihan];
    if (!empty($link_resi)) {
      $update_tracking['link_resi'] = $link_resi;
    }
    $this->db->update('tracking_barang', $update_tracking, ['id_tracking' => $id_tracking]);

    // 4. Ambil Level Approval Pertama
    $firstConfig = $this->md_tagihan_config->getFirstActiveLevel();
    if (!$firstConfig) {
      echo json_encode(['status' => 'error', 'message' => 'Konfigurasi Approval belum diatur.']);
      return;
    }

    // --- [FIX: DEFINISIKAN VARIABEL YANG HILANG] ---
    $initialStatus = $firstConfig->status_code;
    $targetRole    = $firstConfig->role_label;
    $targetPhone   = $firstConfig->notif_number;
    // -----------------------------------------------

    // 5. Persiapan Data Tagihan
    $is_revisi  = $this->input->post('is_revisi');
    $id_tagihan = $this->input->post('id_tagihan');

    // --- [FIX: DEFINISIKAN AKSI LABEL] ---
    $aksi_label = ($is_revisi == 'true') ? "Mengirim Revisi" : "Mengajukan Baru";
    // -------------------------------------

    $data = [
      'id_tracking'       => $id_tracking,
      'id_ekspedisi'      => $this->input->post('id_ekspedisi'),
      'no_invoice'        => $this->input->post('no_invoice'),
      'link_invoice'      => $this->input->post('link_invoice'),
      'link_faktur_pajak' => $this->input->post('link_faktur_pajak'),
      'link_dokumen_lain' => $this->input->post('link_dokumen_lain'),
      'nilai_tagihan'     => $nilai_tagihan,
      'biaya_asuransi'    => $biaya_asuransi,
      'total_tagihan'     => $total_tagihan,
      'tanggal_invoice'   => $this->input->post('tanggal_invoice'),
      'status_approval'   => $initialStatus,
      'created_by'        => sessPenggunaId(),
      'catatan_revisi'    => null,
      'updated_at'        => date('Y-m-d H:i:s')
    ];

    $this->db->trans_begin();

    if ($is_revisi == 'true' && !empty($id_tagihan)) {
      $this->md_tagihan->update($id_tagihan, $data);
      $target_id = $id_tagihan;
    } else {
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
      echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan Database.']);
    } else {
      $this->db->trans_commit();
      $msg = ($wa_status) ? 'Pengajuan Berhasil dan Notifikasi Terkirim ke Approver!' : 'Pengajuan Berhasil, namun notifikasi WhatsApp gagal terkirim.';
      echo json_encode(['status' => 'success', 'message' => $msg]);
    }
  }

  // =========================================================================
  // EDIT DATA TAGIHAN (ADMIN ONLY) - HALAMAN EDIT LENGKAP
  // =========================================================================
  /**
   * Halaman Edit Data Tagihan - Khusus Administrator
   * Untuk mengedit semua data tagihan yang sudah berjalan
   */
  public function edit($id_tagihan_encrypted)
  {
    // 1. Cek Akses Admin (pengguna_id = 1 atau isAdmin)
    if (!isAdmin() && !in_array(sessPenggunaId(), [1, 106])) {
        $this->session->set_flashdata('error_message', 'Akses ditolak.');
        redirect('tagihan/my_submission');
        return;
    }

    // 2. Decrypt ID dan ambil data
    $id_tagihan = decrypt($id_tagihan_encrypted);

    // Cek apakah ini kirim dokumen atau tracking barang
    $tagihan_check = $this->db->select('id_kirim, id_tracking')->from('tagihan_ekspedisi')->where('id_tagihan', $id_tagihan)->get()->row();

    if (!$tagihan_check) {
      $this->session->set_flashdata('error_message', 'Data tagihan tidak ditemukan.');
      redirect('tagihan');
      return;
    }

    // Pilih method sesuai tipe
    if (!empty($tagihan_check->id_kirim)) {
      // Ini kirim dokumen
      $tagihan = $this->md_tagihan->getDetailFullKirim($id_tagihan);
      $tagihan_type = 'kirim_dokumen';
    } else {
      // Ini tracking barang
      $tagihan = $this->md_tagihan->getDetailFull($id_tagihan);
      $tagihan_type = 'tracking_barang';
    }

    if (!$tagihan) {
      $this->session->set_flashdata('error_message', 'Data tagihan tidak ditemukan.');
      redirect('tagihan');
      return;
    }

    // 3. Kirim Data ke View
    $page_data['tagihan'] = $tagihan;
    $page_data['tagihan_type'] = $tagihan_type;
    // $page_data['approval_configs'] = $this->md_tagihan_config->getAllConfig();
    
    // Mengambil semua untuk filter, tapi pastikan View tahu mana yang aktif
    $page_data['approval_configs'] = $this->md_tagihan_config->getAllConfig(); 
    // Tambahkan ini untuk mempermudah View memfilter status aktif
    $page_data['active_configs'] = $this->md_tagihan_config->getAllActiveConfig();
    
    $page_data['is_kirim_dokumen'] = ($tagihan_type == 'kirim_dokumen');

    // Template Config
    $page_data['page_title'] = 'Edit Data Tagihan';
    $page_data['page_desc']  = 'No. Invoice: ' . ($tagihan->no_invoice ?? '-');
    $page_data['page_name']  = 'tagihan/v_edit_tagihan';
    $page_data['switch']     = 'helpdesk';

    $this->load->view('index', $page_data);
  }

  /**
   * Update Data Tagihan Lengkap - Khusus Administrator
   * Untuk mengupdate semua field tagihan yang sudah berjalan
   */
  public function update_tagihan()
  {
    // 1. Cek Akses Admin (pengguna_id = 1 atau isAdmin)
    if (!isAdmin() && !in_array(sessPenggunaId(), [1, 106])) {
        ajaxReturnDie('error', 'Akses ditolak. Anda tidak memiliki wewenang mengubah data.');
    }

    // 2. Validasi Input
    $id_tagihan = $this->input->post('id_tagihan');
    if (!$id_tagihan) {
      ajaxReturnDie('error', 'ID Tagihan tidak valid.');
    }

    // 3. Ambil dan bersihkan input
    $no_invoice       = trim($this->input->post('no_invoice'));
    $tanggal_invoice  = $this->input->post('tanggal_invoice');
    $link_invoice     = trim($this->input->post('link_invoice'));
    $link_bukti_potong = trim($this->input->post('link_bukti_potong'));
    $link_dokumen_lain = trim($this->input->post('link_dokumen_lain'));
    $status_approval = $this->input->post('status_approval');

    $nilai_tagihan  = $this->input->post('nilai_tagihan');
    $biaya_asuransi = $this->input->post('biaya_asuransi');

    // Bersihkan format number
    $nilai_tagihan  = !empty($nilai_tagihan) ? str_replace('.', '', $nilai_tagihan) : 0;
    $biaya_asuransi = !empty($biaya_asuransi) ? str_replace('.', '', $biaya_asuransi) : 0;

    // Hitung total tagihan
    $total_tagihan = floatval($nilai_tagihan) + floatval($biaya_asuransi);

    // 4. Validasi wajib
    if (empty($no_invoice)) {
      ajaxReturnDie('error', 'No. Invoice wajib diisi.');
    }
    if (empty($link_invoice)) {
      ajaxReturnDie('error', 'Link Invoice wajib diisi.');
    }
    if ($nilai_tagihan <= 0) {
      ajaxReturnDie('error', 'Nilai Tagihan harus lebih dari 0.');
    }

        // 5. Prepare Data Update
    $data = [
      'no_invoice'        => $no_invoice,
      'tanggal_invoice'   => $tanggal_invoice,
      'link_invoice'      => $link_invoice,
      'link_bukti_potong' => $link_bukti_potong,
      'link_dokumen_lain' => $link_dokumen_lain,
      'nilai_tagihan'     => $nilai_tagihan,
      'biaya_asuransi'    => $biaya_asuransi,
      'total_tagihan'     => $total_tagihan,
      'updated_at'        => date('Y-m-d H:i:s')
    ];

    // Tambahkan update status_approval jika diubah oleh admin
    if ($status_approval !== null && $status_approval !== '') {
        $data['status_approval'] = (int)$status_approval;
    }

    // 6. Update Database
    $this->db->trans_begin();
    $this->md_tagihan->update($id_tagihan, $data);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      ajaxReturnDie('error', 'Gagal mengupdate data. Silakan coba lagi.');
    } else {
      $this->db->trans_commit();
      ajaxReturnDie('success', 'Data tagihan berhasil diperbarui.');
    }
  }

  // =========================================================================
  // UPDATE BIAYA ASURANSI (ADMIN ONLY) - MODAL QUICK EDIT
  // =========================================================================
  /**
   * Update Biaya Asuransi - Khusus Administrator
   * Untuk mengupdate biaya asuransi pada data tagihan yang sudah berjalan
   */
  public function update_asuransi()
  {
    // 1. Cek Akses Admin
    if ($this->session->userdata('role') != 'Administrator' && !in_array(sessPenggunaId(), [1, 106])) {
        ajaxReturnDie('error', 'Akses ditolak.');
    }

    // 2. Validasi Input
    $id_tagihan     = $this->input->post('id_tagihan');
    $biaya_asuransi = $this->input->post('biaya_asuransi');
    $nilai_tagihan  = $this->input->post('nilai_tagihan');

    if (!$id_tagihan) {
      ajaxReturnDie('error', 'ID Tagihan tidak valid.');
    }

    // 3. Prepare Data Update
    $biaya_asuransi = !empty($biaya_asuransi) ? str_replace('.', '', $biaya_asuransi) : 0;
    $nilai_tagihan  = !empty($nilai_tagihan) ? str_replace('.', '', $nilai_tagihan) : 0;

    // Hitung total tagihan
    $total_tagihan = floatval($nilai_tagihan) + floatval($biaya_asuransi);

    $data = [
      'nilai_tagihan'  => $nilai_tagihan,
      'biaya_asuransi' => $biaya_asuransi,
      'total_tagihan'  => $total_tagihan,
      'updated_at'     => date('Y-m-d H:i:s')
    ];

    // 4. Update Database
    $this->db->trans_begin();
    $this->md_tagihan->update($id_tagihan, $data);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      ajaxReturnDie('error', 'Gagal mengupdate data.');
    } else {
      $this->db->trans_commit();
      ajaxReturnDie('success', 'Biaya asuransi berhasil diupdate. Total Tagihan: Rp ' . number_format($total_tagihan, 0, ',', '.'));
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

    // Cek apakah ini kirim dokumen atau tracking barang
    $tagihan_check = $this->db->select('id_kirim, id_tracking')->from('tagihan_ekspedisi')->where('id_tagihan', $id_tagihan)->get()->row();

    if (!$tagihan_check) {
      ajaxReturnDie('error', 'Data tagihan tidak ditemukan');
    }

    // Pilih method detail sesuai tipe
    if (!empty($tagihan_check->id_kirim)) {
      // Kirim dokumen
      $detail = $this->md_tagihan->getDetailFullKirim($id_tagihan);
      $is_kirim_dokumen = true;
    } else {
      // Tracking barang
      $detail = $this->md_tagihan->getDetailFull($id_tagihan);
      $is_kirim_dokumen = false;
    }

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
    // Untuk kirim dokumen, gunakan kode sebagai identifier
    $identifier = $is_kirim_dokumen ? ($detail->kode ?? '-') : ($detail->no_sj ?? '-');

    $waData = [
      'target_phone'    => $target_phone,
      'target_role'     => $target_role,
      'nama_pengaju'    => $this->session->userdata('nama'),
      'action_status'   => $action_msg,
      'no_sj'           => $identifier,
      'ekspedisi'       => $detail->nama_ekspedisi,
      'biaya_formatted' => "Rp " . number_format(($detail->biaya_real ?? 0), 0, ',', '.'),
      'history_info'    => $history_list,
      'link_invoice'    => $detail->link_invoice ?? '',
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

    // Ambil Detail Barang berdasarkan tracking_type
    $detail_barang_list = [];
    $tracking_type = $data->tracking_type ?? 'pengeluaran_barang';

    // Determine type label and document code
    $type_label = 'Surat Jalan Barang Keluar (SJBK)';
    $doc_code = 'SJBK';

    if ($tracking_type == 'pengeluaran_barang') {
      // SJBK - Surat Jalan Barang Keluar
      $this->load->model('md_detail_barang_keluar');
      if (!empty($data->id_pengeluaran_barang)) {
        $detail_barang_list = $this->md_detail_barang_keluar->getByIdPengeluaranBarang($data->id_pengeluaran_barang);
      }
      $type_label = 'Surat Jalan Barang Keluar (SJBK)';
      $doc_code = 'SJBK';
    } elseif ($tracking_type == 'pengiriman_stok') {
      // TTBK - Tanda Terima Barang Keluar (Pengiriman Stok antar Gudang)
      $this->load->model('md_detail_barang_pengiriman_stok');
      if (!empty($data->id_pengiriman_stok)) {
        $detail_barang_list = $this->md_detail_barang_pengiriman_stok->getByIdPengirimanStok($data->id_pengiriman_stok);
      }
      $type_label = 'Tanda Terima Barang Keluar (TTBK)';
      $doc_code = 'TTBK';
    } elseif ($tracking_type == 'serah_terima_barang') {
      // STTB - Surat Tanda Terima Barang
      $this->load->model('md_serah_terima_barang');
      if (!empty($data->id_serah_terima_barang)) {
        $detail_barang_list = $this->md_serah_terima_barang->getDetailSTBById($data->id_serah_terima_barang);
      }
      $type_label = 'Surat Tanda Terima Barang (STTB)';
      $doc_code = 'STTB';
    }

    // Send to View
    $page_data['detail'] = $data;
    $page_data['detail_barang_keluar'] = $detail_barang_list;
    $page_data['tracking_type'] = $tracking_type;
    $page_data['type_label'] = $type_label;
    $page_data['doc_code'] = $doc_code;

    // Generate PDF
    $file_pdf = 'Tagihan_Ekspedisi_' . ($data->no_sj ?? 'NoSJ');
    $paper = 'A4';
    $orientation = 'portrait';
    $html = $this->load->view('pages/v_print/v_print_tagihan_ekspedisi', $page_data, true);

    $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
  }

  /**
   * Print Tagihan Kirim Dokumen (khusus untuk kirim_dokumen)
   */
  public function print_tagihan_kirim($id_tagihan_encrypted)
  {
    grantAccessFor('all');

    $this->load->library('pdfgenerator');

    $id_tagihan = decrypt($id_tagihan_encrypted);

    // Ambil Data Detail Kirim Dokumen
    $data = $this->md_tagihan->getDetailFullKirim($id_tagihan);
    if (!$data) show_404();

    // Send to View
    $page_data['detail'] = $data;

    // Generate PDF
    $file_pdf = 'Tagihan_Kirim_Dokumen_' . ($data->kode ?? 'NoKode');
    $paper = 'A4';
    $orientation = 'portrait';
    $html = $this->load->view('pages/v_print/v_print_tagihan_kirim', $page_data, true);

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

  // =========================================================================
  // KIRIM DOKUMEN - PENGAJUAN TAGIHAN
  // =========================================================================

  /**
   * Form Pengajuan Tagihan untuk Kirim Dokumen
   */
  public function ajukan_kirim($id_kirim_encrypted = null)
  {
    // Jika tidak ada parameter, ambil dari flashdata (setelah revisi/tolak redirect)
    if (!$id_kirim_encrypted) {
      $this->session->set_flashdata('error_message', 'ID Kirim Dokumen tidak valid.');
      redirect('kirim/show/list');
      return;
    }

    $id_kirim = decrypt($id_kirim_encrypted);
    $reset = $this->input->get('reset'); // Untuk ajukan ulang setelah ditolak

    // 1. Cek Data di tabel tagihan_ekspedisi (apakah sudah pernah diajukan)
    $exists = $this->md_tagihan->checkExistingKirim($id_kirim);

    // ================================================================
    // VALIDASI: PREVENT DOUBLE SUBMISSION
    // ================================================================

    // Data yang sudah di-APPROVE (status_approval = 5) TIDAK BOLEH diajukan lagi
    if ($exists && $exists->status_approval == 5) {
      $this->session->set_flashdata('error_message', 'DATA SUDAH DI-APPROVE! Data kirim dokumen ini sudah final dan tidak bisa diajukan kembali.');
      redirect('tagihan/my_submission');
      return;
    }

    // Data yang sedang pending approval (status 1-4) tidak boleh diajukan ulang
    if ($exists && !in_array($exists->status_approval, [0, 99])) {
      $this->session->set_flashdata('error_message', 'DATA SEDANG DIPROSES! Pengajuan ini masih dalam proses approval. Tunggu hingga selesai atau ditolak.');
      redirect('tagihan/my_submission');
      return;
    }

    // 2. Ambil data utama dari tabel kirim_dokumen
    $kirim = $this->md_tagihan->getKirimById($id_kirim);
    if (!$kirim) show_404();

    // ================================================================
    // FIX: LOGIKA REDIRECT AJUKAN ULANG
    // ================================================================

    // Ambil status approval jika data sudah ada
    $status_approval = ($exists) ? $exists->status_approval : null;

    // Jika statusnya BUKAN revisi (0) dan BUKAN ditolak (99), 
    // maka lakukan validasi ekspedisi internal.
    // Jika statusnya 0 atau 99, validasi ini dilewati agar user bisa memperbaiki data di form.
    if (!in_array($status_approval, [0, 99])) {
      if (empty($kirim->ekspedisi) || strtolower(trim($kirim->ekspedisi)) == 'diantarkan langsung') {
        $this->session->set_flashdata('error_message', 'Pengiriman ini tidak menggunakan ekspedisi atau diantarkan langsung.');
        redirect('kirim/show/list');
        return;
      }
    }

    // Ambil history tracking untuk ditampilkan di timeline form
    $tracking_history = $this->md_tagihan->getKirimHistoryStatus($id_kirim);

    // Persiapan data untuk dilempar ke View
    $page_data['kirim']            = $kirim;
    $page_data['tracking_history'] = $tracking_history;
    $page_data['is_revisi']        = ($exists) ? true : false;
    $page_data['data_lama']        = $exists;

    $page_data['switch']      = "helpdesk";
    $page_data['page_name']   = 'kirim/v_form_tagihan'; // Pastikan view ini tersedia
    $page_data['page_title']  = 'Form Pengajuan Tagihan Ekspedisi';
    $page_data['page_desc']   = 'Kirim Dokumen: ' . $kirim->kode;

    $this->load->view('index', $page_data);
  }

  /**
   * Submit Pengajuan Tagihan Kirim Dokumen
   */
  public function submit_kirim()
  {
    // 1. Validasi Input
    $id_kirim = $this->input->post('id_kirim');
    if (!$id_kirim) {
      $this->session->set_flashdata('error_message', 'ID Kirim Dokumen tidak valid.');
      redirect('kirim/show/list');
      return;
    }

    // 2. Ambil data dari form
    $no_invoice      = $this->input->post('no_invoice');
    $tanggal_invoice = $this->input->post('tanggal_invoice');
    $nilai_tagihan   = str_replace('.', '', $this->input->post('nilai_tagihan'));
    $biaya_asuransi  = str_replace('.', '', $this->input->post('biaya_asuransi'));
    $biaya_asuransi  = !empty($biaya_asuransi) ? $biaya_asuransi : 0; // Default 0 jika kosong

    // Hitung total_tagihan secara manual (nilai_tagihan + biaya_asuransi)
    $total_tagihan   = floatval($nilai_tagihan) + floatval($biaya_asuransi);

    // 3. Update biaya di kirim_dokumen
    $this->md_tagihan->updateKirimBiaya($id_kirim, $nilai_tagihan);

    // 4. Ambil Level Approval Aktif Pertama
    $firstConfig = $this->md_tagihan_config->getFirstActiveLevel();
    if (!$firstConfig) {
      $this->session->set_flashdata('error_message', 'Konfigurasi Approval belum diatur. Hubungi Administrator.');
      redirect('kirim/show/list');
      return;
    }

    $initialStatus = $firstConfig->status_code;
    $targetRole    = $firstConfig->role_label;
    $targetPhone   = $firstConfig->notif_number;

    // 5. Persiapan Data
    $is_revisi  = $this->input->post('is_revisi');
    $id_tagihan = $this->input->post('id_tagihan');
    $aksi_label = ($is_revisi == 'true') ? "Mengirim Revisi" : "Mengajukan Baru";

    // Ambil data kirim untuk id_ekspedisi
    $kirimData = $this->md_tagihan->getKirimById($id_kirim);

    // Coba ambil id_ekspedisi dari data, atau cari berdasarkan nama
    $id_ekspedisi = null;
    if (!empty($kirimData->id_ekspedisi)) {
      $id_ekspedisi = $kirimData->id_ekspedisi;
    } elseif (!empty($kirimData->ekspedisi_id_found)) {
      $id_ekspedisi = $kirimData->ekspedisi_id_found;
    } elseif (!empty($kirimData->ekspedisi)) {
      // Cari id_ekspedisi berdasarkan nama menggunakan query manual
      $nama_ekspedisi = strtolower(trim($kirimData->ekspedisi));
      $ekspedisiByName = $this->db->query(
        "SELECT id_ekspedisi FROM ekspedisi WHERE LOWER(TRIM(nama_ekspedisi)) = ?",
        [$nama_ekspedisi]
      )->row();
      if ($ekspedisiByName) {
        $id_ekspedisi = $ekspedisiByName->id_ekspedisi;
      }
    }

    $data = [
      'id_kirim'          => $id_kirim,
      'id_tracking'       => null, // NULL karena ini bukan tracking barang
      'tagihan_type'      => 'kirim_dokumen',
      'id_ekspedisi'      => $id_ekspedisi,
      'no_invoice'        => $no_invoice,
      'tanggal_invoice'   => $tanggal_invoice,
      'nilai_tagihan'     => $nilai_tagihan,
      'biaya_asuransi'    => $biaya_asuransi,     // NEW: Biaya Asuransi
      'total_tagihan'     => $total_tagihan,      // NEW: Total = nilai + asuransi (dihitung manual)
      'link_invoice'      => $this->input->post('link_invoice'),
      'link_faktur_pajak' => $this->input->post('link_faktur_pajak'),
      'link_dokumen_lain' => $this->input->post('link_dokumen_lain'),
      'status_approval'   => $initialStatus,
      'created_by'        => sessPenggunaId(),
      'catatan_revisi'    => null
    ];

    // 6. Transaksi Database
    $this->db->trans_begin();

    if ($is_revisi == 'true' && $id_tagihan) {
      $this->md_tagihan->update($id_tagihan, $data);
      $target_id = $id_tagihan;
    } else {
      $target_id = $this->md_tagihan->insert($data);
    }

    // 7. Persiapan WA & Log
    $nama_pengaju = $this->session->userdata('nama') ?: 'User';
    $history_list = $this->_generate_approval_list($firstConfig->level_order, false);

    $waData = [
      'target_phone'    => $targetPhone,
      'target_role'     => $targetRole,
      'nama_pengaju'    => $nama_pengaju,
      'action_status'   => $aksi_label . ' (Kirim Dokumen)',
      'no_sj'           => $kirimData->kode ?? '-',
      'ekspedisi'       => $kirimData->nama_ekspedisi ?? $kirimData->ekspedisi ?? '-',
      'history_info'    => $history_list,
      'link_invoice'    => $data['link_invoice'],
      'link_url'        => base_url('tagihan'),
      'catatan'         => ''
    ];

    // 8. Kirim WA & Log
    $wa_status = waTagihanApproval($waData);
    $this->md_tagihan_config->logNotification(
      $target_id,
      $targetRole,
      $targetPhone,
      "Notif Pengajuan Kirim Dokumen (" . $aksi_label . ") ke " . $targetRole
    );

    // 9. Finalisasi
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $this->session->set_flashdata('error_message', 'Terjadi kesalahan Database. Data gagal disimpan. Silahkan coba lagi.');
      redirect('kirim/show/list');
    } else {
      $this->db->trans_commit();
      $msg = ($wa_status) ? 'Pengajuan Berhasil dan Notifikasi Terkirim ke Approver!' : 'Pengajuan Berhasil, namun notifikasi WhatsApp gagal terkirim. Admin akan memproses segera.';
      $this->session->set_flashdata('success_message', $msg);
      // Redirect ke halaman my_submission agar user melihat status pengajuannya
      $redirect_url = ($is_revisi == 'true') ? 'tagihan/my_submission' : 'kirim/show/list';
      redirect($redirect_url);
    }
  }

  /**
   * Detail Tagihan Kirim Dokumen
   */
  public function detail_kirim($id_tagihan_encrypted)
  {
    $id_tagihan = decrypt($id_tagihan_encrypted);

    $data = $this->md_tagihan->getDetailFullKirim($id_tagihan);
    if (!$data) show_404();

    // Ambil history tracking
    $tracking_history = $this->md_tagihan->getKirimHistoryStatus($data->id_kirim);

    $page_data['tagihan']          = $data;
    $page_data['detail']           = $data; // Alias untuk kompatibilitas
    $page_data['tracking_history'] = $tracking_history;
    $page_data['approval_configs'] = $this->md_tagihan_config->getAllConfig();

    // Cek User Login
    $user = $this->md_pengguna->getById(sessPenggunaId());
    $page_data['current_user_jabatan'] = is_array($user) ? $user[0]->jabatan : $user->jabatan;

    // Cek Admin (hardcoded ID seperti tracking)
    $page_data['is_admin'] = in_array(sessPenggunaId(), [1, 106]);

    $page_data['switch']     = 'helpdesk';
    $page_data['page_title'] = 'Detail Tagihan Kirim Dokumen';
    $page_data['page_desc']  = 'Kode: ' . ($data->kode ?? '-');
    $page_data['page_name']  = 'kirim/v_detail_tagihan';

    $this->load->view('index', $page_data);
  }

  /**
   * Upload Bukti Pembayaran per Tagihan (Hanya untuk User ID 1 atau 106)
   */
  public function upload_bukti_bayar_tagihan()
  {
    grantAccessFor('all');

    // Validasi: Hanya user tertentu yang bisa upload
    $id_user_login = sessPenggunaId();
    if (!in_array($id_user_login, [1, 106])) {
      echo json_encode(['success' => false, 'message' => 'Akses ditolak! Anda tidak memiliki akses untuk upload bukti pembayaran.']);
      return;
    }

    $id_tagihan = decrypt($this->input->post('id_tagihan'));
    $link_bukti_bayar = $this->input->post('link_bukti_bayar');
    $keterangan_bayar = $this->input->post('keterangan_bayar');
    $tgl_bayar = $this->input->post('tgl_bayar');

    // Validasi input
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
}
