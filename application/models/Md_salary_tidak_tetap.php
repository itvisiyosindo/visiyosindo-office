<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_salary_tidak_tetap extends CI_Model
{

    public function add($data)
    {
        $this->db->insert('riwayat_salary_tidaktetap', $data);
    }

    public function getByMonth($month, $pengguna_id)
    {
        // $this->db->where("DATE_FORMAT(a.data_created,'%Y-%m')", $month);
        // $this->db->join('absensi a', 'a.id_absensi = rst.id_absensi', 'left');
        // return $this->db->get('riwayat_salary_tidaktetap rst')->result();
        $this->db->where("DATE_FORMAT(a.data_created,'%Y-%m')", $month);
        $this->db->join('absensi a', 'a.id_absensi = rst.id_absensi', 'left');
        return $this->db->get_where('riwayat_salary_tidaktetap rst', array('rst.pengguna_id' => $pengguna_id))->result();
    }

    public function getByPenggunaId($pengguna_id)
    {
        if ($this->input->post('filter_month')) {
            $this->db->where("DATE_FORMAT(a.data_created,'%Y-%m')", $this->input->post('filter_month'));
        } else {
            $this->db->where("DATE_FORMAT(a.data_created,'%m')", date('m') - 1);
        }

        $this->db->join('absensi a', 'a.id_absensi = rst.id_absensi', 'left');
        return $this->db->get_where('riwayat_salary_tidaktetap rst', array('rst.pengguna_id' => $pengguna_id))->result();
    }

    public function getForDetailSalaryTt($pengguna_id)
    {
        if ($this->input->post('filter_month')) {
            $this->datatables->where("DATE_FORMAT(a.data_created,'%Y-%m')", $this->input->post('filter_month'));
        } else {
            $this->datatables->where("DATE_FORMAT(a.data_created,'%m')", date('m') - 1);
        }

        return $this->datatables
            ->select('  
                a.id_absensi,
                a.pengguna_id,
                a.waktu_absen,
                a.status_absen,
                a.type_absen,
                a.latitude,
                a.longitude,
                a.approval,
                a.tanpa_tunjangan,
                a.data_created
                ')
            ->from('absensi a')
            ->where('a.type_absen','masuk')
            ->where('a.pengguna_id', $pengguna_id)
            ->generate();

    }

    public function getOverride($pengguna_id, $month)
    {
        return $this->db->get_where('salary_tidak_tetap_override', [
            'pengguna_id' => $pengguna_id,
            'month' => $month
        ])->row();
    }

    public function getOverridesByMonth($month)
    {
        $res = $this->db->get_where('salary_tidak_tetap_override', ['month' => $month])->result();
        $map = [];
        foreach ($res as $row) {
            $map[$row->pengguna_id] = $row;
        }
        return $map;
    }

    public function saveOverride($data)
    {
        $existing = $this->getOverride($data['pengguna_id'], $data['month']);
        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update('salary_tidak_tetap_override', $data);
        } else {
            $this->db->insert('salary_tidak_tetap_override', $data);
        }
    }

    public function resetOverride($pengguna_id, $month)
    {
        $this->db->where('pengguna_id', $pengguna_id);
        $this->db->where('month', $month);
        $this->db->delete('salary_tidak_tetap_override');
    }

    public function getPenggunaWithSalaryDatatables($excluded_ids = [29])
    {
        $this->db->order_by('pg.nama', 'ASC');
        if (!empty($excluded_ids)) {
            $this->datatables->where('pg.pengguna_id NOT IN (' . implode(',', array_map('intval', $excluded_ids)) . ')');
        }
        return $this->datatables
            ->select('
                pg.pengguna_id,
                pg.nama,
                pg.jabatan,
                pg.status_karyawan,
                pg.email,
                pg.username,
                pg.terima_tunjangan_tt,
                pg.terima_tunjangan_kinerja,
                pg.terima_tunjangan_konsumsi,
                pg.terima_tunjangan_komunikasi,
                pg.terima_tunjangan_transportasi,
                pg.terima_tunjangan_jabatan,
                pg.terima_tunjangan_bbm,
                pg.id_pendapatan_lain,
                pg.id_latestriwayat_salary,
                pg.level,
                pg.status,
                pg.status_approval,
                pg.is_active,
                rs.tunjangan_jabatan,
                rs.tunjangan_kinerja,
                rs.tunjangan_konsumsi,
                rs.tunjangan_komunikasi,
                rs.tunjangan_transportasi,
                rs.tunjangan_bbm,
                rs.pendapatan_lain,
                rs.potongan
            ')
            ->from('pengguna pg')
            ->join('riwayat_salary rs', 'rs.id_riwayat_salary = pg.id_latestriwayat_salary', 'left')
            ->where('pg.status = 1')
            ->where('pg.is_active = 1')
            ->where('pg.pengguna_id != 1')
            ->generate();
    }

    public function getAllPenggunaWithSalaryList($excluded_ids = [29])
    {
        $this->db->select('
            pg.pengguna_id,
            pg.nama,
            pg.jabatan,
            pg.status_karyawan,
            pg.email,
            pg.username,
            pg.terima_tunjangan_tt,
            pg.terima_tunjangan_kinerja,
            pg.terima_tunjangan_konsumsi,
            pg.terima_tunjangan_komunikasi,
            pg.terima_tunjangan_transportasi,
            pg.terima_tunjangan_jabatan,
            pg.terima_tunjangan_bbm,
            pg.id_pendapatan_lain,
            pg.id_latestriwayat_salary,
            pg.level,
            pg.status,
            pg.status_approval,
            pg.is_active,
            rs.tunjangan_jabatan,
            rs.tunjangan_kinerja,
            rs.tunjangan_konsumsi,
            rs.tunjangan_komunikasi,
            rs.tunjangan_transportasi,
            rs.tunjangan_bbm,
            rs.pendapatan_lain,
            rs.potongan
        ');
        $this->db->from('pengguna pg');
        $this->db->join('riwayat_salary rs', 'rs.id_riwayat_salary = pg.id_latestriwayat_salary', 'left');
        $this->db->where('pg.status = 1');
        $this->db->where('pg.is_active = 1');
        $this->db->where('pg.pengguna_id != 1');
        if (!empty($excluded_ids)) {
            $this->db->where_not_in('pg.pengguna_id', $excluded_ids);
        }
        $this->db->order_by('pg.nama', 'ASC');
        return $this->db->get()->result();
    }
}

