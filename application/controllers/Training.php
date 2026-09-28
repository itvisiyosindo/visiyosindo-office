<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;

defined('BASEPATH') or exit('No direct script access allowed');

class Training extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pengguna');
        $this->load->model('md_tiket');
        $this->load->model('md_kategori_tiket');
        $this->load->model('md_surat_list');
        $this->load->model('md_training');
        $this->load->model('md_laporan');
        $this->load->model('md_db_kepegawaian');
        $this->load->helper('email_helper');
        $this->load->helper('tanggal_helper');
        $this->load->helper('whatsapp_helper');
    }

    function id_navbar()
    {
        $id_navbar = "kepegawaian";
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

        $page_data['list_kode_akhir']   = $this->md_pengguna->getByWhereStatus1();

        $this->load->view('index', $page_data);
    }

    //ADD
    public function add()
    {
        grantAccessFor('all');

        $this->md_training->reset_increment("training");
        $idTr = $this->md_training->getTrainingKodeId();
        $ambilId = $idTr->id;
        $ambilId = ($ambilId + 1);
        $panjangId = strlen($ambilId);

        if ($panjangId == 1) {
            $kodeTr = "00" . $ambilId;
        } else if ($panjangId == 2) {
            $kodeTr = "0" . $ambilId;
        } else {
            $kodeTr = $ambilId;
        }

        $bulan = ambil_bulan();
        $tahun = ambil_tahun();

        $kodeTr = $kodeTr . "/TD/HRD/VYM/" . $bulan . "/" . $tahun;
        $data['kode_tr']            = $kodeTr;

        $data['id_pengaju']       = sessPenggunaId();
        $data['nama_training']    = $this->input->post('nama_training', TRUE);
        $data['penyelenggara']    = $this->input->post('penyelenggara', TRUE);
        $data['tanggal_mulai']    = date_db_format($this->input->post('tanggal_mulai', TRUE));
        $data['tanggal_selesai']    = date_db_format($this->input->post('tanggal_selesai', TRUE));
        $data['alasan']            = $this->input->post('alasan', TRUE);
        $data['biaya']            = $this->input->post('biaya', TRUE);
        $data['link_pelatihan']   = $this->input->post('link_pelatihan', TRUE);
        $data['id_kepaladivisi']  = $this->input->post('id_kepaladivisi', TRUE);
        $data['manfaat']          = $this->input->post('manfaat', TRUE);
        $data['status']            = 0; //0=Baru 1=Disetujui 2=Ditolak
        $this->md_training->addTraining($data);


        //send notif WA
        if ($data['id_kepaladivisi'] === null || $data['id_kepaladivisi'] == 0) {
            $idPenerimaNotif = 69;
            $namaDivisi      = '_HR and Legal_';
        } else {
            $idPenerimaNotif = $data['id_kepaladivisi'];
            $dataDivisi         = $this->md_pengguna->getById($data['id_kepaladivisi']);
            $namaDivisi      = $dataDivisi[0]->nama;
        }

        $dataWa = [
            'idPenerima1'  => $idPenerimaNotif,
            'idPenerima2'  => '',
            'idPenerima3'  => '',
            'namaSurat'    => 'Training and Development',
            'penerima'     => $namaDivisi,
            'kode'         => $kodeTr
        ];

        $this->notifWaAddSurat(1, $dataWa);

        /** LOG */
        addLog('Pengajuan Training & Development', 'Training & Development Diajukan');
        ajaxReturnDie('success', 'Training & Development Berhasil Diajukan', TRUE);
    }


    public function updateLink($id)
    {
        grantAccessFor('all');

        $file_sertifikat = $this->input->post('file_sertifikat', TRUE);
        $file_kehadiran = $this->input->post('file_kehadiran', TRUE);

        $data = [
            'file_sertifikat' => nl2br($file_sertifikat),
            'file_kehadiran' => nl2br($file_kehadiran),
        ];
        $this->md_training->updateTraining($id, $data);

        /** LOG */
        addLog('Melaporkan Training & Development', 'Permintaan Training & Development Input Link File Sertifikat / Kehadiran');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }


    public function ttd_setujui($param1 = "", $param2 = "")
    {
        grantAccessFor('all');

        if ($param1 == "ttd_1") {

            $id_sp = $this->input->post('id');
            $data['status'] = 1;
            $data['ttd_1'] = 1;
            $this->md_training->updateTraining($id_sp, $data);

            //send notif wa
            $dataWa = [
                'id'             => $id_sp,
                'idPenerima1'     => 33,
                'idPenerima2'     => '',
                'namaSurat'     => 'Training and Development',
                'ttd_sebelum1'     => 'HR and Legal Officer',
                'ttd_sebelum2'     => '',
                'ttd_sebelum3'     => ''
            ];

            $this->notifWaAprovPb(1, 1, $dataWa);

            addLog('Pengajuan Disetujui oleh HR', 'Permintaan Training & Development Disetujui');
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
        } elseif ($param1 == "ttd_2") {
            $id_sp = $this->input->post('id');
            $data['status'] = 2;
            $data['ttd_2'] = 1;
            $this->md_training->updateTraining($id_sp, $data);

            //send notif wa
            $dataWa = [
                'id'             => $id_sp,
                'idPenerima1'     => '',
                'idPenerima2'     => '',
                'namaSurat'     => 'Training and Development',
                'ttd_sebelum1'     => 'HR and Legal Officer',
                'ttd_sebelum2'     => 'General Manager',
                'ttd_sebelum3'     => ''
            ];

            $this->notifWaAprovPb(1, 2, $dataWa);

            /** LOG */
            addLog('Pengajuan Disetujui oleh General Manager', 'Permintaan Training & Development Disetujui');
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
        } elseif ($param1 == "ttd_divisi") {
            $id_sp = $this->input->post('id');
            $data['status'] = 5;
            $data['ttd_divisi'] = 1;
            $this->md_training->updateTraining($id_sp, $data);

            //send notif wa
            $dataWa = [
                'id'             => $id_sp,
                'idPenerima1'     => 69,
                'idPenerima2'     => '',
                'namaSurat'     => 'Training and Development',
                'ttd_sebelum1'     => 'Kepala Divisi',
                'ttd_sebelum2'     => '',
                'ttd_sebelum3'     => ''
            ];

            $this->notifWaAprovPb(1, 1, $dataWa);

            addLog('Pengajuan Disetujui', 'Permintaan Training & Development Disetujui');
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
        }
    }


    public function ttd_tolak($param1 = "", $param2 = "")
    {
        grantAccessFor('all');

        if ($param1 == "ttd_1") {
            $id_sp = $this->input->post('id');
            $data['status'] = 3;
            $data['ttd_1'] = 2;
            $this->md_training->updateTraining($id_sp, $data);

            //send notif wa
            $dataWa = [
                'namaSurat'     => 'Training and Development',
                'id'             => $id_sp,
                'idPenolak'     => 69,
                'namaPenolak'     => '*HR and Legal Officer*'
            ];
            $this->notifWaRejectPb($dataWa);
            /** LOG */
            addLog('Pengajuan Ditolak oleh HR', 'Permintaan Training & Development Ditolak');
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
        } elseif ($param1 == "ttd_2") {
            $id_sp = $this->input->post('id');
            $data['status'] = 4;
            $data['ttd_2'] = 2;
            $this->md_training->updateTraining($id_sp, $data);

            //send notif wa
            $dataWa = [
                'namaSurat'     => 'Training and Development',
                'id'             => $id_sp,
                'idPenolak'     => 33,
                'namaPenolak'     => '*General Manager*'
            ];
            $this->notifWaRejectPb($dataWa);

            /** LOG */
            addLog('Pengajuan Ditolak oleh General Manager', 'Permintaan Training & Development Ditolak');
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
        } elseif ($param1 == "ttd_divisi") {
            $id_sp = $this->input->post('id');
            $data['status'] = 6;
            $data['ttd_divisi'] = 2;
            $this->md_training->updateTraining($id_sp, $data);

            $dataPenolak    = $this->md_pengguna->getById(sessPenggunaId());
            $namaPenolak           = $dataPenolak[0]->nama;

            //send notif wa
            $dataWa = [
                'namaSurat'     => 'Training and Development',
                'id'             => $id_sp,
                'idPenolak'     => sessPenggunaId(),
                'namaPenolak'     => $namaPenolak
            ];
            $this->notifWaRejectPb($dataWa);

            /** LOG */
            addLog('Pengajuan Ditolak oleh General Manager', 'Permintaan Training & Development Ditolak');
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
        }
    }



    public function updateTolak($id)
    {
        grantAccessFor('all');

        $status = $this->input->post('status', TRUE);

        $data = [
            'status' => nl2br($status),
            'status' => "3",
        ];
        $this->md_training->updateTraining($id, $data);

        addLog('Pengajuan Training & Development Ditolak', 'Permintaan Training & Development Ditolak');
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }


    public function show($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'detail') {
            $page_data['switch']              = $this->id_navbar();
            $page_data['data_training']        = $this->md_training->getByWhere($param2);
            $page_data['page_name']           = 'training/v_detail_training';
            $page_data['page_title']          = 'Data';
            $page_data['page_desc']           = 'Management Training & Development';
            $this->load->view('index', $page_data);
        } else if ($param == 'training') {
            $page_data['switch']            = $this->id_navbar();
            $page_data['page_name']       = 'training/v_training';
            $page_data['list_nama']       = $this->md_laporan->getBywhereActive();
            $page_data['page_title']      = 'Data';
            $page_data['page_desc']       = 'Management Training & Development';
            $this->load->view('index', $page_data);
        } else if ($param == 'training_list') {
            $page_data['switch']            = $this->id_navbar();
            $page_data['page_name']       = 'training/v_list_training';
            $page_data['list_nama']       = $this->md_laporan->getBywhereActive();
            $page_data['page_title']      = 'Data';
            $page_data['page_desc']       = 'Management Training & Development';
            $this->load->view('index', $page_data);
        } else if ($param == 'upload_sertifikat') {
            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']     = 'training/v_upload_sertifikat';
            $page_data['page_title']    = 'Upload Dokumen';
            $page_data['page_desc']     = 'Upload Sertifikat & Bukti Kehadiran';
            $this->load->view('index', $page_data);
        }
    }

    public function pagination($param = "", $param2 = "")
    {
        grantAccessFor('all');

        // 1. LOGIC UNTUK LISTING SEMUA / APPROVAL (Admin/HR/Manager)
        if ($param == 'training') {

            $id_pengguna = sessPenggunaId();
            //Akses Data yang ditampilkan
            if (sessPenggunaId() == 1 || sessPenggunaId() == 69 || sessPenggunaId() == 33 || sessPenggunaId() == 23 || sessPenggunaId() == 54 || sessPenggunaId() == 58) {
                $dt     = $this->md_training->getTrainingAll();
            } else {
                $dt     = $this->md_training->getTrainingPersetujuan($id_pengguna);
            }
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                if ($row->status == 0) {
                    $status = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
                } elseif ($row->status == 1) {
                    $status = '<span class="badge badge-ecommerce badge-primary">Disetujui oleh HR</span>';
                } elseif ($row->status == 2) {
                    $status = '<span class="badge badge-ecommerce badge-primary">Disetujui oleh General Manager</span>';
                } elseif ($row->status == 5) {
                    $status = '<span class="badge badge-ecommerce badge-primary">Disetujui oleh Kepala Divisi</span>';
                } elseif ($row->status == 3) {
                    $status = '<span class="badge badge-ecommerce badge-danger">Ditolak HR</span>';
                } elseif ($row->status == 6) {
                    $status = '<span class="badge badge-ecommerce badge-danger">Ditolak Kepala Divisi</span>';
                } else {
                    $status = '<span class="badge badge-ecommerce badge-danger">Ditolak General Manager</span>';
                }

                $id      = encrypt($row->id);
                $kode_fpp    = '<a href="training/show/detail/' . $row->id . '">' . $row->kode_tr . '</a>';
                
                // --- UPDATE FIX: PEMBERSIHAN DATA BIAYA ---
                $raw_biaya = isset($row->biaya) ? $row->biaya : 0;
                $clean_biaya = preg_replace('/[^0-9]/', '', (string)$raw_biaya);
                if(empty($clean_biaya)) { $clean_biaya = 0; }
                $biaya = 'Rp ' . number_format((float)$clean_biaya, 0, ",", ".") . ',-';
                // ------------------------------------------

                $th = array();
                $th[] = ++$start . '.';
                $th[] = $kode_fpp;
                $th[] = $row->namaPengaju;
                $th[] = $row->jabatan;
                $th[] = $row->nama_training;
                $th[] = $row->penyelenggara;
                $th[] = date('d-M-Y', strtotime($row->tanggal_mulai));
                $th[] = $biaya;
                $th[] = $status;

                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;

            // 2. LOGIC UNTUK LISTING HISTORY USER (View biasa)
        } else if ($param == 'training_user') {

            $id_pengguna = sessPenggunaId();
            //Akses Data yang ditampilkan
            $dt     = $this->md_training->getTrainingPengaju($id_pengguna);

            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                if ($row->status == 0) {
                    $status = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
                } elseif ($row->status == 1) {
                    $status = '<span class="badge badge-ecommerce badge-primary">Disetujui oleh HR</span>';
                } elseif ($row->status == 2) {
                    $status = '<span class="badge badge-ecommerce badge-primary">Disetujui oleh General Manager</span>';
                } elseif ($row->status == 5) {
                    $status = '<span class="badge badge-ecommerce badge-primary">Disetujui oleh Kepala Divisi</span>';
                } elseif ($row->status == 3) {
                    $status = '<span class="badge badge-ecommerce badge-danger">Ditolak HR</span>';
                } elseif ($row->status == 6) {
                    $status = '<span class="badge badge-ecommerce badge-danger">Ditolak Kepala Divisi</span>';
                } else {
                    $status = '<span class="badge badge-ecommerce badge-danger">Ditolak General Manager</span>';
                }

                $id      = encrypt($row->id);
                $kode_fpp    = '<a href="training/show/detail/' . $row->id . '">' . $row->kode_tr . '</a>';
                
                // --- UPDATE FIX: PEMBERSIHAN DATA BIAYA ---
                $raw_biaya = isset($row->biaya) ? $row->biaya : 0;
                $clean_biaya = preg_replace('/[^0-9]/', '', (string)$raw_biaya);
                if(empty($clean_biaya)) { $clean_biaya = 0; }
                $biaya = 'Rp ' . number_format((float)$clean_biaya, 0, ",", ".") . ',-';
                // ------------------------------------------

                $th = array();
                $th[] = ++$start . '.';
                $th[] = $kode_fpp;
                $th[] = $row->namaPengaju;
                $th[] = $row->jabatan;
                $th[] = $row->nama_training;
                $th[] = $row->penyelenggara;
                $th[] = date('d-M-Y', strtotime($row->tanggal_mulai));
                $th[] = $biaya;
                $th[] = $status;

                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;

            // 3. LOGIC UNTUK HALAMAN UPLOAD SERTIFIKAT
        } else if ($param == 'training_user_upload') {
            
            $id_pengguna = sessPenggunaId();
            $filter_status = $this->input->post('filter_status');

            // 1. Ambil Data
            if (sessPenggunaId() == 1 || sessPenggunaId() == 69 || sessPenggunaId() == 33 || sessPenggunaId() == 23 || sessPenggunaId() == 54 || sessPenggunaId() == 58 || isAdmin() || isHrd()) {
                $dt = $this->md_training->getTrainingAll();
            } else {
                $dt = $this->md_training->getTrainingPengaju($id_pengguna);
            }

            $start = $this->input->post('start');
            $data  = array();

            foreach ($dt['data'] as $row) {
                
                // --- [FILTER LOGIC] HANYA TAMPILKAN YANG DISETUJUI ---
                // Status Approved: 1 (HR), 2 (GM), 5 (Kepala Divisi)
                // Jika status TIDAK ADA di dalam array approved, maka lewati (continue)
                if (!in_array($row->status, ['1', '2', '5'])) {
                    continue; 
                }
                // -----------------------------------------------------

                // Cek apakah file ada (pastikan Model sudah select t.file_sertifikat)
                $isFileExist = ($row->file_sertifikat != "" && $row->file_sertifikat != NULL);

                // Filter Dropdown (Belum/Sudah Upload)
                if ($filter_status == 'belum' && $isFileExist) { continue; }
                if ($filter_status == 'sudah' && !$isFileExist) { continue; }

                // Format Tampilan Nama
                $tampilanNamaKode = "";
                if (isset($row->namaPengaju)) {
                    $tampilanNamaKode = '<span class="text-dark font-weight-bold" style="font-size:14px;">' . $row->namaPengaju . '</span>';
                    $tampilanNamaKode .= '<br><small class="text-muted">' . $row->kode_tr . '</small>';
                } else {
                    $tampilanNamaKode = $row->kode_tr;
                }

                // Logika Tombol & Badge
                $status_dokumen = '';
                $btnAction      = '';

                if ($isFileExist) {
                    // KONDISI: SUDAH UPLOAD
                    $status_dokumen = '<span class="badge badge-success" style="font-size:12px;"><i class="fa fa-check"></i> Selesai</span>';
                    
                    $linkFile = base_url('uploads/training/' . $row->file_sertifikat);
                    
                    $btnAction = '<a href="' . $linkFile . '" target="_blank" class="btn btn-sm btn-success shadow-sm">
                                    <i class="fa fa-file-pdf"></i> Lihat Sertifikat
                                  </a>';
                } else {
                    // KONDISI: BELUM UPLOAD
                    $status_dokumen = '<span class="badge badge-danger" style="font-size:12px;">Belum Upload</span>';
                    
                    $btnAction = '<button type="button" class="btn btn-sm btn-info shadow-sm" onclick="bukaModalUpload(\'' . $row->id . '\', \'' . $row->kode_tr . '\')">
                                    <i class="fa fa-upload"></i> Upload
                                  </button>';
                }

                $th = array();
                $th[] = ++$start . '.';
                $th[] = $tampilanNamaKode;
                $th[] = $row->nama_training;
                $th[] = $row->penyelenggara;
                $th[] = date('d-M-Y', strtotime($row->tanggal_mulai));
                $th[] = $status_dokumen;
                $th[] = $btnAction;

                $data[] = $th;
            }
            
            $dt['data'] = $data;
            // Update jumlah record agar pagination akurat
            if ($filter_status != "" || count($data) != count($dt['data'])) {
                $dt['recordsFiltered'] = count($data);
            }
            
            echo json_encode($dt);
            die;
        }
    }

    public function process_upload_sertifikat()
    {
        grantAccessFor('all');

        // 1. Ambil ID
        $id = $this->input->post('id_training_modal');
        
        // 2. Cek Data Existing
        $data_existing = $this->md_training->getByWhere($id);
        
        if (empty($data_existing)) {
            ajaxReturnDie('error', 'Data Training tidak ditemukan.');
        }

        $row = $data_existing[0]; 

        // 3. LOGIC HAPUS FILE LAMA (Agar server tidak penuh)
        if ($row->file_sertifikat) {
            $path_file_lama = './uploads/training/' . $row->file_sertifikat;
            if (file_exists($path_file_lama)) {
                unlink($path_file_lama);
            }
        }

        // 4. SIAPKAN NAMA FILE BARU
        // Ganti spasi & titik dengan underscore agar link tidak error
        $nama_clean = str_replace([" ", "."], "_", $row->namaPengaju);
        
        // 5. PROSES UPLOAD
        if (!empty($_FILES['file_sertifikat']['name'])) {
            
            // Konfigurasi Upload
            $config['file_name']        = $nama_clean . '-Sertifikat-' . time(); // Nama unik dengan waktu
            $config['upload_path']      = './uploads/training/';
            $config['allowed_types']    = 'pdf|jpg|jpeg|png';
            $config['max_size']         = 5048; // 5MB

            $this->load->library('upload', $config);
            $this->upload->initialize($config); // Wajib initialize ulang

            if ($this->upload->do_upload('file_sertifikat')) {
                
                // --- A. Jika Berhasil Upload ---
                $uploadData = $this->upload->data();
                $dataUpdate['file_sertifikat'] = $uploadData['file_name'];

                // Update Database
                $this->md_training->updateTraining($id, $dataUpdate);

                // --- B. Kirim Notifikasi WA ke HR (Looping) ---
                $waData = [
                    'namaPengaju'  => $row->namaPengaju,
                    'kodeSurat'    => $row->kode_tr,
                    'namaTraining' => $row->nama_training
                ];
                
                // Panggil Helper yang baru kita buat
                waTrainingUploadSertifikat($waData);
                
                // Catat Log
                addLog('Upload Dokumen Training', 'User mengupload sertifikat untuk ID: ' . $id);
                
                ajaxReturnDie('success', 'Sertifikat berhasil diupload & Notifikasi dikirim ke HR.', TRUE);

            } else {
                // --- C. Jika Gagal Upload ---
                $errorMsg = $this->upload->display_errors('', '');
                ajaxReturnDie('error', 'Gagal upload: ' . $errorMsg);
            }

        } else {
            ajaxReturnDie('error', 'Anda belum memilih file sertifikat.');
        }
    }
    public function print_page($param1 = "", $param2 = "")
    {
        grantAccessFor('all');

        if ($param1 == 'tnd') {
            $gc = $this->md_training->getByWhere($param2);

            $dt = [
                'title_pdf'     => 'Training',
                'object'     => $param1,
                'data_training'     => $this->md_training->getByWhere($param2)
            ];

            $mpdf = new Mpdf(['format' => 'A4']);
            $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');
            $file_pdf = 'Form Pengajuan Training  ' . $gc[0]->id_pengaju;
            $html = $this->load->view('pages/v_print/print_training', $dt, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output($file_pdf . '.pdf', 'I');
        }
    }
    
    // Fungsi Notif WA (Tidak berubah)
    public function notifWaAddSurat($ulang, $detail) { /* ... isi sama ... */ 
        $ambilDataPengaju     = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju         = $ambilDataPengaju[0]->nama;

        for ($i = 1; $i <= $ulang; $i++) {
            if ($i == 1) {
                $idpenerima = $detail['idPenerima1'];
            } else if ($i == 2) {
                $idpenerima = $detail['idPenerima2'];
            }

            $dataPenerima     = $this->md_pengguna->getById($idpenerima);
            //abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);
            //
            $nope            = $dataPenerima[0]->no_hp;
            $namaPenerima    = $dataPenerima[0]->jabatan;
            $dataWa = [
                'namaSurat'     => $detail['namaSurat'],
                'noPenerima'     => $nope,
                'kodeSurat'     => $detail['kode'],
                'namaPengaju'     => $namaPengaju,
                'namaPenerima'     => urlencode($detail['penerima'])
            ];
            waTrainingAdd($dataWa);
        }
    }
    public function notifWaAprovPb($ulang, $param, $detail) { /* ... isi sama ... */
        $ambilDataPengaju     = $this->md_training->getBywhere($detail['id']);
        $namaPengaju         = $ambilDataPengaju[0]->namaPengaju;
        $kode                = $ambilDataPengaju[0]->kode_tr;
        $perihal             = $ambilDataPengaju[0]->nama_training;
        $idpengaju           = $ambilDataPengaju[0]->id_pengaju;

        for ($i = 1; $i <= $ulang; $i++) {
            if ($i == 1) {
                if ($param == 2) {
                    $idpenerima = $idpengaju;
                } else {
                    $idpenerima = $detail['idPenerima1'];
                }
            } else if ($i == 2) {
                $idpenerima = $detail['idPenerima2'];
            }

            $dataPenerima     = $this->md_pengguna->getById($idpenerima);
            $nope            = $dataPenerima[0]->no_hp;
            if ($idpenerima == 69) {
                $jabatan = "_HR and Legal_";
            } else {
                $jabatan            = $dataPenerima[0]->jabatan;
            }
            $dataWa = [
                'namaSurat'     => $detail['namaSurat'],
                'noPenerima'     => $nope,
                'kodeSurat'     => $kode,
                'namaPengaju'     => urlencode($namaPengaju),
                'namaPenerima'     => urlencode($jabatan),
                'perihal'         => $perihal,
                'ttd_sebelum1'     => $detail['ttd_sebelum1'],
                'ttd_sebelum2'     => $detail['ttd_sebelum2'],
                'ttd_sebelum3'     => $detail['ttd_sebelum3'],
                'ttd_sebelum4'     => ''
            ];

            if ($param == '1') {
                waTrainingAprovOnProg($dataWa);
            } else if ($param == '2') {
                waSuratAprovAll($dataWa);
            }
        }
    }
    public function notifWaRejectPb($detail) { /* ... isi sama ... */
        $ambilDataPengaju     = $this->md_training->getBywhere($detail['id']);
        $namaPengaju = $ambilDataPengaju[0]->namaPengaju;
        $kode = $ambilDataPengaju[0]->kode_tr;
        $perihal             = $ambilDataPengaju[0]->nama_training;
        $idpengaju = $ambilDataPengaju[0]->id_pengaju;

        $dataPenerima     = $this->md_pengguna->getById($idpengaju);
        $nope            = $dataPenerima[0]->no_hp;
        $dataPenolak     = $this->md_pengguna->getById($detail['idPenolak']);
        $nopePenolak     = $dataPenolak[0]->no_hp;

        $dataWa = [
            'namaSurat'     => $detail['namaSurat'],
            'noPenerima'     => $nope,
            'kodeSurat'     => $kode,
            'namaPengaju'     => $namaPengaju,
            'perihal'         => $perihal,
            'namaPenolak'     => urlencode($detail['namaPenolak']),
            'noPenolak'     => $nopePenolak
        ];
        waSuratReject($dataWa);
    }
}