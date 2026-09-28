<?php

use FontLib\Table\Type\post;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date;

defined('BASEPATH') or exit('No direct script access allowed');

class Pelanggan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pelanggan');
        $this->load->model('md_pengguna');
        $this->load->model('md_prov_kota');
        $this->load->model('md_kategori_tiket');
        $this->load->model('md_tiket');
    }

    function id_navbar()
    {
        $id_navbar = "helpdesk";
        return $id_navbar;
    }

    /**
     * Clean and format phone number for Excel export
     * - Remove scientific notation
     * - Keep leading zeros
     * - Format consistently
     */
    private function cleanPhoneNumber($phone)
    {
        if (empty($phone) || $phone === '0') {
            return '';
        }

        // Convert scientific notation to string (e.g., 6.28214E+12)
        if (is_numeric($phone) && stripos($phone, 'E') !== false) {
            $phone = number_format((float)$phone, 0, '', '');
        }

        // Convert to string
        $phone = (string)$phone;

        // Remove all non-numeric characters except + at beginning
        $cleaned = preg_replace('/[^0-9+]/', '', $phone);

        // If starts with 62, add + prefix
        if (substr($cleaned, 0, 2) === '62' && substr($cleaned, 0, 1) !== '+') {
            $cleaned = '+' . $cleaned;
        }

        // If only 0 or empty after cleaning, return empty
        if ($cleaned === '0' || empty($cleaned)) {
            return '';
        }

        return $cleaned;
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']              = $this->id_navbar();
        $page_data['page_name']         = 'pelanggan/v_pelanggan';
        $page_data['page_title']        = 'Pelanggan';
        $page_data['page_desc']         = 'Management Data Pelanggan';
        $page_data['list_kota']         = $this->md_prov_kota->getAllKota();
        $page_data['list_prov']         = $this->md_prov_kota->getAllProvinsi();
        $page_data['nama_marketing']    = $this->md_pengguna->getPenggunaMarketing();
        $page_data['statistik']         = $this->md_pelanggan->getStatistik();
        $page_data['tipe_bisnis']       = $this->md_pelanggan->getTipeBisnis();
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['identitas_pelanggan']        = $this->input->post('nama', TRUE);
        $data['kontak']                        = $this->input->post('kontak', TRUE);
        $data['email']                      = $this->input->post('email', TRUE);
        $data['nik']                        = $this->input->post('nik', TRUE);
        $data['status_pajak']               = $this->input->post('status_pajak', TRUE);
        $data['kota']                        = $this->input->post('kota', TRUE);
        $data['provinsi']                    = $this->input->post('provinsi', TRUE);
        $data['alamat']                        = $this->input->post('alamat', TRUE);
        $data['status']                        = 1;

        $data['tanggal']                    = date_db_format($this->input->post('tanggal', TRUE));
        $data['cpname']                        = $this->input->post('cpname', TRUE);
        $data['tipe_bisnis']                = $this->input->post('tipe_bisnis', TRUE);
        $data['marketing']                    = $this->input->post('marketing', TRUE);
        $data['kelas']                        = $this->input->post('kelas', TRUE);
        $data['npwp']                        = $this->input->post('npwp', TRUE);
        $data['nama_npwp']                    = $this->input->post('nama_npwp', TRUE);
        $data['link_npwp']                  = $this->input->post('link_npwp', TRUE);
        $data['pengiriman_dokumen']         = $this->input->post('pengiriman_dokumen', TRUE);
        $data['alamat_pengiriman_dokumen']  = $this->input->post('alamat_pengiriman_dokumen', TRUE);
        $data['alamat_penagihan']           = $this->input->post('alamat_penagihan', TRUE);
        $data['ktp']                        = $this->input->post('ktp', TRUE);
        $data['jenis_transaksi']            = $this->input->post('jenis_transaksi', TRUE);

        // Tanggal Registrasi - jika tidak diisi, gunakan tanggal sekarang
        $tanggal_registrasi = $this->input->post('tanggal_registrasi', TRUE);
        $data['tanggal_registrasi']         = !empty($tanggal_registrasi) ? date_db_format($tanggal_registrasi) : date('Y-m-d');

        $this->md_pelanggan->addPelanggan($data);

        /** LOG */
        addLog('Penambahan Pelanggan', 'Menambah Pelanggan "' . $data['identitas_pelanggan'] . '"');
        ajaxReturnDie('success', 'Pelanggan berhasil ditambahkan', 'reload_table');
    }


    public function addCommisioning()
    {
        grantAccessFor('all');

        $data['marketing']                = $this->input->post('marketing', TRUE);
        $data['kode_tiket']                = $this->input->post('kode_tiket', TRUE);
        $data['id_pelanggan']              = $this->input->post('id_pel', TRUE);
        $pihak                          = $this->input->post('tipe_bisnis', TRUE);

        $idPel                          = $this->input->post('id_pel', TRUE);
        $pelanggan                      = $this->md_pelanggan->getDetailPelangganById(['id_pelanggan' => $idPel]);
        if ($pihak == "1") {
            $data['pihak_ketiga']       = $pihak;
            $data['nama_hospital']        = $pelanggan[0]->nama;
            $data['address']            = $pelanggan[0]->alamat;
            $data['kota']                = $pelanggan[0]->kota;
            $data['provinsi']            = $pelanggan[0]->prov;
        } else {
            $data['pihak_ketiga']       = $pihak;
            $data['nama_hospital']        = $this->input->post('nama_hospital', TRUE);
            $data['address']            = $this->input->post('address', TRUE);
            $data['kota']                = $this->input->post('kota', TRUE);
            $data['provinsi']            = $this->input->post('provinsi', TRUE);
        }


        $data['warranty_start']           = date_db_format($this->input->post('warranty_start', TRUE));
        $data['warranty_end']           = date_db_format($this->input->post('warranty_end', TRUE));
        $data['serial_number']            = $this->input->post('serial_number', TRUE);
        $data['manufacturer']            = $this->input->post('manufacturer', TRUE);
        $data['model']                    = $this->input->post('model', TRUE);
        $data['equipment']                = $this->input->post('equipment', TRUE);
        $data['merk']                    = $this->input->post('merk', TRUE);
        $data['qty']                    = $this->input->post('qty', TRUE);
        $data['engineer']                = $this->input->post('engineer', TRUE);
        $data['link_invoice']            = $this->input->post('link_invoice', TRUE);
        $data['link_service_report']    = $this->input->post('link_service_report', TRUE);
        $data['link_commisioning']        = $this->input->post('link_commisioning', TRUE);
        $data['link_kepuasan_pelanggan']    = $this->input->post('link_kepuasan_pelanggan', TRUE);
        $data['link_dokumentasi']        = $this->input->post('link_dokumentasi', TRUE);
        $data['link_garansi_vym']        = $this->input->post('link_garansi_vym', TRUE);

        $this->md_pelanggan->addPelangganCommisioning($data);

        /** LOG */
        addLog('Penambahan Commisioning', 'Menambah Commisioning Pelanggan');
        ajaxReturnDie('success', 'Commisioning berhasil ditambahkan', 'reload_table');
    }

    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor('all');
        if ($param == 'detail_kategori') {
            $page_data['data_pengguna']     = $this->md_kategori_tiket->getByWhere(['t.id_topik' => decrypt($param2)]);
            $page_data['page_name']         = 'kategori_tiket/v_kategori_detail';
            $page_data['page_title']        = 'Kategori';
            $page_data['page_desc']         = 'Detail kategori';
            $this->load->view('index', $page_data);
        } else if ($param == 'detail_pelanggan') {
            // Check if pelanggan exists
            $data_pelanggan = $this->md_pelanggan->getDetailPelangganById(['id_pelanggan' => decrypt($param2)]);

            // Redirect if pelanggan not found
            if (empty($data_pelanggan)) {
                $this->session->set_flashdata('error', 'Data pelanggan tidak ditemukan');
                redirect('pelanggan');
                return;
            }

            $page_data['switch']              = $this->id_navbar();
            $page_data['pengguna']          = $this->md_pengguna->getByWhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'all']);
            $page_data['list_kota']         = $this->md_prov_kota->getAllKota();
            $page_data['list_prov']         = $this->md_prov_kota->getAllProvinsi();
            $page_data['nama_marketing']    = $this->md_pengguna->getPenggunaMarketing();
            $page_data['data_pelanggan']    = $data_pelanggan;
            $page_data['data_com']          = $this->md_pelanggan->getComByID(['id_pelanggan' => decrypt($param2)]);
            $page_data['data_tiket']          = $this->md_tiket->getBywhere(['t.id_pelanggan' => decrypt($param2)]);
            $page_data['page_name']           = 'pelanggan/v_detail_pelanggan';
            $page_data['page_title']          = 'Pelanggan';
            $page_data['page_desc']           = 'Detail Pelanggan';
            $this->load->view('index', $page_data);
        }
    }



    public function edit($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_pelanggan->getById($id);
        foreach ($dt as $row) {
            $row->id_pelanggan = encrypt($row->id_pelanggan);
        }
        echo json_encode($dt);
        die;
    }

    public function update()
    {
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));
        $data['identitas_pelanggan']        = $this->input->post('nama', TRUE);
        $data['kontak']                        = $this->input->post('kontak', TRUE);
        $data['email']                      = $this->input->post('email', TRUE);
        $data['nik']                        = $this->input->post('nik', TRUE);
        $data['status_pajak']               = $this->input->post('status_pajak', TRUE);
        $data['kota']                        = $this->input->post('kota', TRUE);
        $data['provinsi']                    = $this->input->post('provinsi', TRUE);
        $data['alamat']                        = $this->input->post('alamat', TRUE);


        $data['tanggal']                    = date_db_format($this->input->post('tanggal', TRUE));
        $data['cpname']                        = $this->input->post('cpname', TRUE);
        $data['tipe_bisnis']                = $this->input->post('tipe_bisnis', TRUE);
        $data['marketing']                    = $this->input->post('marketing', TRUE);
        $data['kelas']                        = $this->input->post('kelas', TRUE);
        $data['npwp']                        = $this->input->post('npwp', TRUE);
        $data['nama_npwp']                    = $this->input->post('nama_npwp', TRUE);
        $data['link_npwp']                  = $this->input->post('link_npwp', TRUE);
        $data['pengiriman_dokumen']         = $this->input->post('pengiriman_dokumen', TRUE);
        $data['alamat_pengiriman_dokumen']  = $this->input->post('alamat_pengiriman_dokumen', TRUE);
        $data['alamat_penagihan']           = $this->input->post('alamat_penagihan', TRUE);
        $data['link_folder_berkas']         = $this->input->post('link_folder_berkas', TRUE);
        $data['ktp']                        = $this->input->post('ktp', TRUE);
        $data['jenis_transaksi']            = $this->input->post('jenis_transaksi', TRUE);

        // Tanggal Registrasi
        $tanggal_registrasi = $this->input->post('tanggal_registrasi', TRUE);
        if (!empty($tanggal_registrasi)) {
            $data['tanggal_registrasi'] = date_db_format($tanggal_registrasi);
        }

        $this->md_pelanggan->update($id, $data);

        /** LOG */
        addLog('Update Pelanggan', 'Memperbarui data Pelanggan "' . $data['identitas_pelanggan'] . '"');
        ajaxReturnDie('success', 'Data pelanggan berhasil diperbarui', 'reload_table');
    }

    public function delete($param1)
    {
        grantAccessFor('all');

        $id = $param1;
        $this->md_pelanggan->hapus('id_pelanggan = ' . $id, 'pelanggan');

        addLog('Menghapus Surat', 'Menghapus Pengajuan Biaya dinas');
        ajaxReturnDie('success', 'Pengajuan Biaya Dinas Berhasil Dihapus', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        // Get filter parameters
        $filter = [
            'status_customer' => $this->input->post('status_customer'),
            'tipe_bisnis' => $this->input->post('tipe_bisnis'),
            'status_pajak' => $this->input->post('status_pajak')
        ];

        $dt    = $this->md_pelanggan->getAllPelanggan($filter);
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $status     = $row->status == 1 ? '<span class="badge badge-ecommerce badge-success">Aktif</span>' : '<span class="badge badge-ecommerce badge-danger">Tidak Aktif</span>';

            $id           = encrypt($row->id_pelanggan);

            // Status Customer switch toggle UI
            $checked = $row->status_customer == 'New Customer' ? 'checked' : '';
            $labelText = $row->status_customer;
            $labelClass = $row->status_customer == 'New Customer' ? 'text-new' : 'text-existing';
            $icon = $row->status_customer == 'New Customer' ? '<i class="fas fa-star"></i>' : '<i class="fas fa-user-check"></i>';

            $statusCustomer = '
                <div class="status-switch-container">
                    <label class="status-switch" title="Ubah status ' . htmlspecialchars($row->nama, ENT_QUOTES) . '">
                        <input type="checkbox" class="toggle-status-customer" data-id="' . $id . '" ' . $checked . '>
                        <span class="status-slider"></span>
                    </label>
                    <span class="status-switch-label ' . $labelClass . '">' . $icon . ' ' . $labelText . '</span>
                </div>';

            $li_btn       = '
                <div class="btn-group" role="group" aria-label="First group">
                   <a href="pelanggan/show/detail_pelanggan/' . $id . '" class="btn btn-sm btn-info" title="Detail"><i class="bx bx-show"></i></a>
                   <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '" title="Edit"><i class="bx bx-pencil"></i></button>
                   <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->id_pelanggan . '" data-object="pelanggan/delete/' . $row->id_pelanggan . '"><i class="bx bx-trash"></i></button>
                </div>';

            $namaPelanggan  = '<a href="pelanggan/show/detail_pelanggan/' . $id . '")>' . $row->nama . '</a>';

            // Format tanggal registrasi
            $tglRegistrasi = !empty($row->tanggal_registrasi) ? date('d-m-Y', strtotime($row->tanggal_registrasi)) : '-';

            $th = array();
            $th[] = $namaPelanggan;
            $th[] = $row->tipe_bisnis ?? '-';
            $th[] = $row->kontak;
            $th[] = $row->email ?? '-';
            $th[] = $row->kota;
            $th[] = $row->prov;
            $th[] = $tglRegistrasi;
            $th[] = $statusCustomer;
            $th[] = $status;
            if (sessPenggunaId() == 72 || sessPenggunaId() == 69 || sessPenggunaId() == 1 || sessPenggunaId() == 755) {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }

        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function toggle_status_customer()
    {
        grantAccessFor('all');

        $id_enc = $this->input->post('id');
        $id = decrypt($id_enc);

        $row = $this->md_pelanggan->getById($id);
        if (empty($row)) {
            ajaxReturnDie('error', 'Data pelanggan tidak ditemukan.');
        }

        $currentYear = date('Y');
        $row = $row[0];
        $current_status = $this->md_pelanggan->getStatusCustomer($row->tanggal_registrasi);

        if ($current_status === 'New Customer') {
            // Change to Existing Customer by setting tanggal_registrasi to previous year
            $new_date = ($currentYear - 1) . '-12-31';
            $new_status_label = 'Existing Customer';
        } else {
            // Change to New Customer by setting tanggal_registrasi to current date
            $new_date = date('Y-m-d');
            $new_status_label = 'New Customer';
        }

        $this->md_pelanggan->update($id, ['tanggal_registrasi' => $new_date]);

        // Log action
        addLog('Update Status Pelanggan', 'Mengubah status Pelanggan "' . $row->identitas_pelanggan . '" menjadi ' . $new_status_label);

        ajaxReturnDie('success', 'Status pelanggan berhasil diubah menjadi ' . $new_status_label);
    }


    public function paginationCommisioning()
    {
        grantAccessFor('all');

        $dt    = $this->md_pelanggan->getAllPelanggan();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $status     = $row->status == 1 ? '<span class="badge badge-ecommerce badge-success">Aktif</span>' : '<span class="badge badge-ecommerce badge-danger">Tidak Aktif</span>';
            $id           = encrypt($row->id_pelanggan);
            $li_btn       = '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                   <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->id_pelanggan . '" data-object="pelanggan/delete/' . $row->id_pelanggan . '"><i class="bx bx-trash"></i></button>
                </div>';

            $namaPelanggan  = '<a href="pelanggan/show/detail_pelanggan/' . $id . '")>' . $row->nama . '</a>';
            $th = array();
            $th[] = $namaPelanggan;
            $th[] = $row->kontak;
            $th[] = $row->alamat;
            $th[] = $row->kota;
            $th[] = $row->prov;
            $th[] = $status;
            $th[] = $row->date_created;
            if (sessPenggunaId() == 72 || sessPenggunaId() == 69 || sessPenggunaId() == 1 || sessPenggunaId() == 755) {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }

        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }


    public function exportlaporan()
    {

        $data = $this->md_pelanggan->getAllPelangganByTGL($this->input->get('idmarketing'), $this->input->get('tglawal'), $this->input->get('tglakhir'));



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

        $sheet->setCellValue('A1', "DATA PELANGGAN " . strtoupper($this->input->get('namamarketing'))); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('A1:L1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1

        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Nama Marketing');
        $sheet->setCellValue('C4', 'Type Bussines');
        $sheet->setCellValue('D4', 'Kelas');
        $sheet->setCellValue('E4', 'Nama Customer');
        $sheet->setCellValue('F4', 'Alamat');
        $sheet->setCellValue('G4', 'Kota');
        $sheet->setCellValue('H4', 'Provinsi');
        $sheet->setCellValue('I4', 'Tanggal Registrasi');
        $sheet->setCellValue('J4', 'Tahun Registrasi');
        $sheet->setCellValue('K4', 'Nama PIC');
        $sheet->setCellValue('L4', 'Kontak');
        $sheet->setCellValue('M4', 'NPWP');
        $sheet->setCellValue('N4', 'Nama NPWP');
        $sheet->setCellValue('O4', 'KTP');
        $sheet->setCellValue('P4', 'Jenis Transaksi');
        $sheet->setCellValue('R4', 'Date Created');

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


        $kolom = 5;
        $nomor = 1;

        foreach ($data as $marketing) {

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $marketing->marketing)
                ->setCellValue('C' . $kolom, $marketing->tipe_bisnis)
                ->setCellValue('D' . $kolom, $marketing->kelas)
                ->setCellValue('E' . $kolom, $marketing->nama)
                ->setCellValue('F' . $kolom, $marketing->alamat)
                ->setCellValue('G' . $kolom, $marketing->kota)
                ->setCellValue('H' . $kolom, $marketing->prov)
                ->setCellValue('I' . $kolom, date('j F Y', strtotime($marketing->date_created)))
                ->setCellValue('J' . $kolom, date('Y', strtotime($marketing->date_created)))
                ->setCellValue('K' . $kolom, $marketing->cpname);
            // Format kolom numerik sebagai TEXT
            $sheet->setCellValueExplicit('L' . $kolom, $this->cleanPhoneNumber($marketing->kontak), DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('M' . $kolom, $marketing->npwp, DataType::TYPE_STRING);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('N' . $kolom, $marketing->nama_npwp);
            $sheet->setCellValueExplicit('O' . $kolom, $marketing->ktp, DataType::TYPE_STRING);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('P' . $kolom, $marketing->jenis_transaksi)
                ->setCellValue('R' . $kolom, $marketing->date_created);

            $kolom++;
            $nomor++;
        }

        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(25); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(30); // Set width kolom C
        $sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
        $sheet->getColumnDimension('E')->setWidth(50); // Set width kolom E
        $sheet->getColumnDimension('F')->setWidth(70); // Set width kolom F
        $sheet->getColumnDimension('G')->setWidth(25); // Set width kolom G
        $sheet->getColumnDimension('H')->setWidth(33); // Set width kolom H
        $sheet->getColumnDimension('I')->setWidth(25); // Set width kolom I
        $sheet->getColumnDimension('J')->setWidth(30); // Set width kolom J
        $sheet->getColumnDimension('K')->setWidth(30); // Set width kolom K
        $sheet->getColumnDimension('L')->setWidth(30); // Set width kolom L
        $sheet->getColumnDimension('M')->setWidth(30); // Set width kolom M
        $sheet->getColumnDimension('N')->setWidth(30); // Set width kolom N
        $sheet->getColumnDimension('O')->setWidth(30); // Set width kolom O
        $sheet->getColumnDimension('P')->setWidth(30); // Set width kolom P
        $sheet->getColumnDimension('Q')->setWidth(30); // Set width kolom P
        $sheet->getColumnDimension('R')->setWidth(30); // Set width kolom P

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Data Pelanggan");
        ob_end_clean();
        // Proses file excel
        $filename = "Data Pelanggan - " . strtoupper($this->input->get('namamarketing')) . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }


    public function exportlaporanCOM()
    {

        $data = $this->md_pelanggan->getAllCommisioningByTGL($this->input->get('idmarketing'), $this->input->get('tglawal'), $this->input->get('tglakhir'));



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

        $sheet->setCellValue('A1', "DATA COMMISIONING " . strtoupper($this->input->get('namamarketing'))); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('A1:L1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1

        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Nama Pelanggan');
        $sheet->setCellValue('C4', 'Nama Hospital / Instansi');
        $sheet->setCellValue('D4', 'Marketing');
        $sheet->setCellValue('E4', 'No Tiket');
        $sheet->setCellValue('F4', 'Alamat');
        $sheet->setCellValue('G4', 'Kota');
        $sheet->setCellValue('H4', 'Provinsi');
        $sheet->setCellValue('I4', 'Warranty Start');
        $sheet->setCellValue('J4', 'Warranty End');
        $sheet->setCellValue('K4', 'Serial Number');
        $sheet->setCellValue('L4', 'Manufacturer');
        $sheet->setCellValue('M4', 'Model');
        $sheet->setCellValue('N4', 'Equipment');
        $sheet->setCellValue('O4', 'MERK');
        $sheet->setCellValue('P4', 'Qty');
        $sheet->setCellValue('Q4', 'Engineer');
        $sheet->setCellValue('R4', 'Invoice');
        $sheet->setCellValue('S4', 'Service Report');
        $sheet->setCellValue('T4', 'Commissioning');
        $sheet->setCellValue('U4', 'Kepuasan Pelanggan');
        $sheet->setCellValue('V4', 'Dokumentasi');
        $sheet->setCellValue('W4', 'Garansi VYM');
        $sheet->setCellValue('Y4', 'Date Created');

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


        $kolom = 5;
        $nomor = 1;

        foreach ($data as $marketing) {

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $marketing->nama)
                ->setCellValue('C' . $kolom, $marketing->nama_hospital)
                ->setCellValue('D' . $kolom, $marketing->marketing)
                ->setCellValue('E' . $kolom, $marketing->kode_tiket)
                ->setCellValue('F' . $kolom, $marketing->address)
                ->setCellValue('G' . $kolom, $marketing->kota)
                ->setCellValue('H' . $kolom, $marketing->prov)
                ->setCellValue('I' . $kolom, date('j F Y', strtotime($marketing->warranty_start)))
                ->setCellValue('J' . $kolom, date('j F Y', strtotime($marketing->warranty_end)))
                ->setCellValue('K' . $kolom, $marketing->serial_number)
                ->setCellValue('L' . $kolom, $marketing->manufacturer)
                ->setCellValue('M' . $kolom, $marketing->model)
                ->setCellValue('N' . $kolom, $marketing->equipment)
                ->setCellValue('O' . $kolom, $marketing->merk)
                ->setCellValue('P' . $kolom, $marketing->qty)
                ->setCellValue('Q' . $kolom, $marketing->engineer)
                ->setCellValue('R' . $kolom, $marketing->link_invoice)
                ->setCellValue('S' . $kolom, $marketing->link_service_report)
                ->setCellValue('T' . $kolom, $marketing->link_commisioning)
                ->setCellValue('U' . $kolom, $marketing->link_kepuasan_pelanggan)
                ->setCellValue('V' . $kolom, $marketing->link_dokumentasi)
                ->setCellValue('W' . $kolom, $marketing->link_garansi_vym)
                ->setCellValue('Y' . $kolom, $marketing->created_at);

            $kolom++;
            $nomor++;
        }

        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(50); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(50); // Set width kolom C
        $sheet->getColumnDimension('D')->setWidth(40); // Set width kolom D
        $sheet->getColumnDimension('E')->setWidth(35); // Set width kolom E
        $sheet->getColumnDimension('F')->setWidth(70); // Set width kolom F
        $sheet->getColumnDimension('G')->setWidth(25); // Set width kolom G
        $sheet->getColumnDimension('H')->setWidth(33); // Set width kolom H
        $sheet->getColumnDimension('I')->setWidth(25); // Set width kolom I
        $sheet->getColumnDimension('J')->setWidth(30); // Set width kolom J
        $sheet->getColumnDimension('K')->setWidth(30); // Set width kolom K
        $sheet->getColumnDimension('L')->setWidth(30); // Set width kolom L
        $sheet->getColumnDimension('M')->setWidth(30); // Set width kolom M
        $sheet->getColumnDimension('N')->setWidth(30); // Set width kolom N
        $sheet->getColumnDimension('O')->setWidth(30); // Set width kolom O
        $sheet->getColumnDimension('P')->setWidth(30); // Set width kolom P
        $sheet->getColumnDimension('Q')->setWidth(30); // Set width kolom P
        $sheet->getColumnDimension('R')->setWidth(50); // Set width kolom P
        $sheet->getColumnDimension('S')->setWidth(50); // Set width kolom P
        $sheet->getColumnDimension('T')->setWidth(50); // Set width kolom P
        $sheet->getColumnDimension('U')->setWidth(50); // Set width kolom P
        $sheet->getColumnDimension('V')->setWidth(50); // Set width kolom P
        $sheet->getColumnDimension('W')->setWidth(50); // Set width kolom P
        $sheet->getColumnDimension('X')->setWidth(30); // Set width kolom P
        $sheet->getColumnDimension('Y')->setWidth(30); // Set width kolom P

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Data Commisioning");
        ob_end_clean();
        // Proses file excel
        $filename = "Data Commisioning - " . strtoupper($this->input->get('namamarketing')) . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    /**
     * Export All Data Pelanggan to Excel dengan Filter
     */
    public function exportAll()
    {
        grantAccessFor('all');

        // Get filter parameters
        $filter = [
            'status_customer' => $this->input->get('status_customer'),
            'tipe_bisnis' => $this->input->get('tipe_bisnis')
        ];

        $data = $this->md_pelanggan->getAllPelangganForExport($filter);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Style untuk header
        $style_col = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2196F3']
            ]
        ];

        // Style untuk data
        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];

        // Title
        $filterTitle = '';
        if (!empty($filter['status_customer'])) {
            $filterTitle .= ' - ' . $filter['status_customer'];
        }
        if (!empty($filter['tipe_bisnis'])) {
            $filterTitle .= ' - ' . $filter['tipe_bisnis'];
        }

        $sheet->setCellValue('A1', "DATA PELANGGAN" . $filterTitle);
        $sheet->mergeCells('A1:W1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header tabel
        $headers = [
            'A3' => 'No',
            'B3' => 'Nama Pelanggan/Instansi',
            'C3' => 'Status Customer',
            'D3' => 'Tipe Bisnis',
            'E3' => 'Kontak',
            'F3' => 'Email',
            'G3' => 'NIK',
            'H3' => 'Alamat',
            'I3' => 'Kota',
            'J3' => 'Provinsi',
            'K3' => 'PIC/Contact Person',
            'L3' => 'Status Pajak',
            'M3' => 'No NPWP',
            'N3' => 'Nama di NPWP',
            'O3' => 'Link NPWP',
            'P3' => 'Pengiriman Dokumen',
            'Q3' => 'Alamat Pengiriman Dokumen',
            'R3' => 'Alamat Penagihan',
            'S3' => 'Link Folder Berkas',
            'T3' => 'Tanggal Registrasi',
            'U3' => 'Kelas',
            'V3' => 'Marketing',
            'W3' => 'Date Created'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($style_col);
        }

        // Data
        $kolom = 4;
        $nomor = 1;
        foreach ($data as $row) {
            $tglRegistrasi = !empty($row->tanggal_registrasi) ? date('d-m-Y', strtotime($row->tanggal_registrasi)) : '';
            $dateCreated = !empty($row->date_created) ? date('d-m-Y H:i:s', strtotime($row->date_created)) : '';

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $row->nama)
                ->setCellValue('C' . $kolom, $row->status_customer)
                ->setCellValue('D' . $kolom, $row->tipe_bisnis);
            // Format kolom numerik sebagai TEXT agar tidak menjadi scientific notation
            $sheet->setCellValueExplicit('E' . $kolom, $this->cleanPhoneNumber($row->kontak), DataType::TYPE_STRING);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('F' . $kolom, $row->email);
            $sheet->setCellValueExplicit('G' . $kolom, $row->nik, DataType::TYPE_STRING);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('H' . $kolom, $row->alamat)
                ->setCellValue('I' . $kolom, $row->kota)
                ->setCellValue('J' . $kolom, $row->prov)
                ->setCellValue('K' . $kolom, $row->cpname)
                ->setCellValue('L' . $kolom, $row->status_pajak);
            $sheet->setCellValueExplicit('M' . $kolom, $row->npwp, DataType::TYPE_STRING);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('N' . $kolom, $row->nama_npwp)
                ->setCellValue('O' . $kolom, $row->link_npwp)
                ->setCellValue('P' . $kolom, $row->pengiriman_dokumen)
                ->setCellValue('Q' . $kolom, $row->alamat_pengiriman_dokumen)
                ->setCellValue('R' . $kolom, $row->alamat_penagihan)
                ->setCellValue('S' . $kolom, $row->link_folder_berkas)
                ->setCellValue('T' . $kolom, $tglRegistrasi)
                ->setCellValue('U' . $kolom, $row->kelas)
                ->setCellValue('V' . $kolom, $row->marketing)
                ->setCellValue('W' . $kolom, $dateCreated);

            // Apply style
            $sheet->getStyle('A' . $kolom . ':W' . $kolom)->applyFromArray($style_row);

            $kolom++;
            $nomor++;
        }

        // Set width kolom
        $widths = [
            'A' => 5,
            'B' => 40,
            'C' => 18,
            'D' => 20,
            'E' => 18,
            'F' => 25,
            'G' => 18,
            'H' => 50,
            'I' => 20,
            'J' => 20,
            'K' => 25,
            'L' => 15,
            'M' => 20,
            'N' => 30,
            'O' => 40,
            'P' => 20,
            'Q' => 50,
            'R' => 50,
            'S' => 40,
            'T' => 18,
            'U' => 10,
            'V' => 25,
            'W' => 20
        ];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->setTitle("Data Pelanggan");

        ob_end_clean();

        $filterName = str_replace(' ', '_', trim($filterTitle, ' - '));
        $filename = "Data_Pelanggan" . ($filterName ? "_" . $filterName : "") . "_" . date('Y-m-d_H-i-s') . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    /**
     * Download Template Import Excel
     */
    public function downloadTemplate()
    {
        grantAccessFor('all');

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Style untuk header
        $style_col = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50']
            ]
        ];

        // Title
        $sheet->setCellValue('A1', "TEMPLATE IMPORT DATA PELANGGAN");
        $sheet->mergeCells('A1:U1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Keterangan
        $sheet->setCellValue('A2', "* Kolom ID kosongkan jika data baru. Isi ID jika ingin update data existing.");
        $sheet->mergeCells('A2:U2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0000'));

        // Header tabel
        $headers = [
            'A4' => 'ID (Kosongkan untuk data baru)',
            'B4' => 'Nama Pelanggan/Instansi *',
            'C4' => 'Tipe Bisnis',
            'D4' => 'Kontak',
            'E4' => 'Email',
            'F4' => 'NIK',
            'G4' => 'Alamat',
            'H4' => 'Kota',
            'I4' => 'Provinsi',
            'J4' => 'PIC/Contact Person',
            'K4' => 'Status Pajak (PKP/NON PKP)',
            'L4' => 'No NPWP',
            'M4' => 'Nama di NPWP',
            'N4' => 'Link NPWP',
            'O4' => 'Pengiriman Dokumen (Hardfile/Softfile)',
            'P4' => 'Alamat Pengiriman Dokumen',
            'Q4' => 'Alamat Penagihan',
            'R4' => 'Link Folder Berkas',
            'S4' => 'Tanggal Registrasi (dd-mm-yyyy)',
            'T4' => 'Kelas RS',
            'U4' => 'Marketing'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($style_col);
        }

        // Contoh data
        $sheet->setCellValue('A5', '');
        $sheet->setCellValue('B5', 'RS Contoh');
        $sheet->setCellValue('C5', 'PRIVATE');
        $sheet->setCellValue('D5', '08123456789');
        $sheet->setCellValue('E5', 'contoh@email.com');
        $sheet->setCellValue('F5', '');
        $sheet->setCellValue('G5', 'Jl. Contoh No. 123');
        $sheet->setCellValue('H5', 'Jakarta Selatan');
        $sheet->setCellValue('I5', 'DKI Jakarta');
        $sheet->setCellValue('J5', 'John Doe');
        $sheet->setCellValue('K5', 'PKP');
        $sheet->setCellValue('L5', '01.234.567.8-901.000');
        $sheet->setCellValue('M5', 'PT Contoh');
        $sheet->setCellValue('N5', 'https://drive.google.com/...');
        $sheet->setCellValue('O5', 'Softfile');
        $sheet->setCellValue('P5', 'Jl. Pengiriman No. 456');
        $sheet->setCellValue('Q5', 'Jl. Penagihan No. 789');
        $sheet->setCellValue('R5', 'https://drive.google.com/...');
        $sheet->setCellValue('S5', date('d-m-Y'));
        $sheet->setCellValue('T5', 'A');
        $sheet->setCellValue('U5', 'Marketing A');

        // Set width kolom
        $widths = [
            'A' => 30,
            'B' => 35,
            'C' => 20,
            'D' => 18,
            'E' => 25,
            'F' => 18,
            'G' => 40,
            'H' => 20,
            'I' => 20,
            'J' => 25,
            'K' => 25,
            'L' => 25,
            'M' => 30,
            'N' => 35,
            'O' => 30,
            'P' => 35,
            'Q' => 35,
            'R' => 35,
            'S' => 25,
            'T' => 12,
            'U' => 20
        ];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $sheet->setTitle("Template Import");

        ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=Template_Import_Pelanggan.xlsx');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    /**
     * Import Data dari Excel
     * - Jika ID ada dan ditemukan di database → UPDATE
     * - Jika Nama sudah ada di database → UPDATE
     * - Jika data baru → INSERT
     */
    public function import()
    {
        grantAccessFor('all');

        $file_mimes = array(
            'application/octet-stream',
            'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            'application/excel',
            'application/vnd.msexcel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        if (!isset($_FILES['file_import']['name']) || !in_array($_FILES['file_import']['type'], $file_mimes)) {
            $this->session->set_flashdata('error', 'File tidak valid. Pastikan file berformat .xlsx');
            redirect('pelanggan');
            return;
        }

        $arr_file = explode('.', $_FILES['file_import']['name']);
        $extension = end($arr_file);

        if ('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }

        $spreadsheet = $reader->load($_FILES['file_import']['tmp_name']);
        $sheet = $spreadsheet->getActiveSheet();
        $sheetData = $sheet->toArray();
        $highestRow = $sheet->getHighestRow();

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        // Deteksi format file: Export atau Template Import
        // Cek header di baris 3 atau 4 untuk menentukan format
        $headerRow = $sheetData[2] ?? []; // Baris 3 (index 2) untuk Export
        $headerRow4 = $sheetData[3] ?? []; // Baris 4 (index 3) untuk Template

        $isExportFormat = false;
        $startRow = 4; // Default untuk template import (mulai dari index 4 = baris 5)

        // Deteksi format Export: Kolom C berisi "Status Customer"
        if (
            stripos($headerRow[2] ?? '', 'Status Customer') !== false ||
            stripos($headerRow4[2] ?? '', 'Status Customer') !== false
        ) {
            $isExportFormat = true;
            $startRow = 3; // Export mulai dari baris 4 (index 3)
        }

        // Jika kolom B baris 3 mengandung "Nama Pelanggan", ini format export
        if (stripos($headerRow[1] ?? '', 'Nama Pelanggan') !== false) {
            $isExportFormat = true;
            $startRow = 3;
        }

        $currentYear = date('Y');

        for ($i = $startRow; $i < count($sheetData); $i++) {
            $row = $sheetData[$i];

            // Skip jika nama kosong (kolom B = index 1)
            $nama = trim($row[1] ?? '');
            if (empty($nama)) {
                $skipped++;
                continue;
            }

            // Mapping kolom berdasarkan format
            if ($isExportFormat) {
                // === FORMAT EXPORT ===
                // A(0)=No, B(1)=Nama, C(2)=StatusCustomer, D(3)=TipeBisnis, E(4)=Kontak, F(5)=Email
                // G(6)=NIK, H(7)=Alamat, I(8)=Kota, J(9)=Provinsi, K(10)=PIC, L(11)=StatusPajak
                // M(12)=NPWP, N(13)=NamaNPWP, O(14)=LinkNPWP, P(15)=PengirimanDokumen
                // Q(16)=AlamatPengiriman, R(17)=AlamatPenagihan, S(18)=LinkFolderBerkas, T(19)=TglRegistrasi, U(20)=Kelas, V(21)=Marketing

                $id = !empty($row[0]) && is_numeric($row[0]) ? intval($row[0]) : null;
                $tipeBisnisExcel = trim($row[3] ?? '');
                $kontakExcel = trim($row[4] ?? '');
                $emailExcel = trim($row[5] ?? '');
                $nikExcel = trim($row[6] ?? '');
                $alamatExcel = trim($row[7] ?? '');
                $kotaExcel = trim($row[8] ?? '');
                $provExcel = trim($row[9] ?? '');
                $cpnameExcel = trim($row[10] ?? '');
                $statusPajakExcel = trim($row[11] ?? '');
                $npwpExcel = trim($row[12] ?? '');
                $namaNpwpExcel = trim($row[13] ?? '');
                $linkNpwpExcel = trim($row[14] ?? '');
                $pengirimanDokExcel = trim($row[15] ?? '');
                $alamatPengirimanExcel = trim($row[16] ?? '');
                $alamatPenagihanExcel = trim($row[17] ?? '');
                $linkFolderBerkasExcel = trim($row[18] ?? '');
                $tglRegColumn = 'T'; // Kolom T untuk tanggal registrasi
                $kelasExcel = trim($row[20] ?? '');
                $marketingExcel = trim($row[21] ?? '');
            } else {
                // === FORMAT TEMPLATE IMPORT ===
                // A(0)=ID, B(1)=Nama, C(2)=TipeBisnis, D(3)=Kontak, E(4)=Email, F(5)=NIK
                // G(6)=Alamat, H(7)=Kota, I(8)=Provinsi, J(9)=PIC, K(10)=StatusPajak
                // L(11)=NPWP, M(12)=NamaNPWP, N(13)=LinkNPWP, O(14)=PengirimanDokumen
                // P(15)=AlamatPengiriman, Q(16)=AlamatPenagihan, R(17)=LinkFolderBerkas, S(18)=TglRegistrasi, T(19)=Kelas, U(20)=Marketing

                $id = !empty($row[0]) && is_numeric($row[0]) ? intval($row[0]) : null;
                $tipeBisnisExcel = trim($row[2] ?? '');
                $kontakExcel = trim($row[3] ?? '');
                $emailExcel = trim($row[4] ?? '');
                $nikExcel = trim($row[5] ?? '');
                $alamatExcel = trim($row[6] ?? '');
                $kotaExcel = trim($row[7] ?? '');
                $provExcel = trim($row[8] ?? '');
                $cpnameExcel = trim($row[9] ?? '');
                $statusPajakExcel = trim($row[10] ?? '');
                $npwpExcel = trim($row[11] ?? '');
                $namaNpwpExcel = trim($row[12] ?? '');
                $linkNpwpExcel = trim($row[13] ?? '');
                $pengirimanDokExcel = trim($row[14] ?? '');
                $alamatPengirimanExcel = trim($row[15] ?? '');
                $alamatPenagihanExcel = trim($row[16] ?? '');
                $linkFolderBerkasExcel = trim($row[17] ?? '');
                $tglRegColumn = 'S'; // Kolom S untuk tanggal registrasi
                $kelasExcel = trim($row[19] ?? '');
                $marketingExcel = trim($row[20] ?? '');
            }

            // Check apakah data sudah ada berdasarkan ID atau Nama
            $existing = $this->md_pelanggan->checkPelangganByIdOrNama($id, $nama);

            // Parse tanggal registrasi dari Excel
            // Baca langsung dari cell untuk mendapatkan nilai raw
            $rowNum = $i + 1; // Karena Excel row dimulai dari 1
            $cellValue = $sheet->getCell($tglRegColumn . $rowNum)->getValue();
            $tanggalRegistrasi = null;

            if (!empty($cellValue)) {
                // Jika nilai adalah angka (Excel serial date)
                if (is_numeric($cellValue)) {
                    try {
                        $tanggalRegistrasi = Date::excelToDateTimeObject($cellValue)->format('Y-m-d');
                    } catch (Exception $e) {
                        // Fallback jika konversi gagal
                        $tanggalRegistrasi = null;
                    }
                } else {
                    // Jika nilai adalah string tanggal
                    $tglRegExcel = trim($cellValue);

                    // Coba berbagai format tanggal
                    $formats = ['d-m-Y', 'd/m/Y', 'Y-m-d', 'd-M-Y', 'd M Y', 'Y/m/d', 'm/d/Y', 'd.m.Y'];

                    foreach ($formats as $format) {
                        $parsedDate = date_create_from_format($format, $tglRegExcel);
                        if ($parsedDate) {
                            $errors_arr = date_get_last_errors();
                            if (!$errors_arr || ($errors_arr['warning_count'] == 0 && $errors_arr['error_count'] == 0)) {
                                $tanggalRegistrasi = $parsedDate->format('Y-m-d');
                                break;
                            }
                        }
                    }

                    // Fallback: coba strtotime
                    if ($tanggalRegistrasi === null) {
                        $timestamp = strtotime($tglRegExcel);
                        if ($timestamp !== false && $timestamp > 0) {
                            $tanggalRegistrasi = date('Y-m-d', $timestamp);
                        }
                    }
                }
            }

            // Prepare data menggunakan variabel yang sudah di-mapping berdasarkan format
            $data = [
                'identitas_pelanggan' => $nama,
                'kontak' => $this->cleanPhoneNumber($kontakExcel),
                'email' => $emailExcel,
                'nik' => $nikExcel,
                'alamat' => $alamatExcel,
                'kota' => $kotaExcel,
                'provinsi' => $provExcel,
                'cpname' => $cpnameExcel,
                'status_pajak' => in_array(strtoupper($statusPajakExcel), ['PKP', 'NON PKP']) ? strtoupper($statusPajakExcel) : 'NON PKP',
                'npwp' => $npwpExcel,
                'nama_npwp' => $namaNpwpExcel,
                'link_npwp' => $linkNpwpExcel,
                'pengiriman_dokumen' => in_array(ucfirst(strtolower($pengirimanDokExcel)), ['Hardfile', 'Softfile']) ? ucfirst(strtolower($pengirimanDokExcel)) : 'Softfile',
                'alamat_pengiriman_dokumen' => $alamatPengirimanExcel,
                'alamat_penagihan' => $alamatPenagihanExcel,
                'link_folder_berkas' => $linkFolderBerkasExcel,
                'kelas' => $kelasExcel,
                'marketing' => $marketingExcel,
            ];

            if ($existing) {
                // === UPDATE DATA EXISTING ===
                $updateData = [];

                foreach ($data as $key => $value) {
                    // Update semua field dari Excel (termasuk jika kosong, akan ditimpa)
                    // Kecuali untuk field tertentu yang ingin dipertahankan jika kosong di Excel
                    $updateData[$key] = $value;
                }

                // TIPE BISNIS: Selalu gunakan dari Excel (jika kosong = kosong, jika ada = update)
                $updateData['tipe_bisnis'] = $tipeBisnisExcel;

                // TANGGAL REGISTRASI: Selalu gunakan dari Excel
                // Jika Excel kosong, set NULL (akan dianggap Existing Customer)
                // Jika Excel ada tanggal, gunakan tanggal tersebut
                if ($tanggalRegistrasi !== null) {
                    $updateData['tanggal_registrasi'] = $tanggalRegistrasi;
                }
                // Jika tanggal registrasi kosong di Excel, TIDAK update field ini (pertahankan yang ada di DB)

                if (!empty($updateData)) {
                    $this->md_pelanggan->update($existing->id_pelanggan, $updateData);
                    $updated++;
                }
            } else {
                // === INSERT DATA BARU ===
                $data['status'] = 1;
                $data['tanggal'] = date('Y-m-d');

                // TIPE BISNIS: Gunakan dari Excel (bisa kosong)
                $data['tipe_bisnis'] = $tipeBisnisExcel;

                // TANGGAL REGISTRASI: 
                // - Jika ada di Excel, gunakan itu (bisa jadi Existing jika tahun lama)
                // - Jika kosong, set ke hari ini (New Customer)
                if ($tanggalRegistrasi !== null) {
                    $data['tanggal_registrasi'] = $tanggalRegistrasi;
                } else {
                    // Set ke hari ini = New Customer
                    $data['tanggal_registrasi'] = date('Y-m-d');
                }

                $this->md_pelanggan->addPelanggan($data);
                $imported++;
            }
        }

        addLog('Import Pelanggan', "Import data pelanggan: {$imported} data baru, {$updated} data diupdate, {$skipped} baris dilewati");

        $message = "Import berhasil! {$imported} data baru ditambahkan, {$updated} data diupdate.";
        if ($skipped > 0) {
            $message .= " {$skipped} baris dilewati (nama kosong).";
        }
        $this->session->set_flashdata('success', $message);
        redirect('pelanggan');
    }

    /**
     * Get Statistik untuk AJAX
     */
    public function getStatistik()
    {
        grantAccessFor('all');
        $statistik = $this->md_pelanggan->getStatistik();
        echo json_encode($statistik);
    }
}
