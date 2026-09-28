<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_virtual_account extends CI_Model {

    function add($data)
    {
        $this->db->insert('virtual_account', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('virtual_account', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('virtual_account va', array('va.id_virtual_account' => $id))->result();
    }

    function getByWhere($param="") 
    {
        $this->db->where('va.status', 1);
        $this->db->join('bank b', 'b.id_bank = va.id_bank');
        return $this->db->get_where('virtual_account va', $param)->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            va.id_virtual_account,
            va.no_va,
            c.nama_customer,
            b.nama_bank,
            va.id_customer,
            va.id_bank,
            va.status,

        ')
        ->join('customer c', 'c.id_customer = va.id_customer')
        ->join('bank b', 'b.id_bank = va.id_bank')
        ->from('virtual_account va')
        ->where('va.status = 1')
        ->generate();        
    }
}