<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Perizinan_visilab extends CI_Controller
{
    private $cron_secret = 'vysi_medikal_2026';

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_perizinan_visilab');
        $this->load->model('md_pengguna');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('tanggal_helper');
    }

    private function id_navbar()
    {
        return 'visilab';
    }

    /**
     * Endpoint langsung Setup & Inisialisasi Database
     */
    public function pull_code($secret = '')
    {
        if ($secret !== $this->cron_secret) {
            show_404();
            return;
        }

        $cmd = 'git fetch origin main 2>&1 && git reset --hard origin/main 2>&1 && git log -1 --oneline 2>&1';
        $out = function_exists('shell_exec') ? @shell_exec($cmd) : 'shell_exec disabled';
        $opcacheReset = false;
        if (function_exists('opcache_reset')) {
            $opcacheReset = @opcache_reset();
        }
        // Force clear opcache for the modified view if possible
        $viewFiles = [
            APPPATH . 'views/pages/visilab/v_perizinan_alat.php',
            APPPATH . 'views/pages/v_bukutamu.php',
            APPPATH . 'controllers/BukuTamu.php',
            APPPATH . 'models/Md_bukutamu.php',
        ];
        foreach ($viewFiles as $vf) {
            if (function_exists('opcache_invalidate') && file_exists($vf)) {
                @opcache_invalidate($vf, true);
            }
        }
        echo json_encode([
            'status'        => true,
            'cmd'           => $cmd,
            'output'        => $out,
            'opcache_reset' => $opcacheReset,
            'view_mtime'    => file_exists($viewFile) ? date('Y-m-d H:i:s', filemtime($viewFile)) : 'missing'
        ]);
    }

    public function setup_db($secret = '')
    {
        if ($secret !== $this->cron_secret) {
            show_404();
            return;
        }

        $sql1 = "CREATE TABLE IF NOT EXISTS `visilab_perizinan_alat` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `nama_instansi` VARCHAR(255) NOT NULL,
            `pihak_ketiga` VARCHAR(255),
            `jenis_alat` VARCHAR(255) NOT NULL,
            `status_jasa` VARCHAR(100) DEFAULT 'Tidak Termasuk Perizinan',
            `status_perizinan` VARCHAR(100) DEFAULT '-',
            `progres_perizinan` TEXT,
            `marketing_id` INT,
            `marketing_nama` VARCHAR(150),
            `marketing_nohp` VARCHAR(50),
            `pic_instansi` VARCHAR(150),
            `kontak_pic` VARCHAR(50),
            `email_pic` VARCHAR(150),
            `tgl_terbit` DATE,
            `tgl_jatuh_tempo` DATE,
            `tgl_exp_luk` DATE,
            `tgl_exp_kalibrasi` DATE,
            `tgl_evaluasi_tld` DATE,
            `catatan` TEXT,
            `reminder_45_sent` TINYINT(1) DEFAULT 0,
            `last_reminder_at` DATETIME,
            `reminder_luk_sent` TINYINT(1) DEFAULT 0,
            `last_reminder_luk_at` DATETIME,
            `reminder_kalibrasi_sent` TINYINT(1) DEFAULT 0,
            `last_reminder_kalibrasi_at` DATETIME,
            `reminder_tld_sent` TINYINT(1) DEFAULT 0,
            `last_reminder_tld_at` DATETIME,
            `created_by` INT,
            `updated_by` INT,
            `created_at` DATETIME,
            `updated_at` DATETIME
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $sql2 = "CREATE TABLE IF NOT EXISTS `visilab_perizinan_history` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `id_perizinan` INT NOT NULL,
            `jenis_notif` VARCHAR(50) DEFAULT 'progres',
            `status_lama` VARCHAR(100),
            `status_baru` VARCHAR(100),
            `progres_lama` TEXT,
            `progres_baru` TEXT,
            `tgl_jatuh_tempo_lama` DATE,
            `tgl_jatuh_tempo_baru` DATE,
            `penerima_cust` VARCHAR(50),
            `penerima_mkt` VARCHAR(50),
            `keterangan` TEXT,
            `notif_marketing_sent` TINYINT(1) DEFAULT 0,
            `notif_visilab_sent` TINYINT(1) DEFAULT 0,
            `notif_customer_sent` TINYINT(1) DEFAULT 0,
            `created_by` INT,
            `created_by_nama` VARCHAR(150),
            `created_at` DATETIME
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $q1 = $this->db->query($sql1);
        $err1 = $this->db->error();

        $q2 = $this->db->query($sql2);
        $err2 = $this->db->error();

        $seedRes = false;
        if ($q1) {
            $countQ = $this->db->query("SELECT COUNT(*) as c FROM visilab_perizinan_alat");
            $rowCount = ($countQ && $countQ->num_rows() > 0) ? (int)$countQ->row()->c : 0;
            if ($rowCount == 0) {
                $this->md_perizinan_visilab->seed_initial_data();
                $seedRes = true;
            }
        }

        // Pastikan kolom file_kta di tabel pengguna ada
        $ktaColReady = false;
        if (!$this->db->field_exists('file_kta', 'pengguna')) {
            $this->db->query("ALTER TABLE `pengguna` ADD COLUMN `file_kta` VARCHAR(255) NULL AFTER `file_passport`;");
            $ktaColReady = true;
        } else {
            $ktaColReady = true;
        }
        $ktaDir = FCPATH . 'uploads/file_karyawan/kta';
        if (!is_dir($ktaDir)) {
            @mkdir($ktaDir, 0755, true);
        }

        $allData = $this->md_perizinan_visilab->get_all([]);
        $allStats = $this->md_perizinan_visilab->get_summary_stats();

        $dbInfo = $this->db->query("SELECT DATABASE() as db, USER() as usr, @@hostname as hst")->row();

        echo json_encode([
            'git_commit'       => '89a5af2-diagnostics-2',
            'connected_db'     => $dbInfo,
            'create_perizinan' => $q1,
            'err1'             => $err1,
            'create_history'   => $q2,
            'err2'             => $err2,
            'total_rows_db'    => count($allData),
            'stats'            => $allStats,
            'first_row'        => !empty($allData) ? $allData[0]->nama_instansi : null
        ]);
    }

    /**
     * Halaman Utama: Daftar Perizinan Alat Visilab
     */
    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']         = $this->id_navbar();
        $page_data['page_name']      = 'visilab/v_perizinan_alat';
        $page_data['page_title']     = 'Perizinan Alat & Kepatuhan';
        $page_data['page_desc']      = 'Monitoring & Pengingat WhatsApp: Perizinan (H-45), LUK (1 Bln), Kalibrasi (1 Bln), TLD (1 Bln)';
        $page_data['stats']          = $this->md_perizinan_visilab->get_summary_stats();
        $page_data['marketing_list'] = $this->md_perizinan_visilab->get_marketing_list();

        $this->load->view('index', $page_data);
    }

    /**
     * Endpoint DataTables JSON
     */
    public function data_json()
    {
        grantAccessFor('all');

        $filters = [
            'status_jasa'      => $this->input->get('status_jasa', TRUE),
            'status_perizinan' => $this->input->get('status_perizinan', TRUE),
            'marketing'        => $this->input->get('marketing', TRUE),
            'masa_berlaku'     => $this->input->get('masa_berlaku', TRUE),
            'search'           => $this->input->get('search', TRUE)
        ];

        $data = $this->md_perizinan_visilab->get_all($filters);

        echo json_encode([
            'status' => true,
            'data'   => $data,
            'stats'  => $this->md_perizinan_visilab->get_summary_stats()
        ]);
    }

    /**
     * Ambil data perizinan untuk modal edit / view
     */
    public function detail($id = '')
    {
        grantAccessFor('all');

        $id = (int)$id;
        $row = $this->md_perizinan_visilab->get_by_id($id);
        if (!$row) {
            echo json_encode(['status' => false, 'message' => 'Data perizinan tidak ditemukan.']);
            return;
        }

        echo json_encode(['status' => true, 'data' => $row]);
    }

    /**
     * Simpan Tambah / Edit Data Perizinan Lengkap
     */
    public function save()
    {
        grantAccessFor('all');

        $id = (int)$this->input->post('id', TRUE);
        $nama_instansi     = trim($this->input->post('nama_instansi', TRUE) ?? '');
        $pihak_ketiga      = trim($this->input->post('pihak_ketiga', TRUE) ?? '-');
        $jenis_alat        = trim($this->input->post('jenis_alat', TRUE) ?? '');
        $status_jasa       = trim($this->input->post('status_jasa', TRUE) ?? 'Tidak Termasuk Perizinan');
        $status_perizinan  = trim($this->input->post('status_perizinan', TRUE) ?? '-');
        $progres_perizinan = trim($this->input->post('progres_perizinan', TRUE) ?? '');
        $marketing_nama    = trim($this->input->post('marketing_nama', TRUE) ?? '');
        $marketing_nohp    = trim($this->input->post('marketing_nohp', TRUE) ?? '');
        $pic_instansi      = trim($this->input->post('pic_instansi', TRUE) ?? '');
        $kontak_pic        = trim($this->input->post('kontak_pic', TRUE) ?? '');
        $email_pic         = trim($this->input->post('email_pic', TRUE) ?? '');
        $catatan           = trim($this->input->post('catatan', TRUE) ?? '');
        $kirim_notif       = $this->input->post('kirim_notif', TRUE) ? true : false;

        // Tanggal-tanggal kepatuhan
        $tgl_jatuh_tempo   = $this->input->post('tgl_jatuh_tempo', TRUE);
        $tgl_exp_luk       = $this->input->post('tgl_exp_luk', TRUE);
        $tgl_exp_kalibrasi = $this->input->post('tgl_exp_kalibrasi', TRUE);
        $tgl_evaluasi_tld  = $this->input->post('tgl_evaluasi_tld', TRUE);

        if (empty($nama_instansi) || empty($jenis_alat)) {
            echo json_encode(['status' => false, 'message' => 'Nama Instansi dan Jenis Alat wajib diisi!']);
            return;
        }

        $tgl_db_izin      = !empty($tgl_jatuh_tempo) ? date('Y-m-d', strtotime($tgl_jatuh_tempo)) : null;
        $tgl_db_luk       = !empty($tgl_exp_luk) ? date('Y-m-d', strtotime($tgl_exp_luk)) : null;
        $tgl_db_kalibrasi = !empty($tgl_exp_kalibrasi) ? date('Y-m-d', strtotime($tgl_exp_kalibrasi)) : null;
        $tgl_db_tld       = !empty($tgl_evaluasi_tld) ? date('Y-m-d', strtotime($tgl_evaluasi_tld)) : null;

        if (empty($marketing_nohp) && !empty($marketing_nama)) {
            $mMap = $this->md_perizinan_visilab->get_marketing_map();
            if (isset($mMap[trim($marketing_nama)])) {
                $marketing_nohp = $mMap[trim($marketing_nama)]->no_hp;
            }
        }

        $payload = [
            'nama_instansi'     => $nama_instansi,
            'pihak_ketiga'      => $pihak_ketiga ?: '-',
            'jenis_alat'        => $jenis_alat,
            'status_jasa'       => $status_jasa,
            'status_perizinan'  => $status_perizinan ?: '-',
            'progres_perizinan' => $progres_perizinan,
            'marketing_nama'    => $marketing_nama,
            'marketing_nohp'    => $marketing_nohp,
            'pic_instansi'      => $pic_instansi,
            'kontak_pic'        => $kontak_pic,
            'email_pic'         => $email_pic,
            'tgl_jatuh_tempo'   => $tgl_db_izin,
            'tgl_exp_luk'       => $tgl_db_luk,
            'tgl_exp_kalibrasi' => $tgl_db_kalibrasi,
            'tgl_evaluasi_tld'  => $tgl_db_tld,
            'catatan'           => $catatan
        ];

        $penggunaLogin = $this->md_pengguna->getById(sessPenggunaId());
        $namaUser = !empty($penggunaLogin[0]->nama) ? $penggunaLogin[0]->nama : 'Petugas Visilab';

        if ($id > 0) {
            $existing = $this->md_perizinan_visilab->get_by_id($id);
            if (!$existing) {
                echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan!']);
                return;
            }

            if ($existing->tgl_jatuh_tempo != $tgl_db_izin) $payload['reminder_45_sent'] = 0;
            if ($existing->tgl_exp_luk != $tgl_db_luk) $payload['reminder_luk_sent'] = 0;
            if ($existing->tgl_exp_kalibrasi != $tgl_db_kalibrasi) $payload['reminder_kalibrasi_sent'] = 0;
            if ($existing->tgl_evaluasi_tld != $tgl_db_tld) $payload['reminder_tld_sent'] = 0;

            $payload['updated_by'] = sessPenggunaId();
            $this->md_perizinan_visilab->update($id, $payload);

            $progresBerubah = (trim((string)$existing->progres_perizinan) !== trim((string)$progres_perizinan));
            $statusBerubah  = (trim((string)$existing->status_perizinan) !== trim((string)$status_perizinan));

            if ($progresBerubah || $statusBerubah) {
                $this->md_perizinan_visilab->add_history([
                    'id_perizinan'        => $id,
                    'jenis_notif'         => 'progres',
                    'status_lama'         => $existing->status_perizinan,
                    'status_baru'         => $status_perizinan,
                    'progres_lama'        => $existing->progres_perizinan,
                    'progres_baru'        => $progres_perizinan,
                    'tgl_jatuh_tempo_lama'=> $existing->tgl_jatuh_tempo,
                    'tgl_jatuh_tempo_baru'=> $tgl_db_izin,
                    'keterangan'          => 'Update data perizinan',
                    'created_by'          => sessPenggunaId(),
                    'created_by_nama'     => $namaUser
                ]);

                if ($kirim_notif) {
                    $rowUpdated = $this->md_perizinan_visilab->get_by_id($id);
                    $this->_kirim_notif_update_progres($rowUpdated, $namaUser);
                }
            }

            $msg = 'Data perizinan berhasil diperbarui!';
        } else {
            $payload['created_by'] = sessPenggunaId();
            $newId = $this->md_perizinan_visilab->insert($payload);

            $this->md_perizinan_visilab->add_history([
                'id_perizinan'        => $newId,
                'jenis_notif'         => 'progres',
                'status_lama'         => '-',
                'status_baru'         => $status_perizinan,
                'progres_lama'        => '',
                'progres_baru'        => $progres_perizinan,
                'tgl_jatuh_tempo_lama'=> null,
                'tgl_jatuh_tempo_baru'=> $tgl_db_izin,
                'keterangan'          => 'Data perizinan baru dibuat',
                'created_by'          => sessPenggunaId(),
                'created_by_nama'     => $namaUser
            ]);

            if ($kirim_notif && (!empty($progres_perizinan) || $status_perizinan != '-')) {
                $rowNew = $this->md_perizinan_visilab->get_by_id($newId);
                $this->_kirim_notif_update_progres($rowNew, $namaUser);
            }

            $msg = 'Data perizinan berhasil ditambahkan!';
        }

        echo json_encode([
            'status'  => true,
            'message' => $msg,
            'stats'   => $this->md_perizinan_visilab->get_summary_stats()
        ]);
    }

    /**
     * Quick Update Progres Perizinan (Modal Cepat dari Tabel)
     */
    public function update_progres()
    {
        grantAccessFor('all');

        $id = (int)$this->input->post('id', TRUE);
        $status_perizinan  = trim($this->input->post('status_perizinan', TRUE) ?? '');
        $progres_perizinan = trim($this->input->post('progres_perizinan', TRUE) ?? '');
        $tgl_jatuh_tempo   = $this->input->post('tgl_jatuh_tempo', TRUE);
        $kirim_notif       = $this->input->post('kirim_notif', TRUE) ? true : false;
        $keterangan        = trim($this->input->post('keterangan', TRUE) ?? 'Update progres dari modal cepat');

        $existing = $this->md_perizinan_visilab->get_by_id($id);
        if (!$existing) {
            echo json_encode(['status' => false, 'message' => 'Data perizinan tidak ditemukan!']);
            return;
        }

        $tgl_db = !empty($tgl_jatuh_tempo) ? date('Y-m-d', strtotime($tgl_jatuh_tempo)) : $existing->tgl_jatuh_tempo;

        $updateData = [
            'status_perizinan'  => $status_perizinan,
            'progres_perizinan' => $progres_perizinan,
            'tgl_jatuh_tempo'   => $tgl_db,
            'updated_by'        => sessPenggunaId()
        ];

        if ($existing->tgl_jatuh_tempo != $tgl_db) {
            $updateData['reminder_45_sent'] = 0;
        }

        $this->md_perizinan_visilab->update($id, $updateData);

        $penggunaLogin = $this->md_pengguna->getById(sessPenggunaId());
        $namaUser = !empty($penggunaLogin[0]->nama) ? $penggunaLogin[0]->nama : 'Petugas Visilab';

        $this->md_perizinan_visilab->add_history([
            'id_perizinan'        => $id,
            'jenis_notif'         => 'progres',
            'status_lama'         => $existing->status_perizinan,
            'status_baru'         => $status_perizinan,
            'progres_lama'        => $existing->progres_perizinan,
            'progres_baru'        => $progres_perizinan,
            'tgl_jatuh_tempo_lama'=> $existing->tgl_jatuh_tempo,
            'tgl_jatuh_tempo_baru'=> $tgl_db,
            'keterangan'          => $keterangan,
            'created_by'          => sessPenggunaId(),
            'created_by_nama'     => $namaUser
        ]);

        $notifResult = ['marketing' => false, 'visilab_group' => false];
        if ($kirim_notif) {
            $rowUpdated = $this->md_perizinan_visilab->get_by_id($id);
            $notifResult = $this->_kirim_notif_update_progres($rowUpdated, $namaUser);
        }

        echo json_encode([
            'status'       => true,
            'message'      => 'Progres perizinan berhasil diupdate!',
            'notif_status' => $notifResult,
            'stats'        => $this->md_perizinan_visilab->get_summary_stats()
        ]);
    }

    /**
     * Preview Pesan Notifikasi Manual
     */
    public function preview_notif()
    {
        grantAccessFor('all');

        $id = (int)$this->input->get('id', TRUE);
        $jenis = trim($this->input->get('jenis', TRUE) ?? 'izin_h45');

        $row = $this->md_perizinan_visilab->get_by_id($id);
        if (!$row) {
            echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan!']);
            return;
        }

        $penggunaLogin = $this->md_pengguna->getById(sessPenggunaId());
        $namaUser = !empty($penggunaLogin[0]->nama) ? $penggunaLogin[0]->nama : 'Petugas Visilab';

        $preview = $this->_build_message_text($row, $jenis, $namaUser);

        echo json_encode([
            'status'             => true,
            'data'               => $row,
            'jenis'              => $jenis,
            'pesan_customer'     => $preview['pesan_customer'],
            'pesan_marketing'    => $preview['pesan_marketing'],
            'kontak_pic'         => $row->kontak_pic,
            'pic_instansi'       => $row->pic_instansi,
            'marketing_nama'     => $row->marketing_nama,
            'marketing_nohp'     => $row->marketing_nohp
        ]);
    }

    /**
     * Kirim Notifikasi WhatsApp Manual (Tombol Modal Pengiriman Manual)
     */
    public function kirim_notif_manual()
    {
        grantAccessFor('all');

        $id = (int)$this->input->post('id', TRUE);
        $jenis = trim($this->input->post('jenis_notif', TRUE) ?? 'izin_h45');
        $send_cust = $this->input->post('send_customer', TRUE) ? true : false;
        $send_mkt  = $this->input->post('send_marketing', TRUE) ? true : false;
        $send_visi = $this->input->post('send_visilab', TRUE) ? true : false;

        $custom_pic_hp = trim($this->input->post('kontak_pic', TRUE) ?? '');
        $custom_mkt_hp = trim($this->input->post('marketing_nohp', TRUE) ?? '');
        $custom_pesan_cust = $this->input->post('pesan_customer', TRUE);
        $custom_pesan_mkt  = $this->input->post('pesan_marketing', TRUE);

        $row = $this->md_perizinan_visilab->get_by_id($id);
        if (!$row) {
            echo json_encode(['status' => false, 'message' => 'Data perizinan tidak ditemukan!']);
            return;
        }

        // Update kontak jika diubah dari modal manual
        $kontakUpdate = [];
        if (!empty($custom_pic_hp) && $custom_pic_hp !== $row->kontak_pic) {
            $kontakUpdate['kontak_pic'] = $custom_pic_hp;
            $row->kontak_pic = $custom_pic_hp;
        }
        if (!empty($custom_mkt_hp) && $custom_mkt_hp !== $row->marketing_nohp) {
            $kontakUpdate['marketing_nohp'] = $custom_mkt_hp;
            $row->marketing_nohp = $custom_mkt_hp;
        }
        if (!empty($kontakUpdate)) {
            $this->md_perizinan_visilab->update($id, $kontakUpdate);
        }

        $penggunaLogin = $this->md_pengguna->getById(sessPenggunaId());
        $namaUser = !empty($penggunaLogin[0]->nama) ? $penggunaLogin[0]->nama : 'Petugas Visilab';

        $generated = $this->_build_message_text($row, $jenis, $namaUser);
        $pesanCust = !empty($custom_pesan_cust) ? $custom_pesan_cust : $generated['pesan_customer'];
        $pesanMkt  = !empty($custom_pesan_mkt) ? $custom_pesan_mkt : $generated['pesan_marketing'];

        $terkirimCust = false;
        $terkirimMkt  = false;
        $terkirimVisi = false;

        // 1. Kirim ke Customer
        if ($send_cust && !empty($row->kontak_pic) && !empty($pesanCust)) {
            $terkirimCust = sendWa([
                'devId'    => hostWa('1'),
                'penerima' => $row->kontak_pic,
                'pesan'    => $pesanCust
            ]);
        }

        // 2. Kirim ke Marketing
        if ($send_mkt && !empty($row->marketing_nohp) && !empty($pesanMkt)) {
            $terkirimMkt = sendWa([
                'devId'    => hostWa('1'),
                'penerima' => $row->marketing_nohp,
                'pesan'    => $pesanMkt
            ]);
        }

        // 3. Kirim ke Grup VISILAB (untuk update progres)
        if ($send_visi && !empty($pesanMkt)) {
            $terkirimVisi = sendWaGroup([
                'devId'    => hostWa('2'),
                'penerima' => 'VISILAB',
                'pesan'    => $pesanMkt
            ]);
        }

        // Tandai flag reminder terkirim
        $updateFlag = [];
        if ($jenis == 'izin_h45') {
            $updateFlag['reminder_45_sent'] = 1;
            $updateFlag['last_reminder_at'] = date('Y-m-d H:i:s');
        } else if ($jenis == 'luk_1bln') {
            $updateFlag['reminder_luk_sent'] = 1;
            $updateFlag['last_reminder_luk_at'] = date('Y-m-d H:i:s');
        } else if ($jenis == 'kalibrasi_1bln') {
            $updateFlag['reminder_kalibrasi_sent'] = 1;
            $updateFlag['last_reminder_kalibrasi_at'] = date('Y-m-d H:i:s');
        } else if ($jenis == 'tld_1bln') {
            $updateFlag['reminder_tld_sent'] = 1;
            $updateFlag['last_reminder_tld_at'] = date('Y-m-d H:i:s');
        }
        if (!empty($updateFlag)) {
            $this->md_perizinan_visilab->update($id, $updateFlag);
        }

        // Catat riwayat log
        $this->md_perizinan_visilab->add_history([
            'id_perizinan'         => $id,
            'jenis_notif'          => $jenis,
            'status_lama'          => $row->status_perizinan,
            'status_baru'          => $row->status_perizinan,
            'progres_lama'         => $row->progres_perizinan,
            'progres_baru'         => $row->progres_perizinan,
            'penerima_cust'        => $terkirimCust ? $row->kontak_pic : null,
            'penerima_mkt'         => $terkirimMkt ? $row->marketing_nohp : null,
            'keterangan'           => 'Kirim notifikasi manual: ' . $jenis,
            'notif_customer_sent'  => $terkirimCust ? 1 : 0,
            'notif_marketing_sent' => $terkirimMkt ? 1 : 0,
            'notif_visilab_sent'   => $terkirimVisi ? 1 : 0,
            'created_by'           => sessPenggunaId(),
            'created_by_nama'      => $namaUser
        ]);

        $logMsg = [];
        if ($terkirimCust) $logMsg[] = "Customer (" . $row->kontak_pic . ")";
        if ($terkirimMkt)  $logMsg[] = "Marketing (" . $row->marketing_nama . ")";
        if ($terkirimVisi) $logMsg[] = "Grup VISILAB";

        $summary = !empty($logMsg) 
            ? "Notifikasi WhatsApp berhasil dikirim ke: " . implode(', ', $logMsg)
            : "Peringatan: Pesan tidak terkirim (cek kembali nomor WhatsApp tujuan).";

        echo json_encode([
            'status'  => true,
            'message' => $summary,
            'detail'  => [
                'customer' => $terkirimCust,
                'marketing'=> $terkirimMkt,
                'visilab'  => $terkirimVisi
            ]
        ]);
    }

    /**
     * Membangun teks pesan WhatsApp sesuai jenis notifikasi
     */
    private function _build_message_text($row, $jenis, $namaUser = 'Petugas Visilab')
    {
        $instansi    = $row->nama_instansi;
        $pihakKetiga = (!empty($row->pihak_ketiga) && $row->pihak_ketiga != '-') ? $row->pihak_ketiga : '-';
        $alat        = $row->jenis_alat;
        $marketing   = !empty($row->marketing_nama) ? $row->marketing_nama : 'Tim Marketing PT Visi Yosindo Medikal';
        $mktHp       = !empty($row->marketing_nohp) ? $row->marketing_nohp : '-';
        $picNama     = !empty($row->pic_instansi) ? $row->pic_instansi : 'PIC Fasyankes';
        $picHp       = !empty($row->kontak_pic) ? $row->kontak_pic : '-';
        $linkOffice  = 'https://office.visiyosindo.id/perizinan_visilab';

        $pesanCust = '';
        $pesanMkt  = '';

        if ($jenis == 'izin_h45') {
            $tgl = !empty($row->tgl_jatuh_tempo) ? date('d-m-Y', strtotime($row->tgl_jatuh_tempo)) : '-';
            $sisa = (int)($row->sisa_hari_izin ?? 45);

            $pesanCust = "*PENGINGAT PERPANJANGAN PERIZINAN ALAT* ⚠️\n\n" .
                "Yth. Bapak/Ibu PIC *" . $instansi . "*,\n\n" .
                "Kami dari *PT Visi Yosindo Medikal (Visilab)* menginformasikan bahwa masa berlaku perizinan alat kesehatan Anda akan segera berakhir dalam waktu dekat (*H-" . $sisa . " Hari*):\n\n" .
                "🏥 *Nama Instansi :* " . $instansi . "\n" .
                "🔬 *Jenis Alat    :* " . $alat . "\n" .
                "📅 *Jatuh Tempo    :* *" . $tgl . "* (Sisa " . $sisa . " hari lagi)\n" .
                "👤 *Marketing PIC :* " . $marketing . " (" . $mktHp . ")\n\n" .
                "Mohon untuk dapat segera mempersiapkan berkas dan proses perpanjangan perizinan alat agar operasional pelayanan tetap berjalan lancar dan sesuai regulasi BAPETEN.\n\n" .
                "Apabila membutuhkan bantuan pengurusan izin, uji kesesuaian/paparan (UKES/UPAR), atau pendampingan perizinan, silakan menghubungi marketing penanggung jawab atau kantor kami.\n\n" .
                "Terima kasih atas perhatian dan kerja samanya.\n" .
                "_PT Visi Yosindo Medikal (Visilab)_";

            $pesanMkt = "*PENGINGAT PERPANJANGAN PERIZINAN ALAT (MARKETING)* ⚠️\n\n" .
                "Halo *" . $row->marketing_nama . "*,\n\n" .
                "Perizinan alat untuk instansi binaan Anda akan segera jatuh tempo dalam *H-" . $sisa . " Hari*:\n\n" .
                "🏥 *Instansi   :* " . $instansi . "\n" .
                "🤝 *Pihak Ketiga:* " . $pihakKetiga . "\n" .
                "🔬 *Jenis Alat :* " . $alat . "\n" .
                "📅 *Jatuh Tempo:* *" . $tgl . "* (Sisa " . $sisa . " hari)\n" .
                "📱 *Kontak PIC :* " . $picNama . " (" . $picHp . ")\n\n" .
                "Mohon segera berkoordinasi dengan pihak instansi untuk pengurusan perpanjangan perizinan alat tersebut.\n\n" .
                "🔗 Cek selengkapnya di: " . $linkOffice . "\n" .
                "_Sistem Office PT Visi Yosindo Medikal_";

        } else if ($jenis == 'luk_1bln') {
            $tgl = !empty($row->tgl_exp_luk) ? date('d-m-Y', strtotime($row->tgl_exp_luk)) : '-';
            $sisa = (int)($row->sisa_hari_luk ?? 30);

            $pesanCust = "*PENGINGAT MASA BERLAKU LUK (UJI KESESUAIAN)* 📋⚠️\n\n" .
                "Yth. Bapak/Ibu PIC *" . $instansi . "*,\n\n" .
                "Kami dari *Laboratorium Uji Kesesuaian PT Visi Yosindo Medikal (Visilab)* menginformasikan bahwa masa berlaku *Laporan Uji Kesesuaian (LUK)* untuk alat berikut akan berakhir dalam waktu *1 Bulan*:\n\n" .
                "🏥 *Nama Instansi :* " . $instansi . "\n" .
                "🔬 *Jenis Alat    :* " . $alat . "\n" .
                "📅 *Masa Habis LUK:* *" . $tgl . "* (Sisa " . $sisa . " hari lagi)\n" .
                "👤 *Marketing PIC :* " . $marketing . " (" . $mktHp . ")\n\n" .
                "Agar sertifikat dan legalitas operasional alat sinar-X tetap aktif memenuhi ketentuan BAPETEN, disarankan untuk segera menjadwalkan pelaksanaan *Uji Kesesuaian (UKES)* ulang sebelum masa berlaku LUK berakhir.\n\n" .
                "Untuk konfirmasi jadwal pengujian, silakan menghubungi tim Visilab atau marketing terkait.\n\n" .
                "Terima kasih.\n" .
                "_PT Visi Yosindo Medikal (Visilab)_";

            $pesanMkt = "*REMINDER LUK EXPIRED 1 BULAN (MARKETING)* 📋\n\n" .
                "Halo *" . $row->marketing_nama . "*,\n\n" .
                "Masa berlaku LUK (Uji Kesesuaian) instansi binaan Anda akan habis dalam *1 Bulan ke depan*:\n\n" .
                "🏥 *Instansi   :* " . $instansi . "\n" .
                "🔬 *Jenis Alat :* " . $alat . "\n" .
                "📅 *Tgl Exp LUK:* *" . $tgl . "* (Sisa " . $sisa . " hari)\n" .
                "📱 *Kontak PIC :* " . $picNama . " (" . $picHp . ")\n\n" .
                "Segera hubungi customer untuk penawaran dan penjadwalan Uji Kesesuaian (UKES) ulang oleh tim Visilab.\n\n" .
                "🔗 Cek di: " . $linkOffice . "\n" .
                "_Sistem Office PT Visi Yosindo Medikal_";

        } else if ($jenis == 'kalibrasi_1bln') {
            $tgl = !empty($row->tgl_exp_kalibrasi) ? date('d-m-Y', strtotime($row->tgl_exp_kalibrasi)) : '-';
            $sisa = (int)($row->sisa_hari_kalibrasi ?? 30);

            $pesanCust = "*PENGINGAT JADWAL RE-KALIBRASI ALAT* ⚙️⚠️\n\n" .
                "Yth. Bapak/Ibu PIC *" . $instansi . "*,\n\n" .
                "Kami dari *PT Visi Yosindo Medikal (Visilab)* menginformasikan bahwa sertifikat kalibrasi alat kesehatan Anda akan segera habis masa berlakunya dalam *1 Bulan*:\n\n" .
                "🏥 *Nama Instansi :* " . $instansi . "\n" .
                "🔬 *Jenis Alat    :* " . $alat . "\n" .
                "📅 *Jatuh Tempo    :* *" . $tgl . "* (Sisa " . $sisa . " hari lagi)\n" .
                "👤 *Marketing PIC :* " . $marketing . " (" . $mktHp . ")\n\n" .
                "Demi menjaga akurasi pembacaan, keselamatan radiasi, dan pemenuhan standar mutu pelayanan, mohon agar dapat menjadwalkan *Kalibrasi Ulang (Re-Kalibrasi)* sebelum masa berlaku habis.\n\n" .
                "Silakan hubungi kami untuk penjadwalan kalibrasi alat.\n\n" .
                "Terima kasih.\n" .
                "_PT Visi Yosindo Medikal (Visilab)_";

            $pesanMkt = "*REMINDER KALIBRASI ALAT 1 BULAN (MARKETING)* ⚙️\n\n" .
                "Halo *" . $row->marketing_nama . "*,\n\n" .
                "Masa berlaku kalibrasi alat instansi binaan Anda akan habis dalam *1 Bulan ke depan*:\n\n" .
                "🏥 *Instansi     :* " . $instansi . "\n" .
                "🔬 *Jenis Alat   :* " . $alat . "\n" .
                "📅 *Exp Kalibrasi:* *" . $tgl . "* (Sisa " . $sisa . " hari)\n" .
                "📱 *Kontak PIC   :* " . $picNama . " (" . $picHp . ")\n\n" .
                "Mohon koordinasikan dengan customer untuk penjadwalan kalibrasi ulang oleh teknisi/lab Visilab.\n\n" .
                "🔗 Cek di: " . $linkOffice . "\n" .
                "_Sistem Office PT Visi Yosindo Medikal_";

        } else if ($jenis == 'tld_1bln') {
            $tgl = !empty($row->tgl_evaluasi_tld) ? date('d-m-Y', strtotime($row->tgl_evaluasi_tld)) : '-';
            $sisa = (int)($row->sisa_hari_tld ?? 30);

            $pesanCust = "*PENGINGAT EVALUASI DOSIS RADIASI TLD (PERIODE TRIWULAN)* 🩻⚠️\n\n" .
                "Yth. Bapak/Ibu PIC Radiologi *" . $instansi . "*,\n\n" .
                "Kami dari *PT Visi Yosindo Medikal (Visilab)* mengingatkan bahwa batas periode pemantauan dosis radiasi *TLD Badge (Triwulan)* akan jatuh tempo dalam *1 Bulan ke depan*:\n\n" .
                "🏥 *Nama Instansi  :* " . $instansi . "\n" .
                "📅 *Jatuh Tempo    :* *" . $tgl . "* (Sisa " . $sisa . " hari lagi)\n" .
                "👤 *Marketing PIC  :* " . $marketing . " (" . $mktHp . ")\n\n" .
                "Sesuai ketentuan keselamatan radiasi, lencana TLD personel wajib dievaluasi pembacaan dosisnya setiap *3 Bulan*.\n\n" .
                "Mohon agar dapat mulai dipersiapkan:\n" .
                "1. Rekapitulasi nama pengguna lencana TLD.\n" .
                "2. Pengiriman kaset TLD periode berjalan ke laboratorium kami untuk proses pembacaan dosis radiasi.\n" .
                "3. Penyiapan kaset TLD pengganti untuk periode triwulan berikutnya.\n\n" .
                "Jika kaset pengganti belum tersedia atau memerlukan bantuan logistik, segera hubungi marketing penanggung jawab.\n\n" .
                "Terima kasih atas kerja samanya.\n" .
                "_PT Visi Yosindo Medikal (Visilab)_";

            $pesanMkt = "*REMINDER EVALUASI TLD 3 BULANAN (MARKETING)* 🩻\n\n" .
                "Halo *" . $row->marketing_nama . "*,\n\n" .
                "Jadwal evaluasi rutin TLD Badge 3 bulanan untuk instansi binaan Anda akan jatuh tempo dalam *1 Bulan ke depan*:\n\n" .
                "🏥 *Instansi   :* " . $instansi . "\n" .
                "📅 *Jatuh Tempo:* *" . $tgl . "* (Sisa " . $sisa . " hari)\n" .
                "📱 *Kontak PIC :* " . $picNama . " (" . $picHp . ")\n\n" .
                "Mohon ingatkan pihak fasyankes/klinik untuk mengumpulkan kaset TLD periode ini dan mengirimkannya untuk evaluasi pembacaan dosis.\n\n" .
                "🔗 Cek di: " . $linkOffice . "\n" .
                "_Sistem Office PT Visi Yosindo Medikal_";

        } else if ($jenis == 'progres') {
            $progres = !empty($row->progres_perizinan) ? $row->progres_perizinan : '-';
            $tgl = !empty($row->tgl_jatuh_tempo) ? date('d-m-Y', strtotime($row->tgl_jatuh_tempo)) : '-';

            $pesanMkt = "*UPDATE PROGRES PERIZINAN ALAT VISILAB* 📋\n\n" .
                "Halo Rekan Visilab & Marketing,\n\n" .
                "Terdapat pembaruan status / progres perizinan alat sebagai berikut:\n\n" .
                "🏥 *Nama Instansi :* " . $instansi . "\n" .
                "🤝 *Pihak Ketiga  :* " . $pihakKetiga . "\n" .
                "🔬 *Jenis Alat    :* " . $alat . "\n" .
                "⚙️ *Status Jasa   :* " . $row->status_jasa . "\n" .
                "📌 *Status Izin   :* " . $row->status_perizinan . "\n" .
                "📝 *Progres Izin  :*\n" . $progres . "\n" .
                "👤 *Marketing     :* " . $row->marketing_nama . "\n" .
                "📅 *Batas Evaluasi:* " . $tgl . "\n" .
                "🔄 *Diupdate Oleh :* " . $namaUser . "\n\n" .
                "Silakan periksa detailnya pada sistem Office:\n" .
                "🔗 " . $linkOffice . "\n\n" .
                "Terima kasih.\n" .
                "_Sistem Office PT Visi Yosindo Medikal_";

            $pesanCust = $pesanMkt;
        }

        return [
            'pesan_customer'  => $pesanCust,
            'pesan_marketing' => $pesanMkt
        ];
    }

    /**
     * Kirim Notifikasi Update Progres ke Marketing & Grup Visilab
     */
    private function _kirim_notif_update_progres($row, $namaUser)
    {
        $built = $this->_build_message_text($row, 'progres', $namaUser);
        $pesan = $built['pesan_marketing'];
        $statusNotif = ['marketing' => false, 'visilab_group' => false];

        $noMkt = trim((string)$row->marketing_nohp);
        if (empty($noMkt) && !empty($row->marketing_nama)) {
            $mMap = $this->md_perizinan_visilab->get_marketing_map();
            if (isset($mMap[trim($row->marketing_nama)])) {
                $noMkt = $mMap[trim($row->marketing_nama)]->no_hp;
            }
        }

        if (!empty($noMkt)) {
            $statusNotif['marketing'] = sendWa([
                'devId'    => hostWa('1'),
                'penerima' => $noMkt,
                'pesan'    => $pesan
            ]);
        }

        $statusNotif['visilab_group'] = sendWaGroup([
            'devId'    => hostWa('2'),
            'penerima' => 'VISILAB',
            'pesan'    => $pesan
        ]);

        return $statusNotif;
    }

    /**
     * Cron Job Otomatis: Memeriksa dan mengirimkan seluruh reminder
     * 1. Perizinan (H-45)
     * 2. LUK (H-30 / 1 Bulan)
     * 3. Kalibrasi (H-30 / 1 Bulan)
     * 4. TLD Triwulan (H-30 / 1 Bulan)
     */
    public function cron_reminder_all($secret = '')
    {
        if (!is_cli() && $secret !== $this->cron_secret && !isAdmin()) {
            show_404();
            return;
        }

        $sentStats = [
            'izin'      => 0,
            'luk'       => 0,
            'kalibrasi' => 0,
            'tld'       => 0
        ];

        // 1. Izin (H-45)
        $listIzin = $this->md_perizinan_visilab->get_expiring('izin', 45, true);
        foreach ($listIzin as $r) {
            $msg = $this->_build_message_text($r, 'izin_h45');
            if (!empty($r->kontak_pic)) sendWa(['devId' => hostWa('1'), 'penerima' => $r->kontak_pic, 'pesan' => $msg['pesan_customer']]);
            if (!empty($r->marketing_nohp)) sendWa(['devId' => hostWa('1'), 'penerima' => $r->marketing_nohp, 'pesan' => $msg['pesan_marketing']]);
            $this->md_perizinan_visilab->update($r->id, ['reminder_45_sent' => 1, 'last_reminder_at' => date('Y-m-d H:i:s')]);
            $sentStats['izin']++;
        }

        // 2. LUK (1 Bulan / 30 Hari)
        $listLuk = $this->md_perizinan_visilab->get_expiring('luk', 30, true);
        foreach ($listLuk as $r) {
            $msg = $this->_build_message_text($r, 'luk_1bln');
            if (!empty($r->kontak_pic)) sendWa(['devId' => hostWa('1'), 'penerima' => $r->kontak_pic, 'pesan' => $msg['pesan_customer']]);
            if (!empty($r->marketing_nohp)) sendWa(['devId' => hostWa('1'), 'penerima' => $r->marketing_nohp, 'pesan' => $msg['pesan_marketing']]);
            $this->md_perizinan_visilab->update($r->id, ['reminder_luk_sent' => 1, 'last_reminder_luk_at' => date('Y-m-d H:i:s')]);
            $sentStats['luk']++;
        }

        // 3. Kalibrasi (1 Bulan / 30 Hari)
        $listKal = $this->md_perizinan_visilab->get_expiring('kalibrasi', 30, true);
        foreach ($listKal as $r) {
            $msg = $this->_build_message_text($r, 'kalibrasi_1bln');
            if (!empty($r->kontak_pic)) sendWa(['devId' => hostWa('1'), 'penerima' => $r->kontak_pic, 'pesan' => $msg['pesan_customer']]);
            if (!empty($r->marketing_nohp)) sendWa(['devId' => hostWa('1'), 'penerima' => $r->marketing_nohp, 'pesan' => $msg['pesan_marketing']]);
            $this->md_perizinan_visilab->update($r->id, ['reminder_kalibrasi_sent' => 1, 'last_reminder_kalibrasi_at' => date('Y-m-d H:i:s')]);
            $sentStats['kalibrasi']++;
        }

        // 4. TLD (1 Bulan / 30 Hari sebelum evaluasi triwulanan)
        $listTld = $this->md_perizinan_visilab->get_expiring('tld', 30, true);
        foreach ($listTld as $r) {
            $msg = $this->_build_message_text($r, 'tld_1bln');
            if (!empty($r->kontak_pic)) sendWa(['devId' => hostWa('1'), 'penerima' => $r->kontak_pic, 'pesan' => $msg['pesan_customer']]);
            if (!empty($r->marketing_nohp)) sendWa(['devId' => hostWa('1'), 'penerima' => $r->marketing_nohp, 'pesan' => $msg['pesan_marketing']]);
            $this->md_perizinan_visilab->update($r->id, ['reminder_tld_sent' => 1, 'last_reminder_tld_at' => date('Y-m-d H:i:s')]);
            $sentStats['tld']++;
        }

        $summary = "Cron Pengingat Visilab Selesai. Terkirim Izin (H-45): {$sentStats['izin']}, LUK (1 Bln): {$sentStats['luk']}, Kalibrasi (1 Bln): {$sentStats['kalibrasi']}, TLD (1 Bln): {$sentStats['tld']}.";

        if (is_cli()) {
            echo $summary . "\n";
        } else {
            echo json_encode(['status' => true, 'stats' => $sentStats, 'message' => $summary]);
        }
    }

    /**
     * Riwayat progres perizinan
     */
    public function riwayat($id = '')
    {
        grantAccessFor('all');

        $id = (int)$id;
        $data = $this->md_perizinan_visilab->get_history($id);
        echo json_encode(['status' => true, 'data' => $data]);
    }

    /**
     * Hapus Data Perizinan
     */
    public function delete($id = '')
    {
        grantAccessFor('all');

        $id = (int)$id;
        $this->md_perizinan_visilab->delete($id);

        echo json_encode([
            'status'  => true,
            'message' => 'Data perizinan berhasil dihapus!',
            'stats'   => $this->md_perizinan_visilab->get_summary_stats()
        ]);
    }

    /**
     * Export Excel Daftar Perizinan
     */
    public function export_excel()
    {
        grantAccessFor('all');

        $filters = [
            'status_jasa'      => $this->input->get('status_jasa', TRUE),
            'status_perizinan' => $this->input->get('status_perizinan', TRUE),
            'marketing'        => $this->input->get('marketing', TRUE),
            'masa_berlaku'     => $this->input->get('masa_berlaku', TRUE),
        ];

        $data = $this->md_perizinan_visilab->get_all($filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('PERIZINAN & KEPATUHAN ALAT');

        $sheet->setCellValue('A1', 'DATA PERIZINAN & KEPATUHAN ALAT MEDIS - VISILAB');
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $headers = [
            'A3' => 'No',
            'B3' => 'Nama Instansi',
            'C3' => 'Pihak Ketiga',
            'D3' => 'Jenis Alat',
            'E3' => 'Status Jasa',
            'F3' => 'Status Perizinan',
            'G3' => 'Progres Perizinan',
            'H3' => 'Marketing',
            'I3' => 'Kontak PIC',
            'J3' => 'Exp Izin (Batas Evaluasi)',
            'K3' => 'Exp LUK (Ukes)',
            'L3' => 'Exp Kalibrasi',
            'M3' => 'Evaluasi TLD'
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        $rowNum = 4;
        $no = 1;
        foreach ($data as $item) {
            $tglIzin = (!empty($item->tgl_jatuh_tempo)) ? date('d-m-Y', strtotime($item->tgl_jatuh_tempo)) : '-';
            $tglLuk  = (!empty($item->tgl_exp_luk)) ? date('d-m-Y', strtotime($item->tgl_exp_luk)) : '-';
            $tglKal  = (!empty($item->tgl_exp_kalibrasi)) ? date('d-m-Y', strtotime($item->tgl_exp_kalibrasi)) : '-';
            $tglTld  = (!empty($item->tgl_evaluasi_tld)) ? date('d-m-Y', strtotime($item->tgl_evaluasi_tld)) : '-';

            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $item->nama_instansi);
            $sheet->setCellValue('C' . $rowNum, $item->pihak_ketiga ?: '-');
            $sheet->setCellValue('D' . $rowNum, $item->jenis_alat);
            $sheet->setCellValue('E' . $rowNum, $item->status_jasa);
            $sheet->setCellValue('F' . $rowNum, $item->status_perizinan ?: '-');
            $sheet->setCellValue('G' . $rowNum, $item->progres_perizinan ?: '-');
            $sheet->setCellValue('H' . $rowNum, $item->marketing_nama ?: '-');
            $sheet->setCellValue('I' . $rowNum, $item->kontak_pic ?: '-');
            $sheet->setCellValue('J' . $rowNum, $tglIzin);
            $sheet->setCellValue('K' . $rowNum, $tglLuk);
            $sheet->setCellValue('L' . $rowNum, $tglKal);
            $sheet->setCellValue('M' . $rowNum, $tglTld);
            $rowNum++;
        }

        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Perizinan_Alat_Visilab_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Import Excel Data Perizinan
     */
    public function import_excel()
    {
        grantAccessFor('all');

        if (empty($_FILES['file_excel']['name'])) {
            echo json_encode(['status' => false, 'message' => 'Silakan pilih file Excel terlebih dahulu!']);
            return;
        }

        $fileTmp = $_FILES['file_excel']['tmp_name'];

        try {
            $spreadsheet = IOFactory::load($fileTmp);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            $mMap = $this->md_perizinan_visilab->get_marketing_map();
            $imported = 0;
            $now = date('Y-m-d H:i:s');

            foreach ($rows as $index => $r) {
                if ($index < 3) continue;

                $instansi = trim((string)($r['B'] ?? ''));
                if (empty($instansi) || strtoupper($instansi) == 'NAMA INSTANSI') continue;

                $pihakKetiga  = trim((string)($r['C'] ?? '-'));
                $jenisAlat    = trim((string)($r['D'] ?? 'Alerio Smart 4000'));
                $statusJasa   = trim((string)($r['E'] ?? 'Tidak Termasuk Perizinan'));
                $statusIzin   = trim((string)($r['F'] ?? '-'));
                $progres      = trim((string)($r['G'] ?? ''));
                $marketing    = trim((string)($r['H'] ?? ''));
                $batasRaw     = trim((string)($r['I'] ?? ''));

                $tglDb = null;
                if (!empty($batasRaw)) {
                    $ts = strtotime($batasRaw);
                    if ($ts) {
                        $tglDb = date('Y-m-d', $ts);
                    }
                }

                $nohpMkt = '';
                $idMkt   = null;
                if (!empty($marketing) && isset($mMap[$marketing])) {
                    $idMkt   = $mMap[$marketing]->pengguna_id;
                    $nohpMkt = $mMap[$marketing]->no_hp;
                }

                $this->md_perizinan_visilab->insert([
                    'nama_instansi'     => $instansi,
                    'pihak_ketiga'      => $pihakKetiga ?: '-',
                    'jenis_alat'        => $jenisAlat,
                    'status_jasa'       => $statusJasa,
                    'status_perizinan'  => $statusIzin ?: '-',
                    'progres_perizinan' => $progres,
                    'marketing_id'      => $idMkt,
                    'marketing_nama'    => $marketing,
                    'marketing_nohp'    => $nohpMkt,
                    'tgl_jatuh_tempo'   => $tglDb,
                    'created_by'        => sessPenggunaId(),
                    'created_at'        => $now,
                    'updated_at'        => $now
                ]);

                $imported++;
            }

            echo json_encode([
                'status'  => true,
                'message' => "Berhasil mengimpor $imported data perizinan alat!",
                'stats'   => $this->md_perizinan_visilab->get_summary_stats()
            ]);
        } catch (\Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Gagal membaca file Excel: ' . $e->getMessage()]);
        }
    }
}
