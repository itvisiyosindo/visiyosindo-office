<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_training_teknisi extends CI_Model
{
    function addTraining($data)
    {
        $this->db->insert('training_teknisi', $data);
        return $this->db->insert_id();
    }

    function addUpdateTraining($data)
    {
        $this->db->insert('training_teknisi_update', $data);
    }

    function updateTraining($id, $data)
    {
        $this->db->where('id_training', $id);
        $this->db->update('training_teknisi', $data);
    }

    function getTrainingKodeId()
    {
        return $this->db->select("COUNT(*) as id_training")
            ->limit(1)
            ->order_by('id_training', "DESC")
            ->get_where('training_teknisi', array('YEAR(`created_at`)' => date('Y')))
            ->row();
    }

    function getById($id)
    {
        $this->db->select('
            t.*,
            tt.nama as nama_topik,
            p.nama as nama_teknisi,
            p.no_hp as no_hp_teknisi,
            p1.nama as nama_pembuat,
            p1.no_hp as no_hp_pembuat
        ')
            ->from('training_teknisi t')
            ->join('topik_tiket tt', 't.id_topik = tt.id_topik', 'left')
            ->join('pengguna p', 't.id_teknisi = p.pengguna_id', 'left')
            ->join('pengguna p1', 't.created_by = p1.pengguna_id', 'left')
            ->where('t.id_training', $id);
        return $this->db->get()->result();
    }

    function getUpdateById($where)
    {
        $this->db->select('
            tu.kode_training,
            tu.id_pembuat,
            tu.update,
            tu.status,
            tu.file_update,
            tu.created_at as waktu,
            t.id_training,
            p1.nama as nama_pembuat
        ')
            ->from('training_teknisi t')
            ->join('training_teknisi_update tu', 't.id_training = tu.id_training')
            ->join('pengguna p1', 'tu.id_pembuat = p1.pengguna_id')
            ->where($where)
            ->order_by('tu.created_at', 'DESC');
        return $this->db->get()->result();
    }

    function getAllTraining()
    {
        if ($this->input->post('filter_rekanan')) {
            $this->datatables->like('t.rekanan', $this->input->post('filter_rekanan'));
        }
        if ($this->input->post('filter_subject')) {
            $this->datatables->like('t.subject', $this->input->post('filter_subject'));
        }

        $this->db->order_by('t.id_training', 'DESC');
        return $this->datatables
            ->select('
                t.id_training,
                t.kode_training,
                t.rekanan,
                t.contact_person,
                t.subject,
                t.prioritas,
                t.status_training,
                t.waktu_mulai,
                t.waktu_selesai,
                t.created_at,
                tt.nama as nama_topik,
                p.nama as nama_teknisi,
                p1.nama as nama_pembuat
            ')
            ->from('training_teknisi t')
            ->join('topik_tiket tt', 't.id_topik = tt.id_topik', 'left')
            ->join('pengguna p', 't.id_teknisi = p.pengguna_id', 'left')
            ->join('pengguna p1', 't.created_by = p1.pengguna_id', 'left')
            ->where('t.status_data', 1)
            ->generate();
    }

    function getTrainingPenerima($id_teknisi)
    {
        if ($this->input->post('filter_rekanan')) {
            $this->datatables->like('t.rekanan', $this->input->post('filter_rekanan'));
        }
        if ($this->input->post('filter_subject')) {
            $this->datatables->like('t.subject', $this->input->post('filter_subject'));
        }

        $this->db->order_by('t.id_training', 'DESC');
        return $this->datatables
            ->select('
                t.id_training,
                t.kode_training,
                t.rekanan,
                t.contact_person,
                t.subject,
                t.prioritas,
                t.status_training,
                t.waktu_mulai,
                t.waktu_selesai,
                t.created_at,
                tt.nama as nama_topik,
                p.nama as nama_teknisi,
                p1.nama as nama_pembuat
            ')
            ->from('training_teknisi t')
            ->join('topik_tiket tt', 't.id_topik = tt.id_topik', 'left')
            ->join('pengguna p', 't.id_teknisi = p.pengguna_id', 'left')
            ->join('pengguna p1', 't.created_by = p1.pengguna_id', 'left')
            ->where('t.status_data', 1)
            ->where('t.id_teknisi', $id_teknisi)
            ->generate();
    }
}
