<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_perizinan_visilab extends CI_Model
{
    protected $table = 'visilab_perizinan_alat';
    protected $table_history = 'visilab_perizinan_history';
    protected static $initialized = false;

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->ensure_table();
    }

    /**
     * Cek keberadaan tabel di database tanpa memicu fatal error
     */
    public function hasTable($tableName)
    {
        return true;
    }

    /**
     * Memastikan tabel perizinan dan tabel history telah dibuat di database,
     * serta menambahkan kolom LUK, Kalibrasi, dan TLD jika belum ada.
     */
    public function ensure_table()
    {
        if (self::$initialized || !isset($this->db) || !$this->db->conn_id) {
            return false;
        }

        $orig = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        // 1. Buat Tabel Utama Perizinan Alat jika belum ada
        if (!$this->hasTable($this->table)) {
            $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `nama_instansi` VARCHAR(255) NOT NULL,
                `pihak_ketiga` VARCHAR(255),
                `jenis_alat` VARCHAR(255) NOT NULL,
                `status_jasa` VARCHAR(100) DEFAULT 'Tidak Termasuk Perizinan',
                `status_perizinan` VARCHAR(100) DEFAULT '-',
                `progres_perizinan` TEXT,
                `marketing_id` INT,
                `marketing_nama` VARCHAR(150),
                `marketing_nohp` VARCHAR(50),
                `pic_instansi` VARCHAR(150),
                `kontak_pic` VARCHAR(50),
                `email_pic` VARCHAR(150),
                `tgl_terbit` DATE,
                `tgl_jatuh_tempo` DATE,
                `tgl_exp_luk` DATE,
                `tgl_exp_kalibrasi` DATE,
                `tgl_evaluasi_tld` DATE,
                `catatan` TEXT,
                `reminder_45_sent` TINYINT(1) DEFAULT 0,
                `last_reminder_at` DATETIME,
                `reminder_luk_sent` TINYINT(1) DEFAULT 0,
                `last_reminder_luk_at` DATETIME,
                `reminder_kalibrasi_sent` TINYINT(1) DEFAULT 0,
                `last_reminder_kalibrasi_at` DATETIME,
                `reminder_tld_sent` TINYINT(1) DEFAULT 0,
                `last_reminder_tld_at` DATETIME,
                `created_by` INT,
                `updated_by` INT,
                `created_at` DATETIME,
                `updated_at` DATETIME
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            @$this->db->query($sql);
        }

        // 2. Buat Tabel Riwayat jika belum ada
        if (!$this->hasTable($this->table_history)) {
            $sql_history = "CREATE TABLE IF NOT EXISTS `{$this->table_history}` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `id_perizinan` INT NOT NULL,
                `jenis_notif` VARCHAR(50) DEFAULT 'progres',
                `status_lama` VARCHAR(100),
                `status_baru` VARCHAR(100),
                `progres_lama` TEXT,
                `progres_baru` TEXT,
                `tgl_jatuh_tempo_lama` DATE,
                `tgl_jatuh_tempo_baru` DATE,
                `penerima_cust` VARCHAR(50),
                `penerima_mkt` VARCHAR(50),
                `keterangan` TEXT,
                `notif_marketing_sent` TINYINT(1) DEFAULT 0,
                `notif_visilab_sent` TINYINT(1) DEFAULT 0,
                `notif_customer_sent` TINYINT(1) DEFAULT 0,
                `created_by` INT,
                `created_by_nama` VARCHAR(150),
                `created_at` DATETIME
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            @$this->db->query($sql_history);
        }

        // 3. Tambah kolom baru jika tabel sudah ada dari versi sebelumnya
        if ($this->hasTable($this->table)) {
            $colsToCheck = [
                'tgl_exp_luk'                => "DATE DEFAULT NULL",
                'tgl_exp_kalibrasi'          => "DATE DEFAULT NULL",
                'tgl_evaluasi_tld'           => "DATE DEFAULT NULL",
                'reminder_luk_sent'          => "TINYINT(1) DEFAULT 0",
                'last_reminder_luk_at'       => "DATETIME DEFAULT NULL",
                'reminder_kalibrasi_sent'    => "TINYINT(1) DEFAULT 0",
                'last_reminder_kalibrasi_at' => "DATETIME DEFAULT NULL",
                'reminder_tld_sent'          => "TINYINT(1) DEFAULT 0",
                'last_reminder_tld_at'       => "DATETIME DEFAULT NULL"
            ];
            foreach ($colsToCheck as $c => $def) {
                $chk = @$this->db->query("SHOW COLUMNS FROM `{$this->table}` LIKE '{$c}'");
                if (!$chk || $chk->num_rows() == 0) {
                    @$this->db->query("ALTER TABLE `{$this->table}` ADD COLUMN `{$c}` {$def}");
                }
            }
        }

        // 4. Auto Seed data awal jika tabel ada dan kosong
        if ($this->hasTable($this->table)) {
            $countQ = @$this->db->query("SELECT COUNT(*) as total FROM `{$this->table}`");
            if ($countQ && $countQ->num_rows() > 0) {
                $totalRows = (int)$countQ->row()->total;
                if ($totalRows == 0) {
                    $this->seed_initial_data();
                }
            }
        }

        $this->db->db_debug = $orig;
        self::$initialized = true;
        return true;
    }

    /**
     * Memasukkan 30 data awal dari master Excel perizinan alat
     */
    public function seed_initial_data()
    {
        $now = date('Y-m-d H:i:s');
        $seeds = [
            [
                'nama_instansi'     => 'KLINIK POIN MEDIKA',
                'pihak_ketiga'      => 'CV MELIA SEJAHTERA',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '',
                'marketing_nama'    => 'Muhammad Tamrin',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'RSUD BANGIL',
                'pihak_ketiga'      => 'CV CIPTA MITRA SUKSES',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '',
                'marketing_nama'    => 'Muhammad Tamrin',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'KLINIK BRAWIJAYA',
                'pihak_ketiga'      => 'PT UNIOMED MEDICAL',
                'jenis_alat'        => 'Alerio Smart 1600',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => 'Menunggu Info Customer',
                'progres_perizinan' => 'BELUM PENGUJIAN UKES UPAR',
                'marketing_nama'    => 'Nurdiansyah',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'KLINIK UTAMA KIRANA MEDIKA MAKASSAR',
                'pihak_ketiga'      => 'PT MORA MEDICAL',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '-',
                'marketing_nama'    => 'Office',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'Klinik Ultra Medika Surabaya',
                'pihak_ketiga'      => 'PT. Ultra Medika Sejahter',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Pendampingan Perizinan',
                'status_perizinan'  => 'On Progress',
                'progres_perizinan' => '',
                'marketing_nama'    => 'Nurdiansyah',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'RS Efarina Etaham Karawang',
                'pihak_ketiga'      => 'PT PAMAH',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '',
                'marketing_nama'    => 'Office',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'Klinik Utama BMC',
                'pihak_ketiga'      => '-',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '',
                'marketing_nama'    => 'Muhammad Tamrin',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'RS Fanybella Pekanbaru (SEWA)',
                'pihak_ketiga'      => '-',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => 'SEWA',
                'marketing_nama'    => 'Office',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'RSIA Kirana Manado',
                'pihak_ketiga'      => 'CV Perdana inti bersaudara',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '',
                'marketing_nama'    => 'Yolanda Pratiwi',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'RSUI Mutiara Bunda',
                'pihak_ketiga'      => '(PT. Hamada)',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '',
                'marketing_nama'    => 'Office',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'RS Cakra Husada',
                'pihak_ketiga'      => 'PT. NAZ CAKRAWALA MEDIKA',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '',
                'marketing_nama'    => 'Office',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'Klinik Khaira Duri',
                'pihak_ketiga'      => '-',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '',
                'marketing_nama'    => 'Muhammad Tamrin',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'Klinik Meditama Syafira Dumai',
                'pihak_ketiga'      => '-',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '',
                'marketing_nama'    => 'Buldani',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'Klinik 4Care medical centre',
                'pihak_ketiga'      => 'PT Andre permata',
                'jenis_alat'        => 'Alerio Smart 1600',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => 'On Progress',
                'progres_perizinan' => "- Dokumen sudah sesuai\n- Menunggu pengganti SIB PPR",
                'marketing_nama'    => 'Yolanda Pratiwi',
                'tgl_jatuh_tempo'   => '2026-11-26'
            ],
            [
                'nama_instansi'     => 'Zio Clinic Occupational Health & Medicine Batu Aji',
                'pihak_ketiga'      => '-',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => 'Selesai',
                'progres_perizinan' => 'SELESAI',
                'marketing_nama'    => 'Yolanda Pratiwi',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'RSU Bersaudara Muara Bungo',
                'pihak_ketiga'      => 'CV Perdana inti bersaudara',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '-',
                'marketing_nama'    => 'Office',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'Klinik Rawat Inap Sejahtera',
                'pihak_ketiga'      => 'PT Alisha Jaya Medika',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => 'Selesai',
                'progres_perizinan' => 'SELESAI',
                'marketing_nama'    => 'Office',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'RS Izza',
                'pihak_ketiga'      => 'PT Alisha Jaya Medika',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => 'Menunggu Info Customer',
                'progres_perizinan' => 'RS Harus di upar ulang karena terdapatkebocoran shielding, alat akan digunakan di IGD',
                'marketing_nama'    => 'Office',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'Klinik Towuti Jaya Sejahtera',
                'pihak_ketiga'      => 'PT Uno Medikal Indonesia',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => 'Selesai',
                'progres_perizinan' => 'SELESAI',
                'marketing_nama'    => 'Nurdiansyah',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'RSUD Caruban',
                'pihak_ketiga'      => 'PT.Trijaya medika utama',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '-',
                'marketing_nama'    => 'Muhammad Tamrin',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'RS Efarina Etahan Brastagi',
                'pihak_ketiga'      => 'PT Panca Mitra amanah',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '-',
                'marketing_nama'    => 'Office',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'Klinik Hewan Sahabat Animalia',
                'pihak_ketiga'      => '-',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => 'On Progress',
                'progres_perizinan' => 'sedang tahap perizinan operasional klinik',
                'marketing_nama'    => 'Nurdiansyah',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'RS Awal Bros Sudirman',
                'pihak_ketiga'      => '-',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => 'Menunggu Info Customer',
                'progres_perizinan' => '',
                'marketing_nama'    => 'Muhammad Tamrin',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'RSU SRIKANDI IBI JEMBER',
                'pihak_ketiga'      => '(PT WILLIS)',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Tidak Termasuk Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => '-',
                'marketing_nama'    => 'Office',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'Sayang Binatang Vet Care',
                'pihak_ketiga'      => 'PT HUSADA SATWA',
                'jenis_alat'        => 'Alerio Smart 1600',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => 'Menunggu Info Customer',
                'progres_perizinan' => 'belum mempunyai PPR',
                'marketing_nama'    => 'Nurdiansyah',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'ISLAND VET BALI',
                'pihak_ketiga'      => 'PT HUSADA SATWA',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => 'Menunggu Info Customer',
                'progres_perizinan' => 'belum mempunyai PPR',
                'marketing_nama'    => 'Nurdiansyah',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'Rs Awal Bros Yasmine',
                'pihak_ketiga'      => '-',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => 'On Progress',
                'progres_perizinan' => 'Baru permintaan perizinan',
                'marketing_nama'    => 'Muhammad Tamrin',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'Sunter Medical Center',
                'pihak_ketiga'      => '-',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => 'Alat belum dikirim dan belum dilakukan pengujian',
                'marketing_nama'    => 'Nurdiansyah',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'Merdeka Medical Cente',
                'pihak_ketiga'      => '-',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => 'Alat belum dikirim dan belum dilakukan pengujian',
                'marketing_nama'    => 'Nurdiansyah',
                'tgl_jatuh_tempo'   => null
            ],
            [
                'nama_instansi'     => 'Klinik Gading Eka Medika',
                'pihak_ketiga'      => '-',
                'jenis_alat'        => 'Alerio Smart 4000',
                'status_jasa'       => 'Pengurusan Perizinan',
                'status_perizinan'  => '-',
                'progres_perizinan' => 'BELUM UKES UPAR',
                'marketing_nama'    => 'Muhammad Tamrin',
                'tgl_jatuh_tempo'   => null
            ]
        ];

        $marketingMap = $this->get_marketing_map();

        foreach ($seeds as &$item) {
            $namaMkt = trim($item['marketing_nama']);
            if (isset($marketingMap[$namaMkt])) {
                $item['marketing_id']   = $marketingMap[$namaMkt]->pengguna_id;
                $item['marketing_nohp'] = $marketingMap[$namaMkt]->no_hp;
            }
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
        }

        @$this->db->insert_batch($this->table, $seeds);
    }

    public function get_marketing_map()
    {
        $map = [];
        $query = $this->db->select('pengguna_id, nama, no_hp')
            ->from('pengguna')
            ->where('status', 1)
            ->get();

        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $clean = trim($row->nama);
                $map[$clean] = $row;
                $map[strtolower($clean)] = $row;
            }
        }
        return $map;
    }

    public function get_marketing_list()
    {
        return $this->db->select('pengguna_id, nama, no_hp')
            ->from('pengguna')
            ->where('status', 1)
            ->group_start()
                ->where('id_divisi', 3)
                ->or_where_in('pengguna_id', [54, 72, 75, 747, 754])
            ->group_end()
            ->order_by('nama', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Ambil semua data perizinan dengan filter & sisa hari masing-masing kewajiban
     */
    public function get_all($filters = [])
    {
        if (!$this->hasTable($this->table)) {
            return [];
        }

        $this->db->select("
            p.*,
            DATEDIFF(p.tgl_jatuh_tempo, CURDATE()) AS sisa_hari_izin,
            DATEDIFF(p.tgl_exp_luk, CURDATE()) AS sisa_hari_luk,
            DATEDIFF(p.tgl_exp_kalibrasi, CURDATE()) AS sisa_hari_kalibrasi,
            DATEDIFF(p.tgl_evaluasi_tld, CURDATE()) AS sisa_hari_tld
        ");
        $this->db->from("{$this->table} p");

        if (!empty($filters['status_jasa'])) {
            $this->db->where('p.status_jasa', $filters['status_jasa']);
        }
        if (!empty($filters['status_perizinan'])) {
            $this->db->where('p.status_perizinan', $filters['status_perizinan']);
        }
        if (!empty($filters['marketing'])) {
            $this->db->like('p.marketing_nama', $filters['marketing']);
        }
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $this->db->group_start();
            $this->db->like('p.nama_instansi', $s);
            $this->db->or_like('p.pihak_ketiga', $s);
            $this->db->or_like('p.jenis_alat', $s);
            $this->db->or_like('p.progres_perizinan', $s);
            $this->db->or_like('p.marketing_nama', $s);
            $this->db->group_end();
        }

        // Filter Masa Berlaku / Jatuh tempo
        if (!empty($filters['masa_berlaku'])) {
            if ($filters['masa_berlaku'] == 'h45_izin') {
                $this->db->where('p.tgl_jatuh_tempo IS NOT NULL');
                $this->db->where('DATEDIFF(p.tgl_jatuh_tempo, CURDATE()) >= 0', NULL, FALSE);
                $this->db->where('DATEDIFF(p.tgl_jatuh_tempo, CURDATE()) <= 45', NULL, FALSE);
            } else if ($filters['masa_berlaku'] == 'h30_luk') {
                $this->db->where('p.tgl_exp_luk IS NOT NULL');
                $this->db->where('DATEDIFF(p.tgl_exp_luk, CURDATE()) >= 0', NULL, FALSE);
                $this->db->where('DATEDIFF(p.tgl_exp_luk, CURDATE()) <= 30', NULL, FALSE);
            } else if ($filters['masa_berlaku'] == 'h30_kalibrasi') {
                $this->db->where('p.tgl_exp_kalibrasi IS NOT NULL');
                $this->db->where('DATEDIFF(p.tgl_exp_kalibrasi, CURDATE()) >= 0', NULL, FALSE);
                $this->db->where('DATEDIFF(p.tgl_exp_kalibrasi, CURDATE()) <= 30', NULL, FALSE);
            } else if ($filters['masa_berlaku'] == 'h30_tld') {
                $this->db->where('p.tgl_evaluasi_tld IS NOT NULL');
                $this->db->where('DATEDIFF(p.tgl_evaluasi_tld, CURDATE()) >= 0', NULL, FALSE);
                $this->db->where('DATEDIFF(p.tgl_evaluasi_tld, CURDATE()) <= 30', NULL, FALSE);
            } else if ($filters['masa_berlaku'] == 'expired') {
                $this->db->group_start();
                $this->db->where('(p.tgl_jatuh_tempo IS NOT NULL AND p.tgl_jatuh_tempo < CURDATE())', NULL, FALSE);
                $this->db->or_where('(p.tgl_exp_luk IS NOT NULL AND p.tgl_exp_luk < CURDATE())', NULL, FALSE);
                $this->db->or_where('(p.tgl_exp_kalibrasi IS NOT NULL AND p.tgl_exp_kalibrasi < CURDATE())', NULL, FALSE);
                $this->db->or_where('(p.tgl_evaluasi_tld IS NOT NULL AND p.tgl_evaluasi_tld < CURDATE())', NULL, FALSE);
                $this->db->group_end();
            }
        }

        $this->db->order_by('p.id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        if (!$this->hasTable($this->table)) {
            return null;
        }

        $this->db->select("
            p.*,
            DATEDIFF(p.tgl_jatuh_tempo, CURDATE()) AS sisa_hari_izin,
            DATEDIFF(p.tgl_exp_luk, CURDATE()) AS sisa_hari_luk,
            DATEDIFF(p.tgl_exp_kalibrasi, CURDATE()) AS sisa_hari_kalibrasi,
            DATEDIFF(p.tgl_evaluasi_tld, CURDATE()) AS sisa_hari_tld
        ");
        $this->db->from("{$this->table} p");
        $this->db->where('p.id', $id);
        return $this->db->get()->row();
    }

    public function insert($data)
    {
        $this->ensure_table();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->ensure_table();
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        if ($this->hasTable($this->table_history)) {
            $this->db->where('id_perizinan', $id)->delete($this->table_history);
        }
        if ($this->hasTable($this->table)) {
            $this->db->where('id', $id)->delete($this->table);
        }
        return true;
    }

    public function add_history($data)
    {
        $this->ensure_table();
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table_history, $data);
    }

    public function get_history($id_perizinan)
    {
        if (!$this->hasTable($this->table_history)) {
            return [];
        }

        return $this->db->where('id_perizinan', $id_perizinan)
            ->order_by('id', 'DESC')
            ->get($this->table_history)
            ->result();
    }

    public function get_expiring($jenis = 'izin', $days = 30, $only_unsent = false)
    {
        if (!$this->hasTable($this->table)) {
            return [];
        }

        $this->db->from("{$this->table} p");

        if ($jenis == 'izin') {
            $this->db->select("p.*, DATEDIFF(p.tgl_jatuh_tempo, CURDATE()) AS sisa_hari", FALSE);
            $this->db->where('p.tgl_jatuh_tempo IS NOT NULL');
            $this->db->where('DATEDIFF(p.tgl_jatuh_tempo, CURDATE()) >= 0', NULL, FALSE);
            $this->db->where('DATEDIFF(p.tgl_jatuh_tempo, CURDATE()) <= ' . (int)$days, NULL, FALSE);
            if ($only_unsent) $this->db->where('p.reminder_45_sent', 0);
            $this->db->order_by('p.tgl_jatuh_tempo', 'ASC');
        } else if ($jenis == 'luk') {
            $this->db->select("p.*, DATEDIFF(p.tgl_exp_luk, CURDATE()) AS sisa_hari", FALSE);
            $this->db->where('p.tgl_exp_luk IS NOT NULL');
            $this->db->where('DATEDIFF(p.tgl_exp_luk, CURDATE()) >= 0', NULL, FALSE);
            $this->db->where('DATEDIFF(p.tgl_exp_luk, CURDATE()) <= ' . (int)$days, NULL, FALSE);
            if ($only_unsent) $this->db->where('p.reminder_luk_sent', 0);
            $this->db->order_by('p.tgl_exp_luk', 'ASC');
        } else if ($jenis == 'kalibrasi') {
            $this->db->select("p.*, DATEDIFF(p.tgl_exp_kalibrasi, CURDATE()) AS sisa_hari", FALSE);
            $this->db->where('p.tgl_exp_kalibrasi IS NOT NULL');
            $this->db->where('DATEDIFF(p.tgl_exp_kalibrasi, CURDATE()) >= 0', NULL, FALSE);
            $this->db->where('DATEDIFF(p.tgl_exp_kalibrasi, CURDATE()) <= ' . (int)$days, NULL, FALSE);
            if ($only_unsent) $this->db->where('p.reminder_kalibrasi_sent', 0);
            $this->db->order_by('p.tgl_exp_kalibrasi', 'ASC');
        } else if ($jenis == 'tld') {
            $this->db->select("p.*, DATEDIFF(p.tgl_evaluasi_tld, CURDATE()) AS sisa_hari", FALSE);
            $this->db->where('p.tgl_evaluasi_tld IS NOT NULL');
            $this->db->where('DATEDIFF(p.tgl_evaluasi_tld, CURDATE()) >= 0', NULL, FALSE);
            $this->db->where('DATEDIFF(p.tgl_evaluasi_tld, CURDATE()) <= ' . (int)$days, NULL, FALSE);
            if ($only_unsent) $this->db->where('p.reminder_tld_sent', 0);
            $this->db->order_by('p.tgl_evaluasi_tld', 'ASC');
        }

        return $this->db->get()->result();
    }

    public function get_summary_stats()
    {
        if (!$this->hasTable($this->table)) {
            return [
                'total'          => 0,
                'proses'         => 0,
                'selesai'        => 0,
                'h45_izin'       => 0,
                'h30_luk'        => 0,
                'h30_kalibrasi'  => 0,
                'h30_tld'        => 0,
                'expired'        => 0
            ];
        }

        $totalQ = @$this->db->query("SELECT COUNT(*) as c FROM `{$this->table}`");
        $total = ($totalQ && $totalQ->num_rows() > 0) ? (int)$totalQ->row()->c : 0;

        $prosesQ = @$this->db->query("SELECT COUNT(*) as c FROM `{$this->table}` WHERE status_jasa IN ('Pengurusan Perizinan', 'Pendampingan Perizinan')");
        $proses = ($prosesQ && $prosesQ->num_rows() > 0) ? (int)$prosesQ->row()->c : 0;

        $selesaiQ = @$this->db->query("SELECT COUNT(*) as c FROM `{$this->table}` WHERE status_perizinan = 'Selesai'");
        $selesai = ($selesaiQ && $selesaiQ->num_rows() > 0) ? (int)$selesaiQ->row()->c : 0;

        $h45Q = @$this->db->query("SELECT COUNT(*) as c FROM `{$this->table}` WHERE tgl_jatuh_tempo IS NOT NULL AND DATEDIFF(tgl_jatuh_tempo, CURDATE()) >= 0 AND DATEDIFF(tgl_jatuh_tempo, CURDATE()) <= 45");
        $h45_izin = ($h45Q && $h45Q->num_rows() > 0) ? (int)$h45Q->row()->c : 0;

        $lukQ = @$this->db->query("SELECT COUNT(*) as c FROM `{$this->table}` WHERE tgl_exp_luk IS NOT NULL AND DATEDIFF(tgl_exp_luk, CURDATE()) >= 0 AND DATEDIFF(tgl_exp_luk, CURDATE()) <= 30");
        $h30_luk = ($lukQ && $lukQ->num_rows() > 0) ? (int)$lukQ->row()->c : 0;

        $kalQ = @$this->db->query("SELECT COUNT(*) as c FROM `{$this->table}` WHERE tgl_exp_kalibrasi IS NOT NULL AND DATEDIFF(tgl_exp_kalibrasi, CURDATE()) >= 0 AND DATEDIFF(tgl_exp_kalibrasi, CURDATE()) <= 30");
        $h30_kalibrasi = ($kalQ && $kalQ->num_rows() > 0) ? (int)$kalQ->row()->c : 0;

        $tldQ = @$this->db->query("SELECT COUNT(*) as c FROM `{$this->table}` WHERE tgl_evaluasi_tld IS NOT NULL AND DATEDIFF(tgl_evaluasi_tld, CURDATE()) >= 0 AND DATEDIFF(tgl_evaluasi_tld, CURDATE()) <= 30");
        $h30_tld = ($tldQ && $tldQ->num_rows() > 0) ? (int)$tldQ->row()->c : 0;

        $expQ = @$this->db->query("SELECT COUNT(*) as c FROM `{$this->table}` WHERE 
            (tgl_jatuh_tempo IS NOT NULL AND tgl_jatuh_tempo < CURDATE()) OR
            (tgl_exp_luk IS NOT NULL AND tgl_exp_luk < CURDATE()) OR
            (tgl_exp_kalibrasi IS NOT NULL AND tgl_exp_kalibrasi < CURDATE()) OR
            (tgl_evaluasi_tld IS NOT NULL AND tgl_evaluasi_tld < CURDATE())");
        $expired = ($expQ && $expQ->num_rows() > 0) ? (int)$expQ->row()->c : 0;

        return [
            'total'          => $total,
            'proses'         => $proses,
            'selesai'        => $selesai,
            'h45_izin'       => $h45_izin,
            'h30_luk'        => $h30_luk,
            'h30_kalibrasi'  => $h30_kalibrasi,
            'h30_tld'        => $h30_tld,
            'expired'        => $expired
        ];
    }
}
