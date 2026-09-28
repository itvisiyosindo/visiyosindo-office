<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_kategori_pajak extends CI_Model {

    function add($data)
    {
        $this->db->insert('kategori_pajak', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('kategori_pajak', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('kategori_pajak tbl', array('tbl.id' => $id))->result();
    }

    function getByWhere($param="") 
    {
        $this->db->where('tbl.status', 1);
        return $this->db->get('kategori_pajak tbl')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('
            tbl.id as idKatPajak,
            tbl.kategori as kategori,
            tbl.besaran as besaran,
            tbl.deskripsi as deskripsi
        ')
        ->from('kategori_pajak tbl')
        ->where('tbl.status = 1')
        ->generate();        
    }
}