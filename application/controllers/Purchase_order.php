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

class Purchase_order extends CI_Controller
{

  function id_navbar()
  {
    $id_navbar = "inventory";
    return $id_navbar;
  }

  public function index()
  {
    grantAccessFor('all');

    $page_data['switch']        = $this->id_navbar();
    $page_data['page_name']     = 'surat/v_purchase_order';
    $page_data['page_title']    = 'Data Purchase Order';
    $page_data['page_desc']     = 'Management Purchase Order';
    $this->load->view('index', $page_data);
  }


  function __construct()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
    $this->load->model('md_kategori_tiket');
    $this->load->model('md_tiket');
    $this->load->model('md_fpp');
    $this->load->model('md_purchase_order');
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
      if ($param2 == 'purchase_order') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_po']        = $this->md_purchase_order->getPOById($param3);
        $page_data['data_detail']    = $this->md_purchase_order->getDetailPOById($param3);

        $gc  = $this->md_purchase_order->getPOById($param3);
        if ($gc[0]->id_po > 102) {
          $page_data['page_name']     = 'purchase_order/v_detail_po';
        } else if ($gc[0]->id_po > 18 && $gc[0]->id_po <= 102) {
          $page_data['page_name']     = 'purchase_order/v_detail_po_2';
        } else {
          $page_data['page_name']     = 'purchase_order/v_detail_po_1';
        }
        $page_data['page_title']    = 'Purchase Order';
        $page_data['page_desc']     = 'Detail Purchase Order';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'purchase_order1') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['data_po']        = $this->md_purchase_order->getPOById($param3);
        $page_data['data_detail']    = $this->md_purchase_order->getDetailPOById($param3);

        $gc  = $this->md_purchase_order->getPOById($param3);
        if ($gc[0]->id_po > 18) {
          $page_data['page_name']     = 'purchase_order/v_detail_po1';
        } else {
          $page_data['page_name']     = 'purchase_order/v_detail_po_1';
        }
        $page_data['page_title']    = 'Purchase Order';
        $page_data['page_desc']     = 'Detail Purchase Order';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'permintaan') {
      if ($param2 == 'purchase_order') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['list_supplier'] = $this->md_purchase_order->getSupplier();
        $page_data['page_name']     = 'purchase_order/v_purchase_order';
        $page_data['page_title']    = 'Purchase Order';
        $page_data['page_desc']     = 'Daftar Pengajuan Purchase Order';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'purchase_order1') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['list_supplier'] = $this->md_purchase_order->getSupplier();
        $page_data['page_name']     = 'purchase_order/v_purchase_order1';
        $page_data['page_title']    = 'Purchase Order';
        $page_data['page_desc']     = 'Daftar Pengajuan Purchase Order';
        $this->load->view('index', $page_data);
      }
    } else if ($param == 'pengajuan') {
      if ($param2 == 'purchase_order') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['list_supplier'] = $this->md_purchase_order->getSupplier();
        $page_data['page_name']     = 'purchase_order/v_aju_po';
        $page_data['page_title']    = 'Purchase Order';
        $page_data['page_desc']     = 'Form Pengajuan Purchase Order';
        $this->load->view('index', $page_data);
      } else if ($param2 == 'purchase_order1') {
        $page_data['switch']        = $this->id_navbar();
        $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
        $page_data['list_supplier'] = $this->md_purchase_order->getSupplier();
        $page_data['page_name']     = 'purchase_order/v_aju_po1';
        $page_data['page_title']    = 'Purchase Order';
        $page_data['page_desc']     = 'Form Pengajuan Purchase Order';
        $this->load->view('index', $page_data);
      }
    }
  }


  //ADD
  public function add()
  {
    grantAccessFor('all');

    //menambah pengajuan PO
    $this->md_purchase_order->reset_increment("purchase_order");
    $idFpp = $this->md_purchase_order->getKodeId();
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

    $kodeFpp = $kodeFpp . "/PPS/GDG/VYM/" . $bulan . "/" . $tahun;
    $data['kode_po']      = $kodeFpp;

    $data['id_pengaju']   = sessPenggunaId();
    $data['bulan_1']      = $this->input->post('bulan_1', TRUE);
    $data['bulan_2']      = $this->input->post('bulan_2', TRUE);
    $data['bulan_3']      = $this->input->post('bulan_3', TRUE);
    $data['lampiran']      = $this->input->post('lampiran', TRUE);
    $data['kota_pengajuan']  = $this->input->post('kota_aju', TRUE);
    //$data['tanggal']	      = date_db_format($this->input->post('tanggal', TRUE));
    $data['tgl_pengajuan']  = date_db_format($this->input->post('pengajuan', TRUE));
    $data['status']         = 0;
    $this->md_purchase_order->addPO($data);



    $lastGcId = $this->md_purchase_order->getLastId();
    $lastGcId = $lastGcId->id;

    //menambah detail po
    $this->md_purchase_order->reset_increment("purchase_order_detail");
    $itung = $this->input->post('itung', TRUE);
    $dataDetailGc['id_po']      = $lastGcId;
    if ($itung > 0) {
      for ($x = 1; $x < $itung; $x++) {
        $dataDetailGc['nama_barang']    = $this->input->post('nama[' . $x . ']', TRUE);
        $dataDetailGc['kode_barang']    = $this->input->post('kodebarang[' . $x . ']', TRUE);
        $dataDetailGc['isibulan_1']      = $this->input->post('bulan1[' . $x . ']', TRUE);
        $dataDetailGc['isibulan_2']      = $this->input->post('bulan2[' . $x . ']', TRUE);
        $dataDetailGc['isibulan_3']      = $this->input->post('bulan3[' . $x . ']', TRUE);
        $dataDetailGc['stok_gudang']     = $this->input->post('gudang[' . $x . ']', TRUE);
        $dataDetailGc['stok_po']         = $this->input->post('stokpo[' . $x . ']', TRUE);
        $dataDetailGc['kebutuhan_po']   = $this->input->post('kebutuhan[' . $x . ']', TRUE);
        $dataDetailGc['rencana_po']     = $this->input->post('rencana[' . $x . ']', TRUE);
        $dataDetailGc['supplier']       = $this->input->post('supplier[' . $x . ']', TRUE);
        //$dataDetailGc['keterangan']   	= $this->input->post('keterangan['.$x.']', TRUE);
        $dataDetailGc['detail']         = $this->input->post('detail[' . $x . ']', TRUE);
        $this->md_purchase_order->addPOdetail($dataDetailGc);
      }
    }




    //send notif wa po
    $dataWa = [
      'idPenerima1'   => 33,
      //'idPenerima1' 	=> 737,
      'idPenerima2'   => '',
      'namaSurat'     => 'Permintaan PO Supplier',
      'penerima'       => '_General Manager_',
      'suplier'       => $dataDetailGc['supplier'],
      'kode'           => $kodeFpp
    ];

    $this->notifWaAddSurat(2, $dataWa);


    //send notif Group wa Gudang
    $dataWa = [
      'idPenerima1'   => 'Gudang PT. VYM',
      //'idPenerima1' 	=> 'Test Api Wa Group',
      'idPenerima2'   => '',
      'idPenerima3'   => '',
      'namaSurat'      => 'Permintaan PO Supplier',
      'penerima'       => '_Warehouse Team_',
      'suplier'       => $dataDetailGc['supplier'],
      'kode'           => $kodeFpp
    ];
    $this->notifWaPoGroup(1, $dataWa);



    /** LOG */
    addLog('Pengajuan Purchase Order', 'Permintaan Purchase Order');
    ajaxReturnDie('success', 'Purchase Order Berhasil Diajukan', TRUE);
  }

  public function add1()
  {
    grantAccessFor('all');

    //menambah pengajuan PO
    $this->md_purchase_order->reset_increment("purchase_order");
    $idFpp = $this->md_purchase_order->getKodeId();
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

    $kodeFpp = $kodeFpp . "/PPS/GDG/VYM/" . $bulan . "/" . $tahun;
    $data['kode_po']      = $kodeFpp;

    $data['id_pengaju']   = sessPenggunaId();
    $data['bulan_1']      = $this->input->post('bulan_1', TRUE);
    $data['bulan_2']      = $this->input->post('bulan_2', TRUE);
    $data['bulan_3']      = $this->input->post('bulan_3', TRUE);
    $data['lampiran']      = $this->input->post('lampiran', TRUE);
    $data['kota_pengajuan']  = $this->input->post('kota_aju', TRUE);
    //$data['tanggal']	      = date_db_format($this->input->post('tanggal', TRUE));
    $data['tgl_pengajuan']  = date_db_format($this->input->post('pengajuan', TRUE));
    $data['status']         = 0;
    $this->md_purchase_order->addPO($data);



    $lastGcId = $this->md_purchase_order->getLastId();
    $lastGcId = $lastGcId->id;

    //menambah detail po
    $this->md_purchase_order->reset_increment("purchase_order_detail");
    $itung = $this->input->post('itung', TRUE);
    $dataDetailGc['id_po']      = $lastGcId;
    if ($itung > 0) {
      for ($x = 1; $x < $itung; $x++) {
        $dataDetailGc['nama_barang']    = $this->input->post('nama[' . $x . ']', TRUE);
        $dataDetailGc['kode_barang']    = $this->input->post('kodebarang[' . $x . ']', TRUE);
        $dataDetailGc['isibulan_1']      = $this->input->post('bulan1[' . $x . ']', TRUE);
        $dataDetailGc['isibulan_2']      = $this->input->post('bulan2[' . $x . ']', TRUE);
        $dataDetailGc['isibulan_3']      = $this->input->post('bulan3[' . $x . ']', TRUE);
        $dataDetailGc['stok_gudang']     = $this->input->post('gudang[' . $x . ']', TRUE);
        $dataDetailGc['stok_po']         = $this->input->post('stokpo[' . $x . ']', TRUE);
        $dataDetailGc['kebutuhan_po']   = $this->input->post('kebutuhan[' . $x . ']', TRUE);
        $dataDetailGc['rencana_po']     = $this->input->post('rencana[' . $x . ']', TRUE);
        $dataDetailGc['supplier']       = $this->input->post('supplier[' . $x . ']', TRUE);
        //$dataDetailGc['keterangan']   	= $this->input->post('keterangan['.$x.']', TRUE);
        $dataDetailGc['detail']         = $this->input->post('detail[' . $x . ']', TRUE);
        $this->md_purchase_order->addPOdetail($dataDetailGc);
      }
    }




    //send notif wa po
    $dataWa = [
      'idPenerima1'   => 769,
      //'idPenerima1' 	=> 737,
      'idPenerima2'   => '',
      'namaSurat'     => 'Permintaan PO Supplier',
      'penerima'       => '_Penanggung Jawab Teknis_',
      'suplier'       => $dataDetailGc['supplier'],
      'kode'           => $kodeFpp
    ];

    $this->notifWaAddSurat(2, $dataWa);


    //send notif Group wa Gudang
    $dataWa = [
      'idPenerima1'   => 'Gudang PT. VYM',
      //'idPenerima1' 	=> 'Test Api Wa Group',
      'idPenerima2'   => '',
      'idPenerima3'   => '',
      'namaSurat'      => 'Permintaan PO Supplier',
      'penerima'       => '_Warehouse Team_',
      'suplier'       => $dataDetailGc['supplier'],
      'kode'           => $kodeFpp
    ];
    $this->notifWaPoGroup(1, $dataWa);



    /** LOG */
    addLog('Pengajuan Purchase Order', 'Permintaan Purchase Order');
    ajaxReturnDie('success', 'Purchase Order Berhasil Diajukan', TRUE);
  }


  //UPDATE

  public function ttd_setujui($param1 = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');

    if ($param1 == "po") {
      if ($param2 == "ttd_gm") {

        $id_sp = $this->input->post('id');
        $data['status'] = 10;
        $data['ttd_gm'] = 1;
        $this->md_purchase_order->updatePO($id_sp, $data);

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '769',
          'idPenerima2'   => '',
          'namaSurat'     => 'Permintaan PO Supplier',
          'penerima'       => '_Penanggung Jawab Teknis_',
          'proses'         => 'periksa detail',
          'ttd_sebelum1'   => 'General Manager',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 1, $dataWa);

        addLog('Pengajuan Purchase Order Disetujui oleh GM', 'Permintaan Purchase Order Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_1") {

        $id_sp = $this->input->post('id');
        $data['status'] = 1;
        $data['ttd_1'] = 1;
        $this->md_purchase_order->updatePO($id_sp, $data);

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '107',
          'idPenerima2'   => '',
          'namaSurat'     => 'Permintaan PO Supplier',
          'penerima'       => '_Head Accounting and Finance_',
          'proses'         => 'periksa detail',
          'ttd_sebelum1'   => 'Penanggung Jawab Teknis',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => ''
        ];

        $this->notifWaAprovPb(1, 1, $dataWa);

        addLog('Pengajuan Purchase Order Disetujui oleh PJT', 'Permintaan Purchase Order Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 2;
        $data['ttd_2'] = 1;
        $this->md_purchase_order->updatePO($id_sp, $data);

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '23',
          'idPenerima2'   => '',
          'namaSurat'     => 'Permintaan PO Supplier',
          'penerima'       => '_Director of Corporate Planning and Business Management_',
          'proses'         => 'periksa detail',
          'ttd_sebelum1'   => 'General Manager',
          //'ttd_sebelum2' 	=> 'Penanggung Jawab Teknis',
          'ttd_sebelum2'   => '',
          'ttd_sebelum3'   => 'Head Accounting and Finance',
          'ttd_sebelum4'   => ''
        ];

        $this->notifWaAprovPb(1, 1, $dataWa);


        addLog('Pengajuan Purchase Order oleh Finance ', 'Permintaan Purchase Order Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_3") {


        $id_sp = $this->input->post('id');
        $data['status'] = 3;
        $data['ttd_3'] = 1;
        $this->md_purchase_order->updatePO($id_sp, $data);

        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '54',
          'idPenerima2'   => '',
          'namaSurat'     => 'Permintaan PO Supplier',
          'penerima'       => '_Director_',
          'proses'         => 'periksa detail',
          //'ttd_sebelum1' 	=> 'Penanggung Jawab Teknis',
          'ttd_sebelum1'   => 'General Manager',
          'ttd_sebelum2'   => 'Head Accounting and Finance',
          'ttd_sebelum3'   => 'Director of Corporate Planning and Business Management'
        ];

        $this->notifWaAprovPb(1, 1, $dataWa);

        /** LOG */
        addLog('Pengajuan Purchase Order oleh Corporate ', 'Permintaan Purchase Order Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_4") {


        $id_sp = $this->input->post('id');
        $data['status'] = 4;
        $data['ttd_4'] = 1;
        $this->md_purchase_order->updatePO($id_sp, $data);



        //send notif wa
        $dataWa = [
          'id'             => $id_sp,
          'idPenerima1'   => '764',
          'idPenerima2'   => '',
          'namaSurat'     => 'Permintaan PO Supplier',
          'penerima'       => 'Staff Accounting',
          'proses'         => 'Masukkan No dan Link PO pada',
          'ttd_sebelum1'   => 'General Manager',
          'ttd_sebelum2'   => 'Head Accounting and Finance',
          'ttd_sebelum3'   => 'Director of Corporate Planning and Business Management',
          'ttd_sebelum4'   => 'Director'
        ];

        $this->notifWaAprovPb(1, 1, $dataWa);



        /** LOG */
        addLog('Pengajuan Purchase Order Disetujui oleh Direktor ', 'Permintaan Purchase Order Disetujui');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }
    }
  }

  public function updateFinance($id_sp)
  {
    grantAccessFor('all');

    $no_po = $this->input->post('no_po');
    $link_sph = $this->input->post('lampiran_finance');
    $status_new = "7";

    $data = [
      'status' => nl2br($status_new),
      'no_po' => nl2br($no_po),
      'lampiran_finance' => nl2br($link_sph),
    ];


    $this->md_purchase_order->updatePO($id_sp, $data);

    //send notif wa
    $dataWa = [
      'id'             => $id_sp,
      'idPenerima1'   => '',
      'idPenerima2'   => '',
      'namaSurat'     => 'Permintaan PO Supplier',
      'penerima'       => '',
      'ttd_sebelum1'   => 'General Manager',
      'ttd_sebelum2'   => 'Head Accounting and Finance',
      'ttd_sebelum3'   => 'Director of Corporate Planning and Business Management',
      'ttd_sebelum4'   => 'Director'
    ];

    $this->notifWaAprovPb(1, 2, $dataWa);

    //send notif Group wa
    $dataGroup = [
      'id'             => $id_sp,
      'idPenerima1'   => 'Gudang PT. VYM',
      //'idPenerima1' 	=> 'Test Api Wa Group',
      'idPenerima2'   => '',
      'namaSurat'     => 'Permintaan PO Supplier',
      'penerima'       => '',
      'noPo'          => $no_po
    ];

    $this->notifWaAprovGroup(1, 1, $dataGroup);

    /** LOG */
    addLog('Pengajuan Purchase Order oleh Finance ', 'Permintaan Purchase Order Disetujui');
    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
  }


  public function updateAdmwhs($id_sp)
  {
    grantAccessFor('all');

    $status_new = $this->input->post('status_new');
    $lampiran_adm = $this->input->post('lampiran_adm');
    $ket_adm = $this->input->post('ket_adm');

    $data = [
      'status' => nl2br($status_new),
      'ket_adm' => nl2br($ket_adm),
      'lampiran_adm' => nl2br($lampiran_adm),
    ];


    $this->md_purchase_order->updatePO($id_sp, $data);

    //send notif wa
    /* $dataWa = [
                        'id' 	          => $id_sp,
                        'idPenerima1' 	=> '',
                        'idPenerima2' 	=> '',
                        'namaSurat' 	  => 'Permintaan PO Supplier',
                        'penerima' 	    => '',
                        'ttd_sebelum1' 	=> 'General Manager',
                        'ttd_sebelum2' 	=> 'Senior Accounting and Finance',
                        'ttd_sebelum3' 	=> 'Director of Corporate Planning and Business Management',
                        'ttd_sebelum4' 	=> 'Director'
                    ];
                
                    $this->notifWaAprovPb(1, 2, $dataWa); */

    if ($status_new == 8) {
      $stat = "Barang Diterima Seluruh";
    } else if ($status_new == 9) {
      $stat = "Barang Diterima Sebagian";
    }

    //send notif Group wa
    $dataGroup = [
      'id'             => $id_sp,
      'idPenerima1'   => 'Gudang PT. VYM',
      //'idPenerima1' 	=> 'Test Api Wa Group',
      'idPenerima2'   => '',
      'namaSurat'     => 'PO Supplier',
      'penerima'       => '',
      'noPo'          => $stat
    ];

    $this->notifWaAprovGroup(1, 3, $dataGroup);

    /** LOG */
    addLog('Pengajuan Purchase Order oleh Adm Warehouse ', 'Permintaan Purchase Order Disetujui');
    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
  }


  public function ttd_tolak($param1 = "", $param2 = "", $param3 = "")
  {
    grantAccessFor('all');

    if ($param1 == "po") {
      if ($param2 == "ttd_gm") {


        $id_sp = $this->input->post('id');
        $data['status'] = 6;
        $data['ttd_gm'] = 2;
        $this->md_purchase_order->updatePO($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Permintaan PO Supplier',
          'id'       => $id_sp,
          'idPenolak'     => 33,
          'namaPenolak'   => '*General Manager*'
        ];
        $this->notifWaRejectPb($dataWa);
        /** LOG */
        addLog('Pengajuan Purchase Order Ditolak oleh GM', 'Permintaan Purchase Order Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } else if ($param2 == "ttd_1") {


        $id_sp = $this->input->post('id');
        $data['status'] = 6;
        $data['ttd_1'] = 2;
        $this->md_purchase_order->updatePO($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Permintaan PO Supplier',
          'id'       => $id_sp,
          'idPenolak'     => 769,
          'namaPenolak'   => '*Penanggung Jawab Teknis*'
        ];
        $this->notifWaRejectPb($dataWa);
        /** LOG */
        addLog('Pengajuan Purchase Order Ditolak oleh PJT', 'Permintaan Purchase Order Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_2") {

        $id_sp = $this->input->post('id');
        $data['status'] = 6;
        $data['ttd_2'] = 2;
        $this->md_purchase_order->updatePO($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Permintaan PO Supplier',
          'id'       => $id_sp,
          'idPenolak'     => 107,
          'namaPenolak'   => '*Head Accounting and Finance*'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Pengajuan Purchase Order Ditolak oleh Finance', 'Permintaan Purchase Order Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_3") {

        $id_sp = $this->input->post('id');
        $data['status'] = 6;
        $data['ttd_3'] = 2;
        $this->md_purchase_order->updatePO($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Permintaan PO Supplier',
          'id'       => $id_sp,
          'idPenolak'     => 23,
          'namaPenolak'   => '*Director of Corporate Planning and Business Management*'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Pengajuan Purchase Order Ditolak oleh Director Corporate', 'Permintaan Purchase Order Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      } elseif ($param2 == "ttd_4") {

        $id_sp = $this->input->post('id');
        $data['status'] = 6;
        $data['ttd_4'] = 2;
        $this->md_purchase_order->updatePO($id_sp, $data);

        //send notif wa
        $dataWa = [
          'namaSurat'     => 'Permintaan PO Supplier',
          'id'       => $id_sp,
          'idPenolak'     => 54,
          'namaPenolak'   => '*Director*'
        ];
        $this->notifWaRejectPb($dataWa);

        /** LOG */
        addLog('Pengajuan Purchase Order Ditolak oleh Director', 'Permintaan Purchase Order Ditolak');
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


    $this->md_purchase_order->updatePO($id, $data);

    addLog('Menghapus purchase order', 'Menghapus purchase order  ');
    ajaxReturnDie('success', 'purchase order berhasil dihapus', 'reload_table');
  }


  public function edit($param1)
  {
    grantAccessFor('all');
    $id = decrypt($param1);
    $dt = $this->md_purchase_order->getDetailPOByIdEdit($id);

    if (isset($dt[0]))
      echo json_encode($dt[0]);
    else
      ajaxReturnDie('error', 'error');
    die;
  }



  public function update()
  {

    grantAccessFor('all');

    $id =  $this->input->post('id');
    if (sessPenggunaId() == '23') {
      $data['nama_barang'] = $this->input->post('namabarang');
      $data['keterangan2']  = $this->input->post('keterangan2');
    } else {

      $data['nama_barang'] = $this->input->post('namabarang');
      $data['kode_barang']  = $this->input->post('kodebarang');
      $data['isibulan_1']  = $this->input->post('isibulan_1');
      $data['isibulan_2']  = $this->input->post('isibulan_2');
      $data['isibulan_3']  = $this->input->post('isibulan_3');
      $data['stok_gudang']  = $this->input->post('stok_gudang');
      $data['stok_po']  = $this->input->post('stok_po');
      $data['kebutuhan_po']  = $this->input->post('kebutuhan_po');
      $data['rencana_po']  = $this->input->post('rencana_po');
      $data['supplier']  = $this->input->post('supplier');
      $data['detail']  = $this->input->post('detail');
    }
    $this->md_purchase_order->updateKeterangan($id, $data);

    /** LOG */
    addLog('Update PO', 'Memperbarui data PO "' . $data['nama_barang'] . '"');
    ajaxReturnDie('success', 'Data PO ' . $data['nama_barang'] . ' berhasil diperbarui', 'reload_table');
  }





  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  // pagination -------------------------------------------------------------------------------------------------------------------------------------------------------
  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------

  public function pagination($param = "", $param2 = "")
  {
    grantAccessFor('all');
    if ($param == 'permintaan_po') {

      //if(sessPenggunaId()==1 || sessPenggunaId()==107 || sessPenggunaId()==722 || sessPenggunaId()==23 || sessPenggunaId()==33 || sessPenggunaId()==15 || sessPenggunaId()==7 || sessPenggunaId()==54){
      $dt     = $this->md_purchase_order->getAllPo();
      //}else{
      //  $dt     = $this->md_purchase_order->getAllPObyID(sessPenggunaId());
      // }
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_po  = '<a href="purchase_order/show/detail/purchase_order/' . $row->id_po . '">' . $row->kode_po . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';



        if ($row->id_po < 101) {
          if ($row->status == "0") {
            $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
          } else if ($row->status == "1") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Penanggung Jawab Teknis</span>';
          } else if ($row->status == "2") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Head Accounting and Finance</span>';
          } else if ($row->status == "3") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director of Corporate Planning & Business Management</span>';
          } else if ($row->status == "4") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director</span>';
          } else {
            $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
          }
        } else {
          if ($row->status == "0") {
            $stat_surat = '<span class="badge badge-ecommerce badge-secondary">Baru Diajukan</span>';
          } else if ($row->status == "10") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
          } else if ($row->status == "1") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Penanggung Jawab Teknis</span>';
          } else if ($row->status == "2") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Head Accounting and Finance</span>';
          } else if ($row->status == "3") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director of Corporate Planning & Business Management</span>';
          } else if ($row->status == "4") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director</span>';
          } else if ($row->status == "6") {
            $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';;
          } else if ($row->status == "7") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">PO Supplier Sudah Terbit</span>';
          } else if ($row->status == "8") {
            $stat_surat = '<span class="badge badge-ecommerce badge-success">Barang Diterima Seluruh</span>';
          } else if ($row->status == "9") {
            $stat_surat = '<span class="badge badge-ecommerce badge-success">Barang Diterima Sebagian</span>';
          }
        }



        /* $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    ' . ($row->status== '100' ? '<a href="surat/show/edit/penawaran/' . $row->id_po . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                    ' . ($row->status== '0' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->id_po . '" data-object="purchase_order/delete"><i class="bx bx-trash"></i></button>' : '') . '
                </div>'; */

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_po;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = date('d-M-Y', strtotime($row->tgl_Pengajuan));
        $th[] = $row->supplier;
        $th[] = $row->no_po;
        $th[] = $stat_surat;
        //$th[] = $li_btn;
        $data[] = $th;
      }
      $dt['data'] = $data;
      echo json_encode($dt);
      die;
    } else if ($param == 'permintaan_po1') {

      //if(sessPenggunaId()==1 || sessPenggunaId()==107 || sessPenggunaId()==722 || sessPenggunaId()==23 || sessPenggunaId()==33 || sessPenggunaId()==15 || sessPenggunaId()==7 || sessPenggunaId()==54){
      $dt     = $this->md_purchase_order->getAllPo();
      //}else{
      //  $dt     = $this->md_purchase_order->getAllPObyID(sessPenggunaId());
      // }
      $start = $this->input->post('start');
      $data  = array();
      foreach ($dt['data'] as $row) {


        $kode_po  = '<a href="purchase_order/show/detail/purchase_order1/' . $row->id_po . '">' . $row->kode_po . '</a>';
        //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';



        if ($row->id_po < 101) {
          if ($row->status == "0") {
            $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
          } else if ($row->status == "1") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Penanggung Jawab Teknis</span>';
          } else if ($row->status == "2") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Head Accounting and Finance</span>';
          } else if ($row->status == "3") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director of Corporate Planning & Business Management</span>';
          } else if ($row->status == "4") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director</span>';
          } else {
            $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
          }
        } else {
          if ($row->status == "0") {
            $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
          } else if ($row->status == "10") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh General Manager</span>';
          } else if ($row->status == "1") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Penanggung Jawab Teknis</span>';
          } else if ($row->status == "2") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Head Accounting and Finance</span>';
          } else if ($row->status == "3") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director of Corporate Planning & Business Management</span>';
          } else if ($row->status == "4") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director</span>';
          } else if ($row->status == "6") {
            $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';;
          } else if ($row->status == "7") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">PO Supplier Sudah Terbit</span>';
          } else if ($row->status == "8") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Barang Diterima Seluruh</span>';
          } else if ($row->status == "9") {
            $stat_surat = '<span class="badge badge-ecommerce badge-info">Barang Diterima Sebagian</span>';
          }
        }



        /* $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    ' . ($row->status== '100' ? '<a href="surat/show/edit/penawaran/' . $row->id_po . '" class="btn btn-sm btn-primary btn-edit"><i class="bx bx-pencil"></i></a>' : '') . ' &nbsp;
                    ' . ($row->status== '0' ? '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->id_po . '" data-object="purchase_order/delete"><i class="bx bx-trash"></i></button>' : '') . '
                </div>'; */

        $th = array();
        $th[] = ++$start;
        $th[] = $kode_po;
        $th[] = $row->pengaju;
        $th[] = $row->jabatan;
        $th[] = date('d-M-Y', strtotime($row->tgl_Pengajuan));
        $th[] = $row->supplier;
        $th[] = $row->no_po;
        $th[] = $stat_surat;
        //$th[] = $li_btn;
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
        'kodePO'       => $detail['kode'],
        'namaPengaju' => $namaPengaju,
        'suplier'     => $detail['suplier'],
        'namaPenerima'   => $detail['penerima']
      ];
      waPoOpen($dataWa);
    }
  }


  public function notifWaPoGroup($ulang, $detail)
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
        'namaPengaju'   => $namaPengaju,
        'suplier'       => urlencode($detail['suplier']),
        'kodePO'         => $detail['kode'],
        'namaPenerima'   => urlencode($detail['penerima'])
      ];
      waPermintaanPoGroup($dataWa);
    }
  }



  public function notifWaAprovPb($ulang, $param, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_purchase_order->getNotifPoId($detail['id']);
    $suplier            = $ambilDataPengaju[0]->supplier;
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode                = $ambilDataPengaju[0]->kode_po;
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
      $nmPengaju      = $dataPenerima[0]->nama;
      $dataWa = [
        'namaSurat'     => $detail['namaSurat'],
        'noPenerima'    => $nope,
        'kodePO'         => $kode,
        'namaPengaju'   => $namaPengaju,
        'suplier'       => urlencode($suplier),
        'namaPenerima'   => urlencode($detail['penerima']),
        'proses'         => $detail['proses'],
        'ttd_sebelum1'   => $detail['ttd_sebelum1'],
        'ttd_sebelum2'   => $detail['ttd_sebelum2'],
        'ttd_sebelum3'   => $detail['ttd_sebelum3'],
        'ttd_sebelum4'   => $detail['ttd_sebelum4']
      ];

      if ($param == '1') {
        waPoAprovOnProg($dataWa);
      } else if ($param == '2') {
        waPoAprovAll($dataWa);
      } else if ($param == '3') {
        waPoAprovAllGroup($dataWa);
      }
    }
  }

  public function notifWaAprovGroup($ulang, $param, $detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_purchase_order->getNotifPoId($detail['id']);
    $suplier            = $ambilDataPengaju[0]->supplier;
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode                = $ambilDataPengaju[0]->kode_po;
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

      $dataPenerima   = $this->md_pengguna->getById($idpengaju);
      $nope           = $dataPenerima[0]->no_hp;
      $nmPengaju      = $dataPenerima[0]->nama;
      $dataWa = [
        'namaSurat'     => $detail['namaSurat'],
        'noPenerima'    => $idpenerima,
        'kodePO'         => $kode,
        'namaPengaju'   => urlencode($namaPengaju),
        'suplier'       => urlencode($suplier),
        'namaPenerima'   => urlencode($detail['penerima']),
        'noPo'           => $detail['noPo']
      ];

      if ($param == '1') {
        waPoAprovAllGroup($dataWa);
      } else if ($param == '3') {
        waPoAprovAllGroupAdm($dataWa);
      } else if ($param == '2') {
        waPoAprovAll($dataWa);
      }
    }
  }





  public function notifWaRejectPb($detail)
  {
    //ambil data pengaju
    $ambilDataPengaju   = $this->md_purchase_order->getNotifPoId($detail['id']);
    $suplier            = $ambilDataPengaju[0]->supplier;
    $namaPengaju        = $ambilDataPengaju[0]->pengaju;
    $kode                = $ambilDataPengaju[0]->kode_po;
    $idpengaju          = $ambilDataPengaju[0]->idPengaju;

    //if($idpengaju == 1){
    //   $idpengaju == 63;
    //}

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
      'namaPengaju'   => $namaPengaju,
      'perihal'     => $perihal,
      'namaPenolak'   => $detail['namaPenolak'],
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

    if ($param1 == 'purchase_order') {

      $gc              = $this->md_purchase_order->getPOById($param2);

      $dt = [
        'title_pdf'  => 'po',
        'object'  => $param1,
        'data_po'  => $this->md_purchase_order->getPOById($param2),
        'data_detail'  => $this->md_purchase_order->getDetailPOById($param2)
      ];

      //load mpdf dan membuat page size 
      $mpdf = new Mpdf(['format' => 'A4']);

      // $mpdf->SetMargins(0, 0, 0, true);

      //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
      $mpdf->AddPage('L', '', '', '', '', '5', '5', '4', '1');

      // filename dari pdf ketika didownload
      $file_pdf = 'Rencana Purchase Order (PO)  ' . $gc[0]->pengaju;

      // page htmk yang akan di jadikan ke pdf
      if ($gc[0]->id_po > 102) {
        $html = $this->load->view('pages/v_print/print_po', $dt, true);
      } else if ($gc[0]->id_po > 18 && $gc[0]->id_po <= 102) {
        $html = $this->load->view('pages/v_print/print_po_2', $dt, true);
      } else {
        $html = $this->load->view('pages/v_print/print_po_1', $dt, true);
      }
      $mpdf->WriteHTML($html);
      $mpdf->Output($file_pdf . '.pdf', 'I');
    }
  }
}
