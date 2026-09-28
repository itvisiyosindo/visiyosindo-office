<header class="page-header">
	<h2><i class="icons fa fa-file-invoice-dollar"></i>&nbsp;<?= $page_title ?></h2>
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
		<div class="table-responsive">
			<div class="text-center mt-0">
				<h2>
					<font color='#000000' face='Times New Roman'>Approval Faktur Pajak</font>
				</h2>
				<h4>
					<font color='#000000' face='Times New Roman'>No :&nbsp;&nbsp;<?= $data_pajak[0]->kode ?></font>
				</h4>
			</div>
			<font color='#000000'>
				<?php

				$ttd = "ttd_1";
				if ((sessPenggunaId() == 33)) {
					$ttd = 'ttd_1';
				} else if ((sessPenggunaId() == 64)) {
					$ttd = 'ttd_2';
				} else if ((sessPenggunaId() == 23)) {
					$ttd = 'ttd_3';
				} else if ((sessPenggunaId() == 54)) {
					$ttd = 'ttd_4';
				}
				?>
				<input type="hidden" id="level_ttd" value="<?= $ttd ?>">
				<?php $id = $data_pajak[0]->idGc ?>
				<input type="hidden" name="id" id="id" value="<?= $data_pajak[0]->idGc ?>">



				<?= form_open('surat_new/updateApprovalEks', array('id' => 'main-form', 'autocomplete' => 'off')); ?>

				<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">

					<tr>
						<td colspan="3" style="text-align:left">
						</td>
					</tr>

					<tr>
			</font>
			<td rowspan="12" width="5%"></td>

			</tr>



			<tr>

			<tr>
				<td>
					<font color="white">i </font>
				</td>
			</tr>
			</tr>
			</table>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">

				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan permintaan Approval Faktur Pajak :</font>
					</td>
				</tr>

				<tr>
					</font>
					<td rowspan="12" width="5%"></td>
				<tr>
					<td width="10%">Nama </td>
					<td>:&nbsp;&nbsp; <?= $data_pajak[0]->nama_pengaju ?> </td>
				</tr>
				<tr>
					<td width="10%">NPP </td>
					<td>:&nbsp;&nbsp; <?= $data_pajak[0]->npp_pengaju ?> </td>
				</tr>
				<tr>
					<td>Jabatan </td>
					<td>:&nbsp;&nbsp; <?= $data_pajak[0]->jabatan_pengaju ?></td>
				</tr>
				</tr>
				</tr>
				<tr>
					<td>
						<font color="white">i </font>
					</td>
				</tr>
			</table>



			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">


				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Detail :</font>
					</td>
				</tr>

				<tr>
					</font>
					<td rowspan="12" width="5%"></td>
				<tr>
					<td width="10%">Tanggal</td>
					<td>:&nbsp;&nbsp; <?= date('d-m-Y', strtotime($data_pajak[0]->tanggal)) ?> </td>
				</tr>
				<tr>
					<td width="10%">Nama Marketing</td>
					<td>:&nbsp;&nbsp; <?= $data_pajak[0]->nama_marketing ?> </td>
				</tr>
				<tr>
					<td width="10%">Nama Customer</td>
					<td>:&nbsp;&nbsp; <?= $data_pajak[0]->nama_customer ?> </td>
				</tr>
				<tr>
					<td width="10%">Nomor PO </td>
					<td>:&nbsp;&nbsp; <?= $data_pajak[0]->no_po ?> </td>
				</tr>
				<tr>
					<td colspan="2"><strong>Nominal PO/INVOICE</strong></td>
				</tr>
				<tr>
					<td width="10%">DPP</td>
					<td>:&nbsp;&nbsp; <?= 'Rp ' . number_format($data_pajak[0]->dpp, 0, ',', '.') ?> </td>
				</tr>
				<tr>
					<td width="10%">PPN</td>
					<td>:&nbsp;&nbsp; <?= 'Rp ' . number_format($data_pajak[0]->ppn, 0, ',', '.') ?> </td>
				</tr>
				<tr>
					<td width="10%">Total</td>
					<td>:&nbsp;&nbsp; <?= 'Rp ' . number_format($data_pajak[0]->dpp + $data_pajak[0]->ppn, 0, ',', '.') ?> </td>
				</tr>
				<tr>
					<td width="10%">Alasan</td>
					<td>:&nbsp;&nbsp; <?= $data_pajak[0]->alasan ?> </td>
				</tr>
				<?php
				$link = '<a href="' . $data_pajak[0]->link_lampiran . '" target="blank"><i class="fas fa-link"></i> Link</a>';
				?>
				<tr>
					<td width="10%">Link Lampiran</td>
					<td>:&nbsp;&nbsp; <?= $link ?> </td>
				</tr>
				</tr>
				<tr>
					<td>
						<font color="white">i </font>
					</td>
				</tr>
			</table>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="black">

				<tr>
					<td>
						<font color="white">i </font>
					</td>
				</tr>
				<tr>
					<td colspan="4">Demikian Approval Faktur Pajak ini saya ajukan, atas perhatiannya saya ucapkan terimakasih.</td>
				</tr>

				<tr>
					<td>
						<font color="white">i </font>
					</td>
				</tr>

				<tr>
					<td>
						<font color="white">i </font>
					</td>
				</tr>
			</table>




			<table id="tbl_3" border="0" style="width:100%; font-family:'Times New Roman'; font-size:15px;">
				<tbody>
					<!-- Baris Tanggal -->
					<tr style="height: 35px;">
						<td colspan="9" style="text-align:right;">
							<br><br>
							Pekanbaru,&nbsp;<?= date('d-m-Y', strtotime($data_pajak[0]->tanggal)) ?>
						</td>
					</tr>

					<!-- Baris Label -->
					<tr style="height: 18px;">
						<td style="text-align:center;">Diajukan Oleh,</td>
						<td></td>
						<td style="text-align:center;" colspan="3">Diverifikasi Oleh,</td>
						<td></td>
						<td style="text-align:center;" colspan="3">Disetujui Oleh,</td>
					</tr>

					<!-- Baris TTD -->
					<?php
					$img_path = "uploads/file_karyawan/ttd/";
					$ttd1 = $img_path . "ttd_" . $data_pajak[0]->idPengaju . ".png";
					$ttd2 = ($data_pajak[0]->ttd_1 == '1') ? $img_path . "ttd_33.png" : (($data_pajak[0]->ttd_1 == '2') ? $img_path . "ttd_not.png" : $img_path . "ttd_notyet2.png");
					$ttd3 = ($data_pajak[0]->ttd_2 == '1') ? $img_path . "ttd_64.png" : (($data_pajak[0]->ttd_2 == '2') ? $img_path . "ttd_not.png" : $img_path . "ttd_notyet2.png");
					$ttd4 = ($data_pajak[0]->ttd_3 == '1') ? $img_path . "ttd_23.png" : (($data_pajak[0]->ttd_3 == '2') ? $img_path . "ttd_not.png" : $img_path . "ttd_notyet2.png");
					$ttd5 = ($data_pajak[0]->ttd_4 == '1') ? $img_path . "ttd_54.png" : (($data_pajak[0]->ttd_4 == '2') ? $img_path . "ttd_not.png" : $img_path . "ttd_notyet2.png");
					?>
					<tr style="height:70px;">
						<td style="text-align:center; width:15%;"><img src="<?= $ttd1 ?>" height="70"></td>
						<td style="width:2%;"></td>
						<td style="text-align:center; width:15%;"><img src="<?= $ttd2 ?>" height="70"></td>
						<td style="width:2%;"></td>
						<td style="text-align:center; width:15%;"><img src="<?= $ttd3 ?>" height="70"></td>
						<td style="width:2%;"></td>
						<td style="text-align:center; width:15%;"><img src="<?= $ttd4 ?>" height="70"></td>
						<td style="width:2%;"></td>
						<td style="text-align:center; width:15%;"><img src="<?= $ttd5 ?>" height="70"></td>
					</tr>


					<!-- Baris Nama -->
					<tr>
						<td style="text-align:center;"><?= $data_pajak[0]->nama_pengaju ?>
							<hr>
						</td>
						<td></td>
						<td style="text-align:center;">Yolanda Pratiwi
							<hr>
						</td>
						<td></td>
						<td style="text-align:center;">Azhari Pratama
							<hr>
						</td>
						<td></td>
						<td style="text-align:center;">Meilina Safitri
							<hr>
						</td>
						<td></td>
						<td style="text-align:center;">Bob Ariyos
							<hr>
						</td>
					</tr>

					<!-- Baris Jabatan -->
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_pajak[0]->jabatan_pengaju ?></i></td>
						<td></td>
						<td style="text-align:center; vertical-align:top;"><i>General Manager</i></td>
						<td></td>
						<td style="text-align:center; vertical-align:top;"><i>Senior Tax</i></td>
						<td></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corporate Planning & Business Management</i></td>
						<td></td>
						<td style="text-align:center; vertical-align:top;"><i>Director</i></td>
					</tr>

				</tbody>
			</table>





		</div>

		<br><br>
		<div width="100%">

			<?php if (sessPenggunaId() == '33' || sessPenggunaId() == '64' || sessPenggunaId() == '23' || sessPenggunaId() == '54') { ?>
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-Sijk="<?= encrypt($data_pajak[0]->idGc) ?>"> <i class="fas fa-check"></i> Terima </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-Sijk="<?= encrypt($data_pajak[0]->idGc) ?>"> <i class="fas fa-times"></i> Tolak </button>
			<?php } ?>
			<a href="surat_new/print_page/app_pajak/<?= $data_pajak[0]->idGc ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
			<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" style="margin-top:12px;" data-dismiss="modal">Kembali</button>
			<?php if (isAdmin()): ?>
				<button type="button" onclick="openAdminEditModal('app_pajak', '<?= encrypt($data_pajak[0]->idGc) ?>')" class="btn btn-warning float-right font-weight-bold text-dark" style="margin-left:12px; margin-top:12px;">
					<i class="fas fa-user-shield mr-1"></i> Edit Data (Admin)
				</button>
			<?php endif; ?>
			<br>
		</div>
		<br><br>
	</div>


	<?= form_close(); ?>

</div>
</div>



<script>
	document.addEventListener('DOMContentLoaded', function() {

		var level_ttd = $('#level_ttd').val();

		$(document).on('click', '.btn-approval', function() {
			var id = $('#id').val();

			Swal.fire({
				title: 'Setujui Approval Faktur Pajak?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat_new/ttd_setujui/app_pajak/' + level_ttd,
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
				title: 'Tolak Permintaan Approval Faktur Pajak?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat_new/ttd_tolak/app_pajak/' + level_ttd,
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