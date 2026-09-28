<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_bank extends CI_Model {

    function add($data)
    {
        $this->db->insert('bank', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('bank', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('bank b', array('b.id_bank' => $id))->result();
    }

    function getByWhere($param="") 
    {
        $this->db->where('b.status', 1);
        return $this->db->get('bank b')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            b.id_bank,
            b.nama_bank,
            b.status,

        ')
        ->from('bank b')
        ->where('b.status = 1')
        ->generate();        
    }
}