<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member_visiacademy extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Md_member_visiacademy');
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']             = 'home';
        $page_data['page_name']          = 'member_visiacademy/v_member_visiacademy';
        $page_data['page_title']         = 'Member Visi Academy';
        $page_data['page_desc']          = 'Program Referral Marketing & Komisi Alkes PT Visi Yosindo Medikal';
        $page_data['members']            = $this->Md_member_visiacademy->getAllMember();
        $page_data['deals']              = $this->Md_member_visiacademy->getAllDeals();
        $page_data['next_kode']          = $this->Md_member_visiacademy->generateNextKode();
        $page_data['summary']            = $this->Md_member_visiacademy->getSummary();
        $page_data['katalog']            = $this->Md_member_visiacademy->getAllKatalog();
        $page_data['katalog_categories'] = $this->Md_member_visiacademy->getKatalogKategoriList();
        $page_data['active_tab']         = $this->input->get('tab', true) ?: '';

        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $nama_member        = trim($this->input->post('nama_member', true));
        $kode_member        = trim($this->input->post('kode_member', true));
        $no_telp            = trim($this->input->post('no_telp', true));
        $email              = trim($this->input->post('email', true));
        $institusi_asal     = trim($this->input->post('institusi_asal', true));
        $nama_bank          = trim($this->input->post('nama_bank', true));
        $no_rekening        = trim($this->input->post('no_rekening', true));
        $atas_nama_rekening = trim($this->input->post('atas_nama_rekening', true));

        if (empty($nama_member)) {
            ajaxReturnDie('error', 'Nama member wajib diisi!');
        }

        if (empty($kode_member)) {
            $kode_member = $this->Md_member_visiacademy->generateNextKode();
        } else {
            // Cek apakah kode sudah digunakan
            $existing = $this->Md_member_visiacademy->getMemberByKode($kode_member);
            if ($existing) {
                ajaxReturnDie('error', 'Kode member "' . htmlspecialchars($kode_member) . '" sudah digunakan. Silakan gunakan kode lain.');
            }
        }

        $data = [
            'kode_member'        => $kode_member,
            'nama_member'        => $nama_member,
            'no_telp'            => $no_telp,
            'email'              => $email,
            'institusi_asal'     => $institusi_asal,
            'nama_bank'          => $nama_bank,
            'no_rekening'        => $no_rekening,
            'atas_nama_rekening' => $atas_nama_rekening,
            'status'             => 'aktif'
        ];

        $inserted = $this->Md_member_visiacademy->addMember($data);
        if ($inserted) {
            ajaxReturnDie('success', 'Member berhasil ditambahkan dengan kode ' . $kode_member, true);
        } else {
            ajaxReturnDie('error', 'Gagal menyimpan data member ke database.');
        }
    }

    public function addDeal()
    {
        grantAccessFor('all');

        $id_member      = (int) $this->input->post('id_member', true);
        $nama_produk    = trim($this->input->post('nama_produk', true));
        $raw_nilai      = $this->input->post('estimasi_nilai', true);
        $raw_persen     = $this->input->post('persen_komisi', true);
        $status_deal    = $this->input->post('status_deal', true) ?: 'Pending';
        $catatan        = trim($this->input->post('catatan', true));

        if (empty($id_member)) {
            ajaxReturnDie('error', 'Silakan pilih member yang mereferensikan!');
        }

        if (empty($nama_produk)) {
            ajaxReturnDie('error', 'Nama produk kebutuhan alkes wajib diisi!');
        }

        $estimasi_nilai = $this->_parseRupiah($raw_nilai);

        if ($raw_persen === null || trim((string)$raw_persen) === '') {
            $persen_komisi = 2.0;
        } else {
            $clean_persen = str_replace(',', '.', trim((string)$raw_persen));
            $persen_komisi = (float) $clean_persen;
        }

        if ($estimasi_nilai <= 0) {
            ajaxReturnDie('error', 'Estimasi nilai transaksi harus lebih dari 0!');
        }

        if ($persen_komisi < 0 || $persen_komisi > 100) {
            ajaxReturnDie('error', 'Persentase komisi harus antara 0% sampai 100%!');
        }

        $nominal_komisi = round(($estimasi_nilai * $persen_komisi) / 100, 2);

        $data = [
            'id_member'      => $id_member,
            'nama_produk'    => $nama_produk,
            'estimasi_nilai' => $estimasi_nilai,
            'persen_komisi'  => $persen_komisi,
            'nominal_komisi' => $nominal_komisi,
            'status_deal'    => in_array($status_deal, ['Pending', 'Deal', 'Batal']) ? $status_deal : 'Pending',
            'catatan'        => $catatan
        ];

        $inserted = $this->Md_member_visiacademy->addDeal($data);
        if ($inserted) {
            $newId = $this->db->insert_id();
            if ($data['status_deal'] === 'Deal' && $newId) {
                $this->_notifDiranggaDeal($newId);
            }
            ajaxReturnDie('success', 'Deal kebutuhan produk berhasil disimpan', true);
        } else {
            ajaxReturnDie('error', 'Gagal menyimpan data deal ke database.');
        }
    }

    public function updateDealStatus()
    {
        grantAccessFor('all');

        $id_deal = (int) $this->input->post('id_deal', true);
        $status  = $this->input->post('status_deal', true);

        if (!$id_deal || !in_array($status, ['Pending', 'Deal', 'Batal'])) {
            ajaxReturnDie('error', 'Data tidak valid.');
        }

        $oldDeal = $this->Md_member_visiacademy->getDealById($id_deal);
        if (!$oldDeal) {
            ajaxReturnDie('error', 'Data deal tidak ditemukan.');
        }

        $updateData = ['status_deal' => $status];

        $raw_nilai  = $this->input->post('estimasi_nilai', true);
        $raw_persen = $this->input->post('persen_komisi', true);
        $hasNilai   = ($raw_nilai !== null && trim((string)$raw_nilai) !== '');
        $hasPersen  = ($raw_persen !== null && trim((string)$raw_persen) !== '');

        if ($hasNilai) {
            $estimasi_nilai = $this->_parseRupiah($raw_nilai);
            if ($estimasi_nilai < 0) {
                ajaxReturnDie('error', 'Nilai transaksi tidak boleh negatif.');
            }
        }

        if ($hasPersen) {
            $clean_persen = str_replace(',', '.', trim((string)$raw_persen));
            $persen_komisi = (float) $clean_persen;
            if ($persen_komisi < 0 || $persen_komisi > 100) {
                ajaxReturnDie('error', 'Persentase komisi harus antara 0% sampai 100%.');
            }
        }

        if ($hasNilai || $hasPersen) {
            $finalNilai  = $hasNilai ? $estimasi_nilai : (float)($oldDeal['estimasi_nilai'] ?? 0);
            $finalPersen = $hasPersen ? $persen_komisi : (float)($oldDeal['persen_komisi'] ?? 2.0);
            if ($status === 'Deal' && $finalNilai <= 0) {
                ajaxReturnDie('error', 'Untuk status Deal, nilai transaksi final harus lebih dari 0!');
            }
            $updateData['estimasi_nilai'] = $finalNilai;
            $updateData['persen_komisi']  = $finalPersen;
            $updateData['nominal_komisi'] = round(($finalNilai * $finalPersen) / 100, 2);
        } elseif ($status === 'Deal' && (float)($oldDeal['estimasi_nilai'] ?? 0) <= 0) {
            ajaxReturnDie('error', 'Untuk status Deal, nilai transaksi final harus lebih dari 0!');
        }

        $raw_catatan = $this->input->post('catatan', true);
        if ($raw_catatan !== null) {
            $updateData['catatan'] = trim((string)$raw_catatan);
        }

        $this->Md_member_visiacademy->updateDeal($id_deal, $updateData);

        // Notifikasi otomatis ke Pak Dirangga saat status berubah menjadi DEAL
        if ($status === 'Deal' && ($oldDeal['status_deal'] ?? '') !== 'Deal') {
            $this->_notifDiranggaDeal($id_deal);
        }

        ajaxReturnDie('success', 'Status dan detail deal berhasil diperbarui.', true);
    }

    public function konfirmasiBayar()
    {
        grantAccessFor('all');

        $id_deal      = (int) $this->input->post('id_deal', true);
        $status_bayar = $this->input->post('status_bayar', true) ?: 'Sudah Ditransfer';
        $catatan      = trim($this->input->post('catatan_transfer', true));
        $tgl_input    = $this->input->post('tgl_transfer', true);

        if (!$id_deal) {
            ajaxReturnDie('error', 'ID Deal tidak valid.');
        }

        $deal = $this->Md_member_visiacademy->getDealById($id_deal);
        if (!$deal) {
            ajaxReturnDie('error', 'Data deal tidak ditemukan.');
        }

        $updateData = [
            'status_bayar'     => in_array($status_bayar, ['Belum Ditransfer', 'Sudah Ditransfer']) ? $status_bayar : 'Sudah Ditransfer',
            'catatan_transfer' => $catatan
        ];

        if ($updateData['status_bayar'] === 'Sudah Ditransfer') {
            $updateData['tgl_transfer'] = !empty($tgl_input) ? date('Y-m-d H:i:s', strtotime($tgl_input)) : date('Y-m-d H:i:s');
        } else {
            $updateData['tgl_transfer'] = null;
        }

        if (!empty($_FILES['bukti_transfer']['name'])) {
            $uploadDir = FCPATH . 'uploads/bukti_komisi/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $config['upload_path']   = $uploadDir;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf|webp';
            $config['max_size']      = 5120; // 5MB
            $config['file_name']     = 'bukti_' . $id_deal . '_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('bukti_transfer')) {
                $uploadData = $this->upload->data();
                $updateData['bukti_transfer'] = $uploadData['file_name'];
            } else {
                ajaxReturnDie('error', 'Gagal upload bukti transfer: ' . $this->upload->display_errors('', ''));
            }
        }

        $this->Md_member_visiacademy->updatePayment($id_deal, $updateData);
        ajaxReturnDie('success', 'Status dan data pembayaran komisi berhasil disimpan.', true);
    }

    public function deleteMember($id = null)
    {
        grantAccessFor('all');

        $id = (int) $id;
        if (!$id) {
            ajaxReturnDie('error', 'ID member tidak valid.');
        }

        $deals = $this->Md_member_visiacademy->getDealByMember($id);
        if (!empty($deals)) {
            ajaxReturnDie('error', 'Member tidak dapat dihapus karena sudah memiliki riwayat deal.');
        }

        $this->Md_member_visiacademy->deleteMember($id);
        ajaxReturnDie('success', 'Member berhasil dihapus.', true);
    }

    public function deleteDeal($id = null)
    {
        grantAccessFor('all');

        $id = (int) $id;
        if (!$id) {
            ajaxReturnDie('error', 'ID deal tidak valid.');
        }

        $this->Md_member_visiacademy->deleteDeal($id);
        ajaxReturnDie('success', 'Data deal berhasil dihapus.', true);
    }

    // =========================================================================
    // KATALOG PRODUK ALKES & BROSUR (va_katalog_produk)
    // =========================================================================

    public function saveKatalog()
    {
        grantAccessFor('all');

        $id_produk         = (int) $this->input->post('id_produk', true);
        $nama_produk       = trim((string) $this->input->post('nama_produk', true));
        $kategori          = trim((string) $this->input->post('kategori', true)) ?: 'Radiologi';
        $deskripsi_raw     = trim((string) $this->input->post('deskripsi_singkat', true));
        $deskripsi_singkat = ($deskripsi_raw !== '') ? $deskripsi_raw : null;
        $estimasi_harga    = $this->_parseRupiah($this->input->post('estimasi_harga', true));
        $urutan            = (int) $this->input->post('urutan', true);
        $statusRaw         = trim((string) $this->input->post('status', true));
        $status            = in_array($statusRaw, ['aktif', 'nonaktif'], true) ? $statusRaw : 'aktif';

        if ($nama_produk === '') {
            ajaxReturnDie('error', 'Nama produk katalog wajib diisi!');
        }
        if (mb_strlen($nama_produk) > 200) {
            ajaxReturnDie('error', 'Nama produk maksimal 200 karakter.');
        }
        if (mb_strlen($kategori) > 100) {
            ajaxReturnDie('error', 'Kategori maksimal 100 karakter.');
        }
        if ($estimasi_harga < 0) {
            ajaxReturnDie('error', 'Estimasi harga tidak boleh negatif.');
        }

        $existing = null;
        if ($id_produk > 0) {
            $existing = $this->Md_member_visiacademy->getKatalogById($id_produk);
            if (!$existing) {
                ajaxReturnDie('error', 'Data produk katalog tidak ditemukan.');
            }
        }

        $oldGambar = $existing['url_gambar'] ?? null;
        $oldBrosur = $existing['url_brosur'] ?? null;

        $gambarRes = $this->_resolveKatalogAsset(
            'file_gambar',
            'uploads/katalog/',
            'jpg|jpeg|png|webp',
            'katalog_' . $nama_produk,
            'url_gambar_external',
            'hapus_gambar',
            $oldGambar,
            'gambar produk'
        );
        if (!$gambarRes['ok']) {
            ajaxReturnDie('error', $gambarRes['error']);
        }

        $brosurRes = $this->_resolveKatalogAsset(
            'file_brosur',
            'uploads/brosur/',
            'pdf',
            'brosur_' . $nama_produk,
            'url_brosur_external',
            'hapus_brosur',
            $oldBrosur,
            'brosur PDF'
        );
        if (!$brosurRes['ok']) {
            if (!empty($gambarRes['uploaded'])) {
                $this->_deleteLocalUploadFile($gambarRes['uploaded'], 'uploads/katalog/');
            }
            ajaxReturnDie('error', $brosurRes['error']);
        }

        $data = [
            'nama_produk'       => $nama_produk,
            'kategori'          => $kategori,
            'deskripsi_singkat' => $deskripsi_singkat,
            'estimasi_harga'    => $estimasi_harga,
            'url_gambar'        => $gambarRes['value'],
            'url_brosur'        => $brosurRes['value'],
            'status'            => $status,
            'urutan'            => $urutan,
        ];

        $saved = ($id_produk > 0)
            ? $this->Md_member_visiacademy->updateKatalog($id_produk, $data)
            : $this->Md_member_visiacademy->addKatalog($data);

        if (!$saved) {
            if (!empty($gambarRes['uploaded'])) {
                $this->_deleteLocalUploadFile($gambarRes['uploaded'], 'uploads/katalog/');
            }
            if (!empty($brosurRes['uploaded'])) {
                $this->_deleteLocalUploadFile($brosurRes['uploaded'], 'uploads/brosur/');
            }
            ajaxReturnDie('error', 'Gagal menyimpan data produk katalog ke database.');
        }

        if ($id_produk > 0) {
            if (!empty($oldGambar) && $oldGambar !== $gambarRes['value']) {
                $this->_deleteLocalUploadFile($oldGambar, 'uploads/katalog/');
            }
            if (!empty($oldBrosur) && $oldBrosur !== $brosurRes['value']) {
                $this->_deleteLocalUploadFile($oldBrosur, 'uploads/brosur/');
            }
        }

        $msg = ($id_produk > 0)
            ? 'Produk katalog berhasil diperbarui.'
            : 'Produk katalog berhasil ditambahkan.';
        ajaxReturnDie('success', $msg, true);
    }

    public function toggleKatalogStatus($id = null)
    {
        grantAccessFor('all');

        $id = (int) ($id ?: $this->input->post('id_produk', true));
        if (!$id) {
            ajaxReturnDie('error', 'ID produk tidak valid.');
        }

        $item = $this->Md_member_visiacademy->getKatalogById($id);
        if (!$item) {
            ajaxReturnDie('error', 'Produk katalog tidak ditemukan.');
        }

        $requestedStatus = trim((string) $this->input->post('status', true));
        if (in_array($requestedStatus, ['aktif', 'nonaktif'], true)) {
            $newStatus = $requestedStatus;
        } else {
            $newStatus = (($item['status'] ?? 'aktif') === 'aktif') ? 'nonaktif' : 'aktif';
        }

        $updated = $this->Md_member_visiacademy->updateKatalog($id, ['status' => $newStatus]);
        if (!$updated) {
            ajaxReturnDie('error', 'Gagal mengubah status produk katalog.');
        }

        ajaxReturnDie('success', 'Status produk "' . $item['nama_produk'] . '" berhasil diubah menjadi ' . ucfirst($newStatus) . '.', true);
    }

    public function deleteKatalog($id = null)
    {
        grantAccessFor('all');

        $id = (int) $id;
        if (!$id) {
            ajaxReturnDie('error', 'ID produk tidak valid.');
        }

        $item = $this->Md_member_visiacademy->getKatalogById($id);
        if (!$item) {
            ajaxReturnDie('error', 'Produk katalog tidak ditemukan.');
        }

        $deleted = $this->Md_member_visiacademy->deleteKatalog($id);
        if (!$deleted) {
            ajaxReturnDie('error', 'Gagal menghapus produk katalog dari database.');
        }

        if (!empty($item['url_gambar'])) {
            $this->_deleteLocalUploadFile($item['url_gambar'], 'uploads/katalog/');
        }
        if (!empty($item['url_brosur'])) {
            $this->_deleteLocalUploadFile($item['url_brosur'], 'uploads/brosur/');
        }

        ajaxReturnDie('success', 'Produk katalog berhasil dihapus.', true);
    }

    private function _parseRupiah($raw)
    {
        $str = trim((string) $raw);
        $isNegative = (strlen($str) > 0 && $str[0] === '-');
        $str = preg_replace('/rp\.?|\s+/i', '', $str);
        $str = ltrim($str, '-');
        if ($str === '') {
            return 0.0;
        }

        if (preg_match('/^\d+\.\d{1,2}$/', $str)) {
            $val = (float) $str;
        } elseif (preg_match('/^\d{1,3}(\.\d{3})+(,\d{1,2})?$/', $str)) {
            $normalized = str_replace(['.', ','], ['', '.'], $str);
            $val = (float) $normalized;
        } else {
            $clean = preg_replace('/[^0-9]/', '', $str);
            $val = ($clean === '') ? 0.0 : (float) $clean;
        }

        return $isNegative ? -$val : $val;
    }

    private function _resolveKatalogAsset($fieldName, $subDir, $allowedTypes, $slugPrefix, $extInputName, $hapusInputName, $oldValue, $label)
    {
        if (!empty($_FILES[$fieldName]['name'])) {
            $upload = $this->_uploadKatalogFile($fieldName, $subDir, $allowedTypes, 4096, $slugPrefix);
            if (!$upload['ok']) {
                return ['ok' => false, 'error' => 'Gagal upload ' . $label . ': ' . $upload['error']];
            }
            return ['ok' => true, 'value' => $upload['path'], 'uploaded' => $upload['path']];
        }

        if ($this->input->post($hapusInputName, true) == '1') {
            return ['ok' => true, 'value' => null, 'uploaded' => null];
        }

        $extUrl = trim((string) $this->input->post($extInputName, true));
        if ($extUrl !== '') {
            if (!preg_match('#^https?://#i', $extUrl) || !filter_var($extUrl, FILTER_VALIDATE_URL)) {
                return ['ok' => false, 'error' => 'URL eksternal ' . $label . ' harus berupa tautan http:// atau https:// yang valid.'];
            }
            if (strlen($extUrl) > 255) {
                return ['ok' => false, 'error' => 'URL eksternal ' . $label . ' maksimal 255 karakter.'];
            }
            return ['ok' => true, 'value' => $extUrl, 'uploaded' => null];
        }

        if (!empty($oldValue) && preg_match('#^https?://#i', $oldValue)) {
            return ['ok' => true, 'value' => null, 'uploaded' => null];
        }

        return ['ok' => true, 'value' => $oldValue, 'uploaded' => null];
    }

    private function _uploadKatalogFile($fieldName, $subDir, $allowedTypes, $maxSizeKb, $prefixSlug)
    {
        $errorCode = $_FILES[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
            return ['ok' => false, 'error' => 'Ukuran file melebihi batas maksimal server (4MB).'];
        }
        if ($errorCode !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'error' => 'Upload file gagal (kode error: ' . (int) $errorCode . ').'];
        }

        $subDir = rtrim(str_replace('\\', '/', $subDir), '/') . '/';
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
            $slug = 'file';
        }
        $slug = rtrim(substr($slug, 0, 40), '_');

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
                'ok'   => true,
                'path' => $subDir . $uploadData['file_name'],
            ];
        }

        return [
            'ok'    => false,
            'error' => trim(strip_tags($this->upload->display_errors('', ''))),
        ];
    }

    private function _deleteLocalUploadFile($relativePath, $allowedPrefix)
    {
        $relativePath = trim((string) $relativePath);
        if ($relativePath === '' || strpos($relativePath, '..') !== false) {
            return false;
        }
        if (strpos($relativePath, $allowedPrefix) !== 0) {
            return false;
        }

        $baseDir = realpath(FCPATH . rtrim($allowedPrefix, '/'));
        $fullPath = realpath(FCPATH . $relativePath);
        if ($baseDir === false || $fullPath === false || !is_file($fullPath)) {
            return false;
        }

        $baseDirNorm = rtrim(str_replace('\\', '/', $baseDir), '/') . '/';
        $fullPathNorm = str_replace('\\', '/', $fullPath);
        if (strpos($fullPathNorm, $baseDirNorm) !== 0) {
            return false;
        }

        return @unlink($fullPath);
    }

    /**
     * Kirim notifikasi WhatsApp otomatis ke Pak Dirangga (Head of Accounting and Tax)
     * saat ada referral alkes yang berhasil DEAL dan komisi siap diproses.
     */
    private function _notifDiranggaDeal($id_deal)
    {
        try {
            $deal = $this->Md_member_visiacademy->getDealById($id_deal);
            if (!$deal) {
                return false;
            }

            // Cari nomor WhatsApp Pak Dirangga (pengguna_id: 106)
            $dirangga = $this->db->select('nama, no_hp, nohp')
                                 ->from('pengguna')
                                 ->group_start()
                                     ->where('pengguna_id', 106)
                                     ->or_like('nama', 'Dirangga', 'both')
                                 ->group_end()
                                 ->where('status', 1)
                                 ->limit(1)
                                 ->get()
                                 ->row_array();

            $penerima = '081275085794';
            if ($dirangga) {
                if (!empty($dirangga['no_hp'])) {
                    $penerima = $dirangga['no_hp'];
                } elseif (!empty($dirangga['nohp'])) {
                    $penerima = $dirangga['nohp'];
                }
            }

            $namaMember = $deal['nama_member'] ?: '-';
            $kodeMember = $deal['kode_member'] ?: '-';
            $institusi  = $deal['institusi_asal'] ?: '-';
            $produk     = $deal['nama_produk'] ?: '-';
            $nilaiDeal  = 'Rp ' . number_format($deal['estimasi_nilai'], 0, ',', '.');
            $persen     = $deal['persen_komisi'] . '%';
            $komisi     = 'Rp ' . number_format($deal['nominal_komisi'], 0, ',', '.');
            $bank       = $deal['nama_bank'] ?: 'Belum diisi';
            $noRek      = $deal['no_rekening'] ?: 'Belum diisi';
            $anRek      = $deal['atas_nama_rekening'] ?: $namaMember;
            $catatan    = !empty($deal['catatan']) ? $deal['catatan'] : '-';

            $pesan = "*Notifikasi Deal Baru - Member Visi Academy* 🤝%0A%0A"
                   . "Halo Pak Dirangga,%0A"
                   . "Terdapat transaksi pengadaan alkes dari program Referral Member Visi Academy yang telah berstatus *DEAL* dan siap diproses komisi pencairannya oleh Finance:%0A%0A"
                   . "📋 *Detail Transaksi:*%0A"
                   . "• *ID Deal:* #DL-" . str_pad($deal['id_deal'], 4, '0', STR_PAD_LEFT) . "%0A"
                   . "• *Produk Alkes:* " . $produk . "%0A"
                   . "• *Nilai Transaksi:* " . $nilaiDeal . "%0A"
                   . "• *Komisi Member (" . $persen . "):* *" . $komisi . "*%0A"
                   . "• *Catatan:* " . $catatan . "%0A%0A"
                   . "👤 *Data Penerima (Member):*%0A"
                   . "• *Nama:* " . $namaMember . " (" . $kodeMember . ")%0A"
                   . "• *Instansi:* " . $institusi . "%0A"
                   . "• *Bank:* " . $bank . "%0A"
                   . "• *No. Rekening:* *" . $noRek . "*%0A"
                   . "• *Atas Nama:* " . $anRek . "%0A%0A"
                   . "Silakan periksa detail rekening dan konfirmasi transfer komisi pada sistem ERP Office:%0A"
                   . "https://office.visiyosindo.id/member_visiacademy";

            sendWa([
                'devId'    => hostWa('3'),
                'penerima' => $penerima,
                'pesan'    => $pesan,
            ]);

            return true;
        } catch (\Throwable $e) {
            log_message('error', 'Gagal kirim notif WA Dirangga: ' . $e->getMessage());
            return false;
        }
    }
}
