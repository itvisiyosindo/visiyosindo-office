<?php

use function Complex\sec;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_penerimaan_stok extends CI_Model
{
    function add($data){
        $this->db->insert('penerimaan_stok', $data);
    }

    function update($id, $data){
        $this->db->where('id_penerimaan_stok', $id);
        $this->db->update('penerimaan_stok', $data);
    }

    function getById($id) {
        return $this->db->get_where('penerimaan_stok', ['id_penerimaan_stok' => $id])->result();
    }

    function getByWhere($where){
        $this->db->select('ps.*,g1.nama_gudang as gudang_asal,g1.alamat_gudang as alamat_gudang_asal,  g2.nama_gudang as gudang_tujuan, g2.alamat_gudang as alamat_gudang_tujuan');
        $this->db->where('ps.status', 1);
        $this->db->join('pengiriman_stok pns', 'pns.id_pengiriman_stok = ps.id_pengiriman_stok');
        $this->db->join('gudang g1', 'g1.id_gudang = pns.id_gudang_asal');
        $this->db->join('gudang g2', 'g2.id_gudang = pns.id_gudang_tujuan');
        return $this->db->get_where('penerimaan_stok ps', $where)->result();
    }

    function getAll()
    {
        if ($this->input->post('filter_month'))
        $this->datatables->where("DATE_FORMAT(ps.tgl_penerimaan_stok,'%Y-%m')", $this->input->post('filter_month'));

        if ($this->input->post('filter_gudang_asal'))
        $this->datatables->where('pst.id_gudang_asal', decrypt($this->input->post('filter_gudang_asal')));

        if ($this->input->post('filter_gudang_tujuan'))
        $this->datatables->where('pst.id_gudang_tujuan', decrypt($this->input->post('filter_gudang_tujuan')));

        return $this->datatables
        ->select(' 
            ps.id_penerimaan_stok, 
            ps.no_penerimaan_stok,
            ps.tgl_penerimaan_stok,
            ps.keterangan,
            ps.status,
            pst.id_gudang_asal,
            pst.id_gudang_tujuan,
            g.nama_gudang as gudang_asal,
            g2.nama_gudang as gudang_tujuan,
        ')
        
        ->from('penerimaan_stok ps')
        ->join('pengiriman_stok pst', 'pst.id_pengiriman_stok = ps.id_pengiriman_stok', 'LEFT')
        ->join('gudang g', 'pst.id_gudang_asal = g.id_gudang', 'LEFT')
        ->join('gudang g2', 'pst.id_gudang_tujuan = g2.id_gudang', 'LEFT')
        ->where('ps.status = 1')
        ->generate();
    }
}
