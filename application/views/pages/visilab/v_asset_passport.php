<?php
defined('BASEPATH') or exit('No direct script access allowed');
$pelangganList = $sources['pelanggan'] ?? [];
$teknisiList   = $sources['teknisi'] ?? [];
$perizinanList = $sources['perizinan'] ?? [];
$pengujianList = $sources['pengujian'] ?? [];
$topikList     = $sources['topik'] ?? [];
?>
<header class="page-header">
	<h2><i class="fas fa-qrcode"></i>&nbsp;<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span>Visilab</span></li>
			<li><span><?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?></span></li>
		</ol>
	</div>
</header>

<style>
	:root {
		--ap-radius: 12px;
		--ap-dark: #0f172a;
		--ap-muted: #64748b;
		--ap-border: #e2e8f0;
	}

	.ap-card {
		border: 0 !important;
		border-radius: var(--ap-radius) !important;
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
		background: #fff !important;
		margin-bottom: 20px;
	}

	.ap-kpi {
		border: 1px solid #edf2f7 !important;
		border-radius: var(--ap-radius) !important;
		background: #fff !important;
		padding: 1rem 1.15rem !important;
		position: relative;
		overflow: hidden;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04) !important;
	}

	.ap-kpi::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		bottom: 0;
		width: 4px;
	}

	.ap-kpi-blue::before { background: #2563eb; }
	.ap-kpi-green::before { background: #16a34a; }
	.ap-kpi-amber::before { background: #d97706; }
	.ap-kpi-red::before { background: #dc2626; }
	.ap-kpi-teal::before { background: #0d9488; }

	.ap-kpi-title {
		font-size: 0.72rem;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		color: var(--ap-muted);
		font-weight: 700;
	}

	.ap-kpi-val {
		font-size: 1.55rem;
		font-weight: 800;
		color: var(--ap-dark);
		line-height: 1.2;
	}

	.ap-thumb {
		width: 48px;
		height: 48px;
		object-fit: cover;
		border-radius: 8px;
		border: 1px solid var(--ap-border);
		background: #f8fafc;
	}

	.ap-thumb-empty {
		width: 48px;
		height: 48px;
		border-radius: 8px;
		border: 1px dashed #cbd5e1;
		background: #f8fafc;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		color: #94a3b8;
	}

	.ap-badge-mini {
		display: inline-block;
		font-size: 11px;
		font-weight: 700;
		padding: 3px 8px;
		border-radius: 6px;
		margin-bottom: 3px;
	}

	.ap-badge-valid { background: #dcfce7; color: #166534; }
	.ap-badge-warning { background: #fef3c7; color: #92400e; }
	.ap-badge-expired { background: #fee2e2; color: #991b1b; }
	.ap-badge-none { background: #f1f5f9; color: #64748b; }

	.source-pull-box {
		background: #f0fdfa;
		border: 1px solid #99f6e4;
		border-radius: 10px;
		padding: 12px 14px;
		margin-bottom: 16px;
	}
</style>

<!-- KPI Cards -->
<div class="row mb-3">
	<div class="col-6 col-md-3 col-lg mb-2">
		<div class="ap-kpi ap-kpi-blue">
			<div class="ap-kpi-title">Total Alat QR</div>
			<div class="ap-kpi-val" id="stat-total"><?= (int)($stats['total_passport'] ?? 0) ?></div>
		</div>
	</div>
	<div class="col-6 col-md-3 col-lg mb-2">
		<div class="ap-kpi ap-kpi-green">
			<div class="ap-kpi-title">Semua Aktif</div>
			<div class="ap-kpi-val" id="stat-patuh"><?= (int)($stats['patuh'] ?? 0) ?></div>
		</div>
	</div>
	<div class="col-6 col-md-3 col-lg mb-2">
		<div class="ap-kpi ap-kpi-amber">
			<div class="ap-kpi-title">Segera Habis</div>
			<div class="ap-kpi-val" id="stat-warning"><?= (int)($stats['segera_habis'] ?? 0) ?></div>
		</div>
	</div>
	<div class="col-6 col-md-3 col-lg mb-2">
		<div class="ap-kpi ap-kpi-red">
			<div class="ap-kpi-title">Expired</div>
			<div class="ap-kpi-val" id="stat-expired"><?= (int)($stats['expired'] ?? 0) ?></div>
		</div>
	</div>
	<div class="col-12 col-md-3 col-lg mb-2">
		<div class="ap-kpi ap-kpi-teal">
			<div class="ap-kpi-title">Laporan QR Masuk</div>
			<div class="ap-kpi-val" id="stat-laporan"><?= (int)($stats['laporan_qr'] ?? 0) ?></div>
		</div>
	</div>
</div>

<!-- Toolbar & Tabel -->
<div class="card ap-card">
	<div class="card-body">
		<div class="d-flex flex-wrap justify-content-between align-items-center mb-3" style="gap: 10px;">
			<div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
				<button type="button" class="btn btn-primary btn-sm font-weight-bold" id="btn-tambah-passport">
					<i class="fas fa-plus"></i> Buat QR Passport Baru
				</button>
				<button type="button" class="btn btn-dark btn-sm font-weight-bold" id="btn-cetak-batch">
					<i class="fas fa-print"></i> Cetak Stiker Terpilih (<span id="selected-count">0</span>)
				</button>
			</div>

			<div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
				<select id="filter-kategori" class="form-control form-control-sm" style="width: 175px;">
					<option value="">Semua Layanan</option>
					<option value="1">Medikal / After-Sales</option>
					<option value="2">Visilab (Uji &amp; Kalibrasi)</option>
				</select>
				<input type="text" id="filter-search" class="form-control form-control-sm" style="width: 220px;" placeholder="Cari produk, S/N, instansi...">
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-bordered table-hover" id="tbl-passport" style="width:100%; font-size:13px;">
				<thead class="thead-light">
					<tr>
						<th width="30" class="text-center"><input type="checkbox" id="chk-all"></th>
						<th width="60" class="text-center">Foto</th>
						<th>Kode &amp; Produk</th>
						<th>Instansi / Lokasi</th>
						<th>Masa Aktif (BAPETEN, LUK, Kalibrasi)</th>
						<th>Teknisi PIC</th>
						<th width="165" class="text-center">Aksi</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal Tambah / Edit QR Passport -->
<div class="modal fade" id="modal-passport" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content" style="border-radius:14px;">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title font-weight-bold" id="modal-passport-title">
					<i class="fas fa-qrcode mr-1"></i> Buat QR Asset Passport Baru
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
			</div>
			<form id="form-passport" enctype="multipart/form-data">
				<div class="modal-body">
					<input type="hidden" name="id_passport" id="f_id_passport">

					<!-- Tarik Data Otomatis dari Office -->
					<div class="source-pull-box">
						<div class="font-weight-bold text-dark mb-2" style="font-size:13px;">
							<i class="fas fa-magic text-info mr-1"></i> Tarik Data Otomatis dari Office (Opsional — tanpa TLD)
						</div>
						<div class="row">
							<div class="col-md-6 mb-2 mb-md-0">
								<label class="small font-weight-bold mb-1">Tautkan Data Perizinan Visilab (BAPETEN, LUK, Kalibrasi)</label>
								<select name="id_perizinan" id="f_id_perizinan" class="form-control form-control-sm">
									<option value="">-- Pilih Data Perizinan (Opsional) --</option>
									<?php foreach ($perizinanList as $pz): ?>
										<option value="<?= (int)$pz->id ?>"
											data-instansi="<?= htmlspecialchars($pz->nama_instansi, ENT_QUOTES, 'UTF-8') ?>"
											data-alat="<?= htmlspecialchars($pz->jenis_alat, ENT_QUOTES, 'UTF-8') ?>"
											data-izin="<?= htmlspecialchars((string)$pz->tgl_jatuh_tempo, ENT_QUOTES, 'UTF-8') ?>"
											data-luk="<?= htmlspecialchars((string)$pz->tgl_exp_luk, ENT_QUOTES, 'UTF-8') ?>"
											data-kalibrasi="<?= htmlspecialchars((string)$pz->tgl_exp_kalibrasi, ENT_QUOTES, 'UTF-8') ?>">
											<?= htmlspecialchars($pz->nama_instansi . ' — ' . $pz->jenis_alat, ENT_QUOTES, 'UTF-8') ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6">
								<label class="small font-weight-bold mb-1">Tautkan Data Pengujian Visilab (UKES / UPAR / LHU / Sertifikat)</label>
								<select name="id_pengujian" id="f_id_pengujian" class="form-control form-control-sm">
									<option value="">-- Pilih Data Pengujian (Opsional) --</option>
									<?php foreach ($pengujianList as $pj): ?>
										<option value="<?= (int)$pj->id ?>"
											data-pelanggan="<?= (int)$pj->id_pelanggan ?>"
											data-instansi="<?= htmlspecialchars($pj->identitas_pelanggan ?: $pj->nama_instansi, ENT_QUOTES, 'UTF-8') ?>"
											data-alat="<?= htmlspecialchars($pj->nama_alat ?: $pj->jenis_alat, ENT_QUOTES, 'UTF-8') ?>"
											data-sn="<?= htmlspecialchars((string)$pj->serial_number, ENT_QUOTES, 'UTF-8') ?>"
											data-kode="<?= htmlspecialchars((string)$pj->kode, ENT_QUOTES, 'UTF-8') ?>"
											data-jenis="<?= htmlspecialchars((string)$pj->jenis_uji, ENT_QUOTES, 'UTF-8') ?>"
											data-lhu="<?= htmlspecialchars((string)$pj->link_lhu, ENT_QUOTES, 'UTF-8') ?>"
											data-sertifikat="<?= htmlspecialchars((string)$pj->link_sertifikat, ENT_QUOTES, 'UTF-8') ?>">
											<?= htmlspecialchars($pj->kode . ' | ' . ($pj->identitas_pelanggan ?: $pj->nama_instansi) . ' — ' . ($pj->nama_alat ?: $pj->jenis_alat), ENT_QUOTES, 'UTF-8') ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-8">
							<div class="row">
								<div class="col-md-6 form-group">
									<label class="font-weight-bold small">Pelanggan Terdaftar (Master RS/Klinik)</label>
									<select name="id_pelanggan" id="f_id_pelanggan" class="form-control form-control-sm">
										<option value="">-- Pilih Pelanggan (atau isi instansi manual) --</option>
										<?php foreach ($pelangganList as $pl): ?>
											<option value="<?= (int)$pl->id_pelanggan ?>" data-nama="<?= htmlspecialchars($pl->identitas_pelanggan, ENT_QUOTES, 'UTF-8') ?>">
												<?= htmlspecialchars($pl->identitas_pelanggan . ($pl->kota ? ' (' . $pl->kota . ')' : ''), ENT_QUOTES, 'UTF-8') ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-md-6 form-group">
									<label class="font-weight-bold small">Nama Instansi / RS (Tampil di QR) <span class="text-danger">*</span></label>
									<input type="text" name="nama_instansi" id="f_nama_instansi" class="form-control form-control-sm" required>
								</div>
								<div class="col-md-6 form-group">
									<label class="font-weight-bold small">Nama Produk / Alat <span class="text-danger">*</span></label>
									<input type="text" name="nama_produk" id="f_nama_produk" class="form-control form-control-sm" placeholder="Contoh: Mobile X-Ray 100mA" required>
								</div>
								<div class="col-md-6 form-group">
									<label class="font-weight-bold small">Nomor Seri Produk (Serial Number)</label>
									<input type="text" name="serial_number" id="f_serial_number" class="form-control form-control-sm" placeholder="Contoh: SN-2026-0089">
								</div>
								<div class="col-md-6 form-group">
									<label class="font-weight-bold small">Merk / Tipe</label>
									<input type="text" name="merk_tipe" id="f_merk_tipe" class="form-control form-control-sm" placeholder="Contoh: Siemens / Multix">
								</div>
								<div class="col-md-6 form-group">
									<label class="font-weight-bold small">Lokasi / Ruangan di RS</label>
									<input type="text" name="lokasi_ruangan" id="f_lokasi_ruangan" class="form-control form-control-sm" placeholder="Contoh: Ruang Radiologi Lt. 1">
								</div>
							</div>
						</div>

						<!-- Foto Produk -->
						<div class="col-md-4">
							<div class="border rounded p-2 text-center bg-light">
								<label class="font-weight-bold small d-block text-left">Foto Produk</label>
								<img id="preview-foto" src="<?= base_url('assets/img/device.png') ?>" alt="Preview" style="max-height:130px; width:auto; border-radius:8px; margin-bottom:8px; object-fit:contain;">
								<input type="file" name="foto_produk_file" id="f_foto_file" class="form-control-file form-control-sm mb-2" accept="image/*">
								<input type="text" name="foto_produk_url" id="f_foto_url" class="form-control form-control-sm" placeholder="Atau tempel URL foto produk...">
							</div>
						</div>
					</div>

					<hr class="my-2">
					<div class="row">
						<div class="col-md-4 form-group">
							<label class="font-weight-bold small">Masa Aktif Izin BAPETEN</label>
							<input type="date" name="tgl_exp_izin" id="f_tgl_exp_izin" class="form-control form-control-sm">
						</div>
						<div class="col-md-4 form-group">
							<label class="font-weight-bold small">Masa Aktif LUK (Uji Kesesuaian)</label>
							<input type="date" name="tgl_exp_luk" id="f_tgl_exp_luk" class="form-control form-control-sm">
						</div>
						<div class="col-md-4 form-group">
							<label class="font-weight-bold small">Masa Aktif Kalibrasi Alat</label>
							<input type="date" name="tgl_exp_kalibrasi" id="f_tgl_exp_kalibrasi" class="form-control form-control-sm">
						</div>
					</div>

					<div class="row">
						<div class="col-md-3 form-group">
							<label class="font-weight-bold small">No. Register Uji Terakhir</label>
							<input type="text" name="kode_uji_terakhir" id="f_kode_uji_terakhir" class="form-control form-control-sm" placeholder="001/UKES/VISILAB/...">
						</div>
						<div class="col-md-3 form-group">
							<label class="font-weight-bold small">Jenis Uji Terakhir</label>
							<input type="text" name="jenis_uji_terakhir" id="f_jenis_uji_terakhir" class="form-control form-control-sm" placeholder="Uji Kesesuaian / Paparan">
						</div>
						<div class="col-md-3 form-group">
							<label class="font-weight-bold small">Link Sertifikat Resmi</label>
							<input type="text" name="link_sertifikat" id="f_link_sertifikat" class="form-control form-control-sm" placeholder="https://...">
						</div>
						<div class="col-md-3 form-group">
							<label class="font-weight-bold small">Link Dokumen LHU</label>
							<input type="text" name="link_lhu" id="f_link_lhu" class="form-control form-control-sm" placeholder="https://...">
						</div>
					</div>

					<hr class="my-2">
					<div class="row">
						<div class="col-md-4 form-group">
							<label class="font-weight-bold small">Kategori Layanan Lapor Kerusakan</label>
							<select name="kategori_layanan" id="f_kategori_layanan" class="form-control form-control-sm">
								<option value="1">Medikal / After-Sales (Koordinator Non-Visilab)</option>
								<option value="2">Visilab (Koordinator Visilab)</option>
							</select>
						</div>
						<div class="col-md-4 form-group">
							<label class="font-weight-bold small">Teknisi PIC Default (Auto Tiket &amp; Task)</label>
							<select name="default_teknisi_id" id="f_default_teknisi_id" class="form-control form-control-sm">
								<option value="">-- Lewat Koordinator (Task dibuat saat Dispatch) --</option>
								<?php foreach ($teknisiList as $tk): ?>
									<option value="<?= (int)$tk->pengguna_id ?>">
										<?= htmlspecialchars($tk->nama . ($tk->jabatan ? ' — ' . $tk->jabatan : ''), ENT_QUOTES, 'UTF-8') ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-4 form-group">
							<label class="font-weight-bold small">Kategori Tiket Default</label>
							<select name="id_topik_default" id="f_id_topik_default" class="form-control form-control-sm">
								<option value="">-- Default Sistem --</option>
								<?php foreach ($topikList as $tp): ?>
									<option value="<?= (int)$tp->id_topik ?>"><?= htmlspecialchars($tp->nama, ENT_QUOTES, 'UTF-8') ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<!-- Info Tambahan Non-TLD -->
					<div class="border rounded p-2 mb-2 bg-light">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="font-weight-bold small">Informasi Alat Tambahan dari Office (Non-TLD, misal: Garansi, Tgl Instalasi)</span>
							<button type="button" class="btn btn-xs btn-outline-primary" id="btn-add-extra">
								<i class="fas fa-plus"></i> Tambah Baris
							</button>
						</div>
						<div id="extra-fields-container"></div>
					</div>

					<div class="row mt-2">
						<div class="col-md-9 form-group mb-0">
							<label class="font-weight-bold small">Catatan Internal (Tidak tampil di halaman publik QR)</label>
							<input type="text" name="catatan_internal" id="f_catatan_internal" class="form-control form-control-sm">
						</div>
						<div class="col-md-3 form-group mb-0">
							<label class="font-weight-bold small">Status QR Publik</label>
							<select name="is_active" id="f_is_active" class="form-control form-control-sm">
								<option value="1">Aktif (Bisa Di-scan)</option>
								<option value="0">Nonaktif</option>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary font-weight-bold" id="btn-save-passport">
						<i class="fas fa-save mr-1"></i> Simpan QR Passport
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Detail QR & Riwayat Service -->
<div class="modal fade" id="modal-detail-passport" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content" style="border-radius:14px;">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title font-weight-bold" id="detail-title">Detail QR Asset Passport</h5>
				<button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<!-- Kolom Kiri: Preview QR & Link -->
					<div class="col-md-4 text-center border-right">
						<div id="detail-qr-canvas" class="d-inline-block p-3 border rounded bg-white mb-2"></div>
						<div class="font-weight-bold text-dark" id="detail-kode"></div>
						<div class="small text-muted mb-2" id="detail-sn"></div>
						<div class="input-group input-group-sm mb-2">
							<input type="text" id="detail-public-url" class="form-control" readonly>
							<div class="input-group-append">
								<button class="btn btn-outline-secondary" type="button" id="btn-copy-url" title="Salin Link">
									<i class="fas fa-copy"></i>
								</button>
							</div>
						</div>
						<div class="d-flex justify-content-center" style="gap:6px;">
							<a href="#" target="_blank" id="detail-open-public" class="btn btn-sm btn-info font-weight-bold">
								<i class="fas fa-external-link-alt"></i> Buka Halaman QR
							</a>
							<a href="#" target="_blank" id="detail-print-btn" class="btn btn-sm btn-dark font-weight-bold">
								<i class="fas fa-print"></i> Cetak Stiker
							</a>
						</div>
					</div>

					<!-- Kolom Kanan: Riwayat Service & Tautkan -->
					<div class="col-md-8">
						<ul class="nav nav-tabs mb-3" role="tablist">
							<li class="nav-item">
								<a class="nav-link active font-weight-bold" data-toggle="tab" href="#tab-srv-timeline">Riwayat Service Terpadu</a>
							</li>
							<li class="nav-item">
								<a class="nav-link font-weight-bold" data-toggle="tab" href="#tab-srv-add">+ Tambah Service Manual</a>
							</li>
							<li class="nav-item">
								<a class="nav-link font-weight-bold" data-toggle="tab" href="#tab-srv-link">Tautkan Tiket / PM Lama</a>
							</li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane fade show active" id="tab-srv-timeline">
								<div id="detail-history-list" style="max-height:360px; overflow-y:auto;"></div>
							</div>

							<div class="tab-pane fade" id="tab-srv-add">
								<form id="form-add-service">
									<input type="hidden" name="id_passport" id="srv_id_passport">
									<div class="row">
										<div class="col-md-4 form-group">
											<label class="small font-weight-bold">Tanggal Service</label>
											<input type="date" name="tanggal_service" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required>
										</div>
										<div class="col-md-4 form-group">
											<label class="small font-weight-bold">Jenis Pekerjaan</label>
											<select name="jenis_service" class="form-control form-control-sm">
												<option value="Perbaikan / Service">Perbaikan / Service</option>
												<option value="Preventif Maintenance">Preventif Maintenance</option>
												<option value="Kalibrasi / Uji">Kalibrasi / Uji</option>
												<option value="Instalasi">Instalasi</option>
											</select>
										</div>
										<div class="col-md-4 form-group">
											<label class="small font-weight-bold">Status</label>
											<select name="status_service" class="form-control form-control-sm">
												<option value="Selesai">Selesai</option>
												<option value="On Progress">On Progress</option>
												<option value="Open">Open</option>
											</select>
										</div>
										<div class="col-md-7 form-group">
											<label class="small font-weight-bold">Judul Pekerjaan <span class="text-danger">*</span></label>
											<input type="text" name="judul" class="form-control form-control-sm" placeholder="Contoh: Penggantian Kabel HV & Kalibrasi" required>
										</div>
										<div class="col-md-5 form-group">
											<label class="small font-weight-bold">Nama Teknisi</label>
											<input type="text" name="nama_teknisi" class="form-control form-control-sm" placeholder="Tim Teknisi VYM">
										</div>
										<div class="col-12 form-group">
											<label class="small font-weight-bold">Deskripsi Tindakan</label>
											<textarea name="deskripsi_tindakan" rows="3" class="form-control form-control-sm" placeholder="Ringkasan tindakan perbaikan yang aman dibaca oleh RS..."></textarea>
										</div>
									</div>
									<button type="submit" class="btn btn-sm btn-primary font-weight-bold">
										<i class="fas fa-plus-circle"></i> Simpan Riwayat Service
									</button>
								</form>
							</div>

							<div class="tab-pane fade" id="tab-srv-link">
								<form id="form-link-record">
									<input type="hidden" name="id_passport" id="link_id_passport">
									<div class="form-group">
										<label class="small font-weight-bold">Pilih Jenis Data</label>
										<select name="record_type" id="link_record_type" class="form-control form-control-sm">
											<option value="tiket">Tiket Service</option>
											<option value="pm">Preventif Maintenance (PM)</option>
										</select>
									</div>
									<div class="form-group">
										<label class="small font-weight-bold">Pilih Record dari Office</label>
										<select name="record_id" id="link_record_id" class="form-control form-control-sm" required></select>
									</div>
									<button type="submit" class="btn btn-sm btn-success font-weight-bold">
										<i class="fas fa-link"></i> Tautkan ke Alat Ini
									</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var allRows = [];
	var linkableCache = { tikets: [], pms: [] };

	function esc(s) {
		return $('<div>').text(s == null ? '' : String(s)).html();
	}

	function renderBadge(item) {
		if (!item) return '';
		var cls = 'ap-badge-' + (item.code || 'none');
		return '<div><span class="ap-badge-mini ' + cls + '">' + esc(item.label) + ': ' + esc(item.date_fmt) + '</span></div>';
	}

	var dt = $('#tbl-passport').DataTable({
		processing: true,
		ordering: false,
		data: [],
		language: {
			emptyTable: 'Belum ada data QR Asset Passport. Klik "Buat QR Passport Baru".'
		},
		columns: [
			{
				data: 'id_passport',
				className: 'text-center align-middle',
				render: function(id) {
					return '<input type="checkbox" class="chk-row" value="' + id + '">';
				}
			},
			{
				data: 'foto_url',
				className: 'text-center align-middle',
				render: function(url) {
					if (url) {
						return '<img src="' + esc(url) + '" class="ap-thumb" alt="Produk">';
					}
					return '<span class="ap-thumb-empty"><i class="fas fa-x-ray"></i></span>';
				}
			},
			{
				data: null,
				className: 'align-middle',
				render: function(r) {
					return '<div class="font-weight-bold text-primary">' + esc(r.nama_produk) + '</div>'
						+ '<div class="small text-muted">S/N: <strong>' + esc(r.serial_number || '-') + '</strong> • ' + esc(r.kode_passport) + '</div>'
						+ (r.merk_tipe ? '<div class="small text-muted">' + esc(r.merk_tipe) + '</div>' : '');
				}
			},
			{
				data: null,
				className: 'align-middle',
				render: function(r) {
					return '<div class="font-weight-bold">' + esc(r.nama_instansi) + '</div>'
						+ (r.lokasi_ruangan ? '<div class="small text-muted"><i class="fas fa-map-marker-alt text-danger"></i> ' + esc(r.lokasi_ruangan) + '</div>' : '')
						+ '<div class="small text-muted">Scan: ' + (r.total_scan || 0) + 'x</div>';
				}
			},
			{
				data: 'compliance_summary',
				className: 'align-middle',
				render: function(cs) {
					if (!cs || !cs.items) return '-';
					return renderBadge(cs.items.izin_bapeten)
						+ renderBadge(cs.items.luk)
						+ renderBadge(cs.items.kalibrasi);
				}
			},
			{
				data: null,
				className: 'align-middle',
				render: function(r) {
					var kat = parseInt(r.kategori_layanan, 10) === 2 ? '<span class="badge badge-info">Visilab</span>' : '<span class="badge badge-secondary">Medikal</span>';
					var tek = r.nama_teknisi_default ? '<div class="small font-weight-bold mt-1">' + esc(r.nama_teknisi_default) + '</div>' : '<div class="small text-muted mt-1">Via Koordinator</div>';
					return kat + tek;
				}
			},
			{
				data: null,
				className: 'text-center align-middle',
				render: function(r) {
					var printUrl = window.location.origin + '/asset_passport/print_sticker/' + r.id_passport;
					return '<div class="d-inline-flex align-items-center justify-content-center" style="gap:4px; white-space:nowrap; position:relative; z-index:2;">'
						+ '<button type="button" class="btn btn-sm btn-info btn-ap-detail px-2 py-1" data-id="' + r.id_passport + '" title="Detail, QR & Riwayat" style="cursor:pointer;"><i class="fas fa-qrcode"></i></button>'
						+ '<a href="' + esc(printUrl) + '" target="_blank" class="btn btn-sm btn-dark btn-ap-print px-2 py-1" title="Cetak Stiker QR" style="cursor:pointer;"><i class="fas fa-print"></i></a>'
						+ '<button type="button" class="btn btn-sm btn-warning btn-ap-edit px-2 py-1" data-id="' + r.id_passport + '" title="Edit" style="cursor:pointer;"><i class="fas fa-edit"></i></button>'
						+ '<button type="button" class="btn btn-sm btn-danger btn-ap-hapus px-2 py-1" data-id="' + r.id_passport + '" data-nama="' + esc(r.nama_produk) + '" title="Hapus" style="cursor:pointer;"><i class="fas fa-trash"></i></button>'
						+ '</div>';
				}
			}
		]
	});

	function loadData() {
		$.getJSON(window.location.origin + '/asset_passport/data_json', {
			kategori_layanan: $('#filter-kategori').val(),
			search: $('#filter-search').val()
		}, function(res) {
			if (res && res.status) {
				allRows = res.data || [];
				dt.clear().rows.add(allRows).draw();
				if (res.stats) {
					$('#stat-total').text(res.stats.total_passport);
					$('#stat-patuh').text(res.stats.patuh);
					$('#stat-warning').text(res.stats.segera_habis);
					$('#stat-expired').text(res.stats.expired);
					$('#stat-laporan').text(res.stats.laporan_qr);
				}
				updateSelectedCount();
			}
		});
	}

	loadData();

	$('#filter-kategori').on('change', loadData);
	var searchTimer = null;
	$('#filter-search').on('keyup', function() {
		clearTimeout(searchTimer);
		searchTimer = setTimeout(loadData, 300);
	});

	// Checkbox batch select
	function updateSelectedCount() {
		$('#selected-count').text($('.chk-row:checked').length);
	}
	$('#chk-all').on('change', function() {
		$('.chk-row').prop('checked', $(this).is(':checked'));
		updateSelectedCount();
	});
	$(document).on('change', '.chk-row', updateSelectedCount);

	$('#btn-cetak-batch').on('click', function() {
		var ids = [];
		$('.chk-row:checked').each(function() {
			ids.push($(this).val());
		});
		if (ids.length === 0) {
			Swal.fire('Info', 'Centang minimal 1 alat untuk cetak batch stiker QR.', 'info');
			return;
		}
		window.open(window.location.origin + '/asset_passport/print_sticker?ids=' + ids.join(','), '_blank');
	});

	// Extra non-TLD rows
	function addExtraRow(lbl, val) {
		var html = '<div class="form-row mb-1 extra-row">'
			+ '<div class="col-5"><input type="text" name="extra_label[]" class="form-control form-control-sm" placeholder="Label (misal: Garansi s/d)" value="' + esc(lbl || '') + '"></div>'
			+ '<div class="col-6"><input type="text" name="extra_value[]" class="form-control form-control-sm" placeholder="Nilai (misal: Des 2027)" value="' + esc(val || '') + '"></div>'
			+ '<div class="col-1"><button type="button" class="btn btn-sm btn-outline-danger btn-block btn-rm-extra">&times;</button></div>'
			+ '</div>';
		$('#extra-fields-container').append(html);
	}
	$('#btn-add-extra').on('click', function() { addExtraRow('', ''); });
	$(document).on('click', '.btn-rm-extra', function() { $(this).closest('.extra-row').remove(); });

	// Tarik data otomatis dari Perizinan / Pengujian / Pelanggan
	$('#f_id_pelanggan').on('change', function() {
		var nama = $(this).find(':selected').data('nama');
		if (nama && !$('#f_nama_instansi').val()) {
			$('#f_nama_instansi').val(nama);
		}
	});

	$('#f_id_perizinan').on('change', function() {
		var opt = $(this).find(':selected');
		if (!$(this).val()) return;
		if (opt.data('instansi')) $('#f_nama_instansi').val(opt.data('instansi'));
		if (opt.data('alat') && !$('#f_nama_produk').val()) $('#f_nama_produk').val(opt.data('alat'));
		if (opt.data('izin')) $('#f_tgl_exp_izin').val(opt.data('izin'));
		if (opt.data('luk')) $('#f_tgl_exp_luk').val(opt.data('luk'));
		if (opt.data('kalibrasi')) $('#f_tgl_exp_kalibrasi').val(opt.data('kalibrasi'));
	});

	$('#f_id_pengujian').on('change', function() {
		var opt = $(this).find(':selected');
		if (!$(this).val()) return;
		if (opt.data('pelanggan')) $('#f_id_pelanggan').val(opt.data('pelanggan'));
		if (opt.data('instansi')) $('#f_nama_instansi').val(opt.data('instansi'));
		if (opt.data('alat')) $('#f_nama_produk').val(opt.data('alat'));
		if (opt.data('sn')) $('#f_serial_number').val(opt.data('sn'));
		if (opt.data('kode')) $('#f_kode_uji_terakhir').val(opt.data('kode'));
		if (opt.data('jenis')) $('#f_jenis_uji_terakhir').val(opt.data('jenis'));
		if (opt.data('lhu')) $('#f_link_lhu').val(opt.data('lhu'));
		if (opt.data('sertifikat')) $('#f_link_sertifikat').val(opt.data('sertifikat'));
		$('#f_kategori_layanan').val('2');
	});

	$('#f_foto_file').on('change', function() {
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) { $('#preview-foto').attr('src', e.target.result); };
			reader.readAsDataURL(this.files[0]);
		}
	});

	$('#btn-tambah-passport').on('click', function() {
		$('#form-passport')[0].reset();
		$('#f_id_passport').val('');
		$('#preview-foto').attr('src', '<?= base_url('assets/img/device.png') ?>');
		$('#extra-fields-container').empty();
		$('#modal-passport-title').html('<i class="fas fa-qrcode mr-1"></i> Buat QR Asset Passport Baru');
		$('#modal-passport').modal('show');
	});

	$(document).on('click', '.btn-ap-edit', function(e) {
		e.preventDefault();
		var id = $(this).attr('data-id');
		$.ajax({
			url: window.location.origin + '/asset_passport/detail/' + id,
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				if (!res || !res.status) {
					Swal.fire('Gagal', (res && res.message) ? res.message : 'Data tidak ditemukan.', 'error');
					return;
				}
				var d = res.data;
				$('#form-passport')[0].reset();
				$('#f_id_passport').val(d.id_passport);
				$('#f_id_perizinan').val(d.id_perizinan || '');
				$('#f_id_pengujian').val(d.id_pengujian || '');
				$('#f_id_pelanggan').val(d.id_pelanggan || '');
				$('#f_nama_instansi').val(d.nama_instansi || '');
				$('#f_lokasi_ruangan').val(d.lokasi_ruangan || '');
				$('#f_nama_produk').val(d.nama_produk || '');
				$('#f_merk_tipe').val(d.merk_tipe || '');
				$('#f_serial_number').val(d.serial_number || '');
				$('#f_tgl_exp_izin').val(d.tgl_exp_izin || '');
				$('#f_tgl_exp_luk').val(d.tgl_exp_luk || '');
				$('#f_tgl_exp_kalibrasi').val(d.tgl_exp_kalibrasi || '');
				$('#f_kode_uji_terakhir').val(d.kode_uji_terakhir || '');
				$('#f_jenis_uji_terakhir').val(d.jenis_uji_terakhir || '');
				$('#f_link_sertifikat').val(d.link_sertifikat || '');
				$('#f_link_lhu').val(d.link_lhu || '');
				$('#f_kategori_layanan').val(d.kategori_layanan || '1');
				$('#f_default_teknisi_id').val(d.default_teknisi_id || '');
				$('#f_id_topik_default').val(d.id_topik_default || '');
				$('#f_catatan_internal').val(d.catatan_internal || '');
				$('#f_is_active').val(d.is_active != null ? d.is_active : '1');
				var fotoSrc = d.foto_url || (window.location.origin + '/assets/img/device.png');
				$('#preview-foto').attr('src', fotoSrc);

				$('#extra-fields-container').empty();
				if (Array.isArray(d.info_tambahan)) {
					d.info_tambahan.forEach(function(item) {
						addExtraRow(item.label, item.value);
					});
				}

				$('#modal-passport-title').html('<i class="fas fa-edit mr-1"></i> Edit QR Asset Passport (' + esc(d.kode_passport) + ')');
				$('#modal-passport').modal('show');
			},
			error: function(xhr) {
				Swal.fire('Error', 'Gagal mengambil data edit (HTTP ' + xhr.status + ').', 'error');
			}
		});
	});

	function compressImageFile(file, maxDim, quality) {
		return new Promise(function(resolve) {
			if (!file || !file.type || file.type.indexOf('image/') !== 0 || file.size <= 700 * 1024) {
				resolve(file);
				return;
			}
			var img = new Image();
			var reader = new FileReader();
			reader.onload = function(ev) {
				img.onload = function() {
					var w = img.width;
					var h = img.height;
					if (w > maxDim || h > maxDim) {
						if (w > h) {
							h = Math.round(h * maxDim / w);
							w = maxDim;
						} else {
							w = Math.round(w * maxDim / h);
							h = maxDim;
						}
					}
					var canvas = document.createElement('canvas');
					canvas.width = w;
					canvas.height = h;
					var ctx = canvas.getContext('2d');
					ctx.drawImage(img, 0, 0, w, h);
					canvas.toBlob(function(blob) {
						if (blob && blob.size < file.size) {
							resolve(new File([blob], (file.name || 'foto').replace(/\.[^.]+$/, '') + '.jpg', { type: 'image/jpeg' }));
						} else {
							resolve(file);
						}
					}, 'image/jpeg', quality);
				};
				img.onerror = function() { resolve(file); };
				img.src = ev.target.result;
			};
			reader.onerror = function() { resolve(file); };
			reader.readAsDataURL(file);
		});
	}

	$('#form-passport').on('submit', function(e) {
		e.preventDefault();
		var formEl = this;
		var $btn = $('#btn-save-passport');
		$btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

		var fileInput = document.getElementById('f_foto_file');
		var rawFile = (fileInput && fileInput.files && fileInput.files[0]) ? fileInput.files[0] : null;

		compressImageFile(rawFile, 1400, 0.82).then(function(finalFile) {
			var fd = new FormData(formEl);
			if (finalFile) {
				fd.set('foto_produk_file', finalFile, finalFile.name || 'produk.jpg');
			}
			$.ajax({
				url: window.location.origin + '/asset_passport/save',
				type: 'POST',
				data: fd,
				processData: false,
				contentType: false,
				dataType: 'json',
				success: function(res) {
					$btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan QR Passport');
					if (res && res.status) {
						$('#modal-passport').modal('hide');
						Swal.fire('Berhasil!', res.message, 'success');
						loadData();
					} else {
						Swal.fire('Gagal', (res && res.message) ? res.message : 'Gagal menyimpan data.', 'warning');
					}
				},
				error: function() {
					$btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan QR Passport');
					Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
				}
			});
		});
	});

	$(document).on('click', '.btn-ap-hapus', function(e) {
		e.preventDefault();
		var id = $(this).attr('data-id');
		var nama = $(this).attr('data-nama');
		Swal.fire({
			title: 'Hapus QR Passport?',
			text: 'Alat "' + nama + '" tidak akan bisa diakses lewat QR lagi.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#dc2626',
			confirmButtonText: 'Ya, Hapus',
			cancelButtonText: 'Batal'
		}).then(function(r) {
			if (r.isConfirmed || r.value) {
				$.ajax({
					url: window.location.origin + '/asset_passport/delete/' + id,
					type: 'GET',
					dataType: 'json',
					success: function(res) {
						if (res && res.status) {
							Swal.fire('Dihapus', res.message, 'success');
							loadData();
						} else {
							Swal.fire('Gagal', (res && res.message) ? res.message : 'Gagal menghapus data.', 'warning');
						}
					},
					error: function(xhr) {
						Swal.fire('Error', 'Gagal menghapus data (HTTP ' + xhr.status + ').', 'error');
					}
				});
			}
		});
	});

	// Detail Modal & Riwayat Service
	function renderDetailHistory(list) {
		var $box = $('#detail-history-list');
		if (!list || list.length === 0) {
			$box.html('<div class="text-center text-muted py-4">Belum ada riwayat service pada unit alat ini.</div>');
			return;
		}
		var html = '';
		list.forEach(function(item) {
			var delBtn = item.source === 'manual'
				? '<button type="button" class="btn btn-sm btn-outline-danger btn-del-srv ml-2 py-0 px-2" data-id="' + item.ref_id + '"><i class="fas fa-trash"></i></button>'
				: '';
			html += '<div class="border rounded p-2 mb-2 bg-light">'
				+ '<div class="d-flex justify-content-between align-items-start">'
				+ '<div><strong>' + esc(item.judul) + '</strong> <span class="badge badge-' + esc(item.status_badge) + '">' + esc(item.status_label) + '</span></div>'
				+ '<div><small class="text-muted">' + esc(item.tanggal_fmt) + '</small>' + delBtn + '</div>'
				+ '</div>'
				+ '<div class="small text-muted">' + esc(item.jenis) + ' • ' + esc(item.kode) + ' • PIC: ' + esc(item.teknisi) + '</div>'
				+ (item.tindakan ? '<div class="small mt-1">' + esc(item.tindakan) + '</div>' : '')
				+ '</div>';
		});
		$box.html(html);
	}

	function populateLinkSelect() {
		var type = $('#link_record_type').val();
		var $sel = $('#link_record_id');
		$sel.empty();
		var rows = type === 'tiket' ? linkableCache.tikets : linkableCache.pms;
		if (!rows || rows.length === 0) {
			$sel.append('<option value="">-- Tidak ada data tersedia --</option>');
			return;
		}
		rows.forEach(function(r) {
			if (type === 'tiket') {
				$sel.append('<option value="' + r.id_tiket + '">' + esc(r.kode_tiket + ' — ' + r.subject + ' (' + r.pelanggan + ')') + '</option>');
			} else {
				$sel.append('<option value="' + r.id_pm + '">' + esc(r.kode_pm + ' — ' + r.subject) + '</option>');
			}
		});
	}

	$('#link_record_type').on('change', populateLinkSelect);

	function openDetail(id) {
		$.ajax({
			url: window.location.origin + '/asset_passport/detail/' + id,
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				if (!res || !res.status) {
					Swal.fire('Gagal', (res && res.message) ? res.message : 'Gagal memuat detail alat.', 'warning');
					return;
				}
				var d = res.data;
				linkableCache = res.linkable || { tikets: [], pms: [] };

				var publicUrl = window.location.origin + '/passport/' + d.qr_token;
				var printUrl  = window.location.origin + '/asset_passport/print_sticker/' + d.id_passport;

				$('#detail-title').text(d.nama_produk + ' — ' + d.nama_instansi);
				$('#detail-kode').text(d.kode_passport);
				$('#detail-sn').text('S/N: ' + (d.serial_number || '-'));
				$('#detail-public-url').val(publicUrl);
				$('#detail-open-public').attr('href', publicUrl);
				$('#detail-print-btn').attr('href', printUrl);
				$('#srv_id_passport').val(d.id_passport);
				$('#link_id_passport').val(d.id_passport);

				var $qrBox = $('#detail-qr-canvas').empty();
				try {
					if (typeof QRCode !== 'undefined') {
						new QRCode($qrBox[0], { text: publicUrl, width: 170, height: 170 });
					} else {
						$qrBox.html('<img src="https://api.qrserver.com/v1/create-qr-code/?size=170x170&data=' + encodeURIComponent(publicUrl) + '" alt="QR">');
					}
				} catch (err) {
					$qrBox.html('<img src="https://api.qrserver.com/v1/create-qr-code/?size=170x170&data=' + encodeURIComponent(publicUrl) + '" alt="QR">');
				}

				renderDetailHistory(res.history || []);
				populateLinkSelect();
				$('#modal-detail-passport').modal('show');
			},
			error: function(xhr) {
				Swal.fire('Error', 'Gagal membuka detail QR (HTTP ' + xhr.status + ').', 'error');
			}
		});
	}

	$(document).on('click', '.btn-ap-detail', function(e) {
		e.preventDefault();
		openDetail($(this).attr('data-id'));
	});

	$('#btn-copy-url').on('click', function() {
		var val = $('#detail-public-url').val() || '';
		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(val).then(function() {
				Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Link QR disalin!', showConfirmButton: false, timer: 1800 });
			});
		} else {
			var el = document.getElementById('detail-public-url');
			el.select();
			document.execCommand('copy');
			Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Link QR disalin!', showConfirmButton: false, timer: 1800 });
		}
	});

	$('#form-add-service').on('submit', function(e) {
		e.preventDefault();
		var idPassport = $('#srv_id_passport').val();
		$.post(window.location.origin + '/asset_passport/add_service', $(this).serialize(), function(res) {
			if (res && res.status) {
				$('#form-add-service')[0].reset();
				$('#srv_id_passport').val(idPassport);
				openDetail(idPassport);
				Swal.fire('Berhasil', res.message, 'success');
			} else {
				Swal.fire('Gagal', (res && res.message) ? res.message : 'Gagal menyimpan.', 'warning');
			}
		}, 'json').fail(function(xhr) {
			Swal.fire('Error', 'Gagal menyimpan riwayat (HTTP ' + xhr.status + ').', 'error');
		});
	});

	$(document).on('click', '.btn-del-srv', function(e) {
		e.preventDefault();
		var srvId = $(this).attr('data-id');
		var idPassport = $('#srv_id_passport').val();
		$.getJSON(window.location.origin + '/asset_passport/delete_service/' + srvId, function(res) {
			if (res && res.status) {
				openDetail(idPassport);
			}
		});
	});

	$('#form-link-record').on('submit', function(e) {
		e.preventDefault();
		var idPassport = $('#link_id_passport').val();
		$.post(window.location.origin + '/asset_passport/link_record', $(this).serialize(), function(res) {
			if (res && res.status) {
				openDetail(idPassport);
				Swal.fire('Berhasil', res.message, 'success');
			} else {
				Swal.fire('Gagal', (res && res.message) ? res.message : 'Gagal menautkan.', 'warning');
			}
		}, 'json').fail(function(xhr) {
			Swal.fire('Error', 'Gagal menautkan data (HTTP ' + xhr.status + ').', 'error');
		});
	});
});
</script>
