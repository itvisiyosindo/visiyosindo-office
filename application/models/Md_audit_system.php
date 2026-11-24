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

    private static $masterCache = [];
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
            $this->ensureAuditKeuanganColumns();
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
                link_drive TEXT,
                audited_by INT,
                audited_by_name VARCHAR(150),
                updated_at DATETIME,
                UNIQUE KEY uq_pbok (id_pbok)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        } else {
            $this->ensureAuditKeuanganColumns();
        }

        $this->db->db_debug = $orig;
    }

    /**
     * Memastikan kolom jenis_surat & id_surat tersedia di tabel audit_keuangan_records
     */
    private function ensureAuditKeuanganColumns()
    {
        if ($this->hasTable('audit_keuangan_records')) {
            $orig = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $fields = @$this->db->list_fields('audit_keuangan_records');
            if (is_array($fields)) {
                if (!in_array('link_drive', $fields)) {
                    @$this->db->query("ALTER TABLE audit_keuangan_records ADD COLUMN link_drive TEXT NULL AFTER kelengkapan_invoice;");
                }
                if (!in_array('jenis_surat', $fields)) {
                    @$this->db->query("ALTER TABLE audit_keuangan_records ADD COLUMN jenis_surat VARCHAR(20) DEFAULT 'pbok' AFTER id;");
                }
                if (!in_array('id_surat', $fields)) {
                    @$this->db->query("ALTER TABLE audit_keuangan_records ADD COLUMN id_surat INT NULL AFTER jenis_surat;");
                    @$this->db->query("UPDATE audit_keuangan_records SET id_surat = id_pbok WHERE id_surat IS NULL;");
                }
            }
            $this->db->db_debug = $orig;
        }
    }

    /**
     * Ambil data master template per tahun dari JSON (dichache di RAM agar tidak re-read disk)
     */
    public function getMasterTemplate($year = 2026)
    {
        $year = (int)$year;
        if (isset(self::$masterCache[$year])) {
            return self::$masterCache[$year];
        }

        $targetFile = $this->masterFilePath;
        if ($year > 0 && $year != 2026) {
            $yearFile = APPPATH . "data/sasaran_mutu_master_{$year}.json";
            if (file_exists($yearFile)) {
                $targetFile = $yearFile;
            }
        }

        if (file_exists($targetFile)) {
            $json = file_get_contents($targetFile);
            // Hapus UTF-8 BOM jika ada agar json_decode tidak return NULL
            if (substr($json, 0, 3) === "\xEF\xBB\xBF") {
                $json = substr($json, 3);
            }
            $json = trim($json);
            $data = json_decode($json, true);
            if (is_array($data)) {
                self::$masterCache[$year] = $data;
                return self::$masterCache[$year];
            }
        }
        return [];
    }

    /**
     * Resolusi divisi karyawan berdasarkan jabatan, posisi Sasaran Mutu, atau divisi DB
     * Memastikan posisi seperti Security / OB / GA / Gudang / Medrep / Marketing tidak salah default ke 'IT' atau 'Helper'
     */
    public function resolveUserDivision($userJabatan, $userDivisi = null, $resolvedPos = null)
    {
        $norm = function($str) {
            $s = html_entity_decode((string)$str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $s = str_replace(['&amp;', '&', '+', '/', '\\', '-', '_', '(', ')', ',', '.', ':', ';', '[', ']'], ' ', $s);
            return preg_replace('/\s+/', ' ', strtolower(trim($s)));
        };

        $normUser = $norm($userJabatan);
        $normDiv  = $norm($userDivisi);

        // 1. Prioritas Utama: Posisi keamanan & kebersihan mutlak General Affair
        if (preg_match('/\b(security|satpam|keamanan|office boy|ob|cleaning service|driver|supir|kebersihan)\b/', $normUser)) {
            return 'General Affair';
        }

        // 2. Divisi Support resmi (Regulatory & IT)
        if (preg_match('/\b(regulatory|import|ekspor|impor|izin edar)\b/', $normUser)) {
            return 'Support';
        }
        if (preg_match('/\b(it|programmer|developer|software|web|edp|hardware)\b/', $normUser)) {
            return 'Support';
        }

        // 3. Marketing & Kreatif
        if (preg_match('/\b(digital marketing|content creator|videografer|videographer|desain|grafis|animator|sales|marketing|medrep|representative)\b/', $normUser)) {
            return 'Marketing';
        }

        // 4. Jika resolvedPos ada di tree Sasaran Mutu, cari divisi resminya
        if (!empty($resolvedPos)) {
            $tree = $this->getDivisiAndPositions();
            foreach ($tree as $dName => $posArr) {
                if (in_array($resolvedPos, $posArr)) {
                    return $dName;
                }
            }
        }

        // 5. Pemetaan berbasis kata kunci jabatan lainnya
        // HR & GA
        if (preg_match('/\b(hr|hrd|legal|personalia|sdm|recruitment)\b/', $normUser)) {
            return 'HRGA';
        }
        if (preg_match('/\b(ga|general affair|general affairs|umum)\b/', $normUser)) {
            return 'General Affair';
        }

        // Warehouse / Gudang / Logistik
        if (preg_match('/\b(gudang|warehouse|logistik|pjt|helper)\b/', $normUser)) {
            return 'Warehouse';
        }

        // Accounting / Tax / Finance
        if (preg_match('/\b(akunting|accounting|keuangan|finance|tax|pajak|kasir)\b/', $normUser)) {
            return 'Akunting, Finance, Tax';
        }

        // Teknisi / Service / Kalibrasi
        if (preg_match('/\b(teknisi|technician|service|elektromedis|electromedical|engineer|technical support)\b/', $normUser)) {
            return 'Teknisi';
        }

        // Visilab / Penguji / Laboratorium
        if (preg_match('/\b(visilab|laboratorium|pengujian|penguji|staff mutu|staff teknis)\b/', $normUser)) {
            return 'Visilab';
        }

        // SGM
        if (preg_match('/\b(sgm|sumpah gajah mada)\b/', $normUser)) {
            return 'SGM';
        }

        // Direksi / Manajemen
        if (preg_match('/\b(direktur|director|komisaris|owner|general manager|gm)\b/', $normUser)) {
            return 'Manajemen';
        }

        // 6. Jika divisi DB bukan IT bawaan salah pasang, gunakan divisi DB
        if (!empty($userDivisi) && $normDiv !== 'it' && $normDiv !== '') {
            return $userDivisi;
        }

        return 'General';
    }

    /**
     * Deteksi dan cocokkan nama posisi karyawan dengan daftar master posisi Sasaran Mutu
     * Menghindari false match (seperti Direktur lain malah mencocokkan Director of Corporate Planning)
     */
    public function resolveUserPosition($userJabatan, $userDivisi = null)
    {
        if (empty($userJabatan) && empty($userDivisi)) return null;

        $master = $this->getMasterTemplate();
        $positions = [];
        foreach ($master as $m) {
            if (!empty($m['posisi']) && !in_array($m['posisi'], $positions)) {
                $positions[] = $m['posisi'];
            }
        }

        $norm = function($str) {
            $s = html_entity_decode((string)$str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $s = str_replace(['&amp;', '&', '+', '/', '\\', '-', '_', '(', ')', ',', '.', ':', ';', '[', ']'], ' ', $s);
            return preg_replace('/\s+/', ' ', strtolower(trim($s)));
        };

        $normUser = $norm($userJabatan);
        $normDiv  = $norm($userDivisi);
        // Gunakan jabatan sebagai sumber utama; jangan gabungkan divisi 'IT' bawaan DB karena banyak pegawai non-IT memiliki id_divisi = IT
        $normDivSafe = ($normDiv === 'it') ? '' : $normDiv;
        $combined = trim($normUser . ' ' . $normDivSafe);

        // 0. Jika Security / Office Boy / Management Trainee (belum ada di master posisi Sasaran Mutu), jangan dipaksa ke posisi lain
        if (preg_match('/\b(security|satpam|keamanan|office boy|ob|cleaning service|management trainee)\b/', $normUser)) {
            return null;
        }

        // 1. Exact match langsung (case-insensitive & clean symbols, abaikan kata penghubung 'and'/'dan')
        $stripConn = function($str) use ($norm) {
            return preg_replace('/\s+/', ' ', trim(preg_replace('/\b(and|dan)\b/', ' ', $norm($str))));
        };
        foreach ($positions as $p) {
            if ($norm($p) === $normUser || $stripConn($p) === $stripConn($userJabatan)) {
                return $p;
            }
        }

        // 2. Kamus Pemetaan Akurat (Domain Rules Spesifik Perusahaan - berbasis Jabatan terlebih dahulu)
        // Product Specialist / Aplicant (misal: Vera Puspita Sari - "Aplicant & Product Specialist")
        if (preg_match('/\b(product specialist|aplikasi|specialist product|aplicant|applicant)\b/', $normUser)) {
            return 'Aplicant and Specialist Product';
        }

        // IT / Developer -> HANYA dari nama Jabatan ($normUser), BUKAN dari nama Divisi DB ($normDiv) yang sering berisi default 'IT'
        if (preg_match('/\b(it|programmer|developer|software|web|edp|hardware)\b/', $normUser)) {
            return 'IT Support & Development';
        }

        // Akunting, Tax, Finance
        if (preg_match('/\b(akunting|accounting|keuangan|finance)\b/', $combined)) {
            if (preg_match('/\b(head|kepala|kabag|manager|manajer|spv|supervisor|lead)\b/', $normUser)) {
                return 'Head of Accounting and Tax';
            }
            if (preg_match('/\b(tax|pajak)\b/', $normUser)) {
                return 'Staff Tax';
            }
            return 'Staff Accounting';
        }
        if (preg_match('/\b(tax|pajak)\b/', $combined)) {
            if (preg_match('/\b(head|kepala|kabag|manager|manajer|spv|supervisor|lead)\b/', $normUser)) {
                return 'Head of Accounting and Tax';
            }
            return 'Staff Tax';
        }

        // Director of Corporate Planning and Business Management
        // HANYA COCOK JIKA ADA KATA KUNCI "corporate planning" ATAU "business management"
        if (preg_match('/\b(corporate planning|business management)\b/', $combined) || 
            (preg_match('/\b(director|direktur)\b/', $normUser) && preg_match('/\b(corporate|planning|business)\b/', $combined))) {
            return 'Director of Corporate Planning and Business Management';
        }

        // Direksi umum (Direktur Utama, Komisaris, Direktur Operasional) -> JANGAN DIPAKSA jadi Director of Corporate Planning!
        if (preg_match('/\b(direktur utama|komisaris|direksi|owner)\b/', $normUser)) {
            return null; // Bebas / Tanpa batas spesifik
        }

        // HR & Legal
        if (preg_match('/\b(hr|hrd|legal|personalia|sdm|recruitment)\b/', $combined)) {
            return 'HR & Legal';
        }

        // General Affair
        if (preg_match('/\b(ga|general affair|general affairs|umum)\b/', $combined)) {
            return 'General Affair';
        }

        // Gudang / Warehouse / Helper
        if (preg_match('/\b(gudang|warehouse|logistik|pjt|helper)\b/', $combined)) {
            if (preg_match('/\b(pjt|penanggung jawab|kepala gudang)\b/', $combined)) {
                return 'PJT & Warehouse';
            }
            if (preg_match('/\b(jogja|yogyakarta)\b/', $combined)) {
                return 'Helper (Gudang Jogja)';
            }
            if (preg_match('/\b(helper)\b/', $combined)) {
                return 'Helper (Gudang Pusat)';
            }
            return 'Admin Warehouse';
        }

        // Visilab / Laboratorium / Staff Mutu / Staff Teknis / Penguji
        if (preg_match('/\b(visilab|laboratorium|kalibrasi|pengujian|staff mutu|staf mutu|staff teknis|staf teknis|penguji)\b/', $combined)) {
            if (preg_match('/\b(head|kepala|manajer|manager)\b/', $normUser)) {
                return 'Head of Visilab';
            }
            if (preg_match('/\b(mutu)\b/', $combined)) {
                return 'Staff Visilab (Staff Mutu)';
            }
            if (preg_match('/\b(teknis)\b/', $combined)) {
                return 'Staff Visilab (Staff Teknis)';
            }
            if (preg_match('/\b(penguji|tester)\b/', $combined)) {
                return 'Penguji Berkualifikasi';
            }
            return 'Staff Visilab (admin visilab)';
        }

        // Teknisi / Service / Medical Engineer
        if (preg_match('/\b(teknisi|technician|service|elektromedis|electromedical|engineer|technical support)\b/', $combined)) {
            if (preg_match('/\b(head|kepala|kabag|spv|supervisor|lead)\b/', $normUser)) {
                return 'Head of Technician';
            }
            if (preg_match('/\b(elektro|electromedical)\b/', $combined)) {
                return 'Electromedical Engineer';
            }
            if (preg_match('/\b(medis|medical)\b/', $combined)) {
                return 'Medical Engineer';
            }
            return 'Technical Support';
        }

        // Sales & Marketing & SGM
        if (preg_match('/\b(sgm|sumpah gajah mada)\b/', $combined)) {
            return 'Head of SGM';
        }
        if (preg_match('/\b(gm|general manager)\b/', $normUser)) {
            return 'General Manager';
        }
        if (preg_match('/\b(sales manager|manajer sales|manager sales)\b/', $normUser)) {
            return 'Sales Manager';
        }
        if (preg_match('/\b(regulatory|import|ekspor|impor|izin edar)\b/', $combined)) {
            return 'Regulatory';
        }
        if (preg_match('/\b(product specialist|aplikasi|specialist product|aplicant|applicant)\b/', $combined)) {
            return 'Aplicant and Specialist Product';
        }
        if (preg_match('/\b(customer relationship|cro|customer service|cs|layanan pelanggan)\b/', $normUser)) {
            return 'Customer Relationship Officer';
        }
        if (preg_match('/\b(digital marketing|digimar)\b/', $combined)) {
            return 'Digital Marketing';
        }
        if (preg_match('/\b(content creator|videografer|videographer|desain|grafis|animator)\b/', $combined)) {
            return 'Content Creator';
        }
        if (preg_match('/\b(sales|marketing|medrep|representative)\b/', $combined)) {
            return 'Medical Representative';
        }

        // 3. Substring match HANYA untuk kata yang spesifik (bukan kata umum seperti staff, head, director)
        $genericWords = ['staff', 'staf', 'head', 'kepala', 'director', 'direktur', 'manager', 'manajer', 'admin', 'helper', 'officer', 'support', 'dan', 'and'];
        if (strlen($normUser) >= 5 && !in_array($normUser, $genericWords)) {
            foreach ($positions as $p) {
                $normP = $norm($p);
                if (strpos($normP, $normUser) !== false || strpos($normUser, $normP) !== false) {
                    return $p;
                }
            }
        }

        // 4. Overlap token dengan proteksi ketat
        $userWords = array_diff(explode(' ', $normUser), $genericWords);
        $bestMatch = null;
        $maxOverlap = 0;
        foreach ($positions as $p) {
            $pWords = array_diff(explode(' ', $norm($p)), $genericWords);
            $overlap = count(array_intersect($userWords, $pWords));
            if ($overlap >= 2 && $overlap > $maxOverlap) {
                $maxOverlap = $overlap;
                $bestMatch = $p;
            }
        }

        return $bestMatch;
    }

    /**
     * Kosongkan data Sasaran Mutu (Records saja atau Full Reset termasuk master)
     */
    public function clearSasaranMutuData($year = 2026, $mode = 'records_only')
    {
        $year = (int)$year;
        $orig = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        // 1. Kosongkan database records untuk tahun tersebut
        if ($this->hasTable('sasaran_mutu_records')) {
            @$this->db->where('year', $year)->delete('sasaran_mutu_records');
        }

        // 2. Kosongkan overrides JSON untuk tahun tersebut
        if (file_exists($this->overridesFilePath)) {
            $ov = json_decode(file_get_contents($this->overridesFilePath), true) ?: [];
            $ov[$year] = [];
            @file_put_contents($this->overridesFilePath, json_encode($ov, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        if ($mode === 'full') {
            // Kosongkan master data untuk tahun tersebut
            $yearMasterPath = APPPATH . "data/sasaran_mutu_master_{$year}.json";
            if (file_exists($yearMasterPath)) {
                @file_put_contents($yearMasterPath, json_encode([], JSON_PRETTY_PRINT));
            }
            if ($year === 2026 && file_exists($this->masterFilePath)) {
                @copy($this->masterFilePath, $this->masterFilePath . '.bak');
                @file_put_contents($this->masterFilePath, json_encode([], JSON_PRETTY_PRINT));
            }
            if ($this->hasTable('audit_kinerja_records')) {
                @$this->db->where('year', $year)->delete('audit_kinerja_records');
            }
        } else {
            // Bersihkan link bukti & status di audit kinerja
            if ($this->hasTable('audit_kinerja_records')) {
                @$this->db->where('year', $year)->update('audit_kinerja_records', [
                    'link_dokumen' => '',
                    'status_audit' => 'Belum Diaudit',
                    'catatan_audit' => ''
                ]);
            }
        }

        $this->db->db_debug = $orig;

        // Reset cache in-memory
        self::$masterCache = [];
        self::$overridesCache = [];
        self::$kinerjaCache = [];

        return true;
    }

    /**
     * Unduh dan parse spreadsheet dari URL (Mendukung link Google Spreadsheet & direct Excel URL)
     */
    public function downloadAndParseSpreadsheetUrl($url)
    {
        $url = trim($url);
        if (empty($url)) {
            return ['status' => 'error', 'message' => 'URL spreadsheet tidak boleh kosong'];
        }

        // 1. Deteksi Google Spreadsheet
        if (strpos($url, 'docs.google.com/spreadsheets') !== false) {
            if (preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $url, $m)) {
                $sheetId = $m[1];
                $gid = null;
                if (preg_match('/[#&?]gid=([0-9]+)/', $url, $gm)) {
                    $gid = $gm[1];
                }
                $exportUrl = "https://docs.google.com/spreadsheets/d/{$sheetId}/export?format=xlsx" . ($gid ? "&gid={$gid}" : "");
            } else {
                return ['status' => 'error', 'message' => 'Format link Google Spreadsheet tidak dikenali'];
            }
        } else {
            $exportUrl = $url;
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'sm_import_');
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 30,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ]);

        $fileContent = @file_get_contents($exportUrl, false, $ctx);
        if (empty($fileContent) && function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $exportUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $fileContent = curl_exec($ch);
            curl_close($ch);
        }

        if (empty($fileContent)) {
            @unlink($tempFile);
            return [
                'status' => 'error',
                'message' => 'Gagal mengunduh file spreadsheet dari link yang diberikan. Pastikan Google Spreadsheet diatur ke "Anyone with the link can view" (Siapa saja yang memiliki link dapat melihat).'
            ];
        }

        file_put_contents($tempFile, $fileContent);

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tempFile);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            @unlink($tempFile);
            return ['status' => 'success', 'data' => $sheetData];
        } catch (\Throwable $e) {
            @unlink($tempFile);
            return ['status' => 'error', 'message' => 'Gagal membaca format spreadsheet: ' . $e->getMessage()];
        }
    }

    /**
     * Parsing dan impor data spreadsheet ke dalam Master Sasaran Mutu
     */
    public function importSasaranMutuFromSpreadsheet($year, $sheetData)
    {
        if (empty($sheetData) || !is_array($sheetData)) {
            return ['status' => 'error', 'message' => 'Data spreadsheet kosong atau tidak dapat dibaca'];
        }

        $headerRowIdx = -1;
        $colMap = [];

        $keywords = [
            'divisi'                 => ['divisi', 'dept', 'departemen', 'bagian'],
            'posisi'                 => ['posisi', 'jabatan', 'role'],
            'jobdesk'                => ['jobdesk', 'job desc', 'uraian tugas', 'tugas', 'tanggung jawab'],
            'detail_proyek'          => ['detail proyek', 'list detail', 'rincian proyek', 'proyek'],
            'implementasi'           => ['implementasi', 'realisasi', 'catatan pekerjaan'],
            'sasaran_mutu'           => ['sasaran mutu', 'sasaran', 'target mutu', 'target'],
            'metode_pengukuran'      => ['metode pengukuran', 'pengukuran', 'cara ukur'],
            'indikator_keberhasilan' => ['indikator keberhasilan', 'indikator', 'kpi', 'tolok ukur'],
            'keterangan'             => ['keterangan', 'waktu pelaksanaan', 'periode', 'catatan'],
            'link_dokumen'           => ['link dokumen', 'link drive', 'bukti pekerjaan', 'link bukti', 'url']
        ];

        for ($r = 0; $r < min(15, count($sheetData)); $r++) {
            $row = $sheetData[$r];
            $tempMap = [];

            foreach ($row as $colIdx => $val) {
                $cellVal = strtolower(trim((string)$val));
                if (empty($cellVal)) continue;

                foreach ($keywords as $field => $kwList) {
                    if (isset($tempMap[$field])) continue;
                    foreach ($kwList as $kw) {
                        if (strpos($cellVal, $kw) !== false) {
                            $tempMap[$field] = $colIdx;
                            break;
                        }
                    }
                }
            }

            if (isset($tempMap['jobdesk']) || (isset($tempMap['posisi']) && isset($tempMap['sasaran_mutu']))) {
                $headerRowIdx = $r;
                $colMap = $tempMap;
                break;
            }
        }

        if ($headerRowIdx === -1) {
            $headerRowIdx = 0;
            $colMap = [
                'no'                     => 0,
                'divisi'                 => 1,
                'posisi'                 => 2,
                'jobdesk'                => 3,
                'detail_proyek'          => 4,
                'implementasi'           => 5,
                'sasaran_mutu'           => 6,
                'metode_pengukuran'      => 7,
                'indikator_keberhasilan' => 8,
                'keterangan'             => 9,
                'link_dokumen'           => 10
            ];
        }

        $items = [];
        $curDivisi = 'UMUM';
        $curPosisi = 'General';
        $seq = 1;

        for ($i = $headerRowIdx + 1; $i < count($sheetData); $i++) {
            $row = $sheetData[$i];
            
            $jobdesk = isset($colMap['jobdesk']) && isset($row[$colMap['jobdesk']]) ? trim((string)$row[$colMap['jobdesk']]) : '';
            if (empty($jobdesk)) {
                continue;
            }

            if (strtolower($jobdesk) === 'jobdesk' || strtolower($jobdesk) === 'job description') {
                continue;
            }

            $divVal = isset($colMap['divisi']) && isset($row[$colMap['divisi']]) ? trim((string)$row[$colMap['divisi']]) : '';
            $posVal = isset($colMap['posisi']) && isset($row[$colMap['posisi']]) ? trim((string)$row[$colMap['posisi']]) : '';

            if (!empty($divVal)) {
                $curDivisi = $divVal;
            }
            if (!empty($posVal)) {
                $curPosisi = $posVal;
            }

            $divKey = preg_replace('/[^a-zA-Z0-9]/', '', strtoupper($curDivisi));
            if (empty($divKey)) $divKey = 'GEN';

            $itemId = "SM_{$divKey}_{$seq}";

            $detailProyek = isset($colMap['detail_proyek']) && isset($row[$colMap['detail_proyek']]) ? trim((string)$row[$colMap['detail_proyek']]) : '';
            $implementasi = isset($colMap['implementasi']) && isset($row[$colMap['implementasi']]) ? trim((string)$row[$colMap['implementasi']]) : '';
            $sasaranMutu  = isset($colMap['sasaran_mutu']) && isset($row[$colMap['sasaran_mutu']]) ? trim((string)$row[$colMap['sasaran_mutu']]) : '';
            $metode       = isset($colMap['metode_pengukuran']) && isset($row[$colMap['metode_pengukuran']]) ? trim((string)$row[$colMap['metode_pengukuran']]) : '';
            $indikator    = isset($colMap['indikator_keberhasilan']) && isset($row[$colMap['indikator_keberhasilan']]) ? trim((string)$row[$colMap['indikator_keberhasilan']]) : '';
            $keterangan   = isset($colMap['keterangan']) && isset($row[$colMap['keterangan']]) ? trim((string)$row[$colMap['keterangan']]) : '';
            $linkDokumen  = isset($colMap['link_dokumen']) && isset($row[$colMap['link_dokumen']]) ? trim((string)$row[$colMap['link_dokumen']]) : '';

            $items[] = [
                'id'                     => $itemId,
                'divisi'                 => $curDivisi,
                'divisi_key'             => $divKey,
                'posisi'                 => $curPosisi,
                'no'                     => $seq,
                'jobdesk'                => $jobdesk,
                'detail_proyek'          => $detailProyek,
                'implementasi'           => $implementasi,
                'sasaran_mutu'           => $sasaranMutu,
                'metode_pengukuran'      => $metode,
                'indikator_keberhasilan' => $indikator,
                'keterangan'             => $keterangan,
                'link_dokumen'           => $linkDokumen
            ];
            $seq++;
        }

        if (empty($items)) {
            return ['status' => 'error', 'message' => 'Tidak ditemukan baris data jobdesk yang valid dari file spreadsheet'];
        }

        $yearFile = APPPATH . "data/sasaran_mutu_master_{$year}.json";
        @file_put_contents($yearFile, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        if ($year == 2026) {
            @file_put_contents($this->masterFilePath, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        self::$masterCache = [];
        self::$overridesCache = [];
        self::$kinerjaCache = [];

        return [
            'status'  => 'success',
            'count'   => count($items),
            'message' => 'Berhasil membaca dan mengimpor ' . count($items) . ' baris sasaran mutu ke tahun ' . $year . '!'
        ];
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
        $divisi  = '';
        if ($this->db->conn_id) {
            $orig_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $u = @$this->db->select('p.jabatan, d.nama as nama_divisi')
                ->from('pengguna p')
                ->join('divisi d', 'p.id_divisi = d.id_divisi', 'left')
                ->where('p.pengguna_id', $userId)
                ->get()->row();
            if ($u) {
                $jabatan = trim((string)$u->jabatan);
                $divisi  = trim((string)$u->nama_divisi);
            }
            $this->db->db_debug = $orig_debug;
        }
        $resolvedPos = $this->resolveUserPosition($jabatan, $divisi);

        $smData = $this->getSasaranMutuData($year);
        $syncedCount = 0;

        foreach ($smData['data'] as $item) {
            $isTarget = false;
            if (!empty($resolvedPos) && strcasecmp($item['posisi'], $resolvedPos) === 0) {
                $isTarget = true;
            } else if (!empty($jabatan) && (strcasecmp($item['posisi'], $jabatan) === 0 || stripos($item['posisi'], $jabatan) !== false || stripos($jabatan, $item['posisi']) !== false)) {
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
     * Mengambil daftar pegawai aktif yang telah dipetakan posisinya untuk modul Audit Kinerja
     * Dilengkapi dengan status audit kinerja karyawan pada tahun bersangkutan
     */
    public function getAuditKinerjaPegawaiList($year = 2026)
    {
        $list = [];
        if (!$this->db->conn_id) {
            return $list;
        }

        $year = (int)$year;
        if ($year < 2020) $year = 2026;

        $orig_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        // Ambil mapping item sasaran mutu per posisi
        $master = $this->getMasterTemplate();
        $posItems = [];
        foreach ($master as $m) {
            $p = $m['posisi'] ?? '';
            if ($p) {
                $posItems[$p][] = $m['id'];
            }
        }

        // Ambil catatan audit kinerja pada tahun tersebut
        $auditRecords = $this->loadAuditKinerjaRecords($year);

        // Ambil pegawai aktif non-sistem
        $query = @$this->db->select('pg.pengguna_id, pg.nama, pg.jabatan, pg.email, d.nama as nama_divisi')
            ->from('pengguna pg')
            ->join('divisi d', 'd.id_divisi = pg.id_divisi', 'left')
            ->where("pg.pengguna_id NOT IN (1, 727, 714, 84, 109, 110, 79, 54, 72, 81, 70, 58, 69, 57, 74, 56, 83, 55, 107, 86, 77, 743)", NULL, FALSE)
            ->where('pg.status', 1)
            ->where('pg.is_active', 1)
            ->order_by('pg.nama', 'ASC')
            ->get();

        if ($query && $query->num_rows() > 0) {
            $tree = $this->getDivisiAndPositions();

            foreach ($query->result_array() as $row) {
                $jabatan = trim((string)$row['jabatan']);
                $divisi  = trim((string)$row['nama_divisi']);
                $resolvedPos = $this->resolveUserPosition($jabatan, $divisi);
                $resolvedDiv = $this->resolveUserDivision($jabatan, $divisi, $resolvedPos);
                $finalPos    = $resolvedPos ?: $jabatan;

                // Hitung status audit kinerja karyawan
                $itemIds = isset($posItems[$finalPos]) ? $posItems[$finalPos] : [];
                $totalItems = count($itemIds);
                $verifiedCount = 0;
                $needEvidenceCount = 0;
                $temuanCount = 0;
                $pendingCount = 0;

                if ($totalItems > 0) {
                    foreach ($itemIds as $itemId) {
                        $rec = isset($auditRecords[$itemId]) ? $auditRecords[$itemId] : [];
                        $st = isset($rec['status_audit']) && !empty($rec['status_audit']) ? $rec['status_audit'] : 'Belum Diaudit';
                        if ($st === 'Terverifikasi / Sesuai') {
                            $verifiedCount++;
                        } else if ($st === 'Perlu Bukti Tambahan') {
                            $needEvidenceCount++;
                        } else if ($st === 'Temuan / Tidak Sesuai') {
                            $temuanCount++;
                        } else {
                            $pendingCount++;
                        }
                    }

                    if ($verifiedCount === $totalItems) {
                        $statusAuditLabel = 'Terverifikasi';
                        $statusAuditBadge = 'badge-success';
                        $statusAuditCode  = 'terverifikasi';
                    } else if ($verifiedCount > 0 || $needEvidenceCount > 0 || $temuanCount > 0) {
                        $statusAuditLabel = $verifiedCount . '/' . $totalItems . ' Terverifikasi';
                        $statusAuditBadge = 'badge-warning';
                        $statusAuditCode  = 'proses';
                    } else {
                        $statusAuditLabel = 'Belum Diaudit (' . $totalItems . ')';
                        $statusAuditBadge = 'badge-secondary';
                        $statusAuditCode  = 'belum';
                    }
                } else {
                    $statusAuditLabel = 'Non-Template';
                    $statusAuditBadge = 'badge-light text-muted border';
                    $statusAuditCode  = 'none';
                }

                $list[] = [
                    'pengguna_id'         => (string)$row['pengguna_id'],
                    'nama'                => $row['nama'],
                    'jabatan'             => $jabatan,
                    'divisi_db'           => $divisi,
                    'posisi'              => $finalPos,
                    'divisi'              => $resolvedDiv,
                    'total_audit'         => $totalItems,
                    'verified_audit'      => $verifiedCount,
                    'need_evidence_audit' => $needEvidenceCount,
                    'temuan_audit'        => $temuanCount,
                    'pending_audit'       => $pendingCount,
                    'status_audit_label'  => $statusAuditLabel,
                    'status_audit_badge'  => $statusAuditBadge,
                    'status_audit_code'   => $statusAuditCode
                ];
            }
        }

        $this->db->db_debug = $orig_debug;
        return $list;
    }

    /**
     * Ambil data Audit Kinerja (jobdesk, detail proyek, implementasi nyata, link dokumen pekerjaan)
     */
    public function getAuditKinerjaData($year = 2026, $divisi = null, $posisi = null, $search = null, $statusAuditFilter = null)
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

            // Filter status audit jika diset
            if (!empty($statusAuditFilter) && $statusAuditFilter !== 'all') {
                if (strcasecmp($statusAudit, $statusAuditFilter) !== 0) {
                    continue;
                }
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
     * Ambil data Audit Keuangan (PBOK dan PPA yang diajukan beserta invoice & lampirannya)
     */
    public function getAuditKeuanganData($year = null, $bulan = null, $status = null, $search = null, $jenisFilter = 'all')
    {
        $records = [];
        $totalNominal = 0;
        $totalDokumen = 0;
        $totalPbok = 0;
        $totalPpa = 0;
        $withInvoice = 0;
        $withoutInvoice = 0;

        // Ambil audit verifikasi keuangan dari DB / JSON
        $keuanganAuditMap = $this->loadAuditKeuanganRecords();

        $loadPbok = ($jenisFilter === 'all' || $jenisFilter === 'pbok');
        $loadPpa  = ($jenisFilter === 'all' || $jenisFilter === 'ppa');

        // 1. DATA SURAT PBOK
        if ($loadPbok && $this->hasTable('surat_pbok')) {
            $orig_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;

            $this->db->select('
                pbok.id_pbok as idSurat,
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
                $idList = array_column($pbokList, 'idSurat');

                // Ambil detail item PBOK
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
                    $idSurat = (int)$pbok['idSurat'];
                    $items = isset($detailsByPbok[$idSurat]) ? $detailsByPbok[$idSurat] : [];

                    $pbokTotal = 0;
                    $itemNames = [];
                    foreach ($items as $it) {
                        $pbokTotal += (float)$it['total'];
                        if (!empty($it['keterangan'])) {
                            $itemNames[] = $it['keterangan'] . ' (' . $it['qty'] . 'x)';
                        }
                    }

                    $hasLampiran = !empty($pbok['lampiran']);
                    $auditKey = 'pbok_' . $idSurat;
                    $auditInfo = isset($keuanganAuditMap[$auditKey]) ? $keuanganAuditMap[$auditKey] : (isset($keuanganAuditMap[$idSurat]) ? $keuanganAuditMap[$idSurat] : []);
                    $statusAudit = isset($auditInfo['status_audit']) ? $auditInfo['status_audit'] : ($hasLampiran ? 'Lampiran Tersedia' : 'Belum Ada Lampiran');
                    $catatanAudit = isset($auditInfo['catatan_audit']) ? $auditInfo['catatan_audit'] : '';
                    $kelengkapanInvoice = isset($auditInfo['kelengkapan_invoice']) ? $auditInfo['kelengkapan_invoice'] : ($hasLampiran ? 'Lengkap' : 'Tidak Ada File');
                    $linkDrive = isset($auditInfo['link_drive']) ? $auditInfo['link_drive'] : '';

                    if ($status && $status !== 'all') {
                        if (strcasecmp($statusAudit, $status) !== 0) {
                            continue;
                        }
                    }

                    if ($hasLampiran) {
                        $withInvoice++;
                    } else {
                        $withoutInvoice++;
                    }

                    $totalNominal += $pbokTotal;
                    $totalPbok++;
                    $totalDokumen++;

                    $records[] = [
                        'jenis'                => 'pbok',
                        'jenis_label'          => 'PBOK',
                        'jenis_badge'          => 'badge-primary',
                        'id_surat'             => $idSurat,
                        'id_pbok'              => $idSurat,
                        'kode_surat'           => $pbok['kode'],
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
                        'stat_persetujuan'     => $pbok['stat_persetujuan'],
                        'status_audit'         => $statusAudit,
                        'kelengkapan_invoice'  => $kelengkapanInvoice,
                        'catatan_audit'        => $catatanAudit,
                        'link_drive'           => $linkDrive,
                        'audited_by_name'      => isset($auditInfo['audited_by_name']) ? $auditInfo['audited_by_name'] : '',
                        'updated_at'           => isset($auditInfo['updated_at']) ? $auditInfo['updated_at'] : '',
                        'detail_url'           => base_url('surat/show/detail_surat/pbok/' . $idSurat . '/1')
                    ];
                }
            }
            $this->db->db_debug = $orig_debug;
        }

        // 2. DATA SURAT PPA
        if ($loadPpa && $this->hasTable('surat_ppa')) {
            $orig_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;

            $this->db->select('
                ppa.id_ppa as idSurat,
                ppa.kode_ppa as kode,
                ppa.tgl_pengajuan,
                ppa.bank,
                ppa.rekening,
                ppa.ats_nama,
                ppa.kota_aju,
                ppa.alamat,
                ppa.pengajuan_ttd_1 as aju_ttd1,
                ppa.ttd_2 as aju_ttd2,
                ppa.ttd_3 as aju_ttd3,
                ppa.lampiran,
                p.pengguna_id,
                p.nama as pengguna,
                p.jabatan as jabatan,
                d.nama as divisi,
                s.status as stat_persetujuan
            ')
            ->from('surat_ppa ppa')
            ->join('surat_list s', 's.id_srt = ppa.id_ppa AND s.id_kat_surat = 7', 'left')
            ->join('pengguna p', 'ppa.id_pengguna = p.pengguna_id', 'left')
            ->join('divisi d', 'p.id_divisi = d.id_divisi', 'left')
            ->order_by('ppa.id_ppa', 'DESC');

            if ($year) {
                $this->db->where('YEAR(ppa.tgl_pengajuan)', (int)$year);
            }
            if ($bulan) {
                $this->db->where('MONTH(ppa.tgl_pengajuan)', (int)$bulan);
            }
            if ($search) {
                $this->db->group_start()
                    ->like('ppa.kode_ppa', $search)
                    ->or_like('p.nama', $search)
                    ->or_like('p.jabatan', $search)
                    ->or_like('ppa.ats_nama', $search)
                    ->group_end();
            }

            $queryPpa = @$this->db->get();
            if ($queryPpa && $queryPpa->num_rows() > 0) {
                $ppaList = $queryPpa->result_array();
                $idListPpa = array_column($ppaList, 'idSurat');

                // Ambil detail item PPA
                $detailsByPpa = [];
                if (!empty($idListPpa) && $this->hasTable('surat_ppa_detail')) {
                    $this->db->select('id, id_ppa, keterangan, estimasi, qty, total')
                        ->from('surat_ppa_detail')
                        ->where_in('id_ppa', $idListPpa)
                        ->order_by('id', 'ASC');
                    $detailQueryPpa = @$this->db->get();
                    if ($detailQueryPpa && $detailQueryPpa->num_rows() > 0) {
                        foreach ($detailQueryPpa->result_array() as $det) {
                            $detailsByPpa[$det['id_ppa']][] = $det;
                        }
                    }
                }

                foreach ($ppaList as $ppa) {
                    $idSurat = (int)$ppa['idSurat'];
                    $items = isset($detailsByPpa[$idSurat]) ? $detailsByPpa[$idSurat] : [];

                    $ppaTotal = 0;
                    $itemNames = [];
                    foreach ($items as $it) {
                        $ppaTotal += (float)$it['total'];
                        if (!empty($it['keterangan'])) {
                            $itemNames[] = $it['keterangan'] . ' (' . $it['qty'] . 'x)';
                        }
                    }

                    $hasLampiran = !empty($ppa['lampiran']);
                    $auditKey = 'ppa_' . $idSurat;
                    $auditInfo = isset($keuanganAuditMap[$auditKey]) ? $keuanganAuditMap[$auditKey] : [];
                    $statusAudit = isset($auditInfo['status_audit']) ? $auditInfo['status_audit'] : ($hasLampiran ? 'Lampiran Tersedia' : 'Belum Ada Lampiran');
                    $catatanAudit = isset($auditInfo['catatan_audit']) ? $auditInfo['catatan_audit'] : '';
                    $kelengkapanInvoice = isset($auditInfo['kelengkapan_invoice']) ? $auditInfo['kelengkapan_invoice'] : ($hasLampiran ? 'Lengkap' : 'Tidak Ada File');
                    $linkDrive = isset($auditInfo['link_drive']) ? $auditInfo['link_drive'] : '';

                    if ($status && $status !== 'all') {
                        if (strcasecmp($statusAudit, $status) !== 0) {
                            continue;
                        }
                    }

                    if ($hasLampiran) {
                        $withInvoice++;
                    } else {
                        $withoutInvoice++;
                    }

                    $totalNominal += $ppaTotal;
                    $totalPpa++;
                    $totalDokumen++;

                    $records[] = [
                        'jenis'                => 'ppa',
                        'jenis_label'          => 'PPA',
                        'jenis_badge'          => 'badge-success',
                        'id_surat'             => $idSurat,
                        'id_pbok'              => $idSurat, // kompatibilitas
                        'kode_surat'           => $ppa['kode'],
                        'kode_pbok'            => $ppa['kode'],
                        'tgl_pengajuan'        => $ppa['tgl_pengajuan'],
                        'pengguna'             => $ppa['pengguna'],
                        'jabatan'              => $ppa['jabatan'],
                        'divisi'               => $ppa['divisi'] ?: 'General',
                        'bank'                 => $ppa['bank'],
                        'rekening'             => $ppa['rekening'],
                        'ats_nama'             => $ppa['ats_nama'],
                        'alamat'               => $ppa['alamat'],
                        'lampiran'             => $ppa['lampiran'],
                        'has_lampiran'         => $hasLampiran,
                        'total_nominal'        => $ppaTotal,
                        'items'                => $items,
                        'ringkasan_barang'     => implode(', ', $itemNames),
                        'stat_persetujuan'     => $ppa['stat_persetujuan'],
                        'status_audit'         => $statusAudit,
                        'kelengkapan_invoice'  => $kelengkapanInvoice,
                        'catatan_audit'        => $catatanAudit,
                        'link_drive'           => $linkDrive,
                        'audited_by_name'      => isset($auditInfo['audited_by_name']) ? $auditInfo['audited_by_name'] : '',
                        'updated_at'           => isset($auditInfo['updated_at']) ? $auditInfo['updated_at'] : '',
                        'detail_url'           => base_url('surat/show/detail_surat/ppa/' . $idSurat . '/1')
                    ];
                }
            }
            $this->db->db_debug = $orig_debug;
        }

        // Urutkan gabungan berdasarkan tanggal pengajuan DESC
        usort($records, function ($a, $b) {
            $tA = !empty($a['tgl_pengajuan']) ? strtotime($a['tgl_pengajuan']) : 0;
            $tB = !empty($b['tgl_pengajuan']) ? strtotime($b['tgl_pengajuan']) : 0;
            if ($tA === $tB) {
                return $b['id_surat'] <=> $a['id_surat'];
            }
            return $tB <=> $tA;
        });

        return [
            'total_dokumen'    => $totalDokumen,
            'total_pbok'       => $totalPbok,
            'total_ppa'        => $totalPpa,
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
                    $j = !empty($r['jenis_surat']) ? strtolower($r['jenis_surat']) : 'pbok';
                    $sid = !empty($r['id_surat']) ? (int)$r['id_surat'] : (int)$r['id_pbok'];
                    $records[$j . '_' . $sid] = $r;
                    $records[$sid] = $r; // fallback
                }
            }
            $this->db->db_debug = $orig;
        }

        if (file_exists($this->keuanganFilePath)) {
            $fileData = json_decode(file_get_contents($this->keuanganFilePath), true);
            if (is_array($fileData)) {
                foreach ($fileData as $key => $r) {
                    if (!isset($records[$key])) {
                        $records[$key] = $r;
                    }
                }
            }
        }

        return $records;
    }

    /**
     * Simpan verifikasi Audit Keuangan PBOK / PPA
     */
    public function saveAuditKeuanganItem($idSurat, $payload, $jenisSurat = 'pbok')
    {
        $idSurat = (int)$idSurat;
        $jenisSurat = strtolower(trim($jenisSurat ?: 'pbok'));
        if (!in_array($jenisSurat, ['pbok', 'ppa'])) {
            $jenisSurat = 'pbok';
        }

        $now = date('Y-m-d H:i:s');
        $userId = sessPenggunaId();
        $userName = sessNama();

        $statusAudit = isset($payload['status_audit']) ? trim($payload['status_audit']) : 'Sesuai & Valid';
        $kelengkapanInvoice = isset($payload['kelengkapan_invoice']) ? trim($payload['kelengkapan_invoice']) : 'Lengkap';
        $catatanAudit = isset($payload['catatan_audit']) ? trim($payload['catatan_audit']) : '';
        $linkDrive = isset($payload['link_drive']) ? trim($payload['link_drive']) : '';

        $record = [
            'id_pbok'              => $idSurat,
            'id_surat'             => $idSurat,
            'jenis_surat'          => $jenisSurat,
            'status_audit'         => $statusAudit,
            'kelengkapan_invoice'  => $kelengkapanInvoice,
            'catatan_audit'        => $catatanAudit,
            'link_drive'           => $linkDrive,
            'audited_by'           => $userId,
            'audited_by_name'      => $userName,
            'updated_at'           => $now
        ];

        // 1. Simpan DB jika tabel tersedia
        if ($this->hasTable('audit_keuangan_records')) {
            $this->ensureAuditKeuanganColumns();
            $orig = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $exist = @$this->db->get_where('audit_keuangan_records', ['jenis_surat' => $jenisSurat, 'id_surat' => $idSurat])->row();
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
        $compositeKey = $jenisSurat . '_' . $idSurat;
        $fileData[$compositeKey] = $record;
        $fileData[$idSurat] = $record;
        @file_put_contents($this->keuanganFilePath, json_encode($fileData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return true;
    }

    /**
     * Cache internal daftar hari libur nasional untuk tahun yang dicek
     */
    private $holidaySetCache = [];

    public function getHolidaysForYear($year)
    {
        $year = (int)$year;
        if (isset($this->holidaySetCache[$year])) {
            return $this->holidaySetCache[$year];
        }

        $holidaySet = [];
        if ($this->hasTable('absensi_config_libur')) {
            $orig = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $rows = @$this->db->query("SELECT tgl FROM absensi_config_libur WHERE tgl >= ? AND tgl <= ?", ["{$year}-01-01", "{$year}-12-31"])->result();
            if ($rows) {
                foreach ($rows as $r) {
                    if (!empty($r->tgl)) {
                        $holidaySet[$r->tgl] = true;
                    }
                }
            }
            $this->db->db_debug = $orig;
        }

        $this->holidaySetCache[$year] = $holidaySet;
        return $holidaySet;
    }

    /**
     * Ambil data Cuti, Sakit, dan Izin resmi karyawan untuk tahun tertentu
     * Sumber:
     * 1. Tabel absensi (type_absen IN ('izin','cuti','sakit') OR status_absen IN ('izin','cuti','sakit'))
     * 2. Tabel surat_cuti_tahunan (status IN (1,2,3))
     * 3. Tabel surat_izin_jam_kerja (status NOT IN (4,5) OR status IS NULL)
     *
     * @return array [ (int)pengguna_id => [ 'YYYY-MM-DD' => 'Cuti' | 'Sakit' | 'Izin' ] ]
     */
    public function getIzinCutiSakitMapForYear($year = 2026, array $userIds = [], array $accountMergeMap = [])
    {
        $year = (int)$year;
        if ($year < 2020) $year = 2026;

        $startDateStr = "{$year}-01-01";
        $endDateStr = "{$year}-12-31";
        $leaveMap = [];

        $orig = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        // 1. Dari tabel absensi
        if ($this->hasTable('absensi')) {
            $this->db->select("pengguna_id, DATE(data_created) as tgl,
                CASE 
                    WHEN status_absen = 'sakit' OR type_absen = 'sakit' THEN 'Sakit'
                    WHEN status_absen = 'cuti' OR type_absen = 'cuti' THEN 'Cuti'
                    WHEN status_absen = 'izin' OR (type_absen = 'izin' AND (status_absen IS NULL OR status_absen = '' OR status_absen = 'izin')) THEN 'Izin'
                    ELSE 'Izin'
                END as kategori", FALSE);
            $this->db->from('absensi');
            $this->db->where('data_created >=', $startDateStr . ' 00:00:00');
            $this->db->where('data_created <=', $endDateStr . ' 23:59:59');
            $this->db->where("(type_absen IN ('izin', 'cuti', 'sakit') OR status_absen IN ('izin', 'cuti', 'sakit'))", NULL, FALSE);
            if (!empty($userIds)) {
                $this->db->where_in('pengguna_id', $userIds);
            }
            $qAbs = @$this->db->get();
            if ($qAbs && $qAbs->num_rows() > 0) {
                foreach ($qAbs->result_array() as $r) {
                    $uid = (int)$r['pengguna_id'];
                    if (isset($accountMergeMap[$uid])) {
                        $uid = (int)$accountMergeMap[$uid];
                    }
                    if (!empty($r['tgl']) && !empty($r['kategori'])) {
                        $leaveMap[$uid][$r['tgl']] = $r['kategori'];
                    }
                }
            }
        }

        // 2. Dari tabel surat_cuti_tahunan
        if ($this->hasTable('surat_cuti_tahunan')) {
            $this->db->select('id_pengaju, tgl_awal, tgl_akhir');
            $this->db->from('surat_cuti_tahunan');
            $this->db->where_in('status', [1, 2, 3]);
            $this->db->where('tgl_awal <=', $endDateStr);
            $this->db->where('tgl_akhir >=', $startDateStr);
            if (!empty($userIds)) {
                $this->db->where_in('id_pengaju', $userIds);
            }
            $qCuti = @$this->db->get();
            if ($qCuti && $qCuti->num_rows() > 0) {
                foreach ($qCuti->result_array() as $r) {
                    $uid = (int)$r['id_pengaju'];
                    if (isset($accountMergeMap[$uid])) {
                        $uid = (int)$accountMergeMap[$uid];
                    }
                    $c = max($startDateStr, $r['tgl_awal']);
                    $e = min($endDateStr, $r['tgl_akhir']);
                    while (strtotime($c) <= strtotime($e)) {
                        $leaveMap[$uid][$c] = 'Cuti';
                        $c = date('Y-m-d', strtotime('+1 day', strtotime($c)));
                    }
                }
            }
        }

        // 3. Dari tabel surat_izin_jam_kerja
        if ($this->hasTable('surat_izin_jam_kerja')) {
            $this->db->select('id_pengaju, jenis_izin, tanggal, tgl_awal, tgl_akhir');
            $this->db->from('surat_izin_jam_kerja');
            $this->db->where("(status NOT IN (4, 5) OR status IS NULL)", NULL, FALSE);
            $this->db->where("((tanggal >= '{$startDateStr}' AND tanggal <= '{$endDateStr}') OR (tgl_awal <= '{$endDateStr}' AND tgl_akhir >= '{$startDateStr}'))", NULL, FALSE);
            if (!empty($userIds)) {
                $this->db->where_in('id_pengaju', $userIds);
            }
            $qIzin = @$this->db->get();
            if ($qIzin && $qIzin->num_rows() > 0) {
                foreach ($qIzin->result_array() as $r) {
                    $uid = (int)$r['id_pengaju'];
                    if (isset($accountMergeMap[$uid])) {
                        $uid = (int)$accountMergeMap[$uid];
                    }
                    $kat = (stripos($r['jenis_izin'] ?? '', 'sakit') !== FALSE) ? 'Sakit' : ((stripos($r['jenis_izin'] ?? '', 'cuti') !== FALSE) ? 'Cuti' : 'Izin');
                    if (!empty($r['tanggal']) && $r['tanggal'] >= $startDateStr && $r['tanggal'] <= $endDateStr) {
                        $leaveMap[$uid][$r['tanggal']] = $kat;
                    }
                    if (!empty($r['tgl_awal']) && !empty($r['tgl_akhir'])) {
                        $c = max($startDateStr, $r['tgl_awal']);
                        $e = min($endDateStr, $r['tgl_akhir']);
                        while (strtotime($c) <= strtotime($e)) {
                            $leaveMap[$uid][$c] = $kat;
                            $c = date('Y-m-d', strtotime('+1 day', strtotime($c)));
                        }
                    }
                }
            }
        }

        $this->db->db_debug = $orig;
        return $leaveMap;
    }

    /**
     * Data kosong default kepatuhan laporan
     */
    public function getEmptyComplianceData($year = 2026)
    {
        return [
            'year'               => (int)$year,
            'total_workdays'     => 0,
            'effective_workdays' => 0,
            'total_points'       => 0,
            'compliance_score'   => 0,
            'count_tepat_waktu'  => 0,
            'count_telat'        => 0,
            'count_tidak_isi'    => 0,
            'count_izin_cuti'    => 0,
            'breakdown'          => []
        ];
    }

    /**
     * Hitung ringkasan kepatuhan pengisian Laporan Mingguan seluruh pegawai secara massal
     * Dioptimalkan dalam satu pass kueri agar loading daftar pegawai cepat
     */
    public function getBulkLaporanMingguanCompliance($year = 2026)
    {
        $year = (int)$year;
        if ($year < 2020) $year = 2026;

        $holidaySet = $this->getHolidaysForYear($year);
        $startDateStr = "{$year}-01-01";
        $todayStr = date('Y-m-d');
        $endOfYearStr = "{$year}-12-31";
        $endDateStr = ($todayStr < $endOfYearStr) ? $todayStr : $endOfYearStr;

        // Ambil mapping akun ganda (merge map) jika ada
        $mergeMap = [];
        if (class_exists('Md_kpi_leaderboard')) {
            $this->load->model('Md_kpi_leaderboard', 'md_kpi_leaderboard', TRUE);
            if (isset($this->md_kpi_leaderboard) && method_exists($this->md_kpi_leaderboard, 'getAccountMergeMap')) {
                $mergeMap = $this->md_kpi_leaderboard->getAccountMergeMap();
            }
        }

        // Ambil data izin/cuti/sakit seluruh karyawan di tahun ini
        $allLeaveMap = $this->getIzinCutiSakitMapForYear($year, [], $mergeMap);

        // Ambil daftar tanggal masuk pertama per karyawan
        $firstMasukMap = [];
        if ($this->hasTable('absensi')) {
            $orig = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $faRows = @$this->db->select('pengguna_id, DATE(MIN(data_created)) as first_masuk')
                ->where('type_absen', 'masuk')
                ->where('data_created >=', "{$year}-01-01 00:00:00")
                ->group_by('pengguna_id')
                ->get('absensi')->result_array();
            if ($faRows) {
                foreach ($faRows as $fa) {
                    $firstMasukMap[(int)$fa['pengguna_id']] = $fa['first_masuk'];
                }
            }
            $this->db->db_debug = $orig;
        }

        // Ambil seluruh laporan tahun tersebut sekaligus
        $allReports = [];
        if ($this->hasTable('laporan')) {
            $orig = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $repRows = @$this->db->select('id, tanggal, id_pengaju, created_at, status')
                ->where('status !=', 3)
                ->where('tanggal >=', "{$year}-01-01")
                ->where('tanggal <=', $endOfYearStr)
                ->get('laporan')->result_array();
            if ($repRows) {
                foreach ($repRows as $r) {
                    $uid = (int)$r['id_pengaju'];
                    if (isset($mergeMap[$uid])) {
                        $uid = (int)$mergeMap[$uid];
                    }
                    $tgl = !empty($r['tanggal']) ? date('Y-m-d', strtotime($r['tanggal'])) : null;
                    if ($tgl) {
                        $allReports[$uid][$tgl][] = $r;
                    }
                }
            }
            $this->db->db_debug = $orig;
        }

        // Generate list hari kerja (Senin - Jumat) di tahun tersebut
        $workdaysList = [];
        $curDate = new DateTime($startDateStr);
        $endDate = new DateTime($endDateStr);

        while ($curDate <= $endDate) {
            $dow = (int)$curDate->format('N');
            $ymd = $curDate->format('Y-m-d');
            if ($dow <= 5 && !isset($holidaySet[$ymd])) {
                $curTs = $curDate->getTimestamp();
                $workdaysList[] = [
                    'ymd'        => $ymd,
                    'monday_str' => date('Y-m-d', strtotime('monday this week', $curTs)),
                    'sunday_str' => date('Y-m-d', strtotime('sunday this week', $curTs))
                ];
            }
            $curDate->modify('+1 day');
        }

        $allUsers = @$this->db->select('pengguna_id')->where('is_active', 1)->get('pengguna')->result_array();
        $results = [];

        foreach ($allUsers as $u) {
            $uid = (int)$u['pengguna_id'];
            $targetUid = isset($mergeMap[$uid]) ? (int)$mergeMap[$uid] : $uid;

            $firstMasuk = isset($firstMasukMap[$targetUid]) ? $firstMasukMap[$targetUid] : (isset($firstMasukMap[$uid]) ? $firstMasukMap[$uid] : null);
            $effectiveStart = ($firstMasuk && $firstMasuk > $startDateStr) ? $firstMasuk : $startDateStr;

            $userReports = isset($allReports[$targetUid]) ? $allReports[$targetUid] : (isset($allReports[$uid]) ? $allReports[$uid] : []);
            $userLeaves = isset($allLeaveMap[$targetUid]) ? $allLeaveMap[$targetUid] : (isset($allLeaveMap[$uid]) ? $allLeaveMap[$uid] : []);

            $totalWorkdays = 0;
            $effectiveWorkdays = 0; // Hari kerja di luar cuti/izin/sakit
            $totalPoints = 0.0;
            $countTepat = 0;
            $countTelat = 0;
            $countTidakIsi = 0;
            $countIzinCuti = 0;

            foreach ($workdaysList as $w) {
                if ($w['ymd'] < $effectiveStart) {
                    continue; // Belum mulai masuk kerja
                }
                $totalWorkdays++;

                $hasReport = isset($userReports[$w['ymd']]) && !empty($userReports[$w['ymd']]);
                $hasLeave = isset($userLeaves[$w['ymd']]);

                if ($hasReport) {
                    $effectiveWorkdays++;
                    $rep = $userReports[$w['ymd']][0];
                    $cr = !empty($rep['created_at']) ? date('Y-m-d', strtotime($rep['created_at'])) : null;
                    $isTelat = (!empty($cr) && ($cr < $w['monday_str'] || $cr > $w['sunday_str']));
                    if ($isTelat) {
                        $countTelat++;
                        $totalPoints += 0.5;
                    } else {
                        $countTepat++;
                        $totalPoints += 1.0;
                    }
                } elseif ($hasLeave) {
                    // OPSI 1: Karyawan sah cuti/sakit/izin dan laporan kosong -> DILEWATI (tidak mengurangi nilai)
                    $countIzinCuti++;
                } else {
                    // Hari kerja aktif tapi tidak mengisi laporan
                    $effectiveWorkdays++;
                    $countTidakIsi++;
                }
            }

            $score = ($effectiveWorkdays > 0) ? round(($totalPoints / $effectiveWorkdays) * 100, 1) : 0.0;

            $results[$uid] = [
                'year'               => $year,
                'total_workdays'     => $totalWorkdays,
                'effective_workdays' => $effectiveWorkdays,
                'total_points'       => $totalPoints,
                'compliance_score'   => $score,
                'count_tepat_waktu'  => $countTepat,
                'count_telat'        => $countTelat,
                'count_tidak_isi'    => $countTidakIsi,
                'count_izin_cuti'    => $countIzinCuti
            ];
        }

        return $results;
    }

    /**
     * Hitung nilai kepatuhan pengisian Laporan Mingguan per karyawan dalam suatu tahun:
     * - Breakdown per hari kerja (Senin-Jumat, di luar libur nasional)
     * - Cuti / Sakit / Izin resmi saat laporan kosong -> OPSI 1: Dilewati / Tidak Mengurangi Nilai
     * - Tepat waktu (diisi dalam minggu berjalan) = 1 poin
     * - Telat (diisi di luar rentang minggu berjalan) = 0.5 poin
     * - Tidak mengisi (hari kerja aktif tanpa izin) = 0 poin
     * - Nilai akhir (%) = (total_poin / effective_workdays) * 100
     */
    public function getLaporanMingguanCompliance($year = 2026, $penggunaId = null)
    {
        $year = (int)$year;
        if ($year < 2020) $year = 2026;

        $penggunaId = (int)$penggunaId;
        if ($penggunaId <= 0) {
            return $this->getEmptyComplianceData($year);
        }

        // Tangani alias/penggabungan akun ganda jika ada
        $userIds = [$penggunaId];
        $accountMergeMap = [];
        if (class_exists('Md_kpi_leaderboard')) {
            $this->load->model('Md_kpi_leaderboard', 'md_kpi_leaderboard', TRUE);
            if (isset($this->md_kpi_leaderboard) && method_exists($this->md_kpi_leaderboard, 'resolveUserIds')) {
                $userIds = $this->md_kpi_leaderboard->resolveUserIds($penggunaId);
            }
            if (isset($this->md_kpi_leaderboard) && method_exists($this->md_kpi_leaderboard, 'getAccountMergeMap')) {
                $accountMergeMap = $this->md_kpi_leaderboard->getAccountMergeMap();
            }
        }

        // Ambil hari libur
        $holidaySet = $this->getHolidaysForYear($year);

        // Ambil data izin/cuti/sakit karyawan
        $userLeaveMapRaw = $this->getIzinCutiSakitMapForYear($year, $userIds, $accountMergeMap);
        $userLeaves = [];
        foreach ($userIds as $uid) {
            if (isset($userLeaveMapRaw[$uid])) {
                foreach ($userLeaveMapRaw[$uid] as $t => $kat) {
                    $userLeaves[$t] = $kat;
                }
            }
        }

        // Rentang penilaian: dari 1 Januari tahun bersangkutan s/d MIN(Hari Ini, 31 Desember tahun itu)
        $startDateStr = "{$year}-01-01";
        $todayStr = date('Y-m-d');
        $endOfYearStr = "{$year}-12-31";
        $endDateStr = ($todayStr < $endOfYearStr) ? $todayStr : $endOfYearStr;

        // Ambil tanggal absen pertama karyawan jika masuk di tengah tahun berjalan
        $firstMasuk = null;
        if ($this->hasTable('absensi')) {
            $orig = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $faRow = @$this->db->select('DATE(MIN(data_created)) as first_masuk')
                ->where_in('pengguna_id', $userIds)
                ->where('type_absen', 'masuk')
                ->where('data_created >=', "{$year}-01-01 00:00:00")
                ->get('absensi')->row();
            if ($faRow && !empty($faRow->first_masuk)) {
                $firstMasuk = $faRow->first_masuk;
            }
            $this->db->db_debug = $orig;
        }

        if ($firstMasuk && $firstMasuk > $startDateStr) {
            $effectiveStart = $firstMasuk;
        } else {
            $effectiveStart = $startDateStr;
        }

        // Ambil seluruh data laporan karyawan pada rentang tahun tersebut
        $laporanRows = [];
        if ($this->hasTable('laporan')) {
            $orig = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $lapQ = @$this->db->select('id, tanggal, id_pengaju, created_at, status, jobdesc, link, ket_hasil')
                ->where_in('id_pengaju', $userIds)
                ->where('status !=', 3)
                ->where('tanggal >=', "{$year}-01-01")
                ->where('tanggal <=', $endOfYearStr)
                ->order_by('tanggal', 'ASC')
                ->order_by('created_at', 'ASC')
                ->get('laporan');
            if ($lapQ && $lapQ->num_rows() > 0) {
                $laporanRows = $lapQ->result_array();
            }
            $this->db->db_debug = $orig;
        }

        // Mapping laporan berdasarkan tanggal kerja Y-m-d
        $laporanByDate = [];
        foreach ($laporanRows as $lap) {
            $tgl = !empty($lap['tanggal']) ? date('Y-m-d', strtotime($lap['tanggal'])) : null;
            if ($tgl) {
                $laporanByDate[$tgl][] = $lap;
            }
        }

        // Loop hari kerja Senin-Jumat dari effectiveStart sampai endDateStr
        $curDate = new DateTime($effectiveStart);
        $endDate = new DateTime($endDateStr);

        $totalWorkdays = 0;
        $effectiveWorkdays = 0;
        $totalPoints = 0.0;
        $countTepat = 0;
        $countTelat = 0;
        $countTidakIsi = 0;
        $countIzinCuti = 0;
        $breakdown = [];

        $hariIndo = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu'
        ];

        while ($curDate <= $endDate) {
            $dow = (int)$curDate->format('N'); // 1 = Senin, 5 = Jumat, 6 = Sabtu, 7 = Minggu
            $ymd = $curDate->format('Y-m-d');
            $dayNameEng = $curDate->format('l');
            $dayName = isset($hariIndo[$dayNameEng]) ? $hariIndo[$dayNameEng] : $dayNameEng;

            // Hanya evaluasi hari kerja (Senin - Jumat) dan bukan libur nasional
            if ($dow <= 5 && !isset($holidaySet[$ymd])) {
                $totalWorkdays++;

                // Hitung rentang minggu kerja untuk tanggal ini (Senin - Minggu)
                $curTs = $curDate->getTimestamp();
                $mondayTs = strtotime('monday this week', $curTs);
                $sundayTs = strtotime('sunday this week', $curTs);
                $mondayStr = date('Y-m-d', $mondayTs);
                $sundayStr = date('Y-m-d', $sundayTs);

                $hasReport = isset($laporanByDate[$ymd]) && !empty($laporanByDate[$ymd]);
                $leaveKategori = isset($userLeaves[$ymd]) ? $userLeaves[$ymd] : null;

                if ($hasReport) {
                    $effectiveWorkdays++;
                    $itemLaporan = $laporanByDate[$ymd][0];
                    $createdAt = !empty($itemLaporan['created_at']) ? date('Y-m-d', strtotime($itemLaporan['created_at'])) : null;

                    // Telat jika diisi di luar rentang minggu (sebelum monday atau sesudah sunday minggu bersangkutan)
                    $isTelat = false;
                    if (!empty($createdAt)) {
                        if ($createdAt < $mondayStr || $createdAt > $sundayStr) {
                            $isTelat = true;
                        }
                    }

                    if ($isTelat) {
                        $point = 0.5;
                        $statusText = 'Telat (Nilai 0.5)';
                        $statusCode = 'telat';
                        $countTelat++;
                    } else {
                        $point = 1.0;
                        $statusText = 'Tepat Waktu (Nilai 1.0)';
                        $statusCode = 'tepat';
                        $countTepat++;
                    }

                    $totalPoints += $point;

                    $breakdown[] = [
                        'tanggal'       => $ymd,
                        'hari'          => $dayName,
                        'status'        => $statusText,
                        'status_code'   => $statusCode,
                        'point'         => $point,
                        'created_at'    => $itemLaporan['created_at'] ?? '-',
                        'rentang_minggu'=> date('d/m', $mondayTs) . ' - ' . date('d/m/Y', $sundayTs),
                        'jobdesc'       => $itemLaporan['jobdesc'] ?? '-',
                        'total_laporan' => count($laporanByDate[$ymd])
                    ];
                } elseif (!empty($leaveKategori)) {
                    // OPSI 1: Karyawan Cuti, Sakit, atau Izin resmi saat laporan kosong -> Dilewati / Dikecualikan
                    $countIzinCuti++;
                    $breakdown[] = [
                        'tanggal'       => $ymd,
                        'hari'          => $dayName,
                        'status'        => "{$leaveKategori} Resmi (Dilewati)",
                        'status_code'   => 'izin_cuti',
                        'point'         => '-',
                        'created_at'    => '-',
                        'rentang_minggu'=> date('d/m', $mondayTs) . ' - ' . date('d/m/Y', $sundayTs),
                        'jobdesc'       => "Izin sah tercatat di absensi / pengajuan ({$leaveKategori}) - Beban kerja dilewati",
                        'total_laporan' => 0
                    ];
                } else {
                    // Tidak mengisi laporan di hari kerja aktif
                    $effectiveWorkdays++;
                    $countTidakIsi++;
                    $breakdown[] = [
                        'tanggal'       => $ymd,
                        'hari'          => $dayName,
                        'status'        => 'Laporan Kosong (Nilai 0)',
                        'status_code'   => 'kosong',
                        'point'         => 0.0,
                        'created_at'    => '-',
                        'rentang_minggu'=> date('d/m', $mondayTs) . ' - ' . date('d/m/Y', $sundayTs),
                        'jobdesc'       => 'Hari kerja aktif tetapi tidak ada laporan yang disubmit',
                        'total_laporan' => 0
                    ];
                }
            }

            $curDate->modify('+1 day');
        }

        $complianceScore = ($effectiveWorkdays > 0) ? round(($totalPoints / $effectiveWorkdays) * 100, 1) : 0.0;

        return [
            'year'               => $year,
            'total_workdays'     => $totalWorkdays,
            'effective_workdays' => $effectiveWorkdays,
            'total_points'       => $totalPoints,
            'compliance_score'   => $complianceScore,
            'count_tepat_waktu'  => $countTepat,
            'count_telat'        => $countTelat,
            'count_tidak_isi'    => $countTidakIsi,
            'count_izin_cuti'    => $countIzinCuti,
            'breakdown'          => $breakdown
        ];
    }
}
