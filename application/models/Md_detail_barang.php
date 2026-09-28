<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_detail_barang extends CI_Model
{
    function add($data)
    {
        $this->db->insert('detail_barang', $data);
    }

    // function delete($id, $data)
    // {
    //     $this->db->where('id_detail_barang', $id);
    //     $this->db->update('detail_barang', $data);
    // }

    function getByWhere($where){
        $this->db->where('db.status',1);
        return $this->db->get_where('detail_barang db', $where)->result();
    }

    function getById($id){
        return $this->db->get_where('detail_barang db', ['db.id_detail_barang' => $id])->result();
    }

    function updateDetailBarang($id, $data)
    {
        $this->db->where('id_detail_barang', $id);
        $this->db->update('detail_barang', $data);
    }

    function deleteByIdPenerimaanBarang($data, $id)
    {
        $this->db->where('id_penerimaan_barang', $id);
        $this->db->update('detail_barang', $data);
    }

    function getByIdPenerimaanBarang($id)
    {
        $this->db->select('db.*, b.nama_barang, b.kode_barang, sb.nama_satuan');
        $this->db->join('barang b', 'b.id_barang = db.id_barang');
        $this->db->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang');
        $this->db->where('db.status', 1);
        return $this->db->get_where('detail_barang db', ['db.id_penerimaan_barang' => $id])->result();
    }

    function getPenerimaanBarangByIdBarang($id_barang, $id_gudang){
        $this->db->where('pb.id_gudang',$id_gudang);
        $this->db->where('db.id_barang',$id_barang);
        $this->db->where('db.current_stock >', 0);
        $this->db->where('db.status',1);
        $this->db->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang', 'LEFT');
        return $this->db->get('detail_barang db')->result();
    }

    function getNoBatchByIdBarang($id_barang, $id_gudang){
        $this->db->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang');
        $this->db->where('pb.id_gudang', $id_gudang);
        $this->db->where('db.id_barang', $id_barang);
        $this->db->where('db.current_stock >=', 1);
        $this->db->where('db.status', 1);
        return $this->db->get('detail_barang db')->result();
    }

    function stockDetailBaranReduce (){
        
    }

    function getStockDetailBarang($id_detail_barang){
        return $this->db->get_where('detail_barang', ['id_detail_barang' =>$id_detail_barang])->result();
    }

    // function getDetailBarangforGudang()
    // {
    //     return $this->datatables
    //     ->select('  
    //         b.nama_barang,
    //         b.id_barang,
    //         b.id_penerimaan_barang
    //     ')
    //     ->from('detail_barang db')
    //     ->join('barang b', 'b.id_barang = db.id_barang')
    //     ->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang', 'LEFT')
    //     ->group_by('b.nama_barang')
    //     ->where('db.status = 1')
    //     ->generate(); 
    // }

    function getForStockGudang()
    {
        $this->db->select('id_detail_barang, id_barang, qty, id_penerimaan_barang');
        return $this->db->get_where('detail_barang', ['status' => 1])->result();
    }
}
