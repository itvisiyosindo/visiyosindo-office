<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Marketing_target extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Md_marketing_target');
        $this->load->model('md_pengguna');
        $this->load->model('md_prov_kota');
        $this->load->helper('encrypt_helper');
    }

    function id_navbar()
    {
        return "marketing";
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'marketing/v_target_list';
        $page_data['page_title'] = 'Target Penjualan & Funnel';
        $page_data['page_desc'] = 'Target Penjualan Jangka Pendek & Funnel Hot';
        
        $page_data['provinsi'] = $this->md_prov_kota->getAllProvinsi();
        $page_data['marketing_list'] = $this->md_pengguna->getPenggunaMarketing();
        $page_data['is_admin_or_leader'] = isAdmin() || isHrd() || isEksekutif();
        
        $this->load->view('index', $page_data);
    }

    public function dashboard()
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'marketing/v_target_dashboard';
        $page_data['page_title'] = 'Dashboard Rekap Target';
        $page_data['page_desc'] = 'Rekapitulasi Target Jangka Pendek & Achievement Forecast';

        $page_data['tahun'] = $this->input->get('tahun') ?: date('Y');
        $page_data['bulan'] = $this->input->get('bulan') ?: date('m');
        $page_data['marketing_list'] = $this->md_pengguna->getPenggunaMarketing();
        $page_data['is_admin_or_leader'] = isAdmin() || isHrd() || isEksekutif();

        // Determine active marketing filter
        if ($page_data['is_admin_or_leader']) {
            $page_data['marketing_id'] = $this->input->get('marketing_id');
            if ($page_data['marketing_id'] === 'all' || !$page_data['marketing_id']) {
                $page_data['marketing_id'] = NULL;
            }
        } else {
            $page_data['marketing_id'] = sessPenggunaId();
        }

        $page_data['rekap'] = $this->Md_marketing_target->get_rekap_dashboard($page_data['tahun'], $page_data['bulan'], $page_data['marketing_id']);
        $page_data['all_targets_year'] = $this->Md_marketing_target->get_targets($page_data['marketing_id'], $page_data['tahun']);

        // Monthly targets (12 months) for specific marketing person
        $monthly_targets = array_fill(1, 12, 0.0);
        if ($page_data['marketing_id']) {
            for ($b = 1; $b <= 12; $b++) {
                $monthly_targets[$b] = $this->Md_marketing_target->get_target_bulanan($page_data['marketing_id'], $page_data['tahun'], $b);
            }
        }
        $page_data['monthly_targets'] = $monthly_targets;

        $this->load->view('index', $page_data);
    }

    public function target_bulanan()
    {
        grantAccessFor('all');
        if (!(isAdmin() || isHrd() || isEksekutif())) {
            redirect(base_url('dashboard'));
        }

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'marketing/v_target_bulanan';
        $page_data['page_title'] = 'Setting Target Bulanan';
        $page_data['page_desc'] = 'Manajemen Target Nominal Bulanan Marketing';

        $page_data['tahun'] = $this->input->get('tahun') ?: date('Y');
        $page_data['marketing_list'] = $this->md_pengguna->getPenggunaMarketing();

        // Get monthly target mappings
        $targets = [];
        foreach ($page_data['marketing_list'] as $m) {
            $m_id = $m->pengguna_id;
            $targets[$m_id] = [];
            for ($b = 1; $b <= 12; $b++) {
                $targets[$m_id][$b] = $this->Md_marketing_target->get_target_bulanan($m_id, $page_data['tahun'], $b);
            }
        }
        $page_data['targets'] = $targets;

        $this->load->view('index', $page_data);
    }

    public function get_targets_json()
    {
        grantAccessFor('all');

        $tahun = $this->input->post('tahun') ?: date('Y');
        $marketing_id = $this->input->post('marketing_id');
        $bulan = $this->input->post('bulan');
        if ($bulan === 'all' || !$bulan) {
            $bulan = NULL;
        }

        // Access checks
        if (!(isAdmin() || isHrd() || isEksekutif())) {
            $marketing_id = sessPenggunaId();
        } else {
            if ($marketing_id === 'all' || !$marketing_id) {
                $marketing_id = NULL;
            }
        }

        $list = $this->Md_marketing_target->get_targets($marketing_id, $tahun, $bulan);
        $data = [];
        $no = 1;

        foreach ($list as $row) {
            $id_encrypt = encrypt($row->id);

            // Status Funnel Badges
            $status_badge = '';
            if ($row->status_funnel === 'Hot') {
                $status_badge = '<span class="badge badge-success font-weight-bold" style="background-color: #28a745; color: white;">Hot</span>';
            } elseif ($row->status_funnel === 'Warm') {
                $status_badge = '<span class="badge badge-warning font-weight-bold" style="background-color: #ffc107; color: black;">Warm</span>';
            } else {
                $status_badge = '<span class="badge badge-danger font-weight-bold" style="background-color: #dc3545; color: white;">Cold</span>';
            }

            if ($row->is_achieved == 1) {
                $status_badge .= '<br><span class="badge badge-success font-weight-bold" style="background-color: #10B981; color: white; display: inline-block; margin-top: 4px;"><i class="fas fa-handshake"></i> Deal Closed: Rp ' . rupiah($row->nilai_achievement) . '</span>';
            }

            // Support info
            $support_info = '<strong>' . htmlspecialchars($row->jenis_support ?: '-') . '</strong>';
            if ($row->kebutuhan_support) {
                $support_info .= '<br><small>' . htmlspecialchars($row->kebutuhan_support) . '</small>';
            }

            // Estimasi Closing formatted
            $estimasi = date('F Y', strtotime($row->estimasi_closing));

            // Gap Harga formatted
            $gap_harga = round($row->gap_harga, 1) . '%';

            // Buttons
            $btn_ach_class = $row->is_achieved ? 'btn-success' : 'btn-warning';
            $btn_ach_title = $row->is_achieved ? 'Edit Realisasi' : 'Realisasi (Closing Deal)';

            $btn_actions = '<div class="btn-group" role="group">';
            $btn_actions .= '<button type="button" class="btn btn-sm ' . $btn_ach_class . ' btn-achieve" data-id="' . $id_encrypt . '" data-instansi="' . htmlspecialchars($row->nama_instansi) . '" data-unit="' . htmlspecialchars($row->nama_unit) . '" data-harga-jual="' . rupiah($row->harga_jual) . '" data-harga-permintaan="' . rupiah($row->harga_permintaan) . '" data-nilai-achievement="' . ($row->nilai_achievement > 0 ? rupiah($row->nilai_achievement) : rupiah($row->harga_permintaan)) . '" data-tgl-achievement="' . ($row->tgl_achievement ?: date('Y-m-d')) . '" data-is-achieved="' . $row->is_achieved . '" title="' . $btn_ach_title . '"><i class="fas fa-trophy"></i></button>';
            $btn_actions .= '<button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id_encrypt . '" title="Edit"><i class="fas fa-pencil-alt"></i></button>';
            $btn_actions .= '<button type="button" class="btn btn-sm btn-danger btn-delete-target" data-id="' . $id_encrypt . '" title="Delete"><i class="fas fa-trash-alt"></i></button>';
            $btn_actions .= '</div>';

            $th = [];
            $th[] = $no++;
            $th[] = $row->tahun;
            $th[] = htmlspecialchars($row->nama_marketing);
            $th[] = htmlspecialchars($row->nama_instansi);
            $th[] = htmlspecialchars($row->nama_provinsi ?: '-');
            $th[] = htmlspecialchars($row->nama_kota ?: '-');
            $th[] = htmlspecialchars($row->pic_customer);
            $th[] = htmlspecialchars($row->nama_unit);
            $th[] = 'Rp ' . rupiah($row->harga_jual);
            $th[] = 'Rp ' . rupiah($row->harga_permintaan);
            $th[] = $gap_harga;
            $th[] = $row->persentase_kecapaian . '%';
            $th[] = $status_badge;
            $th[] = $estimasi;
            $th[] = htmlspecialchars($row->kendala ?: '-');
            $th[] = $support_info;
            $th[] = $btn_actions;

            $data[] = $th;
        }

        echo json_encode(['data' => $data]);
    }

    public function save_target()
    {
        grantAccessFor('all');

        $id_encrypt = $this->input->post('id');
        $id = $id_encrypt ? decrypt($id_encrypt) : null;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|integer');
        $this->form_validation->set_rules('nama_instansi', 'Nama Instansi', 'required');
        $this->form_validation->set_rules('provinsi_kode', 'Provinsi', 'required');
        $this->form_validation->set_rules('kota_id', 'Kota', 'required');
        $this->form_validation->set_rules('pic_customer', 'PIC Customer', 'required');
        $this->form_validation->set_rules('nama_unit', 'Nama Unit', 'required');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required');
        $this->form_validation->set_rules('harga_permintaan', 'Harga Permintaan', 'required');
        $this->form_validation->set_rules('persentase_kecapaian', 'Persentase Kecapaian', 'required|integer');
        $this->form_validation->set_rules('estimasi_closing', 'Estimasi Closing', 'required');
        $this->form_validation->set_rules('kendala', 'Kendala', 'required');
        $this->form_validation->set_rules('jenis_support', 'Jenis Support', 'required');
        $this->form_validation->set_rules('kebutuhan_support', 'Kebutuhan Support Detail', 'required');

        if ($this->form_validation->run() == FALSE) {
            ajaxReturnDie('error', validation_errors());
        }

        $harga_jual = (float) delete_currency($this->input->post('harga_jual'));
        $harga_permintaan = (float) delete_currency($this->input->post('harga_permintaan'));

        if ($harga_jual <= 0) {
            ajaxReturnDie('error', 'Harga Jual harus lebih besar dari 0');
        }

        // Calculate Gap
        $gap_harga = (($harga_jual - $harga_permintaan) / $harga_jual) * 100;

        // Determine status funnel
        $persentase = (int)$this->input->post('persentase_kecapaian');
        if ($persentase < 0 || $persentase > 100) {
            ajaxReturnDie('error', 'Persentase kecapaian harus berkisar 0-100%');
        }

        if ($persentase >= 75) {
            $status_funnel = 'Hot';
        } elseif ($persentase >= 40) {
            $status_funnel = 'Warm';
        } else {
            $status_funnel = 'Cold';
        }

        // Assign marketing
        if (isAdmin() || isHrd() || isEksekutif()) {
            $marketing_id = $this->input->post('marketing_id');
            if (!$marketing_id) {
                $marketing_id = sessPenggunaId();
            }
        } else {
            $marketing_id = sessPenggunaId();
        }

        $data = [
            'tahun' => $this->input->post('tahun'),
            'marketing_id' => $marketing_id,
            'nama_instansi' => $this->input->post('nama_instansi'),
            'provinsi_kode' => $this->input->post('provinsi_kode'),
            'kota_id' => $this->input->post('kota_id'),
            'pic_customer' => $this->input->post('pic_customer'),
            'nama_unit' => $this->input->post('nama_unit'),
            'harga_jual' => $harga_jual,
            'harga_permintaan' => $harga_permintaan,
            'gap_harga' => $gap_harga,
            'persentase_kecapaian' => $persentase,
            'status_funnel' => $status_funnel,
            'estimasi_closing' => $this->input->post('estimasi_closing'),
            'kendala' => $this->input->post('kendala'),
            'jenis_support' => $this->input->post('jenis_support'),
            'kebutuhan_support' => $this->input->post('kebutuhan_support')
        ];

        if ($id) {
            $this->Md_marketing_target->update_target($id, $data);
            addLog('Update Target Prospek', 'Memperbarui prospek target jangka pendek: ' . $data['nama_instansi']);
            ajaxReturnDie('success', 'Data target berhasil diperbarui', true);
        } else {
            $this->Md_marketing_target->add_target($data);
            addLog('Tambah Target Prospek', 'Menambahkan prospek target jangka pendek baru: ' . $data['nama_instansi']);
            ajaxReturnDie('success', 'Data target berhasil ditambahkan', true);
        }
    }

    public function edit($id_encrypt)
    {
        grantAccessFor('all');

        $id = decrypt($id_encrypt);
        $row = $this->Md_marketing_target->get_target_by_id($id);

        if ($row) {
            if (in_array($row->marketing_id, [54, 72, 77])) {
                $row->marketing_id = 54;
            }
            echo json_encode($row);
        } else {
            ajaxReturnDie('error', 'Data target tidak ditemukan');
        }
    }

    public function delete($id_encrypt)
    {
        grantAccessFor('all');

        $id = decrypt($id_encrypt);
        $row = $this->Md_marketing_target->get_target_by_id($id);

        if ($row) {
            $this->Md_marketing_target->delete_target($id);
            addLog('Hapus Target Prospek', 'Menghapus target penjualan instansi: ' . $row->nama_instansi);
            ajaxReturnDie('success', 'Data target berhasil dihapus', true);
        } else {
            ajaxReturnDie('error', 'Data tidak ditemukan');
        }
    }

    public function save_achievement()
    {
        grantAccessFor('all');

        $id_encrypt = $this->input->post('id');
        $id = decrypt($id_encrypt);
        $row = $this->Md_marketing_target->get_target_by_id($id);

        if (!$row) {
            ajaxReturnDie('error', 'Data prospek tidak ditemukan');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nilai_achievement', 'Nilai Realisasi Achievement', 'required');
        $this->form_validation->set_rules('tgl_achievement', 'Tanggal Closing', 'required');

        if ($this->form_validation->run() == FALSE) {
            ajaxReturnDie('error', validation_errors());
        }

        $nilai_achievement = (float) delete_currency($this->input->post('nilai_achievement'));
        $tgl_achievement = $this->input->post('tgl_achievement');
        $is_cancel = $this->input->post('cancel_achievement') == '1';

        if ($is_cancel) {
            $data = [
                'is_achieved' => 0,
                'nilai_achievement' => 0.00,
                'tgl_achievement' => null
            ];
            $this->Md_marketing_target->update_target($id, $data);
            addLog('Batal Closing Deal', 'Membatalkan closing deal prospek instansi: ' . $row->nama_instansi);
            ajaxReturnDie('success', 'Status closing deal berhasil dibatalkan', true);
        } else {
            if ($nilai_achievement < 0) {
                ajaxReturnDie('error', 'Nilai realisasi penjualan tidak boleh kurang dari 0');
            }

            $data = [
                'is_achieved' => 1,
                'nilai_achievement' => $nilai_achievement,
                'tgl_achievement' => $tgl_achievement
            ];
            $this->Md_marketing_target->update_target($id, $data);
            addLog('Closing Deal Prospek', 'Menyimpan realisasi penjualan sebesar Rp ' . rupiah($nilai_achievement) . ' untuk instansi ' . $row->nama_instansi);
            ajaxReturnDie('success', 'Data realisasi penjualan berhasil disimpan', true);
        }
    }

    public function get_target_bulanan_json($marketing_id, $tahun)
    {
        grantAccessFor('all');
        if (!(isAdmin() || isHrd() || isEksekutif())) {
            ajaxReturnDie('error', 'Akses tidak diperbolehkan');
        }

        $data = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $data[$bulan] = $this->Md_marketing_target->get_target_bulanan($marketing_id, $tahun, $bulan);
        }
        echo json_encode($data);
    }

    public function save_all_target_bulanan()
    {
        grantAccessFor('all');
        if (!(isAdmin() || isHrd() || isEksekutif())) {
            ajaxReturnDie('error', 'Akses tidak diperbolehkan');
        }

        $marketing_id = $this->input->post('marketing_id');
        $tahun = $this->input->post('tahun');
        $targets = $this->input->post('target_nominal'); // Array 1..12

        if (!$marketing_id || !$tahun || !is_array($targets)) {
            ajaxReturnDie('error', 'Parameter tidak lengkap');
        }

        foreach ($targets as $bulan => $nominal_str) {
            $nominal = (float) delete_currency($nominal_str);
            $this->Md_marketing_target->save_target_bulanan($marketing_id, $tahun, (int)$bulan, $nominal);
        }

        addLog('Set Target Bulanan', 'Menetapkan target bulanan marketing ID: ' . $marketing_id . ' Tahun: ' . $tahun);
        ajaxReturnDie('success', 'Target bulanan berhasil disimpan', true);
    }

    public function export_excel()
    {
        grantAccessFor('all');

        $tahun = $this->input->get('tahun') ?: date('Y');
        $marketing_id = $this->input->get('marketing_id');
        $bulan = $this->input->get('bulan');
        if ($marketing_id === 'all' || !$marketing_id) {
            $marketing_id = NULL;
        }
        if ($bulan === 'all' || !$bulan) {
            $bulan = NULL;
        }

        // Access check
        if (!(isAdmin() || isHrd() || isEksekutif())) {
            $marketing_id = sessPenggunaId();
        }

        $targets = $this->Md_marketing_target->get_targets($marketing_id, $tahun, $bulan);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Style configs
        $style_header = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E40AF'] // Dark navy/blue
            ]
        ];

        $style_data = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];

        // Title block
        $title_text = "INVENTORY MARKETING – TARGET PENJUALAN JANGKA PENDEK TAHUN " . $tahun;
        if ($marketing_id) {
            if ($marketing_id == 54) {
                $title_text .= " (KANTOR PUSAT [BOB ARIYOS, CRO & BULDANI])";
            } elseif ($marketing_id == 747) {
                $title_text .= " (AFTER SALES SERVICE)";
            } elseif ($marketing_id == 754) {
                $title_text .= " (VISILAB)";
            } else {
                $m_data = $this->md_pengguna->getById($marketing_id);
                if ($m_data) {
                    $title_text .= " (" . strtoupper($m_data[0]->nama) . ")";
                }
            }
        }
        
        $sheet->setCellValue('A1', $title_text);
        $sheet->mergeCells('A1:R1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E40AF'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Status Funnel');
        $sheet->setCellValue('C3', 'Nama Marketing');
        $sheet->setCellValue('D3', 'Nama Instansi');
        $sheet->setCellValue('E3', 'Provinsi');
        $sheet->setCellValue('F3', 'Kota');
        $sheet->setCellValue('G3', 'PIC Customer');
        $sheet->setCellValue('H3', 'Nama Unit/Produk');
        $sheet->setCellValue('I3', 'Harga Jual (Rp)');
        $sheet->setCellValue('J3', 'Harga Permintaan Customer (Rp)');
        $sheet->setCellValue('K3', 'Gap Harga (%)');
        $sheet->setCellValue('L3', 'Persentase Kecapaian (%)');
        $sheet->setCellValue('M3', 'Estimasi Closing');
        $sheet->setCellValue('N3', 'Kendala');
        $sheet->setCellValue('O3', 'Kebutuhan Support');
        $sheet->setCellValue('P3', 'Status Deal');
        $sheet->setCellValue('Q3', 'Nilai Realisasi Achievement (Rp)');
        $sheet->setCellValue('R3', 'Tanggal Closing');

        $sheet->getStyle('A3:R3')->applyFromArray($style_header);
        $sheet->getRowDimension(3)->setRowHeight(30);

        $row_index = 4;
        $no = 1;

        foreach ($targets as $t) {
            $estimasi = date('F Y', strtotime($t->estimasi_closing));
            $support_desc = $t->jenis_support;
            if ($t->kebutuhan_support) {
                $support_desc .= " - " . $t->kebutuhan_support;
            }

            $sheet->setCellValue('A' . $row_index, $no);
            $sheet->setCellValue('B' . $row_index, $t->status_funnel);
            $sheet->setCellValue('C' . $row_index, $t->nama_marketing);
            $sheet->setCellValue('D' . $row_index, $t->nama_instansi);
            $sheet->setCellValue('E' . $row_index, $t->nama_provinsi);
            $sheet->setCellValue('F' . $row_index, $t->nama_kota);
            $sheet->setCellValue('G' . $row_index, $t->pic_customer);
            $sheet->setCellValue('H' . $row_index, $t->nama_unit);
            $sheet->setCellValue('I' . $row_index, $t->harga_jual);
            $sheet->setCellValue('J' . $row_index, $t->harga_permintaan);
            $sheet->setCellValue('K' . $row_index, round($t->gap_harga, 1) / 100);
            $sheet->setCellValue('L' . $row_index, $t->persentase_kecapaian / 100);
            $sheet->setCellValue('M' . $row_index, $estimasi);
            $sheet->setCellValue('N' . $row_index, $t->kendala);
            $sheet->setCellValue('O' . $row_index, $support_desc);
            $sheet->setCellValue('P' . $row_index, $t->is_achieved ? 'Deal Closed' : 'Pipeline');
            $sheet->setCellValue('Q' . $row_index, $t->nilai_achievement);
            $sheet->setCellValue('R' . $row_index, $t->tgl_achievement ? date('d-M-Y', strtotime($t->tgl_achievement)) : '-');

            // Apply formats
            $sheet->getStyle('I' . $row_index)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('J' . $row_index)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('K' . $row_index)->getNumberFormat()->setFormatCode('0.0%');
            $sheet->getStyle('L' . $row_index)->getNumberFormat()->setFormatCode('0%');
            $sheet->getStyle('Q' . $row_index)->getNumberFormat()->setFormatCode('#,##0');

            // Alignment options
            $sheet->getStyle('A' . $row_index)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $row_index)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K' . $row_index)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L' . $row_index)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('M' . $row_index)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('P' . $row_index)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('R' . $row_index)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle('A' . $row_index . ':R' . $row_index)->applyFromArray($style_data);
            $sheet->getRowDimension($row_index)->setRowHeight(22);

            $row_index++;
            $no++;
        }

        // Auto size columns
        foreach (range('A', 'R') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        ob_end_clean();
        $filename = "Inventory_Marketing_Target_" . $tahun . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function get_customers_json()
    {
        grantAccessFor('all');

        // Fetch all active calon pelanggan
        $this->db->select('id, namacaloncustomer as nama, provinsi, kota, \'calon\' as tipe', FALSE);
        $this->db->where('deleted', 0);
        $calon = $this->db->get('calonpelanggan')->result_array();

        // Fetch all active pelangganan
        $this->db->select('p.id as id, p.namapelanggan as nama, c.provinsi, c.kota, \'pelanggan\' as tipe', FALSE);
        $this->db->from('pelangganan p');
        $this->db->join('calonpelanggan c', 'p.idcalonpelanggan = c.id', 'left');
        $this->db->where('p.deleted', 0);
        $pelanggan = $this->db->get()->result_array();

        $merged = array_merge($calon, $pelanggan);
        
        // Sort alphabetically by name
        usort($merged, function($a, $b) {
            return strcmp((string)$a['nama'], (string)$b['nama']);
        });

        echo json_encode($merged);
    }

    public function get_customer_pics_json($id, $type)
    {
        grantAccessFor('all');
        
        $calon_id = $id;
        if ($type === 'pelanggan') {
            $row = $this->db->get_where('pelangganan', ['id' => $id])->row();
            if ($row) {
                $calon_id = $row->idcalonpelanggan;
            }
        }

        $this->db->where('idpic', $calon_id);
        $this->db->where('deleted', 0);
        $pics = $this->db->get('calonpelangganpic')->result();

        echo json_encode($pics);
    }
}
