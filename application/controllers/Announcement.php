<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Announcement extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_announcement');
        $this->load->helper('whatsapp_helper');
    }

    function id_navbar()
    {
        $id_navbar = "kepegawaian";
        return $id_navbar;
    }

    public function index()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $page_data['switch']        = 'kepegawaian';
        $page_data['page_name']     = 'v_announcement';
        $page_data['page_title']    = 'Announcement';
        $page_data['page_desc']     = 'Pemberitahuan';
        $this->load->view('index', $page_data);
    }

    public function daftar()
    {
        grantAccessFor('all');
        $page_data['switch']        = 'home';
        $page_data['page_name']     = 'v_announcement_list';
        $page_data['page_title']    = 'Daftar Pengumuman';
        $page_data['page_desc']     = 'Daftar Pengumuman Karyawan';
        $page_data['announce']         = $this->md_announcement->getAnnouncement();
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['message'] = $this->input->post('message', TRUE);
        $data['applieddate'] = $this->input->post('applieddate', TRUE);
        $data['lampiran'] = $this->input->post('lampiran', TRUE);
        $data['pengguna_id'] = sessPenggunaId();;

        $this->md_announcement->add($data);


        //send notif Group wa
        $dataWa = [
            //'idPenerima1' 	=> 'Test Api Wa Group',
            'idPenerima1'     => 'Visi Yosindo Medical',
            'idPenerima2'     => '',
            'idPenerima3'     => '',
            'namaSurat'      => 'Announcement',
            'penerima'         => '_Karyawan PT Visi Yosindo Medikal_',
            'message'         => urlencode($data['message']),
            'applieddate'     => $data['applieddate'],
            'lampiran'         => $data['lampiran']
        ];
        $this->notifWaAddGroup(1, $dataWa);

        //add log
        //  $aksi = 'Tambah Announcement';
        //    $ket = 'Menambahkan data Announcement - ' . $data['message'];
        //  addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }


    //==================================================
    //================== Notif WA Group ================
    //==================================================
    public function notifWaAddGroup($ulang, $detail)
    {
        //ambil data pengaju
        $ambilDataPengaju     = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju            = $ambilDataPengaju[0]->nama;

        for ($i = 1; $i <= $ulang; $i++) {
            if ($i == 1) {
                $idpenerima = $detail['idPenerima1'];
            } else if ($i == 2) {
                $idpenerima = $detail['idPenerima2'];
            } else if ($i == 3) {
                $idpenerima = $detail['idPenerima3'];
            }


            //abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);
            //
            $dataWa = [
                'namaSurat'       => $detail['namaSurat'],
                'noPenerima'      => $idpenerima,
                'namaPengaju'     => $namaPengaju,
                'namaPenerima'     => $detail['penerima'],
                'message'         => $detail['message'],
                'applieddate'     => $detail['applieddate'],
                'lampiran'      => $detail['lampiran']
            ];
            waAnnouncementGroup($dataWa);
        }
    }
}
