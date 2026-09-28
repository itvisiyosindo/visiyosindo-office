<?php

use function Complex\sec;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_penerimaan_barang extends CI_Model
{
    function add($data)
    {
        $this->db->insert('penerimaan_barang', $data);
    }

    function getById($id)
    {
        $this->db->select('pb.*, pu.nama_pemasok, g.nama_gudang');
        $this->db->join('pemasok_utama pu', 'pb.id_pemasok = pu.id_pemasok', 'LEFT');
        $this->db->join('gudang g', 'pb.id_gudang = g.id_gudang');
        return $this->db->get_where('penerimaan_barang pb', ['pb.id_penerimaan_barang' => $id])->result();
    }

    function getByWhere($where)
    {
        $this->db->select('pb.*, pu.nama_pemasok, pu.alamat_pemasok, g.nama_gudang');
        $this->db->where('pb.status', 1);
        $this->db->join('pemasok_utama pu', 'pu.id_pemasok = pb.id_pemasok','left');
        $this->db->join('gudang g', 'g.id_gudang = pb.id_gudang','left');
        return $this->db->get_where('penerimaan_barang pb', $where)->result();
    }

    function getForStockGudang()
    {
        $this->db->select('id_penerimaan_barang, id_gudang');
        return $this->db->get_where('penerimaan_barang', ['status' => 1])->result();
    }


    function update($where = "", $data = "")
    {
        $this->db->where($where);
        $this->db->update('penerimaan_barang', $data);
    }

    function getDetailBarangById($id)
    {
        $this->db->select('db.*,b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = db.id_barang', 'LEFT');
        return $this->db->get_where('detail_barang db', ['db.id_detail_barang' => $id])->result();
    }

    function getAll1()
    {
        if ($this->input->post('filter_gudang'))
            $this->datatables->where('pb.id_gudang', decrypt($this->input->post('filter_gudang', TRUE)));

        if ($this->input->post('filter_jenis_penerimaan') == 1) {
            $this->datatables->where('pb.id_gudang_asal IS NOT NULL');
        } else if ($this->input->post('filter_jenis_penerimaan') == 2) {
            $this->datatables->where('pb.id_gudang_asal', NULL);
        }
        
        if ($this->input->post('filter_month'))
        $this->datatables->where("DATE_FORMAT(pb.tgl_masuk,'%Y-%m')", $this->input->post('filter_month'));
        
        if ($this->input->post('filter_nama_pemasok'))
        $this->datatables->where('pb.id_pemasok', decrypt($this->input->post('filter_nama_pemasok', TRUE)));

        return $this->datatables
            ->select(' 
            pb.id_penerimaan_barang, 
            pb.no_terima,
            pb.tgl_masuk,
            pu.nama_pemasok,
            pu.alamat_pemasok,
            g.nama_gudang,
            pb.keterangan,
            pb.status,
        ')
            ->from('penerimaan_barang pb')
            // ->where('pb.id_gudang_asal', NULL)
            ->join('pemasok_utama pu', 'pu.id_pemasok = pb.id_pemasok', 'LEFT')
            ->join('gudang g', 'g.id_gudang = pb.id_gudang', 'LEFT')
            ->where('pb.status = 1')
            ->generate();
    }

    function getAll()
    {
        $searchArray = $this->input->post('search', TRUE);
        $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

        $this->db->select('
            pb.id_penerimaan_barang,
            pb.no_terima,
            pb.tgl_masuk,
            pu.nama_pemasok,
            g.nama_gudang,
            pb.keterangan
        ');
        $this->db->from('penerimaan_barang pb');
        $this->db->join('pemasok_utama pu', 'pu.id_pemasok = pb.id_pemasok', 'LEFT');
        $this->db->join('gudang g', 'g.id_gudang = pb.id_gudang', 'LEFT');
        $this->db->where('pb.status', 1);

        // Filter tambahan
        if ($this->input->post('filter_gudang')) {
            $this->db->where('pb.id_gudang', decrypt($this->input->post('filter_gudang', TRUE)));
        }

        if ($this->input->post('filter_jenis_penerimaan') == 1) {
            $this->db->where('pb.id_gudang_asal IS NOT NULL');
        } else if ($this->input->post('filter_jenis_penerimaan') == 2) {
            $this->db->where('pb.id_gudang_asal', NULL);
        }

        if ($this->input->post('filter_month')) {
            $this->db->where("DATE_FORMAT(pb.tgl_masuk,'%Y-%m')", $this->input->post('filter_month'));
        }

        if ($this->input->post('filter_nama_pemasok')) {
            $this->db->where('pb.id_pemasok', decrypt($this->input->post('filter_nama_pemasok', TRUE)));
        }

        // 🔍 Search keyword
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('pb.no_terima', $keyword);
            $this->db->or_like('pu.nama_pemasok', $keyword);
            $this->db->or_like('g.nama_gudang', $keyword);
            $this->db->or_like('pb.keterangan', $keyword);

            // Tambahkan pencarian di barang detail
            $this->db->or_where("pb.id_penerimaan_barang IN (
                SELECT db.id_penerimaan_barang 
                FROM detail_barang db 
                LEFT JOIN barang b ON b.id_barang = db.id_barang 
                WHERE b.nama_barang LIKE '%" . $this->db->escape_like_str($keyword) . "%'
            )");
            $this->db->group_end();
        }

        // Ordering
        $columns = ['pb.id_penerimaan_barang', 'pb.no_terima', 'pb.tgl_masuk', 'pu.nama_pemasok', 'g.nama_gudang', 'pb.keterangan'];
        $orderCol = $columns[0];
        $orderDir = 'DESC';
        if (isset($_POST['order'][0]['column'])) {
            $colIdx = $_POST['order'][0]['column'];
            $orderCol = isset($columns[$colIdx]) ? $columns[$colIdx] : $columns[0];
            $orderDir = $_POST['order'][0]['dir'] === 'desc' ? 'DESC' : 'ASC';
        }
        $this->db->order_by($orderCol, $orderDir);

        // Pagination
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $data = $this->db->get()->result();

        // Get Total Records
        $this->db->reset_query();
        $this->db->select('COUNT(*) as total');
        $this->db->from('penerimaan_barang pb');
        $this->db->where('pb.status', 1);
        $recordsTotal = $this->db->get()->row()->total;

        // Get Filtered Records
        $this->db->reset_query();
        $this->db->select('COUNT(DISTINCT pb.id_penerimaan_barang) as total');
        $this->db->from('penerimaan_barang pb');
        $this->db->join('pemasok_utama pu', 'pu.id_pemasok = pb.id_pemasok', 'LEFT');
        $this->db->join('gudang g', 'g.id_gudang = pb.id_gudang', 'LEFT');
        $this->db->where('pb.status', 1);
        // ... ulangi filter dan search di atas
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('pb.no_terima', $keyword);
            $this->db->or_like('pu.nama_pemasok', $keyword);
            $this->db->or_like('g.nama_gudang', $keyword);
            $this->db->or_like('pb.keterangan', $keyword);
            $this->db->or_where("pb.id_penerimaan_barang IN (
                SELECT db.id_penerimaan_barang 
                FROM detail_barang db 
                LEFT JOIN barang b ON b.id_barang = db.id_barang 
                WHERE b.nama_barang LIKE '%" . $this->db->escape_like_str($keyword) . "%'
            )");
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


    

    public function getBarangDetailByPenerimaan($id_penerimaan_barang)
{
    return $this->db
        ->distinct() // ✅ Hindari duplikat nama_barang
        ->select('b.nama_barang')
        ->from('detail_barang db')
        ->join('barang b', 'b.id_barang = db.id_barang', 'LEFT')
        ->where('db.id_penerimaan_barang', $id_penerimaan_barang)
        ->where('db.status', 1) // ✅ Hanya ambil data status = 1
        ->get()
        ->result();
}



    //////////////////////////////////////////////////////////////////////////////////////
    //mulai dari sini adalah semua tentang function temp data di form penerimaan barang//
    ////////////////////////////////////////////////////////////////////////////////////
    function getTempData()
    {
        $this->db->select('pbt.*,pu.nama_pemasok, g.nama_gudang');
        $this->db->join('pemasok_utama pu', 'pu.id_pemasok = pbt.id_pemasok', 'LEFT');
        $this->db->join('gudang g', 'g.id_gudang = pbt.id_gudang', 'LEFT');
        return $this->db->get_where('penerimaan_barang_temp pbt', ['pbt.pengguna_id' => sessPenggunaId()])->result();
    }

    function addTempData($data)
    {
        $this->db->insert('penerimaan_barang_temp', $data);
    }

    function updateTempData($where, $data)
    {
        $this->db->where($where);
        $this->db->update('penerimaan_barang_temp', $data);
    }

    function getDetailBarangTempBySess()
    {
        $this->db->select('dbt.*,b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = dbt.id_barang', 'LEFT');
        $this->db->where('dbt.id_penerimaan_barang', NULL);
        return $this->db->get_where('detail_barang_temp dbt', ['dbt.pengguna_id' => sessPenggunaId()])->result();
    }

    function getDetailBarangTempById($id)
    {
        $this->db->select('dbt.*,b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = dbt.id_barang', 'LEFT');
        return $this->db->get_where('detail_barang_temp dbt', ['dbt.id_detail_barang_temp' => $id])->result();
    }

    function getByIdPenerimaanBarang($dt)
    {
        $this->db->select('dbt.*, b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = dbt.id_barang');
        return $this->db->get_where('detail_barang_temp dbt', ['dbt.id_penerimaan_barang' => $dt])->result();
    }

    function deleteDetailBarangTemp($data)
    {
        $this->db->delete('detail_barang_temp', ['id_detail_barang_temp' => $data]);
    }

    function addDetailBarangTemp($data)
    {
        $this->db->insert('detail_barang_temp', $data);
    }

    function updateDetailBarangTemp($where, $data)
    {
        $this->db->where($where);
        $this->db->update('detail_barang_temp', $data);
    }

    function destroyTempData()
    {
        $this->db->where('id_penerimaan_barang', NULL);
        $this->db->delete('detail_barang_temp', ['pengguna_id' => sessPenggunaId()]);
        $this->db->delete('penerimaan_barang_temp', ['pengguna_id' => sessPenggunaId()]);
    }

    function destroyNewTempDetailBarang($id)
    {
        $this->db->where('id_penerimaan_barang', $id);
        $this->db->delete('detail_barang_temp');
    }
    //////////////////
    //End Temp Data//
    ////////////////
}
