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
        $this->load->model('md_surat_list');
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
            ->where('deleted', 0)
            ->count_all_results('dokumen_umum');

        // 3. Total Dokumen Produk & Brosur
        $page_data['total_dokumen_product'] = $this->db
            ->where('deleted', 0)
            ->count_all_results('dokumen_product');

        // 4. Total Surat Diterbitkan Bulan Ini
        $page_data['surat_bulan_ini'] = $this->db
            ->where('MONTH(data_created)', date('m'))
            ->where('YEAR(data_created)', date('Y'))
            ->count_all_results('surat_list');

        // 5. Daftar 10 Dokumen / Surat Terbaru
        $page_data['dokumen_terbaru'] = $this->db
            ->select('
                sl.id_list_surat,
                sl.kode,
                sl.kategori,
                sl.data_created,
                COALESCE(p.nama, "-") as nama_pengaju
            ')
            ->from('surat_list sl')
            ->join('pengguna p', 'p.pengguna_id = sl.id_pengguna', 'left')
            ->order_by('sl.id_list_surat', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // 6. Distribusi Jenis Dokumen
        $page_data['distribusi_dokumen'] = [
            'Surat & Izin Dinas' => (int)$page_data['total_surat'],
            'Dokumen Umum'       => (int)$page_data['total_dokumen_umum'],
            'Dokumen Produk'     => (int)$page_data['total_dokumen_product'],
            'Dokumen Rahasia'    => (int)$this->db->where('deleted', 0)->count_all_results('dokumen_rahasia')
        ];

        $this->load->view('index', $page_data);
    }

    public function chart_data_dokumen()
    {
        $tahun = $this->input->get('tahun') ?: date('Y');

        // Surat per bulan
        $surat = $this->db
            ->select('MONTH(data_created) as bulan, COUNT(id_list_surat) as total')
            ->from('surat_list')
            ->where('YEAR(data_created)', $tahun)
            ->group_by('MONTH(data_created)')
            ->get()
            ->result();

        // Dokumen Umum per bulan
        $dokumen = $this->db
            ->select('MONTH(data_created) as bulan, COUNT(id_dokumen_umum) as total')
            ->from('dokumen_umum')
            ->where('deleted', 0)
            ->where('YEAR(data_created)', $tahun)
            ->group_by('MONTH(data_created)')
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

        foreach ($surat as $row) {
            $result[$row->bulan]['surat'] = (int)$row->total;
        }

        foreach ($dokumen as $row) {
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
