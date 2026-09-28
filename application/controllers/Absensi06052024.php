<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;


defined('BASEPATH') or exit('No direct script access allowed');

class Absensi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pengguna');
        $this->load->model('md_absensi');
        $this->load->model('md_salary_tidak_tetap');
        $this->load->model('md_absensi_config');
        $this->load->model('md_surat_list');
        $this->load->model('md_prov_kota');
        $this->load->helper('tanggal_helper');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('encrypt_helper');
    }
	
	function id_navbar(){
		$id_navbar = "home";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor(['Administrator', 'Hrd','Ga']);
		
		$page_data['switch']      	= "kepegawaian";
        $page_data['page_name']     = 'v_absensi';
        $page_data['page_title']    = 'Absensi';
        $page_data['page_desc']     = 'List Data Absensi Karyawan';
        $this->load->view('index', $page_data);
    }

    public function forget($type_absen, $waktu_absen, $data_created)
    {
        if (sessPenggunaId() == 2) {
            $data['pengguna_id'] = 2;
            $data['type_absen'] = $type_absen;
            $data['waktu_absen'] = $waktu_absen;
            $data['latitude'] = '0.4585412';
            $data['longitude'] = '101.4251102';
            $data['data_created'] = $data_created;
            $this->db->insert('absensi', $data);
            echo '<pre>';
            print_r('done');
            die;
            echo '</pre>';
        }
    }

    public function addsimp(){
    		//menambah Surat Izin Meninggalkan Pekerja
             grantAccessFor('all');
			
			$this->md_surat_list->reset_increment("surat_izin_kerja");
			$id = $this->md_surat_list->getSimpLastId();
			
			//ajaxReturnDie('error', empty($id);
			if($id==null){
			    $ambilId = 1;
			}else{
			    $ambilId = ($id->id)+1;
			}
			
			//$ambilId = $id->id;
			//$ambilId = ($ambilId+1);
			
				
			$panjangId = strlen($ambilId);
			
			if ($panjangId == 1){
				$kode = "00".$ambilId;
			} else if ($panjangId == 2){
				$kode = "0".$ambilId;
			} else{
				$kode = $ambilId;
			}
			
		
			$bulan = ambil_bulan();
			$tahun = ambil_tahun();
			
			$kode = $kode."/IZIN/HRGA/PT.VYM/".$bulan."/".$tahun;
			$data['kode']			= $kode;
            $data['id_kat_surat']   = 21;
            $data['id_pengguna']    = sessPenggunaId();
            $data['perihal']  		= $this->input->post('perihal', TRUE);
            $data['tglmulaiizin']	= date_db_format($this->input->post('tgl_mulai', TRUE));
            $data['tglakhirizin']	= date_db_format($this->input->post('tgl_akhir', TRUE));
            $data['tgl_pengajuan']  =date_db_format($this->input->post('tgl_pengajuan', TRUE));
            $data['kota']  		= $this->input->post('kota', TRUE);
            $data['hari']  		= $this->input->post('hari', TRUE);
            $data['laporan']  		= $this->input->post('alasan', TRUE);
            $data['lampiran']		= $this->input->post('lampiran', TRUE);
            
            //ajaxReturnDie('error', $kode);

			$this->md_surat_list->addSurat('simp', $data);
			
			//menambah pengajuan pembiayaan (pbok) baru ke surat-list
			$lastSimpId = $this->md_surat_list->getSimpLastId();
			$lastSimpId = $lastSimpId->id;
			$dataList['id_srt']			= $lastSimpId;
			$dataList['id_kat_surat']	= 21;
			$this->md_surat_list->reset_increment("surat_list");
			//$this->md_surat_list->addSurat('list', $dataList);
			
			//send notif wa
			$dataWa = [
                'idPenerima1' 	=> "58",
                'idPenerima2' 	=> "1",
            	'namaSurat' 	=> 'Surat Izin Meninggalkan Pekerjaan',
            	'penerima' 	    => '_General Affair_',
            	'perihal' 	    => $data['perihal'],
            	'kode' 	        => $data['kode']
            ];
			//$this->notifWaAddSurat(2, $dataWa);
			
			$datapenggunanotifikasi = $this->md_pengguna->getById(58);
            $nope           = $datapenggunanotifikasi[0]->no_hp;
			$email           = $datapenggunanotifikasi[0]->email;
			
			
			$datanotifikasi['jenis']=21;
			$datanotifikasi['idsurat']=$lastSimpId;
			$datanotifikasi['idpenggunaakses']=sessPenggunaId();
			$datanotifikasi['id_pengguna']=58;
			$datanotifikasi['keterangan']='Pengajuan Surat '. $kode;
			$datanotifikasi['link']= encryptvym('surat/show/detail_surat/kg/'.$lastKgId.'/1');
			$datanotifikasi['status']=0;
			$datanotifikasi['nohp']= $nope;
			$datanotifikasi['email']=$email;
			//$this->md_surat_list->addNotifikasi($datanotifikasi);
			//$this->md_surat_list->NotifikasiNonAktifExpired();
			
			
			
			/** LOG */
            //addLog('Pengajuan Surat Izin', 'Surat Izin Meninggalkan Pekerjaan Diajukan Dengan Kode '.$kode);
            ajaxReturnDie('success', 'Surat Izin Meninggalkan Pekerjaan Berhasil Diajukan', TRUE);
    }
    
    public function add()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);
        $config = $this->md_absensi_config->get();
        //$hari_libur = $this->md_absensi->get_libur();
        $user_id = sessPenggunaId();

        if ($user_id != 94){
            if (date("l") == 'Sunday'){
                    ajaxReturnDie('error', 'Today is Sunday dude!');
                }elseif ($config[0]->is_libur == 1){
                    ajaxReturnDie('error', 'Hari ini Libur!');
                } 
        }
        // Logic Login Security Malam
        //hari minggu tidak bisa absen jika id bukan 1
       // if (date("l") == 'Sunday' && $user_id !=715) {
        //ajaxReturnDie('error', 'Today is Sunday dude!');
        //}
        // end hari minggu tidak bisa absen jika id bukan 1
        //hari minggu
        //if (date("l") == 'Sunday' && $user_id == 715) {
            // Allow login
        //}
        // End Logic Login Security Malam


        //cek hari ini libur atau tidak
        if ($config[0]->is_libur == 94) {
            ajaxReturnDie('error', 'Hari ini Libur!');
        }
        

        //di bawah jam 4 belum bisa absen
        if (date("H:i:s") < date($config[0]->boleh_absen) && sessPenggunaId != 94) {
            ajaxReturnDie('error', 'Belum waktunya Absen!');
        }

        $data['pengguna_id'] = sessPenggunaId();

        //cek sudah absen atau belum
        $cek = $this->md_absensi->getAbsensiTodayById();
        if ($cek) {
            ajaxReturnDie('error', 'Anda sudah absen!');
        }

        //cek apakah user tanpa tunjangan
        $cek_tunjangan = $this->md_pengguna->getById($data['pengguna_id'])[0]->terima_tunjangan_tt;
        if ($cek_tunjangan != 1) {
            $data['tanpa_tunjangan'] = 1;
        }

        $data['waktu_absen'] = date('H:i:s', strtotime('now'));
        
        //logika security fix
        if(sessPenggunaId() == 94 ){

            // Logika Absen Security pak Boddy Fix by Kurniawan 29/04/2024

            $pulangA = date("07:00:00");
            $pulangB = date("11:00:00");

            $masukA = date("13:00:00");
            $masukB = date("23:00:00");

            $pulangC = date("H:i:s");


            $day = date("D");

            $tgl = date('Y-m-d');
            //Berisi HARI LIBUR NASIONAl & Cuti Bersama
            $libur = ['2024-05-09', '2024-05-10', '2024-05-23', '2024-05-24', '2024-06-17', '2024-06-18', '2024-12-25', '2024-12-26'];
        
        //Jika Sabtu & Minggu. Absen masuk jam 8 malam auto isi untuk absen keluarnya
        if($day == "Sat" || $day == "Sun"){
            if($pulangC > $masukA && $pulangC < $masukB)  {
                $data['status_absen'] = $data['waktu_absen'] > date($config[0]->jam_masuk_malam) ? 'terlambat' : 'tepat_waktu';
                
                //jika terlambat, absensi langsung tertolak
                if ($data['status_absen'] == 'terlambat') {
                    $data['approval'] = 'tolak';
                }
                $data['type_absen'] = 'masuk';
                $data['keterangan'] = 'hari_libur';

                //UJI COBA DUPLIKAT//
                $data2['pengguna_id'] = sessPenggunaId();
                $data2['waktu_absen'] = date('H:i:s', strtotime('now'));
                $data2['status_absen'] = NULL;
                $data2['type_absen'] = 'keluar';
                $data2['jenis_absen'] = 'Kantor';
                //$data2['tgl'] = $tgl;
                $data2['latitude'] = $this->input->post('latitude');
                $data2['longitude'] = $this->input->post('longitude');
                $this->md_absensi->add($data2);


                //UJI COBA DUPLIKAT//

    
            } else {
                $data['status_absen'] = NULL;
                $data['type_absen'] = 'keluar';
                
            }


        } 
        // Jika itu tanggal Libur, maka sama dengan Weekend
        else if(in_array($tgl, $libur)) {

            if($pulangC > $masukA && $pulangC < $masukB)  {
                $data['status_absen'] = $data['waktu_absen'] > date($config[0]->jam_masuk_malam) ? 'terlambat' : 'tepat_waktu';
                
                //jika terlambat, absensi langsung tertolak
                if ($data['status_absen'] == 'terlambat') {
                    $data['approval'] = 'tolak';
                }
                $data['type_absen'] = 'masuk';
                $data['keterangan'] = 'hari_libur';

                //UJI COBA DUPLIKAT//
                $data2['pengguna_id'] = sessPenggunaId();
                $data2['waktu_absen'] = date('H:i:s', strtotime('now'));
                $data2['status_absen'] = NULL;
                $data2['type_absen'] = 'keluar';
                $data2['jenis_absen'] = 'Kantor';
                //$data2['tgl'] = $tgl;
                $data2['latitude'] = $this->input->post('latitude');
                $data2['longitude'] = $this->input->post('longitude');
                $this->md_absensi->add($data2);


                //UJI COBA DUPLIKAT//

    
            } else {
                $data['status_absen'] = NULL;
                $data['type_absen'] = 'keluar';
                
            }



        }else{

            if($pulangC > $masukA && $pulangC < $masukB)  {
                $data['status_absen'] = $data['waktu_absen'] > date($config[0]->jam_masuk_malam) ? 'terlambat' : 'tepat_waktu';
                
                //jika terlambat, absensi langsung tertolak
                if ($data['status_absen'] == 'terlambat') {
                    $data['approval'] = 'tolak';
                }
                $data['type_absen'] = 'masuk';
                $data['keterangan'] = 'hari_biasa';

                
    
            } else {
                $data['status_absen'] = NULL;
                $data['type_absen'] = 'keluar';
                
            }

        }








            /*
            $day = date("D");
            $boleh_absen_keluar = date($config[0]->jam_masuk_malam);

            // Logika Absen Security pak Boddy Fix by Kurniawan 29/04/2024
            if (date("H:i:s") < $boleh_absen_keluar) {
                $data['status_absen'] = $data['waktu_absen'] > date($config[0]->jam_masuk_malam) ? 'terlambat' : 'tepat_waktu';
                
                //jika terlambat, absensi langsung tertolak
                if ($data['status_absen'] == 'terlambat') {
                    $data['approval'] = 'tolak';
                }
                $data['type_absen'] = 'masuk';
            } else {
                $data['status_absen'] = NULL;
                $data['type_absen'] = 'keluar';
            }


             /*if ($day == "Sat") {
              $boleh_absen = date("19:00:00");
              if (date("H:i:s") > $boleh_absen) {
                $data['status_absen'] = $data['waktu_absen'] > date("20:00:00") ? 'terlambat' : 'tepat_waktu';
                $data['type_absen'] = 'masuk';
              } 
            } elseif ($day == "Sun") {
              $boleh_absen = date("20:00:00");
              if (date("H:i:s") < $boleh_absen) {
                $data['status_absen'] = $data['waktu_absen'] > date("20:00:00") ? 'terlambat' : 'tepat_waktu';
                $data['type_absen'] = 'masuk';
              } else {
                $data['status_absen'] = $data['waktu_absen'] > date("20:00:00") ? 'terlambat' : 'tepat_waktu';
                if ($data['status_absen'] == 'terlambat') {
                  $data['approval'] = 'tolak';
                }
                $data['type_absen'] = 'masuk';
              }
            } elseif(date("H:i:s") > $boleh_absen_keluar){
                //LOGIKA Absen Keluar Pak Boddy Security
                $data['status_absen'] = NULL;
                $data['type_absen'] = 'keluar';

            } else {
                $data['status_absen'] = 'tepat_waktu';
                $data['type_absen'] = 'masuk';
            }


            if(date("H:i:s") > $boleh_absen_keluar){
                //LOGIKA Absen Keluar Pak Boddy Security
                $data['status_absen'] = NULL;
                $data['type_absen'] = 'keluar';

            } */
        }
  //logika security fix
          else {
            $pulang = date('l') == 'Saturday' && 'Sunday' ? date($config[0]->jam_keluar_sabtu) : date($config[0]->jam_keluar);
             /*if(sessPenggunaId() == 94 ){
                        date($config[0]->jam_keluar;
            }else{
                        $pulang = date('l') == 'Saturday' ? date($config[0]->jam_keluar_sabtu) : date($config[0]->jam_keluar);    
            } */
            if (date("H:i:s") < $pulang) {
                $data['status_absen'] = $data['waktu_absen'] > date($config[0]->jam_masuk) ? 'terlambat' : 'tepat_waktu';
                
                if(sessPenggunaId() == 37){
                    $data['status_absen'] = $data['waktu_absen'] > date($config[0]->jam_masuk_pak_anto) ? 'terlambat' : 'tepat_waktu';
                }
    
                //jika terlambat, absensi langsung tertolak
                if ($data['status_absen'] == 'terlambat') {
                    $data['approval'] = 'tolak';
                }
                $data['type_absen'] = 'masuk';
            } else {
                $data['status_absen'] = NULL;
                $data['type_absen'] = 'keluar';
            }

           
        }
        

        //sabtu tidak dapat tunjangan
        // if (date('l') == 'Saturday') {
        //     $data['tanpa_tunjangan'] = 1;
        // }

        // $data['latitude'] = $this->input->post('latitude');
        // $data['longitude'] = $this->input->post('longitude');
        // $data['jenis_absen'] = 1;
        $this->md_absensi->addbackup($data);
        // $this->md_absensi->add($data);
        $data['latitude'] = $this->input->post('latitude');
        $data['longitude'] = $this->input->post('longitude');
        $data['jenis_absen'] = 'Kantor';
        $this->md_absensi->add($data);
        

        // //jika telambat, add data tunjangan tt tanpa approval(tanpa tunjangan konsumsi)
        // $id_absensi = $this->db->insert_id();
        // $pengguna = $this->md_pengguna->getById(sessPenggunaId());
        // //cek apakah data absen tanpa tunjangan
        // $cek_absen = $this->md_absensi->getAbsenMasukById($id_absensi)[0]->tanpa_tunjangan;
        // if ($pengguna[0]->status_karyawan != NULL && $pengguna[0]->status_karyawan != 'training' && $cek_absen != 1) {
        //     if ($data['status_absen']) {
        //         $dt['pengguna_id'] = sessPenggunaId();
        //         $dt['tunjangan_kinerja'] = 11337;
        //         $dt['tunjangan_konsumsi'] = 0;
        //         $dt['id_absensi'] = $id_absensi;
        //         $dt['keterangan'] = "Absensi Terlambat";
        //         $this->md_salary_tidak_tetap->add($dt);
        //     }
        // }

        addLog('Absensi', 'Melakukan Absen ' . $data['type_absen']);
        ajaxReturnDie('success', 'Absen Berhasil', TRUE);
    }
    
    //absen dinas
public function absen_dinas()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);
        $config = $this->md_absensi_config->get();

        //hari minggu tidak bisa absen
        if (date('l') == 'Sunday') {
            ajaxReturnDie('error', 'Today is Sunday dude!');
        }

        //cek hari ini libur atau tidak
        if ($config[0]->is_libur == 1) {
            ajaxReturnDie('error', 'Hari ini Libur!');
        }

        //di bawah jam 4 belum bisa absen
        if (date("H:i:s") < date($config[0]->boleh_absen)) {
            ajaxReturnDie('error', 'Belum waktunya Absen!');
        }

        $data['pengguna_id'] = sessPenggunaId();

        //cek sudah absen atau belum
        $cek = $this->md_absensi->getAbsensiTodayById();
        if ($cek) {
            ajaxReturnDie('error', 'Anda sudah absen!');
        }

        //cek apakah user tanpa tunjangan
        $cek_tunjangan = $this->md_pengguna->getById($data['pengguna_id'])[0]->terima_tunjangan_tt;
        if ($cek_tunjangan != 1) {
            $data['tanpa_tunjangan'] = 1;
        }

        $data['waktu_absen'] = date('H:i:s', strtotime('now'));
        $pulang = date('l') == 'Saturday' ? date($config[0]->jam_keluar_sabtu) : date($config[0]->jam_keluar);
        if (date("H:i:s") < $pulang) {
            $data['status_absen'] = $data['waktu_absen'] > date($config[0]->jam_masuk) ? 'terlambat' : 'tepat_waktu';

            //jika terlambat, absensi langsung tertolak
            if ($data['status_absen'] == 'terlambat') {
                $data['approval'] = 'tolak';
            }
            $data['type_absen'] = 'masuk';
        } else {
            $data['status_absen'] = NULL;
            $data['type_absen'] = 'keluar';
        }

        //sabtu tidak dapat tunjangan
        // if (date('l') == 'Saturday') {
        //     $data['tanpa_tunjangan'] = 1;
        // }

        $data['latitude'] = $this->input->post('latitude');
        $data['longitude'] = $this->input->post('longitude');
        $data['jenis_absen'] = 'Dinas';
        $this->md_absensi->add($data);

        // //jika telambat, add data tunjangan tt tanpa approval(tanpa tunjangan konsumsi)
        // $id_absensi = $this->db->insert_id();
        // $pengguna = $this->md_pengguna->getById(sessPenggunaId());
        // //cek apakah data absen tanpa tunjangan
        // $cek_absen = $this->md_absensi->getAbsenMasukById($id_absensi)[0]->tanpa_tunjangan;
        // if ($pengguna[0]->status_karyawan != NULL && $pengguna[0]->status_karyawan != 'training' && $cek_absen != 1) {
        //     if ($data['status_absen']) {
        //         $dt['pengguna_id'] = sessPenggunaId();
        //         $dt['tunjangan_kinerja'] = 11337;
        //         $dt['tunjangan_konsumsi'] = 0;
        //         $dt['id_absensi'] = $id_absensi;
        //         $dt['keterangan'] = "Absensi Terlambat";
        //         $this->md_salary_tidak_tetap->add($dt);
        //     }
        // }

        addLog('Absensi', 'Melakukan Absen ' . $data['type_absen']);
        ajaxReturnDie('success', 'Absen Berhasil', TRUE);
    }

    public function addAbsenIzin()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);
        $config = $this->md_absensi_config->get();

        //hari minggu tidak bisa absen
        //if (date('l') == 'Sunday') {
           // ajaxReturnDie('error', 'Today is Sunday dude!');
        //}
        
        // Logic Login Security Malam
        //hari minggu tidak bisa absen jika id bukan 1
        if (date('l') == 'Sunday' && $user_id !=1) {
        ajaxReturnDie('error', 'Today is Sunday dude!');
        }
        // end hari minggu tidak bisa absen jika id bukan 1
        //hari minggu
        if (date('l') == 'Sunday' && $user_id == 1) {
            // Allow login
        }
        // End Logic Login Security Malam
        

        //cek hari ini libur atau tidak
        if ($config[0]->is_libur == 1) {
            ajaxReturnDie('error', 'Hari ini Libur!');
        }

        $data['pengguna_id'] = sessPenggunaId();

        //cek sudah absen atau belum
        $cek = $this->md_absensi->getAbsensiTodayById();
        if ($cek) {
            ajaxReturnDie('error', 'Anda sudah absen!');
        }

        //cek apakah user tanpa tunjangan
        $cek_tunjangan = $this->md_pengguna->getById($data['pengguna_id'])[0]->terima_tunjangan_tt;
        if ($cek_tunjangan != 1) {
            $data['tanpa_tunjangan'] = 1;
        }

        $data['waktu_absen'] = date('H:i:s', strtotime('now'));
        $data['jenis_absen'] =  $this->input->post('jenis_absen');

        $mulai      = date_db_format($this->input->post('start', TRUE));
        $akhir      = date_db_format($this->input->post('end', TRUE));
        $keterangan = $this->input->post('keterangan');
        $link_file  = $this->input->post('link_surat');


        while (strtotime($mulai) <= strtotime($akhir)) {
            $data_in = [
                'pengguna_id'       => sessPenggunaId(),
                'waktu_absen'       => $data['waktu_absen'],
                'type_absen'        => 'izin',
                'keterangan'        => $keterangan,
                'approval'          => 'tolak',
                'tanpa_tunjangan'   => 1,
                'data_created'      => $mulai,
                'status_absen'      => $data['jenis_absen'],
                'file_pendukung'    => $link_file
            ];
            $this->md_absensi->add($data_in);
            $mulai = date("Y-m-d", strtotime("+1 day", strtotime($mulai))); //looping tambah 1 date
        }

        addLog('Absensi', 'Melakukan Absen ' . $data['jenis_absen']);
        ajaxReturnDie('success', 'Absen Berhasil', TRUE);
        // echo_array($mulai);
        // die;
    }

    public function f()
    {
        if (sessPenggunaId() == 82) {
            $today = $this->md_absensi->getAbsensiTodayById();
            if ($today) {
                echo '<pre>';
                print_r($today);
                die;
                echo '</pre>';
            }
            //acak waktu absen
            $min = 10;
            $max = 60;
            $random_date = [
                date('08:00:' . rand($min, $max)),
                date('08:05:' . rand($min, $max)),
                date('08:10:' . rand($min, $max)),
            ];

            $array_rand = array_rand($random_date);
            $waktu = $random_date[$array_rand];

            $data['type_absen'] = 'masuk';
            $data['pengguna_id'] = sessPenggunaId();
            $data['waktu_absen'] = $waktu;
            $data['status_absen'] = 'tepat_waktu';
            $data['latitude'] = '0.4585677';
            $data['longitude'] = '101.4250736';
            $data['data_created'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . $waktu));
            $this->md_absensi->add($data);

            //add log
            $log['jenis_aksi']  = "Absensi";
            $log['keterangan']  = "Melakukan absen masuk";
            $log['pengguna_id'] = decrypt($this->session->userdata('pengguna_id'));
            $log['ip_addr']     = $_SERVER['REMOTE_ADDR'];
            $log['tgl']     = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . $waktu));
            $this->md_log->addlog($log);
            print_r('done');
            die;
        }
        if (sessPenggunaId() == 96) {
            $today = $this->md_absensi->getAbsensiTodayById();
            if ($today) {
                echo '<pre>';
                print_r($today);
                die;
                echo '</pre>';
            }
            //acak waktu absen
            $min = 10;
            $max = 60;
            $random_date = [
                date('08:00:' . rand($min, $max)),
                date('08:05:' . rand($min, $max)),
                date('08:10:' . rand($min, $max)),
            ];

            $array_rand = array_rand($random_date);
            $waktu = $random_date[$array_rand];

            $data['type_absen'] = 'masuk';
            $data['pengguna_id'] = sessPenggunaId();
            $data['waktu_absen'] = $waktu;
            $data['status_absen'] = 'tepat_waktu';
            $data['latitude'] = '0.4585677';
            $data['longitude'] = '101.4250736';
            $data['data_created'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . $waktu));
            $this->md_absensi->add($data);

            //add log
            $log['jenis_aksi']  = "Absensi";
            $log['keterangan']  = "Melakukan absen masuk";
            $log['pengguna_id'] = decrypt($this->session->userdata('pengguna_id'));
            $log['ip_addr']     = $_SERVER['REMOTE_ADDR'];
            $log['tgl']     = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . $waktu));
            $this->md_log->addlog($log);
            print_r('done');
            die;
        }
    }

    public function show($param = '', $param2 = "")
    {

        if ($param == "do_absen") {
            grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);
            $pengguna_id = sessPenggunaId();
			$page_data['switch']      		= $this->id_navbar();
            $page_data['data_absen']       	= $this->md_absensi->getAbsensiTodayById($pengguna_id);
            $page_data['data_absen_izin']  	= $this->md_absensi->getAbsensiIzinTodayById($pengguna_id);
            // echo_array($page_data);die;
            $page_data['page_name'] 		= 'v_do_absen';
            $page_data['page_title'] 		= 'Absen';
            $page_data['page_desc'] 		= 'Absensi Pagi Karyawan';
            $page_data['config'] 			= $this->md_absensi_config->get();
            $this->load->view('index', $page_data);
        }else if ($param == "do_absen_dinas") {
            grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);
            $pengguna_id = sessPenggunaId();
			$page_data['switch']      		= $this->id_navbar();
            $page_data['data_absen']       	= $this->md_absensi->getAbsensiTodayById($pengguna_id);
            $page_data['data_absen_izin']  	= $this->md_absensi->getAbsensiIzinTodayById($pengguna_id);
            // echo_array($page_data);die;
            $page_data['page_name'] 		= 'v_do_absen_dinas';
            $page_data['page_title'] 		= 'Absen';
            $page_data['page_desc'] 		= 'Absensi Pagi Karyawan Dinas';
            $page_data['config'] 			= $this->md_absensi_config->get();
            $this->load->view('index', $page_data);
        }else if ($param == "izinfull") {
            grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);
            $pengguna_id = sessPenggunaId();
			$page_data['switch']      		= $this->id_navbar();
            $page_data['page_name'] 		= 'v_suratpimp';
            $page_data['page_title'] 		= 'Izin';
            $page_data['page_desc'] 		= 'Surat Izin Meninggalkan Pekerjaan';
            $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
            $page_data['pengguna']		= $this->md_pengguna->getIzinById(sessPenggunaId());
            $this->load->view('index', $page_data);
        }else if ($param == "izinjamkerja") {
            grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);
            $pengguna_id = sessPenggunaId();
			$page_data['switch']      		= $this->id_navbar();
            $page_data['page_name'] 		= 'v_suratpijk';
            $page_data['page_title'] 		= 'Izin';
            $page_data['page_desc'] 		= 'Izin Jam Kerja';
        	$page_data['pengguna']		= $this->md_pengguna->getIzinById(sessPenggunaId());
        	$this->load->view('index', $page_data);
        } else if ($param == "rekap_absensi") {
            grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);
			if(isKaryawan()){
				$page_data['switch']	= $this->id_navbar();
			}else{
				$page_data['switch']    = "kepegawaian";
			}
            $page_data['pengguna_id'] = $param2;
            $page_data['pengguna'] = $this->md_pengguna->getById(decrypt($param2));
            $page_data['page_name'] = 'v_rekap_absensi';
            $page_data['page_title'] = 'Rekap Absensi';
            $page_data['page_desc'] = 'List Rekap Absensi';
            $this->load->view('index', $page_data);
        } else if ($param == "latlong") {
            grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);
            if ($param2 == 'by_id') {
                $id = decrypt($this->input->post('id'));
                $dt = $this->md_absensi->getAbsenMasukById($id);
            } else {
                $pengguna_id = decrypt($this->input->post('pengguna_id'));
                $dt = $this->md_absensi->getAbsenMasukByPenggunaId($pengguna_id);
            }

            $data['latitude'] = $dt[0]->latitude ?? NULL;
            $data['longitude'] = $dt[0]->longitude ?? NULL;
            echo json_encode($data);
            die;
        }
    }

    public function rekap_absensi()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);
        $pengguna_id = decrypt($this->input->post('pengguna_id'));
        $dt    = $this->md_absensi->getRekapById($pengguna_id);
        $start = $this->input->post('start');
        $data  = array();

        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_absensi);
            $pengguna_id = encrypt($row->pengguna_id);

            $type_absen = $row->type_absen == 'masuk' ? '<span class="badge-success badge-pill">Absen Masuk</span>' : '<span class="badge-warning badge-pill">Absen Keluar</span>';
            if ($row->type_absen == 'masuk') {
                $type_absen = '<span class="badge-success badge-pill">Absen Masuk</span>';
            } elseif ($row->type_absen == 'keluar') {
                $type_absen = '<span class="badge-warning badge-pill">Absen Keluar</span>';
            } else {
                $type_absen = '<span class="badge-info badge-pill">' . ucwords($row->type_absen) . '</span>';
            }

            if ($row->status_absen) {
                if ($row->status_absen == "terlambat") {
                    $status_absen = '<span class="badge-danger badge-pill">Terlambat</span>';
                } else if ($row->status_absen == "tepat_waktu") {
                    $status_absen = '<span class="badge-primary badge-pill">Tepat Waktu</span>';
                } else if ($row->status_absen == "izin") {
                    $status_absen = '<span class="badge-warning badge-pill">Izin</span>';
                } else if ($row->status_absen == "cuti") {
                    $status_absen = '<span class="badge-warning badge-pill">Cuti</span>';
                } else {
                    $status_absen = '<span class="badge-warning badge-pill">Sakit</span>';
                }
            } else {
                $status_absen = '';
            }
            

            if (isAdmin() || isHrd() || isGa()) {
                if ($row->approval) {
                    if ($row->approval == 'tolak') {
                        $li_btn = '<span class="badge-danger badge-pill">Di Tolak</span>';
                    } else {
                        $li_btn = '<span class="badge-success badge-pill">Diterima</span>';
                    }
                } else {
                    $li_btn   = '
                        <div class="btn-group" role="group" aria-label="First group">
                            <button type="button" class="btn btn-sm btn-success btn-approval" pengguna-id="' . $pengguna_id . '" data-id="' . $id . '" approval="terima"><i class="fas fa-check"></i> Terima</button>&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-sm btn-danger btn-approval" pengguna-id="' . $pengguna_id . '"  data-id="' . $id . '" approval="tolak"><i class="fas fa-times"></i> Tolak</button>
                        </div>';
                }
            } else {
                if ($row->approval) {
                    if ($row->approval == 'tolak') {
                        $li_btn = '<span class="badge-danger badge-pill">Di Tolak</span>';
                    } else {
                        $li_btn = '<span class="badge-success badge-pill">Diterima</span>';
                    }
                } else {
                    $li_btn = '<span class="badge-warning badge-pill">Menunggu</span>';
                }
            }

            if ($row->tanpa_tunjangan == 1) {
                $tunjangan = '<span class="badge-danger badge-pill">Tidak</span>';
            } else {
                $tunjangan = '<span class="badge-success badge-pill">Ya</span>';
            }
            
            $jenis_absen = $row->jenis_absen == 'Kantor' ? '<span class="badge-success badge-pill">Absen Kantor</span>' : '<span class="badge-warning badge-pill">Absen Dinas</span>';
            if ($row->jenis_absen == 'Kantor') {
                $jenis_absen = '<span class="badge-success badge-pill">Absen Kantor</span>';
            } elseif ($row->jenis_absen == 'Dinas') {
                $jenis_absen = '<span class="badge-warning badge-pill">Absen Dinas</span>';
            } else {
                $jenis_absen = '<span class="badge-info badge-pill">' . ucwords($row->jenis_absen) . '</span>';
            }

            $btn_map   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-lihat-posisi" data-id="' . $id . '"><i class="fas fa-map-marked-alt"></i></button>&nbsp;&nbsp;&nbsp;
                </div>';
            $link_download = '<a href="' . $row->file_pendukung . '" target="blank"><i class="fas fa-download"></i> Download</a>';;

            $th = array();
            $th[] = ++$start . '.';
            $th[] = date_view_format($row->data_created);
            $th[] = $type_absen;
            $th[] = $row->waktu_absen;
            $th[] = $row->type_absen == 'keluar' ? '' : $status_absen;
            $th[] = $row->type_absen == 'keluar' ? '' : $btn_map;
            $th[] = $row->type_absen == 'keluar' ? '' : ($row->type_absen == 'masuk' || 'izin' ? $tunjangan : '');
            $th[] = $row->type_absen == 'keluar' ? '' : ($row->file_pendukung == '' ? '' : $link_download);
            $th[] = $row->keterangan;
            $th[] = $row->type_absen == 'keluar' ? '' : ($row->type_absen == 'masuk' || 'izin' ? $li_btn : '');
            $th[] = $row->jenis_absen;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function print($param = '', $param2 = '', $param3 = '')
    {
        if ($param == 'allKaryawanByMonth') {
            //$karyawan = $this->md_pengguna->getBywhere(['p.status' => 1, 'p.is_active' => 1, 'pengguna_id !=' => 1, 'p.status_print_absen !=' => 2]);
            $karyawan = $this->md_pengguna->getByWherenotIn(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator', 'pengguna_id !=' => 1, 'p.status_print_absen !=' => 2], [58, 47,84,714,77,79,110,87,72,70,81,69,83,107,86,74,57,738,721]);
            $data = [];
            foreach ($karyawan as $row) {
                $tmp = $this->md_absensi->countTerlambat($row->pengguna_id, $param2);
                $tmp1 = $this->md_absensi->countCuti($row->pengguna_id, $param2);
                $tmp2 = $this->md_absensi->countIzin($row->pengguna_id, $param2);
                $tmp3 = $this->md_absensi->countDinas($row->pengguna_id, $param2);
                $kehadiran = $this->md_absensi->getKehadiran($row->pengguna_id, $param2);

                $dt['total_kehadiran'] = count($kehadiran);
                $dt['total_terlambat'] = $tmp[0]->total_terlambat;
                $dt['total_cuti'] = $tmp1[0]->total;
                $dt['total_izin'] = $tmp2[0]->total;
                $dt['total_dinas'] = $tmp3[0]->total;
                $dt['nama'] = $row->nama;
                $dt['jabatan'] = $row->jabatan;
                $dt['no_pegawai'] = $row->no_pegawai;
                array_push($data, $dt);
            }
            $dta['month'] = $param2;
            $dta['absen'] = $data;
            // echo '<pre>'; print_r( $dta );die; echo '</pre>';
            // foreach($dta['absen'] as $row){
            //     echo '<pre>'; print_r( $row );die; echo '</pre>';
            // }
            $this->load->view('pages/v_print/print_absensi_month', $dta);
        } else {
            $idPengguna =  decrypt($param3);

            $karyawan = $this->md_pengguna->getById($idPengguna);
            $data = [];
            $tahun = date('Y', strtotime($param2)); //Mengambil tahun saat ini
            $bulan = date('m', strtotime($param2));

            //Mengambil bulan saat ini
            $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

            for ($i = 1; $i < $tanggal + 1; $i++) {
                if(strlen($i)==1){
                    $i = sprintf("%02d", $i);
                }
                $dt['pengguna_id'] = $idPengguna;
                $dt['date'] = $param2.'-'.$i; 
                $masuk = $this->md_absensi->getAbsensibyDays($idPengguna,$dt['date'],'masuk');
                $keluar = $this->md_absensi->getAbsensibyDays($idPengguna,$dt['date'],'keluar');
                $izin = $this->md_absensi->getAbsensibyDays($idPengguna,$dt['date'],'izin');
                $dt['jenis_absen'] = $masuk ? 'Absensi' :($izin ?'Izin':'');
                $dt['masuk'] = $masuk?$masuk[0]->waktu_absen:'';
                $dt['keluar'] = $keluar?$keluar[0]->waktu_absen:'';
                $dt['ket']  = $masuk ? $masuk[0]->status_absen :($izin?$izin[0]->keterangan:'');



                array_push($data, $dt);
            }
            
       

            $dta['pengguna'] = $karyawan;
            $dta['month'] = $param2;
            $dta['absen'] = $data;
            $dta['title_pdf'] = 'Rekap Absensi '. ucwords($karyawan[0]->nama).' '.$param2;
            // echo_array($dta);
            // die;
            
           $html= $this->load->view('pages/v_print/print_absensi_detail_month', $dta,TRUE);

               //load mpdf dan membuat page size legal
               $mpdf = new Mpdf(['format' => 'Legal']);
               //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
               $mpdf->AddPage('P');

               $mpdf->WriteHTML($html);
               $mpdf->Output($dta['title_pdf'] . '.pdf', 'I');
        }
    }

    public function pagination()
    {
        grantAccessFor(['Administrator', 'Hrd','Ga']);
        $dt    = $this->md_pengguna->getAllPenggunaAktif();
        $start = $this->input->post('start');
        $data  = array();
        // echo '<pre>'; print_r( $data );die; echo '</pre>';
        foreach ($dt['data'] as $row) {
            $id             = encrypt($row->pengguna_id);
            $data_absen     = $this->md_absensi->getAbsenMasukByPenggunaId($row->pengguna_id);
            
            $data_absen_izin= $this->md_absensi->getAbsenIzinByPenggunaId($row->pengguna_id);
            $nama_pengguna  = '<a href="absensi/show/rekap_absensi/' . $id . '")>' . $row->nama . '</a>';
            $status_absen='';
            if ($id==null){
                $btn_lokasi     = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-lihat-posisi" data-id="' . 00 . '"><i class="fas fa-map-marked-alt"></i></button>
                </div>';
            }else{
                $btn_lokasi     = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-lihat-posisi" data-id="' . $id . '"><i class="fas fa-map-marked-alt"></i></button>
                </div>';
            }
            $apr = '-';
            if (isset($data_absen[0]->status_absen)) {
                if ($data_absen[0]->status_absen == "terlambat") {
                    $status_absen = '<span class="badge-warning badge-pill">Terlambat</span>';
                } else if ($data_absen[0]->status_absen == "tepat_waktu") {
                    $status_absen = '<span class="badge-success badge-pill">Tepat Waktu</span>';
                }
                
                if ($data_absen[0]->approval == 'terima') {
                    $apr = '<span class="badge-success badge-pill">Diterima</span>';
                } else if ($data_absen[0]->approval == 'tolak'){
                    $apr = '<span class="badge-danger badge-pill">Ditolak</span>';
                } else {
					$apr = '<span class="badge-warning badge-pill">Belum Dicek</span>';
				}
            }else if (isset($data_absen_izin[0]->status_absen)){
                if ($data_absen_izin[0]->status_absen == "cuti") {
                    $status_absen = '<span class="badge-warning badge-pill">Cuti</span>';
                } else if ($data_absen_izin[0]->status_absen == "sakit"){
                    $status_absen = '<span class="badge-warning badge-pill">Sakit</span>';
                }
                
                $apr = '<span class="badge-danger badge-pill">Ditolak</span>';
            }else {
                $status_absen = '-';
            }

            $th = array();
            $th[] = ++$start . '.';
            $th[] = $nama_pengguna;
            $th[] = isset($data_absen[0]->waktu_absen) ? $data_absen[0]->waktu_absen : '-';
            $th[] = $status_absen;
            $th[] = $apr;
            $th[] = $btn_lokasi;
            $th[] =  'proses';
            $th[] = 'proses';
            $th[] = 'proses';
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
