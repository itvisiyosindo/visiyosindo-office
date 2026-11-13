<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title_pdf); ?></title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1e293b;
            font-size: 9px;
            line-height: 1.25;
        }

        .header-kop {
            width: 100%;
            text-align: center;
            margin-bottom: 5px;
        }

        .header-kop img {
            width: 100%;
            height: auto;
        }

        .doc-title {
            text-align: center;
            margin-top: 8px;
            margin-bottom: 4px;
        }

        .doc-title h2 {
            font-size: 13px;
            font-weight: bold;
            margin: 0;
            padding: 0;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .doc-meta {
            text-align: center;
            font-size: 8px;
            color: #64748b;
            margin-bottom: 10px;
        }

        #table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #table td,
        #table th {
            border: 1px solid #94a3b8;
            padding: 4px 5px;
            vertical-align: middle;
        }

        #table th {
            background-color: #0f172a;
            color: #ffffff;
            text-align: center;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 3px;
        }

        #table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .badge-status {
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 7.5px;
            display: inline-block;
        }

        .badge-tetap {
            background-color: #dcfce7;
            color: #15803d;
        }

        .badge-kontrak {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .badge-training {
            background-color: #fef3c7;
            color: #b45309;
        }

        .summary-box {
            margin-top: 8px;
            font-size: 8px;
            color: #334155;
        }

        .footer-note {
            margin-top: 15px;
            font-size: 7.5px;
            color: #64748b;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="header-kop">
        <img src="assets/img/kop_landscape.jpg" alt="Kop Visiyosindo" />
    </div>

    <div class="doc-title">
        <h2><?= htmlspecialchars($title_pdf); ?></h2>
    </div>
    <div class="doc-meta">
        Dicetak pada: <?= date('d F Y, H:i') ?> WIB | Total Karyawan: <?= count($data) ?> Orang
    </div>

    <table id="table">
        <thead>
            <tr>
                <th style="width: 2%;">No</th>
                <th style="width: 14%;">Nama Karyawan</th>
                <th style="width: 6%;">NPP</th>
                <th style="width: 9%;">NIK</th>
                <th style="width: 8%;">Tgl Lahir / Usia</th>
                <th style="width: 10%;">Divisi</th>
                <th style="width: 11%;">Jabatan</th>
                <th style="width: 6%;">Status</th>
                <th style="width: 7%;">Tgl Masuk</th>
                <th style="width: 10%;">Masa Kerja</th>
                <th style="width: 8%;">Pendidikan</th>
                <th style="width: 9%;">No. HP</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $hari_ini = new DateTime(date('Y-m-d'));

            foreach ($data as $row): 
                // Hitung Usia dari tgl_lahir
                $usia_text = '-';
                $tgl_lahir_text = '-';
                if (!empty($row->tgl_lahir) && $row->tgl_lahir != '0000-00-00') {
                    $tgl_lahir_dt = new DateTime($row->tgl_lahir);
                    $tgl_lahir_text = $tgl_lahir_dt->format('d/m/Y');
                    $diff_usia = $tgl_lahir_dt->diff($hari_ini);
                    $usia_text = $diff_usia->y . ' th';
                }

                // Hitung Masa Kerja dari tgl_masuk (fallback ke tgl_kontrak)
                $ref_tgl_masuk = !empty($row->tgl_masuk) && $row->tgl_masuk != '0000-00-00' ? $row->tgl_masuk : $row->tgl_kontrak;
                $masa_kerja_text = '-';
                $tgl_masuk_text = '-';

                if (!empty($ref_tgl_masuk) && $ref_tgl_masuk != '0000-00-00') {
                    $dt_masuk = new DateTime($ref_tgl_masuk);
                    $tgl_masuk_text = $dt_masuk->format('d/m/Y');
                    $diff_kerja = $dt_masuk->diff($hari_ini);

                    $parts = [];
                    if ($diff_kerja->y > 0) $parts[] = $diff_kerja->y . ' th';
                    if ($diff_kerja->m > 0) $parts[] = $diff_kerja->m . ' bln';
                    if (empty($parts)) $parts[] = $diff_kerja->d . ' hr';
                    $masa_kerja_text = implode(' ', $parts);
                }

                // Status Karyawan styling
                $status_raw = strtolower((string)$row->status_karyawan);
                if ($status_raw == 'tetap') {
                    $status_badge = '<span class="badge-status badge-tetap">Tetap</span>';
                } elseif ($status_raw == 'kontrak') {
                    $status_badge = '<span class="badge-status badge-kontrak">Kontrak</span>';
                } elseif ($status_raw == 'training') {
                    $status_badge = '<span class="badge-status badge-training">Training</span>';
                } else {
                    $status_badge = !empty($row->status_karyawan) ? htmlspecialchars(ucfirst($row->status_karyawan)) : '-';
                }
            ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td><strong><?= htmlspecialchars($row->nama ?? '-'); ?></strong></td>
                    <td class="text-center"><?= htmlspecialchars($row->no_pegawai ?? '-'); ?></td>
                    <td class="text-center"><?= htmlspecialchars($row->nik ?? '-'); ?></td>
                    <td class="text-center">
                        <?= $tgl_lahir_text ?>
                        <?php if ($usia_text != '-'): ?>
                            <br><small style="color:#64748b;">(<?= $usia_text ?>)</small>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row->nama_divisi ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row->jabatan ?? '-'); ?></td>
                    <td class="text-center"><?= $status_badge; ?></td>
                    <td class="text-center"><?= $tgl_masuk_text; ?></td>
                    <td class="text-center"><?= $masa_kerja_text; ?></td>
                    <td class="text-center"><?= htmlspecialchars($row->pendidikan ?? '-'); ?></td>
                    <td class="text-center"><?= htmlspecialchars($row->no_hp ?? '-'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer-note">
        Dokumen resmi PT. Visi Yosindo Medikal &bull; Dicetak otomatis oleh Office System
    </div>

</body>
</html>
