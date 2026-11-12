<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Admin Controller: Digital QR Asset Passport
 * Mengelola registry alat pelanggan, foto produk, masa aktif (BAPETEN, LUK, Kalibrasi - tanpa TLD),
 * riwayat service terpadu, dan cetak label stiker QR.
 */
class Asset_passport extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Md_asset_passport');
        $this->load->model('md_pengguna');
        $this->load->helper(['url', 'form', 'mandatory_helper', 'whatsapp_helper']);
    }

    private function _id_navbar()
    {
        $src = strtolower((string)$this->input->get('nav'));
        if ($src === 'helpdesk') {
            return 'helpdesk';
        }
        return 'visilab';
    }

    public function index()
    {
        grantAccessFor('all');

        $sources = $this->Md_asset_passport->get_dropdown_sources();

        $page_data['switch']     = $this->_id_navbar();
        $page_data['page_name']  = 'visilab/v_asset_passport';
        $page_data['page_title'] = 'QR Asset Passport';
        $page_data['page_desc']  = 'Digital Passport Alat Kesehatan, Masa Aktif, Riwayat Service & Cetak Stiker QR';
        $page_data['stats']      = $this->Md_asset_passport->get_admin_stats();
        $page_data['sources']    = $sources;

        $this->load->view('index', $page_data);
    }

    public function data_json()
    {
        grantAccessFor('all');

        $filters = [
            'kategori_layanan' => $this->input->get('kategori_layanan', TRUE),
            'search'           => $this->input->get('search', TRUE)
        ];

        $rows = $this->Md_asset_passport->get_all_admin($filters);
        foreach ($rows as $r) {
            $r->public_url = base_url('passport/' . $r->qr_token);
            $r->print_url  = base_url('asset_passport/print_sticker/' . $r->id_passport);
            $r->foto_url   = $this->_resolve_foto_url($r->foto_produk);
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => true,
            'data'   => $rows,
            'stats'  => $this->Md_asset_passport->get_admin_stats()
        ]);
    }

    public function detail($id = 0)
    {
        grantAccessFor('all');
        header('Content-Type: application/json; charset=utf-8');

        try {
            $id = (int)$id;
            $row = $this->Md_asset_passport->get_by_id($id);
            if (!$row) {
                echo json_encode(['status' => false, 'message' => 'Data QR Passport tidak ditemukan.']);
                return;
            }

            $row->public_url = base_url('passport/' . $row->qr_token);
            $row->print_url  = base_url('asset_passport/print_sticker/' . $row->id_passport);
            $row->foto_url   = $this->_resolve_foto_url($row->foto_produk);

            $history  = $this->Md_asset_passport->get_service_history($id);
            $linkable = $this->Md_asset_passport->get_linkable_records($row->id_pelanggan);

            echo json_encode([
                'status'   => true,
                'data'     => $row,
                'history'  => $history,
                'linkable' => $linkable
            ]);
        } catch (\Throwable $e) {
            echo json_encode([
                'status'  => false,
                'message' => 'Gagal memuat detail QR Passport: ' . $e->getMessage()
            ]);
        }
    }

    public function save()
    {
        grantAccessFor('all');
        header('Content-Type: application/json; charset=utf-8');

        if (empty($_POST) && empty($_FILES) && !empty($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            echo json_encode([
                'status'  => false,
                'message' => 'Ukuran file foto terlalu besar melebihi batas server (maksimal 4 MB). Silakan pilih/perkecil foto terlebih dahulu.'
            ]);
            return;
        }

        $id_passport   = (int)$this->input->post('id_passport', TRUE);
        $nama_produk   = trim((string)$this->input->post('nama_produk', TRUE));
        $nama_instansi = trim((string)$this->input->post('nama_instansi', TRUE));
        $id_pelanggan  = (int)$this->input->post('id_pelanggan', TRUE);

        // Fallback nama_instansi dari master pelanggan jika dipilih
        if ($nama_instansi === '' && $id_pelanggan > 0) {
            $pel = $this->db->select('identitas_pelanggan')->from('pelanggan')->where('id_pelanggan', $id_pelanggan)->get()->row();
            if ($pel) {
                $nama_instansi = $pel->identitas_pelanggan;
            }
        }

        if ($nama_produk === '' && $nama_instansi === '') {
            echo json_encode(['status' => false, 'message' => 'Nama Produk dan Nama Instansi / Pelanggan wajib diisi.']);
            return;
        }
        if ($nama_instansi === '') {
            echo json_encode(['status' => false, 'message' => 'Nama Instansi / RS wajib diisi.']);
            return;
        }
        if ($nama_produk === '') {
            echo json_encode(['status' => false, 'message' => 'Nama Produk / Alat wajib diisi.']);
            return;
        }

        $existing = $id_passport > 0 ? $this->Md_asset_passport->get_by_id($id_passport) : null;
        $fotoPath = $existing ? (string)$existing->foto_produk : '';

        $fotoUrlInput = trim((string)$this->input->post('foto_produk_url', TRUE));
        if ($fotoUrlInput !== '') {
            $fotoPath = $fotoUrlInput;
        }

        if (!empty($_FILES['foto_produk_file']['name'])) {
            $uploadDir = FCPATH . 'uploads/passport/products/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }
            $this->load->library('upload');
            $allowedExt = ['jpg', 'jpeg', 'jpe', 'jfif', 'png', 'gif', 'webp', 'bmp', 'tif', 'tiff', 'avif', 'heic', 'heif', 'svg', 'ico'];
            $config = [
                'upload_path'   => $uploadDir,
                'allowed_types' => implode('|', array_merge($allowedExt, array_map('strtoupper', $allowedExt))),
                'max_size'      => 8192,
                'encrypt_name'  => true
            ];
            $this->upload->initialize($config);
            if ($this->upload->do_upload('foto_produk_file')) {
                $up = $this->upload->data();
                $fotoPath = 'uploads/passport/products/' . $up['file_name'];
            } else {
                // Fallback jika MIME OS/server berbeda padahal ekstensi & isi benar-benar gambar
                $origName = (string)($_FILES['foto_produk_file']['name'] ?? '');
                $tmpPath  = (string)($_FILES['foto_produk_file']['tmp_name'] ?? '');
                $ext      = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                $isImg    = in_array($ext, $allowedExt, true) && $tmpPath !== '' && is_uploaded_file($tmpPath) && (@getimagesize($tmpPath) !== false || in_array($ext, ['webp', 'avif', 'heic', 'heif', 'svg'], true));
                if ($isImg) {
                    $newName = (function_exists('random_bytes') ? bin2hex(random_bytes(16)) : md5(uniqid('', true))) . '.' . $ext;
                    if (@move_uploaded_file($tmpPath, $uploadDir . $newName)) {
                        $fotoPath = 'uploads/passport/products/' . $newName;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Gagal menyimpan file foto produk ke server.']);
                        return;
                    }
                } else {
                    echo json_encode(['status' => false, 'message' => 'Gagal upload foto produk: ' . strip_tags($this->upload->display_errors())]);
                    return;
                }
            }
        }

        // Info tambahan non-TLD (key-value aman untuk publik)
        $extraKeys = $this->input->post('extra_label', TRUE);
        $extraVals = $this->input->post('extra_value', TRUE);
        $infoTambahan = [];
        if (is_array($extraKeys) && is_array($extraVals)) {
            foreach ($extraKeys as $idx => $k) {
                $kClean = trim((string)$k);
                $vClean = trim((string)($extraVals[$idx] ?? ''));
                // Pastikan tidak memasukkan informasi TLD
                if ($kClean !== '' && $vClean !== '' && stripos($kClean, 'tld') === false) {
                    $infoTambahan[] = ['label' => $kClean, 'value' => $vClean];
                }
            }
        }

        $myId = (int)(function_exists('sessPenggunaId') ? sessPenggunaId() : 0);

        $payload = [
            'id_pelanggan'       => $id_pelanggan > 0 ? $id_pelanggan : null,
            'nama_instansi'      => $nama_instansi,
            'lokasi_ruangan'     => trim((string)$this->input->post('lokasi_ruangan', TRUE)) ?: null,
            'id_perizinan'       => ((int)$this->input->post('id_perizinan', TRUE)) ?: null,
            'id_pengujian'       => ((int)$this->input->post('id_pengujian', TRUE)) ?: null,
            'nama_produk'        => $nama_produk,
            'merk_tipe'          => trim((string)$this->input->post('merk_tipe', TRUE)) ?: null,
            'serial_number'      => trim((string)$this->input->post('serial_number', TRUE)) ?: null,
            'foto_produk'        => $fotoPath ?: null,
            'tgl_exp_izin'       => $this->_norm_date($this->input->post('tgl_exp_izin', TRUE)),
            'tgl_exp_luk'        => $this->_norm_date($this->input->post('tgl_exp_luk', TRUE)),
            'tgl_exp_kalibrasi'  => $this->_norm_date($this->input->post('tgl_exp_kalibrasi', TRUE)),
            'kode_uji_terakhir'  => trim((string)$this->input->post('kode_uji_terakhir', TRUE)) ?: null,
            'jenis_uji_terakhir' => trim((string)$this->input->post('jenis_uji_terakhir', TRUE)) ?: null,
            'link_lhu'           => trim((string)$this->input->post('link_lhu', TRUE)) ?: null,
            'link_sertifikat'    => trim((string)$this->input->post('link_sertifikat', TRUE)) ?: null,
            'info_tambahan_json' => !empty($infoTambahan) ? json_encode($infoTambahan, JSON_UNESCAPED_UNICODE) : null,
            'kategori_layanan'   => ((int)$this->input->post('kategori_layanan', TRUE) === 2) ? 2 : 1,
            'default_teknisi_id' => ((int)$this->input->post('default_teknisi_id', TRUE)) ?: null,
            'id_topik_default'   => ((int)$this->input->post('id_topik_default', TRUE)) ?: null,
            'catatan_internal'   => trim((string)$this->input->post('catatan_internal', TRUE)) ?: null,
            'is_active'          => $this->input->post('is_active') !== null ? (int)$this->input->post('is_active') : 1,
            'updated_by'         => $myId
        ];

        if ($id_passport <= 0) {
            $payload['created_by'] = $myId;
        }

        $savedId = $this->Md_asset_passport->save_passport($id_passport, $payload);

        // Kirim notifikasi WA ke grup saat QR Asset Passport baru dibuat
        if ($id_passport <= 0 && $savedId > 0 && function_exists('sendWaGroup')) {
            try {
                $savedRow = $this->Md_asset_passport->get_by_id($savedId);
                if ($savedRow) {
                    $qrLink = 'https://office.visiyosindo.id/passport/' . $savedRow->qr_token;
                    $snTxt  = !empty($savedRow->serial_number) ? $savedRow->serial_number : '-';
                    $targetGrp = ((int)$payload['kategori_layanan'] === 2) ? 'VISILAB' : 'TEKNISI MEDIKAL PT. VYM';
                    $pesanBaru = "*[QR ASSET PASSPORT BARU TERDAFTAR]* 📲\n\n"
                        . "🔖 *Kode:* {$savedRow->kode_passport}\n"
                        . "🏥 *Instansi:* {$savedRow->nama_instansi}\n"
                        . "🔬 *Produk:* {$savedRow->nama_produk}\n"
                        . "🔢 *No. Seri:* {$snTxt}\n"
                        . "🔗 *Link QR:* {$qrLink}";

                    @sendWaGroup([
                        'devId'     => hostWa('2'),
                        'penerima'  => $targetGrp,
                        'pesan'     => $pesanBaru,
                        'immediate' => true
                    ]);
                }
            } catch (\Throwable $e) {
            }
        }

        if (function_exists('addlog')) {
            @addlog('QR Asset Passport', ($id_passport > 0 ? 'Update' : 'Tambah') . ' QR Passport #' . $savedId . ' - ' . $nama_produk);
        }

        echo json_encode([
            'status'      => true,
            'message'     => $id_passport > 0 ? 'QR Asset Passport berhasil diperbarui.' : 'QR Asset Passport baru berhasil dibuat!',
            'id_passport' => $savedId
        ]);
    }

    public function delete($id = 0)
    {
        grantAccessFor('all');
        header('Content-Type: application/json; charset=utf-8');

        $id = (int)$id;
        if ($id <= 0) {
            echo json_encode(['status' => false, 'message' => 'ID tidak valid.']);
            return;
        }

        $ok = $this->Md_asset_passport->soft_delete($id);
        if ($ok && function_exists('addlog')) {
            @addlog('QR Asset Passport', 'Menghapus QR Passport ID #' . $id);
        }

        echo json_encode([
            'status'  => $ok,
            'message' => $ok ? 'QR Asset Passport berhasil dihapus.' : 'Data gagal dihapus.'
        ]);
    }

    /**
     * Tambah riwayat service manual dari Admin.
     */
    public function add_service()
    {
        grantAccessFor('all');
        header('Content-Type: application/json; charset=utf-8');

        $id_passport = (int)$this->input->post('id_passport', TRUE);
        $judul       = trim((string)$this->input->post('judul', TRUE));
        $tanggal     = $this->_norm_date($this->input->post('tanggal_service', TRUE)) ?: date('Y-m-d');

        if ($id_passport <= 0 || $judul === '') {
            echo json_encode(['status' => false, 'message' => 'ID Passport dan Judul Service wajib diisi.']);
            return;
        }

        $myId = (int)(function_exists('sessPenggunaId') ? sessPenggunaId() : 0);
        $idService = $this->Md_asset_passport->add_manual_service([
            'id_passport'        => $id_passport,
            'tanggal_service'    => $tanggal,
            'jenis_service'      => trim((string)$this->input->post('jenis_service', TRUE)) ?: 'Perbaikan / Service',
            'judul'              => $judul,
            'deskripsi_tindakan' => trim((string)$this->input->post('deskripsi_tindakan', TRUE)),
            'nama_teknisi'       => trim((string)$this->input->post('nama_teknisi', TRUE)) ?: 'Tim Teknisi VYM',
            'status_service'     => trim((string)$this->input->post('status_service', TRUE)) ?: 'Selesai',
            'created_by'         => $myId
        ]);

        echo json_encode([
            'status'     => $idService > 0,
            'message'    => 'Riwayat service berhasil ditambahkan.',
            'id_service' => $idService
        ]);
    }

    public function delete_service($id_service = 0)
    {
        grantAccessFor('all');
        header('Content-Type: application/json; charset=utf-8');

        $ok = $this->Md_asset_passport->delete_manual_service((int)$id_service);
        echo json_encode(['status' => $ok, 'message' => $ok ? 'Riwayat service dihapus.' : 'Gagal menghapus riwayat.']);
    }

    /**
     * Tautkan Tiket atau Preventif Maintenance lama ke alat QR Passport ini.
     */
    public function link_record()
    {
        grantAccessFor('all');
        header('Content-Type: application/json; charset=utf-8');

        $id_passport = (int)$this->input->post('id_passport', TRUE);
        $type        = trim((string)$this->input->post('record_type', TRUE));
        $recordId    = (int)$this->input->post('record_id', TRUE);

        if ($id_passport <= 0 || $recordId <= 0 || !in_array($type, ['tiket', 'pm'], true)) {
            echo json_encode(['status' => false, 'message' => 'Parameter tautan tidak valid.']);
            return;
        }

        if ($type === 'tiket') {
            $this->db->where('id_tiket', $recordId)->update('tiket', ['id_passport' => $id_passport]);
        } else {
            $this->db->where('id_pm', $recordId)->update('preventif_maintenance', ['id_passport' => $id_passport]);
        }

        echo json_encode(['status' => true, 'message' => 'Riwayat berhasil ditautkan ke QR Passport alat ini.']);
    }

    /**
     * Halaman Cetak Stiker QR (Tunggal atau Batch).
     * URL: /asset_passport/print_sticker/<id> atau /asset_passport/print_sticker?ids=1,2,3
     */
    public function print_sticker($id = 0)
    {
        grantAccessFor('all');

        $items = [];
        $id = (int)$id;
        $idsQuery = trim((string)$this->input->get('ids', TRUE));

        if ($id > 0) {
            $row = $this->Md_asset_passport->get_by_id($id);
            if ($row) {
                $items[] = $row;
            }
        } elseif ($idsQuery !== '') {
            $idList = array_filter(array_map('intval', explode(',', $idsQuery)));
            foreach ($idList as $pid) {
                $row = $this->Md_asset_passport->get_by_id($pid);
                if ($row) {
                    $items[] = $row;
                }
            }
        } else {
            $items = $this->Md_asset_passport->get_all_admin();
        }

        foreach ($items as $it) {
            $it->public_url = base_url('passport/' . $it->qr_token);
            $it->earliest_exp_label = $this->_earliest_expiry_label($it);
        }

        $data['items'] = $items;
        $data['size']  = $this->input->get('size', TRUE) ?: '8x5';
        $this->load->view('pages/visilab/v_asset_passport_print', $data);
    }

    private function _earliest_expiry_label($row)
    {
        $dates = [];
        foreach ([$row->tgl_exp_izin ?? null, $row->tgl_exp_luk ?? null, $row->tgl_exp_kalibrasi ?? null] as $d) {
            if (!empty($d) && $d !== '0000-00-00') {
                $dates[] = strtotime($d);
            }
        }
        if (empty($dates)) {
            return 'Terdaftar di Visilab';
        }
        sort($dates);
        return date('M Y', $dates[0]);
    }

    private function _norm_date($val)
    {
        $val = trim((string)$val);
        if ($val === '' || $val === '0000-00-00') {
            return null;
        }
        $ts = strtotime($val);
        return $ts ? date('Y-m-d', $ts) : null;
    }

    private function _resolve_foto_url($foto)
    {
        $foto = trim((string)$foto);
        if ($foto === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $foto)) {
            return $foto;
        }
        return base_url(ltrim($foto, '/'));
    }
}
