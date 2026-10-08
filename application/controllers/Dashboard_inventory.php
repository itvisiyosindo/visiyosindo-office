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

        $this->load->view('index', $page_data);
    }

    public function chart_data_transaksi()
    {
        $tahun = $this->input->get('tahun') ?: date('Y');

        // Query Penerimaan per bulan
        $penerimaan = $this->db
            ->select('MONTH(data_created) as bulan, COUNT(id_penerimaan_barang) as total')
            ->from('penerimaan_barang')
            ->where('status', 1)
            ->where('YEAR(data_created)', $tahun)
            ->group_by('MONTH(data_created)')
            ->get()
            ->result();

        // Query Pengeluaran per bulan
        $pengeluaran = $this->db
            ->select('MONTH(data_created) as bulan, COUNT(id_pengeluaran_barang) as total')
            ->from('pengeluaran_barang')
            ->where('status', 1)
            ->where('YEAR(data_created)', $tahun)
            ->group_by('MONTH(data_created)')
            ->get()
            ->result();

        $result = [];
        for ($b = 1; $b <= 12; $b++) {
            $result[$b] = [
                'month'       => $b,
                'penerimaan'  => 0,
                'pengeluaran' => 0
            ];
        }

        foreach ($penerimaan as $row) {
            $result[$row->bulan]['penerimaan'] = (int)$row->total;
        }

        foreach ($pengeluaran as $row) {
            $result[$row->bulan]['pengeluaran'] = (int)$row->total;
        }

        echo json_encode(array_values($result));
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
