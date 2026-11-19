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
			<tr>
				<td>HS Code </td>
				<td>:&nbsp;&nbsp; <?= !empty($data_aprv[0]->hs_code) ? htmlspecialchars($data_aprv[0]->hs_code) : '-' ?></td>
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
				<td>Kurs 1 <?= !empty($data_aprv[0]->mata_nilai_inv) ? $data_aprv[0]->mata_nilai_inv : 'USD' ?> </td>
				<td>:&nbsp;&nbsp; <?= $kurs ?></td>
			</tr>
			<tr>
				<td style="vertical-align: top;">Keterangan</td>
				<td>:&nbsp;&nbsp; <?= !empty($data_aprv[0]->keterangan) ? nl2br(htmlspecialchars($data_aprv[0]->keterangan)) : '-' ?></td>
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
																				$name
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

		<?php if (!empty($data_aprv[0]->alasan_penolakan) && ($data_aprv[0]->ttd_1 == '2' || $data_aprv[0]->ttd_2 == '2' || $data_aprv[0]->status == '4' || $data_aprv[0]->status == '5')): ?>
			<div class="alert alert-danger mt-3" style="border-left: 5px solid #dc3545; background-color: #fff5f5; color: #721c24;">
				<h5 style="margin-top: 0; font-weight: bold;"><i class="fas fa-exclamation-triangle mr-2"></i> Pengajuan Approval Forwarder Ditolak</h5>
				<p class="mb-1"><strong>Alasan Penolakan:</strong></p>
				<p class="mb-0" style="font-size: 14px; white-space: pre-wrap;"><?= htmlspecialchars($data_aprv[0]->alasan_penolakan) ?></p>
			</div>
		<?php endif; ?>

		<br><br>
		<div role="document">
			<?php if (
				sessPenggunaId() == "1" ||
				sessPenggunaId() == "23" ||
				sessPenggunaId() == "54"
			) { ?>
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px;" id-Sijk="<?= encrypt(
																																$data_aprv[0]->idGc
																															) ?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px;" id-Sijk="<?= encrypt(
																																$data_aprv[0]->idGc
																															) ?>"> <i class="fas fa-times"></i> Tolak </button>
			<?php } ?>


			<?php if ($data_aprv[0]->link_final != "") { ?>
				<a href="<?= $data_aprv[0]
								->link_final ?>" class="btn btn-primary float-right" style="margin-left:12px;"> <i class="fas fa-link"></i> Invoice Final </a>
			<?php } ?>
			<a href="forwarder/print_page/detail/<?= $data_aprv[0]
														->idGc ?>" class="btn btn-warning float-right" style="margin-left:12px;"> <i class="fas fa-print"></i> Cetak </a>
			<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left">Kembali</button>
						<?php 
			$can_edit = (isAdmin() || sessPenggunaId() == 1 || sessPenggunaId() == 54 || (isset($data_aprv[0]->id_pengguna) && $data_aprv[0]->id_pengguna == sessPenggunaId()));
			if ($can_edit): ?>
				<button type="button" class="btn btn-primary btn-edit-shipment float-left font-weight-bold" style="margin-left:12px;">
					<i class="fas fa-pencil-alt mr-1"></i> Edit Data Pengajuan
				</button>
			<?php endif; ?>
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







<div id="modal-edit-shipment" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-light">
				<h5 class="modal-title font-weight-bold text-white"><i class="fas fa-edit mr-2"></i> Edit Data Pengajuan Forwarder</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
			</div>
			<?= form_open('forwarder/updateForwarder', array('id' => 'form-edit-shipment', 'autocomplete' => 'off')); ?>
			<div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
				<div class="alert alert-warning" style="font-size: 13px;">
					<i class="fas fa-info-circle mr-1"></i> <strong>Perhatian:</strong> Jika pengajuan ini sudah diajukan sebelumnya, perubahan data akan <strong>me-reset status persetujuan (approval) kembali dari awal</strong>.
				</div>

				<input type="hidden" id="edit_shipment_id" name="id_pelanggan" value="<?= encrypt($data_aprv[0]->idGc) ?>">

				<div class="form-group">
					<label for="edit_nama" class="form-control-label font-weight-bold">Nama Shipment <span class="text-danger">*</span> :</label>
					<input type="text" class="form-control" id="edit_nama" name="nama" value="<?= htmlspecialchars($data_aprv[0]->nama) ?>" required>
				</div>
				<div class="form-group">
					<label for="edit_sistem_pengiriman" class="form-control-label font-weight-bold">Sistem Pengiriman <span class="text-danger">*</span> :</label>
					<input type="text" class="form-control" id="edit_sistem_pengiriman" name="sistem_pengiriman" value="<?= htmlspecialchars($data_aprv[0]->sistem_pengiriman) ?>" placeholder="Contoh: via Udara / via Laut" required>
				</div>
				<div class="form-group row">
					<div class="col-md-6">
						<label for="edit_pol" class="form-control-label font-weight-bold">Port of Loading (POL) <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="edit_pol" name="pol" value="<?= htmlspecialchars($data_aprv[0]->pol) ?>" required>
					</div>
					<div class="col-md-6">
						<label for="edit_pod" class="form-control-label font-weight-bold">Port of Discharge (POD) <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="edit_pod" name="pod" value="<?= htmlspecialchars($data_aprv[0]->pod) ?>" required>
					</div>
				</div>
				<div class="form-group">
					<label for="edit_hs_code" class="form-control-label font-weight-bold">HS Code :</label>
					<input type="text" class="form-control" id="edit_hs_code" name="hs_code" value="<?= htmlspecialchars(!empty($data_aprv[0]->hs_code) ? $data_aprv[0]->hs_code : '') ?>" placeholder="Masukkan nomor HS Code">
				</div>
				<div class="form-group">
					<label for="edit_berat_dimensi" class="form-control-label font-weight-bold">Berat dan Dimensi Barang :</label>
					<textarea class="form-control" id="edit_berat_dimensi" name="berat_dimensi" rows="3"><?= htmlspecialchars($data_aprv[0]->berat_dimensi) ?></textarea>
				</div>
				<div class="form-group row">
					<div class="col-md-4">
						<label for="edit_uang_nilai_inv" class="form-control-label font-weight-bold">Mata Uang :</label>
						<select class="form-control" id="edit_uang_nilai_inv" name="uang_nilai_inv" required>
							<option value="USD" <?= ($data_aprv[0]->mata_nilai_inv == 'USD' || empty($data_aprv[0]->mata_nilai_inv)) ? 'selected' : '' ?>>USD</option>
							<option value="EUR" <?= ($data_aprv[0]->mata_nilai_inv == 'EUR') ? 'selected' : '' ?>>EUR</option>
							<option value="IDR" <?= ($data_aprv[0]->mata_nilai_inv == 'IDR') ? 'selected' : '' ?>>IDR</option>
						</select>
					</div>
					<div class="col-md-8">
						<label for="edit_nilai_inv" class="form-control-label font-weight-bold">Nilai Invoice Shipment :</label>
						<input type="number" step="any" class="form-control" id="edit_nilai_inv" name="nilai_inv" value="<?= htmlspecialchars($data_aprv[0]->nilai_inv) ?>">
						<small class="text-muted">Gunakan titik (.) untuk desimal.</small>
					</div>
				</div>
				<div class="form-group">
					<label for="edit_kurs" id="label_edit_kurs" class="form-control-label font-weight-bold">Kurs ke Rupiah (IDR) <span class="text-danger">*</span> :</label>
					<input type="number" class="form-control" id="edit_kurs" name="kurs" value="<?= htmlspecialchars($data_aprv[0]->kurs) ?>" required>
				</div>
				<div class="form-group">
					<label for="edit_keterangan" class="form-control-label font-weight-bold">Keterangan :</label>
					<textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan tambahan..."><?= htmlspecialchars(!empty($data_aprv[0]->keterangan) ? $data_aprv[0]->keterangan : '') ?></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-primary btn-save-shipment"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
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
				text: 'Alasan penolakan wajib diisi untuk transparansi dan evaluasi.',
				icon: 'warning',
				input: 'textarea',
				inputPlaceholder: 'Tuliskan alasan penolakan di sini...',
				inputAttributes: {
					'aria-label': 'Alasan penolakan'
				},
				showCancelButton: true,
				confirmButtonText: 'Tolak',
				cancelButtonText: 'Batal',
				confirmButtonColor: '#d33',
				inputValidator: function(value) {
					if (!value || !value.trim()) {
						return 'Alasan penolakan wajib diisi!';
					}
				}
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: '<?= base_url("forwarder/ttd_tolak/") ?>' + level_ttd,
						dataType: 'JSON',
						data: {
							id: id,
							alasan: result.value.trim(),
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

	
		$(document).on('click', '.btn-edit-shipment', function(e) {
			e.preventDefault();
			$('#modal-edit-shipment').modal('show');
		});

		$('#form-edit-shipment').on('submit', function(e) {
			e.preventDefault();
			var $btn = $(this).find('.btn-save-shipment');
			$btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

			var formData = new FormData(this);
			formData.append('csrf_token', token);

			$.ajax({
				method: 'POST',
				url: $(this).attr('action'),
				dataType: 'JSON',
				data: formData,
				processData: false,
				contentType: false,
				success: function(resp) {
					$btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Perubahan');
					if (resp.status == 'success' || resp.status == true) {
						Swal.fire({
							title: 'Berhasil!',
							text: resp.message || 'Data pengajuan berhasil diperbarui.',
							icon: 'success',
							confirmButtonText: 'OK'
						}).then(function() {
							window.location.reload();
						});
					} else {
						Swal.fire({
							title: 'Perhatian',
							text: resp.message || 'Gagal memperbarui data pengajuan.',
							icon: 'warning',
							confirmButtonText: 'OK'
						});
					}
				},
				error: function(xhr, status, error) {
					$btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Perubahan');
					Swal.fire({
						title: 'Error',
						text: 'Terjadi kesalahan sistem atau koneksi: ' + (error || status),
						icon: 'error',
						confirmButtonText: 'OK'
					});
				}
			});
		});

	function goBack() {
		window.history.back();
	}
</script>