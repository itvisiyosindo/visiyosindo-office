<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Forecast extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_forecast');
        $this->load->model('md_kategori_barang');

        $this->load->model('md_stock');
        $this->load->model('md_history_barang');
        $this->load->model('md_barang');
        $this->load->model('md_pelanggan');
        $this->load->model('md_pengguna');
        $this->load->model('md_prov_kota');
        $this->load->model('md_kategori_tiket');
        $this->load->model('md_tiket');
        $this->load->helper('mandatory_helper');
        $this->load->helper('terbilang_helper');
        $this->load->helper('tanggal_helper');
        $this->load->helper('datetime_helper');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('encrypt_helper');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}


    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor('all');
        if($param == 'list'){  
            $page_data['switch']      	= $this->id_navbar();
		    $page_data['kategori_barang']  = $this->md_kategori_barang->getByWhere();
            $page_data['page_name']  	= 'forecast/v_forecast';
            $page_data['page_title']	= 'Forecast';
            $page_data['page_desc']  	= 'Master Data Forecast';
            $this->load->view('index', $page_data);
          
        }else if($param == 'detail'){
            $page_data['switch']      	= $this->id_navbar();
            $id_kategori = $this->input->get('id_kategori'); // ambil dari query string
            $kategori = $this->md_forecast->getKategoriById($id_kategori);

            $page_data['id_kategori']   = $id_kategori;
            $page_data['nama_kategori'] = $kategori ? $kategori->nama_kategori : '-';
            $page_data['data_forecast'] = $this->md_forecast->getAllTampil($id_kategori);
            $page_data['page_name']     = 'forecast/v_detail_forecast';
            $page_data['page_title']    = 'Forecast';
            $page_data['page_desc']     = 'Master Data Forecast';
            $this->load->view('index', $page_data);
        }else if ($param == 'detailOLD') {
            $page_data['switch']       = $this->id_navbar();
            $id_kategori = $this->input->get('id_kategori'); 
            $page_data['id_kategori']   = $id_kategori;

            // cek apakah forecast sudah ada untuk user + kategori + tahun
            $id_pengaju = sessPenggunaId();
            $tahun = date('Y');
            $forecast = $this->md_forecast->getForecastByKategori($id_kategori, $id_pengaju, $tahun);

            if ($forecast) {
                $page_data['id_forecast']   = $forecast->id;
                $page_data['data_forecast'] = $this->md_forecast->getDetailForecast($forecast->id);
                $page_data['is_update']     = true; // flag untuk view
            } else {
                $page_data['id_forecast']   = null;
                $page_data['data_forecast'] = $this->md_forecast->getAllTampil($id_kategori);
                $page_data['is_update']     = false;
            }

            $page_data['page_name']     = 'forecast/v_detail_forecast';
            $page_data['page_title']    = 'Forecast';
            $page_data['page_desc']     = 'Master Data Forecast';
            $this->load->view('index', $page_data);
        } else if ($param == 'detail_data') {
            $page_data['switch'] = $this->id_navbar();

            $page_data['forecast'] = $this->md_forecast->getById($param3);
            $forecast = $this->md_forecast->getForecastById($param3);

            if ($forecast) {
                $page_data['id_forecast']   = $forecast->id;
                $page_data['data_forecast'] = $this->md_forecast->getDetailForecast($forecast->id,$forecast->status);
                $page_data['is_update']     = true;
            } else {
                $this->session->set_flashdata('error', 'Data forecast tidak ditemukan atau belum dibuat.');
                redirect(base_url("forecast"));
            }

            $page_data['id_kategori'] = $param2;
            $kategori = $this->md_forecast->getKategoriById($param2);

            $page_data['nama_kategori'] = $kategori ? $kategori->nama_kategori : '-';
            $page_data['page_name']   = 'forecast/v_detail_forecast_isi'; // <- view khusus isi/update
            $page_data['page_title']  = 'Forecast';
            $page_data['page_desc']   = 'Data Forecast';
            $this->load->view('index', $page_data);
        }

    }

    public function save_detail()
    {
        $id_pengaju     = sessPenggunaId();
        $id_kategori    = $this->input->post('id_kategori'); 
        $tahun_sekarang = date('Y');
        $tahun_lalu     = $tahun_sekarang - 1;

        // generate kode forecast
        $idFpp      = $this->md_forecast->getKodeId();
        $ambilId    = $idFpp->id + 1;
        $kodeFpp    = str_pad($ambilId, 3, "0", STR_PAD_LEFT);

        $bulan = ambil_bulan();
        $tahun = ambil_tahun();
        $kodeFpp = $kodeFpp . "/FC/VYM/" . $bulan . "/" . $tahun;

        // insert header
        $data_header = [
            'kode'        => $kodeFpp,
            'id_pengaju'  => $id_pengaju,
            'id_kategori' => $id_kategori,
            'tahun_a'     => $tahun_lalu,
            'tahun_b'     => $tahun_sekarang,
            'status'      => 0,
            'created_at'  => date('Y-m-d H:i:s')
        ];
        $this->db->insert('forecast', $data_header);
        $id_forecast = $this->db->insert_id();

        // insert detail
        $details = $this->input->post('details');
        if (!empty($details)) {
            $batch_data = [];
            foreach ($details as $d) {
                $batch_data[] = [
                    'id_forecast' => $id_forecast,
                    'id_barang'   => $d['id_barang'],
                    'nama_barang' => $d['nama_barang'],
                    'satuan'      => $d['satuan'],
                    'terjual_a'   => $d['terjual_tahun_lalu'],
                    'terjual_b'   => $d['terjual_tahun_ini'],
                    'stok'        => $d['total_stok'],
                    'demo'        => $d['demo_stok'],
                    'customer'    => $d['barang_customer_stok'],
                    'permintaan'  => $d['permintaan'],
                    'keterangan'  => $d['keterangan'],
                    'status'      => 1
                ];
            }
            $this->db->insert_batch('forecast_detail', $batch_data);
        }

        echo json_encode([
            'status'      => 'success',
            'message'     => 'Data forecast berhasil disimpan',
            'id_forecast' => $id_forecast,
            'id_kategori' => $id_kategori
        ]);
    }

    public function update_detail()
    {
        $id_forecast = $this->input->post('id_forecast');
        $details     = $this->input->post('details');

        if (!$id_forecast || empty($details)) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak valid.']);
            return;
        }

        foreach ($details as $row) {
            $this->db->where('id_forecast', $id_forecast)
                    ->where('id_barang', $row['id_barang'])
                    ->update('forecast_detail', [
                        'permintaan' => $row['permintaan'],
                        'keterangan' => $row['keterangan']
                    ]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Draft berhasil diperbarui.']);
    }

    public function hapus_detail()
    {
        $id_forecast = $this->input->post('id_forecast');
        $id_barang   = $this->input->post('id_barang');

        if (!$id_forecast || !$id_barang) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak valid.']);
            return;
        }

        $this->db->where('id_forecast', $id_forecast)
                ->where('id_barang', $id_barang)
                ->update('forecast_detail', ['status' => 0]);

        echo json_encode(['status' => 'success', 'message' => 'Item berhasil dihapus (status=0).']);
    }







    public function save_detailOLD()
    {
        $id_pengaju = sessPenggunaId(); // misalnya ambil dari session
        $id_kategori = $this->input->post('id_kategori'); 
        $tahun_sekarang = date('Y');
        $tahun_lalu = $tahun_sekarang - 1;

        // 1. Insert ke header (forecast)

        $idFpp      = $this->md_forecast->getKodeId();
        $ambilId    = $idFpp->id + 1;
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
        $kodeFpp = $kodeFpp . "/FC/VYM/" . $bulan . "/" . $tahun;

        
        $data_header = [
            'kode'       => $kodeFpp,
            'id_pengaju' => $id_pengaju,
            'id_kategori' => $id_kategori,
            'tahun_a'    => $tahun_lalu,
            'tahun_b'    => $tahun_sekarang,
            'status'     => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('forecast', $data_header);
        $id_forecast = $this->db->insert_id();

        // 2. Ambil data detail dari form (AJAX)
        $details = $this->input->post('details');

        if (!empty($details)) {
            $batch_data = [];
            foreach ($details as $d) {
                $batch_data[] = [
                    'id_forecast' => $id_forecast,
                    'id_barang'   => $d['id_barang'],
                    'nama_barang' => $d['nama_barang'],
                    'satuan'      => $d['satuan'],
                    'terjual_a'   => $d['terjual_tahun_lalu'],
                    'terjual_b'   => $d['terjual_tahun_ini'],
                    'stok'        => $d['total_stok'],
                    'demo'        => $d['demo_stok'],
                    'customer'    => $d['barang_customer_stok'],
                    'permintaan'  => $d['permintaan'],
                    'keterangan'  => $d['keterangan'],
                    'status'      => 1
                ];
            }

            $this->db->insert_batch('forecast_detail', $batch_data);
        }

        echo json_encode(['status' => 'success', 'message' => 'Data forecast berhasil disimpan']);
    }




    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_forecast->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       	= encrypt($row->id);
            
            $kode = '<a href="'.base_url('forecast/show/detail_data/'.$row->id_kategori.'/'.$row->id).'">'.$row->kode.'</a>';




            if($row->status== "0"){
                $stat_surat = '<span class="badge badge-ecommerce badge-success">Draft</span>';
              }else if($row->status == "1"){
                $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
              }else if($row->status == "2"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Regulatory & Import</span>';
              }else if($row->status == "3"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Director of Corporate Planning & Business Management</span>';
              }else if($row->status == "4"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Director</span>';
              }else{
                 $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
              } 
                
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $kode;
            $th[] = $row->nama_kategori;
            $th[] = $row->nama;
            $th[] = $stat_surat;
            
            $data[] = $th;

        }
        
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function ajukan(){
    	grantAccessFor('all');
                      
          $id_sp = $this->input->post('id');
          $data['status'] = 1;
          $this->md_forecast->updateForecast($id_sp, $data);

          $ambilData 	= $this->md_forecast->getById($id_sp);
          $kode	        = $ambilData->kode;
          $id_kategori	= $ambilData->id_kategori;
          $kategori	    = $ambilData->nama_kategori;

                $urlNotif = "https://office.visiyosindo.id/forecast/show/detail_data/$id_kategori/$id_sp";  
                 

                $dataWa = [
                            'idPenerima1' 	=> '84',
                            'idPenerima2' 	=> '',
                            'namaSurat' 	=> 'Permintaan Forecast',
                            'urlNotif' 	    => $urlNotif,
                            'penerima' 	    => '_Regulatory and Import_',
                            'perihal' 	    => $kategori,
                            'kode' 	        => $kode
                          ];
                        
                    $this->notifWaAddSuratLink(1, $dataWa);
      
        addLog('Forecast', 'Mengajukan Forecast No: '.$kode	);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      

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
        
        $dataPenerima 	= $this->md_pengguna->getById($idpenerima);
        //abaikan error
      error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 0);
      //
        $nope           = $dataPenerima[0]->no_hp;
        $dataWa = [
            'namaSurat' 	=> urlencode($detail['namaSurat']),
            'urlNotif' 	  => urlencode($detail['urlNotif']),
            'noPenerima' 	=> $nope,
            'kodeSurat' 	=> $detail['kode'],
            'namaPengaju' => $namaPengaju,
            'perihal' 		=> urlencode($detail['perihal']),
            'namaPenerima' 	=> urlencode($detail['penerima'])
            ];
            waSuratOpenLink($dataWa);
      }
}


    public function ttd_setujui($param1="", $param2="", $param3=""){
    	grantAccessFor('all');

      if($param1 == "ttd_1"){
                      
          $id_sp = $this->input->post('id');
          $data['status'] = 2;
          $data['ttd_1'] = 1;
          $this->md_forecast->updateForecast($id_sp, $data);


                $ambilData 	= $this->md_forecast->getById($id_sp);
                $kode	        = $ambilData->kode;
                $id_kategori	= $ambilData->id_kategori;

                $urlNotif = "https://office.visiyosindo.id/forecast/show/detail_data/$id_kategori/$id_sp";  
                    
                    
                 

                    $dataWa = [
                            'id' 	        => $id_sp,
                            'idPenerima1' 	=> '23',
                            'idPenerima2' 	=> '',
                            'namaSurat' 	=> 'Permintaan Forecast',
                            'urlNotif' 	    => $urlNotif,
                            'penerima' 	    => 'Meilina Safitri',
                            'ttd_sebelum1' 	=> 'Regulatory and Import',
                            'ttd_sebelum2' 	=> '',
                            'ttd_sebelum3' 	=> '',
                            'ttd_sebelum4' 	=> ''
                        ];
                    
                    $this->notifWaAprovBa(1, 1, $dataWa);
      
               addLog('Forecast', 'Menyutujui Forecast No: '.$kode	);
               ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      
             }else if($param1 == "ttd_2"){

                $id_sp = $this->input->post('id');
                $data['status'] = 3;
                $data['ttd_2'] = 1;
                $this->md_forecast->updateForecast($id_sp, $data);


                $ambilData 	= $this->md_forecast->getById($id_sp);
                $kode	        = $ambilData->kode;
                $id_kategori	= $ambilData->id_kategori;

                $urlNotif = "https://office.visiyosindo.id/forecast/show/detail_data/$id_kategori/$id_sp";  
                    
                    
                 

                    $dataWa = [
                            'id' 	        => $id_sp,
                            'idPenerima1' 	=> '54',
                            'idPenerima2' 	=> '',
                            'namaSurat' 	=> 'Permintaan Forecast',
                            'urlNotif' 	    => $urlNotif,
                            'penerima' 	    => 'Director',
                            'ttd_sebelum1' 	=> 'Regulatory and Import',
                            'ttd_sebelum2' 	=> 'Director of Corporate Planning and Business Management',
                            'ttd_sebelum3' 	=> '',
                            'ttd_sebelum4' 	=> ''
                        ];
                    
                    $this->notifWaAprovBa(1, 1, $dataWa);
      
               addLog('Forecast', 'Menyutujui Forecast No: '.$kode	);
               ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      
        }else if($param1 == "ttd_3"){

                $id_sp = $this->input->post('id');
                $data['status'] = 4;
                $data['ttd_3'] = 1;
                $this->md_forecast->updateForecast($id_sp, $data);


                $ambilData 	= $this->md_forecast->getById($id_sp);
                $kode	        = $ambilData->kode;
                $id_kategori	= $ambilData->id_kategori;

                $urlNotif = "https://office.visiyosindo.id/forecast/show/detail_data/$id_kategori/$id_sp";  
                          
                          
                      

                          $dataWa = [
                                  'id' 	          => $id_sp,
                                  'idPenerima1' 	=> '84',
                                  'idPenerima2' 	=> '',
                                  'namaSurat' 	    => 'Permintaan Forecast',
                                  'urlNotif' 	    => $urlNotif,
                                  'penerima' 	    => '',
                                  'ttd_sebelum1' 	=> 'Regulatory and Import',
                                  'ttd_sebelum2' 	=> 'Director of Corporate Planning and Business Management',
                                  'ttd_sebelum3' 	=> 'Director',
                                  'ttd_sebelum4' 	=> ''
                              ];
                          
                          $this->notifWaAprovBa(1, 2, $dataWa);


                    //send notif Group wa
                    $dataGroup = [
                        'id' 	          => $id_sp,
                        'idPenerima1' 	=> 'Gudang PT. VYM',
                        //'idPenerima1' 	=> 'Test Api Wa Group',
                        'idPenerima2' 	=> '',
                        'namaSurat' 	=> 'Permintaan Forecast',
                        'penerima' 	    => '',
                        'ttd_sebelum1' 	=> 'Regulatory and Import',
                        'ttd_sebelum2' 	=> 'Director of Corporate Planning and Business Management',
                        'ttd_sebelum3' 	=> 'Director',
                        'ttd_sebelum4' 	=> ''
                  ];
              
                  $this->notifWaAprovGroup(1, 1, $dataGroup);
      
               addLog('Forecast', 'Menyutujui Forecast No: '.$kode	);
               ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      
        }

}


    public function notifWaAprovGroup($ulang, $param, $detail){
        //ambil data pengaju

        $ambilData 	    = $this->md_forecast->getById($detail['id']);
        $kode	        = $ambilData->kode;
        $perihal	    = $ambilData->nama_kategori;
        $namaPengaju    = $ambilData->nama;
        $idpengaju      = $ambilData->id_pengaju;
        
        
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
                
            $dataWa = [
                'namaSurat' 	=> $detail['namaSurat'],
                'noPenerima'  	=> $idpenerima,
                'kodePO' 	    => $kode,
                'namaPengaju' 	=> urlencode($namaPengaju),
                'suplier'   	=> urlencode($perihal),
                'namaPenerima' 	=> urlencode($detail['penerima']),
                'noPo' 	        => $detail['noPo'],
                'ttd_sebelum1' 	=> urlencode($detail['ttd_sebelum1']),
                'ttd_sebelum2' 	=> urlencode($detail['ttd_sebelum2']),
                'ttd_sebelum3' 	=> urlencode($detail['ttd_sebelum3']),
                'ttd_sebelum4' 	=> urlencode($detail['ttd_sebelum4'])
            ];
            
            if($param == '1'){
                waForecastGroup($dataWa);
            }else if($param == '3'){
                waPoAprovAllGroupAdm($dataWa);
            }else if($param == '2'){
                waPoAprovAll($dataWa);
            }
        }
        }



public function notifWaAprovBa($ulang, $param, $detail){

      $ambilData 	= $this->md_forecast->getById($detail['id']);
      $kode	        = $ambilData->kode;
      $perihal	    = $ambilData->nama_kategori;
      $namaPengaju  = $ambilData->nama;
      $idpengaju    = $ambilData->id_pengaju;

      
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
              'namaSurat' 	    => urlencode($detail['namaSurat']),
              'urlNotif' 	    => urlencode($detail['urlNotif']),
              'noPenerima'  	=> $nope,
              'kodeSurat' 	    => $kode,
              'namaPengaju' 	=> urlencode($namaPengaju),
              'namaPenerima' 	=> urlencode($detail['penerima']),
              'perihal' 		=> urlencode($perihal),
              'ttd_sebelum1' 	=> urlencode($detail['ttd_sebelum1']),
              'ttd_sebelum2' 	=> urlencode($detail['ttd_sebelum2']),
              'ttd_sebelum3' 	=> urlencode($detail['ttd_sebelum3']),
              'ttd_sebelum4' 	=> urlencode($detail['ttd_sebelum4'])
          ];
          
          if($param == '1'){
            waSuratAprovOnProgVisilab($dataWa);
          }else if($param == '2'){
              waSuratAprovAllVisilab($dataWa);
          }
      }
    }


public function ttd_tolak($param1="", $param2="", $param3=""){
    	grantAccessFor('all');

          if($param1 == "ttd_1"){
                          
                $id_sp = $this->input->post('id');
                $data['status'] = 5;
                $data['ttd_1'] = 2;
                $this->md_forecast->updateForecast($id_sp, $data);
                          
    
                      //send notif wa
                        $dataWa = [
                            'namaSurat'     => 'Permintaan Forecast',
                                  'id' 	    => $id_sp,
                            'idPenolak'     => '84',
                            'namaPenolak' 	=> 'Regulatory and import'
                              ];
                              $this->notifWaRejectBa($dataWa);
                  /** LOG */
                  addLog('Forecast', 'Menolak');
                  ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    
          }else if($param1 == "ttd_2"){
                          
                $id_sp = $this->input->post('id');
                $data['status'] = 6;
                $data['ttd_2'] = 2;
                $this->md_forecast->updateForecast($id_sp, $data);
                          
    
                      //send notif wa
                        $dataWa = [
                            'namaSurat'     => 'Permintaan Forecast',
                                  'id' 	    => $id_sp,
                            'idPenolak'     => '23',
                            'namaPenolak' 	=> 'Director of Corporate Planning and Business Management'
                              ];
                              $this->notifWaRejectBa($dataWa);
                  /** LOG */
                  addLog('Forecast', 'Menolak');
                  ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    
          }else if($param1 == "ttd_3"){
                          
                $id_sp = $this->input->post('id');
                $data['status'] = 7;
                $data['ttd_3'] = 2;
                $this->md_forecast->updateForecast($id_sp, $data);
                          
    
                      //send notif wa
                        $dataWa = [
                            'namaSurat'     => 'Permintaan Forecast',
                                  'id' 	    => $id_sp,
                            'idPenolak'     => '54',
                            'namaPenolak' 	=> 'Director'
                              ];
                              $this->notifWaRejectBa($dataWa);
                  /** LOG */
                  addLog('Forecast', 'Menolakk');
                  ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    
          }
    
}  


public function notifWaRejectBa($detail){
  

      $ambilData 	= $this->md_forecast->getById($detail['id']);
      $kode	        = $ambilData->kode;
      $perihal	    = $ambilData->nama_kategori;
      $namaPengaju  = $ambilData->nama;
      $idpengaju    = $ambilData->id_pengaju;
      
      //ambil nomor
      $dataPenerima 	= $this->md_pengguna->getById($idpengaju);
      $nope           = $dataPenerima[0]->no_hp;
      $dataPenolak 	  = $this->md_pengguna->getById($detail['idPenolak']);
      $nopePenolak    = $dataPenolak[0]->no_hp;

      $perihal = '';
                  
      //send notif wa
      $dataWa = [
          'namaSurat' 	=> $detail['namaSurat'],
          'noPenerima' 	=> $nope,
          'kodeSurat' 	=> $kode,
          'perihal' 	=> urlencode($perihal),
          'namaPengaju' => $namaPengaju,
          'namaPenolak' => $detail['namaPenolak'],
          'noPenolak' 	=> $nopePenolak
      ];
      waSuratReject($dataWa);
    }


    public function print_page($param1="", $param2="")
    {
            grantAccessFor('all');

                $gc							= $this->md_forecast->getById($param1);
                
                $dt = [
                'title_pdf'	=> 'fc',
                'object'	=> 'forecast',
                'forecast'	=> $this->md_forecast->getById($param1),
                'data_forecast'	=> $this->md_forecast->getDetailForecast($param1, $gc->status)
                ];
                
                //load mpdf dan membuat page size 
                $mpdf = new Mpdf(['format' => 'A4']);

            // $mpdf->SetMargins(0, 0, 0, true);

                //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
                $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');
                
                // filename dari pdf ketika didownload
                $file_pdf = 'Rencana Forecast  '.$gc->nama;

                // page htmk yang akan di jadikan ke pdf
                
                $html = $this->load->view('pages/v_print/print_forecast', $dt, true);
              
                $mpdf->WriteHTML($html);
                $mpdf->Output($file_pdf . '.pdf', 'I');


            
                
    }

   





}
