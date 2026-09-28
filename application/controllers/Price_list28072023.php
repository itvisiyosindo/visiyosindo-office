<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class price_list extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_price_list');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']  	= 'v_price_list';
        $page_data['page_title'] 	= 'Price List';
        $page_data['page_desc']		= 'Management Data Price List';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_price_list']= $this->input->post('nama_price_list');
        $data['kategori']       = $this->input->post('kategori');
        $data['link_download']  = $this->input->post('link_download');
        $data['diskon']         = $this->input->post('diskon');

        checkEmptyForm($data);
        $this->md_price_list->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data Proce List - ' . $data['nama_price_list'];
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }


    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_price_list->getById($id);
        foreach ($dt as $row) {
            $row->id_price_list = encrypt($row->id_price_list);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        $id_price_list    = decrypt($param);

        $data['status'] = 0;
        $this->md_price_list->update(['id_price_list' => $id_price_list], $data);

        //add log
        $temp = $this->md_price_list->getById($id_price_list);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data price_list - ' . $temp[0]->nama_price_list;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'price_list berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_price_list'));
        $data['nama_price_list']= $this->input->post('nama_price_list');
        $data['kategori']       = $this->input->post('kategori');
        $data['link_download']  = $this->input->post('link_download');
        $data['diskon']         = $this->input->post('diskon');
        checkEmptyForm($data);

        $this->md_price_list->update(['id_price_list' => $id], $data);

        //add log
        $temp = $this->md_price_list->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data price_list - ' . $temp[0]->nama_price_list;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_price_list->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_price_list);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="price_list/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $link_download = '<a href="' . $row->link_download . '"><i class="fas fa-download"></i> Download</a>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_price_list;
            $th[] = $row->kategori;
            $th[] = $row->diskon;
            $th[] = $link_download;
            $th[] = date('Y-m-d',strtotime($row->data_created));
            if (isAdmin() || isStafAdmin()) {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
