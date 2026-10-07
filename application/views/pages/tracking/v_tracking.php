<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<!-- Custom Styles for Filter Buttons -->
<style>
	.datepicker {
		z-index: 1600 !important;
		/* Agar muncul di atas modal */
	}

	/* Top Action Buttons */
	.tracking-top-actions .btn {
		font-weight: 600;
		font-size: 0.84rem;
		border-radius: 8px;
		padding: 7px 14px;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
		transition: all 0.2s ease;
		display: inline-flex;
		align-items: center;
		border: 1px solid transparent;
	}
	.tracking-top-actions .btn:hover {
		transform: translateY(-1px);
		box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
	}

	/* Filter Card Container */
	.tracking-filter-card {
		background: #ffffff;
		border: 1px solid #e2e8f0;
		border-radius: 12px;
		box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
		padding: 10px 16px;
	}

	.tracking-filter-label {
		display: inline-flex;
		align-items: center;
		font-size: 0.85rem;
		font-weight: 700;
		color: #475569;
		margin-bottom: 0;
		user-select: none;
	}

	.tracking-filter-label i {
		font-size: 0.95rem;
		color: #3b82f6;
		margin-right: 6px;
	}

	/* Segmented Filter Pills */
	.tracking-filter-group {
		background: #f1f5f9;
		padding: 4px;
		border-radius: 10px;
		display: inline-flex;
		flex-wrap: wrap;
		gap: 4px;
		border: 1px solid #e2e8f0;
	}

	.tracking-filter-group .btn {
		border: none;
		background: transparent;
		color: #64748b;
		font-size: 0.82rem;
		font-weight: 600;
		padding: 6px 14px;
		border-radius: 7px;
		cursor: pointer;
		transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
		display: inline-flex;
		align-items: center;
		white-space: nowrap;
		margin: 0 !important;
	}

	.tracking-filter-group .btn:hover:not(.active) {
		color: #1e293b;
		background: rgba(255, 255, 255, 0.6);
	}

	.tracking-filter-group .btn.active {
		background: #ffffff !important;
		box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04);
	}

	/* Filter Buttons Active Color Themes */
	#btn-filter-all.active {
		color: #1e293b !important;
	}
	#btn-filter-all i {
		color: #64748b;
	}
	#btn-filter-all.active i {
		color: #0f172a;
	}

	#btn-filter-sjbk.active {
		color: #1d4ed8 !important;
	}
	#btn-filter-sjbk i {
		color: #3b82f6;
	}
	#btn-filter-sjbk.active i {
		color: #1d4ed8;
	}

	#btn-filter-ttbk.active {
		color: #b45309 !important;
	}
	#btn-filter-ttbk i {
		color: #d97706;
	}
	#btn-filter-ttbk.active i {
		color: #b45309;
	}

	#btn-filter-sttb.active {
		color: #0f766e !important;
	}
	#btn-filter-sttb i {
		color: #0d9488;
	}
	#btn-filter-sttb.active i {
		color: #0f766e;
	}

	.tracking-filter-group .btn input[type="radio"] {
		display: none;
	}
</style>

<!-- HEADER -->
<header class="page-header">
	<h2><i class="icons fas fa-database"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<?php if ($this->session->flashdata('error_message')): ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<i class="fas fa-exclamation-triangle mr-2"></i>
		<strong>Error!</strong> <?= $this->session->flashdata('error_message') ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success_message')): ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<i class="fas fa-check-circle mr-2"></i>
		<strong>Sukses!</strong> <?= $this->session->flashdata('success_message') ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php endif; ?>

<div class="row">
	<div class="col">
		<div class="d-flex flex-wrap align-items-center tracking-top-actions gap-2 mb-3">
			<?php if (in_array(sessPenggunaId(), [1, 15, 33, 7, 73, 763, 769])) { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-success mr-2 mb-2">
					<i class="icons icon-plus mr-1"></i> Tambah Tracking Pengeluaran
				</a>
				<a href="javascript:;" id="btn-show-add-form-ps" class="btn btn-warning mr-2 mb-2">
					<i class="icons icon-plus mr-1"></i> Tambah Tracking Pengiriman Stok
				</a>
				<a href="javascript:;" id="btn-show-add-form-stb" class="btn btn-info mr-2 mb-2">
					<i class="icons icon-plus mr-1"></i> Tambah Tracking Serah Terima Barang
				</a>
				<a href="javascript:;" id="btn-laporan-form" class="btn btn-secondary mb-2">
					<i class="fas fa-print mr-1"></i> Print Rekapan
				</a>
			<?php } ?>
		</div>

		<!-- Filter Tipe Tracking Segmented Control -->
		<div class="tracking-filter-card mb-3">
			<div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
				<div class="d-flex align-items-center mr-3 py-1">
					<span class="tracking-filter-label">
						<i class="fas fa-filter"></i> Filter Tipe :
					</span>
				</div>
				<div class="btn-group btn-group-toggle tracking-filter-group" data-toggle="buttons">
					<label class="btn active" id="btn-filter-all">
						<input type="radio" name="filter_type" value="" autocomplete="off" checked>
						<i class="fas fa-list mr-1"></i> Semua
					</label>
					<label class="btn" id="btn-filter-sjbk">
						<input type="radio" name="filter_type" value="pengeluaran_barang" autocomplete="off">
						<i class="fas fa-box mr-1"></i> Pengeluaran (SJBK)
					</label>
					<label class="btn" id="btn-filter-ttbk">
						<input type="radio" name="filter_type" value="pengiriman_stok" autocomplete="off">
						<i class="fas fa-truck-moving mr-1"></i> Pengiriman Stok (TTB)
					</label>
					<label class="btn" id="btn-filter-sttb">
						<input type="radio" name="filter_type" value="serah_terima_barang" autocomplete="off">
						<i class="fas fa-handshake mr-1"></i> Serah Terima (STTB)
					</label>
				</div>
			</div>
		</div>

		<!-- TABLE -->
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">No Surat Jalan / Pemindahan</th>
							<th class="text-center">Gudang Pengiriman</th>
							<th class="text-center">Penerima (Customer/Gudang)</th>
							<th class="text-center">Nama Barang</th>
							<th class="text-center">Alamat Penerima</th>
							<th class="text-center">Nama Ekspedisi</th>
							<th class="text-center">Estimasi Penerimaan</th>
							<th class="text-center">Status</th>
							<th class="text-center">Aksi</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- ============================ -->
<!-- MODAL FORM TRACKING BARANG (Pengeluaran Barang) -->
<!-- ============================ -->
<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Tracking Pengeluaran Barang</h5>
				<button type="button" class="close" data-dismiss="modal"></button>
			</div>

			<?= form_open('#', ['id' => 'modal-form', 'autocomplete' => 'off']); ?>
			<input type="hidden" name="tracking_type" value="pengeluaran_barang">

			<div class="modal-body">
				<div>

					<!-- Select SJ -->
					<div class="form-group">
						<label for="id_sj">Nomor Surat Jalan <span class="text-danger">*</span></label>
						<select class="form-control" id="id_sj" name="id_sj" required>
							<option value="">- Pilih No SJ -</option>
							<?php foreach ($list_sj as $row) {
								$value_string =
									$row->id_pengeluaran_barang . "PengHubunG" .
									$row->nama_gudang . "PengHubunG" .
									$row->nama_customer . "PengHubunG" .
									$row->id_gudang . "PengHubunG" .
									$row->id_customer . "PengHubunG" .
									$row->contact . "PengHubunG" .
									$row->alamat . "PengHubunG" .
									$row->nama_ekspedisi . "PengHubunG" .
									$row->id_ekspedisi . "PengHubunG" .
									$row->tgl_keluar . "PengHubunG" .
									$row->no_pengiriman;
							?>
								<option value="<?= htmlspecialchars($value_string, ENT_QUOTES) ?>">
									<?= $row->no_pengiriman ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<!-- Hidden fields for data submission -->
					<input type="hidden" id="id_gudang" name="id_gudang">
					<input type="hidden" id="gudangAsal" name="gudangAsal">
					<input type="hidden" id="id_customer" name="id_customer">
					<input type="hidden" id="id_ekspedisi" name="id_ekspedisi">
					<input type="hidden" id="nama_ekspedisi_in" name="nama_ekspedisi_in">
					<input type="hidden" id="tgl_keluar_in" name="tgl_keluar_in">

					<!-- AUTO FILL FIELDS (Visible Readonly) -->
					<div class="form-group">
						<label>Gudang Pengirim:</label>
						<input type="text" id="nama_gudang" class="form-control" readonly style="background-color: #e9ecef;">
					</div>

					<div class="form-group">
						<label>Nama Customer:</label>
						<input type="text" id="nama_customer" class="form-control" readonly style="background-color: #e9ecef;">
					</div>

					<div class="form-group">
						<label>PIC Penerima:</label>
						<input type="text" id="pic_penerima_in" name="pic_penerima_in" class="form-control" required>
					</div>

					<div class="form-group">
						<label>Alamat Penerima:</label>
						<textarea id="alamat_penerima_in" name="alamat_penerima_in" class="form-control" rows="2" required></textarea>
					</div>

					<div class="form-group">
						<label>Ekspedisi:</label>
						<input type="text" id="nama_ekspedisi" class="form-control" readonly style="background-color: #e9ecef;">
					</div>

					<div class="form-group">
						<label>Tanggal Pengiriman:</label>
						<input type="text" id="tgl_keluar" class="form-control" readonly style="background-color: #e9ecef;">
					</div>

					<hr class="my-4">
					<h6 class="mb-3"><strong>Diisi oleh Tim Warehouse</strong></h6>

					<div class="form-group">
						<label>Estimasi Sampai <span class="text-danger">*</span></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
							</div>
							<input type="text" id="tgl_sampai" name="tgl_sampai" class="form-control datepicker" placeholder="dd-mm-yyyy" required>
						</div>
					</div>

					<div class="form-group">
						<label>No Resi <span class="text-danger">*</span></label>
						<input type="text" id="no_resi" name="no_resi" class="form-control" required>
					</div>

					<div class="form-group">
						<label>Link Resi <span class="text-danger">*</span></label>
						<input type="text" id="link_resi" name="link_resi" placeholder="Google Drive link (atau isi -)" class="form-control" required>
					</div>

					<div class="form-group">
						<label>Keterangan Lainnya <span class="text-danger">*</span></label>
						<textarea id="keterangan" name="keterangan" class="form-control" required></textarea>
					</div>

					<!-- STATUS -->
					<div class="form-group">
						<label>Status <span class="text-danger">*</span></label>
						<select id="status" name="status" class="form-control" required onchange="toggleFormStatus()">
							<option value="">- Pilih Status -</option>
							<option value="1">Proses Kirim</option>
							<option value="2">Manifest Berangkat</option>
							<option value="3">Proses Sortir</option>
							<option value="6">Menunggu Konfirmasi</option>
							<option value="4">Pengantaran Kurir</option>
							<option value="5">Diterima</option>
						</select>
					</div>

					<!-- FORM KETIKA DITERIMA -->
					<div id="form_nama_penerima" class="form-group" style="display:none;">
						<label>Nama Penerima <span class="text-danger">*</span></label>
						<input type="text" id="nama_penerima" name="nama_penerima" class="form-control">
					</div>

					<div id="form_tgl_penerima" class="form-group" style="display:none;">
						<label>Tanggal Penerimaan <span class="text-danger">*</span></label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format":"dd-mm-yyyy"}'>
							<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
							<input type="text" id="tgl_penerima" name="tgl_penerima" class="form-control">
						</div>
					</div>

					<div id="form_bukti_penerima" class="form-group" style="display:none;">
						<label>Bukti Penerimaan <span class="text-danger">*</span></label>
						<textarea id="bukti_penerima" name="bukti_penerima" class="form-control"></textarea>
					</div>

					<!-- FORM STATUS MENUNGGU KONFIRMASI -->
					<div id="form_keterangan_konfirmasi" class="form-group" style="display:none;">
						<label>Keterangan <span class="text-danger">*</span></label>
						<textarea id="keterangan_konfirmasi" name="keterangan_konfirmasi" class="form-control"></textarea>
					</div>

				</div>
			</div>

			<div class="modal-footer">
				<input type="hidden" id="no_sj" name="no_sj">
				<input type="hidden" id="id_pb" name="id_pb">
				<input type="hidden" id="id_tracking" name="id_tracking">

				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>

			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- ============================ -->
<!-- MODAL FORM TRACKING PENGIRIMAN STOK -->
<!-- ============================ -->
<div id="modal-pengiriman-stok" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-warning text-dark">
				<h5 id="modal-label-ps"><i class="flaticon2-avatar icon-2x"></i> Form Tracking Pengiriman Stok (Antar Gudang)</h5>
				<button type="button" class="close" data-dismiss="modal"></button>
			</div>

			<?= form_open('#', ['id' => 'modal-form-ps', 'autocomplete' => 'off']); ?>
			<input type="hidden" name="tracking_type" value="pengiriman_stok">

			<div class="modal-body">
				<div>
					<!-- Select Pengiriman Stok -->
					<div class="form-group">
						<label for="id_ps">No Pemindahan Stok <span class="text-danger">*</span></label>
						<select class="form-control" id="id_ps" name="id_ps" required>
							<option value="">- Pilih No Pemindahan -</option>
							<?php if (isset($list_pengiriman_stok)) foreach ($list_pengiriman_stok as $row) {
								$value_string =
									$row->id_pengiriman_stok . "PengHubunG" .
									$row->nama_gudang_asal . "PengHubunG" .
									$row->nama_gudang_tujuan . "PengHubunG" .
									$row->id_gudang_asal . "PengHubunG" .
									$row->id_gudang_tujuan . "PengHubunG" .
									$row->alamat_gudang_tujuan . "PengHubunG" .
									$row->nama_ekspedisi . "PengHubunG" .
									$row->id_ekspedisi . "PengHubunG" .
									$row->tgl_pengiriman . "PengHubunG" .
									$row->no_pemindahan . "PengHubunG" .
									$row->no_resi;
							?>
								<option value="<?= htmlspecialchars($value_string, ENT_QUOTES) ?>">
									<?= $row->no_pemindahan ?> (<?= $row->nama_gudang_asal ?> → <?= $row->nama_gudang_tujuan ?>)
								</option>
							<?php } ?>
						</select>
					</div>

					<!-- Hidden fields for data submission -->
					<input type="hidden" id="id_gudang_ps" name="id_gudang">
					<input type="hidden" id="gudang_asal_ps" name="gudang_asal_ps">
					<input type="hidden" id="gudang_tujuan_ps" name="gudang_tujuan_ps">
					<input type="hidden" id="id_ekspedisi_ps" name="id_ekspedisi_ps">
					<input type="hidden" id="nama_ekspedisi_ps" name="nama_ekspedisi_ps">
					<input type="hidden" id="tgl_pengiriman_ps" name="tgl_pengiriman_ps">

					<!-- AUTO FILL FIELDS (Visible Readonly) -->
					<div class="form-group">
						<label>Gudang Asal:</label>
						<input type="text" id="gudang_asal_ps_display" class="form-control" readonly style="background-color: #e9ecef;">
					</div>

					<div class="form-group">
						<label>Gudang Tujuan:</label>
						<input type="text" id="gudang_tujuan_ps_display" class="form-control" readonly style="background-color: #e9ecef;">
					</div>

					<div class="form-group">
						<label>PIC Penerima (Gudang Tujuan):</label>
						<input type="text" id="pic_penerima_ps" name="pic_penerima_ps" class="form-control" required>
					</div>

					<div class="form-group">
						<label>Alamat Gudang Tujuan:</label>
						<textarea id="alamat_penerima_ps" name="alamat_penerima_ps" class="form-control" rows="2" required></textarea>
					</div>

					<div class="form-group">
						<label>Ekspedisi:</label>
						<input type="text" id="nama_ekspedisi_ps_display" class="form-control" readonly style="background-color: #e9ecef;">
					</div>

					<div class="form-group">
						<label>Tanggal Pengiriman:</label>
						<input type="text" id="tgl_pengiriman_ps_display" class="form-control" readonly style="background-color: #e9ecef;">
					</div>

					<hr class="my-4">
					<h6 class="mb-3"><strong>Diisi oleh Tim Warehouse</strong></h6>

					<div class="form-group">
						<label>Estimasi Sampai <span class="text-danger">*</span></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
							</div>
							<input type="text" id="tgl_sampai_ps" name="tgl_sampai" class="form-control datepicker" placeholder="dd-mm-yyyy" required>
						</div>
					</div>

					<div class="form-group">
						<label>No Resi <span class="text-danger">*</span></label>
						<input type="text" id="no_resi_ps" name="no_resi" class="form-control" required>
					</div>

					<div class="form-group">
						<label>Link Resi <span class="text-danger">*</span></label>
						<input type="text" id="link_resi_ps" name="link_resi" placeholder="Google Drive link (atau isi -)" class="form-control" required>
					</div>

					<div class="form-group">
						<label>Keterangan Lainnya <span class="text-danger">*</span></label>
						<textarea id="keterangan_ps" name="keterangan" class="form-control" required></textarea>
					</div>

					<!-- STATUS -->
					<div class="form-group">
						<label>Status <span class="text-danger">*</span></label>
						<select id="status_ps" name="status" class="form-control" required onchange="toggleFormStatusPS()">
							<option value="">- Pilih Status -</option>
							<option value="1">Proses Kirim</option>
							<option value="2">Manifest Berangkat</option>
							<option value="3">Proses Sortir</option>
							<option value="6">Menunggu Konfirmasi</option>
							<option value="4">Pengantaran Kurir</option>
							<option value="5">Diterima</option>
						</select>
					</div>

					<!-- FORM KETIKA DITERIMA -->
					<div id="form_nama_penerima_ps" class="form-group" style="display:none;">
						<label>Nama Penerima <span class="text-danger">*</span></label>
						<input type="text" id="nama_penerima_ps" name="nama_penerima" class="form-control">
					</div>

					<div id="form_tgl_penerima_ps" class="form-group" style="display:none;">
						<label>Tanggal Penerimaan <span class="text-danger">*</span></label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format":"dd-mm-yyyy"}'>
							<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
							<input type="text" id="tgl_penerima_ps" name="tgl_penerima" class="form-control">
						</div>
					</div>

					<div id="form_bukti_penerima_ps" class="form-group" style="display:none;">
						<label>Bukti Penerimaan <span class="text-danger">*</span></label>
						<textarea id="bukti_penerima_ps" name="bukti_penerima" class="form-control"></textarea>
					</div>

					<div id="form_keterangan_konfirmasi_ps" class="form-group" style="display:none;">
						<label>Keterangan <span class="text-danger">*</span></label>
						<textarea id="keterangan_konfirmasi_ps" name="keterangan_konfirmasi" class="form-control"></textarea>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<input type="hidden" id="no_pemindahan" name="no_pemindahan">
				<input type="hidden" id="id_pengiriman_stok" name="id_pengiriman_stok">

				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save-ps">Simpan</button>
			</div>

			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- ============================ -->
<!-- MODAL FORM TRACKING SERAH TERIMA BARANG (STTB) -->
<!-- ============================ -->
<div id="modal-serah-terima-barang" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-info text-white">
				<h5 id="modal-label-stb"><i class="flaticon2-avatar icon-2x"></i> Form Tracking Serah Terima Barang (STTB)</h5>
				<button type="button" class="close" data-dismiss="modal"></button>
			</div>

			<?= form_open('#', ['id' => 'modal-form-stb', 'autocomplete' => 'off']); ?>
			<input type="hidden" name="tracking_type" value="serah_terima_barang">

			<div class="modal-body">
				<div>
					<!-- Select Serah Terima Barang -->
					<div class="form-group">
						<label for="id_stb_select">Kode Serah Terima Barang <span class="text-danger">*</span></label>
						<select class="form-control" id="id_stb_select" name="id_stb_select" required>
							<option value="">- Pilih Kode STTB -</option>
							<?php if (isset($list_serah_terima_barang)) foreach ($list_serah_terima_barang as $row) {
								$value_string =
									$row->id_stb . "PengHubunG" .
									$row->kode_stb . "PengHubunG" .
									($row->nama_pihak1 ?? '-') . "PengHubunG" .
									($row->nama_pihak2 ?? '-') . "PengHubunG" .
									($row->alamat_pihak1 ?? '-') . "PengHubunG" .
									($row->alamat_pihak2 ?? '-') . "PengHubunG" .
									($row->tgl_pengajuan ?? '') . "PengHubunG" .
									($row->kota_pengajuan ?? '-') . "PengHubunG" .
									($row->id_pihak1 ?? '') . "PengHubunG" .
									($row->id_customer ?? '');
							?>
								<option value="<?= htmlspecialchars($value_string, ENT_QUOTES) ?>">
									<?= $row->kode_stb ?> (<?= $row->nama_pihak1 ?> → <?= $row->nama_pihak2 ?>)
								</option>
							<?php } ?>
						</select>
					</div>

					<!-- Hidden fields for data submission -->
					<input type="hidden" id="nama_pihak1_stb" name="nama_pihak1_stb">
					<input type="hidden" id="nama_pihak2_stb" name="nama_pihak2_stb">
					<input type="hidden" id="id_customer_stb" name="id_customer_stb">
					<input type="hidden" id="nama_ekspedisi_stb" name="nama_ekspedisi_stb">
					<input type="hidden" id="tgl_pengajuan_stb" name="tgl_pengajuan_stb">

					<!-- AUTO FILL FIELDS (Visible Readonly) -->
					<div class="form-group">
						<label>Pihak Pertama (Pemberi):</label>
						<input type="text" id="nama_pihak1_stb_display" class="form-control" readonly style="background-color: #e9ecef;">
					</div>

					<div class="form-group">
						<label>Pihak Kedua (Penerima):</label>
						<input type="text" id="nama_pihak2_stb_display" class="form-control" readonly style="background-color: #e9ecef;">
					</div>

					<div class="form-group">
						<label>PIC Penerima:</label>
						<input type="text" id="pic_penerima_stb" name="pic_penerima_stb" class="form-control" required>
					</div>

					<div class="form-group">
						<label>Alamat Penerima:</label>
						<textarea id="alamat_penerima_stb" name="alamat_penerima_stb" class="form-control" rows="2" required></textarea>
					</div>

					<div class="form-group">
						<label>Ekspedisi:</label>
						<select class="form-control" id="id_ekspedisi_stb" name="id_ekspedisi_stb">
							<option value="">- Pilih Ekspedisi -</option>
							<?php foreach ($list_eks as $row) { ?>
								<option value="<?= $row->id_ekspedisi ?>" data-nama="<?= $row->nama_ekspedisi ?>">
									<?= $row->nama_ekspedisi ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group">
						<label>Tanggal Pengajuan:</label>
						<input type="text" id="tgl_pengajuan_stb_display" class="form-control" readonly style="background-color: #e9ecef;">
					</div>

					<hr class="my-4">
					<h6 class="mb-3"><strong>Diisi oleh Tim Warehouse</strong></h6>

					<div class="form-group">
						<label>Estimasi Sampai <span class="text-danger">*</span></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
							</div>
							<input type="text" id="tgl_sampai_stb" name="tgl_sampai" class="form-control datepicker" placeholder="dd-mm-yyyy" required>
						</div>
					</div>

					<div class="form-group">
						<label>No Resi</label>
						<input type="text" id="no_resi_stb" name="no_resi" class="form-control" placeholder="Kosongkan jika tidak ada">
					</div>

					<div class="form-group">
						<label>Link Bukti Serah Terima</label>
						<input type="text" id="link_resi_stb" name="link_resi" placeholder="Google Drive link (atau isi -)" class="form-control">
					</div>

					<div class="form-group">
						<label>Keterangan <span class="text-danger">*</span></label>
						<textarea id="keterangan_stb" name="keterangan" class="form-control" required></textarea>
					</div>

					<!-- STATUS -->
					<div class="form-group">
						<label>Status <span class="text-danger">*</span></label>
						<select id="status_stb" name="status" class="form-control" required onchange="toggleFormStatusSTB()">
							<option value="">- Pilih Status -</option>
							<option value="1">Proses Kirim</option>
							<option value="2">Manifest Berangkat</option>
							<option value="3">Proses Sortir</option>
							<option value="6">Menunggu Konfirmasi</option>
							<option value="4">Pengantaran Kurir</option>
							<option value="5">Diterima</option>
						</select>
					</div>

					<!-- FORM KETIKA DITERIMA -->
					<div id="form_nama_penerima_stb" class="form-group" style="display:none;">
						<label>Nama Penerima <span class="text-danger">*</span></label>
						<input type="text" id="nama_penerima_stb" name="nama_penerima" class="form-control">
					</div>

					<div id="form_tgl_penerima_stb" class="form-group" style="display:none;">
						<label>Tanggal Selesai <span class="text-danger">*</span></label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format":"dd-mm-yyyy"}'>
							<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
							<input type="text" id="tgl_penerima_stb" name="tgl_penerima" class="form-control">
						</div>
					</div>

					<div id="form_bukti_penerima_stb" class="form-group" style="display:none;">
						<label>Bukti Serah Terima <span class="text-danger">*</span></label>
						<textarea id="bukti_penerima_stb" name="bukti_penerima" class="form-control"></textarea>
					</div>

					<div id="form_keterangan_konfirmasi_stb" class="form-group" style="display:none;">
						<label>Keterangan <span class="text-danger">*</span></label>
						<textarea id="keterangan_konfirmasi_stb" name="keterangan_konfirmasi" class="form-control"></textarea>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<input type="hidden" id="kode_stb" name="kode_stb">
				<input type="hidden" id="id_stb" name="id_stb">

				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-info btn-save-stb">Simpan</button>
			</div>

			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- ============================ -->
<!-- MODAL FORM TRACKING KIRIM DOKUMEN -->
<!-- ============================ -->
<div id="modal-kirim-dokumen" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-light">
				<h5><i class="fas fa-paper-plane icon-2x"></i> Form Tracking Kirim Dokumen</h5>
				<button type="button" class="close text-light" data-dismiss="modal"></button>
			</div>

			<?= form_open('tracking/add', ['id' => 'modal-form-kirim-dokumen', 'autocomplete' => 'off']); ?>

			<div class="modal-body">
				<input type="hidden" name="tracking_type" value="kirim_dokumen">

				<div class="form-group">
					<label>Pilih Kirim Dokumen <span class="text-danger">*</span></label>
					<select class="form-control" id="id_kirim_dokumen" name="id_kirim_dokumen" required>
						<option value="">-- Pilih Kirim Dokumen --</option>
						<?php if (!empty($list_kirim_dokumen)): ?>
							<?php foreach ($list_kirim_dokumen as $kd): ?>
								<option value="<?= $kd->id_kirim_dokumen ?>"
									data-kode="<?= $kd->kode ?>"
									data-nama-customer="<?= $kd->nama_customer ?>"
									data-alamat="<?= $kd->alamat ?>"
									data-pic="<?= $kd->pic ?>"
									data-ekspedisi="<?= $kd->ekspedisi ?>"
									data-tgl-kirim="<?= $kd->tgl_kirim ?>">
									<?= $kd->kode ?> - <?= $kd->nama_customer ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="form-group">
					<label>Kode Kirim Dokumen</label>
					<input type="text" class="form-control" id="kode_kirim_dokumen" name="kode_kirim_dokumen" readonly>
				</div>

				<div class="form-group">
					<label>Nama Customer</label>
					<input type="text" class="form-control" id="nama_customer_kd" readonly>
				</div>

				<div class="form-group">
					<label>Alamat Penerima</label>
					<textarea class="form-control" id="alamat_kirim_dokumen" name="alamat_kirim_dokumen" rows="2" readonly></textarea>
				</div>

				<div class="form-group">
					<label>PIC Penerima</label>
					<input type="text" class="form-control" id="pic_kirim_dokumen" name="pic_kirim_dokumen" readonly>
				</div>

				<div class="form-group">
					<label>Ekspedisi</label>
					<input type="text" class="form-control" id="nama_ekspedisi_kd" name="nama_ekspedisi_kd" readonly>
					<input type="hidden" id="id_ekspedisi_kd" name="id_ekspedisi_kd">
				</div>

				<div class="form-group">
					<label>Tanggal Kirim</label>
					<input type="text" class="form-control" id="tgl_kirim_kd" name="tgl_kirim_kd" readonly>
				</div>

				<hr>
				<h6 class="text-primary"><strong>Informasi Tracking Tambahan</strong></h6>

				<div class="form-group">
					<label>Tanggal Estimasi Sampai</label>
					<input type="text" class="form-control" data-provide="datepicker" id="tgl_sampai_kd" name="tgl_sampai" data-date-format="dd-mm-yyyy" placeholder="Tanggal Sampai">
				</div>

				<div class="form-group">
					<label>No. Resi</label>
					<input type="text" class="form-control" id="no_resi_kd" name="no_resi" placeholder="No. Resi">
				</div>

				<div class="form-group">
					<label>Link Resi</label>
					<input type="text" class="form-control" id="link_resi_kd" name="link_resi" placeholder="Link Resi">
				</div>

				<div class="form-group">
					<label>Keterangan</label>
					<textarea class="form-control" id="keterangan_kd" name="keterangan" rows="2" placeholder="Keterangan tambahan..."></textarea>
				</div>
			</div>

			<div class="modal-footer">
				<input type="hidden" id="id_kirim_dokumen_hidden" name="id_kirim_dokumen_hidden">

				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary btn-save-kd">Simpan</button>
			</div>

			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- ============================ -->
<!-- MODAL LAPORAN MARKETING      -->
<!-- ============================ -->
<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-dark text-light">
				<h5><i class="flaticon2-avatar icon-2x text-grey-light"></i> Rekapan Tracking Barang</h5>
				<button type="button" class="close" data-dismiss="modal"></button>
			</div>

			<?= form_open('#', ['id' => 'modal-form-marketing', 'autocomplete' => 'off']); ?>

			<div class="modal-body">
				<label>Pilih Tanggal Input Tracking Barang</label>
				<div class="form-group" style="display:flex;">
					<div style="flex:50%; padding:10px;">
						<input class="form-control" data-provide="datepicker" id="tglawal" name="tglawal" data-date-format="yyyy-mm-dd" placeholder="Tanggal Awal" required>
					</div>
					<div style="flex:50%; padding:10px;">
						<input class="form-control" data-provide="datepicker" id="tglakhir" name="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Tanggal Akhir" required>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-export" class="btn btn-success">Export Excel</button>
			</div>

			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- ============================ -->
<!-- JAVASCRIPT                   -->
<!-- ============================ -->
<script>
	function toggleFormStatus() {
		const status = document.getElementById("status").value;

		const fNama = document.getElementById("form_nama_penerima");
		const fTgl = document.getElementById("form_tgl_penerima");
		const fBkt = document.getElementById("form_bukti_penerima");
		const fKet = document.getElementById("form_keterangan_konfirmasi");

		if (status === "5") {
			fNama.style.display = "block";
			fTgl.style.display = "block";
			fBkt.style.display = "block";
			fKet.style.display = "none";
		} else {
			fNama.style.display = "none";
			fTgl.style.display = "none";
			fBkt.style.display = "none";
			fKet.style.display = "block";
		}
	}

	function toggleFormStatusPS() {
		const status = document.getElementById("status_ps").value;

		const fNama = document.getElementById("form_nama_penerima_ps");
		const fTgl = document.getElementById("form_tgl_penerima_ps");
		const fBkt = document.getElementById("form_bukti_penerima_ps");
		const fKet = document.getElementById("form_keterangan_konfirmasi_ps");

		if (status === "5") {
			fNama.style.display = "block";
			fTgl.style.display = "block";
			fBkt.style.display = "block";
			fKet.style.display = "none";
		} else {
			fNama.style.display = "none";
			fTgl.style.display = "none";
			fBkt.style.display = "none";
			fKet.style.display = "block";
		}
	}

	/* COMMENTED OUT: toggleFormStatusPNS - diganti dengan toggleFormStatusSTB
	function toggleFormStatusPNS() {
		const status = document.getElementById("status_pns").value;

		const fNama = document.getElementById("form_nama_penerima_pns");
		const fTgl = document.getElementById("form_tgl_penerima_pns");
		const fBkt = document.getElementById("form_bukti_penerima_pns");
		const fKet = document.getElementById("form_keterangan_konfirmasi_pns");

		if (status === "5") {
			fNama.style.display = "block";
			fTgl.style.display = "block";
			fBkt.style.display = "block";
			fKet.style.display = "none";
		} else {
			fNama.style.display = "none";
			fTgl.style.display = "none";
			fBkt.style.display = "none";
			fKet.style.display = "block";
		}
	}
	*/

	// Fungsi untuk toggle form status Serah Terima Barang (STTB)
	function toggleFormStatusSTB() {
		const status = document.getElementById("status_stb").value;

		const fNama = document.getElementById("form_nama_penerima_stb");
		const fTgl = document.getElementById("form_tgl_penerima_stb");
		const fBkt = document.getElementById("form_bukti_penerima_stb");
		const fKet = document.getElementById("form_keterangan_konfirmasi_stb");

		if (status === "5") {
			fNama.style.display = "block";
			fTgl.style.display = "block";
			fBkt.style.display = "block";
			fKet.style.display = "none";
		} else {
			fNama.style.display = "none";
			fTgl.style.display = "none";
			fBkt.style.display = "none";
			fKet.style.display = "block";
		}
	}

	document.addEventListener("DOMContentLoaded", function() {

		/** -------------------------------------------
		 * AUTO-SELECT FILTER FROM URL PARAMETER
		 * ------------------------------------------*/
		const urlParams = new URLSearchParams(window.location.search);
		const filterParam = urlParams.get('filter');

		if (filterParam) {
			// Set radio button berdasarkan parameter URL
			$('input[name="filter_type"][value="' + filterParam + '"]').prop('checked', true);

			// Update visual button state
			$('.btn-group-toggle label').removeClass('active');
			$('input[name="filter_type"][value="' + filterParam + '"]').parent('label').addClass('active');
		}

		/** -------------------------------------------
		 * DATATABLE
		 * ------------------------------------------*/
		table = $('#kt_table_1').DataTable({
			responsive: true,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'tracking/pagination',
				type: 'POST',
				data: function(e) {
					e.csrf_token = token;
					e.filter_type = $('input[name="filter_type"]:checked').val();
					e.tracking_mode = 'barang'; // Exclude kirim_dokumen di halaman ini
				}
			},
			"drawCallback": function(settings) {
				// PENTING: Inisialisasi Tooltip setiap kali tabel di-render ulang (paging/sorting)
				$('[data-toggle="tooltip"]').tooltip();
			},
			"columnDefs": [{
					targets: [0, 6, 7, 8],
					className: 'text-center'
				},
				{
					targets: 9, // Kolom Aksi
					className: 'text-center',
					render: function(data, type, row) {

						let namaEkspedisi = row[6];
						let idStatus = row[10];
						let encId = row[11];
						let idTagihan = row[12];
						let statusApproval = row[13];
						let trackingType = row[14];

						let btnTagihan = '';

						// UPDATED: Semua tipe tracking dapat mengajukan tagihan ekspedisi
						// Kecuali jika ekspedisi "Diantarkan Langsung"

						// Jika sudah ada tagihan
						if (idTagihan != null && idTagihan != '') {

							if (statusApproval == 99) {
								// Badge tipe untuk tombol
								let typeBadge = '';
								if (trackingType === 'pengiriman_stok') {
									typeBadge = '<span class="badge badge-warning badge-sm ml-1">TTB</span>';
								} else if (trackingType === 'serah_terima_barang') {
									typeBadge = '<span class="badge badge-info badge-sm ml-1">STTB</span>';
								}

								btnTagihan = `
              <a href="<?= base_url('tagihan/ajukan/') ?>${encId}?reset=1" 
                 target="_blank"  
                 class="btn btn-sm btn-danger shadow-sm ml-2 d-flex align-items-center" 
                 title="Pengajuan Ditolak. Klik untuk Mengajukan Ulang.">
                  <i class="bx bx-repeat mr-1"></i>Ajukan Ulang ${typeBadge}
              </a>`;
							}
							// Jika status Revisi (0), arahkan ke form edit
							else if (statusApproval == 0) {
								btnTagihan = `
              <a href="<?= base_url('tagihan/ajukan/') ?>${encId}" 
                 target="_blank"  
                 class="btn btn-sm btn-warning shadow-sm ml-2 d-flex align-items-center" 
                 title="Perlu Revisi. Klik untuk Memperbaiki.">
                  <i class="bx bx-pencil-draw mr-1"></i>Revisi
              </a>`;
							}
							// Jika sedang proses/selesai -> Disabled
							else {
								btnTagihan = `
            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Tagihan sedang diproses atau sudah selesai.">
                <button class="btn btn-sm btn-success shadow-sm ml-2 d-flex align-items-center text-nowrap" 
                        style="cursor: not-allowed; opacity: 0.8;" disabled>
                    <i class="bx bx-check-circle mr-1"></i> Sudah Diajukan
                </button>
            </span>`;
							}

							// --- LOGIKA 1: EKSPEDISI 'Diantarkan Langsung' ---
						} else if (namaEkspedisi === 'Diantarkan Langsung' || namaEkspedisi === 'Dijemput Langsung') {
							btnTagihan = `
              <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Pengiriman Internal (Diantarkan/Dijemput Langsung) tidak memerlukan pengajuan tagihan.">
                  <button class="btn btn-danger text-white shadow-sm rounded d-flex align-items-center" 
                          style="cursor: not-allowed; opacity: 0.6;" disabled>
                      <i class="bx bx-block"></i>
                  </button>
              </span>`;

							// --- LOGIKA 2: STATUS SUDAH DITERIMA (Boleh Ajukan) ---
						} else if (idStatus == '5') {
							// Tentukan label tombol berdasarkan tipe tracking
							let btnLabel = 'Ajukan Tagihan';
							let btnIcon = 'bx-receipt';
							let btnClass = 'btn-dark';

							if (trackingType === 'pengiriman_stok') {
								btnLabel = '<i class="bx bx-receipt mr-1"></i>Ajukan Tagihan';
								btnClass = 'btn-warning text-dark';
							} else if (trackingType === 'serah_terima_barang') {
								btnLabel = '<i class="bx bx-receipt mr-1"></i>Ajukan Tagihan';
								btnClass = 'btn-info';
							} else {
								btnLabel = '<i class="bx bx-receipt mr-1"></i>Ajukan Tagihan';
							}

							btnTagihan = `
              <a href="<?= base_url('tagihan/ajukan/') ?>${encId}" 
                 target="_blank"  
                 class="btn btn-sm ${btnClass} shadow-sm ml-2 d-flex align-items-center" 
                 title="Ajukan Tagihan Ekspedisi">
                  ${btnLabel}
              </a>`;

							// --- LOGIKA 3: BELUM DITERIMA (Disabled) ---
						} else {
							btnTagihan = `
              <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Menunggu status barang 'Diterima'">
                  <button type="button" 
                          class="btn btn-warning shadow-sm ml-2 d-flex align-items-center rounded" 
                          disabled 
                          style="cursor: not-allowed; opacity: 0.7;">
                      <i class="fas fa-clock"></i>
                  </button>
              </span>`;
						}

						// WRAPPER
						return `<div class="d-flex flex-column justify-content-center align-items-center text-nowrap" style="gap: 0.8rem;">
                  ${data} 
                  ${btnTagihan}
              </div>`;
					}
				}
			]
		});

		/** -------------------------------------------
		 * FILTER BUTTON CLICK
		 * ------------------------------------------*/
		$('input[name="filter_type"]').change(function() {
			table.ajax.reload();
		});

		/** -------------------------------------------
		 * MODAL OPEN - Pengeluaran Barang
		 * ------------------------------------------*/
		$('#btn-show-add-form').click(function() {
			$('#modal-form .form-control').val(null);
			$('#modal-form').attr('action', 'tracking/add');
			$('#main-modal').modal();
		});

		/** -------------------------------------------
		 * MODAL OPEN - Pengiriman Stok
		 * ------------------------------------------*/
		$('#btn-show-add-form-ps').click(function() {
			$('#modal-form-ps .form-control').val(null);
			$('#modal-form-ps').attr('action', 'tracking/add');
			$('#modal-pengiriman-stok').modal();
		});

		/** -------------------------------------------
		 * MODAL OPEN - Serah Terima Barang (STTB)
		 * ------------------------------------------*/
		$('#btn-show-add-form-stb').click(function() {
			$('#modal-form-stb .form-control').val(null);
			$('#modal-form-stb').attr('action', 'tracking/add');
			$('#modal-serah-terima-barang').modal();
		});

		/** -------------------------------------------
		 * EXPORT
		 * ------------------------------------------*/
		$('#btn-laporan-form').click(function() {
			$('#main-modal-marketing').modal();
		});

		$('#btn-export').click(function() {
			window.open(
				"<?= base_url(); ?>tracking/exportlaporan/search?tglawal=" +
				encodeURIComponent($('#tglawal').val()) +
				"&tglakhir=" + encodeURIComponent($('#tglakhir').val()),
				"_blank"
			);
			$('#main-modal-marketing').modal('hide');
		});

		/** -------------------------------------------
		 * AUTO FILL FROM SELECT SJ (Pengeluaran Barang)
		 * ------------------------------------------*/
		document.getElementById("id_sj").addEventListener("change", function() {
			let text = this.value;
			let isi = text.split("PengHubunG");

			let idPB = isi[0];
			let nama_gudang = isi[1];
			let nama_customer = isi[2];
			let id_gudang = isi[3];
			let id_customer = isi[4];
			let pic_penerima = isi[5];
			let alamat = isi[6];
			let nama_eks = isi[7];
			let id_eks = isi[8];
			let tgl_keluar = isi[9];
			let no_sj = isi[10];

			// SET UI - Update to use .value for input fields
			document.getElementById('nama_gudang').value = nama_gudang;
			document.getElementById('nama_customer').value = nama_customer;

			document.getElementById('id_gudang').value = id_gudang;
			document.getElementById('gudangAsal').value = nama_gudang;

			document.getElementById('id_customer').value = id_customer;

			document.getElementById('nama_ekspedisi').value = nama_eks;
			document.getElementById('id_ekspedisi').value = id_eks;

			document.getElementById('tgl_keluar').value = tgl_keluar;
			document.getElementById('tgl_keluar_in').value = tgl_keluar;

			document.getElementById('no_sj').value = no_sj;

			document.getElementById('pic_penerima_in').value = pic_penerima;
			document.getElementById('alamat_penerima_in').value = alamat;

			document.getElementById('nama_ekspedisi_in').value = nama_eks;

			document.getElementById('id_pb').value = idPB;
		});

		/** -------------------------------------------
		 * AUTO FILL FROM SELECT PS (Pengiriman Stok)
		 * ------------------------------------------*/
		document.getElementById("id_ps").addEventListener("change", function() {
			let text = this.value;
			let isi = text.split("PengHubunG");

			let id_pengiriman_stok = isi[0];
			let nama_gudang_asal = isi[1];
			let nama_gudang_tujuan = isi[2];
			let id_gudang_asal = isi[3];
			let id_gudang_tujuan = isi[4];
			let alamat_gudang_tujuan = isi[5];
			let nama_ekspedisi = isi[6];
			let id_ekspedisi = isi[7];
			let tgl_pengiriman = isi[8];
			let no_pemindahan = isi[9];
			let no_resi = isi[10];

			// SET UI - Update to use .value for input fields
			document.getElementById('gudang_asal_ps_display').value = nama_gudang_asal;
			document.getElementById('gudang_tujuan_ps_display').value = nama_gudang_tujuan;

			document.getElementById('id_gudang_ps').value = id_gudang_asal;
			document.getElementById('gudang_asal_ps').value = nama_gudang_asal;
			document.getElementById('gudang_tujuan_ps').value = nama_gudang_tujuan;

			document.getElementById('alamat_penerima_ps').value = alamat_gudang_tujuan;

			document.getElementById('nama_ekspedisi_ps_display').value = nama_ekspedisi;
			document.getElementById('id_ekspedisi_ps').value = id_ekspedisi;
			document.getElementById('nama_ekspedisi_ps').value = nama_ekspedisi;

			document.getElementById('tgl_pengiriman_ps_display').value = tgl_pengiriman;
			document.getElementById('tgl_pengiriman_ps').value = tgl_pengiriman;

			document.getElementById('no_pemindahan').value = no_pemindahan;
			document.getElementById('id_pengiriman_stok').value = id_pengiriman_stok;

			// Set no resi jika sudah ada
			if (no_resi && no_resi !== 'undefined') {
				document.getElementById('no_resi_ps').value = no_resi;
			}
		});

		/** -------------------------------------------
		 * SAVE - Pengiriman Stok
		 * ------------------------------------------*/
		$('.btn-save-ps').click(function() {
			var formData = $('#modal-form-ps').serialize();
			$.ajax({
				url: 'tracking/add',
				type: 'POST',
				data: formData,
				dataType: 'json',
				success: function(response) {
					if (response.status === 'success') {
						$('#modal-pengiriman-stok').modal('hide');
						table.ajax.reload();
						Swal.fire('Berhasil', response.message, 'success');
					} else {
						Swal.fire('Error', response.message, 'error');
					}
				},
				error: function(xhr, status, error) {
					Swal.fire('Error', 'Terjadi kesalahan', 'error');
				}
			});
		});

		/** -------------------------------------------
		 * AUTO FILL FROM SELECT STB (Serah Terima Barang)
		 * ------------------------------------------*/
		document.getElementById("id_stb_select").addEventListener("change", function() {
			let text = this.value;
			let isi = text.split("PengHubunG");

			let id_stb = isi[0];
			let kode_stb = isi[1];
			let nama_pihak1 = isi[2];
			let nama_pihak2 = isi[3];
			let alamat_pihak1 = isi[4];
			let alamat_pihak2 = isi[5];
			let tgl_pengajuan = isi[6];
			let kota_pengajuan = isi[7];
			let id_pihak1 = isi[8];
			let id_customer = isi[9];

			// SET UI - Update to use .value for input fields
			document.getElementById('nama_pihak1_stb_display').value = nama_pihak1 || '-';
			document.getElementById('nama_pihak2_stb_display').value = nama_pihak2 || '-';

			document.getElementById('nama_pihak1_stb').value = nama_pihak1 || '-';
			document.getElementById('nama_pihak2_stb').value = nama_pihak2 || '-';
			document.getElementById('id_customer_stb').value = id_customer || '';

			document.getElementById('alamat_penerima_stb').value = alamat_pihak2 || '';

			document.getElementById('tgl_pengajuan_stb_display').value = tgl_pengajuan || '-';
			document.getElementById('tgl_pengajuan_stb').value = tgl_pengajuan || '';

			document.getElementById('kode_stb').value = kode_stb || '';
			document.getElementById('id_stb').value = id_stb || '';
		});

		/* COMMENTED OUT: AUTO FILL FROM SELECT PNS (Penerimaan Stok) - diganti dengan Serah Terima Barang
		document.getElementById("id_pns").addEventListener("change", function() {
			let text = this.value;
			let isi = text.split("PengHubunG");

			let id_penerimaan_stok = isi[0];
			let nama_gudang_asal = isi[1];
			let nama_gudang_tujuan = isi[2];
			let id_gudang_asal = isi[3];
			let id_gudang_tujuan = isi[4];
			let alamat_gudang_tujuan = isi[5];
			let nama_ekspedisi = isi[6];
			let id_ekspedisi = isi[7];
			let tgl_penerimaan = isi[8];
			let no_penerimaan = isi[9];
			let no_resi = isi[10];

			// SET UI
			document.getElementById('gudang_asal_pns_display').innerHTML = nama_gudang_asal;
			document.getElementById('gudang_tujuan_pns_display').innerHTML = nama_gudang_tujuan;

			document.getElementById('gudang_asal_pns').value = nama_gudang_asal;
			document.getElementById('id_gudang_pns').value = id_gudang_tujuan;
			document.getElementById('gudang_tujuan_pns').value = nama_gudang_tujuan;

			document.getElementById('alamat_penerima_pns').value = alamat_gudang_tujuan;

			document.getElementById('nama_ekspedisi_pns_display').innerHTML = nama_ekspedisi || '-';
			document.getElementById('id_ekspedisi_pns').value = id_ekspedisi || '';
			document.getElementById('nama_ekspedisi_pns').value = nama_ekspedisi || '-';

			document.getElementById('tgl_penerimaan_pns_display').innerHTML = tgl_penerimaan;
			document.getElementById('tgl_penerimaan_pns').value = tgl_penerimaan;

			document.getElementById('no_penerimaan').value = no_penerimaan;
			document.getElementById('id_penerimaan_stok').value = id_penerimaan_stok;

			// Set no resi jika sudah ada
			if (no_resi && no_resi !== 'undefined') {
				document.getElementById('no_resi_pns').value = no_resi;
			}
		});
		*/

		/** -------------------------------------------
		 * SAVE - Serah Terima Barang (STTB)
		 * ------------------------------------------*/
		$('.btn-save-stb').click(function() {
			var formData = $('#modal-form-stb').serialize();
			$.ajax({
				url: 'tracking/add',
				type: 'POST',
				data: formData,
				dataType: 'json',
				success: function(response) {
					if (response.status === 'success') {
						$('#modal-serah-terima-barang').modal('hide');
						table.ajax.reload();
						Swal.fire('Berhasil', response.message, 'success');
					} else {
						Swal.fire('Error', response.message, 'error');
					}
				},
				error: function(xhr, status, error) {
					Swal.fire('Error', 'Terjadi kesalahan', 'error');
				}
			});
		});

		/** -------------------------------------------
		 * AUTO FILL FROM SELECT KIRIM DOKUMEN
		 * ------------------------------------------*/
		$('#id_kirim_dokumen').change(function() {
			const selectedOption = $(this).find(':selected');
			const kode = selectedOption.data('kode') || '';
			const namaCustomer = selectedOption.data('nama-customer') || '';
			const alamat = selectedOption.data('alamat') || '';
			const pic = selectedOption.data('pic') || '';
			const ekspedisi = selectedOption.data('ekspedisi') || '';
			const tglKirim = selectedOption.data('tgl-kirim') || '';

			// Fill form fields
			$('#kode_kirim_dokumen').val(kode);
			$('#nama_customer_kd').val(namaCustomer);
			$('#alamat_kirim_dokumen').val(alamat);
			$('#pic_kirim_dokumen').val(pic);
			$('#nama_ekspedisi_kd').val(ekspedisi);
			$('#tgl_kirim_kd').val(tglKirim);
		});

		/** -------------------------------------------
		 * SAVE - Kirim Dokumen
		 * ------------------------------------------*/
		$('.btn-save-kd').click(function() {
			var formData = $('#modal-form-kirim-dokumen').serialize();
			$.ajax({
				url: 'tracking/add',
				type: 'POST',
				data: formData,
				dataType: 'json',
				success: function(response) {
					if (response.status === 'success') {
						$('#modal-kirim-dokumen').modal('hide');
						table.ajax.reload();
						Swal.fire('Berhasil', response.message, 'success');
					} else {
						Swal.fire('Error', response.message, 'error');
					}
				},
				error: function(xhr, status, error) {
					Swal.fire('Error', 'Terjadi kesalahan', 'error');
				}
			});
		});

		/** -------------------------------------------
		 * UPDATE NAMA EKSPEDISI STTB
		 * ------------------------------------------*/
		$('#id_ekspedisi_stb').change(function() {
			var namaEkspedisi = $(this).find(':selected').data('nama') || '';
			$('#nama_ekspedisi_stb').val(namaEkspedisi);
		});

		/** -------------------------------------------
		 * INITIALIZE DATEPICKER
		 * ------------------------------------------*/
		$('.datepicker').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true,
			todayHighlight: true,
			orientation: "bottom auto",
			container: 'body'
		});

		/* COMMENTED OUT: SAVE - Penerimaan Stok - diganti dengan Serah Terima Barang
		$('.btn-save-pns').click(function() {
			var formData = $('#modal-form-pns').serialize();
			$.ajax({
				url: 'tracking/add',
				type: 'POST',
				data: formData,
				dataType: 'json',
				success: function(response) {
					if (response.status === 'success') {
						$('#modal-penerimaan-stok').modal('hide');
						table.ajax.reload();
						Swal.fire('Berhasil', response.message, 'success');
					} else {
						Swal.fire('Error', response.message, 'error');
					}
				},
				error: function(xhr, status, error) {
					Swal.fire('Error', 'Terjadi kesalahan', 'error');
				}
			});
		});
		*/

	});

	function updateDatatable() {
		table.ajax.reload(null, false);
	}
</script>