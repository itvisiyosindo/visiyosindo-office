<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('hp')) {
    function hp($s)
    {
        return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    }
}
$sizeClass  = ($size === '7x4') ? 'size-7x4' : 'size-8x5';
$themeParam = $this->input->get('theme', TRUE) ?: 'luxury';
$themeClass = ($themeParam === 'platinum') ? 'theme-platinum' : 'theme-luxury';
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
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/all.min.css') ?>">
    <style>
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            background: #e2e8f0;
            color: #0f172a;
        }

        .print-toolbar {
            background: linear-gradient(135deg, #091730 0%, #143266 100%);
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
            border-bottom: 2px solid #eab308;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.22);
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-tool {
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            padding: 7px 13px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-tool:hover {
            background: rgba(255, 255, 255, 0.16);
        }

        .btn-tool.active {
            background: linear-gradient(135deg, #ca8a04 0%, #eab308 100%);
            border-color: #fde047;
            color: #091730;
        }

        .btn-tool-primary {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border-color: #34d399;
            color: #fff;
        }

        .sheet-wrap {
            max-width: 210mm;
            margin: 22px auto;
            background: #fff;
            padding: 10mm;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.14);
            display: flex;
            flex-wrap: wrap;
            gap: 6mm;
            align-content: flex-start;
        }

        /* ====== STICKER CARD LUXURY ====== */
        .sticker-card {
            position: relative;
            border-radius: 3.4mm;
            overflow: hidden;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-inside: avoid;
            break-inside: avoid;
            border: 0.45mm solid #1e3a8a;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.12);
        }

        .sticker-card.size-8x5 {
            width: 82mm;
            height: 52mm;
        }

        .sticker-card.size-7x4 {
            width: 72mm;
            height: 44mm;
        }

        /* Watermark background */
        .st-watermark {
            position: absolute;
            right: -4mm;
            bottom: 4mm;
            width: 30mm;
            height: 30mm;
            opacity: 0.045;
            pointer-events: none;
            z-index: 0;
        }

        /* Top Header */
        .st-header {
            position: relative;
            z-index: 1;
            background: linear-gradient(125deg, #081734 0%, #122d63 60%, #193f85 100%);
            color: #fff;
            padding: 1.8mm 2.8mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2mm;
        }

        .theme-platinum .st-header {
            background: linear-gradient(125deg, #f8fafc 0%, #e2e8f0 100%);
            color: #0b1f44;
        }

        .st-logos-pair {
            display: flex;
            align-items: center;
            gap: 1.4mm;
            flex-shrink: 0;
        }

        .st-logo-pill {
            background: #ffffff;
            border-radius: 1.5mm;
            padding: 0.5mm 1.2mm;
            height: 6.2mm;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.22);
            border: 0.2mm solid rgba(234, 179, 8, 0.55);
        }

        .size-7x4 .st-logo-pill {
            height: 5.2mm;
            padding: 0.4mm 1mm;
        }

        .st-logo-pill img {
            height: 4.8mm;
            width: auto;
            display: block;
            object-fit: contain;
        }

        .size-7x4 .st-logo-pill img {
            height: 4mm;
        }

        .st-header-text {
            text-align: right;
            min-width: 0;
            flex: 1;
        }

        .st-brand-main {
            font-size: 6.4pt;
            font-weight: 800;
            letter-spacing: 0.25px;
            line-height: 1.15;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .st-brand-sub {
            font-size: 5pt;
            font-weight: 700;
            color: #facc15;
            letter-spacing: 0.55px;
            text-transform: uppercase;
            margin-top: 0.3mm;
        }

        .theme-platinum .st-brand-sub {
            color: #b45309;
        }

        .size-7x4 .st-brand-main {
            font-size: 5.6pt;
        }

        .size-7x4 .st-brand-sub {
            font-size: 4.4pt;
        }

        /* Metallic Gold Accent Ribbon */
        .st-gold-bar {
            position: relative;
            z-index: 1;
            height: 0.9mm;
            background: linear-gradient(90deg, #b45309 0%, #facc15 35%, #fef08a 50%, #eab308 75%, #b45309 100%);
        }

        .st-subbar {
            position: relative;
            z-index: 1;
            background: #f1f5f9;
            border-bottom: 0.25mm solid #cbd5e1;
            color: #0f2744;
            padding: 0.8mm 2.8mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 5.7pt;
            font-weight: 800;
            letter-spacing: 0.65px;
        }

        .size-7x4 .st-subbar {
            font-size: 5pt;
            padding: 0.6mm 2.4mm;
        }

        .st-subbar-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 5.4pt;
            color: #1e3a8a;
            font-weight: 700;
        }

        /* Body */
        .st-body {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            align-items: center;
            gap: 2.8mm;
            padding: 2mm 2.8mm;
        }

        .size-7x4 .st-body {
            gap: 2.2mm;
            padding: 1.5mm 2.2mm;
        }

        .qr-frame {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #fff;
            border: 0.35mm solid #1e3a8a;
            border-radius: 2.2mm;
            padding: 1.1mm;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.08);
        }

        .qr-box {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .size-8x5 .qr-box {
            width: 22.5mm;
            height: 22.5mm;
        }

        .size-7x4 .qr-box {
            width: 18.5mm;
            height: 18.5mm;
        }

        .qr-box canvas,
        .qr-box img {
            width: 100% !important;
            height: 100% !important;
            display: block;
        }

        .qr-scan-caption {
            margin-top: 0.7mm;
            font-size: 4.5pt;
            font-weight: 800;
            letter-spacing: 0.55px;
            color: #0b1f44;
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
            font-size: 8.2pt;
            font-weight: 800;
            color: #091730;
            line-height: 1.18;
            margin-bottom: 1.1mm;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .size-7x4 .st-product-name {
            font-size: 7.1pt;
            margin-bottom: 0.8mm;
        }

        .st-meta-line {
            font-size: 6.3pt;
            line-height: 1.35;
            color: #334155;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.45mm;
        }

        .size-7x4 .st-meta-line {
            font-size: 5.5pt;
        }

        .st-meta-line strong {
            color: #0f172a;
            font-weight: 700;
        }

        .st-sn-mono {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            color: #1e3a8a;
            background: #eff6ff;
            border: 0.2mm solid #bfdbfe;
            padding: 0.15mm 1.1mm;
            border-radius: 0.9mm;
        }

        .st-badge-row {
            margin-top: 1mm;
            display: flex;
            align-items: center;
            gap: 1.2mm;
            flex-wrap: wrap;
        }

        .st-badge-verified {
            display: inline-flex;
            align-items: center;
            gap: 0.8mm;
            background: linear-gradient(135deg, #047857 0%, #10b981 100%);
            color: #fff;
            font-weight: 800;
            font-size: 5.4pt;
            letter-spacing: 0.35px;
            padding: 0.65mm 2mm;
            border-radius: 999px;
            border: 0.2mm solid #6ee7b7;
        }

        .size-7x4 .st-badge-verified {
            font-size: 4.8pt;
            padding: 0.5mm 1.6mm;
        }

        /* Footer */
        .st-footer {
            position: relative;
            z-index: 1;
            background: #0b1f44;
            color: #e2e8f0;
            text-align: center;
            font-size: 5.3pt;
            font-weight: 600;
            padding: 0.95mm 2mm;
            letter-spacing: 0.15px;
        }

        .st-footer span {
            color: #facc15;
            font-weight: 800;
        }

        .theme-platinum .st-footer {
            background: #f1f5f9;
            color: #334155;
            border-top: 0.25mm solid #cbd5e1;
        }

        .theme-platinum .st-footer span {
            color: #1e3a8a;
        }

        .size-7x4 .st-footer {
            font-size: 4.6pt;
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
            <strong><i class="fas fa-award text-warning"></i> Cetak Stiker QR Digital Asset Passport</strong>
            <span style="font-size:12px; opacity:0.85; margin-left:8px;">(<?= count($items) ?> label siap cetak)</span>
        </div>
        <div class="toolbar-actions">
            <a href="<?= hp($buildPrintUrl('8x5', $themeParam)) ?>" class="btn-tool <?= $size !== '7x4' ? 'active' : '' ?>">8.2 × 5.2 cm</a>
            <a href="<?= hp($buildPrintUrl('7x4', $themeParam)) ?>" class="btn-tool <?= $size === '7x4' ? 'active' : '' ?>">7.2 × 4.4 cm</a>
            <a href="<?= hp($buildPrintUrl($size, 'luxury')) ?>" class="btn-tool <?= $themeParam !== 'platinum' ? 'active' : '' ?>">Navy Gold</a>
            <a href="<?= hp($buildPrintUrl($size, 'platinum')) ?>" class="btn-tool <?= $themeParam === 'platinum' ? 'active' : '' ?>">Platinum White</a>
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
                    <img src="<?= base_url('assets/img/passport/logo_vym.png') ?>" class="st-watermark" alt="">

                    <div>
                        <div class="st-header">
                            <div class="st-logos-pair">
                                <div class="st-logo-pill" title="PT Visi Yosindo Medikal">
                                    <img src="<?= base_url('assets/img/passport/logo_vym.png') ?>" alt="VYM">
                                </div>
                                <div class="st-logo-pill" title="Visilab">
                                    <img src="<?= base_url('assets/img/passport/logo_visilab.png') ?>" alt="Visilab">
                                </div>
                            </div>
                            <div class="st-header-text">
                                <div class="st-brand-main">PT VISI YOSINDO MEDIKAL</div>
                                <div class="st-brand-sub">VISILAB • SAFE RADIATION, SAFE LIFE</div>
                            </div>
                        </div>
                        <div class="st-gold-bar"></div>
                        <div class="st-subbar">
                            <span>DIGITAL ASSET PASSPORT</span>
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
                                <strong>S/N:</strong>
                                <span class="st-sn-mono"><?= hp($item->serial_number ?: '-') ?></span>
                            </div>
                            <div class="st-meta-line">
                                <strong>INSTANSI:</strong> <?= hp($item->nama_instansi) ?>
                            </div>
                            <div class="st-meta-line">
                                <strong>BERLAKU S/D:</strong> <strong><?= hp($item->earliest_exp_label) ?></strong>
                            </div>
                            <div class="st-badge-row">
                                <span class="st-badge-verified">
                                    <i class="fas fa-check-circle"></i> TERVERIFIKASI RESMI
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="st-footer">
                        <span>SCAN QR:</span> Izin BAPETEN • LUK • Kalibrasi • Riwayat &amp; Lapor Servis
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
                        colorDark: '#091730',
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
