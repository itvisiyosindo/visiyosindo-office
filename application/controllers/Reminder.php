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
    // LOGIKA 5: UCAPAN JUMAT / WEEKEND (MANUAL VIA TOMBOL)
    // ==========================================
    public function cron_weekend_greeting($secret_key = '')
    {
        $my_key = 'vysi_medikal_2026';
        if (!is_cli() && $secret_key !== $my_key && !isAdmin() && !isHrd()) {
            show_404();
            return;
        }

        // Nonaktifkan pengiriman otomatis dari Cron Job (hanya jalan jika tombol manual diklik -> force=1)
        $force = $this->input->get_post('force');
        if (empty($force)) {
            $msg = 'Pengiriman otomatis ucapan Jumat telah dinonaktifkan. Silakan kirim secara manual melalui tombol di panel.';
            if (is_cli()) {
                echo $msg . "\n";
            } else {
                echo json_encode(['status' => false, 'msg' => $msg]);
            }
            return;
        }

        $targetStartTs = time();

        // Bersihkan sisa antrian pending lama sebelum memulai batch manual baru
        if (function_exists('ensureWaQueueTable') && ensureWaQueueTable()) {
            @$this->db->query("DELETE FROM `tbl_wa_queue` WHERE `status` = 'pending'");
        }

        // Ambil daftar karyawan yang sudah dikirim ucapan hari Jumat ini agar tidak dobel
        $today = date('Y-m-d');
        $alreadySentIds = [];
        $sentRows = $this->db->query("SELECT pengguna_id FROM tbl_weekend_greetings_log WHERE tanggal_jumat = ?", [$today])->result_array();
        foreach ($sentRows as $s) {
            $alreadySentIds[] = (int)$s['pengguna_id'];
        }

        // Gunakan filter Karyawan Aktif resmi (sama dengan Dashboard Kepegawaian)
        $this->load->model('Md_dashboard');
        $excludedIds = method_exists($this->Md_dashboard, 'getExcludedKaryawanIds') ? $this->Md_dashboard->getExcludedKaryawanIds() : [1];
        $excludedSql = !empty($excludedIds) ? implode(',', array_map('intval', $excludedIds)) : '1';
        $todayLike   = date('Y-m-d') . '%';

        $employees = $this->db->query("
            SELECT p.pengguna_id, p.nama, p.short_name, p.no_hp, p.nohp 
            FROM pengguna p
            LEFT JOIN (
                SELECT DISTINCT pengguna_id FROM absensi WHERE data_created LIKE '{$todayLike}' AND perusahaan = 0
            ) a ON a.pengguna_id = p.pengguna_id
            WHERE p.status = 1 
              AND p.is_active = 1 
              AND p.pengguna_id != 1
              AND (p.pengguna_id NOT IN ({$excludedSql}) OR a.pengguna_id IS NOT NULL)
              AND (
                  (p.no_hp IS NOT NULL AND p.no_hp != '' AND p.no_hp != '-') 
                  OR (p.nohp IS NOT NULL AND p.nohp != '' AND p.nohp != '-')
              )
            ORDER BY p.nama ASC
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
        $seenPhones = [];

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
            $cleanPhone = preg_replace('/[^0-9]/', '', (string)$phone);
            if (substr($cleanPhone, 0, 2) === '08') {
                $normPhone = '62' . substr($cleanPhone, 1);
            } else {
                $normPhone = $cleanPhone;
            }

            // Kirim ke seluruh karyawan aktif yang memiliki nomor HP valid & belum duplikat
            if (empty($cleanPhone) || strlen($cleanPhone) < 9) {
                $gagal++;
                continue;
            }
            if (isset($seenPhones[$normPhone])) {
                continue;
            }
            $seenPhones[$normPhone] = true;

            $friendly = $this->_get_friendly_name($karyawan['nama'], $karyawan['short_name']);

            $greeting = in_array(strtolower($friendly), ['admin', 'administrator', 'penanggung', 'staff'])
                ? "Halo *Rekan Tim PT Visi Yosindo Medikal*, selamat sore! 🌤️✨"
                : "Halo *" . $friendly . "*, selamat sore! 🌤️✨";

            $pesan = $greeting . "\n\n" .
                "Terima kasih banyak sudah berjuang dan memberikan yang terbaik sepanjang minggu ini. Setiap usaha, ketelitian, dan langkah kecil yang kamu lakukan sangat berarti bagi perjalanan kita bersama di PT Visi Yosindo Medikal. 🤝💛\n\n" .
                "Apa pun tantangan yang sudah dilewati pekan ini, kamu hebat sudah menyelesaikannya sampai hari Jumat! Sekarang saatnya melepas lelah sejenak, mengistirahatkan pikiran, dan menikmati waktu hangat bersama keluarga maupun orang-orang tersayang. 🏡☕\n\n" .
                "Jaga kesehatan selalu, semoga akhir pekanmu penuh kebahagiaan dan ketenangan. Sampai jumpa kembali di hari Senin dengan semangat dan senyum yang baru! 🌿😊\n\n" .
                "*Selamat beristirahat & Happy Weekend!* 🎉✨\n" .
                "_Sistem Office PT Visi Yosindo Medikal_";

            $dataSend = [
                'devId'        => hostWa('1'),
                'penerima'     => $phone,
                'pesan'        => urlencode($pesan),
                'bypass_24h'   => true,
                'raw_chat'     => true,
                'min_start_ts' => $targetStartTs
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

        $pendingQueue = 0;
        if ($this->db->table_exists('tbl_wa_queue')) {
            $pendingQueue = (int)($this->db->query("SELECT COUNT(*) AS cnt FROM `tbl_wa_queue` WHERE `status` = 'pending'")->row()->cnt ?? 0);
        }

        $msg = "Pengiriman ucapan weekend selesai dimasukkan ke antrian (1-per-1). Total Karyawan Aktif: $terkirim, Gagal/No HP Kosong: $gagal.";
        if (is_cli()) {
            echo $msg . "\n";
        } else {
            echo json_encode([
                'status'          => true,
                'terkirim'        => $terkirim,
                'pending_queue'   => $pendingQueue,
                'skipped'         => $skipped,
                'skipped_session' => $skippedSess,
                'gagal'           => $gagal,
                'msg'             => $msg,
                'sent_to'         => $sentNames
            ]);
        }
    }

    // ==========================================
    // LOGIKA 6: UCAPAN SEMANGAT SENIN (MANUAL VIA TOMBOL)
    // ==========================================
    public function cron_monday_greeting($secret_key = '')
    {
        $my_key = 'vysi_medikal_2026';
        if (!is_cli() && $secret_key !== $my_key && !isAdmin() && !isHrd()) {
            show_404();
            return;
        }

        // Nonaktifkan pengiriman otomatis dari Cron Job (hanya jalan jika tombol manual diklik -> force=1)
        $force = $this->input->get_post('force');
        if (empty($force)) {
            $msg = 'Pengiriman otomatis ucapan Senin telah dinonaktifkan. Silakan kirim secara manual melalui tombol di panel.';
            if (is_cli()) {
                echo $msg . "\n";
            } else {
                echo json_encode(['status' => false, 'msg' => $msg]);
            }
            return;
        }

        // Bersihkan sisa antrian pending lama sebelum memulai batch manual baru
        if (function_exists('ensureWaQueueTable') && ensureWaQueueTable()) {
            @$this->db->query("DELETE FROM `tbl_wa_queue` WHERE `status` = 'pending'");
        }

        // Ambil daftar karyawan yang sudah dikirim ucapan hari Senin ini agar tidak dobel
        $today = date('Y-m-d');
        $alreadySentIds = [];
        $sentRows = $this->db->query("SELECT pengguna_id FROM tbl_monday_greetings_log WHERE tanggal_senin = ?", [$today])->result_array();
        foreach ($sentRows as $s) {
            $alreadySentIds[] = (int)$s['pengguna_id'];
        }

        // Gunakan filter Karyawan Aktif resmi (sama dengan Dashboard Kepegawaian)
        $this->load->model('Md_dashboard');
        $excludedIds = method_exists($this->Md_dashboard, 'getExcludedKaryawanIds') ? $this->Md_dashboard->getExcludedKaryawanIds() : [1];
        $excludedSql = !empty($excludedIds) ? implode(',', array_map('intval', $excludedIds)) : '1';
        $todayLike   = date('Y-m-d') . '%';

        $employees = $this->db->query("
            SELECT p.pengguna_id, p.nama, p.short_name, p.no_hp, p.nohp 
            FROM pengguna p
            LEFT JOIN (
                SELECT DISTINCT pengguna_id FROM absensi WHERE data_created LIKE '{$todayLike}' AND perusahaan = 0
            ) a ON a.pengguna_id = p.pengguna_id
            WHERE p.status = 1 
              AND p.is_active = 1 
              AND p.pengguna_id != 1
              AND (p.pengguna_id NOT IN ({$excludedSql}) OR a.pengguna_id IS NOT NULL)
              AND (
                  (p.no_hp IS NOT NULL AND p.no_hp != '' AND p.no_hp != '-') 
                  OR (p.nohp IS NOT NULL AND p.nohp != '' AND p.nohp != '-')
              )
            ORDER BY p.nama ASC
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
        $seenPhones = [];

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
            $cleanPhone = preg_replace('/[^0-9]/', '', (string)$phone);
            if (substr($cleanPhone, 0, 2) === '08') {
                $normPhone = '62' . substr($cleanPhone, 1);
            } else {
                $normPhone = $cleanPhone;
            }

            // Kirim ke seluruh karyawan aktif yang memiliki nomor HP valid & belum duplikat
            if (empty($cleanPhone) || strlen($cleanPhone) < 9) {
                $gagal++;
                continue;
            }
            if (isset($seenPhones[$normPhone])) {
                continue;
            }
            $seenPhones[$normPhone] = true;

            $friendly = $this->_get_friendly_name($karyawan['nama'], $karyawan['short_name']);

            $greeting = in_array(strtolower($friendly), ['admin', 'administrator', 'penanggung', 'staff'])
                ? "Semangat Pagi Rekan Tim PT Visi Yosindo Medikal! ☀️💼"
                : "Semangat Pagi " . $friendly . "! ☀️💼";

            $pesan = $greeting . "\n\n" .
                "Selamat menyambut hari Senin dan mengawali aktivitas di minggu yang baru! 🚀\n" .
                "Semoga pekan ini berjalan lancar, penuh energi positif, serta membawa banyak kemudahan dan pencapaian terbaik untuk kita semua di PT Visi Yosindo Medikal.\n\n" .
                "Tetap jaga kesehatan, fokus pada target, dan selamat beraktivitas! 💪✨\n\n" .
                "_Sistem Office PT Visi Yosindo Medikal_";

            $dataSend = [
                'devId'      => hostWa('1'),
                'penerima'   => $phone,
                'pesan'      => urlencode($pesan),
                'bypass_24h' => true,
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

        $pendingQueue = 0;
        if ($this->db->table_exists('tbl_wa_queue')) {
            $pendingQueue = (int)($this->db->query("SELECT COUNT(*) AS cnt FROM `tbl_wa_queue` WHERE `status` = 'pending'")->row()->cnt ?? 0);
        }

        $msg = "Pengiriman ucapan semangat Senin selesai dimasukkan ke antrian (1-per-1). Total Karyawan Aktif: $terkirim, Gagal/No HP Kosong: $gagal.";
        if (is_cli()) {
            echo $msg . "\n";
        } else {
            echo json_encode([
                'status'          => true,
                'terkirim'        => $terkirim,
                'pending_queue'   => $pendingQueue,
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
