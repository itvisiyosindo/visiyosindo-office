<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap PO Masuk - PT Visi Yosindo Medikal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 { margin: 0 0 5px 0; font-size: 16pt; color: #1e3a8a; }
        .header h3 { margin: 0 0 5px 0; font-size: 13pt; }
        .header p { margin: 0; font-size: 9pt; color: #555; }
        .filter-info {
            margin-bottom: 15px;
            font-size: 10pt;
            background: #f8fafc;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 7px 9px;
            font-size: 9.5pt;
        }
        th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: left;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
        }
        .no-print {
            margin-bottom: 20px;
        }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" style="padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak / Print Laporan
        </button>
        <button onclick="window.close()" style="padding: 8px 16px; background: #64748b; color: #fff; border: none; border-radius: 4px; cursor: pointer; margin-left: 8px;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h2>PT. VISI YOSINDO MEDIKAL</h2>
        <h3>LAPORAN PENERIMAAN & REKAP PURCHASE ORDER (PO) CUSTOMER</h3>
        <p>Jl. Melur No. 27 A-B, Sukajadi, Pekanbaru - Riau | Telp: (0761) 8521199 | Email: it.visiyosindo@gmail.com</p>
    </div>

    <div class="filter-info">
        <table style="border: none; margin: 0;">
            <tr style="border: none;">
                <td style="border: none; width: 50%; padding: 2px;">
                    <b>Periode:</b> <?= (!empty($tgl_awal) && !empty($tgl_akhir)) ? date('d M Y', strtotime($tgl_awal)) . ' s/d ' . date('d M Y', strtotime($tgl_akhir)) : 'Semua Periode' ?>
                </td>
                <td style="border: none; width: 50%; padding: 2px;">
                    <b>Status Proses:</b> <?= !empty($status_proses) ? htmlspecialchars($status_proses) : 'Semua Status Proses' ?>
                </td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 2px;">
                    <b>Marketing / Sales:</b> <?= htmlspecialchars($nama_sales ?? 'Semua Marketing') ?>
                </td>
                <td style="border: none; padding: 2px;">
                    <b>Dicetak pada:</b> <?= date('d M Y H:i:s') ?> WIB
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 4%;">No</th>
                <th style="width: 14%;">Kode & No. PO</th>
                <th style="width: 10%;">Tgl PO</th>
                <th style="width: 24%;">Customer / Instansi</th>
                <th style="width: 12%;">Marketing</th>
                <th style="width: 12%;">Sistem Bayar</th>
                <th class="text-right" style="width: 12%;">Nilai Total</th>
                <th class="text-center" style="width: 12%;">Status Proses</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($po_list)): ?>
                <?php 
                    $no = 1; 
                    $grandTotal = 0;
                    foreach ($po_list as $p): 
                        $totalRow = $p->nominal_total_akhir ?: $p->nominal_po;
                        $grandTotal += $totalRow;
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td>
                            <b><?= htmlspecialchars($p->no_po) ?></b><br>
                            <small style="color: #64748b;"><?= htmlspecialchars($p->kode_masuk ?? '-') ?></small>
                        </td>
                        <td><?= date('d/m/Y', strtotime($p->tanggal_po)) ?></td>
                        <td>
                            <b><?= htmlspecialchars($p->nama_customer) ?></b>
                            <?php if (!empty($p->alamat_pengiriman)): ?>
                                <br><small style="color: #64748b;"><?= htmlspecialchars(substr($p->alamat_pengiriman, 0, 45)) ?>...</small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p->nama_sales ?? $p->nama_pembuat ?? '-') ?></td>
                        <td>
                            <?= htmlspecialchars($p->sistem_pembayaran ?: 'Cash') ?><br>
                            <small style="color: #64748b;"><?= htmlspecialchars($p->sistem_perpajakan ?: 'PPN') ?></small>
                        </td>
                        <td class="text-right">Rp <?= number_format($totalRow, 0, ',', '.') ?></td>
                        <td class="text-center">
                            <b><?= htmlspecialchars($p->status_proses ?? 'BELUM DI PROSES') ?></b>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="6" class="text-right" style="font-weight: bold; background: #f8fafc;">GRAND TOTAL:</td>
                    <td class="text-right" style="font-weight: bold; background: #f8fafc;">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
                    <td style="background: #f8fafc;"></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada data PO yang sesuai kriteria filter.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 40px; display: flex; justify-content: space-between;">
        <div style="width: 30%; text-align: center;">
            <p>Dibuat Oleh,</p>
            <br><br><br>
            <p><b>( Tim Marketing )</b></p>
        </div>
        <div style="width: 30%; text-align: center;">
            <p>Diketahui Oleh,</p>
            <br><br><br>
            <p><b>( CRO / Accounting )</b></p>
        </div>
        <div style="width: 30%; text-align: center;">
            <p>Disetujui Oleh,</p>
            <br><br><br>
            <p><b>( General Manager )</b></p>
        </div>
    </div>

</body>
</html>
