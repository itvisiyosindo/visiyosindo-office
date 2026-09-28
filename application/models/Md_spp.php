<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_Spp extends CI_Model
{
    // =========================================================================
    // SPP CRUD
    // =========================================================================

    /**
     * Get All SPP dengan detail
     */
    function getAllSpp($status = null)
    {
        $this->db->select('
            spp.*,
            p.nama as nama_pengaju,
            p.jabatan as jabatan_pengaju,
            cfg.role_label as current_approver_role,
            
            MAX(tg.tanggal_invoice) as latest_tanggal_invoice,
            MAX(tg.status_bayar) as latest_status_bayar,
            MAX(e.payment) as latest_payment_term
        ');
        $this->db->from('spp');
        $this->db->join('pengguna p', 'spp.created_by = p.pengguna_id', 'left');
        $this->db->join('spp_approval_config cfg', 'spp.status_approval = cfg.status_code', 'left');

        // Join dengan tagihan untuk mendapatkan info jatuh tempo
        $this->db->join('tagihan_ekspedisi tg', 'spp.id_spp = tg.id_spp', 'left');
        $this->db->join('tracking_barang t', 'tg.id_tracking = t.id_tracking', 'left');
        $this->db->join('ekspedisi e', 't.id_ekspedisi = e.id_ekspedisi', 'left');

        if ($status !== null) {
            $this->db->where('spp.status_approval', $status);
        }

        $this->db->group_by('spp.id_spp');
        $this->db->order_by('spp.created_at', 'DESC');

        $results = $this->db->get()->result();

        // Post-process: Hitung jatuh tempo untuk setiap SPP
        foreach ($results as $row) {
            if (!empty($row->latest_tanggal_invoice) && !empty($row->latest_payment_term)) {
                $jatuh_tempo = hitung_tanggal_jatuh_tempo(
                    $row->latest_tanggal_invoice,
                    $row->latest_payment_term,
                    $row->latest_status_bayar ?? 'belum_dibayar'
                );

                $row->status_jatuh_tempo = $jatuh_tempo['status_jatuh_tempo'];
                $row->tanggal_jatuh_tempo = $jatuh_tempo['tanggal_jatuh_tempo'];
                $row->hari_tersisa = $jatuh_tempo['hari_tersisa'];
            } else {
                $row->status_jatuh_tempo = 'Tidak Ada Info Payment';
                $row->tanggal_jatuh_tempo = null;
                $row->hari_tersisa = null;
            }
        }

        return $results;
    }

    /**
     * Get SPP by ID
     */
    function getById($id_spp)
    {
        $this->db->select('
            spp.*,
            p.nama as nama_pengaju,
            p.jabatan as jabatan_pengaju,
            p.no_hp as hp_pengaju,
            cfg.role_label as current_approver_role
        ');
        $this->db->from('spp');
        $this->db->join('pengguna p', 'spp.created_by = p.pengguna_id', 'left');
        $this->db->join('spp_approval_config cfg', 'spp.status_approval = cfg.status_code', 'left');
        $this->db->where('spp.id_spp', $id_spp);
        return $this->db->get()->row();
    }

    /**
     * Get tagihan yang sudah APPROVED (status 5) dan belum diajukan SPP
     * Group by no_invoice
     */
    function getApprovedTagihanGrouped()
    {
        $this->db->select('
            tg.no_invoice,
            COUNT(tg.id_tagihan) as jumlah_tagihan,
            SUM(COALESCE(tg.total_tagihan, tg.nilai_tagihan + COALESCE(tg.biaya_asuransi, 0))) as total_nilai,
            GROUP_CONCAT(tg.id_tagihan) as tagihan_ids,
            MIN(tg.tanggal_invoice) as tanggal_invoice_awal,
            MAX(tg.tanggal_invoice) as tanggal_invoice_akhir,
            
            MAX(COALESCE(e.nama_ekspedisi, e_kd.nama_ekspedisi, kd.ekspedisi)) as nama_ekspedisi
        ');
        $this->db->from('tagihan_ekspedisi tg');

        $this->db->join('tracking_barang t', 'tg.id_tracking = t.id_tracking', 'left');
        $this->db->join('ekspedisi e', 't.id_ekspedisi = e.id_ekspedisi', 'left');

        $this->db->join('kirim_dokumen kd', 'tg.id_kirim = kd.id', 'left');
        $this->db->join('ekspedisi e_kd', 'kd.id_ekspedisi = e_kd.id_ekspedisi', 'left');

        $this->db->where('tg.status_approval', 5);
        $this->db->where('tg.id_spp IS NULL');
        $this->db->where('tg.no_invoice IS NOT NULL');
        $this->db->where('tg.no_invoice !=', '');

        $this->db->group_by('tg.no_invoice');
        $this->db->order_by('tg.no_invoice', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Get detail tagihan berdasarkan no_invoice
     */
    function getTagihanByNoInvoice($no_invoice)
    {
        $this->db->select('
            tg.*,
            t.no_sj, 
            stb.kode_stb,
            kd.kode as kode_kirim,
            
            COALESCE(t.no_resi, kd.no_resi) as no_resi,
            COALESCE(t.tgl_sampai, kd.tgl_sampai) as tgl_sampai,
            COALESCE(e.nama_ekspedisi, e_kd.nama_ekspedisi, kd.ekspedisi) as nama_ekspedisi,
            COALESCE(c.nama_customer, kd.nama_customer) as nama_customer,
            
            p.nama as nama_pengaju
        ');
        $this->db->from('tagihan_ekspedisi tg');

        $this->db->join('tracking_barang t', 'tg.id_tracking = t.id_tracking', 'left');
        $this->db->join('surat_stb stb', 't.id_serah_terima_barang = stb.id_stb', 'left');
        $this->db->join('ekspedisi e', 't.id_ekspedisi = e.id_ekspedisi', 'left');
        $this->db->join('customer c', 't.id_customer = c.id_customer', 'left');

        $this->db->join('kirim_dokumen kd', 'tg.id_kirim = kd.id', 'left');
        $this->db->join('ekspedisi e_kd', 'kd.id_ekspedisi = e_kd.id_ekspedisi', 'left');

        $this->db->join('pengguna p', 'tg.created_by = p.pengguna_id', 'left');

        $this->db->where('tg.no_invoice', $no_invoice);
        $this->db->where('tg.status_approval', 5);
        $this->db->where('tg.id_spp IS NULL');
        $this->db->order_by('tg.tanggal_invoice', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Get detail tagihan dalam SPP
     * UPDATED: FIX JOIN TYPE (LEFT) for tracking_barang
     */
    function getTagihanBySpp($id_spp)
    {
        $this->db->select('
            tg.*,
            MAX(t.id_tracking) as id_tracking,
            MAX(t.no_sj) as no_sj,
            MAX(stb.kode_stb) as kode_stb,
            
            MAX(kd.id) as id_kirim,
            MAX(kd.kode) as kode_kirim,
            
            MAX(COALESCE(t.no_resi, kd.no_resi)) as no_resi,
            MAX(COALESCE(t.tgl_sampai, kd.tgl_sampai)) as tgl_sampai,
            
            MAX(COALESCE(e.nama_ekspedisi, e_kd.nama_ekspedisi, kd.ekspedisi)) as nama_ekspedisi,
            MAX(COALESCE(e.payment, e_kd.payment)) as term_payment,
            MAX(COALESCE(e.mou, e_kd.mou)) as link_mou,
            MAX(COALESCE(e.pph23, e_kd.pph23)) as info_pph23,
            MAX(COALESCE(e.nama_pic, e_kd.nama_pic)) as pic_ekspedisi,
            MAX(COALESCE(e.contact, e_kd.contact)) as contact_ekspedisi,
            MAX(COALESCE(e.coverage, e_kd.coverage)) as coverage_ekspedisi,
            
            MAX(COALESCE(c.nama_customer, kd.nama_customer)) as nama_customer,
            MAX(c.alamat_customer) as alamat_customer,

            MAX(p.nama) as nama_pengaju,
            MAX(pb.no_pengiriman) as no_pengeluaran_barang,
            MAX(pb.tgl_keluar) as tanggal_keluar
        ');
        $this->db->from('tagihan_ekspedisi tg');
        $this->db->join('spp_detail sd', 'tg.id_tagihan = sd.id_tagihan');

        // --- FIX: Change INNER JOIN to LEFT JOIN ---
        $this->db->join('tracking_barang t', 'tg.id_tracking = t.id_tracking', 'left');

        $this->db->join('surat_stb stb', 't.id_serah_terima_barang = stb.id_stb', 'left');
        $this->db->join('ekspedisi e', 't.id_ekspedisi = e.id_ekspedisi', 'left');
        $this->db->join('customer c', 't.id_customer = c.id_customer', 'left');
        $this->db->join('pengeluaran_barang pb', 't.id_pengeluaran_barang = pb.id_pengeluaran_barang', 'left');

        // Join Kirim Dokumen
        $this->db->join('kirim_dokumen kd', 'tg.id_kirim = kd.id', 'left');
        $this->db->join('ekspedisi e_kd', 'kd.id_ekspedisi = e_kd.id_ekspedisi', 'left');

        $this->db->join('pengguna p', 'tg.created_by = p.pengguna_id', 'left');

        $this->db->where('sd.id_spp', $id_spp);
        $this->db->group_by('tg.id_tagihan');
        $this->db->order_by('tg.tanggal_invoice', 'ASC');

        $result = $this->db->get()->result();

        // Tambahkan kalkulasi jatuh tempo di PHP untuk setiap row
        foreach ($result as $row) {
            $jatuh_tempo_data = hitung_tanggal_jatuh_tempo($row->tanggal_invoice, $row->term_payment, $row->status_bayar);
            $row->tanggal_kontrak_jatuh_tempo = $jatuh_tempo_data['tanggal_kontrak_jatuh_tempo'];
            $row->tanggal_jatuh_tempo = $jatuh_tempo_data['tanggal_jatuh_tempo'];
            $row->status_jatuh_tempo = $jatuh_tempo_data['status_jatuh_tempo'];
            $row->hari_tersisa = $jatuh_tempo_data['hari_tersisa'];
        }

        return $result;
    }

    /**
     * Generate nomor SPP
     */
    function generateNoSpp()
    {
        $bulan = date('m');
        $tahun = date('Y');
        $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        // Ambil nomor terakhir di bulan ini
        $this->db->select('no_spp');
        $this->db->from('spp');
        $this->db->like('no_spp', '/' . $romawi[(int)$bulan] . '/' . $tahun, 'before');
        $this->db->order_by('id_spp', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get()->row();

        $urut = 1;
        if ($last) {
            $parts = explode('/', $last->no_spp);
            if (isset($parts[0])) {
                $urut = (int)$parts[0] + 1;
            }
        }

        return sprintf('%03d/SPP/%s/%s', $urut, $romawi[(int)$bulan], $tahun);
    }

    function insert($data)
    {
        $this->db->insert('spp', $data);
        return $this->db->insert_id();
    }

    function insertDetail($id_spp, $id_tagihan)
    {
        $this->db->insert('spp_detail', [
            'id_spp' => $id_spp,
            'id_tagihan' => $id_tagihan
        ]);
    }

    function updateTagihanSpp($id_tagihan, $id_spp)
    {
        $this->db->where('id_tagihan', $id_tagihan);
        $this->db->update('tagihan_ekspedisi', ['id_spp' => $id_spp]);
    }

    function update($id_spp, $data)
    {
        $this->db->where('id_spp', $id_spp);
        $this->db->update('spp', $data);
    }

    function checkExistingByInvoice($no_invoice)
    {
        $this->db->where('no_invoice', $no_invoice);
        return $this->db->get('spp')->row();
    }

    // =========================================================================
    // APPROVAL CONFIG
    // =========================================================================

    function getAllConfig()
    {
        $this->db->order_by('level_order', 'ASC');
        return $this->db->get('spp_approval_config')->result();
    }

    function getConfigByStatus($status_code)
    {
        return $this->db->get_where('spp_approval_config', ['status_code' => $status_code])->row();
    }

    function getFirstActiveLevel()
    {
        $this->db->where('is_active', 1);
        $this->db->order_by('level_order', 'ASC');
        $this->db->limit(1);
        return $this->db->get('spp_approval_config')->row();
    }

    function getNextActiveLevel($currentLevelOrder)
    {
        $this->db->where('is_active', 1);
        $this->db->where('level_order >', $currentLevelOrder);
        $this->db->order_by('level_order', 'ASC');
        $this->db->limit(1);
        return $this->db->get('spp_approval_config')->row();
    }

    function updateConfig($id_config, $data)
    {
        $this->db->where('id_config', $id_config);
        $this->db->update('spp_approval_config', $data);
    }

    // =========================================================================
    // NOTIFICATION LOG
    // =========================================================================

    function logNotification($id_spp, $target_role, $target_number, $message)
    {
        $this->db->insert('spp_notification_log', [
            'id_spp' => $id_spp,
            'target_role' => $target_role,
            'target_number' => $target_number,
            'message' => $message
        ]);
    }

    function getAllLogs()
    {
        $this->db->select('l.*, spp.no_spp');
        $this->db->from('spp_notification_log l');
        $this->db->join('spp', 'l.id_spp = spp.id_spp', 'left');
        $this->db->order_by('l.created_at', 'DESC');
        $this->db->limit(100);
        return $this->db->get()->result();
    }

    function getLogById($id_log)
    {
        return $this->db->get_where('spp_notification_log', ['id_log' => $id_log])->row();
    }

    function incrementResendCount($id_log)
    {
        $this->db->set('resend_count', 'resend_count + 1', FALSE);
        $this->db->where('id_log', $id_log);
        $this->db->update('spp_notification_log');
    }

    function getAllSppForExport($filters = [])
    {
        $this->db->select('
            spp.*,
            p.nama as nama_pengaju,
            p.jabatan as jabatan_pengaju,
            cfg.role_label as current_approver_role
        ');
        $this->db->from('spp');
        $this->db->join('pengguna p', 'spp.created_by = p.pengguna_id', 'left');
        $this->db->join('spp_approval_config cfg', 'spp.status_approval = cfg.status_code', 'left');

        if (isset($filters['status']) && $filters['status'] !== '') {
            $this->db->where('spp.status_approval', $filters['status']);
        }

        $this->db->order_by('spp.created_at', 'DESC');
        return $this->db->get()->result();
    }
}
