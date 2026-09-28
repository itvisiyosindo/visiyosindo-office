<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_manual_book extends CI_Model {

    function add($data)
    {
        $this->db->insert('manual_book', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('manual_book', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('manual_book b', array('b.id_manual_book' => $id))->result();
    }

    function getByWhere($where="")
    {
        $this->db->where($where);
        return $this->db->get('manual_book g')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            b.id_manual_book,
            b.nama,
            b.kategori,
            b.link_download,
            b.status,
            b.data_created,

        ')
        ->from('manual_book b')
        ->where('b.status = 1')
        ->generate();        
    }
}