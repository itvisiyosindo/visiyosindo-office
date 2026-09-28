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
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
							<thead>					
								<tr>
									<th> # </th>
									<th> Nomor</th>
									<th> Nama Dokumen</th>
									<th> Keterangan</th>
									<th> Link </th>
									<th> Jenis </th>
									<th> Diajukan Oleh </th>
									<th> Tanggal </th>
									<th> Status </th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Pengajuan Approval Director</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<div class="form-group">
						<label for="nama_dokumen" class="form-control-label">Nama Dokumen <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen" required>
					</div>
					<div class="form-group">
						<label for="ket" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="ket" name="ket" required>
					</div>
					<div class="form-group">
						<label for="link" class="form-control-label">Link <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="link" name="link" required>
					</div>
					<div class="form-group">
						<label for="jenis" class="form-control-label">Jenis<span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="jenis" name="jenis" required>
							<option value="">- Pilih Jenis-</option>
              <option value = "1">Tempel (Soft File)</option>
              <option value = "2">Cap Basah (Hard File)</option>
						</select>
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
				url: 'surat_new/pagination/appdir',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				 targets: [0, 1, 4, 5, 6, 7, 8],
				 className: 'text-center'
			}]
		})
		function updateDatatable() {
		table.ajax.reload(null, false)
	}
		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'surat_new'
			$('#main-modal #modal-form').attr('action', 'surat_new/addSrt/appdir')
			$('#main-modal').modal()
		})

		

		
		
	})

	
</script>
