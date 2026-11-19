<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member_visiacademy extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Md_member_visiacademy');
    }

    public function index()
    {
        grantAccessFor('all');
        $page_data['switch']     = 'home'; // Set default switch ke 'home'
        $page_data['page_name']  = 'member_visiacademy/v_member_visiacademy'; // Perbaiki path view
        $page_data['page_title'] = 'Member Visi Academy';
        $page_data['members']    = $this->Md_member_visiacademy->getAllMember();
        $page_data['deals']      = $this->Md_member_visiacademy->getAllDeals();
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        $data = $this->input->post();
        if(empty($data['nama_member'])) ajaxReturnDie('error', 'Nama wajib diisi');
        $this->Md_member_visiacademy->addMember($data);
        ajaxReturnDie('success', 'Member ditambahkan', 'reload_table');
    }

    public function addDeal()
    {
        $post = $this->input->post();
        if(empty($post['id_member'])) ajaxReturnDie('error', 'Pilih member');
        $post['nominal_komisi'] = $post['estimasi_nilai'] * ($post['persen_komisi'] / 100);
        $this->Md_member_visiacademy->addDeal($post);
        ajaxReturnDie('success', 'Deal ditambahkan', 'reload_table');
    }
}
