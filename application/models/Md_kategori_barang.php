<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_kategori_barang extends CI_Model {

    function add($data)
    {
        $this->db->insert('kategori_barang', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('kategori_barang', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('kategori_barang kb', array('kb.id_kategori' => $id))->result();
    }

    function getByWhere($param="") 
    {
        $this->db->where('kb.status', 1);
        return $this->db->get('kategori_barang kb')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            kb.id_kategori,
            kb.nama_kategori,
            kb.status,

        ')
        ->from('kategori_barang kb')
        ->where('kb.status = 1')
        ->generate();        
    }
}