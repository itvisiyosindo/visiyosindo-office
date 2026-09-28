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

class Inventory_new extends CI_Controller
{

  function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    


    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_inventory_new');
        $this->load->model('md_surat_new');
        $this->load->model('md_pengguna');
		    $this->load->model('md_pelanggan');
        $this->load->model('md_surat_list');
        $this->load->model('md_surat_part_two');
        $this->load->model('md_surat_list');
        $this->load->model('md_prov_kota');
        $this->load->helper('terbilang_helper');
        $this->load->helper('tanggal_helper');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('encrypt_helper');
        
    }

    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor('all');
        if($param == 'detail'){  
          if ($param2 == 'po_pending'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['data_po']	  	  = $this->md_inventory_new->getDetailPoById(decrypt($param3));
            $page_data['data_status']   = $this->md_inventory_new->getUpdateById(['t.id_po' => decrypt($param3)]);
            $page_data['page_name']     = 'inventory/v_detail_po_pending';
            $page_data['page_title']    = 'PO Pending';
            $page_data['page_desc']     = 'Detail PO Pending';
            $this->load->view('index', $page_data);
          }
        }else if($param == 'list'){
          if($param2 == 'po_pending'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['page_name']     = 'inventory/v_po_pending';
            $page_data['page_title']    = 'PO Pending';
            $page_data['page_desc']     = 'Daftar PO Pending';
            $this->load->view('index', $page_data);
          }
			  }else if($param == 'pengajuan'){
          if ($param2 == 'po_pending'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
		        //$page_data['list_custA']     = $this->md_pelanggan->getByWhere(['p.status' => 1]);
            $page_data['list_cust']     = $this->md_inventory_new->getAllCustomer();
				    $page_data['nama_marketing']  = $this->md_pengguna->getPenggunaMarketing();
            $page_data['page_name']     = 'inventory/v_aju_po_pending';
            $page_data['page_title']    = 'PO Pending';
            $page_data['page_desc']     = 'Form PO Pending';
            $this->load->view('index', $page_data);
          }
			  }
          


    }


    //ADD
    public function addSrt($param = "")
    {
          grantAccessFor('all');

          if($param=="po_pending"){
              
              $data['id_pengaju']    = sessPenggunaId();
              $data['tanggal_po']       = date_db_format($this->input->post('tanggal_po', TRUE));
              $data['id_customer']  = $this->input->post('id_pelanggan', TRUE);
              $data['nama_customer']  = $this->input->post('nama_pelanggan', TRUE);
              $data['id_marketing']  = $this->input->post('id_marketing', TRUE);
              $data['nama_marketing']	  = $this->input->post('nama_marketing', TRUE);
              $data['item1']         = $this->input->post('item1', TRUE);
              $data['item2']         = $this->input->post('item2', TRUE);
              $data['item3']	       = $this->input->post('item3', TRUE);
              $data['item4']	       = $this->input->post('item4', TRUE);
              $data['item5']	       = $this->input->post('item5', TRUE);
              $data['item6']	       = $this->input->post('item6', TRUE);
              $data['item7']	       = $this->input->post('item7', TRUE);
              $data['sistem_pembayaran']  = $this->input->post('sistem_pembayaran', TRUE);
              $data['status']        = 1;
              $this->md_inventory_new->addPo($data);



              $lastGcId = $this->md_inventory_new->getTrackLastId();
              $lastGcId = $lastGcId->id;

              $this->md_inventory_new->reset_increment("po_pending_status");
              $dtStatus['id_po']	= $lastGcId;
              $dtStatus['id_pengguna']   = sessPenggunaId();

              $dtStatus['id_status']	   = $this->input->post('id_status', TRUE);
              $dtStatus['remarks']	     = $this->input->post('remarks', TRUE);
              $this->md_inventory_new->addPoStatus($dtStatus);
              
              $status     = $this->input->post('id_status', TRUE);
                if($status==1){
                    $statusTracking = 'Pending';
                }else if($status==2){
                    $statusTracking = 'Batal';
                }else if($status==3){
                    $statusTracking = 'Barang Dalam Pemesanan';
                }else if($status==4){
                    $statusTracking = 'Barang Ready';
                }else if($status==5){
                    $statusTracking = 'Menunggu Konfirmasi Customer';
                }else if($status==6){
                    $statusTracking = 'Dikirim';
                }


              

                $idTracking         = encrypt($dtStatus['id_po']);
                    

                    
                    $dataWa = [
                      //'idPenerima1' 	=> 'Test Api Wa Group',
                      //'idPenerima2' 	=> 'Test2',
                      'idPenerima1' 	=> 'MARKETING PT. VYM',
                      'idPenerima2' 	=> 'Gudang PT. VYM',
                      'namaSurat'  	  => 'PO Pending',
                      'statusSurat' 	=> 'Update Status',
                      'statusTracking' 	=> $statusTracking,
                      'status' 	        => 'memperbarui status',
                      'marketing' 	    => $data['nama_marketing'],
                      'idTracking' 	    => $idTracking,
                      'csname' 	        => $data['nama_customer']
                  ];
                  $this->notifWaAppGudangGroup(2, $dataWa);
 

              /** LOG */
              addLog('PO Pending', 'Input PO Pending');
              ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
            

          }



      }


      public function update()
      {
          grantAccessFor('all');
          $dataDetailGc['id_po']           = $this->input->post('id_po');
          $dataDetailGc['id_status']       = $this->input->post('status');
          $dataDetailGc['remarks']         = $this->input->post('keterangan_konfirmasi');
          $dataDetailGc['id_pengguna']     = sessPenggunaId();
            
            
            
            
            $this->md_inventory_new->addPoStatus($dataDetailGc);

            $ambilDataTracking 	= $this->md_inventory_new->getById($dataDetailGc['id_po']);
            $namacs	            = $ambilDataTracking[0]->nama_customer;
            $marketing	        = $ambilDataTracking[0]->nama_marketing;

            $status     = $this->input->post('status', TRUE);
                if($status==1){
                    $statusTracking = 'Pending';
                }else if($status==2){
                    $statusTracking = 'Batal';
                }else if($status==3){
                    $statusTracking = 'Barang Dalam Pemesanan';
                }else if($status==4){
                    $statusTracking = 'Barang Ready';
                }else if($status==5){
                    $statusTracking = 'Menunggu Konfirmasi Customer';
                }else if($status==6){
                    $statusTracking = 'Dikirim';
                }


              

                $idTracking         = encrypt($dataDetailGc['id_po']);
                    

                    
                    $dataWa = [
                      //'idPenerima1' 	=> 'Test Api Wa Group',
                      //'idPenerima2' 	=> 'Test2',
                      'idPenerima1' 	=> 'MARKETING PT. VYM',
                      'idPenerima2' 	=> 'Gudang PT. VYM',
                      'namaSurat'  	  => 'PO Pending',
                      'statusSurat' 	=> 'Update Status',
                      'statusTracking' 	=> $statusTracking,
                      'status' 	        => 'memperbarui status',
                      'marketing' 	    => $marketing,
                      'idTracking' 	    => $idTracking,
                      'csname' 	        => $namacs
                  ];
                  $this->notifWaAppGudangGroup(2, $dataWa);

            addLog('PO Pending', 'Memperbaharui Status PO Pending');
            ajaxReturnDie('success', 'Status berhasil diperbaharui', TRUE);
			
      }


      public function delete($param)
      {
          grantAccessFor('all');

          
          $id_tracking    = decrypt($param);
          $data['status'] = 0;
          $this->md_inventory_new->update(['id' => $id_tracking], $data);

          //add log
          $aksi = 'Hapus Master Data';
          $ket = 'Menghapus data PO Pending';
          addlog($aksi, $ket);

          ajaxReturnDie('success', 'Data berhasil dihapus', 'reload_table');
      }


    

    

    

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
// pagination -------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  
    public function pagination($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'po_pending') {
        $dt    = $this->md_inventory_new->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id);
            
            
            if(sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 33 || sessPenggunaId() == 7 || sessPenggunaId()==73 || sessPenggunaId()==748 || sessPenggunaId()==72) {
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <a href="inventory_new/show/detail/po_pending/' . $id . '" class="btn btn-sm btn-primary btn-edit">
                        <i class="bx bx-pencil"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="inventory_new/delete"><i class="bx bx-trash"></i></button>
                </div>';
            }else{
                $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <a href="inventory_new/show/detail/po_pending' . $id . '" class="btn btn-sm btn-primary btn-edit">
                        <i class="bx bx-pencil"></i>
                    </a>
                </div>';
            }

            if($row->id_status == "1"){
              $stat = '<span class="badge badge-ecommerce badge-info">Pending</span>';
            }else if($row->id_status == "2"){
              $stat = '<span class="badge badge-ecommerce badge-success">Batal</span>';
            }else if($row->id_status == "3"){
              $stat = '<span class="badge badge-ecommerce badge-success">Barang Dalam Pemesanan</span>';
            }else if($row->id_status == "4"){
              $stat = '<span class="badge badge-ecommerce badge-success">Barang Ready</span>';
            }else if($row->id_status == "5"){
              $stat = '<span class="badge badge-ecommerce badge-success">Menunggu Konfirmasi Customer</span>';
            }else if($row->id_status == "6"){
              $stat = '<span class="badge badge-ecommerce badge-success">Dikirim</span>';
            }

            if($row->item2 == ""){
                $item = '- '.$row->item1;
            }else if($row->item3 == ""){
                $item = '- '.$row->item1.'<br>- '.$row->item2;
            }else if($row->item4 == ""){
                $item = '- '.$row->item1.'<br>- '.$row->item2.'<br>- '.$row->item3;
            }else if($row->item5 == ""){
                $item = '- '.$row->item1.'<br>- '.$row->item2.'<br>- '.$row->item3.'<br>- '.$row->item4;
            }else if($row->item6 == ""){
                $item = '- '.$row->item1.'<br>- '.$row->item2.'<br>- '.$row->item3.'<br>- '.$row->item4.'<br>- '.$row->item5;
            }else if($row->item7 == ""){
                $item = '- '.$row->item1.'<br>- '.$row->item2.'<br>- '.$row->item3.'<br>- '.$row->item4.'<br>- '.$row->item5.'<br>- '.$row->item6;
            }else{
                $item = '- '.$row->item1.'<br>- '.$row->item2.'<br>- '.$row->item3.'<br>- '.$row->item4.'<br>- '.$row->item5.'<br>- '.$row->item6.'<br>- '.$row->item7;
            }
            
                  
                  $th = array();
                  $th[] = ++$start . '.';
                  $th[] = $row->nama_customer;
                  $th[] = $row->nama_marketing;
                  $th[] = $item;
                  $th[] = date('d-M-Y',strtotime($row->tanggal_po));
                  $th[] = $row->sistem_pembayaran;
                  $th[] = $stat;
                  $th[] = $row->remarks;
                  $th[] = $li_btn;
                  $data[] = $th;
              }
              $dt['data'] = $data;
              echo json_encode($dt);
              die;
      }
  
  
  }


   public function notifWaAppGudangGroup($ulang, $detail){
          //ambil data pengaju
          $ambilDataPengaju 	= $this->md_pengguna->getById(sessPenggunaId());
          $namaPengaju		    = $ambilDataPengaju[0]->nama;

    
          
          for($i=1; $i<=$ulang; $i++){
              if($i == 1){
                  $idpenerima = $detail['idPenerima1'];
                  $penerima   = '_Team Marketing_';
              }else if($i == 2){
                  $idpenerima = $detail['idPenerima2'];
                  $penerima   = '_Team Warehouse_';
              }
              
           
            //abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);
            //
             
                $url = 'https://office.visiyosindo.id/inventory_new/show/detail/po_pending/';

              $dataWa = [
                'namaSurat' 	    => urlencode($detail['namaSurat']),
                'noPenerima' 	    => $idpenerima,
                'namaPengaju'     => $namaPengaju,
                'csname' 		      => urlencode($detail['csname']),
                'marketing' 	    => urlencode($detail['marketing']),
                'statusTracking'  => $detail['statusTracking'],
                'statusSurat'     => $detail['statusSurat'],
                'status' 	        => $detail['status'],
                'url' 	          => $url,
                'idTracking' 	    => $detail['idTracking'],
                'namaPenerima'	  => $penerima
                  ];
             
             waAppGroupGudangPo($dataWa);
              
          }
      }


    
    



 




}