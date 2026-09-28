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
				<a href="serah_terima_barang/show/pengajuan/stb" id="btn-a-gc" class="btn btn-sm btn-success">
				    <i class="fas fa-plus"></i>&nbsp;&nbsp;Ajukan
				</a>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kode</th>
							<th> Pihak Pertama</th>
							<th> Pihak Kedua</th>
							<th> Tanggal</th>
							<th> Nama Pengaju</th>
							<th> Aksi</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Edit STB</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
			
					
				<div class="form-group">
						<label for="kode" class="form-control-label">KODE <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="kode" name="kode" required>
					</div>

				<div class="form-group">
						<label for="pihak_pertama" class="form-control-label">Pihak Pertama <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="pihak_pertama" name="pihak_pertama" required>
							<option value="">- Pilih Pihak Pertama -</option>
							<?php
							foreach ($list_cust as $row) {
								echo '<option value="' . $row->id_customer . '">' . $row->nama_customer . '</option>';
							}
							?>
						</select>
					</div>	

					<div class="form-group">
						<label for="pihak_kedua" class="form-control-label">Pihak Kedua <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="pihak_kedua" name="pihak_kedua" required>
							<option value="">- Pilih Pihak Kedua -</option>
							<?php
							foreach ($list_cust as $row) {
								echo '<option value="' . $row->id_customer . '">' . $row->nama_customer . '</option>';
							}
							?>
						</select>
					</div>	
				
				<div class="form-group">
						<label for="kota_pengajuan" class="form-control-label">Kota <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="kota_pengajuan" name="kota_pengajuan" required>
							<option value="">- Pilih Kota -</option>
							<?php
							foreach ($list_kota as $row) {
								echo '<option value="' . $row->nama . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="tgl_pengajuan" class="form-control-label">Tanggal Pengajuan <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" required>
						</div>
					</div>
					
					
					



				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_stb" name="id_stb">
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
				url: 'serah_terima_barang/pagination/permintaan_stb',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6],
				className: 'text-center'
			}]
		})

		function updateDatatable() {
		table.ajax.reload(null, false)
		}


		$(document).on('click', '.btn-edit', function() {
				$('.btn-isactive').remove()
				var object = 'serah_terima_barang'
				$('#main-modal #modal-form').attr('action', 'serah_terima_barang/update')
				$('#main-modal').modal()

				var id = $(this).attr("data-id")
				fetch(object + '/edit/' + id)
					.then(function(resp) {
						return resp.json()
					})
					.then(function(data) {
						$('#main-modal #kode').val(data[0].kode_stb)
						$('#main-modal #pihak_pertama').val(data[0].id_pihak1)
						$('#main-modal #pihak_kedua').val(data[0].id_customer)
						$('#main-modal #kota_pengajuan').val(data[0].kota_pengajuan)
						$('#main-modal #tgl_pengajuan').val(data[0].tgl_pengajuan)
						$('#main-modal #id_stb').val(id)
					})
			})

	})



</script>