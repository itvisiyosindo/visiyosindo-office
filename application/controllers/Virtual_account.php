<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Virtual_account extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_virtual_account');
        $this->load->model('md_customer');
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
		$page_data['page_name'] 	= 'v_virtual_account';
        $page_data['page_title'] 	= 'Virtual Account';
        $page_data['page_desc']  	= 'Management Data Virtual Account';
        $page_data['bank']  		= $this->md_bank->getByWhere(['b.status', 1]);
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['no_va'] = $this->input->post('no_va');
        $data['id_customer'] = decrypt($this->input->post('id_customer'));
        $data['id_bank'] = decrypt($this->input->post('id_bank'));
        checkEmptyForm($data);

        $this->md_virtual_account->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data virtual_account  - ' . $data['no_va'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_virtual_account->getById($id);
        foreach ($dt as $row) {
            $row->id_virtual_account = encrypt($row->id_virtual_account);
            $row->id_bank = encrypt($row->id_bank);
            $row->id_customer = encrypt($row->id_customer);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        //cek apakah data sudah di gunakan
        // $cek = $this->md_barang->getByWhere(['b.id_virtual_account' => decrypt($param)]);
        // if ($cek) {
        //     ajaxReturnDie('error', 'Data sudah di gunakan!');
        // }

        $id_virtual_account    = decrypt($param);
        $data['status'] = 0;
        $this->md_virtual_account->update(['id_virtual_account' => $id_virtual_account], $data);

        //add log
        $temp = $this->md_virtual_account->getById($id_virtual_account);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data virtual_account - ' . $temp[0]->no_va;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'virtual_account berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_virtual_account'));
        $data['no_va'] = $this->input->post('no_va');
        $data['id_bank'] = decrypt($this->input->post('id_bank'));
        $data['id_customer'] = decrypt($this->input->post('id_customer'));
        checkEmptyForm($data);

        $this->md_virtual_account->update(['id_virtual_account' => $id], $data);

        //add log
        $temp = $this->md_virtual_account->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data virtual_account - ' . $temp[0]->no_va;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_virtual_account->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_virtual_account);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="virtual_account/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->no_va;
            $th[] = $row->nama_customer;
            $th[] = $row->nama_bank;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
