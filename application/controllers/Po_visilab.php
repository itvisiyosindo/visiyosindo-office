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

class Po_visilab extends CI_Controller
{

  function id_navbar(){
		$id_navbar = "visilab";
		return $id_navbar;
	}

    public function index()
    {
       grantAccessFor('all');

        $page_data['switch']      	= $this->id_navbar();
		    $page_data['page_name']     = 'visilab/v_purchase_order';
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
        $this->load->model('md_po_visilab');
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
        if($param == 'detail'){  
          if ($param2 == 'purchase_order'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['data_po']		    = $this->md_po_visilab->getPOById($param3);
            $page_data['data_detail']		= $this->md_po_visilab->getDetailPOById($param3);
            //New Version
            $dataUkes	  	= $this->md_po_visilab->getPOById($param3);
            if ($dataUkes[0]->id_po <= 2) {
                $page_data['page_name']     = 'visilab/v_detail_po_1';
            } else {
                $page_data['page_name']     = 'visilab/v_detail_po';
            }
            //$page_data['page_name']     = 'visilab/v_detail_po';
            $page_data['page_title']    = 'Purchase Order';
            $page_data['page_desc']     = 'Detail Purchase Order';
            $this->load->view('index', $page_data);
          }
        }else if($param == 'permintaan'){
          if($param2 == 'purchase_order'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['list_supplier'] = $this->md_po_visilab->getSupplier();
            $page_data['page_name']     = 'visilab/v_purchase_order';
            $page_data['page_title']    = 'Purchase Order';
            $page_data['page_desc']     = 'Daftar Pengajuan Purchase Order';
            $this->load->view('index', $page_data);
          }
			  }else if($param == 'pengajuan'){
          if ($param2 == 'purchase_order'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
            $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
            $page_data['page_name']     = 'visilab/v_aju_po';
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

            //menambah pengajuan PO VISILAB
            $this->md_po_visilab->reset_increment("po_visilab");
            $idFpp = $this->md_po_visilab->getKodeId();
            $ambilId = $idFpp->id;
            $ambilId = ($ambilId+1);
            $panjangId = strlen($ambilId);
            
            if ($panjangId == 1){
              $kodeFpp = "00".$ambilId;
            } else if ($panjangId == 2){
              $kodeFpp = "0".$ambilId;
            } else{
              $kodeFpp = $ambilId;
            }
            
            $bulan = ambil_bulan();
            $tahun = ambil_tahun();
            
            $kodeFpp = $kodeFpp."/PPS/VISILAB/VYM/".$bulan."/".$tahun;
            $data['kode_po']			= $kodeFpp;
            
            $data['id_pengaju']   = sessPenggunaId();
            $data['lampiran']      = $this->input->post('lampiran', TRUE);
            $data['kota_pengajuan']	= $this->input->post('kota_aju', TRUE);
            $data['tgl_pengajuan']	= date_db_format($this->input->post('pengajuan', TRUE));
            $data['status']       	= 0;
            if ($data['id_pengaju'] == 751) {
                $data['ttd_gm'] = 1;
            }
            $this->md_po_visilab->addPO($data);


           
            $lastGcId = $this->md_po_visilab->getLastId();
            $lastGcId = $lastGcId->id;
            
            //menambah detail po
            $this->md_po_visilab->reset_increment("po_visilab_detail");
            $itung = $this->input->post('itung', TRUE);
            $dataDetailGc['id_po']			= $lastGcId;
            if($itung > 0){
              for($x=1;$x<$itung;$x++){
                $dataDetailGc['nama_barang']	  = $this->input->post('nama['.$x.']', TRUE);
                $dataDetailGc['stok_gudang']   	= $this->input->post('gudang['.$x.']', TRUE);
                $dataDetailGc['stok_po']   	    = $this->input->post('stokpo['.$x.']', TRUE);
                $dataDetailGc['rencana_po']   	= $this->input->post('rencana['.$x.']', TRUE);
                $dataDetailGc['supplier']   	  = $this->input->post('supplier['.$x.']', TRUE);
                $dataDetailGc['detail']   	    = $this->input->post('detail['.$x.']', TRUE);
                $this->md_po_visilab->addPOdetail($dataDetailGc);
              }
          }

            
            

              //send notif wa po
              if ($data['id_pengaju'] == 751) {
                  $dataWa = [
                    'id'            => $lastGcId,
                    'idPenerima1' 	=> 75,
                    'idPenerima2' 	=> '',
                    'namaSurat' 	=> 'Permintaan PO Aset Visilab',
                    'penerima' 	    => '_Head of Visilab (Mrs. Mega Ratu)_',
                    'suplier' 	    => $dataDetailGc['supplier'],
                    'kode' 	        => $kodeFpp
                  ];
              } else {
                  $dataWa = [
                    'id'            => $lastGcId,
                    'idPenerima1' 	=> 751,
                    'idPenerima2' 	=> '',
                    'namaSurat' 	=> 'Permintaan PO Aset Visilab',
                    'penerima' 	    => '_Intan Kurnia_',
                    'suplier' 	    => $dataDetailGc['supplier'],
                    'kode' 	        => $kodeFpp
                  ];
              }
          
              $this->notifWaAddSurat(2, $dataWa);


              //send notif Group wa Gudang
              $dataWa = [
                'id'            => $lastGcId,
                'idPenerima1' 	=> 'VISILAB',
                //'idPenerima1' 	=> 'Test Api Wa Group',
                'idPenerima2' 	=> '',
                'idPenerima3' 	=> '',
                'namaSurat'  	  => 'Permintaan PO Aset Visilab',
                'penerima' 	    => '_Visilab Team_',
                'suplier' 	    => $dataDetailGc['supplier'],
                'kode' 	        => $kodeFpp
            ];
            $this->notifWaPoGroup(1, $dataWa);
              
                      

            /** LOG */
            addLog('VISILAB', 'Permintaan Purchase Order Kode: ' . $data['kode_po']);
            ajaxReturnDie('success', 'Purchase Order Berhasil Diajukan', TRUE);
                  


       


    }

    

    //UPDATE

    public function ttd_setujui($param1="", $param2="", $param3=""){
    	grantAccessFor('all');
        
		if($param1 == "po"){
      if($param2 == "ttd_gm"){
                
				        $id_sp = $this->input->post('id');
                $data['status'] = 10;
				        $data['ttd_gm'] = 1;
                $this->md_po_visilab->updatePO($id_sp, $data);

             //send notif wa
			        $dataWa = [
                        'id' 	          => $id_sp,
                        'idPenerima1' 	=> '75',
                        'idPenerima2' 	=> '',
                        'namaSurat' 	  => 'Permintaan PO Aset Visilab',
                        'penerima' 	    => '_Head of Visilab_',
                        'proses' 	      => 'periksa detail',
                        'ttd_sebelum1' 	=> 'Staff Teknis',
                        'ttd_sebelum2' 	=> '',
                        'ttd_sebelum3' 	=> ''
                    ];
                
                $this->notifWaAprovPb(1, 1, $dataWa);

         addLog('Visilab', 'Permintaan Purchase Order Disetujui');
         ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

       }else if($param2 == "ttd_1"){
                
				        $id_sp = $this->input->post('id');
                $data['status'] = 1;
				        $data['ttd_1'] = 1;
                $this->md_po_visilab->updatePO($id_sp, $data);

             //send notif wa
			        $dataWa = [
                        'id' 	          => $id_sp,
                        'idPenerima1' 	=> '107',
                        'idPenerima2' 	=> '',
                        'namaSurat' 	  => 'Permintaan PO Aset Visilab',
                        'penerima' 	    => '_Head of Accounting and Tax (Mr. Dirangga Madali)_',
                        'proses' 	      => 'periksa detail',
                        'ttd_sebelum1' 	=> 'Staff Teknis',
                        'ttd_sebelum2' 	=> 'Manager Puncak',
                        'ttd_sebelum3' 	=> ''
                    ];
                
                $this->notifWaAprovPb(1, 1, $dataWa);

         addLog('Visilab', 'Permintaan Purchase Order Disetujui oleh Manager Puncak');
         ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

       }elseif($param2 == "ttd_2"){
                
          $id_sp = $this->input->post('id');
          $data['status'] = 2;
          $data['ttd_2'] = 1;
          $this->md_po_visilab->updatePO($id_sp, $data);

              //send notif wa
                $dataWa = [
                      'id' 	          => $id_sp,
                      'idPenerima1' 	=> '23',
                      'idPenerima2' 	=> '',
                      'namaSurat' 	  => 'Permintaan PO Aset Visilab',
                      'penerima' 	    => '_Director of Corporate Planning and Business Management_',
                      'proses' 	      => 'periksa detail',
                      'ttd_sebelum1' 	=> 'Head of Visilab',
                      'ttd_sebelum2' 	=> 'Head of Accounting and Tax',
                      'ttd_sebelum3' 	=> '',
                      'ttd_sebelum4' 	=> '',
                      'ttd_sebelum5' 	=> ''
                      ];
                  
                  $this->notifWaAprovPb(1, 1, $dataWa);


                  addLog('Visilab', 'Permintaan Purchase Order Disetujui oleh Finance');
                  ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

          }elseif($param2 == "ttd_3"){
                
            
                  $id_sp = $this->input->post('id');
                  $data['status'] = 3;
                  $data['ttd_3'] = 1;
                  $this->md_po_visilab->updatePO($id_sp, $data);

                  //send notif wa
                    $dataWa = [
                      'id' 	          => $id_sp,
                      'idPenerima1' 	=> '54',
                      'idPenerima2' 	=> '',
                      'namaSurat' 	  => 'Permintaan PO Aset Visilab',
                      'penerima' 	    => '_Director_',
                      'proses' 	      => 'periksa detail',
                      'ttd_sebelum1' 	=> 'Head of Visilab',
                      'ttd_sebelum2' 	=> 'Head of Accounting and Tax',
                      'ttd_sebelum3' 	=> 'Director of Corporate Planning & Business Management',
                      'ttd_sebelum4' 	=> '',
                      'ttd_sebelum5' 	=> ''
                  ];
              
              $this->notifWaAprovPb(1, 1, $dataWa);
            
            /** LOG */
            addLog('Visilab', 'Permintaan Purchase Order Disetujui oleh Director Planning');
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

        }elseif($param2 == "ttd_4"){
               
          
          $id_sp = $this->input->post('id');
          $data['status'] = 4;
          $data['ttd_4'] = 1;
          $this->md_po_visilab->updatePO($id_sp, $data);

          $ambilDataPo = $this->md_po_visilab->getNotifPoId($id_sp);
          $is_pengaju_mutu = (isset($ambilDataPo[0]) && $ambilDataPo[0]->idPengaju == 751);

          //send notif wa to Staff Accounting (ID 714)
          $dataWa = [
            'id' 	          => $id_sp,
            'idPenerima1' 	=> '714',
            'idPenerima2' 	=> '',
            'namaSurat' 	=> 'Permintaan PO Aset Visilab',
            'penerima' 	    => '_Staff Accounting_',
            'proses' 	    => 'Masukkan No dan Link PO pada',
            'ttd_sebelum1' 	=> $is_pengaju_mutu ? 'Head of Visilab' : 'Manager Mutu',
            'ttd_sebelum2' 	=> $is_pengaju_mutu ? 'Head of Accounting and Tax' : 'Head of Visilab',
            'ttd_sebelum3' 	=> $is_pengaju_mutu ? 'Director of Corporate Planning & Business Management' : 'Head of Accounting and Tax',
            'ttd_sebelum4' 	=> $is_pengaju_mutu ? 'Director' : 'Director of Corporate Planning & Business Management',
            'ttd_sebelum5' 	=> $is_pengaju_mutu ? '' : 'Director'
          ];
        
          $this->notifWaAprovPb(1, 1, $dataWa);

           
          
          /** LOG */
          addLog('Visilab', 'Permintaan Purchase Order Disetujui oleh Director');
          ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

        }

      }

    }

    public function updateFinance($id_sp){
    	      grantAccessFor('all');

                  $no_po = $this->input->post('no_po');
                  $link_sph = $this->input->post('lampiran_finance');
                  $status_new = "7";

                  $data = [
                    'status' =>nl2br($status_new),
                    'no_po' =>nl2br($no_po),
                    'lampiran_finance' =>nl2br($link_sph),
                  ];


                  $this->md_po_visilab->updatePO($id_sp, $data);

                     //send notif wa
                      $dataWa = [
                        'id' 	          => $id_sp,
                        'idPenerima1' 	=> '',
                        'idPenerima2' 	=> '',
                        'namaSurat' 	  => 'Permintaan PO Aset Visilab',
                        'penerima' 	    => '',
                        'ttd_sebelum1' 	=> 'Staff Teknis',
                        'ttd_sebelum2' 	=> 'Manager Puncak',
                        'ttd_sebelum3' 	=> 'Senior Accounting and Finance',
                        'ttd_sebelum4' 	=> 'Director of Corporate Planning and Business Management',
                        'ttd_sebelum5' 	=> 'Director'
                    ];
                
                    $this->notifWaAprovPb(1, 2, $dataWa);

                    //send notif Group wa
                    $dataGroup = [
                      'id' 	          => $id_sp,
                      'idPenerima1' 	=> 'VISILAB',
                      //'idPenerima1' 	=> 'Test Api Wa Group',
                      'idPenerima2' 	=> '',
                      'namaSurat' 	  => 'Permintaan PO Aset Visilab',
                      'penerima' 	    => '',
                      'noPo'        	=> $no_po
                  ];
              
                  $this->notifWaAprovGroup(1, 1, $dataGroup);
            
            /** LOG */
            addLog('Visilab', 'Submit Purchase Order oleh Finance');
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

     }


     public function updateAdmwhs($id_sp){
    	      grantAccessFor('all');

                  $status_new = $this->input->post('status_new');
                  $lampiran_adm = $this->input->post('lampiran_adm');
                  $ket_adm = $this->input->post('ket_adm');

                  $data = [
                    'status' =>nl2br($status_new),
                    'ket_adm' =>nl2br($ket_adm),
                    'lampiran_adm' =>nl2br($lampiran_adm),
                  ];


                  $this->md_po_visilab->updatePO($id_sp, $data);

                     //send notif wa
                      $dataWa = [
                        'id' 	          => $id_sp,
                        'idPenerima1' 	=> '',
                        'idPenerima2' 	=> '',
                        'namaSurat' 	  => 'Permintaan PO Aset Visilab',
                        'penerima' 	    => '',
                        'ttd_sebelum1' 	=> 'Staff Teknis',
                        'ttd_sebelum2' 	=> 'Manager Puncak',
                        'ttd_sebelum3' 	=> 'Senior Accounting and Finance',
                        'ttd_sebelum4' 	=> 'Director of Corporate Planning and Business Management',
                        'ttd_sebelum5' 	=> 'Director'
                    ];
                
                    $this->notifWaAprovPb(1, 2, $dataWa);

                    if ($status_new==8){
                      $stat = "Barang/Jasa Diterima Seluruh";
                    }else if ($status_new==9){
                      $stat = "Barang/Jasa Diterima Sebagian";
                    }

                    //send notif Group wa
                    $dataGroup = [
                      'id' 	          => $id_sp,
                      'idPenerima1' 	=> 'VISILAB',
                      //'idPenerima1' 	=> 'Test Api Wa Group',
                      'idPenerima2' 	=> '',
                      'namaSurat' 	  => 'Permintaan PO Aset Visilab',
                      'penerima' 	    => '',
                      'noPo'        	=> $stat
                  ];
              
                  $this->notifWaAprovGroup(1, 3, $dataGroup);
            
            /** LOG */
            addLog('Visilab', 'Submit Status Purchase Order oleh Admin Visilab');
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

     }


    public function ttd_tolak($param1="", $param2="", $param3=""){
    	grantAccessFor('all');
        
          if($param1 == "po"){
            if($param2 == "ttd_gm"){
                      
                      
                      $id_sp = $this->input->post('id');
                      $data['status'] = 6;
                      $data['ttd_gm'] = 2;
                      $this->md_po_visilab->updatePO($id_sp, $data);

                  //send notif wa
                    $dataWa = [
                        'namaSurat'     => 'Permintaan PO Aset Visilab',
                              'id' 	    => $id_sp,
                        'idPenolak'     => 757,
                        'namaPenolak' 	=> '*Staff Teknis*'
                          ];
                          $this->notifWaRejectPb($dataWa);
              /** LOG */
              addLog('Visilab', 'Permintaan Purchase Order Ditolak');
              ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

              }else if($param2 == "ttd_1"){
                      
                      
                      $id_sp = $this->input->post('id');
                      $data['status'] = 6;
                      $data['ttd_1'] = 2;
                      $this->md_po_visilab->updatePO($id_sp, $data);

                  //send notif wa
                    $dataWa = [
                        'namaSurat'     => 'Permintaan PO Aset Visilab',
                              'id' 	    => $id_sp,
                        'idPenolak'     => 75,
                        'namaPenolak' 	=> '*Manager Puncak*'
                          ];
                          $this->notifWaRejectPb($dataWa);
              /** LOG */
              addLog('Visilab', 'Permintaan Purchase Order Ditolak oleh Mega Ratu');
              ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

              }elseif($param2 == "ttd_2"){

                      $id_sp = $this->input->post('id');
                      $data['status'] = 6;
                      $data['ttd_2'] = 2;
                      $this->md_po_visilab->updatePO($id_sp, $data);

                  //send notif wa
                    $dataWa = [
                        'namaSurat'     => 'Permintaan PO Aset Visilab',
                              'id' 	    => $id_sp,
                        'idPenolak'     => 107,
                        'namaPenolak' 	=> '*Senior Accounting and Finance*'
                          ];
                          $this->notifWaRejectPb($dataWa);
                  
                  /** LOG */
                  addLog('Visilab', 'Permintaan Purchase Order Ditolak oleh Finance');
                  ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

              }elseif($param2 == "ttd_3"){

                $id_sp = $this->input->post('id');
                $data['status'] = 6;
                $data['ttd_3'] = 2;
                $this->md_po_visilab->updatePO($id_sp, $data);

            //send notif wa
              $dataWa = [
                  'namaSurat'     => 'Permintaan PO Aset Visilab',
                        'id' 	    => $id_sp,
                  'idPenolak'     => 23,
                  'namaPenolak' 	=> '*Director of Corporate Planning and Business Management*'
                    ];
                    $this->notifWaRejectPb($dataWa);
            
            /** LOG */
            addLog('Visilab', 'Permintaan Purchase Order Ditolak oleh Meilina');
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

              }elseif($param2 == "ttd_4"){

                $id_sp = $this->input->post('id');
                $data['status'] = 6;
                $data['ttd_4'] = 2;
                $this->md_po_visilab->updatePO($id_sp, $data);

            //send notif wa
              $dataWa = [
                  'namaSurat'     => 'Permintaan PO Aset Visilab',
                        'id' 	    => $id_sp,
                  'idPenolak'     => 54,
                  'namaPenolak' 	=> '*Director*'
                    ];
                    $this->notifWaRejectPb($dataWa);
            
            /** LOG */
            addLog('Visilab', 'Permintaan Purchase Order Ditolak oleh Director');
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
    		'status' =>"5",
    	];


    	$this->md_po_visilab->updatePO($id, $data);

        addLog('Menghapus purchase order', 'Menghapus purchase order  ');
        ajaxReturnDie('success', 'purchase order berhasil dihapus', 'reload_table');
    }

    
    public function edit($param1)
    {      
            grantAccessFor('all');
            $id = decrypt($param1);
            $dt = $this->md_po_visilab->getDetailPOByIdEdit($id);

            if(isset($dt[0]))
                echo json_encode($dt[0]);
            else
                ajaxReturnDie('error', 'error');
             die;
  
    }



    public function update()
    {        
       
         grantAccessFor('all');
        
             $id =  $this->input->post('id');
          if (sessPenggunaId() == '23'){
             $data['nama_barang'] = $this->input->post('namabarang');
             $data['keterangan2']  = $this->input->post('keterangan2');
          }else{ 
             
             $data['nama_barang'] = $this->input->post('namabarang');
             $data['stok_gudang']  = $this->input->post('stok_gudang');
             $data['rencana_po']  = $this->input->post('rencana_po');
             $data['supplier']  = $this->input->post('supplier');
             $data['detail']  = $this->input->post('detail');
          }
        $this->md_po_visilab->updateKeterangan($id, $data);

        /** LOG */
        addLog('Visilab', 'Memperbarui data PO "' . $data['nama_barang'] . '"');
        ajaxReturnDie('success', 'Data PO '. $data['nama_barang'].' berhasil diperbarui', 'reload_table');
	}

    

    

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
// pagination -------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  
    public function pagination($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'permintaan_po') {
            
            $dt     = $this->md_po_visilab->getAllPo();

            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {
                            
              $kode_po	= '<a href="po_visilab/show/detail/purchase_order/'.$row->id_po.'">'.$row->kode_po.'</a>';
           
              if($row->status== "0"){
                $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
              }else if($row->status == "10"){
                  $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Staff Teknis </span>';
              }else if($row->status == "1"){
                  $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Manager Puncak</span>';
              }else if($row->status == "2"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Senior Accounting & Finance</span>';
              }else if($row->status == "3"){
                 $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director of Corporate Planning & Business Management</span>';
              }else if($row->status == "4"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui oleh Director</span>';
              }else if($row->status == "6"){
                $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';;
              }else if($row->status == "7"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">PO Supplier Sudah Terbit</span>';
              }else if($row->status == "8"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Barang/Jasa Diterima Seluruh</span>';
              }else if($row->status == "9"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Barang/Jasa Diterima Sebagian</span>';
              }

            
              $th = array();
              $th[] = ++$start;
              $th[] = $kode_po;
              $th[] = $row->pengaju;
              $th[] = $row->jabatan_visilab;
              $th[] = date('d-M-Y',strtotime($row->tgl_Pengajuan));
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
public function notifWaAddSurat($ulang, $detail){
        //ambil data pengaju
      $ambilDataPengaju 	= $this->md_pengguna->getById(sessPenggunaId());
      $namaPengaju		= $ambilDataPengaju[0]->nama;

      for($i=1; $i<=$ulang; $i++){
        if($i == 1){
            $idpenerima = $detail['idPenerima1'];
        }else if($i == 2){
            $idpenerima = $detail['idPenerima2'];
        }
        
        $dataPenerima 	= $this->md_pengguna->getById($idpenerima);
        //abaikan error
      error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 0);
      //
        $nope           = $dataPenerima[0]->no_hp;
        $dataWa = [
            'id'            => isset($detail['id']) ? $detail['id'] : '',
            'namaSurat' 	=> $detail['namaSurat'],
            'noPenerima' 	=> $nope,
            'kodePO' 	    => $detail['kode'],
            'namaPengaju' => $namaPengaju,
            'suplier' 		=> $detail['suplier'],
            'namaPenerima' 	=> $detail['penerima']
            ];
            waPoVisilabOpen($dataWa);
      }
}


public function notifWaPoGroup($ulang, $detail){
  //ambil data pengaju
  $ambilDataPengaju 	= $this->md_pengguna->getById(sessPenggunaId());
  $namaPengaju		    = $ambilDataPengaju[0]->nama;
  
    for($i=1; $i<=$ulang; $i++){
        if($i == 1){
            $idpenerima = $detail['idPenerima1'];
        }else if($i == 2){
            $idpenerima = $detail['idPenerima2'];
        }else if($i == 3){
            $idpenerima = $detail['idPenerima3'];
        }
        
        
        //abaikan error
      error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 0);
      //
        $dataWa = [
          'id'            => isset($detail['id']) ? $detail['id'] : '',
          'namaSurat'   	=> $detail['namaSurat'],
          'noPenerima'  	=> $idpenerima,
          'namaPengaju' 	=> $namaPengaju,
          'suplier' 	    => urlencode($detail['suplier']),
          'kodePO' 	      => $detail['kode'],
          'namaPenerima' 	=> urlencode($detail['penerima'])
            ];
            waPermintaanPoVisilabGroup($dataWa);
    }
  }



    public function notifWaAprovPb($ulang, $param, $detail){
      //ambil data pengaju
      $ambilDataPengaju 	= $this->md_po_visilab->getNotifPoId($detail['id']);
      $suplier		        = $ambilDataPengaju[0]->supplier;
      $namaPengaju		    = $ambilDataPengaju[0]->pengaju;
      $kode		            = $ambilDataPengaju[0]->kode_po;
      $idpengaju		      = $ambilDataPengaju[0]->idPengaju;
      
      
      //send notif wa
      for($i=1; $i<=$ulang; $i++){
          if($i == 1){
              //id pengaju surat
              if($param == 2){
                  $idpenerima = $idpengaju;
              }else{
                  $idpenerima = $detail['idPenerima1'];
              }
          }else if($i == 2){
              //id ???
              $idpenerima = $detail['idPenerima2'];
          }
            
          $dataPenerima 	= $this->md_pengguna->getById($idpenerima);
          $nope           = $dataPenerima[0]->no_hp;
          $nmPengaju      = $dataPenerima[0]->nama;
          $dataWa = [
              'id'            => $detail['id'],
              'namaSurat' 	  => $detail['namaSurat'],
              'noPenerima'  	=> $nope,
              'kodePO' 	      => $kode,
              'namaPengaju' 	=> $namaPengaju,
              'suplier'   	  => urlencode($suplier),
              'namaPenerima' 	=> urlencode($detail['penerima']),
              'proses' 	      => $detail['proses'],
              'ttd_sebelum1' 	=> urlencode($detail['ttd_sebelum1']),
              'ttd_sebelum2' 	=> urlencode($detail['ttd_sebelum2']),
              'ttd_sebelum3' 	=> urlencode($detail['ttd_sebelum3']),
              'ttd_sebelum4' 	=> urlencode($detail['ttd_sebelum4']),
              'ttd_sebelum5' 	=> urlencode($detail['ttd_sebelum5'])
          ];
          
          if($param == '1'){
            waPoVisilabAprovOnProg($dataWa);
          }else if($param == '2'){
            waPoVisilabAprovAll($dataWa);
          }else if($param == '3'){
            waPoVisilabAprovAllGroup($dataWa);
          }
      }
    }

    public function notifWaAprovGroup($ulang, $param, $detail){
      //ambil data pengaju
      $ambilDataPengaju 	= $this->md_po_visilab->getNotifPoId($detail['id']);
      $suplier		        = $ambilDataPengaju[0]->supplier;
      $namaPengaju		    = $ambilDataPengaju[0]->pengaju;
      $kode		            = $ambilDataPengaju[0]->kode_po;
      $idpengaju		      = $ambilDataPengaju[0]->idPengaju;
      
      
      //send notif wa
      for($i=1; $i<=$ulang; $i++){
          if($i == 1){
              //id pengaju surat
              if($param == 2){
                  $idpenerima = $idpengaju;
              }else{
                  $idpenerima = $detail['idPenerima1'];
              }
          }else if($i == 2){
              //id ???
              $idpenerima = $detail['idPenerima2'];
          }
            
          $dataPenerima 	= $this->md_pengguna->getById($idpengaju);
          $nope           = $dataPenerima[0]->no_hp;
          $nmPengaju      = $dataPenerima[0]->nama;
          $dataWa = [
              'id'            => $detail['id'],
              'namaSurat' 	  => $detail['namaSurat'],
              'noPenerima'  	=> $idpenerima,
              'kodePO' 	      => $kode,
              'namaPengaju' 	=> urlencode($namaPengaju),
              'suplier'   	  => urlencode($suplier),
              'namaPenerima' 	=> urlencode($detail['penerima']),
              'noPo' 	        => $detail['noPo']
          ];
          
          if($param == '1'){
            waPoVisilabAprovAllGroup($dataWa);
          }else if($param == '3'){
            waPoVisilabAprovAllGroupAdm($dataWa);
          }else if($param == '2'){
            waPoVisilabAprovAll($dataWa);
          }
      }
    }

    
   


    public function notifWaRejectPb($detail){
      //ambil data pengaju
      $ambilDataPengaju 	= $this->md_po_visilab->getNotifPoId($detail['id']);
      $suplier		        = $ambilDataPengaju[0]->supplier;
      $namaPengaju		    = $ambilDataPengaju[0]->pengaju;
      $kode		            = $ambilDataPengaju[0]->kode_po;
      $idpengaju		      = $ambilDataPengaju[0]->idPengaju;
      
      //if($idpengaju == 1){
      //   $idpengaju == 63;
      //}
      
      //ambil nomor
      $dataPenerima 	= $this->md_pengguna->getById($idpengaju);
      $nope           = $dataPenerima[0]->no_hp;
      $dataPenolak 	  = $this->md_pengguna->getById($detail['idPenolak']);
      $nopePenolak    = $dataPenolak[0]->no_hp;
                  
      //send notif wa
      $dataWa = [
          'id'          => $detail['id'],
          'namaSurat' 	=> $detail['namaSurat'],
          'noPenerima' 	=> $nope,
          'kodeSurat' 	=> $kode,
          'namaPengaju' 	=> $namaPengaju,
          'perihal' 		=> 'Supplier: ' . $suplier,
          'namaPenolak' 	=> $detail['namaPenolak'],
          'noPenolak' 	=> $nopePenolak,
          'link'        => 'po_visilab/show/detail/purchase_order/' . $detail['id']
      ];
      waSuratReject($dataWa);
    }


    







    //==================================================
    //================== Notif WA       ================
    //==================================================

   
    
    
    
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
// print ------------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	public function print_page($param1="", $param2="")
  {
        grantAccessFor('all');

        if($param1 == 'purchase_order'){

            $gc							= $this->md_po_visilab->getPOById($param2);
            
            $dt = [
              'title_pdf'	=> 'po',
              'object'	=> $param1,
              'data_po'	=> $this->md_po_visilab->getPOById($param2),
              'data_detail'	=> $this->md_po_visilab->getDetailPOById($param2)
            ];
            
            //load mpdf dan membuat page size 
            $mpdf = new Mpdf(['format' => 'A4']);

           // $mpdf->SetMargins(0, 0, 0, true);

            //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
	          $mpdf->AddPage('L', '', '', '', '', '5', '5', '4', '1');
              
            // filename dari pdf ketika didownload
            $file_pdf = 'Rencana Purchase Order (PO) Visilab '.$gc[0]->pengaju;

            // page htmk yang akan di jadikan ke pdf
            $dataUkes	  	= $this->md_po_visilab->getPOById($param2);
            if ($dataUkes[0]->id_po <= 2) {
                $html = $this->load->view('pages/v_print/print_po_visilab_1', $dt, true);
            } else {
                $html = $this->load->view('pages/v_print/print_po_visilab', $dt, true); 
            }
            //$html = $this->load->view('pages/v_print/print_po_visilab', $dt, true);
            
            $mpdf->WriteHTML($html);
            $mpdf->Output($file_pdf . '.pdf', 'I');


        }
              
 }


 




}