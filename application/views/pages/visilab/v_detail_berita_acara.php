<header class="page-header">
    <h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
	
	<style>
		hr{
		   display: block;
		   margin-top: 0em;
		   margin-bottom: 0em;
		   margin-left: auto;
		   margin-right: auto;
		   border-top: 1px solid black;
		}
		
		input{
			width:97%;
			height:auto;
			border:0px dotted #f30; 
			border-radius:4px; 
			-moz-border-radius:8px;			
			margin-right:0px;
		}
		
		.myinput{
			width:97%;
			height:auto;
			border:0px solid #000;
			border-radius:0px; 
			-moz-border-radius:8px;
			margin-left:0px;
			background:#b7d5ac;
		}
		
		.myselect{
			width:97%;
			height:auto;
			border:0px solid #000; 
			border-radius:4px; 
			-moz-border-radius:8px;
			margin:0px;
		}
		
		.mydiv br {
			display: none;
		}
		
		.mydiv p {
			padding: 0;
			margin: 0;
		}
	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">

		<div class="text-center">
			<h2 style="color:#000000; font-family:'Times New Roman'; font-weight:bold;">BERITA ACARA</h2>
			<h4 style="color:#000000; font-family:'Times New Roman'; font-weight:bold; text-decoration: underline;">
					No : <?= $data_ba[0]->kode_ba ?><br><br>
			</h4>
		</div>

		<?php
					$ttd = '';
					$id_pengguna = sessPenggunaId();

					if ($id_pengguna == $data_ba[0]->id_ttd1) {
							$ttd = 'ttd_1';
					} else if ($id_pengguna == $data_ba[0]->id_ttd2) {
							$ttd = 'ttd_2';
					} else if ($id_pengguna == $data_ba[0]->id_ttd3) {
							$ttd = 'ttd_3';
					} else if ($id_pengguna == $data_ba[0]->id_ttd4) {
							$ttd = 'ttd_4';
					} else if ($id_pengguna == $data_ba[0]->id_ttd5) {
							$ttd = 'ttd_5';
					}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<?php $id = $data_ba[0]->id_ba ?>
		<input type="hidden" name="id" id="id" value="<?= $data_ba[0]->id_ba ?>">

	    <div class="table-responsive">
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>	
							<font color='#ffffff'>
								<?= 
									$tanggal		= strtotime($data_ba[0]->tanggal);
									$hari 	= date("D", $tanggal);
									$tgl 	= date("d", $tanggal);
									$bln 	= date("m", $tanggal);
									$thn 	= date("Y", $tanggal);
									switch($hari){
										case 'Sun':
											$hari = "Minggu";
										break;
										case 'Mon':         
											$hari = "Senin";
										break;
										case 'Tue':
											$hari = "Selasa";
										break;
										case 'Wed':
											$hari = "Rabu";
										break;
										case 'Thu':
											$hari = "Kamis";
										break;
										case 'Fri':
											$hari = "Jumat";
										break;
										case 'Sat':
											$hari = "Sabtu";
										break;									
									}
								?>
							</font>							
					<td>
						Pada hari ini &nbsp;&nbsp; <strong><?= $hari ?></strong> &nbsp;&nbsp; Tanggal &nbsp;&nbsp; <strong><?= $tgl ?></strong> &nbsp;&nbsp; Bulan &nbsp;&nbsp; <strong><?= $bln ?></strong> &nbsp;&nbsp; Tahun &nbsp;&nbsp; <strong><?= $thn ?> .</strong> &nbsp;&nbsp;
					</td>
						
				</tr>
				
			</table>
		
			
			<br>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
                    <td colspan="4"><strong>Telah dilakukan penelitian dan analisis terhadap : </strong> 
					<br><?= nl2br(htmlspecialchars($data_ba[0]->analisis)) ?>
					<br><br>
				</tr>	
				<tr>
                    <td colspan="4"><strong>Hasil Sementara : </strong>
					<br><?= nl2br(htmlspecialchars($data_ba[0]->hasil)) ?>
					<br><br>
				</tr>
				<tr>
                    <td colspan="4"><strong>Saran, masukan, arahan dan penanganan : </strong>
					<br><?= nl2br(htmlspecialchars($data_ba[0]->penanganan)) ?>
					<br><br>
				</tr>	
				<tr>
                    <td colspan="4"><br>Demikian berita acara ini dibuat, agar dapat digunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya diucapkan terimakasih. <br><br><br><br></td>
				</tr>

				
			</table>


      <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>

				<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_ba[0]->idPengaju.".png";

							// Default tanda tangan
							$ttd1 = $img_path."ttd_notyet2.png";
							$ttd2 = $img_path."ttd_notyet2.png";
							$ttd3 = $img_path."ttd_notyet2.png";
							$ttd4 = $img_path."ttd_notyet2.png";
							$ttd5 = $img_path."ttd_notyet2.png";

							// TTD 1
							if ($data_ba[0]->ttd_1 == '1') {
								$ttd1 = $img_path."ttd_".$data_ba[0]->id_ttd1.".png";
							} else if ($data_ba[0]->ttd_1 == '2') {
								$ttd1 = $img_path."ttd_not.png";
							}

							// TTD 2
							if ($data_ba[0]->ttd_2 == '1') {
								$ttd2 = $img_path."ttd_".$data_ba[0]->id_ttd2.".png";
							} else if ($data_ba[0]->ttd_2 == '2') {
								$ttd2 = $img_path."ttd_not.png";
							}

							// TTD 3
							if ($data_ba[0]->ttd_3 == '1') {
								$ttd3 = $img_path."ttd_".$data_ba[0]->id_ttd3.".png";
							} else if ($data_ba[0]->ttd_3 == '2') {
								$ttd3 = $img_path."ttd_not.png";
							}

							// TTD 4
							if ($data_ba[0]->ttd_4 == '1') {
								$ttd4 = $img_path."ttd_".$data_ba[0]->id_ttd4.".png";
							} else if ($data_ba[0]->ttd_4 == '2') {
								$ttd4 = $img_path."ttd_not.png";
							}

							// TTD 5
							if ($data_ba[0]->ttd_5 == '1') {
								$ttd5 = $img_path."ttd_".$data_ba[0]->id_ttd5.".png";
							} else if ($data_ba[0]->ttd_5 == '2') {
								$ttd5 = $img_path."ttd_not.png";
							}


							//Jabatan 
							if($data_ba[0]->jabatan_visilab != ''){
									$jabatanaju = $data_ba[0]->jabatan_visilab;
							}else{
									$jabatanaju = $data_ba[0]->jabatan;
							}

							if($data_ba[0]->jabatan_visilab1 != ''){
								// 	$jabatan1 = $data_ba[0]->jabatan_visilab1;
								$jabatan1 = $data_ba[0]->jabatan1;
							}else{
								// 	$jabatan1 = $data_ba[0]->jabatan1;
								// 	$jabatan1 = $data_ba[0]->jabatan_visilab1;
							}

							if($data_ba[0]->jabatan_visilab2 != ''){
									$jabatan2 = $data_ba[0]->jabatan_visilab2;
							}else{
									$jabatan2 = $data_ba[0]->jabatan2;
							}
							if($data_ba[0]->jabatan_visilab3 != ''){
									$jabatan3 = $data_ba[0]->jabatan_visilab3;
							}else{
									$jabatan3 = $data_ba[0]->jabatan3;
							}

							if($data_ba[0]->jabatan_visilab4 != ''){
									$jabatan4 = $data_ba[0]->jabatan_visilab4;
							}else{
									$jabatan4 = $data_ba[0]->jabatan4;
							}

							if($data_ba[0]->jabatan_visilab5 != ''){
									$jabatan5 = $data_ba[0]->jabatan_visilab5;
							}else{
									$jabatan5 = $data_ba[0]->jabatan5;
							}

				if($data_ba[0]->jumlah_ttd ==1) {

						?>

					
					<tr style="height: 18px;">
						<td style="text-align:center; width:17%;" >Diajukan Oleh,</td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:20%;" >Disetujui Oleh,</td>
					</tr>
					

					<tr style="height:60px;">
						<td style="text-align:center; width:17%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:5%;"></td>
						<td style="text-align:center; width:5%;"></td>
						<td style="text-align:center; width:20%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_ba[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_ba[0]->nama1 ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatanaju ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatan1 ?></i></td>
					</tr>
				<?php } else if ($data_ba[0]->jumlah_ttd == 2) { ?>
					<tr style="height: 18px;">
							<td style="text-align:center; width:33%;">Diajukan Oleh,</td>
						<td style="text-align:center; width:5%;"></td>
							<td style="text-align:center; width:33%;">Diketahui Oleh,</td>
						<td style="text-align:center; width:5%;"></td>
							<td style="text-align:center; width:33%;">Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
							<td style="text-align:center;"><img src="<?= $ttdaju ?>" height="70"></td>
						<td style="text-align:center; width:5%;"></td>
							<td style="text-align:center;"><img src="<?= $ttd1 ?>" height="70"></td>
						<td style="text-align:center; width:5%;"></td>
							<td style="text-align:center;"><img src="<?= $ttd2 ?>" height="70"></td>
					</tr>
					<tr>
							<td style="text-align:center;"><?= $data_ba[0]->pengaju ?><hr></td>
						<td style="text-align:center; "></td>
							<td style="text-align:center;"><?= $data_ba[0]->nama1 ?><hr></td>
						<td style="text-align:center; "></td>
							<td style="text-align:center;"><?= $data_ba[0]->nama2 ?><hr></td>
					</tr>
					<tr>
							<td style="text-align:center;"><i><?= $jabatanaju ?></i></td>
						<td style="text-align:center; "></td>
							<td style="text-align:center;"><i><?= $jabatan1 ?></i></td>
						<td style="text-align:center; "></td>
							<td style="text-align:center;"><i><?= $jabatan2 ?></i></td>
					</tr>
				<?php }else if ($data_ba[0]->jumlah_ttd ==3){ ?>
					<tr style="height: 18px;">
						<td style="text-align:center; width:17%;" >Diajukan Oleh,</td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:15%;" >Diketahui Oleh,</td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:20%;" >Disetujui Oleh,</td>
					</tr>
					

					<tr style="height:60px;">
						<td style="text-align:center; width:17%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:5%;"></td>
						<td style="text-align:center; width:17%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:5%;"></td>
						<td style="text-align:center; width:17%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:5%;"></td>
						<td style="text-align:center; width:20%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_ba[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_ba[0]->nama1 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_ba[0]->nama2 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_ba[0]->nama3 ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatanaju ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatan1 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatan2 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatan3 ?></i></td>
					</tr>
				<?php }else if ($data_ba[0]->jumlah_ttd ==4){ ?>
					<tr style="height: 18px;">
						<td style="text-align:center; width:15%;" >Diajukan Oleh,</td>
						<td style="text-align:center; width:2%;" ></td>
						<td style="text-align:center; width:2%;" ></td>
						<td style="text-align:center; width:2%;" ></td>
						<td style="text-align:center; width:12%;" >Diketahui Oleh,</td>
						<td style="text-align:center; width:2%;" ></td>
						<td style="text-align:center; width:2%;" ></td>
						<td style="text-align:center; width:2%;" ></td>
						<td style="text-align:center; width:20%;" >Disetujui Oleh,</td>
					</tr>
					

					<tr style="height:60px;">
						<td style="text-align:center; width:17%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:17%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:17%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:20%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:20%;"><?php echo'<img src="'.$ttd4.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_ba[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_ba[0]->nama1 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_ba[0]->nama2 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_ba[0]->nama3 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_ba[0]->nama4 ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatanaju ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatan1 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatan2 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatan3 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatan4 ?></i></td>
					</tr>
				<?php }else if ($data_ba[0]->jumlah_ttd ==5){ ?>
					<tr style="height: 18px;">
						<td style="text-align:center; width:17%;" >Diajukan Oleh,</td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:7%;" >Diketahui Oleh,</td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:20%;" >Disetujui Oleh,</td>
					</tr>
					

					<tr style="height:60px;">
						<td style="text-align:center; width:15%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:15%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:15%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:15%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:15%;"><?php echo'<img src="'.$ttd4.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:15%;"><?php echo'<img src="'.$ttd5.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_ba[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_ba[0]->nama1 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_ba[0]->nama2 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_ba[0]->nama3 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_ba[0]->nama4 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_ba[0]->nama5 ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatanaju ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatan1 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatan2 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatan3 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatan4 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $jabatan5 ?></i></td>
					</tr>
				<?php } ?>

				</tbody>
				
			</table>
			

			</div>
			<?= form_close(); ?>
			<br><br>
		<div width="100%">
			<!--
      <?php if (sessPenggunaId() == $data_ba[0]->id_ttd1 || sessPenggunaId() == $data_ba[0]->id_ttd2 || sessPenggunaId() == $data_ba[0]->id_ttd3 || sessPenggunaId() == $data_ba[0]->id_ttd4 || sessPenggunaId() == $data_ba[0]->id_ttd5) { ?>
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-ba="<?=encrypt($data_ba[0]->id_ba)?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-ba="<?=encrypt($data_ba[0]->id_ba)?>"> <i class="fas fa-times"></i> Tolak </button>				
      <?php } ?> -->

			<?php
				$sessID = sessPenggunaId();
				$beritaAcara = $data_ba[0];

				$bolehTampil = false;

				if ($sessID == $beritaAcara->id_ttd1 && $beritaAcara->ttd_1 == NULL) {
						$bolehTampil = true;
				} elseif ($sessID == $beritaAcara->id_ttd2 && $beritaAcara->ttd_2 == NULL) {
						$bolehTampil = true;
				} elseif ($sessID == $beritaAcara->id_ttd3 && $beritaAcara->ttd_3 == NULL) {
						$bolehTampil = true;
				} elseif ($sessID == $beritaAcara->id_ttd4 && $beritaAcara->ttd_4 == NULL) {
						$bolehTampil = true;
				} elseif ($sessID == $beritaAcara->id_ttd5 && $beritaAcara->ttd_5 == NULL) {
						$bolehTampil = true;
				}

				if ($bolehTampil) {
				?>
						<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-ba="<?=encrypt($beritaAcara->id_ba)?>">
								<i class="fas fa-check"></i> Setujui
						</button>
						<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-ba="<?=encrypt($beritaAcara->id_ba)?>">
								<i class="fas fa-times"></i> Tolak
						</button>
				<?php
				}
				?>

					<a href="visilab/print_page/ba/<?=$data_ba[0]->id_ba?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
      <?php if($data_ba[0]->lampiran != "") { ?>
			    <a href="<?=$data_ba[0]->lampiran?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran </a>
			<?php } ?>
			<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
      <br>
    </div>
		<br><br>
		</div>
		
    </div>	
</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        
		var level_ttd = $('#level_ttd').val();		
        
		$(document).on('click', '.btn-approval', function() {
        	var id = $('#id').val();
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Setujui Berita Acara?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/ttd_setujui/ba/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id : id,
														csrf_token: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })
					
                }
				
            })
        })
        
		
		$(document).on('click', '.btn-denial', function() {
			var id = $('#id').val();
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Tolak Berita Acara?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/ttd_tolak/ba/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id : id,
							csrf_token: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })
					
                }
				
            })
        })


    })

	

    function goBack() {
        window.history.back();
    }
</script>