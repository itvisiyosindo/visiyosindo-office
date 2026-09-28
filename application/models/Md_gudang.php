<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_gudang extends CI_Model {

    function add($data)
    {
        $this->db->insert('gudang', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('gudang', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('gudang g', array('g.id_gudang' => $id))->result();
    }

    function getByWhere($where="")
    {
        $this->db->where($where);
        $this->db->order_by('lokasi', 'ASC');
        $this->db->order_by('data_created', 'ASC');
        return $this->db->get('gudang g')->result();
    }

    function getBywhereInOri($where)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        // Menambahkan kondisi id_gudang dalam daftar (1, 2, 3)
        $this->db->where_in('id_gudang', [1, 2, 5, 3, 7, 10, 6, 11, 4]);
        $this->db->order_by('data_created', 'ASC');
        return $this->db->get('gudang g')->result();
    }


    function getBywhereIn($where)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        // Menambahkan kondisi id_gudang dalam daftar (1, 2, 3)
        $this->db->where_in('id_gudang', [1, 2, 3, 10, 6, 11, 23, 26, 30]);
        $this->db->order_by('data_created', 'ASC');
        return $this->db->get('gudang g')->result();
    }

    function getBywhereIn2($where)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        // Menambahkan kondisi id_gudang dalam daftar (1, 2, 3)
        $this->db->where_in('id_gudang', [1, 2, 3]);
        $this->db->order_by('data_created', 'ASC');
        return $this->db->get('gudang g')->result();
    }


    function getAll(){
            
        return $this->datatables
        ->select('  
            g.id_gudang,
            g.nama_gudang,
            g.penanggung_jawab,
            g.alamat_gudang,
            g.lokasi,
            g.status,

        ')
        ->from('gudang g')
        ->where('g.status = 1')
        ->generate();        
    }
}