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

        $user_id = (string)sessPenggunaId();
        $isAllowed = (isAdmin() || isHrd() || isEksekutif() || isKepalaDivisi() || in_array($user_id, ['1', '744', '29', '69', '58', '33', '23', '54', '107']));
        if (!$isAllowed) {
            $this->session->set_flashdata('error', 'Halaman Audit Kinerja hanya dapat diakses oleh Atasan, HRD, dan Auditor.');
            redirect(base_url('sasaran_mutu'));
            return;
        }

        $year       = $this->input->get('tahun', TRUE);
        $divisi     = $this->input->get('divisi', TRUE);
        $posisi     = $this->input->get('posisi', TRUE);
        $status     = $this->input->get('status', TRUE);
        $search     = $this->input->get('q', TRUE);
        $pegawai_id = $this->input->get('pegawai_id', TRUE);

        if (empty($year)) {
            $year = 2026;
        } else {
            $year = (int)$year;
        }

        $divTree        = $this->md_audit_system->getDivisiAndPositions();
        $pegawaiList    = $this->md_audit_system->getAuditKinerjaPegawaiList($year);
        $selectedPegawai = null;

        // Jika pegawai_id dipilih, cari detail pegawai dan kunci posisi serta divisinya
        if (!empty($pegawai_id)) {
            foreach ($pegawaiList as $peg) {
                if ($peg['pengguna_id'] === (string)$pegawai_id) {
                    $selectedPegawai = $peg;
                    $posisi = $peg['posisi'];
                    $divisi = $peg['divisi'];
                    break;
                }
            }
        }

        if (empty($divisi)) {
            $divisi = 'all';
        }
        if (empty($posisi)) {
            $posisi = 'all';
        }
        if (empty($status)) {
            $status = 'all';
        }

        // Jika belum ada pegawai yang dipilih dan tidak ada parameter posisi/divisi/q khusus
        $hasEmployeeSelected = (!empty($selectedPegawai) || ($posisi !== 'all') || ($divisi !== 'all') || ($status !== 'all') || !empty($search));

        if ($hasEmployeeSelected) {
            $auditData = $this->md_audit_system->getAuditKinerjaData($year, $divisi, $posisi, $search, $status);
        } else {
            // Ketika baru membuka menu, jangan langsung render tabel raksasa seluruh data
            $auditData = [
                'year'                => $year,
                'total_items'         => 0,
                'verified_count'      => 0,
                'need_evidence_count' => 0,
                'pending_count'       => 0,
                'data'                => []
            ];
        }

        $user_jabatan = '';
        $user_id = sessPenggunaId();
        if ($user_id && $this->db->conn_id) {
            $orig_db_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $u = @$this->db->select('jabatan')->where('pengguna_id', $user_id)->get('pengguna')->row();
            if ($u && !empty($u->jabatan)) {
                $user_jabatan = $u->jabatan;
            }
            $this->db->db_debug = $orig_db_debug;
        }

        $page_data['switch']                = 'kepegawaian';
        $page_data['page_name']             = 'audit/v_audit_kinerja';
        $page_data['page_title']            = 'Audit Kinerja Karyawan';
        $page_data['page_desc']             = 'Audit Kinerja Seluruh Divisi: Jobdesk, Detail Proyek, Implementasi Kinerja & Link Dokumen Bukti';
        $page_data['selected_year']         = $year;
        $page_data['selected_div']          = $divisi;
        $page_data['selected_pos']          = $posisi;
        $page_data['selected_status']       = $status;
        $page_data['search_query']          = $search;
        $page_data['selected_pegawai_id']   = $pegawai_id;
        $page_data['selected_pegawai']      = $selectedPegawai;
        $page_data['has_employee_selected'] = $hasEmployeeSelected;
        $page_data['pegawai_list']          = $pegawaiList;
        $page_data['summary']               = $auditData;
        $page_data['items']                 = $auditData['data'];
        $page_data['div_tree']              = $divTree;
        $page_data['current_user_name']     = sessNama();
        $page_data['current_user_id']       = sessPenggunaId();
        $page_data['user_jabatan']          = $user_jabatan;

        // Ambil detail breakdown kepatuhan laporan mingguan jika pegawai dipilih
        $laporanComplianceDetail = null;
        if (!empty($selectedPegawai)) {
            $laporanComplianceDetail = $this->md_audit_system->getLaporanMingguanCompliance($year, $selectedPegawai['pengguna_id']);
        }
        $page_data['laporan_compliance_detail'] = $laporanComplianceDetail;

        $this->load->view('index', $page_data);
    }

    /**
     * Audit Keuangan
     * Mengarah ke PBOK yang diajukan dan invoice/lampirannya
     */
    public function keuangan()
    {
        grantAccessFor('all');

        $user_id = (string)sessPenggunaId();
        $isAllowed = (isAdmin() || isHrd() || isEksekutif() || isKepalaDivisi() || isAccountingUser() || in_array($user_id, ['1', '744', '29', '69', '58', '33', '23', '54', '107']));
        if (!$isAllowed) {
            $this->session->set_flashdata('error', 'Halaman Audit Keuangan hanya dapat diakses oleh Manajemen, Keuangan, dan Auditor.');
            redirect(base_url('dashboard'));
            return;
        }

        $year   = $this->input->get('tahun', TRUE);
        $bulan  = $this->input->get('bulan', TRUE);
        $status = $this->input->get('status', TRUE);
        $search = $this->input->get('q', TRUE);
        $jenis  = $this->input->get('jenis', TRUE) ?: 'all';

        $keuanganData = $this->md_audit_system->getAuditKeuanganData($year, $bulan, $status, $search, $jenis);

        $page_data['switch']        = 'kepegawaian';
        $page_data['page_name']     = 'audit/v_audit_keuangan';
        $page_data['page_title']    = 'Audit Keuangan: PBOK & PPA';
        $page_data['page_desc']     = 'Audit Belanja Operasional Kantor (PBOK) & Pengajuan Pembayaran Anggaran (PPA) beserta Lampiran Invoice';
        $page_data['selected_year'] = $year;
        $page_data['selected_bulan'] = $bulan;
        $page_data['selected_status'] = $status;
        $page_data['selected_jenis'] = $jenis;
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
     * AJAX Simpan Audit Keuangan (verifikasi PBOK / PPA & Invoice)
     */
    public function save_keuangan()
    {
        grantAccessFor('all');

        $idSurat = (int)($this->input->post('id_surat') ?: $this->input->post('id_pbok'));
        $jenisSurat = $this->input->post('jenis_surat', TRUE) ?: 'pbok';

        if (empty($idSurat)) {
            echo json_encode(['status' => 'error', 'message' => 'ID dokumen pengajuan tidak valid']);
            return;
        }

        $payload = [
            'status_audit'        => $this->input->post('status_audit', TRUE),
            'kelengkapan_invoice' => $this->input->post('kelengkapan_invoice', TRUE),
            'catatan_audit'       => $this->input->post('catatan_audit', TRUE),
            'link_drive'          => $this->input->post('link_drive', TRUE)
        ];

        $ok = $this->md_audit_system->saveAuditKeuanganItem($idSurat, $payload, $jenisSurat);
        if ($ok) {
            echo json_encode(['status' => 'success', 'message' => 'Verifikasi audit pengajuan belanja & invoice berhasil disimpan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan verifikasi']);
        }
    }

    /**
     * AJAX Sinkronisasi Massal Semua Task Proyek & Laporan Mingguan ke Audit Kinerja
     */
    public function sync_all_activities()
    {
        grantAccessFor('all');

        $user_id = (string)sessPenggunaId();
        $isAllowed = (isAdmin() || isHrd() || isEksekutif() || isKepalaDivisi() || in_array($user_id, ['1', '744', '29', '69', '58', '33', '23', '54', '107']));
        if (!$isAllowed) {
            echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki hak akses untuk melakukan sinkronisasi massal audit.']);
            return;
        }

        $year   = (int)($this->input->post('tahun') ?: 2026);
        $divisi = $this->input->post('divisi', TRUE) ?: 'all';
        $posisi = $this->input->post('posisi', TRUE) ?: 'all';

        $auditData = $this->md_audit_system->getAuditKinerjaData($year, $divisi, $posisi);
        $items = $auditData['data'];

        $syncedCount = 0;
        foreach ($items as $item) {
            $sync = $this->md_audit_system->getSyncDataForItem($year, $item['id'], null, $item['jobdesk'], $item['posisi']);
            if ($sync && ($sync['laporan_count'] > 0 || $sync['task_count'] > 0)) {
                $payload = [
                    'implementasi' => $sync['compiled_impl'],
                    'link_dokumen' => !empty($item['link_dokumen']) ? $item['link_dokumen'] : $sync['compiled_link']
                ];
                $this->md_audit_system->saveAuditKinerjaItem($year, $item['id'], $payload);
                $syncedCount++;
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Berhasil mensinkronkan {$syncedCount} item audit kinerja secara otomatis dari rekam jejak task & laporan kerja."
        ]);
    }

    /**
     * AJAX Get Breakdown Kepatuhan Laporan Mingguan Pegawai (Modal View)
     */
    public function get_laporan_breakdown()
    {
        grantAccessFor('all');

        $year       = (int)($this->input->get('tahun') ?: 2026);
        $pegawai_id = (int)$this->input->get('pegawai_id');

        if ($pegawai_id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Pegawai tidak valid.']);
            return;
        }

        $detail = $this->md_audit_system->getLaporanMingguanCompliance($year, $pegawai_id);
        $peg = @$this->db->select('nama, jabatan')->where('pengguna_id', $pegawai_id)->get('pengguna')->row();

        echo json_encode([
            'status' => 'success',
            'pegawai' => [
                'nama'    => $peg ? $peg->nama : 'Pegawai',
                'jabatan' => $peg ? $peg->jabatan : '-'
            ],
            'data' => $detail
        ]);
    }

    /**
     * Export Hasil Audit Kinerja ke Format Excel (.xlsx) Resmi
     */
    public function export_excel()
    {
        grantAccessFor('all');

        $user_id = (string)sessPenggunaId();
        $isAllowed = (isAdmin() || isHrd() || isEksekutif() || isKepalaDivisi() || in_array($user_id, ['1', '744', '29', '69', '58', '33', '23', '54', '107']));
        if (!$isAllowed) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses untuk mengunduh rekap audit kinerja.');
            redirect(base_url('sasaran_mutu'));
            return;
        }

        $year       = (int)($this->input->get('tahun') ?: 2026);
        $divisi     = $this->input->get('divisi') ?: 'all';
        $posisi     = $this->input->get('posisi') ?: 'all';
        $status     = $this->input->get('status') ?: 'all';
        $search     = $this->input->get('q') ?: null;
        $pegawai_id = $this->input->get('pegawai_id', TRUE);

        $selectedPegawai = null;
        if (!empty($pegawai_id)) {
            $pegawaiList = $this->md_audit_system->getAuditKinerjaPegawaiList($year);
            foreach ($pegawaiList as $peg) {
                if ($peg['pengguna_id'] === (string)$pegawai_id) {
                    $selectedPegawai = $peg;
                    $posisi = $peg['posisi'];
                    $divisi = $peg['divisi'];
                    break;
                }
            }
        }

        $auditData = $this->md_audit_system->getAuditKinerjaData($year, $divisi, $posisi, $search, $status);
        $items = $auditData['data'];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Audit Kinerja ' . $year);

        // Header Dokumen
        $sheetTitle = !empty($selectedPegawai) ? ('AUDIT KINERJA - ' . strtoupper($selectedPegawai['nama'])) : 'AUDIT KINERJA KARYAWAN - PT VISI YOSINDO MEDIKAL';
        $sheet->setCellValue('A1', $sheetTitle);
        $complianceStr = '';
        if (!empty($selectedPegawai)) {
            $comp = $this->md_audit_system->getLaporanMingguanCompliance($year, $selectedPegawai['pengguna_id']);
            if ($comp) {
                $complianceStr = ' | Kepatuhan Laporan: ' . $comp['compliance_score'] . '% (' . $comp['count_tepat_waktu'] . ' Tepat, ' . $comp['count_telat'] . ' Telat, ' . $comp['count_tidak_isi'] . ' Kosong)';
            }
        }
        $subTitle = 'Tahun Periode: ' . $year . (!empty($selectedPegawai) ? (' | Posisi: ' . $selectedPegawai['posisi'] . ' (' . $selectedPegawai['divisi'] . ')') : '') . $complianceStr . ' | Tanggal Unduh: ' . date('d/m/Y H:i:s');
        $sheet->setCellValue('A2', $subTitle);
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);

        // Judul Kolom Tabel
        $headers = [
            'A4' => 'No',
            'B4' => 'Divisi',
            'C4' => 'Posisi / Jabatan',
            'D4' => 'Jobdesk',
            'E4' => 'List Detail Proyek',
            'F4' => 'Implementasi Nyata Kinerja (Sinkronisasi)',
            'G4' => 'Sasaran Mutu',
            'H4' => 'Link Dokumen Bukti Pekerjaan',
            'I4' => 'Status Audit HR',
            'J4' => 'Catatan Auditor'
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E293B']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
        $sheet->getStyle('A4:J4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(32);

        $rowNum = 5;
        $no = 1;
        foreach ($items as $it) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $it['divisi']);
            $sheet->setCellValue('C' . $rowNum, $it['posisi']);
            $sheet->setCellValue('D' . $rowNum, $it['jobdesk']);
            $sheet->setCellValue('E' . $rowNum, $it['detail_proyek']);
            $sheet->setCellValue('F' . $rowNum, $it['implementasi']);
            $sheet->setCellValue('G' . $rowNum, $it['sasaran_mutu']);
            $sheet->setCellValue('H' . $rowNum, $it['link_dokumen']);
            $sheet->setCellValue('I' . $rowNum, $it['status_audit']);
            $sheet->setCellValue('J' . $rowNum, $it['catatan_audit']);

            // Set link jika ada URL
            if (!empty($it['link_dokumen']) && filter_var($it['link_dokumen'], FILTER_VALIDATE_URL)) {
                $sheet->getCell('H' . $rowNum)->getHyperlink()->setUrl($it['link_dokumen']);
                $sheet->getStyle('H' . $rowNum)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF1D4ED8'))->setUnderline(true);
            }

            // Styling baris & wrap text
            $sheet->getStyle('A' . $rowNum . ':J' . $rowNum)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP)->setWrapText(true);
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $rowNum . ':J' . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFE2E8F0'));

            $rowNum++;
        }

        // Lebar kolom optimal
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(24);
        $sheet->getColumnDimension('D')->setWidth(35);
        $sheet->getColumnDimension('E')->setWidth(35);
        $sheet->getColumnDimension('F')->setWidth(40);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('I')->setWidth(22);
        $sheet->getColumnDimension('J')->setWidth(25);

        $filename = "Audit_Kinerja_Karyawan_{$year}_" . date('Ymd_His') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
