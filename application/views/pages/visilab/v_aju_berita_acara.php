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


    .form-group {
        margin-bottom: 10px;
    }
    .form-control-sm {
        font-size: 14px;
        padding: 4px 8px;
        height: auto;
    }
    label.col-form-label {
        font-weight: bold;
    }

	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
	    <div class="table-responsive">
        <div class="text-center mt-0">
            <h2><font color='#000000' face='Times New Roman'><u>Form Berita Acara <br><br></u></font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>								
						<td>Tanggal </td>
						<td>
							<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}'>
								<span> :&nbsp;&nbsp; </span>
								<input type="text" id="tanggal" Style="width:10%" placeholder='Input Tanggal' required>
							</div>
						</td>
				</tr>
				
				<tr>
					<td rowspan="9" width="7%"></td>
					
				</tr>
				
			</table>
		
			
			<br>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
                    <td colspan="4"><strong>Telah dilakukan penelitian dan analisis terhadap : </strong>
						<textarea id='analisis' type="text" style="width: 100%; box-sizing: border-box;" placeholder='Klik Untuk Memasukkan Penelitian dan Analisis' required></textarea></td>
				</tr>	
				<tr>
                    <td colspan="4"><strong>Hasil Sementara : </strong>
						<textarea id='hasil' type="text" style="width: 100%; box-sizing: border-box;" placeholder='Klik Untuk Memasukkan Hasil Sementara' required></textarea></td>
				</tr>
				<tr>
                    <td colspan="4"><strong>Saran, masukan, arahan dan penanganan : </strong>
						<textarea id='penanganan' type="text" style="width: 100%; box-sizing: border-box;" placeholder='Klik Untuk Memasukkan Saran, masukan, arahan dan penanganan' required></textarea></td>
				</tr>	
				<tr>
					<td colspan="4">Lampiran : &nbsp;
					<input id='lampiran' type="text" Style="width:60%" placeholder='Klik Untuk Memasukkan Lampiran' required></td>
				</tr>
				<tr>
                    <td colspan="4"><br>Demikian berita acara ini dibuat, agar dapat digunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya diucapkan terimakasih. <br><br><br><br></td>
				</tr>

				
			</table>

			<table id="tbl_3" class="table table-borderless" style="width:100%; font-family:Times New Roman; font-size:15px;">
					<tbody>

							<!-- Pilih Jumlah TTD -->
							<tr>
									<td colspan="5">
											<div class="form-group row mb-1">
													<label for="jumlah_ttd" class="col-auto col-form-label pr-2 font-weight-bold" style="font-size:14px;">Jumlah TTD :</label>
													<div class="col-auto pl-0">
															<select id="jumlah_ttd" name="jumlah_ttd" class="form-control form-control-sm" style="width: 80px;">
																	<?php for ($i = 1; $i <= 5; $i++): ?>
																			<option value="<?= $i ?>"><?= $i ?></option>
																	<?php endfor; ?>
															</select>
													</div>
											</div>
									</td>
							</tr>

							<!-- Pilihan TTD Dinamis -->
							<?php for ($i = 1; $i <= 7; $i++): ?>
							<tr class="row-ttd" id="ttd-row-<?= $i ?>" style="<?= $i > 1 ? 'display:none;' : '' ?>">
									<td colspan="5">
											<div class="form-group row mb-1">
													<label for="id_ttd<?= $i ?>" class="col-auto col-form-label pr-2" style="font-size:14px;">Nama TTD <?= $i ?> :</label>
													<div class="col-sm-5 pl-0">
															<select name="id_ttd<?= $i ?>" id="id_ttd<?= $i ?>" class="form-control form-control-sm" style="width: 250px;">
																	<option value="">Pilih Nama</option>
																	<?php foreach ($list_nama as $row): ?>
																			<option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
																	<?php endforeach; ?>
															</select>
													</div>
											</div>
									</td>
							</tr>
							<?php endfor; ?>

					</tbody>
			</table>




             <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>

					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" ></td>
						<td style="text-align:center; width:44.5%;" ></td>
						<td style="text-align:center; width:25.5%;" >Diajukan Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$pengguna[0]->pengguna_id.".png";
							$ttd1 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center;"> <?php echo'<img src="" height="70">';?> </td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"><?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $pengguna[0]->short_name ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $pengguna[0]->jabatan_visilab ?></i></td>
					</tr>
				</tbody>
				
			</table>
			
			</div>
			<?= form_close(); ?>
			<br><br>
			
			<br><br>
		<div role="document">
			<button type="button" class="btn btn-success btn-save float-right btn-ajukan" style="margin-left:12px;"> <i class="fas fa-check"></i> Ajukan </button>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
        </div>
		<br><br>
		</div>
		
    </div>	
</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function () {
				var tanggal     = $('#tanggal').val();
				var analisis    = $('#analisis').val();
				var hasil       = $('#hasil').val();
				var penanganan  = $('#penanganan').val();
				var lampiran    = $('#lampiran').val();
				var jumlah_ttd  = $('#jumlah_ttd').val();

				var id_ttd1 = $('#id_ttd1').val();
				var id_ttd2 = $('#id_ttd2').val();
				var id_ttd3 = $('#id_ttd3').val();
				var id_ttd4 = $('#id_ttd4').val();
				var id_ttd5 = $('#id_ttd5').val();

				Swal.fire({
						title: 'Ajukan Berita Acara?',
						icon: 'question',
						showCancelButton: true,
						confirmButtonText: 'Ya',
						cancelButtonText: 'Tidak'
				}).then(function (result) {
						if (result.value) {
								$.ajax({
										method: 'POST',
										url: 'visilab/add/berita_acara',
										dataType: 'JSON',
										data: {
												tanggal: tanggal,
												analisis: analisis,
												hasil: hasil,
												penanganan: penanganan,
												lampiran: lampiran,
												jumlah_ttd: jumlah_ttd,
												id_ttd1: id_ttd1,
												id_ttd2: id_ttd2,
												id_ttd3: id_ttd3,
												id_ttd4: id_ttd4,
												id_ttd5: id_ttd5,
												csrf_token: token
										},
										success: function (resp) {
												handleResponse(resp);
										}
								});
						}
				});
		});



				$('#jumlah_ttd').on('change', function () {
        var jumlah = parseInt($(this).val());
        $('.row-ttd').hide();
        for (var i = 1; i <= jumlah; i++) {
            $('#ttd-row-' + i).show();
        }
    });



    })

	

    function goBack() {
        window.history.back();
    }
</script>