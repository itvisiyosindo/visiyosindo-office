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
    <img src="assets/img/kop_surat_vym_underline.png" width="100%" height="15%" />
    <div style="text-align:center">
        <h2><?= $title_pdf ?></h2>
        <h3>Periode : <?= $periode ?></h3>
    </div>
    <div>
       <table id="table" style="width:100%">
        <thead class="text-center">
        <tr>
                            <th> No </th>
                            <th> Nama Karyawan</th>
                            <th> Lama Kerja</th>
                            <th> Gaji Pokok </th>
                            <th> Pendapatan Lainnya</th>
                            <th> Potongan BPJS Kesehatan</th>
                            <th> Potongan BPJS TK</th>
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
                            $nama =   $row['nama']; 
                            $lama = $row['tahun'].' Tahun '.$row['bulan'].' Bulan '.$row['hari'].' Hari'; 
                            if($row['tahun']>0){
                                $temptahun = 12;
                            }else{
                                $temptahun = $row['bulan'];
                            }
                            $gapok = isset($row['gajipokok']) ? $row['gajipokok'] : '';
                            $pendapatan_lain= isset($row['pendapatan_lain']) ? $row['pendapatan_lain'] : '';
                            $potonganBPJSkes= $row['bpjskkaryawan'];
                            $potonganBPJStk = $row['bpjstk2persen'];
                            $potongan_lain = potongan_lain($row['pengguna_id']);
                            $sebelumPajak   =  ($gapok+$pendapatan_lain)-($potonganBPJSkes+$potonganBPJStk+$potongan_lain);
                            $Bruto = $row['Bruto'];
                            $TotalPengurang = $row['TotalPengurangan'];
                            $no_rek   =  $row['norek'] ?  $row['norek'] : '-';
                            $pajakdibayar=$row['pajakdibayarkan'] ?  $row['pajakdibayarkan'] : 0;
                            $pph21=0;
                            $d37=0;
                            $e37=0;
                            $d38=0;
                            $e38=0;
                            $d39=0;
                            $e39=0;
                            $pbersih=  ($row['Net']*$temptahun)+$row['totalbonus'];
                            $ptkp = $row['jumlahptkp'];
                            
                            $e34=$pbersih-$ptkp;
                            if($e34>0){
                                //=IF(AND(E34>0;E34<60000000);E34;60000000)
                                if(($e34>0)&&($e34<60000000)){
                                    $d37=$e34;
                                    $e37=$d37*0.05;                       
                                }else{
                                    $d37=60000000;
                                    $e37=$d37*0.05; 
                                }

                                //=IF(AND(E34-D37>0;E34-D37<=250000000);(E34-D37);IF(AND(E34-D37>60000000;E34-D37>=250000000);250000000;0))
                                if((($e34-$d37)>0)&&(($e34-$d37)<=250000000)){
                                    $d38=($e34-$d37);
                                    $e38=$d38*0.15;
                                }else{
                                    if((($e34-$d37)>60000000)&&(($e34-$d37)>=250000000)){
                                        $d38=250000000;
                                        $e38=$d38*0.15;
                                    }else{
                                        $d38=0;
                                        $e38=0;
                                    }
                                }

                                //=IF(AND(E34-D37-D38>0;E34-D37-D38<=500000000);(E34-D37-D38);IF(AND(E34-D37-D38>250000000;E34-D37-D38>=500000000);500000000;0))
                                if((($e34-$d37-$d38)>0)&&(($e34-$d37-$d38)<=500000000)){
                                    $d39=($e34-$d37-$d38);
                                    $e39=$d39*0.25;
                                }else{
                                    if((($e34-$d37-$d38)>250000000)&&(($e34-$d37-$d38)>=500000000)){
                                        $d39=500000000;
                                        $e39=$d39*0.25;
                                    }else{
                                        $d39=0;
                                        $e39=0;
                                    }
                                }
                                 $pph21 = (($e37+$e38+$e39)/$temptahun);
                            }else{
                                $pph21=0;
                            }
                                $pph21 -= $pajakdibayar;
                                $setelahPajak = $sebelumPajak-$pph21;
                                $sumbayar += $setelahPajak;
                            
                       
                       $td = '
                            <tr>
                                 <td style="text-align: center;">' .$x++. '</td>
                                 <td>' .$nama. '</td>
                                 <td>' .$lama. '</td>
                                 <td style="text-align: right;">' .($gapok ? 'Rp. ' . rupiah($gapok) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($pendapatan_lain ? 'Rp. ' . rupiah($pendapatan_lain) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($potonganBPJSkes ? 'Rp. ' . rupiah($potonganBPJSkes) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($potonganBPJStk ? 'Rp. ' . rupiah($potonganBPJStk) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($potongan_lain ? 'Rp. ' . rupiah($potongan_lain) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($sebelumPajak ? 'Rp. ' . rupiah($sebelumPajak) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($pph21 ? 'Rp. ' . rupiah($pph21) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($setelahPajak ? 'Rp. ' . rupiah($setelahPajak) : 'Rp. -'). '</td>                                 
                                 <td style="text-align: center;">' .$no_rek. '</td>
                            </tr>
                        ';
                        echo $td;
                        
                    }
             ?> 
             
            <tr style="border-bottom: none;">
                <td colspan="10"><strong>Total Keseluruhan :</strong></td>
                <td style="text-align: right;"><strong> Rp. <?= rupiah($sumbayar) ?></strong></td>
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
                <td style="width: 21.8748%; height: 18px; text-align: center;"><u>Azhari Pratama</u></td>
                <td style="width: 25%; text-align: center; height: 18px;"><u>Dirangga Madali</u></td>
                <td style="width: 23.2955%; height: 18px; text-align: center;"><u>Yolanda Pratiwi</u></td>
                <td style="width: 23.2955%; height: 18px; text-align: center;"><u>Meilina Safitri</u></td>
                <td style="width: 29.8296%; text-align: center; height: 18px;"><u>Bob Ariyos</u></td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 29.8296%; text-align: center; height: 18px;"><i>General Affair</i></td>
                <td style="width: 21.8748%; text-align: center; height: 18px;"><i>Senior Tax</i></td>
                <td style="width: 29.8296%; text-align: center; height: 18px;"><i>Senior Accounting</i></td>
                <td style="width: 25%; text-align: center; height: 18px;"><i>General Manager</i></td>
                <td style="width: 23.2955%; text-align: center; height: 18px;"><i>Director of Corp Planning & Management Bussinees</i></td>
                <td style="width: 29.8296%; text-align: center; height: 18px;"><i>Direktur</i></td>
            </tr>
        </tbody>
    </table>
    </div>

</body>

</html>