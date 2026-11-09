<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model Md_audit_system
 * Mengelola data:
 * 1. Sasaran Mutu (terbagi sesuai divisi & tahun, 2026 otomatis terisi dari Excel, 2027+ tersinkronisasi penuh langsung di sistem Office tanpa perlu mengisi Excel manual lagi)
 * 2. Audit Kinerja (jobdesk, detail proyek, implementasi nyata kinerja, link dokumen pekerjaan)
 * 3. Audit Keuangan (PBOK yang diajukan beserta detail barang & invoice/lampirannya)
 */
class Md_audit_system extends CI_Model
{
    private $masterFilePath;
    private $overridesFilePath;
    private $kinerjaFilePath;
    private $keuanganFilePath;

    private static $masterCache = null;
    private static $overridesCache = [];
    private static $kinerjaCache = [];
    private static $tablesInitialized = false;

    public function __construct()
    {
        parent::__construct();
        $this->masterFilePath    = APPPATH . 'data/sasaran_mutu_master_2026.json';
        $this->overridesFilePath = APPPATH . 'data/sasaran_mutu_overrides.json';
        $this->kinerjaFilePath   = APPPATH . 'data/audit_kinerja_data.json';
        $this->keuanganFilePath  = APPPATH . 'data/audit_keuangan_data.json';

        $this->ensureDirectories();
        $this->initTables();
    }

    private function ensureDirectories()
    {
        $dir = APPPATH . 'data';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
    }

    /**
     * Cek apakah suatu tabel ada di database secara aman tanpa crash
     */
    public function hasTable($tableName)
    {
        static $cache = [];
        if (isset($cache[$tableName])) {
            return $cache[$tableName];
        }
        if (!$this->db->conn_id) {
            return false;
        }
        $orig = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        $q = @$this->db->query("SHOW TABLES LIKE '{$tableName}'");
        $res = ($q && $q->num_rows() > 0);
        $this->db->db_debug = $orig;
        $cache[$tableName] = $res;
        return $res;
    }

    /**
     * Inisialisasi tabel pendukung jika belum ada (dieksekusi 1x dengan fast check)
     */
    public function initTables()
    {
        if (self::$tablesInitialized || !$this->db->conn_id) {
            return;
        }
        self::$tablesInitialized = true;

        // Jika tabel sudah ada di DB, tidak perlu jalankan CREATE TABLE lagi (menghemat metadata locks)
        if ($this->hasTable('sasaran_mutu_records') && $this->hasTable('audit_kinerja_records') && $this->hasTable('audit_keuangan_records')) {
            return;
        }

        $orig = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        if (!$this->hasTable('sasaran_mutu_records')) {
            @$this->db->query("CREATE TABLE IF NOT EXISTS sasaran_mutu_records (
                id INT AUTO_INCREMENT PRIMARY KEY,
                year INT NOT NULL,
                item_id VARCHAR(100) NOT NULL,
                divisi VARCHAR(100) NOT NULL,
                posisi VARCHAR(150) NOT NULL,
                jobdesk TEXT,
                detail_proyek TEXT,
                implementasi TEXT,
                sasaran_mutu TEXT,
                metode_pengukuran TEXT,
                indikator_keberhasilan TEXT,
                keterangan TEXT,
                updated_by INT,
                updated_by_name VARCHAR(150),
                updated_at DATETIME,
                UNIQUE KEY uq_year_item (year, item_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        if (!$this->hasTable('audit_kinerja_records')) {
            @$this->db->query("CREATE TABLE IF NOT EXISTS audit_kinerja_records (
                id INT AUTO_INCREMENT PRIMARY KEY,
                year INT NOT NULL,
                item_id VARCHAR(100) NOT NULL,
                link_dokumen TEXT,
                status_audit VARCHAR(50) DEFAULT 'Belum Diaudit',
                catatan_audit TEXT,
                audited_by INT,
                audited_by_name VARCHAR(150),
                updated_at DATETIME,
                UNIQUE KEY uq_kinerja (year, item_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        if (!$this->hasTable('audit_keuangan_records')) {
            @$this->db->query("CREATE TABLE IF NOT EXISTS audit_keuangan_records (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_pbok INT NOT NULL,
                status_audit VARCHAR(50) DEFAULT 'Belum Diverifikasi',
                catatan_audit TEXT,
                kelengkapan_invoice VARCHAR(50) DEFAULT 'Lengkap',
                audited_by INT,
                audited_by_name VARCHAR(150),
                updated_at DATETIME,
                UNIQUE KEY uq_pbok (id_pbok)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        $this->db->db_debug = $orig;
    }

    /**
     * Ambil data master template 2026 dari JSON (dichache di RAM agar tidak re-read disk)
     */
    public function getMasterTemplate()
    {
        if (self::$masterCache !== null) {
            return self::$masterCache;
        }

        if (file_exists($this->masterFilePath)) {
            $json = file_get_contents($this->masterFilePath);
            // Hapus UTF-8 BOM jika ada agar json_decode tidak return NULL
            if (substr($json, 0, 3) === "\xEF\xBB\xBF") {
                $json = substr($json, 3);
            }
            $json = trim($json);
            $data = json_decode($json, true);
            if (is_array($data)) {
                self::$masterCache = $data;
                return self::$masterCache;
            }
        }
        return [];
    }

    /**
     * Ambil list divisi dan posisinya
     */
    public function getDivisiAndPositions()
    {
        $master = $this->getMasterTemplate();
        $tree = [];
        foreach ($master as $row) {
            $div = $row['divisi'];
            $pos = $row['posisi'];
            if (!isset($tree[$div])) {
                $tree[$div] = [];
            }
            if ($pos && !in_array($pos, $tree[$div])) {
                $tree[$div][] = $pos;
            }
        }
        return $tree;
    }

    /**
     * Mengambil data Sasaran Mutu per tahun
     * Rule:
     * - Tahun 2026: Nilai diambil lengkap sesuai data Excel untuk keperluan audit HR.
     * - Tahun 2027 dan selanjutnya: Seluruh pengisian langsung tersinkron di sistem office tanpa isi manual di Excel.
     *   Template dasar jobdesk & detail proyek tersedia, realisasi, sasaran, metode, indikator, keterangan,
     *   serta link dokumen diinput langsung oleh masing-masing karyawan dan head di sistem office.
     * - Tersinkronisasi secara langsung dengan Laporan Mingguan & Task yang Diberikan.
     * - Merge dengan catatan/inputan yang tersimpan dari DB / File JSON.
     */
    public function getSasaranMutuData($year = 2026, $divisi = null, $posisi = null, $search = null, $includeSyncCounts = false)
    {
        $year = (int)$year;
        if ($year < 2020) $year = 2026;

        $master = $this->getMasterTemplate();
        $overrides = $this->loadSasaranMutuOverrides($year);
        $kinerjaRecords = $this->loadAuditKinerjaRecords($year);

        // Pre-index Task Proyek berdasarkan id_sasaran_mutu untuk lookup O(1) kilat tanpa nested loops
        $tasksBySmId = [];
        if ($this->db->conn_id && $this->hasTable('task_proyek')) {
            $orig_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;

            $qTask = @$this->db->select('id_task, id_sasaran_mutu')
                ->where('is_deleted', 0)
                ->where('id_sasaran_mutu IS NOT NULL', null, false)
                ->where('id_sasaran_mutu !=', '')
                ->where("(YEAR(created_at) = {$year} OR (tgl_submit IS NOT NULL AND YEAR(tgl_submit) = {$year}))")
                ->get('task_proyek');
            if ($qTask && $qTask->num_rows() > 0) {
                foreach ($qTask->result_array() as $tRow) {
                    $smId = $tRow['id_sasaran_mutu'];
                    if (!isset($tasksBySmId[$smId])) {
                        $tasksBySmId[$smId] = 0;
                    }
                    $tasksBySmId[$smId]++;
                }
            }

            $this->db->db_debug = $orig_debug;
        }

        $results = [];
        $totalItems = 0;
        $filledCount = 0;
        $masterIds = [];

        foreach ($master as $item) {
            $itemId = $item['id'];
            $masterIds[] = $itemId;
            $itemDiv = $item['divisi'];
            $itemPos = $item['posisi'];

            // Filter divisi jika diset
            if ($divisi && $divisi !== 'all' && strcasecmp($itemDiv, $divisi) !== 0 && strcasecmp($item['divisi_key'], $divisi) !== 0) {
                continue;
            }

            // Filter posisi jika diset
            if ($posisi && $posisi !== 'all' && strcasecmp($itemPos, $posisi) !== 0) {
                continue;
            }

            // Hitung korelasi Task yang cocok via index O(1) (instant tanpa delay)
            $taskCount = isset($tasksBySmId[$itemId]) ? $tasksBySmId[$itemId] : 0;
            $lapCount = 0;

            // Tentukan nilai dasar berdasarkan tahun
            if ($year === 2026) {
                $row = [
                    'id'                     => $itemId,
                    'year'                   => $year,
                    'divisi'                 => $item['divisi'],
                    'divisi_key'             => $item['divisi_key'],
                    'posisi'                 => $item['posisi'],
                    'no'                     => $item['no'],
                    'jobdesk'                => $item['jobdesk'],
                    'detail_proyek'          => $item['detail_proyek'],
                    'implementasi'           => $item['implementasi'],
                    'sasaran_mutu'           => $item['sasaran_mutu'],
                    'metode_pengukuran'      => $item['metode_pengukuran'],
                    'indikator_keberhasilan' => $item['indikator_keberhasilan'],
                    'keterangan'             => $item['keterangan'],
                    'link_dokumen'           => '',
                    'laporan_count'          => $lapCount,
                    'task_count'             => $taskCount,
                    'has_sync_data'          => ($lapCount > 0 || $taskCount > 0),
                    'is_custom'              => false
                ];
            } else {
                // Tahun 2027 dan selanjutnya: dikosongkan realisasi & sasaran untuk diinput di web
                $row = [
                    'id'                     => $itemId,
                    'year'                   => $year,
                    'divisi'                 => $item['divisi'],
                    'divisi_key'             => $item['divisi_key'],
                    'posisi'                 => $item['posisi'],
                    'no'                     => $item['no'],
                    'jobdesk'                => $item['jobdesk'],
                    'detail_proyek'          => $item['detail_proyek'],
                    'implementasi'           => '',
                    'sasaran_mutu'           => '',
                    'metode_pengukuran'      => '',
                    'indikator_keberhasilan' => '',
                    'keterangan'             => '',
                    'link_dokumen'           => '',
                    'laporan_count'          => $lapCount,
                    'task_count'             => $taskCount,
                    'has_sync_data'          => ($lapCount > 0 || $taskCount > 0),
                    'is_custom'              => false
                ];
            }

            // Timpa dengan data yang sudah diisi langsung oleh karyawan/head di sistem Office
            if (isset($overrides[$itemId])) {
                $ov = $overrides[$itemId];
                if (isset($ov['detail_proyek']) && $ov['detail_proyek'] !== '') $row['detail_proyek'] = $ov['detail_proyek'];
                if (isset($ov['implementasi'])) $row['implementasi'] = $ov['implementasi'];
                if (isset($ov['sasaran_mutu'])) $row['sasaran_mutu'] = $ov['sasaran_mutu'];
                if (isset($ov['metode_pengukuran'])) $row['metode_pengukuran'] = $ov['metode_pengukuran'];
                if (isset($ov['indikator_keberhasilan'])) $row['indikator_keberhasilan'] = $ov['indikator_keberhasilan'];
                if (isset($ov['keterangan'])) $row['keterangan'] = $ov['keterangan'];
                if (isset($ov['updated_by_name'])) $row['updated_by_name'] = $ov['updated_by_name'];
                if (isset($ov['updated_at'])) $row['updated_at'] = $ov['updated_at'];
            }

            // Sinkronisasi link dokumen dari Audit Kinerja jika ada
            if (isset($kinerjaRecords[$itemId]) && !empty($kinerjaRecords[$itemId]['link_dokumen'])) {
                $row['link_dokumen'] = $kinerjaRecords[$itemId]['link_dokumen'];
            }

            // Filter search jika ada
            if ($search) {
                $sLower = strtolower($search);
                $haystack = strtolower(
                    $row['divisi'] . ' ' .
                    $row['posisi'] . ' ' .
                    $row['jobdesk'] . ' ' .
                    $row['detail_proyek'] . ' ' .
                    $row['implementasi'] . ' ' .
                    $row['sasaran_mutu']
                );
                if (strpos($haystack, $sLower) === false) {
                    continue;
                }
            }

            $totalItems++;
            if (!empty($row['keterangan']) || !empty($row['implementasi'])) {
                $filledCount++;
            }

            $results[] = $row;
        }

        // Sertakan juga jobdesk baru / custom yang ditambah langsung untuk tahun 2027+
        foreach ($overrides as $ovId => $ov) {
            if (!in_array($ovId, $masterIds)) {
                $itemDiv = isset($ov['divisi']) ? $ov['divisi'] : '';
                $itemPos = isset($ov['posisi']) ? $ov['posisi'] : '';
                $itemJob = isset($ov['jobdesk']) ? $ov['jobdesk'] : '';

                if ($divisi && $divisi !== 'all' && strcasecmp($itemDiv, $divisi) !== 0) {
                    continue;
                }
                if ($posisi && $posisi !== 'all' && strcasecmp($itemPos, $posisi) !== 0) {
                    continue;
                }

                $taskCount = isset($tasksBySmId[$ovId]) ? $tasksBySmId[$ovId] : 0;
                $lapCount = 0;

                $row = [
                    'id'                     => $ovId,
                    'year'                   => $year,
                    'divisi'                 => $itemDiv,
                    'divisi_key'             => str_replace([' ', ','], '_', $itemDiv),
                    'posisi'                 => $itemPos,
                    'no'                     => isset($ov['no']) ? $ov['no'] : '',
                    'jobdesk'                => $itemJob,
                    'detail_proyek'          => isset($ov['detail_proyek']) ? $ov['detail_proyek'] : '',
                    'implementasi'           => isset($ov['implementasi']) ? $ov['implementasi'] : '',
                    'sasaran_mutu'           => isset($ov['sasaran_mutu']) ? $ov['sasaran_mutu'] : '',
                    'metode_pengukuran'      => isset($ov['metode_pengukuran']) ? $ov['metode_pengukuran'] : '',
                    'indikator_keberhasilan' => isset($ov['indikator_keberhasilan']) ? $ov['indikator_keberhasilan'] : '',
                    'keterangan'             => isset($ov['keterangan']) ? $ov['keterangan'] : '',
                    'link_dokumen'           => isset($kinerjaRecords[$ovId]['link_dokumen']) ? $kinerjaRecords[$ovId]['link_dokumen'] : '',
                    'laporan_count'          => $lapCount,
                    'task_count'             => $taskCount,
                    'has_sync_data'          => ($lapCount > 0 || $taskCount > 0),
                    'updated_by_name'        => isset($ov['updated_by_name']) ? $ov['updated_by_name'] : '',
                    'updated_at'             => isset($ov['updated_at']) ? $ov['updated_at'] : '',
                    'is_custom'              => true
                ];

                if ($search) {
                    $sLower = strtolower($search);
                    $haystack = strtolower($row['divisi'] . ' ' . $row['posisi'] . ' ' . $row['jobdesk'] . ' ' . $row['detail_proyek'] . ' ' . $row['implementasi']);
                    if (strpos($haystack, $sLower) === false) continue;
                }

                $totalItems++;
                if (!empty($row['keterangan']) || !empty($row['implementasi'])) {
                    $filledCount++;
                }

                $results[] = $row;
            }
        }

        // Hitung progres pengisian per divisi untuk monitoring HR
        $divProgress = [];
        foreach ($results as $r) {
            $d = $r['divisi'];
            if (!isset($divProgress[$d])) {
                $divProgress[$d] = ['total' => 0, 'filled' => 0];
            }
            $divProgress[$d]['total']++;
            if (!empty($r['implementasi']) || !empty($r['keterangan'])) {
                $divProgress[$d]['filled']++;
            }
        }
        foreach ($divProgress as $d => $p) {
            $divProgress[$d]['pct'] = $p['total'] > 0 ? round(($p['filled'] / $p['total']) * 100, 1) : 0;
        }

        $persentase = $totalItems > 0 ? round(($filledCount / $totalItems) * 100, 1) : 0;

        return [
            'year'          => $year,
            'total_items'   => $totalItems,
            'filled_count'  => $filledCount,
            'persentase'    => $persentase,
            'div_progress'  => $divProgress,
            'data'          => $results
        ];
    }

    /**
     * Ambil catatan override Sasaran Mutu (dari DB atau JSON, dicache di RAM)
     */
    public function loadSasaranMutuOverrides($year)
    {
        if (isset(self::$overridesCache[$year])) {
            return self::$overridesCache[$year];
        }

        $overrides = [];

        // 1. Coba baca dari database jika tabel tersedia
        if ($this->hasTable('sasaran_mutu_records')) {
            $orig = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $q = @$this->db->get_where('sasaran_mutu_records', ['year' => $year]);
            if ($q && $q->num_rows() > 0) {
                foreach ($q->result_array() as $r) {
                    $overrides[$r['item_id']] = $r;
                }
            }
            $this->db->db_debug = $orig;
        }

        // 2. Baca dari JSON fallback jika ada
        if (file_exists($this->overridesFilePath)) {
            $json = file_get_contents($this->overridesFilePath);
            if (substr($json, 0, 3) === "\xEF\xBB\xBF") {
                $json = substr($json, 3);
            }
            $fileData = json_decode(trim($json), true);
            if (isset($fileData[$year]) && is_array($fileData[$year])) {
                foreach ($fileData[$year] as $itemId => $r) {
                    if (!isset($overrides[$itemId])) {
                        $overrides[$itemId] = $r;
                    }
                }
            }
        }

        self::$overridesCache[$year] = $overrides;
        return $overrides;
    }

    /**
     * Simpan / update item Sasaran Mutu langsung dari sistem Office
     */
    public function saveSasaranMutuItem($year, $itemId, $payload)
    {
        $year = (int)$year;
        unset(self::$overridesCache[$year]);
        $now = date('Y-m-d H:i:s');
        $userId = sessPenggunaId();
        $userName = sessNama();

        $record = [
            'year'                   => $year,
            'item_id'                => $itemId,
            'divisi'                 => isset($payload['divisi']) ? $payload['divisi'] : '',
            'posisi'                 => isset($payload['posisi']) ? $payload['posisi'] : '',
            'jobdesk'                => isset($payload['jobdesk']) ? $payload['jobdesk'] : '',
            'detail_proyek'          => isset($payload['detail_proyek']) ? $payload['detail_proyek'] : '',
            'implementasi'           => isset($payload['implementasi']) ? $payload['implementasi'] : '',
            'sasaran_mutu'           => isset($payload['sasaran_mutu']) ? $payload['sasaran_mutu'] : '',
            'metode_pengukuran'      => isset($payload['metode_pengukuran']) ? $payload['metode_pengukuran'] : '',
            'indikator_keberhasilan' => isset($payload['indikator_keberhasilan']) ? $payload['indikator_keberhasilan'] : '',
            'keterangan'             => isset($payload['keterangan']) ? $payload['keterangan'] : '',
            'updated_by'             => $userId,
            'updated_by_name'        => $userName,
            'updated_at'             => $now
        ];

        // 1. Simpan ke database jika tabel tersedia
        if ($this->hasTable('sasaran_mutu_records')) {
            $orig = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $exist = @$this->db->get_where('sasaran_mutu_records', ['year' => $year, 'item_id' => $itemId])->row();
            if ($exist) {
                @$this->db->where('id', $exist->id)->update('sasaran_mutu_records', $record);
            } else {
                @$this->db->insert('sasaran_mutu_records', $record);
            }
            $this->db->db_debug = $orig;
        }

        // 2. Simpan ke JSON file agar permanen
        $fileData = [];
        if (file_exists($this->overridesFilePath)) {
            $fileData = json_decode(file_get_contents($this->overridesFilePath), true) ?: [];
        }
        if (!isset($fileData[$year])) {
            $fileData[$year] = [];
        }
        $fileData[$year][$itemId] = $record;
        @file_put_contents($this->overridesFilePath, json_encode($fileData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // 3. Sinkronisasi dua arah ke Audit Kinerja jika link_dokumen diisi
        if (isset($payload['link_dokumen']) && $payload['link_dokumen'] !== '') {
            $this->saveAuditKinerjaItem($year, $itemId, [
                'link_dokumen' => $payload['link_dokumen'],
                'implementasi' => $record['implementasi']
            ]);
        }

        return true;
    }

    /**
     * Tambah item jobdesk baru untuk divisi/posisi di tahun 2027 dan seterusnya
     */
    public function addCustomJobdeskItem($year, $payload)
    {
        $year = (int)$year;
        $divisiKey = isset($payload['divisi']) ? preg_replace('/[^a-zA-Z0-9]/', '', $payload['divisi']) : 'CUSTOM';
        $itemId = 'SM_' . $divisiKey . '_CUSTOM_' . time();
        return $this->saveSasaranMutuItem($year, $itemId, $payload);
    }

    /**
     * Ekstraksi kata kunci penting dari teks jobdesk
     */
    public function extractKeywords($text)
    {
        if (empty($text)) return [];
        $clean = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', strtolower($text));
        $words = preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY);
        $stopWords = [
            'dan', 'yang', 'dari', 'untuk', 'pada', 'atau', 'dengan', 'ke', 'di', 'ini', 'itu', 
            'dalam', 'adalah', 'sebagai', 'secara', 'serta', 'oleh', 'atas', 'setiap', 'agar', 
            'melakukan', 'membuat', 'melaporkan', 'mengelola', 'proses', 'kegiatan', 'rutin'
        ];
        $keywords = [];
        foreach ($words as $w) {
            if (strlen($w) >= 4 && !in_array($w, $stopWords) && !in_array($w, $keywords)) {
                $keywords[] = $w;
            }
        }
        return array_slice($keywords, 0, 8);
    }

    /**
     * Ambil data ringkasan Laporan Mingguan dan Task yang relevan untuk suatu item Sasaran Mutu
     */
    public function getSyncDataForItem($year, $itemId, $userId = null, $jobdeskText = null, $posisi = null)
    {
        $year = (int)$year;
        if ($year < 2020) $year = (int)date('Y');

        if (empty($userId)) {
            $userId = sessPenggunaId();
        }

        // Ambil item master/override jika teks belum diberikan
        if (empty($jobdeskText) || empty($posisi)) {
            $master = $this->getMasterTemplate();
            foreach ($master as $m) {
                if ($m['id'] === $itemId) {
                    $jobdeskText = $m['jobdesk'];
                    $posisi = $m['posisi'];
                    break;
                }
            }
        }

        $laporanList = [];
        $taskList = [];
        $links = [];
        $totalProgress = 0;
        $progressCount = 0;

        if ($this->db->conn_id) {
            $orig_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;

            $keywords = $this->extractKeywords($jobdeskText);

            // 1. Ambil Laporan Mingguan dari tabel `laporan`
            if ($this->hasTable('laporan')) {
                $this->db->select('l.id, l.tanggal, l.id_pengaju, l.jobdesc, l.jenis, l.progress, l.status_pekerjaan, l.link, l.ket_hasil, l.keterangan, p.nama, p.jabatan')
                    ->from('laporan l')
                    ->join('pengguna p', 'p.pengguna_id = l.id_pengaju', 'left')
                    ->where('YEAR(l.tanggal)', $year)
                    ->where('l.status !=', 3);

                if (!empty($userId)) {
                    $this->db->where('l.id_pengaju', $userId);
                }

                $laporanQ = @$this->db->order_by('l.tanggal', 'DESC')->get();
                $laporanRows = ($laporanQ && $laporanQ->num_rows() > 0) ? $laporanQ->result_array() : [];

                if (!empty($laporanRows)) {
                    foreach ($laporanRows as $lap) {
                        $isMatch = false;
                        if (!empty($userId) && $lap['id_pengaju'] == $userId) {
                            if (!empty($keywords)) {
                                $lapText = strtolower($lap['jobdesc'] . ' ' . $lap['ket_hasil'] . ' ' . $lap['keterangan']);
                                foreach ($keywords as $kw) {
                                    if (strlen($kw) >= 4 && strpos($lapText, $kw) !== false) {
                                        $isMatch = true;
                                        break;
                                    }
                                }
                            } else {
                                $isMatch = true;
                            }
                        } else if (!empty($keywords)) {
                            $lapText = strtolower($lap['jobdesc'] . ' ' . $lap['ket_hasil']);
                            foreach ($keywords as $kw) {
                                if (strlen($kw) >= 4 && strpos($lapText, $kw) !== false) {
                                    $isMatch = true;
                                    break;
                                }
                            }
                        }

                        if ($isMatch) {
                            $laporanList[] = $lap;
                            if (!empty($lap['link'])) {
                                $links[] = $lap['link'];
                            }
                            $cleanProg = preg_replace('/[^0-9.]/', '', (string)$lap['progress']);
                            if ($cleanProg !== '' && is_numeric($cleanProg)) {
                                $totalProgress += (float)$cleanProg;
                                $progressCount++;
                            }
                        }
                    }
                }
            }

            // 2. Ambil Task dari tabel `task_proyek`
            if ($this->hasTable('task_proyek')) {
                $this->db->select('t.id_task, t.kode_task, t.judul, t.nama_proyek, t.jenis, t.deskripsi, t.progres, t.status_task, t.catatan_bawahan, t.bukti_selesai, t.rating_bintang, t.created_at, t.tgl_submit, t.id_sasaran_mutu, t.nama_jobdesk, p_beri.nama as nama_atasan')
                    ->from('task_proyek t')
                    ->join('pengguna p_beri', 'p_beri.pengguna_id = t.id_pemberi', 'left')
                    ->where('t.is_deleted', 0)
                    ->where("(YEAR(t.created_at) = {$year} OR (t.tgl_submit IS NOT NULL AND YEAR(t.tgl_submit) = {$year}))");

                if (!empty($userId)) {
                    $this->db->where('t.id_penerima', $userId);
                }

                $taskQ = @$this->db->order_by('t.id_task', 'DESC')->get();
                $taskRows = ($taskQ && $taskQ->num_rows() > 0) ? $taskQ->result_array() : [];

                if (!empty($taskRows)) {
                    foreach ($taskRows as $task) {
                        $isMatch = false;
                        if (!empty($task['id_sasaran_mutu']) && $task['id_sasaran_mutu'] === $itemId) {
                            $isMatch = true;
                        } else if (!empty($keywords)) {
                            $taskText = strtolower($task['judul'] . ' ' . $task['nama_proyek'] . ' ' . $task['deskripsi'] . ' ' . $task['catatan_bawahan']);
                            foreach ($keywords as $kw) {
                                if (strlen($kw) >= 4 && strpos($taskText, $kw) !== false) {
                                    $isMatch = true;
                                    break;
                                }
                            }
                        } else {
                            $isMatch = true;
                        }

                        if ($isMatch) {
                            $taskList[] = $task;
                            if (!empty($task['bukti_selesai'])) {
                                $links[] = $task['bukti_selesai'];
                            }
                            if (is_numeric($task['progres'])) {
                                $totalProgress += (float)$task['progres'];
                                $progressCount++;
                            }
                        }
                    }
                }
            }

            $this->db->db_debug = $orig_debug;
        }

        // Format sintesis Implementasi Nyata Kegiatan
        $implLines = [];
        if (!empty($laporanList)) {
            $implLines[] = "[Realisasi Laporan Mingguan (" . count($laporanList) . " Laporan)]";
            $maxLap = array_slice($laporanList, 0, 5);
            foreach ($maxLap as $lap) {
                $tgl = date('d/m/Y', strtotime($lap['tanggal']));
                $desc = !empty($lap['ket_hasil']) ? $lap['ket_hasil'] : (!empty($lap['jobdesc']) ? $lap['jobdesc'] : 'Kegiatan terlaksana');
                $prog = !empty($lap['progress']) ? " (Progress: {$lap['progress']})" : "";
                $implLines[] = "• [{$tgl}] {$desc}{$prog}";
            }
            if (count($laporanList) > 5) {
                $implLines[] = "• ...dan " . (count($laporanList) - 5) . " laporan mingguan lainnya.";
            }
        }

        if (!empty($taskList)) {
            if (!empty($implLines)) $implLines[] = "";
            $implLines[] = "[Realisasi Task yang Diberikan Atasan (" . count($taskList) . " Task)]";
            $maxTask = array_slice($taskList, 0, 5);
            foreach ($maxTask as $task) {
                $statusStr = $task['status_task'] == 3 ? 'SELESAI (100%)' : ($task['status_task'] == 2 ? 'MENUNGGU REVIEW' : "PROGRES {$task['progres']}%");
                $catatan = !empty($task['catatan_bawahan']) ? " - " . $task['catatan_bawahan'] : "";
                $tagJob = !empty($task['nama_jobdesk']) ? " [Jobdesk: {$task['nama_jobdesk']}]" : "";
                $implLines[] = "• [{$task['kode_task']}] {$task['judul']} ({$statusStr}){$tagJob}{$catatan}";
            }
            if (count($taskList) > 5) {
                $implLines[] = "• ...dan " . (count($taskList) - 5) . " task proyek lainnya.";
            }
        }

        $avgProg = $progressCount > 0 ? round($totalProgress / $progressCount, 0) : 100;
        $uniqueLinks = array_unique(array_filter($links));
        $firstLink = !empty($uniqueLinks) ? reset($uniqueLinks) : '';

        $keteranganStr = "";
        if (!empty($laporanList) || !empty($taskList)) {
            $keteranganStr = "{$avgProg}% (" . count($laporanList) . " Lap. Mingguan, " . count($taskList) . " Task)";
        }

        return [
            'laporan_count'       => count($laporanList),
            'task_count'          => count($taskList),
            'laporan_items'       => $laporanList,
            'task_items'          => $taskList,
            'compiled_impl'       => implode("\n", $implLines),
            'compiled_link'       => $firstLink,
            'all_links'           => array_values($uniqueLinks),
            'compiled_keterangan' => $keteranganStr,
            'avg_progress'        => $avgProg
        ];
    }

    /**
     * Auto-sync satu item Sasaran Mutu dengan data Laporan Mingguan & Task
     */
    public function autoSyncItemWithActivities($year, $itemId, $userId = null)
    {
        $sync = $this->getSyncDataForItem($year, $itemId, $userId);
        if ($sync['laporan_count'] === 0 && $sync['task_count'] === 0) {
            return [
                'status'  => 'empty',
                'message' => 'Belum ada data Laporan Mingguan atau Task Proyek yang cocok dengan jobdesk ini pada periode ' . $year,
                'sync'    => $sync
            ];
        }

        $payload = [
            'implementasi' => $sync['compiled_impl'],
            'link_dokumen' => $sync['compiled_link'],
            'keterangan'   => $sync['compiled_keterangan']
        ];

        $this->saveSasaranMutuItem($year, $itemId, $payload);

        return [
            'status'  => 'success',
            'message' => 'Berhasil mensinkronkan ' . $sync['laporan_count'] . ' Laporan Mingguan dan ' . $sync['task_count'] . ' Task Proyek ke Sasaran Mutu',
            'sync'    => $sync
        ];
    }

    /**
     * Auto-sync seluruh item Sasaran Mutu milik user dengan data Laporan Mingguan & Task
     */
    public function autoSyncAllItemsForUser($year, $userId = null)
    {
        if (empty($userId)) {
            $userId = sessPenggunaId();
        }

        $jabatan = '';
        if ($this->db->conn_id) {
            $orig_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $u = @$this->db->select('jabatan')->where('pengguna_id', $userId)->get('pengguna')->row();
            if ($u) $jabatan = $u->jabatan;
            $this->db->db_debug = $orig_debug;
        }

        $smData = $this->getSasaranMutuData($year);
        $syncedCount = 0;

        foreach ($smData['data'] as $item) {
            $isTarget = false;
            if (!empty($jabatan) && (strcasecmp($item['posisi'], $jabatan) === 0 || stripos($item['posisi'], $jabatan) !== false || stripos($jabatan, $item['posisi']) !== false)) {
                $isTarget = true;
            } else if (!empty($item['laporan_count']) || !empty($item['task_count'])) {
                $isTarget = true;
            }

            if ($isTarget) {
                $res = $this->autoSyncItemWithActivities($year, $item['id'], $userId);
                if ($res['status'] === 'success') {
                    $syncedCount++;
                }
            }
        }

        return [
            'status'       => 'success',
            'synced_count' => $syncedCount,
            'message'      => "Berhasil mensinkronkan {$syncedCount} item Sasaran Mutu dengan Laporan Mingguan & Task yang Diberikan!"
        ];
    }

    /**
     * Ambil data Audit Kinerja (jobdesk, detail proyek, implementasi nyata, link dokumen pekerjaan)
     */
    public function getAuditKinerjaData($year = 2026, $divisi = null, $posisi = null, $search = null)
    {
        $smResult = $this->getSasaranMutuData($year, $divisi, $posisi, $search, false);
        $smData = $smResult['data'];

        // Ambil data audit kinerja (link dokumen, status audit, catatan)
        $auditRecords = $this->loadAuditKinerjaRecords($year);

        $list = [];
        $verifiedCount = 0;
        $needEvidenceCount = 0;
        $pendingCount = 0;

        foreach ($smData as $item) {
            $itemId = $item['id'];
            $auditInfo = isset($auditRecords[$itemId]) ? $auditRecords[$itemId] : [];

            $linkDokumen = isset($auditInfo['link_dokumen']) ? $auditInfo['link_dokumen'] : (isset($item['link_dokumen']) ? $item['link_dokumen'] : '');
            $statusAudit = isset($auditInfo['status_audit']) && !empty($auditInfo['status_audit']) ? $auditInfo['status_audit'] : 'Belum Diaudit';
            $catatanAudit = isset($auditInfo['catatan_audit']) ? $auditInfo['catatan_audit'] : '';
            $auditedByName = isset($auditInfo['audited_by_name']) ? $auditInfo['audited_by_name'] : '';
            $auditUpdatedAt = isset($auditInfo['updated_at']) ? $auditInfo['updated_at'] : '';

            if ($statusAudit === 'Terverifikasi / Sesuai') {
                $verifiedCount++;
            } else if ($statusAudit === 'Perlu Bukti Tambahan') {
                $needEvidenceCount++;
            } else {
                $pendingCount++;
            }

            $list[] = [
                'id'                     => $itemId,
                'year'                   => $year,
                'divisi'                 => $item['divisi'],
                'divisi_key'             => $item['divisi_key'],
                'posisi'                 => $item['posisi'],
                'no'                     => $item['no'],
                'jobdesk'                => $item['jobdesk'],
                'detail_proyek'          => $item['detail_proyek'],
                'implementasi'           => $item['implementasi'],
                'sasaran_mutu'           => $item['sasaran_mutu'],
                'link_dokumen'           => $linkDokumen,
                'status_audit'           => $statusAudit,
                'catatan_audit'          => $catatanAudit,
                'audited_by_name'        => $auditedByName,
                'updated_at'             => $auditUpdatedAt
            ];
        }

        return [
            'year'                => $year,
            'total_items'         => count($list),
            'verified_count'      => $verifiedCount,
            'need_evidence_count' => $needEvidenceCount,
            'pending_count'       => $pendingCount,
            'div_progress'        => isset($smResult['div_progress']) ? $smResult['div_progress'] : [],
            'data'                => $list
        ];
    }

    private function loadAuditKinerjaRecords($year)
    {
        if (isset(self::$kinerjaCache[$year])) {
            return self::$kinerjaCache[$year];
        }

        $records = [];
        if ($this->hasTable('audit_kinerja_records')) {
            $orig = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $q = @$this->db->get_where('audit_kinerja_records', ['year' => $year]);
            if ($q && $q->num_rows() > 0) {
                foreach ($q->result_array() as $r) {
                    $records[$r['item_id']] = $r;
                }
            }
            $this->db->db_debug = $orig;
        }

        if (file_exists($this->kinerjaFilePath)) {
            $fileData = json_decode(file_get_contents($this->kinerjaFilePath), true);
            if (isset($fileData[$year]) && is_array($fileData[$year])) {
                foreach ($fileData[$year] as $itemId => $r) {
                    if (!isset($records[$itemId])) {
                        $records[$itemId] = $r;
                    }
                }
            }
        }

        self::$kinerjaCache[$year] = $records;
        return $records;
    }

    /**
     * Simpan / update data Audit Kinerja (link dokumen & verifikasi)
     */
    public function saveAuditKinerjaItem($year, $itemId, $payload)
    {
        $year = (int)$year;
        unset(self::$kinerjaCache[$year]);
        $now = date('Y-m-d H:i:s');
        $userId = sessPenggunaId();
        $userName = sessNama();

        $linkDokumen = isset($payload['link_dokumen']) ? trim($payload['link_dokumen']) : '';
        $statusAudit = isset($payload['status_audit']) ? trim($payload['status_audit']) : 'Belum Diaudit';
        $catatanAudit = isset($payload['catatan_audit']) ? trim($payload['catatan_audit']) : '';

        $record = [
            'year'            => $year,
            'item_id'         => $itemId,
            'link_dokumen'    => $linkDokumen,
            'status_audit'    => $statusAudit,
            'catatan_audit'   => $catatanAudit,
            'audited_by'      => $userId,
            'audited_by_name' => $userName,
            'updated_at'      => $now
        ];

        // 1. Simpan DB jika tabel tersedia
        if ($this->hasTable('audit_kinerja_records')) {
            $orig = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $exist = @$this->db->get_where('audit_kinerja_records', ['year' => $year, 'item_id' => $itemId])->row();
            if ($exist) {
                @$this->db->where('id', $exist->id)->update('audit_kinerja_records', $record);
            } else {
                @$this->db->insert('audit_kinerja_records', $record);
            }
            $this->db->db_debug = $orig;
        }

        // 2. Simpan JSON
        $fileData = [];
        if (file_exists($this->kinerjaFilePath)) {
            $fileData = json_decode(file_get_contents($this->kinerjaFilePath), true) ?: [];
        }
        if (!isset($fileData[$year])) {
            $fileData[$year] = [];
        }
        $fileData[$year][$itemId] = $record;
        @file_put_contents($this->kinerjaFilePath, json_encode($fileData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // 3. Jika implementasi juga diupdate oleh user saat mengisi link dokumen, sinkronkan ke Sasaran Mutu
        if (isset($payload['implementasi']) && $payload['implementasi'] !== '') {
            $this->saveSasaranMutuItem($year, $itemId, [
                'implementasi' => $payload['implementasi']
            ]);
        }

        return true;
    }

    /**
     * Ambil data Audit Keuangan (PBOK yang diajukan beserta invoice & lampirannya)
     */
    public function getAuditKeuanganData($year = null, $bulan = null, $status = null, $search = null)
    {
        $records = [];
        $totalNominal = 0;
        $totalPbok = 0;
        $withInvoice = 0;
        $withoutInvoice = 0;

        // Ambil audit verifikasi keuangan dari DB / JSON
        $keuanganAuditMap = $this->loadAuditKeuanganRecords();

        if ($this->hasTable('surat_pbok')) {
            $orig_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;

            $this->db->select('
                pbok.id_pbok as idPbok,
                pbok.kode_pbok as kode,
                pbok.tgl_pengajuan,
                pbok.bank,
                pbok.rekening,
                pbok.ats_nama,
                pbok.kota_aju,
                pbok.alamat,
                pbok.pengajuan_ttd_1 as aju_ttd1,
                pbok.ttd_2 as aju_ttd2,
                pbok.ttd_3 as aju_ttd3,
                pbok.lampiran,
                pbok.mata_uang,
                p.pengguna_id,
                p.nama as pengguna,
                p.jabatan as jabatan,
                d.nama as divisi,
                s.status as stat_persetujuan
            ')
            ->from('surat_pbok pbok')
            ->join('surat_list s', 's.id_srt = pbok.id_pbok AND s.id_kat_surat = 4', 'left')
            ->join('pengguna p', 'pbok.id_pengguna = p.pengguna_id', 'left')
            ->join('divisi d', 'p.id_divisi = d.id_divisi', 'left')
            ->order_by('pbok.id_pbok', 'DESC');

            if ($year) {
                $this->db->where('YEAR(pbok.tgl_pengajuan)', (int)$year);
            }
            if ($bulan) {
                $this->db->where('MONTH(pbok.tgl_pengajuan)', (int)$bulan);
            }
            if ($search) {
                $this->db->group_start()
                    ->like('pbok.kode_pbok', $search)
                    ->or_like('p.nama', $search)
                    ->or_like('p.jabatan', $search)
                    ->or_like('pbok.ats_nama', $search)
                    ->group_end();
            }

            $query = @$this->db->get();
            if ($query && $query->num_rows() > 0) {
                $pbokList = $query->result_array();
                $idList = array_column($pbokList, 'idPbok');

                // Ambil seluruh detail item PBOK yang ada
                $detailsByPbok = [];
                if (!empty($idList) && $this->hasTable('surat_pbok_detail')) {
                    $this->db->select('id, id_pbok, kode_detail, keterangan, estimasi, qty, total, catatan, mata_uang')
                        ->from('surat_pbok_detail')
                        ->where_in('id_pbok', $idList)
                        ->order_by('id', 'ASC');
                    $detailQuery = @$this->db->get();
                    if ($detailQuery && $detailQuery->num_rows() > 0) {
                        foreach ($detailQuery->result_array() as $det) {
                            $detailsByPbok[$det['id_pbok']][] = $det;
                        }
                    }
                }

                foreach ($pbokList as $pbok) {
                    $idPbok = (int)$pbok['idPbok'];
                    $items = isset($detailsByPbok[$idPbok]) ? $detailsByPbok[$idPbok] : [];

                    $pbokTotal = 0;
                    $itemNames = [];
                    foreach ($items as $it) {
                        $pbokTotal += (float)$it['total'];
                        if (!empty($it['keterangan'])) {
                            $itemNames[] = $it['keterangan'] . ' (' . $it['qty'] . 'x)';
                        }
                    }

                    $hasLampiran = !empty($pbok['lampiran']);
                    if ($hasLampiran) {
                        $withInvoice++;
                    } else {
                        $withoutInvoice++;
                    }

                    $auditInfo = isset($keuanganAuditMap[$idPbok]) ? $keuanganAuditMap[$idPbok] : [];
                    $statusAudit = isset($auditInfo['status_audit']) ? $auditInfo['status_audit'] : ($hasLampiran ? 'Lampiran Tersedia' : 'Belum Ada Lampiran');
                    $catatanAudit = isset($auditInfo['catatan_audit']) ? $auditInfo['catatan_audit'] : '';
                    $kelengkapanInvoice = isset($auditInfo['kelengkapan_invoice']) ? $auditInfo['kelengkapan_invoice'] : ($hasLampiran ? 'Lengkap' : 'Tidak Ada File');

                    if ($status && $status !== 'all') {
                        if (strcasecmp($statusAudit, $status) !== 0) {
                            continue;
                        }
                    }

                    $totalNominal += $pbokTotal;
                    $totalPbok++;

                    $records[] = [
                        'id_pbok'              => $idPbok,
                        'kode_pbok'            => $pbok['kode'],
                        'tgl_pengajuan'        => $pbok['tgl_pengajuan'],
                        'pengguna'             => $pbok['pengguna'],
                        'jabatan'              => $pbok['jabatan'],
                        'divisi'               => $pbok['divisi'] ?: 'General',
                        'bank'                 => $pbok['bank'],
                        'rekening'             => $pbok['rekening'],
                        'ats_nama'             => $pbok['ats_nama'],
                        'alamat'               => $pbok['alamat'],
                        'lampiran'             => $pbok['lampiran'],
                        'has_lampiran'         => $hasLampiran,
                        'total_nominal'        => $pbokTotal,
                        'items'                => $items,
                        'ringkasan_barang'     => implode(', ', $itemNames),
                        'aju_ttd1'             => $pbok['aju_ttd1'],
                        'aju_ttd2'             => $pbok['aju_ttd2'],
                        'aju_ttd3'             => $pbok['aju_ttd3'],
                        'stat_persetujuan'     => $pbok['stat_persetujuan'],
                        'status_audit'         => $statusAudit,
                        'kelengkapan_invoice'  => $kelengkapanInvoice,
                        'catatan_audit'        => $catatanAudit,
                        'audited_by_name'      => isset($auditInfo['audited_by_name']) ? $auditInfo['audited_by_name'] : '',
                        'updated_at'           => isset($auditInfo['updated_at']) ? $auditInfo['updated_at'] : ''
                    ];
                }
            }
            $this->db->db_debug = $orig_debug;
        }

        return [
            'total_pbok'       => $totalPbok,
            'total_nominal'    => $totalNominal,
            'with_invoice'     => $withInvoice,
            'without_invoice'  => $withoutInvoice,
            'data'             => $records
        ];
    }

    private function loadAuditKeuanganRecords()
    {
        $records = [];
        if ($this->hasTable('audit_keuangan_records')) {
            $orig = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $q = @$this->db->get('audit_keuangan_records');
            if ($q && $q->num_rows() > 0) {
                foreach ($q->result_array() as $r) {
                    $records[(int)$r['id_pbok']] = $r;
                }
            }
            $this->db->db_debug = $orig;
        }

        if (file_exists($this->keuanganFilePath)) {
            $fileData = json_decode(file_get_contents($this->keuanganFilePath), true);
            if (is_array($fileData)) {
                foreach ($fileData as $idPbok => $r) {
                    if (!isset($records[(int)$idPbok])) {
                        $records[(int)$idPbok] = $r;
                    }
                }
            }
        }

        return $records;
    }

    /**
     * Simpan verifikasi Audit Keuangan PBOK
     */
    public function saveAuditKeuanganItem($idPbok, $payload)
    {
        $idPbok = (int)$idPbok;
        $now = date('Y-m-d H:i:s');
        $userId = sessPenggunaId();
        $userName = sessNama();

        $statusAudit = isset($payload['status_audit']) ? trim($payload['status_audit']) : 'Sesuai & Valid';
        $kelengkapanInvoice = isset($payload['kelengkapan_invoice']) ? trim($payload['kelengkapan_invoice']) : 'Lengkap';
        $catatanAudit = isset($payload['catatan_audit']) ? trim($payload['catatan_audit']) : '';

        $record = [
            'id_pbok'              => $idPbok,
            'status_audit'         => $statusAudit,
            'kelengkapan_invoice'  => $kelengkapanInvoice,
            'catatan_audit'        => $catatanAudit,
            'audited_by'           => $userId,
            'audited_by_name'      => $userName,
            'updated_at'           => $now
        ];

        // 1. Simpan DB jika tabel tersedia
        if ($this->hasTable('audit_keuangan_records')) {
            $orig = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $exist = @$this->db->get_where('audit_keuangan_records', ['id_pbok' => $idPbok])->row();
            if ($exist) {
                @$this->db->where('id', $exist->id)->update('audit_keuangan_records', $record);
            } else {
                @$this->db->insert('audit_keuangan_records', $record);
            }
            $this->db->db_debug = $orig;
        }

        // 2. Simpan JSON
        $fileData = [];
        if (file_exists($this->keuanganFilePath)) {
            $fileData = json_decode(file_get_contents($this->keuanganFilePath), true) ?: [];
        }
        $fileData[$idPbok] = $record;
        @file_put_contents($this->keuanganFilePath, json_encode($fileData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return true;
    }
}
