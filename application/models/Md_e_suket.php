<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_e_suket extends CI_Model {

    function add($data)
    {
        $this->db->insert('e_suket', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('e_suket', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('e_suket', array('id_suket' => $id))->result();
    }

    function getByWhere($where="")
    {
        $this->db->where($where);
        return $this->db->get('e_suket')->result();
    }

    function getAll(){
        return $this->datatables
        ->select('  
            id_suket,
            nama_suket,
            kategori,
            link_download,
            status,
            data_created,
            masa_berlaku_dokumen,
        ')
        ->from('e_suket')
        ->where('status = 1')
        ->generate();        
    }
}
