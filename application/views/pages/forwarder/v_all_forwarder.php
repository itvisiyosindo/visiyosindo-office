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
									<th> No </th>
									<th> Kode</th>
									<th> Nama Shipment</th>
									<th> Port of Loading (POL)</th>
									<th> Port of Discharge (POD)</th>
									<th> Kurs (USD to IDR)</th>
									<th> Diajukan Oleh</th>
									<th> Status</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Perbandingan Expedisi Luar Negeri</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					

					<div class="form-group">
						<label for="nama" class="form-control-label">Nama Shipment :</label>
						<input type="text" class="form-control" id="nama" name="nama" required>
					</div>
					<!--<div class="form-group">
						<label for="tanggal" class="form-control-label">Bulan Registrasi <span class="text-danger">*</span> :</label>
						<div class="input-group date"
								data-provide="datepicker"
								data-date-format="mm-yyyy"
								data-date-min-view-mode="1"
								data-date-autoclose="true">
								
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tanggal" name="tanggal" required>
						</div>
					</div>-->
					<div class="form-group">
							<label for="sistem_pengiriman" class="form-control-label">Sistem Pengiriman :</label>
							<input type="text" class="form-control" id="sistem_pengiriman" name="sistem_pengiriman" required>
					</div>
					<div class="form-group">
							<label for="pol" class="form-control-label">Port of Loading (POL) :</label>
							<input type="text" class="form-control" id="pol" name="pol" required>
					</div>
					<div class="form-group">
							<label for="pod" class="form-control-label">Port of Discharge (POD) :</label>
							<input type="text" class="form-control" id="pod" name="pod" required>
					</div>
					<div class="form-group">
							<label for="berat_dimensi" class="form-control-label">Berat dan Dimensi :</label>
							<textarea type="text" class="form-control" id="berat_dimensi" name="berat_dimensi" cols="10" rows="3"></textarea>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_nilai_inv" class="form-control-label">Mata Uang :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_nilai_inv" name="uang_nilai_inv" required>
								<option value="USD">USD</option>
								<option value="IDR">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="nilai_inv" class="form-control-label">Nilai Invoice Shipment :</label>
							<input type="number" class="form-control" id="nilai_inv" name="nilai_inv">
						Isi koma (,) dengan Titik (.)
						</div>
					</div>
					<div class="form-group">
						<label for="kurs" class="form-control-label">Kurs Dollar AS (USD) ke Rupiah (IDR) :</label>
						<input type="number" class="form-control" id="kurs" name="kurs" required>
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
				url: 'forwarder/pagination_permintaan',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 3, 4, 5, 6, 7, 8],
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
			$('#main-modal #modal-form').attr('action', 'forwarder/addForwarder')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			$('.btn-isactive').remove()
			var object = 'forwarder'
			$('#main-modal #modal-form').attr('action', 'forwarder/updateForwarder')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/editForwarder/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #nama').val(data[0].nama)
					$('#main-modal #kurs').val(data[0].kurs)
					$('#main-modal #sistem_pengiriman').val(data[0].sistem_pengiriman)
					$('#main-modal #pol').val(data[0].pol)
					$('#main-modal #pod').val(data[0].pod)
					$('#main-modal #berat_dimensi').val(data[0].berat_dimensi)
					$('#main-modal #nilai_inv').val(data[0].nilai_inv)
					$('#main-modal #mata_nilai_inv').val(data[0].mata_nilai_inv)
					$('#main-modal #id_pelanggan').val(id)
				})
		})

		

		
	})

	
</script>
