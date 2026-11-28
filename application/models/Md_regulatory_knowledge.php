<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_regulatory_knowledge extends CI_Model
{
    protected $table_update     = 'rkc_regulatory_update';
    protected $table_konsultasi = 'rkc_konsultasi';
    protected $table_guidance   = 'rkc_guidance_document';
    protected static $tablesChecked = false;

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->ensureTables();
    }

    /**
     * Daftar master instansi pemerintah (Government)
     */
    public function getInstansiCatalog()
    {
        return [
            'oss' => [
                'kode'        => 'oss',
                'nama'        => 'OSS',
                'nama_lengkap'=> 'Online Single Submission (BKPM)',
                'deskripsi'   => 'Perizinan berusaha berbasis risiko, NIB, KBLI, regulasi usaha, dan alur konsultasi OSS.',
                'icon'        => 'fas fa-landmark',
                'color'       => '#2563eb',
                'color_soft'  => '#eff6ff',
                'border_soft' => '#bfdbfe',
                'gradient'    => 'linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%)',
            ],
            'kemenkes' => [
                'kode'        => 'kemenkes',
                'nama'        => 'Kemenkes',
                'nama_lengkap'=> 'Kementerian Kesehatan RI',
                'deskripsi'   => 'Regulasi alat kesehatan, izin edar (AKL/AKD), CDAKB, IDAK, dan layanan konsultasi Kemenkes.',
                'icon'        => 'fas fa-hospital-user',
                'color'       => '#059669',
                'color_soft'  => '#ecfdf5',
                'border_soft' => '#a7f3d0',
                'gradient'    => 'linear-gradient(135deg, #047857 0%, #10b981 100%)',
            ],
            'bapeten' => [
                'kode'        => 'bapeten',
                'nama'        => 'BAPETEN',
                'nama_lengkap'=> 'Badan Pengawas Tenaga Nuklir',
                'deskripsi'   => 'Peraturan pengawasan radiasi, izin pemanfaatan X-Ray, uji kesesuaian, dan konsultasi BAPETEN.',
                'icon'        => 'fas fa-radiation',
                'color'       => '#d97706',
                'color_soft'  => '#fffbeb',
                'border_soft' => '#fde68a',
                'gradient'    => 'linear-gradient(135deg, #b45309 0%, #f59e0b 100%)',
            ],
        ];
    }

    public function isValidInstansi($kode)
    {
        $catalog = $this->getInstansiCatalog();
        return isset($catalog[strtolower(trim((string) $kode))]);
    }

    private function _tableExistsDirect($tableName)
    {
        $q = @$this->db->query("SHOW TABLES LIKE " . $this->db->escape($tableName));
        return ($q && $q->num_rows() > 0);
    }

    /**
     * Pastikan tabel Knowledge Center tersedia di database.
     * Data contoh awal hanya di-seed sekali saat tabel baru pertama kali dibuat.
     */
    public function ensureTables()
    {
        if (self::$tablesChecked) {
            return true;
        }

        $db = $this->db;
        if (!$db) {
            return false;
        }
        if (empty($db->conn_id)) {
            $db->initialize();
        }
        if (empty($db->conn_id)) {
            return false;
        }

        $origDebug = $db->db_debug;
        $db->db_debug = FALSE;

        $hadUpdate     = $this->_tableExistsDirect($this->table_update);
        $hadKonsultasi = $this->_tableExistsDirect($this->table_konsultasi);
        $hadGuidance   = $this->_tableExistsDirect($this->table_guidance);

        if (!$hadUpdate) {
            $sql = "CREATE TABLE IF NOT EXISTS `{$this->table_update}` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `instansi` ENUM('oss','kemenkes','bapeten') NOT NULL DEFAULT 'oss',
                `nomor_peraturan` VARCHAR(150) DEFAULT NULL,
                `judul` VARCHAR(255) NOT NULL,
                `tanggal_berlaku` DATE DEFAULT NULL,
                `status_peraturan` ENUM('berlaku','akan_berlaku','revisi','dicabut') NOT NULL DEFAULT 'berlaku',
                `ringkasan` TEXT DEFAULT NULL,
                `file_path` VARCHAR(255) DEFAULT NULL,
                `file_nama_asli` VARCHAR(255) DEFAULT NULL,
                `link_eksternal` VARCHAR(500) DEFAULT NULL,
                `created_by` INT(11) DEFAULT NULL,
                `updated_by` INT(11) DEFAULT NULL,
                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`),
                KEY `idx_rkc_upd_instansi` (`instansi`, `is_deleted`),
                KEY `idx_rkc_upd_tgl` (`tanggal_berlaku`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
            if (@$db->query($sql)) {
                $this->_seedRegulatoryUpdates();
            }
        }

        if (!$hadKonsultasi) {
            $sql = "CREATE TABLE IF NOT EXISTS `{$this->table_konsultasi}` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `instansi` ENUM('oss','kemenkes','bapeten') NOT NULL DEFAULT 'oss',
                `judul` VARCHAR(255) NOT NULL,
                `jenis_layanan` ENUM('offline','online','hybrid') NOT NULL DEFAULT 'offline',
                `estimasi_waktu` VARCHAR(150) DEFAULT NULL,
                `ringkasan` TEXT DEFAULT NULL,
                `catatan_penting` TEXT DEFAULT NULL,
                `langkah_json` LONGTEXT DEFAULT NULL,
                `flowchart_file` VARCHAR(255) DEFAULT NULL,
                `flowchart_nama_asli` VARCHAR(255) DEFAULT NULL,
                `link_eksternal` VARCHAR(500) DEFAULT NULL,
                `urutan` INT(11) NOT NULL DEFAULT 0,
                `status` ENUM('aktif','draft') NOT NULL DEFAULT 'aktif',
                `created_by` INT(11) DEFAULT NULL,
                `updated_by` INT(11) DEFAULT NULL,
                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`),
                KEY `idx_rkc_kon_instansi` (`instansi`, `is_deleted`, `urutan`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
            if (@$db->query($sql)) {
                $this->_seedKonsultasi();
            }
        }

        if (!$hadGuidance) {
            $sql = "CREATE TABLE IF NOT EXISTS `{$this->table_guidance}` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `instansi` ENUM('oss','kemenkes','bapeten') NOT NULL DEFAULT 'oss',
                `judul` VARCHAR(255) NOT NULL,
                `kategori` VARCHAR(120) NOT NULL DEFAULT 'Panduan Umum',
                `versi` VARCHAR(50) NOT NULL DEFAULT 'v1.0',
                `tanggal_dokumen` DATE DEFAULT NULL,
                `deskripsi` TEXT DEFAULT NULL,
                `file_path` VARCHAR(255) DEFAULT NULL,
                `file_nama_asli` VARCHAR(255) DEFAULT NULL,
                `link_eksternal` VARCHAR(500) DEFAULT NULL,
                `created_by` INT(11) DEFAULT NULL,
                `updated_by` INT(11) DEFAULT NULL,
                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`),
                KEY `idx_rkc_gui_instansi` (`instansi`, `is_deleted`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
            if (@$db->query($sql)) {
                $this->_seedGuidanceDocuments();
            }
        }

        $db->db_debug = $origDebug;
        self::$tablesChecked = true;
        return true;
    }

    private function _seedRegulatoryUpdates()
    {
        $now = date('Y-m-d H:i:s');
        $rows = [
            [
                'instansi'         => 'oss',
                'nomor_peraturan'  => 'PP No. 5 Tahun 2021',
                'judul'            => 'Penyelenggaraan Perizinan Berusaha Berbasis Risiko (OSS RBA)',
                'tanggal_berlaku'  => '2021-02-02',
                'status_peraturan' => 'berlaku',
                'ringkasan'        => 'Peraturan Pemerintah mengenai penyelenggaraan perizinan berusaha berbasis risiko melalui sistem OSS RBA, klasifikasi tingkat risiko KBLI, penerbitan NIB, Sertifikat Standar, dan Izin. (Data contoh awal — silakan edit atau perbarui oleh Divisi Regulatory).',
                'link_eksternal'   => 'https://oss.go.id/',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'instansi'         => 'kemenkes',
                'nomor_peraturan'  => 'Permenkes No. 14 Tahun 2021',
                'judul'            => 'Standar Kegiatan Usaha dan Produk pada Penyelenggaraan Perizinan Berusaha Berbasis Risiko Sektor Kesehatan',
                'tanggal_berlaku'  => '2021-04-01',
                'status_peraturan' => 'berlaku',
                'ringkasan'        => 'Pedoman standar kegiatan usaha sektor kesehatan termasuk distribusi alat kesehatan (IDAK), sertifikasi CDAKB, serta persyaratan teknis izin edar AKL/AKD. (Data contoh awal — silakan edit atau perbarui oleh Divisi Regulatory).',
                'link_eksternal'   => 'https://regalkes.kemkes.go.id/',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'instansi'         => 'bapeten',
                'nomor_peraturan'  => 'Perba BAPETEN No. 3 Tahun 2021',
                'judul'            => 'Standar Kegiatan Usaha dan Standar Produk pada Penyelenggaraan Perizinan Berusaha Berbasis Risiko Sektor Ketenaganukliran',
                'tanggal_berlaku'  => '2021-04-01',
                'status_peraturan' => 'berlaku',
                'ringkasan'        => 'Mengatur standar perizinan pemanfaatan sumber radiasi pengion (termasuk pesawat sinar-X radiologi diagnostik/intervensional), persyaratan PPR, serta uji kesesuaian. (Data contoh awal — silakan edit atau perbarui oleh Divisi Regulatory).',
                'link_eksternal'   => 'https://balis.bapeten.go.id/',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ];

        foreach ($rows as $r) {
            @$this->db->insert($this->table_update, $r);
        }
    }

    private function _seedKonsultasi()
    {
        $now = date('Y-m-d H:i:s');

        $ossOfflineSteps = [
            [
                'judul'     => 'Ambil Ticketing di Sistem OSS',
                'deskripsi' => 'Pemohon wajib mengambil nomor antrean / ticketing layanan konsultasi terlebih dahulu melalui sistem OSS (semua kanal ticketing OSS) sesuai topik kendala/perizinan.',
                'estimasi'  => 'Hari H (Misal: Selasa)',
                'tipe'      => 'start',
            ],
            [
                'judul'     => 'Pemrosesan & Verifikasi Ticketing oleh OSS',
                'deskripsi' => 'Permohonan ticketing diproses oleh petugas/sistem OSS untuk konfirmasi kelengkapan data awal dan penetapan jadwal konsultasi tatap muka.',
                'estimasi'  => 'Proses Verifikasi',
                'tipe'      => 'process',
            ],
            [
                'judul'     => 'Masa Berlaku Tiket di Minggu Berikutnya',
                'deskripsi' => 'Setelah ticketing selesai diproses, jadwal berlaku pada minggu berikutnya. Contoh: jika ticketing diambil pada hari Selasa, maka tiket baru berlaku untuk konsultasi tatap muka pada hari Selasa minggu depan.',
                'estimasi'  => 'H+7 (Minggu Berikutnya)',
                'tipe'      => 'wait',
            ],
            [
                'judul'     => 'Pelaksanaan Konsultasi Tatap Muka (Offline)',
                'deskripsi' => 'Hadir sesuai jadwal minggu berikutnya di loket konsultasi OSS dengan membawa bukti tiket yang sudah terverifikasi, NIB/dokumen legalitas perusahaan, dan daftar pertanyaan teknis.',
                'estimasi'  => 'Sesuai Jadwal Tiket',
                'tipe'      => 'finish',
            ],
        ];

        $kemenkesSteps = [
            [
                'judul'     => 'Identifikasi Topik & Siapkan Berkas Produk',
                'deskripsi' => 'Siapkan dokumen teknis produk (LoA, CFS, ISO 13485, brosur, atau nomor aju Regalkes/IDAK) serta ringkasan pertanyaan regulasi.',
                'estimasi'  => 'Pra-Konsultasi',
                'tipe'      => 'start',
            ],
            [
                'judul'     => 'Pendaftaran Antrean Layanan Konsultasi Kemenkes',
                'deskripsi' => 'Daftar jadwal konsultasi melalui kanal resmi layanan konsultasi Ditjen Farmalkes / Regalkes sesuai jadwal operasional layanan.',
                'estimasi'  => 'Pendaftaran Antrean',
                'tipe'      => 'process',
            ],
            [
                'judul'     => 'Sesi Konsultasi & Pencatatan Arahan Evaluator',
                'deskripsi' => 'Ikuti sesi konsultasi sesuai jadwal dan dokumentasikan seluruh masukan/arahan evaluator sebagai acuan tindak lanjut pengajuan.',
                'estimasi'  => 'Selesai & Tindak Lanjut',
                'tipe'      => 'finish',
            ],
        ];

        $bapetenSteps = [
            [
                'judul'     => 'Siapkan Data Fasilitas & Nomor Registrasi Balis',
                'deskripsi' => 'Lengkapi informasi izin pemanfaatan, data pesawat sinar-X, hasil uji kesesuaian (LUK), serta status evaluasi di sistem Balis Online.',
                'estimasi'  => 'Pra-Konsultasi',
                'tipe'      => 'start',
            ],
            [
                'judul'     => 'Pengajuan Jadwal Konsultasi Layanan Perizinan BAPETEN',
                'deskripsi' => 'Ajukan permintaan konsultasi melalui kanal resmi layanan konsultasi perizinan BAPETEN (online/offline) sesuai direktorat terkait.',
                'estimasi'  => 'Penjadwalan',
                'tipe'      => 'process',
            ],
            [
                'judul'     => 'Pelaksanaan Konsultasi & Evaluasi Kelengkapan',
                'deskripsi' => 'Lakukan pembahasan kendala teknis/dokumen dengan evaluator BAPETEN dan catat daftar perbaikan untuk pemenuhan persyaratan izin.',
                'estimasi'  => 'Selesai',
                'tipe'      => 'finish',
            ],
        ];

        $rows = [
            [
                'instansi'        => 'oss',
                'judul'           => 'Alur Konsultasi Offline (Tatap Muka) OSS',
                'jenis_layanan'   => 'offline',
                'estimasi_waktu'  => 'Berlaku Minggu Berikutnya (H+7)',
                'ringkasan'       => 'Prosedur standar pengambilan ticketing hingga pelaksanaan konsultasi tatap muka (offline) dengan petugas OSS.',
                'catatan_penting' => 'Tiketing wajib diambil terlebih dahulu di sistem OSS. Setelah diproses, tiket berlaku di minggu berikutnya — misalnya diambil hari Selasa, maka konsultasi tatap muka dilaksanakan pada hari Selasa minggu depan.',
                'langkah_json'    => json_encode($ossOfflineSteps, JSON_UNESCAPED_UNICODE),
                'urutan'          => 1,
                'status'          => 'aktif',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'instansi'        => 'kemenkes',
                'judul'           => 'Alur Konsultasi Layanan Perizinan Alkes (Kemenkes)',
                'jenis_layanan'   => 'hybrid',
                'estimasi_waktu'  => 'Sesuai Jadwal Antrean Layanan',
                'ringkasan'       => 'Alur ringkas konsultasi terkait registrasi izin edar alat kesehatan (AKL/AKD), IDAK, dan CDAKB. (Contoh awal — dapat disesuaikan oleh Divisi Regulatory).',
                'catatan_penting' => 'Pastikan nomor aju atau dokumen spesifikasi teknis produk telah disiapkan sebelum jadwal konsultasi dimulai.',
                'langkah_json'    => json_encode($kemenkesSteps, JSON_UNESCAPED_UNICODE),
                'urutan'          => 1,
                'status'          => 'aktif',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'instansi'        => 'bapeten',
                'judul'           => 'Alur Konsultasi Perizinan Pemanfaatan Radiasi (BAPETEN)',
                'jenis_layanan'   => 'hybrid',
                'estimasi_waktu'  => 'Sesuai Jadwal Evaluator',
                'ringkasan'       => 'Alur ringkas konsultasi perizinan pemanfaatan sumber radiasi pengion / pesawat sinar-X di BAPETEN. (Contoh awal — dapat disesuaikan oleh Divisi Regulatory).',
                'catatan_penting' => 'Sertakan nomor registrasi Balis Online dan dokumen teknis PPR/LUK saat melakukan konsultasi.',
                'langkah_json'    => json_encode($bapetenSteps, JSON_UNESCAPED_UNICODE),
                'urutan'          => 1,
                'status'          => 'aktif',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];

        foreach ($rows as $r) {
            @$this->db->insert($this->table_konsultasi, $r);
        }
    }

    private function _seedGuidanceDocuments()
    {
        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');
        $rows = [
            [
                'instansi'        => 'oss',
                'judul'           => 'Panduan Perubahan & Penambahan KBLI pada Sistem OSS RBA',
                'kategori'        => 'KBLI & NIB',
                'versi'           => 'v1.0',
                'tanggal_dokumen' => $today,
                'deskripsi'       => 'Dokumen panduan langkah demi langkah proses perubahan data usaha dan penambahan kode KBLI di portal OSS RBA, termasuk penyesuaian akta/AHU dan pemenuhan persyaratan dasar. (Silakan upload file PDF panduan terbaru melalui tombol Edit).',
                'link_eksternal'  => 'https://oss.go.id/panduan',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'instansi'        => 'kemenkes',
                'judul'           => 'Panduan Kelengkapan Berkas Registrasi Izin Edar Alat Kesehatan',
                'kategori'        => 'Izin Edar Alkes',
                'versi'           => 'v1.0',
                'tanggal_dokumen' => $today,
                'deskripsi'       => 'Checklist dan panduan teknis penyusunan berkas registrasi izin edar alat kesehatan (AKL/AKD) melalui sistem Regalkes Kemenkes. (Silakan upload file panduan resmi oleh Divisi Regulatory).',
                'link_eksternal'  => 'https://regalkes.kemkes.go.id/',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'instansi'        => 'bapeten',
                'judul'           => 'Panduan Pengajuan Izin Pemanfaatan Pesawat Sinar-X (Balis Online)',
                'kategori'        => 'Perizinan Radiasi',
                'versi'           => 'v1.0',
                'tanggal_dokumen' => $today,
                'deskripsi'       => 'Panduan teknis tahapan pengajuan izin baru maupun perpanjangan pemanfaatan pesawat sinar-X melalui Balis Online BAPETEN. (Silakan upload file panduan terbaru oleh Divisi Regulatory).',
                'link_eksternal'  => 'https://balis.bapeten.go.id/',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];

        foreach ($rows as $r) {
            @$this->db->insert($this->table_guidance, $r);
        }
    }

    /**
     * Ringkasan jumlah item per instansi untuk badges & cards
     */
    public function getCountsByInstansi($includeDraft = false)
    {
        $counts = [];
        foreach (array_keys($this->getInstansiCatalog()) as $kode) {
            $counts[$kode] = [
                'updates'    => 0,
                'konsultasi' => 0,
                'guidance'   => 0,
                'total'      => 0,
            ];
        }

        $q1 = $this->db->select('instansi, COUNT(*) AS cnt')
            ->from($this->table_update)
            ->where('is_deleted', 0)
            ->group_by('instansi')
            ->get()->result();
        foreach ($q1 as $r) {
            if (isset($counts[$r->instansi])) {
                $counts[$r->instansi]['updates'] = (int) $r->cnt;
            }
        }

        $this->db->select('instansi, COUNT(*) AS cnt')
            ->from($this->table_konsultasi)
            ->where('is_deleted', 0);
        if (!$includeDraft) {
            $this->db->where('status', 'aktif');
        }
        $q2 = $this->db->group_by('instansi')->get()->result();
        foreach ($q2 as $r) {
            if (isset($counts[$r->instansi])) {
                $counts[$r->instansi]['konsultasi'] = (int) $r->cnt;
            }
        }

        $q3 = $this->db->select('instansi, COUNT(*) AS cnt')
            ->from($this->table_guidance)
            ->where('is_deleted', 0)
            ->group_by('instansi')
            ->get()->result();
        foreach ($q3 as $r) {
            if (isset($counts[$r->instansi])) {
                $counts[$r->instansi]['guidance'] = (int) $r->cnt;
            }
        }

        foreach ($counts as $k => &$c) {
            $c['total'] = $c['updates'] + $c['konsultasi'] + $c['guidance'];
        }
        unset($c);

        return $counts;
    }

    /* =========================================================================
       1. REGULATORY UPDATE
       ========================================================================= */

    public function getUpdates($instansi)
    {
        return $this->db->select('u.*, p1.nama AS created_by_nama, p2.nama AS updated_by_nama')
            ->from($this->table_update . ' u')
            ->join('pengguna p1', 'p1.pengguna_id = u.created_by', 'left')
            ->join('pengguna p2', 'p2.pengguna_id = u.updated_by', 'left')
            ->where('u.instansi', $instansi)
            ->where('u.is_deleted', 0)
            ->order_by('u.tanggal_berlaku', 'DESC')
            ->order_by('u.id', 'DESC')
            ->get()
            ->result_array();
    }

    public function getUpdateById($id)
    {
        return $this->db->select('u.*, p1.nama AS created_by_nama, p2.nama AS updated_by_nama')
            ->from($this->table_update . ' u')
            ->join('pengguna p1', 'p1.pengguna_id = u.created_by', 'left')
            ->join('pengguna p2', 'p2.pengguna_id = u.updated_by', 'left')
            ->where('u.id', (int) $id)
            ->where('u.is_deleted', 0)
            ->get()
            ->row_array();
    }

    public function insertUpdate($data)
    {
        $this->db->insert($this->table_update, $data);
        return (int) $this->db->insert_id();
    }

    public function updateRegulatory($id, $data)
    {
        return $this->db->where('id', (int) $id)->update($this->table_update, $data);
    }

    public function deleteUpdate($id, $userId = null)
    {
        return $this->db->where('id', (int) $id)->update($this->table_update, [
            'is_deleted' => 1,
            'updated_by' => $userId,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /* =========================================================================
       2. KONSULTASI (FLOWCHART)
       ========================================================================= */

    public function getKonsultasi($instansi, $includeDraft = false)
    {
        $this->db->select('k.*, p1.nama AS created_by_nama, p2.nama AS updated_by_nama')
            ->from($this->table_konsultasi . ' k')
            ->join('pengguna p1', 'p1.pengguna_id = k.created_by', 'left')
            ->join('pengguna p2', 'p2.pengguna_id = k.updated_by', 'left')
            ->where('k.instansi', $instansi)
            ->where('k.is_deleted', 0);

        if (!$includeDraft) {
            $this->db->where('k.status', 'aktif');
        }

        $rows = $this->db->order_by('k.urutan', 'ASC')
            ->order_by('k.id', 'ASC')
            ->get()
            ->result_array();

        foreach ($rows as &$row) {
            $steps = json_decode((string) ($row['langkah_json'] ?? ''), true);
            $row['langkah'] = is_array($steps) ? $steps : [];
        }
        unset($row);

        return $rows;
    }

    public function getKonsultasiById($id)
    {
        $row = $this->db->select('k.*, p1.nama AS created_by_nama, p2.nama AS updated_by_nama')
            ->from($this->table_konsultasi . ' k')
            ->join('pengguna p1', 'p1.pengguna_id = k.created_by', 'left')
            ->join('pengguna p2', 'p2.pengguna_id = k.updated_by', 'left')
            ->where('k.id', (int) $id)
            ->where('k.is_deleted', 0)
            ->get()
            ->row_array();

        if ($row) {
            $steps = json_decode((string) ($row['langkah_json'] ?? ''), true);
            $row['langkah'] = is_array($steps) ? $steps : [];
        }

        return $row;
    }

    public function insertKonsultasi($data)
    {
        $this->db->insert($this->table_konsultasi, $data);
        return (int) $this->db->insert_id();
    }

    public function updateKonsultasi($id, $data)
    {
        return $this->db->where('id', (int) $id)->update($this->table_konsultasi, $data);
    }

    public function deleteKonsultasi($id, $userId = null)
    {
        return $this->db->where('id', (int) $id)->update($this->table_konsultasi, [
            'is_deleted' => 1,
            'updated_by' => $userId,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /* =========================================================================
       3. GUIDANCE DOCUMENT
       ========================================================================= */

    public function getGuidance($instansi)
    {
        return $this->db->select('g.*, p1.nama AS created_by_nama, p2.nama AS updated_by_nama')
            ->from($this->table_guidance . ' g')
            ->join('pengguna p1', 'p1.pengguna_id = g.created_by', 'left')
            ->join('pengguna p2', 'p2.pengguna_id = g.updated_by', 'left')
            ->where('g.instansi', $instansi)
            ->where('g.is_deleted', 0)
            ->order_by('g.tanggal_dokumen', 'DESC')
            ->order_by('g.id', 'DESC')
            ->get()
            ->result_array();
    }

    public function getGuidanceById($id)
    {
        return $this->db->select('g.*, p1.nama AS created_by_nama, p2.nama AS updated_by_nama')
            ->from($this->table_guidance . ' g')
            ->join('pengguna p1', 'p1.pengguna_id = g.created_by', 'left')
            ->join('pengguna p2', 'p2.pengguna_id = g.updated_by', 'left')
            ->where('g.id', (int) $id)
            ->where('g.is_deleted', 0)
            ->get()
            ->row_array();
    }

    public function insertGuidance($data)
    {
        $this->db->insert($this->table_guidance, $data);
        return (int) $this->db->insert_id();
    }

    public function updateGuidance($id, $data)
    {
        return $this->db->where('id', (int) $id)->update($this->table_guidance, $data);
    }

    public function deleteGuidance($id, $userId = null)
    {
        return $this->db->where('id', (int) $id)->update($this->table_guidance, [
            'is_deleted' => 1,
            'updated_by' => $userId,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
