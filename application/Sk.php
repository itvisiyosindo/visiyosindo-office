<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Sk extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_sk');
        $this->load->model('md_pengguna');
        $this->load->helper('whatsapp_helper');
    }
	
	function id_navbar(){
		$id_navbar = "kepegawaian";
		return $id_navbar;
	}

    public function index()
    {
        //grantAccessFor(['Administrator', 'Staf Admin', 'Marketing']);
		grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']  	= 'v_sk';
        $page_data['page_title'] 	= 'SK Penghasilan';
        $page_data['page_desc']  	= 'Management Data SK Penghasilan';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama']       = $this->input->post('nama');
        $data['tanggal']	= date_db_format($this->input->post('tanggal', TRUE));
        $data['link']       = $this->input->post('link');
        $data['status']     = 1;
        checkEmptyForm($data);

        $this->md_sk->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data SK Penghasilan - ' . $data['nama'];
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_sk->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        $id      = decrypt($param);
        $data['status'] = 0;
        $this->md_sk->update(['id' => $id], $data);

        //add log
        $temp = $this->md_sk->getById($id);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data SK Penghasilan - ' . $temp[0]->nama;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id'));
        $data['nama']       = $this->input->post('nama');
        $data['tanggal']	= date_db_format($this->input->post('tanggal', TRUE));
        $data['link']       = $this->input->post('link');
        checkEmptyForm($data);
        $this->md_sk->update(['id' => $id], $data);

        //add log
        $temp = $this->md_sk->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data SK Penghasilan - ' . $temp[0]->nama;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
		grantAccessFor('all');

        $dt    = $this->md_sk->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id);
            $sendWa = '
                    <div class="btn-group" role="group" aria-label="First group">
                            <button type="button" class="btn btn-sm btn-success btn-send-wa" data-id="' . $id . '"><i class="fab fa-whatsapp"></i></button>
                    </div>';
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="sk/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $link = '<a href="' . $row->link . '"><i class="fas fa-download"></i> Download</a>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = date('d-M-Y',strtotime($row->tanggal));
            $th[] = $row->nama;
            $th[] = $link;
            $th[] = $sendWa;
            if (isAdmin() || isStafAdmin() || sessPenggunaId()==92) {
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

        $id = decrypt($this->input->post('id'));
        $level = $this->session->userdata('login_type');
        $data = $this->md_sk->getById($id);
        $hp = $this->input->post('wa_tujuan');
        $hp_tujuan = preg_replace('/[^0-9]/', '', $hp);
        $nama = $this->session->userdata('nama');
        $brosur = $data[0]->nama;
        $linkBrosur = $data[0]->link;
        
        //send notif wa 
                $dataWa = [
                  'idPenerima1' 	=> $hp_tujuan,
                  'idPenerima2' 	=> '',
                  'namaSurat' 	    => 'SK Penghasilan',
                  'penerima' 	    => $nama,
                  'perihal' 	    => $brosur,
                  'link_brosur' 	=> $linkBrosur
                ];
            
                $this->notifWa(1, $dataWa);
 

              /** LOG */
              addLog('Kirim SK Ke Wa', 'Pengiriman SK Penghasilan Ke Wa "' . $hp_tujuan . '"');

            redirect('sk');
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


}
