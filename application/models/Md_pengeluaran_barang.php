<?php

use function Complex\sec;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_pengeluaran_barang extends CI_Model
{
    function add($data)
    {
        $this->db->insert('pengeluaran_barang', $data);
    }

    function getById($id)
    {
        $this->db->select('pb.*, c.nama_customer, g.nama_gudang');
        $this->db->join('customer c', 'pb.id_customer = c.id_customer');
        $this->db->join('gudang g', 'pb.id_gudang = g.id_gudang');
        return $this->db->get_where('pengeluaran_barang pb', ['pb.id_pengeluaran_barang' => $id])->result();
    }

    function getByWhere($where)
    {
        $this->db->select('pb.*, c.nama_customer, c.alamat_customer,c.contact, g.nama_gudang');
        $this->db->where('pb.status', 1);
        $this->db->join('gudang g', 'g.id_gudang = pb.id_gudang');
        $this->db->join('customer c', 'c.id_customer = pb.id_customer');
        return $this->db->get_where('pengeluaran_barang pb', $where)->result();
    }

    function getForStockGudang()
    {
        $this->db->select('id_pengeluaran_barang, id_gudang');
        return $this->db->get_where('pengeluaran_barang', ['status' => 1])->result();
    }


    function update($where = "", $data = "")
    {
        $this->db->where($where);
        $this->db->update('pengeluaran_barang', $data);
    }

    function getDetailBarangKeluarById($id)
    {
        $this->db->select('dbk.*,b.nama_barang, db.id_penerimaan_barang, pb.id_gudang');
        $this->db->join('barang b', 'b.id_barang = dbk.id_barang', 'LEFT');
        $this->db->join('detail_barang db', 'db.id_detail_barang = dbk.id_detail_barang', 'LEFT');
        $this->db->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang', 'LEFT');
        return $this->db->get_where('detail_barang_keluar dbk', ['dbk.id_detail_barang_keluar' => $id])->result();
    }

    function getAll1()
    {
        if ($this->input->post('filter_gudang'))
            $this->datatables->where('pb.id_gudang', decrypt($this->input->post('filter_gudang', TRUE)));

        if ($this->input->post('filter_invoice')) {
            if ($this->input->post('filter_invoice') == 'from_invoice') {
                $this->datatables->where('pb.from_invoice is NOT NULL', NULL, FALSE);
            } else if ($this->input->post('filter_invoice') == 'ready_invoice') {
                $this->datatables->where('pb.id_invoice is NOT NULL', NULL, FALSE);
            } else if ($this->input->post('filter_invoice') == 'unready_invoice') {
                $this->datatables->where('pb.id_invoice', NULL);
                $this->datatables->where('pb.from_invoice', NULL);
            }
        }

        if ($this->input->post('filter_month'))
            $this->datatables->where("DATE_FORMAT(pb.tgl_keluar,'%Y-%m')", $this->input->post('filter_month'));

        if ($this->input->post('filter_customer'))
            $this->datatables->like('c.nama_customer', $this->input->post('filter_customer'));

        if ($this->input->post('search_ready_invoice')) {
            $this->datatables->join('invoice i', 'i.id_invoice = pb.id_invoice');
            $this->db->like('i.no_invoice', $this->input->post('search_ready_invoice'));
        }

        if ($this->input->post('search_tarik_dari_invoice')) {
            $this->datatables->join('invoice fi', 'fi.id_invoice = pb.from_invoice');
            $this->db->like('fi.no_invoice', $this->input->post('search_tarik_dari_invoice'));
        }
        if ($this->input->post('filter_nama_pemasok'))
            $this->datatables->where('pb.id_pemasok', decrypt($this->input->post('filter_nama_pemasok', TRUE)));

        return $this->datatables
            ->select(' 
            pb.id_pengeluaran_barang, 
            pb.no_pengiriman,
            pb.tgl_keluar,
            c.nama_customer,
            g.nama_gudang,
            pb.keterangan,
            pb.id_invoice,
            pb.from_invoice,
            pb.status,
        ')
            ->from('pengeluaran_barang pb')
            ->join('customer c', 'c.id_customer = pb.id_customer', 'LEFT')
            ->join('gudang g', 'g.id_gudang = pb.id_gudang', 'LEFT')
            ->where('pb.status = 1')
            ->generate();
    }


    function getAll()
    {
        $searchArray = $this->input->post('search', TRUE);
        $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

        $this->db->select('
            pb.id_pengeluaran_barang,
            pb.no_pengiriman,
            pb.tgl_keluar,
            c.nama_customer,
            g.nama_gudang,
            pb.keterangan,
            pb.id_invoice,
            pb.from_invoice,
            pb.status_email_tiki,
            e.nama_ekspedisi,
        ');
        $this->db->from('pengeluaran_barang pb');
        $this->db->join('customer c', 'c.id_customer = pb.id_customer', 'LEFT');
        $this->db->join('gudang g', 'g.id_gudang = pb.id_gudang', 'LEFT');

        // ✅ penambahan untuk statu email ke tiki agar pihak gudang dan pak budi tau kalau sudah melakukan email ke pihak TIKI
        $this->db->join('ekspedisi e', 'e.id_ekspedisi = pb.id_ekspedisi', 'LEFT');

        $this->db->where('pb.status', 1);

        // ✅ Filter tambahan
        if ($this->input->post('filter_gudang')) {
            $this->db->where('pb.id_gudang', decrypt($this->input->post('filter_gudang', TRUE)));
        }

        if ($this->input->post('filter_invoice')) {
            if ($this->input->post('filter_invoice') == 'from_invoice') {
                $this->db->where('pb.from_invoice IS NOT NULL', NULL, FALSE);
            } else if ($this->input->post('filter_invoice') == 'ready_invoice') {
                $this->db->where('pb.id_invoice IS NOT NULL', NULL, FALSE);
            } else if ($this->input->post('filter_invoice') == 'unready_invoice') {
                $this->db->where('pb.id_invoice', NULL);
                $this->db->where('pb.from_invoice', NULL);
            }
        }

        if ($this->input->post('filter_month')) {
            $this->db->where("DATE_FORMAT(pb.tgl_keluar,'%Y-%m')", $this->input->post('filter_month'));
        }

        if ($this->input->post('filter_customer')) {
            $this->db->like('c.nama_customer', $this->input->post('filter_customer'));
        }

        // 🔍 Global Search Keyword (termasuk barang)
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('pb.no_pengiriman', $keyword);
            $this->db->or_like('c.nama_customer', $keyword);
            $this->db->or_like('g.nama_gudang', $keyword);
            $this->db->or_like('pb.keterangan', $keyword);

            // Search barang dari detail_barang_keluar
            $this->db->or_where("pb.id_pengeluaran_barang IN (
                SELECT dbk.id_pengeluaran_barang 
                FROM detail_barang_keluar dbk 
                LEFT JOIN barang b ON b.id_barang = dbk.id_barang 
                WHERE b.nama_barang LIKE '%" . $this->db->escape_like_str($keyword) . "%'
            )");
            $this->db->group_end();
        }

        // 🔢 Ordering
        $columns = ['pb.id_pengeluaran_barang', 'pb.no_pengiriman', 'pb.tgl_keluar', 'c.nama_customer', 'g.nama_gudang', 'pb.keterangan'];
        $orderCol = $columns[0];
        $orderDir = 'DESC';
        if (isset($_POST['order'][0]['column'])) {
            $colIdx = $_POST['order'][0]['column'];
            $orderCol = isset($columns[$colIdx]) ? $columns[$colIdx] : $columns[0];
            $orderDir = $_POST['order'][0]['dir'] === 'desc' ? 'DESC' : 'ASC';
        }
        $this->db->order_by($orderCol, $orderDir);

        // 📊 Pagination
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $data = $this->db->get()->result();

        // Total data tanpa filter
        $this->db->reset_query();
        $this->db->select('COUNT(*) as total');
        $this->db->from('pengeluaran_barang pb');
        $this->db->where('pb.status', 1);
        $recordsTotal = $this->db->get()->row()->total;

        // Total data dengan filter pencarian
        $this->db->reset_query();
        $this->db->select('COUNT(DISTINCT pb.id_pengeluaran_barang) as total');
        $this->db->from('pengeluaran_barang pb');
        $this->db->join('customer c', 'c.id_customer = pb.id_customer', 'LEFT');
        $this->db->join('gudang g', 'g.id_gudang = pb.id_gudang', 'LEFT');
        $this->db->where('pb.status', 1);

        // Ulangi semua filter dan pencarian
        if ($this->input->post('filter_gudang')) {
            $this->db->where('pb.id_gudang', decrypt($this->input->post('filter_gudang', TRUE)));
        }
        if ($this->input->post('filter_invoice')) {
            if ($this->input->post('filter_invoice') == 'from_invoice') {
                $this->db->where('pb.from_invoice IS NOT NULL', NULL, FALSE);
            } else if ($this->input->post('filter_invoice') == 'ready_invoice') {
                $this->db->where('pb.id_invoice IS NOT NULL', NULL, FALSE);
            } else if ($this->input->post('filter_invoice') == 'unready_invoice') {
                $this->db->where('pb.id_invoice', NULL);
                $this->db->where('pb.from_invoice', NULL);
            }
        }
        if ($this->input->post('filter_month')) {
            $this->db->where("DATE_FORMAT(pb.tgl_keluar,'%Y-%m')", $this->input->post('filter_month'));
        }
        if ($this->input->post('filter_customer')) {
            $this->db->like('c.nama_customer', $this->input->post('filter_customer'));
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('pb.no_pengiriman', $keyword);
            $this->db->or_like('c.nama_customer', $keyword);
            $this->db->or_like('g.nama_gudang', $keyword);
            $this->db->or_like('pb.keterangan', $keyword);
            $this->db->or_where("pb.id_pengeluaran_barang IN (
                SELECT dbk.id_pengeluaran_barang 
                FROM detail_barang_keluar dbk 
                LEFT JOIN barang b ON b.id_barang = dbk.id_barang 
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



    public function getBarangDetailByPengeluaran($id_pengeluaran_barang)
    {
        return $this->db
            ->distinct() // ✅ Hindari duplikat nama_barang
            ->select('b.nama_barang')
            ->from('detail_barang_keluar dbk')
            ->join('barang b', 'b.id_barang = dbk.id_barang', 'LEFT')
            ->where('dbk.id_pengeluaran_barang', $id_pengeluaran_barang)
            ->where('dbk.status', 1) // ✅ Hanya ambil status = 1
            ->get()
            ->result();
    }




    //////////////////////////////////////////////////////////////////////////////////////
    //mulai dari sini adalah semua tentang function temp data di form penerimaan barang//
    ////////////////////////////////////////////////////////////////////////////////////
    function getTempData()
    {
        $this->db->select('pbt.*,c.nama_customer, g.nama_gudang');
        $this->db->join('customer c', 'c.id_customer = pbt.id_customer', 'LEFT');
        $this->db->join('gudang g', 'g.id_gudang = pbt.id_gudang', 'LEFT');
        return $this->db->get_where('pengeluaran_barang_temp pbt', ['pbt.pengguna_id' => sessPenggunaId()])->result();
    }

    function addTempData($data)
    {
        $this->db->insert('pengeluaran_barang_temp', $data);
    }

    function updateTempData($where, $data)
    {
        $this->db->where($where);
        $this->db->update('pengeluaran_barang_temp', $data);
    }

    function getDetailBarangTempBySess()
    {
        $this->db->select('dbkt.*,b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = dbkt.id_barang', 'LEFT');
        $this->db->where('dbkt.id_pengeluaran_barang', NULL);
        return $this->db->get_where('detail_barang_keluar_temp dbkt', ['dbkt.pengguna_id' => sessPenggunaId()])->result();
    }

    function getDetailBarangTempById($id)
    {
        $this->db->select('dbkt.*,b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = dbkt.id_barang', 'LEFT');
        return $this->db->get_where('detail_barang_keluar_temp dbkt', ['dbkt.id_detail_barang_keluar_temp' => $id])->result();
    }

    function getByIdPengeluaranBarang($dt)
    {
        $this->db->select('dbkt.*, b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = dbkt.id_barang');
        return $this->db->get_where('detail_barang_keluar_temp dbkt', ['dbkt.id_pengeluaran_barang' => $dt])->result();
    }

    function deleteDetailBarangTemp($data)
    {
        $this->db->delete('detail_barang_keluar_temp', ['id_detail_barang_keluar_temp' => $data]);
    }

    function addDetailBarangTemp($data)
    {
        $this->db->insert('detail_barang_keluar_temp', $data);
    }

    function updateDetailBarangKeluarTemp($where, $data)
    {
        $this->db->where($where);
        $this->db->update('detail_barang_keluar_temp', $data);
    }

    function destroyTempData()
    {
        $this->db->where('id_pengeluaran_barang', NULL);
        $this->db->delete('detail_barang_keluar_temp', ['pengguna_id' => sessPenggunaId()]);
        $this->db->delete('pengeluaran_barang_temp', ['pengguna_id' => sessPenggunaId()]);
    }

    function destroyNewTempDetailBarang($id)
    {
        $this->db->where('id_pengeluaran_barang', $id);
        $this->db->delete('detail_barang_keluar_temp');
    }
    //////////////////
    //End Temp Data//
    ////////////////


    public function get_customer_by_id($id_customer)
    {
        return $this->db->get_where('customer', ['id_customer' => $id_customer])->row_array();
    }





    function getAllKeluarByTGL($tglawal, $tglakhir)
    {
        $this->db->order_by('pb.tgl_keluar', 'DESC');
        $query = $this->db
            ->select('
                pb.id_pengeluaran_barang,
                pb.tgl_keluar,
                pb.id_customer,
                pb.no_pengiriman,
                dbk.no_batch,
                dbk.exp_date,
                dbk.qty,
                b.id_barang,
                b.nama_barang,
                b.kode_produk,
                b.akl,
                kb.nama_kategori,
                c.nama_customer
            ')
            ->from('pengeluaran_barang pb')
            ->join('detail_barang_keluar dbk', 'pb.id_pengeluaran_barang = dbk.id_pengeluaran_barang', 'left')
            ->join('barang b', 'b.id_barang = dbk.id_barang', 'left')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            ->join('customer c', 'pb.id_customer = c.id_customer', 'left')
            ->where('dbk.status', 1)
            ->where('pb.tgl_keluar >=', date('Y-m-d', strtotime($tglawal)))
            ->where('pb.tgl_keluar <=', date('Y-m-d', strtotime($tglakhir)));

        return $query->get()->result();
    }
}
