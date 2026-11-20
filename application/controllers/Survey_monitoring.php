<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

defined('BASEPATH') or exit('No direct script access allowed');

class Survey_monitoring extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_survey');
        $this->load->model('md_pelanggan');
        $this->load->helper(['url', 'form']);
    }

    public function id_navbar()
    {
        return 'marketing';
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']     = $this->id_navbar();
        $page_data['page_name']  = 'survey/v_survey_monitoring';
        $page_data['page_title'] = 'Survey Kepuasan Pelanggan';
        $page_data['page_desc']  = 'Monitoring Hasil Penilaian & Tindak Lanjut Respon Pelanggan';
        $page_data['statistics'] = $this->md_survey->get_statistics();

        $this->load->view('index', $page_data);
    }

    public function get_statistics()
    {
        grantAccessFor('all');
        $stats = $this->md_survey->get_statistics();
        echo json_encode($stats);
    }

    public function ajax_list()
    {
        grantAccessFor('all');

        $filter = [
            'status'               => $this->input->post('status', TRUE),
            'status_tindak_lanjut' => $this->input->post('status_tindak_lanjut', TRUE),
            'tgl_awal'             => $this->input->post('tgl_awal', TRUE),
            'tgl_akhir'            => $this->input->post('tgl_akhir', TRUE),
        ];

        $surveys = $this->md_survey->get_all_surveys($filter);
        $data = [];
        $no = 1;

        foreach ($surveys as $row) {
            // Tanggal
            $tglKirim = date('d/m/Y H:i', strtotime($row->created_at));
            $tglSelesai = !empty($row->submitted_at) ? date('d/m/Y H:i', strtotime($row->submitted_at)) : null;
            $colTgl = '<div><small class="text-muted"><i class="far fa-paper-plane mr-1"></i> ' . $tglKirim . '</small></div>';
            if ($tglSelesai) {
                $colTgl .= '<div><small class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> ' . $tglSelesai . '</small></div>';
            }

            // Pelanggan & PIC
            $namaPelanggan = htmlspecialchars($row->nama_pelanggan ?? '-', ENT_QUOTES);
            $namaPic = htmlspecialchars($row->nama_pic ?? '-', ENT_QUOTES);
            $colPelanggan = '<div><strong>' . $namaPelanggan . '</strong></div>';
            $colPelanggan .= '<div class="text-muted small"><i class="far fa-user mr-1"></i> PIC: ' . $namaPic . '</div>';

            // Kontak / WA
            $cleanWa = preg_replace('/[^0-9]/', '', (string)$row->no_wa);
            $colKontak = !empty($cleanWa)
                ? '<a href="https://api.whatsapp.com/send?phone=' . $cleanWa . '" target="_blank" class="text-success font-weight-bold"><i class="fab fa-whatsapp mr-1"></i>' . htmlspecialchars($row->no_wa, ENT_QUOTES) . '</a>'
                : '<span class="text-muted">-</span>';

            // Rating Bintang & Nilai Rata-rata
            if ($row->status === 'Selesai') {
                $starsHtml = '';
                $fullStars = round((float)$row->rata_rata);
                for ($s = 1; $s <= 5; $s++) {
                    if ($s <= $fullStars) {
                        $starsHtml .= '<i class="fas fa-star text-warning" style="color:#f59e0b !important; font-size:12px;"></i>';
                    } else {
                        $starsHtml .= '<i class="far fa-star text-muted" style="font-size:12px;"></i>';
                    }
                }
                $colSkor = '<div class="d-flex align-items-center">
                    <span class="badge badge-warning mr-2" style="background:#fef3c7; color:#92400e; font-weight:700; font-size:13px; border:1px solid #fde68a; padding:4px 8px;">
                        ★ ' . number_format((float)$row->rata_rata, 2) . '
                    </span>
                    <div>' . $starsHtml . '</div>
                </div>';
            } else {
                $colSkor = '<span class="badge badge-light text-muted" style="border:1px dashed #cbd5e1;">Belum Diisi</span>';
            }

            // Status Survey
            if ($row->status === 'Selesai') {
                $colStatus = '<span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Selesai</span>';
            } else {
                $colStatus = '<span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-clock mr-1"></i> Menunggu</span>';
            }

            // Status Tindak Lanjut
            $stTl = $row->status_tindak_lanjut ?? 'Belum Ditindaklanjuti';
            if ($stTl === 'Selesai') {
                $colTl = '<span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Selesai</span>';
            } elseif ($stTl === 'Sedang Ditindaklanjuti') {
                $colTl = '<span class="badge badge-info px-2 py-1"><i class="fas fa-spinner fa-spin mr-1"></i> Diproses</span>';
            } else {
                $colTl = '<span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation-circle mr-1"></i> Belum</span>';
            }

            // Saran / Masukan
            $saranRaw = trim((string)$row->saran_masukan);
            if ($saranRaw !== '') {
                $snippet = mb_strlen($saranRaw) > 50 ? mb_substr($saranRaw, 0, 50) . '...' : $saranRaw;
                $colSaran = '<span title="' . htmlspecialchars($saranRaw, ENT_QUOTES) . '" class="text-dark small font-italic">"' . htmlspecialchars($snippet, ENT_QUOTES) . '"</span>';
            } else {
                $colSaran = '<span class="text-muted small">-</span>';
            }

            // Aksi
            $surveyUrl = base_url('survey/' . $row->token);
            $pesanWa   = $this->md_survey->build_wa_message($row->nama_pelanggan, $row->nama_pic, $surveyUrl);
            $waLink    = $this->md_survey->build_wa_url($cleanWa, $pesanWa);

            $colAksi = '<div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-info btn-detail-survey" data-id="' . $row->id . '" title="Lihat Detail Survey">
                    <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="btn btn-sm btn-warning btn-tindaklanjut" data-id="' . $row->id . '" title="Update Tindak Lanjut">
                    <i class="fas fa-tasks"></i>
                </button>
                <a href="' . $waLink . '" target="_blank" class="btn btn-sm btn-success" title="Kirim / Buka WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>';

            $data[] = [
                $no++,
                $colTgl,
                $colPelanggan,
                $colKontak,
                $colSkor,
                $colStatus,
                $colTl,
                $colSaran,
                $colAksi
            ];
        }

        echo json_encode(['data' => $data]);
    }

    public function detail($id)
    {
        grantAccessFor('all');
        $id = (int)$id;
        $survey = $this->md_survey->get_by_id($id);

        if (!$survey) {
            echo json_encode(['status' => 'error', 'message' => 'Data survey tidak ditemukan.']);
            return;
        }

        echo json_encode(['status' => 'success', 'data' => $survey]);
    }

    public function update_tindak_lanjut()
    {
        grantAccessFor('all');

        $id = (int)$this->input->post('id', TRUE);
        $status_tl = $this->input->post('status_tindak_lanjut', TRUE);
        $catatan_tl = trim((string)$this->input->post('catatan_tindak_lanjut', TRUE));

        if (!$id || empty($status_tl)) {
            echo json_encode(['status' => 'error', 'message' => 'Parameter tidak lengkap.']);
            return;
        }

        $userNama = function_exists('sessNamaPengguna') ? (sessNamaPengguna() ?: 'Admin Office') : (function_exists('sessNama') ? sessNama() : 'Admin Office');

        $updateData = [
            'status_tindak_lanjut'  => $status_tl,
            'catatan_tindak_lanjut' => $catatan_tl,
            'tindak_lanjut_by'      => $userNama,
            'tindak_lanjut_at'      => date('Y-m-d H:i:s')
        ];

        $updated = $this->md_survey->update_tindak_lanjut($id, $updateData);

        if ($updated) {
            echo json_encode([
                'status'  => 'success',
                'message' => 'Status tindak lanjut berhasil diperbarui.'
            ]);
        } else {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal memperbarui status tindak lanjut.'
            ]);
        }
    }

    public function export_excel()
    {
        grantAccessFor('all');

        $filter = [
            'status'               => $this->input->get('status', TRUE),
            'status_tindak_lanjut' => $this->input->get('status_tindak_lanjut', TRUE),
            'tgl_awal'             => $this->input->get('tgl_awal', TRUE),
            'tgl_akhir'            => $this->input->get('tgl_akhir', TRUE),
        ];

        $surveys = $this->md_survey->get_all_surveys($filter);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Survey Kepuasan Pelanggan');

        // Header Dokumen
        $sheet->setCellValue('A1', 'LAPORAN HASIL SURVEY KEPUASAN PELANGGAN');
        $sheet->setCellValue('A2', 'PT VISI YOSINDO MEDIKA - TANGGAL CETAK: ' . date('d/m/Y H:i') . ' WIB');
        $sheet->mergeCells('A1:N1');
        $sheet->mergeCells('A2:N2');

        $sheet->getStyle('A1:A2')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(14);
        $sheet->getStyle('A2')->getFont()->setSize(10);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header Tabel
        $headers = [
            'A4' => 'NO',
            'B4' => 'TGL KIRIM',
            'C4' => 'TGL ISI',
            'D4' => 'NAMA PELANGGAN',
            'E4' => 'NAMA PIC',
            'F4' => 'NO WHATSAPP',
            'G4' => 'P1: KUALITAS PELAYANAN',
            'H4' => 'P2: KECEPATAN RESPONS',
            'I4' => 'P3: KERAMAHAN & PROFESIONALISME',
            'J4' => 'P4: PENANGANAN KELUHAN',
            'K4' => 'P5: LAYANAN TEKNIS',
            'L4' => 'P6: KEPUASAN TINDAK LANJUT',
            'M4' => 'RATA-RATA',
            'N4' => 'STATUS SURVEY',
            'O4' => 'STATUS TINDAK LANJUT',
            'P4' => 'SARAN & MASUKAN',
            'Q4' => 'CATATAN TINDAK LANJUT',
            'R4' => 'DITINDAKLANJUTI OLEH'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $headerRange = 'A4:R4';
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1E3A8A');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(4)->setRowHeight(28);

        $rowIdx = 5;
        $no = 1;
        foreach ($surveys as $s) {
            $sheet->setCellValue('A' . $rowIdx, $no++);
            $sheet->setCellValue('B' . $rowIdx, date('d/m/Y H:i', strtotime($s->created_at)));
            $sheet->setCellValue('C' . $rowIdx, !empty($s->submitted_at) ? date('d/m/Y H:i', strtotime($s->submitted_at)) : '-');
            $sheet->setCellValue('D' . $rowIdx, $s->nama_pelanggan);
            $sheet->setCellValue('E' . $rowIdx, $s->nama_pic ?? '-');
            $sheet->setCellValueExplicit('F' . $rowIdx, $s->no_wa ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('G' . $rowIdx, $s->bintang_pelayanan ?? '-');
            $sheet->setCellValue('H' . $rowIdx, $s->bintang_kecepatan_respons ?? '-');
            $sheet->setCellValue('I' . $rowIdx, $s->bintang_keramahan ?? '-');
            $sheet->setCellValue('J' . $rowIdx, $s->bintang_penanganan_keluhan ?? '-');
            $sheet->setCellValue('K' . $rowIdx, $s->bintang_layanan_teknis ?? '-');
            $sheet->setCellValue('L' . $rowIdx, $s->bintang_kepuasan_tindaklanjut ?? '-');
            $sheet->setCellValue('M' . $rowIdx, $s->rata_rata ? number_format((float)$s->rata_rata, 2) : '-');
            $sheet->setCellValue('N' . $rowIdx, $s->status);
            $sheet->setCellValue('O' . $rowIdx, $s->status_tindak_lanjut ?? 'Belum Ditindaklanjuti');
            $sheet->setCellValue('P' . $rowIdx, $s->saran_masukan ?? '-');
            $sheet->setCellValue('Q' . $rowIdx, $s->catatan_tindak_lanjut ?? '-');
            $sheet->setCellValue('R' . $rowIdx, $s->tindak_lanjut_by ? $s->tindak_lanjut_by . ' (' . date('d/m/Y', strtotime($s->tindak_lanjut_at)) . ')' : '-');

            $rowIdx++;
        }

        $lastRow = $rowIdx - 1;
        if ($lastRow >= 5) {
            $borderStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'D1D5DB']
                    ]
                ]
            ];
            $sheet->getStyle('A4:R' . $lastRow)->applyFromArray($borderStyle);
            $sheet->getStyle('A5:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G5:M' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('N5:O' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Auto width for columns
        foreach (range('A', 'R') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Laporan_Survey_Kepuasan_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
