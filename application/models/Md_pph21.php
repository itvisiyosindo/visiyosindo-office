<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_pph21 extends CI_Model
{
    function getBywhere($where)
    {
        $this->db->where($where);
        return $this->db->get('pph21 p')->result();
    }

    public function getById($id)
    {
        return $this->db->select('pengguna.pengguna_id, k.*')
                ->join('pengguna','k.id_pph21=pengguna.id_pph21')
        ->get_where('pph21 k', array('k.id_pph21' => $id))->result();
    }

    public function add_pph21($data)
    {
        $this->db->insert('pph21', $data);
    }
}
