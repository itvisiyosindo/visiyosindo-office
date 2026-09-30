<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Log extends CI_Controller {
	function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }
    
    function id_navbar(){
		$id_navbar = "home";
		return $id_navbar;
	}

    public function index(){
        grantAccessFor('all');
        
        $page_data['switch']		= $this->id_navbar();
        $page_data['page_name']     = 'log';
        $page_data['page_title']    = 'Log System & Audit Trail';
        $page_data['page_desc']     = 'Monitoring dan riwayat aktivitas pengguna dalam berinteraksi dengan sistem';

        // Data filter Pengguna
        $this->db->select('pengguna_id, nama');
        $this->db->where('is_active', 1);
        if (isAdmin() == FALSE) {
            $this->db->where('pengguna_id !=', 15);
        }
        $this->db->order_by('nama', 'ASC');
        $page_data['pengguna_list'] = $this->db->get('pengguna')->result();

        // Data filter Jenis Aksi
        $page_data['aksi_list'] = $this->md_log->getDistinctAksi();

        // Statistik Ringkas
        $page_data['stats'] = $this->md_log->getSummaryStats(date('Y-m'));

        $this->load->view('index', $page_data);
    }

    public function get_stats(){
        grantAccessFor('all');
        $month = $this->input->post('month');
        if (empty($month)) {
            $month = date('Y-m');
        }
        $stats = $this->md_log->getSummaryStats($month);
        echo json_encode(['status' => 'success', 'data' => $stats]);
        die;
    }

    public function pagination(){
        grantAccessFor('all');
        $dt = $this->md_log->getAllLog();
        $start = $this->input->post('start');
        $data = array();
        
        foreach($dt['data'] as $row){
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_pengguna ? '<strong>' . htmlspecialchars($row->nama_pengguna) . '</strong>' : '<span class="text-muted">-</span>';
            
            // Format Badge Jenis Aksi
            $aksi = htmlspecialchars($row->jenis_aksi);
            $badgeClass = 'badge-secondary';
            $lowerAksi = strtolower($row->jenis_aksi);
            
            if (strpos($lowerAksi, 'tambah') !== false || strpos($lowerAksi, 'create') !== false || strpos($lowerAksi, 'add') !== false || strpos($lowerAksi, 'login') !== false || strpos($lowerAksi, 'setuju') !== false || strpos($lowerAksi, 'approve') !== false) {
                $badgeClass = 'badge-success';
            } else if (strpos($lowerAksi, 'hapus') !== false || strpos($lowerAksi, 'delete') !== false || strpos($lowerAksi, 'tolak') !== false || strpos($lowerAksi, 'reject') !== false || strpos($lowerAksi, 'batal') !== false) {
                $badgeClass = 'badge-danger';
            } else if (strpos($lowerAksi, 'ubah') !== false || strpos($lowerAksi, 'update') !== false || strpos($lowerAksi, 'edit') !== false || strpos($lowerAksi, 'revisi') !== false) {
                $badgeClass = 'badge-warning';
            } else if (strpos($lowerAksi, 'upload') !== false || strpos($lowerAksi, 'kirim') !== false || strpos($lowerAksi, 'aju') !== false || strpos($lowerAksi, 'pengajuan') !== false) {
                $badgeClass = 'badge-info';
            } else if (strpos($lowerAksi, 'visilab') !== false) {
                $badgeClass = 'badge-primary';
            }

            $th[] = '<span class="badge ' . $badgeClass . '" style="font-size: 11px; padding: 4px 8px; font-weight: 500;">' . $aksi . '</span>';
            $th[] = htmlspecialchars($row->keterangan);
            $th[] = !empty($row->ip_addr) ? '<span class="badge badge-dark" style="font-size: 10px; font-family: monospace;"><i class="fa fa-desktop"></i> ' . htmlspecialchars($row->ip_addr) . '</span>' : '<span class="text-muted">-</span>';
            $th[] = '<small class="text-muted" style="white-space: nowrap;"><i class="fa fa-clock-o"></i> ' . date('d-M-Y | H:i:s', strtotime($row->tgl)) . '</small>';
            $data[] = $th;
        }

        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function export_excel() {
        grantAccessFor('all');

        $tgl_mulai = $this->input->get('tgl_mulai');
        $tgl_selesai = $this->input->get('tgl_selesai');
        $filter_month = $this->input->get('filter_month');
        $pengguna_id = $this->input->get('pengguna_id');
        $jenis_aksi = $this->input->get('jenis_aksi');

        $this->db->select('lg.log_id, pg.nama as nama_pengguna, lg.jenis_aksi, lg.keterangan, lg.tgl, lg.ip_addr');
        $this->db->from('log lg');
        $this->db->join('pengguna pg', 'pg.pengguna_id = lg.pengguna_id', 'left');

        if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
            $this->db->where("lg.tgl >=", $tgl_mulai . ' 00:00:00');
            $this->db->where("lg.tgl <=", $tgl_selesai . ' 23:59:59');
        } else if (!empty($filter_month)) {
            $this->db->where("DATE_FORMAT(lg.tgl,'%Y-%m')", $filter_month);
        }

        if (!empty($pengguna_id)) {
            $this->db->where("lg.pengguna_id", $pengguna_id);
        }

        if (!empty($jenis_aksi)) {
            $this->db->where("lg.jenis_aksi", $jenis_aksi);
        }

        if (isAdmin() == FALSE) {
            $this->db->where("lg.pengguna_id !=", 15);
        }

        $this->db->order_by('lg.tgl', 'DESC');
        $this->db->limit(10000); // Safety limit for single export
        $logs = $this->db->get()->result();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Log Aktivitas');

        // Header style
        $headers = ['A1' => 'NO', 'B1' => 'NAMA PENGGUNA', 'C1' => 'JENIS AKSI', 'D1' => 'KETERANGAN', 'E1' => 'IP ADDRESS', 'F1' => 'TANGGAL & WAKTU'];
        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $rowNum = 2;
        $no = 1;
        foreach ($logs as $row) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $row->nama_pengguna ? $row->nama_pengguna : '-');
            $sheet->setCellValue('C' . $rowNum, $row->jenis_aksi);
            $sheet->setCellValue('D' . $rowNum, $row->keterangan);
            $sheet->setCellValue('E' . $rowNum, !empty($row->ip_addr) ? $row->ip_addr : '-');
            $sheet->setCellValue('F' . $rowNum, date('d-m-Y H:i:s', strtotime($row->tgl)));
            $rowNum++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Log_Aktivitas_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
