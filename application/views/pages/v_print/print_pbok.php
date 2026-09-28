<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
	<base href="<?= base_url() ?>">
	<!-- Basic -->
	<meta charset="UTF-8">

	<title> Pengajuan Biaya Operational Kantor | <?= $this->config->item('apps_name') ?></title>
	<meta name="keywords" content="Sistem Informasi" />
	<meta name="description" content="<?= $this->config->item('apps_name') ?>">

	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<!-- includes_css.php -->
	<!-- Web Fonts  -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:100,300,400,600,700,800,900" rel="stylesheet" type="text/css">

	<!-- Vendor CSS -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/animate/animate.compat.css">

	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/font-awesome/css/all.min.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/boxicons/css/boxicons.min.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/magnific-popup/magnific-popup.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

	<!-- Specific Page Vendor CSS -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/jquery-ui/jquery-ui.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/jquery-ui/jquery-ui.theme.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/select2/css/select2.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/select2-bootstrap-theme/select2-bootstrap.min.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/dropzone/basic.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/dropzone/dropzone.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap-markdown/css/bootstrap-markdown.min.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/pnotify/pnotify.custom.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/datatables/media/css/dataTables.bootstrap4.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/simple-line-icons/css/simple-line-icons.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>js/sweetalert2/sweetalert2.min.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>js/croppie/croppie.css" />

	<!--(remove-empty-lines-end)-->

	<!-- Theme CSS -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>css/theme.css" />



	<!-- Theme Layout -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>css/layouts/modern.css" />
	<!--(remove-empty-lines-end)-->



	<!-- Theme Custom CSS -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>css/custom.css">
	<link rel="shortcut icon" href="<?= base_url('assets/') ?>img/favicon.png" />
	<!-- Head Libs -->

	<style>
		html.modern html,
		html.modern body {
			background: white !important;
			color: #d41c0f;
		}

		.page-header {
			background: #1D2127 !important;
		}

		.header .logo-container {
			background-image: none;
			background-color: #1D2127;
			border-bottom-color: #161a1e;
			border-top-color: #1D2127;
			/* background-color: #1D2127; */

		}

		.modal-header {
			padding: 0px 5px 5px 5px;
		}

		@media only screen and (min-width: 768px) {
			html.modern .header:not(.header-nav-menu) .logo {
				line-height: 0px;
				padding: 5px 20px 0 15px;
				font-size: 18px
			}
		}

		@media (max-width: 767px) {
			html.modern .header .logo-container .logo {
				margin-top: 0px;
				line-height: 0px;
				font-size: 18px
			}
		}

		hr {
			display: block;
			margin-top: 0em;
			margin-bottom: 0em;
			margin-left: auto;
			margin-right: auto;
			border-top: 2px solid black;
		}

		.mod-u {
			text-decoration-line: underline;
			text-underline-position: under;
			text-decoration-style: solid;
			text-decoration-color: black;
			text-decoration-thickness: 5px;
		}

		.mylabel {
			border: 1px solid blue;
			display: table-cell;
			width: 100%;
		}

		.mylabel2 {
			border: 1px solid blue;
			display: table-cell;
			width: 20%;
		}
	</style>
	<!-- end includes_css.php -->


	<!-- Head Libs -->
	<script src="<?= base_url('assets/') ?>vendor/modernizr/modernizr.js"></script>

</head>

<body style="background-color:white; font-color:black;">
	<!-- Menu Bar Link Office (Hanya Tampil di Layar / Preview, Sembunyi Saat Print) -->
	<div class="no-print" style="background: #f8f9fa; padding: 12px 20px; border-bottom: 1px solid #dee2e6; margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between;">
		<div>
			<a href="<?= base_url('surat/show/list_pbok') ?>" class="btn btn-sm btn-secondary" style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 600;">
				<i class="fas fa-arrow-left"></i> &nbsp;Kembali ke Menu Office (PBOK)
			</a>
		</div>
		<div style="display: flex; gap: 8px;">
			<?php if (!empty($data_pbok[0]->idPbok)) : ?>
				<a href="<?= base_url('surat/show/detail_surat/pbok/' . $data_pbok[0]->idPbok . '/1') ?>" target="_blank" class="btn btn-sm btn-info" style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 600;">
					<i class="fas fa-external-link-alt"></i> &nbsp;Link Office (Detail Surat)
				</a>
			<?php endif; ?>
			<button type="button" onclick="window.print()" class="btn btn-sm btn-primary" style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 600;">
				<i class="fas fa-print"></i> &nbsp;Cetak / Print Laporan
			</button>
		</div>
	</div>

	<section class="body" style="padding-top:0px;">
		<img src="assets/img/kop_baru.jpg" alt="Logo" style="width: 100%;" />
		<hr>
		</hr>
		<hr>
		</hr>
		<div class="inner-wrapper" style="padding-top: 0px">
			<section role="main" class="content-body content-body-modern" style="padding-top:13px; padding-bottom:0px;">
				<!-- start: page -->

				<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
					<tr>
						<th width="31%"></th>
						<th style="height:27px; text-align:center; vertical-align:top;">
							<font color='#000000'> PENGAJUAN BIAYA
								<hr>
								</hr>
								<hr>
								</hr>
						</th>
						<th width="31%"></th>
					</tr>
					<tr>
						<th colspan="3" style="text-align:center">
							<font color='#000000'> No : <?= $data_pbok[0]->kodePBOK ?>
						</th>
					</tr>
					<tr>
						<td>
							<font color="white">i </font>
						</td>
					</tr>
				</table>

				<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
					<tr>
						<td colspan="3" style="text-align:left">
							<font color='#000000'> Dengan ini saya mengajukan biaya sebagai berikut :
						</td>
					</tr>
					<tr>
						<!-- <td rowspan="8" width="7%"></td> -->
					<tr>
						<td colspan="2">
							<font color='#000000'> Nama
						</td>
						<td>
							<font color='#000000'> :&nbsp;&nbsp; <?= $data_pbok[0]->nama ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<font color='#000000'> Jabatan
						</td>
						<td>
							<font color='#000000'> :&nbsp;&nbsp; <?= $data_pbok[0]->jabatan ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<font color='#000000'> Rencana Tempat Pembelian
						</td>
						<td>
							<font color='#000000'> :&nbsp;&nbsp; <?= $data_pbok[0]->alamat ?>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<font color='#000000'>Nomor Rekening Pembayaran
						</td>
					<tr>
						<td rowspan="4" width="4%"></td>
						<td width="33%">
							<font color='#000000'> Nomor Rekening
						</td>
						<td>
							<font color='#000000'> :&nbsp;&nbsp; <?= $data_pbok[0]->rekening ?>
						</td>
					</tr>
					<tr>
						<td>
							<font color='#000000'> Nama Bank
						</td>
						<td>
							<font color='#000000'> :&nbsp;&nbsp; <?= $data_pbok[0]->bank ?>
						</td>
					</tr>
					<tr>
						<td>
							<font color='#000000'> Atas Nama
						</td>
						<td>
							<font color='#000000'> :&nbsp;&nbsp; <?= $data_pbok[0]->ats_nama ?>
						</td>
					</tr>
					</tr>
					<tr>
						<td>
							<font color="white">i </font>
						</td>
					</tr>
					</tr>
				</table>
			</section>

			<section role="main" class="body" style="padding-top:0px;">
				<div class="table-responsive">
					<table id="kt_table_1" style="font-family:Times New Roman; font-size:15px" border="1" width="100%">
						<thead>
							<tr>
								<th style="text-align:center" bgcolor="#b7d5ac" width="5%">
									<font color='#000000'> No
								</th>
								<th style="text-align:center" bgcolor="#b7d5ac" width="40%">
									<font color='#000000'> Keterangan
								</th>
								<th style="text-align:center" bgcolor="#b7d5ac" width="20%">
									<font color='#000000'> Estimasi Harga Satuan
								</th>
								<th style="text-align:center" bgcolor="#b7d5ac" width="10%">
									<font color='#000000'> Qty
								</th>
								<th style="text-align:center" bgcolor="#b7d5ac" width="25%">
									<font color='#000000'> Estimasi Harga Total
								</th>
							</tr>
						</thead>
						<tbody>

							<?php
							$x = 1;
							$nom = 0;
							$xtampil = 0;
							foreach ($detail as $row) {
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
									<td style="text-align:center">
										<font color='#000000'>
											<?php
											if ($row->qty == 0) {
												echo "";
											} else {
												$xtampil = $xtampil + 1;
												echo $xtampil;
											}
											?>
									</td>
									<td class="ket">
										<font color='#000000'>&nbsp;<?= $row->keterangan ?>&nbsp;
									</td>
									<td class="nom" style="text-align:right">
										<?php

										if ($data_pbok[0]->kodeMataUang == 1) {
											$mata = 'Rp. ';
										} else {
											$mata = '$ ';
										}


										if ($row->est == 0) {
											echo "";
										} else { ?>
											<label class="mylabel" style="text-align:right">
												<font color='#000000'>&nbsp;<?= $mata ?>
											</label>
											<label class="mylabel" style="text-align:right">
												<font color='#000000'><?= number_format($row->est, 0, ",", ".") ?>,-&nbsp;
											</label>
										<?php }	?>
									</td>
									<td class="ket" style="text-align:center">
										<font color='#000000'>
											<?php
											if ($row->qty == 0) {
												echo "";
											} else {
											?>
												<?= $row->qty ?>
											<?php }	?>
									</td>
									<td class="ket" style="text-align:right">
										<font color='#000000'>
											<?php
											if ($row->ttl == 0) {
												echo "";
											} else {
											?>
												<label class="mylabel" style="text-align:right">
													<font color='#000000'>&nbsp;<?= $mata ?>
												</label>
												<label class="mylabel" style="text-align:right">
													<font color='#000000'><?= number_format($row->ttl, 0, ",", ".") ?>,-&nbsp;
												</label>
											<?php }	?>
									</td>
								</tr>
							<?php } ?>
							<?php for ($kosong = $x; $kosong <= 10; $kosong++) { ?>
								<tr>
									<td>
										<font color="white">i </font>
									</td>
									<td> </td>
									<td> </td>
									<td> </td>
									<td> </td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th bgcolor="#b7d5ac" colspan="3" style="text-align:center">
									<font color='#000000'>TOTAL
								</th>
								<td bgcolor="#b7d5ac"></td>
								<th bgcolor="#b7d5ac" style="text-align:right">
									<label class="mylabel" style="text-align:right">
										<font color='#000000'>&nbsp;<?= $mata ?>
									</label>
									<label class="mylabel" style="text-align:right">
										<font color='#000000'><?= number_format($nom, 0, ",", ".") ?>,-&nbsp;
									</label>
									<input type="hidden" id="jml_nom" value="<?= $nom ?>">
								</th>
							</tr>
						</tfoot>
						<tfoot>
							<tr>
								<td colspan="3">&nbsp;<font color='#000000'>Tipe dan Tarif Pajak</td>
								<td>
									<font color='#000000'>&nbsp;:
								</td>
								<td>
									<font color='#000000'>
										<div class="no-outline">
											<?php
											if ($data_pbok[0]->trf_pajak != "") {
												if ($data_pbok[0]->trf_pajak == 0.04) {
													echo "&nbsp;Pph pasal 23 (Tanpa NPWP) 4%";
												} else if ($data_pbok[0]->trf_pajak == 0.02) {
													echo "&nbsp;Pph pasal 23 (Tanpa NPWP) 2%";
												} else {
													echo "&nbsp;-";
												}
											} else {
												echo "&nbsp;-";
											}
											?>
										</div>
								</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;<font color='#000000'>Nominal Pajak</td>
								<td>
									<font color='#000000'>&nbsp;:
								</td>
								<td style="text-align:right">
									<label class="mylabel" style="text-align:right">
										<font color='#000000'>&nbsp;<?= $mata ?>
									</label>
									<label class="mylabel" style="text-align:right">
										<font color='#000000'><?= number_format($nom_pjk, 0, ",", ".") ?>,-&nbsp;
									</label>
									<input type="hidden" id="jml_nom" value="<?= $nom ?>">
								</td>
							</tr>
							<tr id="result_tr" style="display: none;">
								<td colspan="3">&nbsp;<font color='#000000'>Pajak dibayarkan oleh</td>
								<td>
									<font color='#000000'>&nbsp;:
								</td>
								<td>
									<font color='#000000'>
										<div class="no-outline">
											<?php
											if ($data_pbok[0]->pembayar_pajak == 1) {
												echo "&nbsp;vendor";
											} else if ($data_pbok[0]->pembayar_pajak == 2) {
												echo "&nbsp;PT Visi Yosindo Medikal";
											} else {
												echo "&nbsp;-";
											}
											?>
										</div>
								</td>
							</tr>
							<tr>
								<th bgcolor="#b7d5ac" colspan="3" style="text-align:center">
									<font color='#000000'>GRAND TOTAL
								</th>
								<th bgcolor="#b7d5ac" style="text-align:right"> </th>
								<th bgcolor="#b7d5ac" style="text-align:right">
									<font color='#000000'>&nbsp;<?= $mata ?> <?= number_format($grnd_ttl, 0, ",", ".") ?>,-&nbsp;
										<input type="hidden" id="jml_nom" value="<?= $nom ?>">
								</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</section>
			<br>
			<section role="main" class="content-body content-body-modern" style="padding-top: 0px;">
				<table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
					<tbody>
						<tr style="height: 35px;">
							<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
								<?php
								$dt_pengajuan	= strtotime($data_pbok[0]->tgl_pengajuan);
								$tgl_pengajuan = date("d", $dt_pengajuan) . " - " . date("m", $dt_pengajuan) . " - " . date("Y", $dt_pengajuan);
								?>
								<font color='#000000'> <?= $data_pbok[0]->kota_aju ?>, <?= $tgl_pengajuan ?>
							</td>
						</tr>
						<tr style="height: 18px;">
							<td style="text-align:center; width:25.5%;" colspan="2">
								<font color='#000000'> Diajukan Oleh,
							</td>
							<td style="text-align:center; width:49%;" colspan="3">
								<font color='#000000'> Diverifikasi Oleh,
							</td>
							<td style="text-align:center; width:25.5%;" colspan="2">
								<font color='#000000'> Disetujui Oleh,
							</td>
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
							<td style="text-align:center; width:23%; height:60px;"> <?php echo '<img src="' . $ttdaju . '" height="75">'; ?> </td>
							<td style="text-align:center; width:2.5%;"></td>
							<td style="text-align:center; width:23%;"><?php echo '<img src="' . $ttd1 . '" height="75">'; ?></td>
							<td style="text-align:center; width:2%;"></td>
							<td style="text-align:center; width:24%;"><?php echo '<img src="' . $ttd2 . '" height="75">'; ?></td>
							<td style="text-align:center; width:2.5%;"></td>
							<td style="text-align:center; width:23%;"><?php echo '<img src="' . $ttd3 . '" height="75">'; ?></td>
						</tr>
						<tr>
							<td style="text-align:center; ">
								<font color='#000000'> <?= $data_pbok[0]->nama_ttd ?>
									<hr>
									</hr>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; ">
								<font color='#000000'> Dirangga Madali
									<hr>
									</hr>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; ">
								<font color='#000000'> Yolanda Pratiwi
									<hr>
									</hr>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; ">
								<font color='#000000'> Meilina Safitri
									<hr>
									</hr>
							</td>
						</tr>
						<tr>
							<td style="text-align:center; vertical-align:top;">
								<font color='#000000'> <i><?= $data_pbok[0]->jabatan ?></i>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;">
								<font color='#000000'> <i>Head of Accounting and Tax</i>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;">
								<font color='#000000'> <i>General Manager</i>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;">
								<font color='#000000'> <i>Director of Corp Planning & Bussinees Management</i>
							</td>
						</tr>
					</tbody>
				</table>

				<script>
					window.onload = function() {
						window.print();
					}
				</script>
				<!-- end: page -->
			</section>
			<!-- end includes_js.php -->
			<!-- Vendor -->
			<script src="<?= base_url('assets/') ?>vendor/jquery/jquery.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/jquery-cookie/jquery.cookie.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/popper/umd/popper.min.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/common/common.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/nanoscroller/nanoscroller.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/magnific-popup/jquery.magnific-popup.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/jquery-placeholder/jquery.placeholder.js"></script>

			<!-- Specific Page Vendor -->
			<script src="<?= base_url('assets/') ?>vendor/jquery-ui/jquery-ui.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/jquery-validation/jquery.validate.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/select2/js/select2.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/dropzone/dropzone.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/pnotify/pnotify.custom.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/datatables/media/js/jquery.dataTables.min.js"></script>
			<script src="<?= base_url('assets/') ?>vendor/datatables/media/js/dataTables.bootstrap4.min.js"></script>
			<script src="<?= base_url('assets/') ?>js/global.js"></script>
			<script src="<?= base_url('assets/') ?>js/jquery.mask.min.js"></script>
			<script src="<?= base_url('assets/') ?>js/table2excel.min.js"></script>
			<script src="<?= base_url('assets/') ?>/js/sweetalert2/sweetalert2.min.js"></script>
			<script src="<?= base_url('assets/') ?>/js/chart/chart.min.js"></script>
			<script src="<?= base_url('assets/') ?>/js/ckeditor/ckeditor.js"></script>
			<script src="<?= base_url('assets/') ?>/js/croppie/croppie.js"></script>

			<!--(remove-empty-lines-end)-->

			<!-- Theme Base, Components and Settings -->
			<script src="<?= base_url('assets/') ?>js/theme.js"></script>


			<!-- Theme Initialization Files -->
			<script src="<?= base_url('assets/') ?>js/theme.init.js"></script>

			<input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

			<script>
				let token = $('input[name=token]').val()
				// Maintain Scroll Position
				if (typeof localStorage !== 'undefined') {
					if (localStorage.getItem('sidebar-left-position') !== null) {
						var initialPosition = localStorage.getItem('sidebar-left-position'),
							sidebarLeft = document.querySelector('#sidebar-left .nano-content');

						sidebarLeft.scrollTop = initialPosition;
					}
				}
			</script>
		</div>
		<!-- includes_js.php -->
		<img src="assets/img/kop_surat_bawah.jpg" alt="Logo" style="width: 100%;" />
	</section>
</body>

</html>