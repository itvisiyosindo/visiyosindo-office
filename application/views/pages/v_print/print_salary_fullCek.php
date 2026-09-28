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
                            <!-- <th> Lama Kerja</th> -->
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
                        </tr>
                        
        </thead>
        <tbody>

            <?php 
            $x = 1;
            $sumgapok=0;
            $sumKinerja = 0;
            $sumKonsumsi = 0;
            $sumbayar = 0;
            foreach ($dtgaji as $row) {
            ?>
                <tr>
                    <?php
                    // $tunBpjskes     = tunjanganBPJSKesehatan($row->pengguna_id);
                    // $tunBpjstk      = tunjanganBPJStk($row->pengguna_id);
                    // $jabatan        = tunjanganJabatan($row->pengguna_id);
                    // $kinerja        = tunjangan($row->pengguna_id, $month)['total_kinerja'];
                    // $konsumsi       = tunjangan($row->pengguna_id, $month)['total_konsumsi'];
                    // $tunjangan      = 0;
                    // $salary         = $this->md_salary->getById($row->id_latestriwayat_salary);
                     $gapok          = isset($row['gajipokok']) ? $row['gajipokok'] : 0;
                      $pendapatan_lain= isset($row['pendapatanlain']) ? $row['pendapatanlain'] : 0;
                       $potonganBPJSkes= $row['bpjskkaryawan'];
                       $potonganBPJStk = $row['bpjstkkaryawan'];
                       $potongan_lain  = $row['potongan'];
                    // // // $absen_approved = tunjangan($row->pengguna_id, $month)['absen_approved'];
                       $sebelumPajak   = ($gapok+$pendapatan_lain)-($potonganBPJSkes+$potonganBPJStk);
                      $pph21          = $row['pkp'];
                     if($pph21<0){
                        $pkp = 0;
                     }else{
                        if(($pph21>0)&&($pph21<60000000)){
                            $pkp = $pph21*0.05;
                         }else{
                            $pkp = 60000000*0.05;
                         }
                         $pkp = $pkp/12; 
                     }

                     $setelahPajak   = $sebelumPajak-$pkp;


                    ?>
                    <td style="text-align: center;"><?= $x++ ?></td>
                    <td><?= ucwords(strtolower($row['nama'])) ?></td>
                    <!-- <td><?= masaKerja($row['tgl_masuk']) ?></td>  -->

                    <td><?= $gapok ? 'Rp. ' . rupiah($gapok) : 'Rp. -' ?></td>
                     <td><?= $pendapatan_lain ? 'Rp. ' . rupiah($pendapatan_lain) : 'Rp. -' ?></td>
                    <td><?= $potonganBPJSkes ? 'Rp. ' . rupiah($potonganBPJSkes) : 'Rp. -' ?></td>
                    <td><?= $potonganBPJStk ? 'Rp. ' . rupiah($potonganBPJStk) : 'Rp. -' ?></td>
                    <td><?= $potongan_lain ? 'Rp. ' . rupiah($potongan_lain) : 'Rp. -' ?></td>
                    <td><?= $sebelumPajak ? 'Rp. ' . rupiah($sebelumPajak) : 'Rp. -' ?></td>
                    <td><?= $pkp ? 'Rp. ' . rupiah($pkp) : 'Rp. -' ?></td>
                    <td><?= $setelahPajak ? 'Rp. ' . rupiah($setelahPajak) : 'Rp. -' ?></td>
                    <td><?= ($row['norek']) ?></td>
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
                <td style="width: 29.8296%; text-align: center; height: 18px;"><i>Senior Accounting & Finance</i></td>

                <td style="width: 21.8748%; text-align: center; height: 18px;"><i>Senior Tax and Accounting</i></td>
                <td style="width: 25%; text-align: center; height: 18px;"><i>Pimpuinan Umum</i></td>
                
                <td style="width: 23.2955%; text-align: center; height: 18px;"><i>Director of Corp Planning & Management Bussinees</i></td>
                <td style="width: 29.8296%; text-align: center; height: 18px;"><i>Direktur</i></td>
            </tr>
        </tbody>
    </table>

</body>

</html>