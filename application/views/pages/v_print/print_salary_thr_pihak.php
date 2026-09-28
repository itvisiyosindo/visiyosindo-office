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
    <img src="assets/img/kop_baru.jpg" width="100%" height="15%" />
    <div style="text-align:center">
        <h2><?= $title_pdf ?></h2>
    </div>
    <div>
       <table id="table" style="width:100%">
        <thead class="text-center">
        <tr>
                            <th> No </th>
                            <th> Nama Karyawan</th>
                            <th width="100px" > NPP</th>
                            <th width="200px" > Jabatan</th>
                            <th> Lama Kerja</th>
                            <th> Gaji Pokok </th>
                            <th> Tunjangan Jabatan</th>
                            <th> Potongan PPh21</th>
                            <th> Total THR</th>
                            <th> Nomor Rekening</th>
                        </tr>
                        
        </thead>
        <tbody>
             <?php
                     $x = 1;
                    $sumgapok=0;
                    $sumbayar = 0;
                    foreach ($dtgaji as $row) {
                        
                            $idPengguna =  $row['pengguna_id'];

                            if (in_array($idPengguna, ['56'])) {


                            $nama =   $row['nama']; 
                            $lama = $row['tahun'].' Tahun '.$row['bulan'].' Bulan '.$row['hari'].' Hari'; 
                            
                            $gapok = isset($row['gajipokok']) ? $row['gajipokok'] : '';
                            $pendapatan_lain= isset($row['pendapatan_lain']) ? $row['pendapatan_lain'] : '';
                            $jabatan= $row['jabatan'];
                            $npp = $row['npp'];
                            $tunjanganJabatan =  $row['tunjanganjabatan'];
                            $pph21 = 170000;   
                            $thr = $gapok-$pph21;




                            $potongan_lain = potongan_lain($row['pengguna_id']);
                           
                            $no_rek   =  $row['norek'] ?  $row['norek'] : '-';
                            $sumbayar += $thr;




                
                       
                       $td = '
                           <tr>
                                 <td style="text-align: center;">' .$x++. '</td>
                                 <td>' .$nama. '</td>
                                 <td style="text-align: center;">' .$npp. '</td>
                                 <td>' .$jabatan. '</td>
                                 <td>' .$lama. '</td>
                                 <td style="text-align: right;">' .($gapok ? 'Rp. ' . rupiah($gapok) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($tunjanganJabatan ? 'Rp. ' . rupiah($tunjanganJabatan) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($pph21 ? 'Rp. ' . rupiah($pph21) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($thr ? 'Rp. ' . rupiah($thr) : 'Rp. -'). '</td>                             
                                 <td style="text-align: center;">' .$no_rek. '</td>
                            </tr>
                        ';
                        echo $td;
                        
                    }
                }
             ?> 
             
            <tr style="border-bottom: none;">
                <td colspan="8"><strong>Total Keseluruhan :</strong></td>
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
                <td style="width: 29.8296%; text-align: center; height: 18px;"><i>Head of Accounting and Tax</i></td>
                <td style="width: 25%; text-align: center; height: 18px;"><i>General Manager</i></td>
                <td style="width: 23.2955%; text-align: center; height: 18px;"><i>Director of Corp Planning & Business Management</i></td>
                <td style="width: 29.8296%; text-align: center; height: 18px;"><i>Direktur</i></td>
            </tr>
        </tbody>
    </table>
    </div>

</body>

</html>