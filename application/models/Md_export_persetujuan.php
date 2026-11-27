<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_export_persetujuan extends CI_Model
{
    private $_penggunaMap = null;
    private $_suratStatusMap = null;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('mata_uang_helper');
    }

    /**
     * Daftar seluruh jenis persetujuan yang didukung untuk Export Excel
     */
    public function getJenisList()
    {
        return [
            // Keuangan & Operasional
            'pbok' => ['label' => 'PBOK', 'full' => 'PBOK (Biaya Operasional Kantor)', 'group' => 'Keuangan & Operasional', 'sheet' => 'PBOK'],
            'ppa' => ['label' => 'PPA', 'full' => 'PPA (Pembelian & Pemeliharaan Aset)', 'group' => 'Keuangan & Operasional', 'sheet' => 'PPA'],
            'pb' => ['label' => 'Pembiayaan Dinas', 'full' => 'Pembiayaan Dinas', 'group' => 'Keuangan & Operasional', 'sheet' => 'Pembiayaan Dinas'],
            'spp_bayar' => ['label' => 'Permintaan Pembayaran', 'full' => 'Permintaan Pembayaran (SPP)', 'group' => 'Keuangan & Operasional', 'sheet' => 'Permintaan Pembayaran'],
            'pkk' => ['label' => 'Klaim Kas', 'full' => 'Klaim Kas', 'group' => 'Keuangan & Operasional', 'sheet' => 'Klaim Kas'],
            'pkketoll' => ['label' => 'Klaim Kas E-Toll', 'full' => 'Klaim Kas E-Toll', 'group' => 'Keuangan & Operasional', 'sheet' => 'Klaim Kas E-Toll'],
            'gc' => ['label' => 'Limit Grab', 'full' => 'Limit Grab', 'group' => 'Keuangan & Operasional', 'sheet' => 'Limit Grab'],
            'lta' => ['label' => 'Pengajuan LTA', 'full' => 'Pengajuan LTA', 'group' => 'Keuangan & Operasional', 'sheet' => 'Pengajuan LTA'],

            // Ekspedisi & Pengiriman
            'tagihan_ekspedisi' => ['label' => 'Tagihan Ekspedisi', 'full' => 'Tagihan Ekspedisi', 'group' => 'Ekspedisi & Pengiriman', 'sheet' => 'Tagihan Ekspedisi'],
            'spp_ekspedisi' => ['label' => 'SPP Ekspedisi', 'full' => 'SPP Ekspedisi', 'group' => 'Ekspedisi & Pengiriman', 'sheet' => 'SPP Ekspedisi'],
            'appeks' => ['label' => 'Approval Ekspedisi', 'full' => 'Approval Ekspedisi', 'group' => 'Ekspedisi & Pengiriman', 'sheet' => 'Approval Ekspedisi'],
            'forwarder' => ['label' => 'Approval Forwarder', 'full' => 'Approval Forwarder', 'group' => 'Ekspedisi & Pengiriman', 'sheet' => 'Approval Forwarder'],

            // Penjualan, Pajak & Manajemen
            'approval_harga' => ['label' => 'Approval Harga', 'full' => 'Approval Harga', 'group' => 'Penjualan, Pajak & Manajemen', 'sheet' => 'Approval Harga'],
            'approval_po' => ['label' => 'Approval PO', 'full' => 'Approval PO', 'group' => 'Penjualan, Pajak & Manajemen', 'sheet' => 'Approval PO'],
            'app_pajak' => ['label' => 'Approval Faktur Pajak', 'full' => 'Approval Faktur Pajak', 'group' => 'Penjualan, Pajak & Manajemen', 'sheet' => 'Approval Faktur Pajak'],
            'appdir' => ['label' => 'Approval Director', 'full' => 'Approval Director', 'group' => 'Penjualan, Pajak & Manajemen', 'sheet' => 'Approval Director'],

            // Operasional, Dinas & HR
            'kg' => ['label' => 'Kunjungan Gudang', 'full' => 'Kunjungan Gudang', 'group' => 'Operasional, Dinas & HR', 'sheet' => 'Kunjungan Gudang'],
            'pd' => ['label' => 'Permintaan Dinas', 'full' => 'Permintaan Dinas (Mkt / Teknisi / Karyawan)', 'group' => 'Operasional, Dinas & HR', 'sheet' => 'Permintaan Dinas'],
            'sd' => ['label' => 'Surat Dinas', 'full' => 'Surat Dinas', 'group' => 'Operasional, Dinas & HR', 'sheet' => 'Surat Dinas'],
            'kendaraan' => ['label' => 'Penggunaan Kendaraan', 'full' => 'Penggunaan Kendaraan', 'group' => 'Operasional, Dinas & HR', 'sheet' => 'Penggunaan Kendaraan'],
            'meetingroom' => ['label' => 'Ruang Meeting', 'full' => 'Ruang Meeting', 'group' => 'Operasional, Dinas & HR', 'sheet' => 'Ruang Meeting'],
            'izin_cuti' => ['label' => 'Izin & Cuti', 'full' => 'Izin Jam Kerja, Meninggalkan & Cuti', 'group' => 'Operasional, Dinas & HR', 'sheet' => 'Izin & Cuti'],
        ];
    }

    /**
     * Ambil seluruh data persetujuan sesuai filter
     * $filters: jenis (array), tahun, bulan, tgl_awal, tgl_akhir, status (all|disetujui|proses|ditolak)
     */
    public function getData($filters = [])
    {
        $allJenis = array_keys($this->getJenisList());
        $selectedJenis = !empty($filters['jenis']) && is_array($filters['jenis'])
            ? array_values(array_intersect($filters['jenis'], $allJenis))
            : $allJenis;

        if (empty($selectedJenis)) {
            $selectedJenis = $allJenis;
        }

        // Preload map pengguna sekali saja (menghindari ribuan JOIN unindexed)
        $this->_loadPenggunaMap();

        // Preload surat_list hanya jika jenis yg diminta menggunakan surat_list
        $suratListJenis = ['pbok', 'ppa', 'pb', 'spp_bayar', 'pkk', 'pkketoll', 'gc', 'approval_harga', 'kg', 'pd', 'sd'];
        if (!empty(array_intersect($selectedJenis, $suratListJenis))) {
            $this->_loadSuratStatusMap();
        }

        $statusFilter = isset($filters['status']) ? strtolower(trim((string)$filters['status'])) : 'all';
        $rows = [];

        foreach ($selectedJenis as $jenis) {
            $method = '_fetch_' . $jenis;
            if (!method_exists($this, $method)) {
                continue;
            }
            $items = $this->$method($filters);
            foreach ($items as $item) {
                if ($statusFilter !== '' && $statusFilter !== 'all' && $item['status_group'] !== $statusFilter) {
                    continue;
                }
                $rows[] = $item;
            }
        }

        usort($rows, function ($a, $b) {
            $cmp = strcmp((string)$b['tanggal'], (string)$a['tanggal']);
            if ($cmp !== 0) {
                return $cmp;
            }
            return strcmp((string)$b['no_surat'], (string)$a['no_surat']);
        });

        return $rows;
    }

    private function _loadPenggunaMap()
    {
        if ($this->_penggunaMap !== null) {
            return;
        }
        $this->_penggunaMap = [];
        $res = $this->db->query("SELECT pengguna_id, nama FROM pengguna")->result();
        foreach ($res as $u) {
            $this->_penggunaMap[(int)$u->pengguna_id] = trim((string)$u->nama);
        }
    }

    private function _getNamaPengguna($id)
    {
        $k = (int)$id;
        return isset($this->_penggunaMap[$k]) ? $this->_penggunaMap[$k] : '';
    }

    /**
     * Load surat_list sekali saja dan otomatis deduplikasi jika ada (id_kat_surat, id_srt) kembar
     */
    private function _loadSuratStatusMap()
    {
        if ($this->_suratStatusMap !== null) {
            return;
        }
        $this->_suratStatusMap = [];
        $res = $this->db->query("SELECT id_kat_surat, id_srt, status FROM surat_list WHERE id_kat_surat IN (1,2,3,4,5,6,7,8,9,10,11,12,20) ORDER BY id_list_surat ASC")->result();
        foreach ($res as $s) {
            $this->_suratStatusMap[(int)$s->id_kat_surat][(int)$s->id_srt] = (int)$s->status;
        }
    }

    private function _getSuratStatus($katId, $srtId)
    {
        $k = (int)$katId;
        $i = (int)$srtId;
        return isset($this->_suratStatusMap[$k][$i]) ? $this->_suratStatusMap[$k][$i] : 0;
    }

    /**
     * Fetch detail rows dalam batch O(N+M) tanpa JOIN unindexed
     */
    private function _fetchDetailsGrouped($table, $fkCol, $ids, $selectCols = '*', $extraWhere = '', $orderBy = '')
    {
        if (empty($ids)) {
            return [];
        }
        $cleanIds = array_values(array_unique(array_map('intval', $ids)));
        $grouped = [];
        // Chunk IN() agar tetap ringan walau ribuan ID
        foreach (array_chunk($cleanIds, 1000) as $chunk) {
            $sql = "SELECT $selectCols FROM `$table` WHERE `$fkCol` IN (" . implode(',', $chunk) . ")";
            if ($extraWhere !== '') {
                $sql .= " AND ($extraWhere)";
            }
            if ($orderBy !== '') {
                $sql .= " ORDER BY $orderBy";
            }
            $rows = $this->db->query($sql)->result();
            foreach ($rows as $row) {
                $grouped[(int)$row->$fkCol][] = $row;
            }
        }
        return $grouped;
    }

    /**
     * Bangun kondisi tanggal yang SARGable untuk kolom date/datetime
     */
    private function _buildDateWhere($colExpr, $filters, $isDatetime = false)
    {
        $tglAwal = !empty($filters['tgl_awal']) ? trim((string)$filters['tgl_awal']) : '';
        $tglAkhir = !empty($filters['tgl_akhir']) ? trim((string)$filters['tgl_akhir']) : '';
        $tahun = !empty($filters['tahun']) ? (int)$filters['tahun'] : 0;
        $bulan = !empty($filters['bulan']) ? (int)$filters['bulan'] : 0;

        $start = '';
        $endExclusive = '';

        if ($tglAwal !== '' || $tglAkhir !== '') {
            if ($tglAwal !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglAwal)) {
                $start = $tglAwal;
            }
            if ($tglAkhir !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglAkhir)) {
                $endExclusive = date('Y-m-d', strtotime($tglAkhir . ' +1 day'));
            }
        } elseif ($tahun > 0) {
            if ($bulan >= 1 && $bulan <= 12) {
                $start = sprintf('%04d-%02d-01', $tahun, $bulan);
                $endExclusive = date('Y-m-d', strtotime($start . ' +1 month'));
            } else {
                $start = sprintf('%04d-01-01', $tahun);
                $endExclusive = sprintf('%04d-01-01', $tahun + 1);
            }
        } elseif ($bulan >= 1 && $bulan <= 12) {
            return "MONTH($colExpr) = " . $bulan;
        }

        $conds = [];
        if ($start !== '') {
            $val = $isDatetime ? ($start . ' 00:00:00') : $start;
            $conds[] = "$colExpr >= " . $this->db->escape($val);
        }
        if ($endExclusive !== '') {
            $val = $isDatetime ? ($endExclusive . ' 00:00:00') : $endExclusive;
            $conds[] = "$colExpr < " . $this->db->escape($val);
        }

        return empty($conds) ? '1=1' : implode(' AND ', $conds);
    }

    private function _cleanNum($val)
    {
        if ($val === null || $val === '') {
            return 0.0;
        }
        if (is_numeric($val)) {
            return (float)$val;
        }
        $s = preg_replace('/[^0-9.,-]/', '', (string)$val);
        if ($s === '' || $s === '-') {
            return 0.0;
        }
        if (strpos($s, ',') !== false && strpos($s, '.') !== false) {
            if (strrpos($s, ',') > strrpos($s, '.')) {
                $s = str_replace('.', '', $s);
                $s = str_replace(',', '.', $s);
            } else {
                $s = str_replace(',', '', $s);
            }
        } elseif (substr_count($s, '.') > 1) {
            $s = str_replace('.', '', $s);
        } elseif (substr_count($s, ',') > 1) {
            $s = str_replace(',', '', $s);
        } elseif (strpos($s, ',') !== false) {
            $parts = explode(',', $s);
            $s = (strlen(end($parts)) === 3) ? str_replace(',', '', $s) : str_replace(',', '.', $s);
        } elseif (strpos($s, '.') !== false) {
            $parts = explode('.', $s);
            if (strlen(end($parts)) === 3 && strlen($parts[0]) <= 3) {
                $s = str_replace('.', '', $s);
            }
        }
        return is_numeric($s) ? (float)$s : 0.0;
    }

    private function _normDate($dt)
    {
        if (empty($dt) || substr((string)$dt, 0, 10) === '0000-00-00' || substr((string)$dt, 0, 10) === '1970-01-01') {
            return '';
        }
        $ts = strtotime((string)$dt);
        return $ts ? date('Y-m-d', $ts) : '';
    }

    private function _row($jenisKey, $jenisLabel, $tgl, $noSurat, $pengaju, $namaTransaksi, $ditransaksikan, $qty, $mataUang, $nominal, $statusText, $statusGroup, $linkPath = '')
    {
        $normTgl = $this->_normDate($tgl);
        return [
            'jenis_key' => $jenisKey,
            'jenis_label' => $jenisLabel,
            'tanggal' => $normTgl,
            'tahun' => $normTgl !== '' ? (int)substr($normTgl, 0, 4) : '',
            'no_surat' => trim((string)$noSurat),
            'pengaju' => trim((string)$pengaju),
            'nama_transaksi' => trim((string)$namaTransaksi),
            'ditransaksikan' => trim((string)$ditransaksikan),
            'qty' => $qty,
            'mata_uang' => $mataUang ?: 'Rp',
            'nominal' => (float)$nominal,
            'status' => $statusText,
            'status_group' => $statusGroup,
            'link' => $linkPath !== '' ? rtrim(base_url(), '/') . '/' . ltrim($linkPath, '/') : '',
        ];
    }

    private function _cleanParts($parts, $sep = ' - ')
    {
        $out = [];
        foreach ($parts as $p) {
            $s = trim((string)$p);
            if ($s !== '' && $s !== '-') {
                $out[] = $s;
            }
        }
        return implode($sep, $out);
    }

    // 1. PBOK
    private function _fetch_pbok($filters)
    {
        $dateExpr = "COALESCE(NULLIF(h.tgl_pengajuan, '0000-00-00'), DATE(h.created_at))";
        $whereDate = $this->_buildDateWhere($dateExpr, $filters);

        $headers = $this->db->query("
            SELECT h.id_pbok, h.kode_pbok, $dateExpr AS tgl, h.id_pengguna, h.alamat, h.bank, h.ats_nama,
                   h.mata_uang AS header_mu, h.ttd_2, h.ttd_3
            FROM surat_pbok h
            WHERE $whereDate
            ORDER BY tgl DESC, h.id_pbok DESC
        ")->result();

        if (empty($headers)) return [];

        $ids = array_map(function ($x) { return (int)$x->id_pbok; }, $headers);
        $detailsMap = $this->_fetchDetailsGrouped(
            'surat_pbok_detail', 'id_pbok', $ids,
            'id, id_pbok, keterangan, qty, estimasi, total, mata_uang, catatan', '', 'id ASC'
        );

        $out = [];
        foreach ($headers as $r) {
            $id = (int)$r->id_pbok;
            $stat = $this->_getSuratStatus(4, $id);
            if ($stat === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ($stat === 1) {
                if ((string)$r->ttd_3 === '1') {
                    $st = 'Disetujui';
                    $sg = 'disetujui';
                } elseif ((string)$r->ttd_2 === '1') {
                    $st = 'Disetujui General Manager';
                    $sg = 'proses';
                } else {
                    $st = 'Disetujui Finance';
                    $sg = 'proses';
                }
            } else {
                $st = 'Ditolak';
                $sg = 'ditolak';
            }

            $pengaju = $this->_getNamaPengguna($r->id_pengguna);
            $pihak = $this->_cleanParts([$r->ats_nama, $r->bank, $r->alamat], ' / ');
            $namaTrx = 'PBOK' . ($pihak !== '' ? ' - ' . $pihak : '');
            $link = 'surat/show/detail_surat/pbok/' . $id . '/1';
            $items = isset($detailsMap[$id]) ? $detailsMap[$id] : [];

            $emitted = 0;
            foreach ($items as $d) {
                $ket = trim((string)$d->keterangan);
                $nom = $this->_cleanNum($d->total);
                if ($nom == 0 && $this->_cleanNum($d->estimasi) > 0) {
                    $nom = $this->_cleanNum($d->estimasi) * max(1, (int)$d->qty);
                }
                if ($ket === '' && $nom == 0) {
                    continue;
                }
                $itemTxt = $this->_cleanParts([$ket, !empty($d->catatan) ? '(Catatan: ' . $d->catatan . ')' : ''], ' ');
                $muCode = !empty($d->mata_uang) ? $d->mata_uang : (!empty($r->header_mu) ? $r->header_mu : 1);
                $out[] = $this->_row(
                    'pbok', 'PBOK', $r->tgl, $r->kode_pbok, $pengaju,
                    $namaTrx, $itemTxt ?: '-', (int)$d->qty ?: 1, mata_uang($muCode), $nom,
                    $st, $sg, $link
                );
                $emitted++;
            }
            if ($emitted === 0) {
                $out[] = $this->_row(
                    'pbok', 'PBOK', $r->tgl, $r->kode_pbok, $pengaju,
                    $namaTrx, '-', 1, mata_uang($r->header_mu ?: 1), 0,
                    $st, $sg, $link
                );
            }
        }
        return $out;
    }

    // 2. PPA
    private function _fetch_ppa($filters)
    {
        $whereDate = $this->_buildDateWhere('h.tgl_pengajuan', $filters);
        $headers = $this->db->query("
            SELECT h.id_ppa, h.kode_ppa, h.tgl_pengajuan AS tgl, h.id_pengguna, h.alamat, h.bank, h.ats_nama,
                   h.catatan, h.ttd_2, h.ttd_3, h.ttd_4
            FROM surat_ppa h
            WHERE $whereDate
            ORDER BY h.tgl_pengajuan DESC, h.id_ppa DESC
        ")->result();

        if (empty($headers)) return [];

        $ids = array_map(function ($x) { return (int)$x->id_ppa; }, $headers);
        $detailsMap = $this->_fetchDetailsGrouped(
            'surat_ppa_detail', 'id_ppa', $ids,
            'id, id_ppa, keterangan, qty, estimasi, total', '', 'id ASC'
        );

        $out = [];
        foreach ($headers as $r) {
            $id = (int)$r->id_ppa;
            $stat = $this->_getSuratStatus(7, $id);
            if ($stat === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ($stat === 1) {
                if ((string)$r->ttd_4 === '1') {
                    $st = 'Disetujui';
                    $sg = 'disetujui';
                } elseif ((string)$r->ttd_3 === '1') {
                    $st = 'Disetujui Director of Corp Planning';
                    $sg = 'proses';
                } elseif ((string)$r->ttd_2 === '1') {
                    $st = 'Disetujui General Manager';
                    $sg = 'proses';
                } else {
                    $st = 'Disetujui Staff Finance';
                    $sg = 'proses';
                }
            } else {
                $st = 'Ditolak';
                $sg = 'ditolak';
            }

            $pengaju = $this->_getNamaPengguna($r->id_pengguna);
            $pihak = $this->_cleanParts([$r->alamat, $r->ats_nama, $r->bank], ' / ');
            $namaTrx = 'PPA' . ($pihak !== '' ? ' - ' . $pihak : '');
            $link = 'surat/show/detail_surat/ppa/' . $id . '/1';
            $items = isset($detailsMap[$id]) ? $detailsMap[$id] : [];

            $emitted = 0;
            foreach ($items as $d) {
                $ket = trim((string)$d->keterangan);
                $nom = $this->_cleanNum($d->total);
                if ($nom == 0 && $this->_cleanNum($d->estimasi) > 0) {
                    $nom = $this->_cleanNum($d->estimasi) * max(1, (int)$d->qty);
                }
                if ($ket === '' && $nom == 0) {
                    continue;
                }
                $itemTxt = $this->_cleanParts([$ket, !empty($r->catatan) ? '(Catatan: ' . $r->catatan . ')' : ''], ' ');
                $out[] = $this->_row(
                    'ppa', 'PPA', $r->tgl, $r->kode_ppa, $pengaju,
                    $namaTrx, $itemTxt ?: '-', (int)$d->qty ?: 1, 'Rp', $nom,
                    $st, $sg, $link
                );
                $emitted++;
            }
            if ($emitted === 0) {
                $out[] = $this->_row(
                    'ppa', 'PPA', $r->tgl, $r->kode_ppa, $pengaju,
                    $namaTrx, $r->catatan ?: '-', 1, 'Rp', 0,
                    $st, $sg, $link
                );
            }
        }
        return $out;
    }

    // 3. Pembiayaan Dinas
    private function _fetch_pb($filters)
    {
        $whereDate = $this->_buildDateWhere('h.tgl_pengajuan', $filters, true);
        $headers = $this->db->query("
            SELECT h.id_pb, h.kode_pb, h.tgl_pengajuan AS tgl, h.id_pengguna, h.kota, h.keperluan,
                   h.pengajuan_ttd_2, h.pengajuan_ttd_3, h.laporan_ttd_2, h.laporan_ttd_3, h.persetujuan_ga
            FROM surat_biaya_dinas h
            WHERE $whereDate
            ORDER BY h.tgl_pengajuan DESC, h.id_pb DESC
        ")->result();

        if (empty($headers)) return [];

        $ids = array_map(function ($x) { return (int)$x->id_pb; }, $headers);
        $detailsMap = $this->_fetchDetailsGrouped(
            'surat_detail_biaya_dinas', 'id_pb', $ids,
            'id_detail_pb, id_pb, kode_laporan, keterangan, nominal', 'kode_laporan IN (1, 2)', 'id_detail_pb ASC'
        );

        $out = [];
        foreach ($headers as $r) {
            $id = (int)$r->id_pb;
            $stat = $this->_getSuratStatus(1, $id);
            if ($stat === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ($stat === 1) {
                if ((string)$r->pengajuan_ttd_3 === '1') {
                    $st = 'Pengajuan Disetujui';
                    $sg = 'disetujui';
                } elseif ((string)$r->pengajuan_ttd_2 === '1') {
                    $st = 'Disetujui General Manager';
                    $sg = 'proses';
                } else {
                    $st = 'Disetujui Finance';
                    $sg = 'proses';
                }
            } elseif ($stat === 2) {
                if ((int)$r->persetujuan_ga === 1) {
                    $st = 'Laporan Disetujui GA';
                    $sg = 'proses';
                } elseif ((int)$r->persetujuan_ga === 2) {
                    $st = 'Laporan Ditolak GA';
                    $sg = 'ditolak';
                } else {
                    $st = 'Laporan Diajukan';
                    $sg = 'proses';
                }
            } elseif ($stat === 3) {
                if ((string)$r->laporan_ttd_3 === '1') {
                    $st = 'Laporan Disetujui';
                    $sg = 'disetujui';
                } elseif ((string)$r->laporan_ttd_2 === '1') {
                    $st = 'Laporan Disetujui GM';
                    $sg = 'proses';
                } else {
                    $st = 'Laporan Disetujui Finance';
                    $sg = 'proses';
                }
            } else {
                $st = 'Ditolak';
                $sg = 'ditolak';
            }

            $pengaju = $this->_getNamaPengguna($r->id_pengguna);
            $namaTrx = 'Pembiayaan Dinas - ' . $this->_cleanParts([$r->kota, $r->keperluan], ': ');
            $link = 'surat/show/detail_surat/PB/' . $id . '/1';

            $rawItems = isset($detailsMap[$id]) ? $detailsMap[$id] : [];
            $byKode = [1 => [], 2 => []];
            foreach ($rawItems as $d) {
                $byKode[(int)$d->kode_laporan][] = $d;
            }
            // Gunakan realisasi (kode_laporan=2) jika sudah dilaporkan agar tidak dobel hitung estimasi (1)
            $useKode = !empty($byKode[2]) ? 2 : 1;
            $tag = ($useKode === 2) ? '[Realisasi] ' : '[Pengajuan] ';
            $items = $byKode[$useKode];

            $emitted = 0;
            foreach ($items as $d) {
                $ket = trim((string)$d->keterangan);
                $nom = $this->_cleanNum($d->nominal);
                if ($ket === '' && $nom == 0) continue;
                $out[] = $this->_row(
                    'pb', 'Pembiayaan Dinas', $r->tgl, $r->kode_pb, $pengaju,
                    $namaTrx, $tag . ($ket ?: '-'), 1, 'Rp', $nom,
                    $st, $sg, $link
                );
                $emitted++;
            }
            if ($emitted === 0) {
                $out[] = $this->_row(
                    'pb', 'Pembiayaan Dinas', $r->tgl, $r->kode_pb, $pengaju,
                    $namaTrx, $r->keperluan ?: '-', 1, 'Rp', 0,
                    $st, $sg, $link
                );
            }
        }
        return $out;
    }

    // 4. Permintaan Pembayaran (SPP Surat)
    private function _fetch_spp_bayar($filters)
    {
        $whereDate = $this->_buildDateWhere('h.tgl_pengajuan', $filters, true);
        $headers = $this->db->query("
            SELECT h.id, h.kode, h.tgl_pengajuan AS tgl, h.id_pengaju, h.rek_bank, h.rek_nama,
                   h.kode_mata_uang, h.ttd2, h.ttd3
            FROM surat_permintaan_pembayaran h
            WHERE $whereDate
            ORDER BY h.tgl_pengajuan DESC, h.id DESC
        ")->result();

        if (empty($headers)) return [];

        $ids = array_map(function ($x) { return (int)$x->id; }, $headers);
        $detailsMap = $this->_fetchDetailsGrouped(
            'surat_permintaan_pembayaran_detail', 'id_srt', $ids,
            'id, id_srt, keterangan, nominal, mata_uang', '', 'id ASC'
        );

        $out = [];
        foreach ($headers as $r) {
            $id = (int)$r->id;
            $stat = $this->_getSuratStatus(2, $id);
            if ($stat === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ($stat === 1) {
                if ((string)$r->ttd3 === '1') {
                    $st = 'Disetujui';
                    $sg = 'disetujui';
                } elseif ((string)$r->ttd2 === '1') {
                    $st = 'Disetujui Director of Corp Planning';
                    $sg = 'proses';
                } else {
                    $st = 'Disetujui General Manager';
                    $sg = 'proses';
                }
            } else {
                $st = 'Ditolak';
                $sg = 'ditolak';
            }

            $pengaju = $this->_getNamaPengguna($r->id_pengaju);
            $pihak = $this->_cleanParts([$r->rek_nama, $r->rek_bank], ' / ');
            $namaTrx = 'Permintaan Pembayaran' . ($pihak !== '' ? ' - ' . $pihak : '');
            $link = 'surat/show/detail_surat/spp/' . $id . '/1';
            $items = isset($detailsMap[$id]) ? $detailsMap[$id] : [];

            $emitted = 0;
            foreach ($items as $d) {
                $ket = trim((string)$d->keterangan);
                $nom = $this->_cleanNum($d->nominal);
                if ($ket === '' && $nom == 0) continue;
                $muCode = !empty($d->mata_uang) ? $d->mata_uang : (!empty($r->kode_mata_uang) ? $r->kode_mata_uang : 1);
                $out[] = $this->_row(
                    'spp_bayar', 'Permintaan Pembayaran', $r->tgl, $r->kode, $pengaju,
                    $namaTrx, $ket ?: '-', 1, mata_uang($muCode), $nom,
                    $st, $sg, $link
                );
                $emitted++;
            }
            if ($emitted === 0) {
                $out[] = $this->_row(
                    'spp_bayar', 'Permintaan Pembayaran', $r->tgl, $r->kode, $pengaju,
                    $namaTrx, '-', 1, mata_uang($r->kode_mata_uang ?: 1), 0,
                    $st, $sg, $link
                );
            }
        }
        return $out;
    }

    // 5. Klaim Kas (PKK)
    private function _fetch_pkk($filters)
    {
        return $this->_fetchPkkGeneric($filters, 'surat_pkk', 'surat_pkk_detail', 5, 'pkk', 'Klaim Kas', 'pkk');
    }

    // 6. Klaim Kas E-Toll
    private function _fetch_pkketoll($filters)
    {
        return $this->_fetchPkkGeneric($filters, 'surat_pkketoll', 'surat_pkketoll_detail', 20, 'pkketoll', 'Klaim Kas E-Toll', 'pkketoll');
    }

    private function _fetchPkkGeneric($filters, $tbl, $dtlTbl, $katId, $jenisKey, $jenisLabel, $urlSlug)
    {
        $whereDate = $this->_buildDateWhere('h.tgl_pengajuan', $filters);
        $hasHeaderMu = ($tbl === 'surat_pkk');
        $selHeaderMu = $hasHeaderMu ? 'h.kode_mata_uang AS header_mu' : '1 AS header_mu';

        $headers = $this->db->query("
            SELECT h.id_pkk, h.kode_pkk, h.tgl_pengajuan AS tgl, h.id_pengguna, h.type, h.keterangan_pengaju,
                   h.ats_nama, h.ttd_2, h.ttd_3, $selHeaderMu
            FROM `$tbl` h
            WHERE $whereDate
            ORDER BY h.tgl_pengajuan DESC, h.id_pkk DESC
        ")->result();

        if (empty($headers)) return [];

        $ids = array_map(function ($x) { return (int)$x->id_pkk; }, $headers);
        $dtlSelect = ($tbl === 'surat_pkketoll')
            ? 'id_dpkk, id_pkk, nokartu, ket_detail, jenis_pengajuan, nominal, 1 AS mata_uang'
            : "id_dpkk, id_pkk, '' AS nokartu, ket_detail, jenis_pengajuan, nominal, mata_uang";

        $detailsMap = $this->_fetchDetailsGrouped($dtlTbl, 'id_pkk', $ids, $dtlSelect, '', 'id_dpkk ASC');

        $out = [];
        $jMap = [1 => '[Biaya Operasional] ', 2 => '[Biaya Dinas] ', 3 => '[Catatan Finance] '];
        foreach ($headers as $r) {
            $id = (int)$r->id_pkk;
            $stat = $this->_getSuratStatus($katId, $id);
            if ($stat === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ($stat === 1) {
                if ((string)$r->ttd_3 === '1') {
                    $st = 'Disetujui';
                    $sg = 'disetujui';
                } elseif ((string)$r->ttd_2 === '1') {
                    $st = 'Disetujui General Manager';
                    $sg = 'proses';
                } else {
                    $st = 'Disetujui Finance';
                    $sg = 'proses';
                }
            } else {
                $st = 'Ditolak';
                $sg = 'ditolak';
            }

            $pengaju = $this->_getNamaPengguna($r->id_pengguna);
            $namaTrx = $jenisLabel . ' (' . ($r->type ?: '-') . ') - ' . ($r->keterangan_pengaju ?: $r->ats_nama ?: '-');
            $link = 'surat/show/detail_surat/' . $urlSlug . '/' . $id . '/1';
            $items = isset($detailsMap[$id]) ? $detailsMap[$id] : [];

            $emitted = 0;
            foreach ($items as $d) {
                $ket = trim((string)$d->ket_detail);
                $nom = $this->_cleanNum($d->nominal);
                if ($ket === '' && $nom == 0) continue;
                $prefix = isset($jMap[(int)$d->jenis_pengajuan]) ? $jMap[(int)$d->jenis_pengajuan] : '';
                $kartu = trim((string)$d->nokartu) !== '' ? ' [Kartu: ' . trim($d->nokartu) . ']' : '';
                $muCode = !empty($d->mata_uang) ? $d->mata_uang : (!empty($r->header_mu) ? $r->header_mu : 1);

                $out[] = $this->_row(
                    $jenisKey, $jenisLabel, $r->tgl, $r->kode_pkk, $pengaju,
                    $namaTrx, $prefix . ($ket ?: '-') . $kartu, 1, mata_uang($muCode), $nom,
                    $st, $sg, $link
                );
                $emitted++;
            }
            if ($emitted === 0) {
                $out[] = $this->_row(
                    $jenisKey, $jenisLabel, $r->tgl, $r->kode_pkk, $pengaju,
                    $namaTrx, $r->keterangan_pengaju ?: '-', 1, mata_uang($r->header_mu ?: 1), 0,
                    $st, $sg, $link
                );
            }
        }
        return $out;
    }

    // 7. Limit Grab
    private function _fetch_gc($filters)
    {
        $whereDate = $this->_buildDateWhere('h.tgl_pengajuan', $filters, true);
        $headers = $this->db->query("
            SELECT h.id, h.kode, h.tgl_pengajuan AS tgl, h.id_pengaju, h.kota, h.keperluan
            FROM surat_gojek_corp h
            WHERE $whereDate
            ORDER BY h.tgl_pengajuan DESC, h.id DESC
        ")->result();

        if (empty($headers)) return [];

        $ids = array_map(function ($x) { return (int)$x->id; }, $headers);
        $detailsMap = $this->_fetchDetailsGrouped(
            'surat_gojek_corp_detail', 'id_srt', $ids,
            'id, id_srt, keterangan, nominal', '', 'id ASC'
        );

        $out = [];
        foreach ($headers as $r) {
            $id = (int)$r->id;
            $stat = $this->_getSuratStatus(3, $id);
            if ($stat === 0) { $st = 'Baru Diajukan'; $sg = 'proses'; }
            elseif ($stat === 1) { $st = 'Disetujui'; $sg = 'disetujui'; }
            else { $st = 'Ditolak'; $sg = 'ditolak'; }

            $pengaju = $this->_getNamaPengguna($r->id_pengaju);
            $namaTrx = 'Limit Grab - ' . $this->_cleanParts([$r->keperluan, $r->kota], ' / ');
            $link = 'surat/show/detail_surat/gc/' . $id . '/1';
            $items = isset($detailsMap[$id]) ? $detailsMap[$id] : [];

            $emitted = 0;
            foreach ($items as $d) {
                $ket = trim((string)$d->keterangan);
                $nom = $this->_cleanNum($d->nominal);
                if ($ket === '' && $nom == 0) continue;
                $out[] = $this->_row(
                    'gc', 'Limit Grab', $r->tgl, $r->kode, $pengaju,
                    $namaTrx, $ket ?: $r->keperluan ?: '-', 1, 'Rp', $nom,
                    $st, $sg, $link
                );
                $emitted++;
            }
            if ($emitted === 0) {
                $out[] = $this->_row(
                    'gc', 'Limit Grab', $r->tgl, $r->kode, $pengaju,
                    $namaTrx, $r->keperluan ?: '-', 1, 'Rp', 0,
                    $st, $sg, $link
                );
            }
        }
        return $out;
    }

    // 8. Pengajuan LTA
    private function _fetch_lta($filters)
    {
        $whereDate = $this->_buildDateWhere('h.created_at', $filters, true);
        $res = $this->db->query("
            SELECT h.*
            FROM helpdesk_lta_pengajuan h
            WHERE (h.is_active = 1 OR h.is_active IS NULL) AND $whereDate
            ORDER BY h.created_at DESC, h.id DESC
        ")->result();

        $out = [];
        foreach ($res as $r) {
            if ((int)$r->status === 5) { $st = 'Disetujui'; $sg = 'disetujui'; }
            elseif ((int)$r->status === 99) { $st = 'Ditolak'; $sg = 'ditolak'; }
            else { $st = 'Proses Persetujuan'; $sg = 'proses'; }

            $pengaju = $this->_getNamaPengguna($r->pengguna_id);
            $teknisi = $this->_getNamaPengguna($r->teknisi_id);
            $namaTrx = 'Pengajuan LTA - ' . ($r->customer_name ?: '-') . ($teknisi !== '' ? ' (Teknisi: ' . $teknisi . ')' : '');
            $rincian = [];
            if ((int)$r->hari_dinas > 0) $rincian[] = $r->hari_dinas . ' hari dinas';
            if ((float)$r->udara > 0) $rincian[] = 'Udara: Rp ' . number_format($r->udara, 0, ',', '.');
            if ((float)$r->darat > 0) $rincian[] = 'Darat: Rp ' . number_format($r->darat, 0, ',', '.');
            if ((float)$r->penginapan > 0) $rincian[] = 'Penginapan: Rp ' . number_format($r->penginapan, 0, ',', '.');
            if ((float)$r->uang_saku_makan > 0) $rincian[] = 'Uang Saku/Makan: Rp ' . number_format($r->uang_saku_makan, 0, ',', '.');
            if ((float)$r->transport_lokasi > 0) $rincian[] = 'Transport Lokasi: Rp ' . number_format($r->transport_lokasi, 0, ',', '.');
            if ((float)$r->biaya_bagasi > 0) $rincian[] = 'Bagasi: Rp ' . number_format($r->biaya_bagasi, 0, ',', '.');
            if ((float)$r->biaya_lain > 0) $rincian[] = 'Biaya Lain: Rp ' . number_format($r->biaya_lain, 0, ',', '.');
            if (!empty($r->catatan_pengaju)) $rincian[] = 'Catatan: ' . $r->catatan_pengaju;

            $out[] = $this->_row(
                'lta', 'Pengajuan LTA', $r->created_at, $r->kode_pengajuan, $pengaju,
                $namaTrx, implode(' | ', $rincian) ?: '-', 1, 'Rp', $this->_cleanNum($r->total),
                $st, $sg, 'lta_pengajuan/show/persetujuan'
            );
        }
        return $out;
    }

    // 9. Tagihan Ekspedisi
    private function _fetch_tagihan_ekspedisi($filters)
    {
        $dateExpr = "COALESCE(NULLIF(tg.tanggal_invoice, '0000-00-00'), DATE(tg.created_at))";
        $whereDate = $this->_buildDateWhere($dateExpr, $filters);
        $res = $this->db->query("
            SELECT tg.id_tagihan, tg.no_invoice, $dateExpr AS tgl, tg.tagihan_type, tg.created_by,
                   tg.nilai_tagihan, tg.biaya_asuransi, tg.total_tagihan, tg.status_approval, tg.status_bayar,
                   t.no_sj, t.nama_barang, t.no_resi AS resi_trk, c.nama_customer AS cust_trk,
                   e1.nama_ekspedisi AS eks_trk, e2.nama_ekspedisi AS eks_krm, e3.nama_ekspedisi AS eks_direct,
                   kd.kode AS kode_kirim, kd.nama_customer AS cust_krm, kd.keterangan AS ket_krm, kd.no_resi AS resi_krm, kd.ekspedisi AS eks_krm_txt,
                   cfg.role_label AS pending_role
            FROM tagihan_ekspedisi tg
            LEFT JOIN tracking_barang t ON tg.id_tracking = t.id_tracking
            LEFT JOIN customer c ON t.id_customer = c.id_customer
            LEFT JOIN ekspedisi e1 ON t.id_ekspedisi = e1.id_ekspedisi
            LEFT JOIN kirim_dokumen kd ON tg.id_kirim = kd.id
            LEFT JOIN ekspedisi e2 ON kd.id_ekspedisi = e2.id_ekspedisi
            LEFT JOIN ekspedisi e3 ON tg.id_ekspedisi = e3.id_ekspedisi
            LEFT JOIN tagihan_approval_config cfg ON tg.status_approval = cfg.status_code
            WHERE $whereDate
            ORDER BY tgl DESC, tg.id_tagihan DESC
        ")->result();

        $out = [];
        foreach ($res as $r) {
            if ((int)$r->status_approval === 5) {
                $st = 'Disetujui' . ($r->status_bayar === 'sudah_dibayar' ? ' (Sudah Dibayar)' : '');
                $sg = 'disetujui';
            } elseif ((int)$r->status_approval === 99) {
                $st = 'Ditolak';
                $sg = 'ditolak';
            } else {
                $st = 'Proses' . (!empty($r->pending_role) ? ' (Menunggu ' . $r->pending_role . ')' : '');
                $sg = 'proses';
            }

            $ekspedisi = $r->eks_direct ?: ($r->eks_trk ?: ($r->eks_krm ?: $r->eks_krm_txt));
            $customer = ($r->tagihan_type === 'kirim_dokumen') ? $r->cust_krm : $r->cust_trk;
            $namaTrx = 'Tagihan Ekspedisi - ' . $this->_cleanParts([$ekspedisi, $customer], ' / ');

            $parts = [];
            if ($r->tagihan_type === 'kirim_dokumen') {
                $parts[] = '[Kirim Dokumen]';
                if (!empty($r->kode_kirim)) $parts[] = 'Kode: ' . $r->kode_kirim;
                if (!empty($r->ket_krm)) $parts[] = $r->ket_krm;
                if (!empty($r->resi_krm)) $parts[] = 'Resi: ' . $r->resi_krm;
            } else {
                $parts[] = '[Pengiriman Barang]';
                if (!empty($r->nama_barang)) $parts[] = $r->nama_barang;
                if (!empty($r->no_sj)) $parts[] = 'SJ: ' . $r->no_sj;
                if (!empty($r->resi_trk)) $parts[] = 'Resi: ' . $r->resi_trk;
            }
            if ((float)$r->biaya_asuransi > 0) {
                $parts[] = 'Asuransi: Rp ' . number_format($r->biaya_asuransi, 0, ',', '.');
            }

            $nom = (float)$r->total_tagihan > 0 ? (float)$r->total_tagihan : (float)$r->nilai_tagihan;
            $out[] = $this->_row(
                'tagihan_ekspedisi', 'Tagihan Ekspedisi', $r->tgl, $r->no_invoice ?: ('TAG-' . $r->id_tagihan),
                $this->_getNamaPengguna($r->created_by), $namaTrx, implode(' | ', $parts), 1, 'Rp', $nom,
                $st, $sg, 'tagihan'
            );
        }
        return $out;
    }

    // 10. SPP Ekspedisi
    private function _fetch_spp_ekspedisi($filters)
    {
        $whereDate = $this->_buildDateWhere('h.created_at', $filters, true);
        $res = $this->db->query("
            SELECT h.id_spp, h.no_spp, h.no_invoice, h.atas_nama, h.grand_total, h.total_nilai,
                   h.jumlah_tagihan, h.status_approval, h.status_bayar, h.catatan, h.created_at AS tgl, h.created_by,
                   cfg.role_label AS pending_role,
                   tg.no_invoice AS tg_inv, tg.total_tagihan AS tg_total, tg.nilai_tagihan AS tg_nilai, tg.tagihan_type,
                   t.no_sj, t.nama_barang, c.nama_customer AS cust_trk,
                   kd.kode AS kode_kirim, kd.nama_customer AS cust_krm, kd.keterangan AS ket_krm,
                   COALESCE(e3.nama_ekspedisi, e1.nama_ekspedisi, e2.nama_ekspedisi, kd.ekspedisi) AS nama_ekspedisi
            FROM spp h
            LEFT JOIN spp_approval_config cfg ON h.status_approval = cfg.status_code
            LEFT JOIN spp_detail sd ON sd.id_spp = h.id_spp
            LEFT JOIN tagihan_ekspedisi tg ON tg.id_tagihan = sd.id_tagihan
            LEFT JOIN tracking_barang t ON tg.id_tracking = t.id_tracking
            LEFT JOIN customer c ON t.id_customer = c.id_customer
            LEFT JOIN ekspedisi e1 ON t.id_ekspedisi = e1.id_ekspedisi
            LEFT JOIN kirim_dokumen kd ON tg.id_kirim = kd.id
            LEFT JOIN ekspedisi e2 ON kd.id_ekspedisi = e2.id_ekspedisi
            LEFT JOIN ekspedisi e3 ON tg.id_ekspedisi = e3.id_ekspedisi
            WHERE $whereDate
            ORDER BY h.created_at DESC, h.id_spp DESC, sd.id_spp_detail ASC
        ")->result();

        $out = [];
        foreach ($res as $r) {
            if ((int)$r->status_approval === 5) {
                $st = 'Disetujui' . ($r->status_bayar === 'sudah_dibayar' ? ' (Sudah Dibayar)' : '');
                $sg = 'disetujui';
            } elseif ((int)$r->status_approval === 99) {
                $st = 'Ditolak';
                $sg = 'ditolak';
            } elseif ((int)$r->status_approval === 0) {
                $st = 'Revisi';
                $sg = 'proses';
            } else {
                $st = 'Proses' . (!empty($r->pending_role) ? ' (Menunggu ' . $r->pending_role . ')' : '');
                $sg = 'proses';
            }

            $namaTrx = 'SPP Ekspedisi - ' . $this->_cleanParts([$r->atas_nama, $r->no_invoice ? 'Inv: ' . $r->no_invoice : ''], ' ');
            $hasDetail = !empty($r->tg_inv) || !empty($r->nama_barang) || !empty($r->kode_kirim);
            if ($hasDetail) {
                $cust = ($r->tagihan_type === 'kirim_dokumen') ? $r->cust_krm : $r->cust_trk;
                $itemParts = $this->_cleanParts([
                    $r->nama_ekspedisi ? 'Ekspedisi: ' . $r->nama_ekspedisi : '',
                    $cust ? 'Customer: ' . $cust : '',
                    $r->nama_barang ?: $r->ket_krm,
                    $r->no_sj ? 'SJ: ' . $r->no_sj : ($r->kode_kirim ? 'Dok: ' . $r->kode_kirim : ''),
                    $r->tg_inv ? 'Inv: ' . $r->tg_inv : ''
                ], ' | ');
                $nom = (float)$r->tg_total > 0 ? (float)$r->tg_total : (float)$r->tg_nilai;
            } else {
                $itemParts = 'Pembayaran SPP Ekspedisi (' . (int)$r->jumlah_tagihan . ' tagihan)' . ($r->catatan ? ' | ' . $r->catatan : '');
                $nom = (float)$r->grand_total > 0 ? (float)$r->grand_total : (float)$r->total_nilai;
            }

            $out[] = $this->_row(
                'spp_ekspedisi', 'SPP Ekspedisi', $r->tgl, $r->no_spp,
                $this->_getNamaPengguna($r->created_by), $namaTrx, $itemParts ?: '-', 1, 'Rp', $nom,
                $st, $sg, 'spp'
            );
        }
        return $out;
    }

    // 11. Approval Ekspedisi (surat_aprv)
    private function _fetch_appeks($filters)
    {
        $dateExpr = "COALESCE(NULLIF(h.tanggal, '0000-00-00'), DATE(h.created_at))";
        $whereDate = $this->_buildDateWhere($dateExpr, $filters);
        $headers = $this->db->query("
            SELECT h.id, h.kode, $dateExpr AS tgl, h.id_pengaju, h.nama_customer, h.tujuan, h.nama_barang,
                   h.no_sj, h.gudang_asal, h.rencana_expedisi, h.harga_expedisi, h.status
            FROM surat_aprv h
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ")->result();

        if (empty($headers)) return [];

        $ids = array_map(function ($x) { return (int)$x->id; }, $headers);
        $detailsMap = $this->_fetchDetailsGrouped(
            'surat_aprv_detail', 'id_aprv', $ids,
            'id, id_aprv, namaekspedisi, harga', 'approval = 1', 'id ASC'
        );

        $out = [];
        foreach ($headers as $r) {
            $id = (int)$r->id;
            if ((int)$r->status === 0) { $st = 'Baru Diajukan'; $sg = 'proses'; }
            elseif ((int)$r->status === 1 || (int)$r->status === 2) { $st = 'Disetujui'; $sg = 'disetujui'; }
            else { $st = 'Ditolak'; $sg = 'ditolak'; }

            $eksDipilih = [];
            $hargaDipilih = 0.0;
            if (!empty($detailsMap[$id])) {
                foreach ($detailsMap[$id] as $d) {
                    $eksDipilih[] = trim((string)$d->namaekspedisi) . ' (Rp ' . $d->harga . ')';
                    if ($hargaDipilih == 0) {
                        $hargaDipilih = $this->_cleanNum($d->harga);
                    }
                }
            }

            $namaTrx = 'Approval Ekspedisi - ' . $this->_cleanParts([$r->nama_customer, $r->rencana_expedisi ? 'Eks: ' . $r->rencana_expedisi : ''], ' | ');
            $itemParts = $this->_cleanParts([
                $r->nama_barang ? 'Barang: ' . $r->nama_barang : '',
                $r->no_sj ? 'Berat/Dimensi: ' . $r->no_sj : '',
                $r->gudang_asal ? 'Asal: ' . $r->gudang_asal : '',
                $r->tujuan ? 'Tujuan: ' . $r->tujuan : '',
                !empty($eksDipilih) ? 'Dipilih: ' . implode('; ', $eksDipilih) : ''
            ], ' | ');
            $nom = $hargaDipilih > 0 ? $hargaDipilih : $this->_cleanNum($r->harga_expedisi);

            $out[] = $this->_row(
                'appeks', 'Approval Ekspedisi', $r->tgl, $r->kode, $this->_getNamaPengguna($r->id_pengaju),
                $namaTrx, $itemParts ?: '-', 1, 'Rp', $nom,
                $st, $sg, 'surat_new/show/detail/appeks/' . $id
            );
        }
        return $out;
    }

    // 12. Approval Forwarder
    private function _fetch_forwarder($filters)
    {
        $dateExpr = "COALESCE(NULLIF(h.tgl_aju, '0000-00-00'), NULLIF(h.tanggal, '0000-00-00'), DATE(h.created_at))";
        $whereDate = $this->_buildDateWhere($dateExpr, $filters);
        $headers = $this->db->query("
            SELECT h.*, $dateExpr AS tgl
            FROM approval_forwarder h
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ")->result();

        if (empty($headers)) return [];

        $ids = array_map(function ($x) { return (int)$x->id; }, $headers);
        $detailsRaw = $this->db->query("
            SELECT d.*, f.nama AS nama_forwarder
            FROM approval_forwarder_detail d
            LEFT JOIN forwarder f ON f.id = d.id_forwarder
            WHERE d.id_app IN (" . implode(',', $ids) . ")
            ORDER BY d.dipilih DESC, d.id ASC
        ")->result();

        // Sama persis dengan perhitungan TOTAL ONGKOS KIRIM pada v_detail_forwarder.php (tanpa ppn teks & ins_nilai)
        $costFields = [
            'asal_thc', 'asal_bl_fee', 'asal_vgm', 'asal_agaency_fee', 'asal_handling_fee',
            'asal_transportasi', 'asal_loading', 'asal_custom', 'asal_pickup', 'asal_seal',
            'asal_shipping', 'asal_cfs', 'asal_dg', 'asal_psa', 'asal_other',
            'freight_ocean', 'freight_air',
            'tuj_cfs', 'tuj_doc', 'tuj_agency_fee', 'tuj_handling', 'tuj_do',
            'tuj_admin', 'tuj_devanning', 'tuj_fordwarding_fee', 'tuj_mechanics', 'tuj_other',
            'cust_clearance', 'cust_red_line', 'cust_handling', 'cust_admin_fee', 'cust_pib_fee',
            'cust_transfer', 'cust_storage', 'cust_asu', 'cust_other',
            'oth_storage', 'oth_trucking', 'oth_handling', 'oth_buruh', 'oth_adm', 'oth_other'
        ];

        $byApp = [];
        foreach ($detailsRaw as $d) {
            $sum = 0.0;
            foreach ($costFields as $cf) {
                $sum += $this->_cleanNum($d->$cf ?? 0);
            }
            $d->_total_biaya = $sum;
            $byApp[(int)$d->id_app][] = $d;
        }

        $out = [];
        foreach ($headers as $r) {
            $id = (int)$r->id;
            if ((int)$r->status === 0) { $st = 'Proses Pengajuan'; $sg = 'proses'; }
            elseif ((int)$r->status === 1) { $st = 'Baru Diajukan'; $sg = 'proses'; }
            elseif ((int)$r->status === 2) { $st = 'Disetujui Director of Corp Planning'; $sg = 'proses'; }
            elseif ((int)$r->status === 3) { $st = 'Disetujui Director'; $sg = 'disetujui'; }
            else { $st = 'Ditolak'; $sg = 'ditolak'; }

            $chosen = null;
            $allForNames = [];
            if (!empty($byApp[$id])) {
                foreach ($byApp[$id] as $d) {
                    if (!empty($d->nama_forwarder)) {
                        $allForNames[] = $d->nama_forwarder . ' (Rp ' . number_format($d->_total_biaya, 0, ',', '.') . ')';
                    }
                    if ((int)$d->dipilih === 1 && $chosen === null) {
                        $chosen = $d;
                    }
                }
            }

            $namaTrx = 'Approval Forwarder - ' . ($r->nama ?: '-');
            $itemParts = $this->_cleanParts([
                $r->sistem_pengiriman ? 'Via: ' . $r->sistem_pengiriman : '',
                ($r->pol || $r->pod) ? 'Rute: ' . $r->pol . ' -> ' . $r->pod : '',
                $r->berat_dimensi ? 'Berat/Dimensi: ' . $r->berat_dimensi : '',
                $chosen ? 'Forwarder Dipilih: ' . $chosen->nama_forwarder . (!empty($chosen->ppn) ? ' [' . $chosen->ppn . ']' : '') : (!empty($allForNames) ? 'Perbandingan: ' . implode(', ', $allForNames) : ''),
                $r->nilai_inv ? 'Nilai Inv: ' . $r->mata_nilai_inv . ' ' . $r->nilai_inv : '',
                $r->keterangan
            ], ' | ');

            $nom = $chosen ? $chosen->_total_biaya : 0.0;
            $out[] = $this->_row(
                'forwarder', 'Approval Forwarder', $r->tgl, $r->kode, $this->_getNamaPengguna($r->id_pengguna),
                $namaTrx, $itemParts ?: '-', 1, 'Rp', $nom,
                $st, $sg, 'forwarder/show/detail_data/' . $id
            );
        }
        return $out;
    }

    // 13. Approval Harga
    private function _fetch_approval_harga($filters)
    {
        $whereDate = $this->_buildDateWhere('h.tgl', $filters, true);
        $headers = $this->db->query("
            SELECT h.id_approval, h.kode, h.tgl, h.id_pengguna, h.nama AS nama_marketing, h.nama_customer,
                   h.sistem_pembayaran, h.ttd_gm, h.pengajuan_ttd_1, h.ttd_2, h.ttd_1
            FROM surat_approval h
            WHERE $whereDate
            ORDER BY h.tgl DESC, h.id_approval DESC
        ")->result();

        if (empty($headers)) return [];

        $ids = array_map(function ($x) { return (int)$x->id_approval; }, $headers);
        $detailsMap = $this->_fetchDetailsGrouped(
            'surat_approval_detail', 'id_approval', $ids,
            'id, id_approval, nama_barang, acuan_hrg, hrg_ditawarkan, catatan', '', 'id ASC'
        );

        $out = [];
        foreach ($headers as $r) {
            $id = (int)$r->id_approval;
            $stat = $this->_getSuratStatus(8, $id);
            if ($stat === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ($stat === 1) {
                if ((string)$r->ttd_2 === '1' || (string)$r->ttd_1 === '1') {
                    $st = 'Disetujui';
                    $sg = 'disetujui';
                } elseif ((string)$r->pengajuan_ttd_1 === '1') {
                    $st = 'Disetujui Accounting & Tax';
                    $sg = 'proses';
                } elseif ((string)$r->ttd_gm === '1') {
                    $st = 'Disetujui General Manager';
                    $sg = 'proses';
                } else {
                    $st = 'Proses Persetujuan';
                    $sg = 'proses';
                }
            } else {
                $st = 'Ditolak';
                $sg = 'ditolak';
            }

            $pengaju = $this->_getNamaPengguna($r->id_pengguna) ?: $r->nama_marketing;
            $namaTrx = 'Approval Harga - ' . $this->_cleanParts([$r->nama_customer, $r->nama_marketing ? 'Mkt: ' . $r->nama_marketing : ''], ' | ');
            $link = 'surat/show/detail_surat/approval/' . $id;
            $items = isset($detailsMap[$id]) ? $detailsMap[$id] : [];

            $emitted = 0;
            foreach ($items as $d) {
                $brg = trim((string)$d->nama_barang);
                $nom = $this->_cleanNum($d->hrg_ditawarkan);
                if ($brg === '' && $nom == 0) continue;
                $itemParts = $this->_cleanParts([
                    $brg,
                    $d->acuan_hrg ? 'Acuan: ' . $d->acuan_hrg : '',
                    $r->sistem_pembayaran ? 'Bayar: ' . $r->sistem_pembayaran : '',
                    $d->catatan ? 'Catatan: ' . $d->catatan : ''
                ], ' | ');

                $out[] = $this->_row(
                    'approval_harga', 'Approval Harga', $r->tgl, $r->kode, $pengaju,
                    $namaTrx, $itemParts ?: '-', 1, 'Rp', $nom,
                    $st, $sg, $link
                );
                $emitted++;
            }
            if ($emitted === 0) {
                $out[] = $this->_row(
                    'approval_harga', 'Approval Harga', $r->tgl, $r->kode, $pengaju,
                    $namaTrx, '-', 1, 'Rp', 0,
                    $st, $sg, $link
                );
            }
        }
        return $out;
    }

    // 14. Approval PO
    private function _fetch_approval_po($filters)
    {
        $dateExpr = "COALESCE(NULLIF(h.tanggal, '0000-00-00'), DATE(h.created_at))";
        $whereDate = $this->_buildDateWhere($dateExpr, $filters);
        $res = $this->db->query("
            SELECT h.*, $dateExpr AS tgl, pl.identitas_pelanggan
            FROM surat_po h
            LEFT JOIN pelanggan pl ON pl.id_pelanggan = h.id_pelanggan
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ")->result();

        $out = [];
        foreach ($res as $r) {
            if ((string)$r->status === '0' || $r->status === null) { $st = 'Baru Diajukan'; $sg = 'proses'; }
            elseif ((string)$r->status === '1') { $st = 'Disetujui Director'; $sg = 'disetujui'; }
            elseif ((string)$r->status === '3') { $st = 'Disetujui HR & Legal'; $sg = 'proses'; }
            elseif ((string)$r->status === '5') { $st = 'Disetujui General Manager'; $sg = 'proses'; }
            else { $st = 'Ditolak'; $sg = 'ditolak'; }

            $items = [];
            for ($i = 1; $i <= 11; $i++) {
                $col = 'item' . $i;
                if (!empty($r->$col) && trim($r->$col) !== '') {
                    $items[] = trim($r->$col);
                }
            }
            $namaTrx = 'Approval PO - ' . $this->_cleanParts([$r->identitas_pelanggan, $r->no_po ? 'No. PO: ' . $r->no_po : ''], ' | ');
            $ditrx = $this->_cleanParts([
                !empty($items) ? 'Item: ' . implode('; ', $items) : '',
                $r->alasan ? 'Alasan: ' . $r->alasan : ''
            ], ' | ');

            $out[] = $this->_row(
                'approval_po', 'Approval PO', $r->tgl, $r->kode, $this->_getNamaPengguna($r->id_pengaju),
                $namaTrx, $ditrx ?: '-', count($items) ?: 1, 'Rp', 0,
                $st, $sg, 'surat_new/show/detail/po/' . $r->id
            );
        }
        return $out;
    }

    // 15. Approval Faktur Pajak
    private function _fetch_app_pajak($filters)
    {
        $dateExpr = "COALESCE(NULLIF(h.tanggal, '0000-00-00'), DATE(h.created_at))";
        $whereDate = $this->_buildDateWhere($dateExpr, $filters);
        $res = $this->db->query("
            SELECT h.*, $dateExpr AS tgl, pl.identitas_pelanggan AS nama_customer
            FROM approval_faktur_pajak h
            LEFT JOIN pelanggan pl ON pl.id_pelanggan = h.id_customer
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ")->result();

        $out = [];
        foreach ($res as $r) {
            if ((int)$r->status === 0) { $st = 'Baru Diajukan'; $sg = 'proses'; }
            elseif ((int)$r->status === 1) { $st = 'Disetujui General Manager'; $sg = 'proses'; }
            elseif ((int)$r->status === 2) { $st = 'Disetujui Staff Tax'; $sg = 'proses'; }
            elseif ((int)$r->status === 3) { $st = 'Disetujui Director of Corp Planning'; $sg = 'proses'; }
            elseif ((int)$r->status === 4) { $st = 'Disetujui Director'; $sg = 'disetujui'; }
            else { $st = 'Ditolak'; $sg = 'ditolak'; }

            $dpp = $this->_cleanNum($r->dpp);
            $ppn = $this->_cleanNum($r->ppn);
            $mkt = $this->_getNamaPengguna($r->id_marketing);
            $namaTrx = 'Approval Faktur Pajak - ' . $this->_cleanParts([$r->nama_customer, $r->no_po ? 'PO: ' . $r->no_po : ''], ' | ');
            $ditrx = $this->_cleanParts([
                'DPP: Rp ' . number_format($dpp, 0, ',', '.'),
                'PPN: Rp ' . number_format($ppn, 0, ',', '.'),
                $r->pembayaran ? 'Pembayaran: ' . $r->pembayaran : '',
                $mkt !== '' ? 'Mkt: ' . $mkt : '',
                $r->alasan ? 'Alasan: ' . $r->alasan : ''
            ], ' | ');

            $out[] = $this->_row(
                'app_pajak', 'Approval Faktur Pajak', $r->tgl, $r->kode, $this->_getNamaPengguna($r->id_pengaju),
                $namaTrx, $ditrx, 1, 'Rp', $dpp + $ppn,
                $st, $sg, 'surat_new/show/detail/app_pajak/' . $r->id
            );
        }
        return $out;
    }

    // 16. Approval Director
    private function _fetch_appdir($filters)
    {
        $whereDate = $this->_buildDateWhere('h.created_at', $filters, true);
        $res = $this->db->query("
            SELECT h.*
            FROM approval_director h
            WHERE $whereDate
            ORDER BY h.created_at DESC, h.id DESC
        ")->result();

        $out = [];
        foreach ($res as $r) {
            if ((int)$r->status === 0) { $st = 'Baru Diajukan'; $sg = 'proses'; }
            elseif ((int)$r->status === 1) { $st = 'Disetujui'; $sg = 'disetujui'; }
            else { $st = 'Ditolak'; $sg = 'ditolak'; }

            $jenisDok = ((int)$r->jenis === 1) ? 'Tempel (Soft File)' : (((int)$r->jenis === 2) ? 'Cap Basah (Hard File)' : '');
            $namaTrx = 'Approval Director - ' . ($r->nama_dokumen ?: '-');
            $ditrx = $this->_cleanParts([$r->ket, $jenisDok ? '[' . $jenisDok . ']' : ''], ' ');

            $out[] = $this->_row(
                'appdir', 'Approval Director', $r->created_at, $r->kode, $this->_getNamaPengguna($r->id_pengaju),
                $namaTrx, $ditrx ?: '-', 1, 'Rp', 0,
                $st, $sg, 'surat_new/show/detail/appdir/' . $r->id
            );
        }
        return $out;
    }

    // 17. Kunjungan Gudang
    private function _fetch_kg($filters)
    {
        $dateExpr = "COALESCE(NULLIF(h.tgl_pengajuan, '0000-00-00'), NULLIF(h.tgl_dinas, '0000-00-00'))";
        $whereDate = $this->_buildDateWhere($dateExpr, $filters);
        $res = $this->db->query("
            SELECT h.*, $dateExpr AS tgl
            FROM surat_kunjungan_gudang h
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ")->result();

        $out = [];
        foreach ($res as $r) {
            $stat = $this->_getSuratStatus(6, (int)$r->id);
            if ($stat === 0) {
                if ((int)$r->persetujuan_ga === 1) { $st = 'Disetujui GA'; $sg = 'proses'; }
                elseif ((int)$r->persetujuan_ga === 2) { $st = 'Ditolak GA'; $sg = 'ditolak'; }
                else { $st = 'Baru Diajukan'; $sg = 'proses'; }
            } elseif ($stat === 1) { $st = 'Pengajuan Disetujui'; $sg = 'disetujui'; }
            elseif ($stat === 2) { $st = 'Laporan Diajukan'; $sg = 'proses'; }
            elseif ($stat === 3) { $st = 'Laporan Diterima GA'; $sg = 'disetujui'; }
            else { $st = 'Ditolak'; $sg = 'ditolak'; }

            $out[] = $this->_row(
                'kg', 'Kunjungan Gudang', $r->tgl, $r->kode, $this->_getNamaPengguna($r->id_pengguna),
                'Kunjungan Gudang', $this->_cleanParts([$r->perihal, $r->tgl_dinas ? 'Tgl Kunjungan: ' . $r->tgl_dinas : ''], ' | ') ?: '-',
                1, 'Rp', 0, $st, $sg, 'surat/show/detail_surat/kg/' . $r->id . '/1'
            );
        }
        return $out;
    }

    // 18. Permintaan Dinas (Marketing, Karyawan, Teknisi)
    private function _fetch_pd($filters)
    {
        $out = [];
        $where1 = $this->_buildDateWhere('h.tgl_pengajuan', $filters);

        $rows1 = $this->db->query("
            SELECT h.id_pd, h.kode, h.id_kat_surat, h.tgl_pengajuan AS tgl, h.id_pengguna,
                   h.wilayah_dinas, h.tujuan, h.mulai, h.akhir, h.transportasi, h.persetujuan_ga
            FROM surat_pd h
            WHERE $where1
            ORDER BY h.tgl_pengajuan DESC, h.id_pd DESC
        ")->result();

        if (!empty($rows1)) {
            $ids1 = array_map(function ($x) { return (int)$x->id_pd; }, $rows1);
            $dtl1 = $this->_fetchDetailsGrouped('surat_pd_detail', 'id_pd', $ids1, 'id_pd, nama_customer, tujuan_dinas', '', 'id ASC');
            foreach ($rows1 as $r) {
                $id = (int)$r->id_pd;
                $subLabel = ((int)$r->id_kat_surat === 12) ? 'Permintaan Dinas Karyawan' : 'Permintaan Dinas Marketing';
                $slug = ((int)$r->id_kat_surat === 12) ? 'pd_karyawan' : 'pd';
                list($st, $sg) = $this->_mapDinasStatus($this->_getSuratStatus((int)$r->id_kat_surat, $id), $r->persetujuan_ga);
                $kunj = [];
                if (!empty($dtl1[$id])) {
                    foreach ($dtl1[$id] as $d) {
                        $txt = $this->_cleanParts([$d->nama_customer, $d->tujuan_dinas], ' - ');
                        if ($txt !== '') $kunj[] = $txt;
                    }
                }
                $ditrx = $this->_cleanParts([
                    $r->tujuan,
                    ($r->mulai && $r->akhir) ? 'Jadwal: ' . $r->mulai . ' s/d ' . $r->akhir : '',
                    $r->transportasi ? 'Transport: ' . $r->transportasi : '',
                    !empty($kunj) ? 'Kunjungan: ' . implode('; ', $kunj) : ''
                ], ' | ');
                $out[] = $this->_row(
                    'pd', 'Permintaan Dinas', $r->tgl, $r->kode, $this->_getNamaPengguna($r->id_pengguna),
                    $subLabel . ($r->wilayah_dinas ? ' - ' . $r->wilayah_dinas : ''), $ditrx ?: '-',
                    1, 'Rp', 0, $st, $sg, 'surat/show/detail_surat/' . $slug . '/' . $id . '/1'
                );
            }
        }

        $rows2 = $this->db->query("
            SELECT h.id_pd, h.kode, h.tgl_pengajuan AS tgl, h.id_pengguna,
                   h.wilayah_dinas, h.tujuan, h.mulai, h.akhir, h.transportasi, h.persetujuan_ga
            FROM surat_pdt h
            WHERE $where1
            ORDER BY h.tgl_pengajuan DESC, h.id_pd DESC
        ")->result();

        if (!empty($rows2)) {
            $ids2 = array_map(function ($x) { return (int)$x->id_pd; }, $rows2);
            $dtl2 = $this->_fetchDetailsGrouped('surat_pdt_detail', 'id_pd', $ids2, 'id_pd, nama_customer, tujuan_dinas', '', 'id ASC');
            foreach ($rows2 as $r) {
                $id = (int)$r->id_pd;
                list($st, $sg) = $this->_mapDinasStatus($this->_getSuratStatus(11, $id), $r->persetujuan_ga);
                $kunj = [];
                if (!empty($dtl2[$id])) {
                    foreach ($dtl2[$id] as $d) {
                        $txt = $this->_cleanParts([$d->nama_customer, $d->tujuan_dinas], ' - ');
                        if ($txt !== '') $kunj[] = $txt;
                    }
                }
                $ditrx = $this->_cleanParts([
                    $r->tujuan,
                    ($r->mulai && $r->akhir) ? 'Jadwal: ' . $r->mulai . ' s/d ' . $r->akhir : '',
                    $r->transportasi ? 'Transport: ' . $r->transportasi : '',
                    !empty($kunj) ? 'Kunjungan: ' . implode('; ', $kunj) : ''
                ], ' | ');
                $out[] = $this->_row(
                    'pd', 'Permintaan Dinas', $r->tgl, $r->kode, $this->_getNamaPengguna($r->id_pengguna),
                    'Permintaan Dinas Teknisi' . ($r->wilayah_dinas ? ' - ' . $r->wilayah_dinas : ''), $ditrx ?: '-',
                    1, 'Rp', 0, $st, $sg, 'surat/show/detail_surat/pd_teknisi/' . $id . '/1'
                );
            }
        }

        return $out;
    }

    // 19. Surat Dinas
    private function _fetch_sd($filters)
    {
        $dateExpr = "COALESCE(NULLIF(h.tgl_pengajuan, '0000-00-00'), NULLIF(h.tgl_dinas, '0000-00-00'))";
        $whereDate = $this->_buildDateWhere($dateExpr, $filters);
        $res = $this->db->query("
            SELECT h.*, $dateExpr AS tgl
            FROM surat_pd_dinas h
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ")->result();

        $out = [];
        foreach ($res as $r) {
            list($st, $sg) = $this->_mapDinasStatus($this->_getSuratStatus(10, (int)$r->id), $r->persetujuan_ga);
            $pengaju = $this->_getNamaPengguna($r->id_pengguna) ?: $r->nama_karyawan;
            $ditrx = $this->_cleanParts([
                $r->perihal,
                $r->lokasi_dinas ? 'Lokasi: ' . $r->lokasi_dinas : '',
                ($r->tgl_dinas) ? 'Tgl Dinas: ' . $r->tgl_dinas . ($r->tgl_dinas_akhir ? ' s/d ' . $r->tgl_dinas_akhir : '') : ''
            ], ' | ');
            $out[] = $this->_row(
                'sd', 'Surat Dinas', $r->tgl, $r->kode, $pengaju,
                'Surat Dinas - ' . ($r->nama_karyawan ?: $pengaju ?: '-'), $ditrx ?: '-',
                1, 'Rp', 0, $st, $sg, 'surat/show/detail_surat/sd/' . $r->id . '/1'
            );
        }
        return $out;
    }

    private function _mapDinasStatus($stat, $ga)
    {
        if ((int)$stat === 0) {
            if ((int)$ga === 1) return ['Disetujui GA', 'proses'];
            if ((int)$ga === 2) return ['Ditolak GA', 'ditolak'];
            return ['Baru Diajukan', 'proses'];
        }
        if ((int)$stat === 1) return ['Disetujui', 'disetujui'];
        return ['Ditolak', 'ditolak'];
    }

    // 20. Penggunaan Kendaraan
    private function _fetch_kendaraan($filters)
    {
        $dateExpr = "COALESCE(NULLIF(h.tanggal, '0000-00-00'), DATE(h.created_at))";
        $whereDate = $this->_buildDateWhere($dateExpr, $filters);
        $res = $this->db->query("
            SELECT h.*, $dateExpr AS tgl
            FROM surat_kendaraan h
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ")->result();

        $out = [];
        foreach ($res as $r) {
            if ((int)$r->status === 0) { $st = 'Baru Diajukan'; $sg = 'proses'; }
            elseif ((int)$r->status === 1) { $st = 'Disetujui Head of Technician'; $sg = 'proses'; }
            elseif ((int)$r->status === 2) { $st = 'Disetujui General Manager'; $sg = 'disetujui'; }
            else { $st = 'Ditolak'; $sg = 'ditolak'; }

            $namaTrx = 'Penggunaan Kendaraan - ' . $this->_cleanParts([$r->mobil, $r->nopol], ' ');
            $ditrx = $this->_cleanParts([$r->keperluan, $r->lama ? 'Lama: ' . $r->lama . ' Hari' : ''], ' | ');
            $out[] = $this->_row(
                'kendaraan', 'Penggunaan Kendaraan', $r->tgl, $r->kode, $this->_getNamaPengguna($r->id_pengaju),
                $namaTrx, $ditrx ?: '-', 1, 'Rp', 0, $st, $sg, 'surat_new/show/detail/kendaraan/' . $r->id
            );
        }
        return $out;
    }

    // 21. Ruang Meeting
    private function _fetch_meetingroom($filters)
    {
        $dateExpr = "COALESCE(NULLIF(h.tgl_pengajuan, '0000-00-00'), DATE(h.created_at))";
        $whereDate = $this->_buildDateWhere($dateExpr, $filters);
        $res = $this->db->query("
            SELECT h.*, $dateExpr AS tgl
            FROM surat_meetingroom h
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ")->result();

        $out = [];
        foreach ($res as $r) {
            if ((int)$r->status === 0) { $st = 'Baru Diajukan'; $sg = 'proses'; }
            elseif ((int)$r->status === 1) { $st = 'Disetujui GA'; $sg = 'proses'; }
            elseif ((int)$r->status === 2) { $st = 'Disetujui'; $sg = 'disetujui'; }
            else { $st = 'Ditolak'; $sg = 'ditolak'; }

            $ditrx = $this->_cleanParts([$r->perihal, ($r->jam_mulai || $r->jam_akhir) ? 'Pukul: ' . $r->jam_mulai . ' s/d ' . $r->jam_akhir : ''], ' | ');
            $out[] = $this->_row(
                'meetingroom', 'Ruang Meeting', $r->tgl, $r->kode, $this->_getNamaPengguna($r->id_pengaju),
                'Ruang Meeting - ' . ($r->perihal ?: '-'), $ditrx ?: '-', 1, 'Rp', 0,
                $st, $sg, 'surat_part_two/show/detail/meetingroom/' . $r->id
            );
        }
        return $out;
    }

    // 22. Izin & Cuti
    private function _fetch_izin_cuti($filters)
    {
        $out = [];
        $dateExpr1 = "COALESCE(NULLIF(DATE(h.tgl_pengajuan), '0000-00-00'), NULLIF(h.tanggal, '0000-00-00'), DATE(h.created_at))";
        $where1 = $this->_buildDateWhere($dateExpr1, $filters);
        $rows1 = $this->db->query("
            SELECT h.*, $dateExpr1 AS tgl
            FROM surat_izin_jam_kerja h
            WHERE $where1
            ORDER BY tgl DESC, h.id DESC
        ")->result();

        foreach ($rows1 as $r) {
            list($st, $sg) = $this->_mapIzinStatus($r->status);
            $isSimp = ((int)$r->jenis === 2);
            $subLabel = $isSimp ? 'Izin Meninggalkan Pekerjaan' : 'Izin Jam Kerja';
            $slug = $isSimp ? 'izin_meninggalkan' : 'izin_jam_kerja';
            $waktu = $isSimp
                ? ($r->tgl_awal ? $r->tgl_awal . ' s/d ' . $r->tgl_akhir . ($r->total ? ' (' . $r->total . ')' : '') : '')
                : ($r->tanggal ? $r->tanggal . ' ' . $r->jam_mulai . ' s/d ' . $r->jam_akhir : '');

            $out[] = $this->_row(
                'izin_cuti', 'Izin & Cuti', $r->tgl, $r->kode_ijk, $this->_getNamaPengguna($r->id_pengaju),
                $subLabel . ($r->jenis_izin ? ' - ' . $r->jenis_izin : ''),
                $this->_cleanParts([$r->alasan, $waktu ? 'Waktu: ' . $waktu : ''], ' | ') ?: '-',
                1, 'Rp', 0, $st, $sg, 'surat_part_two/show/detail/' . $slug . '/' . $r->id
            );
        }

        $dateExpr2 = "COALESCE(NULLIF(DATE(h.tgl_pengajuan), '0000-00-00'), NULLIF(h.tgl_awal, '0000-00-00'), DATE(h.created_at))";
        $where2 = $this->_buildDateWhere($dateExpr2, $filters);
        $rows2 = $this->db->query("
            SELECT h.*, $dateExpr2 AS tgl
            FROM surat_cuti_tahunan h
            WHERE $where2
            ORDER BY tgl DESC, h.id DESC
        ")->result();

        foreach ($rows2 as $r) {
            list($st, $sg) = $this->_mapIzinStatus($r->status);
            $waktu = $r->tgl_awal ? $r->tgl_awal . ' s/d ' . $r->tgl_akhir . ($r->total ? ' (' . $r->total . ')' : '') : '';
            $out[] = $this->_row(
                'izin_cuti', 'Izin & Cuti', $r->tgl, $r->kode_cuti, $this->_getNamaPengguna($r->id_pengaju),
                'Cuti Tahunan' . ($r->jenis_izin ? ' - ' . $r->jenis_izin : ''),
                $this->_cleanParts([$r->alasan, $waktu ? 'Tanggal: ' . $waktu : ''], ' | ') ?: '-',
                1, 'Rp', 0, $st, $sg, 'surat_part_two/show/detail/cuti/' . $r->id
            );
        }

        return $out;
    }

    private function _mapIzinStatus($status)
    {
        $s = (int)$status;
        if ($s === 0) return ['Baru Diajukan', 'proses'];
        if ($s === 1) return ['Disetujui GA', 'proses'];
        if ($s === 2) return ['Disetujui HR & Legal', 'proses'];
        if ($s === 3) return ['Disetujui General Manager', 'disetujui'];
        return ['Ditolak', 'ditolak'];
    }
}
