<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $title_pdf ?></title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header-box {
            text-align: center;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            color: #003366;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .header-subtitle {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            color: #0056b3;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
            background-color: #f0f7ff;
            border: 1px solid #b8daff;
        }
        .meta-table td {
            padding: 5px 8px;
            font-size: 11px;
            vertical-align: top;
        }
        .meta-table td strong {
            color: #003366;
        }
        .summary-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .summary-box td {
            border: 1px solid #b8daff;
            padding: 8px;
            text-align: center;
            font-size: 11px;
            background-color: #e6f2ff;
        }
        .summary-val {
            font-size: 15px;
            font-weight: bold;
            margin-top: 3px;
            color: #004085;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content-table th, .content-table td {
            border: 1px solid #b8daff;
            padding: 5px 6px;
            font-size: 10.5px;
            vertical-align: top;
        }
        .content-table th {
            background-color: #0056b3;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge-selesai { 
            color: #155724; 
            background-color: #d4edda; 
            border: 1px solid #c3e6cb;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold; 
            font-size: 9.5px;
            display: inline-block;
        }
        .badge-proses { 
            color: #004085; 
            background-color: #cce5ff; 
            border: 1px solid #b8daff;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold; 
            font-size: 9.5px;
            display: inline-block;
        }
        .badge-konfirmasi {
            color: #383d41;
            background-color: #e2e3e5;
            border: 1px solid #d6d8db;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9.5px;
            display: inline-block;
        }
        .badge-pending {
            color: #856404;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9.5px;
            display: inline-block;
        }
        .badge-kendala { 
            color: #721c24; 
            background-color: #f8d7da; 
            border: 1px solid #f5c6cb;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold; 
            font-size: 9.5px;
            display: inline-block;
        }
        .badge-empty {
            color: #6c757d;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
            font-style: italic;
            font-size: 9.5px;
            display: inline-block;
        }
        .badge-libur {
            color: #b91c1c;
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: bold;
            display: inline-block;
            margin-top: 2px;
        }
        .badge-libur-status {
            color: #991b1b;
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9.5px;
            display: inline-block;
        }
        .row-libur {
            background-color: #fff8f8;
        }


        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #003366;
            margin-top: 15px;
            margin-bottom: 8px;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 3px;
        }

        .signature-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            width: 50%;
            font-size: 11px;
        }
        .signature-title {
            color: #003366;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header-box">
        <div class="header-title">RANGKUMAN LAPORAN KERJA BULANAN KARYAWAN</div>
        <div class="header-subtitle">PT. VISI YOSINDO MEDIKAL</div>
    </div>

    <table class="meta-table">
        <tr>
            <td width="15%"><strong>Nama Karyawan</strong></td>
            <td width="2%">:</td>
            <td width="33%"><?= isset($data_job->nama) ? $data_job->nama : '-' ?></td>
            <td width="15%"><strong>Periode Bulan</strong></td>
            <td width="2%">:</td>
            <td width="33%"><?= $nama_bulan ?> <?= $tahun ?></td>
        </tr>
        <tr>
            <td><strong>NPP</strong></td>
            <td>:</td>
            <td><?= isset($data_job->no_pegawai) ? $data_job->no_pegawai : '-' ?></td>
            <td><strong>Rentang Tanggal</strong></td>
            <td>:</td>
            <td><?= date('d-m-Y', strtotime($startDate)) ?> s/d <?= date('d-m-Y', strtotime($endDate)) ?></td>
        </tr>
        <tr>
            <td><strong>Jabatan</strong></td>
            <td>:</td>
            <td><?= isset($data_job->jabatan) ? $data_job->jabatan : '-' ?></td>
            <td><strong>Tanggal Cetak</strong></td>
            <td>:</td>
            <td><?= date('d-m-Y H:i') ?> WIB</td>
        </tr>
    </table>

    <!-- Box Ringkasan Statistik Bulanan -->
    <table class="summary-box">
        <tr>
            <td width="20%">
                Total Kegiatan
                <div class="summary-val"><?= $rekap_status ? $rekap_status->total_pekerjaan : 0 ?></div>
            </td>
            <td width="20%">
                Status Selesai
                <div class="summary-val badge-selesai"><?= $rekap_status ? $rekap_status->total_selesai : 0 ?></div>
            </td>
            <td width="20%">
                Dalam Proses
                <div class="summary-val badge-proses"><?= $rekap_status ? $rekap_status->total_proses : 0 ?></div>
            </td>
            <td width="20%">
                Kendala
                <div class="summary-val badge-kendala"><?= $rekap_status ? $rekap_status->total_kendala : 0 ?></div>
            </td>
            <td width="20%">
                Persentase Selesai
                <div class="summary-val">
                    <?php 
                        $tot = ($rekap_status && $rekap_status->total_pekerjaan > 0) ? $rekap_status->total_pekerjaan : 0;
                        $sel = ($rekap_status && $rekap_status->total_selesai > 0) ? $rekap_status->total_selesai : 0;
                        $pct = ($tot > 0) ? round(($sel / $tot) * 100, 1) : 0;
                        echo $pct . '%';
                    ?>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">I. REKAPITULASI LAPORAN MINGGUAN SELAMA 1 BULAN</div>

    <table class="content-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="10%">Tanggal Laporan</th>
                <th width="7%">Minggu</th>
                <th width="17%">Jobdesk / Point</th>
                <th width="25%">Rincian Kegiatan</th>
                <th width="11%">Waktu Input</th>
                <th width="10%">Status</th>
                <th width="17%">Kendala & Solusi / Ket</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (!empty($laporan_bulan)): 
                $no = 1;
                foreach ($laporan_bulan as $row):
                    // Hitung minggu ke berapa dalam bulan tersebut berdasarkan d (hari)
                    if (!empty($row->tanggal)) {
                        $tgl_d = (int)date('d', strtotime($row->tanggal));
                        $m_ke = 'Minggu ' . ceil($tgl_d / 7);
                    } else {
                        $m_ke = '-';
                    }

                    $created_at_raw = !empty($row->created_at) ? $row->created_at : '';
                    $created_at_date = $created_at_raw ? date('Y-m-d', strtotime($created_at_raw)) : '';
                    $tgl_report = !empty($row->tanggal) ? date('Y-m-d', strtotime($row->tanggal)) : '';
                    
                    $is_late = false;
                    if ($created_at_date && $tgl_report) {
                        $m_mon = date('Y-m-d', strtotime('monday this week', strtotime($tgl_report)));
                        $m_sun = date('Y-m-d', strtotime('sunday this week', strtotime($tgl_report)));
                        if ($created_at_date < $m_mon || $created_at_date > $m_sun) {
                            $is_late = true;
                        }
                    }

                    // Formating Waktu Input Karyawan
                    if ($created_at_raw) {
                        $waktu_input_display = date('d-m-Y H:i', strtotime($created_at_raw));
                        if ($is_late) {
                            $waktu_input_display .= '<br><span style="color: #c00; font-weight: bold; font-size: 8.5px;">(Terlambat)</span>';
                        }
                    } else {
                        $waktu_input_display = '<span style="color: #999;">-</span>';
                    }

                    // Cek Informasi Hari Libur (Weekend / Tanggal Merah / Cuti Bersama)
                    $info_libur = !empty($row->tanggal) ? get_info_hari_libur($row->tanggal) : ['is_libur' => false];
                    $row_class = $info_libur['is_libur'] ? 'row-libur' : '';

                    // Logika Status Pekerjaan Sesuai Inputan Karyawan
                    $st_raw = !empty($row->status_pekerjaan) ? trim($row->status_pekerjaan) : '';
                    $st_upper = strtoupper($st_raw);

                    if ($st_upper === 'SELESAI') {
                        $status_class = 'badge-selesai';
                        $status_text = 'SELESAI';
                    } elseif ($st_upper === 'DALAM PROSES' || $st_upper === 'PROSES') {
                        $status_class = 'badge-proses';
                        $status_text = 'DALAM PROSES';
                    } elseif (strpos($st_upper, 'KONFIRMASI') !== false) {
                        $status_class = 'badge-konfirmasi';
                        $status_text = 'MENUNGGU KONFIRMASI';
                    } elseif ($st_upper === 'PENDING') {
                        $status_class = 'badge-pending';
                        $status_text = 'PENDING';
                    } elseif ($st_upper === 'TIDAK SELESAI' || $st_upper === 'KENDALA') {
                        $status_class = 'badge-kendala';
                        $status_text = 'TIDAK SELESAI';
                    } elseif (!empty($st_raw)) {
                        $status_class = 'badge-proses';
                        $status_text = htmlspecialchars($st_raw);
                    } elseif ($info_libur['is_libur']) {
                        $status_class = 'badge-libur-status';
                        $status_text = 'HARI LIBUR';
                    } else {
                        $status_class = 'badge-empty';
                        $status_text = 'Belum Diisi';
                    }

                    // Logika Rincian Kegiatan
                    if (!empty($row->progress)) {
                        $progress_display = htmlspecialchars($row->progress);
                        if (!empty($row->link)) {
                            $progress_display .= '<br><small style="color: #0056b3;"><strong>Link Bukti:</strong> ' . htmlspecialchars($row->link) . '</small>';
                        }
                        if (!empty($row->ket_hasil)) {
                            $progress_display .= '<br><small><strong>Ket Hasil:</strong> ' . htmlspecialchars($row->ket_hasil) . '</small>';
                        }
                    } elseif (!empty($row->ket_hasil)) {
                        $progress_display = htmlspecialchars($row->ket_hasil);
                        if (!empty($row->link)) {
                            $progress_display .= '<br><small style="color: #0056b3;"><strong>Link Bukti:</strong> ' . htmlspecialchars($row->link) . '</small>';
                        }
                    } elseif (!empty($row->link)) {
                        $progress_display = '<small style="color: #0056b3;"><strong>Link Bukti:</strong> ' . htmlspecialchars($row->link) . '</small>';
                    } elseif (!empty($row->jenis) && strtoupper($row->jenis) === 'TIDAK ADA PEKERJAAN DI MINGGU INI') {
                        $progress_display = '<em style="color: #777;">(Tidak ada pekerjaan di minggu ini)</em>';
                    } elseif ($info_libur['is_libur']) {
                        $progress_display = '<em style="color: #b91c1c; font-weight: 500;">(Hari Libur: ' . htmlspecialchars($info_libur['nama']) . ')</em>';
                    } else {
                        $progress_display = '<em style="color: #c00; font-weight: 500;">Tidak mengisi keterangan hasil pekerjaannya</em>';
                    }

                    // Logika Kendala & Solusi / Ket
                    $ket_items = [];
                    if (!empty($row->kendala) && $row->kendala != 'Tidak Ada') {
                        $ket_items[] = '<strong>Kendala:</strong> ' . htmlspecialchars($row->kendala);
                    }
                    if (!empty($row->solusi)) {
                        $ket_items[] = '<strong>Solusi:</strong> ' . htmlspecialchars($row->solusi);
                    }
                    if (!empty($row->keterangan)) {
                        $ket_items[] = '<strong>Ket:</strong> ' . htmlspecialchars($row->keterangan);
                    }
                    if (!empty($row->pihak)) {
                        $ket_items[] = '<strong>Pihak Terkait:</strong> ' . htmlspecialchars($row->pihak);
                    }

                    if (!empty($ket_items)) {
                        $ket_display = implode('<br>', $ket_items);
                    } else {
                        $ket_display = '<em style="color: #999;">Tidak ada catatan kendala / keterangan.</em>';
                    }
            ?>
                <tr class="<?= $row_class ?>">
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center">
                        <?= !empty($row->tanggal) ? date('d-m-Y', strtotime($row->tanggal)) : '-' ?>
                        <?php if (!empty($info_libur['is_libur'])): ?>
                            <br><span class="badge-libur"><?= htmlspecialchars($info_libur['nama']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">Minggu <?= $m_ke ?></td>
                    <td>
                        <strong><?= !empty($row->point) ? '[' . $row->point . '] ' : '' ?></strong>
                        <?= !empty($row->jobdesc_master) ? $row->jobdesc_master : (!empty($row->jobdesc) ? $row->jobdesc : '-') ?>
                    </td>
                    <td><?= $progress_display ?></td>
                    <td class="text-center"><?= $waktu_input_display ?></td>
                    <td class="text-center"><span class="<?= $status_class ?>"><?= $status_text ?></span></td>
                    <td><?= $ket_display ?></td>
                </tr>
            <?php 
                endforeach;
            else: 
            ?>
                <tr>
                    <td colspan="8" class="text-center"><em>Belum ada data laporan kegiatan untuk bulan ini.</em></td>
                </tr>

            <?php endif; ?>
        </tbody>
    </table>

    <?php if (!empty($pencapaian_bulan)): ?>
        <div class="section-title">II. PENCAPAIAN LAINNYA DALAM 1 BULAN</div>
        <table class="content-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Tanggal</th>
                    <th width="45%">Detail Pencapaian</th>
                    <th width="38%">Keterangan / Link</th>
                </tr>
            </thead>
            <tbody>
                <?php $no_p = 1; foreach ($pencapaian_bulan as $p): ?>
                    <tr>
                        <td class="text-center"><?= $no_p++ ?></td>
                        <td class="text-center"><?= date('d-m-Y', strtotime($p->tanggal)) ?></td>
                        <td><?= $p->detail ?></td>
                        <td>
                            <?= $p->keterangan ?>
                            <?php if (!empty($p->link)): ?>
                                <br><small>Link: <?= $p->link ?></small>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <table class="signature-table">
        <tr>
            <td>
                Mengetahui / Menilai,<br><br><br><br><br>
                <strong>( <?= !empty($data_job->nama_penilai_b) ? $data_job->nama_penilai_b : (!empty($data_job->nama_penilai_a) ? $data_job->nama_penilai_a : '................................') ?> )</strong><br>
                Atasan / Penilai
            </td>
            <td>
                Menyetujui,<br><br><br><br><br>
                <strong>( Dian Melati Amelai )</strong><br>
                HR
            </td>
        </tr>
    </table>


</body>
</html>
