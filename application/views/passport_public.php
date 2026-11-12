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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/js/sweetalert2/sweetalert2.min.css') ?>">
    <style>
        :root {
            --navy: #143061;
            --navy-light: #254f96;
            --teal: #2563eb;
            --teal-soft: #dbeafe;
            --bg: #eef3f9;
            --card-bg: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --border: #d8e2ef;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(180deg, #e7eef7 0%, #f4f7fb 35%, #eef3f9 100%);
            color: var(--text);
            padding-bottom: 92px;
        }

        .passport-topbar {
            background: linear-gradient(135deg, #112852 0%, #1d4282 55%, #2b5cb0 100%);
            color: #fff;
            padding: 18px 16px 60px;
            position: relative;
            border-bottom: 2px solid #cbd5e1;
            box-shadow: 0 6px 24px rgba(17, 40, 82, 0.18);
        }

        .brand-row {
            max-width: 680px;
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
            gap: 6px;
        }

        .brand-logo-wrap {
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
            border-radius: 10px;
            padding: 5px 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 3px 10px rgba(15, 23, 42, 0.14);
            height: 40px;
        }

        .brand-logo-wrap img {
            height: 28px;
            width: auto;
            display: block;
            object-fit: contain;
        }

        .brand-title {
            font-size: 13.5px;
            font-weight: 700;
            letter-spacing: 0.3px;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 10.5px;
            color: #dbeafe;
            font-weight: 600;
            letter-spacing: 0.75px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .verified-chip {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.22) 0%, rgba(226, 232, 240, 0.16) 100%);
            border: 1px solid rgba(255, 255, 255, 0.55);
            color: #f8fafc;
            font-size: 11px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 999px;
            white-space: nowrap;
            letter-spacing: 0.45px;
            backdrop-filter: blur(6px);
        }

        .passport-container {
            max-width: 680px;
            margin: -40px auto 0;
            padding: 0 14px;
            position: relative;
            z-index: 2;
        }

        .p-card {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 18px;
            border: 1px solid #d5dfec;
            box-shadow: 0 10px 28px rgba(17, 40, 82, 0.06);
            margin-bottom: 16px;
            overflow: hidden;
        }

        .p-card-body {
            padding: 18px;
        }

        .p-card-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--navy);
            margin: 0 0 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .p-card-title i {
            color: var(--teal);
        }

        /* Product Hero */
        .product-hero {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .product-photo-box {
            width: 112px;
            height: 112px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: #f8fafc;
            flex-shrink: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: zoom-in;
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
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
            margin: 0 0 4px;
            line-height: 1.3;
        }

        .product-meta {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .sn-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #1e293b;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            margin-right: 6px;
            margin-bottom: 6px;
        }

        .instansi-strip {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px dashed var(--border);
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: space-between;
            font-size: 12.5px;
        }

        .instansi-item strong {
            display: block;
            color: var(--text);
            font-weight: 700;
        }

        .instansi-item span {
            color: var(--muted);
            font-size: 11.5px;
        }

        /* Compliance Rows */
        .comp-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #f8fafc;
            margin-bottom: 10px;
        }

        .comp-item:last-child {
            margin-bottom: 0;
        }

        .comp-item.st-valid {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .comp-item.st-warning {
            background: #fffbeb;
            border-color: #fde68a;
        }

        .comp-item.st-expired {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .comp-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
        }

        .comp-date {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
        }

        .comp-badge {
            font-size: 11.5px;
            font-weight: 700;
            padding: 5px 10px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .comp-badge.st-valid { background: #16a34a; color: #fff; }
        .comp-badge.st-warning { background: #d97706; color: #fff; }
        .comp-badge.st-expired { background: #dc2626; color: #fff; }
        .comp-badge.st-none { background: #94a3b8; color: #fff; }

        /* Office Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 12px;
        }

        .info-box small {
            display: block;
            font-size: 11px;
            color: var(--muted);
            font-weight: 600;
            margin-bottom: 2px;
        }

        .info-box div {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
            word-break: break-word;
        }

        .doc-btn-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 12px;
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
            font-weight: 700;
            text-decoration: none !important;
            border: 1px solid #0d9488;
            color: #0f766e;
            background: #f0fdfa;
            transition: all 0.2s;
        }

        .btn-doc:hover {
            background: #0d9488;
            color: #fff;
        }

        /* Timeline Service */
        .timeline-list {
            position: relative;
            padding-left: 20px;
        }

        .timeline-list::before {
            content: '';
            position: absolute;
            left: 6px;
            top: 6px;
            bottom: 6px;
            width: 2px;
            background: #e2e8f0;
        }

        .tl-item {
            position: relative;
            padding-bottom: 16px;
        }

        .tl-item:last-child {
            padding-bottom: 0;
        }

        .tl-dot {
            position: absolute;
            left: -20px;
            top: 4px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--teal);
            border: 3px solid #fff;
            box-shadow: 0 0 0 1px var(--teal);
        }

        .tl-card {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 14px;
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
        }

        /* Sticky CTA Bottom */
        .sticky-action-bar {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(8px);
            border-top: 1px solid var(--border);
            padding: 12px 14px;
            z-index: 50;
            box-shadow: 0 -6px 20px rgba(15, 23, 42, 0.08);
        }

        .sticky-inner {
            max-width: 680px;
            margin: 0 auto;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn-lapor {
            flex: 1;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 13px 18px;
            font-size: 14.5px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(220, 38, 38, 0.32);
        }

        .btn-lapor:hover {
            filter: brightness(1.05);
        }

        .honeypot-field {
            position: absolute;
            left: -9999px;
            opacity: 0;
            height: 0;
            width: 0;
        }

        @media (max-width: 520px) {
            .product-hero {
                flex-direction: column;
                align-items: flex-start;
            }
            .product-photo-box {
                width: 100%;
                height: 190px;
            }
            .info-grid {
                grid-template-columns: 1fr;
            }
            .comp-item {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <header class="passport-topbar">
        <div class="brand-row">
            <div class="brand-left">
                <div class="brand-logos-pair">
                    <div class="brand-logo-wrap">
                        <img src="<?= base_url('assets/img/passport/logo_vym.png') ?>" alt="PT Visi Yosindo Medikal">
                    </div>
                    <div class="brand-logo-wrap">
                        <img src="<?= base_url('assets/img/passport/logo_visilab.png') ?>" alt="Visilab">
                    </div>
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
                                    <i class="fas fa-barcode text-primary"></i>
                                    S/N: <?= h($passport->serial_number ?: '-') ?>
                                </span>
                                <span class="sn-pill">
                                    <i class="fas fa-id-badge text-info"></i>
                                    <?= h($passport->kode_passport) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="instansi-strip">
                        <div class="instansi-item">
                            <span>Instansi / Fasyankes</span>
                            <strong><i class="fas fa-hospital text-info mr-1"></i> <?= h($passport->nama_instansi) ?></strong>
                        </div>
                        <?php if (!empty($passport->lokasi_ruangan)): ?>
                            <div class="instansi-item">
                                <span>Lokasi / Ruangan</span>
                                <strong><i class="fas fa-map-marker-alt text-danger mr-1"></i> <?= h($passport->lokasi_ruangan) ?></strong>
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

            <!-- Tombol Lapor Kerusakan Sticky di Bawah -->
            <div class="sticky-action-bar">
                <div class="sticky-inner">
                    <button type="button" class="btn-lapor" data-toggle="modal" data-target="#modalLaporKerusakan">
                        <i class="fas fa-exclamation-triangle"></i> Lapor Kerusakan / Panggil Teknisi
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
