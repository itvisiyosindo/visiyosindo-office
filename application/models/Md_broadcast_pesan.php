<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_broadcast_pesan extends CI_Model
{
    private $tableName = 'broadcast_wa_log';
    private $tableReady = null;

    public function ensureTable()
    {
        if ($this->tableReady !== null) {
            return $this->tableReady;
        }

        if ($this->db->table_exists($this->tableName)) {
            $this->tableReady = true;
            return true;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableName}` (
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

        $dbDebug = $this->db->db_debug;
        $this->db->db_debug = false;
        $this->db->query($sql);
        $this->db->db_debug = $dbDebug;

        $this->tableReady = true;
        return true;
    }

    public function logClick($id_bukutamu, $nomorwa, $nama, $kegiatan_id = 0)
    {
        $this->ensureTable();

        $penggunaId = (int) sessPenggunaId();
        $namaPengguna = (string) sessNama();
        $now = date('Y-m-d H:i:s');

        // Check if log exists for this bukutamu
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
}
