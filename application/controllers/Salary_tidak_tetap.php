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

            $excluded_ids = [29]; // Tambahkan ID karyawan yang ingin dikecualikan di sini (contoh: [29, 30, 31])
            $this->db->where_not_in('pg.pengguna_id', $excluded_ids);
            $dt = $this->md_pengguna->getAllPenggunaAktif();
            $start = $this->input->post('start');
            $month = $this->input->post('filter_month');
            $monthfield = $month ? $month : date("Y-m");
            $overrides = $this->md_salary_tidak_tetap->getOverridesByMonth($monthfield);

            $data = array();

            foreach ($dt['data'] as $row) {
                // Reconnect database karna looping terlalu berat
                $this->db->reconnect();

                $pengguna = $this->md_pengguna->getById($row->pengguna_id);
                $salary = $this->md_salary->getById($pengguna[0]->id_latestriwayat_salary);

                $id = encrypt($row->pengguna_id);
                if (($row->pengguna_id == 771 || $row->pengguna_id == 766 || strpos(strtolower($row->nama), 'novemby') !== false || strpos(strtolower($row->nama), 'afylmardopila') !== false) && strpos($monthfield, '2026-07') !== false) {
                    $nama_pengguna = '<a href="salary_tidak_tetap/show/detail/' . $id . '">' . $row->nama . '</a>';
                    $ov_check = isset($overrides[$row->pengguna_id]) ? $overrides[$row->pengguna_id] : null;
                    if ($ov_check) {
                        $nama_pengguna .= ' <span class="badge badge-warning text-dark ml-1" style="font-size:10px;" title="' . htmlspecialchars($ov_check->keterangan ?? 'Manual Override', ENT_QUOTES) . '">Manual</span>';
                    }
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

                    $btn_action = '<div class="btn-group" role="group">
                        <button type="button" class="btn btn-xs btn-warning btn-edit-override" data-id="' . $row->pengguna_id . '" data-nama="' . htmlspecialchars($row->nama, ENT_QUOTES) . '" title="Edit Manual Hitungan Tunjangan"><i class="fa fa-edit"></i> Edit</button>
                        <a target="_blank" class="btn btn-xs btn-danger" href="' . base_url("salary/print_slip_month/" . $monthfield . "/" . $id) . '" title="Print Slip Gaji"><i class="fas fa-file-invoice-dollar"></i> Slip Gaji</a>
                    </div>';

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
                $dataTunjangan = tunjangan($row->pengguna_id, $monthfield);
                $absen_approved = $dataTunjangan['absen_approved'];
                $dinas_approved = $dataTunjangan['dinas_approved'];
                $kantor_approved = isset($dataTunjangan['kantor_approved']) ? $dataTunjangan['kantor_approved'] : $dinas_approved;
                if ($row->pengguna_id == 94) {
                    $salaryBoddyB = $this->md_absensi->getTunjanganBoddyBiasa($row->pengguna_id, $monthfield);
                    $salaryBoddyL = $this->md_absensi->getTunjanganBoddyLibur($row->pengguna_id, $monthfield);
                    $dinas_approved = count($salaryBoddyB) + (count($salaryBoddyL) * 3);
                    $kantor_approved = $dinas_approved;
                    if (strpos($monthfield, '2026-07') !== false) {
                        $dinas_approved = 53;
                        $kantor_approved = 53;
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

                //Tunjangan Formated Rp
                $jabatan = isset($salary[0]->tunjangan_jabatan) ? 'Rp. ' . rupiah($salary[0]->tunjangan_jabatan) : 'Rp. -';
                $kinerja = isset($salary[0]->tunjangan_kinerja) ? 'Rp. ' . rupiah($salary[0]->tunjangan_kinerja) : 'Rp. -';
                $konsumsi = isset($salary[0]->tunjangan_konsumsi) ? 'Rp. ' . rupiah($salary[0]->tunjangan_konsumsi) : 'Rp. -';
                $komunikasi = isset($salary[0]->tunjangan_komunikasi) ? 'Rp. ' . rupiah($salary[0]->tunjangan_komunikasi) : 'Rp. -';
                $transportasi = isset($salary[0]->tunjangan_transportasi) ? 'Rp. ' . rupiah($salary[0]->tunjangan_transportasi) : 'Rp. -';
                if ($row->pengguna_id == 724 && $row->terima_tunjangan_transportasi == 1) {
                    $rate_transport = isset($salary[0]->tunjangan_transportasi) ? $salary[0]->tunjangan_transportasi : 0;
                    $transportasi = 'Rp. ' . rupiah($rate_transport * $dinas_approved);
                }
                $bbm = isset($salary[0]->tunjangan_bbm) ? 'Rp. ' . rupiah($salary[0]->tunjangan_bbm) : 'Rp. -';
                $potongan = isset($salary[0]->potongan) ? 'Rp. ' . rupiah($salary[0]->potongan) : 'Rp. -';
                $s_pendapatanlain = isset($salary[0]->pendapatan_lain) ? 'Rp. ' . rupiah($salary[0]->pendapatan_lain) : 'Rp. -';

                //variabel ceklis tanpa tunjanan
                $s_karyawan = $row->status_karyawan;
                $s_jabatan = $row->terima_tunjangan_jabatan;
                $s_kinerja = $row->terima_tunjangan_kinerja;
                $s_konsumsi = $row->terima_tunjangan_konsumsi;
                $s_komunikasi = $row->terima_tunjangan_komunikasi;
                $s_transportasi = $row->terima_tunjangan_transportasi;
                $s_bbm = $row->terima_tunjangan_bbm;
                if (($pengguna[0]->id_pendapatan_lain) <= 0) {
                    $s_pendapatanlain = 0;
                } else {
                    $s_pendapatanlain = $salary[0]->pendapatan_lain;
                }

                //Tunjangan Tanpa Format Rp
                if ($s_jabatan == 1) {
                    $a = isset($salary[0]->tunjangan_jabatan) ? '' . ($salary[0]->tunjangan_jabatan) : '0';
                } else {
                    $a = 0;
                }
                if ($s_kinerja == 1) {
                    $b = isset($salary[0]->tunjangan_kinerja) ? '' . ($salary[0]->tunjangan_kinerja) : '0';
                } else {
                    $b = 0;
                }
                if ($s_konsumsi == 1) {
                    $c = isset($salary[0]->tunjangan_konsumsi) ? '' . ($salary[0]->tunjangan_konsumsi) : '0';
                } else {
                    $c = 0;
                }
                if ($s_komunikasi == 1) {
                    $d = isset($salary[0]->tunjangan_komunikasi) ? '' . ($salary[0]->tunjangan_komunikasi) : '0';
                } else {
                    $d = 0;
                }
                 if ($s_transportasi == 1) {
                     $e = isset($salary[0]->tunjangan_transportasi) ? '' . ($salary[0]->tunjangan_transportasi) : '0';
                     if ($row->pengguna_id == 724) {
                         $e = $e * $dinas_approved;
                     }
                 } else {
                     $e = 0;
                 }
                if ($s_bbm == 1) {
                    $g = isset($salary[0]->tunjangan_bbm) ? '' . ($salary[0]->tunjangan_bbm) : '0'; //sementara
                } else {
                    $g = 0;
                }

                //variabel status karyawan dan dapat tunjangan atau tidak

                // $terima_jabatan = $row->tgl_keluar;
                //Tunjangan Konsumsi
                $hitung_konsumsi = $c * $dinas_approved;
                $total_konsumsi = 'Rp. ' . ($hitung_konsumsi ? rupiah($hitung_konsumsi) : '-');
                $pott = isset($salary[0]->potongan) ? '' . ($salary[0]->potongan) : '0';
                //Tunjangan Kinerja
                $hitung_kinerja = $b * $dinas_approved;
                if ($row->pengguna_id == 94) {
                    $hitung_kinerja = $b * count($salaryBoddyL) * 3;
                    if (strpos($monthfield, '2026-07') !== false) {
                        $hitung_kinerja = $b * 33;
                    }
                }
                $total_kinerja = 'Rp. ' . ($hitung_kinerja ? rupiah($hitung_kinerja) : '-');
                //bbm
                $hitung_bbm = $g * $dinas_approved;
                $total_bbm = 'Rp. ' . ($hitung_bbm ? rupiah($hitung_bbm) : '-');
                $s_karyawan = $row->status_karyawan;
                //Total Tujangan Tidak Tetap
                if ($s_karyawan == "training") {
                    if ($row->pengguna_id == 771 && strpos($monthfield, '2026-07') !== false) {
                        $f = 162000;
                    } else {
                        $f = '0';
                    }
                } else {
                    $f = ($a + $hitung_kinerja + $hitung_konsumsi + $d + $e + $hitung_bbm - $pott) + $s_pendapatanlain;
                }

                if (strpos(strtolower($row->nama), 'novemby') !== false && strpos($monthfield, '2026-07') !== false) {
                    $dinas_approved = 9;
                    $f = 162000;
                }

                $nama_pengguna = '<a href="salary_tidak_tetap/show/detail/' . $id . '">' . $row->nama . '</a>';
                $ov_check = isset($overrides[$row->pengguna_id]) ? $overrides[$row->pengguna_id] : null;
                if ($ov_check) {
                    $nama_pengguna .= ' <span class="badge badge-warning text-dark ml-1" style="font-size:10px;" title="' . htmlspecialchars($ov_check->keterangan ?? 'Manual Override', ENT_QUOTES) . '">Manual</span>';
                }

                $th = array();
                $th[] = ++$start . '.';
                $th[] = $nama_pengguna;
                $th[] = ($kantor_approved) . ' hari';
                $th[] = $row->level;
                $th[] = $row->status_karyawan;

                $s_karyawan = $row->status_karyawan;
                //Training Tidak ada
                if ($s_karyawan == "training") {
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
                } else {
                    if ($s_jabatan == 1) {
                        $th[] = $jabatan;
                    } else {
                        $th[] = '<i class="fa fa-times"></i>';
                    }
                    if ($s_kinerja == 1) {
                        $th[] = $total_kinerja;
                    } else {
                        $th[] = '<i class="fa fa-times"></i>';
                    }
                    if ($s_konsumsi == 1) {
                        if ($row->pengguna_id == 54) { // pak bob
                            $hitung_konsumsi = 5000000;
                            $total_konsumsi = 'Rp. 5.000.000';
                        }
                        $th[] = $total_konsumsi;
                    } else {
                        $th[] = '<i class="fa fa-times"></i>';
                    }
                    if ($s_komunikasi == 1) {
                        $th[] = $komunikasi;
                    } else {
                        $th[] = '<i class="fa fa-times"></i>';
                    }
                    if ($s_transportasi == 1) {
                        $th[] = $transportasi;
                    } else {
                        $th[] = '<i class="fa fa-times"></i>';
                    }
                    if ($s_bbm == 1) {
                        $th[] = $total_bbm;
                    } else {
                        $th[] = '<i class="fa fa-times"></i>';
                    }

                    if (($pengguna[0]->id_pendapatan_lain) <= 0) {
                        $th[] = '<i class="fa fa-times"></i>';
                    } else if ($s_pendapatanlain <= 0) {
                        $th[] = '<i class="fa fa-times"></i>';
                    } else {
                        $th[] = isset($s_pendapatanlain) ? 'Rp. ' . rupiah($s_pendapatanlain) : 'Rp. -';
                    }
                }

                $ov = isset($overrides[$row->pengguna_id]) ? $overrides[$row->pengguna_id] : null;
                if ($ov) {
                    if ($ov->hari_kerja !== null && $ov->hari_kerja !== '') {
                        $dinas_approved = (int)$ov->hari_kerja;
                        $th[2] = $dinas_approved . ' hari';

                        // Recalculate automatic kinerja & konsumsi based on updated days if not manually set
                        if ($s_kinerja == 1 && ($ov->tunjangan_kinerja === null || $ov->tunjangan_kinerja === '')) {
                            $hitung_kinerja = $b * $dinas_approved;
                            $total_kinerja = 'Rp. ' . rupiah($hitung_kinerja);
                            $th[6] = $total_kinerja;
                        }
                        if ($s_konsumsi == 1 && ($ov->tunjangan_konsumsi === null || $ov->tunjangan_konsumsi === '')) {
                            $hitung_konsumsi = $c * $dinas_approved;
                            $total_konsumsi = 'Rp. ' . rupiah($hitung_konsumsi);
                            $th[7] = $total_konsumsi;
                        }
                        if ($s_bbm == 1 && ($ov->tunjangan_bbm === null || $ov->tunjangan_bbm === '')) {
                            $hitung_bbm = $g * $dinas_approved;
                            $total_bbm = 'Rp. ' . rupiah($hitung_bbm);
                            $th[10] = $total_bbm;
                        }
                    }
                    if ($ov->tunjangan_jabatan !== null && $ov->tunjangan_jabatan !== '') {
                        $a = (float)$ov->tunjangan_jabatan;
                        $jabatan = 'Rp. ' . rupiah($a);
                        $th[5] = $jabatan;
                    }
                    if ($ov->tunjangan_kinerja !== null && $ov->tunjangan_kinerja !== '') {
                        $hitung_kinerja = (float)$ov->tunjangan_kinerja;
                        $total_kinerja = 'Rp. ' . rupiah($hitung_kinerja);
                        $th[6] = $total_kinerja;
                    }
                    if ($ov->tunjangan_konsumsi !== null && $ov->tunjangan_konsumsi !== '') {
                        $hitung_konsumsi = (float)$ov->tunjangan_konsumsi;
                        $total_konsumsi = 'Rp. ' . rupiah($hitung_konsumsi);
                        $th[7] = $total_konsumsi;
                    }
                    if ($ov->tunjangan_komunikasi !== null && $ov->tunjangan_komunikasi !== '') {
                        $d = (float)$ov->tunjangan_komunikasi;
                        $komunikasi = 'Rp. ' . rupiah($d);
                        $th[8] = $komunikasi;
                    }
                    if ($ov->tunjangan_transportasi !== null && $ov->tunjangan_transportasi !== '') {
                        $e = (float)$ov->tunjangan_transportasi;
                        $transportasi = 'Rp. ' . rupiah($e);
                        $th[9] = $transportasi;
                    }
                    if ($ov->tunjangan_bbm !== null && $ov->tunjangan_bbm !== '') {
                        $g = (float)$ov->tunjangan_bbm;
                        $total_bbm = 'Rp. ' . rupiah($g);
                        $th[10] = $total_bbm;
                    }
                    if ($ov->tunjangan_lainnya !== null && $ov->tunjangan_lainnya !== '') {
                        $s_pendapatanlain = (float)$ov->tunjangan_lainnya;
                        $th[11] = 'Rp. ' . rupiah($s_pendapatanlain);
                    }
                    if ($ov->potongan !== null && $ov->potongan !== '') {
                        $pott = (float)$ov->potongan;
                        $potongan = 'Rp. ' . rupiah($pott);
                    }

                    $f = ($a + $hitung_kinerja + $hitung_konsumsi + $d + $e + $g - $pott) + $s_pendapatanlain;
                }

                $btn_action = '<div class="btn-group" role="group">
                    <button type="button" class="btn btn-xs btn-warning btn-edit-override" data-id="' . $row->pengguna_id . '" data-nama="' . htmlspecialchars($row->nama, ENT_QUOTES) . '" title="Edit Manual Hitungan Tunjangan"><i class="fa fa-edit"></i> Edit</button>
                    <a target="_blank" class="btn btn-xs btn-danger" href="' . base_url("salary/print_slip_month/" . $monthfield . "/" . $id) . '" title="Print Slip Gaji"><i class="fas fa-file-invoice-dollar"></i> Slip Gaji</a>
                </div>';

                $th[] = $potongan;
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
        $overrides = $this->md_salary_tidak_tetap->getOverridesByMonth($month);
        $excluded_ids = [29]; // Tambahkan ID karyawan yang ingin dikecualikan di sini (contoh: [29, 30, 31])
        $this->db->where_not_in('pg.pengguna_id', $excluded_ids);
        $dt = $this->md_pengguna->getAllPenggunaAktifList();

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
            $this->db->reconnect();
            $pengguna = $this->md_pengguna->getById($row->pengguna_id);
            $salary = $this->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
            $dataSistem = tunjangan($row->pengguna_id, $month);

            $jabatan = $row->terima_tunjangan_jabatan == 1 ? (isset($salary[0]->tunjangan_jabatan) ? $salary[0]->tunjangan_jabatan : 0) : 0;
            $kinerja = $row->terima_tunjangan_kinerja == 1 ? $dataSistem['total_kinerjadinas'] : 0;
            $konsumsi = $row->terima_tunjangan_konsumsi == 1 ? $dataSistem['total_konsumsidinas'] : 0;
            $bbm = $row->terima_tunjangan_bbm == 1 ? $dataSistem['total_bbm'] : 0;

            if ($row->pengguna_id == 94) {
                $rate_kinerja = isset($salary[0]->tunjangan_kinerja) ? $salary[0]->tunjangan_kinerja : 0;
                $rate_konsumsi = isset($salary[0]->tunjangan_konsumsi) ? $salary[0]->tunjangan_konsumsi : 0;
                $rate_bbm = isset($salary[0]->tunjangan_bbm) ? $salary[0]->tunjangan_bbm : 0;

                $salaryBoddyB = $this->md_absensi->getTunjanganBoddyBiasa($row->pengguna_id, $month);
                $salaryBoddyL = $this->md_absensi->getTunjanganBoddyLibur($row->pengguna_id, $month);
                $dinas_approved_94 = count($salaryBoddyB) + (count($salaryBoddyL) * 3);
                if (strpos($month, '2026-07') !== false) {
                    $dinas_approved_94 = 56;
                }

                $kinerja = $row->terima_tunjangan_kinerja == 1 ? ($rate_kinerja * count($salaryBoddyL) * 3) : 0;
                $konsumsi = $row->terima_tunjangan_konsumsi == 1 ? ($rate_konsumsi * $dinas_approved_94) : 0;
                $bbm = $row->terima_tunjangan_bbm == 1 ? ($rate_bbm * $dinas_approved_94) : 0;
            }

            if ($row->pengguna_id == 14 && strpos($month, '2026-07') !== false) {
                $rate_kinerja = isset($salary[0]->tunjangan_kinerja) ? $salary[0]->tunjangan_kinerja : 0;
                $rate_konsumsi = isset($salary[0]->tunjangan_konsumsi) ? $salary[0]->tunjangan_konsumsi : 0;
                $rate_bbm = isset($salary[0]->tunjangan_bbm) ? $salary[0]->tunjangan_bbm : 0;

                $kinerja = $row->terima_tunjangan_kinerja == 1 ? ($rate_kinerja * 19) : 0;
                $konsumsi = $row->terima_tunjangan_konsumsi == 1 ? ($rate_konsumsi * 19) : 0;
                $bbm = $row->terima_tunjangan_bbm == 1 ? ($rate_bbm * 19) : 0;
            }

            if ($row->pengguna_id == 25 && strpos($month, '2026-07') !== false) {
                $rate_kinerja = isset($salary[0]->tunjangan_kinerja) ? $salary[0]->tunjangan_kinerja : 0;
                $rate_konsumsi = isset($salary[0]->tunjangan_konsumsi) ? $salary[0]->tunjangan_konsumsi : 0;
                $rate_bbm = isset($salary[0]->tunjangan_bbm) ? $salary[0]->tunjangan_bbm : 0;

                $kinerja = $row->terima_tunjangan_kinerja == 1 ? ($rate_kinerja * 21) : 0;
                $konsumsi = $row->terima_tunjangan_konsumsi == 1 ? ($rate_konsumsi * 21) : 0;
                $bbm = $row->terima_tunjangan_bbm == 1 ? ($rate_bbm * 21) : 0;
            }

            if ($row->pengguna_id == 102 && strpos($month, '2026-07') !== false) {
                $rate_kinerja = isset($salary[0]->tunjangan_kinerja) ? $salary[0]->tunjangan_kinerja : 0;
                $rate_konsumsi = isset($salary[0]->tunjangan_konsumsi) ? $salary[0]->tunjangan_konsumsi : 0;
                $rate_bbm = isset($salary[0]->tunjangan_bbm) ? $salary[0]->tunjangan_bbm : 0;

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
            $komunikasi = $row->terima_tunjangan_komunikasi == 1 ? $dataSistem['total_komunikasi'] : 0;
            $transportasi = $row->terima_tunjangan_transportasi == 1 ? $dataSistem['total_transportasi'] : 0;
            $lainnya = ($pengguna[0]->id_pendapatan_lain > 0 && $dataSistem['total_tunjanganlain'] > 0) ? $dataSistem['total_tunjanganlain'] : 0;
            $potongan = isset($salary[0]->potongan) ? $salary[0]->potongan : 0;

            if (strtolower($row->status_karyawan) == 'training') {
                if (($row->pengguna_id == 771 || $row->pengguna_id == 766 || strpos(strtolower($row->nama), 'novemby') !== false || strpos(strtolower($row->nama), 'afylmardopila') !== false) && strpos($month, '2026-07') !== false) {
                    $total = $kinerja + $konsumsi;
                } else {
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
                    $rate_kinerja_val = isset($salary[0]->tunjangan_kinerja) ? (float)$salary[0]->tunjangan_kinerja : 0;
                    $rate_konsumsi_val = isset($salary[0]->tunjangan_konsumsi) ? (float)$salary[0]->tunjangan_konsumsi : 0;
                    $rate_bbm_val = isset($salary[0]->tunjangan_bbm) ? (float)$salary[0]->tunjangan_bbm : 0;

                    if ($row->terima_tunjangan_kinerja == 1 && ($ov->tunjangan_kinerja === null || $ov->tunjangan_kinerja === '')) {
                        $kinerja = $rate_kinerja_val * $days_ov;
                    }
                    if ($row->terima_tunjangan_konsumsi == 1 && ($ov->tunjangan_konsumsi === null || $ov->tunjangan_konsumsi === '')) {
                        $konsumsi = $rate_konsumsi_val * $days_ov;
                    }
                    if ($row->terima_tunjangan_bbm == 1 && ($ov->tunjangan_bbm === null || $ov->tunjangan_bbm === '')) {
                        $bbm = $rate_bbm_val * $days_ov;
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
        $dataSistem = tunjangan($pengguna_id, $month);

        $kantor_approved = $dataSistem['kantor_approved'] ?? $dataSistem['dinas_approved'] ?? 0;
        $dinas_approved = $dataSistem['dinas_approved'] ?? 0;
        $rate_kinerja = isset($salary[0]->tunjangan_kinerja) ? (float)$salary[0]->tunjangan_kinerja : 0;
        $rate_konsumsi = isset($salary[0]->tunjangan_konsumsi) ? (float)$salary[0]->tunjangan_konsumsi : 0;
        $rate_komunikasi = isset($salary[0]->tunjangan_komunikasi) ? (float)$salary[0]->tunjangan_komunikasi : 0;
        $rate_transportasi = isset($salary[0]->tunjangan_transportasi) ? (float)$salary[0]->tunjangan_transportasi : 0;
        $rate_bbm = isset($salary[0]->tunjangan_bbm) ? (float)$salary[0]->tunjangan_bbm : 0;
        $rate_jabatan = isset($salary[0]->tunjangan_jabatan) ? (float)$salary[0]->tunjangan_jabatan : 0;
        $rate_potongan = isset($salary[0]->potongan) ? (float)$salary[0]->potongan : 0;
        $rate_lainnya = isset($salary[0]->pendapatan_lain) ? (float)$salary[0]->pendapatan_lain : 0;

        $auto = [
            'hari_kerja' => $kantor_approved,
            'tunjangan_jabatan' => ($pengguna[0]->terima_tunjangan_jabatan == 1) ? $rate_jabatan : 0,
            'tunjangan_kinerja' => ($pengguna[0]->terima_tunjangan_kinerja == 1) ? ($dataSistem['total_kinerjadinas'] ?? ($rate_kinerja * $dinas_approved)) : 0,
            'tunjangan_konsumsi' => ($pengguna[0]->terima_tunjangan_konsumsi == 1) ? ($dataSistem['total_konsumsidinas'] ?? ($rate_konsumsi * $dinas_approved)) : 0,
            'tunjangan_komunikasi' => ($pengguna[0]->terima_tunjangan_komunikasi == 1) ? ($dataSistem['total_komunikasi'] ?? $rate_komunikasi) : 0,
            'tunjangan_transportasi' => ($pengguna[0]->terima_tunjangan_transportasi == 1) ? ($dataSistem['total_transportasi'] ?? $rate_transportasi) : 0,
            'tunjangan_bbm' => ($pengguna[0]->terima_tunjangan_bbm == 1) ? ($dataSistem['total_bbm'] ?? ($rate_bbm * $dinas_approved)) : 0,
            'tunjangan_lainnya' => ($pengguna[0]->id_pendapatan_lain > 0) ? ($dataSistem['total_tunjanganlain'] ?? $rate_lainnya) : 0,
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
