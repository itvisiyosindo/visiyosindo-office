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

		.mylabel {
			border: 0px solid blue;
			display: table-cell;
			width: 100%;
		}

		input {
			width: 100%;
			height: auto;
			border: 0px dotted #f30;
			border-radius: 4px;
			-moz-border-radius: 8px;
			margin-right: 0px;
		}
	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
		<div class="text-right">
			<?php
			if ($data_pb[0]->persetujuan_ga == 0) {
				echo '<span class="btn btn-warning"><i class="fas fa-info"></i>&nbsp; Baru Diajukan</span>';
			} else if ($data_pb[0]->persetujuan_ga == 1) {
				echo '<span class="btn btn-success"><i class="fas fa-check"></i>&nbsp; Disetujui GA</span>';
			} else if ($data_pb[0]->persetujuan_ga == 2) {
				echo '<span class="btn btn-danger"><i class="fas fa-times"></i>&nbsp; Ditolak GA</span>';
			}
			?>
		</div>
		<div class="table-responsive">
			<div class="text-center">
				<h2>
					<font color='#000000' face='Times New Roman'>No. Pengajuan : <?= $data_pb[0]->kodePB ?></font>
				</h2>
			</div>
			<?php
			$ttd = 'ttd_1';
			if (sessPenggunaId() == '107') {
				$ttd = 'ttd_1';
			} else if ((sessPenggunaId() == '33')) {
				$ttd = 'ttd_2';
			} else if ((sessPenggunaId() == '23')) {
				$ttd = 'ttd_3';
			} else if (isGa()) {
				$ttd = 'persetujuan_ga';
			}
			?>
			<input type="hidden" id="level_ttd" value="<?= $ttd ?>">
			<font color='#000000'>
				<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
					<tr>
						<td colspan="3" style="text-align:left">
							<font color='#000000'>Dengan ini saya memberikan laporan pertanggungjawaban biaya perjalanan dinas :</font>
						</td>
					</tr>
					<tr>
						<font color='#ffffff'>
							<?=
							$dt_pengajuan	= strtotime($data_pb[0]->tglLaporan);
							$dt_pergi		= strtotime($data_pb[0]->tglPergi);
							$dt_pulang		= strtotime($data_pb[0]->tglKembali);

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
						<td>:&nbsp;&nbsp; <?= $data_pb[0]->pengaju ?> </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp; <?= $data_pb[0]->jabatan ?></td>
					</tr>
					<tr>
						<td>Kota Tujuan </td>
						<td>:&nbsp;&nbsp; <?= $data_pb[0]->kota ?></td>
					</tr>
					<tr>
						<td>Keperluan </td>
						<td>:&nbsp;&nbsp; <?= $data_pb[0]->perihal ?></td>
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
					<?php
					$ttdAccounting = $data_pb[0]->lap_ttd1;
					?>
				</table>

				<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
					<thead>
						<tr>
							<th style="text-align:center" bgcolor="#b7d5ac" width="20%"> Tanggal </th>
							<th style="text-align:center" bgcolor="#b7d5ac"> Keterangan </th>
							<th style="text-align:center" bgcolor="#b7d5ac" width="25%"> Nominal </th>
							<th style="text-align:center" bgcolor="#b7d5ac" width="10%"> Aksi </th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						$nom = 0;
						$nom_es = 0;

						foreach ($estimasi as $row) {
							$nom_es = (int) $row->nominal + $nom_es;
						}

						foreach ($detail_pb as $row) {
							$x = $x + 1;
							$nom = (int) $row->nominal + $nom;


							$id_edit = encrypt($row->idDetailPB);
						?>
							<tr>
								<td class="tgl" style="text-align:center;"><?= $row->tgl ?></td>
								<td class="ket"><?= $row->ket ?></td>
								<td class="nom">
									<label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
									<label class="mylabel" style="text-align:right"><?= number_format($row->nominal, 0, ",", ".") ?>,-&nbsp;</label>
								</td>
								<td style="text-align:center">
									<?php if ($ttdAccounting == '') { ?>
										<button type="button" class="btn btn-sm btn-primary btn-edit-pencapaian" data-id="<?= $id_edit ?>"><i class="bx bx-pencil"></i></button>
										<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="<?= $row->idDetailPB ?>" data-object="surat/deleteLapPB/<?= $row->idDetailPB ?>"> <i class="bx bx-trash"></i> </button>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
						<?php
						if ($catatan_finance) {
							$x_cat_finance = 1;
							foreach ($catatan_finance as $row) {
								if ($x_cat_finance == 1) {
									$kategori = "Catatan Finance";
								} else {
									$kategori = "";
								}
								$x_cat_finance = $x_cat_finance + 1;
								echo '
    							            <tr>
                                                <td style="text-align:center">' . $kategori . '</td>
                                                <td>' . $row->ket . '</td>
                                                <td> </td>
                                            </tr>
    							        ';
							}
							$baris = 1;
							for ($kosong = ($x + $x_cat_finance); $kosong <= 30; $kosong++) {
								if (sessPenggunaId() == '107' || sessPenggunaId() == '106') {
									$catatan    = "Tambah catatan finance";
									echo '
            						            <tr>
                                                    <td></td>
                                                    <td><input type="text" id="catatan_finance_' . $baris . '" name="catatan_finance_' . $baris . '" Style="width:100%" placeholder="' . $catatan . '" required></td>
                                                    <td></td>
                                                    </tr>
            							    ';
								} else {
									echo '
                                                <tr>
                                                    <td> <font color="white">i </font> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                </tr>
                                            ';
								}
								$baris = $baris + 1;
							}
							$jml_input = 30 - $x + $x_cat_finance;
							echo '<input type="hidden" id="itung" value="' . $jml_input . '">';
						} else {
							$baris = 1;
							for ($kosong = $x; $kosong <= 30; $kosong++) {
								if ($baris == 1) {
									$kategori = "Catatan Finance";
								} else {
									$kategori = "";
								}
								if (sessPenggunaId() == '107' || sessPenggunaId() == '106') {
									$catatan    = "Tambah catatan finance";
									echo '
            							        <tr>
                                                    <td style="text-align:center">' . $kategori . '</td>
                                                    <td><input type="text" id="catatan_finance_' . $baris . '" name="catatan_finance_' . $baris . '" Style="width:100%" placeholder="' . $catatan . '" required></td>
                                                    <td></td>
                                                </tr>
            							    ';
								} else {
									echo '
                                                <tr>
                                                    <td> <font color="white">i </font> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                </tr>
                                            ';
								}
								$baris = $baris + 1;
							}
							$jml_input = 30 - $x;
							echo '<input type="hidden" id="itung" value="' . $jml_input . '">';
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th bgcolor="#b7d5ac" colspan="2" style="text-align:center"> TOTAL DILAPORKAN </th>
							<td bgcolor="#b7d5ac">
								<label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
								<label class="mylabel" style="text-align:right"><?= number_format($nom, 0, ",", ".") ?>,-&nbsp;</label>
							</td>
							<td></td>
						</tr>
						<tr>
							<th bgcolor="#b7d5ac" colspan="2" style="text-align:center"> TOTAL ESTIMASI </th>
							<td bgcolor="#b7d5ac">
								<label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
								<label class="mylabel" style="text-align:right"><?= number_format($nom_es, 0, ",", ".") ?>,-&nbsp;</label>
							</td>
							<td></td>
						</tr>
						<tr>
							<th bgcolor="#b7d5ac" colspan="2" style="text-align:center"> TOTAL KELEBIHAN/KEKURANGAN </th>
							<td bgcolor="#b7d5ac">
								<label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
								<label class="mylabel" style="text-align:right"><?= number_format($nom - $nom_es, 0, ",", ".") ?>,-&nbsp;</label>
							</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
				<table border="0">
					<tr>
						<td width="7%">
						<td style="text-align:justify; text-justify:inter-word;">
							<font style="font-family:Times New Roman; font-size:15px;">
								Saya membuat laporan pertanggungjawaban penggunaan biaya dengan melampirkan Struk
								/ Bon biaya terkait dan laporan perjalanan dinas dan akan diberikan kepada bagian keuangan.
							</font>
						</td>
						<td width="7%">
					</tr>
				</table>
				<table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
					<tbody>
						<tr style="height: 35px;">
							<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
								<?= $data_pb[0]->kota_pengajuan ?>, <?= $tgl_pengajuan ?>
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
							$ttdaju		= $img_path . "ttd_" . $data_pb[0]->idPengaju . ".png";
							$ttd1 		= $img_path . "ttd_notyet2.png";
							$ttd2 		= $img_path . "ttd_notyet2.png";
							$ttd3 		= $img_path . "ttd_notyet2.png";

							if ($data_pb[0]->lap_ttd1 == '1') {
								$ttd1 = $img_path . "ttd_107.png";
							} else if ($data_pb[0]->lap_ttd1 == '2') {
								$ttd1 = $img_path . "ttd_not.png";
							}
							if ($data_pb[0]->lap_ttd2 == '1') {
								$ttd2 = $img_path . "ttd_33.png";
							} else if ($data_pb[0]->lap_ttd2 == '2') {
								$ttd2 = $img_path . "ttd_not.png";
							}
							if ($data_pb[0]->lap_ttd3 == '1') {
								$ttd3 = $img_path . "ttd_23.png";
							} else if ($data_pb[0]->lap_ttd3 == '2') {
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
							<td style="text-align:center; "><?= $data_pb[0]->nama_ttd ?>
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
							<td style="text-align:center; vertical-align:top;"><i><?= $data_pb[0]->jabatan ?></i></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;"><i>Senior Accounting and Finance</i></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;"><i>General Manager</i></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;"><i>Director of Corp Planning & Bussinees Management</i></td>
						</tr>
					</tbody>
				</table>
				<br><br>
				<div role="document">
					<input type="hidden" id="id_pb_to_all" value="<?= $ambil_id_pb ?>">
					<!-- <?php if (sessPenggunaId() == '33' || sessPenggunaId() == '1' || sessPenggunaId() == '23' || sessPenggunaId() != $data_pb[0]->idPengaju) { ?>
				<button type="button" class="btn btn-success btn-save float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-pb="<?= encrypt($data_pb[0]->idPB) ?>"> <i class="fas fa-check"></i> Setujui </button>
				<?php if (sessPenggunaId() == '107' || sessPenggunaId() == '106') { ?>
            	    <button type="button" class="btn btn-success float-right btn-submit-catatan" style="margin-left:12px; margin-top:12px;" id-pb-up="<?= encrypt($data_pb[0]->idPB) ?>"> <i class="fas fa-check"></i> Tambah Catatan </button>
            	<?php } ?>
				<button type="button" class="btn btn-secondary btn-save float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-pb="<?= encrypt($data_pb[0]->idPB) ?>"> <i class="fas fa-times"></i> Tolak </button>				
            <?php } ?> -->

					<!-- Logic TTD GA -->
					<?php if (sessPenggunaId() == '58') { ?>
						<?php if ($data_pb[0]->persetujuan_ga == 0) { ?>
							<button type="button" class="btn btn-success btn-save float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-pb="<?= encrypt($data_pb[0]->idPB) ?>"> <i class="fas fa-check"></i> Setujui </button>
							<button type="button" class="btn btn-secondary btn-save float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-pb="<?= encrypt($data_pb[0]->idPB) ?>"> <i class="fas fa-times"></i> Tolak </button>
						<?php } ?>
					<?php } ?>

					<!-- Logic TTD Accounting -->
					<?php if (sessPenggunaId() == '107' || sessPenggunaId() == '106') { ?>
						<?php if ($data_pb[0]->lap_ttd1 == '') { ?>
							<button type="button" class="btn btn-success btn-save float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-pb="<?= encrypt($data_pb[0]->idPB) ?>"> <i class="fas fa-check"></i> Setujui </button>
							<button type="button" class="btn btn-secondary btn-save float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-pb="<?= encrypt($data_pb[0]->idPB) ?>"> <i class="fas fa-times"></i> Tolak </button>
						<?php } ?>
						<button type="button" class="btn btn-success float-right btn-submit-catatan" style="margin-left:12px; margin-top:12px;" id-pb-up="<?= encrypt($data_pb[0]->idPB) ?>"> <i class="fas fa-check"></i> Tambah Catatan </button>
					<?php } ?>

					<!-- Logic TTD GM -->
					<?php if (sessPenggunaId() == '33') { ?>
						<?php if ($data_pb[0]->lap_ttd2 == '') { ?>
							<button type="button" class="btn btn-success btn-save float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-pb="<?= encrypt($data_pb[0]->idPB) ?>"> <i class="fas fa-check"></i> Setujui </button>
							<button type="button" class="btn btn-secondary btn-save float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-pb="<?= encrypt($data_pb[0]->idPB) ?>"> <i class="fas fa-times"></i> Tolak </button>
						<?php } ?>
					<?php } ?>

					<!-- Logic TTD Dir Bisnis -->
					<?php if (sessPenggunaId() == '23') { ?>
						<?php if ($data_pb[0]->lap_ttd3 == '') { ?>
							<button type="button" class="btn btn-success btn-save float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-pb="<?= encrypt($data_pb[0]->idPB) ?>"> <i class="fas fa-check"></i> Setujui </button>
							<button type="button" class="btn btn-secondary btn-save float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-pb="<?= encrypt($data_pb[0]->idPB) ?>"> <i class="fas fa-times"></i> Tolak </button>
						<?php } ?>
					<?php } ?>


					<a href="surat/print_page/laporan_PB/<?= $data_pb[0]->idPB ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
					<?php if ($data_pb[0]->lampiran_2 != "") { ?>
						<a href="<?= $data_pb[0]->lampiran_2 ?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran </a>
					<?php } ?>
					<?php if (isAdmin()): ?>
						<button type="button" onclick="openAdminEditModal('pb_laporan', '<?= encrypt($data_pb[0]->idPB) ?>')" class="btn btn-warning float-right font-weight-bold text-dark" style="margin-left:12px; margin-top:12px;">
							<i class="fas fa-user-shield mr-1"></i> Edit Data (Admin)
						</button>
					<?php endif; ?>
					<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" data-dismiss="modal" style="margin-left:12px; margin-top:12px;">Kembali</button>
				</div>
				<br><br>
		</div>
		<br>
		Note : Mohon Verifikasi terlebih dahulu sebelum Setujui/Tolak, Karena tidak dapat diubah
	</div>
</div>



<div id="main-modal-pencapaian" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Detail Biaya Dinas</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-pencapaian', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">


					<div class="form-group">
						<label for="tanggal_lap" class="form-control-label">Tanggal <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tanggal_lap" name="tanggal_lap" required>
						</div>
					</div>


					<div class="form-group">
						<label for="keterangan_lap" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
						<textarea class="form-control" placeholder="Masukkan Detail Pencapaian Anda" name="keterangan_lap" id="keterangan_lap" cols="10" rows="5"></textarea>
					</div>

					<div class="form-group">
						<label for="nominal_lap" class="form-control-label">Nominal <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nominal_lap" name="nominal_lap" required>
					</div>



				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		var level_ttd = $('#level_ttd').val();

		$(document).on('click', '.btn-approval', function() {
			const id_pb = $(this).attr("id-pb")
			Swal.fire({
				//title: approval + ' absensi?',
				title: 'Setujui Laporan Biaya Dinas?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/laporan_pb/1/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_pb: id_pb,
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
			const id_pb = $(this).attr("id-pb");
			Swal.fire({
				title: 'Tolak Laporan Biaya Dinas?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/laporan_pb/2/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_pb: id_pb,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
						}
					})
				}
			})
		})

		$(document).on('click', '.btn-submit-catatan', function() {
			//const id_pb         = $(this).attr("id-pb-up");
			const id_pb = $('#id_pb_to_all').val();
			let itung_isi = $('#itung').val();
			let ket = [];
			let catatan_finance = [];
			for (let i = 1; i < itung_isi; i++) {
				if ($('#catatan_finance_' + i).val() != "") {
					ket[i] = $('#catatan_finance_' + i).val();
				}
			}
			let itung = ket.length;

			Swal.fire({
				title: 'Tambah Catatan Finance?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/update/lpb/' + id_pb,
						dataType: 'JSON',
						data: {
							id_pb: id_pb,
							itung: itung,
							catatan_finance: ket,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
						}
					})
				}
			})
		})




		//Edit Laporan Sebelum di Approve
		$('#btn-show-add-form-pencapaian').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'surat'
			$('#main-modal-pencapaian #modal-form-pencapaian').attr('action', 'surat/addLapPB')
			$('#main-modal-pencapaian').modal()
		})

		$(document).on('click', '.btn-edit-pencapaian', function() {
			$('.btn-isactive').remove()
			var object = 'surat'
			$('#main-modal-pencapaian #modal-form-pencapaian').attr('action', 'surat/updateLapPB')
			$('#main-modal-pencapaian').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/editLapPB/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal-pencapaian #tanggal_lap').val(data[0].tanggal)
					$('#main-modal-pencapaian #keterangan_lap').val(data[0].keterangan)
					$('#main-modal-pencapaian #nominal_lap').val(data[0].nominal)
					$('#main-modal-pencapaian #id_pelanggan').val(id)
				})
		})

	})

	function goBack() {
		window.history.back();
	}
</script>