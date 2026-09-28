<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_acc_sk_ketentuan extends CI_Model {

    function add($data)
    {
        $this->db->insert('acc_sk_ketentuan', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('acc_sk_ketentuan', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('acc_sk_ketentuan b', array('b.id' => $id))->result();
    }

    function getByWhere($where="")
    {
        $this->db->where($where);
        return $this->db->get('acc_sk_ketentuan g')->result();
    }

    function getAll($filters = []){
        $this->datatables
        ->select('  
            b.id,
            b.nama_dokumen,
            b.tanggal_dokumen,
            b.masa_berlaku,
            b.link_dokumen,
            b.status,
            b.created_at
        ')
        ->from('acc_sk_ketentuan b')
        ->where('b.status = 1');

        if (!empty($filters['tahun'])) {
            $this->datatables->where('YEAR(b.tanggal_dokumen)', intval($filters['tahun']));
        }

        if (!empty($filters['status_berlaku'])) {
            if ($filters['status_berlaku'] == 'Aktif') {
                $this->datatables->where('(b.masa_berlaku >= CURDATE() OR b.masa_berlaku IS NULL)');
            } elseif ($filters['status_berlaku'] == 'Kadaluwarsa') {
                $this->datatables->where('b.masa_berlaku < CURDATE()');
                $this->datatables->where('b.masa_berlaku IS NOT NULL');
            } elseif ($filters['status_berlaku'] == 'Seumur_Hidup') {
                $this->datatables->where('b.masa_berlaku IS NULL');
            }
        }

        return $this->datatables->generate();        
    }
}
