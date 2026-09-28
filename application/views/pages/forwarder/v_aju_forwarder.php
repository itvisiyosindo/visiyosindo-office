<?php
// --- LOGIC PREPARATION (Dipindah ke atas agar HTML bersih) ---

// Setup Tanggal
$tanggal = $data_aprv[0]->tanggal;
$bulanTahun = date('F Y', strtotime($tanggal));
$bulanIndonesia = [
	'January' => 'Januari',
	'February' => 'Februari',
	'March' => 'Maret',
	'April' => 'April',
	'May' => 'Mei',
	'June' => 'Juni',
	'July' => 'Juli',
	'August' => 'Agustus',
	'September' => 'September',
	'October' => 'Oktober',
	'November' => 'November',
	'December' => 'Desember',
];
list($bulanInggris, $tahun) = explode(' ', $bulanTahun);
$tanggalFormatted = $bulanIndonesia[$bulanInggris] . ' ' . $tahun;

// Setup Kurs
$kurs = 'Rp ' . number_format($data_aprv[0]->kurs, 0, ',', '.');

// Setup Berat Dimensi
$lines = explode("\n", $data_aprv[0]->berat_dimensi);
$beratFormatted = '';
foreach ($lines as $i => $line) {
	if ($i == 0) {
		$beratFormatted .= ':&nbsp;&nbsp;' . htmlspecialchars($line) . '<br>';
	} else {
		$beratFormatted .= '&nbsp;&nbsp;&nbsp;' . htmlspecialchars($line) . '<br>';
	}
}

// Setup Tanda Tangan
$ttd = "ttd_1";
if ((sessPenggunaId() == '23')) {
	$ttd = 'ttd_1';
} else if ((sessPenggunaId() == '54')) {
	$ttd = 'ttd_2';
}

// Setup Array Konfigurasi Tabel Forwarder
$keteranganList = [
	'EXW CHARGES<br>(Biaya di Negara Asal)' => null,
	'Terminal Handling Charge' => 'asal_thc',
	'BL Fee' => 'asal_bl_fee',
	'VGM Fee' => 'asal_vgm',
	'Agency Fee / Admin Fee' => 'asal_agaency_fee',
	'Handling Fee' => 'asal_handling_fee',
	'Transportation / Trucking' => 'asal_transportasi',
	'Loading / Unloading' => 'asal_loading',
	'Custom Clearance' => 'asal_custom',
	'Pick Up Fee' => 'asal_pickup',
	'Seal Fee' => 'asal_seal',
	'Shipping Line Charge' => 'asal_shipping',
	'CFS (Origin)' => 'asal_cfs',
	'DG Surcharges' => 'asal_dg',
	'PSA Surcharges' => 'asal_psa',
	'Other (Origin)' => 'asal_other',

	'Freight<br>(Biaya Ongkos Kirim)' => null,
	'Ocean Freight' => 'freight_ocean',
	'Air Freight' => 'freight_air',

	'Destination Charges<br>(Biaya di Negara Tujuan)' => null,
	'CFS (Destination)' => 'tuj_cfs',
	'DOC' => 'tuj_doc',
	'AGENCY FEE' => 'tuj_agency_fee',
	'HANDLING' => 'tuj_handling',
	'DO Fee' => 'tuj_do',
	'ADMIN' => 'tuj_admin',
	'DEVANNING' => 'tuj_devanning',
	'FORWARDING FEE' => 'tuj_fordwarding_fee',
	'MECHANICSS' => 'tuj_mechanics',
	'Other (Destination)' => 'tuj_other',

	'Custom Clearance Charges<br>(Bea Cukai)' => null,
	'Custom Clearance Charges' => 'cust_clearance',
	'Red Line / Labor (if any)' => 'cust_red_line',
	'Handling Charges' => 'cust_handling',
	'Admin Fee' => 'cust_admin_fee',
	'PIB Fee' => 'cust_pib_fee',
	'Transfer EDI' => 'cust_transfer',
	'STORAGE + LIFT OF LIFT ON' => 'cust_storage',
	'Asuransi Custom' => 'cust_asu',
	'Other (Customs)' => 'cust_other',

	'Other Charges' => null,
	'Storage' => 'oth_storage',
	'TRUCKING (Delivery Fee) (if any)' => 'oth_trucking',
	'Handling Gudang' => 'oth_handling',
	'Buruh (If Any)' => 'oth_buruh',
	'Admin Gudang' => 'oth_adm',
	'Other (Charges)' => 'oth_other',

	'TOTAL ONGKOS KIRIM' => null,
	'PPN' => 'ppn',

	'Insurance' => null,
	'Nilai Asuransi' => 'ins_nilai',
	'Jenis Asuransi' => 'ins_jenis',
	'FORWARDER YANG DIPILIH' => 'dipilih',
	'Alasan' => 'alasan',
	'Keterangan' => 'keterangan_detail',
	'Link Invoice' => 'link_invoice',
	'Link Packing List' => 'link_packing',
	'Link SPH Forwarder' => 'link_sph_for',
	'Link SPH Asuransi' => 'link_sph_ins',
	'Aksi' => null,
];

$boldItems = [
	'EXW CHARGES<br>(Biaya di Negara Asal)',
	'Freight<br>(Biaya Ongkos Kirim)',
	'Destination Charges<br>(Biaya di Negara Tujuan)',
	'Custom Clearance Charges<br>(Bea Cukai)',
	'Other Charges',
	'TOTAL ONGKOS KIRIM',
	'Insurance',
	'FORWARDER YANG DIPILIH',
	'Aksi'
];

$dataDetail = $detail_aprv;
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

<div class="col-xl-10 mb-8 mb-xl-0" style="margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">

		<div class="text-right">
			<a href="#" class="btn btn-sm btn-warning btn-duplikat" data-href="<?= site_url('forwarder/duplikat/' . $data_aprv[0]->idGc) ?>">
				<i class="fas fa-clone"></i> Duplikat
			</a>
		</div>

		<div class="table-responsive">
			<div class="text-center mt-0">
				<h2 style="font-family: 'Times New Roman'; color: #000000;">Form Permintaan Approval Forwarder Luar Negeri</h2>
				<h2 style="font-family: 'Times New Roman'; color: #000000;">No : <?= $data_aprv[0]->kode ?></h2>
			</div>
			<br>

			<div style="color: #000000;">
				<input type="hidden" id="level_ttd" value="<?= $ttd ?>">
				<input type="hidden" name="id" id="id" value="<?= $data_aprv[0]->idGc ?>">

				<?= form_open('surat_new/updateApprovalEks', array('id' => 'main-form', 'autocomplete' => 'off')); ?>

				<table id="tbl_1" style="font-family:Times New Roman; font-size:15px; width:100%; border:0;">
					<tr>
						<td colspan="3" style="text-align:left; color: #000000;">
							Dengan ini saya mengajukan Permintaan Approval Forwarder Luar Negeri :
						</td>
					</tr>
					<tr>
						<td rowspan="17" width="2%"></td>
						<input type="hidden" id="idpengaju" value="<?= $data_aprv[0]->id_pengguna ?>">
					</tr>
					<tr>
						<td width="15%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_aprv[0]->pengaju ?> </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp; <?= $data_aprv[0]->jabatan ?></td>
					</tr>
					<tr>
						<td><span style="color:white;">i</span></td>
					</tr>
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
						<td style="vertical-align: top;">Berat dan Dimensi Barang</td>
						<td><?= $beratFormatted ?></td>
					</tr>
					<tr>
						<td>Nilai Invoice Shipment</td>
						<td>:&nbsp;&nbsp; <?= $data_aprv[0]->mata_nilai_inv . ' ' . $data_aprv[0]->nilai_inv ?></td>
					</tr>
					<tr>
						<td>Kurs 1 USD </td>
						<td>:&nbsp;&nbsp; <?= $kurs ?></td>
					</tr>
					<tr>
						<td><span style="color:white;">i</span></td>
					</tr>
				</table>

				<table id="tbl_2" style="font-family:Times New Roman; font-size:15px; width:100%; border:0;">
					<tr>
						<td colspan="3" style="text-align:left; color: #000000;">
							Detail perbandingan Forwarder sebagai berikut :
						</td>
					</tr>
					<tr>
						<td><span style="color:white;">i</span></td>
					</tr>
				</table>

				<div class="card-body pl-0">
					<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Forwarder</a>
				</div>

				<table id="kt_table_1" style="font-family:Times New Roman; color:black; font-size:15px; width:100%; border:1px solid black;" border="1">
					<thead>
						<tr>
							<th style="text-align:center" bgcolor="#d3d3d3"> Nama Forwarder </th>
							<?php foreach ($forwarderNames as $name): ?>
								<th style="text-align:center" bgcolor="#d3d3d3"><?= htmlspecialchars($name) ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($keteranganList as $label => $kolomDb):
							$isBold = in_array($label, $boldItems);
							$style = $isBold ? ' style="font-weight:bold; background-color:#e5e5e5;"' : '';
							$styleLabel = 'style="text-align:center;' . ($isBold ? 'font-weight:bold; background-color:#e5e5e5;' : '') . '"';
						?>
							<tr>
								<td <?= $styleLabel ?>><?= $label ?></td>
								<?php foreach (array_values($dataDetail) as $i => $row): ?>
									<?php
									$align = 'right';
									if ($label === 'Aksi') {
										$align = 'center';
									} elseif (
										$kolomDb === 'ppn' || $kolomDb === 'ins_jenis' || $kolomDb === 'storage' || $kolomDb === 'dipilih' || $kolomDb === 'alasan' || $kolomDb === 'keterangan_detail'
										|| $kolomDb === 'link_invoice' || $kolomDb === 'link_packing' || $kolomDb === 'link_sph_for' || $kolomDb === 'link_sph_ins'
									) {
										$align = 'center';
									}
									?>
									<td align="<?= $align ?>" <?= $style ?>>
										<?php
										if ($label === 'Aksi') {
											$idEnc = encrypt($row->id);
											if ($data_aprv[0]->status == 0 || $data_aprv[0]->status == 1) {
												echo '<div style="display: flex; gap: 8px; justify-content: center;">
                                                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $idEnc . '">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $row->id . '" data-object="' . base_url('forwarder/deleteDetail') . '/' . $row->id . '">
                                                            <i class="bx bx-trash"></i> Hapus
                                                        </button>       
                                                      </div>';
											} else {
												echo '';
											}
										} elseif ($kolomDb === null) {
											if ($label === 'TOTAL ONGKOS KIRIM') {
												$total = (float)$row->asal_thc + (float)$row->asal_bl_fee + (float)$row->asal_vgm + (float)$row->asal_agaency_fee + (float)$row->asal_handling_fee +
													(float)$row->asal_transportasi + (float)$row->asal_loading + (float)$row->asal_custom + (float)$row->freight_ocean + (float)$row->freight_air +
													(float)$row->tuj_cfs + (float)$row->tuj_doc + (float)$row->tuj_agency_fee + (float)$row->tuj_handling + (float)$row->tuj_do + (float)$row->tuj_admin +
													(float)$row->tuj_devanning + (float)$row->tuj_fordwarding_fee + (float)$row->tuj_mechanics + (float)$row->tuj_other + (float)$row->cust_clearance +
													(float)$row->cust_red_line + (float)$row->cust_handling + (float)$row->cust_admin_fee + (float)$row->cust_pib_fee + (float)$row->cust_transfer + (float)$row->oth_trucking + (float)$row->asal_pickup + (float)$row->cust_storage + (float)$row->oth_handling + (float)$row->oth_storage + (float)$row->asal_seal + (float)$row->asal_shipping + (float)$row->asal_cfs + (float)$row->asal_dg + (float)$row->asal_psa + (float)$row->asal_other + (float)$row->cust_asu + (float)$row->cust_other + (float)$row->oth_buruh + (float)$row->oth_adm + (float)$row->oth_other;

												echo 'Rp ' . number_format($total, 0, ',', '.');
											} else {
												echo '';
											}
										} else {
											$value = $row->$kolomDb;

											if (in_array($kolomDb, ['link_invoice', 'link_packing', 'link_sph_for', 'link_sph_ins'])) {
												$btnLabel = 'Link';
												if ($kolomDb == 'link_invoice') $btnLabel = 'Invoice';
												if ($kolomDb == 'link_packing') $btnLabel = 'Packing List';
												if ($kolomDb == 'link_sph_for') $btnLabel = 'SPH Forwarder';
												if ($kolomDb == 'link_sph_ins') $btnLabel = 'SPH Asuransi';

												if (!empty($value)) {
													echo '<a href="' . htmlspecialchars($value) . '" class="btn btn-sm btn-primary" target="_blank" style="min-width:100px;">
                                                            <i class="fas fa-link"></i> ' . $btnLabel . '
                                                          </a>';
												} else {
													echo '-';
												}
											} elseif ($kolomDb === 'detail' || $kolomDb === 'alasan' || $kolomDb === 'keterangan_detail' || $kolomDb === 'pol' || $kolomDb === 'pod' || $kolomDb === 'berat') {
												echo nl2br(htmlspecialchars($value));
											} elseif ($kolomDb === 'dipilih') {
												echo ($value == 1) ? htmlspecialchars($row->nama_forwarder) : '';
											} else {
												echo is_numeric($value) ? 'Rp ' . number_format($value, 0, ',', '.') : $value;
											}
										}
										?>
									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<table id="tbl_7" style="font-family:Times New Roman; font-size:15px; width:100%; border:0;">
					<tr>
						<td rowspan="17" width="2%"></td>
						<?php if ($data_aprv[0]->status == 3) { ?>
					<tr>
						<td><span style="color:white;">i</span></td>
					</tr>
					<tr>
						<td width="20%">Link Invoice Final</td>
						<td>:&nbsp;&nbsp;<input id="catatan_finance" type="text" placeholder="Klik Untuk Memasukkan Link Invoice Final" value="<?= isset($data_aprv[0]->link_final) ? htmlspecialchars($data_aprv[0]->link_final) : '' ?>" required></td>
					</tr>
				<?php } ?>
				</tr>
				</table>

				<table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; color:black; font-size:15px;">
					<tbody>
						<tr style="height: 35px;">
							<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="6">
								<br><br>
								<?= $data_aprv[0]->kota_aju ?> Pekanbaru
								<span> ,&nbsp; </span>
								<?= date('d-m-Y', strtotime($data_aprv[0]->created_at)) ?>
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
							$img_path     = "uploads/file_karyawan/ttd/";
							$ttdaju       = $img_path . "ttd_" . $data_aprv[0]->id_pengguna . ".png";
							$ttd1         = $img_path . "ttd_notyet2.png";
							$ttd2         = $img_path . "ttd_notyet2.png";

							if ($data_aprv[0]->ttd_1 == '1') {
								$ttd1 = $img_path . "ttd_23.png";
							} else if ($data_aprv[0]->ttd_1 == '2') {
								$ttd1 = $img_path . "ttd_not.png";
							}

							if ($data_aprv[0]->ttd_2 == '1') {
								$ttd2 = $img_path . "ttd_54.png";
							} else if ($data_aprv[0]->ttd_2 == '2') {
								$ttd2 = $img_path . "ttd_not.png";
							}
							?>
							<td style="text-align:center; width:25%;"><?php echo '<img src="' . $ttdaju . '" height="70">'; ?> </td>
							<td style="text-align:center; width:10%;"></td>
							<td style="text-align:center; width:25%;"><?php echo '<img src="' . $ttd1 . '" height="70">'; ?> </td>
							<td style="text-align:center; width:10%;"></td>
							<td style="text-align:center; width:25%;"><?php echo '<img src="' . $ttd2 . '" height="70">'; ?></td>
						</tr>
						<tr>
							<td style="text-align:center; "><?= $data_aprv[0]->pengaju ?>
								<hr>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; ">Meilina Safitri
								<hr>
							</td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; ">Bob Ariyos
								<hr>
							</td>
						</tr>
						<tr>
							<td style="text-align:center; vertical-align:top;"><i><?= $data_aprv[0]->jabatan ?></i></td>
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
				<?php if ($data_aprv[0]->status == 0) { ?>
					<button type="button" class="btn btn-success btn-save float-right btn-ajukan" style="margin-left:12px;"> <i class="fas fa-check"></i> Ajukan </button>
				<?php } ?>

				<?php if ($data_aprv[0]->status == 3) { ?>
					<button type="button" class="btn btn-success float-right btn-submit2" style="margin-left:12px;" id-Sijk="<?= encrypt($data_aprv[0]->idGc) ?>"> <i class="fas fa-check"></i> Submit (Invoice Final) </button>
				<?php } ?>

				<?php if ($data_aprv[0]->link_final != '') { ?>
					<a href="<?= $data_aprv[0]->link_final ?>" class="btn btn-primary float-right" style="margin-left:12px;"> <i class="fas fa-link"></i> Invoice Final </a>
				<?php } ?>

				<a href="forwarder/print_page/detail/<?= $data_aprv[0]->idGc ?>" class="btn btn-warning float-right" style="margin-left:12px;"> <i class="fas fa-print"></i> Cetak </a>
				<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left">Kembali</button>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Perbandingan Expedisi Luar Negeri</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<div class="form-group">
						<label for="id_forwarder" class="form-control-label"><strong> Nama Forwarder </strong> <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_forwarder" name="id_forwarder" required>
							<option value="">- Pilih Forwarder -</option>
							<?php foreach ($list_for as $row) {
								echo '<option value="' . $row->id . '">' . $row->nama . '</option>';
							} ?>
						</select>
					</div>
					<br>
					<strong> EXW CHARGES (Biaya di Negara Asal) </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_thc1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_thc1" name="uang_asal_thc1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_thc" class="form-control-label">Terminal Handling Charge :</label>
							<input type="number" class="form-control" id="asal_thc" name="asal_thc">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_bl_fee1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_bl_fee1" name="uang_asal_bl_fee1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_bl_fee" class="form-control-label">BL Fee :</label>
							<input type="number" class="form-control" id="asal_bl_fee" name="asal_bl_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_vgm1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>VGM Fee :</label><input type="number" class="form-control" id="asal_vgm" name="asal_vgm"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_agaency_fee1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Agency Fee :</label><input type="number" class="form-control" id="asal_agaency_fee" name="asal_agaency_fee"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_handling_fee1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Handling Fee :</label><input type="number" class="form-control" id="asal_handling_fee" name="asal_handling_fee"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_transportasi1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Transportation :</label><input type="number" class="form-control" id="asal_transportasi" name="asal_transportasi"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_loading1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Loading :</label><input type="number" class="form-control" id="asal_loading" name="asal_loading"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_custom1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Custom Clearance :</label><input type="number" class="form-control" id="asal_custom" name="asal_custom"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_pickup1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Pick Up Fee :</label><input type="number" class="form-control" id="asal_pickup" name="asal_pickup"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_seal1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Seal Fee :</label><input type="number" class="form-control" id="asal_seal" name="asal_seal"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_shipping1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Shipping Line :</label><input type="number" class="form-control" id="asal_shipping" name="asal_shipping"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_cfs1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>CFS :</label><input type="number" class="form-control" id="asal_cfs" name="asal_cfs"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_dg1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>DG Surcharges :</label><input type="number" class="form-control" id="asal_dg" name="asal_dg"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_psa1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>PSA Surcharges :</label><input type="number" class="form-control" id="asal_psa" name="asal_psa"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_other1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Other :</label><input type="number" class="form-control" id="asal_other" name="asal_other"></div>
					</div>

					<br><strong> Freight </strong>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_freight_ocean1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Ocean Freight :</label><input type="number" class="form-control" id="freight_ocean" name="freight_ocean"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_freight_air1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Air Freight :</label><input type="number" class="form-control" id="freight_air" name="freight_air"></div>
					</div>

					<br><strong> Destination Charges </strong>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_cfs1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>CFS :</label><input type="number" class="form-control" id="tuj_cfs" name="tuj_cfs"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_doc1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>DOC :</label><input type="number" class="form-control" id="tuj_doc" name="tuj_doc"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_agency_fee1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>AGENCY FEE :</label><input type="number" class="form-control" id="tuj_agency_fee" name="tuj_agency_fee"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_handling1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>HANDLING :</label><input type="number" class="form-control" id="tuj_handling" name="tuj_handling"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_do1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>DO Fee:</label><input type="number" class="form-control" id="tuj_do" name="tuj_do"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_admin1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>ADMIN :</label><input type="number" class="form-control" id="tuj_admin" name="tuj_admin"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_devanning1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>DEVANNING :</label><input type="number" class="form-control" id="tuj_devanning" name="tuj_devanning"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_fordwarding_fee1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>FORWARDING FEE :</label><input type="number" class="form-control" id="tuj_fordwarding_fee" name="tuj_fordwarding_fee"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_mechanics1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>MECHANICS :</label><input type="number" class="form-control" id="tuj_mechanics" name="tuj_mechanics"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_other1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>OTHER :</label><input type="number" class="form-control" id="tuj_other" name="tuj_other"></div>
					</div>

					<br><strong> Custom Clearance </strong>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_clearance1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Custom Clearance :</label><input type="number" class="form-control" id="cust_clearance" name="cust_clearance"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_red_line1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Red Line :</label><input type="number" class="form-control" id="cust_red_line" name="cust_red_line"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_handling1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Handling Charges :</label><input type="number" class="form-control" id="cust_handling" name="cust_handling"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_admin_fee1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Admin Fee :</label><input type="number" class="form-control" id="cust_admin_fee" name="cust_admin_fee"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_pib_fee1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>PIB Fee :</label><input type="number" class="form-control" id="cust_pib_fee" name="cust_pib_fee"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_transfer1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Transfer EDI :</label><input type="number" class="form-control" id="cust_transfer" name="cust_transfer"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_storage1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>STORAGE :</label><input type="number" class="form-control" id="cust_storage" name="cust_storage"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_asu1">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Asuransi Custom :</label><input type="number" class="form-control" id="cust_asu" name="cust_asu"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_other1">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Other :</label><input type="number" class="form-control" id="cust_other" name="cust_other"></div>
					</div>

					<br><strong>Other Charges </strong>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_oth_storage1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>STORAGE :</label><input type="number" class="form-control" id="oth_storage" name="oth_storage"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_oth_trucking1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>TRUCKING :</label><input type="number" class="form-control" id="oth_trucking" name="oth_trucking"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_oth_handling1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Handling Gudang :</label><input type="number" class="form-control" id="oth_handling" name="oth_handling"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_oth_buruh1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Buruh :</label><input type="number" class="form-control" id="oth_buruh" name="oth_buruh"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_oth_adm1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Admin Gudang :</label><input type="number" class="form-control" id="oth_adm" name="oth_adm"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_oth_other1">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Other :</label><input type="number" class="form-control" id="oth_other" name="oth_other"></div>
					</div>

					<br><strong>Insurance </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_ins_nilai1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_ins_nilai1" name="uang_ins_nilai1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="ins_nilai" class="form-control-label">Nilai Asuransi :</label>
							<input type="number" class="form-control" id="ins_nilai" name="ins_nilai">
						</div>
					</div>
					<div class="form-group">
						<label for="ins_jenis1" class="form-control-label">Jenis Asuransi :</label>
						<select data-plugin-selectTwo class="form-control populate" id="ins_jenis1" name="ins_jenis1" required>
							<option value="NON CLAIM">NON CLAIM</option>
							<option value="CLAIM">CLAIM</option>
						</select>
					</div>

					<br><strong>FORWARDER YANG DIPILIH </strong>
					<div class="form-group">
						<label for="dipilih1" class="form-control-label">Apakah Forwarder yang dipilih?</label>
						<select data-plugin-selectTwo class="form-control populate" id="dipilih1" name="dipilih1" required>
							<option value="2">Tidak</option>
							<option value="1">Ya</option>
						</select>
					</div>
					<div class="form-group"><label>ALASAN :</label><textarea type="text" class="form-control" id="alasan" name="alasan" cols="10" rows="3"></textarea></div>
					<div class="form-group"><label>KETERANGAN :</label><textarea type="text" class="form-control" id="keterangan" name="keterangan" cols="10" rows="3"></textarea></div>

					<br><strong>Link </strong>
					<div class="form-group"><label>Link Invoice :</label><textarea type="text" class="form-control" id="link_invoice" name="link_invoice" cols="10" rows="3"></textarea></div>
					<div class="form-group"><label>Link Packing List :</label><textarea type="text" class="form-control" id="link_packing" name="link_packing" cols="10" rows="3"></textarea></div>
					<div class="form-group"><label>Link SPH Forwarder :</label><textarea type="text" class="form-control" id="link_sph_for" name="link_sph_for" cols="10" rows="3"></textarea></div>
					<div class="form-group"><label>LINK SPH Asuransi :</label><textarea type="text" class="form-control" id="link_sph_ins" name="link_sph_ins" cols="10" rows="3"></textarea></div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" name="id_app" id="id_app" value="<?= isset($data_aprv[0]->idGc) ? htmlspecialchars($data_aprv[0]->idGc, ENT_QUOTES, 'UTF-8') : '' ?>">
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<div id="main-modal-edit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Edit Perbandingan Expedisi Luar Negeri</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-edit', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<div class="form-group">
						<label for="id_forwarder" class="form-control-label"><strong> Nama Forwarder </strong> <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_forwarder" name="id_forwarder" required>
							<option value="">- Pilih Forwarder -</option>
							<?php foreach ($list_for as $row) {
								echo '<option value="' . $row->id . '">' . $row->nama . '</option>';
							} ?>
						</select>
					</div>
					<br>
					<strong> EXW CHARGES (Biaya di Negara Asal) </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_thc" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_thc" name="uang_asal_thc" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_thc" class="form-control-label">Terminal Handling Charge :</label>
							<input type="number" class="form-control" id="asal_thc" name="asal_thc">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_bl_fee">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>BL Fee :</label><input type="number" class="form-control" id="asal_bl_fee" name="asal_bl_fee"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_vgm">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>VGM Fee :</label><input type="number" class="form-control" id="asal_vgm" name="asal_vgm"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_agaency_fee">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Agency Fee :</label><input type="number" class="form-control" id="asal_agaency_fee" name="asal_agaency_fee"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_handling_fee">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Handling Fee :</label><input type="number" class="form-control" id="asal_handling_fee" name="asal_handling_fee"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_transportasi">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Transportation :</label><input type="number" class="form-control" id="asal_transportasi" name="asal_transportasi"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_loading">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Loading :</label><input type="number" class="form-control" id="asal_loading" name="asal_loading"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_custom">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Custom Clearance :</label><input type="number" class="form-control" id="asal_custom" name="asal_custom"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_pickup">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Pick Up Fee :</label><input type="number" class="form-control" id="asal_pickup" name="asal_pickup"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_seal">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Seal Fee :</label><input type="number" class="form-control" id="asal_seal" name="asal_seal"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_shipping">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Shipping Line :</label><input type="number" class="form-control" id="asal_shipping" name="asal_shipping"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_cfs">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>CFS :</label><input type="number" class="form-control" id="asal_cfs" name="asal_cfs"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_dg">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>DG Surcharges :</label><input type="number" class="form-control" id="asal_dg" name="asal_dg"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_psa">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>PSA Surcharges :</label><input type="number" class="form-control" id="asal_psa" name="asal_psa"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_asal_other">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Other :</label><input type="number" class="form-control" id="asal_other" name="asal_other"></div>
					</div>

					<br><strong> Freight </strong>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_freight_ocean">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Ocean Freight :</label><input type="number" class="form-control" id="freight_ocean" name="freight_ocean"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_freight_air">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Air Freight :</label><input type="number" class="form-control" id="freight_air" name="freight_air"></div>
					</div>

					<br><strong> Destination Charges </strong>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_cfs">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>CFS :</label><input type="number" class="form-control" id="tuj_cfs" name="tuj_cfs"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_doc">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>DOC :</label><input type="number" class="form-control" id="tuj_doc" name="tuj_doc"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_agency_fee">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>AGENCY FEE :</label><input type="number" class="form-control" id="tuj_agency_fee" name="tuj_agency_fee"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_handling">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>HANDLING :</label><input type="number" class="form-control" id="tuj_handling" name="tuj_handling"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_do">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>DO Fee :</label><input type="number" class="form-control" id="tuj_do" name="tuj_do"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_admin">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>ADMIN :</label><input type="number" class="form-control" id="tuj_admin" name="tuj_admin"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_devanning">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>DEVANNING :</label><input type="number" class="form-control" id="tuj_devanning" name="tuj_devanning"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_fordwarding_fee">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>FORWARDING FEE :</label><input type="number" class="form-control" id="tuj_fordwarding_fee" name="tuj_fordwarding_fee"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_mechanics">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>MECHANICS :</label><input type="number" class="form-control" id="tuj_mechanics" name="tuj_mechanics"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_tuj_other">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>OTHER :</label><input type="number" class="form-control" id="tuj_other" name="tuj_other"></div>
					</div>

					<br><strong> Custom Clearance </strong>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_clearance">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Custom Clearance :</label><input type="number" class="form-control" id="cust_clearance" name="cust_clearance"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_red_line">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Red Line :</label><input type="number" class="form-control" id="cust_red_line" name="cust_red_line"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_handling">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Handling Charges :</label><input type="number" class="form-control" id="cust_handling" name="cust_handling"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_admin_fee">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Admin Fee :</label><input type="number" class="form-control" id="cust_admin_fee" name="cust_admin_fee"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_pib_fee">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>PIB Fee :</label><input type="number" class="form-control" id="cust_pib_fee" name="cust_pib_fee"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_transfer">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Transfer EDI :</label><input type="number" class="form-control" id="cust_transfer" name="cust_transfer"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_storage">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>STORAGE :</label><input type="number" class="form-control" id="cust_storage" name="cust_storage"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_asu">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Asuransi Custom :</label><input type="number" class="form-control" id="cust_asu" name="cust_asu"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_cust_other">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Other :</label><input type="number" class="form-control" id="cust_other" name="cust_other"></div>
					</div>

					<br><strong>Other Charges </strong>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_oth_storage">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>STORAGE :</label><input type="number" class="form-control" id="oth_storage" name="oth_storage"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_oth_trucking">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>TRUCKING :</label><input type="number" class="form-control" id="oth_trucking" name="oth_trucking"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_oth_handling">
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select></div>
						<div class="col-md-9"><label>Handling Gudang :</label><input type="number" class="form-control" id="oth_handling" name="oth_handling"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_oth_buruh">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Buruh :</label><input type="number" class="form-control" id="oth_buruh" name="oth_buruh"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_oth_adm">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Admin Gudang :</label><input type="number" class="form-control" id="oth_adm" name="oth_adm"></div>
					</div>
					<div class="form-group row">
						<div class="col-md-3"><label>MATA UANG :</label><select class="form-control" name="uang_oth_other">
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select></div>
						<div class="col-md-9"><label>Other :</label><input type="number" class="form-control" id="oth_other" name="oth_other"></div>
					</div>

					<br><strong>Insurance </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_ins_nilai" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_ins_nilai" name="uang_ins_nilai" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="ins_nilai" class="form-control-label">Nilai Asuransi :</label>
							<input type="number" class="form-control" id="ins_nilai" name="ins_nilai">
						</div>
					</div>
					<div class="form-group">
						<label for="ins_jenis" class="form-control-label">Jenis Asuransi :</label>
						<select data-plugin-selectTwo class="form-control populate" id="ins_jenis" name="ins_jenis" required>
							<option value="NON CLAIM">NON CLAIM</option>
							<option value="CLAIM">CLAIM</option>
						</select>
					</div>

					<br><strong>FORWARDER YANG DIPILIH </strong>
					<div class="form-group">
						<label for="dipilih" class="form-control-label">Apakah Forwarder yang dipilih?</label>
						<select data-plugin-selectTwo class="form-control populate" id="dipilih" name="dipilih" required>
							<option value="2">Tidak</option>
							<option value="1">Ya</option>
						</select>
					</div>
					<div class="form-group"><label>ALASAN :</label><textarea type="text" class="form-control" id="alasan" name="alasan" cols="10" rows="3"></textarea></div>
					<div class="form-group"><label>KETERANGAN :</label><textarea type="text" class="form-control" id="keterangan" name="keterangan" cols="10" rows="3"></textarea></div>

					<br><strong>Link </strong>
					<div class="form-group"><label>Link Invoice :</label><textarea type="text" class="form-control" id="link_invoice" name="link_invoice" cols="10" rows="3"></textarea></div>
					<div class="form-group"><label>Link Packing List :</label><textarea type="text" class="form-control" id="link_packing" name="link_packing" cols="10" rows="3"></textarea></div>
					<div class="form-group"><label>Link SPH Forwarder :</label><textarea type="text" class="form-control" id="link_sph_for" name="link_sph_for" cols="10" rows="3"></textarea></div>
					<div class="form-group"><label>LINK SPH Asuransi :</label><textarea type="text" class="form-control" id="link_sph_ins" name="link_sph_ins" cols="10" rows="3"></textarea></div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_app" name="id_app">
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
		$(document).on('click', '.btn-ajukan', function() {
			var id = $('#id').val();
			Swal.fire({
				title: 'Ajukan Permintaan Approval Forwarder?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: '<?= base_url("forwarder/ajukanApp") ?>',
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

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'forwarder'
			$('#main-modal #modal-form').attr('action', '<?= base_url("forwarder/addDetailForwarder") ?>')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			$('.btn-isactive').remove()
			var object = 'forwarder'

			// PENTING: Set action TERLEBIH DAHULU sebelum modal dibuka
			var id = $(this).attr("data-id")
			$('#main-modal-edit #modal-form-edit').attr('action', '<?= base_url("forwarder/updateDetailForwarder") ?>')

			// Tambah data-id ke form sebagai attribute (backup jika id_pelanggan tidak ter-submit)
			$('#main-modal-edit #modal-form-edit').attr('data-detail-id', id)

			$('#main-modal-edit').modal()

			// DEBUG: Log di console
			console.log("btn-edit clicked, encrypted id from button:", id);

			fetch('<?= base_url("forwarder/editDetailForwarder/") ?>' + id).then(function(resp) {
				return resp.json()
			}).then(function(data) {
				console.log("editDetailForwarder response:", data);

				// PENTING: Set id_pelanggan dengan data dari response (encrypted ID dari controller)
				// Gunakan data[0].id dari response yang sudah di-encrypt di controller
				$('#main-modal-edit #id_pelanggan').val(data[0].id);
				console.log("Set id_pelanggan to:", data[0].id);

				// Reset all currency dropdowns to IDR (2) since database values are already stored in IDR
				$('#main-modal-edit select[name^="uang_"]').val('2');

				// Verifikasi field ter-set
				var id_pelanggan_value = $('#main-modal-edit #id_pelanggan').val();
				console.log("Verified id_pelanggan value:", id_pelanggan_value);

				$('#main-modal-edit #id_forwarder').val(data[0].id_forwarder)
				$('#main-modal-edit #asal_thc').val(data[0].asal_thc);
				$('#main-modal-edit #asal_bl_fee').val(data[0].asal_bl_fee);
				$('#main-modal-edit #asal_vgm').val(data[0].asal_vgm);
				$('#main-modal-edit #asal_agaency_fee').val(data[0].asal_agaency_fee);
				$('#main-modal-edit #asal_handling_fee').val(data[0].asal_handling_fee);
				$('#main-modal-edit #asal_transportasi').val(data[0].asal_transportasi);
				$('#main-modal-edit #asal_loading').val(data[0].asal_loading);
				$('#main-modal-edit #asal_custom').val(data[0].asal_custom);
				$('#main-modal-edit #asal_pickup').val(data[0].asal_pickup);
				$('#main-modal-edit #asal_seal').val(data[0].asal_seal);
				$('#main-modal-edit #asal_shipping').val(data[0].asal_shipping);
				$('#main-modal-edit #asal_cfs').val(data[0].asal_cfs);
				$('#main-modal-edit #asal_dg').val(data[0].asal_dg);
				$('#main-modal-edit #asal_psa').val(data[0].asal_psa);
				$('#main-modal-edit #asal_other').val(data[0].asal_other);
				$('#main-modal-edit #freight_ocean').val(data[0].freight_ocean);
				$('#main-modal-edit #freight_air').val(data[0].freight_air);
				$('#main-modal-edit #tuj_cfs').val(data[0].tuj_cfs);
				$('#main-modal-edit #tuj_doc').val(data[0].tuj_doc);
				$('#main-modal-edit #tuj_agency_fee').val(data[0].tuj_agency_fee);
				$('#main-modal-edit #tuj_handling').val(data[0].tuj_handling);
				$('#main-modal-edit #tuj_do').val(data[0].tuj_do);
				$('#main-modal-edit #tuj_admin').val(data[0].tuj_admin);
				$('#main-modal-edit #tuj_devanning').val(data[0].tuj_devanning);
				$('#main-modal-edit #tuj_fordwarding_fee').val(data[0].tuj_fordwarding_fee);
				$('#main-modal-edit #tuj_mechanics').val(data[0].tuj_mechanics);
				$('#main-modal-edit #tuj_other').val(data[0].tuj_other);
				$('#main-modal-edit #cust_clearance').val(data[0].cust_clearance);
				$('#main-modal-edit #cust_red_line').val(data[0].cust_red_line);
				$('#main-modal-edit #cust_handling').val(data[0].cust_handling);
				$('#main-modal-edit #cust_admin_fee').val(data[0].cust_admin_fee);
				$('#main-modal-edit #cust_pib_fee').val(data[0].cust_pib_fee);
				$('#main-modal-edit #cust_transfer').val(data[0].cust_transfer);
				$('#main-modal-edit #cust_storage').val(data[0].cust_storage);
				$('#main-modal-edit #cust_asu').val(data[0].cust_asu);
				$('#main-modal-edit #cust_other').val(data[0].cust_other);
				//$('#main-modal-edit #oth_do').val(data[0].oth_do);
				$('#main-modal-edit #oth_storage').val(data[0].oth_storage);
				$('#main-modal-edit #oth_trucking').val(data[0].oth_trucking);
				$('#main-modal-edit #oth_handling').val(data[0].oth_handling);
				$('#main-modal-edit #oth_buruh').val(data[0].oth_buruh);
				$('#main-modal-edit #oth_adm').val(data[0].oth_adm);
				$('#main-modal-edit #oth_other').val(data[0].oth_other);
				$('#main-modal-edit #ins_nilai').val(data[0].ins_nilai);
				$('#main-modal-edit #ins_jenis').val(data[0].ins_jenis);
				$('#main-modal-edit #ppn').val(data[0].ppn);
				$('#main-modal-edit #dipilih').val(data[0].dipilih);
				$('#main-modal-edit #alasan').val(data[0].alasan);
				// Tambahan: Mengisi nilai keterangan saat edit
				$('#main-modal-edit #keterangan').val(data[0].keterangan);
				$('#main-modal-edit #link_invoice').val(data[0].link_invoice);
				$('#main-modal-edit #link_packing').val(data[0].link_packing);
				$('#main-modal-edit #link_sph_for').val(data[0].link_sph_for);
				$('#main-modal-edit #link_sph_ins').val(data[0].link_sph_ins);
				$('#main-modal-edit #id_app').val(data[0].id_app);

				// Double-check: Verifikasi semua field penting ter-set
				console.log("Final id_pelanggan before submit:", $('#main-modal-edit #id_pelanggan').val());
				console.log("Final id_app before submit:", $('#main-modal-edit #id_app').val());
			})
		})

		$(document).on('click', '.btn-duplikat', function(e) {
			e.preventDefault(); // cegah langsung redirect
			var link = $(this).data('href'); // ambil link tujuan
			Swal.fire({
				title: 'Duplikat Data Ini?',
				text: 'Data akan disalin dan dibuka di halaman baru.',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya, Duplikat',
				cancelButtonText: 'Batal'
			}).then(function(result) {
				if (result.isConfirmed) {
					window.location.href = link;
				}
			});
		});

		$(document).on('click', '.btn-submit2', function() {
			var id = $('#id').val();
			var catatan_finance = $('#catatan_finance').val();
			Swal.fire({
				title: 'Submit Link Invoice Final?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: '<?= base_url("forwarder/UpdateAppeksFinance/") ?>' + id,
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

		// HANDLER KHUSUS UNTUK MODAL EDIT FORM
		// Override global btn-save untuk modal-form-edit dengan validasi khusus
		$(document).on('click', '#main-modal-edit .btn-save', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation(); // Cegah handler global.js ikut menangani klik yang sama

			const form = $(this).closest('form');
			const formId = form.attr('id');
			const url = form.attr('action');

			// Validasi
			if (!url || url === '#') {
				Swal.fire({
					title: 'Error',
					text: 'Form action tidak ter-set dengan benar',
					icon: 'error'
				});
				return false;
			}

			// Validasi id_pelanggan
			const id_pelanggan = $('#' + formId + ' #id_pelanggan').val();
			if (!id_pelanggan) {
				Swal.fire({
					title: 'Error',
					text: 'ID tidak ditemukan. Silakan buka modal edit lagi',
					icon: 'error'
				});
				return false;
			}

			// Validasi id_app
			const id_app = $('#' + formId + ' #id_app').val();
			if (!id_app) {
				Swal.fire({
					title: 'Error',
					text: 'ID Approval tidak ditemukan',
					icon: 'error'
				});
				return false;
			}

			console.log("Modal form-edit submit:");
			console.log("- Form ID:", formId);
			console.log("- Action URL:", url);
			console.log("- id_pelanggan:", id_pelanggan);
			console.log("- id_app:", id_app);

			Swal.fire({
				title: 'Simpan Data?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Batal'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						url: url,
						type: "POST",
						data: new FormData($('#' + formId)[0]),
						contentType: false,
						processData: false,
						dataType: "JSON",
						beforeSend: function() {
							Swal.fire({
								html: `<h4>Mohon Tunggu...</h4>`,
								icon: 'info',
								allowOutsideClick: false,
								timerProgressBar: true,
								showConfirmButton: false,
							})
						},
						success: function(resp) {
							console.log("Submit response:", resp);
							handleResponse(resp);
						},
						error: function(xhr, status, error) {
							console.error("Submit error:", error);
							Swal.fire({
								title: 'Error',
								text: 'Gagal mengirim request: ' + error,
								icon: 'error'
							});
						}
					});
				}
			})

			return false;
		})
	})

	function goBack() {
		window.history.back();
	}
</script>