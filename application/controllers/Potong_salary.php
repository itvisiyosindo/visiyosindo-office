<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Potong_salary extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pengguna');
        $this->load->model('md_salary');
        $this->load->model('md_absensi');
        $this->load->model('md_potong_salary');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor(['Administrator', 'Hrd']);

        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']     = 'v_pengguna';
        $page_data['page_title']    = 'Pengguna';
        $page_data['page_desc']     = 'Management pengguna yang dapat mengakses sistem berdasarkan hak akses yang diberikan';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor(['Administrator', 'Hrd']);

        $pengguna = $this->md_pengguna->getById(decrypt($this->input->post('pengguna_id')));
        //jika trainign tidak perlu di potong
        // if ($pengguna[0]->status_karyawan != 'training') {
        //     if ($this->input->post('approval') == 'tolak') {
        //         $pengguna_id = decrypt($this->input->post('pengguna_id'));
        //         $current_salary = $this->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
        //         //cek potongan terkahir, jika tidak ada potong dari salary
        //         $latest_potong_salary = $this->md_potong_salary->getLatestPotongan($pengguna_id);
        //         if ($latest_potong_salary) {
        //             //potong dari latest potongan terakhir
        //             $data['id_absensi'] = decrypt($this->input->post('id_absensi'));
        //             $data['pengguna_id'] = $pengguna_id;
        //             $data['potongan_gaji_pokok'] = 0;
        //             $data['potongan_tunjangan_jabatan'] = 0;
        //             if ($current_salary[0]->tunjangan_konsumsi  <  $latest_potong_salary->potongan_tunjangan_konsumsi + 10000) {
        //                 $data['potongan_tunjangan_konsumsi'] = $latest_potong_salary->potongan_tunjangan_konsumsi;
        //                 $data['potongan_tunjangan_kinerja'] = $latest_potong_salary->potongan_tunjangan_kinerja + 10000;
        //             } else {
        //                 $data['potongan_tunjangan_konsumsi'] = $latest_potong_salary->potongan_tunjangan_konsumsi  + 10000;
        //                 $data['potongan_tunjangan_kinerja'] = $latest_potong_salary->potongan_tunjangan_kinerja;
        //             }
        //         } else {
        //             //potong dari latest riwayat salary
        //             $data['id_absensi'] = decrypt($this->input->post('id_absensi'));
        //             $data['pengguna_id'] = $pengguna_id;
        //             $data['potongan_gaji_pokok'] = 0;
        //             $data['potongan_tunjangan_jabatan'] = 0;
        //             if ($current_salary[0]->tunjangan_konsumsi >= 10000) {
        //                 $data['potongan_tunjangan_konsumsi'] = 10000;
        //                 $data['potongan_tunjangan_kinerja'] = 0;
        //             } else {
        //                 $data['potongan_tunjangan_konsumsi'] = 0;
        //                 $data['potongan_tunjangan_kinerja'] = 10000;
        //             }
        //         }
        //         $this->md_potong_salary->addRiwayatPemotonganSalary($data);
        //     }
        // }
        $data2['approval'] = $this->input->post('approval');
        $where = ['id_absensi' => decrypt($this->input->post('id_absensi'))];
        $this->md_absensi->updateByWhere($data2, $where);


        addLog('Absensi Approval', $data2['approval'] . ' Absensi milik ' . $pengguna[0]->nama);
        ajaxReturnDie('success', 'Data Berhasil di simpan!', 'reload_table');
    }
}
