<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_detail_barang_keluar extends CI_Model
{
    function add($data)
    {
        $this->db->insert('detail_barang_keluar', $data);
    }

    function delete($id, $data)
    {
        $this->db->where('id_detail_barang_keluar', $id);
        $this->db->update('detail_barang_keluar', $data);
    }

    function getByWhere($id)
    {
        return $this->db->get_where('detail_barang_keluar',['id_detail_barang_keluar'=>$id])->result();
    }

    function getByWhere2($where){
        $this->db->select('dbk.*, pb.no_pengiriman');
        $this->db->join('pengeluaran_barang pb', 'pb.id_pengeluaran_barang = dbk.id_pengeluaran_barang', 'LEFT');
        return $this->db->get_where('detail_barang_keluar dbk',$where)->result();
    }

    function cekByIdDetailBarang($id)
    {
        $this->db->where('status', 1);
        return $this->db->get_where('detail_barang_keluar', ['id_detail_barang' => $id])->result();
    }

    function updateDetailBarangKeluar($id, $data)
    {
        $this->db->where('id_detail_barang_keluar', $id);
        $this->db->update('detail_barang_keluar', $data);
    }

    function deleteByIdPengeluaranBarang($id, $data)
    {
        $this->db->where('id_pengeluaran_barang', $id);
        $this->db->update('detail_barang_keluar', $data);
    }

    function getByIdPengeluaranBarang($id)
    {
        // $this->db->select('dbk.*, b.nama_barang, b.kode_barang, sb.nama_satuan, ');
        // $this->db->join('barang b', 'b.id_barang = dbk.id_barang');
        // $this->db->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang');
        // $this->db->where('dbk.status', 1);
        // return $this->db->get_where('detail_barang_keluar dbk', ['dbk.id_pengeluaran_barang' => $id])->result();

        //hasil jadi 14
        $this->db->select('dbk.*,b.nama_barang, b.kode_barang, sb.nama_satuan, db.nie');
        $this->db->join('barang b', 'b.id_barang = dbk.id_barang');
        $this->db->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang');
        $this->db->join('(select * from detail_barang ORDER BY id_detail_barang DESC) db', 'db.id_detail_barang = dbk.id_detail_barang'); //logic
        $this->db->where('dbk.status', 1);
        // $this->db->where('db.id_detail_barang', 4);
        return $this->db->get_where('detail_barang_keluar dbk', ['dbk.id_pengeluaran_barang' => $id])->result();

// $this->db->select('dbk.*, b.nama_barang, b.kode_barang, sb.nama_satuan, db.nie');
// $this->db->from('detail_barang_keluar dbk');
// $this->db->join('barang b', 'b.id_barang = dbk.id_barang');
// $this->db->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang');
// $this->db->join('detail_barang db', 'db.id_barang = dbk.id_barang');
// $this->db->where('dbk.status', 1);
// return $this->db->get_where('detail_barang_keluar dbk', ['dbk.id_pengeluaran_barang' => $id])->result();

// $this->db->select('dbk.*,b.nama_barang, dbk.qty, dbk.no_batch, dbk.exp_date');
// $this->db->from('detail_barang_keluar dbk');
// $this->db->join('detail_barang db', 'b.id_barang = db.id_barang');
// $this->db->join('satuan_barang sb', 'b.id_satuan_barang = sb.id_satuan');
// $this->db->join('detail_barang_keluar dbk', 'db.id_detail_barang = dbk.id_detail_barang');
// $this->db->where('dbk.status', 1);
// return $this->db->get_where('detail_barang_keluar dbk', ['dbk.id_pengeluaran_barang' => $id])->result();

    }

    function getNoBatchByIdBarang($id_barang, $id_gudang){
        $this->db->join('pengeluaran_barang pb', 'pb.id_penerimaan_barang = dbk.id_penerimaan_barang');
        $this->db->where('dbk.id_gudang', $id_gudang);
        $this->db->where('dbk.id_barang', $id_barang);
        $this->db->where('dbk.status', 1);
        return $this->db->get('detail_barang_keluar dbk')->result();
    }

    // function getDetailBarangforGudang()
    // {
    //     return $this->datatables
    //     ->select('  
    //         b.nama_barang,
    //         b.id_barang,
    //         b.id_penerimaan_barang
    //     ')
    //     ->from('detail_barang_keluar dbk')
    //     ->join('barang b', 'b.id_barang = dbk.id_barang')
    //     ->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = dbk.id_penerimaan_barang', 'LEFT')
    //     ->group_by('b.nama_barang')
    //     ->where('db.status = 1')
    //     ->generate(); 
    // }

    function getForStockGudang()
    {
        $this->db->select('id_detail_barang_keluar, id_barang, qty, id_penerimaan_barang');
        return $this->db->get_where('detail_barang_keluar', ['status' => 1])->result();
    }
}
