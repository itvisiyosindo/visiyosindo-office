<?php

defined('BASEPATH') or exit('No direct script access allowed');

class E_suket extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_e_suket');
        $this->load->model('md_pengguna');
        $this->load->helper('whatsapp_helper');
    }
	
	function id_navbar(){
		$id_navbar = "dokumen";
		return $id_navbar;
	}

    public function index()
    {
		grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']  	= 'v_e_suket';
        $page_data['page_title'] 	= 'e-Suket';
        $page_data['page_desc']  	= 'Management Data e-Suket';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_suket']     = $this->input->post('nama_suket');
        $data['kategori']       = $this->input->post('kategori');
        $data['link_download']  = $this->input->post('link_download');
        $data['masa_berlaku_dokumen'] = $this->input->post('masa_berlaku_dokumen');
        checkEmptyForm($data);

        $this->md_e_suket->add($data);

        $url = 'https://office.visiyosindo.id/dokumen/e_suket';

        $dataWa = [
            'idPenerima1' 	=> 'MARKETING PT. VYM',
            'idPenerima2' 	=> '',
            'namaSurat'  	=> 'Dokumen Product',
            'status' 	    => 'mengupload e-Suket',
            'kategori' 	    => $data['kategori'],
            'url' 	        => $url,
            'penerima' 	    => 'Team Marketing',
            'csname' 	    => $data['nama_suket']
        ];
        $this->notifWaGroup(1, $dataWa);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data e-Suket - ' . $data['nama_suket'];
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_e_suket->getById($id);
        foreach ($dt as $row) {
            $row->id_suket = encrypt($row->id_suket);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        $id_suket = decrypt($param);
        $data['status'] = 0;
        $this->md_e_suket->update(['id_suket' => $id_suket], $data);

        //add log
        $temp = $this->md_e_suket->getById($id_suket);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data e-Suket - ' . $temp[0]->nama_suket;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'e-Suket berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_suket'));
        $data['nama_suket']     = $this->input->post('nama_suket');
        $data['kategori']       = $this->input->post('kategori');
        $data['link_download']  = $this->input->post('link_download');
        $data['masa_berlaku_dokumen'] = $this->input->post('masa_berlaku_dokumen');
        checkEmptyForm($data);
        $this->md_e_suket->update(['id_suket' => $id], $data);

        $url = 'https://office.visiyosindo.id/dokumen/e_suket';

        $dataWa = [
            'idPenerima1' 	=> 'MARKETING PT. VYM',
            'idPenerima2' 	=> '',
            'namaSurat'  	=> 'Dokumen Product',
            'status' 	    => 'mengupdate e-Suket',
            'kategori' 	    => $data['kategori'],
            'url' 	        => $url,
            'penerima' 	    => 'Team Marketing',
            'csname' 	    => $data['nama_suket']
        ];
        $this->notifWaGroup(1, $dataWa);

        //add log
        $temp = $this->md_e_suket->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data e-Suket - ' . $temp[0]->nama_suket;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
		grantAccessFor('all');

        $dt    = $this->md_e_suket->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id = encrypt($row->id_suket);
            $li_btn = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="e_suket/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $link_download = '<a href="' . $row->link_download . '" target="_blank"><i class="fas fa-download"></i> Download</a>';
            
            $masa_berlaku = ($row->masa_berlaku_dokumen != "" && $row->masa_berlaku_dokumen != null) ? $row->masa_berlaku_dokumen : '<span class="badge badge-warning">belum di isi</span>';
            
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_suket;
            $th[] = $row->kategori;
            $th[] = $link_download;
            $th[] = $masa_berlaku;
            if (isAdmin() || isStafAdmin() || sessPenggunaId()==92 || sessPenggunaId()==102) {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    private function notifWaGroup($ulang, $detail)
    {
        //ambil data pengaju
        $ambilDataPengaju = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju = $ambilDataPengaju[0]->nama;

        for ($i = 1; $i <= $ulang; $i++) {
            if ($i == 1) {
                $idpenerima = $detail['idPenerima1'];
            } else if ($i == 2) {
                $idpenerima = $detail['idPenerima2'];
            }

            //abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);

            $dataWa = [
                'namaSurat'    => urlencode($detail['namaSurat']),
                'noPenerima'   => $idpenerima,
                'namaPengaju'  => $namaPengaju,
                'csname'       => urlencode($detail['csname']),
                'kategori'     => $detail['kategori'],
                'status'       => $detail['status'],
                'url'          => $detail['url'],
                'namaPenerima' => $detail['penerima']
            ];

            waDokumenGroup($dataWa);
        }
    }
}
