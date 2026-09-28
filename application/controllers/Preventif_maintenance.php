<?php

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller untuk Preventif Maintenance (PM)
 * 
 * Fitur:
 * - CRUD PM dengan auto numbering
 * - Manajemen pihak ketiga (PT/CV)
 * - History/update PM
 * - Export laporan Excel
 */
class Preventif_maintenance extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_preventif_maintenance');
        $this->load->model('md_pengguna');
        $this->load->model('md_pelanggan');
        $this->load->model('md_kategori_tiket');
        $this->load->helper('email_helper');
        $this->load->helper('whatsapp_helper');
    }

    function id_navbar()
    {
        return "helpdesk";
    }
    
    // ========================================
    // HALAMAN UTAMA - LIST PM
    // ========================================

    /**
     * Halaman list Preventif Maintenance
     */
    public function index()
    {
        grantAccessFor('all');

        $canManagePM = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
        if (!$canManagePM) {
            redirect(base_url('preventif-maintenance/show/my_pm'));
        }

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'preventif_maintenance/v_pm_list';
        $page_data['page_title'] = 'Preventif Maintenance';
        $page_data['page_desc'] = 'Management Preventif Maintenance';
        $page_data['pihak_ketiga'] = $this->md_preventif_maintenance->getPihakKetiga();

        // Count by status
        $page_data['count_open'] = $this->md_preventif_maintenance->countByStatus(1);
        $page_data['count_progress'] = $this->md_preventif_maintenance->countByStatus(2);
        $page_data['count_closed'] = $this->md_preventif_maintenance->countByStatus(3);
        $page_data['count_all'] = $page_data['count_open'] + $page_data['count_progress'] + $page_data['count_closed'];

        $this->load->view('index', $page_data);
    }

    /**
     * Pagination untuk datatables
     */
    public function pagination($param = '', $param2 = '')
    {
        grantAccessFor('all');

        if ($param === 'detail') {
            $id_pm = decrypt($this->input->post('id_pm'));
            $details = $this->md_preventif_maintenance->getDetail($id_pm);

            if (!empty($details)) {
                foreach ($details as $detail) {
                    $file = '';
                    if (!empty($detail->file_update)) {
                        $file = '<div class="mt-2"><a href="' . base_url('uploads/' . $detail->file_update) . '" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-download"></i> Download File</a></div>';
                    }

                    echo '<div class="timeline-item">';
                    echo '<div class="timeline-meta"><strong>' . htmlspecialchars($detail->nama_pembuat) . '</strong> - ' . date('d-m-Y H:i', strtotime($detail->created_at)) . '</div>';
                    echo '<div class="timeline-content">' . html_entity_decode($detail->detail) . $file . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-muted mb-0">Belum ada update</p>';
            }

            return;
        }

        $canManagePM = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;

        // Allow optional filters (e.g., owner filter from "my_pm" view)
        $filter_owner = $this->input->post('filter_owner');
        $where = [];
        if (!$canManagePM) {
            $where = "(pm.id_pembuat = " . sessPenggunaId() . " OR pm.id_penerima = " . sessPenggunaId() . ")";
        } else if (!empty($filter_owner)) {
            $where['pm.id_pembuat'] = intval($filter_owner);
        }

        $dt = $this->md_preventif_maintenance->getAll($where);
        $data = array();

        foreach ($dt as $row) {
            $id = encrypt($row->id_pm);

            // Status badge
            $statusBadge = $this->md_preventif_maintenance->getStatusBadge($row->status_pm);

            // Action buttons
            $actions = '<div class="btn-group">';
            $actions .= '<a href="' . base_url('preventif-maintenance/detail/' . $id) . '" class="btn btn-sm btn-info" title="Lihat"><i class="fas fa-eye"></i></a>';
            if ($canManagePM) {
                $actions .= '<a href="' . base_url('preventif-maintenance/edit/' . $id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>';
                $actions .= '<button type="button" class="btn btn-sm btn-danger btn-delete-pm" data-id="' . $id . '" title="Hapus"><i class="fas fa-trash"></i></button>';
            }
            $actions .= '</div>';

            // Tanggal format
            $tanggal = date('d-m-Y', strtotime($row->tanggal_pm));

            $data[] = array(
                $row->kode_pm,
                $row->subject,
                $row->nama_pihak_ketiga ?? '-',
                $tanggal,
                $row->nama_pembuat,
                $statusBadge,
                $actions
            );
        }

        $output = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => count($dt),
            "recordsFiltered" => count($dt),
            "data"            => $data
        );

        echo json_encode($output);
    }

    /**
     * New DataTables server-side endpoint that delegates to model DataTables methods.
     * If `filter_owner` POST param is present, returns PMs created by that user.
     */
    public function pagination_dt()
    {
        grantAccessFor('all');

        $canManagePM = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
        if (!$canManagePM) {
            echo $this->md_preventif_maintenance->getPmPembuat(sessPenggunaId());
            return;
        }

        $filter_owner = $this->input->post('filter_owner');

        if (!empty($filter_owner)) {
            echo $this->md_preventif_maintenance->getPmPembuat(intval($filter_owner));
            return;
        }

        echo $this->md_preventif_maintenance->getAllPm();
    }

    /**
     * Generic show handler to mimic Tiket module routes like "show/my_tiket"
     * Supports: show/my_pm (list only PM created by current user)
     */
    public function show($page = '', $param = '')
    {
        grantAccessFor('all');

        switch ($page) {
            case 'my_pm':
                $page_data['switch'] = $this->id_navbar();
                // use distinct page_name so navigation can highlight "My PM"
                $page_data['page_name'] = 'preventif_maintenance/v_my_pm';
                $page_data['page_title'] = 'My Preventif Maintenance';
                $page_data['page_desc'] = 'Daftar PM milik saya';
                // Pass filter_owner to the view so DataTable will send it on AJAX
                $page_data['filter_owner'] = sessPenggunaId();
                $page_data['pihak_ketiga'] = $this->md_preventif_maintenance->getPihakKetiga();

                // Set personal stats counts
                $id_pengguna = sessPenggunaId();
                $page_data['count_open'] = $this->md_preventif_maintenance->countByStatus(1, $id_pengguna);
                $page_data['count_progress'] = $this->md_preventif_maintenance->countByStatus(2, $id_pengguna);
                $page_data['count_closed'] = $this->md_preventif_maintenance->countByStatus(3, $id_pengguna);
                $page_data['count_all'] = $page_data['count_open'] + $page_data['count_progress'] + $page_data['count_closed'];

                $this->load->view('index', $page_data);
                break;

            case 'dashboard':
                // Simple dashboard mapping (could be enhanced later)
                $page_data['switch'] = $this->id_navbar();
                $page_data['page_name'] = 'preventif_maintenance/v_pm_dashboard';
                $page_data['page_title'] = 'Dashboard Preventif Maintenance';
                $page_data['page_desc'] = 'Ringkasan Preventif Maintenance';
                $this->load->view('index', $page_data);
                break;

            default:
                show_404();
        }
    }
    
    // ========================================
    // FORM - CREATE & EDIT
    // ========================================

    /**
     * Form tambah PM baru
     */
    public function add()
    {
        grantAccessFor('all');

        $canManagePM = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
        if (!$canManagePM) {
            redirect(base_url('preventif-maintenance/show/my_pm'));
        }

        $page_data['switch'] = $this->id_navbar();
        $page_data['mode'] = 'add';
        $page_data['page_name'] = 'preventif_maintenance/v_pm_form';
        $page_data['page_title'] = 'Tambah Preventif Maintenance';
        $page_data['page_desc'] = 'Form tambah data baru';
        $page_data['kategori'] = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);
        $page_data['pelanggan'] = $this->md_pelanggan->getByWhere(['p.status' => 1]);
        $page_data['pengguna'] = $this->md_pengguna->getBywhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'all']);
        $page_data['pihak_ketiga'] = $this->md_preventif_maintenance->getPihakKetiga();

        $this->load->view('index', $page_data);
    }

    /**
     * Form edit PM
     */
    public function edit($id = '')
    {
        grantAccessFor('all');

        $canManagePM = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
        if (!$canManagePM) {
            redirect(base_url('preventif-maintenance/show/my_pm'));
        }

        $id_pm = decrypt($id);
        $pm = $this->md_preventif_maintenance->getById($id_pm);

        if (empty($pm)) {
            show_404();
        }

        $page_data['switch'] = $this->id_navbar();
        $page_data['mode'] = 'edit';
        $page_data['pm'] = $pm[0];
        $page_data['page_name'] = 'preventif_maintenance/v_pm_form';
        $page_data['page_title'] = 'Edit Preventif Maintenance';
        $page_data['page_desc'] = 'Form edit data';
        $page_data['kategori'] = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);
        $page_data['pelanggan'] = $this->md_pelanggan->getByWhere(['p.status' => 1]);
        $page_data['pengguna'] = $this->md_pengguna->getBywhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'all']);
        $page_data['pihak_ketiga'] = $this->md_preventif_maintenance->getPihakKetiga();

        $this->load->view('index', $page_data);
    }

    /**
     * Simpan PM (create/update)
     */
    public function save()
    {
        grantAccessFor('all');

        $canManagePM = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
        if (!$canManagePM) {
            ajaxReturnDie('error', 'Akses ditolak');
        }

        $mode = $this->input->post('mode');
        $id_penerima = (int) $this->input->post('id_penerima');
        $id_pelanggan = (int) $this->input->post('id_pelanggan');
        $nama_cp = $this->input->post('nama_cp');
        $nomer_cp = $this->input->post('nomer_cp');
        $id_topik = (int) $this->input->post('id_topik');
        $prioritas = (int) $this->input->post('prioritas');

        // --- PERUBAHAN LOGIKA PIHAK KETIGA ---
        $post_id_pihak_ketiga = $this->input->post('id_pihak_ketiga');
        $id_pihak_ketiga = $post_id_pihak_ketiga !== '' ? (int) $post_id_pihak_ketiga : '';

        if ($id_pihak_ketiga === 0) {
            $nama_pihak_ketiga_val = 'Penjualan Langsung dari VYM';
        } else {
            $pihak_ketiga = $id_pihak_ketiga ? $this->md_pelanggan->getById($id_pihak_ketiga) : [];
            $nama_pihak_ketiga_val = !empty($pihak_ketiga) ? $pihak_ketiga[0]->identitas_pelanggan : null;
        }
        // -------------------------------------

        // Parse date range inputs (from form: start and end dates in dd-mm-yyyy format)
        $start_date = $this->input->post('start'); // format: dd-mm-yyyy
        $end_date = $this->input->post('end');     // format: dd-mm-yyyy

        // Convert to database format (YYYY-MM-DD)
        if (!empty($start_date)) {
            $start_parts = explode('-', $start_date);
            $waktu_mulai = $start_parts[2] . '-' . $start_parts[1] . '-' . $start_parts[0] . ' 00:00:00';
        } else {
            $waktu_mulai = null;
        }

        if (!empty($end_date)) {
            $end_parts = explode('-', $end_date);
            $waktu_selesai = $end_parts[2] . '-' . $end_parts[1] . '-' . $end_parts[0] . ' 23:59:59';
        } else {
            $waktu_selesai = null;
        }

        // File links (text input for Google Drive links)
        $file_pendukung = $this->input->post('file_pendukung');
        $file_invoice = $this->input->post('file_invoice');

        // --- PERUBAHAN VALIDASI ---
        if (empty($this->input->post('subject')) || empty($this->input->post('deskripsi')) || $post_id_pihak_ketiga === '' || empty($id_pelanggan) || empty($id_penerima) || empty($id_topik) || empty($prioritas) || empty($start_date) || empty($end_date)) {
            ajaxReturnDie('error', 'Semua field yang bertanda * (wajib) harus diisi');
        }
        // --------------------------

        $data = [
            'subject' => $this->input->post('subject'),
            'deskripsi' => $this->input->post('deskripsi'),
            'id_penerima' => $id_penerima,
            'id_pihak_ketiga' => $id_pihak_ketiga,
            'nama_pihak_ketiga' => $nama_pihak_ketiga_val,
            'waktu_mulai' => $waktu_mulai,
            'waktu_selesai' => $waktu_selesai,
            'file_invoice' => $file_invoice,
            'file_pendukung' => $file_pendukung,
            'id_pelanggan' => $id_pelanggan,
            'nama_cp' => $nama_cp,
            'nomer_cp' => $nomer_cp,
            'id_topik' => $id_topik,
            'prioritas' => $prioritas,
            'tanggal_pm' => !empty($start_date) ? $start_parts[2] . '-' . $start_parts[1] . '-' . $start_parts[0] : null,
            'status_data' => 1
        ];

        $this->db->trans_begin();

        if ($mode == 'add') {
            $data['id_pembuat'] = sessPenggunaId();
            $data['kode_pm'] = $this->md_preventif_maintenance->generateKodePm();
            $data['status_pm'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['log_pm'] = 'PM dibuat melalui form preventif maintenance';

            $id_pm = $this->md_preventif_maintenance->addPm($data);
            $message = 'Preventif Maintenance berhasil ditambahkan';

            addLog('Tambah PM', 'Menambahkan PM ' . $data['kode_pm'] . ' - ' . $data['subject']);
        } else {
            $id_pm = decrypt($this->input->post('id_pm'));
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['log_pm'] = 'PM diupdate melalui form preventif maintenance';

            $this->md_preventif_maintenance->updatePm($id_pm, $data);
            $message = 'Preventif Maintenance berhasil diupdate';

            addLog('Update PM', 'Mengupdate PM dengan ID ' . $id_pm);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Gagal menyimpan data Preventif Maintenance');
        }

        $this->db->trans_commit();

        $pmData = $this->md_preventif_maintenance->getById($id_pm);
        if (!empty($pmData)) {
            $this->sendPreventifMaintenanceNotification($pmData[0], $mode == 'add' ? 'dibuat' : 'diupdate');
        }

        ajaxReturnDie('success', $message, encrypt($id_pm));
    }
    
    // ========================================
    // DETAIL & STATUS
    // ========================================

    /**
     * Halaman detail PM
     */
    public function detail($id = '')
    {
        grantAccessFor('all');

        $id_pm = decrypt($id);
        $pm = $this->md_preventif_maintenance->getById($id_pm);

        if (empty($pm)) {
            show_404();
        }

        $page_data['switch'] = $this->id_navbar();
        $page_data['pm'] = $pm[0];
        $page_data['details'] = $this->md_preventif_maintenance->getDetail($id_pm);
        $page_data['page_name'] = 'preventif_maintenance/v_pm_detail';
        $page_data['page_title'] = 'Detail Preventif Maintenance';
        $page_data['page_desc'] = $pm[0]->subject;

        $this->load->view('index', $page_data);
    }

    /**
     * Tambah update/progress PM
     */
    public function addUpdate()
    {
        grantAccessFor('all');

        $id_pm = decrypt($this->input->post('id_pm'));
        $status_baru = $this->input->post('status_pm');
        $detail = $this->input->post('detail');

        if (empty($id_pm) || empty($detail)) {
            ajaxReturnDie('error', 'Detail update wajib diisi');
        }

        try {
            $file_update = $this->uploadPmFile('file_update');
        } catch (Exception $e) {
            ajaxReturnDie('error', $e->getMessage());
        }

        $data_detail = [
            'id_pm' => $id_pm,
            'id_pembuat' => sessPenggunaId(),
            'detail' => $detail,
            'file_update' => $file_update,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->trans_begin();
        $this->md_preventif_maintenance->addDetail($data_detail);

        // Update status PM jika ada perubahan
        if (!empty($status_baru)) {
            $this->md_preventif_maintenance->updatePm($id_pm, ['status_pm' => $status_baru]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Gagal menambahkan update PM');
        }

        $this->db->trans_commit();

        $pmData = $this->md_preventif_maintenance->getById($id_pm);
        if (!empty($pmData)) {
            $this->sendPreventifMaintenanceNotification($pmData[0], 'diupdate', $detail);
        }

        addLog('Update PM', 'Menambahkan update untuk PM ID ' . $id_pm);

        ajaxReturnDie('success', 'Update berhasil ditambahkan', TRUE);
    }

    /**
     * Update status PM
     */
    public function updateStatus()
    {
        grantAccessFor('all');

        $id_pm = decrypt($this->input->post('id_pm'));
        $status_baru = $this->input->post('status');

        $this->md_preventif_maintenance->updatePm($id_pm, ['status_pm' => $status_baru]);

        addLog('Update Status PM', 'Mengubah status PM ID ' . $id_pm . ' menjadi ' . $status_baru);

        ajaxReturnDie('success', 'Status berhasil diupdate', TRUE);
    }
    
    // ========================================
    // DELETE
    // ========================================

    /**
     * Hapus PM
     */
    public function delete()
    {
        grantAccessFor(['Administrator']);

        $id_pm = decrypt($this->input->post('id'));

        $this->md_preventif_maintenance->deletePm($id_pm);

        addLog('Hapus PM', 'Menghapus PM dengan ID ' . $id_pm);

        ajaxReturnDie('success', 'Preventif Maintenance berhasil dihapus', TRUE);
    }
    
    // ========================================
    // EXPORT
    // ========================================

    /**
     * Export PM ke Excel
     */
    public function export()
    {
        grantAccessFor('all');

        $data = $this->md_preventif_maintenance->getAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Kode PM');
        $sheet->setCellValue('B1', 'Subject');
        $sheet->setCellValue('C1', 'Pihak Ketiga');
        $sheet->setCellValue('D1', 'Tanggal');
        $sheet->setCellValue('E1', 'Pembuat');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Dibuat Pada');

        // Data
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item->kode_pm);
            $sheet->setCellValue('B' . $row, $item->subject);
            $sheet->setCellValue('C' . $row, $item->nama_pihak_ketiga ?? '-');
            $sheet->setCellValue('D' . $row, date('d-m-Y', strtotime($item->tanggal_pm)));
            $sheet->setCellValue('E' . $row, $item->nama_pembuat);
            $sheet->setCellValue('F' . $row, $this->md_preventif_maintenance->getStatusLabel($item->status_pm));
            $sheet->setCellValue('G' . $row, date('d-m-Y H:i', strtotime($item->created_at)));
            $row++;
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(18);

        // Export
        $writer = new Xlsx($spreadsheet);
        $filename = 'Preventif_Maintenance_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    private function normalizeDateInput($value)
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);
        $formats = ['Y-m-d', 'd-m-Y', 'd/m/Y'];

        foreach ($formats as $format) {
            $dateTime = DateTime::createFromFormat($format, $value);
            if ($dateTime instanceof DateTime) {
                return $dateTime->format('Y-m-d');
            }
        }

        $timestamp = strtotime($value);
        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }

    private function normalizeDateTimeInput($value)
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);
        $formats = ['Y-m-d\TH:i', 'Y-m-d\TH:i:s', 'Y-m-d H:i:s', 'Y-m-d H:i', 'd-m-Y H:i', 'd/m/Y H:i'];

        foreach ($formats as $format) {
            $dateTime = DateTime::createFromFormat($format, $value);
            if ($dateTime instanceof DateTime) {
                return $dateTime->format('Y-m-d H:i:s');
            }
        }

        $timestamp = strtotime($value);
        return $timestamp ? date('Y-m-d H:i:s', $timestamp) : null;
    }

    private function uploadPmFile($fieldName)
    {
        if (empty($_FILES[$fieldName]['name'])) {
            return null;
        }

        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Gagal mengupload file. Silakan coba lagi.');
        }

        $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'xls', 'xlsx'];
        $extension = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            throw new Exception('Format file tidak didukung. Gunakan PDF, Word, Excel, atau gambar.');
        }

        $targetDir = FCPATH . 'uploads/preventif_maintenance/';
        if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true) && !is_dir($targetDir)) {
            throw new Exception('Gagal menyiapkan folder upload Preventif Maintenance.');
        }

        $filename = 'pm_' . date('Ymd_His') . '_' . random_int(1000, 9999) . '.' . $extension;
        $destination = $targetDir . $filename;

        if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $destination)) {
            throw new Exception('Gagal menyimpan file upload.');
        }

        return 'preventif_maintenance/' . $filename;
    }

    private function normalizePhoneNumber($phone)
    {
        if (empty($phone)) {
            return null;
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (empty($phone)) {
            return null;
        }

        if (strpos($phone, '62') === 0) {
            return $phone;
        }

        if (strpos($phone, '0') === 0) {
            return '62' . substr($phone, 1);
        }

        return $phone;
    }

    private function sendPreventifMaintenanceNotification($pm, $action = 'dibuat', $detail = '')
    {
        $nomorTujuan = [];

        if (!empty($pm->id_pembuat)) {
            $dataPembuat = $this->md_pengguna->getById($pm->id_pembuat);
            if (!empty($dataPembuat) && !empty($dataPembuat[0]->no_hp)) {
                $nomorTujuan[] = $this->normalizePhoneNumber($dataPembuat[0]->no_hp);
            }
        }

        if (!empty($pm->id_penerima)) {
            $dataPenerima = $this->md_pengguna->getById($pm->id_penerima);
            if (!empty($dataPenerima) && !empty($dataPenerima[0]->no_hp)) {
                $nomorTujuan[] = $this->normalizePhoneNumber($dataPenerima[0]->no_hp);
            }
        }

        $nomorTujuan = array_filter(array_unique($nomorTujuan));
        if (empty($nomorTujuan)) {
            return;
        }

        $statusLabel = $this->md_preventif_maintenance->getStatusLabel($pm->status_pm);
        $namaPenerima = !empty($pm->nama_penerima) ? $pm->nama_penerima : '-';
        $namaPelanggan = !empty($pm->nama_pelanggan) ? $pm->nama_pelanggan : '-';

        $pesan = '*Notifikasi Preventif Maintenance*' .
            '%0A%0ADear ' . $namaPenerima . ',' .
            '%0A%0APreventif Maintenance baru saja ' . $action . ':' .
            '%0A%0AKode      : ' . $pm->kode_pm .
            '%0ASubjek    : ' . urlencode($pm->subject) .
            '%0APelanggan : *' . urlencode($namaPelanggan) . '*' .
            '%0ATeknisi   : *' . urlencode($namaPenerima) . '*' .
            '%0AStatus    : ' . $statusLabel .
            (!empty($detail) ? '%0AUpdate    : ' . urlencode(strip_tags($detail)) : '') .
            '%0A%0ASegera periksa detail Preventif Maintenance anda di https://office.visiyosindo.id' .
            '%0A%0ATerima Kasih';

        foreach ($nomorTujuan as $nomor) {
            sendWa([
                'devId' => hostWa('1'),
                'penerima' => $nomor,
                'pesan' => $pesan
            ]);
        }
    }
}
