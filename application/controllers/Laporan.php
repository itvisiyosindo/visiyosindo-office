<?php
//abaikan error
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
//
use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{

    function id_navbar()
    {
        $id_navbar = "kepegawaian";
        return $id_navbar;
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']        = $this->id_navbar();
        $page_data['list_nama']     = $this->md_laporan->getBywhereActive();
        $page_data['page_name']     = 'laporan/v_lap';
        $page_data['page_title']    = 'Laporan Mingguan';
        $page_data['page_desc']     = 'Management Laporan Mingguan';
        $this->load->view('index', $page_data);
    }


    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_laporan');
        $this->load->model('md_tiket');
        $this->load->model('md_evaluasi');
        $this->load->model('md_pengguna');
        $this->load->model('md_surat_part_two');
        $this->load->model('md_surat_list');
        $this->load->model('md_prov_kota');
        $this->load->helper('email_helper');
        $this->load->helper('terbilang_helper');
        $this->load->helper('tanggal_helper');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('encrypt_helper');
    }

    public function show($param = "", $param2 = "", $param3 = "", $param4 = "0")
    {
        grantAccessFor('all');

        if ($param == 'detail') {
            if ($param2 == 'laporan_mingguan') {
                $id_pengaju = decrypt($param3);

                // Cek apakah $param4 valid, jika tidak, set 0
                $week_offset = (isset($param4) && is_numeric($param4)) ? (int)$param4 : 0;

                // Hitung Senin dan Jumat dalam minggu berdasarkan offset
                $monday = date('Y-m-d', strtotime("monday this week +$week_offset week"));
                $friday = date('Y-m-d', strtotime("friday this week +$week_offset week"));

                // Hitung minggu keberapa dalam bulan
                $week_number = ceil(date('d', strtotime($monday)) / 7);
                //$month_year = date('F Y', strtotime($monday));
                $month_year = $this->convertBulan(date('F Y', strtotime($monday)));


                // Ambil data laporan dalam rentang minggu ini
                $page_data['data_detail'] = $this->md_laporan->getLaporanPerMinggu($monday, $friday, $id_pengaju);

                // Navigasi minggu
                $page_data['week_offset'] = $week_offset;
                $page_data['week_number'] = $week_number;
                $page_data['month_year'] = $month_year;
                $page_data['id_pengaju'] = $param3;
                $page_data['switch'] = $this->id_navbar();
                $page_data['data_job'] = $this->md_laporan->getPengguna($id_pengaju);

                $page_data['page_name'] = 'laporan/v_lap_detail';
                $page_data['page_title'] = 'Laporan Mingguan';
                $page_data['page_desc'] = 'Detail Laporan Mingguan';

                $this->load->view('index', $page_data);
            } else if ($param2 == 'laporan') {
                $id_pengaju = decrypt($param3);

                // Cek apakah $param4 valid, jika tidak, set 0
                $week_offset = (isset($param4) && is_numeric($param4)) ? (int)$param4 : 0;

                // Dapatkan hari ini, lalu cari Senin pada minggu saat ini, lalu tambahkan offset minggu
                $monday = date('Y-m-d', strtotime("monday this week", strtotime("$week_offset week")));
                $friday = date('Y-m-d', strtotime("sunday this week", strtotime("$week_offset week")));

                $startDate = date('d-m-Y', strtotime("monday this week", strtotime("$week_offset week")));
                $endDate = date('d-m-Y', strtotime("sunday this week", strtotime("$week_offset week")));

                $page_data['data_detail'] = $this->md_laporan->getLaporanMingguan($monday, $friday, $id_pengaju);

                $page_data['switch'] = $this->id_navbar();
                // Navigasi minggu
                $page_data['week_offset'] = $week_offset;
                $page_data['startDate'] = $startDate;
                $page_data['endDate'] = $endDate;

                $page_data['id_pengaju'] = $param3;
                $page_data['switch'] = $this->id_navbar();
                $page_data['data_job'] = $this->md_laporan->getPengguna($id_pengaju);

                $page_data['page_name'] = 'laporan/v_lap_detail_new';
                $page_data['page_title'] = 'Laporan Mingguan';
                $page_data['page_desc'] = 'Detail Laporan Mingguan';

                $this->load->view('index', $page_data);
            } else if ($param2 == 'laporan_detail') {
                $id_pengaju = decrypt($param3);

                // Cek apakah $param4 valid, jika tidak, set 0
                $week_offset = (isset($param4) && is_numeric($param4)) ? (int)$param4 : 0;

                // Dapatkan hari ini, lalu cari Senin pada minggu saat ini, lalu tambahkan offset minggu
                $monday = date('Y-m-d', strtotime("monday this week", strtotime("$week_offset week")));
                $friday = date('Y-m-d', strtotime("sunday this week", strtotime("$week_offset week")));

                $startDate = date('d-m-Y', strtotime("monday this week", strtotime("$week_offset week")));
                $endDate = date('d-m-Y', strtotime("sunday this week", strtotime("$week_offset week")));

                $page_data['data_detail'] = $this->md_laporan->getLaporanMingguanRev1($monday, $friday, $id_pengaju);
                $page_data['data_pencapaian'] = $this->md_laporan->getPencapaianMingguanRev1($monday, $friday, $id_pengaju);

                $page_data['switch'] = $this->id_navbar();
                // Navigasi minggu
                $page_data['week_offset'] = $week_offset;
                $page_data['startDate'] = $startDate;
                $page_data['endDate'] = $endDate;

                $page_data['id_pengaju'] = $param3;
                $page_data['switch'] = $this->id_navbar();
                $page_data['data_job'] = $this->md_laporan->getPengguna($id_pengaju);

                $page_data['page_name'] = 'laporan/v_lap_detail_rev1';
                $page_data['page_title'] = 'Laporan Mingguan';
                $page_data['page_desc'] = 'Detail Laporan Mingguan';

                $this->load->view('index', $page_data);
            } else if ($param2 == 'laporan_detail_rev2') {
                $id_pengaju = decrypt($param3);

                // Cek apakah $param4 valid, jika tidak, set 0
                $week_offset = (isset($param4) && is_numeric($param4)) ? (int)$param4 : 0;

                // Dapatkan hari ini, lalu cari Senin pada minggu saat ini, lalu tambahkan offset minggu
                $monday = date('Y-m-d', strtotime("monday this week", strtotime("$week_offset week")));
                $friday = date('Y-m-d', strtotime("sunday this week", strtotime("$week_offset week")));

                $startDate = date('d-m-Y', strtotime("monday this week", strtotime("$week_offset week")));
                $endDate = date('d-m-Y', strtotime("sunday this week", strtotime("$week_offset week")));

                $page_data['data_detail'] = $this->md_laporan->getLaporanMingguanRev2($monday, $friday, $id_pengaju);

                // Ambil data nilai_point
                $this->db->where('id_pengguna', $id_pengaju);
                $this->db->where('date >=', $monday);
                $this->db->where('date <=', $friday);
                $nilai_points = $this->db->get('nilai_point')->result();

                // Susun array untuk akses cepat
                $page_data['nilai_point_map'] = [];
                foreach ($nilai_points as $np) {
                    $page_data['nilai_point_map'][$np->point] = $np;
                }
                $page_data['data_detail_pencapaian'] = $this->md_laporan->getPencapaianPerMingguRev2($monday, $friday, $id_pengaju);

                $page_data['switch'] = $this->id_navbar();


                //$minggu_lalu = -1; // minggu lalu
                $wednesday = date('Y-m-d', strtotime("wednesday this week $week_offset week"));
                $penilaian_umum     = $this->md_laporan->getPenilaianUmum($id_pengaju, $wednesday);
                $page_data['penilaian_umum'] = $penilaian_umum;


                // Navigasi minggu
                $page_data['week_offset'] = $week_offset;
                //untuk save ke nilai point
                $page_data['wednesday'] = $wednesday;
                $page_data['startDate'] = $startDate;
                $page_data['endDate'] = $endDate;

                $page_data['id_pengaju'] = $param3;
                $page_data['switch'] = $this->id_navbar();
                $page_data['data_job'] = $this->md_laporan->getPengguna($id_pengaju);

                $page_data['page_name'] = 'laporan/v_lap_detail_rev2';
                $page_data['page_title'] = 'Laporan Mingguan';
                $page_data['page_desc'] = 'Detail Laporan Mingguan';

                $this->load->view('index', $page_data);
            } else if ($param2 == 'my_detail_nilai') {
                $id_pengaju = decrypt($param3);

                // Cek apakah $param4 valid, jika tidak, set 0
                $week_offset = (isset($param4) && is_numeric($param4)) ? (int)$param4 : 0;

                // Dapatkan hari ini, lalu cari Senin pada minggu saat ini, lalu tambahkan offset minggu
                $monday = date('Y-m-d', strtotime("monday this week", strtotime("$week_offset week")));
                $friday = date('Y-m-d', strtotime("sunday this week", strtotime("$week_offset week")));

                $startDate = date('d-m-Y', strtotime("monday this week", strtotime("$week_offset week")));
                $endDate = date('d-m-Y', strtotime("sunday this week", strtotime("$week_offset week")));

                $page_data['data_detail'] = $this->md_laporan->getLaporanMingguanRev1($monday, $friday, $id_pengaju);
                $page_data['data_pencapaian'] = $this->md_laporan->getPencapaianMingguanRev1($monday, $friday, $id_pengaju);

                $page_data['switch'] = $this->id_navbar();
                // Navigasi minggu
                $page_data['week_offset'] = $week_offset;
                $page_data['startDate'] = $startDate;
                $page_data['endDate'] = $endDate;

                $page_data['id_pengaju'] = $param3;
                $page_data['switch'] = $this->id_navbar();
                $page_data['data_job'] = $this->md_laporan->getPengguna($id_pengaju);

                $page_data['page_name'] = 'laporan/v_my_detail_nilai';
                $page_data['page_title'] = 'Laporan Mingguan';
                $page_data['page_desc'] = 'Detail Laporan Mingguan';

                $this->load->view('index', $page_data);
            } else if ($param2 == 'my_detail_nilai_rev2') {
                $id_pengaju = decrypt($param3);

                // Cek apakah $param4 valid, jika tidak, set 0
                $week_offset = (isset($param4) && is_numeric($param4)) ? (int)$param4 : 0;

                // Dapatkan hari ini, lalu cari Senin pada minggu saat ini, lalu tambahkan offset minggu
                $monday = date('Y-m-d', strtotime("monday this week", strtotime("$week_offset week")));
                $friday = date('Y-m-d', strtotime("sunday this week", strtotime("$week_offset week")));

                $startDate = date('d-m-Y', strtotime("monday this week", strtotime("$week_offset week")));
                $endDate = date('d-m-Y', strtotime("sunday this week", strtotime("$week_offset week")));

                $page_data['data_detail'] = $this->md_laporan->getLaporanMingguanRev2($monday, $friday, $id_pengaju);

                // Ambil data nilai_point
                $this->db->where('id_pengguna', $id_pengaju);
                $this->db->where('date >=', $monday);
                $this->db->where('date <=', $friday);
                $nilai_points = $this->db->get('nilai_point')->result();

                // Susun array untuk akses cepat
                $page_data['nilai_point_map'] = [];
                foreach ($nilai_points as $np) {
                    $page_data['nilai_point_map'][$np->point] = $np;
                }
                $page_data['data_detail_pencapaian'] = $this->md_laporan->getPencapaianPerMingguRev2($monday, $friday, $id_pengaju);

                $page_data['switch'] = $this->id_navbar();


                //$minggu_lalu = -1; // minggu lalu
                $wednesday = date('Y-m-d', strtotime("wednesday this week $week_offset week"));
                $penilaian_umum     = $this->md_laporan->getPenilaianUmum($id_pengaju, $wednesday);
                $page_data['penilaian_umum'] = $penilaian_umum;


                // Navigasi minggu
                $page_data['week_offset'] = $week_offset;
                //untuk save ke nilai point
                $page_data['wednesday'] = $wednesday;
                $page_data['startDate'] = $startDate;
                $page_data['endDate'] = $endDate;

                $page_data['id_pengaju'] = $param3;
                $page_data['switch'] = $this->id_navbar();
                $page_data['data_job'] = $this->md_laporan->getPengguna($id_pengaju);

                $page_data['page_name'] = 'laporan/v_my_detail_nilai_rev2';
                $page_data['page_title'] = 'Laporan Mingguan';
                $page_data['page_desc'] = 'Detail Laporan Mingguan';

                $this->load->view('index', $page_data);
            }
        } else if ($param == 'list') {
            if ($param2 == 'my_data') {
                /*$page_data['switch'] = $this->id_navbar();
                $page_data['list_nama'] = $this->md_laporan->getBywhereActive();
                $page_data['list_job'] = $this->md_laporan->getByJobs(sessPenggunaId());
                $page_data['page_name'] = 'laporan/v_my_lap';
                $page_data['page_title'] = 'Laporan Mingguan';
                $page_data['page_desc'] = 'Management Laporan Mingguan';

                $this->load->view('index', $page_data);*/
                $id_pengaju = sessPenggunaId();

                // Cek apakah $param4 valid, jika tidak, set 0
                $week_offset = (isset($param3) && is_numeric($param3)) ? (int)$param3 : 0;

                // Hitung Senin dan Jumat dalam minggu berdasarkan offset
                $monday = date('Y-m-d', strtotime("monday this week +$week_offset week"));
                $friday = date('Y-m-d', strtotime("friday this week +$week_offset week"));

                // Hitung minggu keberapa dalam bulan
                $week_number = ceil(date('d', strtotime($monday)) / 7);
                //$month_year = date('F Y', strtotime($monday));
                $month_year = $this->convertBulan(date('F Y', strtotime($monday)));


                // Ambil data laporan dalam rentang minggu ini
                $page_data['data_detail'] = $this->md_laporan->getLaporanPerMinggu($monday, $friday, $id_pengaju);

                // Navigasi minggu
                $page_data['week_offset'] = $week_offset;
                $page_data['week_number'] = $week_number;
                $page_data['month_year'] = $month_year;
                $page_data['switch'] = $this->id_navbar();

                $page_data['list_nama'] = $this->md_laporan->getBywhereActive();
                $page_data['list_job'] = $this->md_laporan->getByJobs(sessPenggunaId());
                $page_data['page_name'] = 'laporan/v_my_lap';
                $page_data['page_title'] = 'Laporan Mingguan';
                $page_data['page_desc'] = 'Detail Laporan Mingguan';

                $this->load->view('index', $page_data);
            } else if ($param2 == 'my') {
                $id_pengaju = sessPenggunaId();

                $week_offset = (isset($param3) && is_numeric($param3)) ? (int)$param3 : 0;

                // Dapatkan hari ini, lalu cari Senin pada minggu saat ini, lalu tambahkan offset minggu
                $monday = date('Y-m-d', strtotime("monday this week", strtotime("$week_offset week")));
                $friday = date('Y-m-d', strtotime("sunday this week", strtotime("$week_offset week")));

                $startDate = date('d-m-Y', strtotime("monday this week", strtotime("$week_offset week")));
                $endDate = date('d-m-Y', strtotime("sunday this week", strtotime("$week_offset week")));

                $page_data['data_detail'] = $this->md_laporan->getLaporanMingguan($monday, $friday, $id_pengaju);

                $page_data['switch'] = $this->id_navbar();
                // Navigasi minggu
                $page_data['week_offset'] = $week_offset;
                $page_data['startDate'] = $startDate;
                $page_data['endDate'] = $endDate;

                $page_data['list_nama'] = $this->md_laporan->getBywhereActive();
                $page_data['list_job'] = $this->md_laporan->getByJobs(sessPenggunaId());
                $page_data['page_name'] = 'laporan/v_my_lap_new';
                $page_data['page_title'] = 'Laporan Mingguan';
                $page_data['page_desc'] = 'Detail Laporan Mingguan';

                $this->load->view('index', $page_data);
            } else if ($param2 == 'mydata') {
                $id_pengaju = sessPenggunaId();

                $week_offset = (isset($param3) && is_numeric($param3)) ? (int)$param3 : 0;

                // Dapatkan hari ini, lalu cari Senin pada minggu saat ini, lalu tambahkan offset minggu
                $monday = date('Y-m-d', strtotime("monday this week", strtotime("$week_offset week")));
                $friday = date('Y-m-d', strtotime("sunday this week", strtotime("$week_offset week")));

                $startDate = date('d-m-Y', strtotime("monday this week", strtotime("$week_offset week")));
                $endDate = date('d-m-Y', strtotime("sunday this week", strtotime("$week_offset week")));

                $page_data['data_detail'] = $this->md_laporan->getLaporanMingguanRev1($monday, $friday, $id_pengaju);
                $page_data['data_pencapaian'] = $this->md_laporan->getPencapaianMingguanRev1($monday, $friday, $id_pengaju);

                $page_data['switch'] = $this->id_navbar();
                // Navigasi minggu
                $page_data['week_offset'] = $week_offset;
                $page_data['startDate'] = $startDate;
                $page_data['endDate'] = $endDate;


                $page_data['list_nama'] = $this->md_laporan->getBywhereActive();
                $page_data['list_job'] = $this->md_laporan->getByJobs(sessPenggunaId());
                $page_data['page_name'] = 'laporan/v_my_lap_rev1';
                $page_data['page_title'] = 'Laporan Mingguan';
                $page_data['page_desc'] = 'Detail Laporan Mingguan';

                $this->load->view('index', $page_data);
            } else if ($param2 == 'rev2') {
                $id_pengaju = sessPenggunaId();

                $week_offset = (isset($param3) && is_numeric($param3)) ? (int)$param3 : 0;

                // Dapatkan hari ini, lalu cari Senin pada minggu saat ini, lalu tambahkan offset minggu
                // $monday = date('Y-m-d', strtotime("monday this week", strtotime("$week_offset week")));
                // $friday = date('Y-m-d', strtotime("sunday this week", strtotime("$week_offset week")));

                // $startDate = date('d-m-Y', strtotime("monday this week", strtotime("$week_offset week")));
                // $endDate = date('d-m-Y', strtotime("sunday this week", strtotime("$week_offset week")));
                
                $monday = date('Y-m-d', strtotime("monday this week $week_offset week"));
                $friday = date('Y-m-d', strtotime("sunday this week $week_offset week"));
                
                $startDate = date('d-m-Y', strtotime("monday this week $week_offset week"));
                $endDate = date('d-m-Y', strtotime("sunday this week $week_offset week"));

                // Ambil data nilai_point
                $this->db->where('id_pengguna', $id_pengaju);
                $this->db->where('date >=', $monday);
                $this->db->where('date <=', $friday);
                $nilai_points = $this->db->get('nilai_point')->result();

                // Susun array untuk akses cepat
                $page_data['nilai_point_map'] = [];
                foreach ($nilai_points as $np) {
                    $page_data['nilai_point_map'][$np->point] = $np;
                }

                // $page_data['data_detail'] = $this->md_laporan->getLaporanMingguanRev2($monday, $friday, $id_pengaju);
                
                $page_data['data_detail'] = $this->md_laporan->getLaporanMingguanRev2($monday, $friday, $id_pengaju);
                if (empty($page_data['data_detail'])) {
                    $page_data['data_detail'] = $this->md_laporan->getLaporanPerMinggu($monday, $friday, $id_pengaju);
                }
                $page_data['data_detail_pencapaian'] = $this->md_laporan->getPencapaianPerMingguRev2($monday, $friday, $id_pengaju);

                //$minggu_lalu = -1; // minggu lalu
                $wednesday = date('Y-m-d', strtotime("wednesday this week $week_offset week"));
                $penilaian_umum     = $this->md_laporan->getPenilaianUmum($id_pengaju, $wednesday);
                $page_data['penilaian_umum'] = $penilaian_umum;


                $page_data['switch'] = $this->id_navbar();

                // Navigasi minggu
                $page_data['week_offset'] = $week_offset;
                $page_data['startDate'] = $startDate;
                $page_data['endDate'] = $endDate;



                $page_data['list_nama'] = $this->md_laporan->getBywhereActive();
                $page_data['list_job'] = $this->md_laporan->getByJobs(sessPenggunaId());
                $page_data['page_name'] = 'laporan/v_my_lap_rev2';
                $page_data['page_title'] = 'Laporan Mingguan';
                $page_data['page_desc'] = 'Detail Laporan Mingguan';

                $this->load->view('index', $page_data);
            } else if ($param2 == 'all') {
                $page_data['switch']          = $this->id_navbar();
                $page_data['list_nama']     = $this->md_laporan->getAllPenggunaAktif();
                $page_data['page_name']     = 'laporan/v_lap_new';
                $page_data['page_title']    = 'Laporan Mingguan';
                $page_data['page_desc']     = 'Management Laporan Mingguan';
                $this->load->view('index', $page_data);
            } else if ($param2 == 'all_data') {
                $page_data['switch']          = $this->id_navbar();
                //$page_data['list_nama']     = $this->md_laporan->getAllPenggunaAktif();
                $page_data['list_nama'] = $this->md_laporan->getBywhereActive();
                $page_data['page_name']     = 'laporan/v_lap_rev1';
                $page_data['page_title']    = 'Laporan Mingguan';
                $page_data['page_desc']     = 'Management Laporan Mingguan';
                $this->load->view('index', $page_data);
            } else if ($param2 == 'all_rev2') {
                $page_data['switch']          = $this->id_navbar();
                //$page_data['list_nama']     = $this->md_laporan->getAllPenggunaAktif();
                $page_data['list_nama'] = $this->md_laporan->getBywhereActive();
                $page_data['page_name']     = 'laporan/v_lap_rev2';
                $page_data['page_title']    = 'Laporan Mingguan';
                $page_data['page_desc']     = 'Management Laporan Mingguan';
                $this->load->view('index', $page_data);
            } else if ($param2 == 'nilai') {

                $week_offset = (isset($param3) && is_numeric($param3)) ? (int)$param3 : 0;

                // Format tanggal harus Y-m-d agar sesuai dengan database
                $monday = date('Y-m-d', strtotime("monday this week", strtotime("$week_offset week")));
                $friday = date('Y-m-d', strtotime("friday this week", strtotime("$week_offset week")));

                $page_data['switch'] = $this->id_navbar();
                $page_data['week_offset'] = $week_offset;
                $page_data['startDate'] = $monday; // Format sudah Y-m-d
                $page_data['endDate'] = $friday; // Format sudah Y-m-d

                $page_data['data_detail'] = $this->md_laporan->getAllNilai($monday, $friday);

                $page_data['page_name'] = 'laporan/v_lap_nilai';
                $page_data['page_title'] = 'Laporan Mingguan';
                $page_data['page_desc'] = 'Management Laporan Mingguan';
                $this->load->view('index', $page_data);
            } else if ($param2 == 'mynilai') {
                $page_data['switch']          = $this->id_navbar();
                $page_data['page_name']     = 'laporan/v_my_lap_nilai';
                $page_data['page_title']    = 'Laporan Mingguan';
                $page_data['page_desc']     = 'Management Data Laporan Mingguan';
                $this->load->view('index', $page_data);
            } else if ($param2 == 'my_nilai_rev2') {
                $page_data['switch']          = $this->id_navbar();
                $page_data['page_name']     = 'laporan/v_my_lap_nilai_rev2';
                $page_data['page_title']    = 'Laporan Mingguan';
                $page_data['page_desc']     = 'Management Data Laporan Mingguan';
                $this->load->view('index', $page_data);
            }
        }
    }

    private function convertBulan($date)
    {
        $bulanInggris = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        foreach ($bulanInggris as $en => $id) {
            if (strpos($date, $en) !== false) {
                return str_replace($en, $id, $date);
            }
        }

        return $date;
    }




    //ADD
    public function add()
    {
        grantAccessFor('all');

        $data['id_pengaju']        = sessPenggunaId();
        $data['tanggal']           = date_db_format($this->input->post('tanggal', TRUE));
        //$data['jobdesc']           = $this->input->post('jobdesc', TRUE);
        $lainnya                   = $this->input->post('jobdesc', TRUE);
        if ($lainnya == "0") {
            // Jika opsi "Lainnya" dipilih, ambil nilai dari input teks "keterangan_konfirmasi"
            $data['id_job']        = 0;
            $data['jobdesc']       = $this->input->post('jobdesc_text'); //$this->input->post('keterangan_konfirmasi', TRUE);
        } else {
            // Jika opsi selain "Lainnya" dipilih, ambil nilai dari dropdown "Jobdesc"
            $data['id_job']        = $lainnya;
            $data['jobdesc']       = $this->input->post('jobdesc_text');
        }
        $data['jenis']             = $this->input->post('jenis', TRUE) ?: 'JOBDESK RUTIN';
        $data['kendala']           = $this->input->post('kendala', TRUE) ?: 'Tidak Ada';
        $data['solusi']            = $this->input->post('solusi', TRUE);
        $data['progress']          = $this->input->post('progress', TRUE);
        $data['status_pekerjaan']  = $this->input->post('status_pekerjaan', TRUE) ?: 'SELESAI';
        //$data['link']              = $this->input->post('link', TRUE);
        $hasil                   = $this->input->post('hasil', TRUE);
        if ($hasil == "1") {
            $data['link']          = $this->input->post('link', TRUE);
        } else if ($hasil == "2") {
            $data['ket_hasil']     = $this->input->post('ket_hasil');
        }
        $data['pihak']             = $this->input->post('pihak', TRUE);
        $data['keterangan']        = $this->input->post('keterangan', TRUE);
        $this->md_laporan->add($data);


        /** LOG */
        addLog('Laporan', 'Menambah Laporan Mingguan');
        ajaxReturnDie('success', 'Berhasil Disimpan', TRUE);
    }

    //Add Per Jobdesc
    public function add2()
    {
        grantAccessFor('all');

        $data['id_pengaju']        = sessPenggunaId();
        $data['tanggal']           = date_db_format($this->input->post('tanggal', TRUE));
        $data['id_job']            = $this->input->post('jobdesc');
        $data['jenis']             = $this->input->post('jenis', TRUE) ?: 'JOBDESK RUTIN';
        $data['kendala']           = $this->input->post('kendala', TRUE) ?: 'Tidak Ada';
        $data['solusi']            = $this->input->post('solusi', TRUE);
        $data['progress']          = $this->input->post('progress', TRUE);
        $data['status_pekerjaan']  = $this->input->post('status_pekerjaan', TRUE) ?: 'SELESAI';
        //$data['link']              = $this->input->post('link', TRUE);
        $lainnya                   = $this->input->post('hasil2', TRUE);
        if ($lainnya == "1") {
            $data['link']          = $this->input->post('link', TRUE);
        } else if ($lainnya == "2") {
            $data['ket_hasil']     = $this->input->post('ket_hasil');
        }
        $data['pihak']             = $this->input->post('pihak2', TRUE);
        $data['keterangan']        = $this->input->post('keterangan', TRUE);
        $this->md_laporan->add($data);


        /** LOG */
        addLog('Laporan', 'Menambah Laporan Mingguan');
        ajaxReturnDie('success', 'Berhasil Disimpan', TRUE);
    }


    public function addRev1()
    {
        grantAccessFor('all');

        $data['id_pengaju']        = sessPenggunaId();
        //$data['tanggal']	       = date_db_format($this->input->post('tanggal', TRUE));
        $tgl_input                 = $this->input->post('tanggal', TRUE);
        $data['tanggal']           = $tgl_input ? date_db_format($tgl_input) : date('Y-m-d');
        $data['id_job']            = $this->input->post('jobdesc');
        $data['jenis']             = $this->input->post('jenis', TRUE) ?: 'JOBDESK RUTIN';
        $data['progress']          = $this->input->post('progress', TRUE);
        $data['status_pekerjaan']  = $this->input->post('status_pekerjaan', TRUE) ?: 'SELESAI';
        //$data['link']              = $this->input->post('link', TRUE);
        $lainnya                   = $this->input->post('hasil2', TRUE);
        if ($lainnya == "1") {
            $data['link']          = $this->input->post('link', TRUE);
        } else if ($lainnya == "2") {
            $data['ket_hasil']     = $this->input->post('ket_hasil');
        }
        $data['pihak']             = $this->input->post('pihak2', TRUE);
        $data['keterangan']        = $this->input->post('keterangan', TRUE);
        $data['pencapaian']        = $this->input->post('pencapaian', TRUE) ?: '1';
        $this->md_laporan->add($data);


        /** LOG */
        addLog('Laporan', 'Menambah Laporan Mingguan');
        ajaxReturnDie('success', 'Berhasil Disimpan', TRUE);
    }


    //Add Per Jobdesc with Pencapaian
    public function addRev2()
    {
        grantAccessFor('all');


        $week_offset = $this->input->post('week_offset7', TRUE);
        $week_offset = $week_offset !== null && $week_offset !== '' ? (int)$week_offset : 0;

        $monday = date('Y-m-d', strtotime("monday this week", strtotime("$week_offset week")));
        $friday = date('Y-m-d', strtotime("sunday this week", strtotime("$week_offset week")));

        $data['id_pengaju']        = sessPenggunaId();
        //$data['tanggal']	       = date_db_format($this->input->post('tanggal', TRUE));
        $tgl_input                 = $this->input->post('tanggal', TRUE);
        $data['tanggal']           = $tgl_input ? date_db_format($tgl_input) : date('Y-m-d');
        $data['id_job']            = $this->input->post('jobdesc');
        $data['jenis']             = $this->input->post('jenis', TRUE) ?: 'JOBDESK RUTIN';
        $data['progress']          = $this->input->post('progress', TRUE);
        $data['status_pekerjaan']  = $this->input->post('status_pekerjaan', TRUE) ?: 'SELESAI';
        //$data['link']              = $this->input->post('link', TRUE);
        $lainnya                   = $this->input->post('hasil2', TRUE);
        if ($lainnya == "1") {
            $data['link']          = $this->input->post('link', TRUE);
        } else if ($lainnya == "2") {
            $data['ket_hasil']     = $this->input->post('ket_hasil');
        }
        $data['pihak']             = $this->input->post('pihak2', TRUE);
        $data['keterangan']        = $this->input->post('keterangan', TRUE);
        $data['pencapaian']        = $this->input->post('pencapaian', TRUE) ?: '1';

        //Validasi Agar Tidak ada lagi yang submit diluar PRIODE
        if (strtotime($data['tanggal']) < strtotime($monday) || strtotime($data['tanggal']) > strtotime($friday)) {
            // Jika user klik "Lanjutkan Simpan", akan ada input force_save=1
            if ($this->input->post('force_save') != '1') {
                $monday_disp = date('d-m-Y', strtotime($monday));
                $friday_disp = date('d-m-Y', strtotime($friday));

                ajaxReturnDie('confirm', "Tanggal yang Anda pilih berada di luar periode laporan mingguan ({$monday_disp} s/d {$friday_disp}). Apakah Anda ingin melanjutkan simpan?", FALSE);
            }
        }


        $this->md_laporan->add($data);


        /** LOG */
        addLog('Laporan', 'Menambah Laporan Mingguan');
        ajaxReturnDie('success', 'Berhasil Disimpan', TRUE);
    }

    public function addPencapaian()
    {
        grantAccessFor('all');

        $data['id_pengguna']        = sessPenggunaId();
        $tgl_input                 = $this->input->post('tanggal', TRUE);
        $data['tanggal']           = $tgl_input ? date_db_format($tgl_input) : date('Y-m-d');
        $data['detail']            = $this->input->post('detail');
        $lainnya                   = $this->input->post('hasil3', TRUE);
        if ($lainnya == "1") {
            $data['link']          = $this->input->post('link', TRUE);
        } else if ($lainnya == "2") {
            $data['keterangan']     = $this->input->post('keterangan');
        }
        $this->md_laporan->addPencapaian($data);


        /** LOG */
        addLog('Pencapaian', 'Menambah Pencapaian Mingguan');
        ajaxReturnDie('success', 'Berhasil Disimpan', TRUE);
    }


    public function edit($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_laporan->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    public function editPencapaian($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_laporan->getByIdPencapaian($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    public function editPenilai($param1 = "")
    {
        grantAccessFor('all');

        if (empty($param1) || $param1 == 'undefined') {
            echo json_encode([]);
            die;
        }

        $id = decrypt($param1);

        // Validasi tambahan jika decrypt gagal
        if (!$id) {
            echo json_encode([]);
            die;
        }

        $dt = $this->md_laporan->getByIdPenilai($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    public function updatePenilai()
    {
        grantAccessFor('all');

        // 1. Ambil Data
        // id_pelanggan adalah ID Transaksi (Laporan Penilai ID)
        $id_enkripsi    = $this->input->post('id_pelanggan');

        // id_pengguna_hidden adalah ID Pegawai yang sedang diedit (WAJIB ADA)
        $id_pengguna    = $this->input->post('id_pengguna_hidden', TRUE);

        $penilai_b      = $this->input->post('penilai_b', TRUE);

        // 2. Validasi
        if (empty($penilai_b)) {
            ajaxReturnDie('error', 'Nama Penilai wajib dipilih!', FALSE);
        }
        if (empty($id_pengguna)) {
            ajaxReturnDie('error', 'ID Pegawai tidak terdeteksi. Silakan refresh halaman dan coba lagi.', FALSE);
        }

        // 3. Default Penilai A = 69
        $penilai_a_default = 69;

        // 4. Cek Logika: Apakah pegawai ini SUDAH punya settingan penilai?
        // Kita cek berdasarkan id_pengguna, bukan berdasarkan id transaksi, untuk menghindari duplikat
        $existing_data = $this->db->get_where('laporan_penilai', ['id_pengguna' => $id_pengguna])->row();

        if ($existing_data) {
            // === KASUS UPDATE ===
            // Jika data settingan sudah ada untuk pegawai ini, kita UPDATE baris tersebut
            $data_update = [
                'penilai_a' => $penilai_a_default,
                'penilai_b' => $penilai_b
            ];

            $this->db->where('id', $existing_data->id);
            $this->db->update('laporan_penilai', $data_update);
            $aksi = 'Memperbarui';
        } else {
            // === KASUS INSERT ===
            // Jika belum ada, kita INSERT baru
            $data_insert = [
                'id_pengguna' => $id_pengguna,
                'penilai_a'   => $penilai_a_default,
                'penilai_b'   => $penilai_b
            ];

            $this->db->insert('laporan_penilai', $data_insert);
            $aksi = 'Menambah Baru';
        }

        // 5. Cek Error Database
        if ($this->db->error()['code']) {
            ajaxReturnDie('error', 'Database Error: ' . $this->db->error()['message'], FALSE);
        }

        /** LOG & RESPONSE */
        addLog('Update Penilai', "$aksi Penilai untuk Pegawai ID: $id_pengguna");

        ajaxReturnDie('success', 'Data berhasil disimpan.', TRUE);
    }


    public function edit2($param1)
    {
        grantAccessFor('all');
        $id = $param1;
        $dt = $this->md_laporan->getByIdJob($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    public function update()
    {
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));
        $data['tanggal']           = date_db_format($this->input->post('tanggal', TRUE));
        //$data['jobdesc']           = $this->input->post('jobdesc', TRUE);
        $lainnya                   = $this->input->post('jobdesc', TRUE);
        if ($lainnya == "0") {
            // Jika opsi "Lainnya" dipilih, ambil nilai dari input teks "keterangan_konfirmasi"
            $data['id_job']        = 0;
            $data['jobdesc']       = $this->input->post('jobdesc_text'); //$this->input->post('keterangan_konfirmasi', TRUE);
        } else {
            // Jika opsi selain "Lainnya" dipilih, ambil nilai dari dropdown "Jobdesc"
            $data['id_job']        = $lainnya;
            $data['jobdesc']       = $this->input->post('jobdesc_text');
        }
        $data['jenis']             = $this->input->post('jenis', TRUE);
        $data['pencapaian']            = $this->input->post('pencapaian', TRUE);
        $data['progress']          = $this->input->post('progress', TRUE);
        $data['status_pekerjaan']  = $this->input->post('status_pekerjaan', TRUE);
        //$data['link']              = $this->input->post('link', TRUE);
        $hasil                   = $this->input->post('hasil', TRUE);
        if ($hasil == "1") {
            $data['link']          = $this->input->post('link', TRUE);
        } else if ($hasil == "2") {
            $data['ket_hasil']     = $this->input->post('ket_hasil');
        }
        $data['pihak']             = $this->input->post('pihak', TRUE);
        $data['keterangan']        = $this->input->post('keterangan', TRUE);

        $this->md_laporan->update($id, $data);

        /** LOG */
        addLog('Update Laporan', 'Memperbarui Laporan Mingguan');
        ajaxReturnDie('success', 'Data berhasil diperbarui', TRUE);
    }


    public function updatePencapaian()
    {
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));
        $data['tanggal']            = date_db_format($this->input->post('tanggal', TRUE));

        $data['detail']             = $this->input->post('detail', TRUE);
        $hasil                      = $this->input->post('hasil3', TRUE);
        if ($hasil == "1") {
            $data['link']           = $this->input->post('link', TRUE);
        } else if ($hasil == "2") {
            $data['keterangan']     = $this->input->post('keterangan');
        }

        $this->md_laporan->updatePencapaian($id, $data);

        /** LOG */
        addLog('Update Pencapaian', 'Memperbarui Pencapaian Mingguan');
        ajaxReturnDie('success', 'Data berhasil diperbarui', TRUE);
    }

    public function update_nilai1()
    {
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));

        $data['nilai_a1']    = $this->input->post('nilai_1', TRUE);
        $data['nilai_a2']    = $this->input->post('nilai_2', TRUE);
        $data['nilai_a3']    = $this->input->post('nilai_3', TRUE);
        $data['nilai_a4']    = $this->input->post('nilai_4', TRUE);
        $data['nilai_a5']    = $this->input->post('nilai_5', TRUE);
        $nilai_a             = ($data['nilai_a1'] + $data['nilai_a2'] + $data['nilai_a3'] + $data['nilai_a4'] + $data['nilai_a5']) / 5;
        $data['nilai']       = $nilai_a;


        $this->md_laporan->update($id, $data);

        /** LOG */
        addLog('Update Laporan', 'Mengisi Nilai Laporan A "' . $data['nilai'] . '"');
        ajaxReturnDie('success', 'Data berhasil diperbarui', TRUE);
    }

    public function update_nilai2()
    {
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));

        $data['nilai_b1']    = $this->input->post('nilai_b1', TRUE);
        $data['nilai_b2']    = $this->input->post('nilai_b2', TRUE);
        $data['nilai_b3']    = $this->input->post('nilai_b3', TRUE);
        $data['nilai_b4']    = $this->input->post('nilai_b4', TRUE);
        $data['nilai_b5']    = $this->input->post('nilai_b5', TRUE);
        $nilai_b             = ($data['nilai_b1'] + $data['nilai_b2'] + $data['nilai_b3'] + $data['nilai_b4'] + $data['nilai_b5']) / 5;
        $data['nilai_b']     = $nilai_b;


        $this->md_laporan->update($id, $data);

        /** LOG */
        addLog('Update Laporan', 'Mengisi Nilai Laporan B "' . $data['nilai_b'] . '"');
        ajaxReturnDie('success', 'Data berhasil diperbarui', TRUE);
    }

    public function update_nilai3()
    {
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));

        $data['nilai_pa1']    = $this->input->post('nilai_1', TRUE);
        $data['nilai_pa2']    = $this->input->post('nilai_2', TRUE);
        $data['nilai_pa3']    = $this->input->post('nilai_3', TRUE);
        $data['nilai_pa4']    = $this->input->post('nilai_4', TRUE);
        $data['nilai_pa5']    = $this->input->post('nilai_5', TRUE);
        $nilai_pencapaian_a             = ($data['nilai_pa1'] + $data['nilai_pa2'] + $data['nilai_pa3'] + $data['nilai_pa4'] + $data['nilai_pa5']) / 5;
        $data['nilai_pencapaian_a']     = $nilai_pencapaian_a;


        $this->md_laporan->update($id, $data);

        /** LOG */
        addLog('Update Laporan', 'Mengisi Nilai Pencapaian A "' . $data['nilai_pencapaian_a'] . '"');
        ajaxReturnDie('success', 'Data berhasil diperbarui', TRUE);
    }

    public function update_nilai4()
    {
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));

        $data['nilai_pb1']    = $this->input->post('nilai_1', TRUE);
        $data['nilai_pb2']    = $this->input->post('nilai_2', TRUE);
        $data['nilai_pb3']    = $this->input->post('nilai_3', TRUE);
        $data['nilai_pb4']    = $this->input->post('nilai_4', TRUE);
        $data['nilai_pb5']    = $this->input->post('nilai_5', TRUE);
        $nilai_pencapaian_b             = ($data['nilai_pb1'] + $data['nilai_pb2'] + $data['nilai_pb3'] + $data['nilai_pb4'] + $data['nilai_pb5']) / 5;
        $data['nilai_pencapaian_b']     = $nilai_pencapaian_b;


        $this->md_laporan->update($id, $data);

        /** LOG */
        addLog('Update Laporan', 'Mengisi Nilai Pencapaian B "' . $data['nilai_pencapaian_b'] . '"');
        ajaxReturnDie('success', 'Data berhasil diperbarui', TRUE);
    }

    public function update_catatan1()
    {
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));

        $data['kendala']    = $this->input->post('catatan', TRUE);


        $this->md_laporan->update($id, $data);

        /** LOG */
        addLog('Update Laporan', 'Mengisi Catatan A "' . $data['kendala'] . '"');
        ajaxReturnDie('success', 'Data berhasil diperbarui', TRUE);
    }

    public function update_catatan2()
    {
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));

        $data['solusi']    = $this->input->post('catatan', TRUE);


        $this->md_laporan->update($id, $data);

        /** LOG */
        addLog('Update Laporan', 'Mengisi Catatan B "' . $data['solusi'] . '"');
        ajaxReturnDie('success', 'Data berhasil diperbarui', TRUE);
    }

    //======= Send Notif ========
    public function sendNotif()
    {
        grantAccessFor('all');
        // Ambil semua penilai_b 
        $penilaiList = $this->md_laporan->getPenilaiLaporan();

        foreach ($penilaiList as $penilai) {
            $penilai_b      = $penilai->penilai_b;
            $id_pengguna    = $penilai->id_pengguna;
            $ambilData      = $this->md_pengguna->getById($penilai_b);

            if (!empty($ambilData)) {
                $namaTerima = $ambilData[0]->nama;
                $idPenerima = $ambilData[0]->pengguna_id;


                // Kirim notifikasi WA
                $dataWa = [
                    'id'           => $id_pengguna,
                    'idPenerima1' => $idPenerima,
                    'idPenerima2' => '',
                    'namaSurat'   => 'Laporan Mingguan',
                    'penerima'    => $namaTerima
                ];

                $this->notifWaAddSurat(1, $dataWa);
            }
        }

        /** LOG */
        addLog('Laporan Mingguan', 'Send Notif');
        ajaxReturnDie('success', 'Berhasil', TRUE);
    }


    public function notifWaAddSurat($ulang, $detail)
    {
        //ambil data pengaju
        $ambilDataPengaju     = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju        = $ambilDataPengaju[0]->nama;


        $id_po         = encrypt($detail['id']);
        $ambilDataEv   = $this->md_pengguna->getById($detail['id']);
        $namaPegawai   = $ambilDataEv[0]->nama;

        $startDate = date('d-m-Y', strtotime("monday last week"));
        $endDate   = date('d-m-Y', strtotime("sunday last week"));


        for ($i = 1; $i <= $ulang; $i++) {
            if ($i == 1) {
                $idpenerima = $detail['idPenerima1'];
            } else if ($i == 2) {
                $idpenerima = $detail['idPenerima2'];
            }

            $dataPenerima     = $this->md_pengguna->getById($idpenerima);
            //abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);
            //

            $nope           = $dataPenerima[0]->no_hp;
            $dataWa = [
                'namaSurat'     => urlencode($detail['namaSurat']),
                'noPenerima'     => $nope,
                'namaPegawai'     => urlencode($namaPegawai),
                'namaPengaju'   => urlencode($namaPengaju),
                'link'             => urlencode($id_po),
                'startDate'     => urlencode($startDate),
                'endDate'       => urlencode($endDate),
                'namaPenerima'     => urlencode($detail['penerima'])
            ];
            waLaporan($dataWa);
        }
    }


    //======= Send Notif ========


    //===== Selesai Nilai ======
    public function endPenilaian()
    {
        grantAccessFor('all');

        $id = $this->input->post('id');

        // Kirim notifikasi WA
        $dataWa = [
            'id'           => $id,
            'idPenerima1' => '69',
            'idPenerima2' => '58',
            'idPenerima3' => '',
            'namaSurat'   => 'Laporan Mingguan' //,
            //'penerima'    => '_HR_'
            //'namaPegawai' => $namaPegawai,
            //'smt'         => 'Evaluasi Pegawai',
            //'tahun'       => 'Evaluasi Pegawai'
        ];

        $this->notifWaSimpanSurat(2, $dataWa);

        $ambilDataPengaju     = $this->md_pengguna->getById($id);
        $namaPengaju        = $ambilDataPengaju[0]->nama;




        /** LOG */
        addLog('Laporan Mingguan', 'Selesaikan Penilaian : ' . $$namaPengaju);
        ajaxReturnDie('success', 'Berhasil', TRUE);
    }

    public function notifWaSimpanSurat($ulang, $detail)
    {
        //ambil data pengaju
        $ambilDataPengaju     = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju        = $ambilDataPengaju[0]->nama;


        $id_po         = encrypt($detail['id']);


        $dataPenerima3     = $this->md_pengguna->getById($detail['id']);
        $namaPegawai           = $dataPenerima3[0]->nama;

        $startDate = date('d-m-Y', strtotime("monday last week"));
        $endDate   = date('d-m-Y', strtotime("sunday last week"));

        for ($i = 1; $i <= $ulang; $i++) {
            if ($i == 1) {
                $idpenerima = $detail['idPenerima1'];
                $penerima = '_HR and Legal_';
            } else if ($i == 2) {
                $idpenerima = $detail['idPenerima2'];
                $penerima = '_General Affairs_';
            } else if ($i == 3) {
                $idpenerima = $detail['idPenerima3'];
                $penerima = $nama3;
            }

            $dataPenerima     = $this->md_pengguna->getById($idpenerima);
            //abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);
            //

            $nope           = $dataPenerima[0]->no_hp;
            $dataWa = [
                'namaSurat'     => urlencode($detail['namaSurat']),
                'noPenerima'     => $nope,
                'namaPegawai'     => urlencode($namaPegawai),
                'namaPengaju'   => urlencode($namaPengaju),
                'link'             => urlencode($id_po),
                'startDate'     => urlencode($startDate),
                'endDate'       => urlencode($endDate),
                'namaPenerima'     => urlencode($penerima)
            ];
            waLaporanSelesai($dataWa);
        }
    }

    //===== Selesai Nilai ======



    //======= Send Notif Rev2 ========
    public function sendNotifRev2()
    {
        grantAccessFor('all');
        // Ambil semua penilai_b 
        $penilaiList = $this->md_laporan->getPenilaiLaporan();

        foreach ($penilaiList as $penilai) {
            $penilai_b      = $penilai->penilai_b;
            $id_pengguna    = $penilai->id_pengguna;
            $ambilData      = $this->md_pengguna->getById($penilai_b);

            if (!empty($ambilData)) {
                $namaTerima = $ambilData[0]->nama;
                $idPenerima = $ambilData[0]->pengguna_id;


                // Kirim notifikasi WA
                $dataWa = [
                    'id'           => $id_pengguna,
                    'idPenerima1' => $idPenerima,
                    'idPenerima2' => '',
                    'namaSurat'   => 'Laporan Mingguan',
                    'penerima'    => $namaTerima
                ];

                $this->notifWaAddSuratRev2(1, $dataWa);
            }
        }

        /** LOG */
        addLog('Laporan Mingguan', 'Send Notif');
        ajaxReturnDie('success', 'Berhasil', TRUE);
    }


    public function notifWaAddSuratRev2($ulang, $detail)
    {
        //ambil data pengaju
        $ambilDataPengaju     = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju        = $ambilDataPengaju[0]->nama;


        $id_po         = encrypt($detail['id']);
        $ambilDataEv   = $this->md_pengguna->getById($detail['id']);
        $namaPegawai   = $ambilDataEv[0]->nama;

        $startDate = date('d-m-Y', strtotime("monday last week"));
        $endDate   = date('d-m-Y', strtotime("sunday last week"));


        for ($i = 1; $i <= $ulang; $i++) {
            if ($i == 1) {
                $idpenerima = $detail['idPenerima1'];
            } else if ($i == 2) {
                $idpenerima = $detail['idPenerima2'];
            }

            $dataPenerima     = $this->md_pengguna->getById($idpenerima);
            //abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);
            //

            $nope           = $dataPenerima[0]->no_hp;
            $dataWa = [
                'namaSurat'     => urlencode($detail['namaSurat']),
                'noPenerima'     => $nope,
                'namaPegawai'     => urlencode($namaPegawai),
                'namaPengaju'   => urlencode($namaPengaju),
                'link'             => urlencode($id_po),
                'startDate'     => urlencode($startDate),
                'endDate'       => urlencode($endDate),
                'namaPenerima'     => urlencode($detail['penerima'])
            ];
            waLaporanRev2($dataWa);
        }
    }


    //======= Send Notif Rev 2========


    //===== Selesai Nilai Rev 2======
    public function endPenilaianRev2()
    {
        grantAccessFor('all');

        $id = $this->input->post('id');

        // Kirim notifikasi WA
        $dataWa = [
            'id'           => $id,
            'idPenerima1' => '69',
            'idPenerima2' => '58',
            'idPenerima3' => '',
            'namaSurat'   => 'Laporan Mingguan' //,
            //'penerima'    => '_HR_'
            //'namaPegawai' => $namaPegawai,
            //'smt'         => 'Evaluasi Pegawai',
            //'tahun'       => 'Evaluasi Pegawai'
        ];

        $this->notifWaSimpanSuratRev2(2, $dataWa);

        $ambilDataPengaju     = $this->md_pengguna->getById($id);
        $namaPengaju        = $ambilDataPengaju[0]->nama;




        /** LOG */
        addLog('Laporan Mingguan', 'Selesaikan Penilaian : ' . $namaPengaju);
        ajaxReturnDie('success', 'Berhasil', TRUE);
    }

    public function notifWaSimpanSuratRev2($ulang, $detail)
    {
        //ambil data pengaju
        $ambilDataPengaju     = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju        = $ambilDataPengaju[0]->nama;


        $id_po         = encrypt($detail['id']);


        $dataPenerima3     = $this->md_pengguna->getById($detail['id']);
        $namaPegawai           = $dataPenerima3[0]->nama;

        $startDate = date('d-m-Y', strtotime("monday last week"));
        $endDate   = date('d-m-Y', strtotime("sunday last week"));

        for ($i = 1; $i <= $ulang; $i++) {
            if ($i == 1) {
                $idpenerima = $detail['idPenerima1'];
                $penerima = '_HR and Legal_';
            } else if ($i == 2) {
                $idpenerima = $detail['idPenerima2'];
                $penerima = '_General Affairs_';
            } else if ($i == 3) {
                $idpenerima = $detail['idPenerima3'];
                $penerima = $nama3;
            }

            $dataPenerima     = $this->md_pengguna->getById($idpenerima);
            //abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);
            //

            $nope           = $dataPenerima[0]->no_hp;
            $dataWa = [
                'namaSurat'     => urlencode($detail['namaSurat']),
                'noPenerima'     => $nope,
                'namaPegawai'     => urlencode($namaPegawai),
                'namaPengaju'   => urlencode($namaPengaju),
                'link'             => urlencode($id_po),
                'startDate'     => urlencode($startDate),
                'endDate'       => urlencode($endDate),
                'namaPenerima'     => urlencode($penerima)
            ];
            waLaporanSelesaiRev2($dataWa);
        }
    }

    //===== Selesai Nilai Rev 2======



    //DELETE
    public function delete($id)
    {
        grantAccessFor('all');

        $dtLaporan = $this->md_laporan->getByIdDelete($id);
        $progress  = $dtLaporan->progress;

        // Langsung hapus data
        $this->md_laporan->deleteLaporan($id);


        addLog('Menghapus Laporan', 'Menghapus Laporan : ' . $progress);
        ajaxReturnDie('success', 'Laporan berhasil dihapus', TRUE);
    }

    public function deleteOld($id)
    {
        grantAccessFor('all');

        //$status = $this->input->post('status', TRUE);

        $data = [
            'status' => "3",
        ];


        $this->md_laporan->update($id, $data);

        addLog('Menghapus Laporan', 'Menghapus Laporan  ');
        ajaxReturnDie('success', 'Laporan berhasil dihapus', TRUE);
    }


    public function deletePencapaian($id)
    {
        grantAccessFor('all');

        $dtLaporan = $this->md_laporan->getByIdDeletePencapaian($id);
        $detail    = $dtLaporan->detail;

        // Langsung hapus data
        $this->md_laporan->deletePencapaian($id);


        addLog('Menghapus Pencapaian', 'Menghapus Pencapaian : ' . $detail);
        ajaxReturnDie('success', 'Laporan berhasil dihapus', TRUE);
    }



    public function updateNilai()
    {
        grantAccessFor('all');

        //update detail Stok Opname
        $id_sodetail = $this->input->post('id_sodetail');
        foreach ($id_sodetail as $key => $row) {
            $data['nilai'] = $this->input->post('nilai')[$key];

            $this->md_laporan->update($row, $data);
        }


        //add log
        $aksi = 'Laporan Mingguan';
        $ket = 'Mengisi Nilai';
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }

    public function updateNilaiNew()
    {
        grantAccessFor('all');

        $id_sodetail = $this->input->post('id_sodetail');
        $nilai = $this->input->post('nilai');

        foreach ($id_sodetail as $row) {
            if (isset($nilai[$row])) { // Pastikan nilai ada
                $data['nilai'] = $nilai[$row];
                $this->md_laporan->update($row, $data);
            }
        }

        // Add log
        $aksi = 'Laporan Mingguan';
        $ket = 'Mengisi Nilai';
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }


    public function inputNilaiLaporanAdm()
    {
        grantAccessFor('all');

        $nilai = $this->input->post('nilaia'); // array point => nilai_a
        $pengguna_id = $this->input->post('pengguna_id');
        $wednesday = $this->input->post('wednesday');

        if (empty($nilai)) {
            ajaxReturnDie('error', 'Tidak ada nilai yang dikirim', FALSE);
        }

        foreach ($nilai as $point => $nilai_b) {
            $data = [
                'point' => $point,
                'nilai_a' => $nilai_b,
                'id_pengguna' => $pengguna_id,
                'date' => $wednesday
            ];
            //$this->md_laporan->addNilai($data);
            $this->md_laporan->saveOrUpdateNilaiAdm($data);
        }

        addlog('Laporan Mingguan', 'Mengisi Nilai A');

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }


    public function inputNilaiLaporan()
    {
        grantAccessFor('all');

        $nilai = $this->input->post('nilaib'); // array point => nilai_b
        $pengguna_id = $this->input->post('pengguna_id');
        $wednesday = $this->input->post('wednesday');

        if (empty($nilai)) {
            ajaxReturnDie('error', 'Tidak ada nilai yang dikirim', FALSE);
        }

        foreach ($nilai as $point => $nilai_b) {
            $data = [
                'point' => $point,
                'nilai_b' => $nilai_b,
                'id_pengguna' => $pengguna_id,
                'date' => $wednesday
            ];
            //$this->md_laporan->addNilai($data);
            $this->md_laporan->saveOrUpdateNilai($data);
        }

        addlog('Laporan Mingguan', 'Mengisi Nilai B');

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }


    public function inputNilaiPencapaianAdm()
    {
        grantAccessFor('all');

        $nilaia   = $this->input->post('nilaipa');     // nilai_a [id_sodetail => nilai]
        $catatana = $this->input->post('catatanpa');   // catatan_a [id_sodetail => catatan]

        foreach ($nilaia as $id_sodetail => $nilai_a) {
            $data['nilai_a']    = $nilai_a;
            $data['catatan_a']  = isset($catatana[$id_sodetail]) ? $catatana[$id_sodetail] : null;

            $this->md_laporan->updatePencapaian($id_sodetail, $data);
        }

        // Log
        addlog('Laporan Mingguan', 'Mengisi Nilai Pencapaian A');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }


    public function inputNilaiPencapaian()
    {
        grantAccessFor('all');

        $nilaib   = $this->input->post('nilaipb');     // nilai_b [id_sodetail => nilai]
        $catatanb = $this->input->post('catatanpb');   // catatan_b [id_sodetail => catatan]

        foreach ($nilaib as $id_sodetail => $nilai_b) {
            $data['nilai_b']    = $nilai_b;
            $data['catatan_b']  = isset($catatanb[$id_sodetail]) ? $catatanb[$id_sodetail] : null;

            $this->md_laporan->updatePencapaian($id_sodetail, $data);
        }

        // Log
        addlog('Laporan Mingguan', 'Mengisi Nilai Pencapaian B');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }





    public function inputNilaiUmumAdm()
    {
        grantAccessFor('all');

        $data = [
            'nilaia1' => $this->input->post('nilaia1'),
            'nilaia2' => $this->input->post('nilaia2'),
            'nilaia3' => $this->input->post('nilaia3'),
            'nilaia4' => $this->input->post('nilaia4'),
            'nilaia5' => $this->input->post('nilaia5'),
            'catatana1' => $this->input->post('catatana1'),
            'catatana2' => $this->input->post('catatana2'),
            'catatana3' => $this->input->post('catatana3'),
            'catatana4' => $this->input->post('catatana4'),
            'catatana5' => $this->input->post('catatana5'),
            'id_pengguna' => $this->input->post('id_pengg'),
            'date' => $this->input->post('hari_rabu')
        ];

        // Cek minimal satu nilai diisi
        if (
            empty($data['nilaia1']) &&
            empty($data['nilaia2']) &&
            empty($data['nilaia3']) &&
            empty($data['nilaia4']) &&
            empty($data['nilaia5'])
        ) {
            ajaxReturnDie('error', 'Tidak ada nilai yang dikirim', FALSE);
        }

        $this->md_laporan->saveOrUpdateNilaiUmumAdm($data);

        addlog('Laporan Mingguan', 'Mengisi Nilai Umum A');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }

    public function inputNilaiUmum()
    {
        grantAccessFor('all');

        $data = [
            'nilaib1' => $this->input->post('nilaib1'),
            'nilaib2' => $this->input->post('nilaib2'),
            'nilaib3' => $this->input->post('nilaib3'),
            'nilaib4' => $this->input->post('nilaib4'),
            'nilaib5' => $this->input->post('nilaib5'),
            'catatanb1' => $this->input->post('catatanb1'),
            'catatanb2' => $this->input->post('catatanb2'),
            'catatanb3' => $this->input->post('catatanb3'),
            'catatanb4' => $this->input->post('catatanb4'),
            'catatanb5' => $this->input->post('catatanb5'),
            'id_pengguna' => $this->input->post('id_pengg'),
            'date' => $this->input->post('hari_rabu')
        ];

        // Cek minimal satu nilai diisi
        if (
            empty($data['nilaib1']) &&
            empty($data['nilaib2']) &&
            empty($data['nilaib3']) &&
            empty($data['nilaib4']) &&
            empty($data['nilaib5'])
        ) {
            ajaxReturnDie('error', 'Tidak ada nilai yang dikirim', FALSE);
        }

        $this->md_laporan->saveOrUpdateNilaiUmum($data);

        addlog('Laporan Mingguan', 'Mengisi Nilai Umum B');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }









    //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // pagination -------------------------------------------------------------------------------------------------------------------------------------------------------
    //-------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function pagination($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'all') {

            $dt     = $this->md_laporan->getAllPenggunaAktif();

            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                $id_po           = encrypt($row->pengguna_id);
                $pegawai    = '<a href="laporan/show/detail/laporan_mingguan/' . $id_po . '">' . $row->nama . '</a>';

                $th = array();
                $th[] = ++$start;
                $th[] = $pegawai;
                $th[] = $row->no_pegawai;
                $th[] = $row->jabatan;
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($param == 'allNew') {

            $dt     = $this->md_laporan->getAllPenggunaAktif();

            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                $id_po           = encrypt($row->pengguna_id);
                $pegawai    = '<a href="laporan/show/detail/laporan/' . $id_po . '">' . $row->nama . '</a>';

                $th = array();
                $th[] = ++$start;
                $th[] = $pegawai;
                $th[] = $row->no_pegawai;
                $th[] = $row->jabatan;
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($param == 'allRev1') {

            $dt     = $this->md_laporan->getAllPenggunaAktif();

            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                $id          = encrypt($row->pengguna_id);
                $pegawai    = '<a href="laporan/show/detail/laporan_detail/' . $id . '">' . $row->nama . '</a>';
                $id_lp          = encrypt($row->id_lp);
                $li_btn       = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id_lp . '"><i class="bx bx-pencil"></i></button>
                </div>';

                $th = array();
                $th[] = ++$start;
                $th[] = $pegawai;
                $th[] = $row->no_pegawai;
                $th[] = $row->jabatan;
                $th[] = $row->penilai;
                $th[] = $li_btn;
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($param == 'allRev2') {

            $dt     = $this->md_laporan->getAllPenggunaAktif();

            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                $id           = encrypt($row->pengguna_id);
                $pegawai      = '<a href="laporan/show/detail/laporan_detail_rev2/' . $id . '">' . $row->nama . '</a>';
                $id_lp        = encrypt($row->id_lp);

                $li_btn       = '
                                <div class="btn-group" role="group" aria-label="First group">
                                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id_lp . '" data-user="' . $row->pengguna_id . '"><i class="bx bx-pencil"></i></button>
                                </div>';

                $th = array();
                $th[] = ++$start;
                $th[] = $pegawai;
                $th[] = $row->no_pegawai;
                $th[] = $row->jabatan;
                $th[] = $row->penilai;
                $th[] = $li_btn;
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($param == 'my_data') {

            $dt     = $this->md_laporan->getAllLaporan(sessPenggunaId());
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {
                $id           = encrypt($row->id);
                $li_btn       = '
                    <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->id . '" data-object="laporan/delete/' . $row->id . '"><i class="bx bx-trash"></i></button>
                    </div>';

                if ($row->link != '') {
                    $link_download = '<a href="' . htmlspecialchars($row->link, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-download"></i> Download
                    </a>';
                } else {
                    $link_download = 'Tidak Ada';
                }


                $th = array();
                $th[] = ++$start;
                $th[] = date('d-M-Y', strtotime($row->tanggal));
                $th[] = $row->jobdesc;
                $th[] = $row->progress;
                $th[] = $row->status_pekerjaan;
                $th[] = $link_download;
                $th[] = $li_btn;
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($param == 'my_detail') {

            $dt     = $this->md_evaluasi->getAllEvMy(sessPenggunaId());
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                $id_po           = encrypt($row->id_po);
                $pegawai    = '<a href="evaluasi/show/detail/my_data/' . $id_po . '">' . $row->pegawai . '</a>';

                if ($row->status == "1") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
                } else if ($row->status == "2") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Penilai Diinput</span>';
                } else if ($row->status == "3") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Notifikasi Dikirim</span>';
                } else if ($row->status == "4") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Nilai Diinput</span>';
                } else {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Selesai</span>';
                }


                $th = array();
                $th[] = ++$start;
                $th[] = $pegawai;
                $th[] = $row->no_pegawai;
                $th[] = $row->jabatan;
                $th[] = $row->smt;
                $th[] = $row->tahun;
                $th[] = $stat_surat;
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($param == 'my_penilai') {

            $dt     = $this->md_laporan->getAllPenilai(sessPenggunaId());

            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                $id_po           = encrypt($row->pengguna_id);
                $pegawai    = '<a href="laporan/show/detail/my_detail_nilai/' . $id_po . '">' . $row->nama . '</a>';

                $th = array();
                $th[] = ++$start;
                $th[] = $pegawai;
                $th[] = $row->no_pegawai;
                $th[] = $row->jabatan;
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($param == 'my_penilai_rev2') {

            $dt     = $this->md_laporan->getAllPenilai(sessPenggunaId());

            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                $id_po           = encrypt($row->pengguna_id);
                $pegawai    = '<a href="laporan/show/detail/my_detail_nilai_rev2/' . $id_po . '">' . $row->nama . '</a>';

                $th = array();
                $th[] = ++$start;
                $th[] = $pegawai;
                $th[] = $row->no_pegawai;
                $th[] = $row->jabatan;
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        }
    }



    public function exportlaporan()
    {

        $data = $this->md_laporan->getAllPencapaianByTgl($this->input->get('idmarketing'), $this->input->get('tglawal'), $this->input->get('tglakhir'));



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

        $sheet->setCellValue('A1', "DATA PENCAPAIAN " . strtoupper($this->input->get('namamarketing'))); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('A1:L1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1

        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Tanggal');
        $sheet->setCellValue('C4', 'Nama Pegawai');
        $sheet->setCellValue('D4', 'Deskripsi');
        $sheet->setCellValue('E4', 'Hasil (Link atau Keterangan)');
        $sheet->setCellValue('F4', 'Nilai A');
        $sheet->setCellValue('G4', 'Catatan A');
        $sheet->setCellValue('H4', 'Nilai B');
        $sheet->setCellValue('I4', 'Catatan B');

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

        function formatTanggalIndonesia($tanggal)
        {
            $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $bulan = [
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember'
            ];

            $tgl = date('d', strtotime($tanggal));
            $bln = date('m', strtotime($tanggal));
            $thn = date('Y', strtotime($tanggal));
            $hariText = $hari[date('w', strtotime($tanggal))];

            return $hariText . ', ' . ltrim($tgl, '0') . ' ' . $bulan[$bln] . ' ' . $thn;
        }


        $totalA = 0;
        $totalB = 0;
        $jumlahData = 0;

        foreach ($data as $marketing) {
            $nilaiTampil = !empty($marketing->link) ? $marketing->link : $marketing->keterangan;

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, formatTanggalIndonesia($marketing->tanggal))
                ->setCellValue('C' . $kolom, $marketing->nama)
                ->setCellValue('D' . $kolom, $marketing->detail)
                ->setCellValue('E' . $kolom, $nilaiTampil)
                ->setCellValue('F' . $kolom, $marketing->nilai_a)
                ->setCellValue('G' . $kolom, $marketing->catatan_a)
                ->setCellValue('H' . $kolom, $marketing->nilai_b)
                ->setCellValue('I' . $kolom, $marketing->catatan_b);

            $totalA += $marketing->nilai_a;
            $totalB += $marketing->nilai_b;
            $jumlahData++;

            $kolom++;
            $nomor++;
        }

        // Hitung rata-rata
        $rataA = $jumlahData > 0 ? $totalA / $jumlahData : 0;
        $rataB = $jumlahData > 0 ? $totalB / $jumlahData : 0;

        // Tampilkan rata-rata di baris berikutnya
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('E' . $kolom, 'RATA-RATA')
            ->setCellValue('F' . $kolom, number_format($rataA, 2))
            ->setCellValue('H' . $kolom, number_format($rataB, 2));

        // Bold style
        $spreadsheet->getActiveSheet()->getStyle('D' . $kolom . ':H' . $kolom)->getFont()->setBold(true);

        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(20); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(35); // Set width kolom C
        $sheet->getColumnDimension('D')->setWidth(75); // Set width kolom C
        $sheet->getColumnDimension('E')->setWidth(75); // Set width kolom D
        $sheet->getColumnDimension('F')->setWidth(15); // Set width kolom E
        $sheet->getColumnDimension('G')->setWidth(25); // Set width kolom F
        $sheet->getColumnDimension('H')->setWidth(15); // Set width kolom G
        $sheet->getColumnDimension('I')->setWidth(25); // Set width kolom H

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Data Pencapaian");
        ob_end_clean();
        // Proses file excel
        $filename = "Data Pencapaian - " . strtoupper($this->input->get('namamarketing')) . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function print_mingguan($param1 = "", $param2 = "0")
    {
        grantAccessFor('all');

        $id_pengaju = decrypt($param1);
        if (empty($id_pengaju)) {
            $id_pengaju = sessPenggunaId();
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $param2)) {
            $ts = strtotime($param2);
            $monday = date('Y-m-d', strtotime("monday this week", $ts));
            $friday = date('Y-m-d', strtotime("sunday this week", $ts));
            $startDate = date('d-m-Y', strtotime($monday));
            $endDate = date('d-m-Y', strtotime($friday));
            $week_offset = 0;
        } else {
            $week_offset = (isset($param2) && is_numeric($param2)) ? (int)$param2 : 0;
            $monday = date('Y-m-d', strtotime("monday this week", strtotime("$week_offset week")));
            $friday = date('Y-m-d', strtotime("sunday this week", strtotime("$week_offset week")));
            $startDate = date('d-m-Y', strtotime($monday));
            $endDate = date('d-m-Y', strtotime($friday));
        }

        $week_number = ceil(date('d', strtotime($monday)) / 7);
        $month_year = $this->convertBulan(date('F Y', strtotime($monday)));

        $data_job = $this->md_laporan->getPengguna($id_pengaju);
        $data_detail = $this->md_laporan->getLaporanMingguanRev2($monday, $friday, $id_pengaju);
        if (empty($data_detail)) {
            $data_detail = $this->md_laporan->getLaporanPerMinggu($monday, $friday, $id_pengaju);
        }
        $data_detail_pencapaian = $this->md_laporan->getPencapaianPerMingguRev2($monday, $friday, $id_pengaju);

        $dt = [
            'title_pdf' => 'Laporan Mingguan - ' . ($data_job ? $data_job->nama : ''),
            'data_job' => $data_job,
            'data_detail' => $data_detail,
            'data_detail_pencapaian' => $data_detail_pencapaian,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'week_number' => $week_number,
            'month_year' => $month_year,
            'week_offset' => $week_offset
        ];

        $html = $this->load->view('pages/v_print/print_laporan_mingguan', $dt, true);

        $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
        $mpdf->AddPage('P', '', '', '', '', 10, 10, 10, 10);
        $mpdf->WriteHTML($html);
        $mpdf->Output('Laporan_Mingguan_' . ($data_job ? str_replace(' ', '_', $data_job->nama) : 'Karyawan') . '.pdf', 'I');
    }

    public function print_bulanan($param1 = "", $param2 = "")
    {
        grantAccessFor('all');

        $id_pengaju = decrypt($param1);
        if (empty($id_pengaju)) {
            $id_pengaju = sessPenggunaId();
        }

        $month = !empty($param2) ? $param2 : ($this->input->get('month') ? $this->input->get('month') : date('Y-m'));
        $time = strtotime($month . '-01');
        if (!$time) {
            $time = time();
            $month = date('Y-m');
        }

        $startDate = date('Y-m-01', $time);
        $endDate = date('Y-m-t', $time);

        $tahun = date('Y', $time);
        $bulan_en = date('F', $time);
        $nama_bulan = $this->convertBulan($bulan_en);

        $data_job = $this->md_laporan->getPengguna($id_pengaju);
        $laporan_bulan = $this->md_laporan->getLaporanPerBulan($startDate, $endDate, $id_pengaju);
        $pencapaian_bulan = $this->md_laporan->getPencapaianPerBulan($startDate, $endDate, $id_pengaju);
        $rekap_status = $this->md_laporan->getRekapStatusBulan($startDate, $endDate, $id_pengaju);

        $dt = [
            'title_pdf' => 'Rangkuman Laporan Bulanan - ' . ($data_job ? $data_job->nama : ''),
            'data_job' => $data_job,
            'laporan_bulan' => $laporan_bulan,
            'pencapaian_bulan' => $pencapaian_bulan,
            'rekap_status' => $rekap_status,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'nama_bulan' => $nama_bulan,
            'tahun' => $tahun
        ];

        $html = $this->load->view('pages/v_print/print_laporan_bulanan', $dt, true);

        $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
        $mpdf->AddPage('P', '', '', '', '', 10, 10, 10, 10);
        $mpdf->WriteHTML($html);
        $mpdf->Output('Rangkuman_Laporan_Bulanan_' . ($data_job ? str_replace(' ', '_', $data_job->nama) : 'Karyawan') . '_' . $month . '.pdf', 'I');
    }
}
