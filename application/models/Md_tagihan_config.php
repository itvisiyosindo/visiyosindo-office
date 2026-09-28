<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_Tagihan_Config extends CI_Model
{
  // --- BAGIAN CONFIG ---
  function getAllConfig()
  {
    $this->db->order_by('level_order', 'ASC');
    return $this->db->get('tagihan_approval_config')->result();
  }

  function getConfigByStatus($status_code)
  {
    return $this->db->get_where('tagihan_approval_config', ['status_code' => $status_code])->row();
  }

  function updateConfig($id, $data)
  {
    $this->db->where('id_config', $id);
    $this->db->update('tagihan_approval_config', $data);
  }

  // [BARU] Ambil Level Aktif Paling Awal (Untuk submit pertama kali)
  function getFirstActiveLevel()
  {
    $this->db->where('is_active', 1); // HANYA YANG AKTIF
    $this->db->order_by('level_order', 'ASC');
    $this->db->limit(1);
    return $this->db->get('tagihan_approval_config')->row();
  }

  // [BARU] Ambil Level Aktif Berikutnya setelah level saat ini
  function getNextActiveLevel($current_level_order)
  {
    $this->db->where('is_active', 1); // HANYA YANG AKTIF
    $this->db->where('level_order >', $current_level_order); // Level di atasnya
    $this->db->order_by('level_order', 'ASC'); // Cari yang paling dekat
    $this->db->limit(1);
    return $this->db->get('tagihan_approval_config')->row();
  }

  // [UPDATE] Ambil Semua Config TAPI yang aktif saja (Untuk View Timeline)
  function getAllActiveConfig()
  {
    $this->db->where('is_active', 1);
    $this->db->order_by('level_order', 'ASC');
    return $this->db->get('tagihan_approval_config')->result();
  }

  // --- BAGIAN LOGS ---
  function logNotification($id_tagihan, $target_name, $number, $message)
  {
    $data = [
      'id_tagihan'    => $id_tagihan,
      'target_name'   => $target_name,
      'target_number' => $number,
      'message'       => $message,
      'status'        => 'sent',
      'sent_at'       => date('Y-m-d H:i:s')
    ];
    $this->db->insert('tagihan_notif_logs', $data);
    return $this->db->insert_id();
  }

  function getAllLogs()
  {
    $this->db->select('l.*, t.no_sj');
    $this->db->from('tagihan_notif_logs l');
    $this->db->join('tagihan_ekspedisi tg', 'l.id_tagihan = tg.id_tagihan', 'left');
    $this->db->join('tracking_barang t', 'tg.id_tracking = t.id_tracking', 'left');
    $this->db->order_by('l.sent_at', 'DESC');
    return $this->db->get()->result();
  }

  function getLogById($id_log)
  {
    return $this->db->get_where('tagihan_notif_logs', ['id_log' => $id_log])->row();
  }

  function getConfigByLevel($level)
  {
    return $this->db->get_where('tagihan_approval_config', ['level_order' => $level])->row();
  }

  function getFirstLevel()
  {
    $this->db->order_by('level_order', 'ASC');
    $this->db->limit(1);
    return $this->db->get('tagihan_approval_config')->row();
  }

  function incrementResendCount($id_log)
  {
    $this->db->set('resent_count', 'resent_count+1', FALSE);
    $this->db->set('sent_at', date('Y-m-d H:i:s')); // Update waktu terakhir kirim
    $this->db->where('id_log', $id_log);
    $this->db->update('tagihan_notif_logs');
  }

  function checkJabatanIsApprover($jabatan)
  {
    if (empty($jabatan)) return false;

    // Gunakan group start/end untuk logika OR yang aman
    $this->db->group_start();
    // 1. Cek Exact Match (Sama persis)
    $this->db->where('role_label', $jabatan);

    // 2. ATAU Cek Mirip (Opsional, untuk menangani kasus '& PJT')
    // Ini akan mencocokkan jika jabatan user mengandung kata dari role_label
    // Hati-hati: Pastikan tidak ada role yang namanya terlalu mirip satu sama lain
    $this->db->or_like('role_label', $jabatan);
    $this->db->or_like("'$jabatan'", 'role_label', false); // Cek kebalikan: jika role label adalah bagian dari jabatan user
    $this->db->group_end();

    $this->db->where('is_active', 1); // Pastikan config aktif

    $query = $this->db->get('tagihan_approval_config');

    return $query->num_rows() > 0;
  }
}
