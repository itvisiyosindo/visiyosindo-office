<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_aset extends CI_Model
{


    function getAllAset()
    {
        $searchArray = $this->input->post('search', TRUE);
        $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

        // Subquery untuk ambil id aset_log terbaru per aset
        $subQuery = '(SELECT id_aset, MAX(id) AS max_id FROM aset_log GROUP BY id_aset) latest_log';

        $this->db->select('
            a.id,
            a.kategori,
            a.kode,
            a.nama,
            a.nilai,
            a.tgl_pembelian,
            a.link_pembelian,
            a.link_foto,
            a.keterangan,
            a.dijual,
            a.tgl_dijual,
            a.posisi,
            a.created_at,
            al.id AS log_id,
            al.id_pengguna,
            p.nama AS nama_pengguna
        ');
        $this->db->from('aset a');
        $this->db->join($subQuery, 'a.id = latest_log.id_aset', 'left', false);
        $this->db->join('aset_log al', 'al.id = latest_log.max_id', 'left');
        $this->db->join('pengguna p', 'al.id_pengguna = p.pengguna_id', 'left');

        // Filter search
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('a.kategori', $keyword);
            $this->db->or_like('a.nama', $keyword);
            $this->db->or_like('a.kode', $keyword);
            $this->db->or_like('p.nama', $keyword); // ✅ tambahkan pencarian nama pengguna
            $this->db->group_end();
        }

        // Filter Kategori
        $filter_kategori = $this->input->post('filter_kategori', TRUE);
        if (!empty($filter_kategori)) {
            $this->db->where('a.kategori', $filter_kategori);
        }

        // Default order
        $orderColumn = 'a.kategori, a.nama';
        $orderDir = 'ASC';

        $columns = [
            'a.id',
            'a.kategori',
            'a.kode',
            'a.nama',
            'a.nilai',
            'a.tgl_pembelian',
            'a.link_pembelian',
            'a.link_foto',
            'a.keterangan',
            'a.dijual',
            'a.tgl_dijual',
            'a.posisi',
            'a.created_at'
        ];

        if (isset($_POST['order']) && !empty($_POST['order'])) {
            $colIndex = $_POST['order'][0]['column'];
            $dir = $_POST['order'][0]['dir'];
            if (isset($columns[$colIndex])) {
                $columnName = $columns[$colIndex];
                if (in_array($columnName, ['a.kategori', 'a.nama', 'a.kode', 'p.nama'])) {
                    $orderColumn = $columnName;
                    $orderDir = strtolower($dir) === 'desc' ? 'DESC' : 'ASC';
                }
            }
        }

        $this->db->order_by($orderColumn, $orderDir);

        // Pagination
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $data = $this->db->get()->result();

        // Hitung total records
        $recordsTotal = $this->db->count_all('aset');

        // Hitung filtered records
        $this->db->select('COUNT(*) as count');
        $this->db->from('aset a');
        $this->db->join($subQuery, 'a.id = latest_log.id_aset', 'left', false);
        $this->db->join('aset_log al', 'al.id = latest_log.max_id', 'left');
        $this->db->join('pengguna p', 'al.id_pengguna = p.pengguna_id', 'left');
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('a.kategori', $keyword);
            $this->db->or_like('a.nama', $keyword);
            $this->db->or_like('a.kode', $keyword);
            $this->db->or_like('p.nama', $keyword); // ✅ nama pengguna ikut filter
            $this->db->group_end();
        }

        // Filter Kategori
        if (!empty($filter_kategori)) {
            $this->db->where('a.kategori', $filter_kategori);
        }
        $recordsFiltered = $this->db->get()->row()->count;

        return [
            'draw' => intval($this->input->post('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];
    }


    function getAllAsetOLD()
    {
        $searchArray = $this->input->post('search', TRUE);
        $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

        $this->db->select('a.id, a.kategori, a.kode, a.nama, a.nilai, a.tgl_pembelian, a.link_pembelian, a.link_foto, a.keterangan, a.dijual, a.tgl_dijual, a.posisi, a.created_at');
        $this->db->from('aset a');

        // Search hanya pada kategori, nama, dan kode
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('a.kategori', $keyword);
            $this->db->or_like('a.nama', $keyword);
            $this->db->or_like('a.kode', $keyword);
            $this->db->group_end();
        }

        // Default order
        $orderColumn = 'a.kategori, a.nama';
        $orderDir = 'ASC';

        // Daftar kolom untuk referensi DataTables (urutan harus sama seperti di frontend)
        $columns = [
            'a.id',
            'a.kategori',
            'a.kode',
            'a.nama',
            'a.nilai',
            'a.tgl_pembelian',
            'a.link_pembelian',
            'a.link_foto',
            'a.keterangan',
            'a.dijual',
            'a.tgl_dijual',
            'a.posisi',
            'a.created_at'
        ];

        // Cek order dari DataTables
        if (isset($_POST['order']) && !empty($_POST['order'])) {
            $colIndex = $_POST['order'][0]['column'];
            $dir = $_POST['order'][0]['dir'];
            if (isset($columns[$colIndex])) {
                $columnName = $columns[$colIndex];
                // Hanya izinkan order berdasarkan kategori, nama, dan kode
                if (in_array($columnName, ['a.kategori', 'a.nama', 'a.kode'])) {
                    $orderColumn = $columnName;
                    $orderDir = strtolower($dir) === 'desc' ? 'DESC' : 'ASC';
                }
            }
        }

        $this->db->order_by($orderColumn, $orderDir);

        // Pagination
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $data = $this->db->get()->result();

        // Total records
        $recordsTotal = $this->db->count_all('aset');

        // Filtered records
        $this->db->select('COUNT(*) as count');
        $this->db->from('aset a');
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('a.kategori', $keyword);
            $this->db->or_like('a.nama', $keyword);
            $this->db->or_like('a.kode', $keyword);
            $this->db->group_end();
        }
        $recordsFiltered = $this->db->get()->row()->count;

        return [
            'draw' => intval($this->input->post('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];
    }

    function addAset($data)
    {
        $this->db->insert('aset', $data);
    }

    function getByIdAset($id)
    {
        return $this->db->get_where('aset p', array('p.id' => $id))->result();
    }

    function updateAset($id, $data)
    {
        return $this->db->where('id', $id)->update('aset', $data);
    }

    function getByIdDeleteAset($id)
    {
        return $this->db->get_where('aset p', array('p.id' => $id))->row();
    }

    function deleteAset($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('aset');
    }


    function getAsetBywhere($where)
    {
        return $this->db
            ->select('
                a.id,
                a.kategori,
                a.kode,
                a.nama,
                a.nilai,
                a.tgl_pembelian,
                a.link_pembelian,
                a.link_foto,
                a.keterangan,
                a.dijual,
                a.tgl_dijual,
                a.posisi,
                a.created_at
            ')
            ->from('aset a')
            ->where($where)
            ->get()
            ->row(); // ambil satu baris saja
    }

    function getUpdateById($where)
    {
        $this->db->select('
							al.id,
                            al.id_aset,
                            al.id_pengguna,
                            al.id_sta,
                            al.tanggal,
                            al.created_at,
                            p.nama as nama_pembuat,
                            p.jabatan,
                            p.no_pegawai
                        ')
            ->from('aset_log al')
            ->join('pengguna p', 'al.id_pengguna=p.pengguna_id')
            ->where($where)
            ->order_by('al.created_at', 'DESC');
        return $this->db->get()->result();
    }


    function getWhereKategori()
    {
        $this->db->distinct();
        $this->db->select('kategori');  // Ambil hanya kolom kategori
        return $this->db->get('aset')->result();
    }



    function getAllAsetByKategori($kategori = null)
    {
        $subQuery = '(SELECT id_aset, MAX(id) AS max_id FROM aset_log GROUP BY id_aset) latest_log';

        $this->db->select('
            a.id,
            a.nama AS nama_aset,
            a.kode,
            a.posisi,
            a.kategori,
            al.id AS log_id,
            al.id_pengguna,
            CASE 
                WHEN al.id_pengguna = 58 THEN CONCAT(p.nama, " (STANBY)")
                ELSE p.nama
            END AS nama_pengguna
        ');
        $this->db->from('aset a');
        $this->db->join($subQuery, 'a.id = latest_log.id_aset', 'left', false);
        $this->db->join('aset_log al', 'al.id = latest_log.max_id', 'left');
        $this->db->join('pengguna p', 'al.id_pengguna = p.pengguna_id', 'left');
        $this->db->order_by('a.nama', 'asc');

        if (!empty($kategori)) {
            $this->db->where('a.kategori', $kategori);
        }

        return $this->db->get()->result();
    }

    function getNextAsetLogId()
    {
        $max_id = $this->db->select_max('id')->get('aset_log')->row()->id;
        return ($max_id ? $max_id : 0) + 1;
    }

    function addAsetLog($data)
    {

        $this->db->insert('aset_log', $data);
    }

    function getAsetLogById($id)
    {
        return $this->db->get_where('aset_log al', array('al.id' => $id))->result();
    }

    function updateAsetLog($id, $data)
    {
        return $this->db->where('id', $id)->update('aset_log', $data);
    }

    function deleteAsetLog($id)
    {
        return $this->db->where('id', $id)->delete('aset_log');
    }
}
