<header class="page-header">
	<h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>

	<style>
		.jobdesc-table th {
			background-color: #0056b3;
			color: #ffffff;
			vertical-align: middle !important;
			text-align: center;
			font-size: 13px;
			padding: 10px 8px;
		}
		.jobdesc-table td {
			vertical-align: middle !important;
			padding: 8px;
		}
		.drag-handle {
			cursor: grab;
			color: #64748b;
			padding: 6px 10px;
			border-radius: 4px;
			background-color: #f1f5f9;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			user-select: none;
		}
		.drag-handle:active {
			cursor: grabbing;
			background-color: #cbd5e1;
		}
		.ui-sortable-helper {
			display: table;
			background: #ffffff !important;
			box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
			border: 2px dashed #0056b3 !important;
		}
		.ui-sortable-placeholder {
			background-color: #e0f2fe !important;
			visibility: visible !important;
			height: 60px !important;
			border: 2px dashed #0284c7 !important;
		}
		.textarea-deskripsi {
			width: 100%;
			min-height: 48px;
			resize: vertical;
			font-size: 13px;
			line-height: 1.4;
			border-radius: 4px;
		}
		.no-urut-badge {
			display: inline-block;
			min-width: 28px;
			font-weight: 700;
			font-size: 13px;
			color: #003366;
		}
	</style>
</header>

<div class="row">
	<div class="col-12">
		<div class="card shadow-sm border-0 mb-4" style="background-color: #ffffff; border-radius: 8px;">
			<div class="card-header bg-light border-bottom py-3">
				<h4 class="card-title font-weight-bold mb-0 text-dark">
					<i class="fas fa-file-signature text-primary mr-2"></i> Form Kelengkapan Data Jobdesk
				</h4>
			</div>
			
			<div class="card-body p-4">
				<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
				
				<!-- Informasi Karyawan & Masa Berlaku -->
				<div class="row mb-4">
					<div class="col-md-5 mb-3">
						<label class="font-weight-bold text-dark">Nama Karyawan <span class="text-danger">*</span> :</label>
						<select name="id_pengguna" id="id_pengguna" class="form-control select2" style="width: 100%;" required>
							<option value="">-- Pilih Nama Karyawan --</option>
							<?php foreach ($list_nama as $row): ?>
								<option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="col-md-3 mb-3">
						<label class="font-weight-bold text-dark">Berlaku Dari Tanggal <span class="text-danger">*</span> :</label>
						<input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" value="<?= date('Y-m-d') ?>" required>
					</div>

					<div class="col-md-4 mb-3">
						<label class="font-weight-bold text-dark">Berlaku Sampai Tanggal :</label>
						<input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control" value="2099-12-31" required>
						<small class="text-muted d-block mt-1">* Biarkan 2099-12-31 jika berlaku seterusnya.</small>
					</div>
				</div>

				<hr class="mb-4">

				<!-- Tabel Input Poin Jobdesk -->
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h5 class="font-weight-bold text-dark mb-0">
						<i class="fas fa-list-ol text-primary mr-2"></i> Rincian Poin & Tugas Jobdesk
					</h5>
					<div>
						<button type="button" class="btn btn-sm btn-primary mr-1" id="btn-add-row">
							<i class="fas fa-plus mr-1"></i> Tambah Baris
						</button>
						<button type="button" class="btn btn-sm btn-outline-danger" id="btn-delete-last-row">
							<i class="fas fa-minus mr-1"></i> Hapus Baris Terakhir
						</button>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover jobdesc-table" id="kt_table_1" style="width: 100%;">
						<thead>
							<tr>
								<th width="8%">Urutan</th>
								<th width="12%">Point</th>
								<th width="56%">Deskripsi Tugas / Pekerjaan <span class="text-danger">*</span></th>
								<th width="18%">Penilaian</th>
								<th width="6%">Aksi</th>
							</tr>
						</thead>
						<tbody id="jobdesc_tbody">
							<!-- Baris default awal (5 baris) -->
							<?php for ($x = 1; $x <= 5; $x++): ?>
								<tr class="jobdesc-row">
									<td class="text-center">
										<div class="d-flex align-items-center justify-content-center">
											<span class="drag-handle mr-2" title="Klik dan geser (drag & drop) untuk memindahkan urutan">
												<i class="fas fa-grip-vertical"></i>
											</span>
											<span class="no-urut-badge row-index"><?= $x ?></span>
											<input type="hidden" class="input-idurut" value="<?= $x ?>">
										</div>
									</td>
									<td>
										<input type="text" class="form-control form-control-sm text-center input-point" placeholder="e.g. 1" value="1">
									</td>
									<td>
										<textarea class="form-control textarea-deskripsi input-deskripsi" rows="2" placeholder="Tulis rincian tugas / deskripsi pekerjaan..."></textarea>
									</td>
									<td>
										<select class="form-control form-control-sm input-nilai">
											<option value="1">Dinilai (Jobdesk Utama)</option>
											<option value="2" selected>Tidak Dinilai</option>
										</select>
									</td>
									<td class="text-center">
										<button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" title="Hapus baris ini">
											<i class="fas fa-trash"></i>
										</button>
									</td>
								</tr>
							<?php endfor; ?>
						</tbody>
					</table>
				</div>

				<div class="d-flex justify-content-between align-items-center mt-3">
					<button type="button" class="btn btn-sm btn-primary" id="btn-add-row-bottom">
						<i class="fas fa-plus mr-1"></i> Tambah Baris
					</button>
					<span class="text-muted small font-italic">
						<i class="fas fa-info-circle mr-1"></i> Anda dapat menggeser ikon <i class="fas fa-grip-vertical text-secondary"></i> (drag & drop) untuk mengatur ulang urutan tugas kapan saja.
					</span>
				</div>

				<?= form_close(); ?>
			</div>

			<div class="card-footer bg-light py-3 d-flex justify-content-between">
				<button type="button" onclick="goBack()" class="btn btn-secondary">
					<i class="fas fa-arrow-left mr-1"></i> Kembali
				</button>
				<button type="button" class="btn btn-success btn-ajukan px-4 font-weight-bold">
					<i class="fas fa-check mr-1"></i> Simpan Jobdesk
				</button>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Initialize Select2 jika tersedia
		if ($.fn.select2) {
			$('#id_pengguna').select2({
				placeholder: "-- Pilih Nama Karyawan --",
				allowClear: true
			});
		}

		// Fungsi Penomoran Ulang Otomatis (1..N)
		function renumberRows() {
			$('#jobdesc_tbody tr.jobdesc-row').each(function(index) {
				var newNum = index + 1;
				$(this).find('.row-index').text(newNum);
				$(this).find('.input-idurut').val(newNum);
			});
		}

		// Aktifkan Drag & Drop Sortable menggunakan jQuery UI
		if ($.fn.sortable) {
			$('#jobdesc_tbody').sortable({
				handle: '.drag-handle',
				items: 'tr.jobdesc-row',
				axis: 'y',
				opacity: 0.8,
				cursor: 'grabbing',
				placeholder: 'ui-sortable-placeholder',
				helper: function(e, ui) {
					// Pertahankan lebar kolom saat di-drag
					ui.children().each(function() {
						$(this).width($(this).width());
					});
					return ui;
				},
				stop: function(event, ui) {
					renumberRows();
				}
			}).disableSelection();
		}

		// Fungsi Template Buat Baris Baru
		function createNewRow(rowNum, defaultPoint) {
			var pointVal = defaultPoint || '1';
			return `
				<tr class="jobdesc-row">
					<td class="text-center">
						<div class="d-flex align-items-center justify-content-center">
							<span class="drag-handle mr-2" title="Klik dan geser (drag & drop) untuk memindahkan urutan">
								<i class="fas fa-grip-vertical"></i>
							</span>
							<span class="no-urut-badge row-index">${rowNum}</span>
							<input type="hidden" class="input-idurut" value="${rowNum}">
						</div>
					</td>
					<td>
						<input type="text" class="form-control form-control-sm text-center input-point" placeholder="e.g. 1" value="${pointVal}">
					</td>
					<td>
						<textarea class="form-control textarea-deskripsi input-deskripsi" rows="2" placeholder="Tulis rincian tugas / deskripsi pekerjaan..."></textarea>
					</td>
					<td>
						<select class="form-control form-control-sm input-nilai">
							<option value="1">Dinilai (Jobdesk Utama)</option>
							<option value="2" selected>Tidak Dinilai</option>
						</select>
					</td>
					<td class="text-center">
						<button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" title="Hapus baris ini">
							<i class="fas fa-trash"></i>
						</button>
					</td>
				</tr>
			`;
		}

		// Tambah Baris Baru
		function addRow() {
			var totalRows = $('#jobdesc_tbody tr.jobdesc-row').length;
			var nextNum = totalRows + 1;
			
			// Ambil nilai point terakhir jika ada
			var lastPoint = '1';
			var lastRow = $('#jobdesc_tbody tr.jobdesc-row').last();
			if (lastRow.length > 0) {
				lastPoint = lastRow.find('.input-point').val() || '1';
			}

			var newRowHtml = createNewRow(nextNum, lastPoint);
			$('#jobdesc_tbody').append(newRowHtml);
			renumberRows();

			// Focus ke textarea baris yang baru ditambah
			$('#jobdesc_tbody tr.jobdesc-row').last().find('.input-deskripsi').focus();
		}

		$('#btn-add-row, #btn-add-row-bottom').click(function(e) {
			e.preventDefault();
			addRow();
		});

		// Hapus Baris Terakhir
		$('#btn-delete-last-row').click(function(e) {
			e.preventDefault();
			var rows = $('#jobdesc_tbody tr.jobdesc-row');
			if (rows.length > 1) {
				rows.last().remove();
				renumberRows();
			} else {
				Swal.fire('Info', 'Minimal harus ada 1 baris input jobdesk!', 'info');
			}
		});

		// Hapus Baris Tertentu
		$(document).on('click', '.btn-remove-row', function(e) {
			e.preventDefault();
			var rows = $('#jobdesc_tbody tr.jobdesc-row');
			if (rows.length > 1) {
				$(this).closest('tr.jobdesc-row').remove();
				renumberRows();
			} else {
				Swal.fire('Info', 'Minimal harus ada 1 baris input jobdesk!', 'info');
			}
		});

		// Submit Simpan Jobdesk
		$(document).on('click', '.btn-ajukan', function(e) {
			e.preventDefault();
			var id_pengguna = $('#id_pengguna').val();
			if (!id_pengguna) {
				Swal.fire('Perhatian', 'Silakan pilih Nama Karyawan terlebih dahulu!', 'warning');
				return;
			}

			var des = [];
			var idu = [];
			var poi = [];
			var nil = [];

			$('#jobdesc_tbody tr.jobdesc-row').each(function(idx) {
				var deskripsiVal = $(this).find('.input-deskripsi').val();
				if (deskripsiVal && deskripsiVal.trim() !== '') {
					des.push(deskripsiVal.trim());
					idu.push($(this).find('.input-idurut').val() || (idx + 1));
					poi.push($(this).find('.input-point').val() || '1');
					nil.push($(this).find('.input-nilai').val() || '2');
				}
			});

			if (des.length === 0) {
				Swal.fire('Perhatian', 'Silakan isi minimal 1 baris deskripsi tugas/pekerjaan!', 'warning');
				return;
			}

			Swal.fire({
				title: 'Simpan Jobdesk?',
				text: 'Pastikan seluruh rincian poin dan urutan tugas sudah sesuai.',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya, Simpan',
				cancelButtonText: 'Batal'
			}).then(function(result) {
				if (result.value) {
					var $btn = $('.btn-ajukan');
					$btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

					$.ajax({
						method: 'POST',
						url: 'jobdesc/add',
						dataType: 'JSON',
						data: {
							id_pengguna : id_pengguna,
							tgl_mulai   : $('#tgl_mulai').val(),
							tgl_selesai : $('#tgl_selesai').val(),
							deskripsi   : des,
							idurut      : idu,
							point       : poi,
							nilai       : nil,
							csrf_token  : token
						},
						success: function(resp) {
							$btn.prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Simpan Jobdesk');
							handleResponse(resp);
						},
						error: function() {
							$btn.prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Simpan Jobdesk');
							Swal.fire('Error', 'Terjadi kesalahan saat memproses data pada server', 'error');
						}
					});
				}
			});
		});
	});

	function goBack() {
		window.history.back();
	}
</script>