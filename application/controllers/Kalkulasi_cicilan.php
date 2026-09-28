<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Kalkulasi_cicilan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_kategori_barang');
        $this->load->model('md_barang');
        $this->load->model('md_customer');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']  	= 'v_kalkulasi_cicilan';
        $page_data['page_title'] 	= 'Kalkulasi Cicilan';
        $page_data['page_desc']  	= '';
        $this->load->view('index', $page_data);
    }

    public function calculate()
    {
        grantAccessFor('all');
        $harga_awal = str_replace(".", "", $this->input->post('harga_awal'));
        $dp = $harga_awal * $this->input->post('dp')/100;

        if ($this->input->post('dp') == 30) {
            $data['12_bulan'] = ($harga_awal + 30/100 * ($harga_awal - $dp) - $dp) / 12;
            $data['12_bulan'] = "Rp " . number_format($data['12_bulan'],2,',','.');

            $data['24_bulan'] = ($harga_awal + 30/100 * ($harga_awal - $dp) * 2 - $dp + 15000000) / 24;
            $data['24_bulan'] = "Rp " . number_format($data['24_bulan'],2,',','.');

            $data['36_bulan'] = ($harga_awal + 30/100 * ($harga_awal - $dp) * 3 - $dp + 30000000) / 36;
            $data['36_bulan'] = "Rp " . number_format( $data['36_bulan'],2,',','.');
            
            $data['nilai_dp'] = "Rp " . number_format($dp,2,',','.');

            echo json_encode($data);
            die;
        } else {
            $data['12_bulan'] = (($harga_awal + 50/100 * $harga_awal) - $dp) / 12;
            $data['12_bulan'] = "Rp " . number_format($data['12_bulan'],2,',','.');

            $data['24_bulan'] = (($harga_awal + 50/100 * $harga_awal * 2) - $dp + 15000000) / 24;
            $data['24_bulan'] = "Rp " . number_format($data['24_bulan'],2,',','.');

            $data['36_bulan'] = (($harga_awal + 50/100 * $harga_awal * 3) - $dp + 30000000) / 36;
            $data['36_bulan'] = "Rp " . number_format( $data['36_bulan'],2,',','.');

            $data['nilai_dp'] = "Rp " . number_format($dp,2,',','.');

            echo json_encode($data);
            die;
        }
    }

    public function print(){
        $id = decrypt($this->input->post('id_customer'));
        $customer = $this->md_customer->getById($id);
        $page_data['nama_customer'] = $customer[0]->nama_customer;
        $page_data['alamat_customer'] = $customer[0]->alamat_customer;
        $page_data['contact'] = $customer[0]->contact;
        $page_data['tgl_penawaran'] = $this->input->post('tgl_penawaran');
        $page_data['no_penawaran'] = $this->input->post('no_penawaran');
        // $page_data['keterangan'] = $this->input->post('keterangan');
        $page_data['harga_awal'] = $this->input->post('harga_awal');
        $page_data['dp'] = $this->input->post('dp');
        $page_data['bulan_12'] = $this->input->post('bulan_12');
        $page_data['bulan_24'] = $this->input->post('bulan_24');
        $page_data['bulan_36'] = $this->input->post('bulan_36');
        $page_data['nilai_dp'] = $this->input->post('nilai_dp');
        $this->load->view('pages/v_print/print_kalkulasi_cicilan', $page_data);
    }
}
