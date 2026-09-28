<?php
//abaikan error
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
//
use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Evaluasi extends CI_Controller
{
    function id_navbar()
    {
        $id_navbar = "kepegawaian";
        return $id_navbar;
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']          = $this->id_navbar();
        //$page_data['list_nama']     = $this->md_surat_part_two->getBywhereActive();
        $page_data['list_nama']     = $this->md_laporan->getBywhereActive();
        $page_data['page_name']     = 'evaluasi/v_evaluasi';
        $page_data['page_title']    = 'Evaluasi';
        $page_data['page_desc']     = 'Management Data Evaluasi';
        $this->load->view('index', $page_data);
    }

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_kategori_tiket');
        $this->load->model('md_tiket');
        $this->load->model('md_evaluasi');
        $this->load->model('md_laporan');
        $this->load->model('md_pengguna');
        $this->load->model('md_surat_part_two');
        $this->load->model('md_surat_list');
        $this->load->model('md_prov_kota');
        $this->load->helper('email_helper');
        $this->load->helper('terbilang_helper');
        $this->load->helper('tanggal_helper');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('encrypt_helper');
    }

    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor('all');
        if ($param == 'detail') {
            if ($param2 == 'evaluasi') {
                // TAMBAHKAN baris ini untuk mendapatkan ID
                $id_evaluasi = decrypt($param3);

                // Ambil data utama dulu
                $data_job_sekarang = $this->md_evaluasi->getEvById($id_evaluasi);

                $page_data['switch']          = $this->id_navbar();
                //$page_data['list_nama']     = $this->md_surat_part_two->getBywhereActive();
                $page_data['list_nama']     = $this->md_laporan->getBywhereActive();

                $page_data['data_job']        = $data_job_sekarang; // Gunakan variabel yang sudah diambil
                $page_data['data_detail']   = $this->md_evaluasi->getDetailBywhereID(['f.id_evaluasi' => $id_evaluasi]);
                $page_data['data_detail2']   = $this->md_evaluasi->getDetailBywhereIDDone(['f.id_evaluasi' => $id_evaluasi]);

                // ==========================================================
                // === MEMUAT PENILAIAN UMUM ===
                // ==========================================================
                $page_data['penilaian_umum'] = $this->md_evaluasi->getPenilaianUmum($id_evaluasi);

                // ==========================================================
                // === BARU: AMBIL RIWAYAT EVALUASI ===
                // ==========================================================
                // Kita ambil ID Karyawan dari data evaluasi yang sedang dibuka
                $id_karyawan = $data_job_sekarang->id_pengguna;
                $page_data['riwayat_evaluasi'] = $this->md_evaluasi->getHistoryEvaluasi($id_karyawan);
                // ==========================================================

                $page_data['page_name']     = 'evaluasi/v_evaluasi_detail';
                $page_data['page_title']    = 'Evaluasi';
                $page_data['page_desc']     = 'Detail Evaluasi';
                $this->load->view('index', $page_data);
            } else if ($param2 == 'penilaian') {
                $page_data['switch']          = $this->id_navbar();
                $page_data['list_nama']       = $this->md_surat_part_two->getBywhereActive();

                // Decrypt ID
                $id_evaluasi = decrypt($param3);

                $page_data['data_job']        = $this->md_evaluasi->getEvById($id_evaluasi);
                $page_data['data_detail']     = $this->md_evaluasi->getDetailBywhereIDPengguna(['f.id_evaluasi' => $id_evaluasi]);

                // --- TAMBAHAN: Load data penilaian umum agar muncul di form penilai (jika sudah pernah isi) ---
                $page_data['penilaian_umum']  = $this->md_evaluasi->getPenilaianUmum($id_evaluasi);
                // --------------------------------------------------------------------------------------------

                $page_data['page_name']       = 'evaluasi/v_evaluasi_detail_penilai';
                $page_data['page_title']      = 'Evaluasi';
                $page_data['page_desc']       = 'Detail Evaluasi';
                $this->load->view('index', $page_data);
            } else if ($param2 == 'my_data') {
                $page_data['switch']          = $this->id_navbar();
                $page_data['list_nama']     = $this->md_surat_part_two->getBywhereActive();

                // Decrypt ID evaluasi
                $id_evaluasi_decrypt = decrypt($param3);

                $page_data['data_job']        = $this->md_evaluasi->getEvById($id_evaluasi_decrypt);
                $page_data['data_detail2']   = $this->md_evaluasi->getDetailBywhereIDDone(['f.id_evaluasi' => $id_evaluasi_decrypt]);

                // --- TAMBAHKAN BARIS INI ---
                $page_data['penilaian_umum'] = $this->md_evaluasi->getPenilaianUmum($id_evaluasi_decrypt);
                // ---------------------------

                $page_data['page_name']     = 'evaluasi/v_my_evaluasi_detail';
                $page_data['page_title']    = 'Evaluasi';
                $page_data['page_desc']     = 'Detail Evaluasi';
                $this->load->view('index', $page_data);
            }
        } else if ($param == 'list') {
            if ($param2 == 'my_data') {
                $page_data['switch']          = $this->id_navbar();
                $page_data['page_name']     = 'evaluasi/v_my_evaluasi';
                $page_data['page_title']    = 'Evaluasi';
                $page_data['page_desc']     = 'Management Data Evaluasi';
                $this->load->view('index', $page_data);
            }
        }
    }

    //ADD
    public function add()
    {
        grantAccessFor('all');

        //menambah pengajuan 
        $this->md_evaluasi->reset_increment("evaluasi");


        $data['id_pengaju']   = sessPenggunaId();
        $data['id_pengguna']  = $this->input->post('id_pengguna', TRUE);
        $jenis_evaluasi       = $this->input->post('jenis_evaluasi', TRUE);
        if ($jenis_evaluasi == 1) {
            $data['jenis_evaluasi'] = $this->input->post('jenis_evaluasi', TRUE);
            $data['smt']          = $this->input->post('smt', TRUE);
            $data['tahun']        = $this->input->post('tahun', TRUE);
        } else {
            $data['jenis_evaluasi'] = $this->input->post('jenis_evaluasi', TRUE);
        }
        $this->md_evaluasi->addEv($data);

        $lastGcId = $this->md_evaluasi->getLastId();
        $lastGcId = $lastGcId->id;

        //menambah detail
        // Ambil semua id_jobdesc dari jobdesc_detail yang bernilai 1 berdasarkan id_pengguna
        $id_pengguna = $this->input->post('id_pengguna', TRUE);
        $jobdescList = $this->md_evaluasi->getJobdescByPengguna($id_pengguna);

        // Masukkan ke tabel evaluasi_detail
        if (!empty($jobdescList)) {
            foreach ($jobdescList as $jobdesc) {
                $dataDetail = [
                    'idPengguna'  => $id_pengguna,
                    'id_evaluasi' => $lastGcId,
                    'id_jobdesc'  => $jobdesc->id // ID dari jobdesc_detail
                ];
                $this->md_evaluasi->addEvdetail($dataDetail);
            }
        }



        /** LOG */
        addLog('Evaluasi', 'Menambah Evaluasi Karyawan');
        ajaxReturnDie('success', 'Evaluasi Berhasil Diajukan', TRUE);
    }

    public function updateEvDetail()
    {
        grantAccessFor('all');

        //update detail Stok Opname
        $id_sodetail = $this->input->post('id_sodetail');
        foreach ($id_sodetail as $key => $row) {
            $data['penilaia'] = $this->input->post('penilaia')[$key];
            $data['penilaib'] = $this->input->post('penilaib')[$key];
            $data['penilaic'] = $this->input->post('penilaic')[$key];
            $data['penilaid'] = $this->input->post('penilaid')[$key];
            $data['penilaie'] = $this->input->post('penilaie')[$key];
            $data['penilaif'] = $this->input->post('penilaif')[$key];

            $this->md_evaluasi->updateEvDetail(['id' => decrypt($row)], $data);
        }

        $ambilData     = $this->md_evaluasi->getDetailBywhereID(['f.id' => decrypt($row)]);
        $id_vs            = $ambilData[0]->id_evaluasi;
        $dataUp['status'] = 2; //Status sudah diisi Penilai
        $this->md_evaluasi->update(['id' => $id_vs], $dataUp);

        // Hapus data lama di evaluasi_notif sebelum menyimpan data baru
        $this->md_evaluasi->deleteEvNotif(['id_evaluasi' => $id_vs]);

        // Menyimpan data ke tabel evaluasi_notif tanpa duplikasi id_penilai
        $id_penilai_list = [];
        $fields = ['penilaia', 'penilaib', 'penilaic', 'penilaid', 'penilaie', 'penilaif'];

        foreach ($id_sodetail as $key => $row) {
            foreach ($fields as $field) {
                $id_penilai = $this->input->post($field)[$key];
                if (!empty($id_penilai) && !in_array($id_penilai, $id_penilai_list)) {
                    $id_penilai_list[] = $id_penilai;
                    $dataNotif = [
                        'id_evaluasi' => $id_vs,
                        'id_penilai' => $id_penilai
                    ];
                    $this->md_evaluasi->addEvNotif($dataNotif);
                }
            }
        }


        //add log
        $aksi = 'Evaluasi';
        $ket = 'Mengisi Penilai';
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }

    public function sendNotif()
    {
        grantAccessFor('all');

        $id = $this->input->post('id');
        $data['status'] = 3;
        $this->md_evaluasi->update(['id' => $id], $data);




        // Ambil semua id_penilai berdasarkan id_evaluasi
        $penilaiList = $this->md_evaluasi->getPenilaiByEvaluasiId($id);

        foreach ($penilaiList as $penilai) {
            $id_penilai = $penilai->id_penilai;
            $ambilData  = $this->md_pengguna->getById($id_penilai);

            if (!empty($ambilData)) {
                $namaTerima = $ambilData[0]->nama;
                $idPenerima = $ambilData[0]->pengguna_id;

                //$id_po       	= encrypt($id);
                //$ambilDataEv   = $this->md_evaluasi->getEvById($id);
                //$namaPegawai   = $ambilDataEv[0]->pegawai;
                // $smt           = $ambilDataEv[0]->smt;
                //$tahun         = $ambilDataEv[0]->tahun;


                // Kirim notifikasi WA
                $dataWa = [
                    'id'           => $id,
                    'idPenerima1' => $idPenerima,
                    'idPenerima2' => '',
                    'namaSurat'   => 'Evaluasi Pegawai',
                    'penerima'    => $namaTerima //,
                    //'namaPegawai' => $namaPegawai,
                    //'smt'         => 'Evaluasi Pegawai',
                    //'tahun'       => 'Evaluasi Pegawai'
                ];

                $this->notifWaAddSurat(1, $dataWa);
            }
        }

        /** LOG */
        addLog('Evaluasi', 'Send Notif : ' . $id);
        ajaxReturnDie('success', 'Berhasil', TRUE);
    }


    public function updateEvDetailPenilai()
    {
        $postData = $this->input->post();
        $updateData = [];

        // 1. UPDATE JOBDESC (LOGIC LAMA, TAPI MENERIMA 1-100)
        if (!empty($postData['id_sodetail'])) {
            foreach ($postData['id_sodetail'] as $id) {
                $data = [];
                // Loop untuk nilai dan masukan dari a sampai f
                foreach (range('a', 'f') as $key) {
                    if (!empty($postData["nilai{$key}"][$id])) {
                        $data["nilai{$key}"] = $postData["nilai{$key}"][$id];
                    }
                    if (!empty($postData["masukan{$key}"][$id])) {
                        $data["masukan{$key}"] = $postData["masukan{$key}"][$id];
                    }
                }
                if (!empty($data)) {
                    $updateData[$id] = $data;
                }
            }
        }

        // Eksekusi update jobdesc
        foreach ($updateData as $id => $data) {
            $this->md_evaluasi->updateEvDetail(['id' => $id], $data);
        }

        // 2. UPDATE EVALUASI UMUM (LOGIC BARU REVISI 1)
        // Mengambil data dari input form II. Penilaian Umum
        $id_evaluasi_umum = $this->input->post('id_evaluasi_umum');
        $my_code = $this->input->post('my_penilai_code'); // a, b, c ...

        if (!empty($id_evaluasi_umum) && !empty($my_code)) {
            $dataUmum = [];
            // Loop indikator 1-5
            for ($i = 1; $i <= 5; $i++) {
                // Tangkap input name="umum_nilai_1", simpan ke field "nilaia1" (sesuai kode penilai)
                $input_nilai = $this->input->post('umum_nilai_' . $i);
                $input_catatan = $this->input->post('umum_catatan_' . $i);

                if ($input_nilai !== NULL) {
                    $dataUmum["nilai{$my_code}{$i}"] = $input_nilai;
                }
                if ($input_catatan !== NULL) {
                    $dataUmum["catatan{$my_code}{$i}"] = $input_catatan;
                }
            }

            // Simpan ke tabel evaluasi_umum jika ada data
            if (!empty($dataUmum)) {
                $this->md_evaluasi->saveOrUpdatePenilaianUmum($dataUmum, $id_evaluasi_umum);
            }
        }

        // Update Status Utama
        // Kita butuh ID Evaluasi Utama untuk update status
        // Ambil dari hidden field 'id_evaluasi_umum' atau query ulang dari salah satu detail
        $id_vs = 0;
        if (!empty($id_evaluasi_umum)) {
            $id_vs = $id_evaluasi_umum;
        } else if (!empty($postData['id_sodetail'])) {
            // Fallback logic lama
            $first_id = $postData['id_sodetail'][0];
            $ambilData = $this->md_evaluasi->getDetailBywhereID(['f.id' => $first_id]);
            $id_vs = $ambilData[0]->id_evaluasi;
        }

        if ($id_vs != 0) {
            $dataUp['status'] = 4; // Status sudah diisi Penilai
            $this->md_evaluasi->update(['id' => $id_vs], $dataUp);

            // Notifikasi WA (Logic tetap sama)
            $myID = sessPenggunaId();
            $dataWa = [
                'id'            => $id_vs,
                'idPenerima1'   => '69',
                'idPenerima2'   => '58',
                // 'idPenerima3'   => $myID,
                'namaSurat'     => 'Evaluasi Pegawai'
            ];
            $this->notifWaSimpanSurat(2, $dataWa);
        }

        // add log
        $aksi = 'Evaluasi';
        $ket = 'Mengisi Nilai (Jobdesc & Umum)';
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Disimpan', TRUE);
    }

    public function endPenilaian()
    {
        grantAccessFor('all');

        $id = $this->input->post('id');
        $data['status'] = 5;
        $this->md_evaluasi->update(['id' => $id], $data);

        /*  // Ambil semua id_penilai berdasarkan id_evaluasi
        $penilaiList = $this->md_evaluasi->getPenilaiByEvaluasiId($id);
        
        foreach ($penilaiList as $penilai) {
            $id_penilai = $penilai->id_penilai;
            $ambilData  = $this->md_pengguna->getById($id_penilai);
            
            if (!empty($ambilData)) {
                $namaTerima = $ambilData[0]->nama;
                $idPenerima = $ambilData[0]->pengguna_id;

                //$id_po       	= encrypt($id);
                //$ambilDataEv   = $this->md_evaluasi->getEvById($id);
                //$namaPegawai   = $ambilDataEv[0]->pegawai;
                // $smt           = $ambilDataEv[0]->smt;
                //$tahun         = $ambilDataEv[0]->tahun;


                // Kirim notifikasi WA
                $dataWa = [
                    'id' 	      => $id,
                    'idPenerima1' => $idPenerima,
                    'idPenerima2' => '',
                    'namaSurat'   => 'Evaluasi Pegawai',
                    'penerima'    => $namaTerima//,
                    //'namaPegawai' => $namaPegawai,
                    //'smt'         => 'Evaluasi Pegawai',
                    //'tahun'       => 'Evaluasi Pegawai'
                ];

                $this->notifWaAddSurat(1, $dataWa);
            }
        }*/

        /** LOG */
        addLog('Evaluasi', 'Selesaikan Penilaian : ' . $id);
        ajaxReturnDie('success', 'Berhasil', TRUE);
    }

    public function addDetail()
    {
        grantAccessFor('all');

        //menambah pengajuan PO
        $data['id_jobdesc']  = $this->input->post('id_jobdesc', TRUE);
        $data['idPengguna']  = $this->input->post('id_pengguna', TRUE);
        $data['deskripsi']   = $this->input->post('deskripsi', TRUE);
        $this->md_evaluasi->addJobdetail($data);


        /** LOG */
        addLog('Jobdesk', 'Menambah Jobdesk Karyawan');
        ajaxReturnDie('success', 'Jobdesk Berhasil Diajukan', TRUE);
    }

    //DELETE
    public function delete($id)
    {
        grantAccessFor('all');

        //$status = $this->input->post('status', TRUE);

        $data = [
            'status' => "2",
        ];


        $this->md_evaluasi->updateDetail($id, $data);

        addLog('Menghapus Jobdesk', 'Menghapus Jobdesk  ');
        ajaxReturnDie('success', 'JObdesk berhasil dihapus', TRUE);
    }

    public function edit($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_evaluasi->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    public function updateDetail()
    {
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));
        $data['Deskripsi']    = $this->input->post('deskripsi', TRUE);
        //$data['status']    				= 1;

        $this->md_evaluasi->updateDetail($id, $data);

        /** LOG */
        addLog('Update Jobdesk', 'Memperbarui data Jobdesk "' . $data['Deskripsi'] . '"');
        ajaxReturnDie('success', 'Data Jobdesk berhasil diperbarui', TRUE);
    }

    //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // pagination -------------------------------------------------------------------------------------------------------------------------------------------------------
    //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function pagination($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'all') {

            //if(sessPenggunaId()==1 || sessPenggunaId()==107 || sessPenggunaId()==722 || sessPenggunaId()==23 || sessPenggunaId()==33 || sessPenggunaId()==15 || sessPenggunaId()==7 || sessPenggunaId()==54){
            $dt     = $this->md_evaluasi->getAllEv();
            //}else{
            //  $dt     = $this->md_evaluasi->getAllPObyID(sessPenggunaId());
            // }
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                $id_po           = encrypt($row->id_po);
                $pegawai    = '<a href="evaluasi/show/detail/evaluasi/' . $id_po . '">' . $row->pegawai . '</a>';

                if ($row->status == "1") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
                } else if ($row->status == "2") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Penilai Diinput</span>';
                } else if ($row->status == "3") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Notifikasi Dikirim</span>';
                } else if ($row->status == "4") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Nilai Diinput</span>';
                } else {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Selesai</span>';
                }

                if ($row->jenis_evaluasi == 1) {
                    $jenis_evaluasi = "Semester";
                } else {
                    $jenis_evaluasi = "Kontrak";
                }


                $th = array();
                $th[] = ++$start;
                $th[] = $pegawai;
                $th[] = $row->no_pegawai;
                $th[] = $row->jabatan;
                $th[] = $jenis_evaluasi;
                $th[] = $row->smt;
                $th[] = $row->tahun;
                $th[] = $stat_surat;
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($param == 'detail_penilai') {

            $dt     = $this->md_evaluasi->getAllEvPenilai(sessPenggunaId());
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                $id_po           = encrypt($row->id_po);
                $pegawai    = '<a href="evaluasi/show/detail/penilaian/' . $id_po . '">' . $row->pegawai . '</a>';

                if ($row->status == "1") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
                } else if ($row->status == "2") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Penilai Diinput</span>';
                } else if ($row->status == "3") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Notifikasi Dikirim</span>';
                } else if ($row->status == "4") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Nilai Diinput</span>';
                } else {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Selesai</span>';
                }

                if ($row->jenis_evaluasi == 1) {
                    $jenis_evaluasi = "Semester";
                } else {
                    $jenis_evaluasi = "Kontrak";
                }


                $th = array();
                $th[] = ++$start;
                $th[] = $pegawai;
                $th[] = $row->no_pegawai;
                $th[] = $row->jabatan;
                $th[] = $jenis_evaluasi;
                $th[] = $row->smt;
                $th[] = $row->tahun;
                $th[] = $stat_surat;
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($param == 'my_detail') {

            $dt     = $this->md_evaluasi->getAllEvMy(sessPenggunaId());
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {

                $id_po           = encrypt($row->id_po);
                $pegawai    = '<a href="evaluasi/show/detail/my_data/' . $id_po . '">' . $row->pegawai . '</a>';

                if ($row->status == "1") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
                } else if ($row->status == "2") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Penilai Diinput</span>';
                } else if ($row->status == "3") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Notifikasi Dikirim</span>';
                } else if ($row->status == "4") {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Nilai Diinput</span>';
                } else {
                    $stat_surat = '<span class="badge badge-ecommerce badge-info">Selesai</span>';
                }

                if ($row->jenis_evaluasi == 1) {
                    $jenis_evaluasi = "Semester";
                } else {
                    $jenis_evaluasi = "Kontrak";
                }


                $th = array();
                $th[] = ++$start;
                $th[] = $pegawai;
                $th[] = $row->no_pegawai;
                $th[] = $row->jabatan;
                $th[] = $jenis_evaluasi;
                $th[] = $row->smt;
                $th[] = $row->tahun;
                $th[] = $stat_surat;
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        }
    }

    //-------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Notif Wa -------------------------------------------------------------------------------------------------------------------------------------------------------
    //-------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    public function notifWaAddSurat($ulang, $detail)
    {
        //ambil data pengaju
        $ambilDataPengaju     = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju        = $ambilDataPengaju[0]->nama;


        $id_po         = encrypt($detail['id']);
        $ambilDataEv   = $this->md_evaluasi->getEvById($detail['id']);
        $namaPegawai   = $ambilDataEv->pegawai;
        $smt           = $ambilDataEv->smt;
        $tahun         = $ambilDataEv->tahun;

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

            $nope           = $dataPenerima[0]->no_hp;
            $dataWa = [
                'namaSurat'     => urlencode($detail['namaSurat']),
                'noPenerima'     => $nope,
                'namaPegawai'     => urlencode($namaPegawai),
                'namaPengaju'   => urlencode($namaPengaju),
                'link'             => urlencode($id_po),
                'smt'             => urlencode($smt),
                'tahun'         => urlencode($tahun),
                'namaPenerima'     => urlencode($detail['penerima'])
            ];
            waEvaluasi($dataWa);
        }
    }

    public function notifWaSimpanSurat($ulang, $detail)
    {
        //ambil data pengaju
        $ambilDataPengaju     = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju        = $ambilDataPengaju[0]->nama;


        $id_po         = encrypt($detail['id']);
        $ambilDataEv   = $this->md_evaluasi->getEvById($detail['id']);
        $namaPegawai   = $ambilDataEv->pegawai;
        $smt           = $ambilDataEv->smt;
        $tahun         = $ambilDataEv->tahun;


        $dataPenerima3     = $this->md_pengguna->getById($detail['idPenerima3']);
        $nama3           = $dataPenerima3[0]->nama;

        for ($i = 1; $i <= $ulang; $i++) {
            if ($i == 1) {
                $idpenerima = $detail['idPenerima1'];
                $penerima = '_HR and Legal_';
            } else if ($i == 2) {
                $idpenerima = $detail['idPenerima2'];
                $penerima = '_General Affairs_';
            } else if ($i == 3) {
                $idpenerima = $detail['idPenerima3'];
                $penerima = $nama3;
            }

            $dataPenerima     = $this->md_pengguna->getById($idpenerima);
            //abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);
            //

            $nope           = $dataPenerima[0]->no_hp;
            $dataWa = [
                'namaSurat'     => urlencode($detail['namaSurat']),
                'noPenerima'     => $nope,
                'namaPegawai'     => urlencode($namaPegawai),
                'namaPengaju'   => urlencode($namaPengaju),
                'link'             => urlencode($id_po),
                'smt'             => urlencode($smt),
                'tahun'         => urlencode($tahun),
                'namaPenerima'     => urlencode($penerima)
            ];
            waEvaluasiSimpan($dataWa);
        }
    }

    public function print_page($param1 = "", $param2 = "")
    {
        grantAccessFor('all');

        if ($param1 == 'evaluasi') {


            $gc            = $this->md_evaluasi->getEvById(decrypt($param2));

            $dt = [
                'title_pdf'    => 'po',
                'object'    => $param1,
                'data_job'      => $this->md_evaluasi->getEvById(decrypt($param2)),
                'data_detail'   => $this->md_evaluasi->getDetailBywhereID(['f.id_evaluasi' => decrypt($param2)]),
                'data_detail2'  => $this->md_evaluasi->getDetailBywhereIDDone(['f.id_evaluasi' => decrypt($param2)])
            ];



            //load mpdf dan membuat page size 
            $mpdf = new Mpdf(['format' => 'A4']);

            // $mpdf->SetMargins(0, 0, 0, true);

            //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
            $mpdf->AddPage('L', '', '', '', '', '5', '5', '4', '1');

            // filename dari pdf ketika didownload
            $file_pdf = 'Evaluasi  '; //.$gc[0]->pegawai;

            // page htmk yang akan di jadikan ke pdf
            $html = $this->load->view('pages/v_print/print_evaluasi', $dt, true);

            $mpdf->WriteHTML($html);
            $mpdf->Output($file_pdf . '.pdf', 'I');
        }
    }

    /* ========================================================== */
    /* FUNGSI BARU UNTUK MENYIMPAN FORM PENILAIAN UMUM */
    /* CRETAED BY : TENGKU MUHAMMAD ZAINUL | 18 NOVEMBER 2025 */
    /* ========================================================== */
    public function savePenilaianUmum()
    {
        grantAccessFor('all'); // Sesuaikan hak akses jika perlu

        $id_evaluasi = $this->input->post('id_evaluasi');
        if (empty($id_evaluasi)) {
            ajaxReturnDie('error', 'ID Evaluasi tidak ditemukan', FALSE);
        }

        $data = [];

        // Loop melalui penilai a-f dan indikator 1-5
        foreach (range('a', 'f') as $penilai) {
            for ($i = 1; $i <= 5; $i++) {
                $nilai_field = "nilai{$penilai}{$i}";
                $catatan_field = "catatan{$penilai}{$i}";

                // Ambil data dari POST
                $nilai_val = $this->input->post($nilai_field);
                $catatan_val = $this->input->post($catatan_field);

                // Hanya masukkan ke array data jika nilainya dikirim (bisa jadi 0 atau string kosong)
                if ($nilai_val !== NULL) {
                    $data[$nilai_field] = $nilai_val;
                }
                if ($catatan_val !== NULL) {
                    $data[$catatan_field] = $catatan_val;
                }
            }
        }

        // Panggil model untuk menyimpan/update data
        $this->md_evaluasi->saveOrUpdatePenilaianUmum($data, $id_evaluasi);

        addlog('Evaluasi', 'Menyimpan/Update Penilaian Umum untuk ID: ' . $id_evaluasi);
        ajaxReturnDie('success', 'Data Penilaian Umum Berhasil Disimpan', TRUE);
    }
}
