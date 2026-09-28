<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half">

<head>
	<base href="<?= base_url() ?>">
	<meta charset="UTF-8">
	<title> Rekapitulasi PBOK Masa Menjabat | <?= $this->config->item('apps_name') ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<!-- Web Fonts  -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:100,300,400,600,700,800,900" rel="stylesheet" type="text/css">

	<!-- Vendor CSS -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/font-awesome/css/all.min.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>css/theme.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>css/custom.css">
	<link rel="shortcut icon" href="<?= base_url('assets/') ?>img/favicon.png" />

	<style>
		html, body {
			background: white !important;
			color: #000 !important;
			font-family: 'Times New Roman', Times, serif;
		}

		.print-container {
			padding: 10px 20px;
		}

		hr.double-line {
			border-top: 3px double #000;
			margin: 5px 0 15px 0;
		}

		.table-info-emp td {
			padding: 4px 8px;
			font-size: 14px;
		}

		.table-rekap {
			width: 100%;
			border-collapse: collapse;
			margin-top: 15px;
			font-size: 13px;
		}

		.table-rekap th, .table-rekap td {
			border: 1px solid #000;
			padding: 6px 8px;
		}

		.table-rekap th {
			background-color: #b7d5ac !important;
			color: #000;
			font-weight: bold;
			text-align: center;
		}

		.badge-status {
			display: inline-block;
			padding: 2px 6px;
			font-size: 11px;
			font-weight: bold;
			border-radius: 4px;
		}

		.bg-approved { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
		.bg-pending { background-color: #cce5ff; color: #004085; border: 1px solid #b8daff; }
		.bg-rejected { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

		@media print {
			body {
				padding: 0;
				margin: 0;
			}
			.no-print {
				display: none !important;
			}
		}
	</style>
</head>

<body>
	<!-- Menu Bar Link Office (Hanya Tampil di Layar / Preview, Sembunyi Saat Print) -->
	<div class="no-print" style="background: #f8f9fa; padding: 12px 20px; border-bottom: 1px solid #dee2e6; margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between;">
		<div>
			<a href="<?= base_url('surat/show/list_pbok') ?>" class="btn btn-sm btn-secondary" style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 600;">
				<i class="fas fa-arrow-left"></i> &nbsp;Kembali ke Menu Office (PBOK)
			</a>
		</div>
		<div style="display: flex; gap: 8px;">
			<a href="<?= base_url('surat/show/list_pbok') ?>" class="btn btn-sm btn-info" style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 600;">
				<i class="fas fa-list-alt"></i> &nbsp;Menu Daftar Office
			</a>
			<button type="button" onclick="window.print()" class="btn btn-sm btn-primary" style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 600;">
				<i class="fas fa-print"></i> &nbsp;Cetak / Print Laporan
			</button>
		</div>
	</div>

	<section class="body" style="padding-top:0px;">
		<img src="assets/img/kop_baru.jpg" alt="Kop Surat" style="width: 100%;" />
		<hr class="double-line">

		<div class="print-container">
			<!-- Judul Laporan -->
			<div class="text-center mb-3">
				<h4 style="font-family:'Times New Roman', Times, serif; font-weight: bold; margin-bottom: 2px; color: #000;">
					REKAPITULASI PENGAJUAN BIAYA OPERASIONAL KANTOR (PBOK)
				</h4>
				<h5 style="font-family:'Times New Roman', Times, serif; font-weight: normal; margin-top: 0; color: #333;">
					SELAMA MASA JABATAN KARYAWAN
				</h5>
			</div>

			<!-- Informasi Karyawan / Pengaju -->
			<table class="table-info-emp" width="100%">
				<tr>
					<td width="18%"><b>Nama Pengaju</b></td>
					<td width="32%">: <?= htmlspecialchars($pengguna[0]->nama) ?></td>
					<td width="18%"><b>NPP / No. Pegawai</b></td>
					<td width="32%">: <?= htmlspecialchars($pengguna[0]->no_pegawai ?: '-') ?></td>
				</tr>
				<tr>
					<td><b>Jabatan</b></td>
					<td>: <?= htmlspecialchars($pengguna[0]->jabatan ?: '-') ?></td>
					<td><b>Masa Menjabat</b></td>
					<td>: <?= htmlspecialchars($masa_menjabat_str) ?></td>
				</tr>
				<tr>
					<td><b>Status Karyawan</b></td>
					<td>: <?= ucfirst(htmlspecialchars($pengguna[0]->status_karyawan ?: 'Tetap')) ?></td>
					<td><b>Total Pengajuan</b></td>
					<td>: <?= count($pbok_list) ?> Surat PBOK</td>
				</tr>
			</table>

			<!-- Tabel Rekapitulasi PBOK -->
			<table class="table-rekap">
				<thead>
					<tr>
						<th width="4%">No</th>
						<th width="15%">Kode Surat PBOK</th>
						<th width="10%">Tanggal</th>
						<th width="31%">Item / Keterangan Belanja</th>
						<th width="14%" class="no-print">Link Office</th>
						<th width="12%">Status</th>
						<th width="14%">Total Nominal</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$no = 1;
					$grand_total_all = 0;
					if (!empty($pbok_list)) :
						foreach ($pbok_list as $row) :
							$grand_total_all += $row->grand_total;
							
							$status_label = 'Diajukan';
							$status_class = 'bg-pending';
							$penolak_info = '';

							if ($row->aju_ttd1 == "2") {
								$status_label = 'Ditolak';
								$status_class = 'bg-rejected';
								$penolak_info = 'Head of Accounting & Tax';
							} else if ($row->aju_ttd2 == "2") {
								$status_label = 'Ditolak';
								$status_class = 'bg-rejected';
								$penolak_info = 'General Manager';
							} else if ($row->aju_ttd3 == "2") {
								$status_label = 'Ditolak';
								$status_class = 'bg-rejected';
								$penolak_info = 'Director of Corp Planning';
							} else if ($row->stat_persetujuan == 2 || $row->stat_persetujuan == 3) {
								$status_label = 'Ditolak';
								$status_class = 'bg-rejected';
								$penolak_info = 'Manajemen / Verifikator';
							} else if ($row->stat_persetujuan == 1) {
								if ($row->aju_ttd3 == "1") {
									$status_label = 'Disetujui Completes';
									$status_class = 'bg-approved';
								} else if ($row->aju_ttd2 == "1") {
									$status_label = 'Disetujui GM';
									$status_class = 'bg-approved';
								} else {
									$status_label = 'Disetujui Finance';
									$status_class = 'bg-approved';
								}
							}

							$detail_url = base_url("surat/show/detail_surat/pbok/" . $row->idPbok . "/1");
					?>
							<tr>
								<td style="text-align:center;"><?= $no++ ?></td>
								<td style="text-align:center;">
									<a href="<?= $detail_url ?>" target="_blank" style="color: #0056b3; font-weight: bold; text-decoration: underline;" title="Buka Detail Surat PBOK di System Office">
										<?= htmlspecialchars($row->kode) ?>
									</a>
								</td>
								<td style="text-align:center;"><?= date('d-m-Y', strtotime($row->tgl_pengajuan)) ?></td>
								<td>
									<?= htmlspecialchars($row->item_detail ?: '-') ?>
									<?php if (!empty($row->lampiran)) : ?>
										<br>
										<div style="margin-top: 4px; font-size: 11px; color: #0056b3;">
											<i class="fas fa-paperclip"></i> <b>Lampiran:</b> 
											<a href="<?= htmlspecialchars($row->lampiran) ?>" target="_blank" style="color: #0056b3; text-decoration: underline; word-break: break-all;">
												<?= htmlspecialchars($row->lampiran) ?>
											</a>
										</div>
									<?php endif; ?>
								</td>
								<td style="text-align:center;" class="no-print">
									<a href="<?= $detail_url ?>" target="_blank" class="btn btn-xs btn-outline-primary" style="display: inline-block; font-size: 11px; padding: 2px 8px; border: 1px solid #0056b3; color: #0056b3; background-color: #f0f7ff; border-radius: 4px; text-decoration: none; font-weight: bold;" title="Buka Detail Surat di Office">
										<i class="fas fa-external-link-alt"></i> Link Office
									</a>
								</td>
								<td style="text-align:center;">
									<span class="badge-status <?= $status_class ?>"><?= $status_label ?></span>
									<?php if (!empty($penolak_info)) : ?>
										<div style="margin-top: 4px; font-size: 10px; color: #721c24; font-weight: bold;">
											Oleh: <?= htmlspecialchars($penolak_info) ?>
										</div>
									<?php endif; ?>
								</td>
								<td style="text-align:right;">Rp <?= number_format($row->grand_total, 0, ',', '.') ?>,-</td>
							</tr>
					<?php 
						endforeach;
					?>
						<tr style="background-color: #e2efda; font-weight: bold;">
							<td colspan="4" style="text-align:right; font-size: 13px; background-color: #e2efda !important; vertical-align: middle;">
								<b>TOTAL KESELURUHAN PENGAJUAN PBOK:</b>
							</td>
							<td class="no-print" style="background-color: #e2efda !important;"></td>
							<td style="background-color: #e2efda !important;"></td>
							<td style="text-align:right; font-size: 13px; background-color: #e2efda !important; vertical-align: middle;">
								<b>Rp <?= number_format($grand_total_all, 0, ',', '.') ?>,-</b>
							</td>
						</tr>
					<?php
					else :
					?>
						<tr>
							<td colspan="7" style="text-align:center; padding: 20px;">
								<i>Belum ada data pengajuan PBOK selama masa menjabat.</i>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>

			<!-- Seksi Tanda Tangan -->
			<table border="0" style="width:100%; margin-top: 35px; font-size:14px;">
				<tbody>
					<tr>
						<td style="text-align:right;" colspan="3">
							Jakarta, <?= date('d F Y') ?>
						</td>
					</tr>
					<tr style="height: 20px;">
						<td style="text-align:center; width:33%;">Diajukan Oleh,</td>
						<td style="text-align:center; width:33%;">Diverifikasi Oleh,</td>
						<td style="text-align:center; width:34%;">Disetujui Oleh,</td>
					</tr>
					<tr style="height:75px;">
						<td style="text-align:center; vertical-align:bottom;">
							<u><b><?= htmlspecialchars($pengguna[0]->nama) ?></b></u><br>
							<i><?= htmlspecialchars($pengguna[0]->jabatan ?: 'Pengaju PBOK') ?></i>
						</td>
						<td style="text-align:center; vertical-align:bottom;">
							<u><b>Dirangga Madali</b></u><br>
							<i>Head of Accounting & Tax</i>
						</td>
						<td style="text-align:center; vertical-align:bottom;">
							<u><b>Meilina Safitri</b></u><br>
							<i>Director of Corp Planning & Management</i>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<br>
		<img src="assets/img/kop_surat_bawah.jpg" alt="Kop Surat Bawah" style="width: 100%; margin-top: 20px;" />
	</section>

	<script>
		window.onload = function() {
			window.print();
		};
	</script>
</body>

</html>
