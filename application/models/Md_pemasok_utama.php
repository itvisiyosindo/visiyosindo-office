<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_pemasok_utama extends CI_Model {

    function add($data)
    {
        $this->db->insert('pemasok_utama', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('pemasok_utama', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('pemasok_utama p', array('p.id_pemasok' => $id))->result();
    }

    function getByWhere($param="") 
    {
        $this->db->where('pu.status', 1);
        return $this->db->get('pemasok_utama pu')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            pu.id_pemasok,
            pu.nama_pemasok,
            pu.alamat_pemasok,
            pu.contact,
            pu.status,
            pu.warranty,

        ')
        ->from('pemasok_utama pu')
        ->where('pu.status = 1')
        ->generate();        
    }
}