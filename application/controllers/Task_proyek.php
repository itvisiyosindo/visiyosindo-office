<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task_proyek extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pengguna');
        $this->load->helper('whatsapp_helper');
        $this->_ensureTablesExist();
    }

    private function _ensureTablesExist()
    {
        $this->db->db_debug = FALSE;
        @$this->db->query("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_general_ci'");

        $sql1 = "CREATE TABLE IF NOT EXISTS `task_proyek` (
          `id_task` int(11) NOT NULL AUTO_INCREMENT,
          `kode_task` varchar(50) NOT NULL,
          `judul` varchar(255) NOT NULL,
          `nama_proyek` varchar(200) DEFAULT NULL,
          `jenis` varchar(50) DEFAULT 'Task Harian',
          `prioritas` varchar(20) DEFAULT 'Medium',
          `deskripsi` text DEFAULT NULL,
          `checklist_json` text DEFAULT NULL,
          `id_pemberi` int(11) NOT NULL,
          `id_penerima` int(11) NOT NULL,
          `tgl_mulai` date DEFAULT NULL,
          `tgl_deadline` date DEFAULT NULL,
          `lampiran_atasan` text DEFAULT NULL,
          `progres` int(3) DEFAULT 0,
          `status_task` tinyint(2) DEFAULT 0,
          `catatan_bawahan` text DEFAULT NULL,
          `bukti_selesai` text DEFAULT NULL,
          `tgl_submit` datetime DEFAULT NULL,
          `catatan_revisi` text DEFAULT NULL,
          `rating_bintang` tinyint(1) DEFAULT NULL,
          `ulasan_atasan` text DEFAULT NULL,
          `tgl_dinilai` datetime DEFAULT NULL,
          `is_deleted` tinyint(1) DEFAULT 0,
          `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
          `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id_task`),
          KEY `idx_pemberi` (`id_pemberi`),
          KEY `idx_penerima` (`id_penerima`),
          KEY `idx_status` (`status_task`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        $sql2 = "CREATE TABLE IF NOT EXISTS `task_proyek_log` (
          `id_log` int(11) NOT NULL AUTO_INCREMENT,
          `id_task` int(11) NOT NULL,
          `id_pengguna` int(11) NOT NULL,
          `aksi` varchar(100) NOT NULL,
          `catatan` text DEFAULT NULL,
          `lampiran` text DEFAULT NULL,
          `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id_log`),
          KEY `idx_task` (`id_task`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        @$this->db->query($sql1);
        @$this->db->query($sql2);
    }

    private function _canViewAllTasks($myId)
    {
        return isAdmin() || isHrd() || isEksekutif() || in_array((int)$myId, [1, 23, 33, 54, 55, 56, 58, 69, 744], true);
    }

    public function index()
    {
        grantAccessFor('all');

        $myId = (int)sessPenggunaId();
        $nav  = 'kepegawaian';

        $filterBulan = $this->input->get('bulan', TRUE);
        $filterTahun = $this->input->get('tahun', TRUE);
        if ($filterTahun === null || $filterTahun === '') {
            $filterTahun = date('Y');
        }

        // Pastikan kolom id_atasan tersedia di tabel pengguna
        $chkAtasan = @$this->db->query("SHOW COLUMNS FROM `pengguna` LIKE 'id_atasan'");
        if ($chkAtasan && $chkAtasan->num_rows() === 0) {
            @$this->db->query("ALTER TABLE `pengguna` ADD COLUMN `id_atasan` INT(11) DEFAULT NULL AFTER `hirarki`, ADD INDEX `idx_id_atasan` (`id_atasan`)");
        }

        // Info user login saat ini beserta nama atasan langsungnya
        $me = $this->db->select('p.*, d.nama as nama_divisi, pa.nama as nama_atasan, pa.jabatan as jabatan_atasan')
            ->from('pengguna p')
            ->join('divisi d', 'd.id_divisi = p.id_divisi', 'left')
            ->join('pengguna pa', 'pa.pengguna_id = p.id_atasan', 'left')
            ->where('p.pengguna_id', $myId)
            ->get()
            ->row();

        $myDivisi = ($me && !empty($me->id_divisi)) ? (int)$me->id_divisi : 0;
        $canViewAll = $this->_canViewAllTasks($myId);

        // 1. Daftar semua karyawan aktif (untuk pilihan Atasan Saya di modal & opsi Admin)
        $list_semua_karyawan = $this->db->query("
            SELECT p.pengguna_id, p.nama, p.jabatan, p.id_divisi, d.nama AS nama_divisi
            FROM pengguna p
            LEFT JOIN divisi d ON d.id_divisi = p.id_divisi
            WHERE p.status = 1 AND p.is_active = 1 AND p.pengguna_id != {$myId}
            ORDER BY p.nama ASC
        ")->result();

        // 2. Daftar karyawan yang HANYA dibawahi langsung oleh user saat ini (id_atasan = $myId)
        $list_bawahan = $this->db->query("
            SELECT p.pengguna_id, p.nama, p.jabatan, p.id_divisi, p.hirarki, p.id_atasan, d.nama AS nama_divisi
            FROM pengguna p
            LEFT JOIN divisi d ON d.id_divisi = p.id_divisi
            WHERE p.status = 1 AND p.is_active = 1 AND p.pengguna_id != 1 AND p.id_atasan = {$myId}
            ORDER BY p.nama ASC
        ")->result();

        // 1. Tugas Saya (Sebagai Penerima / Bawahan)
        $this->db->select('t.*, p_beri.nama AS nama_pemberi, p_beri.jabatan AS jabatan_pemberi, p_terima.nama AS nama_penerima, p_terima.jabatan AS jabatan_penerima')
            ->from('task_proyek t')
            ->join('pengguna p_beri', 'p_beri.pengguna_id = t.id_pemberi', 'left')
            ->join('pengguna p_terima', 'p_terima.pengguna_id = t.id_penerima', 'left')
            ->where('t.is_deleted', 0)
            ->where('t.id_penerima', $myId);
        if (!empty($filterBulan)) {
            $this->db->where('MONTH(t.created_at)', (int)$filterBulan);
        }
        if (!empty($filterTahun) && $filterTahun !== 'all') {
            $this->db->where('YEAR(t.created_at)', (int)$filterTahun);
        }
        $tugas_saya = $this->db->order_by('FIELD(t.status_task, 3, 0, 1, 2, 4)', '', FALSE)
            ->order_by('t.id_task', 'DESC')
            ->get()
            ->result();

        // 2. Tugas yang Saya Berikan (Sebagai Atasan) / Semua Tugas jika Admin/Direksi/HRD
        $viewMode = $this->input->get('mode', TRUE);
        $this->db->select('t.*, p_beri.nama AS nama_pemberi, p_beri.jabatan AS jabatan_pemberi, p_terima.nama AS nama_penerima, p_terima.jabatan AS jabatan_penerima, d.nama AS divisi_penerima')
            ->from('task_proyek t')
            ->join('pengguna p_beri', 'p_beri.pengguna_id = t.id_pemberi', 'left')
            ->join('pengguna p_terima', 'p_terima.pengguna_id = t.id_penerima', 'left')
            ->join('divisi d', 'd.id_divisi = p_terima.id_divisi', 'left')
            ->where('t.is_deleted', 0);

        if (!$canViewAll || $viewMode === 'mine') {
            $this->db->where('t.id_pemberi', $myId);
        }
        if (!empty($filterBulan)) {
            $this->db->where('MONTH(t.created_at)', (int)$filterBulan);
        }
        if (!empty($filterTahun) && $filterTahun !== 'all') {
            $this->db->where('YEAR(t.created_at)', (int)$filterTahun);
        }
        $tugas_atasan = $this->db->order_by('FIELD(t.status_task, 2, 1, 3, 0, 4)', '', FALSE)
            ->order_by('t.id_task', 'DESC')
            ->get()
            ->result();

        // 3. KPI Ringkasan Personal & Atasan
        $kpi_my_active = 0;
        $kpi_my_done   = 0;
        $kpi_my_waiting_review = 0;
        $kpi_my_stars_sum = 0;
        $kpi_my_stars_cnt = 0;
        foreach ($tugas_saya as $ts) {
            if ((int)$ts->status_task < 4) {
                $kpi_my_active++;
                if ((int)$ts->status_task === 2) {
                    $kpi_my_waiting_review++;
                }
            } else {
                $kpi_my_done++;
                if (!empty($ts->rating_bintang)) {
                    $kpi_my_stars_sum += (int)$ts->rating_bintang;
                    $kpi_my_stars_cnt++;
                }
            }
        }
        $kpi_my_avg_star = $kpi_my_stars_cnt > 0 ? round($kpi_my_stars_sum / $kpi_my_stars_cnt, 2) : 0;

        $kpi_need_review  = 0;
        $kpi_given_active = 0;
        $kpi_given_done   = 0;
        foreach ($tugas_atasan as $ta) {
            if ((int)$ta->status_task === 2) {
                $kpi_need_review++;
            }
            if ((int)$ta->status_task < 4) {
                $kpi_given_active++;
            } else {
                $kpi_given_done++;
            }
        }

        // 4. Leaderboard & Rekap Rating Kinerja Karyawan (70% Task Proyek : 30% Laporan Mingguan)
        $wherePeriodeTask = "";
        if (!empty($filterBulan)) {
            $wherePeriodeTask .= " AND MONTH(t.created_at) = " . (int)$filterBulan;
        }
        if (!empty($filterTahun) && $filterTahun !== 'all') {
            $wherePeriodeTask .= " AND YEAR(t.created_at) = " . (int)$filterTahun;
        }

        // Tentukan rentang tanggal untuk filter laporan mingguan
        $wherePeriodeNp = "";
        $wherePeriodeLap = "";
        if (!empty($filterBulan) && !empty($filterTahun) && $filterTahun !== 'all') {
            $m = str_pad((int)$filterBulan, 2, '0', STR_PAD_LEFT);
            $startDate = "{$filterTahun}-{$m}-01";
            $endDate = date('Y-m-t', strtotime($startDate));
            $wherePeriodeNp  = " AND date >= '{$startDate}' AND date <= '{$endDate}'";
            $wherePeriodeLap = " AND tanggal >= '{$startDate}' AND tanggal <= '{$endDate}'";
        } elseif (!empty($filterTahun) && $filterTahun !== 'all') {
            $wherePeriodeNp  = " AND YEAR(date) = " . (int)$filterTahun;
            $wherePeriodeLap = " AND YEAR(tanggal) = " . (int)$filterTahun;
        } elseif (!empty($filterBulan)) {
            $wherePeriodeNp  = " AND MONTH(date) = " . (int)$filterBulan;
            $wherePeriodeLap = " AND MONTH(tanggal) = " . (int)$filterBulan;
        }

        $raw_leaderboard = $this->db->query("
            SELECT 
                p.pengguna_id,
                p.nama,
                p.jabatan,
                d.nama AS nama_divisi,
                COALESCE(tsk.total_task, 0) AS total_task,
                COALESCE(tsk.task_selesai, 0) AS task_selesai,
                COALESCE(tsk.task_aktif, 0) AS task_aktif,
                COALESCE(tsk.task_ontime, 0) AS task_ontime,
                tsk.avg_bintang,
                COALESCE(tsk.bintang_5, 0) AS bintang_5,
                COALESCE(np.avg_nilai_point, lap.avg_nilai_lap) AS avg_nilai_laporan,
                COALESCE(np.total_poin_laporan, lap.total_poin_lap, 0) AS total_poin_laporan
            FROM pengguna p
            LEFT JOIN divisi d ON d.id_divisi = p.id_divisi
            LEFT JOIN (
                SELECT 
                    t.id_penerima,
                    COUNT(t.id_task) AS total_task,
                    SUM(CASE WHEN t.status_task = 4 THEN 1 ELSE 0 END) AS task_selesai,
                    SUM(CASE WHEN t.status_task < 4 THEN 1 ELSE 0 END) AS task_aktif,
                    SUM(CASE WHEN t.status_task = 4 AND (t.tgl_deadline IS NULL OR DATE(COALESCE(t.tgl_submit, t.tgl_dinilai)) <= t.tgl_deadline) THEN 1 ELSE 0 END) AS task_ontime,
                    ROUND(AVG(CASE WHEN t.status_task = 4 AND t.rating_bintang IS NOT NULL THEN t.rating_bintang ELSE NULL END), 2) AS avg_bintang,
                    SUM(CASE WHEN t.status_task = 4 AND t.rating_bintang = 5 THEN 1 ELSE 0 END) AS bintang_5
                FROM task_proyek t
                WHERE t.is_deleted = 0 {$wherePeriodeTask}
                GROUP BY t.id_penerima
            ) tsk ON tsk.id_penerima = p.pengguna_id
            LEFT JOIN (
                SELECT 
                    id_pengguna,
                    COUNT(*) AS total_poin_laporan,
                    ROUND(AVG(
                        CASE 
                            WHEN (nilai_a IS NOT NULL AND nilai_a > 0 AND nilai_a <= 100) 
                                 AND (nilai_b IS NOT NULL AND nilai_b > 0 AND nilai_b <= 100) 
                            THEN (CAST(nilai_a AS DECIMAL(5,2)) + CAST(nilai_b AS DECIMAL(5,2))) / 2
                            WHEN (nilai_b IS NOT NULL AND nilai_b > 0 AND nilai_b <= 100) 
                            THEN CAST(nilai_b AS DECIMAL(5,2))
                            WHEN (nilai_a IS NOT NULL AND nilai_a > 0 AND nilai_a <= 100) 
                            THEN CAST(nilai_a AS DECIMAL(5,2))
                            ELSE NULL 
                        END
                    ), 1) AS avg_nilai_point
                FROM nilai_point
                WHERE 1=1 {$wherePeriodeNp}
                GROUP BY id_pengguna
            ) np ON np.id_pengguna = p.pengguna_id
            LEFT JOIN (
                SELECT 
                    id_pengaju,
                    COUNT(*) AS total_poin_lap,
                    ROUND(AVG(
                        CASE 
                            WHEN (nilai > 0 AND nilai <= 100) AND (nilai_b > 0 AND nilai_b <= 100) 
                            THEN (CAST(nilai AS DECIMAL(5,2)) + CAST(nilai_b AS DECIMAL(5,2))) / 2
                            WHEN (nilai_b > 0 AND nilai_b <= 100) 
                            THEN CAST(nilai_b AS DECIMAL(5,2))
                            WHEN (nilai > 0 AND nilai <= 100) 
                            THEN CAST(nilai AS DECIMAL(5,2))
                            ELSE NULL 
                        END
                    ), 1) AS avg_nilai_lap
                FROM laporan
                WHERE status != 3 {$wherePeriodeLap}
                GROUP BY id_pengaju
            ) lap ON lap.id_pengaju = p.pengguna_id
            WHERE p.status = 1 AND (tsk.total_task > 0 OR np.avg_nilai_point IS NOT NULL OR lap.avg_nilai_lap IS NOT NULL)
        ")->result();

        $leaderboard = [];
        $myCombinedKpi = null;

        foreach ($raw_leaderboard as $lb) {
            $hasTask = ($lb->avg_bintang !== null && (int)$lb->task_selesai > 0);
            $hasLap  = ($lb->avg_nilai_laporan !== null);

            $skorTask = $hasTask ? round(((float)$lb->avg_bintang / 5) * 100, 1) : null;
            $skorLap  = $hasLap ? round((float)$lb->avg_nilai_laporan, 1) : null;

            if ($hasTask && $hasLap) {
                $skorAkhir = round(($skorTask * 0.70) + ($skorLap * 0.30), 1);
                $komposisi = '70% Task + 30% Laporan';
                $statusNilai = 'lengkap';
            } elseif ($hasTask && !$hasLap) {
                $skorAkhir = $skorTask;
                $komposisi = '100% Task Proyek';
                $statusNilai = 'task_only';
            } elseif (!$hasTask && $hasLap) {
                $skorAkhir = $skorLap;
                $komposisi = '100% Laporan Mingguan';
                $statusNilai = 'laporan_only';
            } else {
                $skorAkhir = null;
                $komposisi = 'Belum Ada Nilai';
                $statusNilai = 'belum_dinilai';
            }

            $ratingAgregat = $skorAkhir !== null ? round(($skorAkhir / 100) * 5, 2) : 0;

            if ($skorAkhir >= 85) {
                $grade = 'A';
                $gradeLabel = 'Sangat Baik';
                $gradeBadge = 'badge-success';
                $gradeColor = '#16a34a';
            } elseif ($skorAkhir >= 75) {
                $grade = 'B';
                $gradeLabel = 'Baik';
                $gradeBadge = 'badge-primary';
                $gradeColor = '#2563eb';
            } elseif ($skorAkhir >= 65) {
                $grade = 'C';
                $gradeLabel = 'Cukup';
                $gradeBadge = 'badge-warning';
                $gradeColor = '#d97706';
            } elseif ($skorAkhir !== null) {
                $grade = 'D';
                $gradeLabel = 'Kurang (PIP)';
                $gradeBadge = 'badge-danger';
                $gradeColor = '#dc2626';
            } else {
                $grade = '-';
                $gradeLabel = 'Belum Dinilai';
                $gradeBadge = 'badge-secondary';
                $gradeColor = '#64748b';
            }

            $lb->skor_task      = $skorTask;
            $lb->skor_lap       = $skorLap;
            $lb->skor_akhir     = $skorAkhir;
            $lb->rating_agregat = $ratingAgregat;
            $lb->grade          = $grade;
            $lb->grade_label    = $gradeLabel;
            $lb->grade_badge    = $gradeBadge;
            $lb->grade_color    = $gradeColor;
            $lb->komposisi      = $komposisi;
            $lb->status_nilai   = $statusNilai;

            if ((int)$lb->pengguna_id === (int)$myId) {
                $myCombinedKpi = $lb;
            }

            $leaderboard[] = $lb;
        }

        // Urutkan Leaderboard berdasarkan:
        // 1. Skor Akhir tertinggi (NULL paling bawah)
        // 2. Task selesai terbanyak
        // 3. Total task terbanyak
        usort($leaderboard, function($a, $b) {
            if ($a->skor_akhir === null && $b->skor_akhir === null) return 0;
            if ($a->skor_akhir === null) return 1;
            if ($b->skor_akhir === null) return -1;

            if ($a->skor_akhir == $b->skor_akhir) {
                if ($a->task_selesai == $b->task_selesai) {
                    return (int)$b->total_task <=> (int)$a->total_task;
                }
                return (int)$b->task_selesai <=> (int)$a->task_selesai;
            }
            return ($b->skor_akhir < $a->skor_akhir) ? -1 : 1;
        });

        $page_data['switch']          = $nav;
        $page_data['page_name']       = 'task_proyek/v_task_proyek';
        $page_data['page_title']      = 'Manajemen Task & Proyek Kerja';
        $page_data['page_desc']       = 'Penugasan Atasan ke Karyawan, Monitoring Progres & Penilaian Rating Bintang 5';
        $page_data['me']              = $me;
        $page_data['myId']            = $myId;
        $page_data['canViewAll']      = $canViewAll;
        $page_data['isAdmin']         = (isAdmin() || (int)$myId === 1 || in_array((int)$this->session->userdata('id_role'), [1], true));
        $page_data['viewMode']        = $viewMode;
        $page_data['filterBulan']         = $filterBulan;
        $page_data['filterTahun']         = $filterTahun;
        $page_data['list_bawahan']        = $list_bawahan;
        $page_data['list_semua_karyawan'] = $list_semua_karyawan;
        $page_data['tugas_saya']      = $tugas_saya;
        $page_data['tugas_atasan']    = $tugas_atasan;
        $page_data['leaderboard']     = $leaderboard;
        $page_data['kpi'] = [
            'my_active'         => $kpi_my_active,
            'my_done'           => $kpi_my_done,
            'my_waiting_review' => $kpi_my_waiting_review,
            'my_avg_star'       => $kpi_my_avg_star,
            'my_star_cnt'       => $kpi_my_stars_cnt,
            'need_review'       => $kpi_need_review,
            'given_active'      => $kpi_given_active,
            'given_done'        => $kpi_given_done,
            'total_given'       => count($tugas_atasan),
            'my_combined'       => $myCombinedKpi
        ];

        $this->load->view('index', $page_data);
    }

    /**
     * Buat Task / Proyek Baru (bisa untuk 1 atau banyak bawahan sekaligus)
     */
    public function add()
    {
        grantAccessFor('all');
        $this->db->db_debug = FALSE;

        $myId            = (int)sessPenggunaId();
        $judul           = trim((string)$this->input->post('judul', FALSE));
        $nama_proyek     = trim((string)$this->input->post('nama_proyek', FALSE));
        $jenis           = trim((string)$this->input->post('jenis', TRUE)) ?: 'Task Harian';
        $prioritas       = trim((string)$this->input->post('prioritas', TRUE)) ?: 'Medium';
        $deskripsi       = trim((string)$this->input->post('deskripsi', FALSE));
        $tgl_mulai       = $this->input->post('tgl_mulai', TRUE) ?: date('Y-m-d');
        $tgl_deadline    = $this->input->post('tgl_deadline', TRUE) ?: date('Y-m-d', strtotime('+3 days'));
        $lampiran_atasan = trim((string)$this->input->post('lampiran_atasan', FALSE));
        $kirim_wa        = (int)$this->input->post('kirim_wa', TRUE) === 1;

        $penerima_list   = $this->input->post('id_penerima', TRUE);
        $checklist_raw   = $this->input->post('checklist_items', FALSE);

        if ($judul === '' || empty($penerima_list)) {
            ajaxReturnDie('error', 'Judul Task dan Penerima Tugas (Karyawan) wajib diisi!', FALSE);
            return;
        }

        if (!is_array($penerima_list)) {
            $penerima_list = [$penerima_list];
        }

        $isAdmin = (isAdmin() || (int)$myId === 1 || in_array((int)$this->session->userdata('id_role'), [1], true));

        // Validasi: Jika bukan Administrator, hanya boleh memberikan task kepada karyawan yang dibawahi langsung (id_atasan == $myId)
        foreach ($penerima_list as $id_penerima_raw) {
            $id_penerima = (int)$id_penerima_raw;
            if ($id_penerima <= 0) continue;

            $chkSub = $this->db->select('pengguna_id, nama, id_atasan')
                ->from('pengguna')
                ->where('pengguna_id', $id_penerima)
                ->where('status', 1)
                ->where('is_active', 1)
                ->get()
                ->row();

            if (!$chkSub) {
                ajaxReturnDie('error', 'Karyawan penerima tugas (ID: ' . $id_penerima . ') tidak ditemukan atau tidak aktif!', FALSE);
                return;
            }

            if (!$isAdmin && (int)$chkSub->id_atasan !== $myId) {
                $namaBawahan = $chkSub->nama;
                ajaxReturnDie('error', 'Anda tidak dapat memberikan task kepada ' . $namaBawahan . ' karena bukan merupakan bawahan langsung Anda!', FALSE);
                return;
            }
        }

        // Susun JSON checklist sub-task
        $checklist = [];
        if (is_array($checklist_raw)) {
            foreach ($checklist_raw as $item) {
                $cleanItem = trim((string)$item);
                if ($cleanItem !== '') {
                    $checklist[] = ['item' => $cleanItem, 'done' => 0];
                }
            }
        }
        $checklist_json = !empty($checklist) ? json_encode($checklist, JSON_UNESCAPED_UNICODE) : null;

        $pembuatRow = $this->db->select('nama, jabatan')->from('pengguna')->where('pengguna_id', $myId)->get()->row();
        $namaAtasan = $pembuatRow ? $pembuatRow->nama : 'Atasan';

        $createdCount = 0;
        foreach ($penerima_list as $id_penerima_raw) {
            $id_penerima = (int)$id_penerima_raw;
            if ($id_penerima <= 0) continue;

            // Generate kode task unik
            $lastRow = $this->db->select('id_task')->from('task_proyek')->order_by('id_task', 'DESC')->limit(1)->get()->row();
            $nextNum = ($lastRow ? (int)$lastRow->id_task : 0) + 1;
            $kode_task = sprintf('TSK/%s/%s/%03d', date('Y'), date('m'), $nextNum);

            $dataInsert = [
                'kode_task'       => $kode_task,
                'judul'           => $judul,
                'nama_proyek'     => $nama_proyek !== '' ? $nama_proyek : 'Umum',
                'jenis'           => $jenis,
                'prioritas'       => $prioritas,
                'deskripsi'       => $deskripsi,
                'checklist_json'  => $checklist_json,
                'id_pemberi'      => $myId,
                'id_penerima'     => $id_penerima,
                'tgl_mulai'       => date('Y-m-d', strtotime($tgl_mulai)),
                'tgl_deadline'    => date('Y-m-d', strtotime($tgl_deadline)),
                'lampiran_atasan' => $lampiran_atasan,
                'progres'         => 0,
                'status_task'     => 0
            ];

            if ($this->db->insert('task_proyek', $dataInsert)) {
                $id_task = $this->db->insert_id();
                $createdCount++;

                // Catat log pembuatan
                $this->db->insert('task_proyek_log', [
                    'id_task'     => $id_task,
                    'id_pengguna' => $myId,
                    'aksi'        => 'Membuat & Menugaskan Task',
                    'catatan'     => 'Task "' . $judul . '" ditugaskan oleh ' . $namaAtasan . ' (Prioritas: ' . $prioritas . ', Deadline: ' . date('d-m-Y', strtotime($tgl_deadline)) . ').'
                ]);

                // Kirim WA ke bawahan jika dicentang
                if ($kirim_wa) {
                    try {
                        $bawahan = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $id_penerima)->get()->row();
                        $noHp = ($bawahan && !empty($bawahan->no_hp)) ? $bawahan->no_hp : (($bawahan && !empty($bawahan->nohp)) ? $bawahan->nohp : '');
                        if (!empty($noHp) && function_exists('sendWa')) {
                            $pesanWa = "*📋 PENUGASAN TASK / PROYEK BARU*" .
                                "%0A%0AHalo *" . $bawahan->nama . "*," .
                                "%0AAnda menerima penugasan baru dari *" . $namaAtasan . "*:" .
                                "%0A%0A🔹 *Kode*: " . $kode_task .
                                "%0A📌 *Judul*: " . $judul .
                                "%0A📁 *Proyek*: " . ($nama_proyek ?: 'Umum') .
                                "%0A⚡ *Prioritas*: " . $prioritas .
                                "%0A📅 *Deadline*: " . date('d-m-Y', strtotime($tgl_deadline)) .
                                "%0A%0ASilakan buka menu *Task & Proyek Kerja* di https://office.visiyosindo.id/task_proyek untuk mulai mengerjakan.";
                            @sendWa([
                                'devId'    => hostWa('2'),
                                'penerima' => $noHp,
                                'pesan'    => $pesanWa
                            ]);
                        }
                    } catch (\Throwable $e) {
                    }
                }
            }
        }

        if ($createdCount > 0) {
            try {
                addLog('Buat Task Proyek', 'Menugaskan Task "' . $judul . '" kepada ' . $createdCount . ' karyawan');
            } catch (\Throwable $e) {
            }
            ajaxReturnDie('success', 'Berhasil membuat ' . $createdCount . ' penugasan Task / Proyek baru!', TRUE);
        } else {
            ajaxReturnDie('error', 'Gagal menyimpan penugasan Task.', FALSE);
        }
    }

    /**
     * Ambil Detail Task + Checklist + Riwayat Log via AJAX
     */
    public function get_detail()
    {
        grantAccessFor('all');

        $id_task = (int)$this->input->post('id_task', TRUE);
        $task = $this->db->select('t.*, p_beri.nama AS nama_pemberi, p_beri.jabatan AS jabatan_pemberi, p_terima.nama AS nama_penerima, p_terima.jabatan AS jabatan_penerima')
            ->from('task_proyek t')
            ->join('pengguna p_beri', 'p_beri.pengguna_id = t.id_pemberi', 'left')
            ->join('pengguna p_terima', 'p_terima.pengguna_id = t.id_penerima', 'left')
            ->where('t.id_task', $id_task)
            ->where('t.is_deleted', 0)
            ->get()
            ->row();

        if (!$task) {
            ajaxReturnDie('error', 'Data Task tidak ditemukan.', FALSE);
            return;
        }

        $logs = $this->db->select('l.*, p.nama AS nama_pengguna, p.jabatan')
            ->from('task_proyek_log l')
            ->join('pengguna p', 'p.pengguna_id = l.id_pengguna', 'left')
            ->where('l.id_task', $id_task)
            ->order_by('l.id_log', 'DESC')
            ->get()
            ->result();

        $task->checklist = !empty($task->checklist_json) ? json_decode($task->checklist_json, true) : [];
        $task->tgl_mulai_fmt = !empty($task->tgl_mulai) ? date('d-m-Y', strtotime($task->tgl_mulai)) : '-';
        $task->tgl_deadline_fmt = !empty($task->tgl_deadline) ? date('d-m-Y', strtotime($task->tgl_deadline)) : '-';
        $task->is_overdue = (!empty($task->tgl_deadline) && (int)$task->status_task < 4 && date('Y-m-d') > $task->tgl_deadline);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'task'   => $task,
            'logs'   => $logs,
            'my_id'  => (int)sessPenggunaId(),
            'is_admin_reviewer' => $this->_canViewAllTasks((int)sessPenggunaId())
        ]);
        exit;
    }

    /**
     * Bawahan: Update Progres / Checklist / Komentar atau Mulai Kerjakan
     */
    public function update_progres()
    {
        grantAccessFor('all');

        $myId           = (int)sessPenggunaId();
        $id_task        = (int)$this->input->post('id_task', TRUE);
        $progres        = (int)$this->input->post('progres', TRUE);
        $catatan        = trim((string)$this->input->post('catatan', FALSE));
        $checklist_done = $this->input->post('checklist_done', TRUE);

        $task = $this->db->get_where('task_proyek', ['id_task' => $id_task, 'is_deleted' => 0])->row();
        if (!$task) {
            ajaxReturnDie('error', 'Task tidak ditemukan.', FALSE);
            return;
        }

        if ($progres < 0) $progres = 0;
        if ($progres > 100) $progres = 100;

        // Update checklist JSON jika ada
        $checklist = !empty($task->checklist_json) ? json_decode($task->checklist_json, true) : [];
        if (!empty($checklist) && is_array($checklist)) {
            $doneIndices = is_array($checklist_done) ? array_map('intval', $checklist_done) : [];
            $doneCount = 0;
            foreach ($checklist as $idx => &$item) {
                $item['done'] = in_array($idx, $doneIndices, true) ? 1 : 0;
                if ($item['done']) $doneCount++;
            }
            unset($item);
            // Jika progres belum diubah manual tapi ada checklist yang dicentang, sesuaikan otomatis
            if (count($checklist) > 0 && $this->input->post('auto_calc_checklist') == '1') {
                $progres = (int)round(($doneCount / count($checklist)) * 100);
            }
        }

        $newStatus = (int)$task->status_task;
        if ($newStatus === 0 || $newStatus === 3) {
            $newStatus = 1; // In Progress
            if ($progres === 0) $progres = 10;
        }

        $this->db->where('id_task', $id_task)->update('task_proyek', [
            'progres'        => $progres,
            'status_task'    => $newStatus,
            'checklist_json' => !empty($checklist) ? json_encode($checklist, JSON_UNESCAPED_UNICODE) : $task->checklist_json
        ]);

        $this->db->insert('task_proyek_log', [
            'id_task'     => $id_task,
            'id_pengguna' => $myId,
            'aksi'        => 'Update Progres (' . $progres . '%)',
            'catatan'     => $catatan !== '' ? $catatan : 'Memperbarui progres pengerjaan menjadi ' . $progres . '%.'
        ]);

        ajaxReturnDie('success', 'Progres pengerjaan berhasil diperbarui (' . $progres . '%)!', TRUE);
    }

    /**
     * Bawahan: Submit Hasil Kerja ke Atasan untuk Dinilai Bintang 5
     */
    public function submit_selesai()
    {
        grantAccessFor('all');

        $myId            = (int)sessPenggunaId();
        $id_task         = (int)$this->input->post('id_task', TRUE);
        $catatan_bawahan = trim((string)$this->input->post('catatan_bawahan', FALSE));
        $bukti_selesai   = trim((string)$this->input->post('bukti_selesai', FALSE));

        if ($catatan_bawahan === '') {
            ajaxReturnDie('error', 'Harap isi ringkasan hasil pengerjaan terlebih dahulu!', FALSE);
            return;
        }

        $task = $this->db->get_where('task_proyek', ['id_task' => $id_task, 'is_deleted' => 0])->row();
        if (!$task) {
            ajaxReturnDie('error', 'Task tidak ditemukan.', FALSE);
            return;
        }

        // Tandai semua checklist selesai
        $checklist = !empty($task->checklist_json) ? json_decode($task->checklist_json, true) : [];
        if (!empty($checklist) && is_array($checklist)) {
            foreach ($checklist as &$item) {
                $item['done'] = 1;
            }
            unset($item);
        }

        $this->db->where('id_task', $id_task)->update('task_proyek', [
            'progres'         => 100,
            'status_task'     => 2, // Menunggu Review & Penilaian Bintang Atasan
            'catatan_bawahan' => $catatan_bawahan,
            'bukti_selesai'   => $bukti_selesai,
            'tgl_submit'      => date('Y-m-d H:i:s'),
            'checklist_json'  => !empty($checklist) ? json_encode($checklist, JSON_UNESCAPED_UNICODE) : $task->checklist_json
        ]);

        $this->db->insert('task_proyek_log', [
            'id_task'     => $id_task,
            'id_pengguna' => $myId,
            'aksi'        => 'Submit Hasil Kerja (100%)',
            'catatan'     => $catatan_bawahan,
            'lampiran'    => $bukti_selesai
        ]);

        // Kirim WA ke Atasan bahwa task sudah selesai dan siap dinilai bintang
        try {
            $atasan = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $task->id_pemberi)->get()->row();
            $bawahan = $this->db->select('nama')->from('pengguna')->where('pengguna_id', $myId)->get()->row();
            $noHpAtasan = ($atasan && !empty($atasan->no_hp)) ? $atasan->no_hp : (($atasan && !empty($atasan->nohp)) ? $atasan->nohp : '');
            if (!empty($noHpAtasan) && function_exists('sendWa')) {
                $pesanWa = "*✅ LAPORAN PENYELESAIAN TASK (MENUNGGU RATING ⭐)*" .
                    "%0A%0AHalo *" . $atasan->nama . "*," .
                    "%0AKaryawan (*" . ($bawahan ? $bawahan->nama : 'Karyawan') . "*) telah menyelesaikan Task:" .
                    "%0A%0A🔹 *Kode*: " . $task->kode_task .
                    "%0A📌 *Judul*: " . $task->judul .
                    "%0A📝 *Hasil*: " . $catatan_bawahan .
                    ($bukti_selesai ? "%0A📎 *Bukti*: " . $bukti_selesai : "") .
                    "%0A%0ASilakan berikan *Penilaian Rating Bintang (1-5 ⭐)* di https://office.visiyosindo.id/task_proyek";
                @sendWa([
                    'devId'    => hostWa('2'),
                    'penerima' => $noHpAtasan,
                    'pesan'    => $pesanWa
                ]);
            }
        } catch (\Throwable $e) {
        }

        try {
            addLog('Submit Task Selesai', 'Menyelesaikan Task ' . $task->kode_task . ' - ' . $task->judul);
        } catch (\Throwable $e) {
        }

        ajaxReturnDie('success', 'Hasil pekerjaan berhasil dikirim ke Atasan untuk dinilai!', TRUE);
    }

    /**
     * Atasan: Beri Rating Bintang (1-5) ATAU Minta Revisi
     */
    public function review_atasan()
    {
        grantAccessFor('all');

        $myId           = (int)sessPenggunaId();
        $id_task        = (int)$this->input->post('id_task', TRUE);
        $keputusan      = $this->input->post('keputusan', TRUE); // 'terima' | 'revisi'
        $rating_bintang = (int)$this->input->post('rating_bintang', TRUE);
        $ulasan_atasan  = trim((string)$this->input->post('ulasan_atasan', FALSE));

        $task = $this->db->get_where('task_proyek', ['id_task' => $id_task, 'is_deleted' => 0])->row();
        if (!$task) {
            ajaxReturnDie('error', 'Task tidak ditemukan.', FALSE);
            return;
        }

        if ((int)$task->id_pemberi !== $myId && !$this->_canViewAllTasks($myId)) {
            ajaxReturnDie('error', 'Hanya Atasan pemberi tugas atau Direksi/Admin yang dapat menilai task ini.', FALSE);
            return;
        }

        if ($keputusan === 'revisi') {
            if ($ulasan_atasan === '') {
                ajaxReturnDie('error', 'Harap tuliskan catatan bagian mana yang perlu direvisi oleh karyawan!', FALSE);
                return;
            }

            $this->db->where('id_task', $id_task)->update('task_proyek', [
                'status_task'    => 3, // Perlu Revisi
                'progres'        => 75,
                'catatan_revisi' => $ulasan_atasan
            ]);

            $this->db->insert('task_proyek_log', [
                'id_task'     => $id_task,
                'id_pengguna' => $myId,
                'aksi'        => 'Meminta Revisi Pekerjaan',
                'catatan'     => $ulasan_atasan
            ]);

            try {
                $bawahan = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $task->id_penerima)->get()->row();
                $noHp = ($bawahan && !empty($bawahan->no_hp)) ? $bawahan->no_hp : (($bawahan && !empty($bawahan->nohp)) ? $bawahan->nohp : '');
                if (!empty($noHp) && function_exists('sendWa')) {
                    $pesanWa = "*🔄 PERMINTAAN REVISI TASK*" .
                        "%0A%0AHalo *" . $bawahan->nama . "*," .
                        "%0ATask Anda (*" . $task->kode_task . " - " . $task->judul . "*) membutuhkan revisi dari Atasan:" .
                        "%0A%0A📌 *Catatan Revisi*: " . $ulasan_atasan .
                        "%0A%0ASilakan perbaiki dan submit kembali di https://office.visiyosindo.id/task_proyek";
                    @sendWa([
                        'devId'    => hostWa('2'),
                        'penerima' => $noHp,
                        'pesan'    => $pesanWa
                    ]);
                }
            } catch (\Throwable $e) {
            }

            ajaxReturnDie('success', 'Permintaan revisi telah dikirimkan kepada karyawan.', TRUE);
            return;
        }

        // Keputusan == 'terima' (Beri Rating Bintang 1 - 5)
        if ($rating_bintang < 1 || $rating_bintang > 5) {
            ajaxReturnDie('error', 'Harap pilih Rating Bintang antara 1 sampai 5 Bintang!', FALSE);
            return;
        }

        $starText = str_repeat('⭐', $rating_bintang) . ' (' . $rating_bintang . '/5)';

        $this->db->where('id_task', $id_task)->update('task_proyek', [
            'status_task'    => 4, // Selesai & Dinilai
            'progres'        => 100,
            'rating_bintang' => $rating_bintang,
            'ulasan_atasan'  => $ulasan_atasan !== '' ? $ulasan_atasan : 'Pekerjaan selesai dengan baik.',
            'tgl_dinilai'    => date('Y-m-d H:i:s')
        ]);

        $this->db->insert('task_proyek_log', [
            'id_task'     => $id_task,
            'id_pengguna' => $myId,
            'aksi'        => 'Memberi Penilaian ' . $starText,
            'catatan'     => $ulasan_atasan !== '' ? $ulasan_atasan : 'Pekerjaan disetujui dengan rating ' . $rating_bintang . ' bintang.'
        ]);

        try {
            $bawahan = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $task->id_penerima)->get()->row();
            $noHp = ($bawahan && !empty($bawahan->no_hp)) ? $bawahan->no_hp : (($bawahan && !empty($bawahan->nohp)) ? $bawahan->nohp : '');
            if (!empty($noHp) && function_exists('sendWa')) {
                $pesanWa = "*🌟 PENILAIAN TASK SELESAI (" . $starText . ")*" .
                    "%0A%0ASelamat *" . $bawahan->nama . "*!" .
                    "%0ATask Anda telah disetujui dan dinilai oleh Atasan:" .
                    "%0A%0A🔹 *Kode*: " . $task->kode_task .
                    "%0A📌 *Judul*: " . $task->judul .
                    "%0A⭐ *Rating*: " . $starText .
                    "%0A💬 *Ulasan Atasan*: " . ($ulasan_atasan ?: 'Pekerjaan selesai dengan baik.') .
                    "%0A%0ATerima kasih atas kontribusi terbaik Anda!";
                @sendWa([
                    'devId'    => hostWa('2'),
                    'penerima' => $noHp,
                    'pesan'    => $pesanWa
                ]);
            }
        } catch (\Throwable $e) {
        }

        try {
            addLog('Penilaian Bintang Task', 'Memberi rating ' . $rating_bintang . ' bintang untuk Task ' . $task->kode_task);
        } catch (\Throwable $e) {
        }

        ajaxReturnDie('success', 'Penilaian ' . $starText . ' berhasil disimpan!', TRUE);
    }

    /**
     * Hapus Task
     */
    public function delete()
    {
        grantAccessFor('all');

        $myId    = (int)sessPenggunaId();
        $id_task = (int)$this->input->post('id_task', TRUE);
        $task    = $this->db->get_where('task_proyek', ['id_task' => $id_task, 'is_deleted' => 0])->row();

        if (!$task) {
            ajaxReturnDie('error', 'Task tidak ditemukan.', FALSE);
            return;
        }
        if ((int)$task->id_pemberi !== $myId && !$this->_canViewAllTasks($myId)) {
            ajaxReturnDie('error', 'Anda tidak memiliki akses untuk menghapus task ini.', FALSE);
            return;
        }

        $this->db->where('id_task', $id_task)->update('task_proyek', ['is_deleted' => 1]);
        ajaxReturnDie('success', 'Task berhasil dihapus.', TRUE);
    }
}
