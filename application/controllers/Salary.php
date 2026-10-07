<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;


// defined('BASEPATH') or exit('No direct script access allowed');

class Salary extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_salary');
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

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'salary/v_salary';
        $page_data['page_title'] = 'Salary';
        $page_data['page_desc'] = 'Management Salary Tetap Karyawan';
        $this->load->view('index', $page_data);
    }

    public function freelance()
    {
        // grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'salary/v_salary_freelance';
        $page_data['page_title'] = 'Salary Freelance';
        $page_data['page_desc'] = 'Management Salary Freelance';
        $this->load->view('index', $page_data);
    }

    public function history_Salary()
    {
        // grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'salary/v_salary_history';
        $page_data['page_title'] = 'Salary';
        $page_data['page_desc'] = 'Management Salary Tetap Karyawan';
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
        $this->md_salary->addRiwayatSalary($data);

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
                $tmp = $this->md_salary->getById($row->id_latestriwayat_salary);
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
        $pengguna_data = $this->md_pengguna->getById($pengguna_id);
        $id_salary = !empty($pengguna_data) ? $pengguna_data[0]->id_latestriwayat_salary : null;
        $dt = !empty($id_salary) ? $this->md_salary->getById($id_salary) : null;
        if (empty($dt)) {
            $latest = $this->md_salary->get_latest_salary($pengguna_id);
            if (!empty($latest)) {
                $dt = [(object)$latest];
            }
        }
        if (empty($dt)) {
            $new_salary = new stdClass();
            $new_salary->pengguna_id = encrypt($pengguna_id);
            $new_salary->id_riwayat_salary = '';
            $new_salary->gaji_pokok = '';
            $new_salary->tunjangan_jabatan = '';
            $new_salary->dasar_potong_bpjs = '';
            $new_salary->dasar_potong_bpjs_tk = '';
            echo json_encode([$new_salary]);
            die;
        }
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
        if (empty($dt)) {
            $new_komisi = new stdClass();
            $new_komisi->pengguna_id = encrypt($pengguna_id);
            $new_komisi->id_komisi = '';
            $new_komisi->jumlah = '';
            $new_komisi->bulan = '';
            echo json_encode([$new_komisi]);
            die;
        }
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
        if (empty($dt)) {
            $new_pendapatan = new stdClass();
            $new_pendapatan->pengguna_id = encrypt($pengguna_id);
            $new_pendapatan->id_pendapatan = '';
            $new_pendapatan->jumlah = '';
            $new_pendapatan->pengurangan = '';
            echo json_encode([$new_pendapatan]);
            die;
        }
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
        $dt = $this->md_salary->getRiwayatByIdPengguna($pengguna_id);
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
            $id_pengguna = encrypt($row[0]->idpengguna);
            $dataWa = [
                'noPenerima'     => $row[0]->nowhatsapp, // 33,
                'namaSurat'     => 'Slip Gaji ' . getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
                'penerima'         => $row[0]->nama,
                'perihal'         => '',
                'kode'             => $row[0]->npp
            ];
            waSlipGaji($dataWa, 'https://office.visiyosindo.id/salary/print_slip_month/' . $month . '/' . $id_pengguna);
        }


        addlog('Salary', 'Tutup Buku dan Kirim Notif dengan IP : ' . $_SERVER['REMOTE_ADDR']);
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
            $dt    =  $this->md_salary->addRiwayatSalaryTutupBuku((date('m', strtotime($month))), date('Y', strtotime($month)), sessPenggunaId());
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
                $id_pengguna = encrypt($row->idpengguna);
                $dataWa = [
                    'noPenerima'     => $row->nowhatsapp, // 33,
                    'namaSurat'     => 'Slip Gaji ' . getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
                    'penerima'         => $row->nama,
                    'perihal'         => '',
                    'kode'             => $row->npp
                ];
                waSlipGaji($dataWa, 'https://office.visiyosindo.id/salary/print_slip_month/' . $month . '/' . $id_pengguna);
                //}
            }
            $this->load->view('index', $page_data);
        }
        //$data = $this->md_salary->getRiwayatTutupBuku()->num_rows();


        addlog('Salary', 'Tutup Buku dan Kirim Notif dengan IP : ' . $_SERVER['REMOTE_ADDR']);
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

        //$month = '2024-9';

        $dataid = explode(",", decryptvym($id));
        $idpengguna = decrypt($dataid[0]);

        $dtgaji = $this->md_salary->getSalaryTerakhirTutupBukuByPenggunaID($idpengguna);
        if (empty($dtgaji)) {
            $pengguna_info = $this->md_pengguna->getById($idpengguna);
            if (!empty($pengguna_info)) {
                $dtgaji = (object)[
                    'nama' => $pengguna_info[0]->nama,
                    'jabatan' => $pengguna_info[0]->jabatan ?? '-',
                    'npp' => $pengguna_info[0]->no_pegawai ?? '-',
                    'status' => $pengguna_info[0]->status_karyawan ?? '-'
                ];
            }
        }

        $dtsalary = $this->md_salary->getSalaryHistoryPenggunaID($idpengguna);
        if (empty($dtsalary)) {
            $pengguna_data = $this->md_pengguna->getById($idpengguna);
            if (!empty($pengguna_data) && !empty($pengguna_data[0]->id_latestriwayat_salary)) {
                $latest = $this->md_salary->getById($pengguna_data[0]->id_latestriwayat_salary);
                if (!empty($latest)) {
                    $dtsalary = (object)[
                        'gajipokok' => $latest[0]->gaji_pokok ?? 0,
                        'tunjanganjabatan' => $latest[0]->tunjangan_jabatan ?? 0,
                        'tunjangankonsumsi' => 0,
                        'tunjangankinerja' => 0,
                        'tunjangankomunikasi' => 0,
                        'tunjangantransport' => 0,
                        'tunjanganraya' => 0,
                        'bonus' => 0,
                        'pendapatanlain' => 0,
                        'pendapatanlain_tidaktetap' => 0,
                        'tunjanganbbm' => 0,
                        'bpjskesehatan' => tunjanganBPJSKesehatan($idpengguna),
                        'bpjstk' => tunjanganBPJStk($idpengguna),
                        'pph21' => 0,
                        'potonganlainnya' => 0
                    ];
                }
            }
        }

        // Data tunjangan
        $data = [
            'dt' => $this->md_pengguna->getById($idpengguna),
            'title_pdf' => 'SLIP GAJI KARYAWAN',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month,
            'potonganlain' => potongan_lain($idpengguna),
            'tglcetak' => isset($dataid[1]) ? $dataid[1] : time(),
            'dtgaji' => $dtgaji,
            'dtsalary' => $dtsalary
        ];

        // filename dari pdf ketika didownload
        $file_pdf = 'SLIP GAJI KARYAWAN ' . $data['periode'];

        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_slip_gaji', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }


    public function print_slip_month($param = '', $param2 = '')
    {
        grantAccessFor('all');
        $mpdf = new Mpdf(['format' => 'Legal']);
        $mpdf->AddPage('P');

        $month = date('Y-m', strtotime($param)); //Mengambil tahun saat ini
        $idpengguna = decrypt($param2);
        
        $slip_data = $this->md_salary->getSlipByMonth($month, $idpengguna);
        $dtsalary_obj = null;

        if (is_array($slip_data) && !empty($slip_data)) {
            $dtsalary_obj = $slip_data[0];
        } else if (is_object($slip_data)) {
            $dtsalary_obj = $slip_data;
        }

        if (empty($dtsalary_obj)) {
            $dtsalary_obj = $this->md_salary->getSalaryHistoryPenggunaID($idpengguna);
        }

        if (empty($dtsalary_obj)) {
            $pengguna_data = $this->md_pengguna->getById($idpengguna);
            if (!empty($pengguna_data) && !empty($pengguna_data[0]->id_latestriwayat_salary)) {
                $latest = $this->md_salary->getById($pengguna_data[0]->id_latestriwayat_salary);
                if (!empty($latest)) {
                    $dtsalary_obj = (object)[
                        'gajipokok' => $latest[0]->gaji_pokok ?? 0,
                        'tunjanganjabatan' => $latest[0]->tunjangan_jabatan ?? 0,
                        'tunjangankonsumsi' => 0,
                        'tunjangankinerja' => 0,
                        'tunjangankomunikasi' => 0,
                        'tunjangantransport' => 0,
                        'tunjanganraya' => 0,
                        'bonus' => 0,
                        'pendapatanlain' => 0,
                        'pendapatanlain_tidaktetap' => 0,
                        'tunjanganbbm' => 0,
                        'bpjskesehatan' => tunjanganBPJSKesehatan($idpengguna),
                        'bpjstk' => tunjanganBPJStk($idpengguna),
                        'pph21' => 0,
                        'potonganlainnya' => 0
                    ];
                }
            }
        }

        $dtgaji = $this->md_salary->getSalaryTerakhirTutupBukuByPenggunaID($idpengguna);
        if (empty($dtgaji)) {
            $pengguna_info = $this->md_pengguna->getById($idpengguna);
            if (!empty($pengguna_info)) {
                $dtgaji = (object)[
                    'nama' => $pengguna_info[0]->nama,
                    'jabatan' => $pengguna_info[0]->jabatan ?? '-',
                    'npp' => $pengguna_info[0]->no_pegawai ?? '-',
                    'status' => $pengguna_info[0]->status_karyawan ?? '-'
                ];
            }
        }

        // Data tunjangan
        $data = [
            'dt' => $this->md_pengguna->getById($idpengguna),
            'title_pdf' => 'SLIP GAJI KARYAWAN',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month,
            'potonganlain' => potongan_lain($idpengguna),
            'dtgaji' => $dtgaji,
            'dtsalary' => $dtsalary_obj
        ];

        $file_pdf = 'SLIP GAJI KARYAWAN ' . $data['periode'];
        $html = $this->load->view('pages/v_print/print_slip_gaji', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }


    public function print11($param2 = "")
    {

        //load mpdf dan membuat page size legal
        $mpdf = new Mpdf(['format' => 'Legal']);

        //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
        $mpdf->AddPage('L');
        // Data bulan, jika tidak ada dipilih mengambil hari pertama di bulan sebelum nya
        $month = date("Y-m");
        // Data tunjangan
        //if(sessPenggunaId() == '23' || sessPenggunaId() == '25' || sessPenggunaId() == '6' || sessPenggunaId() == '14' || sessPenggunaId() == '15'){
        $data = [
            'dt' => $this->md_pengguna->getByWherenotIn(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator'], [58, 47, 84, 714, 77, 79, 110, 87, 72, 70, 81, 69, 83, 107, 86, 74, 57, 738, 55, 56, 721, 743, 745, 746, 747, 749]),
            //'dtgaji' => $this->md_salary->getSalaryFULLVYMold((date('m', strtotime($month))),date('Y', strtotime($month))),
            'title_pdf' => 'Rekapitulasi Penghasilan',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month,
            'bulan' => date('m', strtotime($month)),
            'tahun' => date('Y', strtotime($month))
        ];

        // filename dari pdf ketika didownload
        $file_pdf = 'Rekapitulasi Penghasilan Periode ' . $data['periode'];

        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_salary_full', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }

    public function print($param2 = "")
    {

        $this->load->library('pdfgenerator');
        $month = date("Y-m");
        $date = DateTime::createFromFormat("Y-m", $month); // Mengubah string ke objek DateTime
        $date->modify("-1 month"); // Mengurangi 1 bulan
        $month1 = $date->format("Y-m");
        $data = [
            //'dt' => $this->md_pengguna->getKaryawan(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator']),
            'dt' => $this->md_pengguna->getByWherenotIn(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator'], [58, 47, 84, 714, 77, 79, 110, 87, 72, 70, 81, 69, 83, 107, 86, 74, 57, 738, 56, 721, 743, 742, 737, 15, 767, 768]),
            'title_pdf' => 'Rekapitulasi Penghasilan',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month,
            'month1' => $month1,
            'bulan2' => date('m', strtotime($month)),
            'tahun2' => date('Y', strtotime($month))
        ];
        // echo_array($data['dt']);die;
        // filename dari pdf ketika didownload
        $file_pdf = 'Rekapitulasi Penghasilan Periode ' . $data['periode'];
        // setting paper
        $paper = 'legal';
        //orientasi paper potrait / landscape
        $orientation = "landscape";
        $html = $this->load->view('pages/v_print/print_salary_full', $data, true);

        // run dompdf
        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }


    public function print_resign($param2 = "")
    {

        $this->load->library('pdfgenerator');
        $month = date("Y-m");
        $date = DateTime::createFromFormat("Y-m", $month); // Mengubah string ke objek DateTime
        $date->modify("-1 month"); // Mengurangi 1 bulan
        $month1 = $date->format("Y-m");
        $data = [
            'dt' => $this->md_pengguna->getById(15),
            //'dt' => $this->md_pengguna->getByWherenotIn(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator'], [58, 47,84,714,77,79,110,87,72,70,81,69,83,107,86,74,57,738,55,56,721,743]),
            'title_pdf' => 'Rekapitulasi Penghasilan',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month,
            'month1' => $month1,
            'bulan2' => date('m', strtotime($month)),
            'tahun2' => date('Y', strtotime($month))
        ];
        // echo_array($data['dt']);die;
        // filename dari pdf ketika didownload
        $file_pdf = 'Rekapitulasi Penghasilan Periode ' . $data['periode'];
        // setting paper
        $paper = 'legal';
        //orientasi paper potrait / landscape
        $orientation = "landscape";
        $html = $this->load->view('pages/v_print/print_salary_full', $data, true);

        // run dompdf
        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }


    public function print_month($month = '')
    {

        //load mpdf dan membuat page size legal
        $mpdf = new Mpdf(['format' => 'Legal']);

        //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
        $mpdf->AddPage('L');
        // Data bulan, jika tidak ada dipilih mengambil hari pertama di bulan sebelum nya
        //$month = date("Y-m");
        // Data tunjangan
        $data = [
            'dt' => $this->md_pengguna->getByWherenotIn(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator'], [58, 47, 84, 714, 77, 79, 110, 87, 72, 70, 81, 69, 83, 107, 86, 74, 57, 56]),
            //'dtgaji' => $this->md_salary->getSalaryFULLVYM((date('m', strtotime($month))),date('Y', strtotime($month))),
            'dtgaji' => $this->md_salary->getSalaryHistoryMonth((date('m', strtotime($month))), date('Y', strtotime($month))),
            'title_pdf' => 'Rekapitulasi Penghasilan',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month,
            'bulan' => date('m', strtotime($month)),
            'tahun' => date('Y', strtotime($month))
        ];
        // filename dari pdf ketika didownload
        $file_pdf = 'Rekapitulasi Penghasilan Periode ' . $data['periode'];

        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_salary_full_history', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }

    public function print_thr($param2 = "")
    {

        //load mpdf dan membuat page size legal
        $mpdf = new Mpdf(['format' => 'Legal']);

        //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
        $mpdf->AddPage('L');
        // Data bulan, jika tidak ada dipilih mengambil hari pertama di bulan sebelum nya
        $month = date("Y-m");
        // Data tunjangan
        $data = [
            'dt' => $this->md_pengguna->getByWherenotIn(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator'], [58, 47, 84, 714, 77, 79, 110, 87, 72, 70, 81, 69, 83, 107, 86, 74, 57]),
            'dtgaji' => $this->md_salary->getSalaryFULLVYM((date('m', strtotime($month))), date('Y', strtotime($month))),
            'title_pdf' => 'REKAPITULASI THR KARYAWAN ',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month
        ];
        // filename dari pdf ketika didownload
        $file_pdf = 'REKAPITULASI THR KARYAWAN  Periode ' . $data['periode'];

        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_salary_thr', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }


    public function pagination()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
    
        $month_filter = $this->input->post('filter_month'); // Contoh: "2024-12"
        $dt = $this->md_pengguna->getAllPenggunaAktif();
        
        $start = $this->input->post('start');
        $data = array();
        $tglcetakstamp = strtotime(date('Y-m-d H:i:s'));
    
        foreach ($dt['data'] as $row) {
            $id_enc = encrypt($row->pengguna_id);
            
            $salary_data = null;
            // LOGIKA FILTER: Jika ada filter bulan, ambil dari history. Jika tidak ada / data kosong, ambil data riwayat_salary terbaru.
            if (!empty($month_filter)) {
                $salary_data = $this->md_salary->getSlipByMonth($month_filter, $row->pengguna_id);
            }
            
            if (empty($salary_data)) {
                $pengguna = $this->md_pengguna->getById($row->pengguna_id);
                if (!empty($pengguna) && !empty($pengguna[0]->id_latestriwayat_salary)) {
                    $salary_res = $this->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
                    $salary_data = !empty($salary_res) ? $salary_res[0] : null;
                }
                if (empty($salary_data)) {
                    $salary_latest = $this->md_salary->get_latest_salary($row->pengguna_id);
                    $salary_data = !empty($salary_latest) ? (object)$salary_latest : null;
                }
            }
    
            // Siapkan Tombol Slip Gaji dengan parameter bulan
            $url_slip = !empty($month_filter) 
                        ? "salary/print_slip_month/" . $month_filter . "/" . $id_enc 
                        : "salary/print_slip/" . encryptvym($id_enc . "," . $tglcetakstamp);
    
            $li_btn = '
                <div class="d-inline-flex align-items-center" style="gap: 5px; white-space: nowrap;">
                    <button type="button" title="Edit Salary" class="btn btn-sm btn-warning text-white shadow-sm btn-edit" data-id="' . $id_enc . '" style="padding: 4px 8px; border-radius: 6px; font-size: 11px;"><i class="bx bx-pencil"></i></button>
                    <button type="button" title="Edit Komisi" class="btn btn-sm btn-primary shadow-sm btn-komisi" data-id="' . $id_enc . '" style="padding: 4px 8px; border-radius: 6px; font-size: 11px;"><i class="bx bx-dollar-circle"></i></button>
                    <button type="button" title="Tambah Pendapatan Lain" class="btn btn-sm btn-info text-white shadow-sm btn-pen_lain" data-id="' . $id_enc . '" style="padding: 4px 8px; border-radius: 6px; font-size: 11px;"><i class="bx bx-message-alt-add"></i></button> 
                    <a target="_blank" class="btn btn-sm btn-danger text-white shadow-sm" href="' . base_url($url_slip) . '" title="Print Slip Gaji" style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                        <i class="fas fa-file-invoice-dollar"></i> Slip ' . ($month_filter ?: "Terbaru") . '
                    </a>
                </div>';

            $th = array();
            $th[] = ++$start . '.';
            $th[] = '<a href="salary/show/detail_salary/' . $id_enc . '">' . $row->nama . '</a>';
            
            // Tampilkan Gaji Pokok & Tunjangan Jabatan dari data yang sudah difilter
            $gajipokok_val = null;
            $tunjanganjabatan_val = null;
            if (!empty($salary_data)) {
                if (isset($salary_data->gajipokok)) {
                    $gajipokok_val = $salary_data->gajipokok;
                } else if (isset($salary_data->gaji_pokok)) {
                    $gajipokok_val = $salary_data->gaji_pokok;
                }

                if (isset($salary_data->tunjanganjabatan)) {
                    $tunjanganjabatan_val = $salary_data->tunjanganjabatan;
                } else if (isset($salary_data->tunjangan_jabatan)) {
                    $tunjanganjabatan_val = $salary_data->tunjangan_jabatan;
                }
            }
            
            $th[] = ($gajipokok_val !== null && $gajipokok_val !== '') ? rupiah($gajipokok_val) : '-';
            $th[] = rupiah(tunjanganBPJSKesehatan($row->pengguna_id));
            $th[] = rupiah(tunjanganBPJStk($row->pengguna_id));
            $th[] = rupiah(komisi($row->pengguna_id));
            $th[] = rupiah(pendapatan_lain($row->pengguna_id));
            $pph21 = pph21_manual($row->pengguna_id, $month_filter);
            $th[] = $pph21 ? rupiah($pph21) : '-';
            $th[] = $li_btn;
            $data[] = $th;
        }
    
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function pagination_freelance()
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
            $salary = $this->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
            $nama_pengguna = '<a href="salary/show/detail_salary/' . $id . '")>' . $row->nama . '</a>';
            $bpjskes = tunjanganBPJSKesehatan($row->pengguna_id);
            $bpjstk = tunjanganBPJStk($row->pengguna_id);
            $komisi = komisi($row->pengguna_id);
            $pendapatan_lain = pendapatan_lain($row->pengguna_id);
            $myObj = $id . "," . $tglcetakstamp;
            $parJSON = encryptvym($myObj);
            $li_btn = '
                <div class="d-inline-flex align-items-center" style="gap: 5px; white-space: nowrap;">
                    <button type="button" title="Edit Salary" class="btn btn-sm btn-warning text-white shadow-sm btn-edit" data-id="' . $id . '" style="padding: 4px 8px; border-radius: 6px; font-size: 11px;"><i class="bx bx-pencil"></i></button>
                    <button type="button" title="Tambah Pendapatan Lain" class="btn btn-sm btn-info text-white shadow-sm btn-pen_lain" data-id="' . $id . '" style="padding: 4px 8px; border-radius: 6px; font-size: 11px;"><i class="bx bx-message-alt-add"></i></button>
                    <a target="_blank" class="btn btn-sm btn-danger text-white shadow-sm" href="' . base_url('salary/print_slip/' . $parJSON) . '" title="Print Slip Gaji" style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;"><i class="fas fa-file-invoice-dollar"></i> Slip Gaji</a>
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


    public function pagination_history()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $dt = $this->md_pengguna->getAllPenggunaReal();
        $start = $this->input->post('start');
        $data = array();
        $bulan = date("Y-m");
        $tglcetak = date('Y-m-d H:i:s');
        $tglcetakstamp = strtotime($tglcetak);

        foreach ($dt['data'] as $row) {
            $id = encrypt($row->pengguna_id);
            $pengguna = $this->md_pengguna->getById($row->pengguna_id);
            $salary = $this->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
            $nama_pengguna = '<a href="salary/show/detail_salary/' . $id . '")>' . $row->nama . '</a>';
            $bpjskes = tunjanganBPJSKesehatan($row->pengguna_id);
            $bpjstk = tunjanganBPJStk($row->pengguna_id);
            $komisi = komisi($row->pengguna_id);
            $pendapatan_lain = pendapatan_lain($row->pengguna_id);
            $myObj = $id . "," . $tglcetakstamp;
            $parJSON = encryptvym($myObj);
            $li_btn = '
                <div class="d-inline-flex align-items-center" style="gap: 5px; white-space: nowrap;">
                    <button type="button" title="Tambah Pendapatan Lain" class="btn btn-sm btn-info text-white shadow-sm btn-pen_lain" data-id="' . $id . '" style="padding: 4px 8px; border-radius: 6px; font-size: 11px;"><i class="bx bx-message-alt-add"></i></button>
                    <a target="_blank" class="btn btn-sm btn-danger text-white shadow-sm" href="' . base_url('salary/print_slip/' . $parJSON) . '" title="Print Slip Gaji" style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;"><i class="fas fa-file-invoice-dollar"></i> Slip Gaji</a>
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

    public function pagination_thr()  //untuk tampilan datatable di menu THR
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
            $salary = $this->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
            $nama_pengguna = '<a href="salary/show/detail_salary/' . $id . '")>' . $row->nama . '</a>';
            $bpjskes = tunjanganBPJSKesehatan($row->pengguna_id);
            $bpjstk = tunjanganBPJStk($row->pengguna_id);
            $komisi = komisi($row->pengguna_id);
            $pendapatan_lain = pendapatan_lain($row->pengguna_id);
            $myObj = $id . "," . $tglcetakstamp;
            $parJSON = encryptvym($myObj);

            $th = array();
            $th[] = ++$start . '.';
            $th[] = $nama_pengguna;
            $th[] = isset($salary[0]->gaji_pokok) ? rupiah($salary[0]->gaji_pokok) : '';
            $th[] = isset($salary[0]->tunjangan_jabatan) ? rupiah($salary[0]->tunjangan_jabatan) : '';
            $th[] = isset($salary[0]->gaji_pokok) ? rupiah($salary[0]->gaji_pokok) : '';
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function paginationtutupbuku()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $dt = $this->md_salary->getRiwayatTutupBuku();
        $start = $this->input->post('start');
        $data = array();
        $bulan = date("Y-m");
        $tglcetak = date('Y-m-d H:i:s');
        $tglcetakstamp = strtotime($tglcetak);
        foreach ($dt['data'] as $row) {
            $li_btn = '
                <div class="btn-group" role="group" aria-label="First group">
                     <button type="button" id="btn-kirim" class="btn btn-sm btn-success btn-edit"  data-id="' . encrypt($row->idpengguna) . '" data-object="salary/kirimnotifikasi/' . encrypt($row->idpengguna) . '"><i class="bx bx-message-alt-add">Kirim WA</i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama;
            $th[] = $row->jabatan;
            $th[] = $row->status;
            $th[] = ($row->gajipokok > 0) ? rupiah($row->gajipokok) : '';
            $th[] = ($row->pphpasal21 > 0) ? rupiah($row->pphpasal21) : '';
            $th[] = $row->nowhatsapp;
            $th[] = $row->norek;
            $th[] =  $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function test()
    {

        $pengguna = $this->md_pengguna->getById(56);
        $salary = $this->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
        $pl = $this->md_pendapatan_lain->getById($pengguna[0]->id_pendapatan_lain);
        echo 'nama Meilina Safitri';
        echo '<br>';
        echo 'Gaji Pokok =' . $salary[0]->gaji_pokok;
        echo '<br>';
        echo 'Tunjangan Jabatan =' . $salary[0]->tunjangan_jabatan;
        echo '<br>';
        echo 'Tunjangan Kinerja =' . tunjangan(56, '2022-05')['total_kinerja'];
        echo '<br>';
        echo 'Tunjangan Konsumsi =' . tunjangan(56, '2022-05')['total_konsumsi'];
        echo '<br>';
        echo 'Tunjangan BPJS Kes =' . tunjanganBPJSKesehatan(56);
        echo '<br>';
        echo 'Tunjangan BPJS Kes =' . potonganBPJSkes(56)['bpjskes'];
        echo '<br>';
        echo 'Tunjangan BPJS TK =' . tunjanganBPJStk(56);
        echo '<br>';

        echo 'Pendapatan Lainnya =' . pendapatan_lain(56);
        echo '<br>';
        echo 'Bruto =' . penghasilan_bruto(56);
        echo '<br>';
        echo 'Netto =' . penghasilan_netto(56);
        echo '<br>';
        echo 'Dasar =' . dasar_perhitungan_pajak_pribadi(56);
        echo '<br>';
        echo 'penghasilan Kena Pajak =' . penghasilan_kena_pajak(56);
        echo '<br>';
        echo 'pph21 =' . dasar_tarif(56);
        echo '<br>';
        echo 'MK =' . masaKerjaBulan($pengguna[0]->tgl_masuk);
        echo '<br>';





        //    print_r(masaKerjaBulan('2021-03-08'));

        // echo 'nama Meilina Safitri';
        // echo '<br>';
        // echo 'Gaji Pokok ='.  $salary[0]->gaji_pokok;
        // echo '<br>';
        // echo 'Tunjangan BPJS Kes ='. tunjanganBPJSKesehatan(49);
        // echo '<br>';

        // echo 'Tunjangan BPJS Tk ='. tunjanganBPJStk(49);

        //    echo_array(tunjanganBPJStk(54));
        //    echo_array(tunjanganBPJSKesehatan(54));
        //    echo_array(dasar_tarif(54));
        //    echo_array(tunjangan(49,'2022-04'));
        //    echo_array(penghasilan_netto(54));
        //    echo_array(dasar_tarif(54));
    }
    
      public function download_template_pph21()
  {
      grantAccessFor(['Administrator', 'Hrd', 'Ga']);

      $master_file = FCPATH . 'uploads/template/Template_Import_PPh21.xlsx';
      if (file_exists($master_file)) {
          $this->load->helper('download');
          force_download('Template_Import_PPh21.xlsx', file_get_contents($master_file));
          return;
      }

      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('Template PPh 21');

      // Header
      $sheet->setCellValue('A1', 'ID Pengguna');
      $sheet->setCellValue('B1', 'Jumlah PPh 21');
      $sheet->setCellValue('C1', 'Nama Karyawan (Referensi)');

      $sheet->getStyle('A1:C1')->getFont()->setBold(true);
      $sheet->getStyle('A1:C1')->getFill()
          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
          ->getStartColor()->setARGB('FFD9E1F2');

      // Ambil data seluruh pengguna aktif
      $where = ['p.status' => 1, 'p.is_active' => 1, 'p.pengguna_id !=' => 1];
      $pengguna_list = $this->db->select('p.pengguna_id, p.nama, p.id_pph21')
          ->from('pengguna p')
          ->where($where)
          ->order_by('p.nama', 'ASC')
          ->get()
          ->result();

      $rowNum = 2;
      foreach ($pengguna_list as $user) {
          $pph_val = pph21_manual($user->pengguna_id);
          $sheet->setCellValue('A' . $rowNum, $user->pengguna_id);
          $sheet->setCellValue('B' . $rowNum, $pph_val ? (float)$pph_val : 0);
          $sheet->setCellValue('C' . $rowNum, $user->nama);
          $rowNum++;
      }

      foreach (range('A', 'C') as $col) {
          $sheet->getColumnDimension($col)->setAutoSize(true);
      }

      $filename = 'Template_Import_PPh21_' . date('Y_m_d') . '.xlsx';

      if (ob_get_length()) ob_end_clean();
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="' . $filename . '"');
      header('Cache-Control: max-age=0');

      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      $writer->save('php://output');
      exit;
  }

  public function import_pph21()
  {
      grantAccessFor(['Administrator', 'Hrd', 'Ga']);

      if (empty($_FILES['file_excel']['name'])) {
          echo json_encode(['status' => 'error', 'message' => 'Silahkan pilih file Excel terlebih dahulu!']);
          return;
      }

      $file_tmp = $_FILES['file_excel']['tmp_name'];
      $ext = pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION);

      if (!in_array(strtolower($ext), ['xlsx', 'xls', 'csv'])) {
          echo json_encode(['status' => 'error', 'message' => 'Format file harus berupa Excel (.xlsx / .xls / .csv)!']);
          return;
      }

      try {
          $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file_tmp);
          $reader->setReadDataOnly(true);
          $spreadsheet = $reader->load($file_tmp);
          $sheet = $spreadsheet->getActiveSheet();
          $rows = $sheet->toArray();

          if (count($rows) < 2) {
              echo json_encode(['status' => 'error', 'message' => 'File Excel kosong atau tidak memiliki baris data!']);
              return;
          }

          $success_count = 0;
          for ($i = 1; $i < count($rows); $i++) {
              $row = $rows[$i];
              $pengguna_id = isset($row[0]) ? trim($row[0]) : '';
              $raw_jumlah = isset($row[1]) ? trim($row[1]) : '0';
              $jumlah_pph = (float)str_replace(['.', ',', ' '], '', $raw_jumlah);

              if (!empty($pengguna_id) && is_numeric($pengguna_id)) {
                  $data_pph = [
                      'jumlah' => $jumlah_pph,
                      'data_created' => date('Y-m-d H:i:s')
                  ];
                  $this->db->insert('pph21', $data_pph);
                  $id_pph21 = $this->db->insert_id();

                  $this->db->where('pengguna_id', $pengguna_id)->update('pengguna', ['id_pph21' => $id_pph21]);
                  $success_count++;
              }
          }

          addLog('Import PPh 21', 'Berhasil mengimpor data PPh 21 sebanyak ' . $success_count . ' karyawan');

          echo json_encode([
              'status' => 'success',
              'message' => 'Berhasil mengimpor data PPh 21 untuk ' . $success_count . ' karyawan.'
          ]);
      } catch (\Exception $e) {
          echo json_encode(['status' => 'error', 'message' => 'Gagal membaca file Excel: ' . $e->getMessage()]);
      }
  }

  public function download_template_komisi()
  {
      grantAccessFor(['Administrator', 'Hrd', 'Ga']);

      $master_file = FCPATH . 'uploads/template/Template_Import_Komisi.xlsx';
      if (file_exists($master_file)) {
          $this->load->helper('download');
          force_download('Template_Import_Komisi.xlsx', file_get_contents($master_file));
          return;
      }

      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('Template Komisi');

      $sheet->setCellValue('A1', 'ID Pengguna');
      $sheet->setCellValue('B1', 'Jumlah Komisi');
      $sheet->setCellValue('C1', 'Bulan (YYYY-MM)');
      $sheet->setCellValue('D1', 'Nama Karyawan (Referensi)');

      $sheet->getStyle('A1:D1')->getFont()->setBold(true);
      $sheet->getStyle('A1:D1')->getFill()
          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
          ->getStartColor()->setARGB('FFD9E1F2');

      $where = ['p.status' => 1, 'p.is_active' => 1, 'p.pengguna_id !=' => 1];
      $pengguna_list = $this->db->select('p.pengguna_id, p.nama, p.id_komisi')
          ->from('pengguna p')
          ->where($where)
          ->order_by('p.nama', 'ASC')
          ->get()
          ->result();

      $rowNum = 2;
      $currentMonth = date('Y-m');
      foreach ($pengguna_list as $user) {
          $komisi_val = komisi($user->pengguna_id);
          $sheet->setCellValue('A' . $rowNum, $user->pengguna_id);
          $sheet->setCellValue('B' . $rowNum, $komisi_val ? (float)$komisi_val : 0);
          $sheet->setCellValue('C' . $rowNum, $currentMonth);
          $sheet->setCellValue('D' . $rowNum, $user->nama);
          $rowNum++;
      }

      foreach (range('A', 'D') as $col) {
          $sheet->getColumnDimension($col)->setAutoSize(true);
      }

      $filename = 'Template_Import_Komisi_' . date('Y_m_d') . '.xlsx';

      if (ob_get_length()) ob_end_clean();
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="' . $filename . '"');
      header('Cache-Control: max-age=0');

      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      $writer->save('php://output');
      exit;
  }

  public function import_komisi()
  {
      grantAccessFor(['Administrator', 'Hrd', 'Ga']);

      if (empty($_FILES['file_excel']['name'])) {
          echo json_encode(['status' => 'error', 'message' => 'Silahkan pilih file Excel terlebih dahulu!']);
          return;
      }

      $file_tmp = $_FILES['file_excel']['tmp_name'];
      $ext = pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION);

      if (!in_array(strtolower($ext), ['xlsx', 'xls', 'csv'])) {
          echo json_encode(['status' => 'error', 'message' => 'Format file harus berupa Excel (.xlsx / .xls / .csv)!']);
          return;
      }

      try {
          $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file_tmp);
          $reader->setReadDataOnly(true);
          $spreadsheet = $reader->load($file_tmp);
          $sheet = $spreadsheet->getActiveSheet();
          $rows = $sheet->toArray();

          if (count($rows) < 2) {
              echo json_encode(['status' => 'error', 'message' => 'File Excel kosong atau tidak memiliki baris data!']);
              return;
          }

          $success_count = 0;
          for ($i = 1; $i < count($rows); $i++) {
              $row = $rows[$i];
              $pengguna_id = isset($row[0]) ? trim($row[0]) : '';
              $raw_jumlah = isset($row[1]) ? trim($row[1]) : '0';
              $jumlah_komisi = (float)str_replace(['.', ',', ' '], '', $raw_jumlah);
              $bulan = isset($row[2]) && !empty(trim($row[2])) ? trim($row[2]) . '-01' : date('Y-m-01');

              if (!empty($pengguna_id) && is_numeric($pengguna_id)) {
                  $data_komisi = [
                      'jumlah' => $jumlah_komisi,
                      'bulan' => $bulan,
                      'data_created' => date('Y-m-d H:i:s'),
                      'perusahaan' => 0
                  ];
                  $this->db->insert('komisi', $data_komisi);
                  $id_komisi = $this->db->insert_id();

                  $this->db->where('pengguna_id', $pengguna_id)->update('pengguna', ['id_komisi' => $id_komisi]);
                  $success_count++;
              }
          }

          addLog('Import Komisi', 'Berhasil mengimpor data komisi sebanyak ' . $success_count . ' karyawan');

          echo json_encode([
              'status' => 'success',
              'message' => 'Berhasil mengimpor data Komisi untuk ' . $success_count . ' karyawan.'
          ]);
      } catch (\Exception $e) {
          echo json_encode(['status' => 'error', 'message' => 'Gagal membaca file Excel: ' . $e->getMessage()]);
      }
  }
}
