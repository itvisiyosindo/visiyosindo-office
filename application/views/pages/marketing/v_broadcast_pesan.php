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
					<div class="col-md-4 mb-2">
						<label class="font-weight-bold">Filter Kegiatan / Event:</label>
						<select id="filter_kegiatan" class="form-control select2">
							<option value="0">-- Semua Kegiatan / Event --</option>
							<?php if (!empty($kegiatan_list)): ?>
								<?php foreach ($kegiatan_list as $keg): ?>
									<option value="<?= $keg->id ?>"><?= htmlspecialchars($keg->nama) ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-3 mb-2">
						<label class="font-weight-bold">Tanggal Mulai:</label>
						<input type="date" id="filter_tanggal_mulai" class="form-control">
					</div>
					<div class="col-md-3 mb-2">
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

				<div class="alert alert-info py-2 mb-3">
					<i class="fas fa-info-circle mr-1"></i>
					Daftar nomor kontak diambil dari <strong>Buku Tamu</strong>. Klik tombol <strong>Hubungi WA</strong> pada baris kontak untuk membuka chat WhatsApp dan mengirim pesan secara manual.
				</div>

				<!-- Table Section -->
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover mb-0" id="table_broadcast" style="width:100%">
						<thead>
							<tr class="text-center">
								<th style="width: 50px;">No</th>
								<th>Nama Lengkap</th>
								<th>Instansi / Perusahaan</th>
								<th>Jabatan</th>
								<th>Kegiatan / Event</th>
								<th>Nomor WhatsApp</th>
								<th>Waktu Input</th>
								<th style="width: 140px;">Aksi</th>
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

<script>
	$(document).ready(function() {
		var token = $('input[name="token"]').val() || $('meta[name="csrf-token"]').attr('content');

		var table = $('#table_broadcast').DataTable({
			processing: true,
			serverSide: true,
			responsive: true,
			order: [[6, 'desc']],
			ajax: {
				url: '<?= base_url("broadcast_pesan/pagination") ?>',
				type: 'POST',
				data: function(d) {
					d.kegiatan_id = $('#filter_kegiatan').val();
					d.tanggal_mulai = $('#filter_tanggal_mulai').val();
					d.tanggal_selesai = $('#filter_tanggal_selesai').val();
					d.csrf_token = token;
				}
			},
			columnDefs: [
				{ targets: [0, 6, 7], className: 'text-center' }
			],
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

		$('#btn_filter').on('click', function() {
			table.ajax.reload();
		});

		$('#btn_reset').on('click', function() {
			$('#filter_kegiatan').val('0').trigger('change');
			$('#filter_tanggal_mulai').val('');
			$('#filter_tanggal_selesai').val('');
			table.ajax.reload();
		});

		$('#filter_kegiatan').on('change', function() {
			table.ajax.reload();
		});
	});
</script>
