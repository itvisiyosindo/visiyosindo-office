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

	/* TradingView Terminal Custom Styles */
	.tv-terminal-card {
		background: #0f172a;
		border-radius: 14px;
		border: 1px solid #1e293b;
		box-shadow: 0 12px 32px rgba(15, 23, 42, 0.22);
		overflow: hidden;
		margin-bottom: 24px;
		transition: background 0.25s ease, border-color 0.25s ease;
	}

	.tv-terminal-card.tv-light-mode {
		background: #ffffff;
		border-color: #e2e8f0;
		box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
	}

	.tv-ticker-header {
		padding: 14px 20px;
		background: rgba(15, 23, 42, 0.95);
		border-bottom: 1px solid #1e293b;
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
		align-items: center;
		gap: 14px;
	}

	.tv-terminal-card.tv-light-mode .tv-ticker-header {
		background: #f8fafc;
		border-bottom-color: #e2e8f0;
	}

	.tv-symbol-box {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.tv-symbol-logo {
		width: 42px;
		height: 42px;
		border-radius: 10px;
		background: linear-gradient(135deg, #10b981 0%, #059669 100%);
		color: #ffffff;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 18px;
		font-weight: 800;
		box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
		flex-shrink: 0;
	}

	.tv-symbol-name {
		font-size: 16px;
		font-weight: 800;
		color: #f8fafc;
		letter-spacing: 0.4px;
		display: flex;
		align-items: center;
		gap: 8px;
		margin: 0;
	}

	.tv-terminal-card.tv-light-mode .tv-symbol-name {
		color: #0f172a;
	}

	.tv-symbol-sub {
		font-size: 11.5px;
		color: #94a3b8;
		margin: 2px 0 0 0;
	}

	.tv-terminal-card.tv-light-mode .tv-symbol-sub {
		color: #64748b;
	}

	.tv-metrics-strip {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 18px;
	}

	.tv-metric-item {
		display: flex;
		flex-direction: column;
		border-left: 1px solid #1e293b;
		padding-left: 14px;
	}

	.tv-terminal-card.tv-light-mode .tv-metric-item {
		border-left-color: #e2e8f0;
	}

	.tv-metric-label {
		font-size: 10.5px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		color: #64748b;
		margin-bottom: 2px;
	}

	.tv-metric-val {
		font-size: 14px;
		font-weight: 800;
		color: #f1f5f9;
		font-family: 'Consolas', 'Monaco', monospace;
	}

	.tv-terminal-card.tv-light-mode .tv-metric-val {
		color: #0f172a;
	}

	.tv-toolbar {
		padding: 8px 16px;
		background: #131c31;
		border-bottom: 1px solid #1e293b;
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
		align-items: center;
		gap: 10px;
	}

	.tv-terminal-card.tv-light-mode .tv-toolbar {
		background: #f1f5f9;
		border-bottom-color: #e2e8f0;
	}

	.tv-btn-group {
		display: inline-flex;
		align-items: center;
		background: #0f172a;
		padding: 3px;
		border-radius: 8px;
		border: 1px solid #1e293b;
		gap: 2px;
	}

	.tv-terminal-card.tv-light-mode .tv-btn-group {
		background: #ffffff;
		border-color: #cbd5e1;
	}

	.tv-btn {
		background: transparent;
		border: none;
		color: #94a3b8;
		font-size: 11.5px;
		font-weight: 700;
		padding: 4px 10px;
		border-radius: 6px;
		cursor: pointer;
		transition: all 0.15s ease;
		display: inline-flex;
		align-items: center;
		gap: 5px;
	}

	.tv-terminal-card.tv-light-mode .tv-btn {
		color: #475569;
	}

	.tv-btn:hover {
		color: #ffffff;
		background: rgba(255, 255, 255, 0.07);
	}

	.tv-terminal-card.tv-light-mode .tv-btn:hover {
		color: #0f172a;
		background: #f1f5f9;
	}

	.tv-btn.active {
		background: #2563eb;
		color: #ffffff !important;
		box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35);
	}

	.tv-canvas-wrap {
		position: relative;
		width: 100%;
		height: 430px;
	}

	.tv-floating-legend {
		position: absolute;
		top: 12px;
		left: 16px;
		z-index: 10;
		pointer-events: none;
		background: rgba(15, 23, 42, 0.82);
		backdrop-filter: blur(6px);
		border: 1px solid rgba(51, 65, 85, 0.7);
		border-radius: 10px;
		padding: 8px 14px;
		color: #f8fafc;
		max-width: 460px;
		box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
	}

	.tv-terminal-card.tv-light-mode .tv-floating-legend {
		background: rgba(255, 255, 255, 0.9);
		border-color: #cbd5e1;
		color: #0f172a;
		box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
	}

	.tv-up-color {
		color: #10b981 !important;
	}

	.tv-down-color {
		color: #ef4444 !important;
	}

	.tv-badge-up {
		background: rgba(16, 185, 129, 0.16);
		color: #10b981;
		border: 1px solid rgba(16, 185, 129, 0.35);
		padding: 2px 7px;
		border-radius: 5px;
		font-size: 11px;
		font-weight: 700;
	}

	.tv-badge-down {
		background: rgba(239, 68, 68, 0.16);
		color: #ef4444;
		border: 1px solid rgba(239, 68, 68, 0.35);
		padding: 2px 7px;
		border-radius: 5px;
		font-size: 11px;
		font-weight: 700;
	}

	.tv-badge-forecast {
		background: rgba(168, 85, 247, 0.20);
		color: #c084fc;
		border: 1px solid rgba(168, 85, 247, 0.45);
		padding: 2px 7px;
		border-radius: 5px;
		font-size: 11px;
		font-weight: 700;
	}

	.tv-tech-footer {
		background: #0b1120;
		border-top: 1px solid #1e293b;
		padding: 14px 18px;
	}

	.tv-terminal-card.tv-light-mode .tv-tech-footer {
		background: #f8fafc;
		border-top-color: #e2e8f0;
	}

	.tv-signal-box {
		background: rgba(30, 41, 59, 0.55);
		border: 1px solid #1e293b;
		border-radius: 10px;
		padding: 10px 14px;
		height: 100%;
	}

	.tv-terminal-card.tv-light-mode .tv-signal-box {
		background: #ffffff;
		border-color: #e2e8f0;
	}

	.tv-signal-title {
		font-size: 10.5px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		color: #64748b;
		margin-bottom: 4px;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.tv-signal-main {
		font-size: 13.5px;
		font-weight: 800;
		color: #f8fafc;
		margin-bottom: 2px;
	}

	.tv-terminal-card.tv-light-mode .tv-signal-main {
		color: #0f172a;
	}

	.tv-signal-sub {
		font-size: 11px;
		color: #94a3b8;
		line-height: 1.35;
	}

	.tv-terminal-card.tv-light-mode .tv-signal-sub {
		color: #64748b;
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

	<!-- 2.5. TradingView Financial Technical Analysis & Forecasting Terminal -->
	<div class="col-12">
		<div class="tv-terminal-card" id="tv-terminal-card">
			<!-- Ticker Header Bar -->
			<div class="tv-ticker-header">
				<div class="tv-symbol-box">
					<div class="tv-symbol-logo" id="tv-symbol-logo">
						<i class="fas fa-chart-line"></i>
					</div>
					<div>
						<h4 class="tv-symbol-name">
							<span id="tv-ticker-code">VSI:NET_CASHFLOW</span>
							<span class="badge badge-dark" style="font-size: 10px; background: #1e293b; color: #38bdf8; border: 1px solid #334155;">IDR • TECHNICAL</span>
							<span id="tv-growth-badge" class="tv-badge-up"><i class="fas fa-caret-up mr-1"></i>0.00%</span>
						</h4>
						<p class="tv-symbol-sub" id="tv-ticker-desc">Analisis Teknikal Arus Kas Bersih (Pemasukan PO/Invoice vs Pengeluaran Ekspedisi & Payroll) + Proyeksi 3 Periode</p>
					</div>
				</div>

				<div class="tv-metrics-strip">
					<div class="tv-metric-item">
						<span class="tv-metric-label">Arus Kas Bersih (<span id="tv-label-year"><?= $tahun_sekarang ?></span>)</span>
						<span class="tv-metric-val tv-up-color" id="tv-stat-net">Rp 0</span>
					</div>
					<div class="tv-metric-item">
						<span class="tv-metric-label">Total Pemasukan (Inflow)</span>
						<span class="tv-metric-val tv-up-color" id="tv-stat-inflow">Rp 0 <small style="font-size: 10.5px; color: #64748b; font-weight: 600;" id="tv-stat-inflow-cnt">(0 Dok)</small></span>
					</div>
					<div class="tv-metric-item">
						<span class="tv-metric-label">Total Pengeluaran (Outflow)</span>
						<span class="tv-metric-val tv-down-color" id="tv-stat-outflow">Rp 0 <small style="font-size: 10.5px; color: #64748b; font-weight: 600;" id="tv-stat-outflow-cnt">(0 Dok)</small></span>
					</div>
					<div class="tv-metric-item">
						<span class="tv-metric-label">Proyeksi +1 Periode</span>
						<span class="tv-metric-val" style="color: #c084fc;" id="tv-stat-forecast">Rp 0</span>
					</div>
					<div class="tv-metric-item">
						<span class="tv-metric-label">Puncak (Resistance)</span>
						<span class="tv-metric-val" id="tv-stat-high">Rp 0</span>
					</div>
				</div>
			</div>

			<!-- TradingView Control Toolbar -->
			<div class="tv-toolbar">
				<div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
					<!-- Source / Instrumen Data -->
					<div class="tv-btn-group" id="tv-source-group">
						<button type="button" class="tv-btn active" data-source="NET_CASHFLOW" title="Analisis Arus Kas Bersih (Total Inflow dikurangi Total Outflow)">
							<i class="fas fa-balance-scale-right text-success"></i> Net Cashflow
						</button>
						<button type="button" class="tv-btn" data-source="INFLOW" title="Total Pemasukan Gabungan (PO Masuk + Invoice)">
							<i class="fas fa-arrow-trend-up text-info"></i> Pemasukan (Inflow)
						</button>
						<button type="button" class="tv-btn" data-source="OUTFLOW" title="Total Pengeluaran (Tagihan Ekspedisi/SPP + Payroll Gaji)">
							<i class="fas fa-arrow-trend-down text-danger"></i> Pengeluaran (Outflow)
						</button>
						<button type="button" class="tv-btn" data-source="PO" title="Omzet PO Masuk Customer">
							<i class="fas fa-file-signature" style="color: #34d399;"></i> PO Masuk
						</button>
						<button type="button" class="tv-btn" data-source="INV" title="Realisasi Penagihan Invoice">
							<i class="fas fa-file-invoice-dollar" style="color: #38bdf8;"></i> Invoice
						</button>
						<button type="button" class="tv-btn" data-source="CUMULATIVE" title="Akumulasi Pertumbuhan Kas Berjalan (Equity Curve)">
							<i class="fas fa-chart-area text-warning"></i> Akumulasi YTD
						</button>
					</div>

					<!-- Timeframe Interval -->
					<div class="tv-btn-group" id="tv-interval-group">
						<button type="button" class="tv-btn" data-interval="1D" title="Timeframe Harian (Daily)">1D</button>
						<button type="button" class="tv-btn" data-interval="1W" title="Timeframe Mingguan (Weekly)">1W</button>
						<button type="button" class="tv-btn active" data-interval="1M" title="Timeframe Bulanan (Monthly)">1M</button>
					</div>

					<!-- Cakupan Tahun -->
					<div class="tv-btn-group" id="tv-scope-group">
						<button type="button" class="tv-btn active" data-scope="YEAR" title="Sesuai Filter Tahun Dashboard">Tahun Terpilih</button>
						<button type="button" class="tv-btn" data-scope="ALL" title="Tampilkan Seluruh Riwayat Lintas Tahun (All-Time)">All-Time</button>
					</div>
				</div>

				<div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
					<!-- Mode Grafik TradingView -->
					<div class="tv-btn-group" id="tv-chart-type-group">
						<button type="button" class="tv-btn active" data-type="baseline" title="Baseline Chart: Hijau saat Surplus / Di atas Rata-rata, Merah saat Defisit / Di bawah Rata-rata">
							<i class="fas fa-water"></i> Baseline (Surplus/Defisit)
						</button>
						<button type="button" class="tv-btn" data-type="cashflow_compare" title="Bandingkan Kurva Pemasukan (Hijau) vs Pengeluaran (Merah)">
							<i class="fas fa-exchange-alt"></i> Inflow vs Outflow
						</button>
						<button type="button" class="tv-btn" data-type="compare" title="Bandingkan Garis PO Masuk vs Invoice">
							<i class="fas fa-random"></i> PO vs Invoice
						</button>
						<button type="button" class="tv-btn" data-type="area" title="Area Gradient Chart">
							<i class="fas fa-mountain"></i> Area
						</button>
						<button type="button" class="tv-btn" data-type="candlestick" title="Candlestick OHLC">
							<i class="fas fa-sliders-h"></i> Candles
						</button>
					</div>

					<!-- Forecasting, Moving Average & Utility -->
					<div class="tv-btn-group">
						<button type="button" class="tv-btn active" id="tv-toggle-forecast" title="Tampilkan/Sembunyikan Proyeksi Forecasting 3 Periode ke Depan (Regresi Tren + Confidence Band)">
							<i class="fas fa-wand-magic-sparkles" style="color: #c084fc;"></i> Forecast (+3)
						</button>
						<button type="button" class="tv-btn active" id="tv-toggle-ma" title="Tampilkan/Sembunyikan Garis Indikator Moving Average Crossover (MA-3 & MA-6)">
							<i class="fas fa-wave-square" style="color: #f59e0b;"></i> MA(3/6)
						</button>
						<button type="button" class="tv-btn" id="tv-btn-fit" title="Reset Zoom / Fit Content">
							<i class="fas fa-compress-arrows-alt"></i> Reset
						</button>
						<button type="button" class="tv-btn" id="tv-btn-theme" title="Ganti Tema Dark / Light Mode">
							<i class="fas fa-sun"></i> Tema
						</button>
					</div>
				</div>
			</div>

			<!-- TradingView Interactive Chart Container -->
			<div class="tv-canvas-wrap">
				<!-- Floating Crosshair Legend -->
				<div class="tv-floating-legend" id="tv-floating-legend">
					<div class="d-flex align-items-center justify-content-between" style="gap: 12px;">
						<span style="font-size: 11.5px; font-weight: 700; color: #38bdf8;" id="tv-leg-date">Arahkan kursor pada grafik</span>
						<span style="font-size: 10.5px; font-weight: 700;" id="tv-leg-status" class="tv-badge-up">LIVE</span>
					</div>
					<div style="font-size: 17px; font-weight: 800; margin: 2px 0; font-family: 'Consolas', monospace;" id="tv-leg-val">Rp 0</div>
					<div class="d-flex flex-wrap align-items-center" style="gap: 8px; font-size: 11px; color: #94a3b8;" id="tv-leg-detail">
						<span>Inflow: <strong style="color: #10b981;" id="tv-leg-inflow">Rp 0</strong></span>
						<span>•</span>
						<span>Outflow: <strong style="color: #ef4444;" id="tv-leg-outflow">Rp 0</strong></span>
						<span>•</span>
						<span>Vol: <strong style="color: #fbbf24;" id="tv-leg-vol">0 Dok</strong></span>
					</div>
					<div style="font-size: 10.5px; color: #94a3b8; margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" id="tv-leg-docs"></div>
				</div>

				<!-- Lightweight Charts DOM Target -->
				<div id="tv-lightweight-chart" style="width: 100%; height: 100%;"></div>
			</div>

			<!-- Executive Financial Technical Analysis Signal Bar -->
			<div class="tv-tech-footer">
				<div class="row">
					<div class="col-xl-3 col-md-6 mb-2 mb-xl-0">
						<div class="tv-signal-box">
							<div class="tv-signal-title">
								<span><i class="fas fa-satellite-dish mr-1 text-info"></i> Sinyal Tren Teknikal</span>
								<span id="tv-sig-badge" class="tv-badge-up">BULLISH</span>
							</div>
							<div class="tv-signal-main" id="tv-sig-label">MENunggu DATA...</div>
							<div class="tv-signal-sub" id="tv-sig-desc">Menganalisis pergerakan rata-rata & momentum arus kas...</div>
						</div>
					</div>
					<div class="col-xl-3 col-md-6 mb-2 mb-xl-0">
						<div class="tv-signal-box">
							<div class="tv-signal-title">
								<span><i class="fas fa-scale-balanced mr-1 text-success"></i> Rasio Cakupan Kas (Inflow/Outflow)</span>
								<span class="badge badge-dark" style="font-size: 10px;" id="tv-sig-ratio">0.00x</span>
							</div>
							<div class="tv-signal-main" id="tv-sig-burn">Burn Rate: Rp 0 / periode</div>
							<div class="tv-signal-sub">Perbandingan total pemasukan (PO/Invoice) terhadap beban pengeluaran otomatis.</div>
						</div>
					</div>
					<div class="col-xl-3 col-md-6 mb-2 mb-md-0">
						<div class="tv-signal-box">
							<div class="tv-signal-title">
								<span><i class="fas fa-wand-magic-sparkles mr-1" style="color: #c084fc;"></i> Proyeksi Regresi (+1 Periode)</span>
								<span class="tv-badge-forecast">FORECAST</span>
							</div>
							<div class="tv-signal-main" style="color: #c084fc;" id="tv-sig-forecast-val">Rp 0</div>
							<div class="tv-signal-sub" id="tv-sig-forecast-range">Estimasi berbasis Linear Regression & Weighted Moving Average (WMA).</div>
						</div>
					</div>
					<div class="col-xl-3 col-md-6">
						<div class="tv-signal-box">
							<div class="tv-signal-title">
								<span><i class="fas fa-file-invoice-dollar mr-1 text-warning"></i> Realisasi PO → Invoice</span>
								<span class="badge badge-info" style="font-size: 10px;" id="tv-sig-conv-pct">0%</span>
							</div>
							<div class="tv-signal-main" id="tv-sig-unbilled">Unbilled PO: Rp 0</div>
							<div class="tv-signal-sub">Potensi piutang / pesanan PO masuk yang belum terbit penagihan Invoice.</div>
						</div>
					</div>
				</div>
			</div>
		</div>
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

<!-- Chart.js & TradingView Lightweight Charts for Accounting Dashboard -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightweight-charts@4.1.3/dist/lightweight-charts.standalone.production.js"></script>
<script>
	if (typeof LightweightCharts === 'undefined') {
		document.write('<script src="https://unpkg.com/lightweight-charts@4.1.3/dist/lightweight-charts.standalone.production.js"><\/script>');
	}
</script>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		var chartMonthly = null;
		var chartDonut = null;

		// =========================================================================
		// A. TRADINGVIEW TECHNICAL ANALYSIS & FORECASTING ENGINE
		// =========================================================================
		var tvContainer = document.getElementById('tv-lightweight-chart');
		var tvChart = null;
		var tvState = {
			source: 'NET_CASHFLOW', // NET_CASHFLOW, INFLOW, OUTFLOW, PO, INV, CUMULATIVE
			interval: '1M',         // 1D, 1W, 1M
			scope: 'YEAR',          // YEAR, ALL
			chartType: 'baseline',  // baseline, cashflow_compare, compare, area, candlestick
			showForecast: true,
			showMA: true,
			isLight: false,
			rawData: null
		};

		var activeSeriesList = [];

		function formatRupiahShort(val) {
			var num = parseFloat(val) || 0;
			var abs = Math.abs(num);
			var sign = num < 0 ? '-' : '';
			if (abs >= 1000000000) {
				return sign + 'Rp ' + (abs / 1000000000).toFixed(2).replace('.', ',') + ' M';
			} else if (abs >= 1000000) {
				return sign + 'Rp ' + (abs / 1000000).toFixed(1).replace('.', ',') + ' Jt';
			} else if (abs >= 1000) {
				return sign + 'Rp ' + (abs / 1000).toFixed(0) + ' Rb';
			}
			return sign + 'Rp ' + Math.round(abs).toLocaleString('id-ID');
		}

		function formatRupiahFull(val) {
			var num = Math.round(parseFloat(val) || 0);
			var sign = num < 0 ? '- ' : '';
			return sign + 'Rp ' + Math.abs(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
		}

		function formatTvDate(dateStr, interval) {
			if (!dateStr) return '-';
			var parts = String(dateStr).split('-');
			if (parts.length < 3) return dateStr;
			var bulanNama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
			var y = parts[0];
			var m = parseInt(parts[1], 10) - 1;
			var d = parts[2];
			if (interval === '1M') {
				return (bulanNama[m] || parts[1]) + ' ' + y;
			}
			return d + ' ' + (bulanNama[m] || parts[1]) + ' ' + y;
		}

		function computeSMA(data, period) {
			var result = [];
			for (var i = 0; i < data.length; i++) {
				if (i < period - 1) continue;
				var sum = 0;
				for (var j = 0; j < period; j++) {
					sum += parseFloat(data[i - j].value || 0);
				}
				result.push({
					time: data[i].time,
					value: Math.round((sum / period) * 100) / 100
				});
			}
			return result;
		}

		function initTvChart() {
			if (!tvContainer || typeof LightweightCharts === 'undefined') return;
			if (tvChart) {
				tvChart.remove();
				tvChart = null;
			}

			var bgColor   = tvState.isLight ? '#ffffff' : '#0f172a';
			var textColor = tvState.isLight ? '#334155' : '#94a3b8';
			var gridColor = tvState.isLight ? 'rgba(226, 232, 240, 0.8)' : 'rgba(30, 41, 59, 0.75)';
			var borderCol = tvState.isLight ? '#cbd5e1' : '#1e293b';

			tvChart = LightweightCharts.createChart(tvContainer, {
				width: tvContainer.clientWidth,
				height: tvContainer.clientHeight || 430,
				layout: {
					background: { type: 'solid', color: bgColor },
					textColor: textColor,
					fontFamily: "'Inter', 'Segoe UI', Roboto, sans-serif",
					fontSize: 11.5
				},
				grid: {
					vertLines: { color: gridColor, style: 1 },
					horzLines: { color: gridColor, style: 1 }
				},
				crosshair: {
					mode: LightweightCharts.CrosshairMode.Normal,
					vertLine: {
						width: 1,
						color: tvState.isLight ? '#64748b' : '#475569',
						style: 2,
						labelBackgroundColor: '#2563eb'
					},
					horzLine: {
						width: 1,
						color: tvState.isLight ? '#64748b' : '#475569',
						style: 2,
						labelBackgroundColor: '#2563eb'
					}
				},
				rightPriceScale: {
					borderColor: borderCol,
					scaleMargins: {
						top: 0.12,
						bottom: 0.25
					}
				},
				timeScale: {
					borderColor: borderCol,
					timeVisible: false,
					rightOffset: 4,
					barSpacing: 28,
					fixLeftEdge: false
				},
				localization: {
					priceFormatter: function(price) {
						return formatRupiahShort(price);
					}
				},
				handleScroll: {
					mouseWheel: true,
					pressedMouseMove: true,
					horzTouchDrag: true,
					vertTouchDrag: false
				},
				handleScale: {
					axisPressedMouseMove: true,
					mouseWheel: true,
					pinch: true
				}
			});

			// Subscribe Crosshair Move untuk update Floating Legend real-time
			tvChart.subscribeCrosshairMove(function(param) {
				if (!tvState.rawData) return;
				var metaMap = tvState.rawData.tooltip_meta || {};
				var baseline = parseFloat(tvState.rawData.baseline_value || 0);

				if (!param || !param.time || !param.point || param.point.x < 0 || param.point.y < 0) {
					var mainArr = tvState.rawData.series_main || [];
					if (mainArr.length > 0) {
						var lastItem = mainArr[mainArr.length - 1];
						updateFloatingLegend(lastItem.time, lastItem.value, metaMap[lastItem.time], baseline);
					}
					return;
				}

				var dateKey = typeof param.time === 'string'
					? param.time
					: (param.time.year + '-' + String(param.time.month).padStart(2, '0') + '-' + String(param.time.day).padStart(2, '0'));

				var meta = metaMap[dateKey];
				var foundVal = 0;
				var mainSeries = tvState.rawData.series_main || [];
				var fcastSeries = tvState.rawData.series_forecast || [];
				var found = false;
				for (var i = 0; i < mainSeries.length; i++) {
					if (mainSeries[i].time === dateKey) {
						foundVal = mainSeries[i].value;
						found = true;
						break;
					}
				}
				if (!found) {
					for (var j = 0; j < fcastSeries.length; j++) {
						if (fcastSeries[j].time === dateKey) {
							foundVal = fcastSeries[j].value;
							break;
						}
					}
				}
				updateFloatingLegend(dateKey, foundVal, meta, baseline);
			});
		}

		function updateFloatingLegend(dateKey, val, meta, baseline) {
			var isForecast = (meta && meta.is_forecast);
			$('#tv-leg-date').text((isForecast ? 'Proyeksi: ' : 'Periode: ') + formatTvDate(dateKey, tvState.interval));
			$('#tv-leg-val').text(formatRupiahFull(val));

			var isAbove = (tvState.source === 'NET_CASHFLOW') ? (val >= 0) : (val >= baseline);
			$('#tv-leg-val')
				.removeClass('tv-up-color tv-down-color')
				.addClass(isAbove ? 'tv-up-color' : 'tv-down-color');

			if (isForecast) {
				$('#tv-leg-status').attr('class', 'tv-badge-forecast').html('<i class="fas fa-wand-magic-sparkles mr-1"></i>FORECAST AI');
			} else if (val === 0 && (!meta || meta.vol_cnt === 0)) {
				$('#tv-leg-status').attr('class', 'badge badge-secondary').text('BELUM ADA TRANSAKSI');
			} else if (tvState.source === 'NET_CASHFLOW') {
				if (val >= 0) {
					$('#tv-leg-status').attr('class', 'tv-badge-up').html('<i class="fas fa-arrow-up mr-1"></i>SURPLUS KAS');
				} else {
					$('#tv-leg-status').attr('class', 'tv-badge-down').html('<i class="fas fa-arrow-down mr-1"></i>DEFISIT KAS');
				}
			} else if (isAbove) {
				$('#tv-leg-status').attr('class', 'tv-badge-up').html('<i class="fas fa-arrow-up mr-1"></i>DI ATAS BASELINE');
			} else {
				$('#tv-leg-status').attr('class', 'tv-badge-down').html('<i class="fas fa-arrow-down mr-1"></i>DI BAWAH BASELINE');
			}

			if (meta) {
				$('#tv-leg-inflow').text(formatRupiahShort(meta.inflow_val || 0));
				$('#tv-leg-outflow').text(formatRupiahShort(meta.outflow_val || 0));
				$('#tv-leg-vol').text((meta.vol_cnt || 0) + ' Dok');
				var breakdownParts = [];
				if (meta.po_val > 0 || meta.inv_val > 0) {
					breakdownParts.push('PO: ' + formatRupiahShort(meta.po_val || 0) + ' (' + (meta.po_cnt || 0) + ') • Inv: ' + formatRupiahShort(meta.inv_val || 0) + ' (' + (meta.inv_cnt || 0) + ')');
				}
				if (meta.top_docs && meta.top_docs.length > 0) {
					breakdownParts.push(meta.top_docs.join(' | '));
				}
				$('#tv-leg-docs').text(breakdownParts.join(' — '));
			}
		}

		function renderTvSeries() {
			if (!tvChart || !tvState.rawData) return;

			for (var i = 0; i < activeSeriesList.length; i++) {
				try {
					tvChart.removeSeries(activeSeriesList[i]);
				} catch (e) {}
			}
			activeSeriesList = [];

			var resp        = tvState.rawData;
			var mainData    = resp.series_main || [];
			var inflowData  = resp.series_inflow || [];
			var outflowData = resp.series_outflow || [];
			var poData      = resp.series_po || [];
			var invData     = resp.series_inv || [];
			var volData     = resp.series_volume || [];
			var ohlcData    = resp.series_ohlc || [];
			var fcastData   = resp.series_forecast || [];
			var fcastUpper  = resp.series_forecast_upper || [];
			var fcastLower  = resp.series_forecast_lower || [];
			var baseline    = parseFloat(resp.baseline_value || 0);
			var burnRate    = parseFloat(resp.avg_outflow_burn || 0);

			// 1. Render Main Series sesuai mode pilihan
			if (tvState.chartType === 'baseline') {
				var baseSeries = tvChart.addBaselineSeries({
					baseValue: { type: 'price', price: baseline },
					topLineColor: '#10b981',
					topFillColor1: 'rgba(16, 185, 129, 0.42)',
					topFillColor2: 'rgba(16, 185, 129, 0.04)',
					bottomLineColor: '#ef4444',
					bottomFillColor1: 'rgba(239, 68, 68, 0.04)',
					bottomFillColor2: 'rgba(239, 68, 68, 0.40)',
					lineWidth: 3,
					crosshairMarkerVisible: true,
					crosshairMarkerRadius: 5,
					priceLineVisible: true,
					lastValueVisible: true
				});
				baseSeries.setData(mainData);

				baseSeries.createPriceLine({
					price: baseline,
					color: tvState.source === 'NET_CASHFLOW' ? '#94a3b8' : '#f59e0b',
					lineWidth: 1,
					lineStyle: LightweightCharts.LineStyle.Dashed,
					axisLabelVisible: true,
					title: tvState.source === 'NET_CASHFLOW' ? 'Break-Even (Rp 0)' : 'Rata-rata (Baseline)'
				});

				if (tvState.source === 'INFLOW' && burnRate > 0) {
					baseSeries.createPriceLine({
						price: burnRate,
						color: '#ef4444',
						lineWidth: 1,
						lineStyle: LightweightCharts.LineStyle.Dotted,
						axisLabelVisible: true,
						title: 'Avg Burn Rate (Outflow)'
					});
				}
				activeSeriesList.push(baseSeries);

			} else if (tvState.chartType === 'cashflow_compare') {
				// Kurva 1: Total Pemasukan / Inflow (Hijau Emerald)
				var inSeries = tvChart.addAreaSeries({
					lineColor: '#10b981',
					topColor: 'rgba(16, 185, 129, 0.34)',
					bottomColor: 'rgba(16, 185, 129, 0.02)',
					lineWidth: 3,
					title: 'Inflow (Pemasukan)'
				});
				inSeries.setData(inflowData);
				activeSeriesList.push(inSeries);

				// Kurva 2: Total Pengeluaran / Outflow (Merah Crimson)
				var outSeries = tvChart.addAreaSeries({
					lineColor: '#ef4444',
					topColor: 'rgba(239, 68, 68, 0.30)',
					bottomColor: 'rgba(239, 68, 68, 0.02)',
					lineWidth: 3,
					title: 'Outflow (Pengeluaran)'
				});
				outSeries.setData(outflowData);
				activeSeriesList.push(outSeries);

			} else if (tvState.chartType === 'compare') {
				// Garis 1: PO Masuk (Emerald Green)
				var poSeries = tvChart.addAreaSeries({
					lineColor: '#10b981',
					topColor: 'rgba(16, 185, 129, 0.30)',
					bottomColor: 'rgba(16, 185, 129, 0.0)',
					lineWidth: 3,
					title: 'PO Masuk'
				});
				poSeries.setData(poData);
				activeSeriesList.push(poSeries);

				// Garis 2: Invoice (Sky Blue)
				var invSeries = tvChart.addLineSeries({
					color: '#38bdf8',
					lineWidth: 3,
					title: 'Invoice'
				});
				invSeries.setData(invData);
				activeSeriesList.push(invSeries);

			} else if (tvState.chartType === 'area') {
				var areaSeries = tvChart.addAreaSeries({
					lineColor: '#38bdf8',
					topColor: 'rgba(56, 189, 248, 0.42)',
					bottomColor: 'rgba(56, 189, 248, 0.02)',
					lineWidth: 3,
					crosshairMarkerVisible: true,
					crosshairMarkerRadius: 5
				});
				areaSeries.setData(mainData);
				activeSeriesList.push(areaSeries);

			} else if (tvState.chartType === 'candlestick') {
				var candleSeries = tvChart.addCandlestickSeries({
					upColor: '#10b981',
					downColor: '#ef4444',
					borderVisible: false,
					wickUpColor: '#10b981',
					wickDownColor: '#ef4444'
				});
				candleSeries.setData(ohlcData);
				activeSeriesList.push(candleSeries);
			}

			// 2. Render Indikator Moving Average Crossover (MA-3 Fast & MA-6 Slow)
			if (tvState.showMA && tvState.chartType !== 'compare' && tvState.chartType !== 'cashflow_compare') {
				if (mainData.length >= 3) {
					var ma3Data = computeSMA(mainData, 3);
					var ma3Series = tvChart.addLineSeries({
						color: '#f59e0b',
						lineWidth: 2,
						lineStyle: LightweightCharts.LineStyle.Solid,
						title: 'MA(3)',
						crosshairMarkerVisible: false,
						lastValueVisible: false,
						priceLineVisible: false
					});
					ma3Series.setData(ma3Data);
					activeSeriesList.push(ma3Series);
				}
				if (mainData.length >= 6) {
					var ma6Data = computeSMA(mainData, 6);
					var ma6Series = tvChart.addLineSeries({
						color: '#38bdf8',
						lineWidth: 2,
						lineStyle: LightweightCharts.LineStyle.Dashed,
						title: 'MA(6)',
						crosshairMarkerVisible: false,
						lastValueVisible: false,
						priceLineVisible: false
					});
					ma6Series.setData(ma6Data);
					activeSeriesList.push(ma6Series);
				}
			}

			// 3. Render Proyeksi Forecasting (+3 Periode ke Depan + Confidence Bands)
			if (tvState.showForecast && fcastData.length >= 2 && tvState.chartType !== 'compare' && tvState.chartType !== 'cashflow_compare') {
				// Upper Optimistic Band
				var upperSeries = tvChart.addLineSeries({
					color: 'rgba(192, 132, 252, 0.45)',
					lineWidth: 1,
					lineStyle: LightweightCharts.LineStyle.Dotted,
					title: 'Optimis (+1σ)',
					crosshairMarkerVisible: false,
					lastValueVisible: false,
					priceLineVisible: false
				});
				upperSeries.setData(fcastUpper);
				activeSeriesList.push(upperSeries);

				// Lower Conservative Band
				var lowerSeries = tvChart.addLineSeries({
					color: 'rgba(192, 132, 252, 0.45)',
					lineWidth: 1,
					lineStyle: LightweightCharts.LineStyle.Dotted,
					title: 'Konservatif (-1σ)',
					crosshairMarkerVisible: false,
					lastValueVisible: false,
					priceLineVisible: false
				});
				lowerSeries.setData(fcastLower);
				activeSeriesList.push(lowerSeries);

				// Main Forecast Trendline
				var fcastSeries = tvChart.addLineSeries({
					color: '#c084fc',
					lineWidth: 3,
					lineStyle: LightweightCharts.LineStyle.Dashed,
					title: 'Forecast (+3)',
					crosshairMarkerVisible: true,
					crosshairMarkerRadius: 5,
					lastValueVisible: true,
					priceLineVisible: false
				});
				fcastSeries.setData(fcastData);
				activeSeriesList.push(fcastSeries);
			}

			// 4. Render Volume Histogram (Frekuensi Dokumen Transaksi) di bagian bawah
			var volSeries = tvChart.addHistogramSeries({
				color: '#26a69a',
				priceFormat: {
					type: 'volume'
				},
				priceScaleId: 'vol_scale',
				lastValueVisible: false,
				priceLineVisible: false
			});
			tvChart.priceScale('vol_scale').applyOptions({
				scaleMargins: {
					top: 0.80,
					bottom: 0
				}
			});
			volSeries.setData(volData);
			activeSeriesList.push(volSeries);

			tvChart.timeScale().fitContent();

			if (mainData.length > 0) {
				var lastPt = mainData[mainData.length - 1];
				updateFloatingLegend(lastPt.time, lastPt.value, (resp.tooltip_meta || {})[lastPt.time], baseline);
			}
		}

		function updateTickerHeader(resp) {
			var sum = resp.summary || {};
			var tickerMeta = {
				'NET_CASHFLOW': { code: 'VSI:NET_CASHFLOW', desc: 'Analisis Arus Kas Bersih (Total Pemasukan PO/Invoice dikurangi Pengeluaran Ekspedisi & Payroll)' },
				'INFLOW':       { code: 'VSI:TOTAL_INFLOW', desc: 'Total Arus Kas Masuk Gabungan (Omzet PO Masuk + Realisasi Invoice Customer)' },
				'OUTFLOW':      { code: 'VSI:TOTAL_OUTFLOW',desc: 'Total Arus Kas Keluar (Tagihan Ekspedisi/SPP + Beban Gaji Payroll SDM)' },
				'PO':           { code: 'VSI:OMZET_PO',     desc: 'Arus Masuk Purchase Order (PO Masuk Customer) — Real-Time Database' },
				'INV':          { code: 'VSI:INVOICE',      desc: 'Realisasi Penagihan Invoice Customer — Real-Time Database' },
				'CUMULATIVE':   { code: 'VSI:YTD_EQUITY',   desc: 'Kurva Akumulasi Pertumbuhan Kas Berjalan (Cumulative Equity Curve)' }
			};
			var info = tickerMeta[tvState.source] || tickerMeta['NET_CASHFLOW'];
			$('#tv-ticker-code').text(info.code);
			$('#tv-ticker-desc').text(info.desc);
			$('#tv-label-year').text(resp.tahun === 'ALL' ? 'All-Time' : resp.tahun);

			var netVal = parseFloat(sum.total_net_cashflow || 0);
			$('#tv-stat-net')
				.text(formatRupiahShort(netVal))
				.removeClass('tv-up-color tv-down-color')
				.addClass(netVal >= 0 ? 'tv-up-color' : 'tv-down-color');

			var inCnt = parseInt(sum.total_inflow_count || (parseInt(sum.total_po_count || 0, 10) + parseInt(sum.total_inv_count || 0, 10)), 10);
			$('#tv-stat-inflow').html(formatRupiahShort(sum.total_inflow_nominal || 0) + ' <small style="font-size: 10.5px; color: #64748b; font-weight: 600;">(' + inCnt + ' Dok)</small>');
			$('#tv-stat-outflow').html(formatRupiahShort(sum.total_outflow_nominal || 0) + ' <small style="font-size: 10.5px; color: #64748b; font-weight: 600;">(' + (sum.total_out_count || 0) + ' Dok)</small>');
			$('#tv-stat-forecast').text(formatRupiahShort(sum.next_forecast_val || 0));
			$('#tv-stat-high').text(formatRupiahShort(sum.high_nominal || 0));

			var g = parseFloat(sum.growth_pct || 0);
			if (g >= 0) {
				$('#tv-growth-badge')
					.attr('class', 'tv-badge-up')
					.html('<i class="fas fa-caret-up mr-1"></i>+' + g.toFixed(2) + '%');
			} else {
				$('#tv-growth-badge')
					.attr('class', 'tv-badge-down')
					.html('<i class="fas fa-caret-down mr-1"></i>' + g.toFixed(2) + '%');
			}

			// Update 4 Kartu Sinyal Analisis Teknikal di Footer Terminal
			var sigStatus = sum.signal_status || 'NEUTRAL';
			$('#tv-sig-badge')
				.attr('class', sigStatus === 'BEARISH' ? 'tv-badge-down' : 'tv-badge-up')
				.text(sigStatus);
			$('#tv-sig-label').text(sum.signal_label || '-');
			$('#tv-sig-desc').text(sum.signal_desc || '-');

			$('#tv-sig-ratio').text((sum.cashflow_ratio || 0) + 'x Coverage');
			$('#tv-sig-burn').text('Burn Rate: ' + formatRupiahShort(resp.avg_outflow_burn || 0) + ' / periode');

			$('#tv-sig-forecast-val').text(formatRupiahFull(sum.next_forecast_val || 0));
			var fUpper = (resp.series_forecast_upper && resp.series_forecast_upper.length > 1) ? resp.series_forecast_upper[1].value : 0;
			var fLower = (resp.series_forecast_lower && resp.series_forecast_lower.length > 1) ? resp.series_forecast_lower[1].value : 0;
			$('#tv-sig-forecast-range').text('Rentang: ' + formatRupiahShort(fLower) + ' s/d ' + formatRupiahShort(fUpper));

			$('#tv-sig-conv-pct').text((sum.conversion_rate_pct || 0) + '% Tertagih');
			$('#tv-sig-unbilled').text('Unbilled PO: ' + formatRupiahShort(sum.unbilled_po_nominal || 0));
		}

		function loadTradingViewData(autoFallback) {
			var selectedYear = (tvState.scope === 'ALL') ? 'ALL' : ($('#filter_year').val() || '<?= $tahun_sekarang ?>');
			$.ajax({
				url: '<?= base_url("dashboard_accounting/tradingview_financial_data") ?>',
				type: 'GET',
				data: {
					tahun: selectedYear,
					interval: tvState.interval,
					source: tvState.source
				},
				dataType: 'json',
				success: function(resp) {
					if (!resp || resp.status !== 'success') return;

					// Jika tahun terpilih belum memiliki transaksi sama sekali, otomatis tampilkan All-Time
					if (autoFallback && resp.summary) {
						if (resp.summary.total_inflow_nominal <= 0 && resp.summary.total_outflow_nominal <= 0 && tvState.scope === 'YEAR') {
							tvState.scope = 'ALL';
							$('#tv-scope-group .tv-btn').removeClass('active');
							$('#tv-scope-group .tv-btn[data-scope="ALL"]').addClass('active');
							loadTradingViewData(false);
							return;
						}
					}

					tvState.rawData = resp;
					updateTickerHeader(resp);
					if (!tvChart) {
						initTvChart();
					}
					renderTvSeries();
				}
			});
		}

		// Event Listeners untuk Toolbar TradingView
		$('#tv-source-group .tv-btn').on('click', function() {
			$('#tv-source-group .tv-btn').removeClass('active');
			$(this).addClass('active');
			tvState.source = $(this).data('source');
			loadTradingViewData(false);
		});

		$('#tv-interval-group .tv-btn').on('click', function() {
			$('#tv-interval-group .tv-btn').removeClass('active');
			$(this).addClass('active');
			tvState.interval = $(this).data('interval');
			loadTradingViewData(false);
		});

		$('#tv-scope-group .tv-btn').on('click', function() {
			$('#tv-scope-group .tv-btn').removeClass('active');
			$(this).addClass('active');
			tvState.scope = $(this).data('scope');
			loadTradingViewData(false);
		});

		$('#tv-chart-type-group .tv-btn').on('click', function() {
			$('#tv-chart-type-group .tv-btn').removeClass('active');
			$(this).addClass('active');
			tvState.chartType = $(this).data('type');
			renderTvSeries();
		});

		$('#tv-toggle-forecast').on('click', function() {
			tvState.showForecast = !tvState.showForecast;
			$(this).toggleClass('active', tvState.showForecast);
			renderTvSeries();
		});

		$('#tv-toggle-ma').on('click', function() {
			tvState.showMA = !tvState.showMA;
			$(this).toggleClass('active', tvState.showMA);
			renderTvSeries();
		});

		$('#tv-btn-fit').on('click', function() {
			if (tvChart) {
				tvChart.timeScale().fitContent();
			}
		});

		$('#tv-btn-theme').on('click', function() {
			tvState.isLight = !tvState.isLight;
			$('#tv-terminal-card').toggleClass('tv-light-mode', tvState.isLight);
			$(this).find('i').attr('class', tvState.isLight ? 'fas fa-moon' : 'fas fa-sun');
			initTvChart();
			renderTvSeries();
		});

		$(window).on('resize', function() {
			if (tvChart && tvContainer) {
				tvChart.applyOptions({ width: tvContainer.clientWidth });
			}
		});

		// Load pertama kali TradingView Chart
		initTvChart();
		loadTradingViewData(true);

		// =========================================================================
		// B. EXISTING DONUT & MONTHLY PAJAK CHARTS (CHART.JS)
		// =========================================================================
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
				if (tvState.scope === 'YEAR') {
					loadTradingViewData(false);
				}
			}
		});
	});
</script>
