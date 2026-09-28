<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_kode_barang extends CI_Model {

    function add($data)
    {
        $this->db->insert('kode_barang', $data);
    }

    function delete($where)
    {
        $this->db->delete('kode_barang',$where);
    }

    function getById($id)
    {
        return $this->db->get_where('kode_barang kb', array('kb.id_kode_barang' => $id))->result();
    }

    function getByWhere($where) 
    {
        // $this->db->where('kb.status', 1);
        return $this->db->get_where('kode_barang kb', $where)->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            kb.id_kode_barang,
            kb.kode_barang,
            kb.id_barang,
            b.nama_barang,
            kb.status,

        ')
        ->from('kode_barang kb')
        ->where('kb.status = 1')
        ->join('barang b', 'kb.id_barang = b.id_barang')
        ->generate();        
    }
}