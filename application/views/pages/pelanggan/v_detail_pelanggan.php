<header class="page-header">
	<h2><i class="fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><a href="<?= base_url('pelanggan') ?>"><i class="fas fa-users"></i> Pelanggan</a></li>
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<style>
	/* Container */
	.detail-container {
		max-width: 1400px;
		margin: 0 auto;
		padding: 0 15px;
	}
	
	/* Card Styling */
	.detail-card {
		border: none;
		border-radius: 10px;
		box-shadow: 0 2px 12px rgba(0,0,0,0.08);
		margin-bottom: 20px;
		background: #fff;
		overflow: hidden;
	}
	.detail-card .card-header {
		background: #fff;
		border-bottom: 1px solid #eee;
		padding: 15px 20px;
		font-weight: 600;
		font-size: 1rem;
		display: flex;
		align-items: center;
	}
	.detail-card .card-body {
		padding: 20px;
	}
	
	/* Info Display */
	.info-group {
		margin-bottom: 18px;
	}
	.info-label {
		color: #6c757d;
		font-size: 0.75rem;
		font-weight: 600;
		margin-bottom: 5px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	.info-value {
		font-size: 0.95rem;
		color: #2c3e50;
		padding: 10px 14px;
		background: #f8f9fa;
		border-radius: 6px;
		border-left: 3px solid #2196F3;
		min-height: 42px;
		display: flex;
		align-items: center;
		word-break: break-word;
	}
	.info-value.empty {
		color: #adb5bd;
		font-style: italic;
		border-left-color: #dee2e6;
		background: #fafafa;
	}
	.info-value a {
		color: inherit;
		text-decoration: none;
	}
	.info-value a:hover {
		text-decoration: underline;
	}
	
	/* Status Badges */
	.status-badge {
		padding: 8px 16px;
		border-radius: 20px;
		font-size: 0.85rem;
		font-weight: 600;
		display: inline-flex;
		align-items: center;
		gap: 6px;
	}
	.status-new {
		background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
		color: white;
	}
	.status-existing {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
	}
	.status-pkp {
		background: #28a745;
		color: white;
		padding: 6px 14px;
	}
	.status-nonpkp {
		background: #6c757d;
		color: white;
		padding: 6px 14px;
	}
	
	/* Customer Header */
	.customer-header {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		padding: 25px 30px;
		border-radius: 10px;
		margin-bottom: 25px;
		box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
	}
	.customer-header h3 {
		margin: 0 0 10px 0;
		font-size: 1.5rem;
		font-weight: 600;
	}
	.customer-header .type-badge {
		background: rgba(255,255,255,0.2);
		padding: 5px 12px;
		border-radius: 15px;
		font-size: 0.8rem;
		display: inline-flex;
		align-items: center;
		gap: 5px;
		margin-right: 8px;
		margin-top: 5px;
	}
	
	/* Section Icons */
	.section-icon {
		width: 32px;
		height: 32px;
		border-radius: 8px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		margin-right: 10px;
		font-size: 0.9rem;
	}
	.icon-info { background: #e3f2fd; color: #1976d2; }
	.icon-location { background: #e8f5e9; color: #388e3c; }
	.icon-tax { background: #fff3e0; color: #f57c00; }
	.icon-shipping { background: #fce4ec; color: #c2185b; }
	.icon-other { background: #f3e5f5; color: #7b1fa2; }
	.icon-chart { background: #e0f7fa; color: #00838f; }
	
	/* Link Button */
	.link-btn {
		color: #2196F3;
		text-decoration: none;
		padding: 8px 14px;
		border: 1px solid #2196F3;
		border-radius: 6px;
		display: inline-flex;
		align-items: center;
		gap: 6px;
		transition: all 0.2s;
		font-size: 0.9rem;
	}
	.link-btn:hover {
		background: #2196F3;
		color: white;
		text-decoration: none;
	}
	
	/* Action Buttons */
	.action-buttons {
		margin-bottom: 20px;
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
	}
	.action-buttons .btn {
		display: inline-flex;
		align-items: center;
		gap: 6px;
	}
	
	/* Tabs */
	.nav-tabs {
		border-bottom: 2px solid #e9ecef;
		margin-top: 30px;
	}
	.nav-tabs .nav-link {
		border: none;
		color: #6c757d;
		font-weight: 500;
		padding: 12px 20px;
		border-radius: 0;
		position: relative;
	}
	.nav-tabs .nav-link:hover {
		color: #2196F3;
		background: transparent;
	}
	.nav-tabs .nav-link.active {
		color: #2196F3;
		background: transparent;
		border: none;
	}
	.nav-tabs .nav-link.active::after {
		content: '';
		position: absolute;
		bottom: -2px;
		left: 0;
		right: 0;
		height: 3px;
		background: #2196F3;
		border-radius: 3px 3px 0 0;
	}
	
	/* Tab Content */
	.tab-content-section {
		padding: 25px 0;
	}
	.tab-pane {
		background: #fff;
		border-radius: 0 0 10px 10px;
		padding: 20px;
		box-shadow: 0 2px 8px rgba(0,0,0,0.05);
	}
	
	/* Table */
	.commissioning-table {
		margin: 0;
		border-radius: 8px;
		overflow: hidden;
	}
	.commissioning-table thead th {
		background: #2196F3;
		color: white;
		text-align: center;
		padding: 12px 10px;
		font-weight: 600;
		font-size: 0.85rem;
		border: none;
	}
	.commissioning-table tbody td {
		padding: 12px 10px;
		vertical-align: middle;
		border-color: #eee;
		font-size: 0.9rem;
	}
	.commissioning-table tbody tr:hover {
		background: #f8f9fa;
	}
	
	/* Empty State */
	.empty-state {
		text-align: center;
		padding: 50px 20px;
		color: #6c757d;
	}
	.empty-state i {
		font-size: 3.5rem;
		margin-bottom: 15px;
		opacity: 0.4;
		display: block;
	}
	.empty-state p {
		margin: 0;
		font-size: 1rem;
	}
	
	/* Timeline */
	.timeline-section {
		background: #f8f9fa;
		border-radius: 10px;
		padding: 20px;
	}
	.timeline-3 {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	.timeline-3 li {
		padding: 15px 20px;
		background: #fff;
		border-radius: 8px;
		margin-bottom: 12px;
		border-left: 3px solid #2196F3;
		box-shadow: 0 1px 4px rgba(0,0,0,0.05);
	}
	.timeline-3 li:last-child {
		margin-bottom: 0;
	}
	
	/* Statistik Card */
	.stat-box {
		text-align: center;
		padding: 15px 10px;
	}
	.stat-box .stat-number {
		font-size: 2rem;
		font-weight: 700;
		line-height: 1.2;
	}
	.stat-box .stat-label {
		font-size: 0.8rem;
		color: #6c757d;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	.stat-box.commissioning .stat-number { color: #2196F3; }
	.stat-box.tiket .stat-number { color: #4caf50; }
	
	/* Responsive */
	@media (max-width: 991px) {
		.customer-header h3 {
			font-size: 1.3rem;
		}
		.customer-header .type-badge {
			font-size: 0.75rem;
		}
	}
	@media (max-width: 767px) {
		.customer-header {
			text-align: center;
			padding: 20px;
		}
		.customer-header .text-md-right {
			text-align: center !important;
			margin-top: 15px;
		}
		.info-value {
			font-size: 0.9rem;
		}
	}
</style>

<?php
// Helper untuk menentukan status customer
$currentYear = date('Y');
$tahunRegistrasi = !empty($data_pelanggan[0]->tanggal_registrasi) ? date('Y', strtotime($data_pelanggan[0]->tanggal_registrasi)) : null;
$statusCustomer = ($tahunRegistrasi == $currentYear) ? 'New Customer' : 'Existing Customer';
$statusClass = ($statusCustomer == 'New Customer') ? 'status-new' : 'status-existing';
?>

<!-- Action Buttons -->
<div class="action-buttons">
	<a href="<?= base_url('pelanggan') ?>" class="btn btn-secondary">
		<i class="fas fa-arrow-left"></i> Kembali ke Daftar
	</a>
	<?php if (sessPenggunaId() == 72 || sessPenggunaId() == 69 || sessPenggunaId() == 1 || sessPenggunaId() == 7) { ?>
	<a href="javascript:;" class="btn btn-primary btn-edit-detail" data-id="<?= encrypt($data_pelanggan[0]->id_pelanggan) ?>">
		<i class="fas fa-edit"></i> Edit Data
	</a>
	<?php } ?>
	<button onclick="window.print()" class="btn btn-outline-secondary">
		<i class="fas fa-print"></i> Cetak
	</button>
</div>

<!-- Customer Header -->
<div class="customer-header">
	<div class="row align-items-center">
		<div class="col-md-8">
			<h3><i class="fas fa-building mr-2"></i><?= htmlspecialchars($data_pelanggan[0]->nama) ?></h3>
			<span class="type-badge">
				<i class="fas fa-tag"></i> <?= $data_pelanggan[0]->tipe_bisnis ?? 'Tidak ada tipe' ?>
			</span>
			<?php if (!empty($data_pelanggan[0]->kelas)) { ?>
				<span class="type-badge ml-2">
					<i class="fas fa-star"></i> Kelas <?= $data_pelanggan[0]->kelas ?>
				</span>
			<?php } ?>
		</div>
		<div class="col-md-4 text-md-right">
			<span class="status-badge <?= $statusClass ?>">
				<i class="fas <?= $statusCustomer == 'New Customer' ? 'fa-star' : 'fa-user-check' ?>"></i>
				<?= $statusCustomer ?>
			</span>
		</div>
	</div>
</div>

<div class="row">
	<!-- Left Column -->
	<div class="col-lg-8">
		<!-- Informasi Dasar -->
		<div class="card detail-card">
			<div class="card-header bg-white">
				<span class="section-icon icon-info"><i class="fas fa-info-circle"></i></span>
				Informasi Dasar
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="info-group">
							<div class="info-label">Nama Pelanggan/Instansi</div>
							<div class="info-value"><?= htmlspecialchars($data_pelanggan[0]->nama) ?></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="info-group">
							<div class="info-label">Type Customer</div>
							<div class="info-value <?= empty($data_pelanggan[0]->tipe_bisnis) ? 'empty' : '' ?>">
								<?= $data_pelanggan[0]->tipe_bisnis ?: 'Belum diisi' ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="info-group">
							<div class="info-label">PIC / Contact Person</div>
							<div class="info-value <?= empty($data_pelanggan[0]->cpname) ? 'empty' : '' ?>">
								<?= $data_pelanggan[0]->cpname ?: 'Belum diisi' ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="info-group">
							<div class="info-label">Kontak</div>
							<div class="info-value <?= empty($data_pelanggan[0]->kontak) ? 'empty' : '' ?>">
								<?php if (!empty($data_pelanggan[0]->kontak)) { ?>
									<a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $data_pelanggan[0]->kontak) ?>" target="_blank" class="text-success">
										<i class="fab fa-whatsapp"></i> <?= $data_pelanggan[0]->kontak ?>
									</a>
								<?php } else { echo 'Belum diisi'; } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="info-group">
							<div class="info-label">Email</div>
							<div class="info-value <?= empty($data_pelanggan[0]->email) ? 'empty' : '' ?>">
								<?php if (!empty($data_pelanggan[0]->email)) { ?>
									<a href="mailto:<?= $data_pelanggan[0]->email ?>">
										<i class="fas fa-envelope"></i> <?= $data_pelanggan[0]->email ?>
									</a>
								<?php } else { echo 'Belum diisi'; } ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="info-group">
							<div class="info-label">NIK</div>
							<div class="info-value <?= empty($data_pelanggan[0]->nik) ? 'empty' : '' ?>">
								<?= $data_pelanggan[0]->nik ?: 'Belum diisi' ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="info-group">
							<div class="info-label">Tanggal Registrasi</div>
							<div class="info-value <?= empty($data_pelanggan[0]->tanggal_registrasi) ? 'empty' : '' ?>">
								<?= !empty($data_pelanggan[0]->tanggal_registrasi) ? date('d F Y', strtotime($data_pelanggan[0]->tanggal_registrasi)) : 'Belum diisi' ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="info-group">
							<div class="info-label">Marketing</div>
							<div class="info-value <?= empty($data_pelanggan[0]->marketing) ? 'empty' : '' ?>">
								<?= $data_pelanggan[0]->marketing ?: 'Belum diisi' ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Alamat -->
		<div class="card detail-card">
			<div class="card-header bg-white">
				<span class="section-icon icon-location"><i class="fas fa-map-marker-alt"></i></span>
				Alamat
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="info-group">
							<div class="info-label">Kota</div>
							<div class="info-value <?= empty($data_pelanggan[0]->kota) ? 'empty' : '' ?>">
								<?= $data_pelanggan[0]->kota ?: 'Belum diisi' ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="info-group">
							<div class="info-label">Provinsi</div>
							<div class="info-value <?= empty($data_pelanggan[0]->prov) ? 'empty' : '' ?>">
								<?= $data_pelanggan[0]->prov ?: 'Belum diisi' ?>
							</div>
						</div>
					</div>
				</div>
				<div class="info-group">
					<div class="info-label">Alamat Lengkap</div>
					<div class="info-value <?= empty($data_pelanggan[0]->alamat) ? 'empty' : '' ?>">
						<?= !empty($data_pelanggan[0]->alamat) ? nl2br(htmlspecialchars($data_pelanggan[0]->alamat)) : 'Belum diisi' ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Informasi Pajak -->
		<div class="card detail-card">
			<div class="card-header bg-white">
				<span class="section-icon icon-tax"><i class="fas fa-file-invoice"></i></span>
				Informasi Pajak
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="info-group">
							<div class="info-label">Status Pajak</div>
							<div>
								<?php 
								$statusPajak = $data_pelanggan[0]->status_pajak ?? 'NON PKP';
								$pajakClass = ($statusPajak == 'PKP') ? 'status-pkp' : 'status-nonpkp';
								?>
								<span class="status-badge <?= $pajakClass ?>"><?= $statusPajak ?></span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="info-group">
							<div class="info-label">No NPWP</div>
							<div class="info-value <?= empty($data_pelanggan[0]->npwp) ? 'empty' : '' ?>"><?= $data_pelanggan[0]->npwp ?: 'Belum diisi' ?></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="info-group">
							<div class="info-label">Nama Instansi di NPWP</div>
							<div class="info-value <?= empty($data_pelanggan[0]->nama_npwp) ? 'empty' : '' ?>"><?= $data_pelanggan[0]->nama_npwp ?: 'Belum diisi' ?></div>
						</div>
					</div>
				</div>
				<div class="info-group">
					<div class="info-label">Link NPWP</div>
					<?php if (!empty($data_pelanggan[0]->link_npwp)) { ?>
						<a href="<?= $data_pelanggan[0]->link_npwp ?>" target="_blank" class="link-btn">
							<i class="fas fa-external-link-alt"></i> Buka Dokumen NPWP
						</a>
					<?php } else { ?>
						<div class="info-value empty">Belum diisi</div>
					<?php } ?>
				</div>
			</div>
		</div>

		<!-- Pengiriman & Penagihan -->
		<div class="card detail-card">
			<div class="card-header bg-white">
				<span class="section-icon icon-shipping"><i class="fas fa-shipping-fast"></i></span>
				Pengiriman & Penagihan
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="info-group">
							<div class="info-label">Pengiriman Dokumen</div>
							<div class="info-value">
								<?php 
								$pengirimanDokumen = $data_pelanggan[0]->pengiriman_dokumen ?? 'Softfile';
								$iconPengiriman = ($pengirimanDokumen == 'Hardfile') ? 'fa-file-alt' : 'fa-file-pdf';
								?>
								<i class="fas <?= $iconPengiriman ?>"></i> <?= $pengirimanDokumen ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="info-group">
							<div class="info-label">Jenis Transaksi</div>
							<div class="info-value <?= empty($data_pelanggan[0]->jenis_transaksi) ? 'empty' : '' ?>">
								<?= $data_pelanggan[0]->jenis_transaksi ?: 'Belum diisi' ?>
							</div>
						</div>
					</div>
				</div>
				<div class="info-group">
					<div class="info-label">Alamat Pengiriman Dokumen</div>
					<div class="info-value <?= empty($data_pelanggan[0]->alamat_pengiriman_dokumen) ? 'empty' : '' ?>">
						<?= !empty($data_pelanggan[0]->alamat_pengiriman_dokumen) ? nl2br(htmlspecialchars($data_pelanggan[0]->alamat_pengiriman_dokumen)) : 'Belum diisi' ?>
					</div>
				</div>
				<div class="info-group">
					<div class="info-label">Alamat Penagihan</div>
					<div class="info-value <?= empty($data_pelanggan[0]->alamat_penagihan) ? 'empty' : '' ?>">
						<?= !empty($data_pelanggan[0]->alamat_penagihan) ? nl2br(htmlspecialchars($data_pelanggan[0]->alamat_penagihan)) : 'Belum diisi' ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Right Column -->
	<div class="col-lg-4">
		<!-- Quick Info Card -->
		<div class="card detail-card">
			<div class="card-header bg-white">
				<span class="section-icon icon-other"><i class="fas fa-info"></i></span>
				Info Lainnya
			</div>
			<div class="card-body">
				<div class="info-group">
					<div class="info-label">Kelas RS</div>
					<div class="info-value <?= empty($data_pelanggan[0]->kelas) ? 'empty' : '' ?>">
						<?= $data_pelanggan[0]->kelas ?: 'Belum diisi' ?>
					</div>
				</div>
				<div class="info-group">
					<div class="info-label">KTP</div>
					<div class="info-value <?= empty($data_pelanggan[0]->ktp) ? 'empty' : '' ?>">
						<?= $data_pelanggan[0]->ktp ?: 'Belum diisi' ?>
					</div>
				</div>
				<div class="info-group">
					<div class="info-label">Tanggal Input</div>
					<div class="info-value <?= empty($data_pelanggan[0]->tanggal) ? 'empty' : '' ?>">
						<?= !empty($data_pelanggan[0]->tanggal) ? date('d F Y', strtotime($data_pelanggan[0]->tanggal)) : 'Belum diisi' ?>
					</div>
				</div>
				<div class="info-group">
					<div class="info-label">Status Data</div>
					<div>
						<?php if ($data_pelanggan[0]->status == 1) { ?>
							<span class="badge badge-success" style="padding: 6px 12px;"><i class="fas fa-check"></i> Aktif</span>
						<?php } else { ?>
							<span class="badge badge-danger" style="padding: 6px 12px;"><i class="fas fa-times"></i> Tidak Aktif</span>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Statistik Card -->
		<div class="card detail-card">
			<div class="card-header bg-white">
				<span class="section-icon icon-chart"><i class="fas fa-chart-bar"></i></span>
				Statistik
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-6">
						<div class="stat-box commissioning">
							<div class="stat-number"><?= count($data_com) ?></div>
							<div class="stat-label">Commissioning</div>
						</div>
					</div>
					<div class="col-6">
						<div class="stat-box tiket">
							<div class="stat-number"><?= count($data_tiket) ?></div>
							<div class="stat-label">Tiket</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Tabs untuk Commissioning dan Tiket -->
<div class="card detail-card mt-4">
	<div class="card-header bg-white p-0" style="border-bottom: none;">
		<ul class="nav nav-tabs" id="detailTabs" role="tablist" style="border-bottom: none; margin: 0;">
			<li class="nav-item">
				<a class="nav-link active" id="commissioning-tab" data-toggle="tab" href="#commissioning" role="tab">
					<i class="fas fa-clipboard-list"></i> Data Commissioning (<?= count($data_com) ?>)
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="tiket-tab" data-toggle="tab" href="#tiket" role="tab">
					<i class="fas fa-ticket-alt"></i> Log Tiket (<?= count($data_tiket) ?>)
				</a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content" id="detailTabsContent">
			<!-- Commissioning Tab -->
			<div class="tab-pane fade show active" id="commissioning" role="tabpanel">
				<?php if (sessPenggunaId() == 72 || sessPenggunaId() == 69 || sessPenggunaId() == 1) { ?>
				<div class="mb-3">
					<a href="javascript:;" id="btn-show-add-form" class="btn btn-success">
						<i class="fas fa-plus"></i> Tambah Commissioning
					</a>
				</div>
				<?php } ?>
				
				<?php if (!empty($data_com)) { ?>
				<div class="table-responsive">
					<table class="table table-bordered table-hover commissioning-table">
				<thead>
					<tr>
						<th width="5%">No</th>
						<th width="15%">Marketing</th>
						<th width="20%">Nama Hospital</th>
						<th width="15%">Kode Tiket</th>
						<th width="20%">Model</th>
						<th width="12%">Warranty Start</th>
						<th width="12%">Warranty End</th>
					</tr>
				</thead>
				<tbody>
					<?php $no = 1; foreach ($data_com as $row) { ?>
					<tr>
						<td class="text-center"><?= $no++ ?></td>
						<td><?= htmlspecialchars($row->marketing) ?></td>
						<td><?= htmlspecialchars($row->nama_hospital) ?></td>
						<td class="text-center"><?= htmlspecialchars($row->kode_tiket) ?></td>
						<td><?= htmlspecialchars($row->model) ?></td>
						<td class="text-center"><?= date('d-m-Y', strtotime($row->warranty_start)) ?></td>
						<td class="text-center"><?= date('d-m-Y', strtotime($row->warranty_end)) ?></td>
					</tr>
					<?php } ?>
				</tbody>
					</table>
				</div>
				<?php } else { ?>
				<div class="empty-state">
					<i class="fas fa-clipboard-list"></i>
					<p>Belum ada data commissioning</p>
				</div>
				<?php } ?>
			</div>

			<!-- Tiket Tab -->
			<div class="tab-pane fade" id="tiket" role="tabpanel">
				<?php if (!empty($data_tiket)) { ?>
				<div class="timeline-section">
					<ul class="timeline-3">
						<?php foreach ($data_tiket as $each) {
							$statusTiket = '';
							$statusClass = '';
							switch ($each->status_tiket) {
								case 1: $statusTiket = 'NEW'; $statusClass = 'badge-primary'; break;
								case 2: $statusTiket = 'Answered'; $statusClass = 'badge-info'; break;
								case 3: $statusTiket = 'Revision'; $statusClass = 'badge-warning'; break;
								case 4: $statusTiket = 'Selesai'; $statusClass = 'badge-success'; break;
								case 5: $statusTiket = 'Tidak Selesai'; $statusClass = 'badge-danger'; break;
							}
							$idTiket = encrypt($each->id_tiket);
						?>
						<li>
							<div class="d-flex justify-content-between align-items-center mb-2">
								<a href="<?= base_url('tiket/show/detail_tiket/' . $idTiket) ?>" class="font-weight-bold text-primary">
									<?= $each->kode_tiket ?>
								</a>
								<span class="badge <?= $statusClass ?>"><?= $statusTiket ?></span>
							</div>
							<p class="mb-1"><?= htmlspecialchars($each->subject ?? '') ?></p>
							<small class="text-muted">
								<i class="fas fa-calendar"></i> <?= date('d M Y H:i', strtotime($each->created_at ?? '')) ?>
							</small>
						</li>
						<?php } ?>
					</ul>
				</div>
				<?php } else { ?>
				<div class="empty-state">
					<i class="fas fa-ticket-alt"></i>
					<p>Belum ada data tiket</p>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<!-- Modal Commissioning (from old template) -->
<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success text-light">
				<h5><i class="fas fa-clipboard-list"></i> Form Commissioning</h5>
				<button type="button" class="close text-white" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-com', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<input type="hidden" name="id_pel" value="<?= $data_pelanggan[0]->id_pelanggan ?>">
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Marketing <span class="text-danger">*</span></label>
							<select class="form-control" name="marketing" required>
								<option value="">- Pilih Marketing -</option>
								<?php foreach ($nama_marketing as $row) { ?>
									<option value="<?= $row->nama ?>"><?= $row->nama ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Kode Tiket</label>
							<input type="text" class="form-control" name="kode_tiket">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label>Pilih Data Pelanggan</label>
					<select class="form-control" name="tipe_bisnis" id="pihak_selector">
						<option value="1">Data dari Pelanggan Ini</option>
						<option value="2">Input Data Baru</option>
					</select>
				</div>

				<div id="data_baru" style="display:none;">
					<div class="form-group">
						<label>Nama Hospital</label>
						<input type="text" class="form-control" name="nama_hospital">
					</div>
					<div class="form-group">
						<label>Alamat</label>
						<textarea class="form-control" name="address" rows="2"></textarea>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Kota</label>
								<select class="form-control" name="kota">
									<option value="">- Pilih Kota -</option>
									<?php foreach ($list_kota as $row) { ?>
										<option value="<?= $row->nama ?>"><?= $row->nama ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Provinsi</label>
								<select class="form-control" name="provinsi">
									<option value="">- Pilih Provinsi -</option>
									<?php foreach ($list_prov as $row) { ?>
										<option value="<?= $row->nama ?>"><?= $row->nama ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
				</div>

				<hr>
				<h6 class="text-primary"><i class="fas fa-tools"></i> Informasi Peralatan</h6>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Warranty Start</label>
							<input type="text" class="form-control" name="warranty_start" data-plugin-datepicker data-plugin-options='{"format": "dd-mm-yyyy"}'>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Warranty End</label>
							<input type="text" class="form-control" name="warranty_end" data-plugin-datepicker data-plugin-options='{"format": "dd-mm-yyyy"}'>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Serial Number</label>
							<input type="text" class="form-control" name="serial_number">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Manufacturer</label>
							<input type="text" class="form-control" name="manufacturer">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Model</label>
							<input type="text" class="form-control" name="model">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Equipment</label>
							<input type="text" class="form-control" name="equipment">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Merk</label>
							<input type="text" class="form-control" name="merk">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Qty</label>
							<input type="number" class="form-control" name="qty" value="1">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label>Engineer</label>
					<select class="form-control" name="engineer">
						<option value="">- Pilih Engineer -</option>
						<?php foreach ($pengguna as $row) { ?>
							<option value="<?= $row->nama ?>"><?= $row->nama ?></option>
						<?php } ?>
					</select>
				</div>

				<hr>
				<h6 class="text-primary"><i class="fas fa-link"></i> Link Dokumen</h6>

				<div class="form-group">
					<label>Link Invoice</label>
					<input type="url" class="form-control" name="link_invoice" placeholder="https://...">
				</div>
				<div class="form-group">
					<label>Link Service Report</label>
					<input type="url" class="form-control" name="link_service_report" placeholder="https://...">
				</div>
				<div class="form-group">
					<label>Link Commissioning</label>
					<input type="url" class="form-control" name="link_commisioning" placeholder="https://...">
				</div>
				<div class="form-group">
					<label>Link Kepuasan Pelanggan</label>
					<input type="url" class="form-control" name="link_kepuasan_pelanggan" placeholder="https://...">
				</div>
				<div class="form-group">
					<label>Link Dokumentasi</label>
					<input type="url" class="form-control" name="link_dokumentasi" placeholder="https://...">
				</div>
				<div class="form-group">
					<label>Link Garansi VYM</label>
					<input type="url" class="form-control" name="link_garansi_vym" placeholder="https://...">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save-com"><i class="fas fa-save"></i> Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Show/hide data baru fields
	$('#pihak_selector').change(function() {
		if ($(this).val() == '2') {
			$('#data_baru').slideDown();
		} else {
			$('#data_baru').slideUp();
		}
	});

	// Show add commissioning modal
	$('#btn-show-add-form').click(function() {
		$('#modal-form-com')[0].reset();
		$('#main-modal').modal('show');
	});

	// Save commissioning
	$('.btn-save-com').click(function() {
		var form = $('#modal-form-com');
		$.ajax({
			url: '<?= base_url('pelanggan/addCommisioning') ?>',
			type: 'POST',
			data: form.serialize(),
			dataType: 'json',
			success: function(response) {
				if (response.status == 'success') {
					alert(response.message);
					location.reload();
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alert('Terjadi kesalahan. Silakan coba lagi.');
			}
		});
	});

	// Edit button from detail page
	$('.btn-edit-detail').click(function() {
		var id = $(this).data('id');
		window.location.href = '<?= base_url('pelanggan') ?>?edit=' + id;
	});
});
</script>
