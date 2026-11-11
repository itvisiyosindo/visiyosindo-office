<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Shared KPI Leaderboard & Rank calculation model (Solusi A: 80% Mutu 6 Pilar + 20% Volume + Plafon SP).
 * Digunakan oleh Task_proyek dan Pengguna agar kalkulasi peringkat di seluruh modul 100% konsisten.
 */
class Md_kpi_leaderboard extends CI_Model
{
    public function getAccountMergeMap()
    {
        return [
            107 => 106, // Head of Accounting and Tax -> Dirangga Madali
        ];
    }

    /**
     * Resolve semua pengguna_id (utama + alias akun ganda) untuk lookup leaderboard / penugasan.
     */
    public function resolveUserIds($penggunaId)
    {
        $pid = (int)$penggunaId;
        $map = $this->getAccountMergeMap();
        $ids = [$pid];
        if (isset($map[$pid])) {
            $ids[] = (int)$map[$pid];
        }
        foreach ($map as $dupId => $targetId) {
            if ((int)$targetId === $pid) {
                $ids[] = (int)$dupId;
            }
        }
        return array_values(array_unique(array_filter($ids, function ($v) {
            return (int)$v > 0;
        })));
    }

    /**
     * Karyawan yang dikecualikan (Bebas PK) dari kuis Product Knowledge:
     * - HRD / Personalia
     * - GA (General Affairs) / Bagian Umum
     * - Security / Satpam / Keamanan
     * - Pak Afrianto (Direktur / Manajemen)
     */
    public function isExemptFromPk($nama, $jabatan = '', $nama_divisi = '')
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

    /**
     * Hitung jumlah hari kerja efektif (Senin-Jumat, di luar hari libur absensi_config_libur).
     */
    public function countEffectiveWorkdays($startDate, $endDate, array $holidaySet, $onlyMonth = 0)
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
     * Ambil peta tanggal masuk pertama masing-masing karyawan.
     */
    public function getFirstAbsenDateMap($todayYmd, array $accountMergeMap)
    {
        $map = [];
        $rows = $this->db->query("
            SELECT pengguna_id, DATE(MIN(data_created)) AS first_masuk
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
    public function getEvaluasiSemesterScoreMap($filterSemester, $filterTahun, array $accountMergeMap)
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
     * Ambil peta status SP aktif & batas plafon nilai maksimal (SP0=100, SP1=80, SP2=60, SP3=40) per pengguna_id.
     */
    public function getActiveSpCapMap($semStart, $semEnd, $refDate, array $accountMergeMap)
    {
        if (!$this->db->table_exists('surat_peringatan')) {
            return [];
        }

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

    /**
     * Bangun Leaderboard KPI Lengkap untuk periode semester atau bulan tertentu.
     */
    public function buildLeaderboard($smtInt, $yInt, array $targetReceiverIds = [], $bulanInt = null)
    {
        $bulanInt  = ($bulanInt !== null && is_numeric($bulanInt) && (int)$bulanInt >= 1 && (int)$bulanInt <= 12) ? (int)$bulanInt : null;
        $isMonthly = ($bulanInt !== null);
        $smtInt    = $isMonthly ? (($bulanInt <= 6) ? 1 : 2) : (((int)$smtInt === 1) ? 1 : 2);
        $yInt      = ((int)$yInt > 0) ? (int)$yInt : (int)date('Y');

        if ($isMonthly) {
            $mPad     = str_pad((string)$bulanInt, 2, '0', STR_PAD_LEFT);
            $lastDay  = (int)date('t', strtotime("{$yInt}-{$mPad}-01"));
            $semStart = "{$yInt}-{$mPad}-01";
            $semEnd   = "{$yInt}-{$mPad}-" . str_pad((string)$lastDay, 2, '0', STR_PAD_LEFT);
            $namaBulanList = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            ];
            $semLabel = 'Bulan ' . $namaBulanList[$bulanInt] . ' ' . $yInt;
        } else {
            $semStart = ($smtInt === 1) ? "{$yInt}-01-01" : "{$yInt}-07-01";
            $semEnd   = ($smtInt === 1) ? "{$yInt}-06-30" : "{$yInt}-12-31";
            $semLabel = ($smtInt === 1) ? "Semester 1 (Jan–Jun {$yInt})" : "Semester 2 (Jul–Des {$yInt})";
        }

        $accountMergeMap  = $this->getAccountMergeMap();
        $duplicateIds     = array_keys($accountMergeMap);
        $excludeMergedSql = !empty($duplicateIds) ? " AND p.pengguna_id NOT IN (" . implode(',', array_map('intval', $duplicateIds)) . ")" : "";

        $wherePeriodeTask = " AND t.created_at >= '{$semStart} 00:00:00' AND t.created_at <= '{$semEnd} 23:59:59'";
        $wherePeriodeNp   = " AND date >= '{$semStart}' AND date <= '{$semEnd}'";
        $wherePeriodeLap  = " AND tanggal >= '{$semStart}' AND tanggal <= '{$semEnd}'";
        $wherePeriodePk   = " AND bulan >= '{$semStart}' AND bulan <= '{$semEnd}'";

        $todayYmd        = date('Y-m-d');
        $absStart        = $semStart;
        $absFullEnd      = $semEnd;
        $absEnd          = ($absFullEnd > $todayYmd) ? $todayYmd : $absFullEnd;
        $currentSmt      = ((int)date('n') <= 6) ? 1 : 2;
        if ($isMonthly) {
            $spRefDate = ($yInt === (int)date('Y') && $bulanInt === (int)date('n')) ? $todayYmd : $semEnd;
        } else {
            $spRefDate = ($yInt === (int)date('Y') && $smtInt === $currentSmt) ? $todayYmd : $semEnd;
        }
        $wherePeriodeAbs = ($absEnd < $absStart)
            ? " AND 1 = 0"
            : " AND a.data_created >= '{$absStart} 00:00:00' AND a.data_created <= '{$absEnd} 23:59:59'";

        $holidaySet = [];
        if ($this->db->table_exists('absensi_config_libur')) {
            $libRows = $this->db->query("SELECT tgl FROM absensi_config_libur WHERE tgl >= '2020-01-01'")->result();
            foreach ($libRows as $lr) {
                if (!empty($lr->tgl)) {
                    $holidaySet[date('Y-m-d', strtotime($lr->tgl))] = true;
                }
            }
        }
        $firstAbsenMap      = $this->getFirstAbsenDateMap($todayYmd, $accountMergeMap);
        $evalScoreMap       = $this->getEvaluasiSemesterScoreMap($smtInt, $yInt, $accountMergeMap);
        $evalExistsInPeriod = !empty($evalScoreMap);
        $spCapMap           = $this->getActiveSpCapMap($semStart, $semEnd, $spRefDate, $accountMergeMap);

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
                COALESCE(penc.avg_nilai_pencapaian, lap.avg_nilai_lap_penc) AS avg_nilai_pencapaian,
                COALESCE(penc.total_pencapaian, lap.total_lap_penc, 0) AS total_pencapaian,
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
                    ), 1) AS avg_nilai_lap,
                    SUM(CASE WHEN (nilai_pencapaian_a > 0 OR nilai_pencapaian_b > 0) THEN 1 ELSE 0 END) AS total_lap_penc,
                    ROUND(AVG(
                        CASE
                            WHEN (nilai_pencapaian_a > 0 OR nilai_pencapaian_b > 0) THEN
                                CASE
                                    WHEN (nilai_pencapaian_a > 0 AND nilai_pencapaian_a <= 100) AND (nilai_pencapaian_b > 0 AND nilai_pencapaian_b <= 100)
                                    THEN (CAST(nilai_pencapaian_a AS DECIMAL(5,2)) + CAST(nilai_pencapaian_b AS DECIMAL(5,2))) / 2
                                    WHEN (nilai_pencapaian_b > 0 AND nilai_pencapaian_b <= 100)
                                    THEN CAST(nilai_pencapaian_b AS DECIMAL(5,2))
                                    WHEN (nilai_pencapaian_a > 0 AND nilai_pencapaian_a <= 100)
                                    THEN CAST(nilai_pencapaian_a AS DECIMAL(5,2))
                                    ELSE NULL
                                END
                            ELSE NULL
                        END
                    ), 1) AS avg_nilai_lap_penc
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
                WHERE (COALESCE(a.is_luar_radius, 0) = 0 OR a.acc_ga = 1)
                  {$wherePeriodeAbs}
                GROUP BY {$caseAbs}
            ) abs_data ON abs_data.id_pengguna = p.pengguna_id
            WHERE p.status = 1 {$excludeMergedSql} AND (
                tsk.total_task > 0 
                OR np.avg_nilai_point IS NOT NULL 
                OR lap.avg_nilai_lap IS NOT NULL 
                OR penc.avg_nilai_pencapaian IS NOT NULL
                OR lap.avg_nilai_lap_penc IS NOT NULL
                OR pk.avg_nilai_pk IS NOT NULL
                OR COALESCE(abs_data.total_absen_rec, 0) > 0
            )
        ")->result();

        $leaderboard = [];
        $myCombinedKpi = null;

        foreach ($raw_leaderboard as $lb) {
            $uid        = (int)$lb->pengguna_id;
            $isExemptPk = $this->isExemptFromPk($lb->nama, $lb->jabatan, $lb->nama_divisi);
            $hasTask    = ($lb->avg_bintang !== null && (int)$lb->task_selesai > 0);
            $hasLap     = ($lb->avg_nilai_laporan !== null);
            $hasPenc    = ($lb->avg_nilai_pencapaian !== null);
            $hasPk      = (!$isExemptPk && $lb->avg_nilai_pk !== null);

            $skorTask = $hasTask ? round(((float)$lb->avg_bintang / 5) * 100, 1) : null;
            $skorLap  = $hasLap ? round((float)$lb->avg_nilai_laporan, 1) : null;
            $skorPenc = $hasPenc ? round((float)$lb->avg_nilai_pencapaian, 1) : null;
            $skorPk   = ($hasPk && !$isExemptPk) ? round((float)$lb->avg_nilai_pk, 1) : null;

            $userAbsStart = $absStart;
            if (isset($firstAbsenMap[$uid]) && $firstAbsenMap[$uid] > $userAbsStart && $firstAbsenMap[$uid] <= $absEnd) {
                $userAbsStart = $firstAbsenMap[$uid];
            }
            $hariKerjaElapsed = $this->countEffectiveWorkdays($userAbsStart, $absEnd, $holidaySet);
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
                $latePenalty = ($absTerlambat / $hariKerjaPenalti) * 60.0;
                $skorAbs     = max(0.0, round($coverageAbs - $latePenalty, 1));
                $volAbs      = round($coverageAbs, 1);
            }

            $evalInfo  = isset($evalScoreMap[$uid]) ? $evalScoreMap[$uid] : null;
            $hasEval   = ($evalInfo !== null && $evalInfo['skor'] !== null);
            $skorEval  = $hasEval ? round((float)$evalInfo['skor'], 1) : null;
            $evalLabel = $hasEval ? $evalInfo['label'] : null;

            $spInfo  = isset($spCapMap[$uid]) ? $spCapMap[$uid] : null;
            $spLevel = $spInfo ? (int)$spInfo['level'] : 0;
            $spCap   = $spInfo ? (int)$spInfo['cap'] : 100;
            $spLabel = $spInfo ? $spInfo['label'] : 'SP 0';
            $spKode  = $spInfo ? $spInfo['kode'] : '';
            $spAkhir = $spInfo ? $spInfo['tgl_akhir'] : '';

            $targetVolTask = $isMonthly ? 1 : 2;
            $targetVolLap  = $isMonthly ? 20 : 120;
            $targetVolPenc = $isMonthly ? 2 : 10;
            $targetVolPk   = 1;

            $volTask = min(100.0, ((int)$lb->task_selesai / $targetVolTask) * 100.0);
            $volLap  = min(100.0, ((int)$lb->total_poin_laporan / $targetVolLap) * 100.0);
            $volPenc = min(100.0, ((int)$lb->total_pencapaian / $targetVolPenc) * 100.0);
            $volPk   = min(100.0, ((int)$lb->total_pk / $targetVolPk) * 100.0);

            if ($isExemptPk) {
                $pillarDefs = [
                    ['active' => $hasTask, 'mutu' => $skorTask, 'prod' => $volTask, 'weight' => 0.30, 'label' => 'Task', 'routine' => true],
                    ['active' => $hasLap,  'mutu' => $skorLap,  'prod' => $volLap,  'weight' => 0.20, 'label' => 'Lap',  'routine' => true],
                    ['active' => $hasPenc, 'mutu' => $skorPenc, 'prod' => $volPenc, 'weight' => 0.20, 'label' => 'Penc', 'routine' => true],
                    ['active' => $hasAbs,  'mutu' => $skorAbs,  'prod' => $volAbs,  'weight' => 0.15, 'label' => 'Abs',  'routine' => true],
                    ['active' => $hasEval, 'mutu' => $skorEval, 'prod' => null,     'weight' => 0.15, 'label' => 'Eval', 'routine' => false],
                ];
                $routineTarget = 4;
            } else {
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

            if (!empty($targetReceiverIds) && in_array($uid, $targetReceiverIds, true)) {
                $myCombinedKpi = $lb;
            }

            $leaderboard[] = $lb;
        }

        usort($leaderboard, function ($a, $b) {
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

        return [
            'leaderboard' => $leaderboard,
            'my_combined' => $myCombinedKpi,
            'sem_start'   => $semStart,
            'sem_end'     => $semEnd,
            'sem_label'   => $semLabel,
            'semester'    => $smtInt,
            'bulan'       => $bulanInt,
            'is_monthly'  => $isMonthly,
            'tahun'       => $yInt,
        ];
    }

    /**
     * Resolve semester & tahun default apabila belum diberikan.
     */
    private function _resolveDefaultPeriod($smtInt = null, $yInt = null)
    {
        if ($smtInt === null) {
            $smtInt = ((int)date('n') <= 6) ? 1 : 2;
        }
        if ($yInt === null) {
            $yInt = (int)date('Y');
        }
        return [((int)$smtInt === 1) ? 1 : 2, (int)$yInt];
    }

    /**
     * Cari peringkat, baris user, serta selisih poin ke peringkat di atasnya pada daftar terperingkat.
     */
    private function _findUserPositionInScoredList(array $scoredList, array $targetIds)
    {
        $rank       = 0;
        $userRow    = null;
        $gapToAbove = null;
        $aboveName  = '';

        foreach ($scoredList as $idx => $item) {
            if (in_array((int)$item->pengguna_id, $targetIds, true)) {
                $rank    = $idx + 1;
                $userRow = $item;
                if ($idx > 0) {
                    $prev       = $scoredList[$idx - 1];
                    $gapToAbove = max(0.0, (float)$prev->skor_akhir - (float)$item->skor_akhir);
                    $aboveName  = (string)($prev->nama ?? '');
                }
                break;
            }
        }

        return [
            'rank'         => $rank,
            'row'          => $userRow,
            'gap_to_above' => $gapToAbove,
            'above_name'   => $aboveName,
        ];
    }

    /**
     * Ekstrak daftar peserta terperingkat + posisi karyawan target dari bundle leaderboard.
     */
    private function _extractSummaryFromBundle(array $bundle, array $targetIds)
    {
        $lbList     = $bundle['leaderboard'];
        $scoredList = [];
        foreach ($lbList as $item) {
            if ($item->skor_akhir !== null) {
                $scoredList[] = $item;
            }
        }

        $pos     = $this->_findUserPositionInScoredList($scoredList, $targetIds);
        $userRow = ($pos['row'] !== null) ? $pos['row'] : $bundle['my_combined'];

        return [
            'scored_list'  => $scoredList,
            'rank'         => $pos['rank'],
            'total_ranked' => count($scoredList),
            'total_all'    => count($lbList),
            'row'          => $userRow,
            'gap_to_above' => $pos['gap_to_above'],
            'above_name'   => $pos['above_name'],
            'sem_label'    => $bundle['sem_label'],
            'semester'     => $bundle['semester'],
            'tahun'        => $bundle['tahun'],
        ];
    }

    /**
     * Resolve target IDs, periode default, lalu ekstrak ringkasan bundle untuk satu karyawan.
     */
    private function _buildSummaryForUser($penggunaId, $smtInt = null, $yInt = null)
    {
        list($smtInt, $yInt) = $this->_resolveDefaultPeriod($smtInt, $yInt);

        $targetIds = $this->resolveUserIds((int)$penggunaId);
        $bundle    = $this->buildLeaderboard($smtInt, $yInt, $targetIds);
        $summary   = $this->_extractSummaryFromBundle($bundle, $targetIds);
        $summary['target_ids'] = $targetIds;
        return $summary;
    }

    /**
     * Path file cache ringan (per semester/tahun) untuk Podium Dashboard Home.
     */
    private function _getDashboardCacheFilePath($smtInt, $yInt)
    {
        $cacheDir = (defined('APPPATH') && is_dir(APPPATH . 'cache') && is_writable(APPPATH . 'cache'))
            ? rtrim(APPPATH . 'cache', '/\\')
            : rtrim(sys_get_temp_dir(), '/\\');
        return $cacheDir . DIRECTORY_SEPARATOR . 'kpi_dash_podium_v1_' . (int)$yInt . '_s' . (int)$smtInt . '.json';
    }

    /**
     * Baca cache JSON Podium Dashboard jika masih valid (tanggal hari ini & belum melewati TTL).
     */
    private function _readDashboardPodiumCache($smtInt, $yInt, $ttlSec = 600)
    {
        $file = $this->_getDashboardCacheFilePath($smtInt, $yInt);
        if (!is_file($file)) {
            return null;
        }
        $raw = @file_get_contents($file);
        if ($raw === false || $raw === '') {
            return null;
        }
        $data = @json_decode($raw, true);
        if (!is_array($data) || empty($data['generated_date']) || empty($data['generated_at'])) {
            return null;
        }
        if ($data['generated_date'] !== date('Y-m-d')) {
            return null;
        }
        if ((time() - (int)$data['generated_at']) > (int)$ttlSec) {
            return null;
        }
        if (!isset($data['items']) || !is_array($data['items'])) {
            return null;
        }
        return $data;
    }

    /**
     * Simpan daftar ringkas terperingkat ke file cache Podium Dashboard secara atomik.
     */
    private function _writeDashboardPodiumCache($smtInt, $yInt, array $summary)
    {
        $slimItems = [];
        foreach ($summary['scored_list'] as $item) {
            $slimItems[] = [
                'pengguna_id'    => (int)$item->pengguna_id,
                'nama'           => (string)($item->nama ?? ''),
                'jabatan'        => (string)($item->jabatan ?? ''),
                'skor_akhir'     => (float)$item->skor_akhir,
                'grade'          => (string)($item->grade ?? ''),
                'grade_badge'    => (string)($item->grade_badge ?? 'badge-secondary'),
                'grade_color'    => (string)($item->grade_color ?? '#0f172a'),
                'rating_agregat' => (float)$item->rating_agregat,
                'skor_mutu'      => (float)$item->skor_mutu,
                'skor_prod'      => (float)$item->skor_prod,
                'sp_level'       => (int)($item->sp_level ?? 0),
                'sp_label'       => (string)($item->sp_label ?? 'SP 0'),
                'sp_cap'         => (int)($item->sp_cap ?? 100),
            ];
        }

        $payload = [
            'generated_date' => date('Y-m-d'),
            'generated_at'   => time(),
            'sem_label'      => (string)$summary['sem_label'],
            'semester'       => (int)$summary['semester'],
            'tahun'          => (int)$summary['tahun'],
            'items'          => $slimItems,
        ];

        $encoded = json_encode($payload);
        if ($encoded === false) {
            return;
        }

        $file = $this->_getDashboardCacheFilePath($smtInt, $yInt);
        $tmp  = $file . '.tmp.' . getmypid() . '.' . mt_rand(1000, 9999);
        if (@file_put_contents($tmp, $encoded, LOCK_EX) !== false) {
            if (!@rename($tmp, $file)) {
                @unlink($tmp);
                @file_put_contents($file, $encoded, LOCK_EX);
            }
        }
    }

    /**
     * Susun output Podium Top N + posisi user login dari daftar item terperingkat (objek atau array).
     */
    private function _buildPodiumResponseFromItems(array $items, $currentPenggunaId, $limit, $semLabel, $smtInt, $yInt)
    {
        $scoredObjects = [];
        foreach ($items as $item) {
            $scoredObjects[] = is_object($item) ? $item : (object)$item;
        }

        $targetIds = $this->resolveUserIds((int)$currentPenggunaId);
        $pos       = $this->_findUserPositionInScoredList($scoredObjects, $targetIds);

        return [
            'top_leaders'  => array_slice($scoredObjects, 0, max(1, (int)$limit)),
            'my_ids'       => $targetIds,
            'rank'         => $pos['rank'],
            'total_ranked' => count($scoredObjects),
            'row'          => $pos['row'],
            'gap_to_above' => $pos['gap_to_above'],
            'above_name'   => $pos['above_name'],
            'sem_label'    => (string)$semLabel,
            'semester'     => (int)$smtInt,
            'tahun'        => (int)$yInt,
        ];
    }

    /**
     * Ambil ringkasan peringkat Leaderboard KPI untuk seorang karyawan tertentu.
     */
    public function getUserLeaderboardSummary($targetPenggunaId, $smtInt = null, $yInt = null)
    {
        $summary = $this->_buildSummaryForUser($targetPenggunaId, $smtInt, $yInt);
        unset($summary['scored_list'], $summary['target_ids']);
        return $summary;
    }

    /**
     * Ambil Podium Dashboard HANYA dari cache file jika tersedia (<1ms, nol query database).
     * Mengembalikan null apabila cache kosong/kedaluwarsa agar halaman Home tidak terblokir query berat.
     */
    public function getCachedDashboardTopPodiumIfWarm($currentPenggunaId, $limit = 3, $smtInt = null, $yInt = null, $ttlSec = 300)
    {
        list($smtInt, $yInt) = $this->_resolveDefaultPeriod($smtInt, $yInt);
        $cached = $this->_readDashboardPodiumCache($smtInt, $yInt, $ttlSec);
        if ($cached === null) {
            return null;
        }
        return $this->_buildPodiumResponseFromItems(
            $cached['items'],
            $currentPenggunaId,
            $limit,
            $cached['sem_label'],
            $cached['semester'],
            $cached['tahun']
        );
    }

    /**
     * Ambil data Podium Top N (default Top 1, 2, 3) + ringkasan peringkat user login untuk Dashboard Home.
     * Menggunakan shared file cache + file lock agar kalkulasi berat cukup berjalan 1x per TTL.
     */
    public function getDashboardTopPodium($currentPenggunaId, $limit = 3, $smtInt = null, $yInt = null, $useCache = true, $ttlSec = 300)
    {
        list($smtInt, $yInt) = $this->_resolveDefaultPeriod($smtInt, $yInt);
        $cached = $useCache ? $this->_readDashboardPodiumCache($smtInt, $yInt, $ttlSec) : null;

        if ($cached === null && $useCache) {
            $lockFp = @fopen($this->_getDashboardCacheFilePath($smtInt, $yInt) . '.lock', 'c');
            if ($lockFp) {
                @flock($lockFp, LOCK_EX);
                $cached = $this->_readDashboardPodiumCache($smtInt, $yInt, $ttlSec);
            }
            if ($cached === null) {
                $summary = $this->_buildSummaryForUser($currentPenggunaId, $smtInt, $yInt);
                $this->_writeDashboardPodiumCache($smtInt, $yInt, $summary);
                $cached = [
                    'items'     => $summary['scored_list'],
                    'sem_label' => $summary['sem_label'],
                    'semester'  => $summary['semester'],
                    'tahun'     => $summary['tahun'],
                ];
            }
            if ($lockFp) {
                @flock($lockFp, LOCK_UN);
                @fclose($lockFp);
            }
        } elseif ($cached === null) {
            $summary = $this->_buildSummaryForUser($currentPenggunaId, $smtInt, $yInt);
            $cached = [
                'items'     => $summary['scored_list'],
                'sem_label' => $summary['sem_label'],
                'semester'  => $summary['semester'],
                'tahun'     => $summary['tahun'],
            ];
        }

        return $this->_buildPodiumResponseFromItems(
            $cached['items'],
            $currentPenggunaId,
            $limit,
            $cached['sem_label'],
            $cached['semester'],
            $cached['tahun']
        );
    }
}
