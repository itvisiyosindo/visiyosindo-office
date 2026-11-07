<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property Md_acc_pemasok $md_acc_pemasok
 * @property Md_acc_bukti_lapor_pajak $md_acc_bukti_lapor_pajak
 * @property Md_acc_laporan_keuangan $md_acc_laporan_keuangan
 * @property Md_acc_sk_ketentuan $md_acc_sk_ketentuan
 * @property Md_surat_new $md_surat_new
 */
class Dashboard_accounting extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('md_acc_pemasok');
        $this->load->model('md_acc_bukti_lapor_pajak');
        $this->load->model('md_acc_laporan_keuangan');
        $this->load->model('md_acc_sk_ketentuan');
        $this->load->model('md_surat_new');
        $this->load->helper('url');
        $this->load->helper('form');

        // Access restriction
        if (!isAccountingUser() && !isAdmin() && !isHrd()) {
            redirect(base_url('dashboard'));
        }
    }

    private function id_navbar()
    {
        return "accounting";
    }

    public function index()
    {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'dashboard_accounting';
        $page_data['page_title']    = 'Dashboard Accounting & Tax';
        $page_data['tahun_sekarang'] = date('Y');

        // 1. STATS PEMASOK
        $stats_pemasok = $this->md_acc_pemasok->getPemasokStats();
        $page_data['total_pemasok'] = (int)($stats_pemasok->total ?? 0);
        $page_data['pemasok_aktif'] = (int)($stats_pemasok->aktif ?? 0);
        $page_data['pemasok_baru']  = (int)($stats_pemasok->baru ?? 0);
        $page_data['pemasok_ref']   = (int)($stats_pemasok->referensi ?? 0);

        // 2. STATS APPROVAL FAKTUR PAJAK
        $page_data['total_faktur_pajak'] = $this->db
            ->count_all_results('approval_faktur_pajak');

        $page_data['faktur_pajak_approved'] = $this->db
            ->where('status', 4)
            ->count_all_results('approval_faktur_pajak');

        $page_data['faktur_pajak_pending'] = $this->db
            ->where_in('status', [0, 1, 2, 3])
            ->count_all_results('approval_faktur_pajak');

        // 3. STATS BUKTI LAPOR PAJAK
        $page_data['total_bukti_lapor'] = $this->db
            ->where('status', 1)
            ->count_all_results('acc_bukti_lapor_pajak');

        $page_data['bukti_lapor_tahun_ini'] = $this->db
            ->where('status', 1)
            ->where('tahun_pelaporan', date('Y'))
            ->count_all_results('acc_bukti_lapor_pajak');

        // 4. STATS LAPORAN KEUANGAN & SK
        $page_data['total_lap_keuangan'] = $this->db
            ->where('status', 1)
            ->count_all_results('acc_laporan_keuangan');

        $page_data['total_sk_ketentuan'] = $this->db
            ->where('status', 1)
            ->count_all_results('acc_sk_ketentuan');

        // 5. DAFTAR APPROVAL FAKTUR PAJAK TERBARU (10 Terakhir)
        $page_data['faktur_pajak_terbaru'] = $this->db
            ->select('
                fp.id,
                fp.tanggal,
                fp.kode,
                fp.no_po,
                fp.pembayaran,
                fp.status,
                fp.link_lampiran,
                fp.created_at,
                COALESCE(p.nama, "-") as nama_pengaju,
                CASE WHEN fp.id_marketing = 1 THEN "Office / Kantor Pusat" ELSE COALESCE(m.nama, "-") END as nama_marketing,
                COALESCE(c.identitas_pelanggan, "-") as nama_customer
            ', FALSE)
            ->from('approval_faktur_pajak fp')
            ->join('pengguna p', 'fp.id_pengaju = p.pengguna_id', 'left')
            ->join('pengguna m', 'fp.id_marketing = m.pengguna_id', 'left')
            ->join('pelanggan c', 'fp.id_customer = c.id_pelanggan', 'left')
            ->order_by('fp.id', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // 6. DAFTAR BUKTI LAPOR PAJAK TERBARU (10 Terakhir)
        $page_data['bukti_lapor_terbaru'] = $this->db
            ->select('
                b.id,
                b.kategori,
                b.nama_dokumen,
                b.tahun_pelaporan,
                b.tanggal_lapor,
                b.batas_akhir,
                b.link_dokumen,
                b.created_at
            ')
            ->from('acc_bukti_lapor_pajak b')
            ->where('b.status', 1)
            ->order_by('b.id', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // 7. DAFTAR LAPORAN KEUANGAN TERBARU (10 Terakhir)
        $page_data['lap_keuangan_terbaru'] = $this->db
            ->select('
                lk.id,
                lk.nama_dokumen,
                lk.jenis_dokumen,
                lk.tahun_pelaporan,
                lk.keperluan_dokumen,
                lk.link_dokumen,
                lk.created_at
            ')
            ->from('acc_laporan_keuangan lk')
            ->where('lk.status', 1)
            ->order_by('lk.id', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // 8. DAFTAR SK KETENTUAN TERBARU (10 Terakhir)
        $page_data['sk_ketentuan_terbaru'] = $this->db
            ->select('
                sk.id,
                sk.nama_dokumen,
                sk.tanggal_dokumen,
                sk.masa_berlaku,
                sk.link_dokumen,
                sk.created_at
            ')
            ->from('acc_sk_ketentuan sk')
            ->where('sk.status', 1)
            ->order_by('sk.id', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // 9. DISTRIBUSI ARSIP DOKUMEN & TRANSAKSI
        $page_data['distribusi_accounting'] = [
            'Pemasok & Rekanan'    => (int)$page_data['total_pemasok'],
            'Approval Faktur Pajak' => (int)$page_data['total_faktur_pajak'],
            'Bukti Lapor Pajak'    => (int)$page_data['total_bukti_lapor'],
            'Laporan Keuangan'     => (int)$page_data['total_lap_keuangan'],
            'SK & Ketentuan'       => (int)$page_data['total_sk_ketentuan']
        ];

        $this->load->view('index', $page_data);
    }

    public function chart_data_accounting()
    {
        $tahun = $this->input->get('tahun') ?: date('Y');

        // 1. Approval Faktur Pajak per bulan
        $fp_monthly = $this->db
            ->select('MONTH(tanggal) as bulan, COUNT(id) as total')
            ->from('approval_faktur_pajak')
            ->where('YEAR(tanggal)', $tahun)
            ->group_by('MONTH(tanggal)')
            ->get()
            ->result_array();

        // 2. Bukti Lapor Pajak per bulan
        $pajak_monthly = $this->db
            ->select('MONTH(tanggal_lapor) as bulan, COUNT(id) as total')
            ->from('acc_bukti_lapor_pajak')
            ->where('status', 1)
            ->where('tahun_pelaporan', $tahun)
            ->group_by('MONTH(tanggal_lapor)')
            ->get()
            ->result_array();

        // 3. Laporan Keuangan per tahun
        $lap_by_type = $this->db
            ->select('jenis_dokumen, COUNT(id) as total')
            ->from('acc_laporan_keuangan')
            ->where('status', 1)
            ->where('tahun_pelaporan', $tahun)
            ->group_by('jenis_dokumen')
            ->get()
            ->result_array();

        $fp_map    = array_fill(1, 12, 0);
        $pajak_map = array_fill(1, 12, 0);

        foreach ($fp_monthly as $row) {
            $b = (int)$row['bulan'];
            if ($b >= 1 && $b <= 12) {
                $fp_map[$b] = (int)$row['total'];
            }
        }

        foreach ($pajak_monthly as $row) {
            $b = (int)$row['bulan'];
            if ($b >= 1 && $b <= 12) {
                $pajak_map[$b] = (int)$row['total'];
            }
        }

        $bulan_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $response = [
            'labels' => $bulan_labels,
            'faktur_pajak' => array_values($fp_map),
            'bukti_lapor'  => array_values($pajak_map),
            'jenis_laporan' => $lap_by_type
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * Endpoint JSON untuk Grafik TradingView Financial Analytics (Omzet PO Masuk & Invoice)
     */
    public function tradingview_financial_data()
    {
        $this->output->set_content_type('application/json');

        $tahun    = strtoupper(trim($this->input->get('tahun') ?: date('Y')));
        $interval = strtoupper(trim($this->input->get('interval') ?: '1M')); // 1D, 1W, 1M
        $source   = strtoupper(trim($this->input->get('source') ?: 'PO'));   // PO, INV, ALL, CUMULATIVE

        if (!in_array($interval, ['1D', '1W', '1M'], true)) {
            $interval = '1M';
        }

        $has_po       = $this->db->table_exists('po_masuk');
        $has_po_item  = $this->db->table_exists('po_masuk_item');
        $has_inv      = $this->db->table_exists('invoice');
        $has_inv_item = $this->db->table_exists('detail_barang_invoice');

        // 1. Ambil transaksi PO Masuk secara aman (cek ketersediaan kolom)
        $po_rows = [];
        if ($has_po) {
            $po_fields = array_flip($this->db->list_fields('po_masuk'));
            $nom_parts = [];
            if (isset($po_fields['nominal_total_akhir'])) $nom_parts[] = "NULLIF(p.nominal_total_akhir, 0)";
            if (isset($po_fields['nominal_po']))          $nom_parts[] = "NULLIF(p.nominal_po, 0)";
            if (isset($po_fields['nominal_subtotal']))    $nom_parts[] = "NULLIF(p.nominal_subtotal, 0)";
            if ($has_po_item) {
                $nom_parts[] = "(SELECT COALESCE(SUM(i.total_harga), 0) FROM po_masuk_item i WHERE i.id_po = p.id_po)";
            }
            $nom_parts[] = "0";
            $po_nom_expr = "COALESCE(" . implode(", ", $nom_parts) . ")";

            $where_year_po = ($tahun !== 'ALL' && is_numeric($tahun))
                ? "AND YEAR(p.tanggal_po) = " . (int)$tahun
                : "";

            $where_del = isset($po_fields['deleted']) ? "AND p.deleted = 0" : "";
            $where_sts = isset($po_fields['status']) ? "AND p.status != 'ditolak'" : "";

            $sql_po = "
                SELECT 
                    p.tanggal_po AS tgl,
                    {$po_nom_expr} AS nominal,
                    p.no_po AS kode_dok,
                    COALESCE(NULLIF(p.nama_customer, ''), c.nama_customer, '-') AS customer_nama
                FROM po_masuk p
                LEFT JOIN customer c ON p.id_customer = c.id_customer
                WHERE p.tanggal_po IS NOT NULL
                  AND p.tanggal_po != '0000-00-00'
                  {$where_del}
                  {$where_sts}
                  {$where_year_po}
                ORDER BY p.tanggal_po ASC, p.id_po ASC
            ";
            $q_po = $this->db->query($sql_po);
            if ($q_po) {
                $po_rows = $q_po->result_array();
            }
        }

        // 2. Ambil transaksi Invoice secara aman (cek ketersediaan kolom)
        $inv_rows = [];
        if ($has_inv) {
            $inv_fields = array_flip($this->db->list_fields('invoice'));
            $inv_parts = [];
            if (isset($inv_fields['total_keseluruhan'])) {
                $inv_parts[] = "NULLIF(CAST(REPLACE(REPLACE(inv.total_keseluruhan, '.', ''), ',', '') AS DECIMAL(18,2)), 0)";
            }
            if (isset($inv_fields['total'])) {
                $inv_parts[] = "NULLIF(CAST(REPLACE(REPLACE(inv.total, '.', ''), ',', '') AS DECIMAL(18,2)), 0)";
            }
            if (isset($inv_fields['sub_total'])) {
                $inv_parts[] = "NULLIF(CAST(REPLACE(REPLACE(inv.sub_total, '.', ''), ',', '') AS DECIMAL(18,2)), 0)";
            }
            if ($has_inv_item) {
                $inv_parts[] = "(SELECT COALESCE(SUM(CAST(REPLACE(REPLACE(dbi.harga_total, '.', ''), ',', '') AS DECIMAL(18,2))), 0) FROM detail_barang_invoice dbi WHERE dbi.id_invoice = inv.id_invoice AND dbi.status = 1)";
            }
            $inv_parts[] = "0";
            $inv_nom_expr = "COALESCE(" . implode(", ", $inv_parts) . ")";

            $where_year_inv = ($tahun !== 'ALL' && is_numeric($tahun))
                ? "AND YEAR(inv.tgl_invoice) = " . (int)$tahun
                : "";

            $sql_inv = "
                SELECT 
                    inv.tgl_invoice AS tgl,
                    {$inv_nom_expr} AS nominal,
                    inv.no_invoice AS kode_dok,
                    COALESCE(c.nama_customer, '-') AS customer_nama
                FROM invoice inv
                LEFT JOIN customer c ON inv.id_customer = c.id_customer
                WHERE inv.status = 1
                  AND inv.tgl_invoice IS NOT NULL
                  AND inv.tgl_invoice != '0000-00-00'
                  {$where_year_inv}
                ORDER BY inv.tgl_invoice ASC, inv.id_invoice ASC
            ";
            $q_inv = $this->db->query($sql_inv);
            if ($q_inv) {
                $inv_rows = $q_inv->result_array();
            }
        }

        // Helper untuk memetakan tanggal ke bucket key (YYYY-MM-DD) sesuai interval TradingView
        $getBucketDate = function ($dateStr) use ($interval) {
            $ts = strtotime($dateStr);
            if (!$ts || $ts <= 0) return null;
            if ($interval === '1M') {
                return date('Y-m-01', $ts);
            } elseif ($interval === '1W') {
                // Senin pada minggu tersebut
                $dow = (int)date('N', $ts); // 1 (Mon) .. 7 (Sun)
                $monday = strtotime('-' . ($dow - 1) . ' days', $ts);
                return date('Y-m-d', $monday);
            } else {
                return date('Y-m-d', $ts);
            }
        };

        // Siapkan kerangka waktu dasar agar timeline kontinyu & rapi di TradingView
        $buckets = [];
        if ($tahun !== 'ALL' && is_numeric($tahun)) {
            $yr = (int)$tahun;
            $max_month = ($yr == (int)date('Y')) ? (int)date('n') : 12;
            if ($interval === '1M') {
                for ($m = 1; $m <= 12; $m++) {
                    $k = sprintf('%04d-%02d-01', $yr, $m);
                    $buckets[$k] = [
                        'po_val' => 0.0, 'po_cnt' => 0, 'po_vals' => [],
                        'inv_val' => 0.0, 'inv_cnt' => 0, 'inv_vals' => [],
                        'top_docs' => []
                    ];
                }
            } elseif ($interval === '1W') {
                $startTs = strtotime("$yr-01-01");
                $endTs   = ($yr == (int)date('Y')) ? time() : strtotime("$yr-12-31");
                $dow = (int)date('N', $startTs);
                $cur = strtotime('-' . ($dow - 1) . ' days', $startTs);
                while ($cur <= $endTs) {
                    $k = date('Y-m-d', $cur);
                    $buckets[$k] = [
                        'po_val' => 0.0, 'po_cnt' => 0, 'po_vals' => [],
                        'inv_val' => 0.0, 'inv_cnt' => 0, 'inv_vals' => [],
                        'top_docs' => []
                    ];
                    $cur = strtotime('+7 days', $cur);
                }
            }
        }

        // Masukkan data PO ke dalam bucket
        $total_po_nominal = 0.0;
        $total_po_count   = 0;
        foreach ($po_rows as $r) {
            $k = $getBucketDate($r['tgl']);
            if (!$k) continue;
            $val = max(0, (float)$r['nominal']);
            if (!isset($buckets[$k])) {
                $buckets[$k] = [
                    'po_val' => 0.0, 'po_cnt' => 0, 'po_vals' => [],
                    'inv_val' => 0.0, 'inv_cnt' => 0, 'inv_vals' => [],
                    'top_docs' => []
                ];
            }
            $buckets[$k]['po_val'] += $val;
            $buckets[$k]['po_cnt'] += 1;
            $buckets[$k]['po_vals'][] = $val;
            if (count($buckets[$k]['top_docs']) < 3) {
                $buckets[$k]['top_docs'][] = 'PO: ' . ($r['kode_dok'] ?: '-') . ' (' . ($r['customer_nama'] ?: '-') . ')';
            }
            $total_po_nominal += $val;
            $total_po_count++;
        }

        // Masukkan data Invoice ke dalam bucket
        $total_inv_nominal = 0.0;
        $total_inv_count   = 0;
        foreach ($inv_rows as $r) {
            $k = $getBucketDate($r['tgl']);
            if (!$k) continue;
            $val = max(0, (float)$r['nominal']);
            if (!isset($buckets[$k])) {
                $buckets[$k] = [
                    'po_val' => 0.0, 'po_cnt' => 0, 'po_vals' => [],
                    'inv_val' => 0.0, 'inv_cnt' => 0, 'inv_vals' => [],
                    'top_docs' => []
                ];
            }
            $buckets[$k]['inv_val'] += $val;
            $buckets[$k]['inv_cnt'] += 1;
            $buckets[$k]['inv_vals'][] = $val;
            if (count($buckets[$k]['top_docs']) < 3) {
                $buckets[$k]['top_docs'][] = 'INV: ' . ($r['kode_dok'] ?: '-') . ' (' . ($r['customer_nama'] ?: '-') . ')';
            }
            $total_inv_nominal += $val;
            $total_inv_count++;
        }

        // Jika interval 1D dan hanya ada sedikit titik, tambahkan tanggal awal bulan agar garis tetap terbentuk rapi
        if ($interval === '1D' && count($buckets) < 2) {
            $yr = ($tahun !== 'ALL' && is_numeric($tahun)) ? (int)$tahun : (int)date('Y');
            for ($m = 1; $m <= 12; $m++) {
                $k = sprintf('%04d-%02d-01', $yr, $m);
                if (!isset($buckets[$k])) {
                    $buckets[$k] = [
                        'po_val' => 0.0, 'po_cnt' => 0, 'po_vals' => [],
                        'inv_val' => 0.0, 'inv_cnt' => 0, 'inv_vals' => [],
                        'top_docs' => []
                    ];
                }
            }
        }

        ksort($buckets);

        $series_main    = [];
        $series_po      = [];
        $series_inv     = [];
        $series_volume  = [];
        $series_ohlc    = [];
        $tooltip_meta   = [];

        $cum_val  = 0.0;
        $prev_val = 0.0;
        $non_zero_vals = [];
        $max_val = 0.0;
        $min_val = null;

        foreach ($buckets as $dateKey => $b) {
            $po_v  = round($b['po_val'], 2);
            $inv_v = round($b['inv_val'], 2);
            $all_v = round($po_v + $inv_v, 2);

            if ($source === 'PO') {
                $raw_val = $po_v;
                $vol_cnt = $b['po_cnt'];
            } elseif ($source === 'INV') {
                $raw_val = $inv_v;
                $vol_cnt = $b['inv_cnt'];
            } else {
                // ALL atau CUMULATIVE
                $raw_val = $all_v;
                $vol_cnt = $b['po_cnt'] + $b['inv_cnt'];
            }

            $cum_val = round($cum_val + $raw_val, 2);
            $val     = ($source === 'CUMULATIVE') ? $cum_val : $raw_val;

            if ($val > 0) {
                $non_zero_vals[] = $val;
                if ($val > $max_val) $max_val = $val;
                if ($min_val === null || $val < $min_val) $min_val = $val;
            }

            $is_up = ($val >= $prev_val);

            $series_main[] = [
                'time'  => $dateKey,
                'value' => $val
            ];

            $series_po[] = [
                'time'  => $dateKey,
                'value' => $po_v
            ];

            $series_inv[] = [
                'time'  => $dateKey,
                'value' => $inv_v
            ];

            $series_volume[] = [
                'time'  => $dateKey,
                'value' => $vol_cnt,
                'color' => $is_up ? 'rgba(16, 185, 129, 0.45)' : 'rgba(239, 68, 68, 0.45)'
            ];

            // Bentuk Candlestick OHLC dari pergerakan periode sebelumnya ke periode saat ini
            $open  = $prev_val > 0 ? $prev_val : ($val > 0 ? round($val * 0.85, 2) : 0);
            $close = $val;
            $high  = max($open, $close, round(max($open, $close) * ($vol_cnt > 1 ? 1.08 : 1.02), 2));
            $low   = min($open, $close) > 0 ? round(min($open, $close) * 0.94, 2) : 0;

            $series_ohlc[] = [
                'time'  => $dateKey,
                'open'  => $open,
                'high'  => $high,
                'low'   => $low,
                'close' => $close
            ];

            $tooltip_meta[$dateKey] = [
                'po_val'   => $po_v,
                'po_cnt'   => $b['po_cnt'],
                'inv_val'  => $inv_v,
                'inv_cnt'  => $b['inv_cnt'],
                'vol_cnt'  => $vol_cnt,
                'cum_val'  => $cum_val,
                'prev_val' => $prev_val,
                'top_docs' => $b['top_docs']
            ];

            $prev_val = $val;
        }

        // Hitung Baseline (Rata-rata periode aktif) untuk fitur warna Hijau/Merah otomatis ala TradingView Baseline Series
        $baseline_value = 0.0;
        if (!empty($non_zero_vals)) {
            $baseline_value = round(array_sum($non_zero_vals) / count($non_zero_vals), 2);
        }

        // Hitung persentase pertumbuhan antara 2 periode aktif terakhir
        $growth_pct = 0.0;
        $cnt_nz = count($non_zero_vals);
        if ($cnt_nz >= 2) {
            $last_nz = $non_zero_vals[$cnt_nz - 1];
            $prev_nz = $non_zero_vals[$cnt_nz - 2];
            if ($prev_nz > 0) {
                $growth_pct = round((($last_nz - $prev_nz) / $prev_nz) * 100, 2);
            }
        }

        $total_active_nominal = ($source === 'PO')
            ? $total_po_nominal
            : (($source === 'INV') ? $total_inv_nominal : ($total_po_nominal + $total_inv_nominal));

        $total_active_count = ($source === 'PO')
            ? $total_po_count
            : (($source === 'INV') ? $total_inv_count : ($total_po_count + $total_inv_count));

        header('Content-Type: application/json');
        echo json_encode([
            'status'         => 'success',
            'tahun'          => $tahun,
            'interval'       => $interval,
            'source'         => $source,
            'series_main'    => $series_main,
            'series_po'      => $series_po,
            'series_inv'     => $series_inv,
            'series_volume'  => $series_volume,
            'series_ohlc'    => $series_ohlc,
            'tooltip_meta'   => $tooltip_meta,
            'baseline_value' => $baseline_value,
            'summary'        => [
                'total_nominal'     => round($total_active_nominal, 2),
                'total_po_nominal'  => round($total_po_nominal, 2),
                'total_inv_nominal' => round($total_inv_nominal, 2),
                'total_count'       => $total_active_count,
                'total_po_count'    => $total_po_count,
                'total_inv_count'   => $total_inv_count,
                'baseline_avg'      => $baseline_value,
                'high_nominal'      => round($max_val, 2),
                'low_nominal'       => round($min_val ?? 0, 2),
                'growth_pct'        => $growth_pct
            ]
        ]);
    }
}
