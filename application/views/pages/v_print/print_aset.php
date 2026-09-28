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
    <img src="assets/img/kop_landscape.jpg" width="100%" height="auto"/>
    <div style="text-align:center">
        <h2><?= $title_pdf ?></h2>
    </div>
    <table id="table" style="width:100%" height="70%">
        <thead class="text-center">
            <?php
                echo "<tr>";
                    echo "<th style='width:3%'> No </th>";
                    echo "<th style='width:25%'> Nama  </th>";
                    echo "<th style='width:7%'> Serial Number / Kode</th>";
                    echo "<th style='width:7%'> Kategori </th>";
                    echo "<th style='width:10%'> Posisi </th>";
                    echo "<th style='width:15%'> PIC </th>";
                echo "</tr>";    
        ?>
        <tbody>
            <?php 
				$x=0;
				foreach ($data as $row) {
					$x = $x+1;
			        echo "<tr>";
    			        echo "<td style='text-align:center;'>".$x."</td>";
    			        echo "<td>".$row->nama_aset."</td>";
    			        echo "<td>".$row->kode."</td>";
    			        echo "<td>".$row->kategori."</td>";
    			        echo "<td>".$row->posisi."</td>";
    			        echo "<td>".$row->nama_pengguna."</td>";
				    echo "</tr>";
				}
			?>		
        </tbody>
    </table>
    <br>
   

</body>

</html>