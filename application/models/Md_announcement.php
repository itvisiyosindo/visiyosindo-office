<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_announcement extends CI_Model
{
    function getAnnouncement()
    {

        $this->db->select('*');
        $this->db->from('announcements');
        $this->db->join('pengguna', 'pengguna.pengguna_id=announcements.pengguna_id');
        $this->db->order_by("announcements.id", 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    function countPengumuman()
    {
        $this->db->select('count(*) as total');
        return $this->db->get_where('announcements')->result();
    }

    function getAnnouncementLimit($limit = 3)
    {
        $this->db->select('*');
        $this->db->from('announcements');
        $this->db->join('pengguna', 'pengguna.pengguna_id=announcements.pengguna_id');
        $this->db->order_by("announcements.id", 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    function add($data)
    {
        $this->db->insert('announcements', $data);
    }
}
