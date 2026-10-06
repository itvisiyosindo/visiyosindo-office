<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title_pdf; ?></title>
    <style>
        body {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        #table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        #table td,
        #table th {
            border: 1px solid #ddd;
            padding: 6px 8px;
        }

        #table th {
            background-color: #ffffff;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
            padding-top: 8px;
            padding-bottom: 8px;
        }

        #table td {
            font-size: 10px;
            text-align: center;
        }

        .info-table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .info-table td {
            padding: 3px 0;
        }
    </style>
</head>

<body>
    <img src="assets/img/kop_baru.jpg" width="100%" />
    <br />
    <table class="info-table">
        <tbody>
            <tr>
                <td style="width: 14%;">Nama</td>
                <td style="width: 2%;">:</td>
                <td style="width: 34%;"><?= ucwords($pengguna[0]->nama) ?></td>
                <td style="width: 14%;">Jabatan</td>
                <td style="width: 2%;">:</td>
                <td style="width: 34%;"><?= ucwords($pengguna[0]->jabatan) ?></td>
            </tr>
            <tr>
                <td>No Pegawai</td>
                <td>:</td>
                <td><?= $pengguna[0]->no_pegawai ?: '-' ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <table id="table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 20%;">Jenis Absen</th>
                <th style="width: 12%;">Lokasi</th>
                <th style="width: 12%;">Masuk</th>
                <th style="width: 12%;">Istirahat</th>
                <th style="width: 12%;">Keluar</th>
                <th style="width: 12%;">Ket</th>
            </tr>
        </thead>
        <tbody>
            <?php $x = 1;
            foreach ($absen as $row) { ?>
                <tr>
                    <td><?= $x++ ?></td>
                    <td><?= $row['date'] ?></td>
                    <td><?= $row['jenis_absen'] ?></td>
                    <td><?= !empty($row['lokasi']) ? $row['lokasi'] : '-' ?></td>
                    <td><?= $row['masuk'] ?></td>
                    <td><?= $row['istirahat'] ?></td>
                    <td><?= $row['keluar'] ?></td>
                    <td><?= $row['ket'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>

</html>