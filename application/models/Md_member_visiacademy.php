<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_member_visiacademy extends CI_Model
{
    private $katalogTableChecked = false;

    /**
     * Pastikan tabel va_katalog_produk tersedia pada shared DB visiyosi_office.
     */
    private function ensureKatalogTable()
    {
        if ($this->katalogTableChecked) {
            return;
        }
        $this->katalogTableChecked = true;

        if (!$this->db->table_exists('va_katalog_produk')) {
            $sql = "CREATE TABLE IF NOT EXISTS `va_katalog_produk` (
                `id_produk` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `nama_produk` VARCHAR(200) NOT NULL,
                `kategori` VARCHAR(100) NOT NULL DEFAULT 'Radiologi',
                `deskripsi_singkat` TEXT NULL,
                `estimasi_harga` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
                `url_gambar` VARCHAR(255) NULL,
                `url_brosur` VARCHAR(255) NULL,
                `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
                `urutan` INT NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                KEY `idx_status_urutan` (`status`, `urutan`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            $this->db->query($sql);
        }
    }

    public function getAllMember()
    {
        return $this->db->order_by('id_member', 'DESC')->get('va_member')->result_array();
    }

    public function getMemberById($id)
    {
        return $this->db->where('id_member', (int)$id)->get('va_member')->row_array();
    }

    public function getMemberByKode($kode)
    {
        return $this->db->where('kode_member', trim($kode))->get('va_member')->row_array();
    }

    public function generateNextKode()
    {
        $last = $this->db->select('kode_member')
                         ->like('kode_member', 'VA-', 'after')
                         ->order_by('id_member', 'DESC')
                         ->limit(1)
                         ->get('va_member')
                         ->row_array();

        if ($last && preg_match('/VA-(\d+)/', $last['kode_member'], $m)) {
            $nextNum = ((int)$m[1]) + 1;
        } else {
            // Count total rows as fallback
            $count = $this->db->count_all('va_member');
            $nextNum = $count + 1;
        }

        return 'VA-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    public function addMember($data)
    {
        return $this->db->insert('va_member', $data);
    }

    public function updateMember($id, $data)
    {
        return $this->db->where('id_member', (int)$id)->update('va_member', $data);
    }

    public function deleteMember($id)
    {
        return $this->db->where('id_member', (int)$id)->delete('va_member');
    }

    public function getAllDeals()
    {
        return $this->db->select('d.*, m.nama_member, m.kode_member, m.institusi_asal, m.no_telp, m.nama_bank, m.no_rekening, m.atas_nama_rekening')
                        ->from('va_member_deal d')
                        ->join('va_member m', 'm.id_member = d.id_member', 'left')
                        ->order_by('d.id_deal', 'DESC')
                        ->get()->result_array();
    }

    public function getDealById($id)
    {
        return $this->db->select('d.*, m.nama_member, m.kode_member, m.institusi_asal, m.no_telp, m.nama_bank, m.no_rekening, m.atas_nama_rekening')
                        ->from('va_member_deal d')
                        ->join('va_member m', 'm.id_member = d.id_member', 'left')
                        ->where('d.id_deal', (int)$id)
                        ->get()->row_array();
    }

    public function getDealByMember($id_member)
    {
        return $this->db->where('id_member', (int)$id_member)->get('va_member_deal')->result_array();
    }

    public function addDeal($data)
    {
        return $this->db->insert('va_member_deal', $data);
    }

    public function updateDealStatus($id, $status)
    {
        return $this->db->where('id_deal', (int)$id)->update('va_member_deal', ['status_deal' => $status]);
    }

    public function updateDeal($id, $data)
    {
        return $this->db->where('id_deal', (int)$id)->update('va_member_deal', $data);
    }

    public function updatePayment($id, $data)
    {
        return $this->db->where('id_deal', (int)$id)->update('va_member_deal', $data);
    }

    public function deleteDeal($id)
    {
        return $this->db->where('id_deal', (int)$id)->delete('va_member_deal');
    }

    // =========================================================================
    // KATALOG PRODUK ALKES (va_katalog_produk)
    // =========================================================================

    public function getAllKatalog()
    {
        $this->ensureKatalogTable();
        return $this->db->order_by('urutan', 'ASC')
                        ->order_by('id_produk', 'DESC')
                        ->get('va_katalog_produk')
                        ->result_array();
    }

    public function getKatalogById($id)
    {
        $this->ensureKatalogTable();
        return $this->db->where('id_produk', (int)$id)
                        ->get('va_katalog_produk')
                        ->row_array();
    }

    public function getKatalogKategoriList()
    {
        $this->ensureKatalogTable();
        $defaults = [
            'Radiologi',
            'Proteksi Radiasi',
            'USG & Imaging',
            'X-Ray & C-Arm',
            'Aksesoris Alkes',
            'Lainnya',
        ];

        $rows = $this->db->distinct()
                         ->select('kategori')
                         ->where('kategori IS NOT NULL', null, false)
                         ->where("TRIM(kategori) !=", '')
                         ->order_by('kategori', 'ASC')
                         ->get('va_katalog_produk')
                         ->result_array();

        $dbCategories = [];
        foreach ($rows as $row) {
            $kat = trim((string)($row['kategori'] ?? ''));
            if ($kat !== '') {
                $dbCategories[] = $kat;
            }
        }

        return array_values(array_unique(array_merge($defaults, $dbCategories)));
    }

    public function addKatalog($data)
    {
        $this->ensureKatalogTable();
        return $this->db->insert('va_katalog_produk', $data);
    }

    public function updateKatalog($id, $data)
    {
        $this->ensureKatalogTable();
        return $this->db->where('id_produk', (int)$id)->update('va_katalog_produk', $data);
    }

    public function deleteKatalog($id)
    {
        $this->ensureKatalogTable();
        return $this->db->where('id_produk', (int)$id)->delete('va_katalog_produk');
    }

    public function getSummary()
    {
        $totalMember = $this->db->count_all('va_member');
        
        $dealStats = $this->db->select("
            COUNT(id_deal) as total_deal,
            SUM(CASE WHEN status_deal = 'Deal' THEN estimasi_nilai ELSE 0 END) as total_nilai_deal,
            SUM(CASE WHEN status_deal = 'Deal' THEN nominal_komisi ELSE 0 END) as total_komisi_deal,
            SUM(CASE WHEN status_deal = 'Pending' THEN nominal_komisi ELSE 0 END) as total_komisi_pending,
            SUM(CASE WHEN status_deal = 'Deal' AND status_bayar = 'Sudah Ditransfer' THEN nominal_komisi ELSE 0 END) as total_komisi_terbayar,
            SUM(CASE WHEN status_deal = 'Deal' AND (status_bayar = 'Belum Ditransfer' OR status_bayar IS NULL) THEN nominal_komisi ELSE 0 END) as total_komisi_siap_bayar
        ")->get('va_member_deal')->row_array();

        return [
            'total_member'              => (int)$totalMember,
            'total_deal'                => (int)($dealStats['total_deal'] ?? 0),
            'total_nilai_deal'          => (float)($dealStats['total_nilai_deal'] ?? 0),
            'total_komisi_deal'         => (float)($dealStats['total_komisi_deal'] ?? 0),
            'total_komisi_pending'      => (float)($dealStats['total_komisi_pending'] ?? 0),
            'total_komisi_terbayar'     => (float)($dealStats['total_komisi_terbayar'] ?? 0),
            'total_komisi_siap_bayar'   => (float)($dealStats['total_komisi_siap_bayar'] ?? 0),
        ];
    }
}
