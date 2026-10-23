<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_whatsapp_chat extends CI_Model
{
    protected $table_messages = 'tbl_whatsapp_messages';
    protected $table_sessions = 'tbl_whatsapp_sessions';

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_whatsapp_session');
    }

    /**
     * Normalisasi nomor telepon ke format internasional (628xxx)
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
     * Format tampilan nomor HP (0813-7175-8592)
     */
    public function format_phone_display($phone)
    {
        $clean = $this->normalize_phone($phone);
        if (substr($clean, 0, 2) === '62') {
            $local = '0' . substr($clean, 2);
        } else {
            $local = $clean;
        }

        if (strlen($local) >= 10) {
            return substr($local, 0, 4) . '-' . substr($local, 4, 4) . '-' . substr($local, 8);
        }
        return $local;
    }

    /**
     * Simpan pesan WhatsApp masuk dari Webhook
     */
    public function record_incoming_message($phone, $message, $senderName = null, $mediaUrl = null, $fonnteId = null, $rawPayload = null)
    {
        $normalizedPhone = $this->normalize_phone($phone);
        if (empty($normalizedPhone)) {
            return false;
        }

        // Perbarui / buat sesi 24 jam
        $sessionResult = $this->Md_whatsapp_session->record_incoming($normalizedPhone, (string)$message);
        $sessionId = !empty($sessionResult['id']) ? $sessionResult['id'] : null;

        $mediaType = null;
        if (!empty($mediaUrl)) {
            $ext = strtolower(pathinfo(parse_url($mediaUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $mediaType = 'image';
            } elseif (in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                $mediaType = 'document';
            } elseif (in_array($ext, ['mp3', 'ogg', 'wav', 'm4a'])) {
                $mediaType = 'audio';
            } elseif (in_array($ext, ['mp4', 'mov', 'avi'])) {
                $mediaType = 'video';
            } else {
                $mediaType = 'file';
            }
        }

        $data = [
            'session_id'   => $sessionId,
            'phone_number' => $normalizedPhone,
            'direction'    => 'in',
            'message_text' => (string)$message,
            'media_url'    => $mediaUrl,
            'media_type'   => $mediaType,
            'sender_name'  => $senderName,
            'status'       => 'received',
            'is_read'      => 0,
            'fonnte_id'    => $fonnteId,
            'raw_payload'  => is_array($rawPayload) ? json_encode($rawPayload) : $rawPayload
        ];

        $this->db->set($data);
        $this->db->set('created_at', 'NOW()', FALSE);
        $this->db->insert($this->table_messages);
        return $this->db->insert_id();
    }

    /**
     * Simpan pesan WhatsApp keluar yang dikirim oleh Admin/Petugas Office
     */
    public function record_outgoing_message($phone, $message, $idPengguna = null, $mediaUrl = null, $fonnteId = null, $status = 'sent')
    {
        $normalizedPhone = $this->normalize_phone($phone);
        if (empty($normalizedPhone)) {
            return false;
        }

        // Cek sesi yang ada
        $session = $this->db->get_where($this->table_sessions, ['phone_number' => $normalizedPhone])->row();
        $sessionId = $session ? $session->id : null;

        $mediaType = null;
        if (!empty($mediaUrl)) {
            $ext = strtolower(pathinfo(parse_url($mediaUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $mediaType = 'image';
            } elseif (in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                $mediaType = 'document';
            } else {
                $mediaType = 'file';
            }
        }

        $data = [
            'session_id'   => $sessionId,
            'phone_number' => $normalizedPhone,
            'direction'    => 'out',
            'message_text' => (string)$message,
            'id_pengguna'  => $idPengguna,
            'media_url'    => $mediaUrl,
            'media_type'   => $mediaType,
            'status'       => $status,
            'is_read'      => 1,
            'fonnte_id'    => $fonnteId
        ];

        $this->db->set($data);
        $this->db->set('created_at', 'NOW()', FALSE);
        $this->db->insert($this->table_messages);
        return $this->db->insert_id();
    }

    /**
     * Dapatkan daftar kontak/percakapan
     */
    public function get_conversations($search = '', $tab = 'all', $limit = 50)
    {
        // 1. Ambil semua nomor unik dari pesan & sesi
        $sql = "SELECT DISTINCT phone_number FROM (
                    SELECT phone_number FROM {$this->table_messages}
                    UNION
                    SELECT phone_number FROM {$this->table_sessions}
                ) AS distinct_phones
                WHERE phone_number IS NOT NULL AND phone_number != '' AND phone_number NOT LIKE '%@g.us%'";

        $phonesResult = $this->db->query($sql)->result_array();
        if (empty($phonesResult)) {
            return [];
        }

        $conversations = [];

        foreach ($phonesResult as $row) {
            $phone = $row['phone_number'];
            $contactInfo = $this->resolve_contact_info($phone);

            // Ambil pesan terakhir
            $lastMsg = $this->db->query(
                "SELECT id, message_text, direction, media_url, media_type, created_at, status, is_read 
                 FROM {$this->table_messages} 
                 WHERE phone_number = ? 
                 ORDER BY id DESC LIMIT 1",
                [$phone]
            )->row_array();

            // Sesi 24 jam
            $session = $this->db->query(
                "SELECT id, last_incoming_at, last_message_text,
                        TIMESTAMPDIFF(MINUTE, last_incoming_at, NOW()) AS elapsed_minutes,
                        (1440 - TIMESTAMPDIFF(MINUTE, last_incoming_at, NOW())) AS remaining_minutes
                 FROM {$this->table_sessions}
                 WHERE phone_number = ?
                 LIMIT 1",
                [$phone]
            )->row_array();

            // Hitung pesan belum dibaca
            $unreadCountRow = $this->db->query(
                "SELECT COUNT(*) AS total FROM {$this->table_messages} 
                 WHERE phone_number = ? AND direction = 'in' AND is_read = 0",
                [$phone]
            )->row();
            $unreadCount = $unreadCountRow ? (int)$unreadCountRow->total : 0;

            $lastTime = !empty($lastMsg['created_at']) 
                ? $lastMsg['created_at'] 
                : (!empty($session['last_incoming_at']) ? $session['last_incoming_at'] : null);

            $lastText = !empty($lastMsg['message_text'])
                ? $lastMsg['message_text']
                : (!empty($lastMsg['media_url']) ? '[Lampiran Gambar/File]' : (!empty($session['last_message_text']) ? $session['last_message_text'] : 'Belum ada pesan'));

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

            $conv = [
                'phone'             => $phone,
                'phone_display'     => $this->format_phone_display($phone),
                'name'              => $contactInfo['name'],
                'type'              => $contactInfo['type'], // 'karyawan', 'customer', 'umum'
                'subtext'           => $contactInfo['subtext'],
                'avatar'            => $contactInfo['avatar'],
                'last_message'      => $lastText,
                'last_direction'    => !empty($lastMsg['direction']) ? $lastMsg['direction'] : 'in',
                'last_time'         => $lastTime,
                'last_time_display' => $this->format_time_label($lastTime),
                'unread_count'      => $unreadCount,
                'session_active'    => $isSessionActive,
                'remaining_minutes' => $remainingMinutes,
                'remaining_text'    => $remainingText
            ];

            // Filter search jika ada
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $match = (
                    stripos($conv['name'], $searchLower) !== false ||
                    strpos($conv['phone'], $searchLower) !== false ||
                    stripos($conv['subtext'], $searchLower) !== false ||
                    stripos($conv['last_message'], $searchLower) !== false
                );
                if (!$match) {
                    continue;
                }
            }

            // Filter tab jika dipilih ('unread', 'active', 'customer', 'karyawan')
            if ($tab === 'unread' && $conv['unread_count'] == 0) {
                continue;
            } elseif ($tab === 'active' && !$conv['session_active']) {
                continue;
            } elseif ($tab === 'customer' && $conv['type'] !== 'customer') {
                continue;
            } elseif ($tab === 'karyawan' && $conv['type'] !== 'karyawan') {
                continue;
            }

            $conversations[] = $conv;
        }

        // Urutkan berdasarkan waktu pesan/sesi terakhir DESC
        usort($conversations, function ($a, $b) {
            $timeA = !empty($a['last_time']) ? strtotime($a['last_time']) : 0;
            $timeB = !empty($b['last_time']) ? strtotime($b['last_time']) : 0;
            return $timeB - $timeA;
        });

        if ($limit > 0) {
            $conversations = array_slice($conversations, 0, $limit);
        }

        return $conversations;
    }

    /**
     * Dapatkan riwayat bubble chat untuk nomor tertentu
     */
    public function get_chat_messages($phone, $limit = 100, $lastId = 0)
    {
        $normalizedPhone = $this->normalize_phone($phone);
        if (empty($normalizedPhone)) {
            return [];
        }

        $this->db->select("m.*, p.nama AS sender_admin_name, p.jabatan AS sender_admin_jabatan");
        $this->db->from("{$this->table_messages} m");
        $this->db->join("pengguna p", "m.id_pengguna = p.pengguna_id", "left");
        $this->db->where("m.phone_number", $normalizedPhone);

        if ($lastId > 0) {
            $this->db->where("m.id >", (int)$lastId);
        }

        $this->db->order_by("m.id", "ASC");
        if ($limit > 0 && $lastId == 0) {
            $this->db->limit($limit);
        }

        $messages = $this->db->get()->result_array();

        $formatted = [];
        foreach ($messages as $m) {
            $formatted[] = [
                'id'           => (int)$m['id'],
                'direction'    => $m['direction'],
                'message'      => $m['message_text'],
                'media_url'    => $m['media_url'],
                'media_type'   => $m['media_type'],
                'sender_name'  => $m['direction'] === 'out' 
                    ? (!empty($m['sender_admin_name']) ? $m['sender_admin_name'] : 'Admin Office')
                    : (!empty($m['sender_name']) ? $m['sender_name'] : $this->format_phone_display($m['phone_number'])),
                'status'       => $m['status'],
                'is_read'      => (int)$m['is_read'],
                'time_raw'     => $m['created_at'],
                'time_display' => date('H:i', strtotime($m['created_at'])),
                'date_display' => date('d M Y', strtotime($m['created_at']))
            ];
        }

        return $formatted;
    }

    /**
     * Tandai semua pesan masuk dari nomor ini sebagai sudah dibaca
     */
    public function mark_messages_read($phone)
    {
        $normalizedPhone = $this->normalize_phone($phone);
        if (empty($normalizedPhone)) {
            return false;
        }

        $this->db->where('phone_number', $normalizedPhone);
        $this->db->where('direction', 'in');
        $this->db->where('is_read', 0);
        $this->db->update($this->table_messages, ['is_read' => 1]);
        return $this->db->affected_rows();
    }

    /**
     * Resolusi kontak berdasarkan nomor HP (Cari di pengguna -> customer -> sesi)
     */
    public function resolve_contact_info($phone)
    {
        $normalizedPhone = $this->normalize_phone($phone);
        $phone0 = '0' . substr($normalizedPhone, 2);
        $phone8 = substr($normalizedPhone, 2);

        // 1. Cek tabel Pengguna (Karyawan)
        $user = $this->db->query(
            "SELECT pengguna_id, nama, jabatan, no_hp, file_foto 
             FROM pengguna 
             WHERE status != '2' 
               AND (no_hp IN (?, ?, ?) OR no_hp_kantor IN (?, ?, ?))
             LIMIT 1",
            [$normalizedPhone, $phone0, $phone8, $normalizedPhone, $phone0, $phone8]
        )->row_array();

        if (!empty($user)) {
            $avatarUrl = !empty($user['file_foto']) ? base_url('assets/images/users/' . $user['file_foto']) : '';
            return [
                'name'    => $user['nama'],
                'type'    => 'karyawan',
                'subtext' => !empty($user['jabatan']) ? $user['jabatan'] : 'Karyawan Office',
                'avatar'  => $avatarUrl,
                'id'      => $user['pengguna_id']
            ];
        }

        // 2. Cek tabel Customer
        $last8 = substr($normalizedPhone, -8);
        $cust = $this->db->query(
            "SELECT id_customer, nama_customer, alamat_customer, contact 
             FROM customer 
             WHERE status != '0' 
               AND (REPLACE(REPLACE(REPLACE(contact, ' ', ''), '-', ''), '+', '') LIKE ?)
             LIMIT 1",
            ['%' . $last8 . '%']
        )->row_array();

        if (!empty($cust)) {
            return [
                'name'    => $cust['nama_customer'],
                'type'    => 'customer',
                'subtext' => !empty($cust['alamat_customer']) ? substr($cust['alamat_customer'], 0, 40) . '...' : 'Customer',
                'avatar'  => '',
                'id'      => $cust['id_customer']
            ];
        }

        // 3. Cek tabel tbl_whatsapp_sessions
        $session = $this->db->query(
            "SELECT nama FROM {$this->table_sessions} WHERE phone_number = ? LIMIT 1",
            [$normalizedPhone]
        )->row_array();

        if (!empty($session) && !empty($session['nama'])) {
            return [
                'name'    => $session['nama'],
                'type'    => 'umum',
                'subtext' => 'WhatsApp Contact',
                'avatar'  => '',
                'id'      => null
            ];
        }

        // 4. Fallback ke nomor HP
        return [
            'name'    => $this->format_phone_display($normalizedPhone),
            'type'    => 'umum',
            'subtext' => 'WhatsApp Baru',
            'avatar'  => '',
            'id'      => null
        ];
    }

    /**
     * Format label waktu human-friendly (Hari ini 14:02, Kemarin, atau 21 Okt)
     */
    private function format_time_label($datetime)
    {
        if (empty($datetime)) {
            return '';
        }

        $timestamp = strtotime($datetime);
        $today = strtotime('today');
        $yesterday = strtotime('yesterday');

        if ($timestamp >= $today) {
            return date('H:i', $timestamp);
        } elseif ($timestamp >= $yesterday) {
            return 'Kemarin';
        } elseif (date('Y', $timestamp) == date('Y')) {
            return date('d M', $timestamp);
        } else {
            return date('d/m/y', $timestamp);
        }
    }
}
