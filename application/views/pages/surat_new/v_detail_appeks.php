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
					<font color='#000000' face='Times New Roman'>No : <?= $data_aprv[0]->kode ?></font>
				</h2>
			</div>
			<font color='#000000'>
				<?php
				$ttd = "ttd_1";
				if ((sessPenggunaId() == '107')) {
					$ttd = 'ttd_1';
				} else if ((sessPenggunaId() == '33')) {
					$ttd = 'ttd_2';
				}
				?>
				<input type="hidden" id="level_ttd" value="<?= $ttd ?>">
				<?php $id = $data_aprv[0]->idGc ?>
				<input type="hidden" name="id" id="id" value="<?= $data_aprv[0]->idGc ?>">



				<?= form_open('surat_new/updateApprovalEks', array('id' => 'main-form', 'autocomplete' => 'off')); ?>

				<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
					<tr>
						<td colspan="3" style="text-align:left">
							<font color='#000000'>Dengan ini saya mengajukan Permintaan Approval Pengiriman Barang/Dokumen :</font>
						</td>
					</tr>
					<tr>

			</font>

			<td rowspan="17" width="2%"></td>

			<input type="hidden" id="idpengaju" value="<?= $data_aprv[0]->idPengaju ?>">
			<tr>
				<td width="25%">Nama </td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->pengaju ?> </td>
			</tr>
			<tr>
				<td>Jabatan </td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->jabatan ?></td>
			</tr>

			<tr>
				<td>
					<font color="white">i </font>
				</td>
			</tr>
			<tr>
				<td>Tanggal </td>
				<td>:&nbsp;&nbsp; <?= date('d/m/Y', strtotime($data_aprv[0]->tanggal)) ?></td>
			</tr>
			<tr>
				<td>Nama Customer </td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->nama_customer ?></td>
			</tr>
			<tr>
				<td>Asal Pengiriman </td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->gudang_asal ?></td>
			</tr>
			<tr>
				<td>Tujuan Pengiriman </td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->tujuan ?></td>
			</tr>
			<tr>
				<td>Detail Barang </td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->nama_barang ?></td>
			</tr>
			<tr>
				<td>Berat Barang </td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->no_sj ?></td>
			</tr>


			<tr>
				<td>
					<font color="white">i </font>
				</td>
			</tr>

			<tr>
				<td>Rencana Expedisi Yang Akan Digunakan</td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->rencana_expedisi ?></td>
			</tr>
			<tr>
				<td>Harga Expedisi Yang Diajukan</td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->harga_expedisi ?></td>
			</tr>

			<tr>
				<td>
					<font color="white">i </font>
				</td>
			</tr>

			<tr>
				<td>PO Customer</td>
				<td>:&nbsp;&nbsp;
					<?php if (!empty($data_aprv[0]->po_customer)): ?>
						<a href="<?= $data_aprv[0]->po_customer ?>">Klik untuk cek</a>
					<?php else: ?>
						-
					<?php endif; ?>
				</td>
			</tr>

			<tr>
				<td>SPH Customer</td>
				<td>:&nbsp;&nbsp;
					<?php if (!empty($data_aprv[0]->sph_customer)): ?>
						<a href="<?= $data_aprv[0]->sph_customer ?>">Klik untuk cek</a>
					<?php else: ?>
						-
					<?php endif; ?>
				</td>
			</tr>

			<tr>
				<td>Lampiran Lainnya</td>
				<td>:&nbsp;&nbsp;
					<?php if (!empty($data_aprv[0]->lampiran)): ?>
						<a href="<?= $data_aprv[0]->lampiran ?>">Klik untuk cek</a>
					<?php else: ?>
						-
					<?php endif; ?>
				</td>
			</tr>

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
						<font color='#000000'>Detail perbandingan harga ongkos kirim sebagai berikut :</font>
					</td>
				</tr>

				<tr>
					<td>
						<font color="white">i </font>
					</td>
				</tr>
			</table>

			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
				<thead>
					<tr>
						<th style="text-align:center" bgcolor="#d3d3d3" width="3%"> No </th>
						<th style="text-align:center" bgcolor="#d3d3d3"> Nama Expedisi </th>
						<th style="text-align:center" bgcolor="#d3d3d3" width="5%"> Berat Barang </th>
						<th style="text-align:center" bgcolor="#d3d3d3" width="10%"> Harga Acuan Terendah</th>
						<th style="text-align:center" bgcolor="#d3d3d3" width="10%"> Harga Yang Ditawarkan </th>
						<th style="text-align:center" bgcolor="#d3d3d3" width="15%"> Kelebihan </th>
						<th style="text-align:center" bgcolor="#d3d3d3" width="15%"> Kekurangan </th>
						<th style="text-align:center" bgcolor="#d3d3d3" width="20%" colspan="4"> Approval </th>
					</tr>
				</thead>
				<tbody>
					<?php
					$x = 1;
					$xy = 0;
					foreach ($detail_aprv as $row) {
						$x = $x + 1;
						$xy = $xy + 1;
					?>
						<tr>
							<td style="text-align:center">
								<input type="hidden" name="id_sodetail[]" value="<?= encrypt($row->id) ?>"><?= $xy ?>
							</td>
							<td class="des">&nbsp;<?= $row->namaekspedisi ?>&nbsp;</td>
							<td style="text-align:center" class="qty">&nbsp;<?= $row->berat ?>&nbsp;</td>
							<?php if ($row->acuanharga != '') { ?>
								<td class="nom" style="text-align:right">
									<font color='#000000'>
										<label class="mylabel2" style="text-align:left">&nbsp;Rp. </label>
										<label class="mylabel" style="text-align:right"><?= number_format($row->acuanharga, 0, ",", ".") ?>,-&nbsp;</label>
									</font>
								</td>
							<?php } else { ?>
								<td class="nom" style="text-align:center">
									<font color='#000000'></font>
								</td>
							<?php } ?>

							<?php if ($row->harga != '') { ?>
								<td class="nom" style="text-align:right">
									<font color='#000000'>
										<label class="mylabel2" style="text-align:left">&nbsp;Rp. </label>
										<label class="mylabel" style="text-align:right"><?= number_format($row->harga, 0, ",", ".") ?>,-&nbsp;</label>
									</font>
								</td>
							<?php } else { ?>
								<td class="nom" style="text-align:center">
									<font color='#000000'></font>
								</td>
							<?php } ?>
							<td style="text-align:center" class="qty">&nbsp;<?= $row->fasilitas ?>&nbsp;</td>
							<td style="text-align:center" class="qty">&nbsp;<?= $row->kekurangan ?>&nbsp;</td>


							<?php
							// Menentukan nilai approval untuk ditampilkan
							if ($row->approval == 1) {
								$dataApproval = "Disetujui";
								$checked1 = "checked"; // Centang checkbox Disetujui
								$checked2 = ""; // Tidak centang checkbox Ditolak
							} else if ($row->approval == 2) {
								$dataApproval = "Ditolak";
								$checked1 = ""; // Tidak centang checkbox Disetujui
								$checked2 = "checked"; // Centang checkbox Ditolak
							} else {
								$checked1 = ""; // Jika approval tidak 1 atau 2
								$checked2 = "";
							}
							?>
							<?php if (isAdmin() || sessPenggunaId() == '107') { ?>
								<td>
									<input type="checkbox" id="approval_1_<?= $row->id ?>" name="approval[]" value="1" <?= $checked1 ?> onclick="toggleCheckbox(this, 'approval_2_<?= $row->id ?>')" />
								</td>
								<td><label>Disetujui</label></td>
								<td>
									<input type="checkbox" id="approval_2_<?= $row->id ?>" name="approval[]" value="2" <?= $checked2 ?> onclick="toggleCheckbox(this, 'approval_1_<?= $row->id ?>')" />
								</td>
								<td><label>Ditolak</label></td>
							<?php } else { ?>
								<td>
									<input type="checkbox" id="approval_1_<?= $row->id ?>" name="approval[]" value="1" <?= $checked1 ?> disabled />
								</td>
								<td><label>Disetujui</label></td>
								<td>
									<input type="checkbox" id="approval_2_<?= $row->id ?>" name="approval[]" value="2" <?= $checked2 ?> disabled />
								</td>
								<td><label>Ditolak</label></td>
							<?php } ?>
						</tr>

					<?php } ?>
					<?php for ($kosong = $x; $kosong <= 7; $kosong++) { ?>
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
							<td> </td>
							<td> </td>
							<td> </td>
							<td> </td>
						</tr>
					<?php } ?>

				</tbody>

			</table>
			<br>
			<div style="text-align: right;">
				<?php if ((sessPenggunaId() == '107' || sessPenggunaId() == '1')) { ?>
					<button type="button" class="btn btn-success btn-save">Simpan</button>
				<?php } ?>
			</div>
			<br>

			<table id="tbl_17" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">


				<tr>
				<tr>
					<td> <i>(Terlampir Dokumen pendukung misal : Surat Jalan, bukti penawaran harga, PO barang, Lampiran menyesuakan dengan kondisi pengajuan) </i> </td>
				</tr>
				<tr>
					<td width="10%" height="35px"> </td>
				</tr>


				</tr>
			</table>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td rowspan="17" width="2%"></td>

					<?php if (sessPenggunaId() == '11' || sessPenggunaId() == '107') { ?>

				<tr>
					<td width="20%">Catatan Finance</td>
					<td>:&nbsp;&nbsp;<input id="catatan_finance" type="text" placeholder="Klik Untuk Memasukkan Catatan Finance" value="<?= isset($data_aprv[0]->catatan_finance) ? htmlspecialchars($data_aprv[0]->catatan_finance) : '' ?>" required>
					</td>
				</tr>

			<?php } else { ?>
				<tr>
					<td width="20%">Catatan Finance</td>
					<td>:&nbsp;&nbsp; <?= $data_aprv[0]->catatan_finance ?></td>
				</tr>

			<?php } ?>

			</tr>
			</table>


			<table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>

					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="6"><br><br>
							<?= $data_aprv[0]->kota_aju ?>
							<span> ,&nbsp; </span>
							<?= date('d-m-Y', strtotime($data_aprv[0]->created_at)) ?>

						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:30%;">Diajukan Oleh,</td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:30%;">Disetujui Oleh,</td>
					</tr>


					<tr style="height:60px;">
						<?php
						$img_path 	= "uploads/file_karyawan/ttd/";
						$ttdaju		= $img_path . "ttd_" . $data_aprv[0]->idPengaju . ".png";
						$ttd1 		= $img_path . "ttd_notyet2.png";

						if ($data_aprv[0]->ttd_1 == '1') {
							$ttd1 = $img_path . "ttd_107.png";
						} else if ($data_aprv[0]->ttd_1 == '2') {
							$ttd1 = $img_path . "ttd_not.png";
						}
						?>
						<td style="text-align:center; width:30%;"> <?php echo '<img src="' . $ttdaju . '" height="70">'; ?> </td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:30%;"><?php echo '<img src="' . $ttd1 . '" height="70">'; ?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_aprv[0]->pengaju ?>
							<hr>
							</hr>
						</td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Dirangga Madali
							<hr>
							</hr>
						</td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_aprv[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Senior Accounting & Finance</i></td>
					</tr>
				</tbody>

			</table>
		</div>

		<br><br>
		<div width="100%">
			<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '107') { ?>
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-Sijk="<?= encrypt($data_aprv[0]->idGc) ?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-Sijk="<?= encrypt($data_aprv[0]->idGc) ?>"> <i class="fas fa-times"></i> Tolak </button>
			<?php } ?>
			<?php if (sessPenggunaId() == '107') { ?>
				<button type="button" class="btn btn-success float-right btn-submit2" style="margin-left:12px; margin-top:12px;" id-Sijk="<?= encrypt($data_aprv[0]->idGc) ?>"> <i class="fas fa-check"></i> Submit (Finance) </button>
			<?php } ?>
			<a href="surat_new/print_page/appeks/<?= $data_aprv[0]->idGc ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
			<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" style="margin-top:12px;" data-dismiss="modal">Kembali</button>
			<?php if (isAdmin()): ?>
				<button type="button" onclick="openAdminEditModal('appeks', '<?= encrypt($data_aprv[0]->idGc) ?>')" class="btn btn-warning float-left font-weight-bold text-dark" style="margin-left:12px; margin-top:12px;">
					<i class="fas fa-user-shield mr-1"></i> Edit Data (Admin)
				</button>
			<?php endif; ?>

			<!--<?php if ($data_aprv[0]->lampiran != "") { ?>
				<a href="<?= $data_aprv[0]->lampiran ?>" target="blank" class="btn btn-primary float-center" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran Lainnya</a>
			<?php } ?>-->
			<br>
		</div>
		<br><br>
	</div>


	<?= form_close(); ?>

</div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Detail</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">


					<div class="form-group">
						<label for="namaekspedisi" class="form-control-label">Nama Ekspedisi <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="namaekspedisi" name="namaekspedisi" required>
					</div>
					<div class="form-group">
						<label for="berat" class="form-control-label">Berat Barang <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="berat" name="berat" required>
					</div>
					<div class="form-group">
						<label for="fasilitas" class="form-control-label">Fasilitas Pengiriman <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="fasilitas" name="fasilitas" required>
					</div>
					<div class="form-group">
						<label for="harga" class="form-control-label">Harga<span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="harga" name="harga" required>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_aprv" name="id_aprv">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="save-form" class="btn btn-success save-form"><i class="fas fa-check"></i> Setujui</button>
				<button type="button" id="save-form-tolak" class="btn btn btn-secondary save-form-tolak"><i class="fas fa-times"></i> Tolak</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>




<script>
	function toggleCheckbox(checkbox, otherCheckboxId) {
		// Jika checkbox ini dicentang, uncheck checkbox yang lain
		if (checkbox.checked) {
			document.getElementById(otherCheckboxId).checked = false;
		}
	}



	document.addEventListener('DOMContentLoaded', function() {

		var level_ttd = $('#level_ttd').val();

		$(document).on('click', '.btn-approval', function() {
			var id = $('#id').val();

			Swal.fire({
				title: 'Setujui Permintaan Approval Ekspedisi?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat_new/ttd_setujui/appeks/' + level_ttd,
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
				title: 'Tolak Permintaan Approval Ekspedisi?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat_new/ttd_tolak/appeks/' + level_ttd,
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

		$(document).on('click', '.btn-submit2', function() {

			var id = $('#id').val();
			var catatan_finance = $('#catatan_finance').val();

			Swal.fire({
				title: 'Submit Catatan Finance?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					// console.log('' + trf_komisi);
					$.ajax({
						method: 'POST',
						url: 'surat_new/UpdateAppeksFinance/' + id,
						dataType: 'JSON',
						data: {
							catatan_finance: catatan_finance,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
						}
					})
				}
			})
		})


		$(document).on('click', '.btn-editItem', function() {
			$('.btn-isactive').remove()
			var object = 'surat_new'
			$('#main-modal #modal-form').attr('action', 'surat_new/updateItemApproval')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/editItem/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #namaekspedisi').val(data[0].namaekspedisi)
					$('#main-modal #berat').val(data[0].berat)
					$('#main-modal #fasilitas').val(data[0].fasilitas)
					$('#main-modal #harga').val(data[0].harga)
					$('#main-modal #id_aprv').val(id)
				})
		})

		$('.save-form').click(function() {

			$.ajax({
				method: 'POST',
				url: 'surat_new/updateItemApproval',
				dataType: 'JSON',
				data: {
					id: $('#id_aprv').val(),
					csrf_token: token
				},
				success: function(resp) {
					handleResponse(resp)
				}
			})
		})

		$('.save-form-tolak').click(function() {

			$.ajax({
				method: 'POST',
				url: 'surat_new/updateItemApprovalTolak',
				dataType: 'JSON',
				data: {
					id: $('#id_aprv').val(),
					csrf_token: token
				},
				success: function(resp) {
					handleResponse(resp)
				}
			})
		})




	})

	function goBack() {
		window.history.back();
	}
</script>