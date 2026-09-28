<!-- 
	Create by KURNIAWAN  
	19-06-2025
-->

<header class="page-header">
	<h2><i class="icons fas fa-truck"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="">
			<?php if (sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 33 || sessPenggunaId() == 7 || sessPenggunaId() == 749 || sessPenggunaId() == 73 || sessPenggunaId() == 23 || sessPenggunaId() == '763' || sessPenggunaId() == '769') { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Ekspedisi</a>
			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama Ekspedisi</th>
							<th> Alamat </th>
							<th> PIC </th>
							<th> Area Coverage </th>
							<th> Term of Payment </th>
							<th> Apakah Bisa Dipotong Pajak (PPH 23)? </th>
							<th> Apakah Ada Minimum Berat / shipment? </th>
							<th> Minimum Berat (Kg) </th>
							<th> Keterangan </th>
							<th> Status Kerjasama </th>
							<th> Link Kerjasama </th>
							<th> Pricelist </th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Ekspedisi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama Ekspedisi <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama_ekspedisi" name="nama_ekspedisi" required>
					</div>
					<div class="form-group">
						<label for="username" class="form-control-label">Alamat <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="alamat_ekspedisi" name="alamat_ekspedisi" required></textarea>
					</div>
					<div class="form-group">
						<label for="contact" class="form-control-label">PIC <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="contact" name="contact" required>
					</div>
					<div class="form-group">
						<label for="coverage" class="form-control-label">Area Coverage <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="coverage" name="coverage" required>
					</div>
					<div class="form-group">
						<label for="payment" class="form-control-label">Term of Payment <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="payment" name="payment" required>
					</div>
					<div class="form-group">
						<label for="pph23" class="form-control-label">Apakah Bisa Dipotong Pajak (PPH 23)? <span class="text-danger">*</span> :</label>
						<select class="form-control" id="pph23" name="pph23" required>
							<option value="">- Pilih -</option>
							<option value="Ya">Ya</option>
							<option value="Tidak">Tidak</option>
						</select>
					</div>
					<div class="form-group">
						<label for="min_berat" class="form-control-label">Apakah Ada Minimum Berat / Shipment? <span class="text-danger">*</span> :</label>
						<select class="form-control" id="min_berat" name="min_berat" required>
							<option value="">- Pilih -</option>
							<option value="1">Ya</option>
							<option value="2">Tidak</option>
						</select>
					</div>
					<div class="form-group">
						<label for="berat" class="form-control-label">Minimum Berat (kg) <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="berat" name="berat" required>
						Isi Koma (,) dengan Titik (.)
					</div>
					<div class="form-group">
						<label for="keterangan" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="keterangan" name="keterangan" required></textarea>
					</div>
					<div class="form-group">
						<label for="kerjasama" class="form-control-label">Status Kerjasama <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kerjasama" name="kerjasama" required>
							<option value="">- Pilih -</option>
							<option value="Ya">Ya</option>
							<option value="Tidak">Tidak</option>
						</select>
					</div>
					<div class="form-group">
						<label for="legalitas" class="form-control-label">Link Kerjasama <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="legalitas" name="legalitas" required></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_ekspedisi" name="id_ekspedisi">
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
				url: 'ekspedisi/pagination_ekspedisi',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 4, 5, 6, 7, 8, 10, 11, 12, 13],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'ekspedisi/addEkspedisi')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'ekspedisi'
			$('#main-modal #modal-form').attr('action', 'ekspedisi/updateEkspedisi')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_ekspedisi').val(data[0].id_ekspedisi)
					$('#main-modal #nama_ekspedisi').val(data[0].nama_ekspedisi)
					$('#main-modal #alamat_ekspedisi').val(data[0].alamat_ekspedisi)
					$('#main-modal #contact').val(data[0].contact)
					$('#main-modal #coverage').val(data[0].coverage)
					$('#main-modal #payment').val(data[0].payment)
					$('#main-modal #pph23').val(data[0].pph23)
					$('#main-modal #min_berat').val(data[0].min_berat)
					$('#main-modal #berat').val(data[0].berat)
					$('#main-modal #keterangan').val(data[0].keterangan)
					$('#main-modal #kerjasama').val(data[0].kerjasama)
					$('#main-modal #legalitas').val(data[0].legalitas)
				})
		})



	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>