<header class="page-header">
	<h2><i class="fas fa-chart-line"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="">
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a><br>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama</th>
							<th> NPP</th>
							<th> Jabatan</th>
							<th> Jenis Evaluasi</th>
							<th> Semester</th>
							<th> Tahun</th>
							<th> Status</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Evaluasi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">




					<div class="form-group">
						<label for="id_pengguna" class="form-control-label">Nama Pegawai <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_pengguna" name="id_pengguna" required>
							<option value="">- Pilih Pegawai-</option>
							<?php
							foreach ($list_nama as $row) {
								echo '<option value="' . $row->pengguna_id . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>

					<div class="form-group">
						<label for="jenis_evaluasi" class="form-control-label">Jenis Evaluasi <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="jenis_evaluasi" name="jenis_evaluasi" required>
							<option value="">- Pilih -</option>
							<option value="1">Semester</option>
							<option value="2">Kontrak</option>
						</select>
					</div>

					<div class="form-group" id="form_smt">
						<label for="smt" class="form-control-label">Semester <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="smt" name="smt" required>
							<option value="">- Pilih Semester -</option>
							<option value="1">1</option>
							<option value="2">2</option>
						</select>
					</div>

					<div class="form-group" id="form_tahun">
						<label for="tahun" class="form-control-label">Tahun <span class="text-danger">*</span> :</label>
						<select class="form-control" id="tahun" name="tahun" required>
							<option value="">- Pilih Tahun -</option>
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
		$('#form_smt, #form_tahun').hide();

		// Ketika jenis evaluasi berubah
		$('#jenis_evaluasi').on('change', function() {
			var jenis = $(this).val();

			if (jenis === '1') {
				$('#form_smt, #form_tahun').show();
			} else {
				$('#form_smt, #form_tahun').hide();
				$('#smt').val('');
				$('#tahun').val('');
			}
		});


		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'evaluasi/pagination/all',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'evaluasi'
			$('#main-modal #modal-form').attr('action', 'evaluasi/add')
			$('#main-modal').modal()
		})


	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}



	// Ambil tahun sekarang
	const tahunSekarang = new Date().getFullYear();
	const dropdownTahun = document.getElementById("tahun");

	// Loop untuk menambahkan opsi tahun (-1 tahun hingga +1 tahun dari tahun sekarang)
	for (let i = tahunSekarang - 0; i <= tahunSekarang + 2; i++) {
		let option = document.createElement("option");
		option.value = i;
		option.textContent = i;
		dropdownTahun.appendChild(option);
	}
</script>