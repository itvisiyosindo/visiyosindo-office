<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Regulatory_knowledge extends CI_Controller
{
    private $allowedDocTypes       = 'pdf|doc|docx|xls|xlsx|ppt|pptx|jpg|jpeg|png|webp|zip';
    private $allowedFlowchartTypes = 'jpg|jpeg|png|webp|gif|pdf';
    private $maxFileSizeKb         = 10240; // 10 MB

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Md_regulatory_knowledge', 'md_rkc');
    }

    private function id_navbar()
    {
        return 'dokumen';
    }

    /**
     * Cek apakah user yang login memiliki hak kelola (tambah/edit/hapus)
     * Knowledge Center Regulatory Advice.
     */
    private function _canManage()
    {
        $myId = (int) sessPenggunaId();
        if ($myId <= 0) {
            return false;
        }

        if (
            isAdmin() ||
            isHrd() ||
            isGa() ||
            isLegalOfficer() ||
            isEksekutif() ||
            isKepalaDivisi() ||
            $myId === 1
        ) {
            return true;
        }

        $user = $this->db->select('p.jabatan, d.nama AS nama_divisi')
            ->from('pengguna p')
            ->join('divisi d', 'd.id_divisi = p.id_divisi', 'left')
            ->where('p.pengguna_id', $myId)
            ->get()
            ->row();

        if ($user) {
            $combined = trim((string) ($user->jabatan ?? '') . ' ' . (string) ($user->nama_divisi ?? ''));
            if (preg_match('/\b(regulatory|regulasi|perizinan|legal|qa|ra|compliance|mutu|pjt)\b/i', $combined)) {
                return true;
            }
        }

        return false;
    }

    private function _requireManage()
    {
        if (!$this->_canManage()) {
            ajaxReturnDie('error', 'Akses ditolak: Hanya Divisi Regulatory, Legal, atau Administrator yang dapat mengelola konten Knowledge Center.');
        }
    }

    public function index($instansi = 'oss', $tab = '')
    {
        $this->instansi($instansi, $tab);
    }

    public function instansi($instansi = 'oss', $tab = '')
    {
        grantAccessFor('all');

        $instansi = strtolower(trim((string) $instansi));
        if (!$this->md_rkc->isValidInstansi($instansi)) {
            $instansi = 'oss';
        }

        $tabQuery = strtolower(trim((string) ($this->input->get('tab', true) ?: $tab)));
        $validTabs = ['regulatory_update', 'konsultasi', 'guidance'];
        $activeTab = in_array($tabQuery, $validTabs, true) ? $tabQuery : 'regulatory_update';

        $catalog   = $this->md_rkc->getInstansiCatalog();
        $canManage = $this->_canManage();

        $page_data['switch']          = $this->id_navbar();
        $page_data['page_name']       = 'dokumen/v_regulatory_knowledge';
        $page_data['page_title']      = 'Knowledge Center — ' . $catalog[$instansi]['nama'];
        $page_data['page_desc']       = 'Regulatory Advice & Pusat Pengetahuan Instansi Pemerintah (' . $catalog[$instansi]['nama_lengkap'] . ')';
        $page_data['instansi_aktif']  = $instansi;
        $page_data['instansi_info']   = $catalog[$instansi];
        $page_data['instansi_list']   = $catalog;
        $page_data['active_tab']      = $activeTab;
        $page_data['can_manage']      = $canManage;
        $page_data['counts']          = $this->md_rkc->getCountsByInstansi($canManage);
        $page_data['list_updates']    = $this->md_rkc->getUpdates($instansi);
        $page_data['list_konsultasi'] = $this->md_rkc->getKonsultasi($instansi, $canManage);
        $page_data['list_guidance']   = $this->md_rkc->getGuidance($instansi);

        $this->load->view('index', $page_data);
    }

    /* =========================================================================
       AJAX: REGULATORY UPDATE
       ========================================================================= */

    public function ajax_get_update($id = 0)
    {
        grantAccessFor('all');
        $row = $this->md_rkc->getUpdateById((int) $id);
        if (!$row) {
            ajaxReturnDie('error', 'Data Regulatory Update tidak ditemukan.');
        }
        ajaxReturnDie('success', 'OK', $row);
    }

    public function ajax_save_update()
    {
        grantAccessFor('all');
        $this->_requireManage();

        $id               = (int) $this->input->post('id', true);
        $instansi         = strtolower(trim((string) $this->input->post('instansi', true)));
        $nomor_peraturan  = trim((string) $this->input->post('nomor_peraturan', true));
        $judul            = trim((string) $this->input->post('judul', true));
        $tanggal_berlaku  = trim((string) $this->input->post('tanggal_berlaku', true));
        $status_peraturan = strtolower(trim((string) $this->input->post('status_peraturan', true)));
        $ringkasan        = trim((string) $this->input->post('ringkasan', false));
        $link_eksternal   = trim((string) $this->input->post('link_eksternal', true));
        $hapus_file       = $this->input->post('hapus_file', true) === '1';

        if (!$this->md_rkc->isValidInstansi($instansi)) {
            ajaxReturnDie('error', 'Instansi pemerintah tidak valid.');
        }
        if ($judul === '') {
            ajaxReturnDie('error', 'Judul peraturan/panduan wajib diisi.');
        }

        $validStatus = ['berlaku', 'akan_berlaku', 'revisi', 'dicabut'];
        if (!in_array($status_peraturan, $validStatus, true)) {
            $status_peraturan = 'berlaku';
        }

        $tglSql = $this->_normalizeDate($tanggal_berlaku);
        if ($tanggal_berlaku !== '' && $tglSql === null) {
            ajaxReturnDie('error', 'Format tanggal berlaku tidak valid.');
        }

        $urlCheck = $this->_validateExternalUrl($link_eksternal);
        if (!$urlCheck['ok']) {
            ajaxReturnDie('error', $urlCheck['error']);
        }
        $link_eksternal = $urlCheck['value'];

        $existing = null;
        if ($id > 0) {
            $existing = $this->md_rkc->getUpdateById($id);
            if (!$existing) {
                ajaxReturnDie('error', 'Data Regulatory Update yang ingin diubah tidak ditemukan.');
            }
        }

        $filePath     = $existing ? ($existing['file_path'] ?? null) : null;
        $fileNamaAsli = $existing ? ($existing['file_nama_asli'] ?? null) : null;
        $oldFileToDel = null;

        if (!empty($_FILES['file_dokumen']['name'])) {
            $upload = $this->_uploadFile(
                'file_dokumen',
                'uploads/regulatory_knowledge/updates/',
                $this->allowedDocTypes,
                $this->maxFileSizeKb,
                $instansi . '_reg_' . $judul
            );
            if (!$upload['ok']) {
                ajaxReturnDie('error', 'Gagal upload dokumen: ' . $upload['error']);
            }
            if (!empty($filePath)) {
                $oldFileToDel = $filePath;
            }
            $filePath     = $upload['path'];
            $fileNamaAsli = $upload['orig_name'];
        } elseif ($hapus_file) {
            if (!empty($filePath)) {
                $oldFileToDel = $filePath;
            }
            $filePath     = null;
            $fileNamaAsli = null;
        }

        $myId = (int) sessPenggunaId();
        $now  = date('Y-m-d H:i:s');

        $payload = [
            'instansi'         => $instansi,
            'nomor_peraturan'  => $nomor_peraturan !== '' ? $nomor_peraturan : null,
            'judul'            => $judul,
            'tanggal_berlaku'  => $tglSql,
            'status_peraturan' => $status_peraturan,
            'ringkasan'        => $ringkasan !== '' ? $ringkasan : null,
            'file_path'        => $filePath,
            'file_nama_asli'   => $fileNamaAsli,
            'link_eksternal'   => $link_eksternal,
            'updated_by'       => $myId,
            'updated_at'       => $now,
        ];

        if ($id > 0) {
            $this->md_rkc->updateRegulatory($id, $payload);
            if ($oldFileToDel) {
                $this->_deleteLocalFile($oldFileToDel, 'uploads/regulatory_knowledge/updates/');
            }
            addlog('Knowledge Center Regulatory', 'Update Regulatory Update (' . strtoupper($instansi) . '): ' . $judul);
            ajaxReturnDie('success', 'Regulatory Update berhasil diperbarui.', ['id' => $id]);
        } else {
            $payload['created_by'] = $myId;
            $payload['created_at'] = $now;
            $newId = $this->md_rkc->insertUpdate($payload);
            addlog('Knowledge Center Regulatory', 'Tambah Regulatory Update (' . strtoupper($instansi) . '): ' . $judul);
            ajaxReturnDie('success', 'Regulatory Update berhasil ditambahkan.', ['id' => $newId]);
        }
    }

    public function ajax_delete_update()
    {
        grantAccessFor('all');
        $this->_requireManage();

        $id  = (int) $this->input->post('id', true);
        $row = $this->md_rkc->getUpdateById($id);
        if (!$row) {
            ajaxReturnDie('error', 'Data Regulatory Update tidak ditemukan.');
        }

        $this->md_rkc->deleteUpdate($id, (int) sessPenggunaId());
        addlog('Knowledge Center Regulatory', 'Hapus Regulatory Update (' . strtoupper($row['instansi']) . '): ' . $row['judul']);
        ajaxReturnDie('success', 'Regulatory Update berhasil dihapus.', true);
    }

    /* =========================================================================
       AJAX: KONSULTASI (FLOWCHART)
       ========================================================================= */

    public function ajax_get_konsultasi($id = 0)
    {
        grantAccessFor('all');
        $row = $this->md_rkc->getKonsultasiById((int) $id);
        if (!$row) {
            ajaxReturnDie('error', 'Data alur konsultasi tidak ditemukan.');
        }
        ajaxReturnDie('success', 'OK', $row);
    }

    public function ajax_save_konsultasi()
    {
        grantAccessFor('all');
        $this->_requireManage();

        $id              = (int) $this->input->post('id', true);
        $instansi        = strtolower(trim((string) $this->input->post('instansi', true)));
        $judul           = trim((string) $this->input->post('judul', true));
        $jenis_layanan   = strtolower(trim((string) $this->input->post('jenis_layanan', true)));
        $estimasi_waktu  = trim((string) $this->input->post('estimasi_waktu', true));
        $ringkasan       = trim((string) $this->input->post('ringkasan', false));
        $catatan_penting = trim((string) $this->input->post('catatan_penting', false));
        $link_eksternal  = trim((string) $this->input->post('link_eksternal', true));
        $urutan          = (int) $this->input->post('urutan', true);
        $status          = strtolower(trim((string) $this->input->post('status', true)));
        $langkah_raw     = $this->input->post('langkah_json', false);
        $hapus_file      = $this->input->post('hapus_file', true) === '1';

        if (!$this->md_rkc->isValidInstansi($instansi)) {
            ajaxReturnDie('error', 'Instansi pemerintah tidak valid.');
        }
        if ($judul === '') {
            ajaxReturnDie('error', 'Judul alur konsultasi wajib diisi.');
        }
        if (!in_array($jenis_layanan, ['offline', 'online', 'hybrid'], true)) {
            $jenis_layanan = 'offline';
        }
        if (!in_array($status, ['aktif', 'draft'], true)) {
            $status = 'aktif';
        }

        $urlCheck = $this->_validateExternalUrl($link_eksternal);
        if (!$urlCheck['ok']) {
            ajaxReturnDie('error', $urlCheck['error']);
        }
        $link_eksternal = $urlCheck['value'];

        // Sanitasi array langkah flowchart
        $decodedSteps = json_decode((string) $langkah_raw, true);
        $cleanSteps   = [];
        $allowedTipe  = ['start', 'process', 'wait', 'decision', 'finish'];
        if (is_array($decodedSteps)) {
            foreach ($decodedSteps as $st) {
                if (!is_array($st)) {
                    continue;
                }
                $stJudul = trim((string) ($st['judul'] ?? ''));
                $stDesk  = trim((string) ($st['deskripsi'] ?? ''));
                $stEst   = trim((string) ($st['estimasi'] ?? ''));
                $stTipe  = strtolower(trim((string) ($st['tipe'] ?? 'process')));
                if ($stJudul === '' && $stDesk === '') {
                    continue;
                }
                if ($stJudul === '') {
                    $stJudul = 'Langkah ' . (count($cleanSteps) + 1);
                }
                if (!in_array($stTipe, $allowedTipe, true)) {
                    $stTipe = 'process';
                }
                $cleanSteps[] = [
                    'judul'     => $stJudul,
                    'deskripsi' => $stDesk,
                    'estimasi'  => $stEst,
                    'tipe'      => $stTipe,
                ];
            }
        }

        $existing = null;
        if ($id > 0) {
            $existing = $this->md_rkc->getKonsultasiById($id);
            if (!$existing) {
                ajaxReturnDie('error', 'Data alur konsultasi yang ingin diubah tidak ditemukan.');
            }
        }

        $filePath     = $existing ? ($existing['flowchart_file'] ?? null) : null;
        $fileNamaAsli = $existing ? ($existing['flowchart_nama_asli'] ?? null) : null;
        $oldFileToDel = null;

        if (!empty($_FILES['flowchart_file']['name'])) {
            $upload = $this->_uploadFile(
                'flowchart_file',
                'uploads/regulatory_knowledge/flowchart/',
                $this->allowedFlowchartTypes,
                $this->maxFileSizeKb,
                $instansi . '_flow_' . $judul
            );
            if (!$upload['ok']) {
                ajaxReturnDie('error', 'Gagal upload diagram flowchart: ' . $upload['error']);
            }
            if (!empty($filePath)) {
                $oldFileToDel = $filePath;
            }
            $filePath     = $upload['path'];
            $fileNamaAsli = $upload['orig_name'];
        } elseif ($hapus_file) {
            if (!empty($filePath)) {
                $oldFileToDel = $filePath;
            }
            $filePath     = null;
            $fileNamaAsli = null;
        }

        if (empty($cleanSteps) && empty($filePath) && empty($link_eksternal)) {
            ajaxReturnDie('error', 'Harap isi minimal 1 langkah flowchart atau lampirkan file/link diagram flowchart.');
        }

        $myId = (int) sessPenggunaId();
        $now  = date('Y-m-d H:i:s');

        $payload = [
            'instansi'            => $instansi,
            'judul'               => $judul,
            'jenis_layanan'       => $jenis_layanan,
            'estimasi_waktu'      => $estimasi_waktu !== '' ? $estimasi_waktu : null,
            'ringkasan'           => $ringkasan !== '' ? $ringkasan : null,
            'catatan_penting'     => $catatan_penting !== '' ? $catatan_penting : null,
            'langkah_json'        => json_encode($cleanSteps, JSON_UNESCAPED_UNICODE),
            'flowchart_file'      => $filePath,
            'flowchart_nama_asli' => $fileNamaAsli,
            'link_eksternal'      => $link_eksternal,
            'urutan'              => $urutan,
            'status'              => $status,
            'updated_by'          => $myId,
            'updated_at'          => $now,
        ];

        if ($id > 0) {
            $this->md_rkc->updateKonsultasi($id, $payload);
            if ($oldFileToDel) {
                $this->_deleteLocalFile($oldFileToDel, 'uploads/regulatory_knowledge/flowchart/');
            }
            addlog('Knowledge Center Regulatory', 'Update Alur Konsultasi (' . strtoupper($instansi) . '): ' . $judul);
            ajaxReturnDie('success', 'Alur konsultasi & flowchart berhasil diperbarui.', ['id' => $id]);
        } else {
            $payload['created_by'] = $myId;
            $payload['created_at'] = $now;
            $newId = $this->md_rkc->insertKonsultasi($payload);
            addlog('Knowledge Center Regulatory', 'Tambah Alur Konsultasi (' . strtoupper($instansi) . '): ' . $judul);
            ajaxReturnDie('success', 'Alur konsultasi & flowchart berhasil ditambahkan.', ['id' => $newId]);
        }
    }

    public function ajax_delete_konsultasi()
    {
        grantAccessFor('all');
        $this->_requireManage();

        $id  = (int) $this->input->post('id', true);
        $row = $this->md_rkc->getKonsultasiById($id);
        if (!$row) {
            ajaxReturnDie('error', 'Data alur konsultasi tidak ditemukan.');
        }

        $this->md_rkc->deleteKonsultasi($id, (int) sessPenggunaId());
        addlog('Knowledge Center Regulatory', 'Hapus Alur Konsultasi (' . strtoupper($row['instansi']) . '): ' . $row['judul']);
        ajaxReturnDie('success', 'Alur konsultasi berhasil dihapus.', true);
    }

    /* =========================================================================
       AJAX: GUIDANCE DOCUMENT
       ========================================================================= */

    public function ajax_get_guidance($id = 0)
    {
        grantAccessFor('all');
        $row = $this->md_rkc->getGuidanceById((int) $id);
        if (!$row) {
            ajaxReturnDie('error', 'Guidance Document tidak ditemukan.');
        }
        ajaxReturnDie('success', 'OK', $row);
    }

    public function ajax_save_guidance()
    {
        grantAccessFor('all');
        $this->_requireManage();

        $id              = (int) $this->input->post('id', true);
        $instansi        = strtolower(trim((string) $this->input->post('instansi', true)));
        $judul           = trim((string) $this->input->post('judul', true));
        $kategori        = trim((string) $this->input->post('kategori', true));
        $versi           = trim((string) $this->input->post('versi', true));
        $tanggal_dokumen = trim((string) $this->input->post('tanggal_dokumen', true));
        $deskripsi       = trim((string) $this->input->post('deskripsi', false));
        $link_eksternal  = trim((string) $this->input->post('link_eksternal', true));
        $hapus_file      = $this->input->post('hapus_file', true) === '1';

        if (!$this->md_rkc->isValidInstansi($instansi)) {
            ajaxReturnDie('error', 'Instansi pemerintah tidak valid.');
        }
        if ($judul === '') {
            ajaxReturnDie('error', 'Judul Guidance Document wajib diisi.');
        }
        if ($kategori === '') {
            $kategori = 'Panduan Umum';
        }
        if ($versi === '') {
            $versi = 'v1.0';
        }

        $tglSql = $this->_normalizeDate($tanggal_dokumen);
        if ($tanggal_dokumen !== '' && $tglSql === null) {
            ajaxReturnDie('error', 'Format tanggal update dokumen tidak valid.');
        }

        $urlCheck = $this->_validateExternalUrl($link_eksternal);
        if (!$urlCheck['ok']) {
            ajaxReturnDie('error', $urlCheck['error']);
        }
        $link_eksternal = $urlCheck['value'];

        $existing = null;
        if ($id > 0) {
            $existing = $this->md_rkc->getGuidanceById($id);
            if (!$existing) {
                ajaxReturnDie('error', 'Guidance Document yang ingin diubah tidak ditemukan.');
            }
        }

        $filePath     = $existing ? ($existing['file_path'] ?? null) : null;
        $fileNamaAsli = $existing ? ($existing['file_nama_asli'] ?? null) : null;
        $oldFileToDel = null;

        if (!empty($_FILES['file_dokumen']['name'])) {
            $upload = $this->_uploadFile(
                'file_dokumen',
                'uploads/regulatory_knowledge/guidance/',
                $this->allowedDocTypes,
                $this->maxFileSizeKb,
                $instansi . '_guide_' . $judul
            );
            if (!$upload['ok']) {
                ajaxReturnDie('error', 'Gagal upload dokumen panduan: ' . $upload['error']);
            }
            if (!empty($filePath)) {
                $oldFileToDel = $filePath;
            }
            $filePath     = $upload['path'];
            $fileNamaAsli = $upload['orig_name'];
        } elseif ($hapus_file) {
            if (!empty($filePath)) {
                $oldFileToDel = $filePath;
            }
            $filePath     = null;
            $fileNamaAsli = null;
        }

        $myId = (int) sessPenggunaId();
        $now  = date('Y-m-d H:i:s');

        $payload = [
            'instansi'        => $instansi,
            'judul'           => $judul,
            'kategori'        => $kategori,
            'versi'           => $versi,
            'tanggal_dokumen' => $tglSql ?: date('Y-m-d'),
            'deskripsi'       => $deskripsi !== '' ? $deskripsi : null,
            'file_path'       => $filePath,
            'file_nama_asli'  => $fileNamaAsli,
            'link_eksternal'  => $link_eksternal,
            'updated_by'      => $myId,
            'updated_at'      => $now,
        ];

        if ($id > 0) {
            $this->md_rkc->updateGuidance($id, $payload);
            if ($oldFileToDel) {
                $this->_deleteLocalFile($oldFileToDel, 'uploads/regulatory_knowledge/guidance/');
            }
            addlog('Knowledge Center Regulatory', 'Update Guidance Document (' . strtoupper($instansi) . '): ' . $judul);
            ajaxReturnDie('success', 'Guidance Document berhasil diperbarui.', ['id' => $id]);
        } else {
            $payload['created_by'] = $myId;
            $payload['created_at'] = $now;
            $newId = $this->md_rkc->insertGuidance($payload);
            addlog('Knowledge Center Regulatory', 'Tambah Guidance Document (' . strtoupper($instansi) . '): ' . $judul);
            ajaxReturnDie('success', 'Guidance Document berhasil ditambahkan.', ['id' => $newId]);
        }
    }

    public function ajax_delete_guidance()
    {
        grantAccessFor('all');
        $this->_requireManage();

        $id  = (int) $this->input->post('id', true);
        $row = $this->md_rkc->getGuidanceById($id);
        if (!$row) {
            ajaxReturnDie('error', 'Guidance Document tidak ditemukan.');
        }

        $this->md_rkc->deleteGuidance($id, (int) sessPenggunaId());
        addlog('Knowledge Center Regulatory', 'Hapus Guidance Document (' . strtoupper($row['instansi']) . '): ' . $row['judul']);
        ajaxReturnDie('success', 'Guidance Document berhasil dihapus.', true);
    }

    /* =========================================================================
       INTERNAL HELPERS
       ========================================================================= */

    private function _normalizeDate($raw)
    {
        $raw = trim((string) $raw);
        if ($raw === '') {
            return null;
        }
        $ts = strtotime($raw);
        if ($ts === false) {
            return null;
        }
        return date('Y-m-d', $ts);
    }

    private function _validateExternalUrl($url)
    {
        $url = trim((string) $url);
        if ($url === '') {
            return ['ok' => true, 'value' => null];
        }
        if (!preg_match('#^https?://#i', $url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return ['ok' => false, 'error' => 'Link eksternal harus berupa URL http:// atau https:// yang valid.'];
        }
        if (strlen($url) > 500) {
            return ['ok' => false, 'error' => 'Link eksternal maksimal 500 karakter.'];
        }
        return ['ok' => true, 'value' => $url];
    }

    private function _uploadFile($fieldName, $subDir, $allowedTypes, $maxSizeKb, $prefixSlug)
    {
        $errorCode = $_FILES[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
            return ['ok' => false, 'error' => 'Ukuran file melebihi batas maksimal (10 MB).'];
        }
        if ($errorCode !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'error' => 'Upload file gagal (kode error: ' . (int) $errorCode . ').'];
        }

        $subDir    = rtrim(str_replace('\\', '/', $subDir), '/') . '/';
        $uploadDir = FCPATH . $subDir;
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                return ['ok' => false, 'error' => 'Gagal membuat direktori upload.'];
            }
        }
        if (!is_file($uploadDir . 'index.html')) {
            @file_put_contents($uploadDir . 'index.html', '');
        }

        $slug = trim(preg_replace('/[^a-z0-9]+/', '_', strtolower((string) $prefixSlug)), '_');
        if ($slug === '') {
            $slug = 'dokumen';
        }
        $slug = rtrim(substr($slug, 0, 45), '_');

        $config = [
            'upload_path'   => $uploadDir,
            'allowed_types' => $allowedTypes,
            'max_size'      => (int) $maxSizeKb,
            'file_name'     => $slug . '_' . time(),
            'remove_spaces' => true,
        ];

        $this->load->library('upload');
        $this->upload->initialize($config);

        if ($this->upload->do_upload($fieldName)) {
            $uploadData = $this->upload->data();
            return [
                'ok'        => true,
                'path'      => $subDir . $uploadData['file_name'],
                'orig_name' => $uploadData['client_name'] ?: $uploadData['file_name'],
            ];
        }

        return [
            'ok'    => false,
            'error' => trim(strip_tags($this->upload->display_errors('', ''))),
        ];
    }

    private function _deleteLocalFile($relativePath, $allowedPrefix)
    {
        $relativePath = trim((string) $relativePath);
        if ($relativePath === '' || strpos($relativePath, '..') !== false) {
            return false;
        }
        if (strpos($relativePath, $allowedPrefix) !== 0) {
            return false;
        }

        $baseDir  = realpath(FCPATH . rtrim($allowedPrefix, '/'));
        $fullPath = realpath(FCPATH . $relativePath);
        if ($baseDir === false || $fullPath === false || !is_file($fullPath)) {
            return false;
        }

        $baseDirNorm  = rtrim(str_replace('\\', '/', $baseDir), '/') . '/';
        $fullPathNorm = str_replace('\\', '/', $fullPath);
        if (strpos($fullPathNorm, $baseDirNorm) !== 0) {
            return false;
        }

        return @unlink($fullPath);
    }
}
