<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


defined('BASEPATH') or exit('No direct script access allowed');

class Kalkulator extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_kalkulator');
        $this->load->model('md_kategori_barang');
        $this->load->model('md_satuan_barang');
        $this->load->model('md_cabang');
        $this->load->model('md_pemasok_utama');
        $this->load->model('md_satuan_barang');
        $this->load->model('md_tarif_pajak');
        $this->load->model('md_barang');
        // $this->load->model('md_kode_barang');
        $this->load->model('md_detail_barang');
        // $this->load->helper('download');
    }
	
	function id_navbar(){
		$id_navbar = "marketing";
		return $id_navbar;
	}


//======================================
//======================================
//===========    SWASTA    =============
//======================================
//======================================
    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['filter_merk']   = $this->md_kalkulator->getFilterMerkSwasta();
		$page_data['filter_nama']   = $this->md_kalkulator->getFilterNamaSwasta();
        $page_data['page_name']  = 'kalkulator/v_kalkulator';
        $page_data['page_title'] = 'Kalkulator Perhitungan Price List';
        $page_data['page_desc']  = 'SWASTA';
        $this->load->view('index', $page_data);
    }

    
    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_kalkulator->getAll();
        $start = $this->input->post('start');
        $data  = array();
        $diskon_input = $this->input->post('diskon');
        $diskon_persen = is_numeric($diskon_input) ? floatval($diskon_input) : 0;
        $komisi_badan = $this->input->post('komisi_badan');
        $komisi_badan_persen = is_numeric($komisi_badan) ? floatval($komisi_badan) : 0;
        $komisi_pribadi = $this->input->post('komisi_pribadi');
        $komisi_pribadi_persen = is_numeric($komisi_pribadi) ? floatval($komisi_pribadi) : 0;
        $komisi_npwp = $this->input->post('komisi_npwp');
        $komisi_npwp_persen = is_numeric($komisi_npwp) ? floatval($komisi_npwp) : 0;

        foreach ($dt['data'] as $row) {
            $id     = encrypt($row->id);
            $harga  = is_numeric($row->harga) ? $row->harga : 0;
            $acuan  = $harga - ($harga * 40 / 100);
            if (is_numeric($diskon_persen) && $diskon_persen > 0) {
                $diskon_nominal = $harga - ($harga * $diskon_persen / 100);
            } else {
                $diskon_nominal = $harga;
            }

            $komisi_badan_nominal = (($diskon_nominal / 1.11) * ($komisi_badan_persen / 100)) * (98 / 100);
            $komisi_pribadi_nominal = (($diskon_nominal / 1.11) * ($komisi_pribadi_persen / 100)) * (97.5 / 100);
            $komisi_npwp_nominal = (($diskon_nominal / 1.11) * ($komisi_npwp_persen / 100)) * (94 / 100);


            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->merk;
            $th[] = $row->nama;
            $th[] = 'Rp ' . number_format($harga, 0, ',', '.');
            $th[] = 'Rp ' . number_format($acuan, 0, ',', '.');
            $th[] = 'Rp ' . number_format($diskon_nominal, 0, ',', '.'); // hasil diskon
            $th[] = 'Rp ' . number_format($komisi_badan_nominal, 0, ',', '.'); // hasil Komisi Badan
            $th[] = 'Rp ' . number_format($komisi_pribadi_nominal, 0, ',', '.'); // hasil Komisi Pribadi
            $th[] = 'Rp ' . number_format($komisi_npwp_nominal, 0, ',', '.'); // hasil Komisi tanpa NPWP
            $data[] = $th;
        }

        $dt['data'] = $data;
        echo json_encode($dt);
        die;
        //log_message('error', print_r($dt, true)); // debug hasil akhir

    }



    public function import() {
        $file_mimes = array(
            'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv',
            'text/x-csv', 'text/csv', 'application/csv', 'application/excel',
            'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        if (isset($_FILES['berkas_excel']['name']) && in_array($_FILES['berkas_excel']['type'], $file_mimes)) {
            $arr_file = explode('.', $_FILES['berkas_excel']['name']);
            $extension = end($arr_file);

            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['berkas_excel']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            // Hapus data sebelumnya dengan jenis = 1
            $this->db->where('jenis', 1);
            $this->db->delete('kalkulator_pricelist');

            // Proses import data baru
            for ($i = 2; $i < count($sheetData); $i++) {
                $data['merk']  = isset($sheetData[$i][2]) ? $sheetData[$i][2] : '';
                $data['nama']  = isset($sheetData[$i][3]) ? $sheetData[$i][3] : '';
                $data['harga'] = isset($sheetData[$i][4]) ? $sheetData[$i][4] : '';
                $data['jenis'] = '1'; // Data baru dengan jenis = 1

                // Jika nama kosong, skip data tersebut
                if (empty($data['nama'])) {
                    continue;
                }

                // Insert data baru
                $this->md_kalkulator->add($data);
            }

            // Log activity
            addlog('Edit Master Data', 'Melakukan Import Price List SWASTA');

            // Redirect ke halaman kalkulator
            redirect('/kalkulator');
        }
    }


    public function export_swasta()
     {
        
         
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
            
          $sheet->setCellValue('B1', "Format Data Price List SWASTA"); // Set kolom A1 dengan tulisan "DATA SISWA"
          $sheet->mergeCells('B1:E1'); // Set Merge Cell pada kolom A1 sampai E1
          $sheet->getStyle('B1')->getFont()->setBold(true); // Set bold kolom A1
            
                    // Buat header tabel nya pada baris ke 3
                    $sheet->setCellValue('B2', 'NO');
                    $sheet->setCellValue('C2', 'Merk Product');
                    $sheet->setCellValue('D2', 'Nama Product');
                    $sheet->setCellValue('E2', 'Pricelist (WAJIB NUMBER Tanpa Titik dan Koma)');
                    
                     // Apply style header yang telah kita buat tadi ke masing-masing kolom header
                    $sheet->getStyle('B2')->applyFromArray($style_col);
                    $sheet->getStyle('C2')->applyFromArray($style_col);
                    $sheet->getStyle('D2')->applyFromArray($style_col);
                    $sheet->getStyle('E2')->applyFromArray($style_col);

                    // Buat header tabel nya pada baris ke 3
                    $sheet->setCellValue('B3', '1');
                    $sheet->setCellValue('C3', 'IATOME');
                    $sheet->setCellValue('D3', 'Mobile Xray Alerio Smart 4000 (100 mA)');
                    $sheet->setCellValue('E3', '337273333');
                    $sheet->setCellValue('F3', 'INI DATA CONTOH');
                    
                     // Apply style header yang telah kita buat tadi ke masing-masing kolom header
                    $sheet->getStyle('B3')->applyFromArray($style_row);
                    $sheet->getStyle('C3')->applyFromArray($style_row);
                    $sheet->getStyle('D3')->applyFromArray($style_row);
                    $sheet->getStyle('E3')->applyFromArray($style_row);

                    

            // Set width kolom
            $sheet->getColumnDimension('A')->setWidth(2); // Set width kolom B
            $sheet->getColumnDimension('B')->setWidth(10); // Set width kolom C
            $sheet->getColumnDimension('C')->setWidth(30); // Set width kolom D
            $sheet->getColumnDimension('D')->setWidth(50); // Set width kolom E
            $sheet->getColumnDimension('E')->setWidth(50); // Set width kolom E
            $sheet->getColumnDimension('F')->setWidth(50); // Set width kolom E
            
            // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
            $sheet->getDefaultRowDimension()->setRowHeight(-1);
            // Set orientasi kertas jadi LANDSCAPE
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            // Set judul file excel nya
            $sheet->setTitle("Format Data Price List SWASTA");
            ob_end_clean();
            // Proses file excel
            $filename = "Format Data Price List SWASTA.xlsx";
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename='.$filename);
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            
            $aksi = 'Edit Master Data';
            $ket = 'Melakukan Eksport Format Data Price List SWASTA';
            addlog($aksi, $ket);
            
    }

    public function print_excel_swasta()
    {
        grantAccessFor('all');

        $filter_merk = $this->input->get_post('filter_merk', TRUE) ?: '';
        $filter_nama = $this->input->get_post('filter_nama', TRUE) ?: '';
        $keyword = $this->input->get_post('search', TRUE) ?: '';

        $diskon_input = $this->input->get_post('diskon', TRUE);
        $diskon_persen = is_numeric($diskon_input) ? floatval($diskon_input) : 0;

        $komisi_badan = $this->input->get_post('komisi_badan', TRUE);
        $komisi_badan_persen = is_numeric($komisi_badan) ? floatval($komisi_badan) : 0;

        $komisi_pribadi = $this->input->get_post('komisi_pribadi', TRUE);
        $komisi_pribadi_persen = is_numeric($komisi_pribadi) ? floatval($komisi_pribadi) : 0;

        $komisi_npwp = $this->input->get_post('komisi_npwp', TRUE);
        $komisi_npwp_persen = is_numeric($komisi_npwp) ? floatval($komisi_npwp) : 0;

        $list = $this->md_kalkulator->getDataSwasta($filter_merk, $filter_nama, $keyword);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul Utama
        $sheet->setCellValue('A1', 'DAFTAR PRICE LIST & PERHITUNGAN HARGA (SWASTA)');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'PT VISI YOSINDO MEDIKAL');
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Keterangan Parameter & Waktu Cetak
        $params_info = "Dicetak: " . date('d-m-Y H:i') . " | Diskon: {$diskon_persen}% | Komisi NPWP Badan: {$komisi_badan_persen}% | Komisi NPWP Pribadi: {$komisi_pribadi_persen}% | Komisi Tanpa NPWP: {$komisi_npwp_persen}%";
        if (!empty($filter_merk)) {
            $params_info .= " | Merk: " . $filter_merk;
        }
        if (!empty($filter_nama)) {
            $params_info .= " | Produk: " . $filter_nama;
        }
        $sheet->setCellValue('A3', $params_info);
        $sheet->mergeCells('A3:I3');
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header Tabel
        $header_row = 5;
        $headers = [
            'A' => 'NO',
            'B' => 'MERK',
            'C' => 'NAMA PRODUCT',
            'D' => 'PRICELIST',
            'E' => 'HARGA ACUAN TERENDAH (-40%)',
            'F' => ($diskon_persen > 0 ? "HASIL DISKON ({$diskon_persen}%)" : 'HASIL DISKON'),
            'G' => ($komisi_badan_persen > 0 ? "KOMISI NPWP BADAN ({$komisi_badan_persen}%)" : 'KOMISI NPWP BADAN'),
            'H' => ($komisi_pribadi_persen > 0 ? "KOMISI NPWP PRIBADI ({$komisi_pribadi_persen}%)" : 'KOMISI NPWP PRIBADI'),
            'I' => ($komisi_npwp_persen > 0 ? "KOMISI TANPA NPWP ({$komisi_npwp_persen}%)" : 'KOMISI TANPA NPWP')
        ];

        $style_header = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1F497D']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]
            ]
        ];

        foreach ($headers as $col => $text) {
            $sheet->setCellValue($col . $header_row, $text);
            $sheet->getStyle($col . $header_row)->applyFromArray($style_header);
        }
        $sheet->getRowDimension($header_row)->setRowHeight(28);

        $style_data_border = [
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FFD9D9D9']]
            ]
        ];

        $curr_row = 6;
        $no = 1;

        foreach ($list as $row) {
            $harga = is_numeric($row->harga) ? floatval($row->harga) : 0;
            $acuan = $harga - ($harga * 0.40);
            $diskon_nominal = ($diskon_persen > 0) ? ($harga - ($harga * $diskon_persen / 100)) : $harga;
            $komisi_badan_nominal = (($diskon_nominal / 1.11) * ($komisi_badan_persen / 100)) * 0.98;
            $komisi_pribadi_nominal = (($diskon_nominal / 1.11) * ($komisi_pribadi_persen / 100)) * 0.975;
            $komisi_npwp_nominal = (($diskon_nominal / 1.11) * ($komisi_npwp_persen / 100)) * 0.94;

            $sheet->setCellValue('A' . $curr_row, $no++);
            $sheet->setCellValue('B' . $curr_row, $row->merk);
            $sheet->setCellValue('C' . $curr_row, $row->nama);
            $sheet->setCellValue('D' . $curr_row, $harga);
            $sheet->setCellValue('E' . $curr_row, $acuan);
            $sheet->setCellValue('F' . $curr_row, $diskon_nominal);
            $sheet->setCellValue('G' . $curr_row, $komisi_badan_nominal);
            $sheet->setCellValue('H' . $curr_row, $komisi_pribadi_nominal);
            $sheet->setCellValue('I' . $curr_row, $komisi_npwp_nominal);

            $sheet->getStyle('A' . $curr_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $curr_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('C' . $curr_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            // Number formats
            $sheet->getStyle('D' . $curr_row . ':I' . $curr_row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('D' . $curr_row . ':I' . $curr_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $sheet->getStyle('A' . $curr_row . ':I' . $curr_row)->applyFromArray($style_data_border);

            // Zebra striping
            if ($no % 2 == 0) {
                $sheet->getStyle('A' . $curr_row . ':I' . $curr_row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2F5F9');
            }

            $curr_row++;
        }

        // Set width auto
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->setTitle('Price List SWASTA');

        if (ob_get_length()) {
            ob_end_clean();
        }

        $filename = 'Pricelist_Swasta_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        addlog('Export Data', 'Melakukan Print / Export Excel Price List SWASTA');
        exit;
    }


//======================================
//======================================
//===========    GOVERNMENT    =========
//======================================
//======================================

    public function gov()
    {
        grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['filter_merk']   = $this->md_kalkulator->getFilterMerkGov();
		$page_data['filter_nama']   = $this->md_kalkulator->getFilterNamaGov();
        $page_data['page_name']  = 'kalkulator/v_kalkulator_gov';
        $page_data['page_title'] = 'Kalkulator Perhitungan Price List';
        $page_data['page_desc']  = 'GOVERNMENT';
        $this->load->view('index', $page_data);
    }

    
    public function pagination_gov()
    {
        grantAccessFor('all');

        $dt    = $this->md_kalkulator->getAllGov();
        $start = $this->input->post('start');
        $data  = array();
        $komisi_badan = $this->input->post('komisi_badan');
        $komisi_badan_persen = is_numeric($komisi_badan) ? floatval($komisi_badan) : 0;
        $komisi_pribadi = $this->input->post('komisi_pribadi');
        $komisi_pribadi_persen = is_numeric($komisi_pribadi) ? floatval($komisi_pribadi) : 0;
        $komisi_npwp = $this->input->post('komisi_npwp');
        $komisi_npwp_persen = is_numeric($komisi_npwp) ? floatval($komisi_npwp) : 0;

        foreach ($dt['data'] as $row) {
            $id     = encrypt($row->id);
            $harga  = is_numeric($row->harga) ? $row->harga : 0;
            
            $komisi_badan_nominal = ((($harga / 1.11)-(($harga / 1.11) * 1.5 / 100)) * $komisi_badan_persen / 100) * (98 / 100);
            $komisi_pribadi_nominal = ((($harga / 1.11)-(($harga / 1.11) * 1.5 / 100)) * $komisi_pribadi_persen / 100) * (97.5 / 100);
            $komisi_npwp_nominal = ((($harga / 1.11)-(($harga / 1.11) * 1.5 / 100)) * $komisi_npwp_persen / 100) * (94 / 100);


            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->merk;
            $th[] = $row->nama;
            $th[] = 'Rp ' . number_format($harga, 0, ',', '.');
            $th[] = 'Rp ' . number_format($komisi_badan_nominal, 0, ',', '.'); // hasil Komisi Badan
            $th[] = 'Rp ' . number_format($komisi_pribadi_nominal, 0, ',', '.'); // hasil Komisi Pribadi
            $th[] = 'Rp ' . number_format($komisi_npwp_nominal, 0, ',', '.'); // hasil Komisi tanpa NPWP
            $data[] = $th;
        }

        $dt['data'] = $data;
        echo json_encode($dt);
        die;
        //log_message('error', print_r($dt, true)); // debug hasil akhir

    }



    public function import_gov() {
        $file_mimes = array(
            'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv',
            'text/x-csv', 'text/csv', 'application/csv', 'application/excel',
            'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        if (isset($_FILES['berkas_excel']['name']) && in_array($_FILES['berkas_excel']['type'], $file_mimes)) {
            $arr_file = explode('.', $_FILES['berkas_excel']['name']);
            $extension = end($arr_file);

            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['berkas_excel']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            // Hapus data sebelumnya dengan jenis = 2
            $this->db->where('jenis', 2);
            $this->db->delete('kalkulator_pricelist');

            // Proses import data baru
            for ($i = 2; $i < count($sheetData); $i++) {
                $data['merk']  = isset($sheetData[$i][2]) ? $sheetData[$i][2] : '';
                $data['nama']  = isset($sheetData[$i][3]) ? $sheetData[$i][3] : '';
                $data['harga'] = isset($sheetData[$i][4]) ? $sheetData[$i][4] : '';
                $data['jenis'] = '2'; // Data baru dengan jenis = 2

                // Jika nama kosong, skip data tersebut
                if (empty($data['nama'])) {
                    continue;
                }

                // Insert data baru
                $this->md_kalkulator->add($data);
            }

            // Log activity
            addlog('Edit Master Data', 'Melakukan Import Price List Government');

            // Redirect ke halaman kalkulator
            redirect('/kalkulator/gov');
        }
    }


    public function export_gov()
     {
        
         
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
            
          $sheet->setCellValue('B1', "Format Data Price List GOVERNMENT"); // Set kolom A1 dengan tulisan "DATA SISWA"
          $sheet->mergeCells('B1:E1'); // Set Merge Cell pada kolom A1 sampai E1
          $sheet->getStyle('B1')->getFont()->setBold(true); // Set bold kolom A1
            
                    // Buat header tabel nya pada baris ke 3
                    $sheet->setCellValue('B2', 'NO');
                    $sheet->setCellValue('C2', 'Merk Product');
                    $sheet->setCellValue('D2', 'Nama Product');
                    $sheet->setCellValue('E2', 'Harga E-Katalog (WAJIB NUMBER Tanpa Titik dan Koma)');
                    
                     // Apply style header yang telah kita buat tadi ke masing-masing kolom header
                    $sheet->getStyle('B2')->applyFromArray($style_col);
                    $sheet->getStyle('C2')->applyFromArray($style_col);
                    $sheet->getStyle('D2')->applyFromArray($style_col);
                    $sheet->getStyle('E2')->applyFromArray($style_col);

                    // Buat header tabel nya pada baris ke 3
                    $sheet->setCellValue('B3', '1');
                    $sheet->setCellValue('C3', 'IATOME');
                    $sheet->setCellValue('D3', 'Mobile Xray Alerio Smart 4000 (100 mA)');
                    $sheet->setCellValue('E3', '337273333');
                    $sheet->setCellValue('F3', 'INI DATA CONTOH');
                    
                     // Apply style header yang telah kita buat tadi ke masing-masing kolom header
                    $sheet->getStyle('B3')->applyFromArray($style_row);
                    $sheet->getStyle('C3')->applyFromArray($style_row);
                    $sheet->getStyle('D3')->applyFromArray($style_row);
                    $sheet->getStyle('E3')->applyFromArray($style_row);

                    

            // Set width kolom
            $sheet->getColumnDimension('A')->setWidth(2); // Set width kolom B
            $sheet->getColumnDimension('B')->setWidth(10); // Set width kolom C
            $sheet->getColumnDimension('C')->setWidth(30); // Set width kolom D
            $sheet->getColumnDimension('D')->setWidth(50); // Set width kolom E
            $sheet->getColumnDimension('E')->setWidth(70); // Set width kolom E
            $sheet->getColumnDimension('F')->setWidth(50); // Set width kolom E
            
            // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
            $sheet->getDefaultRowDimension()->setRowHeight(-1);
            // Set orientasi kertas jadi LANDSCAPE
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            // Set judul file excel nya
            $sheet->setTitle("Price List GOVERNMENT");
            ob_end_clean();
            // Proses file excel
            $filename = "Format Data Price List GOVERNMENT.xlsx";
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename='.$filename);
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            
            $aksi = 'Edit Master Data';
            $ket = 'Melakukan Eksport Format Data Price List GOVERNMENT';
            addlog($aksi, $ket);
            
    }

    public function print_excel_gov()
    {
        grantAccessFor('all');

        $filter_merk = $this->input->get_post('filter_merk', TRUE) ?: '';
        $filter_nama = $this->input->get_post('filter_nama', TRUE) ?: '';
        $keyword = $this->input->get_post('search', TRUE) ?: '';

        $komisi_badan = $this->input->get_post('komisi_badan', TRUE);
        $komisi_badan_persen = is_numeric($komisi_badan) ? floatval($komisi_badan) : 0;

        $komisi_pribadi = $this->input->get_post('komisi_pribadi', TRUE);
        $komisi_pribadi_persen = is_numeric($komisi_pribadi) ? floatval($komisi_pribadi) : 0;

        $komisi_npwp = $this->input->get_post('komisi_npwp', TRUE);
        $komisi_npwp_persen = is_numeric($komisi_npwp) ? floatval($komisi_npwp) : 0;

        $list = $this->md_kalkulator->getDataGov($filter_merk, $filter_nama, $keyword);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul Utama
        $sheet->setCellValue('A1', 'DAFTAR PRICE LIST & PERHITUNGAN HARGA (GOVERNMENT / E-KATALOG)');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'PT VISI YOSINDO MEDIKAL');
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Keterangan Parameter & Waktu Cetak
        $params_info = "Dicetak: " . date('d-m-Y H:i') . " | Komisi NPWP Badan: {$komisi_badan_persen}% | Komisi NPWP Pribadi: {$komisi_pribadi_persen}% | Komisi Tanpa NPWP: {$komisi_npwp_persen}%";
        if (!empty($filter_merk)) {
            $params_info .= " | Merk: " . $filter_merk;
        }
        if (!empty($filter_nama)) {
            $params_info .= " | Produk: " . $filter_nama;
        }
        $sheet->setCellValue('A3', $params_info);
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header Tabel
        $header_row = 5;
        $headers = [
            'A' => 'NO',
            'B' => 'MERK',
            'C' => 'NAMA PRODUCT',
            'D' => 'HARGA E-KATALOG',
            'E' => ($komisi_badan_persen > 0 ? "KOMISI NPWP BADAN ({$komisi_badan_persen}%)" : 'KOMISI NPWP BADAN'),
            'F' => ($komisi_pribadi_persen > 0 ? "KOMISI NPWP PRIBADI ({$komisi_pribadi_persen}%)" : 'KOMISI NPWP PRIBADI'),
            'G' => ($komisi_npwp_persen > 0 ? "KOMISI TANPA NPWP ({$komisi_npwp_persen}%)" : 'KOMISI TANPA NPWP')
        ];

        $style_header = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1F497D']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]
            ]
        ];

        foreach ($headers as $col => $text) {
            $sheet->setCellValue($col . $header_row, $text);
            $sheet->getStyle($col . $header_row)->applyFromArray($style_header);
        }
        $sheet->getRowDimension($header_row)->setRowHeight(28);

        $style_data_border = [
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FFD9D9D9']]
            ]
        ];

        $curr_row = 6;
        $no = 1;

        foreach ($list as $row) {
            $harga = is_numeric($row->harga) ? floatval($row->harga) : 0;
            $komisi_badan_nominal = ((($harga / 1.11)-(($harga / 1.11) * 1.5 / 100)) * $komisi_badan_persen / 100) * 0.98;
            $komisi_pribadi_nominal = ((($harga / 1.11)-(($harga / 1.11) * 1.5 / 100)) * $komisi_pribadi_persen / 100) * 0.975;
            $komisi_npwp_nominal = ((($harga / 1.11)-(($harga / 1.11) * 1.5 / 100)) * $komisi_npwp_persen / 100) * 0.94;

            $sheet->setCellValue('A' . $curr_row, $no++);
            $sheet->setCellValue('B' . $curr_row, $row->merk);
            $sheet->setCellValue('C' . $curr_row, $row->nama);
            $sheet->setCellValue('D' . $curr_row, $harga);
            $sheet->setCellValue('E' . $curr_row, $komisi_badan_nominal);
            $sheet->setCellValue('F' . $curr_row, $komisi_pribadi_nominal);
            $sheet->setCellValue('G' . $curr_row, $komisi_npwp_nominal);

            $sheet->getStyle('A' . $curr_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $curr_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('C' . $curr_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            // Number formats
            $sheet->getStyle('D' . $curr_row . ':G' . $curr_row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('D' . $curr_row . ':G' . $curr_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $sheet->getStyle('A' . $curr_row . ':G' . $curr_row)->applyFromArray($style_data_border);

            // Zebra striping
            if ($no % 2 == 0) {
                $sheet->getStyle('A' . $curr_row . ':G' . $curr_row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2F5F9');
            }

            $curr_row++;
        }

        // Set width auto
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->setTitle('Price List GOVERNMENT');

        if (ob_get_length()) {
            ob_end_clean();
        }

        $filename = 'Pricelist_Government_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        addlog('Export Data', 'Melakukan Print / Export Excel Price List GOVERNMENT');
        exit;
    }
}
