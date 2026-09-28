<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Kategori_pajak extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_kategori_pajak');
    }
	
	function id_navbar(){
		$id_navbar = "helpdesk";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');
        
		$page_data['switch']		= $this->id_navbar();
		$page_data['page_name'] 	= 'kategori_pajak/v_kategori_pajak';
        $page_data['page_title']	= 'Kategori Pajak';
        $page_data['page_desc'] 	= 'Management Data Kategori Pajak';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['kategori']   = $this->input->post('kategori');
        $data['deskripsi']  = $this->input->post('deskripsi');
        $data['besaran']  = $this->input->post('besaran');
        $data['created_by'] = sessPenggunaId();
        checkEmptyForm($data);

        $this->md_kategori_pajak->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data kategori pajak - ' . $data['kategori'];
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_kategori_pajak->getById($id);
        foreach ($dt as $row) {
            $row->id_bhn_presentasi = encrypt($row->id_bhn_presentasi);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        $id_bhn_presentasi    = decrypt($param);
        $data['status'] = 0;
        $this->md_kategori_pajak->update(['id_bhn_presentasi' => $id_bhn_presentasi], $data);

        //add log
        $temp = $this->md_kategori_pajak->getById($id_bhn_presentasi);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data presentasi - ' . $temp[0]->nama_bhn_presentasi;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'presentasi berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_bhn_presentasi'));
        $data['nama_bhn_presentasi'] = $this->input->post('nama_file');
        $data['link_download'] = $this->input->post('link_download');
        checkEmptyForm($data);
        $this->md_kategori_pajak->update(['id_bhn_presentasi' => $id], $data);

        //add log
        $temp = $this->md_kategori_pajak->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data presentasi - ' . $temp[0]->nama_bhn_presentasi;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_kategori_pajak->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->idKatPajak);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="kategori_pajak/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->kategori;
            $th[] = $row->deskripsi;
            $th[] = $row->besaran;
            if (isAdmin() || sessPenggunaId()==81) {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function sendWa()
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_bhn_presentasi'));
        $hp = $this->input->post('wa_tujuan');
        $nama = $this->session->userdata('nama');
        $level = $this->session->userdata('login_type');
        $data = $this->md_kategori_pajak->getById($id);
        $message = '&text=Nama%20Product%20%3A%20'.$data[0]->nama_bhn_presentasi.'%0A%0ALink%20Brosur%20%3A%20'.$data[0]->link_download.'%0A%0A%0Abest%20regard%2C%0A'.$nama.',%20('.$level.')%0APT%20VISI%20YOSINDO%20MEDIKAL';
        $hp_tujuan = hp($hp);
        $link = 'https://api.whatsapp.com/send?phone='. $hp_tujuan . $message;
        redirect($link);
        die;
    }
}
