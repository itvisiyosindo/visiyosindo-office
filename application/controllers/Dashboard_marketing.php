<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_marketing extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_funnel');
    }
	
	function id_navbar(){
		$id_navbar = "marketing";
		return $id_navbar;
	}


  

   public function chart_data()
    {
        $tahun = $this->input->get('tahun') ?: date('Y'); // Gunakan tahun dari request atau default ke tahun sekarang

        $jumlah_id = $this->md_funnel->get_jumlah_id_per_bulan($tahun);

        $result = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $result[$bulan] = [
                'month' => $bulan,
                '105' => 0,
                '742' => 0,
                '745' => 0,
            ];
        }

        foreach ($jumlah_id as $row) {
            $result[$row->bulan][$row->idmarketing] = $row->jumlah_id;
        }

        echo json_encode(array_values($result));
    }



    public function chart_data_funnel()
    {
       
        $tahun = $this->input->get('tahun') ?: date('Y'); // Gunakan tahun dari request atau default ke tahun sekarang

        $jumlah_id = $this->md_funnel->get_funnel_id_per_bulan($tahun);

        // Persiapkan array untuk menyimpan data yang terstruktur
        $result = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $result[$bulan] = [
                'month' => $bulan,
                '105' => 0, // Default untuk ID 105
                '742' => 0, // Default untuk ID 742
                '745' => 0, // Default untuk ID 745
            ];
        }

        // Isi data sesuai hasil query
        foreach ($jumlah_id as $row) {
            $result[$row->bulan][$row->idmarketing] = $row->jumlah_id;
        }

        // Kembalikan data dalam format JSON
        echo json_encode(array_values($result));
    }

    public function chart_data_fpp()
    {
        
        $tahun = $this->input->get('tahun') ?: date('Y'); // Gunakan tahun dari request atau default ke tahun sekarang
        $jumlah_id = $this->md_funnel->get_fpp_id_per_bulan($tahun);

        // Persiapkan array untuk menyimpan data yang terstruktur
        $result = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $result[$bulan] = [
                'month' => $bulan,
                '105' => 0, // Default untuk ID 105
                '742' => 0, // Default untuk ID 742
                '745' => 0, // Default untuk ID 745
            ];
        }

        // Isi data sesuai hasil query
        foreach ($jumlah_id as $row) {
            $result[$row->bulan][$row->id_pengaju] = $row->jumlah_id;
        }

        // Kembalikan data dalam format JSON
        echo json_encode(array_values($result));
    }




	
    public function index()
    {
        grantAccessFor('all');
		
		$page_data['switch']      	= $this->id_navbar();
        $page_data['page_name']   	= 'dashboard_marketing';
        $page_data['page_title']  	= 'Marketing';
        $page_data['countcalonpelanggan'] 	= $this->md_funnel->countCalonPelanggan();
        $page_data['countpelanggan'] 		= $this->md_funnel->countPelanggan();
        $page_data['countfunnel'] 			= $this->md_funnel->countFunnel();
        

        $this->load->view('index', $page_data);
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
