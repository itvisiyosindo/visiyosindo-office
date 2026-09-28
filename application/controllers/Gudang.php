<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Gudang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_gudang');
        $this->load->model('md_penerimaan_barang');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']  	= 'v_gudang';
        $page_data['page_title'] 	= 'Gudang';
        $page_data['page_desc']  	= 'Management Data Gudang';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_gudang'] = $this->input->post('nama_gudang');
        $data['alamat_gudang'] = $this->input->post('alamat_gudang');
        $data['penanggung_jawab'] = $this->input->post('penanggung_jawab');
        $data['lokasi'] = $this->input->post('lokasi');
        checkEmptyForm($data);

        $this->md_gudang->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data gudang - ' . $data['nama_gudang'];
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_gudang->getById($id);
        foreach ($dt as $row) {
            $row->id_gudang = encrypt($row->id_gudang);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        //cek sudah pernah data terpakai
        $cek = $this->md_penerimaan_barang->getByWhere(['pb.id_gudang' => decrypt($param)]);
        if ($cek) {
            ajaxReturnDie('error', 'Data sudah pernah di gunakan!');
        }
        $id_gudang    = decrypt($param);
        $data['status'] = 0;
        $this->md_gudang->update(['id_gudang' => $id_gudang], $data);

        //add log
        $temp = $this->md_gudang->getById($id_gudang);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data gudang - ' . $temp[0]->nama_gudang;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Gudang berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');
        
        $id = decrypt($this->input->post('id_gudang'));
        $data['nama_gudang'] = $this->input->post('nama_gudang');
        $data['alamat_gudang'] = $this->input->post('alamat_gudang');
        $data['penanggung_jawab'] = $this->input->post('penanggung_jawab');
        $data['lokasi'] = $this->input->post('lokasi');
        checkEmptyForm($data);
        $this->md_gudang->update(['id_gudang' => $id], $data);

        //add log
        $temp = $this->md_gudang->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data gudang - ' . $temp[0]->nama_gudang;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_gudang->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_gudang);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="gudang/delete"><i class="bx bx-trash"></i></button>
                </div>';

            if($row->lokasi == "1"){
                $lokasi =  'Pekanbaru';
            }else if($row->lokasi == "2"){
                $lokasi = 'Jakarta';
            }else if($row->lokasi == "3"){
                $lokasi = 'Yogyakarta';
            }else{
                $lokasi = "";
            }

            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_gudang;
            $th[] = $row->penanggung_jawab;
            $th[] = $row->alamat_gudang;
            $th[] = $lokasi;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
