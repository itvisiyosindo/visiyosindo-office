<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_acc_bukti_lapor_pajak extends CI_Model {

    function add($data)
    {
        $this->db->insert('acc_bukti_lapor_pajak', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('acc_bukti_lapor_pajak', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('acc_bukti_lapor_pajak b', array('b.id' => $id))->result();
    }

    function getByWhere($where="")
    {
        $this->db->where($where);
        return $this->db->get('acc_bukti_lapor_pajak g')->result();
    }

    function getAll($kategori, $filters = []){
        $this->datatables
        ->select('  
            b.id,
            b.kategori,
            b.nama_dokumen,
            b.tahun_pelaporan,
            b.tanggal_lapor,
            b.batas_akhir,
            b.link_dokumen,
            b.status,
            b.created_at
        ')
        ->from('acc_bukti_lapor_pajak b')
        ->where('b.status = 1')
        ->where('b.kategori', $kategori);

        if (!empty($filters['tahun_pelaporan'])) {
            $this->datatables->where('b.tahun_pelaporan', intval($filters['tahun_pelaporan']));
        }

        return $this->datatables->generate();        
    }
}
