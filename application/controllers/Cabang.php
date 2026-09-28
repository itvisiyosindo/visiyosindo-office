<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Cabang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_cabang');
        $this->load->model('md_barang');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']  	= 'v_cabang';
        $page_data['page_title'] 	= 'Cabang';
        $page_data['page_desc']  	= 'Management Data Cabang';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_cabang'] = $this->input->post('nama_cabang');
        $data['alamat_cabang'] = $this->input->post('alamat_cabang');
        $data['penanggung_jawab'] = $this->input->post('penanggung_jawab');
        checkEmptyForm($data);

        $this->md_cabang->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data cabang  - ' . $data['nama_cabang'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_cabang->getById($id);
        foreach ($dt as $row) {
            $row->id_cabang = encrypt($row->id_cabang);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        //cek apakah data sudah di gunakan
        $cek = $this->md_barang->getByWhere(['b.id_cabang' => decrypt($param)]);
        if ($cek) {
            ajaxReturnDie('error', 'Data sudah di gunakan!');
        }

        $id_cabang    = decrypt($param);
        $data['status'] = 0;
        $this->md_cabang->update(['id_cabang' => $id_cabang], $data);

        //add log
        $temp = $this->md_cabang->getById($id_cabang);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data cabang - ' . $temp[0]->nama_cabang;
        
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'cabang berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_cabang'));
        $data['nama_cabang'] = $this->input->post('nama_cabang');
        $data['alamat_cabang'] = $this->input->post('alamat_cabang');
        $data['penanggung_jawab'] = $this->input->post('penanggung_jawab');
        checkEmptyForm($data);

        $this->md_cabang->update(['id_cabang' => $id], $data);

        //add log
        $temp = $this->md_cabang->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data cabang - ' . $temp[0]->nama_cabang;
        
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_cabang->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_cabang);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="cabang/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_cabang;
            $th[] = $row->penanggung_jawab;
            $th[] = $row->alamat_cabang;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
