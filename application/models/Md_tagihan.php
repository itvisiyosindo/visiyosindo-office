<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_Tagihan extends CI_Model
{
  function getAllTagihan($status = null)
  {
    $this->db->select('
          tg.*,
          MAX(t.no_sj) as no_sj, 
          MAX(t.no_resi) as no_resi, 
          MAX(t.biaya) as biaya, 
          MAX(t.tgl_sampai) as tgl_sampai,
          MAX(e.nama_ekspedisi) as nama_ekspedisi,
          MAX(COALESCE(e.payment, e_kirim.payment)) as payment_term,
          MAX(p.nama) as nama_pengaju,
          MAX(cfg.role_label) as current_approver_role,
          MAX(cfg.is_active) as is_role_active,
          MAX(ts.tgl_penerima) as tgl_penerima,
          
          MAX(kd.kode) as kode_kirim,
          MAX(kd.nama_customer) as nama_customer_kirim,
          MAX(kd.alamat) as alamat_kirim,
          MAX(kd.ekspedisi) as ekspedisi_kirim,
          MAX(kd.no_resi) as no_resi_kirim,
          MAX(kd.tgl_sampai) as tgl_sampai_kirim,
          MAX(e_kirim.nama_ekspedisi) as nama_ekspedisi_kirim
      ');
    $this->db->from('tagihan_ekspedisi tg');
    $this->db->join('tracking_barang t', 'tg.id_tracking = t.id_tracking', 'left');
    $this->db->join('ekspedisi e', 't.id_ekspedisi = e.id_ekspedisi', 'left');
    $this->db->join('pengguna p', 'tg.created_by = p.pengguna_id', 'left');

    $this->db->join('tagihan_approval_config cfg', 'tg.status_approval = cfg.status_code', 'left');

    $this->db->join('tracking_status ts', 't.id_tracking = ts.id_tracking AND ts.id_status = 5', 'left');

    // Join untuk kirim dokumen
    $this->db->join('kirim_dokumen kd', 'tg.id_kirim = kd.id', 'left');
    $this->db->join('ekspedisi e_kirim', 'kd.id_ekspedisi = e_kirim.id_ekspedisi', 'left');

    if ($status !== null) {
      $this->db->where('tg.status_approval', $status);
    }

    $this->db->group_by('tg.id_tagihan');

    $this->db->order_by('tg.updated_at', 'DESC');

    $result = $this->db->get()->result();

    // Tambahkan kalkulasi jatuh tempo di PHP untuk setiap row
    foreach ($result as $row) {
      $jatuh_tempo_data = hitung_tanggal_jatuh_tempo($row->tanggal_invoice, $row->payment_term, $row->status_bayar);
      $row->tanggal_kontrak_jatuh_tempo = $jatuh_tempo_data['tanggal_kontrak_jatuh_tempo'];
      $row->tanggal_jatuh_tempo = $jatuh_tempo_data['tanggal_jatuh_tempo'];
      $row->status_jatuh_tempo = $jatuh_tempo_data['status_jatuh_tempo'];
      $row->hari_tersisa = $jatuh_tempo_data['hari_tersisa'];
    }

    return $result;
  }

  function getDetailFull($id_tagihan)
  {
    $this->db->select('
            tg.*, 
            t.id_tracking, t.id_pengeluaran_barang, t.id_pengiriman_stok, t.id_serah_terima_barang,
            t.tracking_type, t.no_sj, t.no_resi, t.link_resi, t.biaya as biaya_real, 
            t.nama_barang, t.alamat_penerima, t.tgl_sampai, t.pic_penerima,
            
            /* Ambil Inputan Tanggal Diterima Manual */
            (SELECT ts_fix.tgl_penerima FROM tracking_status ts_fix 
             WHERE ts_fix.id_tracking = t.id_tracking AND ts_fix.id_status = 5 
             ORDER BY ts_fix.created_at DESC LIMIT 1) as tgl_penerima,

            /* Ambil Waktu Sistem saat status diubah ke Diterima (untuk cadangan) */
            (SELECT ts_fix.created_at FROM tracking_status ts_fix 
             WHERE ts_fix.id_tracking = t.id_tracking AND ts_fix.id_status = 5 
             ORDER BY ts_fix.created_at DESC LIMIT 1) as tgl_log_status_5,
            
            c.nama_customer,
            p.nama as nama_pengaju, p.jabatan as jabatan_pengaju, p.no_hp as hp_pengaju,
            e.nama_ekspedisi, e.alamat_ekspedisi, e.contact as kontak_ekspedisi,
            e.nama_pic as pic_ekspedisi, e.jabatan as jabatan_pic, e.kerjasama,
            e.payment as payment_term, e.pph23, e.mou as link_mou, e.legalitas as link_legalitas,

            pb.no_po, pb.no_pengiriman, pb.tgl_keluar as tgl_keluar_gudang, pb.file_pendukung as link_file_po,
            g.nama_gudang, ps.no_pemindahan, ps.tgl_pengiriman as tgl_pengiriman_stok,
            g_asal.nama_gudang as nama_gudang_asal, g_tujuan.nama_gudang as nama_gudang_tujuan,
            stb.kode_stb, stb.kota_pengajuan as kota_stb, stb.tgl_pengajuan as tgl_stb,
            c_pihak1.nama_customer as nama_pihak1, c_pihak2.nama_customer as nama_pihak2
        ');

    $this->db->from('tagihan_ekspedisi tg');
    $this->db->join('tracking_barang t', 't.id_tracking = tg.id_tracking');
    $this->db->join('customer c', 't.id_customer = c.id_customer', 'left');
    $this->db->join('ekspedisi e', 't.id_ekspedisi = e.id_ekspedisi', 'left');
    $this->db->join('pengguna p', 'p.pengguna_id = tg.created_by', 'left');
    $this->db->join('pengeluaran_barang pb', 't.id_pengeluaran_barang = pb.id_pengeluaran_barang', 'left');
    $this->db->join('gudang g', 't.id_gudang = g.id_gudang', 'left');
    $this->db->join('pengiriman_stok ps', 't.id_pengiriman_stok = ps.id_pengiriman_stok', 'left');
    $this->db->join('gudang g_asal', 'ps.id_gudang_asal = g_asal.id_gudang', 'left');
    $this->db->join('gudang g_tujuan', 'ps.id_gudang_tujuan = g_tujuan.id_gudang', 'left');
    $this->db->join('surat_stb stb', 't.id_serah_terima_barang = stb.id_stb', 'left');
    $this->db->join('customer c_pihak1', 'stb.id_pihak1 = c_pihak1.id_customer', 'left');
    $this->db->join('customer c_pihak2', 'stb.id_customer = c_pihak2.id_customer', 'left');

    $this->db->where('tg.id_tagihan', $id_tagihan);
    return $this->db->get()->row();
  }

  function getById($id_tagihan)
  {
    $this->db->select('tg.*, t.no_sj, t.id_tracking');
    $this->db->from('tagihan_ekspedisi tg');
    $this->db->join('tracking_barang t', 'tg.id_tracking = t.id_tracking');
    $this->db->where('tg.id_tagihan', $id_tagihan);
    return $this->db->get()->row();
  }

  function checkExisting($id_tracking)
  {
    return $this->db->get_where('tagihan_ekspedisi', ['id_tracking' => $id_tracking])->row();
  }

  function insert($data)
  {
    $this->db->insert('tagihan_ekspedisi', $data);
    return $this->db->insert_id();
  }

  function update($id, $data)
  {
    $this->db->where('id_tagihan', $id);
    $this->db->update('tagihan_ekspedisi', $data);
  }

  function updateTrackingBiaya($id_tracking, $biaya)
  {
    $this->db->where('id_tracking', $id_tracking);
    $this->db->update('tracking_barang', ['biaya' => $biaya]);
  }

  function getTrackingById($id_tracking)
  {
    $this->db->select('
            t.*, 
            
            c.nama_customer, 
            g.nama_gudang,
            
            e.nama_ekspedisi,
            e.alamat_ekspedisi,
            e.contact as kontak_ekspedisi,
            e.nama_pic as pic_ekspedisi,
            e.jabatan as jabatan_pic,
            e.kerjasama,
            e.mou as link_mou,              
            e.legalitas as link_legalitas,  
            e.payment as payment_term,      
            e.pph23,                        
            e.min_berat,                    
            
            pb.no_po,
            pb.no_pengiriman,
            pb.tgl_keluar as tgl_keluar_gudang,
            pb.keterangan as ket_pengeluaran,
            pb.file_pendukung as link_file_po,
            pb.status_email_tiki,
            
            ps.no_pemindahan,
            ps.tgl_pengiriman as tgl_pengiriman_stok,
            ps.keterangan as ket_pengiriman_stok,
            g_asal.nama_gudang as nama_gudang_asal,
            g_asal.alamat_gudang as alamat_gudang_asal,
            g_tujuan.nama_gudang as nama_gudang_tujuan,
            g_tujuan.alamat_gudang as alamat_gudang_tujuan,
            
            stb.kode_stb,
            stb.kota_pengajuan as kota_stb,
            stb.tgl_pengajuan as tgl_pengajuan_stb,
            c_pihak1.nama_customer as nama_pihak1,
            c_pihak1.alamat_customer as alamat_pihak1,
            c_pihak2.nama_customer as nama_pihak2,
            c_pihak2.alamat_customer as alamat_pihak2,
            p_stb.nama as nama_pengaju_stb
        ');
    $this->db->from('tracking_barang t');

    // Join Table untuk Pengeluaran Barang
    $this->db->join('customer c', 't.id_customer = c.id_customer', 'left');
    $this->db->join('gudang g', 't.id_gudang = g.id_gudang', 'left');
    $this->db->join('ekspedisi e', 't.id_ekspedisi = e.id_ekspedisi', 'left');
    $this->db->join('pengeluaran_barang pb', 't.id_pengeluaran_barang = pb.id_pengeluaran_barang', 'left');

    // Join Table untuk Pengiriman Stok
    $this->db->join('pengiriman_stok ps', 't.id_pengiriman_stok = ps.id_pengiriman_stok', 'left');
    $this->db->join('gudang g_asal', 'ps.id_gudang_asal = g_asal.id_gudang', 'left');
    $this->db->join('gudang g_tujuan', 'ps.id_gudang_tujuan = g_tujuan.id_gudang', 'left');

    // Join Table untuk Serah Terima Barang (STTB)
    $this->db->join('surat_stb stb', 't.id_serah_terima_barang = stb.id_stb', 'left');
    $this->db->join('customer c_pihak1', 'stb.id_pihak1 = c_pihak1.id_customer', 'left');
    $this->db->join('customer c_pihak2', 'stb.id_customer = c_pihak2.id_customer', 'left');
    $this->db->join('pengguna p_stb', 'stb.id_pengaju = p_stb.pengguna_id', 'left');

    $this->db->where('t.id_tracking', $id_tracking);

    return $this->db->get()->row();
  }

  function getByUser($id_user)
  {
    $this->db->select('
          tg.*,
          t.no_sj, t.no_resi, t.biaya, t.tgl_sampai,
          e.nama_ekspedisi,
          p.nama as nama_pengaju,
          cfg.role_label as current_approver_role
      ');
    $this->db->from('tagihan_ekspedisi tg');
    $this->db->join('tracking_barang t', 'tg.id_tracking = t.id_tracking');
    $this->db->join('ekspedisi e', 't.id_ekspedisi = e.id_ekspedisi', 'left');
    $this->db->join('pengguna p', 'tg.created_by = p.pengguna_id', 'left');
    $this->db->join('tagihan_approval_config cfg', 'tg.status_approval = cfg.status_code', 'left');

    // [FILTER KHUSUS] Hanya data milik user ini
    $this->db->where('tg.created_by', $id_user);

    $this->db->order_by('tg.updated_at', 'DESC');
    return $this->db->get()->result();
  }

  function getHistoryStatus($id_tracking)
  {
    $this->db->select('ts.*, p.nama as nama_pengguna');
    $this->db->from('tracking_status ts');
    $this->db->join('pengguna p', 'ts.id_pengguna = p.pengguna_id', 'left');
    $this->db->where('ts.id_tracking', $id_tracking);
    $this->db->order_by('ts.created_at', 'DESC');
    return $this->db->get()->result();
  }

  /**
   * Get All Tagihan with Full Detail (for Print/Export)
   */
  function getAllTagihanForExport($filters = [])
  {
    // Build WHERE conditions for filters
    $where_status = !empty($filters['status']) ? "AND tg.status_approval = " . intval($filters['status']) : "";
    $where_start_date = !empty($filters['start_date']) ? "AND tg.tanggal_invoice >= '" . $this->db->escape_str($filters['start_date']) . "'" : "";
    $where_end_date = !empty($filters['end_date']) ? "AND tg.tanggal_invoice <= '" . $this->db->escape_str($filters['end_date']) . "'" : "";
    $where_ekspedisi_tracking = !empty($filters['ekspedisi']) ? "AND t.id_ekspedisi = " . intval($filters['ekspedisi']) : "";
    $where_ekspedisi_kirim = !empty($filters['ekspedisi']) ? "AND kd.id_ekspedisi = " . intval($filters['ekspedisi']) : "";

    // QUERY 1: Tagihan dari tracking_barang
    $sql_tracking = "
      SELECT 
        tg.*,
        t.no_sj, 
        t.no_resi, 
        t.biaya as biaya_real, 
        t.tgl_sampai,
        e.nama_ekspedisi,
        p.nama as nama_pengaju,
        p.jabatan as jabatan_pengaju,
        c.nama_customer,
        cfg.role_label as current_approver_role,
        NULL as kode_kirim,
        NULL as nama_ekspedisi_kirim,
        NULL as ekspedisi_kirim,
        'tracking_barang' as tagihan_type
      FROM tagihan_ekspedisi tg
      LEFT JOIN tracking_barang t ON tg.id_tracking = t.id_tracking
      LEFT JOIN ekspedisi e ON t.id_ekspedisi = e.id_ekspedisi
      LEFT JOIN pengguna p ON tg.created_by = p.pengguna_id
      LEFT JOIN customer c ON t.id_customer = c.id_customer
      LEFT JOIN tagihan_approval_config cfg ON tg.status_approval = cfg.status_code
      WHERE tg.id_tracking IS NOT NULL
      {$where_status}
      {$where_ekspedisi_tracking}
      {$where_start_date}
      {$where_end_date}
    ";

    // QUERY 2: Tagihan dari kirim_dokumen
    $sql_kirim = "
      SELECT 
        tg.*,
        NULL as no_sj,
        kd.no_resi,
        kd.biaya as biaya_real,
        kd.tgl_sampai,
        e.nama_ekspedisi,
        p.nama as nama_pengaju,
        p.jabatan as jabatan_pengaju,
        kd.nama_customer,
        cfg.role_label as current_approver_role,
        kd.kode as kode_kirim,
        e.nama_ekspedisi as nama_ekspedisi_kirim,
        kd.ekspedisi as ekspedisi_kirim,
        'kirim_dokumen' as tagihan_type
      FROM tagihan_ekspedisi tg
      LEFT JOIN kirim_dokumen kd ON tg.id_kirim = kd.id
      LEFT JOIN ekspedisi e ON kd.id_ekspedisi = e.id_ekspedisi
      LEFT JOIN pengguna p ON tg.created_by = p.pengguna_id
      LEFT JOIN tagihan_approval_config cfg ON tg.status_approval = cfg.status_code
      WHERE tg.id_kirim IS NOT NULL
      {$where_status}
      {$where_ekspedisi_kirim}
      {$where_start_date}
      {$where_end_date}
    ";

    // UNION query dan order by
    $sql = "({$sql_tracking}) UNION ALL ({$sql_kirim}) ORDER BY tanggal_invoice DESC";

    return $this->db->query($sql)->result();
  }

  // =========================================================================
  // METHOD UNTUK KIRIM DOKUMEN
  // =========================================================================

  /**
   * Cek existing tagihan untuk kirim dokumen
   */
  function checkExistingKirim($id_kirim)
  {
    return $this->db->get_where('tagihan_ekspedisi', ['id_kirim' => $id_kirim])->row();
  }

  /**
   * Ambil data kirim dokumen by ID
   */
  function getKirimById($id_kirim)
  {
    $this->db->select('
            kd.*, 
            COALESCE(e.nama_ekspedisi, e2.nama_ekspedisi) as nama_ekspedisi,
            COALESCE(e.alamat_ekspedisi, e2.alamat_ekspedisi) as alamat_ekspedisi,
            COALESCE(e.contact, e2.contact) as kontak_ekspedisi,
            COALESCE(e.nama_pic, e2.nama_pic) as pic_ekspedisi,
            COALESCE(e.jabatan, e2.jabatan) as jabatan_pic,
            COALESCE(e.kerjasama, e2.kerjasama) as kerjasama,
            COALESCE(e.mou, e2.mou) as link_mou,              
            COALESCE(e.legalitas, e2.legalitas) as link_legalitas,  
            COALESCE(e.payment, e2.payment) as payment_term,      
            COALESCE(e.pph23, e2.pph23) as pph23,
            COALESCE(e.min_berat, e2.min_berat) as min_berat,
            COALESCE(e.coverage, e2.coverage) as coverage,
            COALESCE(e.keterangan, e2.keterangan) as keterangan_ekspedisi,
            COALESCE(e.link_tracking, e2.link_tracking) as link_tracking_ekspedisi,
            COALESCE(e.identitas, e2.identitas) as link_identitas,
            COALESCE(e.id_ekspedisi, e2.id_ekspedisi) as ekspedisi_id_found,

            (
                SELECT ks.id_status 
                FROM kirim_status ks 
                WHERE ks.id_kirim = kd.id 
                ORDER BY ks.created_at DESC 
                LIMIT 1
            ) as status_tracking
        ');
    $this->db->from('kirim_dokumen kd');
    // Join by id_ekspedisi if exists
    $this->db->join('ekspedisi e', 'kd.id_ekspedisi = e.id_ekspedisi', 'left');
    // Also join by nama ekspedisi as fallback
    $this->db->join('ekspedisi e2', 'LOWER(TRIM(kd.ekspedisi)) = LOWER(TRIM(e2.nama_ekspedisi)) AND kd.id_ekspedisi IS NULL', 'left');
    $this->db->where('kd.id', $id_kirim);
    return $this->db->get()->row();
  }

  /**
   * Update biaya kirim dokumen
   */
  function updateKirimBiaya($id_kirim, $biaya)
  {
    $this->db->where('id', $id_kirim);
    $this->db->update('kirim_dokumen', ['biaya' => $biaya]);
  }

  /**
   * Ambil history status kirim dokumen
   */
  function getKirimHistoryStatus($id_kirim)
  {
    $this->db->select('ks.*, p.nama as nama_pengguna');
    $this->db->from('kirim_status ks');
    $this->db->join('pengguna p', 'ks.id_pengguna = p.pengguna_id', 'left');
    $this->db->where('ks.id_kirim', $id_kirim);
    $this->db->order_by('ks.created_at', 'DESC');
    return $this->db->get()->result();
  }

  /**
   * Ambil detail tagihan dengan data kirim dokumen
   */
  function getDetailFullKirim($id_tagihan)
  {
    $this->db->select('
        tg.*, 
        
        kd.id as id_kirim,
        kd.kode,
        kd.nama_customer,
        kd.alamat,
        kd.pic,
        kd.asal,
        kd.marketing,
        kd.keterangan,
        kd.link_doc,
        kd.ekspedisi as ekspedisi_nama_lama,
        kd.no_resi,
        kd.link_resi,
        kd.biaya as biaya_real,
        kd.tgl_kirim,
        kd.tgl_sampai,
        kd.id_pengguna as id_pembuat_kirim,
        
        p.nama as nama_pengaju, 
        p.jabatan as jabatan_pengaju,
        p.no_hp as hp_pengaju,
        
        e.nama_ekspedisi,
        e.alamat_ekspedisi,
        e.contact as kontak_ekspedisi,
        e.nama_pic as pic_ekspedisi,
        e.jabatan as jabatan_pic,
        e.kerjasama,
        e.payment as payment_term,
        e.pph23,
        e.mou as link_mou,
        e.legalitas as link_legalitas,
        
        (
            SELECT ks.id_status 
            FROM kirim_status ks 
            WHERE ks.id_kirim = kd.id 
            ORDER BY ks.created_at DESC 
            LIMIT 1
        ) as status_tracking,
        (
            SELECT ks.tgl_penerima 
            FROM kirim_status ks 
            WHERE ks.id_kirim = kd.id AND ks.id_status = 5
            ORDER BY ks.created_at DESC 
            LIMIT 1
        ) as tgl_penerima
    ');

    $this->db->from('tagihan_ekspedisi tg');
    $this->db->join('kirim_dokumen kd', 'tg.id_kirim = kd.id');
    $this->db->join('pengguna p', 'p.pengguna_id = tg.created_by', 'left');
    $this->db->join('ekspedisi e', 'kd.id_ekspedisi = e.id_ekspedisi', 'left');

    $this->db->where('tg.id_tagihan', $id_tagihan);

    return $this->db->get()->row();
  }

  /**
   * Ambil data tagihan by ID (include kirim dokumen)
   */
  function getByIdWithKirim($id_tagihan)
  {
    $this->db->select('tg.*, t.no_sj, t.id_tracking, kd.kode as kode_kirim, kd.id as id_kirim');
    $this->db->from('tagihan_ekspedisi tg');
    $this->db->join('tracking_barang t', 'tg.id_tracking = t.id_tracking', 'left');
    $this->db->join('kirim_dokumen kd', 'tg.id_kirim = kd.id', 'left');
    $this->db->where('tg.id_tagihan', $id_tagihan);
    return $this->db->get()->row();
  }
}
