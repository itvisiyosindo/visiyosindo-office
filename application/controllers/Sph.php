<?php

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller untuk Surat Penawaran Harga (SPH)
 * 
 * Fitur:
 * - CRUD SPH dengan auto numbering
 * - Tabel produk dinamis (tambah baris/kolom)
 * - Sistem pembayaran: Cash, Tempo, Cicilan
 * - Preview & Print PDF
 * - Export laporan Excel
 */
class Sph extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_sph');
    }

    function id_navbar()
    {
        return "helpdesk";
    }

    // ========================================
    // HALAMAN UTAMA - LIST SPH
    // ========================================

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['counts'] = $this->md_sph->countByStatus(perusahaan());
        $page_data['page_name'] = 'sph/v_sph_list';
        $page_data['page_title'] = 'Surat Penawaran Harga';
        $page_data['page_desc'] = 'Management SPH';
        $this->load->view('index', $page_data);
    }

    /**
     * Pagination untuk datatables
     */
    public function pagination()
    {
        grantAccessFor('all');

        $dt = $this->md_sph->getAll(perusahaan());
        $start = $this->input->post('start');
        $data = array();

        foreach ($dt['data'] as $row) {
            $id = encrypt($row->id);

            // Status badge
            $statusBadge = '';
            switch ($row->status) {
                case 'draft':
                    $statusBadge = '<span class="badge badge-secondary">Draft</span>';
                    break;
                case 'final':
                    $statusBadge = '<span class="badge badge-info">Final</span>';
                    break;
                case 'signed':
                    $statusBadge = '<span class="badge badge-success">Signed</span>';
                    break;
                case 'cancelled':
                    $statusBadge = '<span class="badge badge-danger">Cancelled</span>';
                    break;
            }

            // Action buttons
            $actions = '<div class="btn-group">';
            $actions .= '<a href="' . base_url('sph/detail/' . $id) . '" class="btn btn-sm btn-info" title="Lihat"><i class="fas fa-eye"></i></a>';

            // Hanya bisa edit jika belum signed
            if ($row->status != 'signed' && $row->status != 'cancelled') {
                $actions .= '<a href="' . base_url('sph/edit/' . $id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>';
            }

            // Tombol Duplikasi - selalu tersedia
            $actions .= '<a href="' . base_url('sph/duplicate/' . $id) . '" class="btn btn-sm btn-secondary" title="Duplikasi"><i class="fas fa-copy"></i></a>';

            $actions .= '<a href="' . base_url('sph/print/' . $id) . '" class="btn btn-sm btn-primary" title="Print" target="_blank"><i class="fas fa-print"></i></a>';
            $actions .= '<a href="' . base_url('sph/download/' . $id) . '" class="btn btn-sm btn-success" title="Download"><i class="fas fa-download"></i></a>';

            // Hapus hanya untuk admin - gunakan class khusus untuk menghindari global handler
            if (isAdmin()) {
                $actions .= '<button type="button" class="btn btn-sm btn-danger btn-delete-sph" data-id="' . $id . '" title="Hapus"><i class="fas fa-trash"></i></button>';
            }

            $actions .= '</div>';

            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nomor_surat . ($row->is_visilab ? ' <span class="badge badge-info badge-sm">Visilab</span>' : '');
            $th[] = $this->md_sph->formatTanggalSurat($row->tanggal_surat, $row->kota);
            $th[] = $row->nama_penerima;
            $th[] = 'Rp ' . number_format($row->total_harga, 0, ',', '.');
            $th[] = $statusBadge;
            $th[] = $actions;
            $data[] = $th;
        }

        $dt['data'] = $data;
        echo json_encode($dt);
    }

    // ========================================
    // FORM TAMBAH SPH
    // ========================================

    public function tambah()
    {
        grantAccessFor('all');

        // Generate nomor surat
        $nomorData = $this->md_sph->generateNomorSurat(date('Y-m-d'));
        $nomorDataVisilab = $this->md_sph->generateNomorSuratVisilab(date('Y-m-d'));

        $page_data['switch'] = $this->id_navbar();
        $page_data['mode'] = 'tambah';
        $page_data['nomor_surat'] = $nomorData['nomor_surat'];
        $page_data['nomor_surat_visilab'] = $nomorDataVisilab['nomor_surat'];
        $page_data['keterangan_master'] = $this->md_sph->getAllKeteranganMaster(perusahaan());
        $page_data['page_name'] = 'sph/v_sph_form';
        $page_data['page_title'] = 'Buat Surat Penawaran Harga';
        $page_data['page_desc'] = 'Form input SPH baru';
        $this->load->view('index', $page_data);
    }

    /**
     * Get next nomor surat SPH via AJAX
     */
    public function get_next_number()
    {
        grantAccessFor('all');
        $tanggal = $this->input->post('tanggal');
        $isVisilab = $this->input->post('is_visilab') ? 1 : 0;

        if ($isVisilab) {
            $nomorData = $this->md_sph->generateNomorSuratVisilab($tanggal);
        } else {
            $nomorData = $this->md_sph->generateNomorSurat($tanggal);
        }

        echo json_encode([
            'status' => 'success',
            'nomor_surat' => $nomorData['nomor_surat']
        ]);
        die;
    }

    /**
     * Simpan SPH baru
     */
    public function simpan()
    {
        grantAccessFor('all');

        $this->db->trans_begin();

        try {
            // Cek apakah mode Visilab
            $isVisilab = $this->input->post('is_visilab') ? 1 : 0;

            // Generate nomor
            $tanggalSurat = $this->input->post('tanggal_surat');

            if ($isVisilab) {
                // Visilab: auto generate dengan format Visilab
                $nomorDataVisilab = $this->md_sph->generateNomorSuratVisilab($tanggalSurat);
                $nomorSurat = $nomorDataVisilab['nomor_surat'];
                $nomorUrut = $nomorDataVisilab['nomor_urut'];
                $tahun = $nomorDataVisilab['tahun'];
            } else {
                // Normal: auto generate
                $nomorData = $this->md_sph->generateNomorSurat($tanggalSurat);
                $nomorSurat = $nomorData['nomor_surat'];
                $nomorUrut = $nomorData['nomor_urut'];
                $tahun = $nomorData['tahun'];
            }

            // Ambil data penerima
            $tipePenerima = $this->input->post('tipe_penerima');
            $penerimaId = $this->input->post('penerima_id');

            if ($tipePenerima == 'pelanggan') {
                $penerima = $this->md_sph->getPelangganById($penerimaId);
            } else {
                $penerima = $this->md_sph->getCalonPelangganById($penerimaId);
            }

            $namaPenerima = $penerima ? $penerima->nama : '';
            $alamatPenerima = $penerima ? $penerima->alamat : '';

            // Data SPH utama
            $sphData = [
                'nomor_surat' => $nomorSurat,
                'nomor_urut' => $nomorUrut,
                'tahun' => $tahun,
                'tanggal_surat' => $tanggalSurat,
                'kota' => $this->input->post('kota'),
                'hal' => $this->input->post('hal') ?: 'Penawaran Harga',
                'sapaan' => $this->input->post('sapaan'),
                'tipe_penerima' => $tipePenerima,
                'penerima_id' => $penerimaId,
                'nama_penerima' => $namaPenerima,
                'alamat_penerima' => $alamatPenerima,
                'sistem_pembayaran' => $this->input->post('sistem_pembayaran'),
                'tempo_hari' => $this->input->post('tempo_hari') ?: null,
                'dp_persen' => $this->input->post('dp_persen') ?: null,
                'dp_nominal' => $this->input->post('dp_nominal') ?: null,
                'cicilan_bulan' => $this->input->post('cicilan_bulan') ?: null,
                'cicilan_per_bulan' => $this->input->post('cicilan_per_bulan') ?: null,
                'tampilkan_total' => $this->input->post('tampilkan_total') ? 1 : 0,
                'nama_ttd' => $this->input->post('nama_ttd') ?: 'Yolanda Pratiwi, S.Pd',
                'jabatan_ttd' => $this->input->post('jabatan_ttd') ?: 'General Manager',
                'status' => 'draft',
                'created_by' => sessPenggunaId(),
                'perusahaan' => perusahaan(),
                'is_visilab' => $isVisilab,
                'jenis_ppn' => $this->input->post('jenis_ppn') ?: 'ppn',
                'norek_perusahaan' => $this->input->post('norek_perusahaan'),
                'norek_a_n_bob' => $this->input->post('norek_a_n_bob')
            ];

            // Auto-fill norek_terpilih based on jenis_ppn
            $jenisPpn = $sphData['jenis_ppn'];
            $sphData['norek_terpilih'] = $jenisPpn == 'ppn' ? $sphData['norek_perusahaan'] : $sphData['norek_a_n_bob'];

            $sphId = $this->md_sph->create($sphData);

            // Simpan kolom dinamis DULU dan dapatkan mapping ID
            $columnIdMap = $this->simpanDynamicColumns($sphId);

            // Simpan items dengan mapping kolom dinamis
            $this->simpanItems($sphId, $columnIdMap);

            // Simpan keterangan yang dipilih
            $keteranganIds = $this->input->post('keterangan_ids');
            if ($keteranganIds) {
                $this->md_sph->saveKeteranganSelected($sphId, $keteranganIds);
            }

            // Simpan keterangan custom
            $keteranganCustom = $this->input->post('keterangan_custom');
            if ($keteranganCustom) {
                $this->md_sph->saveKeteranganCustom($sphId, $keteranganCustom);
            }

            // Update total harga
            $this->md_sph->updateTotalHarga($sphId);

            $this->db->trans_commit();

            // Cek apakah auto_preview
            $autoPreview = $this->input->post('auto_preview') == '1';

            $response = [
                'status' => 'success',
                'message' => 'SPH berhasil disimpan',
                'data' => [
                    'id' => encrypt($sphId),
                    'nomor_surat' => $nomorSurat,
                    'auto_preview' => $autoPreview,
                    'print_url' => base_url('sph/print/' . encrypt($sphId))
                ]
            ];
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $response = [
                'status' => 'error',
                'message' => 'Gagal menyimpan SPH: ' . $e->getMessage()
            ];
        }

        echo json_encode($response);
    }

    /**
     * Helper untuk menyimpan items
     * @param int $sphId ID SPH
     * @param array $columnIdMap Mapping dari 'new_X' ke ID database kolom dinamis
     */
    private function simpanItems($sphId, $columnIdMap = [])
    {
        $items = $this->input->post('items');
        if (!$items || !is_array($items)) return;

        $totalHarga = 0;

        foreach ($items as $index => $item) {
            $hargaPricelist = floatval(str_replace(['.', ','], ['', '.'], $item['harga_pricelist'] ?? 0));
            $tipeDiskon = $item['tipe_diskon'] ?? 'none';
            $qty = intval($item['qty'] ?? 1);

            // Ambil diskon value dan parse berdasarkan tipe
            $diskonValue = floatval(str_replace(['.', ','], ['', '.'], $item['diskon_value'] ?? 0));
            $diskonPersen = 0;
            $diskonNominal = 0;

            if ($tipeDiskon == 'persen') {
                $diskonPersen = $diskonValue;
            } elseif ($tipeDiskon == 'nominal') {
                $diskonNominal = $diskonValue;
            }

            // Hitung harga penawaran
            $hargaPenawaran = $this->md_sph->hitungHargaPenawaran(
                $hargaPricelist,
                $tipeDiskon,
                $diskonPersen,
                $diskonNominal
            );

            $subtotal = $hargaPenawaran * $qty;
            $totalHarga += $subtotal;

            // Update hidden_fields: ganti dyn_new_X atau dyn_existing_X dengan dyn_existing_{realId}
            $hiddenFields = json_decode($item['hidden_fields'] ?? '[]', true) ?: [];
            $updatedHiddenFields = [];
            foreach ($hiddenFields as $field) {
                // Cek apakah ini field kolom dinamis
                if (strpos($field, 'dyn_') === 0) {
                    // Ekstrak tempColId dari field name (e.g., dyn_new_1 -> new_1, dyn_existing_5 -> existing_5)
                    $tempColId = str_replace('dyn_', '', $field);
                    // Cari mapping ke real ID
                    if (isset($columnIdMap[$tempColId])) {
                        $updatedHiddenFields[] = 'dyn_existing_' . $columnIdMap[$tempColId];
                    } else {
                        // Tidak ada mapping, mungkin sudah format real ID
                        $updatedHiddenFields[] = $field;
                    }
                } else {
                    // Bukan field dinamis, keep as is
                    $updatedHiddenFields[] = $field;
                }
            }

            $itemData = [
                'sph_id' => $sphId,
                'urutan' => $index + 1,
                'pricelist_id' => !empty($item['pricelist_id']) ? $item['pricelist_id'] : null,
                'deskripsi' => $item['deskripsi'] ?? '',
                'jenis_harga' => $item['jenis_harga'] ?? 'regular',
                'harga_pricelist' => $hargaPricelist,
                'tipe_diskon' => $tipeDiskon,
                'diskon_persen' => $diskonPersen,
                'diskon_nominal' => $diskonNominal,
                'tampilkan_diskon' => isset($item['tampilkan_diskon']) ? 1 : 0,
                'harga_penawaran' => $hargaPenawaran,
                'qty' => $qty,
                'subtotal' => $subtotal,
                'hide_on_print' => isset($item['hide_on_print']) ? 1 : 0,
                'hidden_fields' => json_encode($updatedHiddenFields)
            ];

            $itemId = $this->md_sph->createItem($itemData);

            // Simpan nilai kolom dinamis untuk item ini
            if (isset($item['dynamic_values']) && is_array($item['dynamic_values']) && !empty($columnIdMap)) {
                foreach ($item['dynamic_values'] as $tempColId => $nilai) {
                    if (!empty($nilai)) {
                        // Dapatkan real ID dari mapping (new_1 -> real db id)
                        // tempColId bisa berupa 'new_1', 'new_2', dst
                        $realColId = isset($columnIdMap[$tempColId]) ? $columnIdMap[$tempColId] : null;

                        if ($realColId) {
                            // Cek apakah hide_on_print dicentang
                            $hideOnPrint = 0;
                            if (isset($item['dynamic_hide'][$tempColId])) {
                                $hideOnPrint = 1;
                            }

                            $this->md_sph->createDynamicValue([
                                'sph_item_id' => $itemId,
                                'sph_column_id' => $realColId,
                                'nilai' => $nilai,
                                'hide_on_print' => $hideOnPrint
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Helper untuk menyimpan kolom dinamis
     * @return array Mapping dari 'new_X' atau 'existing_X' ke real database ID
     */
    private function simpanDynamicColumns($sphId)
    {
        $columns = $this->input->post('dynamic_columns');
        if (!$columns || !is_array($columns)) return [];

        $columnIdMap = [];
        $urutan = 1;

        // $columns bisa berformat:
        // ['new_1' => 'Nama Kolom'] untuk kolom baru
        // ['existing_5' => 'Nama Kolom'] untuk kolom yang sudah ada sebelumnya
        foreach ($columns as $tempColId => $colName) {
            if (!empty(trim($colName))) {
                $realColId = $this->md_sph->createDynamicColumn([
                    'sph_id' => $sphId,
                    'nama_kolom' => trim($colName),
                    'urutan' => $urutan++
                ]);
                // Map temporary/existing ID ke real database ID baru
                $columnIdMap[$tempColId] = $realColId;
            }
        }

        return $columnIdMap;
    }

    // ========================================
    // FORM EDIT SPH
    // ========================================

    public function edit($id)
    {
        grantAccessFor('all');

        $id = decrypt($id);
        $sph = $this->md_sph->getById($id);

        if (!$sph) {
            $this->session->set_flashdata('error', 'Data SPH tidak ditemukan');
            redirect('sph');
        }

        // Cek apakah bisa diedit
        if ($sph->status == 'signed') {
            $this->session->set_flashdata('error', 'SPH yang sudah ditandatangani tidak dapat diedit');
            redirect('sph/detail/' . encrypt($id));
        }

        $page_data['switch'] = $this->id_navbar();
        $page_data['mode'] = 'edit';
        $page_data['sph'] = $sph;
        $page_data['nomor_surat_visilab'] = $this->md_sph->generateNomorSuratVisilab(date('Y-m-d'))['nomor_surat'];
        $page_data['keterangan_master'] = $this->md_sph->getAllKeteranganMaster(perusahaan());
        $page_data['page_name'] = 'sph/v_sph_form';
        $page_data['page_title'] = 'Edit Surat Penawaran Harga';
        $page_data['page_desc'] = 'Edit SPH: ' . $sph->nomor_surat;
        $this->load->view('index', $page_data);
    }
    
    // ========================================
    // DUPLIKASI SPH
    // ========================================

    /**
     * Duplikasi SPH - Buka form dengan data dari SPH yang ada
     * Akan menjadi data baru saat disimpan
     */
    public function duplicate($id)
    {
        grantAccessFor('all');

        $id = decrypt($id);
        $sph = $this->md_sph->getById($id);

        if (!$sph) {
            $this->session->set_flashdata('error', 'Data SPH tidak ditemukan');
            redirect('sph');
        }

        // Reset beberapa field untuk data baru
        $sph->id = null; // Akan generate baru
        $sph->nomor_surat = ''; // Akan generate baru
        $sph->tanggal_surat = date('Y-m-d'); // Tanggal hari ini
        $sph->status = 'draft'; // Reset ke draft
        $sph->signed_by = null;
        $sph->signed_at = null;

        $page_data['switch'] = $this->id_navbar();
        $page_data['mode'] = 'duplicate'; // Mode duplikasi
        $page_data['sph'] = $sph;
        $page_data['nomor_surat_visilab'] = $this->md_sph->generateNomorSuratVisilab(date('Y-m-d'))['nomor_surat'];
        $page_data['keterangan_master'] = $this->md_sph->getAllKeteranganMaster(perusahaan());
        $page_data['page_name'] = 'sph/v_sph_form';
        $page_data['page_title'] = 'Duplikasi Surat Penawaran Harga';
        $page_data['page_desc'] = 'Duplikasi dari: ' . $this->md_sph->getById($id)->nomor_surat;
        $this->load->view('index', $page_data);
    }

    /**
     * Update SPH
     */
    public function update()
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id'));
        $sph = $this->md_sph->getById($id);

        if (!$sph || $sph->status == 'signed') {
            echo json_encode(['status' => 'error', 'message' => 'SPH tidak dapat diupdate']);
            return;
        }

        $this->db->trans_begin();

        try {
            // Ambil data penerima
            $tipePenerima = $this->input->post('tipe_penerima');
            $penerimaId = $this->input->post('penerima_id');

            if ($tipePenerima == 'pelanggan') {
                $penerima = $this->md_sph->getPelangganById($penerimaId);
            } else {
                $penerima = $this->md_sph->getCalonPelangganById($penerimaId);
            }

            $namaPenerima = $penerima ? $penerima->nama : '';
            $alamatPenerima = $penerima ? $penerima->alamat : '';

            // Cek apakah mode Visilab
            $isVisilab = $this->input->post('is_visilab') ? 1 : 0;

            // Data update
            $sphData = [
                'tanggal_surat' => $this->input->post('tanggal_surat'),
                'kota' => $this->input->post('kota'),
                'hal' => $this->input->post('hal') ?: 'Penawaran Harga',
                'sapaan' => $this->input->post('sapaan'),
                'tipe_penerima' => $tipePenerima,
                'penerima_id' => $penerimaId,
                'nama_penerima' => $namaPenerima,
                'alamat_penerima' => $alamatPenerima,
                'sistem_pembayaran' => $this->input->post('sistem_pembayaran'),
                'tempo_hari' => $this->input->post('tempo_hari') ?: null,
                'dp_persen' => $this->input->post('dp_persen') ?: null,
                'dp_nominal' => $this->input->post('dp_nominal') ?: null,
                'cicilan_bulan' => $this->input->post('cicilan_bulan') ?: null,
                'cicilan_per_bulan' => $this->input->post('cicilan_per_bulan') ?: null,
                'tampilkan_total' => $this->input->post('tampilkan_total') ? 1 : 0,
                'nama_ttd' => $this->input->post('nama_ttd') ?: 'Yolanda Pratiwi, S.Pd',
                'jabatan_ttd' => $this->input->post('jabatan_ttd') ?: 'General Manager',
                'is_visilab' => $isVisilab,
                'jenis_ppn' => $this->input->post('jenis_ppn') ?: 'ppn',
                'norek_perusahaan' => $this->input->post('norek_perusahaan'),
                'norek_a_n_bob' => $this->input->post('norek_a_n_bob')
            ];

            // Auto-fill norek_terpilih based on jenis_ppn
            $jenisPpn = $sphData['jenis_ppn'];
            $sphData['norek_terpilih'] = $jenisPpn == 'ppn' ? $sphData['norek_perusahaan'] : $sphData['norek_a_n_bob'];

            // Regenerate nomor surat jika divisi (is_visilab) atau bulan/tahun tanggal_surat berubah
            $originalTahun = date('Y', strtotime($sph->tanggal_surat));
            $originalBulan = date('m', strtotime($sph->tanggal_surat));

            $newTahun = date('Y', strtotime($sphData['tanggal_surat']));
            $newBulan = date('m', strtotime($sphData['tanggal_surat']));

            if ($isVisilab != $sph->is_visilab || $originalTahun != $newTahun || $originalBulan != $newBulan) {
                if ($isVisilab) {
                    $nomorData = $this->md_sph->generateNomorSuratVisilab($sphData['tanggal_surat']);
                } else {
                    $nomorData = $this->md_sph->generateNomorSurat($sphData['tanggal_surat']);
                }
                $sphData['nomor_surat'] = $nomorData['nomor_surat'];
                $sphData['nomor_urut'] = $nomorData['nomor_urut'];
                $sphData['tahun'] = $nomorData['tahun'];
            }

            $this->md_sph->update($id, $sphData);

            // Hapus items lama dan kolom dinamis lama
            $this->md_sph->deleteItemsBySph($id);
            $this->md_sph->deleteDynamicColumnsBySph($id);

            // Simpan kolom dinamis DULU dan dapatkan mapping ID
            $columnIdMap = $this->simpanDynamicColumns($id);

            // Simpan items dengan mapping kolom dinamis
            $this->simpanItems($id, $columnIdMap);

            // Simpan keterangan
            $keteranganIds = $this->input->post('keterangan_ids');
            $this->md_sph->saveKeteranganSelected($id, $keteranganIds ?: []);

            $keteranganCustom = $this->input->post('keterangan_custom');
            $this->md_sph->saveKeteranganCustom($id, $keteranganCustom ?: []);

            // Update total harga
            $this->md_sph->updateTotalHarga($id);

            $this->db->trans_commit();

            $response = [
                'status' => 'success',
                'message' => 'SPH berhasil diupdate',
                'data' => ['id' => encrypt($id)]
            ];
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $response = [
                'status' => 'error',
                'message' => 'Gagal mengupdate SPH: ' . $e->getMessage()
            ];
        }

        echo json_encode($response);
    }

    // ========================================
    // DETAIL SPH
    // ========================================

    public function detail($id)
    {
        grantAccessFor('all');

        $id = decrypt($id);
        $sph = $this->md_sph->getById($id);

        if (!$sph) {
            $this->session->set_flashdata('error', 'Data SPH tidak ditemukan');
            redirect('sph');
        }

        $page_data['switch'] = $this->id_navbar();
        $page_data['sph'] = $sph;
        $page_data['page_name'] = 'sph/v_sph_detail';
        $page_data['page_title'] = 'Detail Surat Penawaran Harga';
        $page_data['page_desc'] = $sph->nomor_surat;
        $this->load->view('index', $page_data);
    }

    // ========================================
    // HAPUS SPH
    // ========================================

    public function hapus()
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id'));
        $sph = $this->md_sph->getById($id);

        if (!$sph) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            return;
        }

        if ($sph->status != 'draft') {
            echo json_encode(['status' => 'error', 'message' => 'Hanya SPH draft yang dapat dihapus']);
            return;
        }

        $this->md_sph->delete($id);

        echo json_encode(['status' => 'success', 'message' => 'SPH berhasil dihapus']);
    }

    // ========================================
    // TANDA TANGAN & FINALIZE
    // ========================================

    public function finalize()
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id'));
        $sph = $this->md_sph->getById($id);

        if (!$sph || $sph->status != 'draft') {
            echo json_encode(['status' => 'error', 'message' => 'SPH tidak dapat difinalisasi']);
            return;
        }

        $this->md_sph->finalizeSph($id);

        echo json_encode(['status' => 'success', 'message' => 'SPH telah difinalisasi']);
    }

    /**
     * Revert SPH dari status final ke draft untuk revisi
     */
    public function revertToDraft()
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id'));
        $sph = $this->md_sph->getById($id);

        if (!$sph) {
            echo json_encode(['status' => 'error', 'message' => 'SPH tidak ditemukan']);
            return;
        }

        if ($sph->status != 'final') {
            echo json_encode(['status' => 'error', 'message' => 'Hanya SPH dengan status Final yang dapat di-revisi']);
            return;
        }

        // Kembalikan status ke draft
        $this->md_sph->revertToDraft($id);

        echo json_encode([
            'status' => 'success',
            'message' => 'SPH telah dikembalikan ke Draft',
            'redirect_url' => base_url('sph/edit/' . encrypt($id))
        ]);
    }

    public function sign()
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id'));
        $sph = $this->md_sph->getById($id);

        if (!$sph || $sph->status == 'signed' || $sph->status == 'cancelled') {
            echo json_encode(['status' => 'error', 'message' => 'SPH tidak dapat ditandatangani']);
            return;
        }

        $this->md_sph->signSph($id, sessPenggunaId());

        echo json_encode(['status' => 'success', 'message' => 'SPH telah ditandatangani']);
    }
    
    // ========================================
    // PRINT & DOWNLOAD PDF
    // ========================================

    /**
     * Buat instance mPDF dengan header kop & footer kop di setiap halaman
     */
    private function createMpdfWithKop()
    {
        // Pastikan tempDir writable untuk font cache mPDF
        $tempDir = APPPATH . 'cache/mpdf';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 58,
            'margin_bottom' => 18,
            'margin_header' => 5,
            'margin_footer' => 3,
            'default_font' => 'dejavusans',
            'tempDir' => $tempDir,
        ]);

        $headerHtml = '
        <div style="text-align:center;">
            <img src="' . FCPATH . 'assets/img/kop_baru.jpg" style="width:100%;" />
            <hr style="border:0; border-top:3px solid #1a237e; margin:3px 0;" />
            <hr style="border:0; border-top:3px solid #1a237e; margin:0;" />
        </div>';

        $footerHtml = '
        <div style="text-align:center;">
            <img src="' . FCPATH . 'assets/img/kop_surat_bawah.jpg" style="width:100%;" />
        </div>';

        $mpdf->SetHTMLHeader($headerHtml);
        $mpdf->SetHTMLFooter($footerHtml);
        $mpdf->shrink_tables_to_fit = 0;

        return $mpdf;
    }

    /**
     * Print SPH - Output PDF langsung ke browser (inline)
     */
    public function print($id)
    {
        grantAccessFor('all');

        $id = decrypt($id);
        $sph = $this->md_sph->getById($id);

        if (!$sph) {
            show_error('Data SPH tidak ditemukan', 404);
            return;
        }

        // Generate PDF dengan mPDF
        $data['sph'] = $sph;
        $data['is_pdf'] = true;
        $html = $this->load->view('pages/sph/v_sph_print', $data, true);

        require_once APPPATH . 'vendor/autoload.php';

        $mpdf = $this->createMpdfWithKop();
        $mpdf->WriteHTML($html);

        $filename = 'SPH_' . str_replace('/', '-', $sph->nomor_surat) . '.pdf';
        $mpdf->Output($filename, 'I'); // 'I' = Inline (tampil di browser)
    }

    /**
     * Download SPH - Output PDF sebagai file download
     */
    public function download($id)
    {
        grantAccessFor('all');

        $id = decrypt($id);
        $sph = $this->md_sph->getById($id);

        if (!$sph) {
            show_error('Data SPH tidak ditemukan', 404);
            return;
        }

        // Generate PDF dengan mPDF
        $data['sph'] = $sph;
        $data['is_pdf'] = true;
        $html = $this->load->view('pages/sph/v_sph_print', $data, true);

        require_once APPPATH . 'vendor/autoload.php';

        $mpdf = $this->createMpdfWithKop();
        $mpdf->WriteHTML($html);

        $filename = 'SPH_' . str_replace('/', '-', $sph->nomor_surat) . '.pdf';
        $mpdf->Output($filename, 'D'); // 'D' = Download
    }
    
    // ========================================
    // DELETE SPH (ADMIN ONLY)
    // ========================================

    /**
     * Hapus SPH - hanya untuk Administrator
     */
    public function delete()
    {
        grantAccessFor(['Administrator']);

        $id = decrypt($this->input->post('id'));
        $sph = $this->md_sph->getById($id);

        if (!$sph) {
            echo json_encode(['status' => 'error', 'message' => 'Data SPH tidak ditemukan']);
            return;
        }

        $this->db->trans_begin();

        try {
            // Hapus keterangan selected dan custom
            $this->db->where('sph_id', $id)->delete('sph_keterangan_selected');
            $this->db->where('sph_id', $id)->delete('sph_keterangan_custom');

            // Hapus nilai dinamis item
            $items = $this->db->where('sph_id', $id)->get('sph_items')->result();
            foreach ($items as $item) {
                $this->db->where('sph_item_id', $item->id)->delete('sph_dynamic_values');
            }

            // Hapus items
            $this->db->where('sph_id', $id)->delete('sph_items');

            // Hapus kolom dinamis
            $this->db->where('sph_id', $id)->delete('sph_dynamic_columns');

            // Hapus SPH utama
            $this->db->where('id', $id)->delete('sph_penawaran');

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menghapus data');
            }

            $this->db->trans_commit();
            echo json_encode(['status' => 'success', 'message' => 'SPH berhasil dihapus']);
        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    
    // ========================================
    // AJAX ENDPOINTS
    // ========================================

    /**
     * Search pricelist untuk autocomplete
     */
    public function search_pricelist()
    {
        $keyword = $this->input->get('q');
        $jenis = $this->input->get('jenis');

        $results = $this->md_sph->searchPricelist($keyword, $jenis);

        $data = [];
        foreach ($results as $row) {
            $data[] = [
                'id' => $row->id,
                'text' => $row->merk . ' - ' . $row->nama,
                'merk' => $row->merk,
                'nama' => $row->nama,
                'harga' => $row->harga,
                'jenis' => $row->jenis
            ];
        }

        echo json_encode(['results' => $data]);
    }

    /**
     * Search pelanggan
     */
    public function search_pelanggan()
    {
        $keyword = $this->input->get('q');
        $tipe = $this->input->get('tipe'); // 'pelanggan' atau 'calon_pelanggan'

        if ($tipe == 'calon_pelanggan') {
            $results = $this->md_sph->searchCalonPelanggan($keyword);
        } else {
            $results = $this->md_sph->searchPelanggan($keyword);
        }

        $data = [];
        foreach ($results as $row) {
            $data[] = [
                'id' => $row->id,
                'text' => $row->nama,
                'nama' => $row->nama,
                'alamat' => $row->alamat,
                'kota' => $row->kota ?? '',
                'provinsi' => $row->provinsi ?? ''
            ];
        }

        echo json_encode(['results' => $data]);
    }

    /**
     * Get detail pricelist
     */
    public function get_pricelist_detail()
    {
        $id = $this->input->get('id');
        $pricelist = $this->md_sph->getPricelistById($id);

        if ($pricelist) {
            echo json_encode([
                'status' => 'success',
                'data' => $pricelist
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Preview nomor surat
     */
    public function preview_nomor()
    {
        $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
        $isVisilab = $this->input->get('is_visilab');

        if ($isVisilab) {
            $nomorData = $this->md_sph->generateNomorSuratVisilab($tanggal);
        } else {
            $nomorData = $this->md_sph->generateNomorSurat($tanggal);
        }

        echo json_encode($nomorData);
    }

    /**
     * Hitung cicilan
     */
    public function hitung_cicilan()
    {
        $totalHarga = floatval($this->input->get('total'));
        $dpPersen = floatval($this->input->get('dp_persen'));
        $jumlahBulan = intval($this->input->get('bulan'));

        $result = $this->md_sph->hitungCicilan($totalHarga, $dpPersen, $jumlahBulan);

        echo json_encode($result);
    }

    // ========================================
    // MASTER KETERANGAN
    // ========================================

    public function keterangan()
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['keterangan'] = $this->md_sph->getAllKeteranganMaster(perusahaan());
        $page_data['page_name'] = 'sph/v_sph_keterangan';
        $page_data['page_title'] = 'Master Keterangan SPH';
        $page_data['page_desc'] = 'Kelola data keterangan untuk SPH';
        $this->load->view('index', $page_data);
    }

    public function keterangan_simpan()
    {
        grantAccessFor('all');

        $id = $this->input->post('id');
        $keterangan = $this->input->post('keterangan');

        $data = [
            'keterangan' => $keterangan,
            'tipe' => $this->input->post('tipe'),
            'urutan' => $this->input->post('urutan') ?: 0,
            'perusahaan' => perusahaan()
        ];

        // Validasi duplikasi
        $exists = $this->md_sph->checkKeteranganExists($keterangan, perusahaan(), $id);
        if ($exists) {
            echo json_encode(['status' => 'error', 'message' => 'Keterangan "' . $keterangan . '" sudah ada!']);
            return;
        }

        if ($id) {
            $this->md_sph->updateKeteranganMaster($id, $data);
            $message = 'Keterangan berhasil diupdate';
        } else {
            $this->md_sph->createKeteranganMaster($data);
            $message = 'Keterangan berhasil ditambahkan';
        }

        echo json_encode(['status' => 'success', 'message' => $message]);
    }

    public function keterangan_hapus()
    {
        grantAccessFor('all');

        $id = $this->input->post('id');
        $this->md_sph->deleteKeteranganMaster($id);

        echo json_encode(['status' => 'success', 'message' => 'Keterangan berhasil dihapus']);
    }

    // ========================================
    // LAPORAN
    // ========================================

    public function laporan()
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['stats'] = $this->md_sph->countForLaporan(perusahaan());
        $page_data['page_name'] = 'sph/v_sph_laporan';
        $page_data['page_title'] = 'Laporan Surat Penawaran Harga';
        $page_data['page_desc'] = 'Export laporan SPH';
        $this->load->view('index', $page_data);
    }

    public function export_laporan()
    {
        grantAccessFor('all');

        $fromDate = $this->input->get('dari');
        $toDate = $this->input->get('sampai');

        if (!$fromDate || !$toDate) {
            $this->session->set_flashdata('error', 'Tanggal dari dan sampai harus diisi');
            redirect('sph/laporan');
        }

        $data = $this->md_sph->getReport($fromDate, $toDate, perusahaan());

        if (empty($data)) {
            $this->session->set_flashdata('error', 'Tidak ada data SPH dalam rentang tanggal yang dipilih');
            redirect('sph/laporan');
        }

        require_once APPPATH . 'vendor/autoload.php';

        $spreadsheet = new Spreadsheet();
        $sheetSummary = $spreadsheet->getActiveSheet();
        $sheetSummary->setTitle('Ringkasan SPH');

        $headerStyle = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4472C4']
            ],
            'font' => ['color' => ['argb' => 'FFFFFFFF'], 'bold' => true]
        ];

        // Sheet 1 - Ringkasan SPH (lengkap data header SPH)
        $summaryHeaders = [
            'No',
            'ID SPH',
            'Nomor Surat',
            'Nomor Urut',
            'Tahun',
            'Tanggal Surat',
            'Kota',
            'Hal',
            'Sapaan',
            'Tipe Penerima',
            'Penerima ID',
            'Nama Penerima',
            'Alamat Penerima',
            'Sistem Pembayaran',
            'Tempo (Hari)',
            'DP (%)',
            'DP Nominal',
            'Cicilan (Bulan)',
            'Cicilan/Bulan',
            'Tampilkan Total',
            'Nama TTD',
            'Jabatan TTD',
            'Status',
            'Is Visilab',
            'Total Harga',
            'Created By (ID)',
            'Dibuat Oleh',
            'Signed By',
            'Signed At',
            'Perusahaan'
        ];
        $sheetSummary->fromArray($summaryHeaders, null, 'A1');
        $sheetSummary->getStyle('A1:AD1')->applyFromArray($headerStyle);

        // Sheet 2 - Detail Item Produk
        $sheetItems = $spreadsheet->createSheet();
        $sheetItems->setTitle('Detail Produk');
        $itemHeaders = [
            'No',
            'ID SPH',
            'Nomor Surat',
            'Tanggal Surat',
            'Nama Penerima',
            'Urutan Item',
            'Item ID',
            'Pricelist ID',
            'Merk Pricelist',
            'Nama Pricelist',
            'Deskripsi',
            'Jenis Harga',
            'Harga Pricelist',
            'Tipe Diskon',
            'Diskon (%)',
            'Diskon Nominal',
            'Tampilkan Diskon',
            'Harga Penawaran',
            'Qty',
            'Subtotal',
            'Hide Item On Print',
            'Hidden Fields',
            'Dynamic Values'
        ];
        $sheetItems->fromArray($itemHeaders, null, 'A1');
        $sheetItems->getStyle('A1:W1')->applyFromArray($headerStyle);

        // Sheet 3 - Keterangan
        $sheetKeterangan = $spreadsheet->createSheet();
        $sheetKeterangan->setTitle('Keterangan');
        $keteranganHeaders = [
            'No',
            'ID SPH',
            'Nomor Surat',
            'Tanggal Surat',
            'Nama Penerima',
            'Jenis Keterangan',
            'Urutan',
            'Isi Keterangan'
        ];
        $sheetKeterangan->fromArray($keteranganHeaders, null, 'A1');
        $sheetKeterangan->getStyle('A1:H1')->applyFromArray($headerStyle);

        $summaryRow = 2;
        $itemRow = 2;
        $keteranganRow = 2;
        $summaryNo = 1;
        $itemNo = 1;
        $keteranganNo = 1;

        foreach ($data as $item) {
            // Ambil data lengkap per SPH agar export mencakup seluruh detail
            $sphDetail = $this->md_sph->getById($item->id);
            if (!$sphDetail) {
                continue;
            }

            $sheetSummary->setCellValue('A' . $summaryRow, $summaryNo++);
            $sheetSummary->setCellValue('B' . $summaryRow, $sphDetail->id);
            $sheetSummary->setCellValue('C' . $summaryRow, $sphDetail->nomor_surat);
            $sheetSummary->setCellValue('D' . $summaryRow, $sphDetail->nomor_urut);
            $sheetSummary->setCellValue('E' . $summaryRow, $sphDetail->tahun);
            $sheetSummary->setCellValue('F' . $summaryRow, $sphDetail->tanggal_surat);
            $sheetSummary->setCellValue('G' . $summaryRow, $sphDetail->kota);
            $sheetSummary->setCellValue('H' . $summaryRow, $sphDetail->hal);
            $sheetSummary->setCellValue('I' . $summaryRow, $sphDetail->sapaan);
            $sheetSummary->setCellValue('J' . $summaryRow, $sphDetail->tipe_penerima);
            $sheetSummary->setCellValue('K' . $summaryRow, $sphDetail->penerima_id);
            $sheetSummary->setCellValue('L' . $summaryRow, $sphDetail->nama_penerima);
            $sheetSummary->setCellValue('M' . $summaryRow, $sphDetail->alamat_penerima);
            $sheetSummary->setCellValue('N' . $summaryRow, $sphDetail->sistem_pembayaran);
            $sheetSummary->setCellValue('O' . $summaryRow, $sphDetail->tempo_hari);
            $sheetSummary->setCellValue('P' . $summaryRow, $sphDetail->dp_persen);
            $sheetSummary->setCellValue('Q' . $summaryRow, $sphDetail->dp_nominal);
            $sheetSummary->setCellValue('R' . $summaryRow, $sphDetail->cicilan_bulan);
            $sheetSummary->setCellValue('S' . $summaryRow, $sphDetail->cicilan_per_bulan);
            $sheetSummary->setCellValue('T' . $summaryRow, $sphDetail->tampilkan_total ? 'Ya' : 'Tidak');
            $sheetSummary->setCellValue('U' . $summaryRow, $sphDetail->nama_ttd);
            $sheetSummary->setCellValue('V' . $summaryRow, $sphDetail->jabatan_ttd);
            $sheetSummary->setCellValue('W' . $summaryRow, ucfirst($sphDetail->status));
            $sheetSummary->setCellValue('X' . $summaryRow, $sphDetail->is_visilab ? 'Ya' : 'Tidak');
            $sheetSummary->setCellValue('Y' . $summaryRow, $sphDetail->total_harga);
            $sheetSummary->setCellValue('Z' . $summaryRow, $sphDetail->created_by);
            $sheetSummary->setCellValue('AA' . $summaryRow, $sphDetail->created_by_nama);
            $sheetSummary->setCellValue('AB' . $summaryRow, $sphDetail->signed_by);
            $sheetSummary->setCellValue('AC' . $summaryRow, $sphDetail->signed_at);
            $sheetSummary->setCellValue('AD' . $summaryRow, $sphDetail->perusahaan);
            $summaryRow++;

            if (!empty($sphDetail->items)) {
                foreach ($sphDetail->items as $detailItem) {
                    $dynamicValues = [];
                    if (!empty($detailItem->dynamic_values)) {
                        foreach ($detailItem->dynamic_values as $dv) {
                            $dynamicValues[] = $dv->nama_kolom . ': ' . $dv->nilai . ($dv->hide_on_print ? ' (hide)' : '');
                        }
                    }

                    $sheetItems->setCellValue('A' . $itemRow, $itemNo++);
                    $sheetItems->setCellValue('B' . $itemRow, $sphDetail->id);
                    $sheetItems->setCellValue('C' . $itemRow, $sphDetail->nomor_surat);
                    $sheetItems->setCellValue('D' . $itemRow, $sphDetail->tanggal_surat);
                    $sheetItems->setCellValue('E' . $itemRow, $sphDetail->nama_penerima);
                    $sheetItems->setCellValue('F' . $itemRow, $detailItem->urutan);
                    $sheetItems->setCellValue('G' . $itemRow, $detailItem->id);
                    $sheetItems->setCellValue('H' . $itemRow, $detailItem->pricelist_id);
                    $sheetItems->setCellValue('I' . $itemRow, $detailItem->merk ?? '');
                    $sheetItems->setCellValue('J' . $itemRow, $detailItem->nama_pricelist ?? '');
                    $sheetItems->setCellValue('K' . $itemRow, $detailItem->deskripsi);
                    $sheetItems->setCellValue('L' . $itemRow, $detailItem->jenis_harga);
                    $sheetItems->setCellValue('M' . $itemRow, $detailItem->harga_pricelist);
                    $sheetItems->setCellValue('N' . $itemRow, $detailItem->tipe_diskon);
                    $sheetItems->setCellValue('O' . $itemRow, $detailItem->diskon_persen);
                    $sheetItems->setCellValue('P' . $itemRow, $detailItem->diskon_nominal);
                    $sheetItems->setCellValue('Q' . $itemRow, $detailItem->tampilkan_diskon ? 'Ya' : 'Tidak');
                    $sheetItems->setCellValue('R' . $itemRow, $detailItem->harga_penawaran);
                    $sheetItems->setCellValue('S' . $itemRow, $detailItem->qty);
                    $sheetItems->setCellValue('T' . $itemRow, $detailItem->subtotal);
                    $sheetItems->setCellValue('U' . $itemRow, $detailItem->hide_on_print ? 'Ya' : 'Tidak');
                    $sheetItems->setCellValue('V' . $itemRow, $detailItem->hidden_fields);
                    $sheetItems->setCellValue('W' . $itemRow, implode(' | ', $dynamicValues));
                    $itemRow++;
                }
            }

            $selectedKeterangan = !empty($sphDetail->keterangan['selected']) ? $sphDetail->keterangan['selected'] : [];
            foreach ($selectedKeterangan as $ket) {
                $sheetKeterangan->setCellValue('A' . $keteranganRow, $keteranganNo++);
                $sheetKeterangan->setCellValue('B' . $keteranganRow, $sphDetail->id);
                $sheetKeterangan->setCellValue('C' . $keteranganRow, $sphDetail->nomor_surat);
                $sheetKeterangan->setCellValue('D' . $keteranganRow, $sphDetail->tanggal_surat);
                $sheetKeterangan->setCellValue('E' . $keteranganRow, $sphDetail->nama_penerima);
                $sheetKeterangan->setCellValue('F' . $keteranganRow, 'Master (' . $ket->tipe . ')');
                $sheetKeterangan->setCellValue('G' . $keteranganRow, $ket->urutan);
                $sheetKeterangan->setCellValue('H' . $keteranganRow, $ket->keterangan);
                $keteranganRow++;
            }

            $customKeterangan = !empty($sphDetail->keterangan['custom']) ? $sphDetail->keterangan['custom'] : [];
            foreach ($customKeterangan as $ket) {
                $sheetKeterangan->setCellValue('A' . $keteranganRow, $keteranganNo++);
                $sheetKeterangan->setCellValue('B' . $keteranganRow, $sphDetail->id);
                $sheetKeterangan->setCellValue('C' . $keteranganRow, $sphDetail->nomor_surat);
                $sheetKeterangan->setCellValue('D' . $keteranganRow, $sphDetail->tanggal_surat);
                $sheetKeterangan->setCellValue('E' . $keteranganRow, $sphDetail->nama_penerima);
                $sheetKeterangan->setCellValue('F' . $keteranganRow, 'Custom');
                $sheetKeterangan->setCellValue('G' . $keteranganRow, $ket->urutan);
                $sheetKeterangan->setCellValue('H' . $keteranganRow, $ket->keterangan);
                $keteranganRow++;
            }
        }

        // Format currency
        if ($summaryRow > 2) {
            $sheetSummary->getStyle('Q2:Q' . ($summaryRow - 1))->getNumberFormat()->setFormatCode('#,##0');
            $sheetSummary->getStyle('S2:S' . ($summaryRow - 1))->getNumberFormat()->setFormatCode('#,##0');
            $sheetSummary->getStyle('Y2:Y' . ($summaryRow - 1))->getNumberFormat()->setFormatCode('#,##0');
        }

        if ($itemRow > 2) {
            $sheetItems->getStyle('M2:M' . ($itemRow - 1))->getNumberFormat()->setFormatCode('#,##0');
            $sheetItems->getStyle('P2:P' . ($itemRow - 1))->getNumberFormat()->setFormatCode('#,##0');
            $sheetItems->getStyle('R2:R' . ($itemRow - 1))->getNumberFormat()->setFormatCode('#,##0');
            $sheetItems->getStyle('T2:T' . ($itemRow - 1))->getNumberFormat()->setFormatCode('#,##0');
        }

        // Auto width untuk semua sheet
        foreach (range('A', 'AD') as $col) {
            $sheetSummary->getColumnDimension($col)->setAutoSize(true);
        }
        foreach (range('A', 'W') as $col) {
            $sheetItems->getColumnDimension($col)->setAutoSize(true);
        }
        foreach (range('A', 'H') as $col) {
            $sheetKeterangan->getColumnDimension($col)->setAutoSize(true);
        }

        // Output
        $filename = 'Laporan_SPH_Lengkap_' . $fromDate . '_' . $toDate . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Export Multiple SPH ke 1 PDF
     * Menggabungkan semua SPH dalam rentang tanggal menjadi 1 file PDF
     */
    public function export_pdf_bulk()
    {
        grantAccessFor('all');

        $fromDate = $this->input->get('dari');
        $toDate = $this->input->get('sampai');

        if (!$fromDate || !$toDate) {
            $this->session->set_flashdata('error', 'Tanggal dari dan sampai harus diisi');
            redirect('sph/laporan');
        }

        // Ambil semua SPH dalam rentang tanggal
        $sphList = $this->md_sph->getReport($fromDate, $toDate, perusahaan());

        if (empty($sphList)) {
            $this->session->set_flashdata('error', 'Tidak ada data SPH dalam rentang tanggal yang dipilih');
            redirect('sph/laporan');
        }

        require_once APPPATH . 'vendor/autoload.php';

        $mpdf = $this->createMpdfWithKop();

        $isFirstPage = true;

        foreach ($sphList as $sphData) {
            // Ambil data lengkap SPH (dengan items, keterangan, dll)
            $sph = $this->md_sph->getById($sphData->id);

            if ($sph) {
                // Generate HTML untuk SPH ini
                $data['sph'] = $sph;
                $data['is_pdf'] = true;
                $html = $this->load->view('pages/sph/v_sph_print', $data, true);

                // Tambah halaman baru (kecuali halaman pertama)
                if (!$isFirstPage) {
                    $mpdf->AddPage();
                }
                $isFirstPage = false;

                $mpdf->WriteHTML($html);
            }
        }

        // Generate filename
        $filename = 'SPH_Bulk_' . $fromDate . '_to_' . $toDate . '.pdf';
        $mpdf->Output($filename, 'I'); // 'I' = Inline (tampil di browser)
    }
}
