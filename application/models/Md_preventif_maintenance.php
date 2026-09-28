<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Model untuk Preventif Maintenance (PM)
 * 
 * Mengelola operasi database untuk Preventif Maintenance termasuk:
 * - CRUD PM
 * - Manajemen pihak ketiga (PT/CV)
 * - History/update PM
 * - Auto numbering
 */
class Md_preventif_maintenance extends CI_Model
{
    // ========================================
    // CREATE/INSERT
    // ========================================

    /**
     * Tambah PM baru
     */
    public function addPm($data)
    {
        $this->db->insert('preventif_maintenance', $data);
        return $this->db->insert_id();
    }

    /**
     * Tambah detail/update PM
     */
    public function addDetail($data)
    {
        $this->db->insert('preventif_maintenance_detail', $data);
        return $this->db->insert_id();
    }
    
    // ========================================
    // UPDATE
    // ========================================

    /**
     * Update PM by ID
     */
    public function updatePm($id_pm, $data)
    {
        $this->db->where('id_pm', $id_pm);
        $this->db->update('preventif_maintenance', $data);
        return $this->db->affected_rows();
    }

    /**
     * Update PM by where condition
     */
    public function updateByWhere($where, $data)
    {
        $this->db->where($where);
        $this->db->update('preventif_maintenance', $data);
        return $this->db->affected_rows();
    }
    
    // ========================================
    // READ/GET
    // ========================================

    /**
     * Get PM by ID (single record dengan join)
     */
    public function getById($id_pm)
    {
        return $this->db->select('
                            pm.id_pm,
                            pm.kode_pm,
                            pm.log_pm,
                            pm.id_pembuat,
                            pm.id_penerima,
                            pm.id_pelanggan,
                            pm.nama_cp,
                            pm.nomer_cp,
                            pm.id_pihak_ketiga,
                            pm.id_topik,
                            pm.nama_pihak_ketiga,
                            pm.subject,
                            pm.deskripsi,
                            pm.file_pendukung,
                            pm.file_invoice,
                            pm.prioritas,
                            pm.tanggal_pm,
                            pm.waktu_mulai,
                            pm.waktu_selesai,
                            pm.catatan_visit,
                            pm.status_pm,
                            pm.status_data,
                            pm.created_at,
                            pm.updated_at,
                            p.nama as nama_pembuat,
                            p.no_hp as no_hp_pembuat,
                            p.level as level_pembuat,
                            pr.nama as nama_penerima,
                            pr.no_hp as no_hp_penerima,
                            pel.identitas_pelanggan as nama_pihak_ketiga_full,
                            pel.kontak,
                            pel.alamat,
                            pel.kota,
                            pel.provinsi,
                            cust.identitas_pelanggan as nama_pelanggan,
                            cust.kontak as kontak_pelanggan,
                            tt.nama as nama_topik
                        ')
            ->from('preventif_maintenance pm')
            ->join('pengguna p', 'pm.id_pembuat=p.pengguna_id', 'left')
            ->join('pengguna pr', 'pm.id_penerima=pr.pengguna_id', 'left')
            ->join('pelanggan pel', 'pm.id_pihak_ketiga=pel.id_pelanggan', 'left')
            ->where('pm.id_pm', $id_pm)
            ->join('pelanggan cust', 'pm.id_pelanggan=cust.id_pelanggan', 'left')
            ->join('topik_tiket tt', 'pm.id_topik=tt.id_topik', 'left')
            ->where('pm.id_pm', $id_pm)
            ->get()
            ->result();
    }

    /**
     * Get PM dengan where condition
     */
    public function getByWhere($where)
    {
        return $this->db->select('
                            pm.id_pm,
                            pm.kode_pm,
                            pm.log_pm,
                            pm.id_pembuat,
                            pm.id_penerima,
                            pm.id_pelanggan,
                            pm.nama_cp,
                            pm.nomer_cp,
                            pm.id_pihak_ketiga,
                            pm.id_topik,
                            pm.nama_pihak_ketiga,
                            pm.subject,
                            pm.deskripsi,
                            pm.file_pendukung,
                            pm.file_invoice,
                            pm.prioritas,
                            pm.tanggal_pm,
                            pm.waktu_mulai,
                            pm.waktu_selesai,
                            pm.catatan_visit,
                            pm.status_pm,
                            pm.status_data,
                            pm.created_at,
                            pm.updated_at,
                            p.nama as nama_pembuat,
                            p.no_hp as no_hp_pembuat,
                            p.level as level_pembuat,
                            pr.nama as nama_penerima,
                            pr.no_hp as no_hp_penerima,
                            pel.identitas_pelanggan as nama_pihak_ketiga_full,
                            pel.kontak,
                            pel.kota,
                            pel.provinsi,
                            cust.identitas_pelanggan as nama_pelanggan,
                            cust.kontak as kontak_pelanggan,
                            tt.nama as nama_topik
                        ')
            ->from('preventif_maintenance pm')
            ->join('pengguna p', 'pm.id_pembuat=p.pengguna_id', 'left')
            ->join('pengguna pr', 'pm.id_penerima=pr.pengguna_id', 'left')
            ->join('pelanggan pel', 'pm.id_pihak_ketiga=pel.id_pelanggan', 'left')
            ->join('pelanggan cust', 'pm.id_pelanggan=cust.id_pelanggan', 'left')
            ->join('topik_tiket tt', 'pm.id_topik=tt.id_topik', 'left')
            ->where($where)
            ->order_by('pm.created_at', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Get semua PM (untuk datatables)
     */
    public function getAll($where = [])
    {
        $query = $this->db->select('
                            pm.id_pm,
                            pm.kode_pm,
                            pm.subject,
                            pm.tanggal_pm,
                            pm.status_pm,
                            pm.created_at,
                            p.nama as nama_pembuat,
                            p.no_hp as no_hp_pembuat,
                            pr.nama as nama_penerima,
                            COALESCE(pel.identitas_pelanggan, pm.nama_pihak_ketiga) as nama_pihak_ketiga
                        ')
            ->from('preventif_maintenance pm')
            ->join('pengguna p', 'pm.id_pembuat=p.pengguna_id', 'left')
            ->join('pengguna pr', 'pm.id_penerima=pr.pengguna_id', 'left')
            ->join('pelanggan pel', 'pm.id_pihak_ketiga=pel.id_pelanggan', 'left')
            ->where('pm.status_data', 1);

        if (!empty($where)) {
            $query->where($where);
        }

        return $query->order_by('pm.created_at', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Datatable: Get all PM for DataTables server-side
     */
    public function getAllPm()
    {
        $this->db->order_by('pm.created_at', 'DESC');
        return $this->datatables
            ->select('
                pm.id_pm,
                pm.kode_pm,
                pm.created_at,
                pm.subject,
                pm.tanggal_pm,
                pm.status_pm,
                p.nama as nama_pembuat,
                COALESCE(pel.identitas_pelanggan, pm.nama_pihak_ketiga) as nama_pihak_ketiga
            ')
            ->from('preventif_maintenance pm')
            ->join('pengguna p', 'pm.id_pembuat=p.pengguna_id', 'left')
            ->join('pelanggan pel', 'pm.id_pihak_ketiga=pel.id_pelanggan', 'left')
            ->where('pm.status_data = 1')
            ->generate();
    }

    /**
     * Datatable: Get PM created by a specific user (similar to getTiketPembuat)
     */
    public function getPmPembuat($id)
    {
        $this->db->order_by('pm.created_at', 'DESC');
        return $this->datatables
            ->select('
                pm.id_pm,
                pm.kode_pm,
                pm.subject,
                pm.tanggal_pm,
                pm.status_pm,
                p.nama,
                pr.nama as nama_penerima,
                pm.created_at as tgl_terbit
            ')
            ->from('preventif_maintenance pm')
            ->join('pengguna p', 'pm.id_pembuat=p.pengguna_id', 'left')
            ->join('pengguna pr', 'pm.id_penerima=pr.pengguna_id', 'left')
            ->where('pm.status_data = 1')
            ->group_start()
            ->where('pm.id_pembuat', intval($id))
            ->or_where('pm.id_penerima', intval($id))
            ->group_end()
            ->generate();
    }

    /**
     * Datatable: Get PM for a specific pihak ketiga (customer/third party)
     */
    public function getPmPihakKetiga($id)
    {
        $this->db->order_by('pm.created_at', 'DESC');
        return $this->datatables
            ->select('
                pm.id_pm,
                pm.kode_pm,
                pm.subject,
                pm.tanggal_pm,
                pm.status_pm,
                pel.identitas_pelanggan as pihak_ketiga,
                pm.created_at as tgl_terbit
            ')
            ->from('preventif_maintenance pm')
            ->join('pelanggan pel', 'pm.id_pihak_ketiga=pel.id_pelanggan', 'left')
            ->where('pm.status_data = 1')
            ->where('pm.id_pihak_ketiga', intval($id))
            ->generate();
    }

    /**
     * Get PM Detail/History
     */
    public function getDetail($id_pm)
    {
        return $this->db->select('
                            pmd.id_pm_detail,
                            pmd.id_pm,
                            pmd.id_pembuat,
                            pmd.detail,
                            pmd.file_update,
                            pmd.status,
                            pmd.created_at,
                            p.nama as nama_pembuat,
                            p.level as level_pembuat
                        ')
            ->from('preventif_maintenance_detail pmd')
            ->join('pengguna p', 'pmd.id_pembuat=p.pengguna_id', 'left')
            ->where('pmd.id_pm', $id_pm)
            ->order_by('pmd.created_at', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Get last PM code untuk generate kode baru
     */
    public function getLastCode()
    {
        return $this->db->select('id_pm')
            ->order_by('id_pm', 'DESC')
            ->limit(1)
            ->get('preventif_maintenance')
            ->row();
    }

    /**
     * Get count PM tahun ini
     */
    public function getPmKodeId()
    {
        return $this->db->select('COUNT(*) as id_pm')
            ->where('YEAR(created_at)', date('Y'))
            ->get('preventif_maintenance')
            ->row();
    }

    /**
     * Get pihak ketiga (PT/CV) dari pelanggan
     */
    public function getPihakKetiga()
    {
        return $this->db->select('
                            id_pelanggan,
                            identitas_pelanggan as nama,
                            alamat,
                            kota,
                            provinsi
                        ')
            ->from('pelanggan')
            ->where('status', 1)
            ->where('(identitas_pelanggan LIKE "PT%" OR identitas_pelanggan LIKE "CV%")', NULL, FALSE)
            ->order_by('identitas_pelanggan', 'ASC')
            ->get()
            ->result();
    }
    
    // ========================================
    // DELETE
    // ========================================

    /**
     * Soft delete PM
     */
    public function deletePm($id_pm)
    {
        return $this->updatePm($id_pm, ['status_data' => 0]);
    }

    /**
     * Hard delete PM (permanent)
     */
    public function hardDeletePm($id_pm)
    {
        // Delete detail first
        $this->db->where('id_pm', $id_pm);
        $this->db->delete('preventif_maintenance_detail');

        // Delete PM
        $this->db->where('id_pm', $id_pm);
        $this->db->delete('preventif_maintenance');

        return $this->db->affected_rows();
    }
    
    // ========================================
    // HELPER FUNCTIONS
    // ========================================

    /**
     * Generate kode PM
     * Format: NNN/PM/VYM/ROMAN_MONTH/YYYY
     */
    public function generateKodePm()
    {
        $tahun = date('Y');
        $bulan = date('n');

        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        $bulanRomawi = isset($romawi[$bulan]) ? $romawi[$bulan] : '';

        $lastKode = $this->db->select("MAX(CAST(SUBSTRING_INDEX(kode_pm, '/', 1) AS UNSIGNED)) AS nomor")
            ->where('YEAR(created_at)', $tahun)
            ->get('preventif_maintenance')
            ->row();

        $nomor = ($lastKode && !empty($lastKode->nomor)) ? ((int) $lastKode->nomor + 1) : 1;
        $nomorFormatted = str_pad($nomor, 3, '0', STR_PAD_LEFT);

        return "{$nomorFormatted}/PM/VYM/{$bulanRomawi}/{$tahun}";
    }

    /**
     * Get status label
     */
    public function getStatusLabel($status)
    {
        $labels = [
            1 => 'Open',
            2 => 'In Progress',
            3 => 'Closed',
            4 => 'Cancelled'
        ];

        return isset($labels[$status]) ? $labels[$status] : 'Unknown';
    }

    /**
     * Get status badge
     */
    public function getStatusBadge($status)
    {
        $badges = [
            1 => '<span class="badge badge-warning">Open</span>',
            2 => '<span class="badge badge-info">In Progress</span>',
            3 => '<span class="badge badge-success">Closed</span>',
            4 => '<span class="badge badge-danger">Cancelled</span>'
        ];

        return isset($badges[$status]) ? $badges[$status] : '<span class="badge badge-secondary">Unknown</span>';
    }

    /**
     * Count PM by status
     */
    public function countByStatus($status = null, $id_pengguna = null)
    {
        // Reset query builder to ensure clean state
        $this->db->reset_query();

        $this->db->where('status_data', 1);

        if ($status !== null) {
            $this->db->where('status_pm', $status);
        }

        if ($id_pengguna !== null) {
            $this->db->group_start()
                ->where('id_pembuat', intval($id_pengguna))
                ->or_where('id_penerima', intval($id_pengguna))
                ->group_end();
        }

        $count = $this->db->count_all_results('preventif_maintenance');

        return $count;
    }
}
