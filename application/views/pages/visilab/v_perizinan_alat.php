<header class="page-header">
	<h2><i class="fas fa-file-contract"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span>Visilab</span></li>
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<style>
	:root {
		--card-radius: 12px;
		--text-dark: #1e293b;
		--text-muted: #64748b;
		--border-color: #e2e8f0;
	}

	.card-modern {
		border: 0 !important;
		border-radius: var(--card-radius) !important;
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
		background: #fff !important;
		margin-bottom: 24px;
	}

	/* KPI Cards */
	.kpi-card-modern {
		border: 0 !important;
		border-radius: var(--card-radius) !important;
		background: #fff !important;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04) !important;
		padding: 1.1rem 1.25rem !important;
		transition: all 0.25s ease !important;
		position: relative;
		overflow: hidden;
		border: 1px solid #edf2f7 !important;
	}
	.kpi-card-modern:hover {
		transform: translateY(-3px) !important;
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.09) !important;
	}
	.kpi-card-modern::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		bottom: 0;
		width: 4px;
	}
	.kpi-blue::before { background: linear-gradient(180deg, #3b82f6, #1d4ed8); }
	.kpi-amber::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
	.kpi-cyan::before { background: linear-gradient(180deg, #06b6d4, #0891b2); }
	.kpi-slate::before { background: linear-gradient(180deg, #64748b, #475569); }
	.kpi-purple::before { background: linear-gradient(180deg, #8b5cf6, #6d28d9); }
	.kpi-red::before { background: linear-gradient(180deg, #ef4444, #b91c1c); }

	.kpi-icon-wrapper {
		width: 44px;
		height: 44px;
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.2rem;
		flex-shrink: 0;
	}
	.kpi-blue .kpi-icon-wrapper { background: #eff6ff; color: #2563eb; }
	.kpi-amber .kpi-icon-wrapper { background: #fffbeb; color: #d97706; }
	.kpi-cyan .kpi-icon-wrapper { background: #ecfeff; color: #0891b2; }
	.kpi-slate .kpi-icon-wrapper { background: #f1f5f9; color: #475569; }
	.kpi-purple .kpi-icon-wrapper { background: #f5f3ff; color: #7c3aed; }
	.kpi-red .kpi-icon-wrapper { background: #fef2f2; color: #dc2626; }

	.kpi-title {
		font-size: 0.72rem;
		text-transform: uppercase;
		letter-spacing: 0.6px;
		color: var(--text-muted);
		font-weight: 700;
		margin-bottom: 2px;
	}
	.kpi-value {
		font-size: 1.6rem;
		font-weight: 800;
		color: var(--text-dark);
		line-height: 1.2;
	}

	/* Table Modern */
	.table-modern {
		width: 100% !important;
		border-collapse: separate !important;
		border-spacing: 0 !important;
	}
	.table-modern thead th {
		background: #f8fafc !important;
		color: #475569 !important;
		font-size: 0.74rem !important;
		text-transform: uppercase !important;
		letter-spacing: 0.6px !important;
		font-weight: 700 !important;
		padding: 12px 10px !important;
		border-top: 0 !important;
		border-bottom: 1px solid #e2e8f0 !important;
		vertical-align: middle !important;
	}
	.table-modern tbody td {
		padding: 10px 10px !important;
		vertical-align: middle !important;
		border-bottom: 1px solid #f1f5f9 !important;
		font-size: 0.84rem !important;
		color: #1e293b !important;
	}
	.table-modern tbody tr:hover td {
		background-color: #f8fafc !important;
	}

	/* Compact Kepatuhan Item */
	.kepatuhan-row {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 2.5px 0;
		font-size: 11px;
		border-bottom: 1px dashed #e2e8f0;
	}
	.kepatuhan-row:last-child {
		border-bottom: none;
	}

	/* DataTables Controls Modernization */
	.dataTables_wrapper .dataTables_length {
		float: left;
		margin-bottom: 14px;
	}
	.dataTables_wrapper .dataTables_length label {
		font-size: 12.5px;
		color: #64748b;
		font-weight: 500;
		display: inline-flex;
		align-items: center;
		gap: 6px;
	}
	.dataTables_wrapper .dataTables_length select {
		width: 68px !important;
		height: 32px !important;
		padding: 2px 8px !important;
		font-size: 12.5px !important;
		border-radius: 8px !important;
		border: 1.5px solid #cbd5e1 !important;
		background-color: #fff !important;
		color: #1e293b !important;
		outline: none !important;
	}
	.dataTables_wrapper .dataTables_filter {
		float: right;
		margin-bottom: 14px;
	}
	.dataTables_wrapper .dataTables_filter label {
		font-size: 12.5px;
		color: #64748b;
		font-weight: 500;
		display: inline-flex;
		align-items: center;
		gap: 6px;
	}
	.dataTables_wrapper .dataTables_filter input {
		height: 32px !important;
		width: 220px !important;
		padding: 4px 12px !important;
		font-size: 12.5px !important;
		border-radius: 8px !important;
		border: 1.5px solid #cbd5e1 !important;
		background-color: #fff !important;
		outline: none !important;
		transition: all 0.2s ease !important;
	}
	.dataTables_wrapper .dataTables_filter input:focus {
		border-color: #3b82f6 !important;
		box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
	}
	.dataTables_wrapper .dataTables_paginate {
		margin-top: 14px !important;
		font-size: 12px !important;
	}
	.dataTables_wrapper .dataTables_info {
		margin-top: 14px !important;
		font-size: 12px !important;
		color: #64748b !important;
	}
</style>

<!-- Top KPI Summary Cards -->
<div class="row mb-3">
	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm kpi-card-modern kpi-blue h-100">
			<div class="d-flex align-items-center justify-content-between">
				<div>
					<div class="kpi-title"><i class="fas fa-hospital mr-1"></i> Total Instansi</div>
					<div class="kpi-value text-primary" id="stat-total"><?= $stats['total'] ?></div>
				</div>
				<div class="kpi-icon-wrapper">
					<i class="fas fa-hospital"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm kpi-card-modern kpi-amber h-100">
			<div class="d-flex align-items-center justify-content-between">
				<div>
					<div class="kpi-title"><i class="fas fa-file-signature mr-1"></i> Izin &le; 45 Hari</div>
					<div class="kpi-value text-warning" id="stat-h45-izin"><?= $stats['h45_izin'] ?></div>
				</div>
				<div class="kpi-icon-wrapper">
					<i class="fas fa-hourglass-half"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm kpi-card-modern kpi-cyan h-100">
			<div class="d-flex align-items-center justify-content-between">
				<div>
					<div class="kpi-title"><i class="fas fa-clipboard-check mr-1"></i> LUK &le; 1 Bln</div>
					<div class="kpi-value text-info" id="stat-h30-luk"><?= $stats['h30_luk'] ?></div>
				</div>
				<div class="kpi-icon-wrapper">
					<i class="fas fa-clipboard-check"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm kpi-card-modern kpi-slate h-100">
			<div class="d-flex align-items-center justify-content-between">
				<div>
					<div class="kpi-title"><i class="fas fa-cogs mr-1"></i> Kalibrasi &le; 1 Bln</div>
					<div class="kpi-value text-secondary" id="stat-h30-kal"><?= $stats['h30_kalibrasi'] ?></div>
				</div>
				<div class="kpi-icon-wrapper">
					<i class="fas fa-cogs"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm kpi-card-modern kpi-purple h-100">
			<div class="d-flex align-items-center justify-content-between">
				<div>
					<div class="kpi-title"><i class="fas fa-id-badge mr-1"></i> TLD (3 Bln)</div>
					<div class="kpi-value" style="color: #7c3aed;" id="stat-h30-tld"><?= $stats['h30_tld'] ?></div>
				</div>
				<div class="kpi-icon-wrapper">
					<i class="fas fa-id-badge"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm kpi-card-modern kpi-red h-100">
			<div class="d-flex align-items-center justify-content-between">
				<div>
					<div class="kpi-title text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Expired</div>
					<div class="kpi-value text-danger" id="stat-expired"><?= $stats['expired'] ?></div>
				</div>
				<div class="kpi-icon-wrapper">
					<i class="fas fa-exclamation-triangle"></i>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<section class="card card-modern shadow-sm border-0">
			<header class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
				<div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
					<button type="button" class="btn btn-sm btn-primary font-weight-bold shadow-sm" id="btn-tambah" style="border-radius: 8px;">
						<i class="fas fa-plus-circle mr-1"></i> Tambah Data
					</button>
					<button type="button" class="btn btn-sm btn-outline-info font-weight-bold" id="btn-import" style="border-radius: 8px;">
						<i class="fas fa-file-import mr-1"></i> Import Excel
					</button>
					<button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold" id="btn-export" style="border-radius: 8px;">
						<i class="fas fa-file-excel mr-1"></i> Export Excel
					</button>
					<button type="button" class="btn btn-sm btn-warning font-weight-bold text-dark shadow-sm" id="btn-cron-reminder" style="border-radius: 8px;" title="Kirim reminder ke customer & marketing">
						<i class="fab fa-whatsapp mr-1"></i> Cek & Kirim Semua Pengingat
					</button>
				</div>
				<div>
					<button type="button" class="btn btn-sm btn-light border font-weight-bold" id="btn-refresh" style="border-radius: 8px;" title="Muat Ulang Data">
						<i class="fas fa-sync-alt mr-1 text-primary"></i> Refresh
					</button>
				</div>
			</header>

			<div class="card-body p-3">
				<!-- Filter Bar Modern -->
				<div class="p-3 mb-3 rounded border" style="background: #f8fafc; border-color: #e2e8f0 !important;">
					<div class="row align-items-center">
						<div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
							<label class="small font-weight-bold text-muted text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Status Jasa</label>
							<select id="filter_status_jasa" class="form-control form-control-sm" style="border-radius: 8px;">
								<option value="">-- Semua Status Jasa --</option>
								<option value="Pengurusan Perizinan">Pengurusan Perizinan</option>
								<option value="Pendampingan Perizinan">Pendampingan Perizinan</option>
								<option value="Tidak Termasuk Perizinan">Tidak Termasuk Perizinan</option>
							</select>
						</div>

						<div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
							<label class="small font-weight-bold text-muted text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Status Perizinan</label>
							<select id="filter_status_perizinan" class="form-control form-control-sm" style="border-radius: 8px;">
								<option value="">-- Semua Status Izin --</option>
								<option value="Menunggu Info Customer">Menunggu Info Customer</option>
								<option value="On Progress">On Progress</option>
								<option value="Selesai">Selesai</option>
								<option value="-">-</option>
							</select>
						</div>

						<div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
							<label class="small font-weight-bold text-muted text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Marketing</label>
							<select id="filter_marketing" class="form-control form-control-sm" style="border-radius: 8px;">
								<option value="">-- Semua Marketing --</option>
								<?php foreach ($marketing_list as $m) : ?>
									<option value="<?= htmlspecialchars($m->nama) ?>"><?= htmlspecialchars($m->nama) ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
							<label class="small font-weight-bold text-muted text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Masa Berlaku / Jatuh Tempo</label>
							<select id="filter_masa_berlaku" class="form-control form-control-sm" style="border-radius: 8px;">
								<option value="">-- Semua Kewajiban --</option>
								<option value="h45_izin">âš ï¸ Izin Alat &le; 45 Hari</option>
								<option value="h30_luk">ðŸ“‹ LUK (Ukes) &le; 1 Bulan</option>
								<option value="h30_kalibrasi">âš™ï¸ Kalibrasi &le; 1 Bulan</option>
								<option value="h30_tld">ðŸ©» TLD (3 Bln) &le; 1 Bulan</option>
								<option value="expired">ðŸš¨ Ada yang Expired</option>
							</select>
						</div>
					</div>
				</div>

				<!-- Tabel DataTables Modern -->
				<div class="table-responsive">
					<table class="table table-modern table-hover" id="table-perizinan" style="width:100%;">
						<thead>
							<tr>
								<th style="width: 3%; text-align: center;">No</th>
								<th style="width: 17%; text-align: left;">Instansi & Kontak</th>
								<th style="width: 11%; text-align: left;">Pihak Ketiga</th>
								<th style="width: 10%; text-align: center;">Jenis Alat</th>
								<th style="width: 10%; text-align: center;">Status Jasa</th>
								<th style="width: 9%; text-align: center;">Status Izin</th>
								<th style="width: 14%; text-align: left;">Progres Izin</th>
								<th style="width: 15%; text-align: left;">Jadwal Kepatuhan</th>
								<th style="width: 9%; text-align: center;">Marketing</th>
								<th style="width: 12%; text-align: center;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<!-- Diisi via AJAX DataTables -->
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>

<!-- ======================================================== -->
<!-- MODAL 1: FORM TAMBAH / EDIT PERIZINAN LENGKAP -->
<!-- ======================================================== -->
<div id="modal-form-perizinan" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title" id="modal-form-title"><i class="fas fa-file-contract"></i>&nbsp; Form Data Perizinan & Kepatuhan Alat</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('#', array('id' => 'form-perizinan', 'autocomplete' => 'off')); ?>
			<input type="hidden" name="id" id="id_perizinan" value="">
			<div class="modal-body py-3">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Nama Instansi <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control form-control-sm" id="nama_instansi" name="nama_instansi" required placeholder="Contoh: RSUD Bangil / Klinik Brawijaya">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Pihak Ketiga :</label>
							<input type="text" class="form-control form-control-sm" id="pihak_ketiga" name="pihak_ketiga" placeholder="Contoh: PT UNIOMED MEDICAL / -">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Jenis Alat <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control form-control-sm" id="jenis_alat" name="jenis_alat" list="list_jenis_alat" required placeholder="Contoh: Alerio Smart 4000">
							<datalist id="list_jenis_alat">
								<option value="Alerio Smart 4000">
								<option value="Alerio Smart 1600">
								<option value="Alerio Smart 2000">
								<option value="Alerio Smart 3000">
								<option value="X-Ray Mobile">
								<option value="C-Arm">
							</datalist>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Status Jasa <span class="text-danger">*</span> :</label>
							<select class="form-control form-control-sm" id="status_jasa" name="status_jasa" required>
								<option value="Pengurusan Perizinan">Pengurusan Perizinan</option>
								<option value="Pendampingan Perizinan">Pendampingan Perizinan</option>
								<option value="Tidak Termasuk Perizinan">Tidak Termasuk Perizinan</option>
							</select>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Status Perizinan :</label>
							<select class="form-control form-control-sm" id="status_perizinan" name="status_perizinan">
								<option value="-">-</option>
								<option value="Menunggu Info Customer">Menunggu Info Customer</option>
								<option value="On Progress">On Progress</option>
								<option value="Selesai">Selesai</option>
							</select>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Catatan Tambahan :</label>
							<input type="text" class="form-control form-control-sm" id="catatan" name="catatan" placeholder="Catatan internal bila ada">
						</div>
					</div>
				</div>

				<div class="form-group mb-2">
					<label class="form-control-label font-weight-bold">Progres Perizinan :</label>
					<textarea class="form-control form-control-sm" id="progres_perizinan" name="progres_perizinan" rows="2" placeholder="Catatan perkembangan izin (contoh: Dokumen sudah sesuai, menunggu SIB PPR, dll.)"></textarea>
				</div>

				<!-- Bagian Jadwal Kepatuhan & Masa Berlaku -->
				<hr class="my-2">
				<div class="h6 font-weight-bold text-danger mb-2"><i class="fas fa-calendar-check"></i> Tanggal Masa Berlaku & Siklus Kepatuhan (Untuk Notifikasi Pengingat)</div>

				<div class="row bg-light py-2 rounded border mx-0 mb-3">
					<div class="col-md-6 mb-2">
						<label class="font-weight-bold small mb-1">
							1. Exp Izin Alat / Batas Evaluasi (H-45 Hari):
						</label>
						<input type="date" class="form-control form-control-sm" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo">
						<small class="text-muted">Diingatkan 45 hari sebelumnya untuk perpanjangan izin.</small>
					</div>

					<div class="col-md-6 mb-2">
						<label class="font-weight-bold small mb-1">
							2. Exp LUK / Uji Kesesuaian (H-1 Bulan):
						</label>
						<input type="date" class="form-control form-control-sm" id="tgl_exp_luk" name="tgl_exp_luk">
						<small class="text-muted">Diingatkan 1 bulan sebelumnya untuk uji kesesuaian (UKES) ulang.</small>
					</div>

					<div class="col-md-6 mb-2">
						<label class="font-weight-bold small mb-1">
							3. Exp Kalibrasi Alat (H-1 Bulan):
						</label>
						<input type="date" class="form-control form-control-sm" id="tgl_exp_kalibrasi" name="tgl_exp_kalibrasi">
						<small class="text-muted">Diingatkan 1 bulan sebelumnya untuk kalibrasi ulang alat.</small>
					</div>

					<div class="col-md-6 mb-2">
						<label class="font-weight-bold small mb-1">
							4. Evaluasi TLD Badge - Triwulanan (H-1 Bulan):
						</label>
						<input type="date" class="form-control form-control-sm" id="tgl_evaluasi_tld" name="tgl_evaluasi_tld">
						<small class="text-muted">Siklus per 3 bulan. Diingatkan 1 bulan sebelumnya.</small>
					</div>
				</div>

				<!-- Bagian Kontak & Marketing -->
				<div class="h6 font-weight-bold text-primary mb-2"><i class="fas fa-users-cog"></i> Kontak & Penanggung Jawab</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Marketing Penanggung Jawab :</label>
							<select class="form-control form-control-sm" id="marketing_nama" name="marketing_nama">
								<option value="">-- Pilih Marketing --</option>
								<option value="Muhammad Tamrin" data-nohp="081261457547">Muhammad Tamrin</option>
								<option value="Nurdiansyah" data-nohp="">Nurdiansyah</option>
								<option value="Yolanda Pratiwi" data-nohp="">Yolanda Pratiwi</option>
								<option value="Buldani" data-nohp="">Buldani</option>
								<option value="Office" data-nohp="">Office</option>
								<?php foreach ($marketing_list as $m) : ?>
									<option value="<?= htmlspecialchars($m->nama) ?>" data-nohp="<?= htmlspecialchars($m->no_hp) ?>"><?= htmlspecialchars($m->nama) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">No. WhatsApp Marketing :</label>
							<input type="text" class="form-control form-control-sm" id="marketing_nohp" name="marketing_nohp" placeholder="08xxxxxxxxxx">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Nama PIC Instansi / Customer :</label>
							<input type="text" class="form-control form-control-sm" id="pic_instansi" name="pic_instansi" placeholder="Nama PIC Rumah Sakit / Klinik">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">No. WhatsApp PIC Customer (Untuk Reminder) :</label>
							<input type="text" class="form-control form-control-sm" id="kontak_pic" name="kontak_pic" placeholder="08xxxxxxxxxx">
						</div>
					</div>
				</div>

				<div class="form-group mb-0 bg-light p-2 rounded border">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="kirim_notif" name="kirim_notif" value="1" checked>
						<label class="custom-control-label font-weight-bold text-dark" for="kirim_notif">
							<i class="fab fa-whatsapp text-success"></i> Kirim Notifikasi WhatsApp ke Marketing & Grup VISILAB jika progres/status diperbarui
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-success btn-sm" id="btn-save-perizinan">
					<i class="fas fa-save"></i>&nbsp; Simpan Data
				</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- ======================================================== -->
<!-- MODAL 2: KIRIM NOTIFIKASI MANUAL WHATSAPP (LENGKAP) -->
<!-- ======================================================== -->
<div id="modal-notif-manual" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success text-white">
				<h5 class="modal-title"><i class="fab fa-whatsapp"></i>&nbsp; Kirim Notifikasi WhatsApp Manual</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('#', array('id' => 'form-notif-manual')); ?>
			<input type="hidden" name="id" id="manual_id" value="">
			<div class="modal-body py-3">
				<!-- Header Info Instansi -->
				<div class="alert alert-light border py-2 mb-3">
					<div class="h6 font-weight-bold text-primary mb-0" id="manual_info_instansi">-</div>
					<div class="small text-muted" id="manual_info_sub">-</div>
				</div>

				<!-- Pilih Jenis Notifikasi -->
				<div class="form-group mb-3">
					<label class="form-control-label font-weight-bold">Pilih Jenis Notifikasi yang Mau Dikirim <span class="text-danger">*</span> :</label>
					<div class="row">
						<div class="col-md-6 mb-1">
							<div class="custom-control custom-radio">
								<input type="radio" id="notif_type_izin" name="jenis_notif" value="izin_h45" class="custom-control-input" checked>
								<label class="custom-control-label font-weight-bold" for="notif_type_izin">
									⚠️ Reminder Perizinan Alat (H-45 Hari)
								</label>
							</div>
						</div>
						<div class="col-md-6 mb-1">
							<div class="custom-control custom-radio">
								<input type="radio" id="notif_type_luk" name="jenis_notif" value="luk_1bln" class="custom-control-input">
								<label class="custom-control-label font-weight-bold" for="notif_type_luk">
									📋 Reminder LUK / Ukes (H-1 Bulan)
								</label>
							</div>
						</div>
						<div class="col-md-6 mb-1">
							<div class="custom-control custom-radio">
								<input type="radio" id="notif_type_kalibrasi" name="jenis_notif" value="kalibrasi_1bln" class="custom-control-input">
								<label class="custom-control-label font-weight-bold" for="notif_type_kalibrasi">
									⚙️ Reminder Kalibrasi Alat (H-1 Bulan)
								</label>
							</div>
						</div>
						<div class="col-md-6 mb-1">
							<div class="custom-control custom-radio">
								<input type="radio" id="notif_type_tld" name="jenis_notif" value="tld_1bln" class="custom-control-input">
								<label class="custom-control-label font-weight-bold" for="notif_type_tld">
									🩻 Reminder Evaluasi TLD (3 Bulan, H-1 Bulan)
								</label>
							</div>
						</div>
						<div class="col-md-12 mb-1 mt-1">
							<div class="custom-control custom-radio">
								<input type="radio" id="notif_type_progres" name="jenis_notif" value="progres" class="custom-control-input">
								<label class="custom-control-label font-weight-bold text-info" for="notif_type_progres">
									📝 Update Progres Perizinan (Kirim ke Marketing & Grup VISILAB)
								</label>
							</div>
						</div>
					</div>
				</div>

				<!-- Pilihan Penerima & Nomor HP -->
				<div class="card card-sm bg-light mb-3">
					<div class="card-body p-3">
						<div class="font-weight-bold small text-uppercase mb-2 text-dark">Target Penerima WhatsApp:</div>
						<div class="row">
							<div class="col-md-6 mb-2">
								<div class="custom-control custom-checkbox mb-1">
									<input type="checkbox" class="custom-control-input" id="send_customer" name="send_customer" value="1" checked>
									<label class="custom-control-label font-weight-bold" for="send_customer">
										Kirim ke PIC Instansi / Customer
									</label>
								</div>
								<input type="text" class="form-control form-control-sm" id="manual_kontak_pic" name="kontak_pic" placeholder="No. WA PIC Customer (08xxx)">
							</div>

							<div class="col-md-6 mb-2">
								<div class="custom-control custom-checkbox mb-1">
									<input type="checkbox" class="custom-control-input" id="send_marketing" name="send_marketing" value="1" checked>
									<label class="custom-control-label font-weight-bold" for="send_marketing">
										Kirim ke Marketing Penanggung Jawab
									</label>
								</div>
								<input type="text" class="form-control form-control-sm" id="manual_marketing_nohp" name="marketing_nohp" placeholder="No. WA Marketing (08xxx)">
							</div>

							<div class="col-md-12" id="box_send_visilab" style="display: none;">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="send_visilab" name="send_visilab" value="1" checked>
									<label class="custom-control-label font-weight-bold text-primary" for="send_visilab">
										<i class="fas fa-users"></i> Teruskan ke WhatsApp Grup VISILAB
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Preview & Edit Teks Pesan WhatsApp -->
				<div class="form-group mb-2">
					<label class="form-control-label font-weight-bold">
						<i class="fas fa-eye"></i> Preview Pesan WhatsApp (Bisa diedit bila perlu sebelum dikirim):
					</label>
					<div class="nav nav-tabs mb-2" id="preview-tab" role="tablist">
						<a class="nav-item nav-link active py-1 px-3 small font-weight-bold" id="tab-cust" data-toggle="tab" href="#content-cust" role="tab">Pesan ke Customer</a>
						<a class="nav-item nav-link py-1 px-3 small font-weight-bold" id="tab-mkt" data-toggle="tab" href="#content-mkt" role="tab">Pesan ke Marketing & Grup</a>
					</div>
					<div class="tab-content" id="preview-tabContent">
						<div class="tab-pane fade show active" id="content-cust" role="tabpanel">
							<textarea class="form-control form-control-sm" id="manual_pesan_customer" name="pesan_customer" rows="8" style="font-family: monospace; font-size: 11px;"></textarea>
						</div>
						<div class="tab-pane fade" id="content-mkt" role="tabpanel">
							<textarea class="form-control form-control-sm" id="manual_pesan_marketing" name="pesan_marketing" rows="8" style="font-family: monospace; font-size: 11px;"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-success btn-sm" id="btn-submit-manual-notif">
					<i class="fab fa-whatsapp"></i>&nbsp; Kirim Sekarang (WhatsApp)
				</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- ======================================================== -->
<!-- MODAL 3: QUICK UPDATE PROGRES PERIZINAN -->
<!-- ======================================================== -->
<div id="modal-quick-progres" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title"><i class="fas fa-pencil-alt"></i>&nbsp; Update Cepat Progres Perizinan</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('#', array('id' => 'form-quick-progres', 'autocomplete' => 'off')); ?>
			<input type="hidden" name="id" id="quick_id" value="">
			<div class="modal-body py-3">
				<div class="alert alert-info py-2 mb-3">
					<div class="font-weight-bold" id="quick_info_instansi">-</div>
					<div class="small" id="quick_info_alat">-</div>
				</div>

				<div class="form-group mb-2">
					<label class="form-control-label font-weight-bold">Status Perizinan <span class="text-danger">*</span> :</label>
					<select class="form-control form-control-sm" id="quick_status" name="status_perizinan" required>
						<option value="-">-</option>
						<option value="Menunggu Info Customer">Menunggu Info Customer</option>
						<option value="On Progress">On Progress</option>
						<option value="Selesai">Selesai</option>
					</select>
				</div>

				<div class="form-group mb-2">
					<label class="form-control-label font-weight-bold">Progres Perizinan Terbaru <span class="text-danger">*</span> :</label>
					<textarea class="form-control form-control-sm" id="quick_progres" name="progres_perizinan" rows="4" required placeholder="Tuliskan perkembangan perizinan terbaru..."></textarea>
				</div>

				<div class="form-group mb-2">
					<label class="form-control-label font-weight-bold">Batas Evaluasi / Jatuh Tempo Izin :</label>
					<input type="date" class="form-control form-control-sm" id="quick_tgl_jatuh_tempo" name="tgl_jatuh_tempo">
				</div>

				<div class="form-group mb-0 bg-light p-2 rounded border">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="quick_kirim_notif" name="kirim_notif" value="1" checked>
						<label class="custom-control-label font-weight-bold text-dark" for="quick_kirim_notif">
							<i class="fab fa-whatsapp text-success"></i> Kirim Notif WhatsApp ke Marketing & Grup VISILAB
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-primary btn-sm" id="btn-save-quick">
					<i class="fas fa-paper-plane"></i>&nbsp; Simpan & Kirim Notif
				</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- ======================================================== -->
<!-- MODAL 4: RIWAYAT PROGRES & LOG NOTIFIKASI -->
<!-- ======================================================== -->
<div id="modal-riwayat" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title"><i class="fas fa-history"></i>&nbsp; Riwayat Perkembangan & Notifikasi</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body py-3">
				<div class="h6 font-weight-bold text-primary mb-3" id="riwayat_instansi_title">Instansi</div>
				<div id="riwayat_timeline_content">
					<p class="text-center text-muted">Memuat riwayat...</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<!-- ======================================================== -->
<!-- MODAL 5: IMPORT EXCEL -->
<!-- ======================================================== -->
<div id="modal-import" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-info text-white">
				<h5 class="modal-title"><i class="fas fa-file-import"></i>&nbsp; Import Data Perizinan dari Excel</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open_multipart('#', array('id' => 'form-import')); ?>
			<div class="modal-body py-3">
				<p class="small text-muted mb-2">
					Pilih file Excel (.xlsx) atau CSV yang berisi format kolom: 
					<code>No, Nama Instansi, Pihak ketiga, Jenis Alat, Status Jasa, Status Perizinan, Progres Perizinan, Marketing, Batas Evaluasi</code>.
				</p>
				<div class="form-group">
					<label class="form-control-label font-weight-bold">File Excel / CSV <span class="text-danger">*</span> :</label>
					<input type="file" class="form-control-file border p-2" id="file_excel" name="file_excel" accept=".xlsx, .xls, .csv" required>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-info btn-sm text-white" id="btn-submit-import">
					<i class="fas fa-upload"></i>&nbsp; Mulai Import
				</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<style>
	.badge-pulse-warning {
		background-color: #ffc107;
		color: #212529;
		animation: pulseWarn 2s infinite;
	}
	@keyframes pulseWarn {
		0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
		70% { box-shadow: 0 0 0 6px rgba(255, 193, 7, 0); }
		100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
	}
	.timeline-item {
		border-left: 3px solid #007bff;
		padding-left: 15px;
		margin-bottom: 20px;
		position: relative;
	}
	.timeline-item::before {
		content: '';
		position: absolute;
		left: -7px;
		top: 0;
		width: 11px;
		height: 11px;
		border-radius: 50%;
		background-color: #007bff;
	}
	.kepatuhan-row {
		display: flex;
		justify-content: space-between;
		padding: 2px 0;
		border-bottom: 1px dashed #e9ecef;
	}
	.kepatuhan-row:last-child {
		border-bottom: none;
	}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var table;

	function formatTglBadge(tglStr, sisaHari, labelPrefix, maxDays) {
		if (!tglStr) return '<div class="kepatuhan-row text-muted small"><span>' + labelPrefix + '</span><span>-</span></div>';
		var sisa = parseInt(sisaHari, 10);
		var d = new Date(tglStr);
		var tglFormatted = ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + ('' + d.getFullYear()).slice(-2);

		if (isNaN(sisa)) return '<div class="kepatuhan-row"><span class="small font-weight-bold">' + labelPrefix + ': ' + tglFormatted + '</span><span class="badge badge-light">-</span></div>';

		var badgeCls = 'badge-success';
		var text = sisa + ' hr';
		if (sisa < 0) {
			badgeCls = 'badge-danger';
			text = 'Lewat ' + Math.abs(sisa) + ' hr';
		} else if (sisa <= maxDays) {
			badgeCls = 'badge-pulse-warning';
			text = sisa + ' hr ⚠️';
		}

		return '<div class="kepatuhan-row"><span class="small font-weight-bold">' + labelPrefix + ': ' + tglFormatted + '</span><span class="badge ' + badgeCls + ' ml-1">' + text + '</span></div>';
	}

	table = $('#table-perizinan').DataTable({
		responsive: false,
		processing: true,
		serverSide: false,
		order: [[0, 'asc']],
		ajax: {
			url: '<?= base_url("perizinan_visilab/data_json") ?>',
			type: 'GET',
			data: function(d) {
				d.status_jasa = $('#filter_status_jasa').val();
				d.status_perizinan = $('#filter_status_perizinan').val();
				d.marketing = $('#filter_marketing').val();
				d.masa_berlaku = $('#filter_masa_berlaku').val();
			},
			dataSrc: function(json) {
				if (json && json.stats) {
					$('#stat-total').text(json.stats.total);
					$('#stat-h45-izin').text(json.stats.h45_izin);
					$('#stat-h30-luk').text(json.stats.h30_luk);
					$('#stat-h30-kal').text(json.stats.h30_kalibrasi);
					$('#stat-h30-tld').text(json.stats.h30_tld);
					$('#stat-expired').text(json.stats.expired);
				}
				if (json && json.data) {
					return json.data;
				}
				if (Array.isArray(json)) {
					return json;
				}
				return [];
			},
			error: function(xhr, status, error) {
				console.error('DataTables Load Error:', xhr.responseText || error);
			}
		},
		language: {
			search: "_INPUT_",
			searchPlaceholder: "Cari cepat instansi, alat, marketing...",
			lengthMenu: "Tampilkan _MENU_ baris",
			info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
			infoEmpty: "Menampilkan 0 data",
			infoFiltered: "(disaring dari _MAX_ data)",
			zeroRecords: "Tidak ada data perizinan yang sesuai",
			paginate: {
				first: '<i class="fas fa-angle-double-left"></i>',
				previous: '<i class="fas fa-angle-left"></i>',
				next: '<i class="fas fa-angle-right"></i>',
				last: '<i class="fas fa-angle-double-right"></i>'
			}
		},
		columns: [
			{ 
				data: null,
				className: 'text-center align-middle font-weight-bold text-muted',
				render: function(data, type, row, meta) {
					return meta.row + 1;
				}
			},
			{ 
				data: 'nama_instansi',
				className: 'text-left align-middle',
				render: function(data, type, row) {
					var html = '<div class="font-weight-bold text-dark" style="font-size: 13px;">' + (data || '-') + '</div>';
					if (row.pic_instansi || row.kontak_pic) {
						html += '<div class="text-muted small mt-1" style="font-size: 11px;"><i class="fas fa-user-circle mr-1 text-primary"></i>' + (row.pic_instansi || 'PIC') + (row.kontak_pic ? ' &bull; ' + row.kontak_pic : '') + '</div>';
					}
					return html;
				}
			},
			{ 
				data: 'pihak_ketiga',
				className: 'text-left align-middle',
				render: function(data) {
					return (!data || data === '-') ? '<span class="text-muted">-</span>' : '<span class="font-weight-600 text-dark" style="font-size: 12px;">' + data + '</span>';
				}
			},
			{ 
				data: 'jenis_alat',
				className: 'text-center align-middle',
				render: function(data) {
					return '<span class="badge badge-light border text-primary px-2 py-1 font-weight-bold" style="border-radius:6px; font-size:11px;">' + (data || '-') + '</span>';
				}
			},
			{ 
				data: 'status_jasa',
				className: 'text-center align-middle',
				render: function(data) {
					if (!data) return '-';
					var bg = 'background:#f1f5f9; color:#475569; border:1px solid #cbd5e1;';
					if (data.indexOf('Pengurusan') !== -1) bg = 'background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;';
					else if (data.indexOf('Pendampingan') !== -1) bg = 'background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;';
					else if (data.indexOf('Tidak') !== -1) bg = 'background:#fef2f2; color:#b91c1c; border:1px solid #fecaca;';
					return '<span class="badge px-2 py-1 font-weight-bold" style="' + bg + ' border-radius:6px; font-size:11px;">' + data + '</span>';
				}
			},
			{ 
				data: 'status_perizinan',
				className: 'text-center align-middle',
				render: function(data, type, row) {
					if (!data || data === '-') {
						if (row.status_jasa === 'Tidak Termasuk Perizinan') {
							return '<span class="badge badge-light border text-muted px-2 py-1" style="border-radius:6px; font-size:11px;">Bukan Perizinan</span>';
						}
						return '<span class="badge badge-light border text-muted px-2 py-1" style="border-radius:6px; font-size:11px;">Belum Diatur</span>';
					}
					var bg = 'background:#f1f5f9; color:#475569; border:1px solid #cbd5e1;';
					if (data === 'Selesai') bg = 'background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;';
					else if (data === 'On Progress') bg = 'background:#fffbeb; color:#b45309; border:1px solid #fde68a;';
					else if (data === 'Menunggu Info Customer') bg = 'background:#ecfeff; color:#0e7490; border:1px solid #a5f3fc;';
					return '<span class="badge px-2 py-1 font-weight-bold" style="' + bg + ' border-radius:6px; font-size:11px;">' + data + '</span>';
				}
			},
			{ 
				data: 'progres_perizinan',
				className: 'text-left align-middle',
				render: function(data, type, row) {
					if (!data && row.status_jasa === 'Tidak Termasuk Perizinan') {
						return '<span class="badge badge-light border text-muted font-italic px-2 py-1"><i class="fas fa-check-circle mr-1 text-success"></i> Bebas Izin</span>';
					}
					var text = data ? data.replace(/\n/g, '<br>') : '<span class="text-muted small">-</span>';
					var btnQuick = '<div class="mt-1"><button type="button" class="btn btn-xs btn-outline-primary btn-quick-progres py-0 px-2 font-weight-bold" style="font-size:10.5px; border-radius:4px;" data-id="' + row.id + '"><i class="fas fa-pencil-alt mr-1"></i> Update Progres</button></div>';
					return '<div style="font-size:12px; line-height:1.35;">' + text + '</div>' + btnQuick;
				}
			},
			{ 
				data: null,
				className: 'text-left align-middle p-1',
				render: function(data, type, row) {
					var hasAnyTgl = row.tgl_jatuh_tempo || row.tgl_exp_luk || row.tgl_exp_kalibrasi || row.tgl_evaluasi_tld;
					if (!hasAnyTgl) {
						if (row.status_jasa === 'Tidak Termasuk Perizinan') {
							return '<span class="badge badge-light border text-muted px-2 py-1 font-weight-normal"><i class="fas fa-ban mr-1 text-secondary"></i> Non-Perizinan</span>';
						}
						return '<span class="badge badge-light border text-muted px-2 py-1 font-weight-normal"><i class="fas fa-clock mr-1 text-secondary"></i> Belum diisi</span>';
					}
					var rowIzin = formatTglBadge(row.tgl_jatuh_tempo, row.sisa_hari_izin, 'Izin', 45);
					var rowLuk  = formatTglBadge(row.tgl_exp_luk, row.sisa_hari_luk, 'LUK', 30);
					var rowKal  = formatTglBadge(row.tgl_exp_kalibrasi, row.sisa_hari_kalibrasi, 'Kalibrasi', 30);
					var rowTld  = formatTglBadge(row.tgl_evaluasi_tld, row.sisa_hari_tld, 'TLD', 30);

					return '<div class="bg-white p-1 rounded border shadow-none" style="min-width: 145px;">' + rowIzin + rowLuk + rowKal + rowTld + '</div>';
				}
			},
			{ 
				data: 'marketing_nama',
				className: 'text-center align-middle',
				render: function(data, type, row) {
					if (!data || data === '-') return '<span class="text-muted">-</span>';
					var html = '<span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="font-size:11px;">' + data + '</span>';
					if (row.marketing_nohp) {
						html += '<div class="small text-muted mt-1" style="font-size:10px;"><i class="fab fa-whatsapp text-success mr-1"></i>' + row.marketing_nohp + '</div>';
					}
					return html;
				}
			},
			{ 
				data: null,
				orderable: false,
				className: 'text-center align-middle',
				render: function(data, type, row) {
					var btnWa = '<button type="button" class="btn btn-xs btn-outline-success font-weight-bold btn-open-manual-notif px-2 py-1" style="border-radius:6px;" data-id="' + row.id + '" title="Kirim Notifikasi WhatsApp Manual"><i class="fab fa-whatsapp mr-1"></i>WA</button>';
					var btnEdit = '<button type="button" class="btn btn-xs btn-outline-primary btn-edit px-2 py-1" style="border-radius:6px;" data-id="' + row.id + '" title="Edit Data Lengkap"><i class="fas fa-edit"></i></button>';
					var btnHist = '<button type="button" class="btn btn-xs btn-outline-secondary btn-history px-2 py-1" style="border-radius:6px;" data-id="' + row.id + '" title="Lihat Riwayat"><i class="fas fa-history"></i></button>';
					var btnDel = '<button type="button" class="btn btn-xs btn-outline-danger btn-delete px-2 py-1" style="border-radius:6px;" data-id="' + row.id + '" title="Hapus Data"><i class="fas fa-trash-alt"></i></button>';

					return '<div class="d-inline-flex align-items-center justify-content-center" style="white-space:nowrap; gap:4px;">' + btnWa + btnEdit + btnHist + btnDel + '</div>';
				}
			}
		]
	});

	// Filter trigger
	$('#filter_status_jasa, #filter_status_perizinan, #filter_marketing, #filter_masa_berlaku').change(function() {
		table.ajax.reload();
	});

	$('#btn-refresh').click(function() {
		table.ajax.reload();
	});

	$('#marketing_nama').change(function() {
		var selected = $(this).find('option:selected');
		var nohp = selected.data('nohp');
		if (nohp) {
			$('#marketing_nohp').val(nohp);
		}
	});

	// Buka modal tambah
	$('#btn-tambah').click(function() {
		$('#form-perizinan')[0].reset();
		$('#id_perizinan').val('');
		$('#modal-form-title').html('<i class="fas fa-file-contract"></i>&nbsp; Tambah Data Perizinan & Kepatuhan Baru');
		$('#modal-form-perizinan').modal('show');
	});

	// Submit Form Tambah / Edit
	$('#form-perizinan').submit(function(e) {
		e.preventDefault();
		var btn = $('#btn-save-perizinan');
		btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

		$.ajax({
			url: '<?= base_url("perizinan_visilab/save") ?>',
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(res) {
				btn.prop('disabled', false).html('<i class="fas fa-save"></i>&nbsp; Simpan Data');
				if (res.status) {
					$('#modal-form-perizinan').modal('hide');
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: res.message,
						timer: 1800,
						showConfirmButton: false
					});
					table.ajax.reload(null, false);
				} else {
					Swal.fire('Gagal', res.message, 'error');
				}
			},
			error: function() {
				btn.prop('disabled', false).html('<i class="fas fa-save"></i>&nbsp; Simpan Data');
				Swal.fire('Error', 'Terjadi kesalahan sistem saat menyimpan data.', 'error');
			}
		});
	});

	// Edit button
	$('#table-perizinan').on('click', '.btn-edit', function() {
		var id = $(this).data('id');
		$.ajax({
			url: '<?= base_url("perizinan_visilab/detail/") ?>' + id,
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				if (res.status && res.data) {
					var d = res.data;
					$('#id_perizinan').val(d.id);
					$('#nama_instansi').val(d.nama_instansi);
					$('#pihak_ketiga').val(d.pihak_ketiga);
					$('#jenis_alat').val(d.jenis_alat);
					$('#status_jasa').val(d.status_jasa);
					$('#status_perizinan').val(d.status_perizinan);
					$('#progres_perizinan').val(d.progres_perizinan);
					$('#marketing_nama').val(d.marketing_nama);
					$('#marketing_nohp').val(d.marketing_nohp);
					$('#pic_instansi').val(d.pic_instansi);
					$('#kontak_pic').val(d.kontak_pic);
					$('#email_pic').val(d.email_pic);
					$('#tgl_jatuh_tempo').val(d.tgl_jatuh_tempo);
					$('#tgl_exp_luk').val(d.tgl_exp_luk);
					$('#tgl_exp_kalibrasi').val(d.tgl_exp_kalibrasi);
					$('#tgl_evaluasi_tld').val(d.tgl_evaluasi_tld);
					$('#catatan').val(d.catatan);

					$('#modal-form-title').html('<i class="fas fa-edit"></i>&nbsp; Edit Data Perizinan: ' + d.nama_instansi);
					$('#modal-form-perizinan').modal('show');
				} else {
					Swal.fire('Error', res.message || 'Gagal mengambil data', 'error');
				}
			}
		});
	});

	// ==========================================
	// MODAL NOTIFIKASI MANUAL WHATSAPP
	// ==========================================
	$('#table-perizinan').on('click', '.btn-open-manual-notif', function() {
		var id = $(this).data('id');
		$('#manual_id').val(id);
		$('input[name="jenis_notif"][value="izin_h45"]').prop('checked', true);
		loadPreviewNotif(id, 'izin_h45');
		$('#modal-notif-manual').modal('show');
	});

	$('input[name="jenis_notif"]').change(function() {
		var id = $('#manual_id').val();
		var jenis = $(this).val();

		if (jenis === 'progres') {
			$('#box_send_visilab').slideDown();
		} else {
			$('#box_send_visilab').slideUp();
		}

		loadPreviewNotif(id, jenis);
	});

	function loadPreviewNotif(id, jenis) {
		$('#manual_pesan_customer').val('Memuat format pesan...');
		$('#manual_pesan_marketing').val('Memuat format pesan...');

		$.ajax({
			url: '<?= base_url("perizinan_visilab/preview_notif") ?>',
			type: 'GET',
			data: { id: id, jenis: jenis },
			dataType: 'json',
			success: function(res) {
				if (res.status && res.data) {
					var d = res.data;
					$('#manual_info_instansi').html('<i class="fas fa-hospital"></i> ' + d.nama_instansi + ' (' + (d.pihak_ketiga || '-') + ')');
					$('#manual_info_sub').text('Alat: ' + d.jenis_alat + ' | PIC: ' + (d.pic_instansi || '-') + ' | Marketing: ' + (d.marketing_nama || '-'));
					$('#manual_kontak_pic').val(d.kontak_pic || '');
					$('#manual_marketing_nohp').val(d.marketing_nohp || '');
					$('#manual_pesan_customer').val(res.pesan_customer);
					$('#manual_pesan_marketing').val(res.pesan_marketing);
				}
			}
		});
	}

	// Submit Kirim Notifikasi Manual
	$('#form-notif-manual').submit(function(e) {
		e.preventDefault();
		var btn = $('#btn-submit-manual-notif');
		btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengirim WhatsApp...');

		$.ajax({
			url: '<?= base_url("perizinan_visilab/kirim_notif_manual") ?>',
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(res) {
				btn.prop('disabled', false).html('<i class="fab fa-whatsapp"></i>&nbsp; Kirim Sekarang (WhatsApp)');
				if (res.status) {
					$('#modal-notif-manual').modal('hide');
					Swal.fire({
						icon: 'success',
						title: 'Notifikasi Terkirim!',
						text: res.message
					});
					table.ajax.reload(null, false);
				} else {
					Swal.fire('Gagal', res.message, 'warning');
				}
			},
			error: function() {
				btn.prop('disabled', false).html('<i class="fab fa-whatsapp"></i>&nbsp; Kirim Sekarang (WhatsApp)');
				Swal.fire('Error', 'Terjadi kesalahan sistem saat mengirim WA.', 'error');
			}
		});
	});

	// Quick update progres modal
	$('#table-perizinan').on('click', '.btn-quick-progres', function() {
		var id = $(this).data('id');
		$.ajax({
			url: '<?= base_url("perizinan_visilab/detail/") ?>' + id,
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				if (res.status && res.data) {
					var d = res.data;
					$('#quick_id').val(d.id);
					$('#quick_info_instansi').text(d.nama_instansi + ' (' + (d.pihak_ketiga || '-') + ')');
					$('#quick_info_alat').text('Alat: ' + d.jenis_alat + ' | Marketing: ' + (d.marketing_nama || '-'));
					$('#quick_status').val(d.status_perizinan || '-');
					$('#quick_progres').val(d.progres_perizinan || '');
					$('#quick_tgl_jatuh_tempo').val(d.tgl_jatuh_tempo || '');
					$('#modal-quick-progres').modal('show');
				}
			}
		});
	});

	$('#form-quick-progres').submit(function(e) {
		e.preventDefault();
		var btn = $('#btn-save-quick');
		btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

		$.ajax({
			url: '<?= base_url("perizinan_visilab/update_progres") ?>',
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(res) {
				btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i>&nbsp; Simpan & Kirim Notif');
				if (res.status) {
					$('#modal-quick-progres').modal('hide');
					Swal.fire({
						icon: 'success',
						title: 'Tersimpan & Notifikasi Terkirim',
						text: res.message,
						timer: 2000,
						showConfirmButton: false
					});
					table.ajax.reload(null, false);
				} else {
					Swal.fire('Gagal', res.message, 'error');
				}
			},
			error: function() {
				btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i>&nbsp; Simpan & Kirim Notif');
				Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
			}
		});
	});

	// Riwayat button
	$('#table-perizinan').on('click', '.btn-history', function() {
		var id = $(this).data('id');
		$('#riwayat_timeline_content').html('<p class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Memuat riwayat...</p>');
		$('#modal-riwayat').modal('show');

		$.ajax({
			url: '<?= base_url("perizinan_visilab/riwayat/") ?>' + id,
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				if (res.status && res.data && res.data.length > 0) {
					var html = '';
					res.data.forEach(function(item) {
						var notifBadge = '';
						if (item.jenis_notif) {
							notifBadge = '<span class="badge badge-primary mr-1">' + item.jenis_notif + '</span>';
						}
						html += '<div class="timeline-item">';
						html += '<div class="d-flex justify-content-between font-weight-bold text-dark">';
						html += '<span>' + notifBadge + 'Status: <span class="badge badge-info">' + (item.status_baru || '-') + '</span></span>';
						html += '<small class="text-muted">' + item.created_at + '</small>';
						html += '</div>';
						html += '<div class="mt-1 font-italic text-secondary">"' + (item.progres_baru ? item.progres_baru.replace(/\n/g, '<br>') : '-') + '"</div>';
						if (item.keterangan) {
							html += '<div class="small text-muted mt-1"><i class="fas fa-info-circle"></i> ' + item.keterangan + '</div>';
						}
						html += '<small class="text-muted"><i class="fas fa-user-edit"></i> Petugas: ' + (item.created_by_nama || 'System') + '</small>';
						html += '</div>';
					});
					$('#riwayat_timeline_content').html(html);
				} else {
					$('#riwayat_timeline_content').html('<p class="text-center text-muted">Belum ada riwayat untuk perizinan ini.</p>');
				}
			}
		});
	});

	// Delete button
	$('#table-perizinan').on('click', '.btn-delete', function() {
		var id = $(this).data('id');
		Swal.fire({
			title: 'Hapus Data Perizinan?',
			text: 'Data perizinan dan seluruh riwayatnya akan dihapus permanen!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Ya, Hapus!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.value || result.isConfirmed) {
				$.ajax({
					url: '<?= base_url("perizinan_visilab/delete/") ?>' + id,
					type: 'POST',
					dataType: 'json',
					success: function(res) {
						if (res.status) {
							Swal.fire({
								icon: 'success',
								title: 'Dihapus!',
								text: res.message,
								timer: 1500,
								showConfirmButton: false
							});
							table.ajax.reload(null, false);
						} else {
							Swal.fire('Gagal', res.message, 'error');
						}
					}
				});
			}
		});
	});

	// Cron reminder massal
	$('#btn-cron-reminder').click(function() {
		Swal.fire({
			title: 'Jalankan Pengingat Otomatis?',
			text: 'Sistem akan memeriksa seluruh kewajiban kepatuhan (Izin H-45, LUK 1 Bln, Kalibrasi 1 Bln, TLD 1 Bln) dan mengirimkan WhatsApp ke PIC Customer serta Marketing.',
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, Jalankan!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.value || result.isConfirmed) {
				Swal.fire({
					title: 'Memproses Pengingat...',
					allowOutsideClick: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});

				$.ajax({
					url: '<?= base_url("perizinan_visilab/cron_reminder_all/vysi_medikal_2026") ?>',
					type: 'GET',
					dataType: 'json',
					success: function(res) {
						Swal.fire('Selesai', res.message, 'info');
						table.ajax.reload(null, false);
					},
					error: function() {
						Swal.fire('Error', 'Gagal menjalankan cron reminder.', 'error');
					}
				});
			}
		});
	});

	// Export Excel
	$('#btn-export').click(function() {
		var q = $.param({
			status_jasa: $('#filter_status_jasa').val(),
			status_perizinan: $('#filter_status_perizinan').val(),
			marketing: $('#filter_marketing').val(),
			masa_berlaku: $('#filter_masa_berlaku').val()
		});
		window.open('<?= base_url("perizinan_visilab/export_excel?") ?>' + q, '_blank');
	});

	// Import Excel
	$('#btn-import').click(function() {
		$('#form-import')[0].reset();
		$('#modal-import').modal('show');
	});

	$('#form-import').submit(function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		var btn = $('#btn-submit-import');
		btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengimpor...');

		$.ajax({
			url: '<?= base_url("perizinan_visilab/import_excel") ?>',
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(res) {
				btn.prop('disabled', false).html('<i class="fas fa-upload"></i>&nbsp; Mulai Import');
				if (res.status) {
					$('#modal-import').modal('hide');
					Swal.fire('Berhasil!', res.message, 'success');
					table.ajax.reload(null, false);
				} else {
					Swal.fire('Gagal', res.message, 'error');
				}
			},
			error: function() {
				btn.prop('disabled', false).html('<i class="fas fa-upload"></i>&nbsp; Mulai Import');
				Swal.fire('Error', 'Terjadi kesalahan sistem saat mengimpor Excel.', 'error');
			}
		});
	});
});
</script>