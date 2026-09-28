<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class md_detail_barang_invoice extends CI_Model
{
    function add($data)
    {
        $this->db->insert('detail_barang_invoice', $data);
    }

    function delete($id, $data)
    {
        $this->db->where('id_detail_barang_invoice', $id);
        $this->db->update('detail_barang_invoice', $data);
    }

    function getByWhere($id)
    {
        return $this->db->get_where('detail_barang_invoice',['id_detail_barang_invoice'=>$id])->result();
    }

    function getByWhere2($where){
        $this->db->where($where);
        return $this->db->get('detail_barang_invoice dbi')->result();
    }

    // function cekByIdDetailBarang($id)
    // {
    //     // echo '<pre>'; print_r( $id );die; echo '</pre>';
    //     $this->db->where('status', 1);
    //     return $this->db->get_where('detail_barang_keluar', ['id_detail_barang' => $id])->result();
    // }

    function updateDetailBaranginvoice($id, $data)
    {
        $this->db->where('id_detail_barang_invoice', $id);
        $this->db->update('detail_barang_invoice', $data);
    }

    function deleteByIdInvoice($id, $data)
    {
        $this->db->where('id_invoice', $id);
        $this->db->update('detail_barang_invoice', $data);
    }

    function getByIdInvoice($id)
    {
        $this->db->select('dbi.*, b.nama_barang, b.kode_barang, sb.nama_satuan');
        $this->db->join('barang b', 'b.id_barang = dbi.id_barang');
        $this->db->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang');
        $this->db->where('dbi.status', 1);
        return $this->db->get_where('detail_barang_invoice dbi', ['dbi.id_invoice' => $id])->result();
    }

    function getByIdInvoiceGrouped($id)
    {
        $this->db->select('dbi.*, b.nama_barang, b.kode_barang, sb.nama_satuan');
        $this->db->group_by('dbi.id_barang');
        $this->db->join('barang b', 'b.id_barang = dbi.id_barang');
        $this->db->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang');
        $this->db->where('dbi.status', 1);
        return $this->db->get_where('detail_barang_invoice dbi', ['dbi.id_invoice' => $id])->result();
    }

    // function getNoBatchByIdBarang($id_barang, $id_gudang){
    //     $this->db->join('pengeluaran_barang pb', 'pb.id_penerimaan_barang = dbi.id_penerimaan_barang');
    //     $this->db->where('dbi.id_gudang', $id_gudang);
    //     $this->db->where('dbi.id_barang', $id_barang);
    //     $this->db->where('dbi.status', 1);
    //     return $this->db->get('detail_barang_keluar dbi')->result();
    // }

    // function getDetailBarangforGudang()
    // {
    //     return $this->datatables
    //     ->select('  
    //         b.nama_barang,
    //         b.id_barang,
    //         b.id_penerimaan_barang
    //     ')
    //     ->from('detail_barang_keluar dbi')
    //     ->join('barang b', 'b.id_barang = dbi.id_barang')
    //     ->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = dbi.id_penerimaan_barang', 'LEFT')
    //     ->group_by('b.nama_barang')
    //     ->where('db.status = 1')
    //     ->generate(); 
    // }

    // function getForStockGudang()
    // {
    //     $this->db->select('id_detail_barang_keluar, id_barang, qty, id_penerimaan_barang');
    //     return $this->db->get_where('detail_barang_keluar', ['status' => 1])->result();
    // }
}
