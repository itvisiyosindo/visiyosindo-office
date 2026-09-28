<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_brosur extends CI_Model {

    function add($data)
    {
        $this->db->insert('brosur', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('brosur', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('brosur b', array('b.id_brosur' => $id))->result();
    }

    function getByWhere($where="")
    {
        $this->db->where($where);
        return $this->db->get('brosur g')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            b.id_brosur,
            b.nama_brosur,
            b.kategori,
            b.link_download,
            b.status,
            b.data_created,

        ')
        ->from('brosur b')
        ->where('b.status = 1')
        ->generate();        
    }
}