<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model untuk Digital QR Asset Passport (Visilab & After-Sales)
 *
 * Mengelola:
 * - Registry paspor alat pelanggan beserta token QR unik
 * - Sinkronisasi masa aktif (Izin BAPETEN, LUK, Kalibrasi - tanpa TLD) & dokumen LHU/Sertifikat
 * - Riwayat service gabungan (Tiket, Preventif Maintenance, & input manual)
 * - Sinkronisasi otomatis Lapor Kerusakan QR <-> Tiket <-> Task Teknisi (task_proyek)
 */
class Md_asset_passport extends CI_Model
{
    protected $table         = 'visilab_asset_passport';
    protected $table_service = 'visilab_asset_passport_service';
    protected $table_log     = 'visilab_asset_passport_scan_log';
    protected static $initialized = false;

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->ensure_tables();
    }

    /**
     * Auto-create tabel baru & tambahkan kolom penghubung nullable pada tabel eksisting secara aman.
     */
    public function ensure_tables()
    {
        if (self::$initialized) {
            return true;
        }

        $db = $this->db;
        if (!$db) {
            return false;
        }
        if (empty($db->conn_id)) {
            $db->initialize();
        }

        $origDebug = $db->db_debug;
        $db->db_debug = FALSE;

        $sqlPassport = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id_passport` INT(11) NOT NULL AUTO_INCREMENT,
            `kode_passport` VARCHAR(50) NOT NULL,
            `qr_token` VARCHAR(64) NOT NULL,
            `id_pelanggan` INT(11) DEFAULT NULL,
            `nama_instansi` VARCHAR(255) NOT NULL,
            `lokasi_ruangan` VARCHAR(150) DEFAULT NULL,
            `id_perizinan` INT(11) DEFAULT NULL,
            `id_pengujian` INT(11) DEFAULT NULL,
            `nama_produk` VARCHAR(255) NOT NULL,
            `merk_tipe` VARCHAR(150) DEFAULT NULL,
            `serial_number` VARCHAR(150) DEFAULT NULL,
            `foto_produk` VARCHAR(255) DEFAULT NULL,
            `tgl_exp_izin` DATE DEFAULT NULL,
            `tgl_exp_luk` DATE DEFAULT NULL,
            `tgl_exp_kalibrasi` DATE DEFAULT NULL,
            `kode_uji_terakhir` VARCHAR(120) DEFAULT NULL,
            `jenis_uji_terakhir` VARCHAR(100) DEFAULT NULL,
            `link_lhu` TEXT DEFAULT NULL,
            `link_sertifikat` TEXT DEFAULT NULL,
            `info_tambahan_json` TEXT DEFAULT NULL,
            `kategori_layanan` TINYINT(1) DEFAULT 1,
            `default_teknisi_id` INT(11) DEFAULT NULL,
            `id_topik_default` INT(11) DEFAULT NULL,
            `catatan_internal` TEXT DEFAULT NULL,
            `is_active` TINYINT(1) DEFAULT 1,
            `status_data` TINYINT(1) DEFAULT 1,
            `created_by` INT(11) DEFAULT NULL,
            `updated_by` INT(11) DEFAULT NULL,
            `created_at` DATETIME DEFAULT NULL,
            `updated_at` DATETIME DEFAULT NULL,
            PRIMARY KEY (`id_passport`),
            UNIQUE KEY `uk_kode_passport` (`kode_passport`),
            UNIQUE KEY `uk_qr_token` (`qr_token`),
            KEY `idx_pelanggan` (`id_pelanggan`),
            KEY `idx_perizinan` (`id_perizinan`),
            KEY `idx_pengujian` (`id_pengujian`),
            KEY `idx_status_data` (`status_data`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        $this->_execCreateTable($sqlPassport);

        $sqlService = "CREATE TABLE IF NOT EXISTS `{$this->table_service}` (
            `id_service` INT(11) NOT NULL AUTO_INCREMENT,
            `id_passport` INT(11) NOT NULL,
            `sumber` VARCHAR(30) DEFAULT 'manual',
            `id_tiket` INT(11) DEFAULT NULL,
            `id_pm` INT(11) DEFAULT NULL,
            `id_task` INT(11) DEFAULT NULL,
            `tanggal_service` DATE NOT NULL,
            `jenis_service` VARCHAR(100) DEFAULT 'Perbaikan / Service',
            `judul` VARCHAR(255) NOT NULL,
            `deskripsi_tindakan` TEXT DEFAULT NULL,
            `nama_teknisi` VARCHAR(150) DEFAULT NULL,
            `nama_pelapor` VARCHAR(150) DEFAULT NULL,
            `no_wa_pelapor` VARCHAR(50) DEFAULT NULL,
            `foto_laporan` VARCHAR(255) DEFAULT NULL,
            `status_service` VARCHAR(50) DEFAULT 'Selesai',
            `status_data` TINYINT(1) DEFAULT 1,
            `created_by` INT(11) DEFAULT NULL,
            `created_at` DATETIME DEFAULT NULL,
            `updated_at` DATETIME DEFAULT NULL,
            PRIMARY KEY (`id_service`),
            KEY `idx_passport_srv` (`id_passport`),
            KEY `idx_tiket_srv` (`id_tiket`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        $this->_execCreateTable($sqlService);

        $sqlLog = "CREATE TABLE IF NOT EXISTS `{$this->table_log}` (
            `id_log` INT(11) NOT NULL AUTO_INCREMENT,
            `id_passport` INT(11) NOT NULL,
            `aksi` VARCHAR(50) NOT NULL,
            `ip_address` VARCHAR(64) DEFAULT NULL,
            `user_agent` VARCHAR(255) DEFAULT NULL,
            `catatan` TEXT DEFAULT NULL,
            `created_at` DATETIME NOT NULL,
            PRIMARY KEY (`id_log`),
            KEY `idx_passport_log` (`id_passport`),
            KEY `idx_aksi_ip` (`aksi`, `ip_address`, `created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        $this->_execCreateTable($sqlLog);

        // Kolom relasi aman pada tabel eksisting
        if ($db->table_exists('tiket')) {
            $this->_addColumnIfMissing('tiket', 'id_passport', 'INT(11) DEFAULT NULL');
        }
        if ($db->table_exists('preventif_maintenance')) {
            $this->_addColumnIfMissing('preventif_maintenance', 'id_passport', 'INT(11) DEFAULT NULL');
        }
        if ($db->table_exists('task_proyek')) {
            $this->_addColumnIfMissing('task_proyek', 'id_tiket', 'INT(11) DEFAULT NULL');
            $this->_addColumnIfMissing('task_proyek', 'id_passport', 'INT(11) DEFAULT NULL');
        }

        $db->db_debug = $origDebug;
        if ($db->table_exists($this->table)) {
            self::$initialized = true;
            return true;
        }
        return false;
    }

    private function _execCreateTable($sql)
    {
        $ok = @$this->db->query($sql);
        if (!$ok) {
            $fallback = str_replace(
                ['DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci', 'DEFAULT CHARSET=utf8mb4'],
                'DEFAULT CHARSET=utf8',
                $sql
            );
            @$this->db->query($fallback);
        }
    }

    private function _addColumnIfMissing($table, $column, $definition)
    {
        $chk = @$this->db->query("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");
        if ($chk && $chk->num_rows() === 0) {
            @$this->db->query("ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}");
        }
    }

    /**
     * Generate token QR unik acak (tidak bisa ditebak dari ID urut).
     */
    public function generate_unique_token()
    {
        for ($i = 0; $i < 5; $i++) {
            if (function_exists('random_bytes')) {
                $token = bin2hex(random_bytes(12)); // 24 char hex
            } else {
                $token = md5(uniqid((string)mt_rand(), true));
            }
            $exists = $this->db->select('id_passport')->from($this->table)->where('qr_token', $token)->limit(1)->get()->row();
            if (!$exists) {
                return $token;
            }
        }
        return sha1(uniqid('vym_', true) . mt_rand());
    }

    /**
     * Generate kode paspor unik: VYM-QR-YYYY-0001
     */
    public function generate_kode_passport()
    {
        $year = date('Y');
        $prefix = "VYM-QR-{$year}-";
        $row = $this->db->select('kode_passport')
            ->from($this->table)
            ->like('kode_passport', $prefix, 'after')
            ->order_by('id_passport', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        $next = 1;
        if ($row && !empty($row->kode_passport)) {
            $parts = explode('-', $row->kode_passport);
            $lastNum = (int)end($parts);
            $next = $lastNum + 1;
        }
        return $prefix . str_pad((string)$next, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Ambil seluruh paspor aktif untuk Admin DataTable.
     */
    public function get_all_admin($filters = [])
    {
        $this->db->select('
            ap.*,
            pl.identitas_pelanggan AS pelanggan_nama_master,
            pl.kota AS pelanggan_kota,
            pl.provinsi AS pelanggan_provinsi,
            pg.nama AS nama_teknisi_default,
            (SELECT COUNT(*) FROM ' . $this->table_log . ' l WHERE l.id_passport = ap.id_passport AND l.aksi = "scan") AS total_scan
        ')
            ->from($this->table . ' ap')
            ->join('pelanggan pl', 'pl.id_pelanggan = ap.id_pelanggan', 'left')
            ->join('pengguna pg', 'pg.pengguna_id = ap.default_teknisi_id', 'left')
            ->where('ap.status_data', 1);

        if (!empty($filters['kategori_layanan'])) {
            $this->db->where('ap.kategori_layanan', (int)$filters['kategori_layanan']);
        }

        if (!empty($filters['search'])) {
            $s = trim((string)$filters['search']);
            $this->db->group_start()
                ->like('ap.kode_passport', $s)
                ->or_like('ap.nama_produk', $s)
                ->or_like('ap.serial_number', $s)
                ->or_like('ap.nama_instansi', $s)
                ->or_like('pl.identitas_pelanggan', $s)
                ->group_end();
        }

        $this->db->order_by('ap.id_passport', 'DESC');
        $rows = $this->db->get()->result();

        foreach ($rows as $row) {
            $this->_merge_live_office_data($row);
            $row->compliance_summary = $this->compute_compliance_summary($row);
        }

        return $rows;
    }

    /**
     * Ringkasan statistik KPI untuk dashboard Admin QR Passport.
     */
    public function get_admin_stats()
    {
        $rows = $this->get_all_admin();
        $stats = [
            'total_passport' => count($rows),
            'patuh'          => 0,
            'segera_habis'   => 0,
            'expired'        => 0,
            'laporan_qr'     => 0
        ];

        foreach ($rows as $r) {
            $st = $r->compliance_summary['overall_code'] ?? 'none';
            if ($st === 'expired') {
                $stats['expired']++;
            } elseif ($st === 'warning') {
                $stats['segera_habis']++;
            } elseif ($st === 'valid') {
                $stats['patuh']++;
            }
        }

        if ($this->db->table_exists($this->table_log)) {
            $stats['laporan_qr'] = (int)$this->db->where('aksi', 'lapor_kerusakan')->count_all_results($this->table_log);
        }

        return $stats;
    }

    /**
     * Ambil data paspor lengkap berdasarkan ID (untuk Admin).
     */
    public function get_by_id($id_passport)
    {
        $row = $this->db->select('
            ap.*,
            pl.identitas_pelanggan AS pelanggan_nama_master,
            pl.alamat AS pelanggan_alamat,
            pl.kota AS pelanggan_kota,
            pl.provinsi AS pelanggan_provinsi,
            pl.kontak AS pelanggan_kontak,
            pg.nama AS nama_teknisi_default,
            pg.no_hp AS no_hp_teknisi_default
        ')
            ->from($this->table . ' ap')
            ->join('pelanggan pl', 'pl.id_pelanggan = ap.id_pelanggan', 'left')
            ->join('pengguna pg', 'pg.pengguna_id = ap.default_teknisi_id', 'left')
            ->where('ap.id_passport', (int)$id_passport)
            ->where('ap.status_data', 1)
            ->get()
            ->row();

        if (!$row) {
            return null;
        }

        $this->_merge_live_office_data($row);
        $row->compliance_summary = $this->compute_compliance_summary($row);
        return $row;
    }

    /**
     * Ambil data paspor publik berdasarkan QR token (tanpa data sensitif/internal & tanpa TLD).
     */
    public function get_by_token($token)
    {
        $token = trim((string)$token);
        if ($token === '') {
            return null;
        }

        $row = $this->db->select('
            ap.id_passport,
            ap.kode_passport,
            ap.qr_token,
            ap.id_pelanggan,
            ap.nama_instansi,
            ap.lokasi_ruangan,
            ap.id_perizinan,
            ap.id_pengujian,
            ap.nama_produk,
            ap.merk_tipe,
            ap.serial_number,
            ap.foto_produk,
            ap.tgl_exp_izin,
            ap.tgl_exp_luk,
            ap.tgl_exp_kalibrasi,
            ap.kode_uji_terakhir,
            ap.jenis_uji_terakhir,
            ap.link_lhu,
            ap.link_sertifikat,
            ap.info_tambahan_json,
            ap.kategori_layanan,
            ap.default_teknisi_id,
            ap.id_topik_default,
            ap.is_active,
            ap.created_at,
            ap.updated_at,
            pl.identitas_pelanggan AS pelanggan_nama_master,
            pl.alamat AS pelanggan_alamat,
            pl.kota AS pelanggan_kota,
            pl.provinsi AS pelanggan_provinsi
        ')
            ->from($this->table . ' ap')
            ->join('pelanggan pl', 'pl.id_pelanggan = ap.id_pelanggan', 'left')
            ->where('ap.qr_token', $token)
            ->where('ap.status_data', 1)
            ->get()
            ->row();

        if (!$row) {
            return null;
        }

        $this->_merge_live_office_data($row);
        $row->compliance_summary = $this->compute_compliance_summary($row);
        return $row;
    }

    /**
     * Gabungkan data live non-TLD dari visilab_perizinan_alat & pengujian_visilab bila tertaut.
     */
    private function _merge_live_office_data($row)
    {
        $origDebug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        if (!empty($row->id_perizinan) && $this->db->table_exists('visilab_perizinan_alat')) {
            // Sengaja TIDAK memilih tgl_evaluasi_tld
            $qIzin = @$this->db->select('id, nama_instansi, pihak_ketiga, jenis_alat, status_jasa, status_perizinan, progres_perizinan, tgl_terbit, tgl_jatuh_tempo, tgl_exp_luk, tgl_exp_kalibrasi')
                ->from('visilab_perizinan_alat')
                ->where('id', (int)$row->id_perizinan)
                ->get();
            $izin = $qIzin ? $qIzin->row() : null;
            if ($izin) {
                if (!empty($izin->tgl_jatuh_tempo)) {
                    $row->tgl_exp_izin = $izin->tgl_jatuh_tempo;
                }
                if (!empty($izin->tgl_exp_luk)) {
                    $row->tgl_exp_luk = $izin->tgl_exp_luk;
                }
                if (!empty($izin->tgl_exp_kalibrasi)) {
                    $row->tgl_exp_kalibrasi = $izin->tgl_exp_kalibrasi;
                }
                $row->live_status_perizinan = $izin->status_perizinan ?? '';
                $row->live_progres_perizinan = $izin->progres_perizinan ?? '';
                $row->live_status_jasa = $izin->status_jasa ?? '';
                $row->live_tgl_terbit_izin = $izin->tgl_terbit ?? null;
            }
        }

        if (!empty($row->id_pengujian) && $this->db->table_exists('pengujian_visilab')) {
            $qUji = @$this->db->select('id, kode, jenis_uji, jenis_alat, nama_alat, serial_number, jadwal, jadwal_end, link_lhu, link_sertifikat, link_instalasi, status, id_pengujian, created_at')
                ->from('pengujian_visilab')
                ->where('id', (int)$row->id_pengujian)
                ->where('status !=', 0)
                ->get();
            $uji = $qUji ? $qUji->row() : null;
            if ($uji) {
                if (!empty($uji->kode)) {
                    $row->kode_uji_terakhir = $uji->kode;
                }
                if (!empty($uji->jenis_uji)) {
                    $row->jenis_uji_terakhir = $uji->jenis_uji;
                }
                if (!empty($uji->link_lhu)) {
                    $row->link_lhu = $uji->link_lhu;
                }
                if (!empty($uji->link_sertifikat)) {
                    $row->link_sertifikat = $uji->link_sertifikat;
                }
                $row->live_uji_status_code = (int)$uji->status;
                $row->live_uji_status_label = $this->_map_pengujian_status((int)$uji->status);
                $row->live_jadwal_uji = $uji->jadwal ?? null;
                $row->live_jadwal_uji_end = $uji->jadwal_end ?? null;
                $row->live_link_instalasi = $uji->link_instalasi ?? null;
            }
        }

        // Ambil informasi tambahan aman non-TLD
        $row->info_tambahan = [];
        if (!empty($row->info_tambahan_json)) {
            $decoded = json_decode($row->info_tambahan_json, true);
            if (is_array($decoded)) {
                $row->info_tambahan = $decoded;
            }
        }

        if (empty($row->nama_instansi) && !empty($row->pelanggan_nama_master)) {
            $row->nama_instansi = $row->pelanggan_nama_master;
        }

        $this->db->db_debug = $origDebug;
        return $row;
    }

    private function _map_pengujian_status($code)
    {
        $map = [
            1  => 'Baru Diajukan',
            2  => 'Disetujui Staff Teknis',
            3  => 'Disetujui Manager Puncak',
            4  => 'Disetujui Finance',
            5  => 'Disetujui Direktur',
            10 => 'Sertifikat & LHU Terbit'
        ];
        return $map[$code] ?? 'Dalam Proses / Ditolak';
    }

    /**
     * Hitung status masing-masing masa aktif (Izin BAPETEN, LUK, Kalibrasi - tanpa TLD).
     */
    public function compute_compliance_summary($row)
    {
        $items = [
            'izin_bapeten' => $this->_evaluate_date_status('Izin BAPETEN', $row->tgl_exp_izin ?? null, 45),
            'luk'          => $this->_evaluate_date_status('LUK (Uji Kesesuaian)', $row->tgl_exp_luk ?? null, 30),
            'kalibrasi'    => $this->_evaluate_date_status('Kalibrasi Alat', $row->tgl_exp_kalibrasi ?? null, 30),
        ];

        $overall = 'none';
        $hasValid = false;
        $hasWarning = false;
        $hasExpired = false;

        foreach ($items as $it) {
            if ($it['code'] === 'expired') $hasExpired = true;
            elseif ($it['code'] === 'warning') $hasWarning = true;
            elseif ($it['code'] === 'valid') $hasValid = true;
        }

        if ($hasExpired) {
            $overall = 'expired';
        } elseif ($hasWarning) {
            $overall = 'warning';
        } elseif ($hasValid) {
            $overall = 'valid';
        }

        return [
            'overall_code' => $overall,
            'items'        => $items
        ];
    }

    private function _evaluate_date_status($label, $dateStr, $warningDays = 30)
    {
        if (empty($dateStr) || $dateStr === '0000-00-00') {
            return [
                'label'       => $label,
                'date'        => null,
                'date_fmt'    => '-',
                'code'        => 'none',
                'status_text' => 'Belum Terdata',
                'sisa_hari'   => null
            ];
        }

        $todayTs = strtotime(date('Y-m-d'));
        $expTs   = strtotime($dateStr);
        $diff    = (int)floor(($expTs - $todayTs) / 86400);
        $fmt     = date('d M Y', $expTs);

        if ($diff < 0) {
            $code = 'expired';
            $txt  = 'Kadaluarsa (' . abs($diff) . ' hari lalu)';
        } elseif ($diff <= $warningDays) {
            $code = 'warning';
            $txt  = $diff === 0 ? 'Jatuh Tempo Hari Ini' : "Segera Habis (H-{$diff} Hari)";
        } else {
            $code = 'valid';
            $txt  = "Aktif ({$diff} hari lagi)";
        }

        return [
            'label'       => $label,
            'date'        => date('Y-m-d', $expTs),
            'date_fmt'    => $fmt,
            'code'        => $code,
            'status_text' => $txt,
            'sisa_hari'   => $diff
        ];
    }

    /**
     * Gabungkan Riwayat Service alat dari Tiket, Preventif Maintenance, dan Service Manual.
     * Hanya mengembalikan field yang aman tampil ke pelanggan (tanpa biaya/catatan internal).
     */
    public function get_service_history($id_passport)
    {
        $id_passport = (int)$id_passport;
        $timeline = [];

        $origDebug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        // 1. Dari Tiket (perbaikan / laporan kerusakan)
        if ($this->db->table_exists('tiket') && $this->db->field_exists('id_passport', 'tiket')) {
            $qTiket = @$this->db->select('
                t.id_tiket,
                t.kode_tiket,
                t.subject,
                t.deskripsi,
                t.des_peker,
                t.catatan_visit,
                t.status_tiket,
                t.waktu_mulai,
                t.waktu_selesai,
                t.file_laporan_teknisi,
                t.created_at,
                pg.nama AS nama_teknisi
            ')
                ->from('tiket t')
                ->join('pengguna pg', 'pg.pengguna_id = t.id_penerima', 'left')
                ->where('t.id_passport', $id_passport)
                ->where('t.status_data', 1)
                ->order_by('t.created_at', 'DESC')
                ->get();
            $tikets = $qTiket ? $qTiket->result() : [];

            if (!empty($tikets)) {
                foreach ($tikets as $t) {
                    $stMap = [
                        1 => ['label' => 'Tiket Masuk', 'badge' => 'warning'],
                        2 => ['label' => 'Sedang Ditangani', 'badge' => 'info'],
                        3 => ['label' => 'Revisi / Tindak Lanjut', 'badge' => 'warning'],
                        4 => ['label' => 'Selesai', 'badge' => 'success'],
                    ];
                    $stInfo = $stMap[(int)$t->status_tiket] ?? ['label' => 'Ditutup', 'badge' => 'secondary'];

                    $tindakan = !empty($t->des_peker) ? $t->des_peker : (!empty($t->catatan_visit) ? $t->catatan_visit : $t->deskripsi);
                    $tgl = !empty($t->created_at) ? date('Y-m-d', strtotime($t->created_at)) : date('Y-m-d');

                    $timeline[] = [
                        'source'         => 'tiket',
                        'ref_id'         => (int)$t->id_tiket,
                        'kode'           => $t->kode_tiket,
                        'tanggal'        => $tgl,
                        'tanggal_fmt'    => date('d M Y', strtotime($tgl)),
                        'jenis'          => 'Service / Perbaikan',
                        'judul'          => $t->subject,
                        'tindakan'       => $tindakan,
                        'teknisi'        => $t->nama_teknisi ?: 'Tim Teknisi VYM',
                        'status_label'   => $stInfo['label'],
                        'status_badge'   => $stInfo['badge'],
                        'lampiran'       => !empty($t->file_laporan_teknisi) ? $t->file_laporan_teknisi : null,
                        'sort_ts'        => strtotime($t->created_at ?: $tgl)
                    ];
                }
            }
        }

        // 2. Dari Preventif Maintenance
        if ($this->db->table_exists('preventif_maintenance') && $this->db->field_exists('id_passport', 'preventif_maintenance')) {
            $qPm = @$this->db->select('
                pm.id_pm,
                pm.kode_pm,
                pm.subject,
                pm.deskripsi,
                pm.catatan_visit,
                pm.tanggal_pm,
                pm.status_pm,
                pm.created_at,
                pg.nama AS nama_teknisi
            ')
                ->from('preventif_maintenance pm')
                ->join('pengguna pg', 'pg.pengguna_id = pm.id_penerima', 'left')
                ->where('pm.id_passport', $id_passport)
                ->where('pm.status_data', 1)
                ->order_by('pm.tanggal_pm', 'DESC')
                ->get();
            $pms = $qPm ? $qPm->result() : [];

            if (!empty($pms)) {
                foreach ($pms as $pm) {
                    $pmMap = [
                        1 => ['label' => 'Terjadwal', 'badge' => 'warning'],
                        2 => ['label' => 'Sedang Dikerjakan', 'badge' => 'info'],
                        3 => ['label' => 'Selesai', 'badge' => 'success'],
                        4 => ['label' => 'Dibatalkan', 'badge' => 'danger'],
                    ];
                    $stInfo = $pmMap[(int)$pm->status_pm] ?? ['label' => 'Terjadwal', 'badge' => 'secondary'];
                    $tgl = !empty($pm->tanggal_pm) && $pm->tanggal_pm !== '0000-00-00'
                        ? $pm->tanggal_pm
                        : (!empty($pm->created_at) ? date('Y-m-d', strtotime($pm->created_at)) : date('Y-m-d'));

                    $timeline[] = [
                        'source'       => 'pm',
                        'ref_id'       => (int)$pm->id_pm,
                        'kode'         => $pm->kode_pm,
                        'tanggal'      => $tgl,
                        'tanggal_fmt'  => date('d M Y', strtotime($tgl)),
                        'jenis'        => 'Preventif Maintenance',
                        'judul'        => $pm->subject,
                        'tindakan'     => !empty($pm->catatan_visit) ? $pm->catatan_visit : $pm->deskripsi,
                        'teknisi'      => $pm->nama_teknisi ?: 'Tim Teknisi VYM',
                        'status_label' => $stInfo['label'],
                        'status_badge' => $stInfo['badge'],
                        'lampiran'     => null,
                        'sort_ts'      => strtotime($tgl)
                    ];
                }
            }
        }

        // 3. Dari Service Manual (visilab_asset_passport_service)
        if ($this->db->table_exists($this->table_service)) {
            $qMan = @$this->db->select('*')
                ->from($this->table_service)
                ->where('id_passport', $id_passport)
                ->where('status_data', 1)
                ->where('sumber', 'manual')
                ->order_by('tanggal_service', 'DESC')
                ->get();
            $manuals = $qMan ? $qMan->result() : [];

            if (!empty($manuals)) {
                foreach ($manuals as $m) {
                    $badge = 'success';
                    if (stripos($m->status_service, 'open') !== false || stripos($m->status_service, 'antre') !== false) {
                        $badge = 'warning';
                    } elseif (stripos($m->status_service, 'progress') !== false) {
                        $badge = 'info';
                    }
                    $timeline[] = [
                        'source'       => 'manual',
                        'ref_id'       => (int)$m->id_service,
                        'kode'         => 'SRV-' . str_pad((string)$m->id_service, 4, '0', STR_PAD_LEFT),
                        'tanggal'      => $m->tanggal_service,
                        'tanggal_fmt'  => date('d M Y', strtotime($m->tanggal_service)),
                        'jenis'        => $m->jenis_service ?: 'Service / Perbaikan',
                        'judul'        => $m->judul,
                        'tindakan'     => $m->deskripsi_tindakan,
                        'teknisi'      => $m->nama_teknisi ?: 'Tim Teknisi VYM',
                        'status_label' => $m->status_service ?: 'Selesai',
                        'status_badge' => $badge,
                        'lampiran'     => $m->foto_laporan ?: null,
                        'sort_ts'      => strtotime($m->tanggal_service)
                    ];
                }
            }
        }

        $this->db->db_debug = $origDebug;

        usort($timeline, function ($a, $b) {
            return $b['sort_ts'] <=> $a['sort_ts'];
        });

        return $timeline;
    }

    /**
     * Save (insert/update) QR Asset Passport.
     */
    public function save_passport($id_passport, array $data)
    {
        $id_passport = (int)$id_passport;
        if ($id_passport > 0) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id_passport', $id_passport)->update($this->table, $data);
            return $id_passport;
        }

        $data['kode_passport'] = $this->generate_kode_passport();
        $data['qr_token']      = $this->generate_unique_token();
        $data['status_data']   = 1;
        $data['created_at']    = date('Y-m-d H:i:s');
        $data['updated_at']    = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return (int)$this->db->insert_id();
    }

    public function soft_delete($id_passport)
    {
        $this->db->where('id_passport', (int)$id_passport)
            ->update($this->table, [
                'status_data' => 0,
                'updated_at'  => date('Y-m-d H:i:s')
            ]);
        return $this->db->affected_rows() > 0;
    }

    public function add_manual_service(array $data)
    {
        $data['sumber']     = 'manual';
        $data['status_data'] = 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table_service, $data);
        return (int)$this->db->insert_id();
    }

    public function delete_manual_service($id_service)
    {
        $this->db->where('id_service', (int)$id_service)
            ->update($this->table_service, ['status_data' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
        return $this->db->affected_rows() > 0;
    }

    public function record_scan_log($id_passport, $aksi, $catatan = null)
    {
        $ip = isset($_SERVER['REMOTE_ADDR']) ? substr((string)$_SERVER['REMOTE_ADDR'], 0, 64) : '';
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? substr((string)$_SERVER['HTTP_USER_AGENT'], 0, 250) : '';
        $this->db->insert($this->table_log, [
            'id_passport' => (int)$id_passport,
            'aksi'        => $aksi,
            'ip_address'  => $ip,
            'user_agent'  => $ua,
            'catatan'     => $catatan,
            'created_at'  => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Anti-spam ringan tanpa OTP: maksimal 3 laporan kerusakan dalam 10 menit dari IP & alat yang sama.
     */
    public function check_report_rate_limit($id_passport, $ip_address)
    {
        $since = date('Y-m-d H:i:s', time() - 600);
        $count = (int)$this->db->from($this->table_log)
            ->where('id_passport', (int)$id_passport)
            ->where('aksi', 'lapor_kerusakan')
            ->where('ip_address', (string)$ip_address)
            ->where('created_at >=', $since)
            ->count_all_results();
        return $count < 3;
    }

    /**
     * Buat Tiket resmi + otomatis buatkan Task Teknisi (jika Teknisi PIC sudah ditentukan).
     */
    public function create_qr_damage_report($passport, array $input, $foto_url = '')
    {
        $now = date('Y-m-d H:i:s');
        $tahun = date('Y');
        $bulanRomawiMap = [
            '01' => 'I', '02' => 'II', '03' => 'III', '04' => 'IV',
            '05' => 'V', '06' => 'VI', '07' => 'VII', '08' => 'VIII',
            '09' => 'IX', '10' => 'X', '11' => 'XI', '12' => 'XII'
        ];
        $bulanRomawi = $bulanRomawiMap[date('m')] ?? 'I';

        // Generate kode_tiket mengikuti format Tiket::add() -> 001/TKN/XI/2026
        $rowCount = $this->db->select('COUNT(*) AS cnt')
            ->from('tiket')
            ->where('YEAR(created_at)', $tahun)
            ->get()
            ->row();
        $nextNum = ($rowCount ? (int)$rowCount->cnt : 0) + 1;
        $numStr  = str_pad((string)$nextNum, 3, '0', STR_PAD_LEFT);
        $kodeTiket = "{$numStr}/TKN/{$bulanRomawi}/{$tahun}";

        $idVisilab = ((int)($passport->kategori_layanan ?? 1) === 2) ? 2 : 1;
        $coordinatorId = ($idVisilab === 2) ? 751 : 755; // Sesuai Tiket::add()
        $hasDefaultTeknisi = !empty($passport->default_teknisi_id) && (int)$passport->default_teknisi_id > 0;
        $penerimaId = $hasDefaultTeknisi ? (int)$passport->default_teknisi_id : $coordinatorId;

        $idTopik = !empty($passport->id_topik_default) ? (int)$passport->id_topik_default : $this->_find_default_topik_id();
        $prioritas = in_array((int)($input['prioritas'] ?? 3), [1, 2, 3, 4], true) ? (int)$input['prioritas'] : 3;

        $subject = '[QR] ' . trim((string)$input['judul_keluhan']) . ' - ' . $passport->nama_produk;
        if (!empty($passport->serial_number)) {
            $subject .= ' (SN: ' . $passport->serial_number . ')';
        }

        $deskripsiFull = "Laporan Kerusakan via Scan QR Asset Passport ({$passport->kode_passport})\n"
            . "Instansi: {$passport->nama_instansi}" . (!empty($passport->lokasi_ruangan) ? " ({$passport->lokasi_ruangan})" : "") . "\n"
            . "Produk: {$passport->nama_produk}\n"
            . "Nomor Seri: " . ($passport->serial_number ?: '-') . "\n"
            . "Pelapor: {$input['nama_pelapor']} (WA: {$input['no_wa']})\n\n"
            . "Keluhan:\n{$input['deskripsi']}";

        $tiketData = [
            'kode_tiket'         => $kodeTiket,
            'subject'            => substr($subject, 0, 250),
            'id_pelanggan'       => !empty($passport->id_pelanggan) ? (int)$passport->id_pelanggan : null,
            'pelanggan'          => $passport->nama_instansi,
            'nama_cp'            => $input['nama_pelapor'],
            'nomer_cp'           => $input['no_wa'],
            'id_pembuat'         => $coordinatorId, // Pembuat sistem atas nama koordinator
            'id_penerima'        => $penerimaId,
            'id_topik'           => $idTopik,
            'prioritas'          => $prioritas,
            'deskripsi'          => $deskripsiFull,
            'file_pendukung'     => $foto_url !== '' ? $foto_url : null,
            'status_tiket'       => 1, // New
            'status_visit'       => 1,
            'status_surat_dinas' => 0,
            'status_pembiayaan'  => 0,
            'waktu_mulai'        => date('Y-m-d'),
            'waktu_selesai'      => date('Y-m-d', strtotime('+2 days')),
            'log_tiket'          => 1,
            'id_visilab'         => $idVisilab,
            'status_data'        => 1,
            'id_passport'        => (int)$passport->id_passport,
            'created_at'         => $now
        ];

        $this->db->insert('tiket', $tiketData);
        $idTiket = (int)$this->db->insert_id();

        if ($idTiket <= 0) {
            return ['success' => false, 'message' => 'Gagal menyimpan tiket ke database.'];
        }

        // Insert tiket_update awal
        $this->db->insert('tiket_update', [
            'id_tiket'   => $idTiket,
            'kode_tiket' => $kodeTiket,
            'id_pembuat' => $coordinatorId,
            'update'     => 'Laporan kerusakan masuk via QR Asset Passport oleh ' . $input['nama_pelapor'],
            'status'     => '1',
            'created_at' => $now
        ]);

        // Sinkronkan ke task_proyek bila alat sudah punya Teknisi PIC Default
        $kodeTask = null;
        $idTask   = null;
        if ($hasDefaultTeknisi) {
            $taskRes = $this->sync_task_from_tiket($idTiket, $penerimaId, [], $coordinatorId);
            if (!empty($taskRes['created_tasks'][0])) {
                $kodeTask = $taskRes['created_tasks'][0]['kode_task'];
                $idTask   = $taskRes['created_tasks'][0]['id_task'];
            }
        }

        $this->record_scan_log(
            $passport->id_passport,
            'lapor_kerusakan',
            json_encode([
                'id_tiket'    => $idTiket,
                'kode_tiket'  => $kodeTiket,
                'id_task'     => $idTask,
                'kode_task'   => $kodeTask,
                'pelapor'     => $input['nama_pelapor'],
                'no_wa'       => $input['no_wa']
            ], JSON_UNESCAPED_UNICODE)
        );

        return [
            'success'             => true,
            'id_tiket'            => $idTiket,
            'kode_tiket'          => $kodeTiket,
            'id_task'             => $idTask,
            'kode_task'           => $kodeTask,
            'penerima_id'         => $penerimaId,
            'coordinator_id'      => $coordinatorId,
            'has_default_teknisi' => $hasDefaultTeknisi,
            'id_visilab'          => $idVisilab
        ];
    }

    private function _find_default_topik_id()
    {
        if (!$this->db->table_exists('topik_tiket')) {
            return 1;
        }
        $row = $this->db->select('id_topik')
            ->from('topik_tiket')
            ->where('status', 1)
            ->order_by('id_topik', 'ASC')
            ->limit(1)
            ->get()
            ->row();
        return $row ? (int)$row->id_topik : 1;
    }

    /**
     * Sinkronisasi otomatis: Buat Task di task_proyek dari sebuah Tiket untuk teknisi utama & pic support.
     * Dipanggil saat QR report (jika ada teknisi default) atau saat Koordinator melakukan disposTeknisi di Tiket.php.
     */
    public function sync_task_from_tiket($id_tiket, $teknisi_utama_id, array $support_ids = [], $pemberi_id = null)
    {
        if (!$this->db->table_exists('task_proyek') || !$this->db->table_exists('tiket')) {
            return ['created_tasks' => []];
        }

        $id_tiket = (int)$id_tiket;
        $tiket = $this->db->get_where('tiket', ['id_tiket' => $id_tiket])->row();
        if (!$tiket) {
            return ['created_tasks' => []];
        }

        $pemberi_id = (int)($pemberi_id ?: ($tiket->id_pembuat ?: 755));
        $assignees = [];
        if ((int)$teknisi_utama_id > 0) {
            $assignees[] = (int)$teknisi_utama_id;
        }
        foreach ($support_ids as $sid) {
            $sid = (int)$sid;
            if ($sid > 0 && !in_array($sid, $assignees, true)) {
                $assignees[] = $sid;
            }
        }

        $prioMap = [1 => 'Low', 2 => 'Medium', 3 => 'High', 4 => 'Urgent'];
        $prioritasLabel = $prioMap[(int)$tiket->prioritas] ?? 'High';
        $tglMulai = !empty($tiket->waktu_mulai) && $tiket->waktu_mulai !== '0000-00-00' ? $tiket->waktu_mulai : date('Y-m-d');
        $tglDeadline = !empty($tiket->waktu_selesai) && $tiket->waktu_selesai !== '0000-00-00' ? $tiket->waktu_selesai : date('Y-m-d', strtotime('+2 days'));

        $created = [];
        foreach ($assignees as $pid) {
            // Cegah duplikasi task untuk tiket & teknisi yang sama
            $existing = $this->db->select('id_task, kode_task')
                ->from('task_proyek')
                ->where('id_tiket', $id_tiket)
                ->where('id_penerima', $pid)
                ->where('is_deleted', 0)
                ->limit(1)
                ->get()
                ->row();
            if ($existing) {
                continue;
            }

            $lastRow = $this->db->select('id_task')->from('task_proyek')->order_by('id_task', 'DESC')->limit(1)->get()->row();
            $nextNum = ($lastRow ? (int)$lastRow->id_task : 0) + 1;
            $kodeTask = sprintf('TSK/%s/%s/%03d', date('Y'), date('m'), $nextNum);

            $judulTask = "[Tiket {$tiket->kode_tiket}] " . $tiket->subject;
            $deskripsiTask = "Task otomatis dari Tiket {$tiket->kode_tiket}\n"
                . "Pelanggan: " . ($tiket->pelanggan ?: '-') . "\n"
                . (!empty($tiket->nama_cp) ? "CP: {$tiket->nama_cp} ({$tiket->nomer_cp})\n" : "")
                . "\nDetail:\n" . $tiket->deskripsi;

            $insertTask = [
                'kode_task'       => $kodeTask,
                'judul'           => substr($judulTask, 0, 250),
                'nama_proyek'     => !empty($tiket->pelanggan) ? substr($tiket->pelanggan, 0, 190) : 'Service Pelanggan',
                'jenis'           => 'Service / Tiket',
                'prioritas'       => $prioritasLabel,
                'deskripsi'       => $deskripsiTask,
                'id_pemberi'      => $pemberi_id,
                'id_penerima'     => $pid,
                'id_mengetahui'   => $pemberi_id,
                'is_mandiri'      => 0,
                'tgl_mulai'       => $tglMulai,
                'tgl_deadline'    => $tglDeadline,
                'lampiran_atasan' => !empty($tiket->file_pendukung) ? $tiket->file_pendukung : null,
                'progres'         => 0,
                'status_task'     => 0,
                'id_tiket'        => $id_tiket,
                'id_passport'     => !empty($tiket->id_passport) ? (int)$tiket->id_passport : null
            ];

            if ($this->db->insert('task_proyek', $insertTask)) {
                $idTask = (int)$this->db->insert_id();
                $this->db->insert('task_proyek_log', [
                    'id_task'     => $idTask,
                    'id_pengguna' => $pemberi_id,
                    'aksi'        => 'Task Otomatis dari Tiket',
                    'catatan'     => "Dibuat otomatis dari Tiket {$tiket->kode_tiket}"
                ]);
                $created[] = ['id_task' => $idTask, 'kode_task' => $kodeTask, 'id_penerima' => $pid];
            }
        }

        return ['created_tasks' => $created];
    }

    /**
     * Sinkronisasi status & laporan dari Tiket -> Task Teknisi (task_proyek).
     */
    public function sync_task_status_from_tiket($id_tiket)
    {
        if (!$this->db->table_exists('task_proyek') || !$this->db->table_exists('tiket')) {
            return false;
        }
        $id_tiket = (int)$id_tiket;
        $tiket = $this->db->get_where('tiket', ['id_tiket' => $id_tiket])->row();
        if (!$tiket) {
            return false;
        }

        $tasks = $this->db->get_where('task_proyek', ['id_tiket' => $id_tiket, 'is_deleted' => 0])->result();
        if (empty($tasks)) {
            return false;
        }

        $stTiket = (int)$tiket->status_tiket;
        $hasReport = !empty($tiket->des_peker) || !empty($tiket->file_laporan_teknisi);

        foreach ($tasks as $t) {
            $upd = [];
            if ($stTiket === 4) {
                // Tiket Selesai
                $upd['status_task'] = 4;
                $upd['progres']     = 100;
                if (empty($t->tgl_submit)) {
                    $upd['tgl_submit'] = date('Y-m-d H:i:s');
                }
                if (empty($t->tgl_dinilai)) {
                    $upd['tgl_dinilai']    = date('Y-m-d H:i:s');
                    $upd['rating_bintang'] = $t->rating_bintang ?: 5;
                    $upd['ulasan_atasan']  = $t->ulasan_atasan ?: 'Diselesaikan melalui modul Tiket Service.';
                }
            } elseif ($stTiket === 3) {
                $upd['status_task'] = 3; // Revisi
            } elseif ($stTiket === 2) {
                if ($hasReport) {
                    $upd['status_task'] = 2; // Menunggu Review
                    $upd['progres']     = 100;
                    $upd['tgl_submit']  = $t->tgl_submit ?: date('Y-m-d H:i:s');
                } else {
                    $upd['status_task'] = 1; // On Progress
                    $upd['progres']     = max((int)$t->progres, 50);
                }
            }

            if (!empty($tiket->des_peker)) {
                $upd['catatan_bawahan'] = $tiket->des_peker;
            }
            if (!empty($tiket->file_laporan_teknisi)) {
                $upd['bukti_selesai'] = $tiket->file_laporan_teknisi;
            }

            if (!empty($upd)) {
                $this->db->where('id_task', (int)$t->id_task)->update('task_proyek', $upd);
            }
        }
        return true;
    }

    /**
     * Sinkronisasi balik dari Task Teknisi -> Tiket (saat teknisi submit selesai / atasan ACC task).
     */
    public function sync_tiket_from_task($id_task)
    {
        if (!$this->db->table_exists('task_proyek') || !$this->db->table_exists('tiket')) {
            return false;
        }
        $task = $this->db->get_where('task_proyek', ['id_task' => (int)$id_task])->row();
        if (!$task || empty($task->id_tiket)) {
            return false;
        }

        $tiket = $this->db->get_where('tiket', ['id_tiket' => (int)$task->id_tiket])->row();
        if (!$tiket) {
            return false;
        }

        $upd = [];
        $stTask = (int)$task->status_task;
        if ($stTask === 4) {
            $upd['status_tiket'] = 4; // Selesai
        } elseif ($stTask === 2 && (int)$tiket->status_tiket < 2) {
            $upd['status_tiket'] = 2; // On Progress / Laporan terkirim
        } elseif ($stTask === 3) {
            $upd['status_tiket'] = 3; // Revisi
        }

        if (!empty($task->catatan_bawahan) && empty($tiket->des_peker)) {
            $upd['des_peker'] = $task->catatan_bawahan;
        }
        if (!empty($task->bukti_selesai) && empty($tiket->file_laporan_teknisi)) {
            $upd['file_laporan_teknisi'] = $task->bukti_selesai;
        }

        if (!empty($upd)) {
            $this->db->where('id_tiket', (int)$tiket->id_tiket)->update('tiket', $upd);
            $this->db->insert('tiket_update', [
                'id_tiket'   => (int)$tiket->id_tiket,
                'kode_tiket' => $tiket->kode_tiket,
                'id_pembuat' => (int)$task->id_penerima,
                'update'     => 'Sinkronisasi otomatis dari Task ' . $task->kode_task . (!empty($task->catatan_bawahan) ? ': ' . $task->catatan_bawahan : ''),
                'status'     => $stTask === 4 ? '3' : '6',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        return true;
    }

    /**
     * Daftar sumber data office untuk fitur "Tarik Data Otomatis" di Admin Modal.
     */
    public function get_dropdown_sources()
    {
        $origDebug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        $pelanggan = $this->db->select('id_pelanggan, identitas_pelanggan, kota, provinsi, kontak')
            ->from('pelanggan')
            ->where('status', 1)
            ->order_by('identitas_pelanggan', 'ASC')
            ->get()
            ->result();

        $teknisi = $this->db->select('pengguna_id, nama, jabatan, no_hp')
            ->from('pengguna')
            ->where('is_active', 1)
            ->where('status', 1)
            ->order_by('nama', 'ASC')
            ->get()
            ->result();

        $perizinan = [];
        if ($this->db->table_exists('visilab_perizinan_alat')) {
            // Tanpa TLD
            $perizinan = @$this->db->select('id, nama_instansi, jenis_alat, tgl_jatuh_tempo, tgl_exp_luk, tgl_exp_kalibrasi')
                ->from('visilab_perizinan_alat')
                ->order_by('id', 'DESC')
                ->get()
                ->result();
        }

        $pengujian = [];
        if ($this->db->table_exists('pengujian_visilab')) {
            $pengujian = @$this->db->select('p.id, p.kode, p.id_pelanggan, p.nama_instansi, p.jenis_uji, p.jenis_alat, p.nama_alat, p.serial_number, p.link_lhu, p.link_sertifikat, p.status, pl.identitas_pelanggan')
                ->from('pengujian_visilab p')
                ->join('pelanggan pl', 'pl.id_pelanggan = p.id_pelanggan', 'left')
                ->where('p.status !=', 0)
                ->order_by('p.id', 'DESC')
                ->limit(300)
                ->get()
                ->result();
        }

        $topik = [];
        if ($this->db->table_exists('topik_tiket')) {
            $topik = @$this->db->select('id_topik, nama')
                ->from('topik_tiket')
                ->where('status', 1)
                ->order_by('nama', 'ASC')
                ->get()
                ->result();
        }

        $this->db->db_debug = $origDebug;

        return [
            'pelanggan' => $pelanggan ?: [],
            'teknisi'   => $teknisi ?: [],
            'perizinan' => $perizinan ?: [],
            'pengujian' => $pengujian ?: [],
            'topik'     => $topik ?: []
        ];
    }

    /**
     * Ambil daftar Tiket & PM untuk ditautkan ke alat dari Admin.
     */
    public function get_linkable_records($id_pelanggan = null)
    {
        $origDebug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        $tikets = [];
        if ($this->db->table_exists('tiket')) {
            $this->db->select('id_tiket, kode_tiket, subject, pelanggan, status_tiket, created_at')
                ->from('tiket')
                ->where('status_data', 1);
            if (!empty($id_pelanggan)) {
                $this->db->where('id_pelanggan', (int)$id_pelanggan);
            }
            $qT = @$this->db->order_by('id_tiket', 'DESC')->limit(100)->get();
            $tikets = $qT ? $qT->result() : [];
        }

        $pms = [];
        if ($this->db->table_exists('preventif_maintenance')) {
            $this->db->select('id_pm, kode_pm, subject, tanggal_pm, status_pm')
                ->from('preventif_maintenance')
                ->where('status_data', 1);
            if (!empty($id_pelanggan)) {
                $this->db->where('id_pelanggan', (int)$id_pelanggan);
            }
            $qP = @$this->db->order_by('id_pm', 'DESC')->limit(100)->get();
            $pms = $qP ? $qP->result() : [];
        }

        $this->db->db_debug = $origDebug;

        return ['tikets' => $tikets, 'pms' => $pms];
    }
}
