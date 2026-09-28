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
			//font-family:Garamond;
			//background:#363;
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
			if ($data_meeting[0]->ttd_1 == 0) {
				echo '<span class="btn btn-warning">Baru Diajukan</span>';
			} else if ($data_meeting[0]->ttd_1 == 1) {
				echo '<span class="btn btn-success">Disetujui GA</span>';
			} else if ($data_meeting[0]->ttd_1 == 2) {
				echo '<span class="btn btn-danger">Ditolak GA</span>';
			}
			?>
		</div>

		<div class="text-center">
			<h2>
				<font color='#000000' face='Times New Roman'>No : <?= $data_meeting[0]->kode ?> </font>
			</h2>
		</div>
		<?php
		$ttd = "ttd_1";
		if ((sessPenggunaId() == '58')) {
			$ttd = 'ttd_1';
		} else if ((sessPenggunaId() == '69')) {
			$ttd = 'ttd_2';
		}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>">
		<?php $id = $data_meeting[0]->idMeet ?>
		<input type="hidden" name="id" id="id" value="<?= $data_meeting[0]->idMeet ?>">

		<div class="table-responsive">
			<font color='#000000'>
				<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
				<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
					<tbody>
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
							<td colspan="4">
								<font color="white">i </font>
							</td>
						</tr>
						<tr>
							<td width="7%" style="text-align:right;"></td>
							<td width="8%">Nama</td>
							<td>:&nbsp;<?= $data_meeting[0]->nama ?></td>
							<td width="30%"></td>
						</tr>
						<tr>
							<td width="5%"></td>
							<td>Jabatan</td>
							<td>:&nbsp;<?= $data_meeting[0]->jabatan ?></td>
							<td></td>
						</tr>
						<tr>
							<td width="5%"></td>
							<td>NIK</td>
							<td>:&nbsp;<?= $data_meeting[0]->nik ?></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4">
								<font color="white">i </font>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<font color="white">i </font>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								Mengajukan permohonan izin menggunakan Ruang Meeting untuk keperluan, yaitu :
								<?= $data_meeting[0]->perihal ?>
							</td>
						</tr>
						<tr>
							<td colspan="4">Permohonan izin menggunakan Ruang Meeting pada tangal
								&nbsp;&nbsp;
								<?= date('d-m-Y', strtotime($data_meeting[0]->tgl_pengajuan)) ?>
								&nbsp;&nbsp;
								dari pukul &nbsp;&nbsp;
								<?= $data_meeting[0]->jam_mulai ?>
								&nbsp;&nbsp;
								hingga &nbsp;&nbsp;
								<?= $data_meeting[0]->jam_akhir ?>
								&nbsp;&nbsp;
						</tr>
						<tr>
							<td colspan="4"><br>Demikian surat permohonan izin ini saya ajukan, atas perhatian dan izin yang diberikan saya
								ucapkan terima kasih.</td>
						</tr>
						<tr>
							<td colspan="4">
								<font color="white">i </font>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<font color="white">i </font>
							</td>
						</tr>
						<tr>
							<td colspan="3"></td>
							<td style="text-align:center; ">Pekanbaru, <?= date('d-m-Y', strtotime($data_meeting[0]->created_at)) ?></td>
						</tr>
						<tr>
							<td colspan="3"></td>
							<td style="text-align:center; ">PT. Visi Yosindo Medkal</td>
						</tr>
						<tr style="height:60px;">
							<?php

							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttd2 		= $img_path . "ttd_notyet2.png";

							if ($data_meeting[0]->ttd_2 == '1') {
								$ttd2 = $img_path . "ttd_69.png";
							} else if ($data_meeting[0]->ttd_2 == '2') {
								$ttd2 = $img_path . "ttd_not.png";
							}
							?>
							<td colspan="3"></td>
							<td style="text-align:center; "><?php echo '<img src="' . $ttd2 . '" height="70">'; ?></td>

						</tr>
						<tr>
							<td colspan="3"></td>
							<td style="text-align:center; "><b>Dian Melati Amelia
									<hr>
									</hr>
								</b></td>
						</tr>
						<tr>
							<td colspan="3"></td>
							<td style="text-align:center; vertical-align:top;"><i>HR & Legal Officer</i></td>
						</tr>
						<tr>
							<td colspan="4">
								<font color="white">i </font>
							</td>
						</tr>
					</tbody>
				</table>
		</div>
		<?= form_close(); ?>
		<br><br>
		<div width="100%">
			<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '58' || sessPenggunaId() == '69') { ?>
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-Meet="<?= encrypt($data_meeting[0]->idMeet) ?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-Meet="<?= encrypt($data_meeting[0]->idMeet) ?>"> <i class="fas fa-times"></i> Tolak </button>
			<?php } ?>
			<a href="surat_part_two/print_page/meetingroom/<?= $data_meeting[0]->idMeet ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
			<?php if ($data_meeting[0]->lampiran != "") { ?>
				<a href="<?= $data_meeting[0]->lampiran ?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran </a>
			<?php } ?>
			<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left">Kembali</button>
			<?php if (isAdmin()): ?>
				<button type="button" onclick="openAdminEditModal('meetingroom', '<?= encrypt($data_meeting[0]->idMeet) ?>')" class="btn btn-warning float-left font-weight-bold text-dark" style="margin-left:12px;">
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
				//title: approval + ' absensi?',
				title: 'Setujui Pengajuan Penggunaan Ruang Meeting?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat_part_two/ttd_setujui/meetingroom/' + level_ttd,
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
				//title: approval + ' absensi?',
				title: 'Tolak Pengajuan Penggunaan Ruang Meeting?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat_part_two/ttd_tolak/meetingroom/' + level_ttd,
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