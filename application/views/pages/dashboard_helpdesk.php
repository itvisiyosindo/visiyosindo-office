<header class="page-header">
	<h2><i class="icons icon-list"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
	</div>
</header>

<style>
	/* Helpdesk Executive Dashboard Custom Styles */
	.hpd-kpi-card {
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

	.hpd-kpi-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
		border-color: #cbd5e1;
	}

	.hpd-kpi-icon-box {
		width: 48px;
		height: 48px;
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		flex-shrink: 0;
	}

	.hpd-panel-card {
		background: #ffffff;
		border-radius: 12px;
		border: 1px solid #e2e8f0;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
		margin-bottom: 24px;
		overflow: hidden;
	}

	.hpd-panel-header {
		background: #f8fafc;
		border-bottom: 1px solid #e2e8f0;
		padding: 14px 20px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 10px;
	}

	.hpd-panel-title {
		font-size: 15px;
		font-weight: 700;
		color: #1e293b;
		margin: 0;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.table-hpd thead th {
		background-color: #f1f5f9 !important;
		color: #334155 !important;
		font-size: 12px !important;
		font-weight: 700 !important;
		text-transform: uppercase !important;
		letter-spacing: 0.3px !important;
		border-bottom: 1px solid #e2e8f0 !important;
		padding: 10px 12px !important;
	}

	.table-hpd tbody td {
		font-size: 12.5px !important;
		color: #1e293b !important;
		padding: 10px 12px !important;
		vertical-align: middle !important;
	}
</style>

<div class="row">
	<!-- 1. Executive Top Banner Overview -->
	<div class="col-12 mb-3">
		<div class="card p-3 p-md-4 border-0" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-radius: 12px; color: #ffffff; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.12);">
			<div class="d-flex flex-wrap justify-content-between align-items-center">
				<div class="d-flex align-items-center">
					<div style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.2); border: 1px solid rgba(59, 130, 246, 0.35); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; color: #60a5fa; margin-right: 14px; flex-shrink: 0;">
						<i class="fas fa-headset"></i>
					</div>
					<div>
						<h3 style="font-size: 18px; font-weight: 700; color: #ffffff; margin: 0 0 4px 0;">Executive Overview & Analytics Helpdesk</h3>
						<p style="font-size: 12.5px; color: #94a3b8; margin: 0;">Monitoring tiket komplain pelanggan, penanganan keluhan teknis, dan performa service level agreement (SLA).</p>
					</div>
				</div>
				<div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
					<div class="d-flex align-items-center bg-white bg-opacity-10 rounded px-2 py-1" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);">
						<small class="text-white-50 mr-2"><i class="fas fa-calendar-alt"></i> Tahun:</small>
						<input type="text"
							data-plugin-datepicker
							data-plugin-options='{"orientation": "bottom", "format": "yyyy", "minViewMode": "years"}'
							class="form-control form-control-sm text-center font-weight-bold"
							id="filter_year"
							value="<?= isset($tahun_sekarang) ? $tahun_sekarang : date('Y') ?>"
							style="width: 85px; height: 28px; background: #ffffff; color: #0f172a; border-radius: 6px; font-size: 12.5px;"
							required>
					</div>
					<a href="<?= base_url('tiket') ?>" class="btn btn-sm btn-primary ml-2" style="font-size: 12px;">
						<i class="fas fa-ticket-alt mr-1"></i> Data Tiket
					</a>
					<a href="<?= base_url('it_maintenance') ?>" class="btn btn-sm btn-info ml-1" style="font-size: 12px;">
						<i class="fas fa-tools mr-1"></i> IT Maintenance
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- 2. Four Executive Helpdesk KPI Cards -->
	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('tiket') ?>" class="hpd-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Total Tiket</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #1e40af; margin: 4px 0 2px 0;">
							<?= isset($total_tiket) ? number_format($total_tiket, 0, ',', '.') : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #2563eb; font-weight: 600;"><i class="fas fa-database mr-1"></i> Seluruh Riwayat Tiket</span>
					</div>
					<div class="hpd-kpi-icon-box" style="background: #eff6ff; color: #2563eb;">
						<i class="fas fa-ticket-alt"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('tiket') ?>" class="hpd-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Tiket Baru (Open)</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #dc2626; margin: 4px 0 2px 0;">
							<?= isset($tiket_baru) ? $tiket_baru : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #ef4444; font-weight: 600;"><i class="fas fa-bell mr-1"></i> Menunggu Respon</span>
					</div>
					<div class="hpd-kpi-icon-box" style="background: #fef2f2; color: #ef4444;">
						<i class="fas fa-envelope-open-text"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('tiket') ?>" class="hpd-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Sedang Ditangani</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #d97706; margin: 4px 0 2px 0;">
							<?= isset($tiket_proses) ? $tiket_proses : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #d97706; font-weight: 600;"><i class="fas fa-spinner fa-spin mr-1"></i> Dalam Pengerjaan</span>
					</div>
					<div class="hpd-kpi-icon-box" style="background: #fffbeb; color: #d97706;">
						<i class="fas fa-tools"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('tiket') ?>" class="hpd-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Tiket Selesai</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #047857; margin: 4px 0 2px 0;">
							<?= isset($tiket_selesai) ? $tiket_selesai : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #10b981; font-weight: 600;"><i class="fas fa-check-circle mr-1"></i> Solved / Closed</span>
					</div>
					<div class="hpd-kpi-icon-box" style="background: #ecfdf5; color: #10b981;">
						<i class="fas fa-clipboard-check"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<!-- 3. Charts: Trend Tiket Masuk vs Selesai & Top Kategori -->
	<div class="col-lg-8 mb-4">
		<div class="hpd-panel-card h-100">
			<div class="hpd-panel-header">
				<h5 class="hpd-panel-title">
					<i class="fas fa-chart-line text-primary"></i> Trend Tiket Masuk vs Selesai (<span id="hpd-span-tahun"><?= date('Y') ?></span>)
				</h5>
				<span class="badge" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 11px;">
					Inflow vs Resolved
				</span>
			</div>
			<div class="card-body p-3">
				<div style="height: 260px; position: relative;">
					<canvas id="chartHpdTransaksi"></canvas>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-4 mb-4">
		<div class="hpd-panel-card h-100">
			<div class="hpd-panel-header">
				<h5 class="hpd-panel-title">
					<i class="fas fa-chart-pie text-success"></i> Kategori Tiket Terbanyak
				</h5>
			</div>
			<div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
				<div style="height: 200px; width: 100%; position: relative;">
					<canvas id="chartHpdKategori"></canvas>
				</div>
				<div class="w-100 mt-2">
					<ul class="list-unstyled mb-0" style="font-size: 12px;">
						<?php 
						$colors = ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4'];
						if (!empty($kategori_tiket)) {
							$idx = 0;
							foreach ($kategori_tiket as $row) {
								$col = isset($colors[$idx]) ? $colors[$idx] : '#64748b';
								echo '<li class="d-flex justify-content-between align-items-center py-1 border-bottom">';
								echo '<span><i class="fas fa-circle mr-1" style="color:' . $col . '; font-size: 8px;"></i> ' . ($row->nama_kategori ?: 'Umum') . '</span>';
								echo '<span class="font-weight-bold text-dark">' . $row->total . ' tiket</span>';
								echo '</li>';
								$idx++;
							}
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<!-- 4. Tiket Aktif yang Butuh Penanganan Segera -->
	<div class="col-12 mb-4">
		<div class="hpd-panel-card">
			<div class="hpd-panel-header">
				<h5 class="hpd-panel-title">
					<i class="fas fa-tasks text-warning"></i> Tiket Aktif yang Sedang Berjalan
				</h5>
				<a href="<?= base_url('tiket') ?>" class="btn btn-sm btn-outline-primary" style="font-size: 12px;">
					Buka Semua Tiket <i class="fas fa-arrow-right ml-1"></i>
				</a>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover table-hpd mb-0">
						<thead>
							<tr>
								<th>Kode Tiket</th>
								<th>Pelanggan</th>
								<th>Subject / Masalah</th>
								<th>Kategori</th>
								<th class="text-center">Prioritas</th>
								<th class="text-center">Status</th>
								<th>Tanggal Masuk</th>
								<th class="text-center">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($tiket_terbaru)) {
								foreach ($tiket_terbaru as $row) { 
									$idEnc = encrypt($row->id_tiket);
									?>
									<tr>
										<td class="font-weight-bold text-primary">
											<a href="<?= base_url('tiket/show/detail_tiket/' . $idEnc) ?>" class="text-decoration-none">
												<?= $row->kode_tiket ?>
											</a>
										</td>
										<td class="font-weight-semibold text-dark"><?= $row->nama_pelanggan ?: '-' ?></td>
										<td><?= $row->subject ?></td>
										<td><span class="badge badge-light border text-dark" style="font-size: 11px;"><?= $row->nama_kategori ?: 'Umum' ?></span></td>
										<td class="text-center">
											<?php 
											if ($row->prioritas == 4) {
												echo '<span class="badge badge-danger font-weight-bold" style="font-size: 10.5px;">Urgent</span>';
											} elseif ($row->prioritas == 3) {
												echo '<span class="badge badge-warning font-weight-bold" style="font-size: 10.5px;">High</span>';
											} elseif ($row->prioritas == 2) {
												echo '<span class="badge badge-info font-weight-normal" style="font-size: 10.5px;">Medium</span>';
											} else {
												echo '<span class="badge badge-secondary font-weight-normal" style="font-size: 10.5px;">Low</span>';
											}
											?>
										</td>
										<td class="text-center">
											<?php 
											if ($row->status_tiket == 1) {
												echo '<span class="badge badge-danger font-weight-bold" style="font-size: 10.5px;">Open (Baru)</span>';
											} elseif ($row->status_tiket == 2) {
												echo '<span class="badge badge-warning font-weight-bold" style="font-size: 10.5px;">In Progress</span>';
											} elseif ($row->status_tiket == 3) {
												echo '<span class="badge badge-warning font-weight-bold" style="font-size: 10.5px;">Revisi</span>';
											} else {
												echo '<span class="badge badge-success font-weight-bold" style="font-size: 10.5px;">Selesai</span>';
											}
											?>
										</td>
										<td class="text-muted" style="font-size: 12px;"><?= date('d M Y, H:i', strtotime($row->created_at)) ?></td>
										<td class="text-center">
											<a href="<?= base_url('tiket/show/detail_tiket/' . $idEnc) ?>" class="btn btn-xs btn-primary" title="Detail Tiket" style="font-size: 11px; padding: 3px 8px;">
												<i class="fas fa-eye"></i> Detail
											</a>
										</td>
									</tr>
								<?php }
							} else { ?>
								<tr>
									<td colspan="8" class="text-center py-4 text-muted">
										<i class="fas fa-check-circle text-success mr-1"></i> Tidak ada tiket terbuka yang membutuhkan penanganan saat ini.
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- 5. Log Aktivitas Pengguna -->
	<div class="col-12 mb-4">
		<div class="hpd-panel-card">
			<div class="hpd-panel-header">
				<h5 class="hpd-panel-title">
					<i class="fas fa-history text-secondary"></i> Log Aktivitas Pengguna
				</h5>
				<div class="d-flex align-items-center">
					<small class="text-muted mr-2">Filter Bulan:</small>
					<input type="text"
						data-plugin-datepicker
						data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}'
						class="form-control form-control-sm"
						id="filter_month"
						placeholder="Pilih Bulan"
						style="width: 130px; height: 30px; font-size: 12.5px; border-radius: 6px;">
				</div>
			</div>
			<div class="card-body p-3">
				<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover w-100" id="kt_table_1">
						<thead class="bg-light">
							<tr>
								<th style="width: 50px; text-align: center;">#</th>
								<th>Pengguna</th>
								<th>Aksi</th>
								<th>Keterangan</th>
								<th style="width: 170px;">Tanggal & Waktu</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const baseUrl = "<?= base_url(); ?>";
		const bulanNames = [
			"Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
			"Jul", "Agu", "Sep", "Okt", "Nov", "Des"
		];

		let chartHpdInstance = null;
		let currentYear = $('#filter_year').val() || new Date().getFullYear();

		function loadHelpdeskChart(year) {
			$('#hpd-span-tahun').text(year);
			$.ajax({
				url: baseUrl + 'dashboard_helpdesk/chart_data_tiket',
				method: 'GET',
				data: { tahun: year },
				dataType: 'json',
				success: function(data) {
					let labels = [];
					let dataMasuk = [];
					let dataSelesai = [];

					data.forEach(function(item) {
						labels.push(bulanNames[item.month - 1]);
						dataMasuk.push(item.masuk);
						dataSelesai.push(item.selesai);
					});

					const chartData = {
						labels: labels,
						datasets: [
							{
								label: 'Tiket Masuk',
								data: dataMasuk,
								backgroundColor: 'rgba(37, 99, 235, 0.85)',
								borderColor: '#2563eb',
								borderWidth: 1.5,
								borderRadius: 6,
								maxBarThickness: 18
							},
							{
								label: 'Tiket Selesai (Solved)',
								data: dataSelesai,
								backgroundColor: 'rgba(16, 185, 129, 0.85)',
								borderColor: '#10b981',
								borderWidth: 1.5,
								borderRadius: 6,
								maxBarThickness: 18
							}
						]
					};

					const canvas = document.getElementById('chartHpdTransaksi');
					if (!canvas) return;

					if (chartHpdInstance) {
						chartHpdInstance.destroy();
					}

					const ctx = canvas.getContext('2d');
					chartHpdInstance = new Chart(ctx, {
						type: 'bar',
						data: chartData,
						options: {
							responsive: true,
							maintainAspectRatio: false,
							scales: {
								y: {
									beginAtZero: true,
									grid: { color: '#f1f5f9' },
									ticks: {
										precision: 0,
										font: { family: 'Plus Jakarta Sans', size: 11 }
									}
								},
								x: {
									grid: { display: false },
									ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
								}
							},
							plugins: {
								legend: {
									position: 'top',
									labels: {
										boxWidth: 12,
										font: { family: 'Plus Jakarta Sans', size: 11.5, weight: '600' }
									}
								},
								tooltip: {
									backgroundColor: '#1e293b',
									titleFont: { size: 12, weight: 'bold' },
									bodyFont: { size: 11.5 },
									padding: 10,
									cornerRadius: 8
								}
							}
						}
					});
				}
			});
		}

		// Initial load chart
		loadHelpdeskChart(currentYear);

		$('#filter_year').change(function() {
			currentYear = $(this).val() || new Date().getFullYear();
			loadHelpdeskChart(currentYear);
		});

		// Donut Chart Kategori Tiket
		const kategoriCanvas = document.getElementById('chartHpdKategori');
		if (kategoriCanvas) {
			const katLabels = <?= json_encode(!empty($kategori_tiket) ? array_map(function($k) { return $k->nama_kategori ?: 'Umum'; }, $kategori_tiket) : ['Umum']) ?>;
			const katData = <?= json_encode(!empty($kategori_tiket) ? array_map(function($k) { return (int)$k->total; }, $kategori_tiket) : [1]) ?>;

			const ctxKat = kategoriCanvas.getContext('2d');
			new Chart(ctxKat, {
				type: 'doughnut',
				data: {
					labels: katLabels,
					datasets: [{
						data: katData,
						backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4'],
						borderWidth: 2,
						borderColor: '#ffffff'
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					cutout: '68%',
					plugins: {
						legend: { display: false }
					}
				}
			});
		}

		// Log Aktivitas DataTables
		var logTable = $('#kt_table_1').DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			order: [[4, 'desc']],
			ajax: {
				url: '<?= base_url("dashboard/pagination_log") ?>',
				type: 'POST',
				data: function(e) {
					e.filter_month = $('#filter_month').val();
					e.csrf_token = typeof token !== 'undefined' ? token : '';
				}
			},
			language: {
				search: "_INPUT_",
				searchPlaceholder: "Cari riwayat aksi...",
				lengthMenu: "_MENU_ data/hal",
				emptyTable: "Tidak ada riwayat aktivitas",
				zeroRecords: "Tidak ada data yang cocok",
				info: "Menampilkan _START_-_END_ dari _TOTAL_",
				infoEmpty: "0 data",
				paginate: {
					next: "Selanjutnya",
					previous: "Sebelumnya"
				}
			},
			columnDefs: [
				{ targets: [0], className: 'text-center font-weight-bold text-muted' },
				{ targets: [1], className: 'font-weight-semibold text-dark' },
				{ targets: [4], className: 'text-muted text-nowrap' }
			]
		});

		$('#filter_month').change(function() {
			logTable.ajax.reload();
		});
	});
</script>