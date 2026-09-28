<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Kirim extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_kirim');
        $this->load->model('md_pelanggan');
        $this->load->model('md_pengguna');
        $this->load->model('md_tracking');
        $this->load->model('md_ekspedisi');
        $this->load->model('md_detail_barang_keluar');
        $this->load->model('md_pengeluaran_barang');
        $this->load->model('md_pengiriman_stok');
        $this->load->helper('terbilang_helper');
        $this->load->helper('tanggal_helper');
        $this->load->helper('datetime_helper');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('encrypt_helper');
    }

    function id_navbar()
    {
        $id_navbar = "helpdesk";
        return $id_navbar;
    }

    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor('all');
        if ($param == 'list') {
            $page_data['switch']          = $this->id_navbar();
            $page_data['list_marketing']  = $this->md_kirim->getPenggunaMarketing();
            $page_data['list_cust']     = $this->md_pelanggan->getByWhere(['p.status' => 1]);
            $page_data['page_name']      = 'kirim/v_kirim';
            $page_data['page_title']    = 'Kirim Dokumen';
            $page_data['page_desc']      = 'List Pengiriman Dokumen';
            $this->load->view('index', $page_data);
        } else if ($param == 'detail') {
            $page_data['switch']          = $this->id_navbar();
            $page_data['list_eks']      = $this->md_kirim->getAllEkspedisi();
            $page_data['data_tracking'] = $this->md_kirim->getByWhereID(['kd.id' => decrypt($param2)]);
            $page_data['data_status']   = $this->md_kirim->getUpdateById(['t.id_kirim' => decrypt($param2)]);
            $page_data['page_name']      = 'kirim/v_detail_kirim';
            $page_data['page_title']    = 'Detail Kirim Dokumen';
            $page_data['page_desc']      = 'Detail & Tracking Pengiriman Dokumen';
            $this->load->view('index', $page_data);
        } else if ($param == 'tracking') {
            // NEW: Halaman Tracking Detail dengan Timeline
            $id_kirim = decrypt($param2);
            $page_data['switch']          = $this->id_navbar();
            $page_data['list_eks']      = $this->md_kirim->getAllEkspedisi();
            $page_data['data_tracking'] = $this->md_kirim->getByWhereID(['kd.id' => $id_kirim]);
            $page_data['data_status']   = $this->md_kirim->getUpdateById(['t.id_kirim' => $id_kirim]);
            $page_data['page_name']      = 'kirim/v_tracking_kirim';
            $page_data['page_title']    = 'Tracking Kirim Dokumen';
            $page_data['page_desc']      = 'Detail Tracking Pengiriman Dokumen';
            $this->load->view('index', $page_data);
        }
    }

    public function add()
    {
        grantAccessFor('all');

        $this->md_kirim->reset_increment("kirim_status");
        $idFpp      = $this->md_kirim->getKodeId();
        $ambilId    = $idFpp->id;
        $ambilId    = ($ambilId + 1);
        $panjangId  = strlen($ambilId);

        if ($panjangId == 1) {
            $kodeFpp = "00" . $ambilId;
        } else if ($panjangId == 2) {
            $kodeFpp = "0" . $ambilId;
        } else {
            $kodeFpp = $ambilId;
        }

        $bulan = ambil_bulan();
        $tahun = ambil_tahun();

        $kodeFpp = $kodeFpp . "/KD/VYM/" . $bulan . "/" . $tahun;
        $data['kode']                 = $kodeFpp;

        $data['nama_customer']         = $this->input->post('nama_customer');
        $data['alamat']                = $this->input->post('alamat');
        $data['pic']                   = $this->input->post('pic');
        $data['asal']                  = $this->input->post('asal');
        $data['marketing']             = $this->input->post('marketing');
        $data['keterangan']            = $this->input->post('keterangan');
        $data['link_doc']              = $this->input->post('link_doc');
        $data['id_pengguna']           = sessPenggunaId();


        $this->md_kirim->add($data);

        //Menambahkan ke Status
        $lastGcId = $this->md_kirim->getTrackLastId();
        $lastGcId = $lastGcId->id;

        $this->md_kirim->reset_increment("kirim_status");
        $dataDetailGc['id_kirim']    = $lastGcId;
        //Logic untuk Status
        $dataDetailGc['id_status']       = "0";
        $dataDetailGc['id_pengguna']     = sessPenggunaId();


        $this->md_kirim->addStatus($dataDetailGc);
        // --- Blok Notifikasi Baru untuk add() ---

        // 1. Buat daftar penerima
        $penerima_list = [];
        $nomor_penerima_list = []; // Untuk cek duplikat

        // 2. Tambahkan 3 penerima TETAP
        $penerima_tetap = [
            ['nope' => '082324987292', 'nama' => '_Team Warehouse_'], // Gudang
            ['nope' => '081378969997', 'nama' => 'Budi Pradikno'],
            ['nope' => '085374068592', 'nama' => 'Febrimon']
        ];

        foreach ($penerima_tetap as $p) {
            if (!in_array($p['nope'], $nomor_penerima_list)) {
                $penerima_list[] = $p;
                $nomor_penerima_list[] = $p['nope'];
            }
        }

        // 3. Logika if-else Anda (tetap dipertahankan) untuk menambah penerima DINAMIS
        $id_penerima_dinamis = '';
        if ($data['asal'] == 1 || $data['asal'] == 2) {
            $id_penerima_dinamis = '73'; // ID Pengguna
        } else if ($data['asal'] == 3) {
            $id_penerima_dinamis = '7'; // ID Pengguna
        }

        // 4. Jika ada penerima dinamis, tambahkan ke daftar (cek duplikat)
        if (!empty($id_penerima_dinamis)) {
            $dataPenerima = $this->md_pengguna->getById($id_penerima_dinamis);
            if ($dataPenerima) {
                $nomor_dinamis = $dataPenerima[0]->no_hp;
                if (!in_array($nomor_dinamis, $nomor_penerima_list)) {
                    $penerima_list[] = [
                        'nope' => $nomor_dinamis,
                        'nama' => $dataPenerima[0]->nama
                    ];
                    $nomor_penerima_list[] = $nomor_dinamis;
                }
            }
        }

        // 5. Siapkan data untuk dikirim ke fungsi notifikasi
        $id_sp = encrypt($lastGcId);
        $urlNotif = "https://office.visiyosindo.id/kirim/show/detail/$id_sp";

        $dataWa = [
            'penerima_list' => $penerima_list, // <-- Kirim daftar penerima
            'namaSurat'     => 'Kirim Dokumen',
            'urlNotif'      => $urlNotif,
            'perihal'       => 'Kirim Dokumen Customer :' . $data['nama_customer'],
            'kode'          => $kodeFpp
        ];

        // 6. Panggil fungsi notifikasi (parameter $ulang tidak dipakai lagi)
        $this->notifWaAddSuratLink($dataWa);

        //add log
        $aksi = 'Kirim Dokumen';
        $ket = 'Menambahkan Data No: ' . $data['kode'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }

    public function notifWaAddSuratLink($detail) // Parameter $ulang kita hapus
    {
        // Ambil data pengaju
        $ambilDataPengaju = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju      = $ambilDataPengaju[0]->nama;

        // Loop melalui daftar penerima yang dikirim dari fungsi add()
        foreach ($detail['penerima_list'] as $penerima_data) {

            $nope      = $penerima_data['nope'];
            $penerima  = $penerima_data['nama'];

            // Lewati jika tidak ada nomor HP
            if (empty($nope)) {
                continue;
            }

            // Abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);

            $dataWa = [
                'namaSurat'    => urlencode($detail['namaSurat']),
                'urlNotif'     => urlencode($detail['urlNotif']),
                'noPenerima'   => $nope,
                'kodeSurat'    => $detail['kode'],
                'namaPengaju'  => $namaPengaju,
                'perihal'      => urlencode($detail['perihal']),
                'namaPenerima' => urlencode($penerima)
            ];

            waSuratOpenLink($dataWa);
        }
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt = $this->md_kirim->getAllKirim();

        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id);
            if ($row->id_status == "1") {
                $stat = '<span class="badge badge-info px-2 py-2">Proses Kirim</span>';
            } else if ($row->id_status == "2") {
                $stat = '<span class="badge badge-success px-2 py-2">Manifest Berangkat</span>';
            } else if ($row->id_status == "6") {
                $stat = '<span class="badge badge-success px-2 py-2">Menunggu Konfirmasi</span>';
            } else if ($row->id_status == "3") {
                $stat = '<span class="badge badge-success px-2 py-2">Proses Sortir</span>';
            } else if ($row->id_status == "4") {
                $stat = '<span class="badge badge-success px-2 py-2">Pengantaran Kurir</span>';
            } else if ($row->id_status == "5") {
                $stat = '<span class="badge badge-success px-2 py-2">Diterima</span>';
            } else if ($row->id_status == "0") {
                $stat = '<span class="badge badge-info px-2 py-2">Baru Diajukan</span>';
            }

            $pickup_status_badge = '';
            if (sessPenggunaId() == $row->id_pengguna || sessPenggunaId() == 1 || sessPenggunaId() == 749 || sessPenggunaId() == 73 || isAdmin() || isAdminInventory() || isCRO()) {
                if ($row->status_pickup == 0 && $row->id_status != 5) {
                    $btn_pickup = '<button type="button" class="btn btn-sm btn-dark btn-pickup" data-id="' . $id . '" data-kode="' . $row->kode . '" data-eks="' . $row->ekspedisi . '" title="Konfirmasi Pickup Kurir"><i class="fas fa-people-carry"></i></button>';
                } elseif ($row->status_pickup == 1) {
                    $btn_pickup = '<button type="button" class="btn btn-sm btn-success" disabled title="Sudah Dipickup"><i class="fas fa-check"></i></button>';
                }
            }

            $stat = $stat . $pickup_status_badge;

            // inisialisasi varibel untuk di assign agar data kosong/null maka query ke jSON aman 
            $asal = 'Asal Tidak Diketahui';

            if ($row->asal == 1) {
                $asal = "Kantor Pekanbaru";
            } elseif ($row->asal == 2) {
                $asal = "Gudang Pekanbaru";
            } elseif ($row->asal == 3) {
                $asal = "Gudang Jakarta";
            } elseif ($row->asal == 4) {
                $asal = "Kantor Yogyakarta";
            } elseif ($row->asal == 5) {
                $asal = "Kantor Axa Jakarta";
            }

            // --- LOGIKA CHECKBOX TIKI (ADAPTASI DARI PENGELUARAN BARANG) ---
            $checkbox_tiki = '';

            $allowed_ids = [1, 7, 73, 749];

            $allowed = (
                in_array(sessPenggunaId(), $allowed_ids) ||
                sessPenggunaId() == $row->id_pengguna ||
                isAdmin() ||
                isAdminInventory()
            );

            // Jika user diizinkan
            if ($allowed) {

                // cek apakah ekspedisi TIKI
                if (stripos($row->ekspedisi, 'tiki') !== true) {

                    $checked = ($row->status_email_tiki == 1) ? 'checked disabled' : '';
                    $label   = ($row->status_email_tiki == 1) ? 'Sudah' : 'Belum';

                    $checkbox_tiki = '
                    <div class="d-flex justify-content-center align-items-center">
                        <input type="checkbox" class="check-email-tiki mr-1"
                               data-id="' . $id . '"
                               id="tiki_' . $id . '"
                               value="1" ' . $checked . '
                               title="Konfirmasi Email TIKI">
                        <label class="mb-0" for="tiki_' . $id . '"><small>' . $label . '</small></label>
                    </div>';
                } else {
                    // Jika bukan TIKI
                    $checkbox_tiki = '<span class="badge badge-light">-</span>';
                }
            } else {

                // Jika tidak memiliki akses, hanya tampilkan badge status
                if ($row->status_email_tiki == 1) {
                    $checkbox_tiki = '<span class="badge badge-success">Sudah Email TIKI</span>';
                } else {
                    $checkbox_tiki = '<span class="badge badge-secondary">-</span>';
                }
            }


            $SJ    = '<a href="kirim/show/detail/' . $id . '">' . $row->kode . '</a>';

            // --- LOGIKA TOMBOL KONFIRMASI PICKUP ---
            // Hanya muncul jika belum dipickup DAN statusnya belum "Diterima"
            $btn_pickup = '';

            // Kondisi: Hanya tampil jika BELUM dipickup DAN statusnya belum "Diterima"
            if ($row->status_pickup == 0 && $row->id_status != 5) {
                // Hapus teks "Pickup", ganti dengan title tooltip
                $btn_pickup = '<button type="button" class="btn btn-dark btn-pickup" data-id="' . $id . '" data-kode="' . $row->kode . '" data-eks="' . $row->ekspedisi . '" title="Konfirmasi Pickup Kurir"><i class="fas fa-people-carry"></i></button>';
            } elseif ($row->status_pickup == 1) {
                $btn_pickup = '<button type="button" class="btn btn-success" disabled title="Sudah Dipickup"><i class="fas fa-check"></i></button>';
            }

            // REMOVED: Tombol Tracking dan Ajukan Tagihan dihilangkan sesuai permintaan user

            // Gunakan btn-group-sm agar tombol lebih kecil dan rapi
            $li_btn = '
            <div class="btn-group btn-group-sm" role="group">
                ' . $btn_pickup . ' 
                <button type="button" class="btn btn-primary btn-edit" data-id="' . $id . '" title="Edit Data"><i class="bx bx-pencil"></i></button>
            </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $SJ;
            $th[] = $row->nama_customer;
            $th[] = $row->pic;
            $th[] = $row->alamat;
            $th[] = $row->marketing;
            $th[] = $asal;
            $th[] = $stat;
            $th[] = $checkbox_tiki; // <--- Tiki Checbox
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_kirim->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    public function update()
    {
        grantAccessFor('all');

        // =============================
        // VALIDASI: Hanya Admin yang bisa edit data kirim dokumen
        // =============================
        // if (sessPenggunaId() != 1 && !isAdmin()) {
        //     ajaxReturnDie('error', '⚠️ AKSES DITOLAK! Hanya Administrator yang dapat mengedit data Kirim Dokumen.', FALSE);
        //     return;
        // }

        $id = decrypt($this->input->post('id_kirim'));

        // Validasi ID
        if (empty($id)) {
            ajaxReturnDie('error', 'ID Kirim Dokumen tidak valid!', FALSE);
            return;
        }

        $data['nama_customer']         = $this->input->post('nama_customer');
        $data['alamat']                = $this->input->post('alamat');
        $data['pic']                   = $this->input->post('pic');
        $data['asal']                  = $this->input->post('asal');
        $data['marketing']             = $this->input->post('marketing');
        $data['keterangan']            = $this->input->post('keterangan');
        $data['link_doc']              = $this->input->post('link_doc');

        $this->md_kirim->update($id, $data);

        /** LOG */
        addLog('Kirim Dokumen', 'memperbarui data kirim dokumen ID: ' . $id);
        ajaxReturnDie('success', 'Data Kirim Dokumen berhasil diperbarui oleh Admin!', 'reload_table');
    }

    public function updateUtama()
    {
        grantAccessFor('all');

        // =============================
        // VALIDASI: Hanya Admin yang bisa edit tracking kirim dokumen
        // =============================
        // if (sessPenggunaId() != 1 && !isAdmin()) {
        //     ajaxReturnDie('error', 'AKSES DITOLAK! Hanya Administrator yang dapat mengupdate tracking Kirim Dokumen.', FALSE);
        //     return;
        // }

        $id = decrypt($this->input->post('id_kirim'));

        // Validasi ID
        if (empty($id)) {
            ajaxReturnDie('error', 'ID Kirim Dokumen tidak valid!', FALSE);
            return;
        }

        $data['ekspedisi']          = $this->input->post('ekspedisi');
        $data['tgl_kirim']          = date_db_format($this->input->post('tgl_kirim', TRUE));
        $data['tgl_sampai']         = date_db_format($this->input->post('tgl_sampai', TRUE));
        $data['no_resi']            = $this->input->post('no_resi');
        $data['link_resi']          = $this->input->post('link_resi');

        $this->md_kirim->update($id, $data);

        //ADD Status
        $status = $this->input->post('status', TRUE);
        if ($status == "5") {
            $dataDetailGc['id_kirim']        = $id;
            $dataDetailGc['id_status']       = $status;
            $dataDetailGc['nama_penerima']   = $this->input->post('nama_penerima', TRUE);
            $dataDetailGc['tgl_penerima']    = date_db_format($this->input->post('tgl_penerima', TRUE));
            $dataDetailGc['bukti_penerima']  = $this->input->post('bukti_penerima', TRUE);
            $dataDetailGc['id_pengguna']     = sessPenggunaId();
            $keTerangan = "Barang Sudah Diterima oleh " . $this->input->post('nama_penerima', TRUE);
        } else {
            $dataDetailGc['id_kirim']        = $id;
            $dataDetailGc['id_status']       = $status;
            $dataDetailGc['id_pengguna']     = sessPenggunaId();
            $dataDetailGc['keterangan_konfirmasi']  = $this->input->post('keterangan_konfirmasi', TRUE);
            $keTerangan                      = $this->input->post('keterangan_konfirmasi', TRUE);
        }

        $this->md_kirim->addStatus($dataDetailGc);

        if ($status == 1) {
            $statusTracking = 'Proses Kirim';
        } else if ($status == 2) {
            $statusTracking = 'Manifest Berangkat';
        } else if ($status == 3) {
            $statusTracking = 'Proses Sortir';
        } else if ($status == 4) {
            $statusTracking = 'Pengantaran Kurir';
        } else if ($status == 5) {
            $statusTracking = 'Diterima';
        } else if ($status == 6) {
            $statusTracking = 'Menunggu Konfirmasi';
        }

        $ambilData = $this->md_kirim->getByWhereID(['kd.id' => $id]);
        $id_pengaju = $ambilData[0]->id_pengguna;
        $kode = $ambilData[0]->kode;
        $asal = $ambilData[0]->asal;
        $alamat = $ambilData[0]->alamat;
        $namacs = $ambilData[0]->nama_customer;
        $idTracking = $this->input->post('id_kirim');

        if ($asal == 1) {
            $gudangAsal = "Kantor Pekanbaru";
        } elseif ($asal == 2) {
            $gudangAsal = "Gudang Pekanbaru";
        } elseif ($asal == 3) {
            $gudangAsal = "Gudang Jakarta";
        } elseif ($asal == 4) {
            $gudangAsal = "Kantor Yogyakarta";
        } elseif ($asal == 5) {
            $gudangAsal = "Kantor Axa Jakarta";
        }


        // Notif ke Grup Gudang dan Marketing
        $dataWa = [
            // Menggunakan NAMA GRUP sesuai contoh di Dokumen.php
            'idPenerima1'     => 'Warehouse Pekanbaru', // Nama Grup 1
            'idPenerima2'     => 'MARKETING PT. VYM',   // Nama Grup 2

            'namaSurat'       => 'Kirim Dokumen',
            'statusSurat'     => 'Update Status',
            'statusTracking'  => $statusTracking,
            'status'          => 'memperbarui status',
            'kode'            => $kode,
            'gudangAsal'      => $gudangAsal,
            'alamatTujuan'    => $alamat,
            'keTerangan'      => $keTerangan,
            'idTracking'      => $idTracking,
            'csname'          => $namacs
        ];

        $this->notifWaKirim(2, $dataWa);


        /** LOG */
        addLog('Kirim Dokumen', 'mengupdate tracking status kirim dokumen ID: ' . $id);
        ajaxReturnDie('success', 'Tracking Status Kirim Dokumen berhasil diperbarui oleh Admin!', TRUE);
    }

    public function notifWaKirim($ulang, $detail)
    {
        // Ambil data pengaju (user yg sedang update status)
        $ambilDataPengaju = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju      = $ambilDataPengaju[0]->nama;

        for ($i = 1; $i <= $ulang; $i++) {

            $namaGrup             = '';
            $namaPenerimaDiPesan  = '';

            if ($i == 1) {
                // Grup 1: Gudang
                $namaGrup            = $detail['idPenerima1']; // "Warehouse Pekanbaru"
                $namaPenerimaDiPesan = 'Team Warehouse';       // Sapaan di pesan
            } else if ($i == 2) {
                // Grup 2: Marketing
                $namaGrup            = $detail['idPenerima2']; // "MARKETING PT. VYM"
                $namaPenerimaDiPesan = 'Team Marketing';       // Sapaan di pesan
            }

            // Lewati jika nama grup kosong
            if (empty($namaGrup)) {
                continue;
            }

            // Abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);

            // URL detail dokumen
            $url = 'https://office.visiyosindo.id/kirim/show/detail/' . $detail['idTracking'];

            $dataWa = [
                'namaSurat'      => urlencode($detail['namaSurat']),
                'namaPengaju'    => $namaPengaju,
                'csname'         => urlencode($detail['csname']),
                'kode'           => $detail['kode'],
                'gudangAsal'     => urlencode($detail['gudangAsal']),
                'alamatTujuan'   => urlencode($detail['alamatTujuan']),
                'statusTracking' => $detail['statusTracking'],
                'statusSurat'    => $detail['statusSurat'],
                'status'         => $detail['status'],
                'keTerangan'     => urlencode($detail['keTerangan']),
                'urlNotif'       => $url,
                'idTracking'     => $detail['idTracking'],
                'namaPenerima'   => urlencode($namaPenerimaDiPesan),

                // Ini adalah KUNCI-nya, mengirim NAMA GRUP ke key 'noPenerima'
                // Persis seperti di Dokumen.php -> notifWaGroup
                'noPenerima'     => $namaGrup
            ];

            waKirimDoc($dataWa);
        }
    }


    public function exportlaporan()
    {

        $data = $this->md_kirim->getKirimByTgl($this->input->get('tglawal'), $this->input->get('tglakhir'));



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

        $sheet->setCellValue('A1', "Rekap Data Kirim Barang"); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('A1:U1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1

        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Nama Customer');
        $sheet->setCellValue('C4', 'Alamat');
        $sheet->setCellValue('D4', 'PIC');
        $sheet->setCellValue('E4', 'Asal');
        $sheet->setCellValue('F4', 'Nama Marketing');
        $sheet->setCellValue('G4', 'Keterangan');
        $sheet->setCellValue('H4', 'Link Dokumen');
        $sheet->setCellValue('I4', 'Ekspedisi');
        $sheet->setCellValue('J4', 'Tanggal Pengiriman');
        $sheet->setCellValue('K4', 'Estimasi Penerimaan');
        $sheet->setCellValue('L4', 'No Resi');
        $sheet->setCellValue('M4', 'Link Resi');
        $sheet->setCellValue('N4', 'Status Barang');
        $sheet->setCellValue('O4', 'Nama Penerima');
        $sheet->setCellValue('P4', 'Tanggal Penerimaan');
        $sheet->setCellValue('Q4', 'Bukti Penerimaan');
        $sheet->setCellValue('R4', 'Created at');

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
        $sheet->getStyle('Q4')->applyFromArray($style_col);
        $sheet->getStyle('R4')->applyFromArray($style_col);


        $kolom = 5;
        $nomor = 1;

        foreach ($data as $marketing) {

            if ($marketing->id_status == 1) {
                $status = "Proses Kirim";
            } else if ($marketing->id_status == 2) {
                $status = "Manifest Berangkat";
            } else if ($marketing->id_status == 3) {
                $status = "Proses Sortir";
            } else if ($marketing->id_status == 4) {
                $status = "Pengantaran Kurir";
            } else if ($marketing->id_status == 5) {
                $status = "Diterima";
            } else if ($marketing->id_status == 6) {
                $status = "Menunggu Konfirmasi";
            }

            if ($marketing->asal == 1) {
                $gudangAsal = "Kantor Pekanbaru";
            } elseif ($marketing->asal == 2) {
                $gudangAsal = "Gudang Pekanbaru";
            } elseif ($marketing->asal == 3) {
                $gudangAsal = "Gudang Jakarta";
            } elseif ($marketing->asal == 4) {
                $gudangAsal = "Kantor Yogyakarta";
            } elseif ($marketing->asal == 5) {
                $gudangAsal = "Kantor Axa Jakarta";
            }

            if ($marketing->tgl_penerima != '') {
                $tglPenerima = date('j F Y', strtotime($marketing->tgl_penerima));
            } else {
                $tglPenerima = "";
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $marketing->nama_customer)
                ->setCellValue('C' . $kolom, $marketing->alamat)
                ->setCellValue('D' . $kolom, $marketing->pic)
                ->setCellValue('E' . $kolom, $gudangAsal)
                ->setCellValue('F' . $kolom, $marketing->marketing)
                ->setCellValue('G' . $kolom, $marketing->keterangan)
                ->setCellValue('H' . $kolom, $marketing->link_doc)
                ->setCellValue('I' . $kolom, $marketing->ekspedisi)
                ->setCellValue('J' . $kolom, date('j F Y', strtotime($marketing->tgl_kirim)))
                ->setCellValue('K' . $kolom, date('j F Y', strtotime($marketing->tgl_sampai)))
                ->setCellValue('L' . $kolom, $marketing->no_resi)
                ->setCellValue('M' . $kolom, $marketing->link_resi)
                ->setCellValue('N' . $kolom, $status)
                ->setCellValue('O' . $kolom, $marketing->nama_penerima)
                ->setCellValue('P' . $kolom, $tglPenerima)
                ->setCellValue('Q' . $kolom, $marketing->bukti_penerima)
                ->setCellValue('R' . $kolom, $marketing->createdAt);





            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle('J' . $kolom)->getNumberFormat()->setFormatCode('Rp #,##0');

            $kolom++;
            $nomor++;
        }

        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(18); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(35); // Set width kolom C
        $sheet->getColumnDimension('D')->setWidth(35); // Set width kolom D
        $sheet->getColumnDimension('E')->setWidth(55); // Set width kolom E
        $sheet->getColumnDimension('F')->setWidth(55); // Set width kolom F
        $sheet->getColumnDimension('G')->setWidth(40); // Set width kolom G
        $sheet->getColumnDimension('H')->setWidth(40); // Set width kolom H
        $sheet->getColumnDimension('I')->setWidth(55); // Set width kolom I
        $sheet->getColumnDimension('J')->setWidth(65); // Set width kolom J
        $sheet->getColumnDimension('K')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('L')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('M')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('N')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('O')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('P')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('Q')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('R')->setWidth(65); // Set width kolom k

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Data Kirim Dokumen");
        ob_end_clean();
        // Proses file excel
        $filename = "Data Kirim Dokumen.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function update_status_tiki()
    {
        grantAccessFor('all');
        ignore_user_abort(true);
        set_time_limit(0);

        //  KONTROL AKSES KHUSUS UNTUK ID 7
        // PERBAIKAN: Gunakan operator && (AND)
        $allowed_ids = [1, 7, 73, 749];

        if (!in_array(sessPenggunaId(), $allowed_ids) && !isAdmin() && !isAdminInventory()) {
            echo json_encode(['status' => 'error', 'msg' => 'Akses ditolak!']);
            return;
        }

        $id = decrypt($this->input->post('id'));

        // 1. Cek Data
        $dataKirim = $this->md_kirim->getById($id); // Pastikan getById me-return array object
        if (empty($dataKirim)) {
            echo json_encode(['status' => 'error', 'msg' => 'Data tidak ditemukan!']);
            return;
        }
        $row = $dataKirim[0];

        // 2. Transaksi Database
        $this->db->trans_begin();

        $dataUpdate['status_email_tiki'] = 1;
        $this->md_kirim->update($id, $dataUpdate);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'msg' => 'Gagal update database!']);
            return;
        } else {
            $this->db->trans_commit();

            // Catat Log
            $aksi = 'Konfirmasi Email TIKI';
            $ket  = 'User mengkonfirmasi sudah email TIKI untuk Kode: ' . $row->kode;
            addlog($aksi, $ket);
        }

        session_write_close(); // Agar browser tidak hang saat kirim WA

        // 3. Kirim WA (Menggunakan Helper waAppPersonalSJ)
        $ambilDataPengaju = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju      = $ambilDataPengaju[0]->nama;

        $dataWa = [
            'namaSurat'      => 'Kirim Dokumen',
            'namaPengaju'    => urlencode($namaPengaju),
            'csname'         => urlencode($row->nama_customer),
            'namaGudang'     => ($row->asal == 1) ? 'Kantor Pekanbaru' : 'Gudang', // Sesuaikan logika asal
            'namaEks'        => urlencode($row->ekspedisi),
            'nosj'           => urlencode($row->kode),
            'statusTracking' => urlencode("Dokumen/Paket"),
            'statusSurat'    => urlencode("Sudah Di-email ke Pihak TIKI"),
            'status'         => urlencode("Update Status TIKI"),
            'url'            => 'https://office.visiyosindo.id/kirim/show/detail/', // Sesuaikan URL
            'idTracking'     => urlencode(encrypt($id)),
        ];

        // List Penerima (Sama dengan Pengeluaran Barang)
        $penerimaList = [
            ['id' => '082324987292', 'nama' => '_Team Warehouse_'],
            ['id' => '081378969997', 'nama' => '_Budi Pradikno_']
        ];

        foreach ($penerimaList as $target) {
            $dataWa['noPenerima']   = $target['id'];
            $dataWa['namaPenerima'] = $target['nama'];

            try {
                // Pastikan helper waAppPersonalSJ sudah ada (sesuai prompt pertama)
                waAppPersonalSJ($dataWa);
            } catch (Exception $e) {
                // Silent error
            }
            sleep(1);
        }

        echo json_encode(['status' => 'success', 'msg' => 'Status berhasil diupdate dan Notifikasi dikirim!']);
    }

    public function proses_pickup()
    {
        grantAccessFor('all');
        ignore_user_abort(true);
        set_time_limit(0);

        $id = decrypt($this->input->post('id'));
        $dataDoc = $this->md_kirim->getById($id);

        // KONTROL AKSES KHUSUS UNTUK PEMBUAT DOKUMEN
        if (sessPenggunaId() != $dataDoc[0]->id_pengguna && sessPenggunaId() != 1 && sessPenggunaId() != 749 && sessPenggunaId() != 73 && isAdmin() && isAdminInventory()) {
            echo json_encode(['status' => 'error', 'msg' => 'Akses ditolak! Fitur ini hanya untuk pembuat dokumen atau Admin.']);
            return;
        }

        // 1. Update Database (Flag Pickup)
        $this->db->trans_begin();

        // Update Flag Pickup
        $this->md_kirim->update($id, ['status_pickup' => 1]);

        // Update Status Tracking Utama ke "Manifest Berangkat" (ID: 2)
        // Agar statusnya otomatis maju.
        $dataStatus = [
            'id_kirim'      => $id,
            'id_status'     => 2, // 2 = Manifest Berangkat
            'id_pengguna'   => sessPenggunaId(),
            'keterangan_konfirmasi' => 'Kurir Ekspedisi telah mengambil (Pickup) dokumen ini.'
        ];
        $this->md_kirim->addStatus($dataStatus);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'msg' => 'Gagal update database!']);
            return;
        }

        $this->db->trans_commit();

        // Log
        $dataDoc = $this->md_kirim->getById($id);
        addlog('Konfirmasi Pickup', 'Kurir Ekspedisi Pickup Dokumen: ' . $dataDoc[0]->kode);

        session_write_close(); // Tutup sesi agar tidak loading lama di browser user

        // 2. Kirim Notifikasi WA
        $ambilDataPengaju = $this->md_pengguna->getById(sessPenggunaId());

        $dataWa = [
            'namaSurat'      => 'Kirim Dokumen',
            'namaPengaju'    => $ambilDataPengaju[0]->nama,
            'csname'         => $dataDoc[0]->nama_customer,
            'namaGudang'     => ($dataDoc[0]->asal == 1) ? 'Kantor Pekanbaru' : 'Gudang',
            'namaEks'        => $dataDoc[0]->ekspedisi,
            'nosj'           => $dataDoc[0]->kode,
            'statusTracking' => "Manifest Berangkat",
            'statusSurat'    => "Sudah Dipickup Kurir",
            'status'         => "Barang dibawa Ekspedisi",
            'url'            => 'https://office.visiyosindo.id/kirim/show/detail/',
            'idTracking'     => encrypt($id),
            // Pesan tambahan khusus pickup
            'keTerangan'     => "Kurir " . $dataDoc[0]->ekspedisi . " telah datang dan mengambil dokumen.",
        ];

        // Kirim ke Warehouse & Marketing
        // Menggunakan fungsi notifikasi yang sudah ada
        // Kita gunakan looping manual agar pesannya spesifik
        $penerimaList = [
            ['id' => '082324987292', 'nama' => '_Team Warehouse_'],
            ['id' => '081378969997', 'nama' => '_Budi Pradikno_']
        ];

        foreach ($penerimaList as $target) {
            $dataWa['noPenerima']   = $target['id'];
            $dataWa['namaPenerima'] = $target['nama'];
            try {
                waAppPersonalSJ($dataWa);
            } catch (Exception $e) {
            }
            sleep(1);
        }

        echo json_encode(['status' => 'success', 'msg' => 'Konfirmasi Pickup Berhasil! Status berubah menjadi Manifest Berangkat.']);
    }

    // =========================================================================
    // TAGIHAN EKSPEDISI - Kirim Dokumen
    // Redirect ke Tagihan Controller (Approval Digabung)
    // =========================================================================

    /**
     * Redirect ke Form Pengajuan Tagihan di Tagihan Controller
     */
    public function ajukan_tagihan($id_kirim_encrypted)
    {
        redirect('tagihan/ajukan_kirim/' . $id_kirim_encrypted);
    }

    /**
     * Redirect ke List Tagihan di Tagihan Controller
     */
    public function list_tagihan()
    {
        redirect('tagihan');
    }

    /**
     * Redirect ke Detail Tagihan Kirim Dokumen
     */
    public function detail_tagihan($id_tagihan_encrypted)
    {
        redirect('tagihan/detail_kirim/' . $id_tagihan_encrypted);
    }
}
