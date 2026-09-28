<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_tiket_detail extends CI_Model
{

    function getBywhere($where)
    {   $this->db->select('
                            td.id_tiket,
                            td.id_detail_tiket,
                            td.respon,
                            td.file_pendukung,
                            td.data_created,
                            p1.nama as nama,
                            td.id_user_respon,
                        ')
                 ->from('tiket_detail td')
                 ->join('pengguna p1','td.id_user_respon=p1.pengguna_id')
                 ->where($where)
                 ->order_by('td.data_created', 'DESC');
        return $this->db->get()->result();
    }

    function updateByWhere($where, $data){
        $this->db->where($where);
        $this->db->update('topik_tiket', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('tiket_detail td', array('td.id_detail_tiket' => $id))->result();
    }

    function updateRespon($id, $data)
    {
        $this->db->where('id_detail_tiket', $id);
        $this->db->update('tiket_detail', $data);
    }

    function addRespon($data)
    { 
        $this->db->insert('tiket_detail', $data);

    }   

    function getAllTiket()
    {
        $this->db->order_by('t.created_at', 'DESC');
        return $this->datatables
            ->select('
                t.id_tiket,
                tt.nama as nama_topik,
                t.subject,
                t.prioritas,
                p.nama,
                t.status_tiket,
                p.level
            ')
            ->from('tiket t')
            ->join('topik_tiket tt','t.id_topik=tt.id_topik')
            ->join('pengguna p','t.id_penerima=p.pengguna_id')
            ->where('t.status_data = 1')
            ->generate();
    }
    
}
