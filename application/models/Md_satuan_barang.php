<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_satuan_barang extends CI_Model {

    function add($data)
    {
        $this->db->insert('satuan_barang', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('satuan_barang', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('satuan_barang sb', array('sb.id_satuan' => $id))->result();
    }

    function getByWhere($param="") 
    {
        $this->db->where('sb.status', 1);
        return $this->db->get('satuan_barang sb')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            sb.id_satuan,
            sb.nama_satuan,
            sb.status,

        ')
        ->from('satuan_barang sb')
        ->where('sb.status = 1')
        ->generate();        
    }
}