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

        // Ambil info jabatan pengguna untuk filter "Pekerjaan Saya"
        $user_jabatan = '';
        $user_id = sessPenggunaId();
        if ($user_id && $this->db->conn_id) {
            $orig_db_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $u = @$this->db->select('jabatan')->where('pengguna_id', $user_id)->get('pengguna')->row();
            if ($u && !empty($u->jabatan)) {
                $user_jabatan = trim($u->jabatan);
            }
            $this->db->db_debug = $orig_db_debug;
        }

        // Cek apakah user adalah Manajemen / HRD / Auditor
        $isManagementOrHrd = (isAdmin() || isHrd() || isEksekutif() || isKepalaDivisi() || in_array((string)$user_id, ['1', '744', '29', '69', '58', '33', '23', '54', '107']));

        // AUTO-FILTER: Jika staff biasa dan belum memilih filter secara manual, arahkan langsung ke jabatannya sendiri
        if (!$isManagementOrHrd && empty($posisi) && empty($divisi) && !empty($user_jabatan) && empty($search)) {
            $posisi = $user_jabatan;
        }

        if (empty($divisi)) {
            $divisi = 'all';
        }
        if (empty($posisi)) {
            $posisi = 'all';
        }

        $smData = $this->md_audit_system->getSasaranMutuData($year, $divisi, $posisi, $search);
        $divTree = $this->md_audit_system->getDivisiAndPositions();

        $page_data['switch']            = 'kepegawaian';
        $page_data['page_name']         = 'sasaran_mutu/v_sasaran_mutu';
        $page_data['page_title']        = 'Sasaran Mutu ' . $year;
        $page_data['page_desc']         = 'Sasaran & Manajemen Mutu PT Visi Yosindo Medikal';
        $page_data['selected_year']     = $year;
        $page_data['selected_div']      = $divisi;
        $page_data['selected_pos']      = $posisi;
        $page_data['search_query']      = $search;
        $page_data['sm_summary']        = $smData;
        $page_data['items']             = $smData['data'];
        $page_data['div_progress']      = isset($smData['div_progress']) ? $smData['div_progress'] : [];
        $page_data['div_tree']          = $divTree;
        $page_data['current_user_name'] = sessNama();
        $page_data['current_user_id']   = sessPenggunaId();
        $page_data['user_jabatan']      = $user_jabatan;
        $page_data['is_management']     = $isManagementOrHrd;

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
            'keterangan'             => $this->input->post('keterangan', TRUE),
            'link_dokumen'           => $this->input->post('link_dokumen', TRUE)
        ];

        $ok = $this->md_audit_system->saveSasaranMutuItem($year, $itemId, $payload);
        if ($ok) {
            echo json_encode(['status' => 'success', 'message' => 'Data sasaran mutu berhasil disimpan dan tersinkronisasi']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
        }
    }

    /**
     * AJAX Cepat Simpan Link Google Drive Bukti Pekerjaan oleh Karyawan
     */
    public function save_drive_link()
    {
        grantAccessFor('all');

        $year    = (int)($this->input->post('year') ?: 2026);
        $itemId  = $this->input->post('item_id', TRUE);
        $link    = trim((string)$this->input->post('link_dokumen', TRUE));
        $catatan = trim((string)$this->input->post('keterangan', TRUE));

        if (empty($itemId)) {
            echo json_encode(['status' => 'error', 'message' => 'ID item sasaran mutu tidak valid']);
            return;
        }

        if (empty($link)) {
            echo json_encode(['status' => 'error', 'message' => 'Link Google Drive bukti pekerjaan tidak boleh kosong']);
            return;
        }

        $master = $this->md_audit_system->getMasterTemplate();
        $targetItem = null;
        foreach ($master as $m) {
            if ($m['id'] === $itemId) {
                $targetItem = $m;
                break;
            }
        }

        $overrides = $this->md_audit_system->loadSasaranMutuOverrides($year);
        $existing = isset($overrides[$itemId]) ? $overrides[$itemId] : $targetItem;

        $payload = [
            'divisi'                 => isset($existing['divisi']) ? $existing['divisi'] : '',
            'posisi'                 => isset($existing['posisi']) ? $existing['posisi'] : '',
            'jobdesk'                => isset($existing['jobdesk']) ? $existing['jobdesk'] : '',
            'detail_proyek'          => isset($existing['detail_proyek']) ? $existing['detail_proyek'] : '',
            'implementasi'           => isset($existing['implementasi']) ? $existing['implementasi'] : '',
            'sasaran_mutu'           => isset($existing['sasaran_mutu']) ? $existing['sasaran_mutu'] : '',
            'metode_pengukuran'      => isset($existing['metode_pengukuran']) ? $existing['metode_pengukuran'] : '',
            'indikator_keberhasilan' => isset($existing['indikator_keberhasilan']) ? $existing['indikator_keberhasilan'] : '',
            'keterangan'             => !empty($catatan) ? $catatan : (isset($existing['keterangan']) ? $existing['keterangan'] : 'Bukti Tersedia di Drive'),
            'link_dokumen'           => $link
        ];

        $ok = $this->md_audit_system->saveSasaranMutuItem($year, $itemId, $payload);
        if ($ok) {
            echo json_encode([
                'status'  => 'success',
                'message' => 'Link Google Drive bukti pekerjaan berhasil disimpan dan disinkronkan ke Audit Kinerja!',
                'link'    => $link
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan link drive']);
        }
    }

    /**
     * Tambah item Jobdesk baru (untuk 2027 dan seterusnya langsung di Office)
     */
    public function add_custom_item()
    {
        grantAccessFor('all');

        $year    = (int)($this->input->post('year') ?: 2027);
        $divisi  = $this->input->post('divisi', TRUE);
        $posisi  = $this->input->post('posisi', TRUE);
        $jobdesk = $this->input->post('jobdesk', TRUE);

        if (empty($divisi) || empty($posisi) || empty($jobdesk)) {
            echo json_encode(['status' => 'error', 'message' => 'Divisi, Posisi, dan Rincian Jobdesk wajib diisi']);
            return;
        }

        $payload = [
            'divisi'                 => $divisi,
            'posisi'                 => $posisi,
            'jobdesk'                => $jobdesk,
            'detail_proyek'          => $this->input->post('detail_proyek', TRUE),
            'implementasi'           => $this->input->post('implementasi', TRUE),
            'sasaran_mutu'           => $this->input->post('sasaran_mutu', TRUE),
            'metode_pengukuran'      => $this->input->post('metode_pengukuran', TRUE),
            'indikator_keberhasilan' => $this->input->post('indikator_keberhasilan', TRUE),
            'keterangan'             => $this->input->post('keterangan', TRUE),
            'link_dokumen'           => $this->input->post('link_dokumen', TRUE)
        ];

        $ok = $this->md_audit_system->addCustomJobdeskItem($year, $payload);
        if ($ok) {
            echo json_encode(['status' => 'success', 'message' => 'Jobdesk baru berhasil ditambahkan dan tersinkronisasi ke sistem Office']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan jobdesk baru']);
        }
    }

    /**
     * API AJAX untuk melihat preview data sinkronisasi dari Laporan Mingguan & Task
     */
    public function api_get_sync_preview()
    {
        grantAccessFor('all');
        $year    = (int)($this->input->post('year') ?: $this->input->get('year') ?: 2027);
        $itemId  = $this->input->post('item_id', TRUE) ?: $this->input->get('item_id', TRUE);
        $jobdesk = $this->input->post('jobdesk', TRUE) ?: $this->input->get('jobdesk', TRUE);
        $posisi  = $this->input->post('posisi', TRUE) ?: $this->input->get('posisi', TRUE);
        $userId  = sessPenggunaId();

        $data = $this->md_audit_system->getSyncDataForItem($year, $itemId, $userId, $jobdesk, $posisi);
        echo json_encode([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * API AJAX untuk auto-sync 1 item dengan Laporan Mingguan & Task
     */
    public function api_auto_sync_item()
    {
        grantAccessFor('all');
        $year   = (int)($this->input->post('year') ?: 2027);
        $itemId = $this->input->post('item_id', TRUE);
        $userId = sessPenggunaId();

        if (empty($itemId)) {
            echo json_encode(['status' => 'error', 'message' => 'Item ID tidak valid']);
            return;
        }

        $res = $this->md_audit_system->autoSyncItemWithActivities($year, $itemId, $userId);
        echo json_encode($res);
    }

    /**
     * API AJAX untuk auto-sync seluruh item milik user dengan Laporan Mingguan & Task
     */
    public function api_auto_sync_all()
    {
        grantAccessFor('all');
        $year   = (int)($this->input->post('year') ?: 2027);
        $userId = sessPenggunaId();

        $res = $this->md_audit_system->autoSyncAllItemsForUser($year, $userId);
        echo json_encode($res);
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
