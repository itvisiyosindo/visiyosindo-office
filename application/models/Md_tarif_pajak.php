<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_tarif_pajak extends CI_Model
{

    function add($data)
    {
        $this->db->insert('tarif_pajak', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('tarif_pajak', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('tarif_pajak', array('tarif_pajak.id_tarif_pajak' => $id))->result();
    }

    function getByWhere($param = "")
    {
        $this->db->where('status', 1);
        return $this->db->get('tarif_pajak')->result();
    }

    function getAll()
    {

        return $this->datatables
            ->select('  
            tp.id_tarif_pajak,
            tp.nama_pajak,
            tp.persentase,
            tp.status,

        ')
            ->from('tarif_pajak tp')
            ->where('tp.status = 1')
            ->generate();
    }
}
