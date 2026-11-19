<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_member_visiacademy extends CI_Model
{
    public function getAllMember()
    {
        return $this->db->get('va_member')->result_array();
    }

    public function addMember($data)
    {
        return $this->db->insert('va_member', $data);
    }

    public function addDeal($data)
    {
        return $this->db->insert('va_member_deal', $data);
    }

    public function getDealByMember($id_member)
    {
        return $this->db->where('id_member', $id_member)->get('va_member_deal')->result_array();
    }

    public function getAllDeals()
    {
        return $this->db->select('d.*, m.nama_member')
                        ->from('va_member_deal d')
                        ->join('va_member m', 'm.id_member = d.id_member')
                        ->get()->result_array();
    }
}
