<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Visilab extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
    $this->load->model('md_visilab');
    $this->load->model('md_prov_kota');
    $this->load->model('md_pengguna');
    $this->load->model('md_pelanggan');
    $this->load->model('md_surat_part_two');
    $this->load->helper('terbilang_helper');
    $this->load->helper('tanggal_helper');
    $this->load->helper('whatsapp_helper');
    $this->load->helper('encrypt_helper');
  }

  function id_navbar()
  {
    $id_navbar = "visilab";
    return $id_navbar;
  }


  public function show($param = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');
    if ($param == 'permintaan') {
      if ($param2 == 'laporan_stok') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['list_alat']     = $this->md_visilab->getAlat();
        $page_data['page_name']     = 'visilab/v_aju_laporan_stok';
        $page_data['page_title']    = 'Laporan Stok Alat';
        $page_data['page_desc']     = 'Management Data Laporan Stok Alat';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'approval_harga') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['list_alat']     = $this->md_visilab->getAlat();
        $page_data['nama_marketing']  = $this->md_pengguna->getPenggunaMarketing();
        $page_data['page_name']     = 'visilab/v_aju_approval_visilab';
        $page_data['page_title']    = 'Approval Harga';
        $page_data['page_desc']     = 'Management Data Approval Harga';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'berita_acara') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_nama']     = $this->md_visilab->getBywhereActive();
        $page_data['page_name']     = 'visilab/v_aju_berita_acara';
        $page_data['page_title']    = 'Berita Acara';
        $page_data['page_desc']     = 'Form Berita Acara';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'approval_po') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_cust']     = $this->md_pelanggan->getByWhere(['p.status' => 1]);
        $page_data['page_name']     = 'visilab/v_aju_app_po';
        $page_data['page_title']    = 'Approval PO';
        $page_data['page_desc']     = 'Form Permintaan Approval PO';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'detail') {
      if ($param2 == 'laporan_stok') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_stok']      = $this->md_visilab->getBywhereID(['f.id' => $param3]);
        $page_data['detail_stok']     = $this->md_visilab->getDetailBywhereID(['f.id_vs' => $param3]);
        $page_data['page_name']     = 'visilab/v_detail_laporan_stok';
        $page_data['page_title']    = 'Laporan Stok Alat';
        $page_data['page_desc']     = 'Detail Laporan Stok Alat';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'approval_harga') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['data_approval']  = $this->md_visilab->getApprovalById($param3);
        $page_data['detail_approval']  = $this->md_visilab->getDetailApproval($param3);
        $page_data['detail_approvalrevisicount']  = $this->md_visilab->getDetailApprovalrevisicount($param3);
        $page_data['page_name']     = 'visilab/v_detail_approval_visilab';
        $page_data['page_title']    = 'Approval Harga';
        $page_data['page_desc']     = 'Detail Approval Harga';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'berita_acara') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_ba']        = $this->md_visilab->getBeritaAcaraById($param3);
        $page_data['page_name']     = 'visilab/v_detail_berita_acara';
        $page_data['page_title']    = 'Berita Acara';
        $page_data['page_desc']     = 'Detail Berita Acara';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'approval_po') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_po']        = $this->md_visilab->getDetailPoById($param3);
        $page_data['page_name']     = 'visilab/v_detail_app_po';
        $page_data['page_title']    = 'Approval PO';
        $page_data['page_desc']     = 'Detail Permintaan Approval PO';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'list') {
      if ($param2 == 'laporan_stok') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'visilab/v_list_laporan_stok';
        $page_data['page_title']    = 'Laporan Stok Alat';
        $page_data['page_desc']     = 'Daftar Laporan Stok Alat';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'alat') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'visilab/v_alat';
        $page_data['page_title']    = 'Data Alat';
        $page_data['page_desc']     = 'Daftar Alat';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'approval_harga') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'visilab/v_list_approval_visilab';
        $page_data['page_title']    = 'Approval Harga';
        $page_data['page_desc']     = 'Daftar Approval Harga';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'suhu') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'visilab/v_suhu';
        $page_data['page_title']    = 'Data Visilab';
        $page_data['page_desc']     = 'Suhu Ruang';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'berita_acara') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'visilab/v_list_berita_acara';
        $page_data['page_title']    = 'Berita Acara';
        $page_data['page_desc']     = 'Daftar Pengajuan Berita Acara';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'approval_po') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'visilab/v_list_app_po';
        $page_data['page_title']    = 'Approval PO';
        $page_data['page_desc']     = 'Daftar Pengajuan Approval PO';
        $this->load->view('index', $page_data);
      }
    }
  }


  public function add($param = "")
  {
    grantAccessFor('all');

    if ($param == "laporan_stok") {
      //menambah pengajuan laporan Stok Keluar Masuk Alat
      $this->md_visilab->reset_increment("visilab_stok");
      $idFpp = $this->md_visilab->getStokKodeId();
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

      $kodeFpp = $kodeFpp . "/Log/VISILAB/VYM/" . $bulan . "/" . $tahun;
      $data['kode']              = $kodeFpp;
      $data['id_pengaju']       = sessPenggunaId();
      $data['kota_pengajuan']   = $this->input->post('kota_aju', TRUE);
      $data['penggunaan']       = $this->input->post('penggunaan', TRUE);
      $data['link_lampiran']    = $this->input->post('link_lampiran', TRUE);
      $data['status']           = 1;
      
      $data['id_setujui']       = 751;
      
      $this->md_visilab->add($data);


      $lastGcId = $this->md_visilab->getStokLastId();
      $lastGcId = $lastGcId->id;

      //menambah detail Stok
      $this->md_visilab->reset_increment("visilab_stok_detail");
      $itung = $this->input->post('itung', TRUE);
      $dataDetailGc['id_vs']        = $lastGcId;
      $dataDetailGc['id_pengguna']  = sessPenggunaId();
      $dataDetailGc['penggunaan_alat']  = $this->input->post('penggunaan', TRUE);
      if ($itung > 0) {
        for ($x = 1; $x < $itung; $x++) {
          $dataDetailGc['nama_alat']      = $this->input->post('namaalat[' . $x . ']', TRUE);
          //$dataDetailGc['no_seri']	      = $this->input->post('noseri['.$x.']', TRUE);
          $dataDetailGc['waktu_keluar']    = date_db_format($this->input->post('waktukeluar[' . $x . ']', TRUE));
          $dataDetailGc['ket']            = $this->input->post('ket[' . $x . ']', TRUE);
          $this->md_visilab->addDetail($dataDetailGc);
        }
      }

      $url = 'https://office.visiyosindo.id/visilab/show/detail/laporan_stok/' . $lastGcId;


      //send notif wa Ke Kardonal
      $dataWa = [
        'idPenerima1'   => 751,
        'idPenerima2'   => '',
        'namaSurat'     => 'Pengeluaran Alat Visilab',
        'penerima'       => 'Intan Kurnia',
        'perihal'       => $data['penggunaan'],
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSurat(1, $dataWa);

      //Ke Grup Visilab

      $dataWa = [


        //'idPenerima1' 	=> 'Test Api Wa Group',
        //'idPenerima2' 	=> '',
        'idPenerima1'   => 'VISILAB',
        'idPenerima2'   => '',
        'namaSurat'     => 'Pengeluaran Alat Visilab',
        'penerima'       => 'Team Visilab',
        'perihal'       => $data['penggunaan'],
        'url'           => $url,
        'kode'           => $kodeFpp
      ];

      $this->notifWaAddSuratGroup(1, $dataWa);



      /** LOG */
      addLog('Visilab', 'Aju Pengeluaran Alat Kode ' . $data['kode']);
      ajaxReturnDie('success', 'Ukes Berhasil Diajukan', TRUE);
    } else if ($param == "approval_harga") {
      //menambah pengajuan pembiayaan (approval)
      $this->md_visilab->reset_increment("surat_approval");
      $idapproval = $this->md_visilab->getApprovalKodeId();
      $ambilId = $idapproval->id_approval;
      // var_dump($ambilId);
      // die;
      $ambilId = ($ambilId + 1);
      $panjangId = strlen($ambilId);

      if ($panjangId == 1) {
        $kode = "00" . $ambilId;
      } else if ($panjangId == 2) {
        $kode = "0" . $ambilId;
      } else {
        $kode = $ambilId;
      }

      $bulan = ambil_bulan();
      $tahun = ambil_tahun();
      // var_dump($bulan);
      // die;

      $kode = $kode . "/AHK/VISILAB/VYM/" . $bulan . "/" . $tahun;
      $data['kode']            = $kode;
      $data['id_kat_surat']   = 22;
      $data['id_pengguna']    = sessPenggunaId();
      $data['tgl']            = date_db_format($this->input->post('tgl', TRUE));
      $data['nama']            = $this->input->post('nama', TRUE);
      $data['nama_customer']  = $this->input->post('nama_customer', TRUE);
      $data['detail_order']    = $this->input->post('detail_order', TRUE);
      $data['pajak']          = $this->input->post('pajak', TRUE);

      $this->md_visilab->addSurat('approval', $data);

      //menambah pengajuan Approval Visilab baru ke surat-list
      $lastApprovalId       = $this->md_visilab->getApprovalLastId();
      $lastApprovalId       = $lastApprovalId->id_approval;
      $dataList['id_srt']      = $lastApprovalId;
      $dataList['id_kat_surat']  = 22;
      $this->md_visilab->reset_increment("surat_list");
      $this->md_visilab->addSurat('list', $dataList);

      //menambah detail pengajuan pembiayaan (Dpkk)
      $this->md_visilab->reset_increment("approval_visilab_detail");
      $itung = $this->input->post('itung', TRUE);
      $itung1 = $this->input->post('itung1', TRUE);



      $dataDetailAPPROVAL['id_approval']    = $lastApprovalId;
      $dataDetailAPPROVAL['kode_detail']  = 1;
      if ($itung > 0) {
        for ($x = 1; $x < $itung; $x++) {
          $dataDetailAPPROVAL['nama_barang']      = $this->input->post('nama_barang[' . $x . ']', TRUE);
          $dataDetailAPPROVAL['acuan_hrg']      = $this->input->post('acuan_hrg[' . $x . ']', TRUE);
          $dataDetailAPPROVAL['hrg_ditawarkan']    = $this->input->post('hrg_ditawarkan[' . $x . ']', TRUE);
          $dataDetailAPPROVAL['approvall']      = $this->input->post('approvall[' . $x . ']', TRUE);
          $this->md_visilab->addSurat('dapproval', $dataDetailAPPROVAL);
          // var_dump('dpkk', $dataDetailPKK);
          // die;
        }
      }

      $idnotif = $dataDetailAPPROVAL['id_approval'];

      //APPROVAL NOTIFIKASI
      $urlNotif = "https://office.visiyosindo.id/visilab/show/detail/approval_harga/$idnotif";


      //send notif wa
      $dataWa = [
        'idPenerima1'   => 75,
        'idPenerima2'   => '',
        'namaSurat'     => 'Surat Approval Harga',
        'urlNotif'       => $urlNotif,
        'penerima'       => '_Head of Visilab_',
        'perihal'       => "",
        'kode'           => $data['kode']
      ];

      $this->notifWaAddSuratLink(1, $dataWa);


      //send notif Group wa
      $dataGroup = [
        'id'             => $idnotif,
        'idPenerima1'   => 'VISILAB',
        //'idPenerima1' 	=> 'Test Api Wa Group',
        'idPenerima2'   => '',
        'namaSurat'     => 'Surat Approval Harga',
        'urlNotif'       => $urlNotif,
        'penerima'       => 'Head of Visilab',
        'ttd_sebelum1'   => '',
        'ttd_sebelum2'   => '',
        'ttd_sebelum3'   => '',
        'ttd_sebelum4'   => ''
      ];

      $this->notifWaAprovGroup(1, 1, $dataGroup);




      /** LOG */
      addLog('Approval Harga VISILAB', 'Pengajuan Approval Harga Dengan Kode ' . $kode);
      ajaxReturnDie('success', 'Approval Harga Berhasil Diajukan', TRUE);
    } else if ($param == "berita_acara") {
      $this->md_visilab->reset_increment("ba_visilab");
      $idFpp = $this->md_visilab->getBaKodeId();
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

      $kodeFpp = $kodeFpp . "/BA/VISILAB/VYM/" . $bulan . "/" . $tahun;
      $data['kode_ba']      = $kodeFpp;

      $data['id_pengaju']     = sessPenggunaId();
      $tgl_input = $this->input->post('tanggal', TRUE);
      $data['tanggal']        = !empty($tgl_input) ? date_db_format($tgl_input) : date('Y-m-d');
      $data['analisis']       = $this->input->post('analisis', TRUE);
      $data['hasil']          = $this->input->post('hasil', TRUE);
      $data['penanganan']     = $this->input->post('penanganan', TRUE);
      $data['lampiran']        = $this->input->post('lampiran', TRUE);
      $data['jumlah_ttd']      = $this->input->post('jumlah_ttd', TRUE);
      $data['id_ttd1']        = $this->input->post('id_ttd1', TRUE);
      $data['id_ttd2']        = $this->input->post('id_ttd2', TRUE);
      $data['id_ttd3']        = $this->input->post('id_ttd3', TRUE);
      $data['id_ttd4']        = $this->input->post('id_ttd4', TRUE);
      $data['id_ttd5']        = $this->input->post('id_ttd5', TRUE);
      $data['id_ttd6']        = $this->input->post('id_ttd6', TRUE);
      $data['id_ttd7']        = $this->input->post('id_ttd7', TRUE);
      $data['status']         = 0;
      $this->md_visilab->addBeritaAcara($data);

      //ambil nomor
      $dataPenerima   = $this->md_pengguna->getById($data['id_ttd1']);
      $nmPenerima     = $dataPenerima[0]->nama;

      $lastBAlId       = $this->md_visilab->getBAlLastId();
      $lastBAlId       = $lastBAlId->id;


      //APPROVAL NOTIFIKASI
      $urlNotif = "https://office.visiyosindo.id/visilab/show/detail/berita_acara/$lastBAlId";


      //send notif wa
      $dataWa = [
        'idPenerima1'   => $data['id_ttd1'],
        'idPenerima2'   => '',
        'namaSurat'     => 'Berita Acara Visilab',
        'urlNotif'       => $urlNotif,
        'penerima'       => $nmPenerima,
        'perihal'       => "",
        'kode'           => $data['kode_ba']
      ];

      $this->notifWaAddSuratLink(1, $dataWa);






      /** LOG */
      addLog('Visilab', 'Permintaan Berita Acara Kode : ' . $data['kode_ba']);
      ajaxReturnDie('success', 'Berita Acara Berhasil Diajukan', TRUE);
    } else if ($param == "approval_po") {
      //menambah pengajuan PO
      $this->md_visilab->reset_increment("surat_po_visilab");
      $idFpp      = $this->md_visilab->getPoKodeId();
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

      $kodeFpp = $kodeFpp . "/Aprvl/VISILAB/VYM/" . $bulan . "/" . $tahun;
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
      $this->md_visilab->addPo($data);



      $lastPOid       = $this->md_visilab->getPOLastId();
      $lastPOid       = $lastPOid->id;


      //APPROVAL NOTIFIKASI
      $urlNotif = "https://office.visiyosindo.id/visilab/show/detail/approval_po/$lastPOid";


      //send notif wa
      $dataWa = [
        'idPenerima1'   => 75,
        'idPenerima2'   => '',
        'namaSurat'     => 'Approval PO Visilab',
        'urlNotif'       => $urlNotif,
        'penerima'       => '_Head of Visilab_',
        'perihal'       => "",
        'kode'           => $data['kode']
      ];

      $this->notifWaAddSuratLink(1, $dataWa);


      /** LOG */
      addLog('Visilab', 'Permintaan Approval PO Kode : ' . $kodeFpp);
      ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
    }
  }


  public function updateStok()
  {
    grantAccessFor('all');

    //update detail Stok Opname
    $id_sodetail = $this->input->post('id_sodetail');
    foreach ($id_sodetail as $key => $row) {
      $data['waktu_masuk'] = date_db_format($this->input->post('waktu_masuk')[$key]);

      $this->md_visilab->updateSoDetail(['id' => decrypt($row)], $data);
    }

    $ambilData   = $this->md_visilab->getDetailBywhereID(['f.id' => decrypt($row)]);
    $id_vs        = $ambilData[0]->id_vs;
    $dataUp['status'] = 3;
    $this->md_visilab->update(['id' => $id_vs], $dataUp);


    $ambilDataPengaju   = $this->md_visilab->getBywhereID(['f.id' => $id_vs]);
    $kodeFpp            = $ambilDataPengaju[0]->kode;
    $penggunaan            = $ambilDataPengaju[0]->penggunaan;

    $url = 'https://office.visiyosindo.id/visilab/show/detail/laporan_stok/' . $id_vs;


    //send notif wa Ke Kardonal
    $dataWa = [
      'idPenerima1'   => 751,
      'idPenerima2'   => '',
      'namaSurat'     => 'Pengembalian Alat Visilab',
      'penerima'       => 'Intan Kurnia',
      'perihal'       => $penggunaan,
      'kode'           => $kodeFpp
    ];

    $this->notifWaAddSurat(1, $dataWa);


    //Ke Grup Visilab

    $dataWa = [


      //'idPenerima1' 	=> 'Test Api Wa Group',
      //'idPenerima2' 	=> '',
      'idPenerima1'   => 'VISILAB',
      'idPenerima2'   => '',
      'namaSurat'     => 'Pengembalian Alat Visilab',
      'penerima'       => 'Team Visilab',
      'perihal'       => $penggunaan,
      'url'           => $url,
      'kode'           => $kodeFpp
    ];

    $this->notifWaAddSuratGroup(1, $dataWa);


    //add log
    $aksi = 'Visilab';
    $ket = 'Mengisi Waktu Masuk Alat';
    addlog($aksi, $ket);

    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
  }





  public function pagination($param = "", $param2 = "")
  {
    grantAccessFor('all');
    if ($param == 'laporan_stok') {
      $dt     = $this->md_visilab->getAll();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "2") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Pengeluaran Alat Disetujui</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Pengembalian Alat</span>';
        } else if ($row->status == "4") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Pengembalian Alat Disetujui</span>';
        } else if ($row->status == "5") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Pengeluaran Alat Ditolak</span>';
        } else {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Pengembalian Alat Ditolak</span>';
        }


        $kode  = '<a href="visilab/show/detail/laporan_stok/' . $row->idGc . '">' . $row->kode . '</a>';


        $th = array();
        $th[] = ++$start;
        $th[] = $kode;
        $th[] = $row->pengaju;
        $th[] = date('d-m-Y', strtotime($row->created_at));
        $th[] = $stat_surat;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'approval_harga') {

      $dt    = $this->md_visilab->getAllApproval();

      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {



        if ($row->status == 0) {
          $stat_surat = '<span class="badge badge-ecommerce badge-primary">Baru Diajukan</span>';
        } else if ($row->status == 1) {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Head of Visilab</span>';
        } else if ($row->status == 2) {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Pengajuan Disetujui</span>';
        } else {
          $stat_surat =  '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
        }


        $kode_surat  = '<a href="visilab/show/detail/approval_harga/' . $row->id_approval . '">' . $row->kode . '</a>';
        $cetak    = '<a href="surat/print_page/approval/' . $row->id_approval . '">print</a>';
        $myObjedit = encrypt($row->id_approval);
        $parJSONedit = encryptvym($myObjedit);
        $li_btn   = '
                            <div class="btn-group" role="group" aria-label="First group">
                            <button type="button" class="btn btn-sm btn-primary btn-edit" title="Edit Data" data-id="' . $parJSONedit . '" data-object="surat/editapproval/' . $parJSONedit . '"><i class="bx bx-pencil"></i></button>
                    
                          </div>';
        //	<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="'.$row->id_approval.'" data-object="surat/delete/surat/approval/'.$row->id_approval.'"><i class="bx bx-trash"></i></button> 


        $th = array();
        $th[] = ++$start;
        $th[] = $kode_surat;
        $th[] = date('d-m-Y', strtotime($row->tgl));
        $th[] = $row->nama_customer;
        $th[] = $row->nama;
        $th[] = $row->pengaju;
        $th[] = $stat_surat;
        //$th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'list_po') {
      $dt     = $this->md_visilab->getAllPo();
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {

        $kode  = '<a href="visilab/show/detail/approval_po/' . $row->idGc . '">' . $row->kode . '</a>';

        if ($row->status == "0") {
          $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
        } else if ($row->status == "1") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Director of Corporate Planning and Business Management</span>';
        } else if ($row->status == "3") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh HR & Legal</span>';
        } else if ($row->status == "5") {
          $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Head of Visilab</span>';
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
    }
  }


  public function paginationapprovalmodal($param1)
  {
    grantAccessFor('all');
    if ($param1 != "") {
      $dt    = $this->md_visilab->getDetailApprovalModaltable(decrypt($param1));
      $data  = array();
      $index = 1;
      foreach ($dt['data'] as $row) {
        $myObjedit = encrypt($row->id) . ',' . $row->nama_barang . ',' . $row->modal;
        $parJSONedit = encryptvym($myObjedit);
        $li_btn     = '
						<div class="btn-group" role="group" aria-label="First group">
							<button type="button" class="btn btn-sm btn-primary btn-edit" title="Edit Data" data-id="' . $parJSONedit . '"><i class="bx bx-pencil"></i></button>
						</div>';
        $th = array();
        $th[] = $index;
        $th[] = $row->id;
        $th[] = $row->nama_barang;
        $th[] = $row->acuan_hrg;
        $th[] = $row->hrg_ditawarkan;
        $th[] =  $row->modal;
        $th[] = $li_btn;
        $data[] = $th;
        $index++;
      }

      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    }
  }

  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  // edit surat Approval -------------------------------------------------------------------------------------------------------------------------------------------------------
  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  public function editmodalapproval($param1)
  {
    grantAccessFor('all');
    $par = explode(",", decryptvym($param1));
    $data['iddetail'] = $par[0];
    $data['namabarang'] = $par[1];
    $data['modalbarang'] = $par[2];
    echo json_encode($data);
    die;
  }

  public function updatemodalapproval()
  {

    grantAccessFor('all');

    $id =  decrypt($this->input->post('iddetail'));
    $data['namabarang'] = $this->input->post('namabarang');
    $data['modal']  = $this->input->post('modalbarang');

    $this->md_visilab->updatemodalapproval($id, $data['modal']);

    /** LOG */
    //addLog('Update PIC', 'Memperbarui data PIC "' . $data['namabarang'] . '"');
    ajaxReturnDie('success', 'Data Approval Modal ' . $data['namabarang'] . ' berhasil diperbarui', 'reload_table');
  }

  public function updateItemApproval()
  {
    grantAccessFor('all');

    $id = decrypt($this->input->post('id'));
    $approvall   = "1";



    $data = [
      'approvall' => $approvall,
    ];

    $this->md_visilab->updateSuratApprovallDetail($id, $data);

    // Debugging
    addLog('Update approval', 'Item ' . $id);
    ajaxReturnDie('success', 'Approve diubah', TRUE);
  }

  public function updateItemApprovalTolak()
  {
    grantAccessFor('all');

    $id = decrypt($this->input->post('id'));
    $approvall   = "2";



    $data = [
      'approvall' => $approvall,
    ];

    $this->md_visilab->updateSuratApprovallDetail($id, $data);

    // Debugging
    addLog('Update approval', 'Item ' . $id);
    ajaxReturnDie('success', 'Approve diubah', TRUE);
  }

  public function editItem($param1)
  {
    grantAccessFor('all');
    $id = decrypt($param1);
    $dt = $this->md_visilab->getDetailApprovalById($id);
    foreach ($dt as $row) {
      $row->id = encrypt($row->id);
    }
    echo json_encode($dt);
    die;
  }

  public function updateFromAccountingApproval($id)
  {
    grantAccessFor('all');

    $catatan     = $this->input->post('catatan', TRUE);
    $trf_komisi = $this->input->post('trf_komisi', TRUE);
    //$approvall 	= $this->input->post('approvall', TRUE);



    $data = [
      'trf_komisi' => nl2br($trf_komisi),
      'catatan' => nl2br($catatan),
      //'approvall' => $approvall,
    ];


    $this->md_visilab->updateSuratApprovall($id, $data);

    // Debugging
    addLog('Update approval', 'Catatan ' . $catatan);
    ajaxReturnDie('success', 'Catatan berhasil ditambahkan', TRUE);
  }

  public function ttd_approval($param1 = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');

    if ($param1 == "1") {
      if ($param2 == "ttd_1") {
        $id_approval = decrypt($this->input->post('id_approval'));
        $data['ttd_1'] = 1;
        $data['status'] = 1;
        $this->md_visilab->update_approval($id_approval, $data);



        $urlNotif = "https://office.visiyosindo.id/visilab/show/detail/approval_harga/$id_approval";


        $dataWa = [
          'id'             => $id_approval,
          'idPenerima1'   => '107',
          'idPenerima2'   => '',
          'namaSurat'     => 'Surat Approval Harga',
          'urlNotif'       => $urlNotif,
          'penerima'       => '*Senior Accounting and Finance*',
          'ttd_sebelum1'   => 'Head of Visilab',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => '',
          'ttd_sebelum4'   => ''
        ];

        $this->notifWaAprovHarga(1, 1, $dataWa);



        addLog('Approval Harga Visilab', 'Persetujuan Approval Harga Visilab');
        ajaxReturnDie('success', 'Surat Berhasil Disetujui', TRUE);
      } else if ($param2 == "ttd_2") {
        $id_approval = decrypt($this->input->post('id_approval'));
        $data['ttd_2'] = 1;
        $data['status'] = 2;
        $this->md_visilab->update_approval($id_approval, $data);



        $urlNotif = "https://office.visiyosindo.id/visilab/show/detail/approval_harga/$id_approval";


        $dataWa = [
          'id'             => $id_approval,
          'idPenerima1'   => '',
          'idPenerima2'   => '',
          'namaSurat'     => 'Surat Approval Harga',
          'urlNotif'       => $urlNotif,
          'penerima'       => '',
          'ttd_sebelum1'   => 'Head of Visilab',
          'ttd_sebelum2'   => 'Senior Accounting and Finance',
          'ttd_sebelum3'   => '',
          'ttd_sebelum4'   => ''
        ];

        $this->notifWaAprovHarga(1, 2, $dataWa);

        //send notif Group wa
        $dataGroup = [
          'id'             => $id_approval,
          'idPenerima1'   => 'VISILAB',
          //'idPenerima1' 	=> 'Test Api Wa Group',
          'idPenerima2'   => '',
          'namaSurat'     => 'Surat Approval Harga',
          'urlNotif'       => $urlNotif,
          'penerima'       => '',
          'ttd_sebelum1'   => 'Head of Visilab',
          'ttd_sebelum2'   => 'Senior Accounting and Finance',
          'ttd_sebelum3'   => '',
          'ttd_sebelum4'   => ''
        ];

        $this->notifWaAprovGroup(1, 2, $dataGroup);



        addLog('Approval Harga Visilab', 'Persetujuan Approval Harga Visilab');
        ajaxReturnDie('success', 'Surat Berhasil Disetujui', TRUE);
      }
    } else if ($param1 == "2") {
      if ($param2 == "ttd_1") {
        $id_approval = decrypt($this->input->post('id_approval'));
        $data['ttd_1'] = 2;
        $data['status'] = 3;
        $this->md_visilab->update_approval($id_approval, $data);

        //send notif wa
        $dataWa = [
          'id'       => $id_approval,
          'namaSurat'     => 'Surat Approval Harga',
          'idPenolak'     => sessPenggunaId(),
          'namaPenolak'   => '*Head of Visilab*'
        ];
        $this->notifWaRejectHarga($dataWa);

        addLog('Approval Harga Visilab', 'Penolakan Pengajuan Approval Harga');
        ajaxReturnDie('success', 'Surat Berhasil Ditolak', TRUE);
      } else if ($param2 == "ttd_2") {
        $id_approval = decrypt($this->input->post('id_approval'));
        $data['ttd_2'] = 2;
        $data['status'] = 4;
        $this->md_visilab->update_approval($id_approval, $data);

        // //send notif wa
        $dataWa = [
          'id'       => $id_approval,
          'namaSurat'     => 'Surat Approval Harga',
          'idPenolak'     => sessPenggunaId(),
          'namaPenolak'   => '*Senior Accounting and Finance*'
        ];
        $this->notifWaRejectHarga($dataWa);

        addLog('Approval Harga Visilab', 'Penolakan Pengajuan Approval Harga');
        ajaxReturnDie('success', 'Surat Berhasil Ditolak', TRUE);
      }
    }
  }




  //UPDATE

  public function ttd_setujui($param1 = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');

    if ($param1 == "stok") {
      if ($param2 == "1") {
        if ($param3 == "ttd_1") {

          $id_sp = $this->input->post('id');
          $data['status'] = 2;
          $data['ttd_1'] = 1;
          $this->md_visilab->update(['id' => $id_sp], $data);

          $ambilDataPengaju   = $this->md_visilab->getBywhereID(['f.id' => $id_sp]);
          $namaPengaju        = $ambilDataPengaju[0]->pengaju;
          $kode               = $ambilDataPengaju[0]->kode;
          $perihal            = $ambilDataPengaju[0]->penggunaan;
          $idpengaju          = $ambilDataPengaju[0]->idPengaju;

          //send notif wa
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => '',
            'idPenerima2'   => '',
            'namaSurat'     => 'Pengeluaran Alat Visilab',
            'namaPengaju'   => $namaPengaju,
            'kode'           => $kode,
            'perihal'       => $perihal,
            'idpengaju'     => $idpengaju,
            'ttd_sebelum1'   => 'Intan Kurnia',
            'ttd_sebelum2'   => '',
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprovPb(1, 2, $dataWa);

          addLog('VISILAB', 'Pengeluaran Alat Disetujui');
          ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
        }
      } else if ($param2 == "2") {
        if ($param3 == "ttd_1") {

          $id_sp = $this->input->post('id');
          $data['status'] = 4;
          $data['ttd_1'] = 1;
          $this->md_visilab->update(['id' => $id_sp], $data);

          $ambilDataPengaju   = $this->md_visilab->getBywhereID(['f.id' => $id_sp]);
          $namaPengaju        = $ambilDataPengaju[0]->pengaju;
          $kode               = $ambilDataPengaju[0]->kode;
          $perihal            = $ambilDataPengaju[0]->penggunaan;
          $idpengaju          = $ambilDataPengaju[0]->idPengaju;

          //send notif wa
          $dataWa = [
            'id'             => $id_sp,
            'idPenerima1'   => '',
            'idPenerima2'   => '',
            'namaSurat'     => 'Pengembalian Alat Visilab',
            'namaPengaju'   => $namaPengaju,
            'kode'           => $kode,
            'perihal'       => $perihal,
            'idpengaju'     => $idpengaju,
            'ttd_sebelum1'   => 'Intan Kurnia',
            'ttd_sebelum2'   => '',
            'ttd_sebelum3'   => ''
          ];

          $this->notifWaAprovPb(1, 2, $dataWa);

          addLog('VISILAB', 'Pengembalian Alat Disetujui');
          ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
        }
      }
    } else if ($param1 == "ba") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['ttd_1'] = 1;
        $this->md_visilab->updateBeritaAcara($id_sp, $data);

        $ambilDataPengaju   = $this->md_visilab->getBeritaAcaraById($id_sp);
        $id_ttd2            = $ambilDataPengaju[0]->id_ttd2;
        $nama_2             = $ambilDataPengaju[0]->nama2;
        $nama1              = $ambilDataPengaju[0]->nama1;
        $jumlah_ttd         = $ambilDataPengaju[0]->jumlah_ttd;

        $urlNotif = "https://office.visiyosindo.id/visilab/show/detail/berita_acara/$id_sp";


        if ($jumlah_ttd == 1) {
          $kirim = 2;
          $idttd = "";
          $nama2 = "";
        } else {
          $kirim = 1;
          $idttd = $id_ttd2;
          $nama2 = $nama_2;
        }


        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => $idttd,
          'idPenerima2'   => '',
          'namaSurat'     => 'Berita Acara Visilab',
          'urlNotif'       => $urlNotif,
          'penerima'       => $nama2,
          'ttd_sebelum1'   => $nama1,
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => '',
          'ttd_sebelum4'   => ''
        ];

        $this->notifWaAprovBa(1, $kirim, $dataWa);



        addLog('Visilab', 'Permintaan Berita Acara Disetujui ' . $nama1);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['ttd_2'] = 1;
        $this->md_visilab->updateBeritaAcara($id_sp, $data);

        $ambilDataPengaju   = $this->md_visilab->getBeritaAcaraById($id_sp);
        $nama2              = $ambilDataPengaju[0]->nama2;
        $id_ttd3            = $ambilDataPengaju[0]->id_ttd3;
        $nama_3             = $ambilDataPengaju[0]->nama3;
        $nama1              = $ambilDataPengaju[0]->nama1;
        $jumlah_ttd         = $ambilDataPengaju[0]->jumlah_ttd;

        $urlNotif = "https://office.visiyosindo.id/visilab/show/detail/berita_acara/$id_sp";


        if ($jumlah_ttd == 2) {
          $kirim = 2;
          $idttd = "";
          $nama3 = "";
        } else {
          $kirim = 1;
          $idttd = $id_ttd3;
          $nama3 = $nama_3;
        }


        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => $idttd,
          'idPenerima2'   => '',
          'namaSurat'     => 'Berita Acara Visilab',
          'urlNotif'       => $urlNotif,
          'penerima'       => $nama3,
          'ttd_sebelum1'   => $nama1,
          'ttd_sebelum2'   => $nama2,
          'ttd_sebelum3'   => '',
          'ttd_sebelum4'   => ''
        ];

        $this->notifWaAprovBa(1, $kirim, $dataWa);



        addLog('Visilab', 'Permintaan Berita Acara Disetujui ' . $nama2);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_3") {

        $id_sp = $this->input->post('id');
        $data['ttd_3'] = 1;
        $this->md_visilab->updateBeritaAcara($id_sp, $data);


        $ambilDataPengaju   = $this->md_visilab->getBeritaAcaraById($id_sp);
        $nama2              = $ambilDataPengaju[0]->nama2;
        $id_ttd4            = $ambilDataPengaju[0]->id_ttd4;
        $nama3              = $ambilDataPengaju[0]->nama3;
        $nama_4             = $ambilDataPengaju[0]->nama4;
        $nama1              = $ambilDataPengaju[0]->nama1;
        $jumlah_ttd         = $ambilDataPengaju[0]->jumlah_ttd;

        $urlNotif = "https://office.visiyosindo.id/visilab/show/detail/berita_acara/$id_sp";


        if ($jumlah_ttd == 3) {
          $kirim = 2;
          $idttd = "";
          $nama4 = "";
        } else {
          $kirim = 1;
          $idttd = $id_ttd4;
          $nama4 = $nama_4;
        }


        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => $idttd,
          'idPenerima2'   => '',
          'namaSurat'     => 'Berita Acara Visilab',
          'urlNotif'       => $urlNotif,
          'penerima'       => $nama4,
          'ttd_sebelum1'   => $nama1,
          'ttd_sebelum2'   => $nama2,
          'ttd_sebelum3'   => $nama3,
          'ttd_sebelum4'   => ''
        ];

        $this->notifWaAprovBa(1, $kirim, $dataWa);



        addLog('Visilab', 'Permintaan Berita Acara Disetujui ' . $nama3);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_4") {

        $id_sp = $this->input->post('id');
        $data['ttd_4'] = 1;
        $this->md_visilab->updateBeritaAcara($id_sp, $data);

        $ambilDataPengaju   = $this->md_visilab->getBeritaAcaraById($id_sp);
        $id_ttd2            = $ambilDataPengaju[0]->id_ttd2;
        $nama2              = $ambilDataPengaju[0]->nama2;
        $id_ttd5            = $ambilDataPengaju[0]->id_ttd5;
        $nama3              = $ambilDataPengaju[0]->nama3;
        $nama4              = $ambilDataPengaju[0]->nama4;
        $nama_5             = $ambilDataPengaju[0]->nama5;
        $nama1              = $ambilDataPengaju[0]->nama1;
        $jumlah_ttd         = $ambilDataPengaju[0]->jumlah_ttd;

        $urlNotif = "https://office.visiyosindo.id/visilab/show/detail/berita_acara/$id_sp";


        if ($jumlah_ttd == 4) {
          $kirim = 2;
          $idttd = "";
          $nama5 = "";
        } else {
          $kirim = 1;
          $idttd = $id_ttd5;
          $nama5 = $nama_5;
        }


        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => $idttd,
          'idPenerima2'   => '',
          'namaSurat'     => 'Berita Acara Visilab',
          'urlNotif'       => $urlNotif,
          'penerima'       => $nama5,
          'ttd_sebelum1'   => $nama1,
          'ttd_sebelum2'   => $nama2,
          'ttd_sebelum3'   => $nama3,
          'ttd_sebelum4'   => $nama4
        ];

        $this->notifWaAprovBa(1, $kirim, $dataWa);



        addLog('Visilab', 'Permintaan Berita Acara Disetujui ' . $nama4);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_5") {

        $id_sp = $this->input->post('id');
        $data['ttd_5'] = 1;
        $this->md_visilab->updateBeritaAcara($id_sp, $data);

        $ambilDataPengaju   = $this->md_visilab->getBeritaAcaraById($id_sp);
        $id_ttd2            = $ambilDataPengaju[0]->id_ttd2;
        $nama2             = $ambilDataPengaju[0]->nama2;
        $id_ttd3            = $ambilDataPengaju[0]->id_ttd4;
        $nama3             = $ambilDataPengaju[0]->nama3;
        $nama4             = $ambilDataPengaju[0]->nama4;
        $nama5             = $ambilDataPengaju[0]->nama5;
        $nama1              = $ambilDataPengaju[0]->nama1;
        $jumlah_ttd         = $ambilDataPengaju[0]->jumlah_ttd;

        $urlNotif = "https://office.visiyosindo.id/visilab/show/detail/berita_acara/$id_sp";


        $gabungnama = $nama4 . ' - ' . $nama5;

        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '',
          'idPenerima2'   => '',
          'namaSurat'     => 'Berita Acara Visilab',
          'urlNotif'       => $urlNotif,
          'penerima'       => '',
          'ttd_sebelum1'   => $nama1,
          'ttd_sebelum2'   => $nama2,
          'ttd_sebelum3'   => $nama3,
          'ttd_sebelum4'   => $gabungnama
        ];

        $this->notifWaAprovBa(1, 2, $dataWa);



        addLog('Visilab', 'Permintaan Berita Acara Disetujui ' . $nama5);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "po") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1'] = 1;
        $this->md_visilab->updatePo($id_sp, $data);


        $ambilDataPengaju   = $this->md_visilab->getDetailPoById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;
        $jenis              = $ambilDataPengaju[0]->jenis;
        $urlNotif = "https://office.visiyosindo.id/visilab/show/detail/approval_po/$id_sp";

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
          'namaSurat'     => 'Approval PO Visilab',
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'perihal'       => $perihal,
          'idpengaju'     => $idpengaju,
          'ttd_sebelum1'   => 'Head of Visilab',
          'ttd_sebelum2'   => $ttd_2,
          'ttd_sebelum3'   => $ttd_3
        ];

        $this->notifWaAprovUniversal(1, 2, $dataWa);

        addLog('Visilab', 'Approval PO disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd_2'] = 1;
        $this->md_visilab->updatePo($id_sp, $data);


        $ambilDataPengaju   = $this->md_visilab->getDetailPoById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;
        $urlNotif = "https://office.visiyosindo.id/visilab/show/detail/approval_po/$id_sp";

        //Notif ke Wa Pengaju
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '23',
          'idPenerima2'   => '',
          'namaSurat'     => 'Approval PO Visilab',
          'urlNotif'       => $urlNotif,
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'perihal'       => $perihal,
          'idpengaju'     => $idpengaju,
          'penerima'       => '_Director of Corporate Planning and Business Management_',
          'ttd_sebelum1'   => 'Head of Visilab',
          'ttd_sebelum2'   => 'HR and Legal',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovUniversal(1, 1, $dataWa);

        addLog('Visilab', 'Approval PO disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_3") {

        $id_sp = $this->input->post('id');
        $data['status'] = 5;
        $data['ttd_3'] = 1;
        $this->md_visilab->updatePo($id_sp, $data);


        $ambilDataPengaju   = $this->md_visilab->getDetailPoById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;
        $jenis              = $ambilDataPengaju[0]->jenis;


        $urlNotif = "https://office.visiyosindo.id/visilab/show/detail/approval_po/$id_sp";

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
          'namaSurat'     => 'Approval PO Visilab',
          'urlNotif'       => $urlNotif,
          'namaPengaju'   => $namaPengaju,
          'kode'           => $kode,
          'perihal'       => $perihal,
          'idpengaju'     => $idpengaju,
          'penerima'       => $penerima,
          'ttd_sebelum1'   => 'Head of Visilab',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovUniversal(1, 1, $dataWa);

        addLog('Visilab', 'Approval PO disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    }
  }


  public function ttd_tolak($param1 = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');

    if ($param1 == "stok") {
      if ($param2 == "1") {
        if ($param3 == "ttd_1") {


          $id_sp = $this->input->post('id');
          $data['status'] = 5;
          $data['ttd_1'] = 2;
          $this->md_visilab->update(['id' => $id_sp], $data);

          $ambilDataPengaju   = $this->md_visilab->getBywhereID(['f.id' => $id_sp]);
          $namaPengaju        = $ambilDataPengaju[0]->pengaju;
          $kode               = $ambilDataPengaju[0]->kode;
          $perihal            = $ambilDataPengaju[0]->penggunaan;
          $idpengaju          = $ambilDataPengaju[0]->idPengaju;

          //send notif wa
          $dataWa = [
            'namaSurat'     => 'Pengeluaran Alat Visilab',
            'id'       => $id_sp,
            'idPenolak'     => 751,
            'namaPengaju'   => $namaPengaju,
            'kode'  => $kode,
            'perihal'   => $perihal,
            'idpengaju'   => $idpengaju,
            'namaPenolak'   => '*Intan Kurnia*'
          ];
          $this->notifWaRejectPb($dataWa);
          /** LOG */
          addLog('VISILAB', 'Pengeluaran Alat ditolak');
          ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
        }
      } else if ($param2 == "2") {
        if ($param3 == "ttd_1") {


          $id_sp = $this->input->post('id');
          $data['status'] = 6;
          $data['ttd_1'] = 2;
          $this->md_visilab->update(['id' => $id_sp], $data);

          $ambilDataPengaju   = $this->md_visilab->getBywhereID(['f.id' => $id_sp]);
          $namaPengaju        = $ambilDataPengaju[0]->pengaju;
          $kode               = $ambilDataPengaju[0]->kode;
          $perihal            = $ambilDataPengaju[0]->penggunaan;
          $idpengaju          = $ambilDataPengaju[0]->idPengaju;

          //send notif wa
          $dataWa = [
            'namaSurat'     => 'Pengembalian Alat Visilab',
            'id'       => $id_sp,
            'idPenolak'     => 751,
            'namaPengaju'   => $namaPengaju,
            'kode'  => $kode,
            'perihal'   => $perihal,
            'idpengaju'   => $idpengaju,
            'namaPenolak'   => '*Intan Kurnia*'
          ];
          $this->notifWaRejectPb($dataWa);
          /** LOG */
          addLog('VISILAB', 'Pengembalian Alat ditolak');
          ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
        }
      }
    } else if ($param1 == "ba") {
      if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['ttd_1'] = 2;
        $this->md_visilab->updateBeritaAcara($id_sp, $data);


        $ambilDataPengaju   = $this->md_visilab->getBeritaAcaraById($id_sp);
        $id_penolak          = $ambilDataPengaju[0]->id_ttd1;
        $nama_penolak       = $ambilDataPengaju[0]->nama1;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Berita Acara Visilab',
          'id'       => $id_sp,
          'idPenolak'     => $id_penolak,
          'namaPenolak'   => $nama_penolak
        ];
        $this->notifWaRejectBa($dataWa);
        /** LOG */
        addLog('Visilab', 'Permintaan Berita Acara Ditolak oleh ' . $nama_penolak);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['ttd_2'] = 2;
        $this->md_visilab->updateBeritaAcara($id_sp, $data);


        $ambilDataPengaju   = $this->md_visilab->getBeritaAcaraById($id_sp);
        $id_penolak          = $ambilDataPengaju[0]->id_ttd2;
        $nama_penolak       = $ambilDataPengaju[0]->nama2;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Berita Acara Visilab',
          'id'       => $id_sp,
          'idPenolak'     => $id_penolak,
          'namaPenolak'   => $nama_penolak
        ];
        $this->notifWaRejectBa($dataWa);
        /** LOG */
        addLog('Visilab', 'Permintaan Berita Acara Ditolak oleh ' . $nama_penolak);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_3") {

        $id_sp = $this->input->post('id');
        $data['ttd_3'] = 2;
        $this->md_visilab->updateBeritaAcara($id_sp, $data);


        $ambilDataPengaju   = $this->md_visilab->getBeritaAcaraById($id_sp);
        $id_penolak          = $ambilDataPengaju[0]->id_ttd3;
        $nama_penolak       = $ambilDataPengaju[0]->nama3;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Berita Acara Visilab',
          'id'       => $id_sp,
          'idPenolak'     => $id_penolak,
          'namaPenolak'   => $nama_penolak
        ];
        $this->notifWaRejectBa($dataWa);
        /** LOG */
        addLog('Visilab', 'Permintaan Berita Acara Ditolak oleh ' . $nama_penolak);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_4") {

        $id_sp = $this->input->post('id');
        $data['ttd_4'] = 2;
        $this->md_visilab->updateBeritaAcara($id_sp, $data);


        $ambilDataPengaju   = $this->md_visilab->getBeritaAcaraById($id_sp);
        $id_penolak          = $ambilDataPengaju[0]->id_ttd4;
        $nama_penolak       = $ambilDataPengaju[0]->nama4;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Berita Acara Visilab',
          'id'       => $id_sp,
          'idPenolak'     => $id_penolak,
          'namaPenolak'   => $nama_penolak
        ];
        $this->notifWaRejectBa($dataWa);
        /** LOG */
        addLog('Visilab', 'Permintaan Berita Acara Ditolak oleh ' . $nama_penolak);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_5") {

        $id_sp = $this->input->post('id');
        $data['ttd_5'] = 2;
        $this->md_visilab->updateBeritaAcara($id_sp, $data);


        $ambilDataPengaju   = $this->md_visilab->getBeritaAcaraById($id_sp);
        $id_penolak          = $ambilDataPengaju[0]->id_ttd5;
        $nama_penolak       = $ambilDataPengaju[0]->nama5;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Berita Acara Visilab',
          'id'       => $id_sp,
          'idPenolak'     => $id_penolak,
          'namaPenolak'   => $nama_penolak
        ];
        $this->notifWaRejectBa($dataWa);
        /** LOG */
        addLog('Visilab', 'Permintaan Berita Acara Ditolak oleh ' . $nama_penolak);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    } else if ($param1 == "po") {
      if ($param2 == "ttd_1") {


        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_1']  = 2;
        $this->md_visilab->updatePo($id_sp, $data);

        $ambilDataPengaju   = $this->md_visilab->getDetailPoById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Approval PO Visilab',
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
        addLog('Visilab', 'Approval PO Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_2") {


        $id_sp = $this->input->post('id');
        $data['status'] = 4;
        $data['ttd_2']  = 2;
        $this->md_visilab->updatePo($id_sp, $data);

        $ambilDataPengaju   = $this->md_visilab->getDetailPoById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Approval PO Visilab',
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
        addLog('Visilab', 'Approval PO Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_3") {


        $id_sp = $this->input->post('id');
        $data['status'] = 6;
        $data['ttd_3']  = 2;
        $this->md_visilab->updatePo($id_sp, $data);

        $ambilDataPengaju   = $this->md_visilab->getDetailPoById($id_sp);
        $namaPengaju        = $ambilDataPengaju[0]->pengaju;
        $kode               = $ambilDataPengaju[0]->kode;
        $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
        $idpengaju          = $ambilDataPengaju[0]->idPengaju;

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Approval PO Visilab',
          'id'       => $id_sp,
          'idPenolak'     => 75,
          'namaPengaju'   => $namaPengaju,
          'kode'  => $kode,
          'perihal'   => $perihal,
          'idpengaju'   => $idpengaju,
          'namaPenolak'   => '*Head of Visilab*'
        ];
        $this->notifWaRejectPb($dataWa);
        /** LOG */
        addLog('Visilab', 'Approval PO Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    }
  }





  public function print_page($param1 = "", $param2 = "")
  {
    grantAccessFor('all');

    if ($param1 == 'laporan_stok') {

      $gc              = $this->md_visilab->getBywhereID(['f.id' => $param2]);

      $dt = [
        'title_pdf'  => 'laporan_stok',
        'object'    => $param1,
        'data_stok'  => $this->md_visilab->getBywhereID(['f.id' => $param2]),
        'detail_stok'  => $this->md_visilab->getDetailBywhereID(['f.id_vs' => $param2])
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Permintaan Pengeluaran Alat  ' . $gc[0]->pengaju;

      // page html yang akan di jadikan ke pdf

      $html = $this->load->view('pages/v_print/print_visilab_stok', $dt, true);

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');

      //add log
      $aksi = 'Visilab';
      $ket = 'Cetak Kode ' . $gc[0]->kode;
      addlog($aksi, $ket);
    } else if ($param1 == 'printByMonth') {
      //$gc							= $this->md_visilab->getBywhereID(['f.id' => $param2]);

      $dt = [
        'title_pdf'  => 'laporan_stok',
        'object'    => $param1,
        'month'      => $param2,
        //'data_stok'	=> $this->md_visilab->getBywhereID(['f.id' => $param2]),
        'detail_stok'  => $this->md_visilab->getDetailByMonth(['f.created_at' => $param2])
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Laporan Stok Keluar Masuk Alat';

      // page html yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_visilab_stok_month', $dt, true);

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');

      //add log
      $aksi = 'Visilab';
      $ket = 'Cetak Bulanan';
      addlog($aksi, $ket);
    } else if ($param1 == 'approval_harga') {

      $gc              = $this->md_visilab->getApprovalById($param2);

      $dt = [
        'title_pdf'  => 'laporan_stok',
        'object'    => $param1,
        'data_approval'  => $this->md_visilab->getApprovalById($param2),
        'detail_approval'  => $this->md_visilab->getDetailApproval($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Approval Harga  ' . $gc[0]->pengaju;

      // page html yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_approval_harga', $dt, true);

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');

      //add log
      $aksi = 'Visilab';
      $ket = 'Cetak Approval Harga ' . $gc[0]->kode;
      addlog($aksi, $ket);
    } else if ($param1 == 'printSuhuByMonth') {
      //$gc							= $this->md_visilab->getBywhereID(['f.id' => $param2]);

      $dt = [
        'title_pdf'  => 'laporan_suhu',
        'object'    => $param1,
        'month'      => $param2,
        //'data_stok'	=> $this->md_visilab->getBywhereID(['f.id' => $param2]),
        'detail_suhu'  => $this->md_visilab->getSuhuByMonth(['b.created_at' => $param2])
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Laporan Suhu';

      // page html yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_visilab_suhu_month', $dt, true);

      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');

      //add log
      $aksi = 'Visilab';
      $ket = 'Cetak Suhu Bulanan';
      addlog($aksi, $ket);
    } else if ($param1 == 'ba') {

      $gc              = $this->md_visilab->getBeritaAcaraById($param2);

      $dt = [
        'title_pdf'  => 'ba',
        'object'  => $param1,
        'data_ba'  => $this->md_visilab->getBeritaAcaraById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Berita Acara ' . $gc[0]->pengaju;

      // page htmk yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_ba_visilab', $dt, true);
      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    } else if ($param1 == 'approval_po') {

      $gc              = $this->md_visilab->getDetailPoById($param2);

      $dt = [
        'title_pdf'  => 'po',
        'object'  => $param1,
        'data_po'  => $this->md_visilab->getDetailPoById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Approval PO ' . $gc[0]->identitas_pelanggan;

      // page html yang akan di jadikan ke pdf
      $html = $this->load->view('pages/v_print/print_approval_po_visilab', $dt, true);


      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
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
        'namaPengaju' => $namaPengaju,
        'perihal'     => urlencode($detail['perihal']),
        'namaPenerima'   => urlencode($detail['penerima'])
      ];
      waSuratOpen($dataWa);
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

  public function notifWaAddSuratGroup($ulang, $detail)
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

      //abaikan error
      error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 0);
      //

      $dataWa = [
        'namaSurat'   => urlencode($detail['namaSurat']),
        'noPenerima'   => $idpenerima,
        'kodeSurat'   => $detail['kode'],
        'url'         => $detail['url'],
        'namaPengaju' => $namaPengaju,
        'perihal'     => urlencode($detail['perihal']),
        'namaPenerima'   => urlencode($detail['penerima'])
      ];
      waSuratOpenGroup($dataWa);
    }
  }


  public function notifWaAprovPb($ulang, $param, $detail)
  {


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
        'perihal'       => $detail['perihal'],
        'ttd_sebelum1'   => urlencode($detail['ttd_sebelum1']),
        'ttd_sebelum2'   => $detail['ttd_sebelum2'],
        'ttd_sebelum3'   => $detail['ttd_sebelum3'],
        'ttd_sebelum4'   => ''
      ];

      if ($param == '1') {
        waSuratAprovOnProg($dataWa);
      } else if ($param == '2') {
        waSuratAprovAll($dataWa);
      }
    }
  }






  public function notifWaRejectPb($detail)
  {

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


  public function notifWaAprovHarga($ulang, $param, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_visilab->getApprovalById($detail['id']);
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode               = $ambilDataPengaju[0]->kode;
    $perihal            = $ambilDataPengaju[0]->nama_customer;
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;


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
      $dataWa = [
        'namaSurat'     => urlencode($detail['namaSurat']),
        'urlNotif'       => urlencode($detail['urlNotif']),
        'noPenerima'    => $nope,
        'kodeSurat'     => $kode,
        'namaPengaju'   => urlencode($namaPengaju),
        'namaPenerima'   => urlencode($detail['penerima']),
        'perihal'       => urlencode($perihal),
        'ttd_sebelum1'   => urlencode($detail['ttd_sebelum1']),
        'ttd_sebelum2'   => urlencode($detail['ttd_sebelum2']),
        'ttd_sebelum3'   => urlencode($detail['ttd_sebelum3']),
        'ttd_sebelum4'   => urlencode($detail['ttd_sebelum4'])
      ];

      if ($param == '1') {
        waSuratAprovOnProgVisilab($dataWa);
      } else if ($param == '2') {
        waSuratAprovAllVisilab($dataWa);
      }
    }
  }




  public function notifWaAprovGroup($ulang, $param, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_visilab->getApprovalById($detail['id']);
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode               = $ambilDataPengaju[0]->kode;
    $perihal            = $ambilDataPengaju[0]->nama_customer;
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;


    //send notif wa
    for ($i = 1; $i <= $ulang; $i++) {
      if ($i == 1) {
        $idpenerima = $detail['idPenerima1'];
      } else if ($i == 2) {

        $idpenerima = $detail['idPenerima2'];
      }

      $dataPenerima   = $this->md_pengguna->getById($idpengaju);
      $nope           = $dataPenerima[0]->no_hp;
      $nmPengaju      = $dataPenerima[0]->nama;
      $dataWa = [
        'namaSurat'     => urlencode($detail['namaSurat']),
        'urlNotif'       => urlencode($detail['urlNotif']),
        'noPenerima'    => $idpenerima,
        'kode'           => $kode,
        'namaPengaju'   => urlencode($namaPengaju),
        'perihal'       => urlencode($perihal),
        'namaPenerima'   => urlencode($detail['penerima']),
        'ttd_sebelum1'   => urlencode($detail['ttd_sebelum1']),
        'ttd_sebelum2'   => urlencode($detail['ttd_sebelum2']),
        'ttd_sebelum3'   => urlencode($detail['ttd_sebelum3']),
        'ttd_sebelum4'   => urlencode($detail['ttd_sebelum4'])
      ];

      if ($param == '1') {
        waAddGroupVisilab($dataWa);
      } else if ($param == '2') {
        waAllGroupVisilab($dataWa);
      }
    }
  }


  public function notifWaRejectHarga($detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_visilab->getApprovalById($detail['id']);
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode               = $ambilDataPengaju[0]->kode;
    $perihal            = $ambilDataPengaju[0]->nama_customer;
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;

    //if($idpengaju == 1){
    //   $idpengaju == 63;
    //}

    //ambil nomor
    $dataPenerima       = $this->md_pengguna->getById($idpengaju);
    $nope               = $dataPenerima[0]->no_hp;
    $dataPenolak         = $this->md_pengguna->getById($detail['idPenolak']);
    $nopePenolak        = $dataPenolak[0]->no_hp;

    //send notif wa
    $dataWa = [
      'namaSurat'     => urlencode($detail['namaSurat']),
      'noPenerima'     => $nope,
      'kodeSurat'     => $kode,
      'namaPengaju'   => urlencode($namaPengaju),
      'perihal'       => urlencode($perihal),
      'namaPenolak'   => urlencode($detail['namaPenolak']),
      'noPenolak'     => $nopePenolak
    ];
    waSuratReject($dataWa);
  }






  public function notifWaAprovBa($ulang, $param, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_visilab->getBeritaAcaraById($detail['id']);
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode               = $ambilDataPengaju[0]->kode_ba;
    $perihal            = '';
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;


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
      $dataWa = [
        'namaSurat'     => urlencode($detail['namaSurat']),
        'urlNotif'       => urlencode($detail['urlNotif']),
        'noPenerima'    => $nope,
        'kodeSurat'     => $kode,
        'namaPengaju'   => urlencode($namaPengaju),
        'namaPenerima'   => urlencode($detail['penerima']),
        'perihal'       => urlencode($perihal),
        'ttd_sebelum1'   => urlencode($detail['ttd_sebelum1']),
        'ttd_sebelum2'   => urlencode($detail['ttd_sebelum2']),
        'ttd_sebelum3'   => urlencode($detail['ttd_sebelum3']),
        'ttd_sebelum4'   => urlencode($detail['ttd_sebelum4'])
      ];

      if ($param == '1') {
        waSuratAprovOnProgVisilab($dataWa);
      } else if ($param == '2') {
        waSuratAprovAllVisilab($dataWa);
      }
    }
  }



  public function notifWaRejectBa($detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_visilab->getBeritaAcaraById($detail['id']);
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode               = $ambilDataPengaju[0]->kode_ba;
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;

    //ambil nomor
    $dataPenerima   = $this->md_pengguna->getById($idpengaju);
    $nope           = $dataPenerima[0]->no_hp;
    $dataPenolak     = $this->md_pengguna->getById($detail['idPenolak']);
    $nopePenolak    = $dataPenolak[0]->no_hp;

    $perihal = '';

    //send notif wa
    $dataWa = [
      'namaSurat'   => $detail['namaSurat'],
      'noPenerima'   => $nope,
      'kodeSurat'   => $kode,
      'perihal'       => urlencode($perihal),
      'namaPengaju' => $namaPengaju,
      'namaPenolak' => $detail['namaPenolak'],
      'noPenolak'   => $nopePenolak
    ];
    waSuratReject($dataWa);
  }





  public function notifWaAprovUniversal($ulang, $param, $detail)
  {

    $perihal            = '';

    //send notif wa
    for ($i = 1; $i <= $ulang; $i++) {
      if ($i == 1) {
        //id pengaju surat
        if ($param == 2) {
          $idpenerima =  $detail['idpengaju'];
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
        'namaSurat'     => urlencode($detail['namaSurat']),
        'urlNotif'       => urlencode($detail['urlNotif']),
        'noPenerima'    => $nope,
        'kodeSurat'     => $detail['kode'],
        'namaPengaju'   => urlencode($detail['namaPengaju']),
        'namaPenerima'   => urlencode($detail['penerima']),
        'perihal'       => urlencode($perihal),
        'ttd_sebelum1'   => urlencode($detail['ttd_sebelum1']),
        'ttd_sebelum2'   => urlencode($detail['ttd_sebelum2']),
        'ttd_sebelum3'   => urlencode($detail['ttd_sebelum3']),
        'ttd_sebelum4'   => urlencode($detail['ttd_sebelum4'])
      ];

      if ($param == '1') {
        waSuratAprovOnProgVisilab($dataWa);
      } else if ($param == '2') {
        waSuratAprovAllVisilab($dataWa);
      }
    }
  }











  //==================================================
  //================== Notif WA       ================
  //==================================================




  // ==============================================
  // ======== Data Alat Visilab ===================
  // ==============================================

  public function addAlat()
  {
    grantAccessFor('all');

    $data['nama']    = $this->input->post('nama');
    $data['serial_number']       = $this->input->post('serial_number');
    //$data['tgl_kalibrasi']			= date_db_format($this->input->post('tgl_kalibrasi', TRUE));
    $data['tgl_kalibrasi']       = $this->input->post('tgl_kalibrasi');
    $data['tgl_masa_kalibrasi']       = $this->input->post('tgl_masa_kalibrasi');
    $data['tempat_kalibrasi']       = $this->input->post('tempat_kalibrasi');
    //checkEmptyForm($data);

    $this->md_visilab->addAlat($data);





    //add log
    $aksi = 'Master Data Visilab';
    $ket = 'Menambahkan Data Alat - ' . $data['nama'];
    addlog($aksi, $ket);

    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
  }

  public function edit($param1)
  {
    grantAccessFor('all');

    $id = decrypt($param1);
    $dt = $this->md_visilab->getById($id);
    foreach ($dt as $row) {
      $row->id = encrypt($row->id);
    }
    echo json_encode($dt);
    die;
  }

  public function deleteAlat($param)
  {
    grantAccessFor('all');

    $id_brosur    = decrypt($param);
    $data['status'] = 0;
    $this->md_visilab->updateAlat(['id' => $id_brosur], $data);

    //add log
    $temp = $this->md_visilab->getById($id_brosur);
    $aksi = 'Master Data Visilab';
    $ket = 'Menghapus Data Alat - ' . $temp[0]->nama;
    addlog($aksi, $ket);

    ajaxReturnDie('success', 'Alat berhasil dihapus', 'reload_table');
  }

  public function updateAlat($param = "")
  {
    grantAccessFor('all');

    $id = decrypt($this->input->post('id_brosur'));
    $data['nama'] = $this->input->post('nama');
    $data['serial_number'] = $this->input->post('serial_number');
    $data['tgl_kalibrasi']       = $this->input->post('tgl_kalibrasi');
    $data['tgl_masa_kalibrasi']       = $this->input->post('tgl_masa_kalibrasi');
    $data['tempat_kalibrasi']       = $this->input->post('tempat_kalibrasi');
    //checkEmptyForm($data);
    $this->md_visilab->updateAlat(['id' => $id], $data);


    //add log
    $temp = $this->md_visilab->getById($id);
    $aksi = 'Master Data Visilab';
    $ket = 'Mengedit Data Alat - ' . $temp[0]->nama;
    addlog($aksi, $ket);
    ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
  }

  public function pagination_alat()
  {
    grantAccessFor('all');

    $dt    = $this->md_visilab->getAllAlat();
    $start = $this->input->post('start');
    $data  = array();
    foreach ($dt['data'] as $row) {
      $id       = encrypt($row->id);
      $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="visilab/deleteAlat"><i class="bx bx-trash"></i></button>
                </div>';


      $th = array();
      $th[] = ++$start . '.';
      $th[] = $row->nama;
      $th[] = !empty($row->serial_number) ? $row->serial_number : '-';
      $th[] = !empty($row->tgl_kalibrasi) ? $row->tgl_kalibrasi : '-';
      $th[] = !empty($row->tgl_masa_kalibrasi) ? $row->tgl_masa_kalibrasi : '-';
      $th[] = !empty($row->tempat_kalibrasi) ? $row->tempat_kalibrasi : '-';
      $th[] = $li_btn;
      $data[] = $th;
    }
    $dt['data'] = $data;
    echo json_encode($dt);
    die;
  }



  // ==============================================
  // ======== END Data Alat Visilab ===============
  // ==============================================



  // ==============================================
  // ===================== SUHU ===================
  // ==============================================

  public function addSuhu()
  {
    grantAccessFor('all');

    $data['suhu']         = $this->input->post('suhu');
    $data['kelembapan']   = $this->input->post('kelembapan');
    $data['keterangan']   = $this->input->post('keterangan', TRUE) ?: 'Baik';
    $data['id_pengguna']  = sessPenggunaId();
    $data['status']       = 1;

    $tanggal = $this->input->post('tanggal');
    if (!empty($tanggal)) {
      $data['created_at'] = date('Y-m-d H:i:s', strtotime($tanggal . ' ' . date('H:i:s')));
    }

    checkEmptyForm($data);

    $this->md_visilab->addSuhu($data);


    //add log
    $aksi = 'Master Data Visilab';
    $ket = 'Menambahkan Data Suhu Tanggal : ' . (!empty($tanggal) ? $tanggal : date('d-m-Y | H:i:s'));
    addlog($aksi, $ket);

    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
  }

  public function editSuhu($param1)
  {
    grantAccessFor('all');

    $id = decrypt($param1);
    $dt = $this->md_visilab->getSuhuById($id);
    foreach ($dt as $row) {
      $row->id = encrypt($row->id);
    }
    echo json_encode($dt);
    die;
  }

  public function deleteSuhu($param)
  {
    grantAccessFor('all');

    $id_brosur    = decrypt($param);
    $data['status'] = 0;
    $this->md_visilab->updateSuhu(['id' => $id_brosur], $data);

    //add log
    $aksi = 'Master Data Visilab';
    $ket = 'Menghapus Data Suhu Tanggal : ' . date('d-m-Y | H:i:s');
    addlog($aksi, $ket);

    ajaxReturnDie('success', 'Suhu berhasil dihapus', 'reload_table');
  }

  public function updateSuhu($param = "")
  {
    grantAccessFor('all');

    $id_raw = $this->input->post('id_brosur');
    $tanggal = $this->input->post('tanggal');

    if (empty($id_raw)) {
      // Jika id kosong, lakukan penambahan data baru untuk tanggal yang dipilih
      $data['suhu']         = $this->input->post('suhu');
      $data['kelembapan']   = $this->input->post('kelembapan');
      $data['keterangan']   = $this->input->post('keterangan', TRUE) ?: 'Baik';
      $data['id_pengguna']  = sessPenggunaId();
      $data['status']       = 1;
      if (!empty($tanggal)) {
        $data['created_at'] = date('Y-m-d H:i:s', strtotime($tanggal . ' ' . date('H:i:s')));
      }

      checkEmptyForm($data);
      $this->md_visilab->addSuhu($data);

      $aksi = 'Master Data Visilab';
      $ket = 'Menambahkan Data Suhu Tanggal : ' . (!empty($tanggal) ? $tanggal : date('d-m-Y | H:i:s'));
      addlog($aksi, $ket);
      ajaxReturnDie('success', 'Data berhasil ditambahkan', 'reload_table');
    }

    $id = decrypt($id_raw);
    $data['suhu']         = $this->input->post('suhu');
    $data['kelembapan']   = $this->input->post('kelembapan');
    $data['keterangan']   = $this->input->post('keterangan', TRUE) ?: 'Baik';
    $data['id_pengguna']  = sessPenggunaId();
    $this->md_visilab->updateSuhu(['id' => $id], $data);


    //add log
    $aksi = 'Master Data Visilab';
    $ket = 'Mengedit Data Suhu Tanggal : ' . (!empty($tanggal) ? $tanggal : date('d-m-Y | H:i:s'));
    addlog($aksi, $ket);
    ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
  }

  private function hariIndo($tanggal)
  {
    $hariInggris = date('l', strtotime($tanggal));
    $hariIndo = [
      'Sunday' => 'Minggu',
      'Monday' => 'Senin',
      'Tuesday' => 'Selasa',
      'Wednesday' => 'Rabu',
      'Thursday' => 'Kamis',
      'Friday' => 'Jumat',
      'Saturday' => 'Sabtu'
    ];
    return $hariIndo[$hariInggris] . ', ' . date('d-m-Y', strtotime($tanggal));
  }


  public function pagination_suhu1()
  {
    grantAccessFor('all');

    $dt    = $this->md_visilab->getAllSuhu();
    $start = $this->input->post('start');
    $data  = array();
    foreach ($dt['data'] as $row) {
      $id       = encrypt($row->id);
      $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="visilab/deleteSuhu"><i class="bx bx-trash"></i></button>
                </div>';


      $th = array();
      $th[] = ++$start . '.';
      $th[] = $this->hariIndo($row->created_at);
      $th[] = date('H:i:s', strtotime($row->created_at)) . ' WIB';
      $th[] = $row->suhu . ' &deg;C';
      $th[] = $row->kelembapan . ' %';
      $th[] = $row->keterangan;
      $th[] = $row->nama;
      $th[] = $li_btn;
      $data[] = $th;
    }
    $dt['data'] = $data;
    echo json_encode($dt);
    die;
  }

  public function pagination_suhu()
  {
    grantAccessFor('all');

    $dt = $this->md_visilab->getAllSuhu();
    $start = $this->input->post('start') ?? 0;
    $data = array();

    // Simpan data hasil dari database ke array berdasarkan tanggal
    $suhu_data_by_date = [];
    foreach ($dt['data'] as $row) {
      $tanggal = date('Y-m-d', strtotime($row->created_at));
      $suhu_data_by_date[$tanggal] = $row;
    }

    // Ambil bulan yang difilter
    $filter_month = $this->input->post('filter_month') ?: date('Y-m');
    $year_month = explode('-', $filter_month);
    $year = $year_month[0];
    $month = $year_month[1];

    // Cek apakah bulan yang difilter lebih besar dari bulan sekarang
    $filter_timestamp = strtotime($filter_month . '-01');
    $now_timestamp = strtotime(date('Y-m-01'));

    if ($filter_timestamp > $now_timestamp) {
      // Bulan yang dipilih belum berjalan, return kosong
      $dt['data'] = [];
      echo json_encode($dt);
      die;
    }

    // Hitung jumlah hari dalam bulan tersebut
    $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    // Cek apakah bulan yang difilter adalah bulan sekarang
    $current_year = date('Y');
    $current_month = date('m');
    $current_day = date('d');

    if ($year == $current_year && $month == $current_month) {
      // Kalau bulan sekarang, hanya tampil sampai hari ini
      $days_in_month = $current_day;
    }

    for ($day = 1; $day <= $days_in_month; $day++) {
      $tanggal = sprintf('%04d-%02d-%02d', $year, $month, $day);

      $th = array();
      $th[] = ++$start . '.';

      if (isset($suhu_data_by_date[$tanggal])) {
        $row = $suhu_data_by_date[$tanggal];
        $hariIndoText = $this->hariIndo($row->created_at);
        $th[] = $hariIndoText;
        $th[] = date('H:i:s', strtotime($row->created_at)) . ' WIB';
        $th[] = $row->suhu . ' &deg;C';
        $th[] = $row->kelembapan . ' %';
        $th[] = $row->keterangan;
        $th[] = $row->nama;

        $id = encrypt($row->id);
        $li_btn = '
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '" data-tanggal="' . $tanggal . '" data-tanggal-display="' . htmlspecialchars($hariIndoText, ENT_QUOTES) . '" title="Edit Data"><i class="bx bx-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="visilab/deleteSuhu"><i class="bx bx-trash"></i></button>
                    </div>';
        $th[] = $li_btn;
      } else {
        // Data tidak ada di tanggal ini = Libur / Belum diisi
        $hariIndoText = $this->hariIndo($tanggal);
        $th[] = $hariIndoText;
        $th[] = '-';
        $th[] = '-';
        $th[] = '-';
        $th[] = 'Libur';
        $th[] = '-';

        $li_btn = '
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="" data-tanggal="' . $tanggal . '" data-tanggal-display="' . htmlspecialchars($hariIndoText, ENT_QUOTES) . '" title="Isi / Edit Data"><i class="bx bx-pencil"></i></button>
                    </div>';
        $th[] = $li_btn;
      }

      $data[] = $th;
    }

    $dt['data'] = $data;
    echo json_encode($dt);
    die;
  }












  // ==============================================
  // ===================== END SUHU ===============
  // ==============================================



  // ======================================================
  // ===================== Berita Acara ===================
  // ======================================================
  public function pagination_ba()
  {
    grantAccessFor('all');

    $dt     = $this->md_visilab->getAllBA();

    $start = $this->input->post('start');
    $data  = array();
    foreach ($dt['data'] as $row) {

      $kode_ba  = '<a href="visilab/show/detail/berita_acara/' . $row->idGc . '">' . $row->kode_ba . '</a>';

      $status_label = 'Baru Diajukan';
      $badge_class = 'success';

      // Inisialisasi
      for ($i = 1; $i <= 7; $i++) {
        $ttd_val = isset($row->{'ttd_' . $i}) ? $row->{'ttd_' . $i} : 0;

        if ($ttd_val == 1) {
          $status_label = 'Disetujui oleh ' . ($row->{'nama' . $i} ?? '-');
          $badge_class = 'info';
        } elseif ($ttd_val == 2) {
          $status_label = 'Ditolak oleh ' . ($row->{'nama' . $i} ?? '-');
          $badge_class = 'danger';
          break; // Jika sudah ditolak, langsung keluar loop
        }
      }

      // Output final
      $stat_surat = '<span class="badge badge-ecommerce badge-' . $badge_class . '">' . $status_label . '</span>';

      $tgl_aju = date('d-M-Y', strtotime($row->tanggal));

      $th = array();
      $th[] = ++$start;
      $th[] = $kode_ba;
      $th[] = $row->pengaju;
      $th[] = $tgl_aju;
      $th[] = (isset($row->id_ttd1) && $row->id_ttd1 > 0 && !empty($row->nama1)) ? $row->nama1 : '-';
      $th[] = (isset($row->id_ttd2) && $row->id_ttd2 > 0 && !empty($row->nama2)) ? $row->nama2 : '-';
      $th[] = (isset($row->id_ttd3) && $row->id_ttd3 > 0 && !empty($row->nama3)) ? $row->nama3 : '-';
      $th[] = (isset($row->id_ttd4) && $row->id_ttd4 > 0 && !empty($row->nama4)) ? $row->nama4 : '-';
      $th[] = (isset($row->id_ttd5) && $row->id_ttd5 > 0 && !empty($row->nama5)) ? $row->nama5 : '-';
      $th[] = $stat_surat;
      $data[] = $th;
    }
    $dt['data'] = $data;
    echo json_encode($dt);
    die;
  }
  // ======================================================
  // ===================== Berita Acara ===================
  // ======================================================



  //======================================================
  //============= Approval PO ============================
  //======================================================
  public function submitCatatanPo($id)
  {
    grantAccessFor('all');

    $catatan     = $this->input->post('catatan', TRUE);

    $data = [
      'catatan' => nl2br($catatan),
    ];

    $this->md_visilab->updatePo($id, $data);

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

    $this->md_visilab->updatePo($id, $data);

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

    $this->md_visilab->updatePo($id, $data);

    // Debugging
    addLog('Update PO', 'Submit catatan PO ' . $catatan_gm);
    ajaxReturnDie('success', 'catatan berhasil ditambahkan', TRUE);
  }



  //======================================================
  //============= Approval PO ============================
  //======================================================



}
