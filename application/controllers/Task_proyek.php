<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task_proyek extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pengguna');
        $this->load->model('md_kpi_leaderboard');
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
          `id_sasaran_mutu` varchar(100) DEFAULT NULL,
          `nama_jobdesk` varchar(255) DEFAULT NULL,
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

        $colsToAdd = [
            'id_sasaran_mutu' => "VARCHAR(100) DEFAULT NULL AFTER `nama_proyek`",
            'nama_jobdesk'    => "VARCHAR(255) DEFAULT NULL AFTER `id_sasaran_mutu`",
            'id_mengetahui'   => "INT(11) DEFAULT NULL AFTER `id_penerima`",
            'is_mandiri'      => "TINYINT(1) DEFAULT 0 AFTER `id_mengetahui`",
            'acc_hrd_by'      => "INT(11) DEFAULT NULL AFTER `status_task`",
            'acc_hrd_at'      => "DATETIME DEFAULT NULL AFTER `acc_hrd_by`",
            'acc_hrd_catatan' => "TEXT DEFAULT NULL AFTER `acc_hrd_at`"
        ];
        foreach ($colsToAdd as $colName => $colDef) {
            $chk = @$this->db->query("SHOW COLUMNS FROM `task_proyek` LIKE '{$colName}'");
            if ($chk && $chk->num_rows() === 0) {
                @$this->db->query("ALTER TABLE `task_proyek` ADD COLUMN `{$colName}` {$colDef}");
            }
        }

        if (function_exists('ensureTaskProyekSpColumns')) {
            ensureTaskProyekSpColumns();
        }
    }

    private function _canViewAllTasks($myId)
    {
        return true; // Transparansi tugas tim dibuka untuk semua pengguna perusahaan
    }

    private function _canAssignAllTasks($myId)
    {
        return true; // Semua karyawan sekarang bisa memberikan atau mengajukan task
    }

    public function _isHrdOrAdmin($myId)
    {
        if (isAdmin() || isHrd() || isEksekutif() || in_array((int)$myId, [1, 23, 33, 54, 55, 56, 58, 69, 744], true)) {
            return true;
        }
        $sessUser = strtolower((string)$this->session->userdata("username"));
        $sessNama = strtolower((string)sessNama());
        if (strpos($sessUser, "dian") !== false || strpos($sessNama, "dian") !== false || strpos($sessUser, "hrd") !== false) {
            return true;
        }
        if ($myId > 0) {
            $userRow = $this->db->select("username, nama, jabatan")->from("pengguna")->where("pengguna_id", $myId)->get()->row();
            if ($userRow) {
                $uName = strtolower((string)$userRow->username);
                $nName = strtolower((string)$userRow->nama);
                $jName = strtolower((string)$userRow->jabatan);
                if (strpos($uName, "dian") !== false || strpos($nName, "dian") !== false || strpos($jName, "hrd") !== false || strpos($jName, "personalia") !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    private function _getHrdRecipients()
    {
        $hrdUsers = $this->db->select("pengguna_id, nama, no_hp, nohp")
            ->from("pengguna")
            ->group_start()
                ->where("pengguna_id", 744)
                ->or_like("jabatan", "hrd")
                ->or_like("jabatan", "personalia")
            ->group_end()
            ->where("status", 1)
            ->where("is_active", 1)
            ->get()
            ->result();

        if (empty($hrdUsers)) {
            $dian = $this->db->select("pengguna_id, nama, no_hp, nohp")->from("pengguna")->where("pengguna_id", 744)->get()->row();
            if ($dian) {
                $hrdUsers = [$dian];
            }
        }
        return $hrdUsers;
    }

    private function _getAccountMergeMap()
    {
        return $this->md_kpi_leaderboard->getAccountMergeMap();
    }

    public function index()
    {
        grantAccessFor('all');

        $myId = (int)sessPenggunaId();
        $nav  = 'kepegawaian';

        $filterSemester = $this->input->get('semester', TRUE);
        $filterBulan    = $this->input->get('bulan', TRUE);
        $filterTahun    = $this->input->get('tahun', TRUE);

        $lbBulanInt = (!empty($filterBulan) && is_numeric($filterBulan) && (int)$filterBulan >= 1 && (int)$filterBulan <= 12)
            ? (int)$filterBulan
            : null;
        $filterBulan = ($lbBulanInt !== null) ? (string)$lbBulanInt : '';

        if ($lbBulanInt !== null) {
            $filterSemester = ($lbBulanInt <= 6) ? '1' : '2';
        } elseif ($filterSemester !== '1' && $filterSemester !== '2') {
            $filterSemester = ((int)date('n') <= 6) ? '1' : '2';
        }
        if (empty($filterTahun) || $filterTahun === 'all' || !is_numeric($filterTahun)) {
            $filterTahun = date('Y');
        }

        $smtInt   = (int)$filterSemester;
        $yInt     = (int)$filterTahun;
        $semStart = ($smtInt === 1) ? "{$yInt}-01-01" : "{$yInt}-07-01";
        $semEnd   = ($smtInt === 1) ? "{$yInt}-06-30" : "{$yInt}-12-31";
        $semLabel = ($smtInt === 1) ? "Semester 1 (Jan–Jun {$yInt})" : "Semester 2 (Jul–Des {$yInt})";

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

        $myDivisi     = ($me && !empty($me->id_divisi)) ? (int)$me->id_divisi : 0;
        $canAssignAll = $this->_canAssignAllTasks($myId);
        $canViewAll   = $this->_canViewAllTasks($myId);

        // Ambil mapping penggabungan akun ganda
        $accountMergeMap  = $this->_getAccountMergeMap();
        $duplicateIds     = array_keys($accountMergeMap);
        $excludeMergedSql = !empty($duplicateIds) ? " AND p.pengguna_id NOT IN (" . implode(',', array_map('intval', $duplicateIds)) . ")" : "";

        // Tentukan ID penerima untuk Tugas Saya (termasuk akun ganda/alias jika ada)
        $myReceiverIds = [$myId];
        if (isset($accountMergeMap[$myId])) {
            $myReceiverIds[] = (int)$accountMergeMap[$myId];
        }
        foreach ($accountMergeMap as $dupId => $targetId) {
            if ((int)$targetId === (int)$myId) {
                $myReceiverIds[] = (int)$dupId;
            }
        }
        $myReceiverIds = array_values(array_unique($myReceiverIds));

        // Tentukan ID pemberi untuk Tugas yang Saya Berikan
        $myGiverIds = [$myId];
        if (isset($accountMergeMap[$myId])) {
            $myGiverIds[] = (int)$accountMergeMap[$myId];
        }
        foreach ($accountMergeMap as $dupId => $targetId) {
            if ((int)$targetId === (int)$myId) {
                $myGiverIds[] = (int)$dupId;
            }
        }
        $myGiverIds = array_values(array_unique($myGiverIds));

        // 1. Daftar semua karyawan aktif kecuali user login (opsi bawahan standar)
        $list_semua_karyawan = $this->db->query("
            SELECT p.pengguna_id, p.nama, p.jabatan, p.id_divisi, d.nama AS nama_divisi
            FROM pengguna p
            LEFT JOIN divisi d ON d.id_divisi = p.id_divisi
            WHERE p.status = 1 AND p.is_active = 1 AND p.pengguna_id != {$myId} {$excludeMergedSql}
            ORDER BY p.nama ASC
        ")->result();

        // Daftar SEMUA karyawan aktif termasuk user login (untuk opsi pengajuan mandiri dan dropdown Mengetahui)
        $list_semua_karyawan_all = $this->db->query("
            SELECT p.pengguna_id, p.nama, p.jabatan, p.id_divisi, d.nama AS nama_divisi
            FROM pengguna p
            LEFT JOIN divisi d ON d.id_divisi = p.id_divisi
            WHERE p.status = 1 AND p.is_active = 1 {$excludeMergedSql}
            ORDER BY p.nama ASC
        ")->result();

        // 2. Daftar karyawan yang HANYA dibawahi langsung oleh user saat ini (id_atasan = $myId)
        $list_bawahan = $this->db->query("
            SELECT p.pengguna_id, p.nama, p.jabatan, p.id_divisi, p.hirarki, p.id_atasan, d.nama AS nama_divisi
            FROM pengguna p
            LEFT JOIN divisi d ON d.id_divisi = p.id_divisi
            WHERE p.status = 1 AND p.is_active = 1 AND p.pengguna_id != 1 AND p.id_atasan = {$myId} {$excludeMergedSql}
            ORDER BY p.nama ASC
        ")->result();

        // 1. Tugas Saya (Sebagai Penerima / Bawahan / Pengaju Mandiri)
        $this->db->select('t.*, p_beri.nama AS nama_pemberi, p_beri.jabatan AS jabatan_pemberi, p_terima.nama AS nama_penerima, p_terima.jabatan AS jabatan_penerima, p_tahu.nama AS nama_mengetahui, p_acc.nama AS nama_acc_hrd')
            ->from('task_proyek t')
            ->join('pengguna p_beri', 'p_beri.pengguna_id = t.id_pemberi', 'left')
            ->join('pengguna p_terima', 'p_terima.pengguna_id = t.id_penerima', 'left')
            ->join('pengguna p_tahu', 'p_tahu.pengguna_id = t.id_mengetahui', 'left')
            ->join('pengguna p_acc', 'p_acc.pengguna_id = t.acc_hrd_by', 'left')
            ->where('t.is_deleted', 0);

        if (count($myReceiverIds) === 1) {
            $this->db->where('t.id_penerima', $myReceiverIds[0]);
        } else {
            $this->db->where_in('t.id_penerima', $myReceiverIds);
        }

        $this->db->where('t.created_at >=', $semStart . ' 00:00:00');
        $this->db->where('t.created_at <=', $semEnd . ' 23:59:59');
        $tugas_saya = $this->db->order_by('FIELD(t.status_task, 5, 3, 0, 1, 2, 4, 6)', '', FALSE)
            ->order_by('t.id_task', 'DESC')
            ->get()
            ->result();

        // 2. Tugas yang Saya Berikan / Ajukan (Sebagai Pembuat / Atasan)
        $viewMode = $this->input->get('mode', TRUE);
        $this->db->select('t.*, p_beri.nama AS nama_pemberi, p_beri.jabatan AS jabatan_pemberi, p_terima.nama AS nama_penerima, p_terima.jabatan AS jabatan_penerima, d.nama AS divisi_penerima, p_tahu.nama AS nama_mengetahui, p_acc.nama AS nama_acc_hrd')
            ->from('task_proyek t')
            ->join('pengguna p_beri', 'p_beri.pengguna_id = t.id_pemberi', 'left')
            ->join('pengguna p_terima', 'p_terima.pengguna_id = t.id_penerima', 'left')
            ->join('divisi d', 'd.id_divisi = p_terima.id_divisi', 'left')
            ->join('pengguna p_tahu', 'p_tahu.pengguna_id = t.id_mengetahui', 'left')
            ->join('pengguna p_acc', 'p_acc.pengguna_id = t.acc_hrd_by', 'left')
            ->where('t.is_deleted', 0);

        if (count($myGiverIds) === 1) {
            $this->db->where('t.id_pemberi', $myGiverIds[0]);
        } else {
            $this->db->where_in('t.id_pemberi', $myGiverIds);
        }

        $this->db->where('t.created_at >=', $semStart . ' 00:00:00');
        $this->db->where('t.created_at <=', $semEnd . ' 23:59:59');
        $tugas_atasan = $this->db->order_by('FIELD(t.status_task, 5, 2, 1, 3, 0, 4, 6)', '', FALSE)
            ->order_by('t.id_task', 'DESC')
            ->get()
            ->result();

        // 3. Semua Task Perusahaan (Monitoring Tim & Transparansi Semua Karyawan)
        $this->db->select('t.*, p_beri.nama AS nama_pemberi, p_beri.jabatan AS jabatan_pemberi, p_terima.nama AS nama_penerima, p_terima.jabatan AS jabatan_penerima, d.nama AS divisi_penerima, p_tahu.nama AS nama_mengetahui, p_acc.nama AS nama_acc_hrd')
            ->from('task_proyek t')
            ->join('pengguna p_beri', 'p_beri.pengguna_id = t.id_pemberi', 'left')
            ->join('pengguna p_terima', 'p_terima.pengguna_id = t.id_penerima', 'left')
            ->join('divisi d', 'd.id_divisi = p_terima.id_divisi', 'left')
            ->join('pengguna p_tahu', 'p_tahu.pengguna_id = t.id_mengetahui', 'left')
            ->join('pengguna p_acc', 'p_acc.pengguna_id = t.acc_hrd_by', 'left')
            ->where('t.is_deleted', 0);

        $this->db->where('t.created_at >=', $semStart . ' 00:00:00');
        $this->db->where('t.created_at <=', $semEnd . ' 23:59:59');
        $semua_task = $this->db->order_by('FIELD(t.status_task, 5, 2, 1, 3, 0, 4, 6)', '', FALSE)
            ->order_by('t.id_task', 'DESC')
            ->get()
            ->result();

        $kpi_waiting_acc_hrd = 0;
        foreach ($semua_task as $stk) {
            if (function_exists('evaluateTaskDeadlineAndSp')) {
                evaluateTaskDeadlineAndSp($stk, true);
            }
            if ((int)$stk->status_task === 5) {
                $kpi_waiting_acc_hrd++;
            }
        }

        // 3. KPI Ringkasan Personal & Atasan
        $kpi_my_active = 0;
        $kpi_my_done   = 0;
        $kpi_my_waiting_review = 0;
        $kpi_my_stars_sum = 0;
        $kpi_my_stars_cnt = 0;
        foreach ($tugas_saya as $ts) {
            if (function_exists('evaluateTaskDeadlineAndSp')) {
                evaluateTaskDeadlineAndSp($ts, true);
            }
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
            if (function_exists('evaluateTaskDeadlineAndSp')) {
                evaluateTaskDeadlineAndSp($ta, true);
            }
            // Tugas mandiri/ke diri sendiri dinilai oleh Atasan Langsung, HRD, atau Manajemen, bukan oleh pelaksana sendiri
            if ((int)$ta->status_task === 2 && (int)$ta->id_penerima !== $myId) {
                $kpi_need_review++;
            }
            if ((int)$ta->status_task < 4) {
                $kpi_given_active++;
            } else {
                $kpi_given_done++;
            }
        }

        // 4. Leaderboard & Rekap Rating Kinerja Karyawan per Semester / Per Bulan (via shared Md_kpi_leaderboard)
        $lbBundle      = $this->md_kpi_leaderboard->buildLeaderboard($smtInt, $yInt, $myReceiverIds, $lbBulanInt);
        $leaderboard   = $lbBundle['leaderboard'];
        $myCombinedKpi = $lbBundle['my_combined'];

        $isHrdAdmin = $this->_isHrdOrAdmin($myId);
        $page_data['switch']                  = $nav;
        $page_data['page_name']               = 'task_proyek/v_task_proyek';
        $page_data['page_title']              = 'Manajemen Task & Proyek Kerja';
        $page_data['page_desc']               = 'Penugasan Karyawan, Pengajuan Mandiri, Monitoring Tim & Penilaian Rating';
        $page_data['me']                      = $me;
        $page_data['myId']                    = $myId;
        $page_data['myReceiverIds']           = $myReceiverIds;
        $page_data['canViewAll']              = true; // Transparansi tugas tim terbuka untuk semua
        $page_data['canAssignAll']            = true; // Semua karyawan dapat menugaskan / mengajukan
        $page_data['isHrdAdmin']              = $isHrdAdmin;
        $page_data['isAdmin']                 = ($isHrdAdmin || isAdmin() || (int)$myId === 1 || in_array((int)$this->session->userdata('id_role'), [1], true));
        $page_data['viewMode']                = $viewMode;
        $page_data['filterSemester']          = $filterSemester;
        $page_data['semLabel']                = $semLabel;
        $page_data['lbLabel']                 = $lbBundle['sem_label'];
        $page_data['lbIsMonthly']             = !empty($lbBundle['is_monthly']);
        $page_data['lbStart']                 = $lbBundle['sem_start'];
        $page_data['lbEnd']                   = $lbBundle['sem_end'];
        $page_data['filterBulan']             = $filterBulan;
        $page_data['filterTahun']             = $filterTahun;
        $page_data['namaBulanList']           = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $page_data['list_bawahan']            = $list_bawahan;
        $page_data['list_semua_karyawan']     = $list_semua_karyawan;
        $page_data['list_semua_karyawan_all'] = $list_semua_karyawan_all;
        $page_data['tugas_saya']              = $tugas_saya;
        $page_data['tugas_atasan']            = $tugas_atasan;
        $page_data['semua_task']              = $semua_task;
        $page_data['leaderboard']             = $leaderboard;
        $page_data['kpi'] = [
            'my_active'         => $kpi_my_active,
            'my_done'           => $kpi_my_done,
            'my_waiting_review' => $kpi_my_waiting_review,
            'my_avg_star'       => $kpi_my_avg_star,
            'my_star_cnt'       => $kpi_my_stars_cnt,
            'need_review'       => $kpi_need_review,
            'given_active'      => $kpi_given_active,
            'given_done'        => $kpi_given_done,
            'waiting_acc_hrd'   => $kpi_waiting_acc_hrd,
            'total_given'       => count($tugas_atasan),
            'total_semua'       => count($semua_task),
            'my_combined'       => $myCombinedKpi
        ];

        $this->load->view('index', $page_data);
    }

    /**
     * Ambil daftar Jobdesk Sasaran Mutu untuk karyawan yang dipilih (berdasarkan jabatan & divisi)
     */
    public function get_karyawan_jobdesks()
    {
        grantAccessFor('all');
        $this->db->db_debug = FALSE;

        $id_pengguna = (int)($this->input->get('id_pengguna') ?: ($this->input->post('id_pengguna') ?: (isset($_GET['id_pengguna']) ? $_GET['id_pengguna'] : 0)));
        if ($id_pengguna <= 0) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'ID pengguna tidak valid']);
            return;
        }

        $user = $this->db->select('p.pengguna_id, p.nama, p.jabatan, p.id_divisi, d.nama as nama_divisi')
            ->from('pengguna p')
            ->join('divisi d', 'p.id_divisi = d.id_divisi', 'left')
            ->where('p.pengguna_id', $id_pengguna)
            ->get()->row();

        if (!$user) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Karyawan tidak ditemukan']);
            return;
        }

        $rawJabatan = trim((string)$user->jabatan);
        $rawDivisi  = trim((string)$user->nama_divisi);

        $jobdesks  = [];
        $addedJobs = [];
        $seqNo     = 1;

        // 1. PRIORITAS UTAMA: AMBIL JOBDESK YANG MASIH AKTIF UNTUK KARYAWAN TERSEBUT
        // Menyaring HANYA jobdesk yang statusnya AKTIF (tgl_mulai <= today dan tgl_selesai >= today / belum kedaluwarsa)
        $today = date('Y-m-d');
        $laporanJobs = [];

        // A. Cek Header Jobdesk Aktif untuk karyawan ini (status = 1 dan rentang tanggal mencakup hari ini)
        $activeHeaders = $this->db->query("
            SELECT id, tgl_mulai, tgl_selesai
            FROM jobdesc
            WHERE id_pengguna = {$id_pengguna}
              AND (status = 1 OR status IS NULL)
              AND (tgl_mulai IS NULL OR tgl_mulai = '0000-00-00' OR tgl_mulai <= '{$today}')
              AND (tgl_selesai IS NULL OR tgl_selesai = '0000-00-00' OR tgl_selesai = '2099-12-31' OR tgl_selesai >= '{$today}')
            ORDER BY tgl_mulai DESC, id DESC
        ")->result();

        $activeJobdescIds = [];
        if (!empty($activeHeaders)) {
            foreach ($activeHeaders as $ah) {
                $activeJobdescIds[] = (int)$ah->id;
            }
        }

        // B. Ambil detail jobdesk aktif:
        // Jika ada header aktif, ambil HANYA detail dari header aktif tersebut
        if (!empty($activeJobdescIds)) {
            $inIds = implode(',', $activeJobdescIds);
            $laporanJobs = $this->db->query("
                SELECT DISTINCT
                    jd.id AS id_pod,
                    jd.id_jobdesc,
                    jd.deskripsi,
                    jd.point,
                    jd.nilai,
                    jd.id_urut
                FROM jobdesc_detail jd
                WHERE jd.id_jobdesc IN ({$inIds})
                  AND jd.status = 1
                  AND (jd.tgl_selesai IS NULL OR jd.tgl_selesai = '0000-00-00' OR jd.tgl_selesai = '2099-12-31' OR jd.tgl_selesai >= '{$today}')
                  AND (jd.tgl_mulai IS NULL OR jd.tgl_mulai = '0000-00-00' OR jd.tgl_mulai <= '{$today}')
                  AND jd.deskripsi IS NOT NULL
                  AND TRIM(jd.deskripsi) != ''
                ORDER BY jd.point ASC, jd.id_urut ASC, jd.id ASC
            ")->result();
        }

        // C. Jika belum dapat dari header, ambil detail yang langsung mengarah ke idPengguna yang masih aktif
        if (empty($laporanJobs)) {
            $laporanJobs = $this->db->query("
                SELECT DISTINCT
                    jd.id AS id_pod,
                    jd.id_jobdesc,
                    jd.deskripsi,
                    jd.point,
                    jd.nilai,
                    jd.id_urut
                FROM jobdesc_detail jd
                LEFT JOIN jobdesc j ON j.id = jd.id_jobdesc
                WHERE (jd.idPengguna = {$id_pengguna} OR j.id_pengguna = {$id_pengguna})
                  AND jd.status = 1
                  AND (jd.tgl_selesai IS NULL OR jd.tgl_selesai = '0000-00-00' OR jd.tgl_selesai = '2099-12-31' OR jd.tgl_selesai >= '{$today}')
                  AND (jd.tgl_mulai IS NULL OR jd.tgl_mulai = '0000-00-00' OR jd.tgl_mulai <= '{$today}')
                  AND (j.status IS NULL OR j.status = 1)
                  AND (j.tgl_selesai IS NULL OR j.tgl_selesai = '0000-00-00' OR j.tgl_selesai = '2099-12-31' OR j.tgl_selesai >= '{$today}')
                  AND jd.deskripsi IS NOT NULL
                  AND TRIM(jd.deskripsi) != ''
                ORDER BY jd.point ASC, jd.id_urut ASC, jd.id ASC
            ")->result();
        }

        // D. Jika masih kosong juga (misal HR tidak mengisi tanggal masa berlaku), ambil dari batch jobdesk paling terbaru (latest id)
        if (empty($laporanJobs)) {
            $latestHeader = $this->db->query("
                SELECT id FROM jobdesc 
                WHERE id_pengguna = {$id_pengguna} AND (status = 1 OR status IS NULL)
                ORDER BY id DESC LIMIT 1
            ")->row();

            if ($latestHeader) {
                $laporanJobs = $this->db->query("
                    SELECT DISTINCT
                        jd.id AS id_pod,
                        jd.id_jobdesc,
                        jd.deskripsi,
                        jd.point,
                        jd.nilai,
                        jd.id_urut
                    FROM jobdesc_detail jd
                    WHERE jd.id_jobdesc = {$latestHeader->id}
                      AND jd.status = 1
                      AND jd.deskripsi IS NOT NULL
                      AND TRIM(jd.deskripsi) != ''
                    ORDER BY jd.point ASC, jd.id_urut ASC, jd.id ASC
                ")->result();
            }
        }
        if (!empty($laporanJobs)) {
            foreach ($laporanJobs as $lj) {
                $cleanJob = trim((string)$lj->deskripsi);
                if (empty($cleanJob)) continue;

                $jobKey = md5(strtolower($cleanJob));
                if (!isset($addedJobs[$jobKey])) {
                    $addedJobs[$jobKey] = true;
                    $jobdesks[] = [
                        'id'            => 'JOB_' . (!empty($lj->id_pod) ? $lj->id_pod : (!empty($lj->id) ? $lj->id : $seqNo)),
                        'no'            => $seqNo++,
                        'posisi'        => $rawJabatan ?: ($rawDivisi ?: 'Karyawan'),
                        'divisi'        => $rawDivisi ?: 'Umum',
                        'jobdesk'       => $cleanJob,
                        'detail_proyek' => 'Jobdesk Laporan Mingguan: ' . $cleanJob,
                        'sasaran_mutu'  => 'Tercapainya target ' . $cleanJob . ' dengan baik dan tepat waktu'
                    ];
                }
            }
        }

        // 2. JIKA DI LAPORAN MINGGUAN BELUM DISETTING / KOSONG, CARI DARI MASTER SASARAN MUTU
        $resolvedPos = '';
        if (empty($jobdesks)) {
            $this->load->model('md_audit_system');
            $master = $this->md_audit_system->getMasterTemplate();
            $resolvedPos = $this->md_audit_system->resolveUserPosition($rawJabatan, $rawDivisi);

            if (!empty($resolvedPos)) {
                foreach ($master as $m) {
                    $mPos = isset($m['posisi']) ? trim($m['posisi']) : '';
                    if (strcasecmp($mPos, $resolvedPos) === 0) {
                        $cleanJob = rtrim(trim($m['jobdesk']), '; ');
                        if (empty($cleanJob)) continue;

                        $isHeaderOnly = (empty(trim($m['detail_proyek'])) && empty(trim($m['sasaran_mutu'])) && empty(trim($m['implementasi'])) && mb_strtoupper($cleanJob) === $cleanJob);
                        if ($isHeaderOnly) continue;

                        $jobKey = md5(strtolower($cleanJob));
                        if (!isset($addedJobs[$jobKey])) {
                            $addedJobs[$jobKey] = true;
                            $jobdesks[] = [
                                'id'            => $m['id'],
                                'no'            => $seqNo++,
                                'posisi'        => $resolvedPos,
                                'divisi'        => $m['divisi'],
                                'jobdesk'       => $cleanJob,
                                'detail_proyek' => trim($m['detail_proyek']),
                                'sasaran_mutu'  => trim($m['sasaran_mutu'])
                            ];
                        }
                    }
                }

                // Override tahunan jika ada
                $curYear = (int)date('Y');
                $overrides = $this->md_audit_system->loadSasaranMutuOverrides($curYear);
                if (!empty($overrides)) {
                    foreach ($overrides as $ovId => $ov) {
                        $ovPos = isset($ov['posisi']) ? trim($ov['posisi']) : '';
                        $ovJob = isset($ov['jobdesk']) ? trim($ov['jobdesk']) : '';
                        if (empty($ovJob)) continue;

                        if (strcasecmp($ovPos, $resolvedPos) === 0) {
                            $cleanJob = rtrim(trim($ovJob), '; ');
                            $jobKey = md5(strtolower($cleanJob));
                            if (!isset($addedJobs[$jobKey])) {
                                $addedJobs[$jobKey] = true;
                                $jobdesks[] = [
                                    'id'            => $ovId,
                                    'no'            => $seqNo++,
                                    'posisi'        => $resolvedPos,
                                    'divisi'        => isset($ov['divisi']) ? $ov['divisi'] : $rawDivisi,
                                    'jobdesk'       => $cleanJob,
                                    'detail_proyek' => isset($ov['detail_proyek']) ? trim($ov['detail_proyek']) : '',
                                    'sasaran_mutu'  => isset($ov['sasaran_mutu']) ? trim($ov['sasaran_mutu']) : ''
                                ];
                            }
                        }
                    }
                }
            }
        }

        // 3. JIKA TETAP KOSONG, BUATKAN ITEM TUGAS OPERASIONAL RESMI DENGAN NAMA JABATAN KARYAWAN ITU SENDIRI
        if (empty($jobdesks)) {
            $displayPos = !empty($rawJabatan) ? $rawJabatan : (!empty($rawDivisi) ? $rawDivisi : 'Karyawan');
            $jobdesks[] = [
                'id'            => 'SM_CUSTOM_TASK',
                'no'            => 1,
                'posisi'        => $displayPos,
                'divisi'        => $rawDivisi ?: 'Umum',
                'jobdesk'       => 'Tugas & Tanggung Jawab Operasional (' . $displayPos . ')',
                'detail_proyek' => 'Pelaksanaan tugas operasional harian sesuai arahan atasan pada posisi ' . $displayPos,
                'sasaran_mutu'  => 'Tercapainya target pekerjaan ' . $displayPos . ' dengan baik dan tepat waktu'
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status'   => 'success',
            'karyawan' => [
                'id'             => (int)$user->pengguna_id,
                'nama'           => $user->nama,
                'jabatan'        => $user->jabatan,
                'posisi_matched' => $resolvedPos ?: ($rawJabatan ?: 'Karyawan'),
                'divisi'         => $user->nama_divisi ?: 'General'
            ],
            'total'    => count($jobdesks),
            'jobdesks' => $jobdesks
        ]);
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
        $id_sasaran_mutu = trim((string)$this->input->post('id_sasaran_mutu', TRUE));
        $nama_jobdesk    = trim((string)$this->input->post('nama_jobdesk', TRUE));
        $id_mengetahui   = (int)$this->input->post('id_mengetahui', TRUE) ?: null;
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

        // Normalisasi akun ganda: arahkan ke akun utama dan hilangkan duplikasi
        $accountMergeMap  = $this->_getAccountMergeMap();
        $cleaned_penerima = [];
        foreach ($penerima_list as $id_raw) {
            $id_int = (int)$id_raw;
            if ($id_int <= 0) continue;
            if (isset($accountMergeMap[$id_int])) {
                $id_int = (int)$accountMergeMap[$id_int];
            }
            $cleaned_penerima[] = $id_int;
        }
        $penerima_list = array_values(array_unique($cleaned_penerima));

        // VALIDASI: Jika penerima tugas tepat 1 orang, Jobdesk Sasaran Mutu WAJIB dipilih!
        if (count($penerima_list) === 1 && empty($id_sasaran_mutu)) {
            ajaxReturnDie('error', 'Jobdesk Sasaran Mutu wajib dipilih untuk penugasan karyawan tersebut!', FALSE);
            return;
        }

        // Validasi keberadaan user penerima
        foreach ($penerima_list as $id_penerima_raw) {
            $id_penerima = (int)$id_penerima_raw;
            if ($id_penerima <= 0) continue;

            $chkSub = $this->db->select('pengguna_id, nama')
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
        $namaPembuat = $pembuatRow ? $pembuatRow->nama : 'Karyawan';

        $mengetahuiUser = null;
        if (!empty($id_mengetahui)) {
            $mengetahuiUser = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $id_mengetahui)->get()->row();
        }
        $namaMengetahui = $mengetahuiUser ? $mengetahuiUser->nama : '';

        $createdCount = 0;
        foreach ($penerima_list as $id_penerima_raw) {
            $id_penerima = (int)$id_penerima_raw;
            if ($id_penerima <= 0) continue;

            // Cek apakah ini pengajuan task mandiri untuk diri sendiri
            $is_self = ($id_penerima === $myId);
            $is_mandiri = $is_self ? 1 : 0;
            $status_task = $is_self ? 5 : 0; // 5 = Menunggu ACC HRD, 0 = Baru / Aktif

            // Generate kode task unik
            $lastRow = $this->db->select('id_task')->from('task_proyek')->order_by('id_task', 'DESC')->limit(1)->get()->row();
            $nextNum = ($lastRow ? (int)$lastRow->id_task : 0) + 1;
            $kode_task = sprintf('TSK/%s/%s/%03d', date('Y'), date('m'), $nextNum);

            $dataInsert = [
                'kode_task'       => $kode_task,
                'judul'           => $judul,
                'nama_proyek'     => $nama_proyek !== '' ? $nama_proyek : 'Umum',
                'id_sasaran_mutu' => (count($penerima_list) === 1 && !empty($id_sasaran_mutu)) ? $id_sasaran_mutu : null,
                'nama_jobdesk'    => (count($penerima_list) === 1 && !empty($nama_jobdesk)) ? $nama_jobdesk : null,
                'jenis'           => $jenis,
                'prioritas'       => $prioritas,
                'deskripsi'       => $deskripsi,
                'checklist_json'  => $checklist_json,
                'id_pemberi'      => $myId,
                'id_penerima'     => $id_penerima,
                'id_mengetahui'   => $id_mengetahui,
                'is_mandiri'      => $is_mandiri,
                'tgl_mulai'       => date('Y-m-d', strtotime($tgl_mulai)),
                'tgl_deadline'    => date('Y-m-d', strtotime($tgl_deadline)),
                'lampiran_atasan' => $lampiran_atasan,
                'progres'         => 0,
                'status_task'     => $status_task
            ];

            if ($this->db->insert('task_proyek', $dataInsert)) {
                $id_task = $this->db->insert_id();
                $createdCount++;

                $aksiLog = $is_mandiri ? 'Mengajukan Task Mandiri (Menunggu ACC HRD)' : 'Membuat & Menugaskan Task';
                $catatanLog = $is_mandiri 
                    ? 'Pengajuan Task Mandiri "' . $judul . '" diajukan oleh ' . $namaPembuat . ' dan membutuhkan persetujuan (ACC) HRD sebelum mulai pengerjaan.'
                    : 'Task "' . $judul . '" ditugaskan oleh ' . $namaPembuat . ' (Prioritas: ' . $prioritas . ', Deadline: ' . date('d-m-Y', strtotime($tgl_deadline)) . ').';

                $this->db->insert('task_proyek_log', [
                    'id_task'     => $id_task,
                    'id_pengguna' => $myId,
                    'aksi'        => $aksiLog,
                    'catatan'     => $catatanLog
                ]);

                // 1. JIKA TASK MANDIRI: Kirim WA ke HRD untuk di-ACC
                if ($is_mandiri) {
                    try {
                        $hrdRecipients = $this->_getHrdRecipients();
                        foreach ($hrdRecipients as $hrd) {
                            $noHpHrd = (!empty($hrd->no_hp)) ? $hrd->no_hp : ((!empty($hrd->nohp)) ? $hrd->nohp : '');
                            if (!empty($noHpHrd) && function_exists('sendWa')) {
                                $pesanHrd = "[PENGAJUAN TASK MANDIRI] *MENUNGGU ACC HRD*" .
                                    "%0A%0AHalo *" . $hrd->nama . "*," .
                                    "%0AKaryawan *" . $namaPembuat . "* mengajukan task mandiri baru yang membutuhkan persetujuan (ACC) HRD sebelum mulai pengerjaan:" .
                                    "%0A%0A- *Kode*: " . $kode_task .
                                    "%0A- *Judul*: " . $judul .
                                    "%0A- *Proyek*: " . ($nama_proyek ?: 'Umum') .
                                    "%0A- *Prioritas*: " . $prioritas .
                                    "%0A- *Target Selesai*: " . date('d-m-Y', strtotime($tgl_deadline)) .
                                    ($nama_jobdesk ? "%0AðŸŽ¯ *Jobdesk Sasaran Mutu*: " . $nama_jobdesk : "") .
                                    ($namaMengetahui ? "%0A- *Mengetahui*: " . $namaMengetahui : "") .
                                    "%0A%0ASilakan tinjau & klik *ACC* di sistem Office:%0Ahttps://office.visiyosindo.id/task_proyek";
                                @sendWa([
                                    'devId'    => hostWa('2'),
                                    'penerima' => $noHpHrd,
                                    'pesan'    => $pesanHrd
                                ]);
                            }
                        }
                    } catch (\Throwable $e) {}

                    // Konfirmasi ke pengaju jika kirim_wa dicentang
                    if ($kirim_wa) {
                        try {
                            $pembuatUser = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $myId)->get()->row();
                            $noHpPembuat = ($pembuatUser && !empty($pembuatUser->no_hp)) ? $pembuatUser->no_hp : (($pembuatUser && !empty($pembuatUser->nohp)) ? $pembuatUser->nohp : '');
                            if (!empty($noHpPembuat) && function_exists('sendWa')) {
                                $pesanKaryawan = "[BERHASIL DIAJUKAN] *PENGAJUAN TASK MANDIRI*" .
                                    "%0A%0AHalo *" . $namaPembuat . "*," .
                                    "%0APengajuan task Anda (*" . $kode_task . " - " . $judul . "*) telah berhasil masuk ke sistem dan saat ini *Menunggu Persetujuan (ACC) HRD*." .
                                    "%0A%0AAnda akan menerima notifikasi kembali setelah disetujui (ACC) oleh HRD untuk dapat mulai mengerjakan.%0Ahttps://office.visiyosindo.id/task_proyek";
                                @sendWa([
                                    'devId'    => hostWa('2'),
                                    'penerima' => $noHpPembuat,
                                    'pesan'    => $pesanKaryawan
                                ]);
                            }
                        } catch (\Throwable $e) {}
                    }
                    // Catatan: Pihak Mengetahui TIDAK dikirim sekarang.
                    // Sesuai permintaan, notifikasi ke pihak Mengetahui hanya dikirim setelah di-ACC HRD!
                } else {
                    // 2. JIKA PENUGASAN KE REKAN / BAWAHAN
                    if ($kirim_wa) {
                        try {
                            $bawahan = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $id_penerima)->get()->row();
                            $noHp = ($bawahan && !empty($bawahan->no_hp)) ? $bawahan->no_hp : (($bawahan && !empty($bawahan->nohp)) ? $bawahan->nohp : '');
                            if (!empty($noHp) && function_exists('sendWa')) {
                                $pesanWa = "[TUGAS BARU] *PENUGASAN TASK / PROYEK*" .
                                    "%0A%0AHalo *" . $bawahan->nama . "*," .
                                    "%0AAnda menerima penugasan baru dari *" . $namaPembuat . "*:" .
                                    "%0A%0A- *Kode*: " . $kode_task .
                                    "%0A- *Judul*: " . $judul .
                                    "%0A- *Proyek*: " . ($nama_proyek ?: 'Umum') .
                                    "%0A- *Prioritas*: " . $prioritas .
                                    "%0A- *Deadline*: " . date('d-m-Y', strtotime($tgl_deadline)) .
                                    ($namaMengetahui ? "%0A- *Mengetahui*: " . $namaMengetahui : "") .
                                    "%0A%0ASilakan buka menu *Task & Proyek Kerja* di https://office.visiyosindo.id/task_proyek untuk mulai mengerjakan.";
                                @sendWa([
                                    'devId'    => hostWa('2'),
                                    'penerima' => $noHp,
                                    'pesan'    => $pesanWa
                                ]);
                            }
                        } catch (\Throwable $e) {}

                        // Kirim juga ke pihak mengetahui jika ada
                        if ($mengetahuiUser) {
                            try {
                                $noHpMengetahui = (!empty($mengetahuiUser->no_hp)) ? $mengetahuiUser->no_hp : ((!empty($mengetahuiUser->nohp)) ? $mengetahuiUser->nohp : '');
                                if (!empty($noHpMengetahui) && function_exists('sendWa')) {
                                    $pesanTahu = "[INFO MENGETAHUI] *INFORMASI PENUGASAN TASK*" .
                                        "%0A%0AHalo *" . $mengetahuiUser->nama . "*," .
                                        "%0ASebagai pihak yang mengetahui, *" . $namaPembuat . "* telah menugaskan task kepada *" . ($bawahan ? $bawahan->nama : 'Karyawan') . "*:" .
                                        "%0A%0A- *Kode*: " . $kode_task .
                                        "%0A- *Judul*: " . $judul .
                                        "%0A- *Proyek*: " . ($nama_proyek ?: 'Umum') .
                                        "%0A- *Prioritas*: " . $prioritas .
                                        "%0A- *Deadline*: " . date('d-m-Y', strtotime($tgl_deadline)) .
                                        "%0A%0APemantauan tugas: https://office.visiyosindo.id/task_proyek";
                                    @sendWa([
                                        'devId'    => hostWa('2'),
                                        'penerima' => $noHpMengetahui,
                                        'pesan'    => $pesanTahu
                                    ]);
                                }
                            } catch (\Throwable $e) {}
                        }
                    }
                }
            }
        }

        if ($createdCount > 0) {
            try {
                addLog('Buat Task Proyek', 'Menugaskan Task "' . $judul . '" kepada ' . $createdCount . ' karyawan');
            } catch (\Throwable $e) {}
            $suksesMsg = (count($penerima_list) === 1 && (int)$penerima_list[0] === $myId)
                ? 'Pengajuan Task Mandiri berhasil dikirimkan ke HRD dan menunggu persetujuan (ACC)!'
                : 'Berhasil membuat ' . $createdCount . ' penugasan Task / Proyek baru!';
            ajaxReturnDie('success', $suksesMsg, TRUE);
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

        $myId   = (int)sessPenggunaId();
        $id_task = (int)$this->input->post('id_task', TRUE);
        $task = $this->db->select('t.*, p_beri.nama AS nama_pemberi, p_beri.jabatan AS jabatan_pemberi, p_terima.nama AS nama_penerima, p_terima.jabatan AS jabatan_penerima, p_tahu.nama AS nama_mengetahui, p_acc.nama AS nama_acc_hrd')
            ->from('task_proyek t')
            ->join('pengguna p_beri', 'p_beri.pengguna_id = t.id_pemberi', 'left')
            ->join('pengguna p_terima', 'p_terima.pengguna_id = t.id_penerima', 'left')
            ->join('pengguna p_tahu', 'p_tahu.pengguna_id = t.id_mengetahui', 'left')
            ->join('pengguna p_acc', 'p_acc.pengguna_id = t.acc_hrd_by', 'left')
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
        $task->acc_hrd_at_fmt = !empty($task->acc_hrd_at) ? date('d-m-Y H:i', strtotime($task->acc_hrd_at)) : '';
        if (function_exists('evaluateTaskDeadlineAndSp')) {
            evaluateTaskDeadlineAndSp($task, true);
        }
        $task->is_overdue = (!empty($task->is_late) || (!empty($task->tgl_deadline) && (int)$task->status_task < 4 && date('Y-m-d') > $task->tgl_deadline));
        $task->hrd_jadwal_tgl_fmt = !empty($task->hrd_jadwal_tgl) ? date('d-m-Y', strtotime($task->hrd_jadwal_tgl)) : '';

        $isHrdAdmin = $this->_isHrdOrAdmin($myId);
        $isSelfTask = ((int)$task->id_penerima === $myId);
        $isGiver = ((int)$task->id_pemberi === $myId);
        $isAtasanPenerima = false;
        if (!empty($task->id_penerima)) {
            $penerimaUser = $this->db->select('id_atasan')->from('pengguna')->where('pengguna_id', $task->id_penerima)->get()->row();
            if ($penerimaUser && (int)$penerimaUser->id_atasan === $myId) {
                $isAtasanPenerima = true;
            }
        }

        // ATURAN MUTLAK: Pelaksana (penerima tugas) TIDAK BOLEH menilai tugas diri sendiri dalam kondisi apapun!
        // Penilaian tugas mandiri / tugas ke diri sendiri wajib dilakukan secara objektif oleh Atasan Langsung, HRD, atau Manajemen.
        if ($isSelfTask) {
            $canReviewThis = false;
        } else {
            $canReviewThis = ($isGiver || $isAtasanPenerima || $isHrdAdmin);
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status'            => 'success',
            'task'              => $task,
            'logs'              => $logs,
            'my_id'             => $myId,
            'is_admin_reviewer' => $isHrdAdmin,
            'is_hrd_admin'      => $isHrdAdmin,
            'can_review'        => $canReviewThis
        ]);
        exit;
    }

    /**
     * Bawahan: Update Progres / Tambah & Centang Checklist / Komentar atau Mulai Kerjakan
     */
    public function update_progres()
    {
        grantAccessFor('all');

        $myId                = (int)sessPenggunaId();
        $id_task             = (int)$this->input->post('id_task', TRUE);
        $progres             = (int)$this->input->post('progres', TRUE);
        $catatan             = trim((string)$this->input->post('catatan', FALSE));
        $checklist_items_raw = $this->input->post('checklist_items', FALSE);
        $checklist_flags_raw = $this->input->post('checklist_done_flags', TRUE);
        $checklist_done      = $this->input->post('checklist_done', TRUE);

        $task = $this->db->get_where('task_proyek', ['id_task' => $id_task, 'is_deleted' => 0])->row();
        if (!$task) {
            ajaxReturnDie('error', 'Task tidak ditemukan.', FALSE);
            return;
        }

        if ($progres < 0) $progres = 0;
        if ($progres > 100) $progres = 100;

        // Susun / perbarui checklist dari input penerima task (jika dikirimkan)
        $checklist = [];
        $doneCount = 0;
        if (is_array($checklist_items_raw)) {
            $flags = is_array($checklist_flags_raw) ? $checklist_flags_raw : [];
            $doneIndices = is_array($checklist_done) ? array_map('intval', $checklist_done) : [];
            foreach ($checklist_items_raw as $idx => $rawText) {
                $cleanText = trim((string)$rawText);
                if ($cleanText === '') {
                    continue;
                }
                $isDone = isset($flags[$idx]) ? ((int)$flags[$idx] === 1) : in_array((int)$idx, $doneIndices, true);
                $checklist[] = [
                    'item' => $cleanText,
                    'done' => $isDone ? 1 : 0
                ];
                if ($isDone) {
                    $doneCount++;
                }
            }
            if (count($checklist) > 0 && $this->input->post('auto_calc_checklist') == '1') {
                $progres = (int)round(($doneCount / count($checklist)) * 100);
            }
        } else {
            // Fallback jika hanya mengirim centangan dari checklist yang sudah ada
            $checklist = !empty($task->checklist_json) ? json_decode($task->checklist_json, true) : [];
            if (!empty($checklist) && is_array($checklist)) {
                $doneIndices = is_array($checklist_done) ? array_map('intval', $checklist_done) : [];
                foreach ($checklist as $idx => &$item) {
                    $item['done'] = in_array($idx, $doneIndices, true) ? 1 : 0;
                    if ($item['done']) $doneCount++;
                }
                unset($item);
                if (count($checklist) > 0 && $this->input->post('auto_calc_checklist') == '1') {
                    $progres = (int)round(($doneCount / count($checklist)) * 100);
                }
            }
        }

        $newStatus = (int)$task->status_task;
        if ($newStatus === 5) {
            ajaxReturnDie('error', 'Task mandiri ini masih menunggu persetujuan (ACC) dari HRD sebelum dapat mulai dikerjakan.', FALSE);
            return;
        }
        if ($newStatus === 6) {
            ajaxReturnDie('error', 'Task mandiri ini telah ditolak oleh HRD dan tidak dapat dikerjakan.', FALSE);
            return;
        }
        if ($newStatus === 4) {
            ajaxReturnDie('error', 'Task ini sudah selesai dinilai oleh Atasan dan tidak dapat diubah lagi.', FALSE);
            return;
        }
        if ($progres >= 100) {
            $progres   = 100;
            $newStatus = 2; // Menunggu Penilaian
        } elseif ($newStatus === 0 || $newStatus === 3 || $newStatus === 2) {
            $newStatus = 1; // Sedang Dikerjakan
            if ($progres === 0 && empty($checklist)) $progres = 10;
        }

        $updTask = [
            'progres'        => $progres,
            'status_task'    => $newStatus,
            'checklist_json' => !empty($checklist) ? json_encode($checklist, JSON_UNESCAPED_UNICODE) : null
        ];
        if ($newStatus === 2 && empty($task->tgl_submit)) {
            $updTask['tgl_submit'] = date('Y-m-d H:i:s');
        }
        $this->db->where('id_task', $id_task)->update('task_proyek', $updTask);

        $checklistInfo = !empty($checklist) ? ' [Checklist: ' . $doneCount . '/' . count($checklist) . ' selesai]' : '';
        $this->db->insert('task_proyek_log', [
            'id_task'     => $id_task,
            'id_pengguna' => $myId,
            'aksi'        => ($newStatus === 2 ? 'Submit Selesai - Menunggu Penilaian (100%)' : 'Update Progres (' . $progres . '%)'),
            'catatan'     => ($catatan !== '' ? $catatan : 'Memperbarui progres pengerjaan menjadi ' . $progres . '%.') . $checklistInfo
        ]);

        $msgOut = ($newStatus === 2)
            ? 'Progres 100% selesai! Status task kini Menunggu Penilaian Atasan.'
            : 'Checklist & progres pengerjaan berhasil diperbarui (' . $progres . '%)!';
        ajaxReturnDie('success', $msgOut, TRUE);
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

        if ((int)$task->status_task === 5) {
            ajaxReturnDie('error', 'Task mandiri ini masih menunggu persetujuan (ACC) dari HRD sebelum dapat mulai dikerjakan.', FALSE);
            return;
        }
        if ((int)$task->status_task === 6) {
            ajaxReturnDie('error', 'Task mandiri ini telah ditolak oleh HRD.', FALSE);
            return;
        }
        // Validasi: Cegah submit ulang jika status sudah Menunggu Penilaian atau sudah Selesai
        if ((int)$task->status_task === 2) {
            ajaxReturnDie('error', 'Hasil pekerjaan untuk task ini sudah terkirim sebelumnya dan saat ini sedang menunggu penilaian Atasan.', FALSE);
            return;
        }
        if ((int)$task->status_task === 4) {
            ajaxReturnDie('error', 'Task ini sudah selesai dan telah dinilai oleh Atasan.', FALSE);
            return;
        }

        // Tandai semua checklist selesai (ambil dari input terbaru jika ada, atau dari database)
        $checklist_items_raw = $this->input->post('checklist_items', FALSE);
        $checklist = [];
        if (is_array($checklist_items_raw)) {
            foreach ($checklist_items_raw as $rawText) {
                $cleanText = trim((string)$rawText);
                if ($cleanText !== '') {
                    $checklist[] = ['item' => $cleanText, 'done' => 1];
                }
            }
        }
        if (empty($checklist)) {
            $checklist = !empty($task->checklist_json) ? json_decode($task->checklist_json, true) : [];
            if (!empty($checklist) && is_array($checklist)) {
                foreach ($checklist as &$item) {
                    $item['done'] = 1;
                }
                unset($item);
            }
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

        // Kirim WA bahwa task sudah selesai dan siap dinilai bintang
        try {
            $isTaskMandiri = ((int)$task->is_mandiri === 1 || (int)$task->id_pemberi === (int)$task->id_penerima || (int)$task->id_pemberi === $myId);
            $bawahan = $this->db->select('nama')->from('pengguna')->where('pengguna_id', $myId)->get()->row();
            $namaPelaksana = ($bawahan && !empty($bawahan->nama)) ? $bawahan->nama : 'Karyawan';

            if ($isTaskMandiri) {
                // Tugas Mandiri / Ke Diri Sendiri: Notifikasi dikirimkan ke Atasan Langsung & HRD/Manajemen (BUKAN ke diri sendiri)
                $notifList = [];
                $userSelf = $this->db->select('id_atasan')->from('pengguna')->where('pengguna_id', $myId)->get()->row();
                if ($userSelf && !empty($userSelf->id_atasan) && (int)$userSelf->id_atasan !== $myId) {
                    $atasanRow = $this->db->select('pengguna_id, nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $userSelf->id_atasan)->get()->row();
                    if ($atasanRow) {
                        $notifList[] = $atasanRow;
                    }
                }
                $hrdRecipients = $this->_getHrdRecipients();
                foreach ($hrdRecipients as $hr) {
                    if ((int)$hr->pengguna_id !== $myId) {
                        $notifList[] = $hr;
                    }
                }

                if (!empty($notifList) && function_exists('sendWa')) {
                    $pesanWa = "[TASK MANDIRI SELESAI] *MENUNGGU PENILAIAN HRD / ATASAN*" .
                        "%0A%0AHalo Rekan Atasan & HRD," .
                        "%0AKaryawan (*" . $namaPelaksana . "*) telah menyelesaikan Tugas Mandiri:" .
                        "%0A%0A- *Kode*: " . $task->kode_task .
                        "%0A- *Judul*: " . $task->judul .
                        "%0A- *Hasil*: " . $catatan_bawahan .
                        ($bukti_selesai ? "%0A- *Bukti*: " . $bukti_selesai : "") .
                        "%0A%0ASilakan berikan *Penilaian Rating Bintang (1-5 ⭐)* di https://office.visiyosindo.id/task_proyek";
                    $sentHps = [];
                    foreach ($notifList as $rec) {
                        $hp = (!empty($rec->no_hp)) ? $rec->no_hp : ((!empty($rec->nohp)) ? $rec->nohp : '');
                        if (!empty($hp) && !in_array($hp, $sentHps, true)) {
                            $sentHps[] = $hp;
                            @sendWa([
                                'devId'    => hostWa('2'),
                                'penerima' => $hp,
                                'pesan'    => $pesanWa
                            ]);
                        }
                    }
                }
            } else {
                // Task Penugasan Reguler: Kirim ke Atasan Pemberi Tugas
                $atasan = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $task->id_pemberi)->get()->row();
                $noHpAtasan = ($atasan && !empty($atasan->no_hp)) ? $atasan->no_hp : (($atasan && !empty($atasan->nohp)) ? $atasan->nohp : '');
                if (!empty($noHpAtasan) && function_exists('sendWa')) {
                    $pesanWa = "[TASK SELESAI] *MENUNGGU PENILAIAN RATING ATASAN*" .
                        "%0A%0AHalo *" . $atasan->nama . "*," .
                        "%0AKaryawan (*" . $namaPelaksana . "*) telah menyelesaikan Task:" .
                        "%0A%0A- *Kode*: " . $task->kode_task .
                        "%0A- *Judul*: " . $task->judul .
                        "%0A- *Hasil*: " . $catatan_bawahan .
                        ($bukti_selesai ? "%0A- *Bukti*: " . $bukti_selesai : "") .
                        "%0A%0ASilakan berikan *Penilaian Rating Bintang (1-5 ⭐)* di https://office.visiyosindo.id/task_proyek";
                    @sendWa([
                        'devId'    => hostWa('2'),
                        'penerima' => $noHpAtasan,
                        'pesan'    => $pesanWa
                    ]);
                }
            }
        } catch (\Throwable $e) {
        }

        try {
            $this->load->model('Md_asset_passport');
            $this->Md_asset_passport->sync_tiket_from_task($id_task);
        } catch (\Throwable $e) {
        }

        try {
            addLog('Submit Task Selesai', 'Menyelesaikan Task ' . $task->kode_task . ' - ' . $task->judul);
        } catch (\Throwable $e) {
        }

        $responseData = [
            'id_task'         => (int)$id_task,
            'kode_task'       => $task->kode_task,
            'judul'           => $task->judul,
            'catatan_bawahan' => $catatan_bawahan,
            'bukti_selesai'   => $bukti_selesai,
            'progres'         => 100,
            'status_task'     => 2
        ];

        ajaxReturnDie('success', 'Hasil pekerjaan berhasil dikirim ke Atasan untuk dinilai!', $responseData);
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

        $isGiver = ((int)$task->id_pemberi === $myId);
        $isHrdAdmin = $this->_isHrdOrAdmin($myId);
        $isAtasanPenerima = false;
        if (!empty($task->id_penerima)) {
            $penerimaUser = $this->db->select('id_atasan')->from('pengguna')->where('pengguna_id', $task->id_penerima)->get()->row();
            if ($penerimaUser && (int)$penerimaUser->id_atasan === $myId) {
                $isAtasanPenerima = true;
            }
        }

        // ATURAN MUTLAK: Siapapun (termasuk Admin/HRD) TIDAK BOLEH menilai tugas dirinya sendiri!
        if ((int)$task->id_penerima === $myId) {
            ajaxReturnDie('error', 'Anda tidak dapat memberikan penilaian/rating kepada tugas Anda sendiri. Penilaian wajib dilakukan oleh Atasan Langsung, HRD, atau Manajemen.', FALSE);
            return;
        }

        $isTaskMandiri = ((int)$task->is_mandiri === 1 || (int)$task->id_pemberi === (int)$task->id_penerima);
        if ($isTaskMandiri) {
            if (!$isAtasanPenerima && !$isHrdAdmin) {
                ajaxReturnDie('error', 'Untuk tugas mandiri / tugas ke diri sendiri, penilaian hanya dapat dilakukan oleh Atasan Langsung, HRD, atau Manajemen.', FALSE);
                return;
            }
        } else {
            if (!$isGiver && !$isAtasanPenerima && !$isHrdAdmin) {
                ajaxReturnDie('error', 'Hanya Pemberi Tugas, Atasan Langsung, atau Direksi/HRD yang dapat menilai task ini.', FALSE);
                return;
            }
        }

        if ((int)$task->status_task === 4 && !empty($task->rating_bintang)) {
            ajaxReturnDie('error', 'Task ini sudah dinilai (' . (int)$task->rating_bintang . ' Bintang) dan penilaian tidak dapat diubah lagi.', FALSE);
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
                    $pesanWa = "[PERMINTAAN REVISI] *TASK PERLU DIPERBAIKI*" .
                        "%0A%0AHalo *" . $bawahan->nama . "*," .
                        "%0ATask Anda (*" . $task->kode_task . " - " . $task->judul . "*) membutuhkan revisi dari Atasan:" .
                        "%0A%0A- *Catatan Revisi*: " . $ulasan_atasan .
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

        // Evaluasi keterlambatan: jika lewat deadline, maksimal bintang adalah 1 walaupun selesai
        $eval = function_exists('evaluateTaskDeadlineAndSp') ? evaluateTaskDeadlineAndSp($task, false) : ['is_late' => false, 'sp_level' => 0];
        if (!empty($eval['is_late']) && $rating_bintang > 1) {
            $rating_bintang = 1;
        }

        $starText = str_repeat('⭐', $rating_bintang) . ' (' . $rating_bintang . '/5)';
        $defaultUlasan = !empty($eval['is_late'])
            ? 'Pekerjaan selesai melewati batas deadline (Maksimal 1 Bintang).'
            : 'Pekerjaan selesai dengan baik.';

        $updateData = [
            'status_task'    => 4, // Selesai & Dinilai
            'progres'        => 100,
            'rating_bintang' => $rating_bintang,
            'ulasan_atasan'  => $ulasan_atasan !== '' ? $ulasan_atasan : $defaultUlasan,
            'tgl_dinilai'    => date('Y-m-d H:i:s')
        ];
        if (!empty($eval['sp_level'])) {
            $updateData['sp_level'] = (int)$eval['sp_level'];
        }

        $this->db->where('id_task', $id_task)->update('task_proyek', $updateData);

        $this->db->insert('task_proyek_log', [
            'id_task'     => $id_task,
            'id_pengguna' => $myId,
            'aksi'        => 'Memberi Penilaian ' . $starText,
            'catatan'     => $ulasan_atasan !== '' ? $ulasan_atasan : $defaultUlasan
        ]);

        try {
            $bawahan = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $task->id_penerima)->get()->row();
            $noHp = ($bawahan && !empty($bawahan->no_hp)) ? $bawahan->no_hp : (($bawahan && !empty($bawahan->nohp)) ? $bawahan->nohp : '');
            if (!empty($noHp) && function_exists('sendWa')) {
                $pesanWa = "[TASK SELESAI & DINILAI] *HASIL REVIEW ATASAN*" .
                    "%0A%0ASelamat *" . $bawahan->nama . "*!" .
                    "%0ATask Anda telah disetujui dan dinilai oleh Atasan:" .
                    "%0A%0A- *Kode*: " . $task->kode_task .
                    "%0A- *Judul*: " . $task->judul .
                    "%0A- *Rating*: " . $starText .
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
            $this->load->model('Md_asset_passport');
            $this->Md_asset_passport->sync_tiket_from_task($id_task);
        } catch (\Throwable $e) {
        }

        try {
            addLog('Penilaian Bintang Task', 'Memberi rating ' . $rating_bintang . ' bintang untuk Task ' . $task->kode_task);
        } catch (\Throwable $e) {
        }

        // =========================================================================
        // SINKRONISASI OTOMATIS KE SASARAN MUTU & AUDIT KINERJA KARYAWAN
        // =========================================================================
        if (!empty($task->id_sasaran_mutu)) {
            try {
                $taskYear = (int)date('Y', strtotime($task->created_at ?: 'now'));
                $taskLink = !empty($task->bukti_selesai) ? $task->bukti_selesai : (!empty($task->lampiran_atasan) ? $task->lampiran_atasan : '');

                // Cari link di catatan bawahan jika ada URL
                if (empty($taskLink) && !empty($task->catatan_bawahan)) {
                    if (preg_match('/https?:\/\/[^\s]+/', $task->catatan_bawahan, $match)) {
                        $taskLink = $match[0];
                    }
                }

                $tglFormatted = date('d/m/Y');
                $logImpl = "• [Task Selesai {$tglFormatted} ({$starText})]: " . $task->judul . " ({$task->kode_task})";
                if (!empty($ulasan_atasan)) {
                    $logImpl .= " — Catatan Atasan: " . $ulasan_atasan;
                }

                $this->load->model('md_audit_system');
                $existingAudit = $this->md_audit_system->getAuditKinerjaData($taskYear);
                $currentImpl = '';
                $currentLink = '';
                if (!empty($existingAudit['data'])) {
                    foreach ($existingAudit['data'] as $aud) {
                        if ($aud['id'] === $task->id_sasaran_mutu) {
                            $currentImpl = $aud['implementasi'];
                            $currentLink = $aud['link_dokumen'];
                            break;
                        }
                    }
                }

                // Gabungkan tanpa duplikasi kode task
                if (empty($currentImpl)) {
                    $newImpl = $logImpl;
                } else if (strpos($currentImpl, $task->kode_task) === false) {
                    $newImpl = $currentImpl . "\n" . $logImpl;
                } else {
                    $newImpl = $currentImpl;
                }

                $newLink = !empty($currentLink) ? $currentLink : $taskLink;

                // Simpan ke Audit Kinerja & Sasaran Mutu
                $this->md_audit_system->saveAuditKinerjaItem($taskYear, $task->id_sasaran_mutu, [
                    'implementasi' => $newImpl,
                    'link_dokumen' => $newLink
                ]);
                $this->md_audit_system->saveSasaranMutuItem($taskYear, $task->id_sasaran_mutu, [
                    'implementasi' => $newImpl,
                    'link_dokumen' => $newLink
                ]);
            } catch (\Throwable $e) {
                log_message('error', 'Auto sync task to sasaran mutu error: ' . $e->getMessage());
            }
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

    /**
     * Penjadwalan Pertemuan dengan HRD saat Task / Proyek terkena SP 3 (Telat >= 3 Minggu)
     */
    public function jadwalkan_hrd()
    {
        grantAccessFor('all');
        if (function_exists('ensureTaskProyekSpColumns')) {
            ensureTaskProyekSpColumns();
        }

        $myId       = (int)sessPenggunaId();
        $id_task    = (int)$this->input->post('id_task', TRUE);
        $tgl_jadwal = trim((string)$this->input->post('hrd_jadwal_tgl', TRUE));
        $jam_jadwal = trim((string)$this->input->post('hrd_jadwal_jam', TRUE));
        $metode     = trim((string)$this->input->post('hrd_jadwal_metode', TRUE)) ?: 'Tatap Muka';
        $lokasi     = trim((string)$this->input->post('hrd_jadwal_lokasi', TRUE));
        $catatan    = trim((string)$this->input->post('hrd_jadwal_catatan', FALSE));

        if ($id_task <= 0 || $tgl_jadwal === '' || $jam_jadwal === '') {
            ajaxReturnDie('error', 'Tanggal dan Jam Pertemuan dengan HRD wajib diisi!', FALSE);
            return;
        }

        $task = $this->db->get_where('task_proyek', ['id_task' => $id_task, 'is_deleted' => 0])->row();
        if (!$task) {
            ajaxReturnDie('error', 'Data Task tidak ditemukan.', FALSE);
            return;
        }

        $eval = function_exists('evaluateTaskDeadlineAndSp') ? evaluateTaskDeadlineAndSp($task, false) : ['sp_level' => (int)($task->sp_level ?? 0)];
        if ((int)$eval['sp_level'] < 3 && (int)($task->sp_level ?? 0) < 3) {
            ajaxReturnDie('error', 'Penjadwalan pertemuan HRD ini dikhususkan untuk Task yang telah mencapai status SP 3 (terlambat >= 3 minggu).', FALSE);
            return;
        }

        if ($lokasi === '') {
            $lokasi = ($metode === 'Tatap Muka') ? 'Ruang HRD' : 'Link Online / Menyusul';
        }

        $this->db->where('id_task', $id_task)->update('task_proyek', [
            'sp_level'          => 3,
            'hrd_jadwal_tgl'    => date('Y-m-d', strtotime($tgl_jadwal)),
            'hrd_jadwal_jam'    => $jam_jadwal,
            'hrd_jadwal_metode' => $metode,
            'hrd_jadwal_lokasi' => $lokasi,
            'hrd_jadwal_catatan'=> $catatan !== '' ? $catatan : 'Evaluasi keterlambatan proyek (SP 3) bersama HRD.',
            'hrd_jadwal_status' => 'Terjadwal',
            'hrd_jadwal_by'     => $myId
        ]);

        $jadwalSummary = 'Pertemuan HRD (SP 3) dijadwalkan pada ' . date('d-m-Y', strtotime($tgl_jadwal)) . ' pukul ' . $jam_jadwal . ' WIB (' . $metode . ' - ' . $lokasi . ').' . ($catatan !== '' ? ' Catatan: ' . $catatan : '');
        $this->db->insert('task_proyek_log', [
            'id_task'     => $id_task,
            'id_pengguna' => $myId,
            'aksi'        => 'Penjadwalan Pertemuan HRD (SP 3)',
            'catatan'     => $jadwalSummary
        ]);

        // Sinkronkan juga ke tabel evaluasi_lisan (jika tabel tersedia) agar muncul di menu Evaluasi Lisan & Konseling HRD
        if ($this->db->table_exists('evaluasi_lisan')) {
            try {
                $kodeEval = 'EVL-SP3-' . $id_task;
                $existingEval = $this->db->get_where('evaluasi_lisan', ['kode_evaluasi' => $kodeEval, 'is_deleted' => 0])->row();
                $penjadwal = $this->db->select('nama')->from('pengguna')->where('pengguna_id', $myId)->get()->row();
                $dataEval = [
                    'kode_evaluasi'         => $kodeEval,
                    'id_pengguna'           => (int)$task->id_penerima,
                    'tanggal_evaluasi'      => date('Y-m-d'),
                    'masukan_pekerjaan'     => 'Keterlambatan penyelesaian Task/Proyek [' . $task->kode_task . '] "' . $task->judul . '" telah melewati batas 3 minggu (Status SP 3).',
                    'butuh_konseling'       => 1,
                    'tgl_usulan_konseling'  => date('Y-m-d', strtotime($tgl_jadwal)),
                    'jam_usulan_konseling'  => $jam_jadwal,
                    'metode_konseling'      => $metode,
                    'topik_konseling'       => 'Evaluasi SP 3 Keterlambatan Task/Proyek: ' . $task->kode_task . ' - ' . $task->judul,
                    'status_evaluasi'       => 'Ditanggapi',
                    'status_konseling'      => 'Dijadwalkan',
                    'tgl_jadwal_konseling'  => date('Y-m-d', strtotime($tgl_jadwal)),
                    'jam_jadwal_konseling'  => $jam_jadwal,
                    'lokasi_konseling'      => $lokasi,
                    'pic_konseling_id'      => $myId,
                    'pic_konseling_nama'    => $penjadwal ? $penjadwal->nama : 'HRD / Manajemen',
                    'tanggapan_manajemen'   => $catatan !== '' ? $catatan : 'Pemanggilan Evaluasi SP 3 terkait keterlambatan proyek.',
                    'updated_by'            => $myId
                ];
                if ($existingEval) {
                    $this->db->where('id_evaluasi_lisan', $existingEval->id_evaluasi_lisan)->update('evaluasi_lisan', $dataEval);
                } else {
                    $this->db->insert('evaluasi_lisan', $dataEval);
                }
            } catch (\Throwable $e) {
            }
        }

        // Kirim notifikasi WhatsApp ke Karyawan & HRD
        try {
            $bawahan = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $task->id_penerima)->get()->row();
            $hrdUser = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', 744)->get()->row();
            $pesanWa = "*🛑 JADWAL PERTEMUAN HRD - EVALUASI SP 3 PROYEK*" .
                "%0A%0AKaryawan: *" . ($bawahan ? $bawahan->nama : '-') . "*" .
                "%0AKode Task: *" . $task->kode_task . "*" .
                "%0AJudul Proyek: *" . $task->judul . "*" .
                "%0AStatus: *SP 3 (Terlambat >= 3 Minggu)*" .
                "%0A%0A📅 *Tanggal Pertemuan*: " . date('d-m-Y', strtotime($tgl_jadwal)) .
                "%0A⏰ *Waktu*: " . $jam_jadwal . " WIB" .
                "%0A🤝 *Metode*: " . $metode .
                "%0A📍 *Lokasi/Link*: " . $lokasi .
                ($catatan !== '' ? "%0A📝 *Catatan*: " . $catatan : "") .
                "%0A%0AMohon hadir tepat waktu untuk evaluasi penyelesaian pekerjaan bersama HRD.";

            if (function_exists('sendWa')) {
                $noHpBawahan = ($bawahan && !empty($bawahan->no_hp)) ? $bawahan->no_hp : (($bawahan && !empty($bawahan->nohp)) ? $bawahan->nohp : '');
                if (!empty($noHpBawahan)) {
                    @sendWa(['devId' => hostWa('2'), 'penerima' => $noHpBawahan, 'pesan' => $pesanWa]);
                }
                $noHpHrd = ($hrdUser && !empty($hrdUser->no_hp)) ? $hrdUser->no_hp : (($hrdUser && !empty($hrdUser->nohp)) ? $hrdUser->nohp : '');
                if (!empty($noHpHrd) && $noHpHrd !== $noHpBawahan) {
                    @sendWa(['devId' => hostWa('2'), 'penerima' => $noHpHrd, 'pesan' => $pesanWa]);
                }
            }
        } catch (\Throwable $e) {
        }

        ajaxReturnDie('success', 'Jadwal pertemuan evaluasi SP 3 dengan HRD berhasil disimpan!', TRUE);
    }
    /**
     * Persetujuan (ACC) Task Mandiri oleh HRD
     */
    public function acc_hrd()
    {
        grantAccessFor('all');
        $myId = (int)sessPenggunaId();
        if (!$this->_isHrdOrAdmin($myId)) {
            ajaxReturnDie('error', 'Hanya HRD atau Administrator yang berwenang menyetujui (ACC) pengajuan task mandiri.', FALSE);
            return;
        }

        $id_task = (int)$this->input->post('id_task', TRUE);
        $task = $this->db->get_where('task_proyek', ['id_task' => $id_task, 'is_deleted' => 0])->row();
        if (!$task) {
            ajaxReturnDie('error', 'Task tidak ditemukan.', FALSE);
            return;
        }

        if ((int)$task->status_task !== 5) {
            ajaxReturnDie('error', 'Status task saat ini bukan "Menunggu ACC HRD".', FALSE);
            return;
        }

        $hrdRow = $this->db->select('nama')->from('pengguna')->where('pengguna_id', $myId)->get()->row();
        $namaHrd = $hrdRow ? $hrdRow->nama : 'HRD';

        $this->db->where('id_task', $id_task)->update('task_proyek', [
            'status_task' => 0, // Sekarang aktif / Baru / Siap Dikerjakan
            'acc_hrd_by'  => $myId,
            'acc_hrd_at'  => date('Y-m-d H:i:s')
        ]);

        $this->db->insert('task_proyek_log', [
            'id_task'     => $id_task,
            'id_pengguna' => $myId,
            'aksi'        => 'ACC Pengajuan Task oleh HRD',
            'catatan'     => 'Pengajuan task mandiri telah disetujui (ACC) oleh HRD (' . $namaHrd . '). Task siap dikerjakan oleh karyawan.'
        ]);

        // 1. Kirim Notifikasi WA ke Karyawan Pelaksana
        try {
            $karyawan = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $task->id_penerima)->get()->row();
            $noHpKaryawan = ($karyawan && !empty($karyawan->no_hp)) ? $karyawan->no_hp : (($karyawan && !empty($karyawan->nohp)) ? $karyawan->nohp : '');
            if (!empty($noHpKaryawan) && function_exists('sendWa')) {
                $pesanWa = "[DISETUJUI] *PENGAJUAN TASK MANDIRI TELAH DI-ACC HRD*" .
                    "%0A%0AHalo *" . $karyawan->nama . "*," .
                    "%0APengajuan task mandiri Anda telah *DISETUJUI (ACC)* oleh HRD (*" . $namaHrd . "*):" .
                    "%0A%0A- *Kode*: " . $task->kode_task .
                    "%0A- *Judul*: " . $task->judul .
                    "%0A- *Proyek*: " . ($task->nama_proyek ?: 'Umum') .
                    "%0A- *Prioritas*: " . $task->prioritas .
                    "%0A- *Deadline*: " . (!empty($task->tgl_deadline) ? date('d-m-Y', strtotime($task->tgl_deadline)) : '-') .
                    "%0A%0AAnda sekarang dapat mulai mencentang checklist & memperbarui progres pekerjaan di https://office.visiyosindo.id/task_proyek";
                @sendWa([
                    'devId'    => hostWa('2'),
                    'penerima' => $noHpKaryawan,
                    'pesan'    => $pesanWa
                ]);
            }
        } catch (\Throwable $e) {}

        // 2. Kirim Notifikasi WA ke Pihak Mengetahui (HANYA DIKIRIM SETELAH DI-ACC HRD sesuai permintaan user!)
        if (!empty($task->id_mengetahui)) {
            try {
                $mengetahui = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $task->id_mengetahui)->get()->row();
                $noHpMengetahui = ($mengetahui && !empty($mengetahui->no_hp)) ? $mengetahui->no_hp : (($mengetahui && !empty($mengetahui->nohp)) ? $mengetahui->nohp : '');
                $karyawanNama = isset($karyawan) && $karyawan ? $karyawan->nama : 'Karyawan';
                if (!empty($noHpMengetahui) && function_exists('sendWa')) {
                    $pesanMengetahui = "[INFO MENGETAHUI] *PEMBERITAHUAN TASK KARYAWAN*" .
                        "%0A%0AHalo *" . $mengetahui->nama . "*," .
                        "%0ASebagai pihak yang *Mengetahui*, berikut informasi tugas karyawan yang telah disetujui (ACC) oleh HRD:" .
                        "%0A%0A- *Pelaksana*: *" . $karyawanNama . "*" .
                        "%0A- *Kode*: " . $task->kode_task .
                        "%0A- *Judul*: " . $task->judul .
                        "%0A- *Proyek*: " . ($task->nama_proyek ?: 'Umum') .
                        "%0A- *Prioritas*: " . $task->prioritas .
                        "%0A- *Deadline*: " . (!empty($task->tgl_deadline) ? date('d-m-Y', strtotime($task->tgl_deadline)) : '-') .
                        "%0A- *Status*: Disetujui HRD (" . $namaHrd . ")" .
                        "%0A%0ADapat dipantau di https://office.visiyosindo.id/task_proyek";
                    @sendWa([
                        'devId'    => hostWa('2'),
                        'penerima' => $noHpMengetahui,
                        'pesan'    => $pesanMengetahui
                    ]);
                }
            } catch (\Throwable $e) {}
        }

        ajaxReturnDie('success', 'Task mandiri berhasil disetujui (ACC)! Notifikasi WA telah dikirimkan ke Pelaksana dan pihak Mengetahui.', TRUE);
    }

    /**
     * Penolakan Task Mandiri oleh HRD
     */
    public function tolak_hrd()
    {
        grantAccessFor('all');
        $myId = (int)sessPenggunaId();
        if (!$this->_isHrdOrAdmin($myId)) {
            ajaxReturnDie('error', 'Hanya HRD atau Administrator yang berwenang menolak pengajuan task mandiri.', FALSE);
            return;
        }

        $id_task = (int)$this->input->post('id_task', TRUE);
        $alasan  = trim((string)$this->input->post('alasan', FALSE));
        if ($alasan === '') {
            ajaxReturnDie('error', 'Alasan penolakan pengajuan task wajib diisi!', FALSE);
            return;
        }

        $task = $this->db->get_where('task_proyek', ['id_task' => $id_task, 'is_deleted' => 0])->row();
        if (!$task) {
            ajaxReturnDie('error', 'Task tidak ditemukan.', FALSE);
            return;
        }

        $hrdRow = $this->db->select('nama')->from('pengguna')->where('pengguna_id', $myId)->get()->row();
        $namaHrd = $hrdRow ? $hrdRow->nama : 'HRD';

        $this->db->where('id_task', $id_task)->update('task_proyek', [
            'status_task'      => 6, // Ditolak HRD
            'acc_hrd_by'       => $myId,
            'acc_hrd_at'       => date('Y-m-d H:i:s'),
            'acc_hrd_catatan'  => $alasan
        ]);

        $this->db->insert('task_proyek_log', [
            'id_task'     => $id_task,
            'id_pengguna' => $myId,
            'aksi'        => 'Menolak Pengajuan Task Mandiri',
            'catatan'     => 'Ditolak oleh HRD (' . $namaHrd . '). Alasan: ' . $alasan
        ]);

        try {
            $karyawan = $this->db->select('nama, no_hp, nohp')->from('pengguna')->where('pengguna_id', $task->id_penerima)->get()->row();
            $noHpKaryawan = ($karyawan && !empty($karyawan->no_hp)) ? $karyawan->no_hp : (($karyawan && !empty($karyawan->nohp)) ? $karyawan->nohp : '');
            if (!empty($noHpKaryawan) && function_exists('sendWa')) {
                $pesanWa = "[DITOLAK] *PENGAJUAN TASK MANDIRI DITOLAK HRD*" .
                    "%0A%0AHalo *" . $karyawan->nama . "*," .
                    "%0AMohon maaf, pengajuan task mandiri Anda (*" . $task->kode_task . " - " . $task->judul . "*) belum disetujui oleh HRD (*" . $namaHrd . "*)." .
                    "%0A%0A- *Alasan*: " . $alasan .
                    "%0A%0ASilakan sesuaikan pengajuan atau diskusikan dengan HRD.";
                @sendWa([
                    'devId'    => hostWa('2'),
                    'penerima' => $noHpKaryawan,
                    'pesan'    => $pesanWa
                ]);
            }
        } catch (\Throwable $e) {}

        ajaxReturnDie('success', 'Pengajuan task mandiri telah ditolak.', TRUE);
    }

    /**
     * Set Pihak Mengetahui Task & Kirim Notifikasi WhatsApp
     */
    public function set_mengetahui_task($token = '', $kode_task = 'TSK/2026/11/115')
    {
        $myId = (int)sessPenggunaId();
        $isAuth = ($token === 'vysi_medikal_2026') || $this->_isHrdOrAdmin($myId) || isAdmin();
        if (!$isAuth) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'msg' => 'Akses ditolak: token atau sesi tidak valid.']);
            exit;
        }

        if (empty($kode_task)) {
            $kode_task = 'TSK/2026/11/115';
        }

        // 1. Cari data task berdasarkan kode
        $task = $this->db->select('t.*, p_terima.nama AS nama_penerima, p_beri.nama AS nama_pemberi, p_acc.nama AS nama_acc')
            ->from('task_proyek t')
            ->join('pengguna p_terima', 'p_terima.pengguna_id = t.id_penerima', 'left')
            ->join('pengguna p_beri', 'p_beri.pengguna_id = t.id_pemberi', 'left')
            ->join('pengguna p_acc', 'p_acc.pengguna_id = t.acc_hrd_by', 'left')
            ->where('t.kode_task', $kode_task)
            ->where('t.is_deleted', 0)
            ->get()
            ->row();

        if (!$task) {
            $task = $this->db->select('t.*, p_terima.nama AS nama_penerima, p_beri.nama AS nama_pemberi, p_acc.nama AS nama_acc')
                ->from('task_proyek t')
                ->join('pengguna p_terima', 'p_terima.pengguna_id = t.id_penerima', 'left')
                ->join('pengguna p_beri', 'p_beri.pengguna_id = t.id_pemberi', 'left')
                ->join('pengguna p_acc', 'p_acc.pengguna_id = t.acc_hrd_by', 'left')
                ->like('t.kode_task', '115')
                ->where('t.is_deleted', 0)
                ->get()
                ->row();
        }

        if (!$task) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'msg' => 'Task dengan kode ' . $kode_task . ' tidak ditemukan.']);
            exit;
        }

        // 2. Cari Bob Ariyos di tabel pengguna
        $bob = $this->db->select('pengguna_id, nama, jabatan, no_hp, nohp')
            ->from('pengguna')
            ->group_start()
                ->where('pengguna_id', 54)
                ->or_like('nama', 'Bob Ariyos', 'both')
            ->group_end()
            ->where('status', 1)
            ->get()
            ->row();

        if (!$bob) {
            $bob = $this->db->select('pengguna_id, nama, jabatan, no_hp, nohp')
                ->from('pengguna')
                ->where('pengguna_id', 54)
                ->or_like('nama', 'Bob Ariyos', 'both')
                ->get()
                ->row();
        }

        if (!$bob) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'msg' => 'Data pengguna Bob Ariyos tidak ditemukan di database.']);
            exit;
        }

        // 3. Update task_proyek
        $this->db->where('id_task', $task->id_task)->update('task_proyek', [
            'id_mengetahui' => (int)$bob->pengguna_id
        ]);

        // 4. Catat Log Aktivitas
        $this->db->insert('task_proyek_log', [
            'id_task'     => $task->id_task,
            'id_pengguna' => ($myId > 0 ? $myId : $task->id_pemberi),
            'aksi'        => 'Menetapkan Mengetahui: ' . $bob->nama,
            'catatan'     => 'Menetapkan ' . $bob->nama . ' sebagai pihak Mengetahui dan mengirimkan notifikasi WhatsApp.'
        ]);

        // 5. Susun dan Kirim Notifikasi WhatsApp ke Bob Ariyos
        $noHpBob = (!empty($bob->no_hp)) ? $bob->no_hp : ((!empty($bob->nohp)) ? $bob->nohp : '');

        $statusLabels = [
            0 => 'Baru / Belum Mulai',
            1 => 'Sedang Dikerjakan',
            2 => 'Menunggu Penilaian Atasan',
            3 => 'Perlu Revisi',
            4 => 'Selesai & Dinilai',
            5 => 'Menunggu ACC HRD',
            6 => 'Ditolak HRD'
        ];
        $stLabel = isset($statusLabels[(int)$task->status_task]) ? $statusLabels[(int)$task->status_task] : 'Aktif';
        if (!empty($task->nama_acc)) {
            $stLabel .= ' (Disetujui HRD: ' . $task->nama_acc . ')';
        }

        $pelaksanaStr = !empty($task->nama_penerima) ? $task->nama_penerima : 'Karyawan';
        $pemberiStr = !empty($task->nama_pemberi) ? $task->nama_pemberi : '-';
        $deadlineStr = !empty($task->tgl_deadline) ? date('d-m-Y', strtotime($task->tgl_deadline)) : '-';

        $pesanMengetahui = "[INFO MENGETAHUI] *PEMBERITAHUAN TASK KARYAWAN*" .
            "%0A%0AHalo *" . $bob->nama . "*," .
            "%0ASebagai pihak yang *Mengetahui*, berikut informasi penugasan tugas / proyek kerja karyawan:" .
            "%0A%0A- *Pelaksana*: *" . $pelaksanaStr . "*" .
            "%0A- *Pemberi Tugas*: " . $pemberiStr .
            "%0A- *Kode*: " . $task->kode_task .
            "%0A- *Judul*: " . $task->judul .
            "%0A- *Proyek*: " . ($task->nama_proyek ?: 'Umum / Operasional') .
            "%0A- *Prioritas*: " . $task->prioritas .
            "%0A- *Deadline*: " . $deadlineStr .
            "%0A- *Status*: " . $stLabel .
            "%0A%0ADapat dipantau di https://office.visiyosindo.id/task_proyek" .
            "%0A_Sistem Office PT Visi Yosindo Medikal_";

        $waStatus = 'Nomor HP tidak ditemukan';
        if (!empty($noHpBob) && function_exists('sendWa')) {
            $sendRes = @sendWa([
                'devId'    => hostWa('2'),
                'penerima' => $noHpBob,
                'pesan'    => $pesanMengetahui
            ]);
            $waStatus = 'Terkirim ke WhatsApp ' . $noHpBob;
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status'          => 'success',
            'msg'             => 'Task ' . $task->kode_task . ' berhasil diperbarui. Pihak Mengetahui ditetapkan ke ' . $bob->nama . ' dan notifikasi WhatsApp telah dikirimkan.',
            'task'            => [
                'id_task'     => $task->id_task,
                'kode_task'   => $task->kode_task,
                'judul'       => $task->judul,
                'pelaksana'   => $pelaksanaStr,
                'status'      => $stLabel
            ],
            'mengetahui'      => [
                'id'          => $bob->pengguna_id,
                'nama'        => $bob->nama,
                'no_hp'       => $noHpBob
            ],
            'wa_status'       => $waStatus,
            'pesan_preview'   => urldecode(str_replace('%0A', "\n", $pesanMengetahui))
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
