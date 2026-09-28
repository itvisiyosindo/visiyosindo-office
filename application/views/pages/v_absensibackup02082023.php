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
					</div>
				</div>
			</div><br>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama Pengguna</th>
							<th> Waktu Absen Pagi </th>
							<th> Status Absen </th>
							<th> Status Penerimaan </th>
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

<script src="https://maps.googleapis.com/maps/api/js"></script>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		$('#filter_month, #filter_date, #filter_status').change(function() {
			table.ajax.reload()
		})

		$('#print_month').change(function() {
			// $.ajax({
			// 	method: 'POST',
			// 	url: 'absensi/print/allKaryawanByMonth',
			// 	dataType: 'JSON',
			// 	data: {
			// 		print_month: $('#print_month').val(),
			// 		csrf_token: token
			// 	},
			// 	success: function(resp) {
			// 		console.log('fuadi');
					
			// 	}
			// })
			window.location.href = '<?= base_url() ?>' + 'absensi/print/allKaryawanByMonth/' + $('#print_month').val();
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
					if (navigator.geolocation) {
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
								title: "<div style = 'height:60px;width:200px'><b>Your location:</b><br />Latitude: " + p.coords.latitude + "<br />Longitude: " + p.coords.longitude
							});
							google.maps.event.addListener(marker, "click", function(e) {
								var infoWindow = new google.maps.InfoWindow();
								infoWindow.setContent(marker.title);
								infoWindow.open(map, marker);
							});
						});
					} else {
						alert('Geo Location feature is not supported in this browser.');
					}
				}
			})
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>