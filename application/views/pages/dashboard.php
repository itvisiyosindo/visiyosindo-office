<header class="page-header">
	<h2><i class="icons icon-list"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
	</div>
</header>

<style>
	/* Main Home Executive Dashboard Custom Styles */
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

	.big-icon {
		font-size: 45px;
	}

	.dashboard-calendar-panel {
		border: 1px solid #e2e8f0;
		border-radius: 14px;
		box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
	}

	.dashboard-calendar-panel .card-body {
		padding: 18px;
	}

	/* =========================================================
	   VISILAB CALENDAR WIDGET
	========================================================= */
	.visilab-calendar-widget {
		background: #ffffff;
		border: 1px solid rgba(226, 232, 240, 0.9);
		border-radius: 20px;
		box-shadow: 0 4px 24px -2px rgba(15, 23, 42, 0.06);
		padding: 24px;
		margin-bottom: 28px;
		font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
	}

	.visilab-calendar-widget .calendar-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 16px;
		margin-bottom: 20px;
		padding-bottom: 16px;
		border-bottom: 1px solid #f1f5f9;
	}

	.visilab-calendar-widget .calendar-title-wrap {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.visilab-calendar-widget .calendar-icon-badge {
		width: 44px;
		height: 44px;
		border-radius: 12px;
		background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
		color: #059669;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		flex-shrink: 0;
	}

	.visilab-calendar-widget .calendar-title {
		margin: 0;
		font-weight: 700;
		font-size: 1.15rem;
		color: #0f172a;
		letter-spacing: -0.01em;
	}

	.visilab-calendar-widget .calendar-subtitle {
		margin: 2px 0 0 0;
		font-size: 0.82rem;
		color: #64748b;
	}

	#dashboard-visilab-calendar {
		min-height: 480px;
	}

	#dashboard-visilab-calendar .fc-toolbar {
		margin-bottom: 16px !important;
		flex-wrap: wrap;
		gap: 10px;
	}

	#dashboard-visilab-calendar .fc-toolbar-title {
		font-size: 1.15rem !important;
		font-weight: 700 !important;
		color: #0f172a !important;
	}

	#dashboard-visilab-calendar .fc-button {
		background: #f8fafc !important;
		color: #334155 !important;
		border: 1px solid #e2e8f0 !important;
		font-size: 12px !important;
		font-weight: 600 !important;
		padding: 6px 14px !important;
		border-radius: 10px !important;
		box-shadow: none !important;
		transition: all 0.2s ease !important;
		text-transform: capitalize !important;
	}

	#dashboard-visilab-calendar .fc-button:hover {
		background: #e2e8f0 !important;
		color: #0f172a !important;
	}

	#dashboard-visilab-calendar .fc-button-active,
	#dashboard-visilab-calendar .fc-button-primary:not(:disabled).fc-button-active {
		background: linear-gradient(135deg, #059669 0%, #0d9488 100%) !important;
		color: #ffffff !important;
		border-color: transparent !important;
		box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25) !important;
	}

	#dashboard-visilab-calendar .fc-button-group {
		gap: 4px;
	}

	#dashboard-visilab-calendar .fc-button-group > .fc-button {
		border-radius: 10px !important;
		margin: 0 !important;
	}

	#dashboard-visilab-calendar .fc-col-header-cell {
		background: #f8fafc;
		padding: 10px 0;
		border-color: #e2e8f0;
	}

	#dashboard-visilab-calendar .fc-col-header-cell-cushion {
		font-size: 12px;
		font-weight: 700;
		color: #475569;
		text-transform: uppercase;
		letter-spacing: 0.04em;
	}

	#dashboard-visilab-calendar .fc-daygrid-day {
		border-color: #f1f5f9;
		transition: background-color 0.15s ease;
	}

	#dashboard-visilab-calendar .fc-daygrid-day:hover {
		background-color: #f8fafc;
	}

	#dashboard-visilab-calendar .fc-day-today {
		background-color: #f0fdf4 !important;
	}

	#dashboard-visilab-calendar .fc-daygrid-day-number {
		font-size: 12px;
		font-weight: 600;
		color: #64748b;
		padding: 6px 8px;
	}

	#dashboard-visilab-calendar .fc-event {
		border: none !important;
		background: transparent !important;
		margin: 2px 4px !important;
		cursor: pointer;
		transition: transform 0.15s ease;
	}

	#dashboard-visilab-calendar .fc-event:hover {
		transform: translateY(-1px);
	}

	.visilab-event-chip {
		display: flex;
		align-items: center;
		gap: 6px;
		background: #ffffff;
		border: 1px solid #e2e8f0;
		border-radius: 8px;
		padding: 3px 8px;
		font-size: 11px;
		font-weight: 600;
		color: #1e293b;
		box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}

	.visilab-event-chip:hover {
		box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
	}

	.visilab-badge-tag {
		display: inline-block;
		padding: 1px 7px;
		border-radius: 999px;
		color: #ffffff;
		font-size: 10px;
		font-weight: 700;
		flex-shrink: 0;
	}

	/* =========================================================
	   MODERN DASHBOARD SECTION CARDS (Pengumuman & Log)
	========================================================= */
	.modern-dashboard-widget {
		background: #ffffff;
		border: 1px solid rgba(226, 232, 240, 0.9);
		border-radius: 20px;
		box-shadow: 0 4px 24px -2px rgba(15, 23, 42, 0.06);
		padding: 24px;
		margin-bottom: 28px;
		font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
		height: calc(100% - 28px);
		display: flex;
		flex-direction: column;
	}

	.modern-dashboard-widget .widget-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 16px;
		margin-bottom: 20px;
		padding-bottom: 16px;
		border-bottom: 1px solid #f1f5f9;
	}

	.modern-dashboard-widget .widget-title-wrap {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.modern-dashboard-widget .widget-icon-badge {
		width: 44px;
		height: 44px;
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		flex-shrink: 0;
	}

	.badge-announcement {
		background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
		color: #d97706;
	}

	.badge-activity-log {
		background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
		color: #7c3aed;
	}

	.modern-dashboard-widget .widget-title {
		margin: 0;
		font-weight: 700;
		font-size: 1.15rem;
		color: #0f172a;
		letter-spacing: -0.01em;
	}

	.modern-dashboard-widget .widget-subtitle {
		margin: 2px 0 0 0;
		font-size: 0.82rem;
		color: #64748b;
	}

	/* Announcement Items */
	.announcement-feed-container {
		display: flex;
		flex-direction: column;
		gap: 14px;
		max-height: 480px;
		overflow-y: auto;
		padding-right: 4px;
		margin-bottom: 16px;
		flex: 1;
	}

	.announcement-feed-container::-webkit-scrollbar {
		width: 5px;
	}
	.announcement-feed-container::-webkit-scrollbar-thumb {
		background: #cbd5e1;
		border-radius: 10px;
	}

	.announcement-item-card {
		background: #f8fafc;
		border: 1px solid #f1f5f9;
		border-radius: 14px;
		padding: 16px;
		transition: all 0.2s ease;
	}

	.announcement-item-card:hover {
		background: #ffffff;
		border-color: #e2e8f0;
		box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
		transform: translateY(-1px);
	}

	.announcement-item-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 10px;
	}

	.announcement-author-info {
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.announcement-avatar {
		width: 36px;
		height: 36px;
		border-radius: 10px;
		background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
		color: #ffffff;
		display: flex;
		align-items: center;
		justify-content: center;
		font-weight: 700;
		font-size: 14px;
		box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
		flex-shrink: 0;
	}

	.announcement-author-name {
		font-weight: 700;
		font-size: 0.92rem;
		color: #0f172a;
		line-height: 1.2;
	}

	.announcement-date-pill {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		font-size: 11px;
		color: #64748b;
		background: #ffffff;
		border: 1px solid #e2e8f0;
		padding: 3px 8px;
		border-radius: 20px;
		font-weight: 500;
	}

	.announcement-item-body {
		font-size: 0.92rem;
		color: #334155;
		line-height: 1.6;
		margin-bottom: 10px;
		word-break: break-word;
	}

	.announcement-item-body a {
		color: #1e293b;
		text-decoration: none;
	}

	.announcement-actions {
		display: flex;
		align-items: center;
		justify-content: flex-end;
		gap: 8px;
		padding-top: 8px;
		border-top: 1px dashed #e2e8f0;
	}

	.btn-pill-action {
		border-radius: 20px !important;
		font-size: 11px !important;
		font-weight: 600 !important;
		padding: 4px 12px !important;
		display: inline-flex !important;
		align-items: center !important;
		gap: 5px !important;
		transition: all 0.2s ease !important;
	}

	.btn-view-all-announcement {
		background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
		color: #ffffff !important;
		font-weight: 600;
		border: none;
		border-radius: 12px;
		padding: 10px 16px;
		font-size: 13px;
		box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
		transition: all 0.2s ease;
		text-align: center;
		display: block;
	}

	.btn-view-all-announcement:hover {
		transform: translateY(-1px);
		box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
	}

	/* Activity Log Modern Styling */
	.activity-filter-box {
		background: #f8fafc;
		border: 1px solid #e2e8f0;
		border-radius: 12px;
		padding: 10px 14px;
		margin-bottom: 16px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
		gap: 10px;
	}

	.activity-filter-box label {
		font-size: 12px;
		font-weight: 600;
		color: #475569;
		margin: 0;
	}

	.activity-filter-input-wrap {
		width: 190px;
	}

	.activity-filter-input-wrap .input-group-text {
		background: #ffffff;
		border-color: #cbd5e1;
		color: #64748b;
		border-top-left-radius: 8px;
		border-bottom-left-radius: 8px;
	}

	.activity-filter-input-wrap input {
		border-color: #cbd5e1;
		font-size: 12px;
		font-weight: 500;
		border-top-right-radius: 8px;
		border-bottom-right-radius: 8px;
	}

	.modern-table-wrap {
		border: 1px solid #e2e8f0;
		border-radius: 12px;
		overflow: hidden;
	}

	#kt_table_1 {
		margin: 0 !important;
		font-size: 12px;
	}

	#kt_table_1 thead th {
		background: #f8fafc;
		color: #475569;
		font-weight: 700;
		font-size: 11px;
		text-transform: uppercase;
		letter-spacing: 0.04em;
		border-bottom: 1px solid #e2e8f0 !important;
		padding: 10px 12px;
	}

	#kt_table_1 tbody td {
		padding: 10px 12px;
		vertical-align: middle;
		border-color: #f1f5f9;
		color: #334155;
	}

	#kt_table_1 tbody tr:hover td {
		background-color: #f8fafc;
	}

	/* Visilab Detail Modal */
	.modern-visilab-modal .modal-content {
		border: none;
		border-radius: 20px;
		overflow: hidden;
		box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
	}

	.modern-visilab-modal .modal-header {
		background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
		color: #ffffff;
		border-bottom: none;
		padding: 18px 24px;
	}

	.modern-visilab-modal .modal-title {
		font-weight: 700;
		font-size: 1.1rem;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.modern-visilab-modal .modal-body {
		padding: 24px;
		background: #ffffff;
	}

	.visilab-detail-grid {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 12px;
	}

	@media (max-width: 576px) {
		.visilab-detail-grid {
			grid-template-columns: 1fr;
		}
	}

	.visilab-detail-card {
		background: #f8fafc;
		border: 1px solid #e2e8f0;
		border-radius: 10px;
		padding: 10px 14px;
	}

	.visilab-detail-card.full-width {
		grid-column: span 2;
	}

	@media (max-width: 576px) {
		.visilab-detail-card.full-width {
			grid-column: span 1;
		}
	}

	.visilab-detail-label {
		font-size: 11px;
		font-weight: 600;
		color: #64748b;
		text-transform: uppercase;
		letter-spacing: 0.03em;
		margin-bottom: 2px;
	}

	.visilab-detail-value {
		font-size: 13px;
		font-weight: 600;
		color: #0f172a;
	}

	/* Modern Dashboard Attendance Stats Cards */
	.dash-stat-card {
		background: #ffffff;
		border: 1px solid rgba(226, 232, 240, 0.9);
		border-radius: 16px;
		padding: 16px 18px;
		box-shadow: 0 4px 18px -2px rgba(15, 23, 42, 0.05);
		transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		position: relative;
		overflow: hidden;
		height: 100%;
	}

	.dash-stat-card:hover {
		transform: translateY(-3px);
		box-shadow: 0 10px 25px -3px rgba(15, 23, 42, 0.08);
		border-color: #cbd5e1;
	}

	.dash-stat-top {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 8px;
	}

	.dash-stat-label {
		font-size: 11px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.04em;
		color: #64748b;
		margin: 0;
	}

	.dash-stat-icon {
		width: 36px;
		height: 36px;
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
		flex-shrink: 0;
		transition: transform 0.2s ease;
	}

	.dash-stat-card:hover .dash-stat-icon {
		transform: scale(1.08);
	}

	.dash-stat-val {
		font-size: 26px;
		font-weight: 800;
		color: #0f172a;
		line-height: 1.1;
		margin: 0;
		font-family: 'Poppins', sans-serif;
	}

	.dash-stat-link {
		font-size: 11px;
		font-weight: 600;
		color: #94a3b8;
		margin-top: 8px;
		display: flex;
		align-items: center;
		gap: 4px;
		transition: color 0.2s ease;
	}

	.dash-stat-card:hover .dash-stat-link {
		color: #2563eb;
	}

	.icon-dash-hadir { background: #ecfdf5; color: #059669; }
	.icon-dash-terlambat { background: #fff1f2; color: #e11d48; }
	.icon-dash-istirahat { background: #eff6ff; color: #2563eb; }
	.icon-dash-noistirahat { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
	.icon-dash-izin { background: #f5f3ff; color: #7c3aed; }
	.icon-dash-cuti { background: #ecfeff; color: #0891b2; }
	.icon-dash-sakit { background: #fffbeb; color: #d97706; }
	.icon-dash-pulang { background: #fdf2f8; color: #db2777; }
	.icon-dash-pengumuman { background: #eef2ff; color: #4f46e5; }
	.icon-dash-birthday { background: #fef3c7; color: #d97706; }
</style>

<div class="row">
	<!-- 1. Executive Top Banner Overview -->
	<div class="col-12">
		<div class="dash-banner">
			<div class="row align-items-center">
				<div class="col-lg-7 col-12 mb-3 mb-lg-0">
					<div class="d-flex align-items-center">
						<div class="dash-banner-icon">
							<i class="fas fa-home"></i>
						</div>
						<div>
							<h3 style="font-size: 17px; font-weight: 700; color: #ffffff; margin: 0 0 4px 0;">Selamat Datang, <?= sessPenggunaNama() ?: 'Karyawan PT Visi Yosindo Medikal' ?>!</h3>
							<p style="font-size: 12px; color: #94a3b8; margin: 0; line-height: 1.4;">Sistem Informasi Manajemen, Presensi Terpadu & Layanan Operasional Kantor.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-5 col-12 text-lg-right">
					<div class="d-inline-flex flex-wrap align-items-center justify-content-start justify-content-lg-end" style="gap: 8px;">
						<span class="badge" style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; font-weight: 600; padding: 6px 12px; border-radius: 9999px; font-size: 12px;">
							<i class="fas fa-calendar-day mr-1"></i> <?= date('d M Y') ?>
						</span>
						<a href="<?= base_url('absensi') ?>" class="btn btn-sm btn-primary" style="font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 6px;">
							<i class="fas fa-fingerprint mr-1"></i> Absensi
						</a>
						<a href="<?= base_url('announcement') ?>" class="btn btn-sm btn-info" style="font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 6px;">
							<i class="fas fa-bullhorn mr-1"></i> Pengumuman
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if (sessPenggunaId() == 1 || sessPenggunaId() == 69 || sessPenggunaId() == 744 || sessPenggunaId() == 58 || sessPenggunaId() == 54) { ?>

		<?php if ($ulangtahun[0]->total != 0) { ?>
			<div class="col-xl-4 col-sm-6 col-12 mb-3">
				<div class="dash-stat-card" style="border-left: 4px solid #f59e0b;">
					<div class="dash-stat-top">
						<span class="dash-stat-label" style="color: #d97706;"><i class="fas fa-birthday-cake mr-1"></i> Happy Birthday</span>
						<div class="dash-stat-icon icon-dash-birthday"><i class="fas fa-gift"></i></div>
					</div>
					<div>
						<?php foreach ($daftar_ulangtahun as $row) { ?>
							<h5 class="font-weight-bold text-dark mb-1" style="font-size: 15px;"><?= $row->nama ?></h5>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>

		<div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-3">
			<a href="javascript:;" id="btn-show-hadir" class="text-decoration-none">
				<div class="dash-stat-card">
					<div class="dash-stat-top">
						<span class="dash-stat-label">Total Hadir</span>
						<div class="dash-stat-icon icon-dash-hadir"><i class="fas fa-user-check"></i></div>
					</div>
					<div class="dash-stat-val"><?= $present[0]->total ?></div>
					<div class="dash-stat-link">Lihat Detail <i class="fas fa-chevron-right ml-auto"></i></div>
				</div>
			</a>
		</div>

		<div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-3">
			<a href="javascript:;" id="btn-show-terlambat" class="text-decoration-none">
				<div class="dash-stat-card">
					<div class="dash-stat-top">
						<span class="dash-stat-label">Total Terlambat</span>
						<div class="dash-stat-icon icon-dash-terlambat"><i class="fas fa-user-clock"></i></div>
					</div>
					<div class="dash-stat-val"><?= $terlambat[0]->total ?></div>
					<div class="dash-stat-link">Lihat Detail <i class="fas fa-chevron-right ml-auto"></i></div>
				</div>
			</a>
		</div>

		<?php if ($istirahat[0]->total != 0) { ?>
			<div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-3">
				<a href="javascript:;" id="btn-show-istirahat" class="text-decoration-none">
					<div class="dash-stat-card">
						<div class="dash-stat-top">
							<span class="dash-stat-label">Absen Istirahat</span>
							<div class="dash-stat-icon icon-dash-istirahat"><i class="fas fa-coffee"></i></div>
						</div>
						<div class="dash-stat-val"><?= $istirahat[0]->total ?></div>
						<div class="dash-stat-link">Lihat Detail <i class="fas fa-chevron-right ml-auto"></i></div>
					</div>
				</a>
			</div>

			<div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-3">
				<a href="javascript:;" id="btn-show-noistirahat" class="text-decoration-none">
					<div class="dash-stat-card">
						<div class="dash-stat-top">
							<span class="dash-stat-label">Tidak Istirahat</span>
							<div class="dash-stat-icon icon-dash-noistirahat"><i class="fas fa-utensils"></i></div>
						</div>
						<div class="dash-stat-val"><?= $noistirahat ?></div>
						<div class="dash-stat-link">Lihat Detail <i class="fas fa-chevron-right ml-auto"></i></div>
					</div>
				</a>
			</div>
		<?php } ?>

		<div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-3">
			<a href="javascript:;" id="btn-show-izin" class="text-decoration-none">
				<div class="dash-stat-card">
					<div class="dash-stat-top">
						<span class="dash-stat-label">Total Izin</span>
						<div class="dash-stat-icon icon-dash-izin"><i class="fas fa-envelope-open-text"></i></div>
					</div>
					<div class="dash-stat-val"><?= $izin[0]->total ?></div>
					<div class="dash-stat-link">Lihat Detail <i class="fas fa-chevron-right ml-auto"></i></div>
				</div>
			</a>
		</div>

		<div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-3">
			<a href="javascript:;" id="btn-show-cuti" class="text-decoration-none">
				<div class="dash-stat-card">
					<div class="dash-stat-top">
						<span class="dash-stat-label">Total Cuti</span>
						<div class="dash-stat-icon icon-dash-cuti"><i class="fas fa-calendar-minus"></i></div>
					</div>
					<div class="dash-stat-val"><?= $cuti[0]->total ?></div>
					<div class="dash-stat-link">Lihat Detail <i class="fas fa-chevron-right ml-auto"></i></div>
				</div>
			</a>
		</div>

		<div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-3">
			<a href="javascript:;" id="btn-show-sakit" class="text-decoration-none">
				<div class="dash-stat-card">
					<div class="dash-stat-top">
						<span class="dash-stat-label">Total Sakit</span>
						<div class="dash-stat-icon icon-dash-sakit"><i class="fas fa-procedures"></i></div>
					</div>
					<div class="dash-stat-val"><?= $sakit[0]->total ?></div>
					<div class="dash-stat-link">Lihat Detail <i class="fas fa-chevron-right ml-auto"></i></div>
				</div>
			</a>
		</div>

		<div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-3">
			<a href="javascript:;" id="btn-show-pulang" class="text-decoration-none">
				<div class="dash-stat-card">
					<div class="dash-stat-top">
						<span class="dash-stat-label">Total Pulang</span>
						<div class="dash-stat-icon icon-dash-pulang"><i class="fas fa-sign-out-alt"></i></div>
					</div>
					<div class="dash-stat-val"><?= $pulang[0]->total ?></div>
					<div class="dash-stat-link">Lihat Detail <i class="fas fa-chevron-right ml-auto"></i></div>
				</div>
			</a>
		</div>

		<div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-3">
			<a href="<?= base_url('announcement') ?>" class="text-decoration-none">
				<div class="dash-stat-card">
					<div class="dash-stat-top">
						<span class="dash-stat-label">Pengumuman</span>
						<div class="dash-stat-icon icon-dash-pengumuman"><i class="fas fa-bullhorn"></i></div>
					</div>
					<div class="dash-stat-val"><?= $pengumuman[0]->total ?></div>
					<div class="dash-stat-link">Lihat Pengumuman <i class="fas fa-chevron-right ml-auto"></i></div>
				</div>
			</a>
		</div>

	<?php } else { ?>


		<!-- Ulang Tahun -->
		<?php if ($ulangtahun[0]->total != 0) { ?>
			<div class="col-xl-4 col-sm-6 col-12 mb-2">
				<div class="card board1 fill">
					<div class="card-body">
						<div class="dash-widget-header">
							<div>
								<h5 class="text-3-1 text-color-success line-height-2 my-0"> <strong>Happy Birthday</strong></h5>
							</div>
							<?php foreach ($daftar_ulangtahun as $row) { ?>
								<h4 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $row->nama ?></strong></h4>
							<?php } ?>
							<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-cake icon icon-inline icon-md bg-success rounded-circle text-color-light"></i>
									<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
									<circle cx="8.5" cy="7" r="4"></circle>
									<line x1="20" y1="8" x2="20" y2="14"></line>
									<line x1="23" y1="11" x2="17" y2="11"></line>
									</svg>
								</span> </div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>


		<style>
			.modern-stat-card {
				background: #ffffff;
				border: 1px solid rgba(226, 232, 240, 0.85);
				border-radius: 18px;
				padding: 22px 20px;
				box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
				transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
				position: relative;
				overflow: hidden;
			}

			.modern-stat-card::before {
				content: '';
				position: absolute;
				top: 0;
				left: 0;
				right: 0;
				height: 4px;
				background: transparent;
				transition: all 0.3s ease;
			}

			.modern-stat-card:hover {
				transform: translateY(-5px);
				box-shadow: 0 14px 28px -4px rgba(15, 23, 42, 0.1);
				border-color: rgba(203, 213, 225, 0.9);
			}

			/* Card Accents */
			.stat-card-blue:hover::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
			.stat-card-rose:hover::before { background: linear-gradient(90deg, #f43f5e, #fb7185); }
			.stat-card-purple:hover::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
			.stat-card-teal:hover::before { background: linear-gradient(90deg, #0ea5e9, #38bdf8); }

			.stat-icon-wrapper {
				width: 54px;
				height: 54px;
				border-radius: 16px;
				display: flex;
				align-items: center;
				justify-content: center;
				font-size: 26px;
				flex-shrink: 0;
				transition: transform 0.3s ease;
			}

			.modern-stat-card:hover .stat-icon-wrapper {
				transform: scale(1.08) rotate(3deg);
			}

			.stat-icon-blue {
				background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
				color: #2563eb;
			}

			.stat-icon-rose {
				background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
				color: #e11d48;
			}

			.stat-icon-purple {
				background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
				color: #7c3aed;
			}

			.stat-icon-teal {
				background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
				color: #0284c7;
			}

			.stat-card-label {
				font-size: 0.82rem;
				font-weight: 600;
				letter-spacing: 0.02em;
				margin-bottom: 6px;
			}

			.stat-card-value {
				font-size: 1.85rem;
				font-weight: 800;
				color: #0f172a;
				line-height: 1;
				font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
			}

			.stat-detail-pill {
				display: inline-flex;
				align-items: center;
				gap: 4px;
				font-size: 0.75rem;
				font-weight: 600;
				padding: 3px 9px;
				border-radius: 20px;
				transition: all 0.2s ease;
			}

			.stat-detail-pill.blue {
				background: #eff6ff;
				color: #2563eb;
			}
			.modern-stat-card:hover .stat-detail-pill.blue {
				background: #2563eb;
				color: #ffffff;
			}

			.stat-detail-pill.rose {
				background: #fff1f2;
				color: #e11d48;
			}
			.modern-stat-card:hover .stat-detail-pill.rose {
				background: #e11d48;
				color: #ffffff;
			}

			.stat-detail-pill.purple {
				background: #f5f3ff;
				color: #7c3aed;
			}
			.modern-stat-card:hover .stat-detail-pill.purple {
				background: #7c3aed;
				color: #ffffff;
			}
		</style>

		<div class="container-fluid mt-3 mb-2 px-0">
			<div class="row">
				<!-- Sisa Cuti Tahunan -->
				<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
					<a href="javascript:;" id="btn-show-cutiTahunan" class="text-decoration-none">
						<div class="modern-stat-card stat-card-blue">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="stat-card-label text-primary">Sisa Cuti Tahunan</div>
									<div class="d-flex align-items-center gap-2 mt-1">
										<span class="stat-card-value"><?= $cuti_tahunan ?></span>
										<span class="stat-detail-pill blue ml-2">Detail <i class="bx bx-chevron-right"></i></span>
									</div>
								</div>
								<div class="stat-icon-wrapper stat-icon-blue">
									<i class="bx bx-calendar-check"></i>
								</div>
							</div>
						</div>
					</a>
				</div>

				<!-- Izin pada Jam Kerja -->
				<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
					<a href="javascript:;" id="btn-show-open" class="text-decoration-none">
						<div class="modern-stat-card stat-card-rose">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="stat-card-label" style="color: #e11d48;">Izin pada Jam Kerja</div>
									<div class="d-flex align-items-center gap-2 mt-1">
										<span class="stat-card-value"><?= $izin_jam ?></span>
										<span class="stat-detail-pill rose ml-2">Detail <i class="bx bx-chevron-right"></i></span>
									</div>
								</div>
								<div class="stat-icon-wrapper stat-icon-rose">
									<i class="bx bx-user-x"></i>
								</div>
							</div>
						</div>
					</a>
				</div>

				<!-- Izin dari Pekerjaan -->
				<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
					<a href="javascript:;" id="btn-show-submit" class="text-decoration-none">
						<div class="modern-stat-card stat-card-purple">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="stat-card-label" style="color: #7c3aed;">Izin dari Pekerjaan</div>
									<div class="d-flex align-items-center gap-2 mt-1">
										<span class="stat-card-value"><?= $izin_meinggalkan ?></span>
										<span class="stat-detail-pill purple ml-2">Detail <i class="bx bx-chevron-right"></i></span>
									</div>
								</div>
								<div class="stat-icon-wrapper stat-icon-purple">
									<i class="bx bx-user-pin"></i>
								</div>
							</div>
						</div>
					</a>
				</div>

				<!-- Pengumuman -->
				<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
					<div class="modern-stat-card stat-card-teal">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<div class="stat-card-label" style="color: #0284c7;">Pengumuman</div>
								<div class="d-flex align-items-center gap-2 mt-1">
									<span class="stat-card-value"><?= $pengumuman[0]->total ?></span>
								</div>
							</div>
							<div class="stat-icon-wrapper stat-icon-teal">
								<i class="bx bx-bell"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>



	<?php } ?>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>

<div class="row mb-4">
	<div class="col-12">
		<div class="card dashboard-calendar-panel">
			<div class="card-body">
				<?php
				$calendar_widget_id = 'dashboard-cuti-calendar';
				$calendar_modal_id = 'dashboard-cuti-calendar-modal';
				$calendar_widget_title = 'Kalender Cuti Karyawan';
				$calendar_widget_subtitle = 'Pantau siapa saja yang sedang cuti langsung dari dashboard.';
				$this->load->view('pages/surat/partials/v_calendar_cuti_widget');
				?>
			</div>
		</div>
	</div>
</div>

<div class="row mb-4">
	<div class="col-12">
		<div class="visilab-calendar-widget">
			<div class="calendar-header">
				<div class="calendar-title-wrap">
					<div class="calendar-icon-badge">
						<i class="fas fa-microscope"></i>
					</div>
					<div>
						<h4 class="calendar-title">Kalender Jadwal Visilab</h4>
						<p class="calendar-subtitle">Pantau agenda jadwal Ukes & Upar dari dashboard utama.</p>
					</div>
				</div>
			</div>
			<div id="dashboard-visilab-calendar"></div>
		</div>
	</div>
</div>

<div class="modal fade modern-visilab-modal" id="dashboardVisilabDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="fas fa-calendar-check"></i> Detail Jadwal Visilab</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="visilab-detail-grid">
					<div class="visilab-detail-card">
						<div class="visilab-detail-label">Jenis Jadwal</div>
						<div class="visilab-detail-value text-primary" id="dashVisiJenis">-</div>
					</div>
					<div class="visilab-detail-card">
						<div class="visilab-detail-label">Teknisi</div>
						<div class="visilab-detail-value" id="dashVisiTeknisi">-</div>
					</div>
					<div class="visilab-detail-card">
						<div class="visilab-detail-label">Tanggal</div>
						<div class="visilab-detail-value" id="dashVisiTanggal">-</div>
					</div>
					<div class="visilab-detail-card">
						<div class="visilab-detail-label">Jam</div>
						<div class="visilab-detail-value" id="dashVisiJam">-</div>
					</div>
					<div class="visilab-detail-card">
						<div class="visilab-detail-label">Status</div>
						<div class="visilab-detail-value" id="dashVisiStatus">-</div>
					</div>
					<div class="visilab-detail-card">
						<div class="visilab-detail-label">Wilayah</div>
						<div class="visilab-detail-value" id="dashVisiWilayah">-</div>
					</div>
					<div class="visilab-detail-card full-width">
						<div class="visilab-detail-label">Pelanggan</div>
						<div class="visilab-detail-value" id="dashVisiPelanggan">-</div>
					</div>
					<div class="visilab-detail-card">
						<div class="visilab-detail-label">Provinsi</div>
						<div class="visilab-detail-value" id="dashVisiProvinsi">-</div>
					</div>
					<div class="visilab-detail-card">
						<div class="visilab-detail-label">Kabupaten / Kota</div>
						<div class="visilab-detail-value" id="dashVisiKabKota">-</div>
					</div>
					<div class="visilab-detail-card full-width">
						<div class="visilab-detail-label">Alamat Lengkap</div>
						<div class="visilab-detail-value" id="dashVisiAlamat" style="font-weight: 500; font-size: 12px; line-height: 1.5;">-</div>
					</div>
				</div>
			</div>
			<div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 12px 24px;">
				<button type="button" class="btn btn-secondary btn-sm px-4" data-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6 mb-4">
		<div class="modern-dashboard-widget">
			<div class="widget-header">
				<div class="widget-title-wrap">
					<div class="widget-icon-badge badge-announcement">
						<i class="fas fa-bullhorn"></i>
					</div>
					<div>
						<h4 class="widget-title">Pengumuman Kantor</h4>
						<p class="widget-subtitle">Informasi dan edaran resmi terkini.</p>
					</div>
				</div>
			</div>
			
			<div class="announcement-feed-container">
				<?php if (!empty($announce)) {
					foreach ($announce as $value) { 
						$firstChar = !empty($value->nama) ? strtoupper(substr($value->nama, 0, 1)) : 'A';
						$createdDate = !empty($value->data_created) ? date('d M Y, H:i', strtotime($value->data_created)) : '-';
				?>
						<div class="announcement-item-card">
							<div class="announcement-item-header">
								<div class="announcement-author-info">
									<div class="announcement-avatar"><?= $firstChar ?></div>
									<div>
										<div class="announcement-author-name"><?= htmlspecialchars($value->nama) ?></div>
									</div>
								</div>
								<span class="announcement-date-pill">
									<i class="far fa-clock"></i> <?= $createdDate ?>
								</span>
							</div>
							
							<div class="announcement-item-body">
								<?= nl2br($value->message) ?>
							</div>

							<?php if (!empty($value->lampiran)) { ?>
								<div class="announcement-actions">
									<a href="<?= $value->lampiran ?>" target="_blank" class="btn btn-outline-primary btn-sm btn-pill-action">
										<i class="fas fa-paperclip"></i> Lampiran
									</a>
									<button type="button" class="btn btn-warning btn-sm btn-pill-action btn-preview-gdrive" data-url="<?= $value->lampiran ?>" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
										<i class="fas fa-eye"></i> Preview
									</button>
								</div>
							<?php } ?>
						</div>
				<?php 
					} 
				} else { ?>
					<div class="text-center py-5 text-muted">
						<i class="fas fa-info-circle fa-2x mb-2 text-slate-400"></i>
						<p class="mb-0" style="font-size: 13px;">Belum ada pengumuman terbaru saat ini.</p>
					</div>
				<?php } ?>
			</div>

			<div class="pt-2">
				<a href="<?= base_url('Announcement/daftar') ?>" class="btn-view-all-announcement">
					<i class="fas fa-list-ul mr-1"></i> Lihat Semua Pengumuman
				</a>
			</div>
		</div>
	</div>

	<div class="col-lg-6 mb-4">
		<div class="modern-dashboard-widget">
			<div class="widget-header">
				<div class="widget-title-wrap">
					<div class="widget-icon-badge badge-activity-log">
						<i class="fas fa-history"></i>
					</div>
					<div>
						<h4 class="widget-title">Log Aktivitas Anda</h4>
						<p class="widget-subtitle">Riwayat aktivitas dan tindakan pengguna pada sistem.</p>
					</div>
				</div>
			</div>

			<div class="activity-filter-box">
				<label><i class="fas fa-filter text-primary mr-1"></i> Filter Berdasarkan Bulan:</label>
				<div class="activity-filter-input-wrap">
					<div class="input-group input-group-sm">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fa fa-calendar"></i></span>
						</div>
						<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
					</div>
				</div>
			</div>

			<div class="table-responsive modern-table-wrap">
				<table class="table table-hover table-striped table-sm" id="kt_table_1" style="width: 100%;">
					<thead>
						<tr>
							<th>#</th>
							<th>Pengguna</th>
							<th>Aksi</th>
							<th>Keterangan</th>
							<th>Tanggal</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>


<div id="main-modal-hadir" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Hadir</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
							<th>Waktu</th>
							<th>Jenis Absen</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_hadir as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
								<td><?= $row->waktu_absen ?></td>
								<td><?= $row->jenis_absen ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>
<div id="main-modal-terlambat" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Terlambat</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable6" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
							<th>Waktu</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_terlambat as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
								<td><?= $row->waktu_absen ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>

<div id="main-modal-sakit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Sakit</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable1" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_sakit as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>

<div id="main-modal-cuti" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Cuti</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable2" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_cuti as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>

<div id="main-modal-pulang" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Pulang</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable4" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
							<th>Waktu</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_pulang as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
								<td><?= $row->waktu_absen ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>


<div id="main-modal-istirahat" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Absen Setelah Istirahat</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable5" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
							<th>Waktu</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_istirahat as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
								<td><?= $row->waktu_absen ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>




<div id="main-modal-izin" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Izin</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable3" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_izin as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>

<div id="main-modal-noistirahat" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Tidak Absen Istirahat</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable7" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_noistirahat as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>





<div id="main-modal-open" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Izin Pada Jam Kerja</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTableNew1" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th style="width:35%">Kode</th>
							<th style="width:50%">Keperluan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_izin_jam as $row) {

							$koTik  = '<a href="surat_part_two/show/detail/izin_jam_kerja/' . $row->id . '")>' . $row->kode_ijk . '</a>';

						?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $koTik ?></td>
								<td><?= $row->alasan ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>


<div id="main-modal-submit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Izin Meninggalkan Pekerjaan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTableNew2" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th style="width:35%">Kode</th>
							<th style="width:50%">Keperluan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_izin_meninggalkan as $row) {

							$koTik  = '<a href="surat_part_two/show/detail/izin_meninggalkan/' . $row->id . '")>' . $row->kode_ijk . '</a>';

						?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $koTik ?></td>
								<td><?= $row->alasan ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>


<div id="main-modal-cutiTahunan" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Cuti Tahunan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<h6 class="font-weight-bold mb-0 me-2"><?= $detail_cuti ?></h6><br>
				<table id="myTableNew3" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th style="width:35%">Kode</th>
							<th style="width:50%">Keperluan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_cuti_tahunan as $row) {

							$koTik  = '<a href="surat_part_two/show/detail/cuti/' . $row->id . '")>' . $row->kode_cuti . '</a>';

						?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $koTik ?></td>
								<td><?= $row->alasan ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>

				<?php if (isAdmin() || isHrd()) { ?>
					<hr>
					<h6 class="font-weight-bold mb-3">Rekap Sisa Cuti Seluruh Karyawan Tahun <?= $tahun_berjalan ?></h6>
					<div class="table-responsive">
						<table id="myTableSisaCutiKaryawan" class="table table-striped table-sm table-bordered table-hover">
							<thead>
								<tr>
									<th style="width:5%">#</th>
									<th style="width:20%">Nama</th>
									<th style="width:12%">NPP</th>
									<th style="width:18%">Jabatan</th>
									<th style="width:10%">Jatah Cuti</th>
									<th style="width:10%">Cuti Diambil</th>
									<th style="width:10%">Sisa Cuti</th>
									<th style="width:15%">Kategori Masa Kerja</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$no_rekap = 1;
								$masa_kerja_label = array(
									'A' => 'Di atas 5 tahun',
									'B' => 'Di atas 1 tahun',
									'C' => 'Di bawah 1 tahun',
									'D' => 'Belum kontrak'
								);
								foreach ($rekap_sisa_cuti_karyawan as $rekap) {
									$label_masa_kerja = isset($masa_kerja_label[$rekap->masa_kerja]) ? $masa_kerja_label[$rekap->masa_kerja] : '-';
								?>
									<tr>
										<td><?= $no_rekap++ ?></td>
										<td><?= htmlspecialchars($rekap->nama) ?></td>
										<td><?= htmlspecialchars($rekap->no_pegawai) ?></td>
										<td><?= htmlspecialchars($rekap->jabatan) ?></td>
										<td><?= $rekap->jatah_cuti ?></td>
										<td><?= $rekap->cuti_diambil ?></td>
										<td><?= $rekap->sisa_cuti ?></td>
										<td><?= $label_masa_kerja ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				<?php } ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
	document.addEventListener('DOMContentLoaded', function() {

		$('#myTableNew1').DataTable();
		$('#myTableNew2').DataTable();
		$('#myTableNew3').DataTable();
		if ($('#myTableSisaCutiKaryawan').length) {
			$('#myTableSisaCutiKaryawan').DataTable({
				pageLength: 25,
				order: [
					[1, 'asc']
				]
			});
		}

		$('#btn-show-open').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-open').modal()
		})

		$('#btn-show-submit').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-submit').modal()
		})

		$('#btn-show-cutiTahunan').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-cutiTahunan').modal()
		})




		$('#filter_month').change(function() {
			table.ajax.reload()
		})
		$('#myTable').DataTable();
		$('#myTable1').DataTable();
		$('#myTable2').DataTable();
		$('#myTable3').DataTable();
		$('#myTable4').DataTable();
		$('#myTable5').DataTable();
		$('#myTable6').DataTable();
		$('#myTable7').DataTable();

		table = $('#kt_table_1').DataTable({
			responsive: false,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			scrollY: '50vh',
			scrollX: true,
			scrollCollapse: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'dashboard/pagination_log',
				type: 'POST',
				data: function(e) {
					e.filter_month = $('#filter_month').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0],
				className: 'text-center'
			}]
		})

		$('#btn-show-terlambat').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-terlambat').modal()
		})

		$('#btn-show-istirahat').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-istirahat').modal()
		})

		$('#btn-show-noistirahat').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-noistirahat').modal()
		})

		$('#btn-show-izin').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-izin').modal()
		})

		$('#btn-show-hadir').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-hadir').modal()
		})


		$('#btn-show-sakit').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-sakit').modal()
		})

		$('#btn-show-pulang').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-pulang').modal()
		})


		$('#btn-show-cuti').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-cuti').modal()
		})

		/** * Logika Confetti:
		 * Cek PHP: Apakah ada yang ultah? (total > 0)
		 * Cek Session: Apakah ini login pertama? (show_confetti == TRUE)
		 */
		<?php if ($ulangtahun[0]->total > 0 && $this->session->userdata('show_confetti')) : ?>

			// Jalankan Confetti
			var duration = 3 * 1000; // 3 Detik
			var animationEnd = Date.now() + duration;
			var defaults = {
				startVelocity: 30,
				spread: 360,
				ticks: 60,
				zIndex: 0
			};

			function randomInRange(min, max) {
				return Math.random() * (max - min) + min;
			}

			var interval = setInterval(function() {
				var timeLeft = animationEnd - Date.now();

				if (timeLeft <= 0) {
					return clearInterval(interval);
				}

				var particleCount = 50 * (timeLeft / duration);
				// Menembak dari kiri dan kanan
				confetti(Object.assign({}, defaults, {
					particleCount,
					origin: {
						x: randomInRange(0.1, 0.3),
						y: Math.random() - 0.2
					}
				}));
				confetti(Object.assign({}, defaults, {
					particleCount,
					origin: {
						x: randomInRange(0.7, 0.9),
						y: Math.random() - 0.2
					}
				}));
			}, 250);

			// 2. Hapus flag session agar tidak muncul lagi saat refresh (AJAX atau Simple PHP)
			<?php $this->session->unset_userdata('show_confetti'); ?>

		<?php endif; ?>

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
					$.getJSON('<?= base_url('visilab_jadwal/get_events') ?>', {
							year: viewYear
						})
						.done(function(resp) {
							successCallback(resp);
						})
						.fail(function() {
							failureCallback();
						});
				},
				eventContent: function(arg) {
					var props = arg.event.extendedProps || {};
					var wilayah = props.wilayah || 'Lainnya';
					var badgeColor = props.wilayah_badge_color || '#6c757d';
					var title = arg.event.title || '';
					return {
						html: '<div class="visilab-event-chip"><span class="visilab-badge-tag" style="background:' + badgeColor + ';">' + wilayah + '</span><span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + title + '</span></div>'
					};
				},
				eventClick: function(info) {
					var props = info.event.extendedProps || {};
					var opt = {
						year: 'numeric',
						month: 'long',
						day: 'numeric'
					};
					var tanggalMulai = props.tanggal_mulai || (info.event.startStr || '');
					var tanggalSelesai = props.tanggal_selesai || tanggalMulai;
					var tanggal = '-';
					if (tanggalMulai) {
						var tStart = new Date(tanggalMulai + 'T00:00:00').toLocaleDateString('id-ID', opt);
						var tEnd = new Date(tanggalSelesai + 'T00:00:00').toLocaleDateString('id-ID', opt);
						tanggal = tStart === tEnd ? tStart : (tStart + ' s/d ' + tEnd);
					}
					$('#dashVisiJenis').text(props.jenis_jadwal || '-');
					$('#dashVisiTeknisi').text(props.teknisi_nama || (props.teknisi_id ? ('ID ' + props.teknisi_id) : '-'));
					$('#dashVisiTanggal').text(tanggal);
					$('#dashVisiJam').text(props.jam || '-');
					$('#dashVisiStatus').text(props.status || '-');
					$('#dashVisiPelanggan').text(props.lokasi_pelanggan_nama || '-');
					$('#dashVisiWilayah').text(props.wilayah || '-');
					$('#dashVisiProvinsi').text(props.provinsi || '-');
					$('#dashVisiKabKota').text(props.kab_kota || '-');
					$('#dashVisiAlamat').text(props.lokasi_alamat || '-');
					$('#dashboardVisilabDetailModal').modal('show');
				}
			});
			visilabCalendar.render();
		}
	})
</script>

<!-- Modal Preview Google Drive -->
<div class="modal fade" id="modalPreviewGDrive" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="max-width: 85%;">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center" style="display: flex !important; justify-content: space-between !important; align-items: center !important; width: 100%;">
				<h5 class="modal-title" style="margin: 0;"><i class="fas fa-eye"></i> Preview Lampiran</h5>
				<div class="d-flex align-items-center" style="gap: 15px; display: flex !important; align-items: center !important;">
					<a href="" id="btnOpenGDriveTab" target="_blank" class="btn btn-warning btn-xs text-dark" style="font-weight: 600;"><i class="fas fa-external-link-alt"></i> Buka di Tab Baru</a>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="margin: 0; padding: 0; opacity: 0.8;">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>
			<div class="modal-body p-0" style="height: 75vh; position: relative;">
				<div class="alert alert-info py-2 px-3 m-0 rounded-0" style="font-size: 12px; border: none; border-bottom: 1px solid #bce8f1; background-color: #d9edf7; color: #31708f; margin: 0 !important; border-radius: 0 !important;">
					<i class="fas fa-info-circle"></i> <strong>Tips:</strong> Jika file tidak muncul (masalah hak akses / akun), silakan klik tombol <strong>Buka di Tab Baru</strong> untuk melihat file secara langsung.
				</div>
				<iframe id="iframeGDrivePreview" src="" style="width: 100%; height: calc(100% - 38px); border: none;"></iframe>
			</div>
		</div>
	</div>
</div>

<script>
	function initGDrivePreview() {
		$(document).on('click', '.btn-preview-gdrive', function() {
			var rawUrl = $(this).data('url');
			var previewUrl = rawUrl;

			// Set the href for fallback button
			$('#btnOpenGDriveTab').attr('href', rawUrl);

			if (rawUrl.includes('drive.google.com')) {
				// If it's a folder link
				if (rawUrl.includes('/drive/folders/')) {
					var folderMatch = rawUrl.match(/\/folders\/([a-zA-Z0-9_-]+)/);
					if (folderMatch && folderMatch[1]) {
						previewUrl = "https://drive.google.com/embeddedfolderview?id=" + folderMatch[1] + "#grid";
					}
				} else {
					// File link
					var match = rawUrl.match(/\/file\/d\/([a-zA-Z0-9_-]+)/);
					if (match && match[1]) {
						previewUrl = "https://drive.google.com/file/d/" + match[1] + "/preview";
					} else {
						var matchId = rawUrl.match(/[?&]id=([a-zA-Z0-9_-]+)/);
						if (matchId && matchId[1]) {
							previewUrl = "https://drive.google.com/file/d/" + matchId[1] + "/preview";
						}
					}
				}
			}

			$('#iframeGDrivePreview').attr('src', previewUrl);
			$('#modalPreviewGDrive').modal('show');
		});

		$('#modalPreviewGDrive').on('hidden.bs.modal', function() {
			$('#iframeGDrivePreview').attr('src', '');
			$('#btnOpenGDriveTab').attr('href', '');
		});
	}

	if (typeof jQuery !== 'undefined') {
		initGDrivePreview();
	} else {
		window.addEventListener('load', initGDrivePreview);
	}
</script>