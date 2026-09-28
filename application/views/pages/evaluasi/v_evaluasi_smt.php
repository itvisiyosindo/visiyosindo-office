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
							<th> Semester</th>
							<th> Tahun</th>
							<th> Total Nilai</th>
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
						<label for="smt" class="form-control-label">Semester <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="smt" name="smt" required>
							<option value="">- Pilih Semester -</option>
							<option value="Ganjil">Ganjil</option>
							<option value="Genap">Genap</option>
						</select>
					</div>

					<div class="form-group">
						<label for="periode" class="form-control-label">Tahun <span class="text-danger">*</span> :</label>
						<select class="form-control" id="periode" name="periode" required>
							<option value="">- Pilih Tahun -</option>
						</select>
					</div>
					<div class="form-group">
						<label for="nilai" class="form-control-label">Total Nilai Evaluasi :</label>
						<input type="number" class="form-control" placeholder="Masukkan Total Nilai" id="nilai" name="nilai" required>
						Jika ada koma (,) maka diisi dengan titik (.)
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
    $('#jenis_evaluasi').on('change', function () {
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
    pageLength: 50,
			ajax: {
				url: 'bonus/pagination/evaluasi',
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
			var object = 'bonus'
			$('#main-modal #modal-form').attr('action', 'bonus/addEvaluasi')
			$('#main-modal').modal()
		})

			$(document).on('click', '.btn-edit', function() {
				$('.btn-isactive').remove()
				var object = 'bonus'
				$('#main-modal #modal-form').attr('action', 'bonus/updateEvaluasi')
				$('#main-modal').modal()

				var id = $(this).attr("data-id")
				fetch(object + '/edit/' + id)
					.then(function(resp) {
						return resp.json()
					})
					.then(function(data) {
						$('#main-modal #id_pengguna').val(data[0].id_pengguna).trigger('change')
						$('#main-modal #smt').val(data[0].smt).trigger('change')
						$('#main-modal #periode').val(data[0].periode).trigger('change')
						$('#main-modal #nilai').val(data[0].nilai)
						$('#main-modal #id_pelanggan').val(id)
					})
			})

		
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}



		// Ambil tahun sekarang
    const tahunSekarang = new Date().getFullYear();
    const dropdownTahun = document.getElementById("periode");

    // Loop untuk menambahkan opsi tahun (-1 tahun hingga +1 tahun dari tahun sekarang)
    for (let i = tahunSekarang - 0; i <= tahunSekarang + 2; i++) {
        let option = document.createElement("option");
        option.value = i;
        option.textContent = i;
        dropdownTahun.appendChild(option);
    }
</script>