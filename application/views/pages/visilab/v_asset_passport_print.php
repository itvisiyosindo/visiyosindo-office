<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('hp')) {
    function hp($s)
    {
        return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    }
}
$sizeClass = ($size === '7x4') ? 'size-7x4' : 'size-8x5';
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Cetak Stiker QR Asset Passport — PT Visi Yosindo Medikal</title>
    <link rel="shortcut icon" href="<?= base_url('assets/img/favicon.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
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
            background: #0f2744;
            color: #fff;
            padding: 12px 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 20;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-tool {
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-tool.active {
            background: #0d9488;
            border-color: #0d9488;
        }

        .btn-tool-primary {
            background: #10b981;
            border-color: #10b981;
            color: #fff;
        }

        .sheet-wrap {
            max-width: 210mm;
            margin: 20px auto;
            background: #fff;
            padding: 10mm;
            box-shadow: 0 6px 24px rgba(15, 23, 42, 0.12);
            display: flex;
            flex-wrap: wrap;
            gap: 6mm;
            align-content: flex-start;
        }

        .sticker-card {
            border: 1px solid #cbd5e1;
            border-radius: 3mm;
            overflow: hidden;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .sticker-card.size-8x5 {
            width: 80mm;
            height: 50mm;
        }

        .sticker-card.size-7x4 {
            width: 70mm;
            height: 42mm;
        }

        .st-header {
            background: #0f2744;
            color: #fff;
            padding: 1.8mm 3mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2mm;
        }

        .st-header-left {
            display: flex;
            align-items: center;
            gap: 2mm;
        }

        .st-logo {
            background: #fff;
            border-radius: 1.2mm;
            padding: 0.6mm 1.2mm;
            height: 5mm;
            display: flex;
            align-items: center;
        }

        .st-logo img {
            height: 3.8mm;
            width: auto;
        }

        .st-brand-txt {
            font-size: 7.2pt;
            font-weight: 800;
            letter-spacing: 0.2px;
        }

        .st-subbar {
            background: #0d9488;
            color: #fff;
            text-align: center;
            font-size: 6.8pt;
            font-weight: 800;
            letter-spacing: 0.8px;
            padding: 0.9mm 2mm;
        }

        .st-body {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 3mm;
            padding: 2mm 3mm;
        }

        .qr-box {
            border: 1px solid #cbd5e1;
            border-radius: 2mm;
            padding: 1.2mm;
            background: #fff;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .size-8x5 .qr-box {
            width: 24mm;
            height: 24mm;
        }

        .size-7x4 .qr-box {
            width: 20mm;
            height: 20mm;
        }

        .qr-box canvas,
        .qr-box img {
            width: 100% !important;
            height: 100% !important;
            display: block;
        }

        .st-info {
            flex: 1;
            min-width: 0;
            font-size: 7pt;
            line-height: 1.32;
        }

        .size-7x4 .st-info {
            font-size: 6.2pt;
            line-height: 1.26;
        }

        .st-row {
            margin-bottom: 1mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .st-row strong {
            color: #0f2744;
        }

        .st-badge {
            display: inline-block;
            background: #16a34a;
            color: #fff;
            font-weight: 800;
            font-size: 6pt;
            padding: 0.5mm 2mm;
            border-radius: 999px;
            margin-top: 0.3mm;
        }

        .st-footer {
            background: #f1f5f9;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 5.8pt;
            font-weight: 700;
            color: #334155;
            padding: 1mm 2mm;
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
            <strong><i class="fas fa-qrcode"></i> Cetak Stiker QR Asset Passport</strong>
            <span style="font-size:12px; opacity:0.8; margin-left:8px;">(<?= count($items) ?> label siap cetak)</span>
        </div>
        <div class="toolbar-actions">
            <a href="<?= base_url('asset_passport/print_sticker' . (!empty($_GET['ids']) ? '?ids=' . urlencode($_GET['ids']) . '&size=8x5' : (count($items) === 1 ? '/' . $items[0]->id_passport . '?size=8x5' : '?size=8x5'))) ?>"
               class="btn-tool <?= $size !== '7x4' ? 'active' : '' ?>">Ukuran 8 × 5 cm</a>
            <a href="<?= base_url('asset_passport/print_sticker' . (!empty($_GET['ids']) ? '?ids=' . urlencode($_GET['ids']) . '&size=7x4' : (count($items) === 1 ? '/' . $items[0]->id_passport . '?size=7x4' : '?size=7x4'))) ?>"
               class="btn-tool <?= $size === '7x4' ? 'active' : '' ?>">Ukuran 7 × 4 cm</a>
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
                <div class="sticker-card <?= $sizeClass ?>">
                    <div>
                        <div class="st-header">
                            <div class="st-header-left">
                                <div class="st-logo">
                                    <img src="<?= base_url('assets/img/logovym2023.png') ?>" alt="VYM">
                                </div>
                                <span class="st-brand-txt">VISILAB • PT VISI YOSINDO MEDIKAL</span>
                            </div>
                        </div>
                        <div class="st-subbar">DIGITAL ASSET PASSPORT</div>
                    </div>

                    <div class="st-body">
                        <div class="qr-box" id="qr-holder-<?= $idx ?>" data-url="<?= hp($item->public_url) ?>"></div>
                        <div class="st-info">
                            <div class="st-row"><strong>ALAT:</strong> <?= hp($item->nama_produk) ?></div>
                            <div class="st-row"><strong>S/N:</strong> <?= hp($item->serial_number ?: '-') ?></div>
                            <div class="st-row"><strong>RS/INSTANSI:</strong> <?= hp($item->nama_instansi) ?></div>
                            <div class="st-row"><strong>BERLAKU S/D:</strong> <?= hp($item->earliest_exp_label) ?></div>
                            <div><span class="st-badge">TERVERIFIKASI ✓</span></div>
                        </div>
                    </div>

                    <div class="st-footer">
                        Scan QR untuk Izin BAPETEN, LUK, Kalibrasi, Riwayat &amp; Lapor Servis
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
                        width: 160,
                        height: 160,
                        colorDark: '#0f172a',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.M
                    });
                } else {
                    var img = document.createElement('img');
                    img.src = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' + encodeURIComponent(url);
                    img.alt = 'QR Code';
                    el.appendChild(img);
                }
            });
        })();
    </script>
</body>

</html>
