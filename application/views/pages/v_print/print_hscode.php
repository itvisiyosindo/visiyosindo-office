<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !empty($title_pdf) ? $title_pdf : 'Daftar HS Code - PT Visi Yosindo Medikal' ?></title>
    <style>
        @page {
            size: landscape;
            margin: 10mm 12mm 12mm 12mm;
        }

        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            margin: 0;
            padding: 15px 20px;
            color: #1e293b;
            background-color: #fff;
        }

        .header-kop {
            width: 100%;
            text-align: center;
            margin-bottom: 12px;
        }

        .header-kop img {
            width: 100%;
            max-height: 120px;
            object-fit: contain;
        }

        .doc-title-box {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
        }

        .doc-title-box h2 {
            margin: 0 0 4px 0;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }

        .doc-meta {
            font-size: 10px;
            color: #64748b;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
            line-height: 1.35;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #94a3b8;
            padding: 6px 8px;
            vertical-align: top;
        }

        table.data-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        table.data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .whitespace-pre { white-space: pre-line; word-break: break-word; }

        .link-badge {
            color: #1d4ed8;
            text-decoration: none;
            word-break: break-all;
            font-size: 9px;
        }

        .link-badge:hover {
            text-decoration: underline;
        }

        .footer-note {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #64748b;
        }

        /* Screen Action Bar */
        .screen-toolbar {
            position: fixed;
            top: 10px;
            right: 15px;
            z-index: 9999;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 8px 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            gap: 8px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }

        .btn-print {
            background-color: #0284c7;
            color: #ffffff;
        }
        .btn-print:hover { background-color: #0369a1; }

        .btn-close {
            background-color: #64748b;
            color: #ffffff;
        }
        .btn-close:hover { background-color: #475569; }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
            table.data-table th {
                background-color: #0f172a !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            table.data-table tbody tr:nth-child(even) {
                background-color: #f8fafc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <div class="screen-toolbar no-print">
        <button class="btn-action btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
        <button class="btn-action btn-close" onclick="window.close()">✕ Tutup</button>
    </div>

    <div class="header-kop">
        <img src="<?= base_url('assets/img/kop_landscape.jpg') ?>" alt="Kop Surat PT Visi Yosindo Medikal">
    </div>

    <div class="doc-title-box">
        <h2><?= !empty($title_pdf) ? $title_pdf : 'DAFTAR HS CODE & TARIF IMPOR' ?></h2>
        <div class="doc-meta">
            PT VISI YOSINDO MEDIKAL &nbsp;|&nbsp; 
            Total: <?= !empty($data) ? count($data) : 0 ?> Data &nbsp;|&nbsp; 
            Dicetak: <?= !empty($tgl_cetak) ? $tgl_cetak : date('d/m/Y H:i:s') ?>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 14%;">Kode HS Code</th>
                <th style="width: 28%;">Keterangan / Deskripsi Produk</th>
                <th style="width: 28%;">Dokumen Lartas</th>
                <th style="width: 16%;">Tarif Impor</th>
                <th style="width: 10%;">Link HS Code</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data)): ?>
                <?php $no = 1; foreach ($data as $row): 
                    $kode_hs = !empty($row->kode_hs_code) ? $row->kode_hs_code : (!empty($row->nama_dokumen) ? $row->nama_dokumen : '-');
                    $keterangan = !empty($row->keterangan) ? $row->keterangan : '-';
                    $lartas = !empty($row->dokumen_lartas) ? $row->dokumen_lartas : '-';
                    $tarif = !empty($row->tarif_impor) ? $row->tarif_impor : '-';
                    $link = !empty($row->file) ? $row->file : '';
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center text-bold"><?= htmlspecialchars((string)$kode_hs, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="whitespace-pre"><?= htmlspecialchars((string)$keterangan, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="whitespace-pre"><?= htmlspecialchars((string)$lartas, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="whitespace-pre"><?= htmlspecialchars((string)$tarif, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center">
                            <?php if (!empty($link)): ?>
                                <a href="<?= htmlspecialchars((string)$link, ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="link-badge">Buka Link</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; color: #64748b;">Belum ada data HS Code.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer-note">
        <div>Dokumen ini dicetak otomatis dari Sistem Office PT Visi Yosindo Medikal.</div>
        <div>Halaman 1 / Arsip Dokumen Product</div>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
