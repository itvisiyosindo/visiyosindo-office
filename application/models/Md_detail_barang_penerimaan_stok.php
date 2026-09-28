<?php

use function Complex\sec;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_detail_barang_penerimaan_stok extends CI_Model
{
    function add($data)
    {
        $this->db->insert('detail_barang_penerimaan_stok', $data);
    }

    function update($id, $data)
    {
        $this->db->where('id_detail_barang_penerimaan_stok', $id);
        $this->db->update('detail_barang_penerimaan_stok', $data);
    }

    function updateByWhere($where, $data)
    {
        $this->db->where($where);
        $this->db->update('detail_barang_penerimaan_stok', $data);
    }

    function getByWhere($where)
    {
        $this->db->select('dbps.*, b.nama_barang, s.nama_satuan, b.kode_barang, db.exp_date');
        $this->db->where('dbps.status', 1);
        $this->db->join('barang b', 'b.id_barang = dbps.id_barang', 'LEFT');
        $this->db->join('satuan_barang s', 's.id_satuan = b.id_satuan_barang', 'LEFT');
        $this->db->join('detail_barang db', 'db.id_detail_barang = dbps.id_detail_barang_baru', 'LEFT');
        return $this->db->get_where('detail_barang_penerimaan_stok dbps', $where)->result();
    }

    /**
     * Get detail barang by id_penerimaan_stok for Tracking feature
     */
    function getByIdPenerimaanStok($id_penerimaan_stok)
    {
        $this->db->select('dbps.*, b.nama_barang, b.kode_barang, s.nama_satuan, db.exp_date, db.nie');
        $this->db->from('detail_barang_penerimaan_stok dbps');
        $this->db->join('barang b', 'b.id_barang = dbps.id_barang', 'LEFT');
        $this->db->join('satuan_barang s', 's.id_satuan = b.id_satuan_barang', 'LEFT');
        $this->db->join('detail_barang db', 'db.id_detail_barang = dbps.id_detail_barang_baru', 'LEFT');
        $this->db->where('dbps.id_penerimaan_stok', $id_penerimaan_stok);
        $this->db->where('dbps.status', 1);
        return $this->db->get()->result();
    }

    //     function getByWheree($where){
    //     // $this->db->select('dbps.*, b.nama_barang');
    //     $this->db->where('dbps.status', 1);
    //     // $this->db->join('barang b', 'b.id_barang = dbps.id_barang', 'LEFT');
    //     return $this->db->get_where('detail_barang_penerimaan_stok dbps', ['dbps.id_detail_barang_penerimaan_stok' => 19])->result();
    //     echo '<pre>'; print_r( $this->db->last_query() );die; echo '</pre>';
    // }
}
