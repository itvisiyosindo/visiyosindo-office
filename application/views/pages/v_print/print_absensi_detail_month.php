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
            padding: 8px;
        }

        /* #table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #table tr:hover {
            background-color: #ddd;
        } */

        #table th {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
            font-size: 12Px;
        }

        #table td {

            font-size: 10Px;
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
    <img src="assets/img/kop_baru.jpg" width="100%" height="15%" />
    <br />
    <br />
    <table style="border-collapse: collapse; width: 100%; height: 36px;">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 17.5852%; height: 18px;">Nama</td>
                <td style="width: 3.52272%; height: 18px;">:</td>
                <td style="width: 22.6988%; height: 18px;"><?= ucwords($pengguna[0]->nama) ?></td>
                <td style="width: 1.90342%; height: 18px;">&nbsp;</td>
                <td style="width: 17.1449%; height: 18px;">Jabatan</td>
                <td style="width: 1.94604%; height: 18px;">:</td>
                <td style="width: 35.1989%; height: 18px;"><?= ucwords($pengguna[0]->jabatan) ?></td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 17.5852%; height: 18px;">No Pegawai</td>
                <td style="width: 3.52272%; height: 18px;">:</td>
                <td style="width: 22.6988%; height: 18px;"><?= $pengguna[0]->no_pegawai ?></td>
                <td style="width: 1.90342%; height: 18px;">&nbsp;</td>
                <td style="width: 17.1449%; height: 18px;">&nbsp;</td>
                <td style="width: 1.94604%; height: 18px;">&nbsp;</td>
                <td style="width: 35.1989%; height: 18px;">&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <br>
    <table id="table" style="width:100%">
        <thead class="text-center">
            <tr>
                <th style="width:5%"> No </th>
                <th style="width:15%"> Tanggal</th>
                <th> Jenis Absen</th>
                <th> Lokasi</th>
                <th> Masuk </th>
                <th> Istirahat </th>
                <th> Keluar </th>
                <th> Ket </th>
            </tr>
        </thead>
        <tbody>

            <?php $x = 1;
            // echo_array($absen);die;
            foreach ($absen as $row) {
            ?>
                <tr>

                    <td style="text-align: center;"><?= $x++ ?></td>
                    <td><?= $row['date'] ?></td>
                    <td><?= $row['jenis_absen'] ?></td>
                    <td><?= isset($row['jenis_lokasi']) ? $row['jenis_lokasi'] : '-' ?></td>
                    <td><?= $row['masuk'] ?></td>
                    <td><?= $row['istirahat'] ?></td>
                    <td><?= $row['keluar'] ?></td>
                    <td><?= $row['ket'] ?></td>
                </tr>

            <?php
            } ?>

        </tbody>
    </table>

</body>

</html>