<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Db_backup extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Allow public CLI and public URL triggers to bypass session login
        $is_cli = $this->input->is_cli_request();
        $is_cron_url = ($this->uri->segment(2) === 'cron_backup');

        if (!$is_cli && !$is_cron_url) {
            grantAccessFor('all');
            if (!isAdmin()) {
                redirect(base_url('dashboard'));
            }
        }
    }

    private function get_backup_config()
    {
        $config_path = APPPATH . 'config/db_backup_config.json';
        if (!file_exists($config_path)) {
            $default_config = [
                'gdrive_folder_id' => '1F8sn2-UYz7tqj0MKn7DmbhVQT9vSoKne',
                'cron_enabled' => false,
                'cron_schedule' => 'daily',
                'cron_token' => 'VYBACKUP_' . bin2hex(random_bytes(8))
            ];
            file_put_contents($config_path, json_encode($default_config, JSON_PRETTY_PRINT));
            return $default_config;
        }

        return json_decode(file_get_contents($config_path), true);
    }

    private function save_backup_config($config)
    {
        $config_path = APPPATH . 'config/db_backup_config.json';
        file_put_contents($config_path, json_encode($config, JSON_PRETTY_PRINT));
    }

    private function get_gdrive_client()
    {
        require_once APPPATH . 'vendor/autoload.php';
        $oauth_path = APPPATH . 'config/google_drive_oauth.json';
        if (!file_exists($oauth_path)) {
            throw new Exception("Konfigurasi google_drive_oauth.json tidak ditemukan.");
        }

        $oauth_data = json_decode(file_get_contents($oauth_path), true);
        if (!isset($oauth_data['web']['client_id']) || !isset($oauth_data['web']['client_secret']) || !isset($oauth_data['refresh_token'])) {
            throw new Exception("Struktur google_drive_oauth.json tidak valid.");
        }

        $client = new Google\Client();
        $client->setClientId($oauth_data['web']['client_id']);
        $client->setClientSecret($oauth_data['web']['client_secret']);
        $client->setScopes(Google\Service\Drive::DRIVE);
        $client->setAccessType('offline');

        $token = $client->refreshToken($oauth_data['refresh_token']);
        if (isset($token['error'])) {
            throw new Exception("Gagal memperbarui token Google: " . $token['error_description'] . " (" . $token['error'] . ")");
        }

        return $client;
    }

    public function index()
    {
        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'v_db_backup';
        $page_data['page_title'] = 'Database Backup';
        $page_data['page_desc'] = 'Manajemen Pencadangan Database & Sinkronisasi Google Drive';

        // 1. Load backup configurations
        $config = $this->get_backup_config();
        $page_data['gdrive_folder_id'] = $config['gdrive_folder_id'];
        $page_data['cron_enabled'] = $config['cron_enabled'];
        $page_data['cron_schedule'] = $config['cron_schedule'];
        $page_data['cron_token'] = $config['cron_token'];

        // 2. Get database info
        $page_data['db_name'] = $this->db->database;
        $page_data['tables_count'] = count($this->db->list_tables());

        $size_query = $this->db->query("SELECT SUM(data_length + index_length) AS size FROM information_schema.TABLES WHERE table_schema = " . $this->db->escape($this->db->database))->row();
        $page_data['db_size'] = $size_query ? (float)$size_query->size : 0.0;

        // 3. Fetch backups list from Google Drive (using configured folder ID)
        $page_data['backups'] = [];
        $page_data['gdrive_error'] = NULL;

        try {
            $client = $this->get_gdrive_client();
            $service = new Google\Service\Drive($client);

            $optParams = [
                'q' => "'" . $config['gdrive_folder_id'] . "' in parents and trashed = false",
                'fields' => 'files(id, name, size, createdTime)',
                'orderBy' => 'createdTime desc'
            ];
            $results = $service->files->listFiles($optParams);
            $page_data['backups'] = $results->getFiles();
        } catch (Exception $e) {
            $page_data['gdrive_error'] = $e->getMessage();
        }

        $this->load->view('index', $page_data);
    }

    public function save_config()
    {
        if (!isAdmin()) {
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            return;
        }

        $folder_id = $this->input->post('gdrive_folder_id');
        $cron_enabled = $this->input->post('cron_enabled') === '1';
        $cron_schedule = $this->input->post('cron_schedule');

        if (empty($folder_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Folder ID tidak boleh kosong.']);
            return;
        }

        if (!in_array($cron_schedule, ['daily', 'weekly', 'monthly'])) {
            $cron_schedule = 'daily';
        }

        try {
            $config = $this->get_backup_config();
            $config['gdrive_folder_id'] = $folder_id;
            $config['cron_enabled'] = $cron_enabled;
            $config['cron_schedule'] = $cron_schedule;

            $this->save_backup_config($config);

            addLog('Ubah Konfigurasi Backup', 'Mengubah Folder ID ke: ' . $folder_id . ' dan Cron: ' . ($cron_enabled ? 'Aktif' : 'Nonaktif') . ' (' . $cron_schedule . ')');

            echo json_encode(['status' => 'success', 'message' => 'Konfigurasi berhasil disimpan.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan konfigurasi: ' . $e->getMessage()]);
        }
    }

    public function test_connection()
    {
        if (!isAdmin()) {
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            return;
        }

        $folder_id = $this->input->post('gdrive_folder_id');
        if (empty($folder_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Folder ID tidak boleh kosong.']);
            return;
        }

        try {
            $client = $this->get_gdrive_client();
            $service = new Google\Service\Drive($client);

            // Test list files
            $optParams = [
                'q' => "'$folder_id' in parents and trashed = false",
                'fields' => 'files(id, name)',
                'pageSize' => 1
            ];
            $service->files->listFiles($optParams);

            echo json_encode([
                'status' => 'success',
                'message' => 'Koneksi ke Google Drive sukses! Folder ID dapat diakses dengan baik.'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal mengakses Folder Google Drive: ' . $e->getMessage()
            ]);
        }
    }

    public function create_backup()
    {
        if (!isAdmin()) {
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            return;
        }

        // Increase memory and time limits for large databases
        ini_set('memory_limit', '1024M');
        set_time_limit(300);

        $config = $this->get_backup_config();

        $this->load->dbutil();
        $this->load->helper('file');

        $filename = 'backup-' . $this->db->database . '-' . date('Ymd-His') . '.zip';
        $filepath = APPPATH . 'cache/' . $filename;

        $prefs = [
            'format' => 'zip',
            'filename' => $this->db->database . '.sql',
            'add_drop' => TRUE,
            'add_insert' => TRUE,
            'newline' => "\n",
            'ignore' => ['log', 'absensibackup', 'absensi_270624']
        ];

        $backup = $this->dbutil->backup($prefs);

        if (!write_file($filepath, $backup)) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menulis file cadangan lokal di server.']);
            return;
        }

        // Free memory immediately
        unset($backup);

        try {
            $client = $this->get_gdrive_client();
            $service = new Google\Service\Drive($client);

            $fileMetadata = new Google\Service\Drive\DriveFile([
                'name' => $filename,
                'parents' => [$config['gdrive_folder_id']]
            ]);

            $content = file_get_contents($filepath);
            $file = $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => 'application/zip',
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);

            // Remove local temp file
            @unlink($filepath);

            // Log activity
            addLog('Cadangkan Database ke Google Drive', 'Sukses mengunggah file cadangan: ' . $filename . ' (ID: ' . $file->id . ')');

            echo json_encode([
                'status' => 'success',
                'message' => 'Database berhasil dicadangkan dan diunggah ke Google Drive.',
                'file_id' => $file->id
            ]);
        } catch (Exception $e) {
            @unlink($filepath);
            echo json_encode(['status' => 'error', 'message' => 'Google Drive Error: ' . $e->getMessage()]);
        }
    }

    public function download($file_id)
    {
        if (!isAdmin()) {
            show_error('Akses ditolak.', 403);
        }

        try {
            $client = $this->get_gdrive_client();
            $service = new Google\Service\Drive($client);

            $file = $service->files->get($file_id, ['fields' => 'name']);
            $filename = $file->name;

            $response = $service->files->get($file_id, ['alt' => 'media']);
            $content = $response->getBody()->getContents();

            $this->load->helper('download');
            force_download($filename, $content);
        } catch (Exception $e) {
            show_error('Gagal mengunduh file dari Google Drive: ' . $e->getMessage(), 500);
        }
    }

    public function delete($file_id)
    {
        if (!isAdmin()) {
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            return;
        }

        try {
            $client = $this->get_gdrive_client();
            $service = new Google\Service\Drive($client);

            $file = $service->files->get($file_id, ['fields' => 'name']);
            $filename = $file->name;

            $service->files->delete($file_id);

            // Log activity
            addLog('Hapus Cadangan Database Google Drive', 'Sukses menghapus file cadangan: ' . $filename . ' (ID: ' . $file_id . ')');

            echo json_encode(['status' => 'success', 'message' => 'File cadangan berhasil dihapus dari Google Drive.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus file dari Google Drive: ' . $e->getMessage()]);
        }
    }

    // CLI-based Auto Backup Cron Trigger
    public function cli_backup()
    {
        if (!$this->input->is_cli_request()) {
            show_error('Hanya diperbolehkan melalui Command Line.', 403);
            return;
        }

        $config = $this->get_backup_config();
        if (!$config['cron_enabled']) {
            echo "Auto Backup nonaktif.\n";
            return;
        }

        echo "Memulai Auto Backup...\n";
        $this->run_auto_backup($config, 'CLI Cronjob');
        echo "Auto Backup Selesai!\n";
    }

    // URL-based Auto Backup Cron Trigger (Secured with token)
    public function cron_backup($token = '')
    {
        $config = $this->get_backup_config();
        if (empty($token) || $token !== $config['cron_token']) {
            show_error('Token tidak valid.', 403);
            return;
        }

        if (!$config['cron_enabled']) {
            show_error('Auto Backup nonaktif.', 400);
            return;
        }

        $this->run_auto_backup($config, 'Web URL Cronjob');
        echo "Pencadangan Otomatis Berhasil.";
    }

    private function run_auto_backup($config, $trigger_source)
    {
        // Increase memory and time limits for large databases
        ini_set('memory_limit', '1024M');
        set_time_limit(300);

        $this->load->dbutil();
        $this->load->helper('file');

        $filename = 'backup-' . $this->db->database . '-' . date('Ymd-His') . '.zip';
        $filepath = APPPATH . 'cache/' . $filename;

        $prefs = [
            'format' => 'zip',
            'filename' => $this->db->database . '.sql',
            'add_drop' => TRUE,
            'add_insert' => TRUE,
            'newline' => "\n",
            'ignore' => ['log', 'absensibackup', 'absensi_270624']
        ];

        $backup = $this->dbutil->backup($prefs);
        if (!write_file($filepath, $backup)) {
            log_message('error', 'Auto Backup Gagal: Tidak bisa menulis file cadangan lokal.');
            return;
        }

        // Free memory immediately
        unset($backup);

        try {
            $client = $this->get_gdrive_client();
            $service = new Google\Service\Drive($client);

            $fileMetadata = new Google\Service\Drive\DriveFile([
                'name' => $filename,
                'parents' => [$config['gdrive_folder_id']]
            ]);

            $content = file_get_contents($filepath);
            $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => 'application/zip',
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);

            @unlink($filepath);

            addLog('Cadangkan Database Otomatis', 'Pencadangan berhasil dipicu oleh ' . $trigger_source . '. File: ' . $filename);
        } catch (Exception $e) {
            @unlink($filepath);
            log_message('error', 'Auto Backup Google Drive Error: ' . $e->getMessage());
        }
    }

    private function id_navbar()
    {
        $role = sessPenggunaId();
        if ($role == '54' || $role == '72' || $role == '77') {
            return 1; // Kantor Pusat
        }
        return 0;
    }
}
