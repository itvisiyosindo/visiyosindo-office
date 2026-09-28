<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi extends CI_Controller {
	function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->helper('encrypt_helper');
    }

    public function index(){
        grantAccessFor('all');
        $this->load->view('index', $page_data);
    }

    public function search()
    {
        $id = decryptvym($this->input->get('id'));
         redirect($id);
    }
    
    function load(){
        $totalnotif = totalnotifikasimasuk();
        if($totalnotif>0){
            $notif = '
                <div id="notif" class="notifications" data-closable>
                <span class="num">'. totalnotifikasimasuk() .'</span>
                <i class="icon fa fa-envelope"></i>
                <ul>
                    <li><button id="btn-notif" type="button" class="closenotifikasi btn btn-light btn-sm" style="position: absolute; right: 15px; top:0;" data-dismiss="modal"><b>Tutup</b></button></li>
            ';
                        $this->load->helper('encrypt_helper');
                        foreach (notifikasimasuk() as $row) {
                            $notif .= '
                                <li>
                                    <span class="icon"><i class="fa fa-envelope"></i></span>
                                    <span class="text">Dari &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$row->dari.'</br>Kepada : '.$row->kepada.'</br>Tanggal : '.$row->data_created.'</br><a href="'. base_url(decryptvym($row->link)).'">'.$row->keterangan.'</a></br></span>
                                </li>
                            ';
                        }
            $notif .= '    
                </ul>
            </div>
            ';
        }else{
            $notif = '
                <div class="unnotifications">
                    <i class="fa fa-envelope"></i>
                </div>
            ';
        }
       
        echo $notif;
    }
    
}

