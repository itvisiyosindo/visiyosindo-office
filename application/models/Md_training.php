<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_training extends CI_Model
{

//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
// ADD ----------------------------------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
    function addTraining($data)
    {
        $this->db->insert('training', $data);
    }

    // fungsi reset urutan id pada tabel
	function reset_increment($tabel){
		$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
	}
    function getTrainingKodeId(){
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('training',array('YEAR(`created_at`)' => date('Y')))->row();
	}
    
    


//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
// UPDATE ----------------------------------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    
    function updateTraining($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('training', $data);
    }
    
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
// GET ----------------------------------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
    function getTrainingAll()
    {
            $this->db->order_by('t.created_at', 'DESC');
            return $this->datatables
                ->select('
                    t.id,
                    t.kode_tr,
                    t.id_pengaju,
                    t.nama_training,
                    t.penyelenggara,
                    t.biaya,
                    t.tanggal_mulai,
                    t.tanggal_selesai,
                    t.alasan,
                    t.status,
                    t.file_sertifikat, 
                    p.nama AS namaPengaju,
                    p.jabatan
                ')
                ->from('training t')
                ->join('pengguna p', 't.id_pengaju=p.pengguna_id')
                ->generate();
    }    

    function getTrainingPengaju($id)
    {
        $this->db->order_by('t.created_at', 'DESC');
        return $this->datatables
            ->select('
                t.id,
                t.kode_tr,
                t.id_pengaju,
                t.nama_training,
                t.penyelenggara,
                t.biaya,
                t.tanggal_mulai,
                t.tanggal_selesai,
                t.alasan,
                t.status,
                t.file_sertifikat,
                p.nama AS namaPengaju,
                p.jabatan
            ')
            ->from('training t')
            ->join('pengguna p', 't.id_pengaju=p.pengguna_id')
            ->where('t.id_pengaju', $id)
            ->generate();
    }

    function getTrainingPersetujuan($id)
    {
        $this->db->order_by('t.created_at', 'DESC');
        return $this->datatables
            ->select('
                t.id,
                t.kode_tr,
                t.id_pengaju,
                t.nama_training,
                t.penyelenggara,
                t.biaya,
                t.tanggal_mulai,
                t.tanggal_selesai,
                t.alasan,
                t.status,
                t.id_kepaladivisi,
                t.file_sertifikat,
                p.nama AS namaPengaju,
                p.jabatan
            ')
            ->from('training t')
            ->join('pengguna p', 't.id_pengaju=p.pengguna_id')
            ->where('t.id_kepaladivisi', $id)
            ->generate();
    }


function getBywhere($where)
    {
        $this->db->select('
                t.id,
                t.kode_tr,
                t.id_pengaju,
                t.nama_training,
                t.penyelenggara,
                t.biaya,
				t.tanggal_mulai,
				t.tanggal_selesai,
                t.alasan,
                t.status,
                t.manfaat,
                t.id_kepaladivisi,
                t.ttd_divisi,
                t.ttd_1,
                t.ttd_2,
                t.link_pelatihan,
                t.file_sertifikat,
                t.file_kehadiran,
                t.created_at,
                p.nama AS namaPengaju,
                p.jabatan,
                p.no_pegawai,
                p2.nama AS namadivisi,
                p2.jabatan AS jabatandivisi
                        ')
            ->from('training t')
            ->join('pengguna p', 't.id_pengaju=p.pengguna_id')
            ->join('pengguna p2', 't.id_kepaladivisi=p2.pengguna_id', 'left')
            ->where('t.id', $where)
            ->order_by('t.created_at', 'DESC');
        return $this->db->get()->result();
    }
	
	
	
}
