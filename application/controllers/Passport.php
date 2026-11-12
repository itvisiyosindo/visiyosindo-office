<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Public Controller: Halaman Scan QR Digital Asset Passport (/passport/<token>)
 * Dapat diakses langsung oleh pihak RS/Klinik/Teknisi tanpa login karyawan dan tanpa OTP.
 */
class Passport extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Md_asset_passport');
        $this->load->helper(['url', 'form', 'whatsapp_helper']);
    }

    public function index()
    {
        $this->view('');
    }

    public function view($token = '')
    {
        $token = trim((string)$token);
        $passport = $token !== '' ? $this->Md_asset_passport->get_by_token($token) : null;

        if (!$passport || (int)$passport->is_active !== 1) {
            $this->load->view('passport_public', [
                'found'           => false,
                'passport'        => null,
                'service_history' => []
            ]);
            return;
        }

        // Catat aktivitas scan
        $this->Md_asset_passport->record_scan_log($passport->id_passport, 'scan');

        $passport->foto_url = $this->_resolve_foto_url($passport->foto_produk);
        $serviceHistory = $this->Md_asset_passport->get_service_history($passport->id_passport);

        $this->load->view('passport_public', [
            'found'           => true,
            'passport'        => $passport,
            'service_history' => $serviceHistory
        ]);
    }

    /**
     * Endpoint Publik: Submit Lapor Kerusakan (Tanpa OTP).
     * Otomatis membuat Tiket resmi + Task Teknisi (bila Teknisi PIC Default diset) + Notif WhatsApp.
     */
    public function lapor_kerusakan()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (empty($_POST) && empty($_FILES) && !empty($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            echo json_encode([
                'status'  => false,
                'message' => 'Ukuran foto/lampiran terlalu besar melebihi batas server (maks 4 MB). Silakan pilih file yang lebih kecil.'
            ]);
            return;
        }

        // Honeypot anti-bot
        $honeypot = trim((string)$this->input->post('website_url', TRUE));
        if ($honeypot !== '') {
            echo json_encode(['status' => false, 'message' => 'Permintaan tidak valid.']);
            return;
        }

        $token         = trim((string)$this->input->post('qr_token', TRUE));
        $nama_pelapor  = trim((string)$this->input->post('nama_pelapor', TRUE));
        $no_wa         = trim((string)$this->input->post('no_wa', TRUE));
        $judul_keluhan = trim((string)$this->input->post('judul_keluhan', TRUE));
        $deskripsi     = trim((string)$this->input->post('deskripsi', TRUE));
        $prioritas     = (int)$this->input->post('prioritas', TRUE) ?: 3;

        if ($token === '') {
            echo json_encode(['status' => false, 'message' => 'Token QR alat tidak valid.']);
            return;
        }

        $passport = $this->Md_asset_passport->get_by_token($token);
        if (!$passport || (int)$passport->is_active !== 1) {
            echo json_encode(['status' => false, 'message' => 'Data alat tidak ditemukan atau sudah dinonaktifkan.']);
            return;
        }

        if ($nama_pelapor === '' || $no_wa === '' || $judul_keluhan === '' || $deskripsi === '') {
            echo json_encode(['status' => false, 'message' => 'Nama pelapor, No. WhatsApp, jenis kendala, dan deskripsi wajib diisi.']);
            return;
        }

        $cleanWa = preg_replace('/[^0-9]/', '', $no_wa);
        if (strlen($cleanWa) < 9 || strlen($cleanWa) > 16) {
            echo json_encode(['status' => false, 'message' => 'Format nomor WhatsApp tidak valid.']);
            return;
        }

        $ip = isset($_SERVER['REMOTE_ADDR']) ? (string)$_SERVER['REMOTE_ADDR'] : '';
        if (!$this->Md_asset_passport->check_report_rate_limit($passport->id_passport, $ip)) {
            echo json_encode(['status' => false, 'message' => 'Terlalu banyak laporan dari perangkat ini dalam waktu singkat. Silakan coba beberapa menit lagi.']);
            return;
        }

        // Upload foto/lampiran kendala opsional
        $fotoUrl = '';
        if (!empty($_FILES['foto_kerusakan']['name'])) {
            $uploadDir = FCPATH . 'uploads/passport/reports/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }
            $this->load->library('upload');
            $config = [
                'upload_path'   => $uploadDir,
                'allowed_types' => 'jpg|jpeg|png|webp|pdf|JPG|JPEG|PNG|WEBP|PDF',
                'max_size'      => 6144,
                'encrypt_name'  => true
            ];
            $this->upload->initialize($config);
            if ($this->upload->do_upload('foto_kerusakan')) {
                $up = $this->upload->data();
                $fotoUrl = base_url('uploads/passport/reports/' . $up['file_name']);
            } else {
                echo json_encode(['status' => false, 'message' => 'Upload lampiran gagal: ' . strip_tags($this->upload->display_errors())]);
                return;
            }
        }

        $res = $this->Md_asset_passport->create_qr_damage_report($passport, [
            'nama_pelapor'  => $nama_pelapor,
            'no_wa'         => $no_wa,
            'judul_keluhan' => $judul_keluhan,
            'deskripsi'     => $deskripsi,
            'prioritas'     => $prioritas
        ], $fotoUrl);

        if (empty($res['success'])) {
            echo json_encode(['status' => false, 'message' => $res['message'] ?? 'Gagal memproses laporan kerusakan.']);
            return;
        }

        // Kirim notifikasi WhatsApp secara aman (non-blocking terhadap keberhasilan tiket)
        $this->_send_report_notifications($passport, $res, [
            'nama_pelapor'  => $nama_pelapor,
            'no_wa'         => $cleanWa,
            'judul_keluhan' => $judul_keluhan,
            'deskripsi'     => $deskripsi,
            'foto_url'      => $fotoUrl
        ]);

        echo json_encode([
            'status'     => true,
            'message'    => 'Laporan kerusakan berhasil dikirim dan tercatat dengan nomor tiket ' . $res['kode_tiket'] . '.',
            'kode_tiket' => $res['kode_tiket'],
            'kode_task'  => $res['kode_task']
        ]);
    }

    private function _send_report_notifications($passport, array $res, array $input)
    {
        try {
            $kodeTiket = $res['kode_tiket'];
            $kodeTask  = $res['kode_task'];
            $snText    = !empty($passport->serial_number) ? $passport->serial_number : '-';
            $lokasi    = !empty($passport->lokasi_ruangan) ? " ({$passport->lokasi_ruangan})" : '';

            // 1. Notif ke Teknisi PIC / Koordinator Penerima
            $picUser = $this->db->select('nama, no_hp')
                ->from('pengguna')
                ->where('pengguna_id', (int)$res['penerima_id'])
                ->get()
                ->row();

            if ($picUser && !empty($picUser->no_hp) && function_exists('sendWa')) {
                $pesanPic = "*[LAPORAN KERUSAKAN QR PASSPORT]* 🛠️\n\n"
                    . "Halo *{$picUser->nama}*,\n"
                    . "Ada laporan kerusakan alat baru dari scan QR pelanggan:\n\n"
                    . "🎫 *Kode Tiket:* {$kodeTiket}\n"
                    . ($kodeTask ? "📋 *Task Otomatis:* {$kodeTask}\n" : "⏳ *Status:* Menunggu Dispatch Teknisi\n")
                    . "🏥 *Instansi:* {$passport->nama_instansi}{$lokasi}\n"
                    . "🔬 *Produk:* {$passport->nama_produk}\n"
                    . "🔢 *No. Seri:* {$snText}\n"
                    . "👤 *Pelapor:* {$input['nama_pelapor']} ({$input['no_wa']})\n"
                    . "⚠️ *Kendala:* {$input['judul_keluhan']}\n"
                    . "📝 *Detail:* {$input['deskripsi']}\n"
                    . ($input['foto_url'] !== '' ? "📸 *Foto:* {$input['foto_url']}\n" : "")
                    . "\nSilakan cek detailnya di: https://office.visiyosindo.id/tiket";

                @sendWa([
                    'devId'    => hostWa('2'),
                    'penerima' => $picUser->no_hp,
                    'pesan'    => $pesanPic
                ]);
            }

            // 2. Notif ke Grup Teknisi Medikal PT. VYM
            if (function_exists('sendWaGroup')) {
                $pesanGrup = "*[TIKET SERVICE BARU - QR PASSPORT]* 🛠️\n\n"
                    . "🎫 *Tiket:* {$kodeTiket}\n"
                    . ($kodeTask ? "📋 *Task:* {$kodeTask} (PIC: " . ($picUser->nama ?? '-') . ")\n" : "")
                    . "🏥 *Instansi:* {$passport->nama_instansi}{$lokasi}\n"
                    . "🔬 *Alat:* {$passport->nama_produk} (SN: {$snText})\n"
                    . "⚠️ *Keluhan:* {$input['judul_keluhan']}\n"
                    . "👤 *Pelapor:* {$input['nama_pelapor']} ({$input['no_wa']})\n"
                    . "\nCek detail di https://office.visiyosindo.id/tiket";

                @sendWaGroup([
                    'devId'    => hostWa('2'),
                    'penerima' => 'TEKNISI MEDIKAL PT. VYM',
                    'pesan'    => $pesanGrup
                ]);
            }

            // 3. Konfirmasi ke Pelapor RS
            if (!empty($input['no_wa']) && function_exists('sendWa')) {
                $pesanCust = "*KONFIRMASI LAPORAN KERUSAKAN ALAT* ✅\n\n"
                    . "Yth. Bapak/Ibu *{$input['nama_pelapor']}* ({$passport->nama_instansi}),\n\n"
                    . "Laporan kendala alat Anda telah kami terima dan diteruskan ke tim teknisi PT Visi Yosindo Medikal.\n\n"
                    . "🎫 *Nomor Tiket:* {$kodeTiket}\n"
                    . "🔬 *Alat:* {$passport->nama_produk} (SN: {$snText})\n"
                    . "⚠️ *Keluhan:* {$input['judul_keluhan']}\n\n"
                    . "Tim kami akan segera menindaklanjuti laporan ini. Terima kasih atas kepercayaannya.";

                @sendWa([
                    'devId'    => hostWa('2'),
                    'penerima' => $input['no_wa'],
                    'pesan'    => $pesanCust
                ]);
            }
        } catch (\Throwable $e) {
            // Abaikan error gateway WA agar tidak mengganggu respons tiket
        }
    }

    private function _resolve_foto_url($foto)
    {
        $foto = trim((string)$foto);
        if ($foto === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $foto)) {
            return $foto;
        }
        return base_url(ltrim($foto, '/'));
    }
}
