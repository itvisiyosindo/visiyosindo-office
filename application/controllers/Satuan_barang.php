<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Satuan_barang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_satuan_barang');
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
		$page_data['page_name']  = 'v_satuan_barang';
        $page_data['page_title'] = 'Satuan Barang';
        $page_data['page_desc']  = 'Management Data Satuan Barang';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_satuan'] = $this->input->post('nama_satuan');

        $this->md_satuan_barang->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data Satuan Barang - ' . $data['nama_satuan'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_satuan_barang->getById($id);
        foreach ($dt as $row) {
            $row->id_satuan = encrypt($row->id_satuan);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        //cek apakah data sudah di gunakan
        $cek = $this->md_barang->getByWhere(['b.id_satuan_barang' => decrypt($param)]);
        if ($cek) {
            ajaxReturnDie('error', 'Data sudah di gunakan!');
        }
        $id_satuan    = decrypt($param);
        $data['status'] = 0;
        $this->md_satuan_barang->update(['id_satuan' => $id_satuan], $data);

        //add log
        $temp = $this->md_satuan_barang->getById($id_satuan);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data Satuan Barang - ' . $temp[0]->nama_satuan;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_satuan'));
        $data['nama_satuan'] = $this->input->post('nama_satuan');

        $this->md_satuan_barang->update(['id_satuan' => $id], $data);

        //add log
        $temp = $this->md_satuan_barang->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data Satuan Barang - ' . $temp[0]->nama_satuan;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_satuan_barang->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_satuan);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="satuan_barang/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_satuan;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
