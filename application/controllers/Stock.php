<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Stock extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_stock');
        $this->load->model('md_history_barang');
        $this->load->model('md_barang');
        $this->load->model('md_pelanggan');
        $this->load->model('md_pengguna');
        $this->load->model('md_prov_kota');
        $this->load->model('md_kategori_tiket');
        $this->load->model('md_tiket');
        $this->load->helper('mandatory_helper');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');
             
        $page_data['switch']      	= $this->id_navbar();
		$page_data['page_name']     = 'stock/v_stock';
        $page_data['page_title']    = 'Stock Barang';
        $page_data['page_desc']     = 'Management Data Stock Barang';
        $id_barang                  = decrypt($this->input->get('id_barang')); 
        $list_data                  = $this->md_stock->getBarangStock($id_barang);

        // Hitung total stok
        $totalSTOCK = array_sum(array_map(function($row) {
            return (!empty($row->current_stock) && is_numeric($row->current_stock) && $row->current_stock > 0)
                ? (float) $row->current_stock
                : 0;
        }, $list_data));


        // Hitung total stok yang bisa dijual (hanya gudang 1, 2, 3)
        $totalSTOCKjual = array_sum(array_map(function($row) {
            if (in_array($row->id_gudang, [1, 2, 3])) {
                return (!empty($row->current_stock) && is_numeric($row->current_stock) && $row->current_stock > 0)
                    ? (float) $row->current_stock
                    : 0;
            }
            return 0;
        }, $list_data));

        $page_data['list_data']    = $list_data;
        $page_data['totalSTOCK']   = $totalSTOCK;
        $page_data['totalSTOCKjual'] = $totalSTOCKjual;

        $page_data['data_barang']  = $this->md_barang->getByWhere(['b.id_barang' => decrypt($this->input->get('id_barang'))]);
        $this->load->view('index', $page_data);
    }


   

    /*


    public function pagination11()
    {
        grantAccessFor('all');

        $dt    = $this->md_history_barang->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            
            $th = array();
            
            $th[] = $row->tgl_masuk;
            $th[] = $row->no_terima;
            $th[] = 'Penerimaan Stok';
            $th[] = $row->nama_barang;
            $th[] = $row->no_batch;
            $th[] = $row->exp_date;
            $th[] = $row->nama_gudang;
            $th[] = $row->current_stock;
            $data[] = $th;

        }
        
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }


    public function pagination17()
    {
        grantAccessFor('all');

        // Ambil data dari getAll()
        $dt    = $this->md_history_barang->getAll();
        $start = $this->input->post('start');
        $data  = array();

        foreach ($dt['data'] as $row) {
            
            // Tentukan kategori untuk Penerimaan Stok
            if (!is_null($row->penerimaan_barang_id)) {
                $penerimaan_stok_kategori = 'Penerimaan Stok';
            } elseif (!is_null($row->id_pemasok)) {
                $penerimaan_stok_kategori = 'Penerimaan Barang'; // Jika id_pemasok tidak null, berarti Penerimaan Barang
            } else {
                $penerimaan_stok_kategori = null; // Tidak ada kategori
            }

            // Tentukan kategori untuk Pengiriman Stok
            if (!is_null($row->pengiriman_stok_id)) {
                $pengiriman_stok_kategori = 'Pengiriman Stok';
            } else {
                $pengiriman_stok_kategori = null; // Tidak ada pengiriman stok
            }

            // Jika keduanya ada (Penerimaan Stok dan Pengiriman Stok), buat dua baris data
            if ($penerimaan_stok_kategori == 'Penerimaan Stok' && $pengiriman_stok_kategori == 'Pengiriman Stok') {
                // Baris pertama: Penerimaan Stok
                $th1 = array();
                $th1[] = $row->tgl_masuk;
                $th1[] = $row->no_terima;
                $th1[] = $penerimaan_stok_kategori;  // Kategori Penerimaan Stok
                $th1[] = $row->nama_barang;
                $th1[] = $row->no_batch;
                $th1[] = $row->exp_date;
                $th1[] = $row->nama_gudang;
                $th1[] = $row->qty;

                // Baris kedua: Pengiriman Stok
                $th2 = array();
                $th2[] = $row->tgl_masuk;
                $th2[] = $row->no_terima;
                $th2[] = $pengiriman_stok_kategori;  // Kategori Pengiriman Stok
                $th2[] = $row->nama_barang;
                $th2[] = $row->no_batch;
                $th2[] = $row->exp_date;
                $th2[] = $row->gudang_asal;
                $th2[] = $row->qty;

                // Tambahkan kedua baris ke data
                $data[] = $th1;
                $data[] = $th2;

            } else {
                // Jika hanya ada salah satu kategori (Penerimaan Stok atau Pengiriman Stok)
                $th = array();
                $th[] = $row->tgl_masuk;
                $th[] = $row->no_terima;
                $th[] = $penerimaan_stok_kategori ?: $pengiriman_stok_kategori; // Pilih kategori yang ada
                $th[] = $row->nama_barang;
                $th[] = $row->no_batch;
                $th[] = $row->exp_date;
                $th[] = $row->nama_gudang;
                $th[] = $row->qty;

                // Tambahkan baris ke data
                $data[] = $th;
            }
        }

        // Set data yang sudah diproses ke variabel dt
        $dt['data'] = $data;

        // Kirimkan response JSON
        echo json_encode($dt);
        die;
    }



    public function paginationOK()
    {
        grantAccessFor('all');

        // Ambil data dari getAll()
        $dt = $this->md_history_barang->getAll();
        $start = $this->input->post('start');
        $data = array();

        foreach ($dt['data'] as $row) {

            // Tentukan kategori untuk Penerimaan Stok
            if (!is_null($row->penerimaan_barang_id)) {
                $penerimaan_stok_kategori = 'Penerimaan Stok';
            } elseif (!is_null($row->id_pemasok)) {
                $penerimaan_stok_kategori = 'Penerimaan Barang';
            } else {
                $penerimaan_stok_kategori = null;
            }

            // Tentukan kategori untuk Pengiriman Stok
            if (!is_null($row->pengiriman_stok_id)) {
                $pengiriman_stok_kategori = 'Pengiriman Stok';
            } else {
                $pengiriman_stok_kategori = null;
            }

            // Tentukan kategori untuk Pengeluaran Barang
            if (!is_null($row->detail_barang_exit)) { // Jika detail_barang_exit tidak null
                $pengeluaran_kategori = 'Pengeluaran Barang';
            } else {
                $pengeluaran_kategori = null;
            }

            // Jika ada data untuk Pengeluaran Barang
            if (!is_null($pengeluaran_kategori)) {
                $th = array();
                $th[] = $row->tgl_keluar;          // Tanggal keluar
                $th[] = $row->no_pengiriman;      // No pengiriman
                $th[] = $pengeluaran_kategori;    // Kategori Pengeluaran Barang
                $th[] = $row->nama_barang_exit;   // Nama barang dari dbk
                $th[] = $row->no_batch_exit;      // No batch dari dbk
                $th[] = $row->exp_date_exit;      // Exp date dari dbk
                $th[] = $row->nama_gudang_exit;   // Gudang tujuan
                $th[] = $row->qty_exit;           // Kuantitas dari dbk

                // Tambahkan ke data
                $data[] = $th;
            }

            // Jika ada data untuk Penerimaan Stok atau Pengiriman Stok
            if ($penerimaan_stok_kategori == 'Penerimaan Stok' && $pengiriman_stok_kategori == 'Pengiriman Stok') {
                // Baris pertama: Penerimaan Stok
                $th1 = array();
                $th1[] = $row->tgl_masuk;
                $th1[] = $row->no_terima;
                $th1[] = $penerimaan_stok_kategori;
                $th1[] = $row->nama_barang;
                $th1[] = $row->no_batch;
                $th1[] = $row->exp_date;
                $th1[] = $row->nama_gudang;
                $th1[] = $row->qty;

                // Baris kedua: Pengiriman Stok
                $th2 = array();
                $th2[] = $row->tgl_masuk;
                $th2[] = $row->no_terima;
                $th2[] = $pengiriman_stok_kategori;
                $th2[] = $row->nama_barang;
                $th2[] = $row->no_batch;
                $th2[] = $row->exp_date;
                $th2[] = $row->gudang_asal;
                $th2[] = $row->qty;

                // Tambahkan kedua baris ke data
                $data[] = $th1;
                $data[] = $th2;

            } elseif ($penerimaan_stok_kategori || $pengiriman_stok_kategori) {
                // Jika hanya ada salah satu kategori
                $th = array();
                $th[] = $row->tgl_masuk;
                $th[] = $row->no_terima;
                $th[] = $penerimaan_stok_kategori ?: $pengiriman_stok_kategori;
                $th[] = $row->nama_barang;
                $th[] = $row->no_batch;
                $th[] = $row->exp_date;
                $th[] = $row->nama_gudang;
                $th[] = $row->qty;

                // Tambahkan ke data
                $data[] = $th;
            }
        }

        // Set data yang sudah diproses ke variabel dt
        $dt['data'] = $data;

        // Kirimkan response JSON
        echo json_encode($dt);
        die;
    }

    public function pagination()
    {
        grantAccessFor('all');

        // Ambil data dari getAll()
        $id_barang = decrypt($this->input->get('id_barang'));
        $tglawal = $this->input->post('tglawal');
        $tglakhir = $this->input->post('tglakhir');

        // Call the model method with the filters
        $dt = $this->md_history_barang->getAllbyTGL($id_barang, $tglawal, $tglakhir);
        
        //$dt = $this->md_history_barang->getAll();
        $start = $this->input->post('start');
        $data = array();

        foreach ($dt['data'] as $row) {

            // Tentukan kategori untuk Penerimaan Stok
            if (!is_null($row->penerimaan_barang_id)) {
                $penerimaan_stok_kategori = 'Penerimaan Stok';
            } elseif (!is_null($row->id_pemasok)) {
                $penerimaan_stok_kategori = 'Penerimaan Barang';
            } else {
                $penerimaan_stok_kategori = null;
            }

            // Tentukan kategori untuk Pengiriman Stok
            if (!is_null($row->pengiriman_stok_id)) {
                $pengiriman_stok_kategori = 'Pengiriman Stok';
            } else {
                $pengiriman_stok_kategori = null;
            }

            // Tentukan kategori untuk Pengeluaran Barang
            if (!is_null($row->detail_barang_exit)) { // Jika detail_barang_exit tidak null
                $pengeluaran_kategori = 'Pengeluaran Barang';
            } else {
                $pengeluaran_kategori = null;
            }

            // Jika ada data untuk Pengeluaran Barang
            if (!is_null($pengeluaran_kategori)) {
                $th = array();
                $th[] = $row->tgl_keluar;          // Tanggal keluar
                $th[] = $row->no_pengiriman;      // No pengiriman
                $th[] = $pengeluaran_kategori;    // Kategori Pengeluaran Barang
                $th[] = $row->nama_barang_exit;   // Nama barang dari dbk
                $th[] = $row->no_batch_exit;      // No batch dari dbk
                $th[] = $row->exp_date_exit;      // Exp date dari dbk
                $th[] = $row->nama_gudang_exit;   // Gudang tujuan
                $th[] = $row->qty_exit;           // Kuantitas dari dbk

                // Tambahkan ke data
                $data[] = $th;
            }

            // Jika ada data untuk Penerimaan Stok atau Pengiriman Stok
            if ($penerimaan_stok_kategori == 'Penerimaan Stok' && $pengiriman_stok_kategori == 'Pengiriman Stok') {
                // Baris pertama: Penerimaan Stok
                $th1 = array();
                $th1[] = date('d-M-Y',strtotime($row->tgl_masuk));
                $th1[] = $row->no_terima;
                $th1[] = $penerimaan_stok_kategori;
                $th1[] = $row->nama_barang;
                $th1[] = $row->no_batch;
                $th1[] = $row->exp_date;
                $th1[] = $row->nama_gudang;
                $th1[] = $row->qty;

                // Baris kedua: Pengiriman Stok
                $th2 = array();
                $th2[] = date('d-M-Y',strtotime($row->tgl_masuk));
                $th2[] = $row->no_terima;
                $th2[] = $pengiriman_stok_kategori;
                $th2[] = $row->nama_barang;
                $th2[] = $row->no_batch;
                $th2[] = $row->exp_date;
                $th2[] = $row->gudang_asal;
                $th2[] = $row->qty;

                // Tambahkan kedua baris ke data
                $data[] = $th1;
                $data[] = $th2;

            } elseif ($penerimaan_stok_kategori || $pengiriman_stok_kategori) {
                // Jika hanya ada salah satu kategori
                $th = array();
                $th[] = date('d-M-Y',strtotime($row->tgl_masuk));
                $th[] = $row->no_terima;
                $th[] = $penerimaan_stok_kategori ?: $pengiriman_stok_kategori;
                $th[] = $row->nama_barang;
                $th[] = $row->no_batch;
                $th[] = $row->exp_date;
                $th[] = $row->nama_gudang;
                $th[] = $row->qty;

                // Tambahkan ke data
                $data[] = $th;
            }
        }

        // Set data yang sudah diproses ke variabel dt
        $dt['data'] = $data;

        // Kirimkan response JSON
        echo json_encode($dt);
        die;
    }



    */





}
