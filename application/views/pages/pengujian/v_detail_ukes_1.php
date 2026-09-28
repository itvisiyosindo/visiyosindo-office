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
            <h2><font color='#000000' face='Times New Roman'>No : <?= $data_ukes[0]->kode ?> </font></h2>
        </div>
		<?php
		    $ttd = "ttd_1";
				if((sessPenggunaId() == '15')){
					$ttd = 'ttd_1';
				}else if((sessPenggunaId() == '75')){
					$ttd = 'ttd_2';
				}else if((sessPenggunaId() == '107')){
					$ttd = 'ttd_3';
				}else if((sessPenggunaId() == '23')){
					$ttd = 'ttd_4';
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<?php $id = $data_ukes[0]->idGc ?>
		<input type="hidden" name="id" id="id" value="<?= $data_ukes[0]->idGc ?>">

	    <div class="table-responsive">
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <tbody>
                        	<tr>
								<td colspan="3" style="text-align:left">
									<font color='#000000'>Dengan ini saya mengajukan Uji Kesesuaian : </font>
								</td>
							</tr>
                        	<tr>
                        		<td width="2%" style="text-align:right;"></td>
                        		<td width="15%">Nama</td>
                        		<td >:&nbsp;<?= $data_ukes[0]->pengaju ?></td>
                        		<td width="30%"></td>
                        	</tr>
                        	<tr>
                        		<td width="2%"></td>
                        		<td>Jabatan</td>
                        		<td>:&nbsp;<?= $data_ukes[0]->jabatan_visilab ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Detail Data Pelanggan : </td>
                        	</tr>
                          <tr>
                        		<td width="2%" style="text-align:right;"></td>
                        		<td width="17%">Nama Pelanggan</td>
                        		<td >:&nbsp;<?= $data_ukes[0]->identitas_pelanggan ?></td>
                        		<td width="30%"></td>
                        	</tr>
													<tr>
															<td width="2%" style="text-align:right;"></td>
															<td width="17%">Nama Instansi</td>
															<td>:&nbsp;<?= !empty($data_ukes[0]->nama_instansi) ? $data_ukes[0]->nama_instansi : '-' ?></td>
															<td width="30%"></td>
													</tr>
                        	<tr>
                        		<td width="2%"></td>
                        		<td>Jenis Pengujian</td>
                        		<td>:&nbsp;<?= $data_ukes[0]->jenis_uji ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td width="2%"></td>
                        		<td>Jenis Alat</td>
                        		<td>:&nbsp;<?= $data_ukes[0]->jenis_alat ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td width="2%"></td>
                        		<td>Nama Alat</td>
                        		<td>:&nbsp;<?= $data_ukes[0]->nama_alat ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td width="2%"></td>
                        		<td>Serial Number</td>
                        		<td>:&nbsp;<?= $data_ukes[0]->serial_number ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td width="2%"></td>
                        		<td>Jadwal</td>
                        		<td>:&nbsp;<?= date('d-m-Y',strtotime($data_ukes[0]->jadwal)); ?> &nbsp; Sampai &nbsp; <?= date('d-m-Y',strtotime($data_ukes[0]->jadwal_end)); ?></td>
                        		<td></td>
                        	</tr>
                            
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                            <br><br>
                            <tr>
															<td></td>
															<td>File Surat Penawaran Harga</td>
															<td>:&nbsp;<a href="<?= $data_ukes[0]->link_sph ?>">Klik untuk cek</a></td>
															<td></td>
														</tr>
                            <tr>
															<td></td>
															<td>File Form Ceklis Pengujian</td>
															<td>:&nbsp;<a href="<?= $data_ukes[0]->form_ceklis ?>">Klik untuk cek</a></td>
															<td></td>
														</tr>
                            <tr>
																<td></td>
																<td>File Permintaan Instalasi</td>
																<td>:&nbsp;
																		<?= !empty($data_ukes[0]->link_instalasi) ? '<a href="' . $data_ukes[0]->link_instalasi . '">Klik untuk cek</a>' : '-' ?>
																</td>
																<td></td>
														</tr>


                            <tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                            <br><br>
                            <?php if($data_ukes[0]->link_lhu != ""){ ?>
                            <tr>
                        		<td></td>
                        		<td>File LHU</td>
                        		<td>:&nbsp;<a href="<?= $data_ukes[0]->link_lhu ?>">Klik untuk cek</a></td>
                        		<td></td>
                        	</tr>
                            <?php } ?>
                            <?php if($data_ukes[0]->link_sertifikat != ""){ ?>
                            <tr>
                        		<td></td>
                        		<td>File Sertifikat</td>
                        		<td>:&nbsp;<a href="<?= $data_ukes[0]->link_sertifikat ?>">Klik untuk cek</a></td>
                        		<td></td>
                        	</tr>
                            <?php } ?>
                            
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	
                        </tbody>
			</table>

            <table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="9">
							Pekanbaru, <?= date('d-m-Y',strtotime($data_ukes[0]->created_at)) ?>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" colspan="2">Diajukan Oleh,</td>
						<td style="text-align:right; width:25.5%;" colspan="4">Diverifikasi Oleh,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td style="text-align:right; width:30%;" colspan="3">Disetujui Oleh,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_ukes[0]->idPengaju.".png";
							$ttd1 		= $img_path."ttd_notyet2.png";
							$ttd2 		= $img_path."ttd_notyet2.png";
							$ttd3 		= $img_path."ttd_notyet2.png";
							$ttd4 		= $img_path."ttd_notyet2.png";
							
							if($data_ukes[0]->ttd_1 == '1'){
								$ttd1 = $img_path."ttd_15.png";
							}else if($data_ukes[0]->ttd_1 == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
							if($data_ukes[0]->ttd_2 == '1'){
								$ttd2 = $img_path."ttd_75.png";
							}else if($data_ukes[0]->ttd_2 == '2'){
								$ttd2 = $img_path."ttd_not.png";
							}
							if($data_ukes[0]->ttd_3 == '1'){
								$ttd3 = $img_path."ttd_107.png";
							}else if($data_ukes[0]->ttd_3 == '2'){
								$ttd3 = $img_path."ttd_not.png";
							}
							if($data_ukes[0]->ttd_4 == '1'){
								$ttd4 = $img_path."ttd_23.png";
							}else if($data_ukes[0]->ttd_4 == '2'){
								$ttd4 = $img_path."ttd_not.png";
							}
						?>
						<td style="text-align:center; width:17%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:4%;"></td>
						<td style="text-align:center; width:17%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:4%;"></td>
						<td style="text-align:center; width:17%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:4%;"></td>
						<td style="text-align:center; width:20%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
						<td style="text-align:center; width:4%;"></td>
						<td style="text-align:center; width:25%;"><?php echo'<img src="'.$ttd4.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_ukes[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Kardonal<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Mega Ratu<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Dirangga Madali<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Meilina Safitri<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_ukes[0]->jabatan_visilab ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Manager Teknis</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Manager Puncak</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Senior Accounting & Finance</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corporate Planning & Business Management</i></td>
					</tr>
				</tbody>
			</table>
			</div>
			<?= form_close(); ?>
			<br><br>
		<div width="100%">
            <?php if (sessPenggunaId() == '1' || sessPenggunaId() == '15' || sessPenggunaId() == '75' || sessPenggunaId() == '107' || sessPenggunaId() == '23') { ?>
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-Meet="<?=encrypt($data_ukes[0]->idGc)?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-Meet="<?=encrypt($data_ukes[0]->idGc)?>"> <i class="fas fa-times"></i> Tolak </button>				
            <?php } ?>
        <?php if (sessPenggunaId() == '1' || sessPenggunaId() == $data_ukes[0]->idPengaju) { ?>
           <?php if ($data_ukes[0]->ttd_4 != '') { ?>
				<button type="button" class="btn btn-primary float-right btn-editUji" style="margin-left:12px; margin-top:12px;" data-id="<?= encrypt($data_ukes[0]->idGc) ?>"><i class="bx bx-pencil"></i> Submit</button>	
			<?php } ?>	
        <?php } ?>	
            


			<a href="pengujian/print_page/ukes/<?=$data_ukes[0]->idGc?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
            
			<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
            <br>
        </div>
		<br><br>
		</div>
		
    </div>	
</div>





<div id="main-modalUji" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Submit Sertifikat </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-formUji', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					
					
					
					<div class="form-group">
						<label for="link_lhu" class="form-control-label">Link LHU <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="link_lhu" name="link_lhu" required>
					</div>

                    <div class="form-group">
						<label for="link_sertifikat" class="form-control-label">Link Sertifikat <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="link_sertifikat" name="link_sertifikat" required>
					</div>
					

					
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id" name="id">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save"><i class="fas fa-check"></i> Submit</a>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        
		var level_ttd = $('#level_ttd').val();		
        
		$(document).on('click', '.btn-approval', function() {
        	var id = $('#id').val();
			
            Swal.fire({
				title: 'Setujui Pengajuan Uji Kesesuaian?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'pengujian/ttd_setujui/uji/'+level_ttd,
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
				title: 'Tolak Pengajuan Uji Kesesuaian?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'pengujian/ttd_tolak/uji/'+level_ttd,
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


         $(document).on('click', '.btn-editUji', function() {
			$('.btn-isactive').remove()
			var object = 'pengujian'
			$('#main-modalUji #modal-formUji').attr('action', 'pengujian/updateSetujuUji')
			$('#main-modalUji').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
			.then(function(resp) {
				return resp.json()
					})
					.then(function(data) {
                        $('#main-modalUji #link_lhu').val(data[0].link_lhu)
                        $('#main-modalUji #link_sertifikat').val(data[0].link_sertifikat)
						$('#main-modalUji #id').val(id)
					})
		})


    })

	

    function goBack() {
        window.history.back();
    }
</script>