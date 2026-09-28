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

class Pinjam extends CI_Controller
{

  function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    


    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pinjam');
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
        if($param == 'detail'){  
         
            $page_data['switch']      	= $this->id_navbar();
            $page_data['data_aprv']	  	= $this->md_pinjam->getPinjamById(decrypt($param2));
            $page_data['detail_aprv']	  = $this->md_pinjam->getDetailPinjam(decrypt($param2));
            $page_data['data_status']   = $this->md_pinjam->getUpdateById(['t.id_pinjam' => decrypt($param2)]);
            $page_data['page_name']     = 'pinjam/v_detail_pinjam';            
            $page_data['page_title']    = 'Peminjaman Unit';
            $page_data['page_desc']     = 'Detail Permintaan Peminjaman Unit Demo / Pinjam Pakai / Sewa';
            $this->load->view('index', $page_data);
                  
			  }else if($param == 'list'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['page_name']     = 'pinjam/v_pinjam';
            $page_data['page_title']    = 'Peminjaman Unit';
            $page_data['page_desc']     = 'Daftar Pengajuan Peminjaman Unit Demo / Pinjam Pakai / Sewa';
            $this->load->view('index', $page_data);
          
			  }else if($param == 'pengajuan'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
            $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
            $page_data['page_name']     = 'pinjam/v_aju_pinjam';
            $page_data['page_title']    = 'Peminjaman Unit';
            $page_data['page_desc']     = 'Form Permintaan Peminjaman Unit Demo / Pinjam Pakai / Sewa';
            $this->load->view('index', $page_data);
          
			  }
          


    }

    
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
// pagination -------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  
    public function pagination($param = "", $param2 = "")
    {
        grantAccessFor('all');
        
        $dt     = $this->md_pinjam->getAllPinjam();
         $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {
              
              $id       = encrypt($row->id);
              
              $kode	= '<a href="pinjam/show/detail/'.$id.'">'.$row->kode.'</a>';

              if($row->status== "0"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Baru Diajukan</span>';
              }else if($row->status == "1"){
                  $stat_surat = '<span class="badge badge-ecommerce badge-success">Disetujui Oleh General Affair</span>';
              }else if($row->status == "2"){
                  $stat_surat = '<span class="badge badge-ecommerce badge-success">Disetujui Oleh General Manager</span>';
              }else if($row->status == "3"){
                  $stat_surat = '<span class="badge badge-ecommerce badge-success">Disetujui Oleh Head Of Warehouse</span>';
              }else if($row->status == "4"){
                  $stat_surat = '<span class="badge badge-ecommerce badge-success">Barang Diterima Peminjam</span>';
              }else if($row->status == "5"){
                  $stat_surat = '<span class="badge badge-ecommerce badge-success">Barang Dikembalikan Kegudang</span>';
              }else if($row->status == "6"){
                  $stat_surat = '<span class="badge badge-ecommerce badge-success">Barang Dikirim</span>';
              }else{
                 $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
              }

              if($row->jenis== "1"){
                  $jenis = 'Marketing';
              }else if($row->jenis == "2"){
                  $jenis = 'Umum';
              }

              $th = array();
              $th[] = ++$start;
              $th[] = $kode;
              $th[] = $row->nama_peminjam;
              $th[] = $row->jabatan_peminjam;
              $th[] = $jenis;
              $th[] = '<i class="fa fa-clock-o"></i> '.date('d-M-Y',strtotime($row->created_at));
              $th[] = $stat_surat;
              $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;						
    
      
  
  
  }



    //ADD
    public function add($param = "")
    {
          grantAccessFor('all');

          
              //menambah pengajuan Approval Expedisi
              $this->md_pinjam->reset_increment("pinjam");
              $idFpp      = $this->md_pinjam->getPinjamKodeId();
              $ambilId    = $idFpp->id;
              $ambilId    = ($ambilId+1);
              $panjangId  = strlen($ambilId);
              
              if ($panjangId == 1){
                $kodeFpp = "00".$ambilId;
              } else if ($panjangId == 2){
                $kodeFpp = "0".$ambilId;
              } else{
                $kodeFpp = $ambilId;
              }
              
              $bulan = ambil_bulan();
              $tahun = ambil_tahun();
              
              $kodeFpp = $kodeFpp."/PU/WHS/VYM/".$bulan."/".$tahun;
              $data['kode']			     = $kodeFpp;
              
              $data['id_peminjam']   = sessPenggunaId();
              $data['keperluan']     = $this->input->post('keperluan', TRUE);
              $data['link']          = $this->input->post('lampiran', TRUE);
              $data['kota_aju']	     = $this->input->post('kota_aju', TRUE) ?: 'Pekanbaru';


              $pengguna_id = sessPenggunaId(); 
              $pengguna = $this->md_pinjam->getDivisi($pengguna_id);

              if ($pengguna && $pengguna->id_divisi == 3) {
                  $data['idttd_1']	   = '33';
                  $data['jenis']	     = '1';
                  $idpenerima1         = '33';
              } else {
                  $data['idttd_1']	   = '58';
                  $data['jenis']	     = '2';
                  $idpenerima1         = '58';
              }

              $data['idttd_2']	     = '749';


              $this->md_pinjam->addPinjam($data);


              //menambah data ke tabel detail
              $lastGcId = $this->md_pinjam->getPinjamLastId();
              $lastGcId = $lastGcId->id;
              
              //menambah detail Appeks
              $this->md_pinjam->reset_increment("pinjam_detail");
              $itung = $this->input->post('itung', TRUE);
              $dataDetailGc['id_pinjam']			= $lastGcId;
			        //$dataDetailGc['approval']	    = 1;
              if($itung > 0){
                for($x=1;$x<$itung;$x++){
                  $dataDetailGc['nama']	= $this->input->post('namabarang['.$x.']', TRUE);
                  $dataDetailGc['jumlah']	        = $this->input->post('jumlah['.$x.']', TRUE);
                  $dataDetailGc['keterangan']	    = $this->input->post('keterangan['.$x.']', TRUE);
                  $this->md_pinjam->addPinjamDetail($dataDetailGc);
                }
              }


              //Add Status
              $dataProses['id_pinjam']	  = $lastGcId;
              $dataProses['id_pengguna']	= $pengguna_id;
              $dataProses['status']			  = '0'; //Baru Diajukan
              $this->md_pinjam->addPinjamStatus($dataProses);
              
              //Notif Whatsapp
              $id_sp = encrypt($lastGcId);
              $urlNotif = "https://office.visiyosindo.id/pinjam/show/detail/$id_sp";  
                 

                $dataWa = [
                            'idPenerima1' 	=> $idpenerima1,
                            'idPenerima2' 	=> '',
                            'namaSurat' 	  => 'Peminjaman Unit',
                            'urlNotif' 	    => $urlNotif,
                            'perihal' 	    => $data['keperluan'],
                            'kode' 	        => $kodeFpp
                          ];
                        
                    $this->notifWaAddSuratLink(1, $dataWa);

              /** LOG */
              addLog('Peminjaman Unit', 'Pengajuan dengan Kode '.$kodeFpp);
              ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
            

          


      }

      public function notifWaAddSuratLink($ulang, $detail){
          //ambil data pengaju
          $ambilDataPengaju 	= $this->md_pengguna->getById(sessPenggunaId());
          $namaPengaju		= $ambilDataPengaju[0]->nama;

          for($i=1; $i<=$ulang; $i++){
              if($i == 1){
                  $idpenerima = $detail['idPenerima1'];
              }else if($i == 2){
                  $idpenerima = $detail['idPenerima2'];
              }
                          

          //abaikan error
          error_reporting(E_ALL & ~E_NOTICE);
          ini_set('display_errors', 0);

              $dataPenerima 	= $this->md_pengguna->getById($idpenerima);
              $nope           = $dataPenerima[0]->no_hp;
              $penerima       = $dataPenerima[0]->short_name;

              $dataWa = [
                  'namaSurat' 	=> urlencode($detail['namaSurat']),
                  'urlNotif' 	    => urlencode($detail['urlNotif']),
                  'noPenerima' 	=> $nope,
                  'kodeSurat' 	=> $detail['kode'],
                  'namaPengaju'   => $namaPengaju,
                  'perihal' 		=> urlencode($detail['perihal']),
                  'namaPenerima' 	=> urlencode($penerima)
                  ];
                  waSuratOpenLink($dataWa);
          }
      }


      function updateSnPinjam()
      {
          grantAccessFor('all');

          //update detail Stok Opname
          $id_sodetail = $this->input->post('id_sodetail');
          foreach ($id_sodetail as $key => $row) {
              $data['serial_number'] = $this->input->post('serialnumber')[$key];

              
              $this->md_pinjam->updatePinjamDetail(decrypt($row), $data);
              

          }
          //add log
          $aksi = 'Peminjaman Unit';
          $ket = 'Update SN';
          addlog($aksi, $ket);

          ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      }

      

    //UPDATE

    public function ttd_setujui($param1="", $param2="", $param3=""){
    	grantAccessFor('all');
        
        if($param1 == "pinjam"){
          if($param2 == "ttd_1"){
                    
				        $id_sp = $this->input->post('id');
				        $data['ttd_1'] = 1;
                $this->md_pinjam->updatePinjam($id_sp, $data);

                
                $dataStatus['status'] = 1;
                $dataStatus['id_pinjam'] = $id_sp;
                $dataStatus['id_pengguna'] = sessPenggunaId();
                $this->md_pinjam->addPinjamStatus($dataStatus);
                

              $ambilDataPengaju 	= $this->md_pinjam->getPinjamById($id_sp);
              $namaPengaju		    = $ambilDataPengaju->nama_peminjam;
              $kode               = $ambilDataPengaju->kode;
              $perihal            = $ambilDataPengaju->keperluan;
              $idpengaju          = $ambilDataPengaju->id_peminjam;

                //Notif ke Wa Pengaju
                $dataWa = [
                  'id' 	          => $id_sp,
                  'idPenerima1' 	=> $idpengaju,
                  'idPenerima2' 	=> '',
                  'namaSurat' 	  => 'Peminjaman Unit',
                  'namaPengaju' 	=> $namaPengaju,
                  'penerima' 	    => 'Sehan Ohisabaref',
                  'kode' 	        => $kode,
                  'perihal' 	    => $perihal,
                  'idpengaju' 	  => $idpengaju,
                  'ttd_sebelum1' 	=> 'General Affair',
                  'ttd_sebelum2' 	=> '',
                  'ttd_sebelum3' 	=> ''
                  ];
              
              $this->notifWaAprovPb(1, 1, $dataWa);
            
            /** LOG */
            addLog('Peminjaman Unit', 'Disetujui'.$kode);
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

        }else if($param2 == "ttd_2"){
                    
				        $id_sp = $this->input->post('id');
				        $data['ttd_1'] = 1;
                $this->md_pinjam->updatePinjam($id_sp, $data);

                
                $dataStatus['status'] = 2;
                $dataStatus['id_pinjam'] = $id_sp;
                $dataStatus['id_pengguna'] = sessPenggunaId();
                $this->md_pinjam->addPinjamStatus($dataStatus);
                

              $ambilDataPengaju 	= $this->md_pinjam->getPinjamById($id_sp);
              $namaPengaju		    = $ambilDataPengaju->nama_peminjam;
              $kode               = $ambilDataPengaju->kode;
              $perihal            = $ambilDataPengaju->keperluan;
              $idpengaju          = $ambilDataPengaju->id_peminjam;

                //Notif ke Wa Pengaju
                $dataWa = [
                  'id' 	          => $id_sp,
                  'idPenerima1' 	=> $idpengaju,
                  'idPenerima2' 	=> '',
                  'namaSurat' 	  => 'Peminjaman Unit',
                  'namaPengaju' 	=> $namaPengaju,
                  'penerima' 	    => 'Sehan Ohisabaref',
                  'kode' 	        => $kode,
                  'perihal' 	    => $perihal,
                  'idpengaju' 	  => $idpengaju,
                  'ttd_sebelum1' 	=> 'General Manager',
                  'ttd_sebelum2' 	=> '',
                  'ttd_sebelum3' 	=> ''
                  ];
              
              $this->notifWaAprovPb(1, 1, $dataWa);
            
            /** LOG */
            addLog('Peminjaman Unit', 'Disetujui'.$kode);
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

        }else if($param2 == "ttd_3"){
                    
				        $id_sp = $this->input->post('id');
				        $data['ttd_2'] = 1;
                $this->md_pinjam->updatePinjam($id_sp, $data);

                
                $dataStatus['status'] = 3;
                $dataStatus['id_pinjam'] = $id_sp;
                $dataStatus['id_pengguna'] = sessPenggunaId();
                $this->md_pinjam->addPinjamStatus($dataStatus);
                

              $ambilDataPengaju 	= $this->md_pinjam->getPinjamById($id_sp);
              $namaPengaju		    = $ambilDataPengaju->nama_peminjam;
              $kode               = $ambilDataPengaju->kode;
              $perihal            = $ambilDataPengaju->keperluan;
              $idpengaju          = $ambilDataPengaju->id_peminjam;

                //Notif ke Wa Pengaju
                $dataWa = [
                  'id' 	          => $id_sp,
                  'idPenerima1' 	=> '',
                  'idPenerima2' 	=> '',
                  'namaSurat' 	  => 'Peminjaman Unit',
                  'namaPengaju' 	=> $namaPengaju,
                  'penerima' 	    => 'Sehan Ohisabaref',
                  'kode' 	        => $kode,
                  'perihal' 	    => $perihal,
                  'idpengaju' 	  => $idpengaju,
                  'ttd_sebelum1' 	=> 'General Affair',
                  'ttd_sebelum2' 	=> 'Head of Warehouse',
                  'ttd_sebelum3' 	=> ''
                  ];
              
              $this->notifWaAprovPb(1, 2, $dataWa);
            
            /** LOG */
            addLog('Peminjaman Unit', 'Disetujui'.$kode);
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
        }else if($param2 == "ttd_4"){
                    
				        $id_sp = $this->input->post('id');
				        $data['ttd_2'] = 1;
                $this->md_pinjam->updatePinjam($id_sp, $data);

                
                $dataStatus['status'] = 3;
                $dataStatus['id_pinjam'] = $id_sp;
                $dataStatus['id_pengguna'] = sessPenggunaId();
                $this->md_pinjam->addPinjamStatus($dataStatus);
                

              $ambilDataPengaju 	= $this->md_pinjam->getPinjamById($id_sp);
              $namaPengaju		    = $ambilDataPengaju->nama_peminjam;
              $kode               = $ambilDataPengaju->kode;
              $perihal            = $ambilDataPengaju->keperluan;
              $idpengaju          = $ambilDataPengaju->id_peminjam;

                //Notif ke Wa Pengaju
                $dataWa = [
                  'id' 	          => $id_sp,
                  'idPenerima1' 	=> '',
                  'idPenerima2' 	=> '',
                  'namaSurat' 	  => 'Peminjaman Unit',
                  'namaPengaju' 	=> $namaPengaju,
                  'penerima' 	    => 'Sehan Ohisabaref',
                  'kode' 	        => $kode,
                  'perihal' 	    => $perihal,
                  'idpengaju' 	  => $idpengaju,
                  'ttd_sebelum1' 	=> 'General Manager',
                  'ttd_sebelum2' 	=> 'Head of Warehouse',
                  'ttd_sebelum3' 	=> ''
                  ];
              
              $this->notifWaAprovPb(1, 2, $dataWa);
            
            /** LOG */
            addLog('Peminjaman Unit', 'Disetujui'.$kode);
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
        }

      }

    }


    public function ttd_tolak($param1="", $param2="", $param3=""){
    	grantAccessFor('all');
        
          if($param1 == "pinjam"){
              if($param2 == "ttd_1"){                        
                        
                        $id_sp = $this->input->post('id');
                        $data['ttd_1'] = 2;
                        $this->md_pinjam->updatePinjam($id_sp, $data);

                        $dataStatus['status'] = 7;
                        $dataStatus['id_pinjam'] = $id_sp;
                        $dataStatus['id_pengguna'] = sessPenggunaId();
                        $this->md_pinjam->addPinjamStatus($dataStatus);
                

                                              
                        $ambilDataPengaju 	= $this->md_pinjam->getPinjamById($id_sp);
                        $namaPengaju		    = $ambilDataPengaju->nama_peminjam;
                        $kode               = $ambilDataPengaju->kode;
                        $perihal            = $ambilDataPengaju->keperluan;
                        $idpengaju          = $ambilDataPengaju->id_peminjam;

                    //send notif wa
                      $dataWa = [
                          'namaSurat'     => 'Peminjaman Unit',
                                'id' 	    => $id_sp,
                          'idPenolak'     => 58,
                          'namaPengaju'   => $namaPengaju,
                                  'kode'  => $kode,
                              'perihal'   => $perihal,
                            'idpengaju'   => $idpengaju,
                          'namaPenolak' 	=> '*General Affair*'
                            ];
                            $this->notifWaRejectPb($dataWa);
                  /** LOG */
                  addLog('Peminjaman Unit', 'Ditolak');
                  ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

                }else if($param2 == "ttd_2"){

                         $id_sp = $this->input->post('id');
                        $data['ttd_1'] = 2;
                        $this->md_pinjam->updatePinjam($id_sp, $data);

                        $dataStatus['status'] = 7;
                        $dataStatus['id_pinjam'] = $id_sp;
                        $dataStatus['id_pengguna'] = sessPenggunaId();
                        $this->md_pinjam->addPinjamStatus($dataStatus);
                

                                              
                        $ambilDataPengaju 	= $this->md_pinjam->getPinjamById($id_sp);
                        $namaPengaju		    = $ambilDataPengaju->nama_peminjam;
                        $kode               = $ambilDataPengaju->kode;
                        $perihal            = $ambilDataPengaju->keperluan;
                        $idpengaju          = $ambilDataPengaju->id_peminjam;

                    //send notif wa
                      $dataWa = [
                          'namaSurat'     => 'Peminjaman Unit',
                                'id' 	    => $id_sp,
                          'idPenolak'     => 33,
                          'namaPengaju'   => $namaPengaju,
                                  'kode'  => $kode,
                              'perihal'   => $perihal,
                            'idpengaju'   => $idpengaju,
                          'namaPenolak' 	=> '*General Manager*'
                            ];
                            $this->notifWaRejectPb($dataWa);
                  /** LOG */
                  addLog('Peminjaman Unit', 'Ditolak');
                  ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

                }else if($param2 == "ttd_3"){

                         $id_sp = $this->input->post('id');
                        $data['ttd_2'] = 2;
                        $this->md_pinjam->updatePinjam($id_sp, $data);

                        $dataStatus['status'] = 7;
                        $dataStatus['id_pinjam'] = $id_sp;
                        $dataStatus['id_pengguna'] = sessPenggunaId();
                        $this->md_pinjam->addPinjamStatus($dataStatus);
                

                                              
                        $ambilDataPengaju 	= $this->md_pinjam->getPinjamById($id_sp);
                        $namaPengaju		    = $ambilDataPengaju->nama_peminjam;
                        $kode               = $ambilDataPengaju->kode;
                        $perihal            = $ambilDataPengaju->keperluan;
                        $idpengaju          = $ambilDataPengaju->id_peminjam;

                    //send notif wa
                      $dataWa = [
                          'namaSurat'     => 'Peminjaman Unit',
                                'id' 	    => $id_sp,
                          'idPenolak'     => 749,
                          'namaPengaju'   => $namaPengaju,
                                  'kode'  => $kode,
                              'perihal'   => $perihal,
                            'idpengaju'   => $idpengaju,
                          'namaPenolak' 	=> '*Head of Warehouse*'
                            ];
                            $this->notifWaRejectPb($dataWa);
                  /** LOG */
                  addLog('Peminjaman Unit', 'Ditolak');
                  ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

                }else if($param2 == "ttd_4"){

                         $id_sp = $this->input->post('id');
                        $data['ttd_2'] = 2;
                        $this->md_pinjam->updatePinjam($id_sp, $data);

                        $dataStatus['status'] = 7;
                        $dataStatus['id_pinjam'] = $id_sp;
                        $dataStatus['id_pengguna'] = sessPenggunaId();
                        $this->md_pinjam->addPinjamStatus($dataStatus);
                

                                              
                        $ambilDataPengaju 	= $this->md_pinjam->getPinjamById($id_sp);
                        $namaPengaju		    = $ambilDataPengaju->nama_peminjam;
                        $kode               = $ambilDataPengaju->kode;
                        $perihal            = $ambilDataPengaju->keperluan;
                        $idpengaju          = $ambilDataPengaju->id_peminjam;

                    //send notif wa
                      $dataWa = [
                          'namaSurat'     => 'Peminjaman Unit',
                                'id' 	    => $id_sp,
                          'idPenolak'     => 749,
                          'namaPengaju'   => $namaPengaju,
                                  'kode'  => $kode,
                              'perihal'   => $perihal,
                            'idpengaju'   => $idpengaju,
                          'namaPenolak' 	=> '*Head of Warehouse*'
                            ];
                            $this->notifWaRejectPb($dataWa);
                  /** LOG */
                  addLog('Peminjaman Unit', 'Ditolak');
                  ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

                }

            }


    }


    





//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Notif Wa -------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------    

    public function notifWaAprovPb($ulang, $param, $detail){
      
     
      
      //send notif wa
      for($i=1; $i<=$ulang; $i++){
          if($i == 1){
              //id pengaju surat
              if($param == 2){
                  $idpenerima = $detail['idpengaju'];
              }else{
                  $idpenerima = $detail['idPenerima1'];
              }
          }else if($i == 2){
              //id ???
              $idpenerima = $detail['idPenerima2'];
          }
            
          $dataPenerima 	= $this->md_pengguna->getById($idpenerima);
          $nope           = $dataPenerima[0]->no_hp;
          $dataWa = [
              'namaSurat' 	  => $detail['namaSurat'],
              'noPenerima'  	=> $nope,
              'kodeSurat' 	  => $detail['kode'],
              'namaPengaju' 	=> urlencode($detail['namaPengaju']),
              'namaPenerima' 	=> urlencode($detail['penerima']),
              'perihal' 		  => $detail['perihal'],
                		'link' 		=> $detail['id'],
              'ttd_sebelum1' 	=> urlencode($detail['ttd_sebelum1']),
              'ttd_sebelum2' 	=> $detail['ttd_sebelum2'],
              'ttd_sebelum3' 	=> $detail['ttd_sebelum3'],
              'ttd_sebelum4' 	=> ''
          ];
          
          if($param == '1'){
            waSuratAprovOnProg($dataWa);
          }else if($param == '2'){
              waSuratAprovAll($dataWa);
          }else if($param == '3'){
              waSuratAprovAllSkors($dataWa);
          }
      }
    }



    public function notifWaRejectPb($detail){
      
      //ambil nomor
      $dataPenerima 	= $this->md_pengguna->getById($detail['idpengaju']);
      $nope           = $dataPenerima[0]->no_hp;
      $dataPenolak 	  = $this->md_pengguna->getById($detail['idPenolak']);
      $nopePenolak    = $dataPenolak[0]->no_hp;
                  
      //send notif wa
      $dataWa = [
          'namaSurat' 	=> $detail['namaSurat'],
          'noPenerima' 	=> $nope,
          'kodeSurat' 	=> $detail['kode'],
          'namaPengaju' => $detail['namaPengaju'],
          'perihal' 		=> $detail['perihal'],
          'namaPenolak' => urlencode($detail['namaPenolak']),
          'noPenolak' 	=> $nopePenolak
      ];
      waSuratReject($dataWa);
    }


    


    //==================================================
    //================== Notif WA       ================
    //==================================================

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_pinjam->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    public function updateStatusPinjam()
    {        
        grantAccessFor('all');

        //ADD Status
        $id        = decrypt($this->input->post('id_kirim'));
        $status    = $this->input->post('status', TRUE);
            if ($status == "4" || $status == "5") {
                $dataDetailGc['id_pinjam']        = $id ;
                $dataDetailGc['status']           = $status;
                $dataDetailGc['nama_penerima']    = $this->input->post('nama_penerima', TRUE);
                $dataDetailGc['tgl_penerima']     = date_db_format($this->input->post('tgl_penerima', TRUE));
                $dataDetailGc['bukti_penerima']   = $this->input->post('bukti_penerima', TRUE);
                $dataDetailGc['id_pengguna']      = sessPenggunaId();
            } else {
                $dataDetailGc['id_pinjam']        = $id ;
                $dataDetailGc['status']           = $status;
                $dataDetailGc['id_pengguna']      = sessPenggunaId();
                $dataDetailGc['keterangan_konfirmasi']  = $this->input->post('keterangan_konfirmasi', TRUE);
            }
         
        $this->md_pinjam->addPinjamStatus($dataDetailGc);

        

        /** LOG */
        addLog('Peminjaman Unit', 'Memperbarui data ');
        ajaxReturnDie('success', 'Data pelanggan berhasil diperbarui', TRUE);
	}

   
    
    
    
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
// print ------------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	public function print_page($param1="", $param2="")
  {
        grantAccessFor('all');

        if($param1 == 'pinjam'){

            $gc							 = $this->md_pinjam->getPinjamById($param2);
            
            $dt = [
              'title_pdf'	  => 'appeks',
              'object'	    => $param1,
              'data_aprv'	  => $this->md_pinjam->getPinjamById($param2),
              'detail_aprv'	=> $this->md_pinjam->getDetailPinjam($param2)
            ];
            
            //load mpdf dan membuat page size 
            $mpdf = new Mpdf(['format' => 'A4']);

           // $mpdf->SetMargins(0, 0, 0, true);

            //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
	          $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');
              
            // filename dari pdf ketika didownload
            $file_pdf = 'Peminjaman Unit '.$gc->nama_peminjam;
            
            // page html yang akan di jadikan ke pdf
            $html = $this->load->view('pages/v_print/print_pinjam', $dt, true);
            
            $mpdf->WriteHTML($html);
            $mpdf->Output($file_pdf . '.pdf', 'I');


        }
                  
    }


 




}