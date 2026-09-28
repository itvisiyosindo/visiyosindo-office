<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Video_tutorial extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_video_tutorial');
        $this->load->model('md_all');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
		grantAccessFor('all');
        
        $page_data['switch']		= $this->id_navbar();
    	$page_data['page_name']  	= 'inv_lain2/v_video_tutorial';
        $page_data['page_title'] 	= 'Video Tutorial';
        $page_data['page_desc']  	= 'Management Data Video Tutorial';
        //$page_data['list_kategori'] = $this->md_video_tutorial->getAktif('kategori');
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');
        
        $data['nama']   = $this->input->post('nama');
        $data['link']   = $this->input->post('file');
        checkEmptyForm($data);
        $data['created_by']     = sessPenggunaId();
        $this->md_all->reset_increment('video_tutorial');
        $this->md_video_tutorial->add($data);
    
        //add log
        $aksi   = 'Tambah Video';
        $ket    = 'Menambahkan data video - ' . $data['nama'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');
        
        $id = decrypt($param1);
        $dt = $this->md_video_tutorial->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($id)
    {
        grantAccessFor('all');
        
        $id_delete      = decrypt($id);
        $data['status'] = 0;
        $this->md_video_tutorial->update(['id' => $id_delete], $data);
    
        //add log
        $temp   = $this->md_video_tutorial->getById($id_delete);
        $aksi   = 'Hapus Master Data';
        $ket    = 'Menghapus data video tutorial - ' . $temp[0]->nama;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'dokumen berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');
        
        $id             = decrypt($this->input->post('id_video'));
        $data['nama']   = $this->input->post('nama');
        $data['link']   = $this->input->post('file');
        $data['last_updated_by']= sessPenggunaId();
        $data['last_updated_at']= date('y-m-d H:i:s');
        checkEmptyForm($data);
        $this->md_video_tutorial->update(['id' => $id], $data);
    
        //add log
        $temp   = $this->md_video_tutorial->getById($id);
        $aksi   = 'Edit Master Data';
        $ket    = 'Mengedit data video tutorial - ' . $temp[0]->nama;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
		grantAccessFor('all');
        
        $dt             = $this->md_video_tutorial->getAll();
        $start          = $this->input->post('start');
        $data           = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_video);
            $li_btn   = '
            <center>
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="video_tutorial/delete/'.$id.'"><i class="bx bx-trash"></i></button>
                </div>
            </center>';
            $link_download = '<a href="' . $row->link . '" target="blank"><i class="fas fa-download"></i> Download</a>';
                $th = array();
                $th[] = ++$start . '.';
                $th[] = $row->nama;
                $th[] = $link_download;
                if (isAdmin() || sessPenggunaId()=='53') {
                    $th[] = $li_btn;
                }
                $data[] = $th;
            }
            $dt['data']         = $data;
            echo json_encode($dt);
            die;
    }

    public function sendWa()
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_dokumen'));
        $hp = $this->input->post('wa_tujuan');
        $nama = $this->session->userdata('nama');
        $level = $this->session->userdata('login_type');
        $data = $this->md_video_tutorial->getById($id);
        $message = '&text=Nama%20Product%20%3A%20'.$data[0]->nama_dokumen.'%0A%0ALink%20dokumen%20%3A%20'.$data[0]->link_download.'%0A%0A%0Abest%20regard%2C%0A'.$nama.',%20('.$level.')%0APT%20VISI%20YOSINDO%20MEDIKAL';
        $hp_tujuan = hp($hp);
        $link = 'https://api.whatsapp.com/send?phone='. $hp_tujuan . $message;
        redirect($link);
        die;
    }
}
