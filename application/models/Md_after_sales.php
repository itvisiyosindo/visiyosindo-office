<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model Md_after_sales
 * Mengelola data sistem After Sales Service (Tiket Layanan, Perbaikan, Garansi, Sparepart, dsb)
 */
class Md_after_sales extends CI_Model
{
    protected $table = 'after_sales_service';
    protected $table_items = 'after_sales_items';
    protected $table_logs = 'after_sales_logs';
    protected static $initialized = false;

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->ensureSchema();
    }

    /**
     * Memastikan tabel dan struktur database after sales selalu siap
     */
    public function ensureSchema()
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

        // 1. Tabel Utama After Sales Service
        $sqlMain = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `nomor_tiket` VARCHAR(50) NOT NULL,
            `nomor_urut` INT(11) NOT NULL DEFAULT 1,
            `id_pelanggan` INT(11) DEFAULT NULL,
            `nama_pelanggan` VARCHAR(255) NOT NULL,
            `alamat_pelanggan` TEXT DEFAULT NULL,
            `pic_nama` VARCHAR(150) DEFAULT NULL,
            `pic_kontak` VARCHAR(50) DEFAULT NULL,
            `jenis_layanan` VARCHAR(100) NOT NULL DEFAULT 'Perbaikan / Troubleshooting',
            `sumber_tiket` VARCHAR(50) DEFAULT 'manual',
            `id_passport` INT(11) DEFAULT NULL,
            `nama_alat` VARCHAR(255) NOT NULL,
            `merk_tipe` VARCHAR(150) DEFAULT NULL,
            `nomor_seri` VARCHAR(100) DEFAULT NULL,
            `keluhan` TEXT DEFAULT NULL,
            `tgl_masuk` DATE NOT NULL,
            `tgl_kroscek` DATETIME DEFAULT NULL,
            `tgl_pengerjaan` DATETIME DEFAULT NULL,
            `tgl_selesai` DATETIME DEFAULT NULL,
            `id_teknisi` INT(11) DEFAULT NULL,
            `nama_teknisi` VARCHAR(150) DEFAULT NULL,
            `status` ENUM('menunggu_kroscek','proses','selesai','batal') NOT NULL DEFAULT 'menunggu_kroscek',
            `status_pembayaran` ENUM('garansi','free','berbayar') NOT NULL DEFAULT 'berbayar',
            `biaya_sparepart` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
            `biaya_jasa` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
            `biaya_total` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
            `tindakan_perbaikan` TEXT DEFAULT NULL,
            `catatan_teknisi` TEXT DEFAULT NULL,
            `catatan_kroscek` TEXT DEFAULT NULL,
            `status_data` TINYINT(1) NOT NULL DEFAULT 1,
            `created_by` INT(11) DEFAULT NULL,
            `updated_by` INT(11) DEFAULT NULL,
            `created_at` DATETIME DEFAULT NULL,
            `updated_at` DATETIME DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uk_nomor_tiket` (`nomor_tiket`),
            KEY `idx_pelanggan` (`id_pelanggan`),
            KEY `idx_teknisi` (`id_teknisi`),
            KEY `idx_status` (`status`),
            KEY `idx_status_data` (`status_data`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        @$db->query($sqlMain);

        // 2. Tabel Item Sparepart & Jasa Servis
        $sqlItems = "CREATE TABLE IF NOT EXISTS `{$this->table_items}` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `after_sales_id` INT(11) NOT NULL,
            `jenis` ENUM('sparepart','jasa') NOT NULL DEFAULT 'sparepart',
            `deskripsi` VARCHAR(255) NOT NULL,
            `qty` INT(11) NOT NULL DEFAULT 1,
            `satuan` VARCHAR(50) DEFAULT 'Pcs',
            `harga_satuan` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
            `subtotal` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
            `catatan` VARCHAR(255) DEFAULT NULL,
            `created_at` DATETIME DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_after_sales_id` (`after_sales_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        @$db->query($sqlItems);

        // 3. Tabel Log Status & Aktivitas Tiket
        $sqlLogs = "CREATE TABLE IF NOT EXISTS `{$this->table_logs}` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `after_sales_id` INT(11) NOT NULL,
            `status_lama` VARCHAR(50) DEFAULT NULL,
            `status_baru` VARCHAR(50) NOT NULL,
            `keterangan` TEXT DEFAULT NULL,
            `user_id` INT(11) DEFAULT NULL,
            `user_nama` VARCHAR(150) DEFAULT NULL,
            `created_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_log_ass` (`after_sales_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        @$db->query($sqlLogs);

        $db->db_debug = $origDebug;
        self::$initialized = true;
        return true;
    }

    /**
     * Konversi bulan angka ke Romawi
     */
    public function bulanToRomawi($bulan)
    {
        $romawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return isset($romawi[(int)$bulan]) ? $romawi[(int)$bulan] : '';
    }

    /**
     * Generate Nomor Tiket Layanan After Sales
     * Format: NOMOR/ASS/VYM/BULAN_ROMAWI/TAHUN (misal: 001/ASS/VYM/XI/2026)
     */
    public function generateNomorTiket($tanggal = null)
    {
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        $tahun = date('Y', strtotime($tanggal));
        $bulan = date('n', strtotime($tanggal));
        $bulanRomawi = $this->bulanToRomawi($bulan);

        $this->db->select_max('nomor_urut');
        $this->db->where('YEAR(tgl_masuk)', $tahun);
        $res = $this->db->get($this->table)->row();

        $nextNum = ($res && $res->nomor_urut) ? ((int)$res->nomor_urut + 1) : 1;
        $nomorFormatted = sprintf("%03d", $nextNum);

        $nomorTiket = "{$nomorFormatted}/ASS/VYM/{$bulanRomawi}/{$tahun}";

        return [
            'nomor_urut'  => $nextNum,
            'nomor_tiket' => $nomorTiket,
            'tahun'       => $tahun,
            'bulan_romawi'=> $bulanRomawi
        ];
    }

    /**
     * Ambil summary counter status tiket
     */
    public function countByStatus()
    {
        $res = [
            'total'            => 0,
            'menunggu_kroscek' => 0,
            'proses'           => 0,
            'selesai'          => 0,
            'batal'            => 0
        ];

        $this->db->select('status, COUNT(*) as jml');
        $this->db->where('status_data', 1);
        $this->db->group_by('status');
        $query = $this->db->get($this->table)->result();

        foreach ($query as $row) {
            if (isset($res[$row->status])) {
                $res[$row->status] = (int)$row->jml;
            }
            $res['total'] += (int)$row->jml;
        }

        return $res;
    }

    /**
     * Ambil list tiket dengan filter
     */
    public function getAll($filter = [])
    {
        $this->db->select('ass.*, u.nama as nama_pembuat, t.nama as teknisi_nama_lengkap');
        $this->db->from("{$this->table} ass");
        $this->db->join('pengguna u', 'u.id_pengguna = ass.created_by', 'left');
        $this->db->join('pengguna t', 't.id_pengguna = ass.id_teknisi', 'left');
        $this->db->where('ass.status_data', 1);

        if (!empty($filter['status'])) {
            $this->db->where('ass.status', $filter['status']);
        }
        if (!empty($filter['jenis_layanan'])) {
            $this->db->where('ass.jenis_layanan', $filter['jenis_layanan']);
        }
        if (!empty($filter['id_teknisi'])) {
            $this->db->where('ass.id_teknisi', $filter['id_teknisi']);
        }
        if (!empty($filter['tgl_awal'])) {
            $this->db->where('ass.tgl_masuk >=', $filter['tgl_awal']);
        }
        if (!empty($filter['tgl_akhir'])) {
            $this->db->where('ass.tgl_masuk <=', $filter['tgl_akhir']);
        }
        if (!empty($filter['search'])) {
            $search = $filter['search'];
            $this->db->group_start();
            $this->db->like('ass.nomor_tiket', $search);
            $this->db->or_like('ass.nama_pelanggan', $search);
            $this->db->or_like('ass.nama_alat', $search);
            $this->db->or_like('ass.nomor_seri', $search);
            $this->db->group_end();
        }

        $this->db->order_by('ass.id', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Ambil detail tiket by ID
     */
    public function getById($id)
    {
        $this->db->select('ass.*, u.nama as nama_pembuat, t.nama as teknisi_nama_lengkap, t.telepon as no_wa_teknisi');
        $this->db->from("{$this->table} ass");
        $this->db->join('pengguna u', 'u.id_pengguna = ass.created_by', 'left');
        $this->db->join('pengguna t', 't.id_pengguna = ass.id_teknisi', 'left');
        $this->db->where('ass.id', $id);
        $this->db->where('ass.status_data', 1);
        $ass = $this->db->get()->row();

        if ($ass) {
            $ass->items = $this->getItems($id);
            $ass->logs = $this->getLogs($id);
        }

        return $ass;
    }

    /**
     * Ambil item-item servis/sparepart
     */
    public function getItems($after_sales_id)
    {
        return $this->db->where('after_sales_id', $after_sales_id)
            ->order_by('id', 'ASC')
            ->get($this->table_items)
            ->result();
    }

    /**
     * Ambil log history tiket
     */
    public function getLogs($after_sales_id)
    {
        return $this->db->where('after_sales_id', $after_sales_id)
            ->order_by('id', 'ASC')
            ->get($this->table_logs)
            ->result();
    }

    /**
     * Tambah tiket baru
     */
    public function insert($data, $items = [])
    {
        $this->db->trans_start();

        $this->db->insert($this->table, $data);
        $insertId = $this->db->insert_id();

        if (!empty($items) && is_array($items)) {
            foreach ($items as $item) {
                if (empty($item['deskripsi'])) {
                    continue;
                }
                $item['after_sales_id'] = $insertId;
                $item['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert($this->table_items, $item);
            }
        }

        // Catat log pertama
        $this->insertLog([
            'after_sales_id' => $insertId,
            'status_lama'    => null,
            'status_baru'    => $data['status'] ?? 'menunggu_kroscek',
            'keterangan'     => 'Tiket after sales service baru berhasil dibuat.',
            'user_id'        => $data['created_by'] ?? sessPenggunaId(),
            'user_nama'      => sessNamaPengguna(),
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status() ? $insertId : false;
    }

    /**
     * Update data tiket beserta itemnya
     */
    public function update($id, $data, $items = null)
    {
        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->update($this->table, $data);

        if ($items !== null && is_array($items)) {
            // Hapus items lama dan ganti dengan baru
            $this->db->where('after_sales_id', $id)->delete($this->table_items);
            foreach ($items as $item) {
                if (empty($item['deskripsi'])) {
                    continue;
                }
                $item['after_sales_id'] = $id;
                $item['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert($this->table_items, $item);
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Catat log status / perubahan
     */
    public function insertLog($logData)
    {
        return $this->db->insert($this->table_logs, $logData);
    }

    /**
     * Soft delete tiket
     */
    public function delete($id)
    {
        return $this->db->where('id', $id)->update($this->table, [
            'status_data' => 0,
            'updated_at'  => date('Y-m-d H:i:s'),
            'updated_by'  => sessPenggunaId()
        ]);
    }

    /**
     * Ambil daftar pelanggan untuk select2 dropdown
     */
    public function getPelangganList()
    {
        return $this->db->select('id, identitas_pelanggan, nama_pelanggan, alamat, kontak, no_telp')
            ->where('status_hapus', 0)
            ->order_by('nama_pelanggan', 'ASC')
            ->get('pelanggan')
            ->result();
    }

    /**
     * Ambil daftar teknisi aktif (divisi 7 atau role teknisi)
     */
    public function getTeknisiList()
    {
        return $this->db->select('id_pengguna, nama, jabatan, telepon')
            ->where('status_hapus', 0)
            ->where('id_divisi', 7) // Divisi Teknisi
            ->order_by('nama', 'ASC')
            ->get('pengguna')
            ->result();
    }
}
