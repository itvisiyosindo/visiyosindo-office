<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_kepegawaian extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        // $this->load->model('md_anggota_logbook');
        $this->load->model('md_dashboard');
        $this->load->model('md_log');
        $this->load->model('md_absensi');
        $this->load->model('md_laporan');
    }

    function id_navbar()
    {
        $id_navbar = "kepegawaian";
        return $id_navbar;
    }

    public function index($param = "")
    {
        grantAccessFor('all');

        $page_data['switch']       = $this->id_navbar();
        $page_data['page_name']    = 'dashboard_kepegawaian';
        $page_data['page_title']   = 'Dashboard Kepegawaian';
        $page_data['present']      = $this->md_absensi->countPresentToday();
        $page_data['izin']         = $this->md_absensi->countIzinToday();
        $page_data['cuti']         = $this->md_absensi->countCutiToday();
        $page_data['sakit']        = $this->md_absensi->countSakitToday();
        $page_data['daftar_hadir'] = $this->md_absensi->daftar_hadirToday();
        $page_data['daftar_izin']  = $this->md_absensi->daftar_izinToday('izin');
        $page_data['daftar_sakit'] = $this->md_absensi->daftar_izinToday('sakit');
        $page_data['daftar_cuti']  = $this->md_absensi->daftar_izinToday('cuti');
        $page_data['getSP']        = $this->md_dashboard->getSPLatest(sessPenggunaId());
        $page_data['getUser']      = $this->md_pengguna->getById(sessPenggunaId());

        // Ambil bulan dari GET, default ke sekarang
        $month = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');
        // Ubah $month (Y-m) ke nama bulan Indonesia
        $month = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');

        // Pecah $month
        list($tahun, $bulanAngka) = explode('-', $month);

        // Array nama bulan Indonesia
        $namaBulan = [
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

        // Gabungkan
        $page_data['bulanIni'] = $namaBulan[$bulanAngka] . ' ' . $tahun;


        // Hitung sisa masa SP
        $getSP = $this->md_dashboard->getSPLatest(sessPenggunaId());
        if ($getSP) {
            $tgl_pengajuan_str = (isset($getSP->tgl_pengajuan) && !empty($getSP->tgl_pengajuan)) ? $getSP->tgl_pengajuan : date('Y-m-d');
            $tgl_pengajuan = new DateTime($tgl_pengajuan_str);
            $bulan_ini = DateTime::createFromFormat('Y-m', $month);

            $selisih_bulan = (($bulan_ini->format('Y') - $tgl_pengajuan->format('Y')) * 12) +
                ($bulan_ini->format('m') - $tgl_pengajuan->format('m'));

            $masa = isset($getSP->masa) ? intval($getSP->masa) : 0;
            $sisa_masa = $masa - $selisih_bulan;

            if ($sisa_masa <= 0) {
                $page_data['sisa_masa'] = 0;
                $page_data['s_peringatan'] = "Tidak ada SP";
                $page_data['nilaiSp'] = 100; // Set nilai default SP jika tidak ada SP
            } else {
                $page_data['sisa_masa'] = $sisa_masa;

                // Tentukan jenis SP berdasarkan perihal
                $perihal = isset($getSP->perihal) ? strtolower(trim($getSP->perihal)) : '';
                if ($perihal == 'pertama') {
                    $sPeringatan = "SP 1";
                    $nilaiSp     = 80;
                } elseif ($perihal == 'kedua') {
                    $sPeringatan = "SP 2";
                    $nilaiSp     = 60;
                } elseif (in_array($perihal, ['ketiga', 'ketiga lanjutan'])) {
                    $sPeringatan = "SP 3";
                    $nilaiSp     = 40;
                } else {
                    $sPeringatan = "SP Tidak Diketahui";
                    $nilaiSp     = 100; // Nilai default jika perihal tidak dikenali
                }

                $page_data['s_peringatan'] = $sPeringatan;
                $page_data['nilaiSp'] = $nilaiSp;
            }
        }
 else {
            $page_data['sisa_masa'] = 0;
            $page_data['s_peringatan'] = "Tidak ada SP";
            $page_data['nilaiSp'] = 100;
        }




        $page_data['getTest']          = $this->md_dashboard->getTestById(sessPenggunaId(), $month);
        $kehadiran                     = $this->md_absensi->getKehadiran(sessPenggunaId(), $month);
        $page_data['total_kehadiran']  = count($kehadiran);
        $page_data['getHariKerja']     = $this->md_dashboard->getHariKerja($month);

        // Nilai Rata-Rata Laporan Mingguan Perbulan
        $data_nilai = $this->md_dashboard->getNilaiData(sessPenggunaId(), $month);
        $total_nilai = 0;
        $total_nilai_b = 0;
        $jumlah_data = 0;
        foreach ($data_nilai as $row) {
            // Pastikan nilai dan nilai_b adalah angka valid
            if (!empty($row->id_lap) && $row->jenis !== 'TIDAK ADA PEKERJAAN DI MINGGU INI') {
                $total_nilai += (float)$row->nilai;
                $total_nilai_b += (float)$row->nilai_b;
                $jumlah_data++; // Hanya hitung jika data valid
            }
        }
        // Menghitung rata-rata hanya jika ada data valid
        $nilaiRata = ($jumlah_data > 0) ? ($total_nilai / $jumlah_data) : 0;
        $nilaiRata_b = ($jumlah_data > 0) ? ($total_nilai_b / $jumlah_data) : 0;
        $nilaiRataAll = ($jumlah_data > 0) ? ($nilaiRata + $nilaiRata_b) / 2 : 0;
        //$page_data['rata_nilai'] = $nilaiRataAll;

        //Nilai Rata-Rata Pencapaian Per Bulan
        $data_nilai_pencapaian = $this->md_dashboard->getNilaiData(sessPenggunaId(), $month);
        $total_nilai_pencapaian = 0;
        $total_nilai_b_pencapaian = 0;
        $jumlah_data_pencapaian = 0;
        foreach ($data_nilai_pencapaian as $row_pencapaian) {
            // Pastikan nilai dan nilai_b adalah angka valid
            if (!empty($row_pencapaian->id_lap)) {
                $total_nilai_pencapaian += (float)$row_pencapaian->nilai_pencapaian_a;
                $total_nilai_b_pencapaian += (float)$row_pencapaian->nilai_pencapaian_b;
                $jumlah_data_pencapaian++; // Hanya hitung jika data valid
            }
        }
        // Menghitung rata-rata hanya jika ada data valid
        $nilaiRata_pencapaian = ($jumlah_data_pencapaian > 0) ? ($total_nilai_pencapaian / $jumlah_data_pencapaian) : 0;
        $nilaiRata_b_pencapaian = ($jumlah_data_pencapaian > 0) ? ($total_nilai_b_pencapaian / $jumlah_data_pencapaian) : 0;
        $nilaiRataAll_pencapaian = ($jumlah_data_pencapaian > 0) ? ($nilaiRata_pencapaian + $nilaiRata_b_pencapaian) / 2 : 0;
        //$page_data['rata_nilai_pencapaian'] = $nilaiRataAll_pencapaian;

        //===== Nilai Rata-Rata Laporan Mingguan Perbulan NEW ========
        $data_nilai2 = $this->md_dashboard->getNilaiLapMonth(sessPenggunaId(), $month);
        $total_nilai_month_a2 = 0;
        $total_nilai_month_b2 = 0;
        $jumlah_data_month2 = 0;
        foreach ($data_nilai2 as $row21) {
            $total_nilai_month_a2 += (float)$row21->nilai_a;
            $total_nilai_month_b2 += (float)$row21->nilai_b;
            $jumlah_data_month2++; // Hanya hitung jika data valid
        }
        // Menghitung rata-rata hanya jika ada data valid
        $nilaiRata_a2 = ($jumlah_data_month2 > 0) ? ($total_nilai_month_a2 / $jumlah_data_month2) : 0;
        $nilaiRata_b2 = ($jumlah_data_month2 > 0) ? ($total_nilai_month_b2 / $jumlah_data_month2) : 0;
        $nilaiRataAll2 = ($jumlah_data_month2 > 0) ? ($nilaiRata_a2 + $nilaiRata_b2) / 2 : 0;

        //===== Nilai Rata-Rata Penilaian umum Perbulan NEW ========
        $data_nilai3 = $this->md_dashboard->getNilaiUmumMonth(sessPenggunaId(), $month);
        $total_nilai_month_a31 = 0;
        $total_nilai_month_a32 = 0;
        $total_nilai_month_a33 = 0;
        $total_nilai_month_a34 = 0;
        $total_nilai_month_a35 = 0;
        $total_nilai_month_b31 = 0;
        $total_nilai_month_b32 = 0;
        $total_nilai_month_b33 = 0;
        $total_nilai_month_b34 = 0;
        $total_nilai_month_b35 = 0;
        $jumlah_data_month3 = 0;
        foreach ($data_nilai3 as $row31) {
            $total_nilai_month_a31 += (float)$row31->nilaia1;
            $total_nilai_month_a32 += (float)$row31->nilaia2;
            $total_nilai_month_a33 += (float)$row31->nilaia3;
            $total_nilai_month_a34 += (float)$row31->nilaia4;
            $total_nilai_month_a35 += (float)$row31->nilaia5;
            $total_nilai_month_b31 += (float)$row31->nilaib1;
            $total_nilai_month_b32 += (float)$row31->nilaib2;
            $total_nilai_month_b33 += (float)$row31->nilaib3;
            $total_nilai_month_b34 += (float)$row31->nilaib4;
            $total_nilai_month_b35 += (float)$row31->nilaib5;
            $jumlah_data_month3++; // Hanya hitung jika data valid
        }
        // Menghitung rata-rata hanya jika ada data valid
        $nilaiRataMonth_a31 = ($jumlah_data_month3 > 0) ? ($total_nilai_month_a31 / $jumlah_data_month3) : 0;
        $nilaiRataMonth_a32 = ($jumlah_data_month3 > 0) ? ($total_nilai_month_a32 / $jumlah_data_month3) : 0;
        $nilaiRataMonth_a33 = ($jumlah_data_month3 > 0) ? ($total_nilai_month_a33 / $jumlah_data_month3) : 0;
        $nilaiRataMonth_a34 = ($jumlah_data_month3 > 0) ? ($total_nilai_month_a34 / $jumlah_data_month3) : 0;
        $nilaiRataMonth_a35 = ($jumlah_data_month3 > 0) ? ($total_nilai_month_a35 / $jumlah_data_month3) : 0;
        $nilaiRataMonth_b31 = ($jumlah_data_month3 > 0) ? ($total_nilai_month_b31 / $jumlah_data_month3) : 0;
        $nilaiRataMonth_b32 = ($jumlah_data_month3 > 0) ? ($total_nilai_month_b32 / $jumlah_data_month3) : 0;
        $nilaiRataMonth_b33 = ($jumlah_data_month3 > 0) ? ($total_nilai_month_b33 / $jumlah_data_month3) : 0;
        $nilaiRataMonth_b34 = ($jumlah_data_month3 > 0) ? ($total_nilai_month_b34 / $jumlah_data_month3) : 0;
        $nilaiRataMonth_b35 = ($jumlah_data_month3 > 0) ? ($total_nilai_month_b35 / $jumlah_data_month3) : 0;
        $nilaiRataAll3 = ($jumlah_data_month3 > 0) ? ($nilaiRataMonth_a31 + $nilaiRataMonth_a32 + $nilaiRataMonth_a33 + $nilaiRataMonth_a34 + $nilaiRataMonth_a35 + $nilaiRataMonth_b31 + $nilaiRataMonth_b32 + $nilaiRataMonth_b33 + $nilaiRataMonth_b34 + $nilaiRataMonth_b35) / 10 : 0;

        $nilaiRataAllLap = (floatval($nilaiRataAll2) + floatval($nilaiRataAll3)) / 2;


        //Nilai Rata-Rata Pencapaian Per Bulan NEWW
        $data_nilai_pencapaian2 = $this->md_dashboard->getNilaiPencMonth(sessPenggunaId(), $month);
        $total_nilai_pencapaian_a = 0;
        $total_nilai_pencapaian_b = 0;
        $jumlah_data_pencapaian2 = 0;
        foreach ($data_nilai_pencapaian2 as $row_pencapaian2) {
            $total_nilai_pencapaian_a += (float)$row_pencapaian2->nilai_a;
            $total_nilai_pencapaian_b += (float)$row_pencapaian2->nilai_b;
            $jumlah_data_pencapaian2++; // Hanya hitung jika data valid

        }
        // Menghitung rata-rata hanya jika ada data valid
        $nilaiRata_pencapaian_a = ($jumlah_data_pencapaian2 > 0) ? ($total_nilai_pencapaian_a / $jumlah_data_pencapaian2) : 0;
        $nilaiRata_pencapaian_b = ($jumlah_data_pencapaian2 > 0) ? ($total_nilai_pencapaian_b / $jumlah_data_pencapaian2) : 0;
        $nilaiRataAll_pencapaian2 = ($jumlah_data_pencapaian2 > 0) ? ($nilaiRata_pencapaian_a + $nilaiRata_pencapaian_b) / 2 : 0;


        $tanggalCek = date('Y-m', strtotime($month)); // atau gunakan tanggal lain yang relevan

        if ($tanggalCek >= '2025-07') {
            $page_data['rata_nilai'] = $nilaiRataAllLap;
            $page_data['rata_nilai_pencapaian'] = $nilaiRataAll_pencapaian2;
        } else {
            $page_data['rata_nilai'] = $nilaiRataAll;
            $page_data['rata_nilai_pencapaian'] = $nilaiRataAll_pencapaian;
        }




        /*
        //Priode
        $week_offset = (isset($param) && is_numeric($param)) ? (int)$param : 0;

        // Dapatkan hari ini, lalu cari Senin pada minggu saat ini, lalu tambahkan offset minggu
        $monday = date('Y-m-d', strtotime("monday this week", strtotime("$week_offset week")));
        $sunday = date('Y-m-d', strtotime("sunday this week", strtotime("$week_offset week")));

        $startDate = date('d-m-Y', strtotime("monday this week", strtotime("$week_offset week")));
        $endDate = date('d-m-Y', strtotime("sunday this week", strtotime("$week_offset week")));


        // Navigasi minggu
        $page_data['week_offset'] = $week_offset;
        $page_data['startDate'] = $startDate;
        $page_data['endDate'] = $endDate;


        //Nilai Laporan Mingguan Per Minggu
        $data_nilai_week = $this->md_dashboard->getNilaiDataPerWeek(sessPenggunaId(), $monday, $sunday);
        $total_nilai_week = 0;
        $total_nilai_b_week = 0;
        $jumlah_data_week = 0;
        foreach ($data_nilai_week as $row_week) {
            // Pastikan nilai dan nilai_b adalah angka valid
            if (!empty($row_week->id_lap) && $row_week->jenis !== 'TIDAK ADA PEKERJAAN DI MINGGU INI') {
                $total_nilai_week += (float)$row_week->nilai;
                $total_nilai_b_week += (float)$row_week->nilai_b;
                $jumlah_data_week++; // Hanya hitung jika data valid
            }
        }
        // Menghitung rata-rata hanya jika ada data valid
        $nilaiRata_week = ($jumlah_data_week > 0) ? ($total_nilai_week / $jumlah_data_week) : 0;
        $nilaiRata_b_week = ($jumlah_data_week > 0) ? ($total_nilai_b_week / $jumlah_data_week) : 0;
        $rataNilaiWeek = ($jumlah_data_week > 0) ? ($nilaiRata_week + $nilaiRata_b_week)/2 : 0;
        $page_data['nilai_week'] = $rataNilaiWeek;

        //Nilai Pencapaian Per Minggu
        $data_pencapaian_week = $this->md_dashboard->getNilaiPencapaianPerWeek(sessPenggunaId(), $monday, $sunday);
        $total_nilai_pweek = 0;
        $total_nilai_b_pweek = 0;
        $jumlah_data_pweek = 0;
        foreach ($data_pencapaian_week as $row_pweek) {
            // Pastikan nilai dan nilai_b adalah angka valid
            if (!empty($row_pweek->id_lap)) {
                $total_nilai_pweek += (float)$row_pweek->nilai_pencapaian_a;
                $total_nilai_b_pweek += (float)$row_pweek->nilai_pencapaian_b;
                $jumlah_data_pweek++; // Hanya hitung jika data valid
            }
        }
        // Menghitung rata-rata hanya jika ada data valid
        $nilaiRata_pweek = ($jumlah_data_pweek > 0) ? ($total_nilai_pweek / $jumlah_data_pweek) : 0;
        $nilaiRata_b_pweek = ($jumlah_data_pweek > 0) ? ($total_nilai_b_pweek / $jumlah_data_pweek) : 0;
        $rataNilaiPWeek = ($jumlah_data_pweek > 0) ? ($nilaiRata_pweek + $nilaiRata_b_pweek)/2 : 0;
        $page_data['nilai_pencapaian_week'] = $rataNilaiPWeek;
        */

        //====== Display Tabel =====
        $tanggal_awal = date('Y-m-01', strtotime($month));
        $tanggal_akhir = date('Y-m-t', strtotime($month));

        $start = strtotime('monday this week', strtotime($tanggal_awal));
        $end = strtotime($tanggal_akhir);
        $data_mingguan = [];

        $list_pengguna = $this->md_dashboard->getBywhereActive();


        while ($start <= $end) {
            $monday = date('Y-m-d', $start);
            $sunday = date('Y-m-d', strtotime('sunday this week', $start));

            // Ambil data laporan
            $data_nilai_week = $this->md_dashboard->getNilaiDataPerWeek(sessPenggunaId(), $monday, $sunday);
            $total_nilai = 0;
            $total_nilai_b = 0;
            $jumlah_data = 0;

            foreach ($data_nilai_week as $row) {
                if (!empty($row->id_lap) && $row->jenis !== 'TIDAK ADA PEKERJAAN DI MINGGU INI') {
                    $total_nilai += (float)$row->nilai;
                    $total_nilai_b += (float)$row->nilai_b;
                    $jumlah_data++;
                }
            }

            $rata_laporan = $jumlah_data > 0 ? ($total_nilai + $total_nilai_b) / (2 * $jumlah_data) : 0;

            // Ambil data pencapaian
            $data_pencapaian = $this->md_dashboard->getNilaiPencapaianPerWeek(sessPenggunaId(), $monday, $sunday);
            $total_p_a = 0;
            $total_p_b = 0;
            $jumlah_p = 0;

            foreach ($data_pencapaian as $row) {
                if (!empty($row->id_lap)) {
                    $total_p_a += (float)$row->nilai_pencapaian_a;
                    $total_p_b += (float)$row->nilai_pencapaian_b;
                    $jumlah_p++;
                }
            }

            $rata_pencapaian = $jumlah_p > 0 ? ($total_p_a + $total_p_b) / (2 * $jumlah_p) : 0;


            // Ambil data laporan NEW
            $data_nilai_week2 = $this->md_dashboard->getNilaiNewPerWeek(sessPenggunaId(), $monday, $sunday);
            $total_nilai_a2 = 0;
            $total_nilai_b2 = 0;
            $jumlah_data2 = 0;

            foreach ($data_nilai_week2 as $row2) {
                $total_nilai_a2 += (float)$row2->nilai_a;
                $total_nilai_b2 += (float)$row2->nilai_b;
                $jumlah_data2++;
            }

            $rata_laporan2 = $jumlah_data2 > 0 ? ($total_nilai_a2 + $total_nilai_b2) / (2 * $jumlah_data2) : 0;

            // Ambil data penilaian umum NEW
            $data_nilai_week3 = $this->md_dashboard->getPenilauanUmumPerWeek(sessPenggunaId(), $monday, $sunday);
            $total_nilaia1 = 0;
            $total_nilaia2 = 0;
            $total_nilaia3 = 0;
            $total_nilaia4 = 0;
            $total_nilaia5 = 0;
            $total_nilaib1 = 0;
            $total_nilaib2 = 0;
            $total_nilaib3 = 0;
            $total_nilaib4 = 0;
            $total_nilaib5 = 0;
            $jumlah_data3 = 0;

            foreach ($data_nilai_week3 as $row3) {
                $total_nilaia1 += (float)$row3->nilaia1;
                $total_nilaia2 += (float)$row3->nilaia2;
                $total_nilaia3 += (float)$row3->nilaia3;
                $total_nilaia4 += (float)$row3->nilaia4;
                $total_nilaia5 += (float)$row3->nilaia5;
                $total_nilaib1 += (float)$row3->nilaib1;
                $total_nilaib2 += (float)$row3->nilaib2;
                $total_nilaib3 += (float)$row3->nilaib3;
                $total_nilaib4 += (float)$row3->nilaib4;
                $total_nilaib5 += (float)$row3->nilaib5;
                $jumlah_data3++;
            }

            $rata_penilaian_umum = $jumlah_data3 > 0 ? ($total_nilaia1 + $total_nilaia2 + $total_nilaia3 + $total_nilaia4 + $total_nilaia5 + $total_nilaib1 + $total_nilaib2 + $total_nilaib3 + $total_nilaib4 + $total_nilaib5) / (10 * $jumlah_data3) : 0;
            $rata_lap_mingguan = (floatval($rata_laporan2) + floatval($rata_penilaian_umum)) / 2;

            // Ambil data pencapaian New
            $data_pencapaian2 = $this->md_dashboard->getPencapaianPerWeek(sessPenggunaId(), $monday, $sunday);
            $total_pa = 0;
            $total_pb = 0;
            $jumlah_penc = 0;

            foreach ($data_pencapaian2 as $row2) {
                $total_pa += (float)$row2->nilai_a;
                $total_pb += (float)$row2->nilai_b;
                $jumlah_penc++;
            }

            $rata_pencapaian2 = $jumlah_penc > 0 ? ($total_pa + $total_pb) / (2 * $jumlah_penc) : 0;







            /*$data_mingguan[] = [
                'periode' => date('d-m-Y', strtotime($monday)) . ' s/d ' . date('d-m-Y', strtotime($sunday)),
                'nilai_laporan' => $rata_laporan,
                'nilai_pencapaian' => $rata_pencapaian
            ];*/


            //ADMIN NILAI
            foreach ($list_pengguna as $pengguna) {
                $id = $pengguna->pengguna_id;

                $nilai_lap_adm = $this->md_dashboard->getNilaiLaporan($id, $monday, $sunday);

                $nilai_pen_adm = $this->md_dashboard->getNilaiPenilaianUmum($id, $monday, $sunday);

                $nilai_penc_adm = $this->md_dashboard->getNilaiPencapaianAdm($id, $monday, $sunday);


                $nilai_laporan_adm[$id] = (float)($nilai_lap_adm ?? 0);
                $nilai_penilaianumum_adm[$id] = (float)($nilai_pen_adm ?? 0);
                $nilai_pencapaian_adm[$id] = (float)($nilai_penc_adm ?? 0);

                // Rata-rata Laporan dan Penilaian Umum
                $rata_lap_adms[$id] = round((($nilai_laporan_adm[$id] + $nilai_penilaianumum_adm[$id]) / 2), 2);
            }

            //NILAI ADMIN

            $tanggal_mingguan = date('Y-m-d', strtotime($monday));

            if ($tanggal_mingguan >= '2025-06-30') {
                $nilai_laporan = $rata_lap_mingguan;
                $nilai_pencapaian = $rata_pencapaian2;
            } else {
                $nilai_laporan = $rata_laporan;
                $nilai_pencapaian = $rata_pencapaian;
            }



            $data_mingguan[] = [
                'periode' => date('d-m-Y', strtotime($monday)) . ' s/d ' . date('d-m-Y', strtotime($sunday)),
                'nilai_laporan' => $nilai_laporan,
                'nilai_pencapaian' => $nilai_pencapaian,
                'nilai_laporan_adm' => $nilai_laporan_adm,
                'nilai_pencapaian_adm' => $nilai_pencapaian_adm,
                'nilai_penilaianumum_adm' => $nilai_penilaianumum_adm,
                'rata_lap_adms' => $rata_lap_adms
            ];


            $start = strtotime('+1 week', $start);
        }

        $page_data['data_mingguan'] = $data_mingguan;

        $page_data['list_pengguna'] = $list_pengguna;





        //Admin Data
        $month_adm = isset($_GET['bulan_adm']) ? $_GET['bulan_adm'] : date('Y-m');

        // Pecah $month
        list($tahun_adm, $bulanAngka_adm) = explode('-', $month_adm);

        // Array nama bulan Indonesia
        $namaBulan_adm = [
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

        // Gabungkan
        $page_data['bulanIniAdm'] = $namaBulan_adm[$bulanAngka_adm] . ' ' . $tahun_adm;













        $this->load->view('index', $page_data);
    }


    public function sisa_cuti_karyawan()
    {
        grantAccessFor('all');

        $is_allowed = isAdmin() || isHrd() || isGa() || sessPenggunaId() == 92;
        if (!$is_allowed) {
            redirect(base_url('dashboard_kepegawaian'));
            return;
        }

        $tahun = (int) $this->input->get('tahun');
        if ($tahun <= 0) {
            $tahun = (int) date('Y');
        }

        $pemerintah = 8;

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'kepegawaian/v_sisa_cuti_karyawan';
        $page_data['page_title'] = 'Daftar Sisa Cuti Karyawan';
        $page_data['page_desc'] = 'Rekap sisa cuti tahunan seluruh karyawan';
        $page_data['tahun_berjalan'] = $tahun;
        $page_data['pemerintah'] = $pemerintah;
        $page_data['rekap_sisa_cuti_karyawan'] = $this->md_dashboard->getRekapSisaCutiKaryawanTahunBerjalan($pemerintah, $tahun);

        $this->load->view('index', $page_data);
    }

    public function detail_cuti_diambil()
    {
        grantAccessFor('all');

        $is_allowed = isAdmin() || isHrd() || isGa() || sessPenggunaId() == 92;
        if (!$is_allowed) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'status' => 'error',
                    'message' => 'Akses ditolak',
                )));
            return;
        }

        $pengguna_id = (int) $this->input->post('pengguna_id', true);
        $tahun = (int) $this->input->post('tahun', true);

        if ($pengguna_id <= 0) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'status' => 'error',
                    'message' => 'Parameter pengguna tidak valid',
                )));
            return;
        }

        if ($tahun <= 0) {
            $tahun = (int) date('Y');
        }

        $detail = $this->md_dashboard->getDetailPengajuanCutiTahunanKaryawan($pengguna_id, $tahun);
        $summary = $this->md_dashboard->getSummaryPengajuanCutiTahunanKaryawan($pengguna_id, $tahun);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => 'success',
                'message' => 'Data detail cuti berhasil diambil',
                'data' => $detail,
                'summary' => $summary,
                'tahun' => $tahun,
            )));
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