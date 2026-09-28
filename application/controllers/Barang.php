<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_gudang');
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
		$id_navbar = "inventory";
		return $id_navbar;
	}



    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['kategori_barang']  = $this->md_kategori_barang->getByWhere();
        $page_data['satuan_barang']  = $this->md_satuan_barang->getByWhere();
        $page_data['cabang']  = $this->md_cabang->getByWhere();
        $page_data['pemasok_utama']  = $this->md_pemasok_utama->getByWhere();
        $page_data['satuan_barang']  = $this->md_satuan_barang->getByWhere();
        $page_data['tarif_pajak']  = $this->md_tarif_pajak->getByWhere();
        $page_data['page_name']  = 'v_barang';
        $page_data['page_title'] = 'Barang & Jasa';
        $page_data['page_desc']  = 'Management Data Barang & jasa';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['id_kategori'] = decrypt($this->input->post('id_kategori'));
        $data['nama_barang'] = $this->input->post('nama_barang');
        $data['jenis_barang'] = $this->input->post('jenis_barang');
        $data['id_cabang'] = decrypt($this->input->post('id_cabang'));
        $data['id_satuan_barang'] = decrypt($this->input->post('id_satuan_barang'));
        $data['keterangan'] = $this->input->post('keterangan');
        $data['kode_barang'] = time() . 'VYM';
        checkEmptyForm($data);
        $data['batas_min_stock'] = $this->input->post('batas_min_stock');

        //Tambahan untuk kebutuhan E-reporting
        $data['id_produk'] = $this->input->post('id_produk');
        $data['tipe'] = $this->input->post('tipe');
        $data['akl'] = $this->input->post('akl');
        $data['kode_produk'] = $this->input->post('kode_produk');

        //generate and save barcode
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcode = $data['kode_barang'];
        $imageResource = Zend_Barcode::draw('code128', 'image', array('text' => $barcode), array());
        $imageName = $barcode . '.jpg';
        $imagePath = 'uploads/barcode/';
        imagejpeg($imageResource, $imagePath . $imageName);
        // $data['kode_barang'] = $this->input->post('kode_barang');

        // if ($data['kode_barang']) {
        //     //cek conirmasi kode_barang
        //     if ($data['kode_barang'] != $this->input->post('c_kode_barang')) {
        //         ajaxReturnDie('error', 'kode barang tidak sama!');
        //     }
        //     //cek unique kode_barang
        //     $cek1 = $this->md_kode_barang->getByWhere(['kb.kode_barang' => $data['kode_barang']]);
        //     $cek2 = $this->md_barang->getByWhere(['b.kode_barang' => $data['kode_barang']]);
        //     if ($cek1 || $cek2) {
        //         ajaxReturnDie('error', 'Kode Barang sudah ada!');
        //     }
        // }
        $this->md_barang->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data Barang - ' . $data['nama_barang'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function get($param = "", $param2 = "")
    {
        if ($param == 'by_search') {
            $temp   = $this->md_barang->getBySearch(['b.status' => 1]);
            foreach ($temp as $row) {
                $row->id_barang = encrypt($row->id_barang);
            }
            echo json_encode(
                array(
                    'incomplete_results' => true,
                    'items' => $temp,
                )
            );
            die;
        } else if ($param == 'download_barcode') {
            $barcode_barang = $this->md_barang->getById(decrypt($param2))[0]->kode_barang;
            if (file_exists('uploads/barcode/' . $barcode_barang .'.jpg')) {
                $this->load->helper('download');
                force_download('uploads/barcode/' . $barcode_barang .'.jpg', NULL);
            } else {
                show_404();
            }
        }
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_barang->getById($id);
        foreach ($dt as $row) {
            $row->id_barang = encrypt($row->id_barang);
            $row->id_cabang = encrypt($row->id_cabang);
            $row->id_kategori = encrypt($row->id_kategori);
            $row->id_pemasok_utama = encrypt($row->id_pemasok_utama);
            $row->id_satuan_barang = encrypt($row->id_satuan_barang);
            $row->id_tarif_pajak = encrypt($row->id_tarif_pajak);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        $id_barang    = decrypt($param);
        $barcode_barang = $this->md_barang->getById($id_barang)[0]->kode_barang;
        $data['status'] = 0;

        
        //cek barang sudah di gunakan atau belum
        $cek = $this->md_detail_barang->getByWhere(['db.id_barang' => $id_barang]);
        if ($cek) {
            ajaxReturnDie('error', 'Data Sedang di Gunakan');
        }
        file_exists('uploads/barcode/' . $barcode_barang . '.jpg') ? unlink('uploads/barcode/' . $barcode_barang . '.jpg') : '';
        $this->md_barang->update(['id_barang' => $id_barang], $data);


        //add log
        $temp = $this->md_barang->getById($id_barang);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data Barang - ' . $temp[0]->nama_barang;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_barang'));
        $data['id_kategori'] = decrypt($this->input->post('id_kategori'));
        $data['nama_barang'] = $this->input->post('nama_barang');
        $data['jenis_barang'] = $this->input->post('jenis_barang');
        $data['id_cabang'] = decrypt($this->input->post('id_cabang'));
        $data['id_satuan_barang'] = decrypt($this->input->post('id_satuan_barang'));
        $data['keterangan'] = $this->input->post('keterangan');
        checkEmptyForm($data);
        $data['batas_min_stock'] = $this->input->post('batas_min_stock');

        //Tambahan untuk kebutuhan E-reporting
        $data['id_produk'] = $this->input->post('id_produk');
        $data['tipe'] = $this->input->post('tipe');
        $data['akl'] = $this->input->post('akl');
        $data['kode_produk'] = $this->input->post('kode_produk');


        // $data['kode_barang'] = $this->input->post('kode_barang');

        // if ($data['kode_barang']) {
        //     //cek conirmasi kode_barang
        //     if ($data['kode_barang'] != $this->input->post('c_kode_barang')) {
        //         ajaxReturnDie('error', 'kode barang tidak sama!');
        //     }
        //     //cek unique kode_barang
        //     $cek1 = $this->md_kode_barang->getByWhere(['kb.kode_barang' => $data['kode_barang']]);
        //     $cek2 = $this->md_barang->getByWhere(['b.kode_barang' => $data['kode_barang']]);
        //     if ($cek1 || ($cek2 && $cek2[0]->id_barang != $id)) {
        //         ajaxReturnDie('error', 'Kode Barang sudah ada!');
        //     }
        // }
        $this->md_barang->update(['id_barang' => $id], $data);

        //add log
        $temp = $this->md_barang->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data Barang - ' . $temp[0]->nama_barang;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_barang->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_barang);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="barang/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $kode_barang = '<a href="barang/get/download_barcode/' . $id . '")>' . $row->kode_barang . '</a>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_barang;
            $th[] = $row->nama_kategori;
            //$th[] = $kode_barang;
            $th[] = $row->nama_satuan;
            $th[] = $row->id_produk;
            $th[] = $row->akl;
            $th[] = $row->tipe;
            $th[] = $row->kode_produk;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }



    public function export()
     {
        
         $data = $this->md_barang->getAllExport();



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
            
          $sheet->setCellValue('A1', "Format File Barang"); // Set kolom A1 dengan tulisan "DATA SISWA"
          $sheet->mergeCells('A1:U1'); // Set Merge Cell pada kolom A1 sampai E1
          $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
            
                    // Buat header tabel nya pada baris ke 3
                    $sheet->setCellValue('A4', 'ID Barang (Jangan Diubah)');
                    $sheet->setCellValue('B4', 'Nama Barang');
                    $sheet->setCellValue('C4', 'Kode Barang');
                    $sheet->setCellValue('D4', 'Jenis Barang');
                    $sheet->setCellValue('E4', 'ID Kategori');
                    $sheet->setCellValue('F4', 'ID Cabang');
                    $sheet->setCellValue('G4', 'ID Pemasok Utama');
                    $sheet->setCellValue('H4', 'ID Satuan Barang');
                    $sheet->setCellValue('I4', 'Batas Minimal Stock');
                    $sheet->setCellValue('J4', 'Status');
                    $sheet->setCellValue('K4', 'Keterangan');
                    $sheet->setCellValue('L4', 'Created at');
                    $sheet->setCellValue('M4', 'Kategori (Jangan Diubah)');
                    $sheet->setCellValue('N4', 'ID Produk');
                    $sheet->setCellValue('O4', 'Nomor AKL');
                    $sheet->setCellValue('P4', 'Tipe');
                    $sheet->setCellValue('Q4', 'Kode Produk');
                    
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


            $kolom = 5; // Mulai pada baris 8
            $nomor = 1;

            foreach ($data as $marketing) {
                // Menyisipkan data pada baris tertentu
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $marketing->id_barang)
                    ->setCellValue('B' . $kolom, $marketing->nama_barang)
                    ->setCellValue('C' . $kolom, $marketing->kode_barang)
                    ->setCellValue('D' . $kolom, $marketing->jenis_barang)
                    ->setCellValue('E' . $kolom, $marketing->id_kategori)
                    ->setCellValue('F' . $kolom, $marketing->id_cabang)
                    ->setCellValue('G' . $kolom, $marketing->id_pemasok_utama)
                    ->setCellValue('H' . $kolom, $marketing->id_satuan_barang)
                    ->setCellValue('I' . $kolom, $marketing->batas_min_stock)
                    ->setCellValue('J' . $kolom, $marketing->status)
                    ->setCellValue('K' . $kolom, $marketing->keterangan)
                    ->setCellValue('L' . $kolom, $marketing->data_created)
                    ->setCellValue('M' . $kolom, $marketing->kategori)
                    ->setCellValue('N' . $kolom, $marketing->id_produk)
                    ->setCellValue('O' . $kolom, $marketing->akl)
                    ->setCellValue('P' . $kolom, $marketing->tipe)
                    ->setCellValue('Q' . $kolom, $marketing->kode_produk);

                // Apply style untuk border dan alignment pada setiap baris data
                $spreadsheet->getActiveSheet()->getStyle('A' . $kolom . ':Q' . $kolom)
                    ->applyFromArray($style_row); // Menambahkan border dan alignment pada seluruh baris

                // Mengatur alignment center pada kolom tertentu
                $spreadsheet->getActiveSheet()->getStyle('A' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('C' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('D' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('E' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('F' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('G' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('H' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('I' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('J' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('L' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('M' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('N' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('O' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('P' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('Q' . $kolom)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Check jika status == 0 dan beri warna merah pada baris tersebut
                if ($marketing->status == 0) {
                    $spreadsheet->getActiveSheet()->getStyle('A' . $kolom . ':L' . $kolom)
                        ->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FF0000'], // Warna merah (hex: FF0000)
                            ]
                        ]);
                }

                // Increment untuk baris berikutnya
                $kolom++;
            }



          

            // Set width kolom
            $sheet->getColumnDimension('A')->setWidth(27); // Set width kolom B
            $sheet->getColumnDimension('B')->setWidth(57); // Set width kolom C
            $sheet->getColumnDimension('C')->setWidth(0); // Set width kolom D
            $sheet->getColumnDimension('D')->setWidth(0); // Set width kolom E
            $sheet->getColumnDimension('E')->setWidth(0); // Set width kolom E
            $sheet->getColumnDimension('F')->setWidth(0); // Set width kolom F
            $sheet->getColumnDimension('G')->setWidth(0); // Set width kolom G
            $sheet->getColumnDimension('H')->setWidth(0); // Set width kolom H
            $sheet->getColumnDimension('I')->setWidth(0); // Set width kolom I
            $sheet->getColumnDimension('J')->setWidth(0); // Set width kolom J
            $sheet->getColumnDimension('K')->setWidth(33); // Set width kolom k
            $sheet->getColumnDimension('L')->setWidth(0); // Set width kolom k
            $sheet->getColumnDimension('M')->setWidth(37); // Set width kolom k
            $sheet->getColumnDimension('N')->setWidth(20); // Set width kolom k
            $sheet->getColumnDimension('O')->setWidth(20); // Set width kolom k
            $sheet->getColumnDimension('P')->setWidth(40); // Set width kolom k
            $sheet->getColumnDimension('Q')->setWidth(20); // Set width kolom k
            
            // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
            $sheet->getDefaultRowDimension()->setRowHeight(-1);
            // Set orientasi kertas jadi LANDSCAPE
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            // Set judul file excel nya
            $sheet->setTitle("Format File Barang");
            ob_end_clean();
            // Proses file excel
            $filename = "Format File Barang.xlsx";
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename='.$filename);
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            
            $aksi = 'Edit Master Data';
            $ket = 'Melakukan Eksport Barang';
            addlog($aksi, $ket);
            
    }



    public function import() {
        
		$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        if(isset($_FILES['berkas_excel']['name']) && in_array($_FILES['berkas_excel']['type'], $file_mimes)) {

            $arr_file = explode('.', $_FILES['berkas_excel']['name']);
            $extension = end($arr_file);

            if('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['berkas_excel']['tmp_name']);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            //$berhasil = 0;
            for($i = 1;$i < count($sheetData);$i++)
            {


            $id_barang                = $sheetData[$i]['0'];
            $data['nama_barang']      = isset($sheetData[$i]['1']) ? $sheetData[$i]['1'] : ''; 
            $data['kode_barang']      = isset($sheetData[$i]['2']) ? $sheetData[$i]['2'] : '';
            $data['jenis_barang']     = isset($sheetData[$i]['3']) ? $sheetData[$i]['3'] : '';
            $data['id_kategori']      = isset($sheetData[$i]['4']) ? $sheetData[$i]['4'] : '';
            $data['id_cabang']        = isset($sheetData[$i]['5']) ? $sheetData[$i]['5'] : '';
            $data['id_pemasok_utama'] = isset($sheetData[$i]['6']) ? $sheetData[$i]['6'] : '';
            $data['id_satuan_barang'] = isset($sheetData[$i]['7']) ? $sheetData[$i]['7'] : '';
            $data['batas_min_stock']  = isset($sheetData[$i]['8']) ? $sheetData[$i]['8'] : '';
            $data['status']           = isset($sheetData[$i]['9']) ? $sheetData[$i]['9'] : '';
            $data['keterangan']       = isset($sheetData[$i]['10']) ? $sheetData[$i]['10'] : '';
            $data['id_produk']        = isset($sheetData[$i]['13']) ? $sheetData[$i]['13'] : '';
            $data['akl']              = isset($sheetData[$i]['14']) ? $sheetData[$i]['14'] : '';
            $data['tipe']             = isset($sheetData[$i]['15']) ? $sheetData[$i]['15'] : '';
            $data['kode_produk']      = isset($sheetData[$i]['16']) ? $sheetData[$i]['16'] : '';

            // Cek apakah nama_barang kosong atau tidak
            if (empty($data['nama_barang'])) {
                // Jika kosong, kamu bisa memberikan default value atau skip record ini
                continue; // Skip baris ini jika nama_barang kosong
            }

            //if ($id_barang == "") {
            //    $this->md_barang->add($data);
            //} else {
                $this->md_barang->update(['id_barang' => $id_barang], $data);
            //}

                                                                



             //   }
            //$berhasil++;
            }
            
            //header("location: ../../main.php?module=prolanis&berhasil1=$berhasil");
            $aksi = 'Edit Master Data';
            $ket = 'Melakukan Import Barang';
            addlog($aksi, $ket);
            redirect('/barang');
        }
	}


}
