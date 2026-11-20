<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_survey extends CI_Model
{
    protected $table = 'survey_kepuasan';

    public function create_survey($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_by_token($token)
    {
        return $this->db->get_where($this->table, ['token' => $token])->row();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function submit_survey($token, $data)
    {
        $this->db->where('token', $token);
        return $this->db->update($this->table, $data);
    }

    public function update_tindak_lanjut($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function get_all_surveys($filter = [])
    {
        $this->db->select('s.*, p.kota, p.provinsi, p.tipe_bisnis');
        $this->db->from($this->table . ' s');
        $this->db->join('pelanggan p', 'p.id_pelanggan = s.id_pelanggan', 'left');

        if (!empty($filter['status'])) {
            $this->db->where('s.status', $filter['status']);
        }
        if (!empty($filter['status_tindak_lanjut'])) {
            $this->db->where('s.status_tindak_lanjut', $filter['status_tindak_lanjut']);
        }
        if (!empty($filter['tgl_awal'])) {
            $this->db->where('DATE(s.created_at) >=', $filter['tgl_awal']);
        }
        if (!empty($filter['tgl_akhir'])) {
            $this->db->where('DATE(s.created_at) <=', $filter['tgl_akhir']);
        }

        $this->db->order_by('s.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_statistics()
    {
        $total_terkirim = $this->db->count_all($this->table);
        $total_selesai  = $this->db->where('status', 'Selesai')->count_all_results($this->table);
        $total_pending  = $this->db->where('status', 'Pending')->count_all_results($this->table);
        $butuh_tindak_lanjut = $this->db->where('status', 'Selesai')
            ->where('status_tindak_lanjut', 'Belum Ditindaklanjuti')
            ->count_all_results($this->table);

        $row_avg = $this->db->select_avg('rata_rata', 'avg_all')
            ->where('status', 'Selesai')
            ->get($this->table)
            ->row();

        $avg_all = $row_avg && $row_avg->avg_all ? round((float)$row_avg->avg_all, 2) : 0;

        return [
            'total_terkirim'      => $total_terkirim,
            'total_selesai'       => $total_selesai,
            'total_pending'       => $total_pending,
            'avg_all'             => $avg_all,
            'butuh_tindak_lanjut' => $butuh_tindak_lanjut
        ];
    }
}
