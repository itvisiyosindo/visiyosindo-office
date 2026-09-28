<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_detail_package_mesin extends CI_Model {

    function add($data)
    {
        $this->db->insert('detail_package_mesin', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('detail_package_mesin', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('detail_package_mesin dpm', array('dpm.id_dpm' => $id))->result();
    }

    function getByWhere($where="")
    {
        $this->db->where($where);
        return $this->db->get('detail_package_mesin dpm')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            dpm.id_dpm,
            dpm.nama_package,
            dpm.deskripsi,
            dpm.link_gd,
            dpm.status
        ')
        ->from('detail_package_mesin dpm')
        ->where('dpm.status = 1')
        ->generate();        
    }
}