<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_dokumen extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_dashboard');
        $this->load->model('md_log');
        $this->load->model('md_dokumen');
    }

    function id_navbar()
    {
        return "dokumen";
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'dashboard_dokumen';
        $page_data['page_title']    = 'Dashboard Dokumen & Persuratan';
        $page_data['tahun_sekarang'] = date('Y');

        // 1. Total Surat Terbit (surat_list)
        $page_data['total_surat'] = $this->db
            ->count_all_results('surat_list');

        // 2. Total Dokumen Umum
        $page_data['total_dokumen_umum'] = $this->db
            ->where('status', 1)
            ->count_all_results('dokumen_umum');

        // 3. Total Dokumen Produk & Brosur
        $page_data['total_dokumen_product'] = $this->db
            ->where('status', 1)
            ->count_all_results('dokumen_product');

        // 4. Total Dokumen Rahasia
        $page_data['total_dokumen_rahasia'] = $this->db
            ->where('status', 1)
            ->count_all_results('dokumen_rahasia');

        // 5. Total Dokumen Dibuat Bulan Ini (Dokumen Umum + Produk)
        $doc_umum_month = $this->db
            ->where('status', 1)
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->count_all_results('dokumen_umum');

        $doc_prod_month = $this->db
            ->where('status', 1)
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->count_all_results('dokumen_product');

        $page_data['surat_bulan_ini'] = $doc_umum_month + $doc_prod_month;

        // 6. Daftar 10 Dokumen Terbaru
        $page_data['dokumen_terbaru'] = $this->db
            ->select('
                du.id as id_dokumen,
                du.nama_dokumen,
                du.created_at,
                COALESCE(kt.nama, "Umum") as nama_kategori,
                COALESCE(p.nama, "-") as nama_pengunggah
            ')
            ->from('dokumen_umum du')
            ->join('dokumen_kategori kt', 'du.id_kategori = kt.id', 'left')
            ->join('pengguna p', 'p.pengguna_id = du.created_by', 'left')
            ->where('du.status', 1)
            ->order_by('du.id', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // 7. Distribusi Jenis Dokumen
        $page_data['distribusi_dokumen'] = [
            'Surat & Izin Dinas' => (int)$page_data['total_surat'],
            'Dokumen Umum'       => (int)$page_data['total_dokumen_umum'],
            'Dokumen Produk'     => (int)$page_data['total_dokumen_product'],
            'Dokumen Rahasia'    => (int)$page_data['total_dokumen_rahasia']
        ];

        $this->load->view('index', $page_data);
    }

    public function chart_data_dokumen()
    {
        $tahun = $this->input->get('tahun') ?: date('Y');

        // Dokumen Umum per bulan
        $umum = $this->db
            ->select('MONTH(created_at) as bulan, COUNT(id) as total')
            ->from('dokumen_umum')
            ->where('status', 1)
            ->where('YEAR(created_at)', $tahun)
            ->group_by('MONTH(created_at)')
            ->get()
            ->result();

        // Dokumen Produk per bulan
        $product = $this->db
            ->select('MONTH(created_at) as bulan, COUNT(id) as total')
            ->from('dokumen_product')
            ->where('status', 1)
            ->where('YEAR(created_at)', $tahun)
            ->group_by('MONTH(created_at)')
            ->get()
            ->result();

        $result = [];
        for ($b = 1; $b <= 12; $b++) {
            $result[$b] = [
                'month'   => $b,
                'surat'   => 0,
                'dokumen' => 0
            ];
        }

        foreach ($umum as $row) {
            $result[$row->bulan]['surat'] = (int)$row->total;
        }

        foreach ($product as $row) {
            $result[$row->bulan]['dokumen'] = (int)$row->total;
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
