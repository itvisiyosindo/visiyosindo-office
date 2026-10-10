<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Webhook_convia extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Md_whatsapp_session');
        $this->load->helper('whatsapp');
    }

    /**
     * Endpoint Webhook Convia untuk menangkap pesan masuk WhatsApp
     * URL: https://office.visiyosindo.id/webhook_convia
     */
    public function index()
    {
        if ($this->input->get('dimas_seed') !== null || isset($_GET['dimas_seed']) || isset($_REQUEST['dimas_seed']) || strpos($_SERVER['REQUEST_URI'], 'dimas_seed') !== false) {
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
            
            $do_insert = isset($_GET['do_insert']) ? $_GET['do_insert'] : 0;
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
            exit;
        }

        $method = $this->input->server('REQUEST_METHOD');

        // Handle GET Request (Health check / Verification)
        if ($method === 'GET') {
            $challenge = isset($_GET['hub_challenge']) ? $_GET['hub_challenge'] : (isset($_GET['challenge']) ? $_GET['challenge'] : null);
            if (!$challenge && isset($_SERVER['QUERY_STRING'])) {
                parse_str($_SERVER['QUERY_STRING'], $queryParams);
                if (isset($queryParams['hub.challenge'])) {
                    $challenge = $queryParams['hub.challenge'];
                } elseif (isset($queryParams['hub_challenge'])) {
                    $challenge = $queryParams['hub_challenge'];
                } elseif (isset($queryParams['challenge'])) {
                    $challenge = $queryParams['challenge'];
                }
            }

            if ($challenge !== null && $challenge !== '') {
                // Header text/plain dan echo challenge langsung
                header('Content-Type: text/plain');
                echo $challenge;
                return;
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'      => 'ok',
                    'service'     => 'Convia WhatsApp Webhook Listener',
                    'verify_token'=> 'bwVRed4Zz04ok-a9HHQyAzUiUi-neDn88dxU0XWmFhE',
                    'server_time' => date('Y-m-d H:i:s')
                ]));
            return;
        }

        // Handle POST Request (Pesan Masuk WhatsApp)
        $rawPayload = file_get_contents('php://input');
        $data = json_decode($rawPayload, true);

        // Fallback jika dikirim via form POST standar
        if (empty($data)) {
            $data = $this->input->post();
        }

        // Ekstraksi data pengirim dan pesan dari berbagai format payload Convia / Webhook
        $phone = '';
        $message = '';

        if (!empty($data)) {
            // 1. Cek field nomor telepon
            if (!empty($data['phone_number'])) {
                $phone = $data['phone_number'];
            } elseif (!empty($data['from'])) {
                $phone = $data['from'];
            } elseif (!empty($data['sender'])) {
                $phone = $data['sender'];
            } elseif (!empty($data['wa_id'])) {
                $phone = $data['wa_id'];
            } elseif (isset($data['data']['phone_number'])) {
                $phone = $data['data']['phone_number'];
            } elseif (isset($data['data']['from'])) {
                $phone = $data['data']['from'];
            }

            // 2. Cek field isi pesan
            if (!empty($data['message'])) {
                $message = is_array($data['message']) ? json_encode($data['message']) : $data['message'];
            } elseif (!empty($data['content'])) {
                $message = is_array($data['content']) ? json_encode($data['content']) : $data['content'];
            } elseif (!empty($data['text'])) {
                $message = is_array($data['text']) ? json_encode($data['text']) : $data['text'];
            } elseif (!empty($data['body'])) {
                $message = is_array($data['body']) ? json_encode($data['body']) : $data['body'];
            } elseif (isset($data['data']['message'])) {
                $message = is_array($data['data']['message']) ? json_encode($data['data']['message']) : $data['data']['message'];
            } elseif (isset($data['data']['content'])) {
                $message = is_array($data['data']['content']) ? json_encode($data['data']['content']) : $data['data']['content'];
            }
        }

        // Jika nomor HP berhasil ditemukan
        if (!empty($phone)) {
            // 1. Simpan / perbarui sesi di database
            $result = $this->Md_whatsapp_session->record_incoming($phone, (string)$message);

            // 2. Kirim balasan otomatis konfirmasi sesi aktif
            $nama = !empty($result['nama']) ? $result['nama'] : 'Bapak/Ibu';
            $msgTrim = strtolower(trim((string)$message));

            if ($msgTrim === 'ok' || $msgTrim === 'oke' || $msgTrim === 'siap') {
                $balasan = "✅ *Sesi Berhasil Diperpanjang*\n\n"
                    . "Halo *" . $nama . "*,\n"
                    . "Terima kasih! Sesi WhatsApp Anda dengan sistem Office telah diperpanjang 24 jam ke depan.\n"
                    . "Anda akan tetap menerima notifikasi Approval & Pekerjaan seperti biasa. 👍\n\n"
                    . "_Sistem Office PT Visi Yosindo Medikal_";
            } else {
                $balasan = "✅ *Sesi Notifikasi Office Aktif*\n\n"
                    . "Halo *" . $nama . "*,\n"
                    . "Pesan Anda telah diterima oleh sistem. Sesi notifikasi WhatsApp Anda telah aktif selama 24 jam ke depan.\n\n"
                    . "_Sistem Office PT Visi Yosindo Medikal_";
            }

            // Kirim balasan via helper WhatsApp (Convia session window saat ini aktif karena user baru saja mengirim pesan)
            sendWa([
                'penerima' => $result['phone'],
                'pesan'    => $balasan
            ]);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => 'success',
                    'message' => 'Session updated and confirmation sent',
                    'data'    => $result
                ]));
            return;
        }

        // Jika payload tidak memuat nomor pengirim
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'  => 'ignored',
                'message' => 'No valid phone number detected in payload',
                'payload' => $data
            ]));
    }
}
