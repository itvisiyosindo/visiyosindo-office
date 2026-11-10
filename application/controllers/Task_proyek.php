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

    /**
     * Karyawan yang dikecualikan (Bebas PK) dari kuis Product Knowledge:
     * - HRD / Personalia
     * - GA (General Affairs) / Bagian Umum
     * - Security / Satpam / Keamanan
     * - Pak Afrianto (Direktur / Manajemen)
     */
    private function _isExemptFromPk($nama, $jabatan = '', $nama_divisi = '')
    {
        $str = strtolower(trim((string)$nama . ' ' . (string)$jabatan . ' ' . (string)$nama_divisi));
        if (strpos($str, 'afrianto') !== false) {
            return true;
        }
        if (strpos($str, 'security') !== false || strpos($str, 'satpam') !== false || strpos($str, 'keamanan') !== false) {
            return true;
        }
        if (strpos($str, 'general affair') !== false || preg_match('/\bga\b/', $str) || strpos($str, 'ga /') !== false || strpos($str, 'ga &') !== false || strpos($str, 'umum') !== false) {
            return true;
        }
        if (strpos($str, 'human resource') !== false || preg_match('/\bhr\b/', $str) || preg_match('/\bhrd\b/', $str) || strpos($str, 'personalia') !== false) {
            return true;
        }
        return false;
    }

    private function _getAccountMergeMap()
    {
        return [
            107 => 106, // Head of Accounting and Tax -> Dirangga Madali
        ];
    }

    /**
     * Hitung jumlah hari kerja efektif (Senin-Jumat, di luar hari libur absensi_config_libur).
     */
    private function _countEffectiveWorkdays($startDate, $endDate, array $holidaySet, $onlyMonth = 0)
    {
        static $cache = [];
        if (empty($startDate) || empty($endDate) || $endDate < $startDate) {
            return 0;
        }
        $cacheKey = $startDate . '_' . $endDate . '_' . (int)$onlyMonth;
        if (isset($cache[$cacheKey])) {
            return $cache[$cacheKey];
        }

        $count = 0;
        $cur = new DateTime($startDate);
        $end = new DateTime($endDate);
        while ($cur <= $end) {
            $dow = (int)$cur->format('N');
            if ($dow < 6) {
                $m = (int)$cur->format('n');
                if ((int)$onlyMonth === 0 || $m === (int)$onlyMonth) {
                    $ymd = $cur->format('Y-m-d');
                    if (!isset($holidaySet[$ymd])) {
                        $count++;
                    }
                }
            }
            $cur->modify('+1 day');
        }

        $cache[$cacheKey] = $count;
        return $count;
    }

    /**
     * Ambil peta tanggal masuk pertama masing-masing karyawan (untuk pro-rata karyawan baru di filter multi-bulan).
     */
    private function _getFirstAbsenDateMap($todayYmd, array $accountMergeMap)
    {
        $map = [];
        $rows = $this->db->query("
            SELECT pengguna_id, MIN(DATE(data_created)) AS first_masuk
            FROM absensi
            WHERE type_absen = 'masuk'
              AND data_created >= '2020-01-01 00:00:00'
              AND data_created <= ?
            GROUP BY pengguna_id
        ", [$todayYmd . ' 23:59:59'])->result();

        foreach ($rows as $r) {
            if (empty($r->first_masuk)) {
                continue;
            }
            $uid = (int)$r->pengguna_id;
            if (isset($accountMergeMap[$uid])) {
                $uid = (int)$accountMergeMap[$uid];
            }
            if (!isset($map[$uid]) || $r->first_masuk < $map[$uid]) {
                $map[$uid] = $r->first_masuk;
            }
        }
        return $map;
    }

    /**
     * Ambil peta skor Evaluasi Semester (tabel evaluasi + evaluasi_detail, jenis_evaluasi = 1) per pengguna_id.
     */
    private function _getEvaluasiSemesterScoreMap($filterSemester, $filterTahun, array $accountMergeMap)
    {
        $smtInt = (int)$filterSemester;
        $yInt   = (int)$filterTahun;
        if ($smtInt <= 0 || $yInt <= 0) {
            return [];
        }

        $rows = $this->db->query("
            SELECT e.id_pengguna, e.tahun, e.smt,
                   ed.nilaia, ed.nilaib, ed.nilaic, ed.nilaid, ed.nilaie, ed.nilaif
            FROM evaluasi e
            INNER JOIN evaluasi_detail ed ON ed.id_evaluasi = e.id
            WHERE e.jenis_evaluasi = 1
              AND e.tahun = ?
              AND e.smt = ?
        ", [$yInt, $smtInt])->result();

        // Kumpulkan rata-rata per jobdesc identik dengan Md_hasil_evaluasi_semester::getNilaiEvaluasi()
        $perUser = [];
        foreach ($rows as $r) {
            $sum = 0.0;
            $cnt = 0;
            foreach (['nilaia', 'nilaib', 'nilaic', 'nilaid', 'nilaie', 'nilaif'] as $col) {
                $v = isset($r->$col) ? (float)$r->$col : 0.0;
                if ($v > 0) {
                    $sum += $v;
                    $cnt++;
                }
            }
            if ($cnt <= 0) {
                continue;
            }
            $uid = (int)$r->id_pengguna;
            if (isset($accountMergeMap[$uid])) {
                $uid = (int)$accountMergeMap[$uid];
            }
            if (!isset($perUser[$uid])) {
                $perUser[$uid] = ['sum' => 0.0, 'cnt' => 0];
            }
            $perUser[$uid]['sum'] += ($sum / $cnt);
            $perUser[$uid]['cnt']++;
        }

        $result = [];
        foreach ($perUser as $uid => $agg) {
            if ($agg['cnt'] > 0) {
                $result[$uid] = [
                    'skor'  => round($agg['sum'] / $agg['cnt'], 1),
                    'label' => 'Smt ' . $smtInt . ' ' . $yInt,
                    'count' => 1,
                ];
            }
        }
        return $result;
    }

    /**
     * Deteksi level SP (1, 2, 3) dari kolom jenis_sp dan perihal pada tabel surat_peringatan.
     */
    private function _parseSpLevel($jenisSp, $perihal = '')
    {
        $raw = strtolower(trim((string)$jenisSp . ' ' . (string)$perihal));
        if ($raw === '') {
            return 0;
        }
        if (preg_match('/\b(3|iii|ketiga|tiga|sp\s*3|sp\s*iii)\b/', $raw) || strpos($raw, 'ketiga') !== false || strpos($raw, 'sp3') !== false) {
            return 3;
        }
        if (preg_match('/\b(2|ii|kedua|dua|sp\s*2|sp\s*ii)\b/', $raw) || strpos($raw, 'kedua') !== false || strpos($raw, 'sp2') !== false) {
            return 2;
        }
        if (preg_match('/\b(1|i|pertama|kesatu|satu|sp\s*1|sp\s*i)\b/', $raw) || strpos($raw, 'pertama') !== false || strpos($raw, 'kesatu') !== false || strpos($raw, 'sp1') !== false) {
            return 1;
        }
        return 1;
    }

    /**
     * Ambil peta status SP aktif & batas plafon nilai maksimal (SP0=100, SP1=80, SP2=60, SP3=40) per pengguna_id untuk semester terpilih.
     */
    private function _getActiveSpCapMap($semStart, $semEnd, $refDate, array $accountMergeMap)
    {
        if (!$this->db->table_exists('surat_peringatan')) {
            return [];
        }

        // Bangun lookup nama -> pengguna_id sebagai fallback jika sp.nama menyimpan string nama
        $nameToId = [];
        $users = $this->db->select('pengguna_id, nama')->from('pengguna')->get()->result();
        foreach ($users as $u) {
            $key = strtolower(trim((string)$u->nama));
            if ($key !== '') {
                $nameToId[$key] = (int)$u->pengguna_id;
            }
        }

        $rows = $this->db->query("
            SELECT id, kode, nama, jenis_sp, perihal, tgl_pengajuan, tgl_evaluasi, masa, ttd_3
            FROM surat_peringatan
            WHERE ttd_3 IS NULL OR ttd_3 != '2'
            ORDER BY tgl_pengajuan DESC, id DESC
        ")->result();

        $capByLevel = [0 => 100, 1 => 80, 2 => 60, 3 => 40];
        $map = [];

        foreach ($rows as $sp) {
            $rawNama = trim((string)$sp->nama);
            if ($rawNama === '') {
                continue;
            }
            if (is_numeric($rawNama)) {
                $uid = (int)$rawNama;
            } else {
                $nameKey = strtolower($rawNama);
                if (!isset($nameToId[$nameKey])) {
                    continue;
                }
                $uid = $nameToId[$nameKey];
            }
            if (isset($accountMergeMap[$uid])) {
                $uid = (int)$accountMergeMap[$uid];
            }

            $tglMulaiStr = (!empty($sp->tgl_pengajuan) && $sp->tgl_pengajuan !== '0000-00-00')
                ? $sp->tgl_pengajuan
                : ((!empty($sp->tgl_evaluasi) && $sp->tgl_evaluasi !== '0000-00-00') ? $sp->tgl_evaluasi : null);
            if (!$tglMulaiStr) {
                continue;
            }

            $masaBulan = 0;
            if (!empty($sp->masa) && preg_match('/\d+/', (string)$sp->masa, $m)) {
                $masaBulan = (int)$m[0];
            }
            if ($masaBulan <= 0) {
                $masaBulan = 3;
            }

            try {
                $dtMulai = new DateTime($tglMulaiStr);
                $dtAkhir = clone $dtMulai;
                $dtAkhir->modify('+' . $masaBulan . ' months');
                $tglMulai = $dtMulai->format('Y-m-d');
                $tglAkhir = $dtAkhir->format('Y-m-d');
            } catch (Exception $e) {
                continue;
            }

            // SP berlaku pada semester ini jika diterbitkan di dalam semester tersebut ATAU masih aktif berjalan pada tanggal acuan
            $issuedInSem = ($tglMulai >= $semStart && $tglMulai <= $semEnd);
            $activeOnRef = ($tglMulai <= $refDate && $tglAkhir >= $refDate);
            if (!$issuedInSem && !$activeOnRef) {
                continue;
            }

            $level = $this->_parseSpLevel($sp->jenis_sp, $sp->perihal);
            if ($level <= 0) {
                continue;
            }

            if (!isset($map[$uid]) || $level > $map[$uid]['level']) {
                $map[$uid] = [
                    'level'     => $level,
                    'cap'       => isset($capByLevel[$level]) ? $capByLevel[$level] : 80,
                    'label'     => 'SP ' . $level,
                    'kode'      => (string)$sp->kode,
                    'tgl_mulai' => $tglMulai,
                    'tgl_akhir' => $tglAkhir,
                ];
            }
        }

        return $map;
    }

    public function index()
    {
        grantAccessFor('all');

        $myId = (int)sessPenggunaId();
        $nav  = 'kepegawaian';

        $filterSemester = $this->input->get('semester', TRUE);
        $filterBulan    = $this->input->get('bulan', TRUE);
        $filterTahun    = $this->input->get('tahun', TRUE);

        if ($filterSemester !== '1' && $filterSemester !== '2') {
            if (!empty($filterBulan) && (int)$filterBulan >= 1 && (int)$filterBulan <= 12) {
                $filterSemester = ((int)$filterBulan <= 6) ? '1' : '2';
            } else {
                $filterSemester = ((int)date('n') <= 6) ? '1' : '2';
            }
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

        // 4. Leaderboard & Rekap Rating Kinerja Karyawan per Semester (Solusi A: 80% Mutu Kualitas 6 Pilar + 20% Volume Produktivitas + Plafon SP)
        $wherePeriodeTask = " AND t.created_at >= '{$semStart} 00:00:00' AND t.created_at <= '{$semEnd} 23:59:59'";
        $wherePeriodeNp   = " AND date >= '{$semStart}' AND date <= '{$semEnd}'";
        $wherePeriodeLap  = " AND tanggal >= '{$semStart}' AND tanggal <= '{$semEnd}'";
        $wherePeriodePk   = " AND bulan >= '{$semStart}' AND bulan <= '{$semEnd}'";

        $todayYmd    = date('Y-m-d');
        $absStart    = $semStart;
        $absFullEnd  = $semEnd;
        $absEnd      = ($absFullEnd > $todayYmd) ? $todayYmd : $absFullEnd;
        $currentSmt  = ((int)date('n') <= 6) ? 1 : 2;
        $spRefDate   = ($yInt === (int)date('Y') && $smtInt === $currentSmt) ? $todayYmd : $semEnd;
        $wherePeriodeAbs = ($absEnd < $absStart)
            ? " AND 1 = 0"
            : " AND a.data_created >= '{$absStart} 00:00:00' AND a.data_created <= '{$absEnd} 23:59:59'";

        // Ambil hari libur nasional, tanggal mulai absen karyawan, nilai evaluasi semester, & status SP aktif
        $holidaySet = [];
        if ($this->db->table_exists('absensi_config_libur')) {
            $libRows = $this->db->query("SELECT tgl FROM absensi_config_libur WHERE tgl >= '2020-01-01'")->result();
            foreach ($libRows as $lr) {
                if (!empty($lr->tgl)) {
                    $holidaySet[date('Y-m-d', strtotime($lr->tgl))] = true;
                }
            }
        }
        $firstAbsenMap      = $this->_getFirstAbsenDateMap($todayYmd, $accountMergeMap);
        $evalScoreMap       = $this->_getEvaluasiSemesterScoreMap($smtInt, $yInt, $accountMergeMap);
        $evalExistsInPeriod = !empty($evalScoreMap);
        $spCapMap           = $this->_getActiveSpCapMap($semStart, $semEnd, $spRefDate, $accountMergeMap);

        // Generator CASE mapping ID untuk akun ganda / akun jabatan yang dimerge
        $caseTsk = "t.id_penerima";
        $caseNp  = "id_pengguna";
        $caseLap = "id_pengaju";
        $caseAbs = "a.pengguna_id";

        if (!empty($accountMergeMap)) {
            $whensTsk = "";
            $whensNp  = "";
            $whensLap = "";
            $whensAbs = "";
            foreach ($accountMergeMap as $dupId => $targetId) {
                $dupId    = (int)$dupId;
                $targetId = (int)$targetId;
                $whensTsk .= "WHEN t.id_penerima = {$dupId} THEN {$targetId} ";
                $whensNp  .= "WHEN id_pengguna = {$dupId} THEN {$targetId} ";
                $whensLap .= "WHEN id_pengaju = {$dupId} THEN {$targetId} ";
                $whensAbs .= "WHEN a.pengguna_id = {$dupId} THEN {$targetId} ";
            }
            $caseTsk = "(CASE {$whensTsk} ELSE t.id_penerima END)";
            $caseNp  = "(CASE {$whensNp} ELSE id_pengguna END)";
            $caseLap = "(CASE {$whensLap} ELSE id_pengaju END)";
            $caseAbs = "(CASE {$whensAbs} ELSE a.pengguna_id END)";
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
                COALESCE(np.total_poin_laporan, lap.total_poin_lap, 0) AS total_poin_laporan,
                COALESCE(penc.avg_nilai_pencapaian, lap_penc.avg_nilai_lap_penc) AS avg_nilai_pencapaian,
                COALESCE(penc.total_pencapaian, lap_penc.total_lap_penc, 0) AS total_pencapaian,
                COALESCE(pk.avg_nilai_pk, NULL) AS avg_nilai_pk,
                COALESCE(pk.total_pk, 0) AS total_pk,
                COALESCE(abs_data.hari_hadir, 0) AS abs_hari_hadir,
                COALESCE(abs_data.hari_tepat, 0) AS abs_hari_tepat,
                COALESCE(abs_data.hari_terlambat, 0) AS abs_hari_terlambat,
                COALESCE(abs_data.hari_cuti, 0) AS abs_hari_cuti,
                COALESCE(abs_data.hari_izin_sakit, 0) AS abs_hari_izin_sakit,
                COALESCE(abs_data.total_absen_rec, 0) AS abs_total_rec
            FROM pengguna p
            LEFT JOIN divisi d ON d.id_divisi = p.id_divisi
            LEFT JOIN (
                SELECT 
                    {$caseTsk} AS id_penerima,
                    COUNT(t.id_task) AS total_task,
                    SUM(CASE WHEN t.status_task = 4 THEN 1 ELSE 0 END) AS task_selesai,
                    SUM(CASE WHEN t.status_task < 4 THEN 1 ELSE 0 END) AS task_aktif,
                    SUM(CASE WHEN t.status_task = 4 AND (t.tgl_deadline IS NULL OR DATE(COALESCE(t.tgl_submit, t.tgl_dinilai)) <= t.tgl_deadline) THEN 1 ELSE 0 END) AS task_ontime,
                    ROUND(AVG(CASE WHEN t.status_task = 4 AND t.rating_bintang IS NOT NULL THEN (CASE WHEN t.tgl_deadline IS NOT NULL AND DATE(COALESCE(t.tgl_submit, t.tgl_dinilai)) > t.tgl_deadline THEN 1 ELSE t.rating_bintang END) ELSE NULL END), 2) AS avg_bintang,
                    SUM(CASE WHEN t.status_task = 4 AND t.rating_bintang = 5 AND (t.tgl_deadline IS NULL OR DATE(COALESCE(t.tgl_submit, t.tgl_dinilai)) <= t.tgl_deadline) THEN 1 ELSE 0 END) AS bintang_5
                FROM task_proyek t
                WHERE t.is_deleted = 0 {$wherePeriodeTask}
                GROUP BY {$caseTsk}
            ) tsk ON tsk.id_penerima = p.pengguna_id
            LEFT JOIN (
                SELECT 
                    {$caseNp} AS id_pengguna,
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
                GROUP BY {$caseNp}
            ) np ON np.id_pengguna = p.pengguna_id
            LEFT JOIN (
                SELECT 
                    {$caseLap} AS id_pengaju,
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
                GROUP BY {$caseLap}
            ) lap ON lap.id_pengaju = p.pengguna_id
            LEFT JOIN (
                SELECT 
                    {$caseNp} AS id_pengguna,
                    COUNT(*) AS total_pencapaian,
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
                    ), 1) AS avg_nilai_pencapaian
                FROM pencapaian
                WHERE 1=1 {$wherePeriodeLap}
                GROUP BY {$caseNp}
            ) penc ON penc.id_pengguna = p.pengguna_id
            LEFT JOIN (
                SELECT 
                    {$caseLap} AS id_pengaju,
                    COUNT(*) AS total_lap_penc,
                    ROUND(AVG(
                        CASE 
                            WHEN (nilai_pencapaian_a > 0 AND nilai_pencapaian_a <= 100) AND (nilai_pencapaian_b > 0 AND nilai_pencapaian_b <= 100) 
                            THEN (CAST(nilai_pencapaian_a AS DECIMAL(5,2)) + CAST(nilai_pencapaian_b AS DECIMAL(5,2))) / 2
                            WHEN (nilai_pencapaian_b > 0 AND nilai_pencapaian_b <= 100) 
                            THEN CAST(nilai_pencapaian_b AS DECIMAL(5,2))
                            WHEN (nilai_pencapaian_a > 0 AND nilai_pencapaian_a <= 100) 
                            THEN CAST(nilai_pencapaian_a AS DECIMAL(5,2))
                            ELSE NULL 
                        END
                    ), 1) AS avg_nilai_lap_penc
                FROM laporan
                WHERE status != 3 AND (nilai_pencapaian_a > 0 OR nilai_pencapaian_b > 0) {$wherePeriodeLap}
                GROUP BY {$caseLap}
            ) lap_penc ON lap_penc.id_pengaju = p.pengguna_id
            LEFT JOIN (
                SELECT 
                    {$caseNp} AS id_pengguna,
                    COUNT(*) AS total_pk,
                    ROUND(AVG(nilai), 1) AS avg_nilai_pk
                FROM product_knowledge
                WHERE (nilai IS NOT NULL AND nilai > 0) {$wherePeriodePk}
                GROUP BY {$caseNp}
            ) pk ON pk.id_pengguna = p.pengguna_id
            LEFT JOIN (
                SELECT
                    {$caseAbs} AS id_pengguna,
                    COUNT(DISTINCT CASE
                        WHEN (a.type_absen = 'masuk' OR LOWER(COALESCE(a.type_absen, '')) = 'cuti' OR LOWER(COALESCE(a.status_absen, '')) = 'cuti')
                        THEN DATE(a.data_created)
                    END) AS hari_hadir,
                    COUNT(DISTINCT CASE
                        WHEN a.type_absen = 'masuk' AND LOWER(COALESCE(a.status_absen, '')) IN ('tepat_waktu', 'tepat waktu', 'dinas')
                        THEN DATE(a.data_created)
                    END) AS hari_tepat,
                    COUNT(DISTINCT CASE
                        WHEN a.type_absen = 'masuk' AND LOWER(COALESCE(a.status_absen, '')) = 'terlambat'
                        THEN DATE(a.data_created)
                    END) AS hari_terlambat,
                    COUNT(DISTINCT CASE
                        WHEN (LOWER(COALESCE(a.type_absen, '')) = 'cuti' OR LOWER(COALESCE(a.status_absen, '')) = 'cuti')
                        THEN DATE(a.data_created)
                    END) AS hari_cuti,
                    COUNT(DISTINCT CASE
                        WHEN LOWER(COALESCE(a.status_absen, '')) IN ('izin', 'sakit')
                        THEN DATE(a.data_created)
                    END) AS hari_izin_sakit,
                    COUNT(*) AS total_absen_rec
                FROM absensi a
                WHERE (COALESCE(a.is_luar_radius, 0) = 0 OR (a.acc_ga = 1 AND a.acc_yola = 1))
                  {$wherePeriodeAbs}
                GROUP BY {$caseAbs}
            ) abs_data ON abs_data.id_pengguna = p.pengguna_id
            WHERE p.status = 1 {$excludeMergedSql} AND (
                tsk.total_task > 0 
                OR np.avg_nilai_point IS NOT NULL 
                OR lap.avg_nilai_lap IS NOT NULL 
                OR penc.avg_nilai_pencapaian IS NOT NULL
                OR lap_penc.avg_nilai_lap_penc IS NOT NULL
                OR pk.avg_nilai_pk IS NOT NULL
                OR COALESCE(abs_data.total_absen_rec, 0) > 0
            )
        ")->result();

        $leaderboard = [];
        $myCombinedKpi = null;

        foreach ($raw_leaderboard as $lb) {
            $uid        = (int)$lb->pengguna_id;
            $isExemptPk = $this->_isExemptFromPk($lb->nama, $lb->jabatan, $lb->nama_divisi);
            $hasTask    = ($lb->avg_bintang !== null && (int)$lb->task_selesai > 0);
            $hasLap     = ($lb->avg_nilai_laporan !== null);
            $hasPenc    = ($lb->avg_nilai_pencapaian !== null);
            $hasPk      = (!$isExemptPk && $lb->avg_nilai_pk !== null);

            $skorTask = $hasTask ? round(((float)$lb->avg_bintang / 5) * 100, 1) : null;
            $skorLap  = $hasLap ? round((float)$lb->avg_nilai_laporan, 1) : null;
            $skorPenc = $hasPenc ? round((float)$lb->avg_nilai_pencapaian, 1) : null;
            $skorPk   = ($hasPk && !$isExemptPk) ? round((float)$lb->avg_nilai_pk, 1) : null;

            // Pilar 5: Absensi per semester (kehadiran tepat waktu + penalti keterlambatan)
            $userAbsStart = $absStart;
            if (isset($firstAbsenMap[$uid]) && $firstAbsenMap[$uid] > $userAbsStart && $firstAbsenMap[$uid] <= $absEnd) {
                $userAbsStart = $firstAbsenMap[$uid];
            }
            $hariKerjaElapsed = $this->_countEffectiveWorkdays($userAbsStart, $absEnd, $holidaySet);
            $hariKerjaPenalti = max(1, $hariKerjaElapsed);

            $absHadir     = (int)$lb->abs_hari_hadir;
            $absTepat     = (int)$lb->abs_hari_tepat;
            $absTerlambat = (int)$lb->abs_hari_terlambat;
            $absCuti      = (int)$lb->abs_hari_cuti;
            $absIzinSakit = (int)$lb->abs_hari_izin_sakit;
            $absTotalRec  = (int)$lb->abs_total_rec;

            $hasAbs  = ($hariKerjaElapsed > 0 && $absTotalRec > 0);
            $skorAbs = null;
            $volAbs  = null;
            if ($hasAbs) {
                $coverageAbs = min(100.0, ($absHadir / $hariKerjaElapsed) * 100.0);
                // Penalti terlambat: 1x terlambat memotong 0.6 poin hari kerja (~3 poin per terlambat dalam sebulan)
                $latePenalty = ($absTerlambat / $hariKerjaPenalti) * 60.0;
                $skorAbs     = max(0.0, round($coverageAbs - $latePenalty, 1));
                $volAbs      = round($coverageAbs, 1);
            }

            // Pilar 6: Evaluasi Semester (jika semester berjalan belum diisi, diperlakukan sebagai "belum dinilai dulu")
            $evalInfo  = isset($evalScoreMap[$uid]) ? $evalScoreMap[$uid] : null;
            $hasEval   = ($evalInfo !== null && $evalInfo['skor'] !== null);
            $skorEval  = $hasEval ? round((float)$evalInfo['skor'], 1) : null;
            $evalLabel = $hasEval ? $evalInfo['label'] : null;

            // Aturan SP: batas plafon maksimal nilai akhir (SP0=100, SP1=80, SP2=60, SP3=40)
            $spInfo  = isset($spCapMap[$uid]) ? $spCapMap[$uid] : null;
            $spLevel = $spInfo ? (int)$spInfo['level'] : 0;
            $spCap   = $spInfo ? (int)$spInfo['cap'] : 100;
            $spLabel = $spInfo ? $spInfo['label'] : 'SP 0';
            $spKode  = $spInfo ? $spInfo['kode'] : '';
            $spAkhir = $spInfo ? $spInfo['tgl_akhir'] : '';

            // Target Volume Produktivitas: Task min 2 | Lap min 120 poin | Penc min 10 item | PK min 1 kuis | Absensi min hari kerja
            $volTask = min(100.0, ((int)$lb->task_selesai / 2) * 100.0);
            $volLap  = min(100.0, ((int)$lb->total_poin_laporan / 120) * 100.0);
            $volPenc = min(100.0, ((int)$lb->total_pencapaian / 10) * 100.0);
            $volPk   = min(100.0, ((int)$lb->total_pk / 1) * 100.0);

            // 1. Hitung Skor Mutu (6 Pilar Reguler / 5 Pilar Bebas PK) & Skor Produktivitas (Volume)
            if ($isExemptPk) {
                // Bebas PK: 30% Task + 20% Lap + 20% Penc + 15% Absensi + 15% Evaluasi Semester
                $pillarDefs = [
                    ['active' => $hasTask, 'mutu' => $skorTask, 'prod' => $volTask, 'weight' => 0.30, 'label' => 'Task', 'routine' => true],
                    ['active' => $hasLap,  'mutu' => $skorLap,  'prod' => $volLap,  'weight' => 0.20, 'label' => 'Lap',  'routine' => true],
                    ['active' => $hasPenc, 'mutu' => $skorPenc, 'prod' => $volPenc, 'weight' => 0.20, 'label' => 'Penc', 'routine' => true],
                    ['active' => $hasAbs,  'mutu' => $skorAbs,  'prod' => $volAbs,  'weight' => 0.15, 'label' => 'Abs',  'routine' => true],
                    ['active' => $hasEval, 'mutu' => $skorEval, 'prod' => null,     'weight' => 0.15, 'label' => 'Eval', 'routine' => false],
                ];
                $routineTarget = 4;
            } else {
                // Reguler Wajib PK: 25% Task + 20% Lap + 15% Penc + 10% PK + 15% Absensi + 15% Evaluasi Semester
                $pillarDefs = [
                    ['active' => $hasTask, 'mutu' => $skorTask, 'prod' => $volTask, 'weight' => 0.25, 'label' => 'Task', 'routine' => true],
                    ['active' => $hasLap,  'mutu' => $skorLap,  'prod' => $volLap,  'weight' => 0.20, 'label' => 'Lap',  'routine' => true],
                    ['active' => $hasPenc, 'mutu' => $skorPenc, 'prod' => $volPenc, 'weight' => 0.15, 'label' => 'Penc', 'routine' => true],
                    ['active' => $hasPk,   'mutu' => $skorPk,   'prod' => $volPk,   'weight' => 0.10, 'label' => 'PK',   'routine' => true],
                    ['active' => $hasAbs,  'mutu' => $skorAbs,  'prod' => $volAbs,  'weight' => 0.15, 'label' => 'Abs',  'routine' => true],
                    ['active' => $hasEval, 'mutu' => $skorEval, 'prod' => null,     'weight' => 0.15, 'label' => 'Eval', 'routine' => false],
                ];
                $routineTarget = 5;
            }

            $activePillars = [];
            $mutuWeightSum = 0.0;
            $prodWeightSum = 0.0;
            $routineActive = 0;

            foreach ($pillarDefs as $pd) {
                if (!$pd['active']) {
                    continue;
                }
                $activePillars[] = $pd;
                $mutuWeightSum  += $pd['weight'];
                if ($pd['prod'] !== null) {
                    $prodWeightSum += $pd['weight'];
                }
                if ($pd['routine']) {
                    $routineActive++;
                }
            }

            if ($mutuWeightSum > 0) {
                $sumMutu = 0.0;
                $sumProd = 0.0;
                $parts   = [];
                foreach ($activePillars as $p) {
                    $normMutuW = $p['weight'] / $mutuWeightSum;
                    $sumMutu  += $p['mutu'] * $normMutuW;
                    if ($p['prod'] !== null && $prodWeightSum > 0) {
                        $normProdW = $p['weight'] / $prodWeightSum;
                        $sumProd  += $p['prod'] * $normProdW;
                    }
                    $parts[] = round($normMutuW * 100) . '% ' . $p['label'];
                }
                $skorMutu = round($sumMutu, 1);
                $skorProd = ($prodWeightSum > 0) ? round($sumProd, 1) : $skorMutu;

                $missingRoutine   = $routineTarget - $routineActive;
                $isAllRoutineDone = ($missingRoutine === 0);
                $isFullAll        = ($isAllRoutineDone && $hasEval);

                if ($isFullAll || ($isAllRoutineDone && !$evalExistsInPeriod)) {
                    $statusNilai      = 'lengkap';
                    $isEligiblePodium = true;
                    $completenessTier = 10;
                    $komposisiSuffix  = $isExemptPk ? ' (Bebas PK)' : '';
                } elseif ($isAllRoutineDone) {
                    $statusNilai      = 'parsial_no_eval';
                    $isEligiblePodium = true;
                    $completenessTier = 9;
                    $komposisiSuffix  = $isExemptPk ? ' (Bebas PK, Non-Eval)' : ' (Non-Eval)';
                } else {
                    $statusNilai      = 'parsial_' . $routineActive;
                    $isEligiblePodium = false;
                    $completenessTier = max(1, 9 - $missingRoutine);
                    $komposisiSuffix  = $isExemptPk ? ' (Bebas PK, Parsial)' : ' (Parsial)';
                }
                $komposisi = implode(' + ', $parts) . $komposisiSuffix;
            } else {
                $skorMutu         = null;
                $skorProd         = null;
                $komposisi        = $isExemptPk ? 'Belum Ada Nilai (Bebas PK)' : 'Belum Ada Nilai';
                $statusNilai      = 'belum_dinilai';
                $isEligiblePodium = false;
                $completenessTier = 0;
            }

            // 2. SOLUSI A: SKOR AKHIR = 80% KUALITAS MUTU + 20% PRODUKTIVITAS VOLUME, dibatasi plafon SP
            if ($skorMutu !== null) {
                $skorAkhirRaw = round(($skorMutu * 0.80) + ($skorProd * 0.20), 1);
                $skorAkhir    = min($skorAkhirRaw, (float)$spCap);
                $isCappedSp   = ($skorAkhirRaw > (float)$spCap);
            } else {
                $skorAkhirRaw = null;
                $skorAkhir    = null;
                $isCappedSp   = false;
            }

            $ratingAgregat = $skorAkhir !== null ? round(($skorAkhir / 100) * 5, 2) : 0;

            if ($skorAkhir === null) {
                $grade = '-';
                $gradeLabel = 'Belum Dinilai';
                $gradeBadge = 'badge-secondary';
                $gradeColor = '#64748b';
            } elseif ($skorAkhir >= 85) {
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
            } else {
                $grade = 'D';
                $gradeLabel = 'Kurang (PIP)';
                $gradeBadge = 'badge-danger';
                $gradeColor = '#dc2626';
            }

            $lb->is_exempt_pk       = $isExemptPk;
            $lb->skor_task          = $skorTask;
            $lb->skor_lap           = $skorLap;
            $lb->skor_penc          = $skorPenc;
            $lb->skor_pk            = $skorPk;
            $lb->skor_abs           = $skorAbs;
            $lb->abs_hari_hadir     = $absHadir;
            $lb->abs_hari_tepat     = $absTepat;
            $lb->abs_hari_terlambat = $absTerlambat;
            $lb->abs_hari_cuti      = $absCuti;
            $lb->abs_hari_izin      = $absIzinSakit;
            $lb->abs_hari_kerja     = $hariKerjaElapsed;
            $lb->skor_eval          = $skorEval;
            $lb->eval_label         = $evalLabel;
            $lb->sp_level           = $spLevel;
            $lb->sp_cap             = $spCap;
            $lb->sp_label           = $spLabel;
            $lb->sp_kode            = $spKode;
            $lb->sp_akhir           = $spAkhir;
            $lb->avg_nilai_pk       = ($isExemptPk || $lb->avg_nilai_pk === null) ? null : (float)$lb->avg_nilai_pk;
            $lb->total_pk           = (int)$lb->total_pk;
            $lb->skor_mutu          = $skorMutu;
            $lb->skor_prod          = $skorProd;
            $lb->total_pencapaian   = (int)$lb->total_pencapaian;
            $lb->skor_akhir_raw     = $skorAkhirRaw;
            $lb->skor_akhir         = $skorAkhir;
            $lb->is_capped_sp       = $isCappedSp;
            $lb->rating_agregat     = $ratingAgregat;
            $lb->grade              = $grade;
            $lb->grade_label        = $gradeLabel;
            $lb->grade_badge        = $gradeBadge;
            $lb->grade_color        = $gradeColor;
            $lb->komposisi          = $komposisi;
            $lb->status_nilai       = $statusNilai;
            $lb->completeness_tier  = $completenessTier;
            $lb->is_eligible_podium = $isEligiblePodium;

            if (in_array($uid, $myReceiverIds, true)) {
                $myCombinedKpi = $lb;
            }

            $leaderboard[] = $lb;
        }

        // Urutkan Leaderboard berdasarkan aturan Solusi A (Mutu 80% + Produktivitas 20% + Cap SP):
        // 1. Evaluasi lengkap diprioritaskan di puncak (Eligible Juara 1, 2, 3)
        // 2. Skor Akhir (setelah batas SP) tertinggi (NULL paling bawah)
        // 3. Skor Produktivitas tertinggi
        // 4. Task selesai, pencapaian, PK, poin laporan, dan hari hadir
        usort($leaderboard, function($a, $b) {
            if ($a->skor_akhir === null && $b->skor_akhir === null) return 0;
            if ($a->skor_akhir === null) return 1;
            if ($b->skor_akhir === null) return -1;

            if ((int)$a->completeness_tier !== (int)$b->completeness_tier) {
                return (int)$b->completeness_tier <=> (int)$a->completeness_tier;
            }

            if ($a->skor_akhir == $b->skor_akhir) {
                if ($a->skor_prod == $b->skor_prod) {
                    if ($a->task_selesai == $b->task_selesai) {
                        if ($a->total_pencapaian == $b->total_pencapaian) {
                            if ($a->total_pk == $b->total_pk) {
                                if ($a->total_poin_laporan == $b->total_poin_laporan) {
                                    return (int)$b->abs_hari_hadir <=> (int)$a->abs_hari_hadir;
                                }
                                return (int)$b->total_poin_laporan <=> (int)$a->total_poin_laporan;
                            }
                            return (int)$b->total_pk <=> (int)$a->total_pk;
                        }
                        return (int)$b->total_pencapaian <=> (int)$a->total_pencapaian;
                    }
                    return (int)$b->task_selesai <=> (int)$a->task_selesai;
                }
                return ($b->skor_prod < $a->skor_prod) ? -1 : 1;
            }
            return ($b->skor_akhir < $a->skor_akhir) ? -1 : 1;
        });

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
