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
		
		.mydiv {
			display:inline-block;
		}
		
		.mylabel {
			border:0px solid blue;
			display: table-cell;
			width: 100%;
		}
	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFFFFF; padding:5%;">
        <div class="text-center">
            <h2><font color='#000000' face='Times New Roman'>SURAT REKOMENDASI</font></h2>
        </div>
		<?php
		    $ttd = "ttd_1";
				if((sessPenggunaId() == '33')){
					$ttd = 'ttd_1';
				}else if((sessPenggunaId() == '54')){
					$ttd = 'ttd_2';
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<div class="table-responsive">
		<font color='#000000'>
<!-- TABEL SURAT REKOM-->
		<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <tbody>
                        	<tr>
                        		<td colspan="4"> No : <?= $data_rekom[0]->kode ?> </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"> Perihal &nbsp;&nbsp;: <?= $data_rekom[0]->perihal ?>  </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			Kepada Yth.
                        		</td>
                        	</tr>
                        	<!-- <tr>
                        		<td colspan="4"> <input type="text" id="perihal" style="width:75%; border:0px; margin:0px;" required placeholder="Input yang bersangkutan"> </td>
                        	</tr> -->
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			Di -
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Tempat</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			Dengan Ini &nbsp;<?= $data_rekom[0]->keterangan_1 ?>
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			bahwasanya karyawan atas nama sebagai berikut	:
                        		</td>
                        	</tr>
													
													<tr>
															<td colspan="4"><font color="white">i </font></td>
													</tr>
									
									
									<?php
														$kar1 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%">Nama</td>
															<td > :&nbsp; ' .$data_rekom[0]->nama1. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>NPP</td>
															<td>:&nbsp; ' .$data_rekom[0]->npp1. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>Jabatan</td>
															<td>:&nbsp; ' .$data_rekom[0]->jabatan11. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>'; 

														$kar2 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%">Nama</td>
															<td > :&nbsp; ' .$data_rekom[0]->nama2. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>NPP</td>
															<td>:&nbsp; ' .$data_rekom[0]->npp2. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>Jabatan</td>
															<td>:&nbsp; ' .$data_rekom[0]->jabatan2. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>'; 

														$kar3 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%">Nama</td>
															<td > :&nbsp; ' .$data_rekom[0]->nama3. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>NPP</td>
															<td>:&nbsp; ' .$data_rekom[0]->npp3. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>Jabatan</td>
															<td>:&nbsp; ' .$data_rekom[0]->jabatan3. '</td>
															<td></td>
														</tr>							
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>'; 

														$kar4 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%">Nama</td>
															<td > :&nbsp; ' .$data_rekom[0]->nama4. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>NPP</td>
															<td>:&nbsp; ' .$data_rekom[0]->npp4. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>Jabatan</td>
															<td>:&nbsp; ' .$data_rekom[0]->jabatan4. '</td>
															<td></td>
														</tr>										
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>'; 

														$kar5 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%">Nama</td>
															<td > :&nbsp; ' .$data_rekom[0]->nama5. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>NPP</td>
															<td>:&nbsp; ' .$data_rekom[0]->npp5. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>Jabatan</td>
															<td>:&nbsp; ' .$data_rekom[0]->jabatan5. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>';

														$kar6 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%">Nama</td>
															<td > :&nbsp; ' .$data_rekom[0]->nama6. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>NPP</td>
															<td>:&nbsp; ' .$data_rekom[0]->npp6. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>Jabatan</td>
															<td>:&nbsp; ' .$data_rekom[0]->jabatan6. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>';

														$kar7 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%">Nama</td>
															<td > :&nbsp; ' .$data_rekom[0]->nama7. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>NPP</td>
															<td>:&nbsp; ' .$data_rekom[0]->npp7. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>Jabatan</td>
															<td>:&nbsp; ' .$data_rekom[0]->jabatan7. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>';

														$kar8 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%">Nama</td>
															<td > :&nbsp; ' .$data_rekom[0]->nama8. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>NPP</td>
															<td>:&nbsp; ' .$data_rekom[0]->npp8. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>Jabatan</td>
															<td>:&nbsp; ' .$data_rekom[0]->jabatan8. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>';

														$kar9 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%">Nama</td>
															<td > :&nbsp; ' .$data_rekom[0]->nama9. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>NPP</td>
															<td>:&nbsp; ' .$data_rekom[0]->npp9. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>Jabatan</td>
															<td>:&nbsp; ' .$data_rekom[0]->jabatan9. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>';

														$kar10 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%">Nama</td>
															<td > :&nbsp; ' .$data_rekom[0]->nama10. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>NPP</td>
															<td>:&nbsp; ' .$data_rekom[0]->npp10. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td>Jabatan</td>
															<td>:&nbsp; ' .$data_rekom[0]->jabatan10. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>';

												if($data_rekom[0]->jumlahkar == '1'){
													echo $kar1;
												}
												if($data_rekom[0]->jumlahkar == '2'){
													echo $kar1;
													echo $kar2;
												}
												if($data_rekom[0]->jumlahkar == '3'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
												}
												if($data_rekom[0]->jumlahkar == '4'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
												}
												if($data_rekom[0]->jumlahkar == '5'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
													echo $kar5;
												}
												if($data_rekom[0]->jumlahkar == '6'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
													echo $kar5;
													echo $kar6;
												}
												if($data_rekom[0]->jumlahkar == '7'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
													echo $kar5;
													echo $kar6;
													echo $kar7;
												}
												if($data_rekom[0]->jumlahkar == '8'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
													echo $kar5;
													echo $kar6;
													echo $kar7;
													echo $kar8;
												}
												if($data_rekom[0]->jumlahkar == '9'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
													echo $kar5;
													echo $kar6;
													echo $kar7;
													echo $kar8;
													echo $kar9;
												}
												if($data_rekom[0]->jumlahkar == '10'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
													echo $kar5;
													echo $kar6;
													echo $kar7;
													echo $kar8;
													echo $kar9;
													echo $kar10;
												}
										
										?>

                        	<tr>
                        		<td colspan="4">Diberikan rekomendasi <?= $data_rekom[0]->keterangan_2 ?> sebagai berikut :</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="10%" style="text-align:right;"> &nbsp;</td>
                        		<td width="25%">Atas Dasar</td>
                        		<td width="60%"> : &nbsp;<?= $data_rekom[0]->dasar ?></td>
                        	</tr>
                        	<tr>
                        		<td width="10%" style="text-align:right;"> &nbsp;</td>
                        		<td width="25%">Terhitung Mulai Bulan</td>
                        		<td width="60%"> : &nbsp;<?= date_mont('M Y',strtotime($data_rekom[0]->tgl)) ?></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td>Perubahan</td>
                        		<td>:&nbsp;<?= $data_rekom[0]->perubahan ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Demikian surat pemberitahuan ini dibuat, atas perhatian diucapkan terima kasih.
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	
                        </tbody>
					</table>
				<table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr>
						<td height="20px"></td>
					</tr>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="6"><br><br>
						   Pekanbaru
							<span> ,&nbsp; </span>
							<?= $data_rekom[0]->tgl_pengajuan ?>
						</td>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:20%;" >Diajukan Oleh,</td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:25%;" >Diverifikasi Oleh,</td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:20%;" >Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
														$img_path 	= "uploads/file_karyawan/ttd/";
														$ttdaju		= $img_path."ttd_".$data_rekom[0]->idPengaju.".png";
														$ttd1 		= $img_path."ttd_notyet2.png";
														$ttd2 		= $img_path."ttd_notyet2.png";
                        		// $ttd3 		= $img_path."ttd_notyet2.png";

                        		if($data_rekom[0]->aju_ttd1 == '1'){
                        			$ttd1 = $img_path."ttd_33.png";
                        		}else if($data_rekom[0]->aju_ttd1 == '2'){
                        			$ttd1 = $img_path."ttd_not.png";
                        		}
                        		if($data_rekom[0]->aju_ttd2 == '1'){
                        			$ttd2 = $img_path."ttd_54.png";
                        		}else if($data_rekom[0]->aju_ttd2 == '2'){
                        			$ttd2 = $img_path."ttd_not.png";
                        		}
                        		?>
						<td style="text-align:center; width:25%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; ">Amtisari Destiani Eka Putri<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Yolanda Pratiwi<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Bob Ariyos<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i>General Affair</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>General Manager</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director</i></td>
					</tr>
				</tbody>
			</table>
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
						<tr>
							<td colspan="4"><i>Tembusan :</i></td>
						</tr>
						<tr>
							<td style="text-align:right; " width="5%"><i>1. </i></td>
							<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Direksi</i></td>
						</tr>
						<tr>
							<td style="text-align:right; "><i>2. </i></td>
							<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;General Affair</i></td>
						</tr>
						<tr>
							<td style="text-align:right; "><i>3. </i></td>
							<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Finance</i></td>
						</tr>
						<tr>
							<td style="text-align:right; "><i>4. </i></td>
							<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Arsip</i></td>
						</tr>
						<tr>
							<td colspan="4"><font color="white">i </font></td>
						</tr>
						<!-- <tr>
							<td >Lampiran</td>
							<td colspan="3"> :<input type="text" name="lampiran" id="lampiran" placeholder=" &nbsp;Pastekan link lampiran anda" required></td>
						</tr> -->
					</table>
					<br>
           
<!-- PENUTUP SURAT REKOM -->
			
<br><br>
<div width="100%">
	<?php if (sessPenggunaId() == '1' || sessPenggunaId() != $data_rekom[0]->idPengaju) { ?>
		<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-st="<?=encrypt($data_rekom[0]->id)?>"> <i class="fas fa-check"></i> Setujui </button>
		<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-st="<?=encrypt($data_rekom[0]->id)?>"> <i class="fas fa-times"></i> Tolak </button>				
	<?php } ?>
	<a href="surat/print_page/rekom/<?=$data_rekom[0]->id?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
	<?php if($data_rekom[0]->lampiran != "") { ?>
		 <a href="<?=$data_rekom[0]->lampiran?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran </a>
	<?php } ?>
	
	<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" style="margin-top:12px;" data-dismiss="modal">Kembali</button>
	
	
	<br>
</div>
        </div>
		<br>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
		var level_ttd = $('#level_ttd').val();		
        
		$(document).on('click', '.btn-approval', function() {
            const id_rekom = $(this).attr("id-st");
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Setujui Pengajuan Surat Rekomendasi?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/rekom/1/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id_rekom : id_rekom,
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
            const id_rekom = $(this).attr("id-st");
            Swal.fire({
				title: 'Tolak Pengajuan Surat Rekomendasi?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/rekom/2/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id_rekom: id_rekom,
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