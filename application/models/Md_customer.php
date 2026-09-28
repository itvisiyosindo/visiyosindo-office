<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_customer extends CI_Model {

    function add($data)
    {
        $this->db->insert('customer', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('customer', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('customer c', array('c.id_customer' => $id))->result();
    }

    function getByWhere($param="") 
    {
        $this->db->where('c.status', 1);
        return $this->db->get('customer c')->result();
    }

    function getBySearch()
    {
        $q = $this->input->post('q', TRUE);

        $this->db->like('c.nama_customer', $q, 'both');
        return $this->db->get_where('customer c', ['c.status' => 1])->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            c.id_customer,
            c.nama_customer,
            c.alamat_customer,
            c.contact,
            c.status,

        ')
        ->from('customer c')
        ->where('c.status = 1')
        ->generate();        
    }
}