<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wa_chat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Md_whatsapp_chat');
        $this->load->model('Md_whatsapp_session');
        $this->load->helper('whatsapp');
        $this->load->helper('session');
    }

    /**
     * Tentukan navbar aktif (default home, atau sesuai tab marketing/helpdesk)
     */
    public function id_navbar()
    {
        $nav = $this->input->get('nav');
        if (!empty($nav) && in_array($nav, ['home', 'marketing', 'helpdesk', 'inventory', 'kepegawaian'])) {
            return $nav;
        }

        $ref = $this->input->server('HTTP_REFERER');
        if (!empty($ref)) {
            if (strpos($ref, 'marketing') !== false) {
                return 'marketing';
            }
            if (strpos($ref, 'helpdesk') !== false) {
                return 'helpdesk';
            }
        }

        return 'home';
    }

    /**
     * Halaman Utama Panel Chat WhatsApp
     */
    public function index()
    {
        grantAccessFor('all');

        $activePhone = $this->input->get('phone');
        if (!empty($activePhone)) {
            $activePhone = $this->Md_whatsapp_chat->normalize_phone($activePhone);
        }

        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'chat/v_chat_panel';
        $page_data['page_title']    = 'WhatsApp Live Chat';
        $page_data['page_desc']     = 'Panel Komunikasi WhatsApp Terintegrasi Office';
        $page_data['active_phone']  = $activePhone;

        $this->load->view('index', $page_data);
    }

    /**
     * AJAX: Ambil daftar percakapan / kontak
     */
    public function get_conversations()
    {
        grantAccessFor('all');

        $search = $this->input->get_post('search');
        $tab = $this->input->get_post('tab');

        $conversations = $this->Md_whatsapp_chat->get_conversations($search, $tab);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'data'   => $conversations,
                'total'  => count($conversations)
            ]));
    }

    /**
     * AJAX: Ambil riwayat percakapan untuk nomor tertentu
     */
    public function get_messages()
    {
        grantAccessFor('all');

        $phone = $this->input->get_post('phone');
        $lastId = (int)$this->input->get_post('last_id');

        if (empty($phone)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => 'error',
                    'message' => 'Nomor HP tidak valid'
                ]));
            return;
        }

        $phone = $this->Md_whatsapp_chat->normalize_phone($phone);

        // Ambil pesan
        $messages = $this->Md_whatsapp_chat->get_chat_messages($phone, 150, $lastId);

        // Jika bukan incremental polling, tandai semua pesan masuk sebagai telah dibaca
        if ($lastId == 0) {
            $this->Md_whatsapp_chat->mark_messages_read($phone);
        }

        // Ambil info kontak & sesi
        $contactInfo = $this->Md_whatsapp_chat->resolve_contact_info($phone);
        $session = $this->db->query(
            "SELECT id, last_incoming_at, last_message_text,
                    TIMESTAMPDIFF(MINUTE, last_incoming_at, NOW()) AS elapsed_minutes,
                    (1440 - TIMESTAMPDIFF(MINUTE, last_incoming_at, NOW())) AS remaining_minutes
             FROM tbl_whatsapp_sessions
             WHERE phone_number = ?
             LIMIT 1",
            [$phone]
        )->row_array();

        $isSessionActive = false;
        $remainingMinutes = 0;
        $remainingText = 'Kedaluwarsa';

        if (!empty($session) && !empty($session['last_incoming_at'])) {
            $rem = (int)$session['remaining_minutes'];
            if ($rem > 0) {
                $isSessionActive = true;
                $remainingMinutes = $rem;
                $jam = floor($rem / 60);
                $menit = $rem % 60;
                $remainingText = "{$jam}j {$menit}m";
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'   => 'success',
                'phone'    => $phone,
                'contact'  => $contactInfo,
                'session'  => [
                    'active'            => $isSessionActive,
                    'remaining_minutes' => $remainingMinutes,
                    'remaining_text'    => $remainingText,
                    'last_incoming_at'  => !empty($session['last_incoming_at']) ? $session['last_incoming_at'] : null
                ],
                'messages' => $messages
            ]));
    }

    /**
     * AJAX: Kirim balasan pesan WhatsApp
     */
    public function send_message()
    {
        grantAccessFor('all');

        $phone = $this->input->post('phone');
        $message = trim((string)$this->input->post('message'));
        $bypass24h = $this->input->post('bypass_24h');

        if (empty($phone) || empty($message)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => 'error',
                    'message' => 'Nomor penerima dan isi pesan tidak boleh kosong'
                ]));
            return;
        }

        $normalizedPhone = $this->Md_whatsapp_chat->normalize_phone($phone);

        // Cek jendela 24 jam jika bypass tidak aktif
        $isActive = $this->Md_whatsapp_session->is_session_active($normalizedPhone);
        if (!$isActive && empty($bypass24h)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => 'error',
                    'code'    => 'SESSION_EXPIRED',
                    'message' => 'Sesi 24 jam untuk nomor ini sudah kedaluwarsa. Gunakan centang kirim template atau tunggu pelanggan chat kembali.'
                ]));
            return;
        }

        // Kirim via Fonnte
        $dataSend = [
            'penerima'   => $normalizedPhone,
            'pesan'      => $message,
            'bypass_24h' => !empty($bypass24h),
            'immediate'  => true,
            'raw_chat'   => true
        ];

        $sendSuccess = sendWa($dataSend);

        // Catat pesan keluar ke database
        $userId = function_exists('sessPenggunaId') ? sessPenggunaId() : $this->session->userdata('pengguna_id');
        $msgId = $this->Md_whatsapp_chat->record_outgoing_message(
            $normalizedPhone,
            $message,
            $userId,
            null,
            null,
            $sendSuccess ? 'sent' : 'failed'
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'       => $sendSuccess ? 'success' : 'warning',
                'message'      => $sendSuccess ? 'Pesan berhasil terkirim' : 'Pesan dicatat namun gateway melaporkan respon tunda',
                'message_id'   => $msgId,
                'time_display' => date('H:i')
            ]));
    }

    /**
     * AJAX: Tandai percakapan telah dibaca
     */
    public function mark_read()
    {
        grantAccessFor('all');

        $phone = $this->input->post('phone');
        if (!empty($phone)) {
            $this->Md_whatsapp_chat->mark_messages_read($phone);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => 'success']));
    }
}
