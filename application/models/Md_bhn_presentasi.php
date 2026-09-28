<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_bhn_presentasi extends CI_Model {

    function add($data)
    {
        $this->db->insert('bhn_presentasi', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('bhn_presentasi', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('bhn_presentasi b', array('b.id_bhn_presentasi' => $id))->result();
    }

    function getByWhere($where="")
    {
        $this->db->where($where);
        return $this->db->get('bhn_presentasi b')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            b.id_bhn_presentasi,
            b.nama_bhn_presentasi,
            b.link_download,
            b.status,
            b.data_created,

        ')
        ->from('bhn_presentasi b')
        ->where('b.status = 1')
        ->generate();        
    }
}