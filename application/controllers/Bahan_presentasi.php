<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Bahan_presentasi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_bhn_presentasi');
        $this->load->model('md_all');
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
		$page_data['page_name'] 	= 'v_bhn_presentasi';
        $page_data['page_title']	= 'Bahan Presentasi';
        $page_data['page_desc'] 	= 'Management Data Bahan Prensentasi';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_bhn_presentasi'] = $this->input->post('nama_file');
        $data['link_download'] = $this->input->post('link_download');
        checkEmptyForm($data);

        $this->md_bhn_presentasi->add($data);


        $url = 'https://office.visiyosindo.id/dokumen/index/bahan_presentasi';

            $dataWa = [
                      //'idPenerima1' 	=> 'Test Api Wa Group',
                      //'idPenerima2' 	=> '',
                      'idPenerima1' 	=> 'MARKETING PT. VYM',
                      'idPenerima2' 	=> '',
                      'namaSurat'  	    => 'Dokumen Product',
                      'status' 	        => 'mengupload Bahan Presentasi',
                      'kategori' 	    => 'Bahan Presentasi' ,
                      'url' 	        => $url,
                      'penerima' 	    => 'Team Marketing',
                      'csname' 	        => $data['nama_bhn_presentasi']
                  ];
                  $this->notifWaGroup(1, $dataWa);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data presentasi - ' . $data['nama_bhn_presentasi'];
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_bhn_presentasi->getById($id);
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
        $this->md_bhn_presentasi->update(['id_bhn_presentasi' => $id_bhn_presentasi], $data);

        //add log
        $temp = $this->md_bhn_presentasi->getById($id_bhn_presentasi);
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
        $this->md_bhn_presentasi->update(['id_bhn_presentasi' => $id], $data);

        $url = 'https://office.visiyosindo.id/dokumen/index/bahan_presentasi';

            $dataWa = [
                      //'idPenerima1' 	=> 'Test Api Wa Group',
                      //'idPenerima2' 	=> '',
                      'idPenerima1' 	=> 'MARKETING PT. VYM',
                      'idPenerima2' 	=> '',
                      'namaSurat'  	    => 'Dokumen Product',
                      'status' 	        => 'mengupdate Bahan Presentasi',
                      'kategori' 	    => 'Bahan Presentasi' ,
                      'url' 	        => $url,
                      'penerima' 	    => 'Team Marketing',
                      'csname' 	        => $data['nama_bhn_presentasi']
                  ];
                  $this->notifWaGroup(1, $dataWa);


        //add log
        $temp = $this->md_bhn_presentasi->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data presentasi - ' . $temp[0]->nama_bhn_presentasi;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_bhn_presentasi->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_bhn_presentasi);
            $sendWa = '
                    <div class="btn-group" role="group" aria-label="First group">
                            <button type="button" class="btn btn-sm btn-success btn-send-wa" data-id="' . $id . '"><i class="fab fa-whatsapp"></i></button>
                    </div>';
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="bahan_presentasi/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $link_download = '<a href="' . $row->link_download . '" target="blank"><i class="fas fa-download"></i> Download</a>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_bhn_presentasi;
            $th[] = $link_download;
            $th[] = $sendWa;
            if (isAdmin() || isStafAdmin() || sessPenggunaId()==92 || sessPenggunaId()==102) {
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
        $data = $this->md_bhn_presentasi->getById($id);
        $message = '&text=Nama%20Product%20%3A%20'.$data[0]->nama_bhn_presentasi.'%0A%0ALink%20Brosur%20%3A%20'.$data[0]->link_download.'%0A%0A%0Abest%20regard%2C%0A'.$nama.',%20('.$level.')%0APT%20VISI%20YOSINDO%20MEDIKAL';
        $hp_tujuan = hp($hp);
        $link = 'https://api.whatsapp.com/send?phone='. $hp_tujuan . $message;
        redirect($link);
        die;
    }

    public function notifWaGroup($ulang, $detail){
            //ambil data pengaju
            $ambilDataPengaju 	= $this->md_pengguna->getById(sessPenggunaId());
            $namaPengaju		    = $ambilDataPengaju[0]->nama;

        
            
            for($i=1; $i<=$ulang; $i++){
                if($i == 1){
                    $idpenerima = $detail['idPenerima1'];
                    //$penerima   = '_Bapak dan Ibu_';
                }else if($i == 2){
                    $idpenerima = $detail['idPenerima2'];
                    //$penerima   = '_Team Warehouse_';
                }
                
            
                //abaikan error
                error_reporting(E_ALL & ~E_NOTICE);
                ini_set('display_errors', 0);
                //
                
                    

                $dataWa = [
                    'namaSurat' 	=> urlencode($detail['namaSurat']),
                    'noPenerima' 	=> $idpenerima,
                    'namaPengaju'   => $namaPengaju,
                    'csname' 		=> urlencode($detail['csname']),
                    'kategori' 	    => $detail['kategori'],
                    'status' 	    => $detail['status'],
                    'url' 	        => $detail['url'],
                    'namaPenerima'	=> $detail['penerima']
                    ];
                
                waDokumenGroup($dataWa);
                
            }
    }


}
