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
			//font-family:Garamond;
			//background:#363;
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
			//background:#b7d5ac;
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
            <h2><font color='#000000' face='Times New Roman'>No : <?= $data_ba[0]->kode_ba ?> <br></font></h2>
        </div>
		<?php
			$diketahui = $data_ba[0]->id_diketahui;
			$disetujui = $data_ba[0]->id_disetujui;

		    $ttd = "ttd_diketahui";
				if((sessPenggunaId() == $diketahui)){
					$ttd = 'ttd_diketahui';
				}else if((sessPenggunaId() == $disetujui)){
					$ttd = 'ttd_disetujui';
				}else if((sessPenggunaId() == '54')){
					$ttd = 'ttd_dir';
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
					<br><?= $data_ba[0]->analisis ?>
					<br><br>
				</tr>	
				<tr>
                    <td colspan="4"><strong>Hasil Sementara : </strong>
					<br><?= $data_ba[0]->hasil ?>
					<br><br>
				</tr>
				<tr>
                    <td colspan="4"><strong>Saran, masukan, arahan dan penanganan : </strong>
					<br><?= $data_ba[0]->penanganan ?>
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
							$ttd1 		= $img_path."ttd_notyet2.png";
							$ttd2 		= $img_path."ttd_notyet2.png";
							$ttd3 		= $img_path."ttd_notyet2.png";
							
							if($data_ba[0]->ttd_diketahui == '1'){
								$ttd1 = $img_path."ttd_".$data_ba[0]->id_diketahui.".png";
							}else if($data_ba[0]->ttd_diketahui == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
							if($data_ba[0]->ttd_disetujui == '1'){
								$ttd2 = $img_path."ttd_".$data_ba[0]->id_disetujui.".png";
							}else if($data_ba[0]->ttd_disetujui == '2'){
								$ttd2 = $img_path."ttd_not.png";
							}

							if($data_ba[0]->ttd_dir == '1'){
								$ttd3 = $img_path."ttd_54.png";
							}else if($data_ba[0]->ttd_dir == '2'){
								$ttd3 = $img_path."ttd_not.png";
							}

							if($data_ba[0]->idPengaju==72){
								$nmpengaju= "Syarifah Annisa Andira Alhabsyi";
							  }else{
								$nmpengaju= $data_ba[0]->pengaju;
							  }

							if($data_ba[0]->id_diketahui==58){
								$Namadiketahui= "Amtisari Destiani Eka Putri";
							  }else{
								$Namadiketahui= $data_ba[0]->nama_diketahui;
							  }
					
							  if($data_ba[0]->id_disetujui==58){
								$Namadisetujui= "Amtisari Destiani Eka Putri";
							  }else{
								$Namadisetujui= $data_ba[0]->nama_disetujui;
							  }

							  if($data_ba[0]->status_dir==1){

						?>

					
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
						<td style="text-align:center; "><?= $nmpengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $Namadiketahui ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $Namadisetujui ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Bob Ariyos<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_ba[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_ba[0]->jabatan_diketahui ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_ba[0]->jabatan_disetujui ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director</i></td>
					</tr>

				<?php }else{ ?>
					<tr style="height: 18px;">
						<td style="text-align:center; width:20%;" >Diajukan Oleh,</td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:25%;" >Diketahui Oleh,</td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:20%;" >Disetujui Oleh,</td>
					</tr>
					

					<tr style="height:60px;">
						<td style="text-align:center; width:25%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $nmpengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $Namadiketahui ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $Namadisetujui ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_ba[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_ba[0]->jabatan_diketahui ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_ba[0]->jabatan_disetujui ?></i></td>
					</tr>
				<?php } ?>

				</tbody>
				
			</table>
			</div>
			<?= form_close(); ?>
			<br><br>
		<div width="100%">
            <?php if (sessPenggunaId() == '1' || sessPenggunaId() == '54' || sessPenggunaId() == $diketahui || sessPenggunaId() == $disetujui) { ?>
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-ba="<?=encrypt($data_ba[0]->id_ba)?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-ba="<?=encrypt($data_ba[0]->id_ba)?>"> <i class="fas fa-times"></i> Tolak </button>				
            <?php } ?>
			<a href="surat_part_two/print_page/ba/<?=$data_ba[0]->id_ba?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
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
                        url: 'surat_part_two/ttd_setujui/ba/'+level_ttd,
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
                        url: 'surat_part_two/ttd_tolak/ba/'+level_ttd,
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