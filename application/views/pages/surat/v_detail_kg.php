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

		.mydiv {
			display: inline-block;
		}

		.mylabel {
			border: 0px solid blue;
			display: table-cell;
			width: 100%;
		}

		.myinput {
			width: 100%;
			height: auto;
			border: 2px solid orange;
			border-radius: 8px;
			-moz-border-radius: 8px;
			margin-left: 0px;
		}
	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
		<div class="text-right">
			<?php
			if ($data_kg[0]->persetujuan_ga == 0) {
				echo '<span class="btn btn-warning"><i class="fas fa-info"></i>&nbsp; Baru Diajukan</span>';
			} else if ($data_kg[0]->persetujuan_ga == 1) {
				if ($data_kg[0]->stat_surat == 2) {
					echo '<span class="btn btn-warning"><i class="fas fa-info"></i>&nbsp; Laporan Diajukan</span>';
				} else if ($data_kg[0]->stat_surat == 3) {
					echo '<span class="btn btn-success"><i class="fas fa-check"></i>&nbsp; Laporan Diterima GA</span>';
				} else {
					echo '<span class="btn btn-success"><i class="fas fa-check"></i>&nbsp; Disetujui GA</span>';
				}
			} else if ($data_kg[0]->persetujuan_ga == 2) {
				echo '<span class="btn btn-danger"><i class="fas fa-times"></i>&nbsp; Ditolak GA</span>';
			}
			?>
		</div>
		<div class="text-center" style="overflow-wrap:break-word;">
			<h2>
				<font color='#000000' face='Times New Roman'>No : <?= $data_kg[0]->kode ?></font>
			</h2>
		</div>
		<?php
		$ttd = "";
		if ((sessPenggunaId() == '70')) {
			$ttd = 'ttd_1';
		} else if ((sessPenggunaId() == '23')) {
			$ttd = 'ttd_2';
		} else if ((sessPenggunaId() == '33')) {
			$ttd = 'ttd_3';
		} else if (isGa()) {
			$ttd = 'persetujuan_ga';
			if ($data_kg[0]->stat_surat == 2) {
				$ttd = 'persetujuan_laporan';
			}
		}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>">
		<div class="table-responsive">
			<font color='#000000'>
				<!-- TABEL SURAT KUNJUNGAN GUDANG -->
				<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
					<tbody>
						<tr>
							<td colspan="4"> Perihal : Dinas </td>
						</tr>
						<tr>
							<td colspan="4">
								<font color="white">i </font>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								Sehubungan dengan adanya pekerjaan
								<?= $data_kg[0]->perihal ?> di Gudang Pusat pada Tanggal <?= date('d-m-Y', strtotime($data_kg[0]->tgl_dinas)); ?>, maka dengan ini kami mengutus karyawan kami
								sebagai berikut :
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<font color="white">i </font>
							</td>
						</tr>
						<tr>
							<td width="5%" style="text-align:right;">1. &nbsp;</td>
							<td>Nama</td>
							<td>:&nbsp;<?= $data_kg[0]->nama ?></td>
							<td></td>
						</tr>
						<tr>
							<td width="5%"></td>
							<td>Jabatan</td>
							<td>:&nbsp;<?= $data_kg[0]->jabatan ?></td>
							<td></td>
						</tr>
						<tr>
							<td width="5%"></td>
							<td>NIK</td>
							<td>:&nbsp;<?= $data_kg[0]->nik ?></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4">
								<font color="white">i </font>
							</td>
						</tr>
						<tr>
							<td colspan="4">Untuk melaksanakan tugas pada pekerjaan tersebut. Kami berharap karyawan kami dapat
								menyelesaikan tugas tersebut dengan baik.</td>
						</tr>
						<tr>
							<td colspan="4">
								<font color="white">i </font>
							</td>
						</tr>
						<tr>
							<td colspan="4">Surat pengantar dinas ini berlaku hingga tugas selesai dan yang bersangkutan telah memberikan
								laporan kembali ke Perusahaan.
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<font color="white">i </font>
							</td>
						</tr>
						<tr>
							<td colspan="4">Demikian surat pengantar dinas ini dibuat, agar dapat digunakan sebagaimana mestinya. Atas
								perhatian dan kerjasamanya diucapkan terimakasih.</td>
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
							<td width="5%"></td>
							<td></td>
							<td></td>
							<td style="text-align:center; ">Pekanbaru, <?= date('d-m-Y', strtotime($data_kg[0]->tgl_pengajuan)); ?></td>
						</tr>
						<tr>
							<td width="5%"></td>
							<td></td>
							<td></td>
							<td style="text-align:center; ">PT. Visi Yosindo Medkal</td>
						</tr>
						<tr style="height:60px;">
							<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							// $ttdaju		= $img_path."ttd_".$data_pkk[0]->idPengaju.".png";
							// $ttd1 		= $img_path."ttd_notyet2.png";
							// $ttd2 		= $img_path."ttd_notyet2.png";
							$ttd3 		= $img_path . "ttd_notyet2.png";

							if ($data_kg[0]->aju_ttd3 == '1') {
								$ttd3 = $img_path . "ttd_" . $masternotifikasi[0]->disetujui1 . "_cap.png";
							} else if ($data_kg[0]->aju_ttd3 == '2') {
								$ttd3 = $img_path . "ttd_not.png";
							}
							?>
							<td style="text-align:center; width:5%;"> </td>
							<td style="text-align:center; width:2.5%;"></td>
							<td style="text-align:center; width:23%;"></td>
							<td style="text-align:center; width:24%;"><?php echo '<img src="' . $ttd3 . '" height="70">'; ?></td>

						</tr>
						<tr>
							<td width="5%" style="text-align:center; "></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; "><b><u><?= $masternotifikasi[0]->namad1 ?></u></b></td>
						</tr>
						<tr>
							<td width="5%" style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;"></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;"><i><?= $masternotifikasi[0]->jabatand1 ?></i></td>
						</tr>
						<tr>
							<td colspan="4">
								<font color="white">i </font>
							</td>
						</tr>
						<tr>
							<td colspan="4"><i>Tembusan :</i></td>
						</tr>
						<tr>
							<td width="5%" style="text-align:right; "><i>1. </i></td>
							<td colspan="3" style="text-align:left; vertical-align:top;">&nbsp;<i>Director</i></td>
						</tr>
						<tr>
							<td colspan="4">
								<font color="white">i </font>
							</td>
						</tr>
						<?php if (sessPenggunaId() == $data_kg[0]->idPengaju && $data_kg[0]->laporan == "" && $data_kg[0]->stat_surat == 1) { ?>
							<tr>
								<td colspan="4">
									<font color="black">Link Laporan : </font><input class="myinput" type="text" name="lampiran_laporan" id="lampiran_laporan" placeholder=" &nbsp;Pastekan link laporan kunjungan gudang anda" required>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<br>
				<!-- PENUTUP SURAT KUNJUNGAN GUDANG -->

				<br><br>
				<div width="100%">
					<?php
					if (sessPenggunaId() != $data_kg[0]->idPengaju) {
						if ($data_kg[0]->stat_surat == 0) {
					?>
							<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-kg="<?= encrypt($data_kg[0]->id) ?>"> <i class="fas fa-check"></i> Setujui </button>
							<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-kg="<?= encrypt($data_kg[0]->id) ?>"> <i class="fas fa-times"></i> Tolak </button>
						<?php } else if ($data_kg[0]->stat_surat == 2 && isGa()) { ?>
							<button type="button" class="btn btn-success float-right btn-terima-laporan" style="margin-left:12px; margin-top:12px;" id-kg="<?= encrypt($data_kg[0]->id) ?>"> <i class="fas fa-check"></i> Terima Laporan </button>
						<?php } ?>
					<?php
					} else if (sessPenggunaId() == $data_kg[0]->idPengaju && $data_kg[0]->laporan == "") {
					?>
						<button type="button" class="btn btn-success float-right btn-up-laporan" style="margin-left:12px; margin-top:12px;" id-kg="<?= encrypt($data_kg[0]->id) ?>"> <i class="fas fa-check"></i> Submit Laporan </button>
					<?php } ?>
					<?php if ($data_kg[0]->lampiran != "") { ?>
						<a href="<?= $data_kg[0]->lampiran ?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran Pengajuan</a>
					<?php } ?>
					<?php if ($data_kg[0]->laporan != "") { ?>
						<a href="<?= $data_kg[0]->laporan ?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran Laporan</a>
					<?php } ?>
					<a href="surat/print_page/kg/<?= $data_kg[0]->id ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
					<?php if (isAdmin()): ?>
						<button type="button" onclick="openAdminEditModal('kg', '<?= encrypt($data_kg[0]->id) ?>')" class="btn btn-warning float-right font-weight-bold text-dark" style="margin-left:12px; margin-top:12px;">
							<i class="fas fa-user-shield mr-1"></i> Edit Data (Admin)
						</button>
					<?php endif; ?>
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
			const id_kg = $(this).attr("id-kg");

			Swal.fire({
				//title: approval + ' absensi?',
				title: 'Setujui Pengajuan Kunjungan Gudang?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/kg/1/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_kg: id_kg,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
						}
					})

				}

			})
		})

		$(document).on('click', '.btn-up-laporan', function() {
			const id_kg = $(this).attr("id-kg");
			var laporan = $('#lampiran_laporan').val();

			Swal.fire({
				title: 'Upload Laporan Kunjungan Gudang?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/laporan_update/kg',
						dataType: 'JSON',
						data: {
							id_kg: id_kg,
							laporan: laporan,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
						}
					})

				}

			})
		})

		$(document).on('click', '.btn-terima-laporan', function() {
			const id_kg = $(this).attr("id-kg");

			Swal.fire({
				//title: approval + ' absensi?',
				title: 'Setujui Laporan Kunjungan Gudang?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/kg/1/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_kg: id_kg,
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
			const id_kg = $(this).attr("id-kg");
			Swal.fire({
				title: 'Tolak Pengajuan Kunjungan Gudang?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/kg/2/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_kg: id_kg,
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