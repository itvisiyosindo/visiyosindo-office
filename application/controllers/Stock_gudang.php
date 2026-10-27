<?php

use FontLib\Table\Type\post;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


defined('BASEPATH') or exit('No direct script access allowed');

class Stock_gudang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_detail_barang');
        $this->load->model('md_barang');
        $this->load->model('md_gudang');
        $this->load->model('md_penerimaan_barang');
        $this->load->model('md_kategori_barang');
        $this->load->model('md_detail_barang_pengiriman_stok');
        $this->load->model('md_pengiriman_stok');
    }

    function id_navbar()
    {
        $id_navbar = "inventory";
        return $id_navbar;
    }

    /**
     * Helper method: Build pagination data rows menggunakan bulk queries
     * Menggantikan N+1 query pattern yang sangat lambat
     * 
     * @param array $dt         DataTables result dari model (berisi 'data', 'recordsTotal', dll)
     * @param array $dt_gudang  Array of gudang objects
     * @param array $dt_transit Array of transit records (untuk cek apakah ada transit)
     * @return array Modified $dt dengan data rows yang sudah di-build
     */
    private function _buildPaginationData($dt, $dt_gudang, $dt_transit)
    {
        $time_start = microtime(true);
        $has_transit = !empty($dt_transit);
        $data = array();

        // Collect barang IDs dan gudang IDs dari hasil pagination
        $barang_ids = [];
        $gudang_ids = [];
        foreach ($dt['data'] as $row) {
            $barang_ids[] = $row->id_barang;
        }
        foreach ($dt_gudang as $row2) {
            $gudang_ids[] = $row2->id_gudang;
        }

        // Bulk query: ambil semua stock per gudang dalam 1 query (mengganti N+1 queries)
        $stock_lookup = $this->md_barang->getBulkStokByGudangIds($gudang_ids, $barang_ids);

        // Bulk query: ambil semua transit stock dalam 1 query (mengganti N+1 queries)
        $transit_lookup = [];
        if ($has_transit) {
            $transit_lookup = $this->md_barang->getBulkStokTransit($barang_ids);
        }

        // Build data rows menggunakan lookup arrays (tanpa query tambahan)
        foreach ($dt['data'] as $row) {
            $id = $row->id_barang;
            $safeNamaBarang = htmlspecialchars($row->nama_barang, ENT_QUOTES, 'UTF-8');
            $satuan = !empty($row->nama_satuan) ? htmlspecialchars($row->nama_satuan, ENT_QUOTES, 'UTF-8') : 'Unit';
            $th = array();
            $th[] = '<span class="font-weight-semibold">' . $safeNamaBarang . '</span> <span class="badge badge-secondary ml-1" style="font-size:10px; font-weight:normal;">' . $satuan . '</span>';

            // Stock per gudang dari lookup
            foreach ($dt_gudang as $row3) {
                $valStok = isset($stock_lookup[$id][$row3->id_gudang]) ? (int)$stock_lookup[$id][$row3->id_gudang] : 0;
                $th[] = $valStok > 0 ? '<span class="badge badge-success px-2 py-1" style="font-size:12px;">' . $valStok . '</span>' : '0';
            }

            // Transit dari lookup (1 kolom saja)
            if ($has_transit) {
                $valTransit = isset($transit_lookup[$id]) ? (int)$transit_lookup[$id] : 0;
                $th[] = $valTransit > 0 ? '<span class="badge badge-warning px-2 py-1" style="font-size:12px;">' . $valTransit . '</span>' : '0';
            }

            $data[] = $th;
        }

        $dt['data'] = $data;

        // Tambahkan info waktu eksekusi untuk monitoring performa
        $time_end = microtime(true);
        $dt['query_time'] = round(($time_end - $time_start) * 1000, 2) . ' ms';
        $dt['query_count'] = '3 queries (bulk optimized)';

        return $dt;
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']            = $this->id_navbar();
        //$page_data['gudang'] 			= $this->md_gudang->getByWhere(['g.status'=>1]);
        $page_data['gudang']             = $this->md_gudang->getBywhereInOri(['g.status' => 1]);
        $page_data['kategori_barang']      = $this->md_kategori_barang->getByWhere();
        foreach ($page_data['gudang'] as $row) {
            $row->id_gudang = encrypt($row->id_gudang);
        }
        $page_data['transit']              = $this->md_pengiriman_stok->getByWhere(['ps.status' => 1]) ? 1 : 0;
        $page_data['page_name']          = 'v_stock_gudang';
        $page_data['page_title']         = 'Stock di Gudang';
        $page_data['page_desc']          = 'List Stock Barang yg ada Tiap Gudang';
        $this->load->view('index', $page_data);
    }


    public function show($param = "")
    {
        grantAccessFor('all');

        if ($param == 'pusat') {
            $page_data['switch']            = $this->id_navbar();
            $page_data['gudang']             = $this->md_gudang->getByWhere(['g.status' => 1, 'g.lokasi' => 1]);
            $page_data['kategori_barang']      = $this->md_kategori_barang->getByWhere();
            foreach ($page_data['gudang'] as $row) {
                $row->id_gudang = encrypt($row->id_gudang);
            }
            $page_data['transit']              = $this->md_pengiriman_stok->getByWhere(['ps.status' => 1]) ? 1 : 0;
            $page_data['page_name']          = 'v_stock_gudang_pusat';
            $page_data['page_title']         = 'Stock di Gudang Pusat';
            $page_data['page_desc']          = 'List Stock Barang yg ada di Gudang Pusat';
            $this->load->view('index', $page_data);
        } else if ($param == 'jakarta') {
            $page_data['switch']            = $this->id_navbar();
            $page_data['gudang']             = $this->md_gudang->getByWhere(['g.status' => 1, 'g.lokasi' => 2]);
            $page_data['kategori_barang']      = $this->md_kategori_barang->getByWhere();
            foreach ($page_data['gudang'] as $row) {
                $row->id_gudang = encrypt($row->id_gudang);
            }
            $page_data['transit']              = $this->md_pengiriman_stok->getByWhere(['ps.status' => 1]) ? 1 : 0;
            $page_data['page_name']          = 'v_stock_gudang_jkt';
            $page_data['page_title']         = 'Stock di Gudang Transit';
            $page_data['page_desc']          = 'List Stock Barang yg ada di Transit Jakarta';
            $this->load->view('index', $page_data);
        } else if ($param == 'yogyakarta') {
            $page_data['switch']            = $this->id_navbar();
            $page_data['gudang']             = $this->md_gudang->getByWhere(['g.status' => 1, 'g.lokasi' => 3]);
            $page_data['kategori_barang']      = $this->md_kategori_barang->getByWhere();
            foreach ($page_data['gudang'] as $row) {
                $row->id_gudang = encrypt($row->id_gudang);
            }
            $page_data['transit']              = $this->md_pengiriman_stok->getByWhere(['ps.status' => 1]) ? 1 : 0;
            $page_data['page_name']          = 'v_stock_gudang_yogya';
            $page_data['page_title']         = 'Stock di Gudang Transit';
            $page_data['page_desc']          = 'List Stock Barang yg ada di Transit Yogyakarta';
            $this->load->view('index', $page_data);
        } else if ($param == 'default') {
            $page_data['switch']            = $this->id_navbar();
            //$page_data['gudang'] 			= $this->md_gudang->getByWhere(['g.status'=>1]);
            $page_data['gudang']             = $this->md_gudang->getBywhereIn(['g.status' => 1]);
            $page_data['kategori_barang']      = $this->md_kategori_barang->getByWhere();
            foreach ($page_data['gudang'] as $row) {
                $row->id_gudang = encrypt($row->id_gudang);
            }
            $page_data['transit']              = $this->md_pengiriman_stok->getByWhere(['ps.status' => 1]) ? 1 : 0;
            $page_data['page_name']          = 'v_stock_gudang_default';
            $page_data['page_title']         = 'Stock di Gudang';
            $page_data['page_desc']          = 'List Stock Barang yg ada Tiap Gudang';
            $this->load->view('index', $page_data);
        } else if ($param == 'jual') {
            $page_data['switch']            = $this->id_navbar();
            $page_data['gudang']             = $this->md_gudang->getByWhere(['g.status' => 1, 'g.jenis' => 1]);
            $page_data['kategori_barang']      = $this->md_kategori_barang->getByWhere();
            foreach ($page_data['gudang'] as $row) {
                $row->id_gudang = encrypt($row->id_gudang);
            }
            $page_data['transit']              = $this->md_pengiriman_stok->getByWhere(['ps.status' => 1]) ? 1 : 0;
            $page_data['page_name']          = 'v_stock_gudang_jual';
            $page_data['page_title']         = 'Stock di Gudang';
            $page_data['page_desc']          = 'List Stock Barang yg ada di Stock Jual';
            $this->load->view('index', $page_data);
        } else if ($param == 'demo') {
            $page_data['switch']            = $this->id_navbar();
            $page_data['gudang']             = $this->md_gudang->getByWhere(['g.status' => 1, 'g.jenis' => 2]);
            $page_data['kategori_barang']      = $this->md_kategori_barang->getByWhere();
            foreach ($page_data['gudang'] as $row) {
                $row->id_gudang = encrypt($row->id_gudang);
            }
            $page_data['transit']              = $this->md_pengiriman_stok->getByWhere(['ps.status' => 1]) ? 1 : 0;
            $page_data['page_name']          = 'v_stock_gudang_demo';
            $page_data['page_title']         = 'Stock di Gudang';
            $page_data['page_desc']          = 'List Stock Barang yg ada di Stock Demo';
            $this->load->view('index', $page_data);
        } else if ($param == 'rusak') {
            $page_data['switch']            = $this->id_navbar();
            $page_data['gudang']             = $this->md_gudang->getByWhere(['g.status' => 1, 'g.jenis' => 3]);
            $page_data['kategori_barang']      = $this->md_kategori_barang->getByWhere();
            foreach ($page_data['gudang'] as $row) {
                $row->id_gudang = encrypt($row->id_gudang);
            }
            $page_data['transit']              = $this->md_pengiriman_stok->getByWhere(['ps.status' => 1]) ? 1 : 0;
            $page_data['page_name']          = 'v_stock_gudang_rusak';
            $page_data['page_title']         = 'Stock di Gudang';
            $page_data['page_desc']          = 'List Stock Barang yg ada di Stock Rusak';
            $this->load->view('index', $page_data);
        } else if ($param == 'marketing') {
            $page_data['switch']            = $this->id_navbar();
            $page_data['gudang']             = $this->md_gudang->getByWhere(['g.status' => 1, 'g.jenis' => 4]);
            $page_data['kategori_barang']      = $this->md_kategori_barang->getByWhere();
            foreach ($page_data['gudang'] as $row) {
                $row->id_gudang = encrypt($row->id_gudang);
            }
            $page_data['transit']              = $this->md_pengiriman_stok->getByWhere(['ps.status' => 1]) ? 1 : 0;
            $page_data['page_name']          = 'v_stock_gudang_marketing';
            $page_data['page_title']         = 'Stock di Gudang';
            $page_data['page_desc']          = 'List Stock Barang yg ada di Stock Demo Marketing';
            $this->load->view('index', $page_data);
        } else if ($param == 'sparepart') {
            $page_data['switch']            = $this->id_navbar();
            $page_data['gudang']             = $this->md_gudang->getByWhere(['g.status' => 1, 'g.jenis' => 5]);
            $page_data['kategori_barang']      = $this->md_kategori_barang->getByWhere();
            foreach ($page_data['gudang'] as $row) {
                $row->id_gudang = encrypt($row->id_gudang);
            }
            $page_data['transit']              = $this->md_pengiriman_stok->getByWhere(['ps.status' => 1]) ? 1 : 0;
            $page_data['page_name']          = 'v_stock_gudang_sparepart';
            $page_data['page_title']         = 'Stock di Gudang';
            $page_data['page_desc']          = 'List Stock Barang yg ada di Stock Sparepart';
            $this->load->view('index', $page_data);
        } else if ($param == 'customer') {
            $page_data['switch']            = $this->id_navbar();
            $page_data['gudang']             = $this->md_gudang->getByWhere(['g.status' => 1, 'g.jenis' => 6]);
            $page_data['kategori_barang']      = $this->md_kategori_barang->getByWhere();
            foreach ($page_data['gudang'] as $row) {
                $row->id_gudang = encrypt($row->id_gudang);
            }
            $page_data['transit']              = $this->md_pengiriman_stok->getByWhere(['ps.status' => 1]) ? 1 : 0;
            $page_data['page_name']          = 'v_stock_gudang_customer';
            $page_data['page_title']         = 'Stock di Gudang Transit';
            $page_data['page_desc']          = 'List Stock Barang yg ada di Stock Barang Customer';
            $this->load->view('index', $page_data);
        }
    }




    public function pagination()
    {
        grantAccessFor('all');

        $dt = $this->md_barang->getAll();
        //$dt_gudang = $this->md_gudang->getByWhere(['g.status'=>1]);
        $dt_gudang = $this->md_gudang->getBywhereInOri(['g.status' => 1]);
        $dt_transit = $this->md_detail_barang_pengiriman_stok->get(['status' => 1]);

        $dt = $this->_buildPaginationData($dt, $dt_gudang, $dt_transit);
        echo json_encode($dt);
        die;
    }


    public function paginationStok($param = "")
    {
        grantAccessFor('all');
        if ($param == 'pusat') {
            $dt = $this->md_barang->getAllPusat();
            $dt_gudang = $this->md_gudang->getByWhere(['g.status' => 1, 'g.lokasi' => 1]);
            $dt_transit = $this->md_detail_barang_pengiriman_stok->get(['status' => 1]);

            $dt = $this->_buildPaginationData($dt, $dt_gudang, $dt_transit);
            echo json_encode($dt);
            die;
        } else if ($param == 'jakarta') {
            $dt = $this->md_barang->getAllJakarta();
            $dt_gudang = $this->md_gudang->getByWhere(['g.status' => 1, 'g.lokasi' => 2]);
            $dt_transit = $this->md_detail_barang_pengiriman_stok->get(['status' => 1]);

            $dt = $this->_buildPaginationData($dt, $dt_gudang, $dt_transit);
            echo json_encode($dt);
            die;
        } else if ($param == 'yogyakarta') {
            $dt = $this->md_barang->getAllYogyakarta();
            $dt_gudang = $this->md_gudang->getByWhere(['g.status' => 1, 'g.lokasi' => 3]);
            $dt_transit = $this->md_detail_barang_pengiriman_stok->get(['status' => 1]);

            $dt = $this->_buildPaginationData($dt, $dt_gudang, $dt_transit);
            echo json_encode($dt);
            die;
        } else if ($param == 'default') {
            $dt = $this->md_barang->getAllDefault();
            //$dt_gudang = $this->md_gudang->getByWhere(['g.status'=>1]);
            $dt_gudang = $this->md_gudang->getBywhereIn(['g.status' => 1]);
            $dt_transit = $this->md_detail_barang_pengiriman_stok->get(['status' => 1]);

            $dt = $this->_buildPaginationData($dt, $dt_gudang, $dt_transit);
            echo json_encode($dt);
            die;
        } else if ($param == 'jual') {
            $dt = $this->md_barang->getAllJual();
            $dt_gudang = $this->md_gudang->getByWhere(['g.status' => 1, 'g.jenis' => 1]);
            $dt_transit = $this->md_detail_barang_pengiriman_stok->get(['status' => 1]);

            $dt = $this->_buildPaginationData($dt, $dt_gudang, $dt_transit);
            echo json_encode($dt);
            die;
        } else if ($param == 'demo') {
            $dt = $this->md_barang->getAllDemo();
            $dt_gudang = $this->md_gudang->getByWhere(['g.status' => 1, 'g.jenis' => 2]);
            $dt_transit = $this->md_detail_barang_pengiriman_stok->get(['status' => 1]);

            $dt = $this->_buildPaginationData($dt, $dt_gudang, $dt_transit);
            echo json_encode($dt);
            die;
        } else if ($param == 'rusak') {
            $dt = $this->md_barang->getAllRusak();
            $dt_gudang = $this->md_gudang->getByWhere(['g.status' => 1, 'g.jenis' => 3]);
            $dt_transit = $this->md_detail_barang_pengiriman_stok->get(['status' => 1]);

            $dt = $this->_buildPaginationData($dt, $dt_gudang, $dt_transit);
            echo json_encode($dt);
            die;
        } else if ($param == 'marketing') {
            $dt = $this->md_barang->getAllMarketing();
            $dt_gudang = $this->md_gudang->getByWhere(['g.status' => 1, 'g.jenis' => 4]);
            $dt_transit = $this->md_detail_barang_pengiriman_stok->get(['status' => 1]);

            $dt = $this->_buildPaginationData($dt, $dt_gudang, $dt_transit);
            echo json_encode($dt);
            die;
        } else if ($param == 'sparepart') {
            $dt = $this->md_barang->getAllSparepart();
            $dt_gudang = $this->md_gudang->getByWhere(['g.status' => 1, 'g.jenis' => 5]);
            $dt_transit = $this->md_detail_barang_pengiriman_stok->get(['status' => 1]);

            $dt = $this->_buildPaginationData($dt, $dt_gudang, $dt_transit);
            echo json_encode($dt);
            die;
        } else if ($param == 'customer') {
            $dt = $this->md_barang->getAllCustomer();
            $dt_gudang = $this->md_gudang->getByWhere(['g.status' => 1, 'g.jenis' => 6]);
            $dt_transit = $this->md_detail_barang_pengiriman_stok->get(['status' => 1]);

            $dt = $this->_buildPaginationData($dt, $dt_gudang, $dt_transit);
            echo json_encode($dt);
            die;
        }
    }




    public function exportlaporan()
    {

        $filter_stock = $this->input->get('filter_stock');

        if ($filter_stock == 1) {
            $data = $this->md_barang->getAllStockExportFilter($this->input->get('filter_kat'));
            $namaFile = "Semua Data";
        } else if ($filter_stock == 2) {
            $data = $this->md_barang->getAllStockExport($this->input->get('filter_kat'));
            $namaFile = "Stock Ready";
        }


        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = [
            'font' => ['bold' => true], // Set font nya jadi bold
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];


        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];

        //$sheet->setCellValue('A1', "DATA STOCK BARANG PER TANGGAL ". $hari_ini);
        $hari_ini = date('d F Y \P\u\k\u\l H:i:s', time());
        $sheet->setCellValue('A1', "DATA STOCK BARANG PER TANGGAL " . $hari_ini);

        $sheet->mergeCells('A1:L1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1

        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Nama Barang');
        $sheet->setCellValue('C4', 'Nama Kategori');
        $sheet->setCellValue('D4', 'Gudang Pusat - Stock Jual');
        $sheet->setCellValue('E4', 'Transit Jakarta - Stock Jual');
        $sheet->setCellValue('F4', 'Transit Yogyakarta - Stock Jual');
        $sheet->setCellValue('G4', 'Gudang Pusat - Stock Demo');
        $sheet->setCellValue('H4', 'Transit Jakarta - Stock Demo');
        $sheet->setCellValue('I4', 'Transit Yogyakarta - Stock Demo');
        $sheet->setCellValue('J4', 'Gudang Pusat - Stock Rusak');
        $sheet->setCellValue('K4', 'Transit Jakarta - Stock Rusak');
        $sheet->setCellValue('L4', 'Transit Yogyakarta - Stock Rusak');
        $sheet->setCellValue('M4', 'Gudang Pusat - Stock Demo Marketing');
        $sheet->setCellValue('N4', 'Transit Jakarta - Stock Demo Marketing');
        $sheet->setCellValue('O4', 'Transit Yogyakarta - Stock Demo Marketing ');
        $sheet->setCellValue('P4', 'Gudang Pusat - Stock Sparepart');
        $sheet->setCellValue('Q4', 'Transit Jakarta - Stock Sparepart');
        $sheet->setCellValue('R4', 'Transit Yogyakarta - Stock Sparepart');
        $sheet->setCellValue('S4', 'Gudang Pusat - Barang Customer');
        $sheet->setCellValue('T4', 'Transit Jakarta - Barang Customer');
        $sheet->setCellValue('U4', 'Transit Yogyakarta - Barang Customer');
        $sheet->setCellValue('V4', 'Gudang Copier');
        $sheet->setCellValue('W4', 'Gudang Mesin Kanibal');
        $sheet->setCellValue('X4', 'Total Stock');

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $sheet->getStyle('A4')->applyFromArray($style_col);
        $sheet->getStyle('B4')->applyFromArray($style_col);
        $sheet->getStyle('C4')->applyFromArray($style_col);
        $sheet->getStyle('D4')->applyFromArray($style_col);
        $sheet->getStyle('E4')->applyFromArray($style_col);
        $sheet->getStyle('F4')->applyFromArray($style_col);
        $sheet->getStyle('G4')->applyFromArray($style_col);
        $sheet->getStyle('H4')->applyFromArray($style_col);
        $sheet->getStyle('I4')->applyFromArray($style_col);
        $sheet->getStyle('J4')->applyFromArray($style_col);
        $sheet->getStyle('K4')->applyFromArray($style_col);
        $sheet->getStyle('L4')->applyFromArray($style_col);
        $sheet->getStyle('M4')->applyFromArray($style_col);
        $sheet->getStyle('N4')->applyFromArray($style_col);
        $sheet->getStyle('O4')->applyFromArray($style_col);
        $sheet->getStyle('P4')->applyFromArray($style_col);
        $sheet->getStyle('Q4')->applyFromArray($style_col);
        $sheet->getStyle('R4')->applyFromArray($style_col);
        $sheet->getStyle('S4')->applyFromArray($style_col);
        $sheet->getStyle('T4')->applyFromArray($style_col);
        $sheet->getStyle('U4')->applyFromArray($style_col);
        $sheet->getStyle('V4')->applyFromArray($style_col);
        $sheet->getStyle('W4')->applyFromArray($style_col);
        $sheet->getStyle('X4')->applyFromArray($style_col);


        $kolom = 5;
        $nomor = 1;

        foreach ($data as $marketing) {

            $totalAll =
                $marketing['stok_gudang_1'] +
                $marketing['stok_gudang_3'] +
                $marketing['stok_gudang_2'] +
                $marketing['stok_gudang_10'] +
                $marketing['stok_gudang_6'] +
                $marketing['stok_gudang_11'] +
                $marketing['stok_gudang_7'] +
                $marketing['stok_gudang_25'] +
                $marketing['stok_gudang_29'] +
                $marketing['stok_gudang_23'] +
                $marketing['stok_gudang_26'] +
                $marketing['stok_gudang_30'] +
                $marketing['stok_gudang_5'] +
                $marketing['stok_gudang_27'] +
                $marketing['stok_gudang_31'] +
                $marketing['stok_gudang_24'] +
                $marketing['stok_gudang_28'] +
                $marketing['stok_gudang_32'] +
                $marketing['stok_gudang_4'];



            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $marketing['nama_barang'])
                ->setCellValue('C' . $kolom, $marketing['nama_kategori'])
                ->setCellValue('D' . $kolom, $marketing['stok_gudang_1'])
                ->setCellValue('E' . $kolom, $marketing['stok_gudang_3'])
                ->setCellValue('F' . $kolom, $marketing['stok_gudang_2'])
                ->setCellValue('G' . $kolom, $marketing['stok_gudang_10'])
                ->setCellValue('H' . $kolom, $marketing['stok_gudang_6'])
                ->setCellValue('I' . $kolom, $marketing['stok_gudang_11'])
                ->setCellValue('J' . $kolom, $marketing['stok_gudang_7'])
                ->setCellValue('K' . $kolom, $marketing['stok_gudang_25'])
                ->setCellValue('L' . $kolom, $marketing['stok_gudang_29'])
                ->setCellValue('M' . $kolom, $marketing['stok_gudang_23'])
                ->setCellValue('N' . $kolom, $marketing['stok_gudang_26'])
                ->setCellValue('O' . $kolom, $marketing['stok_gudang_30'])
                ->setCellValue('P' . $kolom, $marketing['stok_gudang_5'])
                ->setCellValue('Q' . $kolom, $marketing['stok_gudang_27'])
                ->setCellValue('R' . $kolom, $marketing['stok_gudang_31'])
                ->setCellValue('S' . $kolom, $marketing['stok_gudang_24'])
                ->setCellValue('T' . $kolom, $marketing['stok_gudang_28'])
                ->setCellValue('U' . $kolom, $marketing['stok_gudang_32'])
                ->setCellValue('V' . $kolom, $marketing['stok_gudang_4'])
                ->setCellValue('W' . $kolom, $marketing['stok_gudang_33'])
                ->setCellValue('X' . $kolom, $totalAll);

            $kolom++;
            $nomor++;
        }

        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(55); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(55); // Set width kolom B
        $sheet->getColumnDimension('D')->setWidth(45); // Set width kolom C
        $sheet->getColumnDimension('E')->setWidth(45); // Set width kolom D
        $sheet->getColumnDimension('F')->setWidth(45); // Set width kolom E
        $sheet->getColumnDimension('G')->setWidth(45); // Set width kolom F
        $sheet->getColumnDimension('H')->setWidth(45); // Set width kolom G
        $sheet->getColumnDimension('I')->setWidth(45); // Set width kolom H
        $sheet->getColumnDimension('J')->setWidth(45); // Set width kolom I
        $sheet->getColumnDimension('K')->setWidth(45); // Set width kolom J
        $sheet->getColumnDimension('L')->setWidth(45); // Set width kolom K
        $sheet->getColumnDimension('M')->setWidth(45); // Set width kolom L
        $sheet->getColumnDimension('N')->setWidth(45); // Set width kolom M
        $sheet->getColumnDimension('O')->setWidth(45); // Set width kolom N
        $sheet->getColumnDimension('P')->setWidth(45); // Set width kolom O
        $sheet->getColumnDimension('Q')->setWidth(45); // Set width kolom P
        $sheet->getColumnDimension('R')->setWidth(45); // Set width kolom P
        $sheet->getColumnDimension('S')->setWidth(45); // Set width kolom P
        $sheet->getColumnDimension('T')->setWidth(45); // Set width kolom P
        $sheet->getColumnDimension('U')->setWidth(45); // Set width kolom P
        $sheet->getColumnDimension('V')->setWidth(45); // Set width kolom P
        $sheet->getColumnDimension('W')->setWidth(45); // Set width kolom P
        $sheet->getColumnDimension('X')->setWidth(20); // Set width kolom P

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Data Stock Barang");
        ob_end_clean();
        // Proses file excel
        $today = date('d F Y H-i-s', time());
        $filename = "Data Stock Barang - " . $namaFile . " " . $today . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    /**
     * AJAX Endpoint: Visual Dashboard Data untuk Stok Gudang, Kategori, & Pergerakan In/Out
     */
    public function get_dashboard_stats()
    {
        grantAccessFor('all');

        $tahun = (int)($this->input->get('tahun') ?: date('Y'));
        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = (int)date('Y');
        }

        $id_gudang_raw = $this->input->get('id_gudang');
        $gudang_ids = [];
        $id_gudang = 0;
        if (!empty($id_gudang_raw) && $id_gudang_raw !== 'all' && $id_gudang_raw !== '0') {
            if (is_array($id_gudang_raw)) {
                $rawList = $id_gudang_raw;
            } else {
                $rawList = explode(',', (string)$id_gudang_raw);
            }
            foreach ($rawList as $item) {
                $item = trim((string)$item);
                if ($item === '' || $item === '0' || $item === 'all') continue;
                $decrypted = decrypt($item);
                if (is_numeric($decrypted) && (int)$decrypted > 0) {
                    $gudang_ids[] = (int)$decrypted;
                } elseif (is_numeric($item) && (int)$item > 0) {
                    $gudang_ids[] = (int)$item;
                }
            }
            $gudang_ids = array_values(array_unique($gudang_ids));
            if (count($gudang_ids) === 1) {
                $id_gudang = $gudang_ids[0];
            }
        }

        $id_kategori_raw = $this->input->get('id_kategori');
        $kategori_ids = [];
        $id_kategori = 0;
        if (!empty($id_kategori_raw) && $id_kategori_raw !== 'all' && $id_kategori_raw !== '0') {
            if (is_array($id_kategori_raw)) {
                $rawKatList = $id_kategori_raw;
            } else {
                $rawKatList = explode(',', (string)$id_kategori_raw);
            }
            foreach ($rawKatList as $item) {
                $item = trim((string)$item);
                if ($item === '' || $item === '0' || $item === 'all') continue;
                $decryptedKat = decrypt($item);
                if (is_numeric($decryptedKat) && (int)$decryptedKat > 0) {
                    $kategori_ids[] = (int)$decryptedKat;
                } elseif (is_numeric($item) && (int)$item > 0) {
                    $kategori_ids[] = (int)$item;
                }
            }
            $kategori_ids = array_values(array_unique($kategori_ids));
            if (count($kategori_ids) === 1) {
                $id_kategori = $kategori_ids[0];
            }
        }

        $id_barang_raw = $this->input->get('id_barang');
        $barang_ids = [];
        if (!empty($id_barang_raw) && $id_barang_raw !== 'all' && $id_barang_raw !== '0') {
            if (is_array($id_barang_raw)) {
                $rawBList = $id_barang_raw;
            } else {
                $rawBList = explode(',', (string)$id_barang_raw);
            }
            foreach ($rawBList as $item) {
                $item = trim((string)$item);
                if ($item === '' || $item === '0' || $item === 'all') continue;
                if (is_numeric($item) && (int)$item > 0) {
                    $barang_ids[] = (int)$item;
                }
            }
            $barang_ids = array_values(array_unique($barang_ids));
        }

        $nama_barang = trim((string)$this->input->get('nama_barang', TRUE));

        // 1. KPI Stok Tersedia & Total SKU
        $this->db->select('COALESCE(SUM(db.current_stock), 0) as total_stok, COUNT(DISTINCT db.id_barang) as total_sku')
            ->from('detail_barang db')
            ->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang AND pb.status = 1')
            ->join('barang b', 'b.id_barang = db.id_barang AND b.status = 1')
            ->where('db.status', 1)
            ->where('db.current_stock >', 0);
        if (!empty($gudang_ids)) {
            $this->db->where_in('pb.id_gudang', $gudang_ids);
        } elseif ($id_gudang > 0) {
            $this->db->where('pb.id_gudang', $id_gudang);
        }
        if (!empty($kategori_ids)) {
            $this->db->where_in('b.id_kategori', $kategori_ids);
        } elseif ($id_kategori > 0) {
            $this->db->where('b.id_kategori', $id_kategori);
        }
        if (!empty($barang_ids)) {
            $this->db->where_in('b.id_barang', $barang_ids);
        } elseif ($nama_barang !== '') {
            $this->db->group_start()
                ->like('b.nama_barang', $nama_barang)
                ->or_like('b.kode_barang', $nama_barang)
                ->group_end();
        }
        $kpi_stok = $this->db->get()->row();

        // Total Inbound (Stok Masuk Tahun Ini)
        $this->db->select('COALESCE(SUM(db.qty), 0) as total_qty, COUNT(DISTINCT pb.id_penerimaan_barang) as total_trx')
            ->from('penerimaan_barang pb')
            ->join('detail_barang db', 'db.id_penerimaan_barang = pb.id_penerimaan_barang AND db.status = 1')
            ->join('barang b', 'b.id_barang = db.id_barang', 'left')
            ->where('pb.status', 1)
            ->where('YEAR(pb.tgl_masuk)', $tahun);
        if (!empty($gudang_ids)) {
            $this->db->where_in('pb.id_gudang', $gudang_ids);
        } elseif ($id_gudang > 0) {
            $this->db->where('pb.id_gudang', $id_gudang);
        }
        if (!empty($kategori_ids)) {
            $this->db->where_in('b.id_kategori', $kategori_ids);
        } elseif ($id_kategori > 0) {
            $this->db->where('b.id_kategori', $id_kategori);
        }
        if (!empty($barang_ids)) {
            $this->db->where_in('b.id_barang', $barang_ids);
        } elseif ($nama_barang !== '') {
            $this->db->group_start()
                ->like('b.nama_barang', $nama_barang)
                ->or_like('b.kode_barang', $nama_barang)
                ->group_end();
        }
        $inbound_year = $this->db->get()->row();

        // Total Outbound (Stok Keluar Tahun Ini)
        $this->db->select('COALESCE(SUM(dbk.qty), 0) as total_qty, COUNT(DISTINCT pb.id_pengeluaran_barang) as total_trx')
            ->from('pengeluaran_barang pb')
            ->join('detail_barang_keluar dbk', 'dbk.id_pengeluaran_barang = pb.id_pengeluaran_barang AND dbk.status = 1')
            ->join('barang b', 'b.id_barang = dbk.id_barang', 'left')
            ->where('pb.status', 1)
            ->where('YEAR(pb.tgl_keluar)', $tahun);
        if (!empty($gudang_ids)) {
            $this->db->where_in('pb.id_gudang', $gudang_ids);
        } elseif ($id_gudang > 0) {
            $this->db->where('pb.id_gudang', $id_gudang);
        }
        if (!empty($kategori_ids)) {
            $this->db->where_in('b.id_kategori', $kategori_ids);
        } elseif ($id_kategori > 0) {
            $this->db->where('b.id_kategori', $id_kategori);
        }
        if (!empty($barang_ids)) {
            $this->db->where_in('b.id_barang', $barang_ids);
        } elseif ($nama_barang !== '') {
            $this->db->group_start()
                ->like('b.nama_barang', $nama_barang)
                ->or_like('b.kode_barang', $nama_barang)
                ->group_end();
        }
        $outbound_year = $this->db->get()->row();

        // 2. Stok Berdasarkan Kategori Barang
        $this->db->select('kb.id_kategori, kb.nama_kategori, COUNT(DISTINCT b.id_barang) as total_sku, COALESCE(SUM(db.current_stock), 0) as total_stok')
            ->from('kategori_barang kb')
            ->join('barang b', 'b.id_kategori = kb.id_kategori AND b.status = 1')
            ->join('detail_barang db', 'db.id_barang = b.id_barang AND db.status = 1 AND db.current_stock > 0')
            ->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang AND pb.status = 1');
        if (!empty($gudang_ids)) {
            $this->db->where_in('pb.id_gudang', $gudang_ids);
        } elseif ($id_gudang > 0) {
            $this->db->where('pb.id_gudang', $id_gudang);
        }
        if (!empty($kategori_ids)) {
            $this->db->where_in('kb.id_kategori', $kategori_ids);
        } elseif ($id_kategori > 0) {
            $this->db->where('kb.id_kategori', $id_kategori);
        }
        if ($nama_barang !== '') {
            $this->db->group_start()
                ->like('b.nama_barang', $nama_barang)
                ->or_like('b.kode_barang', $nama_barang)
                ->group_end();
        }
        $this->db->group_by(['kb.id_kategori', 'kb.nama_kategori'])
            ->having('total_stok >', 0)
            ->order_by('total_stok', 'DESC');
        $kategori_stok = $this->db->get()->result();

        // 3. Stok Berdasarkan Gudang Penyimpanan (Hanya gudang yang dicentang / semua jika tidak ada filter)
        $extraGudangJoinFilter = "";
        if (!empty($kategori_ids)) {
            $katIdListStr = implode(',', array_map('intval', $kategori_ids));
            $extraGudangJoinFilter .= " AND b.id_kategori IN ({$katIdListStr})";
        } elseif ($id_kategori > 0) {
            $extraGudangJoinFilter .= " AND b.id_kategori = " . (int)$id_kategori;
        }
        if (!empty($barang_ids)) {
            $bIdListStr = implode(',', array_map('intval', $barang_ids));
            $extraGudangJoinFilter .= " AND b.id_barang IN ({$bIdListStr})";
        } elseif ($nama_barang !== '') {
            $escLike = $this->db->escape_like_str($nama_barang);
            $extraGudangJoinFilter .= " AND (b.nama_barang LIKE '%{$escLike}%' OR b.kode_barang LIKE '%{$escLike}%')";
        }

        $extraGudangWhere = "";
        if (!empty($gudang_ids)) {
            $idListStr = implode(',', array_map('intval', $gudang_ids));
            $extraGudangWhere = " AND g.id_gudang IN ({$idListStr})";
        } elseif ($id_gudang > 0) {
            $extraGudangWhere = " AND g.id_gudang = " . (int)$id_gudang;
        }

        $gudang_stok = $this->db->query("
            SELECT 
                g.id_gudang,
                g.nama_gudang,
                g.lokasi,
                g.jenis,
                COUNT(DISTINCT CASE WHEN b.id_barang IS NOT NULL THEN db.id_barang ELSE NULL END) as total_sku,
                COALESCE(SUM(CASE WHEN b.id_barang IS NOT NULL THEN db.current_stock ELSE 0 END), 0) as total_stok
            FROM gudang g
            LEFT JOIN penerimaan_barang pb ON pb.id_gudang = g.id_gudang AND pb.status = 1
            LEFT JOIN detail_barang db ON db.id_penerimaan_barang = pb.id_penerimaan_barang AND db.status = 1 AND db.current_stock > 0
            LEFT JOIN barang b ON b.id_barang = db.id_barang AND b.status = 1 {$extraGudangJoinFilter}
            WHERE g.status = 1 {$extraGudangWhere}
            GROUP BY g.id_gudang, g.nama_gudang, g.lokasi, g.jenis
            ORDER BY total_stok DESC, g.nama_gudang ASC
        ")->result();

        // 4. Pergerakan Stok Masuk vs Keluar Bulanan (Tahun Terpilih)
        $this->db->select('MONTH(pb.tgl_masuk) as bulan, COUNT(DISTINCT pb.id_penerimaan_barang) as total_trx, COALESCE(SUM(db.qty), 0) as total_qty')
            ->from('penerimaan_barang pb')
            ->join('detail_barang db', 'db.id_penerimaan_barang = pb.id_penerimaan_barang AND db.status = 1')
            ->join('barang b', 'b.id_barang = db.id_barang', 'left')
            ->where('pb.status', 1)
            ->where('YEAR(pb.tgl_masuk)', $tahun);
        if (!empty($gudang_ids)) {
            $this->db->where_in('pb.id_gudang', $gudang_ids);
        } elseif ($id_gudang > 0) {
            $this->db->where('pb.id_gudang', $id_gudang);
        }
        if (!empty($kategori_ids)) {
            $this->db->where_in('b.id_kategori', $kategori_ids);
        } elseif ($id_kategori > 0) {
            $this->db->where('b.id_kategori', $id_kategori);
        }
        if (!empty($barang_ids)) {
            $this->db->where_in('b.id_barang', $barang_ids);
        } elseif ($nama_barang !== '') {
            $this->db->group_start()
                ->like('b.nama_barang', $nama_barang)
                ->or_like('b.kode_barang', $nama_barang)
                ->group_end();
        }
        $inbound_monthly = $this->db->group_by('MONTH(pb.tgl_masuk)')->get()->result();

        $this->db->select('MONTH(pb.tgl_keluar) as bulan, COUNT(DISTINCT pb.id_pengeluaran_barang) as total_trx, COALESCE(SUM(dbk.qty), 0) as total_qty')
            ->from('pengeluaran_barang pb')
            ->join('detail_barang_keluar dbk', 'dbk.id_pengeluaran_barang = pb.id_pengeluaran_barang AND dbk.status = 1')
            ->join('barang b', 'b.id_barang = dbk.id_barang', 'left')
            ->where('pb.status', 1)
            ->where('YEAR(pb.tgl_keluar)', $tahun);
        if (!empty($gudang_ids)) {
            $this->db->where_in('pb.id_gudang', $gudang_ids);
        } elseif ($id_gudang > 0) {
            $this->db->where('pb.id_gudang', $id_gudang);
        }
        if (!empty($kategori_ids)) {
            $this->db->where_in('b.id_kategori', $kategori_ids);
        } elseif ($id_kategori > 0) {
            $this->db->where('b.id_kategori', $id_kategori);
        }
        if (!empty($barang_ids)) {
            $this->db->where_in('b.id_barang', $barang_ids);
        } elseif ($nama_barang !== '') {
            $this->db->group_start()
                ->like('b.nama_barang', $nama_barang)
                ->or_like('b.kode_barang', $nama_barang)
                ->group_end();
        }
        $outbound_monthly = $this->db->group_by('MONTH(pb.tgl_keluar)')->get()->result();

        $bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $movement = [];
        for ($m = 1; $m <= 12; $m++) {
            $movement[$m] = [
                'bulan'       => $m,
                'nama_bulan'  => $bulanNames[$m - 1],
                'masuk_qty'   => 0,
                'masuk_trx'   => 0,
                'keluar_qty'  => 0,
                'keluar_trx'  => 0
            ];
        }
        foreach ($inbound_monthly as $row) {
            $b = (int)$row->bulan;
            if (isset($movement[$b])) {
                $movement[$b]['masuk_qty'] = (int)$row->total_qty;
                $movement[$b]['masuk_trx'] = (int)$row->total_trx;
            }
        }
        foreach ($outbound_monthly as $row) {
            $b = (int)$row->bulan;
            if (isset($movement[$b])) {
                $movement[$b]['keluar_qty'] = (int)$row->total_qty;
                $movement[$b]['keluar_trx'] = (int)$row->total_trx;
            }
        }

        // 5. Top 10 Barang Terbanyak Berdasarkan Stok
        $this->db->select('b.id_barang, b.kode_barang, b.nama_barang, kb.nama_kategori, sb.nama_satuan, COALESCE(SUM(db.current_stock), 0) as stok')
            ->from('barang b')
            ->join('detail_barang db', 'db.id_barang = b.id_barang AND db.status = 1 AND db.current_stock > 0')
            ->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang AND pb.status = 1')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->where('b.status', 1);
        if (!empty($gudang_ids)) {
            $this->db->where_in('pb.id_gudang', $gudang_ids);
        } elseif ($id_gudang > 0) {
            $this->db->where('pb.id_gudang', $id_gudang);
        }
        if (!empty($kategori_ids)) {
            $this->db->where_in('b.id_kategori', $kategori_ids);
        } elseif ($id_kategori > 0) {
            $this->db->where('b.id_kategori', $id_kategori);
        }
        if (!empty($barang_ids)) {
            $this->db->where_in('b.id_barang', $barang_ids);
        } elseif ($nama_barang !== '') {
            $this->db->group_start()
                ->like('b.nama_barang', $nama_barang)
                ->or_like('b.kode_barang', $nama_barang)
                ->group_end();
        }
        $top_barang = $this->db->group_by(['b.id_barang', 'b.kode_barang', 'b.nama_barang', 'kb.nama_kategori', 'sb.nama_satuan'])
            ->having('stok >', 0)
            ->order_by('stok', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // 6. Daftar saran Nama Barang untuk autocomplete & multi-filter (diurutkan stok > 0 di atas)
        $this->db->select('b.id_barang, b.nama_barang, b.kode_barang, COALESCE(SUM(db.current_stock), 0) as stok')
            ->from('barang b')
            ->join('detail_barang db', 'db.id_barang = b.id_barang AND db.status = 1 AND db.current_stock > 0', 'left')
            ->where('b.status', 1);
        if (!empty($kategori_ids)) {
            $this->db->where_in('b.id_kategori', $kategori_ids);
        } elseif ($id_kategori > 0) {
            $this->db->where('b.id_kategori', $id_kategori);
        }
        $barang_options = $this->db->group_by(['b.id_barang', 'b.nama_barang', 'b.kode_barang'])
            ->order_by('stok', 'DESC')
            ->order_by('b.nama_barang', 'ASC')
            ->limit(250)
            ->get()
            ->result();

        $response = [
            'status' => 'success',
            'tahun' => $tahun,
            'id_gudang' => !empty($gudang_ids) ? implode(',', $gudang_ids) : ($id_gudang > 0 ? (string)$id_gudang : '0'),
            'gudang_ids' => $gudang_ids,
            'id_kategori' => !empty($kategori_ids) ? implode(',', $kategori_ids) : ($id_kategori > 0 ? (string)$id_kategori : '0'),
            'kategori_ids' => $kategori_ids,
            'id_barang' => !empty($barang_ids) ? implode(',', $barang_ids) : '',
            'barang_ids' => $barang_ids,
            'nama_barang' => $nama_barang,
            'kpi' => [
                'total_stok' => (int)($kpi_stok->total_stok ?? 0),
                'total_sku' => (int)($kpi_stok->total_sku ?? 0),
                'masuk_qty' => (int)($inbound_year->total_qty ?? 0),
                'masuk_trx' => (int)($inbound_year->total_trx ?? 0),
                'keluar_qty' => (int)($outbound_year->total_qty ?? 0),
                'keluar_trx' => (int)($outbound_year->total_trx ?? 0),
            ],
            'kategori_stok' => $kategori_stok,
            'gudang_stok' => $gudang_stok,
            'movement' => array_values($movement),
            'top_barang' => $top_barang,
            'barang_options' => $barang_options
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
