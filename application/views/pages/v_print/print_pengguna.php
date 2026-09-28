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
    <img src="assets/img/kop_baru.jpg" width="100%" height="auto"/>
    <div style="text-align:center">
        <h2><?= $title_pdf ?></h2>
    </div>
    <table id="table" style="width:100%" height="70%">
        <thead class="text-center">
            <?php
                echo "<tr>";
                    echo "<th style='width:3%'> No </th>";
                    echo "<th style='width:25%'> Nama </th>";
                    echo "<th style='width:7%'> NPP</th>";
                    echo "<th style='width:7%'> Jabatan </th>";
                    echo "<th style='width:15%'> Status Kepegawaian </th>";
                    echo "<th style='width:10%'> Tanggal Masuk </th>";
                    echo "<th style='width:15%'> Lama Bekerja </th>";
                echo "</tr>";    
        ?>
        <tbody>
            <?php 
				$x=0;
				foreach ($data as $row) {
					$x = $x+1;


                        $hari_ini = date('Y-m-d');
                        $tanggal_masuk = new DateTime($row->tgl_kontrak); // Tanggal masuk
                        $tanggal_hari_ini = new DateTime($hari_ini); // Tanggal hari ini
                        $selisih = $tanggal_masuk->diff($tanggal_hari_ini); // Menghitung selisih
                        $tahun = $selisih->y;
                        $bulan = $selisih->m;
                        $hari = $selisih->d;

                        if ($tahun == 0) {
                            // Tetap mencantumkan bulan dan hari jika belum mencapai 1 tahun
                            $lama = $bulan . ' Bulan ' . $hari . ' Hari';
                        } else {
                            // Jika sudah lebih dari 1 tahun
                            $lama = $tahun . ' Tahun ' . $bulan . ' Bulan ' . $hari . ' Hari';
                        }
                        
                    $status_karyawan = $row->status_karyawan;
                    if ($status_karyawan == 'kontrak') {
                        $status_text = 'Kontrak';
                    } elseif ($status_karyawan == 'training') {
                        $status_text = 'Training';
                    } elseif ($status_karyawan == 'tetap') {
                        $status_text = 'Tetap';
                    } else {
                        $status_text = '-'; // kalau kosong atau tidak dikenal
                    }



			        echo "<tr>";
    			        echo "<td style='text-align:center;'>".$x."</td>";
    			        echo "<td>".$row->nama."</td>";
    			        echo "<td>".$row->no_pegawai."</td>";
    			        echo "<td>".$row->jabatan."</td>";
                        echo "<td>".(!empty($status_text) ? $status_text : '-')."</td>";
    			        echo "<td>".(!empty($row->tgl_kontrak) ? date('d-m-Y', strtotime($row->tgl_kontrak)) : '-')."</td>";
                        echo "<td>".(!empty($lama) ? $lama : '-')."</td>";

				    echo "</tr>";
				}
			?>		
        </tbody>
    </table>
    <br>
   

</body>

</html>