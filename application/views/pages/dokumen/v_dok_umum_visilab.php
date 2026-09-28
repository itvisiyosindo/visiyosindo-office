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
            <?php if (isAdmin() || isGa() || sessPenggunaId()=='74' || sessPenggunaId()=='83' || sessPenggunaId()=='84' || sessPenggunaId()=='86' || sessPenggunaId()=='754' || sessPenggunaId()=='736' || sessPenggunaId()=='15' || sessPenggunaId()=='23' || sessPenggunaId()=='54' || sessPenggunaId()=='33' || sessPenggunaId()=='69' || sessPenggunaId()=='751' || sessPenggunaId()=='75' || sessPenggunaId()=='760') { ?>
					
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Dokumen Umum</a>
            <?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama Dokumen</th>
							<th> Kategori</th>
							<th> Link Download</th>
                            <?php // if (isAdmin() || isGa() || sessPenggunaId()=='74' || sessPenggunaId()=='83' || sessPenggunaId()=='84') { ?>
                                <th> <center>Aksi</center> </th>
                            <?php // } ?>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form dokumen</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama dokumen <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama" name="nama" required>
					</div>
					<div class="form-group">
						<label for="kategori" class="form-control-label"> Kategori <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kategori" name="kategori" required>
							<option value="">- Pilih Kategori -</option>
							<?php
								foreach ($list_kategori as $row) {
									echo "<option value='".$row->id."'>".$row->nama."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="username" class="form-control-label">Link Download <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="file" name="file" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_dokumen" name="id_dokumen">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<div id="send-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Kirim Via Wa</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('dokumen/sendWa', array('id' => 'send-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="nama" class="form-control-label">No Wa Tujuan <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="wa_tujuan" name="wa_tujuan" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input class="form-control" type="hidden" id="id_dokumen" name="id_dokumen">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-success">Kirim</button>
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
				url: 'dokumen/pagination/umum_visilab',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 2, 3],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'dokumen/add/umum_visilab')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'dokumen'
			$('#main-modal #modal-form').attr('action', 'dokumen/update/umum_visilab')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/umum_visilab/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_dokumen').val(data[0].id)
					$('#main-modal #nama').val(data[0].nama_dokumen)
					$('#main-modal #kategori option[value="' +data[0].id_kategori+ '"]').prop("selected", true).trigger('change')
					$('#main-modal #file').val(data[0].file)
				})
		})

		$(document).on('click', '.btn-send-wa', function() {
			$('.form-control').val(null)
			$('#send-modal #id_dokumen').val($(this).attr("data-id"))
			$('#send-modal').modal()
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>