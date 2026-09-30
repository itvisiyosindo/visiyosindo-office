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
