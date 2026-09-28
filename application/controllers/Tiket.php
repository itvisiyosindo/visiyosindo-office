<?php

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Tiket extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pengguna');
        $this->load->model('md_tiket');
        $this->load->model('md_tiket_detail');
        $this->load->model('md_kategori_tiket');
        $this->load->model('md_pelanggan');
        $this->load->model('md_surat_list');
        $this->load->model('md_db_kepegawaian');
        $this->load->helper('email_helper');
        $this->load->helper('whatsapp_helper');
    }

    function id_navbar()
    {
        $id_navbar = "helpdesk";
        return $id_navbar;
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']          = $this->id_navbar();
        $page_data['page_name']     = 'tiket/v_tiket';
        $page_data['page_title']    = 'Data Tiket';
        $page_data['page_desc']     = 'Management Tiket Permasalahan';
        $page_data['kategori']      = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);
        $page_data['pelanggan']     = $this->md_pelanggan->getByWhere(['p.status' => 1]);
        //coba

        // $testPIC = $this->md_pengguna->countTiket();
        // if ($testPIC>1){
        $page_data['list_kode_akhir']   = $this->md_pengguna->getByWhereStatus1();
        // }else{
        //    $page_data['pengguna']	= $this->md_pengguna->getByWhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'all', 'p.hirarki' => 4]); 
        //}
        //$page_data['list_kode_akhir']   = $this->md_pengguna->getByWhereStatus1(['t.status_tiket !='=>4, 'p2.is_active' => 1, 'p2.status' => 1, 'p2.level !=' => 'all']);
        //END Coba
        if (isEksetkutif()) {
            $page_data['pengguna']    = $this->md_pengguna->getByWhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'all']);
        } else {
            $page_data['pengguna']    = $this->md_pengguna->getByWhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'all', 'p.hirarki' => 4]);
        }
        $this->load->view('index', $page_data);
    }

    public function add($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'respon') {
            $id_tiket = decrypt($this->input->post('id', TRUE));

            $data  =  [
                'respon'           => $this->input->post('deskripsi', TRUE),
                'file_pendukung'   => $this->input->post('attachment', TRUE),
                'status'           => 1,
                'id_tiket'         => $id_tiket,
                'id_user_respon'   => sessPenggunaId()
            ];
            $dataTiket = $this->md_tiket->getById($id_tiket);
            if ($data['id_user_respon'] == $dataTiket[0]->id_penerima) {

                $data1  =   [
                    'status_tiket'     => 2,
                ];
                $this->md_tiket->updateTiket($id_tiket, $data1);
            }
            $dataTiket = $this->md_tiket->getById($id_tiket);

            $this->md_tiket_detail->addRespon($data);

            /** LOG */
            addLog('Menambah Respon', 'Menambah Respon "' . $data['respon'] . '" untuk tiket ' . $dataTiket[0]->subject);
            ajaxReturnDie('success', 'Respon berhasil ditambahkan', TRUE);
        } else {
            $this->md_db_kepegawaian->reset_increment("tiket");
            $idTik  = $this->md_tiket->getTiketKodeId();

            if (is_null($idTik)) {
                $ambilId = 0;
            } else {
                $ambilId = $idTik->id_tiket;
            }
            /** $ambilId = $idTik->id_tiket; */
            $ambilId = $ambilId + 1;
            $panjangId = strlen($ambilId);

            if ($panjangId == 1) {
                $kodeTiket = "00" . $ambilId;
            } else if ($panjangId == 2) {
                $kodeTiket = "0" . $ambilId;
            } else {
                $kodeTiket = $ambilId;
            }

            if (date("m") == "01") {
                $bulanTiket = "I";
            } else if (date("m") == "02") {
                $bulanTiket = "II";
            } else if (date("m") == "03") {
                $bulanTiket = "III";
            } else if (date("m") == "04") {
                $bulanTiket = "IV";
            } else if (date("m") == "05") {
                $bulanTiket = "V";
            } else if (date("m") == "06") {
                $bulanTiket = "VI";
            } else if (date("m") == "07") {
                $bulanTiket = "VII";
            } else if (date("m") == "08") {
                $bulanTiket = "VIII";
            } else if (date("m") == "09") {
                $bulanTiket = "IX";
            } else if (date("m") == "10") {
                $bulanTiket = "X";
            } else if (date("m") == "11") {
                $bulanTiket = "XI";
            } else if (date("m") == "12") {
                $bulanTiket = "XII";
            }

            $tahunTiket = date("Y");

            $kodeTiket = $kodeTiket . "/TKN/" . $bulanTiket . "/" . $tahunTiket;

            $data['kode_tiket']            = $kodeTiket;
            $data['subject']              = $this->input->post('subject', TRUE);

            // Cek apakah pelanggan manual (non-terdaftar)
            $isPelangganManual = $this->input->post('pelanggan_manual_flag', TRUE);
            if ($isPelangganManual) {
                $data['id_pelanggan']      = NULL;
                $data['pelanggan']         = $this->input->post('pelanggan_manual', TRUE);
            } else {
                $data['id_pelanggan']      = $this->input->post('list_pelanggan', TRUE);
                $dataPelanggan             = $this->md_pelanggan->getById($data['id_pelanggan']);
                $data['pelanggan']         = $dataPelanggan[0]->identitas_pelanggan;
            }

            $data['nama_cp']              = $this->input->post('nama_contact_person', TRUE);
            $data['nomer_cp']              = $this->input->post('contact_person', TRUE);
            $data['id_pembuat']            = sessPenggunaId();
            $data['id_topik']              = $this->input->post('kategori', TRUE);
            $data['prioritas']             = $this->input->post('prioritas', TRUE);
            //$data['id_penerima']       	= 15;
            $data['deskripsi']             = $this->input->post('deskripsi', TRUE);
            $data['file_pendukung']        = $this->input->post('attachment', TRUE);
            $data['file_invoice']        = $this->input->post('invoice', TRUE);
            $data['status_tiket']          = 1;
            $data['status_Visit']          = 1;
            $data['status_surat_dinas']    = 0;
            $data['status_pembiayaan']    = 0;
            $data['waktu_mulai']           = date_db_format($this->input->post('start', TRUE));
            $data['waktu_selesai']         = date_db_format($this->input->post('end', TRUE));
            $data['log_tiket']    = 1;

            //Pilih Notifikasi Pemilih Teknisi
            //1(non Visilab) ke arifa
            $id_visilab                 = $this->input->post('id_visilab', TRUE) ?: '1';
            $data['id_visilab']            = $id_visilab;

            if ($id_visilab == 1) {
                $data['id_penerima']    = 755;
            } else if ($id_visilab == 2) { //1(Visilab) ke Rendy
                $data['id_penerima']    = 751;
            }

            $this->md_tiket->addTiket($data);

            //Membuat OPEN TICKET
            //$data1['id_tiket']			= $id_tiket;
            $data1['kode_tiket']            = $kodeTiket;
            $data1['id_pembuat']            = sessPenggunaId();
            $data1['update']                  = $this->input->post('subject', TRUE);
            $data1['status']                  = "1";

            $this->md_tiket->addUpdateTiket($data1);


            $dataTiket = [
                'idPenerima' => $data['id_penerima'],
                'kode'      => $data['kode_tiket'],
                'subject'   => $data['subject'],
                'namaPelanggan' => $data['pelanggan']
            ];
            $this->sendWaTiket(8, $dataTiket);

            addLog('Menambahkan Tiket', 'Menambah Tiket ' . $kodeTiket);
            ajaxReturnDie('success', 'Tiket berhasil ditambahkan', TRUE);

            /* $this->md_db_kepegawaian->reset_increment("tiket_detail_pic_support");
			$cekPicSupport = 0;
            if($this->input->post('pic_support', TRUE) != ""){
                $cekPicSupport = 1;
                foreach ($this->input->post('pic_support', TRUE) as $value){
    				$pic_support = [
    					'id_tiket'		=> $ambilId,
    					'id_pic_support'=> $value
    				];
    				$this->md_tiket->addPicSupport($pic_support);
			    }
            }
            
            if($cekPicSupport == 0){
                $dataTiket = [
                    'idPenerima'=> $data['id_penerima'],
                    'kode'      => $data['kode_tiket'],
                    'subject'   => $data['subject']
                ];
                $this->sendWaTiket(1, $dataTiket);
            }else if($cekPicSupport == 1){
                $dataPicSupport = $this->md_tiket->getAllPicSupport($ambilId);
                $dataPic        = "";
                
    		    foreach ($dataPicSupport as $row) {
    		        $dataPic .= "%0A- ".$row->nama_pic;
    			}

                $dataKetua = [
                    'kode'          => $data['kode_tiket'],
                    'subject'       => $data['subject'],
                    'idKetua'       => $data['id_penerima'],
                    'pic_support'   => $dataPic
                ];
                $this->sendWaTiketMultiPic('ketua', $dataKetua);
                
                foreach ($this->input->post('pic_support', TRUE) as $value){
                    $dataSupport = [
                        'kode'      => $data['kode_tiket'],
                        'subject'   => $data['subject'],
                        'idKetua'   => $data['id_penerima'],
                        'idSupport' => $value
                    ];
                    $this->sendWaTiketMultiPic('support', $dataSupport);
                }
            } 

            //LOG 
            addLog('Menambah Tiket', 'Menambah Tiket '.$kodeTiket);
            ajaxReturnDie('success', 'Tiket berhasil ditambahkan', TRUE); */
        }
    }

    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor('all');
        if ($param == 'detail_tiket') {
            $page_data['switch']              = $this->id_navbar();
            $page_data['data_tiket']          = $this->md_tiket->getByWhere(['t.id_tiket' => decrypt($param2)]);
            $page_data['data_update']          = $this->md_tiket->getUpdateById(['t.id_tiket' => decrypt($param2)]);
            $page_data['data_pic_support']    = $this->md_tiket->getAllPicSupport(decrypt($param2));
            $page_data['page_name']           = 'tiket/v_edit_tiket';
            $page_data['page_title']          = 'Tiket';
            $page_data['page_desc']           = 'Detail Tiket';
            $page_data['kategori']            = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);
            $page_data['pelanggan']           = $this->md_pelanggan->getByWhere(['p.status' => 1]);
            if ($page_data['data_tiket'][0]->id_penerima == sessPenggunaId()) {
                $page_data['pengguna']      = $this->md_pengguna->getBywhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'all', 'p.pengguna_id' => sessPenggunaId()], ['p.hirarki' => 4]);
            } else {
                $page_data['pengguna']      = $this->md_pengguna->getByWhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'all']);
            }
            $this->load->view('index', $page_data);
        } else if ($param == 'konfirmasi_ga') {
            $page_data['switch']          = $this->id_navbar();
            $page_data['data_tiket']      = $this->md_tiket->getByWhere(['t.id_tiket' => decrypt($param2)]);
            $page_data['page_name']       = 'tiket/v_konfirmasi_ga';
            $page_data['page_title']      = 'Data Tiket';
            $page_data['page_desc']       = 'Management Tiket Permasalahan';
            $page_data['kategori']        = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);
            $page_data['list_kode']       = $this->md_tiket->getByWhere(['t.status_visit' => 2, 't.status_tiket !=' => 4]);
            $page_data['list_kode_akhir'] = $this->md_tiket->getByWhere(['t.status_tiket !=' => 4]);
            $this->load->view('index', $page_data);
        } else if ($param == 'konfirmasi_cro') {
            $page_data['switch']          = $this->id_navbar();
            $page_data['data_tiket']      = $this->md_tiket->getByWhere(['t.id_tiket' => decrypt($param2)]);
            $page_data['page_name']       = 'tiket/v_konfirmasi_cro';
            $page_data['page_title']      = 'Data Tiket';
            $page_data['page_desc']       = 'Management Tiket Permasalahan';
            $page_data['kategori']        = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);
            $page_data['list_kode']       = $this->md_tiket->getByWhere(['t.status_visit' => 2, 't.status_tiket !=' => 4]);
            $page_data['list_kode_akhir'] = $this->md_tiket->getByWhere(['t.status_tiket !=' => 4]);
            $this->load->view('index', $page_data);
        } else if ($param == 'konfirmasi_finance') {
            $page_data['switch']              = $this->id_navbar();
            $page_data['data_tiket']      = $this->md_tiket->getByWhere(['t.id_tiket' => decrypt($param2)]);
            $page_data['page_name']       = 'tiket/v_konfirmasi_finance';
            $page_data['page_title']      = 'Data Tiket';
            $page_data['page_desc']       = 'Management Tiket Permasalahan';
            $page_data['kategori']        = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);
            $page_data['list_kode']       = $this->md_tiket->getByWhere(['t.status_pembiayaan' => 1, 't.status_tiket !=' => 4, 't.status_surat_dinas' => 2]);
            $page_data['list_kode_akhir'] = $this->md_tiket->getByWhere(['t.status_pembiayaan' => 2, 't.status_tiket !=' => 4]);
            $this->load->view('index', $page_data);
        } else if ($param == 'dashboard') {
            $page_data['switch']              = $this->id_navbar();
            $page_data['page_name']         = 'tiket/v_dashboard_tiket';
            $page_data['page_title']        = 'Dashboard Tiket';
            $page_data['total_ticket']         = $this->md_tiket->countTotalTicket();
            $page_data['ticket_open']         = $this->md_tiket->countTicketOpen();
            $page_data['ticket_closed']     = $this->md_tiket->countTicketClosed();
            $page_data['ticket_submit']     = $this->md_tiket->countTicketSubmit();
            $page_data['percent_ticket']     = $this->md_tiket->PercentageTicket();
            $page_data['average_response_time']  = $this->md_tiket->getAverageResponseTime();
            $page_data['tickets_by_technician']  = $this->md_tiket->get_ticket_count_by_technician();
            $page_data['ticket_by_category']     = $this->md_tiket->get_ticket_by_category();
            $page_data['detail_ticket']     = $this->md_tiket->get_detail_ticket();
            $page_data['kategori']          = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);
            $page_data['daftar_open']         = $this->md_tiket->daftar_tiketOpen();
            $page_data['daftar_submit']     = $this->md_tiket->daftar_tiketSubmit();
            $page_data['avg_respon_time']   = $this->md_tiket->getAvgResponTime();

            $this->load->view('index', $page_data);
        } else {
            $page_data['switch']              = $this->id_navbar();
            $page_data['data_tiket']        = $this->md_tiket->getByWhere(['t.id_tiket' => decrypt($param2)]);
            $page_data['page_name']         = 'tiket/v_my_tiket';
            $page_data['page_title']        = 'Data Tiket';
            $page_data['page_desc']         = 'Management Tiket Permasalahan';
            $page_data['kategori']          = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);
            $page_data['list_kode']         = $this->md_tiket->getByWhere(['t.id_penerima' => sessPenggunaId(), 't.status_visit !=' => 2, 't.status_tiket !=' => 4]);
            $page_data['list_kode_biaya']   = $this->md_tiket->getByWhere(['t.id_penerima' => sessPenggunaId(), 't.status_visit' => 2, 't.status_tiket !=' => 4, 't.status_surat_dinas' => 2, 'p.kota !=' => 'Pekanbaru']);
            $page_data['list_kode_akhir']   = $this->md_tiket->getByWhere(['t.id_penerima' => sessPenggunaId(), 't.status_tiket !=' => 4, 't.status_data' => 1]);
            $page_data['list_pb_awal']      = $this->md_surat_list->getPbTiket(sessPenggunaId(), "pengajuan");
            $page_data['list_pb_akhir']        = $this->md_surat_list->getPbTiket(sessPenggunaId(), "laporan");
            if (isEksetkutif() || sessPenggunaId() == '755' || sessPenggunaId() == '754') {
                $page_data['pengguna']    = $this->md_pengguna->getByWhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'all']);
            } else {
                $page_data['pengguna']    = $this->md_pengguna->getByWhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'all', 'p.hirarki' => 4]);
            }
            $this->load->view('index', $page_data);
        }
    }

    public function edit($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_tiket->getById($id);
        foreach ($dt as $row) {
            $row->id_tiket = encrypt($row->id_tiket);
        }
        echo json_encode($dt);
        die;
    }

    public function update($param = "", $param2 = "")
    {
        if ($param == 'status_tiket') {
            $id_tiket = decrypt($this->input->post('id_tiket', TRUE));
            $data['status_tiket'] = $this->input->post('value');

            $this->md_tiket->updateTiket($id_tiket, $data);
            if ($data['status_tiket'] == 1) {
                $status_tiket = "New";
            } else if ($data['status_tiket'] == 2) {
                $status_tiket = "Answered";
            } else if ($data['status_tiket'] == 3) {
                $status_tiket = "Review";
            } else if ($data['status_tiket'] == 4) {
                $status_tiket = "Revision";
            } else {
                $status_tiket = "Closed";
            }

            //Membuat CLOSE TICKET
            $ambilKdtiket  = $this->md_tiket->getById($id_tiket);
            $up_log['id_tiket']        = $this->input->post('id_tiket');
            $up_log['kode_tiket']    = $ambilKdtiket[0]->kode_tiket;
            $up_log['id_pembuat']    = sessPenggunaId();
            $up_log['update']        = $status_tiket;
            $up_log['status']    = "3";
            $this->md_tiket->addUpdateTiket($up_log);


            $datalog2 = $this->md_tiket->getById($id_tiket);
            addLog('Memperbaharui Tiket', 'Mengubah status tiket "' . $datalog2[0]->subject . '" menjadi "' . $status_tiket . '"');
            ajaxReturnDie('success', 'Status Tiket Berhasil Diubah', TRUE);
        } else if ($param == 'prioritas') {
            $id_tiket = decrypt($this->input->post('id_tiket', TRUE));
            $data['prioritas'] = $this->input->post('value');

            $this->md_tiket->updateTiket($id_tiket, $data);
            if ($data['prioritas'] == 1) {
                $prioritas = "Low";
            } else if ($data['prioritas'] == 2) {
                $prioritas = "Medium";
            } else if ($data['prioritas'] == 3) {
                $prioritas = "High";
            } else {
                $prioritas = "Urgent";
            }

            $datalog2 = $this->md_tiket->getById($id_tiket);
            addLog('Memperbaharui Tiket', 'Mengubah prioritas tiket' . $datalog2[0]->subject . ' menjadi ' . $prioritas);
            ajaxReturnDie('success', 'Prioritas Tiket Berhasil Diubah', TRUE);
        } else if ($param == 'edit_on_detail') {
            grantAccessFor('all');
            $id_tiket                   = decrypt($this->input->post('id_tiket'));
            $data['subject']            = $this->input->post('subject', TRUE);

            // Cek apakah pelanggan manual (non-terdaftar)
            $isPelangganManual = $this->input->post('pelanggan_manual_flag', TRUE);
            if ($isPelangganManual) {
                $data['id_pelanggan']      = NULL;
                $data['pelanggan']         = $this->input->post('pelanggan', TRUE);
            } else {
                $data['id_pelanggan']      = $this->input->post('list_pelanggan', TRUE);
                $dataPelanggan             = $this->md_pelanggan->getById($data['id_pelanggan']);
                $data['pelanggan']         = $dataPelanggan[0]->identitas_pelanggan;
            }

            $data['id_topik']           = $this->input->post('kategori', TRUE);
            $data['prioritas']          = $this->input->post('prioritas', TRUE);
            $data['status_tiket']       = $this->input->post('status_tiket', TRUE);
            $data['id_penerima']        = decrypt($this->input->post('agent', TRUE));
            $data['deskripsi']          = $this->input->post('deskripsi', TRUE);
            $data['file_pendukung']     = $this->input->post('file_pendukung', TRUE);
            $data['file_invoice']       = $this->input->post('invoice', TRUE);
            $data['waktu_mulai']        = date_db_format($this->input->post('start', TRUE));
            $data['waktu_selesai']      = date_db_format($this->input->post('end', TRUE));

            $dataTiketLama  = $this->md_tiket->getById($id_tiket);
            $idPenerimaLama = $dataTiketLama[0]->id_penerima;
            $idPenerimaBaru = $data['id_penerima'];
            $kodeTiket      = $dataTiketLama[0]->kode_tiket;

            $this->md_tiket->updateTiket($id_tiket, $data);

            if ($data['status_tiket'] == 1) {
                $status_tiket = "New";
            } else if ($data['status_tiket'] == 2) {
                $status_tiket = "On Progress";
            } else if ($data['status_tiket'] == 3) {
                $status_tiket = "Revision";
            } else if ($data['status_tiket'] == 4) {
                $status_tiket = "Selesai";
            } else {
                $status_tiket = "Closed";
            }
            //Membuat CLOSE TICKET
            $ambilKdtiket  = $this->md_tiket->getById($id_tiket);
            $up_log['id_tiket']        = $this->input->post('id_tiket');
            $up_log['kode_tiket']    = $ambilKdtiket[0]->kode_tiket;
            $up_log['id_pembuat']    = sessPenggunaId();
            $up_log['update']        = $status_tiket;
            $up_log['status']    = "3";
            $this->md_tiket->addUpdateTiket($up_log);


            if ($idPenerimaLama != $idPenerimaBaru) {
                $dataTiket = [
                    'idPenerima1'   => $idPenerimaLama,
                    'idPenerima2'   => $idPenerimaBaru,
                    'kode'          => $kodeTiket,
                    'subject'       => $data['subject']
                ];
                $this->sendWaTiketGantiPIC($dataTiket);
            }

            addLog('Memperbaharui Tiket', 'Memperbaharui data Tiket ' . $data['subject']);
            ajaxReturnDie('success', 'Tiket berhasil diperbaharui', TRUE);
        } else if ($param == 'logTiket') {
            grantAccessFor('all');


            $id_tik     = $this->input->post('id_tiket');
            $dataTiket  = $this->md_tiket->getById($id_tik);


            //$up_visit['status_tiket']		= 2;
            $up_visit['log_tiket']            = 1;
            $this->md_tiket->updateTiket($id_tik, $up_visit);

            //Membuat UPDATE TICKET
            $up_log['id_tiket']        = $this->input->post('id_tiket');
            $up_log['kode_tiket']    = $dataTiket[0]->kode_tiket;
            $up_log['id_pembuat']    = sessPenggunaId();
            $up_log['update']        = $this->input->post('deskripsi', TRUE);
            //$up_log['file_update']	= $this->input->post('attachment', TRUE);
            $up_log['file_update'] = !empty($this->input->post('attachment', TRUE)) ? $this->input->post('attachment', TRUE) : '-';

            $up_log['status']    = "2";
            $this->md_tiket->addUpdateTiket($up_log);

            $datalog2 = $this->md_tiket->getById($id_tik);
            $dataTiket = [
                'idPenerima' => $datalog2[0]->id_penerima,
                'kode'      => $datalog2[0]->kode_tiket,
                'subject'   => $datalog2[0]->subject
            ];
            $this->sendWaTiket(7, $dataTiket);

            $dataGroup = [
                'idPenerima' => $datalog2[0]->id_penerima,
                'kode'      => $datalog2[0]->kode_tiket,
                'nmGroup'   => 'TEKNISI MEDIKAL PT. VYM',
                //'nmGroup'   => 'Test Api Wa Group',
                'ketPeker'  => $up_log['update'],
                'linkBukti' => $up_log['file_update'],
                'subject'   => $datalog2[0]->subject
            ];
            $this->sendWaTiket(10, $dataGroup);

            addLog('Memperbaharui Tiket', 'Melakukan Update Ticket ' . $datalog2[0]->kode_tiket);
            ajaxReturnDie('success', 'Update Ticket Berhasil Diajukan', TRUE);
        } else if ($param == 'disposTeknisi') {
            grantAccessFor('all');
            $id_tiket    = $this->input->post('id_tiket_7');
            $penerima = $this->input->post('id_penerima', TRUE);
            $subject = $this->input->post('subject');

            $up_visit['id_penerima']            = $penerima;
            $this->md_tiket->updateTiket($id_tiket, $up_visit);



            //Membuat UPDATE TICKET (DISPATCH)
            $ambilKodetiket  = $this->md_tiket->getById($id_tiket);
            $kodeTiket = $ambilKodetiket[0]->kode_tiket;
            $IDTiket = $ambilKodetiket[0]->id_tiket;
            $namaPelanggan = $ambilKodetiket[0]->pelanggan;

            //ambil nama & nomor
            $dataPenerima     = $this->md_pengguna->getById($penerima);
            $nope           = $dataPenerima[0]->no_hp;
            $nama           = $dataPenerima[0]->nama;
            $update = "Dispatch Teknisi " . $nama . " untuk mengerjakan Tiket : " . $kodeTiket;


            $up_log['id_tiket']        = $this->input->post('id_tiket_7');
            $up_log['kode_tiket']    = $kodeTiket;
            $up_log['id_pembuat']    = sessPenggunaId();
            $up_log['update']        = $update;
            $up_log['status']    = "7";
            $this->md_tiket->addUpdateTiket($up_log);


            $this->md_db_kepegawaian->reset_increment("tiket_detail_pic_support");
            $cekPicSupport = 0;
            if ($this->input->post('pic_support', TRUE) != "") {
                $cekPicSupport = 1;
                foreach ($this->input->post('pic_support', TRUE) as $value) {
                    $pic_support = [
                        'id_tiket'        => $IDTiket,
                        'id_pic_support' => $value
                    ];
                    $this->md_tiket->addPicSupport($pic_support);
                }
            }

            if ($cekPicSupport == 0) {
                $dataTiket = [
                    'idPenerima' => $penerima,
                    'kode'      => $kodeTiket,
                    'subject'   => $subject,
                    'namaPelanggan' => $namaPelanggan
                ];
                $this->sendWaTiket(1, $dataTiket);


                $dataGroup = [
                    'idPenerima' => $penerima,
                    'kode'      => $kodeTiket,
                    'nmGroup'   => 'TEKNISI MEDIKAL PT. VYM',
                    'subject'   => $subject,
                    'namaPelanggan' => $namaPelanggan,
                    'nama_cp'   => $ambilKodetiket[0]->nama_cp,
                    'nomer_cp'  => $ambilKodetiket[0]->nomer_cp,
                    'deskripsi' => $ambilKodetiket[0]->deskripsi
                ];
                $this->sendWaTiket(9, $dataGroup);
            } else if ($cekPicSupport == 1) {
                $dataPicSupport = $this->md_tiket->getAllPicSupport($id_tiket);
                $dataPic        = "";

                foreach ($dataPicSupport as $row) {
                    $dataPic .= "%0A- " . $row->nama_pic;
                }

                $dataKetua = [
                    'kode'          => $kodeTiket,
                    'subject'       => $subject,
                    'idKetua'       => $penerima,
                    'pic_support'   => $dataPic
                ];
                $this->sendWaTiketMultiPic('ketua', $dataKetua);

                $dataGroup = [
                    'idPenerima' => $penerima,
                    'kode'      => $kodeTiket,
                    'nmGroup'   => 'TEKNISI MEDIKAL PT. VYM',
                    'subject'   => $subject,
                    'namaPelanggan' => $namaPelanggan,
                    'nama_cp'   => $ambilKodetiket[0]->nama_cp,
                    'nomer_cp'  => $ambilKodetiket[0]->nomer_cp,
                    'deskripsi' => $ambilKodetiket[0]->deskripsi
                ];
                $this->sendWaTiket(9, $dataGroup);

                foreach ($this->input->post('pic_support', TRUE) as $value) {
                    $dataSupport = [
                        'kode'      => $kodeTiket,
                        'subject'   => $subject,
                        'idKetua'   => $penerima,
                        'idSupport' => $value
                    ];
                    $this->sendWaTiketMultiPic('support', $dataSupport);
                }
            }
            /** LOG */
            addLog('Menambah Tiket', 'Menambah Tiket ' . $kodeTiket);
            ajaxReturnDie('success', 'Tiket berhasil ditambahkan', TRUE);
        } else if ($param == 'ajukanVisit') {
            grantAccessFor('all');
            $id_tiket    = $this->input->post('id_tiket_0');

            $up_visit['status_visit']            = 2;
            $up_visit['status_surat_dinas']        = 1;
            $up_visit['status_tiket']            = 2;
            $up_visit['catatan_visit']             = $this->input->post('deskripsi_visit', TRUE);
            $up_visit['file_pendukung_visit']    = $this->input->post('attachment_visit', TRUE);
            $up_visit['awal_visit']               = date_db_format($this->input->post('start', TRUE));
            $up_visit['akhir_visit']             = date_db_format($this->input->post('end', TRUE));

            $this->md_tiket->updateTiket($id_tiket, $up_visit);

            //Membuat UPDATE TICKET (Pengajuan VISIT)
            $ambilKodetiket  = $this->md_tiket->getById($id_tiket);
            $up_log['id_tiket']        = $this->input->post('id_tiket_0');
            $up_log['kode_tiket']    = $ambilKodetiket[0]->kode_tiket;
            $up_log['id_pembuat']    = sessPenggunaId();
            $up_log['update']        = $this->input->post('deskripsi_visit', TRUE);
            $up_log['file_update']    = $this->input->post('attachment_visit', TRUE);
            $up_log['status']    = "4";
            $this->md_tiket->addUpdateTiket($up_log);


            $datalog2 = $this->md_tiket->getById($id_tiket);

            $dataTiket = [
                'idPenerima' => $datalog2[0]->id_penerima,
                'kode'      => $datalog2[0]->kode_tiket,
                'subject'   => $datalog2[0]->subject
            ];
            $this->sendWaTiket(2, $dataTiket);

            addLog('Memperbaharui Tiket', 'Mengajukan Visit untuk Tiket ' . $datalog2[0]->kode_tiket);
            ajaxReturnDie('success', 'Permintaan Visit Berhasil Diajukan', TRUE);
        } else if ($param == 'ajukanBiaya') {
            grantAccessFor('all');
            $id_tiket    = $this->input->post('id_tiket_2');
            $id_pb        = $this->input->post('pb', TRUE);
            checkEmptyForm($id_pb);
            $up_visit['status_pembiayaan']        = 1;
            $up_visit['catatan_pembiayaan']     = $this->input->post('catatan', TRUE);
            $up_visit['file_pengajuan_biaya']    = $id_pb;
            $this->md_tiket->updateTiket($id_tiket, $up_visit);

            //Membuat UPDATE TICKET (Pengajuan Biaya)
            $ambilKdtiket  = $this->md_tiket->getById($id_tiket);
            $up_log['id_tiket']        = $this->input->post('id_tiket_2');
            $up_log['kode_tiket']    = $ambilKdtiket[0]->kode_tiket;
            $up_log['id_pembuat']    = sessPenggunaId();
            $up_log['update']        = $this->input->post('catatan', TRUE);
            $up_log['file_update']    = $id_pb;
            $up_log['status']    = "5";
            $this->md_tiket->addUpdateTiket($up_log);

            $datalog2 = $this->md_tiket->getById($id_tiket);
            $dataTiket = [
                'idPenerima' => $datalog2[0]->id_penerima,
                'kode'      => $datalog2[0]->kode_tiket,
                'subject'   => $datalog2[0]->subject
            ];
            $this->sendWaTiket(4, $dataTiket);

            addLog('Memperbaharui Tiket', 'Mengajukan Surat Biaya untuk Tiket ' . $datalog2[0]->kode_tiket);
            ajaxReturnDie('success', 'Surat Biaya Berhasil Diajukan', TRUE);
        } else if ($param == 'kirimLaporan') {
            grantAccessFor('all');
            $id_tiket    = $this->input->post('id_tiket_3');
            $id_pb        = $this->input->post('laporan_pb', TRUE);
            $gabolehKosong = [
                'des_peker' => $this->input->post('des_peker', TRUE),
                'bukti_kerja' => $this->input->post('bukti_kerja', TRUE),
            ];
            checkEmptyFormTiket($gabolehKosong);


            $up_visit['status_tiket']                = 2;
            $up_visit['file_laporan_teknisi']       = $this->input->post('bukti_kerja', TRUE);
            $up_visit['des_peker']                  = $this->input->post('des_peker', TRUE);
            //$up_visit['file_laporan_biaya']		    = $this->input->post('laporan_biaya', TRUE);
            $up_visit['file_laporan_biaya']            = $id_pb;
            $up_visit['file_feedback_cust']           = $this->input->post('feedback_cust', TRUE);
            $up_visit['deskripsi_feedback_cust']    = $this->input->post('feedback_desk', TRUE);



            $this->md_tiket->updateTiket($id_tiket, $up_visit);

            //Membuat UPDATE TICKET (Laporan Akhir)
            $ambilKdtiket  = $this->md_tiket->getById($id_tiket);
            $up_log['id_tiket']        = $this->input->post('id_tiket_3');
            $up_log['kode_tiket']    = $ambilKdtiket[0]->kode_tiket;
            $up_log['id_pembuat']    = sessPenggunaId();
            $up_log['update']        = $this->input->post('des_peker', TRUE);
            $up_log['file_update']    = $this->input->post('bukti_kerja', TRUE);
            $up_log['status']    = "6";
            $this->md_tiket->addUpdateTiket($up_log);

            $datalog2 = $this->md_tiket->getById($id_tiket);
            $dataTiket = [
                'idPenerima' => $datalog2[0]->id_penerima,
                'kode'      => $datalog2[0]->kode_tiket,
                'subject'   => $datalog2[0]->subject,
                'cek'       => $up_visit['file_laporan_biaya']
            ];
            // NOTIF WA LAPORAN AKHIR TIKET TEKNISI (REQ BANG RICKI)
            $this->sendWaTiket(6, $dataTiket);

            $dataGroup = [
                'idPenerima' => $datalog2[0]->id_penerima,
                'kode'      => $datalog2[0]->kode_tiket,
                'nmGroup'   => 'TEKNISI MEDIKAL PT. VYM',
                //'nmGroup'   => 'Test Api Wa Group',
                'ketPeker'  => $up_log['update'],
                'linkBukti' => $up_log['file_update'],
                'subject'   => $datalog2[0]->subject
            ];
            // NOTIF WA LAPORAN AKHIR TIKET TEKNISI (REQ BANG RICKI)
            $this->sendWaTiket(11, $dataGroup);

            addLog('Memperbaharui Tiket', 'Mengirim Laporan Akhir untuk Tiket ' . $datalog2[0]->kode_tiket);
            ajaxReturnDie('success', 'Laporan Akhir Berhasil Dikirim', TRUE);
        } else if ($param == 'ga_submitSudin') {
            grantAccessFor('all');
            $id_tiket    = $this->input->post('id_tiket');

            $up_visit['file_surat_dinas']       = $this->input->post('file_surat_dinas', TRUE);
            $up_visit['catatan_surat_dinas']    = $this->input->post('catatan', TRUE);
            $up_visit['status_surat_dinas']        = 2;
            $this->md_tiket->updateTiket($id_tiket, $up_visit);

            $datalog2 = $this->md_tiket->getById($id_tiket);
            $dataTiket = [
                'idPenerima' => $datalog2[0]->id_penerima,
                'kode'      => $datalog2[0]->kode_tiket,
                'subject'   => $datalog2[0]->subject
            ];
            $this->sendWaTiket(3, $dataTiket);

            addLog('Memperbaharui Tiket', 'Menerbitkan Surat Dinas untuk Tiket ' . $datalog2[0]->kode_tiket);
            ajaxReturnDie('success', 'Surat Dinas Berhasil Diterbitkan', TRUE);
        } else if ($param == 'ga_closeTiket') {
            grantAccessFor('all');
            $id_tiket    = $this->input->post('id_tiket_2');

            $up_visit['catatan_close_tiket']        = $this->input->post('catatan', TRUE);
            $up_visit['status_tiket']                = 4;

            /*
			if ($this->input->post('value') == '1'){
				$statusClose = "Close (Tepat Waktu)";
			}else if ($this->input->post('value') == '2'){
				$statusClose = "Close (Terlambat)";
			}else if ($this->input->post('value') == '3'){
				$statusClose = "Close (Bersyarat)";
			}
*/

            $this->md_tiket->updateTiket($id_tiket, $up_visit);

            //Membuat CLOSE TICKET
            $ambilKdtiket  = $this->md_tiket->getById($id_tiket);
            $up_log['id_tiket']        = $this->input->post('id_tiket_2');
            $up_log['kode_tiket']    = $ambilKdtiket[0]->kode_tiket;
            $up_log['id_pembuat']    = sessPenggunaId();
            $up_log['update']        = $this->input->post('catatan', TRUE);
            //$up_log['file_update']	= $this->input->post('bukti_kerja', TRUE);
            $up_log['status']    = "3";
            $this->md_tiket->addUpdateTiket($up_log);

            $datalog2 = $this->md_tiket->getById($id_tiket);
            addLog('Memperbaharui Tiket', 'Mengubah Status Tiket ' . $datalog2[0]->kode_tiket . ' Menjadi Selesai');
            ajaxReturnDie('success', 'Tiket Berhasil Diselesaikan', TRUE);
        } else if ($param == 'finance_pembiayaan') {
            grantAccessFor('all');
            $id_tiket    = $this->input->post('id_tiket');
            $id_pb        = $this->input->post('id_pb', TRUE);

            //$up_visit['file_pengajuan_biaya']   = $this->input->post('file_persetujuan_biaya', TRUE);
            //$up_visit['file_transfer_biaya']	= $this->input->post('bukti_tf', TRUE);
            $up_visit['status_pembiayaan']        = 2;
            $this->md_tiket->updateTiket($id_tiket, $up_visit);

            $up_pb['status_tiket']        = 1;
            $this->md_surat_list->update_pb($id_pb, $up_pb);

            $datalog2 = $this->md_tiket->getById($id_tiket);
            $dataTiket = [
                'idPenerima' => $datalog2[0]->id_penerima,
                'kode'      => $datalog2[0]->kode_tiket,
                'subject'   => $datalog2[0]->subject
            ];
            $this->sendWaTiket(5, $dataTiket);

            addLog('Memperbaharui Tiket', 'Menyetujui dan Melakukan Transfer Biaya untuk Tiket ' . $datalog2[0]->kode_tiket);
            ajaxReturnDie('success', 'Berhasil Mengunggah Persetujuan', TRUE);
        } else if ($param == 'finance_laporan') {
            grantAccessFor('all');
            $id_tiket    = $this->input->post('id_tiket_2');
            $id_pb        = $this->input->post('id_pb_2');

            //$up_visit['file_laporan_biaya']   = $this->input->post('file_close_biaya', TRUE);
            $up_visit['catatan_close_biaya']    = $this->input->post('catatan', TRUE);
            $up_visit['status_pembiayaan']      = 4;
            $this->md_tiket->updateTiket($id_tiket, $up_visit);

            $up_pb['status_tiket']        = 2;
            $this->md_surat_list->update_pb($id_pb, $up_pb);

            $datalog2 = $this->md_tiket->getById($id_tiket);

            addLog('Memperbaharui Tiket', 'Menyetujui Laporan Biaya untuk Tiket ' . $datalog2[0]->kode_tiket);
            ajaxReturnDie('success', 'Berhasil Mengunggah Persetujuan', TRUE);
        }
    }


    public function delete($param = "", $param1)
    {
        if ($param == 'respon') {
            grantAccessFor('all');

            $responId    = decrypt($param1);
            $temp           = $this->md_tiket_detail->getById($responId);
            $data['status'] = 2;
            $this->md_tiket_detail->updateRespon($responId, $data);
            addLog('Menghapus Respon Tiket', 'Menghapus respon ' . $temp[0]->respon);
            ajaxReturnDie('success', 'Respon berhasil dihapus', TRUE);
        } else if ($param == 'tiket') {
            grantAccessFor('all');

            $id_tiket    = decrypt($param1);
            $temp           = $this->md_tiket->getById($id_tiket);
            $data['status_data'] = 2;
            $datarespon['status'] = 2;
            $respon_tiket = $this->md_tiket_detail->getByWhere(['td.id_tiket' => $id_tiket, 'td.status' => 1]);

            if (!empty($respon_tiket)) {
                foreach ($respon_tiket as $respon) {
                    $this->md_tiket_detail->updateRespon($respon->id_detail_tiket, $datarespon);
                }
            }
            $this->md_tiket->updateTiket($id_tiket, $data);
            addLog('Menghapus Tiket', 'Menghapus Tiket ' . $temp[0]->subject);
            ajaxReturnDie('success', 'Tiket berhasil dihapus', 'reload_table');
        }
    }

    public function pagination($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'respon') {

            $id_tiket = $this->input->post('id_tiket');
            $respon = $this->md_tiket_detail->getByWhere(['td.id_tiket' => decrypt($id_tiket), 'td.status' => 1]);
            if ($respon) {
                foreach ($respon as $row) {
                    $id_detail_tiket = encrypt($row->id_detail_tiket);
                    $delete = '<a href="javascript:void(0)" class="text-color-danger btn-delete" data-id="' . $id_detail_tiket . '" data-object="tiket/delete/respon" >Delete note</a>';
                    $file = "";

                    if ($row->file_pendukung != NULL) {
                        $file = '<a href="' . $row->file_pendukung . '" class="text-color-info" target="blank">File Pendukung</a></small>';
                    }

                    echo '<div class="ecommerce-timeline-item">';
                    echo '<input type="hidden" name="id_detail_tiket" id="id_detail_tiket" value="' . $id_detail_tiket . '">';

                    if (isPic() && $row->id_user_respon != sessPenggunaId()) {
                        echo '<small>added on ' . indo_date($row->data_created) . ' by ' . $row->nama . '';
                    } else {
                        echo '<small>added on ' . indo_date($row->data_created) . ' by ' . $row->nama . ' - ' . $delete . ' </small>';
                    }

                    echo '<p>' . $row->respon . '</p>';
                    echo $file;
                    echo '</div>';
                }
            } else {

                echo   '<p>Tidak ada respon.</p>';
            }
        } else if ($param == 'my_tiket') {

            $id_pengguna = sessPenggunaId();

            $dt    = $this->md_tiket->getTiketPenerima($id_pengguna);
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                if ($row->prioritas == 1) {
                    $prioritas = '<span class="badge badge-ecommerce badge-success">Low</span>';
                } elseif ($row->prioritas == 2) {
                    $prioritas = '<span class="badge badge-ecommerce badge-primary">Medium</span>';
                } elseif ($row->prioritas == 3) {
                    $prioritas =  '<span class="badge badge-ecommerce badge-warning">High</span>';
                } else {
                    $prioritas = '<span class="badge badge-ecommerce badge-danger">Urgent</span>';
                }

                if ($row->status_visit == 1) {
                    $status_visit = '<span class="badge badge-ecommerce badge-warning">No Visit</span>';
                } elseif ($row->status_visit == 2) {
                    $status_visit = '<span class="badge badge-ecommerce badge-success">Visit</span>';
                }
                if ($row->status_visit == 1) {
                    $status_visit = '<span class="badge badge-ecommerce badge-warning">No Visit</span>';
                    $pd = '<strong>--</strong>';
                } elseif ($row->status_visit == 2) {
                    $status_visit = '<span class="badge badge-ecommerce badge-success">Visit</span>';
                    $pd = '<a class="badge badge-ecommerce badge-success" href="surat/show/pengajuan/pd_teknisi")>Ajukan</a>';
                }


                $from = date_create($row->created_at);
                $too = date_create();
                $diff  = date_diff($from, $too); //untuk menghitung hari
                $hitungWaktu = $diff->d;
                // echo $diff->d . ' Hari, ';   

                if ($row->status_tiket == 1) {
                    //$stiket = '<span class="badge badge-ecommerce badge-primary">Baru</span>';
                    if ($hitungWaktu <= 1) {
                        $stiket = '<span class="badge badge-ecommerce badge-primary">Baru</span>';
                    } else if ($hitungWaktu > 1 && $hitungWaktu <= 3) {
                        $stiket = '<span class="badge badge-ecommerce badge-warning">Baru</span>';
                    } else {
                        $stiket = '<span class="badge badge-ecommerce badge-danger">Baru</span>';
                    }
                } elseif ($row->status_tiket == 2) {
                    //$stiket = '<span class="badge badge-ecommerce badge-info">Dalam Proses</span>';
                    if ($hitungWaktu <= 1) {
                        $stiket = '<span class="badge badge-ecommerce badge-info">Dalam Proses</span>';
                    } else if ($hitungWaktu > 1 && $hitungWaktu <= 3) {
                        $stiket = '<span class="badge badge-ecommerce badge-warning">Dalam Proses</span>';
                    } else {
                        $stiket = '<span class="badge badge-ecommerce badge-danger">Dalam Proses</span>';
                    }
                } elseif ($row->status_tiket == 3) {
                    $stiket = '<span class="badge badge-ecommerce badge-warning">Revisi</span>';
                } elseif ($row->status_tiket == 4) {
                    $stiket = '<span class="badge badge-ecommerce badge-success">Selesai</span>';
                } else {
                    $stiket = '<span class="badge badge-ecommerce badge-danger">Tidak Selesai</span>';
                }

                if ($row->status_pembiayaan == 0 && $row->status_visit == 2) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-danger">Belum Diajukan</span>';
                    if ($row->kota == "Pekanbaru") {
                        $status_biaya = '<span class="badge badge-ecommerce badge-warning">Tidak Diperlukan (Dalam Kota)</span>';
                    }
                } elseif ($row->status_pembiayaan == 1) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-info">Diajukan</span>';
                    $link_biaya = "" . $row->file_biaya1;
                } elseif ($row->status_pembiayaan == 2) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-primary">DiTransfer</span>';
                    $link_biaya = "" . $row->file_biaya1;
                } elseif ($row->status_pembiayaan == 3) {
                    $status_biaya =  '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
                } elseif ($row->status_pembiayaan == 4) {
                    $status_biaya =  '<span class="badge badge-ecommerce badge-success">Selesai</span>';
                    $link_biaya = "" . $row->file_biaya2;
                } else if ($row->status_pembiayaan == 0 && $row->status_visit == 1) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-warning">Tidak Diperlukan</span>';
                }

                if ($row->status_surat_dinas == 0 && $row->status_visit == 2) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-danger">Belum Diajukan</span>';
                } else if ($row->status_surat_dinas == 1) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-info">Diajukan</span>';
                } elseif ($row->status_surat_dinas == 2) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-success">Disetujui</span>';
                    $link_sudin = "" . $row->file_sudin;
                } elseif ($row->status_surat_dinas == 3) {
                    $status_sudin =  '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
                } else if ($row->status_surat_dinas == 0 && $row->status_visit == 1) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-warning">Tidak Diperlukan</span>';
                }

                $id     = encrypt($row->id_tiket);
                $kotik  = '<a href="tiket/show/detail_tiket/' . $id . '")>' . $row->kode_tiket . '</a>';
                $sudin  = $status_sudin;
                $biaya  = $status_biaya;
                if ($row->status_surat_dinas == 2) {
                    $sudin  = '<a href="' . $link_sudin . '")>' . $status_sudin . '</a>';
                }

                if ($row->status_pembiayaan == 1 || $row->status_pembiayaan == 2) {
                    $biaya  = '<a href="surat/show/detail_surat/PB/' . $link_biaya . '/1" target="blank">' . $status_biaya . '</a>';
                } else if ($row->status_pembiayaan == 4) {
                    $biaya  = '<a href="surat/show/detail_surat/laporan_PB/' . $link_biaya . '/1" target="blank">' . $status_biaya . '</a>';
                }

                $th = array();
                $th[] = ++$start . '.';
                $th[] = $kotik;
                $th[] = $row->identitas_pelanggan;
                $th[] = $prioritas;
                $th[] = $row->nama;
                //$th[] = $status_visit;
                //$th[] = $pd;
                //$th[] = $sudin;
                //$th[] = $biaya;
                $th[] = $stiket;

                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($param == 'konfirmasi_ga') {

            $dt    = $this->md_tiket->getTiketGA();
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                if ($row->prioritas == 1) {
                    $prioritas = '<span class="badge badge-ecommerce badge-success">Low</span>';
                } elseif ($row->prioritas == 2) {
                    $prioritas = '<span class="badge badge-ecommerce badge-primary">Medium</span>';
                } elseif ($row->prioritas == 3) {
                    $prioritas =  '<span class="badge badge-ecommerce badge-warning">High</span>';
                } else {
                    $prioritas = '<span class="badge badge-ecommerce badge-danger">Urgent</span>';
                }

                $from = date_create($row->created_at);
                $too = date_create();
                $diff  = date_diff($from, $too); //untuk menghitung hari
                $hitungWaktu = $diff->d;
                // echo $diff->d . ' Hari, ';   

                if ($row->status_tiket == 1) {
                    //$stiket = '<span class="badge badge-ecommerce badge-primary">Baru</span>';
                    if ($hitungWaktu <= 1) {
                        $stiket = '<span class="badge badge-ecommerce badge-primary">Baru</span>';
                    } else if ($hitungWaktu > 1 && $hitungWaktu <= 3) {
                        $stiket = '<span class="badge badge-ecommerce badge-warning">Baru</span>';
                    } else {
                        $stiket = '<span class="badge badge-ecommerce badge-danger">Baru</span>';
                    }
                } elseif ($row->status_tiket == 2) {
                    //$stiket = '<span class="badge badge-ecommerce badge-info">Dalam Proses</span>';
                    if ($hitungWaktu <= 1) {
                        $stiket = '<span class="badge badge-ecommerce badge-info">Dalam Proses</span>';
                    } else if ($hitungWaktu > 1 && $hitungWaktu <= 3) {
                        $stiket = '<span class="badge badge-ecommerce badge-warning">Dalam Proses</span>';
                    } else {
                        $stiket = '<span class="badge badge-ecommerce badge-danger">Dalam Proses</span>';
                    }
                } elseif ($row->status_tiket == 3) {
                    $stiket = '<span class="badge badge-ecommerce badge-warning">Revisi</span>';
                } elseif ($row->status_tiket == 4) {
                    $stiket = '<span class="badge badge-ecommerce badge-success">Selesai</span>';
                } else {
                    $stiket = '<span class="badge badge-ecommerce badge-danger">Tidak Selesai</span>';
                }

                if ($row->status_visit == 1) {
                    $status_visit = '<span class="badge badge-ecommerce badge-warning">No Visit</span>';
                } elseif ($row->status_visit == 2) {
                    $status_visit = '<span class="badge badge-ecommerce badge-success">Visit</span>';
                }

                if ($row->status_pembiayaan == 0 && $row->status_visit == 2) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-danger">Belum Diajukan</span>';
                    if ($row->kota == "Pekanbaru") {
                        $status_biaya = '<span class="badge badge-ecommerce badge-warning">Tidak Diperlukan (Dalam Kota)</span>';
                    }
                } elseif ($row->status_pembiayaan == 1) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-info">Diajukan</span>';
                    $link_biaya = "" . $row->file_biaya1;
                } elseif ($row->status_pembiayaan == 2) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-primary">DiTransfer</span>';
                    $link_biaya = "" . $row->file_biaya1;
                } elseif ($row->status_pembiayaan == 3) {
                    $status_biaya =  '<span class="badge badge-ecommerce badge-warning">Ditolak</span>';
                } elseif ($row->status_pembiayaan == 4) {
                    $status_biaya =  '<span class="badge badge-ecommerce badge-success">Selesai</span>';
                    $link_biaya = "" . $row->file_biaya2;
                } else if ($row->status_pembiayaan == 0 && $row->status_visit == 1) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-warning">Tidak Diperlukan</span>';
                }

                if ($row->status_surat_dinas == 0 && $row->status_visit == 2) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-danger">Belum Diajukan</span>';
                } else if ($row->status_surat_dinas == 1) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-info">Diajukan</span>';
                } elseif ($row->status_surat_dinas == 2) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-success">Disetujui</span>';
                    $link_sudin = "" . $row->file_sudin;
                } elseif ($row->status_surat_dinas == 3) {
                    $status_sudin =  '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
                } else if ($row->status_surat_dinas == 0 && $row->status_visit == 1) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-warning">Tidak Diperlukan</span>';
                }

                $id     = encrypt($row->id_tiket);
                $kotik  = '<a href="tiket/show/detail_tiket/' . $id . '")>' . $row->kode_tiket . '</a>';
                $sudin  = $status_sudin;
                $biaya  = $status_biaya;
                if ($row->status_surat_dinas == 2) {
                    $sudin  = '<a href="' . $link_sudin . '")>' . $status_sudin . '</a>';
                }

                if ($row->status_pembiayaan == 1 || $row->status_pembiayaan == 2) {
                    $biaya  = '<a href="surat/show/detail_surat/PB/' . $link_biaya . '/1" target="blank">' . $status_biaya . '</a>';
                } else if ($row->status_pembiayaan == 4) {
                    $biaya  = '<a href="surat/show/detail_surat/laporan_PB/' . $link_biaya . '/1" target="blank">' . $status_biaya . '</a>';
                }

                $th = array();
                $th[] = ++$start . '.';
                $th[] = $kotik;
                $th[] = $row->identitas_pelanggan;
                $th[] = $prioritas;
                $th[] = $row->nama;
                $th[] = $status_visit;
                $th[] = $sudin;
                $th[] = $biaya;
                $th[] = $stiket;

                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($param == 'konfirmasi_cro') {

            $dt    = $this->md_tiket->getTiketGA();
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                if ($row->prioritas == 1) {
                    $prioritas = '<span class="badge badge-ecommerce badge-success">Low</span>';
                } elseif ($row->prioritas == 2) {
                    $prioritas = '<span class="badge badge-ecommerce badge-primary">Medium</span>';
                } elseif ($row->prioritas == 3) {
                    $prioritas =  '<span class="badge badge-ecommerce badge-warning">High</span>';
                } else {
                    $prioritas = '<span class="badge badge-ecommerce badge-danger">Urgent</span>';
                }

                $from = date_create($row->created_at);
                $too = date_create();
                $diff  = date_diff($from, $too); //untuk menghitung hari
                $hitungWaktu = $diff->d;
                // echo $diff->d . ' Hari, ';   

                if ($row->status_tiket == 1) {
                    //$stiket = '<span class="badge badge-ecommerce badge-primary">Baru</span>';
                    if ($hitungWaktu <= 1) {
                        $stiket = '<span class="badge badge-ecommerce badge-primary">Baru</span>';
                    } else if ($hitungWaktu > 1 && $hitungWaktu <= 3) {
                        $stiket = '<span class="badge badge-ecommerce badge-warning">Baru</span>';
                    } else {
                        $stiket = '<span class="badge badge-ecommerce badge-danger">Baru</span>';
                    }
                } elseif ($row->status_tiket == 2) {
                    //$stiket = '<span class="badge badge-ecommerce badge-info">Dalam Proses</span>';
                    if ($hitungWaktu <= 1) {
                        $stiket = '<span class="badge badge-ecommerce badge-info">Dalam Proses</span>';
                    } else if ($hitungWaktu > 1 && $hitungWaktu <= 3) {
                        $stiket = '<span class="badge badge-ecommerce badge-warning">Dalam Proses</span>';
                    } else {
                        $stiket = '<span class="badge badge-ecommerce badge-danger">Dalam Proses</span>';
                    }
                } elseif ($row->status_tiket == 3) {
                    $stiket = '<span class="badge badge-ecommerce badge-warning">Revisi</span>';
                } elseif ($row->status_tiket == 4) {
                    $stiket = '<span class="badge badge-ecommerce badge-success">Selesai</span>';
                } else {
                    $stiket = '<span class="badge badge-ecommerce badge-danger">Tidak Selesai</span>';
                }

                if ($row->status_visit == 1) {
                    $status_visit = '<span class="badge badge-ecommerce badge-warning">No Visit</span>';
                } elseif ($row->status_visit == 2) {
                    $status_visit = '<span class="badge badge-ecommerce badge-success">Visit</span>';
                }

                if ($row->status_pembiayaan == 0 && $row->status_visit == 2) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-danger">Belum Diajukan</span>';
                    if ($row->kota == "Pekanbaru") {
                        $status_biaya = '<span class="badge badge-ecommerce badge-warning">Tidak Diperlukan (Dalam Kota)</span>';
                    }
                } elseif ($row->status_pembiayaan == 1) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-info">Diajukan</span>';
                    $link_biaya = "" . $row->file_biaya1;
                } elseif ($row->status_pembiayaan == 2) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-primary">DiTransfer</span>';
                    $link_biaya = "" . $row->file_biaya1;
                } elseif ($row->status_pembiayaan == 3) {
                    $status_biaya =  '<span class="badge badge-ecommerce badge-warning">Ditolak</span>';
                } elseif ($row->status_pembiayaan == 4) {
                    $status_biaya =  '<span class="badge badge-ecommerce badge-success">Selesai</span>';
                    $link_biaya = "" . $row->file_biaya2;
                } else if ($row->status_pembiayaan == 0 && $row->status_visit == 1) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-warning">Tidak Diperlukan</span>';
                }

                if ($row->status_surat_dinas == 0 && $row->status_visit == 2) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-danger">Belum Diajukan</span>';
                } else if ($row->status_surat_dinas == 1) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-info">Diajukan</span>';
                } elseif ($row->status_surat_dinas == 2) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-success">Disetujui</span>';
                    $link_sudin = "" . $row->file_sudin;
                } elseif ($row->status_surat_dinas == 3) {
                    $status_sudin =  '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
                } else if ($row->status_surat_dinas == 0 && $row->status_visit == 1) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-warning">Tidak Diperlukan</span>';
                }

                $id     = encrypt($row->id_tiket);
                $kotik  = '<a href="tiket/show/detail_tiket/' . $id . '")>' . $row->kode_tiket . '</a>';
                $sudin  = $status_sudin;
                $biaya  = $status_biaya;
                if ($row->status_surat_dinas == 2) {
                    $sudin  = '<a href="' . $link_sudin . '")>' . $status_sudin . '</a>';
                }

                if ($row->status_pembiayaan == 1 || $row->status_pembiayaan == 2) {
                    $biaya  = '<a href="surat/show/detail_surat/PB/' . $link_biaya . '/1" target="blank">' . $status_biaya . '</a>';
                } else if ($row->status_pembiayaan == 4) {
                    $biaya  = '<a href="surat/show/detail_surat/laporan_PB/' . $link_biaya . '/1" target="blank">' . $status_biaya . '</a>';
                }

                $th = array();
                $th[] = ++$start . '.';
                $th[] = $kotik;
                $th[] = $row->identitas_pelanggan;
                $th[] = $row->created_at_formated;
                $th[] = $row->subject;
                $th[] = $row->nama;
                $th[] = $status_visit;
                $th[] = $sudin;
                $th[] = $biaya;
                $th[] = $stiket;
                $th[] = $row->catatan_ct;

                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($param == 'konfirmasi_finance') {
            $id_pengguna = sessPenggunaId();

            $dt    = $this->md_tiket->getTiketFinance();
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                if ($row->prioritas == 1) {
                    $prioritas = '<span class="badge badge-ecommerce badge-success">Low</span>';
                } elseif ($row->prioritas == 2) {
                    $prioritas = '<span class="badge badge-ecommerce badge-primary">Medium</span>';
                } elseif ($row->prioritas == 3) {
                    $prioritas =  '<span class="badge badge-ecommerce badge-warning">High</span>';
                } else {
                    $prioritas = '<span class="badge badge-ecommerce badge-danger">Urgent</span>';
                }

                $from = date_create($row->created_at);
                $too = date_create();
                $diff  = date_diff($from, $too); //untuk menghitung hari
                $hitungWaktu = $diff->d;
                // echo $diff->d . ' Hari, ';   

                if ($row->status_tiket == 1) {
                    //$stiket = '<span class="badge badge-ecommerce badge-primary">Baru</span>';
                    if ($hitungWaktu <= 1) {
                        $stiket = '<span class="badge badge-ecommerce badge-primary">Baru</span>';
                    } else if ($hitungWaktu > 1 && $hitungWaktu <= 3) {
                        $stiket = '<span class="badge badge-ecommerce badge-warning">Baru</span>';
                    } else {
                        $stiket = '<span class="badge badge-ecommerce badge-danger">Baru</span>';
                    }
                } elseif ($row->status_tiket == 2) {
                    //$stiket = '<span class="badge badge-ecommerce badge-info">Dalam Proses</span>';
                    if ($hitungWaktu <= 1) {
                        $stiket = '<span class="badge badge-ecommerce badge-info">Dalam Proses</span>';
                    } else if ($hitungWaktu > 1 && $hitungWaktu <= 3) {
                        $stiket = '<span class="badge badge-ecommerce badge-warning">Dalam Proses</span>';
                    } else {
                        $stiket = '<span class="badge badge-ecommerce badge-danger">Dalam Proses</span>';
                    }
                } elseif ($row->status_tiket == 3) {
                    $stiket = '<span class="badge badge-ecommerce badge-warning">Revisi</span>';
                } elseif ($row->status_tiket == 4) {
                    $stiket = '<span class="badge badge-ecommerce badge-success">Selesai</span>';
                } else {
                    $stiket = '<span class="badge badge-ecommerce badge-danger">Tidak Selesai</span>';
                }

                if ($row->status_visit == 1) {
                    $status_visit = '<span class="badge badge-ecommerce badge-warning">No Visit</span>';
                } elseif ($row->status_visit == 2) {
                    $status_visit = '<span class="badge badge-ecommerce badge-success">Visit</span>';
                }

                if ($row->status_pembiayaan == 0 && $row->status_visit == 2) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-danger">Belum Diajukan</span>';
                    if ($row->kota == "Pekanbaru") {
                        $status_biaya = '<span class="badge badge-ecommerce badge-warning">Tidak Diperlukan (Dalam Kota)</span>';
                    }
                } elseif ($row->status_pembiayaan == 1) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-info">Diajukan</span>';
                    $link_biaya = "" . $row->file_biaya1;
                } elseif ($row->status_pembiayaan == 2) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-primary">DiTransfer</span>';
                    $link_biaya = "" . $row->file_biaya1;
                } elseif ($row->status_pembiayaan == 3) {
                    $status_biaya =  '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
                } elseif ($row->status_pembiayaan == 4) {
                    $status_biaya =  '<span class="badge badge-ecommerce badge-success">Selesai</span>';
                    $link_biaya = "" . $row->file_biaya2;
                } else if ($row->status_pembiayaan == 0 && $row->status_visit == 1) {
                    $status_biaya = '<span class="badge badge-ecommerce badge-warning">Tidak Diperlukan</span>';
                }

                if ($row->status_surat_dinas == 0 && $row->status_visit == 2) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-danger">Belum Diajukan</span>';
                } else if ($row->status_surat_dinas == 1) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-info">Diajukan</span>';
                } elseif ($row->status_surat_dinas == 2) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-success">Disetujui</span>';
                    $link_sudin = "" . $row->file_sudin;
                } elseif ($row->status_surat_dinas == 3) {
                    $status_sudin =  '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
                } else if ($row->status_surat_dinas == 0 && $row->status_visit == 1) {
                    $status_sudin = '<span class="badge badge-ecommerce badge-warning">Tidak Diperlukan</span>';
                }

                $id    = encrypt($row->id_tiket);
                $koTik = '<a href="tiket/show/detail_tiket/' . $id . '")>' . $row->kode_tiket . '</a>';
                $sudin  = $status_sudin;
                $biaya  = $status_biaya;
                if ($row->status_surat_dinas == 2) {
                    $sudin  = '<a href="' . $link_sudin . '")>' . $status_sudin . '</a>';
                }

                if ($row->status_pembiayaan == 1 || $row->status_pembiayaan == 2) {
                    $biaya  = '<a href="surat/show/detail_surat/PB/' . $link_biaya . '/1" target="blank">' . $status_biaya . '</a>';
                } else if ($row->status_pembiayaan == 4) {
                    $biaya  = '<a href="surat/show/detail_surat/laporan_PB/' . $link_biaya . '/1" target="blank">' . $status_biaya . '</a>';
                }

                $th = array();
                $th[] = ++$start . '.';
                $th[] = $koTik;
                $th[] = $prioritas;
                $th[] = $row->nama;
                $th[] = $status_visit;
                $th[] = $sudin;
                $th[] = $biaya;
                $th[] = $stiket;

                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else {
            // ============================================================
            // PERBAIKAN UTAMA ADA DI SINI (TABEL UTAMA TIKET)
            // ============================================================
            $id_pengguna = sessPenggunaId();

            $dt    = $this->md_tiket->getAllTiket();
            $start = $this->input->post('start');
            $data  = array();

            foreach ($dt['data'] as $row) {
                // Logic Prioritas
                if ($row->prioritas == 1) {
                    $prioritas = '<span class="badge badge-ecommerce badge-success">Low</span>';
                } elseif ($row->prioritas == 2) {
                    $prioritas = '<span class="badge badge-ecommerce badge-primary">Medium</span>';
                } elseif ($row->prioritas == 3) {
                    $prioritas =  '<span class="badge badge-ecommerce badge-warning">High</span>';
                } else {
                    $prioritas = '<span class="badge badge-ecommerce badge-danger">Urgent</span>';
                }

                $from = date_create($row->created_at);
                $too = date_create();
                $diff  = date_diff($from, $too);
                $hitungWaktu = $diff->d;

                // Logic Status Tiket
                if ($row->status_tiket == 1) {
                    if ($hitungWaktu <= 1) {
                        $stiket = '<span class="badge badge-ecommerce badge-primary">Baru</span>';
                    } else if ($hitungWaktu > 1 && $hitungWaktu <= 3) {
                        $stiket = '<span class="badge badge-ecommerce badge-warning">Baru</span>';
                    } else {
                        $stiket = '<span class="badge badge-ecommerce badge-danger">Baru</span>';
                    }
                } elseif ($row->status_tiket == 2) {
                    if ($hitungWaktu <= 1) {
                        $stiket = '<span class="badge badge-ecommerce badge-info">Dalam Proses</span>';
                    } else if ($hitungWaktu > 1 && $hitungWaktu <= 3) {
                        $stiket = '<span class="badge badge-ecommerce badge-warning">Dalam Proses</span>';
                    } else {
                        $stiket = '<span class="badge badge-ecommerce badge-danger">Dalam Proses</span>';
                    }
                } elseif ($row->status_tiket == 3) {
                    $stiket = '<span class="badge badge-ecommerce badge-warning">Revisi</span>';
                } elseif ($row->status_tiket == 4) {
                    $stiket = '<span class="badge badge-ecommerce badge-success">Selesai</span>';
                } else {
                    $stiket = '<span class="badge badge-ecommerce badge-danger">Tidak Selesai</span>';
                }

                $id      = encrypt($row->id_tiket);
                $koTik   = '<a href="tiket/show/detail_tiket/' . $id . '">' . $row->kode_tiket . '</a>';

                // Link Download Laporan
                if ($row->laporan_teknisi != "") {
                    $link_download = '<a href="' . $row->laporan_teknisi . '" target="_blank"><i class="fas fa-download"></i> Download</a>';
                } else {
                    $link_download = "-";
                }

                $li_btn = '<div class="btn-group" role="group">';

                // 1. Tombol Edit (Tetap muncul untuk user yang punya akses halaman ini)
                $li_btn .= '<a href="tiket/show/detail_tiket/' . $id . '" class="btn btn-sm btn-primary" title="Edit"><i class="bx bx-pencil"></i></a>';

                // 2. Tombol Close Tiket (LOGIKA BARU)
                // Syarat: Tiket belum selesai (status != 4) DAN (User adalah Admin ATAU ID 755)
                if ($row->status_tiket != 4 && (isAdmin() || sessPenggunaId() == 755)) {
                    $li_btn .= '<button type="button" class="btn btn-sm btn-success btn-close-ticket" data-id="' . $id . '" title="Close Tiket"><i class="bx bx-check"></i></button>';
                }

                // 3. Tombol Hapus (Logika lama Anda: Admin, ID 72, atau ID 85)
                if (isAdmin() || sessPenggunaId() == 72 || sessPenggunaId() == 85) {
                    $li_btn .= '<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="tiket/delete/tiket"><i class="bx bx-trash"></i></button>';
                }

                $li_btn .= '</div>';

                // DATA ROW
                $th = array();
                $th[] = ++$start . '.';
                $th[] = $koTik;
                $th[] = date('d-m-Y', strtotime($row->tgl_terbit)); // Format tanggal agar rapi
                $th[] = $row->identitas_pelanggan;
                $th[] = $row->subject;
                $th[] = date('d-m-Y', strtotime($row->waktu_mulai)); // Tanggal Berangkat
                $th[] = $row->nama_penerima; // Teknisi
                $th[] = $row->deskripsi;
                $th[] = $link_download; // Laporan Teknisi

                // MENYESUAIKAN DENGAN VIEW (KOLOM KONDISIONAL)
                // Cek kondisi sesuai dengan view: isEksetkutif() || isKepalaDivisi() || isAdminDivisi() || sessPenggunaId() == 755
                if (isAdmin() || sessPenggunaId() == 755) {
                    // Kolom Log Aktivitas
                    $th[] = '<button type="button" class="btn btn-sm btn-info btn-log-activity" data-id="' . $id . '" title="Lihat Log"><i class="fas fa-history"></i> Log</button>';

                    // Kolom Status Respond Time (Hitung kasar selisih hari jika perlu, atau string placeholder)
                    // Disini saya buat visualisasi sederhana berdasarkan hitungWaktu
                    if ($hitungWaktu == 0) {
                        $th[] = '<span class="badge badge-success">< 1 Hari</span>';
                    } else {
                        $th[] = '<span class="badge badge-warning">' . $hitungWaktu . ' Hari</span>';
                    }
                }
                $th[] = $stiket;

                $th[] = $li_btn;

                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        }
    }

    public function get_log_history()
    {
        // Decrypt ID Tiket yang dikirim dari AJAX
        $id_tiket = decrypt($this->input->post('id'));
        $data = $this->md_tiket->getUpdateById(['t.id_tiket' => $id_tiket]);

        $html = '<div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>No</th>
                                <th>Nama Pengupdate</th>
                                <th>Waktu Update</th>
                                <th>Aktivitas / Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>';

        if (!empty($data)) {
            $no = 1;
            foreach ($data as $row) {
                $waktu = date('d-m-Y H:i', strtotime($row->waktu));
                $html .= '<tr>';
                $html .= '<td class="text-center">' . $no++ . '</td>';
                $html .= '<td><strong>' . $row->nama_pembuat . '</strong></td>';
                $html .= '<td>' . $waktu . '</td>';
                $html .= '<td>' . $row->update . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="4" class="text-center">Belum ada aktivitas log pada tiket ini.</td></tr>';
        }
        $html .= '</tbody></table></div>';
        echo $html;
    }

    //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Notif Wa -------------------------------------------------------------------------------------------------------------------------------------------------------
    //-------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    public function nopeAdmin($select)
    {
        if ($select == 'ga') {
            $dataAdmin     = $this->md_pengguna->getById(1);
            $nopeAdmin  = $dataAdmin[0]->no_hp;
        } else if ($select == 'financeStaff') {
            $dataAdmin     = $this->md_pengguna->getById(70);
            $nopeAdmin  = $dataAdmin[0]->no_hp;
        }
        return $nopeAdmin;
    }

    public function sendWaTiket($kodeKirim, $dataTiket)
    {
        $nope1  = "";
        $nope2  = "";
        $cek    = "";

        //ambil nomor
        $dataPenerima     = $this->md_pengguna->getById($dataTiket['idPenerima']);
        $nope           = $dataPenerima[0]->no_hp;
        $nama           = $dataPenerima[0]->nama;

        if ($kodeKirim == 6) {
            $nope2  = $this->nopeAdmin('financeStaff');
            if ($dataTiket['cek'] != "") {
                $cek = 1;
            } else {
                $cek = 0;
            }
        } else if ($kodeKirim == 4) {
            $nope = $this->nopeAdmin('financeStaff');
        }
        if ($kodeKirim == 9) {
            $dataWa = [
                'noPenerima'    => $nope,
                'noPenerima1'   => $nope1,
                'noPenerima2'   => $nope2,
                'namaPenerima'  => $nama,
                'groupPenerima' => urlencode($dataTiket['nmGroup']),
                'namaPengaju'   => $nama,
                'kodeTiket'     => $dataTiket['kode'],
                'subject'       => urlencode($dataTiket['subject']),
                'cek'           => $cek,
                'namaPelanggan' => isset($dataTiket['namaPelanggan']) ? urlencode($dataTiket['namaPelanggan']) : '-',
                'nama_cp'       => isset($dataTiket['nama_cp']) ? urlencode($dataTiket['nama_cp']) : '-',
                'nomer_cp'      => isset($dataTiket['nomer_cp']) ? urlencode($dataTiket['nomer_cp']) : '-',
                'deskripsi'     => isset($dataTiket['deskripsi']) ? urlencode($dataTiket['deskripsi']) : '-'
            ];
        } else if ($kodeKirim == 10) {
            $dataWa = [
                'noPenerima'    => $nope,
                'noPenerima1'   => $nope1,
                'noPenerima2'   => $nope2,
                'namaPenerima'  => $nama,
                'melakukan'     => "melakukan UPDATE",
                'detailTiket'   => "pada Log",
                'groupPenerima' => urlencode($dataTiket['nmGroup']),
                'namaPengaju'   => $nama,
                'kodeTiket'     => $dataTiket['kode'],
                'ketPeker'      => urlencode($dataTiket['ketPeker']),
                'linkBukti'     => urlencode($dataTiket['linkBukti']),
                'subject'       => urlencode($dataTiket['subject']),
                'cek'           => $cek,
                'namaPelanggan' => isset($dataTiket['namaPelanggan']) ? urlencode($dataTiket['namaPelanggan']) : '-'
            ];
        } else if ($kodeKirim == 11) {
            $dataWa = [
                'noPenerima'    => $nope,
                'noPenerima1'   => $nope1,
                'noPenerima2'   => $nope2,
                'namaPenerima'  => $nama,
                'melakukan'     => "mengajukan LAPORAN AKHIR",
                'detailTiket'   => "dan CLOSE",
                'groupPenerima' => urlencode($dataTiket['nmGroup']),
                'namaPengaju'   => $nama,
                'kodeTiket'     => $dataTiket['kode'],
                'ketPeker'      => urlencode($dataTiket['ketPeker']),
                'linkBukti'     => urlencode($dataTiket['linkBukti']),
                'subject'       => urlencode($dataTiket['subject']),
                'cek'           => $cek,
                'namaPelanggan' => isset($dataTiket['namaPelanggan']) ? urlencode($dataTiket['namaPelanggan']) : '-'
            ];
        } else {
            $dataWa = [
                'noPenerima'    => $nope,
                'noPenerima1'   => $nope1,
                'noPenerima2'   => $nope2,
                'namaPenerima'  => $nama,
                'namaPengaju'   => $nama,
                'kodeTiket'     => $dataTiket['kode'],
                'subject'       => urlencode($dataTiket['subject']),
                'cek'           => $cek,
                'namaPelanggan' => isset($dataTiket['namaPelanggan']) ? urlencode($dataTiket['namaPelanggan']) : '-'
            ];
        }

        if ($kodeKirim == 1) {
            waTiketOpen($dataWa);
        } else if ($kodeKirim == 2) {
            waTiketAjuVisit($dataWa);
        } else if ($kodeKirim == 3) {
            waTiketTerbitSudin($dataWa);
        } else if ($kodeKirim == 4) {
            waTiketAjuBiaya($dataWa);
        } else if ($kodeKirim == 5) {
            waTiketSetujuBiaya($dataWa);
        } else if ($kodeKirim == 6) {
            waTiketAjuLaporan($dataWa);
        } else if ($kodeKirim == 7) {
            waTiketLogUpdate($dataWa);
        } else if ($kodeKirim == 8) {
            waTiketPertama($dataWa);
        } else if ($kodeKirim == 9) {
            waTiketOpenGroup($dataWa);
        } else if ($kodeKirim == 10) {
            waTiketGroupNew($dataWa);
        } else if ($kodeKirim == 11) {
            waTiketGroupNew($dataWa);
        }
        return TRUE;
    }

    public function sendWaTiketGantiPIC($dataTiket)
    {
        for ($x = 1; $x <= 2; $x++) {
            $dataPenerima     = $this->md_pengguna->getById($dataTiket['idPenerima' . $x]);
            $nope[$x]       = $dataPenerima[0]->no_hp;
            $nama[$x]       = $dataPenerima[0]->nama;
        }

        $dataWa = [
            'noPenerima1'   => $nope[1],
            'noPenerima2'   => $nope[2],
            'nama1'         => $nama[1],
            'nama2'         => $nama[2],
            'kodeTiket'     => $dataTiket['kode'],
            'subject'       => urlencode($dataTiket['subject'])
        ];

        waTiketGantiPIC($dataWa);
        return TRUE;
    }

    public function sendWaTiketMultiPic($kodeKirim, $dataTiket)
    {
        $dataKetua       = $this->md_pengguna->getById($dataTiket['idKetua']);
        $namaKetua      = $dataKetua[0]->nama;
        $namaSupport    = "";

        if ($kodeKirim == 'ketua') {
            $nope       = $dataKetua[0]->no_hp;
        } else if ($kodeKirim == 'support') {
            $dataSupport     = $this->md_pengguna->getById($dataTiket['idSupport']);
            $nope           = $dataSupport[0]->no_hp;
            $namaSupport    = $dataSupport[0]->nama;
            $dataTiket['pic_support'] = "";
        }

        $dataWa = [
            'noPenerima'    => $nope,
            'namaKetua'     => $namaKetua,
            'namaSupport'   => $namaSupport,
            'kodeTiket'     => $dataTiket['kode'],
            'subject'       => urlencode($dataTiket['subject']),
            'pic_support'   => urlencode($dataTiket['pic_support']),
        ];

        if ($kodeKirim == 'ketua') {
            waTiketMultiPicKetua($dataWa);
        } else if ($kodeKirim == 'support') {
            waTiketMultiPicSupport($dataWa);
        }
        return TRUE;
    }


    public function exportlaporan()
    {

        $data = $this->md_tiket->getAllKegiatan($this->input->get('idmarketing'), $this->input->get('idkat'));



        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = [
            'font' => ['bold' => true], // Set font nya jadi bold
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];


        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];

        $hari_ini = date('d F Y \P\u\k\u\l H:i:s', time());
        $sheet->setCellValue('A1', "DATA TIKET - Cetak Tanggal " . $hari_ini);
        $sheet->mergeCells('A1:L1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1

        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Kode Tiket');
        $sheet->setCellValue('C4', 'Tanggal Terbit');
        $sheet->setCellValue('D4', 'Pelanggan');
        $sheet->setCellValue('E4', 'Kategori');
        $sheet->setCellValue('F4', 'Subject');
        $sheet->setCellValue('G4', 'Deskripsi');
        $sheet->setCellValue('H4', 'Deskripsi Pekerjaan / Tindakan');
        $sheet->setCellValue('I4', 'Teknisi');
        $sheet->setCellValue('J4', 'Waktu Mulai');
        $sheet->setCellValue('K4', 'Waktu Selesai');
        $sheet->setCellValue('L4', 'Prioritas');
        $sheet->setCellValue('M4', 'Status');
        $sheet->setCellValue('N4', 'Link Laporan Teknisi');
        $sheet->setCellValue('O4', 'Feedback Customer');
        $sheet->setCellValue('P4', 'Link Feedback Customer');

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $sheet->getStyle('A4')->applyFromArray($style_col);
        $sheet->getStyle('B4')->applyFromArray($style_col);
        $sheet->getStyle('C4')->applyFromArray($style_col);
        $sheet->getStyle('D4')->applyFromArray($style_col);
        $sheet->getStyle('E4')->applyFromArray($style_col);
        $sheet->getStyle('F4')->applyFromArray($style_col);
        $sheet->getStyle('G4')->applyFromArray($style_col);
        $sheet->getStyle('H4')->applyFromArray($style_col);
        $sheet->getStyle('I4')->applyFromArray($style_col);
        $sheet->getStyle('J4')->applyFromArray($style_col);
        $sheet->getStyle('K4')->applyFromArray($style_col);
        $sheet->getStyle('L4')->applyFromArray($style_col);
        $sheet->getStyle('M4')->applyFromArray($style_col);
        $sheet->getStyle('N4')->applyFromArray($style_col);
        $sheet->getStyle('O4')->applyFromArray($style_col);
        $sheet->getStyle('P4')->applyFromArray($style_col);


        $kolom = 5;
        $nomor = 1;

        foreach ($data as $marketing) {

            if ($marketing->status_tiket == 1) {
                $stiket = 'Baru';
            } elseif ($marketing->status_tiket == 2) {
                $stiket = 'Dalam Proses';
            } elseif ($marketing->status_tiket == 3) {
                $stiket = 'Revisi';
            } elseif ($marketing->status_tiket == 4) {
                $stiket = 'Selesai';
            } else {
                $stiket = 'Tidak Selesai';
            }

            if ($marketing->prioritas == 1) {
                $prioritas = 'Low';
            } elseif ($marketing->prioritas == 2) {
                $prioritas = 'Medium';
            } elseif ($marketing->prioritas == 3) {
                $prioritas = 'High';
            } elseif ($marketing->prioritas == 4) {
                $prioritas = 'Urgent';
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $marketing->kode_tiket)
                ->setCellValue('C' . $kolom, date('d-M-Y | H:i', strtotime($marketing->tgl_terbit)))
                ->setCellValue('D' . $kolom, $marketing->identitas_pelanggan)
                ->setCellValue('E' . $kolom, $marketing->nama_topik)
                ->setCellValue('F' . $kolom, $marketing->subject)
                ->setCellValue('G' . $kolom, $marketing->deskripsi)
                ->setCellValue('H' . $kolom, $marketing->des_peker)
                ->setCellValue('I' . $kolom, $marketing->nama_penerima)
                ->setCellValue('J' . $kolom, date('d-M-Y', strtotime($marketing->waktu_mulai)))
                ->setCellValue('K' . $kolom, date('d-M-Y', strtotime($marketing->waktu_selesai)))
                ->setCellValue('L' . $kolom, $prioritas)
                ->setCellValue('M' . $kolom, $stiket)
                ->setCellValue('N' . $kolom, $marketing->laporan_teknisi)
                ->setCellValue('O' . $kolom, $marketing->deskripsi_feedback_cust)
                ->setCellValue('P' . $kolom, $marketing->file_feedback_cust);

            $kolom++;
            $nomor++;
        }

        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(25); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
        $sheet->getColumnDimension('D')->setWidth(45); // Set width kolom D
        $sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('F')->setWidth(45); // Set width kolom F
        $sheet->getColumnDimension('G')->setWidth(65); // Set width kolom G
        $sheet->getColumnDimension('H')->setWidth(115); // Set width kolom H
        $sheet->getColumnDimension('I')->setWidth(30); // Set width kolom H
        $sheet->getColumnDimension('J')->setWidth(20); // Set width kolom H
        $sheet->getColumnDimension('K')->setWidth(20); // Set width kolom H
        $sheet->getColumnDimension('L')->setWidth(15); // Set width kolom H
        $sheet->getColumnDimension('M')->setWidth(15); // Set width kolom H
        $sheet->getColumnDimension('N')->setWidth(115); // Set width kolom H
        $sheet->getColumnDimension('O')->setWidth(35); // Set width kolom H
        $sheet->getColumnDimension('P')->setWidth(115); // Set width kolom H

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Data Buku Tamu");
        ob_end_clean();
        // Proses file excel
        $today = date('d F Y H-i-s', time());
        $filename = "Data Tiket -  Cetak Tanggal " . $today . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
