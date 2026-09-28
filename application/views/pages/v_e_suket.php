<header class="page-header">
	<h2><i class="fas fa-certificate"></i>&nbsp;<?= $page_title ?></h2>
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
		<h3>e-Suket</h3>
		<p>Data e-Suket ditata secara clean untuk memudahkan pencarian dokumen, masa berlaku, dan distribusi link.</p>
	</div>

	<div class="doc-ui-toolbar">
		<?php if(isAdmin() || isStafAdmin() || sessPenggunaId()==92 || sessPenggunaId()==102){ ?>
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah e-Suket</a>
		<?php } ?>
	</div>

	<div class="doc-ui-card">
		<div class="table-responsive">
			<table class="table table-striped table-sm table-bordered table-hover doc-ui-table" id="kt_table_1">
				<thead>
					<tr>
						<th>#</th>
						<th>Nama e-Suket</th>
						<th>Kategori</th>
						<th>Link Download</th>
						<th>Masa Berlaku</th>
						<?php if (isAdmin() || isStafAdmin() || sessPenggunaId()==92 || sessPenggunaId()==102) { ?>
							<th>Aksi</th>
						<?php } ?>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<div id="main-modal" class="modal fade doc-ui-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="fas fa-certificate text-grey-light"></i> Form e-Suket</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="nama_suket" class="form-control-label">Nama e-Suket <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama_suket" name="nama_suket" required>
					</div>
					<div class="form-group">
						<label for="kategori" class="form-control-label">Kategori <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="kategori" name="kategori" required>
					</div>
					<div class="form-group">
						<label for="link_download" class="form-control-label">Link Download <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="link_download" name="link_download" required>
					</div>
					<div class="form-group">
                        <label for="masa_berlaku_dokumen" class="form-control-label">Masa Berlaku Dokumen <span class="text-danger">*</span> :</label>
                        <input type="text" class="form-control" id="masa_berlaku_dokumen" name="masa_berlaku_dokumen" placeholder="Contoh: 31 Desember 2026 atau Selamanya" required>
                    </div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_suket" name="id_suket">
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
				url: 'e_suket/pagination',
				type: 'POST',
				data: function(e) {
					e.csrf_token = token
				}
			},
			columnDefs: [{
			    targets: [0, 2, 3, 4],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'e_suket/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'e_suket'
			$('#main-modal #modal-form').attr('action', 'e_suket/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_suket').val(data[0].id_suket)
					$('#main-modal #nama_suket').val(data[0].nama_suket)
					$('#main-modal #kategori').val(data[0].kategori)
					$('#main-modal #link_download').val(data[0].link_download)
					$('#main-modal #masa_berlaku_dokumen').val(data[0].masa_berlaku_dokumen)
				})
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>
