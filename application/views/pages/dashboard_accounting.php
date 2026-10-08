<header class="page-header">
	<h2><i class="icons icon-calculator"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
	</div>
</header>

<style>
	/* Accounting & Tax Executive Dashboard Custom Styles */
	.dash-banner {
		background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
		border-radius: 12px;
		color: #ffffff;
		box-shadow: 0 4px 20px rgba(15, 23, 42, 0.12);
		padding: 18px 22px;
		margin-bottom: 20px;
	}

	.dash-banner-icon {
		width: 48px;
		height: 48px;
		background: rgba(59, 130, 246, 0.2);
		border: 1px solid rgba(59, 130, 246, 0.35);
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 22px;
		color: #60a5fa;
		margin-right: 14px;
		flex-shrink: 0;
	}

	.dash-year-box {
		display: inline-flex;
		align-items: center;
		background: rgba(255, 255, 255, 0.1);
		border: 1px solid rgba(255, 255, 255, 0.2);
		border-radius: 8px;
		padding: 4px 10px;
	}

	.dash-year-box input {
		width: 72px;
		height: 28px;
		background: #ffffff;
		color: #0f172a;
		border: none;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		text-align: center;
		padding: 0 4px;
	}

	.acc-kpi-card {
		background: #ffffff;
		border-radius: 12px;
		border: 1px solid #e2e8f0;
		box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px 0 rgba(0, 0, 0, 0.02);
		transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
		position: relative;
		overflow: hidden;
		text-decoration: none !important;
		display: block;
		height: 100%;
	}

	.acc-kpi-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
		border-color: #cbd5e1;
	}

	.acc-kpi-icon-box {
		width: 48px;
		height: 48px;
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		flex-shrink: 0;
	}

	.acc-panel-card {
		background: #ffffff;
		border-radius: 12px;
		border: 1px solid #e2e8f0;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
		margin-bottom: 24px;
		overflow: hidden;
	}

	.acc-panel-header {
		background: #f8fafc;
		border-bottom: 1px solid #e2e8f0;
		padding: 14px 20px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 10px;
	}

	.acc-panel-title {
		font-size: 15px;
		font-weight: 700;
		color: #1e293b;
		margin: 0;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.table-acc thead th {
		background-color: #f1f5f9 !important;
		color: #334155 !important;
		font-size: 12px !important;
		font-weight: 700 !important;
		text-transform: uppercase !important;
		letter-spacing: 0.3px !important;
		border-bottom: 1px solid #e2e8f0 !important;
		padding: 10px 12px !important;
	}

	.table-acc tbody td {
		font-size: 12.5px !important;
		color: #1e293b !important;
		padding: 10px 12px !important;
		vertical-align: middle !important;
	}

	.nav-pills-acc .nav-link {
		border-radius: 8px;
		font-size: 13px;
		font-weight: 600;
		color: #64748b;
		padding: 7px 16px;
		border: 1px solid transparent;
		transition: all 0.2s ease;
		background: #f1f5f9;
		margin-right: 6px;
	}

	.nav-pills-acc .nav-link.active {
		background-color: #0f172a !important;
		color: #ffffff !important;
		box-shadow: 0 2px 6px rgba(15, 23, 42, 0.3);
	}

	.nav-pills-acc .nav-link:hover:not(.active) {
		background-color: #e2e8f0;
		color: #1e293b;
	}
</style>

<div class="row">
	<!-- 1. Executive Top Banner Overview -->
	<div class="col-12">
		<div class="dash-banner">
			<div class="row align-items-center">
				<div class="col-lg-6 col-12 mb-3 mb-lg-0">
					<div class="d-flex align-items-center">
						<div class="dash-banner-icon">
							<i class="fas fa-file-invoice-dollar"></i>
						</div>
						<div>
							<h3 style="font-size: 17px; font-weight: 700; color: #ffffff; margin: 0 0 4px 0;">Executive Overview & Analytics Accounting & Tax</h3>
							<p style="font-size: 12px; color: #94a3b8; margin: 0; line-height: 1.4;">Monitoring kepatuhan pelaporan SPT Masa/Tahunan, approval faktur pajak, arsip laporan keuangan, dan mitra pemasok.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-12 text-lg-right">
					<div class="d-inline-flex flex-wrap align-items-center justify-content-start justify-content-lg-end" style="gap: 8px;">
						<div class="dash-year-box">
							<small style="color: #cbd5e1; margin-right: 6px; font-size: 11.5px;"><i class="fas fa-calendar-alt mr-1"></i> Tahun:</small>
							<input type="text"
								data-plugin-datepicker
								data-plugin-options='{"orientation": "bottom", "format": "yyyy", "minViewMode": "years"}'
								class="form-control"
								id="filter_year"
								value="<?= isset($tahun_sekarang) ? $tahun_sekarang : date('Y') ?>"
								required>
						</div>
						<a href="<?= base_url('acc_pemasok') ?>" class="btn btn-sm btn-primary" style="font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 6px;">
							<i class="fas fa-truck-loading mr-1"></i> Pemasok
						</a>
						<a href="<?= base_url('surat_new/show/list_app_pajak') ?>" class="btn btn-sm btn-info" style="font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 6px;">
							<i class="fas fa-file-invoice mr-1"></i> Approval Faktur
						</a>
						<a href="<?= base_url('acc_bukti_lapor_pajak') ?>" class="btn btn-sm btn-success" style="font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 6px;">
							<i class="fas fa-stamp mr-1"></i> Lapor Pajak
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- 2. Four Key Performance Indicator (KPI) Cards -->
	<!-- KPI 1: Mitra Pemasok -->
	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('acc_pemasok') ?>" class="acc-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Mitra Pemasok</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #4338ca; margin: 4px 0 2px 0;">
							<?= number_format($total_pemasok, 0, ',', '.') ?>
						</h3>
						<span style="font-size: 11.5px; color: #059669; font-weight: 600;">
							<i class="fas fa-check-circle mr-1"></i> <?= $pemasok_aktif ?> Aktif | <?= $pemasok_baru ?> Baru
						</span>
					</div>
					<div class="acc-kpi-icon-box" style="background: #e0e7ff; color: #4338ca;">
						<i class="fas fa-truck-loading"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<!-- KPI 2: Approval Faktur Pajak -->
	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('surat_new/show/list_app_pajak') ?>" class="acc-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Approval Faktur Pajak</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #0284c7; margin: 4px 0 2px 0;">
							<?= number_format($total_faktur_pajak, 0, ',', '.') ?>
						</h3>
						<span style="font-size: 11.5px; color: #0284c7; font-weight: 600;">
							<i class="fas fa-check-double mr-1"></i> <?= $faktur_pajak_approved ?> Disetujui | <?= $faktur_pajak_pending ?> Proses
						</span>
					</div>
					<div class="acc-kpi-icon-box" style="background: #e0f2fe; color: #0284c7;">
						<i class="fas fa-file-invoice"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<!-- KPI 3: Bukti Lapor Pajak -->
	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('acc_bukti_lapor_pajak') ?>" class="acc-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Bukti Lapor Pajak</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #059669; margin: 4px 0 2px 0;">
							<?= number_format($total_bukti_lapor, 0, ',', '.') ?>
						</h3>
						<span style="font-size: 11.5px; color: #059669; font-weight: 600;">
							<i class="fas fa-calendar-check mr-1"></i> <?= $bukti_lapor_tahun_ini ?> Laporan di <?= $tahun_sekarang ?>
						</span>
					</div>
					<div class="acc-kpi-icon-box" style="background: #dcfce7; color: #059669;">
						<i class="fas fa-stamp"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<!-- KPI 4: Laporan Keuangan & SK -->
	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('acc_laporan_keuangan') ?>" class="acc-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Laporan Keuangan</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #d97706; margin: 4px 0 2px 0;">
							<?= number_format($total_lap_keuangan, 0, ',', '.') ?>
						</h3>
						<span style="font-size: 11.5px; color: #d97706; font-weight: 600;">
							<i class="fas fa-file-contract mr-1"></i> <?= $total_sk_ketentuan ?> Dokumen SK Terdata
						</span>
					</div>
					<div class="acc-kpi-icon-box" style="background: #fef3c7; color: #d97706;">
						<i class="fas fa-balance-scale"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<!-- 3. Analytics Charts Section -->
	<div class="col-lg-8 col-12 mb-4">
		<div class="acc-panel-card h-100 mb-0">
			<div class="acc-panel-header">
				<h4 class="acc-panel-title">
					<i class="fas fa-chart-bar text-primary"></i> Tren Transaksi & Kepatuhan Pajak (<span id="span_chart_year"><?= $tahun_sekarang ?></span>)
				</h4>
				<span class="badge badge-light px-2 py-1 font-weight-bold" style="color: #64748b;">Monthly Analytics</span>
			</div>
			<div class="card-body p-3" style="position: relative; height: 320px;">
				<canvas id="chart-accounting-monthly"></canvas>
			</div>
		</div>
	</div>

	<div class="col-lg-4 col-12 mb-4">
		<div class="acc-panel-card h-100 mb-0">
			<div class="acc-panel-header">
				<h4 class="acc-panel-title">
					<i class="fas fa-chart-pie text-success"></i> Komposisi Portofolio Data
				</h4>
			</div>
			<div class="card-body p-3" style="position: relative; height: 320px;">
				<canvas id="chart-accounting-donut"></canvas>
			</div>
		</div>
	</div>

	<!-- 4. Tabbed Data Management & Recent Records -->
	<div class="col-12 mb-4">
		<div class="acc-panel-card">
			<div class="acc-panel-header">
				<ul class="nav nav-pills nav-pills-acc" id="accDashboardTabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="tab-fp-link" data-toggle="pill" href="#tab-fp" role="tab">
							<i class="fas fa-file-invoice mr-1"></i> Approval Faktur Pajak Terbaru
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="tab-lapor-link" data-toggle="pill" href="#tab-lapor" role="tab">
							<i class="fas fa-stamp mr-1"></i> Bukti Lapor Pajak
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="tab-lapkeu-link" data-toggle="pill" href="#tab-lapkeu" role="tab">
							<i class="fas fa-balance-scale mr-1"></i> Laporan Keuangan
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="tab-sk-link" data-toggle="pill" href="#tab-sk" role="tab">
							<i class="fas fa-file-contract mr-1"></i> SK & Ketentuan
						</a>
					</li>
				</ul>
			</div>
			<div class="card-body p-0">
				<div class="tab-content" id="accDashboardTabsContent" style="padding: 16px;">
					<!-- TAB 1: Approval Faktur Pajak -->
					<div class="tab-pane fade show active" id="tab-fp" role="tabpanel">
						<div class="table-responsive">
							<table class="table table-hover table-acc mb-0">
								<thead>
									<tr>
										<th>No</th>
										<th>Kode Pengajuan</th>
										<th>Tanggal</th>
										<th>Pengaju / Marketing</th>
										<th>Customer</th>
										<th>No. PO</th>
										<th>Status Approval</th>
										<th class="text-center">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($faktur_pajak_terbaru)) {
										$no = 1;
										foreach ($faktur_pajak_terbaru as $fp) {
											$stat_badge = '<span class="badge badge-secondary">Menunggu</span>';
											if ($fp->status == 4) {
												$stat_badge = '<span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Approved</span>';
											} elseif ($fp->status == 9) {
												$stat_badge = '<span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>';
											} else {
												$stat_badge = '<span class="badge badge-warning px-2 py-1 text-dark"><i class="fas fa-clock mr-1"></i> Proses (' . $fp->status . '/4)</span>';
											}
									?>
											<tr>
												<td><?= $no++ ?></td>
												<td class="font-weight-bold" style="color: #1e40af;">
													<?= htmlspecialchars($fp->kode) ?>
												</td>
												<td><?= date('d/m/Y', strtotime($fp->tanggal)) ?></td>
												<td>
													<div class="font-weight-semibold"><?= htmlspecialchars($fp->nama_pengaju) ?></div>
													<small class="text-muted">Mkt: <?= htmlspecialchars($fp->nama_marketing) ?></small>
												</td>
												<td><?= htmlspecialchars($fp->nama_customer) ?></td>
												<td><?= htmlspecialchars($fp->no_po ?: '-') ?></td>
												<td><?= $stat_badge ?></td>
												<td class="text-center">
													<a href="<?= base_url('surat_new/show/detail/app_pajak/' . $fp->id) ?>" class="btn btn-xs btn-primary" title="Lihat Detail">
														<i class="fas fa-eye"></i> Detail
													</a>
													<?php if (!empty($fp->link_lampiran)) { ?>
														<a href="<?= htmlspecialchars($fp->link_lampiran) ?>" target="_blank" class="btn btn-xs btn-default text-info ml-1" title="Buka Lampiran GDrive">
															<i class="fas fa-external-link-alt"></i>
														</a>
													<?php } ?>
												</td>
											</tr>
										<?php }
									} else { ?>
										<tr>
											<td colspan="8" class="text-center py-4 text-muted">
												<i class="fas fa-info-circle mr-1"></i> Belum ada data pengajuan faktur pajak.
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>

					<!-- TAB 2: Bukti Lapor Pajak -->
					<div class="tab-pane fade" id="tab-lapor" role="tabpanel">
						<div class="table-responsive">
							<table class="table table-hover table-acc mb-0">
								<thead>
									<tr>
										<th>No</th>
										<th>Kategori Pajak</th>
										<th>Nama Dokumen</th>
										<th>Tahun Pelaporan</th>
										<th>Tanggal Lapor</th>
										<th>Batas Akhir</th>
										<th class="text-center">Dokumen</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($bukti_lapor_terbaru)) {
										$no = 1;
										foreach ($bukti_lapor_terbaru as $bl) { ?>
											<tr>
												<td><?= $no++ ?></td>
												<td>
													<span class="badge badge-info px-2 py-1 font-weight-bold"><?= htmlspecialchars($bl->kategori) ?></span>
												</td>
												<td class="font-weight-semibold" style="color: #0f172a;"><?= htmlspecialchars($bl->nama_dokumen) ?></td>
												<td><span class="badge badge-dark"><?= htmlspecialchars($bl->tahun_pelaporan) ?></span></td>
												<td><?= !empty($bl->tanggal_lapor) ? date('d/m/Y', strtotime($bl->tanggal_lapor)) : '-' ?></td>
												<td><?= !empty($bl->batas_akhir) ? date('d/m/Y', strtotime($bl->batas_akhir)) : '-' ?></td>
												<td class="text-center">
													<?php if (!empty($bl->link_dokumen)) { ?>
														<a href="<?= htmlspecialchars($bl->link_dokumen) ?>" target="_blank" class="btn btn-xs btn-outline-primary" style="border-radius: 4px;">
															<i class="fas fa-file-pdf mr-1"></i> Buka File
														</a>
													<?php } else { ?>
														<span class="text-muted small">-</span>
													<?php } ?>
												</td>
											</tr>
										<?php }
									} else { ?>
										<tr>
											<td colspan="7" class="text-center py-4 text-muted">
												<i class="fas fa-info-circle mr-1"></i> Belum ada bukti lapor pajak terdata.
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>

					<!-- TAB 3: Laporan Keuangan -->
					<div class="tab-pane fade" id="tab-lapkeu" role="tabpanel">
						<div class="table-responsive">
							<table class="table table-hover table-acc mb-0">
								<thead>
									<tr>
										<th>No</th>
										<th>Nama Dokumen</th>
										<th>Jenis Dokumen</th>
										<th>Tahun</th>
										<th>Keperluan</th>
										<th class="text-center">Dokumen</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($lap_keuangan_terbaru)) {
										$no = 1;
										foreach ($lap_keuangan_terbaru as $lk) { ?>
											<tr>
												<td><?= $no++ ?></td>
												<td class="font-weight-semibold" style="color: #0f172a;"><?= htmlspecialchars($lk->nama_dokumen) ?></td>
												<td>
													<span class="badge badge-primary px-2 py-1"><?= htmlspecialchars($lk->jenis_dokumen) ?></span>
												</td>
												<td><span class="badge badge-dark"><?= htmlspecialchars($lk->tahun_pelaporan) ?></span></td>
												<td><?= htmlspecialchars($lk->keperluan_dokumen ?: '-') ?></td>
												<td class="text-center">
													<?php if (!empty($lk->link_dokumen)) { ?>
														<a href="<?= htmlspecialchars($lk->link_dokumen) ?>" target="_blank" class="btn btn-xs btn-outline-primary" style="border-radius: 4px;">
															<i class="fas fa-file-pdf mr-1"></i> Buka File
														</a>
													<?php } else { ?>
														<span class="text-muted small">-</span>
													<?php } ?>
												</td>
											</tr>
										<?php }
									} else { ?>
										<tr>
											<td colspan="6" class="text-center py-4 text-muted">
												<i class="fas fa-info-circle mr-1"></i> Belum ada data laporan keuangan.
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>

					<!-- TAB 4: SK & Ketentuan -->
					<div class="tab-pane fade" id="tab-sk" role="tabpanel">
						<div class="table-responsive">
							<table class="table table-hover table-acc mb-0">
								<thead>
									<tr>
										<th>No</th>
										<th>Nama Dokumen SK</th>
										<th>Tanggal Terbit</th>
										<th>Masa Berlaku</th>
										<th>Status Berlaku</th>
										<th class="text-center">Dokumen</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($sk_ketentuan_terbaru)) {
										$no = 1;
										foreach ($sk_ketentuan_terbaru as $sk) {
											$is_expired = (!empty($sk->masa_berlaku) && strtotime($sk->masa_berlaku) < time());
									?>
											<tr>
												<td><?= $no++ ?></td>
												<td class="font-weight-semibold" style="color: #0f172a;"><?= htmlspecialchars($sk->nama_dokumen) ?></td>
												<td><?= !empty($sk->tanggal_dokumen) ? date('d/m/Y', strtotime($sk->tanggal_dokumen)) : '-' ?></td>
												<td><?= !empty($sk->masa_berlaku) ? date('d/m/Y', strtotime($sk->masa_berlaku)) : 'Seumur Hidup / Tetap' ?></td>
												<td>
													<?php if (empty($sk->masa_berlaku)) { ?>
														<span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Tetap</span>
													<?php } elseif ($is_expired) { ?>
														<span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation-triangle mr-1"></i> Kadaluwarsa</span>
													<?php } else { ?>
														<span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Aktif</span>
													<?php } ?>
												</td>
												<td class="text-center">
													<?php if (!empty($sk->link_dokumen)) { ?>
														<a href="<?= htmlspecialchars($sk->link_dokumen) ?>" target="_blank" class="btn btn-xs btn-outline-primary" style="border-radius: 4px;">
															<i class="fas fa-file-pdf mr-1"></i> Buka File
														</a>
													<?php } else { ?>
														<span class="text-muted small">-</span>
													<?php } ?>
												</td>
											</tr>
										<?php }
									} else { ?>
										<tr>
											<td colspan="6" class="text-center py-4 text-muted">
												<i class="fas fa-info-circle mr-1"></i> Belum ada dokumen SK & Ketentuan.
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Chart.js and Custom Script for Accounting Dashboard -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	$(document).ready(function() {
		var chartMonthly = null;
		var chartDonut = null;

		// 1. Initial Donut Chart
		var donutCtx = document.getElementById('chart-accounting-donut').getContext('2d');
		chartDonut = new Chart(donutCtx, {
			type: 'doughnut',
			data: {
				labels: <?= json_encode(array_keys($distribusi_accounting)) ?>,
				datasets: [{
					data: <?= json_encode(array_values($distribusi_accounting)) ?>,
					backgroundColor: [
						'#4f46e5', // Pemasok (Indigo)
						'#0284c7', // Faktur Pajak (Sky)
						'#059669', // Bukti Lapor (Emerald)
						'#d97706', // Lap Keuangan (Amber)
						'#8b5cf6'  // SK (Purple)
					],
					borderWidth: 2,
					borderColor: '#ffffff'
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: {
						position: 'bottom',
						labels: {
							boxWidth: 12,
							font: { size: 11.5, weight: '600' }
						}
					}
				},
				cutout: '62%'
			}
		});

		// 2. Function to Load & Render Monthly Bar Chart
		function loadMonthlyChart(year) {
			$('#span_chart_year').text(year);
			$.ajax({
				url: '<?= base_url("dashboard_accounting/chart_data_accounting") ?>',
				type: 'GET',
				data: { tahun: year },
				dataType: 'json',
				success: function(resp) {
					var ctx = document.getElementById('chart-accounting-monthly').getContext('2d');
					if (chartMonthly) {
						chartMonthly.destroy();
					}
					chartMonthly = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: resp.labels,
							datasets: [
								{
									label: 'Approval Faktur Pajak',
									data: resp.faktur_pajak,
									backgroundColor: 'rgba(2, 132, 199, 0.85)',
									borderColor: '#0284c7',
									borderWidth: 1,
									borderRadius: 5
								},
								{
									label: 'Bukti Lapor Pajak',
									data: resp.bukti_lapor,
									backgroundColor: 'rgba(5, 150, 105, 0.85)',
									borderColor: '#059669',
									borderWidth: 1,
									borderRadius: 5
								}
							]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							plugins: {
								legend: {
									position: 'top',
									labels: { font: { size: 12, weight: '600' } }
								}
							},
							scales: {
								y: {
									beginAtZero: true,
									ticks: { precision: 0 }
								},
								x: {
									grid: { display: false }
								}
							}
						}
					});
				}
			});
		}

		// Initial load
		loadMonthlyChart($('#filter_year').val());

		// Trigger change on datepicker select
		$('#filter_year').on('change', function() {
			var yr = $(this).val();
			if (yr) {
				loadMonthlyChart(yr);
			}
		});
	});
</script>
