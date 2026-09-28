<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use FontLib\Table\Type\post;

class Customer extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_customer');
        $this->load->model('md_pengeluaran_barang');
    }

    function id_navbar()
    {
        $id_navbar = "inventory";
        return $id_navbar;
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']  = 'v_customer';
        $page_data['page_title'] = 'Customer';
        $page_data['page_desc']  = 'Management Data Customer';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_customer'] = $this->input->post('nama_customer');
        $data['alamat_customer'] = $this->input->post('alamat_customer');
        $data['contact'] = $this->input->post('contact');
        checkEmptyForm($data);

        $this->md_customer->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data customer - ' . $data['nama_customer'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function get($param = "")
    {
        grantAccessFor('all');

        if ($param == 'by_search') {
            $temp   = $this->md_customer->getBySearch(['c.status' => 1]);
            foreach ($temp as $row) {
                $row->id_customer = encrypt($row->id_customer);
            }
            echo json_encode(
                array(
                    'incomplete_results' => true,
                    'items' => $temp,
                )
            );
            die;
        } else {
            $id_customer = decrypt($this->input->post('id_customer'));
            $data = $this->md_customer->getById($id_customer);
            $data[0]->id_customer = encrypt($data[0]->id_customer);
            echo json_encode($data);
            die;
        }
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_customer->getById($id);
        foreach ($dt as $row) {
            $row->id_customer = encrypt($row->id_customer);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        //cek apakah data sudah di gunakan
        $cek = $this->md_pengeluaran_barang->getByWhere(['pb.id_customer' => decrypt($param)]);
        if ($cek) {
            ajaxReturnDie('error', 'Data sudah di gunakan!');
        }
        $id_customer    = decrypt($param);
        $data['status'] = 0;
        $this->md_customer->update(['id_customer' => $id_customer], $data);

        //add log
        $temp = $this->md_customer->getById($id_customer);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data customer - ' . $temp[0]->nama_customer;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_customer'));
        $data['nama_customer'] = $this->input->post('nama_customer');
        $data['alamat_customer'] = $this->input->post('alamat_customer');
        $data['contact'] = $this->input->post('contact');
        checkEmptyForm($data);

        $this->md_customer->update(['id_customer' => $id], $data);

        //add log
        $temp = $this->md_customer->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data customer - ' . $temp[0]->nama_customer;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_customer->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_customer);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="customer/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_customer;
            $th[] = $row->alamat_customer;
            $th[] = $row->contact;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function export_db()
    {
        grantAccessFor('all');
        if (!isAdmin()) {
            redirect(base_url('dashboard'));
        }

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'v_export_customer';
        $page_data['page_title'] = 'Export Database Customer';
        $page_data['page_desc'] = 'Halaman ekspor data master customer, pelanggan, dan calon pelanggan';

        // Count rows from each table
        $page_data['count_customer'] = $this->db->count_all('customer');
        $page_data['count_pelanggan'] = $this->db->count_all('pelanggan');
        $page_data['count_calonpelanggan'] = $this->db->count_all('calonpelanggan');

        $this->load->view('index', $page_data);
    }

    public function export_action($table = '')
    {
        grantAccessFor('all');
        if (!isAdmin()) {
            redirect(base_url('dashboard'));
        }

        // Set higher execution limits
        ini_set('memory_limit', '1024M');
        set_time_limit(300);

        if ($table === 'customer') {
            $this->export_table_customer();
        } elseif ($table === 'pelanggan') {
            $this->export_table_pelanggan();
        } elseif ($table === 'calonpelanggan') {
            $this->export_table_calonpelanggan();
        } else {
            show_404();
        }
    }

    private function apply_excel_styles($sheet, $max_col, $max_row, $title)
    {
        $sheet->setShowGridlines(true);

        // Header style
        $header_style = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E3A8A'] // Premium dark blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // Apply style to header row
        $sheet->getStyle('A1:' . $max_col . '1')->applyFromArray($header_style);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Data rows style (borders)
        if ($max_row > 1) {
            $data_style = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'D2D6DC'], // light gray
                    ],
                ],
            ];
            $sheet->getStyle('A2:' . $max_col . $max_row)->applyFromArray($data_style);
        }

        // Auto size columns
        $start = 'A';
        while (true) {
            $sheet->getColumnDimension($start)->setAutoSize(true);
            if ($start === $max_col) {
                break;
            }
            $start++;
        }
    }

    private function export_table_customer()
    {
        $rows = $this->db->get('customer')->result_array();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Customer');

        $headers = [
            'ID Customer',
            'Nama Customer',
            'Alamat Customer',
            'Contact Person',
            'Status',
            'Tanggal Dibuat',
            'Perusahaan ID'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $line = 2;
        foreach ($rows as $row) {
            $status_text = $row['status'] == 1 ? 'Aktif' : 'Nonaktif';

            $sheet->setCellValue('A' . $line, $row['id_customer']);
            $sheet->setCellValue('B' . $line, $row['nama_customer']);
            $sheet->setCellValue('C' . $line, $row['alamat_customer']);
            $sheet->setCellValue('D' . $line, $row['contact']);
            $sheet->setCellValue('E' . $line, $status_text);
            $sheet->setCellValue('F' . $line, $row['date_created']);
            $sheet->setCellValue('G' . $line, $row['perusahaan']);
            $line++;
        }

        $max_col = 'G';
        $this->apply_excel_styles($sheet, $max_col, $line - 1, 'Customer');

        $filename = 'Export_Customer_' . date('Ymd-His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function export_table_pelanggan()
    {
        $rows = $this->db->get('pelanggan')->result_array();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pelanggan');

        $headers = [
            'ID Pelanggan',
            'Nama Pelanggan',
            'Kontak',
            'Email',
            'NIK',
            'Status Pajak',
            'Provinsi',
            'Kota',
            'Alamat',
            'Status',
            'Tanggal',
            'Contact Person Name',
            'Tipe Bisnis',
            'Marketing ID',
            'Kelas',
            'NPWP',
            'Nama NPWP',
            'Link NPWP',
            'Pengiriman Dokumen',
            'Alamat Pengiriman Dokumen',
            'Alamat Penagihan',
            'Link Folder Berkas',
            'Tanggal Registrasi',
            'KTP',
            'Jenis Transaksi',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
            'Perusahaan ID'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $line = 2;
        foreach ($rows as $row) {
            $status_text = $row['status'] == 1 ? 'Aktif' : 'Nonaktif';

            $sheet->setCellValue('A' . $line, $row['id_pelanggan']);
            $sheet->setCellValue('B' . $line, $row['identitas_pelanggan']);
            $sheet->setCellValue('C' . $line, $row['kontak']);
            $sheet->setCellValue('D' . $line, $row['email']);
            $sheet->setCellValue('E' . $line, $row['nik']);
            $sheet->setCellValue('F' . $line, $row['status_pajak']);
            $sheet->setCellValue('G' . $line, $row['provinsi']);
            $sheet->setCellValue('H' . $line, $row['kota']);
            $sheet->setCellValue('I' . $line, $row['alamat']);
            $sheet->setCellValue('J' . $line, $status_text);
            $sheet->setCellValue('K' . $line, $row['tanggal']);
            $sheet->setCellValue('L' . $line, $row['cpname']);
            $sheet->setCellValue('M' . $line, $row['tipe_bisnis']);
            $sheet->setCellValue('N' . $line, $row['marketing']);
            $sheet->setCellValue('O' . $line, $row['kelas']);
            $sheet->setCellValue('P' . $line, $row['npwp']);
            $sheet->setCellValue('Q' . $line, $row['nama_npwp']);
            $sheet->setCellValue('R' . $line, $row['link_npwp']);
            $sheet->setCellValue('S' . $line, $row['pengiriman_dokumen']);
            $sheet->setCellValue('T' . $line, $row['alamat_pengiriman_dokumen']);
            $sheet->setCellValue('U' . $line, $row['alamat_penagihan']);
            $sheet->setCellValue('V' . $line, $row['link_folder_berkas']);
            $sheet->setCellValue('W' . $line, $row['tanggal_registrasi']);
            $sheet->setCellValue('X' . $line, $row['ktp']);
            $sheet->setCellValue('Y' . $line, $row['jenis_transaksi']);
            $sheet->setCellValue('Z' . $line, $row['date_created']);
            $sheet->setCellValue('AA' . $line, $row['updated_at']);
            $sheet->setCellValue('AB' . $line, $row['perusahaan']);
            $line++;
        }

        $max_col = 'AB';
        $this->apply_excel_styles($sheet, $max_col, $line - 1, 'Pelanggan');

        $filename = 'Export_Pelanggan_' . date('Ymd-His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function export_table_calonpelanggan()
    {
        // Join with pengguna, provinsi, kota to resolve names
        $this->db->select('
            c.id,
            c.kodecaloncustomer,
            c.namacaloncustomer,
            c.statuscaloncustomer,
            c.tipecustomer,
            c.kelascustomer,
            c.namapihakketiga,
            c.alamatcaloncustomer,
            c.email,
            c.website,
            c.data_created,
            c.deleted,
            c.data_deleted,
            c.perusahaan,
            p.nama as nama_marketing,
            u.nama as nama_pembuat,
            prov.nama as nama_provinsi,
            kot.nama as nama_kota
        ');
        $this->db->from('calonpelanggan c');
        $this->db->join('pengguna p', 'c.idmarketing = p.pengguna_id', 'left');
        $this->db->join('pengguna u', 'c.pengguna_id = u.pengguna_id', 'left');
        $this->db->join('provinsi prov', 'c.provinsi = prov.kode', 'left');
        $this->db->join('kota kot', 'c.kota = kot.id', 'left');
        $this->db->where('c.deleted', 0);
        $this->db->order_by('c.id', 'desc');
        $rows = $this->db->get()->result_array();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Calon Pelanggan');

        $headers = [
            'ID Calon',
            'Kode Calon Customer',
            'Nama Calon Customer',
            'Status Calon Customer',
            'Tipe Customer',
            'Kelas Customer',
            'Nama Pihak Ketiga',
            'Provinsi',
            'Kota',
            'Alamat Calon Customer',
            'Email',
            'Website',
            'Marketing',
            'Pembuat/Created By',
            'Tanggal Dibuat',
            'Perusahaan ID'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $line = 2;
        foreach ($rows as $row) {
            $status_text = $row['statuscaloncustomer'] == 1 ? 'Aktif' : 'Nonaktif';

            $sheet->setCellValue('A' . $line, $row['id']);
            $sheet->setCellValue('B' . $line, $row['kodecaloncustomer']);
            $sheet->setCellValue('C' . $line, $row['namacaloncustomer']);
            $sheet->setCellValue('D' . $line, $status_text);
            $sheet->setCellValue('E' . $line, $row['tipecustomer']);
            $sheet->setCellValue('F' . $line, $row['kelascustomer']);
            $sheet->setCellValue('G' . $line, $row['namapihakketiga']);
            $sheet->setCellValue('H' . $line, $row['nama_provinsi']);
            $sheet->setCellValue('I' . $line, $row['nama_kota']);
            $sheet->setCellValue('J' . $line, $row['alamatcaloncustomer']);
            $sheet->setCellValue('K' . $line, $row['email']);
            $sheet->setCellValue('L' . $line, $row['website']);
            $sheet->setCellValue('M' . $line, $row['nama_marketing']);
            $sheet->setCellValue('N' . $line, $row['nama_pembuat']);
            $sheet->setCellValue('O' . $line, $row['data_created']);
            $sheet->setCellValue('P' . $line, $row['perusahaan']);
            $line++;
        }

        $max_col = 'P';
        $this->apply_excel_styles($sheet, $max_col, $line - 1, 'Calon Pelanggan');

        $filename = 'Export_CalonPelanggan_' . date('Ymd-His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
