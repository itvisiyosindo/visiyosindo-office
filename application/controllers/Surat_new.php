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

class Surat_new extends CI_Controller
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
    $this->load->model('md_surat_new');
    $this->load->model('md_pengguna');
    $this->load->model('md_gudang');
    $this->load->model('md_pelanggan');
    $this->load->model('md_surat_list');
    $this->load->model('md_surat_part_two');
    $this->load->model('md_surat_list');
    $this->load->model('md_prov_kota');
    $this->load->helper('terbilang_helper');
    $this->load->helper('tanggal_helper');
    $this->load->helper('datetime_helper');
    $this->load->helper('whatsapp_helper');
    $this->load->helper('encrypt_helper');
  }

  public function show($param = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');
    if ($param == 'detail') {
      if ($param2 == 'kendaraan') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_kend']      = $this->md_surat_new->getDetailKendaraanById($param3);
        $page_data['page_name']     = 'surat_new/v_detail_kendaraan';
        $page_data['page_title']    = 'Penggunaan Kendaraan';
        $page_data['page_desc']     = 'Detail Permintaan Penggunaan Kendaraan Kantor';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'po') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_po']        = $this->md_surat_new->getDetailPoById($param3);
        $app_po                     = $this->md_surat_new->getDetailPoById($param3);
        if ($app_po[0]->idGc > 209) { //209
          $page_data['page_name']     = 'surat_new/v_detail_po';
        } else {
          $page_data['page_name']     = 'surat_new/v_detail_po_1';
        }
        $page_data['page_title']    = 'Approval PO';
        $page_data['page_desc']     = 'Detail Permintaan Approval PO';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'appeks') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_aprv']      = $this->md_surat_new->getAppeksDetailById($param3);
        $page_data['detail_aprv']    = $this->md_surat_new->getDetailAppById($param3);
        //$page_data['page_name']     = 'surat_new/v_detail_appeks';
        //Untuk merangkum pergantian format
        $approval          = $this->md_surat_new->getAppeksDetailById($param3);
        if ($approval[0]->idGc > 2) {
          $page_data['page_name']     = 'surat_new/v_detail_appeks';
        } else {
          $page_data['page_name']     = 'surat_new/v_detail_appeks_1';
        }
        $page_data['page_title']    = 'Approval Expedisi';
        $page_data['page_desc']     = 'Detail Permintaan Approval Expedisi';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'sta') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_serah']      = $this->md_surat_new->getStaDetailById($param3);
        $page_data['detail_serah']    = $this->md_surat_new->getDetailSerahById($param3);
        $page_data['page_name']     = 'surat_new/v_detail_sta';
        $page_data['page_title']    = 'Serah Terima Aset';
        $page_data['page_desc']     = 'Detail Serah Terima Aset';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'stfp') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_serah']      = $this->md_surat_new->getStaDetailById($param3);
        $page_data['detail_serah']    = $this->md_surat_new->getDetailSerahById($param3);
        $page_data['page_name']     = 'surat_new/v_detail_stfp';
        $page_data['page_title']    = 'Serah Terima Fisik Perlengkapan';
        $page_data['page_desc']     = 'Detail Serah Terima Fisik Perlengkapan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'stp') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_serah']      = $this->md_surat_new->getStaDetailById($param3);
        $page_data['detail_serah']    = $this->md_surat_new->getDetailSerahById($param3);
        $page_data['page_name']     = 'surat_new/v_detail_stp';
        $page_data['page_title']    = 'Serah Terima Pekerjaan';
        $page_data['page_desc']     = 'Detail Serah Terima Pekerjaan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'spi') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_istirahat']      = $this->md_surat_new->getIstirahatById($param3);
        $page_data['page_name']     = 'surat_new/v_detail_spi';
        $page_data['page_title']    = 'Surat Skorsing';
        $page_data['page_desc']     = 'Detail Surat Skorsing';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'appdir') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_appdir']    = $this->md_surat_new->getAppdirById($param3);
        $page_data['page_name']     = 'surat_new/v_detail_appdir';
        $page_data['page_title']    = 'Approval Director';
        $page_data['page_desc']     = 'Detail Approval Director';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'app_pajak') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_pajak']    = $this->md_surat_new->getAppPajakById($param3);
        $page_data['page_name']     = 'surat_new/v_detail_app_pajak';
        $page_data['page_title']    = 'Approval Faktur Pajak';
        $page_data['page_desc']     = 'Detail Approval Faktur Pajak';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'permintaan') {
      if ($param2 == 'kendaraan') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_kendaraan';
        $page_data['page_title']    = 'Penggunaan Kendaraan';
        $page_data['page_desc']     = 'Daftar Permintaan Penggunaan Kendaraan Kantor yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'po') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_po';
        $page_data['page_title']    = 'Approval PO';
        $page_data['page_desc']     = 'Daftar Permintaan Approval PO yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'appeks') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_appeks';
        $page_data['page_title']    = 'Approval Expedisi';
        $page_data['page_desc']     = 'Daftar Permintaan Approval Expedisi yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'sta') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_sta';
        $page_data['page_title']    = 'Serah Terima Aset';
        $page_data['page_desc']     = 'Daftar Serah Terima Aset yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'stfp') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_stfp';
        $page_data['page_title']    = 'Serah Terima Fisik Perlengkapan';
        $page_data['page_desc']     = 'Daftar Serah Terima Fisik Perlengkapan yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'stp') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_stp';
        $page_data['page_title']    = 'Serah Terima Pekerjaan';
        $page_data['page_desc']     = 'Daftar Serah Terima Pekerjaan yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'appdir') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_appdir';
        $page_data['page_title']    = 'Approval Director';
        $page_data['page_desc']     = 'Daftar Approval Director yang anda Ajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'app_pajak') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_app_pajak';
        $page_data['page_title']    = 'Approval Faktur Pajak';
        $page_data['list_marketing']  = $this->md_pengguna->getPenggunaMarketing();
        //$page_data['list_cust']     = $this->md_surat_new->getAllCustomer(); Ini Ambil dari tabel Customer (yang Diinput tim warehouse)
        $page_data['list_cust']     = $this->md_pelanggan->getByWhere(['p.status' => 1]); //Kalau ini dari tabel pelanggan (yang input CRO)  gatau kenapa dulu tabelnya bisa pisah wkwk
        $page_data['page_desc']     = 'Daftar Approval Faktur Pajak yang anda Ajukan';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'list') {
      if ($param2 == 'kendaraan') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_list_kendaraan';
        $page_data['page_title']    = 'Penggunaan Kendaraan';
        $page_data['page_desc']     = 'Daftar Pengajuan Penggunaan Kendaraan Kantor yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'po') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_list_po';
        $page_data['page_title']    = 'Approval PO';
        $page_data['page_desc']     = 'Daftar Pengajuan Approval PO yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'appeks') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_list_appeks';
        $page_data['page_title']    = 'Approval Expedisi';
        $page_data['page_desc']     = 'Daftar Pengajuan Approval Expedisi yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'sta') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_list_sta';
        $page_data['page_title']    = 'Serah Terima Aset';
        $page_data['page_desc']     = 'Daftar Serah Terima Aset yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'stfp') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_list_stfp';
        $page_data['page_title']    = 'Serah Terima Fisik Perlengkapan';
        $page_data['page_desc']     = 'Daftar Serah Terima Fisik Perlengkapan yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'stp') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_list_stp';
        $page_data['page_title']    = 'Serah Terima Pekerjaan';
        $page_data['page_desc']     = 'Daftar Serah Terima Pekerjaan yang Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'spi') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_list_spi';
        $page_data['page_title']    = 'Surat Skorsing';
        $page_data['page_desc']     = 'Daftar Surat Skorsing';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'appdir') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_list_appdir';
        $page_data['page_title']    = 'Approval Director';
        $page_data['page_desc']     = 'Daftar Approval Director';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'app_pajak') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'surat_new/v_list_app_pajak';
        $page_data['page_title']    = 'Approval Faktur Pajak';
        $page_data['page_desc']     = 'Daftar Approval Faktur Pajak';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'pengajuan') {
      if ($param2 == 'kendaraan') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['page_name']     = 'surat_new/v_aju_kendaraan';
        $page_data['page_title']    = 'Penggunaan Kendaraan';
        $page_data['page_desc']     = 'Form Permintaan Penggunaan Kendaraan Kantor';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'po') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_cust']     = $this->md_pelanggan->getByWhere(['p.status' => 1]);
        $page_data['page_name']     = 'surat_new/v_aju_po';
        $page_data['page_title']    = 'Approval PO';
        $page_data['page_desc']     = 'Form Permintaan Approval PO';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'appeks') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['gudang']        = $this->md_gudang->getByWhere(['g.status' => 1]);
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['list_cust']     = $this->md_pelanggan->getByWhere(['p.status' => 1]);
        $page_data['page_name']     = 'surat_new/v_aju_appeks';
        $page_data['page_title']    = 'Approval Expedisi';
        $page_data['page_desc']     = 'Form Permintaan Approval Expedisi';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'sta') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['list_nama']     = $this->md_surat_part_two->getBywhereActive();
        $page_data['page_name']     = 'surat_new/v_aju_sta';
        $page_data['page_title']    = 'Serah Terima Aset';
        $page_data['page_desc']     = 'Form Serah Terima Aset';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'stfp') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['list_nama']     = $this->md_surat_part_two->getBywhereActive();
        $page_data['page_name']     = 'surat_new/v_aju_stfp';
        $page_data['page_title']    = 'Serah Terima Fisik Perlengkapan';
        $page_data['page_desc']     = 'Form Serah Terima Fisik Perlengkapan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'stp') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['list_nama']     = $this->md_surat_part_two->getBywhereActive();
        $page_data['page_name']     = 'surat_new/v_aju_stp';
        $page_data['page_title']    = 'Serah Terima Pekerjaan';
        $page_data['page_desc']     = 'Form Serah Terima Pekerjaan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'spi') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['list_nama']     = $this->md_surat_part_two->getBywhereActive();
        $page_data['page_name']     = 'surat_new/v_aju_spi';
        $page_data['page_title']    = 'Surat Skorsing';
        $page_data['page_desc']     = 'Form Surat Skorsing';
        $this->load->view('index', $page_data);
      }
    }
  }


  //ADD
  public function addSrt($param = "")
  {
    grantAccessFor('all');

    if ($param == "kendaraan") {
      //menambah pengajuan Kendaraan kantor
      $this->md_surat_new->reset_increment("surat_kendaraan");
      $idFpp      = $this->md_surat_new->getKendaraanKodeId();
      $ambilId    = $idFpp->id;
      $ambilId    = ($ambilId + 1);
      $panjangId  = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/PPKK/PT.VYM/" . $bulan . "/" . $tahun;
      $data['kode']           = $kodeFpp;

      $data['id_pengaju']    = sessPenggunaId();
      $data['tanggal']       = date_db_format($this->input->post('tanggal', TRUE));
      $data['keperluan']     = $this->input->post('keperluan', TRUE);
      $data['lama']          = $this->input->post('lama', TRUE);
      $data['mobil']         = $this->input->post('mobil', TRUE);
      $data['nopol']         = $this->input->post('nopol', TRUE);
      $data['lampiran']       = $this->input->post('lampiran', TRUE);
      $data['status']        = 0;
      $this->md_surat_new->addKendaraan($data);

      //send notif wa 
      $dataWa = [
        'idPenerima1'   => 15,
        'idPenerima2'   => '',
        'namaSurat'     => 'Permintaan Penggunaan Kendaraan Kantor',
        'penerima'       => 'Kardonal',
        'perihal'       => $data['keperluan'],
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Pengajuan Penggunaan Kendaraan', 'Permintaan Penggunaan Kendaraan Kantor');
      ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
    } else if ($param == "po") {
      //menambah pengajuan PO
      $this->md_surat_new->reset_increment("surat_po");
      $idFpp      = $this->md_surat_new->getPoKodeId();
      $ambilId    = $idFpp->id;
      $ambilId    = ($ambilId + 1);
      $panjangId  = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/Aprvl/CRO/PT.VYM/" . $bulan . "/" . $tahun;
      $data['kode']           = $kodeFpp;

      $data['id_pengaju']    = sessPenggunaId();
      $data['tanggal']       = date_db_format($this->input->post('tanggal', TRUE));
      $data['id_pelanggan']  = $this->input->post('id_pelanggan', TRUE);
      $data['no_po']         = $this->input->post('no_po', TRUE);
      $data['item1']         = $this->input->post('item1', TRUE);
      $data['item2']         = $this->input->post('item2', TRUE);
      $data['item3']         = $this->input->post('item3', TRUE);
      $data['item4']         = $this->input->post('item4', TRUE);
      $data['item5']         = $this->input->post('item5', TRUE);
      $data['lampiran']       = $this->input->post('lampiran', TRUE);
      $data['alasan']         = $this->input->post('alasan', TRUE);
      $data['jenis']         = $this->input->post('jenis', TRUE) ?: '1';
      $data['status']        = 0;
      $this->md_surat_new->addPo($data);

      //send notif wa 
      $dataWa = [
        'idPenerima1'   => 33,
        'idPenerima2'   => '',
        'namaSurat'     => 'Approval PO',
        'penerima'       => '_General Manager_',
        'perihal'       => $data['no_po'],
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Approval PO', 'Permintaan Approval PO Kode : ' . $kodeFpp);
      ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
    } else if ($param == "appeks") {
      //menambah pengajuan Approval Expedisi
      $this->md_surat_new->reset_increment("surat_aprv");
      $idFpp      = $this->md_surat_new->getAppeksKodeId();
      $ambilId    = $idFpp->id;
      $ambilId    = ($ambilId + 1);
      $panjangId  = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/S.App/WHS/VYM/" . $bulan . "/" . $tahun;
      $data['kode']           = $kodeFpp;

      $data['id_pengaju']    = sessPenggunaId();
      $data['tanggal']       = date_db_format($this->input->post('tanggal', TRUE));
      $data['nama_customer'] = $this->input->post('nama_customer', TRUE);
      $data['tujuan']        = $this->input->post('tujuan', TRUE);
      $data['nama_barang']   = $this->input->post('nama_barang', TRUE);
      $data['no_sj']         = $this->input->post('no_sj', TRUE);
      $data['lampiran']       = $this->input->post('lampiran', TRUE);
      $data['kota_aju']       = $this->input->post('kota_aju', TRUE);
      $data['gudang_asal']       = $this->input->post('gudang_asal', TRUE);
      $data['rencana_expedisi']   = $this->input->post('rencana_expedisi', TRUE);
      $data['harga_expedisi']     = $this->input->post('harga_expedisi', TRUE);
      $data['po_customer']       = $this->input->post('po_customer', TRUE);
      $data['sph_customer']       = $this->input->post('sph_customer', TRUE);
      $data['status']        = 0;
      $this->md_surat_new->addAppeks($data);


      //menambah data ke tabel detail
      $lastGcId = $this->md_surat_new->getAppeksLastId();
      $lastGcId = $lastGcId->id;

      //menambah detail Appeks
      $this->md_surat_new->reset_increment("surat_aprv_detail");
      $itung = $this->input->post('itung', TRUE);
      $dataDetailGc['id_aprv']      = $lastGcId;
      //$dataDetailGc['approval']	    = 1;
      if ($itung > 0) {
        for ($x = 1; $x < $itung; $x++) {
          $dataDetailGc['namaekspedisi']  = $this->input->post('namaekspedisi[' . $x . ']', TRUE);
          $dataDetailGc['berat']          = $this->input->post('berat[' . $x . ']', TRUE);
          $dataDetailGc['acuanharga']      = $this->input->post('acuanharga[' . $x . ']', TRUE);
          $dataDetailGc['harga']          = $this->input->post('harga[' . $x . ']', TRUE);
          $dataDetailGc['fasilitas']      = $this->input->post('fasilitas[' . $x . ']', TRUE);
          $dataDetailGc['kekurangan']      = $this->input->post('kekurangan[' . $x . ']', TRUE);
          //$dataDetailGc['approval']			  = $this->input->post('approval['.$x.']', TRUE);
          $this->md_surat_new->addAppeksDetail($dataDetailGc);
        }
      }

      //send notif wa 
      $dataWa = [
        'idPenerima1'   => 107,
        'idPenerima2'   => '',
        'namaSurat'     => 'Approval Expedisi',
        'penerima'       => 'Senior Accounting and Finance',
        'perihal'       => $data['nama_customer'],
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Approval Expedisi', 'Permintaan Approval Expedisi Kode ' . $kodeFpp);
      ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
    } else if ($param == "sta") {
      //menambah pengajuan Serah Terima Aset
      $this->md_surat_new->reset_increment("surat_serah");
      $idFpp      = $this->md_surat_new->getStaKodeId();
      $ambilId    = $idFpp->id_serah;
      $ambilId    = ($ambilId + 1);
      $panjangId  = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/STA/HRGA/VYM/" . $bulan . "/" . $tahun;
      $data['kode']           = $kodeFpp;

      $data['id_pengaju']    = sessPenggunaId();
      $data['tanggal']       = date_db_format($this->input->post('tanggal', TRUE));
      $data['id_terima']     = $this->input->post('id_terima', TRUE);
      $data['keterangan']    = $this->input->post('keterangan', TRUE);
      $data['lampiran']       = $this->input->post('lampiran', TRUE);
      $data['kota_aju']       = $this->input->post('kota_aju', TRUE);
      $data['jenis']         = 1;
      $this->md_surat_new->addSerah($data);


      //menambah pengajuan fpp
      $lastGcId = $this->md_surat_new->getStaLastId();
      $lastGcId = $lastGcId->id_serah;

      //menambah detail fpp
      $this->md_surat_new->reset_increment("surat_serah_detail");
      $itung = $this->input->post('itung', TRUE);
      $dataDetailGc['id_serah']      = $lastGcId;
      if ($itung > 0) {
        for ($x = 1; $x < $itung; $x++) {
          $dataDetailGc['nama']  = $this->input->post('nama[' . $x . ']', TRUE);
          $dataDetailGc['sn']        = $this->input->post('sn[' . $x . ']', TRUE);
          $dataDetailGc['jumlah']      = $this->input->post('jumlah[' . $x . ']', TRUE);
          $this->md_surat_new->addSerahDetail($dataDetailGc);
        }
      }

      $ambilData   = $this->md_pengguna->getById($data['id_terima']);
      $namaTerima     = $ambilData[0]->nama;

      //send notif wa 
      $dataWa = [
        'idPenerima1'   => $data['id_terima'],
        'idPenerima2'   => '',
        'namaSurat'     => 'Serah Terima Aset',
        'penerima'       => $namaTerima,
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Serah Terima Aset', 'Permintaan Serah Terima Aset Kode ' . $kodeFpp);
      ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
    } else if ($param == "stfp") {
      //menambah pengajuan Serah Terima Fisik Perlengkapan
      $this->md_surat_new->reset_increment("surat_serah");
      $idFpp      = $this->md_surat_new->getStfpKodeId();
      $ambilId    = $idFpp->id_serah;
      $ambilId    = ($ambilId + 1);
      $panjangId  = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/STFP/HRGA/VYM/" . $bulan . "/" . $tahun;
      $data['kode']           = $kodeFpp;

      $data['id_pengaju']    = sessPenggunaId();
      $data['tanggal']       = date_db_format($this->input->post('tanggal', TRUE));
      $data['id_terima']     = $this->input->post('id_terima', TRUE);
      $data['keterangan']    = $this->input->post('keterangan', TRUE);
      $data['lampiran']       = $this->input->post('lampiran', TRUE);
      $data['kota_aju']       = $this->input->post('kota_aju', TRUE);
      $data['jenis']         = 2;
      $this->md_surat_new->addSerah($data);


      //menambah pengajuan fpp
      $lastGcId = $this->md_surat_new->getStaLastId();
      $lastGcId = $lastGcId->id_serah;

      //menambah detail fpp
      $this->md_surat_new->reset_increment("surat_serah_detail");
      $itung = $this->input->post('itung', TRUE);
      $dataDetailGc['id_serah']      = $lastGcId;
      if ($itung > 0) {
        for ($x = 1; $x < $itung; $x++) {
          $dataDetailGc['nama']  = $this->input->post('nama[' . $x . ']', TRUE);
          $dataDetailGc['jumlah']      = $this->input->post('jumlah[' . $x . ']', TRUE);
          $this->md_surat_new->addSerahDetail($dataDetailGc);
        }
      }

      $ambilData   = $this->md_pengguna->getById($data['id_terima']);
      $namaTerima     = $ambilData[0]->nama;

      //send notif wa 
      $dataWa = [
        'idPenerima1'   => $data['id_terima'],
        'idPenerima2'   => '',
        'namaSurat'     => 'Serah Terima Fisik Perlengkapan',
        'penerima'       => $namaTerima,
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Serah Terima Fisik Perlengkapan', 'Permintaan Serah Terima Fisik Perlengkapan ' . $kodeFpp);
      ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
    } else if ($param == "stp") {
      //menambah pengajuan Serah Terima Fisik Perlengkapan
      $this->md_surat_new->reset_increment("surat_serah");
      $idFpp      = $this->md_surat_new->getStpKodeId();
      $ambilId    = $idFpp->id_serah;
      $ambilId    = ($ambilId + 1);
      $panjangId  = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/STP/HRGA/VYM/" . $bulan . "/" . $tahun;
      $data['kode']           = $kodeFpp;

      $data['id_pengaju']    = sessPenggunaId();
      $data['tanggal']       = date_db_format($this->input->post('tanggal', TRUE));
      $data['id_terima']     = $this->input->post('id_terima', TRUE);
      $data['id_diketahui']  = $this->input->post('id_diketahui', TRUE);
      $data['keterangan']    = $this->input->post('keterangan', TRUE);
      $data['kota_aju']       = $this->input->post('kota_aju', TRUE);
      $data['jenis']         = 3;
      $this->md_surat_new->addSerah($data);


      //menambah pengajuan fpp
      $lastGcId = $this->md_surat_new->getStaLastId();
      $lastGcId = $lastGcId->id_serah;

      //menambah detail fpp
      $this->md_surat_new->reset_increment("surat_serah_detail");
      $itung = $this->input->post('itung', TRUE);
      $dataDetailGc['id_serah']      = $lastGcId;
      if ($itung > 0) {
        for ($x = 1; $x < $itung; $x++) {
          $dataDetailGc['nama']  = $this->input->post('nama[' . $x . ']', TRUE);
          $dataDetailGc['link']      = $this->input->post('link[' . $x . ']', TRUE);
          $dataDetailGc['ket']      = $this->input->post('ket[' . $x . ']', TRUE);
          $this->md_surat_new->addSerahDetail($dataDetailGc);
        }
      }

      $ambilData   = $this->md_pengguna->getById($data['id_terima']);
      $namaTerima     = $ambilData[0]->nama;

      //send notif wa 
      $dataWa = [
        'idPenerima1'   => $data['id_terima'],
        'idPenerima2'   => '',
        'namaSurat'     => 'Serah Terima Pekerjaan',
        'penerima'       => $namaTerima,
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Serah Terima Pekerjaan', 'Permintaan Serah Terima Pekerjaan ' . $kodeFpp);
      ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
    } else if ($param == "spi") {
      //menambah pengajuan Serah Terima Fisik Perlengkapan
      $this->md_surat_new->reset_increment("surat_skorsing");
      $idFpp      = $this->md_surat_new->getIstirahatKodeId();
      $ambilId    = $idFpp->id;
      $ambilId    = ($ambilId + 1);
      $panjangId  = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/SKORSING/HRGA/VYM/" . $bulan . "/" . $tahun;
      $data['kode']           = $kodeFpp;

      $data['idpengaju']    = sessPenggunaId();
      $data['tanggal']      = date_db_format($this->input->post('tanggal', TRUE));
      $data['idpengguna']   = $this->input->post('idpengguna', TRUE);
      $data['masa']         = $this->input->post('masa', TRUE);
      $this->md_surat_new->addIstirahat($data);



      //send notif wa 
      $dataWa = [
        'idPenerima1'   => '69',
        'idPenerima2'   => '',
        'namaSurat'     => 'Surat Skorsing',
        'penerima'       => '_HR and Legal_',
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Surat Skorsing', 'Permintaan Surat Skorsing ' . $kodeFpp);
      ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
    } else if ($param == "appdir") {
      //menambah pengajuan Serah Terima Fisik Perlengkapan
      $this->md_surat_new->reset_increment("approval_director");
      $idFpp      = $this->md_surat_new->getAppdirKodeId();
      $ambilId    = $idFpp->id;
      $ambilId    = ($ambilId + 1);
      $panjangId  = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/AppDir/HRGA/VYM/" . $bulan . "/" . $tahun;
      $data['kode']           = $kodeFpp;

      $data['id_pengaju']    = sessPenggunaId();
      $data['nama_dokumen']  = $this->input->post('nama_dokumen', TRUE);
      $data['ket']           = $this->input->post('ket', TRUE);
      $data['link']          = $this->input->post('link', TRUE);
      $data['jenis']         = $this->input->post('jenis', TRUE);
      $data['status']        = 0;
      $this->md_surat_new->addAppdir($data);



      //send notif wa 
      $dataWa = [
        'idPenerima1'   => '69',
        'idPenerima2'   => '',
        'namaSurat'     => 'Approval Director',
        'penerima'       => '_HR and Legal_',
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Approval Director', 'Permintaan Approval Director ' . $kodeFpp);
      ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
    } else if ($param == "app_pajak") {
      //menambah pengajuan Serah Terima Fisik Perlengkapan
      $this->md_surat_new->reset_increment("approval_faktur_pajak");
      $idFpp      = $this->md_surat_new->getAppPajakKodeId();
      $ambilId    = $idFpp->id;
      $ambilId    = ($ambilId + 1);
      $panjangId  = strlen($ambilId);

      if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
      } else {
        $kodeFpp = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();

      $kodeFpp = $kodeFpp . "/FP/FAT/VYM/" . $bulan . "/" . $tahun;
      $data['kode']           = $kodeFpp;

      $data['id_pengaju']    = sessPenggunaId();
      $data['tanggal']       = date_db_format($this->input->post('tanggal', TRUE));
      $data['id_marketing']  = $this->input->post('id_marketing', TRUE);
      $data['id_customer']   = $this->input->post('id_customer', TRUE);
      $data['no_po']         = $this->input->post('no_po', TRUE);
      $data['dpp']           = $this->input->post('dpp', TRUE);
      $data['ppn']           = $this->input->post('ppn', TRUE);
      $data['pembayaran']    = $this->input->post('pembayaran', TRUE);
      $data['alasan']        = $this->input->post('alasan', TRUE);
      $data['link_lampiran'] = $this->input->post('link_lampiran', TRUE);
      $data['status']        = 0;
      $this->md_surat_new->addAppPajak($data);



      //send notif wa 
      $dataWa = [
        'idPenerima1'   => '33',
        'idPenerima2'   => '',
        'namaSurat'     => 'Approval Faktur Pajak',
        'penerima'       => '_General Manager_',
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);


      /** LOG */
      addLog('Approval Faktur Pajak', 'Permintaan Approval Faktur Pajak ' . $kodeFpp);
      ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
    }
  }


  //UPDATE

  public function ttd_setujui($param1 = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');

    if ($param1 == "kendaraan") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1'] = 1;
        $this->md_surat_new->updatekendaraan($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_new->getDetailKendaraanById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->keperluan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '33',
          'idPenerima2'   => '',
          'namaSurat'     => 'Permintaan Penggunaan Kendaraan Kantor',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'perihal'       => $perihal,
          'idpengaju'     => $idpengaju,
          'penerima'       => '_General Manager_',
          'ttd_sebelum1'   => 'Head of Technician, Media Technology, and Sec',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 1, $dataWa);

        addLog('Pengajuan Kendaraan', 'Permintaan kendaraan disetujui Head Tecnician');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {


        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_2'] = 1;
        $this->md_surat_new->updatekendaraan($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_new->getDetailKendaraanById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->keperluan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //Notif ke Wa Pengaju
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '',
          'idPenerima2'   => '',
          'namaSurat'     => 'Permintaan Penggunaan Kendaraan Kantor',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'perihal'       => $perihal,
          'idpengaju'     => $idpengaju,
          'ttd_sebelum1'   => 'Head of Technician, Media Technology, and Secr',
          'ttd_sebelum2'   => 'General Manager',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 2, $dataWa);

        /** LOG */
        addLog('Pengajuan Kendaraan', 'Permintaan Kendaraan Disetujui General Manager');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "po") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1'] = 1;
        $this->md_surat_new->updatePo($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getDetailPoById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;
        $jenis              = $ambilDataPengaju[0]->jenis;

        if ($jenis != 1) {
          $ttd_2      = 'HR and Legal';
          $ttd_3      = 'Director of Corporate Planning and Business Management';
        } else {
          $ttd_2      = 'Director of Corporate Planning and Business Management';
          $ttd_3      = '';
        }

        //Notif ke Wa Pengaju
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '',
          'idPenerima2'   => '',
          'namaSurat'     => 'Approval PO',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'perihal'       => $perihal,
          'idpengaju'     => $idpengaju,
          'ttd_sebelum1'   => 'General Manager',
          'ttd_sebelum2'   => $ttd_2,
          'ttd_sebelum3'   => $ttd_3
        ];

        $this->notifWaAprovPb(1, 2, $dataWa);

        addLog('Approval PO', 'Permintaan PO disetujui Director of Corporate Planning and Business Management');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd_2'] = 1;
        $this->md_surat_new->updatePo($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getDetailPoById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //Notif ke Wa Pengaju
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '23',
          'idPenerima2'   => '',
          'namaSurat'     => 'Approval PO',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'perihal'       => $perihal,
          'idpengaju'     => $idpengaju,
          'penerima'       => '_Director of Corporate Planning and Business Management_',
          'ttd_sebelum1'   => 'General Manager',
          'ttd_sebelum2'   => 'HR and Legal',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 1, $dataWa);

        addLog('Approval PO', 'Permintaan PO disetujui HR and Legal');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_3") {

        $id_sp = $this->input->post('id');
        $data['status'] = 5;
        $data['ttd_3'] = 1;
        $this->md_surat_new->updatePo($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getDetailPoById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;
        $jenis              = $ambilDataPengaju[0]->jenis;

        if ($jenis != 1) {
          $idPenerima = '69';
          $penerima   = '_HR and Legal_';
        } else {
          $idPenerima = '23';
          $penerima   = '_Director of Corporate Planning and Business Management_';
        }

        //Notif ke Wa Pengaju
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => $idPenerima,
          'idPenerima2'   => '',
          'namaSurat'     => 'Approval PO',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'perihal'       => $perihal,
          'idpengaju'     => $idpengaju,
          'penerima'       => $penerima,
          'ttd_sebelum1'   => 'General Manager',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 1, $dataWa);

        addLog('Approval PO', 'Permintaan PO disetujui HR and Legal');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "appeks") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1'] = 1;
        $this->md_surat_new->updateAppeks($id_sp, $data);

        /*
                $ambilDataPengaju 	= $this->md_surat_new->getAppeksDetailById($id_sp);
                $namaPengaju		    = $ambilDataPengaju[0]->pengaju;
                $kode               = $ambilDataPengaju[0]->kode;
                $perihal            = $ambilDataPengaju[0]->nama_customer;
                $idpengaju          = $ambilDataPengaju[0]->idPengaju;

             //send notif wa
			        $dataWa = [
                        'id' 	          => $id_sp,
                        'idPenerima1' 	=> '33',
                        'idPenerima2' 	=> '',
                        'namaSurat' 	  => 'Permintaan Approval Expedisi',
                        'namaPengaju' 	=> $namaPengaju,
                        'kode' 	        => $kode,
                        'perihal' 	    => $perihal,
                        'idpengaju' 	  => $idpengaju,
                        'penerima' 	    => '_General Manager_',
                        'ttd_sebelum1' 	=> 'Head of Technician, Media Technology, and Sec',
                        'ttd_sebelum2' 	=> '',
                        'ttd_sebelum3' 	=> ''
                    ];
                
                $this->notifWaAprovPb(1, 1, $dataWa);

         addLog('Approval Expedisi', 'Disetujui Head Tecnician Kode '.$kode);
         ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

       }elseif($param2 == "ttd_2"){ 
               
          
            $id_sp = $this->input->post('id');
            $data['status'] = 2;
            $data['ttd_1'] = 1;
            $this->md_surat_new->updateAppeks($id_sp, $data); */

        $ambilDataPengaju   = $this->md_surat_new->getAppeksDetailById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->nama_customer;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //Notif ke Wa Pengaju
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '',
          'idPenerima2'   => '',
          'namaSurat'     => 'Permintaan Approval Expedisi',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'perihal'       => $perihal,
          'idpengaju'     => $idpengaju,
          'ttd_sebelum1'   => 'Senior Accounting and Finance',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 2, $dataWa);

        /** LOG */
        addLog('Approval Expedisi', 'Disetujui Senior Accounting and Finance Kode ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "sta") {

      $id_sp = $this->input->post('id');
      $data['status'] = 2;
      $data['ttd'] = 1;
      $this->md_surat_new->updateSerah($id_sp, $data);

      $ambilDataPengaju   = $this->md_surat_new->getStaDetailById($id_sp);
      $namaPengaju        = $ambilDataPengaju[0]->pengaju;
      $kode               = $ambilDataPengaju[0]->kode;
      $idpengaju          = $ambilDataPengaju[0]->idPengaju;
      $namaTerima          = $ambilDataPengaju[0]->penerima;



      //send notif wa
      $dataWa = [
        'id'             => $id_sp,
        'idPenerima1'   => $idpengaju,
        'idPenerima2'   => '',
        'namaSurat'     => 'Serah Terima Aset',
        'namaPengaju'   => $namaPengaju,
        'kode'           => $kode,
        'idpengaju'     => $idpengaju,
        'penerima'       => $namaPengaju,
        'ttd_sebelum1'   => $namaTerima,
        'ttd_sebelum2'   => '',
        'ttd_sebelum3'   => ''
      ];

      $this->notifWaAprovPb(1, 1, $dataWa);

      addLog('Serah Terima Aset', 'Telah diterima ' . $kode);
      ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    } else if ($param1 == "stfp") {

      $id_sp = $this->input->post('id');
      $data['status'] = 2;
      $data['ttd'] = 1;
      $this->md_surat_new->updateSerah($id_sp, $data);

      $ambilDataPengaju   = $this->md_surat_new->getStaDetailById($id_sp);
      $namaPengaju        = $ambilDataPengaju[0]->pengaju;
      $kode               = $ambilDataPengaju[0]->kode;
      $idpengaju          = $ambilDataPengaju[0]->idPengaju;
      $namaTerima          = $ambilDataPengaju[0]->penerima;



      //send notif wa
      $dataWa = [
        'id'             => $id_sp,
        'idPenerima1'   => $idpengaju,
        'idPenerima2'   => '',
        'namaSurat'     => 'Serah Terima Fisik Perlengkapan',
        'namaPengaju'   => $namaPengaju,
        'kode'           => $kode,
        'idpengaju'     => $idpengaju,
        'penerima'       => $namaPengaju,
        'ttd_sebelum1'   => $namaTerima,
        'ttd_sebelum2'   => '',
        'ttd_sebelum3'   => ''
      ];

      $this->notifWaAprovPb(1, 1, $dataWa);

      addLog('Serah Terima Fisik Perlengkapan', 'Telah diterima ' . $kode);
      ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    } else if ($param1 == "stp") {
      if ($param2 == "terima") {

        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd'] = 1;
        $this->md_surat_new->updateSerah($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_new->getStaDetailById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;
        $iddiketahui        = $ambilDataPengaju[0]->id_diketahui;
        $namaTerima         = $ambilDataPengaju[0]->penerima;
        $namaKetahui        = $ambilDataPengaju[0]->mengetahui;



        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => $iddiketahui,
          'idPenerima2'   => '',
          'namaSurat'     => 'Serah Terima Pekerjaan',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'idpengaju'     => $idpengaju,
          'penerima'       => $namaKetahui,
          'ttd_sebelum1'   => $namaTerima,
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 1, $dataWa);

        addLog('Serah Terima Pekerjaan', 'Telah diterima ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
      if ($param2 == "ketahui") {

        $id_sp = $this->input->post('id');
        $data['status'] = 4;
        $data['ttd_1'] = 1;
        $this->md_surat_new->updateSerah($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_new->getStaDetailById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;
        $namaTerima         = $ambilDataPengaju[0]->penerima;
        $namaKetahui        = $ambilDataPengaju[0]->mengetahui;



        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => $idpengaju,
          'idPenerima2'   => '58',
          'namaSurat'     => 'Serah Terima Pekerjaan',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'idpengaju'     => $idpengaju,
          'penerima'       => $namaPengaju,
          'ttd_sebelum1'   => $namaTerima,
          'ttd_sebelum2'   => $namaKetahui,
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(2, 2, $dataWa);

        addLog('Serah Terima Pekerjaan', 'Telah diterima ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "spi") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd'] = 1;
        $this->md_surat_new->updateIstirahat($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_new->getIstirahatById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju2;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;
        $idpengguna         = $ambilDataPengaju[0]->idpengguna;



        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => $idpengaju,
          'idPenerima2'   => '',
          'namaSurat'     => 'Surat Skorsing',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'idpengaju'     => $idpengaju,
          'penerima'       => $namaPengaju,
          'ttd_sebelum1'   => '_HR and Legal_',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 2, $dataWa);


        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => $idpengguna,
          'idPenerima2'   => '',
          'namaSurat'     => 'Surat Skorsing',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'idpengaju'     => $idpengaju,
          'penerima'       => $namaPengaju,
          'ttd_sebelum1'   => '_HR and Legal_',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 3, $dataWa);

        addLog('Surat Skorsing', 'Telah disetujui ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "appdir") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd'] = 1;
        $this->md_surat_new->updateAppdir($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_new->getAppdirById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;
        $idpengguna         = $ambilDataPengaju[0]->idpengguna;

        if ($idpengaju == 769) {

          //send notif wa
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => $idpengaju,
            'idPenerima2'   => '',
            'namaSurat'     => 'Approval Director',
            'namaPengaju'   => $namaPengaju,
            'kode'           => $kode,
            'idpengaju'     => $idpengaju,
            'penerima'       => $namaPengaju,
            'ttd_sebelum1'   => '_HR and Legal_',
            'ttd_sebelum2'   => '',
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprovPb(1, 2, $dataWa);

          //send notif wa
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => '738',
            'idPenerima2'   => '',
            'namaSurat'     => 'Approval Director',
            'namaPengaju'   => $namaPengaju,
            'kode'           => $kode,
            'idpengaju'     => '738',
            'penerima'       => $namaPengaju,
            'ttd_sebelum1'   => '_HR and Legal_',
            'ttd_sebelum2'   => '',
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprovPb(1, 2, $dataWa);
        } else {

          //send notif wa
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => $idpengaju,
            'idPenerima2'   => '',
            'namaSurat'     => 'Approval Director',
            'namaPengaju'   => $namaPengaju,
            'kode'           => $kode,
            'idpengaju'     => $idpengaju,
            'penerima'       => $namaPengaju,
            'ttd_sebelum1'   => '_HR and Legal_',
            'ttd_sebelum2'   => '',
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprovPb(1, 2, $dataWa);
        }

        addLog('Approval Director', 'Telah disetujui ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "app_pajak") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1'] = 1;
        $this->md_surat_new->updateAppPajak($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_new->getAppPajakById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->nama_pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '64',
          'idPenerima2'   => '',
          'namaSurat'     => 'Approval Faktur Pajak',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'idpengaju'     => $idpengaju,
          'penerima'       => 'Senior Tax',
          'ttd_sebelum1'   => '_General Manager_',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];
        $this->notifWaAprovPb(1, 1, $dataWa);

        addLog('Approval Faktur Pajak', 'Telah disetujui ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_2'] = 1;
        $this->md_surat_new->updateAppPajak($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_new->getAppPajakById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->nama_pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '23',
          'idPenerima2'   => '',
          'namaSurat'     => 'Approval Faktur Pajak',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'idpengaju'     => $idpengaju,
          'penerima'       => 'Director of Corporate Planning and Business Management',
          'ttd_sebelum1'   => '_General Manager_',
          'ttd_sebelum2'   => '_Senior Tax_',
          'ttd_sebelum3'   => ''
        ];
        $this->notifWaAprovPb(1, 1, $dataWa);

        addLog('Approval Faktur Pajak', 'Telah disetujui ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_3") {

        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd_3'] = 1;
        $this->md_surat_new->updateAppPajak($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_new->getAppPajakById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->nama_pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '54',
          'idPenerima2'   => '',
          'namaSurat'     => 'Approval Faktur Pajak',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'idpengaju'     => $idpengaju,
          'penerima'       => 'Director',
          'ttd_sebelum1'   => '_General Manager_',
          'ttd_sebelum2'   => '_Senior Tax_',
          'ttd_sebelum3'   => '_Director of Corporate Planning and Business Management_'
        ];
        $this->notifWaAprovPb(1, 1, $dataWa);

        addLog('Approval Faktur Pajak', 'Telah disetujui ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_4") {

        $id_sp = $this->input->post('id');
        $data['status'] = 4;
        $data['ttd_4'] = 1;
        $this->md_surat_new->updateAppPajak($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_new->getAppPajakById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->nama_pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => $idpengaju,
          'idPenerima2'   => '',
          'namaSurat'     => 'Approval Faktur Pajak',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'idpengaju'     => $idpengaju,
          'penerima'       => $namaPengaju,
          'ttd_sebelum1'   => '_General Manager_',
          'ttd_sebelum2'   => '_Senior Tax_',
          'ttd_sebelum3'   => '_Director of Corporate Planning and Business Management -Director_'
        ];
        $this->notifWaAprovPb(1, 2, $dataWa);

        addLog('Approval Faktur Pajak', 'Telah disetujui ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    }
  }


  public function ttd_tolak($param1 = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');

    if ($param1 == "kendaraan") {
      if ($param2 == "ttd_1") {


        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd_1'] = 2;
        $this->md_surat_new->updatekendaraan($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getDetailKendaraanById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->keperluan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Permintaan Penggunaan Kendaraan Kantor',
          'id'       => $id_sp,
          'idPenolak'     => 15,
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'perihal'   => $perihal,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '*Kardonal*'
        ];
        $this->notifWaRejectPb($dataWa);
        /** LOG */
        addLog('Pengajuan Kendaraan', 'Permintaan Kendaraan Ditolak Head technician');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 4;
        $data['ttd_2'] = 2;
        $this->md_surat_new->updatekendaraan($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getDetailKendaraanById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->keperluan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Permintaan Penggunaan Kendaraan Kantor',
          'id'       => $id_sp,
          'idPenolak'     => 33,
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'perihal'   => $perihal,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '*General Manager*'
        ];
        $this->notifWaRejectPb($dataWa);


        /** LOG */
        addLog('Pengajuan Kendaraan', 'Permintaan Kendaraan Ditolak oLeh general manager');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    }
    if ($param1 == "po") {
      if ($param2 == "ttd_1") {


        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_1']  = 2;
        $this->md_surat_new->updatePo($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_new->getDetailPoById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Approval PO',
          'id'       => $id_sp,
          'idPenolak'     => 23,
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'perihal'   => $perihal,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '*Director of Corporate Planning and Business Management*'
        ];
        $this->notifWaRejectPb($dataWa);
        /** LOG */
        addLog('Approval PO', 'Permintaan PO Ditolak Director of Corporate Planning and Business Management');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_2") {


        $id_sp = $this->input->post('id');
        $data['status'] = 4;
        $data['ttd_2']  = 2;
        $this->md_surat_new->updatePo($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_new->getDetailPoById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Approval PO',
          'id'       => $id_sp,
          'idPenolak'     => 69,
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'perihal'   => $perihal,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '*HR and Legal*'
        ];
        $this->notifWaRejectPb($dataWa);
        /** LOG */
        addLog('Approval PO', 'Permintaan PO Ditolak HR and Legal');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_3") {


        $id_sp = $this->input->post('id');
        $data['status'] = 6;
        $data['ttd_3']  = 2;
        $this->md_surat_new->updatePo($id_sp, $data);

        $ambilDataPengaju   = $this->md_surat_new->getDetailPoById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Approval PO',
          'id'       => $id_sp,
          'idPenolak'     => 33,
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'perihal'   => $perihal,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '*General Manager*'
        ];
        $this->notifWaRejectPb($dataWa);
        /** LOG */
        addLog('Approval PO', 'Permintaan PO Ditolak GM');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "appeks") {
      if ($param2 == "ttd_1") {


        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd_1'] = 2;
        $this->md_surat_new->updateAppeks($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getAppeksDetailById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->nama_customer;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Permintaan Approval Expedisi',
          'id'       => $id_sp,
          'idPenolak'     => 107,
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'perihal'   => $perihal,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '*Senior Accounting and Finance*'
        ];
        $this->notifWaRejectPb($dataWa);
        /** LOG */
        addLog('Approval Expedisi', 'Ditolak Senior Accounting and Finance');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 4;
        $data['ttd_2'] = 2;
        $this->md_surat_new->updateAppeks($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getAppeksDetailById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->nama_customer;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Permintaan Approval Expedisi',
          'id'       => $id_sp,
          'idPenolak'     => 33,
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'perihal'   => $perihal,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '*General Manager*'
        ];
        $this->notifWaRejectPb($dataWa);


        /** LOG */
        addLog('Approval Expedisi', 'Permintaan Kendaraan Ditolak oLeh general manager');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "sta") {


      $id_sp = $this->input->post('id');
      $data['status'] = 3;
      $data['ttd'] = 2;
      $this->md_surat_new->updateSerah($id_sp, $data);


      $ambilDataPengaju   = $this->md_surat_new->getStaDetailById($id_sp);
      $namaPengaju        = $ambilDataPengaju[0]->pengaju;
      $kode               = $ambilDataPengaju[0]->kode;
      $idpengaju          = $ambilDataPengaju[0]->idPengaju;
      $namaTerima          = $ambilDataPengaju[0]->penerima;
      $idterima           = $ambilDataPengaju[0]->id_terima;


      //send notif wa
      $dataWa = [
        'namaSurat'     => 'Serah Terima Aset',
        'id'       => $id_sp,
        'idPenolak'     => $idterima,
        'namaPengaju'   => $namaPengaju,
        'kode'  => $kode,
        'perihal'   => $perihal,
        'idpengaju'   => $idpengaju,
        'namaPenolak'   => $namaTerima
      ];
      $this->notifWaRejectPb($dataWa);
      /** LOG */
      addLog('Serah Terima Aset', 'Permintaan Ditolak');
      ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    } else if ($param1 == "stfp") {


      $id_sp = $this->input->post('id');
      $data['status'] = 3;
      $data['ttd'] = 2;
      $this->md_surat_new->updateSerah($id_sp, $data);


      $ambilDataPengaju   = $this->md_surat_new->getStaDetailById($id_sp);
      $namaPengaju        = $ambilDataPengaju[0]->pengaju;
      $kode               = $ambilDataPengaju[0]->kode;
      $idpengaju          = $ambilDataPengaju[0]->idPengaju;
      $namaTerima          = $ambilDataPengaju[0]->penerima;
      $idterima           = $ambilDataPengaju[0]->id_terima;


      //send notif wa
      $dataWa = [
        'namaSurat'     => 'Serah Terima Fisik Perlengkapan',
        'id'       => $id_sp,
        'idPenolak'     => $idterima,
        'namaPengaju'   => $namaPengaju,
        'kode'  => $kode,
        'perihal'   => $perihal,
        'idpengaju'   => $idpengaju,
        'namaPenolak'   => $namaTerima
      ];
      $this->notifWaRejectPb($dataWa);
      /** LOG */
      addLog('Serah Terima Fisik Perlengkapan', 'Permintaan Ditolak');
      ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    } else if ($param1 == "stp") {
      if ($param2 == "terima") {

        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd'] = 2;
        $this->md_surat_new->updateSerah($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getStaDetailById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;
        $namaTerima          = $ambilDataPengaju[0]->penerima;
        $idterima           = $ambilDataPengaju[0]->id_terima;


        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Serah Terima Pekerjaan',
          'id'       => $id_sp,
          'idPenolak'     => $idterima,
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'perihal'   => $perihal,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => $namaTerima
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Serah Terima Pekerjaan', 'Permintaan Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ketahui") {

        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd_1'] = 2;
        $this->md_surat_new->updateSerah($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getStaDetailById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;
        $namaTerima          = $ambilDataPengaju[0]->mengetahui;
        $idterima           = $ambilDataPengaju[0]->id_diketahui;


        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Serah Terima Pekerjaan',
          'id'       => $id_sp,
          'idPenolak'     => $idterima,
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'perihal'   => $perihal,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => $namaTerima
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Serah Terima Pekerjaan', 'Permintaan Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "spi") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd'] = 2;
        $this->md_surat_new->updateIstirahat($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getIstirahatById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju2;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;


        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Serah Terima Pekerjaan',
          'id'       => $id_sp,
          'idPenolak'     => '69',
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '_HR and Legal_'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Surat Skorsing', 'Permintaan Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "appdir") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd'] = 2;
        $this->md_surat_new->updateAppdir($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getAppdirById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;


        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Approval Director',
          'id'       => $id_sp,
          'idPenolak'     => '69',
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '_HR and Legal_'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Approval Director', 'Permintaan Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "app_pajak") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 5;
        $data['ttd_1'] = 2;
        $this->md_surat_new->updateAppPajak($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getAppPajakById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->nama_pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;


        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Approval Faktur Pajak',
          'id'       => $id_sp,
          'idPenolak'     => '33',
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '_General Manager_'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Approval Faktur Pajak', 'Permintaan Ditolak ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 5;
        $data['ttd_2'] = 2;
        $this->md_surat_new->updateAppPajak($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getAppPajakById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->nama_pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;


        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Approval Faktur Pajak',
          'id'       => $id_sp,
          'idPenolak'     => '64',
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '_Senior Tax_'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Approval Faktur Pajak', 'Permintaan Ditolak ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_3") {

        $id_sp = $this->input->post('id');
        $data['status'] = 5;
        $data['ttd_3'] = 2;
        $this->md_surat_new->updateAppPajak($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getAppPajakById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->nama_pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;


        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Approval Faktur Pajak',
          'id'       => $id_sp,
          'idPenolak'     => '23',
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '_Director of Corporate Planning and Business Management_'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Approval Faktur Pajak', 'Permintaan Ditolak ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_3") {

        $id_sp = $this->input->post('id');
        $data['status'] = 5;
        $data['ttd_3'] = 2;
        $this->md_surat_new->updateAppPajak($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getAppPajakById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->nama_pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;


        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Approval Faktur Pajak',
          'id'       => $id_sp,
          'idPenolak'     => '23',
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '_Director of Corporate Planning and Business Management_'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Approval Faktur Pajak', 'Permintaan Ditolak ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_4") {

        $id_sp = $this->input->post('id');
        $data['status'] = 5;
        $data['ttd_4'] = 2;
        $this->md_surat_new->updateAppPajak($id_sp, $data);


        $ambilDataPengaju   = $this->md_surat_new->getAppPajakById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->nama_pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;


        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Approval Faktur Pajak',
          'id'       => $id_sp,
          'idPenolak'     => '54',
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '_Director_'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Approval Faktur Pajak', 'Permintaan Ditolak ' . $kode);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    }
  }


  public function updateApprovalEks()
  {
    grantAccessFor('all');

    //update detail Stok Opname
    $id_sodetail = $this->input->post('id_sodetail');
    foreach ($id_sodetail as $key => $row) {
      $data['approval'] = $this->input->post('approval')[$key];


      //$this->md_surat_new->updateSoDetail(['id' => decrypt($row)], $data);
      $this->md_surat_new->updateAppeksDetail(decrypt($row), $data);
    }
    //add log
    $aksi = 'Aprroval Expedisi';
    $ket = 'Update Approval Expedisi';
    addlog($aksi, $ket);

    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
  }

  public function UpdateAppeksFinance($id_sp)
  {
    grantAccessFor('all');

    $catatan_finance = $this->input->post('catatan_finance');

    $data = [
      'catatan_finance' => nl2br($catatan_finance),
    ];

    $this->md_surat_new->updateAppeks($id_sp, $data);


    /** LOG */
    addLog('Approval Expedisi ', 'Submit Catatan Finance');
    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
  }

  public function editItem($param1)
  {
    grantAccessFor('all');
    $id = decrypt($param1);
    $dt = $this->md_surat_new->getDetailApprovalById($id);
    foreach ($dt as $row) {
      $row->id = encrypt($row->id);
    }
    echo json_encode($dt);
    die;
  }

  public function updateItemApproval()
  {
    grantAccessFor('all');

    $id = decrypt($this->input->post('id'));
    $approval   = "1";


    $data = [
      'approval' => $approval,
    ];

    $this->md_surat_new->updateAppeksDetail($id, $data);

    // Debugging
    addLog('Update approval Expedisi', 'Item ' . $id);
    ajaxReturnDie('success', 'Approve diubah', TRUE);
  }

  public function updateItemApprovalTolak()
  {
    grantAccessFor('all');

    $id = decrypt($this->input->post('id'));
    $approval   = "2";

    $data = [
      'approval' => $approval,
    ];

    $this->md_surat_new->updateAppeksDetail($id, $data);

    // Debugging
    addLog('Update approval', 'Item ' . $id);
    ajaxReturnDie('success', 'Approve diubah', TRUE);
  }


  public function submitCatatanPo($id)
  {
    grantAccessFor('all');

    $catatan     = $this->input->post('catatan', TRUE);

    $data = [
      'catatan' => nl2br($catatan),
    ];

    $this->md_surat_new->updatePo($id, $data);

    // Debugging
    addLog('Update PO', 'Submit catatan PO ' . $catatan);
    ajaxReturnDie('success', 'catatan berhasil ditambahkan', TRUE);
  }

  public function submitCatatanPoHR($id)
  {
    grantAccessFor('all');

    $catatan_hr     = $this->input->post('catatan_hr', TRUE);

    $data = [
      'catatan_hr' => nl2br($catatan_hr),
    ];

    $this->md_surat_new->updatePo($id, $data);

    // Debugging
    addLog('Update PO', 'Submit catatan PO ' . $catatan_hr);
    ajaxReturnDie('success', 'catatan berhasil ditambahkan', TRUE);
  }

  public function submitCatatanPoGM($id)
  {
    grantAccessFor('all');

    $catatan_gm     = $this->input->post('catatan_gm', TRUE);

    $data = [
      'catatan_gm' => nl2br($catatan_gm),
    ];

    $this->md_surat_new->updatePo($id, $data);

    // Debugging
    addLog('Update PO', 'Submit catatan PO ' . $catatan_gm);
    ajaxReturnDie('success', 'catatan berhasil ditambahkan', TRUE);
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






  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  // pagination -------------------------------------------------------------------------------------------------------------------------------------------------------
  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------

  public function pagination($param = "", $param2 = "")
  {
    grantAccessFor('all');
    if ($param == 'permintaan_kendaraan') {
      $dt     = $this->md_surat_new->getKendaraanByID(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/kendaraan/' . $row->idGc . '">' . $row->kode . '</a>';

        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Head of Technician, Media Technology, and Sec</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = $row->lama;

        $pukul = $mulai . ' Hari ';



        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = $row->keperluan;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $pukul;
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_kendaraan') {
      $dt     = $this->md_surat_new->getAllKendaraan();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/kendaraan/' . $row->idGc . '">' . $row->kode . '</a>';
        $aksi = '';
        if (isAdmin()) {
          $aksi = '<a href="javascript:void(0)" onclick="openAdminEditModal(\'kendaraan\', \'' . encrypt($row->idGc) . '\')" class="btn btn-sm btn-warning" title="Edit Data (Admin)"><i class="fas fa-pencil-alt text-dark"></i></a>';
        }

        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Head of Technician, Media Technology, and Sec</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $mulai = $row->lama;

        $pukul = $mulai . ' Hari ';



        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = $row->keperluan;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $pukul;
        $th[] = $stat_surat;
        $th[] = $aksi;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'permintaan_po') {
      $dt     = $this->md_surat_new->getPoByID(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/po/' . $row->idGc . '">' . $row->kode . '</a>';

        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Director of Corporate Planning and Business Management</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else if ($row->status == "5") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }




        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->identitas_pelanggan;
        $th[] = $row->no_po;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->pengaju;
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_po') {
      $dt     = $this->md_surat_new->getAllPo();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/po/' . $row->idGc . '">' . $row->kode . '</a>';
        $aksi = '';
        if (isAdmin()) {
          $aksi = '<a href="javascript:void(0)" onclick="openAdminEditModal(\'po\', \'' . encrypt($row->idGc) . '\')" class="btn btn-sm btn-warning" title="Edit Data (Admin)"><i class="fas fa-pencil-alt text-dark"></i></a>';
        }

        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Director of Corporate Planning and Business Management</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else if ($row->status == "5") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->identitas_pelanggan;
        $th[] = $row->no_po;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->pengaju;
        $th[] = $stat_surat;
        $th[] = $aksi;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_appeks') {
      $dt     = $this->md_surat_new->getAllAppeks();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/appeks/' . $row->idGc . '">' . $row->kode . '</a>';
        $aksi = '';
        if (isAdmin()) {
          $aksi = '<a href="javascript:void(0)" onclick="openAdminEditModal(\'appeks\', \'' . encrypt($row->idGc) . '\')" class="btn btn-sm btn-warning" title="Edit Data (Admin)"><i class="fas fa-pencil-alt text-dark"></i></a>';
        }

        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Pengajuan Disetujui</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Pengajuan Disetujui</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->nama_customer;
        $th[] = $row->tujuan;
        $th[] = $row->nama_barang;
        $th[] = $row->no_sj;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->pengaju;
        $th[] = $stat_surat;
        $th[] = $aksi;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'appeks') {
      //$dt     = $this->md_surat_new->getAppeksByID(sessPenggunaId());
      $dt     = $this->md_surat_new->getAllAppeks();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/appeks/' . $row->idGc . '">' . $row->kode . '</a>';

        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Pengajuan Disetujui</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Pengajuan Disetujui</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->nama_customer;
        $th[] = $row->tujuan;
        $th[] = $row->nama_barang;
        $th[] = $row->no_sj;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->pengaju;
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_sta') {

      if (sessPenggunaId() == '1' || sessPenggunaId() == '23' || sessPenggunaId() == '33' || isGa()) {
        $dt     = $this->md_surat_new->getAllSta();
      } else {
        $dt     = $this->md_surat_new->getSta2ByID(sessPenggunaId());
      }
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/sta/' . $row->idGc . '">' . $row->kode . '</a>';

        if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Diterima</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->pengaju;
        $th[] = $row->penerima;
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'sta') {
      $dt     = $this->md_surat_new->getStaByID(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/sta/' . $row->idGc . '">' . $row->kode . '</a>';

        if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Diterima</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->pengaju;
        $th[] = $row->penerima;
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_stfp') {

      if (sessPenggunaId() == '1' || sessPenggunaId() == '23' || sessPenggunaId() == '33' || isGa()) {
        $dt     = $this->md_surat_new->getAllStfp();
      } else {
        $dt     = $this->md_surat_new->getStfp2ByID(sessPenggunaId());
      }
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/stfp/' . $row->idGc . '">' . $row->kode . '</a>';

        if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Diterima</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->pengaju;
        $th[] = $row->penerima;
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'stfp') {
      $dt     = $this->md_surat_new->getStfpByID(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/stfp/' . $row->idGc . '">' . $row->kode . '</a>';

        if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Diterima</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->pengaju;
        $th[] = $row->penerima;
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_stp') {

      if (sessPenggunaId() == '1' || sessPenggunaId() == '23' || sessPenggunaId() == '33' || isGa()) {
        $dt     = $this->md_surat_new->getAllStp();
      } else {
        $dt     = $this->md_surat_new->getStp2ByID(sessPenggunaId());
      }
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/stp/' . $row->idGc . '">' . $row->kode . '</a>';

        if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh ' . $row->penerima . ' </span>';
        } else if ($row->status == "4") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh ' . $row->mengetahui . ' </span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->pengaju;
        $th[] = $row->penerima;
        $th[] = $row->mengetahui;
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'stp') {
      $dt     = $this->md_surat_new->getStpByID(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/stp/' . $row->idGc . '">' . $row->kode . '</a>';

        if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh' . $row->penerima . ' </span>';
        } else if ($row->status == "4") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh' . $row->mengetahui . ' </span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->pengaju;
        $th[] = $row->penerima;
        $th[] = $row->mengetahui;
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_spi') {

      $dt     = $this->md_surat_new->getAllIstirahat();

      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/spi/' . $row->idGc . '">' . $row->kode . '</a>';

        if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR and Legal </span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->penerima;
        $th[] = $row->masa;
        $th[] = $row->pengaju;
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_appdir') {
      $dt     = $this->md_surat_new->getAllAppdir();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/appdir/' . $row->idGc . '">' . $row->kode . '</a>';
        $aksi = '';
        if (isAdmin()) {
          $aksi = '<a href="javascript:void(0)" onclick="openAdminEditModal(\'appdir\', \'' . encrypt($row->idGc) . '\')" class="btn btn-sm btn-warning" title="Edit Data (Admin)"><i class="fas fa-pencil-alt text-dark"></i></a>';
        }

        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        if ($row->jenis == "1") {
          $jenis = 'Tempel (Soft File)';
        } else if ($row->jenis == "2") {
          $jenis = 'Cap Basah (Hard File)';
        }


        $link = '<a href="' . $row->link . '" target="blank"><i class="fas fa-link"></i> Link</a>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->nama_dokumen;
        $th[] = $row->ket;
        $th[] = $link;
        $th[] = $jenis;
        $th[] = $row->pengaju;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->created_at));
        $th[] = $stat_surat;
        $th[] = $aksi;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'appdir') {
      $dt     = $this->md_surat_new->getAllAppdirById(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/appdir/' . $row->idGc . '">' . $row->kode . '</a>';

        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        if ($row->jenis == "1") {
          $jenis = 'Tempel (Soft File)';
        } else if ($row->jenis == "2") {
          $jenis = 'Cap Basah (Hard File)';
        }

        $link = '<a href="' . $row->link . '" target="blank"><i class="fas fa-link"></i> Link</a>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->nama_dokumen;
        $th[] = $row->ket;
        $th[] = $link;
        $th[] = $jenis;
        $th[] = $row->pengaju;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->created_at));
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'app_pajak') {
      $dt     = $this->md_surat_new->getAllAppPajakById(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/app_pajak/' . $row->idFp . '">' . $row->kode . '</a>';

        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Senior Tax</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director of Corporate Planning and Business Management </span>';
        } else if ($row->status == "4") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $link = '<a href="' . $row->link_lampiran . '" target="blank"><i class="fas fa-link"></i> Link</a>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->nama_customer;
        $th[] = $row->no_po;
        $th[] = $row->marketing;
        $th[] = $row->pembayaran;
        $th[] = $row->pengaju;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->created_at));
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_app_pajak') {
      $dt     = $this->md_surat_new->getAllAppPajak();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="surat_new/show/detail/app_pajak/' . $row->idFp . '">' . $row->kode . '</a>';
        $aksi = '';
        if (isAdmin()) {
          $aksi = '<a href="javascript:void(0)" onclick="openAdminEditModal(\'app_pajak\', \'' . encrypt($row->idFp) . '\')" class="btn btn-sm btn-warning" title="Edit Data (Admin)"><i class="fas fa-pencil-alt text-dark"></i></a>';
        }

        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Senior Tax</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director of Corporate Planning and Business Management </span>';
        } else if ($row->status == "4") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $link = '<a href="' . $row->link_lampiran . '" target="blank"><i class="fas fa-link"></i> Link</a>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->nama_customer;
        $th[] = $row->no_po;
        $th[] = $row->marketing;
        $th[] = $row->pembayaran;
        $th[] = $row->pengaju;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->created_at));
        $th[] = $stat_surat;
        $th[] = $aksi;
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
        'namaSurat'   => urlencode($detail['namaSurat']),
        'noPenerima'   => $nope,
        'kodeSurat'   => $detail['kode'],
        'namaPengaju' => urlencode($namaPengaju),
        'perihal'     => urlencode($detail['perihal']),
        'namaPenerima'   => urlencode($detail['penerima'])
      ];
      waSuratOpen($dataWa);
    }
  }


  public function notifWaAprovPb($ulang, $param, $detail)
  {
    //ambil data pengaju
    //$ambilDataPengaju 	= $this->md_surat_new->getDetailKendaraanById($detail['id']);
    //$namaPengaju		    = $ambilDataPengaju[0]->pengaju;
    //$kode               = $ambilDataPengaju[0]->kode;
    //$perihal            = $ambilDataPengaju[0]->keperluan;
    //$idpengaju          = $ambilDataPengaju[0]->idPengaju;



    //send notif wa
    for ($i = 1; $i <= $ulang; $i++) {
      if ($i == 1) {
        //id pengaju surat
        if ($param == 2) {
          $idpenerima = $detail['idpengaju'];
        } else {
          $idpenerima = $detail['idPenerima1'];
        }
      } else if ($i == 2) {
        //id ???
        $idpenerima = $detail['idPenerima2'];
      }

      $dataPenerima   = $this->md_pengguna->getById($idpenerima);
      $nope           = $dataPenerima[0]->no_hp;
      $dataWa = [
        'namaSurat'     => $detail['namaSurat'],
        'noPenerima'    => $nope,
        'kodeSurat'     => $detail['kode'],
        'namaPengaju'   => urlencode($detail['namaPengaju']),
        'namaPenerima'   => urlencode($detail['penerima']),
        'perihal'       => $detail['perihal'],
        'link'     => $detail['id'],
        'ttd_sebelum1'   => urlencode($detail['ttd_sebelum1']),
        'ttd_sebelum2'   => $detail['ttd_sebelum2'],
        'ttd_sebelum3'   => $detail['ttd_sebelum3'],
        'ttd_sebelum4'   => ''
      ];

      if ($param == '1') {
        waSuratAprovOnProg($dataWa);
      } else if ($param == '2') {
        waSuratAprovAll($dataWa);
      } else if ($param == '3') {
        waSuratAprovAllSkors($dataWa);
      }
    }
  }






  public function notifWaRejectPb($detail)
  {
    //ambil data pengaju
    //$ambilDataPengaju 	= $this->md_surat_new->getDetailKendaraanById($detail['id']);
    //$namaPengaju		    = $ambilDataPengaju[0]->pengaju;
    //$kode               = $ambilDataPengaju[0]->kode;
    //$perihal            = $ambilDataPengaju[0]->keperluan;
    //$idpengaju          = $ambilDataPengaju[0]->idPengaju;

    //if($idpengaju == 1){
    //   $idpengaju == 63;
    //}

    //ambil nomor
    $dataPenerima   = $this->md_pengguna->getById($detail['idpengaju']);
    $nope           = $dataPenerima[0]->no_hp;
    $dataPenolak     = $this->md_pengguna->getById($detail['idPenolak']);
    $nopePenolak    = $dataPenolak[0]->no_hp;

    //send notif wa
    $dataWa = [
      'namaSurat'   => $detail['namaSurat'],
      'noPenerima'   => $nope,
      'kodeSurat'   => $detail['kode'],
      'namaPengaju' => $detail['namaPengaju'],
      'perihal'     => $detail['perihal'],
      'namaPenolak' => urlencode($detail['namaPenolak']),
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

    if ($param1 == 'kendaraan') {

      $gc              = $this->md_surat_new->getDetailKendaraanById($param2);

      $dt = [
        'title_pdf'  => 'kend',
        'object'  => $param1,
        'data_kend'  => $this->md_surat_new->getDetailKendaraanById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Permintaan Penggunaan Kendaraan Kantor  ' . $gc[0]->pengaju;

      // page html yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_kendaraan', $dt, true);

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'po') {

      $gc              = $this->md_surat_new->getDetailPoById($param2);

      $dt = [
        'title_pdf'  => 'po',
        'object'  => $param1,
        'data_po'  => $this->md_surat_new->getDetailPoById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Approval PO ' . $gc[0]->identitas_pelanggan;

      // page html yang akan di jadikan ke pdf

      if ($gc[0]->idGc > 209) {  //209
        $html = $this->load->view('pages/v_print/print_heldesk_po', $dt, true);
      } else {
        $html = $this->load->view('pages/v_print/print_heldesk_po_1', $dt, true);
      }

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'appeks') {

      $gc               = $this->md_surat_new->getAppeksDetailById($param2);

      $dt = [
        'title_pdf'    => 'appeks',
        'object'      => $param1,
        'data_aprv'    => $this->md_surat_new->getAppeksDetailById($param2),
        'detail_aprv'  => $this->md_surat_new->getDetailAppById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Approval Expedisi ' . $gc[0]->nama_customer;

      // page html yang akan di jadikan ke pdf
      //$html = $this->load->view('pages/v_print/print_appeks', $dt, true);
      if ($gc[0]->idGc > 2) {
        $html = $this->load->view('pages/v_print/print_appeks', $dt, true);
      } else {
        $html = $this->load->view('pages/v_print/print_appeks_1', $dt, true);
      }

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'sta') {

      $gc               = $this->md_surat_new->getStaDetailById($param2);

      $dt = [
        'title_pdf'    => 'sta',
        'object'      => $param1,
        'data_serah'    => $this->md_surat_new->getStaDetailById($param2),
        'detail_serah'  => $this->md_surat_new->getDetailSerahById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Serah Terima Aset ' . $gc[0]->kode;

      // page html yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_sta', $dt, true);

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'stfp') {

      $gc               = $this->md_surat_new->getStaDetailById($param2);

      $dt = [
        'title_pdf'    => 'sta',
        'object'      => $param1,
        'data_serah'    => $this->md_surat_new->getStaDetailById($param2),
        'detail_serah'  => $this->md_surat_new->getDetailSerahById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Serah Terima Fisik Perlengkapan ' . $gc[0]->kode;

      // page html yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_stfp', $dt, true);

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'stp') {

      $gc               = $this->md_surat_new->getStaDetailById($param2);

      $dt = [
        'title_pdf'    => 'sta',
        'object'      => $param1,
        'data_serah'    => $this->md_surat_new->getStaDetailById($param2),
        'detail_serah'  => $this->md_surat_new->getDetailSerahById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Serah Terima Pekerjaan ' . $gc[0]->kode;

      // page html yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_stp', $dt, true);

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'spi') {

      $gc               = $this->md_surat_new->getIstirahatById($param2);

      $dt = [
        'title_pdf'    => 'sta',
        'object'      => $param1,
        'data_istirahat'    => $this->md_surat_new->getIstirahatById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Surat Skorsing ' . $gc[0]->kode;

      // page html yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_spi', $dt, true);

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'app_pajak') {

      $gc               = $this->md_surat_new->getAppPajakById($param2);

      $dt = [
        'title_pdf'    => 'sta',
        'object'      => $param1,
        'data_pajak'  => $this->md_surat_new->getAppPajakById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Approval Faktur Pajak ' . $gc[0]->kode;

      // page html yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_app_pajak', $dt, true);

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    }
  }
}
