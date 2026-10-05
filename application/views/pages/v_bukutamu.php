<header class="page-header">
	<h2><i class="fas fa-digital-tachograph"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<?php
$selected_kegiatan_id = isset($selected_kegiatan_id) ? (int) $selected_kegiatan_id : 0;
$selected_tanggal_mulai = !empty($selected_tanggal_mulai) ? $selected_tanggal_mulai : '';
$selected_tanggal_selesai = !empty($selected_tanggal_selesai) ? $selected_tanggal_selesai : '';
$selected_event_label = !empty($selected_event_label) ? $selected_event_label : 'Semua Kegiatan';
$stats = isset($statistik) && is_array($statistik) ? $statistik : array();
$total_data = (int) ($stats['total_data'] ?? 0);
$total_hari_ini = (int) ($stats['total_hari_ini'] ?? 0);
$total_instansi_unik = (int) ($stats['total_instansi_unik'] ?? 0);
$total_nomorwa_unik = (int) ($stats['total_nomorwa_unik'] ?? 0);
$last_input_label = !empty($stats['last_input_at']) ? date('d-M-Y | H:i', strtotime($stats['last_input_at'])) : '-';
$kegiatan_aktif_label = (isset($kegiatan) && is_object($kegiatan) && !empty($kegiatan->nama)) ? $kegiatan->nama : '-';
?>

<style>
	.btm-shell {
		background: linear-gradient(180deg, #f4f8fc 0%, #fbfdff 100%);
		border-radius: 14px;
		padding: 1rem;
	}

	.btm-toolbar {
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 0.75rem;
		margin-bottom: 0.8rem;
	}

	.btm-toolbar h4 {
		margin: 0;
		font-size: 1.05rem;
		font-weight: 700;
		color: #1e2d3d;
	}

	.btm-toolbar p {
		margin: 0.25rem 0 0;
		color: #60758b;
		font-size: 0.86rem;
	}

	.btm-actions {
		display: flex;
		gap: 0.5rem;
		flex-wrap: wrap;
	}

	.btm-filter-card {
		background: #fff;
		border: 1px solid #e5edf5;
		border-radius: 12px;
		padding: 0.9rem;
		margin-bottom: 0.85rem;
	}

	.btm-filter-card label {
		font-weight: 600;
		color: #2f4356;
		font-size: 0.86rem;
		margin-bottom: 0.3rem;
	}

	.btm-stats {
		display: grid;
		grid-template-columns: repeat(5, minmax(130px, 1fr));
		gap: 0.65rem;
		margin-bottom: 0.85rem;
	}

	.btm-stat {
		background: #fff;
		border: 1px solid #e5edf5;
		border-radius: 12px;
		padding: 0.75rem;
	}

	.btm-stat .num {
		font-size: 1.35rem;
		font-weight: 800;
		line-height: 1.1;
		color: #0d4d7f;
	}

	.btm-stat .txt {
		margin-top: 0.2rem;
		font-size: 0.76rem;
		text-transform: uppercase;
		letter-spacing: 0.03em;
		color: #6f8498;
	}

	.btm-stat-foot {
		color: #6f8498;
		font-size: 0.8rem;
		margin-bottom: 0.9rem;
	}

	.btm-table-card {
		background: #fff;
		border: 1px solid #e5edf5;
		border-radius: 12px;
		padding: 0.85rem;
	}

	#kt_table_1 th {
		font-size: 0.8rem;
		text-transform: uppercase;
		letter-spacing: 0.03em;
		background: #f1f6fb;
	}

	@media (max-width: 992px) {
		.btm-stats {
			grid-template-columns: repeat(2, minmax(130px, 1fr));
		}
	}
</style>

<div class="btm-shell">
	<div class="btm-toolbar">
		<div>
			<h4>Dashboard Manajemen Buku Tamu</h4>
			<p>Kelola data pengunjung berdasarkan event dan rentang tanggal dengan statistik realtime.</p>
		</div>
		<div class="btm-actions">
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Kegiatan</a>
			<a href="javascript:;" id="btn-show-qr-modal" class="btn btn-sm btn-warning text-dark font-weight-bold"><i class="fas fa-qrcode"></i>&nbsp;QR Code Agenda</a>
			<a href="javascript:;" id="btn-show-tamu-form" class="btn btn-sm btn-info"><i class="icons icon-user-follow"></i>&nbsp;Tambah Tamu Manual</a>
			<a href="javascript:;" id="btn-cetaklaporan-form" class="btn btn-sm btn-primary"><i class="fas fa-print"></i>&nbsp;Cetak Rekapan</a>
		</div>
	</div>

	<div class="btm-filter-card">
		<div class="row align-items-end">
			<div class="col-md-4 form-group mb-2">
				<label for="filter_kegiatan">Filter Event</label>
				<select class="form-control" id="filter_kegiatan" name="filter_kegiatan">
					<option value="" <?= $selected_kegiatan_id === 0 ? 'selected' : '' ?>>Semua Kegiatan</option>
					<?php if (!empty($nama_kegiatan)): ?>
						<?php foreach ($nama_kegiatan as $value): ?>
							<option value="<?= (int) $value->id ?>" <?= ((int) $value->id === $selected_kegiatan_id ? 'selected' : '') ?>><?= $value->nama ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="col-md-2 form-group mb-2">
				<label for="filter_tanggal_mulai">Dari Tanggal</label>
				<input type="date" class="form-control" id="filter_tanggal_mulai" value="<?= html_escape($selected_tanggal_mulai) ?>">
			</div>
			<div class="col-md-2 form-group mb-2">
				<label for="filter_tanggal_selesai">Sampai Tanggal</label>
				<input type="date" class="form-control" id="filter_tanggal_selesai" value="<?= html_escape($selected_tanggal_selesai) ?>">
			</div>
			<div class="col-md-2 form-group mb-2">
				<button type="button" id="btn-apply-filter" class="btn btn-primary btn-block">Terapkan</button>
			</div>
			<div class="col-md-2 form-group mb-2">
				<button type="button" id="btn-reset-filter" class="btn btn-light btn-block">Reset</button>
			</div>
		</div>
		<small class="text-muted">Kegiatan aktif saat ini: <strong><?= html_escape($kegiatan_aktif_label) ?></strong> | Filter dipilih: <strong id="selected-event-label"><?= html_escape($selected_event_label) ?></strong></small>
	</div>

	<div class="btm-stats">
		<div class="btm-stat">
			<div class="num" id="stat-total-data"><?= $total_data ?></div>
			<div class="txt">Total Data (Filter)</div>
		</div>
		<div class="btm-stat">
			<div class="num"><?= (int) $totalIsi ?></div>
			<div class="txt">Total Semua Data</div>
		</div>
		<div class="btm-stat">
			<div class="num" id="stat-total-hari-ini"><?= $total_hari_ini ?></div>
			<div class="txt">Pengisi Hari Ini</div>
		</div>
		<div class="btm-stat">
			<div class="num" id="stat-instansi-unik"><?= $total_instansi_unik ?></div>
			<div class="txt">Instansi Unik</div>
		</div>
		<div class="btm-stat">
			<div class="num" id="stat-nomorwa-unik"><?= $total_nomorwa_unik ?></div>
			<div class="txt">Nomor WA Unik</div>
		</div>
	</div>
	<div class="btm-stat-foot">Data terakhir masuk: <strong id="stat-last-input"><?= $last_input_label ?></strong></div>

	<div class="btm-table-card">
		<div class="table-responsive">
			<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
				<thead>
					<tr>
						<th> # </th>
						<th> Nama</th>
						<th> Nomor WhatsApp </th>
						<th> Email </th>
						<th> Jabatan </th>
						<th> Instansi </th>
						<th> Tipe RS </th>
						<th> Provinsi </th>
						<th> Kota </th>
						<th> Tanggal </th>
						<th> Kegiatan </th>
						<th> Kebutuhan </th>
						<th class="text-nowrap text-center" style="width: 140px; min-width: 140px;"> Aksi </th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>


<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Buku Tamu</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama Kegiatan <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama" name="nama" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_brosur" name="id_brosur">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>



<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Cetak Buku Tamu </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-marketing-form">
					<!--<div class="form-group" style="display: flex;">
				        <div style="flex: 50%;padding: 10px;">
						<input class="form-control"  data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
						</div>
						<div style="flex: 50%;padding: 10px;">
						<input class="form-control"  data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
						</div>
					</div>-->
					<div class="form-group">
						<label class="control-label">Nama Kegiatan</label>
						<select class="select-transaction input-group-sm form-control" name="namamarketing" id="namamarketing">
							<?php if ($nama_kegiatan != NULL): ?>
								<option value=''>Semua Kegiatan</option>
								<?php foreach ($nama_kegiatan as $value): ?>
									<option value="<?php echo $value->nama; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('nama_marketing'); ?>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-export" class="btn btn-success btn-clear-form">Export Excel</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>




<div id="modal-tamu-manual" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="manualTamuModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="icons icon-user-follow text-grey-light"></i> Input Tamu Manual</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'form-tamu-manual', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="form-group">
					<label for="manual_kegiatan_id" class="form-control-label font-weight-bold">Pilih Event / Kegiatan <span class="text-danger">*</span> :</label>
					<select class="form-control" id="manual_kegiatan_id" name="kegiatan_id" required>
						<option value="">- Pilih Kegiatan -</option>
						<?php if (!empty($nama_kegiatan)): ?>
							<?php foreach ($nama_kegiatan as $value): ?>
								<option value="<?= (int) $value->id ?>"><?= html_escape($value->nama) ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="manual_nama" class="form-control-label font-weight-bold">Nama Lengkap <span class="text-danger">*</span> :</label>
					<input type="text" class="form-control" id="manual_nama" name="nama" placeholder="Contoh: Budi Santoso" required>
				</div>
				<div class="form-group">
					<label for="manual_nomorwa" class="form-control-label font-weight-bold">Nomor WhatsApp <span class="text-danger">*</span> :</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fab fa-whatsapp text-success"></i></span>
						</div>
						<input type="text" class="form-control" id="manual_nomorwa" name="nomorwa" placeholder="Contoh: 081234567890 atau 628123456789" required>
					</div>
				</div>
				<div class="form-group">
					<label for="manual_email" class="form-control-label font-weight-bold">Email (Opsional) :</label>
					<input type="email" class="form-control" id="manual_email" name="email" placeholder="Contoh: budi@gmail.com">
				</div>
				<div class="form-group">
					<label for="manual_jabatan" class="form-control-label font-weight-bold">Jabatan <span class="text-danger">*</span> :</label>
					<input type="text" class="form-control" id="manual_jabatan" name="jabatan" placeholder="Contoh: Kepala IT / Staf" required>
				</div>
				<div class="form-group">
					<label for="manual_instansi" class="form-control-label font-weight-bold">Instansi <span class="text-danger">*</span> :</label>
					<input type="text" class="form-control" id="manual_instansi" name="instansi" placeholder="Contoh: RSUD Pasar Minggu" required>
				</div>
				<div class="form-group">
					<label for="manual_tipe_rs" class="form-control-label font-weight-bold">Tipe RS :</label>
					<select class="form-control" id="manual_tipe_rs" name="tipe_rs">
						<option value="">- Pilih Tipe RS -</option>
						<option value="A">Tipe A</option>
						<option value="B">Tipe B</option>
						<option value="C">Tipe C</option>
						<option value="D">Tipe D</option>
						<option value="Non-RS">Non-RS</option>
					</select>
				</div>
				<div class="form-group">
					<label for="manual_provinsi" class="form-control-label font-weight-bold">Provinsi <span class="text-danger">*</span> :</label>
					<select class="form-control" id="manual_provinsi" name="provinsi" required>
						<option value="">— Pilih Provinsi —</option>
						<?php foreach ($provinsi as $value): ?>
							<option value="<?= $value->id ?>"><?= htmlspecialchars($value->nama) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="manual_kota" class="form-control-label font-weight-bold">Kabupaten / Kota <span class="text-danger">*</span> :</label>
					<select class="form-control" id="manual_kota" name="kota" required>
						<option value="">- Pilih Kabupaten/Kota -</option>
					</select>
				</div>
				<div class="form-group">
					<label for="manual_kebutuhan" class="form-control-label font-weight-bold">Kebutuhan / Catatan :</label>
					<textarea class="form-control" id="manual_kebutuhan" name="kebutuhan" rows="3" placeholder="Ketik kebutuhan atau catatan tamu..."></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-info btn-save-tamu"><i class="fas fa-save"></i> Simpan Data Tamu</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<div id="modal-tamu-edit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="editTamuModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="icons icon-note text-grey-light"></i> Edit Data Tamu</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'form-tamu-edit', 'autocomplete' => 'off')); ?>
			<input type="hidden" id="edit_id_bukutamu" name="id_bukutamu">
			<div class="modal-body">
				<div class="form-group">
					<label for="edit_kegiatan_id" class="form-control-label font-weight-bold">Pilih Event / Kegiatan <span class="text-danger">*</span> :</label>
					<select class="form-control" id="edit_kegiatan_id" name="kegiatan_id" required>
						<option value="">- Pilih Kegiatan -</option>
						<?php if (!empty($nama_kegiatan)): ?>
							<?php foreach ($nama_kegiatan as $value): ?>
								<option value="<?= (int) $value->id ?>"><?= html_escape($value->nama) ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="edit_nama" class="form-control-label font-weight-bold">Nama Lengkap <span class="text-danger">*</span> :</label>
					<input type="text" class="form-control" id="edit_nama" name="nama" required>
				</div>
				<div class="form-group">
					<label for="edit_nomorwa" class="form-control-label font-weight-bold">Nomor WhatsApp <span class="text-danger">*</span> :</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fab fa-whatsapp text-success"></i></span>
						</div>
						<input type="text" class="form-control" id="edit_nomorwa" name="nomorwa" required>
					</div>
				</div>
				<div class="form-group">
					<label for="edit_email" class="form-control-label font-weight-bold">Email (Opsional) :</label>
					<input type="email" class="form-control" id="edit_email" name="email">
				</div>
				<div class="form-group">
					<label for="edit_jabatan" class="form-control-label font-weight-bold">Jabatan <span class="text-danger">*</span> :</label>
					<input type="text" class="form-control" id="edit_jabatan" name="jabatan" required>
				</div>
				<div class="form-group">
					<label for="edit_instansi" class="form-control-label font-weight-bold">Instansi <span class="text-danger">*</span> :</label>
					<input type="text" class="form-control" id="edit_instansi" name="instansi" required>
				</div>
				<div class="form-group">
					<label for="edit_tipe_rs" class="form-control-label font-weight-bold">Tipe RS :</label>
					<select class="form-control" id="edit_tipe_rs" name="tipe_rs">
						<option value="">- Pilih Tipe RS -</option>
						<option value="A">Tipe A</option>
						<option value="B">Tipe B</option>
						<option value="C">Tipe C</option>
						<option value="D">Tipe D</option>
						<option value="Non-RS">Non-RS</option>
					</select>
				</div>
				<div class="form-group">
					<label for="edit_provinsi" class="form-control-label font-weight-bold">Provinsi <span class="text-danger">*</span> :</label>
					<select class="form-control" id="edit_provinsi" name="provinsi" required>
						<option value="">— Pilih Provinsi —</option>
						<?php foreach ($provinsi as $value): ?>
							<option value="<?= $value->id ?>"><?= htmlspecialchars($value->nama) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="edit_kota" class="form-control-label font-weight-bold">Kabupaten / Kota <span class="text-danger">*</span> :</label>
					<select class="form-control" id="edit_kota" name="kota" required>
						<option value="">- Pilih Kabupaten/Kota -</option>
					</select>
				</div>
				<div class="form-group">
					<label for="edit_kebutuhan" class="form-control-label font-weight-bold">Kebutuhan / Catatan :</label>
					<textarea class="form-control" id="edit_kebutuhan" name="kebutuhan" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-primary btn-update-tamu"><i class="fas fa-save"></i> Simpan Perubahan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<div id="modal-qr-code" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.2);">
			<div class="modal-header bg-dark text-light" style="padding: 16px 20px;">
				<h5 class="modal-title font-weight-bold" id="qrModalLabel"><i class="fas fa-qrcode text-warning"></i>&nbsp; QR Code Buku Tamu</h5>
				<button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">&times;</button>
			</div>
			<div class="modal-body text-center" style="padding: 24px;">
				<div class="form-group text-left mb-3">
					<label for="qr_select_kegiatan" class="font-weight-bold" style="font-size: 0.85rem; color: #334155;">Pilih Agenda / Event:</label>
					<select class="form-control" id="qr_select_kegiatan">
						<?php if (!empty($nama_kegiatan)): ?>
							<?php foreach ($nama_kegiatan as $value): ?>
								<option value="<?= (int) $value->id ?>" <?= ((int) $value->id === $selected_kegiatan_id ? 'selected' : '') ?>><?= html_escape($value->nama) ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="p-3 mb-3" style="background: #f8fafc; border-radius: 14px; border: 2px dashed #0056b3; display: inline-block;">
					<div id="qr-loading-spinner" style="display: none; padding: 60px 0;">
						<i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
						<p class="text-muted small mt-2">Memuat QR Code...</p>
					</div>
					<img id="qr-modal-image" src="" alt="QR Code" style="width: 220px; height: 220px; display: block; border-radius: 8px; background: #fff; padding: 6px;" />
				</div>

				<h5 id="qr-modal-event-title" class="font-weight-bold text-dark mb-1" style="font-size: 1.15rem;"></h5>
				<p class="text-muted small mb-3">Scan QR code di atas untuk membuka formulir Buku Tamu di smartphone pengunjung.</p>

				<div class="input-group mb-3">
					<input type="text" id="qr-modal-link-input" class="form-control text-muted" readonly style="font-size: 0.85rem; background: #f1f5f9;">
					<div class="input-group-append">
						<button class="btn btn-outline-primary" type="button" id="btn-copy-qr-link"><i class="fas fa-copy"></i> Salin Link</button>
					</div>
				</div>

				<div class="d-flex justify-content-center flex-wrap" style="gap: 8px;">
					<a href="#" id="btn-print-standee" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-print"></i>&nbsp; Cetak Standee Meja</a>
					<a href="#" id="btn-download-qr-img" target="_blank" download class="btn btn-success btn-sm"><i class="fas fa-download"></i>&nbsp; Download QR</a>
					<a href="#" id="btn-test-form-link" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-external-link-alt"></i>&nbsp; Buka Form</a>
				</div>
			</div>
			<div class="modal-footer" style="padding: 12px 20px; background: #f8fafc;">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		var $filterKegiatan = $('#filter_kegiatan');
		var $filterTanggalMulai = $('#filter_tanggal_mulai');
		var $filterTanggalSelesai = $('#filter_tanggal_selesai');

		function getFilterPayload() {
			return {
				kegiatan_id: $filterKegiatan.val(),
				tanggal_mulai: $filterTanggalMulai.val(),
				tanggal_selesai: $filterTanggalSelesai.val()
			};
		}

		function validateDateRange(showAlert) {
			var mulai = $filterTanggalMulai.val();
			var selesai = $filterTanggalSelesai.val();

			if (mulai && selesai && mulai > selesai) {
				if (showAlert) {
					Swal.fire('Validasi', 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.', 'warning');
				}
				return false;
			}

			return true;
		}

		function updateSelectedEventLabel(label) {
			$('#selected-event-label').text(label || 'Semua Kegiatan');
		}

		function refreshSelectedEventFromFilter() {
			var label = $filterKegiatan.find('option:selected').text();
			updateSelectedEventLabel(label);
		}

		function updateStatCards(stats) {
			$('#stat-total-data').text(stats.total_data || 0);
			$('#stat-total-hari-ini').text(stats.total_hari_ini || 0);
			$('#stat-instansi-unik').text(stats.total_instansi_unik || 0);
			$('#stat-nomorwa-unik').text(stats.total_nomorwa_unik || 0);
			$('#stat-last-input').text(stats.last_input_label || '-');
			if (stats.event_label) {
				updateSelectedEventLabel(stats.event_label);
			}
		}

		function loadStatistik() {
			if (!validateDateRange(false)) {
				return;
			}

			var payload = getFilterPayload();
			payload.csrf_token = token;

			$.ajax({
				url: 'BukuTamu/statistik',
				type: 'POST',
				dataType: 'JSON',
				data: payload,
				success: function(resp) {
					if (resp && resp.status === 'ok' && resp.data) {
						updateStatCards(resp.data);
					}
				}
			});
		}

		table = $('#kt_table_1').DataTable({
			responsive: false,
			scrollX: true,
			processing: true,
			serverSide: true,
			order: [
				[9, 'desc']
			],
			ajax: {
				url: 'BukuTamu/pagination',
				type: 'POST',
				data: function(e) {
					var payload = getFilterPayload();
					e.kegiatan_id = payload.kegiatan_id;
					e.tanggal_mulai = payload.tanggal_mulai;
					e.tanggal_selesai = payload.tanggal_selesai;
					e.csrf_token = token;
				}
			},
			columnDefs: [{
				targets: [0, 9, 12],
				className: 'text-center'
			}],
			language: {
				search: 'Cari:',
				emptyTable: 'Belum ada data buku tamu.',
				zeroRecords: 'Data tidak ditemukan.',
				lengthMenu: 'Tampilkan _MENU_ data',
				info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
				infoEmpty: 'Menampilkan 0 data',
				paginate: {
					first: '<<',
					last: '>>',
					next: '>',
					previous: '<'
				}
			}
		});

		function applyFilter() {
			if (!validateDateRange(true)) {
				return;
			}

			refreshSelectedEventFromFilter();
			updateDatatable();
			loadStatistik();
		}

		$('#btn-apply-filter').click(function() {
			applyFilter();
		});

		$('#btn-reset-filter').click(function() {
			$filterKegiatan.val('');
			$filterTanggalMulai.val('');
			$filterTanggalSelesai.val('');
			applyFilter();
		});

		$filterKegiatan.change(function() {
			applyFilter();
		});

		$filterTanggalMulai.add($filterTanggalSelesai).change(function() {
			applyFilter();
		});

		refreshSelectedEventFromFilter();
		loadStatistik();

		// QR Code Modal Loader
		function loadQrModalData(kegiatanId) {
			$('#qr-modal-image').hide();
			$('#qr-loading-spinner').show();
			$('#qr-modal-event-title').text('Memuat...');
			$('#qr-modal-link-input').val('');

			$.ajax({
				url: '<?= base_url('BukuTamu/getQrModalJson') ?>',
				type: 'GET',
				data: { kegiatan_id: kegiatanId },
				dataType: 'JSON',
				success: function(res) {
					$('#qr-loading-spinner').hide();
					if (res.status === 'success') {
						$('#qr-modal-image').attr('src', res.qr_image_url).fadeIn();
						$('#qr-modal-event-title').text(res.kegiatan_nama);
						$('#qr-modal-link-input').val(res.target_url);
						$('#btn-print-standee').attr('href', res.print_url);
						$('#btn-download-qr-img').attr('href', res.qr_image_url);
						$('#btn-test-form-link').attr('href', res.target_url);
					} else {
						Swal.fire('Gagal!', res.message, 'error');
					}
				},
				error: function() {
					$('#qr-loading-spinner').hide();
					Swal.fire('Gagal!', 'Gagal memuat data QR Code.', 'error');
				}
			});
		}

		$('#btn-show-qr-modal').click(function() {
			var filterVal = $filterKegiatan.val();
			if (filterVal) {
				$('#qr_select_kegiatan').val(filterVal);
			} else {
				var firstVal = $('#qr_select_kegiatan option:first').val();
				if (firstVal) {
					$('#qr_select_kegiatan').val(firstVal);
				}
			}
			loadQrModalData($('#qr_select_kegiatan').val());
			$('#modal-qr-code').modal('show');
		});

		$('#qr_select_kegiatan').on('change', function() {
			loadQrModalData(this.value);
		});

		$('#btn-copy-qr-link').click(function() {
			var copyText = document.getElementById("qr-modal-link-input");
			copyText.select();
			copyText.setSelectionRange(0, 99999);
			if (navigator.clipboard) {
				navigator.clipboard.writeText(copyText.value);
			} else {
				document.execCommand("copy");
			}

			var $btn = $(this);
			var originalHtml = $btn.html();
			$btn.html('<i class="fas fa-check"></i> Tersalin!').removeClass('btn-outline-primary').addClass('btn-success');
			setTimeout(function() {
				$btn.html(originalHtml).removeClass('btn-success').addClass('btn-outline-primary');
			}, 2000);
		});

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'BukuTamu/addKegiatan')
			$('#main-modal').modal()
		})

		$('#btn-cetaklaporan-form').click(function() {
			var labelKegiatan = $filterKegiatan.find('option:selected').text();
			if ($filterKegiatan.val()) {
				$('#namamarketing').val(labelKegiatan);
			} else {
				$('#namamarketing').val('');
			}
			$('#main-modal-marketing').modal()


		})

		$("#btn-export").click(function() {

			//tglawal = $("#tglawal").val();
			//tglakhir = $("#tglakhir").val(); 
			//window.open("<?php echo base_url(); ?>BukuTamu/exportlaporan/search?tglawal="+encodeURIComponent(tglawal)+"&tglakhir="+encodeURIComponent(tglakhir)+"&idmarketing="+encodeURIComponent(idmarketing)+"&namamarketing="+encodeURIComponent(namamarketing),"_blank");

			idmarketing = $("#namamarketing").val();
			namamarketing = $("#namamarketing option:selected").text();
			window.open("<?php echo base_url(); ?>BukuTamu/exportlaporan/search?idmarketing=" + encodeURIComponent(idmarketing) + "&namamarketing=" + encodeURIComponent(namamarketing), "_blank");
			$('#main-modal-marketing').modal('hide')

		});

		// Show manual guest input modal
		$('#btn-show-tamu-form').click(function() {
			$('#form-tamu-manual')[0].reset();
			// Pre-select event from filter if one is selected
			var filterVal = $('#filter_kegiatan').val();
			if (filterVal) {
				$('#manual_kegiatan_id').val(filterVal);
			} else {
				$('#manual_kegiatan_id').val('');
			}
			$('#modal-tamu-manual').modal('show');
		});

		// Submit manual guest form via AJAX
		$('#form-tamu-manual').on('submit', function(e) {
			e.preventDefault();

			var $form = $(this);
			var $submitBtn = $form.find('.btn-save-tamu');
			var originalBtnHtml = $submitBtn.html();

			$submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

			var formData = $form.serializeArray();
			formData.push({
				name: '<?= $this->security->get_csrf_token_name() ?>',
				value: '<?= $this->security->get_csrf_hash() ?>'
			});

			$.ajax({
				url: '<?= base_url('BukuTamu/addTamuManual') ?>',
				type: 'POST',
				dataType: 'JSON',
				data: formData,
				success: function(res) {
					$submitBtn.prop('disabled', false).html(originalBtnHtml);
					if (res.status === 'success') {
						Swal.fire({
							title: 'Berhasil!',
							text: res.message,
							icon: 'success',
							timer: 2000,
							showConfirmButton: false
						});
						$('#modal-tamu-manual').modal('hide');
						updateDatatable();
						loadStatistik();
					} else {
						Swal.fire('Gagal!', res.message, 'error');
					}
				},
				error: function(xhr, status, error) {
					$submitBtn.prop('disabled', false).html(originalBtnHtml);
					Swal.fire('Gagal!', 'Terjadi kesalahan sistem: ' + (xhr.responseJSON?.message || error || 'Gagal terhubung ke server'), 'error');
				}
			});
		});

		// Province to City loader in manual modal
		$('#manual_provinsi').on('change', function() {
			var provId = this.value;
			var $kota = $('#manual_kota');
			$kota.html('<option value="">Loading...</option>');
			if (provId) {
				$.ajax({
					url: '<?= base_url('BukuTamu/add_ajax_kota') ?>/' + provId,
					type: 'GET',
					success: function(html) {
						$kota.html(html);
					},
					error: function() {
						$kota.html('<option value="">Gagal memuat data</option>');
					}
				});
			} else {
				$kota.html('<option value="">- Pilih Kabupaten/Kota -</option>');
			}
		});

		// Province to City loader in edit modal
		$('#edit_provinsi').on('change', function() {
			var provId = this.value;
			var $kota = $('#edit_kota');
			$kota.html('<option value="">Loading...</option>');
			if (provId) {
				$.ajax({
					url: '<?= base_url('BukuTamu/add_ajax_kota') ?>/' + provId,
					type: 'GET',
					success: function(html) {
						$kota.html(html);
					},
					error: function() {
						$kota.html('<option value="">Gagal memuat data</option>');
					}
				});
			} else {
				$kota.html('<option value="">- Pilih Kabupaten/Kota -</option>');
			}
		});

		// Edit button click handler
		$('#kt_table_1').on('click', '.btn-edit-tamu', function() {
			var id = $(this).data('id');

			$.ajax({
				url: '<?= base_url('BukuTamu/getTamuJson') ?>/' + id,
				type: 'GET',
				dataType: 'JSON',
				success: function(res) {
					if (res.status === 'success') {
						var data = res.data;
						$('#edit_id_bukutamu').val(id);
						$('#edit_kegiatan_id').val(data.kegiatan);
						$('#edit_nama').val(data.nama);
						$('#edit_nomorwa').val(data.nomorwa);
						$('#edit_email').val(data.email);
						$('#edit_jabatan').val(data.jabatan);
						$('#edit_instansi').val(data.instansi);
						$('#edit_tipe_rs').val(data.tipe_rs);
						$('#edit_kebutuhan').val(data.kebutuhan);

						// Handle province / city selection matching text names
						var savedProv = data.provinsi;
						var savedKota = data.kota;

						var provOption = $('#edit_provinsi option').filter(function() {
							return $(this).text().trim().toLowerCase() === (savedProv || '').trim().toLowerCase();
						});

						if (provOption.length > 0) {
							var provId = provOption.val();
							$('#edit_provinsi').val(provId);

							// Fetch and load cities, then match city text
							$.ajax({
								url: '<?= base_url('BukuTamu/add_ajax_kota') ?>/' + provId,
								type: 'GET',
								success: function(html) {
									$('#edit_kota').html(html);

									var kotaOption = $('#edit_kota option').filter(function() {
										return $(this).text().trim().toLowerCase() === (savedKota || '').trim().toLowerCase();
									});
									if (kotaOption.length > 0) {
										$('#edit_kota').val(kotaOption.val());
									} else {
										$('#edit_kota').val('');
									}
								}
							});
						} else {
							$('#edit_provinsi').val('');
							$('#edit_kota').html('<option value="">- Pilih Kabupaten/Kota -</option>');
						}

						$('#modal-tamu-edit').modal('show');
					} else {
						Swal.fire('Gagal!', res.message, 'error');
					}
				},
				error: function() {
					Swal.fire('Gagal!', 'Terjadi kesalahan sistem saat memuat data.', 'error');
				}
			});
		});

		// Submit edit form via AJAX
		$('#form-tamu-edit').on('submit', function(e) {
			e.preventDefault();

			var $form = $(this);
			var $submitBtn = $form.find('.btn-update-tamu');
			var originalBtnHtml = $submitBtn.html();

			$submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

			var formData = $form.serializeArray();
			formData.push({
				name: '<?= $this->security->get_csrf_token_name() ?>',
				value: '<?= $this->security->get_csrf_hash() ?>'
			});

			$.ajax({
				url: '<?= base_url('BukuTamu/saveEditTamu') ?>',
				type: 'POST',
				dataType: 'JSON',
				data: formData,
				success: function(res) {
					$submitBtn.prop('disabled', false).html(originalBtnHtml);
					if (res.status === 'success') {
						Swal.fire({
							title: 'Berhasil!',
							text: res.message,
							icon: 'success',
							timer: 2000,
							showConfirmButton: false
						});
						$('#modal-tamu-edit').modal('hide');
						updateDatatable();
						loadStatistik();
					} else {
						Swal.fire('Gagal!', res.message, 'error');
					}
				},
				error: function(xhr, status, error) {
					$submitBtn.prop('disabled', false).html(originalBtnHtml);
					Swal.fire('Gagal!', 'Terjadi kesalahan sistem: ' + (xhr.responseJSON?.message || error || 'Gagal terhubung ke server'), 'error');
				}
			});
		});

		// Delete button click handler
		$('#kt_table_1').on('click', '.btn-delete-tamu', function() {
			var id = $(this).data('id');

			Swal.fire({
				title: 'Apakah anda yakin?',
				text: 'Data tamu ini akan dihapus permanen dari sistem!',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, hapus!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: '<?= base_url('BukuTamu/deleteTamu') ?>/' + id,
						type: 'POST',
						data: {
							'<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>'
						},
						dataType: 'JSON',
						success: function(res) {
							if (res.status === 'success') {
								Swal.fire('Berhasil!', res.message, 'success');
								updateDatatable();
								loadStatistik();
							} else {
								Swal.fire('Gagal!', res.message, 'error');
							}
						},
						error: function() {
							Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');
						}
					});
				}
			});
		});
	});

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>