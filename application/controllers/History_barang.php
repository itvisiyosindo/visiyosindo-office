<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class History_barang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
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

        
        $page_data['idbarang']      = $this->input->get('id_barang');
        $page_data['tglawal']       = $this->input->get('tglawal');
        $page_data['tglakhir']      = $this->input->get('tglakhir');
        //checkEmptyForm($page_data);

        $page_data['switch']      	= $this->id_navbar();
		$page_data['page_name']     = 'history_barang/v_history_barang';
        $page_data['page_title']    = 'History Barang';
        $page_data['page_desc']     = 'Management Data Penerimaan & Pengeluaran Barang';
        $page_data['list_data']     = $this->md_history_barang->getAllHistoryByTGL(decrypt($this->input->get('id_barang')),$this->input->get('tglawal'),$this->input->get('tglakhir'));
        $page_data['data_barang']   = $this->md_barang->getByWhere(['b.id_barang' => decrypt($this->input->get('id_barang'))]);
        $this->load->view('index', $page_data);
    }


    public function coba()
    {
        grantAccessFor('all');

        
        $page_data['idbarang']      = $this->input->get('id_barang');
        $page_data['tglawal']       = $this->input->get('tglawal');
        $page_data['tglakhir']      = $this->input->get('tglakhir');
        //checkEmptyForm($page_data);

        $page_data['switch']      	= $this->id_navbar();
		$page_data['page_name']     = 'history_barang/v_history_barang_2';
        $page_data['page_title']    = 'History Barang';
        $page_data['page_desc']     = 'Management Data Penerimaan & Pengeluaran Barang';
        $page_data['list_data']     = $this->md_history_barang->getAllHistoryByTGL(decrypt($this->input->get('id_barang')),$this->input->get('tglawal'),$this->input->get('tglakhir'));
        $page_data['data_barang']   = $this->md_barang->getByWhere(['b.id_barang' => decrypt($this->input->get('id_barang'))]);
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



    public function exportlaporan()
     {
        $idbarang       = decrypt($this->input->get('id_barang'));
        $data_barang    = $this->md_barang->getByWhere(['b.id_barang' => $idbarang]);
        $data           = $this->md_history_barang->getAllHistoryByTGL($idbarang,$this->input->get('tglawal'),$this->input->get('tglakhir'));

        $tglawal        = $this->input->get('tglawal');
        $tglakhir       = $this->input->get('tglakhir');

            //$data = $this->md_history_barang->getAllHistoryByTGL();

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
            
          $sheet->setCellValue('A1', "Nama Barang : ". $data_barang[0]->nama_barang); // Set kolom A1 dengan tulisan "DATA SISWA"
          $sheet->setCellValue('A2', "Periode : ". date('d-m-Y',strtotime($tglawal)) ." s/d ".  date('d-m-Y',strtotime($tglakhir))); // Set kolom A1 dengan tulisan "DATA SISWA"
          $sheet->mergeCells('A1:L1'); // Set Merge Cell pada kolom A1 sampai E1
          $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
            
                    // Buat header tabel nya pada baris ke 3
                    $sheet->setCellValue('A4', 'No');
                    $sheet->setCellValue('B4', 'Tanggal');
                    $sheet->setCellValue('C4', 'Nomor');
                    $sheet->setCellValue('D4', 'Customer / Pemasok');
                    $sheet->setCellValue('E4', 'Tipe Transaksi');
                    //$sheet->setCellValue('E4', 'Nama Barang');
                    $sheet->setCellValue('F4', 'No Batch');
                    $sheet->setCellValue('G4', 'Exp Date');
                    $sheet->setCellValue('H4', 'Gudang');
                    $sheet->setCellValue('I4', 'Jumlah');
                    
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


           $kolom = 5;
           $nomor = 1;
           
           // Assuming you have the $spreadsheet object and $kolom, $nomor variables initialized
            // Assuming you have the $spreadsheet object and $kolom, $nomor variables initialized
            foreach ($data as $row) {

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
                    // Baris Pengeluaran Barang
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, $nomor)
                        ->setCellValue('B' . $kolom, $row->tgl_keluar)          // Tanggal keluar
                        ->setCellValue('C' . $kolom, $row->no_pengiriman)      // No pengiriman
                        ->setCellValue('D' . $kolom, $row->nama_customer)      // No pengiriman
                        ->setCellValue('E' . $kolom, $pengeluaran_kategori)     // Kategori Pengeluaran Barang
                        //->setCellValue('E' . $kolom, $row->nama_barang_exit)   // Nama barang
                        ->setCellValue('F' . $kolom, $row->no_batch_exit)      // No batch
                        ->setCellValue('G' . $kolom, $row->exp_date_exit)      // Exp date
                        ->setCellValue('H' . $kolom, $row->nama_gudang_exit)   // Gudang tujuan
                        ->setCellValue('I' . $kolom, $row->qty_exit);          // Kuantitas

                    $kolom++;
                    $nomor++;
                }

                // Jika ada data untuk Penerimaan Stok atau Pengiriman Stok
                if ($penerimaan_stok_kategori == 'Penerimaan Stok' && $pengiriman_stok_kategori == 'Pengiriman Stok') {
                    // Baris pertama: Penerimaan Stok
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, $nomor)
                        ->setCellValue('B' . $kolom, $row->tgl_masuk)         // Tanggal masuk
                        ->setCellValue('C' . $kolom, $row->no_terima)         // No terima
                        ->setCellValue('D' . $kolom, "")         // No terima
                        ->setCellValue('E' . $kolom, $penerimaan_stok_kategori) // Kategori Penerimaan Stok
                        //->setCellValue('E' . $kolom, $row->nama_barang)       // Nama barang
                        ->setCellValue('F' . $kolom, $row->no_batch)          // No batch
                        ->setCellValue('G' . $kolom, $row->exp_date)          // Exp date
                        ->setCellValue('H' . $kolom, $row->nama_gudang)       // Gudang asal
                        ->setCellValue('I' . $kolom, $row->qty);              // Kuantitas

                    $kolom++;

                    // Baris kedua: Pengiriman Stok
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, $nomor)
                        ->setCellValue('B' . $kolom, $row->tgl_masuk)         // Tanggal masuk
                        ->setCellValue('C' . $kolom, $row->no_terima)         // No terima
                        ->setCellValue('D' . $kolom, "")         // No terima
                        ->setCellValue('E' . $kolom, $pengiriman_stok_kategori) // Kategori Pengiriman Stok
                        //->setCellValue('E' . $kolom, $row->nama_barang)       // Nama barang
                        ->setCellValue('F' . $kolom, $row->no_batch)          // No batch
                        ->setCellValue('G' . $kolom, $row->exp_date)          // Exp date
                        ->setCellValue('H' . $kolom, $row->gudang_asal)       // Gudang asal
                        ->setCellValue('I' . $kolom, $row->qty);              // Kuantitas

                    $kolom++;
                    $nomor++;
                } elseif ($penerimaan_stok_kategori || $pengiriman_stok_kategori) {
                    // Jika hanya ada salah satu kategori
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, $nomor)
                        ->setCellValue('B' . $kolom, $row->tgl_masuk)         // Tanggal masuk
                        ->setCellValue('C' . $kolom, $row->no_terima)         // No terima
                        ->setCellValue('D' . $kolom, $row->nama_pemasok)         // No terima
                        ->setCellValue('E' . $kolom, $penerimaan_stok_kategori ?: $pengiriman_stok_kategori) // Kategori
                        //->setCellValue('E' . $kolom, $row->nama_barang)       // Nama barang
                        ->setCellValue('F' . $kolom, $row->no_batch)          // No batch
                        ->setCellValue('G' . $kolom, $row->exp_date)          // Exp date
                        ->setCellValue('H' . $kolom, $row->nama_gudang)       // Gudang asal
                        ->setCellValue('I' . $kolom, $row->qty);              // Kuantitas

                    $kolom++;
                    $nomor++;
                }
            }



            // Set width kolom
            $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
            $sheet->getColumnDimension('B')->setWidth(25); // Set width kolom B
            $sheet->getColumnDimension('C')->setWidth(35); // Set width kolom C
            $sheet->getColumnDimension('D')->setWidth(50); // Set width kolom D
            $sheet->getColumnDimension('E')->setWidth(30); // Set width kolom D
            $sheet->getColumnDimension('F')->setWidth(30); // Set width kolom E
            $sheet->getColumnDimension('G')->setWidth(25); // Set width kolom F
            $sheet->getColumnDimension('H')->setWidth(50); // Set width kolom G
            $sheet->getColumnDimension('I')->setWidth(20); // Set width kolom H
            //$sheet->getColumnDimension('I')->setWidth(25); // Set width kolom I
            
            // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
            $sheet->getDefaultRowDimension()->setRowHeight(-1);
            // Set orientasi kertas jadi LANDSCAPE
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            // Set judul file excel nya
            $sheet->setTitle("Data History Barang");
            ob_end_clean();
            // Proses file excel
            $filename = "Data History Barang.xlsx";
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename='.$filename);
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            
            
    }


    



}
