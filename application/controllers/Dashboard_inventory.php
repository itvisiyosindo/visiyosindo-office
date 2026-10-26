<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_inventory extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_dashboard');
        $this->load->model('md_log');
        $this->load->model('md_barang');
    }

    function id_navbar()
    {
        return "inventory";
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'dashboard_inventory';
        $page_data['page_title']    = 'Dashboard Inventory & Pergudangan';
        $page_data['tahun_sekarang'] = date('Y');

        // 1. Total Master Barang Aktif
        $page_data['total_barang'] = $this->db
            ->where('status', 1)
            ->count_all_results('barang');

        // 2. Total Gudang Aktif
        $page_data['total_gudang'] = $this->db
            ->where('status', 1)
            ->count_all_results('gudang');

        // 3. Total Penerimaan Barang Bulan Ini
        $page_data['total_penerimaan_bulan_ini'] = $this->db
            ->where('status', 1)
            ->where('MONTH(data_created)', date('m'))
            ->where('YEAR(data_created)', date('Y'))
            ->count_all_results('penerimaan_barang');

        // 4. Total Pengeluaran Barang Bulan Ini
        $page_data['total_pengeluaran_bulan_ini'] = $this->db
            ->where('status', 1)
            ->where('MONTH(data_created)', date('m'))
            ->where('YEAR(data_created)', date('Y'))
            ->count_all_results('pengeluaran_barang');

        // 5. Daftar Barang dengan Stok Kritis / Menipis (Stok <= Batas Minimum)
        $page_data['barang_kritis'] = $this->db
            ->select('
                b.id_barang,
                b.kode_barang,
                b.nama_barang,
                b.batas_min_stock,
                kb.nama_kategori,
                sb.nama_satuan,
                COALESCE(SUM(db.current_stock), 0) as total_stock
            ')
            ->from('barang b')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            ->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left')
            ->join('detail_barang db', 'db.id_barang = b.id_barang AND db.status = 1', 'left')
            ->where('b.status', 1)
            ->where('b.batas_min_stock >', 0)
            ->group_by('b.id_barang')
            ->having('total_stock <= b.batas_min_stock')
            ->order_by('total_stock', 'ASC')
            ->limit(10)
            ->get()
            ->result();

        // 6. Distribusi 5 Kategori Barang Terbanyak
        $page_data['kategori_populer'] = $this->db
            ->select('kb.nama_kategori, COUNT(b.id_barang) as total')
            ->from('barang b')
            ->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left')
            ->where('b.status', 1)
            ->group_by('b.id_kategori')
            ->order_by('total', 'DESC')
            ->limit(5)
            ->get()
            ->result();

                // 7. Total Stok Berdasarkan Gudang Penyimpanan
        $page_data['gudang_stok'] = $this->db->query("
            SELECT 
                g.id_gudang,
                g.nama_gudang,
                g.lokasi,
                g.jenis,
                COUNT(DISTINCT db.id_barang) as total_sku,
                COALESCE(SUM(db.current_stock), 0) as total_stok
            FROM gudang g
            LEFT JOIN penerimaan_barang pb ON pb.id_gudang = g.id_gudang AND pb.status = 1
            LEFT JOIN detail_barang db ON db.id_penerimaan_barang = pb.id_penerimaan_barang AND db.status = 1 AND db.current_stock > 0
            WHERE g.status = 1
            GROUP BY g.id_gudang, g.nama_gudang, g.lokasi, g.jenis
            HAVING total_stok > 0
            ORDER BY total_stok DESC
        ")->result();

        // 8. Total Stok Berdasarkan Kategori Barang (Unit Qty Stok Fisik)
        $page_data['kategori_stok'] = $this->db->query("
            SELECT 
                kb.id_kategori,
                kb.nama_kategori,
                COUNT(DISTINCT b.id_barang) as total_sku,
                COALESCE(SUM(db.current_stock), 0) as total_stok
            FROM kategori_barang kb
            JOIN barang b ON b.id_kategori = kb.id_kategori AND b.status = 1
            JOIN detail_barang db ON db.id_barang = b.id_barang AND db.status = 1 AND db.current_stock > 0
            JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang AND pb.status = 1
            GROUP BY kb.id_kategori, kb.nama_kategori
            HAVING total_stok > 0
            ORDER BY total_stok DESC
        ")->result();

        // 9. Total Stok Fisik Ready
        $stokFisikRow = $this->db->query("
            SELECT COALESCE(SUM(db.current_stock), 0) as total_stok
            FROM detail_barang db
            JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang AND pb.status = 1
            WHERE db.status = 1 AND db.current_stock > 0
        ")->row();
        $page_data['total_stok_fisik'] = $stokFisikRow ? (int)$stokFisikRow->total_stok : 0;

        $this->load->view('index', $page_data);
    }

    public function chart_data_transaksi()
    {
        $tahun = (int)($this->input->get('tahun') ?: date('Y'));

        // Query Penerimaan per bulan (Trx & Qty)
        $penerimaan = $this->db
            ->select('MONTH(pb.tgl_masuk) as bulan, COUNT(DISTINCT pb.id_penerimaan_barang) as total, COALESCE(SUM(db.qty), 0) as total_qty')
            ->from('penerimaan_barang pb')
            ->join('detail_barang db', 'db.id_penerimaan_barang = pb.id_penerimaan_barang AND db.status = 1', 'left')
            ->where('pb.status', 1)
            ->where('YEAR(pb.tgl_masuk)', $tahun)
            ->group_by('MONTH(pb.tgl_masuk)')
            ->get()
            ->result();

        // Query Pengeluaran per bulan (Trx & Qty)
        $pengeluaran = $this->db
            ->select('MONTH(pb.tgl_keluar) as bulan, COUNT(DISTINCT pb.id_pengeluaran_barang) as total, COALESCE(SUM(dbk.qty), 0) as total_qty')
            ->from('pengeluaran_barang pb')
            ->join('detail_barang_keluar dbk', 'dbk.id_pengeluaran_barang = pb.id_pengeluaran_barang AND dbk.status = 1', 'left')
            ->where('pb.status', 1)
            ->where('YEAR(pb.tgl_keluar)', $tahun)
            ->group_by('MONTH(pb.tgl_keluar)')
            ->get()
            ->result();

        $result = [];
        for ($b = 1; $b <= 12; $b++) {
            $result[$b] = [
                'month'       => $b,
                'penerimaan'  => 0,
                'pengeluaran' => 0,
                'qty_masuk'   => 0,
                'qty_keluar'  => 0
            ];
        }

        foreach ($penerimaan as $row) {
            $result[(int)$row->bulan]['penerimaan'] = (int)$row->total;
            $result[(int)$row->bulan]['qty_masuk']   = (int)$row->total_qty;
        }

        foreach ($pengeluaran as $row) {
            $result[(int)$row->bulan]['pengeluaran'] = (int)$row->total;
            $result[(int)$row->bulan]['qty_keluar']  = (int)$row->total_qty;
        }

        header('Content-Type: application/json');
        echo json_encode(array_values($result));
        exit;
    }

    public function pagination_log()
    {
        grantAccessFor('all');

        $dt = $this->md_log->getAllLog();
        $start = $this->input->post('start');
        $data = array();
        foreach ($dt['data'] as $row) {
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_pengguna ? $row->nama_pengguna : '-';
            $th[] = $row->jenis_aksi;
            $th[] = $row->keterangan;
            $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y | H:i', strtotime($row->tgl));
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
