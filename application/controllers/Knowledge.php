<?php

use FontLib\Table\Type\post;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Knowledge extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_knowledge');
        $this->load->model('md_laporan');
        $this->load->model('md_pengguna');


        $this->load->model('md_pelanggan');
        $this->load->model('md_prov_kota');
        $this->load->model('md_kategori_tiket');
        $this->load->model('md_tiket');
    }
	
	function id_navbar(){
		$id_navbar = "kepegawaian";
		return $id_navbar;
	}

    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor('all');

        if ($param == 'knowledge') {
            
            $page_data['switch']      	= $this->id_navbar();
            $page_data['page_name']     = 'knowledge/v_knowledge';
            $page_data['page_title']    = 'Test Product Knowledge';
            $page_data['page_desc']     = 'Management Data Kepegawaian';
            $page_data['list_nama']     = $this->md_laporan->getBywhereActive();
            $tahun = $this->input->get('tahun'); 
            $page_data['list_knowledge'] = $this->md_knowledge->getAllKnowledge($tahun);
            $this->load->view('index', $page_data);

        }else if ($param == 'detail') {
			      $page_data['switch']      		= $this->id_navbar();
            $page_data['list_nama']         = $this->md_laporan->getBywhereActive();
            $page_data['pengguna']          = $this->md_pengguna->getById(decrypt($param2));
            $page_data['page_name']         = 'knowledge/v_knowledge_detail';
            $page_data['page_title']        = 'Test Product Knowledge';
            $page_data['page_desc']         = 'Management Data Kepegawaian';
            $this->load->view('index', $page_data);
			
        }else if ($param == 'my_detail') {
			      $page_data['switch']      		= $this->id_navbar();
            $page_data['list_nama']         = $this->md_laporan->getBywhereActive();
            $page_data['pengguna']          = $this->md_pengguna->getById(sessPenggunaId());
            $page_data['page_name']         = 'knowledge/v_my_knowledge_detail';
            $page_data['page_title']        = 'Test Product Knowledge';
            $page_data['page_desc']         = 'Management Data Kepegawaian';
            $this->load->view('index', $page_data);
			
        }


    }

    public function add()
    {
        grantAccessFor('all');

        $data['id_pengguna']	= $this->input->post('id_pengguna', TRUE);
        $data['nilai']    		= $this->input->post('nilai', TRUE);

        $ambilDataEv   = $this->md_pengguna->getById($data['id_pengguna']);
        $namaPegawai   = $ambilDataEv[0]->nama;

        $this->md_knowledge->add($data);

        /** LOG */
        addLog('Product Knowledge', 'Input nilai pegawai ' .$namaPegawai. ' sebesar ' . $data['nilai']);
        ajaxReturnDie('success', 'Pelanggan berhasil ditambahkan', TRUE);
    }


    public function pagination($param = "")
    {
        grantAccessFor('all');

        $pengguna_id = decrypt($param);
        $dt    = $this->md_knowledge->getAllDetail($pengguna_id);
        $start = $this->input->post('start');
        $data  = array();


        foreach ($dt['data'] as $row) {
            $id       	= encrypt($row->id);
            $li_btn   	= '
                <div class="btn-group" role="group" aria-label="First group">
                <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                </div>';


            $tanggal = $row->bulan; // Misalnya: '2025-05-27'
            $timestamp = strtotime($tanggal);
            $bulan = date('n', $timestamp); // Mengambil angka bulan (1-12)
            $tahun = date('Y', $timestamp); // Mengambil tahun

            $nama_bulan = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];

            $nama_bulan = $nama_bulan[$bulan] . ' ' . $tahun;

                    
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $nama_bulan; // Menggunakan nama bulan dalam bahasa Indonesia
            $th[] = $row->nilai;
            $data[] = $th;
        }
        
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }


    public function edit($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_knowledge->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }



}
