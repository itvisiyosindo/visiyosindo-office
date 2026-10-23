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

    // ==========================================
    // LOGIKA 5: UCAPAN WEEKEND OTOMATIS (JUMAT 17:30)
    // ==========================================
    public function cron_weekend_greeting($secret_key = '')
    {
        $my_key = 'vysi_medikal_2026';
        if (!is_cli() && $secret_key !== $my_key && !isAdmin() && !isHrd()) {
            show_404();
            return;
        }

        // Cek hari Jumat (N = 5). Jika bukan Jumat dan tanpa force, jangan kirim
        $isFriday = (date('N') == 5);
        $force = $this->input->get_post('force');
        if (!$isFriday && empty($force)) {
            $msg = 'Ucapan weekend otomatis hanya dijadwalkan setiap hari Jumat (Hari ini: ' . date('l') . ').';
            if (is_cli()) {
                echo $msg . "\n";
            } else {
                echo json_encode(['status' => false, 'msg' => $msg]);
            }
            return;
        }

        // Ambil daftar karyawan yang sudah dikirim ucapan hari Jumat ini agar tidak dobel
        $today = date('Y-m-d');
        $alreadySentIds = [];
        $sentRows = $this->db->query("SELECT pengguna_id FROM tbl_weekend_greetings_log WHERE tanggal_jumat = ?", [$today])->result_array();
        foreach ($sentRows as $s) {
            $alreadySentIds[] = (int)$s['pengguna_id'];
        }

        // Ambil seluruh karyawan aktif yang memiliki nomor HP
        $employees = $this->db->query("
            SELECT pengguna_id, nama, short_name, no_hp, nohp 
            FROM pengguna 
            WHERE status = 1 
              AND is_active = 1 
              AND (
                  (no_hp IS NOT NULL AND no_hp != '') 
                  OR (nohp IS NOT NULL AND nohp != '')
              )
            ORDER BY nama ASC
        ")->result_array();

        if (empty($employees)) {
            $msg = 'Tidak ada data karyawan aktif yang ditemukan.';
            if (is_cli()) {
                echo $msg . "\n";
            } else {
                echo json_encode(['status' => false, 'msg' => $msg]);
            }
            return;
        }

        $terkirim = 0;
        $skipped  = 0;
        $skippedSess = 0;
        $gagal    = 0;
        $sentNames = [];

        // Load model chat & session
        $this->load->model('Md_whatsapp_session');
        $this->load->model('Md_whatsapp_chat');

        foreach ($employees as $karyawan) {
            $empId = (int)$karyawan['pengguna_id'];

            // Lewati jika sudah pernah dikirim di hari Jumat ini
            if (in_array($empId, $alreadySentIds) && empty($force)) {
                $skipped++;
                continue;
            }

            $phone = !empty($karyawan['no_hp']) ? $karyawan['no_hp'] : $karyawan['nohp'];

            // Pastikan nomor HP ada dan sesi 24 jam masih aktif
            if (empty($phone) || !$this->Md_whatsapp_session->is_session_active($phone)) {
                $skippedSess++;
                continue;
            }

            $friendly = $this->_get_friendly_name($karyawan['nama'], $karyawan['short_name']);

            $greeting = in_array(strtolower($friendly), ['admin', 'administrator', 'penanggung', 'staff'])
                ? "Halo Rekan Tim PT Visi Yosindo Medikal! 👋✨"
                : "Halo Kak " . $friendly . "! 👋✨";

            $pesan = $greeting . "\n\n" .
                "Selamat menyelesaikan pekerjaan minggu ini! 🌿\n" .
                "Terima kasih banyak atas dedikasi, kerja keras, dan kontribusi terbaiknya selama sepekan ini di PT Visi Yosindo Medikal.\n\n" .
                "Selamat menikmati waktu istirahat dan berkumpul bersama keluarga serta orang-orang terkasih. Semoga akhir pekan ini menyenangkan dan kembali dengan energi baru di minggu depan!\n\n" .
                "Selamat beristirahat & Happy Weekend! ☕🎉\n\n" .
                "_Sistem Office PT Visi Yosindo Medikal_";

            $dataSend = [
                'devId'      => hostWa('1'),
                'penerima'   => $phone,
                'pesan'      => urlencode($pesan),
                'bypass_24h' => false,
                'raw_chat'   => true
            ];

            if (sendWa($dataSend)) {
                $terkirim++;
                $sentNames[] = $karyawan['nama'];

                // Catat ke log khusus ucapan weekend
                $this->db->replace('tbl_weekend_greetings_log', [
                    'pengguna_id'   => $empId,
                    'nama'          => $karyawan['nama'],
                    'phone_number'  => $phone,
                    'tanggal_jumat' => $today,
                    'status'        => 'sent',
                    'created_at'    => date('Y-m-d H:i:s')
                ]);

                // Catat ke riwayat chat panel jika model tersedia
                if (isset($this->Md_whatsapp_chat)) {
                    $this->Md_whatsapp_chat->record_outgoing_message($phone, $pesan, null, null, null, 'sent');
                }
            } else {
                $gagal++;
            }
        }

        $msg = "Pengiriman ucapan weekend selesai. Terkirim: $terkirim, Skip (Sudah dikirim): $skipped, Skip (Sesi 24j Mati): $skippedSess, Gagal: $gagal.";
        if (is_cli()) {
            echo $msg . "\n";
        } else {
            echo json_encode([
                'status'    => true,
                'terkirim'  => $terkirim,
                'skipped'   => $skipped,
                'skipped_session' => $skippedSess,
                'gagal'     => $gagal,
                'msg'       => $msg,
                'sent_to'   => $sentNames
            ]);
        }
    }

    // ==========================================
    // LOGIKA 6: UCAPAN SEMANGAT SENIN OTOMATIS (SENIN 09:00)
    // ==========================================
    public function cron_monday_greeting($secret_key = '')
    {
        $my_key = 'vysi_medikal_2026';
        if (!is_cli() && $secret_key !== $my_key && !isAdmin() && !isHrd()) {
            show_404();
            return;
        }

        // Cek hari Senin (N = 1). Jika bukan Senin dan tanpa force, jangan kirim
        $isMonday = (date('N') == 1);
        $force = $this->input->get_post('force');
        if (!$isMonday && empty($force)) {
            $msg = 'Ucapan semangat Senin otomatis hanya dijadwalkan setiap hari Senin (Hari ini: ' . date('l') . ').';
            if (is_cli()) {
                echo $msg . "\n";
            } else {
                echo json_encode(['status' => false, 'msg' => $msg]);
            }
            return;
        }

        // Ambil daftar karyawan yang sudah dikirim ucapan hari Senin ini agar tidak dobel
        $today = date('Y-m-d');
        $alreadySentIds = [];
        $sentRows = $this->db->query("SELECT pengguna_id FROM tbl_monday_greetings_log WHERE tanggal_senin = ?", [$today])->result_array();
        foreach ($sentRows as $s) {
            $alreadySentIds[] = (int)$s['pengguna_id'];
        }

        // Ambil seluruh karyawan aktif yang memiliki nomor HP
        $employees = $this->db->query("
            SELECT pengguna_id, nama, short_name, no_hp, nohp 
            FROM pengguna 
            WHERE status = 1 
              AND is_active = 1 
              AND (
                  (no_hp IS NOT NULL AND no_hp != '') 
                  OR (nohp IS NOT NULL AND nohp != '')
              )
            ORDER BY nama ASC
        ")->result_array();

        if (empty($employees)) {
            $msg = 'Tidak ada data karyawan aktif yang ditemukan.';
            if (is_cli()) {
                echo $msg . "\n";
            } else {
                echo json_encode(['status' => false, 'msg' => $msg]);
            }
            return;
        }

        $terkirim = 0;
        $skipped  = 0;
        $skippedSess = 0;
        $gagal    = 0;
        $sentNames = [];

        // Load model chat & session
        $this->load->model('Md_whatsapp_session');
        $this->load->model('Md_whatsapp_chat');

        foreach ($employees as $karyawan) {
            $empId = (int)$karyawan['pengguna_id'];

            // Lewati jika sudah pernah dikirim di hari Senin ini
            if (in_array($empId, $alreadySentIds) && empty($force)) {
                $skipped++;
                continue;
            }

            $phone = !empty($karyawan['no_hp']) ? $karyawan['no_hp'] : $karyawan['nohp'];

            // Pastikan nomor HP ada dan sesi 24 jam masih aktif
            if (empty($phone) || !$this->Md_whatsapp_session->is_session_active($phone)) {
                $skippedSess++;
                continue;
            }

            $friendly = $this->_get_friendly_name($karyawan['nama'], $karyawan['short_name']);

            $greeting = in_array(strtolower($friendly), ['admin', 'administrator', 'penanggung', 'staff'])
                ? "Semangat Pagi Rekan Tim PT Visi Yosindo Medikal! ☀️💼"
                : "Semangat Pagi Kak " . $friendly . "! ☀️💼";

            $pesan = $greeting . "\n\n" .
                "Selamat menyambut hari Senin dan mengawali aktivitas di minggu yang baru! 🚀\n" .
                "Semoga pekan ini berjalan lancar, penuh energi positif, serta membawa banyak kemudahan dan pencapaian terbaik untuk kita semua di PT Visi Yosindo Medikal.\n\n" .
                "Tetap jaga kesehatan, fokus pada target, dan selamat beraktivitas! 💪✨\n\n" .
                "_Sistem Office PT Visi Yosindo Medikal_";

            $dataSend = [
                'devId'      => hostWa('1'),
                'penerima'   => $phone,
                'pesan'      => urlencode($pesan),
                'bypass_24h' => false,
                'raw_chat'   => true
            ];

            if (sendWa($dataSend)) {
                $terkirim++;
                $sentNames[] = $karyawan['nama'];

                // Catat ke log khusus ucapan hari Senin
                $this->db->replace('tbl_monday_greetings_log', [
                    'pengguna_id'   => $empId,
                    'nama'          => $karyawan['nama'],
                    'phone_number'  => $phone,
                    'tanggal_senin' => $today,
                    'status'        => 'sent',
                    'created_at'    => date('Y-m-d H:i:s')
                ]);

                // Catat ke riwayat chat panel jika model tersedia
                if (isset($this->Md_whatsapp_chat)) {
                    $this->Md_whatsapp_chat->record_outgoing_message($phone, $pesan, null, null, null, 'sent');
                }
            } else {
                $gagal++;
            }
        }

        $msg = "Pengiriman ucapan semangat Senin selesai. Terkirim: $terkirim, Skip (Sudah dikirim): $skipped, Skip (Sesi 24j Mati): $skippedSess, Gagal: $gagal.";
        if (is_cli()) {
            echo $msg . "\n";
        } else {
            echo json_encode([
                'status'          => true,
                'terkirim'        => $terkirim,
                'skipped'         => $skipped,
                'skipped_session' => $skippedSess,
                'gagal'           => $gagal,
                'msg'             => $msg,
                'sent_to'         => $sentNames
            ]);
        }
    }

    /**
     * Helper nama panggilan personal untuk sapaan
     */
    private function _get_friendly_name($nama, $short_name)
    {
        if (!empty($nama) && preg_match('/\(([^)]+)\)/', $nama, $m)) {
            $parts = preg_split('/\s+/', trim($m[1]));
            return $parts[0];
        }
        if (!empty($short_name) && preg_match('/\(([^)]+)\)/', $short_name, $m)) {
            $parts = preg_split('/\s+/', trim($m[1]));
            return $parts[0];
        }

        $titleKeywords = ['admin', 'staff', 'head', 'general', 'customer', 'finance', 'hr', 'penanggung'];
        $firstWord = strtolower(explode(' ', trim((string)$nama))[0]);
        if (in_array($firstWord, $titleKeywords) && !empty($short_name)) {
            $parts = preg_split('/\s+/', trim((string)$short_name));
            return $parts[0];
        }

        $clean = trim((string)$nama);
        $clean = preg_replace('/^(dr\.|drg\.|ir\.|drs\.|apt\.|ns\.)\s+/i', '', $clean);
        $parts = preg_split('/\s+/', $clean);
        return !empty($parts[0]) ? $parts[0] : 'Rekan';
    }
}
