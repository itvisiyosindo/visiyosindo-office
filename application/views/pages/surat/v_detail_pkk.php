<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
			border: 0px solid #000;
			border-radius: 0px;
			-moz-border-radius: 8px;
			margin-left: 0px;
		}
	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
		<div class="text-center">
			<h2>
				<font color='#000000' face='Times New Roman'>No : <?= $data_pkk[0]->kodePKK ?></font>
			</h2>
			<h5>
				<font color='#666666' face='Times New Roman'><?= mata_uang_icon($data_pkk[0]->mata_uang) ?></font>
			</h5>
		</div>
		<?php
		$ttd = "ttd_1";
		if ((sessPenggunaId() == '107')) {
			$ttd = 'ttd_1';
		} else if ((sessPenggunaId() == '33')) {
			$ttd = 'ttd_2';
		} else if ((sessPenggunaId() == '23')) {
			$ttd = 'ttd_3';
		}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>">
		<div class="table-responsive">
			<font color='#000000'>
				<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
					<tr>
						<td></td>
						<td colspan="3" style="text-align:left">
							<font color='#000000'>Dengan ini saya mengajukan klaim kas :</font>
						</td>
					</tr>
					<tr>
						<td rowspan="9" width="7%"></td>
					<tr>
						<!-- <td width="5%"></td> -->
						<td width="22%" colspan="2">
							<font color='#000000'> Nama
						</td>
						<td>
							<font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->nama ?>
						</td>
					</tr>
					<tr>
						<!-- <td width="5%"></td> -->
						<td colspan="2">
							<font color='#000000'> Jabatan
						</td>
						<td>
							<font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->jabatan ?>
						</td>
					</tr>
					<tr>
						<!-- <td width="5%"></td> -->
						<td colspan="2" width="22%">
							<font color='#000000'> Kas
						</td>
						<td>
							<font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->type ?>
						</td>
					</tr>
					<tr>
						<!-- <td width="5%"></td> -->
						<td colspan="2" width="22%">
							<font color='#000000'> Keterangan
						</td>
						<td>
							<font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->keterangan_pengaju ?>
						</td>
					</tr>
					<tr>
						<td colspan="3" color='#000000'>Nomor Rekening Pembayaran </td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td>
							<font color='#000000' width="22%"> Nama Bank
						</td>
						<td>
							<font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->nama_bank ?>
						</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td>
							<font color='#000000' width="22%"> Nomor Rekening
						</td>
						<td>
							<font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->no_rek ?>
						</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td>
							<font color='#000000' width="22%"> Atas Nama
						</td>
						<td>
							<font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->ats_nama ?>
						</td>
					</tr>
					<tr>
						<td>
							<font color="white">i </font>
						</td>
					</tr>
					</tr>
				</table>

				<!-- TABEL BIAYA OPERASIONAL -->
				<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
					<thead>
						<tr>
							<th colspan="3" bgcolor="#b7d5ac" colspan="3" style="text-align:center">Biaya Operasional</th>
						</tr>
						<tr>
							<th style="text-align:center" bgcolor="#b7d5ac" width="15%"> Tanggal </th>
							<th style="text-align:center" bgcolor="#b7d5ac" width="60%"> Keterangan </th>
							<th style="text-align:center" bgcolor="#b7d5ac" width="25%"> Nominal </th>
						</tr>
					</thead>

					<tbody>
						<?php
						$x = 1;
						$nom = 0;
						// var_dump($detail_pkk);
						// die;
						foreach ($detail_pkk as $row) {
							$x = $x + 1;
							$nom = (float) $row->nominal + $nom;
						?>
							<tr>
								<!-- <td class="tgl" style="text-align:center;"><?= $row->tgl ?></td> -->
								<td class="tanggal" style="text-align:center;">
									<!-- <?= $row->tanggal ?> -->
									<?php
									if ($row->nominal == 0) {
										echo "<font color='white'>i </font>";
									} else {
										echo $row->tanggal;
									}
									?>
								</td>
								<td class="ket_detail">
									&nbsp;<?= $row->ket_detail ?>&nbsp;
								</td>
								<td class="nominal">
									<?php
									if ($row->nominal == 0) {
										echo "<font color='white'>i </font>";
									} else { ?>
										<label class="mylabel" style="text-align:left">&nbsp;Rp.
										</label>
										<label class="mylabel" style="text-align:right"><?= number_format($row->nominal, 0, ",", ".") ?>,-&nbsp;
										</label>
									<?php }	?>
								</td>
							</tr>
						<?php } ?>
						<?php for ($kosong = $x; $kosong <= 5; $kosong++) { ?>
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
							<th colspan="2" bgcolor="#b7d5ac" style="text-align:center">TOTAL </th>
							<th bgcolor="#b7d5ac"><label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
								<label class="mylabel" style="text-align:right"><?= number_format($nom, 0, ",", ".") ?>,-&nbsp;</label>
								<input type="hidden" id="jml_nom" value="<?= $nom ?>">
							</th>
						</tr>
					</tfoot>
				</table>
				<!-- PENUTUP TABEL BIAYA OPERASIONAL -->
				<br><br>
				<!-- TABEL BIAYA DINAS -->
				<?php if ($detail_pkk1) { ?>
					<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
						<thead>
							<tr>
								<th colspan="3" bgcolor="#b7d5ac" colspan="3" style="text-align:center">Biaya Dinas</th>
							</tr>
							<tr>
								<th style="text-align:center" bgcolor="#b7d5ac" width="15%"> Tanggal </th>
								<th style="text-align:center" bgcolor="#b7d5ac" width="60%"> Keterangan </th>
								<th style="text-align:center" bgcolor="#b7d5ac" width="25%"> Nominal </th>
							</tr>
						</thead>

						<tbody>
							<?php
							$x = 1;
							$nom2 = 0;
							foreach ($detail_pkk1 as $row) {
								$x = $x + 1;
								if ($detail_pkk1 != null) {
									$nom2 = (float) $row->nominal + $nom2;
								} else {
									$nom2 = 0;
								}
							?>
								<tr>
									<!-- <td class="tgl" style="text-align:center;"><?= $row->tgl ?></td> -->
									<td class="tanggal" style="text-align:center;">
										<!-- <?= $row->tanggal ?></td> -->
										<?php
										if ($row->nominal == 0) {
											echo "<font color='white'>i </font>";
										} else {
											echo $row->tanggal;
											// echo date('d-m-Y', strtotime($row->tanggal));
										}
										?>
									<td class="ket_detail">
										&nbsp;<?= $row->ket_detail ?>&nbsp;</td>
									<td class="nominal">
										<?php
										if ($row->nominal == 0) {
											echo "<font color='white'>i </font>";
										} else { ?>
											<label class="mylabel" style="text-align:left">&nbsp;Rp.
											</label>
											<label class="mylabel" style="text-align:right"><?= number_format($row->nominal, 0, ",", ".") ?>,-&nbsp;
											</label>
										<?php }	?>
									</td>
								</tr>
							<?php } ?>

							<?php for ($kosong = $x; $kosong <= 5; $kosong++) { ?>
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
								<th colspan="2" bgcolor="#b7d5ac" style="text-align:center">TOTAL </th>
								<th bgcolor="#b7d5ac"><label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
									<label class="mylabel" style="text-align:right"><?= number_format($nom2, 0, ",", ".") ?>,-&nbsp;</label>
									<input type="hidden" id="jml_nom" value="<?= $nom ?>">
								</th>
							</tr>
						</tfoot>
					</table>
					<!-- PENUTUP TABEL BIAYA DINAS -->
					<br><br>
					<!-- TABEL CATATAN FINANCE -->
				<?php } ?>
				<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
					<form action="">
						<thead>
							<tr>
								<th colspan="3" bgcolor="#b7d5ac" colspan="3" style="text-align:center">Catatan Finance</th>
							</tr>
							<tr>
								<th style="text-align:center" bgcolor="#b7d5ac" width="15%"> Tanggal </th>
								<th style="text-align:center" bgcolor="#b7d5ac" width="60%"> Keterangan </th>
								<th style="text-align:center" bgcolor="#b7d5ac" width="25%"> Nominal </th>
							</tr>
						</thead>

						<tbody>
							<?php
							$x2 = 1;
							$nom3 = 0;
							// var_dump($detail_pkk);
							// die;
							foreach ($detail_pkk2 as $row) {
								$x2 = $x2 + 1;
								$nom3 = (float) $row->nominal + $nom3;
							?>

								<tr>
									<!-- <td class="tgl" style="text-align:center;"><?= $row->tgl ?></td> -->
									<td class="tanggal" style="text-align:center;">
										<!-- <?= $row->tanggal ?> -->
										<?php
										if ($row->nominal == 0) {
											echo "";
										} else {
											echo $row->tanggal;
										}
										?>
									</td>
									<td class="ket_detail">
										&nbsp;<?= $row->ket_detail ?>&nbsp;
									</td>
									<td class="nominal">
										<?php
										if ($row->nominal == 0) {
											echo "";
										} else { ?>
											<label class="mylabel" style="text-align:left">&nbsp;Rp.
											</label>
											<label class="mylabel" style="text-align:right"><?= number_format($row->nominal, 0, ",", ".") ?>,-&nbsp;
											</label>
										<?php }	?>
									</td>
								</tr>
							<?php } ?>

							<?php $itung2 = 1;
							for ($x2 = 1; $x2 <= 12; $x2++) {
								$itung2 = $x2;

								if ($pengguna[0]->pengguna_id == 107) {
							?>
									<tr>
										<input class="no-outline" type="hidden" id="id_pkk" name="id_pkk" value="<?= $data_pkk[0]->idPKK ?>">
										<td>
											<input class="myinput" type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="<?= 'tanggal2_' . $x2 ?>" Style="width:100%; text-align:center">
										</td>
										<td>
											<input class="myinput" type="text" id="<?= 'ket_detail2_' . $x2 ?>" Style="width:100%" placeholder="Diisi oleh finance">
										</td>
										<td>
											<input class="myinput" type="number" id="<?= 'nominal2_' . $x2 ?>" Style="width:100%; text-align:right" placeholder="Diisi oleh finance">
										</td>
									</tr>
								<?php } else {
									echo '
                                            <tr>
                                                <td> <font color="white">i </font> </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        ';
								} ?>
							<?php } ?>
							<input type="hidden" id="itung2" value="<?= $itung2 ?>" />
						</tbody>

						<tfoot>
							<?php if ($pengguna[0]->pengguna_id == 107) { ?>
								<tr>
									<td colspan="3">
										<input class="myinput" type="text" id="lampiran_finance" Style="width:100%" placeholder="Pastekan link lampiran">
									</td>
								</tr>
							<?php } ?>
							<tr>
								<th colspan="2" bgcolor="#b7d5ac" style="text-align:center">TOTAL </th>
								<th bgcolor="#b7d5ac"><label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
									<label class="mylabel" style="text-align:right"><?= number_format($nom3, 0, ",", ".") ?>,-&nbsp;</label>
									<input type="hidden" id="jml_nom" value="<?= $nom ?>">
								</th>
							</tr>
						</tfoot>
					</form>
				</table>
				<!-- PENUTUP TABEL CATATAN FINANCE -->
				<br>
				<!-- TABEL PERHITUNGAN TOTAL KESELURUHAN -->
				<?php
				$nom4 = 0;
				if ($detail_pkk1 != null) {
					$nom2 = $nom2;
				} else {
					$nom2 = 0;
				}
				$nom4 = $nom + $nom2 + $nom3;
				?>
				<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
					<thead>
						<th bgcolor="#b7d5ac" style="text-align:center">TOTAL BIAYA OPERASIONAL</th>
						<th bgcolor="#b7d5ac" width="25%"><label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
							<label class="mylabel" style="text-align:right"><?= number_format($nom, 0, ",", ".") ?>,-&nbsp;</label>
							<input type="hidden" id="jml_nom" value="<?= $nom ?>">
						</th>
					</thead>
					<thead>
						<th bgcolor="#b7d5ac" style="text-align:center">TOTAL BIAYA DINAS</th>
						<th bgcolor="#b7d5ac"><label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
							<label class="mylabel" style="text-align:right"><?= number_format($nom2, 0, ",", ".") ?>,-&nbsp;</label>
							<input type="hidden" id="jml_nom" value="<?= $nom ?>">
						</th>
					</thead>
					<thead>
						<th bgcolor="#b7d5ac" style="text-align:center">TOTAL CATATAN FINANCE</th>
						<th bgcolor="#b7d5ac"><label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
							<label class="mylabel" style="text-align:right"><?= number_format($nom3, 0, ",", ".") ?>,-&nbsp;</label>
							<input type="hidden" id="jml_nom" value="<?= $nom ?>">
						</th>
					</thead>
					<thead>
						<th bgcolor="#b7d5ac" style="text-align:center">GRAND TOTAL BIAYA PENGAJUAN KLAIM KAS</th>
						<th bgcolor="#b7d5ac"><label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
							<label class="mylabel" style="text-align:right"><?= number_format($nom4, 0, ",", ".") ?>,-&nbsp;</label>
							<input type="hidden" id="jml_nom" value="<?= $nom ?>">
						</th>
					</thead>
				</table>
				<!-- PENUTUP TABEL PERHITUNGAN TOTAL KESELURUHAN -->
				<br>
				<!-- TABEL KETERANGAN -->
				<table id="tbl_2" border="0">
					<tr>
						<td width="7%">
						<td style="text-align:justify; text-justify:inter-word;">
							<font style="font-family:Times New Roman; font-size:15px;">
								Saya membuat laporan penggunaan kas dengan melampirkan Struk/Bon biaya terkait dan akan diberikan kepada bagian keuangan.
							</font>
						</td>
						<td width="7%">
					</tr>
				</table>
				<!-- PENUTUP TABEL KETERANGAN -->
				<br>
				<table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
					<tbody>
						<tr style="height: 35px;">
							<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
								<?= $data_pkk[0]->kota_aju ?>, <?= date('d-m-Y', strtotime($data_pkk[0]->tgl_pengajuan)); ?>
							</td>
						</tr>
						<tr style="height: 18px;">
							<td style="text-align:center; width:25.5%;" colspan="2">Diajukan Oleh,</td>
							<td style="text-align:center; width:49%;" colspan="3">Diverifikasi Oleh,</td>
							<td style="text-align:center; width:25.5%;" colspan="2">Disetujui Oleh,</td>
						</tr>
						<tr style="height:60px;">
							<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path . "ttd_" . $data_pkk[0]->idPengaju . ".png";
							$ttd1 		= $img_path . "ttd_notyet2.png";
							$ttd2 		= $img_path . "ttd_notyet2.png";
							$ttd3 		= $img_path . "ttd_notyet2.png";

							if ($data_pkk[0]->aju_ttd1 == '1') {
								$ttd1 =  $img_path . "ttd_" . $masternotifikasi[0]->verifikasi1 . ".png";
							} else if ($data_pkk[0]->aju_ttd1 == '2') {
								$ttd1 = $img_path . "ttd_not.png";
							}
							if ($data_pkk[0]->aju_ttd2 == '1') {
								$ttd2 =  $img_path . "ttd_" . $masternotifikasi[0]->verifikasi2 . ".png";
							} else if ($data_pkk[0]->aju_ttd2 == '2') {
								$ttd2 = $img_path . "ttd_not.png";
							}
							if ($data_pkk[0]->aju_ttd3 == '1') {
								$ttd3 = $img_path . "ttd_" . $masternotifikasi[0]->disetujui1 . ".png";
							} else if ($data_pkk[0]->aju_ttd3 == '2') {
								$ttd3 = $img_path . "ttd_not.png";
							}
							?>
							<td style="text-align:center; width:23%;"> <?php echo '<img src="' . $ttdaju . '" height="70">'; ?> </td>
							<td style="text-align:center; width:2.5%;"></td>
							<td style="text-align:center; width:23%;"><?php echo '<img src="' . $ttd1 . '" height="70">'; ?></td>
							<td style="text-align:center; width:2%;"></td>
							<td style="text-align:center; width:24%;"><?php echo '<img src="' . $ttd2 . '" height="70">'; ?></td>
							<td style="text-align:center; width:2.5%;"></td>
							<td style="text-align:center; width:23%;"><?php echo '<img src="' . $ttd3 . '" height="70">'; ?></td>
						</tr>
						<tr>
							<td style="text-align:center; "><?= $data_pkk[0]->nama_ttd ?>
								<hr>
								</hr>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; "><?= $masternotifikasi[0]->namav1 ?>
								<hr>
								</hr>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; "><?= $masternotifikasi[0]->namav2 ?>
								<hr>
								</hr>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; "><?= $masternotifikasi[0]->namad1 ?>
								<hr>
								</hr>
							</td>
						</tr>
						<tr>
							<td style="text-align:center; vertical-align:top;"><i><?= $data_pkk[0]->jabatan ?></i></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;"><i><?= $masternotifikasi[0]->jabatanv1 ?></i></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;"><i><?= $masternotifikasi[0]->jabatanv2 ?></i></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;"><i><?= $masternotifikasi[0]->jabatand1 ?></i></td>
						</tr>
					</tbody>
				</table>

				<br><br>
				<div width="100%">
					<?php if (sessPenggunaId() == '1' || sessPenggunaId() != $data_pkk[0]->idPengaju) { ?>
						<?php if (sessPenggunaId() == '107') { ?>
							<button type="button" class="btn btn-primary float-right btn-submit" style="margin-left:12px; margin-top:12px;" id-pkk="<?= encrypt($data_pkk[0]->idPKK) ?>"> <i class="fas fa-check"></i> Submit </button>
						<?php } ?>
						<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-pkk="<?= encrypt($data_pkk[0]->idPKK) ?>"> <i class="fas fa-check"></i> Setujui </button>
						<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-pkk="<?= encrypt($data_pkk[0]->idPKK) ?>"> <i class="fas fa-times"></i> Tolak </button>
					<?php } ?>
					<a href="surat/print_page/pkk/<?= $data_pkk[0]->idPKK ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
					<?php if ($data_pkk[0]->lampiran != "") { ?>
						<a href="<?= $data_pkk[0]->lampiran ?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran Pengaju</a>
					<?php } ?>
					<?php if ($data_pkk[0]->lampiran_finance != "") { ?>
						<a href="<?= $data_pkk[0]->lampiran_finance ?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran Finance</a>
					<?php } ?>
					<?php if (isAdmin()): ?>
						<button type="button" onclick="openAdminEditModal('pkk', '<?= encrypt($data_pkk[0]->idPKK) ?>')" class="btn btn-warning float-right font-weight-bold text-dark" style="margin-left:12px; margin-top:12px;">
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
			const id_pkk = $(this).attr("id-pkk");

			Swal.fire({
				//title: approval + ' absensi?',
				title: 'Setujui Pengajuan Klaim Kas?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/pkk/1/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_pkk: id_pkk,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
						}
					})

				}

			})
		})
		var idPkk = $('#id_pkk').val();
		let ket_detail2 = [];
		let nominal2 = [];
		let tanggal2 = [];

		let itung_isi2 = $('#itung2').val();

		$(document).on('click', '.btn-submit', function() {
			var lampiran_finance = $('#lampiran_finance').val();
			for (let i = 1; i <= itung_isi2; i++) {
				if ($('#ket_detail2_' + i).val() != "") {
					tanggal2[i] = $('#tanggal2_' + i).val();
					ket_detail2[i] = $('#ket_detail2_' + i).val();
					nominal2[i] = $('#nominal2_' + i).val();

					// total[i]		= nominal[i]*qty[i];
					// nom[i] = $('#nominal_'+i).val();
				}
			}
			let itung2 = ket_detail2.length;
			console.log('' + nominal2);
			Swal.fire({
				title: 'Data telah tepat?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					console.log('' + idPkk);
					$.ajax({
						method: 'POST',
						url: 'surat/updateFromFinancePkk',
						dataType: 'JSON',
						data: {
							itung2: itung2,
							id_pkk: idPkk,
							tanggal2: tanggal2,
							ket_detail2: ket_detail2,
							nominal2: nominal2,
							lampiran_finance: lampiran_finance,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
						}
					})
				}
			})
		})

		$(document).on('click', '.btn-save', function() {
			const id_pkk = $(this).attr("id-pkk")
			Swal.fire({
				title: 'Setujui Pengajuan Klaim Kas?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/pkk/1/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_pkk: id_pkk,
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
			const id_pkk = $(this).attr("id-pkk")
			Swal.fire({
				title: 'Tolak Pengajuan Klaim Kas?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/pkk/2/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_pkk: id_pkk,
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