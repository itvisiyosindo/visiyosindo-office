<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tagihan_Config extends CI_Controller
{
  function __construct()
  {
    parent::__construct();

    // Mengizinkan Administrator ATAU User ID 759 (IT/Dev)
    if (!isAdmin() && sessPenggunaId() != '759') {
      redirect('dashboard');
    }

    $this->load->model('md_tagihan_config');
    $this->load->helper('whatsapp_helper');
  }

  public function index()
  {
    $page_data['configs']    = $this->md_tagihan_config->getAllConfig();
    $page_data['logs']       = $this->md_tagihan_config->getAllLogs();
    $page_data['page_title'] = 'Konfigurasi Approval & Log Notifikasi';
    $page_data['page_name']  = 'tagihan/config/v_config_approval';
    $page_data['page_desc']  = 'Fitur untuk mengatur alur approval tagihan ekspedisi serta melihat riwayat pengiriman notifikasi WhatsApp.';


    $page_data['switch']     = 'helpdesk';
    $this->load->view('index', $page_data);
  }

  function id_navbar()
  {
    return "helpdesk";
  }

  public function update_setting()
  {
    if (!$this->input->is_ajax_request()) {
      exit('No direct script access allowed');
    }

    $id_config = $this->input->post('id_config');
    $data = [
      'role_label'   => $this->input->post('role_label'),
      'notif_number' => $this->input->post('notif_number'),
      'can_edit'     => $this->input->post('can_edit'),
      'is_active'    => $this->input->post('is_active')
    ];

    $this->md_tagihan_config->updateConfig($id_config, $data);

    echo json_encode(['status' => 'success', 'message' => 'Konfigurasi berhasil diperbarui!']);
  }

  // Fitur Kirim Ulang WA (Resend) - REFACTORED
  // Mengambil data terkini dari database dan menggunakan template yang benar
  public function resend_wa($id_log)
  {
    $log = $this->md_tagihan_config->getLogById($id_log);

    if (!$log) {
      $this->session->set_flashdata('error', 'Data log tidak ditemukan');
      redirect('tagihan_config');
      return;
    }

    // Ambil data tagihan terkini dari database
    $tagihan = $this->db->select('
        tg.*,
        COALESCE(t.no_sj, stb.kode_stb, kd.kode) as no_sj,
        COALESCE(e.nama_ekspedisi, e_kd.nama_ekspedisi, kd.ekspedisi) as nama_ekspedisi,
        p.nama as nama_pengaju,
        cfg.role_label as current_approver_role
      ')
      ->from('tagihan_ekspedisi tg')
      ->join('tracking_barang t', 'tg.id_tracking = t.id_tracking', 'left')
      ->join('surat_stb stb', 't.id_serah_terima_barang = stb.id_stb', 'left')
      ->join('ekspedisi e', 't.id_ekspedisi = e.id_ekspedisi', 'left')
      ->join('kirim_dokumen kd', 'tg.id_kirim = kd.id', 'left')
      ->join('ekspedisi e_kd', 'kd.id_ekspedisi = e_kd.id_ekspedisi', 'left')
      ->join('pengguna p', 'tg.created_by = p.pengguna_id', 'left')
      ->join('tagihan_approval_config cfg', 'tg.status_approval = cfg.status_code', 'left')
      ->where('tg.id_tagihan', $log->id_tagihan)
      ->get()->row();

    if (!$tagihan) {
      $this->session->set_flashdata('error', 'Data tagihan tidak ditemukan atau sudah dihapus');
      redirect('tagihan_config');
      return;
    }

    // Tentukan action status berdasarkan status approval
    $action_status = 'Mengajukan';
    if ($tagihan->status_approval == 0) {
      $action_status = 'Mengirim Revisi';
    } elseif ($tagihan->status_approval == 99) {
      $action_status = 'Menolak';
    } elseif ($tagihan->status_approval > 0 && $tagihan->status_approval < 5) {
      $action_status = 'Menyetujui';
    } elseif ($tagihan->status_approval == 5) {
      $action_status = 'Menyelesaikan Approval';
    }

    // Generate history approval list
    $approval_configs = $this->md_tagihan_config->getAllConfig();
    $history_list = "";

    if ($tagihan->status_approval == 5) {
      // Semua disetujui
      foreach ($approval_configs as $cfg) {
        if ($cfg->is_active) {
          $history_list .= " - " . $cfg->role_label . " (*Disetujui*)\n";
        }
      }
    } else {
      // Generate berdasarkan status saat ini
      foreach ($approval_configs as $cfg) {
        if (!$cfg->is_active) continue;

        if ($cfg->status_code < $tagihan->status_approval) {
          $history_list .= " - " . $cfg->role_label . " (*Disetujui*)\n";
        } elseif ($cfg->status_code == $tagihan->status_approval) {
          $history_list .= " - " . $cfg->role_label . " (*Menunggu*)\n";
        } else {
          $history_list .= " - " . $cfg->role_label . "\n";
        }
      }
    }

    // Siapkan Data WA menggunakan template yang benar
    $waData = [
      'target_phone'    => $log->target_number,
      'target_role'     => $log->target_role,
      'nama_pengaju'    => $tagihan->nama_pengaju ?? 'User',
      'action_status'   => $action_status,
      'no_sj'           => $tagihan->no_sj ?? '-',
      'ekspedisi'       => $tagihan->nama_ekspedisi ?? '-',
      'biaya_formatted' => "Rp " . number_format(($tagihan->nilai_tagihan ?? 0), 0, ',', '.'),
      'history_info'    => $history_list,
      'link_invoice'    => $tagihan->link_invoice ?? '',
      'link_url'        => base_url('tagihan'),
      'catatan'         => $tagihan->catatan_revisi ?? ''
    ];

    // Kirim menggunakan helper waTagihanApproval
    $result = waTagihanApproval($waData);

    // Update Counter Resend
    $this->md_tagihan_config->incrementResendCount($id_log);

    if ($result['success']) {
      $this->session->set_flashdata('success', 'Notifikasi berhasil dikirim ulang ke ' . $log->target_number . ' dengan data terkini.');
    } else {
      $this->session->set_flashdata('warning', 'Notifikasi dikirim dengan metode backup ke ' . $log->target_number . '. ' . ($result['message'] ?? ''));
    }

    redirect('tagihan_config');
  }
}
