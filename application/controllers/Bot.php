<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bot extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pengguna');
        $this->load->helper('whatsapp_helper');
    }

    public function index()
    {
        echo "Visiyosindo Automation Bot Service is Running.";
    }

    /**
     * Cron Job: Ucapan Selamat Akhir Pekan (Jumat Sore 17:00 WIB)
     * URL: https://office.visiyosindo.id/bot/cron_weekend
     */
    public function cron_weekend()
    {
        // Ambil semua karyawan yang aktif
        $this->db->select('pengguna_id, nama, no_hp');
        $this->db->from('pengguna');
        $this->db->where('status', 1);
        $karyawanList = $this->db->get()->result();

        $totalSent = 0;
        $failed = 0;
        $details = [];

        foreach ($karyawanList as $karyawan) {
            $phone = !empty($karyawan->no_hp) ? $karyawan->no_hp : '';
            $cleanPhone = preg_replace('/[^0-9]/', '', (string)$phone);

            if (empty($cleanPhone) || strlen($cleanPhone) < 9) {
                continue;
            }

            // Nama panggilan/depan agar lebih akrab
            $namaLengkap = trim($karyawan->nama);
            $namaPanggilan = explode(' ', $namaLengkap)[0];

            $res = waSalamWeekend([
                'noPenerima'    => $cleanPhone,
                'namaKaryawan'  => $namaPanggilan,
                'template_name' => 'ucapan_akhir_pekan' // Otomatis pakai template Convia jika sudah diapprove
            ]);

            if ($res) {
                $totalSent++;
                $details[] = ['nama' => $namaLengkap, 'phone' => $cleanPhone, 'status' => 'success'];
            } else {
                $failed++;
                $details[] = ['nama' => $namaLengkap, 'phone' => $cleanPhone, 'status' => 'failed'];
            }
        }

        addLog('Broadcast WhatsApp', 'Cron Weekend Dikirim ke ' . $totalSent . ' Karyawan');

        $response = [
            'status'     => 'success',
            'pesan'      => 'Cron Weekend Selesai Dijalankan',
            'total_sent' => $totalSent,
            'failed'     => $failed,
            'timestamp'  => date('Y-m-d H:i:s')
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * Cron Job: Ucapan Semangat Senin Pagi (Senin Pagi 07:00 WIB)
     * URL: https://office.visiyosindo.id/bot/cron_senin
     */
    public function cron_senin()
    {
        // Ambil semua karyawan yang aktif
        $this->db->select('pengguna_id, nama, no_hp');
        $this->db->from('pengguna');
        $this->db->where('status', 1);
        $karyawanList = $this->db->get()->result();

        $totalSent = 0;
        $failed = 0;
        $details = [];

        foreach ($karyawanList as $karyawan) {
            $phone = !empty($karyawan->no_hp) ? $karyawan->no_hp : '';
            $cleanPhone = preg_replace('/[^0-9]/', '', (string)$phone);

            if (empty($cleanPhone) || strlen($cleanPhone) < 9) {
                continue;
            }

            $namaLengkap = trim($karyawan->nama);
            $namaPanggilan = explode(' ', $namaLengkap)[0];

            $res = waSalamSenin([
                'noPenerima'    => $cleanPhone,
                'namaKaryawan'  => $namaPanggilan,
                'template_name' => 'ucapan_senin_pagi' // Otomatis pakai template Convia jika sudah diapprove
            ]);

            if ($res) {
                $totalSent++;
                $details[] = ['nama' => $namaLengkap, 'phone' => $cleanPhone, 'status' => 'success'];
            } else {
                $failed++;
                $details[] = ['nama' => $namaLengkap, 'phone' => $cleanPhone, 'status' => 'failed'];
            }
        }

        addLog('Broadcast WhatsApp', 'Cron Senin Pagi Dikirim ke ' . $totalSent . ' Karyawan');

        $response = [
            'status'     => 'success',
            'pesan'      => 'Cron Senin Pagi Selesai Dijalankan',
            'total_sent' => $totalSent,
            'failed'     => $failed,
            'timestamp'  => date('Y-m-d H:i:s')
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
