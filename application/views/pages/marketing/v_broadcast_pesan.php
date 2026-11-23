<header class="page-header">
	<h2><i class="fab fa-whatsapp text-success mr-2"></i><?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span>Marketing</span></li>
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col-12">
		<div class="card card-modern">
			<div class="card-body">
				<!-- Filter Section -->
				<div class="row mb-3 align-items-end">
					<div class="col-md-3 mb-2">
						<label class="font-weight-bold">Filter Kegiatan / Event:</label>
						<select id="filter_kegiatan" class="form-control">
							<option value="">-- Semua Kegiatan / Event --</option>
							<?php if (!empty($kegiatan_list)): ?>
								<?php foreach ($kegiatan_list as $keg): ?>
									<option value="<?= $keg->id ?>"><?= htmlspecialchars($keg->nama) ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-3 mb-2">
						<label class="font-weight-bold">Status Hubungi:</label>
						<select id="filter_status_hubungi" class="form-control">
							<option value="">-- Semua Status --</option>
							<option value="belum">Belum Dihubungi</option>
							<option value="sudah">Sudah Dihubungi</option>
						</select>
					</div>
					<div class="col-md-2 mb-2">
						<label class="font-weight-bold">Tanggal Mulai:</label>
						<input type="date" id="filter_tanggal_mulai" class="form-control">
					</div>
					<div class="col-md-2 mb-2">
						<label class="font-weight-bold">Tanggal Selesai:</label>
						<input type="date" id="filter_tanggal_selesai" class="form-control">
					</div>
					<div class="col-md-2 mb-2">
						<button type="button" id="btn_filter" class="btn btn-primary btn-block">
							<i class="fas fa-filter mr-1"></i> Filter
						</button>
						<button type="button" id="btn_reset" class="btn btn-default btn-block mt-1">
							<i class="fas fa-undo mr-1"></i> Reset
						</button>
					</div>
				</div>

				<!-- Selection Bar / Multi Contact Action -->
				<div class="card bg-light border p-3 mb-3 d-flex flex-row justify-content-between align-items-center flex-wrap" style="border-radius: 8px;">
					<div class="d-flex align-items-center mb-1">
						<div class="custom-control custom-checkbox mr-3">
							<input type="checkbox" class="custom-control-input" id="check_all_page">
							<label class="custom-control-label font-weight-bold" for="check_all_page">Pilih Semua di Halaman Ini</label>
						</div>
						<span class="badge badge-info py-1 px-2 font-size-sm" id="badge_selected_count">0 kontak terpilih</span>
					</div>
					<div class="mb-1">
						<button type="button" class="btn btn-sm btn-success shadow-sm" id="btn_mulai_antrean" disabled>
							<i class="fab fa-whatsapp mr-1"></i> Mulai Hubungi Kontak Terpilih (Satu per Satu)
						</button>
						<button type="button" class="btn btn-sm btn-outline-secondary ml-1" id="btn_clear_selection" style="display: none;">
							<i class="fas fa-times mr-1"></i> Batal Pilih
						</button>
					</div>
				</div>

				<div class="alert alert-info py-2 mb-3">
					<i class="fas fa-info-circle mr-1"></i>
					Anda dapat mencentang checklist kontak untuk memilih beberapa nomor sekaligus, lalu klik tombol <strong>Mulai Hubungi Kontak Terpilih</strong> untuk memproses kontak satu per satu secara berurutan.
				</div>

				<!-- Table Section -->
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover mb-0" id="table_broadcast" style="width:100%">
						<thead>
							<tr class="text-center">
								<th style="width: 45px;">No</th>
								<th>Nama Lengkap</th>
								<th>Instansi / Perusahaan</th>
								<th>Jabatan</th>
								<th>Kegiatan / Event</th>
								<th>Nomor WhatsApp</th>
								<th>Waktu Input</th>
								<th style="width: 150px;">Status Hubungi</th>
								<th style="width: 160px;">Aksi</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Antrean Hubungi Kontak Satu per Satu -->
<div class="modal fade" id="modal_antrean" tabindex="-1" role="dialog" aria-labelledby="modalAntreanLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success text-white py-2">
				<h5 class="modal-title font-weight-bold" id="modalAntreanLabel">
					<i class="fab fa-whatsapp mr-1"></i> Hubungi Kontak Terpilih (Satu per Satu)
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body text-center p-4">
				<div class="mb-2">
					<span class="badge badge-pill badge-primary px-3 py-1 font-size-sm" id="queue_progress_badge">Kontak 1 dari 5</span>
				</div>
				<h4 class="font-weight-bold text-dark mb-1" id="queue_nama">Nama Kontak</h4>
				<p class="text-muted mb-2" id="queue_instansi">Instansi</p>
				<h5 class="text-success font-weight-bold mb-3" id="queue_wa">
					<i class="fab fa-whatsapp mr-1"></i> 08xxxxxxxxxx
				</h5>

				<div class="alert alert-light border small text-muted text-left mb-3">
					<i class="fas fa-info-circle mr-1"></i> Klik tombol <strong>Buka Chat WA Sekarang</strong> untuk mengirim pesan manual. Setelah selesai, klik tombol <strong>Lanjut Kontak Berikutnya</strong>.
				</div>

				<div class="d-flex justify-content-center flex-wrap" style="gap: 10px;">
					<button type="button" class="btn btn-success btn-lg shadow-sm" id="btn_open_current_wa">
						<i class="fab fa-whatsapp mr-1"></i> Buka Chat WA Sekarang
					</button>
					<button type="button" class="btn btn-primary btn-lg shadow-sm" id="btn_next_contact">
						Lanjut Kontak Berikutnya <i class="fas fa-chevron-right ml-1"></i>
					</button>
				</div>
			</div>
			<div class="modal-footer py-2 justify-content-between">
				<button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Tutup / Selesai</button>
				<span class="text-muted small" id="queue_remaining_text">Tersisa 4 kontak lagi</span>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		var csrfToken = (typeof token !== 'undefined') ? token : ($('input[name="token"]').val() || '');

		// Data antrean kontak yang dicentang
		var selectedContacts = {};
		var queueList = [];
		var currentQueueIndex = 0;

		var table = $('#table_broadcast').DataTable({
			processing: true,
			serverSide: true,
			responsive: false,
			scrollX: true,
			order: [[6, 'desc']],
			ajax: {
				url: '<?= base_url("broadcast_pesan/pagination") ?>',
				type: 'POST',
				data: function(d) {
					d.kegiatan_id = $('#filter_kegiatan').val();
					d.status_hubungi = $('#filter_status_hubungi').val();
					d.tanggal_mulai = $('#filter_tanggal_mulai').val();
					d.tanggal_selesai = $('#filter_tanggal_selesai').val();
					d.csrf_token = csrfToken;
				}
			},
			columnDefs: [
				{ targets: [0, 6, 7, 8], className: 'text-center' }
			],
			drawCallback: function() {
				// Sinkronisasi status centang checkbox setelah tabel di-reload
				$('.chk-kontak').each(function() {
					var id = $(this).data('id');
					if (selectedContacts[id]) {
						$(this).prop('checked', true);
					}
				});
				updateSelectionBar();
			},
			language: {
				search: 'Cari Kontak:',
				emptyTable: 'Tidak ada data buku tamu ditemukan.',
				zeroRecords: 'Data kontak tidak cocok dengan pencarian.',
				lengthMenu: 'Tampilkan _MENU_ data',
				info: 'Menampilkan _START_ - _END_ dari _TOTAL_ kontak',
				infoEmpty: 'Menampilkan 0 kontak',
				paginate: {
					first: '<<',
					last: '>>',
					next: '>',
					previous: '<'
				}
			}
		});

		function updateSelectionBar() {
			var count = Object.keys(selectedContacts).length;
			$('#badge_selected_count').text(count + ' kontak terpilih');
			if (count > 0) {
				$('#btn_mulai_antrean').prop('disabled', false);
				$('#btn_clear_selection').show();
			} else {
				$('#btn_mulai_antrean').prop('disabled', true);
				$('#btn_clear_selection').hide();
			}

			// Cek apakah semua di halaman aktif tercentang
			var allCheckedInPage = $('.chk-kontak').length > 0;
			$('.chk-kontak').each(function() {
				if (!$(this).prop('checked')) {
					allCheckedInPage = false;
				}
			});
			$('#check_all_page').prop('checked', allCheckedInPage);
		}

		// Centang / uncentang per baris
		$(document).on('change', '.chk-kontak', function() {
			var id = $(this).data('id');
			if ($(this).is(':checked')) {
				selectedContacts[id] = {
					id: id,
					kegiatan: $(this).data('kegiatan'),
					nama: $(this).data('nama'),
					instansi: $(this).data('instansi'),
					wa: $(this).data('wa'),
					url: $(this).data('url')
				};
			} else {
				delete selectedContacts[id];
			}
			updateSelectionBar();
		});

		// Check all di halaman ini
		$('#check_all_page').on('change', function() {
			var isChecked = $(this).is(':checked');
			$('.chk-kontak').each(function() {
				$(this).prop('checked', isChecked);
				var id = $(this).data('id');
				if (isChecked) {
					selectedContacts[id] = {
						id: id,
						kegiatan: $(this).data('kegiatan'),
						nama: $(this).data('nama'),
						instansi: $(this).data('instansi'),
						wa: $(this).data('wa'),
						url: $(this).data('url')
					};
				} else {
					delete selectedContacts[id];
				}
			});
			updateSelectionBar();
		});

		// Batal semua pilihan
		$('#btn_clear_selection').on('click', function() {
			selectedContacts = {};
			$('.chk-kontak').prop('checked', false);
			$('#check_all_page').prop('checked', false);
			updateSelectionBar();
		});

		// Trigger Filter
		$('#btn_filter').on('click', function() {
			table.ajax.reload();
		});

		$('#btn_reset').on('click', function() {
			$('#filter_kegiatan').val('');
			$('#filter_status_hubungi').val('');
			$('#filter_tanggal_mulai').val('');
			$('#filter_tanggal_selesai').val('');
			table.ajax.reload();
		});

		$('#filter_kegiatan, #filter_status_hubungi').on('change', function() {
			table.ajax.reload();
		});

		// Kirim tracking helper
		function sendTracking(item) {
			$.ajax({
				url: '<?= base_url("broadcast_pesan/track_click") ?>',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_bukutamu: item.id,
					kegiatan_id: item.kegiatan,
					nama: item.nama,
					nomorwa: item.wa,
					csrf_token: csrfToken
				},
				success: function() {
					table.ajax.reload(null, false);
				}
			});
		}

		// Klik Tombol Satuan "Hubungi WA" di Baris Tabel
		$(document).on('click', '.btn-hubungi-wa', function(e) {
			e.preventDefault();
			var $btn = $(this);
			var item = {
				id: $btn.data('id'),
				kegiatan: $btn.data('kegiatan'),
				nama: $btn.data('nama'),
				wa: $btn.data('wa'),
				url: $btn.data('url')
			};

			window.open(item.url, '_blank', 'noopener,noreferrer');
			sendTracking(item);
		});

		// ==================== ALUR MODAL ANTREAN SATU PER SATU ====================
		$('#btn_mulai_antrean').on('click', function() {
			queueList = Object.values(selectedContacts);
			if (queueList.length === 0) return;

			currentQueueIndex = 0;
			showQueueItem(currentQueueIndex);
			$('#modal_antrean').modal('show');
		});

		function showQueueItem(idx) {
			if (idx >= queueList.length) {
				$('#modal_antrean').modal('hide');
				if (typeof Swal !== 'undefined') {
					Swal.fire('Selesai!', 'Semua kontak terpilih telah selesai diproses.', 'success');
				}
				selectedContacts = {};
				updateSelectionBar();
				table.ajax.reload(null, false);
				return;
			}

			var item = queueList[idx];
			$('#queue_progress_badge').text('Kontak ' + (idx + 1) + ' dari ' + queueList.length);
			$('#queue_nama').text(item.nama);
			$('#queue_instansi').text(item.instansi || '-');
			$('#queue_wa').html('<i class="fab fa-whatsapp mr-1"></i> ' + item.wa);

			var sisa = queueList.length - (idx + 1);
			$('#queue_remaining_text').text(sisa > 0 ? ('Tersisa ' + sisa + ' kontak lagi') : 'Ini adalah kontak terakhir');

			if (idx === queueList.length - 1) {
				$('#btn_next_contact').html('Selesai <i class="fas fa-check ml-1"></i>');
			} else {
				$('#btn_next_contact').html('Lanjut Kontak Berikutnya <i class="fas fa-chevron-right ml-1"></i>');
			}
		}

		// Tombol Buka Chat WA Sekarang di Modal
		$('#btn_open_current_wa').on('click', function() {
			if (currentQueueIndex < queueList.length) {
				var item = queueList[currentQueueIndex];
				window.open(item.url, '_blank', 'noopener,noreferrer');
				sendTracking(item);
			}
		});

		// Tombol Lanjut Kontak Berikutnya di Modal
		$('#btn_next_contact').on('click', function() {
			currentQueueIndex++;
			showQueueItem(currentQueueIndex);
		});
	});
</script>
