<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends CI_Controller {
	function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }
    
    function id_navbar(){
		$id_navbar = "home";
		return $id_navbar;
	}

    public function index(){
        grantAccessFor('all');
        
        $page_data['switch']		= $this->id_navbar();
        $page_data['page_name']     = 'log';
        $page_data['page_title']    = 'Log System';
        $page_data['page_desc']     = 'Data aktivitas pengguna dalam berinteraksi dengan sistem';
        $this->load->view('index', $page_data);
    }

    public function pagination(){
        grantAccessFor('all');
        $dt = $this->md_log->getAllLog();
        $start = $this->input->post('start');
        $data =array();
        foreach($dt['data'] as $row){
            $th = array();
            $th[] = ++$start.'.';
            $th[] = $row->nama_pengguna ? $row->nama_pengguna : '-';
            $th[] = $row->jenis_aksi;
            $th[] = $row->keterangan;
            $th[] = '<i class="fa fa-clock-o"></i> '.date('d-M-Y | H:i',strtotime($row->tgl));
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    } 
}

