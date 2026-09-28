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
			/* font-family:Garamond;
			//background:#363; */
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
			/* //background:#b7d5ac; */
		}

		.mydiv br {
			display: none;
		}

		.mydiv p {
			padding: 0;
			margin: 0;
		}

		/* --- START CSS Tambahan untuk Detail TLD --- */
		.header-table-tld {
			background-color: #b7d5ac !important;
			/* Warna hijau muda dari header tabel atas */
			text-align: center;
			font-weight: bold;
		}

		.tld-display-value {
			font-weight: bold;
			/* Menonjolkan nilai yang dipilih/diisi */
		}

		/* --- END CSS Tambahan untuk Detail TLD --- */
	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
		<div class="table-responsive">
			<div class="text-center mt-0">
				<h2>
					<font color='#000000' face='Times New Roman'>No : <?= $data_fpp[0]->kode_fpp ?></font>
				</h2>
			</div>
			<font color='#000000'>

				<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
					<tr>
						<td colspan="3" style="text-align:left">
							<font color='#000000'>Dengan ini saya mengajukan permintaan penawaran :</font>
						</td>
					</tr>
					<tr>

			</font>

			<font color='#ffffff'>
				<?=

				$dt_pengajuan	= strtotime($data_fpp[0]->tgl_Pengajuan);
				$tgl_pengajuan 	= date("d", $dt_pengajuan) . " - " . date("m", $dt_pengajuan) . " - " . date("Y", $dt_pengajuan);

				?>

			</font>
			<td rowspan="12" width="7%"></td>

			<input type="hidden" id="idpengaju" value="<?= $data_fpp[0]->idPengaju ?>">
			<tr>
				<td width="18%">Nama </td>
				<td>:&nbsp;&nbsp; <?= $data_fpp[0]->pengaju ?> </td>
			</tr>
			<tr>
				<td>Jabatan </td>
				<td>:&nbsp;&nbsp; <?= $data_fpp[0]->jabatan ?></td>
			</tr>

			<tr>
				<td>Customer Name </td>
				<td>:&nbsp;&nbsp; <?= $data_fpp[0]->csName ?></td>
			</tr>
			<tr>
				<td>Alamat </td>
				<td>:&nbsp;&nbsp; <?= $data_fpp[0]->alamat ?></td>
			</tr>
			<tr>
				<td>Tanggal </td>
				<td>:&nbsp;&nbsp; <?= $data_fpp[0]->tanggal ?></td>
			</tr>
			<tr>
				<td>Contact Person Name</td>
				<td>:&nbsp;&nbsp; <?= $data_fpp[0]->cpName ?></td>
			</tr>
			<tr>
				<td>No CP </td>
				<td>:&nbsp;&nbsp; <?= $data_fpp[0]->noCp ?></td>
			</tr>

			<tr>
				<td>Term Of Payment </td>
				<td>:&nbsp;&nbsp; <?= $data_fpp[0]->paYment ?></td>
			</tr>

			<tr>
				<td>Tipe Cicilan </td>
				<td>:&nbsp;&nbsp; <?= $data_fpp[0]->cicilan ?></td>
			</tr>

			<tr>
				<td>Ongkos Kirim </td>
				<td>:&nbsp;&nbsp; <?= $data_fpp[0]->ongkir ?></td>
			</tr>

			<tr>
				<td>Pajak </td>
				<td>:&nbsp;&nbsp; <?= $data_fpp[0]->pajak ?></td>
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
						<th style="text-align:center" bgcolor="#b7d5ac" width="5%"> No </th>
						<th style="text-align:center" bgcolor="#b7d5ac"> Description </th>
						<th style="text-align:center" bgcolor="#b7d5ac" width="7%"> Qty </th>
						<th style="text-align:center" bgcolor="#b7d5ac" width="15%"> Unit Price (IDR) </th>
						<th style="text-align:center" bgcolor="#b7d5ac" width="7%"> Disc </th>
						<th style="text-align:center" bgcolor="#b7d5ac" width="7%"> Komisi User </th>
						<th style="text-align:center" bgcolor="#b7d5ac" width="12%"> Nama User </th>
						<th style="text-align:center" bgcolor="#b7d5ac" width="7%"> Komisi Pihak Ke-3 </th>
						<th style="text-align:center" bgcolor="#b7d5ac" width="12%"> Nama Pihak Ke-3 </th>
					</tr>
				</thead>
				<tbody>
					<?php
					$x = 1;
					$xy = 0;
					foreach ($detail_fpp as $row) {
						$x = $x + 1;
						$xy = $xy + 1;
					?>
						<tr>
							<td style="text-align:center">
								<?= $xy ?>
							</td>
							<td class="des">&nbsp;<?= $row->des ?>&nbsp;</td>
							<td style="text-align:center" class="qty">&nbsp;<?= $row->Qty ?>&nbsp;</td>
							<?php if ($row->pri != '') { ?>
								<td class="nom" style="text-align:right">
									<font color='#000000'>
										<label class="mylabel2" style="text-align:left">&nbsp;Rp. </label>
										<label class="mylabel" style="text-align:right"><?= number_format($row->pri, 0, ",", ".") ?>,-&nbsp;</label>
								</td>
							<?php } else { ?>
								<td class="nom" style="text-align:center">
									<font color='#000000'>
								</td>
							<?php }  ?>
							<td style="text-align:center" class="dis">&nbsp;<?= $row->dis ?></td>
							<td style="text-align:center" class="kom">&nbsp;<?= $row->kom ?></td>
							<td style="text-align:center" class="kom">&nbsp;<?= $row->usr ?></td>
							<td style="text-align:center" class="kom">&nbsp;<?= $row->komtiga ?></td>
							<td style="text-align:center" class="kom">&nbsp;<?= $row->nmtiga ?></td>
						</tr>
					<?php } ?>
					<?php for ($kosong = $x; $kosong <= 20; $kosong++) { ?>
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
						</tr>
					<?php } ?>

				</tbody>

			</table>
			<br>
			<h4 style="font-family:Times New Roman; color:black; margin-top: 15px;">Kondisi Penawaran TLD</h4>
			<table id="table_tld_detail" style="font-family:Times New Roman; font-size:15px" border="1" width="100%">
				<thead>
					<tr>
						<th class="header-table-tld" width="70%">Kondisi Penawaran</th>
						<th class="header-table-tld" width="30%">Isi Form</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Apa Jenis Pembelian TLD</td>
						<td class="tld-display-value" style="text-align: center;"><?= $data_fpp[0]->jenis_pembelian_tld ?></td>
					</tr>
					<tr>
						<td>Berapa Jumlah Pekerja Radiasi ? (SEBUTKAN DALAM ANGKA)</td>
						<td class="tld-display-value" style="text-align: center;"><?= $data_fpp[0]->jumlah_pekerja_radiasi ?></td>
					</tr>
					<tr>
						<td>Include Zero Check ? (YA/TIDAK)</td>
						<td class="tld-display-value" style="text-align: center;"><?= $data_fpp[0]->include_zero_check ?></td>
					</tr>
					<tr>
						<td>Apakah sudah memiliki TLD Kontrol ? (YA/TIDAK)</td>
						<td class="tld-display-value" style="text-align: center;"><?= $data_fpp[0]->sudah_memiliki_tld_kontrol ?></td>
					</tr>
					<tr>
						<td>Apakah membutuhkan TLD Kontrol Baru? (YA/TIDAK)</td>
						<td class="tld-display-value" style="text-align: center;"><?= $data_fpp[0]->membutuhkan_tld_kontrol_baru ?></td>
					</tr>
					<tr>
						<td>Apakah TLD yang di tawarkan sudah include TLD Kontrol ? (YA/TIDAK)</td>
						<td class="tld-display-value" style="text-align: center;"><?= $data_fpp[0]->tld_include_tld_kontrol ?></td>
					</tr>
					<tr>
						<td>Apakah user sudah pernah terdaftar di Lab Dosimetri yang sama? (YA/TIDAK)</td>
						<td class="tld-display-value" style="text-align: center;"><?= $data_fpp[0]->user_terdaftar_lab_dosimetri ?></td>
					</tr>
					<tr>
						<td>Instansi Menyetujui estimasi pengerjaan Zero Check &plusmn;14 hari kerja ? (YA/TIDAK)</td>
						<td class="tld-display-value" style="text-align: center;"><?= $data_fpp[0]->setuju_estimasi_zero_check ?></td>
					</tr>
				</tbody>
			</table>

			<br>
			      <table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">

				<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">

					<input class="no-outline" type="hidden" id="id_fpp" name="id_fpp" value="<?= $data_fpp[0]->idFpp ?>">
					<input class="no-outline" type="hidden" id="status" name="status" value="2">

					<tr>
					<tr>
						<td width="10%">Notes </td>
						<td>:&nbsp;&nbsp;<?= $data_fpp[0]->noTes ?></td>
					</tr>
					<tr>
						<td width="10%" height="35px"> </td>
					</tr>
					<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '72' || sessPenggunaId() == '754') {   ?>

						<?php if ($data_fpp[0]->sph == "" && $data_fpp[0]->approval == "") { ?>
							<tr>
								<td width="10%">No SPH </td>
								<td>:&nbsp;&nbsp;<input id='no_sph' type="text" placeholder='Klik Untuk Memasukkan No SPH' required></td>
							</tr>
							<tr>
								<td width="10%">Link SPH </td>
								<td>:&nbsp;&nbsp;<input id='link_sph' type="text" placeholder='Klik Untuk Memasukkan Link SPH' required></td>
							</tr>
						<?php } elseif ($data_fpp[0]->sph != "" && $data_fpp[0]->approval == "") { ?>
							<tr>
								<td width="10%">No Approval </td>
								<td>:&nbsp;&nbsp;<input id='no_approval' type="text" placeholder='Klik Untuk Memasukkan No Approval' required></td>
							</tr>
							<tr>
								<td width="10%">Link Approval </td>
								<td>:&nbsp;&nbsp;<input id='link_approval' type="text" placeholder='Klik Untuk Memasukkan Link Approval' required></td>
							</tr>
						<?php } ?>
					<?php	} ?>

					</tr>
				</table>

				<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">



					<tr>

					</tr>
				</table>


				<table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
					<tbody>

						<tr style="height: 35px;">
							<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="3">

								<?= $data_fpp[0]->kota_pengajuan ?>, <?= $tgl_pengajuan ?>
							</td>
						</tr>
						<tr style="height: 18px;">
							<td style="text-align:center; width:25.5%;"></td>
							<td style="text-align:center; width:44.5%;"></td>
							<td style="text-align:center; width:25.5%;">Diajukan Oleh,</td>
						</tr>
						<tr style="height:60px;">
							<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path . "ttd_" . $data_fpp[0]->idPengaju . ".png";
							$ttd1 		= $img_path . "ttd_blank.png";
							?>
							<td style="text-align:center;"> <?php echo '<img src="" height="70">'; ?> </td>
							<td style="text-align:center;"></td>
							<td style="text-align:center;"><?php echo '<img src="' . $ttdaju . '" height="70">'; ?></td>
						</tr>
						<tr>
							<td style="text-align:center; "></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; "><?= $data_fpp[0]->nama_ttd ?>
								<hr>
								</hr>
							</td>
						</tr>
						<tr>
							<td style="text-align:center; vertical-align:top;"><i></i></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;"><i><?= $data_fpp[0]->jabatan ?></i></td>
						</tr>
					</tbody>

				</table>
		</div>

		<br><br>
		<div width="100%">
			<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '72' || sessPenggunaId() == '754') { ?>

				<?php if ($data_fpp[0]->status == 0) { ?>
					<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-fpp="<?= encrypt($data_fpp[0]->idFpp) ?>"> <i class="fas fa-times"></i> Tolak </button>
				<?php } ?>
				<?php if ($data_fpp[0]->sph == "" && $data_fpp[0]->approval == "") { ?>
					<button type="button" class="btn btn-primary float-right btn-submit1" style="margin-left:12px; margin-top:12px;" id-fpp="<?= encrypt($data_fpp[0]->idFpp) ?>"> <i class="fas fa-check"></i> Submit (SPH) </button>
				<?php } elseif ($data_fpp[0]->sph != "" && $data_fpp[0]->approval == "") { ?>
					<button type="button" class="btn btn-primary float-right btn-submit2" style="margin-left:12px; margin-top:12px;" id-fpp="<?= encrypt($data_fpp[0]->idFpp) ?>"> <i class="fas fa-check"></i> Submit (Approval)</button>
				<?php } ?>
			<?php } ?>
			<a href="fpp/print_page/fpp/<?= $data_fpp[0]->idFpp ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
			<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" style="margin-top:12px;" data-dismiss="modal">Kembali</button>

			<?php if ($data_fpp[0]->sph != "") { ?>
				<a href="<?= $data_fpp[0]->sph ?>" target="blank" class="btn btn-primary float-center" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran SPH</a>
			<?php } ?>
			<?php if ($data_fpp[0]->approval != "") { ?>
				<a href="<?= $data_fpp[0]->approval ?>" target="blank" class="btn btn-primary float-center" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran Aproval</a>
			<?php } ?>
			<br>
		</div>
		<br><br>
	</div>

</div>
</div>


<script>
	document.addEventListener('DOMContentLoaded', function() {


		$(document).on('click', '.btn-submit1', function() {
			var link_sph = $('#link_sph').val();
			var no_sph = $('#no_sph').val();
			var id_fpp = $('#id_fpp').val();

			// var level_ttd = $('#level_ttd').val();
			// console.log(approvall);
			Swal.fire({
				title: 'Link SPH telah tepat?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					// console.log('' + trf_komisi);
					$.ajax({
						method: 'POST',
						url: 'fpp/updateCro1/' + id_fpp,
						dataType: 'JSON',
						data: {
							link_sph: link_sph,
							no_sph: no_sph,
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
			var no_approval = $('#no_approval').val();
			var link_approval = $('#link_approval').val();
			var id_fpp = $('#id_fpp').val();

			// var level_ttd = $('#level_ttd').val();
			// console.log(approvall);
			Swal.fire({
				title: 'Link Approval telah tepat?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					// console.log('' + trf_komisi);
					$.ajax({
						method: 'POST',
						url: 'fpp/updateCro2/' + id_fpp,
						dataType: 'JSON',
						data: {
							no_approval: no_approval,
							link_approval: link_approval,
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
			var status = $('#status').val();
			var id_fpp = $('#id_fpp').val();

			// var level_ttd = $('#level_ttd').val();
			// console.log(approvall);
			Swal.fire({
				title: 'Tolak Pengajuan Permintaan Penawaran?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					// console.log('' + trf_komisi);
					$.ajax({
						method: 'POST',
						url: 'fpp/updateCro3/' + id_fpp,
						dataType: 'JSON',
						data: {
							status: status,
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