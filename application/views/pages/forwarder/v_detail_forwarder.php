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
					<font color='#000000' face='Times New Roman'>Approval Forwarder Luar Negeri</font>
				</h2>
				<h2>
					<font color='#000000' face='Times New Roman'>No : <?= $data_aprv[0]
																			->kode ?></font>
				</h2>
			</div><br>
			<font color='#000000'>
				<?php
				$ttd = "ttd_1";
				if (sessPenggunaId() == "23") {
					$ttd = "ttd_1";
				} elseif (sessPenggunaId() == "54") {
					$ttd = "ttd_2";
				}
				?>
				<input type="hidden" id="level_ttd" value="<?= $ttd ?>">
				<?php $id = $data_aprv[0]->idGc; ?>
				<input type="hidden" name="id" id="id" value="<?= $data_aprv[0]->idGc ?>">



				<?= form_open("surat_new/updateApprovalEks", [
					"id" => "main-form",
					"autocomplete" => "off",
				]) ?>

				<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
					<tr>
						<td colspan="3" style="text-align:left">
							<font color='#000000'>Dengan ini saya mengajukan Permintaan Approval Forwarder Luar Negeri :</font>
						</td>
					</tr>
					<tr>

			</font>

			<td rowspan="17" width="2%"></td>

			<input type="hidden" id="idpengaju" value="<?= $data_aprv[0]->id_pengguna ?>">
			<tr>
				<td width="15%">Nama </td>
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
			<?php
			$tanggal = $data_aprv[0]->tanggal;
			$bulanTahun = date("F Y", strtotime($tanggal));

			// ubah nama bulan Inggris ke Indonesia
			$bulanIndonesia = [
				"January" => "Januari",
				"February" => "Februari",
				"March" => "Maret",
				"April" => "April",
				"May" => "Mei",
				"June" => "Juni",
				"July" => "Juli",
				"August" => "Agustus",
				"September" => "September",
				"October" => "Oktober",
				"November" => "November",
				"December" => "Desember",
			];

			[$bulanInggris, $tahun] = explode(" ", $bulanTahun);
			$tanggalFormatted = $bulanIndonesia[$bulanInggris] . " " . $tahun;

			$kurs = "Rp " . number_format($data_aprv[0]->kurs, 0, ",", ".");
			?>
			<!--<tr>
						<td>Bulan </td>
						<td>:&nbsp;&nbsp; <?= $tanggalFormatted ?></td>
					</tr>-->
			<tr>
				<td>Nama Shipment</td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->nama ?></td>
			</tr>
			<tr>
				<td>Sistem Pengiriman</td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->sistem_pengiriman ?></td>
			</tr>
			<tr>
				<td>Port of Loading (POL)</td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->pol ?></td>
			</tr>
			<tr>
				<td>Port of Discharge (POD) </td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->pod ?></td>
			</tr>
			<?php
			$lines = explode("\n", $data_aprv[0]->berat_dimensi);
			$formatted = "";

			foreach ($lines as $i => $line) {
				if ($i == 0) {
					$formatted .= ":&nbsp;&nbsp;" . htmlspecialchars($line) . "<br>";
				} else {
					$formatted .=
						"&nbsp;&nbsp;&nbsp;" . htmlspecialchars($line) . "<br>";
				}
			}
			?>
			<tr>
				<td style="vertical-align: top;">Berat dan Dimensi Barang</td>
				<td><?= $formatted ?></td>
			</tr>
			<tr>
				<td>Nilai Invoice Shipment</td>
				<td>:&nbsp;&nbsp; <?= $data_aprv[0]->mata_nilai_inv .
										" " .
										$data_aprv[0]->nilai_inv ?></td>
			</tr>
			<tr>
				<td>Kurs 1 USD </td>
				<td>:&nbsp;&nbsp; <?= $kurs ?></td>
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
						<font color='#000000'>Detail perbandingan Forwarder sebagai berikut :</font>
					</td>
				</tr>

				<tr>
					<td>
						<font color="white">i </font>
					</td>
				</tr>

			</table>

			<?php
			$data = $detail_aprv;
			$forwarderCount = count($data);
			?>

			<table id="kt_table_1" style="font-family:Times New Roman; color:black; font-size:15px" border="1" width="100%">
				<thead>
					<tr>
						<!--<th style="text-align:center" bgcolor="#d3d3d3" width="3%"> No </th>-->
						<th style="text-align:center" bgcolor="#d3d3d3"> Nama Forwarder </th>
						<?php foreach ($forwarderNames as $name): ?>
							<th style="text-align:center" bgcolor="#d3d3d3"><?= htmlspecialchars(
																				$name,
																			) ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$keteranganList = [
						"EXW CHARGES<br>(Biaya di Negara Asal)" => null,
						"Terminal Handling Charge" => "asal_thc",
						"BL Fee" => "asal_bl_fee",
						"VGM Fee" => "asal_vgm",
						"Agency Fee / Admin Fee" => "asal_agaency_fee",
						"Handling Fee" => "asal_handling_fee",
						"Transportation / Trucking" => "asal_transportasi",
						"Loading / Unloading" => "asal_loading",
						"Custom Clearance" => "asal_custom",
						"Pick Up Fee" => "asal_pickup",
						//Tambahan
						"Seal Fee" => "asal_seal",
						"Shipping Line Charge" => "asal_shipping",
						"CFS (Origin)" => "asal_cfs",
						"DG Surcharges" => "asal_dg",
						"PSA Surcharges" => "asal_psa",
						"Other (Origin)" => "asal_other",

						"Freight<br>(Biaya Ongkos Kirim)" => null,
						"Ocean Freight" => "freight_ocean",
						"Air Freight" => "freight_air",

						"Destination Charges<br>(Biaya di Negara Tujuan)" => null,
						"CFS (Destination)" => "tuj_cfs",
						"DOC" => "tuj_doc",
						"AGENCY FEE" => "tuj_agency_fee",
						"HANDLING" => "tuj_handling",
						"DO Fee" => "tuj_do",
						"ADMIN" => "tuj_admin",
						"DEVANNING" => "tuj_devanning",
						"FORWARDING FEE" => "tuj_fordwarding_fee",
						"MECHANICSS" => "tuj_mechanics",
						"Other (Destination)" => "tuj_other",

						"Custom Clearance Charges<br>(Bea Cukai)" => null,
						"Custom Clearance Charges" => "cust_clearance",
						"Red Line / Labor (if any)" => "cust_red_line",
						"Handling Charges" => "cust_handling",
						"Admin Fee" => "cust_admin_fee",
						"PIB Fee" => "cust_pib_fee",
						"Transfer EDI" => "cust_transfer",
						"STORAGE + LIFT OF LIFT ON" => "cust_storage",
						"Asuransi Custom" => "cust_asu",
						"Other (Customs)" => "cust_other",

						"Other Charges" => null,
						//'DO' => 'oth_do',
						"Storage" => "oth_storage",
						"TRUCKING (Delivery Fee) (if any)" => "oth_trucking",
						"Handling Gudang" => "oth_handling",
						"Buruh (If Any)" => "oth_buruh",
						"Admin Gudang" => "oth_adm",
						"Other (Charges)" => "oth_other",
						"TOTAL ONGKOS KIRIM" => null,
						"PPN" => "ppn",

						"Insurance" => null,
						"Nilai Asuransi" => "ins_nilai",
						"Jenis Asuransi" => "ins_jenis",
						"FORWARDER YANG DIPILIH" => "dipilih",
						"Alasan" => "alasan",
						"Keterangan" => "keterangan_detail",
						"Link Invoice" => "link_invoice",
						"Link Packing List" => "link_packing",
						"Link SPH Forwarder" => "link_sph_for",
						"Link SPH Asuransi" => "link_sph_ins",
					];

					// Tambahkan ini: label yang ingin dibold
					$boldItems = [
						"EXW CHARGES<br>(Biaya di Negara Asal)",
						"Freight<br>(Biaya Ongkos Kirim)",
						"Destination Charges<br>(Biaya di Negara Tujuan)",
						"Custom Clearance Charges<br>(Bea Cukai)",
						"Other Charges",
						"TOTAL ONGKOS KIRIM",
						"Insurance",
						"FORWARDER YANG DIPILIH",
						"Aksi",
					];

					$no = 1;
					foreach ($keteranganList as $label => $kolomDb):

						$isBold = in_array($label, $boldItems);
						$style = $isBold
							? ' style="font-weight:bold; background-color:#e5e5e5;"'
							: "";
						$styleLabel =
							'style="text-align:center;' .
							($isBold ? "font-weight:bold; background-color:#e5e5e5;" : "") .
							'"';
					?>
						<tr>
							<!--<td align="center"<?= $style ?>><?= $no++ ?></td>-->
							<td <?= $styleLabel ?>><?= $label ?></td>


							<?php
							//foreach ($data as $row):
							?>
							<?php foreach (array_values($data) as $i => $row): ?>


								<?php
								$align = "right";
								if (
									$kolomDb === "ppn" ||
									$kolomDb === "ins_jenis" ||
									$kolomDb === "storage" ||
									$kolomDb === "dipilih" ||
									$kolomDb === "alasan" ||
									$kolomDb === "keterangan_detail" ||
									$kolomDb === "link_invoice" ||
									$kolomDb === "link_packing" ||
									$kolomDb === "link_sph_for" ||
									$kolomDb === "link_sph_ins"
								) {
									$align = "center";
								}
								?>
								<td align="<?= $align ?>" <?= $style ?>>
									<?php if ($kolomDb === null) {
										if ($label === "TOTAL ONGKOS KIRIM") {
											$total =
												(float) $row->asal_thc +
												(float) $row->asal_bl_fee +
												(float) $row->asal_vgm +
												(float) $row->asal_agaency_fee +
												(float) $row->asal_handling_fee +
												(float) $row->asal_transportasi +
												(float) $row->asal_loading +
												(float) $row->asal_custom +
												(float) $row->freight_ocean +
												(float) $row->freight_air +
												(float) $row->tuj_cfs +
												(float) $row->tuj_doc +
												(float) $row->tuj_agency_fee +
												(float) $row->tuj_handling +
												(float) $row->tuj_do +
												(float) $row->tuj_admin +
												(float) $row->tuj_devanning +
												(float) $row->tuj_fordwarding_fee +
												(float) $row->tuj_mechanics +
												(float) $row->tuj_other +
												(float) $row->cust_clearance +
												(float) $row->cust_red_line +
												(float) $row->cust_handling +
												(float) $row->cust_admin_fee +
												(float) $row->cust_pib_fee +
												(float) $row->cust_transfer +
												(float) $row->oth_trucking +
												(float) $row->asal_pickup +
												(float) $row->cust_storage +
												(float) $row->oth_handling +
												(float) $row->oth_storage +
												(float) $row->asal_seal +
												(float) $row->asal_shipping +
												(float) $row->asal_cfs +
												(float) $row->asal_dg +
												(float) $row->asal_psa +
												(float) $row->asal_other +
												(float) $row->cust_asu +
												(float) $row->cust_other +
												(float) $row->oth_buruh +
												(float) $row->oth_adm +
												(float) $row->oth_other;

											echo "Rp " . number_format($total, 0, ",", ".");
										} else {
											echo "";
										}
									} else {
										$value = $row->$kolomDb;

										if ($kolomDb === "link_invoice") {
											if (!empty($value)) {
												echo '<a href="' .
													htmlspecialchars($value) .
													'" class="btn btn-sm btn-primary" target="_blank" style="min-width:100px;">
																	<i class="fas fa-link"></i> Invoice
																</a>';
											} else {
												echo "-";
											}
										} elseif ($kolomDb === "link_packing") {
											if (!empty($value)) {
												echo '<a href="' .
													htmlspecialchars($value) .
													'" class="btn btn-sm btn-primary" target="_blank" style="min-width:100px;">
																	<i class="fas fa-link"></i> Packing List
																</a>';
											} else {
												echo "-";
											}
										} elseif ($kolomDb === "link_sph_for") {
											if (!empty($value)) {
												echo '<a href="' .
													htmlspecialchars($value) .
													'" class="btn btn-sm btn-primary" target="_blank" style="min-width:100px;">
																	<i class="fas fa-link"></i> SPH Forwarder
																</a>';
											} else {
												echo "-";
											}
										} elseif ($kolomDb === "link_sph_ins") {
											if (!empty($value)) {
												echo '<a href="' .
													htmlspecialchars($value) .
													'" class="btn btn-sm btn-primary" target="_blank" style="min-width:100px;">
																	<i class="fas fa-link"></i> SPH Asuransi
																</a>';
											} else {
												echo "-";
											}
										} elseif (
											$kolomDb === "detail" ||
											$kolomDb === "alasan" ||
											$kolomDb === "pol" ||
											$kolomDb === "pod" ||
											$kolomDb === "berat" ||
											$kolomDb === "keterangan_detail"
										) {
											echo nl2br(htmlspecialchars($value));
										} elseif ($kolomDb === "dipilih") {
											echo $value == 1 ? htmlspecialchars($row->nama_forwarder) : "";
										} else {
											echo is_numeric($value)
												? "Rp " . number_format($value, 0, ",", ".")
												: $value;
										}
									} ?>

								</td>
							<?php endforeach; ?>
						</tr>
					<?php
					endforeach;
					?>
				</tbody>
			</table>


			<table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>

					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="6"><br><br>
							<?= $data_aprv[0]->kota_aju ?> Pekanbaru
							<span> ,&nbsp; </span>
							<?= date("d-m-Y", strtotime($data_aprv[0]->created_at)) ?>

						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25%;">Dibuat Oleh,</td>
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:25%;">Diverifikasi Oleh,</td>
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:25%;">Disetujui Oleh,</td>
					</tr>


					<tr style="height:60px;">
						<?php
						$img_path = "uploads/file_karyawan/ttd/";
						$ttdaju = $img_path . "ttd_" . $data_aprv[0]->id_pengguna . ".png";
						$ttd1 = $img_path . "ttd_notyet2.png";
						$ttd2 = $img_path . "ttd_notyet2.png";

						if ($data_aprv[0]->ttd_1 == "1") {
							$ttd1 = $img_path . "ttd_23.png";
						} elseif ($data_aprv[0]->ttd_1 == "2") {
							$ttd1 = $img_path . "ttd_not.png";
						}

						if ($data_aprv[0]->ttd_2 == "1") {
							$ttd2 = $img_path . "ttd_54.png";
						} elseif ($data_aprv[0]->ttd_2 == "2") {
							$ttd2 = $img_path . "ttd_not.png";
						}
						?>
						<td style="text-align:center; width:25%;"><?php echo '<img src="' .
																		$ttdaju .
																		'" height="70">'; ?> </td>
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:25%;"><?php echo '<img src="' .
																		$ttd1 .
																		'" height="70">'; ?> </td>
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:25%;"><?php echo '<img src="' .
																		$ttd2 .
																		'" height="70">'; ?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_aprv[0]->pengaju ?>
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
						<td style="text-align:center; vertical-align:top;"><i><?= $data_aprv[0]
																					->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corporate Planning & Business Management</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director</i></td>
					</tr>
				</tbody>

			</table>
		</div>

		<br><br>
		<div role="document">
			<?php if (
				sessPenggunaId() == "1" ||
				sessPenggunaId() == "23" ||
				sessPenggunaId() == "54"
			) { ?>
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px;" id-Sijk="<?= encrypt(
																																$data_aprv[0]->idGc,
																															) ?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px;" id-Sijk="<?= encrypt(
																																$data_aprv[0]->idGc,
																															) ?>"> <i class="fas fa-times"></i> Tolak </button>
			<?php } ?>


			<?php if ($data_aprv[0]->link_final != "") { ?>
				<a href="<?= $data_aprv[0]
								->link_final ?>" class="btn btn-primary float-right" style="margin-left:12px;"> <i class="fas fa-link"></i> Invoice Final </a>
			<?php } ?>
			<a href="forwarder/print_page/detail/<?= $data_aprv[0]
														->idGc ?>" class="btn btn-warning float-right" style="margin-left:12px;"> <i class="fas fa-print"></i> Cetak </a>
			<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left">Kembali</button>
			<?php if (isAdmin()): ?>
				<button type="button" onclick="openAdminEditModal('forwarder', '<?= encrypt($data_aprv[0]->idGc) ?>')" class="btn btn-warning float-left font-weight-bold text-dark" style="margin-left:12px;">
					<i class="fas fa-user-shield mr-1"></i> Edit Data (Admin)
				</button>
			<?php endif; ?>
		</div>
		<br><br>
	</div>


	<?= form_close() ?>

</div>
</div>






<script>
	document.addEventListener('DOMContentLoaded', function() {

		var level_ttd = $('#level_ttd').val();

		$(document).on('click', '.btn-approval', function() {
			var id = $('#id').val();

			Swal.fire({
				title: 'Setujui Permintaan Approval Forwarder?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: '<?= base_url("forwarder/ttd_setujui/") ?>' + level_ttd,
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
				title: 'Tolak Permintaan Approval Forwarder?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: '<?= base_url("forwarder/ttd_tolak/") ?>' + level_ttd,
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