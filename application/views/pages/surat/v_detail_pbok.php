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

		.mytextarea {
			border: none;
			outline: none;
			min-height: 25px;
			width: 300px;
			height: auto;
		}
	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
		<div class="text-center">
			<h2>
				<font color='#000000' face='Times New Roman'>No : <?= $data_pbok[0]->kodePBOK ?></font>
			</h2>
			<input type="hidden" id="kodesurat" value="<?= $data_pbok[0]->kodePBOK ?>">
			<h5>
				<font color='#666666' face='Times New Roman'><?= mata_uang_icon($data_pbok[0]->mata_uang) ?></font>
			</h5>
		</div>
		<?php
		$ttd = "ttd_1";
		if (sessPenggunaId() == '107' || sessPenggunaId() == '106') {
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

				<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
					<tr>
						<td colspan="4" style="text-align:left">
							<font color='#000000'>Dengan ini saya mengajukan biaya sebagai berikut :</font>
						</td>
					</tr>
					<tr>
			</font>
			<td rowspan="10" width="7%"></td>
			<tr>
				<td width="18%" colspan="2">Nama </td>
				<td>:&nbsp;&nbsp; <?= $data_pbok[0]->nama ?> </td>
			</tr>
			<tr>
				<td colspan="2">Divisi </td>
				<td>:&nbsp;&nbsp; <?= $data_pbok[0]->jabatan ?></td>
			</tr>
			<tr>
				<td colspan="2">Rencana Tempat Pembelian </td>
				<td>:&nbsp;&nbsp; <?= $data_pbok[0]->alamat ?></td>
			</tr>

			<tr>
				<td width="30%" colspan="2">Nomor Rekening Pembayaran </td>
			<tr>
				<td rowspan="5" width="2.5%"></td>
				<td>Nama Bank </td>
				<td>:&nbsp;&nbsp; <?= $data_pbok[0]->bank ?></td>
			</tr>
			<tr>
				<td>Nomor Rekening </td>
				<td>:&nbsp;&nbsp; <?= $data_pbok[0]->rekening ?> </td>
			</tr>
			<tr>
				<td>Atas Nama </td>
				<td>:&nbsp;&nbsp; <?= $data_pbok[0]->ats_nama ?> </td>
			</tr>
			</tr>
			<tr>
				<td>
					<font color="white">i </font>
				</td>
			</tr>
			</tr>
			</table>

			<table id="kt_table_1" style="font-family:Times New Roman; color:black; font-size:15px" border="1" width="100%">
				<thead>
					<tr>
						<th hidden>Kode</th>
						<th style="text-align:center" bgcolor="#b7d5ac" width="5%"> No. </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> Keterangan </th>
						<th style="text-align:center" bgcolor="#b7d5ac" width="10%"> Estimasi Harga Satuan </th>
						<th style="text-align:center" bgcolor="#b7d5ac" width="5%"> Qty </th>
						<th style="text-align:center" bgcolor="#b7d5ac" width="10%"> Estimasi Harga Total </th>
						<th style="text-align:center" bgcolor="#b7d5ac" width="20%"> Jenis Persetujuan </th>
						<th style="text-align:center" bgcolor="#b7d5ac" width="15%"> Catatan </th>
					</tr>
				</thead>
				<tbody>
					<?php
					$x = 1;
					$nom = 0;
					$xtampil = 0;
					foreach ($detail_pbok as $row) {
						$x = $x + 1;
						$x2 = $x - 1;
						$nom = (int) $row->ttl + $nom;
						$nom_pjk = $data_pbok[0]->nml_pajak;
						if ($data_pbok[0]->pembayar_pajak == "1" || ((empty($data_pbok[0]->pembayar_pajak) || $data_pbok[0]->pembayar_pajak == 'undefined') && $nom_pjk > 0)) {
							$grnd_ttl = $nom - $nom_pjk;
						} else if ($data_pbok[0]->pembayar_pajak == "2") {
							$grnd_ttl = $nom + $nom_pjk;
						} else {
							$grnd_ttl = $nom;
						}
					?>
						<tr>
							<!-- <td class="tgl" style="text-align:center;"><?= $row->tgl ?></td> -->
							<td class="id" hidden>&nbsp;<?= encrypt($row->id) ?>&nbsp;</td>
							<td>
								<?php
								if ($row->qty == 0) {
									echo "";
								} else {
									$xtampil = $xtampil + 1;
									echo $xtampil;
								}
								?>
							</td>
							<td class="ket">&nbsp;<?= $row->keterangan ?>&nbsp;</td>
							<td class="nom">
								<?php
								// Cek mata uang per item, jika kosong gunakan global
								$mata_uang_item = isset($row->mata_uang) && $row->mata_uang ? $row->mata_uang : $data_pbok[0]->kodeMataUang;

								if ($mata_uang_item == 1) {
									$mata = 'Rp. ';
								} else if ($mata_uang_item == 2 || $mata_uang_item == 4) {
									$mata = '$ ';
								} else if ($mata_uang_item == 3) {
									$mata = 'RM ';
								} else if ($mata_uang_item == 5) {
									$mata = '₹ ';
								} else {
									$mata = 'Rp. '; // default
								}

								if ($row->est == 0) {
									echo "";
								} else { ?>
									<label class="mylabel" style="text-align:left">&nbsp;<?= $mata ?> </label>
									<label class="mylabel" style="text-align:right"><?= number_format($row->est, 0, ",", ".") ?>,-&nbsp;</label>
								<?php }	?>

							</td>
							<td class="ket" style="text-align:center">
								<?php
								if ($row->qty == 0) {
									echo "";
								} else {
								?>
									<?= $row->qty ?>
								<?php }	?>
							</td>
							<td class="ket">
								<?php
								if ($row->ttl == 0) {
									echo "";
								} else {
								?>
									<label class="mylabel" style="text-align:left">&nbsp;<?= $mata ?> </label>
									<label class="mylabel" style="text-align:right"><?= number_format($row->ttl, 0, ",", ".") ?>,-&nbsp;</label>
								<?php }	?>
							</td>
							<td>
								<input <?php if ($pengguna[0]->pengguna_id != 23) { ?> disabled <?php } ?> type="checkbox" name="approvall" onclick="onlyOne(this)" value="Disetujui" checked class="mr-1" id="<?= 'approvall_' . $x ?>">Disetujui
								<input <?php if ($pengguna[0]->pengguna_id != 23) { ?> disabled <?php } ?> type="checkbox" name="approvall" onclick="onlyOne(this)" value="Ditolak" class="mr-1" id="<?= 'approvall_' . $x ?>">Ditolak
								<input <?php if ($pengguna[0]->pengguna_id != 23) { ?> disabled <?php } ?> type="checkbox" name="approvall" onclick="onlyOne(this)" value="Ditolak" class="mr-1" id="<?= 'approvall_' . $x ?>">Revisi
							</td>
							<td><textarea <?php if (($pengguna[0]->pengguna_id == 23)) { ?> <?php } elseif (($pengguna[0]->pengguna_id == 107)) { ?> <?php } else { ?> disabled <?php } ?> type="text" name="catatan" id="catatan" class="mytextarea" placeholder="&nbsp;Inputkan Catatan Keterangan" onblur="fcatatan(<?= '\'' . encrypt($data_pbok[0]->idPbok) . '\'' ?>,<?= '\'' . encrypt($row->id) . '\'' ?>);"><?= $row->catatan ?></textarea></td>

						</tr>
					<?php } ?>
					<?php for ($kosong = $x; $kosong <= 15; $kosong++) { ?>
						<tr>
							<td>
								<font color="white">i </font>
							</td>
							<td> </td>
							<td> </td>
							<td> </td>
							<td> </td>
							<td> </td>
							<td> </td>
						</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<th bgcolor="#b7d5ac" colspan="4" style="text-align:center">GRAND TOTAL </th>
						<td bgcolor="#b7d5ac">
							<label class="mylabel" style="text-align:left">&nbsp;<?= $mata ?> </label>
							<label class="mylabel" style="text-align:right"><?= number_format($grnd_ttl, 0, ",", ".") ?>,-&nbsp;</label>
							<input type="hidden" id="jml_nom" value="<?= $nom ?>">
						</td>
						<td bgcolor="#b7d5ac"></td>
						<td bgcolor="#b7d5ac"></td>
					</tr>
				</tfoot>
				<tfoot>
					<tr>
						<th bgcolor="#b7d5ac" colspan="4" style="text-align:center">TOTAL </th>
						<th bgcolor="#b7d5ac" style="text-align:right">
							<label class="mylabel" style="text-align:left">&nbsp;<?= $mata ?> </label>
							<label class="mylabel" style="text-align:left"><?= number_format($nom, 0, ",", ".") ?>,-&nbsp;</label>
							<input type="hidden" id="jml_nom" value="<?= $nom ?>">
						</th>
						<th bgcolor="#b7d5ac"></th>
						<th bgcolor="#b7d5ac"></th>
					</tr>
				</tfoot>
				<tfoot>
					<tr>
						<th colspan="7" align="left">
							<?= form_open('#', array('id' => 'a-pbok-form', 'autocomplete' => 'off')); ?>
							<table border="0" style="width:100%">
								<tr>
									<input class="no-outline" type="hidden" id="id_pbok" name="id_pbok" value="<?= $data_pbok[0]->idPbok ?>">
									<td>Tipe dan Tarif Pajak</td>
									<td>&nbsp;: </td>
									<td colspan="4">
										<div class="no-outline">
											<?php
											if ($data_pbok[0]->trf_pajak != "") {
												if ($data_pbok[0]->trf_pajak == 0.04) {
													echo "<span>Pph pasal 23 (Tanpa NPWP) 4%</span>";
												} else if ($data_pbok[0]->trf_pajak == 0.02) {
													echo "<span>Pph pasal 23 (Dengan NPWP) 2%</span>";
												} else {
													echo "Tanpa Pajak";
												}
											} else {
											?>
												<select id="trf_pajak" name="trf_pajak" class="form-control" <?php if ($pengguna[0]->pengguna_id != 107) { ?>
													disabled
													<?php } ?> id="exampleFormControlSelect1">
													<option value="0">Tanpa Pajak</option>
													<option value="0.04">Pph pasal 23 (Tanpa NPWP) 4%</option>
													<option value="0.02">Pph pasal 23 (Dengan NPWP) 2%</option>
												</select>
											<?php } ?>
										</div>
									</td>
								</tr>
								<tr>
									<td>Nominal Pajak</td>
									<td>&nbsp;: </td>
									<td>
										<label class="mylabel" style="text-align:left">&nbsp;<?= $mata ?> <?= number_format($data_pbok[0]->nml_pajak, 0, ",", ".") ?>,-&nbsp;</label>
										<input <?php if ($pengguna[0]->pengguna_id != 107) { ?>
											disabled
											<?php } ?> type="number" id="nml_pajak" name="nml_pajak">
									</td>
								</tr>
								<tr>
									<td>Pajak dibayarkan oleh</td>
									<td>&nbsp;: </td>
									<?php
									if ($data_pbok[0]->pembayar_pajak != null) { ?>

										<?php
										if ($data_pbok[0]->pembayar_pajak == 1) {
											echo "Vendor";
										} else if ($data_pbok[0]->pembayar_pajak == 2) {
											echo "PT Visi Yosindo Medikal";
										} else {
											echo "-";
										}
										?>
										</td>
									<?php } else { ?>
										<td>
											<input <?php if ($pengguna[0]->pengguna_id != 107) { ?>
												disabled
												<?php } ?> type="radio" id="pembayar_pajak1" name="pembayar_pajak" value="1" />
											<label>Vendor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input class="active"
												<?php if ($pengguna[0]->pengguna_id != 107) { ?>
												disabled
												<?php } ?>
												type="radio" id="pembayar_pajak2" name="pembayar_pajak" value="2" />
											<label>PT. Visi Yosindo Medikal</label>
										</td>

									<?php } ?>
								</tr>
							</table>
							<?= form_close(); ?>
						</th>

					</tr>
				</tfoot>
			</table>
			<br>
			<table border="0" style="width:100%; font-family:Times New Roman; color:black; font-size:15px;">
				<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
							<?php
							$dt_pengajuan	= strtotime($data_pbok[0]->tgl_pengajuan);
							$tgl_pengajuan = date("d", $dt_pengajuan) . " - " . date("m", $dt_pengajuan) . " - " . date("Y", $dt_pengajuan);
							?>
							<?= $data_pbok[0]->kota_aju ?>, <?= $tgl_pengajuan ?>
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
						$ttdaju		= $img_path . "ttd_" . $data_pbok[0]->idPengaju . ".png";
						$ttd1 		= $img_path . "ttd_notyet2.png";
						$ttd2 		= $img_path . "ttd_notyet2.png";
						$ttd3 		= $img_path . "ttd_notyet2.png";

						if ($data_pbok[0]->aju_ttd1 == '1') {
							$ttd1 = $img_path . "ttd_107.png";
						} else if ($data_pbok[0]->aju_ttd1 == '2') {
							$ttd1 = $img_path . "ttd_not.png";
						}
						if ($data_pbok[0]->aju_ttd2 == '1') {
							$ttd2 = $img_path . "ttd_33.png";
						} else if ($data_pbok[0]->aju_ttd2 == '2') {
							$ttd2 = $img_path . "ttd_not.png";
						}
						if ($data_pbok[0]->aju_ttd3 == '1') {
							$ttd3 = $img_path . "ttd_23.png";
						} else if ($data_pbok[0]->aju_ttd3 == '2') {
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
						<td style="text-align:center; "><?= $data_pbok[0]->nama_ttd ?>
							<hr>
							</hr>
						</td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Dirangga Madali
							<hr>
							</hr>
						</td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Yolanda Pratiwi
							<hr>
							</hr>
						</td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Meilina Safitri
							<hr>
							</hr>
						</td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_pbok[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Head of Accounting & Tax</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>General Manager</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corp Planning & Bussinees Management</i></td>
					</tr>
				</tbody>
			</table>
			<br>
			<?php if (sessPenggunaId() != '81' || sessPenggunaId() == '1') { ?>
				<table id="kt_table_1" style="font-family:Times New Roman; color:black; font-size:15px" border="0" width="100%">
					<tr>
					<tr>
						<th bgcolor="#b7d5ac" colspan="2" style="text-align:center">Catatan Senior Accounting & Finance </th>
						<td bgcolor="#b7d5ac" colspan="3">:&nbsp;&nbsp; </td>
					</tr>
					</tr>
				</table>
			<?php } ?>
			<br>
			<?php if (sessPenggunaId() == '107' || sessPenggunaId() == '106') { ?>
				<table id="kt_table_1" style="font-family:Times New Roman; color:black; font-size:15px" border="0" width="100%">
					<tr>
						<th bgcolor="" colspan="2" style="text-align:center">Catatan Finance </th>
						<td bgcolor="" colspan="3"><input type="text" name="catatan" id="catatan" placeholder=" &nbsp;Inputkan catatan anda" required></td>
					</tr>
				</table>
			<?php } ?>
			<br>


			<br><br>
			<div width="100%">
				<!-- <?php if (sessPenggunaId() == '1' || sessPenggunaId() != $data_pbok[0]->idPengaju || sessPenggunaId() == '23') { ?>
                <?php if (sessPenggunaId() == '107') { ?>
            	    <button type="button" class="btn btn-primary float-right btn-submit" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-check"></i> Submit </button>
            	<?php } ?>
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-times"></i> Tolak </button>
				<button type="button" class="btn btn-dark float-right btn-dark" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-times"></i> Revisi(soon) </button>
            <?php } ?> -->


				<!-- Logic TTD Accounting -->
				<?php if (sessPenggunaId() == '107') { ?>
					<button type="button" class="btn btn-primary float-right btn-submit" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-check"></i> Submit </button>

					<?php if ($data_pbok[0]->aju_ttd1 == '') { ?>

						<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-check"></i> Setujui </button>
						<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-times"></i> Tolak </button>
						<button type="button" class="btn btn-dark float-right btn-dark" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-times"></i> Revisi(soon) </button>
					<?php } ?>
				<?php } ?>

				<!-- Logic TTD GM -->
				<?php if (sessPenggunaId() == '33') { ?>
					<?php if ($data_pbok[0]->aju_ttd2 == '') { ?>

						<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-check"></i> Setujui </button>
						<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-times"></i> Tolak </button>
						<button type="button" class="btn btn-dark float-right btn-dark" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-times"></i> Revisi(soon) </button>
					<?php } ?>
				<?php } ?>

				<!-- Logic TTD Dir Bisnis -->
				<?php if (sessPenggunaId() == '23') { ?>
					<?php if ($data_pbok[0]->aju_ttd3 == '') { ?>

						<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-check"></i> Setujui </button>
						<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-times"></i> Tolak </button>
						<button type="button" class="btn btn-dark float-right btn-dark" style="margin-left:12px; margin-top:12px;" id-pbok="<?= encrypt($data_pbok[0]->idPbok) ?>"> <i class="fas fa-times"></i> Revisi(soon) </button>
					<?php } ?>
				<?php } ?>

				<a href="surat/print_page/PBOK2/<?= $data_pbok[0]->idPbok ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak Detail </a>
				<a href="surat/print_page/PBOK/<?= $data_pbok[0]->idPbok ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
				<?php if ($data_pbok[0]->lampiran != "") { ?>
					<a href="<?= $data_pbok[0]->lampiran ?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran </a>
				<?php } ?>
				<?php if (isAdmin()): ?>
					<button type="button" onclick="openAdminEditModal('pbok', '<?= encrypt($data_pbok[0]->idPbok) ?>')" class="btn btn-warning float-right font-weight-bold text-dark" style="margin-left:12px; margin-top:12px;">
						<i class="fas fa-user-shield mr-1"></i> Edit Data (Admin)
					</button>
				<?php endif; ?>
				<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" style="margin-top:12px;" data-dismiss="modal">Kembali</button>
				<br>
			</div>
		</div>
		<br>
		<br>
		Note : Mohon Verifikasi terlebih dahulu sebelum Setujui/Tolak, Karena tidak dapat diubah
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		var level_ttd = $('#level_ttd').val();

		$(document).on('click', '.btn-approval', function() {
			const id_pbok = $(this).attr("id-pbok");

			Swal.fire({
				//title: approval + ' absensi?',
				title: 'Setujui Pengajuan PBOK?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/pbok/4/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_pbok: id_pbok,
							kodesurat: $('#kodesurat').val(),
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
			var trf_pajak = $('#trf_pajak').val();
			var nml_pajak = $('#nml_pajak').val();
			var pembayar_pajak = jQuery("input[name=pembayar_pajak]:checked").val();



			var idPbok = $('#id_pbok').val();
			// var level_ttd = $('#level_ttd').val();
			console.log(pembayar_pajak);
			Swal.fire({
				title: 'Data telah tepat?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					console.log('' + nml_pajak);
					$.ajax({
						method: 'POST',
						url: 'surat/updateFromFinance/' + idPbok + '/' + trf_pajak + '/' + nml_pajak + '/' + pembayar_pajak,
						dataType: 'JSON',
						data: {
							trf_pajak: trf_pajak,
							nml_pajak: nml_pajak,
							pembayar_pajak: pembayar_pajak,
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
			const id_pbok = $(this).attr("id-pbok")
			Swal.fire({
				title: 'Setujui Pengajuan PBOK?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/pbok/4/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_pbok: id_pbok,
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
			const id_pbok = $(this).attr("id-pbok")
			Swal.fire({
				title: 'Tolak Pengajuan PBOK?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/pbok/2/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_pbok: id_pbok,
							kodesurat: $('#kodesurat').val(),
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
						}
					})
				}
			})
		})

		// 		$(document).on('onblur', '.catatan', function(id_pbok,noid) {
		// 					const catatan = $(this).attr("catatan")
		// 					Swal.fire({
		// 							title: 'Tambah Catatan Keterangan ?',
		// 							icon: 'question',
		// 							showCancelButton: true,
		// 							confirmButtonText: 'Ya',
		// 							cancelButtonText: 'Tidak'
		// 					}).then(function(result) {
		// 							if (result.value) {
		// 									$.ajax({
		// 											method: 'POST',
		// 											url: 'surat/catatan_update/pbok/',
		// 											dataType: 'JSON',
		// 											data: {
		// 													idpbok: id_pbok,
		// // 					id: noid,
		// // 					dcatatan: catatan,
		// // 					csrf_token: token
		// 											},
		// 											success: function(resp) {
		// 													handleResponse(resp)
		// 											}
		// 									})
		// 							}
		// 					})
		//     })
	})

	function fcatatan(id_pbok, noid) {
		var catatan = document.getElementById('catatan').value;
		$.ajax({
			method: 'POST',
			url: 'surat/catatan_update/pbok/',
			dataType: 'json',
			data: {
				idpbok: id_pbok,
				id: noid,
				dcatatan: catatan,
				csrf_token: token
			},
			success: function(resp) {
				handleResponse(resp)
			}
		})
	}

	function goBack() {
		window.history.back();
	}
</script>