<header class="page-header">
	<h2><i class="icons icon-list"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
	</div>
</header>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>

<style>
	/* Visilab Executive Dashboard Custom Styles */
	.visi-kpi-card {
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

	.visi-kpi-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
		border-color: #cbd5e1;
	}

	.visi-kpi-icon-box {
		width: 48px;
		height: 48px;
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		flex-shrink: 0;
	}

	.visi-panel-card {
		background: #ffffff;
		border-radius: 12px;
		border: 1px solid #e2e8f0;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
		margin-bottom: 24px;
		overflow: hidden;
	}

	.visi-panel-header {
		background: #f8fafc;
		border-bottom: 1px solid #e2e8f0;
		padding: 14px 20px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 10px;
	}

	.visi-panel-title {
		font-size: 15px;
		font-weight: 700;
		color: #1e293b;
		margin: 0;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.table-visi thead th {
		background-color: #f1f5f9 !important;
		color: #334155 !important;
		font-size: 12px !important;
		font-weight: 700 !important;
		text-transform: uppercase !important;
		letter-spacing: 0.3px !important;
		border-bottom: 1px solid #e2e8f0 !important;
		padding: 10px 12px !important;
	}

	.table-visi tbody td {
		font-size: 12.5px !important;
		color: #1e293b !important;
		padding: 10px 12px !important;
		vertical-align: middle !important;
	}

	#dashboard-visilab-calendar {
		min-height: 480px;
	}

	#dashboard-visilab-calendar .fc-event {
		cursor: pointer;
		border-radius: 4px;
	}

	#dashboard-visilab-calendar .fc-toolbar-title {
		font-size: 1.15rem;
		font-weight: 700;
		color: #1e293b;
	}

	/* Visilab Executive Dashboard Custom Styles */
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
							<i class="fas fa-microscope"></i>
						</div>
						<div>
							<h3 style="font-size: 17px; font-weight: 700; color: #ffffff; margin: 0 0 4px 0;">Executive Overview & Analytics Visilab</h3>
							<p style="font-size: 12px; color: #94a3b8; margin: 0; line-height: 1.4;">Monitoring layanan Uji Kesesuaian (Ukes), Uji Paparan Radiasi (Upar), dan jadwal operasional kalibrasi.</p>
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
						<a href="<?= base_url('pengujian/ukes') ?>" class="btn btn-sm btn-primary" style="font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 6px;">
							<i class="fas fa-flask mr-1"></i> Uji Kesesuaian
						</a>
						<a href="<?= base_url('pengujian/upar') ?>" class="btn btn-sm btn-success" style="font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 6px;">
							<i class="fas fa-radiation mr-1"></i> Uji Paparan
						</a>
						<a href="<?= base_url('po_visilab') ?>" class="btn btn-sm btn-info" style="font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 6px;">
							<i class="fas fa-shopping-cart mr-1"></i> PO Visilab
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- 2. Four Executive Visilab KPI Cards -->
	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('pengujian/ukes') ?>" class="visi-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Total Pengujian</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #1e40af; margin: 4px 0 2px 0;">
							<?= isset($total_pengujian) ? number_format($total_pengujian, 0, ',', '.') : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #2563eb; font-weight: 600;"><i class="fas fa-flask mr-1"></i> Ukes & Upar Terdaftar</span>
					</div>
					<div class="visi-kpi-icon-box" style="background: #eff6ff; color: #2563eb;">
						<i class="fas fa-microscope"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('visilab_jadwal') ?>" class="visi-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Jadwal (Bulan Ini)</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #047857; margin: 4px 0 2px 0;">
							<?= isset($jadwal_bulan_ini) ? $jadwal_bulan_ini : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #10b981; font-weight: 600;"><i class="fas fa-calendar-day mr-1"></i> Kunjungan & Servis</span>
					</div>
					<div class="visi-kpi-icon-box" style="background: #ecfdf5; color: #10b981;">
						<i class="fas fa-calendar-check"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('po_visilab') ?>" class="visi-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">PO Visilab</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #0284c7; margin: 4px 0 2px 0;">
							<?= isset($total_po_visilab) ? number_format($total_po_visilab, 0, ',', '.') : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #0284c7; font-weight: 600;"><i class="fas fa-file-invoice-dollar mr-1"></i> Order Pembelian</span>
					</div>
					<div class="visi-kpi-icon-box" style="background: #f0f9ff; color: #0284c7;">
						<i class="fas fa-shopping-cart"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('dokumen_rahasia_visilab') ?>" class="visi-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Dokumen Visilab</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #d97706; margin: 4px 0 2px 0;">
							<?= isset($total_dokumen_visilab) ? number_format($total_dokumen_visilab, 0, ',', '.') : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #d97706; font-weight: 600;"><i class="fas fa-shield-alt mr-1"></i> Sertifikat & Arsip</span>
					</div>
					<div class="visi-kpi-icon-box" style="background: #fffbeb; color: #d97706;">
						<i class="fas fa-certificate"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<!-- 3. Charts: Trend Pengujian & Distribusi Jenis Uji -->
	<div class="col-lg-8 mb-4">
		<div class="visi-panel-card h-100">
			<div class="visi-panel-header">
				<h5 class="visi-panel-title">
					<i class="fas fa-chart-line text-primary"></i> Trend Pengujian Laboratorium Kalibrasi (<span id="visi-span-tahun"><?= date('Y') ?></span>)
				</h5>
				<span class="badge" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 11px;">
					Ukes vs Upar
				</span>
			</div>
			<div class="card-body p-3">
				<div style="height: 260px; position: relative;">
					<canvas id="chartVisiTransaksi"></canvas>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-4 mb-4">
		<div class="visi-panel-card h-100">
			<div class="visi-panel-header">
				<h5 class="visi-panel-title">
					<i class="fas fa-chart-pie text-success"></i> Komparasi Jenis Uji
				</h5>
			</div>
			<div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
				<div style="height: 200px; width: 100%; position: relative;">
					<canvas id="chartVisiKategori"></canvas>
				</div>
				<div class="w-100 mt-2">
					<ul class="list-unstyled mb-0" style="font-size: 12px;">
						<?php 
						$colors = ['#2563eb', '#10b981'];
						if (!empty($distribusi_uji)) {
							$idx = 0;
							foreach ($distribusi_uji as $jns => $jml) {
								$col = isset($colors[$idx]) ? $colors[$idx] : '#64748b';
								echo '<li class="d-flex justify-content-between align-items-center py-1 border-bottom">';
								echo '<span><i class="fas fa-circle mr-1" style="color:' . $col . '; font-size: 8px;"></i> ' . $jns . '</span>';
								echo '<span class="font-weight-bold text-dark">' . $jml . ' pengujian</span>';
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

	<!-- 4. Kalender Jadwal Operasional Visilab -->
	<div class="col-12 mb-4">
		<div class="visi-panel-card">
			<div class="visi-panel-header">
				<h5 class="visi-panel-title">
					<i class="fas fa-calendar-check text-info"></i> Kalender Jadwal Operasional Visilab
				</h5>
				<span class="badge" style="background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; font-size: 11.5px; padding: 5px 10px;">
					<i class="fas fa-info-circle mr-1"></i> Klik jadwal untuk melihat detail lokasi & teknisi
				</span>
			</div>
			<div class="card-body p-3">
				<div id="dashboard-visilab-calendar"></div>
			</div>
		</div>
	</div>

	<!-- 5. Log Aktivitas Visilab -->
	<div class="col-12 mb-4">
		<div class="visi-panel-card">
			<div class="visi-panel-header">
				<h5 class="visi-panel-title">
					<i class="fas fa-history text-secondary"></i> Log Aktivitas Pengguna
				</h5>
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

		let chartVisiInstance = null;
		let currentYear = $('#filter_year').val() || new Date().getFullYear();

		function loadVisilabChart(year) {
			$('#visi-span-tahun').text(year);
			$.ajax({
				url: baseUrl + 'dashboard_visilab/chart_data_pengujian',
				method: 'GET',
				data: { tahun: year },
				dataType: 'json',
				success: function(data) {
					let labels = [];
					let dataUkes = [];
					let dataUpar = [];

					data.forEach(function(item) {
						labels.push(bulanNames[item.month - 1]);
						dataUkes.push(item.ukes);
						dataUpar.push(item.upar);
					});

					const chartData = {
						labels: labels,
						datasets: [
							{
								label: 'Uji Kesesuaian (Ukes)',
								data: dataUkes,
								backgroundColor: 'rgba(37, 99, 235, 0.85)',
								borderColor: '#2563eb',
								borderWidth: 1.5,
								borderRadius: 6,
								maxBarThickness: 18
							},
							{
								label: 'Uji Paparan (Upar)',
								data: dataUpar,
								backgroundColor: 'rgba(16, 185, 129, 0.85)',
								borderColor: '#10b981',
								borderWidth: 1.5,
								borderRadius: 6,
								maxBarThickness: 18
							}
						]
					};

					const canvas = document.getElementById('chartVisiTransaksi');
					if (!canvas) return;

					if (chartVisiInstance) {
						chartVisiInstance.destroy();
					}

					const ctx = canvas.getContext('2d');
					chartVisiInstance = new Chart(ctx, {
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
		loadVisilabChart(currentYear);

		$('#filter_year').change(function() {
			currentYear = $(this).val() || new Date().getFullYear();
			loadVisilabChart(currentYear);
		});

		// Donut Chart Kategori Uji
		const kategoriCanvas = document.getElementById('chartVisiKategori');
		if (kategoriCanvas) {
			const katLabels = <?= json_encode(!empty($distribusi_uji) ? array_keys($distribusi_uji) : ['Ukes', 'Upar']) ?>;
			const katData = <?= json_encode(!empty($distribusi_uji) ? array_values($distribusi_uji) : [1, 1]) ?>;

			const ctxKat = kategoriCanvas.getContext('2d');
			new Chart(ctxKat, {
				type: 'doughnut',
				data: {
					labels: katLabels,
					datasets: [{
						data: katData,
						backgroundColor: ['#2563eb', '#10b981'],
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

		// FullCalendar Visilab
		var visilabCalendarEl = document.getElementById('dashboard-visilab-calendar');
		if (visilabCalendarEl && typeof FullCalendar !== 'undefined') {
			var visilabCalendar = new FullCalendar.Calendar(visilabCalendarEl, {
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
				}
			});
			visilabCalendar.render();
		}

		// Log Aktivitas DataTables
		var logTable = $('#kt_table_1').DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			order: [[4, 'desc']],
			ajax: {
				url: '<?= base_url("dashboard_visilab/pagination_log") ?>',
				type: 'POST',
				data: function(e) {
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
	});
</script>