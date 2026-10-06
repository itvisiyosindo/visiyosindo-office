<header class="page-header">
	<h2><i class="icons icon-user-follow"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>
<div class="row">
	<div class="col">
		<div class="card-body">
			<div class="row">
				<div class="col-md-2">
					<small>Filter By Month:</small>
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
						<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
					</div>
				</div>
				<div class="col-md-2">
					<small>Filter By date:</small>
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
						<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm-dd", "minViewMode": "days"}' class="form-control" id="filter_date" placeholder="Pilih Tanggal" required data-plugin-datepicker>
					</div>
				</div>
				<div class="col-md-2">
					<small>Filter By Status Absen:</small>
					<select class="form-control " name="filter_status" id="filter_status">
						<option value="">Semua</option>
						<option value="terlambat">Terlambat</option>
						<option value="tepat_waktu">Tepat Waktu</option>
					</select>
				</div>
				<div class="col-md-2">
					<small> <i class="fas fa-print"></i> Print By Month:</small>
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
						<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="print_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
						<div class="input-group-append">
							<button type="button" id="btn_print_rekap" class="btn btn-danger text-white" title="Cetak Rekapitulasi Tunjangan Tidak Tetap (PDF)"><i class="fas fa-file-pdf"></i></button>
						</div>
					</div>
				</div>

				<div class="col-md-2">
					<small> <i class="fas fa-camera"></i> Print Foto & GPS:</small><br>
					<button type="button" id="btn_print_foto_gps" class="btn btn-sm btn-info text-white" style="font-weight: 700;" title="Buka Print Preview Absensi Foto Selfie & Lokasi GPS 1 Bulan">
						<i class="fas fa-camera"></i> Cetak Foto & GPS
					</button>
					<?php if (sessPenggunaId()=='1' || sessPenggunaId()=='58' || sessPenggunaId()=='69' || sessPenggunaId()=='744') { ?>
						<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-outline-secondary ml-1" title="Tambah Absen Manual"><i class="icons icon-plus"></i></a>
					<?php } ?>
				</div>

			</div>
			<br>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama Pengguna</th>
							<th> Waktu Absen Pagi </th>
							<th> Status Absen </th>
							<th> Status Penerimaan </th>
							<th> IP Address </th>
							<th> Lokasi </th>
						    <th> Jumlah Kehadiran </th>
						    <th> Kantor </th>
						    <th> Dinas</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Lokasi Absen</h4>
				<button type="button" class="close" style="color:white;margin: -1px" data-dismiss="modal" aria-label="Close"><i class="far fa-times-circle"></i></button>
			</div>
			<div class="modal-body">
				<div id="dvMap" style="height: 700px"></div><br>
				<div class="text-right">
					<!-- <input type="hidden" id='latitude' value="">
					<input type="hidden" id='longitude' value=""> -->
				</div>
			</div>
		</div>
	</div>
</div>

<div id="main-modal-add" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Absen</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-add', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					
					
					<span> Fitur ini digunakan Jika ada yang Tidak Bisa Absen seperti sedang Perjalanan Dinas Luar Negeri </span><br><br>
				
					<div class="form-group">
						<label for="pengguna_id" class="form-control-label">Nama Pegawai <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="pengguna_id" name="pengguna_id" required>
							<option value="">- Pilih Pegawai-</option>
							<?php
							foreach ($list_nama as $row) {
								echo '<option value="' . $row->pengguna_id . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>
					

					<div class="form-group">
						<label for="status_absen" class="form-control-label">Status Absen <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="status_absen" name="status_absen" required>
							<option value="">- Pilih -</option>
							<option value="tepat_waktu">Tepat Waktu (Jika Masuk)</option>
						</select>
					</div>

					<div class="form-group">
						<label for="type_absen" class="form-control-label">Type Absen <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="type_absen" name="type_absen" required>
							<option value="">- Pilih -</option>
							<option value="masuk">Masuk</option>
							<option value="istirahat">Istirahat</option>
							<option value="keluar">Keluar</option>
						</select>
					</div>

					<div class="form-group">
						<label for="jenis_absen" class="form-control-label">Jenis Absen <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="jenis_absen" name="jenis_absen" required>
							<option value="">- Pilih -</option>
							<option value="Kantor">Kantor</option>
							<option value="Dinas">Dinas</option>
						</select>
					</div>

					<div class="form-group">
							<label for="tanggal" class="form-control-label">Tanggal & Waktu Absen <span class="text-danger">*</span> :</label>
							<div class="input-group">
									<span class="input-group-text">
											<i class="fas fa-calendar-alt"></i>
									</span>
									<input type="datetime-local" class="form-control" id="tanggal" name="tanggal" required>
							</div>
					</div>


					


				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script src="https://maps.googleapis.com/maps/api/js"></script>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		$('#filter_month, #filter_date, #filter_status').change(function() {
			table.ajax.reload()
		})

		$('#btn_print_rekap').click(function() {
			var month = $('#print_month').val() || $('#filter_month').val() || '<?= date("Y-m") ?>';
			window.open('<?= base_url("absensi/print/allKaryawanByMonth/") ?>' + month, '_blank');
		});

		$('#btn_print_foto_gps').click(function() {
			var month = $('#print_month').val() || $('#filter_month').val() || '<?= date("Y-m") ?>';
			window.open('<?= base_url("absensi/print_foto_gps/") ?>' + month, '_blank');
		})

		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'absensi/pagination',
				type: 'POST',
				data: function(e) {
					e.filter_month = $('#filter_month').val()
					e.filter_status = $('#filter_status').val()
					e.filter_date = $('#filter_date').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 2, 3, 4, 5],
				className: 'text-center'
			}]
		})

		$(document).on('click', '.btn-lihat-posisi', function() {
			$('#main-modal').modal()

			// $('#latitude').val(null);
			// $('#longitude').val(null);
			$.ajax({
				method: 'POST',
				url: 'absensi/show/latlong',
				dataType: 'JSON',
				data: {
					pengguna_id: $(this).attr("data-id"),
					filter_month: $('#filter_month').val(),
					filter_date: $('#filter_date').val(),
					csrf_token: token
				},
				success: function(resp) {
					/*if (navigator.geolocation) {
						navigator.geolocation.getCurrentPosition(function(p) {
							var LatLng = new google.maps.LatLng(resp.latitude, resp.longitude);
							var mapOptions = {
								center: LatLng,
								zoom: 19,
								mapTypeId: google.maps.MapTypeId.ROADMAP
							};
							var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
							var marker = new google.maps.Marker({
								position: LatLng,
								map: map,
								title: "<div style= 'height:60px;width:200px'><b>Your location:</b><br />Latitude: " + p.coords.latitude + "<br />Longitude: " + p.coords.longitude
							});
							google.maps.event.addListener(marker, "click", function(e) {
								var infoWindow = new google.maps.InfoWindow();
								infoWindow.setContent(marker.title);
								infoWindow.open(map, marker);
							});
						});
					} else {
						alert('Geo Location feature is not supported in this browser.');
					}*/
					if((resp.latitude!=null) && (resp.longitude!=null)){
				        $('#main-modal').modal()
    				    document.getElementById("dvMap").innerHTML = "<iframe style='overflow:hidden;height:100%;width:100%' loading='lazy' allowfullscreen referrerpolicy='no-referrer-when-downgrade' src='https://www.google.com/maps/embed/v1/place?key=AIzaSyAFycbDEoOn8GPKQ1_fij6S1e1UpRZgKJo &q="+ resp.latitude + "," + resp.longitude + " &center="+ resp.latitude + "," + resp.longitude + " &zoom=21 &maptype=roadmap'></iframe>"
				    }else{
				        document.getElementById("dvMap").innerHTML = "";
				    }
				}
			})
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'absensi'
			$('#main-modal-add #modal-form-add').attr('action', 'absensi/addAbsenGa')
			$('#main-modal-add').modal()
		})


	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>