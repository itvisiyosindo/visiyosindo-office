<header class="page-header">
	<h2><i class="fas fa-star-half-alt"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<style>
	.survey-kpi-card {
		border-radius: 12px;
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
		transition: all 0.3s ease;
		border: 1px solid #e2e8f0;
		background: #ffffff;
		padding: 20px;
		margin-bottom: 20px;
	}
	.survey-kpi-card:hover {
		transform: translateY(-4px);
		box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
	}
	.survey-kpi-icon {
		width: 50px;
		height: 50px;
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 22px;
	}
	.survey-kpi-val {
		font-size: 1.85rem;
		font-weight: 700;
		line-height: 1.2;
		color: #1e293b;
	}
	.survey-kpi-title {
		font-size: 0.82rem;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		color: #64748b;
		font-weight: 600;
	}

	.table-survey thead th {
		background-color: #1e3a8a !important;
		color: #ffffff !important;
		text-align: center;
		vertical-align: middle;
		font-size: 13px;
		font-weight: 600;
		border: none;
	}
	.table-survey tbody td {
		vertical-align: middle;
		font-size: 13px;
	}

	/* Detail stars display */
	.rating-item-box {
		background: #f8fafc;
		border: 1px solid #e2e8f0;
		border-radius: 8px;
		padding: 12px 16px;
		margin-bottom: 10px;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}
	.rating-item-label {
		font-weight: 600;
		color: #334155;
		font-size: 13.5px;
	}
	.rating-item-stars {
		display: flex;
		align-items: center;
		gap: 4px;
	}
	.rating-item-stars i {
		font-size: 16px;
	}
	.score-badge {
		background: #fef3c7;
		color: #92400e;
		font-weight: 700;
		padding: 2px 8px;
		border-radius: 6px;
		font-size: 12px;
		margin-left: 8px;
		border: 1px solid #fde68a;
	}
</style>

<!-- KPI Cards -->
<div class="row">
	<div class="col-md-3 col-sm-6">
		<div class="survey-kpi-card d-flex align-items-center">
			<div class="survey-kpi-icon mr-3" style="background:#eff6ff; color:#2563eb;">
				<i class="fas fa-paper-plane"></i>
			</div>
			<div>
				<div class="survey-kpi-val" id="stat-terkirim"><?= number_format($statistics['total_terkirim'] ?? 0) ?></div>
				<div class="survey-kpi-title">Total Terkirim</div>
			</div>
		</div>
	</div>

	<div class="col-md-3 col-sm-6">
		<div class="survey-kpi-card d-flex align-items-center">
			<div class="survey-kpi-icon mr-3" style="background:#ecfdf5; color:#059669;">
				<i class="fas fa-check-double"></i>
			</div>
			<div>
				<div class="survey-kpi-val text-success" id="stat-selesai"><?= number_format($statistics['total_selesai'] ?? 0) ?></div>
				<div class="survey-kpi-title">Respon Selesai</div>
			</div>
		</div>
	</div>

	<div class="col-md-3 col-sm-6">
		<div class="survey-kpi-card d-flex align-items-center">
			<div class="survey-kpi-icon mr-3" style="background:#fffbeb; color:#d97706;">
				<i class="fas fa-star"></i>
			</div>
			<div>
				<div class="survey-kpi-val text-warning" id="stat-avg">
					<?= number_format($statistics['avg_all'] ?? 0, 2) ?> <small style="font-size:1rem; color:#64748b;">/ 5.0</small>
				</div>
				<div class="survey-kpi-title">Rata-rata Skor Bintang</div>
			</div>
		</div>
	</div>

	<div class="col-md-3 col-sm-6">
		<div class="survey-kpi-card d-flex align-items-center">
			<div class="survey-kpi-icon mr-3" style="background:#fef2f2; color:#dc2626;">
				<i class="fas fa-exclamation-triangle"></i>
			</div>
			<div>
				<div class="survey-kpi-val text-danger" id="stat-tindaklanjut"><?= number_format($statistics['butuh_tindak_lanjut'] ?? 0) ?></div>
				<div class="survey-kpi-title">Butuh Tindak Lanjut</div>
			</div>
		</div>
	</div>
</div>

<!-- Filter Section -->
<div class="card mb-4" style="border-radius:10px; border:1px solid #e2e8f0;">
	<div class="card-body">
		<div class="row align-items-end">
			<div class="col-md-2 col-sm-6 mb-2">
				<label class="form-control-label small font-weight-bold">Status Survey</label>
				<select id="filter_status" class="form-control form-control-sm">
					<option value="">Semua Status</option>
					<option value="Selesai">Selesai</option>
					<option value="Pending">Pending (Menunggu)</option>
				</select>
			</div>

			<div class="col-md-2 col-sm-6 mb-2">
				<label class="form-control-label small font-weight-bold">Status Tindak Lanjut</label>
				<select id="filter_status_tl" class="form-control form-control-sm">
					<option value="">Semua Status</option>
					<option value="Belum Ditindaklanjuti">Belum Ditindaklanjuti</option>
					<option value="Sedang Ditindaklanjuti">Sedang Ditindaklanjuti</option>
					<option value="Selesai">Selesai</option>
				</select>
			</div>

			<div class="col-md-2 col-sm-6 mb-2">
				<label class="form-control-label small font-weight-bold">Tgl Awal</label>
				<input type="date" id="filter_tgl_awal" class="form-control form-control-sm">
			</div>

			<div class="col-md-2 col-sm-6 mb-2">
				<label class="form-control-label small font-weight-bold">Tgl Akhir</label>
				<input type="date" id="filter_tgl_akhir" class="form-control form-control-sm">
			</div>

			<div class="col-md-4 col-sm-12 mb-2 d-flex justify-content-end align-items-center" style="gap:6px;">
				<button type="button" id="btn-filter" class="btn btn-sm btn-primary">
					<i class="fas fa-filter mr-1"></i> Filter
				</button>
				<button type="button" id="btn-reset-filter" class="btn btn-sm btn-outline-secondary">
					<i class="fas fa-undo mr-1"></i> Reset
				</button>
				<button type="button" id="btn-export-excel" class="btn btn-sm btn-success">
					<i class="fas fa-file-excel mr-1"></i> Excel
				</button>
				<a href="<?= base_url('pelanggan') ?>" class="btn btn-sm btn-info">
					<i class="fas fa-users mr-1"></i> Data Pelanggan
				</a>
			</div>
		</div>
	</div>
</div>

<!-- Table Data -->
<div class="card" style="border-radius:10px; border:1px solid #e2e8f0;">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-striped table-survey w-100" id="table-survey-monitoring">
				<thead>
					<tr>
						<th width="40">No</th>
						<th width="140">Waktu</th>
						<th>Pelanggan & PIC</th>
						<th width="130">Kontak WA</th>
						<th width="140">Rating Bintang</th>
						<th width="100">Status</th>
						<th width="110">Tindak Lanjut</th>
						<th>Saran & Masukan</th>
						<th width="110">Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal Detail Survey -->
<div class="modal fade" id="modalDetailSurvey" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" style="border-radius:12px; overflow:hidden;">
			<div class="modal-header" style="background: linear-gradient(135deg, #1e3a8a, #2563eb); color:#fff;">
				<h5 class="modal-title font-weight-bold">
					<i class="fas fa-clipboard-check mr-2"></i> Detail Penilaian Survey Kepuasan
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="background:#f8fafc; padding:24px;">
				<div class="card mb-3 border-0 shadow-sm">
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<table class="table table-sm table-borderless mb-0">
									<tr>
										<td class="text-muted" width="120">Pelanggan:</td>
										<td class="font-weight-bold" id="detail-nama-pelanggan">-</td>
									</tr>
									<tr>
										<td class="text-muted">PIC:</td>
										<td class="font-weight-bold" id="detail-nama-pic">-</td>
									</tr>
									<tr>
										<td class="text-muted">No. WhatsApp:</td>
										<td id="detail-no-wa">-</td>
									</tr>
								</table>
							</div>
							<div class="col-md-6">
								<table class="table table-sm table-borderless mb-0">
									<tr>
										<td class="text-muted" width="130">Tgl Dikirim:</td>
										<td id="detail-tgl-kirim">-</td>
									</tr>
									<tr>
										<td class="text-muted">Tgl Diisi:</td>
										<td id="detail-tgl-submit">-</td>
									</tr>
									<tr>
										<td class="text-muted">Skor Rata-rata:</td>
										<td>
											<span class="badge badge-warning" id="detail-rata-rata" style="background:#fef3c7; color:#92400e; font-size:14px; font-weight:700; border:1px solid #fde68a;">
												-
											</span>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>

				<h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-star text-warning mr-1"></i> Rincian 6 Poin Penilaian Pelanggan</h6>

				<div class="rating-item-box">
					<div class="rating-item-label">1. Kualitas pelayanan secara keseluruhan</div>
					<div class="rating-item-stars" id="stars-p1"></div>
				</div>

				<div class="rating-item-box">
					<div class="rating-item-label">2. Kecepatan respons terhadap kebutuhan pelanggan</div>
					<div class="rating-item-stars" id="stars-p2"></div>
				</div>

				<div class="rating-item-box">
					<div class="rating-item-label">3. Keramahan dan profesionalisme tim</div>
					<div class="rating-item-stars" id="stars-p3"></div>
				</div>

				<div class="rating-item-box">
					<div class="rating-item-label">4. Kecepatan penanganan keluhan</div>
					<div class="rating-item-stars" id="stars-p4"></div>
				</div>

				<div class="rating-item-box">
					<div class="rating-item-label">5. Kualitas layanan teknis dan purna jual</div>
					<div class="rating-item-stars" id="stars-p5"></div>
				</div>

				<div class="rating-item-box">
					<div class="rating-item-label">6. Kepuasan terhadap tindak lanjut perusahaan</div>
					<div class="rating-item-stars" id="stars-p6"></div>
				</div>

				<!-- Masukan & Saran -->
				<div class="card mt-3 border-0 shadow-sm">
					<div class="card-body">
						<h6 class="font-weight-bold text-dark mb-2"><i class="far fa-comment-dots text-primary mr-1"></i> Saran & Masukan dari Pelanggan:</h6>
						<div id="detail-saran" class="p-3 bg-light rounded text-muted font-italic" style="border-left:4px solid #2563eb;">
							Tidak ada saran atau masukan.
						</div>
					</div>
				</div>

				<!-- Riwayat Tindak Lanjut -->
				<div class="card mt-3 border-0 shadow-sm">
					<div class="card-body">
						<h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-clipboard-list text-info mr-1"></i> Status Tindak Lanjut Tim:</h6>
						<div class="d-flex align-items-center mb-2">
							<span class="mr-2 text-muted">Status:</span>
							<span id="detail-status-tl">-</span>
							<span class="ml-3 text-muted" id="detail-tl-by">-</span>
						</div>
						<div id="detail-catatan-tl" class="p-2 bg-light rounded small text-dark">
							Belum ada catatan tindak lanjut.
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer bg-white">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-warning btn-sm" id="btn-to-tindaklanjut"><i class="fas fa-edit mr-1"></i> Update Tindak Lanjut</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Update Tindak Lanjut -->
<div class="modal fade" id="modalTindakLanjut" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="border-radius:12px; overflow:hidden;">
			<div class="modal-header bg-warning text-dark">
				<h5 class="modal-title font-weight-bold">
					<i class="fas fa-tasks mr-2"></i> Update Tindak Lanjut Survey
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form-update-tindaklanjut">
				<input type="hidden" name="id" id="tl_survey_id">
				<div class="modal-body">
					<div class="alert alert-info py-2 px-3 small mb-3">
						<strong id="tl_nama_pelanggan">-</strong><br>
						<span id="tl_masukan_preview" class="font-italic text-muted">-</span>
					</div>

					<div class="form-group mb-3">
						<label class="font-weight-bold small">Status Tindak Lanjut <span class="text-danger">*</span></label>
						<select name="status_tindak_lanjut" id="tl_status" class="form-control" required>
							<option value="Belum Ditindaklanjuti">Belum Ditindaklanjuti</option>
							<option value="Sedang Ditindaklanjuti">Sedang Ditindaklanjuti</option>
							<option value="Selesai">Selesai</option>
						</select>
					</div>

					<div class="form-group mb-2">
						<label class="font-weight-bold small">Catatan Tindak Lanjut / Respon Manajemen</label>
						<textarea name="catatan_tindak_lanjut" id="tl_catatan" class="form-control" rows="4" placeholder="Tuliskan tindakan yang telah dilakukan untuk menanggapi masukan/keluhan pelanggan ini..."></textarea>
					</div>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary btn-sm" id="btn-save-tl">
						<i class="fas fa-save mr-1"></i> Simpan Tindak Lanjut
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var table = $('#table-survey-monitoring').DataTable({
		processing: true,
		ajax: {
			url: '<?= base_url('survey_monitoring/ajax_list') ?>',
			type: 'POST',
			data: function(d) {
				d.status = $('#filter_status').val();
				d.status_tindak_lanjut = $('#filter_status_tl').val();
				d.tgl_awal = $('#filter_tgl_awal').val();
				d.tgl_akhir = $('#filter_tgl_akhir').val();
			}
		},
		order: [[0, 'asc']],
		pageLength: 25,
		language: {
			emptyTable: "Belum ada data survey yang tercatat",
			processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i> Memuat data...'
		}
	});

	function refreshStats() {
		$.ajax({
			url: '<?= base_url('survey_monitoring/get_statistics') ?>',
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				if (res) {
					$('#stat-terkirim').text(res.total_terkirim || 0);
					$('#stat-selesai').text(res.total_selesai || 0);
					$('#stat-avg').html((res.avg_all || 0).toFixed(2) + ' <small style="font-size:1rem; color:#64748b;">/ 5.0</small>');
					$('#stat-tindaklanjut').text(res.butuh_tindak_lanjut || 0);
				}
			}
		});
	}

	$('#btn-filter').on('click', function() {
		table.ajax.reload();
		refreshStats();
	});

	$('#btn-reset-filter').on('click', function() {
		$('#filter_status').val('');
		$('#filter_status_tl').val('');
		$('#filter_tgl_awal').val('');
		$('#filter_tgl_akhir').val('');
		table.ajax.reload();
		refreshStats();
	});

	$('#btn-export-excel').on('click', function() {
		var q = $.param({
			status: $('#filter_status').val(),
			status_tindak_lanjut: $('#filter_status_tl').val(),
			tgl_awal: $('#filter_tgl_awal').val(),
			tgl_akhir: $('#filter_tgl_akhir').val()
		});
		window.location.href = '<?= base_url('survey_monitoring/export_excel') ?>?' + q;
	});

	function renderStarsBox(score) {
		var s = parseInt(score) || 0;
		if (s === 0) {
			return '<span class="text-muted small">Belum diisi</span>';
		}
		var html = '';
		for (var i = 1; i <= 5; i++) {
			if (i <= s) {
				html += '<i class="fas fa-star text-warning" style="color:#f59e0b !important;"></i>';
			} else {
				html += '<i class="far fa-star text-muted"></i>';
			}
		}
		html += '<span class="score-badge">' + s + ' / 5</span>';
		return html;
	}

	var currentDetailData = null;

	// View detail modal
	$(document).on('click', '.btn-detail-survey', function() {
		var id = $(this).data('id');
		$.ajax({
			url: '<?= base_url('survey_monitoring/detail/') ?>' + id,
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				if (res.status === 'success') {
					var d = res.data;
					currentDetailData = d;
					$('#detail-nama-pelanggan').text(d.nama_pelanggan || '-');
					$('#detail-nama-pic').text(d.nama_pic || '-');
					$('#detail-no-wa').html(d.no_wa ? '<a href="https://api.whatsapp.com/send?phone=' + d.no_wa.replace(/[^0-9]/g, '') + '" target="_blank" class="text-success font-weight-bold"><i class="fab fa-whatsapp"></i> ' + d.no_wa + '</a>' : '-');
					$('#detail-tgl-kirim').text(d.created_at || '-');
					$('#detail-tgl-submit').text(d.submitted_at || 'Belum diisi');
					$('#detail-rata-rata').text(d.rata_rata ? '★ ' + parseFloat(d.rata_rata).toFixed(2) + ' / 5.0' : '-');

					$('#stars-p1').html(renderStarsBox(d.bintang_pelayanan));
					$('#stars-p2').html(renderStarsBox(d.bintang_kecepatan_respons));
					$('#stars-p3').html(renderStarsBox(d.bintang_keramahan));
					$('#stars-p4').html(renderStarsBox(d.bintang_penanganan_keluhan));
					$('#stars-p5').html(renderStarsBox(d.bintang_layanan_teknis));
					$('#stars-p6').html(renderStarsBox(d.bintang_kepuasan_tindaklanjut));

					if (d.saran_masukan && d.saran_masukan.trim() !== '') {
						$('#detail-saran').text('"' + d.saran_masukan + '"');
					} else {
						$('#detail-saran').html('<span class="text-muted">Tidak ada catatan saran atau keluhan yang disampaikan.</span>');
					}

					var stClass = d.status_tindak_lanjut === 'Selesai' ? 'badge-success' : (d.status_tindak_lanjut === 'Sedang Ditindaklanjuti' ? 'badge-info' : 'badge-danger');
					$('#detail-status-tl').html('<span class="badge ' + stClass + '">' + (d.status_tindak_lanjut || 'Belum Ditindaklanjuti') + '</span>');
					$('#detail-tl-by').text(d.tindak_lanjut_by ? 'Oleh: ' + d.tindak_lanjut_by + ' (' + d.tindak_lanjut_at + ')' : '');
					$('#detail-catatan-tl').text(d.catatan_tindak_lanjut || 'Belum ada catatan tindak lanjut.');

					$('#modalDetailSurvey').modal('show');
				}
			}
		});
	});

	// Quick switch from Detail modal to Tindak Lanjut modal
	$('#btn-to-tindaklanjut').on('click', function() {
		if (!currentDetailData) return;
		$('#modalDetailSurvey').modal('hide');
		openModalTindakLanjut(currentDetailData);
	});

	$(document).on('click', '.btn-tindaklanjut', function() {
		var id = $(this).data('id');
		$.ajax({
			url: '<?= base_url('survey_monitoring/detail/') ?>' + id,
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				if (res.status === 'success') {
					openModalTindakLanjut(res.data);
				}
			}
		});
	});

	function openModalTindakLanjut(data) {
		$('#tl_survey_id').val(data.id);
		$('#tl_nama_pelanggan').text(data.nama_pelanggan + (data.nama_pic ? ' (PIC: ' + data.nama_pic + ')' : ''));
		$('#tl_masukan_preview').text(data.saran_masukan ? 'Saran/Keluhan: "' + data.saran_masukan + '"' : 'Tidak ada catatan masukan khusus.');
		$('#tl_status').val(data.status_tindak_lanjut || 'Belum Ditindaklanjuti');
		$('#tl_catatan').val(data.catatan_tindak_lanjut || '');
		$('#modalTindakLanjut').modal('show');
	}

	$('#form-update-tindaklanjut').on('submit', function(e) {
		e.preventDefault();
		$('#btn-save-tl').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

		$.ajax({
			url: '<?= base_url('survey_monitoring/update_tindak_lanjut') ?>',
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(res) {
				$('#btn-save-tl').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Tindak Lanjut');
				if (res.status === 'success') {
					$('#modalTindakLanjut').modal('hide');
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: res.message,
						timer: 1800,
						showConfirmButton: false
					});
					table.ajax.reload(null, false);
					refreshStats();
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Gagal',
						text: res.message
					});
				}
			},
			error: function() {
				$('#btn-save-tl').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Tindak Lanjut');
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Terjadi kesalahan sistem saat menyimpan.'
				});
			}
		});
	});

	// Hapus data survey
	$(document).on('click', '.btn-delete-survey', function() {
		var id = $(this).data('id');
		var nama = $(this).data('nama') || 'ini';

		Swal.fire({
			title: 'Hapus Data Survey?',
			text: 'Data survey pelanggan "' + nama + '" akan dihapus permanen.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Ya, Hapus!',
			cancelButtonText: 'Batal'
		}).then(function(result) {
			if (result.isConfirmed || result.value) {
				$.post('<?= base_url('survey_monitoring/delete') ?>', { id: id }, function(res) {
					if (res && res.status === 'success') {
						Swal.fire({
							icon: 'success',
							title: 'Terhapus',
							text: res.message,
							timer: 1400,
							showConfirmButton: false
						});
						table.ajax.reload(null, false);
						refreshStats();
					} else {
						Swal.fire('Gagal', (res && res.message) ? res.message : 'Gagal menghapus data.', 'error');
					}
				}, 'json');
			}
		});
	});
});
</script>
