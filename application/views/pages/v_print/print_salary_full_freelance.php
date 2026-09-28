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
        <h3>Periode : <?= $periode ?></h3>
    </div>
    <div>
       <table id="table" style="width:100%">
        <thead class="text-center">
        <tr>
                            <th> No </th>
                            <th> Nama Karyawan</th>
                            <th> Gaji Pokok </th>
                            <th> Tunjangan Jabatan</th>
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
                    $sumbayar = 0;
                    foreach ($dtgaji as $row) {
                            $nama =   $row['nama']; 
                            $gaji_pokok =   $row['gajipokok'];
                            $tunjanganJabatan =   $row['tunjangan_jabatan'];
                            $gapok = 0;
                            $pph21 = 12500;
                            $no_rek   =  $row['norek'] ?  $row['norek'] : '-';
                            $idPengguna =  $row['pengguna_id'];
                            $potongan_lain = potongan_lain($idPengguna);
                            $sebelumPajak = $tunjanganJabatan-$potongan_lain;
                            $total = $tunjanganJabatan-$pph21-$potongan_lain;
                            
                          
                            $sumbayar += $total;

                            //Tambahkan Salary ke tabel history_salary
                                $data['tahun'] = $tahun;
                                $data['bulan'] = $bulan;
                                $data['nama'] = $nama;
                                $data['no_rek'] = $no_rek;
                                $data['idpengguna'] = $idPengguna;
                                $data['gajipokok'] = $gapok;
                                $data['pph21'] = $pph21;
                                $data['tunjanganjabatan'] = $tunjanganJabatan;
                                $this->md_salary->addHistorySalary($data);

                              
                       
                       $td = '
                           <tr>
                                 <td style="text-align: center;">' .$x++. '</td>
                                 <td>' .$nama. '</td>
                                 <td style="text-align: right;">' .($gaji_pokok ? 'Rp. ' . rupiah($gaji_pokok) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($tunjanganJabatan ? 'Rp. ' . rupiah($tunjanganJabatan) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($gapok ? 'Rp. ' . rupiah($gapok) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($gapok ? 'Rp. ' . rupiah($gapok) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($gapok ? 'Rp. ' . rupiah($gapok) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($potongan_lain ? 'Rp. ' . rupiah($potongan_lain) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($sebelumPajak ? 'Rp. ' . rupiah($sebelumPajak) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($pph21 ? 'Rp. ' . rupiah($pph21) : 'Rp. -'). '</td>
                                 <td style="text-align: right;">' .($total? 'Rp. ' . rupiah($total) : 'Rp. -'). '</td>                                 
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
            <td style="height: 80px; text-align: center;">&nbsp;</td>
            <td style="height: 80px; text-align: center;">&nbsp;</td>
            <td style="height: 80px; text-align: center;">&nbsp;</td>
        </tr>
        
        <tr>
            <td style="width: 33.33%; text-align: center;"><u>Novemby Ardiansyah Putra</u></td>
            <td style="width: 33.33%; text-align: center;"><u>Mulia</u></td>
            <td style="width: 33.33%; text-align: center;"><u>Dian Melati Amelia</u></td>
        </tr>
        
        <tr>
            <td style="width: 33.33%; text-align: center;"><i>General Affair</i></td>
            <td style="width: 33.33%; text-align: center;"><i>Staff Accounting</i></td>
            <td style="width: 33.33%; text-align: center;"><i>HR & Legal</i></td>
        </tr>
    </tbody>
</table>
    </div>

</body>

</html>