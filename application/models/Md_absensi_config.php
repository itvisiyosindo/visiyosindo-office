<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_absensi_config extends CI_Model
{
    function get()
    {
        return $this->db->where('perusahaan', grantAccessForPerusahaan())
            ->get('absensi_config')
            ->result();
    }

    function update($data)
    {
        // Update dengan WHERE clause untuk ensure only correct row di update
        $this->db->where('perusahaan', grantAccessForPerusahaan());
        $this->db->update('absensi_config', $data);
    }



    // START Hari Libur

    function addLibur($data)
    {
        $this->db->insert('absensi_config_libur', $data);
    }

    function updateLibur($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('absensi_config_libur', $data);
    }

    function getLibur()
    {
        return $this->db->get('absensi_config_libur')->result();
    }

    function getById($id)
    {
        return $this->db->get_where('absensi_config_libur p', array('p.id' => $id))->result();
    }

    function getAll()
    {
        $this->db->order_by('tgl', 'DESC');
        return $this->datatables
            ->select('
				id,
                tgl,
				ket,
                id as aksi
            ')
            ->from('absensi_config_libur')
            ->generate();
    }
}
