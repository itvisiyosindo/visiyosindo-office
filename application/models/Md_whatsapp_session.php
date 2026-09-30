<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_whatsapp_session extends CI_Model
{
    protected $table = 'tbl_whatsapp_sessions';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Normalisasi nomor telepon ke format 628xxx
     *
     * @param string $phone
     * @return string
     */
    public function normalize_phone($phone)
    {
        $clean = preg_replace('/[^0-9]/', '', (string)$phone);
        if (substr($clean, 0, 2) === '62') {
            return $clean;
        } elseif (substr($clean, 0, 1) === '0') {
            return '62' . substr($clean, 1);
        } elseif (substr($clean, 0, 1) === '8') {
            return '628' . substr($clean, 1);
        }
        return $clean;
    }

    /**
     * Catat atau perbarui sesi pesan masuk dari WhatsApp
     *
     * @param string $phone
     * @param string $message
     * @return array
     */
    public function record_incoming($phone, $message = '')
    {
        $normalizedPhone = $this->normalize_phone($phone);
        if (empty($normalizedPhone)) {
            return ['status' => false, 'message' => 'Nomor HP kosong'];
        }

        // Cari data pengguna dari database berdasarkan nomor HP
        $pengguna = $this->find_pengguna_by_phone($normalizedPhone);
        $penggunaId = !empty($pengguna) ? $pengguna->pengguna_id : null;
        $namaPengguna = !empty($pengguna) ? $pengguna->nama : null;

        // Cek apakah nomor sudah terdaftar di tbl_whatsapp_sessions
        $existing = $this->db->get_where($this->table, ['phone_number' => $normalizedPhone])->row();

        $now = date('Y-m-d H:i:s');
        if ($existing) {
            $updateData = [
                'last_incoming_at'      => $now,
                'last_message_text' => $message,
                'reminder_sent'         => 0,
                'updated_at'            => $now
            ];
            if ($penggunaId && empty($existing->pengguna_id)) {
                $updateData['pengguna_id'] = $penggunaId;
            }
            if ($namaPengguna && empty($existing->nama)) {
                $updateData['nama'] = $namaPengguna;
            }

            $this->db->where('id', $existing->id);
            $this->db->update($this->table, $updateData);

            return [
                'status'     => true,
                'action'     => 'updated',
                'id'         => $existing->id,
                'phone'      => $normalizedPhone,
                'nama'       => !empty($existing->nama) ? $existing->nama : $namaPengguna
            ];
        } else {
            $insertData = [
                'phone_number'          => $normalizedPhone,
                'pengguna_id'           => $penggunaId,
                'nama'                  => $namaPengguna,
                'last_incoming_at'      => $now,
                'last_message_text' => $message,
                'reminder_sent'         => 0,
                'created_at'            => $now,
                'updated_at'            => $now
            ];
            $this->db->insert($this->table, $insertData);
            $insertId = $this->db->insert_id();

            return [
                'status'     => true,
                'action'     => 'inserted',
                'id'         => $insertId,
                'phone'      => $normalizedPhone,
                'nama'       => $namaPengguna
            ];
        }
    }

    /**
     * Cari pengguna berdasarkan variasi nomor HP
     *
     * @param string $normalizedPhone (contoh: 6281234567890)
     * @return object|null
     */
    public function find_pengguna_by_phone($normalizedPhone)
    {
        $phone0 = '0' . substr($normalizedPhone, 2); // 08xxx
        $phone8 = substr($normalizedPhone, 2);       // 8xxx

        $this->db->select('pengguna_id, nama, nohp, status');
        $this->db->group_start();
        $this->db->where('nohp', $normalizedPhone);
        $this->db->or_where('nohp', $phone0);
        $this->db->or_where('nohp', $phone8);
        $this->db->or_where('nohp', '+' . $normalizedPhone);
        $this->db->group_end();
        $this->db->where('status !=', '2'); // Bukan yang dihapus jika ada
        $this->db->order_by('pengguna_id', 'DESC');
        $this->db->limit(1);
        return $this->db->get('pengguna')->row();
    }

    /**
     * Dapatkan sesi yang mendekati masa habis 24 jam (misal jam ke-23 / sisa <= 1 jam)
     *
     * @param int $thresholdHours Jam mulainya pengingat (default 23 jam)
     * @return array
     */
    public function get_expiring_sessions($thresholdHours = 23)
    {
        $thresholdHours = (int)$thresholdHours;
        $sql = "SELECT id, phone_number, pengguna_id, nama, last_incoming_at, reminder_sent,
                       TIMESTAMPDIFF(MINUTE, last_incoming_at, NOW()) AS elapsed_minutes,
                       (1440 - TIMESTAMPDIFF(MINUTE, last_incoming_at, NOW())) AS remaining_minutes
                FROM {$this->table}
                WHERE last_incoming_at <= DATE_SUB(NOW(), INTERVAL ? HOUR)
                  AND last_incoming_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                  AND reminder_sent = 0";

        $query = $this->db->query($sql, [$thresholdHours]);
        return $query->result();
    }

    /**
     * Tandai pengingat sudah dikirim
     *
     * @param int $id
     * @return bool
     */
    public function mark_reminder_sent($id)
    {
        $this->db->where('id', (int)$id);
        return $this->db->update($this->table, [
            'reminder_sent'    => 1,
            'last_reminder_at' => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Ambil semua daftar sesi untuk monitoring
     *
     * @return array
     */
    public function get_all_sessions()
    {
        $sql = "SELECT id, phone_number, pengguna_id, nama, last_incoming_at, last_message_text,
                       reminder_sent, last_reminder_at,
                       TIMESTAMPDIFF(HOUR, last_incoming_at, NOW()) AS elapsed_hours,
                       TIMESTAMPDIFF(MINUTE, last_incoming_at, NOW()) AS elapsed_minutes,
                       (1440 - TIMESTAMPDIFF(MINUTE, last_incoming_at, NOW())) AS remaining_minutes,
                       CASE
                           WHEN TIMESTAMPDIFF(HOUR, last_incoming_at, NOW()) < 23 THEN 'ACTIVE'
                           WHEN TIMESTAMPDIFF(HOUR, last_incoming_at, NOW()) < 24 THEN 'EXPIRING_SOON'
                           ELSE 'EXPIRED'
                       END AS session_status
                FROM {$this->table}
                ORDER BY last_incoming_at DESC";

        return $this->db->query($sql)->result();
    }
}
