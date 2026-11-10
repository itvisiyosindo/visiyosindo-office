<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_perizinan_visilab extends CI_Model
{
    protected $table = 'visilab_perizinan_alat';
    protected $table_history = 'visilab_perizinan_history';

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->ensure_table();
    }

    /**
     * Memastikan tabel perizinan dan tabel history telah dibuat di database,
     * serta menambahkan kolom LUK, Kalibrasi, dan TLD jika belum ada.
     */
    public function ensure_table()
    {
        if (!isset($this->db)) {
            return false;
        }

        // 1. Tabel Utama Perizinan Alat
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `nama_instansi` VARCHAR(255) NOT NULL,
            `pihak_ketiga` VARCHAR(255) DEFAULT NULL,
            `jenis_alat` VARCHAR(255) NOT NULL,
            `status_jasa` VARCHAR(100) NOT NULL DEFAULT 'Tidak Termasuk Perizinan',
            `status_perizinan` VARCHAR(100) DEFAULT '-',
            `progres_perizinan` TEXT DEFAULT NULL,
            `marketing_id` INT(11) DEFAULT NULL,
            `marketing_nama` VARCHAR(150) DEFAULT NULL,
            `marketing_nohp` VARCHAR(50) DEFAULT NULL,
            `pic_instansi` VARCHAR(150) DEFAULT NULL,
            `kontak_pic` VARCHAR(50) DEFAULT NULL,
            `email_pic` VARCHAR(150) DEFAULT NULL,
            `tgl_terbit` DATE DEFAULT NULL,
            `tgl_jatuh_tempo` DATE DEFAULT NULL COMMENT 'Batas Evaluasi / Masa Berlaku Izin',
            `tgl_exp_luk` DATE DEFAULT NULL COMMENT 'Masa Berlaku LUK Uji Kesesuaian (Reminder 1 Bulan)',
            `tgl_exp_kalibrasi` DATE DEFAULT NULL COMMENT 'Masa Berlaku Kalibrasi (Reminder 1 Bulan)',
            `tgl_evaluasi_tld` DATE DEFAULT NULL COMMENT 'Jatuh Tempo Evaluasi TLD Triwulan (Reminder 1 Bulan)',
            `catatan` TEXT DEFAULT NULL,
            `reminder_45_sent` TINYINT(1) DEFAULT 0,
            `last_reminder_at` DATETIME DEFAULT NULL,
            `reminder_luk_sent` TINYINT(1) DEFAULT 0,
            `last_reminder_luk_at` DATETIME DEFAULT NULL,
            `reminder_kalibrasi_sent` TINYINT(1) DEFAULT 0,
            `last_reminder_kalibrasi_at` DATETIME DEFAULT NULL,
            `reminder_tld_sent` TINYINT(1) DEFAULT 0,
            `last_reminder_tld_at` DATETIME DEFAULT NULL,
            `created_by` INT(11) DEFAULT NULL,
            `updated_by` INT(11) DEFAULT NULL,
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_status_perizinan` (`status_perizinan`),
            KEY `idx_status_jasa` (`status_jasa`),
            KEY `idx_tgl_jatuh_tempo` (`tgl_jatuh_tempo`),
            KEY `idx_tgl_luk` (`tgl_exp_luk`),
            KEY `idx_tgl_kalibrasi` (`tgl_exp_kalibrasi`),
            KEY `idx_tgl_tld` (`tgl_evaluasi_tld`),
            KEY `idx_marketing` (`marketing_nama`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        @$this->db->query($sql);

        // Tambah kolom jika tabel sudah ada dari versi sebelumnya
        $existingCols = $this->db->list_fields($this->table);
        $newCols = [
            'tgl_exp_luk'                => "DATE DEFAULT NULL COMMENT 'Masa Berlaku LUK Uji Kesesuaian'",
            'tgl_exp_kalibrasi'          => "DATE DEFAULT NULL COMMENT 'Masa Berlaku Kalibrasi Alat'",
            'tgl_evaluasi_tld'           => "DATE DEFAULT NULL COMMENT 'Jatuh Tempo Evaluasi TLD 3 Bulanan'",
            'reminder_luk_sent'          => "TINYINT(1) DEFAULT 0",
            'last_reminder_luk_at'       => "DATETIME DEFAULT NULL",
            'reminder_kalibrasi_sent'    => "TINYINT(1) DEFAULT 0",
            'last_reminder_kalibrasi_at' => "DATETIME DEFAULT NULL",
            'reminder_tld_sent'          => "TINYINT(1) DEFAULT 0",
            'last_reminder_tld_at'       => "DATETIME DEFAULT NULL",
        ];
        foreach ($newCols as $col => $definition) {
            if (!in_array($col, $existingCols)) {
                @$this->db->query("ALTER TABLE `{$this->table}` ADD COLUMN `{$col}` {$definition}");
            }
        }

        // 2. Tabel Riwayat Progres Perizinan
        $sql_history = "CREATE TABLE IF NOT EXISTS `{$this->table_history}` (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_perizinan` INT(11) UNSIGNED NOT NULL,
            `jenis_notif` VARCHAR(50) DEFAULT 'progres' COMMENT 'progres, izin_h45, luk_1bln, kalibrasi_1bln, tld_1bln',
            `status_lama` VARCHAR(100) DEFAULT NULL,
            `status_baru` VARCHAR(100) DEFAULT NULL,
            `progres_lama` TEXT DEFAULT NULL,
            `progres_baru` TEXT DEFAULT NULL,
            `tgl_jatuh_tempo_lama` DATE DEFAULT NULL,
            `tgl_jatuh_tempo_baru` DATE DEFAULT NULL,
            `penerima_cust` VARCHAR(50) DEFAULT NULL,
            `penerima_mkt` VARCHAR(50) DEFAULT NULL,
            `keterangan` TEXT DEFAULT NULL,
            `notif_marketing_sent` TINYINT(1) DEFAULT 0,
            `notif_visilab_sent` TINYINT(1) DEFAULT 0,
            `notif_customer_sent` TINYINT(1) DEFAULT 0,
            `created_by` INT(11) DEFAULT NULL,
            `created_by_nama` VARCHAR(150) DEFAULT NULL,
            `created_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_id_perizinan` (`id_perizinan`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        @$this->db->query($sql_history);

        // Tambah kolom history jika belum ada
        $existingHistCols = $this->db->list_fields($this->table_history);
        if (!in_array('jenis_notif', $existingHistCols)) {
            @$this->db->query("ALTER TABLE `{$this->table_history}` ADD COLUMN `jenis_notif` VARCHAR(50) DEFAULT 'progres'");
        }
        if (!in_array('notif_customer_sent', $existingHistCols)) {
            @$this->db->query("ALTER TABLE `{$this->table_history}` ADD COLUMN `notif_customer_sent` TINYINT(1) DEFAULT 0");
        }

        // 3. Auto Seed data awal jika tabel masih kosong
        $count = $this->db->count_all($this->table);
        if ($count == 0) {
            $this->seed_initial_data();
        }

        return true;
    }

    /**
     * Memasukkan 30 data awal dari master Excel perizinan alat
     */
    private function seed_initial_data()
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
                $this->db->where('DATEDIFF(p.tgl_jatuh_tempo, CURDATE()) >=', 0);
                $this->db->where('DATEDIFF(p.tgl_jatuh_tempo, CURDATE()) <=', 45);
            } else if ($filters['masa_berlaku'] == 'h30_luk') {
                $this->db->where('p.tgl_exp_luk IS NOT NULL');
                $this->db->where('DATEDIFF(p.tgl_exp_luk, CURDATE()) >=', 0);
                $this->db->where('DATEDIFF(p.tgl_exp_luk, CURDATE()) <=', 30);
            } else if ($filters['masa_berlaku'] == 'h30_kalibrasi') {
                $this->db->where('p.tgl_exp_kalibrasi IS NOT NULL');
                $this->db->where('DATEDIFF(p.tgl_exp_kalibrasi, CURDATE()) >=', 0);
                $this->db->where('DATEDIFF(p.tgl_exp_kalibrasi, CURDATE()) <=', 30);
            } else if ($filters['masa_berlaku'] == 'h30_tld') {
                $this->db->where('p.tgl_evaluasi_tld IS NOT NULL');
                $this->db->where('DATEDIFF(p.tgl_evaluasi_tld, CURDATE()) >=', 0);
                $this->db->where('DATEDIFF(p.tgl_evaluasi_tld, CURDATE()) <=', 30);
            } else if ($filters['masa_berlaku'] == 'expired') {
                $this->db->group_start();
                $this->db->where('(p.tgl_jatuh_tempo IS NOT NULL AND p.tgl_jatuh_tempo < CURDATE())');
                $this->db->or_where('(p.tgl_exp_luk IS NOT NULL AND p.tgl_exp_luk < CURDATE())');
                $this->db->or_where('(p.tgl_exp_kalibrasi IS NOT NULL AND p.tgl_exp_kalibrasi < CURDATE())');
                $this->db->or_where('(p.tgl_evaluasi_tld IS NOT NULL AND p.tgl_evaluasi_tld < CURDATE())');
                $this->db->group_end();
            }
        }

        $this->db->order_by('p.id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
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
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id_perizinan', $id)->delete($this->table_history);
        $this->db->where('id', $id)->delete($this->table);
        return true;
    }

    public function add_history($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table_history, $data);
    }

    public function get_history($id_perizinan)
    {
        return $this->db->where('id_perizinan', $id_perizinan)
            ->order_by('id', 'DESC')
            ->get($this->table_history)
            ->result();
    }

    /**
     * Ambil data yang jatuh tempo dalam H-X hari untuk masing-masing jenis
     */
    public function get_expiring($jenis = 'izin', $days = 30, $only_unsent = false)
    {
        $this->db->from("{$this->table} p");

        if ($jenis == 'izin') {
            $this->db->select("p.*, DATEDIFF(p.tgl_jatuh_tempo, CURDATE()) AS sisa_hari");
            $this->db->where('p.tgl_jatuh_tempo IS NOT NULL');
            $this->db->where('DATEDIFF(p.tgl_jatuh_tempo, CURDATE()) >=', 0);
            $this->db->where('DATEDIFF(p.tgl_jatuh_tempo, CURDATE()) <=', (int)$days);
            if ($only_unsent) $this->db->where('p.reminder_45_sent', 0);
            $this->db->order_by('p.tgl_jatuh_tempo', 'ASC');
        } else if ($jenis == 'luk') {
            $this->db->select("p.*, DATEDIFF(p.tgl_exp_luk, CURDATE()) AS sisa_hari");
            $this->db->where('p.tgl_exp_luk IS NOT NULL');
            $this->db->where('DATEDIFF(p.tgl_exp_luk, CURDATE()) >=', 0);
            $this->db->where('DATEDIFF(p.tgl_exp_luk, CURDATE()) <=', (int)$days);
            if ($only_unsent) $this->db->where('p.reminder_luk_sent', 0);
            $this->db->order_by('p.tgl_exp_luk', 'ASC');
        } else if ($jenis == 'kalibrasi') {
            $this->db->select("p.*, DATEDIFF(p.tgl_exp_kalibrasi, CURDATE()) AS sisa_hari");
            $this->db->where('p.tgl_exp_kalibrasi IS NOT NULL');
            $this->db->where('DATEDIFF(p.tgl_exp_kalibrasi, CURDATE()) >=', 0);
            $this->db->where('DATEDIFF(p.tgl_exp_kalibrasi, CURDATE()) <=', (int)$days);
            if ($only_unsent) $this->db->where('p.reminder_kalibrasi_sent', 0);
            $this->db->order_by('p.tgl_exp_kalibrasi', 'ASC');
        } else if ($jenis == 'tld') {
            $this->db->select("p.*, DATEDIFF(p.tgl_evaluasi_tld, CURDATE()) AS sisa_hari");
            $this->db->where('p.tgl_evaluasi_tld IS NOT NULL');
            $this->db->where('DATEDIFF(p.tgl_evaluasi_tld, CURDATE()) >=', 0);
            $this->db->where('DATEDIFF(p.tgl_evaluasi_tld, CURDATE()) <=', (int)$days);
            if ($only_unsent) $this->db->where('p.reminder_tld_sent', 0);
            $this->db->order_by('p.tgl_evaluasi_tld', 'ASC');
        }

        return $this->db->get()->result();
    }

    public function get_summary_stats()
    {
        $total = $this->db->count_all($this->table);

        $proses = $this->db->where_in('status_jasa', ['Pengurusan Perizinan', 'Pendampingan Perizinan'])
            ->count_all_results($this->table);

        $selesai = $this->db->where('status_perizinan', 'Selesai')
            ->count_all_results($this->table);

        // Izin H-45
        $h45_izin = $this->db->where('tgl_jatuh_tempo IS NOT NULL')
            ->where('DATEDIFF(tgl_jatuh_tempo, CURDATE()) >=', 0)
            ->where('DATEDIFF(tgl_jatuh_tempo, CURDATE()) <=', 45)
            ->count_all_results($this->table);

        // LUK H-30 (1 Bulan)
        $h30_luk = $this->db->where('tgl_exp_luk IS NOT NULL')
            ->where('DATEDIFF(tgl_exp_luk, CURDATE()) >=', 0)
            ->where('DATEDIFF(tgl_exp_luk, CURDATE()) <=', 30)
            ->count_all_results($this->table);

        // Kalibrasi H-30 (1 Bulan)
        $h30_kalibrasi = $this->db->where('tgl_exp_kalibrasi IS NOT NULL')
            ->where('DATEDIFF(tgl_exp_kalibrasi, CURDATE()) >=', 0)
            ->where('DATEDIFF(tgl_exp_kalibrasi, CURDATE()) <=', 30)
            ->count_all_results($this->table);

        // TLD H-30 (1 Bulan)
        $h30_tld = $this->db->where('tgl_evaluasi_tld IS NOT NULL')
            ->where('DATEDIFF(tgl_evaluasi_tld, CURDATE()) >=', 0)
            ->where('DATEDIFF(tgl_evaluasi_tld, CURDATE()) <=', 30)
            ->count_all_results($this->table);

        // Expired (lewat tanggal jatuh tempo apapun)
        $expired = $this->db->group_start()
            ->where('(tgl_jatuh_tempo IS NOT NULL AND tgl_jatuh_tempo < CURDATE())')
            ->or_where('(tgl_exp_luk IS NOT NULL AND tgl_exp_luk < CURDATE())')
            ->or_where('(tgl_exp_kalibrasi IS NOT NULL AND tgl_exp_kalibrasi < CURDATE())')
            ->or_where('(tgl_evaluasi_tld IS NOT NULL AND tgl_evaluasi_tld < CURDATE())')
            ->group_end()
            ->count_all_results($this->table);

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
