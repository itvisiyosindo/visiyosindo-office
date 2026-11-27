<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_export_persetujuan extends CI_Model
{
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

    private function _buildDateWhere($colExpr, $filters)
    {
        $tglAwal = !empty($filters['tgl_awal']) ? trim((string)$filters['tgl_awal']) : '';
        $tglAkhir = !empty($filters['tgl_akhir']) ? trim((string)$filters['tgl_akhir']) : '';
        $tahun = !empty($filters['tahun']) ? (int)$filters['tahun'] : 0;
        $bulan = !empty($filters['bulan']) ? (int)$filters['bulan'] : 0;

        $conds = [];
        if ($tglAwal !== '' || $tglAkhir !== '') {
            if ($tglAwal !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglAwal)) {
                $conds[] = "DATE($colExpr) >= " . $this->db->escape($tglAwal);
            }
            if ($tglAkhir !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglAkhir)) {
                $conds[] = "DATE($colExpr) <= " . $this->db->escape($tglAkhir);
            }
        } else {
            if ($tahun > 0) {
                $conds[] = "YEAR($colExpr) = " . $tahun;
            }
            if ($bulan >= 1 && $bulan <= 12) {
                $conds[] = "MONTH($colExpr) = " . $bulan;
            }
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

        $sql = "
            SELECT h.id_pbok, h.kode_pbok, $dateExpr AS tgl, h.alamat, h.bank, h.rekening, h.ats_nama,
                   h.mata_uang AS header_mu, h.pengajuan_ttd_1, h.ttd_2, h.ttd_3,
                   s.status AS stat_persetujuan, p.nama AS pengaju,
                   d.keterangan AS item_ket, d.qty, d.estimasi, d.total, d.mata_uang AS item_mu, d.catatan AS item_catatan
            FROM surat_pbok h
            LEFT JOIN surat_list s ON s.id_srt = h.id_pbok AND s.id_kat_surat = 4
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengguna
            LEFT JOIN surat_pbok_detail d ON d.id_pbok = h.id_pbok
            WHERE $whereDate
            ORDER BY tgl DESC, h.id_pbok DESC, d.id ASC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            if ((int)$r->stat_persetujuan === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ((int)$r->stat_persetujuan === 1) {
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

            $pihak = $this->_cleanParts([$r->ats_nama, $r->bank, $r->alamat], ' / ');
            $namaTrx = 'PBOK' . ($pihak !== '' ? ' - ' . $pihak : '');
            $item = $this->_cleanParts([$r->item_ket, $r->item_catatan ? '(Catatan: ' . $r->item_catatan . ')' : ''], ' ');
            $muCode = !empty($r->item_mu) ? $r->item_mu : (!empty($r->header_mu) ? $r->header_mu : 1);
            $nom = $this->_cleanNum($r->total);
            if ($nom == 0 && $this->_cleanNum($r->estimasi) > 0) {
                $nom = $this->_cleanNum($r->estimasi) * max(1, (int)$r->qty);
            }

            $out[] = $this->_row(
                'pbok', 'PBOK', $r->tgl, $r->kode_pbok, $r->pengaju,
                $namaTrx, $item ?: '-', (int)$r->qty ?: 1, mata_uang($muCode), $nom,
                $st, $sg, 'surat/show/detail_surat/pbok/' . $r->id_pbok . '/1'
            );
        }
        return $out;
    }

    // 2. PPA
    private function _fetch_ppa($filters)
    {
        $whereDate = $this->_buildDateWhere('h.tgl_pengajuan', $filters);
        $sql = "
            SELECT h.id_ppa, h.kode_ppa, h.tgl_pengajuan AS tgl, h.alamat, h.bank, h.ats_nama, h.catatan,
                   h.pengajuan_ttd_1, h.ttd_2, h.ttd_3, h.ttd_4,
                   s.status AS stat_persetujuan, p.nama AS pengaju,
                   d.keterangan AS item_ket, d.qty, d.estimasi, d.total
            FROM surat_ppa h
            LEFT JOIN surat_list s ON s.id_srt = h.id_ppa AND s.id_kat_surat = 7
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengguna
            LEFT JOIN surat_ppa_detail d ON d.id_ppa = h.id_ppa
            WHERE $whereDate
            ORDER BY h.tgl_pengajuan DESC, h.id_ppa DESC, d.id ASC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            if ((int)$r->stat_persetujuan === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ((int)$r->stat_persetujuan === 1) {
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

            $pihak = $this->_cleanParts([$r->alamat, $r->ats_nama, $r->bank], ' / ');
            $namaTrx = 'PPA' . ($pihak !== '' ? ' - ' . $pihak : '');
            $item = $this->_cleanParts([$r->item_ket, $r->catatan ? '(Catatan: ' . $r->catatan . ')' : ''], ' ');
            $nom = $this->_cleanNum($r->total);
            if ($nom == 0 && $this->_cleanNum($r->estimasi) > 0) {
                $nom = $this->_cleanNum($r->estimasi) * max(1, (int)$r->qty);
            }

            $out[] = $this->_row(
                'ppa', 'PPA', $r->tgl, $r->kode_ppa, $r->pengaju,
                $namaTrx, $item ?: '-', (int)$r->qty ?: 1, 'Rp', $nom,
                $st, $sg, 'surat/show/detail_surat/ppa/' . $r->id_ppa . '/1'
            );
        }
        return $out;
    }

    // 3. Pembiayaan Dinas
    private function _fetch_pb($filters)
    {
        $whereDate = $this->_buildDateWhere('h.tgl_pengajuan', $filters);
        $headers = $this->db->query("
            SELECT h.id_pb, h.kode_pb, h.tgl_pengajuan AS tgl, h.kota, h.keperluan, h.tgl_pergi, h.tgl_kembali,
                   h.pengajuan_ttd_2, h.pengajuan_ttd_3, h.laporan_ttd_2, h.laporan_ttd_3, h.persetujuan_ga,
                   s.status AS stat_persetujuan, p.nama AS pengaju
            FROM surat_biaya_dinas h
            LEFT JOIN surat_list s ON s.id_srt = h.id_pb AND s.id_kat_surat = 1
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengguna
            WHERE $whereDate
            ORDER BY h.tgl_pengajuan DESC, h.id_pb DESC
        ")->result();

        if (empty($headers)) {
            return [];
        }

        $ids = array_map(function ($x) { return (int)$x->id_pb; }, $headers);
        $detailsRaw = $this->db->query("
            SELECT id_pb, kode_laporan, tanggal, keterangan, nominal
            FROM surat_detail_biaya_dinas
            WHERE id_pb IN (" . implode(',', $ids) . ") AND kode_laporan IN (1, 2)
            ORDER BY id_detail_pb ASC
        ")->result();

        $byPb = [];
        foreach ($detailsRaw as $d) {
            $byPb[(int)$d->id_pb][(int)$d->kode_laporan][] = $d;
        }

        $out = [];
        foreach ($headers as $r) {
            $id = (int)$r->id_pb;
            if ((int)$r->stat_persetujuan === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ((int)$r->stat_persetujuan === 1) {
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
            } elseif ((int)$r->stat_persetujuan === 2) {
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
            } elseif ((int)$r->stat_persetujuan === 3) {
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

            $namaTrx = 'Pembiayaan Dinas - ' . $this->_cleanParts([$r->kota, $r->keperluan], ': ');
            // Gunakan realisasi (kode_laporan=2) jika sudah dilaporkan agar tidak dobel hitung dengan estimasi (kode_laporan=1)
            $useKode = !empty($byPb[$id][2]) ? 2 : 1;
            $tag = ($useKode === 2) ? '[Realisasi] ' : '[Pengajuan] ';
            $items = !empty($byPb[$id][$useKode]) ? $byPb[$id][$useKode] : [];

            if (empty($items)) {
                $out[] = $this->_row(
                    'pb', 'Pembiayaan Dinas', $r->tgl, $r->kode_pb, $r->pengaju,
                    $namaTrx, $r->keperluan ?: '-', 1, 'Rp', 0,
                    $st, $sg, 'surat/show/detail_surat/PB/' . $id . '/1'
                );
                continue;
            }

            foreach ($items as $d) {
                $ket = trim((string)$d->keterangan);
                $nom = $this->_cleanNum($d->nominal);
                if ($ket === '' && $nom == 0) {
                    continue;
                }
                $out[] = $this->_row(
                    'pb', 'Pembiayaan Dinas', $r->tgl, $r->kode_pb, $r->pengaju,
                    $namaTrx, $tag . ($ket ?: '-'), 1, 'Rp', $nom,
                    $st, $sg, 'surat/show/detail_surat/PB/' . $id . '/1'
                );
            }
        }
        return $out;
    }

    // 4. Permintaan Pembayaran (SPP Surat)
    private function _fetch_spp_bayar($filters)
    {
        $whereDate = $this->_buildDateWhere('h.tgl_pengajuan', $filters);
        $sql = "
            SELECT h.id, h.kode, h.tgl_pengajuan AS tgl, h.rek_bank, h.rek_nama, h.kode_mata_uang, h.catatan,
                   h.ttd1, h.ttd2, h.ttd3, s.status AS stat_persetujuan, p.nama AS pengaju,
                   d.keterangan AS item_ket, d.nominal, d.mata_uang AS item_mu
            FROM surat_permintaan_pembayaran h
            LEFT JOIN surat_list s ON s.id_srt = h.id AND s.id_kat_surat = 2
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengaju
            LEFT JOIN surat_permintaan_pembayaran_detail d ON d.id_srt = h.id
            WHERE $whereDate
            ORDER BY h.tgl_pengajuan DESC, h.id DESC, d.id ASC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            $ket = trim((string)$r->item_ket);
            $nom = $this->_cleanNum($r->nominal);
            if ($ket === '' && $nom == 0) {
                continue;
            }
            if ((int)$r->stat_persetujuan === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ((int)$r->stat_persetujuan === 1) {
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

            $pihak = $this->_cleanParts([$r->rek_nama, $r->rek_bank], ' / ');
            $namaTrx = 'Permintaan Pembayaran' . ($pihak !== '' ? ' - ' . $pihak : '');
            $muCode = !empty($r->item_mu) ? $r->item_mu : (!empty($r->kode_mata_uang) ? $r->kode_mata_uang : 1);

            $out[] = $this->_row(
                'spp_bayar', 'Permintaan Pembayaran', $r->tgl, $r->kode, $r->pengaju,
                $namaTrx, $ket ?: '-', 1, mata_uang($muCode), $nom,
                $st, $sg, 'surat/show/detail_surat/spp/' . $r->id . '/1'
            );
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
        $hasDetailMu = ($tbl === 'surat_pkk');
        $selMu = ($hasHeaderMu ? 'h.kode_mata_uang' : '1') . ' AS header_mu, ' . ($hasDetailMu ? 'd.mata_uang' : '1') . ' AS item_mu';
        $selKartu = ($tbl === 'surat_pkketoll') ? 'd.nokartu,' : "'' AS nokartu,";

        $sql = "
            SELECT h.id_pkk, h.kode_pkk, h.tgl_pengajuan AS tgl, h.type, h.keterangan_pengaju, h.nama_bank, h.ats_nama,
                   h.ttd_2, h.ttd_3, $selMu, s.status AS stat_persetujuan, p.nama AS pengaju,
                   $selKartu d.ket_detail, d.jenis_pengajuan, d.nominal
            FROM $tbl h
            LEFT JOIN surat_list s ON s.id_srt = h.id_pkk AND s.id_kat_surat = $katId
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengguna
            LEFT JOIN $dtlTbl d ON d.id_pkk = h.id_pkk
            WHERE $whereDate
            ORDER BY h.tgl_pengajuan DESC, h.id_pkk DESC, d.id_dpkk ASC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        $jMap = [1 => '[Biaya Operasional] ', 2 => '[Biaya Dinas] ', 3 => '[Catatan Finance] '];
        foreach ($res as $r) {
            $ket = trim((string)$r->ket_detail);
            $nom = $this->_cleanNum($r->nominal);
            if ($ket === '' && $nom == 0) {
                continue;
            }
            if ((int)$r->stat_persetujuan === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ((int)$r->stat_persetujuan === 1) {
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

            $namaTrx = $jenisLabel . ' (' . ($r->type ?: '-') . ') - ' . ($r->keterangan_pengaju ?: $r->ats_nama ?: '-');
            $prefix = isset($jMap[(int)$r->jenis_pengajuan]) ? $jMap[(int)$r->jenis_pengajuan] : '';
            $kartu = trim((string)$r->nokartu) !== '' ? ' [Kartu: ' . trim($r->nokartu) . ']' : '';
            $muCode = !empty($r->item_mu) ? $r->item_mu : (!empty($r->header_mu) ? $r->header_mu : 1);

            $out[] = $this->_row(
                $jenisKey, $jenisLabel, $r->tgl, $r->kode_pkk, $r->pengaju,
                $namaTrx, $prefix . ($ket ?: '-') . $kartu, 1, mata_uang($muCode), $nom,
                $st, $sg, 'surat/show/detail_surat/' . $urlSlug . '/' . $r->id_pkk . '/1'
            );
        }
        return $out;
    }

    // 7. Limit Grab
    private function _fetch_gc($filters)
    {
        $whereDate = $this->_buildDateWhere('h.tgl_pengajuan', $filters);
        $sql = "
            SELECT h.id, h.kode, h.tgl_pengajuan AS tgl, h.kota, h.keperluan,
                   s.status AS stat_persetujuan, p.nama AS pengaju,
                   d.keterangan AS item_ket, d.nominal
            FROM surat_gojek_corp h
            LEFT JOIN surat_list s ON s.id_srt = h.id AND s.id_kat_surat = 3
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengaju
            LEFT JOIN surat_gojek_corp_detail d ON d.id_srt = h.id
            WHERE $whereDate
            ORDER BY h.tgl_pengajuan DESC, h.id DESC, d.id ASC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            if ((int)$r->stat_persetujuan === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ((int)$r->stat_persetujuan === 1) {
                $st = 'Disetujui';
                $sg = 'disetujui';
            } else {
                $st = 'Ditolak';
                $sg = 'ditolak';
            }

            $namaTrx = 'Limit Grab - ' . $this->_cleanParts([$r->keperluan, $r->kota], ' / ');
            $out[] = $this->_row(
                'gc', 'Limit Grab', $r->tgl, $r->kode, $r->pengaju,
                $namaTrx, $r->item_ket ?: $r->keperluan ?: '-', 1, 'Rp', $this->_cleanNum($r->nominal),
                $st, $sg, 'surat/show/detail_surat/gc/' . $r->id . '/1'
            );
        }
        return $out;
    }

    // 8. Pengajuan LTA
    private function _fetch_lta($filters)
    {
        $whereDate = $this->_buildDateWhere('h.created_at', $filters);
        $sql = "
            SELECT h.*, p.nama AS pengaju, t.nama AS teknisi
            FROM helpdesk_lta_pengajuan h
            LEFT JOIN pengguna p ON p.pengguna_id = h.pengguna_id
            LEFT JOIN pengguna t ON t.pengguna_id = h.teknisi_id
            WHERE (h.is_active = 1 OR h.is_active IS NULL) AND $whereDate
            ORDER BY h.created_at DESC, h.id DESC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            if ((int)$r->status === 5) {
                $st = 'Disetujui';
                $sg = 'disetujui';
            } elseif ((int)$r->status === 99) {
                $st = 'Ditolak';
                $sg = 'ditolak';
            } else {
                $st = 'Proses Persetujuan';
                $sg = 'proses';
            }

            $namaTrx = 'Pengajuan LTA - ' . ($r->customer_name ?: '-') . (!empty($r->teknisi) ? ' (Teknisi: ' . $r->teknisi . ')' : '');
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
                'lta', 'Pengajuan LTA', $r->created_at, $r->kode_pengajuan, $r->pengaju,
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
        $sql = "
            SELECT tg.id_tagihan, tg.no_invoice, $dateExpr AS tgl, tg.tagihan_type,
                   tg.nilai_tagihan, tg.biaya_asuransi, tg.total_tagihan, tg.status_approval, tg.status_bayar,
                   p.nama AS pengaju,
                   t.no_sj, t.nama_barang, t.no_resi AS resi_trk, c.nama_customer AS cust_trk,
                   e1.nama_ekspedisi AS eks_trk, e2.nama_ekspedisi AS eks_krm, e3.nama_ekspedisi AS eks_direct,
                   kd.kode AS kode_kirim, kd.nama_customer AS cust_krm, kd.keterangan AS ket_krm, kd.no_resi AS resi_krm, kd.ekspedisi AS eks_krm_txt,
                   cfg.role_label AS pending_role
            FROM tagihan_ekspedisi tg
            LEFT JOIN pengguna p ON p.pengguna_id = tg.created_by
            LEFT JOIN tracking_barang t ON tg.id_tracking = t.id_tracking
            LEFT JOIN customer c ON t.id_customer = c.id_customer
            LEFT JOIN ekspedisi e1 ON t.id_ekspedisi = e1.id_ekspedisi
            LEFT JOIN kirim_dokumen kd ON tg.id_kirim = kd.id
            LEFT JOIN ekspedisi e2 ON kd.id_ekspedisi = e2.id_ekspedisi
            LEFT JOIN ekspedisi e3 ON tg.id_ekspedisi = e3.id_ekspedisi
            LEFT JOIN tagihan_approval_config cfg ON tg.status_approval = cfg.status_code
            WHERE $whereDate
            ORDER BY tgl DESC, tg.id_tagihan DESC
        ";
        $res = $this->db->query($sql)->result();
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
                'tagihan_ekspedisi', 'Tagihan Ekspedisi', $r->tgl, $r->no_invoice ?: ('TAG-' . $r->id_tagihan), $r->pengaju,
                $namaTrx, implode(' | ', $parts), 1, 'Rp', $nom,
                $st, $sg, 'tagihan'
            );
        }
        return $out;
    }

    // 10. SPP Ekspedisi
    private function _fetch_spp_ekspedisi($filters)
    {
        $whereDate = $this->_buildDateWhere('h.created_at', $filters);
        $sql = "
            SELECT h.id_spp, h.no_spp, h.no_invoice, h.atas_nama, h.nama_bank, h.grand_total, h.total_nilai,
                   h.jumlah_tagihan, h.status_approval, h.status_bayar, h.catatan, h.created_at AS tgl,
                   p.nama AS pengaju, cfg.role_label AS pending_role,
                   tg.no_invoice AS tg_inv, tg.total_tagihan AS tg_total, tg.nilai_tagihan AS tg_nilai, tg.tagihan_type,
                   t.no_sj, t.nama_barang, c.nama_customer AS cust_trk,
                   kd.kode AS kode_kirim, kd.nama_customer AS cust_krm, kd.keterangan AS ket_krm,
                   COALESCE(e3.nama_ekspedisi, e1.nama_ekspedisi, e2.nama_ekspedisi, kd.ekspedisi) AS nama_ekspedisi
            FROM spp h
            LEFT JOIN pengguna p ON p.pengguna_id = h.created_by
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
        ";
        $res = $this->db->query($sql)->result();
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
                'spp_ekspedisi', 'SPP Ekspedisi', $r->tgl, $r->no_spp, $r->pengaju,
                $namaTrx, $itemParts ?: '-', 1, 'Rp', $nom,
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
        $sql = "
            SELECT h.id, h.kode, $dateExpr AS tgl, h.nama_customer, h.tujuan, h.nama_barang, h.no_sj,
                   h.gudang_asal, h.rencana_expedisi, h.harga_expedisi, h.status, p.nama AS pengaju,
                   (SELECT GROUP_CONCAT(CONCAT(d.namaekspedisi, ' (Rp ', d.harga, ')') SEPARATOR '; ')
                    FROM surat_aprv_detail d WHERE d.id_aprv = h.id AND d.approval = 1) AS eks_disetujui,
                   (SELECT d.harga FROM surat_aprv_detail d WHERE d.id_aprv = h.id AND d.approval = 1 ORDER BY d.id ASC LIMIT 1) AS harga_disetujui
            FROM surat_aprv h
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengaju
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            if ((int)$r->status === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ((int)$r->status === 1 || (int)$r->status === 2) {
                $st = 'Disetujui';
                $sg = 'disetujui';
            } else {
                $st = 'Ditolak';
                $sg = 'ditolak';
            }

            $namaTrx = 'Approval Ekspedisi - ' . $this->_cleanParts([$r->nama_customer, $r->rencana_expedisi ? 'Eks: ' . $r->rencana_expedisi : ''], ' | ');
            $itemParts = $this->_cleanParts([
                $r->nama_barang ? 'Barang: ' . $r->nama_barang : '',
                $r->no_sj ? 'Berat/Dimensi: ' . $r->no_sj : '',
                $r->gudang_asal ? 'Asal: ' . $r->gudang_asal : '',
                $r->tujuan ? 'Tujuan: ' . $r->tujuan : '',
                $r->eks_disetujui ? 'Dipilih: ' . $r->eks_disetujui : ''
            ], ' | ');
            $nom = $this->_cleanNum($r->harga_disetujui) > 0 ? $this->_cleanNum($r->harga_disetujui) : $this->_cleanNum($r->harga_expedisi);

            $out[] = $this->_row(
                'appeks', 'Approval Ekspedisi', $r->tgl, $r->kode, $r->pengaju,
                $namaTrx, $itemParts ?: '-', 1, 'Rp', $nom,
                $st, $sg, 'surat_new/show/detail/appeks/' . $r->id
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
            SELECT h.*, $dateExpr AS tgl, p.nama AS pengaju
            FROM approval_forwarder h
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengguna
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ")->result();

        if (empty($headers)) {
            return [];
        }

        $ids = array_map(function ($x) { return (int)$x->id; }, $headers);
        $detailsRaw = $this->db->query("
            SELECT d.*, f.nama AS nama_forwarder
            FROM approval_forwarder_detail d
            LEFT JOIN forwarder f ON f.id = d.id_forwarder
            WHERE d.id_app IN (" . implode(',', $ids) . ")
            ORDER BY d.dipilih DESC, d.id ASC
        ")->result();

        $costFields = [
            'asal_thc', 'asal_bl_fee', 'asal_vgm', 'asal_agaency_fee', 'asal_handling_fee',
            'asal_transportasi', 'asal_loading', 'asal_custom', 'asal_pickup', 'asal_seal',
            'asal_shipping', 'asal_cfs', 'asal_dg', 'asal_psa', 'asal_other',
            'freight_ocean', 'freight_air',
            'tuj_cfs', 'tuj_doc', 'tuj_agency_fee', 'tuj_handling', 'tuj_do',
            'tuj_admin', 'tuj_devanning', 'tuj_fordwarding_fee', 'tuj_mechanics', 'tuj_other',
            'cust_clearance', 'cust_red_line', 'cust_handling', 'cust_admin_fee', 'cust_pib_fee',
            'cust_transfer', 'cust_storage', 'cust_lift', 'cust_asu', 'cust_other',
            'oth_do', 'oth_storage', 'oth_trucking', 'oth_handling', 'oth_buruh', 'oth_adm', 'oth_other',
            'ins_nilai', 'ppn'
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
            if ((int)$r->status === 0) {
                $st = 'Proses Pengajuan';
                $sg = 'proses';
            } elseif ((int)$r->status === 1) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ((int)$r->status === 2) {
                $st = 'Disetujui Director of Corp Planning';
                $sg = 'proses';
            } elseif ((int)$r->status === 3) {
                $st = 'Disetujui Director';
                $sg = 'disetujui';
            } else {
                $st = 'Ditolak';
                $sg = 'ditolak';
            }

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
                $chosen ? 'Forwarder Dipilih: ' . $chosen->nama_forwarder : (!empty($allForNames) ? 'Perbandingan: ' . implode(', ', $allForNames) : ''),
                $r->nilai_inv ? 'Nilai Inv: ' . $r->mata_nilai_inv . ' ' . $r->nilai_inv : '',
                $r->keterangan
            ], ' | ');

            $nom = $chosen ? $chosen->_total_biaya : 0.0;
            $out[] = $this->_row(
                'forwarder', 'Approval Forwarder', $r->tgl, $r->kode, $r->pengaju,
                $namaTrx, $itemParts ?: '-', 1, 'Rp', $nom,
                $st, $sg, 'forwarder/show/detail_data/' . $id
            );
        }
        return $out;
    }

    // 13. Approval Harga
    private function _fetch_approval_harga($filters)
    {
        $whereDate = $this->_buildDateWhere('h.tgl', $filters);
        $sql = "
            SELECT h.id_approval, h.kode, h.tgl, h.nama AS nama_marketing, h.nama_customer, h.sistem_pembayaran,
                   h.ttd_gm, h.pengajuan_ttd_1, h.ttd_2, h.ttd_1,
                   s.status AS stat_persetujuan, p.nama AS pengaju,
                   d.nama_barang, d.acuan_hrg, d.hrg_ditawarkan, d.approvall, d.catatan AS item_catatan
            FROM surat_approval h
            LEFT JOIN surat_list s ON s.id_srt = h.id_approval AND s.id_kat_surat = 8
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengguna
            LEFT JOIN surat_approval_detail d ON d.id_approval = h.id_approval
            WHERE $whereDate
            ORDER BY h.tgl DESC, h.id_approval DESC, d.id ASC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            if ((int)$r->stat_persetujuan === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ((int)$r->stat_persetujuan === 1) {
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

            $namaTrx = 'Approval Harga - ' . $this->_cleanParts([$r->nama_customer, $r->nama_marketing ? 'Mkt: ' . $r->nama_marketing : ''], ' | ');
            $itemParts = $this->_cleanParts([
                $r->nama_barang,
                $r->acuan_hrg ? 'Acuan: ' . $r->acuan_hrg : '',
                $r->sistem_pembayaran ? 'Bayar: ' . $r->sistem_pembayaran : '',
                $r->item_catatan ? 'Catatan: ' . $r->item_catatan : ''
            ], ' | ');

            $out[] = $this->_row(
                'approval_harga', 'Approval Harga', $r->tgl, $r->kode, $r->pengaju ?: $r->nama_marketing,
                $namaTrx, $itemParts ?: '-', 1, 'Rp', $this->_cleanNum($r->hrg_ditawarkan),
                $st, $sg, 'surat/show/detail_surat/approval/' . $r->id_approval
            );
        }
        return $out;
    }

    // 14. Approval PO
    private function _fetch_approval_po($filters)
    {
        $dateExpr = "COALESCE(NULLIF(h.tanggal, '0000-00-00'), DATE(h.created_at))";
        $whereDate = $this->_buildDateWhere($dateExpr, $filters);
        $sql = "
            SELECT h.*, $dateExpr AS tgl, p.nama AS pengaju, pl.identitas_pelanggan
            FROM surat_po h
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengaju
            LEFT JOIN pelanggan pl ON pl.id_pelanggan = h.id_pelanggan
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            if ((string)$r->status === '0' || $r->status === null) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ((string)$r->status === '1') {
                $st = 'Disetujui Director';
                $sg = 'disetujui';
            } elseif ((string)$r->status === '3') {
                $st = 'Disetujui HR & Legal';
                $sg = 'proses';
            } elseif ((string)$r->status === '5') {
                $st = 'Disetujui General Manager';
                $sg = 'proses';
            } else {
                $st = 'Ditolak';
                $sg = 'ditolak';
            }

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
                'approval_po', 'Approval PO', $r->tgl, $r->kode, $r->pengaju,
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
        $sql = "
            SELECT h.*, $dateExpr AS tgl, p1.nama AS pengaju, p2.nama AS marketing, pl.identitas_pelanggan AS nama_customer
            FROM approval_faktur_pajak h
            LEFT JOIN pengguna p1 ON p1.pengguna_id = h.id_pengaju
            LEFT JOIN pengguna p2 ON p2.pengguna_id = h.id_marketing
            LEFT JOIN pelanggan pl ON pl.id_pelanggan = h.id_customer
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            if ((int)$r->status === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ((int)$r->status === 1) {
                $st = 'Disetujui General Manager';
                $sg = 'proses';
            } elseif ((int)$r->status === 2) {
                $st = 'Disetujui Staff Tax';
                $sg = 'proses';
            } elseif ((int)$r->status === 3) {
                $st = 'Disetujui Director of Corp Planning';
                $sg = 'proses';
            } elseif ((int)$r->status === 4) {
                $st = 'Disetujui Director';
                $sg = 'disetujui';
            } else {
                $st = 'Ditolak';
                $sg = 'ditolak';
            }

            $dpp = $this->_cleanNum($r->dpp);
            $ppn = $this->_cleanNum($r->ppn);
            $namaTrx = 'Approval Faktur Pajak - ' . $this->_cleanParts([$r->nama_customer, $r->no_po ? 'PO: ' . $r->no_po : ''], ' | ');
            $ditrx = $this->_cleanParts([
                'DPP: Rp ' . number_format($dpp, 0, ',', '.'),
                'PPN: Rp ' . number_format($ppn, 0, ',', '.'),
                $r->pembayaran ? 'Pembayaran: ' . $r->pembayaran : '',
                $r->marketing ? 'Mkt: ' . $r->marketing : '',
                $r->alasan ? 'Alasan: ' . $r->alasan : ''
            ], ' | ');

            $out[] = $this->_row(
                'app_pajak', 'Approval Faktur Pajak', $r->tgl, $r->kode, $r->pengaju,
                $namaTrx, $ditrx, 1, 'Rp', $dpp + $ppn,
                $st, $sg, 'surat_new/show/detail/app_pajak/' . $r->id
            );
        }
        return $out;
    }

    // 16. Approval Director
    private function _fetch_appdir($filters)
    {
        $whereDate = $this->_buildDateWhere('h.created_at', $filters);
        $sql = "
            SELECT h.*, p.nama AS pengaju
            FROM approval_director h
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengaju
            WHERE $whereDate
            ORDER BY h.created_at DESC, h.id DESC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            if ((int)$r->status === 0) {
                $st = 'Baru Diajukan';
                $sg = 'proses';
            } elseif ((int)$r->status === 1) {
                $st = 'Disetujui';
                $sg = 'disetujui';
            } else {
                $st = 'Ditolak';
                $sg = 'ditolak';
            }

            $jenisDok = ((int)$r->jenis === 1) ? 'Tempel (Soft File)' : (((int)$r->jenis === 2) ? 'Cap Basah (Hard File)' : '');
            $namaTrx = 'Approval Director - ' . ($r->nama_dokumen ?: '-');
            $ditrx = $this->_cleanParts([$r->ket, $jenisDok ? '[' . $jenisDok . ']' : ''], ' ');

            $out[] = $this->_row(
                'appdir', 'Approval Director', $r->created_at, $r->kode, $r->pengaju,
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
        $sql = "
            SELECT h.*, $dateExpr AS tgl, s.status AS stat_persetujuan, p.nama AS pengaju
            FROM surat_kunjungan_gudang h
            LEFT JOIN surat_list s ON s.id_srt = h.id AND s.id_kat_surat = 6
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengguna
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            if ((int)$r->stat_persetujuan === 0) {
                if ((int)$r->persetujuan_ga === 1) { $st = 'Disetujui GA'; $sg = 'proses'; }
                elseif ((int)$r->persetujuan_ga === 2) { $st = 'Ditolak GA'; $sg = 'ditolak'; }
                else { $st = 'Baru Diajukan'; $sg = 'proses'; }
            } elseif ((int)$r->stat_persetujuan === 1) {
                $st = 'Pengajuan Disetujui'; $sg = 'disetujui';
            } elseif ((int)$r->stat_persetujuan === 2) {
                $st = 'Laporan Diajukan'; $sg = 'proses';
            } elseif ((int)$r->stat_persetujuan === 3) {
                $st = 'Laporan Diterima GA'; $sg = 'disetujui';
            } else {
                $st = 'Ditolak'; $sg = 'ditolak';
            }

            $out[] = $this->_row(
                'kg', 'Kunjungan Gudang', $r->tgl, $r->kode, $r->pengaju,
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
        // surat_pd (kat 9 = Marketing, kat 12 = Karyawan)
        $where1 = $this->_buildDateWhere('h.tgl_pengajuan', $filters);
        $rows1 = $this->db->query("
            SELECT h.id_pd, h.kode, h.id_kat_surat, h.tgl_pengajuan AS tgl, h.wilayah_dinas, h.tujuan, h.mulai, h.akhir,
                   h.transportasi, h.persetujuan_ga, s.status AS stat_persetujuan, p.nama AS pengaju,
                   (SELECT GROUP_CONCAT(CONCAT(d.nama_customer, ' - ', d.tujuan_dinas) SEPARATOR '; ')
                    FROM surat_pd_detail d WHERE d.id_pd = h.id_pd) AS detail_kunjungan
            FROM surat_pd h
            LEFT JOIN surat_list s ON s.id_srt = h.id_pd AND s.id_kat_surat = h.id_kat_surat
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengguna
            WHERE $where1
            ORDER BY h.tgl_pengajuan DESC, h.id_pd DESC
        ")->result();

        foreach ($rows1 as $r) {
            $subLabel = ((int)$r->id_kat_surat === 12) ? 'Permintaan Dinas Karyawan' : 'Permintaan Dinas Marketing';
            $slug = ((int)$r->id_kat_surat === 12) ? 'pd_karyawan' : 'pd';
            list($st, $sg) = $this->_mapDinasStatus($r->stat_persetujuan, $r->persetujuan_ga);
            $ditrx = $this->_cleanParts([
                $r->tujuan,
                ($r->mulai && $r->akhir) ? 'Jadwal: ' . $r->mulai . ' s/d ' . $r->akhir : '',
                $r->transportasi ? 'Transport: ' . $r->transportasi : '',
                $r->detail_kunjungan ? 'Kunjungan: ' . $r->detail_kunjungan : ''
            ], ' | ');
            $out[] = $this->_row(
                'pd', 'Permintaan Dinas', $r->tgl, $r->kode, $r->pengaju,
                $subLabel . ($r->wilayah_dinas ? ' - ' . $r->wilayah_dinas : ''), $ditrx ?: '-',
                1, 'Rp', 0, $st, $sg, 'surat/show/detail_surat/' . $slug . '/' . $r->id_pd . '/1'
            );
        }

        // surat_pdt (kat 11 = Teknisi)
        $rows2 = $this->db->query("
            SELECT h.id_pd, h.kode, h.tgl_pengajuan AS tgl, h.wilayah_dinas, h.tujuan, h.mulai, h.akhir,
                   h.transportasi, h.persetujuan_ga, s.status AS stat_persetujuan, p.nama AS pengaju,
                   (SELECT GROUP_CONCAT(CONCAT(d.nama_customer, ' - ', d.tujuan_dinas) SEPARATOR '; ')
                    FROM surat_pdt_detail d WHERE d.id_pd = h.id_pd) AS detail_kunjungan
            FROM surat_pdt h
            LEFT JOIN surat_list s ON s.id_srt = h.id_pd AND s.id_kat_surat = 11
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengguna
            WHERE $where1
            ORDER BY h.tgl_pengajuan DESC, h.id_pd DESC
        ")->result();

        foreach ($rows2 as $r) {
            list($st, $sg) = $this->_mapDinasStatus($r->stat_persetujuan, $r->persetujuan_ga);
            $ditrx = $this->_cleanParts([
                $r->tujuan,
                ($r->mulai && $r->akhir) ? 'Jadwal: ' . $r->mulai . ' s/d ' . $r->akhir : '',
                $r->transportasi ? 'Transport: ' . $r->transportasi : '',
                $r->detail_kunjungan ? 'Kunjungan: ' . $r->detail_kunjungan : ''
            ], ' | ');
            $out[] = $this->_row(
                'pd', 'Permintaan Dinas', $r->tgl, $r->kode, $r->pengaju,
                'Permintaan Dinas Teknisi' . ($r->wilayah_dinas ? ' - ' . $r->wilayah_dinas : ''), $ditrx ?: '-',
                1, 'Rp', 0, $st, $sg, 'surat/show/detail_surat/pd_teknisi/' . $r->id_pd . '/1'
            );
        }

        return $out;
    }

    // 19. Surat Dinas
    private function _fetch_sd($filters)
    {
        $dateExpr = "COALESCE(NULLIF(h.tgl_pengajuan, '0000-00-00'), NULLIF(h.tgl_dinas, '0000-00-00'))";
        $whereDate = $this->_buildDateWhere($dateExpr, $filters);
        $sql = "
            SELECT h.*, $dateExpr AS tgl, s.status AS stat_persetujuan, p.nama AS pengaju
            FROM surat_pd_dinas h
            LEFT JOIN surat_list s ON s.id_srt = h.id AND s.id_kat_surat = 10
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengguna
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            list($st, $sg) = $this->_mapDinasStatus($r->stat_persetujuan, $r->persetujuan_ga);
            $ditrx = $this->_cleanParts([
                $r->perihal,
                $r->lokasi_dinas ? 'Lokasi: ' . $r->lokasi_dinas : '',
                ($r->tgl_dinas) ? 'Tgl Dinas: ' . $r->tgl_dinas . ($r->tgl_dinas_akhir ? ' s/d ' . $r->tgl_dinas_akhir : '') : ''
            ], ' | ');
            $out[] = $this->_row(
                'sd', 'Surat Dinas', $r->tgl, $r->kode, $r->pengaju ?: $r->nama_karyawan,
                'Surat Dinas - ' . ($r->nama_karyawan ?: $r->pengaju ?: '-'), $ditrx ?: '-',
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
        $sql = "
            SELECT h.*, $dateExpr AS tgl, p.nama AS pengaju
            FROM surat_kendaraan h
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengaju
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            if ((int)$r->status === 0) { $st = 'Baru Diajukan'; $sg = 'proses'; }
            elseif ((int)$r->status === 1) { $st = 'Disetujui Head of Technician'; $sg = 'proses'; }
            elseif ((int)$r->status === 2) { $st = 'Disetujui General Manager'; $sg = 'disetujui'; }
            else { $st = 'Ditolak'; $sg = 'ditolak'; }

            $namaTrx = 'Penggunaan Kendaraan - ' . $this->_cleanParts([$r->mobil, $r->nopol], ' ');
            $ditrx = $this->_cleanParts([$r->keperluan, $r->lama ? 'Lama: ' . $r->lama . ' Hari' : ''], ' | ');
            $out[] = $this->_row(
                'kendaraan', 'Penggunaan Kendaraan', $r->tgl, $r->kode, $r->pengaju,
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
        $sql = "
            SELECT h.*, $dateExpr AS tgl, p.nama AS pengaju
            FROM surat_meetingroom h
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengaju
            WHERE $whereDate
            ORDER BY tgl DESC, h.id DESC
        ";
        $res = $this->db->query($sql)->result();
        $out = [];
        foreach ($res as $r) {
            if ((int)$r->status === 0) { $st = 'Baru Diajukan'; $sg = 'proses'; }
            elseif ((int)$r->status === 1) { $st = 'Disetujui GA'; $sg = 'proses'; }
            elseif ((int)$r->status === 2) { $st = 'Disetujui'; $sg = 'disetujui'; }
            else { $st = 'Ditolak'; $sg = 'ditolak'; }

            $ditrx = $this->_cleanParts([$r->perihal, ($r->jam_mulai || $r->jam_akhir) ? 'Pukul: ' . $r->jam_mulai . ' s/d ' . $r->jam_akhir : ''], ' | ');
            $out[] = $this->_row(
                'meetingroom', 'Ruang Meeting', $r->tgl, $r->kode, $r->pengaju,
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
        // surat_izin_jam_kerja (jenis 1 = Izin Jam Kerja, jenis 2 = Izin Meninggalkan Pekerjaan)
        $dateExpr1 = "COALESCE(NULLIF(DATE(h.tgl_pengajuan), '0000-00-00'), NULLIF(h.tanggal, '0000-00-00'), DATE(h.created_at))";
        $where1 = $this->_buildDateWhere($dateExpr1, $filters);
        $rows1 = $this->db->query("
            SELECT h.*, $dateExpr1 AS tgl, p.nama AS pengaju
            FROM surat_izin_jam_kerja h
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengaju
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
                'izin_cuti', 'Izin & Cuti', $r->tgl, $r->kode_ijk, $r->pengaju,
                $subLabel . ($r->jenis_izin ? ' - ' . $r->jenis_izin : ''),
                $this->_cleanParts([$r->alasan, $waktu ? 'Waktu: ' . $waktu : ''], ' | ') ?: '-',
                1, 'Rp', 0, $st, $sg, 'surat_part_two/show/detail/' . $slug . '/' . $r->id
            );
        }

        // surat_cuti_tahunan
        $dateExpr2 = "COALESCE(NULLIF(DATE(h.tgl_pengajuan), '0000-00-00'), NULLIF(h.tgl_awal, '0000-00-00'), DATE(h.created_at))";
        $where2 = $this->_buildDateWhere($dateExpr2, $filters);
        $rows2 = $this->db->query("
            SELECT h.*, $dateExpr2 AS tgl, p.nama AS pengaju
            FROM surat_cuti_tahunan h
            LEFT JOIN pengguna p ON p.pengguna_id = h.id_pengaju
            WHERE $where2
            ORDER BY tgl DESC, h.id DESC
        ")->result();

        foreach ($rows2 as $r) {
            list($st, $sg) = $this->_mapIzinStatus($r->status);
            $waktu = $r->tgl_awal ? $r->tgl_awal . ' s/d ' . $r->tgl_akhir . ($r->total ? ' (' . $r->total . ')' : '') : '';
            $out[] = $this->_row(
                'izin_cuti', 'Izin & Cuti', $r->tgl, $r->kode_cuti, $r->pengaju,
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
