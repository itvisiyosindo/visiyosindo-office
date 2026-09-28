<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_visilab extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_dashboard');
        $this->load->model('md_log');
        $this->load->model('md_absensi');
    }
	
	function id_navbar(){
		$id_navbar = "visilab";
		return $id_navbar;
	}
	
    public function index()
    {
        grantAccessFor('all');
		
		$page_data['switch']      	= $this->id_navbar();
        $page_data['page_name']   	= 'dashboard_visilab';
        $page_data['page_title']  	= 'Dashboard Visilab';
        $page_data['present'] 		= $this->md_absensi->countPresentToday();
        $page_data['izin'] 			= $this->md_absensi->countIzinToday();
        $page_data['cuti'] 			= $this->md_absensi->countCutiToday();
        $page_data['sakit'] 		= $this->md_absensi->countSakitToday();
        $page_data['daftar_hadir'] 	= $this->md_absensi->daftar_hadirToday();
        $page_data['daftar_izin'] 	= $this->md_absensi->daftar_izinToday('izin');
        $page_data['daftar_sakit'] 	= $this->md_absensi->daftar_izinToday('sakit');
        $page_data['daftar_cuti'] 	= $this->md_absensi->daftar_izinToday('cuti');

        // $page_data['page_desc']       = 'Barang dan Jasa';
        // $page_data['jenis_dashboard'] = 'Administrator';
        $this->load->view('index', $page_data);
    }

    public function pagination_log()
    {
        grantAccessFor('all');

        $dt = $this->md_log->getAllLogVisilab();
        $start = $this->input->post('start');
        $data = array();
        foreach ($dt['data'] as $row) {
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_pengguna ? $row->nama_pengguna : '-';
            $th[] = $row->jenis_aksi;
            $th[] = $row->keterangan;
            $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y | H:i', strtotime($row->tgl));
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
