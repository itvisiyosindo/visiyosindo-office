<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class detail_package_mesin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_detail_package_mesin');
    }
	
	function id_navbar(){
		$id_navbar = "marketing";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']  	= 'v_detail_package_mesin';
        $page_data['page_title']	= 'Detail Package Mesin';
        $page_data['page_desc']  	= 'Management Data Detail Package Mesin PT. VYM';
        $this->load->view('index', $page_data);
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_detail_package_mesin->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_dpm);
            $link_download = '<a href="' . $row->link_gd . '"><i class="fas fa-download"></i> Download</a>';
            
			$th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_package;
            $th[] = $row->deskripsi;            
            $th[] = $link_download;
            
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
