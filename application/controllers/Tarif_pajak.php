<?php

use FontLib\Table\Type\post;

defined('BASEPATH') OR exit('No direct script access allowed');

class tarif_pajak extends CI_Controller {
	function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_tarif_pajak');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');
        
        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']  	= 'v_tarif_pajak';
        $page_data['page_title'] 	= 'tarif_pajak';
        $page_data['page_desc']  	= 'Management Data Tarif Pajak';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        $data['persentase'] = $this->input->post('persentase');
        $data['nama_pajak'] = $this->input->post('nama_pajak');

        $this->md_tarif_pajak->add($data);
        ajaxReturnDie('success','Data Berhasil Ditambahkan','reload_table');
    }

    public function edit($param1){
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_tarif_pajak->getById($id);
        foreach($dt as $row){
            $row->id_tarif_pajak = encrypt($row->id_tarif_pajak);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param) 
    {
        grantAccessFor('all');

        $id_tarif_pajak    = decrypt($param);
        $data['status'] = 0;
        $this->md_tarif_pajak->update(['id_tarif_pajak'=> $id_tarif_pajak],$data);

        ajaxReturnDie('success','Data berhasil dihapus','reload_table');
    }

    public function update($param="") 
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_tarif_pajak'));
        $data['persentase'] = $this->input->post('persentase');
        $data['nama_pajak'] = $this->input->post('nama_pajak');

        $this->md_tarif_pajak->update(['id_tarif_pajak'=> $id], $data);

        ajaxReturnDie('success','Data berhasil diupdate','reload_table');
    }

    public function pagination(){ 
        grantAccessFor('all');

        $dt    = $this->md_tarif_pajak->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach($dt['data'] as $row){
            $id       = encrypt($row->id_tarif_pajak);
            $li_btn   ='
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="'.$id.'"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="'.$id.'" data-object="tarif_pajak/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start.'.';
            $th[] = $row->nama_pajak;
            $th[] = $row->persentase."%";
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}

