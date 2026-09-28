<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title_pdf; ?></title>
    <style>
        #table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #table td,
        #table th {
            border: 1px solid #ddd;
            padding: 4px 5px;
        }

        #table th {
            padding-top: 5px;
            padding-bottom: 5px;
            text-align: center;
            font-size: 10Px;
        }

        #table td {
            font-size: 9px;
        }

        .tandatangan {
            text-align: center;
            margin-left: 600px;
        }

        .tandatangan2 {
            text-align: left;
        }

        .tandatangan3 {
            text-align: left;
            margin-left: 300px;
        }
    </style>
</head>

<body>
    <img src="assets/img/kop_baru.jpg" width="100%" height="30%" />
    <div style="text-align:center; margin:0; padding:0;">
        <h3 style="margin:2px 0;"><?= $title_pdf ?></h3>
        <h4 style="margin:2px 0;">Periode : <?= $periode ?></h4>
    </div>
    <table id="table" style="width:100%">
        <thead class="text-center">
            <tr>
                <th style="width:5%"> No </th>
                <th style="width:10%"> Nama Karyawan</th>
                <th style="width:5%"> No Pegawai</th>
                <th style="width:7%"> Status Karyawan</th>
                <th style="width:10%"> Masa Kerja</th>
                <th style="width:7%"> Hari Kehadiran</th>
                <th> Tunjangan Jabatan </th>
                <th> Tunjangan Kinerja </th>
                <th> Tunjangan Konsumsi </th>
                <th> Tunjangan Komunikasi </th>
                <th> Tunjangan Transportasi </th>
                <th> Tunjangan BBM </th>
                <th> Tunjangan Lainnya </th>
                <th> Potongan </th>
                <th style="width:10%"> Total Tunjangan </th>
                <th> No Rekening </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $x = 1;
            $sumJabatan = 0;
            $sumKinerja = 0;
            $sumKonsumsi = 0;
            $sumKomunikasi = 0;
            $sumTransportasi = 0;
            $sumBbm = 0;
            $sumLainnya = 0;
            $sumPotongan = 0;
            $sumTotal = 0;

            if (!empty($dt)) :
                foreach ($dt as $row) :
                    // Calculate total for this row
                    $total = $row->tunjangan_jabatan
                        + $row->tunjangan_kinerja
                        + $row->tunjangan_konsumsi
                        + $row->tunjangan_komunikasi
                        + $row->tunjangan_transportasi
                        + $row->tunjangan_bbm
                        + $row->tunjangan_lainnya
                        - $row->potongan;
            ?>
                    <tr>
                        <td style="text-align: center;"><?= $x++ ?></td>
                        <td><?= ucwords(strtolower($row->nama_karyawan)) ?></td>
                        <td style="text-align: center;"><?= $row->no_pegawai ?: '-' ?></td>
                        <td style="text-align: center;"><?= ucwords(strtolower($row->status_karyawan)) ?></td>
                        <td><?= $row->masa_kerja ?: '-' ?></td>
                        <td style="text-align: center;"><?= $row->hari_kehadiran ?> hari</td>
                        <td><?= $row->tunjangan_jabatan > 0 ? 'Rp. ' . rupiah($row->tunjangan_jabatan) : 'Rp. -' ?></td>
                        <td><?= $row->tunjangan_kinerja > 0 ? 'Rp. ' . rupiah($row->tunjangan_kinerja) : 'Rp. -' ?></td>
                        <td><?= $row->tunjangan_konsumsi > 0 ? 'Rp. ' . rupiah($row->tunjangan_konsumsi) : 'Rp. -' ?></td>
                        <td><?= $row->tunjangan_komunikasi > 0 ? 'Rp. ' . rupiah($row->tunjangan_komunikasi) : 'Rp. -' ?></td>
                        <td><?= $row->tunjangan_transportasi > 0 ? 'Rp. ' . rupiah($row->tunjangan_transportasi) : 'Rp. -' ?></td>
                        <td><?= $row->tunjangan_bbm > 0 ? 'Rp. ' . rupiah($row->tunjangan_bbm) : 'Rp. -' ?></td>
                        <td><?= $row->tunjangan_lainnya > 0 ? 'Rp. ' . rupiah($row->tunjangan_lainnya) : 'Rp. -' ?></td>
                        <td><?= $row->potongan > 0 ? 'Rp. ' . rupiah($row->potongan) : 'Rp. -' ?></td>
                        <td style="font-weight: bold;"><?= $total > 0 ? 'Rp. ' . rupiah($total) : 'Rp. -' ?></td>
                        <td><?= $row->no_rekening ?: '-' ?></td>
                    </tr>

                <?php
                    // Sum totals
                    $sumJabatan += $row->tunjangan_jabatan;
                    $sumKinerja += $row->tunjangan_kinerja;
                    $sumKonsumsi += $row->tunjangan_konsumsi;
                    $sumKomunikasi += $row->tunjangan_komunikasi;
                    $sumTransportasi += $row->tunjangan_transportasi;
                    $sumBbm += $row->tunjangan_bbm;
                    $sumLainnya += $row->tunjangan_lainnya;
                    $sumPotongan += $row->potongan;
                    $sumTotal += $total;
                endforeach;
            else :
                ?>
                <tr>
                    <td colspan="16" style="text-align: center;">Tidak ada data untuk periode ini</td>
                </tr>
            <?php endif; ?>

            <!-- Total Row -->
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td colspan="5" style="text-align:right;">Total Keseluruhan :</td>
                <td style="text-align: center;">-</td>
                <td>Rp. <?= rupiah($sumJabatan) ?></td>
                <td>Rp. <?= rupiah($sumKinerja) ?></td>
                <td>Rp. <?= rupiah($sumKonsumsi) ?></td>
                <td>Rp. <?= rupiah($sumKomunikasi) ?></td>
                <td>Rp. <?= rupiah($sumTransportasi) ?></td>
                <td>Rp. <?= rupiah($sumBbm) ?></td>
                <td>Rp. <?= rupiah($sumLainnya) ?></td>
                <td>Rp. <?= rupiah($sumPotongan) ?></td>
                <td>Rp. <?= rupiah($sumTotal) ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table border="0" style="border-collapse: collapse; width: 100%;">
        <tbody>
            <tr>
                <td style="width: 33.33%; text-align: center;">&nbsp;</td>
                <td style="width: 33.33%; text-align: center;">&nbsp;</td>
                <td style="width: 33.33%; text-align: center;">Pekanbaru, <?= indo_dates(date('Y-m-d')) ?></td>
            </tr>

            <tr>
                <td style="width: 33.33%; text-align: center;">Diajukan Oleh</td>
                <td style="width: 33.33%; text-align: center;">Diverifikasi Oleh</td>
                <td style="width: 33.33%; text-align: center;">Disetujui Oleh</td>
            </tr>

            <tr>
                <td style="height: 50px; text-align: center;">&nbsp;</td>
                <td style="height: 50px; text-align: center;">&nbsp;</td>
                <td style="height: 50px; text-align: center;">&nbsp;</td>
            </tr>

            <tr>
                <td style="width: 33.33%; text-align: center;"><u>Novemby Ardiansyah Putra</u></td>
                <td style="width: 33.33%; text-align: center;"><u>Azhari Pratama</u></td>
                <td style="width: 33.33%; text-align: center;"><u>Dian Melati Amelia</u></td>
            </tr>

            <tr>
                <td style="width: 33.33%; text-align: center;"><i>General Affair</i></td>
                <td style="width: 33.33%; text-align: center;"><i>Staff Tax</i></td>
                <td style="width: 33.33%; text-align: center;"><i>HR & Legal</i></td>
            </tr>
        </tbody>
    </table>
</body>

</html>