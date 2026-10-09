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
            padding: 6px 10px;
            font-size: 11px;
            vertical-align: top;
        }
        .meta-table td strong {
            color: #003366;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content-table th, .content-table td {
            border: 1px solid #b8daff;
            padding: 6px 8px;
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
        .text-bold { font-weight: bold; }
        
        .badge-dinilai {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9.5px;
            display: inline-block;
        }
        .badge-tidak-dinilai {
            color: #6c757d;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9.5px;
            display: inline-block;
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
            margin-top: 30px;
            border-collapse: collapse;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            width: 50%;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="header-box">
        <div class="header-title">DAFTAR DESKRIPSI TUGAS & PEKERJAAN (JOBDESK) KARYAWAN</div>
        <div class="header-subtitle">PT. VISI YOSINDO MEDIKAL</div>
    </div>

    <table class="meta-table">
        <tr>
            <td width="15%"><strong>Nama Karyawan</strong></td>
            <td width="2%">:</td>
            <td width="33%"><?= isset($data_job->nama) ? $data_job->nama : (isset($data_job->pegawai) ? $data_job->pegawai : '-') ?></td>
            <td width="15%"><strong>Tanggal Cetak</strong></td>
            <td width="2%">:</td>
            <td width="33%"><?= date('d-m-Y H:i') ?> WIB</td>
        </tr>
        <tr>
            <td><strong>NPP</strong></td>
            <td>:</td>
            <td><?= isset($data_job->no_pegawai) ? $data_job->no_pegawai : '-' ?></td>
            <td><strong>Dokumen</strong></td>
            <td>:</td>
            <td>Master Jobdesk Karyawan</td>
        </tr>
        <tr>
            <td><strong>Jabatan</strong></td>
            <td>:</td>
            <td><?= isset($data_job->jabatan) ? $data_job->jabatan : '-' ?></td>
            <td><strong>Masa Berlaku</strong></td>
            <td>:</td>
            <td>
                <?php 
                    $tgl_m = (!empty($data_job->tgl_mulai) && $data_job->tgl_mulai != '0000-00-00') ? date('d/m/Y', strtotime($data_job->tgl_mulai)) : '01/01/2024';
                    $tgl_s = (!empty($data_job->tgl_selesai) && $data_job->tgl_selesai != '0000-00-00' && $data_job->tgl_selesai != '2099-12-31') ? date('d/m/Y', strtotime($data_job->tgl_selesai)) : 'Seterusnya';
                    echo $tgl_m . ' s/d ' . $tgl_s;
                ?>
            </td>
        </tr>
    </table>

    <div class="section-title">RINCIAN POINTS & RINCIAN TUGAS (JOBDESK)</div>

    <table class="content-table">
        <thead>
            <tr>
                <th width="8%">No Urut</th>
                <th width="20%">Point (Sub Jobdesk)</th>
                <th width="52%">Deskripsi Tugas / Pekerjaan</th>
                <th width="20%">Penilaian</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data_detail)): ?>
                <?php 
                $no = 1;
                foreach ($data_detail as $row): 
                    $no_urut_display = (!empty($row->id_urut) && $row->id_urut != 0) ? $row->id_urut : $no;
                ?>
                    <tr>
                        <td class="text-center"><?= $no_urut_display ?></td>
                        <td class="text-center"><strong><?= !empty($row->point) ? '[' . htmlspecialchars($row->point) . ']' : '-' ?></strong></td>
                        <td>
                            <?php if ($row->nilai == 1): ?>
                                <strong style="color: #003366;"><?= htmlspecialchars($row->deskripsi) ?></strong>
                            <?php else: ?>
                                <?= htmlspecialchars($row->deskripsi) ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($row->nilai == 1): ?>
                                <span class="badge-dinilai">Dinilai (Jobdesk Utama)</span>
                            <?php else: ?>
                                <span class="badge-tidak-dinilai">Tidak Dinilai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php 
                    $no++;
                endforeach; 
                ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center"><em>Belum ada rincian jobdesk untuk karyawan ini.</em></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

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
