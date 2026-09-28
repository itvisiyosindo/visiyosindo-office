<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


// defined('BASEPATH') or exit('No direct script access allowed');

class Salary_thr extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_salary_thr');
        $this->load->model('md_komisi');
        $this->load->model('md_pendapatan_lain');
        $this->load->helper('mandatory_helper');
        $this->load->helper('terbilang_helper');
        $this->load->helper('encrypt_helper');
        $this->load->helper('whatsapp_helper');
    }

    function id_navbar()
    {
        $id_navbar = "kepegawaian";
        return $id_navbar;
    }

    public function index()
    {
        // grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        // 1. HITUNG JUMLAH NATAL (Hanya yang punya NPP)
        $this->db->join('data_jenis_thr djt', 'p.pengguna_id = djt.pengguna_id', 'left');
        $this->db->where('djt.tipe_thr', 'NATAL');
        $this->db->where('p.is_active', 1);
        $this->db->where('p.status', 1);

        // --- FILTER BARU: Hanya Karyawan dengan NPP ---
        $this->db->where("p.no_pegawai != ''");
        $this->db->where("p.no_pegawai IS NOT NULL");
        // ----------------------------------------------

        $page_data['total_natal'] = $this->db->count_all_results('pengguna p');


        // 2. HITUNG JUMLAH IDUL FITRI (Hanya yang punya NPP)
        // Menggunakan Query Builder agar lebih aman dan rapi
        $this->db->select('COUNT(*) as total');
        $this->db->from('pengguna p');
        $this->db->join('data_jenis_thr djt', 'p.pengguna_id = djt.pengguna_id', 'left');

        // Kondisi: Aktif & (Idul Fitri ATAU Null)
        $this->db->where('p.is_active', 1);
        $this->db->where('p.status', 1);
        $this->db->group_start(); // Kurung buka (
        $this->db->where('djt.tipe_thr', 'IDUL_FITRI');
        $this->db->or_where('djt.tipe_thr IS NULL');
        $this->db->group_end();   // Kurung tutup )

        // --- FILTER BARU: Hanya Karyawan dengan NPP ---
        $this->db->where("p.no_pegawai != ''");
        $this->db->where("p.no_pegawai IS NOT NULL");
        $this->db->where('p.level !=', 'Administrator');
        // ----------------------------------------------

        $query_fitri = $this->db->get();
        $page_data['total_fitri'] = $query_fitri->row()->total;
        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'salary/v_salary_thr';
        $page_data['page_title'] = 'Salary THR';
        $page_data['page_desc'] = 'Management THR Karyawan';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $data['pengguna_id'] = decrypt($this->input->post('pengguna_id'));
        $data['gaji_pokok'] = $this->input->post('gaji_pokok') ? delete_currency($this->input->post('gaji_pokok')) : 0;
        $data['tunjangan_jabatan'] = $this->input->post('tunjangan_jabatan') ? delete_currency($this->input->post('tunjangan_jabatan')) : 0;
        $data['dasar_potong_bpjs'] = $this->input->post('dasar_potongan_bpjs') ? delete_currency($this->input->post('dasar_potongan_bpjs')) : 0;
        $data['dasar_potong_bpjs_tk'] = $this->input->post('dasar_potongan_bpjs_tk') ? delete_currency($this->input->post('dasar_potongan_bpjs_tk')) : 0;
        $this->md_salary_thr->addRiwayatSalary($data);

        //update latest riwayat salary
        $data2['id_latestriwayat_salary'] = $this->db->insert_id();
        $this->md_pengguna->updatePengguna($data['pengguna_id'], $data2);

        /** LOG */
        $tmp = $this->md_pengguna->getById($data['pengguna_id']);
        addLog('Update Salary', 'Mengupdate salary "' . $tmp[0]->nama . '"');
        ajaxReturnDie('success', 'Salary Berhasil di Edit', TRUE);
    }

    public function add_komisi()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $id_pengguna = decrypt($this->input->post('pengguna_id'));
        $data['jumlah'] = $this->input->post('komisi') ? delete_currency($this->input->post('komisi')) : 0;
        $data['bulan'] = date_db_format($this->input->post('bulan'));

        // print_r($data);die;
        $this->md_komisi->addKomisi($data);

        $data2['id_komisi'] = $this->db->insert_id();
        $this->md_pengguna->updatePengguna($id_pengguna, $data2);

        /** LOG */
        $tmp = $this->md_pengguna->getById($id_pengguna);
        addLog('Update Komisi', 'Mengupdate komisi "' . $tmp[0]->nama . '"');
        ajaxReturnDie('success', 'Komisi Berhasil di Edit', TRUE);
    }


    public function add_pendapatan_lain()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $id_pengguna = decrypt($this->input->post('pengguna_id'));
        $data['jumlah'] = $this->input->post('pendapatan') ? delete_currency($this->input->post('pendapatan')) : 0;
        $data['pengurangan'] = $this->input->post('pengurangan') ? delete_currency($this->input->post('pengurangan')) : 0;

        // print_r($data);die;
        $this->md_pendapatan_lain->add_pendapatan_lain($data);

        $data2['id_pendapatan_lain'] = $this->db->insert_id();
        $this->md_pengguna->updatePengguna($id_pengguna, $data2);

        /** LOG */
        $tmp = $this->md_pengguna->getById($id_pengguna);
        addLog('Update Pendapatan Lain', 'Mengupdate pendapatan lain "' . $tmp[0]->nama . '"');
        ajaxReturnDie('success', 'Pendapatan Lain Berhasil di Edit', TRUE);
    }

    public function show($param = "", $param2 = "")
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga', 85]);


        if ($param == 'detail_salary') {
            $page_data['switch'] = $this->id_navbar();
            $page_data['pengguna'] = $this->md_pengguna->getById(decrypt($param2));
            $page_data['page_name'] = 'salary/v_detail_salary';
            $page_data['page_title'] = 'Detail Salary';
            $page_data['page_desc'] = 'Riwayat Perubahan Salary Karyawan';
            $this->load->view('index', $page_data);
        } else if ($param == 'print') {
            // Data tunjangan
            $where = ['p.status' => 1, 'p.is_active' => 1, 'p.pengguna_id !=' => 1];
            $pengguna = $this->md_pengguna->getBywhere($where);
            $total = [];
            $salary_data = [];
            foreach ($pengguna as $row) {
                $tmp = $this->md_salary_thr->getById($row->id_latestriwayat_salary);
                $salary = '';
                // $pph21 = '';
                $data['nama'] = $row->nama;
                $data['rek'] = $row->no_rek;
                if ($tmp) {
                    foreach ($tmp as $row2) {
                        $salary = $param2 == 'gapok' ? $row2->gaji_pokok : $row2->tunjangan_jabatan;
                        // $pph21 = $param2 == 'gapok' ? $row2->gaji_pokok : 0;
                    }
                }
                $data['salary'] = $salary;
                array_push($salary_data, $data);
                array_push($total, $salary);
            }

            //load mpdf dan membuat page size legal
            $mpdf = new Mpdf(['format' => 'Legal']);
            //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
            $mpdf->AddPage('P');

            $dt = [
                'title_pdf' => 'Rekapitulasi Tunjangan Jabatan',
                'object' => $param2,
                'salary_data' => $salary_data,
                'total' => array_sum($total)
            ];

            // filename dari pdf ketika didownload
            $file_pdf = 'Rekapitulasi Tunjangan Jabatan';

            // page htmk yang akan di jadikan ke pdf
            $html = $this->load->view('pages/v_print/print_salary', $dt, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output($file_pdf . '.pdf', 'I');
        }
    }


    public function edit($param)
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $pengguna_id = decrypt($param);
        $id_salary = $this->md_pengguna->getById($pengguna_id)[0]->id_latestriwayat_salary;
        $dt = $this->md_salary_thr->getById($id_salary);
        $dt[0]->pengguna_id = encrypt($dt[0]->pengguna_id);
        $dt[0]->id_riwayat_salary = encrypt($dt[0]->id_riwayat_salary);
        $dt[0]->gaji_pokok = $dt[0]->gaji_pokok ? rupiah($dt[0]->gaji_pokok) : '';
        $dt[0]->tunjangan_jabatan = $dt[0]->tunjangan_jabatan ? rupiah($dt[0]->tunjangan_jabatan) : '';
        $dt[0]->dasar_potong_bpjs = $dt[0]->dasar_potong_bpjs ? rupiah($dt[0]->dasar_potong_bpjs) : '';
        $dt[0]->dasar_potong_bpjs_tk = $dt[0]->dasar_potong_bpjs_tk ? rupiah($dt[0]->dasar_potong_bpjs_tk) : '';
        echo json_encode($dt);
        die;
    }

    public function edit_komisi($param)
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $pengguna_id = decrypt($param);
        $id_komisi = $this->md_pengguna->getById($pengguna_id)[0]->id_komisi;
        $dt = $this->md_komisi->getById($id_komisi);
        // echo_array($dt);die;
        $dt[0]->pengguna_id = encrypt($dt[0]->pengguna_id);
        $dt[0]->id_komisi = encrypt($dt[0]->id_komisi);
        $dt[0]->jumlah = $dt[0]->jumlah ? rupiah($dt[0]->jumlah) : '';
        $dt[0]->bulan = $dt[0]->bulan;
        echo json_encode($dt);
        die;
    }

    public function edit_pendapatan_lain($param)
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $pengguna_id = decrypt($param);
        $id_pendapatan_lain = $this->md_pengguna->getById($pengguna_id)[0]->id_pendapatan_lain;
        $dt = $this->md_pendapatan_lain->getById($id_pendapatan_lain);
        // echo_array($dt);die;
        $dt[0]->pengguna_id = encrypt($dt[0]->pengguna_id);
        $dt[0]->id_pendapatan = encrypt($dt[0]->id_pendapatan);
        $dt[0]->jumlah = $dt[0]->jumlah ? rupiah($dt[0]->jumlah) : '';
        $dt[0]->pengurangan = $dt[0]->pengurangan ? rupiah($dt[0]->pengurangan) : '';
        echo json_encode($dt);
        die;
    }

    public function getAllRiwayat($param = "")
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $pengguna_id = decrypt($param);
        $dt = $this->md_salary_thr->getRiwayatByIdPengguna($pengguna_id);
        $start = $this->input->post('start');
        $data = array();
        foreach ($dt['data'] as $row) {
            $th = array();
            $th[] = ++$start . '.';
            $th[] = isset($row->gaji_pokok) ? rupiah($row->gaji_pokok) : '';
            $th[] = isset($row->tunjangan_jabatan) ? rupiah($row->tunjangan_jabatan) : '';
            $th[] = date_view_format($row->data_created);
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function kirimnotifikasi($id)
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $month = date("Y-m");

        $this->db->where('tutupbuku =', 0);
        $this->db->where('year(data_created) =', date('Y'));
        $this->db->where('month(data_created) =', date('m'));
        $this->db->where('idpengguna', decrypt($id));
        $query = $this->db->get('riwayat_salary_tutupbuku');
        $jumlah = $query->num_rows();
        $tglcetak = date('Y-m-d H:i:s');
        $tglcetakstamp = strtotime($tglcetak);
        $row = $query->result();
        if ($jumlah > 0) {
            $myObj = encrypt($row[0]->idpengguna) . "," . $tglcetakstamp;
            $parJSON = encryptvym($myObj);
            $dataWa = [
                'noPenerima'     => $row[0]->nowhatsapp, // 33,
                'namaSurat'     => 'Slip Gaji ' . getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
                'penerima'         => $row[0]->nama,
                'perihal'         => '',
                'kode'             => $row[0]->npp
            ];
            waSlipGaji($dataWa, 'https://office.visiyosindo.id/salary/print_slip/' . $parJSON);
        }
    }

    public function tutupbuku()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $month = date("Y-m");
        $this->db->where('tutupbuku =', 0);
        $this->db->where('year(data_created) =', date('Y'));
        $this->db->where('month(data_created) =', date('m'));
        $query = $this->db->get('riwayat_salary_tutupbuku');
        $jumlah = $query->num_rows();

        if ($jumlah <= 0) {
            $dt    =  $this->md_salary_thr->addRiwayatSalaryTutupBuku((date('m', strtotime($month))), date('Y', strtotime($month)), sessPenggunaId());
        }


        $month = date("Y-m");

        $page_data['switch'] = $this->id_navbar();
        $page_data['id_pengguna'] = sessPenggunaId();
        $page_data['page_name']       = 'salary/v_salarytutupbuku';
        $page_data['page_title']      = 'Tutup Buku Penggajian';
        $page_data['page_desc']       = 'Data Penggajian';

        $tglcetak = date('Y-m-d H:i:s');
        $tglcetakstamp = strtotime($tglcetak);




        if ($jumlah > 0) {
            foreach ($query->result() as $row) {
                //if($row->idpengguna==715){
                //send notif wa
                $myObj = encrypt($row->idpengguna) . "," . $tglcetakstamp;
                $parJSON = encryptvym($myObj);
                $dataWa = [
                    'noPenerima'     => $row->nowhatsapp, // 33,
                    'namaSurat'     => 'Slip Gaji ' . getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
                    'penerima'         => $row->nama,
                    'perihal'         => '',
                    'kode'             => $row->npp
                ];
                waSlipGaji($dataWa, 'https://office.visiyosindo.id/salary/print_slip/' . $parJSON);
                //}
            }
            $this->load->view('index', $page_data);
        }
        //$data = $this->md_salary->getRiwayatTutupBuku()->num_rows();

    }

    public function print_slip($id)
    {
        grantAccessFor('all');
        //load mpdf dan membuat page size legal
        $mpdf = new Mpdf(['format' => 'Legal']);
        //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
        $mpdf->AddPage('P');
        // Data bulan, jika tidak ada dipilih mengambil hari pertama di bulan sebelum nya
        $month = date("Y-m");

        $dataid = explode(",", decryptvym($id));
        $idpengguna = decrypt($dataid[0]);

        // Data tunjangan
        $data = [
            'dt' => $this->md_pengguna->getById($idpengguna),
            'title_pdf' => 'SLIP GAJI KARYAWAN',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month,
            'potonganlain' => potongan_lain($idpengguna),
            'tglcetak' => $dataid[1],
            'dtgaji' => $this->md_salary_thr->getSalaryTerakhirTutupBukuByPenggunaID($idpengguna)
        ];



        // filename dari pdf ketika didownload
        $file_pdf = 'SLIP GAJI KARYAWAN ' . $data['periode'];

        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_slip_gaji', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }

    public function print($param = "")
    {
        // 1. Setup PDF
        $mpdf = new Mpdf(['format' => 'Legal']);
        $mpdf->AddPage('L');
        $month = date("Y-m");

        // 2. Ambil Semua Data Gaji
        $raw_data = $this->md_salary_thr->getSalaryFULLVYM((date('m', strtotime($month))), date('Y', strtotime($month)));

        // 3. Ambil List ID Karyawan NATAL
        $query_natal = $this->db->get_where('data_jenis_thr', ['tipe_thr' => 'NATAL'])->result_array();
        $ids_natal = array_column($query_natal, 'pengguna_id');

        // 4. LOGIKA FILTER DATA (Pisahkan isinya, tapi Judul sama)
        $filtered_data = [];

        if ($param == 'natal') {
            // --- TOMBOL MERAH (KHUSUS NATAL) ---
            // Hanya ambil karyawan yang ID-nya ADA di daftar Natal
            foreach ($raw_data as $row) {
                if (in_array($row['pengguna_id'], $ids_natal)) {
                    $filtered_data[] = $row;
                }
            }
        } else {
            // --- TOMBOL HIJAU (DEFAULT/IDUL FITRI) ---
            // Ambil karyawan yang ID-nya TIDAK ADA di daftar Natal
            foreach ($raw_data as $row) {
                if (!in_array($row['pengguna_id'], $ids_natal)) {
                    $filtered_data[] = $row;
                }
            }
        }

        // 5. JUDUL SERAGAM (Sesuai Request)
        // Apapun filternya, judul tetap satu.
        $title = 'REKAPITULASI THR KARYAWAN';

        $data = [
            'dt' => $this->md_pengguna->getByWherenotIn(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator'], [58, 47, 84, 714, 77, 79, 110, 87, 72, 70, 81, 69, 83, 107, 86, 74, 57, 744, 747, 742]),
            'dtgaji' => $filtered_data, // Data yang dikirim sudah terfilter (Natal saja / Non-Natal saja)
            'title_pdf' => $title,      // Judul Tetap Sama
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month
        ];

        $file_pdf = $title . ' Periode ' . $data['periode'];
        $html = $this->load->view('pages/v_print/print_salary_thr', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }

    public function print_pihak($param2 = "")
    {

        //load mpdf dan membuat page size legal
        $mpdf = new Mpdf(['format' => 'Legal']);

        //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
        $mpdf->AddPage('L');
        // Data bulan, jika tidak ada dipilih mengambil hari pertama di bulan sebelum nya
        $month = date("Y-m");
        // Data tunjangan
        $data = [
            'dt' => $this->md_pengguna->getByWherenotIn(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator'], [58, 47, 84, 714, 77, 79, 110, 87, 72, 70, 81, 69, 83, 107, 86, 74, 57, 744, 747, 742]),
            'dtgaji' => $this->md_salary_thr->getSalaryFULLVYM((date('m', strtotime($month))), date('Y', strtotime($month))),
            'title_pdf' => 'REKAPITULASI THR PIHAK KETIGA',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month
        ];
        // filename dari pdf ketika didownload
        $file_pdf = 'REKAPITULASI THR PIHAK KETIGA  Periode ' . $data['periode'];

        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_salary_thr_pihak', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }


    /**
     * Export THR Idul Fitri ke Excel
     */
    public function export_excel()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $this->_generate_excel('idul_fitri');
    }

    /**
     * Export THR Natal ke Excel
     */
    public function export_excel_natal()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $this->_generate_excel('natal');
    }

    /**
     * Generate file Excel THR berdasarkan tipe (idul_fitri / natal)
     */
    private function _generate_excel($tipe = 'idul_fitri')
    {
        $month = date("Y-m");

        // Ambil semua data gaji
        $raw_data = $this->md_salary_thr->getSalaryFULLVYM((date('m', strtotime($month))), date('Y', strtotime($month)));

        // Ambil list ID karyawan NATAL
        $query_natal = $this->db->get_where('data_jenis_thr', ['tipe_thr' => 'NATAL'])->result_array();
        $ids_natal = array_column($query_natal, 'pengguna_id');

        // Filter data berdasarkan tipe
        $filtered_data = [];
        if ($tipe == 'natal') {
            foreach ($raw_data as $row) {
                if (in_array($row['pengguna_id'], $ids_natal)) {
                    $filtered_data[] = $row;
                }
            }
        } else {
            foreach ($raw_data as $row) {
                if (!in_array($row['pengguna_id'], $ids_natal)) {
                    $filtered_data[] = $row;
                }
            }
        }

        // Buat Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Style header
        $style_col = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
        ];

        // Style data rows
        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ];

        // Style currency (rata kanan)
        $style_currency = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
        ];

        $periode = getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month));
        $title = 'REKAPITULASI THR KARYAWAN';
        $sub_title = ($tipe == 'natal') ? 'Penerima THR Natal' : 'Penerima THR Idul Fitri';

        // Judul
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', $sub_title . ' - Periode ' . $periode);
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header tabel (baris 4)
        $headers = ['No', 'Nama Karyawan', 'NPP', 'Jabatan', 'Lama Kerja', 'Gaji Pokok', 'Tunjangan Jabatan', 'Total THR', 'Nomor Rekening'];
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        foreach ($headers as $i => $header) {
            $sheet->setCellValue($columns[$i] . '4', $header);
        }
        $sheet->getStyle('A4:I4')->applyFromArray($style_col);

        // Isi data
        $rowNum = 5;
        $no = 1;
        $totalThr = 0;

        foreach ($filtered_data as $row) {
            $lama = $row['tahun'] . ' Tahun ' . $row['bulan'] . ' Bulan ' . $row['hari'] . ' Hari';
            $gapok = isset($row['gajipokok']) ? $row['gajipokok'] : 0;
            $tunjanganJabatan = isset($row['tunjanganjabatan']) ? $row['tunjanganjabatan'] : 0;
            $npp = isset($row['npp']) ? $row['npp'] : '';
            $jabatan = isset($row['jabatan']) ? $row['jabatan'] : '';
            $norek = isset($row['norek']) ? $row['norek'] : '-';

            if ($row['tahun'] > 0) {
                $thr = $gapok + $tunjanganJabatan;
            } else {
                $temptahun = $row['bulan'];
                $thr = ($gapok + $tunjanganJabatan) * $temptahun / 12;
            }

            $totalThr += $thr;

            $sheet->setCellValue('A' . $rowNum, $no);
            $sheet->setCellValue('B' . $rowNum, $row['nama']);
            $sheet->setCellValue('C' . $rowNum, $npp);
            $sheet->setCellValue('D' . $rowNum, $jabatan);
            $sheet->setCellValue('E' . $rowNum, $lama);
            $sheet->setCellValue('F' . $rowNum, $gapok);
            $sheet->setCellValue('G' . $rowNum, $tunjanganJabatan);
            $sheet->setCellValue('H' . $rowNum, $thr);
            $sheet->setCellValue('I' . $rowNum, $norek);

            // Apply style border
            $sheet->getStyle('A' . $rowNum . ':I' . $rowNum)->applyFromArray($style_row);

            // Center align No, NPP, Norek
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Format angka currency
            $sheet->getStyle('F' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('G' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('H' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');

            $no++;
            $rowNum++;
        }

        // Total row
        $sheet->setCellValue('A' . $rowNum, '');
        $sheet->mergeCells('A' . $rowNum . ':G' . $rowNum);
        $sheet->setCellValue('A' . $rowNum, 'Total Keseluruhan');
        $sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('H' . $rowNum, $totalThr);
        $sheet->getStyle('H' . $rowNum)->getFont()->setBold(true);
        $sheet->getStyle('H' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('A' . $rowNum . ':I' . $rowNum)->applyFromArray($style_row);

        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(22);

        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->setTitle('THR Karyawan');

        // Output file
        ob_end_clean();
        $tipe_label = ($tipe == 'natal') ? 'Natal' : 'Idul Fitri';
        $filename = 'Rekapitulasi THR ' . $tipe_label . ' Periode ' . $periode . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function pagination()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $dt = $this->md_pengguna->getAllPenggunaAktif();
        $start = $this->input->post('start');
        $data = array();
        $bulan = date("Y-m");
        $tglcetak = date('Y-m-d H:i:s');
        $tglcetakstamp = strtotime($tglcetak);

        foreach ($dt['data'] as $row) {
            $id = encrypt($row->pengguna_id);
            $pengguna = $this->md_pengguna->getById($row->pengguna_id);
            $salary = $this->md_salary_thr->getById($pengguna[0]->id_latestriwayat_salary);
            $nama_pengguna = '<a href="salary/show/detail_salary/' . $id . '")>' . $row->nama . '</a>';
            $bpjskes = tunjanganBPJSKesehatan($row->pengguna_id);
            $bpjstk = tunjanganBPJStk($row->pengguna_id);
            $komisi = komisi($row->pengguna_id);
            $pendapatan_lain = pendapatan_lain($row->pengguna_id);
            $myObj = $id . "," . $tglcetakstamp;
            $parJSON = encryptvym($myObj);
            $li_btn = '
                <div class="btn-group" role="group" aria-label="First group">
                <button type="button" title="Tambah Pendapatan Lain" class="btn btn-sm btn-info btn-pen_lain" data-id="' . $id . '"><i class="bx bx-message-alt-add"></i></button>
                    <a target="_self" class="btn btn-danger" href="salary/print_slip/' . $parJSON . '">Slip Gaji</a>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $nama_pengguna;
            $th[] = isset($salary[0]->gaji_pokok) ? rupiah($salary[0]->gaji_pokok) : '';
            $th[] = $bpjskes ? rupiah($bpjskes) : '-';
            $th[] = $bpjstk ? rupiah($bpjstk) : '-';
            $th[] = $komisi ? rupiah($komisi) : '-';
            $th[] = $pendapatan_lain ? rupiah($pendapatan_lain) : '-';
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
