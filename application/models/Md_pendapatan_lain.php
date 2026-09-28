<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_pendapatan_lain extends CI_Model
{
    function getBywhere($where)
    {
        $this->db->where($where);
        return $this->db->get('pendatapan_lain p')->result();
    }

    public function getById($id)
    {
        return $this->db->select('pengguna.pengguna_id, k.*')
                ->join('pengguna','k.id_pendapatan=pengguna.id_pendapatan_lain')
        ->get_where('pendapatan_lain k', array('k.id_pendapatan' => $id))->result();
    }

    public function add_pendapatan_lain($data)
    {
        $this->db->insert('pendapatan_lain', $data);
    }
}
