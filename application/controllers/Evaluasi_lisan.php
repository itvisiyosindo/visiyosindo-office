<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Evaluasi_lisan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pengguna');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('encrypt_helper');
        $this->_ensureTablesExist();
    }

    private function _ensureTablesExist()
    {
        $this->db->db_debug = FALSE;
        @$this->db->query("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_general_ci'");

        $sql = "CREATE TABLE IF NOT EXISTS `evaluasi_lisan` (
          `id_evaluasi_lisan` int(11) NOT NULL AUTO_INCREMENT,
          `kode_evaluasi` varchar(40) NOT NULL,
          `id_pengguna` int(11) NOT NULL,
          `tanggal_evaluasi` date NOT NULL,
          `masukan_manajemen` text DEFAULT NULL,
          `masukan_karyawan` text DEFAULT NULL,
          `masukan_lingkungan` text DEFAULT NULL,
          `masukan_pekerjaan` text DEFAULT NULL,
          `file_lampiran` text DEFAULT NULL,
          `link_lampiran` text DEFAULT NULL,
          `butuh_konseling` tinyint(1) DEFAULT 0,
          `tgl_usulan_konseling` date DEFAULT NULL,
          `jam_usulan_konseling` varchar(20) DEFAULT NULL,
          `metode_konseling` varchar(50) DEFAULT 'Tatap Muka',
          `topik_konseling` text DEFAULT NULL,
          `status_evaluasi` varchar(30) DEFAULT 'Terkirim',
          `status_konseling` varchar(30) DEFAULT 'Tidak Ada',
          `tgl_jadwal_konseling` date DEFAULT NULL,
          `jam_jadwal_konseling` varchar(20) DEFAULT NULL,
          `lokasi_konseling` varchar(255) DEFAULT NULL,
          `pic_konseling_id` int(11) DEFAULT NULL,
          `pic_konseling_nama` varchar(150) DEFAULT NULL,
          `tanggapan_manajemen` text DEFAULT NULL,
          `catatan_hasil_konseling` text DEFAULT NULL,
          `updated_by` int(11) DEFAULT NULL,
          `is_deleted` tinyint(1) DEFAULT 0,
          `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
          `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id_evaluasi_lisan`),
          KEY `idx_pengguna` (`id_pengguna`),
          KEY `idx_status_konseling` (`status_konseling`),
          KEY `idx_tanggal` (`tanggal_evaluasi`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        @$this->db->query($sql);
    }

    /**
     * Cek apakah user tertentu termasuk Manajemen (Bob Ariyos, Meilina, Yola) atau HR / GA / Admin
     */
    public function canManageAll($targetId = null, $userRow = null)
    {
        $sessionUserId = (int)sessPenggunaId();
        if ($targetId === null) {
            $targetId = $sessionUserId;
        } else {
            $targetId = (int)$targetId;
        }

        // Cek session helper HANYA jika yang dicek adalah user yang sedang login
        if ($targetId === $sessionUserId && (isAdmin() || isHrd() || isGa())) {
            return true;
        }

        // ID khusus Manajemen & HRD (1=Admin, 58=Meilina Safitri, 69=Bob Ariyos, 744=Yolanda/HRD)
        if (in_array($targetId, [1, 58, 69, 744], true)) {
            return true;
        }

        if (!$userRow && $targetId > 0) {
            $userRow = $this->db->select('pengguna_id, nama, jabatan, level')
                ->where('pengguna_id', $targetId)
                ->get('pengguna')
                ->row();
        }

        if ($userRow) {
            $nama    = strtolower(trim((string)($userRow->nama ?? '')));
            $jabatan = strtolower(trim((string)($userRow->jabatan ?? '')));
            $level   = strtolower(trim((string)($userRow->level ?? '')));

            // Manajemen: Bob Ariyos, Meilina Safitri, Yolanda Pratiwi
            if (
                strpos($nama, 'bob ariyos') !== false ||
                strpos($nama, 'meilina safitri') !== false ||
                strpos($nama, 'yolanda pratiwi') !== false
            ) {
                return true;
            }

            // HR & GA berdasarkan level akun atau jabatan spesifik (word-boundary)
            if (
                $level === 'hrd' ||
                $level === 'ga' ||
                (bool)preg_match('/\b(hr|hrd|hrga|human resource|general affair)\b/i', $jabatan)
            ) {
                return true;
            }
        }

        return false;
    }

    public function index()
    {
        grantAccessFor('all');

        $myId = (int)sessPenggunaId();
        $nav  = $this->input->get('nav', TRUE);
        $switch = ($nav === 'home') ? 'home' : 'kepegawaian';

        $me = $this->db->select('p.*, d.nama as nama_divisi')
            ->from('pengguna p')
            ->join('divisi d', 'd.id_divisi = p.id_divisi', 'left')
            ->where('p.pengguna_id', $myId)
            ->get()
            ->row();

        $canManage = $this->canManageAll($myId, $me);

        // Filter parameters
        $filterBulan     = $this->input->get('bulan', TRUE);
        $filterTahun     = $this->input->get('tahun', TRUE);
        $filterKonseling = $this->input->get('konseling', TRUE);
        $filterStatus    = $this->input->get('status', TRUE);

        if ($filterTahun === null) {
            $filterTahun = date('Y');
        }

        // Data milik user sendiri
        $myEvaluasi = $this->db->select('e.*, p.nama as nama_karyawan, p.jabatan, d.nama as nama_divisi')
            ->from('evaluasi_lisan e')
            ->join('pengguna p', 'p.pengguna_id = e.id_pengguna', 'left')
            ->join('divisi d', 'd.id_divisi = p.id_divisi', 'left')
            ->where('e.id_pengguna', $myId)
            ->where('e.is_deleted', 0)
            ->order_by('e.id_evaluasi_lisan', 'DESC')
            ->get()
            ->result();

        // Data seluruh karyawan (khusus Manajemen: Bob Ariyos, Meilina, Yola & HR/GA)
        $allEvaluasi = [];
        $stats = [
            'total'             => 0,
            'butuh_konseling'   => 0,
            'menunggu_jadwal'   => 0,
            'terjadwal'         => 0,
            'konseling_selesai' => 0,
        ];

        if ($canManage) {
            $this->db->select('e.*, p.nama as nama_karyawan, p.jabatan, p.no_hp, d.nama as nama_divisi')
                ->from('evaluasi_lisan e')
                ->join('pengguna p', 'p.pengguna_id = e.id_pengguna', 'left')
                ->join('divisi d', 'd.id_divisi = p.id_divisi', 'left')
                ->where('e.is_deleted', 0);

            if (!empty($filterTahun) && $filterTahun !== 'all') {
                $this->db->where('YEAR(e.tanggal_evaluasi)', (int)$filterTahun);
            }
            if (!empty($filterBulan) && $filterBulan !== 'all') {
                $this->db->where('MONTH(e.tanggal_evaluasi)', (int)$filterBulan);
            }
            if (!empty($filterKonseling) && $filterKonseling !== 'all') {
                if ($filterKonseling === 'ya') {
                    $this->db->where('e.butuh_konseling', 1);
                } elseif ($filterKonseling === 'tidak') {
                    $this->db->where('e.butuh_konseling', 0);
                } else {
                    $this->db->where('e.status_konseling', $filterKonseling);
                }
            }
            if (!empty($filterStatus) && $filterStatus !== 'all') {
                $this->db->where('e.status_evaluasi', $filterStatus);
            }

            $allEvaluasi = $this->db->order_by('e.id_evaluasi_lisan', 'DESC')->get()->result();

            foreach ($allEvaluasi as $row) {
                $stats['total']++;
                if ((int)$row->butuh_konseling === 1) {
                    $stats['butuh_konseling']++;
                }
                if ($row->status_konseling === 'Menunggu Jadwal') {
                    $stats['menunggu_jadwal']++;
                } elseif ($row->status_konseling === 'Terjadwal') {
                    $stats['terjadwal']++;
                } elseif ($row->status_konseling === 'Selesai') {
                    $stats['konseling_selesai']++;
                }
            }
        }

        $page_data['switch']          = $switch;
        $page_data['page_name']       = 'evaluasi_lisan/v_evaluasi_lisan';
        $page_data['page_title']      = 'Evaluasi Lisan & Konseling HRGA';
        $page_data['page_desc']       = 'Masukan Karyawan (Manajemen, Karyawan, Lingkungan Kerja, Pekerjaan) & Penjadwalan Konseling HRGA';
        $page_data['me']              = $me;
        $page_data['canManage']       = $canManage;
        $page_data['myEvaluasi']      = $myEvaluasi;
        $page_data['allEvaluasi']     = $allEvaluasi;
        $page_data['stats']           = $stats;
        $page_data['filterBulan']     = $filterBulan;
        $page_data['filterTahun']     = $filterTahun;
        $page_data['filterKonseling'] = $filterKonseling;
        $page_data['filterStatus']    = $filterStatus;

        $this->load->view('index', $page_data);
    }

    /**
     * Simpan Evaluasi Lisan & Pengajuan Konseling (Tambah / Edit oleh Karyawan)
     */
    public function simpan()
    {
        grantAccessFor('all');

        $myId = (int)sessPenggunaId();
        $id_edit = (int)$this->input->post('id_evaluasi_lisan');

        $existing = null;
        if ($id_edit > 0) {
            $existing = $this->db->get_where('evaluasi_lisan', [
                'id_evaluasi_lisan' => $id_edit,
                'is_deleted'        => 0
            ])->row();

            if (!$existing) {
                $this->session->set_flashdata('error', 'Data evaluasi tidak ditemukan.');
                redirect('evaluasi_lisan');
                return;
            }

            if ((int)$existing->id_pengguna !== $myId && !$this->canManageAll($myId)) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
                redirect('evaluasi_lisan');
                return;
            }
        }

        $tanggal_evaluasi   = $this->input->post('tanggal_evaluasi', TRUE) ?: date('Y-m-d');
        $masukan_manajemen  = trim((string)$this->input->post('masukan_manajemen', TRUE));
        $masukan_karyawan   = trim((string)$this->input->post('masukan_karyawan', TRUE));
        $masukan_lingkungan = trim((string)$this->input->post('masukan_lingkungan', TRUE));
        $masukan_pekerjaan  = trim((string)$this->input->post('masukan_pekerjaan', TRUE));
        $link_lampiran      = trim((string)$this->input->post('link_lampiran', TRUE));

        $butuh_konseling      = (int)$this->input->post('butuh_konseling') === 1 ? 1 : 0;
        $tgl_usulan_konseling = $butuh_konseling ? ($this->input->post('tgl_usulan_konseling', TRUE) ?: null) : null;
        $jam_usulan_konseling = $butuh_konseling ? trim((string)$this->input->post('jam_usulan_konseling', TRUE)) : null;
        $metode_konseling     = $butuh_konseling ? ($this->input->post('metode_konseling', TRUE) ?: 'Tatap Muka') : null;
        $topik_konseling      = $butuh_konseling ? trim((string)$this->input->post('topik_konseling', TRUE)) : null;

        if (
            $masukan_manajemen === '' &&
            $masukan_karyawan === '' &&
            $masukan_lingkungan === '' &&
            $masukan_pekerjaan === ''
        ) {
            $this->session->set_flashdata('error', 'Mohon isi setidaknya satu kolom masukan (Manajemen / Karyawan / Lingkungan Kerja / Pekerjaan).');
            redirect('evaluasi_lisan');
            return;
        }

        // Handle upload lampiran pendukung
        $file_lampiran = $existing ? ($existing->file_lampiran ?? '') : '';
        if (!empty($_FILES['file_lampiran']['name'])) {
            $upload_dir = FCPATH . 'uploads/evaluasi_lisan/';
            if (!is_dir($upload_dir)) {
                @mkdir($upload_dir, 0775, true);
            }
            @chmod($upload_dir, 0775);

            $config = [
                'upload_path'   => $upload_dir,
                'allowed_types' => 'pdf|jpg|jpeg|png|doc|docx|xls|xlsx',
                'max_size'      => 10240, // 10MB
                'encrypt_name'  => TRUE,
            ];

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('file_lampiran')) {
                $up = $this->upload->data();
                $file_lampiran = base_url('uploads/evaluasi_lisan/' . $up['file_name']);
            } else {
                $this->session->set_flashdata('error', 'Gagal mengunggah lampiran: ' . $this->upload->display_errors('', ''));
                redirect('evaluasi_lisan');
                return;
            }
        }

        $status_konseling = 'Tidak Ada';
        if ($butuh_konseling) {
            if ($existing && !empty($existing->status_konseling) && $existing->status_konseling !== 'Tidak Ada') {
                $status_konseling = $existing->status_konseling;
            } else {
                $status_konseling = 'Menunggu Jadwal';
            }
        }

        $payload = [
            'tanggal_evaluasi'     => $tanggal_evaluasi,
            'masukan_manajemen'    => $masukan_manajemen,
            'masukan_karyawan'     => $masukan_karyawan,
            'masukan_lingkungan'   => $masukan_lingkungan,
            'masukan_pekerjaan'    => $masukan_pekerjaan,
            'file_lampiran'        => $file_lampiran,
            'link_lampiran'        => $link_lampiran,
            'butuh_konseling'      => $butuh_konseling,
            'tgl_usulan_konseling' => $tgl_usulan_konseling,
            'jam_usulan_konseling' => $jam_usulan_konseling,
            'metode_konseling'     => $metode_konseling,
            'topik_konseling'      => $topik_konseling,
            'status_konseling'     => $status_konseling,
        ];

        if ($existing) {
            $payload['updated_by'] = $myId;
            $this->db->where('id_evaluasi_lisan', $id_edit)->update('evaluasi_lisan', $payload);
            $this->session->set_flashdata('success', 'Data Evaluasi Lisan berhasil diperbarui!');
        } else {
            $countMonth = $this->db->where('YEAR(created_at)', date('Y'))
                ->where('MONTH(created_at)', date('m'))
                ->count_all_results('evaluasi_lisan');
            $payload['kode_evaluasi']   = 'EVL-' . date('Ym') . '-' . str_pad($countMonth + 1, 4, '0', STR_PAD_LEFT);
            $payload['id_pengguna']     = $myId;
            $payload['status_evaluasi'] = 'Terkirim';

            $this->db->insert('evaluasi_lisan', $payload);

            // Kirim notifikasi WA ke Manajemen (Bob Ariyos, Meilina, Yola) & HR/GA setiap ada Evaluasi Lisan baru
            $this->_notifyHrGaNewSubmission($myId, $payload);

            $this->session->set_flashdata('success', 'Evaluasi Lisan & Masukan Anda berhasil dikirim!');
        }

        redirect('evaluasi_lisan');
    }

    /**
     * Tindak Lanjut & Penjadwalan Konseling oleh Manajemen (Bob Ariyos, Meilina, Yola) / HR / GA
     */
    public function tindak_lanjut()
    {
        grantAccessFor('all');

        $myId = (int)sessPenggunaId();
        if (!$this->canManageAll($myId)) {
            $this->session->set_flashdata('error', 'Hanya Manajemen dan HR/GA yang memiliki akses untuk menindaklanjuti evaluasi & konseling.');
            redirect('evaluasi_lisan');
            return;
        }

        $id_evaluasi = (int)$this->input->post('id_evaluasi_lisan');
        $row = $this->db->get_where('evaluasi_lisan', [
            'id_evaluasi_lisan' => $id_evaluasi,
            'is_deleted'        => 0
        ])->row();

        if (!$row) {
            $this->session->set_flashdata('error', 'Data evaluasi tidak ditemukan.');
            redirect('evaluasi_lisan');
            return;
        }

        $me = $this->db->select('nama')->where('pengguna_id', $myId)->get('pengguna')->row();
        $myName = $me ? $me->nama : sessNama();

        $status_evaluasi        = $this->input->post('status_evaluasi', TRUE) ?: $row->status_evaluasi;
        $butuh_konseling        = (int)$this->input->post('butuh_konseling') === 1 ? 1 : (int)$row->butuh_konseling;
        $status_konseling       = $this->input->post('status_konseling', TRUE) ?: $row->status_konseling;
        $tgl_jadwal_konseling   = $this->input->post('tgl_jadwal_konseling', TRUE) ?: null;
        $jam_jadwal_konseling   = trim((string)$this->input->post('jam_jadwal_konseling', TRUE)) ?: null;
        $metode_konseling       = $this->input->post('metode_konseling', TRUE) ?: $row->metode_konseling;
        $lokasi_konseling       = trim((string)$this->input->post('lokasi_konseling', TRUE)) ?: null;
        $tanggapan_manajemen    = trim((string)$this->input->post('tanggapan_manajemen', TRUE));
        $catatan_hasil_konseling= trim((string)$this->input->post('catatan_hasil_konseling', TRUE));

        if (!empty($tgl_jadwal_konseling) && $status_konseling === 'Menunggu Jadwal') {
            $status_konseling = 'Terjadwal';
            $butuh_konseling  = 1;
        }

        $updateData = [
            'status_evaluasi'         => $status_evaluasi,
            'butuh_konseling'         => $butuh_konseling,
            'status_konseling'        => $status_konseling,
            'tgl_jadwal_konseling'    => $tgl_jadwal_konseling,
            'jam_jadwal_konseling'    => $jam_jadwal_konseling,
            'metode_konseling'        => $metode_konseling,
            'lokasi_konseling'        => $lokasi_konseling,
            'pic_konseling_id'        => $myId,
            'pic_konseling_nama'      => $myName,
            'tanggapan_manajemen'     => $tanggapan_manajemen,
            'catatan_hasil_konseling' => $catatan_hasil_konseling,
            'updated_by'              => $myId,
        ];

        $this->db->where('id_evaluasi_lisan', $id_evaluasi)->update('evaluasi_lisan', $updateData);

        // Kirim notifikasi WA ke karyawan bila jadwal konseling atau tanggapan diperbarui
        $this->_notifyEmployeeFollowUp($row->id_pengguna, $row->kode_evaluasi, $updateData);

        $this->session->set_flashdata('success', 'Tindak lanjut & penjadwalan konseling berhasil disimpan!');
        redirect('evaluasi_lisan');
    }

    /**
     * Hapus Evaluasi Lisan (Soft Delete)
     */
    public function hapus($id = 0)
    {
        grantAccessFor('all');

        $myId = (int)sessPenggunaId();
        $id   = (int)$id;

        $row = $this->db->get_where('evaluasi_lisan', [
            'id_evaluasi_lisan' => $id,
            'is_deleted'        => 0
        ])->row();

        if (!$row) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('evaluasi_lisan');
            return;
        }

        $canManage = $this->canManageAll($myId);
        if ((int)$row->id_pengguna !== $myId && !$canManage) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses untuk menghapus data ini.');
            redirect('evaluasi_lisan');
            return;
        }

        $this->db->where('id_evaluasi_lisan', $id)->update('evaluasi_lisan', [
            'is_deleted' => 1,
            'updated_by' => $myId
        ]);

        $this->session->set_flashdata('success', 'Data evaluasi berhasil dihapus.');
        redirect('evaluasi_lisan');
    }

    private function _notifyHrGaNewSubmission($id_pengguna, $payload)
    {
        if (!function_exists('sendWa') || !function_exists('hostWa')) {
            return;
        }

        $emp = $this->db->select('nama, jabatan')->where('pengguna_id', (int)$id_pengguna)->get('pengguna')->row();
        $namaKaryawan = $emp ? $emp->nama : 'Karyawan';
        $jabatan      = ($emp && !empty($emp->jabatan)) ? ' (' . $emp->jabatan . ')' : '';

        // Cari nomor HP Manajemen (Bob Ariyos, Meilina Safitri, Yolanda Pratiwi) & HR/GA
        $users = $this->db->select('pengguna_id, nama, jabatan, level, no_hp')
            ->where('is_active', 1)
            ->where('status', 1)
            ->where('pengguna_id !=', 1)
            ->get('pengguna')
            ->result();

        $phones = [];
        foreach ($users as $u) {
            $namaLower = strtolower(trim((string)($u->nama ?? '')));
            if (strpos($namaLower, 'dirangga') !== false) {
                continue;
            }
            $jabatanLower = strtolower(trim((string)($u->jabatan ?? '')));
            $isTargetWa = (
                in_array((int)$u->pengguna_id, [58, 69, 744], true) ||
                strpos($namaLower, 'bob ariyos') !== false ||
                strpos($namaLower, 'meilina safitri') !== false ||
                strpos($namaLower, 'yolanda pratiwi') !== false ||
                (bool)preg_match('/\b(hr|hrd|hrga|human resource|general affair)\b/i', $jabatanLower)
            );
            if ($isTargetWa) {
                $phone = preg_replace('/[^0-9]/', '', (string)($u->no_hp ?? ''));
                if (!empty($phone)) {
                    if (substr($phone, 0, 1) === '0') {
                        $phone = '62' . substr($phone, 1);
                    }
                    $phones[$phone] = true;
                }
            }
        }

        $butuhKonseling = (int)($payload['butuh_konseling'] ?? 0) === 1;
        $tglUsulan = !empty($payload['tgl_usulan_konseling']) ? date('d/m/Y', strtotime($payload['tgl_usulan_konseling'])) : '-';
        $jamUsulan = !empty($payload['jam_usulan_konseling']) ? $payload['jam_usulan_konseling'] : '-';

        $pesan = "*[EVALUASI LISAN & KONSELING HRGA BARU]*\n\n"
            . "Karyawan: *" . $namaKaryawan . "*" . $jabatan . "\n"
            . "Kode: *" . $payload['kode_evaluasi'] . "*\n"
            . "Tanggal: " . date('d/m/Y', strtotime($payload['tanggal_evaluasi'])) . "\n"
            . "Pengajuan Konseling HRGA: *" . ($butuhKonseling ? "YA (Butuh Konseling)" : "Tidak") . "*\n";

        if ($butuhKonseling) {
            $pesan .= "Usulan Jadwal: *" . $tglUsulan . " (" . $jamUsulan . ")*\n"
                . "Metode: " . ($payload['metode_konseling'] ?: 'Tatap Muka') . "\n"
                . (!empty($payload['topik_konseling']) ? "Topik: " . $payload['topik_konseling'] . "\n" : "");
        }

        $pesan .= "\nSilakan cek detail masukan di menu *Evaluasi Lisan & Konseling HRGA*:\n"
            . base_url('evaluasi_lisan');

        foreach (array_keys($phones) as $phone) {
            @sendWa([
                'devId'    => hostWa('1'),
                'penerima' => $phone,
                'pesan'    => $pesan
            ]);
        }
    }

    private function _notifyEmployeeFollowUp($id_pengguna, $kode_evaluasi, $updateData)
    {
        if (!function_exists('sendWa') || !function_exists('hostWa')) {
            return;
        }

        $emp = $this->db->select('nama, no_hp')->where('pengguna_id', (int)$id_pengguna)->get('pengguna')->row();
        if (!$emp || empty($emp->no_hp)) {
            return;
        }

        $phone = preg_replace('/[^0-9]/', '', (string)$emp->no_hp);
        if (empty($phone)) return;
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        $tglJadwal = !empty($updateData['tgl_jadwal_konseling']) ? date('d/m/Y', strtotime($updateData['tgl_jadwal_konseling'])) : '-';
        $jamJadwal = !empty($updateData['jam_jadwal_konseling']) ? $updateData['jam_jadwal_konseling'] : '-';

        $pesan = "*[UPDATE EVALUASI LISAN & KONSELING HRGA]*\n\n"
            . "Halo *" . $emp->nama . "*,\n"
            . "Pengajuan Evaluasi Lisan Anda (*" . $kode_evaluasi . "*) telah ditindaklanjuti oleh *" . $updateData['pic_konseling_nama'] . "*.\n\n"
            . "• Status Evaluasi: *" . $updateData['status_evaluasi'] . "*\n";

        if ((int)$updateData['butuh_konseling'] === 1 || $updateData['status_konseling'] !== 'Tidak Ada') {
            $pesan .= "• Status Konseling: *" . $updateData['status_konseling'] . "*\n"
                . "• Jadwal Konseling: *" . $tglJadwal . " Pukul " . $jamJadwal . "*\n"
                . "• Metode / Lokasi: *" . ($updateData['metode_konseling'] ?: 'Tatap Muka') . (!empty($updateData['lokasi_konseling']) ? ' - ' . $updateData['lokasi_konseling'] : '') . "*\n";
        }

        if (!empty($updateData['tanggapan_manajemen'])) {
            $pesan .= "\n*Tanggapan Manajemen/HRGA:*\n" . $updateData['tanggapan_manajemen'] . "\n";
        }

        $pesan .= "\nCek detailnya di VisiOffice:\n" . base_url('evaluasi_lisan');

        @sendWa([
            'devId'    => hostWa('1'),
            'penerima' => $phone,
            'pesan'    => $pesan
        ]);
    }
}
