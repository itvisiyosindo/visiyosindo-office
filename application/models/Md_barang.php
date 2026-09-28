<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_barang extends CI_Model
{

    function add($data)
    {
        $this->db->insert('barang', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('barang', $data);
    }

    function getById($id)
    {
        $this->db->select('b.*, c.nama_cabang, kb.nama_kategori, pu.nama_pemasok, sb.nama_satuan, tp.nama_pajak');
        $this->db->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori');
        $this->db->join('cabang c', 'c.id_cabang = b.id_cabang');
        $this->db->join('pemasok_utama pu', 'pu.id_pemasok = b.id_pemasok_utama', 'LEFT');
        $this->db->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'LEFT');
        $this->db->join('tarif_pajak tp', 'tp.id_tarif_pajak = b.id_tarif_pajak', 'LEFT');
        return $this->db->get_where('barang b', array('b.id_barang' => $id))->result();
    }

    function getBySearch()
    {
        $q = $this->input->post('q', TRUE);
        $id_gudang = $this->input->post('id_gudang', TRUE);


        if (ctype_lower($q) || preg_match('/\s/', $q)) {
            //search by nama_barang
            $this->db->like('b.nama_barang', $q, 'both');
        } else {
            //search by kode_barang
            $this->db->select('b.*, kb.kode_barang');
            $this->db->join('kode_barang kb', 'b.id_barang = kb.id_barang', 'LEFT');
            $this->db->where('b.kode_barang', $q);
            $this->db->or_where('kb.kode_barang', $q);
        }

        //get data by gudang_id(form pengeluaran barang)
        if ($id_gudang) {
            $this->db->join('detail_barang db', 'db.id_barang = b.id_barang', 'LEFT');
            $this->db->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang', 'LEFT');
            // $this->db->group_by('b.nama_barang');
            $this->db->where('pb.id_gudang', decrypt($id_gudang));
            $this->db->where('db.status', 1);
            $this->db->where('pb.status', 1);
        }

        $this->db->group_by('b.nama_barang');
        return $this->db->get_where('barang b', ['b.status' => 1])->result();
    }

    function getByWhere($where = "")
    {
        $this->db->where('b.status', 1);
        return $this->db->get_where('barang b', $where)->result();
    }

    function getForStockGudang()
    {
        $this->db->select('id_barang, nama_barang');
        return $this->db->get_where('barang', ['status' => 1])->result();
    }

    function getStokByBarangIdGudangId($id_barang, $id_gudang)
    {
        $q = 'SELECT sum(db.current_stock) as total_stock FROM barang b
                left join detail_barang db on db.id_barang = b.id_barang
                left join penerimaan_barang pb on pb.id_penerimaan_barang = db.id_penerimaan_barang
                left join gudang g on g.id_gudang = pb.id_gudang
                where (db.status = 1 or db.id_detail_barang is null) and g.id_gudang = ? and b.id_barang = ?
                group by g.nama_gudang';
        return $this->db->query($q, [$id_gudang, $id_barang])->result();
    }

    function getStokByBarangIdPEngirimanStok($id_barang)
    {

        $this->db->select('sum(dbps.current_qty) as total_transit');
        $this->db->join('detail_barang_pengiriman_stok dbps', 'dbps.id_barang = b.id_barang');
        $this->db->join('pengiriman_stok ps', ' ps.id_pengiriman_stok = dbps.id_pengiriman_stok');
        $this->db->where('b.id_barang', $id_barang);
        return $this->db->get_where('barang b', ['dbps.status' => 1])->result();
    }

    /**
     * Bulk: Ambil semua stock per barang per gudang dalam 1 query
     * Menggantikan N+1 calls ke getStokByBarangIdGudangId()
     * Return: associative array [id_barang][id_gudang] => total_stock
     */
    function getBulkStokByGudangIds($gudang_ids, $barang_ids = [])
    {
        if (empty($gudang_ids)) return [];

        $gudang_ids_str = implode(',', array_map('intval', $gudang_ids));

        $q = "SELECT db.id_barang, pb.id_gudang, SUM(db.current_stock) as total_stock 
              FROM detail_barang db
              INNER JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
              WHERE db.status = 1 
              AND pb.id_gudang IN ({$gudang_ids_str})";

        if (!empty($barang_ids)) {
            $barang_ids_str = implode(',', array_map('intval', $barang_ids));
            $q .= " AND db.id_barang IN ({$barang_ids_str})";
        }

        $q .= " GROUP BY db.id_barang, pb.id_gudang";

        $result = $this->db->query($q)->result();

        // Build lookup array: [id_barang][id_gudang] => total_stock
        $lookup = [];
        foreach ($result as $row) {
            $lookup[$row->id_barang][$row->id_gudang] = $row->total_stock;
        }
        return $lookup;
    }

    /**
     * Bulk: Ambil semua transit stock per barang dalam 1 query
     * Menggantikan N+1 calls ke getStokByBarangIdPEngirimanStok()
     * Return: associative array [id_barang] => total_transit
     */
    function getBulkStokTransit($barang_ids = [])
    {
        $q = "SELECT dbps.id_barang, SUM(dbps.current_qty) as total_transit 
              FROM detail_barang_pengiriman_stok dbps
              WHERE dbps.status = 1";

        if (!empty($barang_ids)) {
            $barang_ids_str = implode(',', array_map('intval', $barang_ids));
            $q .= " AND dbps.id_barang IN ({$barang_ids_str})";
        }

        $q .= " GROUP BY dbps.id_barang";

        $result = $this->db->query($q)->result();

        // Build lookup array: [id_barang] => total_transit
        $lookup = [];
        foreach ($result as $row) {
            $lookup[$row->id_barang] = $row->total_transit;
        }
        return $lookup;
    }

    function getAll88()
    {
        if ($this->input->post('filter_kategori'))
            $this->datatables->where('b.id_kategori', decrypt($this->input->post('filter_kategori', TRUE)));

        if ($this->input->post('scan_barcode')) {

            $this->datatables->where('b.kode_barang', $this->input->post('scan_barcode', TRUE));
        }


        return $this->datatables
            ->select('  
            b.id_barang,  
            b.nama_barang,  
            b.jenis_barang,  
            b.id_cabang,  
            b.id_pemasok_utama,  
            sb.nama_satuan,  
            b.kode_barang,  
            b.status, 
            kb.nama_kategori,
            b.id_kategori,
            b.data_created,
            b.status
            ')
            ->from('barang b')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            // ->join('kode_barang kob', 'kob.id_barang = b.id_barang', 'left')
            ->where('b.status = 1')
            //->group_by('b.nama_barang')
            ->generate();
    }

    // Untuk export data ke Excel
    function getAllExport()
    {
        $this->db->order_by('b.id_kategori', 'DESC');  // Mengurutkan berdasarkan id_kategori
        $this->db->order_by('b.data_created', 'DESC'); // Kemudian urutkan berdasarkan data_created

        $query = $this->db
            ->select('
            b.id_barang,  
            b.nama_barang,  
            b.jenis_barang,  
            b.id_cabang,  
            b.id_pemasok_utama, 
            b.id_satuan_barang, 
            b.kode_barang,  
            b.batas_min_stock,
            b.id_tarif_pajak,
            b.keterangan,
            b.status, 
            b.id_kategori,
            b.data_created,
            b.id_produk,
            b.tipe,
            b.akl,
            b.kode_produk,
            kb.nama_kategori AS kategori
        ')
            ->from('barang b')
            ->where('b.status = 1')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori');

        return $query->get()->result();
    }



    function getAll()
    {
        if ($this->input->post('filter_kategori'))
            $this->datatables->where('b.id_kategori', decrypt($this->input->post('filter_kategori', TRUE)));

        if ($this->input->post('scan_barcode')) {
            $this->datatables->where('b.kode_barang', $this->input->post('scan_barcode', TRUE));
        }

        if ($this->input->post('filter_stok') == '1') {
            $this->datatables->where("(
            (SELECT sum(db.current_stock) as total_stock FROM detail_barang db
                LEFT JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
                LEFT JOIN gudang g ON g.id_gudang = pb.id_gudang
                WHERE db.id_barang = b.id_barang) > 0
            OR
            (SELECT sum(dbps.current_qty) as total_transit FROM detail_barang_pengiriman_stok dbps
                LEFT JOIN pengiriman_stok ps ON ps.id_pengiriman_stok = dbps.id_pengiriman_stok
                WHERE dbps.id_barang = b.id_barang AND dbps.status = 1) > 0
        )");
        }

        return $this->datatables
            ->select('  
        b.id_barang,  
        b.nama_barang,  
        b.jenis_barang,  
        b.id_cabang,  
        b.id_pemasok_utama,  
        sb.nama_satuan,  
        b.kode_barang,  
        b.status, 
        kb.nama_kategori,
        b.id_kategori,
        b.data_created,
        b.status,
        b.id_produk,
        b.tipe,
        b.akl,
        b.kode_produk
        ')
            ->from('barang b')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            // ->join('kode_barang kob', 'kob.id_barang = b.id_barang', 'left')
            ->where('b.status = 1')
            //->group_by('b.nama_barang')
            ->generate();
    }

    function getAllJual()
    {
        if ($this->input->post('filter_kategori'))
            $this->datatables->where('b.id_kategori', decrypt($this->input->post('filter_kategori', TRUE)));

        if ($this->input->post('scan_barcode')) {
            $this->datatables->where('b.kode_barang', $this->input->post('scan_barcode', TRUE));
        }

        if ($this->input->post('filter_stok') == '1') {
            $this->datatables->where("(
            (
                SELECT SUM(db.current_stock) AS total_stock 
                FROM detail_barang db
                LEFT JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
                LEFT JOIN gudang g ON g.id_gudang = pb.id_gudang
                WHERE db.id_barang = b.id_barang 
                AND g.id_gudang IN (1, 2, 3)
            ) > 0
            OR
            (
                SELECT SUM(dbps.current_qty) AS total_transit 
                FROM detail_barang_pengiriman_stok dbps
                LEFT JOIN pengiriman_stok ps ON ps.id_pengiriman_stok = dbps.id_pengiriman_stok
                WHERE dbps.id_barang = b.id_barang 
                AND dbps.status = 1
            ) > 0

        )");
        }

        return $this->datatables
            ->select('  
        b.id_barang,  
        b.nama_barang,  
        b.jenis_barang,  
        b.id_cabang,  
        b.id_pemasok_utama,  
        sb.nama_satuan,  
        b.kode_barang,  
        b.status, 
        kb.nama_kategori,
        b.id_kategori,
        b.data_created,
        b.status
        ')
            ->from('barang b')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            // ->join('kode_barang kob', 'kob.id_barang = b.id_barang', 'left')
            ->where('b.status = 1')
            //->group_by('b.nama_barang')
            ->generate();
    }

    function getAllDemo()
    {
        if ($this->input->post('filter_kategori'))
            $this->datatables->where('b.id_kategori', decrypt($this->input->post('filter_kategori', TRUE)));

        if ($this->input->post('scan_barcode')) {
            $this->datatables->where('b.kode_barang', $this->input->post('scan_barcode', TRUE));
        }

        if ($this->input->post('filter_stok') == '1') {
            $this->datatables->where("(
            (
                SELECT SUM(db.current_stock) AS total_stock 
                FROM detail_barang db
                LEFT JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
                LEFT JOIN gudang g ON g.id_gudang = pb.id_gudang
                WHERE db.id_barang = b.id_barang 
                AND g.id_gudang IN (6, 10, 11)
            ) > 0
            OR
            (
                SELECT SUM(dbps.current_qty) AS total_transit 
                FROM detail_barang_pengiriman_stok dbps
                LEFT JOIN pengiriman_stok ps ON ps.id_pengiriman_stok = dbps.id_pengiriman_stok
                WHERE dbps.id_barang = b.id_barang 
                AND dbps.status = 1
            ) > 0

        )");
        }

        return $this->datatables
            ->select('  
        b.id_barang,  
        b.nama_barang,  
        b.jenis_barang,  
        b.id_cabang,  
        b.id_pemasok_utama,  
        sb.nama_satuan,  
        b.kode_barang,  
        b.status, 
        kb.nama_kategori,
        b.id_kategori,
        b.data_created,
        b.status
        ')
            ->from('barang b')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            // ->join('kode_barang kob', 'kob.id_barang = b.id_barang', 'left')
            ->where('b.status = 1')
            //->group_by('b.nama_barang')
            ->generate();
    }


    function getAllRusak()
    {
        if ($this->input->post('filter_kategori'))
            $this->datatables->where('b.id_kategori', decrypt($this->input->post('filter_kategori', TRUE)));

        if ($this->input->post('scan_barcode')) {
            $this->datatables->where('b.kode_barang', $this->input->post('scan_barcode', TRUE));
        }

        if ($this->input->post('filter_stok') == '1') {
            $this->datatables->where("(
            (
                SELECT SUM(db.current_stock) AS total_stock 
                FROM detail_barang db
                LEFT JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
                LEFT JOIN gudang g ON g.id_gudang = pb.id_gudang
                WHERE db.id_barang = b.id_barang 
                AND g.id_gudang IN (7, 25, 29)
            ) > 0
            OR
            (
                SELECT SUM(dbps.current_qty) AS total_transit 
                FROM detail_barang_pengiriman_stok dbps
                LEFT JOIN pengiriman_stok ps ON ps.id_pengiriman_stok = dbps.id_pengiriman_stok
                WHERE dbps.id_barang = b.id_barang 
                AND dbps.status = 1
            ) > 0

        )");
        }

        return $this->datatables
            ->select('  
        b.id_barang,  
        b.nama_barang,  
        b.jenis_barang,  
        b.id_cabang,  
        b.id_pemasok_utama,  
        sb.nama_satuan,  
        b.kode_barang,  
        b.status, 
        kb.nama_kategori,
        b.id_kategori,
        b.data_created,
        b.status
        ')
            ->from('barang b')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            // ->join('kode_barang kob', 'kob.id_barang = b.id_barang', 'left')
            ->where('b.status = 1')
            //->group_by('b.nama_barang')
            ->generate();
    }


    function getAllMarketing()
    {
        if ($this->input->post('filter_kategori'))
            $this->datatables->where('b.id_kategori', decrypt($this->input->post('filter_kategori', TRUE)));

        if ($this->input->post('scan_barcode')) {
            $this->datatables->where('b.kode_barang', $this->input->post('scan_barcode', TRUE));
        }

        if ($this->input->post('filter_stok') == '1') {
            $this->datatables->where("(
            (
                SELECT SUM(db.current_stock) AS total_stock 
                FROM detail_barang db
                LEFT JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
                LEFT JOIN gudang g ON g.id_gudang = pb.id_gudang
                WHERE db.id_barang = b.id_barang 
                AND g.id_gudang IN (23, 26, 30)
            ) > 0
            OR
            (
                SELECT SUM(dbps.current_qty) AS total_transit 
                FROM detail_barang_pengiriman_stok dbps
                LEFT JOIN pengiriman_stok ps ON ps.id_pengiriman_stok = dbps.id_pengiriman_stok
                WHERE dbps.id_barang = b.id_barang 
                AND dbps.status = 1
            ) > 0

        )");
        }

        return $this->datatables
            ->select('  
        b.id_barang,  
        b.nama_barang,  
        b.jenis_barang,  
        b.id_cabang,  
        b.id_pemasok_utama,  
        sb.nama_satuan,  
        b.kode_barang,  
        b.status, 
        kb.nama_kategori,
        b.id_kategori,
        b.data_created,
        b.status
        ')
            ->from('barang b')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            // ->join('kode_barang kob', 'kob.id_barang = b.id_barang', 'left')
            ->where('b.status = 1')
            //->group_by('b.nama_barang')
            ->generate();
    }


    function getAllSparepart()
    {
        if ($this->input->post('filter_kategori'))
            $this->datatables->where('b.id_kategori', decrypt($this->input->post('filter_kategori', TRUE)));

        if ($this->input->post('scan_barcode')) {
            $this->datatables->where('b.kode_barang', $this->input->post('scan_barcode', TRUE));
        }

        if ($this->input->post('filter_stok') == '1') {
            $this->datatables->where("(
            (
                SELECT SUM(db.current_stock) AS total_stock 
                FROM detail_barang db
                LEFT JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
                LEFT JOIN gudang g ON g.id_gudang = pb.id_gudang
                WHERE db.id_barang = b.id_barang 
                AND g.id_gudang IN (5, 27, 31)
            ) > 0
            OR
            (
                SELECT SUM(dbps.current_qty) AS total_transit 
                FROM detail_barang_pengiriman_stok dbps
                LEFT JOIN pengiriman_stok ps ON ps.id_pengiriman_stok = dbps.id_pengiriman_stok
                WHERE dbps.id_barang = b.id_barang 
                AND dbps.status = 1
            ) > 0

        )");
        }

        return $this->datatables
            ->select('  
        b.id_barang,  
        b.nama_barang,  
        b.jenis_barang,  
        b.id_cabang,  
        b.id_pemasok_utama,  
        sb.nama_satuan,  
        b.kode_barang,  
        b.status, 
        kb.nama_kategori,
        b.id_kategori,
        b.data_created,
        b.status
        ')
            ->from('barang b')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            // ->join('kode_barang kob', 'kob.id_barang = b.id_barang', 'left')
            ->where('b.status = 1')
            //->group_by('b.nama_barang')
            ->generate();
    }


    function getAllCustomer()
    {
        if ($this->input->post('filter_kategori'))
            $this->datatables->where('b.id_kategori', decrypt($this->input->post('filter_kategori', TRUE)));

        if ($this->input->post('scan_barcode')) {
            $this->datatables->where('b.kode_barang', $this->input->post('scan_barcode', TRUE));
        }

        if ($this->input->post('filter_stok') == '1') {
            $this->datatables->where("(
            (
                SELECT SUM(db.current_stock) AS total_stock 
                FROM detail_barang db
                LEFT JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
                LEFT JOIN gudang g ON g.id_gudang = pb.id_gudang
                WHERE db.id_barang = b.id_barang 
                AND g.id_gudang IN (24, 28, 32)
            ) > 0
            OR
            (
                SELECT SUM(dbps.current_qty) AS total_transit 
                FROM detail_barang_pengiriman_stok dbps
                LEFT JOIN pengiriman_stok ps ON ps.id_pengiriman_stok = dbps.id_pengiriman_stok
                WHERE dbps.id_barang = b.id_barang 
                AND dbps.status = 1
            ) > 0

        )");
        }

        return $this->datatables
            ->select('  
        b.id_barang,  
        b.nama_barang,  
        b.jenis_barang,  
        b.id_cabang,  
        b.id_pemasok_utama,  
        sb.nama_satuan,  
        b.kode_barang,  
        b.status, 
        kb.nama_kategori,
        b.id_kategori,
        b.data_created,
        b.status
        ')
            ->from('barang b')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            // ->join('kode_barang kob', 'kob.id_barang = b.id_barang', 'left')
            ->where('b.status = 1')
            //->group_by('b.nama_barang')
            ->generate();
    }


    function getAllDefault()
    {
        if ($this->input->post('filter_kategori'))
            $this->datatables->where('b.id_kategori', decrypt($this->input->post('filter_kategori', TRUE)));

        if ($this->input->post('scan_barcode')) {
            $this->datatables->where('b.kode_barang', $this->input->post('scan_barcode', TRUE));
        }

        if ($this->input->post('filter_stok') == '1') {
            $this->datatables->where("(
            (
                SELECT SUM(db.current_stock) AS total_stock 
                FROM detail_barang db
                LEFT JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
                LEFT JOIN gudang g ON g.id_gudang = pb.id_gudang
                WHERE db.id_barang = b.id_barang 
                AND g.id_gudang IN (1, 2, 3, 10, 6, 11, 23, 26, 30)
            ) > 0
            OR
            (
                SELECT SUM(dbps.current_qty) AS total_transit 
                FROM detail_barang_pengiriman_stok dbps
                LEFT JOIN pengiriman_stok ps ON ps.id_pengiriman_stok = dbps.id_pengiriman_stok
                WHERE dbps.id_barang = b.id_barang 
                AND dbps.status = 1
            ) > 0

        )");
        }

        return $this->datatables
            ->select('  
        b.id_barang,  
        b.nama_barang,  
        b.jenis_barang,  
        b.id_cabang,  
        b.id_pemasok_utama,  
        sb.nama_satuan,  
        b.kode_barang,  
        b.status, 
        kb.nama_kategori,
        b.id_kategori,
        b.data_created,
        b.status
        ')
            ->from('barang b')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            // ->join('kode_barang kob', 'kob.id_barang = b.id_barang', 'left')
            ->where('b.status = 1')
            //->group_by('b.nama_barang')
            ->generate();
    }


    function getAllPusat()
    {
        if ($this->input->post('filter_kategori'))
            $this->datatables->where('b.id_kategori', decrypt($this->input->post('filter_kategori', TRUE)));

        if ($this->input->post('scan_barcode')) {
            $this->datatables->where('b.kode_barang', $this->input->post('scan_barcode', TRUE));
        }

        if ($this->input->post('filter_stok') == '1') {
            $this->datatables->where("(
            (
                SELECT SUM(db.current_stock) AS total_stock 
                FROM detail_barang db
                LEFT JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
                LEFT JOIN gudang g ON g.id_gudang = pb.id_gudang
                WHERE db.id_barang = b.id_barang 
                AND g.lokasi IN (1)
            ) > 0
            OR
            (
                SELECT SUM(dbps.current_qty) AS total_transit 
                FROM detail_barang_pengiriman_stok dbps
                LEFT JOIN pengiriman_stok ps ON ps.id_pengiriman_stok = dbps.id_pengiriman_stok
                WHERE dbps.id_barang = b.id_barang 
                AND dbps.status = 1
            ) > 0

        )");
        }

        return $this->datatables
            ->select('  
        b.id_barang,  
        b.nama_barang,  
        b.jenis_barang,  
        b.id_cabang,  
        b.id_pemasok_utama,  
        sb.nama_satuan,  
        b.kode_barang,  
        b.status, 
        kb.nama_kategori,
        b.id_kategori,
        b.data_created,
        b.status
        ')
            ->from('barang b')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            // ->join('kode_barang kob', 'kob.id_barang = b.id_barang', 'left')
            ->where('b.status = 1')
            //->group_by('b.nama_barang')
            ->generate();
    }

    function getAllJakarta()
    {
        if ($this->input->post('filter_kategori'))
            $this->datatables->where('b.id_kategori', decrypt($this->input->post('filter_kategori', TRUE)));

        if ($this->input->post('scan_barcode')) {
            $this->datatables->where('b.kode_barang', $this->input->post('scan_barcode', TRUE));
        }

        if ($this->input->post('filter_stok') == '1') {
            $this->datatables->where("(
            (
                SELECT SUM(db.current_stock) AS total_stock 
                FROM detail_barang db
                LEFT JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
                LEFT JOIN gudang g ON g.id_gudang = pb.id_gudang
                WHERE db.id_barang = b.id_barang 
                AND g.lokasi IN (2)
            ) > 0
            OR
            (
                SELECT SUM(dbps.current_qty) AS total_transit 
                FROM detail_barang_pengiriman_stok dbps
                LEFT JOIN pengiriman_stok ps ON ps.id_pengiriman_stok = dbps.id_pengiriman_stok
                WHERE dbps.id_barang = b.id_barang 
                AND dbps.status = 1
            ) > 0

        )");
        }

        return $this->datatables
            ->select('  
        b.id_barang,  
        b.nama_barang,  
        b.jenis_barang,  
        b.id_cabang,  
        b.id_pemasok_utama,  
        sb.nama_satuan,  
        b.kode_barang,  
        b.status, 
        kb.nama_kategori,
        b.id_kategori,
        b.data_created,
        b.status
        ')
            ->from('barang b')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            // ->join('kode_barang kob', 'kob.id_barang = b.id_barang', 'left')
            ->where('b.status = 1')
            //->group_by('b.nama_barang')
            ->generate();
    }

    function getAllYogyakarta()
    {
        if ($this->input->post('filter_kategori'))
            $this->datatables->where('b.id_kategori', decrypt($this->input->post('filter_kategori', TRUE)));

        if ($this->input->post('scan_barcode')) {
            $this->datatables->where('b.kode_barang', $this->input->post('scan_barcode', TRUE));
        }

        if ($this->input->post('filter_stok') == '1') {
            $this->datatables->where("(
            (
                SELECT SUM(db.current_stock) AS total_stock 
                FROM detail_barang db
                LEFT JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
                LEFT JOIN gudang g ON g.id_gudang = pb.id_gudang
                WHERE db.id_barang = b.id_barang 
                AND g.lokasi IN (3)
            ) > 0
            OR
            (
                SELECT SUM(dbps.current_qty) AS total_transit 
                FROM detail_barang_pengiriman_stok dbps
                LEFT JOIN pengiriman_stok ps ON ps.id_pengiriman_stok = dbps.id_pengiriman_stok
                WHERE dbps.id_barang = b.id_barang 
                AND dbps.status = 1
            ) > 0

        )");
        }

        return $this->datatables
            ->select('  
        b.id_barang,  
        b.nama_barang,  
        b.jenis_barang,  
        b.id_cabang,  
        b.id_pemasok_utama,  
        sb.nama_satuan,  
        b.kode_barang,  
        b.status, 
        kb.nama_kategori,
        b.id_kategori,
        b.data_created,
        b.status
        ')
            ->from('barang b')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            // ->join('kode_barang kob', 'kob.id_barang = b.id_barang', 'left')
            ->where('b.status = 1')
            //->group_by('b.nama_barang')
            ->generate();
    }


    function getAllStockExportFilter($id_kategori = NULL)
    {
        $customGudangOrder = [1, 3, 2, 10, 6, 11, 7, 25, 29, 23, 26, 30, 5, 27, 31, 24, 28, 32, 4, 33];  // Daftar ID gudang yang relevan

        // Ambil data barang yang statusnya aktif (status = 1)
        $this->db->select('barang.id_barang, barang.nama_barang, barang.id_kategori, kb.nama_kategori')
            ->from('barang')
            ->join('kategori_barang kb', 'kb.id_kategori = barang.id_kategori', 'left') // JOIN dengan kategori_barang
            ->where('barang.status', 1)
            ->order_by('barang.id_barang', 'DESC');

        if ($id_kategori != NULL) {
            $this->db->where('barang.id_kategori', $id_kategori);
        }

        $barangData = $this->db->get()->result();

        $result = [];

        // Loop untuk setiap barang
        foreach ($barangData as $barang) {
            $row = [
                'nama_barang' => $barang->nama_barang,
                'nama_kategori' => $barang->nama_kategori  // Menyimpan nama barang
            ];

            // Loop untuk setiap gudang yang relevan
            foreach ($customGudangOrder as $gudang_id) {
                // Query untuk mengambil stok barang per gudang
                $this->db
                    ->select('COALESCE(SUM(db.current_stock), 0) as total_stock', false)  // Menghitung total stok, 0 jika tidak ada
                    ->from('barang b')
                    ->join('detail_barang db', 'db.id_barang = b.id_barang', 'left')  // Join dengan detail barang
                    ->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang', 'left')  // Join dengan penerimaan barang
                    ->join('gudang g', 'g.id_gudang = pb.id_gudang', 'left')  // Join dengan gudang
                    ->where('b.id_barang', $barang->id_barang)  // Filter berdasarkan id_barang
                    ->where('g.id_gudang', $gudang_id)  // Filter berdasarkan id_gudang untuk masing-masing gudang
                    ->where('(db.status = 1 OR db.id_detail_barang IS NULL)', null, false)  // Sama seperti query pada getStokByBarangIdGudangId untuk status
                    ->group_by('g.nama_gudang');  // Grouping berdasarkan nama gudang untuk stok yang lebih tepat

                // Ambil total stok dari query
                $stok_row = $this->db->get()->row();  // Mendapatkan hasil query pertama

                // Cek jika hasil query null
                $stok = $stok_row ? $stok_row->total_stock : 0;  // Jika tidak ada hasil, set stok = 0

                // Tambahkan stok gudang ke dalam row dengan kunci dinamis (stok_gudang_X)
                $row["stok_gudang_{$gudang_id}"] = $stok;
            }

            // Tambahkan row ke dalam hasil akhir
            $result[] = $row;
        }

        return $result;  // Kembalikan hasil query dalam bentuk array
    }



    function getAllStockExportFilter2()
    {
        $customGudangOrder = [1, 3, 2, 10, 6, 11, 7, 25, 29, 23, 26, 30, 5, 27, 31, 24, 28, 32, 4, 33];  // Daftar ID gudang yang relevan

        // Ambil data barang yang statusnya aktif (status = 1)
        $this->db->select('id_barang, nama_barang')
            ->from('barang')
            ->where('status', 1)
            ->order_by('id_barang', 'DESC');;
        $barangData = $this->db->get()->result();

        $result = [];

        // Loop untuk setiap barang
        foreach ($barangData as $barang) {
            $row = [
                'nama_barang' => $barang->nama_barang,  // Menyimpan nama barang
            ];

            // Loop untuk setiap gudang yang relevan
            foreach ($customGudangOrder as $gudang_id) {
                // Query untuk mengambil stok barang per gudang
                $this->db
                    ->select('COALESCE(SUM(db.current_stock), 0) as total_stock', false)  // Menghitung total stok, 0 jika tidak ada
                    ->from('barang b')
                    ->join('detail_barang db', 'db.id_barang = b.id_barang', 'left')  // Join dengan detail barang
                    ->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang', 'left')  // Join dengan penerimaan barang
                    ->join('gudang g', 'g.id_gudang = pb.id_gudang', 'left')  // Join dengan gudang
                    ->where('b.id_barang', $barang->id_barang)  // Filter berdasarkan id_barang
                    ->where('g.id_gudang', $gudang_id)  // Filter berdasarkan id_gudang untuk masing-masing gudang
                    ->where('(db.status = 1 OR db.id_detail_barang IS NULL)', null, false)  // Sama seperti query pada getStokByBarangIdGudangId untuk status
                    ->group_by('g.nama_gudang');  // Grouping berdasarkan nama gudang untuk stok yang lebih tepat

                // Ambil total stok dari query
                $stok_row = $this->db->get()->row();  // Mendapatkan hasil query pertama

                // Cek jika hasil query null
                $stok = $stok_row ? $stok_row->total_stock : 0;  // Jika tidak ada hasil, set stok = 0

                // Tambahkan stok gudang ke dalam row dengan kunci dinamis (stok_gudang_X)
                $row["stok_gudang_{$gudang_id}"] = $stok;
            }

            // Tambahkan row ke dalam hasil akhir
            $result[] = $row;
        }

        return $result;  // Kembalikan hasil query dalam bentuk array
    }


    function getAllStockExport($id_kategori = NULL)
    {
        $customGudangOrder = [1, 3, 2, 10, 6, 11, 7, 25, 29, 23, 26, 30, 5, 27, 31, 24, 28, 32, 4, 33];  // Daftar ID gudang yang relevan

        // Ambil data barang yang statusnya aktif (status = 1)
        $this->db->select('barang.id_barang, barang.nama_barang, barang.id_kategori, kb.nama_kategori')
            ->from('barang')
            ->join('kategori_barang kb', 'kb.id_kategori = barang.id_kategori', 'left') // JOIN dengan kategori_barang
            ->where('barang.status', 1)
            ->order_by('barang.id_barang', 'DESC');

        if ($id_kategori != NULL) {
            $this->db->where('barang.id_kategori', $id_kategori);
        }
        $barangData = $this->db->get()->result();

        $result = [];

        // Loop untuk setiap barang
        foreach ($barangData as $barang) {
            $row = [
                'nama_barang' => $barang->nama_barang,
                'nama_kategori' => $barang->nama_kategori, // Menyimpan nama barang
            ];

            $allZeroStock = true; // Flag untuk mengecek apakah stok di semua gudang kosong

            // Loop untuk setiap gudang yang relevan
            foreach ($customGudangOrder as $gudang_id) {
                // Query untuk mengambil stok barang per gudang
                $this->db
                    ->select('COALESCE(SUM(db.current_stock), 0) as total_stock', false)  // Menghitung total stok, 0 jika tidak ada
                    ->from('barang b')
                    ->join('detail_barang db', 'db.id_barang = b.id_barang', 'left')  // Join dengan detail barang
                    ->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang', 'left')  // Join dengan penerimaan barang
                    ->join('gudang g', 'g.id_gudang = pb.id_gudang', 'left')  // Join dengan gudang
                    ->where('b.id_barang', $barang->id_barang)  // Filter berdasarkan id_barang
                    ->where('g.id_gudang', $gudang_id)  // Filter berdasarkan id_gudang untuk masing-masing gudang
                    ->where('(db.status = 1 OR db.id_detail_barang IS NULL)', null, false)  // Sama seperti query pada getStokByBarangIdGudangId untuk status
                    ->group_by('g.nama_gudang');  // Grouping berdasarkan nama gudang untuk stok yang lebih tepat

                // Ambil total stok dari query
                $stok_row = $this->db->get()->row();  // Mendapatkan hasil query pertama

                // Cek jika hasil query null
                $stok = $stok_row ? $stok_row->total_stock : 0;  // Jika tidak ada hasil, set stok = 0

                // Tambahkan stok gudang ke dalam row dengan kunci dinamis (stok_gudang_X)
                $row["stok_gudang_{$gudang_id}"] = $stok;

                // Jika ada stok yang lebih dari 0, set allZeroStock ke false
                if ($stok > 0) {
                    $allZeroStock = false;
                }
            }

            // Jika stok untuk semua gudang adalah 0, barang tidak dimasukkan ke hasil
            if (!$allZeroStock) {
                $result[] = $row;
            }
        }

        return $result;  // Kembalikan hasil query dalam bentuk array
    }


    function getAllStockExport2($id_kategori = null)
    {
        $customGudangOrder = [1, 3, 2, 10, 6, 11, 7, 25, 29, 23, 26, 30, 5, 27, 31, 24, 28, 32, 4, 33];

        // Ambil data barang yang statusnya aktif (status = 1) dan sesuai dengan id_kategori jika diberikan
        $this->db->select('id_barang, nama_barang, id_kategori')
            ->from('barang')
            ->where('status', 1);

        if (!is_null($id_kategori)) {
            $this->db->where('id_kategori', $id_kategori);
        }

        $this->db->order_by('id_barang', 'DESC');
        $barangData = $this->db->get()->result();

        $result = [];

        foreach ($barangData as $barang) {
            $row = [
                'nama_barang' => $barang->nama_barang,
                'id_kategori' => $barang->id_kategori
            ];

            $allZeroStock = true;

            foreach ($customGudangOrder as $gudang_id) {
                $this->db
                    ->select('COALESCE(SUM(db.current_stock), 0) as total_stock', false)
                    ->from('barang b')
                    ->join('detail_barang db', 'db.id_barang = b.id_barang', 'left')
                    ->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang', 'left')
                    ->join('gudang g', 'g.id_gudang = pb.id_gudang', 'left')
                    ->where('b.id_barang', $barang->id_barang)
                    ->where('g.id_gudang', $gudang_id)
                    ->where('(db.status = 1 OR db.id_detail_barang IS NULL)', null, false)
                    ->group_by('g.nama_gudang');

                $stok_row = $this->db->get()->row();
                $stok = $stok_row ? $stok_row->total_stock : 0;

                $row["stok_gudang_{$gudang_id}"] = $stok;

                if ($stok > 0) {
                    $allZeroStock = false;
                }
            }

            if (!$allZeroStock) {
                $result[] = $row;
            }
        }

        return $result;
    }
}
