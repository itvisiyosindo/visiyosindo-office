<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reminder extends CI_Controller {
    
    private $nomor_cc = '082138759268'; 

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_reminder');
        $this->load->helper('whatsapp_helper'); 
    }

    public function index() {
        if(sessPenggunaId() != 92) grantAccessFor(['Administrator', 'Hrd', 'Ga']); 
        
        $data = [
            'switch' => 'kepegawaian',
            'page_name' => 'v_reminder',
            'page_title' => 'Reminder Ultah & Anniversary',
            'page_desc' => 'Monitoring H-7, H-3, dan Hari H untuk Content Creator',
            'data_reminder' => $this->md_reminder->getUpcomingReminders()
        ];
        $this->load->view('index', $data);
    }

    // ==========================================
    // LOGIKA 1: CRON JOB (TETAP UNTUK OTOMATIS H-X)
    // ==========================================
    public function execute_cron($secret_key = '') {
        // Kunci rahasia agar tidak sembarang orang menembak URL ini
        $my_key = 'vysi_medikal_2026'; 
    
        // Cek apakah dipanggil via CLI (Terminal) ATAU kuncinya benar
        if (is_cli() || $secret_key === $my_key) {
            
            $intervals = [7, 3, 0]; 
            foreach ($intervals as $day) {
                $list = $this->md_reminder->getDataForNotif($day);
                if (!empty($list)) {
                    $status_label = ($day == 0) ? "HARI INI" : "H-$day";
                    $this->_send_batch_wa($list, $status_label);
                }
            }
            echo "Cron Job Berhasil dijalankan.";
    
        } else {
            // Jika diakses orang luar tanpa kunci
            show_404();
        }
    }

    // ==========================================
    // LOGIKA 2: TOMBOL MASSAL (AJAX) - SELURUH DATA
    // ==========================================
    public function kirim_notif_massal_ajax() {
        // PERBAIKAN: Mengambil seluruh data (30 hari kedepan) sesuai tabel
        $list = $this->md_reminder->getUpcomingReminders();
        
        if (empty($list)) {
            echo json_encode(['status' => false, 'msg' => "Tidak ada data pengingat saat ini."]);
            return;
        }

        $res = $this->_send_batch_wa($list, "REKAP 30 HARI KEDEPAN");
        
        // Memastikan output JSON keluar untuk menghentikan loading di View
        echo json_encode([
            'status' => $res, 
            'msg' => $res ? "Seluruh daftar reminder berhasil dikirim ke Athala Aqsha." : "Gagal mengirim pesan. Cek koneksi API WA."
        ]);
    }

    // ==========================================
    // LOGIKA 3: TOMBOL PER BARIS
    // ==========================================
    public function kirim_single_notif_cc() {
        if(!isAdmin()){
            echo json_encode(['status' => false, 'msg' => 'Akses ditolak!']);
            return;
        }
        
        $data_row = [
            (object)[
                'nama' => $this->input->post('nama'),
                'jenis' => $this->input->post('jenis'),
                'info_tahun' => $this->input->post('info')
            ]
        ];

        $status = $this->_send_batch_wa($data_row, "PENGINGAT MANUAL");
        echo json_encode(['status' => $status, 'msg' => $status ? "Terikirim" : "Gagal"]);
    }

    // --- PRIVATE HELPER: FORMAT PESAN ---
    private function _send_batch_wa($list, $label) {
        $pesan = "*NOTIFIKASI REMINDER ULANG TAHUN DAN ANNIVERSARY KARYAWAN VISI YSODINO MEDIKAL*\n\n";
        $pesan .= "_Dear, *Athala Aqsha*_\n\n";
        $pesan .= "Berikut Data Karyawan yang akan Ulang Tahun atau Work Anniversary (" . $label . ") :\n\n";

        foreach ($list as $v) {
            $pesan .= "- *" . strtoupper($v->nama) . "* | " . $v->jenis . " " . $v->info_tahun . "\n";
        }

        $pesan .= "\n_*Pesan Ini Dikirim secara otomatis oleh sistem_";

        $dataSend = array(
            'devId'    => hostWa('1'),
            'penerima' => $this->nomor_cc,
            'pesan'    => urlencode($pesan) 
        );

        return sendWa($dataSend);
    }

    // ==========================================
    // LOGIKA 4: CRON / REMINDER LAPORAN MINGGUAN
    // ==========================================
    public function remind_laporan_mingguan($secret_key = '') {
        $my_key = 'vysi_medikal_2026';
        if (!is_cli() && $secret_key !== $my_key && !isAdmin() && !isHrd()) {
            show_404();
            return;
        }

        $this->load->model('md_laporan');

        $monday     = date('Y-m-d', strtotime('monday this week'));
        $friday     = date('Y-m-d', strtotime('friday this week'));
        $tglMulai   = date('d-m-Y', strtotime($monday));
        $tglSelesai = date('d-m-Y', strtotime($friday));

        $list = $this->md_laporan->getKaryawanBelumIsiLaporanMingguan($monday, $friday);

        if (empty($list)) {
            $msg = 'Seluruh karyawan sudah mengisi laporan mingguan.';
            if (is_cli()) {
                echo $msg . "\n";
            } else {
                echo json_encode(['status' => true, 'total' => 0, 'msg' => $msg]);
            }
            return;
        }

        $terkirim = 0;
        $gagal    = 0;
        $linkLaporan = 'https://office.visiyosindo.id/laporan/show/list/rev2';

        foreach ($list as $karyawan) {
            if (empty($karyawan->no_hp)) {
                $gagal++;
                continue;
            }

            $pesan = "*PENGINGAT LAPORAN MINGGUAN* 📝" .
                "%0A%0AHalo *" . $karyawan->nama . "*," .
                "%0A%0AIni adalah pengingat bahwa Anda *belum mengisi Laporan Mingguan* untuk minggu ini (periode " . $tglMulai . " s/d " . $tglSelesai . ")." .
                "%0A%0AMohon segera mengisi laporan mingguan Anda melalui link berikut:" .
                "%0A🔗 " . $linkLaporan .
                "%0A%0ATerima Kasih." .
                "%0A_Sistem Office PT Visi Yosindo Medikal_";

            $dataSend = [
                'devId'    => hostWa('1'),
                'penerima' => $karyawan->no_hp,
                'pesan'    => $pesan
            ];

            if (sendWa($dataSend)) {
                $terkirim++;
            } else {
                $gagal++;
            }
        }

        $msg = "Pengingat Laporan Mingguan selesai. Terkirim: $terkirim, Gagal/No HP Kosong: $gagal";
        if (is_cli()) {
            echo $msg . "\n";
        } else {
            echo json_encode(['status' => true, 'terkirim' => $terkirim, 'gagal' => $gagal, 'msg' => $msg]);
        }
    }
}