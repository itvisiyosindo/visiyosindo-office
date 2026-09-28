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
    <img src="assets/img/kop_surat_vym_underline.png" width="100%" height="auto"/>
    <div style="text-align:center">
        <h2><?= $title_pdf ?></h2>
        <h3>Periode : <?= $periode ?></h3>
    </div>
    <table id="table" style="width:100%" height="70%">
        <thead class="text-center">
            <?php
                echo "<tr>";
                    echo "<th style='width:3%'> No </th>";
                    echo "<th style='width:5%'> Nama Marketing </th>";
                    echo "<th style='width:5%'> Tanggal Rencana </th>";
                    echo "<th style='width:3.5%'> Hari </th>";
                    echo "<th style='width:6%'> Nama Customer</th>";
                    if ($provinsi==1) : echo "<th style='width:6%'>Provinsi</th>"; endif;
                    if ($perjalanankerja==1) : echo "<th style='width:6%'>Perjalanan Kerja</th>"; endif;
                    if ($rencana==1) :   echo "<th style='width:5%'>Rencana Kerja</th>"; endif;
                    if ($tujuankegiatan==1) : echo "<th style='width:6%'>Tujuan Kerja</th>"; endif;
                    if ($tipecustomer==1) : echo "<th style='width:5.5%'>Tipe Customer</th>"; endif;
                    if ($pic==1) : echo "<th style='width:6%'>PIC</th>"; endif;
                    if ($tglrealisasi==1) : echo "<th style='width:6%'>Tanggal Realisasi</th>"; endif;
                    if ($realisasi==1) : echo "<th style='width:5%'>Realisasi</th>"; endif;
                    if ($funnelstatus==1) : echo "<th style='width:5%'>Funnel Status</th>"; endif;
                    if ($funnelstatus==1) : echo "<th style='width:5%'>Progress</th>"; endif;
                    if ($product==1) : echo "<th style='width:6%'>Product</th>"; endif;
                    if ($merk==1) : echo "<th style='width:5%'>Merk</th>"; endif;
                    if ($kompetitor==1) : echo "<th style='width:5%'>Kompetitor</th>"; endif;
                    if ($peluang==1) : echo "<th style='width:5%'>Peluang</th>"; endif;
                    if ($modality==1) : echo "<th style='width:4%'>Modality</th>"; endif;
                    if ($keterangan==1) : echo "<th style='width:8%'>Keterangan</th>"; endif;
                    if ($kendala==1) : echo "<th style='width:6%'>Kendala</th>"; endif;
                    if ($analisa==1) : echo "<th style='width:6%'>Hasil Analisa</th>"; endif;
                    if ($linkDokumentasi==1) : echo "<th style='width:6%'>Link Dokumentasi</th>"; endif;
                echo "</tr>";    
        ?>
        <tbody>
            <?php 
				$x=0;
				foreach ($data as $row) {
					$x = $x+1;
			        echo "<tr>";
    			        echo "<td>".$x."</td>";
    			        echo "<td>".$row->namamarketing."</td>";
    			        echo "<td>".'<i class="fa fa-clock-o"></i>'.date('Y-m-d',strtotime($row->tanggal));"</td>";
    			        echo "<td>".getHari($row->tanggal)."</td>";
    			        echo "<td>".$row->namacaloncustomer."</td>";
                        if ($provinsi==1) : echo "<td>".$row->provinsikota."</td>"; endif;
    			        if ($perjalanankerja==1) :echo "<td>".$row->jenispekerjaan."</td>"; endif;
    			        if ($rencana==1) :echo "<td>".$row->namastatus."</td>"; endif;
        			    if ($tujuankegiatan==1) :echo "<td>".$row->tujuankegiatan."</td>"; endif;   
        			    if ($tipecustomer==1) :echo "<td>".$row->tipecustomer."</td>"; endif;
    			        if ($pic==1) :echo "<td>".str_replace("()","", $row->pic)."</td>"; endif;
    			        if ($tglrealisasi==1) :echo "<td>".$row->tglrealisasi."</td>"; endif;
    			        if ($realisasi==1) :echo "<td>".$row->realisasi."</td>"; endif;
    			        if ($funnelstatus==1) :echo "<td>".$row->statusfunnel."</td>"; endif;
    			        if ($funnelstatus==1) :echo "<td>".$row->namaprogress."</td>"; endif;
    			        if ($product==1) :echo "<td>".$row->nama_barang."</td>"; endif;
    			        if ($merk==1) :echo "<td>".$row->nama_kategori."</td>"; endif;
    			        if ($kompetitor==1) :echo "<td>".$row->kompetitor."</td>"; endif;
    			        if ($peluang==1) :echo "<td>".$row->peluangkeberhasilan."</td>"; endif;
    			        if ($modality==1) :echo "<td>".$row->modality."</td>"; endif;
    			        if ($keterangan==1) :echo "<td>".$row->keterangan."</td>"; endif;
    			        if ($kendala==1) :echo "<td>".$row->kendala."</td>"; endif;
    			        if ($analisa==1) :echo "<td>".$row->hasilanalisa."</td>"; endif;
    			        if ($linkDokumentasi==1) :echo "<td>".$row->file_funnel."</td>"; endif;
				    echo "</tr>";
				}
			?>		
        </tbody>
    </table>
    <br>
   

</body>

</html>