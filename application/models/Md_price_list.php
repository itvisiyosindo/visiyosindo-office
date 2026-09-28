<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_price_list extends CI_Model
{

    function add($data)
    {
        $this->db->insert('price_list', $data);
    }

    function update($id, $data)
    {

        $this->db->where('id_price_list', $id);
        $this->db->update('price_list', $data);
    }

    function hapus($id, $data)
    {
        $datahapus = array(
            'status' => 0,
            'alasan' => $data['alasan'],
            'pengguna_id' => $data['pengguna_id']
        );

        $this->db->where('id_price_list', $id);
        $this->db->update('price_list', $datahapus);
    }

    function getById($id)
    {
        return $this->db->get_where('price_list pl', array('pl.id_price_list' => $id))->result();
    }

    function getByWhere($where = "")
    {
        $this->db->where($where);
        return $this->db->get('price_list g')->result();
    }

    function getAll()
    {
        $this->db->order_by('pl.kategori asc', 'pl.nama_price_list asc');

        return $this->datatables
            ->select('  
            pl.id_price_list,
            pl.nama_price_list,
            pl.kategori,
            pl.link_download,
            pl.data_created,
            pl.diskon,
            pl.status,
            pl.pengguna_id,
            pl.data_deleted,
            p.nama
        ')
            ->from('price_list pl')
            ->join('pengguna p', 'p.pengguna_id=pl.pengguna_id')
            ->where('pl.status = 1')
            ->where('pl.jenis = 1')
            ->generate();
    }


    function getAllKatalog()
    {
        $this->db->order_by('pl.kategori asc', 'pl.nama_price_list asc');

        return $this->datatables
            ->select('  
            pl.id_price_list,
            pl.nama_price_list,
            pl.kategori,
            pl.link_download,
            pl.data_created,
            pl.diskon,
            pl.status,
            pl.pengguna_id,
            pl.data_deleted,
            p.nama
        ')
            ->from('price_list pl')
            ->join('pengguna p', 'p.pengguna_id=pl.pengguna_id')
            ->where('pl.status = 1')
            ->where('pl.jenis = 2')
            ->generate();
    }

    // Tambahkan di dalam class Md_price_list
    function getAllEndoscopy()
    {
        $this->db->order_by('pl.kategori asc', 'pl.nama_price_list asc');

        return $this->datatables
            ->select('  
        pl.id_price_list,
        pl.nama_price_list,
        pl.kategori,
        pl.link_download,
        pl.data_created,
        pl.diskon,
        pl.status,
        pl.pengguna_id,
        pl.data_deleted,
        p.nama
    ')
            ->from('price_list pl')
            ->join('pengguna p', 'p.pengguna_id=pl.pengguna_id')
            ->where('pl.status = 1')
            ->where('pl.jenis = 3') // Filter untuk Endoscopy
            ->generate();
    }
}
