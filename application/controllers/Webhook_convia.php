<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Webhook_convia extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Md_whatsapp_session');
        $this->load->model('Md_whatsapp_chat');
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
            $data = !empty($_POST) ? $_POST : $this->input->post();
        }
        if (empty($data)) {
            $data = $_REQUEST;
        }

        // Catat incoming request untuk audit & live debug
        $logDir = APPPATH . 'logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        $logLine = date('Y-m-d H:i:s') . ' | IP: ' . $this->input->ip_address()
            . ' | RAW: ' . substr((string)$rawPayload, 0, 500)
            . ' | POST: ' . json_encode($_POST) . PHP_EOL;
        @file_put_contents($logDir . '/webhook_incoming.log', $logLine, FILE_APPEND);

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
            } elseif (!empty($data['phone'])) {
                $phone = $data['phone'];
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
        if (!empty($phone) && strpos($phone, '@g.us') === false) {
            // 1. Ekstraksi data pelengkap pesan
            $senderName = !empty($data['name']) ? $data['name'] : (!empty($data['pushname']) ? $data['pushname'] : null);
            $mediaUrl = !empty($data['url']) ? $data['url'] : (!empty($data['file']) ? $data['file'] : null);
            $fonnteId = !empty($data['id']) ? $data['id'] : null;

            // 2. Catat pesan ke tabel riwayat chat & perbarui sesi 24 jam
            $msgId = $this->Md_whatsapp_chat->record_incoming_message($phone, (string)$message, $senderName, $mediaUrl, $fonnteId, $data);

            // Sesi dan pesan diperbarui secara senyap (tanpa mengirim balasan konfirmasi)
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'     => 'success',
                    'message'    => 'Message recorded and session updated silently',
                    'message_id' => $msgId
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
