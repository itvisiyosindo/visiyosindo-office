<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class blob extends CI_Controller {

     function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('form_validation');
        $this->load->model('md_blob');

    }
    
    

	function index()
	{
	    grantAccessFor('all');
	    $page_data['switch'] = 'home';
	    $page_data['page_name']  = 'form_uploadblob';
        $page_data['page_title'] = 'Upload BLOB';
        $page_data['page_desc']  = 'Upload BLOB';
        $this->load->view('index',$page_data);
	}
	
	function getblob(){
	    $result = $this->md_blob->getimage(); 
        header('Content-Type:image/gif'); 
        echo $result; 
	}
	
	function proses()
	{
	    grantAccessFor('all');
	    $this->form_validation->set_rules('keterangan_berkas', 'keterangan_berkas', 'required');
       
    
        if ($this->form_validation->run() == FALSE ) {
        
            $this->load->view('form_uploadblob', $data);
          } else {
            $config['upload_path']        = '.assets/img/';
            $config['allowed_types']      = 'gif|jpg|png';
            $now = date('Y-m-d-H-i-s');
            $config['file_name']          = $now.'.gif';
        
                       $config['max_size']             = 0;
                       // $config['max_width']            = 1024;
                       // $config['max_height']           = 768;
        
                       $this->load->library('upload', $config);
                       $this->upload->initialize($config);
                       //
                       if ( ! $this->upload->do_upload('userfile'))
                       {
                               $error = array('error' => $this->upload->display_errors());
                               print_r($error);
                       }
                       else
                       {
                               $data = array('upload_data' => $this->upload->data());
                       }
                       // exit;
            $pet = 'assets/img/signatureEmail.gif';
            $data = [
              "keterangan_berkas" => $this->input->post('keterangan_berkas', true),
              "berkas" => $pet
            ];
            $this->md_blob->add($data);
            $this->session->set_flashdata('flash', 'Ditambah');
           redirect('blob');
          }
    			
	
		
	}
}