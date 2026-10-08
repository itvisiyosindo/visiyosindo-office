<header class="page-header">
	<h2><i class="icons icon-list"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
	</div>
</header>

<style>
	/* Inventory Executive Dashboard Custom Styles */
	.inv-kpi-card {
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

	.inv-kpi-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
		border-color: #cbd5e1;
	}

	.inv-kpi-icon-box {
		width: 48px;
		height: 48px;
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		flex-shrink: 0;
	}

	.inv-panel-card {
		background: #ffffff;
		border-radius: 12px;
		border: 1px solid #e2e8f0;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
		margin-bottom: 24px;
		overflow: hidden;
	}

	.inv-panel-header {
		background: #f8fafc;
		border-bottom: 1px solid #e2e8f0;
		padding: 14px 20px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 10px;
	}

	.inv-panel-title {
		font-size: 15px;
		font-weight: 700;
		color: #1e293b;
		margin: 0;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.table-inv-alert thead th {
		background-color: #f1f5f9 !important;
		color: #334155 !important;
		font-size: 12px !important;
		font-weight: 700 !important;
		text-transform: uppercase !important;
		letter-spacing: 0.3px !important;
		border-bottom: 1px solid #e2e8f0 !important;
		padding: 10px 12px !important;
	}

	.table-inv-alert tbody td {
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
						<i class="fas fa-boxes"></i>
					</div>
					<div>
						<h3 style="font-size: 18px; font-weight: 700; color: #ffffff; margin: 0 0 4px 0;">Executive Overview & Analytics Inventory</h3>
						<p style="font-size: 12.5px; color: #94a3b8; margin: 0;">Monitoring persediaan master barang, transaksi keluar/masuk gudang, dan peringatan stok minimum.</p>
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
					<a href="<?= base_url('barang') ?>" class="btn btn-sm btn-primary ml-2" style="font-size: 12px;">
						<i class="fas fa-cubes mr-1"></i> Master Barang
					</a>
					<a href="<?= base_url('penerimaan_barang') ?>" class="btn btn-sm btn-success ml-1" style="font-size: 12px;">
						<i class="fas fa-arrow-down mr-1"></i> Penerimaan
					</a>
					<a href="<?= base_url('pengeluaran_barang') ?>" class="btn btn-sm btn-info ml-1" style="font-size: 12px;">
						<i class="fas fa-arrow-up mr-1"></i> Pengeluaran
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- 2. Four Executive Inventory KPI Cards -->
	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('barang') ?>" class="inv-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Master Barang</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #1e40af; margin: 4px 0 2px 0;">
							<?= isset($total_barang) ? number_format($total_barang, 0, ',', '.') : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #2563eb; font-weight: 600;"><i class="fas fa-box mr-1"></i> Item Aktif Terdaftar</span>
					</div>
					<div class="inv-kpi-icon-box" style="background: #eff6ff; color: #2563eb;">
						<i class="fas fa-cubes"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('gudang') ?>" class="inv-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Gudang Penyimpanan</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #047857; margin: 4px 0 2px 0;">
							<?= isset($total_gudang) ? $total_gudang : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #10b981; font-weight: 600;"><i class="fas fa-warehouse mr-1"></i> Lokasi Gudang Aktif</span>
					</div>
					<div class="inv-kpi-icon-box" style="background: #ecfdf5; color: #10b981;">
						<i class="fas fa-warehouse"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('penerimaan_barang') ?>" class="inv-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Penerimaan (Bulan Ini)</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #0284c7; margin: 4px 0 2px 0;">
							<?= isset($total_penerimaan_bulan_ini) ? $total_penerimaan_bulan_ini : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #0284c7; font-weight: 600;"><i class="fas fa-arrow-circle-down mr-1"></i> Surat Masuk Stok</span>
					</div>
					<div class="inv-kpi-icon-box" style="background: #f0f9ff; color: #0284c7;">
						<i class="fas fa-truck-loading"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="<?= base_url('pengeluaran_barang') ?>" class="inv-kpi-card">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Pengeluaran (Bulan Ini)</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #d97706; margin: 4px 0 2px 0;">
							<?= isset($total_pengeluaran_bulan_ini) ? $total_pengeluaran_bulan_ini : 0 ?>
						</h3>
						<span style="font-size: 11.5px; color: #d97706; font-weight: 600;"><i class="fas fa-arrow-circle-up mr-1"></i> Pengiriman Ke Klien</span>
					</div>
					<div class="inv-kpi-icon-box" style="background: #fffbeb; color: #d97706;">
						<i class="fas fa-shipping-fast"></i>
					</div>
				</div>
			</div>
		</a>
	</div>

	<!-- 3. Charts: Transaksi Keluar Masuk & Distribusi Kategori -->
	<div class="col-lg-8 mb-4">
		<div class="inv-panel-card h-100">
			<div class="inv-panel-header">
				<h5 class="inv-panel-title">
					<i class="fas fa-chart-line text-primary"></i> Trend Transaksi Penerimaan vs Pengeluaran (<span id="inv-span-tahun"><?= date('Y') ?></span>)
				</h5>
				<span class="badge" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 11px;">
					Inbound vs Outbound
				</span>
			</div>
			<div class="card-body p-3">
				<div style="height: 260px; position: relative;">
					<canvas id="chartInvTransaksi"></canvas>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-4 mb-4">
		<div class="inv-panel-card h-100">
			<div class="inv-panel-header">
				<h5 class="inv-panel-title">
					<i class="fas fa-chart-pie text-success"></i> Kategori Barang Terbanyak
				</h5>
			</div>
			<div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
				<div style="height: 200px; width: 100%; position: relative;">
					<canvas id="chartInvKategori"></canvas>
				</div>
				<div class="w-100 mt-2">
					<ul class="list-unstyled mb-0" style="font-size: 12px;">
						<?php 
						$colors = ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4'];
						if (!empty($kategori_populer)) {
							$idx = 0;
							foreach ($kategori_populer as $row) {
								$col = isset($colors[$idx]) ? $colors[$idx] : '#64748b';
								echo '<li class="d-flex justify-content-between align-items-center py-1 border-bottom">';
								echo '<span><i class="fas fa-circle mr-1" style="color:' . $col . '; font-size: 8px;"></i> ' . ($row->nama_kategori ?: 'Lainnya') . '</span>';
								echo '<span class="font-weight-bold text-dark">' . $row->total . ' item</span>';
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

	<!-- 4. Quick-Alert: Peringatan Barang Stok Kritis / Menipis -->
	<div class="col-12 mb-4">
		<div class="inv-panel-card">
			<div class="inv-panel-header">
				<h5 class="inv-panel-title">
					<i class="fas fa-exclamation-triangle text-danger"></i> Peringatan Stok Minimum / Kritis
				</h5>
				<a href="<?= base_url('barang') ?>" class="btn btn-sm btn-outline-primary" style="font-size: 12px;">
					Lihat Semua Stok <i class="fas fa-arrow-right ml-1"></i>
				</a>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover table-inv-alert mb-0">
						<thead>
							<tr>
								<th>Kode</th>
								<th>Nama Barang</th>
								<th>Kategori</th>
								<th class="text-center">Batas Minimum</th>
								<th class="text-center">Stok Terkini</th>
								<th class="text-center">Status</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($barang_kritis)) {
								foreach ($barang_kritis as $row) { 
									$stok = (float)$row->total_stock;
									$min  = (float)$row->batas_min_stock;
									$is_habis = $stok <= 0;
									?>
									<tr>
										<td class="font-weight-bold text-muted" style="font-size: 12px;"><?= $row->kode_barang ?: '-' ?></td>
										<td class="font-weight-semibold text-dark"><?= $row->nama_barang ?></td>
										<td><span class="badge badge-light border text-dark" style="font-size: 11px;"><?= $row->nama_kategori ?: 'Umum' ?></span></td>
										<td class="text-center font-weight-bold text-muted"><?= $min ?> <?= $row->nama_satuan ?></td>
										<td class="text-center font-weight-bold <?= $is_habis ? 'text-danger' : 'text-warning' ?>" style="font-size: 13.5px;">
											<?= $stok ?> <?= $row->nama_satuan ?>
										</td>
										<td class="text-center">
											<?php if ($is_habis) { ?>
												<span class="badge badge-danger" style="font-size: 11px; padding: 4px 8px;">Stok Habis (0)</span>
											<?php } else { ?>
												<span class="badge badge-warning" style="font-size: 11px; padding: 4px 8px;">Mendekati Minimum</span>
											<?php } ?>
										</td>
									</tr>
								<?php }
							} else { ?>
								<tr>
									<td colspan="6" class="text-center py-4 text-muted">
										<i class="fas fa-check-circle text-success mr-1"></i> Semua stok barang saat ini berada di atas batas minimum.
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
		<div class="inv-panel-card">
			<div class="inv-panel-header">
				<h5 class="inv-panel-title">
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

		let chartTransaksiInstance = null;
		let currentYear = $('#filter_year').val() || new Date().getFullYear();

		function loadTransaksiChart(year) {
			$('#inv-span-tahun').text(year);
			$.ajax({
				url: baseUrl + 'dashboard_inventory/chart_data_transaksi',
				method: 'GET',
				data: { tahun: year },
				dataType: 'json',
				success: function(data) {
					let labels = [];
					let dataMasuk = [];
					let dataKeluar = [];

					data.forEach(function(item) {
						labels.push(bulanNames[item.month - 1]);
						dataMasuk.push(item.penerimaan);
						dataKeluar.push(item.pengeluaran);
					});

					const chartData = {
						labels: labels,
						datasets: [
							{
								label: 'Penerimaan (Barang Masuk)',
								data: dataMasuk,
								backgroundColor: 'rgba(16, 185, 129, 0.85)',
								borderColor: '#10b981',
								borderWidth: 1.5,
								borderRadius: 6,
								maxBarThickness: 18
							},
							{
								label: 'Pengeluaran (Barang Keluar)',
								data: dataKeluar,
								backgroundColor: 'rgba(245, 158, 11, 0.85)',
								borderColor: '#f59e0b',
								borderWidth: 1.5,
								borderRadius: 6,
								maxBarThickness: 18
							}
						]
					};

					const canvas = document.getElementById('chartInvTransaksi');
					if (!canvas) return;

					if (chartTransaksiInstance) {
						chartTransaksiInstance.destroy();
					}

					const ctx = canvas.getContext('2d');
					chartTransaksiInstance = new Chart(ctx, {
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
		loadTransaksiChart(currentYear);

		$('#filter_year').change(function() {
			currentYear = $(this).val() || new Date().getFullYear();
			loadTransaksiChart(currentYear);
		});

		// Donut Chart Kategori
		const kategoriCanvas = document.getElementById('chartInvKategori');
		if (kategoriCanvas) {
			const katLabels = <?= json_encode(!empty($kategori_populer) ? array_map(function($k) { return $k->nama_kategori ?: 'Lainnya'; }, $kategori_populer) : ['Umum']) ?>;
			const katData = <?= json_encode(!empty($kategori_populer) ? array_map(function($k) { return (int)$k->total; }, $kategori_populer) : [1]) ?>;

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