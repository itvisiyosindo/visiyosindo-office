<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Visilab_jadwal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Md_visilab_jadwal');
        $this->load->model('md_pengguna');
        $this->load->model('md_pelanggan');
        $this->load->helper('url');
        $this->load->helper('form');
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch'] = 'visilab';

        // load teknisi (pengguna with no_pegawai and active)
        $this->db->select('pengguna_id, nama, no_pegawai');
        $this->db->where('is_active', 1);
        $this->db->where('no_pegawai IS NOT NULL');
        $this->db->order_by('nama', 'ASC');
        $page_data['teknisi_list'] = $this->db->get('pengguna')->result();

        // load pelanggan for lokasi selection
        $this->db->select('id_pelanggan, identitas_pelanggan, alamat');
        $this->db->order_by('identitas_pelanggan', 'ASC');
        $page_data['pelanggan_list'] = $this->db->get('pelanggan')->result();

        $page_data['page_name'] = 'visilab/v_jadwal_list';
        $page_data['page_title'] = 'Manajemen Jadwal Ukes & Upar';
        $this->load->view('index', $page_data);
    }

    public function get_events()
    {
        $year = $this->input->get('year') ?: date('Y');
        $events = $this->Md_visilab_jadwal->getEventsByYear($year);

        $out = [];
        foreach ($events as $e) {
            $title = $e->jenis_jadwal . ' - ' . ($e->lokasi_alamat ? substr($e->lokasi_alamat,0,50) : '');
            $color = '#007bff';
            if (strtolower($e->status) == 'pending') $color = '#ffc107';
            if (strtolower($e->status) == 'selesai') $color = '#28a745';

            $tanggalMulai = !empty($e->event_start) ? $e->event_start : (!empty($e->tanggal) ? $e->tanggal : null);
            $tanggalSelesai = !empty($e->event_end) ? $e->event_end : $tanggalMulai;
            $tanggalSelesaiCalendar = $tanggalSelesai ? date('Y-m-d', strtotime($tanggalSelesai . ' +1 day')) : $tanggalMulai;

            $wilayah = isset($e->wilayah) ? trim((string) $e->wilayah) : '';
            if ($wilayah === '') {
                $wilayah = $this->inferWilayahFromProvinsi(isset($e->provinsi) ? $e->provinsi : '');
            }

            $out[] = [
                'id' => $e->id,
                'title' => $title,
                'start' => $tanggalMulai,
                'end' => $tanggalSelesaiCalendar,
                'allDay' => true,
                'color' => $color,
                'classNames' => ['wilayah-' . $this->slugWilayah($wilayah)],
                'extendedProps' => [
                    'jenis_jadwal' => $e->jenis_jadwal,
                    'teknisi_id' => $e->teknisi_id,
                    'teknisi_nama' => isset($e->teknisi_nama) ? $e->teknisi_nama : '',
                    'lokasi_pelanggan_id' => $e->lokasi_pelanggan_id,
                    'lokasi_pelanggan_nama' => isset($e->lokasi_pelanggan_nama) ? $e->lokasi_pelanggan_nama : '',
                    'wilayah' => $wilayah,
                    'wilayah_badge_color' => $this->getWilayahBadgeColor($wilayah),
                    'provinsi' => isset($e->provinsi) ? $e->provinsi : '',
                    'kab_kota' => isset($e->kab_kota) ? $e->kab_kota : '',
                    'lokasi_alamat' => $e->lokasi_alamat,
                    'tanggal_mulai' => $tanggalMulai,
                    'tanggal_selesai' => $tanggalSelesai,
                    'jam' => $e->jam,
                    'status' => $e->status,
                    'pending_reason' => $e->pending_reason
                ]
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($out);
    }

    public function get($id = null)
    {
        if (!$id) {
            show_404();
            return;
        }
        $row = $this->Md_visilab_jadwal->getById($id);
        header('Content-Type: application/json');
        echo json_encode($row);
    }

    public function save()
    {
        $id = $this->input->post('id');

        $tanggalMulai = $this->input->post('tanggal_mulai') ?: $this->input->post('tanggal');
        $tanggalSelesai = $this->input->post('tanggal_selesai') ?: $tanggalMulai;

        if ($tanggalMulai && $tanggalSelesai && strtotime($tanggalSelesai) < strtotime($tanggalMulai)) {
            $tmp = $tanggalMulai;
            $tanggalMulai = $tanggalSelesai;
            $tanggalSelesai = $tmp;
        }

        $wilayah = trim((string) $this->input->post('wilayah'));
        if ($wilayah === '') {
            $wilayah = $this->inferWilayahFromProvinsi($this->input->post('provinsi'));
        }

        $data = [
            'jenis_jadwal' => $this->input->post('jenis_jadwal'),
            'teknisi_id' => $this->input->post('teknisi_id') ?: null,
            'lokasi_pelanggan_id' => $this->input->post('lokasi_pelanggan_id') ?: null,
            'wilayah' => $wilayah,
            'provinsi' => $this->input->post('provinsi'),
            'kab_kota' => $this->input->post('kab_kota'),
            'lokasi_alamat' => $this->input->post('lokasi_alamat'),
            'tanggal' => $tanggalMulai,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'jam' => $this->input->post('jam'),
            'status' => $this->input->post('status') ?: 'On Proses',
            'pending_reason' => $this->input->post('pending_reason') ?: null,
            'created_by' => sessPenggunaId()
        ];

        if ($id) {
            $this->Md_visilab_jadwal->update($id, $data);
            $resp = ['success' => true, 'id' => $id];
        } else {
            $newId = $this->Md_visilab_jadwal->insert($data);
            $resp = ['success' => true, 'id' => $newId];
        }

        header('Content-Type: application/json');
        echo json_encode($resp);
    }

    public function ajax_pelanggan()
    {
        grantAccessFor('all');

        $q = trim((string) $this->input->get('q', true));
        $this->db->select('id_pelanggan as id, identitas_pelanggan as text, alamat, provinsi, kota as kab_kota');
        if ($q !== '') {
            $this->db->group_start();
            $this->db->like('identitas_pelanggan', $q);
            $this->db->or_like('alamat', $q);
            $this->db->group_end();
        }
        $this->db->order_by('identitas_pelanggan', 'ASC');
        $this->db->limit(50);

        $rows = $this->db->get('pelanggan')->result();
        header('Content-Type: application/json');
        echo json_encode(['results' => $rows]);
    }

    public function delete($id = null)
    {
        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing id']);
            return;
        }
        $this->Md_visilab_jadwal->delete($id);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }

    public function export_excel()
    {
        grantAccessFor('all');

        $year = (int) $this->input->get('year');
        if ($year < 2000 || $year > 2100) {
            $year = (int) date('Y');
        }

        $rows = $this->Md_visilab_jadwal->getEventsByYear($year);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Jadwal ' . $year);

        $headers = [
            'No',
            'Jenis Jadwal',
            'Teknisi',
            'Wilayah',
            'Provinsi',
            'Kab/Kota',
            'Pelanggan',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Jam',
            'Status',
            'Alasan Pending',
            'Alamat'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        $line = 2;
        $no = 1;
        foreach ($rows as $row) {
            $tanggalMulai = !empty($row->event_start) ? $row->event_start : (!empty($row->tanggal) ? $row->tanggal : '');
            $tanggalSelesai = !empty($row->event_end) ? $row->event_end : $tanggalMulai;

            $wilayah = isset($row->wilayah) ? trim((string) $row->wilayah) : '';
            if ($wilayah === '') {
                $wilayah = $this->inferWilayahFromProvinsi(isset($row->provinsi) ? $row->provinsi : '');
            }

            $sheet->setCellValue('A' . $line, $no++);
            $sheet->setCellValue('B' . $line, (string) $row->jenis_jadwal);
            $sheet->setCellValue('C' . $line, isset($row->teknisi_nama) ? (string) $row->teknisi_nama : '');
            $sheet->setCellValue('D' . $line, $wilayah);
            $sheet->setCellValue('E' . $line, isset($row->provinsi) ? (string) $row->provinsi : '');
            $sheet->setCellValue('F' . $line, isset($row->kab_kota) ? (string) $row->kab_kota : '');
            $sheet->setCellValue('G' . $line, isset($row->lokasi_pelanggan_nama) ? (string) $row->lokasi_pelanggan_nama : '');
            $sheet->setCellValue('H' . $line, $tanggalMulai);
            $sheet->setCellValue('I' . $line, $tanggalSelesai);
            $sheet->setCellValue('J' . $line, isset($row->jam) ? (string) $row->jam : '');
            $sheet->setCellValue('K' . $line, isset($row->status) ? (string) $row->status : '');
            $sheet->setCellValue('L' . $line, isset($row->pending_reason) ? (string) $row->pending_reason : '');
            $sheet->setCellValue('M' . $line, isset($row->lokasi_alamat) ? (string) $row->lokasi_alamat : '');

            $line++;
        }

        foreach (range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $filename = 'Jadwal_Visilab_' . $year . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function inferWilayahFromProvinsi($provinsi)
    {
        $p = strtolower(trim((string) $provinsi));
        if ($p === '') {
            return '';
        }

        $map = [
            'Sumatera' => ['aceh', 'sumatera', 'riau', 'kepri', 'jambi', 'bengkulu', 'lampung', 'bangka belitung'],
            'Jawa' => ['dki', 'jakarta', 'jawa', 'banten', 'yogyakarta'],
            'Kalimantan' => ['kalimantan'],
            'Sulawesi' => ['sulawesi', 'gorontalo'],
            'Bali Nusra' => ['bali', 'nusa tenggara'],
            'Maluku Papua' => ['maluku', 'papua']
        ];

        foreach ($map as $wilayah => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($p, $keyword) !== false) {
                    return $wilayah;
                }
            }
        }

        return 'Lainnya';
    }

    private function getWilayahBadgeColor($wilayah)
    {
        $key = strtolower(trim((string) $wilayah));
        $colors = [
            'sumatera' => '#0d6efd',
            'jawa' => '#dc3545',
            'kalimantan' => '#198754',
            'sulawesi' => '#fd7e14',
            'bali nusra' => '#6f42c1',
            'maluku papua' => '#20c997',
            'lainnya' => '#6c757d'
        ];
        return isset($colors[$key]) ? $colors[$key] : '#6c757d';
    }

    private function slugWilayah($wilayah)
    {
        $slug = strtolower(trim((string) $wilayah));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug !== '' ? $slug : 'lainnya';
    }
}
