<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_syarat_pembayaran extends CI_Model {

    function add($data)
    {
        $this->db->insert('syarat_pembayaran', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('syarat_pembayaran', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('syarat_pembayaran sp', array('sp.id_syarat_pembayaran' => $id))->result();
    }

    function getByWhere($param="") 
    {
        $this->db->where('sp.status', 1);
        return $this->db->get('syarat_pembayaran sp')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            sp.id_syarat_pembayaran,
            sp.nama_syarat_pembayaran,
            sp.status,

        ')
        ->from('syarat_pembayaran sp')
        ->where('sp.status = 1')
        ->generate();        
    }
}