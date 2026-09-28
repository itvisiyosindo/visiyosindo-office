<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_knowledge extends CI_Model
{

    function add($data)
    {
        $this->db->insert('product_knowledge', $data);
    }

    public function getAllKnowledge($tahun = null)
    {
        if ($tahun === null) {
            $tahun = date('Y'); // default: tahun sekarang
        }

        $this->db->select('
            p.pengguna_id,
            p.nama,
            MONTH(k.bulan) AS bulan,
            k.nilai,
            k.id
        ');
        $this->db->from('product_knowledge k');
        $this->db->join('pengguna p', 'p.pengguna_id = k.id_pengguna');
        $this->db->where('YEAR(k.bulan)', $tahun);
        $this->db->order_by('p.nama', 'ASC');
        $query = $this->db->get();
        $result = $query->result();

        // Olah data menjadi format per pengguna dengan nilai per bulan
        $data = [];
        foreach ($result as $row) {
            if (!isset($data[$row->pengguna_id])) {
                $data[$row->pengguna_id] = [
                    'id' => $row->pengguna_id, // simpan ID pengguna
                    'nama' => $row->nama,
                    'nilai' => array_fill(1, 12, null) // isi bulan 1-12 dengan null
                ];
            }
            $data[$row->pengguna_id]['nilai'][$row->bulan] = $row->nilai;
        }

        return $data;
    }



    function getAllDetail($id)
    {
        $this->db->order_by('bulan', 'DESC');
        return $this->datatables
            ->select('
				id,
                id_pengguna,
				nilai,
				bulan,
				created_at
            ')
            ->from('product_knowledge')
            ->where('id_pengguna', $id)
            ->generate();
    }

    function getById($id)
    {
        return $this->db->get_where('product_knowledge p', array('p.id' => $id))->result();
    }












}
