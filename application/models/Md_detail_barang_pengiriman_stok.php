<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class md_detail_barang_pengiriman_stok extends CI_Model
{
    function add($data)
    {
        $this->db->insert('detail_barang_pengiriman_stok', $data);
    }

    function delete($id, $data)
    {
        $this->db->where('id_detail_barang_pengiriman_stok', $id);
        $this->db->update('detail_barang_pengiriman_stok', $data);
    }

    function getByWhere($id)
    {
        $this->db->where('status', 1);
        return $this->db->get_where('detail_barang_pengiriman_stok',['id_detail_barang_pengiriman_stok'=>$id])->result();
    }

    function getByDetailBarang($id){
        $this->db->where('status', 1);
        return $this->db->get_where('detail_barang_pengiriman_stok',['id_detail_barang'=>$id])->result();
    }

    function get($where){
        return $this->db->get_where('detail_barang_pengiriman_stok',$where)->result();
    }

    function getDetailBarangPengirimanStokById($id){
        $this->db->select('dbps.*,b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = dbps.id_barang', 'LEFT');
        $this->db->join('detail_barang db', 'db.id_detail_barang = dbps.id_detail_barang', 'LEFT');
        return $this->db->get_where('detail_barang_pengiriman_stok dbps', ['dbps.id_detail_barang_pengiriman_stok' => $id])->result();
    }

    // function cekByIdDetailBarang($id)
    // {
    //     // echo '<pre>'; print_r( $id );die; echo '</pre>';
    //     $this->db->where('status', 1);
    //     return $this->db->get_where('detail_barang_keluar', ['id_detail_barang' => $id])->result();
    // }

    function updateDetailBarangPengirimanStok($id, $data)
    {
        $this->db->where('id_detail_barang_pengiriman_stok', $id);
        $this->db->update('detail_barang_pengiriman_stok', $data);
    }

    function deleteByIdPengirimanStok($id, $data)
    {
        $this->db->where('id_pengiriman_stok', $id);
        $this->db->update('detail_barang_pengiriman_stok', $data);
    }

    function getByIdPengirimanStok($id)
    {
        // echo '<pre>'; print_r( $id );die; echo '</pre>';
        $this->db->select('dps.*, b.nama_barang, b.kode_barang, s.nama_satuan, db.exp_date');
        $this->db->join('barang b', 'b.id_barang = dps.id_barang');
        $this->db->join('satuan_barang s', 's.id_satuan = b.id_satuan_barang');
        $this->db->join('detail_barang db', 'db.id_detail_barang = dps.id_detail_barang');
        $this->db->where('dps.status', 1);
        return $this->db->get_where('detail_barang_pengiriman_stok dps', ['dps.id_pengiriman_stok' => $id])->result();
    }

    function getByIdPengeluaranBarang($id)
    {
        $this->db->select('dbps.*, b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = dbps.id_barang');
        $this->db->where('dbps.status', 1);
        return $this->db->get_where('detail_barang_pengiriman_stok dbps', ['dbps.id_detail_barang_pengiriman_stok' => $id])->result();
    }


    // function getNoBatchByIdBarang($id_barang, $id_gudang){
    //     $this->db->join('pengeluaran_barang pb', 'pb.id_penerimaan_barang = dbk.id_penerimaan_barang');
    //     $this->db->where('dbk.id_gudang', $id_gudang);
    //     $this->db->where('dbk.id_barang', $id_barang);
    //     $this->db->where('dbk.status', 1);
    //     return $this->db->get('detail_barang_keluar dbk')->result();
    // }

    // function getForStockGudang()
    // {
    //     $this->db->select('id_detail_barang_keluar, id_barang, qty, id_penerimaan_barang');
    //     return $this->db->get_where('detail_barang_keluar', ['status' => 1])->result();
    // }
}
