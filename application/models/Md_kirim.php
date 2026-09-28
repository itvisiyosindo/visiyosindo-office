<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_kirim extends CI_Model
{

    function getPenggunaMarketing()
    {
        $this->db->from('pengguna pg');
        $this->db->where('pg.id_divisi', 3);
        $this->db->where('pg.is_active', 1);
        // Kecualikan ID tertentu
        $this->db->where_not_in('pg.pengguna_id', [54, 747, 754]);

        return $this->db->get()->result();
    }

    function getKodeId()
    {
        return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('kirim_dokumen', array('YEAR(`created_at`)' => date('Y')))->row();
    }

    function getTrackLastId()
    {
        return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('kirim_dokumen')->row();
    }

    function reset_increment($tabel)
    {
        $this->db->query("ALTER TABLE " . $tabel . " AUTO_INCREMENT = 1");
    }

    function add($data)
    {
        $this->db->insert('kirim_dokumen', $data);
    }

    function addStatus($data)
    {
        $this->db->insert('kirim_status', $data);
    }


    function getAllKirim()
    {
        $searchArray = $this->input->post('search', TRUE);
        $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

        $this->db->select('
            kd.id,
            kd.kode,
            kd.nama_customer,
            kd.alamat,
            kd.pic,
            kd.asal,
            kd.marketing,
            kd.keterangan,
            kd.id_pengguna,
            kd.status_email_tiki,
            kd.status_pickup,
            kd.ekspedisi,
            kd.no_resi,
            kd.link_resi,
            kd.tgl_kirim,
            kd.tgl_sampai,
            kd.created_at,
            (
                SELECT ks.id_status 
                FROM kirim_status ks
                WHERE ks.id_kirim = kd.id
                ORDER BY ks.created_at DESC 
                LIMIT 1
            ) AS id_status
        ');
        $this->db->from('kirim_dokumen kd');

        // 🔍 Global Search
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('kd.kode', $keyword);
            $this->db->or_like('kd.nama_customer', $keyword);
            $this->db->or_like('kd.alamat', $keyword);
            $this->db->or_like('kd.pic', $keyword);
            $this->db->or_like('kd.asal', $keyword);
            $this->db->or_like('kd.marketing', $keyword);
            $this->db->or_like('kd.ekspedisi', $keyword);
            $this->db->or_like('kd.no_resi', $keyword);
            $this->db->group_end();
        }

        // 🔢 Ordering
        $columns = [
            'kd.id',
            'kd.kode',
            'kd.nama_customer',
            'kd.alamat',
            'kd.pic',
            'kd.asal',
            'kd.marketing',
            'kd.ekspedisi',
            'kd.no_resi',
            'kd.tgl_kirim',
            'kd.tgl_sampai',
            'kd.created_at'
        ];
        $orderCol = $columns[0];
        $orderDir = 'DESC';
        if ($this->input->post('order')) {
            $colIdx   = $this->input->post('order')[0]['column'];
            $orderCol = isset($columns[$colIdx]) ? $columns[$colIdx] : $columns[0];
            $orderDir = $this->input->post('order')[0]['dir'] === 'desc' ? 'DESC' : 'ASC';
        }
        $this->db->order_by($orderCol, $orderDir);

        // 📊 Pagination (FIX Undefined index: length)
        $length = $this->input->post('length');
        $start  = $this->input->post('start');
        if ($length !== null && $length != -1) {
            $this->db->limit(intval($length), intval($start ?? 0));
        }

        $data = $this->db->get()->result();

        // Total tanpa filter
        $this->db->reset_query();
        $this->db->select('COUNT(*) as total');
        $this->db->from('kirim_dokumen kd');
        $recordsTotal = $this->db->get()->row()->total;

        // Total dengan filter
        $this->db->reset_query();
        $this->db->select('COUNT(DISTINCT kd.id) as total');
        $this->db->from('kirim_dokumen kd');
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('kd.kode', $keyword);
            $this->db->or_like('kd.nama_customer', $keyword);
            $this->db->or_like('kd.alamat', $keyword);
            $this->db->or_like('kd.pic', $keyword);
            $this->db->or_like('kd.asal', $keyword);
            $this->db->or_like('kd.marketing', $keyword);
            $this->db->or_like('kd.ekspedisi', $keyword);
            $this->db->or_like('kd.no_resi', $keyword);
            $this->db->group_end();
        }
        $recordsFiltered = $this->db->get()->row()->total;

        return [
            'draw' => intval($this->input->post('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];
    }

    function getById($id)
    {
        return $this->db->get_where('kirim_dokumen e', array('e.id' => $id))->result();
    }

    function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('kirim_dokumen', $data);
    }

    function getBywhereID($where)
    {
        $this->db->select('
            kd.id,
            kd.nama_customer,
            kd.alamat,
            kd.kode,
            kd.ekspedisi,
            kd.id_pengguna,
            kd.pic,
            kd.asal,
            kd.marketing,
            kd.keterangan,
            kd.tgl_kirim,
            kd.tgl_sampai,
            kd.no_resi,
            kd.link_resi,
            kd.link_doc,
            kd.created_at
        ')
            ->from('kirim_dokumen kd')
            ->where($where)
            ->order_by('kd.created_at', 'DESC');

        return $this->db->get()->result();
    }

    function getUpdateById($where)
    {
        $this->db->select('
							tu.id,
                            t.bukti_penerima,
                            t.nama_penerima,
                            t.tgl_penerima,
                            t.id_kirim,
                            t.id_status,
                            t.keterangan_konfirmasi,
                            t.created_at,
                            p1.nama as nama_pembuat
                        ')
            ->from('kirim_status t')
            ->join('kirim_dokumen tu', 't.id_kirim=tu.id')
            ->join('pengguna p1', 't.id_pengguna=p1.pengguna_id')
            ->where($where)
            ->order_by('t.created_at', 'DESC');
        return $this->db->get()->result();
    }

    function getAllEkspedisi()
    {
        $this->db->where('status', 1);
        $this->db->order_by('k.nama_ekspedisi', 'ASC');
        return $this->db->get('ekspedisi k')->result();
    }

    function getKirimByTgl($tglawal, $tglakhir)
    {
        // Subquery: ambil status terakhir per id_kirim
        $subquery = $this->db->select('id_kirim, MAX(created_at) as latest_created_at')
            ->from('kirim_status')
            ->group_by('id_kirim')
            ->get_compiled_select();

        return $this->db
            ->select('
                kd.id,
                kd.kode,
                kd.nama_customer,
                kd.alamat,
                kd.pic,
                kd.asal,
                kd.marketing,
                kd.keterangan,
                kd.link_doc,
                kd.id_pengguna,
                kd.ekspedisi,
                kd.no_resi,
                kd.link_resi,
                kd.tgl_kirim,
                kd.tgl_sampai,
                kd.created_at AS createdAt,
                ks.id_status,
                ks.id_pengguna AS statusUser,
                ks.keterangan_konfirmasi,
                ks.nama_penerima,
                ks.tgl_penerima,
                ks.bukti_penerima,
                ks.created_at AS statusCreatedAt
            ')
            ->from('kirim_dokumen kd')
            ->join('kirim_status ks', 'kd.id = ks.id_kirim')
            ->join("($subquery) latest_ks", 'ks.id_kirim = latest_ks.id_kirim AND ks.created_at = latest_ks.latest_created_at')
            ->where('kd.created_at >=', date('Y-m-d', strtotime($tglawal)))
            ->where('kd.created_at <=', date('Y-m-d', strtotime($tglakhir)))
            ->order_by('kd.id', 'DESC')
            ->get()
            ->result();
    }
}
