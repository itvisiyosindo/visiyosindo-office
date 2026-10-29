<header class="page-header">
	<h2><i class="icons fas fa-database "></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="d-flex align-items-center justify-content-between mb-2">
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success shadow-sm">
				<i class="icons icon-plus"></i>&nbsp; Tambah Cabang
			</a>
			<span class="text-muted small">
				<i class="fas fa-info-circle text-info mr-1"></i> Radius absensi cabang aktif digunakan untuk validasi geofence absensi kantor (default 100 meter).
			</span>
		</div>
		<div class="card-body bg-white rounded shadow-sm p-3">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th style="width: 40px;"> # </th>
							<th> Nama Cabang</th>
							<th> Penanggung Jawab</th>
							<th> Alamat </th>
							<th style="width: 230px;"> Koordinat & Radius GPS </th>
							<th style="width: 90px;"> Aksi </th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label" class="modal-title font-weight-bold"><i class="fas fa-building mr-2"></i> Form Cabang & Koordinat Absensi</h5>
				<button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">&times;</button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group mb-2">
							<label for="nama_cabang" class="form-control-label font-weight-bold">Nama Cabang <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="nama_cabang" name="nama_cabang" placeholder="Contoh: Kantor Cabang Jakarta" required>
						</div>
						<div class="form-group mb-2">
							<label for="penanggung_jawab" class="form-control-label font-weight-bold">Penanggung Jawab <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" placeholder="Nama Kepala Cabang / PIC" required>
						</div>
						<div class="form-group mb-2">
							<label for="alamat_cabang" class="form-control-label font-weight-bold">Alamat Cabang <span class="text-danger">*</span> :</label>
							<textarea class="form-control" id="alamat_cabang" name="alamat_cabang" rows="3" placeholder="Alamat lengkap kantor cabang" required></textarea>
						</div>
					</div>

					<div class="col-md-6">
						<div class="card p-3 mb-2" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px;">
							<h6 class="font-weight-bold text-dark mb-2">
								<i class="fas fa-crosshairs text-primary mr-1"></i> Titik Koordinat GPS & Radius
							</h6>
							<p class="text-muted small mb-2" style="font-size: 11px;">
								Tentukan titik GPS kantor cabang untuk validasi kehadiran absensi.
							</p>

							<div class="form-group mb-2">
								<label for="latitude" class="form-control-label small font-weight-bold mb-1">Latitude :</label>
								<input type="text" class="form-control form-control-sm" id="latitude" name="latitude" placeholder="Contoh: 0.4588367 atau -6.2087634">
							</div>
							<div class="form-group mb-2">
								<label for="longitude" class="form-control-label small font-weight-bold mb-1">Longitude :</label>
								<input type="text" class="form-control form-control-sm" id="longitude" name="longitude" placeholder="Contoh: 101.4250736 atau 106.845599">
							</div>
							<div class="form-group mb-2">
								<label for="radius" class="form-control-label small font-weight-bold mb-1">Radius Absen (Meter) :</label>
								<div class="input-group input-group-sm">
									<input type="number" class="form-control form-control-sm" id="radius" name="radius" value="100" min="10" max="5000">
									<div class="input-group-append">
										<span class="input-group-text font-weight-bold">Meter</span>
									</div>
								</div>
								<small class="text-muted" style="font-size: 11px;">Default: 100 meter.</small>
							</div>

							<div class="d-flex align-items-center mt-2" style="gap: 6px;">
								<button type="button" class="btn btn-xs btn-outline-info shadow-sm" id="btn-get-current-loc" style="font-size: 11px; padding: 4px 10px;">
									<i class="fas fa-map-marker-alt mr-1"></i> Ambil Titik Saya Saat Ini
								</button>
								<a href="https://maps.google.com" target="_blank" class="btn btn-xs btn-outline-secondary shadow-sm" style="font-size: 11px; padding: 4px 10px;">
									<i class="fas fa-external-link-alt mr-1"></i> Buka Google Maps
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer bg-light">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_cabang" name="id_cabang">
				<button type="button" class="btn btn-sm btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-sm btn-success btn-save font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Cabang</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'cabang/pagination',
				type: 'POST',
				data: function(e) {
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 5],
				className: 'text-center'
			}]
		});

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null);
			$('#radius').val(100);
			$('#main-modal #modal-form').attr('action', 'cabang/add');
			$('#modal-label').html('<i class="fas fa-plus-circle mr-2"></i> Tambah Cabang');
			$('#main-modal').modal();
		});

		$(document).on('click', '.btn-edit', function() {
			var object = 'cabang';
			$('#main-modal #modal-form').attr('action', 'cabang/update');
			$('#modal-label').html('<i class="fas fa-pencil-alt mr-2"></i> Edit Cabang & Koordinat');
			$('#main-modal').modal();

			var id = $(this).attr("data-id");
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json();
				})
				.then(function(data) {
					$('#main-modal #id_cabang').val(data[0].id_cabang);
					$('#main-modal #nama_cabang').val(data[0].nama_cabang);
					$('#main-modal #penanggung_jawab').val(data[0].penanggung_jawab);
					$('#main-modal #alamat_cabang').val(data[0].alamat_cabang);
					$('#main-modal #latitude').val(data[0].latitude || '');
					$('#main-modal #longitude').val(data[0].longitude || '');
					$('#main-modal #radius').val(data[0].radius || 100);
				});
		});

		$('#btn-get-current-loc').click(function() {
			if (!navigator.geolocation) {
				Swal.fire('Error', 'Browser tidak mendukung Geolocation GPS.', 'error');
				return;
			}
			var btn = $(this);
			btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mendeteksi...');
			navigator.geolocation.getCurrentPosition(function(pos) {
				$('#latitude').val(pos.coords.latitude);
				$('#longitude').val(pos.coords.longitude);
				btn.prop('disabled', false).html('<i class="fas fa-check text-success mr-1"></i> Titik Ditemukan!');
				setTimeout(function() {
					btn.html('<i class="fas fa-map-marker-alt mr-1"></i> Ambil Titik Saya Saat Ini');
				}, 2500);
			}, function(err) {
				btn.prop('disabled', false).html('<i class="fas fa-map-marker-alt mr-1"></i> Ambil Titik Saya Saat Ini');
				Swal.fire('Gagal', 'Gagal membaca lokasi GPS. Pastikan izin lokasi diaktifkan.', 'warning');
			}, {
				enableHighAccuracy: true,
				timeout: 10000
			});
		});
	});

	function updateDatatable() {
		table.ajax.reload(null, false);
	}
</script>
