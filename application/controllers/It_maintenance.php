<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property Md_it_maintenance $md_it_maintenance
 * @property Md_pengguna $md_pengguna
 * @property Md_aset $md_aset
 */
class It_maintenance extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->_init_db();
        $this->load->model('md_it_maintenance');
        $this->load->model('md_pengguna');
        $this->load->model('md_aset');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('tanggal_helper');
        $this->load->helper('mandatory_helper');
    }

    private function _init_db()
    {
        // 1. it_ticket
        if (!$this->db->table_exists('it_ticket')) {
            $this->db->query("CREATE TABLE `it_ticket` (
              `id_ticket` INT AUTO_INCREMENT PRIMARY KEY,
              `kode_tiket` VARCHAR(50) NOT NULL UNIQUE,
              `id_pembuat` INT NOT NULL,
              `id_penerima` INT DEFAULT NULL,
              `id_aset` INT DEFAULT NULL,
              `prioritas` VARCHAR(50) DEFAULT NULL,
              `subject` VARCHAR(255) NOT NULL,
              `deskripsi` TEXT NOT NULL,
              `file_pendukung` VARCHAR(255) DEFAULT NULL,
              `status_tiket` TINYINT DEFAULT 1,
              `solusi` TEXT DEFAULT NULL,
              `status_data` TINYINT DEFAULT 1,
              `created_at` DATETIME NOT NULL,
              `updated_at` DATETIME DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        } else {
            // Dynamic column migration for existing database setups
            if (!$this->db->field_exists('prioritas', 'it_ticket')) {
                $this->db->query("ALTER TABLE `it_ticket` ADD `prioritas` VARCHAR(50) DEFAULT NULL AFTER `id_aset`;");
            }
        }

        // 2. it_ticket_update
        if (!$this->db->table_exists('it_ticket_update')) {
            $this->db->query("CREATE TABLE `it_ticket_update` (
              `id_update` INT AUTO_INCREMENT PRIMARY KEY,
              `id_ticket` INT NOT NULL,
              `id_pembuat` INT NOT NULL,
              `detail` TEXT NOT NULL,
              `file_update` VARCHAR(255) DEFAULT NULL,
              `status` TINYINT NOT NULL,
              `created_at` DATETIME NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        // 3. it_maintenance_aset
        if (!$this->db->table_exists('it_maintenance_aset')) {
            $this->db->query("CREATE TABLE `it_maintenance_aset` (
              `id_maintenance` INT AUTO_INCREMENT PRIMARY KEY,
              `kode_maintenance` VARCHAR(50) NOT NULL UNIQUE,
              `id_aset` INT NOT NULL,
              `id_pengguna` INT NOT NULL,
              `id_it` INT NOT NULL,
              `tanggal_cek` DATE NOT NULL,
              `status_hardware` VARCHAR(100) NOT NULL,
              `status_software` VARCHAR(100) NOT NULL,
              `backup_gdrive` TINYINT DEFAULT 0,
              `test_keamanan` VARCHAR(100) NOT NULL,
              `keterangan` TEXT DEFAULT NULL,
              `rekomendasi` VARCHAR(100) NOT NULL,
              `status_data` TINYINT DEFAULT 1,
              `created_at` DATETIME NOT NULL,
              `updated_at` DATETIME DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        // 4. it_maintenance_config
        if (!$this->db->table_exists('it_maintenance_config')) {
            $this->db->query("CREATE TABLE `it_maintenance_config` (
              `id` INT AUTO_INCREMENT PRIMARY KEY,
              `key` VARCHAR(100) NOT NULL UNIQUE,
              `value` TEXT NOT NULL,
              `created_at` DATETIME NOT NULL,
              `updated_at` DATETIME DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            $this->db->query("INSERT INTO `it_maintenance_config` (`key`, `value`, `created_at`) VALUES ('it_wa_numbers', '', NOW()), ('wa_groups', '', NOW());");
        }
    }

    private function _decrypt_id($val)
    {
        if (empty($val)) return 0;
        if (is_numeric($val)) {
            return intval($val);
        }
        $decoded = base64_decode($val, true);
        if ($decoded !== false && is_numeric($decoded)) {
            return intval($decoded);
        }
        return intval(decrypt($val));
    }

    public function id_navbar()
    {
        return "helpdesk";
    }

    // ==========================================
    // VIEW PAGES
    // ==========================================

    public function open_ticket()
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'it_maintenance/v_open_ticket';
        $page_data['page_title'] = 'Open Ticket IT';
        $page_data['page_desc'] = 'Laporkan kendala perangkat elektronik IT anda';

        // Tampilkan semua aset aktif perusahaan beserta PIC terakhirnya agar tidak kosong
        $subQuery = '(SELECT id_aset, MAX(id) AS max_id FROM aset_log GROUP BY id_aset) latest_log';
        $page_data['user_assets'] = $this->db->select('
            a.id, 
            a.nama, 
            a.kode, 
            a.kategori, 
            COALESCE(p.nama, "Tidak Ada PIC") as nama_pic
        ')
            ->from('aset a')
            ->join($subQuery, 'a.id = latest_log.id_aset', 'left', false)
            ->join('aset_log al', 'al.id = latest_log.max_id', 'left')
            ->join('pengguna p', 'al.id_pengguna = p.pengguna_id', 'left')
            ->where('a.dijual !=', 2)
            ->order_by('a.nama', 'ASC')
            ->get()
            ->result();

        // Dapatkan aset yang saat ini dipegang oleh user login (untuk pre-selected/otomatis terpilih)
        $my_assets = $this->md_it_maintenance->getAssetsByPengguna(sessPenggunaId());
        $page_data['my_asset_id'] = !empty($my_assets) ? $my_assets[0]->id : '';

        $this->load->view('index', $page_data);
    }

    public function my_ticket()
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'it_maintenance/v_my_ticket';
        $page_data['page_title'] = 'My Ticket IT';
        $page_data['page_desc'] = 'Daftar tiket kendala IT yang anda ajukan';

        $this->load->view('index', $page_data);
    }

    public function data_ticket()
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['stats'] = $this->md_it_maintenance->getTicketStats();
        $page_data['page_name'] = 'it_maintenance/v_data_ticket';
        $page_data['page_title'] = 'Data Ticket IT';
        $page_data['page_desc'] = 'Semua tiket kendala perangkat IT perusahaan';

        $this->load->view('index', $page_data);
    }

    public function data_maintenance()
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'it_maintenance/v_data_maintenance';
        $page_data['page_title'] = 'Data Maintenance Aset IT';
        $page_data['page_desc'] = 'Hasil checklist & pemeliharaan berkala perangkat IT';
        $page_data['stats'] = $this->md_it_maintenance->getMaintenanceStats();

        $this->load->view('index', $page_data);
    }

    public function export_maintenance_excel()
    {
        grantAccessFor('all');

        // Get filters
        $filters = [
            'hardware_filter' => $this->input->get('hardware_filter', TRUE),
            'rekomendasi_filter' => $this->input->get('rekomendasi_filter', TRUE),
            'start_date' => $this->input->get('start_date', TRUE),
            'end_date' => $this->input->get('end_date', TRUE),
            'keyword' => $this->input->get('keyword', TRUE)
        ];

        $list = $this->md_it_maintenance->getMaintenancesForExport($filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Maintenance Aset IT');

        // Title and info metadata rows
        $sheet->setCellValue('A1', 'REKAP HASIL PEMELIHARAAN BERKALA ASET IT');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A2', 'Tanggal Export: ' . date('d F Y H:i'));

        $row_idx = 3;
        if (!empty($filters['start_date']) || !empty($filters['end_date'])) {
            $date_str = 'Periode Cek: ';
            if (!empty($filters['start_date'])) {
                $date_str .= date('d-m-Y', strtotime($filters['start_date']));
            } else {
                $date_str .= 'Awal';
            }
            $date_str .= ' s/d ';
            if (!empty($filters['end_date'])) {
                $date_str .= date('d-m-Y', strtotime($filters['end_date']));
            } else {
                $date_str .= 'Akhir';
            }
            $sheet->setCellValue('A' . $row_idx, $date_str);
            $row_idx++;
        }
        if (!empty($filters['hardware_filter'])) {
            $sheet->setCellValue('A' . $row_idx, 'Filter Status Hardware: ' . $filters['hardware_filter']);
            $row_idx++;
        }
        if (!empty($filters['rekomendasi_filter'])) {
            $sheet->setCellValue('A' . $row_idx, 'Filter Rekomendasi: ' . $filters['rekomendasi_filter']);
            $row_idx++;
        }

        // Add empty row
        $row_idx++;

        $headers = [
            'No',
            'Kode Check',
            'Tanggal Cek',
            'Nama Aset',
            'Kode Aset',
            'Pemegang (PIC)',
            'Pemeriksa (IT)',
            'Status Hardware',
            'Status Software',
            'GDrive Backup',
            'Rekomendasi',
            'Test Keamanan',
            'Keterangan Tambahan'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row_idx, $header);
            $sheet->getStyle($col . $row_idx)->getFont()->setBold(true);
            $sheet->getStyle($col . $row_idx)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFC6DEFF');
            $col++;
        }

        $line = $row_idx + 1;
        $no = 1;
        foreach ($list as $row) {
            $gdrive = ($row->backup_gdrive == 1) ? 'Ya' : 'Tidak';
            $sheet->setCellValue('A' . $line, $no++);
            $sheet->setCellValue('B' . $line, (string) $row->kode_maintenance);
            $sheet->setCellValue('C' . $line, date('d-m-Y', strtotime($row->tanggal_cek)));
            $sheet->setCellValue('D' . $line, (string) $row->nama_aset);
            $sheet->setCellValue('E' . $line, (string) $row->kode_aset);
            $sheet->setCellValue('F' . $line, (string) ($row->nama_pengguna ?: '-'));
            $sheet->setCellValue('G' . $line, (string) ($row->nama_it ?: '-'));
            $sheet->setCellValue('H' . $line, (string) $row->status_hardware);
            $sheet->setCellValue('I' . $line, (string) $row->status_software);
            $sheet->setCellValue('J' . $line, $gdrive);
            $sheet->setCellValue('K' . $line, (string) $row->rekomendasi);
            $sheet->setCellValue('L' . $line, (string) $row->test_keamanan);
            $sheet->setCellValue('M' . $line, (string) ($row->keterangan ?: '-'));

            $line++;
        }

        foreach (range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $filename = 'Rekap_Maintenance_Aset_IT_' . date('Y-m-d_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function config_notif()
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'it_maintenance/v_config_notif';
        $page_data['page_title'] = 'Config Notifikasi IT';
        $page_data['page_desc'] = 'Pengaturan tujuan WhatsApp pengajuan & progress tiket';
        $page_data['it_wa_numbers'] = $this->md_it_maintenance->getConfig('it_wa_numbers');
        $page_data['wa_groups'] = $this->md_it_maintenance->getConfig('wa_groups');

        $this->load->view('index', $page_data);
    }

    public function checklist_form($id_aset = '')
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'it_maintenance/v_checklist_form';
        $page_data['page_title'] = 'Form Checklist Maintenance Aset IT';
        $page_data['page_desc'] = 'Checklist pemeliharaan berkala perangkat';

        // Load asset details and find current PIC
        if (!empty($id_aset)) {
            $id_aset_dec = $this->_decrypt_id($id_aset);
            $aset = $this->md_aset->getAsetBywhere(['a.id' => $id_aset_dec]);
            if ($aset) {
                // Get current log/PIC
                $logs = $this->md_aset->getUpdateById(['al.id_aset' => $id_aset_dec]);
                $page_data['aset'] = $aset;
                $page_data['current_pic'] = !empty($logs) ? $logs[0] : null;
            }
        }

        $page_data['list_aset'] = $this->md_aset->getAllAsetByKategori();

        $this->load->view('index', $page_data);
    }

    public function detail_ticket($id_ticket_enc = '')
    {
        grantAccessFor('all');

        // Jika parameter berupa angka mentah (ID asli), redirect ke URL yang di-encrypt (Hashids)
        if (is_numeric($id_ticket_enc)) {
            redirect('it_maintenance/detail_ticket/' . encrypt(intval($id_ticket_enc)));
            return;
        }

        $id_ticket = $this->_decrypt_id($id_ticket_enc);
        $ticket = $this->md_it_maintenance->getTicketById($id_ticket);

        if (empty($ticket)) {
            show_404();
        }

        $page_data['switch'] = $this->id_navbar();
        $page_data['ticket'] = $ticket;
        $page_data['updates'] = $this->md_it_maintenance->getTicketUpdates($id_ticket);
        $page_data['technicians'] = $this->md_it_maintenance->getITTechnicians();
        $page_data['page_name'] = 'it_maintenance/v_maintenance_detail';
        $page_data['page_title'] = 'Detail Ticket IT';
        $page_data['page_desc'] = $ticket->kode_tiket;

        $this->load->view('index', $page_data);
    }

    // ==========================================
    // ACTION HANDLERS
    // ==========================================

    public function save_config()
    {
        grantAccessFor('all');

        $it_wa_numbers = $this->input->post('it_wa_numbers', TRUE);
        $wa_groups = $this->input->post('wa_groups', TRUE);

        $this->md_it_maintenance->updateConfig('it_wa_numbers', $it_wa_numbers);
        $this->md_it_maintenance->updateConfig('wa_groups', $wa_groups);

        ajaxReturnDie('success', 'Konfigurasi notifikasi berhasil diperbarui', TRUE);
    }

    public function save_ticket()
    {
        grantAccessFor('all');

        $subject = $this->input->post('subject', TRUE);
        $deskripsi = $this->input->post('deskripsi', TRUE);
        $id_aset = $this->input->post('id_aset', TRUE);
        $prioritas = $this->input->post('prioritas', TRUE);
        $file_pendukung = $this->input->post('file_pendukung', TRUE);

        // Make all input fields mandatory
        if (empty($subject) || empty($deskripsi) || $id_aset === '' || empty($prioritas) || empty($file_pendukung)) {
            ajaxReturnDie('error', 'Semua input field wajib diisi agar IT tidak kesusahan melihat kondisi barang yang dilaporkan');
        }

        $kode_tiket = $this->md_it_maintenance->generateKodeTiket();

        $data = [
            'kode_tiket' => $kode_tiket,
            'id_pembuat' => sessPenggunaId(),
            'id_aset' => intval($id_aset),
            'prioritas' => $prioritas,
            'subject' => $subject,
            'deskripsi' => $deskripsi,
            'file_pendukung' => $file_pendukung,
            'status_tiket' => 1, // Open
            'status_data' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->trans_begin();
        $id_ticket = $this->md_it_maintenance->addTicket($data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Gagal membuat tiket baru');
        }
        $this->db->trans_commit();

        // Send notifications to IT Numbers and groups
        $this->sendCreationNotification($id_ticket, $kode_tiket, $subject, $id_aset, $deskripsi, $file_pendukung, $prioritas);

        addLog('Open Ticket IT', "Membuat tiket IT {$kode_tiket}");

        ajaxReturnDie('success', 'Tiket kendala IT berhasil dibuat', TRUE);
    }

    public function get_asset_ticket_history()
    {
        grantAccessFor('all');
        $id_aset = intval($this->input->post('id_aset', TRUE));

        $tickets = $this->md_it_maintenance->getTicketsByWhere(['t.id_aset' => $id_aset, 't.status_data' => 1]);

        $html = '<div class="card border shadow-none" style="border-radius: 8px; border: 1px solid #e2e8f0 !important; margin-top: 15px;">
                    <div class="card-header bg-light py-2 px-3" style="background-color: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important;">
                        <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-history text-primary"></i> Riwayat Perbaikan / Tiket IT Perangkat Ini</h6>
                    </div>
                    <div class="card-body p-3">';

        if (empty($tickets)) {
            $html .= '<p class="text-muted text-center mb-0" style="font-size:13px;">Belum ada riwayat tiket/perbaikan untuk perangkat ini.</p>';
        } else {
            $html .= '<div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" style="font-size:13px; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Kode Tiket</th>
                                    <th>Subjek</th>
                                    <th>Prioritas</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($tickets as $t) {
                $status_label = 'Open';
                $status_class = 'badge-danger';
                if ($t->status_tiket == 2) {
                    $status_label = 'In Progress';
                    $status_class = 'badge-warning';
                } elseif ($t->status_tiket == 3) {
                    $status_label = 'Solved';
                    $status_class = 'badge-success';
                } elseif ($t->status_tiket == 4) {
                    $status_label = 'Closed';
                    $status_class = 'badge-secondary';
                } elseif ($t->status_tiket == 5) {
                    $status_label = 'Rejected';
                    $status_class = 'badge-danger';
                }

                $prio = $t->prioritas ?: 'Rendah';
                $prio_class = 'badge-info';
                if ($prio == 'Sedang') $prio_class = 'badge-warning';
                elseif ($prio == 'Tinggi') $prio_class = 'badge-danger';

                $detail_link = base_url('it_maintenance/detail_ticket/' . encrypt($t->id_ticket));

                $html .= '<tr>
                            <td><a href="' . $detail_link . '" target="_blank"><strong>' . $t->kode_tiket . '</strong></a></td>
                            <td>' . htmlspecialchars($t->subject) . '</td>
                            <td><span class="badge ' . $prio_class . '" style="font-size: 10px; padding: 3px 6px;">' . $prio . '</span></td>
                            <td>' . date('d-m-Y', strtotime($t->created_at)) . '</td>
                            <td><span class="badge ' . $status_class . '" style="font-size: 10px; padding: 3px 6px;">' . $status_label . '</span></td>
                          </tr>';
            }
            $html .= '</tbody></table></div>';
        }
        $html .= '</div></div>';
        echo $html;
        exit;
    }

    public function assign_ticket()
    {
        grantAccessFor('all');

        $id_ticket = $this->_decrypt_id($this->input->post('id_ticket', TRUE));
        $id_penerima = $this->input->post('id_penerima', TRUE);

        if (empty($id_ticket) || empty($id_penerima)) {
            ajaxReturnDie('error', 'Data tidak lengkap');
        }

        $data = [
            'id_penerima' => intval($id_penerima),
            'status_tiket' => 2, // In Progress
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->trans_begin();
        $this->md_it_maintenance->updateTicket($id_ticket, $data);

        // Add history timeline update
        $update_data = [
            'id_ticket' => $id_ticket,
            'id_pembuat' => sessPenggunaId(),
            'detail' => 'Tiket telah di-assign ke teknisi dan status diubah menjadi *In Progress*.',
            'status' => 2,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->md_it_maintenance->addTicketUpdate($update_data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Gagal memproses penugasan');
        }
        $this->db->trans_commit();

        // Send WA update to groups
        $ticket = $this->md_it_maintenance->getTicketById($id_ticket);
        $this->sendUpdateNotification($ticket, 'In Progress', 'Tiket ditugaskan ke teknisi ' . $ticket->nama_penerima);

        // Send WA directly to the assigned technician
        $this->sendTechnicianNotification($ticket);

        addLog('Assign Ticket IT', "Tiket {$ticket->kode_tiket} ditugaskan ke {$ticket->nama_penerima}");

        ajaxReturnDie('success', 'Teknisi berhasil ditugaskan', TRUE);
    }

    public function save_ticket_update()
    {
        grantAccessFor('all');

        $id_ticket = $this->_decrypt_id($this->input->post('id_ticket', TRUE));
        $detail = $this->input->post('detail', TRUE);
        $status_tiket = $this->input->post('status_tiket', TRUE);
        $solusi = $this->input->post('solusi', TRUE);

        if (empty($id_ticket) || empty($detail)) {
            ajaxReturnDie('error', 'Detail update wajib diisi');
        }

        $file_update = $this->input->post('file_update', TRUE);
        if (empty($file_update)) {
            $file_update = null;
        }

        $ticket = $this->md_it_maintenance->getTicketById($id_ticket);

        $update_data = [
            'id_ticket' => $id_ticket,
            'id_pembuat' => sessPenggunaId(),
            'detail' => $detail,
            'file_update' => $file_update,
            'status' => intval($status_tiket),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->trans_begin();
        $this->md_it_maintenance->addTicketUpdate($update_data);

        // Update ticket main record
        $main_data = [
            'status_tiket' => intval($status_tiket),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Solved or Closed
        if (in_array(intval($status_tiket), [3, 4]) && !empty($solusi)) {
            $main_data['solusi'] = $solusi;
        }

        $this->md_it_maintenance->updateTicket($id_ticket, $main_data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Gagal memperbarui tiket');
        }
        $this->db->trans_commit();

        $statusLabel = $this->md_it_maintenance->getStatusLabel($status_tiket);

        // Notify groups
        $this->sendUpdateNotification($ticket, $statusLabel, $detail);

        // Notify reporter
        if (in_array(intval($status_tiket), [3, 4])) {
            $this->sendCompletionNotification($ticket, $statusLabel, $solusi ? $solusi : $detail);
        } else {
            $this->sendReporterUpdateNotification($ticket, $statusLabel, $detail);
        }

        addLog('Update Ticket IT', "Update progress tiket {$ticket->kode_tiket} - Status: {$statusLabel}");

        ajaxReturnDie('success', 'Update tiket berhasil disimpan', TRUE);
    }

    public function save_maintenance()
    {
        grantAccessFor('all');

        $id_aset = $this->input->post('id_aset', TRUE);
        $tanggal_cek = $this->input->post('tanggal_cek', TRUE);
        $status_hardware = $this->input->post('status_hardware', TRUE);
        $status_software = $this->input->post('status_software', TRUE);
        $backup_gdrive = $this->input->post('backup_gdrive', TRUE) ? 1 : 0;
        $test_keamanan = $this->input->post('test_keamanan', TRUE);
        $keterangan = $this->input->post('keterangan', TRUE);
        $rekomendasi = $this->input->post('rekomendasi', TRUE);

        if (empty($id_aset) || empty($tanggal_cek) || empty($status_hardware) || empty($status_software) || empty($test_keamanan) || empty($rekomendasi)) {
            ajaxReturnDie('error', 'Semua field wajib diisi');
        }

        // Get asset logs to retrieve current user PIC holding the device
        $logs = $this->md_aset->getUpdateById(['al.id_aset' => intval($id_aset)]);
        if (empty($logs)) {
            ajaxReturnDie('error', 'Aset tidak memiliki PIC / Log penggunaan. Silakan tetapkan PIC aset terlebih dahulu.');
        }

        $id_pengguna_pic = $logs[0]->id_pengguna;
        $kode_maintenance = $this->md_it_maintenance->generateKodeMaintenance();

        $data = [
            'kode_maintenance' => $kode_maintenance,
            'id_aset' => intval($id_aset),
            'id_pengguna' => $id_pengguna_pic,
            'id_it' => sessPenggunaId(),
            'tanggal_cek' => date('Y-m-d', strtotime($tanggal_cek)),
            'status_hardware' => $status_hardware,
            'status_software' => $status_software,
            'backup_gdrive' => $backup_gdrive,
            'test_keamanan' => $test_keamanan,
            'keterangan' => $keterangan,
            'rekomendasi' => $rekomendasi,
            'status_data' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->trans_begin();
        $this->md_it_maintenance->addMaintenance($data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Gagal menyimpan checklist maintenance');
        }
        $this->db->trans_commit();

        addLog('Checklist Maintenance Aset IT', "Menambahkan checklist maintenance {$kode_maintenance}");

        ajaxReturnDie('success', 'Data checklist maintenance aset berhasil disimpan', TRUE);
    }

    public function delete_ticket()
    {
        grantAccessFor(['Administrator']);
        $id = $this->_decrypt_id($this->input->post('id', TRUE));
        $this->md_it_maintenance->deleteTicket($id);
        ajaxReturnDie('success', 'Tiket berhasil dihapus', TRUE);
    }

    public function delete_maintenance()
    {
        grantAccessFor(['Administrator']);
        $id = $this->_decrypt_id($this->input->post('id', TRUE));
        $this->md_it_maintenance->deleteMaintenance($id);
        ajaxReturnDie('success', 'Data maintenance berhasil dihapus', TRUE);
    }

    // ==========================================
    // PAGINATION (DATATABLES SERVER SIDE)
    // ==========================================

    public function my_ticket_pagination()
    {
        grantAccessFor('all');
        echo json_encode($this->md_it_maintenance->getTicketsDatatable(['t.id_pembuat' => sessPenggunaId()]));
    }

    public function data_ticket_pagination()
    {
        grantAccessFor('all');
        echo json_encode($this->md_it_maintenance->getTicketsDatatable());
    }

    public function data_maintenance_pagination()
    {
        grantAccessFor('all');
        echo json_encode($this->md_it_maintenance->getMaintenancesDatatable());
    }

    // ==========================================
    // NOTIFICATION HELPERS
    // ==========================================

    private function sendCreationNotification($id_ticket, $kode_tiket, $subject, $id_aset, $deskripsi = '', $file_pendukung = '', $prioritas = '')
    {
        $aset_name = 'Lainnya / Umum';
        if ($id_aset && intval($id_aset) > 0) {
            $aset = $this->md_aset->getAsetBywhere(['a.id' => intval($id_aset)]);
            if ($aset) {
                $aset_name = "{$aset->nama} ({$aset->kode})";
            }
        }

        $reporter = $this->md_pengguna->getById(sessPenggunaId());
        $nama_reporter = !empty($reporter) ? $reporter[0]->nama : 'Karyawan';

        $detail_link = "https://office.visiyosindo.id/it_maintenance/detail_ticket/" . encrypt($id_ticket);

        // Truncate deskripsi agar tidak terlalu panjang di WA
        $deskripsi_clean = strip_tags(trim($deskripsi));
        if (mb_strlen($deskripsi_clean) > 300) {
            $deskripsi_clean = mb_substr($deskripsi_clean, 0, 300) . '...';
        }

        $pesan = "*Notifikasi Tiket IT Baru*" .
            "%0A" . str_repeat("-", 30) .
            "%0A%0ATerdapat pengajuan tiket kendala perangkat IT baru:%0A" .
            "%0A*Kode Tiket* : *{$kode_tiket}*" .
            "%0A*Subjek*     : " . urlencode($subject) .
            "%0A*Perangkat*  : " . urlencode($aset_name) .
            "%0A*Pelapor*    : " . urlencode($nama_reporter) .
            "%0A*Prioritas*  : *" . urlencode($prioritas ?: '-') . "*" .
            "%0A*Tanggal*    : " . date('d-m-Y') . " Pukul " . date('H:i') . " WIB" .
            "%0A%0A*Deskripsi Masalah:*" .
            "%0A" . urlencode($deskripsi_clean);

        if (!empty($file_pendukung)) {
            $pesan .= "%0A%0A*Lampiran:* " . urlencode($file_pendukung);
        }

        $pesan .=  "%0A%0A" . str_repeat("-", 30) .
            "%0AMohon tim IT untuk segera memeriksa dan memproses tiket ini di:" .
            "%0A🔗 {$detail_link}" .
            "%0A%0ATerima Kasih";

        // 1. Send to direct IT numbers
        $it_numbers_raw = $this->md_it_maintenance->getConfig('it_wa_numbers');
        if (!empty($it_numbers_raw)) {
            $numbers = explode(',', $it_numbers_raw);
            foreach ($numbers as $num) {
                $num = trim($num);
                if (!empty($num)) {
                    $clean_num = $this->normalizePhoneNumber($num);
                    if ($clean_num) {
                        sendWa([
                            'devId' => hostWa('1'),
                            'penerima' => $clean_num,
                            'pesan' => $pesan
                        ]);
                    }
                }
            }
        }

        // 2. Send to WA Groups
        $groups_raw = $this->md_it_maintenance->getConfig('wa_groups');
        if (!empty($groups_raw)) {
            $groups = explode(',', $groups_raw);
            foreach ($groups as $g) {
                $g = trim($g);
                if (!empty($g)) {
                    sendWaGroup([
                        'devId' => hostWa('1'),
                        'penerima' => $g,
                        'pesan' => $pesan
                    ]);
                }
            }
        }
    }

    private function sendUpdateNotification($ticket, $status, $detail)
    {
        $groups_raw = $this->md_it_maintenance->getConfig('wa_groups');
        if (empty($groups_raw)) return;

        $updater = $this->md_pengguna->getById(sessPenggunaId());
        $nama_updater = !empty($updater) ? $updater[0]->nama : 'IT Staff';

        $detail_link = "https://office.visiyosindo.id/it_maintenance/detail_ticket/" . encrypt($ticket->id_ticket);

        $pesan = "*Update Progress Tiket IT*" .
            "%0A%0ATiket kendala IT telah di-update:" .
            "%0A%0AKode Tiket : *{$ticket->kode_tiket}*" .
            "%0ASubjek     : " . urlencode($ticket->subject) .
            "%0AStatus     : *{$status}*" .
            "%0AUpdate Oleh : " . urlencode($nama_updater) .
            "%0ADetail     : " . urlencode(strip_tags($detail)) .
            "%0A%0APeriksa detail progress di {$detail_link}." .
            "%0A%0ATerima Kasih";

        $groups = explode(',', $groups_raw);
        foreach ($groups as $g) {
            $g = trim($g);
            if (!empty($g)) {
                sendWaGroup([
                    'devId' => hostWa('1'),
                    'penerima' => $g,
                    'pesan' => $pesan
                ]);
            }
        }
    }

    private function sendCompletionNotification($ticket, $status, $solusi)
    {
        $pembuat = $this->md_pengguna->getById($ticket->id_pembuat);
        if (empty($pembuat) || empty($pembuat[0]->no_hp)) return;

        $clean_phone = $this->normalizePhoneNumber($pembuat[0]->no_hp);
        if (!$clean_phone) return;

        $detail_link = "https://office.visiyosindo.id/it_maintenance/detail_ticket/" . encrypt($ticket->id_ticket);

        $pesan = "*Notifikasi Penyelesaian Tiket IT*" .
            "%0A%0ADear *{$ticket->nama_pembuat}*," .
            "%0A%0ATiket kendala IT yang Anda ajukan telah dinyatakan *{$status}*:" .
            "%0A%0AKode Tiket : *{$ticket->kode_tiket}*" .
            "%0ASubjek     : " . urlencode($ticket->subject) .
            "%0ASolusi     : " . urlencode(strip_tags($solusi)) .
            "%0ATanggal    : " . date('d-m-Y H:i') .
            "%0A%0AMohon periksa kembali perangkat Anda. Detail progress lengkap di {$detail_link}." .
            "%0A%0ASalam," .
            "%0ATim IT PT. Visi Yosindo Medikal";

        sendWa([
            'devId' => hostWa('1'),
            'penerima' => $clean_phone,
            'pesan' => $pesan
        ]);
    }

    private function sendReporterUpdateNotification($ticket, $status, $detail)
    {
        $pembuat = $this->md_pengguna->getById($ticket->id_pembuat);
        if (empty($pembuat) || empty($pembuat[0]->no_hp)) return;

        $clean_phone = $this->normalizePhoneNumber($pembuat[0]->no_hp);
        if (!$clean_phone) return;

        $detail_link = "https://office.visiyosindo.id/it_maintenance/detail_ticket/" . encrypt($ticket->id_ticket);

        $pesan = "*Update Progress Tiket IT*" .
            "%0A%0ADear *{$ticket->nama_pembuat}*," .
            "%0A%0ATiket kendala IT yang Anda ajukan telah di-update:" .
            "%0A%0AKode Tiket : *{$ticket->kode_tiket}*" .
            "%0ASubjek     : " . urlencode($ticket->subject) .
            "%0AStatus Baru : *{$status}*" .
            "%0ACatatan    : " . urlencode(strip_tags($detail)) .
            "%0A%0APeriksa detail progress selengkapnya di {$detail_link}." .
            "%0A%0ATerima Kasih";

        sendWa([
            'devId' => hostWa('1'),
            'penerima' => $clean_phone,
            'pesan' => $pesan
        ]);
    }

    private function sendTechnicianNotification($ticket)
    {
        if (empty($ticket->no_hp_penerima)) return;

        $clean_phone = $this->normalizePhoneNumber($ticket->no_hp_penerima);
        if (!$clean_phone) return;

        $detail_link = "https://office.visiyosindo.id/it_maintenance/detail_ticket/" . encrypt($ticket->id_ticket);

        $pesan = "*Tugas Tiket IT Baru*" .
            "%0A%0ADear *{$ticket->nama_penerima}*," .
            "%0A%0AAnda telah ditugaskan untuk menyelesaikan tiket kendala IT berikut:" .
            "%0A%0AKode Tiket : *{$ticket->kode_tiket}*" .
            "%0APelapor    : " . urlencode($ticket->nama_pembuat) .
            "%0ASubjek     : " . urlencode($ticket->subject) .
            "%0ADeskripsi  : " . urlencode(strip_tags($ticket->deskripsi)) .
            "%0A%0AMohon segera merapat dan update progress penyelesaian tiket di {$detail_link}." .
            "%0A%0ATerima Kasih";

        sendWa([
            'devId' => hostWa('1'),
            'penerima' => $clean_phone,
            'pesan' => $pesan
        ]);
    }

    private function normalizePhoneNumber($phone)
    {
        if (empty($phone)) return null;
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (empty($phone)) return null;

        if (strpos($phone, '62') === 0) {
            return $phone;
        }

        if (strpos($phone, '0') === 0) {
            return '62' . substr($phone, 1);
        }

        return $phone;
    }

    private function uploadTicketFile($fieldName)
    {
        if (empty($_FILES[$fieldName]['name'])) return null;
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) return null;

        if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Gagal mengupload file. Silakan coba lagi.');
        }

        $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'xls', 'xlsx'];
        $extension = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            throw new Exception('Format file tidak didukung. Gunakan PDF, Word, Excel, atau gambar.');
        }

        $targetDir = FCPATH . 'uploads/it_maintenance/';
        if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true) && !is_dir($targetDir)) {
            throw new Exception('Gagal menyiapkan folder upload.');
        }

        $filename = 'tkt_' . date('Ymd_His') . '_' . random_int(1000, 9999) . '.' . $extension;
        $destination = $targetDir . $filename;

        if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $destination)) {
            throw new Exception('Gagal menyimpan file upload.');
        }

        return 'it_maintenance/' . $filename;
    }
}
