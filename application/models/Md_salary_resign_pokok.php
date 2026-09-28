<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_salary_resign_pokok extends CI_Model {
    protected $table = 'salary_resign_pokok';

    public function add($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function getById($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function getByPeriode($periode) {
        $this->db->where('periode', trim($periode));
        $this->db->order_by('nama_karyawan', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function getAllForDatatables() {
        if ($this->input->post('filter_month')) {
            $this->db->where('periode', $this->input->post('filter_month'));
        }
        return $this->datatables
            ->select('id, nama_karyawan, no_pegawai, status_karyawan, masa_kerja, hari_kehadiran, gaji_pokok, pendapatan_lain, bpjs_kes, bpjs_tk, potongan_lain, pph21, total_diterima, no_rekening, periode')
            ->from($this->table)
            ->generate();
    }

    public function getTotalsByPeriode($periode) {
        $this->db->select('
            SUM(gaji_pokok) as sum_pokok, 
            SUM(pendapatan_lain) as sum_p_lain,
            SUM(bpjs_kes) as sum_bpjs_kes,
            SUM(bpjs_tk) as sum_bpjs_tk,
            SUM(potongan_lain) as sum_potongan_lain,
            SUM(pph21) as sum_pph21,
            SUM(total_diterima) as sum_total
        ');
        $this->db->where('periode', $periode);
        return $this->db->get($this->table)->row();
    }
}