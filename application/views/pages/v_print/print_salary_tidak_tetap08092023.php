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
            font-size: 11Px;
        }
        
        #table td{
            font-size: 10px;
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
    <img src="assets/img/kop_surat_vym_underline.png" width="100%" height="30%" />
    <div style="text-align:center">
        <h2><?= $title_pdf ?></h2>
        <h3>Periode : <?= $periode ?></h3>
    </div>
    <table id="table" style="width:100%">
        <thead class="text-center">
            <tr>
                <th style="width:5%"> No </th>
                <th style="width:15%"> Nama Karyawan</th>
                <th style="width:5%"> No Pegawai</th>
                <th style="width:7%"> Status Karyawan</th>
                <th style="width:12%"> Masa Kerja</th>
                <th style="width:7%"> Hari Kehadiran</th>
                <th> Tunjangan Jabatan </th>
                <th> Tunjangan Kinerja </th>
                <th> Tunjangan Konsumsi </th>
                <th> Tunjangan Komunikasi </th>
                <th> Tunjangan Transportasi </th>
                <th> Tunjangan BBM </th>
                <th> Potongan </th>
                <th> Total Tunjangan </th>
                <th> No Rekening </th>
            </tr>
        </thead>
        <tbody>

            <?php $x = 1;
            $sumJabatan=0;
            $sumKinerja = 0;
            $sumKonsumsi = 0;
            $sumKomunikasi = 0;
            $sumTunjangan = 0;
            $sumbbm = 0;
            $sumtransportasi = 0;
            $sumPotongan = 0;
            foreach ($dt as $row) {
            ?>
                <tr>
                    <?php

                        //variabel ceklis tanpa tunjanan
                        $s_karyawan = $row->status_karyawan;
                        $s_jabatan = $row->terima_tunjangan_jabatan;
                        $s_kinerja = $row->terima_tunjangan_kinerja;
                        $s_konsumsi = $row->terima_tunjangan_konsumsi;
                        $s_komunikasi = $row->terima_tunjangan_komunikasi;
                        $s_transportasi = $row->terima_tunjangan_transportasi;
                        $s_bbm = $row->terima_tunjangan_bbm;

                        if ($s_jabatan == 1) {
                            $jabatan = tunjanganJabatan($row->pengguna_id);
                        }else{
                            $jabatan = 0;
                        }
                        if ($s_kinerja == 1) {
                            $kinerja = tunjangan($row->pengguna_id, $month)['total_kinerja'];
                        } else {
                            $kinerja = 0;
                        }
                        if ($s_konsumsi == 1) {
                            $konsumsi = tunjangan($row->pengguna_id, $month)['total_konsumsi'];
                        } else {
                            $konsumsi = 0;
                        }
                        if ($s_komunikasi == 1) {
                            $komunikasi = tunjangan($row->pengguna_id, $month)['total_komunikasi'];
                        } else {
                            $komunikasi = 0;
                        }
                        if ($s_transportasi == 1) {
                            $transportasi = tunjangan($row->pengguna_id, $month)['total_transportasi'];
                        } else {
                            $transportasi = 0;
                        }
                        if ($s_bbm == 1) {
                            $bbm = tunjangan($row->pengguna_id, $month)['total_bbm'];
                        } else {
                            $bbm = 0;
                        }
                    $potongan1 =tunjangan($row->pengguna_id, $month)['total_potongan'];
                    $s_karyawan = $row->status_karyawan;

                        if ($s_karyawan == "training") {
                            $tunjangan = '0';
                        } else {
                            $tunjangan = $jabatan + $kinerja + $konsumsi + $komunikasi + $transportasi + $bbm - $potongan1;
                        }

                        // $jabatan = tunjanganJabatan($row->pengguna_id);
                  
                    // $tunjangan  = tunjangan($row->pengguna_id, $month)['total_tunjangan']+$jabatan;
                    $absen_approved  = tunjangan($row->pengguna_id, $month)['absen_approved'];
                    

                   

                        // if ($s_karyawan == "training") {
                        //     $th[] = 'Training';
                        //     $th[] = 'Training';
                        //     $th[] = 'Training';
                        //     $th[] = 'Training';
                        //     $th[] = 'Training';
                        // } else {
                        //     if ($s_jabatan == 1) {
                        //         $th[] = $jabatan;
                        //     } else {
                        //         $th[] = '<i class="fa fa-times"></i>';
                        //     }
                        //     if ($s_kinerja == 1) {
                        //         $th[] = $total_kinerja;
                        //     } else {
                        //         $th[] = '<i class="fa fa-times"></i>';
                        //     }
                        //     if ($s_konsumsi == 1) {
                        //         $th[] = $total_kinerja;
                        //     } else {
                        //         $th[] = '<i class="fa fa-times"></i>';
                        //     }
                        //     if ($s_komunikasi == 1) {
                        //         $th[] = $komunikasi;
                        //     } else {
                        //         $th[] = '<i class="fa fa-times"></i>';
                        //     }
                        //     if ($s_transportasi == 1) {
                        //         $th[] = $transportasi;
                        //     } else {
                        //         $th[] = '<i class="fa fa-times"></i>';
                        //     }
                        // }
                    // if($row->pengguna_id == 65 || $row->pengguna_id == 62){
                    //     $transportasi   = 'Rp. 250.000,-';
                    // }
                    
                    // if($row->pengguna_id == 91){
                    //     $konsumsi   = 120000;
                    //     $kinerja    = 78200;
                    //     $tunjangan  = 198200;
                    //     $absen_approved = 8;
                    // }
                    
                    // if($row->pengguna_id == 93){
                    //     $konsumsi   = 60000;
                    //     $kinerja    = 39100;
                    //     $absen_approved = 4;
                    // }
                    
                    if($row->pengguna_id == 54){
                        $konsumsi   = 5000000;
                        $tunjangan   = 5000000;
                    }


                    ?>
                    <td style="text-align: center;"><?= $x++ ?></td>
                    <td><?= ucwords(strtolower($row->nama)) ?></td>
                    <td style="text-align: center;"><?= $row->no_pegawai ?></td>
                    <td style="text-align: center;"><?= ucwords(strtolower($row->status_karyawan)) ?></td>
                    <td><?= masaKerja($row->tgl_masuk) ?></td>
                    <td><?= $absen_approved ?> hari</td>
                    <td><?= $jabatan ? 'Rp. ' . rupiah($jabatan) : 'Rp. -' ?></td>
                    <td><?= $kinerja ? 'Rp. ' . rupiah($kinerja) : 'Rp. -' ?></td>
                    <td><?= $konsumsi ? 'Rp. ' . rupiah($konsumsi) : 'Rp. -' ?></td>
                    <td><?= $komunikasi ? 'Rp. ' . rupiah($komunikasi) : 'Rp. -' ?></td>
                    <td><?= $transportasi ? 'Rp. ' . rupiah($transportasi) : 'Rp. -' ?></td>
                     <td><?= $bbm ? 'Rp. ' . rupiah($bbm) : 'Rp. -' ?></td>
                     <td><?= $potongan1 ? 'Rp. ' . rupiah($potongan1) : 'Rp. -' ?></td>
                    <td><?= $tunjangan ? 'Rp. ' . rupiah($tunjangan) : 'Rp. -' ?></td>
                    <td><?= $row->no_rek ? $row->no_rek : '-' ?></td>
                </tr>

            <?php
                $sumJabatan += $jabatan;
                $sumKinerja += $kinerja;
                $sumKonsumsi += $konsumsi;
                $sumKomunikasi += $komunikasi;
                $sumTunjangan += $tunjangan;
                $sumbbm += $bbm;
                $sumtransportasi += $transportasi;
                $sumPotongan += $potongan1;
            } ?>
            <tr style="border-bottom: none;">

                <td colspan="6"><strong>Total Keseluruhan :</strong></td>

                <td><strong> Rp. <?= rupiah($sumJabatan) ?> </strong></td>
                <td><strong> Rp. <?= rupiah($sumKinerja) ?></strong></td>
                <td><strong> Rp. <?= rupiah($sumKonsumsi) ?></strong></td>
                <td><strong> Rp. <?= rupiah($sumKomunikasi) ?></strong></td>
                <td><strong>Rp. <?= rupiah($sumtransportasi) ?></strong></td>
                <td><strong>Rp. <?= rupiah($sumbbm) ?></strong></td>
                <td><strong> Rp. <?= rupiah($sumPotongan) ?></strong></td>
                <td><strong> Rp. <?= rupiah($sumTunjangan) ?></strong></td>
                <td><strong> </strong></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table border="0" style="border-collapse: collapse; width: 100%; height: 128px;">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px;">&nbsp;</td>
                 <td style="width: 25%; height: 18px;">&nbsp;</td>
                <td style="width: 21.8748%; height: 18px;">&nbsp;</td>
                <td style="width: 9.87224%; height: 18px;">&nbsp;</td>
                <td style="width: 13.4233%; height: 18px;">&nbsp;</td>
                <td style="width: 29.8296%; height: 18px; text-align: center;">Pekanbaru,<?= indo_dates(date('Y-m-d')) ?>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px; text-align: center;" colspan="1">Diajukan Oleh</td>
                <td style="height: 18px; width: 45.1703%; text-align: center;" colspan="4">Diverifikasi Oleh</td>
                <td style="width: 29.8296%; height: 18px; text-align: center;">Disetujui Oleh</td>
            </tr>
            <tr style="height: 56px;">
                <td style="width: 25%; height: 56px; text-align: center;" colspan="1">&nbsp;</td>
                <td style="width: 45.1703%; height: 56px; text-align: center;" colspan="4">&nbsp;</td>
                <td style="width: 29.8296%; height: 56px; text-align: center;">&nbsp;</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; text-align: center; height: 18px;"><u>Amtisari DE Putri</u></td>
                <td style="width: 25%; text-align: center; height: 18px;"><u>Azhari Pratama</u></td>
                <td style="width: 25%; text-align: center; height: 18px;"><u>Dirangga Madali</u></td>
                <td style="width: 21.8748%; height: 18px; text-align: center;"><u>Yolanda Pratiwi</u></td>
                <td style="width: 23.2955%; height: 18px; text-align: center;"><u>Meilina Safitri</u></td>
                <td style="width: 29.8296%; text-align: center; height: 18px;"><u>Bob Ariyos</u></td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 29.8296%; text-align: center; height: 18px;"><i>General Affair</i></td>
                <td style="width: 29.8296%; text-align: center; height: 18px;"><i>Senior Tax</i></td>
                <td style="width: 25%; text-align: center; height: 18px;"><i>Senior Accounting</i></td>
                <td style="width: 21.8748%; text-align: center; height: 18px;"><i>General Manager</i></td>
                <td style="width: 23.2955%; text-align: center; height: 18px;"><i>Director of Corp Planning & Management Bussinees</i></td>
                <td style="width: 29.8296%; text-align: center; height: 18px;"><i>Direktur</i></td>
            </tr>
        </tbody>
    </table>

</body>

</html>