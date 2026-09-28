<header class="page-header">
	<h2><i class="icons fas fa-database"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="">
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Pemasok</a>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama Pemasok</th>
							<th> Alamat </th>
							<th> Contact Person </th>
							<th> Warranty Period </th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Pemasok</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama Pemasok <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama_pemasok" name="nama_pemasok" required>
					</div>
					<div class="form-group">
						<label for="username" class="form-control-label">Alamat Pemasok <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="alamat_pemasok" name="alamat_pemasok" required>
					</div>
					<div class="form-group">
						<label for="username" class="form-control-label">Contact Person <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="contact" name="contact" required></textarea>
					</div>
					<div class="form-group">
						<label for="warranty" class="form-control-label">Warranty Period <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="warranty" name="warranty" required></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pemasok" name="id_pemasok">
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
				url: 'pemasok_utama/pagination',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 5],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'pemasok_utama/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'pemasok_utama'
			$('#main-modal #modal-form').attr('action', 'pemasok_utama/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_pemasok').val(data[0].id_pemasok)
					$('#main-modal #nama_pemasok').val(data[0].nama_pemasok)
					$('#main-modal #alamat_pemasok').val(data[0].alamat_pemasok)
					$('#main-modal #contact').val(data[0].contact)
					$('#main-modal #warranty').val(data[0].warranty)
				})
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>