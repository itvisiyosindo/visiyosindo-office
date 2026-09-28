<header class="page-header">
	<h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>

	<style>
		hr {
			display: block;
			margin-top: 0em;
			margin-bottom: 0em;
			margin-left: auto;
			margin-right: auto;
			border-top: 1px solid black;
		}

		input {
			width: 97%;
			height: auto;
			border: 0px dotted #f30;
			border-radius: 4px;
			-moz-border-radius: 8px;
			margin-right: 0px;
		}

		.myinput {
			width: 97%;
			height: auto;
			border: 0px solid #000;
			border-radius: 0px;
			-moz-border-radius: 8px;
			margin-left: 0px;
			background: #b7d5ac;
		}

		.myselect {
			width: 97%;
			height: auto;
			border: 0px solid #000;
			border-radius: 4px;
			-moz-border-radius: 8px;
			margin: 0px;
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

		<div class="text-right">
			<?php
			if ($data_kend[0]->status == 0) {
				echo '<span class="btn btn-warning">Baru Diajukan</span>';
			} else if ($data_kend[0]->status == 1) {
				echo '<span class="btn btn-success">Disetujui Head of Technician, Media Technology, and Sec</span>';
			} else if ($data_kend[0]->status == 2) {
				echo '<span class="btn btn-success">Disetujui General Manager</span>';
			} else {
				echo '<span class="btn btn-danger">Ditolak </span>';
			}
			?>
		</div>

		<div class="text-center">
			<h2>
				<font color='#000000' face='Times New Roman'>No : <?= $data_kend[0]->kode ?> </font>
			</h2>
		</div>
		<?php
		$ttd = "ttd_1";
		if ((sessPenggunaId() == '15')) {
			$ttd = 'ttd_1';
		} else if ((sessPenggunaId() == '33')) {
			$ttd = 'ttd_2';
		}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>">
		<?php $id = $data_kend[0]->idGc ?>
		<input type="hidden" name="id" id="id" value="<?= $data_kend[0]->idGc ?>">

		<div class="table-responsive">
			<font color='#000000'>
				<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
				<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
					<tr>
						<td colspan="3" style="text-align:left"><br><br>
							<font color='#000000'>Kepada Yth.<br>
								<b>PIMPINAN PT. VISI YOSINDO MEDIKAL</b><br>
								Jl. Inpres No. 268 D – Pekanbaru<br><br>
								Dengan Hormat,<br>
								Saya yang bertanda tangan di bawah ini : <br><br>
							</font>
						</td>
					</tr>
					<tr>
						<td rowspan="9" width="7%"></td>
					<tr>
						<td width="18%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_kend[0]->pengaju ?> </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp; <?= $data_kend[0]->jabatan ?></td>
					</tr>
					<tr>
						<td>NPP </td>
						<td>:&nbsp;&nbsp; <?= $data_kend[0]->no_pegawai ?></td>
					</tr>
					</tr>

				</table>


				<br>

				<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">

					<tr>
						<td colspan="4">Mengajukan permohonan penggunaan kendaraan kantor : </td>
					</tr>

				</table>

				<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">

					<tr>
						<td rowspan="9" width="7%"></td>
					<tr>
						<td width="18%">Keperluan </td>
						<td>:&nbsp;&nbsp; <?= $data_kend[0]->keperluan ?> </td>
					</tr>
					<tr>
						<td>Jenis Mobil </td>
						<td>:&nbsp;&nbsp; <?= $data_kend[0]->mobil ?> </td>
					</tr>
					<tr>
						<td>No. Polisi </td>
						<td>:&nbsp;&nbsp; <?= $data_kend[0]->nopol ?> </td>
					</tr>
					<tr>
						<td>Lama Pemakaian </td>
						<td>:&nbsp;&nbsp; <?= $data_kend[0]->lama ?> Hari</td>
					</tr>
					<tr>
						<td>Tanggal </td>
						<td>:&nbsp;&nbsp; <?= date('d-m-Y', strtotime($data_kend[0]->tanggal)) ?> </td>
					</tr>
					</tr>

				</table>

				<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">

					<tr>
						<td colspan="4"><br>Demikian surat permohonan penggunaan kendaraan kantor ini saya ajukan, atas perhatiannya saya
							ucapkan terimakasih.</td>
					</tr>

				</table>


				<table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
					<tbody>

						<tr style="height: 35px;">
							<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="6"><br><br>
								Pekanbaru
								<span> ,&nbsp; </span>
								<?= date('d-m-Y', strtotime($data_kend[0]->created_at)) ?>

							</td>
						</tr>
						<tr style="height: 18px;">
							<td style="text-align:center; width:20%;">Diajukan Oleh,</td>
							<td style="text-align:center; width:17%;"></td>
							<td style="text-align:center; width:25%;">Diketahui Oleh,</td>
							<td style="text-align:center; width:17%;"></td>
							<td style="text-align:center; width:20%;">Disetujui Oleh,</td>
						</tr>


						<tr style="height:60px;">
							<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path . "ttd_" . $data_kend[0]->idPengaju . ".png";
							$ttd1 		= $img_path . "ttd_notyet2.png";
							$ttd2 		= $img_path . "ttd_notyet2.png";

							if ($data_kend[0]->ttd_1 == '1') {
								$ttd1 = $img_path . "ttd_15.png";
							} else if ($data_kend[0]->ttd_1 == '2') {
								$ttd1 = $img_path . "ttd_not.png";
							}
							if ($data_kend[0]->ttd_2 == '1') {
								$ttd2 = $img_path . "ttd_33.png";
							} else if ($data_kend[0]->ttd_2 == '2') {
								$ttd2 = $img_path . "ttd_not.png";
							}
							?>
							<td style="text-align:center; width:25%;"> <?php echo '<img src="' . $ttdaju . '" height="70">'; ?> </td>
							<td style="text-align:center; width:15%;"></td>
							<td style="text-align:center; width:23%;"><?php echo '<img src="' . $ttd1 . '" height="70">'; ?></td>
							<td style="text-align:center; width:15%;"></td>
							<td style="text-align:center; width:24%;"><?php echo '<img src="' . $ttd2 . '" height="70">'; ?></td>
						</tr>
						<tr>
							<td style="text-align:center; "><?= $data_kend[0]->pengaju ?>
								<hr>
								</hr>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; ">Kardonal
								<hr>
								</hr>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; ">Yolanda Pratiwi
								<hr>
								</hr>
							</td>
						</tr>
						<tr>
							<td style="text-align:center; vertical-align:top;"><i><?= $data_kend[0]->jabatan ?></i></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;"><i>Head of Technician, Media Technology, and Sec</i></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;"><i>General Manager</i></td>
						</tr>
					</tbody>

				</table>
		</div>
		<?= form_close(); ?>
		<br><br>
		<div width="100%">
			<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '15' || sessPenggunaId() == '33') { ?>
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-Sijk="<?= encrypt($data_kend[0]->idGc) ?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-Sijk="<?= encrypt($data_kend[0]->idGc) ?>"> <i class="fas fa-times"></i> Tolak </button>
			<?php } ?>
			<a href="surat_new/print_page/kendaraan/<?= $data_kend[0]->idGc ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
			<?php if ($data_kend[0]->lampiran != "") { ?>
				<a href="<?= $data_kend[0]->lampiran ?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran </a>
			<?php } ?>
			<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left">Kembali</button>
			<?php if (isAdmin()): ?>
				<button type="button" onclick="openAdminEditModal('kendaraan', '<?= encrypt($data_kend[0]->idGc) ?>')" class="btn btn-warning float-left font-weight-bold text-dark" style="margin-left:12px;">
					<i class="fas fa-user-shield mr-1"></i> Edit Data (Admin)
				</button>
			<?php endif; ?>
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
				title: 'Setujui Permintaan Penggunaan Kendaraan Kantor?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat_new/ttd_setujui/kendaraan/' + level_ttd,
						dataType: 'JSON',
						data: {
							id: id,
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
				title: 'Tolak Permintaan Penggunaan Kendaraan Kantor?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat_new/ttd_tolak/kendaraan/' + level_ttd,
						dataType: 'JSON',
						data: {
							id: id,
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