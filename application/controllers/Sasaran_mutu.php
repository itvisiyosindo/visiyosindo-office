<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sasaran_mutu extends CI_Controller
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

        $smData = $this->md_audit_system->getSasaranMutuData($year, $divisi, $posisi, $search);
        $divTree = $this->md_audit_system->getDivisiAndPositions();

        $page_data['switch']       = 'kepegawaian';
        $page_data['page_name']    = 'sasaran_mutu/v_sasaran_mutu';
        $page_data['page_title']   = 'Sasaran Mutu ' . $year;
        $page_data['page_desc']    = 'Sasaran & Manajemen Mutu PT Visi Yosindo Medikal';
        $page_data['selected_year'] = $year;
        $page_data['selected_div']  = $divisi;
        $page_data['selected_pos']  = $posisi;
        $page_data['search_query']  = $search;
        $page_data['sm_summary']   = $smData;
        $page_data['items']        = $smData['data'];
        $page_data['div_tree']     = $divTree;
        $page_data['current_user_name'] = sessNama();
        $page_data['current_user_id']   = sessPenggunaId();

        $this->load->view('index', $page_data);
    }

    /**
     * API AJAX untuk live update / get data
     */
    public function api_get_data()
    {
        grantAccessFor('all');
        $year   = (int)($this->input->get('tahun') ?: 2026);
        $divisi = $this->input->get('divisi') ?: 'all';
        $posisi = $this->input->get('posisi') ?: 'all';
        $search = $this->input->get('q') ?: '';

        $res = $this->md_audit_system->getSasaranMutuData($year, $divisi, $posisi, $search);
        echo json_encode([
            'status' => 'success',
            'summary' => [
                'year' => $res['year'],
                'total_items' => $res['total_items'],
                'filled_count' => $res['filled_count'],
                'persentase' => $res['persentase']
            ],
            'data' => $res['data']
        ]);
    }

    /**
     * Simpan pembaruan Sasaran Mutu (oleh Karyawan / Head)
     */
    public function save_item()
    {
        grantAccessFor('all');

        $year    = (int)($this->input->post('year') ?: 2026);
        $itemId  = $this->input->post('item_id', TRUE);

        if (empty($itemId)) {
            echo json_encode(['status' => 'error', 'message' => 'ID item tidak valid']);
            return;
        }

        $payload = [
            'divisi'                 => $this->input->post('divisi', TRUE),
            'posisi'                 => $this->input->post('posisi', TRUE),
            'jobdesk'                => $this->input->post('jobdesk', TRUE),
            'detail_proyek'          => $this->input->post('detail_proyek', TRUE),
            'implementasi'           => $this->input->post('implementasi', TRUE),
            'sasaran_mutu'           => $this->input->post('sasaran_mutu', TRUE),
            'metode_pengukuran'      => $this->input->post('metode_pengukuran', TRUE),
            'indikator_keberhasilan' => $this->input->post('indikator_keberhasilan', TRUE),
            'keterangan'             => $this->input->post('keterangan', TRUE)
        ];

        $ok = $this->md_audit_system->saveSasaranMutuItem($year, $itemId, $payload);
        if ($ok) {
            echo json_encode(['status' => 'success', 'message' => 'Data sasaran mutu berhasil disimpan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
        }
    }

    /**
     * Export data ke CSV
     */
    public function export_csv()
    {
        grantAccessFor('all');

        $year   = (int)($this->input->get('tahun') ?: 2026);
        $divisi = $this->input->get('divisi') ?: 'all';
        $posisi = $this->input->get('posisi') ?: 'all';

        $res = $this->md_audit_system->getSasaranMutuData($year, $divisi, $posisi);
        $data = $res['data'];

        $filename = "Sasaran_Mutu_{$year}_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF"); // BOM UTF-8

        fputcsv($output, ['No', 'Divisi', 'Posisi', 'Jobdesk', 'List Detail Proyek', 'Implementasi Nyata Kegiatan', 'Sasaran Mutu', 'Metode Pengukuran', 'Indikator Keberhasilan', 'Keterangan']);

        $no = 1;
        foreach ($data as $r) {
            fputcsv($output, [
                $no++,
                $r['divisi'],
                $r['posisi'],
                $r['jobdesk'],
                $r['detail_proyek'],
                $r['implementasi'],
                $r['sasaran_mutu'],
                $r['metode_pengukuran'],
                $r['indikator_keberhasilan'],
                $r['keterangan']
            ]);
        }
        fclose($output);
        exit;
    }
}
