<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Detail_barang_keluar extends CI_Controller
{
    // function __construct()
    // {
    //     parent::__construct();
    //     date_default_timezone_set('Asia/Jakarta');
    //     $this->load->model('md_gudang');
    //     $this->load->model('md_customer');
    //     $this->load->model('md_pengeluaran_barang');
    //     $this->load->model('md_detail_barang_keluar');
    //     $this->load->model('md_barang');
    // }

    // public function get($param)
    // {
    //     if($param == 'no_batch'){
    //         $id_barang = decrypt($this->input->post('id_barang'));
    //         $id_gudang = decrypt($this->input->post('id_gudang'));
    //         $data = $this->md_detail_barang_keluar->getNoBatchByIdBarang($id_barang, $id_gudang);
    //         echo '<pre>'; print_r( $data );die; echo '</pre>';
    //         foreach($data as $row){
    //             $row->id_detail_barang = encrypt($row->id_detail_barang);
    //             $row->id_barang = encrypt($row->id_barang);
    //             $row->id_gudang = encrypt($row->id_gudang);
    //             $row->id_pemasok = encrypt($row->id_pemasok);
    //             $row->id_penerimaan_barang = encrypt($row->id_penerimaan_barang);
    //             $row->exp_date = date_view_format($row->exp_date);
    //         }
    //         echo json_encode($data);
    //     } else if ($param == 'cek_stock'){
    //         echo '<pre>'; print_r($this->input->post()  );die; echo '</pre>';
    //     }
    // }
}
