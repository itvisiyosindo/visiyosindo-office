<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_helpdesk extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_dashboard');
        $this->load->model('md_log');
        $this->load->model('md_tiket');
        $this->load->model('md_kategori_tiket');
    }

    function id_navbar()
    {
        return "helpdesk";
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'dashboard_helpdesk';
        $page_data['page_title']    = 'Dashboard Helpdesk & Maintenance';
        $page_data['tahun_sekarang'] = date('Y');

        // 1. Total Semua Tiket Aktif
        $page_data['total_tiket'] = $this->db
            ->where('status_data', 1)
            ->count_all_results('tiket');

        // 2. Total Tiket Baru (Open)
        $page_data['tiket_baru'] = $this->db
            ->where('status_data', 1)
            ->where('status_tiket', 1)
            ->count_all_results('tiket');

        // 3. Total Tiket Dalam Proses (In-Progress / Revisi)
        $page_data['tiket_proses'] = $this->db
            ->where('status_data', 1)
            ->where_in('status_tiket', [2, 3])
            ->count_all_results('tiket');

        // 4. Total Tiket Selesai (Solved / Closed)
        $page_data['tiket_selesai'] = $this->db
            ->where('status_data', 1)
            ->where('status_tiket', 4)
            ->count_all_results('tiket');

        // 5. Tiket Aktif Terbaru yang Butuh Penanganan (Status != 4)
        $page_data['tiket_terbaru'] = $this->db
            ->select('
                t.id_tiket,
                t.kode_tiket,
                t.subject,
                t.prioritas,
                t.status_tiket,
                t.created_at,
                COALESCE(pel.identitas_pelanggan, t.pelanggan) as nama_pelanggan,
                tt.nama as nama_kategori,
                p.nama as nama_teknisi
            ')
            ->from('tiket t')
            ->join('topik_tiket tt', 'tt.id_topik = t.id_topik', 'left')
            ->join('pelanggan pel', 'pel.id_pelanggan = t.id_pelanggan', 'left')
            ->join('pengguna p', 'p.pengguna_id = t.id_penerima', 'left')
            ->where('t.status_data', 1)
            ->where('t.status_tiket !=', 4)
            ->order_by('t.prioritas', 'DESC')
            ->order_by('t.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // 6. Distribusi Top Kategori / Topik Keluhan Tiket
        $page_data['kategori_tiket'] = $this->db
            ->select('COALESCE(tt.nama, "Umum") as nama_kategori, COUNT(t.id_tiket) as total')
            ->from('tiket t')
            ->join('topik_tiket tt', 'tt.id_topik = t.id_topik', 'left')
            ->where('t.status_data', 1)
            ->group_by('t.id_topik')
            ->order_by('total', 'DESC')
            ->limit(5)
            ->get()
            ->result();

        $this->load->view('index', $page_data);
    }

    public function chart_data_tiket()
    {
        $tahun = $this->input->get('tahun') ?: date('Y');

        // Tiket Masuk per bulan
        $masuk = $this->db
            ->select('MONTH(created_at) as bulan, COUNT(id_tiket) as total')
            ->from('tiket')
            ->where('status_data', 1)
            ->where('YEAR(created_at)', $tahun)
            ->group_by('MONTH(created_at)')
            ->get()
            ->result();

        // Tiket Selesai per bulan
        $selesai = $this->db
            ->select('MONTH(created_at) as bulan, COUNT(id_tiket) as total')
            ->from('tiket')
            ->where('status_data', 1)
            ->where('status_tiket', 4)
            ->where('YEAR(created_at)', $tahun)
            ->group_by('MONTH(created_at)')
            ->get()
            ->result();

        $result = [];
        for ($b = 1; $b <= 12; $b++) {
            $result[$b] = [
                'month'   => $b,
                'masuk'   => 0,
                'selesai' => 0
            ];
        }

        foreach ($masuk as $row) {
            $result[$row->bulan]['masuk'] = (int)$row->total;
        }

        foreach ($selesai as $row) {
            $result[$row->bulan]['selesai'] = (int)$row->total;
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
