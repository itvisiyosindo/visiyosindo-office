<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('hp')) {
    function hp($s)
    {
        return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    }
}
$sizeClass  = ($size === '7x4') ? 'size-7x4' : 'size-8x5';
$themeParam = $this->input->get('theme', TRUE) ?: 'sapphire';
$themeClass = ($themeParam === 'silver') ? 'theme-silver' : 'theme-sapphire';
$idsParam   = trim((string)$this->input->get('ids', TRUE));

$buildPrintUrl = function ($newSize, $newTheme) use ($items, $idsParam) {
    $base = base_url('asset_passport/print_sticker');
    if ($idsParam !== '') {
        return $base . '?ids=' . urlencode($idsParam) . '&size=' . $newSize . '&theme=' . $newTheme;
    }
    if (count($items) === 1 && !empty($items[0]->id_passport)) {
        return $base . '/' . (int)$items[0]->id_passport . '?size=' . $newSize . '&theme=' . $newTheme;
    }
    return $base . '?size=' . $newSize . '&theme=' . $newTheme;
};
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Cetak Stiker QR Asset Passport — PT Visi Yosindo Medikal &amp; Visilab</title>
    <link rel="shortcut icon" href="<?= base_url('assets/img/favicon.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/all.min.css') ?>">
    <style>
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #e9eff6;
            color: #1e293b;
        }

        .print-toolbar {
            background: linear-gradient(135deg, #132a54 0%, #1e4282 100%);
            color: #fff;
            padding: 12px 22px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 20;
            border-bottom: 1px solid rgba(203, 213, 225, 0.45);
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.14);
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-tool {
            border: 1px solid rgba(255, 255, 255, 0.28);
            background: rgba(255, 255, 255, 0.1);
            color: #f8fafc;
            padding: 7px 13px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-tool:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .btn-tool.active {
            background: linear-gradient(135deg, #f8fafc 0%, #dbe4f0 100%);
            border-color: #ffffff;
            color: #132a54;
            font-weight: 700;
        }

        .btn-tool-primary {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            border-color: #93c5fd;
            color: #fff;
        }

        .sheet-wrap {
            max-width: 210mm;
            margin: 22px auto;
            background: #fff;
            padding: 10mm;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.1);
            display: flex;
            flex-wrap: wrap;
            gap: 6mm;
            align-content: flex-start;
        }

        /* ====== STICKER CARD: SOFT BLUE & SHIMMERING SILVER ====== */
        .sticker-card {
            position: relative;
            border-radius: 3.4mm;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-inside: avoid;
            break-inside: avoid;
            /* Brushed shimmering silver base */
            background: linear-gradient(135deg,
                #ffffff 0%,
                #f1f5f9 24%,
                #e3eaf3 45%,
                #ffffff 56%,
                #eaf0f7 78%,
                #dce5f0 100%);
            border: 0.35mm solid #94a3b8;
            box-shadow:
                inset 0 0 0 0.25mm rgba(255, 255, 255, 0.9),
                0 3px 12px rgba(15, 35, 70, 0.1);
        }

        .sticker-card.size-8x5 {
            width: 82mm;
            height: 52mm;
        }

        .sticker-card.size-7x4 {
            width: 72mm;
            height: 44mm;
        }

        /* Diagonal soft metallic sheen */
        .st-shimmer-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(118deg,
                rgba(255, 255, 255, 0) 22%,
                rgba(255, 255, 255, 0.55) 42%,
                rgba(219, 234, 254, 0.22) 50%,
                rgba(255, 255, 255, 0) 68%);
            pointer-events: none;
            z-index: 1;
        }

        /* Subtle watermark */
        .st-watermark {
            position: absolute;
            right: -3mm;
            bottom: 4mm;
            width: 28mm;
            height: 28mm;
            opacity: 0.04;
            pointer-events: none;
            z-index: 0;
        }

        /* Header */
        .st-header {
            position: relative;
            z-index: 2;
            background: linear-gradient(130deg, #132c5c 0%, #1d4489 55%, #2e5fae 100%);
            color: #fff;
            padding: 1.9mm 3mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2.2mm;
        }

        .theme-silver .st-header {
            background: linear-gradient(130deg, #e2e8f0 0%, #f8fafc 48%, #cbd5e1 100%);
            color: #132c5c;
        }

        .st-logos-pair {
            display: flex;
            align-items: center;
            gap: 1.2mm;
            background: rgba(255, 255, 255, 0.96);
            padding: 0.7mm 1.6mm;
            border-radius: 1.6mm;
            border: 0.2mm solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.12);
            flex-shrink: 0;
        }

        .size-7x4 .st-logos-pair {
            padding: 0.5mm 1.2mm;
        }

        .st-logos-pair img {
            height: 4.3mm;
            width: auto;
            display: block;
            object-fit: contain;
        }

        .size-7x4 .st-logos-pair img {
            height: 3.6mm;
        }

        .st-logo-divider {
            width: 0.2mm;
            height: 3.6mm;
            background: #cbd5e1;
        }

        .st-header-text {
            text-align: right;
            min-width: 0;
            flex: 1;
        }

        .st-brand-main {
            font-size: 6.1pt;
            font-weight: 700;
            letter-spacing: 0.35px;
            line-height: 1.15;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .st-brand-sub {
            font-size: 5pt;
            font-weight: 600;
            color: #dbeafe;
            letter-spacing: 0.7px;
            text-transform: uppercase;
            margin-top: 0.35mm;
        }

        .theme-silver .st-brand-sub {
            color: #3b5998;
        }

        .size-7x4 .st-brand-main {
            font-size: 5.3pt;
        }

        .size-7x4 .st-brand-sub {
            font-size: 4.3pt;
        }

        /* Shimmering Silver Hairline Ribbon */
        .st-silver-ribbon {
            position: relative;
            z-index: 2;
            height: 0.75mm;
            background: linear-gradient(90deg,
                #94a3b8 0%,
                #f8fafc 28%,
                #cbd5e1 50%,
                #ffffff 74%,
                #94a3b8 100%);
        }

        .st-subbar {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.68);
            border-bottom: 0.2mm solid rgba(148, 163, 184, 0.45);
            color: #1e3a6e;
            padding: 0.75mm 3mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 5.4pt;
            font-weight: 700;
            letter-spacing: 0.75px;
        }

        .size-7x4 .st-subbar {
            font-size: 4.7pt;
            padding: 0.6mm 2.4mm;
        }

        .st-subbar-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 5.2pt;
            color: #475569;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        /* Body */
        .st-body {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            align-items: center;
            gap: 3mm;
            padding: 2mm 3mm;
        }

        .size-7x4 .st-body {
            gap: 2.3mm;
            padding: 1.5mm 2.3mm;
        }

        .qr-frame {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #ffffff;
            border: 0.28mm solid #cbd5e1;
            border-radius: 2.2mm;
            padding: 1.2mm;
            box-shadow: 0 2px 6px rgba(15, 35, 70, 0.08);
        }

        .qr-box {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .size-8x5 .qr-box {
            width: 22mm;
            height: 22mm;
        }

        .size-7x4 .qr-box {
            width: 18mm;
            height: 18mm;
        }

        .qr-box canvas,
        .qr-box img {
            width: 100% !important;
            height: 100% !important;
            display: block;
        }

        .qr-scan-caption {
            margin-top: 0.65mm;
            font-size: 4.3pt;
            font-weight: 700;
            letter-spacing: 0.6px;
            color: #334e82;
            text-transform: uppercase;
        }

        .st-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .st-product-name {
            font-size: 8.1pt;
            font-weight: 700;
            color: #0f2347;
            line-height: 1.2;
            margin-bottom: 1.1mm;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .size-7x4 .st-product-name {
            font-size: 7pt;
            margin-bottom: 0.8mm;
        }

        .st-meta-line {
            font-size: 6pt;
            line-height: 1.38;
            color: #475569;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.45mm;
        }

        .size-7x4 .st-meta-line {
            font-size: 5.3pt;
        }

        .st-meta-label {
            color: #64748b;
            font-weight: 600;
        }

        .st-meta-val {
            color: #0f172a;
            font-weight: 700;
        }

        .st-sn-mono {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            color: #1e3a8a;
            background: rgba(255, 255, 255, 0.85);
            border: 0.2mm solid #cbd5e1;
            padding: 0.15mm 1.2mm;
            border-radius: 0.9mm;
        }

        .st-badge-row {
            margin-top: 1.1mm;
            display: flex;
            align-items: center;
        }

        .st-badge-verified {
            display: inline-flex;
            align-items: center;
            gap: 0.9mm;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            color: #1e40af;
            font-weight: 700;
            font-size: 5.2pt;
            letter-spacing: 0.4px;
            padding: 0.55mm 2mm;
            border-radius: 999px;
            border: 0.22mm solid #93c5fd;
        }

        .size-7x4 .st-badge-verified {
            font-size: 4.6pt;
            padding: 0.45mm 1.6mm;
        }

        /* Soft Brushed Silver Footer */
        .st-footer {
            position: relative;
            z-index: 2;
            background: linear-gradient(90deg, #dde6f2 0%, #f1f5f9 50%, #dde6f2 100%);
            border-top: 0.2mm solid rgba(148, 163, 184, 0.55);
            color: #334155;
            text-align: center;
            font-size: 5.1pt;
            font-weight: 600;
            padding: 0.95mm 2mm;
            letter-spacing: 0.15px;
        }

        .st-footer strong {
            color: #1e3a8a;
            font-weight: 700;
        }

        .size-7x4 .st-footer {
            font-size: 4.5pt;
            padding: 0.75mm 1.5mm;
        }

        @media print {
            body {
                background: #fff;
            }
            .print-toolbar {
                display: none !important;
            }
            .sheet-wrap {
                margin: 0;
                padding: 4mm;
                box-shadow: none;
                max-width: none;
            }
            @page {
                size: A4 portrait;
                margin: 6mm;
            }
        }
    </style>
</head>

<body>
    <div class="print-toolbar">
        <div>
            <strong><i class="fas fa-qrcode"></i> Cetak Stiker QR Digital Asset Passport</strong>
            <span style="font-size:12px; opacity:0.85; margin-left:8px;">(<?= count($items) ?> label siap cetak)</span>
        </div>
        <div class="toolbar-actions">
            <a href="<?= hp($buildPrintUrl('8x5', $themeParam)) ?>" class="btn-tool <?= $size !== '7x4' ? 'active' : '' ?>">8.2 × 5.2 cm</a>
            <a href="<?= hp($buildPrintUrl('7x4', $themeParam)) ?>" class="btn-tool <?= $size === '7x4' ? 'active' : '' ?>">7.2 × 4.4 cm</a>
            <a href="<?= hp($buildPrintUrl($size, 'sapphire')) ?>" class="btn-tool <?= $themeParam !== 'silver' ? 'active' : '' ?>">Soft Blue Silver</a>
            <a href="<?= hp($buildPrintUrl($size, 'silver')) ?>" class="btn-tool <?= $themeParam === 'silver' ? 'active' : '' ?>">Full Brushed Silver</a>
            <button type="button" class="btn-tool btn-tool-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Print / Simpan PDF
            </button>
            <a href="<?= base_url('asset_passport') ?>" class="btn-tool">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="sheet-wrap">
        <?php if (empty($items)): ?>
            <div style="padding:30px; text-align:center; width:100%; color:#64748b;">
                Tidak ada data stiker QR untuk dicetak.
            </div>
        <?php else: ?>
            <?php foreach ($items as $idx => $item): ?>
                <div class="sticker-card <?= $sizeClass ?> <?= $themeClass ?>">
                    <div class="st-shimmer-overlay"></div>
                    <img src="<?= base_url('assets/img/passport/logo_vym.png') ?>" class="st-watermark" alt="">

                    <div>
                        <div class="st-header">
                            <div class="st-logos-pair">
                                <img src="<?= base_url('assets/img/passport/logo_vym.png') ?>" alt="VYM" title="PT Visi Yosindo Medikal">
                                <span class="st-logo-divider"></span>
                                <img src="<?= base_url('assets/img/passport/logo_visilab.png') ?>" alt="Visilab" title="Visilab">
                            </div>
                            <div class="st-header-text">
                                <div class="st-brand-main">PT VISI YOSINDO MEDIKAL</div>
                                <div class="st-brand-sub">VISILAB • DIGITAL ASSET PASSPORT</div>
                            </div>
                        </div>
                        <div class="st-silver-ribbon"></div>
                        <div class="st-subbar">
                            <span>VERIFIED MEDICAL EQUIPMENT</span>
                            <span class="st-subbar-code"><?= hp($item->kode_passport) ?></span>
                        </div>
                    </div>

                    <div class="st-body">
                        <div class="qr-frame">
                            <div class="qr-box" id="qr-holder-<?= $idx ?>" data-url="<?= hp($item->public_url) ?>"></div>
                            <div class="qr-scan-caption">SCAN TO VERIFY</div>
                        </div>

                        <div class="st-info">
                            <div class="st-product-name"><?= hp($item->nama_produk) ?></div>
                            <div class="st-meta-line">
                                <span class="st-meta-label">S/N:</span>
                                <span class="st-sn-mono"><?= hp($item->serial_number ?: '-') ?></span>
                            </div>
                            <div class="st-meta-line">
                                <span class="st-meta-label">Instansi:</span>
                                <span class="st-meta-val"><?= hp($item->nama_instansi) ?></span>
                            </div>
                            <div class="st-meta-line">
                                <span class="st-meta-label">Berlaku s/d:</span>
                                <span class="st-meta-val"><?= hp($item->earliest_exp_label) ?></span>
                            </div>
                            <div class="st-badge-row">
                                <span class="st-badge-verified">
                                    <i class="fas fa-check-circle"></i> TERVERIFIKASI RESMI
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="st-footer">
                        <strong>Scan QR:</strong> Izin BAPETEN • LUK • Kalibrasi • Riwayat &amp; Lapor Servis
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        (function() {
            var holders = document.querySelectorAll('[id^="qr-holder-"]');
            holders.forEach(function(el) {
                var url = el.getAttribute('data-url');
                if (typeof QRCode !== 'undefined') {
                    new QRCode(el, {
                        text: url,
                        width: 180,
                        height: 180,
                        colorDark: '#132c5c',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.M
                    });
                } else {
                    var img = document.createElement('img');
                    img.src = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + encodeURIComponent(url);
                    img.alt = 'QR Code';
                    el.appendChild(img);
                }
            });
        })();
    </script>
</body>

</html>
