<header class="page-header">
	<h2><i class="icons icon-list"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
	</div>
</header>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>

<style>
	/* Marketing Executive Dashboard Custom Styles */
	.mkt-kpi-card {
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

	.mkt-kpi-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
		border-color: #cbd5e1;
	}

	.mkt-kpi-icon-box {
		width: 48px;
		height: 48px;
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		flex-shrink: 0;
	}

	.mkt-panel-card {
		background: #ffffff;
		border-radius: 12px;
		border: 1px solid #e2e8f0;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
		margin-bottom: 24px;
		overflow: hidden;
	}

	.mkt-panel-header {
		background: #f8fafc;
		border-bottom: 1px solid #e2e8f0;
		padding: 14px 20px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 10px;
	}

	.mkt-panel-title {
		font-size: 15px;
		font-weight: 700;
		color: #1e293b;
		margin: 0;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.mkt-chart-card {
		background: #ffffff;
		border: 1px solid #e2e8f0;
		border-radius: 12px;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
		height: 100%;
		display: flex;
		flex-direction: column;
	}

	.mkt-chart-card .card-header {
		background: #f8fafc;
		border-bottom: 1px solid #e2e8f0;
		padding: 12px 18px;
		font-weight: 700;
		font-size: 14px;
		color: #1e293b;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.mkt-chart-card .card-body {
		padding: 16px;
		flex: 1;
		position: relative;
	}

	#dashboard-marketing-visilab-calendar {
		min-height: 480px;
	}

	#dashboard-marketing-visilab-calendar .fc-event {
		cursor: pointer;
		border-radius: 4px;
	}

	#dashboard-marketing-visilab-calendar .fc-toolbar-title {
		font-size: 1.15rem;
		font-weight: 700;
		color: #1e293b;
	}

	.nav-pills-custom .nav-link {
		border-radius: 8px;
		font-size: 13px;
		font-weight: 600;
		color: #64748b;
		padding: 6px 14px;
		border: 1px solid transparent;
		transition: all 0.2s ease;
	}

	.nav-pills-custom .nav-link.active {
		background-color: #2563eb;
		color: #ffffff;
		box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
	}

	.nav-pills-custom .nav-link:hover:not(.active) {
		background-color: #f1f5f9;
		color: #1e293b;
	}

	/* Marketing Executive Dashboard Custom Styles */
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
</style>

<div class="row">
	<!-- 1. Executive Top Banner Overview -->
	<div class="col-12">
		<div class="dash-banner">
			<div class="row align-items-center">
				<div class="col-lg-6 col-12 mb-3 mb-lg-0">
					<div class="d-flex align-items-center">
						<div class="dash-banner-icon">
							<i class="fas fa-chart-line"></i>
						</div>
						<div>
							<h3 style="font-size: 17px; font-weight: 700; color: #ffffff; margin: 0 0 4px 0;">Executive Overview & Analytics Marketing</h3>
							<p style="font-size: 12px; color: #94a3b8; margin: 0; line-height: 1.4;">Monitoring perolehan prospek leads, funnel pipeline, permohonan penawaran (FPP), dan jadwal operasional.</p>
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
						<a href="<?= base_url('funnel') ?>" class="btn btn-sm btn-primary" style="font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 6px;">
							<i class="fas fa-filter mr-1"></i> Data Funnel
						</a>
						<a href="<?= base_url('calonpelanggan') ?>" class="btn btn-sm btn-info" style="font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 6px;">
							<i class="fas fa-user-plus mr-1"></i> Calon Pelanggan
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- 2. Four Executive Marketing KPI Cards -->
	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('calonpelanggan') ?>" class="mkt-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Calon Pelanggan</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #1e40af; margin: 4px 0 2px 0;">
							<?= isset($countcalonpelanggan[0]->total) ? $countcalonpelanggan[0]->total : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #2563eb; font-weight: 600;"><i class="fas fa-user-tie mr-1"></i> Prospek Masuk</span>
					</div>
					<div class="mkt-kpi-icon-box" style="background: #eff6ff; color: #2563eb;">
						<i class="fas fa-users"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('funnel') ?>" class="mkt-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Funnel Sales</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #b45309; margin: 4px 0 2px 0;">
							<?= isset($countfunnel[0]->total) ? $countfunnel[0]->total : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #d97706; font-weight: 600;"><i class="fas fa-filter mr-1"></i> Pipeline Aktif</span>
					</div>
					<div class="mkt-kpi-icon-box" style="background: #fffbeb; color: #d97706;">
						<i class="fas fa-funnel-dollar"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('fpp') ?>" class="mkt-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Permintaan Penawaran</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #047857; margin: 4px 0 2px 0;">
							<?= isset($countfpp[0]->total) ? $countfpp[0]->total : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #10b981; font-weight: 600;"><i class="fas fa-check-circle mr-1"></i> FPP Disetujui</span>
					</div>
					<div class="mkt-kpi-icon-box" style="background: #ecfdf5; color: #10b981;">
						<i class="fas fa-file-invoice-dollar"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('pelangganan') ?>" class="mkt-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Pelanggan Terdaftar</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #6d28d9; margin: 4px 0 2px 0;">
							<?= isset($countpelanggan[0]->total) ? $countpelanggan[0]->total : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #7c3aed; font-weight: 600;"><i class="fas fa-handshake mr-1"></i> Institusi Resmi</span>
					</div>
					<div class="mkt-kpi-icon-box" style="background: #f5f3ff; color: #7c3aed;">
						<i class="fas fa-building"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<!-- 3. Multi-Tabbed Marketing Analytics Charts -->
	<div class="col-12 mb-4">
		<div class="mkt-panel-card">
			<div class="mkt-panel-header">
				<div class="d-flex align-items-center gap-3">
					<h5 class="mkt-panel-title">
						<i class="fas fa-chart-bar text-primary"></i> Grafik Kinerja Bulanan Marketing (<span id="span-tahun"><?= date('Y') ?></span>)
					</h5>
				</div>
				<ul class="nav nav-pills nav-pills-custom" id="chartTabs" role="tablist">
					<li class="nav-item mr-1">
						<a class="nav-link active" id="tab-calon-link" data-toggle="pill" href="#tab-calon" role="tab">
							<i class="fas fa-user-plus mr-1"></i> Calon Pelanggan
						</a>
					</li>
					<li class="nav-item mr-1">
						<a class="nav-link" id="tab-funnel-link" data-toggle="pill" href="#tab-funnel" role="tab">
							<i class="fas fa-filter mr-1"></i> Funnel Pipeline
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="tab-fpp-link" data-toggle="pill" href="#tab-fpp" role="tab">
							<i class="fas fa-file-invoice mr-1"></i> Permintaan Penawaran (FPP)
						</a>
					</li>
				</ul>
			</div>
			<div class="card-body p-3">
				<div class="tab-content" id="chartTabsContent">
					<!-- Tab 1: Calon Pelanggan -->
					<div class="tab-pane fade show active" id="tab-calon" role="tabpanel">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="text-muted" style="font-size: 13px;">Jumlah calon prospek yang didaftarkan oleh masing-masing tim marketing per bulan.</span>
							<span class="badge" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 11px;">Leads Acquired</span>
						</div>
						<div style="height: 280px; position: relative;">
							<canvas id="bar"></canvas>
						</div>
					</div>

					<!-- Tab 2: Funnel Pipeline -->
					<div class="tab-pane fade" id="tab-funnel" role="tabpanel">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="text-muted" style="font-size: 13px;">Aktivitas funnel peluang sales yang dibuat dan ditindaklanjuti per bulan.</span>
							<span class="badge" style="background: #fffbeb; color: #d97706; border: 1px solid #fde68a; font-size: 11px;">Pipeline Activity</span>
						</div>
						<div style="height: 280px; position: relative;">
							<canvas id="bar2"></canvas>
						</div>
					</div>

					<!-- Tab 3: FPP -->
					<div class="tab-pane fade" id="tab-fpp" role="tabpanel">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="text-muted" style="font-size: 13px;">Permintaan penawaran harga yang telah disetujui untuk customer per bulan.</span>
							<span class="badge" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; font-size: 11px;">Approved Proposals</span>
						</div>
						<div style="height: 280px; position: relative;">
							<canvas id="bar3"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- 4. Kalender Jadwal Visilab Operasional -->
	<div class="col-12 mb-4">
		<div class="mkt-panel-card">
			<div class="mkt-panel-header">
				<h5 class="mkt-panel-title">
					<i class="fas fa-calendar-check text-info"></i> Kalender Jadwal Visilab (Ukes & Upar)
				</h5>
				<span class="badge" style="background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; font-size: 11.5px; padding: 5px 10px;">
					<i class="fas fa-info-circle mr-1"></i> Klik kegiatan untuk melihat detail lokasi & teknisi
				</span>
			</div>
			<div class="card-body p-3">
				<div id="dashboard-marketing-visilab-calendar"></div>
			</div>
		</div>
	</div>

	<!-- 5. Log Aktivitas Marketing -->
	<div class="col-12 mb-4">
		<div class="mkt-panel-card">
			<div class="mkt-panel-header">
				<h5 class="mkt-panel-title">
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

<!-- Modal Detail Jadwal Visilab -->
<div class="modal fade" id="dashboardMarketingVisilabDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content" style="border-radius: 12px; overflow: hidden; border: 1px solid #cbd5e1; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);">
			<div class="modal-header" style="background: #1e293b; color: #ffffff; padding: 14px 20px;">
				<h5 class="modal-title" style="font-size: 15px; font-weight: 700; color: #ffffff; margin: 0; display: flex; align-items: center; gap: 8px;">
					<i class="fas fa-calendar-alt text-info"></i> Detail Jadwal Operasional Visilab
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body p-4" style="font-size: 13px; color: #334155;">
				<div class="row mb-2">
					<div class="col-sm-4 font-weight-bold text-muted">Jenis Jadwal:</div>
					<div class="col-sm-8 font-weight-semibold text-dark" id="marketingVisiJenis">-</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-4 font-weight-bold text-muted">Teknisi:</div>
					<div class="col-sm-8 font-weight-semibold text-dark" id="marketingVisiTeknisi">-</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-4 font-weight-bold text-muted">Tanggal:</div>
					<div class="col-sm-8 text-dark" id="marketingVisiTanggal">-</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-4 font-weight-bold text-muted">Jam:</div>
					<div class="col-sm-8 text-dark" id="marketingVisiJam">-</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-4 font-weight-bold text-muted">Status:</div>
					<div class="col-sm-8" id="marketingVisiStatus">-</div>
				</div>
				<hr class="my-2" style="border-color: #f1f5f9;">
				<div class="row mb-2">
					<div class="col-sm-4 font-weight-bold text-muted">Pelanggan:</div>
					<div class="col-sm-8 font-weight-bold text-primary" id="marketingVisiPelanggan">-</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-4 font-weight-bold text-muted">Wilayah / Prov:</div>
					<div class="col-sm-8 text-dark"><span id="marketingVisiWilayah">-</span> / <span id="marketingVisiProvinsi">-</span></div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-4 font-weight-bold text-muted">Kab / Kota:</div>
					<div class="col-sm-8 text-dark" id="marketingVisiKabKota">-</div>
				</div>
				<div class="row mb-0">
					<div class="col-sm-4 font-weight-bold text-muted">Alamat:</div>
					<div class="col-sm-8 text-dark" id="marketingVisiAlamat">-</div>
				</div>
			</div>
			<div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 10px 20px;">
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const baseUrl = "<?= base_url(); ?>";
		const bulanNames = [
			"Januari", "Februari", "Maret", "April", "Mei", "Juni",
			"Juli", "Agustus", "September", "Oktober", "November", "Desember"
		];

		let chartInstances = {};
		let currentYear = $('#filter_year').val() || new Date().getFullYear();

		// Modern Color Palette for Team Members
		const memberStyles = [
			{ label: 'M Tamrin', bg: 'rgba(37, 99, 235, 0.85)', border: '#2563eb' },
			{ label: 'Rizqilillah', bg: 'rgba(6, 182, 212, 0.85)', border: '#06b6d4' },
			{ label: 'Nurdiansyah', bg: 'rgba(16, 185, 129, 0.85)', border: '#10b981' }
		];

		function buildBarChart(canvasId, urlEndpoint, year) {
			$.ajax({
				url: baseUrl + urlEndpoint,
				method: 'GET',
				data: { tahun: year },
				dataType: 'json',
				success: function(data) {
					let labels = [];
					let dataset1 = [];
					let dataset2 = [];
					let dataset3 = [];

					data.forEach(function(item) {
						labels.push(bulanNames[item.month - 1]);
						dataset1.push(item['105'] || 0);
						dataset2.push(item['742'] || 0);
						dataset3.push(item['745'] || 0);
					});

					const chartData = {
						labels: labels,
						datasets: [
							{
								label: memberStyles[0].label,
								data: dataset1,
								backgroundColor: memberStyles[0].bg,
								borderColor: memberStyles[0].border,
								borderWidth: 1.5,
								borderRadius: 6,
								maxBarThickness: 18
							},
							{
								label: memberStyles[1].label,
								data: dataset2,
								backgroundColor: memberStyles[1].bg,
								borderColor: memberStyles[1].border,
								borderWidth: 1.5,
								borderRadius: 6,
								maxBarThickness: 18
							},
							{
								label: memberStyles[2].label,
								data: dataset3,
								backgroundColor: memberStyles[2].bg,
								borderColor: memberStyles[2].border,
								borderWidth: 1.5,
								borderRadius: 6,
								maxBarThickness: 18
							}
						]
					};

					const canvas = document.getElementById(canvasId);
					if (!canvas) return;

					if (chartInstances[canvasId]) {
						chartInstances[canvasId].destroy();
					}

					const ctx = canvas.getContext('2d');
					chartInstances[canvasId] = new Chart(ctx, {
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
				},
				error: function(err) {
					console.error("Error fetching chart data for " + canvasId + ":", err);
				}
			});
		}

		function loadAllMarketingCharts(year) {
			$('#span-tahun').text(year);
			buildBarChart('bar', 'dashboard_marketing/chart_data', year);
			buildBarChart('bar2', 'dashboard_marketing/chart_data_funnel', year);
			buildBarChart('bar3', 'dashboard_marketing/chart_data_fpp', year);
		}

		// Initial load
		loadAllMarketingCharts(currentYear);

		// Year Change Listener
		$('#filter_year').change(function() {
			currentYear = $(this).val() || new Date().getFullYear();
			loadAllMarketingCharts(currentYear);
		});

		// Trigger chart resize when tabs are switched
		$('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
			Object.values(chartInstances).forEach(function(instance) {
				instance.resize();
			});
		});

		// Log Aktivitas Datatable
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

		// FullCalendar Visilab
		var visilabMarketingCalendarEl = document.getElementById('dashboard-marketing-visilab-calendar');
		if (visilabMarketingCalendarEl && typeof FullCalendar !== 'undefined') {
			var visilabMarketingCalendar = new FullCalendar.Calendar(visilabMarketingCalendarEl, {
				initialView: 'dayGridMonth',
				locale: 'id',
				height: 520,
				headerToolbar: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,dayGridWeek'
				},
				events: function(info, successCallback, failureCallback) {
					var viewYear = new Date((info.start.getTime() + info.end.getTime()) / 2).getFullYear();
					$.getJSON('<?= base_url("visilab_jadwal/get_events") ?>', { year: viewYear })
						.done(function(resp) { successCallback(resp); })
						.fail(function() { failureCallback(); });
				},
				eventContent: function(arg) {
					var props = arg.event.extendedProps || {};
					var wilayah = props.wilayah || 'Lainnya';
					var badgeColor = props.wilayah_badge_color || '#6c757d';
					return {
						html: '<div style="padding: 2px 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><span style="display:inline-block;padding:1px 6px;border-radius:999px;background:' + badgeColor + ';color:#fff;font-size:9.5px;font-weight:700;margin-right:4px;">' + wilayah + '</span><span style="font-size:11.5px;font-weight:600;">' + (arg.event.title || '') + '</span></div>'
					};
				},
				eventClick: function(info) {
					var props = info.event.extendedProps || {};
					var opt = { year: 'numeric', month: 'long', day: 'numeric' };
					var tanggalMulai = props.tanggal_mulai || (info.event.startStr || '');
					var tanggalSelesai = props.tanggal_selesai || tanggalMulai;
					var tanggal = '-';
					if (tanggalMulai) {
						var tStart = new Date(tanggalMulai + 'T00:00:00').toLocaleDateString('id-ID', opt);
						var tEnd = new Date(tanggalSelesai + 'T00:00:00').toLocaleDateString('id-ID', opt);
						tanggal = tStart === tEnd ? tStart : (tStart + ' s/d ' + tEnd);
					}
					$('#marketingVisiJenis').text(props.jenis_jadwal || '-');
					$('#marketingVisiTeknisi').text(props.teknisi_nama || (props.teknisi_id ? ('ID ' + props.teknisi_id) : '-'));
					$('#marketingVisiTanggal').text(tanggal);
					$('#marketingVisiJam').text(props.jam || '-');
					$('#marketingVisiStatus').html('<span class="badge badge-primary font-weight-normal">' + (props.status || '-') + '</span>');
					$('#marketingVisiPelanggan').text(props.lokasi_pelanggan_nama || '-');
					$('#marketingVisiWilayah').text(props.wilayah || '-');
					$('#marketingVisiProvinsi').text(props.provinsi || '-');
					$('#marketingVisiKabKota').text(props.kab_kota || '-');
					$('#marketingVisiAlamat').text(props.lokasi_alamat || '-');
					$('#dashboardMarketingVisilabDetailModal').modal('show');
				}
			});
			visilabMarketingCalendar.render();
		}
	});
</script>