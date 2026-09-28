<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Manual_book extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_manual_book');
        $this->load->model('md_pengguna');
        $this->load->helper('whatsapp_helper');
    }
	
	function id_navbar(){
		$id_navbar = "dokumen";
		return $id_navbar;
	}

    public function index()
    {
        //grantAccessFor(['Administrator', 'Staf Admin', 'Marketing']);
		grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']  	= 'v_manual_book';
        $page_data['page_title'] 	= 'manual_book';
        $page_data['page_desc']  	= 'Management Data Manual Book';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama']           = $this->input->post('nama_brosur');
        $data['kategori']       = $this->input->post('kategori');
        $data['link_download']  = $this->input->post('link_download');
        checkEmptyForm($data);


         $url = 'https://office.visiyosindo.id/dokumen/index/manual_book';

            $dataWa = [
                      //'idPenerima1' 	=> 'Test Api Wa Group',
                      'idPenerima2' 	=> '',
                      'idPenerima1' 	=> 'TEKNISI MEDIKAL PT. VYM',
                      'idPenerima2' 	=> '',
                      'namaSurat'  	    => 'Dokumen Product',
                      'status' 	        => 'mengupload Manual Book',
                      'kategori' 	    => $data['kategori'] ,
                      'url' 	        => $url,
                      'penerima' 	    => 'Team Engineer',
                      'csname' 	        => $data['nama']
                  ];
                  $this->notifWaGroup(1, $dataWa);

        $this->md_manual_book->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data Manual Book - ' . $data['nama'];
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_manual_book->getById($id);
        foreach ($dt as $row) {
            $row->id_manual_book = encrypt($row->id_manual_book);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        $id_manual_book    = decrypt($param);
        $data['status'] = 0;
        $this->md_manual_book->update(['id_manual_book' => $id_manual_book], $data);

        //add log
        $temp = $this->md_manual_book->getById($id_manual_book);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data Manual Book - ' . $temp[0]->nama;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Manual Book berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_brosur'));
        $data['nama'] = $this->input->post('nama_brosur');
        $data['kategori'] = $this->input->post('kategori');
        $data['link_download'] = $this->input->post('link_download');
        checkEmptyForm($data);
        $this->md_manual_book->update(['id_manual_book' => $id], $data);


        $url = 'https://office.visiyosindo.id/dokumen/index/manual_book';

            $dataWa = [
                      //'idPenerima1' 	=> 'Test Api Wa Group',
                      'idPenerima2' 	=> '',
                      'idPenerima1' 	=> 'TEKNISI MEDIKAL PT. VYM',
                      'idPenerima2' 	=> '',
                      'namaSurat'  	    => 'Dokumen Product',
                      'status' 	        => 'mengupdate Manual Book',
                      'kategori' 	    => $data['kategori'] ,
                      'url' 	        => $url,
                      'penerima' 	    => 'Team Engineer',
                      'csname' 	        => $data['nama']
                  ];
                  $this->notifWaGroup(1, $dataWa);


        //add log
        $temp = $this->md_manual_book->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data Manual Book - ' . $temp[0]->nama;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        //grantAccessFor(['Administrator', 'Staf Admin', 'Marketing']);
		grantAccessFor('all');

        $dt    = $this->md_manual_book->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_manual_book);
            $sendWa = '
                    <div class="btn-group" role="group" aria-label="First group">
                            <button type="button" class="btn btn-sm btn-success btn-send-wa" data-id="' . $id . '"><i class="fab fa-whatsapp"></i></button>
                    </div>';
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="manual_book/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $link_download = '<a href="' . $row->link_download . '"><i class="fas fa-download"></i> Download</a>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama;
            $th[] = $row->kategori;
            $th[] = $link_download;
            $th[] = $sendWa;
            $th[] = $li_btn;
            
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function sendWa121()
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_brosur'));
        $hp = $this->input->post('wa_tujuan');
        $nama = $this->session->userdata('nama');
        $level = $this->session->userdata('login_type');
        $data = $this->md_manual_book->getById($id);
        $message = '&text=Nama%20Product%20%3A%20'.$data[0]->nama.'%0A%0ALink%Manual%Book%20%3A%20'.$data[0]->link_download.'%0A%0A%0Abest%20regard%2C%0A'.$nama.',%20('.$level.')%0APT%20VISI%20YOSINDO%20MEDIKAL';
        $hp_tujuan = hp($hp);
        $link = 'https://api.whatsapp.com/send?phone='. $hp_tujuan . $message;
        redirect($link);
        die;
    }

    public function sendWa()
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_brosur'));
        $level = $this->session->userdata('login_type');
        $data = $this->md_manual_book->getById($id);
        $hp = $this->input->post('wa_tujuan');
        $hp_tujuan = preg_replace('/[^0-9]/', '', $hp);
        $nama = $this->session->userdata('nama');
        $brosur = $data[0]->nama;
        $linkBrosur = $data[0]->link_download;
        
        //send notif wa 
                $dataWa = [
                  'idPenerima1' 	=> $hp_tujuan,
                  'idPenerima2' 	=> '',
                  'namaSurat' 	    => 'Manual Book dari Office',
                  'penerima' 	    => $nama,
                  'perihal' 	    => $brosur,
                  'link_brosur' 	=> $linkBrosur
                ];
            
                $this->notifWa(1, $dataWa);
 

              /** LOG */
              addLog('Kirim Manual Book Ke Wa', 'Pengiriman Manual Book Ke Wa "' . $hp_tujuan . '"');

            redirect('manual_book');
    }


    public function notifWa($ulang, $detail){
        //ambil data pengaju
        //$ambilDataPengaju 	= $this->md_pengguna->getById(sessPenggunaId());
        //$namaPengaju		    = $ambilDataPengaju[0]->nama;

        for($i=1; $i<=$ulang; $i++){
            if($i == 1){
                $idpenerima = $detail['idPenerima1'];
            }else if($i == 2){
                $idpenerima = $detail['idPenerima2'];
            }
            
            //$dataPenerima 	= $this->md_pengguna->getById($idpenerima);
            //abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);
            //
            $nope               = $dataPenerima[0]->no_hp;
            $dataWa = [
                'namaSurat' 	=> $detail['namaSurat'],
                'noPenerima' 	=> $detail['idPenerima1'],
                'perihal' 		=> $detail['perihal'],
                'namaPenerima' 	=> $detail['penerima'],
                'link_brosur' 	=> $detail['link_brosur']
                ];
                waSendBrosurdanSurat($dataWa);
        }
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
