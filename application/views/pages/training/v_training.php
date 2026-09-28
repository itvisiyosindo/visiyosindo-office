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
			
				<a href="javascript:;" id="btn-show-ajuTraining-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Ajukan Training</a>
				
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kode </th>
							<th> Nama Pengaju</th>
							<th> Jabatan</th>
							<th> Nama Training</th>
							<th> Penyelenggara</th>
							<th> Tanggal</th>
							<th> Biaya</th>
							<th> Status</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>





<!-- Update Log Tiket -->
<div id="log-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Pengajuan Training & Development</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'ajuTraining-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-tiket-form">
					<div class="form-group">
						<label for="nama_training" class="form-control-label">Nama Training<span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Nama Training" id="nama_training" name="nama_training" required>
					</div>
					<div class="form-group">
						<label for="penyelenggara" class="form-control-label">Penyelenggara <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Penyelenggara" id="penyelenggara" name="penyelenggara" required>
					</div>
					<div class="form-group">
						<label for="tanggal" class="form-control-label">Tanggal <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
							<span class="input-group-text border-start-0 border-end-0 rounded-0">
								to
							</span>
							<input type="text" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
						</div>
					</div>
					<div class="form-group">
						<label for="alasan" class="form-control-label">Alasan Mengikuti Training <span class="text-danger">*</span> :</label>
						<textarea class="form-control" name="alasan" id="alasan" cols="10" rows="2"></textarea>
					</div>
					<div class="form-group">
						<label for="alasan" class="form-control-label">Kegunaan/Manfaat <span class="text-danger">*</span> :</label>
						<textarea class="form-control" name="manfaat" id="manfaat" cols="10" rows="2"></textarea>
					</div>
					<div class="form-group">
						<label for="penyelenggara" class="form-control-label">Biaya <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" placeholder="Masukkan Biaya" id="biaya" name="biaya" required>
					</div>
					<div class="form-group">
						<label for="penyelenggara" class="form-control-label">Link Pelatihan (Lampiran) :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Pelatihan" id="link_pelatihan" name="link_pelatihan" required>
					</div>
					<div class="form-group">
						<label for="agent" class="form-control-label">Nama Kepala Divisi (Atasan) :<span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" name="id_kepaladivisi" id="id_kepaladivisi">
							<option value="">- Pilih -</option>
							<?php
							foreach ($list_nama as $row) {
								echo '<option value="' . $row->pengguna_id . '">' . $row->nama . " | " . $row->jabatan . '</option>';
							}
							?>
						</select>
						Kosongkan Jika Tidak Ada
					</div>
					

				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-success btn-save">Ajukan</button>
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
				url: 'training/pagination/training_user',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7, 8],
				className: 'text-center'
			}]
		})
		
		$('#btn-show-ajuTraining-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'training'
			$('#log-modal #ajuTraining-form').attr('action', 'training/add')
			$('#log-modal').modal()
		})

		
		
		

	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>