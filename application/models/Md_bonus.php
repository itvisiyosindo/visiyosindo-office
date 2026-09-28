<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_bonus extends CI_Model {

    // Add
	function addEv($data)
	{
		$this->db->insert('nilai_evaluasi', $data);
	}

    function addGapok($data)
	{
		$this->db->insert('bonus_config', $data);
	}

    function getAllEv()
	{
			//$this->db->order_by('f.created_at', 'DESC'); // urut data terbaru dulu
            $this->db->order_by('p.nama', 'ASC');        // lalu urut abjad nama
			return $this->datatables
					->select('
							f.id,
							f.id_pengguna,
							f.smt,
							f.periode,
							f.nilai,
							p.nama,
							p.no_pegawai,
							p.jabatan as jabatan
							')
        ->from('nilai_evaluasi f')
        ->join('pengguna p', 'f.id_pengguna=p.pengguna_id')
        ->generate();
	}


    
    function getById($id)
    {
        return $this->db->get_where('nilai_evaluasi p', array('p.id' => $id))->result();
    }

    function updateEv($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('nilai_evaluasi', $data);
    }




    function getGapokTahunIni()
    {
        $this->db->select('gapok');
        $this->db->from('bonus_config');
        $this->db->where('YEAR(created_at)', date('Y'));
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $gapok = $query->row()->gapok;
            return !empty($gapok) ? $gapok : 0; // kalau NULL atau kosong → 0
        } else {
            return 0; // kalau tahun ini tidak ada data sama sekali → 0
        }
    }


    function getAllBonus()
    {
        // daftar ID pengguna yang TIDAK ingin ditampilkan
        $excluded_ids = [58, 47, 84, 714, 77, 79, 110, 87, 72, 70, 81, 69, 83, 107, 86, 74, 57, 738, 56, 721, 743, 742];

        // tambahkan kondisi ke Query Builder biasa
        $this->db->where('p.is_active', 1);
        $this->db->where('p.status', 1);
        $this->db->where('p.level !=', 'Administrator');
        $this->db->where_not_in('p.pengguna_id', $excluded_ids);
        $this->db->order_by('p.nama', 'ASC');

        // lalu baru panggil datatables
        return $this->datatables
            ->select('
                p.pengguna_id,
                p.nama,
                p.no_pegawai,
                p.jabatan,
                p.level,
                p.tgl_kontrak,
                p.status,
                p.status_karyawan,
                p.is_active
            ')
            ->from('pengguna p')
            ->generate();
    }




















    










}