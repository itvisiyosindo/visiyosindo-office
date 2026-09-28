<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class BukuTamu extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_prov_kota');
    }
	

    public function index()
    {
        $this->load->model('md_bukutamu');
        $data['v_bukutamu']= $this->md_bukutamu->get_data();
        
        $this->load->view('v_bukutamu', $data);
    }
    
    function add_ajax_kota($id_prov){
      $query = $this->db->get_where('kota',array('id_prov'=>$id_prov));
      $data = "<option value=''>- Pilih Kabupaten/Kota -</option>";
      foreach ($query->result() as $value) {
          $data .= "<option value='".$value->id."'>".$value->tipe.' '.$value->nama."</option>";
      }
           echo $data;
    }

    public function register()
  {
    // Assuming form validation and other processing

    $data = array(
        'nama'     => $this->input->post('nama', TRUE),
        'jabatan'  => $this->input->post('jabatan', TRUE),
        'nomorwa'  => $this->input->post('nomorwa', TRUE),
        'email'    => $this->input->post('email', TRUE),
        'instansi' => $this->input->post('instansi', TRUE),
        'provinsi' => $this->input->post('provinsi', TRUE),
        'kota'     => $this->input->post('kota', TRUE),
        // Add other fields as needed
    );

    // Insert data into the database
    $this->bukutamu->insert_data($data);

    // You can add a success message or redirect to another page
    echo "Data inserted successfully!";
}


}