<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_komisi extends CI_Model
{
    function getBywhere($where)
    {
        $this->db->where($where);
        return $this->db->get('komisi k')->result();
    }

    public function getById($id)
    {
        return $this->db->select('pengguna.pengguna_id, k.*')
                ->join('pengguna','k.id_komisi=pengguna.id_komisi')
        ->get_where('komisi k', array('k.id_komisi' => $id))->result();
    }

    public function addKomisi($data)
    {
        $this->db->insert('komisi', $data);
    }
}
