<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_acc_laporan_keuangan extends CI_Model {

    function add($data)
    {
        $this->db->insert('acc_laporan_keuangan', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('acc_laporan_keuangan', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('acc_laporan_keuangan b', array('b.id' => $id))->result();
    }

    function getByWhere($where="")
    {
        $this->db->where($where);
        return $this->db->get('acc_laporan_keuangan g')->result();
    }

    function getAll($filters = []){
        $this->datatables
        ->select('  
            b.id,
            b.nama_dokumen,
            b.jenis_dokumen,
            b.tahun_pelaporan,
            b.keperluan_dokumen,
            b.link_dokumen,
            b.status,
            b.created_at
        ')
        ->from('acc_laporan_keuangan b')
        ->where('b.status = 1');

        if (!empty($filters['jenis_dokumen'])) {
            $this->datatables->where('b.jenis_dokumen', $filters['jenis_dokumen']);
        }
        if (!empty($filters['keperluan_dokumen'])) {
            $this->datatables->where('b.keperluan_dokumen', $filters['keperluan_dokumen']);
        }
        if (!empty($filters['tahun_pelaporan'])) {
            $this->datatables->where('b.tahun_pelaporan', intval($filters['tahun_pelaporan']));
        }

        return $this->datatables->generate();        
    }
}
