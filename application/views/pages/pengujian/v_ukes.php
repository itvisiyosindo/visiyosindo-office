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
			<!-- <?php if(sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 33 || sessPenggunaId() == 7 || sessPenggunaId()==73 || sessPenggunaId()==754 || sessPenggunaId()==765) { ?> -->
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah </a>
			<!--	<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp;Print Rekapan</a>
			 <?php } ?> -->
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kode</th>
							<th> Nama Pelanggan </th>
							<th> Jenis Pengujian </th>
							<th> Jenis Alat </th>
							<th> Nama Alat </th>
							<th> Serial Number </th>
							<th> Pengaju </th>
							<th> Status </th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Tambah Ukes</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					
					<div class="form-group">
						<label for="id_pelanggan" class="form-control-label">Nama Pelanggan <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_pelanggan" name="id_pelanggan" required>
							<option value="">- Pilih Pelanggan -</option>
							<?php
							foreach ($list_cust as $row) {
								echo '<option value="' . $row->id_pelanggan . '">' . $row->identitas_pelanggan . '</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="nama_instansi" class="form-control-label">Nama Instansi <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama_instansi" name="nama_instansi" required>
					</div>
					<div class="form-group">
						<label for="jenis_uji" class="form-control-label">Jenis Pengujian <span class="text-danger">*</span> :</label>
						<select class="form-control" id="jenis_uji" name="jenis_uji" required>
							<option value="">- Pilih Jenis Pengujian -</option>
							<option value="Alat Baru">Alat Baru</option>
							<option value="Perpanjangan">Perpanjangan</option>
						</select>
					</div>	
					<div class="form-group">
						<label for="jenis_alat" class="form-control-label">Jenis Alat <span class="text-danger">*</span> :</label>
						<select class="form-control" id="jenis_alat" name="jenis_alat" required>
							<option value="">- Pilih Jenis Alat -</option>
							<option value="Stationery X-Ray">Stationery X-Ray</option>
							<option value="Mobile X-Ray">Mobile X-Ray</option>
							<option value="Portable X-Ray">Portable X-Ray</option>
						</select>
					</div>		
					<div class="form-group">
						<label for="nama_alat" class="form-control-label">Nama Alat <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="nama_alat" name="nama_alat" required></textarea>
					</div>	
					<div class="form-group">
						<label for="serial_number" class="form-control-label">Serial Number <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="serial_number" name="serial_number" required>
					</div>
					<div class="form-group">
						<label for="waktu" class="form-control-label">Jadwal <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="jadwal" name="jadwal" required>
							<span class="input-group-text border-start-0 border-end-0 rounded-0">
								to
							</span>
							<input type="text" class="form-control" id="jadwal_end" name="jadwal_end" required>
						</div>
					</div>
					<div class="form-group">
						<label for="form_ceklis" class="form-control-label">Link Form Ceklis Pengujian <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="form_ceklis" name="form_ceklis" required>
					</div>
					<div class="form-group">
						<label for="link_sph" class="form-control-label">Link Surat Penawaran Harga (SPH) <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="link_sph" name="link_sph" required>
					</div>
					
					<div class="form-group">
						<label for="link_sph" class="form-control-label">Link Permintaan Instalasi <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="link_instalasi" name="link_instalasi" required>
					</div>
					

					
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id" name="id">
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
				url: 'pengujian/pagination/ukes',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
				className: 'text-center'
			}]
		})

		
		$('#btn-show-add-form').click(function () {
				$('.form-control').val(null)
				$('.btn-isactive').remove()
				var object = 'pengujian'
				$('#main-modal #modal-form').attr('action', 'pengujian/add/ukes')
				$('#main-modal').modal()
			})

		$(document).on('click', '.btn-edit', function() {
			$('.btn-isactive').remove()
			var object = 'pengujian'
			$('#main-modal #modal-form').attr('action', 'pengujian/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_pelanggan').val(data[0].id_pelanggan)
					$('#main-modal #nama_instansi').val(data[0].nama_instansi)
					$('#main-modal #jenis_uji').val(data[0].jenis_uji)
					$('#main-modal #jenis_alat').val(data[0].jenis_alat)
					$('#main-modal #nama_alat').val(data[0].nama_alat)
					$('#main-modal #serial_number').val(data[0].serial_number)
					$('#main-modal #form_ceklis').val(data[0].form_ceklis)
					$('#main-modal #jadwal').val(data[0].jadwal)
					$('#main-modal #jadwal_end').val(data[0].jadwal_end)
					$('#main-modal #link_sph').val(data[0].link_sph)
					$('#main-modal #link_instalasi').val(data[0].link_instalasi)
					$('#main-modal #id').val(id)
				})
		})


		
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}

	


</script>