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
            <?php if(isAdmin() || sessPenggunaId()==81){ ?>
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Kategori Pajak</a>
            <?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kategori Pajak</th>
							<th> Deskripsi</th>
							<th> Besaran</th>
                            <?php if (isAdmin() || sessPenggunaId()==81) { ?>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Kategori Pajak</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="kategori" class="form-control-label">Kategori Pajak <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="kategori" name="kategori" required>
					</div>
					<div class="form-group">
						<label for="deskripsi" class="form-control-label">Deskripsi <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="deskripsi" name="deskripsi" required>
					</div>
					<div class="form-group">
						<label for="besaran" class="form-control-label">Besaran <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="besaran" name="besaran" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_kategori_pajak" name="id_kategori_pajak">
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
				url: 'kategori_pajak/pagination',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 3],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'kategori_pajak/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'kategori_pajak'
			$('#main-modal #modal-form').attr('action', 'kategori_pajak/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_kategori_pajak').val(data[0].id_kategori_pajak)
					$('#main-modal #nama_kategori_pajak').val(data[0].nama_kategori_pajak)
					$('#main-modal #link_download').val(data[0].link_download)
				})
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>