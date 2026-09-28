<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Divisi_Pengguna extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_divisi_pengguna');
    }
	
	function id_navbar(){
		$id_navbar = "kepegawaian";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);

        $page_data['switch']      	= $this->id_navbar();
		$page_data['page_name']		= 'divisi_Pengguna/v_divisi';
        $page_data['page_title']    = 'Data Divisi Pengguna';
        $page_data['page_desc']     = 'Management Data Divisi Pengguna';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor(['Administrator']);

        $data['nama']     = $this->input->post('nama', TRUE);
        $data['deskripsi']    = $this->input->post('deskripsi', TRUE);

        $this->md_divisi_pengguna->addDivisi($data);

        /** LOG */
        addLog('Menambah Divisi', 'Menambah Divisi "' . $data['nama'] . '"');
        ajaxReturnDie('success', 'Divisi berhasil ditambahkan', 'reload_table');
    }

    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);
        if ($param == 'detail_kategori') {
			$page_data['switch']		= $this->id_navbar();
            $page_data['data_pengguna'] = $this->md_divisi_pengguna->getByWhere(['t.id_topik' => decrypt($param2)]);
            $page_data['page_name']     = 'kategori_tiket/v_kategori_detail';
            $page_data['page_title']    = 'Kategori';
            $page_data['page_desc']     = 'Detail kategori';
            $this->load->view('index', $page_data);
        }
        // show_404();
    }

    public function edit($param1)
    {
        grantAccessFor(['Administrator', 'Hrd']);
        $id = decrypt($param1);
        $dt = $this->md_divisi_pengguna->getById($id);
        foreach ($dt as $row) {
            $row->id_topik = encrypt($row->id_topik);
        }
        echo json_encode($dt);
        die;
    }

    public function update($param = "", $param2 = "")
    {
        if ($param == 'is_active') {
            grantAccessFor(['Administrator']);
            $id_topik = decrypt($this->input->post('topik_id', TRUE));
            $data['is_active'] = $this->input->post('value');
            $this->md_divisi_pengguna->updateKategori($id_topik, $data);

            $datalog =  $data['is_active'] == 1 ? "Aktif" : "Tidak Aktif";
            $datalog2 = $this->md_divisi_pengguna->getById($id_topik);
            addLog('Memperbaharui Kategori', 'Mengubah status aktif "' . $datalog2[0]->nama . '" menjadi "' . $datalog . '"');
            ajaxReturnDie('success', 'Status Aktif Berhasil Diubah', TRUE);
        } else {
            grantAccessFor(['Administrator']);
            $id_topik      = decrypt($this->input->post('id_topik'));
            $data['nama'] = $this->input->post('nama');
            $data['deskripsi'] = $this->input->post('deskripsi');

            $this->md_divisi_pengguna->updatekategori($id_topik, $data);
            $data2 = $this->md_divisi_pengguna->getById($id_topik);
            addLog('Memperbaharui Kategori', 'Memperbaharui data Kategori ' . $data2[0]->nama);
            ajaxReturnDie('success', 'Kategori berhasil diperbaharui', TRUE);
        }
    }


    public function delete($param1)
    {
        grantAccessFor(['Administrator', 'HRD']);
        
        $topik_id    = decrypt($param1);

        $getUsedTopik = $this->md_tiket->getByIdTopik($topik_id);
        
        if($getUsedTopik){
            ajaxReturnDie('error', 'Kategori yang sudah digunakan tidak dapat di hapus', 'reload_table');

        }else{
            $temp           = $this->md_divisi_pengguna->getById($topik_id);
            $data['status'] = 2; //kategori di hapus
            $this->md_divisi_pengguna->updateKategori($topik_id, $data);
            addLog('Menghapus Kategori', 'Menghapus kategori ' . $temp[0]->nama);
            ajaxReturnDie('success', 'Kategori berhasil dihapus', 'reload_table');
        }
    }



    public function pagination()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);

        $dt    = $this->md_divisi_pengguna->getAllDivisi();

        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $is_active = $row->is_active == 1 ? '<span class="badge badge-ecommerce badge-success">Aktif</span>' : '<span class="badge badge-ecommerce badge-danger">Tidak Aktif</span>';
            $id       = encrypt($row->id_divisi);
            $nama_kategori = $row->nama;
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                   <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="kategori_Tiket/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $nama_kategori;
            $th[] = $row->deskripsi ? $row->deskripsi : '-';
            $th[] = $is_active;
            if (isAdmin()) {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
