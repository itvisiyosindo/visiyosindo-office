<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;

defined('BASEPATH') or exit('No direct script access allowed');

class Salary_tidak_tetap extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_salary_tidak_tetap');
        $this->load->model('md_salary');
        $this->load->model('md_divisi_pengguna');
        $this->load->model('md_absensi');
    }

    function id_navbar()
    {
        $id_navbar = "kepegawaian";
        return $id_navbar;
    }

    public function index()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'salary/v_salary_tidak_tetap';
        $page_data['page_title'] = 'Salary Tidak Tetap';
        $page_data['page_desc'] = 'Management Salary Tidak Tetap Karyawan';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $pengguna_id = decrypt($this->input->post('pengguna_id'));
        $pengguna = $this->md_pengguna->getById($pengguna_id);

        //update status approval di absensi
        $data2['approval'] = $this->input->post('approval');
        $where = ['id_absensi' => decrypt($this->input->post('id_absensi'))];
        $this->md_absensi->updateByWhere($data2, $where);

        addLog('Absensi Approval', $data2['approval'] . ' Absensi milik ' . $pengguna[0]->nama);
        ajaxReturnDie('success', 'Data Berhasil di simpan!', 'reload_table');
    }


    public function show($param = "", $param2 = "")
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        if ($param == 'detail') {
            $page_data['switch'] = $this->id_navbar();
            $page_data['pengguna'] = $this->md_pengguna->getById(decrypt($param2));
            $page_data['page_name'] = 'salary/v_detail_salary_tt';
            $page_data['page_title'] = 'Detail Salary Tidak Tetap';
            $page_data['page_desc'] = '';
            $this->load->view('index', $page_data);
        } else if ($param == 'print') {

            $this->load->library('pdfgenerator');
            $month = $param2 ? $param2 : date("Y-m", strtotime("first day of last month"));
            $data = [
                //'dt' => $this->md_pengguna->getKaryawan(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator']),
                'dt' => $this->md_pengguna->getByWherenotIn(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator'], [58, 47, 84, 714, 77, 79, 110, 87, 72, 70, 81, 69, 83, 107, 86, 74, 57, 738, 55, 56, 721, 743, 750, 746, 29]),
                'title_pdf' => 'Rekapitulasi Tunjangan Tidak Tetap',
                'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
                'month' => $month
            ];
            // echo_array($data['dt']);die;
            // filename dari pdf ketika didownload
            $file_pdf = 'Rekapitulasi Tunjangan Tidak Tetap ' . $data['periode'];
            // setting paper
            $paper = 'legal';
            //orientasi paper potrait / landscape
            $orientation = "landscape";
            $html = $this->load->view('pages/v_print/print_salary_tidak_tetap', $data, true);

            // run dompdf
            $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
        } else if ($param == 'print2') {

            $this->load->library('pdfgenerator');
            $month = $param2 ? $param2 : date("Y-m", strtotime("first day of last month"));
            $data = [
                //'dt' => $this->md_pengguna->getKaryawan(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator']),
                'dt' => $this->md_pengguna->getBywhereSkor(),
                'title_pdf' => 'Tunjangan Karyawan Resign',
                'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
                'month' => $month
            ];
            // echo_array($data['dt']);die;
            // filename dari pdf ketika didownload
            $file_pdf = 'Tunjangan Karyawan Resign ' . $data['periode'];
            // setting paper
            $paper = 'legal';
            //orientasi paper potrait / landscape
            $orientation = "landscape";
            $html = $this->load->view('pages/v_print/print_salary_tidak_tetap', $data, true);

            // run dompdf
            $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
        }
    }


    public function pagination($param = "", $param2 = "")
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        if ($param == "detail_salary_tidak_tetap") {
            $pengguna_id = decrypt($this->input->post('pengguna_id'));

            $id_divisi = $this->md_pengguna->getById($pengguna_id);
            $dataPengguna = $this->md_divisi_pengguna->getByIdPengguna($id_divisi[0]->id_divisi);

            $dt = $this->md_salary_tidak_tetap->getForDetailSalaryTt($pengguna_id);
            $start = $this->input->post('start');
            $data = array();
            foreach ($dt['data'] as $row) {

                $pengguna = $this->md_pengguna->getById($row->pengguna_id);
                $salary = $this->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
                $tgl = date_view_format($row->data_created);
                
                $tunjangan_kinerja = 0;
                $tunjangan_konsumsi = 0;
                if ($row->approval == 'terima') {
                    $tunjangan_kinerja = isset($salary[0]->tunjangan_kinerja) ? $salary[0]->tunjangan_kinerja : 0;
                    $tunjangan_konsumsi = isset($salary[0]->tunjangan_konsumsi) ? $salary[0]->tunjangan_konsumsi : 0;
                }

                $th = array();
                $th[] = ++$start . '.';
                $th[] = indo_date($row->data_created);
                $th[] = $tunjangan_kinerja ? 'Rp. ' . rupiah($tunjangan_kinerja) : 'Rp.-';
                $th[] = $tunjangan_konsumsi ? 'Rp. ' . rupiah($tunjangan_konsumsi) : 'Rp.-';
                $th[] = $row->approval == 'terima' ? 'Tunjangan Disetujui' : 'Tunjangan Tidak Setujui';
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else {

            $excluded_ids = [29]; // ID karyawan yang dikecualikan
            $month = $this->input->post('filter_month');
            $monthfield = $month ? $month : date("Y-m");
            
            // 1 query untuk ambil seluruh attendance data bulan terkait
            $attendance_map = $this->md_absensi->getMonthlyAttendanceSummary($monthfield);
            // 1 query untuk ambil seluruh override
            $overrides = $this->md_salary_tidak_tetap->getOverridesByMonth($monthfield);
            // 1 query dengan datatables server-side pagination & join riwayat_salary
            $dt = $this->md_salary_tidak_tetap->getPenggunaWithSalaryDatatables($excluded_ids);
            
            $start = $this->input->post('start');
            $data = array();

            foreach ($dt['data'] as $row) {
                $id = encrypt($row->pengguna_id);
                $nama_pengguna = '<a href="salary_tidak_tetap/show/detail/' . $id . '">' . $row->nama . '</a>';
                $ov_check = isset($overrides[$row->pengguna_id]) ? $overrides[$row->pengguna_id] : null;
                if ($ov_check) {
                    $nama_pengguna .= ' <span class="badge badge-warning text-dark ml-1" style="font-size:10px;" title="' . htmlspecialchars($ov_check->keterangan ?? 'Manual Override', ENT_QUOTES) . '">Manual</span>';
                }

                $btn_action = '<div class="d-inline-flex align-items-center" style="gap: 5px; white-space: nowrap;">
                    <button type="button" class="btn btn-sm btn-warning text-white shadow-sm btn-edit-override" data-id="' . $row->pengguna_id . '" data-nama="' . htmlspecialchars($row->nama, ENT_QUOTES) . '" title="Edit Manual Hitungan Tunjangan" style="padding: 4px 8px; border-radius: 6px; font-size: 11px;"><i class="fa fa-edit"></i> Edit</button>
                    <a target="_blank" class="btn btn-sm btn-danger text-white shadow-sm" href="' . base_url("salary/print_slip_month/" . $monthfield . "/" . $id) . '" title="Print Slip Gaji" style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;"><i class="fas fa-file-invoice-dollar"></i> Slip Gaji</a>
                </div>';

                // Kasus khusus Juli 2026 untuk novemby / afyl
                if (($row->pengguna_id == 771 || $row->pengguna_id == 766 || strpos(strtolower($row->nama), 'novemby') !== false || strpos(strtolower($row->nama), 'afylmardopila') !== false) && strpos($monthfield, '2026-07') !== false) {
                    $is_afyl = ($row->pengguna_id == 766 || strpos(strtolower($row->nama), 'afylmardopila') !== false);
                    $days = $is_afyl ? 7 : 9;
                    $kinerja_val = $days * 30000;
                    $konsumsi_val = $days * 18000;
                    $total_val = $kinerja_val + $konsumsi_val;

                    if ($ov_check) {
                        if ($ov_check->hari_kerja !== null && $ov_check->hari_kerja !== '') $days = (int)$ov_check->hari_kerja;
                        if ($ov_check->tunjangan_kinerja !== null && $ov_check->tunjangan_kinerja !== '') $kinerja_val = (float)$ov_check->tunjangan_kinerja;
                        if ($ov_check->tunjangan_konsumsi !== null && $ov_check->tunjangan_konsumsi !== '') $konsumsi_val = (float)$ov_check->tunjangan_konsumsi;
                        $total_val = $kinerja_val + $konsumsi_val;
                    }

                    $th = array();
                    $th[] = ++$start . '.';
                    $th[] = $nama_pengguna;
                    $th[] = $days . ' hari';
                    $th[] = $row->level;
                    $th[] = $row->status_karyawan;
                    $th[] = '<i class="fa fa-times"></i>'; // Tunjangan Jabatan
                    $th[] = 'Rp. ' . rupiah($kinerja_val); // Tunjangan Kinerja
                    $th[] = 'Rp. ' . rupiah($konsumsi_val); // Tunjangan Konsumsi
                    $th[] = '<i class="fa fa-times"></i>'; // Tunjangan Komunikasi
                    $th[] = '<i class="fa fa-times"></i>'; // Tunjangan Transportasi
                    $th[] = '<i class="fa fa-times"></i>'; // Tunjangan BBM
                    $th[] = '<i class="fa fa-times"></i>'; // Tunjangan Lainnya
                    $th[] = 'Rp. -'; // Potongan
                    $th[] = 'Rp. ' . rupiah($total_val); // Total Tunjangan
                    $th[] = $btn_action;
                    $data[] = $th;
                    continue;
                }

                // Ambil data kehadiran dari batch map
                $att = isset($attendance_map[$row->pengguna_id]) ? $attendance_map[$row->pengguna_id] : ['absen_approved' => 0, 'dinas_approved' => 0, 'kantor_approved' => 0, 'hari_biasa' => 0, 'hari_libur' => 0];
                $absen_approved = $att['absen_approved'];
                $dinas_approved = $att['dinas_approved'];
                $kantor_approved = $att['kantor_approved'];

                // Rate tunjangan dari joined table riwayat_salary
                $rate_jabatan = isset($row->tunjangan_jabatan) ? (float)$row->tunjangan_jabatan : 0;
                $rate_kinerja = isset($row->tunjangan_kinerja) ? (float)$row->tunjangan_kinerja : 0;
                $rate_konsumsi = isset($row->tunjangan_konsumsi) ? (float)$row->tunjangan_konsumsi : 0;
                $rate_komunikasi = isset($row->tunjangan_komunikasi) ? (float)$row->tunjangan_komunikasi : 0;
                $rate_transportasi = isset($row->tunjangan_transportasi) ? (float)$row->tunjangan_transportasi : 0;
                $rate_bbm = isset($row->tunjangan_bbm) ? (float)$row->tunjangan_bbm : 0;
                $rate_potongan = isset($row->potongan) ? (float)$row->potongan : 0;
                $rate_pendapatanlain = (($row->id_pendapatan_lain ?? 0) > 0 && isset($row->pendapatan_lain)) ? (float)$row->pendapatan_lain : 0;

                // Penyesuaian khusus Pak Boddy (ID 94)
                if ($row->pengguna_id == 94) {
                    $dinas_approved = $att['hari_biasa'] + ($att['hari_libur'] * 3);
                    $kantor_approved = $dinas_approved;
                    if (strpos($monthfield, '2026-07') !== false) {
                        $dinas_approved = 53;
                        $kantor_approved = 53;
                    }
                    if (strpos($monthfield, '2026-09') !== false) {
                        $dinas_approved = 46;
                        $kantor_approved = 46;
                    }
                }
                if ($row->pengguna_id == 14 && strpos($monthfield, '2026-07') !== false) {
                    $dinas_approved = 19;
                    $kantor_approved = 19;
                }
                if ($row->pengguna_id == 25 && strpos($monthfield, '2026-07') !== false) {
                    $dinas_approved = 21;
                    $kantor_approved = 21;
                }
                if ($row->pengguna_id == 102 && strpos($monthfield, '2026-07') !== false) {
                    $dinas_approved = 19;
                    $kantor_approved = 19;
                }
                if (($row->pengguna_id == 771 || strpos(strtolower($row->nama), 'novemby') !== false) && strpos($monthfield, '2026-07') !== false) {
                    $dinas_approved = 9;
                    $kantor_approved = 9;
                }

                $s_karyawan = $row->status_karyawan;
                $is_training = (strtolower(trim($row->status_karyawan ?? '')) == 'training');
                $is_magang = (strtolower(trim($row->status_karyawan ?? '')) == 'magang' || $row->pengguna_id == 758);
                if ($is_magang) {
                    $kantor_approved = 0;
                    $dinas_approved = 0;
                }

                // Perhitungan otomatis awal
                $a = ($row->terima_tunjangan_jabatan == 1) ? $rate_jabatan : 0;
                $hitung_kinerja = ($row->terima_tunjangan_kinerja == 1) ? ($rate_kinerja * $dinas_approved) : 0;
                if ($row->pengguna_id == 94) {
                    $hitung_kinerja = ($row->terima_tunjangan_kinerja == 1) ? ($rate_kinerja * $att['hari_libur'] * 3) : 0;
                    if (strpos($monthfield, '2026-07') !== false) {
                        $hitung_kinerja = ($row->terima_tunjangan_kinerja == 1) ? ($rate_kinerja * 33) : 0;
                    }
                }

                $hitung_konsumsi = ($row->terima_tunjangan_konsumsi == 1) ? ($rate_konsumsi * $dinas_approved) : 0;
                if ($row->pengguna_id == 54) { // pak bob
                    $hitung_konsumsi = ($row->terima_tunjangan_konsumsi == 1) ? 5000000 : 0;
                }

                $d = ($row->terima_tunjangan_komunikasi == 1) ? $rate_komunikasi : 0;
                $e = ($row->terima_tunjangan_transportasi == 1) ? ($row->pengguna_id == 724 ? $rate_transportasi * $dinas_approved : $rate_transportasi) : 0;
                $hitung_bbm = ($row->terima_tunjangan_bbm == 1) ? ($rate_bbm * $dinas_approved) : 0;
                $pott = $rate_potongan;
                $s_pendapatanlain = $rate_pendapatanlain;

                // Total Tunjangan
                if ($is_training) {
                    if ($row->pengguna_id == 771 && strpos($monthfield, '2026-07') !== false) {
                        $f = 162000;
                    } else {
                        $f = 0;
                    }
                } else if ($is_magang) {
                    $f = 0;
                } else {
                    $f = ($a + $hitung_kinerja + $hitung_konsumsi + $d + $e + $hitung_bbm - $pott) + $s_pendapatanlain;
                }

                if (strpos(strtolower($row->nama), 'novemby') !== false && strpos($monthfield, '2026-07') !== false) {
                    $dinas_approved = 9;
                    $f = 162000;
                }

                // Terapkan Override Manual jika ada
                if ($ov_check) {
                    if ($ov_check->hari_kerja !== null && $ov_check->hari_kerja !== '') {
                        $dinas_approved = (int)$ov_check->hari_kerja;
                        $kantor_approved = $dinas_approved;
                        if ($row->terima_tunjangan_kinerja == 1 && ($ov_check->tunjangan_kinerja === null || $ov_check->tunjangan_kinerja === '')) {
                            $hitung_kinerja = $rate_kinerja * $dinas_approved;
                        }
                        if ($row->terima_tunjangan_konsumsi == 1 && ($ov_check->tunjangan_konsumsi === null || $ov_check->tunjangan_konsumsi === '')) {
                            $hitung_konsumsi = $rate_konsumsi * $dinas_approved;
                        }
                        if ($row->terima_tunjangan_bbm == 1 && ($ov_check->tunjangan_bbm === null || $ov_check->tunjangan_bbm === '')) {
                            $hitung_bbm = $rate_bbm * $dinas_approved;
                        }
                    }
                    if ($ov_check->tunjangan_jabatan !== null && $ov_check->tunjangan_jabatan !== '') {
                        $a = (float)$ov_check->tunjangan_jabatan;
                    }
                    if ($ov_check->tunjangan_kinerja !== null && $ov_check->tunjangan_kinerja !== '') {
                        $hitung_kinerja = (float)$ov_check->tunjangan_kinerja;
                    }
                    if ($ov_check->tunjangan_konsumsi !== null && $ov_check->tunjangan_konsumsi !== '') {
                        $hitung_konsumsi = (float)$ov_check->tunjangan_konsumsi;
                    }
                    if ($ov_check->tunjangan_komunikasi !== null && $ov_check->tunjangan_komunikasi !== '') {
                        $d = (float)$ov_check->tunjangan_komunikasi;
                    }
                    if ($ov_check->tunjangan_transportasi !== null && $ov_check->tunjangan_transportasi !== '') {
                        $e = (float)$ov_check->tunjangan_transportasi;
                    }
                    if ($ov_check->tunjangan_bbm !== null && $ov_check->tunjangan_bbm !== '') {
                        $hitung_bbm = (float)$ov_check->tunjangan_bbm;
                    }
                    if ($ov_check->tunjangan_lainnya !== null && $ov_check->tunjangan_lainnya !== '') {
                        $s_pendapatanlain = (float)$ov_check->tunjangan_lainnya;
                    }
                    if ($ov_check->potongan !== null && $ov_check->potongan !== '') {
                        $pott = (float)$ov_check->potongan;
                    }

                    $f = ($a + $hitung_kinerja + $hitung_konsumsi + $d + $e + $hitung_bbm - $pott) + $s_pendapatanlain;
                }

                $th = array();
                $th[] = ++$start . '.';
                $th[] = $nama_pengguna;
                $th[] = ($kantor_approved) . ' hari';
                $th[] = $row->level;
                $th[] = $row->status_karyawan;

                // Training & Magang Display
                if ($is_training) {
                    if (($row->pengguna_id == 771 || strpos(strtolower($row->nama), 'novemby') !== false) && strpos($monthfield, '2026-07') !== false) {
                        $th[] = '<i class="fa fa-times"></i>';
                        $th[] = '<i class="fa fa-times"></i>';
                        $th[] = 'Rp. ' . rupiah(18000);
                        $th[] = '<i class="fa fa-times"></i>';
                        $th[] = '<i class="fa fa-times"></i>';
                        $th[] = '<i class="fa fa-times"></i>';
                        $th[] = '<i class="fa fa-times"></i>';
                    } else {
                        $th[] = 'Training';
                        $th[] = 'Training';
                        $th[] = 'Training';
                        $th[] = 'Training';
                        $th[] = 'Training';
                        $th[] = 'Training';
                        $th[] = 'Training';
                    }
                } else if ($is_magang) {
                    $th[] = 'Magang';
                    $th[] = 'Magang';
                    $th[] = 'Magang';
                    $th[] = 'Magang';
                    $th[] = 'Magang';
                    $th[] = 'Magang';
                    $th[] = 'Magang';
                } else {
                    $th[] = ($row->terima_tunjangan_jabatan == 1 || ($ov_check && $ov_check->tunjangan_jabatan !== null)) ? ($a ? 'Rp. ' . rupiah($a) : 'Rp. -') : '<i class="fa fa-times"></i>';
                    $th[] = ($row->terima_tunjangan_kinerja == 1 || ($ov_check && $ov_check->tunjangan_kinerja !== null)) ? ($hitung_kinerja ? 'Rp. ' . rupiah($hitung_kinerja) : 'Rp. -') : '<i class="fa fa-times"></i>';
                    $th[] = ($row->terima_tunjangan_konsumsi == 1 || ($ov_check && $ov_check->tunjangan_konsumsi !== null)) ? ($hitung_konsumsi ? 'Rp. ' . rupiah($hitung_konsumsi) : 'Rp. -') : '<i class="fa fa-times"></i>';
                    $th[] = ($row->terima_tunjangan_komunikasi == 1 || ($ov_check && $ov_check->tunjangan_komunikasi !== null)) ? ($d ? 'Rp. ' . rupiah($d) : 'Rp. -') : '<i class="fa fa-times"></i>';
                    $th[] = ($row->terima_tunjangan_transportasi == 1 || ($ov_check && $ov_check->tunjangan_transportasi !== null)) ? ($e ? 'Rp. ' . rupiah($e) : 'Rp. -') : '<i class="fa fa-times"></i>';
                    $th[] = ($row->terima_tunjangan_bbm == 1 || ($ov_check && $ov_check->tunjangan_bbm !== null)) ? ($hitung_bbm ? 'Rp. ' . rupiah($hitung_bbm) : 'Rp. -') : '<i class="fa fa-times"></i>';
                    $th[] = (($row->id_pendapatan_lain > 0 && $s_pendapatanlain > 0) || ($ov_check && $ov_check->tunjangan_lainnya !== null)) ? ($s_pendapatanlain ? 'Rp. ' . rupiah($s_pendapatanlain) : 'Rp. -') : '<i class="fa fa-times"></i>';
                }

                $th[] = $pott ? 'Rp. ' . rupiah($pott) : 'Rp. -';
                $th[] = 'Rp. ' . ($f ? rupiah($f) : '0');
                $th[] = $btn_action;
                $data[] = $th;
            }

            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        }
    }

    public function getTotals()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $month = $this->input->post('filter_month') ? $this->input->post('filter_month') : date("Y-m");
        $excluded_ids = [29]; // ID karyawan yang dikecualikan
        
        // Batch query efisien: 1 query attendance + 1 query overrides + 1 query pengguna with salary
        $attendance_map = $this->md_absensi->getMonthlyAttendanceSummary($month);
        $overrides = $this->md_salary_tidak_tetap->getOverridesByMonth($month);
        $dt = $this->md_salary_tidak_tetap->getAllPenggunaWithSalaryList($excluded_ids);

        $totals = [
            'total_jabatan' => 0,
            'total_kinerja' => 0,
            'total_konsumsi' => 0,
            'total_komunikasi' => 0,
            'total_transportasi' => 0,
            'total_bbm' => 0,
            'total_lainnya' => 0,
            'total_potongan' => 0,
            'total_tunjangan' => 0,
        ];

        foreach ($dt as $row) {
            $att = isset($attendance_map[$row->pengguna_id]) ? $attendance_map[$row->pengguna_id] : ['absen_approved' => 0, 'dinas_approved' => 0, 'kantor_approved' => 0, 'hari_biasa' => 0, 'hari_libur' => 0];
            $dinas_approved = $att['dinas_approved'];

            $rate_jabatan = isset($row->tunjangan_jabatan) ? (float)$row->tunjangan_jabatan : 0;
            $rate_kinerja = isset($row->tunjangan_kinerja) ? (float)$row->tunjangan_kinerja : 0;
            $rate_konsumsi = isset($row->tunjangan_konsumsi) ? (float)$row->tunjangan_konsumsi : 0;
            $rate_komunikasi = isset($row->tunjangan_komunikasi) ? (float)$row->tunjangan_komunikasi : 0;
            $rate_transportasi = isset($row->tunjangan_transportasi) ? (float)$row->tunjangan_transportasi : 0;
            $rate_bbm = isset($row->tunjangan_bbm) ? (float)$row->tunjangan_bbm : 0;
            $rate_potongan = isset($row->potongan) ? (float)$row->potongan : 0;
            $rate_pendapatanlain = (($row->id_pendapatan_lain ?? 0) > 0 && isset($row->pendapatan_lain)) ? (float)$row->pendapatan_lain : 0;

            $jabatan = $row->terima_tunjangan_jabatan == 1 ? $rate_jabatan : 0;
            $kinerja = $row->terima_tunjangan_kinerja == 1 ? ($rate_kinerja * $dinas_approved) : 0;
            $konsumsi = $row->terima_tunjangan_konsumsi == 1 ? ($rate_konsumsi * $dinas_approved) : 0;
            $bbm = $row->terima_tunjangan_bbm == 1 ? ($rate_bbm * $dinas_approved) : 0;

            if ($row->pengguna_id == 94) {
                $dinas_approved_94 = $att['hari_biasa'] + ($att['hari_libur'] * 3);
                if (strpos($month, '2026-07') !== false) {
                    $dinas_approved_94 = 56;
                }
                if (strpos($month, '2026-09') !== false) {
                    $dinas_approved_94 = 46;
                }

                $kinerja = $row->terima_tunjangan_kinerja == 1 ? ($rate_kinerja * $att['hari_libur'] * 3) : 0;
                $konsumsi = $row->terima_tunjangan_konsumsi == 1 ? ($rate_konsumsi * $dinas_approved_94) : 0;
                $bbm = $row->terima_tunjangan_bbm == 1 ? ($rate_bbm * $dinas_approved_94) : 0;
            }

            if ($row->pengguna_id == 14 && strpos($month, '2026-07') !== false) {
                $kinerja = $row->terima_tunjangan_kinerja == 1 ? ($rate_kinerja * 19) : 0;
                $konsumsi = $row->terima_tunjangan_konsumsi == 1 ? ($rate_konsumsi * 19) : 0;
                $bbm = $row->terima_tunjangan_bbm == 1 ? ($rate_bbm * 19) : 0;
            }

            if ($row->pengguna_id == 25 && strpos($month, '2026-07') !== false) {
                $kinerja = $row->terima_tunjangan_kinerja == 1 ? ($rate_kinerja * 21) : 0;
                $konsumsi = $row->terima_tunjangan_konsumsi == 1 ? ($rate_konsumsi * 21) : 0;
                $bbm = $row->terima_tunjangan_bbm == 1 ? ($rate_bbm * 21) : 0;
            }

            if ($row->pengguna_id == 102 && strpos($month, '2026-07') !== false) {
                $kinerja = $row->terima_tunjangan_kinerja == 1 ? ($rate_kinerja * 19) : 0;
                $konsumsi = $row->terima_tunjangan_konsumsi == 1 ? ($rate_konsumsi * 19) : 0;
                $bbm = $row->terima_tunjangan_bbm == 1 ? ($rate_bbm * 19) : 0;
            }

            if (($row->pengguna_id == 771 || $row->pengguna_id == 766 || strpos(strtolower($row->nama), 'novemby') !== false || strpos(strtolower($row->nama), 'afylmardopila') !== false) && strpos($month, '2026-07') !== false) {
                $kinerja = 270000;
                $konsumsi = 162000;
            }

            if ($row->pengguna_id == 54 && $row->terima_tunjangan_konsumsi == 1) {
                $konsumsi = 5000000;
            }
            $komunikasi = $row->terima_tunjangan_komunikasi == 1 ? $rate_komunikasi : 0;
            $transportasi = $row->terima_tunjangan_transportasi == 1 ? ($row->pengguna_id == 724 ? $rate_transportasi * $dinas_approved : $rate_transportasi) : 0;
            $lainnya = $rate_pendapatanlain;
            $potongan = $rate_potongan;

            $is_training_or_magang = in_array(strtolower(trim($row->status_karyawan ?? '')), ['training', 'magang']) || ($row->pengguna_id == 758);
            if ($is_training_or_magang) {
                if (($row->pengguna_id == 771 || $row->pengguna_id == 766 || strpos(strtolower($row->nama), 'novemby') !== false || strpos(strtolower($row->nama), 'afylmardopila') !== false) && strpos($month, '2026-07') !== false) {
                    $total = $kinerja + $konsumsi;
                } else {
                    $jabatan = 0;
                    $kinerja = 0;
                    $konsumsi = 0;
                    $komunikasi = 0;
                    $transportasi = 0;
                    $bbm = 0;
                    $lainnya = 0;
                    $potongan = 0;
                    $total = 0;
                }
            } else {
                $total = $jabatan + $kinerja + $konsumsi + $komunikasi + $transportasi + $bbm - $potongan + $lainnya;
            }

            if (($row->pengguna_id == 771 || $row->pengguna_id == 766 || strpos(strtolower($row->nama), 'novemby') !== false || strpos(strtolower($row->nama), 'afylmardopila') !== false) && strpos($month, '2026-07') !== false) {
                $jabatan = 0;
                $kinerja = 0;
                $konsumsi = 162000;
                $bbm = 0;
                $komunikasi = 0;
                $transportasi = 0;
                $lainnya = 0;
                $potongan = 0;
                $total = 162000;
            }

            if (isset($overrides[$row->pengguna_id])) {
                $ov = $overrides[$row->pengguna_id];
                if ($ov->hari_kerja !== null && $ov->hari_kerja !== '') {
                    $days_ov = (int)$ov->hari_kerja;
                    if ($row->terima_tunjangan_kinerja == 1 && ($ov->tunjangan_kinerja === null || $ov->tunjangan_kinerja === '')) {
                        $kinerja = $rate_kinerja * $days_ov;
                    }
                    if ($row->terima_tunjangan_konsumsi == 1 && ($ov->tunjangan_konsumsi === null || $ov->tunjangan_konsumsi === '')) {
                        $konsumsi = $rate_konsumsi * $days_ov;
                    }
                    if ($row->terima_tunjangan_bbm == 1 && ($ov->tunjangan_bbm === null || $ov->tunjangan_bbm === '')) {
                        $bbm = $rate_bbm * $days_ov;
                    }
                }
                if ($ov->tunjangan_jabatan !== null && $ov->tunjangan_jabatan !== '') $jabatan = (float)$ov->tunjangan_jabatan;
                if ($ov->tunjangan_kinerja !== null && $ov->tunjangan_kinerja !== '') $kinerja = (float)$ov->tunjangan_kinerja;
                if ($ov->tunjangan_konsumsi !== null && $ov->tunjangan_konsumsi !== '') $konsumsi = (float)$ov->tunjangan_konsumsi;
                if ($ov->tunjangan_komunikasi !== null && $ov->tunjangan_komunikasi !== '') $komunikasi = (float)$ov->tunjangan_komunikasi;
                if ($ov->tunjangan_transportasi !== null && $ov->tunjangan_transportasi !== '') $transportasi = (float)$ov->tunjangan_transportasi;
                if ($ov->tunjangan_bbm !== null && $ov->tunjangan_bbm !== '') $bbm = (float)$ov->tunjangan_bbm;
                if ($ov->tunjangan_lainnya !== null && $ov->tunjangan_lainnya !== '') $lainnya = (float)$ov->tunjangan_lainnya;
                if ($ov->potongan !== null && $ov->potongan !== '') $potongan = (float)$ov->potongan;

                $total = $jabatan + $kinerja + $konsumsi + $komunikasi + $transportasi + $bbm + $lainnya - $potongan;
            }

            $totals['total_jabatan'] += $jabatan;
            $totals['total_kinerja'] += $kinerja;
            $totals['total_konsumsi'] += $konsumsi;
            $totals['total_komunikasi'] += $komunikasi;
            $totals['total_transportasi'] += $transportasi;
            $totals['total_bbm'] += $bbm;
            $totals['total_lainnya'] += $lainnya;
            $totals['total_potongan'] += $potongan;
            $totals['total_tunjangan'] += $total;
        }

        $totals['total_jabatan_formatted'] = rupiah($totals['total_jabatan']);
        $totals['total_kinerja_formatted'] = rupiah($totals['total_kinerja']);
        $totals['total_konsumsi_formatted'] = rupiah($totals['total_konsumsi']);
        $totals['total_komunikasi_formatted'] = rupiah($totals['total_komunikasi']);
        $totals['total_transportasi_formatted'] = rupiah($totals['total_transportasi']);
        $totals['total_bbm_formatted'] = rupiah($totals['total_bbm']);
        $totals['total_lainnya_formatted'] = rupiah($totals['total_lainnya']);
        $totals['total_potongan_formatted'] = rupiah($totals['total_potongan']);
        $totals['total_tunjangan_formatted'] = rupiah($totals['total_tunjangan']);

        echo json_encode(['status' => 'success', 'totals' => $totals]);
        die;
    }

    public function print($param2 = "")
    {
        //load mpdf dan membuat page size legal
        $mpdf = new Mpdf(['format' => 'Legal']);
        //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
        $mpdf->AddPage('L');
        // Data bulan, jika tidak ada dipilih gunakan bulan sekarang
        $month = $param2 ? $param2 : date('Y-m');
        // Data tunjangan
        $excluded_ids = [29]; // Tambahkan ID karyawan yang ingin dikecualikan di sini (contoh: [29, 30, 31])
        $this->db->where_not_in('pg.pengguna_id', $excluded_ids);
        $data = [
            'dt' => $this->md_pengguna->getAllPenggunaAktif(),
            'title_pdf' => 'Rekapitulasi Tunjangan Tidak Tetap',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month
        ];
        // filename dari pdf ketika didownload
        $file_pdf = 'Rekapitulasi Tunjangan Tidak Tetap ' . $data['periode'];

        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_salary_tidak_tetap', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }

    private function _clean_number($val)
    {
        if ($val === null || $val === '') return null;
        $clean = preg_replace('/[^0-9]/', '', (string)$val);
        return ($clean !== '') ? (float)$clean : null;
    }

    public function get_override()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $pengguna_id = $this->input->post('pengguna_id');
        $month = $this->input->post('month');

        $override = $this->md_salary_tidak_tetap->getOverride($pengguna_id, $month);

        $pengguna = $this->md_pengguna->getById($pengguna_id);
        $salary = $this->md_salary->getById($pengguna[0]->id_latestriwayat_salary ?? 0);
        
        $attendance_map = $this->md_absensi->getMonthlyAttendanceSummary($month);
        $att = isset($attendance_map[$pengguna_id]) ? $attendance_map[$pengguna_id] : ['absen_approved' => 0, 'dinas_approved' => 0, 'kantor_approved' => 0, 'hari_biasa' => 0, 'hari_libur' => 0];

        $kantor_approved = $att['kantor_approved'];
        $dinas_approved = $att['dinas_approved'];

        if ($pengguna_id == 94) {
            $dinas_approved = $att['hari_biasa'] + ($att['hari_libur'] * 3);
            $kantor_approved = $dinas_approved;
            if (strpos($month, '2026-07') !== false) {
                $dinas_approved = 53;
                $kantor_approved = 53;
            }
            if (strpos($month, '2026-09') !== false) {
                $dinas_approved = 46;
                $kantor_approved = 46;
            }
        }

        $rate_kinerja = isset($salary[0]->tunjangan_kinerja) ? (float)$salary[0]->tunjangan_kinerja : 0;
        $rate_konsumsi = isset($salary[0]->tunjangan_konsumsi) ? (float)$salary[0]->tunjangan_konsumsi : 0;
        $rate_komunikasi = isset($salary[0]->tunjangan_komunikasi) ? (float)$salary[0]->tunjangan_komunikasi : 0;
        $rate_transportasi = isset($salary[0]->tunjangan_transportasi) ? (float)$salary[0]->tunjangan_transportasi : 0;
        $rate_bbm = isset($salary[0]->tunjangan_bbm) ? (float)$salary[0]->tunjangan_bbm : 0;
        $rate_jabatan = isset($salary[0]->tunjangan_jabatan) ? (float)$salary[0]->tunjangan_jabatan : 0;
        $rate_potongan = isset($salary[0]->potongan) ? (float)$salary[0]->potongan : 0;
        $rate_lainnya = (($pengguna[0]->id_pendapatan_lain ?? 0) > 0 && isset($salary[0]->pendapatan_lain)) ? (float)$salary[0]->pendapatan_lain : 0;

        $auto_kinerja = ($pengguna[0]->terima_tunjangan_kinerja == 1) ? ($rate_kinerja * $dinas_approved) : 0;
        if ($pengguna_id == 94) {
            $auto_kinerja = ($pengguna[0]->terima_tunjangan_kinerja == 1) ? ($rate_kinerja * $att['hari_libur'] * 3) : 0;
        }

        $auto_konsumsi = ($pengguna[0]->terima_tunjangan_konsumsi == 1) ? ($rate_konsumsi * $dinas_approved) : 0;
        if ($pengguna_id == 54) {
            $auto_konsumsi = ($pengguna[0]->terima_tunjangan_konsumsi == 1) ? 5000000 : 0;
        }

        $auto = [
            'hari_kerja' => $kantor_approved,
            'tunjangan_jabatan' => ($pengguna[0]->terima_tunjangan_jabatan == 1) ? $rate_jabatan : 0,
            'tunjangan_kinerja' => $auto_kinerja,
            'tunjangan_konsumsi' => $auto_konsumsi,
            'tunjangan_komunikasi' => ($pengguna[0]->terima_tunjangan_komunikasi == 1) ? $rate_komunikasi : 0,
            'tunjangan_transportasi' => ($pengguna[0]->terima_tunjangan_transportasi == 1) ? ($pengguna_id == 724 ? $rate_transportasi * $dinas_approved : $rate_transportasi) : 0,
            'tunjangan_bbm' => ($pengguna[0]->terima_tunjangan_bbm == 1) ? ($rate_bbm * $dinas_approved) : 0,
            'tunjangan_lainnya' => $rate_lainnya,
            'potongan' => $rate_potongan
        ];

        $rates = [
            'rate_kinerja' => ($pengguna[0]->terima_tunjangan_kinerja == 1) ? $rate_kinerja : 0,
            'rate_konsumsi' => ($pengguna[0]->terima_tunjangan_konsumsi == 1) ? $rate_konsumsi : 0,
            'rate_bbm' => ($pengguna[0]->terima_tunjangan_bbm == 1) ? $rate_bbm : 0,
        ];

        echo json_encode([
            'status' => 'success',
            'override' => $override,
            'auto' => $auto,
            'rates' => $rates
        ]);
        die;
    }

    public function save_override()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $pengguna_id = $this->input->post('pengguna_id', TRUE);
        $month = $this->input->post('month', TRUE);
        $is_reset = $this->input->post('is_reset', TRUE);

        if ($is_reset) {
            $this->md_salary_tidak_tetap->resetOverride($pengguna_id, $month);
            echo json_encode(['status' => 'success', 'message' => 'Hitungan manual berhasil direset. Kembali ke hitungan otomatis.']);
            die;
        }

        $data = [
            'pengguna_id' => $pengguna_id,
            'month' => $month,
            'hari_kerja' => $this->input->post('hari_kerja') !== '' ? (int)$this->input->post('hari_kerja') : NULL,
            'tunjangan_jabatan' => $this->_clean_number($this->input->post('tunjangan_jabatan')),
            'tunjangan_kinerja' => $this->_clean_number($this->input->post('tunjangan_kinerja')),
            'tunjangan_konsumsi' => $this->_clean_number($this->input->post('tunjangan_konsumsi')),
            'tunjangan_komunikasi' => $this->_clean_number($this->input->post('tunjangan_komunikasi')),
            'tunjangan_transportasi' => $this->_clean_number($this->input->post('tunjangan_transportasi')),
            'tunjangan_bbm' => $this->_clean_number($this->input->post('tunjangan_bbm')),
            'tunjangan_lainnya' => $this->_clean_number($this->input->post('tunjangan_lainnya')),
            'potongan' => $this->_clean_number($this->input->post('potongan')),
            'keterangan' => $this->input->post('keterangan', TRUE)
        ];

        $this->md_salary_tidak_tetap->saveOverride($data);
        echo json_encode(['status' => 'success', 'message' => 'Hitungan manual tunjangan berhasil disimpan!']);
        die;
    }
}
