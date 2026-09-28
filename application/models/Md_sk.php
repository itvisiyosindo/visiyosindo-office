<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_sk extends CI_Model {

    function add($data)
    {
        $this->db->insert('sk_penghasilan', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('sk_penghasilan', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('sk_penghasilan b', array('b.id' => $id))->result();
    }

    function getByWhere($where="")
    {
        $this->db->where($where);
        return $this->db->get('sk_penghasilan g')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            b.id,
            b.nama,
            b.tanggal,
            b.link,
            b.status,
            b.created_at,

        ')
        ->from('sk_penghasilan b')
        ->where('b.status = 1')
        ->generate();        
    }
}