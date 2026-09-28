<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Model untuk Surat Penawaran Harga (SPH)
 * 
 * Mengelola semua operasi database untuk SPH termasuk:
 * - CRUD SPH
 * - Items produk
 * - Kolom dinamis
 * - Keterangan
 * - Auto numbering
 */
class Md_sph extends CI_Model
{
    protected $table = 'sph_penawaran';
    protected $table_items = 'sph_items';
    protected $table_dynamic_cols = 'sph_dynamic_columns';
    protected $table_dynamic_vals = 'sph_dynamic_values';
    protected $table_keterangan_master = 'sph_keterangan_master';
    protected $table_keterangan_selected = 'sph_keterangan_selected';
    protected $table_keterangan_custom = 'sph_keterangan_custom';

    // ========================================
    // HELPER FUNCTIONS
    // ========================================

    /**
     * Konversi bulan ke romawi
     */
    public function bulanToRomawi($bulan)
    {
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        return isset($romawi[(int)$bulan]) ? $romawi[(int)$bulan] : '';
    }

    /**
     * Generate nomor surat berikutnya
     * Format: NOMOR/SP/CRO/VYM/BULAN_ROMAWI/TAHUN
     */
    public function generateNomorSurat($tanggal = null)
    {
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        $tahun = date('Y', strtotime($tanggal));
        $bulan = date('n', strtotime($tanggal));
        $bulanRomawi = $this->bulanToRomawi($bulan);

        // Strategi 1: Ambil nomor_urut MAX dari kolom nomor_urut
        $this->db->select_max('nomor_urut');
        $this->db->where('YEAR(tanggal_surat)', $tahun);
        $this->db->where('is_visilab', 0);
        $result = $this->db->get($this->table)->row();

        $nomorUrutFromCol = ($result && $result->nomor_urut > 0) ? (int) $result->nomor_urut : 0;

        // Strategi 2: Fallback — parse nomor terbesar dari nomor_surat yang mengandung /SP/CRO/VYM/
        $this->db->select('nomor_surat');
        $this->db->where('YEAR(tanggal_surat)', $tahun);
        $this->db->where('is_visilab', 0);
        $this->db->like('nomor_surat', '/SP/CRO/VYM/');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(50);
        $existingRows = $this->db->get($this->table)->result();

        $nomorUrutFromParse = 0;
        if ($existingRows) {
            foreach ($existingRows as $row) {
                $parts = explode('/', $row->nomor_surat);
                if (!empty($parts[0])) {
                    $parsed = (int) ltrim($parts[0], '0');
                    if ($parsed > $nomorUrutFromParse) {
                        $nomorUrutFromParse = $parsed;
                    }
                }
            }
        }

        // Ambil yang terbesar dari kedua strategi
        $lastNomor = max($nomorUrutFromCol, $nomorUrutFromParse);

        // Tentukan angka awal custom dan tahun targetnya
        $customStart = 73;
        $targetYear  = '2026';

        if ($lastNomor > 0) {
            $nomorUrut = $lastNomor + 1;
        } else {
            // Belum ada surat di tahun ini
            if ($tahun == $targetYear) {
                $nomorUrut = $customStart;
            } else {
                $nomorUrut = 1;
            }
        }

        $nomorFormatted = str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

        $nomorSurat = "{$nomorFormatted}/SP/CRO/VYM/{$bulanRomawi}/{$tahun}";

        return [
            'nomor_surat' => $nomorSurat,
            'nomor_urut' => $nomorUrut,
            'tahun' => $tahun
        ];
    }

    /**
     * Generate nomor surat Visilab berikutnya
     * Format: NOMOR/SP/GA-VL/VYM/BULAN_ROMAWI/TAHUN
     */
    public function generateNomorSuratVisilab($tanggal = null)
    {
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        $tahun = date('Y', strtotime($tanggal));
        $bulan = date('n', strtotime($tanggal));
        $bulanRomawi = $this->bulanToRomawi($bulan);

        // Strategi 1: Ambil nomor_urut MAX dari kolom nomor_urut
        $this->db->select_max('nomor_urut');
        $this->db->where('YEAR(tanggal_surat)', $tahun);
        $this->db->where('is_visilab', 1);
        $result = $this->db->get($this->table)->row();

        $nomorUrutFromCol = ($result && $result->nomor_urut > 0) ? (int) $result->nomor_urut : 0;

        // Strategi 2: Fallback — parse nomor terbesar dari nomor_surat yang mengandung GA-VL
        // Ini menangani kasus dimana nomor_urut tidak tersimpan benar di production
        $this->db->select('nomor_surat');
        $this->db->where('YEAR(tanggal_surat)', $tahun);
        $this->db->where('is_visilab', 1);
        $this->db->like('nomor_surat', '/SP/GA-VL/VYM/');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(50);
        $existingRows = $this->db->get($this->table)->result();

        $nomorUrutFromParse = 0;
        if ($existingRows) {
            foreach ($existingRows as $row) {
                // Parse nomor dari format "035/SP/GA-VL/VYM/III/2026"
                $parts = explode('/', $row->nomor_surat);
                if (!empty($parts[0])) {
                    $parsed = (int) ltrim($parts[0], '0');
                    if ($parsed > $nomorUrutFromParse) {
                        $nomorUrutFromParse = $parsed;
                    }
                }
            }
        }

        // Ambil yang terbesar dari kedua strategi
        $lastNomor = max($nomorUrutFromCol, $nomorUrutFromParse);
        $nomorUrut = $lastNomor + 1;

        $nomorFormatted = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

        $nomorSurat = "{$nomorFormatted}/SP/GA-VL/VYM/{$bulanRomawi}/{$tahun}";

        return [
            'nomor_surat' => $nomorSurat,
            'nomor_urut' => $nomorUrut,
            'tahun' => $tahun
        ];
    }

    /**
     * Format tanggal surat: "Pekanbaru, 28 Mei 2025"
     */
    public function formatTanggalSurat($tanggal, $kota = 'Pekanbaru')
    {
        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $hari = date('j', strtotime($tanggal));
        $bulan = $bulanIndo[date('n', strtotime($tanggal))];
        $tahun = date('Y', strtotime($tanggal));

        return "{$kota}, {$hari} {$bulan} {$tahun}";
    }

    // ========================================
    // SPH CRUD OPERATIONS
    // ========================================

    /**
     * Simpan SPH baru
     */
    public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update SPH
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Hapus SPH (cascade akan menghapus items, dll)
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Ambil SPH by ID dengan semua relasi
     */
    public function getById($id)
    {
        $this->db->select('sp.*, p.nama as created_by_nama');
        $this->db->from($this->table . ' sp');
        $this->db->join('pengguna p', 'sp.created_by = p.pengguna_id', 'left');
        $this->db->where('sp.id', $id);

        $sph = $this->db->get()->row();

        if ($sph) {
            $sph->items = $this->getItems($id);
            $sph->dynamic_columns = $this->getDynamicColumns($id);
            $sph->keterangan = $this->getKeterangan($id);
        }

        return $sph;
    }

    /**
     * Ambil semua SPH dengan pagination untuk datatables
     */
    public function getAll($perusahaan = 1)
    {
        $searchArray = $this->input->post('search', TRUE);
        $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

        $this->db->select('sp.*, p.nama as created_by_nama');
        $this->db->from($this->table . ' sp');
        $this->db->join('pengguna p', 'sp.created_by = p.pengguna_id', 'left');
        $this->db->where('sp.perusahaan', $perusahaan);

        // Search
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('sp.nomor_surat', $keyword);
            $this->db->or_like('sp.nama_penerima', $keyword);
            $this->db->or_like('sp.alamat_penerima', $keyword);
            $this->db->group_end();
        }

        // Filter status
        if ($this->input->post('filter_status')) {
            $this->db->where('sp.status', $this->input->post('filter_status'));
        }

        // Filter tanggal
        // Filter tanggal
        if ($this->input->post('filter_dari') && $this->input->post('filter_sampai')) {
            $this->db->where('sp.tanggal_surat >=', $this->input->post('filter_dari'));
            $this->db->where('sp.tanggal_surat <=', $this->input->post('filter_sampai'));
        }

        // Filter visilab
        if ($this->input->post('filter_visilab') !== '' && $this->input->post('filter_visilab') !== null) {
            $this->db->where('sp.is_visilab', $this->input->post('filter_visilab'));
        }

        // Count total sebelum limit
        $totalQuery = clone $this->db;
        $total = $totalQuery->count_all_results('', false);

        // Order
        $orderColumn = 'sp.id';
        $orderDir = 'DESC';
        if (isset($_POST['order']) && !empty($_POST['order'])) {
            $columnIndex = $_POST['order'][0]['column'];
            $dir = $_POST['order'][0]['dir'];
            $columns = ['sp.id', 'sp.nomor_surat', 'sp.tanggal_surat', 'sp.nama_penerima', 'sp.total_harga', 'sp.status'];
            if (isset($columns[$columnIndex])) {
                $orderColumn = $columns[$columnIndex];
                $orderDir = (strtolower($dir) === 'desc') ? 'DESC' : 'ASC';
            }
        }
        $this->db->order_by($orderColumn, $orderDir);

        // Limit
        if (isset($_POST['length']) && $_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $data = $this->db->get()->result();

        return [
            'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 1,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
    }

    // ========================================
    // ITEMS OPERATIONS
    // ========================================

    /**
     * Simpan item SPH
     */
    public function createItem($data)
    {
        $this->db->insert($this->table_items, $data);
        return $this->db->insert_id();
    }

    /**
     * Batch insert items
     */
    public function createItems($items)
    {
        if (!empty($items)) {
            $this->db->insert_batch($this->table_items, $items);
        }
    }

    /**
     * Update item
     */
    public function updateItem($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table_items, $data);
    }

    /**
     * Hapus item
     */
    public function deleteItem($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_items);
    }

    /**
     * Hapus semua items dari SPH
     */
    public function deleteItemsBySph($sphId)
    {
        $this->db->where('sph_id', $sphId);
        return $this->db->delete($this->table_items);
    }

    /**
     * Ambil items dari SPH
     */
    public function getItems($sphId)
    {
        $this->db->select('si.*, kp.merk, kp.nama as nama_pricelist');
        $this->db->from($this->table_items . ' si');
        $this->db->join('kalkulator_pricelist kp', 'si.pricelist_id = kp.id', 'left');
        $this->db->where('si.sph_id', $sphId);
        $this->db->order_by('si.urutan', 'ASC');

        $items = $this->db->get()->result();

        // Ambil nilai kolom dinamis untuk setiap item
        foreach ($items as &$item) {
            $item->dynamic_values = $this->getDynamicValuesByItem($item->id);
        }

        return $items;
    }

    // ========================================
    // DYNAMIC COLUMNS OPERATIONS
    // ========================================

    /**
     * Simpan kolom dinamis
     */
    public function createDynamicColumn($data)
    {
        $this->db->insert($this->table_dynamic_cols, $data);
        return $this->db->insert_id();
    }

    /**
     * Batch insert dynamic columns
     */
    public function createDynamicColumns($columns)
    {
        if (!empty($columns)) {
            $this->db->insert_batch($this->table_dynamic_cols, $columns);
        }
    }

    /**
     * Hapus semua kolom dinamis dari SPH
     */
    public function deleteDynamicColumnsBySph($sphId)
    {
        $this->db->where('sph_id', $sphId);
        return $this->db->delete($this->table_dynamic_cols);
    }

    /**
     * Ambil kolom dinamis dari SPH
     */
    public function getDynamicColumns($sphId)
    {
        $this->db->where('sph_id', $sphId);
        $this->db->order_by('urutan', 'ASC');
        return $this->db->get($this->table_dynamic_cols)->result();
    }

    /**
     * Simpan nilai kolom dinamis
     */
    public function createDynamicValue($data)
    {
        $this->db->insert($this->table_dynamic_vals, $data);
        return $this->db->insert_id();
    }

    /**
     * Batch insert dynamic values
     */
    public function createDynamicValues($values)
    {
        if (!empty($values)) {
            $this->db->insert_batch($this->table_dynamic_vals, $values);
        }
    }

    /**
     * Ambil nilai dinamis by item
     */
    public function getDynamicValuesByItem($itemId)
    {
        $this->db->select('dv.*, dc.nama_kolom');
        $this->db->from($this->table_dynamic_vals . ' dv');
        $this->db->join($this->table_dynamic_cols . ' dc', 'dv.sph_column_id = dc.id', 'left');
        $this->db->where('dv.sph_item_id', $itemId);
        return $this->db->get()->result();
    }

    // ========================================
    // KETERANGAN MASTER OPERATIONS
    // ========================================

    /**
     * Ambil semua keterangan master yang aktif
     */
    public function getAllKeteranganMaster($perusahaan = 1)
    {
        $this->db->where('is_active', 1);
        $this->db->where('perusahaan', $perusahaan);
        $this->db->order_by('urutan', 'ASC');
        return $this->db->get($this->table_keterangan_master)->result();
    }

    /**
     * Cek apakah keterangan sudah ada (untuk validasi duplikasi)
     * @param string $keterangan
     * @param int $perusahaan
     * @param int|null $excludeId - ID yang dikecualikan (untuk update)
     * @return bool
     */
    public function checkKeteranganExists($keterangan, $perusahaan = 1, $excludeId = null)
    {
        $this->db->where('keterangan', $keterangan);
        $this->db->where('perusahaan', $perusahaan);
        $this->db->where('is_active', 1);

        if ($excludeId) {
            $this->db->where('id !=', $excludeId);
        }

        return $this->db->count_all_results($this->table_keterangan_master) > 0;
    }

    /**
     * Simpan keterangan master baru
     */
    public function createKeteranganMaster($data)
    {
        $this->db->insert($this->table_keterangan_master, $data);
        return $this->db->insert_id();
    }

    /**
     * Update keterangan master
     */
    public function updateKeteranganMaster($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table_keterangan_master, $data);
    }

    /**
     * Hapus (soft delete) keterangan master
     */
    public function deleteKeteranganMaster($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table_keterangan_master, ['is_active' => 0]);
    }

    // ========================================
    // KETERANGAN SELECTED OPERATIONS
    // ========================================

    /**
     * Simpan keterangan yang dipilih untuk SPH
     */
    public function saveKeteranganSelected($sphId, $keteranganIds)
    {
        // Hapus yang lama dulu
        $this->db->where('sph_id', $sphId);
        $this->db->delete($this->table_keterangan_selected);

        // Insert yang baru
        if (!empty($keteranganIds)) {
            $data = [];
            $urutan = 1;
            foreach ($keteranganIds as $ketId) {
                $data[] = [
                    'sph_id' => $sphId,
                    'keterangan_id' => $ketId,
                    'urutan' => $urutan++
                ];
            }
            $this->db->insert_batch($this->table_keterangan_selected, $data);
        }
    }

    /**
     * Ambil keterangan yang dipilih untuk SPH
     */
    public function getKeterangan($sphId)
    {
        // Keterangan dari master
        $this->db->select('ks.*, km.keterangan, km.tipe');
        $this->db->from($this->table_keterangan_selected . ' ks');
        $this->db->join($this->table_keterangan_master . ' km', 'ks.keterangan_id = km.id', 'left');
        $this->db->where('ks.sph_id', $sphId);
        $this->db->order_by('ks.urutan', 'ASC');
        $selected = $this->db->get()->result();

        // Keterangan custom
        $this->db->where('sph_id', $sphId);
        $this->db->order_by('urutan', 'ASC');
        $custom = $this->db->get($this->table_keterangan_custom)->result();

        return [
            'selected' => $selected,
            'custom' => $custom
        ];
    }

    /**
     * Simpan keterangan custom
     */
    public function saveKeteranganCustom($sphId, $keteranganList)
    {
        // Hapus yang lama dulu
        $this->db->where('sph_id', $sphId);
        $this->db->delete($this->table_keterangan_custom);

        // Insert yang baru
        if (!empty($keteranganList)) {
            $data = [];
            $urutan = 100;
            foreach ($keteranganList as $ket) {
                if (!empty(trim($ket))) {
                    $data[] = [
                        'sph_id' => $sphId,
                        'keterangan' => trim($ket),
                        'urutan' => $urutan++
                    ];
                }
            }
            if (!empty($data)) {
                $this->db->insert_batch($this->table_keterangan_custom, $data);
            }
        }
    }

    // ========================================
    // PRICELIST OPERATIONS
    // ========================================

    /**
     * Search pricelist untuk autocomplete
     */
    public function searchPricelist($keyword, $jenis = null, $limit = 20)
    {
        $this->db->select('id, merk, nama, harga, jenis');
        $this->db->from('kalkulator_pricelist');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama', $keyword);
            $this->db->or_like('merk', $keyword);
            $this->db->group_end();
        }

        if ($jenis !== null) {
            $this->db->where('jenis', $jenis);
        }

        $this->db->limit($limit);
        $this->db->order_by('nama', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Ambil pricelist by ID
     */
    public function getPricelistById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('kalkulator_pricelist')->row();
    }

    // ========================================
    // CUSTOMER/CALON CUSTOMER OPERATIONS
    // ========================================

    /**
     * Search pelanggan (customer existing)
     */
    public function searchPelanggan($keyword, $limit = 20)
    {
        $this->db->select('id_pelanggan as id, identitas_pelanggan as nama, alamat, kota, provinsi');
        $this->db->from('pelanggan');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('identitas_pelanggan', $keyword);
            $this->db->or_like('alamat', $keyword);
            $this->db->group_end();
        }

        $this->db->limit($limit);
        $this->db->order_by('identitas_pelanggan', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Search calon pelanggan
     */
    public function searchCalonPelanggan($keyword, $limit = 20)
    {
        $this->db->select('id, namacaloncustomer as nama, alamatcaloncustomer as alamat, kota, provinsi');
        $this->db->from('calonpelanggan');
        $this->db->where('deleted', 0);

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('namacaloncustomer', $keyword);
            $this->db->or_like('alamatcaloncustomer', $keyword);
            $this->db->group_end();
        }

        $this->db->limit($limit);
        $this->db->order_by('namacaloncustomer', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Ambil pelanggan by ID
     */
    public function getPelangganById($id)
    {
        $this->db->select('id_pelanggan as id, identitas_pelanggan as nama, alamat, kota, provinsi');
        $this->db->where('id_pelanggan', $id);
        return $this->db->get('pelanggan')->row();
    }

    /**
     * Ambil calon pelanggan by ID
     */
    public function getCalonPelangganById($id)
    {
        $this->db->select('id, namacaloncustomer as nama, alamatcaloncustomer as alamat, kota, provinsi');
        $this->db->where('id', $id);
        return $this->db->get('calonpelanggan')->row();
    }

    // ========================================
    // SIGNING OPERATIONS
    // ========================================

    /**
     * Tandatangani SPH
     */
    public function signSph($id, $signedBy)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, [
            'status' => 'signed',
            'signed_by' => $signedBy,
            'signed_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Finalize SPH (sebelum TTD)
     */
    public function finalizeSph($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['status' => 'final']);
    }

    /**
     * Revert SPH dari final ke draft untuk revisi
     */
    public function revertToDraft($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['status' => 'draft']);
    }

    /**
     * Batalkan SPH
     */
    public function cancelSph($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['status' => 'cancelled']);
    }

    // ========================================
    // LAPORAN OPERATIONS
    // ========================================

    /**
     * Ambil data untuk laporan berdasarkan rentang tanggal
     */
    public function getReport($fromDate, $toDate, $perusahaan = 1)
    {
        $this->db->select('sp.*, p.nama as created_by_nama');
        $this->db->from($this->table . ' sp');
        $this->db->join('pengguna p', 'sp.created_by = p.pengguna_id', 'left');
        $this->db->where('sp.perusahaan', $perusahaan);
        $this->db->where('sp.tanggal_surat >=', $fromDate);
        $this->db->where('sp.tanggal_surat <=', $toDate);
        $this->db->order_by('sp.tanggal_surat', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Hitung total SPH by status
     */
    public function countByStatus($perusahaan = 1)
    {
        $this->db->select('status, COUNT(*) as jumlah');
        $this->db->where('perusahaan', $perusahaan);
        $this->db->group_by('status');
        $result = $this->db->get($this->table)->result();

        $counts = [
            'draft' => 0,
            'final' => 0,
            'signed' => 0,
            'cancelled' => 0,
            'total' => 0
        ];

        foreach ($result as $row) {
            $counts[$row->status] = (int) $row->jumlah;
            $counts['total'] += (int) $row->jumlah;
        }

        return $counts;
    }

    /**
     * Hitung statistik untuk halaman laporan
     * - Bulan Ini
     * - Tahun Ini
     * - Signed Bulan Ini
     * - Draft (semua)
     */
    public function countForLaporan($perusahaan = 1)
    {
        $bulanIni = date('Y-m');
        $tahunIni = date('Y');

        // Bulan Ini
        $this->db->where('perusahaan', $perusahaan);
        $this->db->like('tanggal_surat', $bulanIni, 'after');
        $bulanIniCount = $this->db->count_all_results($this->table);

        // Tahun Ini
        $this->db->where('perusahaan', $perusahaan);
        $this->db->like('tanggal_surat', $tahunIni, 'after');
        $tahunIniCount = $this->db->count_all_results($this->table);

        // Signed Bulan Ini
        $this->db->where('perusahaan', $perusahaan);
        $this->db->where('status', 'signed');
        $this->db->like('tanggal_surat', $bulanIni, 'after');
        $signedBulanIniCount = $this->db->count_all_results($this->table);

        // Draft (semua)
        $this->db->where('perusahaan', $perusahaan);
        $this->db->where('status', 'draft');
        $draftCount = $this->db->count_all_results($this->table);

        return [
            'bulan_ini' => $bulanIniCount,
            'tahun_ini' => $tahunIniCount,
            'signed_bulan_ini' => $signedBulanIniCount,
            'draft' => $draftCount
        ];
    }

    // ========================================
    // KALKULASI
    // ========================================

    /**
     * Hitung harga penawaran
     */
    public function hitungHargaPenawaran($hargaPricelist, $tipeDiskon, $diskonPersen, $diskonNominal)
    {
        if ($tipeDiskon == 'persen' && $diskonPersen > 0) {
            return $hargaPricelist * (100 - $diskonPersen) / 100;
        } elseif ($tipeDiskon == 'nominal' && $diskonNominal > 0) {
            return $hargaPricelist - $diskonNominal;
        }
        return $hargaPricelist;
    }

    /**
     * Hitung cicilan
     */
    public function hitungCicilan($totalHarga, $dpPersen, $jumlahBulan)
    {
        $dpNominal = $totalHarga * $dpPersen / 100;
        $sisaHarga = $totalHarga - $dpNominal;
        $cicilanPerBulan = $jumlahBulan > 0 ? $sisaHarga / $jumlahBulan : 0;

        return [
            'dp_persen' => $dpPersen,
            'dp_nominal' => $dpNominal,
            'sisa_harga' => $sisaHarga,
            'cicilan_bulan' => $jumlahBulan,
            'cicilan_per_bulan' => $cicilanPerBulan
        ];
    }

    /**
     * Update total harga SPH
     */
    public function updateTotalHarga($sphId)
    {
        $this->db->select_sum('subtotal');
        $this->db->where('sph_id', $sphId);
        $result = $this->db->get($this->table_items)->row();

        $total = $result ? $result->subtotal : 0;

        $this->db->where('id', $sphId);
        return $this->db->update($this->table, ['total_harga' => $total]);
    }
}
