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
                    if ($kode_fpp==1) : echo "<th style='width:6%'>No FPP</th>"; endif;
                    if ($csname==1) : echo "<th style='width:6%'>Nama Customer</th>"; endif;
                    if ($alamat==1) : echo "<th style='width:6%'>Alamat</th>"; endif;
                    if ($tgl==1) : echo "<th style='width:6%'>Tanggal</th>"; endif;
                    if ($cpname==1) :   echo "<th style='width:5%'>Contact Person</th>"; endif;
                    if ($nocp==1) : echo "<th style='width:6%'>No CP</th>"; endif;
                    if ($payment==1) : echo "<th style='width:5.5%'>Term of Payment</th>"; endif;
                    if ($nama_barang==1) : echo "<th style='width:8%'>Nama Barang</th>"; endif;
                    if ($harga_ditawarkan==1) : echo "<th style='width:7%'>Harga Ditawarkan</th>"; endif;
                    if ($notes==1) : echo "<th style='width:6%'>Notes</th>"; endif;
                    if ($no_sph==1) : echo "<th style='width:6%'>No SPH</th>"; endif;
                    if ($link_sph==1) : echo "<th style='width:6%'>Link SPH</th>"; endif;
                    if ($link_approval==1) : echo "<th style='width:5%'>Link Approval</th>"; endif;
                echo "</tr>";    
        ?>
        <tbody>
            <?php 
				$x=0;
				foreach ($data as $row) {
					$x = $x+1;
					$items = isset($data_items[$x-1]) ? $data_items[$x-1] : [(object)['des' => '', 'pri' => '']];
					$itemCount = count($items);
					foreach ($items as $idx => $item) {
						echo "<tr>";
						if ($idx === 0) {
							$spanAttr = $itemCount > 1 ? " rowspan='$itemCount'" : '';
							echo "<td$spanAttr style='text-align:center;vertical-align:middle'>".$x."</td>";
							if ($kode_fpp==1) : echo "<td$spanAttr style='vertical-align:middle'>".$row->kode_fpp."</td>"; endif;
							if ($csname==1) : echo "<td$spanAttr style='vertical-align:middle'>".$row->csname."</td>"; endif;
							if ($alamat==1) : echo "<td$spanAttr style='vertical-align:middle'>".$row->alamat."</td>"; endif;
							if ($tgl==1) : echo "<td$spanAttr style='vertical-align:middle'>".$row->tgl."</td>"; endif;
							if ($cpname==1) : echo "<td$spanAttr style='vertical-align:middle'>".$row->cpname."</td>"; endif;
							if ($nocp==1) : echo "<td$spanAttr style='vertical-align:middle'>".$row->nocp."</td>"; endif;
							if ($payment==1) : echo "<td$spanAttr style='vertical-align:middle'>".$row->payment."</td>"; endif;
						}
						if ($nama_barang==1) : echo "<td>".($item->des ?? '')."</td>"; endif;
						if ($harga_ditawarkan==1) : echo "<td style='text-align:right'>".(!empty($item->pri) && is_numeric($item->pri) ? number_format((float)$item->pri, 0, ',', '.') : ($item->pri ?? ''))."</td>"; endif;
						if ($idx === 0) {
							$spanAttr = $itemCount > 1 ? " rowspan='$itemCount'" : '';
							if ($notes==1) : echo "<td$spanAttr style='vertical-align:middle'>".str_replace("()","", $row->notes)."</td>"; endif;
							if ($no_sph==1) : echo "<td$spanAttr style='vertical-align:middle'>".$row->no_sph."</td>"; endif;
							if ($link_sph==1) : echo "<td$spanAttr style='vertical-align:middle'>".$row->link_sph."</td>"; endif;
							if ($link_approval==1) : echo "<td$spanAttr style='vertical-align:middle'>".$row->link_approval."</td>"; endif;
						}
						echo "</tr>";
					}
				}
			?>		
        </tbody>
    </table>
    <br>
   

</body>

</html>