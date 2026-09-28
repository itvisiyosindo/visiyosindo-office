<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting_thr extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('md_setting_thr');
    $this->load->helper('encrypt_helper'); // Pastikan helper encrypt diload

    // Security Access (Sesuaikan dengan role di sistem Anda)
    // grantAccessFor(['Administrator', 'Hrd', 'Ga']); 
  }

  function id_navbar()
  {
    return "kepegawaian";
  }

  public function index()
  {
    $data['switch'] = $this->id_navbar();
    $data['page_name'] = 'salary/v_setting_thr'; // Mengarah ke View yang akan kita buat
    $data['page_title'] = 'Setting Kategori THR';
    $data['page_desc'] = 'Konfigurasi Penerima THR Natal & Idul Fitri';

    // Ambil data karyawan dari model
    $data['karyawan'] = $this->md_setting_thr->getAllKaryawan();

    $this->load->view('index', $data);
  }

  // API untuk menerima request Ajax
  public function update_action()
  {
    // Cek akses jika perlu
    // grantAccessFor(['Administrator', 'Hrd']);

    $encrypted_id = $this->input->post('id');
    $tipe = $this->input->post('tipe');

    // Dekripsi ID
    $pengguna_id = decrypt($encrypted_id);

    if ($pengguna_id && $tipe) {
      $update = $this->md_setting_thr->updateJenisTHR($pengguna_id, $tipe);

      if ($update) {
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan']);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan database']);
      }
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Data ID atau Tipe tidak valid']);
    }
  }

  public function preview_generate()
  {
    $candidates = $this->md_setting_thr->getUnregisteredEmployees();

    if ($candidates) {
      echo json_encode(['status' => 'success', 'data' => $candidates]);
    } else {
      echo json_encode(['status' => 'empty', 'message' => 'Semua karyawan yang memiliki NPP sudah terdaftar di sistem THR.']);
    }
  }

  // 2. Fungsi Eksekusi (Dipanggil saat tombol Konfirmasi di Modal diklik)
  public function execute_generate()
  {
    // Panggil fungsi insert massal yang sudah dibuat sebelumnya
    $inserted_rows = $this->md_setting_thr->generateDefaultData();

    if ($inserted_rows >= 0) {
      echo json_encode([
        'status' => 'success',
        'message' => 'Berhasil menyimpan ' . $inserted_rows . ' data karyawan.'
      ]);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan database']);
    }
  }
}
