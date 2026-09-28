<?php

use function Complex\sec;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_pengiriman_stok extends CI_Model
{
    function add($data)
    {
        $this->db->insert('pengiriman_stok', $data);
    }

    function getByWhere($where)
    {
        $this->db->select('ps.*, rsps.status_pengiriman, g1.nama_gudang as gudang_asal, g2.nama_gudang as gudang_tujuan, g1.alamat_gudang as alamat_gudang_asal,  g2.alamat_gudang as alamat_gudang_tujuan,  e.nama_ekspedisi');
        $this->db->join('riwayat_status_pengiriman_stok rsps', 'rsps.id_latest_riwayatstatus_pengiriman = ps.id_latest_riwayatstatus_pengiriman');
        $this->db->join('gudang g1', 'g1.id_gudang = ps.id_gudang_asal');
        $this->db->join('gudang g2', 'g2.id_gudang = ps.id_gudang_tujuan');
        $this->db->join('ekspedisi e', 'e.id_ekspedisi = ps.id_ekspedisi');
        $this->db->where('ps.status', 1);
        return $this->db->get_where('pengiriman_stok ps', $where)->result();
    }

    function getById($id)
    {
        $this->db->select('ps.*, g.nama_gudang as nama_gudang_asal, g2.nama_gudang as nama_gudang_tujuan, rsps.status_pengiriman, e.nama_ekspedisi');
        $this->db->join('gudang g', 'ps.id_gudang_asal = g.id_gudang');
        $this->db->join('gudang g2', 'ps.id_gudang_tujuan = g2.id_gudang');
        $this->db->join('ekspedisi e', 'e.id_ekspedisi = ps.id_ekspedisi');
        $this->db->join('riwayat_status_pengiriman_stok rsps', 'rsps.id_latest_riwayatstatus_pengiriman = ps.id_latest_riwayatstatus_pengiriman');
        return $this->db->get_where('pengiriman_stok ps', ['ps.id_pengiriman_stok' => $id])->result();
    }

    function update($where="", $data="")
    {   
        $this->db->where($where);
        $this->db->update('pengiriman_stok', $data);
    }

    function getAll()
    {
        if ($this->input->post('filter_month'))
        $this->datatables->where("DATE_FORMAT(ps.tgl_pengiriman,'%Y-%m')", $this->input->post('filter_month'));

        if ($this->input->post('filter_gudang_asal'))
        $this->datatables->where('ps.id_gudang_asal', decrypt($this->input->post('filter_gudang_asal')));

        if ($this->input->post('filter_gudang_tujuan'))
        $this->datatables->where('ps.id_gudang_tujuan', decrypt($this->input->post('filter_gudang_tujuan')));

        if($this->input->post('filter_status_pengiriman') == 1){
            $this->datatables->where_in('rsps.status_pengiriman', ['Diterima Sebagian', 'Belum Diterima']);
        } else if($this->input->post('filter_status_pengiriman') == 2){
            $this->datatables->where('rsps.status_pengiriman', 'Diterima Seluruhnya');
        } else if($this->input->post('filter_status_pengiriman') == 3){
            $this->datatables->where('rsps.status_pengiriman', 'Diterima Sebagian');
        } else if($this->input->post('filter_status_pengiriman') == 4){
            $this->datatables->where('rsps.status_pengiriman', 'Belum Diterima');
        }


        return $this->datatables
            
            ->select(' 
            ps.id_pengiriman_stok,
            ps.no_pemindahan,
            ps.tgl_pengiriman,
            rsps.status_pengiriman,
            ps.no_resi,
            ps.keterangan,
            g.nama_gudang as nama_gudang_asal,
            g2.nama_gudang as nama_gudang_tujuan,

        ')
            ->from('pengiriman_stok ps')
            ->join('gudang g', 'g.id_gudang = ps.id_gudang_asal', 'LEFT')
            ->join('gudang g2', 'g2.id_gudang = ps.id_gudang_tujuan', 'LEFT')
            ->join('riwayat_status_pengiriman_stok rsps', 'rsps.id_latest_riwayatstatus_pengiriman = ps.id_latest_riwayatstatus_pengiriman', 'LEFT')
            ->where('ps.status = 1')
            ->generate();
    }
    //////////////////////////////////////////////////////////////////////////////////////
    //mulai dari sini adalah semua tentang function temp data di form penerimaan barang//
    ////////////////////////////////////////////////////////////////////////////////////
    function getTempData()
    {
        $this->db->select('pst.*, g.nama_gudang as nama_gudang_asal, g2.nama_gudang as nama_gudang_tujuan');
        $this->db->join('gudang g', 'g.id_gudang = pst.id_gudang_asal', 'LEFT');
        $this->db->join('gudang g2', 'g2.id_gudang = pst.id_gudang_tujuan', 'LEFT');
        return $this->db->get_where('pengiriman_stok_temp pst', ['pst.pengguna_id' => sessPenggunaId()])->result();
    }

    function addTempData($data)
    {
        $this->db->insert('pengiriman_stok_temp', $data);
    }

    function updateTempData($where, $data)
    {
        $this->db->where($where);
        $this->db->update('pengiriman_stok_temp', $data);
    }

    function getDetailBarangTempBySess()
    {
        $this->db->select('dbpst.*,b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = dbpst.id_barang', 'LEFT');
        $this->db->where('dbpst.id_pengiriman_stok', NULL);
        return $this->db->get_where('detail_barang_pengiriman_stok_temp dbpst', ['dbpst.pengguna_id' => sessPenggunaId()])->result();
    }

    function getDetailBarangPengirimanStokTempById($id)
    {
        $this->db->select('dbpst.*,b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = dbpst.id_barang', 'LEFT');
        return $this->db->get_where('detail_barang_pengiriman_stok_temp dbpst', ['dbpst.id_detail_barang_pengiriman_stok_temp' => $id])->result();
    }

    function getByIdPengirimanStok($id)
    {
        $this->db->select('dbpst.*, b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = dbpst.id_barang');
        return $this->db->get_where('detail_barang_pengiriman_stok_temp dbpst', ['dbpst.id_pengiriman_stok' => $id])->result();
    }

    function addDetailBarangPengirimanStokTemp($data)
    {
        $this->db->insert('detail_barang_pengiriman_stok_temp', $data);
    }

    function updateDetailBarangPengirimanStokTemp($where, $data)
    {
        $this->db->where($where);
        $this->db->update('detail_barang_pengiriman_stok_temp', $data);
    }

    function deleteDetailBarangTemp($data)
    {
        $this->db->delete('detail_barang_pengiriman_stok_temp', ['id_detail_barang_pengiriman_stok_temp' => $data]);
    }

    function destroyTempData()
    {
        $this->db->where('id_pengiriman_stok', NULL);
        $this->db->delete('detail_barang_pengiriman_stok_temp', ['pengguna_id' => sessPenggunaId()]);
        $this->db->delete('pengiriman_stok_temp', ['pengguna_id' => sessPenggunaId()]);
    }

    function destroyNewTempDetailBarangPengirimanStok($id)
    {
        $this->db->where('id_pengiriman_stok', $id);
        $this->db->delete('detail_barang_pengiriman_stok_temp');
    }
    //////////////////
    //End Temp Data//
    ////////////////
}
