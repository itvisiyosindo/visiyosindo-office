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
     * Endpoint JSON untuk Terminal Analisis Teknikal Keuangan & Forecasting (TradingView)
     * Menggunakan 100% data otomatis dari database:
     * - Inflow  : po_masuk (+ po_masuk_item), invoice (+ detail_barang_invoice)
     * - Outflow : tagihan_ekspedisi (SPP/Logistik), salary_history (Payroll SDM)
     */
    public function tradingview_financial_data()
    {
        $this->output->set_content_type('application/json');

        $tahun    = strtoupper(trim($this->input->get('tahun') ?: date('Y')));
        $interval = strtoupper(trim($this->input->get('interval') ?: '1M')); // 1D, 1W, 1M
        $source   = strtoupper(trim($this->input->get('source') ?: 'NET_CASHFLOW')); // NET_CASHFLOW, INFLOW, OUTFLOW, PO, INV, CUMULATIVE

        if (!in_array($interval, ['1D', '1W', '1M'], true)) {
            $interval = '1M';
        }
        if ($source === 'ALL') {
            $source = 'INFLOW';
        }

        $has_po       = $this->db->table_exists('po_masuk');
        $has_po_item  = $this->db->table_exists('po_masuk_item');
        $has_inv      = $this->db->table_exists('invoice');
        $has_inv_item = $this->db->table_exists('detail_barang_invoice');
        $has_tagihan  = $this->db->table_exists('tagihan_ekspedisi');
        $has_salary   = $this->db->table_exists('salary_history');

        // 1. INFLOW A: Ambil transaksi PO Masuk secara aman
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

        // 2. INFLOW B: Ambil transaksi Invoice secara aman
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

            $no_po_col = isset($inv_fields['no_po']) ? "inv.no_po" : "''";

            $sql_inv = "
                SELECT 
                    inv.tgl_invoice AS tgl,
                    {$inv_nom_expr} AS nominal,
                    inv.no_invoice AS kode_dok,
                    {$no_po_col} AS no_po_ref,
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

        // 3. OUTFLOW A: Ambil Pengeluaran Tagihan Ekspedisi / SPP secara aman
        $out_rows = [];
        if ($has_tagihan) {
            $tg_fields = array_flip($this->db->list_fields('tagihan_ekspedisi'));
            $tg_nom_parts = [];
            if (isset($tg_fields['total_tagihan'])) {
                $tg_nom_parts[] = "NULLIF(tg.total_tagihan, 0)";
            }
            if (isset($tg_fields['nilai_tagihan'])) {
                $asuransi_part = isset($tg_fields['biaya_asuransi']) ? "COALESCE(tg.biaya_asuransi, 0)" : "0";
                $tg_nom_parts[] = "NULLIF(COALESCE(tg.nilai_tagihan, 0) + {$asuransi_part}, 0)";
            }
            $tg_nom_parts[] = "0";
            $tg_nom_expr = "COALESCE(" . implode(", ", $tg_nom_parts) . ")";

            $tg_date_expr = isset($tg_fields['tanggal_invoice']) && isset($tg_fields['created_at'])
                ? "COALESCE(NULLIF(tg.tanggal_invoice, '0000-00-00'), DATE(tg.created_at))"
                : (isset($tg_fields['tanggal_invoice']) ? "tg.tanggal_invoice" : "DATE(tg.created_at)");

            $where_year_tg = ($tahun !== 'ALL' && is_numeric($tahun))
                ? "AND YEAR({$tg_date_expr}) = " . (int)$tahun
                : "";
            $where_app_tg = isset($tg_fields['status_approval']) ? "AND tg.status_approval != 99" : "";

            $sql_tg = "
                SELECT 
                    {$tg_date_expr} AS tgl,
                    {$tg_nom_expr} AS nominal,
                    COALESCE(tg.no_invoice, 'Tagihan Ekspedisi') AS kode_dok,
                    'Ekspedisi / Logistik' AS kategori_out
                FROM tagihan_ekspedisi tg
                WHERE {$tg_date_expr} IS NOT NULL
                  AND {$tg_date_expr} != '0000-00-00'
                  {$where_app_tg}
                  {$where_year_tg}
                ORDER BY {$tg_date_expr} ASC
            ";
            $q_tg = $this->db->query($sql_tg);
            if ($q_tg) {
                foreach ($q_tg->result_array() as $tr) {
                    $out_rows[] = $tr;
                }
            }
        }

        // 4. OUTFLOW B: Ambil Pengeluaran Payroll / Gaji (salary_history) secara aman
        if ($has_salary) {
            $sal_fields = array_flip($this->db->list_fields('salary_history'));
            $sal_add = [];
            foreach (['gajipokok', 'tunjangankonsumsi', 'tunjangankinerja', 'tunjangankomunikasi', 'tunjangantransport', 'tunjanganjabatan', 'bonus'] as $col) {
                if (isset($sal_fields[$col])) {
                    $sal_add[] = "COALESCE(CAST(sh.{$col} AS DECIMAL(18,2)), 0)";
                }
            }
            if (!empty($sal_add)) {
                $sal_sub = isset($sal_fields['potonganlainnya']) ? "COALESCE(CAST(sh.potonganlainnya AS DECIMAL(18,2)), 0)" : "0";
                $sal_nom_expr = "(" . implode(" + ", $sal_add) . " - {$sal_sub})";

                $sal_date_expr = (isset($sal_fields['tahun']) && isset($sal_fields['bulan']))
                    ? "STR_TO_DATE(CONCAT(sh.tahun, '-', LPAD(sh.bulan, 2, '0'), '-25'), '%Y-%m-%d')"
                    : "DATE(sh.created_at)";

                $where_year_sal = ($tahun !== 'ALL' && is_numeric($tahun))
                    ? (isset($sal_fields['tahun']) ? "AND sh.tahun = " . (int)$tahun : "AND YEAR(sh.created_at) = " . (int)$tahun)
                    : "";

                $sql_sal = "
                    SELECT 
                        {$sal_date_expr} AS tgl,
                        SUM({$sal_nom_expr}) AS nominal,
                        CONCAT('Payroll ', COUNT(*), ' Pegawai') AS kode_dok,
                        'Payroll / Gaji' AS kategori_out
                    FROM salary_history sh
                    WHERE 1=1 {$where_year_sal}
                    GROUP BY {$sal_date_expr}
                    HAVING nominal > 0
                    ORDER BY tgl ASC
                ";
                $q_sal = $this->db->query($sql_sal);
                if ($q_sal) {
                    foreach ($q_sal->result_array() as $sr) {
                        if (!empty($sr['tgl']) && $sr['tgl'] !== '0000-00-00') {
                            $out_rows[] = $sr;
                        }
                    }
                }
            }
        }

        // Helper untuk memetakan tanggal ke bucket key (YYYY-MM-DD) sesuai interval TradingView
        $getBucketDate = function ($dateStr) use ($interval) {
            $ts = strtotime($dateStr);
            if (!$ts || $ts <= 0) return null;
            if ($interval === '1M') {
                return date('Y-m-01', $ts);
            } elseif ($interval === '1W') {
                $dow = (int)date('N', $ts); // 1 (Mon) .. 7 (Sun)
                $monday = strtotime('-' . ($dow - 1) . ' days', $ts);
                return date('Y-m-d', $monday);
            } else {
                return date('Y-m-d', $ts);
            }
        };

        $makeEmptyBucket = function () {
            return [
                'po_val'             => 0.0, 'po_cnt'             => 0,
                'inv_val'            => 0.0, 'inv_cnt'            => 0,
                'inv_standalone_val' => 0.0, 'inv_standalone_cnt' => 0,
                'out_val'            => 0.0, 'out_cnt'            => 0,
                'out_ekspedisi_val'  => 0.0, 'out_payroll_val'    => 0.0,
                'top_docs'           => []
            ];
        };

        // Siapkan kerangka waktu dasar agar timeline kontinyu & rapi di TradingView
        $buckets = [];
        if ($tahun !== 'ALL' && is_numeric($tahun)) {
            $yr = (int)$tahun;
            $max_m = ($yr == (int)date('Y')) ? (int)date('n') : 12;
            if ($interval === '1M') {
                for ($m = 1; $m <= $max_m; $m++) {
                    $k = sprintf('%04d-%02d-01', $yr, $m);
                    $buckets[$k] = $makeEmptyBucket();
                }
            } elseif ($interval === '1W') {
                $startTs = strtotime("$yr-01-01");
                $endTs   = ($yr == (int)date('Y')) ? time() : strtotime("$yr-12-31");
                $dow = (int)date('N', $startTs);
                $cur = strtotime('-' . ($dow - 1) . ' days', $startTs);
                while ($cur <= $endTs) {
                    $k = date('Y-m-d', $cur);
                    $buckets[$k] = $makeEmptyBucket();
                    $cur = strtotime('+7 days', $cur);
                }
            }
        }

        // Masukkan data PO ke dalam bucket & catat daftar no_po untuk mencegah double-counting dengan Invoice
        $total_po_nominal = 0.0;
        $total_po_count   = 0;
        $po_no_set        = [];
        foreach ($po_rows as $r) {
            $k = $getBucketDate($r['tgl']);
            if (!$k) continue;
            $val = max(0, (float)$r['nominal']);
            if (!isset($buckets[$k])) $buckets[$k] = $makeEmptyBucket();
            $buckets[$k]['po_val'] += $val;
            $buckets[$k]['po_cnt'] += 1;
            if (!empty($r['kode_dok'])) {
                $norm_po = strtolower(trim($r['kode_dok']));
                if ($norm_po !== '' && $norm_po !== '-') {
                    $po_no_set[$norm_po] = true;
                }
            }
            if (count($buckets[$k]['top_docs']) < 3) {
                $buckets[$k]['top_docs'][] = 'PO: ' . ($r['kode_dok'] ?: '-') . ' (' . ($r['customer_nama'] ?: '-') . ')';
            }
            $total_po_nominal += $val;
            $total_po_count++;
        }

        // Masukkan data Invoice ke dalam bucket (pisahkan Invoice yang sudah punya PO di po_masuk agar tidak terhitung ganda pada Total Inflow)
        $total_inv_nominal            = 0.0;
        $total_inv_count              = 0;
        $total_inv_standalone_nominal = 0.0;
        $total_inv_standalone_count   = 0;
        foreach ($inv_rows as $r) {
            $k = $getBucketDate($r['tgl']);
            if (!$k) continue;
            $val = max(0, (float)$r['nominal']);
            if (!isset($buckets[$k])) $buckets[$k] = $makeEmptyBucket();
            $buckets[$k]['inv_val'] += $val;
            $buckets[$k]['inv_cnt'] += 1;

            $ref_po = isset($r['no_po_ref']) ? strtolower(trim((string)$r['no_po_ref'])) : '';
            $is_already_in_po = ($ref_po !== '' && $ref_po !== '-' && isset($po_no_set[$ref_po]));
            if (!$is_already_in_po) {
                $buckets[$k]['inv_standalone_val'] += $val;
                $buckets[$k]['inv_standalone_cnt'] += 1;
                $total_inv_standalone_nominal      += $val;
                $total_inv_standalone_count++;
            }

            if (count($buckets[$k]['top_docs']) < 3) {
                $buckets[$k]['top_docs'][] = 'INV: ' . ($r['kode_dok'] ?: '-') . ' (' . ($r['customer_nama'] ?: '-') . ')';
            }
            $total_inv_nominal += $val;
            $total_inv_count++;
        }

        // Masukkan data Outflow (Ekspedisi/SPP & Payroll) ke dalam bucket
        $total_out_nominal   = 0.0;
        $total_out_count     = 0;
        $total_ekspedisi_nom = 0.0;
        $total_payroll_nom   = 0.0;
        foreach ($out_rows as $r) {
            $k = $getBucketDate($r['tgl']);
            if (!$k) continue;
            $val = max(0, (float)$r['nominal']);
            if ($val <= 0) continue;
            if (!isset($buckets[$k])) $buckets[$k] = $makeEmptyBucket();
            $buckets[$k]['out_val'] += $val;
            $buckets[$k]['out_cnt'] += 1;
            if (isset($r['kategori_out']) && strpos($r['kategori_out'], 'Payroll') !== false) {
                $buckets[$k]['out_payroll_val'] += $val;
                $total_payroll_nom += $val;
            } else {
                $buckets[$k]['out_ekspedisi_val'] += $val;
                $total_ekspedisi_nom += $val;
            }
            if (count($buckets[$k]['top_docs']) < 3) {
                $buckets[$k]['top_docs'][] = 'OUT: ' . ($r['kode_dok'] ?: '-') . ' (' . ($r['kategori_out'] ?: '-') . ')';
            }
            $total_out_nominal += $val;
            $total_out_count++;
        }

        if (count($buckets) < 2) {
            $yr = ($tahun !== 'ALL' && is_numeric($tahun)) ? (int)$tahun : (int)date('Y');
            $max_m = ($yr == (int)date('Y')) ? max(3, (int)date('n')) : 12;
            for ($m = 1; $m <= $max_m; $m++) {
                $k = sprintf('%04d-%02d-01', $yr, $m);
                if (!isset($buckets[$k])) {
                    $buckets[$k] = $makeEmptyBucket();
                }
            }
        }

        ksort($buckets);

        $series_main    = [];
        $series_inflow  = [];
        $series_outflow = [];
        $series_po      = [];
        $series_inv     = [];
        $series_volume  = [];
        $series_ohlc    = [];
        $tooltip_meta   = [];

        $cum_val       = 0.0;
        $prev_val      = 0.0;
        $active_vals   = [];
        $inflow_vals   = [];
        $outflow_vals  = [];
        $max_val       = null;
        $min_val       = null;

        foreach ($buckets as $dateKey => $b) {
            $po_v     = round($b['po_val'], 2);
            $inv_v    = round($b['inv_val'], 2);
            // Total Inflow tanpa double-counting: PO Masuk + Invoice yang belum tercatat di PO Masuk
            $inflow_v = round($po_v + $b['inv_standalone_val'], 2);
            $out_v    = round($b['out_val'], 2);
            $net_v    = round($inflow_v - $out_v, 2);
            $inflow_c = $b['po_cnt'] + $b['inv_standalone_cnt'];

            if ($source === 'PO') {
                $raw_val = $po_v;
                $vol_cnt = $b['po_cnt'];
            } elseif ($source === 'INV') {
                $raw_val = $inv_v;
                $vol_cnt = $b['inv_cnt'];
            } elseif ($source === 'OUTFLOW') {
                $raw_val = $out_v;
                $vol_cnt = $b['out_cnt'];
            } elseif ($source === 'NET_CASHFLOW') {
                $raw_val = $net_v;
                $vol_cnt = $inflow_c + $b['out_cnt'];
            } else {
                // INFLOW atau CUMULATIVE
                $raw_val = $inflow_v;
                $vol_cnt = $inflow_c;
            }

            $cum_val = round($cum_val + ($source === 'CUMULATIVE' ? $net_v : $raw_val), 2);
            $val     = ($source === 'CUMULATIVE') ? $cum_val : $raw_val;

            if ($vol_cnt > 0 || $val != 0) {
                $active_vals[] = $val;
                if ($max_val === null || $val > $max_val) $max_val = $val;
                if ($min_val === null || $val < $min_val) $min_val = $val;
            }
            if ($inflow_v > 0) $inflow_vals[] = $inflow_v;
            if ($out_v > 0)    $outflow_vals[] = $out_v;

            $is_up = ($source === 'NET_CASHFLOW') ? ($val >= 0) : ($val >= $prev_val);

            $series_main[]    = ['time' => $dateKey, 'value' => $val];
            $series_inflow[]  = ['time' => $dateKey, 'value' => $inflow_v];
            $series_outflow[] = ['time' => $dateKey, 'value' => $out_v];
            $series_po[]      = ['time' => $dateKey, 'value' => $po_v];
            $series_inv[]     = ['time' => $dateKey, 'value' => $inv_v];

            $series_volume[] = [
                'time'  => $dateKey,
                'value' => $vol_cnt,
                'color' => $is_up ? 'rgba(16, 185, 129, 0.45)' : 'rgba(239, 68, 68, 0.45)'
            ];

            $open  = ($prev_val != 0) ? $prev_val : round($val * 0.85, 2);
            $close = $val;
            $span  = max(abs($close - $open) * 0.25, abs($close) * 0.05);
            $high  = round(max($open, $close) + $span, 2);
            $low   = round(min($open, $close) - $span, 2);

            $series_ohlc[] = [
                'time'  => $dateKey,
                'open'  => $open,
                'high'  => $high,
                'low'   => $low,
                'close' => $close
            ];

            $tooltip_meta[$dateKey] = [
                'inflow_val'    => $inflow_v,
                'outflow_val'   => $out_v,
                'net_val'       => $net_v,
                'po_val'        => $po_v,
                'po_cnt'        => $b['po_cnt'],
                'inv_val'       => $inv_v,
                'inv_cnt'       => $b['inv_cnt'],
                'ekspedisi_val' => round($b['out_ekspedisi_val'], 2),
                'payroll_val'   => round($b['out_payroll_val'], 2),
                'out_cnt'       => $b['out_cnt'],
                'vol_cnt'       => $vol_cnt,
                'cum_val'       => $cum_val,
                'prev_val'      => $prev_val,
                'is_forecast'   => false,
                'top_docs'      => $b['top_docs']
            ];

            $prev_val = $val;
        }

        // Hitung Baseline: Untuk NET_CASHFLOW, baseline adalah Rp 0 (Surplus vs Defisit).
        // Untuk mode lain, baseline adalah rata-rata periode aktif.
        $avg_active = !empty($active_vals) ? round(array_sum($active_vals) / count($active_vals), 2) : 0.0;
        $baseline_value = ($source === 'NET_CASHFLOW') ? 0.0 : $avg_active;
        $avg_outflow_burn = !empty($outflow_vals) ? round(array_sum($outflow_vals) / count($outflow_vals), 2) : 0.0;

        // =========================================================================
        // 5. FITUR FORECASTING / PROYEKSI 3 PERIODE KE DEPAN (LINEAR REGRESSION + WMA)
        // =========================================================================
        $series_forecast       = [];
        $series_forecast_upper = [];
        $series_forecast_lower = [];
        $next_forecast_val     = 0.0;

        $n_pts = count($series_main);
        if ($n_pts >= 2) {
            // Ambil maksimal 8 titik terakhir untuk regresi tren & deviasi standar
            $window = array_slice($series_main, max(0, $n_pts - 8));
            $m_cnt  = count($window);

            $sumX = 0; $sumY = 0; $sumXY = 0; $sumX2 = 0;
            $wma_num = 0; $wma_den = 0;
            for ($i = 0; $i < $m_cnt; $i++) {
                $x = $i + 1;
                $y = (float)$window[$i]['value'];
                $sumX  += $x;
                $sumY  += $y;
                $sumXY += ($x * $y);
                $sumX2 += ($x * $x);

                $weight   = $x;
                $wma_num += ($y * $weight);
                $wma_den += $weight;
            }

            $denom = ($m_cnt * $sumX2) - ($sumX * $sumX);
            $slope = ($denom != 0) ? (($m_cnt * $sumXY) - ($sumX * $sumY)) / $denom : 0.0;
            $intercept = ($sumY - ($slope * $sumX)) / $m_cnt;
            $wma_val = ($wma_den > 0) ? ($wma_num / $wma_den) : ($sumY / $m_cnt);

            // Hitung Standard Deviation (Volatilitas) untuk Confidence Band Atas & Bawah
            $variance = 0.0;
            for ($i = 0; $i < $m_cnt; $i++) {
                $diff = (float)$window[$i]['value'] - ($sumY / $m_cnt);
                $variance += ($diff * $diff);
            }
            $std_dev = sqrt($variance / max(1, $m_cnt));
            if ($std_dev <= 0) {
                $std_dev = abs($wma_val) * 0.12;
            }

            // Titik jangkar (anchor) dari titik aktual terakhir agar garis proyeksi menyambung mulus
            $last_actual = $series_main[$n_pts - 1];
            $series_forecast[]       = ['time' => $last_actual['time'], 'value' => $last_actual['value']];
            $series_forecast_upper[] = ['time' => $last_actual['time'], 'value' => $last_actual['value']];
            $series_forecast_lower[] = ['time' => $last_actual['time'], 'value' => $last_actual['value']];

            $cur_ts = strtotime($last_actual['time']);
            for ($step = 1; $step <= 3; $step++) {
                if ($interval === '1M') {
                    $cur_ts = strtotime('+1 month', $cur_ts);
                    $next_date = date('Y-m-01', $cur_ts);
                } elseif ($interval === '1W') {
                    $cur_ts = strtotime('+7 days', $cur_ts);
                    $next_date = date('Y-m-d', $cur_ts);
                } else {
                    $cur_ts = strtotime('+1 day', $cur_ts);
                    $next_date = date('Y-m-d', $cur_ts);
                }

                // Kombinasi 60% Linear Regression + 40% Weighted Moving Average + Drift
                $reg_pred = $intercept + ($slope * ($m_cnt + $step));
                $wma_pred = $wma_val + ($slope * $step * 0.65);
                $pred_val = round((0.6 * $reg_pred) + (0.4 * $wma_pred), 2);

                // Jika bukan NET_CASHFLOW, pastikan proyeksi omzet tidak negatif
                if ($source !== 'NET_CASHFLOW') {
                    $pred_val = max(0, $pred_val);
                }

                $band_width = round($std_dev * (0.75 + ($step * 0.25)), 2);
                $upper_val  = round($pred_val + $band_width, 2);
                $lower_val  = ($source !== 'NET_CASHFLOW') ? max(0, round($pred_val - $band_width, 2)) : round($pred_val - $band_width, 2);

                if ($step === 1) {
                    $next_forecast_val = $pred_val;
                }

                $series_forecast[]       = ['time' => $next_date, 'value' => $pred_val];
                $series_forecast_upper[] = ['time' => $next_date, 'value' => $upper_val];
                $series_forecast_lower[] = ['time' => $next_date, 'value' => $lower_val];

                $tooltip_meta[$next_date] = [
                    'inflow_val'   => ($source === 'OUTFLOW') ? 0 : $pred_val,
                    'outflow_val'  => ($source === 'OUTFLOW') ? $pred_val : $avg_outflow_burn,
                    'net_val'      => ($source === 'NET_CASHFLOW') ? $pred_val : ($pred_val - $avg_outflow_burn),
                    'po_val'       => 0,
                    'po_cnt'       => 0,
                    'inv_val'      => 0,
                    'inv_cnt'      => 0,
                    'out_cnt'      => 0,
                    'vol_cnt'      => 0,
                    'cum_val'      => $cum_val,
                    'prev_val'     => $last_actual['value'],
                    'is_forecast'  => true,
                    'upper_bound'  => $upper_val,
                    'lower_bound'  => $lower_val,
                    'top_docs'     => ['Proyeksi AI / Regresi Tren (+' . $step . ' Periode) | Rentang: Rp ' . number_format($lower_val, 0, ',', '.') . ' s/d Rp ' . number_format($upper_val, 0, ',', '.')]
                ];
            }
        }

        // Hitung persentase pertumbuhan & Sinyal Analisis Teknikal
        $growth_pct = 0.0;
        $cnt_act = count($active_vals);
        if ($cnt_act >= 2) {
            $last_v = $active_vals[$cnt_act - 1];
            $prev_v = $active_vals[$cnt_act - 2];
            if (abs($prev_v) > 0) {
                $growth_pct = round((($last_v - $prev_v) / abs($prev_v)) * 100, 2);
            }
        }

        // Total Inflow bebas hitung ganda: PO Masuk + Invoice mandiri (yang no_po-nya belum ada di po_masuk)
        $total_inflow_nominal = $total_po_nominal + $total_inv_standalone_nominal;
        $total_inflow_count   = $total_po_count + $total_inv_standalone_count;
        $total_net_cashflow   = $total_inflow_nominal - $total_out_nominal;
        $cashflow_ratio       = ($total_out_nominal > 0) ? round($total_inflow_nominal / $total_out_nominal, 2) : ($total_inflow_nominal > 0 ? 99.9 : 0.0);
        $conversion_rate_pct  = ($total_po_nominal > 0) ? round(($total_inv_nominal / $total_po_nominal) * 100, 1) : 0.0;
        $unbilled_po_nominal  = max(0, $total_po_nominal - $total_inv_nominal);

        // Penentuan Sinyal Teknikal Otomatis (Bullish / Neutral / Bearish)
        $signal_status = 'NEUTRAL';
        $signal_label  = 'NEUTRAL • KONSOLIDASI';
        $signal_desc   = 'Arus kas bergerak stabil di sekitar rata-rata historis.';
        if ($total_net_cashflow > 0 && $growth_pct >= 0) {
            $signal_status = 'BULLISH';
            $signal_label  = 'STRONG BULLISH • SURPLUS & EKSPANSI';
            $signal_desc   = 'Arus kas masuk melampaui beban pengeluaran dengan momentum pertumbuhan positif.';
        } elseif ($total_net_cashflow > 0 && $growth_pct < 0) {
            $signal_status = 'BULLISH';
            $signal_label  = 'MODERATE BULLISH • SURPLUS MELAMBAT';
            $signal_desc   = 'Kas bersih masih surplus, namun laju pertumbuhan periode terakhir mengalami koreksi.';
        } elseif ($total_net_cashflow < 0) {
            $signal_status = 'BEARISH';
            $signal_label  = 'BEARISH WARNING • DEFISIT ARUS KAS';
            $signal_desc   = 'Beban pengeluaran melampaui arus masuk pada periode ini. Percepat penagihan Invoice/PO.';
        }

        if ($source === 'PO') {
            $total_active_nominal = $total_po_nominal;
            $total_active_count   = $total_po_count;
        } elseif ($source === 'INV') {
            $total_active_nominal = $total_inv_nominal;
            $total_active_count   = $total_inv_count;
        } elseif ($source === 'OUTFLOW') {
            $total_active_nominal = $total_out_nominal;
            $total_active_count   = $total_out_count;
        } elseif ($source === 'NET_CASHFLOW') {
            $total_active_nominal = $total_net_cashflow;
            $total_active_count   = $total_inflow_count + $total_out_count;
        } else {
            $total_active_nominal = $total_inflow_nominal;
            $total_active_count   = $total_inflow_count;
        }

        echo json_encode([
            'status'                => 'success',
            'tahun'                 => $tahun,
            'interval'              => $interval,
            'source'                => $source,
            'series_main'           => $series_main,
            'series_inflow'         => $series_inflow,
            'series_outflow'        => $series_outflow,
            'series_po'             => $series_po,
            'series_inv'            => $series_inv,
            'series_volume'         => $series_volume,
            'series_ohlc'           => $series_ohlc,
            'series_forecast'       => $series_forecast,
            'series_forecast_upper' => $series_forecast_upper,
            'series_forecast_lower' => $series_forecast_lower,
            'tooltip_meta'          => $tooltip_meta,
            'baseline_value'        => $baseline_value,
            'avg_outflow_burn'      => $avg_outflow_burn,
            'summary'               => [
                'total_nominal'        => round($total_active_nominal, 2),
                'total_inflow_nominal' => round($total_inflow_nominal, 2),
                'total_inflow_count'   => $total_inflow_count,
                'total_outflow_nominal'=> round($total_out_nominal, 2),
                'total_ekspedisi_nom'  => round($total_ekspedisi_nom, 2),
                'total_payroll_nom'    => round($total_payroll_nom, 2),
                'total_net_cashflow'   => round($total_net_cashflow, 2),
                'total_po_nominal'     => round($total_po_nominal, 2),
                'total_inv_nominal'    => round($total_inv_nominal, 2),
                'unbilled_po_nominal'  => round($unbilled_po_nominal, 2),
                'conversion_rate_pct'  => $conversion_rate_pct,
                'cashflow_ratio'       => $cashflow_ratio,
                'total_count'          => $total_active_count,
                'total_po_count'       => $total_po_count,
                'total_inv_count'      => $total_inv_count,
                'total_out_count'      => $total_out_count,
                'baseline_avg'         => $avg_active,
                'high_nominal'         => round($max_val ?? 0, 2),
                'low_nominal'          => round($min_val ?? 0, 2),
                'growth_pct'           => $growth_pct,
                'next_forecast_val'    => $next_forecast_val,
                'signal_status'        => $signal_status,
                'signal_label'         => $signal_label,
                'signal_desc'          => $signal_desc
            ]
        ]);
    }
}
