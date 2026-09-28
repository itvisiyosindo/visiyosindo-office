<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_pop_penjualan_produk extends CI_Model {

    function add($data)
    {
        $this->db->insert('pop_penjualan_produk', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('pop_penjualan_produk', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('pop_penjualan_produk ppp', array('ppp.id_pop_penjualan_produk' => $id))->result();
    }

    function getByWhere($where="")
    {
        $this->db->where($where);
        return $this->db->get('pop_penjualan_produk ppp')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            ppp.id_pop_penjualan_produk,
            ppp.nama_pop_penjualan_produk,
            ppp.kategori,
            ppp.wilayah,
            ppp.link_download,
            ppp.status
        ')
        ->from('pop_penjualan_produk ppp')
        ->where('ppp.status = 1')
        ->generate();        
    }
}