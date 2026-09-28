<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccurateController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('AccurateAPI');
    }

    public function index()
    {
        // Contoh pemanggilan GET
        $response = $this->accurateapi->get('/api/employee/list.do'); // Ganti dengan endpoint yang sesuai
        print_r($response); // Menampilkan hasil response dari API Accurate
    }

    public function createTransaction()
    {
        // Contoh pemanggilan POST untuk membuat transaksi
        //$data = [
        //    'invoice' => [
        //        'customer_id' => 12345,
        //        'total_amount' => 500000,
        //        'date' => '2024-11-07'
        //    ]
       // ];
       // $response = $this->accurateapi->post('/v1/transactions', $data); // Ganti dengan endpoint dan data yang sesuai
       // print_r($response); // Menampilkan hasil response dari API Accurate
    }
}
