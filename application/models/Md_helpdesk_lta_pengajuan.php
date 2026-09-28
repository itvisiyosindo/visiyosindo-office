<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Md_helpdesk_lta_pengajuan extends CI_Model
{
    private $table = 'helpdesk_lta_pengajuan';

    public function __construct()
    {
        parent::__construct();
        // ensure helper functions for tanggal are available
        $this->load->helper('tanggal_helper');
        // Table schema is managed via migrations / external SQL (see db/schema_helpdesk_lta_pengajuan.sql)
    }

    // Schema creation removed - use migration or the provided SQL file for production

    public function generateKodePengajuan()
    {
        $tahun = date('Y');
        $perusahaan = grantAccessForPerusahaan();

        $row = $this->db->query(
            "SELECT COUNT(*) AS total FROM {$this->table} WHERE perusahaan = ? AND YEAR(created_at) = ?",
            [$perusahaan, $tahun]
        )->row();

        $urutan = str_pad(((int) ($row->total ?? 0)) + 1, 3, '0', STR_PAD_LEFT);

        return $urutan . '/LTA/HELPDESK/VYM/' . ambil_bulan() . '/' . ambil_tahun();
    }

    public function addPengajuan($data)
    {
        $this->db->insert($this->table, $data);
        return (int) $this->db->insert_id();
    }

    public function updatePengajuan($id, $data)
    {
        $this->db->where('id', (int) $id);
        $this->db->where('perusahaan', grantAccessForPerusahaan());

        return $this->db->update($this->table, $data);
    }

    public function getPengajuanById($id)
    {
        return $this->db->select(
            'l.*, '
            . 'p.nama AS nama_pengaju, p.jabatan AS jabatan_pengaju, p.no_hp AS hp_pengaju, '
            . 'a.nama AS nama_approver, a.jabatan AS jabatan_approver, a.no_hp AS hp_approver'
        )
            ->from($this->table . ' l')
            ->join('pengguna p', 'l.pengguna_id = p.pengguna_id', 'left')
            ->join('pengguna a', 'l.id_approver = a.pengguna_id', 'left')
            ->where('l.id', (int) $id)
            ->where('l.perusahaan', grantAccessForPerusahaan())
            ->where('l.is_active', 1)
            ->get()
            ->row();
    }

    public function getMyPengajuan($pengguna_id, $limit = 100)
    {
        return $this->db->select(
            'l.id, l.kode_pengajuan, l.nominal_udara, l.nominal_darat, l.hari_dinas, l.status, l.created_at, l.updated_at, '
            . 'a.nama AS nama_approver'
        )
            ->from($this->table . ' l')
            ->join('pengguna a', 'l.id_approver = a.pengguna_id', 'left')
            ->where('l.pengguna_id', (int) $pengguna_id)
            ->where('l.perusahaan', grantAccessForPerusahaan())
            ->where('l.is_active', 1)
            ->order_by('l.id', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result();
    }

    public function getApprovalInbox($approver_ids)
    {
        return $this->db->select(
            'l.id, l.kode_pengajuan, l.nominal_udara, l.nominal_darat, l.hari_dinas, l.status, l.created_at, '
            . 'p.nama AS nama_pengaju, p.jabatan AS jabatan_pengaju'
        )
            ->from($this->table . ' l')
            ->join('pengguna p', 'l.pengguna_id = p.pengguna_id', 'left')
            ->where('l.perusahaan', grantAccessForPerusahaan())
            ->where('l.is_active', 1)
            ->where('l.status', 0)
            ->where_in('l.id_approver', array_map('intval', (array) $approver_ids))
            ->order_by('l.created_at', 'DESC')
            ->get()
            ->result();
    }

    public function getApprovalHistory($approver_ids, $limit = 120)
    {
        return $this->db->select(
            'l.id, l.kode_pengajuan, l.nominal_udara, l.nominal_darat, l.hari_dinas, l.status, l.updated_at, '
            . 'p.nama AS nama_pengaju, p.jabatan AS jabatan_pengaju'
        )
            ->from($this->table . ' l')
            ->join('pengguna p', 'l.pengguna_id = p.pengguna_id', 'left')
            ->where('l.perusahaan', grantAccessForPerusahaan())
            ->where('l.is_active', 1)
            ->where_in('l.status', [5, 99])
            ->where_in('l.id_approver', array_map('intval', (array) $approver_ids))
            ->order_by('l.updated_at', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result();
    }

    public function getTotalByStatusForApprover($approver_ids, $status)
    {
        return (int) $this->db
            ->where_in('id_approver', array_map('intval', (array) $approver_ids))
            ->where('status', (int) $status)
            ->where('is_active', 1)
            ->where('perusahaan', grantAccessForPerusahaan())
            ->count_all_results($this->table);
    }

    /**
     * Count submissions for a pengguna grouped by important statuses.
     * Returns array: [ 'pending' => int, 'approved' => int, 'rejected' => int, 'total' => int ]
     */
    public function countByPenggunaPerStatus($pengguna_id)
    {
        $pengguna_id = (int) $pengguna_id;

        $rows = $this->db
            ->select('status, COUNT(*) AS cnt')
            ->from($this->table)
            ->where('pengguna_id', $pengguna_id)
            ->where('perusahaan', grantAccessForPerusahaan())
            ->where('is_active', 1)
            ->group_by('status')
            ->get()
            ->result();

        $result = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'total' => 0,
        ];

        foreach ($rows as $r) {
            $s = (int) $r->status;
            $c = (int) $r->cnt;
            if ($s === 0) {
                $result['pending'] = $c;
            } elseif ($s === 5) {
                $result['approved'] = $c;
            } elseif ($s === 99) {
                $result['rejected'] = $c;
            }
            $result['total'] += $c;
        }

        return $result;
    }
}
