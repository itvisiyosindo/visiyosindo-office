<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller Cron Notifikasi Laporan Absensi Harian (Senin - Jumat)
 *
 * Mengirimkan daftar karyawan yang belum absen ke grup WhatsApp perusahaan
 * melalui akun Fonnte terpisah (nomor 0895410953259) tanpa mengubah
 * konfigurasi/API notifikasi yang sudah ada.
 *
 * Jadwal Cron (Senin - Jumat):
 * - Pagi  (09:00 WIB): https://office.visiyosindo.id/cron_absensi/pagi
 * - Siang (13:10 WIB): https://office.visiyosindo.id/cron_absensi/siang
 * - Sore  (17:15 WIB): https://office.visiyosindo.id/cron_absensi/sore
 */
class Cron_absensi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->config->load('fonnte_absensi');
        $this->load->model('md_dashboard');
    }

    public function index()
    {
        $this->_jsonResponse([
            'status'    => 'ok',
            'service'   => 'Cron Notifikasi Laporan Absensi Harian',
            'sender'    => $this->config->item('fonnte_absensi_sender'),
            'group'     => $this->config->item('fonnte_absensi_group_name'),
            'endpoints' => [
                'pagi'    => base_url('cron_absensi/pagi'),
                'siang'   => base_url('cron_absensi/siang'),
                'sore'    => base_url('cron_absensi/sore'),
                'preview' => base_url('cron_absensi/preview/pagi'),
                'groups'  => base_url('cron_absensi/groups'),
            ],
        ]);
    }

    /**
     * Jadwal Pagi - 09:00 WIB (Belum Absen Masuk)
     */
    public function pagi()
    {
        $this->_handleCron('pagi');
    }

    /**
     * Jadwal Siang - 13:10 WIB (Belum Absen Istirahat)
     */
    public function siang()
    {
        $this->_handleCron('siang');
    }

    /**
     * Jadwal Sore - 17:15 WIB (Belum Absen Pulang)
     */
    public function sore()
    {
        $this->_handleCron('sore');
    }

    /**
     * Alias untuk jadwal sore / pulang
     */
    public function pulang()
    {
        $this->_handleCron('sore');
    }

    /**
     * Preview pesan tanpa mengirim ke WhatsApp
     * Contoh: /cron_absensi/preview/pagi
     */
    public function preview($sesi = 'pagi')
    {
        if (!$this->_authorizeRequest()) {
            return;
        }

        $sesi = $this->_normalizeSesi($sesi);
        if ($sesi === null) {
            $this->_jsonResponse([
                'status'  => 'error',
                'message' => 'Sesi tidak valid. Gunakan: pagi, siang, atau sore.',
            ], 400);
            return;
        }

        $today = date('Y-m-d');
        $report = $this->_buildBelumAbsenReport($sesi, $today);
        $message = $this->_formatWhatsappMessage($sesi, $today, $report['belum_absen']);

        $this->_jsonResponse([
            'status'         => 'preview',
            'sesi'           => $sesi,
            'tanggal'        => $today,
            'is_workday'     => $this->_isWorkday($today),
            'is_holiday'     => $this->_isHoliday($today),
            'total_belum'    => count($report['belum_absen']),
            'belum_absen'    => $report['belum_absen'],
            'excluded_count' => $report['excluded_count'],
            'message'        => $message,
        ]);
    }

    /**
     * Cek daftar grup WhatsApp dari akun Fonnte 0895410953259
     * Berguna untuk memverifikasi Group ID "Kepegawaian visi/vym group"
     */
    public function groups()
    {
        if (!$this->_authorizeRequest()) {
            return;
        }

        $token = (string)$this->config->item('fonnte_absensi_token');
        if ($token === '') {
            $this->_jsonResponse([
                'status'  => 'error',
                'message' => 'FONNTE_ABSENSI_TOKEN belum dikonfigurasi di .env atau application/config/fonnte_absensi.php.',
            ], 400);
            return;
        }

        // Refresh daftar grup terbaru di Fonnte terlebih dahulu
        $this->_fonnteRequest($this->config->item('fonnte_absensi_fetch_group_url'), [], $token);
        $res = $this->_fonnteRequest($this->config->item('fonnte_absensi_groups_url'), [], $token);

        if (!$res['ok']) {
            $this->_jsonResponse([
                'status'  => 'error',
                'message' => 'Gagal mengambil daftar grup dari Fonnte.',
                'detail'  => $res,
            ], 502);
            return;
        }

        $targetName = (string)$this->config->item('fonnte_absensi_group_name');
        $matched = $this->_findGroupIdByName($res['data'], $targetName);

        $this->_jsonResponse([
            'status'        => 'success',
            'target_name'   => $targetName,
            'matched_group' => $matched,
            'groups'        => $res['data']['data'] ?? $res['data'],
        ]);
    }

    /**
     * Eksekusi utama cron notifikasi absensi
     */
    private function _handleCron($sesi)
    {
        if (!$this->_authorizeRequest()) {
            return;
        }

        $sesi = $this->_normalizeSesi($sesi);
        if ($sesi === null) {
            $this->_jsonResponse([
                'status'  => 'error',
                'message' => 'Sesi tidak valid.',
            ], 400);
            return;
        }

        $today = date('Y-m-d');
        $force = !empty($this->input->get_post('force'));

        if (!$force) {
            if (!$this->_isWorkday($today)) {
                $this->_jsonResponse([
                    'status'  => 'skipped',
                    'reason'  => 'weekend',
                    'tanggal' => $today,
                    'sesi'    => $sesi,
                    'message' => 'Notifikasi hanya berjalan hari Senin - Jumat.',
                ]);
                return;
            }

            if ($this->_isHoliday($today)) {
                $this->_jsonResponse([
                    'status'  => 'skipped',
                    'reason'  => 'holiday',
                    'tanggal' => $today,
                    'sesi'    => $sesi,
                    'message' => 'Hari ini tercatat sebagai hari libur.',
                ]);
                return;
            }

            if ($this->_hasSentToday($today, $sesi)) {
                $this->_jsonResponse([
                    'status'  => 'skipped',
                    'reason'  => 'already_sent',
                    'tanggal' => $today,
                    'sesi'    => $sesi,
                    'message' => 'Laporan sesi ini sudah dikirim hari ini.',
                ]);
                return;
            }
        }

        $token = (string)$this->config->item('fonnte_absensi_token');
        if ($token === '') {
            $this->_jsonResponse([
                'status'  => 'error',
                'message' => 'FONNTE_ABSENSI_TOKEN belum diisi di .env atau application/config/fonnte_absensi.php.',
            ], 500);
            return;
        }

        $groupId = $this->_resolveTargetGroupId($token);
        if ($groupId === '') {
            $this->_jsonResponse([
                'status'  => 'error',
                'message' => 'Group ID untuk "' . $this->config->item('fonnte_absensi_group_name') . '" tidak ditemukan. Pastikan nomor ' . $this->config->item('fonnte_absensi_sender') . ' sudah bergabung di grup tersebut atau isi FONNTE_ABSENSI_GROUP_ID.',
            ], 500);
            return;
        }

        $report = $this->_buildBelumAbsenReport($sesi, $today);
        $message = $this->_formatWhatsappMessage($sesi, $today, $report['belum_absen']);

        $sendResult = $this->_sendFonnteDirect($groupId, $message, $token);
        if (!$sendResult['ok']) {
            $this->_jsonResponse([
                'status'  => 'error',
                'sesi'    => $sesi,
                'tanggal' => $today,
                'target'  => $groupId,
                'message' => 'Gagal mengirim pesan ke Fonnte.',
                'detail'  => $sendResult,
            ], 502);
            return;
        }

        $this->_markSentToday($today, $sesi);

        $this->_jsonResponse([
            'status'         => 'success',
            'sesi'           => $sesi,
            'tanggal'        => $today,
            'target_group'   => $groupId,
            'total_belum'    => count($report['belum_absen']),
            'belum_absen'    => $report['belum_absen'],
            'excluded_count' => $report['excluded_count'],
            'fonnte'         => $sendResult['data'],
        ]);
    }

    /**
     * Hitung daftar karyawan aktif yang belum absen sesuai sesi
     *
     * @param string $sesi  pagi|siang|sore
     * @param string $today Y-m-d
     * @return array{belum_absen: array<int, string>, excluded_count: int}
     */
    private function _buildBelumAbsenReport($sesi, $today)
    {
        $karyawanAktif = $this->md_dashboard->daftarKaryawanAktifAbsensi();
        $perusahaan = (int)grantAccessForPerusahaan();

        $rows = $this->db->select('pengguna_id, type_absen, status_absen')
            ->from('absensi')
            ->where('perusahaan', $perusahaan)
            ->where('data_created >=', $today . ' 00:00:00')
            ->where('data_created <=', $today . ' 23:59:59')
            ->get()
            ->result();

        $statusMap = [];
        foreach ($rows as $row) {
            $uid = (int)$row->pengguna_id;
            if (!isset($statusMap[$uid])) {
                $statusMap[$uid] = [
                    'masuk'     => false,
                    'istirahat' => false,
                    'keluar'    => false,
                    'izin_cuti' => false,
                ];
            }

            $type = strtolower(trim((string)$row->type_absen));
            $status = strtolower(trim((string)$row->status_absen));

            if ($type === 'masuk') {
                $statusMap[$uid]['masuk'] = true;
            } elseif ($type === 'istirahat') {
                $statusMap[$uid]['istirahat'] = true;
            } elseif ($type === 'keluar') {
                $statusMap[$uid]['keluar'] = true;
            }

            if (in_array($type, ['izin', 'cuti'], true) || in_array($status, ['izin', 'sakit', 'cuti'], true)) {
                $statusMap[$uid]['izin_cuti'] = true;
            }
        }

        $excludedPulang = $this->config->item('fonnte_absensi_excluded_pulang');
        $excludedLookup = [];
        if (is_array($excludedPulang)) {
            foreach ($excludedPulang as $name) {
                $excludedLookup[strtolower(trim((string)$name))] = true;
            }
        }

        $belumAbsen = [];
        $excludedCount = 0;

        foreach ($karyawanAktif as $karyawan) {
            $uid = (int)$karyawan->pengguna_id;
            $nama = trim((string)$karyawan->nama);
            $userStatus = $statusMap[$uid] ?? [
                'masuk'     => false,
                'istirahat' => false,
                'keluar'    => false,
                'izin_cuti' => false,
            ];

            if ($sesi === 'pagi') {
                // Pagi (09:00): belum absen masuk dan tidak sedang izin/sakit/cuti
                if (!$userStatus['masuk'] && !$userStatus['izin_cuti']) {
                    $belumAbsen[] = $nama;
                }
            } elseif ($sesi === 'siang') {
                // Siang (13:10): sudah absen masuk hari ini namun belum absen istirahat
                if ($userStatus['masuk'] && !$userStatus['istirahat']) {
                    $belumAbsen[] = $nama;
                }
            } elseif ($sesi === 'sore') {
                // Sore (17:15): sudah absen masuk hari ini namun belum absen pulang
                if ($userStatus['masuk'] && !$userStatus['keluar']) {
                    if (isset($excludedLookup[strtolower($nama)])) {
                        $excludedCount++;
                        continue;
                    }
                    $belumAbsen[] = $nama;
                }
            }
        }

        return [
            'belum_absen'    => $belumAbsen,
            'excluded_count' => $excludedCount,
        ];
    }

    /**
     * Format pesan WhatsApp (hanya melaporkan yang belum absen)
     */
    private function _formatWhatsappMessage($sesi, $today, $belumAbsen)
    {
        $labels = [
            'pagi'  => 'BELUM ABSEN MASUK (PAGI)',
            'siang' => 'BELUM ABSEN ISTIRAHAT (SIANG)',
            'sore'  => 'BELUM ABSEN PULANG (SORE)',
        ];

        $judul = $labels[$sesi] ?? 'BELUM ABSEN';
        $tanggalIndo = $this->_formatTanggalIndonesia($today);
        $jam = date('H:i');

        $lines = [];
        $lines[] = "*LAPORAN {$judul}*";
        $lines[] = "Hari/Tgl : {$tanggalIndo}";
        $lines[] = "Pukul    : {$jam} WIB";
        $lines[] = '';

        if (empty($belumAbsen)) {
            $lines[] = 'Semua karyawan sudah melakukan absensi pada sesi ini.';
        } else {
            $lines[] = 'Daftar karyawan yang belum absen (' . count($belumAbsen) . ' orang):';
            foreach ($belumAbsen as $idx => $nama) {
                $lines[] = ($idx + 1) . '. ' . $nama;
            }
        }

        $lines[] = '';
        $lines[] = '_Notifikasi Otomatis Absensi • PT Visi Yosindo Medikal_';

        return implode("\n", $lines);
    }

    private function _resolveTargetGroupId($token)
    {
        $configuredId = trim((string)$this->config->item('fonnte_absensi_group_id'));
        if ($configuredId !== '') {
            return $configuredId;
        }

        $targetName = (string)$this->config->item('fonnte_absensi_group_name');
        $res = $this->_fonnteRequest($this->config->item('fonnte_absensi_groups_url'), [], $token);
        if (!$res['ok']) {
            return '';
        }

        $matched = $this->_findGroupIdByName($res['data'], $targetName);
        if ($matched !== null && !empty($matched['id'])) {
            return (string)$matched['id'];
        }

        // Jika cache grup di Fonnte belum ter-refresh, fetch-group lalu coba lagi sekali
        $this->_fonnteRequest($this->config->item('fonnte_absensi_fetch_group_url'), [], $token);
        $resRetry = $this->_fonnteRequest($this->config->item('fonnte_absensi_groups_url'), [], $token);
        if ($resRetry['ok']) {
            $matchedRetry = $this->_findGroupIdByName($resRetry['data'], $targetName);
            if ($matchedRetry !== null && !empty($matchedRetry['id'])) {
                return (string)$matchedRetry['id'];
            }
        }

        return '';
    }

    private function _findGroupIdByName($apiData, $targetName)
    {
        $list = $apiData['data'] ?? $apiData;
        if (!is_array($list)) {
            return null;
        }

        $targetLower = strtolower(trim($targetName));

        // Utamakan kecocokan persis
        foreach ($list as $group) {
            if (!is_array($group)) {
                continue;
            }
            $name = strtolower(trim((string)($group['name'] ?? ($group['subject'] ?? ''))));
            if ($name !== '' && $name === $targetLower) {
                return $group;
            }
        }

        // Fallback kecocokan sebagian
        foreach ($list as $group) {
            if (!is_array($group)) {
                continue;
            }
            $name = strtolower(trim((string)($group['name'] ?? ($group['subject'] ?? ''))));
            if ($name !== '' && strpos($name, $targetLower) !== false) {
                return $group;
            }
        }

        return null;
    }

    private function _sendFonnteDirect($target, $message, $token)
    {
        $payload = [
            'target'      => $target,
            'message'     => $message,
            'countryCode' => '62',
        ];

        return $this->_fonnteRequest($this->config->item('fonnte_absensi_api_url'), $payload, $token);
    }

    private function _fonnteRequest($url, $payload, $token)
    {
        if (!function_exists('curl_init')) {
            return ['ok' => false, 'error' => 'cURL extension tidak tersedia.'];
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Authorization: ' . $token,
            ],
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($response === false) {
            return ['ok' => false, 'http_code' => $httpCode, 'error' => $curlError];
        }

        $json = json_decode($response, true);
        $isError = ($httpCode < 200 || $httpCode >= 300);
        if (is_array($json) && isset($json['status']) && ($json['status'] === false || $json['status'] === 'false' || $json['status'] === 'error')) {
            $isError = true;
        }

        return [
            'ok'        => !$isError,
            'http_code' => $httpCode,
            'data'      => is_array($json) ? $json : $response,
        ];
    }

    private function _normalizeSesi($sesi)
    {
        $sesi = strtolower(trim((string)$sesi));
        if ($sesi === 'pulang') {
            $sesi = 'sore';
        }
        return in_array($sesi, ['pagi', 'siang', 'sore'], true) ? $sesi : null;
    }

    private function _isWorkday($date)
    {
        $dayOfWeek = (int)date('N', strtotime($date));
        return $dayOfWeek >= 1 && $dayOfWeek <= 5;
    }

    private function _isHoliday($date)
    {
        $perusahaan = (int)grantAccessForPerusahaan();
        $cfg = $this->db->select('is_libur')
            ->where('perusahaan', $perusahaan)
            ->get('absensi_config')
            ->row();

        if ($cfg && (int)$cfg->is_libur === 1) {
            return true;
        }

        $liburRow = $this->db->select('id')
            ->where('tgl', $date)
            ->limit(1)
            ->get('absensi_config_libur')
            ->row();

        return !empty($liburRow);
    }

    private function _getLockFilePath($date, $sesi)
    {
        return APPPATH . 'logs' . DIRECTORY_SEPARATOR . "cron_absensi_{$date}_{$sesi}.lock";
    }

    private function _hasSentToday($date, $sesi)
    {
        return file_exists($this->_getLockFilePath($date, $sesi));
    }

    private function _markSentToday($date, $sesi)
    {
        $lockFile = $this->_getLockFilePath($date, $sesi);
        @file_put_contents($lockFile, date('Y-m-d H:i:s'));
    }

    private function _authorizeRequest()
    {
        $cronKey = trim((string)$this->config->item('fonnte_absensi_cron_key'));
        if ($cronKey === '' || is_cli()) {
            return true;
        }

        $provided = trim((string)$this->input->get_post('key'));
        if (!hash_equals($cronKey, $provided)) {
            $this->_jsonResponse([
                'status'  => 'unauthorized',
                'message' => 'Cron key tidak valid.',
            ], 403);
            return false;
        }

        return true;
    }

    private function _formatTanggalIndonesia($date)
    {
        $hari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];
        $bulan = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $ts = strtotime($date);
        $namaHari = $hari[(int)date('N', $ts)];
        $tgl = (int)date('j', $ts);
        $namaBulan = $bulan[(int)date('n', $ts)];
        $tahun = date('Y', $ts);

        return "{$namaHari}, {$tgl} {$namaBulan} {$tahun}";
    }

    private function _jsonResponse($data, $statusCode = 200)
    {
        $this->output
            ->set_status_header($statusCode)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
