<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_video_tutorial extends CI_Model {

    function add($data)
    {
        $this->db->insert('video_tutorial', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('video_tutorial', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('video_tutorial tb', array('tb.id' => $id))->result();
    }

    function getByWhere($where="")
    {
        $this->db->where($where);
        return $this->db->get('video_tutorial tb')->result();
    }

    function getAll()
    {
        return $this->datatables
            ->select('  
                tb.id as id_video,
                tb.nama,
                tb.link,
                tb.status,
                tb.created_at,
                tb.created_by
            ')
            ->from('video_tutorial tb')
            ->where('tb.status = 1')
            ->generate();
    }
}