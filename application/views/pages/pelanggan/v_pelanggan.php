<header class="page-header">
	<h2><i class="icons icon-user-follow"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<style>
	.stat-card {
		transition: all 0.3s ease;
		cursor: pointer;
	}

	.stat-card:hover {
		transform: translateY(-5px);
		box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
	}

	.stat-icon {
		font-size: 45px;
		opacity: 0.9;
	}

	.stat-number {
		font-size: 2rem;
		font-weight: 700;
	}

	.stat-label {
		font-size: 0.85rem;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.filter-section {
		background: #f8f9fa;
		border-radius: 8px;
		padding: 15px;
		margin-bottom: 20px;
	}

	.btn-action-group .btn {
		margin-right: 5px;
		margin-bottom: 5px;
	}

	#kt_table_1 thead th {
		text-align: center !important;
		vertical-align: middle !important;
		background-color: #2196F3;
		color: white;
	}

	.badge-info {
		background-color: #17a2b8;
	}

	.badge-secondary {
		background-color: #6c757d;
	}

	.flash-message {
		margin-bottom: 15px;
	}

	/* Modern Custom Switch Toggle for Customer Status */
	.status-switch-container {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		justify-content: center;
	}

	.status-switch {
		position: relative;
		display: inline-block;
		width: 48px;
		height: 24px;
		margin-bottom: 0;
		vertical-align: middle;
	}

	.status-switch input {
		opacity: 0;
		width: 0;
		height: 0;
	}

	.status-slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #cbd5e1;
		transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
		border-radius: 24px;
	}

	.status-slider:before {
		position: absolute;
		content: "";
		height: 18px;
		width: 18px;
		left: 3px;
		bottom: 3px;
		background-color: white;
		transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
		border-radius: 50%;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
	}

	/* Checked State: New Customer */
	.status-switch input:checked+.status-slider {
		background-color: #17a2b8;
		/* info color */
	}

	.status-switch input:checked+.status-slider:before {
		transform: translateX(24px);
	}

	/* Status text label */
	.status-switch-label {
		font-size: 0.85rem;
		font-weight: 600;
		min-width: 110px;
		text-align: left;
		transition: color 0.3s ease;
	}

	.status-switch-label.text-new {
		color: #17a2b8;
	}

	.status-switch-label.text-existing {
		color: #6c757d;
	}
</style>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('success')): ?>
	<div class="alert alert-success alert-dismissible fade show flash-message" role="alert">
		<i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
	<div class="alert alert-danger alert-dismissible fade show flash-message" role="alert">
		<i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php endif; ?>

<!-- Statistik Cards -->
<div class="container-fluid mb-4">
	<div class="row">
		<!-- Total Pelanggan -->
		<div class="col-lg-4 col-md-6 col-sm-12 mb-3">
			<div class="card shadow-sm border-0 stat-card" id="card-total">
				<div class="card-body d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px;">
					<div class="text-white">
						<h6 class="stat-label mb-1">TOTAL PELANGGAN</h6>
						<h3 class="stat-number mb-0" id="stat-total"><?= number_format($statistik['total']) ?></h3>
					</div>
					<i class="bx bx-group text-white stat-icon"></i>
				</div>
			</div>
		</div>

		<!-- New Customer -->
		<div class="col-lg-4 col-md-6 col-sm-12 mb-3">
			<div class="card shadow-sm border-0 stat-card" id="card-new">
				<div class="card-body d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 8px;">
					<div class="text-white">
						<h6 class="stat-label mb-1">NEW CUSTOMER (<?= date('Y') ?>)</h6>
						<h3 class="stat-number mb-0" id="stat-new"><?= number_format($statistik['new_customer']) ?></h3>
					</div>
					<i class="bx bx-star text-white stat-icon"></i>
				</div>
			</div>
		</div>

		<!-- Existing Customer -->
		<div class="col-lg-4 col-md-6 col-sm-12 mb-3">
			<div class="card shadow-sm border-0 stat-card" id="card-existing">
				<div class="card-body d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 8px;">
					<div class="text-white">
						<h6 class="stat-label mb-1">EXISTING CUSTOMER</h6>
						<h3 class="stat-number mb-0" id="stat-existing"><?= number_format($statistik['existing_customer']) ?></h3>
					</div>
					<i class="bx bx-user-check text-white stat-icon"></i>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col">
		<!-- Action Buttons -->
		<div class="card-body pb-2">
			<div class="row">
				<div class="col-md-12">
					<div class="btn-action-group">
						<?php if (sessPenggunaId() == 72 || sessPenggunaId() == 69 || sessPenggunaId() == 1 || sessPenggunaId() == 7 || sessPenggunaId() == 754 || sessPenggunaId() == 64) { ?>
							<a href="javascript:;" id="btn-show-add-form" class="btn btn-success">
								<i class="icons icon-plus"></i>&nbsp;Tambah Pelanggan
							</a>
						<?php } ?>
						<a href="javascript:;" id="btn-export-form" class="btn btn-primary">
							<i class="fas fa-file-excel"></i>&nbsp;Export Excel
						</a>
						<?php if (sessPenggunaId() == 72 || sessPenggunaId() == 69 || sessPenggunaId() == 1 || sessPenggunaId() == 7 || sessPenggunaId() == 754) { ?>
							<a href="javascript:;" id="btn-import-form" class="btn btn-info">
								<i class="fas fa-upload"></i>&nbsp;Import Excel
							</a>
						<?php } ?>
						<a href="javascript:;" id="btn-cetaklaporan-form" class="btn btn-secondary">
							<i class="fas fa-print"></i>&nbsp;Cetak Rekapan
						</a>
						<a class="btn btn-outline-dark" href="<?= base_url('calonpelanggan') ?>">
							<i class="fas fa-user-plus"></i>&nbsp;Data Calon Pelanggan
						</a>
					</div>
				</div>
			</div>
		</div>

		<!-- Filter Section -->
		<div class="card-body pt-0">
			<div class="filter-section">
				<div class="row align-items-end">
					<div class="col-md-3">
						<label class="small mb-1">Status Customer:</label>
						<select class="form-control form-control-sm" id="filter_status_customer">
							<option value="">Semua Status</option>
							<option value="New Customer">New Customer</option>
							<option value="Existing Customer">Existing Customer</option>
						</select>
					</div>
					<div class="col-md-3">
						<label class="small mb-1">Tipe Bisnis:</label>
						<select class="form-control form-control-sm" id="filter_tipe_bisnis">
							<option value="">Semua Tipe</option>
							<?php foreach ($tipe_bisnis as $row) { ?>
								<option value="<?= $row->tipe_bisnis ?>"><?= $row->tipe_bisnis ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-3">
						<label class="small mb-1">Status Pajak:</label>
						<select class="form-control form-control-sm" id="filter_status_pajak">
							<option value="">Semua Status Pajak</option>
							<option value="PKP">PKP</option>
							<option value="NON PKP">NON PKP</option>
						</select>
					</div>
					<div class="col-md-3">
						<button type="button" class="btn btn-sm btn-warning" id="btn-reset-filter">
							<i class="fas fa-redo"></i> Reset Filter
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Data Table -->
		<div class="card-body pt-0">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th>Nama Pelanggan</th>
							<th>Tipe Bisnis</th>
							<th>Kontak</th>
							<th>Email</th>
							<th>Kota</th>
							<th>Provinsi</th>
							<th>Tgl Registrasi</th>
							<th>Status Customer</th>
							<th>Status</th>
							<?php if (isAdmin() || sessPenggunaId() == 72 || sessPenggunaId() == 85 || sessPenggunaId() == 69 || sessPenggunaId() == 1) { ?>
								<th>Aksi</th>
							<?php } ?>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal Form Pelanggan -->
<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-light">
				<h5 id="modal-label"><i class="fas fa-user-plus"></i> Form Pelanggan</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
				<div class="dt-kategori-form">
					<!-- Row 1: Informasi Dasar -->
					<h6 class="text-primary mb-3"><i class="fas fa-info-circle"></i> Informasi Dasar</h6>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="nama" class="form-control-label">Nama Pelanggan/Instansi <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="nama" name="nama" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="tipe_bisnis" class="form-control-label">Type Customer <span class="text-danger">*</span></label>
								<select data-plugin-selectTwo class="form-control populate" id="tipe_bisnis" name="tipe_bisnis" required>
									<option value="">- Pilih Type -</option>
									<option value="GOVERNMENT">GOVERNMENT</option>
									<option value="PRIVATE">PRIVATE</option>
									<option value="CLINIC">CLINIC</option>
									<option value="PUBLIC CLINIC">PUBLIC CLINIC</option>
									<option value="AGENT">AGENT</option>
									<option value="DOKTER">DOKTER</option>
									<option value="ANIMAL CLINIC">ANIMAL CLINIC</option>
									<option value="COPIER">COPIER</option>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="kontak" class="form-control-label">Kontak <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="kontak" name="kontak" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="email" class="form-control-label">Email</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="email@contoh.com">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="nik" class="form-control-label">NIK <small class="text-muted">(opsional)</small></label>
								<input type="text" class="form-control" id="nik" name="nik" maxlength="20">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="cpname" class="form-control-label">PIC / Contact Person <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="cpname" name="cpname" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="tanggal_registrasi" class="form-control-label">Tanggal Registrasi Customer <span class="text-danger">*</span></label>
								<div class="input-group">
									<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
									<input type="text" class="form-control" data-plugin-datepicker data-plugin-options='{"format": "dd-mm-yyyy", "todayBtn": "linked", "todayHighlight": true}' id="tanggal_registrasi" name="tanggal_registrasi" required>
								</div>
							</div>
						</div>
					</div>

					<hr class="my-3">

					<!-- Row 2: Alamat -->
					<h6 class="text-primary mb-3"><i class="fas fa-map-marker-alt"></i> Alamat</h6>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="kota" class="form-control-label">Kota <span class="text-danger">*</span></label>
								<select data-plugin-selectTwo class="form-control populate" id="kota" name="kota" required>
									<option value="">- Pilih Kota -</option>
									<?php foreach ($list_kota as $row) {
										echo '<option value="' . $row->nama . '">' . $row->nama . '</option>';
									} ?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="provinsi" class="form-control-label">Provinsi <span class="text-danger">*</span></label>
								<select data-plugin-selectTwo class="form-control populate" id="provinsi" name="provinsi" required>
									<option value="">- Pilih Provinsi -</option>
									<?php foreach ($list_prov as $row) {
										echo '<option value="' . $row->nama . '">' . $row->nama . '</option>';
									} ?>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="alamat" class="form-control-label">Alamat Lengkap <span class="text-danger">*</span></label>
						<textarea name="alamat" class="form-control" id="alamat" rows="2" required></textarea>
					</div>

					<hr class="my-3">

					<!-- Row 3: Informasi Pajak -->
					<h6 class="text-primary mb-3"><i class="fas fa-file-invoice"></i> Informasi Pajak <small class="text-muted">(opsional)</small></h6>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="status_pajak" class="form-control-label">Status Pajak Customer</label>
								<select class="form-control" id="status_pajak" name="status_pajak">
									<option value="NON PKP">NON PKP</option>
									<option value="PKP">PKP</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="npwp" class="form-control-label">No NPWP</label>
								<input type="text" class="form-control" id="npwp" name="npwp">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="nama_npwp" class="form-control-label">Nama Instansi di NPWP</label>
								<input type="text" class="form-control" id="nama_npwp" name="nama_npwp">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="link_npwp" class="form-control-label">Link NPWP</label>
						<input type="url" class="form-control" id="link_npwp" name="link_npwp" placeholder="https://...">
					</div>

					<hr class="my-3">

					<!-- Row 4: Pengiriman & Penagihan -->
					<h6 class="text-primary mb-3"><i class="fas fa-shipping-fast"></i> Pengiriman & Penagihan</h6>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="pengiriman_dokumen" class="form-control-label">Pengiriman Dokumen</label>
								<select class="form-control" id="pengiriman_dokumen" name="pengiriman_dokumen">
									<option value="Softfile">Softfile</option>
									<option value="Hardfile">Hardfile</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="jenis_transaksi" class="form-control-label">Jenis Transaksi</label>
								<select class="form-control" id="jenis_transaksi" name="jenis_transaksi">
									<option value="">- Pilih -</option>
									<option value="PPN">PPN</option>
									<option value="NON PPN">NON PPN</option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="alamat_pengiriman_dokumen" class="form-control-label">Alamat Pengiriman Dokumen</label>
						<textarea name="alamat_pengiriman_dokumen" class="form-control" id="alamat_pengiriman_dokumen" rows="2" placeholder="Alamat untuk pengiriman dokumen hardfile/softfile"></textarea>
					</div>

					<div class="form-group">
						<label for="alamat_penagihan" class="form-control-label">Alamat Penagihan</label>
						<textarea name="alamat_penagihan" class="form-control" id="alamat_penagihan" rows="2" placeholder="Alamat untuk penagihan"></textarea>
					</div>

					<div class="form-group">
						<label for="link_folder_berkas" class="form-control-label">
							<i class="fas fa-folder-open text-warning mr-1"></i> Link Folder Berkas Pelanggan
						</label>
						<input type="url" class="form-control" id="link_folder_berkas" name="link_folder_berkas" placeholder="https://drive.google.com/...">
						<small class="form-text text-muted">Masukkan link folder Google Drive/OneDrive yang berisi berkas pelanggan</small>
					</div>

					<hr class="my-3">

					<!-- Row 5: Informasi Tambahan -->
					<h6 class="text-primary mb-3"><i class="fas fa-info"></i> Informasi Tambahan</h6>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="marketing" class="form-control-label">Marketing</label>
								<select data-plugin-selectTwo class="form-control populate" id="marketing" name="marketing">
									<option value="">- Pilih Marketing -</option>
									<option value="Office / Kantor Pusat">Office / Kantor Pusat</option>
									<?php foreach ($nama_marketing as $row) {
										echo '<option value="' . $row->nama . '">' . $row->nama . '</option>';
									} ?>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="kelas" class="form-control-label">Kelas RS</label>
								<select class="form-control" id="kelas" name="kelas">
									<option value="">- Pilih Kelas -</option>
									<option value="A">A</option>
									<option value="B">B</option>
									<option value="C">C</option>
									<option value="D">D</option>
									<option value="E">E</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ktp" class="form-control-label">KTP</label>
								<input type="text" class="form-control" id="ktp" name="ktp">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="tanggal" class="form-control-label">Tanggal Input</label>
						<div class="input-group">
							<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
							<input type="text" class="form-control" data-plugin-datepicker data-plugin-options='{"format": "dd-mm-yyyy", "todayBtn": "linked", "todayHighlight": true}' id="tanggal" name="tanggal">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
				<button type="button" class="btn btn-success btn-save"><i class="fas fa-save"></i> Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- Modal Export Excel -->
<div id="export-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-light">
				<h5 id="modal-label"><i class="fas fa-file-excel"></i> Export Data Pelanggan</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="form-control-label">Status Customer:</label>
					<select class="form-control" id="export_status_customer">
						<option value="">Semua Status</option>
						<option value="New Customer">New Customer</option>
						<option value="Existing Customer">Existing Customer</option>
					</select>
				</div>
				<div class="form-group">
					<label class="form-control-label">Tipe Bisnis:</label>
					<select class="form-control" id="export_tipe_bisnis">
						<option value="">Semua Tipe</option>
						<?php foreach ($tipe_bisnis as $row) { ?>
							<option value="<?= $row->tipe_bisnis ?>"><?= $row->tipe_bisnis ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-do-export" class="btn btn-success"><i class="fas fa-download"></i> Export Semua Data</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Import Excel -->
<div id="import-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-info text-light">
				<h5 id="modal-label"><i class="fas fa-upload"></i> Import Data Pelanggan</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open_multipart('pelanggan/import', array('id' => 'import-form')); ?>
			<div class="modal-body">
				<div class="alert alert-info">
					<i class="fas fa-info-circle"></i> <strong>Panduan Import:</strong>
					<ul class="mb-0 mt-2">
						<li>Download template terlebih dahulu</li>
						<li>Isi data sesuai format template</li>
						<li>Kolom ID kosongkan jika data baru</li>
						<li>Jika ID terisi, data akan diupdate</li>
						<li>Data juga dapat diupdate berdasarkan nama pelanggan yang sama</li>
					</ul>
				</div>
				<div class="form-group">
					<label class="form-control-label">Pilih File Excel (.xlsx):</label>
					<input type="file" name="file_import" class="form-control" accept=".xlsx,.xls" required>
				</div>
				<div class="text-left">
					<a href="<?= base_url('pelanggan/downloadTemplate') ?>" class="btn btn-sm btn-outline-primary">
						<i class="fas fa-download"></i> Download Template
					</a>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-info"><i class="fas fa-upload"></i> Import Data</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- Modal Cetak Laporan (Existing) -->
<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-secondary text-light">
				<h5 id="modal-label"><i class="fas fa-print"></i> Cetak Data Pelanggan</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="form-group">
					<label class="form-control-label">Rentang Tanggal:</label>
					<div class="row">
						<div class="col-6">
							<input class="form-control" data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Tanggal Awal">
						</div>
						<div class="col-6">
							<input class="form-control" data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Tanggal Akhir">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="form-control-label">Nama Marketing:</label>
					<select class="form-control" name="namamarketing" id="namamarketing">
						<option value=''>Semua Marketing</option>
						<?php if ($nama_marketing != NULL): ?>
							<?php foreach ($nama_marketing as $value): ?>
								<option value="<?= $value->nama ?>"><?= $value->nama ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-export" class="btn btn-success"><i class="fas fa-file-excel"></i> Export Excel</button>
				<button type="button" id="btn-exportCOM" class="btn btn-primary"><i class="fas fa-file-excel"></i> Export Commissioning</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// DataTable initialization
		var table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[6, 'desc']
			],
			ajax: {
				url: 'pelanggan/pagination',
				type: 'POST',
				data: function(e) {
					e.status_customer = $('#filter_status_customer').val();
					e.tipe_bisnis = $('#filter_tipe_bisnis').val();
					e.status_pajak = $('#filter_status_pajak').val();
					e.csrf_token = token;
				}
			},
			columnDefs: [{
					targets: [0],
					className: 'text-left'
				},
				{
					targets: [1, 2, 3, 4, 5, 6, 7, 8],
					className: 'text-center'
				},
				<?php if (isAdmin() || sessPenggunaId() == 72 || sessPenggunaId() == 85 || sessPenggunaId() == 69 || sessPenggunaId() == 1) { ?> {
						targets: [9],
						className: 'text-center',
						orderable: false
					}
				<?php } ?>
			],
			language: {
				processing: '<i class="fas fa-spinner fa-spin"></i> Memuat data...',
				emptyTable: 'Tidak ada data pelanggan',
				zeroRecords: 'Data tidak ditemukan'
			}
		});

		function updateDatatable() {
			table.ajax.reload(null, false);
		}

		function updateStatistik() {
			$.get('pelanggan/getStatistik', function(data) {
				var stats = JSON.parse(data);
				$('#stat-total').text(stats.total.toLocaleString('id-ID'));
				$('#stat-new').text(stats.new_customer.toLocaleString('id-ID'));
				$('#stat-existing').text(stats.existing_customer.toLocaleString('id-ID'));
			});
		}

		// Filter change events
		$('#filter_status_customer, #filter_tipe_bisnis, #filter_status_pajak').change(function() {
			updateDatatable();
		});

		// Reset filter
		$('#btn-reset-filter').click(function() {
			$('#filter_status_customer').val('');
			$('#filter_tipe_bisnis').val('');
			$('#filter_status_pajak').val('');
			updateDatatable();
		});

		// Card click for quick filter
		$('#card-total').click(function() {
			$('#filter_status_customer').val('');
			updateDatatable();
		});

		$('#card-new').click(function() {
			$('#filter_status_customer').val('New Customer');
			updateDatatable();
		});

		$('#card-existing').click(function() {
			$('#filter_status_customer').val('Existing Customer');
			updateDatatable();
		});

		// Show Add Form
		$('#btn-show-add-form').click(function() {
			$('#main-modal .form-control').val('');
			$('#main-modal select').val('').trigger('change');
			$('#main-modal #status_pajak').val('NON PKP');
			$('#main-modal #pengiriman_dokumen').val('Softfile');
			$('#main-modal #tanggal_registrasi').val('<?= date('d-m-Y') ?>');
			$('#main-modal #modal-form').attr('action', 'pelanggan/add');
			$('#main-modal #modal-label').html('<i class="fas fa-user-plus"></i> Tambah Pelanggan Baru');
			$('#main-modal').modal('show');
		});

		// Edit button click
		$(document).on('click', '.btn-edit', function() {
			var id = $(this).attr("data-id");
			$('#main-modal #modal-form').attr('action', 'pelanggan/update');
			$('#main-modal #modal-label').html('<i class="fas fa-user-edit"></i> Edit Data Pelanggan');

			fetch('pelanggan/edit/' + id)
				.then(function(resp) {
					return resp.json();
				})
				.then(function(data) {
					$('#main-modal #nama').val(data[0].identitas_pelanggan);
					$('#main-modal #kontak').val(data[0].kontak);
					$('#main-modal #email').val(data[0].email);
					$('#main-modal #nik').val(data[0].nik);
					$('#main-modal #status_pajak').val(data[0].status_pajak);
					$('#main-modal #kota').val(data[0].kota).trigger('change');
					$('#main-modal #provinsi').val(data[0].provinsi).trigger('change');
					$('#main-modal #alamat').val(data[0].alamat);
					$('#main-modal #tanggal').val(data[0].tanggal);
					$('#main-modal #cpname').val(data[0].cpname);
					$('#main-modal #tipe_bisnis').val(data[0].tipe_bisnis).trigger('change');
					$('#main-modal #marketing').val(data[0].marketing).trigger('change');
					$('#main-modal #kelas').val(data[0].kelas);
					$('#main-modal #npwp').val(data[0].npwp);
					$('#main-modal #nama_npwp').val(data[0].nama_npwp);
					$('#main-modal #link_npwp').val(data[0].link_npwp);
					$('#main-modal #pengiriman_dokumen').val(data[0].pengiriman_dokumen);
					$('#main-modal #alamat_pengiriman_dokumen').val(data[0].alamat_pengiriman_dokumen);
					$('#main-modal #alamat_penagihan').val(data[0].alamat_penagihan);
					$('#main-modal #link_folder_berkas').val(data[0].link_folder_berkas);
					$('#main-modal #ktp').val(data[0].ktp);
					$('#main-modal #jenis_transaksi').val(data[0].jenis_transaksi);
					$('#main-modal #id_pelanggan').val(id);

					// Format tanggal registrasi
					if (data[0].tanggal_registrasi) {
						var tgl = new Date(data[0].tanggal_registrasi);
						var formatted = ('0' + tgl.getDate()).slice(-2) + '-' + ('0' + (tgl.getMonth() + 1)).slice(-2) + '-' + tgl.getFullYear();
						$('#main-modal #tanggal_registrasi').val(formatted);
					}

					$('#main-modal').modal('show');
				});
		});

		// Export Modal
		$('#btn-export-form').click(function() {
			$('#export_status_customer').val('');
			$('#export_tipe_bisnis').val('');
			$('#export-modal').modal('show');
		});

		$('#btn-do-export').click(function() {
			var status_customer = encodeURIComponent($('#export_status_customer').val());
			var tipe_bisnis = encodeURIComponent($('#export_tipe_bisnis').val());
			window.open('<?= base_url() ?>pelanggan/exportAll?status_customer=' + status_customer + '&tipe_bisnis=' + tipe_bisnis, '_blank');
			$('#export-modal').modal('hide');
		});

		// Import Modal
		$('#btn-import-form').click(function() {
			$('#import-modal').modal('show');
		});

		// Cetak Rekapan Modal
		$('#btn-cetaklaporan-form').click(function() {
			$('#main-modal-marketing').modal('show');
		});

		$('#btn-export').click(function() {
			var tglawal = encodeURIComponent($('#tglawal').val());
			var tglakhir = encodeURIComponent($('#tglakhir').val());
			var idmarketing = encodeURIComponent($('#namamarketing').val());
			var namamarketing = encodeURIComponent($('#namamarketing option:selected').text());
			window.open('<?= base_url() ?>pelanggan/exportlaporan/search?tglawal=' + tglawal + '&tglakhir=' + tglakhir + '&idmarketing=' + idmarketing + '&namamarketing=' + namamarketing, '_blank');
			$('#main-modal-marketing').modal('hide');
		});

		$('#btn-exportCOM').click(function() {
			var tglawal = encodeURIComponent($('#tglawal').val());
			var tglakhir = encodeURIComponent($('#tglakhir').val());
			var idmarketing = encodeURIComponent($('#namamarketing').val());
			var namamarketing = encodeURIComponent($('#namamarketing option:selected').text());
			window.open('<?= base_url() ?>pelanggan/exportlaporanCOM/search?tglawal=' + tglawal + '&tglakhir=' + tglakhir + '&idmarketing=' + idmarketing + '&namamarketing=' + namamarketing, '_blank');
			$('#main-modal-marketing').modal('hide');
		});

		// Toggle status customer
		$(document).on('change', '.toggle-status-customer', function() {
			var checkbox = $(this);
			var id = checkbox.data('id');
			var isChecked = checkbox.is(':checked');
			var nextStatus = isChecked ? 'New Customer' : 'Existing Customer';

			Swal.fire({
				title: 'Ubah Status Pelanggan?',
				text: "Apakah Anda yakin ingin mengubah status pelanggan ini menjadi " + nextStatus + "?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Ubah!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: 'pelanggan/toggle_status_customer',
						type: 'POST',
						dataType: 'json',
						data: {
							id: id,
							csrf_token: token
						},
						success: function(response) {
							handleResponse(response);
							if (response.status === 'success') {
								updateDatatable();
								updateStatistik();
							} else {
								// Revert switch state on failed response
								checkbox.prop('checked', !isChecked);
							}
						},
						error: function() {
							// Revert switch state on ajax error
							checkbox.prop('checked', !isChecked);
							Swal.fire({
								title: 'Gagal!',
								text: 'Terjadi kesalahan sistem.',
								icon: 'error'
							});
						}
					});
				} else {
					// Revert switch state if user cancels
					checkbox.prop('checked', !isChecked);
				}
			});
		});

		// After successful save, update statistik
		$(document).on('datatable.reload', function() {
			updateStatistik();
		});
	});
</script>