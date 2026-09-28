<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Voting extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Md_pengguna');
    }
	
	function id_navbar(){
		$id_navbar = "kepegawaian";
		return $id_navbar;
	}

    public function index()
    {
       grantAccessFor('all');

        $page_data['switch']      	= $this->id_navbar();
		$page_data['page_name']		= 'voting/v_voting';
        $page_data['page_title']    = 'Voting';
        $page_data['page_desc']     = 'Voting';
        $page_data['idpengguna']     = sessPenggunaId();
        $page_data['totalvoting']  = $this->Md_pengguna->getTotalVoting();
        $this->load->view('index', $page_data);
    }

   public function add()
    {
        grantAccessFor('all');
        $tahun = date("Y");
        $data['pengguna_id'] =sessPenggunaId();
        $data['tahun'] =$tahun;
        
        $this->Md_pengguna->resetVoting(sessPenggunaId());
        $this->Md_pengguna->addVoting($data);

        ajaxReturnDie('success', 'Anda sudah bisa Voting..!!', 'reload_table');
    }
    
     public function updatedicipline($param)
     {
        grantAccessFor('all');
        $tahun = date("Y");
        $data['pengguna_id'] =sessPenggunaId();
        $data['dicipline'] =decrypt(str_replace("dicipline","",$param));
        
        $this->Md_pengguna->updateVoting(sessPenggunaId(),$data);

        ajaxReturnDie('success', 'Votingan Dicipline Anda berhasil..!!', 'reload_table');
     }
     
      public function updateprofesional($param)
     {
        grantAccessFor('all');
        $tahun = date("Y");
        $data['pengguna_id'] =sessPenggunaId();
        $data['profesional'] = decrypt(str_replace("profesional","",$param));
        
        $this->Md_pengguna->updateVoting(sessPenggunaId(),$data);

        ajaxReturnDie('success', 'Votingan Profesional Anda berhasil..!!', 'reload_table');
     }
     
      public function updatecreative($param)
     {
        grantAccessFor('all');
        $tahun = date("Y");
        $data['pengguna_id'] =sessPenggunaId();
        $data['creative'] = decrypt(str_replace("creative","",$param));
        
        $this->Md_pengguna->updateVoting(sessPenggunaId(),$data);

        ajaxReturnDie('success', 'Votingan Creative Anda berhasil..!!', 'reload_table');
     }
      public function updatesales($param)
     {
        grantAccessFor('all');
        $tahun = date("Y");
        $data['pengguna_id'] =sessPenggunaId();
        $data['sales'] = decrypt(str_replace("sales","",$param));
        
        $this->Md_pengguna->updateVoting(sessPenggunaId(),$data);

        ajaxReturnDie('success', 'Votingan Sales Anda berhasil..!!', 'reload_table');
     }
      public function updateoperational($param)
     {
        grantAccessFor('all');
        $tahun = date("Y");
        $data['pengguna_id'] =sessPenggunaId();
        $data['operational'] = decrypt(str_replace("operational","",$param));
        
        $this->Md_pengguna->updateVoting(sessPenggunaId(),$data);

        ajaxReturnDie('success', 'Votingan Operational Anda berhasil..!!', 'reload_table');
     }
     public function updatebestofyear($param)
     {
        grantAccessFor('all');
        $tahun = date("Y");
        $data['pengguna_id'] =sessPenggunaId();
        $data['bestofyear'] = decrypt(str_replace("bestofyear","",$param));
        
        $this->Md_pengguna->updateVoting(sessPenggunaId(),$data);

        ajaxReturnDie('success', 'Votingan Best Of Year Anda berhasil..!!', 'reload_table');
     }

    public function pagination($param)
    {
        grantAccessFor('all');
        
        if($param==1){
            $dt    = $this->Md_pengguna->getAllPenggunaVoting();            
        }else{
             $dt    = $this->Md_pengguna->getAllPenggunaVotingKosong(sessPenggunaId());   
        }


        $start = $this->input->post('start');
       
        $data  = array();
       
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->pengguna_id);
            $th = array();

            if($param==0){
                $th[] = ++$start . '.';
                 $li_btn1   = '
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-primary btn-print" data-id="' . $id . '"  style="width:150px; text-align: left;"><i class="fas fa-calendar-check"></i>&nbsp;'.$row->dicipline.'</button> 
                    </div>';
                  
                  $li_btn2   = '
                    <div class="btn-group" role="group" aria-label="First group">
                       <button type="button" class="btn btn-sm btn-success btn-edit" data-id="' . $id . '" style="width:150px; text-align: left;"><i class="fas fa-certificate"></i>&nbsp;'.$row->profesional.'</button>
                    </div>';
                  $li_btn3   = '
                    <div class="btn-group" role="group" aria-label="First group">
                       <button type="button" class="btn btn-sm btn-warning btn-edit" data-id="' . $id . '" style="width:150px; text-align: left;"><i class="fas fa-smile-wink"></i>&nbsp;'.$row->creative.'</button>
                    </div>'; 
                 $li_btn4   = '
                    <div class="btn-group" role="group" aria-label="First group">
                       <button type="button" class="btn btn-sm btn-danger btn-edit" data-id="' . $id . '" style="width:150px; text-align: left;"><i class="fas fa-poll"></i>&nbsp;'.$row->sales.'</button>
                    </div>';
                $li_btn5   = '
                    <div cass="bt5-group" role="group" aria-label="First group">
                       <button type="button" class="btn btn-sm btn-dark btn-edit" data-id="' . $id . '" style="width:150px; text-align: left;"><i class="fas fa-thumbs-up"></i>&nbsp;'.$row->operational.'</button>
                    </div>';     
                $li_btn6   = '
                    <div cass="bt5-group" role="group" aria-label="First group">
                       <button type="button" class="btn btn-sm btn-info btn-edit" data-id="' . $id . '" style="width:150px; text-align: left;"><i class="fas fa-star"></i>&nbsp;'.$row->bestofyear.'</button>
                     </div>';       
                $th[] =  $li_btn1;
                $th[] =  $li_btn2;
                $th[] =  $li_btn3;
                $th[] =  $li_btn4;
                $th[] =  $li_btn5;
                $th[] =  $li_btn6;
                $data[] = $th;
            }else{
                if($row->pengguna_id != sessPenggunaId()){
                    $th[] = ++$start . '.';
                    $li_btn1   = '
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="dicipline' . $id . '"  style="width:150px; text-align: left;"><i class="fas fa-calendar-check"></i>&nbsp;'.$row->nama.'</button> 
                    </div>';
                  
                  $li_btn2   = '
                    <div class="btn-group" role="group" aria-label="First group">
                       <button type="button" class="btn btn-sm btn-success btn-edit" data-id="profesional' . $id . '" style="width:150px; text-align: left;"><i class="fas fa-certificate"></i>&nbsp;'.$row->nama.'</button>
                    </div>';
                  $li_btn3   = '
                    <div class="btn-group" role="group" aria-label="First group">
                       <button type="button" class="btn btn-sm btn-warning btn-edit" data-id="creative' . $id . '" style="width:150px; text-align: left;"><i class="fas fa-smile-wink"></i>&nbsp;'.$row->nama.'</button>
                    </div>'; 
                 $li_btn4   = '
                    <div class="btn-group" role="group" aria-label="First group">
                       <button type="button" class="btn btn-sm btn-danger btn-edit" data-id="sales' . $id . '" style="width:150px; text-align: left;"><i class="fas fa-poll"></i>&nbsp;'.$row->nama.'</button>
                    </div>';
                $li_btn5   = '
                    <div cass="bt5-group" role="group" aria-label="First group">
                       <button type="button" class="btn btn-sm btn-dark btn-edit" data-id="operational' . $id . '" style="width:150px; text-align: left;"><i class="fas fa-thumbs-up"></i>&nbsp;'.$row->nama.'</button>
                    </div>';     
                $li_btn6   = '
                    <div cass="bt5-group" role="group" aria-label="First group">
                       <button type="button" class="btn btn-sm btn-info btn-edit" data-id="bestofyear' . $id . '" style="width:150px; text-align: left;"><i class="fas fa-star"></i>&nbsp;'.$row->nama.'</button>
                     </div>';       
                $th[] =  $li_btn1;
                $th[] =  $li_btn2;
                $th[] =  $li_btn3;
                $th[] =  $li_btn4;
                $th[] =  $li_btn5;
                $th[] =  $li_btn6;
                $data[] = $th;
                }
            }
        }
         $dt['data'] = $data;
        
           echo json_encode($dt);

        die;
            
    }
    
     public function paginationhasilvotingdicipline()
    {
        if(isAdmin() || sessPenggunaId()=='29'){
            $dt    = $this->Md_pengguna->getAllHasilVotingDicipline();
            $data  = array();
            $index = 1;
            foreach ($dt['data'] as $row) {
                $th = array();
                $th[] = $index;
                $th[] = $row->nama;
                $th[] = $row->total;
                $data[] = $th;
                $index++;
            }
            
            $dt['data'] = $data;
            echo json_encode($dt);
            die;  
        }
           
    }
     public function paginationhasilvotingprofesional()
    {
        if(isAdmin() || sessPenggunaId()=='29'){
            $dt    = $this->Md_pengguna->getAllHasilVotingProfesional();
            $data  = array();
            $index = 1;
            foreach ($dt['data'] as $row) {
                $th = array();
                $th[] = $index;
                $th[] = $row->nama;
                $th[] = $row->total;
                $data[] = $th;
                $index++;
            }
            
            $dt['data'] = $data;
            echo json_encode($dt);
            die;  
        }
           
    }
     public function paginationhasilvotingcreative()
    {
       if(isAdmin() || sessPenggunaId()=='29'){
            $dt    = $this->Md_pengguna->getAllHasilVotingCreative();
            $data  = array();
            $index = 1;
            foreach ($dt['data'] as $row) {
                $th = array();
                $th[] = $index;
                $th[] = $row->nama;
                $th[] = $row->total;
                $data[] = $th;
                $index++;
            }
            
            $dt['data'] = $data;
            echo json_encode($dt);
            die;  
        }
           
    }
    public function paginationhasilvotingsales()
    {
       if(isAdmin() || sessPenggunaId()=='29'){
            $dt    = $this->Md_pengguna->getAllHasilVotingSales();
            $data  = array();
            $index = 1;
            foreach ($dt['data'] as $row) {
                $th = array();
                $th[] = $index;
                $th[] = $row->nama;
                $th[] = $row->total;
                $data[] = $th;
                $index++;
            }
            
            $dt['data'] = $data;
            echo json_encode($dt);
            die;  
        }
           
    }
     public function paginationhasilvotingoperational()
    {
        if(isAdmin() || sessPenggunaId()=='29'){
            $dt    = $this->Md_pengguna->getAllHasilVotingOperational();
            $data  = array();
            $index = 1;
            foreach ($dt['data'] as $row) {
                $th = array();
                $th[] = $index;
                $th[] = $row->nama;
                $th[] = $row->total;
                $data[] = $th;
                $index++;
            }
            
            $dt['data'] = $data;
            echo json_encode($dt);
            die;  
        }
           
    }
    public function paginationhasilvotingbestofyear()
    {
       if(isAdmin() || sessPenggunaId()=='29'){
            $dt    = $this->Md_pengguna->getAllHasilVotingBestofyear();
            $data  = array();
            $index = 1;
            foreach ($dt['data'] as $row) {
                $th = array();
                $th[] = $index;
                $th[] = $row->nama;
                $th[] = $row->total;
                $data[] = $th;
                $index++;
            }
            
            $dt['data'] = $data;
            echo json_encode($dt);
            die;  
        }
           
    }
     public function paginationhasilvotingsudahvoting()
    {
       if(isAdmin() || sessPenggunaId()=='29'){
            $dt    = $this->Md_pengguna->getAllHasilVotingSudahVoting();
            $data  = array();
            $index = 1;
            foreach ($dt['data'] as $row) {
                $th = array();
                $th[] = $index;
                $th[] = $row->nama;
                $th[] = ($row->profesional==0) ?  'Abstain' : '';
                $th[] = ($row->creative==0) ?  'Abstain' : '';
                $th[] = ($row->operational==0) ?  'Abstain' : '';
                $th[] = ($row->bestofyear==0) ?  'Abstain' : '';
                $data[] = $th;
                $index++;
            }
            
            $dt['data'] = $data;
            echo json_encode($dt);
            die;  
        }
           
    }
}
