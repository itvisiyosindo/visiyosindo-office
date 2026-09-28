<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Bank extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_bank');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']  	= 'v_bank';
        $page_data['page_title'] 	= 'Bank';
        $page_data['page_desc']  	= 'Management Data bank';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_bank'] = $this->input->post('nama_bank');
        checkEmptyForm($data);

        $this->md_bank->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data bank  - ' . $data['nama_bank'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_bank->getById($id);
        foreach ($dt as $row) {
            $row->id_bank = encrypt($row->id_bank);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        //cek apakah data sudah di gunakan
        // $cek = $this->md_barang->getByWhere(['b.id_bank' => decrypt($param)]);
        // if ($cek) {
        //     ajaxReturnDie('error', 'Data sudah di gunakan!');
        // }

        $id_bank    = decrypt($param);
        $data['status'] = 0;
        $this->md_bank->update(['id_bank' => $id_bank], $data);

        //add log
        $temp = $this->md_bank->getById($id_bank);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data bank - ' . $temp[0]->nama_bank;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'bank berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_bank'));
        $data['nama_bank'] = $this->input->post('nama_bank');
        checkEmptyForm($data);

        $this->md_bank->update(['id_bank' => $id], $data);

        //add log
        $temp = $this->md_bank->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data bank - ' . $temp[0]->nama_bank;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_bank->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_bank);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="bank/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_bank;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
