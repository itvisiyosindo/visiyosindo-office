<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Slip_gaji extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pengguna');
        $this->load->model('md_salary');
        $this->load->model('md_absensi');
        $this->load->model('md_potong_salary');
        $this->load->model('md_slip_gaji');
    }

    public function index()
    {
        grantAccessFor(['Administrator']);

        $page_data['page_name']       = 'v_slip_gaji';
        $page_data['page_title']      = 'Slip Gaji';
        $page_data['page_desc']       = 'List slip gaji karyawan';
        $this->load->view('index', $page_data);
    }

    public function show($param = "", $param2 = "")
    {
        if ($param == "detail_slip_gaji") {
            $pengguna_id = decrypt($param2);
            $page_data['pengguna'] = $this->md_pengguna->getById($pengguna_id);
            $page_data['salary'] = $this->md_salary->getById($page_data['pengguna'][0]->id_latestriwayat_salary);
            $page_data['riwayat_potongan'] = $this->md_potong_salary->getMonthPotongan($pengguna_id);
            $latest_riwayat_potongan = $this->md_potong_salary->getLatestPotongan($pengguna_id);
            $page_data['current_salary'] = array();
            $page_data['current_salary'][0] = new stdClass;
            $page_data['current_salary'][0]->total_gaji_pokok = $page_data['salary'][0]->gaji_pokok;
            $page_data['current_salary'][0]->total_tunjangan_jabatan = $page_data['salary'][0]->tunjangan_jabatan;
            $page_data['page_name']       = 'v_detail_slip_gaji';
            $page_data['page_title']      = 'Detail Slip Gaji';
            $page_data['page_desc']       = 'List Detail slip gaji karyawan';
            $this->load->view('index', $page_data);
        }
    }

    public function pagination()
    {
        grantAccessFor(['Administrator']);

        $dt    = $this->md_pengguna->getAllPengguna();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->pengguna_id);
            $nama_pengguna = '<a href="slip_gaji/show/detail_slip_gaji/' . $id . '")>' . $row->nama . '</a>';

            $th = array();
            $th[] = ++$start . '.';
            $th[] = $nama_pengguna;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
