<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Syarat_pembayaran extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_syarat_pembayaran');
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
		$page_data['page_name']  = 'v_syarat_pembayaran';
        $page_data['page_title'] = 'Syarat Pembayaran';
        $page_data['page_desc']  = 'Management Data Syarat Pembayaran';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_syarat_pembayaran'] = $this->input->post('nama_syarat_pembayaran');
        checkEmptyForm($data);

        $this->md_syarat_pembayaran->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data syarat pembayaran  - ' . $data['nama_syarat_pembayaran'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_syarat_pembayaran->getById($id);
        foreach ($dt as $row) {
            $row->id_syarat_pembayaran = encrypt($row->id_syarat_pembayaran);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        //cek apakah data sudah di gunakan
        // $cek = $this->md_barang->getByWhere(['b.id_syarat_pembayaran' => decrypt($param)]);
        // if ($cek) {
        //     ajaxReturnDie('error', 'Data sudah di gunakan!');
        // }

        $id_syarat_pembayaran    = decrypt($param);
        $data['status'] = 0;
        $this->md_syarat_pembayaran->update(['id_syarat_pembayaran' => $id_syarat_pembayaran], $data);

        //add log
        $temp = $this->md_syarat_pembayaran->getById($id_syarat_pembayaran);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data syarat pembayaran - ' . $temp[0]->nama_syarat_pembayaran;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'syarat_pembayaran berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_syarat_pembayaran'));
        $data['nama_syarat_pembayaran'] = $this->input->post('nama_syarat_pembayaran');
        checkEmptyForm($data);

        $this->md_syarat_pembayaran->update(['id_syarat_pembayaran' => $id], $data);

        //add log
        $temp = $this->md_syarat_pembayaran->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data Syarat pembayaran - ' . $temp[0]->nama_syarat_pembayaran;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_syarat_pembayaran->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_syarat_pembayaran);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="syarat_pembayaran/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_syarat_pembayaran;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
