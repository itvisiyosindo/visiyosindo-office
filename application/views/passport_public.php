<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('h')) {
    function h($str)
    {
        return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
    }
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= $found ? h($passport->nama_produk) . ' — Digital Asset Passport' : 'QR Asset Passport — PT Visi Yosindo Medikal' ?></title>
    <link rel="shortcut icon" href="<?= base_url('assets/img/favicon.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/js/sweetalert2/sweetalert2.min.css') ?>">
    <style>
        :root {
            --navy: #142d59;
            --navy-mid: #1e4282;
            --accent: #2563eb;
            --accent-soft: #eff6ff;
            --bg: #f4f7fb;
            --card-bg: #ffffff;
            --text: #0f172a;
            --text-soft: #334155;
            --muted: #64748b;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            padding-bottom: 96px;
        }

        .passport-topbar {
            background: linear-gradient(135deg, #0f2448 0%, #1b3f7d 60%, #2656a3 100%);
            color: #fff;
            padding: 20px 16px 64px;
            position: relative;
        }

        .passport-topbar::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 2px;
            background: linear-gradient(90deg, rgba(203, 213, 225, 0.15) 0%, rgba(248, 250, 252, 0.7) 50%, rgba(203, 213, 225, 0.15) 100%);
        }

        .brand-row {
            max-width: 720px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .brand-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logos-pair {
            display: flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, 0.97);
            border-radius: 11px;
            padding: 6px 10px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.16);
        }

        .brand-logos-pair img {
            height: 25px;
            width: auto;
            display: block;
            object-fit: contain;
        }

        .brand-logo-sep {
            width: 1px;
            height: 20px;
            background: #cbd5e1;
        }

        .brand-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 700;
            letter-spacing: 0.2px;
            line-height: 1.25;
            color: #ffffff;
        }

        .brand-sub {
            font-size: 10px;
            color: #bfdbfe;
            font-weight: 600;
            letter-spacing: 0.9px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .verified-chip {
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #f8fafc;
            font-size: 11px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 999px;
            white-space: nowrap;
            letter-spacing: 0.4px;
            backdrop-filter: blur(8px);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .verified-chip i {
            color: #93c5fd;
        }

        .passport-container {
            max-width: 720px;
            margin: -42px auto 0;
            padding: 0 16px;
            position: relative;
            z-index: 2;
        }

        .p-card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid #e4eaf3;
            box-shadow: 0 4px 20px rgba(15, 35, 70, 0.045);
            margin-bottom: 16px;
            overflow: hidden;
        }

        .p-card-body {
            padding: 20px;
        }

        .p-card-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin: 0 0 16px;
            display: flex;
            align-items: center;
            gap: 9px;
            padding-bottom: 11px;
            border-bottom: 1px solid #f1f5f9;
        }

        .p-card-title i {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #eff6ff;
            color: #1d4ed8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        /* Product Hero */
        .product-hero {
            display: flex;
            gap: 18px;
            align-items: center;
        }

        .product-photo-box {
            width: 120px;
            height: 120px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            flex-shrink: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 6px;
        }

        .product-photo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 10px;
            cursor: zoom-in;
        }

        .photo-zoom-hint {
            position: absolute;
            right: 8px;
            bottom: 8px;
            width: 24px;
            height: 24px;
            border-radius: 7px;
            background: rgba(15, 23, 42, 0.65);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            pointer-events: none;
        }

        .product-photo-placeholder {
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
            padding: 8px;
        }

        .product-photo-placeholder i {
            font-size: 28px;
            display: block;
            margin-bottom: 4px;
            color: #cbd5e1;
        }

        .product-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 19px;
            font-weight: 800;
            color: var(--text);
            margin: 0 0 4px;
            line-height: 1.28;
            letter-spacing: -0.2px;
        }

        .product-meta {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 10px;
            font-weight: 500;
        }

        .sn-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            border: 1px solid #dbe3ee;
            color: #1e293b;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11.5px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 8px;
            margin-right: 6px;
            margin-bottom: 6px;
        }

        .sn-pill i {
            color: #2563eb;
            font-size: 12px;
        }

        .instansi-grid {
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .instansi-item {
            background: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: 10px;
            padding: 10px 12px;
        }

        .instansi-item span {
            display: block;
            color: var(--muted);
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .instansi-item strong {
            display: block;
            color: var(--text);
            font-size: 13px;
            font-weight: 700;
            line-height: 1.35;
        }

        /* Compliance Rows - Calm Pastel SaaS Style */
        .comp-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 15px;
            border-radius: 12px;
            border: 1px solid #e5ebf3;
            border-left: 3.5px solid #94a3b8;
            background: #ffffff;
            margin-bottom: 10px;
            transition: background 0.15s;
        }

        .comp-item:last-child {
            margin-bottom: 0;
        }

        .comp-item.st-valid {
            border-left-color: #10b981;
            background: #fcfefd;
        }

        .comp-item.st-warning {
            border-left-color: #f59e0b;
            background: #fffdf7;
        }

        .comp-item.st-expired {
            border-left-color: #ef4444;
            background: #fffafa;
        }

        .comp-label {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text);
        }

        .comp-date {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
        }

        .comp-date strong {
            color: var(--text-soft);
            font-weight: 600;
        }

        .comp-badge {
            font-size: 11.5px;
            font-weight: 600;
            padding: 5px 11px;
            border-radius: 999px;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .comp-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .comp-badge.st-valid { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .comp-badge.st-valid::before { background: #10b981; }

        .comp-badge.st-warning { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .comp-badge.st-warning::before { background: #f59e0b; }

        .comp-badge.st-expired { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .comp-badge.st-expired::before { background: #ef4444; }

        .comp-badge.st-none { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
        .comp-badge.st-none::before { background: #94a3b8; }

        /* Office Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: 10px;
            padding: 11px 13px;
        }

        .info-box small {
            display: block;
            font-size: 11px;
            color: var(--muted);
            font-weight: 600;
            margin-bottom: 3px;
        }

        .info-box div {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            word-break: break-word;
        }

        .doc-btn-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
        }

        .btn-doc {
            flex: 1 1 180px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none !important;
            border: 1px solid #cbd5e1;
            color: #1e3a8a;
            background: #f8fafc;
            transition: all 0.18s ease;
        }

        .btn-doc:hover {
            background: #eff6ff;
            border-color: #93c5fd;
            color: #1d4ed8;
        }

        /* Timeline Service */
        .timeline-list {
            position: relative;
            padding-left: 22px;
        }

        .timeline-list::before {
            content: '';
            position: absolute;
            left: 6px;
            top: 8px;
            bottom: 8px;
            width: 1.5px;
            background: #e2e8f0;
        }

        .tl-item {
            position: relative;
            padding-bottom: 14px;
        }

        .tl-item:last-child {
            padding-bottom: 0;
        }

        .tl-dot {
            position: absolute;
            left: -22px;
            top: 6px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: #2563eb;
            border: 3px solid #fff;
            box-shadow: 0 0 0 1px #bfdbfe;
        }

        .tl-card {
            background: #f8fafc;
            border: 1px solid #e7edf5;
            border-radius: 12px;
            padding: 13px 15px;
        }

        .tl-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 6px;
        }

        .tl-title {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text);
        }

        .tl-meta {
            font-size: 11.5px;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .tl-desc {
            font-size: 12.5px;
            color: #334155;
            white-space: pre-line;
            margin: 0;
            line-height: 1.5;
        }

        .passport-footer-note {
            text-align: center;
            font-size: 11.5px;
            color: #64748b;
            padding: 6px 0 14px;
        }

        /* Sticky CTA Bottom - Clean & Calm */
        .sticky-action-bar {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(10px);
            border-top: 1px solid #e2e8f0;
            padding: 11px 16px;
            z-index: 50;
            box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.05);
        }

        .sticky-inner {
            max-width: 720px;
            margin: 0 auto;
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
        }

        .sticky-help-text {
            font-size: 12px;
            color: #475569;
            line-height: 1.3;
        }

        .sticky-help-text strong {
            display: block;
            color: #0f172a;
            font-size: 12.5px;
        }

        .btn-lapor {
            background: #be123c;
            color: #fff;
            border: 1px solid #9f1239;
            border-radius: 10px;
            padding: 11px 18px;
            font-size: 13.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.18s ease, transform 0.1s ease;
        }

        .btn-lapor:hover {
            background: #9f1239;
        }

        .honeypot-field {
            position: absolute;
            left: -9999px;
            opacity: 0;
            height: 0;
            width: 0;
        }

        @media (max-width: 560px) {
            .product-hero {
                flex-direction: column;
                align-items: flex-start;
            }
            .product-photo-box {
                width: 100%;
                height: 185px;
            }
            .instansi-grid,
            .info-grid {
                grid-template-columns: 1fr;
            }
            .comp-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .sticky-help-text {
                display: none;
            }
            .btn-lapor {
                width: 100%;
                padding: 12px 16px;
            }
        }
    </style>
</head>

<body>
    <header class="passport-topbar">
        <div class="brand-row">
            <div class="brand-left">
                <div class="brand-logos-pair">
                    <img src="<?= base_url('assets/img/passport/logo_vym.png') ?>" alt="PT Visi Yosindo Medikal">
                    <span class="brand-logo-sep"></span>
                    <img src="<?= base_url('assets/img/passport/logo_visilab.png') ?>" alt="Visilab">
                </div>
                <div>
                    <div class="brand-sub">Digital Asset Passport</div>
                    <div class="brand-title">PT Visi Yosindo Medikal • Visilab</div>
                </div>
            </div>
            <?php if ($found): ?>
                <span class="verified-chip"><i class="fas fa-check-circle"></i> TERVERIFIKASI</span>
            <?php endif; ?>
        </div>
    </header>

    <main class="passport-container">
        <?php if (!$found): ?>
            <div class="p-card">
                <div class="p-card-body text-center py-5">
                    <i class="fas fa-qrcode fa-3x text-muted mb-3"></i>
                    <h4 class="font-weight-bold mb-2">QR Asset Passport Tidak Ditemukan</h4>
                    <p class="text-muted mb-0" style="font-size:13.5px;">
                        Kode QR alat tidak terdaftar atau telah dinonaktifkan. Silakan hubungi tim PT Visi Yosindo Medikal / Visilab untuk verifikasi lebih lanjut.
                    </p>
                </div>
            </div>
        <?php else: ?>
            <?php
            $compItems = $passport->compliance_summary['items'] ?? [];
            $hasDocs   = !empty($passport->link_lhu) || !empty($passport->link_sertifikat);
            $hasOfficeInfo = !empty($passport->kode_uji_terakhir)
                || !empty($passport->jenis_uji_terakhir)
                || !empty($passport->live_status_perizinan)
                || !empty($passport->live_uji_status_label)
                || !empty($passport->info_tambahan);
            ?>

            <!-- 1. Identitas & Foto Produk -->
            <section class="p-card">
                <div class="p-card-body">
                    <div class="product-hero">
                        <div class="product-photo-box">
                            <?php if (!empty($passport->foto_url)): ?>
                                <img src="<?= h($passport->foto_url) ?>" alt="<?= h($passport->nama_produk) ?>" onclick="previewImageModal('<?= h($passport->foto_url) ?>')">
                                <span class="photo-zoom-hint"><i class="fas fa-search-plus"></i></span>
                            <?php else: ?>
                                <div class="product-photo-placeholder">
                                    <i class="fas fa-x-ray"></i>
                                    Foto Produk
                                </div>
                            <?php endif; ?>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <h1 class="product-name"><?= h($passport->nama_produk) ?></h1>
                            <?php if (!empty($passport->merk_tipe)): ?>
                                <div class="product-meta"><?= h($passport->merk_tipe) ?></div>
                            <?php endif; ?>
                            <div>
                                <span class="sn-pill">
                                    <i class="fas fa-barcode"></i>
                                    S/N: <?= h($passport->serial_number ?: '-') ?>
                                </span>
                                <span class="sn-pill">
                                    <i class="fas fa-id-badge"></i>
                                    <?= h($passport->kode_passport) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="instansi-grid">
                        <div class="instansi-item">
                            <span>Instansi / Fasyankes</span>
                            <strong><i class="fas fa-hospital text-primary mr-1"></i> <?= h($passport->nama_instansi) ?></strong>
                        </div>
                        <?php if (!empty($passport->lokasi_ruangan)): ?>
                            <div class="instansi-item">
                                <span>Lokasi / Ruangan</span>
                                <strong><i class="fas fa-map-marker-alt text-primary mr-1"></i> <?= h($passport->lokasi_ruangan) ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- 2. Masa Aktif Kepatuhan (Izin BAPETEN, LUK, Kalibrasi - Tanpa TLD) -->
            <section class="p-card">
                <div class="p-card-body">
                    <h2 class="p-card-title">
                        <i class="fas fa-shield-alt"></i> Masa Aktif Perizinan & Kepatuhan Alat
                    </h2>

                    <?php foreach (['izin_bapeten', 'luk', 'kalibrasi'] as $key): ?>
                        <?php if (isset($compItems[$key])): $it = $compItems[$key]; ?>
                            <div class="comp-item st-<?= h($it['code']) ?>">
                                <div>
                                    <div class="comp-label"><?= h($it['label']) ?></div>
                                    <div class="comp-date">Berlaku s/d: <strong><?= h($it['date_fmt']) ?></strong></div>
                                </div>
                                <span class="comp-badge st-<?= h($it['code']) ?>">
                                    <?= h($it['status_text']) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- 3. Informasi & Dokumen Resmi dari Office (Non-TLD) -->
            <?php if ($hasOfficeInfo || $hasDocs): ?>
                <section class="p-card">
                    <div class="p-card-body">
                        <h2 class="p-card-title">
                            <i class="fas fa-file-medical-alt"></i> Data Pengujian & Dokumen Resmi Office
                        </h2>

                        <?php if ($hasOfficeInfo): ?>
                            <div class="info-grid">
                                <?php if (!empty($passport->kode_uji_terakhir)): ?>
                                    <div class="info-box">
                                        <small>No. Register Pengujian</small>
                                        <div><?= h($passport->kode_uji_terakhir) ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($passport->jenis_uji_terakhir)): ?>
                                    <div class="info-box">
                                        <small>Jenis Pengujian Terakhir</small>
                                        <div><?= h($passport->jenis_uji_terakhir) ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($passport->live_uji_status_label)): ?>
                                    <div class="info-box">
                                        <small>Progres Uji Visilab</small>
                                        <div><?= h($passport->live_uji_status_label) ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($passport->live_status_perizinan) && $passport->live_status_perizinan !== '-'): ?>
                                    <div class="info-box">
                                        <small>Status Perizinan Office</small>
                                        <div><?= h($passport->live_status_perizinan) ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($passport->info_tambahan)): ?>
                                    <?php foreach ($passport->info_tambahan as $ext): ?>
                                        <div class="info-box">
                                            <small><?= h($ext['label']) ?></small>
                                            <div><?= h($ext['value']) ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($hasDocs): ?>
                            <div class="doc-btn-row">
                                <?php if (!empty($passport->link_sertifikat)): ?>
                                    <a href="<?= h($passport->link_sertifikat) ?>" target="_blank" rel="noopener" class="btn-doc">
                                        <i class="fas fa-certificate"></i> Unduh Sertifikat Resmi
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($passport->link_lhu)): ?>
                                    <a href="<?= h($passport->link_lhu) ?>" target="_blank" rel="noopener" class="btn-doc">
                                        <i class="fas fa-file-pdf"></i> Unduh Dokumen LHU
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- 4. Riwayat Service & Perawatan -->
            <section class="p-card">
                <div class="p-card-body">
                    <h2 class="p-card-title">
                        <i class="fas fa-tools"></i> Riwayat Service & Perawatan
                    </h2>

                    <?php if (empty($service_history)): ?>
                        <div class="text-center py-3 text-muted" style="font-size:13px;">
                            <i class="fas fa-clipboard-check fa-2x mb-2 text-black-50 d-block"></i>
                            Belum ada riwayat kerusakan atau service yang tercatat pada unit ini.
                        </div>
                    <?php else: ?>
                        <div class="timeline-list">
                            <?php foreach ($service_history as $srv): ?>
                                <div class="tl-item">
                                    <span class="tl-dot"></span>
                                    <div class="tl-card">
                                        <div class="tl-head">
                                            <div>
                                                <div class="tl-title"><?= h($srv['judul']) ?></div>
                                                <div class="tl-meta">
                                                    <i class="far fa-calendar-alt mr-1"></i> <?= h($srv['tanggal_fmt']) ?>
                                                    • <?= h($srv['jenis']) ?>
                                                    <?php if (!empty($srv['kode'])): ?>
                                                        • <strong><?= h($srv['kode']) ?></strong>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <span class="badge badge-<?= h($srv['status_badge']) ?>" style="font-size:11px; padding:4px 8px;">
                                                <?= h($srv['status_label']) ?>
                                            </span>
                                        </div>
                                        <?php if (!empty($srv['tindakan'])): ?>
                                            <p class="tl-desc"><?= h($srv['tindakan']) ?></p>
                                        <?php endif; ?>
                                        <div class="mt-2 d-flex justify-content-between align-items-center flex-wrap" style="font-size:11.5px; color:#64748b; gap:6px;">
                                            <span><i class="fas fa-user-cog mr-1"></i> PIC: <?= h($srv['teknisi']) ?></span>
                                            <?php if (!empty($srv['lampiran'])): ?>
                                                <a href="<?= h($srv['lampiran']) ?>" target="_blank" rel="noopener" class="text-info font-weight-bold">
                                                    <i class="fas fa-paperclip"></i> Lihat Lampiran
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <div class="passport-footer-note">
                Dokumen Digital Terverifikasi &bull; PT Visi Yosindo Medikal &amp; Visilab
            </div>

            <!-- Tombol Lapor Kerusakan Sticky di Bawah -->
            <div class="sticky-action-bar">
                <div class="sticky-inner">
                    <div class="sticky-help-text">
                        <strong>Ada kendala pada unit ini?</strong>
                        Laporan langsung terhubung ke tim teknisi resmi.
                    </div>
                    <button type="button" class="btn-lapor" data-toggle="modal" data-target="#modalLaporKerusakan">
                        <i class="fas fa-tools"></i> Lapor Kerusakan / Panggil Teknisi
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <?php if ($found): ?>
        <!-- Modal Form Lapor Kerusakan (Tanpa OTP) - Ditaruh langsung di bawah body agar di atas backdrop -->
        <div class="modal fade" id="modalLaporKerusakan" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:1060;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius:16px; border:0;">
                    <div class="modal-header" style="background:var(--navy); color:#fff; border-radius:16px 16px 0 0;">
                        <h5 class="modal-title font-weight-bold" style="font-size:15px;">
                            <i class="fas fa-tools mr-2"></i>Lapor Kerusakan Alat
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formLaporKerusakan" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="qr_token" value="<?= h($passport->qr_token) ?>">
                            <input type="text" name="website_url" class="honeypot-field" tabindex="-1" autocomplete="off">

                            <div class="alert alert-light border mb-3" style="font-size:12.5px; border-radius:10px;">
                                <strong><?= h($passport->nama_produk) ?></strong><br>
                                <span class="text-muted">S/N: <?= h($passport->serial_number ?: '-') ?> • <?= h($passport->nama_instansi) ?></span>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold small">Nama Pelapor <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pelapor" class="form-control" placeholder="Misal: Budi (Radiologi / IPSRS)" required>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold small">No. WhatsApp Aktif <span class="text-danger">*</span></label>
                                <input type="tel" name="no_wa" class="form-control" placeholder="0812xxxxxxxx" required>
                                <small class="text-muted">Nomor tiket & konfirmasi akan dikirim ke WhatsApp ini.</small>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-8">
                                    <label class="font-weight-bold small">Jenis Kendala <span class="text-danger">*</span></label>
                                    <input type="text" name="judul_keluhan" class="form-control" placeholder="Misal: Alat mati / Error eksposi" required>
                                </div>
                                <div class="form-group col-4">
                                    <label class="font-weight-bold small">Urgensi</label>
                                    <select name="prioritas" class="form-control">
                                        <option value="3" selected>High</option>
                                        <option value="4">Urgent</option>
                                        <option value="2">Medium</option>
                                        <option value="1">Low</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold small">Detail Kerusakan <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" rows="3" class="form-control" placeholder="Jelaskan gejala kerusakan, kode error yang muncul, atau kondisi alat saat ini..." required></textarea>
                            </div>

                            <div class="form-group mb-0">
                                <label class="font-weight-bold small">Foto / Lampiran Kendala (Opsional)</label>
                                <input type="file" name="foto_kerusakan" class="form-control-file" accept="image/*,.webp,.jfif,.avif,.heic,.heif,.bmp,.tiff,.pdf">
                                <small class="text-muted">Mendukung JPG, PNG, WEBP, JFIF, AVIF, HEIC, BMP, atau PDF.</small>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger font-weight-bold" id="btnSubmitLapor">
                                <i class="fas fa-paper-plane mr-1"></i> Kirim Laporan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="<?= base_url('assets/vendor/jquery/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/popper/umd/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.js') ?>"></script>
    <script src="<?= base_url('assets/js/sweetalert2/sweetalert2.min.js') ?>"></script>
    <script>
        function previewImageModal(url) {
            Swal.fire({
                imageUrl: url,
                imageAlt: 'Foto Produk',
                showConfirmButton: false,
                showCloseButton: true,
                width: 600
            });
        }

        function compressPublicImage(file, maxDim, quality) {
            return new Promise(function(resolve) {
                if (!file || !file.type || file.type.indexOf('image/') !== 0 || file.size <= 700 * 1024) {
                    resolve(file);
                    return;
                }
                var img = new Image();
                var reader = new FileReader();
                reader.onload = function(ev) {
                    img.onload = function() {
                        var w = img.width;
                        var h = img.height;
                        if (w > maxDim || h > maxDim) {
                            if (w > h) {
                                h = Math.round(h * maxDim / w);
                                w = maxDim;
                            } else {
                                w = Math.round(w * maxDim / h);
                                h = maxDim;
                            }
                        }
                        var canvas = document.createElement('canvas');
                        canvas.width = w;
                        canvas.height = h;
                        canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                        canvas.toBlob(function(blob) {
                            if (blob && blob.size < file.size) {
                                resolve(new File([blob], (file.name || 'kendala').replace(/\.[^.]+$/, '') + '.jpg', { type: 'image/jpeg' }));
                            } else {
                                resolve(file);
                            }
                        }, 'image/jpeg', quality);
                    };
                    img.onerror = function() { resolve(file); };
                    img.src = ev.target.result;
                };
                reader.onerror = function() { resolve(file); };
                reader.readAsDataURL(file);
            });
        }

        $('#formLaporKerusakan').on('submit', function(e) {
            e.preventDefault();
            var formEl = this;
            var $btn = $('#btnSubmitLapor');
            var origHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...');

            var fileInput = formEl.querySelector('input[name="foto_kerusakan"]');
            var rawFile = (fileInput && fileInput.files && fileInput.files[0]) ? fileInput.files[0] : null;

            compressPublicImage(rawFile, 1400, 0.82).then(function(finalFile) {
                var formData = new FormData(formEl);
                if (finalFile) {
                    formData.set('foto_kerusakan', finalFile, finalFile.name || 'kendala.jpg');
                }
                $.ajax({
                    url: window.location.origin + '/passport/lapor_kerusakan',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(res) {
                        $btn.prop('disabled', false).html(origHtml);
                        if (res && res.status) {
                            $('#modalLaporKerusakan').modal('hide');
                            formEl.reset();
                            Swal.fire({
                                icon: 'success',
                                title: 'Laporan Terkirim!',
                                html: 'Tiket Anda berhasil dicatat dengan nomor:<br><strong style="font-size:18px;color:#0f2744;">' + (res.kode_tiket || '-') + '</strong><br><br><small>Konfirmasi telah dikirim ke WhatsApp Anda dan tim teknisi kami segera menindaklanjuti.</small>',
                                confirmButtonColor: '#0d9488'
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Perhatian', (res && res.message) ? res.message : 'Gagal mengirim laporan.', 'warning');
                        }
                    },
                    error: function() {
                        $btn.prop('disabled', false).html(origHtml);
                        Swal.fire('Error', 'Terjadi gangguan jaringan. Silakan coba kembali.', 'error');
                    }
                });
            });
        });
    </script>
</body>

</html>
