<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_divisi_pengguna extends CI_Model
{

    function getBywhere($where)
    {
        $this->db->where($where);
        $this->db->order_by('d.nama', 'ASC');
        return $this->db->get('divisi d')->result();
    }

    function updateByWhere($where, $data)
    {
        $this->db->where($where);
        $this->db->update('divisi', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('divisi d', array('d.id_topik' => $id))->result();
    }
    function getByIdPengguna($id)
    {

        $this->db->select('
            d.nama    
            ')
            ->from('divisi d')
            ->join('pengguna p', 'd.id_divisi=p.id_divisi')
            ->where('d.id_divisi', $id)
            ->order_by('d.created_at', 'DESC');
        return $this->db->get()->result();
    }

    function updateDivisi($id, $data)
    {
        $this->db->where('id_topik', $id);
        $this->db->update('divisi', $data);
    }

    function addDivisi($data)
    {
        $this->db->insert('divisi', $data);
    }

    function getAllDivisi()
    {
        $this->db->order_by('d.nama', 'ASC');
        return $this->datatables
            ->select('d.id_divisi,d.nama, d.deskripsi,d.status,d.is_active')
            ->from('divisi d')
            ->where('d.status = 1')
            ->generate();
    }
}
