<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Md_wfa_pengajuan extends CI_Model
{
    private $table = 'wfa_pengajuan';
    private $memverifikasi_user_ids = [29, 58];
    private $menyetujui_user_ids = [744, 69];
    private $memverifikasi_user_id = 29;
    private $menyetujui_user_id = 744;

    public function generateKodePengajuan()
    {
        $tahun = date('Y');
        $perusahaan = grantAccessForPerusahaan();

        $row = $this->db->query(
            "SELECT COUNT(*) AS total FROM {$this->table} WHERE perusahaan = ? AND YEAR(created_at) = ?",
            [$perusahaan, $tahun]
        )->row();

        $urutan = str_pad(((int) ($row->total ?? 0)) + 1, 3, '0', STR_PAD_LEFT);

        return $urutan . '/WFA/HRD/VYM/' . ambil_bulan() . '/' . ambil_tahun();
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

    public function hasPendingPengajuanOnDate($pengguna_id, $tanggal_wfa)
    {
        return $this->db
            ->where('pengguna_id', (int) $pengguna_id)
            ->where('tanggal_wfa', $tanggal_wfa)
            ->where_in('status', [0, 1, 2])
            ->where('is_active', 1)
            ->where('perusahaan', grantAccessForPerusahaan())
            ->count_all_results($this->table) > 0;
    }

    public function getApproverCandidates($exclude_user_id = null)
    {
        // Samakan sumber data approver dengan Md_pengguna::getAllPegawai.
        $system_excluded_ids = [1, 727, 714, 84, 109, 110, 79, 54, 72, 81, 70, 57, 74, 56, 83, 55, 107, 86, 77, 743];
        $excluded_ids = array_values(array_unique(array_merge(
            $system_excluded_ids,
            $this->memverifikasi_user_ids,
            $this->menyetujui_user_ids
        )));

        $this->db->select('pg.pengguna_id, pg.nama, pg.jabatan, pg.short_name, pg.no_hp')
            ->from('pengguna pg')
            ->where('pg.status', 1)
            ->where('pg.is_active', 1)
            ->where_not_in('pg.pengguna_id', $excluded_ids)
            ->order_by('pg.nama', 'ASC');

        if (!empty($exclude_user_id)) {
            $this->db->where('pg.pengguna_id !=', (int) $exclude_user_id);
        }

        return $this->db->get()->result();
    }

    public function getPengajuanById($id)
    {
        return $this->db->select(
            'w.*, '
            . 'p.nama AS nama_pengaju, p.jabatan AS jabatan_pengaju, p.short_name AS short_pengaju, p.no_hp AS hp_pengaju, '
            . 'mk.nama AS nama_mengetahui, mk.jabatan AS jabatan_mengetahui, mk.short_name AS short_mengetahui, mk.no_hp AS hp_mengetahui, '
            . 'mv.nama AS nama_memverifikasi, mv.jabatan AS jabatan_memverifikasi, mv.short_name AS short_memverifikasi, mv.no_hp AS hp_memverifikasi, '
            . 'ms.nama AS nama_menyetujui, ms.jabatan AS jabatan_menyetujui, ms.short_name AS short_menyetujui, ms.no_hp AS hp_menyetujui'
        )
            ->from($this->table . ' w')
            ->join('pengguna p', 'w.pengguna_id = p.pengguna_id', 'left')
            ->join('pengguna mk', 'w.id_mengetahui = mk.pengguna_id', 'left')
            ->join('pengguna mv', 'mv.pengguna_id = ' . (int) $this->memverifikasi_user_id, 'left')
            ->join('pengguna ms', 'w.id_menyetujui = ms.pengguna_id', 'left')
            ->where('w.id', (int) $id)
            ->where('w.perusahaan', grantAccessForPerusahaan())
            ->where('w.is_active', 1)
            ->get()
            ->row();
    }

    public function getMyPengajuan($pengguna_id, $limit = 50)
    {
        return $this->db->select(
            'w.id, w.kode_pengajuan, w.tanggal_wfa, w.status, w.created_at, '
            . 'w.status_mengetahui, w.status_menyetujui, mk.nama AS nama_mengetahui, mv.nama AS nama_memverifikasi, ms.nama AS nama_menyetujui'
        )
            ->from($this->table . ' w')
            ->join('pengguna mk', 'w.id_mengetahui = mk.pengguna_id', 'left')
            ->join('pengguna mv', 'mv.pengguna_id = ' . (int) $this->memverifikasi_user_id, 'left')
            ->join('pengguna ms', 'w.id_menyetujui = ms.pengguna_id', 'left')
            ->where('w.pengguna_id', (int) $pengguna_id)
            ->where('w.perusahaan', grantAccessForPerusahaan())
            ->where('w.is_active', 1)
            ->order_by('w.id', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result();
    }

    public function getApprovalInbox($pengguna_id)
    {
        $pengguna_id = (int) $pengguna_id;
        $can_memverifikasi = in_array($pengguna_id, $this->memverifikasi_user_ids, true);
        $can_menyetujui = in_array($pengguna_id, $this->menyetujui_user_ids, true);

        return $this->db->select(
            'w.id, w.kode_pengajuan, w.tanggal_wfa, w.status, w.created_at, '
            . 'p.nama AS nama_pengaju, p.jabatan AS jabatan_pengaju, '
            . 'mk.nama AS nama_mengetahui, mv.nama AS nama_memverifikasi, ms.nama AS nama_menyetujui'
        )
            ->from($this->table . ' w')
            ->join('pengguna p', 'w.pengguna_id = p.pengguna_id', 'left')
            ->join('pengguna mk', 'w.id_mengetahui = mk.pengguna_id', 'left')
            ->join('pengguna mv', 'mv.pengguna_id = ' . (int) $this->memverifikasi_user_id, 'left')
            ->join('pengguna ms', 'w.id_menyetujui = ms.pengguna_id', 'left')
            ->where('w.perusahaan', grantAccessForPerusahaan())
            ->where('w.is_active', 1)
            ->group_start()
                ->group_start()
                    ->where('w.id_mengetahui', $pengguna_id)
                    ->where('w.status_mengetahui', 0)
                    ->where('w.status', 0)
                ->group_end()
                ->or_group_start()
                    ->where('w.status_mengetahui', 1)
                    ->where('w.status_menyetujui', 0)
                    ->where('w.status', 1)
                    ->where($can_memverifikasi ? '1=1' : '1=0', null, false)
                ->group_end()
                ->or_group_start()
                    ->where_in('w.id_menyetujui', $this->menyetujui_user_ids)
                    ->where('w.status_mengetahui', 1)
                    ->where('w.status_menyetujui', 1)
                    ->where('w.status', 2)
                    ->where($can_menyetujui ? '1=1' : '1=0', null, false)
                ->group_end()
            ->group_end()
            ->order_by('w.created_at', 'DESC')
            ->get()
            ->result();
    }

    public function getApprovalHistory($pengguna_id, $limit = 100)
    {
        $pengguna_id = (int) $pengguna_id;
        $can_memverifikasi = in_array($pengguna_id, $this->memverifikasi_user_ids, true);
        $can_menyetujui = in_array($pengguna_id, $this->menyetujui_user_ids, true);

        return $this->db->select(
            'w.id, w.kode_pengajuan, w.tanggal_wfa, w.status, w.created_at, w.updated_at, '
            . 'w.status_mengetahui, w.status_menyetujui, '
            . 'p.nama AS nama_pengaju, mk.nama AS nama_mengetahui, mv.nama AS nama_memverifikasi, ms.nama AS nama_menyetujui'
        )
            ->from($this->table . ' w')
            ->join('pengguna p', 'w.pengguna_id = p.pengguna_id', 'left')
            ->join('pengguna mk', 'w.id_mengetahui = mk.pengguna_id', 'left')
            ->join('pengguna mv', 'mv.pengguna_id = ' . (int) $this->memverifikasi_user_id, 'left')
            ->join('pengguna ms', 'w.id_menyetujui = ms.pengguna_id', 'left')
            ->where('w.perusahaan', grantAccessForPerusahaan())
            ->where('w.is_active', 1)
            ->group_start()
                ->where('w.id_mengetahui', $pengguna_id)
                ->or_group_start()
                    ->where('w.status_mengetahui', 1)
                    ->where('w.status_menyetujui !=', 0)
                    ->where($can_memverifikasi ? '1=1' : '1=0', null, false)
                ->group_end()
                ->or_group_start()
                    ->where_in('w.id_menyetujui', $this->menyetujui_user_ids)
                    ->where($can_menyetujui ? '1=1' : '1=0', null, false)
                ->group_end()
            ->group_end()
            ->where_in('w.status', [5, 99])
            ->order_by('w.updated_at', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result();
    }

    public function getAdminFridaySummary($tanggal_wfa)
    {
        return $this->db
            ->select(
                "COUNT(*) AS total,
                 SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) AS menunggu_mengetahui,
                 SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS menunggu_memverifikasi,
                 SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) AS menunggu_menyetujui,
                 SUM(CASE WHEN status = 5 THEN 1 ELSE 0 END) AS disetujui,
                 SUM(CASE WHEN status = 99 THEN 1 ELSE 0 END) AS ditolak",
                false
            )
            ->from($this->table)
            ->where('tanggal_wfa', $tanggal_wfa)
            ->where('is_active', 1)
            ->where('perusahaan', grantAccessForPerusahaan())
            ->get()
            ->row();
    }

    public function getAdminFridayList($tanggal_wfa)
    {
        return $this->db->select(
            'w.id, w.kode_pengajuan, w.tanggal_wfa, w.status, w.created_at, '
            . 'p.nama AS nama_pengaju, p.jabatan AS jabatan_pengaju, '
            . 'mk.nama AS nama_mengetahui, mv.nama AS nama_memverifikasi, ms.nama AS nama_menyetujui'
        )
            ->from($this->table . ' w')
            ->join('pengguna p', 'w.pengguna_id = p.pengguna_id', 'left')
            ->join('pengguna mk', 'w.id_mengetahui = mk.pengguna_id', 'left')
            ->join('pengguna mv', 'mv.pengguna_id = ' . (int) $this->memverifikasi_user_id, 'left')
            ->join('pengguna ms', 'w.id_menyetujui = ms.pengguna_id', 'left')
            ->where('w.tanggal_wfa', $tanggal_wfa)
            ->where('w.is_active', 1)
            ->where('w.perusahaan', grantAccessForPerusahaan())
            ->order_by('w.created_at', 'DESC')
            ->get()
            ->result();
    }
}
