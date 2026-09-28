<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Pengujian extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pengujian');
		    $this->load->model('md_pelanggan');
        $this->load->model('md_pengguna');
        $this->load->helper('terbilang_helper');
        $this->load->helper('tanggal_helper');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('encrypt_helper');
    }
	
	function id_navbar(){
		$id_navbar = "visilab";
		return $id_navbar;
	}

   
    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor('all');
        if($param == 'permintaan'){  
          if ($param2 == 'ukes'){
            $page_data['switch']      	= $this->id_navbar();
		        $page_data['list_cust']     = $this->md_pelanggan->getByWhere(['p.status' => 1]);
            $page_data['page_name']  	= 'pengujian/v_ukes';
            $page_data['page_title']	= 'Uji Kesesuaian';
            $page_data['page_desc']  	= 'Management Data Uji Kesesuaian';
            $this->load->view('index', $page_data);
          }else if ($param2 == 'upar'){
            $page_data['switch']      	= $this->id_navbar();
		        $page_data['list_cust']     = $this->md_pelanggan->getByWhere(['p.status' => 1]);
			      $page_data['page_name']     = 'pengujian/v_upar';
            $page_data['page_title']    = 'Uji Paparan';
            $page_data['page_desc']     = 'Management Data Uji Paparan';
            $this->load->view('index', $page_data);
          }
        }else if($param == 'detail'){  
          if ($param2 == 'ukes'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['data_ukes']	  	= $this->md_pengujian->getBywhereID(['f.id' => $param3]);
            //Versi Terbaru
            $dataUkes	  	= $this->md_pengujian->getBywhereID(['f.id' => $param3]);
            if ($dataUkes[0]->idGc <= 44) {
                $page_data['page_name']     = 'pengujian/v_detail_ukes_1';
            } else {
                $page_data['page_name']     = 'pengujian/v_detail_ukes';  
            }
            //$page_data['page_name']     = 'pengujian/v_detail_ukes';
            $page_data['page_title']    = 'Uji Kesesuaian';
            $page_data['page_desc']     = 'Detail Uji Kesesuaian';
            $this->load->view('index', $page_data);
          }else if ($param2 == 'upar'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['data_upar']	  	= $this->md_pengujian->getBywhereID(['f.id' => $param3]);
            //Versi Terbaru
            $dataUpar  	= $this->md_pengujian->getBywhereID(['f.id' => $param3]);
            if ($dataUpar[0]->idGc <= 45) {
                $page_data['page_name']     = 'pengujian/v_detail_upar_1';
            } else {
                $page_data['page_name']     = 'pengujian/v_detail_upar';  
            }
			      //$page_data['page_name']     = 'pengujian/v_detail_upar';
            $page_data['page_title']    = 'Uji Paparan';
            $page_data['page_desc']     = 'Detail Data Uji Paparan';
            $this->load->view('index', $page_data);
          }
        }

    }

    

    public function add($param = "")
    {
        grantAccessFor('all');

         if($param=="ukes"){
            //menambah pengajuan Ukes
            $this->md_pengujian->reset_increment("pengujian_visilab");
            $idFpp = $this->md_pengujian->getUkesKodeId();
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
            
            $kodeFpp = $kodeFpp."/UKES/VISILAB/VYM/".$bulan."/".$tahun;
            $data['kode']			= $kodeFpp;
            
            $data['id_pengaju']     = sessPenggunaId();
            $data['id_pelanggan']   = $this->input->post('id_pelanggan', TRUE);
            $data['nama_instansi']  = $this->input->post('nama_instansi', TRUE);
            $data['jenis_uji']      = $this->input->post('jenis_uji', TRUE);
            $data['jenis_alat']     = $this->input->post('jenis_alat', TRUE);
            $data['nama_alat']      = $this->input->post('nama_alat', TRUE);
            $data['serial_number']	= $this->input->post('serial_number', TRUE);
            $data['form_ceklis']  	= $this->input->post('form_ceklis', TRUE);
            $data['link_instalasi'] = $this->input->post('link_instalasi', TRUE);
            $data['jadwal']         = date_db_format($this->input->post('jadwal', TRUE));
            $data['jadwal_end']     = date_db_format($this->input->post('jadwal_end', TRUE));
            $data['link_sph']	      = $this->input->post('link_sph', TRUE);
            $data['id_pengujian']   = 1;
            $data['status']        	= 1;
            $this->md_pengujian->add($data);
            
            
            $ambilDataPelanggan	= $this->md_pelanggan->getById($data['id_pelanggan']);
            $namaPelanggan		    = $ambilDataPelanggan[0]->identitas_pelanggan;

            //send notif wa 
            $dataWa = [
                'idPenerima1' 	=> 751,
                'idPenerima2' 	=> '',
                'namaSurat' 	=> 'Permintaan Uji Kesesuaian',
                'penerima' 	    => '_Intan Kurnia_',
                'perihal' 	    => $data['nama_alat'],
                'kode' 	        => $kodeFpp
            ];
          
            $this->notifWaAddSurat(1, $dataWa);


            //send notif Group wa VISILAB
              $dataWa = [
                'idPenerima1' 	=> 'VISILAB',
                //'idPenerima1' 	=> 'Test Api Wa Group',
                'idPenerima2' 	=> '',
                'idPenerima3' 	=> '',
                'namaSurat'  	  => 'Permintaan Uji Kesesuaian',
                'penerima' 	    => '_Team Visilab_',
                'perihal' 	    => $namaPelanggan,
                'kode' 	        => $kodeFpp
            ];
            $this->notifWaVisilabGroup(1, $dataWa);
                      

            /** LOG */
            addLog('Visilab', 'Aju Ukes Kode ' . $data['kode']);
            ajaxReturnDie('success', 'Ukes Berhasil Diajukan', TRUE);
                  

        }else if($param=="upar"){
            //menambah pengajuan Upar
            $this->md_pengujian->reset_increment("pengujian_visilab");
            $idFpp = $this->md_pengujian->getUparKodeId();
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
            
            $kodeFpp = $kodeFpp."/UPAR/VISILAB/VYM/".$bulan."/".$tahun;
            $data['kode']			= $kodeFpp;
            
            $data['id_pengaju']     = sessPenggunaId();
            $data['id_pelanggan']   = $this->input->post('id_pelanggan', TRUE);
            $data['nama_instansi']  = $this->input->post('nama_instansi', TRUE);
            $data['jenis_uji']      = $this->input->post('jenis_uji', TRUE);
            $data['jenis_alat']     = $this->input->post('jenis_alat', TRUE);
            $data['nama_alat']      = $this->input->post('nama_alat', TRUE);
            $data['serial_number']	= $this->input->post('serial_number', TRUE);
            $data['form_ceklis']  	= $this->input->post('form_ceklis', TRUE);
            $data['link_instalasi'] = $this->input->post('link_instalasi', TRUE);
            $data['jadwal']         = date_db_format($this->input->post('jadwal', TRUE));
            $data['jadwal_end']     = date_db_format($this->input->post('jadwal_end', TRUE));
            $data['link_sph']	      = $this->input->post('link_sph', TRUE);
            $data['id_pengujian']   = 2;
            $data['status']        	= 1;
            $this->md_pengujian->add($data);
            
            $ambilDataPelanggan	= $this->md_pelanggan->getById($data['id_pelanggan']);
            $namaPelanggan		    = $ambilDataPelanggan[0]->identitas_pelanggan;

            //send notif wa 
            $dataWa = [
                'idPenerima1' 	=> 751,
                'idPenerima2' 	=> '',
                'namaSurat' 	  => 'Permintaan Uji Paparan',
                'penerima' 	    => '_Intan Kurnia_',
                'perihal' 	    => $namaPelanggan,
                'kode' 	        => $kodeFpp
            ];
          
            $this->notifWaAddSurat(1, $dataWa);


           

            //send notif Group wa VISILAB
              $dataWa = [
                'idPenerima1' 	=> 'VISILAB',
                //'idPenerima1' 	=> 'Test Api Wa Group',
                'idPenerima2' 	=> '',
                'idPenerima3' 	=> '',
                'namaSurat'  	  => 'Permintaan Uji Paparan',
                'penerima' 	    => '_Team Visilab_',
                'perihal' 	    => $namaPelanggan,
                'kode' 	        => $kodeFpp
            ];
            $this->notifWaVisilabGroup(1, $dataWa);
                      

            /** LOG */
            addLog('Visilab', 'Aju Upar Kode ' . $data['kode']);
            ajaxReturnDie('success', 'Ukes Berhasil Diajukan', TRUE);
                  

        }

        
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_pengujian->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

            $id    = decrypt($param);
            $data['status'] = 0;
            $this->md_pengujian->update(['id' => $id], $data);

            //add log
            $aksi = 'Visilab';
            $ket = 'Menghapus data';
            addlog($aksi, $ket);

            ajaxReturnDie('success', 'Data berhasil dihapus', 'reload_table');

       
    }

    public function update()
    {
            grantAccessFor('all');
            $id = decrypt($this->input->post('id'));
            $data['id_pelanggan']   = $this->input->post('id_pelanggan', TRUE);
            $data['nama_instansi']  = $this->input->post('nama_instansi', TRUE);
            $data['jenis_uji']      = $this->input->post('jenis_uji', TRUE);
            $data['jenis_alat']     = $this->input->post('jenis_alat', TRUE);
            $data['nama_alat']      = $this->input->post('nama_alat', TRUE);
            $data['serial_number']	= $this->input->post('serial_number', TRUE);
            $data['form_ceklis']  	= $this->input->post('form_ceklis', TRUE);
            $data['link_instalasi'] = $this->input->post('link_instalasi', TRUE);
            $data['jadwal']         = date_db_format($this->input->post('jadwal', TRUE));
            $data['jadwal_end']     = date_db_format($this->input->post('jadwal_end', TRUE));
            $data['link_sph']	      = $this->input->post('link_sph', TRUE);
            
           
            $this->md_pengujian->update(['id' => $id], $data);

            /** LOG */
            addLog('Visilab', 'Memperbarui data Ukes "' . $data['serial_number'] . '"');
            ajaxReturnDie('success', 'Data Pengujian berhasil diperbarui', 'reload_table');
			
    }

    //UPDATE
    public function ttd_setujui($param1="", $param2="", $param3="")
    {
    	grantAccessFor('all');
        
        if($param1 == "uji"){
          if($param2 == "ttd_1"){
                    
                    $id = $this->input->post('id');
                    $data['status'] = 2;
                    $data['ttd_1']  = 1;
                    $this->md_pengujian->update(['id' => $id], $data);

                    $ambilDataPengaju   = $this->md_pengujian->getBywhereID(['f.id' => $id]);
                    $idPengujian		    = $ambilDataPengaju[0]->id_pengujian;
                    $kode		            = $ambilDataPengaju[0]->kode;

                    if($idPengujian==1){
                      $surat  = "Permintaan Uji Kesesuaian";
                      $urlNotif = "https://office.visiyosindo.id/pengujian/show/detail/ukes/$id";
                    }else if($idPengujian==2){
                      $surat  = "Permintaan Uji Paparan";
                      $urlNotif = "https://office.visiyosindo.id/pengujian/show/detail/upar/$id";
                    }

                //send notif wa
                  $dataWa = [
                            'id' 	          => $id,
                            'idPenerima1' 	=> '75',
                            'idPenerima2' 	=> '',
                            'namaSurat' 	  => $surat,
                            'urlNotif' 	    => $urlNotif,
                            'penerima' 	    => '_Mega Ratu_',
                            'ttd_sebelum1' 	=> 'Intan Kurnia',
                            'ttd_sebelum2' 	=> '',
                            'ttd_sebelum3' 	=> ''
                        ];
                    
                    $this->notifWaAprovPb(1, 1, $dataWa);

            addLog('Visilab', 'Persetujuan '.$surat.' No : '.$kode.'');
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

          }elseif($param2 == "ttd_2"){
                  
              
                $id = $this->input->post('id');
                $data['status'] = 3;
                $data['ttd_2']  = 1;
                $this->md_pengujian->update(['id' => $id], $data);

                $ambilDataPengaju   = $this->md_pengujian->getBywhereID(['f.id' => $id]);
                $idPengujian		    = $ambilDataPengaju[0]->id_pengujian;
                $kode		            = $ambilDataPengaju[0]->kode;

                    if($idPengujian==1){
                      $surat  = "Permintaan Uji Kesesuaian";
                      $urlNotif = "https://office.visiyosindo.id/pengujian/show/detail/ukes/$id";
                    }else if($idPengujian==2){
                      $surat  = "Permintaan Uji Paparan";
                      $urlNotif = "https://office.visiyosindo.id/pengujian/show/detail/upar/$id";
                    }

                //send notif wa
                  $dataWa = [
                            'id' 	          => $id,
                            'idPenerima1' 	=> '107',
                            'idPenerima2' 	=> '',
                            'namaSurat' 	  => $surat,
                            'urlNotif' 	    => $urlNotif,
                            'penerima' 	    => '_Dirangga Madali_',
                            'ttd_sebelum1' 	=> 'Intan Kurnia',
                            'ttd_sebelum2' 	=> 'Mega Ratu',
                            'ttd_sebelum3' 	=> ''
                        ];
                    
                    $this->notifWaAprovPb(1, 1, $dataWa);
                
                /** LOG */
                addLog('Visilab', 'Persetujuan '.$surat.' No : '.$kode.'');
                ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

            }elseif($param2 == "ttd_3"){
                  
              
                $id = $this->input->post('id');
                $data['status'] = 4;
                $data['ttd_3']  = 1;
                $this->md_pengujian->update(['id' => $id], $data);

                $ambilDataPengaju   = $this->md_pengujian->getBywhereID(['f.id' => $id]);
                $idPengujian		    = $ambilDataPengaju[0]->id_pengujian;
                $kode		            = $ambilDataPengaju[0]->kode;

                    if($idPengujian==1){
                      $surat  = "Permintaan Uji Kesesuaian";
                      $urlNotif = "https://office.visiyosindo.id/pengujian/show/detail/ukes/$id";
                    }else if($idPengujian==2){
                      $surat  = "Permintaan Uji Paparan";
                      $urlNotif = "https://office.visiyosindo.id/pengujian/show/detail/upar/$id";
                    }

                //send notif wa
                  $dataWa = [
                            'id' 	          => $id,
                            'idPenerima1' 	=> '23',
                            'idPenerima2' 	=> '',
                            'namaSurat' 	  => $surat,
                            'urlNotif' 	    => $urlNotif,
                            'penerima' 	    => '_Meilina Safitri_',
                            'ttd_sebelum1' 	=> 'Intan Kurnia',
                            'ttd_sebelum2' 	=> 'Mega Ratu',
                            'ttd_sebelum3' 	=> 'Dirangga Madali'
                        ];
                    
                    $this->notifWaAprovPb(1, 1, $dataWa);
                
                /** LOG */
                addLog('Visilab', 'Persetujuan '.$surat.' No : '.$kode.'');
                ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

            }elseif($param2 == "ttd_4"){
                  
              
                $id = $this->input->post('id');
                $data['status'] = 5;
                $data['ttd_4']  = 1;
                $this->md_pengujian->update(['id' => $id], $data);

                $ambilDataPengaju   = $this->md_pengujian->getBywhereID(['f.id' => $id]);
                $idPengujian		    = $ambilDataPengaju[0]->id_pengujian;
                $kode		            = $ambilDataPengaju[0]->kode;

                    if($idPengujian==1){
                      $surat  = "Permintaan Uji Kesesuaian";
                    }else if($idPengujian==2){
                      $surat  = "Permintaan Uji Paparan";
                    }

                //send notif wa
                  $dataWa = [
                            'id' 	          => $id,
                            'idPenerima1' 	=> '',
                            'idPenerima2' 	=> '',
                            'namaSurat' 	  => $surat,
                            'penerima' 	    => '',
                            'ttd_sebelum1' 	=> 'Intan Kurnia',
                            'ttd_sebelum2' 	=> 'Mega Ratu',
                            'ttd_sebelum3' 	=> 'Dirangga Madali',
                            'ttd_sebelum4' 	=> 'Meilina Safitri'
                        ];
                    
                    $this->notifWaAprovPb(1, 2, $dataWa);



                    //send notif Group wa
                    $dataGroup = [
                      'id' 	          => $id,
                      'idPenerima1' 	=> 'VISILAB',
                      //'idPenerima1' 	=> 'Test Api Wa Group',
                      'idPenerima2' 	=> '',
                      'namaSurat' 	  => $surat,
                      'penerima' 	    => '',
                            'ttd_sebelum1' 	=> 'Intan Kurnia',
                            'ttd_sebelum2' 	=> 'Mega Ratu',
                            'ttd_sebelum3' 	=> 'Dirangga Madali',
                            'ttd_sebelum4' 	=> 'Meilina Safitri'
                  ];
              
                  $this->notifWaAprovGroup(1, 1, $dataGroup);
                
                /** LOG */
                addLog('Visilab', 'Persetujuan '.$surat.' No : '.$kode.'');
                ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

            }
          }
    }


    public function ttd_tolak($param1="", $param2="", $param3="")
    {
    	grantAccessFor('all');
        
          if($param1 == "uji"){
            if($param2 == "ttd_1"){
                      
                      
                      $id = $this->input->post('id');
                      $data['status'] = 6;
                      $data['ttd_1'] = 2;
                      $this->md_pengujian->update(['id' => $id], $data);

                      $ambilDataPengaju   = $this->md_pengujian->getBywhereID(['f.id' => $id]);
                      $idPengujian		    = $ambilDataPengaju[0]->id_pengujian;
                      $kode		            = $ambilDataPengaju[0]->kode;

                      if($idPengujian==1){
                        $surat  = "Permintaan Uji Kesesuaian";
                      }else if($idPengujian==2){
                        $surat  = "Permintaan Uji Paparan";
                      }

                  //send notif wa
                    $dataWa = [
                        'namaSurat'     => $surat,
                              'id' 	    => $id,
                        'idPenolak'     => 751,
                        'namaPenolak' 	=> '*Intan Kurnia*'
                          ];
                          $this->notifWaRejectPb($dataWa);
                /** LOG */
                addLog('Visilab', 'Penolakan '.$surat.' No : '.$kode.'');
                ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

              }elseif($param2 == "ttd_2"){

                      $id = $this->input->post('id');
                      $data['status'] = 7;
                      $data['ttd_2'] = 2;
                      $this->md_pengujian->update(['id' => $id], $data);

                      $ambilDataPengaju   = $this->md_pengujian->getBywhereID(['f.id' => $id]);
                      $idPengujian		    = $ambilDataPengaju[0]->id_pengujian;
                      $kode		            = $ambilDataPengaju[0]->kode;

                      if($idPengujian==1){
                        $surat  = "Permintaan Uji Kesesuaian";
                      }else if($idPengujian==2){
                        $surat  = "Permintaan Uji Paparan";
                      }

                  //send notif wa
                    $dataWa = [
                        'namaSurat'     => $surat,
                              'id' 	    => $id,
                        'idPenolak'     => 75,
                        'namaPenolak' 	=> '*Mega ratu*'
                          ];
                          $this->notifWaRejectPb($dataWa);
                  
                  /** LOG */
                  addLog('Visilab', 'Penolakan '.$surat.' No : '.$kode.'');
                  ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

              }elseif($param2 == "ttd_3"){

                    $id = $this->input->post('id');
                    $data['status'] = 8;
                    $data['ttd_3'] = 2;
                    $this->md_pengujian->update(['id' => $id], $data);

                      $ambilDataPengaju   = $this->md_pengujian->getBywhereID(['f.id' => $id]);
                      $idPengujian		    = $ambilDataPengaju[0]->id_pengujian;
                      $kode		            = $ambilDataPengaju[0]->kode;

                      if($idPengujian==1){
                        $surat  = "Permintaan Uji Kesesuaian";
                      }else if($idPengujian==2){
                        $surat  = "Permintaan Uji Paparan";
                      }


                //send notif wa
                  $dataWa = [
                      'namaSurat'     => $surat,
                            'id' 	    => $id,
                      'idPenolak'     => 107,
                      'namaPenolak' 	=> '*Dirangga Madali*'
                        ];
                        $this->notifWaRejectPb($dataWa);
                
                /** LOG */
                addLog('Visilab', 'Penolakan '.$surat.' No : '.$kode.'');
                ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

              }elseif($param2 == "ttd_4"){

                    $id = $this->input->post('id');
                    $data['status'] = 9;
                    $data['ttd_4'] = 2;
                    $this->md_pengujian->update(['id' => $id], $data);

                      $ambilDataPengaju   = $this->md_pengujian->getBywhereID(['f.id' => $id]);
                      $idPengujian		    = $ambilDataPengaju[0]->id_pengujian;
                      $kode		            = $ambilDataPengaju[0]->kode;

                      if($idPengujian==1){
                        $surat  = "Permintaan Uji Kesesuaian";
                      }else if($idPengujian==2){
                        $surat  = "Permintaan Uji Paparan";
                      }


                //send notif wa
                  $dataWa = [
                      'namaSurat'     => $surat,
                            'id' 	    => $id,
                      'idPenolak'     => 23,
                      'namaPenolak' 	=> '*Meilina Safitri*'
                        ];
                        $this->notifWaRejectPb($dataWa);
                
                /** LOG */
                addLog('Visilab', 'Penolakan '.$surat.' No : '.$kode.'');
                ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

              }

          }


    }


        


   

    public function updateSetujuUji()
    {
            grantAccessFor('all');
            $id = decrypt($this->input->post('id'));
            $data['link_lhu']               = $this->input->post('link_lhu', TRUE);
            $data['link_sertifikat']        = $this->input->post('link_sertifikat', TRUE);
            $data['status']        	        = 10;
            
            $this->md_pengujian->update(['id' => $id], $data);

            $ambilDataPengaju   = $this->md_pengujian->getBywhereID(['f.id' => $id]);
            $idPengujian		    = $ambilDataPengaju[0]->id_pengujian;
            $kode		            = $ambilDataPengaju[0]->kode;

            if($idPengujian==1){
              $surat  = "Permintaan Uji Kesesuaian";
            }else if($idPengujian==2){
              $surat  = "Permintaan Uji Paparan";
            }

                  //send notif wa
                  $dataWa = [
                            'id' 	          => $id,
                            'idPenerima1' 	=> '737',
                            'idPenerima2' 	=> '',
                            'namaSurat' 	  => $surat,
                            'penerima' 	    => '_Grup Visilab_',
                            'ttd_sebelum1' 	=> 'Head of Technician, Media Technology, and Sec',
                            'ttd_sebelum2' 	=> 'Regulatory',
                            'ttd_sebelum3' 	=> 'Senior Accounting and Finance',
                            'ttd_sebelum3' 	=> 'Director of Corporate Planning and Business Management'
                        ];
                    
                    $this->notifWaAprovPb(1, 1, $dataWa);

            /** LOG */
                addLog('Submit Sertifikat', ''.$surat.' No : '.$kode.'');
                ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
			
    }



    public function pagination($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'ukes') {
            $dt     = $this->md_pengujian->getAllUkes();
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {
              
              if($row->status== "1"){
                $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
              }else if($row->status == "2"){
                  $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Oleh Staff Teknis</span>';
              }else if($row->status == "3"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Oleh Manager Puncak</span>';
              }else if($row->status == "4"){
                 $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Oleh Senior Accounting and Finance</span>';
              }else if($row->status == "5"){
                 $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Oleh Director of Corporate Planning and Business Management</span>';
              }else if($row->status == "10"){
                 $stat_surat = '<span class="badge badge-ecommerce badge-info">Sertifikat Sudah Disubmit</span>';
              }else{
                 $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
              }


            $kode	= '<a href="pengujian/show/detail/ukes/'.$row->idGc.'">'.$row->kode.'</a>';
            $id       	= encrypt($row->idGc);
            $li_btn   	= '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                   <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="'. $id .'" data-object="pengujian/delete/'. $id .'"><i class="bx bx-trash"></i></button>
                </div>';
              
              $th = array();
              $th[] = ++$start;
              $th[] = $kode;
              $th[] = $row->identitas_pelanggan;
              $th[] = $row->jenis_uji;
              $th[] = $row->jenis_alat;
              $th[] = $row->nama_alat;
              $th[] = $row->serial_number;
              $th[] = $row->pengaju;
              $th[] = $stat_surat;
              $th[] = $li_btn;
              $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;					
        
      }else if ($param == 'upar') {
            $dt     = $this->md_pengujian->getAllUpar();
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {
              
              if($row->status== "1"){
                $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
              }else if($row->status == "2"){
                  $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Oleh Staff Teknis</span>';
              }else if($row->status == "3"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Oleh Manager Puncak</span>';
              }else if($row->status == "4"){
                 $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Oleh Senior Accounting and Finance</span>';
              }else if($row->status == "5"){
                 $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Oleh Director of Corporate Planning and Business Management</span>';
              }else if($row->status == "10"){
                 $stat_surat = '<span class="badge badge-ecommerce badge-info">Sertifikat Sudah Disubmit</span>';
              }else{
                 $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
              }

            $kode	= '<a href="pengujian/show/detail/upar/'.$row->idGc.'">'.$row->kode.'</a>';
            $id       	= encrypt($row->idGc);
            $li_btn   	= '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                   <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="'. $id .'" data-object="pengujian/delete/'. $id .'"><i class="bx bx-trash"></i></button>
                </div>';
              
              $th = array();
              $th[] = ++$start;
              $th[] = $kode;
              $th[] = $row->identitas_pelanggan;
              $th[] = $row->jenis_uji;
              $th[] = $row->jenis_alat;
              $th[] = $row->nama_alat;
              $th[] = $row->serial_number;
              $th[] = $row->pengaju;
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
                'namaSurat' 	=> $detail['namaSurat'],
                'noPenerima' 	=> $nope,
                'kodeSurat' 	=> $detail['kode'],
                'namaPengaju' => urlencode($namaPengaju),
                'perihal' 		=> urlencode($detail['perihal']),
                'namaPenerima' 	=> urlencode($detail['penerima'])
                ];
                waSuratOpen($dataWa);
        }
    }


     public function notifWaAprovPb($ulang, $param, $detail){
      //ambil data pengaju
      $ambilDataPengaju   = $this->md_pengujian->getBywhereID(['f.id' => $detail['id']]);
      $namaPengaju		    = $ambilDataPengaju[0]->pengaju;
      $kode               = $ambilDataPengaju[0]->kode;
      $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
      $idpengaju          = $ambilDataPengaju[0]->idPengaju;
      
      //if($idpengaju == 1){
      //    $idpengaju == 63;
      //}
      
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
          $dataWa = [
              'namaSurat' 	  => $detail['namaSurat'],
              'urlNotif' 	    => $detail['urlNotif'],
              'noPenerima'  	=> $nope,
              'kodeSurat' 	  => $kode,
              'namaPengaju' 	=> urlencode($namaPengaju),
              'namaPenerima' 	=> urlencode($detail['penerima']),
              'perihal' 		  => urlencode($perihal),
              'ttd_sebelum1' 	=> urlencode($detail['ttd_sebelum1']),
              'ttd_sebelum2' 	=> urlencode($detail['ttd_sebelum2']),
              'ttd_sebelum3' 	=> urlencode($detail['ttd_sebelum3']),
              'ttd_sebelum4' 	=> urlencode($detail['ttd_sebelum4'])
          ];
          
          if($param == '1'){
            waSuratAprovOnProgVisilab($dataWa);
          }else if($param == '2'){
              waSuratAprovAll($dataWa);
          }
      }
    }


    public function notifWaRejectPb($detail){
      //ambil data pengaju
      $ambilDataPengaju   = $this->md_pengujian->getBywhereID(['f.id' => $detail['id']]);
      $namaPengaju		    = $ambilDataPengaju[0]->pengaju;
      $kode               = $ambilDataPengaju[0]->kode;
      $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
      $idpengaju          = $ambilDataPengaju[0]->idPengaju;
      
      //if($idpengaju == 1){
      //   $idpengaju == 63;
      //}
      
      //ambil nomor
      $dataPenerima 	    = $this->md_pengguna->getById($idpengaju);
      $nope               = $dataPenerima[0]->no_hp;
      $dataPenolak 	      = $this->md_pengguna->getById($detail['idPenolak']);
      $nopePenolak        = $dataPenolak[0]->no_hp;
                  
      //send notif wa
      $dataWa = [
          'namaSurat' 	  => $detail['namaSurat'],
          'noPenerima' 	  => $nope,
          'kodeSurat' 	  => $kode,
          'namaPengaju'   => urlencode($namaPengaju),
          'perihal' 		  => urlencode($perihal),
          'namaPenolak'   => urlencode($detail['namaPenolak']),
          'noPenolak' 	  => $nopePenolak
      ];
      waSuratReject($dataWa);
    }

    public function notifWaVisilabGroup($ulang, $detail){
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
              'namaSurat'   	=> $detail['namaSurat'],
              'noPenerima'  	=> $idpenerima,
              'namaPengaju' 	=> urlencode($namaPengaju),
              'perihal' 	    => urlencode($detail['perihal']),
              'kode' 	        => $detail['kode'],
              'namaPenerima' 	=> urlencode($detail['penerima'])
                ];
                waPermintaanVisilabGroup($dataWa);
        }
      }


      public function notifWaAprovGroup($ulang, $param, $detail){
      //ambil data pengaju
      $ambilDataPengaju   = $this->md_pengujian->getBywhereID(['f.id' => $detail['id']]);
      $namaPengaju		    = $ambilDataPengaju[0]->pengaju;
      $kode               = $ambilDataPengaju[0]->kode;
      $perihal            = $ambilDataPengaju[0]->identitas_pelanggan;
      $idpengaju          = $ambilDataPengaju[0]->idPengaju;
      
      
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
              'namaSurat' 	  => $detail['namaSurat'],
              'noPenerima'  	=> $idpenerima,
              'kode' 	      => $kode,
              'namaPengaju' 	=> urlencode($namaPengaju),
              'perihal'   	  => urlencode($perihal),
              'namaPenerima' 	=> urlencode($detail['penerima']),
              'ttd_sebelum1' 	=> urlencode($detail['ttd_sebelum1']),
              'ttd_sebelum2' 	=> urlencode($detail['ttd_sebelum2']),
              'ttd_sebelum3' 	=> urlencode($detail['ttd_sebelum3']),
              'ttd_sebelum4' 	=> urlencode($detail['ttd_sebelum4'])
          ];
          
          if($param == '1'){
            waVisilabAprovAllGroup($dataWa);
          }else if($param == '2'){
            waPoAprovAll($dataWa);
          }
      }
    }



    public function print_page($param1="", $param2="")
    {
        grantAccessFor('all');

        if($param1 == 'ukes'){

            $gc							= $this->md_pengujian->getBywhereID(['f.id' => $param2]);
            
            $dt = [
              'title_pdf'	=> 'ukes',
              'object'	  => $param1,
              'data_ukes'	=> $this->md_pengujian->getBywhereID(['f.id' => $param2])
            ];
            
            //load mpdf dan membuat page size 
            $mpdf = new Mpdf(['format' => 'A4']);

           // $mpdf->SetMargins(0, 0, 0, true);

            //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
	          $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');
              
            // filename dari pdf ketika didownload
            $file_pdf = 'Permintaan Uji Kesesuaian  '.$gc[0]->identitas_pelanggan;
            
            // page html yang akan di jadikan ke pdf
            if ($gc[0]->idGc <= 44) {
                $html = $this->load->view('pages/v_print/print_ukes_1', $dt, true);
            } else {
                $html = $this->load->view('pages/v_print/print_ukes', $dt, true); 
            }
            //$html = $this->load->view('pages/v_print/print_ukes', $dt, true);
            
            $mpdf->WriteHTML($html);
            $mpdf->Output($file_pdf . '.pdf', 'I');

        }else if($param1 == 'upar'){

            $gc							= $this->md_pengujian->getBywhereID(['f.id' => $param2]);
            
            $dt = [
              'title_pdf'	=> 'upar',
              'object'	  => $param1,
              'data_ukes'	=> $this->md_pengujian->getBywhereID(['f.id' => $param2])
            ];
            
            //load mpdf dan membuat page size 
            $mpdf = new Mpdf(['format' => 'A4']);

           // $mpdf->SetMargins(0, 0, 0, true);

            //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
	          $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');
              
            // filename dari pdf ketika didownload
            $file_pdf = 'Permintaan Uji Paparan  '.$gc[0]->identitas_pelanggan;
            
            // page html yang akan di jadikan ke pdf
            if ($gc[0]->idGc <= 45) {
                $html = $this->load->view('pages/v_print/print_upar_1', $dt, true);
            } else {
                $html = $this->load->view('pages/v_print/print_upar', $dt, true); 
            }
            //$html = $this->load->view('pages/v_print/print_upar', $dt, true);
            
            $mpdf->WriteHTML($html);
            $mpdf->Output($file_pdf . '.pdf', 'I');

        }


                  
    }



    



}