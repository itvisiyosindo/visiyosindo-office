<?php
defined('BASEPATH') or exit('No direct script access allowed');

$bulanIndo = [
    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
    7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
];

$fmtDateIndo = function ($raw) use ($bulanIndo) {
    $raw = trim((string) $raw);
    if ($raw === '' || $raw === '0000-00-00') {
        return '-';
    }
    $ts = strtotime($raw);
    if ($ts === false) {
        return htmlspecialchars($raw, ENT_QUOTES, 'UTF-8');
    }
    $d = date('d', $ts);
    $m = $bulanIndo[(int) date('n', $ts)] ?? date('M', $ts);
    $y = date('Y', $ts);
    return $d . ' ' . $m . ' ' . $y;
};

$fmtDateTimeIndo = function ($raw) use ($bulanIndo) {
    $raw = trim((string) $raw);
    if ($raw === '' || $raw === '0000-00-00 00:00:00') {
        return '-';
    }
    $ts = strtotime($raw);
    if ($ts === false) {
        return '-';
    }
    $d = date('d', $ts);
    $m = $bulanIndo[(int) date('n', $ts)] ?? date('M', $ts);
    $y = date('Y', $ts);
    return $d . ' ' . $m . ' ' . $y . ' ' . date('H:i', $ts);
};

$resolveFileUrl = function ($path) {
    $path = trim((string) $path);
    if ($path === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    return base_url(ltrim($path, '/'));
};

$getFileExt = function ($path) {
    $clean = preg_replace('/\?.*$/', '', (string) $path);
    return strtolower(pathinfo($clean, PATHINFO_EXTENSION));
};

$getDocIcon = function ($ext, $hasExternalOnly = false) {
    if ($hasExternalOnly) {
        return ['icon' => 'fas fa-external-link-alt', 'bg' => '#eef2ff', 'color' => '#4f46e5', 'label' => 'LINK'];
    }
    switch ($ext) {
        case 'pdf':
            return ['icon' => 'fas fa-file-pdf', 'bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'PDF'];
        case 'doc':
        case 'docx':
            return ['icon' => 'fas fa-file-word', 'bg' => '#eff6ff', 'color' => '#2563eb', 'label' => 'WORD'];
        case 'xls':
        case 'xlsx':
            return ['icon' => 'fas fa-file-excel', 'bg' => '#ecfdf5', 'color' => '#059669', 'label' => 'EXCEL'];
        case 'ppt':
        case 'pptx':
            return ['icon' => 'fas fa-file-powerpoint', 'bg' => '#fff7ed', 'color' => '#ea580c', 'label' => 'PPT'];
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'webp':
            return ['icon' => 'fas fa-file-image', 'bg' => '#faf5ff', 'color' => '#9333ea', 'label' => 'IMG'];
        case 'zip':
            return ['icon' => 'fas fa-file-archive', 'bg' => '#f8fafc', 'color' => '#475569', 'label' => 'ZIP'];
        default:
            return ['icon' => 'fas fa-file-alt', 'bg' => '#f1f5f9', 'color' => '#334155', 'label' => 'FILE'];
    }
};

$statusUpdateMeta = [
    'berlaku'      => ['label' => 'Berlaku', 'bg' => '#dcfce7', 'color' => '#166534', 'border' => '#bbf7d0', 'icon' => 'fas fa-check-circle'],
    'akan_berlaku' => ['label' => 'Akan Berlaku', 'bg' => '#dbeafe', 'color' => '#1e40af', 'border' => '#bfdbfe', 'icon' => 'fas fa-clock'],
    'revisi'       => ['label' => 'Revisi / Perubahan', 'bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => 'fas fa-sync-alt'],
    'dicabut'      => ['label' => 'Dicabut / Tidak Berlaku', 'bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#fecaca', 'icon' => 'fas fa-ban'],
];

$layananMeta = [
    'offline' => ['label' => 'Offline / Tatap Muka', 'bg' => '#eff6ff', 'color' => '#1d4ed8', 'border' => '#bfdbfe', 'icon' => 'fas fa-building'],
    'online'  => ['label' => 'Online / Daring', 'bg' => '#ecfdf5', 'color' => '#047857', 'border' => '#a7f3d0', 'icon' => 'fas fa-laptop'],
    'hybrid'  => ['label' => 'Online & Offline', 'bg' => '#f5f3ff', 'color' => '#6d28d9', 'border' => '#ddd6fe', 'icon' => 'fas fa-exchange-alt'],
];

$stepTipeMeta = [
    'start'    => ['badge' => 'MULAI', 'icon' => 'fas fa-play', 'accent' => '#2563eb', 'bg' => '#eff6ff', 'border' => '#93c5fd'],
    'process'  => ['badge' => 'PROSES', 'icon' => 'fas fa-cogs', 'accent' => '#334155', 'bg' => '#ffffff', 'border' => '#cbd5e1'],
    'wait'     => ['badge' => 'JADWAL / TUNGGU', 'icon' => 'fas fa-hourglass-half', 'accent' => '#b45309', 'bg' => '#fffbeb', 'border' => '#fcd34d'],
    'decision' => ['badge' => 'VERIFIKASI', 'icon' => 'fas fa-code-branch', 'accent' => '#7c3aed', 'bg' => '#f5f3ff', 'border' => '#c4b5fd'],
    'finish'   => ['badge' => 'SELESAI', 'icon' => 'fas fa-flag-checkered', 'accent' => '#047857', 'bg' => '#ecfdf5', 'border' => '#6ee7b7'],
];

// Kumpulkan kategori guidance unik untuk filter dropdown
$guidanceCategories = [];
foreach ($list_guidance as $gRow) {
    $kat = trim((string) ($gRow['kategori'] ?? ''));
    if ($kat !== '' && !in_array($kat, $guidanceCategories, true)) {
        $guidanceCategories[] = $kat;
    }
}
sort($guidanceCategories);

$accentColor = $instansi_info['color'];
$accentSoft  = $instansi_info['color_soft'];
$accentBdr   = $instansi_info['border_soft'];
?>

<header class="page-header">
    <h2><i class="fas fa-balance-scale"></i>&nbsp;<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>Dokumen Perusahaan</span></li>
            <li><span>Knowledge Center</span></li>
            <li><span><?= htmlspecialchars($instansi_info['nama'], ENT_QUOTES, 'UTF-8') ?></span></li>
        </ol>
    </div>
</header>

<style>
    .rkc-gov-card {
        display: block;
        text-decoration: none !important;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 18px;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        height: 100%;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
    }
    .rkc-gov-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }
    .rkc-gov-card.is-active {
        border-width: 2px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.09);
    }
    .rkc-gov-card .rkc-gov-accent-bar {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }
    .rkc-gov-icon {
        width: 44px;
        height: 44px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }
    .rkc-count-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .rkc-workspace {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }
    .rkc-hero-strip {
        padding: 18px 22px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .rkc-tabs {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 0 16px;
        margin: 0;
        list-style: none;
        gap: 4px;
    }
    .rkc-tabs > li > a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 13px 18px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-decoration: none !important;
        border-bottom: 3px solid transparent;
        white-space: nowrap;
        transition: all 0.18s ease;
    }
    .rkc-tabs > li > a:hover {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.7);
    }
    .rkc-tabs > li > a.active {
        color: <?= $accentColor ?>;
        border-bottom-color: <?= $accentColor ?>;
        background: #ffffff;
    }
    .rkc-tab-count {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 12px;
        background: #e2e8f0;
        color: #334155;
    }
    .rkc-tabs > li > a.active .rkc-tab-count {
        background: <?= $accentSoft ?>;
        color: <?= $accentColor ?>;
        border: 1px solid <?= $accentBdr ?>;
    }
    .rkc-tab-body {
        padding: 20px 22px;
    }
    .rkc-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 18px;
        padding: 12px 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
    }
    .rkc-table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-top: none !important;
        border-bottom: 2px solid #e2e8f0 !important;
        padding: 11px 12px !important;
        vertical-align: middle !important;
    }
    .rkc-table tbody td {
        vertical-align: middle !important;
        padding: 13px 12px !important;
        border-color: #f1f5f9 !important;
        font-size: 13px;
        color: #1e293b;
    }
    .rkc-table tbody tr:hover {
        background: #f8fafc;
    }
    .rkc-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 600;
        line-height: 1.2;
        white-space: nowrap;
    }
    .rkc-date-box {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-weight: 700;
        font-size: 12.5px;
        color: #0f172a;
        white-space: nowrap;
    }
    .rkc-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* ===== FLOWCHART VISUAL STYLES ===== */
    .rkc-flow-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
        margin-bottom: 22px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(15, 23, 42, 0.035);
    }
    .rkc-flow-header {
        padding: 16px 20px;
        background: linear-gradient(to right, #f8fafc, #ffffff);
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .rkc-flow-callout {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-left: 4px solid #d97706;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 18px;
        font-size: 13px;
        color: #78350f;
    }
    .rkc-flowchart-canvas {
        background: radial-gradient(#e2e8f0 1px, transparent 1px);
        background-size: 18px 18px;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px 18px;
    }
    .rkc-flow-track {
        max-width: 680px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .rkc-flow-node {
        width: 100%;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        padding: 14px 18px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        position: relative;
        transition: transform 0.16s ease, box-shadow 0.16s ease;
    }
    .rkc-flow-node:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.09);
    }
    .rkc-flow-node.node-start,
    .rkc-flow-node.node-finish {
        border-radius: 20px;
    }
    .rkc-flow-node.node-wait {
        border-style: dashed;
        border-width: 2px;
    }
    .rkc-flow-step-num {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 12.5px;
        color: #ffffff;
        flex-shrink: 0;
    }
    .rkc-flow-connector {
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 34px;
        justify-content: center;
    }
    .rkc-flow-connector-line {
        width: 2px;
        height: 20px;
        background: #94a3b8;
    }
    .rkc-flow-connector-arrow {
        width: 0;
        height: 0;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-top: 8px solid #64748b;
    }
    .rkc-diagram-box {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #ffffff;
        padding: 14px;
        height: 100%;
    }
    .rkc-diagram-thumb {
        width: 100%;
        max-height: 360px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        cursor: zoom-in;
    }

    /* ===== GUIDANCE DOCUMENT CARD ===== */
    .rkc-guide-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #ffffff;
        padding: 18px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
    }
    .rkc-guide-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.07);
        transform: translateY(-2px);
    }
    .rkc-doc-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 20px;
    }

    /* ===== STEP BUILDER IN MODAL ===== */
    .rkc-builder-row {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 10px;
        position: relative;
    }
    .rkc-builder-row-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .rkc-empty-box {
        text-align: center;
        padding: 46px 20px;
        background: #f8fafc;
        border: 1.5px dashed #cbd5e1;
        border-radius: 12px;
    }
    .rkc-modal-header {
        padding: 14px 20px !important;
        background: #0f172a;
        color: #ffffff;
        border-bottom: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .rkc-modal-header .close {
        color: #cbd5e1;
        opacity: 1;
        text-shadow: none;
        margin: 0;
        padding: 4px 8px;
    }
    .rkc-modal-header .close:hover {
        color: #ffffff;
    }
    .rkc-layanan-filter-btn {
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        padding: 5px 13px;
    }
    @media (max-width: 767px) {
        .rkc-tab-body { padding: 14px; }
        .rkc-hero-strip { padding: 14px; }
        .rkc-flowchart-canvas { padding: 14px 10px; }
    }
</style>

<!-- 1. GOVERNMENT AGENCY SELECTOR CARDS (OSS, KEMENKES, BAPETEN) -->
<div class="row mb-3">
    <?php foreach ($instansi_list as $kode => $gov):
        $isActive = ($kode === $instansi_aktif);
        $cnt      = $counts[$kode] ?? ['updates' => 0, 'konsultasi' => 0, 'guidance' => 0, 'total' => 0];
    ?>
        <div class="col-12 col-md-4 mb-3 mb-md-0">
            <a href="<?= base_url('regulatory_knowledge/instansi/' . $kode) ?>"
               class="rkc-gov-card <?= $isActive ? 'is-active' : '' ?>"
               style="<?= $isActive ? 'border-color:' . $gov['color'] . '; background:' . $gov['color_soft'] . ';' : '' ?>">
                <div class="rkc-gov-accent-bar" style="background: <?= $gov['gradient'] ?>;"></div>
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="d-flex align-items-center" style="gap: 12px;">
                        <div class="rkc-gov-icon" style="background: <?= $isActive ? $gov['color'] : $gov['color_soft'] ?>; color: <?= $isActive ? '#ffffff' : $gov['color'] ?>;">
                            <i class="<?= $gov['icon'] ?>"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center" style="gap: 6px;">
                                <h5 class="mb-0 font-weight-bold text-dark" style="font-size: 16px;"><?= htmlspecialchars($gov['nama'], ENT_QUOTES, 'UTF-8') ?></h5>
                                <?php if ($isActive): ?>
                                    <span class="badge" style="background: <?= $gov['color'] ?>; color: #fff; font-size: 10px; padding: 3px 7px; border-radius: 10px;">Aktif</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted" style="font-size: 11.5px; line-height: 1.3;"><?= htmlspecialchars($gov['nama_lengkap'], ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                    </div>
                </div>
                <p class="mb-2 text-muted" style="font-size: 12px; line-height: 1.45; min-height: 34px;">
                    <?= htmlspecialchars($gov['deskripsi'], ENT_QUOTES, 'UTF-8') ?>
                </p>
                <div class="d-flex flex-wrap" style="gap: 6px;">
                    <span class="rkc-count-pill"><i class="fas fa-gavel" style="color:<?= $gov['color'] ?>;"></i> <?= $cnt['updates'] ?> Update</span>
                    <span class="rkc-count-pill"><i class="fas fa-project-diagram" style="color:<?= $gov['color'] ?>;"></i> <?= $cnt['konsultasi'] ?> Flowchart</span>
                    <span class="rkc-count-pill"><i class="fas fa-book" style="color:<?= $gov['color'] ?>;"></i> <?= $cnt['guidance'] ?> Guidance</span>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<!-- 2. MAIN WORKSPACE: 3 SUBMENU TABS FOR SELECTED GOVERNMENT -->
<div class="rkc-workspace">
    <!-- Hero Strip -->
    <div class="rkc-hero-strip" style="background: linear-gradient(90deg, <?= $accentSoft ?> 0%, #ffffff 65%);">
        <div class="d-flex align-items-center" style="gap: 14px;">
            <div class="rkc-gov-icon" style="background: <?= $instansi_info['gradient'] ?>; color: #ffffff; box-shadow: 0 4px 12px rgba(15,23,42,0.12);">
                <i class="<?= $instansi_info['icon'] ?>"></i>
            </div>
            <div>
                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                    <h4 class="mb-0 font-weight-bold text-dark" style="font-size: 17px;">
                        Knowledge Center — <?= htmlspecialchars($instansi_info['nama'], ENT_QUOTES, 'UTF-8') ?>
                    </h4>
                    <span class="rkc-badge" style="background: <?= $accentSoft ?>; color: <?= $accentColor ?>; border: 1px solid <?= $accentBdr ?>;">
                        Regulatory Advice
                    </span>
                </div>
                <div class="text-muted small mt-1">
                    <?= htmlspecialchars($instansi_info['nama_lengkap'], ENT_QUOTES, 'UTF-8') ?> — Peraturan terbaru, alur flowchart konsultasi, dan dokumen panduan teknis.
                </div>
            </div>
        </div>
        <div>
            <?php if ($can_manage): ?>
                <span class="rkc-badge" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                    <i class="fas fa-user-shield"></i> Akses Kelola (Regulatory / Admin)
                </span>
            <?php else: ?>
                <span class="rkc-badge" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;">
                    <i class="fas fa-eye"></i> Mode Baca &amp; Unduh
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- 3 Submenu Tabs -->
    <ul class="rkc-tabs nav" id="rkcSubmenuTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?= $active_tab === 'regulatory_update' ? 'active' : '' ?>"
               id="tab-link-regulatory_update"
               data-toggle="tab"
               data-rkc-tab="regulatory_update"
               href="#tab_rkc_updates"
               role="tab">
                <i class="fas fa-gavel"></i>
                <span>Regulatory Update</span>
                <span class="rkc-tab-count"><?= count($list_updates) ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $active_tab === 'konsultasi' ? 'active' : '' ?>"
               id="tab-link-konsultasi"
               data-toggle="tab"
               data-rkc-tab="konsultasi"
               href="#tab_rkc_konsultasi"
               role="tab">
                <i class="fas fa-project-diagram"></i>
                <span>Konsultasi (Flowchart)</span>
                <span class="rkc-tab-count"><?= count($list_konsultasi) ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $active_tab === 'guidance' ? 'active' : '' ?>"
               id="tab-link-guidance"
               data-toggle="tab"
               data-rkc-tab="guidance"
               href="#tab_rkc_guidance"
               role="tab">
                <i class="fas fa-book-open"></i>
                <span>Guidance Document</span>
                <span class="rkc-tab-count"><?= count($list_guidance) ?></span>
            </a>
        </li>
    </ul>

    <div class="tab-content rkc-tab-body">

        <!-- =================================================================
             SUBMENU 1: REGULATORY UPDATE
             ================================================================= -->
        <div class="tab-pane fade <?= $active_tab === 'regulatory_update' ? 'show active' : '' ?>" id="tab_rkc_updates" role="tabpanel">
            <div class="rkc-toolbar">
                <div class="d-flex align-items-center flex-wrap" style="gap: 8px; flex: 1;">
                    <div class="input-group input-group-sm" style="max-width: 320px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control border-left-0" id="rkcSearchUpdate" placeholder="Cari nomor peraturan, judul, ringkasan...">
                    </div>
                    <select class="form-control form-control-sm" id="rkcFilterStatusUpdate" style="width: auto; min-width: 170px;">
                        <option value="">Semua Status Peraturan</option>
                        <option value="berlaku">Berlaku</option>
                        <option value="akan_berlaku">Akan Berlaku</option>
                        <option value="revisi">Revisi / Perubahan</option>
                        <option value="dicabut">Dicabut / Tidak Berlaku</option>
                    </select>
                </div>
                <?php if ($can_manage): ?>
                    <button type="button" class="btn btn-sm btn-primary font-weight-bold shadow-sm btn-rkc-add-update">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Regulatory Update
                    </button>
                <?php endif; ?>
            </div>

            <?php if (empty($list_updates)): ?>
                <div class="rkc-empty-box">
                    <i class="fas fa-gavel fa-3x text-muted mb-3"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Belum Ada Regulatory Update untuk <?= htmlspecialchars($instansi_info['nama'], ENT_QUOTES, 'UTF-8') ?></h5>
                    <p class="text-muted small mb-3">Tambahkan peraturan pemerintah, panduan regulasi, beserta tanggal berlakunya agar tim selalu merujuk pada aturan terbaru.</p>
                    <?php if ($can_manage): ?>
                        <button type="button" class="btn btn-sm btn-primary font-weight-bold btn-rkc-add-update">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Regulatory Update Pertama
                        </button>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover rkc-table mb-0" id="rkcTableUpdates">
                        <thead>
                            <tr>
                                <th style="width: 42px;" class="text-center">#</th>
                                <th>Peraturan / Panduan</th>
                                <th style="width: 155px;" class="text-center">Tanggal Berlaku</th>
                                <th style="width: 165px;" class="text-center">Status</th>
                                <th style="width: 175px;" class="text-center">Lampiran / Sumber</th>
                                <th style="width: 150px;">Terakhir Update</th>
                                <th style="width: 135px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $noUpd = 1; foreach ($list_updates as $upd):
                                $stKey   = $upd['status_peraturan'] ?? 'berlaku';
                                $stInfo  = $statusUpdateMeta[$stKey] ?? $statusUpdateMeta['berlaku'];
                                $fUrl    = $resolveFileUrl($upd['file_path'] ?? '');
                                $extUrl  = trim((string) ($upd['link_eksternal'] ?? ''));
                                $ext     = $getFileExt($upd['file_path'] ?? '');
                                $docIco  = $getDocIcon($ext);
                                $updBy   = !empty($upd['updated_by_nama']) ? $upd['updated_by_nama'] : (!empty($upd['created_by_nama']) ? $upd['created_by_nama'] : 'Divisi Regulatory');
                                $updTime = !empty($upd['updated_at']) ? $upd['updated_at'] : ($upd['created_at'] ?? '');
                                $searchHaystack = strtolower(trim(
                                    ($upd['nomor_peraturan'] ?? '') . ' ' .
                                    ($upd['judul'] ?? '') . ' ' .
                                    ($upd['ringkasan'] ?? '')
                                ));
                            ?>
                                <tr class="rkc-update-row"
                                    data-search="<?= htmlspecialchars($searchHaystack, ENT_QUOTES, 'UTF-8') ?>"
                                    data-status="<?= htmlspecialchars($stKey, ENT_QUOTES, 'UTF-8') ?>">
                                    <td class="text-center text-muted font-weight-bold"><?= $noUpd++ ?></td>
                                    <td>
                                        <?php if (!empty($upd['nomor_peraturan'])): ?>
                                            <div class="mb-1">
                                                <span class="badge" style="background: <?= $accentSoft ?>; color: <?= $accentColor ?>; border: 1px solid <?= $accentBdr ?>; font-size: 11px; font-weight: 700; padding: 3px 8px;">
                                                    <?= htmlspecialchars($upd['nomor_peraturan'], ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="font-weight-bold text-dark" style="font-size: 13.5px; line-height: 1.35;">
                                            <?= htmlspecialchars($upd['judul'], ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                        <?php if (!empty($upd['ringkasan'])): ?>
                                            <div class="text-muted small mt-1 rkc-clamp-2" style="line-height: 1.45;">
                                                <?= htmlspecialchars($upd['ringkasan'], ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="rkc-date-box">
                                            <i class="far fa-calendar-check" style="color: <?= $accentColor ?>;"></i>
                                            <?= $fmtDateIndo($upd['tanggal_berlaku'] ?? '') ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="rkc-badge" style="background: <?= $stInfo['bg'] ?>; color: <?= $stInfo['color'] ?>; border: 1px solid <?= $stInfo['border'] ?>;">
                                            <i class="<?= $stInfo['icon'] ?>"></i> <?= $stInfo['label'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center" style="gap: 5px;">
                                            <?php if ($fUrl !== ''): ?>
                                                <a href="<?= htmlspecialchars($fUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener"
                                                   class="btn btn-xs btn-outline-primary font-weight-semibold" style="font-size: 11.5px; padding: 3px 10px;">
                                                    <i class="<?= $docIco['icon'] ?> mr-1"></i> Unduh <?= $docIco['label'] ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($extUrl !== ''): ?>
                                                <a href="<?= htmlspecialchars($extUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener"
                                                   class="btn btn-xs btn-outline-secondary" style="font-size: 11.5px; padding: 3px 10px;">
                                                    <i class="fas fa-external-link-alt mr-1"></i> Buka Tautan
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($fUrl === '' && $extUrl === ''): ?>
                                                <span class="text-muted small font-italic">Belum ada file</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size: 12px; font-weight: 600; color: #334155;"><?= htmlspecialchars($updBy, ENT_QUOTES, 'UTF-8') ?></div>
                                        <div class="text-muted" style="font-size: 11px;"><?= $fmtDateTimeIndo($updTime) ?></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-xs btn-info btn-rkc-detail-update" data-id="<?= (int) $upd['id'] ?>" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if ($can_manage): ?>
                                                <button type="button" class="btn btn-xs btn-warning text-white btn-rkc-edit-update" data-id="<?= (int) $upd['id'] ?>" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-danger btn-rkc-delete-update" data-id="<?= (int) $upd['id'] ?>" data-judul="<?= htmlspecialchars($upd['judul'], ENT_QUOTES, 'UTF-8') ?>" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div id="rkcEmptyFilterUpdate" class="rkc-empty-box mt-3 d-none">
                    <i class="fas fa-search fa-2x text-muted mb-2"></i>
                    <div class="font-weight-bold text-dark">Tidak ada peraturan yang cocok dengan filter pencarian.</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- =================================================================
             SUBMENU 2: KONSULTASI (ALUR & FLOWCHART)
             ================================================================= -->
        <div class="tab-pane fade <?= $active_tab === 'konsultasi' ? 'show active' : '' ?>" id="tab_rkc_konsultasi" role="tabpanel">
            <div class="rkc-toolbar">
                <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                    <span class="small font-weight-bold text-muted mr-1">Filter Layanan:</span>
                    <button type="button" class="btn btn-sm btn-primary rkc-layanan-filter-btn" data-layanan="">Semua</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rkc-layanan-filter-btn" data-layanan="offline"><i class="fas fa-building mr-1"></i> Offline / Tatap Muka</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rkc-layanan-filter-btn" data-layanan="online"><i class="fas fa-laptop mr-1"></i> Online</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rkc-layanan-filter-btn" data-layanan="hybrid"><i class="fas fa-exchange-alt mr-1"></i> Hybrid</button>
                </div>
                <?php if ($can_manage): ?>
                    <button type="button" class="btn btn-sm btn-primary font-weight-bold shadow-sm btn-rkc-add-konsultasi">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Alur Konsultasi / Flowchart
                    </button>
                <?php endif; ?>
            </div>

            <?php if (empty($list_konsultasi)): ?>
                <div class="rkc-empty-box">
                    <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Belum Ada Flowchart Konsultasi untuk <?= htmlspecialchars($instansi_info['nama'], ENT_QUOTES, 'UTF-8') ?></h5>
                    <p class="text-muted small mb-3">Buat flowchart langkah demi langkah atau unggah diagram alur konsultasi online/offline di sini.</p>
                    <?php if ($can_manage): ?>
                        <button type="button" class="btn btn-sm btn-primary font-weight-bold btn-rkc-add-konsultasi">
                            <i class="fas fa-plus-circle mr-1"></i> Buat Flowchart Pertama
                        </button>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div id="rkcKonsultasiList">
                    <?php foreach ($list_konsultasi as $kon):
                        $layKey   = $kon['jenis_layanan'] ?? 'offline';
                        $layInfo  = $layananMeta[$layKey] ?? $layananMeta['offline'];
                        $steps    = is_array($kon['langkah'] ?? null) ? $kon['langkah'] : [];
                        $fFileUrl = $resolveFileUrl($kon['flowchart_file'] ?? '');
                        $fExt     = $getFileExt($kon['flowchart_file'] ?? '');
                        $isImg    = in_array($fExt, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true);
                        $extUrl   = trim((string) ($kon['link_eksternal'] ?? ''));
                        $hasMedia = ($fFileUrl !== '' || $extUrl !== '');
                        $updBy    = !empty($kon['updated_by_nama']) ? $kon['updated_by_nama'] : (!empty($kon['created_by_nama']) ? $kon['created_by_nama'] : 'Divisi Regulatory');
                        $updTime  = !empty($kon['updated_at']) ? $kon['updated_at'] : ($kon['created_at'] ?? '');
                    ?>
                        <div class="rkc-flow-card rkc-konsultasi-item" data-layanan="<?= htmlspecialchars($layKey, ENT_QUOTES, 'UTF-8') ?>">
                            <div class="rkc-flow-header">
                                <div>
                                    <div class="d-flex align-items-center flex-wrap mb-1" style="gap: 7px;">
                                        <span class="rkc-badge" style="background: <?= $layInfo['bg'] ?>; color: <?= $layInfo['color'] ?>; border: 1px solid <?= $layInfo['border'] ?>;">
                                            <i class="<?= $layInfo['icon'] ?>"></i> <?= $layInfo['label'] ?>
                                        </span>
                                        <?php if (!empty($kon['estimasi_waktu'])): ?>
                                            <span class="rkc-badge" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;">
                                                <i class="far fa-clock text-primary"></i> <?= htmlspecialchars($kon['estimasi_waktu'], ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (($kon['status'] ?? 'aktif') === 'draft'): ?>
                                            <span class="rkc-badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                                <i class="fas fa-pen-square"></i> Draft (Hanya terlihat pengelola)
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <h5 class="mb-1 font-weight-bold text-dark" style="font-size: 16px;">
                                        <?= htmlspecialchars($kon['judul'], ENT_QUOTES, 'UTF-8') ?>
                                    </h5>
                                    <?php if (!empty($kon['ringkasan'])): ?>
                                        <p class="mb-0 text-muted" style="font-size: 13px;"><?= nl2br(htmlspecialchars($kon['ringkasan'], ENT_QUOTES, 'UTF-8')) ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php if ($can_manage): ?>
                                    <div class="d-flex align-items-center" style="gap: 6px;">
                                        <button type="button" class="btn btn-xs btn-warning text-white font-weight-semibold btn-rkc-edit-konsultasi" data-id="<?= (int) $kon['id'] ?>">
                                            <i class="fas fa-pencil-alt mr-1"></i> Edit Alur
                                        </button>
                                        <button type="button" class="btn btn-xs btn-outline-danger btn-rkc-delete-konsultasi" data-id="<?= (int) $kon['id'] ?>" data-judul="<?= htmlspecialchars($kon['judul'], ENT_QUOTES, 'UTF-8') ?>" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="p-3 p-md-4">
                                <?php if (!empty($kon['catatan_penting'])): ?>
                                    <div class="rkc-flow-callout d-flex align-items-start" style="gap: 10px;">
                                        <i class="fas fa-exclamation-circle mt-1" style="font-size: 16px; color: #d97706;"></i>
                                        <div>
                                            <strong class="d-block mb-1" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Ketentuan &amp; Catatan Penting</strong>
                                            <span><?= nl2br(htmlspecialchars($kon['catatan_penting'], ENT_QUOTES, 'UTF-8')) ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="row">
                                    <?php if (!empty($steps)): ?>
                                        <div class="<?= $hasMedia ? 'col-lg-8' : 'col-12' ?> mb-3 mb-lg-0">
                                            <div class="rkc-flowchart-canvas">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <span class="small font-weight-bold text-uppercase text-muted" style="letter-spacing: 0.05em;">
                                                        <i class="fas fa-sitemap mr-1" style="color: <?= $accentColor ?>;"></i> Flowchart Alur Konsultasi (<?= count($steps) ?> Tahap)
                                                    </span>
                                                </div>
                                                <div class="rkc-flow-track">
                                                    <?php $totalSteps = count($steps); foreach ($steps as $idx => $st):
                                                        $tKey  = $st['tipe'] ?? 'process';
                                                        $tMeta = $stepTipeMeta[$tKey] ?? $stepTipeMeta['process'];
                                                    ?>
                                                        <div class="rkc-flow-node node-<?= htmlspecialchars($tKey, ENT_QUOTES, 'UTF-8') ?>"
                                                             style="background: <?= $tMeta['bg'] ?>; border-color: <?= $tMeta['border'] ?>;">
                                                            <div class="d-flex align-items-start" style="gap: 13px;">
                                                                <div class="rkc-flow-step-num" style="background: <?= $tMeta['accent'] ?>;">
                                                                    <?= $idx + 1 ?>
                                                                </div>
                                                                <div style="flex: 1; min-width: 0;">
                                                                    <div class="d-flex align-items-center justify-content-between flex-wrap mb-1" style="gap: 6px;">
                                                                        <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                                                                            <span class="badge" style="background: rgba(15,23,42,0.07); color: <?= $tMeta['accent'] ?>; font-size: 10px; font-weight: 700; padding: 3px 7px;">
                                                                                <i class="<?= $tMeta['icon'] ?> mr-1"></i><?= $tMeta['badge'] ?>
                                                                            </span>
                                                                            <strong class="text-dark" style="font-size: 14px;"><?= htmlspecialchars($st['judul'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
                                                                        </div>
                                                                        <?php if (!empty($st['estimasi'])): ?>
                                                                            <span class="badge badge-light border text-dark" style="font-size: 11px; font-weight: 600; padding: 4px 8px;">
                                                                                <i class="far fa-clock mr-1 text-muted"></i><?= htmlspecialchars($st['estimasi'], ENT_QUOTES, 'UTF-8') ?>
                                                                            </span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <?php if (!empty($st['deskripsi'])): ?>
                                                                        <div class="text-muted" style="font-size: 12.5px; line-height: 1.5;">
                                                                            <?= nl2br(htmlspecialchars($st['deskripsi'], ENT_QUOTES, 'UTF-8')) ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php if ($idx < $totalSteps - 1): ?>
                                                            <div class="rkc-flow-connector" aria-hidden="true">
                                                                <div class="rkc-flow-connector-line"></div>
                                                                <div class="rkc-flow-connector-arrow"></div>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($hasMedia): ?>
                                        <div class="<?= !empty($steps) ? 'col-lg-4' : 'col-12' ?>">
                                            <div class="rkc-diagram-box d-flex flex-column justify-content-between">
                                                <div>
                                                    <div class="small font-weight-bold text-uppercase text-muted mb-2" style="letter-spacing: 0.05em;">
                                                        <i class="fas fa-image mr-1 text-primary"></i> Lampiran Diagram &amp; Tautan
                                                    </div>
                                                    <?php if ($fFileUrl !== ''): ?>
                                                        <?php if ($isImg): ?>
                                                            <img src="<?= htmlspecialchars($fFileUrl, ENT_QUOTES, 'UTF-8') ?>"
                                                                 alt="Diagram Flowchart <?= htmlspecialchars($kon['judul'], ENT_QUOTES, 'UTF-8') ?>"
                                                                 class="rkc-diagram-thumb mb-2 btn-rkc-zoom-image"
                                                                 data-src="<?= htmlspecialchars($fFileUrl, ENT_QUOTES, 'UTF-8') ?>"
                                                                 data-title="<?= htmlspecialchars($kon['judul'], ENT_QUOTES, 'UTF-8') ?>">
                                                            <div class="d-flex flex-wrap" style="gap: 6px;">
                                                                <button type="button" class="btn btn-xs btn-outline-primary btn-rkc-zoom-image"
                                                                        data-src="<?= htmlspecialchars($fFileUrl, ENT_QUOTES, 'UTF-8') ?>"
                                                                        data-title="<?= htmlspecialchars($kon['judul'], ENT_QUOTES, 'UTF-8') ?>">
                                                                    <i class="fas fa-search-plus mr-1"></i> Perbesar
                                                                </button>
                                                                <a href="<?= htmlspecialchars($fFileUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="btn btn-xs btn-outline-secondary">
                                                                    <i class="fas fa-download mr-1"></i> Unduh
                                                                </a>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="p-3 bg-light rounded border text-center mb-2">
                                                                <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                                                <div class="small font-weight-semibold text-dark text-truncate mb-2">
                                                                    <?= htmlspecialchars($kon['flowchart_nama_asli'] ?: basename($kon['flowchart_file']), ENT_QUOTES, 'UTF-8') ?>
                                                                </div>
                                                                <a href="<?= htmlspecialchars($fFileUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="btn btn-sm btn-danger">
                                                                    <i class="fas fa-external-link-alt mr-1"></i> Buka Diagram PDF
                                                                </a>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>

                                                    <?php if ($extUrl !== ''): ?>
                                                        <div class="mt-3 pt-2 border-top">
                                                            <a href="<?= htmlspecialchars($extUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary btn-block">
                                                                <i class="fas fa-external-link-alt mr-1"></i> Buka Portal / Link Ticketing
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="px-3 px-md-4 py-2 bg-light border-top d-flex justify-content-between align-items-center flex-wrap" style="font-size: 11.5px; color: #64748b;">
                                <span><i class="fas fa-user-edit mr-1"></i> Diperbarui oleh <strong><?= htmlspecialchars($updBy, ENT_QUOTES, 'UTF-8') ?></strong></span>
                                <span><i class="far fa-clock mr-1"></i> <?= $fmtDateTimeIndo($updTime) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div id="rkcEmptyFilterKonsultasi" class="rkc-empty-box d-none">
                    <i class="fas fa-filter fa-2x text-muted mb-2"></i>
                    <div class="font-weight-bold text-dark">Belum ada alur konsultasi untuk kategori layanan ini.</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- =================================================================
             SUBMENU 3: GUIDANCE DOCUMENT
             ================================================================= -->
        <div class="tab-pane fade <?= $active_tab === 'guidance' ? 'show active' : '' ?>" id="tab_rkc_guidance" role="tabpanel">
            <div class="rkc-toolbar">
                <div class="d-flex align-items-center flex-wrap" style="gap: 8px; flex: 1;">
                    <div class="input-group input-group-sm" style="max-width: 320px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control border-left-0" id="rkcSearchGuidance" placeholder="Cari judul panduan, kategori, deskripsi...">
                    </div>
                    <select class="form-control form-control-sm" id="rkcFilterKatGuidance" style="width: auto; min-width: 170px;">
                        <option value="">Semua Kategori Panduan</option>
                        <?php foreach ($guidanceCategories as $kat): ?>
                            <option value="<?= htmlspecialchars(strtolower($kat), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($kat, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($can_manage): ?>
                    <button type="button" class="btn btn-sm btn-primary font-weight-bold shadow-sm btn-rkc-add-guidance">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Guidance Document
                    </button>
                <?php endif; ?>
            </div>

            <?php if (empty($list_guidance)): ?>
                <div class="rkc-empty-box">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Belum Ada Guidance Document untuk <?= htmlspecialchars($instansi_info['nama'], ENT_QUOTES, 'UTF-8') ?></h5>
                    <p class="text-muted small mb-3">Unggah dokumen panduan teknis (misal: Panduan Perubahan/Penambahan KBLI, checklist dokumen, dll.) agar mudah diakses seluruh tim.</p>
                    <?php if ($can_manage): ?>
                        <button type="button" class="btn btn-sm btn-primary font-weight-bold btn-rkc-add-guidance">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Guidance Document Pertama
                        </button>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="row" id="rkcGuidanceGrid">
                    <?php foreach ($list_guidance as $g):
                        $fUrl    = $resolveFileUrl($g['file_path'] ?? '');
                        $extUrl  = trim((string) ($g['link_eksternal'] ?? ''));
                        $ext     = $getFileExt($g['file_path'] ?? '');
                        $docIco  = $getDocIcon($ext, ($fUrl === '' && $extUrl !== ''));
                        $updBy   = !empty($g['updated_by_nama']) ? $g['updated_by_nama'] : (!empty($g['created_by_nama']) ? $g['created_by_nama'] : 'Divisi Regulatory');
                        $searchH = strtolower(trim(($g['judul'] ?? '') . ' ' . ($g['kategori'] ?? '') . ' ' . ($g['deskripsi'] ?? '') . ' ' . ($g['versi'] ?? '')));
                    ?>
                        <div class="col-12 col-lg-6 mb-3 rkc-guidance-item"
                             data-search="<?= htmlspecialchars($searchH, ENT_QUOTES, 'UTF-8') ?>"
                             data-kategori="<?= htmlspecialchars(strtolower(trim((string) ($g['kategori'] ?? ''))), ENT_QUOTES, 'UTF-8') ?>">
                            <div class="rkc-guide-card">
                                <div>
                                    <div class="d-flex align-items-start" style="gap: 14px;">
                                        <div class="rkc-doc-icon-box" style="background: <?= $docIco['bg'] ?>; color: <?= $docIco['color'] ?>;">
                                            <i class="<?= $docIco['icon'] ?>"></i>
                                            <span style="font-size: 9px; font-weight: 800; margin-top: 2px;"><?= $docIco['label'] ?></span>
                                        </div>
                                        <div style="flex: 1; min-width: 0;">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-1" style="gap: 6px;">
                                                <div class="d-flex align-items-center flex-wrap" style="gap: 5px;">
                                                    <span class="badge" style="background: <?= $accentSoft ?>; color: <?= $accentColor ?>; border: 1px solid <?= $accentBdr ?>; font-size: 11px; padding: 3px 8px;">
                                                        <?= htmlspecialchars($g['kategori'] ?: 'Panduan Umum', ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                    <span class="badge badge-light border text-dark" style="font-size: 11px; padding: 3px 7px;">
                                                        <?= htmlspecialchars($g['versi'] ?: 'v1.0', ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                </div>
                                                <span class="text-muted" style="font-size: 11.5px;">
                                                    <i class="far fa-calendar-alt mr-1"></i><?= $fmtDateIndo($g['tanggal_dokumen'] ?? $g['created_at']) ?>
                                                </span>
                                            </div>
                                            <h6 class="font-weight-bold text-dark mb-1" style="font-size: 14.5px; line-height: 1.35;">
                                                <?= htmlspecialchars($g['judul'], ENT_QUOTES, 'UTF-8') ?>
                                            </h6>
                                            <?php if (!empty($g['deskripsi'])): ?>
                                                <p class="text-muted mb-2" style="font-size: 12.5px; line-height: 1.5;">
                                                    <?= nl2br(htmlspecialchars($g['deskripsi'], ENT_QUOTES, 'UTF-8')) ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if (!empty($g['file_nama_asli'])): ?>
                                                <div class="small text-muted mb-2 text-truncate" title="<?= htmlspecialchars($g['file_nama_asli'], ENT_QUOTES, 'UTF-8') ?>">
                                                    <i class="fas fa-paperclip mr-1"></i> <?= htmlspecialchars($g['file_nama_asli'], ENT_QUOTES, 'UTF-8') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3 mt-2 border-top d-flex align-items-center justify-content-between flex-wrap" style="gap: 8px;">
                                    <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                                        <?php if ($fUrl !== ''): ?>
                                            <a href="<?= htmlspecialchars($fUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="btn btn-xs btn-primary font-weight-semibold">
                                                <i class="fas fa-download mr-1"></i> Unduh / Buka Dokumen
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($extUrl !== ''): ?>
                                            <a href="<?= htmlspecialchars($extUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="btn btn-xs btn-outline-primary">
                                                <i class="fas fa-external-link-alt mr-1"></i> Buka Tautan
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($fUrl === '' && $extUrl === ''): ?>
                                            <span class="small text-muted font-italic">Belum ada file terlampir</span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-flex align-items-center" style="gap: 6px;">
                                        <span class="text-muted d-none d-sm-inline" style="font-size: 11px;">
                                            <i class="fas fa-user mr-1"></i><?= htmlspecialchars($updBy, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                        <?php if ($can_manage): ?>
                                            <button type="button" class="btn btn-xs btn-warning text-white btn-rkc-edit-guidance" data-id="<?= (int) $g['id'] ?>" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-danger btn-rkc-delete-guidance" data-id="<?= (int) $g['id'] ?>" data-judul="<?= htmlspecialchars($g['judul'], ENT_QUOTES, 'UTF-8') ?>" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div id="rkcEmptyFilterGuidance" class="rkc-empty-box d-none">
                    <i class="fas fa-search fa-2x text-muted mb-2"></i>
                    <div class="font-weight-bold text-dark">Tidak ada guidance document yang cocok dengan filter pencarian.</div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- =========================================================================
     MODAL 1: DETAIL REGULATORY UPDATE
     ========================================================================= -->
<div class="modal fade" id="modalRkcUpdateDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header rkc-modal-header">
                <h5 class="modal-title font-weight-bold" style="font-size: 15px;">
                    <i class="fas fa-gavel mr-2 text-info"></i>Detail Regulatory Update
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body p-4" id="rkcDetailUpdateBody">
                <div class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat detail...</div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL 2: ZOOM GAMBAR FLOWCHART
     ========================================================================= -->
<div class="modal fade" id="modalRkcImagePreview" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header rkc-modal-header">
                <h5 class="modal-title font-weight-bold" id="rkcImagePreviewTitle" style="font-size: 15px;">Diagram Flowchart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body text-center bg-dark p-3">
                <img src="" id="rkcImagePreviewImg" alt="Diagram Flowchart" class="img-fluid rounded" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

<?php if ($can_manage): ?>
<!-- =========================================================================
     MODAL 3: FORM TAMBAH / EDIT REGULATORY UPDATE
     ========================================================================= -->
<div class="modal fade" id="modalRkcUpdate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header rkc-modal-header">
                <h5 class="modal-title font-weight-bold" id="modalRkcUpdateTitle" style="font-size: 15px;">
                    <i class="fas fa-gavel mr-2 text-info"></i>Tambah Regulatory Update
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form id="formRkcUpdate" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="id" id="upd_id" value="0">
                <input type="hidden" name="hapus_file" id="upd_hapus_file" value="0">
                <div class="modal-body p-4">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="font-weight-semibold small">Instansi Pemerintah <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="instansi" id="upd_instansi" required>
                                <option value="oss">OSS</option>
                                <option value="kemenkes">Kemenkes</option>
                                <option value="bapeten">BAPETEN</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="font-weight-semibold small">Nomor Peraturan / PP</label>
                            <input type="text" class="form-control form-control-sm" name="nomor_peraturan" id="upd_nomor" placeholder="Misal: PP No. 5 Tahun 2021">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="font-weight-semibold small">Tanggal Berlaku <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" name="tanggal_berlaku" id="upd_tanggal_berlaku" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label class="font-weight-semibold small">Judul Peraturan / Panduan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="judul" id="upd_judul" placeholder="Judul lengkap peraturan atau update regulasi" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="font-weight-semibold small">Status Peraturan</label>
                            <select class="form-control form-control-sm" name="status_peraturan" id="upd_status">
                                <option value="berlaku">Berlaku</option>
                                <option value="akan_berlaku">Akan Berlaku</option>
                                <option value="revisi">Revisi / Perubahan</option>
                                <option value="dicabut">Dicabut / Tidak Berlaku</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-semibold small">Ringkasan Isi &amp; Poin Perubahan</label>
                        <textarea class="form-control form-control-sm" name="ringkasan" id="upd_ringkasan" rows="4" placeholder="Jelaskan poin penting peraturan, perubahan yang berlaku, atau dampaknya terhadap operasional/perizinan..."></textarea>
                    </div>

                    <div class="p-3 bg-light rounded border">
                        <div class="small font-weight-bold text-dark mb-2"><i class="fas fa-paperclip mr-1 text-primary"></i> Lampiran Dokumen Peraturan (Upload File atau Link Eksternal)</div>
                        <div class="form-row">
                            <div class="form-group col-md-6 mb-md-0">
                                <label class="small text-muted mb-1">Upload File (PDF / Word / Excel / Gambar, maks. 10 MB)</label>
                                <input type="file" class="form-control-file small" name="file_dokumen" id="upd_file_dokumen" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.webp,.zip">
                                <div id="upd_existing_file_wrap" class="mt-2 d-none">
                                    <span class="badge badge-info mr-1" id="upd_existing_file_name"></span>
                                    <button type="button" class="btn btn-xs btn-outline-danger" id="btnUpdRemoveFile"><i class="fas fa-times"></i> Hapus File</button>
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-0">
                                <label class="small text-muted mb-1">Atau Tautan Eksternal (JDIH / Google Drive / Web Resmi)</label>
                                <input type="url" class="form-control form-control-sm" name="link_eksternal" id="upd_link_eksternal" placeholder="https://...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary font-weight-bold btn-rkc-save-update">
                        <i class="fas fa-save mr-1"></i> Simpan Regulatory Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL 4: FORM TAMBAH / EDIT KONSULTASI (FLOWCHART BUILDER)
     ========================================================================= -->
<div class="modal fade" id="modalRkcKonsultasi" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header rkc-modal-header">
                <h5 class="modal-title font-weight-bold" id="modalRkcKonsultasiTitle" style="font-size: 15px;">
                    <i class="fas fa-project-diagram mr-2 text-info"></i>Tambah Alur Konsultasi &amp; Flowchart
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form id="formRkcKonsultasi" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="id" id="kon_id" value="0">
                <input type="hidden" name="hapus_file" id="kon_hapus_file" value="0">
                <input type="hidden" name="langkah_json" id="kon_langkah_json" value="[]">
                <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label class="font-weight-semibold small">Instansi Pemerintah <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="instansi" id="kon_instansi" required>
                                <option value="oss">OSS</option>
                                <option value="kemenkes">Kemenkes</option>
                                <option value="bapeten">BAPETEN</option>
                            </select>
                        </div>
                        <div class="form-group col-md-5">
                            <label class="font-weight-semibold small">Judul Alur Konsultasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="judul" id="kon_judul" placeholder="Misal: Alur Konsultasi Offline (Tatap Muka) OSS" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-semibold small">Jenis Layanan</label>
                            <select class="form-control form-control-sm" name="jenis_layanan" id="kon_jenis_layanan">
                                <option value="offline">Offline / Tatap Muka</option>
                                <option value="online">Online / Daring</option>
                                <option value="hybrid">Online &amp; Offline</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-semibold small">Status</label>
                            <select class="form-control form-control-sm" name="status" id="kon_status">
                                <option value="aktif">Aktif (Tampil)</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="font-weight-semibold small">Estimasi Waktu / Masa Berlaku</label>
                            <input type="text" class="form-control form-control-sm" name="estimasi_waktu" id="kon_estimasi_waktu" placeholder="Misal: Berlaku Minggu Berikutnya (H+7)">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-semibold small">Ringkasan Singkat</label>
                            <input type="text" class="form-control form-control-sm" name="ringkasan" id="kon_ringkasan" placeholder="Gambaran umum alur konsultasi ini...">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-semibold small">Urutan Tampil</label>
                            <input type="number" class="form-control form-control-sm" name="urutan" id="kon_urutan" value="1" min="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-semibold small">Catatan Penting / Ketentuan Khusus</label>
                        <textarea class="form-control form-control-sm" name="catatan_penting" id="kon_catatan_penting" rows="2" placeholder="Misal: Tiketing diambil hari Selasa maka konsultasi tatap muka dilaksanakan Selasa minggu depan..."></textarea>
                    </div>

                    <!-- INTERACTIVE FLOWCHART STEP BUILDER -->
                    <div class="p-3 rounded border mb-3" style="background: #f1f5f9;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap: 8px;">
                            <div>
                                <div class="font-weight-bold text-dark" style="font-size: 13.5px;">
                                    <i class="fas fa-sitemap text-primary mr-1"></i> Langkah-langkah Flowchart (Visual Step-by-Step)
                                </div>
                                <small class="text-muted">Susun urutan langkah flowchart dari awal sampai selesai. Otomatis dirender menjadi diagram alur visual.</small>
                            </div>
                            <button type="button" class="btn btn-xs btn-success font-weight-bold" id="btnRkcAddStep">
                                <i class="fas fa-plus mr-1"></i> Tambah Langkah
                            </button>
                        </div>
                        <div id="rkcStepsContainer"></div>
                    </div>

                    <!-- OPTIONAL DIAGRAM UPLOAD / EXTERNAL LINK -->
                    <div class="p-3 bg-light rounded border">
                        <div class="small font-weight-bold text-dark mb-2"><i class="fas fa-image mr-1 text-primary"></i> Lampiran Diagram Visual / Portal Ticketing (Opsional)</div>
                        <div class="form-row">
                            <div class="form-group col-md-6 mb-md-0">
                                <label class="small text-muted mb-1">Upload Gambar / PDF Flowchart (PNG, JPG, PDF, maks. 10 MB)</label>
                                <input type="file" class="form-control-file small" name="flowchart_file" id="kon_flowchart_file" accept=".jpg,.jpeg,.png,.webp,.gif,.pdf">
                                <div id="kon_existing_file_wrap" class="mt-2 d-none">
                                    <span class="badge badge-info mr-1" id="kon_existing_file_name"></span>
                                    <button type="button" class="btn btn-xs btn-outline-danger" id="btnKonRemoveFile"><i class="fas fa-times"></i> Hapus File</button>
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-0">
                                <label class="small text-muted mb-1">Link Portal Ticketing / Referensi Konsultasi</label>
                                <input type="url" class="form-control form-control-sm" name="link_eksternal" id="kon_link_eksternal" placeholder="https://...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary font-weight-bold btn-rkc-save-konsultasi">
                        <i class="fas fa-save mr-1"></i> Simpan Flowchart Konsultasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL 5: FORM TAMBAH / EDIT GUIDANCE DOCUMENT
     ========================================================================= -->
<div class="modal fade" id="modalRkcGuidance" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header rkc-modal-header">
                <h5 class="modal-title font-weight-bold" id="modalRkcGuidanceTitle" style="font-size: 15px;">
                    <i class="fas fa-book-open mr-2 text-info"></i>Tambah Guidance Document
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form id="formRkcGuidance" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="id" id="gui_id" value="0">
                <input type="hidden" name="hapus_file" id="gui_hapus_file" value="0">
                <div class="modal-body p-4">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="font-weight-semibold small">Instansi Pemerintah <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="instansi" id="gui_instansi" required>
                                <option value="oss">OSS</option>
                                <option value="kemenkes">Kemenkes</option>
                                <option value="bapeten">BAPETEN</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="font-weight-semibold small">Kategori / Topik</label>
                            <input type="text" class="form-control form-control-sm" name="kategori" id="gui_kategori" list="rkcKatSuggestions" placeholder="Misal: KBLI & NIB, Izin Edar">
                            <datalist id="rkcKatSuggestions">
                                <option value="KBLI & NIB">
                                <option value="Perizinan Berusaha">
                                <option value="Izin Edar Alkes">
                                <option value="CDAKB & IDAK">
                                <option value="Perizinan Radiasi">
                                <option value="Panduan Umum">
                            </datalist>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-semibold small">Versi</label>
                            <input type="text" class="form-control form-control-sm" name="versi" id="gui_versi" value="v1.0" placeholder="v1.0">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-semibold small">Tgl Update</label>
                            <input type="date" class="form-control form-control-sm" name="tanggal_dokumen" id="gui_tanggal_dokumen" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-semibold small">Judul Guidance Document <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="judul" id="gui_judul" placeholder="Misal: Panduan Perubahan & Penambahan KBLI di OSS" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-semibold small">Deskripsi / Catatan Panduan</label>
                        <textarea class="form-control form-control-sm" name="deskripsi" id="gui_deskripsi" rows="3" placeholder="Penjelasan singkat isi dokumen panduan, cakupan, atau catatan pembaruan..."></textarea>
                    </div>

                    <div class="p-3 bg-light rounded border">
                        <div class="small font-weight-bold text-dark mb-2"><i class="fas fa-file-upload mr-1 text-primary"></i> File Dokumen Panduan (Upload File atau Tautan Eksternal)</div>
                        <div class="form-row">
                            <div class="form-group col-md-6 mb-md-0">
                                <label class="small text-muted mb-1">Upload File (PDF / Word / Excel / PPT / Gambar, maks. 10 MB)</label>
                                <input type="file" class="form-control-file small" name="file_dokumen" id="gui_file_dokumen" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.webp,.zip">
                                <div id="gui_existing_file_wrap" class="mt-2 d-none">
                                    <span class="badge badge-info mr-1" id="gui_existing_file_name"></span>
                                    <button type="button" class="btn btn-xs btn-outline-danger" id="btnGuiRemoveFile"><i class="fas fa-times"></i> Hapus File</button>
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-0">
                                <label class="small text-muted mb-1">Atau Tautan Eksternal (Google Drive / Portal Resmi)</label>
                                <input type="url" class="form-control form-control-sm" name="link_eksternal" id="gui_link_eksternal" placeholder="https://...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary font-weight-bold btn-rkc-save-guidance">
                        <i class="fas fa-save mr-1"></i> Simpan Guidance Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var BASE_URL        = '<?= rtrim(base_url(), '/') ?>/';
    var INSTANSI_AKTIF  = '<?= htmlspecialchars($instansi_aktif, ENT_QUOTES, 'UTF-8') ?>';
    var ACTIVE_TAB      = '<?= htmlspecialchars($active_tab, ENT_QUOTES, 'UTF-8') ?>';
    var TODAY_ISO       = '<?= date('Y-m-d') ?>';

    function escHtml(str) {
        return String(str == null ? '' : str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function fmtDateId(iso) {
        if (!iso || iso === '0000-00-00') return '-';
        var parts = String(iso).slice(0, 10).split('-');
        if (parts.length !== 3) return iso;
        var months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        var m = months[parseInt(parts[1], 10) - 1] || parts[1];
        return parts[2] + ' ' + m + ' ' + parts[0];
    }

    function appendCsrf(fd) {
        if (typeof token !== 'undefined' && token) {
            fd.append('csrf_token', token);
        }
        return fd;
    }

    function postDataWithCsrf(obj) {
        if (typeof token !== 'undefined' && token) {
            obj.csrf_token = token;
        }
        return obj;
    }

    function reloadOnTab(targetInstansi, targetTab) {
        var inst = targetInstansi || INSTANSI_AKTIF;
        var tab  = targetTab || ACTIVE_TAB || 'regulatory_update';
        window.location.href = BASE_URL + 'regulatory_knowledge/instansi/' + encodeURIComponent(inst) + '?tab=' + encodeURIComponent(tab);
    }

    // Sinkronisasi query string ?tab=... saat pindah tab
    $('#rkcSubmenuTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var t = $(e.target).data('rkc-tab');
        if (t) {
            ACTIVE_TAB = t;
            if (window.history && window.history.replaceState) {
                var newUrl = BASE_URL + 'regulatory_knowledge/instansi/' + encodeURIComponent(INSTANSI_AKTIF) + '?tab=' + encodeURIComponent(t);
                window.history.replaceState(null, '', newUrl);
            }
        }
    });

    // =========================================================================
    // FILTER & SEARCH (CLIENT-SIDE REALTIME)
    // =========================================================================
    function filterUpdates() {
        var q  = ($('#rkcSearchUpdate').val() || '').toLowerCase().trim();
        var st = ($('#rkcFilterStatusUpdate').val() || '').toLowerCase();
        var visible = 0;
        $('.rkc-update-row').each(function() {
            var $tr = $(this);
            var hay = String($tr.attr('data-search') || '');
            var rst = String($tr.attr('data-status') || '');
            var matchQ  = !q || hay.indexOf(q) !== -1;
            var matchSt = !st || rst === st;
            if (matchQ && matchSt) {
                $tr.show();
                visible++;
            } else {
                $tr.hide();
            }
        });
        $('#rkcEmptyFilterUpdate').toggleClass('d-none', visible > 0 || $('.rkc-update-row').length === 0);
    }
    $('#rkcSearchUpdate').on('input', filterUpdates);
    $('#rkcFilterStatusUpdate').on('change', filterUpdates);

    $('.rkc-layanan-filter-btn').on('click', function() {
        var $btn = $(this);
        $('.rkc-layanan-filter-btn').removeClass('btn-primary').addClass('btn-outline-secondary');
        $btn.removeClass('btn-outline-secondary').addClass('btn-primary');
        var lay = String($btn.attr('data-layanan') || '');
        var visible = 0;
        $('.rkc-konsultasi-item').each(function() {
            var itemLay = String($(this).attr('data-layanan') || '');
            var ok = !lay || itemLay === lay;
            $(this).toggle(ok);
            if (ok) visible++;
        });
        $('#rkcEmptyFilterKonsultasi').toggleClass('d-none', visible > 0 || $('.rkc-konsultasi-item').length === 0);
    });

    function filterGuidance() {
        var q   = ($('#rkcSearchGuidance').val() || '').toLowerCase().trim();
        var kat = ($('#rkcFilterKatGuidance').val() || '').toLowerCase();
        var visible = 0;
        $('.rkc-guidance-item').each(function() {
            var $el = $(this);
            var hay = String($el.attr('data-search') || '');
            var rk  = String($el.attr('data-kategori') || '');
            var ok  = (!q || hay.indexOf(q) !== -1) && (!kat || rk === kat);
            $el.toggle(ok);
            if (ok) visible++;
        });
        $('#rkcEmptyFilterGuidance').toggleClass('d-none', visible > 0 || $('.rkc-guidance-item').length === 0);
    }
    $('#rkcSearchGuidance').on('input', filterGuidance);
    $('#rkcFilterKatGuidance').on('change', filterGuidance);

    // Lightbox Zoom Gambar Flowchart
    $(document).on('click', '.btn-rkc-zoom-image', function() {
        var src   = $(this).attr('data-src');
        var title = $(this).attr('data-title') || 'Diagram Flowchart';
        $('#rkcImagePreviewImg').attr('src', src);
        $('#rkcImagePreviewTitle').text(title);
        $('#modalRkcImagePreview').modal('show');
    });

    // =========================================================================
    // DETAIL REGULATORY UPDATE
    // =========================================================================
    $(document).on('click', '.btn-rkc-detail-update', function() {
        var id = $(this).data('id');
        $('#rkcDetailUpdateBody').html('<div class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat detail peraturan...</div>');
        $('#modalRkcUpdateDetail').modal('show');

        $.getJSON(BASE_URL + 'regulatory_knowledge/ajax_get_update/' + id, function(resp) {
            if (!resp || resp.status !== 'success' || !resp.data) {
                $('#rkcDetailUpdateBody').html('<div class="alert alert-danger mb-0">' + escHtml(resp && resp.msg ? resp.msg : 'Gagal memuat detail.') + '</div>');
                return;
            }
            var d = resp.data;
            var statusMap = {
                berlaku: { label: 'Berlaku', cls: 'badge-success' },
                akan_berlaku: { label: 'Akan Berlaku', cls: 'badge-primary' },
                revisi: { label: 'Revisi / Perubahan', cls: 'badge-warning' },
                dicabut: { label: 'Dicabut / Tidak Berlaku', cls: 'badge-danger' }
            };
            var st = statusMap[d.status_peraturan] || statusMap.berlaku;
            var fileBtn = '';
            if (d.file_path) {
                var fUrl = /^https?:\/\//i.test(d.file_path) ? d.file_path : (BASE_URL + d.file_path.replace(/^\/+/, ''));
                fileBtn += '<a href="' + escHtml(fUrl) + '" target="_blank" rel="noopener" class="btn btn-sm btn-primary mr-2"><i class="fas fa-download mr-1"></i> Unduh Dokumen' + (d.file_nama_asli ? ' (' + escHtml(d.file_nama_asli) + ')' : '') + '</a>';
            }
            if (d.link_eksternal) {
                fileBtn += '<a href="' + escHtml(d.link_eksternal) + '" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary"><i class="fas fa-external-link-alt mr-1"></i> Buka Tautan Sumber</a>';
            }
            var html = ''
                + '<div class="d-flex align-items-center flex-wrap mb-2" style="gap:8px;">'
                + '  <span class="badge badge-dark text-uppercase">' + escHtml(d.instansi) + '</span>'
                + (d.nomor_peraturan ? '<span class="badge badge-info">' + escHtml(d.nomor_peraturan) + '</span>' : '')
                + '  <span class="badge ' + st.cls + '">' + escHtml(st.label) + '</span>'
                + '</div>'
                + '<h5 class="font-weight-bold text-dark mb-3">' + escHtml(d.judul) + '</h5>'
                + '<div class="row mb-3">'
                + '  <div class="col-sm-6 mb-2"><div class="p-2 bg-light rounded border"><div class="small text-muted">Tanggal Berlaku</div><div class="font-weight-bold text-dark"><i class="far fa-calendar-check text-primary mr-1"></i> ' + escHtml(fmtDateId(d.tanggal_berlaku)) + '</div></div></div>'
                + '  <div class="col-sm-6 mb-2"><div class="p-2 bg-light rounded border"><div class="small text-muted">Terakhir Diperbarui</div><div class="font-weight-bold text-dark"><i class="fas fa-user-edit text-muted mr-1"></i> ' + escHtml(d.updated_by_nama || d.created_by_nama || 'Divisi Regulatory') + '</div></div></div>'
                + '</div>'
                + '<div class="mb-3">'
                + '  <div class="small font-weight-bold text-uppercase text-muted mb-1">Ringkasan &amp; Poin Peraturan</div>'
                + '  <div class="p-3 bg-light rounded border" style="white-space: pre-line; font-size: 13.5px; line-height: 1.6;">' + escHtml(d.ringkasan || 'Tidak ada catatan ringkasan.') + '</div>'
                + '</div>'
                + (fileBtn ? '<div class="pt-2 border-top">' + fileBtn + '</div>' : '');

            $('#rkcDetailUpdateBody').html(html);
        }).fail(function() {
            $('#rkcDetailUpdateBody').html('<div class="alert alert-danger mb-0">Gagal menghubungi server.</div>');
        });
    });

    <?php if ($can_manage): ?>
    // =========================================================================
    // CRUD 1: REGULATORY UPDATE
    // =========================================================================
    $(document).on('click', '.btn-rkc-add-update', function() {
        $('#formRkcUpdate')[0].reset();
        $('#upd_id').val(0);
        $('#upd_hapus_file').val(0);
        $('#upd_instansi').val(INSTANSI_AKTIF);
        $('#upd_tanggal_berlaku').val(TODAY_ISO);
        $('#upd_status').val('berlaku');
        $('#upd_existing_file_wrap').addClass('d-none');
        $('#modalRkcUpdateTitle').html('<i class="fas fa-gavel mr-2 text-info"></i>Tambah Regulatory Update');
        $('#modalRkcUpdate').modal('show');
    });

    $(document).on('click', '.btn-rkc-edit-update', function() {
        var id = $(this).data('id');
        $.getJSON(BASE_URL + 'regulatory_knowledge/ajax_get_update/' + id, function(resp) {
            if (!resp || resp.status !== 'success' || !resp.data) {
                Swal.fire('Gagal', (resp && resp.msg) || 'Data tidak ditemukan.', 'error');
                return;
            }
            var d = resp.data;
            $('#formRkcUpdate')[0].reset();
            $('#upd_id').val(d.id);
            $('#upd_hapus_file').val(0);
            $('#upd_instansi').val(d.instansi || INSTANSI_AKTIF);
            $('#upd_nomor').val(d.nomor_peraturan || '');
            $('#upd_judul').val(d.judul || '');
            $('#upd_tanggal_berlaku').val(d.tanggal_berlaku || '');
            $('#upd_status').val(d.status_peraturan || 'berlaku');
            $('#upd_ringkasan').val(d.ringkasan || '');
            $('#upd_link_eksternal').val(d.link_eksternal || '');
            if (d.file_path) {
                $('#upd_existing_file_name').text(d.file_nama_asli || d.file_path.split('/').pop());
                $('#upd_existing_file_wrap').removeClass('d-none');
            } else {
                $('#upd_existing_file_wrap').addClass('d-none');
            }
            $('#modalRkcUpdateTitle').html('<i class="fas fa-pencil-alt mr-2 text-warning"></i>Edit Regulatory Update');
            $('#modalRkcUpdate').modal('show');
        });
    });

    $('#btnUpdRemoveFile').on('click', function() {
        $('#upd_hapus_file').val(1);
        $('#upd_existing_file_wrap').addClass('d-none');
    });

    $('#formRkcUpdate').on('submit', function(e) {
        e.preventDefault();
        var fd = appendCsrf(new FormData(this));
        var targetInst = $('#upd_instansi').val() || INSTANSI_AKTIF;
        Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, showConfirmButton: false, didOpen: function() { Swal.showLoading(); } });
        $.ajax({
            url: BASE_URL + 'regulatory_knowledge/ajax_save_update',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(resp) {
                if (resp && resp.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: resp.msg, timer: 1600, showConfirmButton: false })
                        .then(function() { reloadOnTab(targetInst, 'regulatory_update'); });
                } else {
                    Swal.fire('Gagal', (resp && resp.msg) || 'Gagal menyimpan data.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan saat menghubungi server.', 'error');
            }
        });
    });

    $(document).on('click', '.btn-rkc-delete-update', function() {
        var id = $(this).data('id');
        var judul = $(this).attr('data-judul') || 'data ini';
        Swal.fire({
            title: 'Hapus Regulatory Update?',
            text: 'Peraturan "' + judul + '" akan dihapus dari daftar.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(function(res) {
            if (res.isConfirmed || res.value) {
                $.post(BASE_URL + 'regulatory_knowledge/ajax_delete_update', postDataWithCsrf({ id: id }), function(resp) {
                    if (resp && resp.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Terhapus!', text: resp.msg, timer: 1300, showConfirmButton: false })
                            .then(function() { reloadOnTab(INSTANSI_AKTIF, 'regulatory_update'); });
                    } else {
                        Swal.fire('Gagal', (resp && resp.msg) || 'Gagal menghapus data.', 'error');
                    }
                }, 'json');
            }
        });
    });

    // =========================================================================
    // CRUD 2: KONSULTASI (INTERACTIVE FLOWCHART STEP BUILDER)
    // =========================================================================
    function createStepRowHtml(step) {
        step = step || { judul: '', deskripsi: '', estimasi: '', tipe: 'process' };
        var tipe = step.tipe || 'process';
        return ''
            + '<div class="rkc-builder-row">'
            + '  <div class="rkc-builder-row-header">'
            + '    <span class="badge badge-primary rkc-step-label" style="font-size: 11px;">Langkah</span>'
            + '    <div class="btn-group btn-group-xs">'
            + '      <button type="button" class="btn btn-xs btn-outline-secondary btn-rkc-step-up" title="Naik"><i class="fas fa-arrow-up"></i></button>'
            + '      <button type="button" class="btn btn-xs btn-outline-secondary btn-rkc-step-down" title="Turun"><i class="fas fa-arrow-down"></i></button>'
            + '      <button type="button" class="btn btn-xs btn-outline-danger btn-rkc-step-del" title="Hapus Langkah"><i class="fas fa-times"></i></button>'
            + '    </div>'
            + '  </div>'
            + '  <div class="form-row">'
            + '    <div class="form-group col-md-3 mb-2">'
            + '      <select class="form-control form-control-sm rkc-st-tipe">'
            + '        <option value="start"' + (tipe === 'start' ? ' selected' : '') + '>Mulai (Start)</option>'
            + '        <option value="process"' + (tipe === 'process' ? ' selected' : '') + '>Proses / Langkah</option>'
            + '        <option value="wait"' + (tipe === 'wait' ? ' selected' : '') + '>Menunggu / Jadwal Berlaku</option>'
            + '        <option value="decision"' + (tipe === 'decision' ? ' selected' : '') + '>Verifikasi / Keputusan</option>'
            + '        <option value="finish"' + (tipe === 'finish' ? ' selected' : '') + '>Selesai (Finish)</option>'
            + '      </select>'
            + '    </div>'
            + '    <div class="form-group col-md-5 mb-2">'
            + '      <input type="text" class="form-control form-control-sm rkc-st-judul" placeholder="Judul langkah (misal: Ambil Ticketing OSS)" value="' + escHtml(step.judul || '') + '">'
            + '    </div>'
            + '    <div class="form-group col-md-4 mb-2">'
            + '      <input type="text" class="form-control form-control-sm rkc-st-estimasi" placeholder="Estimasi / Waktu (misal: Hari H / H+7)" value="' + escHtml(step.estimasi || '') + '">'
            + '    </div>'
            + '  </div>'
            + '  <div class="form-group mb-0">'
            + '    <textarea class="form-control form-control-sm rkc-st-deskripsi" rows="2" placeholder="Keterangan detail yang harus dilakukan pada tahap ini...">' + escHtml(step.deskripsi || '') + '</textarea>'
            + '  </div>'
            + '</div>';
    }

    function refreshStepNumbers() {
        $('#rkcStepsContainer .rkc-builder-row').each(function(i) {
            $(this).find('.rkc-step-label').text('Langkah ' + (i + 1));
        });
    }

    function renderStepBuilder(steps) {
        var $c = $('#rkcStepsContainer').empty();
        if (!steps || !steps.length) {
            $c.append(createStepRowHtml({ tipe: 'start', judul: '', estimasi: '', deskripsi: '' }));
            $c.append(createStepRowHtml({ tipe: 'process', judul: '', estimasi: '', deskripsi: '' }));
            $c.append(createStepRowHtml({ tipe: 'finish', judul: '', estimasi: '', deskripsi: '' }));
        } else {
            steps.forEach(function(st) {
                $c.append(createStepRowHtml(st));
            });
        }
        refreshStepNumbers();
    }

    function collectStepsFromBuilder() {
        var result = [];
        $('#rkcStepsContainer .rkc-builder-row').each(function() {
            var $r = $(this);
            var judul = ($r.find('.rkc-st-judul').val() || '').trim();
            var desk  = ($r.find('.rkc-st-deskripsi').val() || '').trim();
            var est   = ($r.find('.rkc-st-estimasi').val() || '').trim();
            var tipe  = ($r.find('.rkc-st-tipe').val() || 'process').trim();
            if (judul !== '' || desk !== '') {
                result.push({ judul: judul, deskripsi: desk, estimasi: est, tipe: tipe });
            }
        });
        return result;
    }

    $('#btnRkcAddStep').on('click', function() {
        $('#rkcStepsContainer').append(createStepRowHtml({ tipe: 'process', judul: '', estimasi: '', deskripsi: '' }));
        refreshStepNumbers();
    });

    $(document).on('click', '.btn-rkc-step-del', function() {
        $(this).closest('.rkc-builder-row').remove();
        refreshStepNumbers();
    });

    $(document).on('click', '.btn-rkc-step-up', function() {
        var $row = $(this).closest('.rkc-builder-row');
        var $prev = $row.prev('.rkc-builder-row');
        if ($prev.length) {
            $row.insertBefore($prev);
            refreshStepNumbers();
        }
    });

    $(document).on('click', '.btn-rkc-step-down', function() {
        var $row = $(this).closest('.rkc-builder-row');
        var $next = $row.next('.rkc-builder-row');
        if ($next.length) {
            $row.insertAfter($next);
            refreshStepNumbers();
        }
    });

    $(document).on('click', '.btn-rkc-add-konsultasi', function() {
        $('#formRkcKonsultasi')[0].reset();
        $('#kon_id').val(0);
        $('#kon_hapus_file').val(0);
        $('#kon_instansi').val(INSTANSI_AKTIF);
        $('#kon_jenis_layanan').val('offline');
        $('#kon_status').val('aktif');
        $('#kon_urutan').val(1);
        $('#kon_existing_file_wrap').addClass('d-none');
        renderStepBuilder([]);
        $('#modalRkcKonsultasiTitle').html('<i class="fas fa-project-diagram mr-2 text-info"></i>Tambah Alur Konsultasi &amp; Flowchart');
        $('#modalRkcKonsultasi').modal('show');
    });

    $(document).on('click', '.btn-rkc-edit-konsultasi', function() {
        var id = $(this).data('id');
        $.getJSON(BASE_URL + 'regulatory_knowledge/ajax_get_konsultasi/' + id, function(resp) {
            if (!resp || resp.status !== 'success' || !resp.data) {
                Swal.fire('Gagal', (resp && resp.msg) || 'Data tidak ditemukan.', 'error');
                return;
            }
            var d = resp.data;
            $('#formRkcKonsultasi')[0].reset();
            $('#kon_id').val(d.id);
            $('#kon_hapus_file').val(0);
            $('#kon_instansi').val(d.instansi || INSTANSI_AKTIF);
            $('#kon_judul').val(d.judul || '');
            $('#kon_jenis_layanan').val(d.jenis_layanan || 'offline');
            $('#kon_status').val(d.status || 'aktif');
            $('#kon_estimasi_waktu').val(d.estimasi_waktu || '');
            $('#kon_ringkasan').val(d.ringkasan || '');
            $('#kon_catatan_penting').val(d.catatan_penting || '');
            $('#kon_urutan').val(d.urutan != null ? d.urutan : 1);
            $('#kon_link_eksternal').val(d.link_eksternal || '');
            if (d.flowchart_file) {
                $('#kon_existing_file_name').text(d.flowchart_nama_asli || d.flowchart_file.split('/').pop());
                $('#kon_existing_file_wrap').removeClass('d-none');
            } else {
                $('#kon_existing_file_wrap').addClass('d-none');
            }
            renderStepBuilder(Array.isArray(d.langkah) ? d.langkah : []);
            $('#modalRkcKonsultasiTitle').html('<i class="fas fa-pencil-alt mr-2 text-warning"></i>Edit Alur Konsultasi &amp; Flowchart');
            $('#modalRkcKonsultasi').modal('show');
        });
    });

    $('#btnKonRemoveFile').on('click', function() {
        $('#kon_hapus_file').val(1);
        $('#kon_existing_file_wrap').addClass('d-none');
    });

    $('#formRkcKonsultasi').on('submit', function(e) {
        e.preventDefault();
        var steps = collectStepsFromBuilder();
        $('#kon_langkah_json').val(JSON.stringify(steps));
        var fd = appendCsrf(new FormData(this));
        var targetInst = $('#kon_instansi').val() || INSTANSI_AKTIF;
        Swal.fire({ title: 'Menyimpan Flowchart...', allowOutsideClick: false, showConfirmButton: false, didOpen: function() { Swal.showLoading(); } });
        $.ajax({
            url: BASE_URL + 'regulatory_knowledge/ajax_save_konsultasi',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(resp) {
                if (resp && resp.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: resp.msg, timer: 1600, showConfirmButton: false })
                        .then(function() { reloadOnTab(targetInst, 'konsultasi'); });
                } else {
                    Swal.fire('Gagal', (resp && resp.msg) || 'Gagal menyimpan flowchart.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan saat menghubungi server.', 'error');
            }
        });
    });

    $(document).on('click', '.btn-rkc-delete-konsultasi', function() {
        var id = $(this).data('id');
        var judul = $(this).attr('data-judul') || 'alur ini';
        Swal.fire({
            title: 'Hapus Alur Konsultasi?',
            text: 'Flowchart "' + judul + '" akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(function(res) {
            if (res.isConfirmed || res.value) {
                $.post(BASE_URL + 'regulatory_knowledge/ajax_delete_konsultasi', postDataWithCsrf({ id: id }), function(resp) {
                    if (resp && resp.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Terhapus!', text: resp.msg, timer: 1300, showConfirmButton: false })
                            .then(function() { reloadOnTab(INSTANSI_AKTIF, 'konsultasi'); });
                    } else {
                        Swal.fire('Gagal', (resp && resp.msg) || 'Gagal menghapus data.', 'error');
                    }
                }, 'json');
            }
        });
    });

    // =========================================================================
    // CRUD 3: GUIDANCE DOCUMENT
    // =========================================================================
    $(document).on('click', '.btn-rkc-add-guidance', function() {
        $('#formRkcGuidance')[0].reset();
        $('#gui_id').val(0);
        $('#gui_hapus_file').val(0);
        $('#gui_instansi').val(INSTANSI_AKTIF);
        $('#gui_versi').val('v1.0');
        $('#gui_tanggal_dokumen').val(TODAY_ISO);
        $('#gui_existing_file_wrap').addClass('d-none');
        $('#modalRkcGuidanceTitle').html('<i class="fas fa-book-open mr-2 text-info"></i>Tambah Guidance Document');
        $('#modalRkcGuidance').modal('show');
    });

    $(document).on('click', '.btn-rkc-edit-guidance', function() {
        var id = $(this).data('id');
        $.getJSON(BASE_URL + 'regulatory_knowledge/ajax_get_guidance/' + id, function(resp) {
            if (!resp || resp.status !== 'success' || !resp.data) {
                Swal.fire('Gagal', (resp && resp.msg) || 'Data tidak ditemukan.', 'error');
                return;
            }
            var d = resp.data;
            $('#formRkcGuidance')[0].reset();
            $('#gui_id').val(d.id);
            $('#gui_hapus_file').val(0);
            $('#gui_instansi').val(d.instansi || INSTANSI_AKTIF);
            $('#gui_kategori').val(d.kategori || 'Panduan Umum');
            $('#gui_versi').val(d.versi || 'v1.0');
            $('#gui_tanggal_dokumen').val(d.tanggal_dokumen || TODAY_ISO);
            $('#gui_judul').val(d.judul || '');
            $('#gui_deskripsi').val(d.deskripsi || '');
            $('#gui_link_eksternal').val(d.link_eksternal || '');
            if (d.file_path) {
                $('#gui_existing_file_name').text(d.file_nama_asli || d.file_path.split('/').pop());
                $('#gui_existing_file_wrap').removeClass('d-none');
            } else {
                $('#gui_existing_file_wrap').addClass('d-none');
            }
            $('#modalRkcGuidanceTitle').html('<i class="fas fa-pencil-alt mr-2 text-warning"></i>Edit Guidance Document');
            $('#modalRkcGuidance').modal('show');
        });
    });

    $('#btnGuiRemoveFile').on('click', function() {
        $('#gui_hapus_file').val(1);
        $('#gui_existing_file_wrap').addClass('d-none');
    });

    $('#formRkcGuidance').on('submit', function(e) {
        e.preventDefault();
        var fd = appendCsrf(new FormData(this));
        var targetInst = $('#gui_instansi').val() || INSTANSI_AKTIF;
        Swal.fire({ title: 'Menyimpan Dokumen...', allowOutsideClick: false, showConfirmButton: false, didOpen: function() { Swal.showLoading(); } });
        $.ajax({
            url: BASE_URL + 'regulatory_knowledge/ajax_save_guidance',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(resp) {
                if (resp && resp.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: resp.msg, timer: 1600, showConfirmButton: false })
                        .then(function() { reloadOnTab(targetInst, 'guidance'); });
                } else {
                    Swal.fire('Gagal', (resp && resp.msg) || 'Gagal menyimpan dokumen.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan saat menghubungi server.', 'error');
            }
        });
    });

    $(document).on('click', '.btn-rkc-delete-guidance', function() {
        var id = $(this).data('id');
        var judul = $(this).attr('data-judul') || 'dokumen ini';
        Swal.fire({
            title: 'Hapus Guidance Document?',
            text: 'Dokumen "' + judul + '" akan dihapus dari daftar.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(function(res) {
            if (res.isConfirmed || res.value) {
                $.post(BASE_URL + 'regulatory_knowledge/ajax_delete_guidance', postDataWithCsrf({ id: id }), function(resp) {
                    if (resp && resp.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Terhapus!', text: resp.msg, timer: 1300, showConfirmButton: false })
                            .then(function() { reloadOnTab(INSTANSI_AKTIF, 'guidance'); });
                    } else {
                        Swal.fire('Gagal', (resp && resp.msg) || 'Gagal menghapus dokumen.', 'error');
                    }
                }, 'json');
            }
        });
    });
    <?php endif; ?>
});
</script>
