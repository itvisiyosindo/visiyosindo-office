<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_member_visiacademy extends CI_Model
{
    private $katalogTableChecked = false;

    /**
     * Pastikan tabel va_katalog_produk tersedia pada shared DB visiyosi_office.
     */
    private function ensureKatalogTable()
    {
        if ($this->katalogTableChecked) {
            return;
        }
        $this->katalogTableChecked = true;

        if (!$this->db->table_exists('va_katalog_produk')) {
            $sql = "CREATE TABLE IF NOT EXISTS `va_katalog_produk` (
                `id_produk` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `nama_produk` VARCHAR(200) NOT NULL,
                `kategori` VARCHAR(100) NOT NULL DEFAULT 'Radiologi',
                `deskripsi_singkat` TEXT NULL,
                `estimasi_harga` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
                `url_gambar` VARCHAR(255) NULL,
                `url_brosur` VARCHAR(255) NULL,
                `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
                `urutan` INT NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                KEY `idx_status_urutan` (`status`, `urutan`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            $this->db->query($sql);
        }
    }

    public function getAllMember()
    {
        return $this->db->order_by('id_member', 'DESC')->get('va_member')->result_array();
    }

    public function getActiveMembers($includeMemberId = null)
    {
        $this->db->from('va_member');
        if ((int)$includeMemberId > 0) {
            $this->db->group_start()
                     ->where('status', 'aktif')
                     ->or_where('id_member', (int)$includeMemberId)
                     ->group_end();
        } else {
            $this->db->where('status', 'aktif');
        }
        return $this->db->order_by('nama_member', 'ASC')->get()->result_array();
    }

    public function getMemberById($id)
    {
        return $this->db->where('id_member', (int)$id)->get('va_member')->row_array();
    }

    public function getMemberByKode($kode)
    {
        return $this->db->where('kode_member', trim($kode))->get('va_member')->row_array();
    }

    public function generateNextKode()
    {
        $last = $this->db->select('kode_member')
                         ->like('kode_member', 'VA-', 'after')
                         ->order_by('id_member', 'DESC')
                         ->limit(1)
                         ->get('va_member')
                         ->row_array();

        if ($last && preg_match('/VA-(\d+)/', $last['kode_member'], $m)) {
            $nextNum = ((int)$m[1]) + 1;
        } else {
            // Count total rows as fallback
            $count = $this->db->count_all('va_member');
            $nextNum = $count + 1;
        }

        return 'VA-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    public function addMember($data)
    {
        return $this->db->insert('va_member', $data);
    }

    public function updateMember($id, $data)
    {
        return $this->db->where('id_member', (int)$id)->update('va_member', $data);
    }

    public function deleteMember($id)
    {
        return $this->db->where('id_member', (int)$id)->delete('va_member');
    }

    public function getAllDeals()
    {
        $hasPoLink = $this->db->table_exists('po_masuk') && $this->db->field_exists('id_deal_afiliasi', 'po_masuk');
        $select = 'd.*, m.nama_member, m.kode_member, m.institusi_asal, m.no_telp, m.nama_bank, m.no_rekening, m.atas_nama_rekening';
        if ($hasPoLink) {
            $select .= ', po.linked_id_po, po.linked_no_po, po.linked_kode_masuk, po.linked_po_status, po.linked_po_status_proses';
        }

        $this->db->select($select)
                 ->from('va_member_deal d')
                 ->join('va_member m', 'm.id_member = d.id_member', 'left');

        if ($hasPoLink) {
            $poSubquery = "(SELECT pm1.id_deal_afiliasi, pm1.id_po AS linked_id_po, pm1.no_po AS linked_no_po, pm1.kode_masuk AS linked_kode_masuk, pm1.status AS linked_po_status, pm1.status_proses AS linked_po_status_proses FROM po_masuk pm1 INNER JOIN (SELECT id_deal_afiliasi, MAX(id_po) AS max_id_po FROM po_masuk WHERE deleted = 0 AND id_deal_afiliasi IS NOT NULL GROUP BY id_deal_afiliasi) pm2 ON pm1.id_po = pm2.max_id_po)";
            $this->db->join($poSubquery . ' po', 'po.id_deal_afiliasi = d.id_deal', 'left', false);
        }

        return $this->db->order_by('d.id_deal', 'DESC')
                        ->get()->result_array();
    }

    public function getPendingDealsForPo($excludePoId = null, $includeDealId = null)
    {
        $this->db->select('d.id_deal, d.id_member, d.nama_produk, d.estimasi_nilai, d.persen_komisi, d.nominal_komisi, d.status_deal, d.catatan, d.created_at');
        $this->db->from('va_member_deal d');

        if ((int)$includeDealId > 0) {
            $this->db->group_start()
                     ->where('d.status_deal', 'Pending')
                     ->or_where('d.id_deal', (int)$includeDealId)
                     ->group_end();
        } else {
            $this->db->where('d.status_deal', 'Pending');
        }

        if ($this->db->table_exists('po_masuk') && $this->db->field_exists('id_deal_afiliasi', 'po_masuk')) {
            $excludeCond = ((int)$excludePoId > 0) ? ' AND pm.id_po != ' . (int)$excludePoId : '';
            $this->db->where("NOT EXISTS (SELECT 1 FROM po_masuk pm WHERE pm.id_deal_afiliasi = d.id_deal AND pm.deleted = 0{$excludeCond})", null, false);
        }

        return $this->db->order_by('d.id_deal', 'DESC')->get()->result_array();
    }

    public function isDealLinkedToOtherPo($id_deal, $excludePoId = null)
    {
        $id_deal = (int)$id_deal;
        if ($id_deal <= 0 || !$this->db->table_exists('po_masuk') || !$this->db->field_exists('id_deal_afiliasi', 'po_masuk')) {
            return false;
        }

        $this->db->from('po_masuk');
        $this->db->where('id_deal_afiliasi', $id_deal);
        $this->db->where('deleted', 0);
        if ((int)$excludePoId > 0) {
            $this->db->where('id_po !=', (int)$excludePoId);
        }
        return $this->db->count_all_results() > 0;
    }

    public function getDealById($id)
    {
        return $this->db->select('d.*, m.nama_member, m.kode_member, m.institusi_asal, m.no_telp, m.nama_bank, m.no_rekening, m.atas_nama_rekening')
                        ->from('va_member_deal d')
                        ->join('va_member m', 'm.id_member = d.id_member', 'left')
                        ->where('d.id_deal', (int)$id)
                        ->get()->row_array();
    }

    public function getDealByMember($id_member)
    {
        return $this->db->where('id_member', (int)$id_member)->get('va_member_deal')->result_array();
    }

    public function addDeal($data)
    {
        return $this->db->insert('va_member_deal', $data);
    }

    public function updateDealStatus($id, $status)
    {
        return $this->db->where('id_deal', (int)$id)->update('va_member_deal', ['status_deal' => $status]);
    }

    public function updateDeal($id, $data)
    {
        return $this->db->where('id_deal', (int)$id)->update('va_member_deal', $data);
    }

    public function updatePayment($id, $data)
    {
        return $this->db->where('id_deal', (int)$id)->update('va_member_deal', $data);
    }

    public function deleteDeal($id)
    {
        return $this->db->where('id_deal', (int)$id)->delete('va_member_deal');
    }

    // =========================================================================
    // KATALOG PRODUK ALKES (va_katalog_produk)
    // =========================================================================

    public function getAllKatalog()
    {
        $this->ensureKatalogTable();
        return $this->db->order_by('urutan', 'ASC')
                        ->order_by('id_produk', 'DESC')
                        ->get('va_katalog_produk')
                        ->result_array();
    }

    public function getKatalogById($id)
    {
        $this->ensureKatalogTable();
        return $this->db->where('id_produk', (int)$id)
                        ->get('va_katalog_produk')
                        ->row_array();
    }

    public function getKatalogKategoriList()
    {
        $this->ensureKatalogTable();
        $defaults = [
            'Radiologi',
            'Proteksi Radiasi',
            'USG & Imaging',
            'X-Ray & C-Arm',
            'Aksesoris Alkes',
            'Lainnya',
        ];

        $rows = $this->db->distinct()
                         ->select('kategori')
                         ->where('kategori IS NOT NULL', null, false)
                         ->where("TRIM(kategori) !=", '')
                         ->order_by('kategori', 'ASC')
                         ->get('va_katalog_produk')
                         ->result_array();

        $dbCategories = [];
        foreach ($rows as $row) {
            $kat = trim((string)($row['kategori'] ?? ''));
            if ($kat !== '') {
                $dbCategories[] = $kat;
            }
        }

        return array_values(array_unique(array_merge($defaults, $dbCategories)));
    }

    public function addKatalog($data)
    {
        $this->ensureKatalogTable();
        return $this->db->insert('va_katalog_produk', $data);
    }

    public function updateKatalog($id, $data)
    {
        $this->ensureKatalogTable();
        return $this->db->where('id_produk', (int)$id)->update('va_katalog_produk', $data);
    }

    public function deleteKatalog($id)
    {
        $this->ensureKatalogTable();
        return $this->db->where('id_produk', (int)$id)->delete('va_katalog_produk');
    }

    public function getOfficeKatalogSourceCounts()
    {
        $counts = [
            'katalog_office'   => 0,
            'brosur_endoscopy' => 0,
            'brosur_office'    => 0,
            'master_barang'    => 0,
        ];

        if ($this->db->table_exists('price_list')) {
            $counts['katalog_office'] = (int)$this->db->where('status', 1)->where('jenis', 2)->count_all_results('price_list');
            $counts['brosur_endoscopy'] = (int)$this->db->where('status', 1)->where('jenis', 3)->count_all_results('price_list');
        }

        if ($this->db->table_exists('brosur')) {
            $counts['brosur_office'] = (int)$this->db->where('status', 1)->count_all_results('brosur');
        }

        if ($this->db->table_exists('barang')) {
            $counts['master_barang'] = (int)$this->db->where('status', 1)->count_all_results('barang');
        }

        return $counts;
    }

    public function getOfficeKatalogSources($source = 'katalog_office', $search = '', $onlyKeys = null)
    {
        $this->ensureKatalogTable();

        $search = trim((string)$search);
        $filterByKeys = (is_array($onlyKeys) && !empty($onlyKeys));
        $keySet = [];
        $plIds = [];
        $brIds = [];
        $bgIds = [];

        if ($filterByKeys) {
            foreach ($onlyKeys as $k) {
                $k = trim((string)$k);
                if ($k === '') {
                    continue;
                }
                $keySet[$k] = true;
                if (preg_match('/^pl_(\d+)$/', $k, $m)) {
                    $plIds[] = (int)$m[1];
                } elseif (preg_match('/^br_(\d+)$/', $k, $m)) {
                    $brIds[] = (int)$m[1];
                } elseif (preg_match('/^bg_(\d+)$/', $k, $m)) {
                    $bgIds[] = (int)$m[1];
                }
            }
        }

        // Ambil daftar nama produk yang sudah ada di va_katalog_produk untuk deteksi duplikat
        $existingRows = $this->db->select('nama_produk')->get('va_katalog_produk')->result_array();
        $existingNames = [];
        foreach ($existingRows as $er) {
            $nm = mb_strtolower(trim((string)($er['nama_produk'] ?? '')));
            if ($nm !== '') {
                $existingNames[$nm] = true;
            }
        }

        $cleanUrl = function ($raw) {
            $url = trim((string)$raw);
            if ($url === '' || strlen($url) > 255) {
                return null;
            }
            if (!preg_match('#^https?://#i', $url)) {
                return null;
            }
            return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
        };

        $items = [];
        $sourcesToRun = ($source === 'all')
            ? ['katalog_office', 'brosur_endoscopy', 'brosur_office', 'master_barang']
            : [$source];

        // 1 & 2. Tabel Katalog (jenis=2) & Brosur Endoscopy (jenis=3) dari price_list
        if ($this->db->table_exists('price_list')) {
            foreach (['katalog_office' => [2, 'Katalog Office', 'Radiologi'], 'brosur_endoscopy' => [3, 'Brosur Endoscopy', 'Endoscopy']] as $srcKey => $meta) {
                if (!in_array($srcKey, $sourcesToRun, true)) {
                    continue;
                }
                if ($filterByKeys && empty($plIds)) {
                    continue;
                }
                $this->db->select('id_price_list, nama_price_list, kategori, diskon, link_download')
                         ->from('price_list')
                         ->where('status', 1)
                         ->where('jenis', $meta[0]);
                if ($filterByKeys) {
                    $this->db->where_in('id_price_list', $plIds);
                } elseif ($search !== '') {
                    $this->db->group_start()
                             ->like('nama_price_list', $search)
                             ->or_like('kategori', $search)
                             ->or_like('diskon', $search)
                             ->group_end();
                }
                $rows = $this->db->order_by('kategori', 'ASC')
                                 ->order_by('nama_price_list', 'ASC')
                                 ->get()
                                 ->result_array();
                foreach ($rows as $r) {
                    $sKey = 'pl_' . (int)$r['id_price_list'];
                    if ($filterByKeys && !isset($keySet[$sKey])) {
                        continue;
                    }
                    $nama = mb_substr(trim((string)($r['nama_price_list'] ?? '')), 0, 200);
                    if ($nama === '') {
                        continue;
                    }
                    $kat = mb_substr(trim((string)($r['kategori'] ?? '')), 0, 100);
                    if ($kat === '') {
                        $kat = $meta[2];
                    }
                    $diskon = trim((string)($r['diskon'] ?? ''));
                    $desk = ($diskon !== '' && $diskon !== '-') ? ('Info: ' . $diskon) : null;
                    $items[] = [
                        'source_key'        => $sKey,
                        'source_type'       => $srcKey,
                        'source_label'      => $meta[1],
                        'nama_produk'       => $nama,
                        'kategori'          => $kat,
                        'deskripsi_singkat' => $desk,
                        'url_brosur'        => $cleanUrl($r['link_download'] ?? ''),
                        'already_exists'    => isset($existingNames[mb_strtolower($nama)]),
                    ];
                }
            }
        }

        // 3. Brosur Produk dari tabel brosur
        if (in_array('brosur_office', $sourcesToRun, true) && $this->db->table_exists('brosur')) {
            if (!$filterByKeys || !empty($brIds)) {
                $this->db->select('id_brosur, nama_brosur, kategori, link_download')
                         ->from('brosur')
                         ->where('status', 1);
                if ($filterByKeys) {
                    $this->db->where_in('id_brosur', $brIds);
                } elseif ($search !== '') {
                    $this->db->group_start()
                             ->like('nama_brosur', $search)
                             ->or_like('kategori', $search)
                             ->group_end();
                }
                $rows = $this->db->order_by('kategori', 'ASC')
                                 ->order_by('nama_brosur', 'ASC')
                                 ->get()
                                 ->result_array();
                foreach ($rows as $r) {
                    $sKey = 'br_' . (int)$r['id_brosur'];
                    if ($filterByKeys && !isset($keySet[$sKey])) {
                        continue;
                    }
                    $nama = mb_substr(trim((string)($r['nama_brosur'] ?? '')), 0, 200);
                    if ($nama === '') {
                        continue;
                    }
                    $kat = mb_substr(trim((string)($r['kategori'] ?? '')), 0, 100);
                    if ($kat === '') {
                        $kat = 'Radiologi';
                    }
                    $items[] = [
                        'source_key'        => $sKey,
                        'source_type'       => 'brosur_office',
                        'source_label'      => 'Brosur Produk',
                        'nama_produk'       => $nama,
                        'kategori'          => $kat,
                        'deskripsi_singkat' => null,
                        'url_brosur'        => $cleanUrl($r['link_download'] ?? ''),
                        'already_exists'    => isset($existingNames[mb_strtolower($nama)]),
                    ];
                }
            }
        }

        // 4. Master Barang dari tabel barang
        if (in_array('master_barang', $sourcesToRun, true) && $this->db->table_exists('barang')) {
            if (!$filterByKeys || !empty($bgIds)) {
                $hasKatTable = $this->db->table_exists('kategori_barang');
                $this->db->select('b.id_barang, b.nama_barang, b.keterangan, b.tipe, b.akl' . ($hasKatTable ? ', kb.nama_kategori' : ''))
                         ->from('barang b');
                if ($hasKatTable) {
                    $this->db->join('kategori_barang kb', 'kb.id_kategori = b.id_kategori', 'left');
                }
                $this->db->where('b.status', 1);
                if ($filterByKeys) {
                    $this->db->where_in('b.id_barang', $bgIds);
                } elseif ($search !== '') {
                    $this->db->group_start()
                             ->like('b.nama_barang', $search)
                             ->or_like('b.tipe', $search)
                             ->or_like('b.akl', $search);
                    if ($hasKatTable) {
                        $this->db->or_like('kb.nama_kategori', $search);
                    }
                    $this->db->group_end();
                }
                $this->db->order_by('b.nama_barang', 'ASC');
                if (!$filterByKeys) {
                    $this->db->limit(200);
                }
                $rows = $this->db->get()->result_array();
                foreach ($rows as $r) {
                    $sKey = 'bg_' . (int)$r['id_barang'];
                    if ($filterByKeys && !isset($keySet[$sKey])) {
                        continue;
                    }
                    $nama = mb_substr(trim((string)($r['nama_barang'] ?? '')), 0, 200);
                    if ($nama === '') {
                        continue;
                    }
                    $kat = mb_substr(trim((string)($r['nama_kategori'] ?? '')), 0, 100);
                    if ($kat === '') {
                        $kat = 'Lainnya';
                    }
                    $parts = [];
                    $tipe = trim((string)($r['tipe'] ?? ''));
                    $akl  = trim((string)($r['akl'] ?? ''));
                    $ket  = trim((string)($r['keterangan'] ?? ''));
                    if ($tipe !== '' && $tipe !== '-') {
                        $parts[] = 'Tipe: ' . $tipe;
                    }
                    if ($akl !== '' && $akl !== '-') {
                        $parts[] = 'AKL: ' . $akl;
                    }
                    if ($ket !== '' && $ket !== '-') {
                        $parts[] = $ket;
                    }
                    $desk = !empty($parts) ? mb_substr(implode(' | ', $parts), 0, 300) : null;
                    $items[] = [
                        'source_key'        => $sKey,
                        'source_type'       => 'master_barang',
                        'source_label'      => 'Master Barang',
                        'nama_produk'       => $nama,
                        'kategori'          => $kat,
                        'deskripsi_singkat' => $desk,
                        'url_brosur'        => null,
                        'already_exists'    => isset($existingNames[mb_strtolower($nama)]),
                    ];
                }
            }
        }

        return $items;
    }

    public function importKatalogFromOffice($items, $defaultStatus = 'aktif')
    {
        $this->ensureKatalogTable();

        $status = in_array($defaultStatus, ['aktif', 'nonaktif'], true) ? $defaultStatus : 'aktif';

        $existingRows = $this->db->select('nama_produk')->get('va_katalog_produk')->result_array();
        $existingNames = [];
        foreach ($existingRows as $er) {
            $nm = mb_strtolower(trim((string)($er['nama_produk'] ?? '')));
            if ($nm !== '') {
                $existingNames[$nm] = true;
            }
        }

        $maxRow = $this->db->select_max('urutan')->get('va_katalog_produk')->row_array();
        $nextUrutan = (int)($maxRow['urutan'] ?? 0);

        $inserted = 0;
        $skipped  = 0;

        foreach ($items as $item) {
            $nama = mb_substr(trim((string)($item['nama_produk'] ?? '')), 0, 200);
            if ($nama === '') {
                $skipped++;
                continue;
            }
            $normKey = mb_strtolower($nama);
            if (isset($existingNames[$normKey])) {
                $skipped++;
                continue;
            }

            $kat = mb_substr(trim((string)($item['kategori'] ?? '')), 0, 100);
            if ($kat === '') {
                $kat = 'Radiologi';
            }

            $deskRaw = trim((string)($item['deskripsi_singkat'] ?? ''));
            $desk = ($deskRaw !== '') ? $deskRaw : null;

            $brosurRaw = trim((string)($item['url_brosur'] ?? ''));
            $brosur = ($brosurRaw !== '' && strlen($brosurRaw) <= 255) ? $brosurRaw : null;

            $nextUrutan++;
            $ok = $this->db->insert('va_katalog_produk', [
                'nama_produk'       => $nama,
                'kategori'          => $kat,
                'deskripsi_singkat' => $desk,
                'estimasi_harga'    => 0,
                'url_gambar'        => null,
                'url_brosur'        => $brosur,
                'status'            => $status,
                'urutan'            => $nextUrutan,
            ]);

            if ($ok) {
                $existingNames[$normKey] = true;
                $inserted++;
            } else {
                $skipped++;
            }
        }

        return [
            'inserted' => $inserted,
            'skipped'  => $skipped,
        ];
    }

    public function getSummary()
    {
        $totalMember = $this->db->count_all('va_member');
        
        $dealStats = $this->db->select("
            COUNT(id_deal) as total_deal,
            SUM(CASE WHEN status_deal = 'Deal' THEN estimasi_nilai ELSE 0 END) as total_nilai_deal,
            SUM(CASE WHEN status_deal = 'Deal' THEN nominal_komisi ELSE 0 END) as total_komisi_deal,
            SUM(CASE WHEN status_deal = 'Pending' THEN nominal_komisi ELSE 0 END) as total_komisi_pending,
            SUM(CASE WHEN status_deal = 'Deal' AND status_bayar = 'Sudah Ditransfer' THEN nominal_komisi ELSE 0 END) as total_komisi_terbayar,
            SUM(CASE WHEN status_deal = 'Deal' AND (status_bayar = 'Belum Ditransfer' OR status_bayar IS NULL) THEN nominal_komisi ELSE 0 END) as total_komisi_siap_bayar
        ")->get('va_member_deal')->row_array();

        return [
            'total_member'              => (int)$totalMember,
            'total_deal'                => (int)($dealStats['total_deal'] ?? 0),
            'total_nilai_deal'          => (float)($dealStats['total_nilai_deal'] ?? 0),
            'total_komisi_deal'         => (float)($dealStats['total_komisi_deal'] ?? 0),
            'total_komisi_pending'      => (float)($dealStats['total_komisi_pending'] ?? 0),
            'total_komisi_terbayar'     => (float)($dealStats['total_komisi_terbayar'] ?? 0),
            'total_komisi_siap_bayar'   => (float)($dealStats['total_komisi_siap_bayar'] ?? 0),
        ];
    }
}
