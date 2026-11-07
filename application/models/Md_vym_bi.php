<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Md_vym_bi — Dedicated Read-Only Financial & Business Intelligence Model
 * Product: VYM Business Intelligence (VYM BI)
 *
 * Strictly enforces Section 5 Financial Data Principles:
 * 1. Customer Orders (PO Masuk)       != Cash Inflow / Recognized Revenue
 * 2. Issued Invoices (Penagihan)      != Completed Cash Payment (Treated as Billed Receivable)
 * 3. Recognized Tax Basis (DPP/PPN)   = Verified from approval_faktur_pajak & invoice
 * 4. Supplier/Freight Obligations     = Unpaid tagihan_ekspedisi (Accounts Payable)
 * 5. Actual Cash Expenditures         = Paid tagihan_ekspedisi (sudah_dibayar) + Closed Payroll (salary_history)
 * 6. Profitability Guardrail          = Never fabricates net profit without verified COGS
 */
class Md_vym_bi extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Resolve date range filter into [start_date, end_date, label, preset]
     */
    public function resolveDateFilter($preset = 'YTD', $start_custom = null, $end_custom = null)
    {
        $preset = strtoupper(trim((string)$preset ?: 'YTD'));
        $today  = date('Y-m-d');
        $year   = (int)date('Y');
        $month  = (int)date('n');

        if ($preset === 'CUSTOM' && !empty($start_custom) && !empty($end_custom)) {
            $s = date('Y-m-d', strtotime($start_custom));
            $e = date('Y-m-d', strtotime($end_custom));
            if ($s > $e) {
                $tmp = $s;
                $s   = $e;
                $e   = $tmp;
            }
            return [
                'preset'     => 'CUSTOM',
                'start_date' => $s,
                'end_date'   => $e,
                'label'      => date('d M Y', strtotime($s)) . ' – ' . date('d M Y', strtotime($e))
            ];
        }

        if ($preset === 'MTD') {
            $s = sprintf('%04d-%02d-01', $year, $month);
            return [
                'preset'     => 'MTD',
                'start_date' => $s,
                'end_date'   => $today,
                'label'      => 'Month-to-Date (' . date('M Y') . ')'
            ];
        }

        if ($preset === 'QTD') {
            $q_start_month = (floor(($month - 1) / 3) * 3) + 1;
            $s = sprintf('%04d-%02d-01', $year, $q_start_month);
            $q_num = ceil($month / 3);
            return [
                'preset'     => 'QTD',
                'start_date' => $s,
                'end_date'   => $today,
                'label'      => "Quarter-to-Date (Q{$q_num} {$year})"
            ];
        }

        if ($preset === '1Y' || $preset === 'L12M') {
            $s = date('Y-m-d', strtotime('-365 days'));
            return [
                'preset'     => '1Y',
                'start_date' => $s,
                'end_date'   => $today,
                'label'      => 'Trailing 12 Months (' . date('d M Y', strtotime($s)) . ' – ' . date('d M Y') . ')'
            ];
        }

        if ($preset === 'PREV_YEAR') {
            $prev_y = $year - 1;
            return [
                'preset'     => 'PREV_YEAR',
                'start_date' => "{$prev_y}-01-01",
                'end_date'   => "{$prev_y}-12-31",
                'label'      => "Full Year {$prev_y}"
            ];
        }

        if ($preset === 'ALL') {
            return [
                'preset'     => 'ALL',
                'start_date' => '2018-01-01',
                'end_date'   => $today,
                'label'      => 'All-Time Historical Records'
            ];
        }

        // Default: YTD
        return [
            'preset'     => 'YTD',
            'start_date' => "{$year}-01-01",
            'end_date'   => $today,
            'label'      => "Year-to-Date (FY {$year})"
        ];
    }

    /**
     * Master Executive Overview Dataset (Phase 2 Foundation + Strict Accounting Separation)
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

        // Fallback otomatis ke All-Time jika periode terpilih (misal awal tahun) belum memiliki catatan transaksi
        $used_fallback_all = false;
        if (
            empty($orders['rows']) &&
            empty($invoices['rows']) &&
            empty($tagihan['rows']) &&
            empty($payroll['rows']) &&
            $start_date > '2020-01-01'
        ) {
            $used_fallback_all = true;
            $start_date  = '2018-01-01';
            $end_date    = date('Y-m-d');
            $orders      = $this->fetchCustomerOrders($start_date, $end_date);
            $invoices    = $this->fetchIssuedInvoices($start_date, $end_date);
            $taxes       = $this->fetchTaxRecognitions($start_date, $end_date);
            $tagihan     = $this->fetchFreightObligationsAndPayments($start_date, $end_date);
            $payroll     = $this->fetchPayrollExpenditures($start_date, $end_date);
        }

        // Cross-reference PO Masuk vs Issued Invoices via normalized no_po
        $invoiced_po_map = [];
        foreach ($invoices['rows'] as $inv) {
            $norm_po = strtolower(trim((string)($inv['no_po'] ?? '')));
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
            $norm_po = strtolower(trim((string)($po['no_po'] ?? '')));
            $po_val  = (float)$po['nominal_total'];
            if ($norm_po !== '' && isset($invoiced_po_map[$norm_po])) {
                $billed_po_count++;
            } else {
                $unbilled_po_nominal += $po_val;
                $unbilled_po_count++;
            }
        }

        // Build Time-Series Buckets for Bloomberg Multi-Layer Financial Chart
        $buckets = $this->buildTimeSeriesBuckets(
            $start_date,
            $end_date,
            $interval,
            $orders['rows'],
            $invoices['rows'],
            $tagihan['rows'],
            $payroll['rows']
        );

        // Build Top Customers & Recent Institutional Ledger
        $top_customers = $this->buildCustomerBreakdown($orders['rows'], $invoices['rows']);
        $recent_ledger = $this->buildUnifiedAuditLedger($orders['rows'], $invoices['rows'], $tagihan['rows'], $payroll['rows']);

        // Build Phase 3 Deep-Dive Financial Intelligence Payload (Submodules 2, 3, 4, 5)
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

        // Build Phase 4 Business Analytics Payload (Submodule 6: Customer, Product, Supplier, Operational)
        $biz_analytics = $this->buildBusinessAnalyticsPayload(
            $start_date,
            $end_date,
            $orders,
            $invoices,
            $tagihan
        );

        // Compute Validated Ratios
        $order_total       = $orders['total_nominal'];
        $invoice_total     = $invoices['total_nominal'];
        $invoice_dpp       = $invoices['total_dpp'];
        $verified_dp_cash  = $orders['verified_dp_nominal'];
        $obligations_unpaid= $tagihan['unpaid_nominal'];
        $cash_out_paid     = $tagihan['paid_nominal'] + $payroll['total_nominal'];

        $order_to_billing_pct = ($order_total > 0)
            ? round(($invoice_total / $order_total) * 100, 1)
            : 0.0;

        // Operating Cash Coverage: Billed Receivable vs Actual Cash Outflow
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
            'time_series'            => $buckets,
            'top_customers'          => $top_customers,
            'recent_ledger'          => $recent_ledger,
            'financial_intelligence' => $fin_intel,
            'business_analytics'     => $biz_analytics
        ];
    }

    /**
     * 1. Fetch Customer Orders (po_masuk) — Contracted Order Book
     */
    public function fetchCustomerOrders($start_date, $end_date)
    {
        $res = [
            'total_nominal'     => 0.0,
            'total_subtotal'    => 0.0,
            'total_ppn'         => 0.0,
            'total_ongkir'      => 0.0,
            'approved_nominal'  => 0.0,
            'pending_nominal'   => 0.0,
            'verified_dp_nominal'=> 0.0,
            'cicilan_dp_only'   => 0.0,
            'cash_approved_only'=> 0.0,
            'count'             => 0,
            'approved_count'    => 0,
            'pending_count'     => 0,
            'cash_or_dp_count'  => 0,
            'rows'              => []
        ];

        if (!$this->db->table_exists('po_masuk')) {
            return $res;
        }

        $fields   = array_flip($this->db->list_fields('po_masuk'));
        $has_item = $this->db->table_exists('po_masuk_item');

        $nom_parts = [];
        if (isset($fields['nominal_total_akhir'])) $nom_parts[] = "NULLIF(p.nominal_total_akhir, 0)";
        if (isset($fields['nominal_po']))          $nom_parts[] = "NULLIF(p.nominal_po, 0)";
        if (isset($fields['nominal_subtotal']))    $nom_parts[] = "NULLIF(p.nominal_subtotal, 0)";
        if ($has_item) {
            $nom_parts[] = "(SELECT COALESCE(SUM(i.total_harga), 0) FROM po_masuk_item i WHERE i.id_po = p.id_po)";
        }
        $nom_parts[] = "0";
        $nom_expr = "COALESCE(" . implode(", ", $nom_parts) . ")";

        $sub_expr    = isset($fields['nominal_subtotal']) ? "COALESCE(NULLIF(p.nominal_subtotal, 0), {$nom_expr})" : $nom_expr;
        $ppn_expr    = isset($fields['nominal_ppn']) ? "COALESCE(p.nominal_ppn, 0)" : "0";
        $ongkir_expr = isset($fields['nominal_ongkir']) ? "COALESCE(p.nominal_ongkir, 0)" : "0";
        $dp_expr     = isset($fields['cicilan_dp_nominal']) ? "COALESCE(p.cicilan_dp_nominal, 0)" : "0";
        $pay_expr    = isset($fields['sistem_pembayaran']) ? "COALESCE(p.sistem_pembayaran, 'Cash')" : "'Cash'";
        $sts_expr    = isset($fields['status']) ? "COALESCE(p.status, 'menunggu_kroscek')" : "'disetujui'";
        $prs_expr    = isset($fields['status_proses']) ? "COALESCE(p.status_proses, '-')" : "'-'";
        $sls_expr    = isset($fields['nama_sales']) ? "COALESCE(NULLIF(p.nama_sales, ''), u.nama, '-')" : "COALESCE(u.nama, '-')";

        $where_del = isset($fields['deleted']) ? "AND p.deleted = 0" : "";
        $where_sts = isset($fields['status']) ? "AND p.status != 'ditolak'" : "";

        $sql = "
            SELECT
                p.id_po,
                p.no_po,
                p.tanggal_po AS tgl,
                {$nom_expr} AS nominal_total,
                {$sub_expr} AS nominal_subtotal,
                {$ppn_expr} AS nominal_ppn,
                {$ongkir_expr} AS nominal_ongkir,
                {$dp_expr} AS dp_nominal,
                {$pay_expr} AS sistem_pembayaran,
                {$sts_expr} AS status_po,
                {$prs_expr} AS status_proses,
                {$sls_expr} AS nama_sales,
                COALESCE(NULLIF(p.nama_customer, ''), c.nama_customer, pl.identitas_pelanggan, '-') AS customer_nama
            FROM po_masuk p
            LEFT JOIN customer c ON p.id_customer = c.id_customer
            LEFT JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
            LEFT JOIN pengguna u ON p.created_by = u.pengguna_id
            WHERE p.tanggal_po IS NOT NULL
              AND p.tanggal_po != '0000-00-00'
              AND p.tanggal_po >= " . $this->db->escape($start_date) . "
              AND p.tanggal_po <= " . $this->db->escape($end_date) . "
              {$where_del}
              {$where_sts}
            ORDER BY p.tanggal_po DESC, p.id_po DESC
        ";

        $q = $this->db->query($sql);
        if (!$q) {
            return $res;
        }

        foreach ($q->result_array() as $r) {
            $tot = max(0.0, (float)$r['nominal_total']);
            $sub = max(0.0, (float)$r['nominal_subtotal']);
            $ppn = max(0.0, (float)$r['nominal_ppn']);
            $ong = max(0.0, (float)$r['nominal_ongkir']);
            $dp  = max(0.0, (float)$r['dp_nominal']);
            $sts = strtolower(trim((string)$r['status_po']));
            $pay = strtoupper(trim((string)$r['sistem_pembayaran']));

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

            if ($dp > 0) {
                $res['verified_dp_nominal'] += $dp;
                $res['cicilan_dp_only']     += $dp;
                $res['cash_or_dp_count']++;
            } elseif (in_array($pay, ['CASH', 'COD'], true) && $sts === 'disetujui') {
                $res['verified_dp_nominal'] += $tot;
                $res['cash_approved_only']  += $tot;
                $res['cash_or_dp_count']++;
            }

            $res['rows'][] = $r;
        }

        return $res;
    }

    /**
     * 2. Fetch Issued Customer Invoices (invoice) — Billed Receivables
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

        if (!$this->db->table_exists('invoice')) {
            return $res;
        }

        $fields   = array_flip($this->db->list_fields('invoice'));
        $has_item = $this->db->table_exists('detail_barang_invoice');

        $tot_parts = [];
        if (isset($fields['total_keseluruhan'])) {
            $tot_parts[] = "NULLIF(CAST(REPLACE(REPLACE(inv.total_keseluruhan, '.', ''), ',', '') AS DECIMAL(18,2)), 0)";
        }
        if (isset($fields['total'])) {
            $tot_parts[] = "NULLIF(CAST(REPLACE(REPLACE(inv.total, '.', ''), ',', '') AS DECIMAL(18,2)), 0)";
        }
        if (isset($fields['sub_total'])) {
            $tot_parts[] = "NULLIF(CAST(REPLACE(REPLACE(inv.sub_total, '.', ''), ',', '') AS DECIMAL(18,2)), 0)";
        }
        if ($has_item) {
            $tot_parts[] = "(SELECT COALESCE(SUM(CAST(REPLACE(REPLACE(dbi.harga_total, '.', ''), ',', '') AS DECIMAL(18,2))), 0) FROM detail_barang_invoice dbi WHERE dbi.id_invoice = inv.id_invoice AND dbi.status = 1)";
        }
        $tot_parts[] = "0";
        $tot_expr = "COALESCE(" . implode(", ", $tot_parts) . ")";

        $dpp_expr = isset($fields['total'])
            ? "COALESCE(NULLIF(CAST(REPLACE(REPLACE(inv.total, '.', ''), ',', '') AS DECIMAL(18,2)), 0), {$tot_expr})"
            : $tot_expr;

        $ong_expr = isset($fields['ongkir'])
            ? "COALESCE(CAST(REPLACE(REPLACE(inv.ongkir, '.', ''), ',', '') AS DECIMAL(18,2)), 0)"
            : "0";

        $nopo_expr = isset($fields['no_po']) ? "COALESCE(inv.no_po, '')" : "''";
        $brg_expr  = isset($fields['status_barang_keluar']) ? "COALESCE(inv.status_barang_keluar, 'belum_keluar')" : "'belum_keluar'";

        $sql = "
            SELECT
                inv.id_invoice,
                inv.no_invoice,
                inv.tgl_invoice AS tgl,
                {$nopo_expr} AS no_po,
                {$tot_expr} AS nominal_total,
                {$dpp_expr} AS nominal_dpp,
                {$ong_expr} AS nominal_ongkir,
                {$brg_expr} AS status_barang_keluar,
                COALESCE(c.nama_customer, '-') AS customer_nama,
                COALESCE(p.nama, '-') AS marketing_nama
            FROM invoice inv
            LEFT JOIN customer c ON inv.id_customer = c.id_customer
            LEFT JOIN pengguna p ON inv.id_marketing = p.pengguna_id
            WHERE inv.status = 1
              AND inv.tgl_invoice IS NOT NULL
              AND inv.tgl_invoice != '0000-00-00'
              AND inv.tgl_invoice >= " . $this->db->escape($start_date) . "
              AND inv.tgl_invoice <= " . $this->db->escape($end_date) . "
            ORDER BY inv.tgl_invoice DESC, inv.id_invoice DESC
        ";

        $q = $this->db->query($sql);
        if (!$q) {
            return $res;
        }

        foreach ($q->result_array() as $r) {
            $tot = max(0.0, (float)$r['nominal_total']);
            $dpp = max(0.0, (float)$r['nominal_dpp']);
            $ong = max(0.0, (float)$r['nominal_ongkir']);

            $res['total_nominal'] += $tot;
            $res['total_dpp']     += $dpp;
            $res['total_ongkir']  += $ong;
            $res['count']++;

            if ($r['status_barang_keluar'] === 'sudah_keluar') {
                $res['shipped_count']++;
            }

            $res['rows'][] = $r;
        }

        return $res;
    }

    /**
     * 3. Fetch Tax Recognition (approval_faktur_pajak)
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

        if (!$this->db->table_exists('approval_faktur_pajak')) {
            return $res;
        }

        $fields = array_flip($this->db->list_fields('approval_faktur_pajak'));
        $dpp_expr = isset($fields['dpp'])
            ? "COALESCE(CAST(REPLACE(REPLACE(dpp, '.', ''), ',', '') AS DECIMAL(18,2)), 0)"
            : "0";
        $ppn_expr = isset($fields['ppn'])
            ? "COALESCE(CAST(REPLACE(REPLACE(ppn, '.', ''), ',', '') AS DECIMAL(18,2)), 0)"
            : "0";

        $sql = "
            SELECT
                status,
                {$dpp_expr} AS dpp_val,
                {$ppn_expr} AS ppn_val
            FROM approval_faktur_pajak
            WHERE tanggal IS NOT NULL
              AND tanggal != '0000-00-00'
              AND tanggal >= " . $this->db->escape($start_date) . "
              AND tanggal <= " . $this->db->escape($end_date) . "
        ";

        $q = $this->db->query($sql);
        if (!$q) {
            return $res;
        }

        foreach ($q->result_array() as $r) {
            $sts = (int)$r['status'];
            $dpp = max(0.0, (float)$r['dpp_val']);
            $ppn = max(0.0, (float)$r['ppn_val']);

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

        if (!$this->db->table_exists('tagihan_ekspedisi')) {
            return $res;
        }

        $fields = array_flip($this->db->list_fields('tagihan_ekspedisi'));

        $nom_parts = [];
        if (isset($fields['total_tagihan'])) {
            $nom_parts[] = "NULLIF(tg.total_tagihan, 0)";
        }
        if (isset($fields['nilai_tagihan'])) {
            $asu = isset($fields['biaya_asuransi']) ? "COALESCE(tg.biaya_asuransi, 0)" : "0";
            $nom_parts[] = "NULLIF(COALESCE(tg.nilai_tagihan, 0) + {$asu}, 0)";
        }
        $nom_parts[] = "0";
        $nom_expr = "COALESCE(" . implode(", ", $nom_parts) . ")";

        $date_expr = (isset($fields['tanggal_invoice']) && isset($fields['created_at']))
            ? "COALESCE(NULLIF(tg.tanggal_invoice, '0000-00-00'), DATE(tg.created_at))"
            : (isset($fields['tanggal_invoice']) ? "tg.tanggal_invoice" : "DATE(tg.created_at)");

        $where_app = isset($fields['status_approval']) ? "AND tg.status_approval != 99" : "";
        $byr_expr  = isset($fields['status_bayar']) ? "COALESCE(tg.status_bayar, 'belum_dibayar')" : "'belum_dibayar'";
        $app_expr  = isset($fields['status_approval']) ? "COALESCE(tg.status_approval, 1)" : "5";

        $sql = "
            SELECT
                tg.id_tagihan,
                COALESCE(tg.no_invoice, CONCAT('TAG-', tg.id_tagihan)) AS no_dokumen,
                {$date_expr} AS tgl,
                {$nom_expr} AS nominal_total,
                {$byr_expr} AS status_bayar,
                {$app_expr} AS status_approval,
                COALESCE(e.nama_ekspedisi, 'Vendor Ekspedisi') AS vendor_nama
            FROM tagihan_ekspedisi tg
            LEFT JOIN tracking_barang t ON tg.id_tracking = t.id_tracking
            LEFT JOIN ekspedisi e ON t.id_ekspedisi = e.id_ekspedisi
            WHERE {$date_expr} IS NOT NULL
              AND {$date_expr} != '0000-00-00'
              AND {$date_expr} >= " . $this->db->escape($start_date) . "
              AND {$date_expr} <= " . $this->db->escape($end_date) . "
              {$where_app}
            ORDER BY {$date_expr} DESC, tg.id_tagihan DESC
        ";

        $q = $this->db->query($sql);
        if (!$q) {
            return $res;
        }

        $today_ts = time();
        foreach ($q->result_array() as $r) {
            $nom = max(0.0, (float)$r['nominal_total']);
            if ($nom <= 0) continue;

            $is_paid = (strtolower(trim((string)$r['status_bayar'])) === 'sudah_dibayar');
            $app_sts = (int)$r['status_approval'];

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
                $inv_ts = strtotime($r['tgl']);
                if ($inv_ts && (($today_ts - $inv_ts) / 86400) > 30) {
                    $res['overdue_count']++;
                }
            }

            $res['rows'][] = $r;
        }

        return $res;
    }

    /**
     * 5. Fetch Actual Payroll Expenditures (salary_history)
     */
    public function fetchPayrollExpenditures($start_date, $end_date)
    {
        $res = [
            'total_nominal' => 0.0,
            'month_count'   => 0,
            'slip_count'    => 0,
            'rows'          => []
        ];

        if (!$this->db->table_exists('salary_history')) {
            return $res;
        }

        $fields = array_flip($this->db->list_fields('salary_history'));
        $add_cols = [];
        foreach (['gajipokok', 'tunjangankonsumsi', 'tunjangankinerja', 'tunjangankomunikasi', 'tunjangantransport', 'tunjanganjabatan', 'bonus'] as $col) {
            if (isset($fields[$col])) {
                $add_cols[] = "COALESCE(CAST(sh.{$col} AS DECIMAL(18,2)), 0)";
            }
        }
        if (empty($add_cols)) {
            return $res;
        }

        $sub_col  = isset($fields['potonganlainnya']) ? "COALESCE(CAST(sh.potonganlainnya AS DECIMAL(18,2)), 0)" : "0";
        $nom_expr = "(" . implode(" + ", $add_cols) . " - {$sub_col})";

        $date_expr = (isset($fields['tahun']) && isset($fields['bulan']))
            ? "STR_TO_DATE(CONCAT(sh.tahun, '-', LPAD(sh.bulan, 2, '0'), '-25'), '%Y-%m-%d')"
            : "DATE(sh.created_at)";

        $sql = "
            SELECT
                {$date_expr} AS tgl,
                SUM({$nom_expr}) AS nominal_total,
                COUNT(*) AS slip_cnt
            FROM salary_history sh
            WHERE {$date_expr} IS NOT NULL
              AND {$date_expr} >= " . $this->db->escape($start_date) . "
              AND {$date_expr} <= " . $this->db->escape($end_date) . "
            GROUP BY {$date_expr}
            HAVING nominal_total > 0
            ORDER BY tgl DESC
        ";

        $q = $this->db->query($sql);
        if (!$q) {
            return $res;
        }

        foreach ($q->result_array() as $r) {
            $nom = max(0.0, (float)$r['nominal_total']);
            $cnt = (int)$r['slip_cnt'];
            $res['total_nominal'] += $nom;
            $res['month_count']++;
            $res['slip_count'] += $cnt;
            $res['rows'][] = [
                'tgl'           => $r['tgl'],
                'nominal_total' => $nom,
                'slip_cnt'      => $cnt,
                'no_dokumen'    => 'PAYROLL-' . date('Ym', strtotime($r['tgl'])),
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
        if ($this->db->table_exists('acc_pemasok_harga')) {
            $supplier_price_cnt = (int)$this->db->count_all_results('acc_pemasok_harga');
        }

        $has_detail_barang_hpp = false;
        if ($this->db->table_exists('detail_barang')) {
            $db_fields = array_flip($this->db->list_fields('detail_barang'));
            $has_detail_barang_hpp = (isset($db_fields['harga_beli']) || isset($db_fields['hpp']));
        }

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
            $ts = strtotime($dateStr);
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

        // Pre-seed monthly buckets if range is within 36 months for continuous chart rendering
        if ($interval === '1M') {
            $s_ts = strtotime(date('Y-m-01', strtotime($start_date)));
            $e_ts = strtotime(date('Y-m-01', strtotime($end_date)));
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
     * Build Top Customers Breakdown (Contracted Orders vs Billed Invoices)
     */
    private function buildCustomerBreakdown($po_rows, $inv_rows)
    {
        $map = [];

        foreach ($po_rows as $r) {
            $name = trim((string)($r['customer_nama'] ?? '-'));
            if ($name === '' || $name === '-') $name = 'Pelanggan Umum / Lainnya';
            $key = strtolower($name);
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
            $name = trim((string)($r['customer_nama'] ?? '-'));
            if ($name === '' || $name === '-') $name = 'Pelanggan Umum / Lainnya';
            $key = strtolower($name);
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
            $maxA = max($a['order_nominal'], $a['inv_nominal']);
            $maxB = max($b['order_nominal'], $b['inv_nominal']);
            if ($maxA == $maxB) return 0;
            return ($maxA > $maxB) ? -1 : 1;
        });

        return array_slice($list, 0, 8);
    }

    /**
     * Build Unified Institutional Audit Ledger (Latest 25 Cross-Table Records with Strict Classification)
     */
    private function buildUnifiedAuditLedger($po_rows, $inv_rows, $tagihan_rows, $payroll_rows)
    {
        $ledger = [];

        foreach (array_slice($po_rows, 0, 12) as $r) {
            $ledger[] = [
                'tgl'            => $r['tgl'],
                'kode_dok'       => $r['no_po'] ?: ('PO-' . $r['id_po']),
                'kategori'       => 'CUSTOMER ORDER (PO)',
                'klasifikasi'    => 'ACTUAL • ORDER BOOK',
                'badge_color'    => 'cyan',
                'entitas'        => $r['customer_nama'] ?: '-',
                'status_dok'     => strtoupper($r['status_po'] ?: '-'),
                'nominal'        => round((float)$r['nominal_total'], 2),
                'sumber_tabel'   => 'po_masuk',
                'arah_kas'       => 'NON-CASH (CONTRACT)'
            ];
        }

        foreach (array_slice($inv_rows, 0, 12) as $r) {
            $ledger[] = [
                'tgl'            => $r['tgl'],
                'kode_dok'       => $r['no_invoice'] ?: ('INV-' . $r['id_invoice']),
                'kategori'       => 'ISSUED INVOICE',
                'klasifikasi'    => 'ACTUAL • BILLED AR',
                'badge_color'    => 'emerald',
                'entitas'        => $r['customer_nama'] ?: '-',
                'status_dok'     => strtoupper(str_replace('_', ' ', $r['status_barang_keluar'] ?: 'TERBIT')),
                'nominal'        => round((float)$r['nominal_total'], 2),
                'sumber_tabel'   => 'invoice',
                'arah_kas'       => 'RECEIVABLE (BILLED)'
            ];
        }

        foreach (array_slice($tagihan_rows, 0, 12) as $r) {
            $is_paid = (strtolower(trim((string)$r['status_bayar'])) === 'sudah_dibayar');
            $ledger[] = [
                'tgl'            => $r['tgl'],
                'kode_dok'       => $r['no_dokumen'],
                'kategori'       => $is_paid ? 'PAID FREIGHT BILL' : 'VENDOR OBLIGATION',
                'klasifikasi'    => $is_paid ? 'ACTUAL • CASH OUTFLOW' : 'ACTUAL • PAYABLE AP',
                'badge_color'    => $is_paid ? 'rose' : 'amber',
                'entitas'        => $r['vendor_nama'],
                'status_dok'     => $is_paid ? 'SUDAH DIBAYAR' : 'BELUM DIBAYAR',
                'nominal'        => round((float)$r['nominal_total'], 2),
                'sumber_tabel'   => 'tagihan_ekspedisi',
                'arah_kas'       => $is_paid ? 'CASH OUTFLOW (-)' : 'LIABILITY (UNPAID)'
            ];
        }

        foreach (array_slice($payroll_rows, 0, 6) as $r) {
            $ledger[] = [
                'tgl'            => $r['tgl'],
                'kode_dok'       => $r['no_dokumen'],
                'kategori'       => 'CLOSED PAYROLL SDM',
                'klasifikasi'    => 'ACTUAL • CASH OUTFLOW',
                'badge_color'    => 'rose',
                'entitas'        => $r['vendor_nama'],
                'status_dok'     => 'TUTUP BUKU GAJI',
                'nominal'        => round((float)$r['nominal_total'], 2),
                'sumber_tabel'   => 'salary_history',
                'arah_kas'       => 'CASH OUTFLOW (-)'
            ];
        }

        usort($ledger, function ($a, $b) {
            return strcmp($b['tgl'], $a['tgl']);
        });

        return array_slice($ledger, 0, 25);
    }

    // =========================================================================
    // PHASE 3: FINANCIAL INTELLIGENCE DEEP-DIVE ANALYTICS ENGINE
    // (Submodules 2: Financial Performance, 3: Revenue, 4: Profitability, 5: Cashflow)
    // =========================================================================

    private function buildFinancialIntelligencePayload($start_date, $end_date, $orders, $invoices, $taxes, $tagihan, $payroll, $invoiced_po_map, $cogs_status)
    {
        // 1. Monthly Comparative Financial Statement (Submodule 2: Financial Performance)
        $m_map = [];
        $makeMonth = function ($ym) {
            return [
                'ym'                     => $ym,
                'label'                  => date('M Y', strtotime($ym . '-01')),
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
                'operating_contribution' => 0.0,
                'mom_billing_pct'        => 0.0
            ];
        };

        foreach ($orders['rows'] as $r) {
            $ym = substr((string)$r['tgl'], 0, 7);
            if (strlen($ym) !== 7) continue;
            if (!isset($m_map[$ym])) $m_map[$ym] = $makeMonth($ym);
            $tot = max(0.0, (float)$r['nominal_total']);
            $dp  = max(0.0, (float)$r['dp_nominal']);
            $sts = strtolower(trim((string)$r['status_po']));
            $pay = strtoupper(trim((string)$r['sistem_pembayaran']));
            $m_map[$ym]['orders_gross'] += $tot;
            $m_map[$ym]['orders_cnt']++;
            if ($dp > 0) {
                $m_map[$ym]['verified_dp_cash'] += $dp;
            } elseif (in_array($pay, ['CASH', 'COD'], true) && $sts === 'disetujui') {
                $m_map[$ym]['verified_dp_cash'] += $tot;
            }
        }

        foreach ($invoices['rows'] as $r) {
            $ym = substr((string)$r['tgl'], 0, 7);
            if (strlen($ym) !== 7) continue;
            if (!isset($m_map[$ym])) $m_map[$ym] = $makeMonth($ym);
            $m_map[$ym]['invoices_gross']  += max(0.0, (float)$r['nominal_total']);
            $m_map[$ym]['invoices_dpp']    += max(0.0, (float)$r['nominal_dpp']);
            $m_map[$ym]['invoices_ongkir'] += max(0.0, (float)$r['nominal_ongkir']);
            $m_map[$ym]['invoices_cnt']++;
        }

        foreach ($tagihan['rows'] as $r) {
            $ym = substr((string)$r['tgl'], 0, 7);
            if (strlen($ym) !== 7) continue;
            if (!isset($m_map[$ym])) $m_map[$ym] = $makeMonth($ym);
            $val = max(0.0, (float)$r['nominal_total']);
            if (strtolower(trim((string)$r['status_bayar'])) === 'sudah_dibayar') {
                $m_map[$ym]['paid_freight'] += $val;
            } else {
                $m_map[$ym]['unpaid_obligations'] += $val;
            }
        }

        foreach ($payroll['rows'] as $r) {
            $ym = substr((string)$r['tgl'], 0, 7);
            if (strlen($ym) !== 7) continue;
            if (!isset($m_map[$ym])) $m_map[$ym] = $makeMonth($ym);
            $m_map[$ym]['paid_payroll'] += max(0.0, (float)$r['nominal_total']);
        }

        ksort($m_map);
        $monthly_statement = [];
        $prev_inv = null;
        foreach ($m_map as $ym => $m) {
            $m['orders_gross']           = round($m['orders_gross'], 2);
            $m['invoices_gross']         = round($m['invoices_gross'], 2);
            $m['invoices_dpp']           = round($m['invoices_dpp'], 2);
            $m['invoices_ongkir']        = round($m['invoices_ongkir'], 2);
            $m['verified_dp_cash']       = round($m['verified_dp_cash'], 2);
            $m['paid_freight']           = round($m['paid_freight'], 2);
            $m['paid_payroll']           = round($m['paid_payroll'], 2);
            $m['unpaid_obligations']     = round($m['unpaid_obligations'], 2);
            $m['operating_contribution'] = round($m['invoices_dpp'] - ($m['paid_freight'] + $m['paid_payroll']), 2);
            if ($prev_inv !== null && $prev_inv > 0) {
                $m['mom_billing_pct'] = round((($m['invoices_gross'] - $prev_inv) / $prev_inv) * 100, 1);
            }
            if ($m['invoices_gross'] > 0) {
                $prev_inv = $m['invoices_gross'];
            }
            $monthly_statement[] = $m;
        }
        $monthly_statement = array_reverse($monthly_statement); // Terbaru di atas untuk tabel laporan

        // 2. Revenue Analytics: Payment Terms, Sales Attribution & Unbilled PO List (Submodule 3)
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
                    'sales_nama'    => $sales_name,
                    'order_count'   => 0,
                    'po_count'      => 0,
                    'order_nominal' => 0.0,
                    'inv_count'     => 0,
                    'inv_nominal'   => 0.0,
                    'billed_nominal'=> 0.0,
                    'realization_pct'=> 0.0
                ];
            }
            $sales_map[$s_key]['order_count']++;
            $sales_map[$s_key]['po_count']++;
            $sales_map[$s_key]['order_nominal'] += $tot;

            $norm_po = strtolower(trim((string)($r['no_po'] ?? '')));
            $is_billed = ($norm_po !== '' && isset($invoiced_po_map[$norm_po]));
            if (!$is_billed && count($unbilled_list) < 20) {
                $po_ts = strtotime($r['tgl']);
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
            return ($mB <=> $mA);
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

        // 3. Profitability Analysis: Pre-COGS Waterfall & Top Billed SKUs (Submodule 4)
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

        // 4. Cashflow Intelligence: Unpaid Payables Aging Watchlist & Burn Rate (Submodule 5)
        $payables_watchlist = [];
        foreach ($tagihan['rows'] as $r) {
            if (strtolower(trim((string)$r['status_bayar'])) === 'sudah_dibayar') continue;
            $inv_ts = strtotime($r['tgl']);
            $days   = $inv_ts ? max(0, (int)floor(($today_ts - $inv_ts) / 86400)) : 0;
            $nom_v  = round((float)$r['nominal_total'], 2);
            $payables_watchlist[] = [
                'tgl'             => $r['tgl'],
                'tanggal_invoice' => $r['tgl'],
                'no_dokumen'      => $r['no_dokumen'],
                'nomor_invoice'   => $r['no_dokumen'],
                'vendor_nama'     => $r['vendor_nama'],
                'nama_ekspedisi'  => $r['vendor_nama'],
                'nomor_sj'        => '-',
                'status_approval' => ((int)$r['status_approval'] === 5) ? 'APPROVED (SIAP BAYAR)' : ('PROSES LEVEL ' . (int)$r['status_approval']),
                'days_aging'      => $days,
                'aging_bucket'    => ($days > 60) ? '> 60 Hari (Kritis)' : (($days > 30) ? '31–60 Hari (Overdue)' : '0–30 Hari (Current)'),
                'nominal_total'   => $nom_v,
                'nominal'         => $nom_v
            ];
        }
        usort($payables_watchlist, function ($a, $b) {
            return ($b['days_aging'] <=> $a['days_aging']);
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
     * Fetch Top Billed Products / SKUs from detail_barang_invoice (with po_masuk_item fallback)
     */
    private function fetchTopBilledSkus($start_date, $end_date)
    {
        $skus = [];

        if ($this->db->table_exists('detail_barang_invoice') && $this->db->table_exists('invoice')) {
            $sql = "
                SELECT
                    COALESCE(b.kode_barang, CONCAT('SKU-', dbi.id_barang)) AS kode_barang,
                    COALESCE(b.nama_barang, 'Item Invoice') AS nama_barang,
                    SUM(CAST(REPLACE(REPLACE(dbi.qty, '.', ''), ',', '') AS DECIMAL(18,2))) AS total_qty,
                    SUM(CAST(REPLACE(REPLACE(dbi.harga_total, '.', ''), ',', '') AS DECIMAL(18,2))) AS total_nominal,
                    COUNT(DISTINCT inv.id_invoice) AS doc_count,
                    'detail_barang_invoice' AS source_table
                FROM detail_barang_invoice dbi
                INNER JOIN invoice inv ON inv.id_invoice = dbi.id_invoice
                LEFT JOIN barang b ON b.id_barang = dbi.id_barang
                WHERE inv.status = 1
                  AND dbi.status = 1
                  AND inv.tgl_invoice >= " . $this->db->escape($start_date) . "
                  AND inv.tgl_invoice <= " . $this->db->escape($end_date) . "
                GROUP BY dbi.id_barang, b.kode_barang, b.nama_barang
                HAVING total_nominal > 0
                ORDER BY total_nominal DESC
                LIMIT 15
            ";
            $q = $this->db->query($sql);
            if ($q) {
                foreach ($q->result_array() as $r) {
                    $qty = max(1.0, (float)$r['total_qty']);
                    $nom = max(0.0, (float)$r['total_nominal']);
                    $skus[] = [
                        'kode_barang'   => $r['kode_barang'],
                        'nama_barang'   => $r['nama_barang'],
                        'item_nama'     => $r['nama_barang'],
                        'total_qty'     => round($qty, 2),
                        'qty_billed'    => round($qty, 2),
                        'avg_price'     => round($nom / $qty, 2),
                        'total_nominal' => round($nom, 2),
                        'gross_billed'  => round($nom, 2),
                        'doc_count'     => (int)$r['doc_count'],
                        'source_table'  => $r['source_table'],
                        'source'        => $r['source_table']
                    ];
                }
            }
        }

        // Fallback atau pelengkap dari po_masuk_item jika detail_barang_invoice kosong pada periode tersebut
        if (empty($skus) && $this->db->table_exists('po_masuk_item') && $this->db->table_exists('po_masuk')) {
            $sql_po = "
                SELECT
                    COALESCE(NULLIF(i.tipe_barang, ''), CONCAT('ITEM-', COALESCE(i.id_barang, 0))) AS kode_barang,
                    i.nama_barang,
                    SUM(COALESCE(i.qty, 0)) AS total_qty,
                    SUM(COALESCE(i.total_harga, 0)) AS total_nominal,
                    COUNT(DISTINCT p.id_po) AS doc_count,
                    'po_masuk_item' AS source_table
                FROM po_masuk_item i
                INNER JOIN po_masuk p ON p.id_po = i.id_po
                WHERE p.deleted = 0
                  AND p.status != 'ditolak'
                  AND p.tanggal_po >= " . $this->db->escape($start_date) . "
                  AND p.tanggal_po <= " . $this->db->escape($end_date) . "
                GROUP BY i.nama_barang, i.tipe_barang, i.id_barang
                HAVING total_nominal > 0
                ORDER BY total_nominal DESC
                LIMIT 15
            ";
            $q_po = $this->db->query($sql_po);
            if ($q_po) {
                foreach ($q_po->result_array() as $r) {
                    $qty = max(1.0, (float)$r['total_qty']);
                    $nom = max(0.0, (float)$r['total_nominal']);
                    $skus[] = [
                        'kode_barang'   => $r['kode_barang'],
                        'nama_barang'   => $r['nama_barang'],
                        'item_nama'     => $r['nama_barang'],
                        'total_qty'     => round($qty, 2),
                        'qty_billed'    => round($qty, 2),
                        'avg_price'     => round($nom / $qty, 2),
                        'total_nominal' => round($nom, 2),
                        'gross_billed'  => round($nom, 2),
                        'doc_count'     => (int)$r['doc_count'],
                        'source_table'  => $r['source_table'],
                        'source'        => $r['source_table']
                    ];
                }
            }
        }

        return $skus;
    }

    /**
     * =========================================================================
     * PHASE 4 — BUSINESS ANALYTICS (SUBMODULE 6: BUSINESS PERFORMANCE)
     * Customer Concentration (HHI), Product/SKU Commercial Matrix,
     * Principal Supplier & Freight Vendor Analytics, and Operational Fulfillment SLA
     * =========================================================================
     */
    private function buildBusinessAnalyticsPayload($start_date, $end_date, $orders, $invoices, $tagihan)
    {
        // 1. CUSTOMER INTELLIGENCE & CONCENTRATION (HHI + PARETO)
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
            return ($valB <=> $valA);
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

        // 2. PRODUCT / SKU COMMERCIAL VELOCITY ANALYTICS
        $product_intelligence = $this->fetchProductVelocityMatrix($start_date, $end_date);

        // 3. SUPPLIER CATALOG & LOGISTICS VENDOR ANALYTICS
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
            return ($b['total_nominal'] <=> $a['total_nominal']);
        });

        $principal_suppliers = $this->fetchPrincipalSupplierAnalytics();

        // 4. OPERATIONAL & FULFILLMENT SLA ANALYTICS
        $po_date_by_nopo = [];
        $status_pipeline = [];
        foreach ($orders['rows'] as $r) {
            $norm_po = strtolower(trim((string)($r['no_po'] ?? '')));
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
            $norm_po = strtolower(trim((string)($r['no_po'] ?? '')));
            if ($norm_po !== '' && isset($po_date_by_nopo[$norm_po]) && !empty($r['tgl'])) {
                $t1 = strtotime($po_date_by_nopo[$norm_po]);
                $t2 = strtotime($r['tgl']);
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
                'order_approval_rate_pct'   => $approval_rate,
                'invoice_dispatch_rate_pct' => $dispatch_rate,
                'avg_po_to_invoice_days'    => $avg_lead_days,
                'matched_po_invoice_count'  => $lead_matched,
                'freight_cost_to_billed_pct'=> $freight_ratio
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
     * Fetch Combined Product / SKU Commercial Matrix (po_masuk_item vs detail_barang_invoice)
     */
    private function fetchProductVelocityMatrix($start_date, $end_date)
    {
        $sku_map = [];
        $distinct_ordered = 0;
        $distinct_billed  = 0;
        $units_ordered    = 0.0;
        $units_billed     = 0.0;

        // 1. Ordered Items from po_masuk_item
        if ($this->db->table_exists('po_masuk_item') && $this->db->table_exists('po_masuk')) {
            $sql_ord = "
                SELECT
                    COALESCE(NULLIF(i.tipe_barang, ''), CONCAT('SKU-', COALESCE(i.id_barang, 0))) AS kode_barang,
                    COALESCE(NULLIF(i.nama_barang, ''), 'Item PO') AS nama_barang,
                    SUM(COALESCE(i.qty, 0)) AS ord_qty,
                    SUM(COALESCE(i.total_harga, 0)) AS ord_nom
                FROM po_masuk_item i
                INNER JOIN po_masuk p ON p.id_po = i.id_po
                WHERE p.deleted = 0
                  AND p.status != 'ditolak'
                  AND p.tanggal_po >= " . $this->db->escape($start_date) . "
                  AND p.tanggal_po <= " . $this->db->escape($end_date) . "
                GROUP BY i.nama_barang, i.tipe_barang, i.id_barang
            ";
            $q_ord = $this->db->query($sql_ord);
            if ($q_ord) {
                foreach ($q_ord->result_array() as $r) {
                    $name = trim((string)$r['nama_barang']);
                    $key  = strtolower($name);
                    $qty  = max(0.0, (float)$r['ord_qty']);
                    $nom  = max(0.0, (float)$r['ord_nom']);
                    if (!isset($sku_map[$key])) {
                        $sku_map[$key] = [
                            'kode_barang'     => $r['kode_barang'],
                            'nama_barang'     => $name,
                            'ordered_qty'     => 0.0,
                            'ordered_nominal' => 0.0,
                            'billed_qty'      => 0.0,
                            'billed_nominal'  => 0.0
                        ];
                    }
                    $sku_map[$key]['ordered_qty']     += $qty;
                    $sku_map[$key]['ordered_nominal'] += $nom;
                    $units_ordered += $qty;
                    $distinct_ordered++;
                }
            }
        }

        // 2. Billed Items from detail_barang_invoice
        if ($this->db->table_exists('detail_barang_invoice') && $this->db->table_exists('invoice')) {
            $sql_inv = "
                SELECT
                    COALESCE(b.kode_barang, CONCAT('SKU-', dbi.id_barang)) AS kode_barang,
                    COALESCE(b.nama_barang, 'Item Invoice') AS nama_barang,
                    SUM(CAST(REPLACE(REPLACE(dbi.qty, '.', ''), ',', '') AS DECIMAL(18,2))) AS inv_qty,
                    SUM(CAST(REPLACE(REPLACE(dbi.harga_total, '.', ''), ',', '') AS DECIMAL(18,2))) AS inv_nom
                FROM detail_barang_invoice dbi
                INNER JOIN invoice inv ON inv.id_invoice = dbi.id_invoice
                LEFT JOIN barang b ON b.id_barang = dbi.id_barang
                WHERE inv.status = 1
                  AND dbi.status = 1
                  AND inv.tgl_invoice >= " . $this->db->escape($start_date) . "
                  AND inv.tgl_invoice <= " . $this->db->escape($end_date) . "
                GROUP BY dbi.id_barang, b.kode_barang, b.nama_barang
            ";
            $q_inv = $this->db->query($sql_inv);
            if ($q_inv) {
                foreach ($q_inv->result_array() as $r) {
                    $name = trim((string)$r['nama_barang']);
                    $key  = strtolower($name);
                    $qty  = max(0.0, (float)$r['inv_qty']);
                    $nom  = max(0.0, (float)$r['inv_nom']);
                    if (!isset($sku_map[$key])) {
                        $sku_map[$key] = [
                            'kode_barang'     => $r['kode_barang'],
                            'nama_barang'     => $name,
                            'ordered_qty'     => 0.0,
                            'ordered_nominal' => 0.0,
                            'billed_qty'      => 0.0,
                            'billed_nominal'  => 0.0
                        ];
                    } else {
                        $sku_map[$key]['kode_barang'] = $r['kode_barang'] ?: $sku_map[$key]['kode_barang'];
                    }
                    $sku_map[$key]['billed_qty']     += $qty;
                    $sku_map[$key]['billed_nominal'] += $nom;
                    $units_billed += $qty;
                    $distinct_billed++;
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
                'fulfillment_pct' => ($ord_q > 0) ? round(($inv_q / $ord_q) * 100, 1) : (($inv_q > 0) ? 100.0 : 0.0),
                'avg_unit_price'  => round($ref_n / max(1.0, $ref_q), 2)
            ];
        }

        usort($sku_list, function ($a, $b) {
            $vA = max($a['billed_nominal'], $a['ordered_nominal']);
            $vB = max($b['billed_nominal'], $b['ordered_nominal']);
            return ($vB <=> $vA);
        });

        return [
            'summary' => [
                'distinct_skus_ordered' => $distinct_ordered,
                'distinct_skus_billed'  => $distinct_billed,
                'total_units_ordered'   => round($units_ordered, 2),
                'total_units_billed'    => round($units_billed, 2),
                'unit_fulfillment_pct'  => ($units_ordered > 0) ? round(($units_billed / $units_ordered) * 100, 1) : 0.0
            ],
            'matrix' => array_slice($sku_list, 0, 20)
        ];
    }

    /**
     * Fetch Principal / Medical Device Supplier Catalog Analytics (acc_pemasok & sub-tables)
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

        if (!$this->db->table_exists('acc_pemasok')) {
            return $res;
        }

        if ($this->db->table_exists('acc_pemasok_harga')) {
            $res['stats']['price_catalog_docs'] = (int)$this->db->count_all_results('acc_pemasok_harga');
        }
        if ($this->db->table_exists('acc_pemasok_agreement')) {
            $res['stats']['agreement_docs'] = (int)$this->db->count_all_results('acc_pemasok_agreement');
        }

        $has_status = $this->db->table_exists('acc_master_status_pemasok');
        $has_tipe   = $this->db->table_exists('acc_master_tipe_pemasok');
        $has_prod   = $this->db->table_exists('acc_pemasok_product');
        $has_harga  = $this->db->table_exists('acc_pemasok_harga');
        $has_agree  = $this->db->table_exists('acc_pemasok_agreement');

        $join_status = $has_status ? "LEFT JOIN acc_master_status_pemasok s ON p.id_status = s.id" : "";
        $join_tipe   = $has_tipe   ? "LEFT JOIN acc_master_tipe_pemasok t ON p.id_tipe = t.id" : "";
        $sel_status  = $has_status ? "COALESCE(s.nama, 'Aktif')" : "'Aktif'";
        $sel_tipe    = $has_tipe   ? "COALESCE(t.nama, 'Principal')" : "'Principal'";
        $sel_prod    = $has_prod   ? "(SELECT COUNT(*) FROM acc_pemasok_product pp WHERE pp.id_pemasok = p.id)" : "0";
        $sel_harga   = $has_harga  ? "(SELECT COUNT(*) FROM acc_pemasok_harga ph WHERE ph.id_pemasok = p.id)" : "0";
        $sel_agree   = $has_agree  ? "(SELECT COUNT(*) FROM acc_pemasok_agreement pa WHERE pa.id_pemasok = p.id)" : "0";

        $sql = "
            SELECT
                p.id,
                COALESCE(p.nama_pemasok, '-') AS nama_pemasok,
                COALESCE(p.negara, '-') AS negara,
                p.id_status,
                {$sel_status} AS status_nama,
                {$sel_tipe} AS tipe_nama,
                {$sel_prod} AS product_count,
                {$sel_harga} AS harga_doc_count,
                {$sel_agree} AS agreement_count,
                COALESCE(p.tanggal_loa_awal, '-') AS tanggal_loa
            FROM acc_pemasok p
            {$join_status}
            {$join_tipe}
            WHERE p.status_data = 1
            ORDER BY harga_doc_count DESC, product_count DESC, p.id DESC
            LIMIT 25
        ";

        $q = $this->db->query($sql);
        if (!$q) {
            return $res;
        }

        foreach ($q->result_array() as $r) {
            $res['stats']['total_suppliers']++;
            if ((int)$r['id_status'] === 1) {
                $res['stats']['active_suppliers']++;
            }
            if (count($res['rows']) < 15) {
                $res['rows'][] = [
                    'nama_pemasok'    => $r['nama_pemasok'],
                    'negara'          => $r['negara'],
                    'status_nama'     => $r['status_nama'],
                    'tipe_nama'       => $r['tipe_nama'],
                    'product_count'   => (int)$r['product_count'],
                    'harga_doc_count' => (int)$r['harga_doc_count'],
                    'agreement_count' => (int)$r['agreement_count'],
                    'tanggal_loa'     => $r['tanggal_loa']
                ];
            }
        }

        return $res;
    }
}
