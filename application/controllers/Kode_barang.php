<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Kode_barang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        // $this->load->model('md_cabang');
        $this->load->model('md_barang');
        $this->load->model('md_kode_barang');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']  	= 'v_kode_barang';
        $page_data['page_title'] 	= 'Kode Barang';
        $page_data['page_desc']  	= 'Management Data Kode Barang';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['kode_barang'] = $this->input->post('kode_barang');
        $data['id_barang'] = decrypt($this->input->post('id_barang'));
        checkEmptyForm($data);

        //cek conirmasi kode_barang
        if ($data['kode_barang'] != $this->input->post('c_kode_barang')) {
            ajaxReturnDie('error', 'kode barang tidak sama!');
        }

        //cek unique kode_Barang
        $cek1 = $this->md_kode_barang->getByWhere(['kb.kode_barang' => $data['kode_barang']]);
        $cek2 = $this->md_barang->getByWhere(['b.kode_barang' => $data['kode_barang']]);
        if ($cek1 || $cek2) {
            ajaxReturnDie('error', 'Kode Barang sudah ada!');
        }
        $this->md_kode_barang->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data kode barang  - ' . $data['kode_barang'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function get($param = "")
    {
        if ($param == 'cek_barcode') {
            $barcode = $this->input->post('barcode');
            //cek unique kode_Barang
            $cek1 = $this->md_kode_barang->getByWhere(['kb.kode_barang' => $barcode]);
            $cek2 = $this->md_barang->getByWhere(['b.kode_barang' => $barcode]);
            if ($cek1 || $cek2) {
                // ajaxReturnDie('error', 'Kode Barang sudah ada!');
                echo json_encode('Barcode sudah ada!');
            } else {
                // ajaxReturnDie('success', 'Kode Barang Belum ada');
                echo json_encode('Barcode Belum ada!');
            }
            die;
        }
    }

    // public function edit($param1)
    // {
    //     grantAccessFor(['Administrator', 'Staf Admin']);

    //     $id = decrypt($param1);
    //     $dt = $this->md_cabang->getById($id);
    //     foreach ($dt as $row) {
    //         $row->id_cabang = encrypt($row->id_cabang);
    //     }
    //     echo json_encode($dt);
    //     die;
    // }

    public function delete($param)
    {
        grantAccessFor('all');

        $id_kode_barang    = decrypt($param);
        $data['status'] = 0;

        //add log
        $temp = $this->md_kode_barang->getById($id_kode_barang);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data kode barang - ' . $temp[0]->kode_barang;
        addlog($aksi, $ket);

        $this->md_kode_barang->delete(['id_kode_barang' => $id_kode_barang]);


        ajaxReturnDie('success', 'cabang berhasil dihapus', 'reload_table');
    }

    // public function update($param = "")
    // {
    //     grantAccessFor(['Administrator', 'Staf Admin']);

    //     $id = decrypt($this->input->post('id_cabang'));
    //     $data['nama_cabang'] = $this->input->post('nama_cabang');
    //     $data['alamat_cabang'] = $this->input->post('alamat_cabang');
    //     $data['penanggung_jawab'] = $this->input->post('penanggung_jawab');
    //     checkEmptyForm($data);

    //     $this->md_cabang->update(['id_cabang' => $id], $data);

    //     //add log
    //     $temp = $this->md_cabang->getById($id);
    //     $aksi = 'Edit Master Data';
    //     $ket = 'Mengedit data cabang - ' . $temp[0]->nama_cabang;
    //     addlog($aksi, $ket);

    //     ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    // }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_kode_barang->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_kode_barang);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="kode_barang/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->kode_barang;
            $th[] = $row->nama_barang;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
