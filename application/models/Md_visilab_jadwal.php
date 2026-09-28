<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Md_visilab_jadwal extends CI_Model
{
    protected $table = 'visilab_jadwal';
    protected $tableFields = null;

    private function getTableFields()
    {
        if ($this->tableFields === null) {
            $this->tableFields = $this->db->list_fields($this->table);
        }
        return $this->tableFields;
    }

    private function sanitizeDataByExistingFields($data)
    {
        $validFields = array_flip($this->getTableFields());
        return array_intersect_key($data, $validFields);
    }

    public function getById($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function getEventsByYear($year)
    {
        $fields = $this->getTableFields();
        $hasTanggal = in_array('tanggal', $fields, true);
        $hasTanggalMulai = in_array('tanggal_mulai', $fields, true);
        $hasTanggalSelesai = in_array('tanggal_selesai', $fields, true);
        $hasRangeField = $hasTanggalMulai || $hasTanggalSelesai;

        if ($hasTanggalMulai && $hasTanggal) {
            $startExpr = 'COALESCE(vj.tanggal_mulai, vj.tanggal)';
        } elseif ($hasTanggalMulai) {
            $startExpr = 'vj.tanggal_mulai';
        } else {
            $startExpr = 'vj.tanggal';
        }

        if ($hasTanggalSelesai && $hasTanggalMulai && $hasTanggal) {
            $endExpr = 'COALESCE(vj.tanggal_selesai, vj.tanggal_mulai, vj.tanggal)';
        } elseif ($hasTanggalSelesai && $hasTanggalMulai) {
            $endExpr = 'COALESCE(vj.tanggal_selesai, vj.tanggal_mulai)';
        } elseif ($hasTanggalSelesai && $hasTanggal) {
            $endExpr = 'COALESCE(vj.tanggal_selesai, vj.tanggal)';
        } else {
            $endExpr = $startExpr;
        }

        $start = "$year-01-01";
        $end = "$year-12-31";
        $this->db->select('vj.*, ' . $startExpr . ' as event_start, ' . $endExpr . ' as event_end, p.identitas_pelanggan as lokasi_pelanggan_nama, p.alamat as pelanggan_alamat, p.provinsi as pelanggan_provinsi, p.kota as pelanggan_kab_kota, pg.nama as teknisi_nama', false);
        $this->db->from($this->table . ' vj');
        $this->db->join('pelanggan p', 'p.id_pelanggan = vj.lokasi_pelanggan_id', 'left');
        $this->db->join('pengguna pg', 'pg.pengguna_id = vj.teknisi_id', 'left');

        if ($hasRangeField) {
            $rangeCond = '(' . $startExpr . ' <= ' . $this->db->escape($end) . ' AND ' . $endExpr . ' >= ' . $this->db->escape($start) . ')';
            $this->db->where($rangeCond, null, false);
            $this->db->order_by('event_start', 'ASC');
        } else {
            $this->db->where('vj.tanggal >=', $start);
            $this->db->where('vj.tanggal <=', $end);
            $this->db->order_by('vj.tanggal', 'ASC');
        }

        return $this->db->get()->result();
    }

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data = $this->sanitizeDataByExistingFields($data);
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data = $this->sanitizeDataByExistingFields($data);
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }
}
