<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Md_vym_bi — Institutional Financial & Business Intelligence Analytical Model
 * Read-Only Analytical Service Layer for VYM Business Intelligence (VYM BI)
 *
 * Strictly enforces Section 5 Financial Data Principles & Zero-Crash DB Safety:
 * - Every query runs through safeQuery() with db_debug temporarily disabled
 * - Every table & column is verified with safeTableExists() & safeListFields()
 * - All string-to-decimal & date parsing and aggregation happen in PHP (immune to MySQL Strict / ONLY_FULL_GROUP_BY)
 */
class Md_vym_bi extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // =========================================================================
    // ZERO-CRASH DATABASE & PARSING HELPERS
    // =========================================================================

    private function safeQuery($sql)
    {
        if (!isset($this->db) || !is_object($this->db)) {
            return false;
        }
        $orig_debug = isset($this->db->db_debug) ? $this->db->db_debug : true;
        $this->db->db_debug = false;
        $q = @$this->db->query($sql);
        $this->db->db_debug = $orig_debug;
        return ($q && is_object($q)) ? $q : false;
    }

    private function safeTableExists($table)
    {
        if (!isset($this->db) || !is_object($this->db)) {
            return false;
        }
        $orig_debug = isset($this->db->db_debug) ? $this->db->db_debug : true;
        $this->db->db_debug = false;
        $exists = (bool)@$this->db->table_exists($table);
        $this->db->db_debug = $orig_debug;
        return $exists;
    }

    private function safeListFields($table)
    {
        if (!$this->safeTableExists($table)) {
            return [];
        }
        $orig_debug = isset($this->db->db_debug) ? $this->db->db_debug : true;
        $this->db->db_debug = false;
        $list = @$this->db->list_fields($table);
        $this->db->db_debug = $orig_debug;
        return is_array($list) ? array_flip($list) : [];
    }

    private function cleanNumber($val)
    {
        if ($val === null || $val === false || $val === '' || $val === 'NaN') {
            return 0.0;
        }
        if (is_int($val) || is_float($val)) {
            return (float)$val;
        }
        $s = trim((string)$val);
        if ($s === '' || strtolower($s) === 'nan') {
            return 0.0;
        }
        // If already a standard numeric string (e.g. "12500000.00" from DECIMAL column)
        if (is_numeric($s) && strpos($s, '.') !== false && substr_count($s, '.') === 1 && strpos($s, ',') === false) {
            $parts = explode('.', $s);
            if (strlen($parts[1]) <= 2) {
                return (float)$s;
            }
        }
        // Indonesian thousand dot format: remove dots, convert comma to dot
        $clean = str_replace('.', '', $s);
        $clean = str_replace(',', '.', $clean);
        $clean = preg_replace('/[^0-9.\-]/', '', $clean);
        return is_numeric($clean) ? (float)$clean : 0.0;
    }

    private function cleanDate($val)
    {
        if (empty($val)) {
            return null;
        }
        $s = substr(trim((string)$val), 0, 10);
        if ($s === '0000-00-00' || strlen($s) < 8) {
            return null;
        }
        $ts = @strtotime($s);
        if (!$ts || $ts <= 0) {
            return null;
        }
        return date('Y-m-d', $ts);
    }

    /**
     * Resolve Interactive Date Filter Preset into [start_date, end_date, preset, label]
     */
    public function resolveDateFilter($preset = 'YTD', $start_custom = null, $end_custom = null)
    {
        $preset = strtoupper(trim((string)$preset));
        $today  = date('Y-m-d');
        $year   = (int)date('Y');
        $month  = (int)date('n');

        switch ($preset) {
            case 'MTD':
                $start = date('Y-m-01');
                $end   = $today;
                $label = 'Month-to-Date (' . date('M Y') . ')';
                break;

            case 'QTD':
                $q_start_month = (floor(($month - 1) / 3) * 3) + 1;
                $start = sprintf('%04d-%02d-01', $year, $q_start_month);
                $end   = $today;
                $q_no  = (int)ceil($month / 3);
                $label = "Quarter-to-Date (Q{$q_no} {$year})";
                break;

            case '1Y':
                $start = date('Y-m-d', strtotime('-365 days'));
                $end   = $today;
                $label = 'Trailing 12 Months (1Y)';
                break;

            case 'PREV_YEAR':
                $prev_y = $year - 1;
                $start  = "{$prev_y}-01-01";
                $end    = "{$prev_y}-12-31";
                $label  = "Full Year {$prev_y}";
                break;

            case 'ALL':
                $start = '2020-01-01';
                $end   = $today;
                $label = 'All-Time Historical';
                break;

            case 'CUSTOM':
                $s_ts  = !empty($start_custom) ? @strtotime($start_custom) : false;
                $e_ts  = !empty($end_custom) ? @strtotime($end_custom) : false;
                $start = $s_ts ? date('Y-m-d', $s_ts) : date('Y-01-01');
                $end   = $e_ts ? date('Y-m-d', $e_ts) : $today;
                if ($start > $end) {
                    $tmp   = $start;
                    $start = $end;
                    $end   = $tmp;
                }
                $label = 'Custom (' . date('d M Y', strtotime($start)) . ' – ' . date('d M Y', strtotime($end)) . ')';
                break;

            case 'YTD':
            default:
                $preset = 'YTD';
                $start  = "{$year}-01-01";
                $end    = $today;
                $label  = "Year-to-Date ({$year})";
                break;
        }

        return [
            'preset'     => $preset,
            'start_date' => $start,
            'end_date'   => $end,
            'label'      => $label
        ];
    }

    /**
     * Emergency Empty Skeleton Fallback so Controller never fails
     */
    public function getEmptyOverviewSkeleton($start_date, $end_date, $interval = '1M')
    {
        return [
            'status'                  => 'success',
            'used_fallback_all'       => false,
            'filter'                  => [
                'start_date' => $start_date,
                'end_date'   => $end_date,
                'interval'   => $interval
            ],
            'kpis'                    => [],
            'time_series'             => [
                'orders'      => [],
                'invoices'    => [],
                'cash_out'    => [],
                'obligations' => [],
                'volume'      => [],
                'meta'        => []
            ],
            'top_customers'           => [],
            'recent_ledger'           => [],
            'financial_intelligence'  => [
                'monthly_statement'       => [],
                'revenue_funnel'          => [],
                'payment_terms_breakdown' => [],
                'sales_attribution'       => [],
                'unbilled_orders_list'    => [],
                'profitability_waterfall' => [],
                'top_billed_skus'         => [],
                'cogs_audit'              => [],
                'payables_watchlist'      => []
            ],
            'business_analytics'      => [
                'customer_intelligence'           => ['summary' => [], 'matrix' => []],
                'product_intelligence'            => ['summary' => [], 'matrix' => []],
                'supplier_logistics_intelligence' => ['freight_vendors' => [], 'principal_suppliers' => ['stats' => [], 'rows' => []]],
                'operational_intelligence'        => ['kpis' => [], 'pipeline_breakdown' => []]
            ],
            'predictive_intelligence' => [
                'forward_projections' => [],
                'risk_matrix'         => [],
                'anomaly_alerts'      => [],
                'ai_analyst'          => ['executive_briefing' => [], 'preset_qa' => []]
            ]
        ];
    }

    /**
     * Master Aggregator for VYM BI (All 8 Submodules)
     */
    public function getExecutiveOverviewData($start_date, $end_date, $interval = '1M')
    {
        $interval = in_array($interval, ['1D', '1W', '1M'], true) ? $interval : '1M';

        $orders      = $this->fetchCustomerOrders($start_date, $end_date);
        $invoices    = $this->fetchIssuedInvoices($start_date, $end_date);
        $taxes       = $this->fetchTaxRecognitions($start_date, $end_date);
        $tagihan     = $this->fetchFreightObligationsAndPayments($start_date, $end_date);
        $payroll     = $this->fetchPayrollExpenditures($start_date, $end_date);
        $cogs_status = $this->inspectCogsCoverage();

        // Auto-fallback to ALL-TIME if current YTD window has 0 records
        $used_fallback_all = false;
        if (
            $orders['count'] === 0 &&
            $invoices['count'] === 0 &&
            $tagihan['paid_count'] === 0 &&
            $tagihan['unpaid_count'] === 0 &&
            $start_date > '2020-01-01'
        ) {
            $used_fallback_all = true;
            $start_date        = '2020-01-01';
            $orders            = $this->fetchCustomerOrders($start_date, $end_date);
            $invoices          = $this->fetchIssuedInvoices($start_date, $end_date);
            $taxes             = $this->fetchTaxRecognitions($start_date, $end_date);
            $tagihan           = $this->fetchFreightObligationsAndPayments($start_date, $end_date);
            $payroll           = $this->fetchPayrollExpenditures($start_date, $end_date);
        }

        // Map invoiced PO numbers
        $invoiced_po_map = [];
        foreach ($invoices['rows'] as $inv) {
            $norm_po = strtolower(trim((string)(isset($inv['no_po']) ? $inv['no_po'] : '')));
            if ($norm_po !== '' && $norm_po !== '-') {
                if (!isset($invoiced_po_map[$norm_po])) {
                    $invoiced_po_map[$norm_po] = 0.0;
                }
                $invoiced_po_map[$norm_po] += (float)$inv['nominal_total'];
            }
        }

        $unbilled_po_nominal = 0.0;
        $unbilled_po_count   = 0;
        $billed_po_count     = 0;

        foreach ($orders['rows'] as $po) {
            $norm_po = strtolower(trim((string)(isset($po['no_po']) ? $po['no_po'] : '')));
            $po_val  = (float)$po['nominal_total'];
            if ($norm_po !== '' && isset($invoiced_po_map[$norm_po])) {
                $billed_po_count++;
            } else {
                $unbilled_po_nominal += $po_val;
                $unbilled_po_count++;
            }
        }

        $buckets = $this->buildTimeSeriesBuckets(
            $start_date,
            $end_date,
            $interval,
            $orders['rows'],
            $invoices['rows'],
            $tagihan['rows'],
            $payroll['rows']
        );

        $top_customers = $this->buildCustomerBreakdown($orders['rows'], $invoices['rows']);
        $recent_ledger = $this->buildUnifiedAuditLedger($orders['rows'], $invoices['rows'], $tagihan['rows'], $payroll['rows']);

        $fin_intel = $this->buildFinancialIntelligencePayload(
            $start_date,
            $end_date,
            $orders,
            $invoices,
            $taxes,
            $tagihan,
            $payroll,
            $invoiced_po_map,
            $cogs_status
        );

        $biz_analytics = $this->buildBusinessAnalyticsPayload(
            $start_date,
            $end_date,
            $orders,
            $invoices,
            $tagihan
        );

        $pred_intel = $this->buildPredictiveIntelligencePayload(
            $start_date,
            $end_date,
            $orders,
            $invoices,
            $taxes,
            $tagihan,
            $payroll,
            $unbilled_po_nominal,
            $unbilled_po_count,
            $cogs_status,
            $fin_intel,
            $biz_analytics
        );

        $order_total        = $orders['total_nominal'];
        $invoice_total      = $invoices['total_nominal'];
        $invoice_dpp        = $invoices['total_dpp'];
        $verified_dp_cash   = $orders['verified_dp_nominal'];
        $obligations_unpaid = $tagihan['unpaid_nominal'];
        $cash_out_paid      = $tagihan['paid_nominal'] + $payroll['total_nominal'];

        $order_to_billing_pct = ($order_total > 0)
            ? round(($invoice_total / $order_total) * 100, 1)
            : 0.0;

        $billing_to_cashout_ratio = ($cash_out_paid > 0)
            ? round($invoice_total / $cash_out_paid, 2)
            : 0.0;

        return [
            'status'            => 'success',
            'used_fallback_all' => $used_fallback_all,
            'filter'            => [
                'start_date' => $start_date,
                'end_date'   => $end_date,
                'interval'   => $interval
            ],
            'kpis'              => [
                'customer_orders' => [
                    'code'           => 'VYM:ORDER_BOOK',
                    'title'          => 'Customer Orders (PO Masuk)',
                    'classification' => 'ACTUAL • CONTRACTED ORDER',
                    'badge_type'     => 'actual',
                    'nominal'        => round($order_total, 2),
                    'dpp_subtotal'   => round($orders['total_subtotal'], 2),
                    'ppn_nominal'    => round($orders['total_ppn'], 2),
                    'ongkir_nominal' => round($orders['total_ongkir'], 2),
                    'approved_nom'   => round($orders['approved_nominal'], 2),
                    'pending_nom'    => round($orders['pending_nominal'], 2),
                    'count'          => $orders['count'],
                    'approved_count' => $orders['approved_count'],
                    'pending_count'  => $orders['pending_count'],
                    'formula'        => 'SUM(COALESCE(NULLIF(nominal_total_akhir,0), NULLIF(nominal_po,0), nominal_subtotal)) WHERE deleted = 0 AND status != "ditolak"',
                    'source_tables'  => 'po_masuk, po_masuk_item',
                    'accounting_note'=> 'Pesanan kontrak dari pelanggan (Order Book). Bukan kas masuk aktual dan belum diakui sebagai penagihan lunas.'
                ],
                'issued_invoices' => [
                    'code'           => 'VYM:BILLED_AR',
                    'title'          => 'Issued Invoices (Penagihan)',
                    'classification' => 'ACTUAL • BILLED RECEIVABLE',
                    'badge_type'     => 'actual',
                    'nominal'        => round($invoice_total, 2),
                    'net_dpp'        => round($invoice_dpp, 2),
                    'ongkir_nominal' => round($invoices['total_ongkir'], 2),
                    'count'          => $invoices['count'],
                    'shipped_count'  => $invoices['shipped_count'],
                    'conversion_pct' => $order_to_billing_pct,
                    'formula'        => 'SUM(COALESCE(NULLIF(total_keseluruhan,0), NULLIF(total,0), sub_total)) WHERE invoice.status = 1',
                    'source_tables'  => 'invoice, detail_barang_invoice',
                    'accounting_note'=> 'Faktur tagihan resmi yang telah diterbitkan ke pelanggan (Piutang Usaha / Billed Revenue). Tidak diklaim sebagai pembayaran lunas.'
                ],
                'unbilled_backlog' => [
                    'code'           => 'VYM:UNBILLED_PO',
                    'title'          => 'Unbilled Order Backlog',
                    'classification' => 'ACTUAL • PIPELINE',
                    'badge_type'     => 'actual',
                    'nominal'        => round($unbilled_po_nominal, 2),
                    'count'          => $unbilled_po_count,
                    'billed_po_cnt'  => $billed_po_count,
                    'formula'        => 'SUM(po_masuk.nominal_total_akhir) WHERE LOWER(TRIM(po_masuk.no_po)) NOT IN (SELECT LOWER(TRIM(invoice.no_po)) FROM invoice WHERE status = 1)',
                    'source_tables'  => 'po_masuk LEFT JOIN invoice ON no_po',
                    'accounting_note'=> 'Nilai PO pelanggan yang masih dalam tahap pemenuhan/kroscek dan belum diterbitkan dokumen Invoice.'
                ],
                'recognized_tax' => [
                    'code'           => 'VYM:TAX_BASIS',
                    'title'          => 'Approved Tax Basis (DPP & PPN)',
                    'classification' => 'ACTUAL • TAX RECOGNIZED',
                    'badge_type'     => 'actual',
                    'dpp_nominal'    => round($taxes['approved_dpp'], 2),
                    'ppn_nominal'    => round($taxes['approved_ppn'], 2),
                    'nominal'        => round($taxes['approved_dpp'] + $taxes['approved_ppn'], 2),
                    'approved_count' => $taxes['approved_count'],
                    'pending_count'  => $taxes['pending_count'],
                    'formula'        => 'SUM(dpp), SUM(ppn) FROM approval_faktur_pajak WHERE status = 4 (Approved)',
                    'source_tables'  => 'approval_faktur_pajak, acc_bukti_lapor_pajak',
                    'accounting_note'=> 'Dasar Pengenaan Pajak (DPP) dan PPN Keluaran yang telah melewati verifikasi dan mendapatkan persetujuan Faktur Pajak.'
                ],
                'verified_customer_cash' => [
                    'code'           => 'VYM:VERIFIED_DP_CASH',
                    'title'          => 'Verified Down Payments & Cash PO',
                    'classification' => 'ACTUAL • RECORDED DP/CASH',
                    'badge_type'     => 'actual',
                    'nominal'        => round($verified_dp_cash, 2),
                    'dp_nominal'     => round($orders['cicilan_dp_only'], 2),
                    'cash_po_nominal'=> round($orders['cash_approved_only'], 2),
                    'count'          => $orders['cash_or_dp_count'],
                    'formula'        => 'SUM(cicilan_dp_nominal) + SUM(nominal_total_akhir WHERE sistem_pembayaran IN ("Cash","COD") AND status = "disetujui")',
                    'source_tables'  => 'po_masuk (cicilan_dp_nominal, sistem_pembayaran)',
                    'accounting_note'=> 'Tabel invoice belum memiliki kolom tanggal pelunasan kas; metrik ini hanya menghitung DP cicilan & PO Cash/COD yang sudah disetujui.'
                ],
                'supplier_obligations' => [
                    'code'           => 'VYM:AP_OBLIGATION',
                    'title'          => 'Freight & Vendor Obligations (Unpaid)',
                    'classification' => 'ACTUAL • PAYABLE OBLIGATION',
                    'badge_type'     => 'actual',
                    'nominal'        => round($obligations_unpaid, 2),
                    'approved_unpaid'=> round($tagihan['approved_unpaid_nominal'], 2),
                    'waiting_review' => round($tagihan['waiting_unpaid_nominal'], 2),
                    'count'          => $tagihan['unpaid_count'],
                    'overdue_count'  => $tagihan['overdue_count'],
                    'formula'        => 'SUM(COALESCE(NULLIF(total_tagihan,0), nilai_tagihan + biaya_asuransi)) WHERE status_approval != 99 AND status_bayar != "sudah_dibayar"',
                    'source_tables'  => 'tagihan_ekspedisi, ekspedisi',
                    'accounting_note'=> 'Kewajiban tagihan vendor/ekspedisi yang belum dibayar (Accounts Payable). Dipisahkan dari pengeluaran kas aktual.'
                ],
                'actual_cash_outflow' => [
                    'code'           => 'VYM:ACTUAL_CASH_OUT',
                    'title'          => 'Actual Cash Expenditures (Paid)',
                    'classification' => 'ACTUAL • SETTLED OUTFLOW',
                    'badge_type'     => 'actual',
                    'nominal'        => round($cash_out_paid, 2),
                    'paid_freight'   => round($tagihan['paid_nominal'], 2),
                    'paid_payroll'   => round($payroll['total_nominal'], 2),
                    'count'          => $tagihan['paid_count'] + $payroll['slip_count'],
                    'paid_freight_cnt'=> $tagihan['paid_count'],
                    'payroll_months' => $payroll['month_count'],
                    'payroll_slips'  => $payroll['slip_count'],
                    'formula'        => 'SUM(tagihan_ekspedisi WHERE status_bayar = "sudah_dibayar") + SUM(salary_history net take-home pay)',
                    'source_tables'  => 'tagihan_ekspedisi (status_bayar="sudah_dibayar"), salary_history',
                    'accounting_note'=> 'Pengeluaran kas aktual yang telah dibayarkan (tagihan ekspedisi berstatus sudah_dibayar + realisasi tutup buku gaji karyawan).'
                ],
                'profitability_guard' => [
                    'code'           => 'VYM:PROFIT_GUARD',
                    'title'          => 'Operating Contribution & COGS Status',
                    'classification' => $cogs_status['has_unit_cogs'] ? 'ACTUAL • VERIFIED' : 'GUARDRAIL • COGS INCOMPLETE',
                    'badge_type'     => 'estimated',
                    'nominal'        => round($invoice_dpp - $cash_out_paid, 2),
                    'invoice_dpp'    => round($invoice_dpp, 2),
                    'verified_opex'  => round($cash_out_paid, 2),
                    'coverage_ratio' => $billing_to_cashout_ratio,
                    'cogs_status'    => $cogs_status,
                    'formula'        => 'Billed Net DPP (invoice.total) - Verified Settled Outflow (Paid Freight + Closed Payroll) [Excludes Unverified Unit COGS]',
                    'source_tables'  => 'invoice, tagihan_ekspedisi, salary_history, acc_pemasok_harga',
                    'accounting_note'=> 'Sesuai Prinsip Akuntansi VYM BI: Laba Bersih (Net Profit) tidak dihitung secara semu karena HPP (COGS) per batch barang keluar belum terhubung penuh di tabel transaksi.'
                ]
            ],
            'time_series'             => $buckets,
            'top_customers'           => $top_customers,
            'recent_ledger'           => $recent_ledger,
            'financial_intelligence'  => $fin_intel,
            'business_analytics'      => $biz_analytics,
            'predictive_intelligence' => $pred_intel
        ];
    }

    /**
     * 1. Fetch Customer Orders (po_masuk) — Zero-Crash Schema-Verified
     */
    public function fetchCustomerOrders($start_date, $end_date)
    {
        $res = [
            'total_nominal'      => 0.0,
            'total_subtotal'     => 0.0,
            'total_ppn'          => 0.0,
            'total_ongkir'       => 0.0,
            'approved_nominal'   => 0.0,
            'pending_nominal'    => 0.0,
            'verified_dp_nominal'=> 0.0,
            'cicilan_dp_only'    => 0.0,
            'cash_approved_only' => 0.0,
            'count'              => 0,
            'approved_count'     => 0,
            'pending_count'      => 0,
            'cash_or_dp_count'   => 0,
            'rows'               => []
        ];

        $fields = $this->safeListFields('po_masuk');
        if (empty($fields) || !isset($fields['tanggal_po'])) {
            return $res;
        }

        // Pre-fetch item totals per id_po in PHP if po_masuk_item exists
        $item_totals_by_po = [];
        $item_fields = $this->safeListFields('po_masuk_item');
        if (!empty($item_fields) && isset($item_fields['id_po']) && isset($item_fields['total_harga'])) {
            $q_it = $this->safeQuery("SELECT id_po, total_harga FROM po_masuk_item");
            if ($q_it) {
                foreach ($q_it->result_array() as $ir) {
                    $pid = (int)$ir['id_po'];
                    if (!isset($item_totals_by_po[$pid])) {
                        $item_totals_by_po[$pid] = 0.0;
                    }
                    $item_totals_by_po[$pid] += $this->cleanNumber($ir['total_harga']);
                }
            }
        }

        // Pre-fetch customer names if customer table exists
        $cust_names = [];
        $cust_fields = $this->safeListFields('customer');
        if (!empty($cust_fields) && isset($cust_fields['id_customer']) && isset($cust_fields['nama_customer'])) {
            $q_c = $this->safeQuery("SELECT id_customer, nama_customer FROM customer");
            if ($q_c) {
                foreach ($q_c->result_array() as $cr) {
                    $cust_names[(int)$cr['id_customer']] = trim((string)$cr['nama_customer']);
                }
            }
        }

        // Pre-fetch pengguna names if pengguna table exists
        $user_names = [];
        $user_fields = $this->safeListFields('pengguna');
        if (!empty($user_fields) && isset($user_fields['pengguna_id']) && isset($user_fields['nama'])) {
            $q_u = $this->safeQuery("SELECT pengguna_id, nama FROM pengguna");
            if ($q_u) {
                foreach ($q_u->result_array() as $ur) {
                    $user_names[(int)$ur['pengguna_id']] = trim((string)$ur['nama']);
                }
            }
        }

        $where_del = isset($fields['deleted']) ? "AND p.deleted = 0" : "";
        $where_sts = isset($fields['status']) ? "AND p.status != 'ditolak'" : "";

        $sql = "
            SELECT p.*
            FROM po_masuk p
            WHERE p.tanggal_po IS NOT NULL
              AND p.tanggal_po != '0000-00-00'
              AND p.tanggal_po >= " . $this->db->escape($start_date) . "
              AND p.tanggal_po <= " . $this->db->escape($end_date) . "
              {$where_del}
              {$where_sts}
            ORDER BY p.tanggal_po DESC
        ";

        $q = $this->safeQuery($sql);
        if (!$q) {
            return $res;
        }

        foreach ($q->result_array() as $r) {
            $id_po = isset($r['id_po']) ? (int)$r['id_po'] : 0;
            $tgl   = $this->cleanDate(isset($r['tanggal_po']) ? $r['tanggal_po'] : '');
            if (!$tgl) continue;

            $v_akhir = isset($r['nominal_total_akhir']) ? $this->cleanNumber($r['nominal_total_akhir']) : 0.0;
            $v_po    = isset($r['nominal_po']) ? $this->cleanNumber($r['nominal_po']) : 0.0;
            $v_sub   = isset($r['nominal_subtotal']) ? $this->cleanNumber($r['nominal_subtotal']) : 0.0;
            $v_item  = isset($item_totals_by_po[$id_po]) ? $item_totals_by_po[$id_po] : 0.0;

            $tot = 0.0;
            if ($v_akhir > 0) $tot = $v_akhir;
            elseif ($v_po > 0) $tot = $v_po;
            elseif ($v_sub > 0) $tot = $v_sub;
            elseif ($v_item > 0) $tot = $v_item;

            $sub = ($v_sub > 0) ? $v_sub : $tot;
            $ppn = isset($r['nominal_ppn']) ? max(0.0, $this->cleanNumber($r['nominal_ppn'])) : 0.0;
            $ong = isset($r['nominal_ongkir']) ? max(0.0, $this->cleanNumber($r['nominal_ongkir'])) : 0.0;
            $dp  = isset($r['cicilan_dp_nominal']) ? max(0.0, $this->cleanNumber($r['cicilan_dp_nominal'])) : 0.0;

            $pay = !empty($r['sistem_pembayaran']) ? trim((string)$r['sistem_pembayaran']) : 'Cash';
            $sts = !empty($r['status']) ? strtolower(trim((string)$r['status'])) : 'menunggu_kroscek';
            $prs = !empty($r['status_proses']) ? trim((string)$r['status_proses']) : '-';

            $c_name = '';
            if (!empty($r['nama_customer'])) {
                $c_name = trim((string)$r['nama_customer']);
            } elseif (!empty($r['id_customer']) && isset($cust_names[(int)$r['id_customer']])) {
                $c_name = $cust_names[(int)$r['id_customer']];
            }
            if ($c_name === '') $c_name = '-';

            $s_name = '';
            if (!empty($r['nama_sales'])) {
                $s_name = trim((string)$r['nama_sales']);
            } elseif (!empty($r['created_by']) && isset($user_names[(int)$r['created_by']])) {
                $s_name = $user_names[(int)$r['created_by']];
            }
            if ($s_name === '') $s_name = 'Office / Direct';

            $res['total_nominal']  += $tot;
            $res['total_subtotal'] += $sub;
            $res['total_ppn']      += $ppn;
            $res['total_ongkir']   += $ong;
            $res['count']++;

            if ($sts === 'disetujui') {
                $res['approved_nominal'] += $tot;
                $res['approved_count']++;
            } else {
                $res['pending_nominal'] += $tot;
                $res['pending_count']++;
            }

            $pay_up = strtoupper($pay);
            if ($dp > 0) {
                $res['verified_dp_nominal'] += $dp;
                $res['cicilan_dp_only']     += $dp;
                $res['cash_or_dp_count']++;
            } elseif (in_array($pay_up, ['CASH', 'COD'], true) && $sts === 'disetujui') {
                $res['verified_dp_nominal'] += $tot;
                $res['cash_approved_only']  += $tot;
                $res['cash_or_dp_count']++;
            }

            $res['rows'][] = [
                'id_po'             => $id_po,
                'no_po'             => !empty($r['no_po']) ? trim((string)$r['no_po']) : ('PO-' . $id_po),
                'tgl'               => $tgl,
                'nominal_total'     => $tot,
                'nominal_subtotal'  => $sub,
                'nominal_ppn'       => $ppn,
                'nominal_ongkir'    => $ong,
                'dp_nominal'        => $dp,
                'sistem_pembayaran' => $pay,
                'status_po'         => $sts,
                'status_proses'     => $prs,
                'nama_sales'        => $s_name,
                'customer_nama'     => $c_name
            ];
        }

        return $res;
    }

    /**
     * 2. Fetch Issued Customer Invoices (invoice) — Zero-Crash PHP Parsed
     */
    public function fetchIssuedInvoices($start_date, $end_date)
    {
        $res = [
            'total_nominal' => 0.0,
            'total_dpp'     => 0.0,
            'total_ongkir'  => 0.0,
            'count'         => 0,
            'shipped_count' => 0,
            'rows'          => []
        ];

        $fields = $this->safeListFields('invoice');
        if (empty($fields) || !isset($fields['tgl_invoice'])) {
            return $res;
        }

        // Pre-fetch detail_barang_invoice totals per id_invoice in PHP
        $dbi_totals = [];
        $dbi_fields = $this->safeListFields('detail_barang_invoice');
        if (!empty($dbi_fields) && isset($dbi_fields['id_invoice']) && isset($dbi_fields['harga_total'])) {
            $where_dbi_sts = isset($dbi_fields['status']) ? "WHERE status = 1" : "";
            $q_dbi = $this->safeQuery("SELECT id_invoice, harga_total FROM detail_barang_invoice {$where_dbi_sts}");
            if ($q_dbi) {
                foreach ($q_dbi->result_array() as $dr) {
                    $iid = (int)$dr['id_invoice'];
                    if (!isset($dbi_totals[$iid])) {
                        $dbi_totals[$iid] = 0.0;
                    }
                    $dbi_totals[$iid] += $this->cleanNumber($dr['harga_total']);
                }
            }
        }

        $cust_names = [];
        $cust_fields = $this->safeListFields('customer');
        if (!empty($cust_fields) && isset($cust_fields['id_customer']) && isset($cust_fields['nama_customer'])) {
            $q_c = $this->safeQuery("SELECT id_customer, nama_customer FROM customer");
            if ($q_c) {
                foreach ($q_c->result_array() as $cr) {
                    $cust_names[(int)$cr['id_customer']] = trim((string)$cr['nama_customer']);
                }
            }
        }

        $user_names = [];
        $user_fields = $this->safeListFields('pengguna');
        if (!empty($user_fields) && isset($user_fields['pengguna_id']) && isset($user_fields['nama'])) {
            $q_u = $this->safeQuery("SELECT pengguna_id, nama FROM pengguna");
            if ($q_u) {
                foreach ($q_u->result_array() as $ur) {
                    $user_names[(int)$ur['pengguna_id']] = trim((string)$ur['nama']);
                }
            }
        }

        $where_sts = isset($fields['status']) ? "AND inv.status = 1" : "";

        $sql = "
            SELECT inv.*
            FROM invoice inv
            WHERE inv.tgl_invoice IS NOT NULL
              AND inv.tgl_invoice != '0000-00-00'
              AND inv.tgl_invoice >= " . $this->db->escape($start_date) . "
              AND inv.tgl_invoice <= " . $this->db->escape($end_date) . "
              {$where_sts}
            ORDER BY inv.tgl_invoice DESC
        ";

        $q = $this->safeQuery($sql);
        if (!$q) {
            return $res;
        }

        foreach ($q->result_array() as $r) {
            $id_inv = isset($r['id_invoice']) ? (int)$r['id_invoice'] : 0;
            $tgl    = $this->cleanDate(isset($r['tgl_invoice']) ? $r['tgl_invoice'] : '');
            if (!$tgl) continue;

            $v_kes = isset($r['total_keseluruhan']) ? $this->cleanNumber($r['total_keseluruhan']) : 0.0;
            $v_tot = isset($r['total']) ? $this->cleanNumber($r['total']) : 0.0;
            $v_sub = isset($r['sub_total']) ? $this->cleanNumber($r['sub_total']) : 0.0;
            $v_dbi = isset($dbi_totals[$id_inv]) ? $dbi_totals[$id_inv] : 0.0;

            $tot = 0.0;
            if ($v_kes > 0) $tot = $v_kes;
            elseif ($v_tot > 0) $tot = $v_tot;
            elseif ($v_sub > 0) $v_sub = $v_sub;
            if ($tot <= 0) {
                $tot = ($v_sub > 0) ? $v_sub : $v_dbi;
            }

            $dpp = ($v_tot > 0) ? $v_tot : $tot;
            $ong = isset($r['ongkir']) ? max(0.0, $this->cleanNumber($r['ongkir'])) : 0.0;
            $brg = !empty($r['status_barang_keluar']) ? trim((string)$r['status_barang_keluar']) : 'belum_keluar';

            $c_name = (!empty($r['id_customer']) && isset($cust_names[(int)$r['id_customer']]))
                ? $cust_names[(int)$r['id_customer']]
                : '-';
            $m_name = (!empty($r['id_marketing']) && isset($user_names[(int)$r['id_marketing']]))
                ? $user_names[(int)$r['id_marketing']]
                : 'Office / Direct';

            $res['total_nominal'] += $tot;
            $res['total_dpp']     += $dpp;
            $res['total_ongkir']  += $ong;
            $res['count']++;

            if ($brg === 'sudah_keluar') {
                $res['shipped_count']++;
            }

            $res['rows'][] = [
                'id_invoice'           => $id_inv,
                'no_invoice'           => !empty($r['no_invoice']) ? trim((string)$r['no_invoice']) : ('INV-' . $id_inv),
                'tgl'                  => $tgl,
                'no_po'                => isset($r['no_po']) ? trim((string)$r['no_po']) : '',
                'nominal_total'        => $tot,
                'nominal_dpp'          => $dpp,
                'nominal_ongkir'       => $ong,
                'status_barang_keluar' => $brg,
                'customer_nama'        => $c_name,
                'marketing_nama'       => $m_name
            ];
        }

        return $res;
    }

    /**
     * 3. Fetch Tax Recognition (approval_faktur_pajak) — Zero-Crash PHP Parsed
     */
    public function fetchTaxRecognitions($start_date, $end_date)
    {
        $res = [
            'approved_dpp'   => 0.0,
            'approved_ppn'   => 0.0,
            'pending_dpp'    => 0.0,
            'pending_ppn'    => 0.0,
            'approved_count' => 0,
            'pending_count'  => 0
        ];

        $fields = $this->safeListFields('approval_faktur_pajak');
        if (empty($fields) || !isset($fields['tanggal'])) {
            return $res;
        }

        $sql = "
            SELECT *
            FROM approval_faktur_pajak
            WHERE tanggal IS NOT NULL
              AND tanggal != '0000-00-00'
              AND tanggal >= " . $this->db->escape($start_date) . "
              AND tanggal <= " . $this->db->escape($end_date) . "
        ";

        $q = $this->safeQuery($sql);
        if (!$q) {
            return $res;
        }

        foreach ($q->result_array() as $r) {
            $sts = isset($r['status']) ? (int)$r['status'] : 0;
            $dpp = isset($r['dpp']) ? max(0.0, $this->cleanNumber($r['dpp'])) : 0.0;
            $ppn = isset($r['ppn']) ? max(0.0, $this->cleanNumber($r['ppn'])) : 0.0;

            if ($sts === 4) {
                $res['approved_dpp'] += $dpp;
                $res['approved_ppn'] += $ppn;
                $res['approved_count']++;
            } elseif (in_array($sts, [0, 1, 2, 3], true)) {
                $res['pending_dpp'] += $dpp;
                $res['pending_ppn'] += $ppn;
                $res['pending_count']++;
            }
        }

        return $res;
    }

    /**
     * 4. Fetch Supplier / Freight Obligations vs Settled Payments (tagihan_ekspedisi)
     */
    public function fetchFreightObligationsAndPayments($start_date, $end_date)
    {
        $res = [
            'paid_nominal'            => 0.0,
            'unpaid_nominal'          => 0.0,
            'approved_unpaid_nominal' => 0.0,
            'waiting_unpaid_nominal'  => 0.0,
            'paid_count'              => 0,
            'unpaid_count'            => 0,
            'overdue_count'           => 0,
            'rows'                    => []
        ];

        $fields = $this->safeListFields('tagihan_ekspedisi');
        if (empty($fields)) {
            return $res;
        }

        // Map ekspedisi names
        $ekspedisi_names = [];
        $eks_fields = $this->safeListFields('ekspedisi');
        if (!empty($eks_fields) && isset($eks_fields['id_ekspedisi']) && isset($eks_fields['nama_ekspedisi'])) {
            $q_e = $this->safeQuery("SELECT id_ekspedisi, nama_ekspedisi FROM ekspedisi");
            if ($q_e) {
                foreach ($q_e->result_array() as $er) {
                    $ekspedisi_names[(int)$er['id_ekspedisi']] = trim((string)$er['nama_ekspedisi']);
                }
            }
        }

        // Map tracking_barang -> id_ekspedisi & no_sj
        $tracking_map = [];
        $trk_fields = $this->safeListFields('tracking_barang');
        if (!empty($trk_fields) && isset($trk_fields['id_tracking'])) {
            $q_t = $this->safeQuery("SELECT * FROM tracking_barang");
            if ($q_t) {
                foreach ($q_t->result_array() as $tr) {
                    $tid = (int)$tr['id_tracking'];
                    $eid = isset($tr['id_ekspedisi']) ? (int)$tr['id_ekspedisi'] : 0;
                    $tracking_map[$tid] = [
                        'vendor_nama' => isset($ekspedisi_names[$eid]) ? $ekspedisi_names[$eid] : 'Vendor Ekspedisi',
                        'no_sj'       => !empty($tr['no_sj']) ? trim((string)$tr['no_sj']) : '-'
                    ];
                }
            }
        }

        $q = $this->safeQuery("SELECT * FROM tagihan_ekspedisi");
        if (!$q) {
            return $res;
        }

        $today_ts = time();
        foreach ($q->result_array() as $r) {
            $app_sts = isset($r['status_approval']) ? (int)$r['status_approval'] : 5;
            if ($app_sts === 99) continue;

            $raw_tgl = '';
            if (!empty($r['tanggal_invoice']) && $r['tanggal_invoice'] !== '0000-00-00') {
                $raw_tgl = $r['tanggal_invoice'];
            } elseif (!empty($r['created_at'])) {
                $raw_tgl = $r['created_at'];
            }
            $tgl = $this->cleanDate($raw_tgl);
            if (!$tgl || $tgl < $start_date || $tgl > $end_date) {
                continue;
            }

            $v_tot = isset($r['total_tagihan']) ? $this->cleanNumber($r['total_tagihan']) : 0.0;
            $v_nil = isset($r['nilai_tagihan']) ? $this->cleanNumber($r['nilai_tagihan']) : 0.0;
            $v_asu = isset($r['biaya_asuransi']) ? $this->cleanNumber($r['biaya_asuransi']) : 0.0;
            $nom   = ($v_tot > 0) ? $v_tot : ($v_nil + $v_asu);
            if ($nom <= 0) continue;

            $id_tg   = isset($r['id_tagihan']) ? (int)$r['id_tagihan'] : (isset($r['id']) ? (int)$r['id'] : 0);
            $no_dok  = !empty($r['no_invoice']) ? trim((string)$r['no_invoice']) : ('TAG-' . $id_tg);
            $byr_str = isset($r['status_bayar']) ? strtolower(trim((string)$r['status_bayar'])) : 'belum_dibayar';
            $is_paid = ($byr_str === 'sudah_dibayar');

            $tid         = isset($r['id_tracking']) ? (int)$r['id_tracking'] : 0;
            $vendor_nama = isset($tracking_map[$tid]) ? $tracking_map[$tid]['vendor_nama'] : 'Vendor Ekspedisi';
            $no_sj       = isset($tracking_map[$tid]) ? $tracking_map[$tid]['no_sj'] : '-';

            if ($is_paid) {
                $res['paid_nominal'] += $nom;
                $res['paid_count']++;
            } else {
                $res['unpaid_nominal'] += $nom;
                $res['unpaid_count']++;
                if ($app_sts === 5) {
                    $res['approved_unpaid_nominal'] += $nom;
                } else {
                    $res['waiting_unpaid_nominal'] += $nom;
                }
                $inv_ts = strtotime($tgl);
                if ($inv_ts && (($today_ts - $inv_ts) / 86400) > 30) {
                    $res['overdue_count']++;
                }
            }

            $res['rows'][] = [
                'id_tagihan'      => $id_tg,
                'no_dokumen'      => $no_dok,
                'no_sj'           => $no_sj,
                'tgl'             => $tgl,
                'nominal_total'   => $nom,
                'status_bayar'    => $byr_str,
                'status_approval' => $app_sts,
                'vendor_nama'     => $vendor_nama
            ];
        }

        // Sort descending by date in PHP
        usort($res['rows'], function ($a, $b) {
            if ($b['tgl'] === $a['tgl']) return 0;
            return ($b['tgl'] > $a['tgl']) ? 1 : -1;
        });

        return $res;
    }

    /**
     * 5. Fetch Actual Payroll Expenditures (salary_history) — Zero-Crash PHP Grouped
     */
    public function fetchPayrollExpenditures($start_date, $end_date)
    {
        $res = [
            'total_nominal' => 0.0,
            'month_count'   => 0,
            'slip_count'    => 0,
            'rows'          => []
        ];

        $fields = $this->safeListFields('salary_history');
        if (empty($fields)) {
            return $res;
        }

        $q = $this->safeQuery("SELECT * FROM salary_history");
        if (!$q) {
            return $res;
        }

        $add_keys = ['gajipokok', 'tunjangankonsumsi', 'tunjangankinerja', 'tunjangankomunikasi', 'tunjangantransport', 'tunjanganjabatan', 'bonus'];
        $by_date  = [];

        foreach ($q->result_array() as $r) {
            $tgl = null;
            if (!empty($r['tahun']) && !empty($r['bulan']) && is_numeric($r['tahun']) && is_numeric($r['bulan'])) {
                $y = (int)$r['tahun'];
                $m = (int)$r['bulan'];
                if ($y >= 2000 && $y <= 2100 && $m >= 1 && $m <= 12) {
                    $tgl = sprintf('%04d-%02d-25', $y, $m);
                }
            } elseif (!empty($r['created_at'])) {
                $tgl = $this->cleanDate($r['created_at']);
            }

            if (!$tgl || $tgl < $start_date || $tgl > $end_date) {
                continue;
            }

            $sum_add = 0.0;
            foreach ($add_keys as $k) {
                if (isset($r[$k])) {
                    $sum_add += $this->cleanNumber($r[$k]);
                }
            }
            $pot = isset($r['potonganlainnya']) ? $this->cleanNumber($r['potonganlainnya']) : 0.0;
            $net = max(0.0, $sum_add - $pot);
            if ($net <= 0) continue;

            if (!isset($by_date[$tgl])) {
                $by_date[$tgl] = ['nominal_total' => 0.0, 'slip_cnt' => 0];
            }
            $by_date[$tgl]['nominal_total'] += $net;
            $by_date[$tgl]['slip_cnt']++;
        }

        krsort($by_date);

        foreach ($by_date as $tgl => $agg) {
            $nom = $agg['nominal_total'];
            $cnt = $agg['slip_cnt'];
            $res['total_nominal'] += $nom;
            $res['month_count']++;
            $res['slip_count'] += $cnt;
            $res['rows'][] = [
                'tgl'           => $tgl,
                'nominal_total' => $nom,
                'slip_cnt'      => $cnt,
                'no_dokumen'    => 'PAYROLL-' . date('Ym', strtotime($tgl)),
                'vendor_nama'   => "Gaji SDM ({$cnt} Pegawai)"
            ];
        }

        return $res;
    }

    /**
     * 6. Inspect COGS (Harga Pokok Penjualan) Data Coverage
     */
    public function inspectCogsCoverage()
    {
        $supplier_price_cnt = 0;
        if ($this->safeTableExists('acc_pemasok_harga')) {
            $q_cnt = $this->safeQuery("SELECT COUNT(*) AS cnt FROM acc_pemasok_harga");
            if ($q_cnt && $q_cnt->row()) {
                $supplier_price_cnt = (int)$q_cnt->row()->cnt;
            }
        }

        $db_fields = $this->safeListFields('detail_barang');
        $has_detail_barang_hpp = (isset($db_fields['harga_beli']) || isset($db_fields['hpp']));

        return [
            'has_unit_cogs'       => $has_detail_barang_hpp,
            'supplier_price_rows' => $supplier_price_cnt,
            'status_label'        => $has_detail_barang_hpp ? 'VERIFIED BATCH COGS' : 'PARTIAL (SUPPLIER CATALOG ONLY)',
            'explanation'         => 'Tabel detail_barang_keluar & detail_barang_invoice belum menyimpan HPP per batch keluar secara langsung. Katalog harga pemasok tercatat sebanyak ' . $supplier_price_cnt . ' dokumen di acc_pemasok_harga.'
        ];
    }

    /**
     * Build Time-Series Buckets for TradingView / Bloomberg Terminal Chart
     */
    private function buildTimeSeriesBuckets($start_date, $end_date, $interval, $po_rows, $inv_rows, $tagihan_rows, $payroll_rows)
    {
        $getBucketKey = function ($dateStr) use ($interval) {
            $ts = @strtotime($dateStr);
            if (!$ts || $ts <= 0) return null;
            if ($interval === '1M') {
                return date('Y-m-01', $ts);
            } elseif ($interval === '1W') {
                $dow = (int)date('N', $ts);
                return date('Y-m-d', strtotime('-' . ($dow - 1) . ' days', $ts));
            }
            return date('Y-m-d', $ts);
        };

        $makeBucket = function () {
            return [
                'order_val'      => 0.0,
                'order_cnt'      => 0,
                'invoice_val'    => 0.0,
                'invoice_cnt'    => 0,
                'cash_out_val'   => 0.0,
                'cash_out_cnt'   => 0,
                'obligation_val' => 0.0,
                'obligation_cnt' => 0
            ];
        };

        $buckets = [];

        if ($interval === '1M') {
            $s_ts = @strtotime(date('Y-m-01', strtotime($start_date)));
            $e_ts = @strtotime(date('Y-m-01', strtotime($end_date)));
            $months_diff = (($e_ts - $s_ts) / (86400 * 30));
            if ($months_diff >= 0 && $months_diff <= 36) {
                $cur = $s_ts;
                while ($cur <= $e_ts) {
                    $buckets[date('Y-m-01', $cur)] = $makeBucket();
                    $cur = strtotime('+1 month', $cur);
                }
            }
        }

        foreach ($po_rows as $r) {
            $k = $getBucketKey($r['tgl']);
            if (!$k) continue;
            if (!isset($buckets[$k])) $buckets[$k] = $makeBucket();
            $buckets[$k]['order_val'] += max(0.0, (float)$r['nominal_total']);
            $buckets[$k]['order_cnt']++;
        }

        foreach ($inv_rows as $r) {
            $k = $getBucketKey($r['tgl']);
            if (!$k) continue;
            if (!isset($buckets[$k])) $buckets[$k] = $makeBucket();
            $buckets[$k]['invoice_val'] += max(0.0, (float)$r['nominal_total']);
            $buckets[$k]['invoice_cnt']++;
        }

        foreach ($tagihan_rows as $r) {
            $k = $getBucketKey($r['tgl']);
            if (!$k) continue;
            if (!isset($buckets[$k])) $buckets[$k] = $makeBucket();
            $val = max(0.0, (float)$r['nominal_total']);
            if (strtolower(trim((string)$r['status_bayar'])) === 'sudah_dibayar') {
                $buckets[$k]['cash_out_val'] += $val;
                $buckets[$k]['cash_out_cnt']++;
            } else {
                $buckets[$k]['obligation_val'] += $val;
                $buckets[$k]['obligation_cnt']++;
            }
        }

        foreach ($payroll_rows as $r) {
            $k = $getBucketKey($r['tgl']);
            if (!$k) continue;
            if (!isset($buckets[$k])) $buckets[$k] = $makeBucket();
            $buckets[$k]['cash_out_val'] += max(0.0, (float)$r['nominal_total']);
            $buckets[$k]['cash_out_cnt'] += (int)$r['slip_cnt'];
        }

        ksort($buckets);

        $series_orders      = [];
        $series_invoices    = [];
        $series_cash_out    = [];
        $series_obligations = [];
        $series_volume      = [];
        $meta_by_date       = [];

        foreach ($buckets as $dateKey => $b) {
            $ord_v = round($b['order_val'], 2);
            $inv_v = round($b['invoice_val'], 2);
            $out_v = round($b['cash_out_val'], 2);
            $obl_v = round($b['obligation_val'], 2);
            $vol_c = $b['order_cnt'] + $b['invoice_cnt'] + $b['cash_out_cnt'] + $b['obligation_cnt'];

            $series_orders[]      = ['time' => $dateKey, 'value' => $ord_v];
            $series_invoices[]    = ['time' => $dateKey, 'value' => $inv_v];
            $series_cash_out[]    = ['time' => $dateKey, 'value' => $out_v];
            $series_obligations[] = ['time' => $dateKey, 'value' => $obl_v];
            $series_volume[]      = [
                'time'  => $dateKey,
                'value' => $vol_c,
                'color' => ($inv_v >= $out_v) ? 'rgba(16, 185, 129, 0.40)' : 'rgba(239, 68, 68, 0.40)'
            ];

            $meta_by_date[$dateKey] = [
                'order_val'      => $ord_v,
                'order_cnt'      => $b['order_cnt'],
                'invoice_val'    => $inv_v,
                'invoice_cnt'    => $b['invoice_cnt'],
                'cash_out_val'   => $out_v,
                'cash_out_cnt'   => $b['cash_out_cnt'],
                'obligation_val' => $obl_v,
                'obligation_cnt' => $b['obligation_cnt'],
                'vol_cnt'        => $vol_c
            ];
        }

        return [
            'orders'      => $series_orders,
            'invoices'    => $series_invoices,
            'cash_out'    => $series_cash_out,
            'obligations' => $series_obligations,
            'volume'      => $series_volume,
            'meta'        => $meta_by_date
        ];
    }

    /**
     * Build Top Customer Breakdown
     */
    private function buildCustomerBreakdown($po_rows, $inv_rows)
    {
        $map = [];

        foreach ($po_rows as $r) {
            $name = trim((string)($r['customer_nama'] ?: 'Pelanggan Umum'));
            if ($name === '' || $name === '-') $name = 'Pelanggan Umum';
            $key  = strtolower($name);
            if (!isset($map[$key])) {
                $map[$key] = [
                    'customer_nama' => $name,
                    'order_nominal' => 0.0,
                    'order_count'   => 0,
                    'inv_nominal'   => 0.0,
                    'inv_count'     => 0
                ];
            }
            $map[$key]['order_nominal'] += max(0.0, (float)$r['nominal_total']);
            $map[$key]['order_count']++;
        }

        foreach ($inv_rows as $r) {
            $name = trim((string)($r['customer_nama'] ?: 'Pelanggan Umum'));
            if ($name === '' || $name === '-') $name = 'Pelanggan Umum';
            $key  = strtolower($name);
            if (!isset($map[$key])) {
                $map[$key] = [
                    'customer_nama' => $name,
                    'order_nominal' => 0.0,
                    'order_count'   => 0,
                    'inv_nominal'   => 0.0,
                    'inv_count'     => 0
                ];
            }
            $map[$key]['inv_nominal'] += max(0.0, (float)$r['nominal_total']);
            $map[$key]['inv_count']++;
        }

        $list = array_values($map);
        usort($list, function ($a, $b) {
            $scoreA = max($a['order_nominal'], $a['inv_nominal']);
            $scoreB = max($b['order_nominal'], $b['inv_nominal']);
            if ($scoreB == $scoreA) return 0;
            return ($scoreB > $scoreA) ? 1 : -1;
        });

        return array_slice($list, 0, 10);
    }

    /**
     * Build Unified Institutional Audit Ledger
     */
    private function buildUnifiedAuditLedger($po_rows, $inv_rows, $tagihan_rows, $payroll_rows)
    {
        $ledger = [];

        foreach (array_slice($po_rows, 0, 20) as $r) {
            $ledger[] = [
                'tgl'          => $r['tgl'],
                'kode_dok'     => $r['no_po'] ?: ('PO-' . $r['id_po']),
                'kategori'     => 'CUSTOMER ORDER (PO)',
                'klasifikasi'  => 'ACTUAL • ORDER BOOK',
                'badge_color'  => 'cyan',
                'entitas'      => $r['customer_nama'] ?: '-',
                'status_dok'   => strtoupper((string)$r['status_po']),
                'sumber_tabel' => 'po_masuk',
                'nominal'      => round((float)$r['nominal_total'], 2),
                'arah_kas'     => 'NON-CASH (KOMITMEN PO)'
            ];
        }

        foreach (array_slice($inv_rows, 0, 20) as $r) {
            $ledger[] = [
                'tgl'          => $r['tgl'],
                'kode_dok'     => $r['no_invoice'] ?: ('INV-' . $r['id_invoice']),
                'kategori'     => 'ISSUED INVOICE',
                'klasifikasi'  => 'ACTUAL • BILLED AR',
                'badge_color'  => 'emerald',
                'entitas'      => $r['customer_nama'] ?: '-',
                'status_dok'   => ($r['status_barang_keluar'] === 'sudah_keluar') ? 'BARANG KELUAR' : 'TERBIT FAKTUR',
                'sumber_tabel' => 'invoice',
                'nominal'      => round((float)$r['nominal_total'], 2),
                'arah_kas'     => 'RECEIVABLE (PIUTANG TAGIH)'
            ];
        }

        foreach (array_slice($tagihan_rows, 0, 20) as $r) {
            $is_paid = (strtolower(trim((string)$r['status_bayar'])) === 'sudah_dibayar');
            $ledger[] = [
                'tgl'          => $r['tgl'],
                'kode_dok'     => $r['no_dokumen'],
                'kategori'     => $is_paid ? 'PAID FREIGHT EXPENSE' : 'VENDOR OBLIGATION (AP)',
                'klasifikasi'  => $is_paid ? 'ACTUAL • CASH OUT' : 'ACTUAL • UNPAID AP',
                'badge_color'  => $is_paid ? 'rose' : 'amber',
                'entitas'      => $r['vendor_nama'] ?: 'Vendor Ekspedisi',
                'status_dok'   => $is_paid ? 'SUDAH DIBAYAR' : 'BELUM DIBAYAR',
                'sumber_tabel' => 'tagihan_ekspedisi',
                'nominal'      => round((float)$r['nominal_total'], 2),
                'arah_kas'     => $is_paid ? 'CASH OUTFLOW (LUNAS)' : 'LIABILITAS (HUTANG)'
            ];
        }

        foreach (array_slice($payroll_rows, 0, 10) as $r) {
            $ledger[] = [
                'tgl'          => $r['tgl'],
                'kode_dok'     => $r['no_dokumen'],
                'kategori'     => 'PAYROLL EXPENDITURE',
                'klasifikasi'  => 'ACTUAL • CASH OUT',
                'badge_color'  => 'rose',
                'entitas'      => $r['vendor_nama'],
                'status_dok'   => 'TUTUP BUKU GAJI',
                'sumber_tabel' => 'salary_history',
                'nominal'      => round((float)$r['nominal_total'], 2),
                'arah_kas'     => 'CASH OUTFLOW (GAJI)'
            ];
        }

        usort($ledger, function ($a, $b) {
            if ($b['tgl'] === $a['tgl']) return 0;
            return ($b['tgl'] > $a['tgl']) ? 1 : -1;
        });

        return array_slice($ledger, 0, 35);
    }

    /**
     * =========================================================================
     * PHASE 3 — FINANCIAL INTELLIGENCE DEEP-DIVE ANALYTICS
     * =========================================================================
     */
    private function buildFinancialIntelligencePayload($start_date, $end_date, $orders, $invoices, $taxes, $tagihan, $payroll, $invoiced_po_map, $cogs_status)
    {
        $monthly_map = [];
        $makeMonth = function ($ym) {
            $ts = @strtotime($ym . '-01');
            return [
                'period_ym'              => $ym,
                'label'                  => $ts ? date('M Y', $ts) : $ym,
                'orders_gross'           => 0.0,
                'orders_cnt'             => 0,
                'invoices_gross'         => 0.0,
                'invoices_dpp'           => 0.0,
                'invoices_ongkir'        => 0.0,
                'invoices_cnt'           => 0,
                'verified_dp_cash'       => 0.0,
                'paid_freight'           => 0.0,
                'paid_payroll'           => 0.0,
                'unpaid_obligations'     => 0.0,
                'operating_contribution' => 0.0
            ];
        };

        foreach ($orders['rows'] as $r) {
            $ym = substr((string)$r['tgl'], 0, 7);
            if (strlen($ym) !== 7) continue;
            if (!isset($monthly_map[$ym])) $monthly_map[$ym] = $makeMonth($ym);
            $tot = max(0.0, (float)$r['nominal_total']);
            $dp  = max(0.0, (float)$r['dp_nominal']);
            $sts = strtolower(trim((string)$r['status_po']));
            $pay = strtoupper(trim((string)$r['sistem_pembayaran']));

            $monthly_map[$ym]['orders_gross'] += $tot;
            $monthly_map[$ym]['orders_cnt']++;

            if ($dp > 0) {
                $monthly_map[$ym]['verified_dp_cash'] += $dp;
            } elseif (in_array($pay, ['CASH', 'COD'], true) && $sts === 'disetujui') {
                $monthly_map[$ym]['verified_dp_cash'] += $tot;
            }
        }

        foreach ($invoices['rows'] as $r) {
            $ym = substr((string)$r['tgl'], 0, 7);
            if (strlen($ym) !== 7) continue;
            if (!isset($monthly_map[$ym])) $monthly_map[$ym] = $makeMonth($ym);
            $monthly_map[$ym]['invoices_gross']  += max(0.0, (float)$r['nominal_total']);
            $monthly_map[$ym]['invoices_dpp']    += max(0.0, (float)$r['nominal_dpp']);
            $monthly_map[$ym]['invoices_ongkir'] += max(0.0, (float)$r['nominal_ongkir']);
            $monthly_map[$ym]['invoices_cnt']++;
        }

        foreach ($tagihan['rows'] as $r) {
            $ym = substr((string)$r['tgl'], 0, 7);
            if (strlen($ym) !== 7) continue;
            if (!isset($monthly_map[$ym])) $monthly_map[$ym] = $makeMonth($ym);
            $val = max(0.0, (float)$r['nominal_total']);
            if (strtolower(trim((string)$r['status_bayar'])) === 'sudah_dibayar') {
                $monthly_map[$ym]['paid_freight'] += $val;
            } else {
                $monthly_map[$ym]['unpaid_obligations'] += $val;
            }
        }

        foreach ($payroll['rows'] as $r) {
            $ym = substr((string)$r['tgl'], 0, 7);
            if (strlen($ym) !== 7) continue;
            if (!isset($monthly_map[$ym])) $monthly_map[$ym] = $makeMonth($ym);
            $monthly_map[$ym]['paid_payroll'] += max(0.0, (float)$r['nominal_total']);
        }

        ksort($monthly_map);
        $monthly_statement = [];
        $prev_billed = null;
        foreach ($monthly_map as $ym => $m) {
            $cash_out = $m['paid_freight'] + $m['paid_payroll'];
            $op_contr = $m['invoices_dpp'] - $cash_out;
            $mom_pct  = ($prev_billed !== null && $prev_billed > 0)
                ? round((($m['invoices_gross'] - $prev_billed) / $prev_billed) * 100, 1)
                : null;
            $prev_billed = $m['invoices_gross'];

            $m['orders_gross']           = round($m['orders_gross'], 2);
            $m['invoices_gross']         = round($m['invoices_gross'], 2);
            $m['invoices_dpp']           = round($m['invoices_dpp'], 2);
            $m['invoices_ongkir']        = round($m['invoices_ongkir'], 2);
            $m['verified_dp_cash']       = round($m['verified_dp_cash'], 2);
            $m['paid_freight']           = round($m['paid_freight'], 2);
            $m['paid_payroll']           = round($m['paid_payroll'], 2);
            $m['unpaid_obligations']     = round($m['unpaid_obligations'], 2);
            $m['operating_contribution'] = round($op_contr, 2);
            $m['mom_billing_pct']        = $mom_pct;
            $monthly_statement[]         = $m;
        }
        $monthly_statement = array_reverse($monthly_statement);

        $payment_terms = [];
        $sales_map     = [];
        $unbilled_list = [];
        $today_ts      = time();

        foreach ($orders['rows'] as $r) {
            $tot  = max(0.0, (float)$r['nominal_total']);
            $dp   = max(0.0, (float)$r['dp_nominal']);
            $term = strtoupper(trim((string)($r['sistem_pembayaran'] ?: 'LAINNYA')));
            if (!isset($payment_terms[$term])) {
                $payment_terms[$term] = [
                    'term'          => $term,
                    'order_count'   => 0,
                    'po_count'      => 0,
                    'order_nominal' => 0.0,
                    'dp_nominal'    => 0.0,
                    'share_pct'     => 0.0
                ];
            }
            $payment_terms[$term]['order_count']++;
            $payment_terms[$term]['po_count']++;
            $payment_terms[$term]['order_nominal'] += $tot;
            $payment_terms[$term]['dp_nominal']    += $dp;

            $sales_name = trim((string)($r['nama_sales'] ?: 'Office / Direct'));
            $s_key      = strtolower($sales_name);
            if (!isset($sales_map[$s_key])) {
                $sales_map[$s_key] = [
                    'sales_nama'     => $sales_name,
                    'order_count'    => 0,
                    'po_count'       => 0,
                    'order_nominal'  => 0.0,
                    'inv_count'      => 0,
                    'inv_nominal'    => 0.0,
                    'billed_nominal' => 0.0,
                    'realization_pct'=> 0.0
                ];
            }
            $sales_map[$s_key]['order_count']++;
            $sales_map[$s_key]['po_count']++;
            $sales_map[$s_key]['order_nominal'] += $tot;

            $norm_po = strtolower(trim((string)(isset($r['no_po']) ? $r['no_po'] : '')));
            $is_billed = ($norm_po !== '' && isset($invoiced_po_map[$norm_po]));
            if (!$is_billed && count($unbilled_list) < 20) {
                $po_ts = @strtotime($r['tgl']);
                $days_open = $po_ts ? max(0, (int)floor(($today_ts - $po_ts) / 86400)) : 0;
                $unbilled_list[] = [
                    'tgl'               => $r['tgl'],
                    'tanggal'           => $r['tgl'],
                    'no_po'             => $r['no_po'] ?: ('PO-' . $r['id_po']),
                    'customer_nama'     => $r['customer_nama'] ?: '-',
                    'nama_sales'        => $sales_name,
                    'sistem_pembayaran' => $r['sistem_pembayaran'] ?: '-',
                    'status_po'         => strtoupper((string)$r['status_po']),
                    'status_label'      => strtoupper((string)$r['status_po']),
                    'days_open'         => $days_open,
                    'nominal_total'     => round($tot, 2),
                    'nominal'           => round($tot, 2)
                ];
            }
        }

        foreach ($payment_terms as $tk => $pt_row) {
            $payment_terms[$tk]['order_nominal'] = round($pt_row['order_nominal'], 2);
            $payment_terms[$tk]['dp_nominal']    = round($pt_row['dp_nominal'], 2);
            $payment_terms[$tk]['share_pct']     = ($orders['total_nominal'] > 0)
                ? round(($pt_row['order_nominal'] / $orders['total_nominal']) * 100, 1)
                : 0.0;
        }

        foreach ($invoices['rows'] as $r) {
            $tot = max(0.0, (float)$r['nominal_total']);
            $mkt = trim((string)($r['marketing_nama'] ?: 'Office / Direct'));
            $s_key = strtolower($mkt);
            if (!isset($sales_map[$s_key])) {
                $sales_map[$s_key] = [
                    'sales_nama'     => $mkt,
                    'order_count'    => 0,
                    'po_count'       => 0,
                    'order_nominal'  => 0.0,
                    'inv_count'      => 0,
                    'inv_nominal'    => 0.0,
                    'billed_nominal' => 0.0,
                    'realization_pct'=> 0.0
                ];
            }
            $sales_map[$s_key]['inv_count']++;
            $sales_map[$s_key]['inv_nominal']    += $tot;
            $sales_map[$s_key]['billed_nominal'] += $tot;
        }

        foreach ($sales_map as $sk => $s_row) {
            $sales_map[$sk]['order_nominal']   = round($s_row['order_nominal'], 2);
            $sales_map[$sk]['inv_nominal']     = round($s_row['inv_nominal'], 2);
            $sales_map[$sk]['billed_nominal']  = round($s_row['billed_nominal'], 2);
            $sales_map[$sk]['realization_pct'] = ($s_row['order_nominal'] > 0)
                ? round(($s_row['billed_nominal'] / $s_row['order_nominal']) * 100, 1)
                : 0.0;
        }

        $sales_list = array_values($sales_map);
        usort($sales_list, function ($a, $b) {
            $mA = max($a['order_nominal'], $a['inv_nominal']);
            $mB = max($b['order_nominal'], $b['inv_nominal']);
            if ($mB == $mA) return 0;
            return ($mB > $mA) ? 1 : -1;
        });

        $revenue_funnel = [
            [
                'stage'          => '1. Customer Orders (PO Masuk)',
                'source'         => 'po_masuk (status != ditolak)',
                'classification' => 'ACTUAL • CONTRACTED ORDER',
                'count'          => $orders['count'],
                'doc_count'      => $orders['count'],
                'nominal'        => round($orders['total_nominal'], 2),
                'pct_of_top'     => 100.0
            ],
            [
                'stage'          => '2. Approved Orders (Kroscek Disetujui)',
                'source'         => 'po_masuk (status = disetujui)',
                'classification' => 'ACTUAL • APPROVED ORDER',
                'count'          => $orders['approved_count'],
                'doc_count'      => $orders['approved_count'],
                'nominal'        => round($orders['approved_nominal'], 2),
                'pct_of_top'     => ($orders['total_nominal'] > 0) ? round(($orders['approved_nominal'] / $orders['total_nominal']) * 100, 1) : 0.0
            ],
            [
                'stage'          => '3. Issued Invoices (Penagihan Terbit / AR)',
                'source'         => 'invoice (status = 1)',
                'classification' => 'ACTUAL • BILLED RECEIVABLE',
                'count'          => $invoices['count'],
                'doc_count'      => $invoices['count'],
                'nominal'        => round($invoices['total_nominal'], 2),
                'pct_of_top'     => ($orders['total_nominal'] > 0) ? round(($invoices['total_nominal'] / $orders['total_nominal']) * 100, 1) : 0.0
            ],
            [
                'stage'          => '4. Approved Faktur Pajak (DPP + PPN)',
                'source'         => 'approval_faktur_pajak (status = 4)',
                'classification' => 'ACTUAL • TAX RECOGNIZED',
                'count'          => $taxes['approved_count'],
                'doc_count'      => $taxes['approved_count'],
                'nominal'        => round($taxes['approved_dpp'] + $taxes['approved_ppn'], 2),
                'pct_of_top'     => ($orders['total_nominal'] > 0) ? round((($taxes['approved_dpp'] + $taxes['approved_ppn']) / $orders['total_nominal']) * 100, 1) : 0.0
            ],
            [
                'stage'          => '5. Verified Down Payments & Cash/COD PO',
                'source'         => 'po_masuk (cicilan_dp_nominal + Cash/COD)',
                'classification' => 'ACTUAL • RECORDED DP/CASH',
                'count'          => $orders['cash_or_dp_count'],
                'doc_count'      => $orders['cash_or_dp_count'],
                'nominal'        => round($orders['verified_dp_nominal'], 2),
                'pct_of_top'     => ($orders['total_nominal'] > 0) ? round(($orders['verified_dp_nominal'] / $orders['total_nominal']) * 100, 1) : 0.0
            ]
        ];

        $inv_gross    = round($invoices['total_nominal'], 2);
        $inv_ongkir   = round($invoices['total_ongkir'], 2);
        $inv_dpp      = round($invoices['total_dpp'], 2);
        $paid_freight = round($tagihan['paid_nominal'], 2);
        $paid_payroll = round($payroll['total_nominal'], 2);
        $op_contrib   = round($inv_dpp - ($paid_freight + $paid_payroll), 2);

        $profitability_waterfall = [
            [
                'step'           => '1. Gross Billed Revenue (Total Invoice)',
                'type'           => 'BASE',
                'formula'        => 'SUM(invoice.total_keseluruhan) WHERE status = 1',
                'classification' => 'ACTUAL • invoice.total_keseluruhan',
                'nominal'        => $inv_gross,
                'pct_of_gross'   => 100.0
            ],
            [
                'step'           => '2. (-) Billed Shipping Pass-Through (Ongkir Invoice)',
                'type'           => 'DEDUCTION',
                'formula'        => '-SUM(invoice.ongkir) WHERE status = 1',
                'classification' => 'ACTUAL • invoice.ongkir',
                'nominal'        => -$inv_ongkir,
                'pct_of_gross'   => ($inv_gross > 0) ? round(($inv_ongkir / $inv_gross) * 100, 1) : 0.0
            ],
            [
                'step'           => '3. (=) Net Billed Revenue (DPP Penjualan)',
                'type'           => 'SUBTOTAL',
                'formula'        => 'SUM(invoice.total) [Gross - Ongkir]',
                'classification' => 'ACTUAL • invoice.total',
                'nominal'        => $inv_dpp,
                'pct_of_gross'   => ($inv_gross > 0) ? round(($inv_dpp / $inv_gross) * 100, 1) : 0.0
            ],
            [
                'step'           => '4. (-) Settled Logistics & Freight Expense (Lunas)',
                'type'           => 'DEDUCTION',
                'formula'        => '-SUM(tagihan_ekspedisi.total_tagihan) WHERE status_bayar = "sudah_dibayar"',
                'classification' => 'ACTUAL • tagihan_ekspedisi (sudah_dibayar)',
                'nominal'        => -$paid_freight,
                'pct_of_gross'   => ($inv_gross > 0) ? round(($paid_freight / $inv_gross) * 100, 1) : 0.0
            ],
            [
                'step'           => '5. (-) Closed SDM Payroll Expense (Gaji & Tunjangan)',
                'type'           => 'DEDUCTION',
                'formula'        => '-SUM(salary_history net take-home pay)',
                'classification' => 'ACTUAL • salary_history',
                'nominal'        => -$paid_payroll,
                'pct_of_gross'   => ($inv_gross > 0) ? round(($paid_payroll / $inv_gross) * 100, 1) : 0.0
            ],
            [
                'step'           => '6. (=) Verified Operating Contribution (Pre-Unit COGS)',
                'type'           => 'RESULT',
                'formula'        => 'Net Billed DPP - (Paid Freight + Closed Payroll)',
                'classification' => 'GUARDRAIL • EXCLUDES UNVERIFIED UNIT COGS',
                'nominal'        => $op_contrib,
                'pct_of_gross'   => ($inv_gross > 0) ? round(($op_contrib / $inv_gross) * 100, 1) : 0.0
            ]
        ];

        $top_skus = $this->fetchTopBilledSkus($start_date, $end_date);

        $payables_watchlist = [];
        foreach ($tagihan['rows'] as $r) {
            if (strtolower(trim((string)$r['status_bayar'])) === 'sudah_dibayar') continue;
            $inv_ts = @strtotime($r['tgl']);
            $days   = $inv_ts ? max(0, (int)floor(($today_ts - $inv_ts) / 86400)) : 0;
            $nom_v  = round((float)$r['nominal_total'], 2);
            $payables_watchlist[] = [
                'tgl'             => $r['tgl'],
                'tanggal_invoice' => $r['tgl'],
                'no_dokumen'      => $r['no_dokumen'],
                'nomor_invoice'   => $r['no_dokumen'],
                'vendor_nama'     => $r['vendor_nama'],
                'nama_ekspedisi'  => $r['vendor_nama'],
                'nomor_sj'        => isset($r['no_sj']) ? $r['no_sj'] : '-',
                'status_approval' => ((int)$r['status_approval'] === 5) ? 'APPROVED (SIAP BAYAR)' : ('PROSES LEVEL ' . (int)$r['status_approval']),
                'days_aging'      => $days,
                'aging_bucket'    => ($days > 60) ? '> 60 Hari (Kritis)' : (($days > 30) ? '31–60 Hari (Overdue)' : '0–30 Hari (Current)'),
                'nominal_total'   => $nom_v,
                'nominal'         => $nom_v
            ];
        }
        usort($payables_watchlist, function ($a, $b) {
            if ($b['days_aging'] == $a['days_aging']) return 0;
            return ($b['days_aging'] > $a['days_aging']) ? 1 : -1;
        });

        return [
            'monthly_statement'       => $monthly_statement,
            'revenue_funnel'          => $revenue_funnel,
            'payment_terms_breakdown' => array_values($payment_terms),
            'sales_attribution'       => array_slice($sales_list, 0, 12),
            'unbilled_orders_list'    => $unbilled_list,
            'profitability_waterfall' => $profitability_waterfall,
            'top_billed_skus'         => $top_skus,
            'cogs_audit'              => $cogs_status,
            'payables_watchlist'      => array_slice($payables_watchlist, 0, 20)
        ];
    }

    /**
     * Fetch Top Billed Products / SKUs — Zero-Crash PHP Aggregated
     */
    private function fetchTopBilledSkus($start_date, $end_date)
    {
        $matrix = $this->fetchProductVelocityMatrix($start_date, $end_date);
        $skus   = [];

        foreach ($matrix['matrix'] as $m) {
            $is_inv = ($m['billed_nominal'] > 0);
            $qty    = $is_inv ? $m['billed_qty'] : $m['ordered_qty'];
            $nom    = $is_inv ? $m['billed_nominal'] : $m['ordered_nominal'];
            $src    = $is_inv ? 'detail_barang_invoice' : 'po_masuk_item';
            if ($nom <= 0) continue;

            $skus[] = [
                'kode_barang'   => $m['kode_barang'],
                'nama_barang'   => $m['nama_barang'],
                'item_nama'     => $m['nama_barang'],
                'total_qty'     => $qty,
                'qty_billed'    => $qty,
                'avg_price'     => $m['avg_unit_price'],
                'total_nominal' => $nom,
                'gross_billed'  => $nom,
                'doc_count'     => max(1, (int)$m['doc_count']),
                'source_table'  => $src,
                'source'        => $src
            ];
            if (count($skus) >= 15) break;
        }

        return $skus;
    }

    /**
     * =========================================================================
     * PHASE 4 — BUSINESS ANALYTICS (SUBMODULE 6: BUSINESS PERFORMANCE)
     * =========================================================================
     */
    private function buildBusinessAnalyticsPayload($start_date, $end_date, $orders, $invoices, $tagihan)
    {
        $cust_map = [];
        foreach ($orders['rows'] as $r) {
            $name = trim((string)($r['customer_nama'] ?: 'Pelanggan Umum'));
            if ($name === '' || $name === '-') $name = 'Pelanggan Umum';
            $key  = strtolower($name);
            if (!isset($cust_map[$key])) {
                $cust_map[$key] = [
                    'customer_nama' => $name,
                    'order_count'   => 0,
                    'order_nominal' => 0.0,
                    'inv_count'     => 0,
                    'inv_nominal'   => 0.0,
                    'last_tx_date'  => $r['tgl']
                ];
            }
            $cust_map[$key]['order_count']++;
            $cust_map[$key]['order_nominal'] += max(0.0, (float)$r['nominal_total']);
            if ($r['tgl'] > $cust_map[$key]['last_tx_date']) {
                $cust_map[$key]['last_tx_date'] = $r['tgl'];
            }
        }

        foreach ($invoices['rows'] as $r) {
            $name = trim((string)($r['customer_nama'] ?: 'Pelanggan Umum'));
            if ($name === '' || $name === '-') $name = 'Pelanggan Umum';
            $key  = strtolower($name);
            if (!isset($cust_map[$key])) {
                $cust_map[$key] = [
                    'customer_nama' => $name,
                    'order_count'   => 0,
                    'order_nominal' => 0.0,
                    'inv_count'     => 0,
                    'inv_nominal'   => 0.0,
                    'last_tx_date'  => $r['tgl']
                ];
            }
            $cust_map[$key]['inv_count']++;
            $cust_map[$key]['inv_nominal'] += max(0.0, (float)$r['nominal_total']);
            if ($r['tgl'] > $cust_map[$key]['last_tx_date']) {
                $cust_map[$key]['last_tx_date'] = $r['tgl'];
            }
        }

        $cust_list = array_values($cust_map);
        usort($cust_list, function ($a, $b) {
            $valA = max($a['inv_nominal'], $a['order_nominal']);
            $valB = max($b['inv_nominal'], $b['order_nominal']);
            if ($valB == $valA) return 0;
            return ($valB > $valA) ? 1 : -1;
        });

        $total_basis = ($invoices['total_nominal'] > 0)
            ? $invoices['total_nominal']
            : max(1.0, $orders['total_nominal']);

        $hhi_sum    = 0.0;
        $top1_share = 0.0;
        $top3_share = 0.0;
        $top5_share = 0.0;
        $top1_name  = '-';

        $customer_matrix = [];
        foreach ($cust_list as $idx => $c) {
            $basis_val = ($invoices['total_nominal'] > 0) ? $c['inv_nominal'] : $c['order_nominal'];
            $share_pct = ($total_basis > 0) ? (($basis_val / $total_basis) * 100.0) : 0.0;
            $hhi_sum  += ($share_pct * $share_pct);

            if ($idx === 0) {
                $top1_share = round($share_pct, 1);
                $top1_name  = $c['customer_nama'];
            }
            if ($idx < 3) $top3_share += $share_pct;
            if ($idx < 5) $top5_share += $share_pct;

            if ($idx < 20) {
                $real_pct = ($c['order_nominal'] > 0)
                    ? round(($c['inv_nominal'] / $c['order_nominal']) * 100, 1)
                    : (($c['inv_nominal'] > 0) ? 100.0 : 0.0);
                $aov = ($c['order_count'] > 0)
                    ? round($c['order_nominal'] / $c['order_count'], 2)
                    : round($c['inv_nominal'] / max(1, $c['inv_count']), 2);

                $customer_matrix[] = [
                    'customer_nama'       => $c['customer_nama'],
                    'order_count'         => $c['order_count'],
                    'order_nominal'       => round($c['order_nominal'], 2),
                    'inv_count'           => $c['inv_count'],
                    'inv_nominal'         => round($c['inv_nominal'], 2),
                    'unbilled_gap'        => round(max(0.0, $c['order_nominal'] - $c['inv_nominal']), 2),
                    'realization_pct'     => $real_pct,
                    'share_of_billed_pct' => round($share_pct, 1),
                    'avg_order_val'       => $aov,
                    'last_tx_date'        => $c['last_tx_date']
                ];
            }
        }

        $hhi_index = (int)round($hhi_sum);
        $conc_tier = 'TERDIVERSIFIKASI (LOW < 1.500)';
        if ($hhi_index > 2500) {
            $conc_tier = 'KONSENTRASI TINGGI (HIGH > 2.500)';
        } elseif ($hhi_index >= 1500) {
            $conc_tier = 'MODERAT (1.500 – 2.500)';
        }

        $customer_intelligence = [
            'summary' => [
                'active_customers'   => count($cust_list),
                'top1_name'          => $top1_name,
                'top1_share_pct'     => round($top1_share, 1),
                'top3_share_pct'     => round(min(100.0, $top3_share), 1),
                'top5_share_pct'     => round(min(100.0, $top5_share), 1),
                'hhi_index'          => $hhi_index,
                'concentration_risk' => $conc_tier,
                'avg_order_value'    => ($orders['count'] > 0) ? round($orders['total_nominal'] / $orders['count'], 2) : 0.0,
                'avg_invoice_value'  => ($invoices['count'] > 0) ? round($invoices['total_nominal'] / $invoices['count'], 2) : 0.0
            ],
            'matrix' => $customer_matrix
        ];

        $product_intelligence = $this->fetchProductVelocityMatrix($start_date, $end_date);

        $vendor_map = [];
        foreach ($tagihan['rows'] as $r) {
            $v_name = trim((string)($r['vendor_nama'] ?: 'Vendor Ekspedisi'));
            $v_key  = strtolower($v_name);
            if (!isset($vendor_map[$v_key])) {
                $vendor_map[$v_key] = [
                    'vendor_nama'    => $v_name,
                    'doc_count'      => 0,
                    'paid_nominal'   => 0.0,
                    'unpaid_nominal' => 0.0,
                    'total_nominal'  => 0.0
                ];
            }
            $nom = max(0.0, (float)$r['nominal_total']);
            $vendor_map[$v_key]['doc_count']++;
            $vendor_map[$v_key]['total_nominal'] += $nom;
            if (strtolower(trim((string)$r['status_bayar'])) === 'sudah_dibayar') {
                $vendor_map[$v_key]['paid_nominal'] += $nom;
            } else {
                $vendor_map[$v_key]['unpaid_nominal'] += $nom;
            }
        }

        $freight_vendors = [];
        foreach ($vendor_map as $v) {
            $tot_v = max(0.0, $v['total_nominal']);
            $cnt_v = max(1, $v['doc_count']);
            $freight_vendors[] = [
                'vendor_nama'       => $v['vendor_nama'],
                'doc_count'         => $v['doc_count'],
                'paid_nominal'      => round($v['paid_nominal'], 2),
                'unpaid_nominal'    => round($v['unpaid_nominal'], 2),
                'total_nominal'     => round($tot_v, 2),
                'avg_shipment_cost' => round($tot_v / $cnt_v, 2),
                'paid_ratio_pct'    => ($tot_v > 0) ? round(($v['paid_nominal'] / $tot_v) * 100, 1) : 0.0
            ];
        }
        usort($freight_vendors, function ($a, $b) {
            if ($b['total_nominal'] == $a['total_nominal']) return 0;
            return ($b['total_nominal'] > $a['total_nominal']) ? 1 : -1;
        });

        $principal_suppliers = $this->fetchPrincipalSupplierAnalytics();

        $po_date_by_nopo = [];
        $status_pipeline = [];
        foreach ($orders['rows'] as $r) {
            $norm_po = strtolower(trim((string)(isset($r['no_po']) ? $r['no_po'] : '')));
            if ($norm_po !== '' && $norm_po !== '-' && !empty($r['tgl'])) {
                if (!isset($po_date_by_nopo[$norm_po]) || $r['tgl'] < $po_date_by_nopo[$norm_po]) {
                    $po_date_by_nopo[$norm_po] = $r['tgl'];
                }
            }
            $st_label = 'PO: ' . strtoupper((string)($r['status_po'] ?: 'MENUNGGU'));
            if (!isset($status_pipeline[$st_label])) {
                $status_pipeline[$st_label] = [
                    'stage'     => $st_label,
                    'source'    => 'po_masuk.status',
                    'doc_count' => 0,
                    'nominal'   => 0.0
                ];
            }
            $status_pipeline[$st_label]['doc_count']++;
            $status_pipeline[$st_label]['nominal'] += max(0.0, (float)$r['nominal_total']);
        }

        $lead_days_sum = 0;
        $lead_matched  = 0;
        foreach ($invoices['rows'] as $r) {
            $norm_po = strtolower(trim((string)(isset($r['no_po']) ? $r['no_po'] : '')));
            if ($norm_po !== '' && isset($po_date_by_nopo[$norm_po]) && !empty($r['tgl'])) {
                $t1 = @strtotime($po_date_by_nopo[$norm_po]);
                $t2 = @strtotime($r['tgl']);
                if ($t1 && $t2 && $t2 >= $t1) {
                    $lead_days_sum += (int)floor(($t2 - $t1) / 86400);
                    $lead_matched++;
                }
            }
            $bk_label = 'INVOICE: ' . strtoupper(str_replace('_', ' ', (string)($r['status_barang_keluar'] ?: 'BELUM KELUAR')));
            if (!isset($status_pipeline[$bk_label])) {
                $status_pipeline[$bk_label] = [
                    'stage'     => $bk_label,
                    'source'    => 'invoice.status_barang_keluar',
                    'doc_count' => 0,
                    'nominal'   => 0.0
                ];
            }
            $status_pipeline[$bk_label]['doc_count']++;
            $status_pipeline[$bk_label]['nominal'] += max(0.0, (float)$r['nominal_total']);
        }

        foreach ($status_pipeline as $sk => $sp) {
            $status_pipeline[$sk]['nominal'] = round($sp['nominal'], 2);
        }

        $avg_lead_days = ($lead_matched > 0) ? round($lead_days_sum / $lead_matched, 1) : 0.0;
        $approval_rate = ($orders['count'] > 0) ? round(($orders['approved_count'] / $orders['count']) * 100, 1) : 0.0;
        $dispatch_rate = ($invoices['count'] > 0) ? round(($invoices['shipped_count'] / $invoices['count']) * 100, 1) : 0.0;
        $total_freight = $tagihan['paid_nominal'] + $tagihan['unpaid_nominal'];
        $freight_ratio = ($invoices['total_nominal'] > 0) ? round(($total_freight / $invoices['total_nominal']) * 100, 2) : 0.0;

        $operational_intelligence = [
            'kpis' => [
                'order_approval_rate_pct'    => $approval_rate,
                'invoice_dispatch_rate_pct'  => $dispatch_rate,
                'avg_po_to_invoice_days'     => $avg_lead_days,
                'matched_po_invoice_count'   => $lead_matched,
                'freight_cost_to_billed_pct' => $freight_ratio
            ],
            'pipeline_breakdown' => array_values($status_pipeline)
        ];

        return [
            'customer_intelligence'           => $customer_intelligence,
            'product_intelligence'            => $product_intelligence,
            'supplier_logistics_intelligence' => [
                'freight_vendors'     => array_slice($freight_vendors, 0, 15),
                'principal_suppliers' => $principal_suppliers
            ],
            'operational_intelligence'        => $operational_intelligence
        ];
    }

    /**
     * Fetch Combined Product / SKU Commercial Matrix — Zero-Crash PHP Aggregated
     */
    private function fetchProductVelocityMatrix($start_date, $end_date)
    {
        $sku_map          = [];
        $ordered_sku_keys = [];
        $billed_sku_keys  = [];
        $units_ordered    = 0.0;
        $units_billed     = 0.0;

        // Pre-fetch master barang map
        $barang_map = [];
        $b_fields = $this->safeListFields('barang');
        if (!empty($b_fields) && isset($b_fields['id_barang'])) {
            $q_b = $this->safeQuery("SELECT * FROM barang");
            if ($q_b) {
                foreach ($q_b->result_array() as $br) {
                    $bid = (int)$br['id_barang'];
                    $barang_map[$bid] = [
                        'kode_barang' => !empty($br['kode_barang']) ? trim((string)$br['kode_barang']) : ('SKU-' . $bid),
                        'nama_barang' => !empty($br['nama_barang']) ? trim((string)$br['nama_barang']) : ('Barang #' . $bid)
                    ];
                }
            }
        }

        // 1. Ordered Items from po_masuk_item + po_masuk (filtered & grouped in PHP)
        $pm_fields  = $this->safeListFields('po_masuk');
        $pmi_fields = $this->safeListFields('po_masuk_item');
        if (!empty($pm_fields) && !empty($pmi_fields) && isset($pmi_fields['id_po'])) {
            $where_del = isset($pm_fields['deleted']) ? "AND p.deleted = 0" : "";
            $where_sts = isset($pm_fields['status']) ? "AND p.status != 'ditolak'" : "";
            $sql_ord = "
                SELECT i.*
                FROM po_masuk_item i
                INNER JOIN po_masuk p ON p.id_po = i.id_po
                WHERE p.tanggal_po IS NOT NULL
                  AND p.tanggal_po != '0000-00-00'
                  AND p.tanggal_po >= " . $this->db->escape($start_date) . "
                  AND p.tanggal_po <= " . $this->db->escape($end_date) . "
                  {$where_del}
                  {$where_sts}
            ";
            $q_ord = $this->safeQuery($sql_ord);
            if ($q_ord) {
                foreach ($q_ord->result_array() as $r) {
                    $bid  = isset($r['id_barang']) ? (int)$r['id_barang'] : 0;
                    $name = !empty($r['nama_barang']) ? trim((string)$r['nama_barang']) : (isset($barang_map[$bid]) ? $barang_map[$bid]['nama_barang'] : 'Item PO');
                    $kode = !empty($r['tipe_barang']) ? trim((string)$r['tipe_barang']) : (isset($barang_map[$bid]) ? $barang_map[$bid]['kode_barang'] : ('ITEM-' . $bid));
                    $qty  = isset($r['qty']) ? max(0.0, $this->cleanNumber($r['qty'])) : 0.0;
                    $nom  = isset($r['total_harga']) ? max(0.0, $this->cleanNumber($r['total_harga'])) : 0.0;
                    if ($nom <= 0 && $qty <= 0) continue;

                    $key = strtolower($name);
                    if (!isset($sku_map[$key])) {
                        $sku_map[$key] = [
                            'kode_barang'     => $kode,
                            'nama_barang'     => $name,
                            'ordered_qty'     => 0.0,
                            'ordered_nominal' => 0.0,
                            'billed_qty'      => 0.0,
                            'billed_nominal'  => 0.0,
                            'doc_count'       => 0
                        ];
                    }
                    $sku_map[$key]['ordered_qty']     += $qty;
                    $sku_map[$key]['ordered_nominal'] += $nom;
                    $sku_map[$key]['doc_count']++;
                    $units_ordered += $qty;
                    $ordered_sku_keys[$key] = true;
                }
            }
        }

        // 2. Billed Items from detail_barang_invoice + invoice (filtered & grouped in PHP)
        $inv_fields = $this->safeListFields('invoice');
        $dbi_fields = $this->safeListFields('detail_barang_invoice');
        if (!empty($inv_fields) && !empty($dbi_fields) && isset($dbi_fields['id_invoice'])) {
            $where_inv_sts = isset($inv_fields['status']) ? "AND inv.status = 1" : "";
            $where_dbi_sts = isset($dbi_fields['status']) ? "AND dbi.status = 1" : "";
            $sql_inv = "
                SELECT dbi.*
                FROM detail_barang_invoice dbi
                INNER JOIN invoice inv ON inv.id_invoice = dbi.id_invoice
                WHERE inv.tgl_invoice IS NOT NULL
                  AND inv.tgl_invoice != '0000-00-00'
                  AND inv.tgl_invoice >= " . $this->db->escape($start_date) . "
                  AND inv.tgl_invoice <= " . $this->db->escape($end_date) . "
                  {$where_inv_sts}
                  {$where_dbi_sts}
            ";
            $q_inv = $this->safeQuery($sql_inv);
            if ($q_inv) {
                foreach ($q_inv->result_array() as $r) {
                    $bid  = isset($r['id_barang']) ? (int)$r['id_barang'] : 0;
                    $name = isset($barang_map[$bid]) ? $barang_map[$bid]['nama_barang'] : (!empty($r['nama_barang']) ? trim((string)$r['nama_barang']) : ('Item Invoice #' . $bid));
                    $kode = isset($barang_map[$bid]) ? $barang_map[$bid]['kode_barang'] : ('SKU-' . $bid);
                    $qty  = isset($r['qty']) ? max(0.0, $this->cleanNumber($r['qty'])) : 0.0;
                    $nom  = isset($r['harga_total']) ? max(0.0, $this->cleanNumber($r['harga_total'])) : 0.0;
                    if ($nom <= 0 && $qty <= 0) continue;

                    $key = strtolower($name);
                    if (!isset($sku_map[$key])) {
                        $sku_map[$key] = [
                            'kode_barang'     => $kode,
                            'nama_barang'     => $name,
                            'ordered_qty'     => 0.0,
                            'ordered_nominal' => 0.0,
                            'billed_qty'      => 0.0,
                            'billed_nominal'  => 0.0,
                            'doc_count'       => 0
                        ];
                    } else {
                        $sku_map[$key]['kode_barang'] = $kode ?: $sku_map[$key]['kode_barang'];
                    }
                    $sku_map[$key]['billed_qty']     += $qty;
                    $sku_map[$key]['billed_nominal'] += $nom;
                    $sku_map[$key]['doc_count']++;
                    $units_billed += $qty;
                    $billed_sku_keys[$key] = true;
                }
            }
        }

        $sku_list = [];
        foreach ($sku_map as $s) {
            $ord_q = round($s['ordered_qty'], 2);
            $ord_n = round($s['ordered_nominal'], 2);
            $inv_q = round($s['billed_qty'], 2);
            $inv_n = round($s['billed_nominal'], 2);
            $ref_n = ($inv_n > 0) ? $inv_n : $ord_n;
            $ref_q = ($inv_q > 0) ? $inv_q : max(1.0, $ord_q);

            $sku_list[] = [
                'kode_barang'     => $s['kode_barang'],
                'nama_barang'     => $s['nama_barang'],
                'ordered_qty'     => $ord_q,
                'ordered_nominal' => $ord_n,
                'billed_qty'      => $inv_q,
                'billed_nominal'  => $inv_n,
                'doc_count'       => $s['doc_count'],
                'fulfillment_pct' => ($ord_q > 0) ? round(($inv_q / $ord_q) * 100, 1) : (($inv_q > 0) ? 100.0 : 0.0),
                'avg_unit_price'  => round($ref_n / max(1.0, $ref_q), 2)
            ];
        }

        usort($sku_list, function ($a, $b) {
            $vA = max($a['billed_nominal'], $a['ordered_nominal']);
            $vB = max($b['billed_nominal'], $b['ordered_nominal']);
            if ($vB == $vA) return 0;
            return ($vB > $vA) ? 1 : -1;
        });

        return [
            'summary' => [
                'distinct_skus_ordered' => count($ordered_sku_keys),
                'distinct_skus_billed'  => count($billed_sku_keys),
                'total_units_ordered'   => round($units_ordered, 2),
                'total_units_billed'    => round($units_billed, 2),
                'unit_fulfillment_pct'  => ($units_ordered > 0) ? round(($units_billed / $units_ordered) * 100, 1) : 0.0
            ],
            'matrix' => array_slice($sku_list, 0, 20)
        ];
    }

    /**
     * Fetch Principal / Medical Device Supplier Catalog Analytics — Zero-Crash PHP Joined
     */
    private function fetchPrincipalSupplierAnalytics()
    {
        $res = [
            'stats' => [
                'total_suppliers'    => 0,
                'active_suppliers'   => 0,
                'price_catalog_docs' => 0,
                'agreement_docs'     => 0
            ],
            'rows' => []
        ];

        $p_fields = $this->safeListFields('acc_pemasok');
        if (empty($p_fields)) {
            return $res;
        }

        $harga_by_sup = [];
        if ($this->safeTableExists('acc_pemasok_harga')) {
            $q_h = $this->safeQuery("SELECT id_pemasok FROM acc_pemasok_harga");
            if ($q_h) {
                foreach ($q_h->result_array() as $hr) {
                    $res['stats']['price_catalog_docs']++;
                    $sid = isset($hr['id_pemasok']) ? (int)$hr['id_pemasok'] : 0;
                    $harga_by_sup[$sid] = (isset($harga_by_sup[$sid]) ? $harga_by_sup[$sid] : 0) + 1;
                }
            }
        }

        $agree_by_sup = [];
        if ($this->safeTableExists('acc_pemasok_agreement')) {
            $q_a = $this->safeQuery("SELECT id_pemasok FROM acc_pemasok_agreement");
            if ($q_a) {
                foreach ($q_a->result_array() as $ar) {
                    $res['stats']['agreement_docs']++;
                    $sid = isset($ar['id_pemasok']) ? (int)$ar['id_pemasok'] : 0;
                    $agree_by_sup[$sid] = (isset($agree_by_sup[$sid]) ? $agree_by_sup[$sid] : 0) + 1;
                }
            }
        }

        $prod_by_sup = [];
        if ($this->safeTableExists('acc_pemasok_product')) {
            $q_pr = $this->safeQuery("SELECT id_pemasok FROM acc_pemasok_product");
            if ($q_pr) {
                foreach ($q_pr->result_array() as $pr) {
                    $sid = isset($pr['id_pemasok']) ? (int)$pr['id_pemasok'] : 0;
                    $prod_by_sup[$sid] = (isset($prod_by_sup[$sid]) ? $prod_by_sup[$sid] : 0) + 1;
                }
            }
        }

        $status_names = [];
        if ($this->safeTableExists('acc_master_status_pemasok')) {
            $q_s = $this->safeQuery("SELECT id, nama FROM acc_master_status_pemasok");
            if ($q_s) {
                foreach ($q_s->result_array() as $sr) {
                    $status_names[(int)$sr['id']] = trim((string)$sr['nama']);
                }
            }
        }

        $tipe_names = [];
        if ($this->safeTableExists('acc_master_tipe_pemasok')) {
            $q_t = $this->safeQuery("SELECT id, nama FROM acc_master_tipe_pemasok");
            if ($q_t) {
                foreach ($q_t->result_array() as $tr) {
                    $tipe_names[(int)$tr['id']] = trim((string)$tr['nama']);
                }
            }
        }

        $where_sd = isset($p_fields['status_data']) ? "WHERE status_data = 1" : "";
        $q = $this->safeQuery("SELECT * FROM acc_pemasok {$where_sd}");
        if (!$q) {
            return $res;
        }

        $all_rows = [];
        foreach ($q->result_array() as $r) {
            $sid = isset($r['id']) ? (int)$r['id'] : 0;
            $st_id = isset($r['id_status']) ? (int)$r['id_status'] : 1;
            $tp_id = isset($r['id_tipe']) ? (int)$r['id_tipe'] : 0;

            $res['stats']['total_suppliers']++;
            if ($st_id === 1) {
                $res['stats']['active_suppliers']++;
            }

            $all_rows[] = [
                'nama_pemasok'    => !empty($r['nama_pemasok']) ? trim((string)$r['nama_pemasok']) : ('Pemasok #' . $sid),
                'negara'          => !empty($r['negara']) ? trim((string)$r['negara']) : '-',
                'status_nama'     => isset($status_names[$st_id]) ? $status_names[$st_id] : 'Aktif',
                'tipe_nama'       => isset($tipe_names[$tp_id]) ? $tipe_names[$tp_id] : 'Principal',
                'product_count'   => isset($prod_by_sup[$sid]) ? $prod_by_sup[$sid] : 0,
                'harga_doc_count' => isset($harga_by_sup[$sid]) ? $harga_by_sup[$sid] : 0,
                'agreement_count' => isset($agree_by_sup[$sid]) ? $agree_by_sup[$sid] : 0,
                'tanggal_loa'     => !empty($r['tanggal_loa_awal']) ? $r['tanggal_loa_awal'] : '-'
            ];
        }

        usort($all_rows, function ($a, $b) {
            if ($b['harga_doc_count'] != $a['harga_doc_count']) {
                return ($b['harga_doc_count'] > $a['harga_doc_count']) ? 1 : -1;
            }
            if ($b['product_count'] == $a['product_count']) return 0;
            return ($b['product_count'] > $a['product_count']) ? 1 : -1;
        });

        $res['rows'] = array_slice($all_rows, 0, 15);
        return $res;
    }

    /**
     * =========================================================================
     * PHASE 5 — PREDICTIVE INTELLIGENCE (SUBMODULES 7 & 8)
     * =========================================================================
     */
    private function buildPredictiveIntelligencePayload(
        $start_date,
        $end_date,
        $orders,
        $invoices,
        $taxes,
        $tagihan,
        $payroll,
        $unbilled_po_nominal,
        $unbilled_po_count,
        $cogs_status,
        $fin_intel,
        $biz_analytics
    ) {
        $monthly_asc = array_reverse(isset($fin_intel['monthly_statement']) ? $fin_intel['monthly_statement'] : []);

        $ord_series = [];
        $inv_series = [];
        $out_series = [];
        foreach ($monthly_asc as $m) {
            if ($m['orders_gross'] > 0 || $m['invoices_gross'] > 0 || ($m['paid_freight'] + $m['paid_payroll']) > 0) {
                $ord_series[] = (float)$m['orders_gross'];
                $inv_series[] = (float)$m['invoices_gross'];
                $out_series[] = (float)($m['paid_freight'] + $m['paid_payroll']);
            }
        }

        $computeForecastStep = function ($arr, $step) {
            $cnt = count($arr);
            if ($cnt === 0) return 0.0;
            if ($cnt === 1) return max(0.0, $arr[0]);

            $tail  = array_slice($arr, -3);
            $t_cnt = count($tail);
            $w_sum = 0.0;
            $w_tot = 0.0;
            for ($i = 0; $i < $t_cnt; $i++) {
                $w = ($i + 1);
                $w_sum += $tail[$i] * $w;
                $w_tot += $w;
            }
            $wma = ($w_tot > 0) ? ($w_sum / $w_tot) : 0.0;

            $first = $tail[0];
            $last  = $tail[$t_cnt - 1];
            $slope = ($t_cnt > 1) ? (($last - $first) / ($t_cnt - 1)) : 0.0;
            $damped_slope = $slope * 0.35;

            return max(0.0, $wma + ($damped_slope * $step));
        };

        $conv_rate = ($orders['total_nominal'] > 0)
            ? min(1.0, max(0.25, $invoices['total_nominal'] / $orders['total_nominal']))
            : 0.65;

        $backlog_m1 = $unbilled_po_nominal * $conv_rate * 0.45;
        $backlog_m2 = $unbilled_po_nominal * $conv_rate * 0.25;
        $backlog_m3 = $unbilled_po_nominal * $conv_rate * 0.15;

        $base_ts = @strtotime(date('Y-m-01', strtotime($end_date)));
        if (!$base_ts || $base_ts <= 0) {
            $base_ts = strtotime(date('Y-m-01'));
        }

        $forward_projections = [];
        $backlog_schedule = [1 => $backlog_m1, 2 => $backlog_m2, 3 => $backlog_m3];
        for ($h = 1; $h <= 3; $h++) {
            $f_ts    = strtotime("+{$h} month", $base_ts);
            $f_ord   = round($computeForecastStep($ord_series, $h), 2);
            $f_inv_w = $computeForecastStep($inv_series, $h);
            $f_inv   = round(($f_inv_w * 0.75) + (isset($backlog_schedule[$h]) ? $backlog_schedule[$h] : 0.0), 2);
            $f_out   = round($computeForecastStep($out_series, $h), 2);
            $f_net   = round($f_inv - $f_out, 2);

            $forward_projections[] = [
                'horizon'             => 'M+' . $h,
                'period_label'        => date('M Y', $f_ts),
                'classification'      => 'FORECAST • STATISTICAL MODEL',
                'model_method'        => '3M Weighted Moving Average + Damped Trend + Unbilled PO Realization',
                'projected_orders'    => $f_ord,
                'projected_invoices'  => $f_inv,
                'projected_cash_out'  => $f_out,
                'projected_net_contr' => $f_net,
                'bear_case_invoices'  => round($f_inv * 0.82, 2),
                'bull_case_invoices'  => round($f_inv * 1.18, 2)
            ];
        }

        $c_sum    = isset($biz_analytics['customer_intelligence']['summary']) ? $biz_analytics['customer_intelligence']['summary'] : [];
        $hhi      = isset($c_sum['hhi_index']) ? (int)$c_sum['hhi_index'] : 0;
        $top3_pct = isset($c_sum['top3_share_pct']) ? (float)$c_sum['top3_share_pct'] : 0.0;

        $r1_score = min(100, (int)round(($hhi / 3500) * 100));
        $r1_sev   = ($r1_score >= 75) ? 'CRITICAL' : (($r1_score >= 50) ? 'HIGH' : (($r1_score >= 30) ? 'MODERATE' : 'LOW'));

        $unbilled_pct = ($orders['total_nominal'] > 0)
            ? round(($unbilled_po_nominal / $orders['total_nominal']) * 100, 1)
            : 0.0;
        $r2_score = min(100, (int)round($unbilled_pct * 1.25));
        $r2_sev   = ($r2_score >= 70) ? 'HIGH' : (($r2_score >= 40) ? 'MODERATE' : 'LOW');

        $overdue_cnt = (int)$tagihan['overdue_count'];
        $unpaid_ap   = (float)$tagihan['unpaid_nominal'];
        $r3_score    = min(100, ($overdue_cnt * 12) + ($unpaid_ap > 0 ? 20 : 0));
        $r3_sev      = ($r3_score >= 70) ? 'CRITICAL' : (($r3_score >= 45) ? 'HIGH' : (($r3_score >= 25) ? 'MODERATE' : 'LOW'));

        $r4_score = $cogs_status['has_unit_cogs'] ? 15 : 68;
        $r4_sev   = $cogs_status['has_unit_cogs'] ? 'LOW' : 'HIGH';

        $tax_tot_cnt  = $taxes['approved_count'] + $taxes['pending_count'];
        $tax_pend_pct = ($tax_tot_cnt > 0) ? round(($taxes['pending_count'] / $tax_tot_cnt) * 100, 1) : 0.0;
        $r5_score     = min(100, (int)round($tax_pend_pct));
        $r5_sev       = ($r5_score >= 60) ? 'HIGH' : (($r5_score >= 30) ? 'MODERATE' : 'LOW');

        $risk_matrix = [
            [
                'code'           => 'RISK:CUST_CONC',
                'dimension'      => '1. Customer Concentration & Revenue Dependency',
                'classification' => 'ACTUAL • HHI EXPOSURE',
                'score'          => $r1_score,
                'severity'       => $r1_sev,
                'metric_value'   => "HHI {$hhi} • Top-3 Share: {$top3_pct}%",
                'formula'        => 'SUM((Customer_Billed_i / Total_Billed)^2) [Herfindahl-Hirschman Index]',
                'source_tables'  => 'po_masuk, invoice, customer',
                'mitigation'     => ($r1_score >= 50)
                    ? 'Diversifikasi akuisisi RS/Instansi baru untuk menurunkan ketergantungan pada 3 pelanggan utama.'
                    : 'Struktur portofolio pelanggan cukup terdiversifikasi; pertahankan retensi akun utama.'
            ],
            [
                'code'           => 'RISK:UNBILLED_LAG',
                'dimension'      => '2. Unbilled Order Backlog Stagnation',
                'classification' => 'ACTUAL • PIPELINE LAG',
                'score'          => $r2_score,
                'severity'       => $r2_sev,
                'metric_value'   => "{$unbilled_po_count} PO Belum Tagih ({$unbilled_pct}% Order Book)",
                'formula'        => 'Unbilled_PO_Nominal / Total_Contracted_Orders * 100',
                'source_tables'  => 'po_masuk LEFT JOIN invoice ON no_po',
                'mitigation'     => ($unbilled_po_count > 0)
                    ? 'Prioritaskan penerbitan Surat Jalan & Faktur untuk ' . $unbilled_po_count . ' PO yang belum terbit Invoice.'
                    : 'Tidak ada antrean PO yang tertahan; konversi PO ke Invoice berjalan optimal.'
            ],
            [
                'code'           => 'RISK:AP_OVERDUE',
                'dimension'      => '3. Vendor & Freight Payables Aging Exposure',
                'classification' => 'ACTUAL • AP LIQUIDITY',
                'score'          => $r3_score,
                'severity'       => $r3_sev,
                'metric_value'   => "{$overdue_cnt} Tagihan > 30 Hari • Total AP: Rp " . number_format($unpaid_ap, 0, ',', '.'),
                'formula'        => 'COUNT(tagihan_ekspedisi WHERE status_bayar != "sudah_dibayar" AND DATEDIFF(NOW(), tanggal_invoice) > 30)',
                'source_tables'  => 'tagihan_ekspedisi, ekspedisi',
                'mitigation'     => ($overdue_cnt > 0)
                    ? 'Jadwalkan penyelesaian bertahap atas ' . $overdue_cnt . ' tagihan ekspedisi > 30 hari agar SLA pengiriman tidak terhambat.'
                    : 'Seluruh kewajiban tagihan vendor/ekspedisi berada dalam batas umur normal (< 30 hari).'
            ],
            [
                'code'           => 'RISK:COGS_BLINDSPOT',
                'dimension'      => '4. Unit COGS & Gross Margin Traceability Gap',
                'classification' => 'GUARDRAIL • SCHEMA AUDIT',
                'score'          => $r4_score,
                'severity'       => $r4_sev,
                'metric_value'   => $cogs_status['status_label'] . ' (' . $cogs_status['supplier_price_rows'] . ' Dok Pemasok)',
                'formula'        => 'Schema Audit: detail_barang_invoice vs acc_pemasok_harga batch linkage',
                'source_tables'  => 'detail_barang_invoice, acc_pemasok_harga',
                'mitigation'     => 'Pertahankan COGS Guardrail (Prinsip #5) hingga harga beli batch terhubung langsung ke setiap baris item invoice.'
            ],
            [
                'code'           => 'RISK:TAX_VERIF',
                'dimension'      => '5. Tax Faktur Verification & Compliance Queue',
                'classification' => 'ACTUAL • TAX QUEUE',
                'score'          => $r5_score,
                'severity'       => $r5_sev,
                'metric_value'   => "{$taxes['pending_count']} Pending / {$taxes['approved_count']} Approved ({$tax_pend_pct}% Pending)",
                'formula'        => 'Pending_Faktur_Count / Total_Faktur_Submissions * 100',
                'source_tables'  => 'approval_faktur_pajak',
                'mitigation'     => ($taxes['pending_count'] > 0)
                    ? 'Selesaikan verifikasi ' . $taxes['pending_count'] . ' pengajuan Faktur Pajak yang masih berstatus proses.'
                    : 'Seluruh pengajuan Faktur Pajak pada periode ini telah disetujui.'
            ]
        ];

        $anomaly_alerts = [];
        $inv_vals = [];
        foreach ($invoices['rows'] as $r) {
            if ((float)$r['nominal_total'] > 0) {
                $inv_vals[] = (float)$r['nominal_total'];
            }
        }
        if (count($inv_vals) >= 3) {
            $mean = array_sum($inv_vals) / count($inv_vals);
            $var  = 0.0;
            foreach ($inv_vals as $v) {
                $var += ($v - $mean) * ($v - $mean);
            }
            $std = sqrt($var / count($inv_vals));
            if ($std > 0) {
                foreach ($invoices['rows'] as $r) {
                    $v = (float)$r['nominal_total'];
                    $z = ($v - $mean) / $std;
                    if ($z >= 1.85 && count($anomaly_alerts) < 6) {
                        $anomaly_alerts[] = [
                            'tgl'          => $r['tgl'],
                            'kode_dok'     => $r['no_invoice'],
                            'type'         => 'STATISTICAL OUTLIER (Z = +' . round($z, 2) . 'σ)',
                            'severity'     => 'HIGH VALUE',
                            'badge'        => 'emerald',
                            'entitas'      => $r['customer_nama'],
                            'source_table' => 'invoice',
                            'nominal'      => round($v, 2),
                            'explanation'  => 'Nilai faktur melampaui rata-rata historis (Rp ' . number_format($mean, 0, ',', '.') . ') sebesar +' . round($z, 2) . ' standar deviasi.'
                        ];
                    }
                }
            }
        }

        $unb_rows = isset($fin_intel['unbilled_orders_list']) ? $fin_intel['unbilled_orders_list'] : [];
        foreach ($unb_rows as $ub) {
            if ($ub['days_open'] >= 30 && count($anomaly_alerts) < 12) {
                $anomaly_alerts[] = [
                    'tgl'          => $ub['tanggal'],
                    'kode_dok'     => $ub['no_po'],
                    'type'         => 'UNBILLED PO STAGNATION (' . $ub['days_open'] . ' Hari)',
                    'severity'     => 'WARNING',
                    'badge'        => 'amber',
                    'entitas'      => $ub['customer_nama'],
                    'source_table' => 'po_masuk',
                    'nominal'      => $ub['nominal'],
                    'explanation'  => 'PO pelanggan berusia ' . $ub['days_open'] . ' hari belum memiliki pencatatan nomor PO yang sama pada tabel invoice.'
                ];
            }
        }

        $ap_rows = isset($fin_intel['payables_watchlist']) ? $fin_intel['payables_watchlist'] : [];
        foreach ($ap_rows as $ap) {
            if ($ap['days_aging'] >= 45 && count($anomaly_alerts) < 16) {
                $anomaly_alerts[] = [
                    'tgl'          => $ap['tanggal_invoice'],
                    'kode_dok'     => $ap['nomor_invoice'],
                    'type'         => 'OVERDUE PAYABLE (' . $ap['days_aging'] . ' Hari)',
                    'severity'     => 'CRITICAL AP',
                    'badge'        => 'rose',
                    'entitas'      => $ap['nama_ekspedisi'],
                    'source_table' => 'tagihan_ekspedisi',
                    'nominal'      => $ap['nominal'],
                    'explanation'  => 'Tagihan vendor ekspedisi berstatus belum_dibayar melewati 45 hari sejak tanggal invoice.'
                ];
            }
        }

        $ord_tot_fmt  = 'Rp ' . number_format($orders['total_nominal'], 0, ',', '.');
        $inv_tot_fmt  = 'Rp ' . number_format($invoices['total_nominal'], 0, ',', '.');
        $dpp_tot_fmt  = 'Rp ' . number_format($invoices['total_dpp'], 0, ',', '.');
        $unb_tot_fmt  = 'Rp ' . number_format($unbilled_po_nominal, 0, ',', '.');
        $dp_tot_fmt   = 'Rp ' . number_format($orders['verified_dp_nominal'], 0, ',', '.');
        $out_tot_fmt  = 'Rp ' . number_format($tagihan['paid_nominal'] + $payroll['total_nominal'], 0, ',', '.');
        $ap_tot_fmt   = 'Rp ' . number_format($tagihan['unpaid_nominal'], 0, ',', '.');
        $oc_val       = $invoices['total_dpp'] - ($tagihan['paid_nominal'] + $payroll['total_nominal']);
        $oc_tot_fmt   = 'Rp ' . number_format($oc_val, 0, ',', '.');
        $m1_proj_fmt  = isset($forward_projections[0]) ? ('Rp ' . number_format($forward_projections[0]['projected_invoices'], 0, ',', '.')) : 'Rp 0';
        $top_cust_str = (isset($c_sum['top1_name']) ? $c_sum['top1_name'] : '-') . ' (' . (isset($c_sum['top1_share_pct']) ? $c_sum['top1_share_pct'] : 0) . '%)';
        $conv_pct_val = isset($fin_intel['revenue_funnel'][2]['pct_of_top']) ? $fin_intel['revenue_funnel'][2]['pct_of_top'] : 0;

        $executive_briefing = [
            [
                'code'           => 'AI:THESIS_REV',
                'title'          => '1. Order Book & Billing Realization Diagnostics',
                'classification' => 'ACTUAL • VERIFIED TELEMETRY',
                'headline'       => "Konversi PO ke Faktur mencapai {$conv_pct_val}% dengan Backlog Belum Tertagih {$unb_tot_fmt}.",
                'body'           => "Pada periode {$start_date} s/d {$end_date}, perusahaan membukukan komitmen pesanan pelanggan (Contracted Order Book dari tabel po_masuk) sebesar {$ord_tot_fmt} ({$orders['count']} PO). Dari nilai tersebut, tagihan resmi yang telah diterbitkan pada tabel invoice mencapai {$inv_tot_fmt} ({$invoices['count']} faktur, Net DPP {$dpp_tot_fmt}). Terdapat {$unbilled_po_count} dokumen PO senilai {$unb_tot_fmt} yang belum terbit faktur dan menjadi katalis penagihan jangka pendek."
            ],
            [
                'code'           => 'AI:THESIS_CASH',
                'title'          => '2. Cashflow Discipline, Verified DP & Payables Liquidity',
                'classification' => 'ACTUAL • STRICT SEPARATION',
                'headline'       => "Kas Keluar Aktual tercatat {$out_tot_fmt}; Kewajiban Ekspedisi Belum Bayar {$ap_tot_fmt}.",
                'body'           => "Sesuai Prinsip #5 VYM BI, komitmen PO dan faktur terbit tidak diklaim sebagai kas masuk lunas. Penerimaan uang muka (DP cicilan) & PO Cash/COD terverifikasi tercatat sebesar {$dp_tot_fmt}. Di sisi pengeluaran aktual, beban ekspedisi lunas (tagihan_ekspedisi.status_bayar = sudah_dibayar) ditambah tutup buku gaji SDM (salary_history) mencapai {$out_tot_fmt}, sementara hutang ekspedisi belum dibayar tercatat {$ap_tot_fmt} ({$tagihan['unpaid_count']} tagihan, {$overdue_cnt} di antaranya berusia > 30 hari)."
            ],
            [
                'code'           => 'AI:THESIS_MARGIN',
                'title'          => '3. Operating Contribution, COGS Guardrail & Concentration Risk',
                'classification' => 'GUARDRAIL • HHI & COGS',
                'headline'       => "Kontribusi Operasional (Excl. COGS) tercatat {$oc_tot_fmt} • Indeks HHI Pelanggan {$hhi}.",
                'body'           => "Selisih antara Net Billed DPP ({$dpp_tot_fmt}) dan Kas Keluar Operasional Terverifikasi ({$out_tot_fmt}) menghasilkan Kontribusi Operasional Pra-HPP sebesar {$oc_tot_fmt}. Laba Bersih tidak dipublikasikan secara semu karena HPP per batch keluar belum terhubung langsung ke detail_barang_invoice (tersedia {$cogs_status['supplier_price_rows']} dokumen katalog harga pada acc_pemasok_harga). Dari sisi komersial, pelanggan terbesar adalah {$top_cust_str} dengan porsi Top-3 mencapai {$top3_pct}%."
            ],
            [
                'code'           => 'AI:THESIS_OUTLOOK',
                'title'          => '4. Quantitative Outlook & Prioritized Management Actions',
                'classification' => 'FORECAST • STRATEGIC PLAN',
                'headline'       => "Proyeksi Penagihan Bulan Depan (M+1) diestimasikan {$m1_proj_fmt} (Model WMA + Realisasi Backlog).",
                'body'           => "Prioritas eksekusi manajemen: (1) Percepat pengiriman & penerbitan faktur atas {$unbilled_po_count} PO unbilled senilai {$unb_tot_fmt}; (2) Selesaikan {$overdue_cnt} tagihan ekspedisi yang telah melewati 30 hari; (3) Tuntaskan verifikasi {$taxes['pending_count']} antrean Faktur Pajak pada approval_faktur_pajak."
            ]
        ];

        $preset_qa = [
            [
                'q_id'     => 'QA_REVENUE',
                'question' => 'Bagaimana perbandingan PO Masuk vs Invoice Terbit dan berapa PO yang belum ditagih?',
                'answer'   => "Berdasarkan tabel po_masuk dan invoice pada periode ini:\n• Contracted Customer Orders (PO Masuk): {$ord_tot_fmt} ({$orders['count']} dokumen PO; {$orders['approved_count']} disetujui, {$orders['pending_count']} proses kroscek).\n• Issued Invoices (Billed AR): {$inv_tot_fmt} ({$invoices['count']} faktur; Net DPP excl. ongkir sebesar {$dpp_tot_fmt}).\n• Unbilled Order Backlog: {$unb_tot_fmt} ({$unbilled_po_count} PO belum memiliki faktur di tabel invoice).\n• Prinsip Akuntansi: Nilai PO tidak diakui sebagai kas masuk, dan faktur terbit diklasifikasikan sebagai piutang tertagih (Billed Receivable)."
            ],
            [
                'q_id'     => 'QA_CASHFLOW',
                'question' => 'Bagaimana posisi arus kas aktual, DP pelanggan, dan hutang vendor ekspedisi yang jatuh tempo?',
                'answer'   => "Rekonsiliasi kas & kewajiban (po_masuk, tagihan_ekspedisi, salary_history):\n• Verified Customer DP & Cash/COD PO: {$dp_tot_fmt} ({$orders['cash_or_dp_count']} transaksi terverifikasi).\n• Settled Cash Expenditures (Lunas): {$out_tot_fmt} (terdiri dari ekspedisi sudah_dibayar Rp " . number_format($tagihan['paid_nominal'], 0, ',', '.') . " dan payroll SDM Rp " . number_format($payroll['total_nominal'], 0, ',', '.') . ").\n• Unpaid Vendor/Freight Obligations (AP): {$ap_tot_fmt} ({$tagihan['unpaid_count']} dokumen belum_dibayar, dengan {$overdue_cnt} dokumen berusia > 30 hari)."
            ],
            [
                'q_id'     => 'QA_PROFIT_COGS',
                'question' => 'Berapa kontribusi operasional perusahaan dan mengapa Net Profit menggunakan COGS Guardrail?',
                'answer'   => "Audit Profitabilitas & HPP (invoice, tagihan_ekspedisi, salary_history, acc_pemasok_harga):\n• Net Billed DPP: {$dpp_tot_fmt}\n• Verified Settled OpEx (Freight + Payroll): {$out_tot_fmt}\n• Pre-COGS Operating Contribution: {$oc_tot_fmt}\n• Alasan COGS Guardrail: Tabel detail_barang_invoice belum menyimpan kolom harga_beli / HPP per batch keluar (meskipun terdapat {$cogs_status['supplier_price_rows']} dokumen katalog harga di acc_pemasok_harga). Sesuai Prinsip #5 VYM BI, sistem menolak mengarang persentase margin kotor fiktif."
            ],
            [
                'q_id'     => 'QA_CUSTOMER_HHI',
                'question' => 'Siapa pelanggan dengan kontribusi terbesar dan bagaimana tingkat risiko konsentrasi pelanggan (HHI)?',
                'answer'   => "Analisis Konsentrasi Pelanggan (po_masuk & invoice):\n• Total Entitas Pelanggan Aktif: " . (isset($c_sum['active_customers']) ? $c_sum['active_customers'] : 0) . " institusi/pelanggan.\n• Pelanggan Terbesar (Top-1): {$top_cust_str}.\n• Porsi Top-3 Pelanggan: {$top3_pct}% dari total nilai transaksi.\n• Indeks HHI: {$hhi} (" . (isset($c_sum['concentration_risk']) ? $c_sum['concentration_risk'] : '-') . ").\n• Rata-rata Nilai Pesanan (AOV): Rp " . number_format(isset($c_sum['avg_order_value']) ? $c_sum['avg_order_value'] : 0, 0, ',', '.') . " per PO."
            ],
            [
                'q_id'     => 'QA_FORECAST',
                'question' => 'Bagaimana proyeksi (Forecast) penagihan dan arus kas 3 bulan ke depan?',
                'answer'   => "Proyeksi Kuantitatif 3 Bulan (FORECAST • STATISTICAL MODEL menggunakan 3M Weighted Moving Average + Damped Trend + Konversi Unbilled Backlog):\n" .
                    (isset($forward_projections[0]) ? "• {$forward_projections[0]['period_label']} (M+1): Proyeksi Invoice Rp " . number_format($forward_projections[0]['projected_invoices'], 0, ',', '.') . " | Proyeksi Kas Keluar Rp " . number_format($forward_projections[0]['projected_cash_out'], 0, ',', '.') . "\n" : "") .
                    (isset($forward_projections[1]) ? "• {$forward_projections[1]['period_label']} (M+2): Proyeksi Invoice Rp " . number_format($forward_projections[1]['projected_invoices'], 0, ',', '.') . " | Proyeksi Kas Keluar Rp " . number_format($forward_projections[1]['projected_cash_out'], 0, ',', '.') . "\n" : "") .
                    (isset($forward_projections[2]) ? "• {$forward_projections[2]['period_label']} (M+3): Proyeksi Invoice Rp " . number_format($forward_projections[2]['projected_invoices'], 0, ',', '.') . " | Proyeksi Kas Keluar Rp " . number_format($forward_projections[2]['projected_cash_out'], 0, ',', '.') : "")
            ]
        ];

        return [
            'forward_projections' => $forward_projections,
            'risk_matrix'         => $risk_matrix,
            'anomaly_alerts'      => $anomaly_alerts,
            'ai_analyst'          => [
                'executive_briefing' => $executive_briefing,
                'preset_qa'          => $preset_qa
            ]
        ];
    }
}
