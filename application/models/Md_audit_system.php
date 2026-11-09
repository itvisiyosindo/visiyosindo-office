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
     * Inisialisasi tabel pendukung jika belum ada
     */
    public function initTables()
    {
        if (!$this->db->conn_id) {
            return;
        }

        // Tabel untuk override / pengisian Sasaran Mutu tahunan langsung di web
        $this->db->query("CREATE TABLE IF NOT EXISTS sasaran_mutu_records (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Tabel untuk Audit Kinerja & link dokumen pekerjaan
        $this->db->query("CREATE TABLE IF NOT EXISTS audit_kinerja_records (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Tabel untuk Audit Keuangan (verifikasi PBOK & Invoice)
        $this->db->query("CREATE TABLE IF NOT EXISTS audit_keuangan_records (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_pbok INT NOT NULL,
            status_audit VARCHAR(50) DEFAULT 'Belum Diverifikasi',
            catatan_audit TEXT,
            kelengkapan_invoice VARCHAR(50) DEFAULT 'Lengkap',
            audited_by INT,
            audited_by_name VARCHAR(150),
            updated_at DATETIME,
            UNIQUE KEY uq_pbok (id_pbok)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    /**
     * Ambil data master template 2026 dari JSON
     */
    public function getMasterTemplate()
    {
        if (file_exists($this->masterFilePath)) {
            $json = file_get_contents($this->masterFilePath);
            $data = json_decode($json, true);
            if (is_array($data)) {
                return $data;
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
     * - Merge dengan catatan/inputan yang tersimpan dari DB / File JSON.
     */
    public function getSasaranMutuData($year = 2026, $divisi = null, $posisi = null, $search = null)
    {
        $year = (int)$year;
        if ($year < 2020) $year = 2026;

        $master = $this->getMasterTemplate();
        $overrides = $this->loadSasaranMutuOverrides($year);
        $kinerjaRecords = $this->loadAuditKinerjaRecords($year);

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

                if ($divisi && $divisi !== 'all' && strcasecmp($itemDiv, $divisi) !== 0) {
                    continue;
                }
                if ($posisi && $posisi !== 'all' && strcasecmp($itemPos, $posisi) !== 0) {
                    continue;
                }

                $row = [
                    'id'                     => $ovId,
                    'year'                   => $year,
                    'divisi'                 => $itemDiv,
                    'divisi_key'             => str_replace([' ', ','], '_', $itemDiv),
                    'posisi'                 => $itemPos,
                    'no'                     => isset($ov['no']) ? $ov['no'] : '',
                    'jobdesk'                => isset($ov['jobdesk']) ? $ov['jobdesk'] : '',
                    'detail_proyek'          => isset($ov['detail_proyek']) ? $ov['detail_proyek'] : '',
                    'implementasi'           => isset($ov['implementasi']) ? $ov['implementasi'] : '',
                    'sasaran_mutu'           => isset($ov['sasaran_mutu']) ? $ov['sasaran_mutu'] : '',
                    'metode_pengukuran'      => isset($ov['metode_pengukuran']) ? $ov['metode_pengukuran'] : '',
                    'indikator_keberhasilan' => isset($ov['indikator_keberhasilan']) ? $ov['indikator_keberhasilan'] : '',
                    'keterangan'             => isset($ov['keterangan']) ? $ov['keterangan'] : '',
                    'link_dokumen'           => isset($kinerjaRecords[$ovId]['link_dokumen']) ? $kinerjaRecords[$ovId]['link_dokumen'] : '',
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
     * Ambil catatan override Sasaran Mutu (dari DB atau JSON)
     */
    private function loadSasaranMutuOverrides($year)
    {
        $overrides = [];

        // 1. Coba baca dari database
        if ($this->db->conn_id) {
            $q = $this->db->get_where('sasaran_mutu_records', ['year' => $year]);
            if ($q && $q->num_rows() > 0) {
                foreach ($q->result_array() as $r) {
                    $overrides[$r['item_id']] = $r;
                }
            }
        }

        // 2. Baca dari JSON fallback jika ada
        if (file_exists($this->overridesFilePath)) {
            $fileData = json_decode(file_get_contents($this->overridesFilePath), true);
            if (isset($fileData[$year]) && is_array($fileData[$year])) {
                foreach ($fileData[$year] as $itemId => $r) {
                    if (!isset($overrides[$itemId])) {
                        $overrides[$itemId] = $r;
                    }
                }
            }
        }

        return $overrides;
    }

    /**
     * Simpan / update item Sasaran Mutu langsung dari sistem Office
     */
    public function saveSasaranMutuItem($year, $itemId, $payload)
    {
        $year = (int)$year;
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

        // 1. Simpan ke database
        if ($this->db->conn_id) {
            $exist = $this->db->get_where('sasaran_mutu_records', ['year' => $year, 'item_id' => $itemId])->row();
            if ($exist) {
                $this->db->where('id', $exist->id)->update('sasaran_mutu_records', $record);
            } else {
                $this->db->insert('sasaran_mutu_records', $record);
            }
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
     * Ambil data Audit Kinerja (jobdesk, detail proyek, implementasi nyata, link dokumen pekerjaan)
     */
    public function getAuditKinerjaData($year = 2026, $divisi = null, $posisi = null, $search = null)
    {
        $smResult = $this->getSasaranMutuData($year, $divisi, $posisi, $search);
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
        $records = [];
        if ($this->db->conn_id) {
            $q = $this->db->get_where('audit_kinerja_records', ['year' => $year]);
            if ($q && $q->num_rows() > 0) {
                foreach ($q->result_array() as $r) {
                    $records[$r['item_id']] = $r;
                }
            }
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

        return $records;
    }

    /**
     * Simpan / update data Audit Kinerja (link dokumen & verifikasi)
     */
    public function saveAuditKinerjaItem($year, $itemId, $payload)
    {
        $year = (int)$year;
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

        // 1. Simpan DB
        if ($this->db->conn_id) {
            $exist = $this->db->get_where('audit_kinerja_records', ['year' => $year, 'item_id' => $itemId])->row();
            if ($exist) {
                $this->db->where('id', $exist->id)->update('audit_kinerja_records', $record);
            } else {
                $this->db->insert('audit_kinerja_records', $record);
            }
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

        if ($this->db->conn_id) {
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

            $query = $this->db->get();
            if ($query && $query->num_rows() > 0) {
                $pbokList = $query->result_array();
                $idList = array_column($pbokList, 'idPbok');

                // Ambil seluruh detail item PBOK yang ada
                $detailsByPbok = [];
                if (!empty($idList)) {
                    $this->db->select('id, id_pbok, kode_detail, keterangan, estimasi, qty, total, catatan, mata_uang')
                        ->from('surat_pbok_detail')
                        ->where_in('id_pbok', $idList)
                        ->order_by('id', 'ASC');
                    $detailQuery = $this->db->get();
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
        if ($this->db->conn_id) {
            $q = $this->db->get('audit_keuangan_records');
            if ($q && $q->num_rows() > 0) {
                foreach ($q->result_array() as $r) {
                    $records[(int)$r['id_pbok']] = $r;
                }
            }
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

        // 1. Simpan DB
        if ($this->db->conn_id) {
            $exist = $this->db->get_where('audit_keuangan_records', ['id_pbok' => $idPbok])->row();
            if ($exist) {
                $this->db->where('id', $exist->id)->update('audit_keuangan_records', $record);
            } else {
                $this->db->insert('audit_keuangan_records', $record);
            }
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
