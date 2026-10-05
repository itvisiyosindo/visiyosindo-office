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

		<div class="text-right">
            <?php
                if($data_po[0]->status == 0){
                    echo '<span class="btn btn-warning">Baru Diajukan</span>';
                }else if($data_po[0]->status == 1){
                    echo '<span class="btn btn-success">Disetujui Director of Corporate Planning and Business Management</span>';
                }else if($data_po[0]->status == 3){
                    echo '<span class="btn btn-success">Disetujui HR & Legal</span>';
                }else if($data_po[0]->status == 5){
                    echo '<span class="btn btn-success">Disetujui Head of Visilab</span>';
                }else{
                    echo '<span class="btn btn-danger">Ditolak </span>';
                }
            ?>
        </div>

		<div class="text-center">
            <h2><font color='#000000' face='Times New Roman'>No : <?= $data_po[0]->kode ?> </font></h2>
        </div>
		<?php
		    $ttd = "ttd_1";
				if((sessPenggunaId() == '23')){
					$ttd = 'ttd_1';
				}else if((sessPenggunaId() == '69' || sessPenggunaId() == '744')){
					$ttd = 'ttd_2';
				}else if((sessPenggunaId() == '75')){
					$ttd = 'ttd_3';
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<?php $id = $data_po[0]->idGc ?> 
		<input type="hidden" name="id" id="id" value="<?= $data_po[0]->idGc ?>">

	    <div class="table-responsive">
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left"><br><br>
						<font color='#000000'>Kepada Yth:<br>
						<b>Direktur PT. Visi Yosindo Medikal</b><br><br>
						
						Saya yang bertanda tangan di bawah ini : <br><br></font>
					</td>
				</tr>
				<tr>
					<tr>
						<td width="25%">Nama </td>
						<td>:</td>
						<td> Meilina Safitri, S.E   </td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td>:</td>
						<td>  Director of Corporate Planning & Business Management  </td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>			
					<tr>
						<td width="25%">Nama </td>
						<td>:</td>
						<td> Mega Ratu   </td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td>:</td>
						<td>Head of Visilab </td>
					</tr>

				<?php if($data_po[0]->jenis != 1){ ?>
					<tr>
						<td><font color="white">i </font></td>
					</tr>			
					<tr>
						<td width="25%">Nama </td>
						<td>:</td>
						<td> Dian Melati Amelia   </td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td>:</td>
						<td>  HR & Legal  </td>
					</tr>
				<?php } ?>

				</tr>
				
			</table>
		
			
			<br>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				
				<tr>
            <td colspan="4">Untuk Purchase Order (PO) berikut : </td>
				</tr>	

			</table>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				
				<tr>					
						
					<tr><br>
						<td>Nama Instansi/Perusahaan</td>
						<td>:</td>
						<td><?= $data_po[0]->identitas_pelanggan ?></td>
					</tr>
					<tr>
						<td width="25%">Nomor PO</td>
						<td>:</td>
						<td><?= $data_po[0]->no_po ?></td>
					</tr>
					<tr>
						<td width="25%">Item PO</td>
						<td>:</td>
						<td><?= $data_po[0]->item1 ?></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><?= $data_po[0]->item2 ?></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><?= $data_po[0]->item3 ?></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><?= $data_po[0]->item4 ?></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><?= $data_po[0]->item5 ?></td></td>
					</tr>


					<tr>
						<td width="25%">Tanggal PO</td>
						<td>:</td>
						<td><?= date('d/m/Y',strtotime($data_po[0]->tanggal)) ?></td>
					</tr>


					<tr>
						<td width="25%"><br><br>Alasan PO tidak diproses</td>
						<td><br><br>:</td>
						<td><br><br><?= $data_po[0]->alasan ?></td>
					</tr>

				</tr>

			</table>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
					
				<tr>
            <td colspan="4"><br><br><br>Dengan ini menyetujui Purchase Order (PO) berikut untuk dapat diproses dan dikirimkan barangnya ke customer.</td>
				</tr>

			</table>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
					
				<tr>

			<br><br>
					<tr>
						<td width="25%"><br><br>Catatan Director </td>
						<td><br><br>:</td>
						<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '23') { ?>
						<td><br><br><input id='catatan' type="text" placeholder='Klik Untuk Memasukkan catatan Director of Corporate Planning & Business Management' value="<?= $data_po[0]->catatan ?>" required></td>
						<?php }else{ ?>
						<td><br><br><?= $data_po[0]->catatan ?></td>
						<?php } ?>
					</tr>

					<tr>
						<td width="25%"><br>Catatan Head of Visilab </td>
						<td><br>:</td>
						<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '75') { ?>
						<td><br><input id='catatan_gm' type="text" placeholder='Klik Untuk Memasukkan catatan oleh General Manager' value="<?= $data_po[0]->catatan_gm ?>" required></td>
						<?php }else{ ?>
						<td><br><?= $data_po[0]->catatan_gm ?></td>
						<?php } ?>
					</tr>
					
				<?php if($data_po[0]->jenis != 1){ ?>
					<tr>
						<td width="25%"><br>Catatan HR </td>
						<td><br>:</td>
						<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '69' || sessPenggunaId() == '744') { ?>
						<td><br><input id='catatan_hr' type="text" placeholder='Klik Untuk Memasukkan catatan oleh HR & Legal' value="<?= $data_po[0]->catatan_hr ?>" required></td>
						<?php }else{ ?>
						<td><br><?= $data_po[0]->catatan_hr ?></td>
						<?php } ?>
					</tr>
				<?php } ?>



				</tr>

			</table>


            <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>

					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="6"><br><br>
							Pekanbaru
							<span> ,&nbsp; </span>
							<?= date('d-m-Y',strtotime($data_po[0]->created_at)) ?>
							
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:20%;" ></td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:25%;" ></td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:20%;" >Disetujui Oleh,</td>
					</tr>
					
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_po[0]->idPengaju.".png";
							$ttd1 		= $img_path."ttd_notyet2.png";
							$ttd2 		= $img_path."ttd_notyet2.png";
							$ttd3 		= $img_path."ttd_notyet2.png";
							
							if($data_po[0]->ttd_1 == '1'){
								$ttd1 = $img_path."ttd_23.png";
							}else if($data_po[0]->ttd_1 == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}

							if($data_po[0]->ttd_2 == '1'){
								$ttd2 = $img_path."ttd_69.png";
							}else if($data_po[0]->ttd_2 == '2'){
								$ttd2 = $img_path."ttd_not.png";
							}

							if($data_po[0]->ttd_3 == '1'){
								$ttd3 = $img_path."ttd_75.png";
							}else if($data_po[0]->ttd_3 == '2'){
								$ttd3 = $img_path."ttd_not.png";
							}
						?>
					
				<?php if($data_po[0]->jenis != 1){ ?>

					<tr style="height:60px;">
						<td style="text-align:center; width:25%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; ">Mega Ratu<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Dian Melati Amelia<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Meilina Safitri, S.E<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i>Head of Visilab</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>HR & Legal</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corporate Planning & Business Management</i></td>
					</tr>

				<?php }else{ ?>

					<tr style="height:60px;">
						<td style="text-align:center; width:25%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:23%;"></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; ">Mega Ratu<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Meilina Safitri, S.E<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i>Head of Visilab</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corporate Planning & Business Management</i></td>
					</tr>
					
				<?php } ?>

				</tbody>
				
			</table>
			</div>
			<?= form_close(); ?>
			<br><br>
		<div width="100%">
      <?php if (sessPenggunaId() == '23') { ?>
				    	
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_po[0]->idGc)?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_po[0]->idGc)?>"> <i class="fas fa-times"></i> Tolak </button>				
       	<button type="button" class="btn btn-primary float-right btn-submit" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_po[0]->idGc)?>"> <i class="fas fa-check"></i> Submit </button> 
        
			<?php } ?>

			<?php if (sessPenggunaId() == '75') { ?>
				    	
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_po[0]->idGc)?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_po[0]->idGc)?>"> <i class="fas fa-times"></i> Tolak </button>				
       	<button type="button" class="btn btn-primary float-right btn-submit-gm" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_po[0]->idGc)?>"> <i class="fas fa-check"></i> Submit </button> 
        
			<?php } ?>
		
		<?php if($data_po[0]->jenis != 1){ ?>
      <?php if (sessPenggunaId() == '69' || sessPenggunaId() == '744') { ?>
				    	
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_po[0]->idGc)?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_po[0]->idGc)?>"> <i class="fas fa-times"></i> Tolak </button>				
       	<button type="button" class="btn btn-primary float-right btn-submit-hr" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_po[0]->idGc)?>"> <i class="fas fa-check"></i> Submit </button> 
        
			<?php } ?>
		<?php } ?>

			<a href="visilab/print_page/approval_po/<?=$data_po[0]->idGc?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
     	<?php if($data_po[0]->lampiran != "") { ?>
			    <a href="<?=$data_po[0]->lampiran?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran </a>
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
				title: 'Setujui Permintaan Approval PO Visilab?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/ttd_setujui/po/'+level_ttd,
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
				title: 'Tolak Permintaan Approval PO Visilab?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/ttd_tolak/po/'+level_ttd,
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


				$(document).on('click', '.btn-submit', function() {
        	var catatan = $('#catatan').val();
        	var id 		 = $('#id').val();

        	        	Swal.fire({
        		title: 'Data telah tepat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
        	}).then(function(result) {
        		if (result.value) {	
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/submitCatatanPo/'+id,
                        dataType: 'JSON',
                        data: {
							catatan : catatan,
							csrf_token	: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }
        	})
        })

				$(document).on('click', '.btn-submit-hr', function() {
        	var catatan_hr = $('#catatan_hr').val();
        	var id 		 = $('#id').val();

        	        	Swal.fire({
        		title: 'Data telah tepat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
        	}).then(function(result) {
        		if (result.value) {	
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/submitCatatanPoHR/'+id,
                        dataType: 'JSON',
                        data: {
							catatan_hr : catatan_hr,
							csrf_token	: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }
        	})
        })


				$(document).on('click', '.btn-submit-gm', function() {
        	var catatan_gm = $('#catatan_gm').val();
        	var id 		 = $('#id').val();

        	        	Swal.fire({
        		title: 'Data telah tepat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
        	}).then(function(result) {
        		if (result.value) {	
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/submitCatatanPoGM/'+id,
                        dataType: 'JSON',
                        data: {
							catatan_gm : catatan_gm,
							csrf_token	: token
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