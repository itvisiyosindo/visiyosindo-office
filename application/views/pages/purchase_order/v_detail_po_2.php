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
<div class="col-xl-12 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
		<div class="table-responsive">
			<div class="text-center mt-0">
				<h2>
					<font color='#000000' face='Times New Roman'><u>No : <?= $data_po[0]->kode_po ?></u></font>
				</h2>
			</div>
			<?php
			$ttd = "ttd_1";
			if ((sessPenggunaId() == '769')) {
				$ttd = 'ttd_1';
			} else if ((sessPenggunaId() == '107')) {
				$ttd = 'ttd_2';
			} else if ((sessPenggunaId() == '23')) {
				$ttd = 'ttd_3';
			} else if ((sessPenggunaId() == '54')) {
				$ttd = 'ttd_4';
			}
			?>
			<input type="hidden" id="level_ttd" value="<?= $ttd ?>">
			<input type="hidden" name="id" id="id" value="<?= $data_po[0]->id_po ?>">
			<?php
			$dt_pengajuan = strtotime($data_po[0]->tgl_Pengajuan);
			$tgl_pengajuan = date("d", $dt_pengajuan) . " - " . date("m", $dt_pengajuan) . " - " . date("Y", $dt_pengajuan);
			?>
			<font color='#000000'>
				<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%"
					color="red">
					<tr>
						<td colspan="3" style="text-align:left">
							<font color='#000000'><br>Dengan ini saya mengajukan Purchase Order :</font>
						</td>
					</tr>
					<tr>
			</font>
			<td rowspan="5" width="7%"></td>
			<tr>
				<td width="10%">Nama </td>
				<td>:&nbsp;&nbsp; <?= $data_po[0]->pengaju ?> </td>
			</tr>
			<tr>
				<td>Jabatan </td>
				<td>:&nbsp;&nbsp; <?= $data_po[0]->jabatan ?></td>
			</tr>

			<tr>
				<td>
					<font color="white">i </font>
				</td>
			</tr>
			</tr>
			</table>

			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1"
				width="100%">
				<thead>
					<tr>
						<th style="text-align:center" width="3%"> No </th>
						<th style="text-align:center"> Nama Barang/Jasa </th>
						<th style="text-align:center" width="11%"> Kode Barang/Jasa </th>
						<th colspan="3" style="text-align:center" width="21%"> Stock Keluar </th>
						<th style="text-align:center" width="8%"> Stock Gudang Keluruhan Saat Ini </th>
						<th style="text-align:center" width="5%"> Stock yang Sudah di PO </th>
						<th style="text-align:center" width="5%"> Kebutuhan PO Pending </th>
						<th style="text-align:center" bgcolor="#ffc000" width="5%"> Rencana PO Baru (Dari Gudang) </th>
						<th style="text-align:center" bgcolor="#ffc000" width="7%"> Supplier </th>
						<th style="text-align:center" bgcolor="#ffff01" width="10%"> Detail </th>
						<th style="text-align:center" bgcolor="#ffff01" width="10%"> Catatan <br> <span
								style="font-size:small;">Director of Corporate Planning & Business Management</span>
						</th>
						<?php if (sessPenggunaId() == '1' || sessPenggunaId() == $data_po[0]->idPengaju) { ?>
							<th style="text-align:center" bgcolor="#ffff01" width="5%"> Aksi </th>
						<?php } ?>
					</tr>
					<tr>
						<th style="text-align:center" bgcolor="#b7d5ac"> </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> <?= $data_po[0]->bulan_1 ?> </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> <?= $data_po[0]->bulan_2 ?> </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> <?= $data_po[0]->bulan_3 ?> </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> </th>
						<?php if (sessPenggunaId() == '1' || sessPenggunaId() == $data_po[0]->idPengaju) { ?>
							<th style="text-align:center" bgcolor="#b7d5ac"> </th>
						<?php } ?>

					</tr>
				</thead>
				<tbody>
					<?php
					$x = 1;
					$xy = 0;
					foreach ($data_detail as $row) {
						$x = $x + 1;
						$xy = $xy + 1;

						$id = encrypt($row->id_pod);
					?>
						<tr>
							<td style="text-align:center"> <?= $xy ?> </td>
							<td class="nama">&nbsp;<?= $row->nama_barang ?>&nbsp;</td>
							<td class="nama">&nbsp;<?= $row->kode_barang ?>&nbsp;</td>
							<td class="bulan1" style="text-align:center">&nbsp;<?= $row->isibulan_1 ?>&nbsp;</td>
							<td class="bulan2" style="text-align:center">&nbsp;<?= $row->isibulan_2 ?>&nbsp;</td>
							<td class="bulan3" style="text-align:center">&nbsp;<?= $row->isibulan_3 ?>&nbsp;</td>
							<td class="stok_gudang" style="text-align:center">&nbsp;<?= $row->stok_gudang ?>&nbsp;</td>
							<td class="stok_po" style="text-align:center">&nbsp;<?= $row->stok_po ?>&nbsp;</td>
							<td class="kebutuhan_po" style="text-align:center">&nbsp;<?= $row->kebutuhan_po ?>&nbsp;</td>
							<td class="rencana_po" style="text-align:center">&nbsp;<?= $row->rencana_po ?>&nbsp;</td>
							<td class="supplier" style="text-align:center">&nbsp;<?= $row->supplier ?>&nbsp;</td>
							<td class="detail" style="text-align:center">&nbsp;<?= $row->detail ?>&nbsp;</td>

							<?php if (sessPenggunaId() == '23') { ?>
								<td class="keterangan2" style="text-align:center">&nbsp;<?= $row->keterangan2 ?>&nbsp;<button
										type="button" class="btn btn-sm btn-primary btn-edit" data-id="<?= $id ?>"><i
											class="bx bx-pencil"></i></button>&nbsp;</td>
							<?php } else { ?>
								<td class="keterangan2" style="text-align:center">&nbsp;<?= $row->keterangan2 ?>&nbsp;</td>
							<?php } ?>

							<?php if (sessPenggunaId() == '1' || sessPenggunaId() == $data_po[0]->idPengaju) { ?>
								<td class="aksi" style="text-align:center">&nbsp;<button type="button"
										class="btn btn-sm btn-primary btn-edit" data-id="<?= $id ?>"><i
											class="bx bx-pencil"></i></button>&nbsp;</td>
							<?php } ?>
						</tr>
					<?php } ?>
					<?php for ($kosong = $x; $kosong <= 12; $kosong++) { ?>
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
							<td> </td>
							<td> </td>
							<?php if (sessPenggunaId() == '1' || sessPenggunaId() == $data_po[0]->idPengaju) { ?>
								<td> </td>
							<?php } ?>
						</tr>
					<?php } ?>
				</tbody>

			</table>
			<br>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td rowspan="5"></td>

					<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '107') { ?>
						<?php if ($data_po[0]->ttd_4 == '1') { ?>
				<tr>
					<td width="10%">No PO</td>
					<td>:&nbsp;&nbsp;<input id='no_po' type="text"
							placeholder='Klik Untuk Memasukkan No PO (Diisi oleh Senior Accounting & Finance)' required>
					</td>
				</tr>
				<tr>
					<td width="10%">Lampiran PO</td>
					<td>:&nbsp;&nbsp;<input id='lampiran_finance' type="text"
							placeholder='Klik Untuk Memasukkan Link Lampiran PO (Diisi oleh Senior Accounting & Finance)'
							required></td>
				</tr>
		<?php }
					} ?>

		</tr>
			</table>



			<table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="9">
							<?= $data_po[0]->kota_pengajuan ?>, <?= $tgl_pengajuan ?>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" colspan="2">Diajukan Oleh,</td>
						<td style="text-align:right; width:25.5%;" colspan="4">Diverifikasi
							Oleh,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
						<td style="text-align:right; width:30%;" colspan="3">Disetujui
							Oleh,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
					<tr style="height:60px;">
						<?php
						$img_path = "uploads/file_karyawan/ttd/";
						$ttdaju = $img_path . "ttd_" . $data_po[0]->idPengaju . ".png";
						$ttd1 = $img_path . "ttd_notyet2.png";
						$ttd2 = $img_path . "ttd_notyet2.png";
						$ttd3 = $img_path . "ttd_notyet2.png";
						$ttd4 = $img_path . "ttd_notyet2.png";

						if ($data_po[0]->ttd_1 == '1') {
							$ttd1 = $img_path . "ttd_769.png";
						} else if ($data_po[0]->ttd_1 == '2') {
							$ttd1 = $img_path . "ttd_not.png";
						}
						if ($data_po[0]->ttd_2 == '1') {
							$ttd2 = $img_path . "ttd_107.png";
						} else if ($data_po[0]->ttd_2 == '2') {
							$ttd2 = $img_path . "ttd_not.png";
						}
						if ($data_po[0]->ttd_3 == '1') {
							$ttd3 = $img_path . "ttd_23.png";
						} else if ($data_po[0]->ttd_3 == '2') {
							$ttd3 = $img_path . "ttd_not.png";
						}
						if ($data_po[0]->ttd_4 == '1') {
							$ttd4 = $img_path . "ttd_54.png";
						} else if ($data_po[0]->ttd_4 == '2') {
							$ttd4 = $img_path . "ttd_not.png";
						}
						?>
						<td style="text-align:center; width:17%;">
							<?php echo '<img src="' . $ttdaju . '" height="70">'; ?> </td>
						<td style="text-align:center; width:4%;"></td>
						<td style="text-align:center; width:17%;"><?php echo '<img src="' . $ttd1 . '" height="70">'; ?>
						</td>
						<td style="text-align:center; width:4%;"></td>
						<td style="text-align:center; width:17%;"><?php echo '<img src="' . $ttd2 . '" height="70">'; ?>
						</td>
						<td style="text-align:center; width:4%;"></td>
						<td style="text-align:center; width:20%;"><?php echo '<img src="' . $ttd3 . '" height="70">'; ?>
						</td>
						<td style="text-align:center; width:4%;"></td>
						<td style="text-align:center; width:25%;"><?php echo '<img src="' . $ttd4 . '" height="70">'; ?>
						</td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_po[0]->nama_ttd ?>
							<hr>
							</hr>
						</td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Fitri Andriani
							<hr>
							</hr>
						</td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Dirangga Madali
							<hr>
							</hr>
						</td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Meilina Safitri
							<hr>
							</hr>
						</td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Bob Ariyos
							<hr>
							</hr>
						</td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_po[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Penanggung Jawab Teknis</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Senior Accounting & Finance</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corporate Planning & Business
								Management</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director</i></td>
					</tr>
				</tbody>
			</table>
			<br><br>
		</div>
		<br><br>
		<div role="document">
			<?php if (sessPenggunaId() == '107') { ?>
				<?php if ($data_po[0]->ttd_4 == '1') { ?>
					<button type="button" class="btn btn-success float-right btn-submit"
						style="margin-left:12px; margin-top:12px;" id-po="<?= encrypt($data_po[0]->id_po) ?>"> <i
							class="fas fa-check"></i> Submit (PO) </button>
			<?php }
			} ?>
			<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '769' || sessPenggunaId() == '23' || sessPenggunaId() == '107' || sessPenggunaId() == '54') { ?>

				<button type="button" class="btn btn-success float-right btn-approval"
					style="margin-left:12px; margin-top:12px;" id-po="<?= encrypt($data_po[0]->id_po) ?>"> <i
						class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial"
					style="margin-left:12px; margin-top:12px;" id-po="<?= encrypt($data_po[0]->id_po) ?>"> <i
						class="fas fa-times"></i> Tolak </button>
			<?php
			} ?>
			<a href="purchase_order/print_page/purchase_order/<?= $data_po[0]->id_po ?>"
				class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i
					class="fas fa-print"></i> Cetak </a>
			<?php if ($data_po[0]->lampiran != "") { ?>
				<a href="<?= $data_po[0]->lampiran ?>" class="btn btn-primary float-right"
					style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran
					Warehouse</a>
			<?php } ?>
			<?php if ($data_po[0]->lampiran_finance != "") { ?>
				<a href="<?= $data_po[0]->lampiran_finance ?>" class="btn btn-primary float-right"
					style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran Finance</a>
			<?php } ?>
			<button type="button" onclick="goBack()"
				class="btn btn-secondary btn-clear-form float-left">Kembali</button>
			<br>
		</div>
		<br><br>
	</div>

</div>
</div>

<div id="main-modal-calonedit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
	role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Catatan </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-calonedit', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-calonpelangganedit-form">
					<div class="form-group">
						<div hidden>
							<input type="text" class="form-control col-sm-5" id="idcalonedit" name="idcalonedit"
								readonly required />
						</div>
					</div>
					<?php if (sessPenggunaId() == 23) { ?>
						<div class="form-group">
							<div>
								<label for="namabarang" class="form-control-label">Nama Barang :</label>
								<input type="text" class="form-control" id="namabarang" name="namabarang" readonly
									required />
							</div>
						</div>
						<div class="form-group">
							<label for="keterangan2" class="form-control-label">Keterangan<span class="text-danger">*</span>
								:</label>
							<input type="text" class="form-control" id="keterangan2" name="keterangan2" required>
						</div>

					<?php } else if (sessPenggunaId() == '1' || sessPenggunaId() == $data_po[0]->idPengaju) { ?>
						<div class="form-group">
							<div>
								<label for="namabarang" class="form-control-label">Nama Barang :</label>
								<input type="text" class="form-control" id="namabarang" name="namabarang" required />
							</div>
						</div>
						<div class="form-group">
							<label for="kodebarang" class="form-control-label">Kode Barang<span class="text-danger">*</span>
								:</label>
							<input type="text" class="form-control" id="kodebarang" name="kodebarang" required>
						</div>
						<div class="form-group">
							<label for="isibulan_1" class="form-control-label">Stok Bulan <?= $data_po[0]->bulan_1 ?><span
									class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="isibulan_1" name="isibulan_1" required>
						</div>
						<div class="form-group">
							<label for="isibulan_2" class="form-control-label">Stok Bulan <?= $data_po[0]->bulan_2 ?><span
									class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="isibulan_2" name="isibulan_2" required>
						</div>
						<div class="form-group">
							<label for="isibulan_3" class="form-control-label">Stok Bulan <?= $data_po[0]->bulan_3 ?><span
									class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="isibulan_3" name="isibulan_3" required>
						</div>
						<div class="form-group">
							<label for="stok_gudang" class="form-control-label">Stok Gudang<span
									class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="stok_gudang" name="stok_gudang" required>
						</div>
						<div class="form-group">
							<label for="stok_po" class="form-control-label">Stok Sudah PO<span class="text-danger">*</span>
								:</label>
							<input type="text" class="form-control" id="stok_po" name="stok_po" required>
						</div>
						<div class="form-group">
							<label for="kebutuhan_po" class="form-control-label">Kebutuhan PO<span
									class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="kebutuhan_po" name="kebutuhan_po" required>
						</div>
						<div class="form-group">
							<label for="rencana_po" class="form-control-label">Rencana PO<span class="text-danger">*</span>
								:</label>
							<input type="text" class="form-control" id="rencana_po" name="rencana_po" required>
						</div>
						<div class="form-group">
							<label for="supplier" class="form-control-label">Supplier<span class="text-danger">*</span>
								:</label>
							<input type="text" class="form-control" id="supplier" name="supplier" required>
						</div>
						<div class="form-group">
							<label for="detail" class="form-control-label">Detail<span class="text-danger">*</span>
								:</label>
							<input type="text" class="form-control" id="detail" name="detail" required>
						</div>
					<?php } ?>


					<div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary btn-clear-form"
								data-dismiss="modal">Tutup</button>
							<button type="button" id="btn-edit-form-pelanggan" class="btn btn-success btn-edit"
								data-dismiss="modal">Update</button>
						</div>
					</div>

					<?= form_close(); ?>
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
						title: 'Setujui permohonan Purchase Order?',
						icon: 'question',
						showCancelButton: true,
						confirmButtonText: 'Ya',
						cancelButtonText: 'Tidak'
					}).then(function(result) {
						if (result.value) {
							$.ajax({
								method: 'POST',
								url: 'purchase_order/ttd_setujui/po/' + level_ttd,
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

				$(document).on('click', '.btn-submit', function() {

					var id = $('#id').val();
					var no_po = $('#no_po').val();
					var lampiran_finance = $('#lampiran_finance').val();

					// var level_ttd = $('#level_ttd').val();
					// console.log(approvall);
					Swal.fire({
						title: 'Submit No PO dan Link Lampiran?',
						icon: 'question',
						showCancelButton: true,
						confirmButtonText: 'Ya',
						cancelButtonText: 'Tidak'
					}).then(function(result) {
						if (result.value) {
							// console.log('' + trf_komisi);
							$.ajax({
								method: 'POST',
								url: 'purchase_order/updateFinance/' + id,
								dataType: 'JSON',
								data: {
									no_po: no_po,
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


				$(document).on('click', '.btn-denial', function() {
					var id = $('#id').val();

					Swal.fire({
						//title: approval + ' absensi?',
						title: 'Tolak permohonan Purchase Order?',
						icon: 'question',
						showCancelButton: true,
						confirmButtonText: 'Ya',
						cancelButtonText: 'Tidak'
					}).then(function(result) {
						if (result.value) {
							$.ajax({
								method: 'POST',
								url: 'purchase_order/ttd_tolak/po/' + level_ttd,
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


				//Tambah catatan director meli
				$(document).on('click', '.btn-edit', function() {

					var par = $(this).data("id");
					var url = "purchase_order/edit/" + par

					$.ajax({
						type: "GET",
						url: url,
						success: function(response) {
							//console.log(response)  	
							if (response) {
								result = JSON.parse(response);
								$('#main-modal-calonedit #idcalonedit').val(result['id_pod']);
								$('#main-modal-calonedit #namabarang').val(result['namabarang']);
								$('#main-modal-calonedit #keterangan2').val(result['keterangan2']);

								$('#main-modal-calonedit #kodebarang').val(result['kodebarang']);
								$('#main-modal-calonedit #isibulan_1').val(result['isibulan_1']);
								$('#main-modal-calonedit #isibulan_2').val(result['isibulan_2']);
								$('#main-modal-calonedit #isibulan_3').val(result['isibulan_3']);
								$('#main-modal-calonedit #stok_gudang').val(result['stok_gudang']);
								$('#main-modal-calonedit #stok_po').val(result['stok_po']);
								$('#main-modal-calonedit #kebutuhan_po').val(result['kebutuhan_po']);
								$('#main-modal-calonedit #rencana_po').val(result['rencana_po']);
								$('#main-modal-calonedit #supplier').val(result['supplier']);
								$('#main-modal-calonedit #detail').val(result['detail']);
								$('#main-modal-calonedit').modal();
							}
						},
						error: function(request, status, error) {
							alert(request.responseText);
						}
					});


				})

				$('#btn-edit-form-pelanggan').click(function() {
					$.ajax({
						type: "POST",
						url: "purchase_order/update",
						cache: false,
						data: {
							id: $('#main-modal-calonedit  #idcalonedit').val(),
							namabarang: $('#main-modal-calonedit #namabarang').val(),
							keterangan2: $('#main-modal-calonedit #keterangan2').val(),

							kodebarang: $('#main-modal-calonedit #kodebarang').val(),
							isibulan_1: $('#main-modal-calonedit #isibulan_1').val(),
							isibulan_2: $('#main-modal-calonedit #isibulan_2').val(),
							isibulan_3: $('#main-modal-calonedit #isibulan_3').val(),
							stok_gudang: $('#main-modal-calonedit #stok_gudang').val(),
							stok_po: $('#main-modal-calonedit #stok_po').val(),
							kebutuhan_po: $('#main-modal-calonedit #kebutuhan_po').val(),
							rencana_po: $('#main-modal-calonedit #rencana_po').val(),
							supplier: $('#main-modal-calonedit #supplier').val(),
							detail: $('#main-modal-calonedit #detail').val(),

						},
						success: function(result) {
							var pesan = $.parseJSON(result)
							Swal.fire({
								type: 'success',
								icon: 'success',
								title: pesan.msg,
								showConfirmButton: false,
								timer: 2000
							});
						},
					});
					//	window.location.reload();
				})






			})



			function goBack() {
				window.history.back();
			}
		</script>