<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_potong_salary extends CI_Model
{

    function addRiwayatPemotonganSalary($data)
    {
        $this->db->insert('riwayat_potong_salary', $data);
    }

    function getLatestPotongan($pengguna_id){
        $this->db->order_by('id_riwayat_potong_salary', 'DESC');
        $this->db->like('data_created', date('Y-m', time()));
        return $this->db->get_where('riwayat_potong_salary', ['pengguna_id' => $pengguna_id])->row();
    }

    function getMonthPotongan($pengguna_id){
        $this->db->like('data_created', date('Y-m', time()));
        $this->db->order_by('data_created', 'DESC');
        return $this->db->get_where('riwayat_potong_salary', ['pengguna_id' => $pengguna_id])->result();
    }

    // public function getRekapById($pengguna_id)
    // {
    //     return $this->datatables
    //     ->select('  
    //     a.id_absensi,
    //     a.pengguna_id,
    //     a.waktu_absen,
    //     a.status_absen,
    //     a.type_absen,
    //     a.latitude,
    //     a.longitude,
    //     a.data_created
    //     ')
    //     ->from('absensi a')
    //     ->where('a.pengguna_id', $pengguna_id)
    //     ->generate();
    // }
}
