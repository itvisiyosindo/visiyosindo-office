<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reminder_wa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Md_whatsapp_session');
        $this->load->helper('whatsapp');
    }

    /**
     * Endpoint Cron Job untuk memeriksa sesi yang mendekati masa habis (sisa 1 jam / jam ke-23)
     * URL: https://office.visiyosindo.id/reminder_wa/check_sessions
     * Cron schedule rekomendasi: Setiap 10 - 15 menit
     */
    public function check_sessions()
    {
        // 1. Ambil semua sesi yang berada di jam ke-23 (sisa < 60 menit) dan belum dikirimkan reminder
        $expiringSessions = $this->Md_whatsapp_session->get_expiring_sessions(23);

        $sentCount = 0;
        $details = [];

        foreach ($expiringSessions as $session) {
            $nama = !empty($session->nama) ? $session->nama : 'Bapak/Ibu';
            $sisaMenit = max(1, (int)$session->remaining_minutes);

            $pesan = "⏰ *Pengingat Sesi Notifikasi WhatsApp Office*\n\n"
                . "Halo *" . $nama . "*,\n"
                . "Sesi pesan WhatsApp Anda dengan sistem Office akan berakhir dalam kurun waktu sekitar *" . $sisaMenit . " menit lagi* (sesuai batasan jendela 24 jam WhatsApp).\n\n"
                . "👉 Mohon *balas chat ini* (cukup ketik: *OK*) agar sesi notifikasi tetap aktif dan Anda tetap dapat menerima notifikasi approval maupun update tugas tanpa terputus.\n\n"
                . "Terima kasih! 🙏\n"
                . "_Sistem Office PT Visi Yosindo Medikal_";

            // Kirim pesan pengingat (karena masih di jam ke-23, sesi 24 jam masih terbuka sehingga chat bebas bisa masuk)
            $sendResult = sendWa([
                'penerima' => $session->phone_number,
                'pesan'    => $pesan
            ]);

            // Tandai reminder sudah dikirim agar tidak dikirim berulang kali dalam 1 jam tersebut
            $this->Md_whatsapp_session->mark_reminder_sent($session->id);

            $sentCount++;
            $details[] = [
                'id'                => $session->id,
                'phone'             => $session->phone_number,
                'nama'              => $session->nama,
                'remaining_minutes' => $sisaMenit,
                'send_status'       => $sendResult
            ];
        }

        $response = [
            'status'        => 'success',
            'timestamp'     => date('Y-m-d H:i:s'),
            'total_checked' => count($expiringSessions),
            'total_sent'    => $sentCount,
            'details'       => $details
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }

    /**
     * Endpoint untuk memantau status semua sesi nomor WhatsApp
     * URL: https://office.visiyosindo.id/reminder_wa/status
     */
    public function status()
    {
        $sessions = $this->Md_whatsapp_session->get_all_sessions();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'         => 'success',
                'server_time'    => date('Y-m-d H:i:s'),
                'total_sessions' => count($sessions),
                'sessions'       => $sessions
            ], JSON_PRETTY_PRINT));
    }

    /**
     * Endpoint helper untuk inisialisasi / testing sesi nomor tertentu secara manual
     * URL: https://office.visiyosindo.id/reminder_wa/init_session?phone=085668033598
     */
    public function init_session()
    {
        $phone = $this->input->get('phone');
        $msg   = $this->input->get('msg') ?: 'Manual init via browser';

        if (empty($phone)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => 'error',
                    'message' => 'Parameter phone wajib diisi. Contoh: ?phone=085668033598'
                ]));
            return;
        }

        $res = $this->Md_whatsapp_session->record_incoming($phone, $msg);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'  => 'success',
                'message' => 'Sesi berhasil diinisialisasi',
                'result'  => $res
            ], JSON_PRETTY_PRINT));
    }
}
