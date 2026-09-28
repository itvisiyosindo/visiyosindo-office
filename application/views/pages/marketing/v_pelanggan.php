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
			<?php if (sessPenggunaId() == 72 || sessPenggunaId() == 69 || sessPenggunaId() == 1 || sessPenggunaId() == 755) { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Pelanggan</a>
			<?php } ?>
		</div>
		<br>
		<div class="card-body">
		<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
	<thead>					
		<tr>
			<!-- <th> No </th> -->
			<th> Nama</th>
			<th> Kontak </th>
			<th> Alamat </th>
			<th> Kota </th>
			<th> Provinsi </th>
			<th> Status </th>
			<th> Tanggal </th>
			<?php if (isAdmin() || sessPenggunaId()==72 || sessPenggunaId()==85 || sessPenggunaId() == 755){ ?>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Pelanggan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama" name="nama" required>
					</div>
					<div class="form-group">
						<label for="kontak" class="form-control-label">Kontak <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="kontak" name="kontak" required>
					</div>					
					<div class="form-group">
						<label for="kota" class="form-control-label">Kota <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="kota" name="kota" required>
					</div>
					<div class="form-group">
						<label for="provinsi" class="form-control-label">Provinsi <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="provinsi" name="provinsi" required>
					</div>
					<div class="form-group">
						<label for="alamat" class="form-control-label">Alamat <span class="text-danger">*</span> :</label>
						<textarea name="alamat" class="form-control" id="alamat" cols="15" rows="3"></textarea>
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
<script>
	document.addEventListener('DOMContentLoaded', function() {
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			
			// order: [
			// 	[0, 'ASC']
			// ],
			ajax: {
				url: 'pelanggan/pagination',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				// targets: [0, 1, 2],
				// className: 'text-center'
			}]
		})
		function updateDatatable() {
		table.ajax.reload(null, false)
	}
		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'pelanggan'
			$('#main-modal #modal-form').attr('action', 'pelanggan/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			$('.btn-isactive').remove()
			var object = 'pelanggan'
			$('#main-modal #modal-form').attr('action', 'pelanggan/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #nama').val(data[0].identitas_pelanggan)
					$('#main-modal #kontak').val(data[0].kontak)
					$('#main-modal #kota').val(data[0].kota)
					$('#main-modal #provinsi').val(data[0].provinsi)
					$('#main-modal #alamat').val(data[0].alamat)
					$('#main-modal #id_pelanggan').val(id)
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

	
</script>
