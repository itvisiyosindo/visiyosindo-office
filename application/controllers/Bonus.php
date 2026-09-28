<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;

defined('BASEPATH') or exit('No direct script access allowed');

class Bonus extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_bonus');
        $this->load->model('md_laporan');
        $this->load->model('md_pengguna');
        $this->load->model('md_salary_tidak_tetap');
        $this->load->model('md_salary');
        $this->load->model('md_divisi_pengguna');
        $this->load->model('md_absensi');
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
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $GapokTahunIni       = $this->md_bonus->getGapokTahunIni();
        $page_data['GapokTahunIni'] = $GapokTahunIni;



        $page_data['page_name'] = 'salary/v_bonus';
        $page_data['page_title'] = 'Bonus Tahunan';
        $page_data['page_desc'] = 'Management Salary Bonus Tahunan';
        $this->load->view('index', $page_data);
    }

    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor('all');
        if($param == 'evaluasi'){  
            $page_data['switch']      	= $this->id_navbar();
            $page_data['list_nama']     = $this->md_laporan->getBywhereActive();
            $page_data['page_name']     = 'evaluasi/v_evaluasi_smt';
            $page_data['page_title']    = 'Evaluasi Semester';
            $page_data['page_desc']     = 'Management Data Evaluasi Semester';
            $this->load->view('index', $page_data);
        }else if($param == 'list'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['page_name']     = 'jobdesc/v_job';
            $page_data['page_title']    = 'Jobdesk';
            $page_data['page_desc']     = 'Daftar Jobdesk';
            $this->load->view('index', $page_data);
          
		}
    }



    public function addEvaluasi()
    {
        grantAccessFor('all');

            
            
            $data['id_pengaju']   = sessPenggunaId();
            $data['id_pengguna']  = $this->input->post('id_pengguna', TRUE);
            $data['smt']          = $this->input->post('smt', TRUE);
            $data['periode']      = $this->input->post('periode', TRUE);
            $data['nilai']        = $this->input->post('nilai', TRUE);

            $this->md_bonus->addEv($data);

            /** LOG */
            addLog('Evaluasi', 'Menambah Evaluasi Semester');
            ajaxReturnDie('success', 'Evaluasi Berhasil Diajukan', TRUE);
          

    }

    public function addGapok()
    {
        grantAccessFor('all');

            
            
            $data['gapok']        = $this->input->post('gapok', TRUE);

            $this->md_bonus->addGapok($data);

            /** LOG */
            addLog('Bonus Tahunan', 'Menambah Gaji pokok');
            ajaxReturnDie('success', 'Berhasil Ditambahkan', TRUE);
          

    }


    public function pagination($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'evaluasi') {
            
            $dt     = $this->md_bonus->getAllEv();
         
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {
              
              $id       	= encrypt($row->id);
              $li_btn   	= '
                    <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    </div>';
              
              $th = array();
              $th[] = ++$start;
              $th[] = $row->nama;
              $th[] = $row->no_pegawai;
              $th[] = $row->jabatan;
              $th[] = $row->smt;
              $th[] = $row->periode;
              $th[] = $row->nilai;
              $th[] = $li_btn;
              $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;					
        
      }else if ($param == 'all') {
            
            $dt     = $this->md_bonus->getAllBonus();
            $start = $this->input->post('start');
            $data  = array();
            $tahun_ini = date('Y');

            // ambil gapok terbaru tahun ini
            $this->db->select('gapok');
            $this->db->from('bonus_config');
            $this->db->where('YEAR(created_at)', $tahun_ini);
            $this->db->order_by('id', 'DESC');
            $this->db->limit(1);
            $q_gapok = $this->db->get()->row();
            $gapok = ($q_gapok) ? $q_gapok->gapok : 0;

            foreach ($dt['data'] as $row) {

                //Status Karyawan
                if ($row->status_karyawan == 'training') {
                    $status = "Training";
                } elseif ($row->status_karyawan == 'kontrak') {
                    $status = "PKWT";
                } elseif ($row->status_karyawan == 'tetap') {
                    $status = "PKWTT";
                } else {
                    $status = "";
                }

                //Masa Kerja
                $tgl_kontrak = $row->tgl_kontrak;
                $awal = new DateTime($tgl_kontrak);
                $sekarang = new DateTime();
                $diff = $awal->diff($sekarang);
                $masa_kerja = [];

                if ($diff->y > 0) $masa_kerja[] = $diff->y . ' Tahun';
                if ($diff->m > 0) $masa_kerja[] = $diff->m . ' Bulan';
                if ($diff->d > 0 || empty($masa_kerja)) $masa_kerja[] = $diff->d . ' Hari';
                $masa_kerja = implode(' ', $masa_kerja);

                // Nilai Evaluasi terbaru tahun ini
                $this->db->select('nilai');
                $this->db->from('nilai_evaluasi');
                $this->db->where('id_pengguna', $row->pengguna_id);
                $this->db->where('periode', $tahun_ini);
                $this->db->order_by('created_at', 'DESC');
                $this->db->limit(1);
                $q = $this->db->get()->row();
                $nilai = ($q) ? $q->nilai : 0;

                // pastikan nilai numerik
                $nilai = 0;
                if ($q && is_numeric(trim($q->nilai))) {
                    $nilai = floatval(trim($q->nilai));
                }

                // Presentasi berdasarkan nilai
                if ($nilai >= 90 && $nilai <= 100) {
                    $presentasi = 1.00; // 100%
                } elseif ($nilai >= 80 && $nilai < 90) {
                    $presentasi = 0.80; // 80%
                } elseif ($nilai >= 70 && $nilai < 80) {
                    $presentasi = 0.60;
                } elseif ($nilai >= 60 && $nilai < 70) {
                    $presentasi = 0.40;
                } elseif ($nilai > 0 && $nilai < 60) {
                    $presentasi = 0.20;
                } else {
                    $presentasi = 0;
                }

                // Prorate
                if (empty($row->tgl_kontrak) || $row->tgl_kontrak == '0000-00-00') {
                    $prorate = "Belum Kontrak";
                } else {
                    $tgl_kontrak = new DateTime($row->tgl_kontrak);
                    $diff = $tgl_kontrak->diff($sekarang);
                    $total_bulan = ($diff->y * 12) + $diff->m;

                    if ($diff->y >= 1) {
                        $prorate = "Tidak";
                    } elseif ($total_bulan == 0 && $diff->d > 0) {
                        $prorate = "Belum sampai 1 bulan";
                    } else {
                        $prorate = "Ya (" . $total_bulan . "/12)";
                    }
                }

                // Hitung Total Bonus
                if (empty($row->tgl_kontrak) || $row->tgl_kontrak == '0000-00-00') {
                    $totalBonus = "Belum dapat (belum kontrak)";
                } elseif (in_array($row->pengguna_id, [105, 745])) {
                    $totalBonus = "Tidak dapat (Marketing)";
                    $presentasi = 0; // paksa presentasi 0 agar tampil "-"
                } elseif ($prorate == "Belum sampai 1 bulan") {
                    $totalBonus = "Belum sampai sebulan kontrak";
                } elseif (strpos($prorate, 'Ya') !== false) {
                    //preg_match('/\((\d+)\/12\)/', $prorate, $match);
                    //$bulan = isset($match[1]) ? $match[1] : 0;
                    //$totalBonus = round(($bulan / 12) * $gapok * $presentasi);
                    $totalBonus = "Belum sampai setahun kontrak";
                } else {
                    $totalBonus = round($gapok * $presentasi);
                }

                // Format presentasi biar tampil dengan persen
                $presentasi_label = ($presentasi > 0) ? ($presentasi * 100) . '%' : '-';

                // Susun kolom DataTables
                $th = array();
                $th[] = ++$start;
                $th[] = $row->nama;
                $th[] = $row->no_pegawai;
                $th[] = $row->jabatan;
                $th[] = $status;
                $th[] = $masa_kerja;
                //$th[] = $prorate;
                $th[] = $nilai;
                $th[] = $presentasi_label;
                $th[] = is_numeric($totalBonus) ? 'Rp ' . number_format($totalBonus, 0, ',', '.') : $totalBonus;
                $data[] = $th;
            }

            $dt['data'] = $data;
            echo json_encode($dt);
            die;
					
        
      }
    }

    public function edit($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_bonus->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }


    public function updateEvaluasi()
    {        
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));
        $data['id_pengguna']  = $this->input->post('id_pengguna', TRUE);
        $data['smt']          = $this->input->post('smt', TRUE);
        $data['periode']      = $this->input->post('periode', TRUE);
        $data['nilai']        = $this->input->post('nilai', TRUE);

        $this->md_bonus->updateEv($id, $data);

        /** LOG */
        addLog('Evaluasi', 'Update Evaluasi Semester');
        ajaxReturnDie('success', 'Evaluasi Berhasil Diperbaharui', TRUE);
	}














        public function print($param2 = ""){

            $this->load->library('pdfgenerator');
            $month = date("Y-m");$date = DateTime::createFromFormat("Y-m", $month); // Mengubah string ke objek DateTime
            $date->modify("-1 month"); // Mengurangi 1 bulan
            $month1 = $date->format("Y-m"); 
            $data = [
                //'dt' => $this->md_pengguna->getKaryawan(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator']),
                'dt' => $this->md_pengguna->getByWherenotIn(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator'], [58, 47,84,714,77,79,110,87,72,70,81,69,83,107,86,74,57,738,56,721,743,742]),
                'title_pdf' => 'Rekapitulasi Bonus Tahunan',
                'periode' => $param2,
                'month' => $month,
                'month1' => $month1,
                'bulan2' => date('m', strtotime($month)),
                'tahun2' => date('Y', strtotime($month))
            ];
            // echo_array($data['dt']);die;
            // filename dari pdf ketika didownload
            $file_pdf = 'Rekapitulasi Bonus Tahunan Periode ' . $data['periode'];
            // setting paper
            $paper = 'legal';
            //orientasi paper potrait / landscape
            $orientation = "landscape";
            $html = $this->load->view('pages/v_print/print_bonus_tahunan', $data, true);

            // run dompdf
            $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
        }





    
}