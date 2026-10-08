<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_visilab extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_dashboard');
        $this->load->model('md_log');
        $this->load->model('md_pengujian');
        $this->load->model('md_visilab_jadwal');
        $this->load->model('md_po_visilab');
    }

    function id_navbar()
    {
        return "visilab";
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'dashboard_visilab';
        $page_data['page_title']    = 'Dashboard Visilab (Laboratorium Kalibrasi)';
        $page_data['tahun_sekarang'] = date('Y');

        // 1. Total Seluruh Pengujian (Ukes + Upar)
        $page_data['total_pengujian'] = $this->db
            ->count_all_results('pengujian_visilab');

        // 2. Total Jadwal Pengujian / Kunjungan Bulan Ini
        $page_data['jadwal_bulan_ini'] = $this->db
            ->where('MONTH(tanggal)', date('m'))
            ->where('YEAR(tanggal)', date('Y'))
            ->count_all_results('visilab_jadwal');

        // 3. Total PO Visilab Berjalan
        $page_data['total_po_visilab'] = $this->db
            ->count_all_results('po_visilab');

        // 4. Total Dokumen & Sertifikat Visilab
        $page_data['total_dokumen_visilab'] = $this->db
            ->count_all_results('dokumen_visilab');

        // 5. Jadwal Pengujian Mendatang (Upcoming Schedule)
        $page_data['jadwal_mendatang'] = $this->db
            ->select('
                vj.id,
                vj.jenis_jadwal,
                vj.tanggal,
                vj.jam,
                vj.status,
                vj.wilayah,
                COALESCE(p.identitas_pelanggan, "-") as nama_pelanggan,
                COALESCE(pg.nama, "-") as nama_teknisi
            ')
            ->from('visilab_jadwal vj')
            ->join('pelanggan p', 'p.id_pelanggan = vj.lokasi_pelanggan_id', 'left')
            ->join('pengguna pg', 'pg.pengguna_id = vj.teknisi_id', 'left')
            ->order_by('vj.tanggal', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // 6. Distribusi Jenis Uji (Ukes vs Upar)
        $page_data['distribusi_uji'] = [
            'Uji Kesesuaian (Ukes)' => $this->db->where('id_pengujian', 1)->count_all_results('pengujian_visilab'),
            'Uji Paparan (Upar)'     => $this->db->where('id_pengujian', 2)->count_all_results('pengujian_visilab')
        ];

        $this->load->view('index', $page_data);
    }

    public function chart_data_pengujian()
    {
        $tahun = $this->input->get('tahun') ?: date('Y');

        // Pengujian Ukes (id_pengujian = 1) per bulan
        $ukes = $this->db
            ->select('MONTH(created_at) as bulan, COUNT(id) as total')
            ->from('pengujian_visilab')
            ->where('id_pengujian', 1)
            ->where('YEAR(created_at)', $tahun)
            ->group_by('MONTH(created_at)')
            ->get()
            ->result();

        // Pengujian Upar (id_pengujian = 2) per bulan
        $upar = $this->db
            ->select('MONTH(created_at) as bulan, COUNT(id) as total')
            ->from('pengujian_visilab')
            ->where('id_pengujian', 2)
            ->where('YEAR(created_at)', $tahun)
            ->group_by('MONTH(created_at)')
            ->get()
            ->result();

        $result = [];
        for ($b = 1; $b <= 12; $b++) {
            $result[$b] = [
                'month' => $b,
                'ukes'  => 0,
                'upar'  => 0
            ];
        }

        foreach ($ukes as $row) {
            $result[$row->bulan]['ukes'] = (int)$row->total;
        }

        foreach ($upar as $row) {
            $result[$row->bulan]['upar'] = (int)$row->total;
        }

        echo json_encode(array_values($result));
    }

    public function pagination_log()
    {
        grantAccessFor('all');

        $dt = $this->md_log->getAllLogVisilab();
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
