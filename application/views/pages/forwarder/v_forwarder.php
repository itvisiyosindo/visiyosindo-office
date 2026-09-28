<!-- 
	Create by KURNIAWAN  
	30-06-2025
-->

<header class="page-header">
	<h2><i class="fas fa-plane"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="card-body">
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
							<thead>					
								<tr>
									<th> No</th>
									<th> Nama</th>
									<th> Alamat</th>
									<th> Contact Person</th>
									<th> Website</th>
									<th> Keterangan</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Forwarder</h5>
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
						<label for="alamat" class="form-control-label">Alamat <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="alamat" name="alamat" required></textarea>
					</div>
					<div class="form-group">
						<label for="contact" class="form-control-label">Contact Person <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="contact" name="contact" required>
					</div>
					<div class="form-group">
						<label for="website" class="form-control-label">Website <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="website" name="website" required>
					</div>
					<div class="form-group">
						<label for="keterangan" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="keterangan" name="keterangan" required></textarea>
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
				url: 'forwarder/pagination',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 4, 6],
				className: 'text-center'
			}]
		})
		function updateDatatable() {
		table.ajax.reload(null, false)
	}
		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'forwarder'
			$('#main-modal #modal-form').attr('action', 'forwarder/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			$('.btn-isactive').remove()
			var object = 'forwarder'
			$('#main-modal #modal-form').attr('action', 'forwarder/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #nama').val(data[0].nama)
					$('#main-modal #alamat').val(data[0].alamat)
					$('#main-modal #contact').val(data[0].contact)
					$('#main-modal #website').val(data[0].website)
					$('#main-modal #keterangan').val(data[0].keterangan)
					$('#main-modal #id_pelanggan').val(id)
				})
		})

		

		
	})

	
</script>
