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
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Data</a>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama Satuan</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Gudang</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama Satuan <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama_satuan" name="nama_satuan" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="id_satuan" name="id_satuan">
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
				url: 'satuan_barang/pagination',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 2],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'satuan_barang/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-isactive', function() {
			$.ajax({
				method: 'POST',
				url: 'pengguna/update/is_active',
				dataType: 'json',
				data: {
					id_gudang: $(this).attr("id"),
					value: $(this).attr("value_isactive"),
					csrf_token: token
				},
				success: function(resp) {
					handleResponse(resp)
				}
			})

		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'satuan_barang'
			$('#main-modal #modal-form').attr('action', 'satuan_barang/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_satuan').val(data[0].id_satuan)
					$('#main-modal #nama_satuan').val(data[0].nama_satuan)
				})
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>