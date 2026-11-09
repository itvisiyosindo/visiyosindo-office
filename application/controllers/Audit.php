<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Audit extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_audit_system');
        $this->load->model('md_pengguna');
        $this->load->model('md_dashboard');
    }

    public function index()
    {
        redirect(base_url('audit/kinerja'));
    }

    /**
     * Audit Kinerja
     * Seluruh karyawan terbagi sesuai divisi
     * Ada jobdesk masing2 karyawan, list detail proyek, implementasi nyata kinerja, dan link dokumen pekerjaan
     */
    public function kinerja()
    {
        grantAccessFor('all');

        $year   = $this->input->get('tahun', TRUE);
        $divisi = $this->input->get('divisi', TRUE);
        $posisi = $this->input->get('posisi', TRUE);
        $search = $this->input->get('q', TRUE);

        if (empty($year)) {
            $year = 2026;
        } else {
            $year = (int)$year;
        }

        if (empty($divisi)) {
            $divisi = 'all';
        }
        if (empty($posisi)) {
            $posisi = 'all';
        }

        $auditData = $this->md_audit_system->getAuditKinerjaData($year, $divisi, $posisi, $search);
        $divTree   = $this->md_audit_system->getDivisiAndPositions();

        $page_data['switch']        = 'kepegawaian';
        $page_data['page_name']     = 'audit/v_audit_kinerja';
        $page_data['page_title']    = 'Audit Kinerja Karyawan';
        $page_data['page_desc']     = 'Audit Kinerja Seluruh Divisi: Jobdesk, Detail Proyek, Implementasi Kinerja & Link Dokumen Bukti';
        $page_data['selected_year'] = $year;
        $page_data['selected_div']  = $divisi;
        $page_data['selected_pos']  = $posisi;
        $page_data['search_query']  = $search;
        $page_data['summary']       = $auditData;
        $page_data['items']         = $auditData['data'];
        $page_data['div_tree']      = $divTree;
        $page_data['current_user_name'] = sessNama();
        $page_data['current_user_id']   = sessPenggunaId();

        $this->load->view('index', $page_data);
    }

    /**
     * Audit Keuangan
     * Mengarah ke PBOK yang diajukan dan invoice/lampirannya
     */
    public function keuangan()
    {
        grantAccessFor('all');

        $year   = $this->input->get('tahun', TRUE);
        $bulan  = $this->input->get('bulan', TRUE);
        $status = $this->input->get('status', TRUE);
        $search = $this->input->get('q', TRUE);

        $keuanganData = $this->md_audit_system->getAuditKeuanganData($year, $bulan, $status, $search);

        $page_data['switch']        = 'kepegawaian';
        $page_data['page_name']     = 'audit/v_audit_keuangan';
        $page_data['page_title']    = 'Audit Keuangan & PBOK';
        $page_data['page_desc']     = 'Audit Belanja Pengajuan Biaya Operasional Kantor (PBOK) & Lampiran Invoice';
        $page_data['selected_year'] = $year;
        $page_data['selected_bulan'] = $bulan;
        $page_data['selected_status'] = $status;
        $page_data['search_query']  = $search;
        $page_data['summary']       = $keuanganData;
        $page_data['pbok_list']     = $keuanganData['data'];
        $page_data['current_user_name'] = sessNama();
        $page_data['current_user_id']   = sessPenggunaId();

        $this->load->view('index', $page_data);
    }

    /**
     * AJAX Simpan Audit Kinerja (link dokumen pekerjaan & status verifikasi)
     */
    public function save_kinerja()
    {
        grantAccessFor('all');

        $year    = (int)($this->input->post('year') ?: 2026);
        $itemId  = $this->input->post('item_id', TRUE);

        if (empty($itemId)) {
            echo json_encode(['status' => 'error', 'message' => 'ID item tidak valid']);
            return;
        }

        $payload = [
            'link_dokumen'  => $this->input->post('link_dokumen', TRUE),
            'status_audit'  => $this->input->post('status_audit', TRUE),
            'catatan_audit' => $this->input->post('catatan_audit', TRUE),
            'implementasi'  => $this->input->post('implementasi', TRUE)
        ];

        $ok = $this->md_audit_system->saveAuditKinerjaItem($year, $itemId, $payload);
        if ($ok) {
            echo json_encode(['status' => 'success', 'message' => 'Data audit kinerja dan link dokumen berhasil disimpan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data audit kinerja']);
        }
    }

    /**
     * AJAX Simpan Audit Keuangan (verifikasi PBOK & Invoice)
     */
    public function save_keuangan()
    {
        grantAccessFor('all');

        $idPbok = (int)$this->input->post('id_pbok');
        if (empty($idPbok)) {
            echo json_encode(['status' => 'error', 'message' => 'ID PBOK tidak valid']);
            return;
        }

        $payload = [
            'status_audit'        => $this->input->post('status_audit', TRUE),
            'kelengkapan_invoice' => $this->input->post('kelengkapan_invoice', TRUE),
            'catatan_audit'       => $this->input->post('catatan_audit', TRUE)
        ];

        $ok = $this->md_audit_system->saveAuditKeuanganItem($idPbok, $payload);
        if ($ok) {
            echo json_encode(['status' => 'success', 'message' => 'Verifikasi audit PBOK & invoice berhasil disimpan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan verifikasi']);
        }
    }
}
