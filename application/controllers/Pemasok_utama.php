<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Pemasok_utama extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pemasok_utama');
        $this->load->model('md_penerimaan_barang');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']      	= $this->id_navbar();
		$page_data['page_name']  	= 'v_pemasok_utama';
        $page_data['page_title'] 	= 'Pemasok Utama';
        $page_data['page_desc']  	= 'Management Data Pemasok Utama';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_pemasok'] = $this->input->post('nama_pemasok');
        $data['alamat_pemasok'] = $this->input->post('alamat_pemasok');
        $data['contact'] = $this->input->post('contact');
        $data['warranty'] = $this->input->post('warranty');
        checkEmptyForm($data);

        $this->md_pemasok_utama->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data Pemasok - ' . $data['nama_pemasok'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_pemasok_utama->getById($id);
        foreach ($dt as $row) {
            $row->id_pemasok = encrypt($row->id_pemasok);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        //cek apakah data sudah di gunakan
        $cek = $this->md_penerimaan_barang->getByWhere(['pb.id_pemasok' => decrypt($param)]);
        if ($cek) {
            ajaxReturnDie('error', 'Data sudah di gunakan!');
        }

        $id_pemasok    = decrypt($param);
        $data['status'] = 0;
        $this->md_pemasok_utama->update(['id_pemasok' => $id_pemasok], $data);

        //add log
        $temp = $this->md_pemasok_utama->getById($id_pemasok);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data Pemasok - ' . $temp[0]->nama_pemasok;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');
        
        $id = decrypt($this->input->post('id_pemasok'));
        $data['nama_pemasok'] = $this->input->post('nama_pemasok');
        $data['alamat_pemasok'] = $this->input->post('alamat_pemasok');
        $data['contact'] = $this->input->post('contact');
        $data['warranty'] = $this->input->post('warranty');
        checkEmptyForm($data);

        $this->md_pemasok_utama->update(['id_pemasok' => $id], $data);

        //add log
        $temp = $this->md_pemasok_utama->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data Pemasok - ' . $temp[0]->nama_pemasok;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_pemasok_utama->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_pemasok);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="pemasok_utama/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_pemasok;
            $th[] = $row->alamat_pemasok;
            $th[] = $row->contact;
            $th[] = $row->warranty;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
