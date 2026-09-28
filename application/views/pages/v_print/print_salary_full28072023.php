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
            font-size: 10Px;
        }

        #table td {
          
            font-size: 9Px;
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
                            <th> No </th>
                            <th> Nama Karyawan</th>
                            <th> Lama Kerja</th>
                            <th> Gaji Pokok </th>
                            <!--<th> Total Tunjangan</th>-->
                            <th> Pendapatan Lainnya</th>
                            <th> Potongan BPJS Kesehatan</th>
                            <th> Potongan BPJS Ketenagakerjaan</th>
                            <th> Potongan Lainnya</th>
                            <th> Penghasilan Sebelum Pajak</th>
                            <th> Pajak PPH21</th>
                            <th> Penghasilan Setelah Pajak</th>
                            <th> Nomor Rekening</th>
                            <!--<th> Tunjangan BPJS Kesehatan</th>-->
                            <!--<th> Tunjangan BPJS Ketenagakerjaan</th>-->
                        </tr>
                        
        </thead>
        <tbody>

            <?php $x = 1;
            $sumgapok=0;
            $sumKinerja = 0;
            $sumKonsumsi = 0;
            $sumbayar = 0;
            foreach ($dt as $row) {
            ?>
                <tr>
                    <?php
                    $tunBpjskes     = tunjanganBPJSKesehatan($row->pengguna_id);
                    $tunBpjstk      = tunjanganBPJStk($row->pengguna_id);
                    $jabatan        = tunjanganJabatan($row->pengguna_id);
                    $kinerja        = tunjangan($row->pengguna_id, $month)['total_kinerja'];
                    $konsumsi       = tunjangan($row->pengguna_id, $month)['total_konsumsi'];
                    $tunjangan      = 0;
                    $salary         = $this->md_salary->getById($row->id_latestriwayat_salary);
                    $gapok          = isset($salary[0]->gaji_pokok) ? $salary[0]->gaji_pokok : '';
                    $pendapatan_lain= isset($salary[0]->pendapatan_lain) ? $salary[0]->pendapatan_lain : '';
                    $potonganBPJSkes= potonganBPJSkes($row->pengguna_id)['bpjskes'];
                    $potongan_lain  = potongan_lain($row->pengguna_id);
                    $potonganBPJStk = potonganBPJStk($row->pengguna_id)['bpjstk'];
                    $absen_approved = tunjangan($row->pengguna_id, $month)['absen_approved'];
                    $sebelumPajak   = penghasilan_sebelumPajak($row->pengguna_id) + $tunjangan;
                    $pph21          = dasar_tarif($row->pengguna_id);
                    $setelahPajak   = $sebelumPajak-$pph21;

                    // if($pph21 == NULL){
                    //     echo $pph21='mantap';
                    // }else{
                    //     echo $pph21='ntap';
                    // }
if (ucwords(strtolower($row->nama)) == "Afrianto") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Agus Wicaksono") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Alfin Aswar Nurdiansyah") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Amtisari Destiani Eka Putri") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Athala Aqsa Yeni") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Azhari Pratama") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Bambang Trijaya") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Bastian Angga Putra") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Bob Ariyos") {
    $pph21 = 843749.6;
} elseif (ucwords(strtolower($row->nama)) == "Boddy Cahayadi") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Budi Pradikno") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Deni Oktavianto") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Deni Setiawan") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Dirangga Madali") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Fazry Perdana Putra") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Febrimon") {
    $pph21 = 10494.6;
} elseif (ucwords(strtolower($row->nama)) == "Gendi Yusdianto") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Haridho Rezki") {
    $pph21 = 671796.6;
} elseif (ucwords(strtolower($row->nama)) == "Jeny Jhonita") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Jovan Yoga Pratama") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Kardonal") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Mega Ratu") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Meilina Safitri") {
    $pph21 = 133938.6;
} elseif (ucwords(strtolower($row->nama)) == "Muhammad Thamrin") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Neysha Verlanda") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Novi Dewi Elmita") {
    $pph21 = 178750;
} elseif (ucwords(strtolower($row->nama)) == "Nuradilah") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Prassetya Agus Purnama") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Reza Karina Rashid") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Ricky Arindi") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Rizki Sabtu Perdana") {
    $pph21 = 52994.55;
} elseif (ucwords(strtolower($row->nama)) == "Rosmaniar") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Saida Dita Hanifawati") {
    $pph21 = 14739.6;
} elseif (ucwords(strtolower($row->nama)) == "Silvia Miftaviana") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Sry Rahayu") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Sulistyo Harmoko") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Suwandi") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Vera Puspita Sari") {
    $pph21 = 0;
} elseif (ucwords(strtolower($row->nama)) == "Yolanda Pratiwi") {
    $pph21 = 27064;
}
if (ucwords(strtolower($row->nama)) == "Afrianto") {
    $setelahPajak = 3000000;
} elseif (ucwords(strtolower($row->nama)) == "Agus Wicaksono") {
    $setelahPajak = 4711538;
} elseif (ucwords(strtolower($row->nama)) == "Alfin Aswar Nurdiansyah") {
    $setelahPajak = 3134968;
} elseif (ucwords(strtolower($row->nama)) == "Amtisari Destiani Eka Putri") {
    $setelahPajak = 3219453;
} elseif (ucwords(strtolower($row->nama)) == "Athala Aqsa Yeni") {
    $setelahPajak = 3285833;
} elseif (ucwords(strtolower($row->nama)) == "Azhari Pratama") {
    $setelahPajak = 3219453;
} elseif (ucwords(strtolower($row->nama)) == "Bambang Trijaya") {
    $setelahPajak = 4754745;
} elseif (ucwords(strtolower($row->nama)) == "Bastian Angga Putra") {
    $setelahPajak = 3219453;
} elseif (ucwords(strtolower($row->nama)) == "Bob Ariyos") {
    $setelahPajak = 13056249.6;
} elseif (ucwords(strtolower($row->nama)) == "Boddy Cahayadi") {
    $setelahPajak = 4219023;
} elseif (ucwords(strtolower($row->nama)) == "Budi Pradikno") {
    $setelahPajak = 2000000;
} elseif (ucwords(strtolower($row->nama)) == "Deni Oktavianto") {
    $setelahPajak = 4525479;
} elseif (ucwords(strtolower($row->nama)) == "Deni Setiawan") {
    $setelahPajak = 3285833;
} elseif (ucwords(strtolower($row->nama)) == "Dirangga Madali") {
    $setelahPajak = 3319023;
} elseif (ucwords(strtolower($row->nama)) == "Fazry Perdana Putra") {
    $setelahPajak = 3319023;
} elseif (ucwords(strtolower($row->nama)) == "Febrimon") {
    $setelahPajak = 4744249.6;
} elseif (ucwords(strtolower($row->nama)) == "Gendi Yusdianto") {
    $setelahPajak = 0;
} elseif (ucwords(strtolower($row->nama)) == "Haridho Rezki") {
    $setelahPajak = 2547655.55;
} elseif (ucwords(strtolower($row->nama)) == "Jeny Jhonita") {
    $setelahPajak = 3219453;
} elseif (ucwords(strtolower($row->nama)) == "Jovan Yoga Pratama") {
    $setelahPajak = 3285833;
} elseif (ucwords(strtolower($row->nama)) == "Kardonal") {
    $setelahPajak = 3715663;
} elseif (ucwords(strtolower($row->nama)) == "Mega Ratu") {
    $setelahPajak = 3219453;
} elseif (ucwords(strtolower($row->nama)) == "Meilina Safitri") {
    $setelahPajak = 3083723.55;
} elseif (ucwords(strtolower($row->nama)) == "Muhammad Thamrin") {
    $setelahPajak = 3461538;
} elseif (ucwords(strtolower($row->nama)) == "Neysha Verlanda") {
    $setelahPajak = 3285833;
} elseif (ucwords(strtolower($row->nama)) == "Novi Dewi Elmita") {
    $setelahPajak = 8321250;
} elseif (ucwords(strtolower($row->nama)) == "Nuradilah") {
    $setelahPajak = 3219453;
} elseif (ucwords(strtolower($row->nama)) == "Prassetya Agus Purnama") {
    $setelahPajak = 2500000;
} elseif (ucwords(strtolower($row->nama)) == "Reza Karina Rashid") {
    $setelahPajak = 0;
} elseif (ucwords(strtolower($row->nama)) == "Ricky Arindi") {
    $setelahPajak = 3219453;
} elseif (ucwords(strtolower($row->nama)) == "Rizki Sabtu Perdana") {
    $setelahPajak = 4701749.55;
} elseif (ucwords(strtolower($row->nama)) == "Rosmaniar") {
    $setelahPajak = 2000000;
} elseif (ucwords(strtolower($row->nama)) == "Saida Dita Hanifawati") {
    $setelahPajak = 3204712.55;
} elseif (ucwords(strtolower($row->nama)) == "Silvia Miftaviana") {
    $setelahPajak = 0;
} elseif (ucwords(strtolower($row->nama)) == "Sry Rahayu") {
    $setelahPajak = 3219453;
} elseif (ucwords(strtolower($row->nama)) == "Sulistyo Harmoko") {
    $setelahPajak = 2324775;
} elseif (ucwords(strtolower($row->nama)) == "Suwandi") {
    $setelahPajak = 3285833;
} elseif (ucwords(strtolower($row->nama)) == "Vera Puspita Sari") {
    $setelahPajak = 3319023;
} elseif (ucwords(strtolower($row->nama)) == "Yolanda Pratiwi") {
    $setelahPajak = 3192389;
}

                    ?>
                    <td style="text-align: center;"><?= $x++ ?></td>
                    <td><?= ucwords(strtolower($row->nama)) ?></td>
                    <td><?= masaKerja($row->tgl_masuk) ?></td>

                    <td><?= $gapok ? 'Rp. ' . rupiah($gapok) : 'Rp. -' ?></td>
                    <!--<td><?= $tunjangan ? 'Rp. ' . rupiah($tunjangan) : 'Rp. -' ?></td>-->
                    <td><?= $pendapatan_lain ? 'Rp. ' . rupiah($pendapatan_lain) : 'Rp. -' ?></td>
                    <td><?= $potonganBPJSkes ? 'Rp. ' . rupiah($potonganBPJSkes) : 'Rp. -' ?></td>
                    <td><?= $potonganBPJStk ? 'Rp. ' . rupiah($potonganBPJStk) : 'Rp. -' ?></td>
                    <td><?= $potongan_lain ? 'Rp. ' . rupiah($potongan_lain) : 'Rp. -' ?></td>
                    <td><?= $sebelumPajak ? 'Rp. ' . rupiah($sebelumPajak) : 'Rp. -' ?></td>
                    <td><?= $pph21 ? 'Rp. ' . rupiah($pph21) : 'Rp. -' ?></td>
                    <td><?= $setelahPajak ? 'Rp. ' . rupiah($setelahPajak) : 'Rp. -' ?></td>


                    <td><?= $row->no_rek ? $row->no_rek : '-' ?></td>
                    <!--<td><?= $tunBpjskes ? 'Rp. ' . rupiah($tunBpjskes) : 'Rp. -' ?></td>-->
                    <!--<td><?= $tunBpjstk  ? 'Rp. ' . rupiah($tunBpjstk ) : 'Rp. -' ?></td>-->
                </tr>

            <?php
                $sumgapok += $gapok;
                $sumKinerja += $kinerja;
                $sumKonsumsi += $konsumsi;
                $sumbayar += $setelahPajak;
            } ?>
            <tr style="border-bottom: none;">

                <td colspan="10"><strong>Total Keseluruhan :</strong></td>

                <td><strong> Rp. <?= rupiah($sumbayar) ?></strong></td>
               <td><strong></strong></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table style="border-collapse: collapse; width: 100%; height: 128px;">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px;">&nbsp;</td>
                <td style="width: 21.8748%; height: 18px;">&nbsp;</td>
                <td style="width: 9.87224%; height: 18px;">&nbsp;</td>
                <td style="width: 13.4233%; height: 18px;">&nbsp;</td>
                <td style="width: 13.4233%; height: 18px;">&nbsp;</td>
                <td style="width: 29.8296%; height: 18px; text-align: center;">Pekanbaru,
                    <?= indo_dates(date('Y-m-d')) ?>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px; text-align: center;">Diajukan Oleh</td>
                <td style="height: 18px; width: 45.1703%; text-align: center;" colspan="4">Diverifikasi Oleh</td>
                <td style="width: 29.8296%; height: 18px; text-align: center;">Disetujui Oleh</td>
            </tr>
            <tr style="height: 56px;">
                <td style="width: 25%; height: 56px; text-align: center;">&nbsp;</td>
                <td style="width: 45.1703%; height: 56px; text-align: center;" colspan="4">&nbsp;</td>
                <td style="width: 29.8296%; height: 56px; text-align: center;">&nbsp;</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; text-align: center; height: 18px;"><u>Amtisari Destiani Eka Putri</u></td>
                <td style="width: 25%; text-align: center; height: 18px;"><u>Dirangga Madali</u></td>
                <td style="width: 21.8748%; height: 18px; text-align: center;"><u>Azhari Pratama</u></td>
                <td style="width: 23.2955%; height: 18px; text-align: center;"><u>Yolanda Pratiwi</u></td>
                <td style="width: 23.2955%; height: 18px; text-align: center;"><u>Meilina Safitri</u></td>
                <td style="width: 29.8296%; text-align: center; height: 18px;"><u>Bob Ariyos</u></td>
            </tr>
            <tr style="height: 18px;">
                                <td style="width: 29.8296%; text-align: center; height: 18px;"><i>Admin Accounting</i></td>
                <td style="width: 29.8296%; text-align: center; height: 18px;"><i>Senior Accounting And Finance</i></td>

                <td style="width: 21.8748%; text-align: center; height: 18px;"><i>Senior Tax and Accounting</i></td>
                <td style="width: 25%; text-align: center; height: 18px;"><i>Pimpuinan Umum</i></td>
                
                <td style="width: 23.2955%; text-align: center; height: 18px;"><i>Director of Corp Planning & Management Bussinees</i></td>
                <td style="width: 29.8296%; text-align: center; height: 18px;"><i>Direktur</i></td>
            </tr>
        </tbody>
    </table>

</body>

</html>