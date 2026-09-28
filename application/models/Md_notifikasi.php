<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_notifikasi extends CI_Model {
    function countMasuk($pengguna_id) {
        $this->db->select('COUNT(*) as total');
        $this->db->from('notifikasipengguna');
        $this->db->where('id_pengguna',$pengguna_id);
        $this->db->where('status',0);
        $this->db->where('DATE_ADD(data_created, INTERVAL 7 DAY) >= CURDATE()');
        return $this->db->get()->result()[0]->total;
    }
     function countMasukAdmin() {
        $this->db->select('COUNT(*) as total');
        $this->db->from('notifikasipengguna');
        $this->db->where('status',0);
        $this->db->where('DATE_ADD(data_created, INTERVAL 7 DAY) >= CURDATE()');
        return $this->db->get()->result()[0]->total;
    }   
    function notifikasimasuk($pengguna_id) {
        $this->db->order_by('notifikasipengguna.id', 'desc');
        $this->db->select('notifikasipengguna.*,d.nama as dari,k.nama as kepada');
        $this->db->from('notifikasipengguna');
        $this->db->where('notifikasipengguna.id_pengguna',$pengguna_id);
        $this->db->where('notifikasipengguna.status',0);
        $this->db->where('DATE_ADD(notifikasipengguna.data_created, INTERVAL 7 DAY) >= CURDATE()');
        $this->db->join('pengguna d','d.pengguna_id=notifikasipengguna.idpenggunaakses');
        $this->db->join('pengguna k','k.pengguna_id=notifikasipengguna.id_pengguna');
        return $this->db->get()->result();
    }   
    function notifikasimasukadmin() {
        $this->db->order_by('notifikasipengguna.id', 'desc');
        $this->db->select('notifikasipengguna.*,d.nama as dari,k.nama as kepada');
        $this->db->from('notifikasipengguna');
        $this->db->where('notifikasipengguna.status',0);
        $this->db->where('DATE_ADD(notifikasipengguna.data_created, INTERVAL 7 DAY) >= CURDATE()');
        $this->db->join('pengguna d','d.pengguna_id=notifikasipengguna.idpenggunaakses');
        $this->db->join('pengguna k','k.pengguna_id=notifikasipengguna.id_pengguna');
        return $this->db->get()->result();
    }   
    



   function getNotifikasiPenggunaById($pengguna_id) {
        $this->db->order_by('notifikasipengguna.id', 'asc');
        $where = array('notifikasipengguna.id_pengguna' => $pengguna_id);    
        return $this->db
             ->select('
				notifikasipengguna.id,
                notifikasipengguna.jenis as idjenis,
                masterjenisnotifikasi.keterangan as jenis,
                notifikasipengguna.idpenggunaakses,
				notifikasipengguna.id_pengguna,
				notifikasipengguna.keterangan,
				notifikasipengguna.link,
				notifikasipengguna.status,
				notifikasipengguna.nohp,
				notifikasipengguna.email,
                notifikasipengguna.data_created
            ')
            ->join('masterjenisnotifikasi','masterjenisnotifikasi.id = notifikasipengguna.jenis')
            ->get_where('notifikasipengguna', $where);    
    }

    function getNotifikasiById($id) {
        return $this->db->get_where('notifikasi', array('notifikasi_id' => $id))->result();
    }
    function getNotifikasiByData($dt){
        return $this->db->get_where('notifikasi',$dt)->result();
    }

    function updateNotifikasi($id, $data) {
        $this->db->where('notifikasi_id', $id);
        $this->db->update('notifikasi', $data);
    }   

    function updateNotifikasiByPenggunaId($id, $data) {
        $this->db->where('pengguna_id', $id);
        $this->db->update('notifikasi', $data);
    }  
    function updateNotifikasiByAnggotaId($id, $data) {
        $this->db->where('anggota_id', $id);
        $this->db->update('notifikasi', $data);
    }  
    function getTop10NotifikasiBelumBaca() {
        $this->db->select('*');
        $this->db->from('notifikasi');
        if($this->session->userdata('login_type')=='Anggota')
            $this->db->where('anggota_id',sessAnggotaId());
        else 
            $this->db->where('pengguna_id',sessPenggunaId());
        $this->db->limit(10);
        $this->db->where('baca',NULL);
        $this->db->order_by('notifikasi_id','desc');
        return $this->db->get()->result();
    }

    function countNotifikasiBelumBaca() {
        $this->db->select('COUNT(*) as total');
        $this->db->from('notifikasi');
        if($this->session->userdata('login_type')=='Anggota')
            $this->db->where('anggota_id',sessAnggotaId());
        else 
            $this->db->where('pengguna_id',sessPenggunaId());

        $this->db->where('baca',NULL);
        return $this->db->get()->result();
    }   

    function addNotifikasi($data){
        $this->db->insert('notifikasi', $data);
    }

    function getAllNotifikasi(){
        $q = '';
        if(isAnggota())
            $this->datatables->where('ntf.anggota_id',sessAnggotaId());
        else
            $this->datatables->where('ntf.pengguna_id',sessPenggunaId());

        return $this->datatables
        ->select('  
            ntf.notifikasi_id,
            (
                CASE 
                    WHEN ntf.pengguna_id is not null THEN pg.nama 
                    WHEN ntf.anggota_id is not null THEN a.nama
                    ELSE NULL
                END
            ) as nama,
            ntf.judul,
            ntf.keterangan,
            ntf.tgl,
            ntf.baca,
            ntf.link,   
            ntf.status

        ')
        ->from('notifikasi ntf')
        ->join('pengguna pg','pg.pengguna_id = ntf.pengguna_id','left')
        ->join('anggota a','a.anggota_id = ntf.anggota_id','left')
        ->generate();        
    }
}