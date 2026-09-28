<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Kategori_barang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_kategori_barang');
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
		$page_data['page_name']  	= 'v_kategori_barang';
        $page_data['page_title'] 	= 'Kategori Barang';
        $page_data['page_desc']  	= 'Management Data Kategori Barang';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_kategori'] = $this->input->post('nama_kategori');

        $this->md_kategori_barang->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data Kategori Barang - ' . $data['nama_kategori'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_kategori_barang->getById($id);
        foreach ($dt as $row) {
            $row->id_kategori = encrypt($row->id_kategori);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        //cek apakah data sudah di gunakan
        $cek = $this->md_barang->getByWhere(['b.id_kategori' => decrypt($param)]);
        if ($cek) {
            ajaxReturnDie('error', 'Data sudah di gunakan!');
        }
        $id_kategori    = decrypt($param);
        $data['status'] = 0;
        $this->md_kategori_barang->update(['id_kategori' => $id_kategori], $data);

        //add log
        $temp = $this->md_kategori_barang->getById($id_kategori);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data Kategori Barang - ' . $temp[0]->nama_kategori;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Kategori Barang berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_kategori'));
        $data['nama_kategori'] = $this->input->post('nama_kategori');

        $this->md_kategori_barang->update(['id_kategori' => $id], $data);
        
        //add log
        $temp = $this->md_kategori_barang->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data kategori barang - ' . $temp[0]->nama_kategori;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_kategori_barang->getAll();
        $start = $this->input->post('start');

        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_kategori);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="kategori_barang/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_kategori;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
