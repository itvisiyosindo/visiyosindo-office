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
		<div class="">
			<?php if (isAdmin() || sessPenggunaId()==72) { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Kategori</a>
			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama Kategori</th>
							<th> Deskripsi </th>
							<th> Status Aktif </th>
							<?php if (isAdmin() || sessPenggunaId()==72){ ?>
				<th> Aksi </th>
			<?php } ?>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Kategori</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama Kategori <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama" name="nama" required>
					</div>
					<div class="form-group">
						<label for="username" class="form-control-label">Deskripsi <span class="text-danger">*</span> :</label>
						<textarea name="deskripsi" class="form-control" id="deskripsi" cols="15" rows="3"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="topik_id" name="topik_id">
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
				url: 'kategori_Tiket/pagination',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 3],
				className: 'text-center'
			}]
		})
		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'kategori_Tiket'
			$('#main-modal #modal-form').attr('action', 'kategori_Tiket/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			$('.btn-isactive').remove()
			var object = 'kategori_Tiket'
			$('#main-modal #modal-form').attr('action', 'kategori_Tiket/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #topik_id').val(data[0].id_topik)
					$('#main-modal #nama').val(data[0].nama)
					$('#main-modal #deskripsi').val(data[0].deskripsi)


					let text_isaktif = data[0].is_active == 1 ? "Nonaktifkan" : "Aktifkan"
					let color = data[0].is_active == 1 ? "danger" : "success"
					let value = data[0].is_active == 1 ? 0 : 1
					let x = `
					<button type="button" class="btn btn-${color} btn-isactive" id="${data[0].id_topik}" value_isactive="${value}">${text_isaktif}</button>
					`;
					$('.is_aktif').append(x)
				})
		})

		$(document).on('click', '.btn-isactive', function() {
			$.ajax({
				method: 'POST',
				url: 'kategori_Tiket/update/is_active',
				dataType: 'json',
				data: {
					topik_id: $(this).attr("id"),
					value: $(this).attr("value_isactive"),
					csrf_token: token
				},
				success: function(resp) {
					handleResponse(resp)
				}
			})
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>