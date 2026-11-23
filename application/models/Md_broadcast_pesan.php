<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_broadcast_pesan extends CI_Model
{
    private $tableName = 'broadcast_wa_log';
    private $historyTable = 'broadcast_wa_history';
    private $tableReady = null;

    public function ensureTable()
    {
        if ($this->tableReady !== null) {
            return $this->tableReady;
        }

        $dbDebug = $this->db->db_debug;
        $this->db->db_debug = false;

        // Table 1: Summary per kontak
        $sql1 = "CREATE TABLE IF NOT EXISTS `{$this->tableName}` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_bukutamu` INT NOT NULL,
            `kegiatan_id` INT NULL,
            `nama` VARCHAR(150) NULL,
            `nomorwa` VARCHAR(30) NULL,
            `pengguna_id` INT NULL,
            `nama_pengguna` VARCHAR(150) NULL,
            `total_klik` INT NOT NULL DEFAULT 1,
            `last_click_at` DATETIME NOT NULL,
            `created_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_id_bukutamu` (`id_bukutamu`),
            KEY `idx_pengguna_id` (`pengguna_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db->query($sql1);

        // Table 2: Activity log per pengiriman/klik untuk rekap harian
        $sql2 = "CREATE TABLE IF NOT EXISTS `{$this->historyTable}` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_bukutamu` INT NOT NULL,
            `kegiatan_id` INT NULL,
            `nama` VARCHAR(150) NULL,
            `instansi` VARCHAR(255) NULL,
            `jabatan` VARCHAR(150) NULL,
            `nama_kegiatan` VARCHAR(255) NULL,
            `nomorwa` VARCHAR(30) NULL,
            `pengguna_id` INT NULL,
            `nama_pengguna` VARCHAR(150) NULL,
            `tanggal` DATE NOT NULL,
            `clicked_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_tanggal` (`tanggal`),
            KEY `idx_id_bukutamu` (`id_bukutamu`),
            KEY `idx_pengguna_id` (`pengguna_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db->query($sql2);

        $this->db->db_debug = $dbDebug;
        $this->tableReady = true;
        return true;
    }

    public function logClick($id_bukutamu, $nomorwa, $nama, $kegiatan_id = 0, $instansi = '', $jabatan = '', $nama_kegiatan = '')
    {
        $this->ensureTable();

        $penggunaId = (int) sessPenggunaId();
        $namaPengguna = (string) sessNama();
        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');

        // 1. Insert into history table for daily tracking
        $this->db->insert($this->historyTable, [
            'id_bukutamu'   => (int)$id_bukutamu,
            'kegiatan_id'   => (int)$kegiatan_id,
            'nama'          => $nama,
            'instansi'      => $instansi,
            'jabatan'       => $jabatan,
            'nama_kegiatan' => $nama_kegiatan,
            'nomorwa'       => $nomorwa,
            'pengguna_id'   => $penggunaId,
            'nama_pengguna' => $namaPengguna,
            'tanggal'       => $today,
            'clicked_at'    => $now
        ]);

        // 2. Update summary table per bukutamu
        $existing = $this->db->where('id_bukutamu', (int)$id_bukutamu)
                             ->get($this->tableName)
                             ->row();

        if ($existing) {
            $this->db->where('id', $existing->id)
                     ->update($this->tableName, [
                         'total_klik'    => $existing->total_klik + 1,
                         'pengguna_id'   => $penggunaId,
                         'nama_pengguna' => $namaPengguna,
                         'last_click_at' => $now
                     ]);
            return [
                'status'        => 'updated',
                'total_klik'    => $existing->total_klik + 1,
                'last_click_at' => $now,
                'nama_pengguna' => $namaPengguna
            ];
        } else {
            $this->db->insert($this->tableName, [
                'id_bukutamu'   => (int)$id_bukutamu,
                'kegiatan_id'   => (int)$kegiatan_id,
                'nama'          => $nama,
                'nomorwa'       => $nomorwa,
                'pengguna_id'   => $penggunaId,
                'nama_pengguna' => $namaPengguna,
                'total_klik'    => 1,
                'last_click_at' => $now,
                'created_at'    => $now
            ]);
            return [
                'status'        => 'inserted',
                'total_klik'    => 1,
                'last_click_at' => $now,
                'nama_pengguna' => $namaPengguna
            ];
        }
    }

    public function getAllBroadcastData($kegiatan_id = null, $tanggal_mulai = null, $tanggal_selesai = null, $status_hubungi = null)
    {
        $this->ensureTable();

        $this->db->order_by('bt.created_at', 'DESC');
        $dt = $this->datatables
            ->select('  
                bt.nama,
                bt.id_bukutamu,
                bt.nomorwa,
                bt.email,
                bt.jabatan,
                bt.instansi,
                bt.tipe_rs,
                bt.provinsi,
                bt.kota,
                bt.kegiatan,
                bt.kebutuhan,
                bt.created_at,
                bc.nama as nama_kegiatan,
                bwl.total_klik,
                bwl.nama_pengguna,
                bwl.last_click_at
            ')
            ->from('bukutamu bt')
            ->join('bukutamu_config bc', 'bt.kegiatan=bc.id', 'left')
            ->join($this->tableName . ' bwl', 'bt.id_bukutamu=bwl.id_bukutamu', 'left');

        if (!empty($kegiatan_id)) {
            $dt->where('bt.kegiatan', (int)$kegiatan_id);
        }

        if (!empty($tanggal_mulai)) {
            $dt->where('bt.created_at >=', $tanggal_mulai . ' 00:00:00');
        }

        if (!empty($tanggal_selesai)) {
            $dt->where('bt.created_at <=', $tanggal_selesai . ' 23:59:59');
        }

        if ($status_hubungi === 'sudah') {
            $dt->where('bwl.id IS NOT NULL', null, false);
        } elseif ($status_hubungi === 'belum') {
            $dt->where('bwl.id IS NULL', null, false);
        }

        return $dt->generate();
    }

    public function getDailyHistoryData($tanggal = null, $kegiatan_id = null)
    {
        $this->ensureTable();

        if (empty($tanggal)) {
            $tanggal = date('Y-m-d');
        }

        $this->db->order_by('bwh.clicked_at', 'DESC');
        $dt = $this->datatables
            ->select('
                bwh.id,
                bwh.id_bukutamu,
                bwh.nama,
                bwh.instansi,
                bwh.jabatan,
                bwh.nama_kegiatan,
                bwh.nomorwa,
                bwh.nama_pengguna,
                bwh.clicked_at,
                bwh.tanggal
            ')
            ->from($this->historyTable . ' bwh')
            ->where('bwh.tanggal', $tanggal);

        if (!empty($kegiatan_id)) {
            $dt->where('bwh.kegiatan_id', (int)$kegiatan_id);
        }

        return $dt->generate();
    }

    public function getDailyStats($tanggal = null)
    {
        $this->ensureTable();

        if (empty($tanggal)) {
            $tanggal = date('Y-m-d');
        }

        $totalKlik = $this->db->where('tanggal', $tanggal)->count_all_results($this->historyTable);
        
        $totalKontakUnik = $this->db->select('COUNT(DISTINCT id_bukutamu) as total')
                                   ->where('tanggal', $tanggal)
                                   ->get($this->historyTable)
                                   ->row()->total ?? 0;

        return [
            'total_klik'        => (int)$totalKlik,
            'total_kontak_unik' => (int)$totalKontakUnik
        ];
    }
}
