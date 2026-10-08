<?php
//abaikan error
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
//
use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Surat_part_two extends CI_Controller
{

  function id_navbar()
  {
    $id_navbar = "helpdesk";
    return $id_navbar;
  }

  public function index()
  {
    grantAccessFor('all');

    $page_data['switch']        = $this->id_navbar();
    $page_data['page_name']     = 'surat/v_surat_list';
    $page_data['page_title']    = 'Data Surat';
    $page_data['page_desc']     = 'Management Surat - Menyurat';
    $this->load->view('index', $page_data);
  }


  function __construct()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
    $this->load->model('md_absensi');
    $this->load->model('md_pengguna');
    $this->load->model('md_salary_tidak_tetap');
    $this->load->model('md_absensi_config');
    $this->load->model('md_surat_list');
    $this->load->model('md_surat_part_two');
    $this->load->model('md_surat_list');
    $this->load->model('md_prov_kota');
    $this->load->helper('email_helper');
    $this->load->helper('terbilang_helper');
    $this->load->helper('tanggal_helper');
    $this->load->helper('whatsapp_helper');
    $this->load->helper('encrypt_helper');
  }

  public function show($param = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');
    if ($param == 'detail') {
      if ($param2 == 'izin_jam_kerja') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_sijk']      = $this->md_surat_part_two->getSijkById($param3);
        $sijk                        = $this->md_surat_part_two->getSijkById($param3);
        if ($sijk[0]->id_Sijk > 62) {
          $page_data['page_name']     = 'surat/v_detail_izin_jam_kerja';
        } else {
          $page_data['page_name']     = 'surat/v_detail_izin_jam_kerja_1';
        }
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Detail Surat Izin pada Jam Kerja';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'izin_meninggalkan') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_sijk']      = $this->md_surat_part_two->getSijkById($param3);
        $simp                        = $this->md_surat_part_two->getSijkById($param3);
        if ($simp[0]->id_Sijk > 61) {
          $page_data['page_name']     = 'surat/v_detail_izin_meninggalkan';
        } else {
          $page_data['page_name']     = 'surat/v_detail_izin_meninggalkan_1';
        }
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Detail Surat Izin Meninggalkan Pekerjaan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'cuti') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_sijk']      = $this->md_surat_part_two->getCutiById($param3);
        $cuti                       = $this->md_surat_part_two->getCutiById($param3);
        if ($cuti[0]->id_Sijk > 1) {
          $page_data['page_name']     = 'surat/v_detail_izin_cuti';
        } else {
          $page_data['page_name']     = 'surat/v_detail_izin_cuti_1';
        }
        $page_data['page_title']    = 'Surat Cuti';
        $page_data['page_desc']     = 'Detail Surat Cuti Tahunan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'ba' || $param2 == 'berita_acara') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_ba']      = $this->md_surat_part_two->getBeritaAcaraById($param3);
        $page_data['page_name']     = 'surat/v_detail_berita_acara';
        $page_data['page_title']    = 'Berita Acara';
        $page_data['page_desc']     = 'Detail Berita Acara';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'paklaring') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_pk']      = $this->md_surat_part_two->getPaklaringById($param3);
        $page_data['page_name']     = 'surat/v_detail_paklaring';
        $page_data['page_title']    = 'Paklaring';
        $page_data['page_desc']     = 'Detail Pengalaman Kerja';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'meetingroom') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_meeting']  = $this->md_surat_part_two->getMeetingById($param3);
        $page_data['page_name']     = 'surat/v_detail_meetingroom';
        $page_data['page_title']    = 'Meeting Room';
        $page_data['page_desc']     = 'Detail Pengajuan Ruang Meeting';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'izin_jam_kerja_sgm') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_sijk']      = $this->md_surat_part_two->getAllById($param3);
        $page_data['page_name']     = 'surat/v_detail_izin_jam_kerja_sgm';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Detail Surat Izin pada Jam Kerja';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'izin_meninggalkan_sgm') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_sijk']      = $this->md_surat_part_two->getAllById($param3);
        $page_data['page_name']     = 'surat/v_detail_izin_meninggalkan_sgm';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Detail Surat Izin Meninggalkan Pekerjaan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'cuti_sgm') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_sijk']      = $this->md_surat_part_two->getAllById($param3);
        $page_data['page_name']     = 'surat/v_detail_izin_cuti_sgm';
        $page_data['page_title']    = 'Surat Cuti';
        $page_data['page_desc']     = 'Detail Surat Cuti Tahunan';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'permintaan') {
      if ($param2 == 'izin_jam_kerja') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_pengajuan_izin_jam_kerja';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Daftar Pengajuan Surat Izin pada Jam Kerja yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'izin_meninggalkan') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_pengajuan_izin_meninggalkan';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Daftar Pengajuan Surat Izin Meninggalkan Pekerjaan yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'cuti') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_pengajuan_izin_cuti';
        $page_data['page_title']    = 'Surat Cuti';
        $page_data['page_desc']     = 'Daftar Pengajuan Surat Cuti Tahunan yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'ba') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_pengajuan_berita_acara';
        $page_data['page_title']    = 'Berita Acara';
        $page_data['page_desc']     = 'Daftar Pengajuan Berita Acara yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'meetingroom') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_pengajuan_meetingroom';
        $page_data['page_title']    = 'Meeting Room';
        $page_data['page_desc']     = 'Daftar Pengajuan Ruang Meeting yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'izin_jam_kerja_sgm') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_pengajuan_izin_jam_kerja_sgm';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Daftar Pengajuan Surat Izin pada Jam Kerja yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'izin_meninggalkan_sgm') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_pengajuan_izin_meninggalkan_sgm';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Daftar Pengajuan Surat Izin Meninggalkan Pekerjaan yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'cuti_sgm') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_pengajuan_izin_cuti_sgm';
        $page_data['page_title']    = 'Surat Cuti';
        $page_data['page_desc']     = 'Daftar Pengajuan Surat Cuti Tahunan yang anda Ajukan';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'list') {
      if ($param2 == 'sijk') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_surat_list_izin_jam_kerja';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Daftar Pengajuan Surat Izin pada Jam Kerja yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'simp') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_surat_list_izin_meninggalkan';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Daftar Pengajuan Surat Izin Meninggalkan Pekerjaan yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'cuti') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_surat_list_izin_cuti';
        $page_data['page_title']    = 'Surat Cuti';
        $page_data['page_desc']     = 'Daftar Pengajuan Surat Cuti Tahunan yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'berita_acara') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_surat_list_berita_acara';
        $page_data['page_title']    = 'Berita Acara';
        $page_data['page_desc']     = 'Daftar Pengajuan Berita Acara yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'paklaring') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_paklaring';
        $page_data['page_title']    = 'Paklaring';
        $page_data['page_desc']     = 'Daftar Pengajuan Surat Pengalaman Kerja yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'meetingroom') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_surat_list_meetingroom';
        $page_data['page_title']    = 'Meeting Room';
        $page_data['page_desc']     = 'Daftar Pengajuan Ruang Meeting yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'sijk_sgm') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_surat_list_izin_jam_kerja_sgm';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Daftar Pengajuan Surat Izin pada Jam Kerja yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'simp_sgm') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_surat_list_izin_meninggalkan_sgm';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Daftar Pengajuan Surat Izin Meninggalkan Pekerjaan yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'cuti_sgm') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat/v_surat_list_izin_cuti_sgm';
        $page_data['page_title']    = 'Surat Cuti';
        $page_data['page_desc']     = 'Daftar Pengajuan Surat Cuti Tahunan yang Diajukan';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'pengajuan') {
      if ($param2 == 'izin_jam_kerja') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['page_name']     = 'surat/v_aju_izin_jam_kerja';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Form Izin pada Jam Kerja';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'izin_meninggalkan') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['page_name']     = 'surat/v_aju_izin_meninggalkan';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Form Izin Meninggalkan Pekerjaan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'cuti') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['page_name']     = 'surat/v_aju_izin_cuti';
        $page_data['page_title']    = 'Surat Cuti';
        $page_data['page_desc']     = 'Form Cuti Tahunan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'ba') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_nama']     = $this->md_surat_part_two->getBywhereActive();
        $page_data['page_name']     = 'surat/v_aju_berita_acara';
        $page_data['page_title']    = 'Berita Acara';
        $page_data['page_desc']     = 'Form Berita Acara';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'paklaring') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_nama']     = $this->md_surat_part_two->getBywhereActive();
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['page_name']     = 'surat/v_aju_paklaring';
        $page_data['page_title']    = 'Paklaring';
        $page_data['page_desc']     = 'Form Pengalaman Kerja';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'meetingroom') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['page_name']     = 'surat/v_permintaan_meetingroom';
        $page_data['page_title']    = 'Meeting Room';
        $page_data['page_desc']     = 'Form Pengajuan Penggunaan Ruang Meeting';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'izin_jam_kerja_sgm') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['page_name']     = 'surat/v_aju_izin_jam_kerja_sgm';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Form Izin pada Jam Kerja';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'izin_meninggalkan_sgm') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['page_name']     = 'surat/v_aju_izin_meninggalkan_sgm';
        $page_data['page_title']    = 'Surat Izin';
        $page_data['page_desc']     = 'Form Izin Meninggalkan Pekerjaan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'cuti_sgm') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['page_name']     = 'surat/v_aju_izin_cuti_sgm';
        $page_data['page_title']    = 'Surat Cuti';
        $page_data['page_desc']     = 'Form Cuti Tahunan';
        $this->load->view('index', $page_data);
      }
    }
  }


  //ADD
  public function addIzin($param = "")
  {
    grantAccessFor('all');

    if ($param == "addIzinJamKerja") {
      //menambah pengajuan Surat Izin pada Jam Kerja
      $this->md_surat_part_two->reset_increment("surat_izin_jam_kerja");
      //$idFpp = $this->md_surat_part_two->getIzinJamKodeId();
      $idFpp = $this->md_surat_part_two->getSijkKodeId();
      $ambilId = $idFpp->id;
      $ambilId = ($ambilId + 1);
      $panjangId = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/IZIN/HRGA/VYM/" . $bulan . "/" . $tahun;
      $data['kode_ijk']      = $kodeFpp;

      $data['id_pengaju']     = sessPenggunaId();
      $data['alasan']          = $this->input->post('alasan', TRUE);
      $data['tanggal']        = date_db_format($this->input->post('tanggal', TRUE));
      $data['jam_mulai']      = $this->input->post('jam_mulai', TRUE);
      $data['jam_akhir']      = $this->input->post('jam_akhir', TRUE);
      $data['kota_pengajuan']  = $this->input->post('kota_aju', TRUE);
      $data['lampiran']        = $this->input->post('lampiran', TRUE);
      $data['tgl_pengajuan']  = date_db_format($this->input->post('pengajuan', TRUE));
      $data['status']         = 0;
      $data['jenis']          = 1;
      $insertId = $this->md_surat_part_two->addIzinJam($data);



      // Notifikasi WA ke GA (id=58) saat SIJK diajukan
      $approverSijk = $this->md_pengguna->getById(58);
      $noPengajuSijk = $this->md_pengguna->getById(sessPenggunaId());
      if (!empty($approverSijk[0]->no_hp)) {
        waIzinJamKerjaPengajuan([
          'noPenerima'   => $approverSijk[0]->no_hp,
          'namaApprover' => $approverSijk[0]->nama,
          'namaPengaju'  => $noPengajuSijk[0]->nama,
          'kodeSurat'    => $kodeFpp,
          'tglIzin'      => $data['tanggal'],
          'jamMulai'     => $data['jam_mulai'],
          'jamSelesai'   => $data['jam_akhir'],
          'keperluan'    => $data['alasan'],
          'linkApproval' => 'https://office.visiyosindo.id/surat_part_two/show/detail/izin_jam_kerja/' . $insertId
        ]);
      }


      /** LOG */
      addLog('Pengajuan Surat Izin Pada Jam Kerja', 'Permintaan Surat Izin Pada Jam Kerja');
      ajaxReturnDie('success', 'Surat Izin Pada Jam Kerja Berhasil Diajukan');
    } else if ($param == "addIzinMeninggalkan") {
      //menambah pengajuan Surat Izin Meninggalkan Pekerjaan
      $this->md_surat_part_two->reset_increment("surat_izin_jam_kerja");
      $idFpp = $this->md_surat_part_two->getSimpKodeId();
      $ambilId = $idFpp->id;
      $ambilId = ($ambilId + 1);
      $panjangId = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/IZIN/HRGA/VYM/" . $bulan . "/" . $tahun;
      $data['kode_ijk']      = $kodeFpp;

      $data['id_pengaju']     = sessPenggunaId();
      $data['alasan']          = $this->input->post('alasan', TRUE);
      $data['jenis_izin']     = $this->input->post('jenis_izin', TRUE);
      $data['total']          = $this->input->post('total', TRUE);
      $data['tgl_awal']       = date_db_format($this->input->post('tgl_awal', TRUE));
      $data['tgl_akhir']      = date_db_format($this->input->post('tgl_akhir', TRUE));
      $data['kota_pengajuan']  = $this->input->post('kota_aju', TRUE);
      $data['lampiran']        = $this->input->post('lampiran', TRUE);
      $data['tgl_pengajuan']  = date_db_format($this->input->post('pengajuan', TRUE));
      $data['status']         = 0;
      $data['jenis']          = 2;
      $insertId = $this->md_surat_part_two->addIzinJam($data);



      // Notifikasi WA ke GA (id=58) saat SIMP diajukan
      $approverSimp = $this->md_pengguna->getById(58);
      $noPengajuSimp = $this->md_pengguna->getById(sessPenggunaId());
      if (!empty($approverSimp[0]->no_hp)) {
        waIzinMeninggalkanPengajuan([
          'noPenerima'   => $approverSimp[0]->no_hp,
          'namaApprover' => $approverSimp[0]->nama,
          'namaPengaju'  => $noPengajuSimp[0]->nama,
          'kodeSurat'    => $kodeFpp,
          'tglMulai'     => $data['tgl_awal'],
          'tglAkhir'     => $data['tgl_akhir'],
          'totalHari'    => $data['total'],
          'alasan'       => $data['alasan'],
          'linkApproval' => 'https://office.visiyosindo.id/surat_part_two/show/detail/izin_meninggalkan/' . $insertId
        ]);
      }


      /** LOG */
      addLog('Pengajuan Surat Izin Meninggalkan Pekerjaan', 'Permintaan Surat Izin Meninggalkan Pekerjaan');
      ajaxReturnDie('success', 'Surat Izin Meninggalkan Pekerjaan Berhasil Diajukan');
    } else if ($param == "addCuti") {
      //menambah pengajuan Surat Izin Meninggalkan Pekerjaan
      $this->md_surat_part_two->reset_increment("surat_cuti_tahunan");
      $idFpp = $this->md_surat_part_two->getCutiKodeId();
      $ambilId = $idFpp->id;
      $ambilId = ($ambilId + 1);
      $panjangId = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/CUTI/HRGA/VYM/" . $bulan . "/" . $tahun;
      $data['kode_cuti']      = $kodeFpp;

      $data['id_pengaju']     = sessPenggunaId();
      $data['alasan']          = $this->input->post('alasan', TRUE);
      $data['jenis_izin']     = "cuti";
      $data['total']          = $this->input->post('total', TRUE);
      $data['tgl_awal']       = date_db_format($this->input->post('tgl_awal', TRUE));
      $data['tgl_akhir']      = date_db_format($this->input->post('tgl_akhir', TRUE));
      $data['kota_pengajuan']  = $this->input->post('kota_aju', TRUE);
      $data['lampiran']        = $this->input->post('lampiran', TRUE);
      $data['tgl_pengajuan']  = date_db_format($this->input->post('pengajuan', TRUE));
      $data['status']         = 0;
      $data['jenis']          = 3;
      $insertId = $this->md_surat_part_two->addCuti($data);



      // Notifikasi WA ke GA (id=58) saat Cuti diajukan
      $approverCuti = $this->md_pengguna->getById(58);
      $noPengajuCuti = $this->md_pengguna->getById(sessPenggunaId());
      if (!empty($approverCuti[0]->no_hp)) {
        waCutiPengajuan([
          'noPenerima'   => $approverCuti[0]->no_hp,
          'namaApprover' => $approverCuti[0]->nama,
          'namaPengaju'  => $noPengajuCuti[0]->nama,
          'kodeSurat'    => $kodeFpp,
          'tglMulai'     => $data['tgl_awal'],
          'tglAkhir'     => $data['tgl_akhir'],
          'totalHari'    => $data['total'],
          'alasan'       => $data['alasan'],
          'linkApproval' => 'https://office.visiyosindo.id/surat_part_two/show/detail/cuti/' . $insertId
        ]);
      }


      /** LOG */
      addLog('Pengajuan Cuti Tahunan', 'Permintaan Cuti Tahunan');
      ajaxReturnDie('success', 'Surat Cuti Tahunan Berhasil Diajukan');
    } else if ($param == "addIzinJamKerjaSgm") {
      //menambah pengajuan Surat Izin pada Jam Kerja
      $this->md_surat_part_two->reset_increment("surat_sgm");
      //$idFpp = $this->md_surat_part_two->getIzinJamKodeId();
      $idFpp = $this->md_surat_part_two->getSijkSgmKodeId();
      $ambilId = $idFpp->id;
      $ambilId = ($ambilId + 1);
      $panjangId = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/IZIN/HRGA/SGM/" . $bulan . "/" . $tahun;
      $data['kode']      = $kodeFpp;

      $data['id_pengaju']     = sessPenggunaId();
      $data['alasan']          = $this->input->post('alasan', TRUE);
      $data['tgl_awal']       = date_db_format($this->input->post('tanggal', TRUE));
      $data['jam_mulai']      = $this->input->post('jam_mulai', TRUE);
      $data['jam_akhir']      = $this->input->post('jam_akhir', TRUE);
      $data['kota_pengajuan']  = $this->input->post('kota_aju', TRUE);
      $data['lampiran']        = $this->input->post('lampiran', TRUE);
      $data['tgl_pengajuan']  = date_db_format($this->input->post('pengajuan', TRUE));
      $data['status']         = 0;
      $data['jenis']          = 1;
      $insertId = $this->md_surat_part_two->addSgm($data);



      //send notif wa TEST
      $dataWa = [
        'idPenerima1'   => 58,
        'idPenerima2'   => '',
        'namaSurat'     => 'Surat Izin Pada Jam Kerja PT Sumpah Gajah Mada',
        'penerima'       => '_General Affair_',
        'perihal'       => $data['alasan'],
        'link'          => 'surat_part_two/show/detail/izin_jam_kerja_sgm/' . $insertId,
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Pengajuan Izin Pada Jam Kerja', 'Permintaan Surat Izin Pada Jam Kerja');
      ajaxReturnDie('success', 'Surat Izin Pada Jam Kerja Berhasil Diajukan');
    } else if ($param == "addIzinMeninggalkanSgm") {
      //menambah pengajuan Surat Izin Meninggalkan Pekerjaan
      $this->md_surat_part_two->reset_increment("surat_sgm");
      $idFpp = $this->md_surat_part_two->getSimpSgmKodeId();
      $ambilId = $idFpp->id;
      $ambilId = ($ambilId + 1);
      $panjangId = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/IZIN/HRGA/SGM/" . $bulan . "/" . $tahun;
      $data['kode']      = $kodeFpp;

      $data['id_pengaju']     = sessPenggunaId();
      $data['alasan']          = $this->input->post('alasan', TRUE);
      $data['jenis_izin']     = $this->input->post('jenis_izin', TRUE);
      $data['total']          = $this->input->post('total', TRUE);
      $data['tgl_awal']       = date_db_format($this->input->post('tgl_awal', TRUE));
      $data['tgl_akhir']      = date_db_format($this->input->post('tgl_akhir', TRUE));
      $data['kota_pengajuan']  = $this->input->post('kota_aju', TRUE);
      $data['lampiran']        = $this->input->post('lampiran', TRUE);
      $data['tgl_pengajuan']  = date_db_format($this->input->post('pengajuan', TRUE));
      $data['status']         = 0;
      $data['jenis']          = 2;
      $insertId = $this->md_surat_part_two->addSgm($data);



      //send notif wa TEST
      $dataWa = [
        'idPenerima1'   => 58,
        'idPenerima2'   => '',
        'namaSurat'     => 'Surat Izin Meninggalkan Pekerjaan',
        'penerima'       => '_General Affair_',
        'perihal'       => $data['alasan'],
        'link'          => 'surat_part_two/show/detail/izin_meninggalkan_sgm/' . $insertId,
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Pengajuan Surat Izin Meninggalkan Pekerjaan', 'Permintaan Surat Izin Meninggalkan Pekerjaan');
      ajaxReturnDie('success', 'Surat Izin Meninggalkan Pekerjaan Berhasil Diajukan');
    } else if ($param == "addCutiSgm") {
      //menambah pengajuan Surat Izin Meninggalkan Pekerjaan
      $this->md_surat_part_two->reset_increment("surat_sgm");
      $idFpp = $this->md_surat_part_two->getCutiSgmKodeId();
      $ambilId = $idFpp->id;
      $ambilId = ($ambilId + 1);
      $panjangId = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/CUTI/HRGA/SGM/" . $bulan . "/" . $tahun;
      $data['kode']      = $kodeFpp;

      $data['id_pengaju']     = sessPenggunaId();
      $data['alasan']          = $this->input->post('alasan', TRUE);
      $data['jenis_izin']     = "cuti";
      $data['total']          = $this->input->post('total', TRUE);
      $data['tgl_awal']       = date_db_format($this->input->post('tgl_awal', TRUE));
      $data['tgl_akhir']      = date_db_format($this->input->post('tgl_akhir', TRUE));
      $data['kota_pengajuan']  = $this->input->post('kota_aju', TRUE);
      $data['lampiran']        = $this->input->post('lampiran', TRUE);
      $data['tgl_pengajuan']  = date_db_format($this->input->post('pengajuan', TRUE));
      $data['status']         = 0;
      $data['jenis']          = 3;
      $insertId = $this->md_surat_part_two->addSgm($data);



      //send notif wa TEST
      $dataWa = [
        'idPenerima1'   => 58,
        'idPenerima2'   => '',
        'namaSurat'     => 'Cuti Tahunan',
        'penerima'       => '_General Affair_',
        'perihal'       => $data['alasan'],
        'link'          => 'surat_part_two/show/detail/cuti_sgm/' . $insertId,
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Pengajuan Cuti Tahunan', 'Permintaan Cuti Tahunan');
      ajaxReturnDie('success', 'Surat Cuti Tahunan Berhasil Diajukan');
    }
  }


  public function addSrt($param = "")
  {
    grantAccessFor('all');

    if ($param == "addBeritaAcara") {
      //menambah pengajuan Berita Acara
      $this->md_surat_part_two->reset_increment("surat_berita_acara");
      $idFpp = $this->md_surat_part_two->getBaKodeId();
      $ambilId = $idFpp->id;
      $ambilId = ($ambilId + 1);
      $panjangId = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/BA/HRGA/VYM/" . $bulan . "/" . $tahun;
      $data['kode_ba']      = $kodeFpp;

      $data['id_pengaju']     = sessPenggunaId();
      $data['tanggal']        = date_db_format($this->input->post('tanggal', TRUE));
      $data['analisis']       = $this->input->post('analisis', TRUE);
      $data['hasil']          = $this->input->post('hasil', TRUE);
      $data['penanganan']     = $this->input->post('penanganan', TRUE);
      $data['id_diketahui']    = $this->input->post('id_diketahui', TRUE);
      $data['id_disetujui']    = $this->input->post('id_disetujui', TRUE);
      $data['status_dir']      = ($this->input->post('direktor', TRUE) == 1) ? 1 : 0;
      $data['id_dir']          = ($data['status_dir'] == 1) ? $this->input->post('id_dir', TRUE) : null;
      $data['lampiran']        = $this->input->post('lampiran', TRUE);
      $data['status']         = 0;
            $this->md_surat_part_two->addBeritaAcara($data);

      // =========================================================================
      // 💬 KODE NOTIFIKASI WA BERITA ACARA + DEBUG OTOMATIS
      // =========================================================================
      $this->load->helper('whatsapp_helper');
      $lastBaId = $this->db->insert_id();
      if (empty($lastBaId)) {
          $lastRow = $this->db->select('id')->from('surat_berita_acara')->where('kode_ba', $kodeFpp)->get()->row();
          $lastBaId = $lastRow ? $lastRow->id : '';
      }
      $idPenerima = (!empty($data['id_diketahui'])) ? $data['id_diketahui'] : (!empty($data['id_disetujui']) ? $data['id_disetujui'] : ($data['status_dir'] == 1 ? $data['id_dir'] : ''));
      if (empty($idPenerima)) {
          ajaxReturnDie('error', 'DEBUG FAIL: Anda belum memilih Atasan (Diketahui/Disetujui/Direktur)!', FALSE);
      }
      $dataPenerima = $this->md_pengguna->getById($idPenerima);
      if (empty($dataPenerima) || !isset($dataPenerima[0])) {
          ajaxReturnDie('error', 'DEBUG FAIL: ID Atasan ' . $idPenerima . ' tidak ditemukan di database!', FALSE);
      }
      $atasan     = $dataPenerima[0];
      $noHpAtasan = !empty($atasan->no_hp) ? $atasan->no_hp : (!empty($atasan->hp) ? $atasan->hp : '');
      if (empty($noHpAtasan)) {
          ajaxReturnDie('error', 'DEBUG FAIL: Akun Atasan (' . $atasan->nama . ') TIDAK MEMILIKI NOMOR HP di database!', FALSE);
      }
      $pemohon     = $this->md_pengguna->getById(sessPenggunaId());
      $namaPemohon = (!empty($pemohon) && isset($pemohon[0])) ? $pemohon[0]->nama : 'Karyawan';
      $res = waPengajuanBaru([
        'noPenerima'       => $noHpAtasan,
        'namaApprover'     => $atasan->nama,
        'jenisPengajuan'   => 'Berita Acara (Kode: ' . $kodeFpp . ')',
        'namaPemohon'      => $namaPemohon,
        'tanggalPengajuan' => date('d-m-Y'),
        'keterangan'       => 'Membutuhkan Tanda Tangan / Persetujuan Berita Acara',
        'linkDetail'       => 'https://office.visiyosindo.id/surat_part_two/show/detail/ba/' . $lastBaId
      ]);
      if (!$res) {
          ajaxReturnDie('error', 'DEBUG FAIL: Panggilan API Convia GAGAL dikirim ke nomor ' . $noHpAtasan . ' (' . $atasan->nama . '). Cek koneksi Convia / API Key!', FALSE);
      }
      // =========================================================================
      // =========================================================================
      
      /** LOG */
      addLog('Pengajuan Berita Acara', 'Permintaan Berita Acara');
      ajaxReturnDie('success', 'Berita Acara Berhasil Diajukan', TRUE);
    } else if ($param == "paklaring") {
      //menambah pengajuan Pengalaman Kerja
      $this->md_surat_part_two->reset_increment("surat_paklaring");
      $idFpp = $this->md_surat_part_two->getPaklaringKodeId();
      $ambilId = $idFpp->id;
      $ambilId = ($ambilId + 1);
      $panjangId = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();
      $kodeFpp = $kodeFpp . "/S.Ket/HRGA/PT.VYM/PKU/" . $bulan . "/" . $tahun;
      $data['kode']      = $kodeFpp;

      $data['id_pengaju']     = sessPenggunaId();
      $data['id_pegawai']     = $this->input->post('id_pegawai', TRUE);
      $data['kota_pengajuan']  = $this->input->post('kota_aju', TRUE);
      $data['tgl_pengajuan']  = date_db_format($this->input->post('pengajuan', TRUE));
      $data['status']         = 0;
      $insertId = $this->md_surat_part_two->addPaklaring($data);


      //send notif wa 
      $dataWa = [
        'idPenerima1'   => 69,
        'idPenerima2'   => '',
        'namaSurat'     => 'Pengalaman Kerja',
        'penerima'       => '_HR and Legal_',
        'perihal'       => 'Keterangan Pengalaman Kerja',
        'link'          => 'surat_part_two/show/detail/paklaring/' . $insertId,
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Pengajuan Pengalaman Kerja', 'Permintaan Pengalaman Kerja');
      ajaxReturnDie('success', 'Paklaring Berhasil Diajukan', TRUE);
    } else if ($param == "meetingroom") {
      //menambah pengajuan Meeting Room
      $this->md_surat_part_two->reset_increment("surat_meetingroom");
      $idFpp = $this->md_surat_part_two->getMeetingKodeId();
      $ambilId = $idFpp->id;
      $ambilId = ($ambilId + 1);
      $panjangId = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }


      $bulan = ambil_bulan();
      $tahun = ambil_tahun();
      $kodeFpp = $kodeFpp . "/MR/HRGA/VYM/" . $bulan . "/" . $tahun;
      $data['kode']      = $kodeFpp;

      $data['id_pengaju']     = sessPenggunaId();
      $data['perihal']        = $this->input->post('perihal', TRUE);
      $data['tgl_pengajuan']  = date_db_format($this->input->post('tgl_pengajuan', TRUE));
      $data['jam_mulai']      = $this->input->post('jam_mulai', TRUE);
      $data['jam_akhir']      = $this->input->post('jam_akhir', TRUE);
      $data['lampiran']        = $this->input->post('lampiran', TRUE);
      $data['status']         = 0;
      $insertId = $this->md_surat_part_two->addMeeting($data);


      //send notif wa 
      $dataWa = [
        'idPenerima1'   => 58,
        'idPenerima2'   => '',
        'namaSurat'     => 'Penggunaan Ruang Meeting',
        'penerima'       => 'General Affair',
        'perihal'       => $data['perihal'],
        'link'          => 'surat_part_two/show/detail/meetingroom/' . $insertId,
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Pengajuan Meeting Room', 'Permintaan Ruang Meeting');
      ajaxReturnDie('success', 'meeting room Berhasil Diajukan', TRUE);
    }
  }


  //UPDATE

  public function ttd_setujui($param1 = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');

    if ($param1 == "sijk") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1'] = 1;
        $this->md_surat_part_two->updateSijk($id_sp, $data);

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '69',
          'idPenerima2'   => '',
          'namaSurat'     => 'Surat Izin pada Jam Kerja',
          'penerima'       => '_HR and Legal Officer_',
          'ttd_sebelum1'   => 'General Affair',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 1, $dataWa);

        addLog('Pengajuan Surat Izin Pada Jam Kerja Disetujui oleh GA', 'Permintaan Surat Izin Pada Jam Kerja Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {


        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_2'] = 1;
        $this->md_surat_part_two->updateSijk($id_sp, $data);

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '33',
          'idPenerima2'   => '',
          'namaSurat'     => 'Surat Izin Pada Jam Kerja',
          'penerima'       => '_General Manager_',
          'ttd_sebelum1'   => 'HR and Legal Officer',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 1, $dataWa);

        /** LOG */
        addLog('Pengajuan Surat Izin Pada Jam Kerja Disetujui oleh HR ', 'Permintaan Surat Izin Pada Jam Kerja Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_3") {


        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd_3'] = 1;
        $this->md_surat_part_two->updateSijk($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_part_two->getSijkById($id_sp);
        $idPengaju          = $ambilDataPengaju[0]->idPengaju;
        $alasan              = $ambilDataPengaju[0]->alasan;
        $link_file          = $ambilDataPengaju[0]->lampiran;
        $tglIzin            = $ambilDataPengaju[0]->tanggal;
        $jamMulai            = $ambilDataPengaju[0]->jam_mulai;
        $jamAkhir            = $ambilDataPengaju[0]->jam_akhir;
        $dataTgl            = date('H:i:s', strtotime('now'));
        $ket                = "Izin pada jam kerja Pukul " . $jamMulai . " sampai " . $jamAkhir;
        //cek sudah absen atau belum
        $cek = $this->md_absensi->getAbsenIzinById($idPengaju, $tglIzin);
        if ($cek) {
          //Update data Tabel Absen
          //$data2['waktu_absen'] = $dataTgl;
          $data2['type_absen']  = 'izin';
          $data2['keterangan']  =  $ket;
          $data2['approval']    = 'tolak';
          $data2['tanpa_tunjangan'] = 1;
          //$data2['data_created']    = 1;
          $data2['status_absen']    = 'izin';
          $data2['file_pendukung']  = $link_file;
          $this->md_absensi->updateByWhereToday($data2, ['absensi.pengguna_id' => $idPengaju]);
        } else {
          //Input data ke Tabel Absen
          $data2['pengguna_id'] = $idPengaju;
          $data2['waktu_absen'] = $dataTgl;
          $data2['type_absen']  = 'izin';
          $data2['keterangan']  =  $ket;
          $data2['approval']    = 'tolak';
          $data2['tanpa_tunjangan'] = 1;
          $data2['data_created']    = $tglIzin;
          $data2['status_absen']    = 'izin';
          $data2['file_pendukung']  = $link_file;
          $this->md_absensi->add($data2);
        }

        //send notif wa
        $cekDevisi = $this->md_pengguna->getById($idPengaju);
        if ($cekDevisi[0]->id_divisi == 7) {
          //Notif ke Wa Pengaju & Pak Don
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => '',
            'idPenerima2'   => '58',
            'namaSurat'     => 'Surat Izin Pada Jam Kerja',
            //'penerima' 	    => '_General Manager_',
            'ttd_sebelum1'   => 'HR and Legal Officer',
            'ttd_sebelum2'   => 'General Manager',
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprovPb(2, 2, $dataWa);


          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => '1',
            'idPenerima2'   => '',
            'namaSurat'     => 'Surat Izin Pada Jam Kerja',
            'penerima'       => '_Kardonal_',
            'ttd_sebelum1'   => 'HR and Legal Officer',
            'ttd_sebelum2'   => 'General Manager',
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprovPb(1, 3, $dataWa);
        } else {
          //Notif ke Wa Pengaju
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => '',
            'idPenerima2'   => '',
            'namaSurat'     => 'Surat Izin Pada Jam Kerja',
            //'penerima' 	    => '_General Manager_',
            'ttd_sebelum1'   => 'HR and Legal Officer',
            'ttd_sebelum2'   => 'General Manager',
            'ttd_sebelum3'   => ''
          ];

          // $this->notifWaAprovPb(1, 2, $dataWa);
        }

        // =========================================================================
        // SEND NOTIF WA GROUP (VISI YOSINDO MEDICAL) - UNTUK SEMUA CUTI VYM
        // =========================================================================
        $this->notifWaGroupCuti($id_sp, 'Visi Yosindo Medical');
            // =========================================================================

        // Notif WA DISETUJUI ke pengaju SIJK
        $sijkPengajuData = $this->md_pengguna->getById($idPengaju);
        if (!empty($sijkPengajuData[0]->no_hp)) {
          waIzinJamKerjaHasil([
            'noPenerima'   => $sijkPengajuData[0]->no_hp,
            'namaKaryawan' => $sijkPengajuData[0]->nama,
            'kodeSurat'    => $ambilDataPengaju[0]->kode_ijk,
            'status'       => 'DISETUJUI',
            'namaApprover' => 'General Manager',
            'alasan'       => '',
            'linkDetail'   => 'https://office.visiyosindo.id/surat_part_two/show/detail/izin_jam_kerja/' . $id_sp
          ]);
        }

        /** LOG */
        addLog('Pengajuan Surat Izin Pada Jam Kerja Disetujui oleh HR ', 'Permintaan Surat Izin Pada Jam Kerja Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "simp") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1'] = 1;
        $this->md_surat_part_two->updateSijk($id_sp, $data);

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '69',
          'idPenerima2'   => '',
          'namaSurat'     => 'Surat Izin Meninggalkan Pekerjaan',
          'penerima'       => '_HR and Legal Officer_',
          'ttd_sebelum1'   => 'General Affair',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 1, $dataWa);

        addLog('Pengajuan Surat Izin Meninggalkan Pekerjaan Disetujui oleh GA', 'Permintaan Surat Izin Meninggalkan Pekerjaan Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {


        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_2'] = 1;
        $this->md_surat_part_two->updateSijk($id_sp, $data);

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '33',
          'idPenerima2'   => '',
          'namaSurat'     => 'Surat Izin Meninggalkan Pekerjaan',
          'penerima'       => '_General Manager_',
          'ttd_sebelum1'   => 'HR and Legal Officer',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 1, $dataWa);

        /** LOG */
        addLog('Pengajuan Surat Izin Meninggalkan Pekerjaan Disetujui oleh HR ', 'Permintaan Surat Izin Meninggalkan Pekerjaan Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_3") {


        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd_3'] = 1;
        $this->md_surat_part_two->updateSijk($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_part_two->getSijkById($id_sp);
        $idPengaju          = $ambilDataPengaju[0]->idPengaju;
        $jenisIzin          = $ambilDataPengaju[0]->jenis_izin;
        $alasan              = $ambilDataPengaju[0]->alasan;
        $tgl_awal            = $ambilDataPengaju[0]->tgl_awal;
        $tgl_akhir          = $ambilDataPengaju[0]->tgl_akhir;
        $link_file          = $ambilDataPengaju[0]->lampiran;

        //====================================
        //=========  INPUT Ke Tabel Absen ====
        //====================================     

        $dataTgl = date('H:i:s', strtotime('now'));

        while (strtotime($tgl_awal) <= strtotime($tgl_akhir)) {
          $data_in = [
            'pengguna_id'       => $idPengaju,
            'waktu_absen'       => $dataTgl,
            'type_absen'        => 'izin',
            'keterangan'        => $alasan,
            'approval'          => 'tolak',
            'tanpa_tunjangan'   => 1,
            'data_created'      => $tgl_awal,
            'status_absen'      => $jenisIzin,
            'file_pendukung'    => $link_file
          ];
          $this->md_absensi->add($data_in);
          $tgl_awal = date("Y-m-d", strtotime("+1 day", strtotime($tgl_awal))); //looping tambah 1 date
        }


        //====================================
        //====== End INPUT Ke Tabel Absen ====
        //====================================


        //send notif wa
        $cekDevisi = $this->md_pengguna->getById($idPengaju);
        if ($cekDevisi[0]->id_divisi == 7) {
          //Notif ke Wa Pengaju & Pak Don
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => '',
            'idPenerima2'   => '58',
            'namaSurat'     => 'Surat Izin Meninggalkan Pekerjaan',
            //'penerima' 	    => '_General Manager_',
            'ttd_sebelum1'   => 'HR and Legal Officer',
            'ttd_sebelum2'   => 'General Manager',
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprovPb(2, 2, $dataWa);


          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => '1',
            'idPenerima2'   => '',
            'namaSurat'     => 'Surat Izin Meninggalkan Pekerjaan',
            'penerima'       => '_Kardonal_',
            'ttd_sebelum1'   => 'HR and Legal Officer',
            'ttd_sebelum2'   => 'General Manager',
            'ttd_sebelum3'   => ''
          ];

          //   $this->notifWaAprovPb(1, 4, $dataWa);
        } else {
          //Notif ke Wa Pengaju
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => '',
            'idPenerima2'   => '',
            'namaSurat'     => 'Surat Izin Meninggalkan Pekerjaan',
            //'penerima' 	    => '_General Manager_',
            'ttd_sebelum1'   => 'HR and Legal Officer',
            'ttd_sebelum2'   => 'General Manager',
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprovPb(1, 2, $dataWa);
        }

        // Notif WA DISETUJUI ke pengaju SIMP
        $simpPengajuData = $this->md_pengguna->getById($idPengaju);
        if (!empty($simpPengajuData[0]->no_hp)) {
          waIzinMeninggalkanHasil([
            'noPenerima'   => $simpPengajuData[0]->no_hp,
            'namaKaryawan' => $simpPengajuData[0]->nama,
            'kodeSurat'    => $ambilDataPengaju[0]->kode_ijk,
            'status'       => 'DISETUJUI',
            'namaApprover' => 'General Manager',
            'alasan'       => '',
            'linkDetail'   => 'https://office.visiyosindo.id/surat_part_two/show/detail/izin_meninggalkan/' . $id_sp
          ]);
        }

        /** LOG */
        addLog('Pengajuan Surat Izin Meninggalkan Pekerjaan Disetujui oleh HR ', 'Permintaan Surat Izin Meninggalkan Pekerjaan Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "cuti") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1'] = 1;
        $this->md_surat_part_two->updateCuti($id_sp, $data);

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '69',
          'idPenerima2'   => '',
          'namaSurat'     => 'Cuti Tahunan',
          'penerima'       => '_HR and Legal Officer_',
          'ttd_sebelum1'   => 'General Affair',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovCuti(1, 1, $dataWa);

        addLog('Pengajuan Cuti Tahunan Disetujui oleh GA', 'Permintaan Cuti Tahunan Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {


        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_2'] = 1;
        $this->md_surat_part_two->updateCuti($id_sp, $data);

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '33',
          'idPenerima2'   => '',
          'namaSurat'     => 'Cuti Tahunan',
          'penerima'       => '_General Manager_',
          'ttd_sebelum1'   => 'HR and Legal Officer',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovCuti(1, 1, $dataWa);

        /** LOG */
        addLog('Pengajuan Cuti Tahunan Disetujui oleh HR ', 'Permintaan Cuti Tahunan Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_3") {


        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd_3'] = 1;
        $this->md_surat_part_two->updateCuti($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_part_two->getCutiById($id_sp);
        $idPengaju          = $ambilDataPengaju[0]->idPengaju;
        $jenisIzin          = $ambilDataPengaju[0]->jenis_izin;
        $alasan              = $ambilDataPengaju[0]->alasan;
        $tgl_awal            = $ambilDataPengaju[0]->tgl_awal;
        $tgl_akhir          = $ambilDataPengaju[0]->tgl_akhir;
        $link_file          = $ambilDataPengaju[0]->lampiran;


        //====================================
        //=========  INPUT Ke Tabel Absen ====
        //====================================     

        $dataTgl = date('H:i:s', strtotime('now'));

        while (strtotime($tgl_awal) <= strtotime($tgl_akhir)) {
          $data_in = [
            'pengguna_id'       => $idPengaju,
            'waktu_absen'       => $dataTgl,
            'type_absen'        => 'izin',
            'keterangan'        => 'Cuti Tahunan',
            'approval'          => 'tolak',
            'tanpa_tunjangan'   => 1,
            'data_created'      => $tgl_awal,
            'status_absen'      => $jenisIzin,
            'file_pendukung'    => $link_file
          ];
          $this->md_absensi->add($data_in);
          $tgl_awal = date("Y-m-d", strtotime("+1 day", strtotime($tgl_awal))); //looping tambah 1 date
        }


        //====================================
        //====== End INPUT Ke Tabel Absen ====
        //====================================


        //send notif wa
        $cekDevisi = $this->md_pengguna->getById($idPengaju);
        if ($cekDevisi[0]->id_divisi == 7) {
          //Notif ke Wa Pengaju & Pak Don
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => '',
            'idPenerima2'   => '58',
            'namaSurat'     => 'Cuti Tahunan',
            //'penerima' 	    => '_General Manager_',
            'ttd_sebelum1'   => 'HR and Legal Officer',
            'ttd_sebelum2'   => 'General Manager',
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprovCuti(2, 2, $dataWa);


          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => '11',
            'idPenerima2'   => '',
            'namaSurat'     => 'Cuti Tahunan',
            'penerima'       => '_Kardonal_',
            'ttd_sebelum1'   => 'HR and Legal Officer',
            'ttd_sebelum2'   => 'General Manager',
            'ttd_sebelum3'   => ''
          ];

          // $this->notifWaAprovCuti(1, 4, $dataWa);
        } else {
          //Notif ke Wa Pengaju
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => '',
            'idPenerima2'   => '',
            'namaSurat'     => 'Cuti Tahunan',
            //'penerima' 	    => '_General Manager_',
            'ttd_sebelum1'   => 'HR and Legal Officer',
            'ttd_sebelum2'   => 'General Manager',
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprovCuti(1, 2, $dataWa);
        }
        /** LOG */
        addLog('Pengajuan Cuti Tahunan Disetujui oleh HR ', 'Permintaan Cuti Tahunan Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "ba") {
      if ($param2 == "ttd_diketahui") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_diketahui'] = 1;
        $this->md_surat_part_two->updateBeritaAcara($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_part_two->getBeritaAcaraById($id_sp);
        $id_disetujui        = $ambilDataPengaju[0]->id_disetujui;
        $nama_disetujui     = $ambilDataPengaju[0]->nama_disetujui;
        $kdBa               = $ambilDataPengaju[0]->kode_ba;
        $pengaju            = $ambilDataPengaju[0]->pengaju;
        $jabatan            = $ambilDataPengaju[0]->jabatan_diketahui;
        $analisis           = !empty($ambilDataPengaju[0]->analisis) ? $ambilDataPengaju[0]->analisis : $kdBa;

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => $id_disetujui,
          'idPenerima2'   => '',
          'namaSurat'     => 'Berita Acara',
          'penerima'       => $nama_disetujui,
          'pengaju'       => $pengaju,
          'kode'           => $kdBa,
          'perihal'        => $analisis,
          'link'           => 'surat_part_two/show/detail/ba/' . $id_sp,
          'ttd_sebelum1'   => $jabatan,
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprov(1, 1, $dataWa);

        addLog('Pengajuan Berita Acara Disetujui', 'Permintaan Berita Acara Disetujui ' . $jabatan);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_disetujui") {


        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_disetujui'] = 1;
        $this->md_surat_part_two->updateBeritaAcara($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_part_two->getBeritaAcaraById($id_sp);
        $idPengaju          = $ambilDataPengaju[0]->idPengaju;
        $nama_disetujui     = $ambilDataPengaju[0]->nama_disetujui;
        $kdBa               = $ambilDataPengaju[0]->kode_ba;
        $pengaju            = $ambilDataPengaju[0]->pengaju;
        $jabatan            = $ambilDataPengaju[0]->jabatan_diketahui;
        $jabatanSetu        = $ambilDataPengaju[0]->jabatan_disetujui;
        $id_dir             = $ambilDataPengaju[0]->id_dir;
        $jabatanDir         = $ambilDataPengaju[0]->jabatan_dir;
        $analisis           = !empty($ambilDataPengaju[0]->analisis) ? $ambilDataPengaju[0]->analisis : $kdBa;

        //Cek status direktor
        if ($ambilDataPengaju[0]->status_dir == 1) {
          //send notif wa
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => $id_dir,
            'idPenerima2'   => '',
            'namaSurat'     => 'Berita Acara',
            'penerima'       => $jabatanDir,
            'pengaju'       => $pengaju,
            'kode'           => $kdBa,
            'perihal'        => $analisis,
            'link'           => 'surat_part_two/show/detail/ba/' . $id_sp,
            'ttd_sebelum1'   => $jabatan,
            'ttd_sebelum2'   => $jabatanSetu,
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprov(1, 3, $dataWa);
        } else {
          //send notif wa
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => $idPengaju,
            'idPenerima2'   => '',
            'namaSurat'     => 'Berita Acara',
            'penerima'       => $pengaju,
            'pengaju'       => $pengaju,
            'kode'           => $kdBa,
            'perihal'        => $analisis,
            'link'           => 'surat_part_two/show/detail/ba/' . $id_sp,
            'ttd_sebelum1'   => $jabatan,
            'ttd_sebelum2'   => $jabatanSetu,
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprov(1, 2, $dataWa);

          //send notif wa ke GA
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => 58,
            'idPenerima2'   => '',
            'namaSurat'     => 'Berita Acara',
            'penerima'       => $pengaju,
            'pengaju'       => $pengaju,
            'kode'           => $kdBa,
            'perihal'        => $analisis,
            'link'           => 'surat_part_two/show/detail/ba/' . $id_sp,
            'ttd_sebelum1'   => $jabatan,
            'ttd_sebelum2'   => $jabatanSetu,
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprov(1, 4, $dataWa);
        }


        /** LOG */
        addLog('Pengajuan Berita Acara Disetujui', 'Permintaan Berita Acara Disetujui ' . $jabatanSetu);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_dir") {


        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd_dir'] = 1;
        $this->md_surat_part_two->updateBeritaAcara($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_part_two->getBeritaAcaraById($id_sp);
        $idPengaju          = $ambilDataPengaju[0]->idPengaju;
        $nama_disetujui     = $ambilDataPengaju[0]->nama_disetujui;
        $kdBa               = $ambilDataPengaju[0]->kode_ba;
        $pengaju            = $ambilDataPengaju[0]->pengaju;
        $jabatan            = $ambilDataPengaju[0]->jabatan_diketahui;
        $jabatanSetu        = $ambilDataPengaju[0]->jabatan_disetujui;
        $analisis           = !empty($ambilDataPengaju[0]->analisis) ? $ambilDataPengaju[0]->analisis : $kdBa;

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => $idPengaju,
          'idPenerima2'   => '',
          'namaSurat'     => 'Berita Acara',
          'penerima'       => $pengaju,
          'pengaju'       => $pengaju,
          'kode'           => $kdBa,
          'perihal'        => $analisis,
          'link'           => 'surat_part_two/show/detail/ba/' . $id_sp,
          'ttd_sebelum1'   => $jabatan,
          'ttd_sebelum2'   => $jabatanSetu,
          'ttd_sebelum3'   => 'Direktor'
        ];

        $this->notifWaAprov(1, 2, $dataWa);


        //send notif wa ke GA
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => 58,
          'idPenerima2'   => '',
          'namaSurat'     => 'Berita Acara',
          'penerima'       => $pengaju,
          'pengaju'       => $pengaju,
          'kode'           => $kdBa,
          'perihal'        => $analisis,
          'link'           => 'surat_part_two/show/detail/ba/' . $id_sp,
          'ttd_sebelum1'   => $jabatan,
          'ttd_sebelum2'   => $jabatanSetu,
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprov(1, 4, $dataWa);



        /** LOG */
        addLog('Pengajuan Berita Acara Disetujui', 'Permintaan Berita Acara Disetujui ' . $jabatanSetu);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "paklaring") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1'] = 1;
        $this->md_surat_part_two->updatePaklaring($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_part_two->getPaklaringById($id_sp);
        $kdBa               = $ambilDataPengaju[0]->kode;

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '58',
          'idPenerima2'   => '',
          'kode'           => $kdBa,
          'namaSurat'     => 'Pengalaman Kerja',
          'penerima'       => '_General Affair_',
          'ttd_sebelum1'   => '_HR and Legal_',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprov(1, 1, $dataWa);

        addLog('Pengajuan Pengalaman Kerja Disetujui oleh GM', 'Permintaan Pengalaman Kerja Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "meetingroom") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1']  = 1;
        $this->md_surat_part_two->updateMeeting($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_part_two->getMeetingById($id_sp);
        $kdBa               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->perihal;

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '69',
          'idPenerima2'   => '',
          'kode'           => $kdBa,
          'perihal'       => $perihal,
          'namaSurat'     => 'Penggunaan Ruang Meeting',
          'penerima'       => '_HR and Legal Officer_',
          'ttd_sebelum1'   => 'General Affair',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprov(1, 1, $dataWa);

        addLog('Pengajuan Ruang Meeting', 'Penggunaan Ruang Meeting Disetujui oleh GA');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_2'] = 1;
        $this->md_surat_part_two->updateMeeting($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_part_two->getMeetingById($id_sp);
        $kdBa               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->perihal;
        $idPengaju          = $ambilDataPengaju[0]->idPengaju;
        $namaPengaju               = $ambilDataPengaju[0]->nama;

        //Notif ke Wa Pengaju
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => $idPengaju,
          'idPenerima2'   => '',
          'kode'           => $kdBa,
          'perihal'       => $perihal,
          'penerima'       => $namaPengaju,
          'namaSurat'     => 'Penggunaan Ruang Meeting',
          'ttd_sebelum1'   => 'General Affair',
          'ttd_sebelum2'   => 'HR and Legal Officer',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprov(1, 2, $dataWa);
      }
      /** LOG */
      addLog('Pengajuan Penggunaan Ruang Meeting ', 'Penggunaan Ruang Meeting Disetujui HR');
      ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    } else if ($param1 == "sijk_sgm") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1'] = 1;
        $this->md_surat_part_two->updateSgm($id_sp, $data);

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '69',
          'idPenerima2'   => '',
          'namaSurat'     => 'Surat Izin pada Jam Kerja',
          'penerima'       => '_HR and Legal_',
          'ttd_sebelum1'   => 'General Affair',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPbSGM(1, 1, $dataWa);

        addLog('Pengajuan Surat Izin Pada Jam Kerja Disetujui oleh GA', 'Permintaan Surat Izin Pada Jam Kerja Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {


        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_2'] = 1;
        $this->md_surat_part_two->updateSgm($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_part_two->getAllById($id_sp);
        $idPengaju          = $ambilDataPengaju[0]->idPengaju;
        $alasan              = $ambilDataPengaju[0]->alasan;
        $link_file          = $ambilDataPengaju[0]->lampiran;
        $tglIzin            = $ambilDataPengaju[0]->tgl_awal;
        $jamMulai            = $ambilDataPengaju[0]->jam_mulai;
        $jamAkhir            = $ambilDataPengaju[0]->jam_akhir;
        $dataTgl            = date('H:i:s', strtotime('now'));
        $ket                = "Izin pada jam kerja Pukul " . $jamMulai . " sampai " . $jamAkhir;
        //cek sudah absen atau belum
        $cek = $this->md_absensi->getAbsenIzinById($idPengaju, $tglIzin);
        if ($cek) {
          //Update data Tabel Absen
          //$data2['waktu_absen'] = $dataTgl;
          $data2['type_absen']  = 'izin';
          $data2['keterangan']  =  $ket;
          $data2['approval']    = 'tolak';
          $data2['tanpa_tunjangan'] = 1;
          //$data2['data_created']    = 1;
          $data2['status_absen']    = 'izin';
          $data2['file_pendukung']  = $link_file;
          $this->md_absensi->updateByWhereToday($data2, ['absensi.pengguna_id' => $idPengaju]);
        } else {
          //Input data ke Tabel Absen
          $data2['pengguna_id'] = $idPengaju;
          $data2['waktu_absen'] = $dataTgl;
          $data2['type_absen']  = 'izin';
          $data2['keterangan']  =  $ket;
          $data2['approval']    = 'tolak';
          $data2['tanpa_tunjangan'] = 1;
          $data2['data_created']    = $tglIzin;
          $data2['status_absen']    = 'izin';
          $data2['file_pendukung']  = $link_file;
          $this->md_absensi->add($data2);
        }

        //send notif wa

        //Notif ke Wa Pengaju
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '',
          'idPenerima2'   => '',
          'namaSurat'     => 'Surat Izin Pada Jam Kerja',
          //'penerima' 	    => '_General Manager_',
          'ttd_sebelum1'   => 'General Affair',
          'ttd_sebelum2'   => 'HR and Legal',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPbSGM(1, 2, $dataWa);

        /** LOG */
        addLog('Pengajuan Surat Izin Pada Jam Kerja Disetujui oleh HR ', 'Permintaan Surat Izin Pada Jam Kerja Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "simp_sgm") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1'] = 1;
        $this->md_surat_part_two->updateSgm($id_sp, $data);

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '69',
          'idPenerima2'   => '',
          'namaSurat'     => 'Surat Izin Meninggalkan Pekerjaan',
          'penerima'       => '_HR and Legal_',
          'ttd_sebelum1'   => 'General Affair',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPbSGM(1, 1, $dataWa);

        addLog('Pengajuan Surat Izin Meninggalkan Pekerjaan Disetujui oleh GA', 'Permintaan Surat Izin Meninggalkan Pekerjaan Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_2'] = 1;
        $this->md_surat_part_two->updateSgm($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_part_two->getAllById($id_sp);
        $idPengaju          = $ambilDataPengaju[0]->idPengaju;
        $jenisIzin          = $ambilDataPengaju[0]->jenis_izin;
        $alasan              = $ambilDataPengaju[0]->alasan;
        $tgl_awal            = $ambilDataPengaju[0]->tgl_awal;
        $tgl_akhir          = $ambilDataPengaju[0]->tgl_akhir;
        $link_file          = $ambilDataPengaju[0]->lampiran;

        //====================================
        //=========  INPUT Ke Tabel Absen ====
        //====================================     

        $dataTgl = date('H:i:s', strtotime('now'));

        while (strtotime($tgl_awal) <= strtotime($tgl_akhir)) {
          $data_in = [
            'pengguna_id'       => $idPengaju,
            'waktu_absen'       => $dataTgl,
            'type_absen'        => 'izin',
            'keterangan'        => $alasan,
            'approval'          => 'tolak',
            'tanpa_tunjangan'   => 1,
            'data_created'      => $tgl_awal,
            'status_absen'      => $jenisIzin,
            'file_pendukung'    => $link_file
          ];
          $this->md_absensi->add($data_in);
          $tgl_awal = date("Y-m-d", strtotime("+1 day", strtotime($tgl_awal))); //looping tambah 1 date
        }


        //====================================
        //====== End INPUT Ke Tabel Absen ====
        //====================================


        //send notif wa

        //Notif ke Wa Pengaju
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '',
          'idPenerima2'   => '',
          'namaSurat'     => 'Surat Izin Meninggalkan Pekerjaan',
          //'penerima' 	    => '_General Manager_',
          'ttd_sebelum1'   => 'General Affair',
          'ttd_sebelum2'   => 'HR and Legal',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPbSGM(1, 2, $dataWa);

        /** LOG */
        addLog('Pengajuan Surat Izin Meninggalkan Pekerjaan Disetujui oleh HR ', 'Permintaan Surat Izin Meninggalkan Pekerjaan Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "cuti_sgm") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1'] = 1;
        $this->md_surat_part_two->updateSgm($id_sp, $data);

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '69',
          'idPenerima2'   => '',
          'namaSurat'     => 'Cuti Tahunan',
          'penerima'       => '_HR and Legal_',
          'ttd_sebelum1'   => 'General Affair',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPbSGM(1, 1, $dataWa);

        addLog('Pengajuan Cuti Tahunan Disetujui oleh GA', 'Permintaan Cuti Tahunan Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_2'] = 1;
        $this->md_surat_part_two->updateSgm($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_part_two->getAllById($id_sp);
        $idPengaju          = $ambilDataPengaju[0]->idPengaju;
        $jenisIzin          = $ambilDataPengaju[0]->jenis_izin;
        $alasan              = $ambilDataPengaju[0]->alasan;
        $tgl_awal            = $ambilDataPengaju[0]->tgl_awal;
        $tgl_akhir          = $ambilDataPengaju[0]->tgl_akhir;
        $link_file          = $ambilDataPengaju[0]->lampiran;


        //====================================
        //=========  INPUT Ke Tabel Absen ====
        //====================================     

        $dataTgl = date('H:i:s', strtotime('now'));

        while (strtotime($tgl_awal) <= strtotime($tgl_akhir)) {
          $data_in = [
            'pengguna_id'       => $idPengaju,
            'waktu_absen'       => $dataTgl,
            'type_absen'        => 'izin',
            'keterangan'        => $alasan,
            'approval'          => 'tolak',
            'tanpa_tunjangan'   => 1,
            'data_created'      => $tgl_awal,
            'status_absen'      => $jenisIzin,
            'file_pendukung'    => $link_file
          ];
          $this->md_absensi->add($data_in);
          $tgl_awal = date("Y-m-d", strtotime("+1 day", strtotime($tgl_awal))); //looping tambah 1 date
        }


        //====================================
        //====== End INPUT Ke Tabel Absen ====
        //====================================


        //send notif wa

        //Notif ke Wa Pengaju
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '',
          'idPenerima2'   => '',
          'namaSurat'     => 'Cuti Tahunan',
          //'penerima' 	    => '_General Manager_',
          'ttd_sebelum1'   => 'General Affair',
          'ttd_sebelum2'   => 'HR and Legal',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPbSGM(1, 2, $dataWa);


        // =========================================================================
        // SEND NOTIF WA GROUP (VISI YOSINDO MEDICAL)
        // =========================================================================
        $this->notifWaGroupCuti($id_sp, 'Visi Yosindo Medical');
                        // =========================================================================

        /** LOG */
        addLog('Pengajuan Cuti Tahunan Disetujui oleh HR ', 'Permintaan Cuti Tahunan Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    }
  }


  public function ttd_tolak($param1 = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');

    if ($param1 == "sijk") {
      if ($param2 == "ttd_1") {


        $id_sp = $this->input->post('id');
        $data['status'] = 6;
        $data['ttd_1'] = 2;
        $this->md_surat_part_two->updateSijk($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Surat Izin Pada Jam Kerja',
          'id'       => $id_sp,
          'idPenolak'     => 58,
          'namaPenolak'   => '*General Affair*'
        ];
        $this->notifWaRejectPb($dataWa);
        /** LOG */
        addLog('Pengajuan Surat Izin Pada Jam Kerja Ditolak oleh GA', 'Permintaan Surat Izin Pada Jam Kerja Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 7;
        $data['ttd_2'] = 2;
        $this->md_surat_part_two->updateSijk($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Surat Izin Pada Jam Kerja',
          'id'       => $id_sp,
          'idPenolak'     => 69,
          'namaPenolak'   => '*HR and Legal Officer*'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Pengajuan Surat Izin Pada Jam Kerja Ditolak oleh HR', 'Permintaan Surat Izin Pada Jam Kerja Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_3") {

        $id_sp = $this->input->post('id');
        $data['status'] = 8;
        $data['ttd_3'] = 2;
        $this->md_surat_part_two->updateSijk($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Surat Izin Pada Jam Kerja',
          'id'       => $id_sp,
          'idPenolak'     => 33,
          'namaPenolak'   => '*General Manager*'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Pengajuan Surat Izin Pada Jam Kerja Ditolak oleh HR', 'Permintaan Surat Izin Pada Jam Kerja Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "simp") {
      if ($param2 == "ttd_1") {


        $id_sp = $this->input->post('id');
        $data['status'] = 6;
        $data['ttd_1'] = 2;
        $this->md_surat_part_two->updateSijk($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Surat Izin Meninggalkan Pekerjaan',
          'id'       => $id_sp,
          'idPenolak'     => 58,
          'namaPenolak'   => '*General Affair*'
        ];
        $this->notifWaRejectPb($dataWa);
        /** LOG */
        addLog('Pengajuan Surat Izin Meninggalkan Pekerjaan Ditolak oleh GA', 'Permintaan Surat Izin Meninggalkan Pekerjaan Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 7;
        $data['ttd_2'] = 2;
        $this->md_surat_part_two->updateSijk($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Surat Izin Pada Jam Kerja',
          'id'       => $id_sp,
          'idPenolak'     => 69,
          'namaPenolak'   => '*HR and Legal Officer*'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Pengajuan Surat Izin Meninggalkan Pekerjaan oleh HR', 'Permintaan Surat Izin Meninggalkan Pekerjaan Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_3") {

        $id_sp = $this->input->post('id');
        $data['status'] = 8;
        $data['ttd_3'] = 2;
        $this->md_surat_part_two->updateSijk($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Surat Izin Meninggalkan Pekerjaan',
          'id'       => $id_sp,
          'idPenolak'     => 33,
          'namaPenolak'   => '*General Manager*'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Pengajuan Surat Izin Meninggalkan Pekerjaan Ditolak oleh HR', 'Permintaan Surat Izin Meninggalkan Pekerjaan Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "cuti") {
      if ($param2 == "ttd_1") {


        $id_sp = $this->input->post('id');

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Cuti Tahunan',
          'id'       => $id_sp,
          'idPenolak'     => 58,
          'namaPenolak'   => '*General Affair*'
        ];
        $this->notifWaRejectCuti($dataWa);

        // Hapus pengajuan cuti secara otomatis dari database saat ditolak
        $this->db->where('id', $id_sp)->delete('surat_cuti_tahunan');

        /** LOG */
        addLog('Pengajuan Cuti Tahunan Ditolak oleh GA', 'Permintaan Cuti Tahunan Ditolak dan Otomatis Dihapus');
        ajaxReturnDie('success', 'Pengajuan Cuti Ditolak dan Berhasil Dihapus', TRUE);
      } elseif ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Cuti Tahunan',
          'id'       => $id_sp,
          'idPenolak'     => 69,
          'namaPenolak'   => '*HR and Legal Officer*'
        ];
        $this->notifWaRejectCuti($dataWa);

        // Hapus pengajuan cuti secara otomatis dari database saat ditolak
        $this->db->where('id', $id_sp)->delete('surat_cuti_tahunan');

        /** LOG */
        addLog('Pengajuan Cuti Tahunan oleh HR', 'Permintaan Cuti Tahunan Ditolak dan Otomatis Dihapus');
        ajaxReturnDie('success', 'Pengajuan Cuti Ditolak dan Berhasil Dihapus', TRUE);
      } elseif ($param2 == "ttd_3") {

        $id_sp = $this->input->post('id');

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Cuti Tahunan',
          'id'       => $id_sp,
          'idPenolak'     => 33,
          'namaPenolak'   => '*General Manager*'
        ];
        $this->notifWaRejectCuti($dataWa);

        // Hapus pengajuan cuti secara otomatis dari database saat ditolak
        $this->db->where('id', $id_sp)->delete('surat_cuti_tahunan');

        /** LOG */
        addLog('Pengajuan Cuti Tahunan Ditolak oleh HR', 'Permintaan Cuti Tahunan Ditolak dan Otomatis Dihapus');
        ajaxReturnDie('success', 'Pengajuan Cuti Ditolak dan Berhasil Dihapus', TRUE);
      }
    } else if ($param1 == "ba") {
      if ($param2 == "ttd_diketahui") {


        $id_sp = $this->input->post('id');
        $data['status'] = 6;
        $data['ttd_diketahui'] = 2;
        $this->md_surat_part_two->updateBeritaAcara($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_part_two->getBeritaAcaraById($id_sp);
        $id_diketahui        = $ambilDataPengaju[0]->id_diketahui;
        $nama_diketahui     = $ambilDataPengaju[0]->nama_diketahui;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Berita Acara',
          'id'       => $id_sp,
          'idPenolak'     => $id_diketahui,
          'namaPenolak'   => $nama_diketahui
        ];
        $this->notifWaRejectBa($dataWa);
        /** LOG */
        addLog('Pengajuan Berita Acara Ditolak', 'Permintaan Berita Acara Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_disetujui") {

        $id_sp = $this->input->post('id');
        $data['status'] = 7;
        $data['ttd_disetujui'] = 2;
        $this->md_surat_part_two->updateBeritaAcara($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_part_two->getBeritaAcaraById($id_sp);
        $id_disetujui       = $ambilDataPengaju[0]->id_disetujui;
        $nama_disetujui     = $ambilDataPengaju[0]->nama_disetujui;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Berita Acara',
          'id'       => $id_sp,
          'idPenolak'     => $id_disetujui,
          'namaPenolak'   => $nama_disetujui
        ];
        $this->notifWaRejectBa($dataWa);

        /** LOG */
        addLog('Pengajuan Berita Acara Ditolak', 'Permintaan Berita Acara Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_dir") {

        $id_sp = $this->input->post('id');
        $data['status'] = 7;
        $data['ttd_dir'] = 2;
        $this->md_surat_part_two->updateBeritaAcara($id_sp, $data);

        //$ambilDataPengaju 	= $this->md_surat_part_two->getBeritaAcaraById($id_sp);
        //$id_disetujui       = $ambilDataPengaju[0]->id_disetujui;
        //$nama_disetujui     = $ambilDataPengaju[0]->nama_disetujui;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Berita Acara',
          'id'       => $id_sp,
          'idPenolak'     => 54,
          'namaPenolak'   => 'Director'
        ];
        $this->notifWaRejectBa($dataWa);

        /** LOG */
        addLog('Pengajuan Berita Acara Ditolak', 'Permintaan Berita Acara Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "paklaring") {
      if ($param2 == "ttd_1") {


        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_1'] = 2;
        $this->md_surat_part_two->updatePaklaring($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_part_two->getPaklaringById($id_sp);
        $idPengaju        = $ambilDataPengaju[0]->idPengaju;
        $nama_diketahui     = $ambilDataPengaju[0]->nama_diketahui;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Pengalaman Kerja',
          'id'       => $id_sp,
          'idPenolak'     => '33',
          'namaPenolak'   => '_HR and Legal_'
        ];
        $this->notifWaRejectBa($dataWa);
        /** LOG */
        addLog('Pengajuan Pengalaman Kerja Ditolak', 'Permintaan Pengalaman Kerja Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "meetingroom") {
      if ($param2 == "ttd_1") {


        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd_1'] = 2;
        $this->md_surat_part_two->updateMeeting($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Penggunaan Ruang Meeting',
          'id'       => $id_sp,
          'idPenolak'     => 58,
          'namaPenolak'   => '*General Affair*'
        ];
        $this->notifWaRejectMR($dataWa);
        /** LOG */
        addLog('Penggunaan Ruang Meeting', 'Penggunaan Ruang Meeting Ditolak oleh GA');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 4;
        $data['ttd_2'] = 2;
        $this->md_surat_part_two->updatemeeting($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Penggunaan Ruang Meeting',
          'id'       => $id_sp,
          'idPenolak'     => 69,
          'namaPenolak'   => '*HR and Legal Officer*'
        ];
        $this->notifWaRejectMR($dataWa);

        /** LOG */
        addLog('Penggunaan Ruang Meeting', 'Penggunaan Ruang Meeting Ditolak oleh HR');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "sijk_sgm") {
      if ($param2 == "ttd_1") {


        $id_sp = $this->input->post('id');
        $data['status'] = 6;
        $data['ttd_1'] = 2;
        $this->md_surat_part_two->updateSgm($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Surat Izin Pada Jam Kerja',
          'id'       => $id_sp,
          'idPenolak'     => 58,
          'namaPenolak'   => '*General Affair*'
        ];
        $this->notifWaRejectPbSGM($dataWa);
        /** LOG */
        addLog('Pengajuan Surat Izin Pada Jam Kerja Ditolak oleh GA', 'Permintaan Surat Izin Pada Jam Kerja Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 7;
        $data['ttd_2'] = 2;
        $this->md_surat_part_two->updateSgm($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Surat Izin Pada Jam Kerja',
          'id'       => $id_sp,
          'idPenolak'     => 69,
          'namaPenolak'   => '*HR and Legal*'
        ];
        $this->notifWaRejectPbSGM($dataWa);

        /** LOG */
        addLog('Pengajuan Surat Izin Pada Jam Kerja Ditolak oleh HR', 'Permintaan Surat Izin Pada Jam Kerja Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "simp_sgm") {
      if ($param2 == "ttd_1") {


        $id_sp = $this->input->post('id');
        $data['status'] = 6;
        $data['ttd_1'] = 2;
        $this->md_surat_part_two->updateSgm($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Surat Izin Meninggalkan Pekerjaan',
          'id'       => $id_sp,
          'idPenolak'     => 58,
          'namaPenolak'   => '*General Affair*'
        ];
        $this->notifWaRejectPbSGM($dataWa);
        /** LOG */
        addLog('Pengajuan Surat Izin Meninggalkan Pekerjaan Ditolak oleh GA', 'Permintaan Surat Izin Meninggalkan Pekerjaan Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 7;
        $data['ttd_2'] = 2;
        $this->md_surat_part_two->updateSgm($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Surat Izin Meninggalkan Pekerjaan',
          'id'       => $id_sp,
          'idPenolak'     => 69,
          'namaPenolak'   => '*HR and Legal*'
        ];
        $this->notifWaRejectPbSGM($dataWa);

        /** LOG */
        addLog('Pengajuan Surat Izin Meninggalkan Pekerjaan Ditolak oleh HR', 'Permintaan Surat Izin Meninggalkan Pekerjaan Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "cuti_sgm") {
      if ($param2 == "ttd_1") {


        $id_sp = $this->input->post('id');
        $data['status'] = 6;
        $data['ttd_1'] = 2;
        $this->md_surat_part_two->updateSgm($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Cuti Tahunan',
          'id'       => $id_sp,
          'idPenolak'     => 58,
          'namaPenolak'   => '*General Affair*'
        ];
        $this->notifWaRejectPbSGM($dataWa);
        /** LOG */
        addLog('Pengajuan Cuti Tahunan Ditolak oleh GA', 'Permintaan Cuti Tahunan Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 7;
        $data['ttd_2'] = 2;
        $this->md_surat_part_two->updateSgm($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Cuti Tahunan',
          'id'       => $id_sp,
          'idPenolak'     => 69,
          'namaPenolak'   => '*HR and Legal*'
        ];
        $this->notifWaRejectPbSGM($dataWa);

        /** LOG */
        addLog('Pengajuan Cuti Tahunan oleh HR', 'Permintaan Cuti Tahunan Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    }
  }


  //DELETE
  public function delete($id)
  {
    grantAccessFor('all');

    //$status = $this->input->post('status', TRUE);

    $data = [
      'status' => "5",
    ];


    $this->md_surat_part_two->updateSijk($id, $data);

    addLog('Menghapus Surat Izin', 'Menghapus Surat Izin  ');
    ajaxReturnDie('success', 'Surat Izin berhasil dihapus', 'reload_table');
  }

  public function deleteCuti($id)
  {
    grantAccessFor('all');

    if (strlen($id) > 10 && !is_numeric($id)) {
      $decId = decrypt($id);
      if ($decId) {
        $id = $decId;
      }
    }

    $cuti = $this->md_surat_part_two->getCutiById($id);
    if (!empty($cuti)) {
      $idPengaju = !empty($cuti[0]->idPengaju) ? $cuti[0]->idPengaju : (!empty($cuti[0]->id_pengaju) ? $cuti[0]->id_pengaju : null);
      if (!empty($idPengaju) && !empty($cuti[0]->tgl_awal) && !empty($cuti[0]->tgl_akhir)) {
        $this->db->where('pengguna_id', $idPengaju)
          ->where('type_absen', 'izin')
          ->where('data_created >=', date('Y-m-d 00:00:00', strtotime($cuti[0]->tgl_awal)))
          ->where('data_created <=', date('Y-m-d 23:59:59', strtotime($cuti[0]->tgl_akhir)))
          ->delete('absensi');
      }
    }

    $data = [
      'status' => "5",
    ];

    $this->md_surat_part_two->updateCuti($id, $data);

    addLog('Menghapus Cuti', 'Menghapus Cuti Tahunan ');
    ajaxReturnDie('success', 'Cuti berhasil dihapus', 'reload_table');
  }

  public function batalCuti($id = null)
  {
    grantAccessFor('all');

    if (empty($id)) {
      $id = $this->input->post('id', TRUE);
    }

    if (empty($id)) {
      ajaxReturnDie('error', 'ID Pengajuan Cuti tidak valid');
    }

    if (strlen($id) > 10 && !is_numeric($id)) {
      $decId = decrypt($id);
      if ($decId) {
        $id = $decId;
      }
    }

    $cuti = $this->md_surat_part_two->getCutiById($id);
    if (empty($cuti)) {
      ajaxReturnDie('error', 'Data pengajuan cuti tidak ditemukan');
    }

    $idPengaju = !empty($cuti[0]->idPengaju) ? $cuti[0]->idPengaju : (!empty($cuti[0]->id_pengaju) ? $cuti[0]->id_pengaju : null);
    $currentStatus = $cuti[0]->status;

    if (sessPenggunaId() != $idPengaju && !isAdmin()) {
      ajaxReturnDie('error', 'Anda tidak memiliki hak akses untuk membatalkan pengajuan cuti ini');
    }

    if ($currentStatus == '3' && !isAdmin()) {
      ajaxReturnDie('error', 'Pengajuan cuti yang sudah disetujui final oleh General Manager tidak dapat dibatalkan');
    }

    if ($currentStatus == '5') {
      ajaxReturnDie('error', 'Pengajuan cuti ini sudah dibatalkan sebelumnya');
    }

    // Hapus data absensi yang sudah terlanjur di-insert jika cuti ini sebelumnya sudah disetujui
    if (!empty($idPengaju) && !empty($cuti[0]->tgl_awal) && !empty($cuti[0]->tgl_akhir)) {
      $this->db->where('pengguna_id', $idPengaju)
        ->where('type_absen', 'izin')
        ->where('data_created >=', date('Y-m-d 00:00:00', strtotime($cuti[0]->tgl_awal)))
        ->where('data_created <=', date('Y-m-d 23:59:59', strtotime($cuti[0]->tgl_akhir)))
        ->delete('absensi');
    }

    $data = [
      'status' => '5',
    ];

    $this->md_surat_part_two->updateCuti($id, $data);

    addLog('Batal Pengajuan Cuti', 'Membatalkan pengajuan cuti tahunan: ' . $cuti[0]->kode_cuti);
    ajaxReturnDie('success', 'Pengajuan cuti tahunan berhasil dibatalkan', 'reload_table');
  }

  public function batalCutiSgm($id = null)
  {
    grantAccessFor('all');

    if (empty($id)) {
      $id = $this->input->post('id', TRUE);
    }

    if (empty($id)) {
      ajaxReturnDie('error', 'ID Pengajuan Cuti SGM tidak valid');
    }

    if (strlen($id) > 10 && !is_numeric($id)) {
      $decId = decrypt($id);
      if ($decId) {
        $id = $decId;
      }
    }

    $cuti = $this->md_surat_part_two->getAllById($id);
    if (empty($cuti)) {
      ajaxReturnDie('error', 'Data pengajuan cuti SGM tidak ditemukan');
    }

    $idPengaju = !empty($cuti[0]->idPengaju) ? $cuti[0]->idPengaju : (!empty($cuti[0]->id_pengaju) ? $cuti[0]->id_pengaju : null);
    $currentStatus = $cuti[0]->status;

    if (sessPenggunaId() != $idPengaju && !isAdmin()) {
      ajaxReturnDie('error', 'Anda tidak memiliki hak akses untuk membatalkan pengajuan cuti ini');
    }

    if ($currentStatus == '3' && !isAdmin()) {
      ajaxReturnDie('error', 'Pengajuan cuti yang sudah disetujui final tidak dapat dibatalkan');
    }

    if ($currentStatus == '5') {
      ajaxReturnDie('error', 'Pengajuan cuti ini sudah dibatalkan sebelumnya');
    }

    // Hapus data absensi yang sudah terlanjur di-insert jika cuti ini sebelumnya sudah disetujui
    if (!empty($idPengaju) && !empty($cuti[0]->tgl_awal) && !empty($cuti[0]->tgl_akhir)) {
      $this->db->where('pengguna_id', $idPengaju)
        ->where('type_absen', 'izin')
        ->where('data_created >=', date('Y-m-d 00:00:00', strtotime($cuti[0]->tgl_awal)))
        ->where('data_created <=', date('Y-m-d 23:59:59', strtotime($cuti[0]->tgl_akhir)))
        ->delete('absensi');
    }

    $data = [
      'status' => '5',
    ];

    $this->md_surat_part_two->updateSgm($id, $data);

    addLog('Batal Pengajuan Cuti SGM', 'Membatalkan pengajuan cuti SGM: ' . $cuti[0]->kode);
    ajaxReturnDie('success', 'Pengajuan cuti SGM berhasil dibatalkan', 'reload_table');
  }

  public function deleteBa($id)
  {
    grantAccessFor('all');

    $data = [
      'status' => "5",
    ];

    $this->md_surat_part_two->updateBeritaAcara($id, $data);

    addLog('Menghapus Berita Acara', 'Menghapus Berita Acara ID: ' . $id);
    ajaxReturnDie('success', 'Berita Acara berhasil dihapus', 'reload_table');
  }


    

    

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Calendar Cuti API ------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------

  /**
   * Get calendar events for Cuti Tahunan
   * Endpoint: surat_part_two/getCalendarCuti
   * @return JSON
   */
  public function getCalendarCuti()
  {
    grantAccessFor('all');

    try {
      $year = $this->input->get('year') ? intval($this->input->get('year')) : intval(date('Y'));
      $month = $this->input->get('month') ? intval($this->input->get('month')) : 0;

      $events = $this->md_surat_part_two->getCutiForCalendar($year, $month);

      header('Content-Type: application/json');
      echo json_encode($events);
    } catch (Exception $e) {
      header('Content-Type: application/json');
      http_response_code(500);
      echo json_encode(array('error' => $e->getMessage()));
    }
  }

  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  // pagination -------------------------------------------------------------------------------------------------------------------------------------------------------
  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------

  public function pagination($param = "", $param2 = "")
  {
    grantAccessFor('all');
    if ($param == 'permintaan_izin_jam_kerja') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getAllSIJKbyID(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_fpp  = '<a href="surat_part_two/show/detail/izin_jam_kerja/' . $row->idGc . '">' . $row->kode_ijk . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = $row->jam_mulai;
        $akhir = $row->jam_akhir;

        $pukul = $mulai . ' s/d ' . $akhir;

        $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    ' . ($row->status == '17' ? '<a href="surat/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                    ' . ($row->status == '17' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="surat_part_two/delete"><i class="bx bx-trash"></i></button>' : '') . '
                </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->alasan;
        $th[] = $pukul;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_izin_jam_kerja') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getAllSIJK();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_fpp  = '<a href="surat_part_two/show/detail/izin_jam_kerja/' . $row->idGc . '">' . $row->kode_ijk . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = $row->jam_mulai;
        $akhir = $row->jam_akhir;

        $pukul = $mulai . ' s/d ' . $akhir;

        $li_btn   = '
            <div class="btn-group" role="group" aria-label="First group">
                ' . ($row->status == '17' ? '<a href="surat/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                ' . ($row->status == '17' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="surat_part_two/delete"><i class="bx bx-trash"></i></button>' : '') . '
                ' . (isAdmin() ? '<a href="javascript:void(0)" onclick="openAdminEditModal(\'sijk\', \'' . encrypt($row->idGc) . '\')" class="btn btn-sm btn-warning" title="Edit Data (Admin)"><i class="fas fa-pencil-alt text-dark"></i></a>' : '') . '
            </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->alasan;
        $th[] = $pukul;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'permintaan_izin_meninggalkan') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getAllSIMPbyID(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_fpp  = '<a href="surat_part_two/show/detail/izin_meninggalkan/' . $row->idGc . '">' . $row->kode_ijk . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = date('d-M-Y', strtotime($row->tgl_awal));
        $akhir = date('d-M-Y', strtotime($row->tgl_akhir));

        $pukul = $mulai . ' s/d ' . $akhir;

        $li_btn   = '
            <div class="btn-group" role="group" aria-label="First group">
                ' . ($row->status == '17' ? '<a href="surat/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                ' . ($row->status == '17' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="surat_part_two/delete"><i class="bx bx-trash"></i></button>' : '') . '
            </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = $row->alasan;
        $th[] = $pukul;
        $th[] = $row->total;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_izin_meninggalkan') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getAllSIMP();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_fpp  = '<a href="surat_part_two/show/detail/izin_meninggalkan/' . $row->idGc . '">' . $row->kode_ijk . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = date('d-M-Y', strtotime($row->tgl_awal));
        $akhir = date('d-M-Y', strtotime($row->tgl_akhir));

        $pukul = $mulai . ' s/d ' . $akhir;

        $li_btn   = '
            <div class="btn-group" role="group" aria-label="First group">
                ' . ($row->status == '17' ? '<a href="surat/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                ' . ($row->status == '17' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="surat_part_two/delete"><i class="bx bx-trash"></i></button>' : '') . '
                ' . (isAdmin() ? '<a href="javascript:void(0)" onclick="openAdminEditModal(\'simp\', \'' . encrypt($row->idGc) . '\')" class="btn btn-sm btn-warning" title="Edit Data (Admin)"><i class="fas fa-pencil-alt text-dark"></i></a>' : '') . '
            </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = $row->alasan;
        $th[] = $pukul;
        $th[] = $row->total;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'permintaan_cuti') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getAllCUTIbyID(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_fpp  = '<a href="surat_part_two/show/detail/cuti/' . $row->idGc . '">' . $row->kode_cuti . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = date('d-M-Y', strtotime($row->tgl_awal));
        $akhir = date('d-M-Y', strtotime($row->tgl_akhir));

        $pukul = $mulai . ' s/d ' . $akhir;

        $li_btn   = '
            <div class="btn-group" role="group" aria-label="First group">
                ' . ($row->status == '17' ? '<a href="surat/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                ' . ($row->status == '17' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="surat_part_two/deleteCuti"><i class="bx bx-trash"></i></button>' : '') . '
            </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = $row->alasan;
        $th[] = $pukul;
        $th[] = $row->total;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_cuti') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getAllCUTI();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_fpp  = '<a href="surat_part_two/show/detail/cuti/' . $row->idGc . '">' . $row->kode_cuti . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = date('d-M-Y', strtotime($row->tgl_awal));
        $akhir = date('d-M-Y', strtotime($row->tgl_akhir));

        $pukul = $mulai . ' s/d ' . $akhir;

        $btnBatal = '';
        if ($row->status != '3' && $row->status != '5') {
          $btnBatal = '<button type="button" class="btn btn-sm btn-danger btn-batal-cuti" title="Batalkan Pengajuan Cuti" data-id="' . $row->idGc . '" data-kode="' . $row->kode_cuti . '"><i class="fas fa-ban"></i> Batal</button>';
        }

        $li_btn   = '
            <div class="btn-group" role="group" aria-label="Aksi">
                <a href="surat_part_two/show/detail/cuti/' . $row->idGc . '" class="btn btn-sm btn-info" title="Lihat Detail"><i class="fas fa-eye"></i> Detail</a> &nbsp;
                ' . $btnBatal . '
            </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = $row->alasan;
        $th[] = $pukul;
        $th[] = $row->total;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_cuti') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getAllCUTI();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_fpp  = '<a href="surat_part_two/show/detail/cuti/' . $row->idGc . '">' . $row->kode_cuti . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = date('d-M-Y', strtotime($row->tgl_awal));
        $akhir = date('d-M-Y', strtotime($row->tgl_akhir));

        $pukul = $mulai . ' s/d ' . $akhir;

        $btnBatal = '';
        if ($row->status != '3' && $row->status != '5' && (isAdmin() || (isset($row->idPengaju) && $row->idPengaju == sessPenggunaId()))) {
          $btnBatal = '<button type="button" class="btn btn-sm btn-danger btn-batal-cuti" title="Batalkan Pengajuan Cuti" data-id="' . $row->idGc . '" data-kode="' . $row->kode_cuti . '"><i class="fas fa-ban"></i> Batal</button>';
        }

        $li_btn   = '
            <div class="btn-group" role="group" aria-label="Aksi">
                <a href="surat_part_two/show/detail/cuti/' . $row->idGc . '" class="btn btn-sm btn-info" title="Lihat Detail"><i class="fas fa-eye"></i> Detail</a> &nbsp;
                ' . (isAdmin() ? '<a href="javascript:void(0)" onclick="openAdminEditModal(\'cuti\', \'' . encrypt($row->idGc) . '\')" class="btn btn-sm btn-warning" title="Edit Data (Admin)"><i class="fas fa-pencil-alt text-dark"></i></a> &nbsp;' : '') . '
                ' . $btnBatal . '
            </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = $row->alasan;
        $th[] = $pukul;
        $th[] = $row->total;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_ba') {
      if (sessPenggunaId() == '1' || sessPenggunaId() == '23' || sessPenggunaId() == '33' || isGa()) {
        $dt     = $this->md_surat_part_two->getAllBA();
      } elseif (sessPenggunaId() == '54') {
        $dt     = $this->md_surat_part_two->getBADirbySet();
      } else {
        $dt     = $this->md_surat_part_two->getBAbySet(sessPenggunaId());
      }
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_ba  = '<a href="surat_part_two/show/detail/ba/' . $row->idGc . '" class="font-weight-bold text-primary" title="Buka Detail Berita Acara">' . $row->kode_ba . '</a>';

        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-success px-2 py-1">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-info px-2 py-1">Disetujui oleh ' . $row->nama_diketahui . '</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-info px-2 py-1">Disetujui oleh ' . $row->nama_disetujui . '</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-info px-2 py-1">Disetujui oleh Director</span>';
        } else {
          $stat_surat = '<span class="badge badge-danger px-2 py-1">Ditolak</span>';
        }

        $tgl_aju = date('d-M-Y', strtotime($row->tanggal));

        if ($row->id_diketahui == "58") {
          $diketahui = "Amtisari Destiani Eka Putri";
        } else {
          $diketahui = $row->nama_diketahui;
        }

        if ($row->id_disetujui == "58") {
          $disetujui = "Amtisari Destiani Eka Putri";
        } else {
          $disetujui = $row->nama_disetujui;
        }

        $li_btn   = '
            <div class="btn-group btn-group-sm" role="group">
                <a href="surat_part_two/show/detail/ba/' . $row->idGc . '" class="btn btn-sm btn-primary" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                ' . ($row->status == '17' ? '<a href="surat/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-pencil-alt"></i></a>' : '') . '
                ' . ($row->status == '17' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="surat_part_two/deleteCuti"><i class="fas fa-trash"></i></button>' : '') . '
                ' . (isAdmin() ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data (Admin)" data-id="' . $row->idGc . '" data-object="surat_part_two/deleteBa"><i class="fas fa-trash"></i></button>' : '') . '
            </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_ba;
        $th[] = '<span class="font-weight-bold text-dark">' . $row->pengaju . '</span>';
        $th[] = '<span class="text-secondary">' . $row->jabatan . '</span>';
        $th[] = '<span class="text-nowrap font-weight-500">' . $tgl_aju . '</span>';
        $th[] = $diketahui ? $diketahui : '-';
        $th[] = $disetujui ? $disetujui : '-';
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'permintaan_ba') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getBAbyID(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_ba  = '<a href="surat_part_two/show/detail/ba/' . $row->idGc . '">' . $row->kode_ba . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh ' . $row->nama_diketahui . '</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh ' . $row->nama_disetujui . '</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $tgl_aju = date('d-M-Y', strtotime($row->tanggal));

        if ($row->id_diketahui == 58) {
          $diketahui = "Amtisari Destiani Eka Putri";
        } else {
          $diketahui = $row->nama_diketahui;
        }

        if ($row->id_disetujui == 58) {
          $disetujui = "Amtisari Destiani Eka Putri";
        } else {
          $disetujui = $row->nama_disetujui;
        }

        $li_btn   = '
            <div class="btn-group" role="group" aria-label="First group">
                <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="surat_part_two/deleteBa"><i class="bx bx-trash"></i></button>
            </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_ba;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = $tgl_aju;
        $th[] = $diketahui;
        $th[] = $disetujui;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'paklaring') {
      $dt     = $this->md_surat_part_two->getAllPaklaring();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode  = '<a href="surat_part_two/show/detail/paklaring/' . $row->idGc . '">' . $row->kode . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $tgl_masuk = date('d-M-Y', strtotime($row->tgl_masuk));
        $tgl_keluar = date('d-M-Y', strtotime($row->tgl_keluar));


        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->pegawai;
        $th[] = $row->jabatan;
        $th[] = $tgl_masuk;
        $th[] = $tgl_keluar;
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'meetingroom') {

      if (sessPenggunaId() == '1' || sessPenggunaId() == '23' || sessPenggunaId() == '33' || sessPenggunaId() == '58' || sessPenggunaId() == '69' || sessPenggunaId() == '744') {
        $dt     = $this->md_surat_part_two->getAllMeeting();
      } else {
        $dt     = $this->md_surat_part_two->getAllMeetingBy(sessPenggunaId());
      }

      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode  = '<a href="surat_part_two/show/detail/meetingroom/' . $row->idGc . '">' . $row->kode . '</a>';
        $aksi = '';
        if (isAdmin()) {
          $aksi = '<a href="javascript:void(0)" onclick="openAdminEditModal(\'meetingroom\', \'' . encrypt($row->idGc) . '\')" class="btn btn-sm btn-warning" title="Edit Data (Admin)"><i class="fas fa-pencil-alt text-dark"></i></a>';
        }
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal Officer</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $tgl_pengajuan = date('d-M-Y', strtotime($row->tgl_pengajuan));


        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->perihal;
        $th[] = $tgl_pengajuan;
        $th[] = $row->nama;
        $th[] = $stat_surat;
        $th[] = $aksi;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'permintaan_izin_jam_kerja_sgm') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getAllSIJKSGMbyID(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_fpp  = '<a href="surat_part_two/show/detail/izin_jam_kerja_sgm/' . $row->idGc . '">' . $row->kode . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = $row->jam_mulai;
        $akhir = $row->jam_akhir;

        $pukul = $mulai . ' s/d ' . $akhir;

        $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    ' . ($row->status == '17' ? '<a href="surat/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                    ' . ($row->status == '17' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="surat_part_two/delete"><i class="bx bx-trash"></i></button>' : '') . '
                </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tgl_awal));
        $th[] = $row->alasan;
        $th[] = $pukul;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_izin_jam_kerja_sgm') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getAllSIJKSGM();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_fpp  = '<a href="surat_part_two/show/detail/izin_jam_kerja_sgm/' . $row->idGc . '">' . $row->kode . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = $row->jam_mulai;
        $akhir = $row->jam_akhir;

        $pukul = $mulai . ' s/d ' . $akhir;

        $li_btn   = '
            <div class="btn-group" role="group" aria-label="First group">
                ' . ($row->status == '17' ? '<a href="surat/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                ' . ($row->status == '17' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="surat_part_two/delete"><i class="bx bx-trash"></i></button>' : '') . '
                ' . (isAdmin() ? '<a href="javascript:void(0)" onclick="openAdminEditModal(\'sijk_sgm\', \'' . encrypt($row->idGc) . '\')" class="btn btn-sm btn-warning" title="Edit Data (Admin)"><i class="fas fa-pencil-alt text-dark"></i></a>' : '') . '
            </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tgl_awal));
        $th[] = $row->alasan;
        $th[] = $pukul;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'permintaan_izin_meninggalkan_sgm') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getAllSimpSGMbyID(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_fpp  = '<a href="surat_part_two/show/detail/izin_meninggalkan_sgm/' . $row->idGc . '">' . $row->kode . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = date('d-M-Y', strtotime($row->tgl_awal));
        $akhir = date('d-M-Y', strtotime($row->tgl_akhir));

        $pukul = $mulai . ' s/d ' . $akhir;

        $li_btn   = '
            <div class="btn-group" role="group" aria-label="First group">
                ' . ($row->status == '17' ? '<a href="surat/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                ' . ($row->status == '17' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="surat_part_two/delete"><i class="bx bx-trash"></i></button>' : '') . '
            </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = $row->alasan;
        $th[] = $pukul;
        $th[] = $row->total;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_izin_meninggalkan_sgm') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getAllSimpSGM();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_fpp  = '<a href="surat_part_two/show/detail/izin_meninggalkan_sgm/' . $row->idGc . '">' . $row->kode . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = date('d-M-Y', strtotime($row->tgl_awal));
        $akhir = date('d-M-Y', strtotime($row->tgl_akhir));

        $pukul = $mulai . ' s/d ' . $akhir;

        $li_btn   = '
            <div class="btn-group" role="group" aria-label="First group">
                ' . ($row->status == '17' ? '<a href="surat/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                ' . ($row->status == '17' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="surat_part_two/delete"><i class="bx bx-trash"></i></button>' : '') . '
                ' . (isAdmin() ? '<a href="javascript:void(0)" onclick="openAdminEditModal(\'simp_sgm\', \'' . encrypt($row->idGc) . '\')" class="btn btn-sm btn-warning" title="Edit Data (Admin)"><i class="fas fa-pencil-alt text-dark"></i></a>' : '') . '
            </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = $row->alasan;
        $th[] = $pukul;
        $th[] = $row->total;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'permintaan_cuti_sgm') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getAllCutiSGMbyID(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_fpp  = '<a href="surat_part_two/show/detail/cuti_sgm/' . $row->idGc . '">' . $row->kode . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = date('d-M-Y', strtotime($row->tgl_awal));
        $akhir = date('d-M-Y', strtotime($row->tgl_akhir));

        $pukul = $mulai . ' s/d ' . $akhir;

        $btnBatal = '';
        if ($row->status != '3' && $row->status != '5') {
          $btnBatal = '<button type="button" class="btn btn-sm btn-danger btn-batal-cuti-sgm" title="Batalkan Pengajuan Cuti SGM" data-id="' . $row->idGc . '" data-kode="' . $row->kode . '"><i class="fas fa-ban"></i> Batal</button>';
        }

        $li_btn   = '
            <div class="btn-group" role="group" aria-label="Aksi">
                <a href="surat_part_two/show/detail/cuti_sgm/' . $row->idGc . '" class="btn btn-sm btn-info" title="Lihat Detail"><i class="fas fa-eye"></i> Detail</a> &nbsp;
                ' . $btnBatal . '
            </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = $row->alasan;
        $th[] = $pukul;
        $th[] = $row->total;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_cuti_sgm') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_surat_part_two->getAllCutiSGM();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_fpp  = '<a href="surat_part_two/show/detail/cuti_sgm/' . $row->idGc . '">' . $row->kode . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';




        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Affair</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = date('d-M-Y', strtotime($row->tgl_awal));
        $akhir = date('d-M-Y', strtotime($row->tgl_akhir));

        $pukul = $mulai . ' s/d ' . $akhir;

        $btnBatal = '';
        if ($row->status != '3' && $row->status != '5' && (isAdmin() || (isset($row->idPengaju) && $row->idPengaju == sessPenggunaId()))) {
          $btnBatal = '<button type="button" class="btn btn-sm btn-danger btn-batal-cuti-sgm" title="Batalkan Pengajuan Cuti SGM" data-id="' . $row->idGc . '" data-kode="' . $row->kode . '"><i class="fas fa-ban"></i> Batal</button>';
        }

        $li_btn   = '
            <div class="btn-group" role="group" aria-label="Aksi">
                <a href="surat_part_two/show/detail/cuti_sgm/' . $row->idGc . '" class="btn btn-sm btn-info" title="Lihat Detail"><i class="fas fa-eye"></i> Detail</a> &nbsp;
                ' . (isAdmin() ? '<a href="javascript:void(0)" onclick="openAdminEditModal(\'cuti_sgm\', \'' . encrypt($row->idGc) . '\')" class="btn btn-sm btn-warning" title="Edit Data (Admin)"><i class="fas fa-pencil-alt text-dark"></i></a> &nbsp;' : '') . '
                ' . $btnBatal . '
            </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = $row->alasan;
        $th[] = $pukul;
        $th[] = $row->total;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    }
  }




  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  // Notif Wa -------------------------------------------------------------------------------------------------------------------------------------------------------
  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------    
  public function notifWaAddSurat($ulang, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_pengguna->getById(sessPenggunaId());
    $namaPengaju    = $ambilDataPengaju[0]->nama;

    for ($i = 1; $i <= $ulang; $i++) {
      if ($i == 1) {
        $idpenerima = $detail['idPenerima1'];
      } else if ($i == 2) {
        $idpenerima = $detail['idPenerima2'];
      }

      $dataPenerima   = $this->md_pengguna->getById($idpenerima);
      //abaikan error
      error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 0);
      //
      $nope           = $dataPenerima[0]->no_hp;
      $dataWa = [
        'namaSurat'   => $detail['namaSurat'],
        'noPenerima'   => $nope,
        'kodeSurat'   => $detail['kode'],
        'namaPengaju' => $namaPengaju,
        'perihal'     => urlencode($detail['perihal']),
        'link'        => !empty($detail['link']) ? $detail['link'] : '',
        'namaPenerima'   => urlencode($detail['penerima'])
      ];
      waSuratOpen($dataWa);
    }
  }


  public function notifWaAprovPb($ulang, $param, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_surat_part_two->getSijkById($detail['id']);
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode               = $ambilDataPengaju[0]->kode_ijk;
    $perihal            = $ambilDataPengaju[0]->alasan;
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;
    $tgl                = date('d-m-Y', strtotime($ambilDataPengaju[0]->tanggal));
    $mulai              = $ambilDataPengaju[0]->jam_mulai;
    $akhir              = $ambilDataPengaju[0]->jam_akhir;
    $total              = $ambilDataPengaju[0]->total;
    $tglmulai           = date('d-m-Y', strtotime($ambilDataPengaju[0]->tgl_awal));
    $tglakhir           = date('d-m-Y', strtotime($ambilDataPengaju[0]->tgl_akhir));

    //if($idpengaju == 1){
    //    $idpengaju == 63;
    //}

    //send notif wa
    for ($i = 1; $i <= $ulang; $i++) {
      if ($i == 1) {
        //id pengaju surat
        if ($param == 2) {
          $idpenerima = $idpengaju;
        } else {
          $idpenerima = $detail['idPenerima1'];
        }
      } else if ($i == 2) {
        //id ???
        $idpenerima = $detail['idPenerima2'];
      }

      $dataPenerima   = $this->md_pengguna->getById($idpenerima);
      $nope           = !empty($dataPenerima[0]->no_hp) ? $dataPenerima[0]->no_hp : '';
      $linkDetail     = 'surat_part_two/show/detail/izin_jam_kerja/' . $detail['id'];
      if (strpos(strtolower($detail['namaSurat']), 'meninggalkan') !== false) {
        $linkDetail   = 'surat_part_two/show/detail/izin_meninggalkan/' . $detail['id'];
      }
      $dataWa = [
        'namaSurat'     => $detail['namaSurat'],
        'noPenerima'    => $nope,
        'kodeSurat'     => $kode,
        'namaPengaju'   => $namaPengaju,
        'namaPenerima'  => urlencode($detail['penerima']),
        'perihal'       => urlencode($perihal),
        'link'          => $linkDetail,
        'tanggal'       => $tgl,
        'mulai'         => $mulai,
        'akhir'         => $akhir,
        'total'         => $total,
        'tglmulai'      => $tglmulai,
        'tglakhir'      => $tglakhir,
        'ttd_sebelum1'  => urlencode($detail['ttd_sebelum1']),
        'ttd_sebelum2'  => urlencode($detail['ttd_sebelum2']),
        'ttd_sebelum3'  => urlencode($detail['ttd_sebelum3']),
        'ttd_sebelum4'  => ''
      ];

      if ($param == '1') {
        waSuratAprovOnProg($dataWa);
      } else if ($param == '2') {
        waSuratAprovAll($dataWa);
      } else if ($param == '3') {
        waSijkEngineer($dataWa);
      } else if ($param == '4') {
        waIzinCutiEngineer($dataWa);
      }
    }
  }






  public function notifWaRejectPb($detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_surat_part_two->getSijkById($detail['id']);
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode               = $ambilDataPengaju[0]->kode_ijk;
    $perihal            = $ambilDataPengaju[0]->alasan;
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;

    //ambil nomor
    $dataPenerima   = $this->md_pengguna->getById($idpengaju);
    $nope           = !empty($dataPenerima[0]->no_hp) ? $dataPenerima[0]->no_hp : '';
    $dataPenolak     = $this->md_pengguna->getById($detail['idPenolak']);
    $nopePenolak    = !empty($dataPenolak[0]->no_hp) ? $dataPenolak[0]->no_hp : '';

    $linkDetail = 'surat_part_two/show/detail/izin_jam_kerja/' . $detail['id'];
    if (strpos(strtolower($detail['namaSurat']), 'meninggalkan') !== false) {
      $linkDetail = 'surat_part_two/show/detail/izin_meninggalkan/' . $detail['id'];
    }

    //send notif wa
    $dataWa = [
      'namaSurat'   => $detail['namaSurat'],
      'noPenerima'   => $nope,
      'kodeSurat'   => $kode,
      'namaPengaju'   => $namaPengaju,
      'perihal'     => urlencode($perihal),
      'link'        => $linkDetail,
      'namaPenolak'   => urlencode($detail['namaPenolak']),
      'noPenolak'   => $nopePenolak
    ];
    waSuratReject($dataWa);
  }


  public function notifWaAprovCuti($ulang, $param, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_surat_part_two->getCutiById($detail['id']);
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode               = $ambilDataPengaju[0]->kode_cuti;
    $perihal            = $ambilDataPengaju[0]->alasan;
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;
    $total              = $ambilDataPengaju[0]->total;
    $tglmulai           = date('d-m-Y', strtotime($ambilDataPengaju[0]->tgl_awal));
    $tglakhir           = date('d-m-Y', strtotime($ambilDataPengaju[0]->tgl_akhir));

    //send notif wa
    for ($i = 1; $i <= $ulang; $i++) {
      if ($i == 1) {
        //id pengaju surat
        if ($param == 2) {
          $idpenerima = $idpengaju;
        } else {
          $idpenerima = $detail['idPenerima1'];
        }
      } else if ($i == 2) {
        //id ???
        $idpenerima = $detail['idPenerima2'];
      }

      $dataPenerima   = $this->md_pengguna->getById($idpenerima);
      $nope           = !empty($dataPenerima[0]->no_hp) ? $dataPenerima[0]->no_hp : '';
      $dataWa = [
        'namaSurat'     => $detail['namaSurat'],
        'noPenerima'    => $nope,
        'kodeSurat'     => $kode,
        'namaPengaju'   => $namaPengaju,
        'namaPenerima'  => urlencode($detail['penerima']),
        'perihal'       => urlencode($perihal),
        'link'          => 'surat_part_two/show/detail/cuti/' . $detail['id'],
        'total'         => $total,
        'tglmulai'      => $tglmulai,
        'tglakhir'      => $tglakhir,
        'ttd_sebelum1'  => urlencode($detail['ttd_sebelum1']),
        'ttd_sebelum2'  => urlencode($detail['ttd_sebelum2']),
        'ttd_sebelum3'  => urlencode($detail['ttd_sebelum3']),
        'ttd_sebelum4'  => ''
      ];

      if ($param == '1') {
        waSuratAprovOnProg($dataWa);
      } else if ($param == '2') {
        waSuratAprovAll($dataWa);
      } else if ($param == '3') {
        waSijkEngineer($dataWa);
      } else if ($param == '4') {
        waIzinCutiEngineer($dataWa);
      }
    }
  }


  public function notifWaRejectCuti($detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_surat_part_two->getCutiById($detail['id']);
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode               = $ambilDataPengaju[0]->kode_cuti;
    $perihal            = $ambilDataPengaju[0]->alasan;
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;

    //ambil nomor
    $dataPenerima   = $this->md_pengguna->getById($idpengaju);
    $nope           = !empty($dataPenerima[0]->no_hp) ? $dataPenerima[0]->no_hp : '';
    $dataPenolak     = $this->md_pengguna->getById($detail['idPenolak']);
    $nopePenolak    = !empty($dataPenolak[0]->no_hp) ? $dataPenolak[0]->no_hp : '';

    //send notif wa
    $dataWa = [
      'namaSurat'   => $detail['namaSurat'],
      'noPenerima'   => $nope,
      'kodeSurat'   => $kode,
      'namaPengaju'   => $namaPengaju,
      'perihal'     => urlencode($perihal),
      'link'        => 'surat_part_two/show/detail/cuti/' . $detail['id'],
      'namaPenolak'   => urlencode($detail['namaPenolak']),
      'noPenolak'   => $nopePenolak
    ];
    waSuratReject($dataWa);
  }



  public function notifWaAprov($ulang, $param, $detail)
  {

    //send notif wa
    for ($i = 1; $i <= $ulang; $i++) {
      if ($i == 1) {

        $idpenerima = $detail['idPenerima1'];
      } else if ($i == 2) {

        $idpenerima = $detail['idPenerima2'];
      }

      $dataPenerima   = $this->md_pengguna->getById($idpenerima);
      $nope           = !empty($dataPenerima[0]->no_hp) ? $dataPenerima[0]->no_hp : '';
      $perihalBa      = !empty($detail['perihal']) ? $detail['perihal'] : (!empty($detail['kode']) ? $detail['kode'] : '');
      $dataWa = [
        'namaSurat'     => $detail['namaSurat'],
        'noPenerima'    => $nope,
        'idBA'          => $detail['id'],
        'kodeSurat'     => $detail['kode'],
        'namaPengaju'   => $detail['pengaju'],
        'namaPenerima'  => urlencode($detail['penerima']),
        'perihal'       => urlencode($perihalBa),
        'link'          => isset($detail['link']) ? $detail['link'] : 'surat_part_two/show/detail/ba/' . $detail['id'],
        'ttd_sebelum1'  => urlencode($detail['ttd_sebelum1']),
        'ttd_sebelum2'  => urlencode($detail['ttd_sebelum2']),
        'ttd_sebelum3'  => urlencode($detail['ttd_sebelum3']),
        'ttd_sebelum4'  => ''
      ];

      if ($param == '1') {
        waSuratAprovOnProg($dataWa);
      } else if ($param == '2') {
        waSuratAprovAll($dataWa);
      } else if ($param == '3') {
        waSuratAprovOnProgDir($dataWa);
      } else if ($param == '4') {
        waSuratAprovGABA($dataWa);
      }
    }
  }

  public function notifWaRejectBa($detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_surat_part_two->getBeritaAcaraById($detail['id']);
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode               = $ambilDataPengaju[0]->kode_ba;
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;

    //ambil nomor
    $dataPenerima   = $this->md_pengguna->getById($idpengaju);
    $nope           = $dataPenerima[0]->no_hp;
    $dataPenolak     = $this->md_pengguna->getById($detail['idPenolak']);
    $nopePenolak    = $dataPenolak[0]->no_hp;

    //send notif wa
    $dataWa = [
      'namaSurat'   => $detail['namaSurat'],
      'noPenerima'   => $nope,
      'kodeSurat'   => $kode,
      'namaPengaju' => $namaPengaju,
      'link'        => 'surat_part_two/show/detail/ba/' . $detail['id'],
      'namaPenolak' => $detail['namaPenolak'],
      'noPenolak'   => $nopePenolak
    ];
    waSuratReject($dataWa);
  }


  public function notifWaRejectMR($detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_surat_part_two->getMeetingById($detail['id']);
    $namaPengaju        = $ambilDataPengaju[0]->nama;
    $kode               = $ambilDataPengaju[0]->kode;
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;

    //ambil nomor
    $dataPenerima   = $this->md_pengguna->getById($idpengaju);
    $nope           = $dataPenerima[0]->no_hp;
    $dataPenolak     = $this->md_pengguna->getById($detail['idPenolak']);
    $nopePenolak    = $dataPenolak[0]->no_hp;

    //send notif wa
    $dataWa = [
      'namaSurat'   => $detail['namaSurat'],
      'noPenerima'   => $nope,
      'kodeSurat'   => $kode,
      'namaPengaju' => $namaPengaju,
      'link'        => 'surat_part_two/show/detail/meetingroom/' . $detail['id'],
      'namaPenolak' => $detail['namaPenolak'],
      'noPenolak'   => $nopePenolak
    ];
    waSuratReject($dataWa);
  }



  public function notifWaAprovPbSGM($ulang, $param, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_surat_part_two->getAllById($detail['id']);
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode               = $ambilDataPengaju[0]->kode;
    $perihal            = $ambilDataPengaju[0]->alasan;
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;
    $tgl                = date('d-m-Y', strtotime($ambilDataPengaju[0]->tgl_awal));
    $mulai              = $ambilDataPengaju[0]->jam_mulai;
    $akhir              = $ambilDataPengaju[0]->jam_akhir;
    $total              = $ambilDataPengaju[0]->total;
    $tglmulai           = date('d-m-Y', strtotime($ambilDataPengaju[0]->tgl_awal));
    $tglakhir           = date('d-m-Y', strtotime($ambilDataPengaju[0]->tgl_akhir));

    $linkDetail = 'surat_part_two/show/detail/izin_jam_kerja_sgm/' . $detail['id'];
    if (strpos(strtolower($detail['namaSurat']), 'meninggalkan') !== false) {
      $linkDetail = 'surat_part_two/show/detail/izin_meninggalkan_sgm/' . $detail['id'];
    } else if (strpos(strtolower($detail['namaSurat']), 'cuti') !== false) {
      $linkDetail = 'surat_part_two/show/detail/cuti_sgm/' . $detail['id'];
    }

    //send notif wa
    for ($i = 1; $i <= $ulang; $i++) {
      if ($i == 1) {
        //id pengaju surat
        if ($param == 2) {
          $idpenerima = $idpengaju;
        } else {
          $idpenerima = $detail['idPenerima1'];
        }
      } else if ($i == 2) {
        //id ???
        $idpenerima = $detail['idPenerima2'];
      }

      $dataPenerima   = $this->md_pengguna->getById($idpenerima);
      $nope           = $dataPenerima[0]->no_hp;
      $nmPengaju      = $dataPenerima[0]->nama;
      $dataWa = [
        'namaSurat'     => $detail['namaSurat'],
        'noPenerima'    => $nope,
        'kodeSurat'     => $kode,
        'namaPengaju'   => $namaPengaju,
        'namaPenerima'   => urlencode($detail['penerima']),
        'perihal'       => urlencode($perihal),
        'link'          => $linkDetail,
        'tanggal'       => $tgl,
        'mulai'         => $mulai,
        'akhir'         => $akhir,
        'total'         => $total,
        'tglmulai'       => $tglmulai,
        'tglakhir'       => $tglakhir,
        'ttd_sebelum1'   => urlencode($detail['ttd_sebelum1']),
        'ttd_sebelum2'   => urlencode($detail['ttd_sebelum2']),
        'ttd_sebelum3'   => urlencode($detail['ttd_sebelum3']),
        'ttd_sebelum4'   => ''
      ];

      if ($param == '1') {
        waSuratAprovOnProg($dataWa);
      } else if ($param == '2') {
        waSuratAprovAll($dataWa);
      } else if ($param == '3') {
        waSijkEngineer($dataWa);
      } else if ($param == '4') {
        waIzinCutiEngineer($dataWa);
      }
    }
  }


  public function notifWaRejectPbSGM($detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_surat_part_two->getAllById($detail['id']);
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode               = $ambilDataPengaju[0]->kode;
    $perihal            = $ambilDataPengaju[0]->alasan;
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;

    //ambil nomor
    $dataPenerima   = $this->md_pengguna->getById($idpengaju);
    $nope           = $dataPenerima[0]->no_hp;
    $dataPenolak     = $this->md_pengguna->getById($detail['idPenolak']);
    $nopePenolak    = $dataPenolak[0]->no_hp;

    $linkDetail = 'surat_part_two/show/detail/izin_jam_kerja_sgm/' . $detail['id'];
    if (strpos(strtolower($detail['namaSurat']), 'meninggalkan') !== false) {
      $linkDetail = 'surat_part_two/show/detail/izin_meninggalkan_sgm/' . $detail['id'];
    } else if (strpos(strtolower($detail['namaSurat']), 'cuti') !== false) {
      $linkDetail = 'surat_part_two/show/detail/cuti_sgm/' . $detail['id'];
    }

    //send notif wa
    $dataWa = [
      'namaSurat'   => $detail['namaSurat'],
      'noPenerima'   => $nope,
      'kodeSurat'   => $kode,
      'namaPengaju'   => $namaPengaju,
      'perihal'     => urlencode($perihal),
      'link'        => $linkDetail,
      'namaPenolak'   => urlencode($detail['namaPenolak']),
      'noPenolak'   => $nopePenolak
    ];
    waSuratReject($dataWa);
  }







  //==================================================
  //================== Notif WA       ================
  //==================================================





  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  // print ------------------------------------------------------------------------------------------------------------------------------------------------------------
  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------

  public function print_page($param1 = "", $param2 = "")
  {
    grantAccessFor('all');

    if ($param1 == 'sijk') {

      $gc              = $this->md_surat_part_two->getSijkById($param2);

      $dt = [
        'title_pdf'  => 'sijk',
        'object'  => $param1,
        'data_sijk'  => $this->md_surat_part_two->getSijkById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Surat Izin Pada Jam Kerja  ' . $gc[0]->pengaju;

      $simp     = $this->md_surat_part_two->getSijkById($param2);
      // page html yang akan di jadikan ke pdf
      if ($simp[0]->id_Sijk > 62) {
        $html = $this->load->view('pages/v_print/print_sijk', $dt, true);
      } else {
        $html = $this->load->view('pages/v_print/print_sijk_1', $dt, true);
      }
      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'simp') {

      $gc              = $this->md_surat_part_two->getSijkById($param2);

      $dt = [
        'title_pdf'  => 'simp',
        'object'  => $param1,
        'data_sijk'  => $this->md_surat_part_two->getSijkById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Surat Izin Meninggalkan Pekerjaan  ' . $gc[0]->pengaju;


      $simp      = $this->md_surat_part_two->getSijkById($param2);
      if ($simp[0]->id_Sijk > 61) {
        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_simp', $dt, true);
      } else {
        $html = $this->load->view('pages/v_print/print_simp_1', $dt, true);
      }
      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'cuti') {

      $gc              = $this->md_surat_part_two->getCutiById($param2);

      $dt = [
        'title_pdf'  => 'cuti',
        'object'  => $param1,
        'data_sijk'  => $this->md_surat_part_two->getCutiById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Cuti Tahunan  ' . $gc[0]->pengaju;

      $cuti    = $this->md_surat_part_two->getCutiById($param2);
      if ($cuti[0]->id_Sijk > 1) {
        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_cuti', $dt, true);
      } else {
        $html = $this->load->view('pages/v_print/print_cuti_1', $dt, true);
      }
      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'ba') {

      $gc              = $this->md_surat_part_two->getBeritaAcaraById($param2);

      $dt = [
        'title_pdf'  => 'ba',
        'object'  => $param1,
        'data_ba'  => $this->md_surat_part_two->getBeritaAcaraById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Berita Acara ' . $gc[0]->pengaju;

      // page htmk yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_ba', $dt, true);
      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'paklaring') {

      $gc              = $this->md_surat_part_two->getPaklaringById($param2);

      $dt = [
        'title_pdf'  => 'paklaring',
        'object'  => $param1,
        'data_pk'  => $this->md_surat_part_two->getPaklaringById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Surat keterangan Pengalaman kerja ' . $gc[0]->nama;

      // page htmk yang akan di jadikan ke pdf
      if ($gc[0]->idGc == 5) {
        $html = $this->load->view('pages/v_print/print_paklaring_sgm', $dt, true);
      } else {
        $html = $this->load->view('pages/v_print/print_paklaring', $dt, true);
      }
      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'meetingroom') {

      $gc              = $this->md_surat_part_two->getMeetingById($param2);

      $dt = [
        'title_pdf'  => 'Meeting Room',
        'object'  => $param1,
        'data_meeting'  => $this->md_surat_part_two->getMeetingById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Surat Penggunaan Ruang Meeting ' . $gc[0]->nama;

      // page htmk yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_meetingroom', $dt, true);
      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'sijk_sgm') {

      $gc              = $this->md_surat_part_two->getAllById($param2);

      $dt = [
        'title_pdf'  => 'sijk',
        'object'  => $param1,
        'data_sijk'  => $this->md_surat_part_two->getAllById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Surat Izin Pada Jam Kerja  ' . $gc[0]->pengaju;

      $html = $this->load->view('pages/v_print/print_sijk_sgm', $dt, true);

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'simp_sgm') {

      $gc              = $this->md_surat_part_two->getAllById($param2);

      $dt = [
        'title_pdf'  => 'simp',
        'object'  => $param1,
        'data_sijk'  => $this->md_surat_part_two->getAllById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Surat Izin Meninggalkan Pekerjaan  ' . $gc[0]->pengaju;


      $html = $this->load->view('pages/v_print/print_simp_sgm', $dt, true);

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'cuti_sgm') {

      $gc              = $this->md_surat_part_two->getAllById($param2);

      $dt = [
        'title_pdf'  => 'cuti',
        'object'  => $param1,
        'data_sijk'  => $this->md_surat_part_two->getAllById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Cuti Tahunan  ' . $gc[0]->pengaju;

      $html = $this->load->view('pages/v_print/print_cuti_sgm', $dt, true);

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    }
  }

  //================================================================================
  // FUNGSI TAMBAHAN: NOTIFIKASI WA GROUP
  //================================================================================
  public function notifWaGroupCuti($id_cuti, $nama_group_wa)
  {
    // 1. Ambil Data Cuti menggunakan Model yang sudah di-load
    // Pastikan model md_surat_part_two sudah diload di construct
    $dataCuti = $this->md_surat_part_two->getCutiById($id_cuti);

    // Jika tidak ditemukan di table utama, cek table SGM (karena logic sgm pakai getAllById)
    if (empty($dataCuti)) {
      $dataCuti = $this->md_surat_part_two->getAllById($id_cuti);
    }

    // Validasi data
    if (empty($dataCuti)) {
      return false;
    }

    // Ambil data detail
    $namaKaryawan = isset($dataCuti[0]->pengaju) ? $dataCuti[0]->pengaju : 'Karyawan'; // jaga-jaga jika field beda
    $tglAwal      = $dataCuti[0]->tgl_awal;
    $tglAkhir     = $dataCuti[0]->tgl_akhir;
    $keterangan   = 'Disetujui';

    // 2. Logic Format Tanggal (Range atau Single)
    $formatIndo = function ($dateDb) {
      $daftarHari  = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
      $daftarBulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

      $timestamp   = strtotime($dateDb);
      $hariInggris = date('l', $timestamp);
      $tgl         = date('j', $timestamp);
      $bln         = date('n', $timestamp);
      $thn         = date('Y', $timestamp);

      return $daftarHari[$hariInggris] . ", " . $tgl . " " . $daftarBulan[$bln] . " " . $thn;
    };

    if ($tglAwal == $tglAkhir) {
      $tglDisplay = $formatIndo($tglAwal);
    } else {
      $tglDisplay = $formatIndo($tglAwal) . " s/d " . $formatIndo($tglAkhir);
    }

    // 3. Siapkan Array Data
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);

    $dataWa = [
      'targetGroup' => $nama_group_wa,
      'header'      => 'INFORMASI CUTI TAHUNAN',
      'nama'        => $namaKaryawan,
      'tanggal'     => $tglDisplay,
      'keterangan'  => $keterangan
    ];

    // 4. Panggil Helper
    // Pastikan helper 'whatsapp_helper' sudah diload
    waAppGroupCuti($dataWa);
  }
}
