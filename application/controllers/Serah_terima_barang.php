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

class Serah_terima_barang extends CI_Controller
{

  function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
       grantAccessFor('all');

        $page_data['switch']      	= $this->id_navbar();
        $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
		    $page_data['page_name']     = 'serah_terima_barang/v_stb';
        $page_data['page_title']    = 'Data Serah Terima Barang';
        $page_data['page_desc']     = 'Management Serah Terima Barang';
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
        $this->load->model('md_serah_terima_barang');
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
          if ($param2 == 'stb'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['data_po']		    = $this->md_serah_terima_barang->getSTBById($param3);
            $page_data['data_detail']		= $this->md_serah_terima_barang->getDetailSTBById($param3);
            $page_data['page_name']     = 'serah_terima_barang/v_detail_stb';
            $page_data['page_title']    = 'Serah Terima Barang';
            $page_data['page_desc']     = 'Detail Serah Terima Barang';
            $this->load->view('index', $page_data);
          }
        }else if($param == 'permintaan'){
          if($param2 == 'stb'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
            $page_data['list_cust']     = $this->md_serah_terima_barang->getAllCustomer();
            $page_data['page_name']     = 'serah_terima_barang/v_stb';
            $page_data['page_title']    = 'Serah Terima Barang';
            $page_data['page_desc']     = 'Daftar Pengajuan Serah Terima Barang';
            $this->load->view('index', $page_data);
          }
			  }else if($param == 'pengajuan'){
          if ($param2 == 'stb'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
            $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
            $page_data['list_cust']     = $this->md_serah_terima_barang->getAllCustomer();
            $page_data['page_name']     = 'serah_terima_barang/v_aju_stb';
            $page_data['page_title']    = 'Serah Terima Barang';
            $page_data['page_desc']     = 'Form Serah Terima Barang';
            $this->load->view('index', $page_data);
          }
			  }
          


    }


    //ADD
    public function add()
    {
        grantAccessFor('all');

            //menambah pengajuan PO
            $this->md_serah_terima_barang->reset_increment("surat_stb");
            $idFpp = $this->md_serah_terima_barang->getKodeId();
            $ambilId = $idFpp->id_stb;
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
            
            $kodeFpp = $kodeFpp."/SSTB/VYM/".$bulan."/".$tahun;
            $data['kode_stb']			= $kodeFpp;
            
            $data['id_pengaju']     = sessPenggunaId();
            $data['id_pihak1']      = $this->input->post('id_pihak1', TRUE);
            $data['id_customer']    = $this->input->post('id_customer', TRUE);
            $data['kota_pengajuan']	= $this->input->post('kota_aju', TRUE);
            $data['tgl_pengajuan']	= date_db_format($this->input->post('pengajuan', TRUE));
            $this->md_serah_terima_barang->addSTB($data);


           
            $lastGcId = $this->md_serah_terima_barang->getLastId();
            $lastGcId = $lastGcId->id_stb;
            
            //menambah detail po
            $this->md_purchase_order->reset_increment("surat_stb_detail");
            $itung = $this->input->post('itung', TRUE);
            $dataDetailGc['id_stb']			= $lastGcId;
            if($itung > 0){
              for($x=1;$x<$itung;$x++){
                $dataDetailGc['nomor']	  = $this->input->post('nomor['.$x.']', TRUE);
                $dataDetailGc['nama_barang']	  = $this->input->post('nama['.$x.']', TRUE);
                $dataDetailGc['merk']	    = $this->input->post('merk['.$x.']', TRUE);
                $dataDetailGc['no_batch']    	= $this->input->post('nobatch['.$x.']', TRUE);
                $dataDetailGc['qty']	    = $this->input->post('qty['.$x.']', TRUE);
                $dataDetailGc['satuan']   	= $this->input->post('satuan['.$x.']', TRUE);
                $dataDetailGc['ket']   	    = $this->input->post('ket['.$x.']', TRUE);
                $this->md_serah_terima_barang->addSTBdetail($dataDetailGc);
              }
          }

            
            

              
                      

            /** LOG */
            addLog('Pengajuan Serah Terima Barang', 'Permintaan Serah Terima Barang');
            ajaxReturnDie('success', 'Serah Terima Barang Berhasil Diajukan', TRUE);
                  


       


    }


   

    
    //DELETE
    public function delete($id)
    {
      grantAccessFor('all');

    	//$status = $this->input->post('status', TRUE);

    	$data = [
    		'status' =>"5",
    	];


    	$this->md_purchase_order->updatePO($id, $data);

        addLog('Menghapus Serah Terima Barang', 'Menghapus Serah Terima Barang');
        ajaxReturnDie('success', 'Serah Terima Barang berhasil dihapus', 'reload_table');
    }

    
   
    

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
// pagination -------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  
    public function pagination($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'permintaan_stb') {
            
          //if(sessPenggunaId()==1 || sessPenggunaId()==107 || sessPenggunaId()==72 || sessPenggunaId()==23 || sessPenggunaId()==33 || sessPenggunaId()==54){
            $dt     = $this->md_serah_terima_barang->getAllSTB();
          //}else{
          //  $dt     = $this->md_serah_terima_barang->getAllSTBbyID(sessPenggunaId());
         // }
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {
              
              
              $kode_stb	= '<a href="serah_terima_barang/show/detail/stb/'.$row->id_stb.'">'.$row->kode_stb.'</a>';
              //$cetak		= '<a href="surat/print_page/gc/'.$row->idGc.'">print</a>';
              $id = encrypt($row->id_stb);
              $li_btn   	= '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                   
                </div>';

                            
              
              $th = array();
              $th[] = ++$start;
              $th[] = $kode_stb;
              $th[] = $row->nama_pihak1;
              $th[] = $row->nama_customer;
              $th[] = date('d-M-Y',strtotime($row->tgl_Pengajuan));
              $th[] = $row->pengaju;
				      $th[] = $li_btn;
              $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;					
        
      }
    
    }


      public function update()
      {        
          grantAccessFor('all');
          $id = decrypt($this->input->post('id_stb'));
          $data['kode_stb']	= $this->input->post('kode', TRUE);
          $data['id_pihak1']	= $this->input->post('pihak_pertama', TRUE);
          $data['id_customer']	= $this->input->post('pihak_kedua', TRUE);
          $data['kota_pengajuan']	= $this->input->post('kota_pengajuan', TRUE);
          $data['tgl_pengajuan']	= date_db_format($this->input->post('tgl_pengajuan', TRUE));
        

          $this->md_serah_terima_barang->update($id, $data);

          /** LOG */
          addLog('Update Data', 'Memperbarui data STB "' . $data['id_stb'] . '"');
          ajaxReturnDie('success', 'Data STB berhasil diperbarui', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_serah_terima_barang->getById($id);
        foreach ($dt as $row) {
            $row->id_stb = encrypt($row->id_stb);
        }
        echo json_encode($dt);
        die;
    }

    
    public function editDetail($param1)
    {      
            grantAccessFor('all');
            $id = decrypt($param1);
            $dt = $this->md_serah_terima_barang->getByIdDetail($id);

            if(isset($dt[0]))
                echo json_encode($dt[0]);
            else
                ajaxReturnDie('error', 'error');
             die;
  
    }

    public function updateDetail()
    {        
       
         grantAccessFor('all');
        
             $id =  $this->input->post('id');
             $data['nomor'] = $this->input->post('nomor');
             $data['nama_barang'] = $this->input->post('namabarang');
             $data['merk']  = $this->input->post('merk');
             $data['no_batch']  = $this->input->post('nobatch');
             $data['qty']  = $this->input->post('qty');
             $data['satuan']  = $this->input->post('satuan');
             $data['ket']  = $this->input->post('ket');
       
        $this->md_serah_terima_barang->updateDetail($id, $data);

        /** LOG */
        addLog('Update STB', 'Memperbarui data STB "' . $data['nama_barang'] . '"');
        ajaxReturnDie('success', 'Data STB '. $data['nama_barang'].' berhasil diperbarui', 'reload_table');
	}

  public function deleteDetail()
  {
      $id = $this->input->post('id');
      if ($id) {
          $data = ['status' => 0]; 
          $this->md_serah_terima_barang->updateDetail($id, $data);
          echo json_encode(['status' => true, 'msg' => 'Data berhasil dihapus']);
      } else {
          echo json_encode(['status' => false, 'msg' => 'ID tidak ditemukan']);
      }


  }

  public function addDetail()
  {
      grantAccessFor('all');

      $data = [
          'id_stb'      => $this->input->post('id_stb', TRUE),
          'nomor' => $this->input->post('nomor', TRUE),
          'nama_barang' => $this->input->post('namabarang', TRUE),
          'merk'        => $this->input->post('merk', TRUE),
          'no_batch'    => $this->input->post('nobatch', TRUE),
          'qty'         => $this->input->post('qty', TRUE),
          'satuan'      => $this->input->post('satuan', TRUE),
          'ket'         => $this->input->post('ket', TRUE),
          'status'      => 1, // default aktif
      ];

      $this->md_serah_terima_barang->addSTBdetail($data);

      addLog('Tambah Data', 'Menambahkan detail STB: ' . $data['nama_barang']);
      ajaxReturnDie('success', 'Detail berhasil ditambahkan', 'reload_table');
  }







    
    
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
// print ------------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	public function print_page($param1="", $param2="")
  {
        grantAccessFor('all');

        if($param1 == 'stb'){

            $gc							= $this->md_serah_terima_barang->getSTBById($param2);
            
            $dt = [
              'title_pdf'	=> 'stb',
              'object'	=> $param1,
              'data_po'	=> $this->md_serah_terima_barang->getSTBById($param2),
              'data_detail'	=> $this->md_serah_terima_barang->getDetailSTBById($param2)
            ];
            
            //load mpdf dan membuat page size 
            $mpdf = new Mpdf(['format' => 'A4']);

            //$mpdf->SetMargins(0, 0, 0, true);

            //orientasi ketas 'L' untuk Landscape 'P' untuk Portait
	          $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');
              
            //filename dari pdf ketika didownload
            $file_pdf = 'Surat Serah Terima Barang  '.$gc[0]->nama_customer;

            //page html yang akan di jadikan ke pdf
            $html = $this->load->view('pages/v_print/print_stb', $dt, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output($file_pdf . '.pdf', 'I');


        }
              
 }


 




}