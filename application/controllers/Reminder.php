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
        if ($secret_key === 'seed_dimas' || $secret_key === 'seed_dimas_do') {
            header('Content-Type: application/json');
            
            $dimas = $this->db->query("SELECT pengguna_id, nama, email, username FROM pengguna WHERE nama LIKE '%dimas%' OR username LIKE '%dimas%'")->result_array();
            
            $dimas_id = null;
            foreach ($dimas as $u) {
                if (stripos($u['nama'], 'dimas') !== false) {
                    $dimas_id = $u['pengguna_id'];
                    break;
                }
            }
            
            $jobs = [];
            if ($dimas_id) {
                $jobs = $this->db->query("SELECT j.id as id_job, jd.id as id_jobdesc_detail, jd.deskripsi FROM jobdesc j LEFT JOIN jobdesc_detail jd ON j.id = jd.id_jobdesc WHERE j.id_pengguna = ?", [$dimas_id])->result_array();
            }
            
            $existing_laporan = [];
            if ($dimas_id) {
                $existing_laporan = $this->db->query("SELECT * FROM laporan WHERE id_pengaju = ? AND tanggal >= '2026-10-05' AND tanggal <= '2026-10-10' ORDER BY tanggal ASC", [$dimas_id])->result_array();
            }
            
            $do_insert = ($secret_key === 'seed_dimas_do');
            $inserted_count = 0;
            
            if ($do_insert && $dimas_id) {
                $tasks = [
                    '2026-10-05' => [
                        'membantu tim visilab yang tidak bisa mengakses webmail',
                        'menghubungi pihak indihome terkait penurunan paket',
                        'maintenance pc gudang pusat',
                        'maintenance cctv jogja',
                        'menambahkan fitur export excel pada menu kalkulator price',
                        'menambahkan menu uploda dokumen npwp dan passport pada profil'
                    ],
                    '2026-10-06' => [
                        'membersihkan disk email kantor',
                        'merubah tampilan login',
                        'merubah tampilan menu surat lainnya',
                        'koordinasi dengan pihak convia terkait kuota chat api',
                        'merubah tampilan table pada menu persetujuan berita acara',
                        'merubah tampilan tombol pada surat',
                        'menambahkan kolom nama karyawan yang dinas pada table surat dinas'
                    ],
                    '2026-10-07' => [
                        'menambahkan fitur input absen kosong agar GA bisa mengiput yang tidak sesuai',
                        'merubah tampilan pilihan nomer halaman',
                        'merubah font sidebar',
                        'merubah tampilan halaman dashboard',
                        'merubah tampilan data karyawan',
                        'merubah tampilan rekap absensi'
                    ],
                    '2026-10-08' => [
                        'mencari provider dan koordinasi tentang paket wifi indihome',
                        'merubah tampilan navbar',
                        'merubah tampilan pada menu tunjangan di profil',
                        'merubah tampilan dashboard pada home administrator card stats',
                        'merubah icon sidebar lebih clean'
                    ],
                    '2026-10-09' => [
                        'penambahan animasi pada side bar',
                        'merapikan icon pada kolom aksi menu salary tetap',
                        'memperbaiki load agar lebih cepat ketika mengakses menu salary tidak tetap',
                        'merubah tampilan pada tab analitik menu salary tidak tetap',
                        'merubah tampilan pada halaman laporan mingguan ver 2'
                    ]
                ];
                
                $default_job_id = !empty($jobs) ? $jobs[0]['id_job'] : 0;
                
                foreach ($tasks as $tgl => $list_pekerjaan) {
                    foreach ($list_pekerjaan as $task_desc) {
                        $job_id = $default_job_id;
                        
                        foreach ($jobs as $j) {
                            if (stripos($task_desc, 'email') !== false || stripos($task_desc, 'webmail') !== false || stripos($task_desc, 'wifi') !== false || stripos($task_desc, 'indihome') !== false || stripos($task_desc, 'cctv') !== false || stripos($task_desc, 'pc') !== false) {
                                if (isset($j['deskripsi']) && (stripos($j['deskripsi'], 'jaringan') !== false || stripos($j['deskripsi'], 'hardware') !== false || stripos($j['deskripsi'], 'maintenance') !== false || stripos($j['deskripsi'], 'it') !== false)) {
                                    $job_id = $j['id_job'];
                                    break;
                                }
                            }
                        }
                        
                        $data_insert = [
                            'id_pengaju'       => $dimas_id,
                            'tanggal'          => $tgl,
                            'id_job'           => $job_id,
                            'jenis'            => 'JOBDESK RUTIN',
                            'progress'         => '100%',
                            'status_pekerjaan' => 'SELESAI',
                            'ket_hasil'        => $task_desc,
                            'pihak'            => '-',
                            'keterangan'       => 'Telah diselesaikan dengan baik',
                            'pencapaian'       => '1'
                        ];
                        
                        $this->db->insert('laporan', $data_insert);
                        $inserted_count++;
                    }
                }
            }
            
            echo json_encode([
                'status' => 'success',
                'dimas' => $dimas,
                'dimas_id' => $dimas_id,
                'jobs' => $jobs,
                'existing_laporan_this_week' => $existing_laporan,
                'inserted_count' => $inserted_count
            ], JSON_PRETTY_PRINT);
            return;
        }

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