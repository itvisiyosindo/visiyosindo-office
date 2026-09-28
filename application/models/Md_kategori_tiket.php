<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_kategori_tiket extends CI_Model
{

    function getBywhere($where)
    {
        $this->db->where($where);
        $this->db->order_by('t.nama', 'ASC');
        return $this->db->get('topik_tiket t')->result();
    }

    function updateByWhere($where, $data)
    {
        $this->db->where($where);
        $this->db->update('topik_tiket', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('topik_tiket t', array('t.id_topik' => $id))->result();
    }

    function updateKategori($id, $data)
    {
        $this->db->where('id_topik', $id);
        $this->db->update('topik_tiket', $data);
    }

    function addKategori($data)
    {
        $this->db->insert('topik_tiket', $data);
    }

    function getAllKategori()
    {
        $this->db->order_by('t.nama', 'ASC');
        return $this->datatables
            ->select('t.id_topik,t.nama, t.deskripsi,t.status,t.is_active')
            ->from('topik_tiket t')
            ->where('t.status = 1')
            ->generate();
    }

    function getUsedKategori()
    {
        
    }
}
