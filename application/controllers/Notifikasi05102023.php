<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi extends CI_Controller {
	function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index(){
        grantAccessFor('all');
        $page_data['page_name']   = 'notifikasi';
        $page_data['page_title']  = 'Notifikasi';
        $page_data['page_desc']   = 'Record notifikasi untuk setiap pengguna sistem';
        $page_data['page_active'] = array('Notifikasi');
        $this->load->view('index', $page_data);
    }

    public function get(){
        grantAccessFor('all');
        
        $list_belum_baca = array();
        $count_belum_baca = $this->md_notifikasi->countNotifikasiBelumBaca()[0]->total;
        if($count_belum_baca){
            $tmp = $this->md_notifikasi->getTop10NotifikasiBelumBaca();
            foreach($tmp as $row){
                $dt['notifikasi_id'] = encrypt($row->notifikasi_id);
                $dt['judul'] = $row->judul;
                $dt['time'] = time_passed($row->tgl);
                $dt['link'] = $row->link;
                $list_belum_baca[] = $dt;
            }
        }
        $data['count_belum_baca'] = $count_belum_baca;
        $data['list_belum_baca'] = $list_belum_baca;
        echo json_encode($data);
        die;
    }


    public function update($param1=''){
        grantAccessFor('all');
        
        if($param1 == 'all'){
            if(isAdmin()){
                $data['baca'] = '1';
                $this->md_notifikasi->updateNotifikasiByPenggunaId(sessPenggunaId(),$data);
            }
            else if(isAnggota()){
                $data['baca'] = '1';
                $this->md_notifikasi->updateNotifikasiByAnggotaId(sessAnggotaId(),$data);
            }
            redirect('dashboard');
        }
        else {
            $id = decrypt($param1);
            $data['baca'] = '1';
            $this->md_notifikasi->updateNotifikasi($id,$data);
            $tmp = $this->md_notifikasi->getNotifikasiById($id);
            redirect($tmp[0]->link);
        }
    }

    public function pagination(){
        grantAccessFor('all');
        
        $dt    = $this->md_notifikasi->getAllNotifikasi();
        // echo '<pre>'; print_r( $dt );die; echo '</pre>';
        $start = $this->input->post('start');
        $data  = array();
        foreach($dt['data'] as $row){
            $id     = encrypt($row->notifikasi_id);
            $status = $row->baca ? '<span class="badge badge-success">Read</span>' : '<span class="badge badge-warning text-white">Unread</span>';

            $th     = array();
            $th[]   = ++$start.'.';
            $th[]   = '<a href="'.$row->link.'" onClick="updateNotif('.$id.')">'.$row->keterangan.'</a>';
            $th[]   = '<a href="'.$row->link.'" onClick="updateNotif('.$id.')"><i class="fa fa-link"></i> '.$row->judul.' </a>';
            $th[]   = $row->nama;
            $th[]   = indo_date($row->tgl);
            $th[]   = date('H:i',strtotime($row->tgl));
            $th[]   = $status;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }  
}

