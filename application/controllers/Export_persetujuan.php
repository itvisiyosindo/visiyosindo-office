<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Export_persetujuan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_export_persetujuan');
    }

    private function _canAccess()
    {
        if (isAdmin() || isGa() || isHrd() || isEksekutif() || isKepalaDivisi() || isAccountingUser()) {
            return true;
        }
        $allowed = [
            '1', '6', '10', '15', '23', '33', '39', '54', '58', '62', '64', '65',
            '69', '70', '72', '81', '88', '106', '107', '714', '744', '749',
            '763', '764', '769', '771', '777'
        ];
        return in_array((string)sessPenggunaId(), $allowed, true);
    }

    private function _requireAccess($json = false)
    {
        grantAccessFor('all');
        if (!$this->_canAccess()) {
            if ($json) {
                $this->output->set_status_header(403)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki akses ke fitur Export Persetujuan.']));
                exit;
            }
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman Export Persetujuan.');
            redirect(base_url('dashboard_helpdesk'));
            exit;
        }
    }

    private function _parseFilters()
    {
        $rawJenis = $this->input->get('jenis', true);
        $jenis = [];
        if (is_array($rawJenis)) {
            $jenis = array_values(array_filter(array_map('trim', $rawJenis)));
        } elseif (is_string($rawJenis) && trim($rawJenis) !== '' && trim($rawJenis) !== 'all') {
            $jenis = array_values(array_filter(array_map('trim', explode(',', $rawJenis))));
        }

        $tahun = $this->input->get('tahun', true);
        $bulan = $this->input->get('bulan', true);
        $tglAwal = trim((string)$this->input->get('tgl_awal', true));
        $tglAkhir = trim((string)$this->input->get('tgl_akhir', true));
        $status = trim((string)$this->input->get('status', true));

        if ($tglAwal !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglAwal)) {
            $tglAwal = '';
        }
        if ($tglAkhir !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglAkhir)) {
            $tglAkhir = '';
        }

        return [
            'jenis' => $jenis,
            'tahun' => ($tahun !== null && $tahun !== '' && is_numeric($tahun)) ? (int)$tahun : 0,
            'bulan' => ($bulan !== null && $bulan !== '' && is_numeric($bulan)) ? (int)$bulan : 0,
            'tgl_awal' => $tglAwal,
            'tgl_akhir' => $tglAkhir,
            'status' => in_array($status, ['all', 'disetujui', 'proses', 'ditolak'], true) ? $status : 'all',
        ];
    }

    private function _formatPeriodLabel($filters)
    {
        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        if ($filters['tgl_awal'] !== '' || $filters['tgl_akhir'] !== '') {
            $a = $filters['tgl_awal'] !== '' ? date('d/m/Y', strtotime($filters['tgl_awal'])) : 'Awal';
            $b = $filters['tgl_akhir'] !== '' ? date('d/m/Y', strtotime($filters['tgl_akhir'])) : 'Sekarang';
            return "Tanggal $a s/d $b";
        }
        if ($filters['tahun'] > 0 && $filters['bulan'] >= 1 && $filters['bulan'] <= 12) {
            return $bulanNames[$filters['bulan']] . ' ' . $filters['tahun'];
        }
        if ($filters['tahun'] > 0) {
            return 'Tahun ' . $filters['tahun'];
        }
        if ($filters['bulan'] >= 1 && $filters['bulan'] <= 12) {
            return 'Bulan ' . $bulanNames[$filters['bulan']] . ' (Semua Tahun)';
        }
        return 'Semua Periode';
    }

    public function index()
    {
        $this->_requireAccess(false);

        $from = $this->input->get('from', true);
        $preJenis = $this->input->get('jenis', true);
        $selectedJenis = [];
        if (is_array($preJenis)) {
            $selectedJenis = $preJenis;
        } elseif (is_string($preJenis) && trim($preJenis) !== '') {
            $selectedJenis = array_values(array_filter(array_map('trim', explode(',', $preJenis))));
        }

        $jenisList = $this->md_export_persetujuan->getJenisList();
        $groupedJenis = [];
        foreach ($jenisList as $key => $meta) {
            $groupedJenis[$meta['group']][$key] = $meta;
        }

        $curYear = (int)date('Y');
        $years = [];
        for ($y = max($curYear, 2026); $y >= 2021; $y--) {
            $years[] = $y;
        }

        $page_data['switch'] = ($from === 'accounting') ? 'accounting' : 'helpdesk';
        $page_data['page_name'] = 'surat/v_export_persetujuan';
        $page_data['page_title'] = 'Export Excel Persetujuan';
        $page_data['page_desc'] = 'Rekap & Export Excel Semua Persetujuan (PBOK, PPA, Ekspedisi, dll) berdasarkan Tanggal dan Tahun';
        $page_data['grouped_jenis'] = $groupedJenis;
        $page_data['selected_jenis'] = $selectedJenis;
        $page_data['years'] = $years;
        $page_data['default_year'] = $curYear;

        $this->load->view('index', $page_data);
    }

    /**
     * AJAX Preview ringkasan & sampel 100 baris sebelum download Excel
     */
    public function preview()
    {
        $this->_requireAccess(true);
        @ini_set('memory_limit', '512M');
        @set_time_limit(120);

        $filters = $this->_parseFilters();
        $rows = $this->md_export_persetujuan->getData($filters);

        $summaryByJenis = [];
        $totalsByCurrency = [];
        foreach ($rows as $r) {
            $k = $r['jenis_key'];
            if (!isset($summaryByJenis[$k])) {
                $summaryByJenis[$k] = [
                    'jenis' => $r['jenis_label'],
                    'count' => 0,
                    'nominal_rp' => 0.0,
                ];
            }
            $summaryByJenis[$k]['count']++;
            if ($r['mata_uang'] === 'Rp') {
                $summaryByJenis[$k]['nominal_rp'] += $r['nominal'];
            }
            if ($r['nominal'] > 0) {
                $mu = $r['mata_uang'] ?: 'Rp';
                if (!isset($totalsByCurrency[$mu])) {
                    $totalsByCurrency[$mu] = 0.0;
                }
                $totalsByCurrency[$mu] += $r['nominal'];
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'period_label' => $this->_formatPeriodLabel($filters),
                'total_rows' => count($rows),
                'summary' => array_values($summaryByJenis),
                'totals_by_currency' => $totalsByCurrency,
                'sample' => array_slice($rows, 0, 100),
            ]));
    }

    /**
     * Download file Excel (.xlsx)
     */
    public function download()
    {
        $this->_requireAccess(false);
        @ini_set('memory_limit', '512M');
        @set_time_limit(180);

        $filters = $this->_parseFilters();
        $rows = $this->md_export_persetujuan->getData($filters);
        $jenisList = $this->md_export_persetujuan->getJenisList();
        $periodLabel = $this->_formatPeriodLabel($filters);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Office System PT. Visi Yosindo Medikal')
            ->setTitle('Rekap Persetujuan - ' . $periodLabel);

        // Kelompokkan baris per jenis
        $grouped = [];
        foreach ($rows as $r) {
            $grouped[$r['jenis_key']][] = $r;
        }

        $sheetIdx = 0;
        if (count($grouped) > 1 || empty($grouped)) {
            $sheet = $spreadsheet->getActiveSheet();
            $this->_fillSheet($sheet, 'Rekap Semua', 'REKAP SEMUA PERSETUJUAN', $periodLabel, $filters['status'], $rows);
            $sheetIdx++;
        }

        foreach ($grouped as $jenisKey => $jenisRows) {
            $meta = isset($jenisList[$jenisKey]) ? $jenisList[$jenisKey] : ['label' => $jenisKey, 'full' => $jenisKey, 'sheet' => $jenisKey];
            $sheet = ($sheetIdx === 0) ? $spreadsheet->getActiveSheet() : $spreadsheet->createSheet($sheetIdx);
            $sheetTitle = $this->_safeSheetTitle($meta['sheet']);
            $this->_fillSheet($sheet, $sheetTitle, 'REKAP PERSETUJUAN - ' . strtoupper($meta['full']), $periodLabel, $filters['status'], $jenisRows);
            $sheetIdx++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        $slugJenis = (count($filters['jenis']) === 1) ? $filters['jenis'][0] : 'Semua_Persetujuan';
        $slugPeriode = ($filters['tgl_awal'] !== '' || $filters['tgl_akhir'] !== '')
            ? ($filters['tgl_awal'] ?: 'awal') . '_sd_' . ($filters['tgl_akhir'] ?: 'akhir')
            : (($filters['tahun'] > 0) ? ('Tahun_' . $filters['tahun'] . ($filters['bulan'] > 0 ? '_Bln_' . sprintf('%02d', $filters['bulan']) : '')) : 'Semua_Periode');

        $filename = 'Rekap_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $slugJenis) . '_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $slugPeriode) . '_' . date('Ymd_His') . '.xlsx';

        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function _safeSheetTitle($title)
    {
        $clean = preg_replace('/[\[\]\:\*\?\/\\\\]/', ' ', (string)$title);
        $clean = trim(preg_replace('/\s+/', ' ', $clean));
        if ($clean === '') {
            $clean = 'Sheet';
        }
        return substr($clean, 0, 31);
    }

    private function _fillSheet($sheet, $sheetTitle, $docTitle, $periodLabel, $statusFilter, $rows)
    {
        $sheet->setTitle($sheetTitle);

        // Title & metadata
        $sheet->setCellValue('A1', $docTitle);
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1E3A5F']],
        ]);

        $statusLabels = [
            'all' => 'Semua Status',
            'disetujui' => 'Disetujui',
            'proses' => 'Proses / Menunggu',
            'ditolak' => 'Ditolak',
        ];
        $metaLine = 'Periode: ' . $periodLabel . '   |   Status: ' . ($statusLabels[$statusFilter] ?? 'Semua Status') . '   |   Dicetak: ' . date('d/m/Y H:i') . ' WIB   |   Total Baris: ' . count($rows);
        $sheet->setCellValue('A2', $metaLine);
        $sheet->mergeCells('A2:M2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '4A5568']],
        ]);

        $headers = [
            'A' => 'No',
            'B' => 'Tanggal',
            'C' => 'Tahun',
            'D' => 'Jenis Persetujuan',
            'E' => 'No. Surat / Kode',
            'F' => 'Nama Pengaju',
            'G' => 'Nama Transaksi',
            'H' => 'Yang Ditransaksikan (Rincian / Item)',
            'I' => 'Qty',
            'J' => 'Mata Uang',
            'K' => 'Nominal / Nilai',
            'L' => 'Status',
            'M' => 'Link Dokumen',
        ];

        $headerRow = 4;
        foreach ($headers as $col => $text) {
            $sheet->setCellValue($col . $headerRow, $text);
        }

        $sheet->getStyle("A{$headerRow}:M{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A5F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(26);

        $rowNum = $headerRow + 1;
        $no = 1;
        $totalsByCurrency = [];

        foreach ($rows as $r) {
            $tglDisp = $r['tanggal'] !== '' ? date('d/m/Y', strtotime($r['tanggal'])) : '-';
            $sheet->setCellValueExplicit('A' . $rowNum, $no, DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('B' . $rowNum, $tglDisp, DataType::TYPE_STRING);
            if ($r['tahun'] !== '') {
                $sheet->setCellValueExplicit('C' . $rowNum, (int)$r['tahun'], DataType::TYPE_NUMERIC);
            } else {
                $sheet->setCellValueExplicit('C' . $rowNum, '-', DataType::TYPE_STRING);
            }
            $sheet->setCellValueExplicit('D' . $rowNum, (string)$r['jenis_label'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $rowNum, (string)$r['no_surat'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F' . $rowNum, (string)$r['pengaju'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('G' . $rowNum, (string)$r['nama_transaksi'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('H' . $rowNum, (string)$r['ditransaksikan'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('I' . $rowNum, (int)$r['qty'], DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('J' . $rowNum, (string)$r['mata_uang'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('K' . $rowNum, (float)$r['nominal'], DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('L' . $rowNum, (string)$r['status'], DataType::TYPE_STRING);

            if (!empty($r['link'])) {
                $sheet->setCellValueExplicit('M' . $rowNum, $r['link'], DataType::TYPE_STRING);
                $sheet->getCell('M' . $rowNum)->getHyperlink()->setUrl($r['link']);
                $sheet->getStyle('M' . $rowNum)->getFont()->setUnderline(true)->getColor()->setRGB('0D6EFD');
            } else {
                $sheet->setCellValueExplicit('M' . $rowNum, '-', DataType::TYPE_STRING);
            }

            $mu = $r['mata_uang'] ?: 'Rp';
            if ((float)$r['nominal'] > 0) {
                if (!isset($totalsByCurrency[$mu])) {
                    $totalsByCurrency[$mu] = 0.0;
                }
                $totalsByCurrency[$mu] += (float)$r['nominal'];
            }

            $rowNum++;
            $no++;
        }

        $lastDataRow = $rowNum - 1;
        if ($lastDataRow >= $headerRow + 1) {
            $sheet->getStyle("A5:M{$lastDataRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
            ]);
            $sheet->getStyle("A5:C{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I5:J{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("K5:K{$lastDataRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("G5:H{$lastDataRow}")->getAlignment()->setWrapText(true);

            // Total rows per currency
            if (empty($totalsByCurrency)) {
                $totalsByCurrency = ['Rp' => 0.0];
            }
            foreach ($totalsByCurrency as $mu => $totalVal) {
                $sheet->setCellValueExplicit('A' . $rowNum, 'TOTAL (' . $mu . ')', DataType::TYPE_STRING);
                $sheet->mergeCells("A{$rowNum}:I{$rowNum}");
                $sheet->setCellValueExplicit('J' . $rowNum, $mu, DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('K' . $rowNum, (float)$totalVal, DataType::TYPE_NUMERIC);
                $sheet->getStyle("K{$rowNum}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("A{$rowNum}:M{$rowNum}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EDF2F7']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '94A3B8']]],
                ]);
                $sheet->getStyle("A{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("J{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $rowNum++;
            }
        } else {
            $sheet->setCellValue('A5', 'Tidak ada data persetujuan pada periode / filter yang dipilih.');
            $sheet->mergeCells('A5:M5');
            $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Lebar kolom proporsional
        $widths = [
            'A' => 6,
            'B' => 13,
            'C' => 8,
            'D' => 23,
            'E' => 28,
            'F' => 22,
            'G' => 36,
            'H' => 52,
            'I' => 7,
            'J' => 10,
            'K' => 18,
            'L' => 24,
            'M' => 40,
        ];
        foreach ($widths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        $sheet->setAutoFilter("A{$headerRow}:M" . max($headerRow, $lastDataRow));
        $sheet->freezePane('A5');
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    }
}
