<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_cabang extends CI_Model {

    function add($data)
    {
        $this->db->insert('cabang', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('cabang', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('cabang c', array('c.id_cabang' => $id))->result();
    }

    function getByWhere($param="") 
    {
        $this->db->where('c.status', 1);
        return $this->db->get('cabang c')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            c.id_cabang,
            c.nama_cabang,
            c.penanggung_jawab,
            c.alamat_cabang,
            c.status,

        ')
        ->from('cabang c')
        ->where('c.status = 1')
        ->generate();        
    }
}