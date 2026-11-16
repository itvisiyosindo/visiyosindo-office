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

class Fpp extends CI_Controller
{

  function id_navbar()
  {
    $id_navbar = "marketing";
    return $id_navbar;
  }

  public function index()
  {
    grantAccessFor('all');

    $page_data['switch']        = $this->id_navbar();
    $page_data['page_name']     = 'surat/v_surat_list';
    $page_data['page_title']    = 'Data Permintaan Penawaran';
    $page_data['page_desc']     = 'Management Permintaan Penawaran';
    $this->load->view('index', $page_data);
  }


  function __construct()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
    $this->load->model('md_kategori_tiket');
    $this->load->model('md_tiket');
    $this->load->model('md_fpp');
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
    if ($param == 'detail_fpp') {
      if ($param2 == 'penawaran') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['data_gc']        = $this->md_surat_list->getGcById($param3);
        $page_data['detail_gc']      = $this->md_surat_list->getDetailGc($param3);
        $page_data['data_fpp']      = $this->md_fpp->getFppById($param3);
        $page_data['detail_fpp']    = $this->md_fpp->getFppDetailById($param3);
        $page_data['masternotifikasi'] = $this->md_surat_list->getmasternotifikasi(3);

        //Versi Terbaru
        $fpp = $this->md_fpp->getFppById($param3);
        if ($fpp[0]->idFpp > 1804) {
          $page_data['page_name']     = 'marketing/v_detail_fpp';
        } else {
          $page_data['page_name']     = 'marketing/v_detail_fpp_1';
        }
        $page_data['page_title']    = 'FPP';
        $page_data['page_desc']     = 'Detail Permintaan Penawaran';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'presentase') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['data_presentase']      = $this->md_fpp->getPresentaseById($param3);
        $page_data['detail_presentase']    = $this->md_fpp->getPresentaseDetailById($param3);
        $page_data['page_name']     = 'marketing/v_detail_presentase';
        $page_data['page_title']    = 'Presentase';
        $page_data['page_desc']     = 'Detail Permintaan Presentation / Training User / Demo Request';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'trouble') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['data_trouble']      = $this->md_fpp->getTroubleById($param3);
        $page_data['detail_trouble']    = $this->md_fpp->getTroubleDetailById($param3);
        $page_data['page_name']     = 'marketing/v_detail_trouble';
        $page_data['page_title']    = 'Trouble / Installation';
        $page_data['page_desc']     = 'Detail Permintaan Trouble / Installation Request';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'permintaan') {
      if ($param2 == 'penawaran') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_gc']    = $this->md_surat_list->getGcById($param3);
        $page_data['detail_gc']    = $this->md_surat_list->getDetailGc($param3);
        $page_data['nama_marketing']  = $this->md_pengguna->getPenggunaMarketing();
        $page_data['page_name']     = 'marketing/v_pengajuan_fpp';
        $page_data['page_title']    = 'FPP';
        $page_data['page_desc']     = 'Daftar Permintaan Penawaran Diajukan';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'presentase') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['nama_marketing']  = $this->md_pengguna->getPenggunaMarketing();
        $page_data['page_name']     = 'marketing/v_pengajuan_presentase';
        $page_data['page_title']    = 'Presentase';
        $page_data['page_desc']     = 'Detail Permintaan Presentation / Training User / Demo Request';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'trouble') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['nama_marketing']  = $this->md_pengguna->getPenggunaMarketing();
        $page_data['page_name']     = 'marketing/v_pengajuan_trouble';
        $page_data['page_title']    = 'Trouble / Installation';
        $page_data['page_desc']     = 'Detail Permintaan Trouble / Installation Request';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'marketing') {
      if ($param2 == 'penawaran') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_gc']    = $this->md_surat_list->getGcById($param3);
        $page_data['detail_gc']    = $this->md_surat_list->getDetailGc($param3);
        $page_data['page_name']     = 'marketing/v_pengajuan_fpp_marketing';
        $page_data['page_title']    = 'FPP';
        $page_data['page_desc']     = 'Daftar Permintaan Penawaran yang Anda Ajukan';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'list') {
      if ($param2 == 'gc') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'marketing/v_surat_list_fpp';
        $page_data['page_title']    = 'Data Permintaan Penawaran';
        $page_data['page_desc']     = 'Daftar Permintaan Penawaran Yang diajukan';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'pengajuan') {
      if ($param2 == 'penawaran') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['list_barang']   = $this->md_fpp->getBarang();
        $page_data['page_name']     = 'marketing/v_aju_fpp';
        $page_data['page_title']    = 'FPP';
        $page_data['page_desc']     = 'Form Permintaan Penawaran';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'presentase') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['list_barang']   = $this->md_fpp->getBarang();
        $page_data['nama_teknisi']    = $this->md_pengguna->getPenggunaTeknisi();
        $page_data['page_name']     = 'marketing/v_aju_presentase';
        $page_data['page_title']    = 'Presentase';
        $page_data['page_desc']     = 'Detail Permintaan Presentation / Training User / Demo Request';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'trouble') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['list_barang']   = $this->md_fpp->getBarang();
        $page_data['nama_teknisi']    = $this->md_pengguna->getPenggunaTeknisi();
        $page_data['page_name']     = 'marketing/v_aju_trouble';
        $page_data['page_title']    = 'Trouble / Installation';
        $page_data['page_desc']     = 'Detail Permintaan Trouble / Installation Request';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'edit') {
      if ($param2 == 'penawaran') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['data_gc']    = $this->md_surat_list->getGcById($param3);
        $page_data['detail_gc']    = $this->md_surat_list->getDetailGc($param3);
        $page_data['data_fpp']    = $this->md_fpp->getFppById($param3);
        $page_data['detail_fpp']    = $this->md_fpp->getFppDetailById($param3);
        $page_data['masternotifikasi'] = $this->md_surat_list->getmasternotifikasi(3);
        $page_data['page_name']     = 'marketing/v_edit_fpp';
        $page_data['page_title']    = 'FPP';
        $page_data['page_desc']     = 'Edit Permintaan Penawaran';
        $this->load->view('index', $page_data);
      }
    }
  }


  //ADD
  public function add()
  {
    grantAccessFor('all');

    //if($param1=="permintaan"){
    //menambah pengajuan fpp
    $this->md_fpp->reset_increment("fpp");
    $idFpp = $this->md_fpp->getFppKodeId();
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

    $kodeFpp = $kodeFpp . "/FPP/MKT/VYM/" . $bulan . "/" . $tahun;
    $data['kode_fpp']      = $kodeFpp;

    $data['id_pengaju']     = sessPenggunaId();
    $data['csname']          = $this->input->post('csname', TRUE);
    $data['alamat']          = $this->input->post('alamat', TRUE);
    $data['tgl']            = date_db_format($this->input->post('tanggal', TRUE));
    $data['cpname']          = $this->input->post('cpname', TRUE);
    $data['nocp']            = $this->input->post('cpno', TRUE);
    $lainnya = $this->input->post('payment', TRUE);
    if ($lainnya == "1") {
      // Jika opsi "Lainnya" dipilih, ambil nilai dari input teks "lainnyaInput"
      $data['payment'] = $this->input->post('lainnyaInput', TRUE);
    } else {
      // Jika opsi selain "Lainnya" dipilih, ambil nilai dari dropdown "payment"
      $data['payment'] = $lainnya;
    }
    $data['cicilan']            = $this->input->post('cicilan', TRUE);
    $lainnya = $this->input->post('ongkir', TRUE);
    if ($lainnya == "1") {
      // Jika opsi "Lainnya" dipilih, ambil nilai dari input teks "lainnyaInput"
      $data['ongkir'] = $this->input->post('lainnyaOngkir', TRUE);
    } else {
      // Jika opsi selain "Lainnya" dipilih, ambil nilai dari dropdown "payment"
      $data['ongkir'] = $lainnya;
    }
    $data['pajak']          = $this->input->post('pajak', TRUE);
    $data['notifikasi']     = $this->input->post('notifikasi', TRUE) ?: '1';
    $data['notes']          = $this->input->post('notes', TRUE);
    $data['kota_pengajuan']  = $this->input->post('kota_aju', TRUE);
    $data['tgl_pengajuan']  = date_db_format($this->input->post('pengajuan', TRUE));
    $data['status']  = 0;

    $data['approval_1'] = $this->input->post('approval_1', TRUE);
    $data['approval_2'] = $this->input->post('approval_2', TRUE);
    $data['approval_3'] = $this->input->post('approval_3', TRUE);
    $data['approval_4'] = $this->input->post('approval_4', TRUE);
    $data['approval_5'] = $this->input->post('approval_5', TRUE);
    $data['approval_6'] = $this->input->post('approval_6', TRUE);
    $data['approval_7'] = $this->input->post('approval_7', TRUE);
    $data['approval_8'] = $this->input->post('approval_8', TRUE);

    // --- START Tambahan Kolom TLD ---
    $data['jenis_pembelian_tld']      = $this->input->post('jenis_pembelian_tld', TRUE);
    $data['jumlah_pekerja_radiasi']   = $this->input->post('jumlah_pekerja_radiasi', TRUE);
    $data['include_zero_check']       = $this->input->post('include_zero_check', TRUE);
    $data['sudah_memiliki_tld_kontrol'] = $this->input->post('sudah_memiliki_tld_kontrol', TRUE);
    $data['membutuhkan_tld_kontrol_baru'] = $this->input->post('membutuhkan_tld_kontrol_baru', TRUE);
    $data['tld_include_tld_kontrol']  = $this->input->post('tld_include_tld_kontrol', TRUE);
    $data['user_terdaftar_lab_dosimetri'] = $this->input->post('user_terdaftar_lab_dosimetri', TRUE);
    $data['setuju_estimasi_zero_check'] = $this->input->post('setuju_estimasi_zero_check', TRUE);
    // --- END Tambahan Kolom TLD ---

    $this->md_fpp->addFpp($data);

    //menambah pengajuan fpp
    $lastGcId = $this->md_fpp->getFppLastId();
    $lastGcId = $lastGcId->id;

    //menambah detail fpp
    $this->md_fpp->reset_increment("fpp_detail");
    $itung = $this->input->post('itung', TRUE);
    $dataDetailGc['id_fpp']      = $lastGcId;
    if ($itung > 0) {
      for ($x = 1; $x < $itung; $x++) {
        $dataDetailGc['deskripsi']  = $this->input->post('deskripsi[' . $x . ']', TRUE);
        $dataDetailGc['qty']        = $this->input->post('quali[' . $x . ']', TRUE);
        $dataDetailGc['price']      = $this->input->post('price[' . $x . ']', TRUE);
        $dataDetailGc['diskon']      = $this->input->post('diskon[' . $x . ']', TRUE);
        $dataDetailGc['komisi']     = $this->input->post('komisi[' . $x . ']', TRUE);
        $dataDetailGc['namauser']     = $this->input->post('namauser[' . $x . ']', TRUE);
        $dataDetailGc['komisiketiga']     = $this->input->post('komisiketiga[' . $x . ']', TRUE);
        $dataDetailGc['namaketiga']     = $this->input->post('namaketiga[' . $x . ']', TRUE);
        $this->md_fpp->addDetailFpp($dataDetailGc);
      }
    }


    $ambilDataPengaju   = $this->md_pengguna->getById(sessPenggunaId());
    //$namaPengaju		    = $ambilDataPengaju[0]->nama;


    //if(sessPenggunaId()==6){
    //  $penerima = 'aridho Rezki';
    //}else{
    $penerima = $ambilDataPengaju[0]->nama;
    //}

    $idnotif = $dataDetailGc['id_fpp'];
    //NOTIFIKASI
    $urlNotif = "https://office.visiyosindo.id/fpp/show/detail_fpp/penawaran/$idnotif";


    if ($data['notifikasi'] == 1) {
      $idpenerima1 = 'MARKETING PT. VYM';
      //$idpenerima1 = 'Test Api Wa Group';
      $idpenerima2 = '';
      $namapenerima = '_Team Marketing_';
      $ulang = '1';
    } else if ($data['notifikasi'] == 2) {
      $idpenerima1 = 'VISILAB';
      //$idpenerima1 = 'Test Api Wa Group';
      $idpenerima2 = '';
      $namapenerima = '_Team Visilab_';
      $ulang = '1';

      //send notif wa ADMIN VISILAB
      $dataWa = [
        'idPenerima1'   => 1,
        'idPenerima2'   => 83,
        'namaSurat'     => 'Permintaan Penawaran',
        'urlNotif'       => $urlNotif,
        'penerima'       => '_Team Visilab_',
        'perihal'       => "",
        'kode'           => $data['kode_fpp']
      ];

      $this->notifWaAddSuratLink(2, $dataWa);
    } else if ($data['notifikasi'] == 3) {
      $idpenerima1 = 'MARKETING PT. VYM';
      $idpenerima2 = 'VISILAB';
      //$idpenerima1 = 'Test Api Wa Group';
      //$idpenerima2 = 'Test Api Wa Group';
      $namapenerima = '_Team Marketing & Visilab_';
      $ulang = '2';

      //send notif wa ADMIN VISILAB
      $dataWa = [
        'idPenerima1'   => 1,
        'idPenerima2'   => 83,
        'namaSurat'     => 'Permintaan Penawaran',
        'urlNotif'       => $urlNotif,
        'penerima'       => '_Team Visilab_',
        'perihal'       => "",
        'kode'           => $data['kode_fpp']
      ];

      $this->notifWaAddSuratLink(2, $dataWa);
    }

    //send notif Group wa
    $dataWa = [
      'idPenerima1'   => $idpenerima1,
      //'idPenerima1' 	=> 'Test Api Wa Group',
      'idPenerima2'   => $idpenerima2,
      'idPenerima3'   => '',
      'namaSurat'      => 'Permintaan Penawaran',
      'penerima'       => $namapenerima,
      'pengaju'       => $penerima,
      'csname'         => $data['csname'],
      'kode'           => $kodeFpp
    ];
    $this->notifWaAddFppGroup($ulang, $dataWa);



    /** LOG */
    addLog('Pengajuan Permintaan Penawaran', 'Permintaan Penawaran Diajukan dengan Kode ' . $kodeFpp);
    ajaxReturnDie('success', 'Permintaan Penawaran Berhasil Diajukan', TRUE);

    // }



  }


  public function addPermintaan($param1)
  {
    grantAccessFor('all');

    if ($param1 == "presentase") {
      //menambah pengajuan Presentase
      $this->md_fpp->reset_increment("presentase");
      $idFpp = $this->md_fpp->getPresentaseKodeId();
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

      $kodeFpp = $kodeFpp . "/FPTD/MKT/VYM/" . $bulan . "/" . $tahun;
      $data['kode']      = $kodeFpp;

      $data['id_pengaju']     = sessPenggunaId();
      $data['csname']          = $this->input->post('csname', TRUE);
      $data['alamat']          = $this->input->post('alamat', TRUE);
      $data['cpname']          = $this->input->post('cpname', TRUE);
      $data['nocp']            = $this->input->post('nocp', TRUE);
      $data['invoice']         = $this->input->post('invoice', TRUE);
      $lainnya = $this->input->post('teknisi', TRUE);
      if ($lainnya == "1") {
        $data['teknisi'] = $this->input->post('lainnyaInput', TRUE);
      } else {
        $data['teknisi'] = $lainnya;
      }

      $data['notes']          = $this->input->post('notes', TRUE);
      $data['kota_pengajuan']  = $this->input->post('kota_aju', TRUE);
      $data['tgl_pengajuan']  = date_db_format($this->input->post('pengajuan', TRUE));
      $data['status']  = 0;
      $this->md_fpp->addPresentase($data);

      //menambah pengajuan Presentase
      $lastGcId = $this->md_fpp->getPresentaseLastId();
      $lastGcId = $lastGcId->id;

      //menambah detail Presentase
      $this->md_fpp->reset_increment("presentase_detail");
      $itung = $this->input->post('itung', TRUE);
      $dataDetailGc['id_presentase']      = $lastGcId;
      if ($itung > 0) {
        for ($x = 1; $x < $itung; $x++) {
          $dataDetailGc['deskripsi']  = $this->input->post('deskripsi[' . $x . ']', TRUE);
          $dataDetailGc['req_detail']    = $this->input->post('req_detail[' . $x . ']', TRUE);
          $this->md_fpp->addDetailPresentase($dataDetailGc);
        }
      }


      $ambilDataPengaju   = $this->md_pengguna->getById(sessPenggunaId());
      $penerima           = $ambilDataPengaju[0]->nama;



      //send notif Group wa
      $dataWa = [
        'idPenerima1'   => 'MARKETING PT. VYM',
        //'idPenerima1' 	=> 'Test Api Wa Group',
        'idPenerima2'   => '',
        'idPenerima3'   => '',
        'namaSurat'      => 'Permintaan Presentation / Training User / Demo Request',
        'statusSurat'   => 'Pengajuan',
        'status'         => 'mengajukan',
        'penerima'       => '_Team Marketing_',
        'pengaju'       => $penerima,
        'csname'         => $data['csname'],
        'kode'           => $kodeFpp
      ];
      $this->notifWaMarketingGroup(1, $dataWa);



      /** LOG */
      addLog('Pengajuan Presentase', 'Permintaan Presentase dengan Kode ' . $kodeFpp);
      ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
    } else if ($param1 == "trouble") {
      //menambah pengajuan Trouble
      $this->md_fpp->reset_increment("trouble");
      $idFpp = $this->md_fpp->getTroubleKodeId();
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

      $kodeFpp = $kodeFpp . "/FTI/MKT/VYM/" . $bulan . "/" . $tahun;
      $data['kode']      = $kodeFpp;

      $data['id_pengaju']     = sessPenggunaId();
      $data['csname']          = $this->input->post('csname', TRUE);
      $data['alamat']          = $this->input->post('alamat', TRUE);
      $data['cpname']          = $this->input->post('cpname', TRUE);
      $data['nocp']            = $this->input->post('nocp', TRUE);
      $data['invoice']         = $this->input->post('invoice', TRUE);
      $data['notes']          = $this->input->post('notes', TRUE);
      $data['item1']          = $this->input->post('kondisi1', TRUE);
      $data['item2']          = $this->input->post('kondisi2', TRUE);
      $data['item3']          = $this->input->post('kondisi3', TRUE);
      $data['item4']          = $this->input->post('kondisi4', TRUE);
      $data['item5']          = $this->input->post('kondisi5', TRUE);
      $data['item6']          = $this->input->post('kondisi6', TRUE);
      $data['item7']          = $this->input->post('kondisi7', TRUE);
      $data['item8']          = $this->input->post('kondisi8', TRUE);
      $data['item9']          = $this->input->post('kondisi9', TRUE);
      $data['item10']          = $this->input->post('kondisi10', TRUE);
      $data['item11']          = $this->input->post('kondisi11', TRUE);
      $data['namaitem11']     = $this->input->post('namaitem11', TRUE);
      $data['kota_pengajuan']  = $this->input->post('kota_aju', TRUE);
      $data['tgl_pengajuan']  = date_db_format($this->input->post('pengajuan', TRUE));
      $data['status']  = 0;
      $this->md_fpp->addTrouble($data);

      //menambah pengajuan Trouble
      $lastGcId = $this->md_fpp->getTroubleLastId();
      $lastGcId = $lastGcId->id;

      //menambah detail Trouble
      $this->md_fpp->reset_increment("trouble_detail");
      $itung = $this->input->post('itung', TRUE);
      $dataDetailGc['id_trouble']      = $lastGcId;
      if ($itung > 0) {
        for ($x = 1; $x < $itung; $x++) {
          $dataDetailGc['deskripsi']  = $this->input->post('deskripsi[' . $x . ']', TRUE);
          $dataDetailGc['req_detail']    = $this->input->post('req_detail[' . $x . ']', TRUE);
          $this->md_fpp->addDetailTrouble($dataDetailGc);
        }
      }


      $ambilDataPengaju   = $this->md_pengguna->getById(sessPenggunaId());
      $penerima           = $ambilDataPengaju[0]->nama;



      //send notif Group wa
      $dataWa = [
        'idPenerima1'   => 'MARKETING PT. VYM',
        //'idPenerima1' 	=> 'Test Api Wa Group',
        'idPenerima2'   => '',
        'idPenerima3'   => '',
        'namaSurat'      => 'Permintaan Trouble / Installation Request',
        'statusSurat'   => 'Pengajuan',
        'status'         => 'mengajukan',
        'penerima'       => '_Team Marketing_',
        'pengaju'       => $penerima,
        'csname'         => $data['csname'],
        'kode'           => $kodeFpp
      ];
      $this->notifWaMarketingGroup(1, $dataWa);



      /** LOG */
      addLog('Pengajuan Trouble', 'Permintaan Trouble dengan Kode ' . $kodeFpp);
      ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
    }
  }


  //UPDATE

  public function updateCro1($id)
  {
    grantAccessFor('all');

    $no_sph = $this->input->post('no_sph', TRUE);
    $link_sph = $this->input->post('link_sph', TRUE);

    $data = [
      'no_sph' => nl2br($no_sph),
      'link_sph' => nl2br($link_sph),
      'status' => "1",
    ];

    // var_dump($data);
    // die;

    $this->md_fpp->updateFpp($id, $data);

    $ambilDataPengaju   = $this->md_fpp->getFppById($id);
    $namacs              = $ambilDataPengaju[0]->csName;
    $kodeFPP            = $ambilDataPengaju[0]->kode_fpp;
    $notif           = $ambilDataPengaju[0]->notifikasi;

    //if($idPengaju==6){
    //  $penerima = 'aridho Rezki';
    //}else{
    $penerima           = $ambilDataPengaju[0]->pengaju;
    //}
    if ($notif  == 1) {
      $idpenerima1 = 'MARKETING PT. VYM';
      //$idpenerima1 = 'Test Api Wa Group';
      $idpenerima2 = '';
      $ulang = '1';
    } else if ($notif  == 2) {
      $idpenerima1 = 'VISILAB';
      //$idpenerima1 = 'Test Api Wa Group';
      $idpenerima2 = '';
      $ulang = '1';
    } else if ($notif  == 3) {
      $idpenerima1 = 'MARKETING PT. VYM';
      $idpenerima2 = 'VISILAB';
      //$idpenerima1 = 'Test Api Wa Group';
      //$idpenerima2 = 'Test Api Wa Group';
      $ulang = '2';
    }

    $dataWa = [
      'id'             => $id,
      'idPenerima1'   => $idpenerima1,
      //'idPenerima1' 	=> 'Test Api Wa Group',
      'idPenerima2'   => $idpenerima2,
      'namaSurat'      => 'Permintaan Penawaran',
      'noSph'          => $no_sph,
      'linkSph'        => $link_sph,
      'kode'           => $kodeFPP,
      'jenis'         => 'SPH',
      'penerima'       => $penerima,
      'csname'         => $namacs
    ];
    $this->notifWaAppGroup($ulang, $dataWa);


    /** LOG */
    addLog('Pengajuan Permintaan Penawaran Telah disetujui', 'Permintaan Penawaran Disetujui dan SPH dengan Kode ' . $kodeFPP);
    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
  }

  public function updateCro2($id)
  {
    grantAccessFor('all');

    $no_approval = $this->input->post('no_approval', TRUE);
    $link_approval = $this->input->post('link_approval', TRUE);

    $data = [
      'no_approval' => nl2br($no_approval),
      'link_approval' => nl2br($link_approval),
    ];

    // var_dump($data);
    // die;

    $this->md_fpp->updateFpp($id, $data);

    $ambilDataPengaju   = $this->md_fpp->getFppById($id);
    $namacs              = $ambilDataPengaju[0]->csName;
    $kodeFPP             = $ambilDataPengaju[0]->kode_fpp;
    $notif           = $ambilDataPengaju[0]->notifikasi;

    //if($idPengaju==6){
    //  $penerima = 'aridho Rezki';
    //}else{
    $penerima           = $ambilDataPengaju[0]->pengaju;
    //}

    if ($notif  == 1) {
      $idpenerima1 = 'MARKETING PT. VYM';
      //$idpenerima1 = 'Test Api Wa Group';
      $idpenerima2 = '';
      $ulang = '1';
    } else if ($notif  == 2) {
      $idpenerima1 = 'VISILAB';
      //$idpenerima1 = 'Test Api Wa Group';
      $idpenerima2 = '';
      $ulang = '1';
    } else if ($notif  == 3) {
      $idpenerima1 = 'MARKETING PT. VYM';
      $idpenerima2 = 'VISILAB';
      //$idpenerima1 = 'Test Api Wa Group';
      //$idpenerima2 = 'Test Api Wa Group';
      $ulang = '2';
    }


    //send notif Group wa

    $dataWa = [
      'id'             => $id,
      'idPenerima1'   => $idpenerima1,
      //'idPenerima1' 	=> 'Test Api Wa Group',
      'idPenerima2'   => $idpenerima2,
      'namaSurat'      => 'Permintaan Penawaran',
      'noSph'          => $no_approval,
      'linkSph'        => $link_approval,
      'kode'           => $kodeFPP,
      'jenis'         => 'Approval',
      'penerima'       => $penerima,
      'csname'         => $namacs
    ];
    $this->notifWaAppGroup($ulang, $dataWa);

    /** LOG */
    addLog('Pengajuan Permintaan Penawaran Telah disetujui', 'Permintaan Penawaran Disetujui dan Approval dengan Kode ' . $kodeFPP);
    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
  }


  public function updateCro3($id)
  {
    grantAccessFor('all');

    $status = $this->input->post('status', TRUE);

    $data = [
      'status' => nl2br($status),
      'status' => "3",
    ];
    $this->md_fpp->updateFpp($id, $data);

    $ambilDataPengaju   = $this->md_fpp->getFppById($id);
    $namacs              = $ambilDataPengaju[0]->csName;
    $kodeFPP             = $ambilDataPengaju[0]->kode_fpp;
    $notif           = $ambilDataPengaju[0]->notifikasi;

    //if($idPengaju==6){
    //  $penerima = 'aridho Rezki';
    //}else{
    $penerima           = $ambilDataPengaju[0]->pengaju;
    //}

    if ($notif  == 1) {
      $idpenerima1 = 'MARKETING PT. VYM';
      //$idpenerima1 = 'Test Api Wa Group';
      $idpenerima2 = '';
      $ulang = '1';
    } else if ($notif  == 2) {
      $idpenerima1 = 'VISILAB';
      //$idpenerima1 = 'Test Api Wa Group';
      $idpenerima2 = '';
      $ulang = '1';
    } else if ($notif  == 3) {
      $idpenerima1 = 'MARKETING PT. VYM';
      $idpenerima2 = 'VISILAB';
      //$idpenerima1 = 'Test Api Wa Group';
      //$idpenerima2 = 'Test Api Wa Group';
      $ulang = '2';
    }

    //Notif Group WA
    $dataWa = [
      'id'             => $id,
      'idPenerima1'   => $idpenerima1,
      //'idPenerima1' 	=> 'Test Api Wa Group',
      'idPenerima2'   => $idpenerima2,
      'namaSurat'      => 'Permintaan Penawaran',
      'penerima'      => $penerima,
      'kode'           => $kodeFPP,
      'csname'         => $namacs
    ];
    $this->notifWaTolakGroup($ulang, $dataWa);

    /** LOG */
    addLog('Pengajuan Permintaan Penawaran Ditolak', 'Permintaan Penawaran Ditolak dengan Kode ' . $kodeFPP);
    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
  }





  //DELETE
  public function delete($id)
  {
    grantAccessFor('all');

    $current_user_id = sessPenggunaId();
    $fpp_data = $this->md_fpp->getFppById($id);
    if (!empty($fpp_data)) {
      $creator_id = $fpp_data[0]->idPengaju;
      if (in_array($current_user_id, [105, 745])) {
        if ($creator_id != $current_user_id) {
          ajaxReturnDie('error', 'Anda tidak memiliki akses untuk menghapus data milik marketing lain.');
          return;
        }
      }
    }

    $data = [
      'status' => "5",
    ];


    $this->md_fpp->updateFpp($id, $data);

    addLog('Menghapus FPP', 'Menghapus FPP ' . $id);
    ajaxReturnDie('success', 'FPP berhasil dihapus', 'reload_table');
  }


  public function deletePresentase($id)
  {
    grantAccessFor('all');


    $data = [
      'status' => "5",
    ];


    $this->md_fpp->updatePresentase($id, $data);

    addLog('Menghapus Presentase', 'Menghapus Presentase' . $id);
    ajaxReturnDie('success', 'berhasil dihapus', 'reload_table');
  }

  public function deleteTrouble($id)
  {
    grantAccessFor('all');


    $data = [
      'status' => "5",
    ];


    $this->md_fpp->updateTrouble($id, $data);

    addLog('Menghapus Trouble', 'Menghapus Trouble' . $id);
    ajaxReturnDie('success', 'berhasil dihapus', 'reload_table');
  }



  public function updatePresentaseCRO($id)
  {
    grantAccessFor('all');

    $link_sph = $this->input->post('link_sph', TRUE);

    $data = [
      'link' => nl2br($link_sph),
      'status' => "1",
    ];

    // var_dump($data);
    // die;

    $this->md_fpp->updatePresentase($id, $data);

    $ambilDataPengaju   = $this->md_fpp->getPresentaseById($id);
    $namacs              = $ambilDataPengaju[0]->csName;
    $kodeFPP            = $ambilDataPengaju[0]->kode;
    $penerima           = $ambilDataPengaju[0]->pengaju;



    $dataWa = [
      'id'             => $id,
      'idPenerima1'   => 'MARKETING PT. VYM',
      //'idPenerima1' 	=> 'Test Api Wa Group',
      'idPenerima2'   => '',
      'namaSurat'      => 'Permintaan Presentation / Training User / Demo Request',
      'statusSurat'   => 'Persetujuan',
      'status'         => 'menyetujui',
      'link'           => $link_sph,
      'kode'           => $kodeFPP,
      'penerima'       => $penerima,
      'csname'         => $namacs
    ];
    $this->notifWaAppMarketingGroup(1, $dataWa);

    addLog('Persetujuan Presentase', ' Kode Presentase' . $kodeFPP);
    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
  }


  //UPDATE
  public function update_setujui($param1 = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');

    if ($param1 == "presentase") {
      if ($param2 == "ttd_1") {

        $id = $this->input->post('id_fpp');
        $data['status'] = 1;
        $this->md_fpp->updatePresentase($id, $data);

        $ambilDataPengaju   = $this->md_fpp->getPresentaseById($id);
        $namacs              = $ambilDataPengaju[0]->csName;
        $kodeFPP            = $ambilDataPengaju[0]->kode;
        $penerima           = $ambilDataPengaju[0]->pengaju;



        $dataWa = [
          'id'             => $id,
          'idPenerima1'   => 'MARKETING PT. VYM',
          //'idPenerima1' 	=> 'Test Api Wa Group',
          'idPenerima2'   => '',
          'namaSurat'      => 'Permintaan Presentation / Training User / Demo Request',
          'statusSurat'   => 'Persetujuan',
          'status'         => 'menyetujui',
          'kode'           => $kodeFPP,
          'penerima'       => $penerima,
          'csname'         => $namacs
        ];
        $this->notifWaAppMarketingGroup(1, $dataWa);

        addLog('Persetujuan Presentase', ' Kode Presentase' . $kodeFPP);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "trouble") {
      if ($param2 == "ttd_1") {

        $id = $this->input->post('id_fpp');
        $data['status'] = 1;
        $this->md_fpp->updateTrouble($id, $data);

        $ambilDataPengaju   = $this->md_fpp->getTroubleById($id);
        $namacs              = $ambilDataPengaju[0]->csName;
        $kodeFPP            = $ambilDataPengaju[0]->kode;
        $penerima           = $ambilDataPengaju[0]->pengaju;



        $dataWa = [
          'id'             => $id,
          'idPenerima1'   => 'MARKETING PT. VYM',
          //'idPenerima1' 	=> 'Test Api Wa Group',
          'idPenerima2'   => '',
          'namaSurat'      => 'Permintaan Trouble / Installation Request',
          'statusSurat'   => 'Persetujuan',
          'status'         => 'menyetujui',
          'kode'           => $kodeFPP,
          'penerima'       => $penerima,
          'csname'         => $namacs
        ];
        $this->notifWaAppMarketingGroup(1, $dataWa);

        addLog('Persetujuan Trouble', ' Kode Trouble' . $kodeFPP);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    }
  }


  public function update_tolak($param1 = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');

    if ($param1 == "presentase") {
      if ($param2 == "ttd_1") {

        $id = $this->input->post('id_fpp');
        $data['status'] = 2;
        $this->md_fpp->updatePresentase($id, $data);

        $ambilDataPengaju   = $this->md_fpp->getPresentaseById($id);
        $namacs              = $ambilDataPengaju[0]->csName;
        $kodeFPP            = $ambilDataPengaju[0]->kode;
        $penerima           = $ambilDataPengaju[0]->pengaju;



        $dataWa = [
          'id'             => $id,
          'idPenerima1'   => 'MARKETING PT. VYM',
          //'idPenerima1' 	=> 'Test Api Wa Group',
          'idPenerima2'   => '',
          'namaSurat'      => 'Permintaan Presentation / Training User / Demo Request',
          'statusSurat'   => 'Penolakan',
          'status'         => 'menolak',
          'kode'           => $kodeFPP,
          'penerima'       => $penerima,
          'csname'         => $namacs
        ];
        $this->notifWaAppMarketingGroup(1, $dataWa);

        addLog('Penolakan Presentase', ' Kode Presentase' . $kodeFPP);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "trouble") {
      if ($param2 == "ttd_1") {

        $id = $this->input->post('id_fpp');
        $data['status'] = 2;
        $this->md_fpp->updateTrouble($id, $data);

        $ambilDataPengaju   = $this->md_fpp->getTroubleById($id);
        $namacs              = $ambilDataPengaju[0]->csName;
        $kodeFPP            = $ambilDataPengaju[0]->kode;
        $penerima           = $ambilDataPengaju[0]->pengaju;



        $dataWa = [
          'id'             => $id,
          'idPenerima1'   => 'MARKETING PT. VYM',
          //'idPenerima1' 	=> 'Test Api Wa Group',
          'idPenerima2'   => '',
          'namaSurat'      => 'Permintaan Trouble / Installation Request',
          'statusSurat'   => 'Penolakan',
          'status'         => 'menolak',
          'kode'           => $kodeFPP,
          'penerima'       => $penerima,
          'csname'         => $namacs
        ];
        $this->notifWaAppMarketingGroup(1, $dataWa);

        addLog('Penolakan Trouble', ' Kode Trouble' . $kodeFPP);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    }
  }






  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  // pagination -------------------------------------------------------------------------------------------------------------------------------------------------------
  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------

  public function pagination($param = "", $param2 = "")
  {
    grantAccessFor('all');
    if ($param == 'permintaan_penawaran') {
      // Ambil data filter
      $bulan = $this->input->post('bulan');

      $tahun = $this->input->post('tahun');
      if (isAdmin() || isHrd() || isCRO() || isGa() || sessPenggunaId() == '105' || sessPenggunaId() == '745' || sessPenggunaId() == '754' || sessPenggunaId() == '755') {
        $dt     = $this->md_fpp->getAllFPP($bulan, $tahun); // <-- Kirim parameter filter
      } else if (isTeamMarketing()) {
        $dt     = $this->md_fpp->getAllFPPbyID(sessPenggunaId(), $bulan, $tahun); // <-- Kirim parameter filter
      }
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $csName  = '<a href="fpp/show/detail_fpp/penawaran/' . $row->idGc . '">' . $row->csName . '</a>';
        $kode_fpp  = '<a href="fpp/show/detail_fpp/penawaran/' . $row->idGc . '">' . $row->kode_fpp . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';

        if ($row->sph != "") {
          $buttonSph = '<a href="' . $row->sph . '" target="blank" class="btn btn-primary float-center" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran SPH</a>';
        } else {
          $buttonSph = "";
        }


        if ($row->aproval != "") {
          $buttonAproval = '<a href="' . $row->aproval . '" target="blank" class="btn btn-primary float-center" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran Aproval</a>';
        } else {
          $buttonAproval = "";
        }


        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Customer Relation Officer</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    ' . ($row->status == '7' ? '<a href="fpp/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                    ' . ($row->status == '0' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="fpp/delete"><i class="bx bx-trash"></i></button>' : '') . '
                </div>';

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->csName;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->pengaju;
        $th[] = $buttonSph;
        $th[] = $buttonAproval;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'permintaan_marketing') {
      //$dt    = $this->md_surat_list->getAllMygc(sessPenggunaId());
      $dt     = $this->md_fpp->getAllFPPbyID(sessPenggunaId());
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $csName  = '<a href="fpp/show/detail_fpp/penawaran/' . $row->idGc . '">' . $row->csName . '</a>';
        $kode_fpp  = '<a href="fpp/show/detail_fpp/penawaran/' . $row->idGc . '">' . $row->kode_fpp . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';

        if ($row->sph != "") {
          $buttonSph = '<a href="' . $row->sph . '" target="blank" class="btn btn-primary float-center" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran SPH</a>';
        } else {
          $buttonSph = "";
        }


        if ($row->aproval != "") {
          $buttonAproval = '<a href="' . $row->aproval . '" target="blank" class="btn btn-primary float-center" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran Aproval</a>';
        } else {
          $buttonAproval = "";
        }


        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Customer Relation Officer</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    ' . ($row->status == '7' ? '<a href="fpp/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                    ' . ($row->status == '0' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="fpp/delete"><i class="bx bx-trash"></i></button>' : '') . '
                </div>';



        $th = array();
        $th[] = ++$start;
        $th[] = $kode_fpp;
        $th[] = $row->csName;
        $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y', strtotime($row->tanggal));
        $th[] = $row->pengaju;
        $th[] = $buttonSph;
        $th[] = $buttonAproval;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'presentase') {
      if (isAdmin() || isHrd() || isCRO() || isGa() || sessPenggunaId() == '105' || sessPenggunaId() == '745') {
        $dt     = $this->md_fpp->getAllPresentase();
      } else if (isTeamMarketing()) {
        $dt     = $this->md_fpp->getAllPresentaseById(sessPenggunaId());
      }
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode  = '<a href="fpp/show/detail_fpp/presentase/' . $row->idGc . '">' . $row->kode . '</a>';


        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Customer Relation Officer</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    ' . ($row->status == '7' ? '<a href="fpp/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                    ' . ($row->status == '0' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="fpp/deletePresentase"><i class="bx bx-trash"></i></button>' : '') . '
                </div>';

        if ($row->link != '') {
          $linkDownload = '<a href="' . $row->link . '"><i class="fas fa-download"></i> Download</a>';
        } else {
          $linkDownload = "";
        }

        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->csName;
        $th[] = $row->pengaju;
        $th[] = $row->invoice;
        $th[] = $row->teknisi;
        $th[] = $linkDownload;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'trouble') {
      if (isAdmin() || isHrd() || isCRO() || isGa() || sessPenggunaId() == '105' || sessPenggunaId() == '745') {
        $dt     = $this->md_fpp->getAllTrouble();
      } else if (isTeamMarketing()) {
        $dt     = $this->md_fpp->getAllTroubleById(sessPenggunaId());
      }
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode  = '<a href="fpp/show/detail_fpp/trouble/' . $row->idGc . '">' . $row->kode . '</a>';


        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Customer Relation Officer</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }

        $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    ' . ($row->status == '7' ? '<a href="fpp/show/edit/penawaran/' . $row->idGc . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                    ' . ($row->status == '0' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->idGc . '" data-object="fpp/deleteTrouble"><i class="bx bx-trash"></i></button>' : '') . '
                </div>';



        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->csName;
        $th[] = $row->pengaju;
        $th[] = $row->invoice;
        $th[] = $stat_surat;
        $th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    }
  }




  //==================================================
  //================== Notif WA Group ================
  //==================================================
  public function notifWaAddFppGroup($ulang, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_pengguna->getById(sessPenggunaId());
    $namaPengaju        = $ambilDataPengaju[0]->nama;

    for ($i = 1; $i <= $ulang; $i++) {
      if ($i == 1) {
        $idpenerima = $detail['idPenerima1'];
      } else if ($i == 2) {
        $idpenerima = $detail['idPenerima2'];
      } else if ($i == 3) {
        $idpenerima = $detail['idPenerima3'];
      }


      //abaikan error
      error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 0);
      //
      $dataWa = [
        'namaSurat'     => $detail['namaSurat'],
        'noPenerima'    => $idpenerima,
        'namaPengaju'   => urlencode($detail['pengaju']),
        'csname'         => urlencode($detail['csname']),
        'kodeFPP'       => $detail['kode'],
        'namaPenerima'   => urlencode($detail['penerima'])
      ];

      //if(sessPenggunaId()==6){
      //  waPermintaanPenawaranGroupHaridho($dataWa);
      //}else{
      waPermintaanPenawaranGroup($dataWa);
      //}
    }
  }

  public function notifWaAppGroup($ulang, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_pengguna->getById(sessPenggunaId());
    $namaPengaju        = $ambilDataPengaju[0]->nama;

    $ambilData          = $this->md_fpp->getFppById($detail['id']);
    $idPengaju           = $ambilData[0]->idPengaju;


    for ($i = 1; $i <= $ulang; $i++) {
      if ($i == 1) {
        $idpenerima = $detail['idPenerima1'];
      } else if ($i == 2) {
        $idpenerima = $detail['idPenerima2'];
      }


      //abaikan error
      error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 0);
      //



      $dataWa = [
        'namaSurat'   => $detail['namaSurat'],
        'noPenerima'   => $idpenerima,
        'namaPengaju' => urlencode($namaPengaju),
        'csname'       => urlencode($detail['csname']),
        'kodeFPP'     => $detail['kode'],
        'jenis'       => $detail['jenis'],
        'noSph'       => $detail['noSph'],
        'linkSph'     => $detail['linkSph'],
        'namaPenerima'  => urlencode($detail['penerima'])
        //'noHp' 		    => $nope
      ];
      //if($idPengaju==6){
      //   waAppPermintaanPenawaranGroupHaridho($dataWa);
      //}else{
      waAppPermintaanPenawaranGroup($dataWa);
      // }
    }
  }

  public function notifWaTolakGroup($ulang, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_pengguna->getById(sessPenggunaId());
    $namaPengaju        = $ambilDataPengaju[0]->nama;

    $ambilData          = $this->md_fpp->getFppById($detail['id']);
    $idPengaju          = $ambilData[0]->idPengaju;

    for ($i = 1; $i <= $ulang; $i++) {
      if ($i == 1) {
        $idpenerima = $detail['idPenerima1'];
      } else if ($i == 2) {
        $idpenerima = $detail['idPenerima2'];
      }

      $dataPenerima   = $this->md_pengguna->getById($idPengaju);
      //abaikan error
      error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 0);
      //

      $jabatan        = $dataPenerima[0]->nama;
      //$nope           = $dataPenerima[0]->no_hp;

      $dataWa = [
        'namaSurat'   => $detail['namaSurat'],
        'noPenerima'   => $idpenerima,
        'namaPengaju' => $namaPengaju,
        'csname'       => urlencode($detail['csname']),
        'kodeFPP'     => $detail['kode'],
        //'noHp' 		    => $nope,
        'namaPenerima'  => urlencode($detail['penerima'])
      ];
      //if($idPengaju==6){
      //  waTolakPermintaanPenawaranGroupHaridho($dataWa);
      // }else{
      waTolakPermintaanPenawaranGroup($dataWa);
      // }
    }
  }





  public function notifWaMarketingGroup($ulang, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_pengguna->getById(sessPenggunaId());
    $namaPengaju        = $ambilDataPengaju[0]->nama;

    for ($i = 1; $i <= $ulang; $i++) {
      if ($i == 1) {
        $idpenerima = $detail['idPenerima1'];
      } else if ($i == 2) {
        $idpenerima = $detail['idPenerima2'];
      } else if ($i == 3) {
        $idpenerima = $detail['idPenerima3'];
      }


      //abaikan error
      error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 0);
      //
      $dataWa = [
        'namaSurat'     => urlencode($detail['namaSurat']),
        'noPenerima'    => $idpenerima,
        'namaPengaju'   => $detail['pengaju'],
        'csname'         => urlencode($detail['csname']),
        'kode'           => $detail['kode'],
        'statusSurat'   => $detail['statusSurat'],
        'status'         => $detail['status'],
        'namaPenerima'   => $detail['penerima']
      ];


      waAppGroupMarketing($dataWa);
    }
  }

  public function notifWaAppMarketingGroup($ulang, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_pengguna->getById(sessPenggunaId());
    $namaPengaju        = $ambilDataPengaju[0]->nama;

    //$ambilData	        = $this->md_fpp->getFppById($detail['id']);
    //$idPengaju           = $ambilData[0]->idPengaju;


    for ($i = 1; $i <= $ulang; $i++) {
      if ($i == 1) {
        $idpenerima = $detail['idPenerima1'];
      } else if ($i == 2) {
        $idpenerima = $detail['idPenerima2'];
      }


      //abaikan error
      error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 0);
      //



      $dataWa = [
        'namaSurat'   => urlencode($detail['namaSurat']),
        'noPenerima'   => $idpenerima,
        'namaPengaju' => $namaPengaju,
        'csname'       => urlencode($detail['csname']),
        'kode'         => $detail['kode'],
        'link'        => $detail['link'],
        'statusSurat'   => $detail['statusSurat'],
        'status'         => $detail['status'],
        'namaPenerima'  => $detail['penerima']
        //'noHp' 		    => $nope
      ];

      waAppGroupMarketing($dataWa);
    }
  }



  public function notifWaAddSuratLink($ulang, $detail)
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
        'urlNotif'     => urlencode($detail['urlNotif']),
        'noPenerima'   => $nope,
        'kodeSurat'   => $detail['kode'],
        'namaPengaju' => $namaPengaju,
        'perihal'     => urlencode($detail['perihal']),
        'namaPenerima'   => urlencode($detail['penerima'])
      ];
      waSuratOpenLink($dataWa);
    }
  }



  //==================================================
  //================== Notif WA Group ================
  //==================================================





  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  // print ------------------------------------------------------------------------------------------------------------------------------------------------------------
  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------

  public function print_page($param1 = "", $param2 = "")
  {
    grantAccessFor('all');

    if ($param1 == 'fpp') {
      $gc              = $this->md_fpp->getFppById($param2);

      $dt = [
        'title_pdf'  => 'fpp',
        'object'  => $param1,
        'data_fpp'  => $this->md_fpp->getFppById($param2),
        'detail_fpp'  => $this->md_fpp->getFppDetailById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '1', '1', '1', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Form Permintaan Penawaran  ' . $gc[0]->pengaju;

      // page htmk yang akan di jadikan ke pdf
      if ($gc[0]->idFpp > 1804) {
        $html = $this->load->view('pages/v_print/print_fpp', $dt, true);
      } else {
        $html = $this->load->view('pages/v_print/print_fpp_1', $dt, true);
      }
      //$html = $this->load->view('pages/v_print/print_fpp', $dt, true);
      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'presentase') {
      $gc              = $this->md_fpp->getPresentaseById($param2);

      $dt = [
        'title_pdf'  => 'fpp',
        'object'  => $param1,
        'data_fpp'  => $this->md_fpp->getPresentaseById($param2),
        'detail_fpp'  => $this->md_fpp->getPresentaseDetailById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '1', '1', '1', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Form Presentation / Training User / Demo Request  ' . $gc[0]->pengaju;

      // page htmk yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_presentase', $dt, true);
      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'trouble') {
      $gc              = $this->md_fpp->getTroubleById($param2);

      $dt = [
        'title_pdf'  => 'trouble',
        'object'  => $param1,
        'data_fpp'  => $this->md_fpp->getTroubleById($param2),
        'detail_fpp'  => $this->md_fpp->getTroubleDetailById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '1', '1', '1', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Form Trouble / Installation Request  ' . $gc[0]->pengaju;

      // page htmk yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_trouble', $dt, true);
      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    }
  }


  public function exportLaporan()
  {

    $data = $this->md_fpp->getAllLaporanFPPByPenggunaID($this->input->get('idmarketing'), $this->input->get('tglawal'), $this->input->get('tglakhir'));



    $spreadsheet = new Spreadsheet;
    $sheet = $spreadsheet->getActiveSheet();

    // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
    $style_col = [
      'font' => ['bold' => true], // Set font nya jadi bold
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ],
      'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
      ]
    ];


    // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
    $style_row = [
      'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ],
      'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
      ]
    ];

    $sheet->setCellValue('A1', "LAPORAN DATA MARKETING " . strtoupper($this->input->get('namamarketing'))); // Set kolom A1 dengan tulisan "DATA SISWA"
    $sheet->mergeCells('A1:L1'); // Set Merge Cell pada kolom A1 sampai E1
    $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1

    // Buat header tabel nya pada baris ke 3
    $sheet->setCellValue('A4', 'No');
    $sheet->setCellValue('B4', 'No FPP');
    $sheet->setCellValue('C4', 'Nama Marketing');
    $sheet->setCellValue('D4', 'Nama Customer');
    $sheet->setCellValue('E4', 'Alamat');
    $sheet->setCellValue('F4', 'Tanggal');
    $sheet->setCellValue('G4', 'Contact Person');
    $sheet->setCellValue('H4', 'No CP');
    $sheet->setCellValue('I4', 'Payment');
    $sheet->setCellValue('J4', 'Notes');
    $sheet->setCellValue('K4', 'No SPH');
    $sheet->setCellValue('L4', 'Link SPH');
    $sheet->setCellValue('M4', 'Link Approval');

    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('A4')->applyFromArray($style_col);
    $sheet->getStyle('B4')->applyFromArray($style_col);
    $sheet->getStyle('C4')->applyFromArray($style_col);
    $sheet->getStyle('D4')->applyFromArray($style_col);
    $sheet->getStyle('E4')->applyFromArray($style_col);
    $sheet->getStyle('F4')->applyFromArray($style_col);
    $sheet->getStyle('G4')->applyFromArray($style_col);
    $sheet->getStyle('H4')->applyFromArray($style_col);
    $sheet->getStyle('I4')->applyFromArray($style_col);
    $sheet->getStyle('J4')->applyFromArray($style_col);
    $sheet->getStyle('K4')->applyFromArray($style_col);
    $sheet->getStyle('L4')->applyFromArray($style_col);
    $sheet->getStyle('M4')->applyFromArray($style_col);

    $kolom = 5;
    $nomor = 1;

    foreach ($data as $marketing) {

      $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A' . $kolom, $nomor)
        ->setCellValue('B' . $kolom, $marketing->kode_fpp)
        ->setCellValue('C' . $kolom, $marketing->namamarketing)
        ->setCellValue('D' . $kolom, $marketing->csname)
        ->setCellValue('E' . $kolom, $marketing->alamat)
        ->setCellValue('F' . $kolom, date('j F Y', strtotime($marketing->tgl)))
        ->setCellValue('G' . $kolom, $marketing->cpname)
        ->setCellValue('H' . $kolom, $marketing->nocp)
        ->setCellValue('I' . $kolom, $marketing->payment)
        ->setCellValue('J' . $kolom, $marketing->notes)
        ->setCellValue('K' . $kolom, $marketing->no_sph)
        ->setCellValue('L' . $kolom, $marketing->link_sph)
        ->setCellValue('M' . $kolom, $marketing->link_approval);

      $kolom++;
      $nomor++;
    }

    // Set width kolom
    $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
    $sheet->getColumnDimension('B')->setWidth(35); // Set width kolom B
    $sheet->getColumnDimension('C')->setWidth(50); // Set width kolom B
    $sheet->getColumnDimension('D')->setWidth(65); // Set width kolom B
    $sheet->getColumnDimension('E')->setWidth(75); // Set width kolom C
    $sheet->getColumnDimension('F')->setWidth(25); // Set width kolom D
    $sheet->getColumnDimension('G')->setWidth(45); // Set width kolom E
    $sheet->getColumnDimension('H')->setWidth(25); // Set width kolom F
    $sheet->getColumnDimension('I')->setWidth(25); // Set width kolom G
    $sheet->getColumnDimension('J')->setWidth(55); // Set width kolom H
    $sheet->getColumnDimension('K')->setWidth(35); // Set width kolom B
    $sheet->getColumnDimension('L')->setWidth(90); // Set width kolom I
    $sheet->getColumnDimension('M')->setWidth(90); // Set width kolom J

    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
    $sheet->getDefaultRowDimension()->setRowHeight(-1);
    // Set orientasi kertas jadi LANDSCAPE
    $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    // Set judul file excel nya
    $sheet->setTitle("Data Permintaan Penawaran");
    ob_end_clean();
    // Proses file excel
    $filename = "Data Permintaan Penawaran - " . strtoupper($this->input->get('namamarketing')) . ".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
  }

  public function printlaporan()
  {
    grantAccessFor('all');

    $page_data['page_name']     = 'v_print/print_permintaan_penawaran';
    $page_data['page_title']    = 'Laporan Permintaan Penawaran';
    $page_data['page_desc']     = 'Laporan Permintaan Penawaran';
    $this->load->view('index', $page_data);

    $dt = [
      'title_pdf'  => 'Laporan Permintaan Penawaran' . ' ' . $this->input->get('namamarketing'),
      'periode'  =>  $this->input->get('tglawal') . ' s.d ' . $this->input->get('tglakhir'),
      'data'  => $this->md_fpp->getAllLaporanFPPByPenggunaID($this->input->get('idmarketing'), $this->input->get('tglawal'), $this->input->get('tglakhir')),
      'csname' =>  $this->input->get('csname'),
      'kode_fpp' =>  $this->input->get('kode_fpp'),
      'alamat' =>  $this->input->get('alamat'),
      'tgl' =>  $this->input->get('tgl'),
      'cpname' =>  $this->input->get('cpname'),
      'nocp' =>  $this->input->get('nocp'),
      'payment' =>  $this->input->get('payment'),
      'notes' =>  $this->input->get('notes'),
      'no_sph' =>  $this->input->get('no_sph'),
      'link_sph' =>  $this->input->get('link_sph'),
      'link_approval' =>  $this->input->get('link_approval'),
    ];

    //load mpdf dan membuat page size 
    $mpdf = new Mpdf(['format' => 'Tabloid']);

    //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
    $mpdf->AddPage('L', '', '', '', '', '5', '5', '3', '3');



    // filename dari pdf ketika didownload
    $file_pdf = 'Laporan Merketing ';

    // page htmk yang akan di jadikan ke pdf
    $html = $this->load->view('pages/v_print/print_permintaan_penawaran', $dt, true);
    $mpdf->WriteHTML($html);
    $mpdf->Output($file_pdf . '.pdf', 'I');
  }
}
