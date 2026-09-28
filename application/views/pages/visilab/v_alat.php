<header class="page-header">
	<h2><i class="far fa-newspaper "></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="">
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Alat</a>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama Alat</th>
							<th> Serial Number</th>
							<th> Tanggal Kalibrasi</th>
							<th> Masa Berlaku Kalibrasi</th>
							<th> Tempat Kalibrasi</th>
              <th> Aksi </th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Data Alat Visilab</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama Alat <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama" name="nama" required>
					</div>
					<div class="form-group">
						<label for="kategori" class="form-control-label">Serial Number <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="serial_number" name="serial_number" required>
					</div>
					<div class="form-group">
						<label for="tanggal" class="form-control-label">Tanggal Kalibrasi <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tgl_kalibrasi" name="tgl_kalibrasi" required>
						</div>
					</div>
					<div class="form-group">
						<label for="tanggal" class="form-control-label">Masa Berlaku Kalibrasi <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tgl_masa_kalibrasi" name="tgl_masa_kalibrasi" required>
						</div>
					</div>					
					<div class="form-group">
						<label for="kategori" class="form-control-label">Tempat Kalibrasi <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="tempat_kalibrasi" name="tempat_kalibrasi" required>
					</div>
                <div id="danger-alert">Jika Data Tidak Ada, Kosongkan Saja </div>
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
				url: 'visilab/pagination_alat',
				type: 'POST',
				data: function(e) {
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'visilab/addAlat')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'visilab'
			$('#main-modal #modal-form').attr('action', 'visilab/updateAlat')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_brosur').val(data[0].id)
					$('#main-modal #nama').val(data[0].nama)
					$('#main-modal #serial_number').val(data[0].serial_number)
					$('#main-modal #tgl_kalibrasi').val(data[0].tgl_kalibrasi)
					$('#main-modal #tgl_masa_kalibrasi').val(data[0].tgl_masa_kalibrasi)
					$('#main-modal #tempat_kalibrasi').val(data[0].tempat_kalibrasi)
				})
		})

	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>