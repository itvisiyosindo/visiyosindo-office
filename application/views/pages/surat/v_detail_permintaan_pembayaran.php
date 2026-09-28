<?php
$this->load->helper('terbilang');
$this->load->helper('mata_uang');
?>
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

		.mytextarea {
			width: 100%;
			height: auto;
			border: 0px solid #000;
			border-radius: 0px;
			-moz-border-radius: 8px;
			margin: 0px;
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

		.mylabel {
			border: 0px solid blue;
			display: table-cell;
			width: 100%;
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
		<div class="text-center mt-0">
			<h2>
				<font color='#000000' face='Times New Roman'>No : <?= $data_spp[0]->kode ?></font>
			</h2>
			<input type="hidden" id="kodesurat" value="<?= $data_spp[0]->kode ?>">
		</div>
		<br>

		<?php
		$dt_pengajuan	= strtotime($data_spp[0]->tglPengajuan);
		$tgl_pengajuan 	= date("d", $dt_pengajuan) . " - " . date("m", $dt_pengajuan) . " - " . date("Y", $dt_pengajuan);

		$ttd = 'ttd_1';

		if ((sessPenggunaId() != $data_spp[0]->idPengaju) || sessPenggunaId() == 1) {
			if ((sessPenggunaId() == '33')) {
				$ttd = 'ttd_1';
			} else if ((sessPenggunaId() == '23')) {
				$ttd = 'ttd_2';
			} else if ((sessPenggunaId() == '54')) {
				$ttd = 'ttd_3';
			}
		}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>">
		<input class="no-outline" type="hidden" id="id-spp" name="id-spp" value="<?= $data_spp[0]->idSpp ?>">

		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
			<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>BANKING untuk pembayaran sebagai berikut:</font>
					</td>
				</tr>
			</table>
			<br>
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
					$x = 0;
					$nom = 0;
					foreach ($detail_spp as $row) {
						$x = $x + 1;
						$nom = (float) $row->nominal + $nom;
					?>
						<tr>
							<td style="text-align:center">
								<?php
								if ($x == 1) {
									$tgl_out	= strtotime($row->tgl);
									$tgl_out 	= date("d", $tgl_out) . " - " . date("m", $tgl_out) . " - " . date("Y", $tgl_out);
									echo $tgl_out;
								} else {
									echo "";
								}
								?>
							</td>
							<td>&nbsp;<?= $row->ket ?>&nbsp;</td>
							<td>
								<?php
								if ($row->nominal == 0) {
									echo "";
								} else {
								?>
									<label class="mylabel" style="text-align:left">&nbsp;<?= mata_uang($data_spp[0]->kodeMataUang) ?></label>
									<label class="mylabel" style="text-align:right"><?= nominal($data_spp[0]->kodeMataUang, $row->nominal) ?>&nbsp;</label>
								<?php }	?>
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
						<th bgcolor="#b7d5ac" colspan="2" style="text-align:center"> TOTAL PENGAJUAN </th>
						<th bgcolor="#b7d5ac">
							<label class="mylabel" style="text-align:left">&nbsp;<?= mata_uang($data_spp[0]->kodeMataUang) ?></label>
							<label class="mylabel" style="text-align:right"><?= nominal($data_spp[0]->kodeMataUang, $nom) ?>&nbsp;</label>
						</th>
						<!--<td><input type="text" id="total" Style="width:100%; text-align:right" class="myinput" placeholder="-" disabled required></td>-->
					</tr>
				</tfoot>
			</table>

			<table border="0" style="font-family:Times New Roman; font-color:black; font-size:15px" width="100%">
				<tr>
					<td colspan="2" width="20%" style="text-align:center">
						<font>Terbilang :</font>
					</td>
					<td style="text-align:justify; text-justify:inter-word;">
						<?php
						$this->load->helper('terbilang');
						if ($data_spp[0]->idPengaju == 2) {
							echo '<textarea class="mytextarea" id="terbilang" name="terbilang" rows="2" placeholder="Ketik Terbilang" required>' . $data_spp[0]->terbilang . '</textarea>';
						} else {
							echo terbilang($data_spp[0]->kodeMataUang, $nom);
						}
						?>
					</td>
					<br>
				</tr>
				<tr>
					<th colspan="3" style="text-align:left">Rekening / Virtual Account Pembayaran :</th>
				</tr>
				<tr>
					<td>Nama Bank </td>
					<td width="2px">:&nbsp;</td>
					<td>
						<?php
						if ($data_spp[0]->idPengaju == sessPenggunaId()) {
							echo '<input type="text" id="bank" name="bank" Style="width:100%" placeholder="Ketik Nama Bank" value="' . $data_spp[0]->bank . '" required>';
						} else {
							echo $data_spp[0]->bank;
						}
						?>
					</td>
				</tr>
				<tr>
					<td>No Rekening </td>
					<td>:&nbsp;</td>
					<td>
						<?php
						if ($data_spp[0]->idPengaju == sessPenggunaId()) {
							echo '<input type="text" id="norek" name="norek" Style="width:100%" placeholder="Ketik No Rekening" value="' . $data_spp[0]->norek . '" required>';
						} else {
							echo $data_spp[0]->norek;
						}
						?>
					</td>
				</tr>
				<tr>
					<td>Atas Nama </td>
					<td>:&nbsp;</td>
					<td>
						<?php
						if ($data_spp[0]->idPengaju == sessPenggunaId()) {
							echo '<input type="text" id="pengaju" name="pengaju" Style="width:100%" placeholder="Ketik Nama Pemilik Rekening" value="' . $data_spp[0]->namaRek . '" required>';
						} else {
							echo $data_spp[0]->namaRek;
						}
						?>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<font color="white">i </font>
					</td>
				</tr>
				<tr>
					<td>Link Lampiran </td>
					<td>:&nbsp;</td>
					<td>
						<?php
						if ($data_spp[0]->idPengaju == sessPenggunaId()) {
							echo ' 	<input type="text" id="lampiran" name="lampiran" Style="width:100%" placeholder="Ketik Link Lampiran" value="' . $data_spp[0]->lampiran . '" required>
									 ';
						} else {
							echo ' <a href="' . $data_spp[0]->lampiran . '" class="btn btn-sm btn-primary"> Cek Lampiran </a>&nbsp; ';
						}
						?>
					</td>
				</tr>
				<tr>
					<th></th>
					<td></td>
					<td>
						<?php
						if ($data_spp[0]->idPengaju == sessPenggunaId()) {
							echo ' <a href="' . $data_spp[0]->lampiran . '" class="btn btn-sm btn-primary"> Cek Lampiran </a> ';
						} else {
							echo ' <font color="white"> i </font> ';
						}
						?>
					</td>
				</tr>
				</tr>
			</table>

			<br>
			<?php if (sessPenggunaId() != '54') { ?>
				<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
					<tr>
					<tr>
						<th bgcolor="#b7d5ac" colspan="2" style="text-align:center">Catatan Direktur </th>
						<td bgcolor="#b7d5ac" colspan="3">:&nbsp;&nbsp; <?= $data_spp[0]->catatan ?> </td>
					</tr>
					</tr>
				</table>
			<?php } ?>
			<br>
			<?php if (sessPenggunaId() == '54') { ?>
				<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
					<tr>
						<th bgcolor="" colspan="2" style="text-align:center">Catatan Direktur : </th>
						<td bgcolor="" colspan="3"><input type="text" name="catatan" id="catatan" placeholder=" &nbsp;Inputkan catatan anda" required></td>
					</tr>
				</table>
				<?php if ($data_spp[0]->catatan != '') { ?>
					<table id="kt_table_77" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
						<tr>
						<tr>
							<th bgcolor="#b7d5ac" colspan="2" style="text-align:center">Catatan Direktur </th>
							<td bgcolor="#b7d5ac" colspan="3">:&nbsp;&nbsp; <?= $data_spp[0]->catatan ?> </td>
						</tr>
						</tr>
					</table>
				<?php } ?>
			<?php } ?>

			<br>
			<br>

			<table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
							<?= $data_spp[0]->kota_pengajuan ?>, <?= $tgl_pengajuan ?>
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
						$ttdaju		= $img_path . "ttd_" . $data_spp[0]->idPengaju . ".png";
						$ttd1 		= $img_path . "ttd_notyet2.png";
						$ttd2 		= $img_path . "ttd_notyet2.png";
						$ttd3 		= $img_path . "ttd_notyet2.png";

						if ($data_spp[0]->aju_ttd1 == '1') {
							$ttd1 = $img_path . "ttd_33.png";
						} else if ($data_spp[0]->aju_ttd1 == '2') {
							$ttd1 = $img_path . "ttd_not.png";
						}
						if ($data_spp[0]->aju_ttd2 == '1') {
							$ttd2 = $img_path . "ttd_23.png";
						} else if ($data_spp[0]->aju_ttd2 == '2') {
							$ttd2 = $img_path . "ttd_not.png";
						}
						if ($data_spp[0]->aju_ttd3 == '1') {
							$ttd3 = $img_path . "ttd_54.png";
						} else if ($data_spp[0]->aju_ttd3 == '2') {
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
						<th style="text-align:center; "><?= $data_spp[0]->nama_ttd ?>
							<hr>
							</hr>
						</th>
						<td style="text-align:center; "></td>
						<th style="text-align:center; ">Yolanda Pratiwi
							<hr>
							</hr>
						</th>
						<td style="text-align:center; "></td>
						<th style="text-align:center; ">Meilina Safitri
							<hr>
							</hr>
						</th>
						<td style="text-align:center; "></td>
						<th style="text-align:center; ">Bob Ariyos
							<hr>
							</hr>
						</th>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_spp[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>General Manager</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corporate Planning & Business Management</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director</i></td>
					</tr>
				</tbody>
			</table>
			<?= form_close(); ?>
			<br><br>
			<div width="100%">
				<?php if (sessPenggunaId() == '1' || sessPenggunaId() != $data_spp[0]->idPengaju) { ?>
					<button type="button" class="btn btn-success btn-save float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-spp="<?= encrypt($data_spp[0]->idSpp) ?>"> <i class="fas fa-check"></i> Setujui </button>
					<button type="button" class="btn btn-secondary btn-save float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-spp="<?= encrypt($data_spp[0]->idSpp) ?>"> <i class="fas fa-times"></i> Tolak </button>
				<?php } ?>
				<?php if (sessPenggunaId() == '54') { ?>
					<button type="button" class="btn btn-primary float-right btn-catatan" style="margin-left:12px; margin-top:12px;" id-spp="<?= encrypt($data_spp[0]->idSpp) ?>"> <i class="fas fa-check"></i> Submit Catatan </button>
				<?php } ?>
				<a href="surat/print_page/spp/<?= $data_spp[0]->idSpp ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
				<?php if (isAdmin()): ?>
					<button type="button" onclick="openAdminEditModal('spp_pembayaran', '<?= encrypt($data_spp[0]->idSpp) ?>')" class="btn btn-warning float-right font-weight-bold text-dark" style="margin-left:12px; margin-top:12px;">
						<i class="fas fa-user-shield mr-1"></i> Edit Data (Admin)
					</button>
				<?php endif; ?>
				<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" style="margin-left:12px; margin-top:12px;" data-dismiss="modal">Kembali</button>
				<br>
			</div>
			<br>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {

		var level_ttd = $('#level_ttd').val();

		$(document).on('click', '.btn-ajukan', function() {
			var kota_aju = $('#drop_kota').val();
			const isi = kota_aju.split("PengHubunG");
			kota_aju = isi[1];
			var tgl = $('#tgl').val();
			var tgl_aju = $('#pengajuan').val();
			var terbilang = $('#terbilang').val();
			var bank = $('#bank').val();
			var norek = $('#norek').val();
			var nama = $('#pengaju').val();
			var lampiran = $('#lampiran').val();

			let itung_isi = $('#itung').val();
			let ket = [];
			let keterangan = [];
			let nom = [];
			let nominal = [];
			for (let i = 1; i <= itung_isi; i++) {
				if ($('#keterangan_' + i).val() != "") {
					ket[i] = $('#keterangan_' + i).val();
					nom[i] = $('#nominal_' + i).val();
				}
			}
			let itung = ket.length;

			Swal.fire({
				//title: approval + ' absensi?',
				title: 'Ajukan Permintaan Pembayaran?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/add/spp',
						dataType: 'JSON',
						data: {
							kota_aju: kota_aju,
							itung: itung,
							nama: nama,
							lampiran: lampiran,
							tgl_aju: tgl_aju,
							terbilang: terbilang,
							bank: bank,
							norek: norek,
							tgl: tgl,
							keterangan: ket,
							nominal: nom,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
						}
					})
				}
			})
		})

		$(document).on('click', '.btn-approval', function() {
			const id_spp = $(this).attr("id-spp");

			Swal.fire({
				//title: approval + ' absensi?',
				title: 'Setujui Permintaan Pembayaran?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/spp/1/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_spp: id_spp,
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

		$(document).on('click', '.btn-catatan', function() {
			var catatan = $('#catatan').val();
			var id = $('#id-spp').val();
			// var level_ttd = $('#level_ttd').val();
			console.log(catatan);
			Swal.fire({
				title: 'Data telah tepat?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					console.log('' + catatan);
					$.ajax({
						method: 'POST',
						//url: 'surat/updatecatatanSpp/'+id+'/'+catatan,
						url: 'surat/updatecatatanSpp/' + id,
						dataType: 'JSON',
						data: {
							catatan: catatan,
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
			const id_spp = $(this).attr("id-spp")
			Swal.fire({
				title: 'Tolak Permintaan Pembayaran?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/spp/2/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_spp: id_spp,
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

	})

	function goBack() {
		window.history.back();
	}
</script>