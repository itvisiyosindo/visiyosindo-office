<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        // $this->load->model('md_anggota_logbook');
        $this->load->model('md_dashboard');
        $this->load->model('md_log');
        $this->load->model('md_absensi');
        $this->load->model('md_announcement');
    }

    function id_navbar()
    {
        $id_navbar = "home";
        return $id_navbar;
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']          = $this->id_navbar();
        $page_data['page_name']        = 'dashboard';
        $page_data['page_title']    = 'Dashboard Home';
        $page_data['present']         = $this->md_absensi->countPresentToday();
        $page_data['terlambat']     = $this->md_absensi->countTerlambatToday();
        $page_data['istirahat']     = $this->md_absensi->countIstirahatToday();
        $page_data['noistirahat']     = $this->md_absensi->countTidakIstirahatToday();
        $page_data['izin']             = $this->md_absensi->countIzinToday();
        $page_data['cuti']             = $this->md_absensi->countCutiToday();
        $page_data['sakit']         = $this->md_absensi->countSakitToday();
        $page_data['pulang']         = $this->md_absensi->countPulangToday();
        $page_data['pengumuman']         = $this->md_announcement->countPengumuman();
        $page_data['daftar_hadir']     = $this->md_absensi->daftar_hadirToday();
        $page_data['ulangtahun']     = $this->md_absensi->count_ulangTahun();
        $page_data['daftar_ulangtahun']     = $this->md_absensi->daftar_ulangTahun();
        $page_data['daftar_pulang']     = $this->md_absensi->daftar_pulangToday();
        $page_data['daftar_istirahat']     = $this->md_absensi->daftar_istirahatToday();
        $page_data['daftar_noistirahat']     = $this->md_absensi->daftar_tidakIstirahatToday();
        $page_data['daftar_terlambat']     = $this->md_absensi->daftar_terlambatToday();
        $page_data['daftar_izin']     = $this->md_absensi->daftar_izinToday('izin');
        $page_data['daftar_sakit']     = $this->md_absensi->daftar_izinToday('sakit');
        $page_data['daftar_cuti']     = $this->md_absensi->daftar_izinToday('cuti');
        $page_data['announce']         = $this->md_announcement->getAnnouncementLimit(3);
        // $page_data['page_desc']       = 'Barang dan Jasa';
        // $page_data['jenis_dashboard'] = 'Administrator';

        //Berfungsi untuk Absen Kantor atau Dinas
        $pengguna_id = sessPenggunaId();
        $page_data['is_dinas'] = $this->md_absensi->is_dinas_today($pengguna_id);

        //Dashboard Home New
        $page_data['izin_jam']         = $this->md_dashboard->countIzinJamKerja($pengguna_id);
        $page_data['izin_meinggalkan']     = $this->md_dashboard->countIzinMeninggalkan($pengguna_id);
        //$page_data['cuti_tahunan'] 		= $this->md_dashboard->countCutiTahunan($pengguna_id);
        $page_data['daftar_izin_jam']     = $this->md_dashboard->daftarIzinJamKerja($pengguna_id);
        $page_data['daftar_izin_meninggalkan']     = $this->md_dashboard->daftarIzinMeninggalkan($pengguna_id);
        $page_data['daftar_cuti_tahunan']     = $this->md_dashboard->daftarCutiTahunan($pengguna_id);

        //Sisa Cuti tahunan
        $pemerintah   = 8; //Tahun 2026 Tiap Tahun Harus Diupdate tergantung Peraturan Pemerintah
        $sisa_cuti    = 12 - $pemerintah;
        $page_data['tahun_berjalan'] = (int) date('Y');
        $page_data['rekap_sisa_cuti_karyawan'] = array();

        $total_cuti      = $this->md_dashboard->countCutiTahunan($pengguna_id); //Dari Pengajuan Cuti yang sudah disetujui GM
        $masa_kerja   = $this->md_dashboard->getMasaKerjaKategori($pengguna_id);

        if ($masa_kerja == 'A') {
            // kondisi A Diatas 5 tahun
            $total_sisa_cuti = ($sisa_cuti + 2) - $total_cuti;
            $page_data['detail_cuti'] = "Anda Sudah Bekerja Diatas 5 tahun dari Tanggal Kontrak, mendapatkan Jatah Cuti " . ($sisa_cuti + 2) . " Hari";
        } elseif ($masa_kerja == 'B') {
            // kondisi B Diatas 1 tahun
            $total_sisa_cuti = $sisa_cuti - $total_cuti;
            $page_data['detail_cuti'] = "Anda Sudah Bekerja Diatas 1 tahun dari Tanggal Kontrak, mendapatkan Jatah Cuti " . ($sisa_cuti) . " Hari";
        } elseif ($masa_kerja == 'C') {
            // kondisi C Dibawah 1 tahun
            $total_sisa_cuti = 0;
            $page_data['detail_cuti'] = "Anda Bekerja Dibawah 1 tahun dari Tanggal Kontrak, sehingga belum Mendapatkan Jatah Cuti";
        } else {
            // kondisi D belum Kontrak atau belum diinput
            $total_sisa_cuti = 0;
            $page_data['detail_cuti'] = "Anda Belum Kontrak, sehingga belum Mendapatkan Jatah Cuti. Jika Sudah Kontrak, segera Hubungi General Affair";
        }

        $page_data['cuti_tahunan'] = $total_sisa_cuti;

        if (isAdmin() || isHrd()) {
            $page_data['rekap_sisa_cuti_karyawan'] = $this->md_dashboard->getRekapSisaCutiKaryawanTahunBerjalan($pemerintah, $page_data['tahun_berjalan']);
        }





        $this->load->view('index', $page_data);
    }

    public function pagination_log()
    {
        grantAccessFor('all');

        $dt = $this->md_log->getAllLog();
        $start = $this->input->post('start');
        $data = array();
        foreach ($dt['data'] as $row) {
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_pengguna ? $row->nama_pengguna : '-';
            $th[] = $row->jenis_aksi;
            $th[] = $row->keterangan;
            $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y | H:i', strtotime($row->tgl));
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
