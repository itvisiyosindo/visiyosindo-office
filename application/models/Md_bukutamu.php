<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_bukutamu extends CI_Model
{

    private $submitLogTable = 'bukutamu_submit_log';
    private $submitLogTableReady = null;

    private function normalizeKegiatanId($kegiatan_id)
    {
        $kegiatan_id = (int) $kegiatan_id;

        return $kegiatan_id > 0 ? $kegiatan_id : null;
    }

    private function normalizeTanggal($tanggal)
    {
        $tanggal = trim((string) $tanggal);
        if ($tanggal === '') {
            return null;
        }

        $dateObj = DateTime::createFromFormat('Y-m-d', $tanggal);
        if ($dateObj && $dateObj->format('Y-m-d') === $tanggal) {
            return $tanggal;
        }

        return null;
    }

    private function applyBukuTamuFilter($kegiatan_id = null, $tanggal_mulai = null, $tanggal_selesai = null, $alias = 'bt')
    {
        $kegiatan_id = $this->normalizeKegiatanId($kegiatan_id);
        $tanggal_mulai = $this->normalizeTanggal($tanggal_mulai);
        $tanggal_selesai = $this->normalizeTanggal($tanggal_selesai);

        if (!empty($kegiatan_id)) {
            $this->db->where($alias . '.kegiatan', $kegiatan_id);
        }

        if (!empty($tanggal_mulai)) {
            $this->db->where($alias . '.created_at >=', $tanggal_mulai . ' 00:00:00');
        }

        if (!empty($tanggal_selesai)) {
            $this->db->where($alias . '.created_at <=', $tanggal_selesai . ' 23:59:59');
        }
    }

    private function normalizeIpAddress($ipAddress)
    {
        $ipAddress = trim((string) $ipAddress);

        if ($ipAddress === '' || $ipAddress === '0.0.0.0') {
            $ipAddress = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
        }

        if ($ipAddress === '') {
            return null;
        }

        return substr($ipAddress, 0, 45);
    }

    private function normalizeUserAgent($userAgent)
    {
        $userAgent = trim((string) $userAgent);

        return $userAgent === '' ? null : substr($userAgent, 0, 255);
    }

    private function ensureSubmitLogTable()
    {
        if ($this->submitLogTableReady !== null) {
            return $this->submitLogTableReady;
        }

        if ($this->db->table_exists($this->submitLogTable)) {
            $this->submitLogTableReady = true;

            return true;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->submitLogTable}` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `kegiatan_id` INT NULL,
            `nama` VARCHAR(120) NULL,
            `nomorwa` VARCHAR(20) NULL,
            `nama_key` VARCHAR(120) NULL,
            `nomorwa_key` VARCHAR(32) NULL,
            `ip_address` VARCHAR(45) NULL,
            `user_agent` VARCHAR(255) NULL,
            `is_duplicate` TINYINT(1) NOT NULL DEFAULT 0,
            `status_note` VARCHAR(120) NULL,
            `created_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_nama_nomorwa` (`nama_key`, `nomorwa_key`),
            KEY `idx_ip_address` (`ip_address`),
            KEY `idx_created_at` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $dbDebug = $this->db->db_debug;
        $this->db->db_debug = false;
        $created = $this->db->query($sql);
        $this->db->db_debug = $dbDebug;
        $this->submitLogTableReady = ($created !== false) || $this->db->table_exists($this->submitLogTable);

        return $this->submitLogTableReady;
    }

    private function logSubmitAttempt($kegiatan_id, $nama, $nomorwa, $namaKey, $noWaKey, $ipAddress, $userAgent, $isDuplicate, $statusNote)
    {
        $logData = [
            'kegiatan_id' => !empty($kegiatan_id) ? (int) $kegiatan_id : null,
            'nama' => substr((string) $nama, 0, 120),
            'nomorwa' => substr((string) $nomorwa, 0, 20),
            'nama_key' => substr((string) $namaKey, 0, 120),
            'nomorwa_key' => substr((string) $noWaKey, 0, 32),
            'ip_address' => $this->normalizeIpAddress($ipAddress),
            'user_agent' => $this->normalizeUserAgent($userAgent),
            'is_duplicate' => !empty($isDuplicate) ? 1 : 0,
            'status_note' => substr((string) $statusNote, 0, 120),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->ensureSubmitLogTable()) {
            $dbDebug = $this->db->db_debug;
            $this->db->db_debug = false;
            $this->db->insert($this->submitLogTable, $logData);
            $this->db->db_debug = $dbDebug;
        }

        log_message(
            'info',
            '[BukuTamu] status=' . $logData['status_note']
                . ' duplicate=' . $logData['is_duplicate']
                . ' kegiatan=' . (string) $logData['kegiatan_id']
                . ' nama_key=' . $logData['nama_key']
                . ' nomorwa_key=' . $logData['nomorwa_key']
                . ' ip=' . (string) $logData['ip_address']
        );
    }

    private function acquireDuplicateLock($lockName, $timeout = 5)
    {
        $driver = strtolower((string) $this->db->dbdriver);
        if (!in_array($driver, ['mysql', 'mysqli'], true)) {
            return false;
        }

        $query = $this->db->query('SELECT GET_LOCK(?, ?) AS lock_status', [$lockName, (int) $timeout]);
        if (!$query) {
            return false;
        }

        $row = $query->row();

        return !empty($row) && (int) $row->lock_status === 1;
    }

    private function releaseDuplicateLock($lockName)
    {
        $driver = strtolower((string) $this->db->dbdriver);
        if (!in_array($driver, ['mysql', 'mysqli'], true)) {
            return;
        }

        @$this->db->query('SELECT RELEASE_LOCK(?)', [$lockName]);
    }

    private function findDuplicateByNormalizedValue($namaKey, $noWaKey, $kegiatan_id = null)
    {
        if ($namaKey === '' || $noWaKey === '') {
            return null;
        }

        $kegiatan_id = $this->normalizeKegiatanId($kegiatan_id);

        $query = $this->db
            ->select('id_bukutamu, nama, nomorwa, kegiatan, created_at')
            ->from('bukutamu');

        if (!empty($kegiatan_id)) {
            $query->where('kegiatan', $kegiatan_id);
        }

        $rows = $query->get()->result();
        foreach ($rows as $row) {
            if ($this->normalizeNama($row->nama) === $namaKey && $this->normalizeNoWa($row->nomorwa) === $noWaKey) {
                return $row;
            }
        }

        return null;
    }

    public function findDuplicateByNoWaInEvent($kegiatan_id, $nomorwa)
    {
        $kegiatan_id = (int) $kegiatan_id;
        $noWaKey = $this->normalizeNoWa($nomorwa);

        if ($kegiatan_id <= 0 || $noWaKey === '') {
            return null;
        }

        $exactRow = $this->db
            ->select('id_bukutamu, nama, nomorwa, kegiatan, created_at')
            ->from('bukutamu')
            ->where('kegiatan', $kegiatan_id)
            ->where('nomorwa', $noWaKey)
            ->order_by('id_bukutamu', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        if (!empty($exactRow)) {
            return $exactRow;
        }

        $rows = $this->db
            ->select('id_bukutamu, nama, nomorwa, kegiatan, created_at')
            ->from('bukutamu')
            ->where('kegiatan', $kegiatan_id)
            ->get()
            ->result();

        foreach ($rows as $row) {
            if ($this->normalizeNoWa($row->nomorwa) === $noWaKey) {
                return $row;
            }
        }

        return null;
    }

    public function findDuplicateByNamaNoWa($nama, $nomorwa, $kegiatan_id = null)
    {
        $namaKey = $this->normalizeNama($nama);
        $noWaKey = $this->normalizeNoWa($nomorwa);

        return $this->findDuplicateByNormalizedValue($namaKey, $noWaKey, $kegiatan_id);
    }

    public function saveWithDuplicateGuard($data, $ipAddress = '', $userAgent = '')
    {
        $nama = isset($data['nama']) ? (string) $data['nama'] : '';
        $nomorwa = isset($data['nomorwa']) ? (string) $data['nomorwa'] : '';
        $kegiatan_id = isset($data['kegiatan']) ? (int) $data['kegiatan'] : null;

        $namaKey = $this->normalizeNama($nama);
        $noWaKey = $this->normalizeNoWa($nomorwa);
        $ipAddress = $this->normalizeIpAddress($ipAddress);
        $userAgent = $this->normalizeUserAgent($userAgent);

        if ($namaKey === '' || $noWaKey === '' || empty($kegiatan_id)) {
            $this->logSubmitAttempt($kegiatan_id, $nama, $nomorwa, $namaKey, $noWaKey, $ipAddress, $userAgent, 0, 'invalid-input');

            return [
                'status' => 'invalid',
                'message' => 'Nama, nomor WhatsApp, atau event tidak valid.',
            ];
        }

        $lockName = 'bukutamu_dup_' . sha1((string) $kegiatan_id . '|' . $noWaKey);
        $lockAcquired = $this->acquireDuplicateLock($lockName, 5);

        $duplicateRow = $this->findDuplicateByNoWaInEvent($kegiatan_id, $noWaKey);
        if (!empty($duplicateRow)) {
            $this->logSubmitAttempt($kegiatan_id, $nama, $nomorwa, $namaKey, $noWaKey, $ipAddress, $userAgent, 1, 'duplicate-phone-event-blocked');

            if ($lockAcquired) {
                $this->releaseDuplicateLock($lockName);
            }

            return [
                'status' => 'duplicate',
                'message' => 'Data duplikat: nomor WhatsApp sudah terdaftar pada event yang sama.',
                'duplicate_row' => $duplicateRow,
            ];
        }

        if ($this->db->field_exists('ip_addr', 'bukutamu')) {
            $data['ip_addr'] = $ipAddress;
        } elseif ($this->db->field_exists('ip_address', 'bukutamu')) {
            $data['ip_address'] = $ipAddress;
        }

        if ($this->db->field_exists('user_agent', 'bukutamu') && !isset($data['user_agent'])) {
            $data['user_agent'] = $userAgent;
        }

        $inserted = $this->db->insert('bukutamu', $data);

        $this->logSubmitAttempt(
            $kegiatan_id,
            $nama,
            $nomorwa,
            $namaKey,
            $noWaKey,
            $ipAddress,
            $userAgent,
            0,
            $inserted ? 'inserted' : 'insert-failed'
        );

        if ($lockAcquired) {
            $this->releaseDuplicateLock($lockName);
        }

        if (!$inserted) {
            return [
                'status' => 'failed',
                'message' => 'Gagal menyimpan data ke database.',
            ];
        }

        return [
            'status' => 'inserted',
            'message' => 'Data berhasil disimpan.',
        ];
    }

    public function normalizeNama($nama)
    {
        $nama = trim((string) $nama);
        $nama = preg_replace('/\s+/', ' ', $nama);

        return strtolower($nama);
    }

    public function normalizeNoWa($nomorwa)
    {
        $digits = preg_replace('/\D+/', '', (string) $nomorwa);

        if ($digits === '') {
            return '';
        }

        if (strpos($digits, '62') === 0) {
            return $digits;
        }

        if (strpos($digits, '0') === 0) {
            return '62' . substr($digits, 1);
        }

        if (strpos($digits, '8') === 0) {
            return '62' . $digits;
        }

        return $digits;
    }

    public function isDuplicateInEvent($kegiatan_id, $nama, $nomorwa)
    {
        return $this->findDuplicateByNoWaInEvent((int) $kegiatan_id, $nomorwa) !== null;
    }

    public function get_provinsi()
    {
        // Misalnya, method ini mengambil daftar provinsi dari database
        $query = $this->db->get('provinsi');
        return $query->result();
    }

    public function insert_data($data)
    {
        // Misalnya, method ini memasukkan data ke dalam database
        return $this->db->insert('bukutamu', $data);
    }

    function getAllBukuTamu($kegiatan_id = null, $tanggal_mulai = null, $tanggal_selesai = null)
    {
        $this->db->order_by('bt.created_at', 'DESC');
        $dt = $this->datatables
            ->select('  
            bt.nama,
            bt.id_bukutamu,
            bt.nomorwa,
            bt.email,
            bt.jabatan,
            bt.instansi,
            bt.tipe_rs,
            bt.provinsi,
            bt.kota,
            bt.kegiatan,
            bt.kebutuhan,
            bt.created_at,
            bc.nama as nama_kegiatan
        ')
            ->from('bukutamu bt')
            ->join('bukutamu_config bc', 'bt.kegiatan=bc.id');

        $kegiatan_id = $this->normalizeKegiatanId($kegiatan_id);
        $tanggal_mulai = $this->normalizeTanggal($tanggal_mulai);
        $tanggal_selesai = $this->normalizeTanggal($tanggal_selesai);

        if (!empty($kegiatan_id)) {
            $dt->where('bt.kegiatan', $kegiatan_id);
        }

        if (!empty($tanggal_mulai)) {
            $dt->where('bt.created_at >=', $tanggal_mulai . ' 00:00:00');
        }

        if (!empty($tanggal_selesai)) {
            $dt->where('bt.created_at <=', $tanggal_selesai . ' 23:59:59');
        }

        return $dt->generate();
    }

    function getTotalIsi()
    {
        $result = $this->db->get_where('bukutamu');
        return $result->num_rows();
    }

    function getTotalIsiByKegiatan($kegiatan_id)
    {
        return (int) $this->db
            ->where('kegiatan', (int) $kegiatan_id)
            ->count_all_results('bukutamu');
    }

    function add($data)
    {
        $this->db->insert('bukutamu_config', $data);
        return $this->db->insert_id();
    }

    function getLastId()
    {
        return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('bukutamu_config')->row();
    }

    function getKegiatan()
    {
        return $this->db->get_where('bukutamu_config pg')->result();
    }

    public function getKegiatanById($kegiatan_id)
    {
        return $this->db
            ->where('id', (int) $kegiatan_id)
            ->get('bukutamu_config')
            ->row();
    }

    public function isKegiatanExists($kegiatan_id)
    {
        return $this->db
            ->where('id', (int) $kegiatan_id)
            ->count_all_results('bukutamu_config') > 0;
    }

    public function getManagementStats($kegiatan_id = null, $tanggal_mulai = null, $tanggal_selesai = null)
    {
        $kegiatan_id = $this->normalizeKegiatanId($kegiatan_id);
        $tanggal_mulai = $this->normalizeTanggal($tanggal_mulai);
        $tanggal_selesai = $this->normalizeTanggal($tanggal_selesai);

        $this->db->from('bukutamu bt');
        $this->applyBukuTamuFilter($kegiatan_id, $tanggal_mulai, $tanggal_selesai, 'bt');
        $total_data = (int) $this->db->count_all_results();

        $this->db->from('bukutamu bt');
        $this->applyBukuTamuFilter($kegiatan_id, $tanggal_mulai, $tanggal_selesai, 'bt');
        $this->db->where('bt.created_at >=', date('Y-m-d') . ' 00:00:00');
        $this->db->where('bt.created_at <=', date('Y-m-d') . ' 23:59:59');
        $total_hari_ini = (int) $this->db->count_all_results();

        $instansi_row = $this->db->select('COUNT(DISTINCT NULLIF(TRIM(bt.instansi), "")) AS total', false)
            ->from('bukutamu bt');
        $this->applyBukuTamuFilter($kegiatan_id, $tanggal_mulai, $tanggal_selesai, 'bt');
        $instansi_row = $this->db->get()->row();
        $total_instansi_unik = (int) ($instansi_row->total ?? 0);

        $nomorwa_row = $this->db->select('COUNT(DISTINCT NULLIF(TRIM(bt.nomorwa), "")) AS total', false)
            ->from('bukutamu bt');
        $this->applyBukuTamuFilter($kegiatan_id, $tanggal_mulai, $tanggal_selesai, 'bt');
        $nomorwa_row = $this->db->get()->row();
        $total_nomorwa_unik = (int) ($nomorwa_row->total ?? 0);

        $latest_row = $this->db->select('bt.created_at')
            ->from('bukutamu bt');
        $this->applyBukuTamuFilter($kegiatan_id, $tanggal_mulai, $tanggal_selesai, 'bt');
        $latest_row = $this->db->order_by('bt.created_at', 'DESC')->limit(1)->get()->row();

        return [
            'total_data' => $total_data,
            'total_hari_ini' => $total_hari_ini,
            'total_instansi_unik' => $total_instansi_unik,
            'total_nomorwa_unik' => $total_nomorwa_unik,
            'last_input_at' => !empty($latest_row->created_at) ? $latest_row->created_at : null,
        ];
    }

    // Untuk export data ke Excel PELANGGAN
    function getAllKegiatan($kegiatan = NULL)
    {
        $this->db->order_by('bt.created_at', 'DESC');
        //return $this->db
        $query = $this->db
            ->select('
                        bt.nama,
                        bt.id_bukutamu,
                        bt.nomorwa,
                        bt.email,
                        bt.jabatan,
                        bt.instansi,
                        bt.tipe_rs,
                        bt.provinsi,
                        bt.kota,
                        bt.kegiatan,
                        bt.created_at,
                        bc.nama as nama_kegiatan


            ')
            ->from('bukutamu bt')
            ->join('bukutamu_config bc', 'bt.kegiatan=bc.id');

        // Kondisi jika ada marketing yang dipilih
        if ($kegiatan != NULL) {
            $query->where('bc.nama', $kegiatan);
        }

        return $query->get()->result();
    }

    public function updateBukuTamu($id, $data)
    {
        $this->db->where('id_bukutamu', $id);
        return $this->db->update('bukutamu', $data);
    }

    public function deleteBukuTamu($id)
    {
        $this->db->where('id_bukutamu', $id);
        return $this->db->delete('bukutamu');
    }

    public function getBukuTamuById($id)
    {
        return $this->db->get_where('bukutamu', ['id_bukutamu' => $id])->row();
    }
}