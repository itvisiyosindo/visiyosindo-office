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
	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
		<div class="text-center">
			<h2>
				<font color='#000000' face='Times New Roman'>No : <?= $data_gc[0]->kode ?></font>
			</h2>
			<input type="hidden" id="kodesurat" value="<?= $data_gc[0]->kode ?>">
			<input type="hidden" id="idpengaju" value="<?= $data_gc[0]->idPengaju ?>">
		</div>
		<?php
		$ttd = "ttd_1";
		if (in_array(sessPenggunaId(), ['714', '764', '777', '29'])) {
			$ttd = 'ttd_1';
		}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>">
		<div class="table-responsive">
			<font color='#000000'>
				<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
					<tr>
						<td colspan="3" style="text-align:left">
							<font color='#000000'>Dengan ini saya mengajukan biaya perjalanan dinas :</font>
						</td>
					</tr>
					<tr>
						<font color='#ffffff'>
							<?=
							$dt_pengajuan	= strtotime($data_gc[0]->tglPengajuan);
							$dt_pergi		= strtotime($data_gc[0]->tglPergi);
							$dt_pulang		= strtotime($data_gc[0]->tglKembali);

							$tgl_pengajuan 	= date("d", $dt_pengajuan) . " - " . date("m", $dt_pengajuan) . " - " . date("Y", $dt_pengajuan);
							$hari_pergi 	= date("D", $dt_pergi);
							$tgl_pergi 		= date("d", $dt_pergi) . " - " . date("m", $dt_pergi) . " - " . date("Y", $dt_pergi);
							$pergi 	        = date_create(date("d", $dt_pergi) . "-" . date("m", $dt_pergi) . "-" . date("Y", $dt_pergi));
							$pulang 	    = date_create(date("d", $dt_pulang) . "-" . date("m", $dt_pulang) . "-" . date("Y", $dt_pulang));
							$lama_hari 		= date_diff($pergi, $pulang);
							$lama_hari		= $lama_hari->format("%d") + 1;

							switch ($hari_pergi) {
								case 'Sun':
									$hari_pergi = "Minggu";
									break;
								case 'Mon':
									$hari_pergi = "Senin";
									break;
								case 'Tue':
									$hari_pergi = "Selasa";
									break;
								case 'Wed':
									$hari_pergi = "Rabu";
									break;
								case 'Thu':
									$hari_pergi = "Kamis";
									break;
								case 'Fri':
									$hari_pergi = "Jumat";
									break;
								case 'Sat':
									$hari_pergi = "Sabtu";
									break;
							}
							?>
						</font>
						<td rowspan="9" width="7%"></td>
					<tr>
						<td width="18%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_gc[0]->pengaju ?> </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp; <?= $data_gc[0]->jabatan ?></td>
					</tr>
					<tr>
						<td>Kota Tujuan </td>
						<td>:&nbsp;&nbsp; <?= $data_gc[0]->kota ?></td>
					</tr>
					<tr>
						<td>Keperluan </td>
						<td>:&nbsp;&nbsp; <?= $data_gc[0]->perihal ?></td>
					</tr>
					<tr>
						<td>Hari </td>
						<td>:&nbsp;&nbsp; <?= $hari_pergi ?></td>
					</tr>
					<tr>
						<td>Tanggal </td>
						<td>:&nbsp;&nbsp; <?= $tgl_pergi ?></td>
					</tr>
					<tr>
						<td>Lama Perjalanan </td>
						<td>:&nbsp;&nbsp; <?= $lama_hari ?> hari</td>
					</tr>
					<tr>
						<td>
							<font color="white">i </font>
						</td>
					</tr>
					</tr>
				</table>

				<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
					<thead>
						<tr>
							<th style="text-align:center" bgcolor="#b7d5ac" width="20%"> Tanggal </th>
							<th style="text-align:center" bgcolor="#b7d5ac"> Keterangan </th>
							<th style="text-align:center" bgcolor="#b7d5ac" width="25%"> Nominal </th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						$nom = 0;
						foreach ($detail_gc as $row) {
							$x = $x + 1;
							$nom = (int) $row->nominal + $nom;
						?>
							<tr>
								<td class="tgl" style="text-align:center;"><?= $row->tgl ?></td>
								<td class="ket">&nbsp;<?= $row->ket ?>&nbsp;</td>
								<td class="nom">
									<label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
									<label class="mylabel" style="text-align:right"><?= number_format($row->nominal, 0, ",", ".") ?>,-&nbsp;</label>
								</td>
							</tr>
						<?php } ?>
						<?php for ($kosong = $x; $kosong <= 13; $kosong++) { ?>
							<tr>
								<td>
									<font color="white">i </font>
								</td>
								<td> </td>
								<td> </td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<th bgcolor="#b7d5ac" colspan="2" style="text-align:center"> TOTAL </th>
							<td bgcolor="#b7d5ac">
								<label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
								<label class="mylabel" style="text-align:right"><?= number_format($nom, 0, ",", ".") ?>,-&nbsp;</label>
								<input type="hidden" id="jml_nom" value="<?= $nom ?>">
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<input class="myinput" type="text" id="lampiran_finance" Style="width:100%" placeholder="Pastekan link lampiran (dalam pengerjaan)">
							</td>
						</tr>
					</tfoot>
				</table>
				<br>
				<table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
					<tbody>
						<tr style="height: 35px;">
							<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="3">
								<?= $data_gc[0]->kota_pengajuan ?>, <?= $tgl_pengajuan ?>
							</td>
						</tr>
						<tr style="height: 18px;">
							<td style="text-align:center; width:25%;">Diajukan Oleh,</td>
							<td></td>
							<td style="text-align:center; width:25%;">Diverifikasi Oleh,</td>
						</tr>
						<tr style="height:60px;">
							<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path . "ttd_" . $data_gc[0]->idPengaju . ".png";
							$ttd1 		= $img_path . "ttd_notyet2.png";
							$nama_verifikator = isset($masternotifikasi[0]->namav1) ? $masternotifikasi[0]->namav1 : 'Mulia';
							$jabatan_verifikator = isset($masternotifikasi[0]->jabatanv1) ? $masternotifikasi[0]->jabatanv1 : 'Finance Staff';

							if ($data_gc[0]->aju_ttd1 == '1') {
								// Verifikator resmi ID 777 (Mulia)
								$id_ver = '777';
								$ttd1 = $img_path . "ttd_" . $id_ver . ".png";
							} else if ($data_gc[0]->aju_ttd1 == '2') {
								$ttd1 = $img_path . "ttd_not.png";
							}
							?>
							<td style="text-align:center; width:23%;"> <?php echo '<img src="' . $ttdaju . '" height="70">'; ?> </td>
							<td style="text-align:center;"></td>
							<td style="text-align:center; width:23%;"><?php echo '<img src="' . $ttd1 . '" height="70">'; ?></td>
						</tr>
						<tr>
							<td style="text-align:center; "><?= $data_gc[0]->nama_ttd ?>
								<hr>
								</hr>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; "><?= $nama_verifikator ?>
								<hr>
								</hr>
							</td>
						</tr>
						<tr>
							<td style="text-align:center; vertical-align:top;"><i><?= $data_gc[0]->jabatan ?></i></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;"><i><?= $jabatan_verifikator ?></i></td>
						</tr>
					</tbody>
				</table>
				<br><br>
				<div width="100%">
					<?php if (in_array(sessPenggunaId(), ['1', '58', '714', '764', '777', '29']) || sessPenggunaId() != $data_gc[0]->idPengaju) { ?>
						<button type="button" class="btn btn-success btn-save float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-gc="<?= encrypt($data_gc[0]->idGc) ?>"> <i class="fas fa-check"></i> Setujui </button>
						<button type="button" class="btn btn-secondary btn-save float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-gc="<?= encrypt($data_gc[0]->idGc) ?>"> <i class="fas fa-times"></i> Tolak </button>
					<?php } ?>
					<a href="surat/print_page/gc/<?= $data_gc[0]->idGc ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
					<?php if (function_exists('isAdmin') && isAdmin()): ?>
						<button type="button" onclick="openAdminEditModal('gc', '<?= encrypt($data_gc[0]->idGc) ?>')" class="btn btn-warning float-right font-weight-bold text-dark" style="margin-left:12px; margin-top:12px;">
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
			const id_gc = $(this).attr("id-gc");

			Swal.fire({
				title: 'Setujui Pengajuan Limit GoCorp?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/gc/1/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_gc: id_gc,
							kodesurat: $('#kodesurat').val(),
							idpengaju: $('#idpengaju').val(),
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
			const id_gc = $(this).attr("id-gc")
			Swal.fire({
				title: 'Tolak Pengajuan Limit GoCorp?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/gc/2/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_gc: id_gc,
							kodesurat: $('#kodesurat').val(),
							idpengaju: $('#idpengaju').val(),
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
