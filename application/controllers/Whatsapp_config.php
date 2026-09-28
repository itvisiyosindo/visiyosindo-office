<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * WhatsApp API Configuration Controller
 * 
 * Mengelola konfigurasi dan monitoring device WhatsApp via WhacCenter API
 * Akses: Administrator Only
 * 
 * @author System
 * @since 2026-01-19
 */
class Whatsapp_config extends CI_Controller
{
    // WhacCenter API Base URL
    private $api_base_url = 'https://app.whacenter.com/api';

    // Device IDs - Sesuaikan dengan device yang terdaftar di WhacCenter
    private $registered_devices = [
        [
            'device_id' => '4611b50d90ba3abb8e5dca14b6d53557',
            'name' => 'Whatsapp Notifikasi Config',
            'description' => 'Device WhatsApp untuk notifikasi Office Backup'
        ],
        [
            'device_id' => '7388d9d2431fbc65c0ce49a4fac7550b',
            'name' => 'Admin IT Databank',
            'description' => 'Device WhatsApp Admin IT untuk notifikasi sistem'
        ],
        [
            'device_id' => 'bbe8ce03e61d0c490e850bce5f3df56c',
            'name' => 'Databank',
            'description' => 'Device WhatsApp Databank (Connected)'
        ]
    ];

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

    /**
     * Helper untuk mendapatkan ID navbar
     */
    private function id_navbar()
    {
        return "home";
    }

    /**
     * Halaman utama - Daftar Device WhatsApp
     */
    public function index()
    {
        grantAccessFor(['Administrator']);

        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'v_whatsapp_config';
        $page_data['page_title']    = 'WhatsApp API Configuration';

        $this->load->view('index', $page_data);
    }

    /**
     * API: Get daftar device dengan status dari WhacCenter API
     * Menggunakan device ID yang terdaftar di whatsapp_helper.php
     * 
     * @return JSON
     */
    public function get_devices()
    {
        grantAccessFor(['Administrator']);

        try {
            $devices = [];

            // Loop semua device yang terdaftar dan ambil statusnya
            foreach ($this->registered_devices as $device) {
                $device_id = $device['device_id'];
                $url = $this->api_base_url . '/statusDevice?device_id=' . urlencode($device_id);

                $ctx = stream_context_create([
                    'http' => [
                        'timeout' => 10,
                        'method' => 'GET',
                        'header' => 'Content-Type: application/json'
                    ]
                ]);

                $response = @file_get_contents($url, false, $ctx);

                $deviceInfo = [
                    'device_id' => $device_id,
                    'name' => $device['name'],
                    'description' => $device['description'],
                    'status' => 'unknown',
                    'nomor' => '-',
                    'qr' => ''
                ];

                if ($response !== false) {
                    $data = json_decode($response, true);
                    if (isset($data['status']) && $data['status'] === true && isset($data['data'])) {
                        $deviceInfo['status'] = $data['data']['status'] ?? 'unknown';
                        $deviceInfo['nomor'] = $data['data']['nomor'] ?? '-';
                        $deviceInfo['nama_wa'] = $data['data']['nama'] ?? '';
                        $deviceInfo['qr'] = $data['data']['qr'] ?? '';
                    }
                }

                $devices[] = $deviceInfo;
            }

            echo json_encode([
                'success' => true,
                'devices' => $devices
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'devices' => []
            ]);
        }
    }

    /**
     * API: Cek status device
     * Endpoint: https://app.whacenter.com/api/statusDevice?device_id=xxx
     * Response: { status: true, message: "...", data: { status: "CONNECTED", nomor: "...", nama: "...", qr: "done" } }
     * 
     * @return JSON
     */
    public function check_status()
    {
        grantAccessFor(['Administrator']);

        $device_id = $this->input->post('device_id');

        if (empty($device_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Device ID tidak boleh kosong'
            ]);
            return;
        }

        try {
            // Endpoint yang benar: /statusDevice
            $url = $this->api_base_url . '/statusDevice?device_id=' . urlencode($device_id);

            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 15,
                    'method' => 'GET',
                    'header' => 'Content-Type: application/json'
                ]
            ]);

            $response = @file_get_contents($url, false, $ctx);

            if ($response === false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal terhubung ke WhacCenter API',
                    'status' => 'unknown'
                ]);
                return;
            }

            $data = json_decode($response, true);

            // Format response sesuai struktur WhacCenter
            // { status: true, data: { status: "CONNECTED", nomor: "...", nama: "...", qr: "done" } }
            if (isset($data['status']) && $data['status'] === true && isset($data['data'])) {
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'status' => $data['data']['status'] ?? 'unknown',
                        'nomor' => $data['data']['nomor'] ?? '',
                        'nama' => $data['data']['nama'] ?? '',
                        'qr' => $data['data']['qr'] ?? ''
                    ],
                    'raw_response' => $data
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $data['message'] ?? 'Gagal mendapatkan status',
                    'data' => $data
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Relog device (disconnect dan reconnect)
     * Endpoint: https://app.whacenter.com/api/relogDevice (POST)
     * Payload: device_id
     * 
     * @return JSON
     */
    public function relog_device()
    {
        grantAccessFor(['Administrator']);

        $device_id = $this->input->post('device_id');

        if (empty($device_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Device ID tidak boleh kosong'
            ]);
            return;
        }

        try {
            // Endpoint yang benar: /relogDevice dengan method POST
            $url = $this->api_base_url . '/relogDevice';

            $ch = curl_init($url);
            $data = array('device_id' => $device_id);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $curl_error = curl_error($ch);
            curl_close($ch);

            if ($response === false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal terhubung ke WhacCenter API: ' . $curl_error
                ]);
                return;
            }

            $result = json_decode($response, true);

            if (isset($result['status']) && $result['status'] === true) {
                echo json_encode([
                    'success' => true,
                    'data' => $result,
                    'message' => 'Relog berhasil, koneksi device berhasil di-refresh'
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'data' => $result,
                    'message' => $result['message'] ?? 'Relog berhasil, koneksi device di-refresh'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Dapatkan QR Code untuk scan
     * Endpoint: https://app.whacenter.com/api/qr?device_id=xxx
     * 
     * Catatan: 
     * - Endpoint /qr mengembalikan gambar QR Code jika session aktif
     * - Jika qr: "timeout", perlu relog dulu untuk generate QR baru
     * - Jika status: "CONNECTED", tidak perlu QR
     * 
     * @return JSON atau Image
     */
    public function get_qrcode()
    {
        grantAccessFor(['Administrator']);

        $device_id = $this->input->post('device_id');
        $direct_image = $this->input->get('direct'); // untuk akses langsung gambar

        // Jika akses langsung gambar (untuk img src)
        if ($direct_image && $this->input->get('device_id')) {
            $device_id = $this->input->get('device_id');
            $this->proxy_qr_image($device_id);
            return;
        }

        if (empty($device_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Device ID tidak boleh kosong'
            ]);
            return;
        }

        try {
            // Cek status device terlebih dahulu
            $status_url = $this->api_base_url . '/statusDevice?device_id=' . urlencode($device_id);

            $ch = curl_init($status_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $status_response = curl_exec($ch);
            curl_close($ch);

            $device_status = 'UNKNOWN';
            $qr_status = '';

            if ($status_response !== false) {
                $status_data = json_decode($status_response, true);

                if (isset($status_data['data']['status'])) {
                    $device_status = strtoupper($status_data['data']['status']);
                }

                if (isset($status_data['data']['qr'])) {
                    $qr_status = $status_data['data']['qr'];
                }

                // Jika device sudah CONNECTED, tidak perlu QR
                if ($device_status === 'CONNECTED') {
                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'status' => 'CONNECTED',
                            'message' => 'Device sudah terhubung, tidak perlu scan QR Code',
                            'nomor' => $status_data['data']['nomor'] ?? '',
                            'nama' => $status_data['data']['nama'] ?? ''
                        ]
                    ]);
                    return;
                }

                // Jika QR timeout, perlu relog dulu
                if (strtolower($qr_status) === 'timeout') {
                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'status' => $device_status,
                            'qr_status' => 'timeout',
                            'need_relog' => true,
                            'message' => 'Session QR sudah timeout. Silakan klik "Relog Device" untuk mendapatkan QR Code baru.'
                        ]
                    ]);
                    return;
                }
            }

            // Device belum connected dan QR tersedia
            // Gunakan proxy endpoint untuk menghindari masalah cache browser
            $qr_url = base_url('whatsapp_config/get_qrcode?direct=1&device_id=' . urlencode($device_id) . '&t=' . time());

            echo json_encode([
                'success' => true,
                'data' => [
                    'qrcode' => $qr_url,
                    'status' => $device_status,
                    'qr_data_available' => !empty($qr_status) && strtolower($qr_status) !== 'timeout'
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Proxy untuk fetch gambar QR dari WhacCenter
     * Ini menghindari masalah CORS dan cache browser
     * 
     * @param string $device_id
     */
    private function proxy_qr_image($device_id)
    {
        // Fetch QR image dari WhacCenter
        $qr_url = $this->api_base_url . '/qr?device_id=' . urlencode($device_id);

        $ch = curl_init($qr_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $image_data = curl_exec($ch);
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($image_data === false || $http_code !== 200) {
            // Return placeholder image jika gagal
            header('Content-Type: image/png');
            $img = imagecreatetruecolor(280, 280);
            $bg = imagecolorallocate($img, 255, 255, 255);
            $text_color = imagecolorallocate($img, 150, 150, 150);
            imagefill($img, 0, 0, $bg);
            imagestring($img, 5, 60, 130, 'QR Code tidak tersedia', $text_color);
            imagepng($img);
            imagedestroy($img);
            return;
        }

        // Set header dan output image
        header('Content-Type: ' . ($content_type ?: 'image/png'));
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        echo $image_data;
    }

    /**
     * API: Disconnect device
     * 
     * @return JSON
     */
    public function disconnect_device()
    {
        grantAccessFor(['Administrator']);

        $device_id = $this->input->post('device_id');

        if (empty($device_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Device ID tidak boleh kosong'
            ]);
            return;
        }

        try {
            $url = $this->api_base_url . '/device/disconnect?device_id=' . $device_id;

            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 15,
                    'method' => 'GET',
                    'header' => 'Content-Type: application/json'
                ]
            ]);

            $response = @file_get_contents($url, false, $ctx);

            if ($response === false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal terhubung ke WhacCenter API'
                ]);
                return;
            }

            $data = json_decode($response, true);

            echo json_encode([
                'success' => true,
                'data' => $data,
                'message' => 'Device berhasil di-disconnect'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Kirim pesan test
     * Endpoint: https://app.whacenter.com/api/send (POST)
     * Payload: device_id, number, message
     * 
     * @return JSON
     */
    public function send_test()
    {
        grantAccessFor(['Administrator']);

        $device_id = $this->input->post('device_id');
        $send_type = $this->input->post('send_type') ?: 'individual';
        $phone = $this->input->post('phone');
        $group_name = $this->input->post('group_name');
        $message = $this->input->post('message');

        $attachment_mode = $this->input->post('attachment_mode') ?: 'url';
        $file_url = $this->input->post('file_url');

        if ($attachment_mode === 'upload' && !empty($_FILES['file_upload']['name'])) {
            $config['upload_path']   = FCPATH . 'uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx|zip|rar';
            $config['max_size']      = '5000'; // Max 5MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload('file_upload')) {
                $upload_data = $this->upload->data();
                $file_url = base_url('uploads/' . $upload_data['file_name']);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal mengunggah file lokal: ' . strip_tags($this->upload->display_errors())
                ]);
                return;
            }
        }

        if (empty($device_id) || empty($message) || ($send_type == 'individual' && empty($phone)) || ($send_type == 'group' && empty($group_name))) {
            echo json_encode([
                'success' => false,
                'message' => 'Semua field harus diisi'
            ]);
            return;
        }

        try {
            if ($send_type == 'group') {
                // Endpoint: /sendGroup dengan method POST dan form-data
                $url = $this->api_base_url . '/sendGroup';
                $data = array(
                    'device_id' => $device_id,
                    'group' => $group_name,
                    'message' => $message
                );
                if (!empty($file_url)) {
                    $data['file'] = $file_url;
                }
            } else {
                // Format nomor telepon
                $phone = preg_replace('/[^0-9]/', '', $phone);
                if (substr($phone, 0, 1) == '0') {
                    $phone = '62' . substr($phone, 1);
                }

                // Endpoint: /send dengan method POST dan form-data
                $url = $this->api_base_url . '/send';
                $data = array(
                    'device_id' => $device_id,
                    'number' => $phone,
                    'message' => $message
                );
                if (!empty($file_url)) {
                    $data['file'] = $file_url;
                }
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            curl_close($ch);

            if ($response === false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal terhubung ke WhacCenter API: ' . $curl_error
                ]);
                return;
            }

            $result = json_decode($response, true);

            // Response format: { status: true/false, message: "...", data: { id: xxx } }
            if (isset($result['status']) && $result['status'] === true) {
                $target = $send_type == 'group' ? $group_name : $phone;
                echo json_encode([
                    'success' => true,
                    'message' => 'Pesan berhasil dikirim ke ' . $target,
                    'data' => $result
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $result['message'] ?? 'Gagal mengirim pesan. Pastikan device terhubung.',
                    'data' => $result
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Dapatkan info device
     * 
     * @return JSON
     */
    public function get_device_info()
    {
        grantAccessFor(['Administrator']);

        $device_id = $this->input->post('device_id');

        if (empty($device_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Device ID tidak boleh kosong'
            ]);
            return;
        }

        try {
            $url = $this->api_base_url . '/device/info?device_id=' . $device_id;

            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 15,
                    'method' => 'GET',
                    'header' => 'Content-Type: application/json'
                ]
            ]);

            $response = @file_get_contents($url, false, $ctx);

            if ($response === false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal terhubung ke WhacCenter API'
                ]);
                return;
            }

            $data = json_decode($response, true);

            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
