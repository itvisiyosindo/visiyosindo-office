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
	    <div class="table-responsive">
				<div class="text-right">
            <?php
                if($data_presentase[0]->status == 0){
                    echo '<span class="btn btn-warning">Baru Diajukan</span>';
                }else if($data_presentase[0]->status == 1){
                    echo '<span class="btn btn-success">Disetujui Customer Relation Officer</span>';
                }else{
                    echo '<span class="btn btn-danger">Ditolak </span>';
                }
            ?>
        </div>
        <div class="text-center mt-0">
			<h2><font color='#000000' face='Times New Roman'>No : <?= $data_presentase[0]->kode ?></font></h2>
        </div>
		<font color='#000000'>
			<?php
		    $ttd = "ttd_1";
				if((sessPenggunaId() == '1' || sessPenggunaId() == '72')){
					$ttd = 'ttd_1';
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 

			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan permintaan Presentation / Training User / Demo Request :</font>
					</td>
				</tr>
				<tr>	
														
		</font>
		
					<font color='#ffffff'>
							<?= 
							
								$dt_pengajuan	= strtotime($data_presentase[0]->tgl_Pengajuan);
								$tgl_pengajuan 	= date("d", $dt_pengajuan)." - ".date("m", $dt_pengajuan)." - ".date("Y", $dt_pengajuan);
							
							?>
					
					</font>
					<td rowspan="12" width="7%"></td>
					
            <input type="hidden" id="idpengaju" value="<?= $data_presentase[0]->idPengaju ?>"> 
					<tr>
						<td width="18%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_presentase[0]->pengaju ?>  </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?=  $data_presentase[0]->jabatan ?></td>
					</tr>
					
					<tr>
						<td>Customer Name </td>
						<td>:&nbsp;&nbsp; <?=  $data_presentase[0]->csName ?></td>
					</tr>
					<tr>
						<td>Alamat </td>
						<td>:&nbsp;&nbsp; <?=  $data_presentase[0]->alamat ?></td>
					</tr>
					<tr>
						<td>Contact Person Name</td>
						<td>:&nbsp;&nbsp; <?=  $data_presentase[0]->cpName ?></td>
					</tr>
					<tr>
						<td>No CP </td>
						<td>:&nbsp;&nbsp; <?=  $data_presentase[0]->noCp ?></td>
					</tr>

					<tr>
						<td>Invoice / Quotation </td>
						<td>:&nbsp;&nbsp; <?=  $data_presentase[0]->invoice ?></td>
					</tr>		
					
					<tr>
						<td>Technician / Aplicant Name </td>
						<td>:&nbsp;&nbsp; <?=  $data_presentase[0]->teknisi ?></td>
					</tr>	

				

					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="5%"> No </th>
                                <th style="text-align:center" bgcolor="#b7d5ac"> Description Equipment</th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="40%"> Request Detail </th>
                            </tr>
                        </thead>
                        <tbody>
										<?php
													$x=1;
													$xy=0;
													foreach ($detail_presentase as $row) {
													$x = $x+1;
													$xy = $xy+1;
										?>
																<tr>
                                    <td style="text-align:center"><?= $xy ?></td>
																		<td class="des" >&nbsp;<?= $row->des ?>&nbsp;</td>
																		<td style="text-align:center" class="kom" >&nbsp;<?= $row->req ?></td>
                                </tr>
										<?php } ?>
										<?php for($kosong=$x;$kosong<=20;$kosong++){ ?>
																<tr>
                                    <td> <font color="white">i </font> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
										<?php } ?>

                        </tbody>
                        
			</table>
			<br>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
			
			<input class="no-outline" type="hidden" id="id_fpp" name="id_fpp" value="<?= $data_presentase[0]->idFpp ?>">
			<input class="no-outline" type="hidden" id="status" name="status" value="2">

				<tr>
					<tr>
						<td width="10%">Notes </td>
						<td>:&nbsp;&nbsp;<?= $data_presentase[0]->noTes ?></td>
					</tr>
					<tr>
						<td width="10%" height="35px"> </td>
					</tr>

						<?php if(sessPenggunaId() == '1' || sessPenggunaId() == '72'){   ?>

								<tr>
									<td width="10%">Link </td>
									<td>:&nbsp;&nbsp;<input id='link_sph' type="text" placeholder='Klik Untuk Memasukkan Link' required></td>
								</tr>
								
					<?php	} ?>
					
												
				</tr>
			</table>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
						


				<tr>
					
				</tr>
			</table>


            <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>

					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="3">
								
								<?= $data_presentase[0]->kota_pengajuan ?>, <?= $tgl_pengajuan ?>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" ></td>
						<td style="text-align:center; width:44.5%;" ></td>
						<td style="text-align:center; width:25.5%;" >Diajukan Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_presentase[0]->idPengaju.".png";
							$ttd1 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center;"> <?php echo'<img src="" height="70">';?> </td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"><?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_presentase[0]->nama_ttd ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_presentase[0]->jabatan ?></i></td>
					</tr>
				</tbody>
				
			</table>
			</div>
		
			<br><br>
		<div width="100%">
				<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '72' ) { ?>
					<!-- <button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-Fpp="<?=encrypt($data_presentase[0]->idFpp)?>"> <i class="fas fa-check"></i> Setujui </button> -->
				
          <button type="button" class="btn btn-primary float-right btn-submit1" style="margin-left:12px; margin-top:12px;" id-fpp="<?=encrypt($data_presentase[0]->idFpp)?>"> <i class="fas fa-check"></i> Setujui </button>
			
					<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-fpp="<?=encrypt($data_presentase[0]->idFpp)?>"> <i class="fas fa-times"></i> Tolak </button>
				<?php } ?>
				
			  <a href="fpp/print_page/presentase/<?=$data_presentase[0]->idFpp?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
        <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" style="margin-top:12px;" data-dismiss="modal">Kembali</button>
			<?php if($data_presentase[0]->link != ""){ ?>
				<a href="<?=$data_presentase[0]->link?>" target="blank" class="btn btn-primary float-center" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Link Surat Presentase</a>
			<?php } ?>
			
			<br>
        </div>
		<br><br>
		</div>
		
    </div>	
</div>


<script>

	


    document.addEventListener('DOMContentLoaded', function() {

			$(document).on('click', '.btn-submit1', function() {
        	var link_sph = $('#link_sph').val();
        	var id_fpp = $('#id_fpp').val();

        	// var level_ttd = $('#level_ttd').val();
        	// console.log(approvall);
        	Swal.fire({
        		title: 'Setujui Permintaan Presentase?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
        	}).then(function(result) {
        		if (result.value) {	
                    $.ajax({
                        method: 'POST',
                        url: 'fpp/updatePresentaseCRO/'+id_fpp,
                        dataType: 'JSON',
                        data: {
							link_sph: link_sph,
							csrf_token	: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }
        	})
        })

			var level_ttd = $('#level_ttd').val();	

		$(document).on('click', '.btn-approval', function() {
        	var id_fpp = $('#id_fpp').val();
			
            Swal.fire({
				title: 'Setujui Permintaan Presentase?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'fpp/update_setujui/presentase/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                          id_fpp : id_fpp,
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
			var id_fpp = $('#id_fpp').val();
			
            Swal.fire({
				title: 'Tolak Permintaan Presentase?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'fpp/update_tolak/presentase/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                         id_fpp : id_fpp,
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
