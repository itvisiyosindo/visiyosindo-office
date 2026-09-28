<header class="page-header">
	<h2><i class="far fa-newspaper "></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<style>
	.doc-ui-shell {
		background: linear-gradient(180deg, #eaf2fb 0%, #f8fbff 100%);
		border-radius: 16px;
		padding: 1.1rem;
	}

	.doc-ui-head {
		background: linear-gradient(135deg, #0d4d7f 0%, #1e7f89 100%);
		border-radius: 14px;
		color: #fff;
		padding: 1rem 1.1rem;
		margin-bottom: 0.9rem;
	}

	.doc-ui-head h3 {
		margin: 0;
		font-size: 1.15rem;
		font-weight: 700;
	}

	.doc-ui-head p {
		margin: 0.35rem 0 0;
		opacity: 0.95;
		font-size: 0.9rem;
	}

	.doc-ui-toolbar {
		display: flex;
		flex-wrap: wrap;
		gap: 0.5rem;
		margin-bottom: 0.85rem;
	}

	.doc-ui-card {
		background: #fff;
		border: 1px solid #d8e5f2;
		border-radius: 12px;
		padding: 0.9rem;
		box-shadow: 0 6px 18px rgba(13, 77, 127, 0.06);
	}

	.doc-ui-table {
		margin-bottom: 0;
	}

	.doc-ui-table thead th {
		background: #edf4fb;
		color: #33485c;
		border-color: #d7e4f2;
		font-size: 0.8rem;
		text-transform: uppercase;
		letter-spacing: 0.02em;
		vertical-align: middle;
	}

	.doc-ui-table td {
		vertical-align: middle;
	}

	.doc-ui-modal .modal-content {
		border: none;
		border-radius: 12px;
		overflow: hidden;
	}

	.doc-ui-modal .modal-header {
		background: linear-gradient(135deg, #0f3d61 0%, #145d6d 100%);
		color: #fff;
	}

	.doc-ui-modal .form-control {
		border-radius: 10px;
		border: 1px solid #d6e4f1;
	}

	.doc-ui-modal .form-control:focus {
		border-color: #1f7a8c;
		box-shadow: 0 0 0 0.12rem rgba(31, 122, 140, 0.18);
	}

	@media (max-width: 768px) {
		.doc-ui-shell {
			padding: 0.9rem;
		}
	}
</style>

<div class="doc-ui-shell">
	<div class="doc-ui-head">
		<h3>Bahan Presentasi</h3>
		<p>Kelola materi presentasi product secara profesional dengan struktur data yang rapi dan mudah diakses.</p>
	</div>

	<div class="doc-ui-toolbar">
		<?php if(isAdmin() || isStafAdmin() || sessPenggunaId()==92  || sessPenggunaId()==102){ ?>
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Bahan Presentasi</a>
		<?php } ?>
	</div>

	<div class="doc-ui-card">
		<div class="table-responsive">
			<table class="table table-striped table-sm table-bordered table-hover doc-ui-table" id="kt_table_1">
				<thead>
					<tr>
						<th>#</th>
						<th>Nama File</th>
						<th>Link Download</th>
						<th>Kirim Via Wa</th>
						<?php if (isAdmin() || isStafAdmin() || sessPenggunaId()==92 || sessPenggunaId()==102) { ?>
							<th>Aksi</th>
						<?php } ?>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<!-- Modal Tambah dan Edit Data -->
<div id="main-modal" class="modal fade doc-ui-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Bahan Presentasi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama File <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama_file" name="nama_file" required>
					</div>
					<div class="form-group">
						<label for="username" class="form-control-label">Link Download <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="link_download" name="link_download" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_bhn_presentasi" name="id_bhn_presentasi">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- Modal Send WA -->
<div id="send-modal" class="modal fade doc-ui-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Kirim Via Wa</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('bahan_presentasi/sendWa', array('id' => 'send-form', 'autocomplete' => 'off')); ?>
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
				<input class="form-control" type="hidden" id="id_bhn_presentasi" name="id_bhn_presentasi">
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
				url: 'bahan_presentasi/pagination',
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
			$('#main-modal #modal-form').attr('action', 'bahan_presentasi/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'bahan_presentasi'
			$('#main-modal #modal-form').attr('action', 'bahan_presentasi/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_bhn_presentasi').val(data[0].id_bhn_presentasi)
					$('#main-modal #nama_file').val(data[0].nama_bhn_presentasi)
					$('#main-modal #link_download').val(data[0].link_download)
				})
		})

		$(document).on('click', '.btn-send-wa', function() {
			$('.form-control').val(null)
			$('#send-modal #id_bhn_presentasi').val($(this).attr("data-id"))
			$('#send-modal').modal()
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>