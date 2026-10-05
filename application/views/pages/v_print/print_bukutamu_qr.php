<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Standee QR Code Buku Tamu - <?= html_escape($kegiatan->nama ?? 'Event') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/all.min.css') ?>" />
    <link rel="shortcut icon" href="<?= base_url('assets/img/favicon.png') ?>" />

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #eef3f8;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .action-bar {
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-print {
            background: #0056b3;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 86, 179, 0.3);
        }

        .btn-print:hover {
            background: #004494;
            transform: translateY(-2px);
        }

        .btn-download {
            background: #10b981;
            color: #fff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-download:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .btn-close {
            background: #64748b;
            color: #fff;
        }

        .btn-close:hover {
            background: #475569;
        }

        /* Standee Paper Container */
        .standee-card {
            background: #ffffff;
            width: 100%;
            max-width: 580px;
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 2px solid #e2e8f0;
        }

        /* Top decorative accent bar */
        .standee-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(90deg, #0056b3 0%, #00a8cc 50%, #10b981 100%);
        }

        .logo-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .logo-box img {
            height: 58px;
            width: auto;
            object-fit: contain;
        }

        .company-info h3 {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin: 0;
        }

        .company-info p {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
            font-weight: 500;
        }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 16px auto 22px;
            width: 85%;
        }

        .title-badge {
            display: inline-block;
            background: #f1f5f9;
            color: #0056b3;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 6px 16px;
            border-radius: 20px;
            margin-bottom: 12px;
            border: 1px solid #cbd5e1;
        }

        .event-title {
            font-size: 24px;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.25;
            margin-bottom: 20px;
            padding: 0 10px;
        }

        .qr-frame-wrapper {
            background: linear-gradient(145deg, #f8fafc, #edf2f7);
            border: 3px dashed #0056b3;
            border-radius: 22px;
            padding: 22px;
            display: inline-block;
            margin-bottom: 24px;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .qr-image {
            width: 250px;
            height: 250px;
            display: block;
            border-radius: 12px;
            background: #ffffff;
            padding: 8px;
        }

        .scan-instruction {
            font-size: 16px;
            font-weight: 800;
            color: #0056b3;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .steps-container {
            display: flex;
            justify-content: space-around;
            gap: 10px;
            background: #f8fafc;
            border-radius: 14px;
            padding: 14px 10px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        .step-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .step-icon {
            width: 32px;
            height: 32px;
            background: #e0f2fe;
            color: #0284c7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .step-text {
            font-size: 10.5px;
            font-weight: 600;
            color: #334155;
            line-height: 1.3;
        }

        .footer-url {
            font-size: 11px;
            color: #64748b;
            word-break: break-all;
            background: #f1f5f9;
            padding: 8px 14px;
            border-radius: 8px;
            font-family: monospace;
            display: inline-block;
            max-width: 100%;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .action-bar {
                display: none !important;
            }

            .standee-card {
                box-shadow: none;
                border: 1px solid #94a3b8;
                max-width: 100%;
                width: 100%;
                padding: 30px 24px;
                border-radius: 14px;
                page-break-inside: avoid;
            }

            .qr-image {
                width: 260px;
                height: 260px;
            }
        }
    </style>
</head>

<body>

    <div class="action-bar">
        <button class="btn btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak / Print Standee
        </button>
        <a href="<?= $qr_image_url ?>" download="QR_BukuTamu_<?= html_escape($kegiatan->id) ?>.png" class="btn btn-download" target="_blank">
            <i class="fas fa-download"></i> Download QR Image
        </a>
        <button class="btn btn-close" onclick="window.close()">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

    <div class="standee-card">
        <div class="logo-box">
            <img src="<?= base_url('assets/img/logovym2023.png') ?>" alt="Logo PT Visi Yosindo Medikal">
            <div class="company-info" style="text-align: left;">
                <h3>PT VISI YOSINDO MEDIKAL</h3>
                <p>Medical Equipment & Healthcare Solutions</p>
            </div>
        </div>

        <div class="divider"></div>

        <div>
            <span class="title-badge">
                <i class="fas fa-book-reader"></i> BUKU TAMU DIGITAL (GUEST BOOK)
            </span>
        </div>

        <h1 class="event-title">
            <?= html_escape($kegiatan->nama ?? 'Event Exhibition') ?>
        </h1>

        <div class="qr-frame-wrapper">
            <img src="<?= $qr_image_url ?>" alt="QR Code Buku Tamu" class="qr-image">
        </div>

        <div class="scan-instruction">
            <i class="fas fa-camera"></i> SCAN QR CODE UNTUK MENGISI
        </div>

        <div class="steps-container">
            <div class="step-item">
                <div class="step-icon">1</div>
                <div class="step-text">Buka Kamera HP / QR Scanner</div>
            </div>
            <div class="step-item">
                <div class="step-icon">2</div>
                <div class="step-text">Arahkan ke QR Code di atas</div>
            </div>
            <div class="step-item">
                <div class="step-icon">3</div>
                <div class="step-text">Isi Data Buku Tamu & Selesai</div>
            </div>
        </div>

        <div class="footer-url">
            🔗 Link Alternatif: <?= html_escape($target_url) ?>
        </div>
    </div>

</body>

</html>
