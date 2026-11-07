<?php
defined('BASEPATH') or exit('No direct script access allowed');

$kpis          = $bi_initial_data['kpis'] ?? [];
$top_customers = $bi_initial_data['top_customers'] ?? [];
$recent_ledger = $bi_initial_data['recent_ledger'] ?? [];
$fin_intel     = $bi_initial_data['financial_intelligence'] ?? [];
$biz_analytics = $bi_initial_data['business_analytics'] ?? [];
$active_sub    = $bi_active_sub ?? 'executive_overview';
$active_meta   = $bi_active_meta ?? [];

$default_series_mode = 'ALL_LAYERS';
if ($active_sub === 'revenue_analytics') {
    $default_series_mode = 'ORDER_VS_INVOICE';
} elseif ($active_sub === 'profitability_analysis') {
    $default_series_mode = 'BILLED_VS_CASHOUT';
} elseif ($active_sub === 'cashflow_intelligence') {
    $default_series_mode = 'OBLIGATIONS_VS_PAID';
}
?>

<!-- TradingView Lightweight Charts (jsDelivr CDN + unpkg Fallback) -->
<script src="https://cdn.jsdelivr.net/npm/lightweight-charts@4.1.3/dist/lightweight-charts.standalone.production.js"
        onerror="var s=document.createElement('script');s.src='https://unpkg.com/lightweight-charts@4.1.3/dist/lightweight-charts.standalone.production.js';document.head.appendChild(s);"></script>

<style>
    /* =========================================================================
     * VYM BUSINESS INTELLIGENCE — BLOOMBERG TERMINAL INSTITUTIONAL THEME
     * Scoped inside .vym-bi-wrap so existing Office System UI remains untouched
     * ========================================================================= */
    .vym-bi-wrap {
        background: #060b14;
        color: #e2e8f0;
        border: 1px solid #1e293b;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 24px;
        box-shadow: 0 18px 40px rgba(2, 6, 23, 0.55);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .vym-mono {
        font-family: 'Consolas', 'JetBrains Mono', 'Courier New', monospace;
        letter-spacing: -0.2px;
    }

    /* 1. Top Bloomberg Command Header */
    .vym-top-bar {
        background: linear-gradient(90deg, #0b1324 0%, #0f1d35 60%, #0b1324 100%);
        border: 1px solid #1e3a5f;
        border-left: 4px solid #00e5ff;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 12px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .vym-terminal-brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .vym-terminal-badge {
        background: rgba(0, 229, 255, 0.12);
        border: 1px solid rgba(0, 229, 255, 0.4);
        color: #00e5ff;
        font-weight: 800;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 4px;
        letter-spacing: 0.8px;
    }

    .vym-terminal-title {
        font-size: 16px;
        font-weight: 800;
        color: #f8fafc;
        margin: 0;
        letter-spacing: 0.3px;
    }

    .vym-terminal-sub {
        font-size: 11.5px;
        color: #94a3b8;
        margin: 2px 0 0 0;
    }

    .vym-status-strip {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        font-size: 11px;
    }

    .vym-pill-live {
        background: rgba(16, 185, 129, 0.14);
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: #34d399;
        padding: 3px 9px;
        border-radius: 4px;
        font-weight: 700;
    }

    .vym-pill-principle {
        background: rgba(245, 158, 11, 0.14);
        border: 1px solid rgba(245, 158, 11, 0.4);
        color: #fbbf24;
        padding: 3px 9px;
        border-radius: 4px;
        font-weight: 700;
    }

    /* 2. 8-Submodule Navigation Ribbon */
    .vym-subnav-ribbon {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(175px, 1fr));
        gap: 6px;
        margin-bottom: 12px;
        background: #0a101d;
        padding: 6px;
        border-radius: 8px;
        border: 1px solid #1e293b;
    }

    .vym-subnav-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 7px 10px;
        border-radius: 6px;
        background: #0f172a;
        border: 1px solid #1e293b;
        color: #94a3b8;
        font-size: 11.5px;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.15s ease;
    }

    .vym-subnav-item:hover {
        background: #162238;
        color: #f8fafc;
        border-color: #334155;
    }

    .vym-subnav-item.active {
        background: linear-gradient(135deg, #0284c7 0%, #1d4ed8 100%);
        color: #ffffff;
        border-color: #38bdf8;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
    }

    .vym-subnav-no {
        font-family: 'Consolas', monospace;
        font-size: 10px;
        opacity: 0.75;
        margin-right: 5px;
    }

    /* 3. Interactive Filter Toolbar */
    .vym-filter-bar {
        background: #0b1322;
        border: 1px solid #1e293b;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 14px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .vym-btn-group {
        display: inline-flex;
        background: #060b14;
        border: 1px solid #1e293b;
        border-radius: 6px;
        padding: 2px;
        gap: 2px;
    }

    .vym-btn {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .vym-btn:hover {
        color: #f8fafc;
        background: rgba(255, 255, 255, 0.06);
    }

    .vym-btn.active {
        background: #0284c7;
        color: #ffffff;
    }

    .vym-date-input {
        background: #060b14 !important;
        border: 1px solid #334155 !important;
        color: #e2e8f0 !important;
        font-size: 11.5px !important;
        height: 29px !important;
        padding: 2px 8px !important;
        border-radius: 4px !important;
        width: 128px;
    }

    /* 4. High-Density Institutional KPI Grid */
    .vym-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(265px, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .vym-kpi-card {
        background: #0b1322;
        border: 1px solid #1e293b;
        border-radius: 8px;
        padding: 13px 14px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: border-color 0.18s ease, transform 0.18s ease;
    }

    .vym-kpi-card:hover {
        border-color: #38bdf8;
        transform: translateY(-2px);
    }

    .vym-kpi-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        margin-bottom: 6px;
    }

    .vym-kpi-code {
        font-family: 'Consolas', monospace;
        font-size: 10.5px;
        font-weight: 700;
        color: #38bdf8;
    }

    .vym-class-badge {
        font-size: 9.5px;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 3px;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .vym-class-actual {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.35);
    }

    .vym-class-estimated {
        background: rgba(245, 158, 11, 0.15);
        color: #fbbf24;
        border: 1px solid rgba(245, 158, 11, 0.35);
    }

    .vym-class-forecast {
        background: rgba(192, 132, 252, 0.15);
        color: #c084fc;
        border: 1px solid rgba(192, 132, 252, 0.35);
    }

    .vym-kpi-title {
        font-size: 12px;
        font-weight: 700;
        color: #cbd5e1;
        margin-bottom: 4px;
    }

    .vym-kpi-value {
        font-family: 'Consolas', 'JetBrains Mono', monospace;
        font-size: 20px;
        font-weight: 800;
        color: #f8fafc;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .vym-kpi-meta {
        border-top: 1px dashed #1e293b;
        padding-top: 7px;
        font-size: 11px;
        color: #94a3b8;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .vym-kpi-meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .vym-trace-btn {
        background: rgba(56, 189, 248, 0.1);
        border: 1px solid rgba(56, 189, 248, 0.3);
        color: #38bdf8;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 6px;
        align-self: flex-start;
    }

    .vym-trace-btn:hover {
        background: #0284c7;
        color: #ffffff;
    }

    /* 5. Terminal Panels & Charts */
    .vym-panel {
        background: #0b1322;
        border: 1px solid #1e293b;
        border-radius: 8px;
        margin-bottom: 16px;
        overflow: hidden;
    }

    .vym-panel-head {
        background: #0f1a2e;
        border-bottom: 1px solid #1e293b;
        padding: 10px 14px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .vym-panel-title {
        font-size: 12.5px;
        font-weight: 800;
        color: #f8fafc;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .vym-panel-body {
        padding: 14px;
    }

    /* 6. Institutional Dark Data Tables */
    .vym-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11.5px;
    }

    .vym-table th {
        background: #09101d;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 10.5px;
        letter-spacing: 0.4px;
        padding: 8px 10px;
        border-bottom: 1px solid #1e293b;
    }

    .vym-table td {
        padding: 8px 10px;
        border-bottom: 1px solid rgba(30, 41, 59, 0.65);
        color: #e2e8f0;
        vertical-align: middle;
    }

    .vym-table tbody tr:hover {
        background: rgba(56, 189, 248, 0.06);
    }

    .vym-badge-cyan {
        background: rgba(56, 189, 248, 0.14);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.35);
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 10px;
        font-weight: 700;
    }

    .vym-badge-emerald {
        background: rgba(16, 185, 129, 0.14);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.35);
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 10px;
        font-weight: 700;
    }

    .vym-badge-amber {
        background: rgba(245, 158, 11, 0.14);
        color: #fbbf24;
        border: 1px solid rgba(245, 158, 11, 0.35);
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 10px;
        font-weight: 700;
    }

    .vym-badge-rose {
        background: rgba(239, 68, 68, 0.14);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.35);
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 10px;
        font-weight: 700;
    }

    /* Chart Legend Overlay */
    .vym-chart-container {
        position: relative;
        width: 100%;
        height: 390px;
        background: #070d19;
    }

    .vym-chart-legend {
        position: absolute;
        top: 10px;
        left: 12px;
        z-index: 10;
        background: rgba(11, 19, 34, 0.88);
        border: 1px solid #1e293b;
        border-radius: 6px;
        padding: 8px 12px;
        pointer-events: none;
        font-size: 11px;
    }
</style>

<div class="vym-bi-wrap">
    <!-- 1. TOP BLOOMBERG TERMINAL COMMAND BAR -->
    <div class="vym-top-bar">
        <div class="vym-terminal-brand">
            <span class="vym-terminal-badge vym-mono"><?= htmlspecialchars($active_meta['code'] ?? 'VYM:EXEC_OVW') ?></span>
            <div>
                <h2 class="vym-terminal-title">
                    VYM BUSINESS INTELLIGENCE <span style="color: #38bdf8; font-weight: 400;">//</span> <?= htmlspecialchars($active_meta['title'] ?? 'Executive Overview') ?>
                </h2>
                <p class="vym-terminal-sub"><?= htmlspecialchars($active_meta['subtitle'] ?? '') ?></p>
            </div>
        </div>
        <div class="vym-status-strip">
            <span class="vym-pill-live"><i class="fas fa-circle mr-1" style="font-size: 8px;"></i>REAL-TIME DB FEED</span>
            <span class="vym-pill-principle"><i class="fas fa-shield-alt mr-1"></i>STRICT ACCOUNTING SEPARATION</span>
            <span class="badge badge-dark vym-mono" style="background:#111c30; border:1px solid #334155; padding:4px 8px; color:#94a3b8;" id="vym-active-period-label">
                <?= htmlspecialchars($bi_filter['label'] ?? 'YTD') ?>
            </span>
        </div>
    </div>

    <!-- 2. 8-SUBMODULE NAVIGATION RIBBON -->
    <div class="vym-subnav-ribbon">
        <?php foreach ($bi_registry as $slug => $meta): ?>
            <a href="<?= base_url('vym_bi/' . $slug) ?>"
               class="vym-subnav-item <?= ($active_sub === $slug) ? 'active' : '' ?>">
                <span>
                    <span class="vym-subnav-no">[<?= $meta['no'] ?>]</span>
                    <?= htmlspecialchars($meta['title']) ?>
                </span>
                <i class="<?= $meta['icon'] ?>" style="font-size: 11px; opacity: 0.85;"></i>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if ($active_sub !== 'executive_overview'): ?>
        <!-- Submodule Contextual Banner -->
        <div style="background: rgba(2, 132, 199, 0.12); border: 1px solid rgba(56, 189, 248, 0.35); border-radius: 8px; padding: 10px 14px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
            <div style="font-size: 12px; color: #e2e8f0;">
                <i class="fas fa-bolt text-info mr-1"></i>
                <strong>Submodul Aktif: [<?= htmlspecialchars($active_meta['no']) ?>] <?= htmlspecialchars($active_meta['title']) ?></strong>
                — <?= htmlspecialchars($active_meta['subtitle'] ?? 'Laporan & kalkulasi tervalidasi dari tabel transaksi sumber.') ?>
            </div>
            <span class="badge badge-info vym-mono"><?= htmlspecialchars($active_meta['phase'] ?? 'PHASE 3 • ACTIVE') ?></span>
        </div>
    <?php endif; ?>

    <!-- 3. INTERACTIVE TIME & GRANULARITY FILTER BAR -->
    <div class="vym-filter-bar">
        <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
            <span style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">
                <i class="fas fa-calendar-alt text-info mr-1"></i> Periode Analisis:
            </span>
            <div class="vym-btn-group" id="vym-preset-group">
                <?php
                $presets = [
                    'MTD'       => 'MTD',
                    'QTD'       => 'QTD',
                    'YTD'       => 'YTD',
                    '1Y'        => '1Y (12Bln)',
                    'PREV_YEAR' => 'Tahun Lalu',
                    'ALL'       => 'All-Time'
                ];
                $cur_preset = $bi_filter['preset'] ?? 'YTD';
                foreach ($presets as $p_key => $p_label):
                ?>
                    <button type="button" class="vym-btn <?= ($cur_preset === $p_key) ? 'active' : '' ?>" data-preset="<?= $p_key ?>">
                        <?= $p_label ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="d-inline-flex align-items-center" style="gap: 5px;">
                <input type="date" id="vym-start-date" class="vym-date-input vym-mono" value="<?= htmlspecialchars($bi_filter['start_date'] ?? date('Y-01-01')) ?>">
                <span style="color: #64748b; font-size: 11px;">s/d</span>
                <input type="date" id="vym-end-date" class="vym-date-input vym-mono" value="<?= htmlspecialchars($bi_filter['end_date'] ?? date('Y-m-d')) ?>">
                <button type="button" id="vym-btn-apply-custom" class="vym-btn active" style="padding: 5px 10px;">
                    <i class="fas fa-filter mr-1"></i> Terapkan
                </button>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
            <span style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Interval:</span>
            <div class="vym-btn-group" id="vym-interval-group">
                <button type="button" class="vym-btn <?= ($bi_interval === '1D') ? 'active' : '' ?>" data-interval="1D">1D (Harian)</button>
                <button type="button" class="vym-btn <?= ($bi_interval === '1W') ? 'active' : '' ?>" data-interval="1W">1W (Mingguan)</button>
                <button type="button" class="vym-btn <?= ($bi_interval === '1M') ? 'active' : '' ?>" data-interval="1M">1M (Bulanan)</button>
            </div>
        </div>
    </div>

    <!-- 4. EIGHT INSTITUTIONAL KPI CARDS (STRICT FINANCIAL SEPARATION) -->
    <div class="vym-kpi-grid" id="vym-kpi-grid">
        <!-- KPI 1: Customer Orders (PO Masuk) -->
        <?php $k1 = $kpis['customer_orders'] ?? []; ?>
        <div class="vym-kpi-card" style="border-top: 3px solid #38bdf8;">
            <div>
                <div class="vym-kpi-top">
                    <span class="vym-kpi-code"><?= htmlspecialchars($k1['code'] ?? 'VYM:ORDER_BOOK') ?></span>
                    <span class="vym-class-badge vym-class-actual"><?= htmlspecialchars($k1['classification'] ?? 'ACTUAL • ORDER') ?></span>
                </div>
                <div class="vym-kpi-title">1. Customer Orders (PO Masuk)</div>
                <div class="vym-kpi-value" style="color: #38bdf8;" id="kpi-order-val">
                    Rp <?= number_format($k1['nominal'] ?? 0, 0, ',', '.') ?>
                </div>
            </div>
            <div class="vym-kpi-meta">
                <div class="vym-kpi-meta-row">
                    <span>Volume Pesanan:</span>
                    <strong class="vym-mono text-white" id="kpi-order-cnt"><?= number_format($k1['count'] ?? 0, 0, ',', '.') ?> PO</strong>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>Approved vs Kroscek:</span>
                    <span class="vym-mono" id="kpi-order-split"><?= ($k1['approved_count'] ?? 0) ?> Disetujui / <?= ($k1['pending_count'] ?? 0) ?> Proses</span>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>Status Kas:</span>
                    <span style="color: #fbbf24; font-weight: 700;">Bukan Kas Masuk</span>
                </div>
                <button type="button" class="vym-trace-btn" onclick="openVymFormulaModal('customer_orders')">
                    <i class="fas fa-code-branch mr-1"></i> Formula &amp; Source Table
                </button>
            </div>
        </div>

        <!-- KPI 2: Issued Invoices (Billed Revenue / AR) -->
        <?php $k2 = $kpis['issued_invoices'] ?? []; ?>
        <div class="vym-kpi-card" style="border-top: 3px solid #10b981;">
            <div>
                <div class="vym-kpi-top">
                    <span class="vym-kpi-code"><?= htmlspecialchars($k2['code'] ?? 'VYM:BILLED_AR') ?></span>
                    <span class="vym-class-badge vym-class-actual"><?= htmlspecialchars($k2['classification'] ?? 'ACTUAL • BILLED AR') ?></span>
                </div>
                <div class="vym-kpi-title">2. Issued Invoices (Penagihan Terbit)</div>
                <div class="vym-kpi-value" style="color: #10b981;" id="kpi-inv-val">
                    Rp <?= number_format($k2['nominal'] ?? 0, 0, ',', '.') ?>
                </div>
            </div>
            <div class="vym-kpi-meta">
                <div class="vym-kpi-meta-row">
                    <span>Dokumen Faktur:</span>
                    <strong class="vym-mono text-white" id="kpi-inv-cnt"><?= number_format($k2['count'] ?? 0, 0, ',', '.') ?> Invoice</strong>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>Net DPP (Excl. Ongkir):</span>
                    <span class="vym-mono" id="kpi-inv-dpp">Rp <?= number_format($k2['net_dpp'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>Rasio Realisasi PO→Inv:</span>
                    <strong class="vym-mono" style="color: #34d399;" id="kpi-inv-conv"><?= $k2['conversion_pct'] ?? 0 ?>%</strong>
                </div>
                <button type="button" class="vym-trace-btn" onclick="openVymFormulaModal('issued_invoices')">
                    <i class="fas fa-code-branch mr-1"></i> Formula &amp; Source Table
                </button>
            </div>
        </div>

        <!-- KPI 3: Unbilled Order Backlog -->
        <?php $k3 = $kpis['unbilled_backlog'] ?? []; ?>
        <div class="vym-kpi-card" style="border-top: 3px solid #f59e0b;">
            <div>
                <div class="vym-kpi-top">
                    <span class="vym-kpi-code"><?= htmlspecialchars($k3['code'] ?? 'VYM:UNBILLED_PO') ?></span>
                    <span class="vym-class-badge vym-class-actual"><?= htmlspecialchars($k3['classification'] ?? 'ACTUAL • PIPELINE') ?></span>
                </div>
                <div class="vym-kpi-title">3. Unbilled Order Backlog (PO Belum Invoice)</div>
                <div class="vym-kpi-value" style="color: #fbbf24;" id="kpi-unbilled-val">
                    Rp <?= number_format($k3['nominal'] ?? 0, 0, ',', '.') ?>
                </div>
            </div>
            <div class="vym-kpi-meta">
                <div class="vym-kpi-meta-row">
                    <span>PO Belum Ditagih:</span>
                    <strong class="vym-mono text-white" id="kpi-unbilled-cnt"><?= number_format($k3['count'] ?? 0, 0, ',', '.') ?> PO</strong>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>PO Sudah Terbit Invoice:</span>
                    <span class="vym-mono" id="kpi-billed-cnt"><?= number_format($k3['billed_po_cnt'] ?? 0, 0, ',', '.') ?> PO</span>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>Tindakan:</span>
                    <span style="color: #38bdf8;">Percepat Pengiriman &amp; Penagihan</span>
                </div>
                <button type="button" class="vym-trace-btn" onclick="openVymFormulaModal('unbilled_backlog')">
                    <i class="fas fa-code-branch mr-1"></i> Formula &amp; Source Table
                </button>
            </div>
        </div>

        <!-- KPI 4: Approved Tax Basis (DPP + PPN) -->
        <?php $k4 = $kpis['recognized_tax'] ?? []; ?>
        <div class="vym-kpi-card" style="border-top: 3px solid #818cf8;">
            <div>
                <div class="vym-kpi-top">
                    <span class="vym-kpi-code"><?= htmlspecialchars($k4['code'] ?? 'VYM:TAX_BASIS') ?></span>
                    <span class="vym-class-badge vym-class-actual"><?= htmlspecialchars($k4['classification'] ?? 'ACTUAL • TAX') ?></span>
                </div>
                <div class="vym-kpi-title">4. Approved Tax Basis (DPP + PPN)</div>
                <div class="vym-kpi-value" style="color: #818cf8;" id="kpi-tax-val">
                    Rp <?= number_format($k4['nominal'] ?? 0, 0, ',', '.') ?>
                </div>
            </div>
            <div class="vym-kpi-meta">
                <div class="vym-kpi-meta-row">
                    <span>DPP Disetujui:</span>
                    <span class="vym-mono text-white" id="kpi-tax-dpp">Rp <?= number_format($k4['dpp_nominal'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>PPN Keluaran:</span>
                    <span class="vym-mono" style="color: #a5b4fc;" id="kpi-tax-ppn">Rp <?= number_format($k4['ppn_nominal'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>Status Faktur Pajak:</span>
                    <span class="vym-mono" id="kpi-tax-cnt"><?= ($k4['approved_count'] ?? 0) ?> Appr / <?= ($k4['pending_count'] ?? 0) ?> Pending</span>
                </div>
                <button type="button" class="vym-trace-btn" onclick="openVymFormulaModal('recognized_tax')">
                    <i class="fas fa-code-branch mr-1"></i> Formula &amp; Source Table
                </button>
            </div>
        </div>

        <!-- KPI 5: Verified Customer Down Payments & Cash PO -->
        <?php $k5 = $kpis['verified_customer_cash'] ?? []; ?>
        <div class="vym-kpi-card" style="border-top: 3px solid #2dd4bf;">
            <div>
                <div class="vym-kpi-top">
                    <span class="vym-kpi-code"><?= htmlspecialchars($k5['code'] ?? 'VYM:VERIFIED_DP_CASH') ?></span>
                    <span class="vym-class-badge vym-class-actual"><?= htmlspecialchars($k5['classification'] ?? 'ACTUAL • DP/CASH') ?></span>
                </div>
                <div class="vym-kpi-title">5. Verified Customer DP &amp; Cash PO</div>
                <div class="vym-kpi-value" style="color: #2dd4bf;" id="kpi-cashin-val">
                    Rp <?= number_format($k5['nominal'] ?? 0, 0, ',', '.') ?>
                </div>
            </div>
            <div class="vym-kpi-meta">
                <div class="vym-kpi-meta-row">
                    <span>Uang Muka (DP Cicilan):</span>
                    <span class="vym-mono text-white" id="kpi-cashin-dp">Rp <?= number_format($k5['dp_nominal'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>PO Cash/COD Approved:</span>
                    <span class="vym-mono" id="kpi-cashin-cod">Rp <?= number_format($k5['cash_po_nominal'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>Catatan Akuntansi:</span>
                    <span style="color: #94a3b8;">Piutang Tempo Tidak Dihitung Kas</span>
                </div>
                <button type="button" class="vym-trace-btn" onclick="openVymFormulaModal('verified_customer_cash')">
                    <i class="fas fa-code-branch mr-1"></i> Formula &amp; Source Table
                </button>
            </div>
        </div>

        <!-- KPI 6: Supplier & Freight Obligations (Unpaid AP) -->
        <?php $k6 = $kpis['supplier_obligations'] ?? []; ?>
        <div class="vym-kpi-card" style="border-top: 3px solid #fb923c;">
            <div>
                <div class="vym-kpi-top">
                    <span class="vym-kpi-code"><?= htmlspecialchars($k6['code'] ?? 'VYM:AP_OBLIGATION') ?></span>
                    <span class="vym-class-badge vym-class-actual"><?= htmlspecialchars($k6['classification'] ?? 'ACTUAL • PAYABLE AP') ?></span>
                </div>
                <div class="vym-kpi-title">6. Vendor &amp; Freight Obligations (Unpaid)</div>
                <div class="vym-kpi-value" style="color: #fb923c;" id="kpi-obl-val">
                    Rp <?= number_format($k6['nominal'] ?? 0, 0, ',', '.') ?>
                </div>
            </div>
            <div class="vym-kpi-meta">
                <div class="vym-kpi-meta-row">
                    <span>Approved Belum Bayar:</span>
                    <span class="vym-mono text-white" id="kpi-obl-appr">Rp <?= number_format($k6['approved_unpaid'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>Menunggu Approval:</span>
                    <span class="vym-mono" id="kpi-obl-wait">Rp <?= number_format($k6['waiting_review'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>Tagihan &gt; 30 Hari:</span>
                    <strong class="vym-mono" style="color: #f87171;" id="kpi-obl-overdue"><?= number_format($k6['overdue_count'] ?? 0, 0, ',', '.') ?> Dokumen</strong>
                </div>
                <button type="button" class="vym-trace-btn" onclick="openVymFormulaModal('supplier_obligations')">
                    <i class="fas fa-code-branch mr-1"></i> Formula &amp; Source Table
                </button>
            </div>
        </div>

        <!-- KPI 7: Actual Cash Expenditures (Settled Outflow) -->
        <?php $k7 = $kpis['actual_cash_outflow'] ?? []; ?>
        <div class="vym-kpi-card" style="border-top: 3px solid #ef4444;">
            <div>
                <div class="vym-kpi-top">
                    <span class="vym-kpi-code"><?= htmlspecialchars($k7['code'] ?? 'VYM:ACTUAL_CASH_OUT') ?></span>
                    <span class="vym-class-badge vym-class-actual"><?= htmlspecialchars($k7['classification'] ?? 'ACTUAL • SETTLED') ?></span>
                </div>
                <div class="vym-kpi-title">7. Actual Cash Expenditures (Paid)</div>
                <div class="vym-kpi-value" style="color: #f87171;" id="kpi-out-val">
                    Rp <?= number_format($k7['nominal'] ?? 0, 0, ',', '.') ?>
                </div>
            </div>
            <div class="vym-kpi-meta">
                <div class="vym-kpi-meta-row">
                    <span>Ekspedisi Lunas:</span>
                    <span class="vym-mono text-white" id="kpi-out-freight">Rp <?= number_format($k7['paid_freight'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>Payroll SDM Tertutup:</span>
                    <span class="vym-mono" id="kpi-out-payroll">Rp <?= number_format($k7['paid_payroll'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>Total Bukti Bayar:</span>
                    <span class="vym-mono" id="kpi-out-cnt"><?= ($k7['paid_freight_cnt'] ?? 0) ?> Tagihan + <?= ($k7['payroll_slips'] ?? 0) ?> Slip</span>
                </div>
                <button type="button" class="vym-trace-btn" onclick="openVymFormulaModal('actual_cash_outflow')">
                    <i class="fas fa-code-branch mr-1"></i> Formula &amp; Source Table
                </button>
            </div>
        </div>

        <!-- KPI 8: Operating Contribution & COGS Guardrail -->
        <?php $k8 = $kpis['profitability_guard'] ?? []; ?>
        <div class="vym-kpi-card" style="border-top: 3px solid #c084fc;">
            <div>
                <div class="vym-kpi-top">
                    <span class="vym-kpi-code"><?= htmlspecialchars($k8['code'] ?? 'VYM:PROFIT_GUARD') ?></span>
                    <span class="vym-class-badge vym-class-estimated"><?= htmlspecialchars($k8['classification'] ?? 'GUARDRAIL • COGS') ?></span>
                </div>
                <div class="vym-kpi-title">8. Operating Contribution (Excl. COGS)</div>
                <div class="vym-kpi-value" style="color: #c084fc;" id="kpi-prof-val">
                    Rp <?= number_format($k8['nominal'] ?? 0, 0, ',', '.') ?>
                </div>
            </div>
            <div class="vym-kpi-meta">
                <div class="vym-kpi-meta-row">
                    <span>Billed Coverage Ratio:</span>
                    <strong class="vym-mono text-white" id="kpi-prof-ratio"><?= $k8['coverage_ratio'] ?? 0 ?>x Outflow</strong>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>Status Data HPP/COGS:</span>
                    <span class="vym-mono" style="color: #fbbf24;"><?= htmlspecialchars($k8['cogs_status']['status_label'] ?? 'PARTIAL') ?></span>
                </div>
                <div class="vym-kpi-meta-row">
                    <span>Prinsip #5:</span>
                    <span style="color: #cbd5e1;">Tanpa Asumsi HPP Semu</span>
                </div>
                <button type="button" class="vym-trace-btn" onclick="openVymFormulaModal('profitability_guard')">
                    <i class="fas fa-code-branch mr-1"></i> Formula &amp; Source Table
                </button>
            </div>
        </div>
    </div>

    <!-- 5. MULTI-SERIES INSTITUTIONAL FINANCIAL TERMINAL CHART -->
    <div class="vym-panel">
        <div class="vym-panel-head">
            <h3 class="vym-panel-title">
                <i class="fas fa-chart-area text-info"></i>
                INSTITUTIONAL MULTI-LAYER TELEMETRY: ORDERS (PO) vs BILLED INVOICES vs SETTLED CASH OUTFLOW vs UNPAID OBLIGATIONS
            </h3>
            <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
                <div class="vym-btn-group" id="vym-series-mode-group">
                    <button type="button" class="vym-btn <?= ($default_series_mode === 'ALL_LAYERS') ? 'active' : '' ?>" data-mode="ALL_LAYERS">Semua Lapisan (4 Kurva)</button>
                    <button type="button" class="vym-btn <?= ($default_series_mode === 'ORDER_VS_INVOICE') ? 'active' : '' ?>" data-mode="ORDER_VS_INVOICE">Order (PO) vs Invoice</button>
                    <button type="button" class="vym-btn <?= ($default_series_mode === 'BILLED_VS_CASHOUT') ? 'active' : '' ?>" data-mode="BILLED_VS_CASHOUT">Billed AR vs Paid Outflow</button>
                    <button type="button" class="vym-btn <?= ($default_series_mode === 'OBLIGATIONS_VS_PAID') ? 'active' : '' ?>" data-mode="OBLIGATIONS_VS_PAID">Unpaid AP vs Paid Outflow</button>
                </div>
                <button type="button" class="vym-btn" id="vym-btn-fit-chart" style="border: 1px solid #334155;">
                    <i class="fas fa-compress-arrows-alt mr-1"></i> Fit Scale
                </button>
            </div>
        </div>
        <div class="vym-chart-container">
            <div class="vym-chart-legend" id="vym-chart-legend">
                <div style="font-weight: 800; color: #f8fafc; margin-bottom: 4px;" id="vym-leg-period">Arahkan kursor pada grafik</div>
                <div class="d-flex flex-wrap align-items-center" style="gap: 12px;">
                    <span><i class="fas fa-square mr-1" style="color:#38bdf8;"></i>Orders (PO): <strong class="vym-mono" id="vym-leg-ord">Rp 0</strong></span>
                    <span><i class="fas fa-square mr-1" style="color:#10b981;"></i>Billed (Inv): <strong class="vym-mono" id="vym-leg-inv">Rp 0</strong></span>
                    <span><i class="fas fa-square mr-1" style="color:#ef4444;"></i>Paid Cash Out: <strong class="vym-mono" id="vym-leg-out">Rp 0</strong></span>
                    <span><i class="fas fa-square mr-1" style="color:#fb923c;"></i>Unpaid AP: <strong class="vym-mono" id="vym-leg-obl">Rp 0</strong></span>
                </div>
            </div>
            <div id="vym-bi-tv-chart" style="width: 100%; height: 100%;"></div>
        </div>
    </div>

    <!-- =========================================================================
         5B. PHASE 3 — FINANCIAL INTELLIGENCE VALIDATED REPORTS SUITE
         Covers Submodules:
           [02] Financial Performance (Monthly Comparative Statement)
           [03] Revenue Analytics (Conversion Funnel, Payment Terms, Sales Attribution, Unbilled PO Backlog)
           [04] Profitability Analysis (Pre-COGS Operating Contribution Waterfall & Top Billed SKUs)
           [05] Cashflow Intelligence (Verified Inflow vs Outflow & AP Payables Aging Watchlist)
         ========================================================================= -->
    <?php
    $fi_monthly    = $fin_intel['monthly_statement'] ?? [];
    $fi_funnel     = $fin_intel['revenue_funnel'] ?? [];
    $fi_pay_terms  = $fin_intel['payment_terms_breakdown'] ?? [];
    $fi_sales_attr = $fin_intel['sales_attribution'] ?? [];
    $fi_unbilled   = $fin_intel['unbilled_orders_list'] ?? [];
    $fi_waterfall  = $fin_intel['profitability_waterfall'] ?? [];
    $fi_top_skus   = $fin_intel['top_billed_skus'] ?? [];
    $fi_payables   = $fin_intel['payables_watchlist'] ?? [];

    $default_fi_tab = 'FIN_PERF';
    if ($active_sub === 'revenue_analytics') {
        $default_fi_tab = 'REV_ANALYTICS';
    } elseif ($active_sub === 'profitability_analysis') {
        $default_fi_tab = 'PROFIT_ANALYSIS';
    } elseif ($active_sub === 'cashflow_intelligence') {
        $default_fi_tab = 'CASHFLOW_INTEL';
    } elseif ($active_sub === 'business_performance') {
        $default_fi_tab = 'BIZ_PERF';
    }

    $ba_cust = $biz_analytics['customer_intelligence'] ?? [];
    $ba_prod = $biz_analytics['product_intelligence'] ?? [];
    $ba_supp = $biz_analytics['supplier_logistics_intelligence'] ?? [];
    $ba_ops  = $biz_analytics['operational_intelligence'] ?? [];
    ?>
    <div class="vym-panel">
        <div class="vym-panel-head">
            <h3 class="vym-panel-title">
                <i class="fas fa-microchip text-info"></i>
                PHASE 3 &amp; 4 INTELLIGENCE DECK — FINANCIAL STATEMENTS &amp; BUSINESS ANALYTICS
            </h3>
            <div class="vym-btn-group" id="vym-fi-tab-group">
                <button type="button" class="vym-btn <?= ($default_fi_tab === 'FIN_PERF') ? 'active' : '' ?>" data-fitab="FIN_PERF">
                    [02] Financial Performance
                </button>
                <button type="button" class="vym-btn <?= ($default_fi_tab === 'REV_ANALYTICS') ? 'active' : '' ?>" data-fitab="REV_ANALYTICS">
                    [03] Revenue Analytics
                </button>
                <button type="button" class="vym-btn <?= ($default_fi_tab === 'PROFIT_ANALYSIS') ? 'active' : '' ?>" data-fitab="PROFIT_ANALYSIS">
                    [04] Profitability Analysis
                </button>
                <button type="button" class="vym-btn <?= ($default_fi_tab === 'CASHFLOW_INTEL') ? 'active' : '' ?>" data-fitab="CASHFLOW_INTEL">
                    [05] Cashflow Intelligence
                </button>
                <button type="button" class="vym-btn <?= ($default_fi_tab === 'BIZ_PERF') ? 'active' : '' ?>" data-fitab="BIZ_PERF">
                    [06] Business Performance
                </button>
                <button type="button" class="vym-btn" data-fitab="ALL_FI">
                    Tampilkan Semua
                </button>
            </div>
        </div>

        <div class="vym-panel-body">
            <!-- TAB [02]: FINANCIAL PERFORMANCE — MONTHLY COMPARATIVE STATEMENT -->
            <div class="vym-fi-pane" data-fipane="FIN_PERF" style="<?= ($default_fi_tab === 'FIN_PERF') ? '' : 'display:none;' ?>">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                    <div>
                        <strong style="color:#f8fafc; font-size:12.5px;">
                            <i class="fas fa-balance-scale text-info mr-1"></i>
                            [02] MONTHLY COMPARATIVE FINANCIAL PERFORMANCE STATEMENT (ORDERS vs BILLED vs SETTLED CASHFLOW)
                        </strong>
                        <div style="font-size:11px; color:#94a3b8;">
                            Rekonsiliasi bulanan tervalidasi antara PO Masuk (`po_masuk`), Penagihan Faktur (`invoice`), Kas Keluar Lunas (`tagihan_ekspedisi` + `salary_history`), dan Kewajiban Belum Bayar.
                        </div>
                    </div>
                    <span class="vym-class-badge vym-class-actual">ACTUAL • MULTI-TABLE RECONCILIATION</span>
                </div>
                <div class="table-responsive">
                    <table class="vym-table" id="vym-fi-monthly-table">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th class="text-right">Contracted Orders (PO)</th>
                                <th class="text-right">Issued Invoices (Gross)</th>
                                <th class="text-right">Net Billed DPP</th>
                                <th class="text-right">MoM Billing</th>
                                <th class="text-right">Verified DP/Cash</th>
                                <th class="text-right">Paid Freight</th>
                                <th class="text-right">Closed Payroll</th>
                                <th class="text-right">Unpaid AP</th>
                                <th class="text-right">Operating Contrib. (Excl. COGS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($fi_monthly)): ?>
                                <?php foreach ($fi_monthly as $m):
                                    $mom = $m['mom_billing_pct'];
                                    $mom_html = '<span class="text-muted">-</span>';
                                    if ($mom !== null) {
                                        $mom_col = ($mom >= 0) ? '#34d399' : '#f87171';
                                        $mom_sign = ($mom >= 0) ? '+' : '';
                                        $mom_html = '<span style="color:' . $mom_col . ';font-weight:700;">' . $mom_sign . $mom . '%</span>';
                                    }
                                    $oc = (float)$m['operating_contribution'];
                                ?>
                                    <tr>
                                        <td class="vym-mono font-weight-bold" style="color:#f8fafc;"><?= htmlspecialchars($m['label']) ?></td>
                                        <td class="text-right vym-mono" style="color:#38bdf8;">Rp <?= number_format($m['orders_gross'], 0, ',', '.') ?> <small class="text-muted">(<?= $m['orders_cnt'] ?>)</small></td>
                                        <td class="text-right vym-mono" style="color:#10b981;">Rp <?= number_format($m['invoices_gross'], 0, ',', '.') ?> <small class="text-muted">(<?= $m['invoices_cnt'] ?>)</small></td>
                                        <td class="text-right vym-mono">Rp <?= number_format($m['invoices_dpp'], 0, ',', '.') ?></td>
                                        <td class="text-right vym-mono"><?= $mom_html ?></td>
                                        <td class="text-right vym-mono" style="color:#2dd4bf;">Rp <?= number_format($m['verified_dp_cash'], 0, ',', '.') ?></td>
                                        <td class="text-right vym-mono" style="color:#f87171;">Rp <?= number_format($m['paid_freight'], 0, ',', '.') ?></td>
                                        <td class="text-right vym-mono" style="color:#fca5a5;">Rp <?= number_format($m['paid_payroll'], 0, ',', '.') ?></td>
                                        <td class="text-right vym-mono" style="color:#fb923c;">Rp <?= number_format($m['unpaid_obligations'], 0, ',', '.') ?></td>
                                        <td class="text-right vym-mono font-weight-bold" style="color:<?= ($oc >= 0) ? '#c084fc' : '#f87171' ?>;">
                                            Rp <?= number_format($oc, 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="10" class="text-center py-3 text-muted">Tidak ada data bulanan pada periode ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB [03]: REVENUE ANALYTICS — FUNNEL, PAYMENT TERMS, SALES ATTRIBUTION, UNBILLED PO BACKLOG -->
            <div class="vym-fi-pane" data-fipane="REV_ANALYTICS" style="<?= ($default_fi_tab === 'REV_ANALYTICS') ? '' : 'display:none;' ?>">
                <div class="row">
                    <!-- Funnel & Payment Terms -->
                    <div class="col-lg-6 col-12 mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <strong style="color:#f8fafc; font-size:12px;">
                                <i class="fas fa-filter text-info mr-1"></i>
                                [03A] ORDER-TO-BILLING-TO-TAX CONVERSION FUNNEL
                            </strong>
                            <span class="vym-class-badge vym-class-actual">ACTUAL • PIPELINE</span>
                        </div>
                        <div class="table-responsive mb-3">
                            <table class="vym-table" id="vym-fi-funnel-table">
                                <thead>
                                    <tr>
                                        <th>Tahap Konversi</th>
                                        <th>Klasifikasi</th>
                                        <th class="text-right">Dokumen</th>
                                        <th class="text-right">Nominal (IDR)</th>
                                        <th class="text-right">% thd PO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($fi_funnel as $f): ?>
                                        <tr>
                                            <td>
                                                <strong style="color:#f8fafc;"><?= htmlspecialchars($f['stage']) ?></strong>
                                                <div style="font-size:10px; color:#64748b;">Sumber: <code><?= htmlspecialchars($f['source']) ?></code></div>
                                            </td>
                                            <td><span class="vym-class-badge vym-class-actual"><?= htmlspecialchars($f['classification']) ?></span></td>
                                            <td class="text-right vym-mono"><?= number_format($f['doc_count'], 0, ',', '.') ?></td>
                                            <td class="text-right vym-mono font-weight-bold" style="color:#38bdf8;">Rp <?= number_format($f['nominal'], 0, ',', '.') ?></td>
                                            <td class="text-right vym-mono" style="color:#34d399;"><?= $f['pct_of_top'] ?>%</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <strong style="color:#f8fafc; font-size:12px;">
                                <i class="fas fa-credit-card text-success mr-1"></i>
                                [03B] ORDER BOOK BREAKDOWN BY PAYMENT TERMS (`po_masuk.sistem_pembayaran`)
                            </strong>
                        </div>
                        <div class="table-responsive">
                            <table class="vym-table" id="vym-fi-payterms-table">
                                <thead>
                                    <tr>
                                        <th>Sistem Pembayaran</th>
                                        <th class="text-right">Jumlah PO</th>
                                        <th class="text-right">Total Nilai PO</th>
                                        <th class="text-right">DP Terverifikasi</th>
                                        <th class="text-right">Porsi (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($fi_pay_terms)): ?>
                                        <?php foreach ($fi_pay_terms as $pt): ?>
                                            <tr>
                                                <td><strong style="color:#f8fafc;"><?= htmlspecialchars($pt['term']) ?></strong></td>
                                                <td class="text-right vym-mono"><?= number_format($pt['po_count'], 0, ',', '.') ?> PO</td>
                                                <td class="text-right vym-mono" style="color:#38bdf8;">Rp <?= number_format($pt['order_nominal'], 0, ',', '.') ?></td>
                                                <td class="text-right vym-mono" style="color:#2dd4bf;">Rp <?= number_format($pt['dp_nominal'], 0, ',', '.') ?></td>
                                                <td class="text-right vym-mono"><?= $pt['share_pct'] ?>%</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada data skema pembayaran.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sales Attribution & Unbilled Backlog Action List -->
                    <div class="col-lg-6 col-12 mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <strong style="color:#f8fafc; font-size:12px;">
                                <i class="fas fa-user-tie text-warning mr-1"></i>
                                [03C] SALES REPRESENTATIVE ATTRIBUTION (PO vs BILLED REALIZATION)
                            </strong>
                            <span class="vym-class-badge vym-class-actual">ACTUAL • SALES</span>
                        </div>
                        <div class="table-responsive mb-3">
                            <table class="vym-table" id="vym-fi-sales-table">
                                <thead>
                                    <tr>
                                        <th>Sales / Marketing</th>
                                        <th class="text-right">Orders (PO)</th>
                                        <th class="text-right">Issued Invoices</th>
                                        <th class="text-right">Rasio Tagih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($fi_sales_attr)): ?>
                                        <?php foreach ($fi_sales_attr as $sa): ?>
                                            <tr>
                                                <td><strong style="color:#f8fafc;"><?= htmlspecialchars($sa['sales_nama']) ?></strong></td>
                                                <td class="text-right vym-mono" style="color:#38bdf8;">Rp <?= number_format($sa['order_nominal'], 0, ',', '.') ?> <small class="text-muted">(<?= $sa['po_count'] ?>)</small></td>
                                                <td class="text-right vym-mono" style="color:#34d399;">Rp <?= number_format($sa['billed_nominal'], 0, ',', '.') ?> <small class="text-muted">(<?= $sa['inv_count'] ?>)</small></td>
                                                <td class="text-right vym-mono" style="color:#fbbf24;"><?= $sa['realization_pct'] ?>%</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4" class="text-center py-3 text-muted">Tidak ada data atribusi sales.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <strong style="color:#f8fafc; font-size:12px;">
                                <i class="fas fa-exclamation-circle text-warning mr-1"></i>
                                [03D] ACTIONABLE UNBILLED PO BACKLOG (PO BELUM TERBIT INVOICE)
                            </strong>
                        </div>
                        <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                            <table class="vym-table" id="vym-fi-unbilled-table">
                                <thead>
                                    <tr>
                                        <th>No. PO</th>
                                        <th>Tgl PO</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th class="text-right">Usia</th>
                                        <th class="text-right">Nominal PO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($fi_unbilled)): ?>
                                        <?php foreach ($fi_unbilled as $ub): ?>
                                            <tr>
                                                <td class="vym-mono font-weight-bold" style="color:#fbbf24;"><?= htmlspecialchars($ub['no_po']) ?></td>
                                                <td class="vym-mono"><?= date('d/m/Y', strtotime($ub['tanggal'])) ?></td>
                                                <td><?= htmlspecialchars($ub['customer_nama']) ?></td>
                                                <td class="vym-mono" style="font-size:10px;"><?= htmlspecialchars($ub['status_label']) ?></td>
                                                <td class="text-right vym-mono"><?= $ub['days_open'] ?> hr</td>
                                                <td class="text-right vym-mono font-weight-bold">Rp <?= number_format($ub['nominal'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center py-3 text-muted">Seluruh PO pada periode ini telah diterbitkan Invoice.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB [04]: PROFITABILITY ANALYSIS — OPERATING WATERFALL & TOP BILLED SKUs -->
            <div class="vym-fi-pane" data-fipane="PROFIT_ANALYSIS" style="<?= ($default_fi_tab === 'PROFIT_ANALYSIS') ? '' : 'display:none;' ?>">
                <div class="row">
                    <!-- Left: Validated Operating Contribution Waterfall -->
                    <div class="col-lg-6 col-12 mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <strong style="color:#f8fafc; font-size:12px;">
                                <i class="fas fa-percentage text-info mr-1"></i>
                                [04A] VALIDATED PRE-COGS OPERATING CONTRIBUTION WATERFALL
                            </strong>
                            <span class="vym-class-badge vym-class-estimated">SECTION 5 COGS GUARDRAIL</span>
                        </div>
                        <div class="table-responsive">
                            <table class="vym-table" id="vym-fi-waterfall-table">
                                <thead>
                                    <tr>
                                        <th>Komponen Struktur Kontribusi</th>
                                        <th>Rumus &amp; Sumber</th>
                                        <th class="text-right">Nominal (IDR)</th>
                                        <th class="text-right">% Gross Inv</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($fi_waterfall as $wf):
                                        $wf_col = '#e2e8f0';
                                        if ($wf['type'] === 'BASE') $wf_col = '#10b981';
                                        elseif ($wf['type'] === 'SUBTOTAL') $wf_col = '#38bdf8';
                                        elseif ($wf['type'] === 'DEDUCTION') $wf_col = '#f87171';
                                        elseif ($wf['type'] === 'RESULT') $wf_col = '#c084fc';
                                    ?>
                                        <tr>
                                            <td>
                                                <strong style="color:<?= $wf_col ?>;"><?= htmlspecialchars($wf['step']) ?></strong>
                                                <div><span class="vym-class-badge vym-class-actual"><?= htmlspecialchars($wf['classification']) ?></span></div>
                                            </td>
                                            <td class="vym-mono" style="font-size:10.5px; color:#94a3b8;"><?= htmlspecialchars($wf['formula']) ?></td>
                                            <td class="text-right vym-mono font-weight-bold" style="color:<?= $wf_col ?>;">
                                                Rp <?= number_format($wf['nominal'], 0, ',', '.') ?>
                                            </td>
                                            <td class="text-right vym-mono"><?= $wf['pct_of_gross'] ?>%</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Right: Top Billed Products / SKUs -->
                    <div class="col-lg-6 col-12 mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <strong style="color:#f8fafc; font-size:12px;">
                                <i class="fas fa-boxes text-success mr-1"></i>
                                [04B] TOP BILLED PRODUCTS / SKUs (`detail_barang_invoice`)
                            </strong>
                            <span class="vym-class-badge vym-class-actual">ACTUAL • SKU MIX</span>
                        </div>
                        <div class="table-responsive" style="max-height: 340px; overflow-y: auto;">
                            <table class="vym-table" id="vym-fi-sku-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Produk / Item Alat Kesehatan</th>
                                        <th class="text-right">Qty Terfaktur</th>
                                        <th class="text-right">Faktur</th>
                                        <th class="text-right">Total Nilai Terfaktur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($fi_top_skus)): ?>
                                        <?php foreach ($fi_top_skus as $s_idx => $sku): ?>
                                            <tr>
                                                <td class="vym-mono text-muted"><?= $s_idx + 1 ?></td>
                                                <td>
                                                    <strong style="color:#f8fafc;"><?= htmlspecialchars($sku['item_nama']) ?></strong>
                                                    <div style="font-size:10px; color:#64748b;">Sumber: <code><?= htmlspecialchars($sku['source']) ?></code></div>
                                                </td>
                                                <td class="text-right vym-mono"><?= number_format($sku['qty_billed'], 0, ',', '.') ?></td>
                                                <td class="text-right vym-mono"><?= number_format($sku['doc_count'], 0, ',', '.') ?></td>
                                                <td class="text-right vym-mono font-weight-bold" style="color:#34d399;">
                                                    Rp <?= number_format($sku['gross_billed'], 0, ',', '.') ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada rincian produk terfaktur pada periode ini.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB [05]: CASHFLOW INTELLIGENCE — UNPAID VENDOR/FREIGHT PAYABLES AGING WATCHLIST -->
            <div class="vym-fi-pane" data-fipane="CASHFLOW_INTEL" style="<?= ($default_fi_tab === 'CASHFLOW_INTEL') ? '' : 'display:none;' ?>">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                    <div>
                        <strong style="color:#f8fafc; font-size:12.5px;">
                            <i class="fas fa-money-bill-wave text-warning mr-1"></i>
                            [05] CASHFLOW INTELLIGENCE &amp; UNPAID VENDOR/FREIGHT PAYABLES AGING WATCHLIST (`tagihan_ekspedisi`)
                        </strong>
                        <div style="font-size:11px; color:#94a3b8;">
                            Daftar kewajiban vendor/ekspedisi aktual yang berstatus <code>belum_dibayar</code> diurutkan berdasarkan umur tagihan (aging days) untuk manajemen likuiditas kas keluar.
                        </div>
                    </div>
                    <span class="vym-class-badge vym-class-actual">ACTUAL • AP AGING WATCHLIST</span>
                </div>
                <div class="table-responsive">
                    <table class="vym-table" id="vym-fi-payables-table">
                        <thead>
                            <tr>
                                <th>No. Invoice Ekspedisi</th>
                                <th>Tgl Invoice</th>
                                <th>Vendor Ekspedisi</th>
                                <th>No. SJ / Surat Jalan</th>
                                <th>Status Approval</th>
                                <th>Bucket Umur</th>
                                <th class="text-right">Usia Tagihan</th>
                                <th class="text-right">Nominal Hutang (IDR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($fi_payables)): ?>
                                <?php foreach ($fi_payables as $ap):
                                    $b_col = ($ap['days_aging'] > 60) ? 'vym-badge-rose' : (($ap['days_aging'] > 30) ? 'vym-badge-amber' : 'vym-badge-cyan');
                                ?>
                                    <tr>
                                        <td class="vym-mono font-weight-bold" style="color:#f8fafc;"><?= htmlspecialchars($ap['nomor_invoice']) ?></td>
                                        <td class="vym-mono"><?= date('d/m/Y', strtotime($ap['tanggal_invoice'])) ?></td>
                                        <td><strong><?= htmlspecialchars($ap['nama_ekspedisi']) ?></strong></td>
                                        <td class="vym-mono text-muted"><?= htmlspecialchars($ap['nomor_sj']) ?></td>
                                        <td class="vym-mono"><?= htmlspecialchars($ap['status_approval']) ?></td>
                                        <td><span class="<?= $b_col ?>"><?= htmlspecialchars($ap['aging_bucket']) ?></span></td>
                                        <td class="text-right vym-mono font-weight-bold"><?= $ap['days_aging'] ?> Hari</td>
                                        <td class="text-right vym-mono font-weight-bold" style="color:#fb923c;">
                                            Rp <?= number_format($ap['nominal'], 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center py-3 text-muted">Tidak ada hutang tagihan ekspedisi yang belum dibayar pada periode ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB [06]: PHASE 4 BUSINESS PERFORMANCE — CUSTOMER HHI, PRODUCT MATRIX, SUPPLIERS & OPERATIONAL SLA -->
            <?php
            $c_sum = $ba_cust['summary'] ?? [];
            $c_mat = $ba_cust['matrix'] ?? [];
            $p_sum = $ba_prod['summary'] ?? [];
            $p_mat = $ba_prod['matrix'] ?? [];
            $f_vnd = $ba_supp['freight_vendors'] ?? [];
            $p_sup = $ba_supp['principal_suppliers']['rows'] ?? [];
            $p_st  = $ba_supp['principal_suppliers']['stats'] ?? [];
            $o_kpi = $ba_ops['kpis'] ?? [];
            $o_pip = $ba_ops['pipeline_breakdown'] ?? [];
            ?>
            <div class="vym-fi-pane" data-fipane="BIZ_PERF" style="<?= ($default_fi_tab === 'BIZ_PERF') ? '' : 'display:none;' ?>">
                <!-- 4 Operational & Concentration Summary Strip -->
                <div class="row mb-3">
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <div style="background:#08101e; border:1px solid #1e293b; border-left:3px solid #38bdf8; border-radius:6px; padding:10px 12px;">
                            <div style="font-size:10px; color:#94a3b8; font-weight:700; text-transform:uppercase;">Customer Concentration (HHI)</div>
                            <div class="vym-mono" style="font-size:16px; font-weight:800; color:#38bdf8;" id="ba-hhi-val">
                                HHI <?= number_format($c_sum['hhi_index'] ?? 0, 0, ',', '.') ?> • Top-3: <?= $c_sum['top3_share_pct'] ?? 0 ?>%
                            </div>
                            <div style="font-size:10.5px; color:#cbd5e1;" id="ba-hhi-tier">
                                <?= htmlspecialchars($c_sum['concentration_risk'] ?? '-') ?> (<?= $c_sum['active_customers'] ?? 0 ?> Entitas)
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <div style="background:#08101e; border:1px solid #1e293b; border-left:3px solid #10b981; border-radius:6px; padding:10px 12px;">
                            <div style="font-size:10px; color:#94a3b8; font-weight:700; text-transform:uppercase;">SKU &amp; Unit Fulfillment</div>
                            <div class="vym-mono" style="font-size:16px; font-weight:800; color:#10b981;" id="ba-sku-val">
                                <?= number_format($p_sum['distinct_skus_billed'] ?? 0, 0, ',', '.') ?> Billed / <?= number_format($p_sum['distinct_skus_ordered'] ?? 0, 0, ',', '.') ?> Ordered SKU
                            </div>
                            <div style="font-size:10.5px; color:#cbd5e1;" id="ba-sku-sub">
                                Realisasi Unit: <?= $p_sum['unit_fulfillment_pct'] ?? 0 ?>% (<?= number_format($p_sum['total_units_billed'] ?? 0, 0, ',', '.') ?> Unit)
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <div style="background:#08101e; border:1px solid #1e293b; border-left:3px solid #f59e0b; border-radius:6px; padding:10px 12px;">
                            <div style="font-size:10px; color:#94a3b8; font-weight:700; text-transform:uppercase;">Principal Suppliers &amp; Catalog</div>
                            <div class="vym-mono" style="font-size:16px; font-weight:800; color:#fbbf24;">
                                <?= number_format($p_st['active_suppliers'] ?? 0, 0, ',', '.') ?> Aktif / <?= number_format($p_st['total_suppliers'] ?? 0, 0, ',', '.') ?> Pemasok
                            </div>
                            <div style="font-size:10.5px; color:#cbd5e1;">
                                Katalog Harga: <?= number_format($p_st['price_catalog_docs'] ?? 0, 0, ',', '.') ?> Dok • LoA/Agrmt: <?= number_format($p_st['agreement_docs'] ?? 0, 0, ',', '.') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <div style="background:#08101e; border:1px solid #1e293b; border-left:3px solid #c084fc; border-radius:6px; padding:10px 12px;">
                            <div style="font-size:10px; color:#94a3b8; font-weight:700; text-transform:uppercase;">Operational Fulfillment SLA</div>
                            <div class="vym-mono" style="font-size:16px; font-weight:800; color:#c084fc;" id="ba-ops-val">
                                Lead Time: <?= $o_kpi['avg_po_to_invoice_days'] ?? 0 ?> Hari
                            </div>
                            <div style="font-size:10.5px; color:#cbd5e1;" id="ba-ops-sub">
                                Approval PO: <?= $o_kpi['order_approval_rate_pct'] ?? 0 ?>% • Dispatch Inv: <?= $o_kpi['invoice_dispatch_rate_pct'] ?? 0 ?>%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- [06A] Customer Pareto & Concentration Matrix -->
                    <div class="col-lg-6 col-12 mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <strong style="color:#f8fafc; font-size:12px;">
                                <i class="fas fa-hospital-user text-info mr-1"></i>
                                [06A] CUSTOMER PARETO, SHARE OF WALLET &amp; AOV MATRIX
                            </strong>
                            <span class="vym-class-badge vym-class-actual">ACTUAL • HHI PARETO</span>
                        </div>
                        <div class="table-responsive" style="max-height: 310px; overflow-y: auto;">
                            <table class="vym-table" id="vym-ba-cust-table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th class="text-right">Orders (PO)</th>
                                        <th class="text-right">Billed (Inv)</th>
                                        <th class="text-right">Porsi (%)</th>
                                        <th class="text-right">Rata2 Order (AOV)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($c_mat)): ?>
                                        <?php foreach ($c_mat as $cm): ?>
                                            <tr>
                                                <td>
                                                    <strong style="color:#f8fafc;"><?= htmlspecialchars($cm['customer_nama']) ?></strong>
                                                    <div style="font-size:10px; color:#64748b;">Realisasi Tagih: <?= $cm['realization_pct'] ?>% • Akhir: <?= htmlspecialchars($cm['last_tx_date']) ?></div>
                                                </td>
                                                <td class="text-right vym-mono" style="color:#38bdf8;">Rp <?= number_format($cm['order_nominal'], 0, ',', '.') ?> <small class="text-muted">(<?= $cm['order_count'] ?>)</small></td>
                                                <td class="text-right vym-mono" style="color:#34d399;">Rp <?= number_format($cm['inv_nominal'], 0, ',', '.') ?> <small class="text-muted">(<?= $cm['inv_count'] ?>)</small></td>
                                                <td class="text-right vym-mono font-weight-bold" style="color:#fbbf24;"><?= $cm['share_of_billed_pct'] ?>%</td>
                                                <td class="text-right vym-mono">Rp <?= number_format($cm['avg_order_val'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada data pelanggan pada periode ini.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- [06B] Product / SKU Commercial Velocity Matrix -->
                    <div class="col-lg-6 col-12 mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <strong style="color:#f8fafc; font-size:12px;">
                                <i class="fas fa-cubes text-success mr-1"></i>
                                [06B] PRODUCT / SKU ORDERED vs BILLED VELOCITY (`po_masuk_item` vs `detail_barang_invoice`)
                            </strong>
                            <span class="vym-class-badge vym-class-actual">ACTUAL • SKU ANALYTICS</span>
                        </div>
                        <div class="table-responsive" style="max-height: 310px; overflow-y: auto;">
                            <table class="vym-table" id="vym-ba-prod-table">
                                <thead>
                                    <tr>
                                        <th>Item / Alat Kesehatan</th>
                                        <th class="text-right">Qty PO</th>
                                        <th class="text-right">Qty Inv</th>
                                        <th class="text-right">Nilai Billed (Inv)</th>
                                        <th class="text-right">ASP (Harga Rata2)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($p_mat)): ?>
                                        <?php foreach ($p_mat as $pm): ?>
                                            <tr>
                                                <td>
                                                    <strong style="color:#f8fafc;"><?= htmlspecialchars($pm['nama_barang']) ?></strong>
                                                    <div style="font-size:10px; color:#64748b;">Kode: <code><?= htmlspecialchars($pm['kode_barang']) ?></code> • Fulfill: <?= $pm['fulfillment_pct'] ?>%</div>
                                                </td>
                                                <td class="text-right vym-mono" style="color:#38bdf8;"><?= number_format($pm['ordered_qty'], 0, ',', '.') ?></td>
                                                <td class="text-right vym-mono" style="color:#34d399;"><?= number_format($pm['billed_qty'], 0, ',', '.') ?></td>
                                                <td class="text-right vym-mono font-weight-bold" style="color:#10b981;">
                                                    Rp <?= number_format($pm['billed_nominal'] > 0 ? $pm['billed_nominal'] : $pm['ordered_nominal'], 0, ',', '.') ?>
                                                </td>
                                                <td class="text-right vym-mono">Rp <?= number_format($pm['avg_unit_price'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada rincian produk pada periode ini.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- [06C] Logistics / Freight Vendor Performance & Principal Suppliers -->
                    <div class="col-lg-6 col-12 mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <strong style="color:#f8fafc; font-size:12px;">
                                <i class="fas fa-truck-loading text-warning mr-1"></i>
                                [06C] LOGISTICS VENDOR SCORECARD (`tagihan_ekspedisi` &amp; `ekspedisi`)
                            </strong>
                            <span class="vym-class-badge vym-class-actual">ACTUAL • VENDOR SLA</span>
                        </div>
                        <div class="table-responsive mb-3" style="max-height: 220px; overflow-y: auto;">
                            <table class="vym-table" id="vym-ba-vendor-table">
                                <thead>
                                    <tr>
                                        <th>Vendor Ekspedisi</th>
                                        <th class="text-right">Dokumen</th>
                                        <th class="text-right">Paid (Lunas)</th>
                                        <th class="text-right">Unpaid (AP)</th>
                                        <th class="text-right">Rata2 / Kirim</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($f_vnd)): ?>
                                        <?php foreach ($f_vnd as $fv): ?>
                                            <tr>
                                                <td><strong style="color:#f8fafc;"><?= htmlspecialchars($fv['vendor_nama']) ?></strong></td>
                                                <td class="text-right vym-mono"><?= number_format($fv['doc_count'], 0, ',', '.') ?></td>
                                                <td class="text-right vym-mono" style="color:#34d399;">Rp <?= number_format($fv['paid_nominal'], 0, ',', '.') ?></td>
                                                <td class="text-right vym-mono" style="color:#fb923c;">Rp <?= number_format($fv['unpaid_nominal'], 0, ',', '.') ?></td>
                                                <td class="text-right vym-mono">Rp <?= number_format($fv['avg_shipment_cost'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada tagihan ekspedisi pada periode ini.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- [06D] Principal Suppliers & Operational Pipeline -->
                    <div class="col-lg-6 col-12 mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <strong style="color:#f8fafc; font-size:12px;">
                                <i class="fas fa-industry text-info mr-1"></i>
                                [06D] PRINCIPAL MEDICAL SUPPLIERS (`acc_pemasok`) &amp; OPERATIONAL PIPELINE
                            </strong>
                            <span class="vym-class-badge vym-class-actual">ACTUAL • SUPPLIER &amp; OPS</span>
                        </div>
                        <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                            <table class="vym-table" id="vym-ba-ops-table">
                                <thead>
                                    <tr>
                                        <th>Tahap Operasional / Pemasok Principal</th>
                                        <th>Sumber / Negara</th>
                                        <th class="text-right">Volume / Dokumen</th>
                                        <th class="text-right">Nilai / Katalog</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($o_pip as $op): ?>
                                        <tr>
                                            <td><strong style="color:#38bdf8;"><?= htmlspecialchars($op['stage']) ?></strong></td>
                                            <td><code><?= htmlspecialchars($op['source']) ?></code></td>
                                            <td class="text-right vym-mono"><?= number_format($op['doc_count'], 0, ',', '.') ?> Dok</td>
                                            <td class="text-right vym-mono font-weight-bold">Rp <?= number_format($op['nominal'], 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php foreach (array_slice($p_sup, 0, 8) as $ps): ?>
                                        <tr>
                                            <td><strong style="color:#f8fafc;">SUPPLIER: <?= htmlspecialchars($ps['nama_pemasok']) ?></strong></td>
                                            <td><?= htmlspecialchars($ps['negara']) ?> (<?= htmlspecialchars($ps['tipe_nama']) ?>)</td>
                                            <td class="text-right vym-mono"><?= (int)$ps['product_count'] ?> Produk</td>
                                            <td class="text-right vym-mono" style="color:#fbbf24;"><?= (int)$ps['harga_doc_count'] ?> Katalog Harga</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. TWO-COLUMN INSTITUTIONAL BREAKDOWN: TOP CUSTOMERS & ACCOUNTING INTEGRITY MATRIX -->
    <div class="row">
        <!-- Left: Top Customer Order vs Billed Realization -->
        <div class="col-lg-7 col-12">
            <div class="vym-panel">
                <div class="vym-panel-head">
                    <h3 class="vym-panel-title">
                        <i class="fas fa-building text-success"></i>
                        TOP CUSTOMER CONCENTRATION: CONTRACTED ORDERS (PO) vs BILLED INVOICES
                    </h3>
                    <span class="vym-class-badge vym-class-actual">ACTUAL • ENTITY ATTRIBUTION</span>
                </div>
                <div class="vym-panel-body p-0">
                    <div class="table-responsive">
                        <table class="vym-table" id="vym-customer-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Entitas Customer</th>
                                    <th class="text-right">Contracted Orders (PO)</th>
                                    <th class="text-right">Issued Invoices (Billed)</th>
                                    <th class="text-right">Unbilled Gap</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($top_customers)): ?>
                                    <?php foreach ($top_customers as $idx => $cust):
                                        $gap = max(0, $cust['order_nominal'] - $cust['inv_nominal']);
                                    ?>
                                        <tr>
                                            <td class="vym-mono text-muted"><?= $idx + 1 ?></td>
                                            <td>
                                                <strong style="color: #f8fafc;"><?= htmlspecialchars($cust['customer_nama']) ?></strong>
                                            </td>
                                            <td class="text-right vym-mono" style="color: #38bdf8;">
                                                Rp <?= number_format($cust['order_nominal'], 0, ',', '.') ?>
                                                <small class="text-muted">(<?= $cust['order_count'] ?>)</small>
                                            </td>
                                            <td class="text-right vym-mono" style="color: #34d399;">
                                                Rp <?= number_format($cust['inv_nominal'], 0, ',', '.') ?>
                                                <small class="text-muted">(<?= $cust['inv_count'] ?>)</small>
                                            </td>
                                            <td class="text-right vym-mono" style="color: <?= $gap > 0 ? '#fbbf24' : '#64748b' ?>;">
                                                Rp <?= number_format($gap, 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Tidak ada data transaksi pada rentang waktu ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Financial Data Governance & Audit Rules -->
        <div class="col-lg-5 col-12">
            <div class="vym-panel">
                <div class="vym-panel-head">
                    <h3 class="vym-panel-title">
                        <i class="fas fa-balance-scale-left text-warning"></i>
                        FINANCIAL DATA GOVERNANCE &amp; AUDIT DISCLOSURES
                    </h3>
                    <span class="vym-class-badge vym-class-estimated">SECTION 5 COMPLIANT</span>
                </div>
                <div class="vym-panel-body" style="font-size: 11.5px; line-height: 1.55; color: #cbd5e1;">
                    <div class="mb-2 pb-2" style="border-bottom: 1px solid #1e293b;">
                        <strong style="color: #38bdf8;"><i class="fas fa-check-circle mr-1"></i> 1. Customer PO ≠ Cash Inflow:</strong>
                        Seluruh angka pada <code>VYM:ORDER_BOOK</code> berasal dari <code>po_masuk</code> dan diklasifikasikan murni sebagai komitmen pesanan pelanggan, bukan uang kas masuk.
                    </div>
                    <div class="mb-2 pb-2" style="border-bottom: 1px solid #1e293b;">
                        <strong style="color: #34d399;"><i class="fas fa-check-circle mr-1"></i> 2. Issued Invoice ≠ Completed Payment:</strong>
                        Faktur pada tabel <code>invoice</code> diklasifikasikan sebagai <em>Billed Accounts Receivable</em> (Piutang Usaha). Karena belum ada tabel jurnal pelunasan bank per invoice, sistem tidak mengklaim invoice sebagai kas lunas.
                    </div>
                    <div class="mb-2 pb-2" style="border-bottom: 1px solid #1e293b;">
                        <strong style="color: #fb923c;"><i class="fas fa-check-circle mr-1"></i> 3. Obligations vs Settled Cash Expenditures:</strong>
                        Tagihan vendor/ekspedisi di <code>tagihan_ekspedisi</code> dipisahkan tegas antara <code>belum_dibayar</code> (Hutang Operasional / AP) dan <code>sudah_dibayar</code> (Kas Keluar Aktual + Payroll <code>salary_history</code>).
                    </div>
                    <div>
                        <strong style="color: #c084fc;"><i class="fas fa-shield-alt mr-1"></i> 4. COGS &amp; Profit Guardrail:</strong>
                        <?= htmlspecialchars($kpis['profitability_guard']['cogs_status']['explanation'] ?? '') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 7. UNIFIED CROSS-TABLE INSTITUTIONAL AUDIT LEDGER -->
    <div class="vym-panel mb-0">
        <div class="vym-panel-head">
            <h3 class="vym-panel-title">
                <i class="fas fa-list-alt text-info"></i>
                UNIFIED INSTITUTIONAL TRANSACTION LEDGER (TRACEABLE SOURCE TABLES)
            </h3>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <input type="text" id="vym-ledger-search" class="vym-date-input" style="width: 210px;" placeholder="Cari No. Dokumen / Entitas...">
            </div>
        </div>
        <div class="vym-panel-body p-0">
            <div class="table-responsive">
                <table class="vym-table" id="vym-ledger-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nomor Dokumen</th>
                            <th>Instrumen Keuangan</th>
                            <th>Klasifikasi Data</th>
                            <th>Entitas (Customer / Vendor / SDM)</th>
                            <th>Status</th>
                            <th>Tabel Sumber</th>
                            <th>Dampak Arus Kas</th>
                            <th class="text-right">Nominal (IDR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_ledger)): ?>
                            <?php foreach ($recent_ledger as $row):
                                $b_cls = 'vym-badge-' . ($row['badge_color'] ?? 'cyan');
                            ?>
                                <tr>
                                    <td class="vym-mono"><?= date('d/m/Y', strtotime($row['tgl'])) ?></td>
                                    <td class="vym-mono font-weight-bold" style="color: #f8fafc;"><?= htmlspecialchars($row['kode_dok']) ?></td>
                                    <td><span class="<?= $b_cls ?>"><?= htmlspecialchars($row['kategori']) ?></span></td>
                                    <td><span class="vym-class-badge vym-class-actual"><?= htmlspecialchars($row['klasifikasi']) ?></span></td>
                                    <td><?= htmlspecialchars($row['entitas']) ?></td>
                                    <td class="vym-mono" style="font-size: 10.5px;"><?= htmlspecialchars($row['status_dok']) ?></td>
                                    <td><code><?= htmlspecialchars($row['sumber_tabel']) ?></code></td>
                                    <td class="vym-mono" style="font-size: 10.5px; color: #94a3b8;"><?= htmlspecialchars($row['arah_kas']) ?></td>
                                    <td class="text-right vym-mono font-weight-bold">
                                        Rp <?= number_format($row['nominal'], 0, ',', '.') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Belum ada catatan transaksi pada periode ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: KPI FORMULA & SOURCE TRACEABILITY -->
<div class="modal fade" id="vymFormulaModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background: #0b1322; color: #e2e8f0; border: 1px solid #38bdf8; border-radius: 10px;">
            <div class="modal-header" style="background: #070d19; border-bottom: 1px solid #1e293b; padding: 12px 16px;">
                <h5 class="modal-title vym-mono" style="font-size: 14px; font-weight: 800; color: #38bdf8;" id="vym-modal-code">VYM:KPI_TRACE</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 16px; font-size: 12.5px;">
                <div class="mb-3">
                    <div class="text-muted" style="font-size: 10.5px; text-transform: uppercase; font-weight: 700;">Nama Indikator KPI</div>
                    <div style="font-size: 15px; font-weight: 800; color: #ffffff;" id="vym-modal-title">-</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted" style="font-size: 10.5px; text-transform: uppercase; font-weight: 700;">Klasifikasi Data Akuntansi</div>
                    <div class="mt-1"><span class="vym-class-badge vym-class-actual" id="vym-modal-class">-</span></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted" style="font-size: 10.5px; text-transform: uppercase; font-weight: 700;">Tabel Sumber Database (Traceable Tables)</div>
                    <div class="vym-mono mt-1 p-2" style="background: #060b14; border: 1px solid #1e293b; border-radius: 6px; color: #34d399;" id="vym-modal-tables">-</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted" style="font-size: 10.5px; text-transform: uppercase; font-weight: 700;">Rumus Kalkulasi Validasi (SQL / Query Logic)</div>
                    <div class="vym-mono mt-1 p-2" style="background: #060b14; border: 1px solid #1e293b; border-radius: 6px; color: #fbbf24; font-size: 11.5px; word-break: break-word;" id="vym-modal-formula">-</div>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 10.5px; text-transform: uppercase; font-weight: 700;">Prinsip Akuntansi &amp; Interpretasi</div>
                    <div class="mt-1" style="color: #cbd5e1; line-height: 1.5;" id="vym-modal-note">-</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var vymBiState = {
        preset: <?= json_encode($bi_filter['preset'] ?? 'YTD') ?>,
        startDate: <?= json_encode($bi_filter['start_date'] ?? date('Y-01-01')) ?>,
        endDate: <?= json_encode($bi_filter['end_date'] ?? date('Y-m-d')) ?>,
        interval: <?= json_encode($bi_interval ?? '1M') ?>,
        seriesMode: <?= json_encode($default_series_mode) ?>,
        data: <?= json_encode($bi_initial_data) ?>
    };

    function openVymFormulaModal(kpiKey) {
        if (!vymBiState.data || !vymBiState.data.kpis || !vymBiState.data.kpis[kpiKey]) return;
        var k = vymBiState.data.kpis[kpiKey];
        document.getElementById('vym-modal-code').textContent = k.code || 'VYM:KPI';
        document.getElementById('vym-modal-title').textContent = k.title || '-';
        document.getElementById('vym-modal-class').textContent = k.classification || 'ACTUAL';
        document.getElementById('vym-modal-tables').textContent = k.source_tables || '-';
        document.getElementById('vym-modal-formula').textContent = k.formula || '-';
        document.getElementById('vym-modal-note').textContent = k.accounting_note || '-';
        if (window.jQuery) {
            window.jQuery('#vymFormulaModal').modal('show');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var $ = window.jQuery;
        var chartContainer = document.getElementById('vym-bi-tv-chart');
        var tvChart = null;
        var activeSeries = [];

        function fmtRpShort(val) {
            var n = parseFloat(val) || 0;
            var sign = n < 0 ? '-' : '';
            var abs = Math.abs(n);
            if (abs >= 1e9) return sign + 'Rp ' + (abs / 1e9).toFixed(2).replace('.', ',') + ' M';
            if (abs >= 1e6) return sign + 'Rp ' + (abs / 1e6).toFixed(1).replace('.', ',') + ' Jt';
            if (abs >= 1e3) return sign + 'Rp ' + (abs / 1e3).toFixed(0) + ' Rb';
            return sign + 'Rp ' + Math.round(abs).toLocaleString('id-ID');
        }

        function fmtRpFull(val) {
            var n = Math.round(parseFloat(val) || 0);
            var sign = n < 0 ? '-' : '';
            return sign + 'Rp ' + Math.abs(n).toLocaleString('id-ID');
        }

        function initVymChart() {
            if (!chartContainer || typeof LightweightCharts === 'undefined') return;
            if (tvChart) {
                tvChart.remove();
                tvChart = null;
            }

            tvChart = LightweightCharts.createChart(chartContainer, {
                width: chartContainer.clientWidth,
                height: chartContainer.clientHeight || 390,
                layout: {
                    background: { type: 'solid', color: '#070d19' },
                    textColor: '#94a3b8',
                    fontFamily: "'Consolas', 'Inter', monospace",
                    fontSize: 11
                },
                grid: {
                    vertLines: { color: 'rgba(30, 41, 59, 0.65)', style: 1 },
                    horzLines: { color: 'rgba(30, 41, 59, 0.65)', style: 1 }
                },
                crosshair: {
                    mode: LightweightCharts.CrosshairMode.Normal
                },
                rightPriceScale: {
                    borderColor: '#1e293b',
                    scaleMargins: { top: 0.14, bottom: 0.22 }
                },
                timeScale: {
                    borderColor: '#1e293b',
                    rightOffset: 3,
                    barSpacing: 26
                },
                localization: {
                    priceFormatter: function(p) { return fmtRpShort(p); }
                }
            });

            tvChart.subscribeCrosshairMove(function(param) {
                if (!vymBiState.data || !vymBiState.data.time_series) return;
                var metaMap = vymBiState.data.time_series.meta || {};
                if (!param || !param.time) return;
                var dKey = typeof param.time === 'string'
                    ? param.time
                    : (param.time.year + '-' + String(param.time.month).padStart(2, '0') + '-' + String(param.time.day).padStart(2, '0'));
                var m = metaMap[dKey];
                if (m) {
                    $('#vym-leg-period').text('Periode: ' + dKey + ' (' + (m.vol_cnt || 0) + ' Dokumen)');
                    $('#vym-leg-ord').text(fmtRpShort(m.order_val || 0));
                    $('#vym-leg-inv').text(fmtRpShort(m.invoice_val || 0));
                    $('#vym-leg-out').text(fmtRpShort(m.cash_out_val || 0));
                    $('#vym-leg-obl').text(fmtRpShort(m.obligation_val || 0));
                }
            });
        }

        function renderVymSeries() {
            if (!tvChart || !vymBiState.data || !vymBiState.data.time_series) return;
            for (var i = 0; i < activeSeries.length; i++) {
                try { tvChart.removeSeries(activeSeries[i]); } catch (e) {}
            }
            activeSeries = [];

            var ts = vymBiState.data.time_series;
            var mode = vymBiState.seriesMode;

            if (mode === 'ALL_LAYERS' || mode === 'ORDER_VS_INVOICE') {
                var sOrd = tvChart.addAreaSeries({
                    lineColor: '#38bdf8',
                    topColor: 'rgba(56, 189, 248, 0.25)',
                    bottomColor: 'rgba(56, 189, 248, 0.01)',
                    lineWidth: 2,
                    title: 'Orders (PO)'
                });
                sOrd.setData(ts.orders || []);
                activeSeries.push(sOrd);
            }

            if (mode === 'ALL_LAYERS' || mode === 'ORDER_VS_INVOICE' || mode === 'BILLED_VS_CASHOUT') {
                var sInv = tvChart.addLineSeries({
                    color: '#10b981',
                    lineWidth: 3,
                    title: 'Issued Invoices (AR)'
                });
                sInv.setData(ts.invoices || []);
                activeSeries.push(sInv);
            }

            if (mode === 'ALL_LAYERS' || mode === 'BILLED_VS_CASHOUT' || mode === 'OBLIGATIONS_VS_PAID') {
                var sOut = tvChart.addLineSeries({
                    color: '#ef4444',
                    lineWidth: 2,
                    title: 'Settled Cash Out'
                });
                sOut.setData(ts.cash_out || []);
                activeSeries.push(sOut);
            }

            if (mode === 'ALL_LAYERS' || mode === 'OBLIGATIONS_VS_PAID') {
                var sObl = tvChart.addLineSeries({
                    color: '#fb923c',
                    lineWidth: 2,
                    lineStyle: LightweightCharts.LineStyle.Dashed,
                    title: 'Unpaid Obligations (AP)'
                });
                sObl.setData(ts.obligations || []);
                activeSeries.push(sObl);
            }

            var sVol = tvChart.addHistogramSeries({
                priceFormat: { type: 'volume' },
                priceScaleId: 'vol_scale',
                lastValueVisible: false,
                priceLineVisible: false
            });
            tvChart.priceScale('vol_scale').applyOptions({
                scaleMargins: { top: 0.82, bottom: 0 }
            });
            sVol.setData(ts.volume || []);
            activeSeries.push(sVol);

            tvChart.timeScale().fitContent();
        }

        function updateUiFromData(resp) {
            vymBiState.data = resp;
            if (resp.resolved_filter) {
                $('#vym-active-period-label').text(resp.resolved_filter.label);
                $('#vym-start-date').val(resp.resolved_filter.start_date);
                $('#vym-end-date').val(resp.resolved_filter.end_date);
            }

            var k = resp.kpis || {};
            if (k.customer_orders) {
                $('#kpi-order-val').text(fmtRpFull(k.customer_orders.nominal));
                $('#kpi-order-cnt').text(k.customer_orders.count + ' PO');
                $('#kpi-order-split').text(k.customer_orders.approved_count + ' Disetujui / ' + k.customer_orders.pending_count + ' Proses');
            }
            if (k.issued_invoices) {
                $('#kpi-inv-val').text(fmtRpFull(k.issued_invoices.nominal));
                $('#kpi-inv-cnt').text(k.issued_invoices.count + ' Invoice');
                $('#kpi-inv-dpp').text(fmtRpFull(k.issued_invoices.net_dpp));
                $('#kpi-inv-conv').text(k.issued_invoices.conversion_pct + '%');
            }
            if (k.unbilled_backlog) {
                $('#kpi-unbilled-val').text(fmtRpFull(k.unbilled_backlog.nominal));
                $('#kpi-unbilled-cnt').text(k.unbilled_backlog.count + ' PO');
                $('#kpi-billed-cnt').text(k.unbilled_backlog.billed_po_cnt + ' PO');
            }
            if (k.recognized_tax) {
                $('#kpi-tax-val').text(fmtRpFull(k.recognized_tax.nominal));
                $('#kpi-tax-dpp').text(fmtRpFull(k.recognized_tax.dpp_nominal));
                $('#kpi-tax-ppn').text(fmtRpFull(k.recognized_tax.ppn_nominal));
                $('#kpi-tax-cnt').text(k.recognized_tax.approved_count + ' Appr / ' + k.recognized_tax.pending_count + ' Pending');
            }
            if (k.verified_customer_cash) {
                $('#kpi-cashin-val').text(fmtRpFull(k.verified_customer_cash.nominal));
                $('#kpi-cashin-dp').text(fmtRpFull(k.verified_customer_cash.dp_nominal));
                $('#kpi-cashin-cod').text(fmtRpFull(k.verified_customer_cash.cash_po_nominal));
            }
            if (k.supplier_obligations) {
                $('#kpi-obl-val').text(fmtRpFull(k.supplier_obligations.nominal));
                $('#kpi-obl-appr').text(fmtRpFull(k.supplier_obligations.approved_unpaid));
                $('#kpi-obl-wait').text(fmtRpFull(k.supplier_obligations.waiting_review));
                $('#kpi-obl-overdue').text(k.supplier_obligations.overdue_count + ' Dokumen');
            }
            if (k.actual_cash_outflow) {
                $('#kpi-out-val').text(fmtRpFull(k.actual_cash_outflow.nominal));
                $('#kpi-out-freight').text(fmtRpFull(k.actual_cash_outflow.paid_freight));
                $('#kpi-out-payroll').text(fmtRpFull(k.actual_cash_outflow.paid_payroll));
                $('#kpi-out-cnt').text(k.actual_cash_outflow.paid_freight_cnt + ' Tagihan + ' + k.actual_cash_outflow.payroll_slips + ' Slip');
            }
            if (k.profitability_guard) {
                $('#kpi-prof-val').text(fmtRpFull(k.profitability_guard.nominal));
                $('#kpi-prof-ratio').text(k.profitability_guard.coverage_ratio + 'x Outflow');
            }

            // Rebuild Top Customers Table
            var custHtml = '';
            var custs = resp.top_customers || [];
            if (custs.length > 0) {
                for (var c = 0; c < custs.length; c++) {
                    var row = custs[c];
                    var gap = Math.max(0, (parseFloat(row.order_nominal) || 0) - (parseFloat(row.inv_nominal) || 0));
                    custHtml += '<tr>' +
                        '<td class="vym-mono text-muted">' + (c + 1) + '</td>' +
                        '<td><strong style="color:#f8fafc;">' + $('<div>').text(row.customer_nama).html() + '</strong></td>' +
                        '<td class="text-right vym-mono" style="color:#38bdf8;">' + fmtRpFull(row.order_nominal) + ' <small class="text-muted">(' + row.order_count + ')</small></td>' +
                        '<td class="text-right vym-mono" style="color:#34d399;">' + fmtRpFull(row.inv_nominal) + ' <small class="text-muted">(' + row.inv_count + ')</small></td>' +
                        '<td class="text-right vym-mono" style="color:' + (gap > 0 ? '#fbbf24' : '#64748b') + ';">' + fmtRpFull(gap) + '</td>' +
                        '</tr>';
                }
            } else {
                custHtml = '<tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada data transaksi pada rentang waktu ini.</td></tr>';
            }
            $('#vym-customer-table tbody').html(custHtml);

            // Rebuild Unified Audit Ledger
            var ledHtml = '';
            var led = resp.recent_ledger || [];
            if (led.length > 0) {
                for (var l = 0; l < led.length; l++) {
                    var lr = led[l];
                    var bCls = 'vym-badge-' + (lr.badge_color || 'cyan');
                    ledHtml += '<tr>' +
                        '<td class="vym-mono">' + $('<div>').text(lr.tgl).html() + '</td>' +
                        '<td class="vym-mono font-weight-bold" style="color:#f8fafc;">' + $('<div>').text(lr.kode_dok).html() + '</td>' +
                        '<td><span class="' + bCls + '">' + $('<div>').text(lr.kategori).html() + '</span></td>' +
                        '<td><span class="vym-class-badge vym-class-actual">' + $('<div>').text(lr.klasifikasi).html() + '</span></td>' +
                        '<td>' + $('<div>').text(lr.entitas).html() + '</td>' +
                        '<td class="vym-mono" style="font-size:10.5px;">' + $('<div>').text(lr.status_dok).html() + '</td>' +
                        '<td><code>' + $('<div>').text(lr.sumber_tabel).html() + '</code></td>' +
                        '<td class="vym-mono" style="font-size:10.5px; color:#94a3b8;">' + $('<div>').text(lr.arah_kas).html() + '</td>' +
                        '<td class="text-right vym-mono font-weight-bold">' + fmtRpFull(lr.nominal) + '</td>' +
                        '</tr>';
                }
            } else {
                ledHtml = '<tr><td colspan="9" class="text-center py-4 text-muted">Belum ada catatan transaksi pada periode ini.</td></tr>';
            }
            $('#vym-ledger-table tbody').html(ledHtml);

            // Rebuild Phase 3 Financial Intelligence Tables
            var fi = resp.financial_intelligence || {};

            // [02] Monthly Statement
            var ms = fi.monthly_statement || [];
            var msHtml = '';
            if (ms.length > 0) {
                for (var mIdx = 0; mIdx < ms.length; mIdx++) {
                    var mRow = ms[mIdx];
                    var momVal = mRow.mom_billing_pct;
                    var momHtml = '<span class="text-muted">-</span>';
                    if (momVal !== null && typeof momVal !== 'undefined') {
                        var momCol = (momVal >= 0) ? '#34d399' : '#f87171';
                        var momSign = (momVal >= 0) ? '+' : '';
                        momHtml = '<span style="color:' + momCol + ';font-weight:700;">' + momSign + momVal + '%</span>';
                    }
                    var ocVal = parseFloat(mRow.operating_contribution) || 0;
                    msHtml += '<tr>' +
                        '<td class="vym-mono font-weight-bold" style="color:#f8fafc;">' + $('<div>').text(mRow.label).html() + '</td>' +
                        '<td class="text-right vym-mono" style="color:#38bdf8;">' + fmtRpFull(mRow.orders_gross) + ' <small class="text-muted">(' + mRow.orders_cnt + ')</small></td>' +
                        '<td class="text-right vym-mono" style="color:#10b981;">' + fmtRpFull(mRow.invoices_gross) + ' <small class="text-muted">(' + mRow.invoices_cnt + ')</small></td>' +
                        '<td class="text-right vym-mono">' + fmtRpFull(mRow.invoices_dpp) + '</td>' +
                        '<td class="text-right vym-mono">' + momHtml + '</td>' +
                        '<td class="text-right vym-mono" style="color:#2dd4bf;">' + fmtRpFull(mRow.verified_dp_cash) + '</td>' +
                        '<td class="text-right vym-mono" style="color:#f87171;">' + fmtRpFull(mRow.paid_freight) + '</td>' +
                        '<td class="text-right vym-mono" style="color:#fca5a5;">' + fmtRpFull(mRow.paid_payroll) + '</td>' +
                        '<td class="text-right vym-mono" style="color:#fb923c;">' + fmtRpFull(mRow.unpaid_obligations) + '</td>' +
                        '<td class="text-right vym-mono font-weight-bold" style="color:' + (ocVal >= 0 ? '#c084fc' : '#f87171') + ';">' + fmtRpFull(ocVal) + '</td>' +
                        '</tr>';
                }
            } else {
                msHtml = '<tr><td colspan="10" class="text-center py-3 text-muted">Tidak ada data bulanan pada periode ini.</td></tr>';
            }
            $('#vym-fi-monthly-table tbody').html(msHtml);

            // [03A] Revenue Funnel
            var rf = fi.revenue_funnel || [];
            var rfHtml = '';
            for (var rIdx = 0; rIdx < rf.length; rIdx++) {
                var fRow = rf[rIdx];
                rfHtml += '<tr>' +
                    '<td><strong style="color:#f8fafc;">' + $('<div>').text(fRow.stage).html() + '</strong><div style="font-size:10px; color:#64748b;">Sumber: <code>' + $('<div>').text(fRow.source).html() + '</code></div></td>' +
                    '<td><span class="vym-class-badge vym-class-actual">' + $('<div>').text(fRow.classification).html() + '</span></td>' +
                    '<td class="text-right vym-mono">' + fRow.doc_count + '</td>' +
                    '<td class="text-right vym-mono font-weight-bold" style="color:#38bdf8;">' + fmtRpFull(fRow.nominal) + '</td>' +
                    '<td class="text-right vym-mono" style="color:#34d399;">' + fRow.pct_of_top + '%</td>' +
                    '</tr>';
            }
            $('#vym-fi-funnel-table tbody').html(rfHtml);

            // [03B] Payment Terms
            var pt = fi.payment_terms_breakdown || [];
            var ptHtml = '';
            if (pt.length > 0) {
                for (var pIdx = 0; pIdx < pt.length; pIdx++) {
                    var pRow = pt[pIdx];
                    ptHtml += '<tr>' +
                        '<td><strong style="color:#f8fafc;">' + $('<div>').text(pRow.term).html() + '</strong></td>' +
                        '<td class="text-right vym-mono">' + pRow.po_count + ' PO</td>' +
                        '<td class="text-right vym-mono" style="color:#38bdf8;">' + fmtRpFull(pRow.order_nominal) + '</td>' +
                        '<td class="text-right vym-mono" style="color:#2dd4bf;">' + fmtRpFull(pRow.dp_nominal) + '</td>' +
                        '<td class="text-right vym-mono">' + pRow.share_pct + '%</td>' +
                        '</tr>';
                }
            } else {
                ptHtml = '<tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada data skema pembayaran.</td></tr>';
            }
            $('#vym-fi-payterms-table tbody').html(ptHtml);

            // [03C] Sales Attribution
            var sa = fi.sales_attribution || [];
            var saHtml = '';
            if (sa.length > 0) {
                for (var sIdx = 0; sIdx < sa.length; sIdx++) {
                    var sRow = sa[sIdx];
                    saHtml += '<tr>' +
                        '<td><strong style="color:#f8fafc;">' + $('<div>').text(sRow.sales_nama).html() + '</strong></td>' +
                        '<td class="text-right vym-mono" style="color:#38bdf8;">' + fmtRpFull(sRow.order_nominal) + ' <small class="text-muted">(' + sRow.po_count + ')</small></td>' +
                        '<td class="text-right vym-mono" style="color:#34d399;">' + fmtRpFull(sRow.billed_nominal) + ' <small class="text-muted">(' + sRow.inv_count + ')</small></td>' +
                        '<td class="text-right vym-mono" style="color:#fbbf24;">' + sRow.realization_pct + '%</td>' +
                        '</tr>';
                }
            } else {
                saHtml = '<tr><td colspan="4" class="text-center py-3 text-muted">Tidak ada data atribusi sales.</td></tr>';
            }
            $('#vym-fi-sales-table tbody').html(saHtml);

            // [03D] Unbilled PO Backlog
            var ub = fi.unbilled_orders_list || [];
            var ubHtml = '';
            if (ub.length > 0) {
                for (var uIdx = 0; uIdx < ub.length; uIdx++) {
                    var uRow = ub[uIdx];
                    ubHtml += '<tr>' +
                        '<td class="vym-mono font-weight-bold" style="color:#fbbf24;">' + $('<div>').text(uRow.no_po).html() + '</td>' +
                        '<td class="vym-mono">' + $('<div>').text(uRow.tanggal).html() + '</td>' +
                        '<td>' + $('<div>').text(uRow.customer_nama).html() + '</td>' +
                        '<td class="vym-mono" style="font-size:10px;">' + $('<div>').text(uRow.status_label).html() + '</td>' +
                        '<td class="text-right vym-mono">' + uRow.days_open + ' hr</td>' +
                        '<td class="text-right vym-mono font-weight-bold">' + fmtRpFull(uRow.nominal) + '</td>' +
                        '</tr>';
                }
            } else {
                ubHtml = '<tr><td colspan="6" class="text-center py-3 text-muted">Seluruh PO pada periode ini telah diterbitkan Invoice.</td></tr>';
            }
            $('#vym-fi-unbilled-table tbody').html(ubHtml);

            // [04A] Profitability Waterfall
            var wf = fi.profitability_waterfall || [];
            var wfHtml = '';
            for (var wIdx = 0; wIdx < wf.length; wIdx++) {
                var wRow = wf[wIdx];
                var wfCol = '#e2e8f0';
                if (wRow.type === 'BASE') wfCol = '#10b981';
                else if (wRow.type === 'SUBTOTAL') wfCol = '#38bdf8';
                else if (wRow.type === 'DEDUCTION') wfCol = '#f87171';
                else if (wRow.type === 'RESULT') wfCol = '#c084fc';
                wfHtml += '<tr>' +
                    '<td><strong style="color:' + wfCol + ';">' + $('<div>').text(wRow.step).html() + '</strong><div><span class="vym-class-badge vym-class-actual">' + $('<div>').text(wRow.classification).html() + '</span></div></td>' +
                    '<td class="vym-mono" style="font-size:10.5px; color:#94a3b8;">' + $('<div>').text(wRow.formula).html() + '</td>' +
                    '<td class="text-right vym-mono font-weight-bold" style="color:' + wfCol + ';">' + fmtRpFull(wRow.nominal) + '</td>' +
                    '<td class="text-right vym-mono">' + wRow.pct_of_gross + '%</td>' +
                    '</tr>';
            }
            $('#vym-fi-waterfall-table tbody').html(wfHtml);

            // [04B] Top Billed SKUs
            var skus = fi.top_billed_skus || [];
            var skuHtml = '';
            if (skus.length > 0) {
                for (var kIdx = 0; kIdx < skus.length; kIdx++) {
                    var kRow = skus[kIdx];
                    skuHtml += '<tr>' +
                        '<td class="vym-mono text-muted">' + (kIdx + 1) + '</td>' +
                        '<td><strong style="color:#f8fafc;">' + $('<div>').text(kRow.item_nama).html() + '</strong><div style="font-size:10px; color:#64748b;">Sumber: <code>' + $('<div>').text(kRow.source).html() + '</code></div></td>' +
                        '<td class="text-right vym-mono">' + Math.round(kRow.qty_billed).toLocaleString('id-ID') + '</td>' +
                        '<td class="text-right vym-mono">' + kRow.doc_count + '</td>' +
                        '<td class="text-right vym-mono font-weight-bold" style="color:#34d399;">' + fmtRpFull(kRow.gross_billed) + '</td>' +
                        '</tr>';
                }
            } else {
                skuHtml = '<tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada rincian produk terfaktur pada periode ini.</td></tr>';
            }
            $('#vym-fi-sku-table tbody').html(skuHtml);

            // [05] Payables Watchlist
            var ap = fi.payables_watchlist || [];
            var apHtml = '';
            if (ap.length > 0) {
                for (var aIdx = 0; aIdx < ap.length; aIdx++) {
                    var aRow = ap[aIdx];
                    var bCol = (aRow.days_aging > 60) ? 'vym-badge-rose' : ((aRow.days_aging > 30) ? 'vym-badge-amber' : 'vym-badge-cyan');
                    apHtml += '<tr>' +
                        '<td class="vym-mono font-weight-bold" style="color:#f8fafc;">' + $('<div>').text(aRow.nomor_invoice).html() + '</td>' +
                        '<td class="vym-mono">' + $('<div>').text(aRow.tanggal_invoice).html() + '</td>' +
                        '<td><strong>' + $('<div>').text(aRow.nama_ekspedisi).html() + '</strong></td>' +
                        '<td class="vym-mono text-muted">' + $('<div>').text(aRow.nomor_sj).html() + '</td>' +
                        '<td class="vym-mono">' + $('<div>').text(aRow.status_approval).html() + '</td>' +
                        '<td><span class="' + bCol + '">' + $('<div>').text(aRow.aging_bucket).html() + '</span></td>' +
                        '<td class="text-right vym-mono font-weight-bold">' + aRow.days_aging + ' Hari</td>' +
                        '<td class="text-right vym-mono font-weight-bold" style="color:#fb923c;">' + fmtRpFull(aRow.nominal) + '</td>' +
                        '</tr>';
                }
            } else {
                apHtml = '<tr><td colspan="8" class="text-center py-3 text-muted">Tidak ada hutang tagihan ekspedisi yang belum dibayar pada periode ini.</td></tr>';
            }
            $('#vym-fi-payables-table tbody').html(apHtml);

            // Rebuild Phase 4 Business Analytics Tables & Summary
            var ba = resp.business_analytics || {};
            var baCust = ba.customer_intelligence || {};
            var baProd = ba.product_intelligence || {};
            var baSupp = ba.supplier_logistics_intelligence || {};
            var baOps  = ba.operational_intelligence || {};

            if (baCust.summary) {
                var cs = baCust.summary;
                $('#ba-hhi-val').text('HHI ' + cs.hhi_index + ' • Top-3: ' + cs.top3_share_pct + '%');
                $('#ba-hhi-tier').text((cs.concentration_risk || '-') + ' (' + cs.active_customers + ' Entitas)');
            }
            if (baProd.summary) {
                var ps = baProd.summary;
                $('#ba-sku-val').text(ps.distinct_skus_billed + ' Billed / ' + ps.distinct_skus_ordered + ' Ordered SKU');
                $('#ba-sku-sub').text('Realisasi Unit: ' + ps.unit_fulfillment_pct + '% (' + Math.round(ps.total_units_billed || 0).toLocaleString('id-ID') + ' Unit)');
            }
            if (baOps.kpis) {
                var ok = baOps.kpis;
                $('#ba-ops-val').text('Lead Time: ' + ok.avg_po_to_invoice_days + ' Hari');
                $('#ba-ops-sub').text('Approval PO: ' + ok.order_approval_rate_pct + '% • Dispatch Inv: ' + ok.invoice_dispatch_rate_pct + '%');
            }

            // [06A] Customer Pareto Matrix
            var cMat = baCust.matrix || [];
            var cMatHtml = '';
            if (cMat.length > 0) {
                for (var cmIdx = 0; cmIdx < cMat.length; cmIdx++) {
                    var cm = cMat[cmIdx];
                    cMatHtml += '<tr>' +
                        '<td><strong style="color:#f8fafc;">' + $('<div>').text(cm.customer_nama).html() + '</strong>' +
                        '<div style="font-size:10px; color:#64748b;">Realisasi Tagih: ' + cm.realization_pct + '% • Akhir: ' + $('<div>').text(cm.last_tx_date).html() + '</div></td>' +
                        '<td class="text-right vym-mono" style="color:#38bdf8;">' + fmtRpFull(cm.order_nominal) + ' <small class="text-muted">(' + cm.order_count + ')</small></td>' +
                        '<td class="text-right vym-mono" style="color:#34d399;">' + fmtRpFull(cm.inv_nominal) + ' <small class="text-muted">(' + cm.inv_count + ')</small></td>' +
                        '<td class="text-right vym-mono font-weight-bold" style="color:#fbbf24;">' + cm.share_of_billed_pct + '%</td>' +
                        '<td class="text-right vym-mono">' + fmtRpFull(cm.avg_order_val) + '</td>' +
                        '</tr>';
                }
            } else {
                cMatHtml = '<tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada data pelanggan pada periode ini.</td></tr>';
            }
            $('#vym-ba-cust-table tbody').html(cMatHtml);

            // [06B] Product Velocity Matrix
            var pMat = baProd.matrix || [];
            var pMatHtml = '';
            if (pMat.length > 0) {
                for (var pmIdx = 0; pmIdx < pMat.length; pmIdx++) {
                    var pm = pMat[pmIdx];
                    var valDisp = (parseFloat(pm.billed_nominal) > 0) ? pm.billed_nominal : pm.ordered_nominal;
                    pMatHtml += '<tr>' +
                        '<td><strong style="color:#f8fafc;">' + $('<div>').text(pm.nama_barang).html() + '</strong>' +
                        '<div style="font-size:10px; color:#64748b;">Kode: <code>' + $('<div>').text(pm.kode_barang).html() + '</code> • Fulfill: ' + pm.fulfillment_pct + '%</div></td>' +
                        '<td class="text-right vym-mono" style="color:#38bdf8;">' + Math.round(pm.ordered_qty || 0).toLocaleString('id-ID') + '</td>' +
                        '<td class="text-right vym-mono" style="color:#34d399;">' + Math.round(pm.billed_qty || 0).toLocaleString('id-ID') + '</td>' +
                        '<td class="text-right vym-mono font-weight-bold" style="color:#10b981;">' + fmtRpFull(valDisp) + '</td>' +
                        '<td class="text-right vym-mono">' + fmtRpFull(pm.avg_unit_price) + '</td>' +
                        '</tr>';
                }
            } else {
                pMatHtml = '<tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada rincian produk pada periode ini.</td></tr>';
            }
            $('#vym-ba-prod-table tbody').html(pMatHtml);

            // [06C] Freight Vendors
            var fVnd = baSupp.freight_vendors || [];
            var fVndHtml = '';
            if (fVnd.length > 0) {
                for (var fvIdx = 0; fvIdx < fVnd.length; fvIdx++) {
                    var fv = fVnd[fvIdx];
                    fVndHtml += '<tr>' +
                        '<td><strong style="color:#f8fafc;">' + $('<div>').text(fv.vendor_nama).html() + '</strong></td>' +
                        '<td class="text-right vym-mono">' + fv.doc_count + '</td>' +
                        '<td class="text-right vym-mono" style="color:#34d399;">' + fmtRpFull(fv.paid_nominal) + '</td>' +
                        '<td class="text-right vym-mono" style="color:#fb923c;">' + fmtRpFull(fv.unpaid_nominal) + '</td>' +
                        '<td class="text-right vym-mono">' + fmtRpFull(fv.avg_shipment_cost) + '</td>' +
                        '</tr>';
                }
            } else {
                fVndHtml = '<tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada tagihan ekspedisi pada periode ini.</td></tr>';
            }
            $('#vym-ba-vendor-table tbody').html(fVndHtml);

            // [06D] Operational Pipeline & Principal Suppliers
            var oPip = baOps.pipeline_breakdown || [];
            var pSup = (baSupp.principal_suppliers && baSupp.principal_suppliers.rows) ? baSupp.principal_suppliers.rows : [];
            var opsHtml = '';
            for (var opIdx = 0; opIdx < oPip.length; opIdx++) {
                var op = oPip[opIdx];
                opsHtml += '<tr>' +
                    '<td><strong style="color:#38bdf8;">' + $('<div>').text(op.stage).html() + '</strong></td>' +
                    '<td><code>' + $('<div>').text(op.source).html() + '</code></td>' +
                    '<td class="text-right vym-mono">' + op.doc_count + ' Dok</td>' +
                    '<td class="text-right vym-mono font-weight-bold">' + fmtRpFull(op.nominal) + '</td>' +
                    '</tr>';
            }
            for (var psIdx = 0; psIdx < Math.min(8, pSup.length); psIdx++) {
                var psRow = pSup[psIdx];
                opsHtml += '<tr>' +
                    '<td><strong style="color:#f8fafc;">SUPPLIER: ' + $('<div>').text(psRow.nama_pemasok).html() + '</strong></td>' +
                    '<td>' + $('<div>').text(psRow.negara + ' (' + psRow.tipe_nama + ')').html() + '</td>' +
                    '<td class="text-right vym-mono">' + psRow.product_count + ' Produk</td>' +
                    '<td class="text-right vym-mono" style="color:#fbbf24;">' + psRow.harga_doc_count + ' Katalog Harga</td>' +
                    '</tr>';
            }
            $('#vym-ba-ops-table tbody').html(opsHtml);

            renderVymSeries();
        }

        function fetchVymOverview() {
            $.ajax({
                url: '<?= base_url("vym_bi/api_overview_data") ?>',
                type: 'GET',
                data: {
                    preset: vymBiState.preset,
                    start_date: vymBiState.startDate,
                    end_date: vymBiState.endDate,
                    interval: vymBiState.interval
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp && resp.status === 'success') {
                        updateUiFromData(resp);
                    }
                }
            });
        }

        // Toolbar Event Listeners
        $('#vym-preset-group .vym-btn').on('click', function() {
            $('#vym-preset-group .vym-btn').removeClass('active');
            $(this).addClass('active');
            vymBiState.preset = $(this).data('preset');
            fetchVymOverview();
        });

        $('#vym-btn-apply-custom').on('click', function() {
            $('#vym-preset-group .vym-btn').removeClass('active');
            vymBiState.preset = 'CUSTOM';
            vymBiState.startDate = $('#vym-start-date').val();
            vymBiState.endDate = $('#vym-end-date').val();
            fetchVymOverview();
        });

        $('#vym-interval-group .vym-btn').on('click', function() {
            $('#vym-interval-group .vym-btn').removeClass('active');
            $(this).addClass('active');
            vymBiState.interval = $(this).data('interval');
            fetchVymOverview();
        });

        $('#vym-series-mode-group .vym-btn').on('click', function() {
            $('#vym-series-mode-group .vym-btn').removeClass('active');
            $(this).addClass('active');
            vymBiState.seriesMode = $(this).data('mode');
            renderVymSeries();
        });

        $('#vym-fi-tab-group .vym-btn').on('click', function() {
            $('#vym-fi-tab-group .vym-btn').removeClass('active');
            $(this).addClass('active');
            var tabKey = $(this).data('fitab');
            if (tabKey === 'ALL_FI') {
                $('.vym-fi-pane').show();
            } else {
                $('.vym-fi-pane').hide();
                $('.vym-fi-pane[data-fipane="' + tabKey + '"]').show();
            }
        });

        $('#vym-btn-fit-chart').on('click', function() {
            if (tvChart) tvChart.timeScale().fitContent();
        });

        $('#vym-ledger-search').on('keyup', function() {
            var q = $(this).val().toLowerCase();
            $('#vym-ledger-table tbody tr').each(function() {
                var txt = $(this).text().toLowerCase();
                $(this).toggle(txt.indexOf(q) !== -1);
            });
        });

        $(window).on('resize', function() {
            if (tvChart && chartContainer) {
                tvChart.applyOptions({ width: chartContainer.clientWidth });
            }
        });

        initVymChart();
        renderVymSeries();
    });
</script>
