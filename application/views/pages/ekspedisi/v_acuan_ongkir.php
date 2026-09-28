<!-- 
	Create by KURNIAWAN  
	23-06-2025
-->

<header class="page-header">
	<h2><i class="icons fas fa-truck-loading"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>
<style>
	#kt_table_1 thead th {
		text-align: center !important;
		vertical-align: middle !important;
	}
</style>


<div class="row">
	<div class="col">
		<div class="">
			<?php if(sessPenggunaId() == 1) { ?>
				<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success"><i class="fas fa-database"></i>&nbsp;&nbsp;&nbsp;Master Data</a>
			<?php } ?>
			<?php if(sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 33 || sessPenggunaId() == 7 || sessPenggunaId() == 749 || sessPenggunaId() == 73 || sessPenggunaId() == 23 ) { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a>
			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Brand</th>
							<th> Nama Product </th>
							<th> Berat Barang dengan Packaging (Kg) </th>
							<th> Dimensi Barang (P x L x T) </th>
							<th> Berat Barang berdasarkan Dimensi (Kg) </th>
							<th> Acuan Berat Barang di Expedisi </th>
							<th> Acuan Berat Barang di Expedisi (Kg) </th>
							<th> Acuan Ongkir Maksimal (Rp) </th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Berat & Acuan Ongkir</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="brand" class="form-control-label">Brand <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="brand" name="brand" required>
					</div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama Product <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama" name="nama" required>
					</div>
					<div class="form-group">
						<label for="berat" class="form-control-label">Berat Barang dengan Packaging (Kg) <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="berat" name="berat" required>
						Isi Koma (,) dengan Titik (.)
					</div>
					<div class="form-group">
						<label for="dimensi" class="form-control-label">Dimensi Barang (PxLxT) <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="dimensi" name="dimensi" required>
					</div>
					<div class="form-group">
						<label for="berat_dimensi" class="form-control-label">Berat Barang berdasarkan Dimensi (Kg) <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="berat_dimensi" name="berat_dimensi" required>
						Isi Koma (,) dengan Titik (.)
					</div>
					
					<div class="form-group">
							<label for="acuan_berat" class="form-control-label">Acuan Berat Barang di Expedisi <span class="text-danger">*</span> :</label>
							<select class="form-control" id="acuan_berat" name="acuan_berat" required>
									<option value="">- Pilih -</option>
									<option value="1">Berat Barang</option>
									<option value="2">Dimensi Barang</option>
							</select>
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


<div id="main-modal-acc" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Berat & Acuan Ongkir</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-acc', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="brand" class="form-control-label">Brand <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="brand" name="brand" readonly>
					</div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama Product <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama" name="nama" readonly>
					</div>
					<div class="form-group">
						<label for="berat" class="form-control-label">Berat Barang dengan Packaging (Kg) <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="berat" name="berat" readonly>
					</div>
					<div class="form-group">
						<label for="dimensi" class="form-control-label">Dimensi Barang (PxLxT) <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="dimensi" name="dimensi" readonly>
					</div>
					<div class="form-group">
						<label for="berat_dimensi" class="form-control-label">Berat Barang berdasarkan Dimensi (Kg) <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="berat_dimensi" name="berat_dimensi" readonly>
					</div>
					
					<div class="form-group">
							<label for="acuan_berat" class="form-control-label">Acuan Berat Barang di Expedisi <span class="text-danger">*</span> :</label>
							<select class="form-control" id="acuan_berat" name="acuan_berat" readonly>
									<option value="">- Pilih -</option>
									<option value="1">Berat Barang</option>
									<option value="2">Dimensi Barang</option>
							</select>
					</div>

					<div class="form-group">
						<label for="acuan_ongkir_maksimal" class="form-control-label">Acuan Ongkir Maksimal (Rp) <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="acuan_ongkir_maksimal" name="acuan_ongkir_maksimal" required>
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



<div id="file-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Master Data Berat & Acuan Ongkir</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form with enctype for file upload -->
            <?= form_open('#', array('id' => 'file-form', 'enctype' => 'multipart/form-data', 'autocomplete' => 'off')); ?>
                <div class="modal-body">
										<div>
											<form method="post" enctype="multipart/form-data" action="kalkulator/import">
												<div class="box-body">
													<div class="form-group">
															<label for="exampleInputFile">Upload File :</label>
															<input type="file" name="berkas_excel" class="form-control" id="exampleInputFile">
															<small class="text-danger">upload file .xlsx ONLY</small>
													</div>
													<br>
												</div>
											</form>
										</div>
                    <!--<div>
                        <div class="text-left mb-2">
                            <label for="InputExperience" class="col-form-label">Download Format :</label>&nbsp;&nbsp;&nbsp;
                            <a href="javascript:" id="btn-download"><i class="fas fa-download"></i> Download File</a>
                        </div>
                    </div>-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
												<button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i>&nbsp;&nbsp; Import</button>
                    </div>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>



	<script>
		document.addEventListener('DOMContentLoaded', function() {
		/*table = $('#kt_table_1').DataTable({
				responsive: false,
				processing: true,
				serverSide: true,

				ajax: {
						url: 'ekspedisi/paginationAcuanOngkir',
						type: 'POST',
						data: function(e) {
								e.csrf_token = token
						}
				},
				order: [[1, 'asc'], [2, 'asc']], // Kolom ke-1: Brand, kolom ke-2: Nama Produk
				columnDefs: [
						{
								targets: [0,3,4,5,6,7,9], className: 'text-center' // tengah
						},
						{
								targets: [1,2], className: 'text-left' // kiri
						},
						{
								targets: [8], className: 'text-right' // kanan
						}
				]
		});*/

		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [[0, 'desc']],
			ajax: {
				url: 'ekspedisi/paginationAcuanOngkir',
				type: 'POST',
				data: function(e) {
					e.csrf_token = token;
				}
			},
			order: [[1, 'asc'], [2, 'asc']],
			columnDefs: [
					{ targets: [0,3,4,5,6,7,9], className: 'text-center', searchable: false },
					{ targets: [1, 2], className: 'text-left', searchable: false },
					{ targets: [8], className: 'text-right', searchable: false },
					{ targets: [1, 2], searchable: true },
			]

		});



		$('#btn-laporan-form').click(function() {
					$('#file-modal .form-control').val(null);
					$('#file-modal').modal();
					$('#file-modal #file-form').attr('action', 'ekspedisi/importAcuanOngkir');

					$('#file-modal #object').val(object); 
			});

			//$("#btn-download").click(function(){
      //    window.open("<?php echo base_url(); ?>ekspedisi/exportDestinasi","_blank");
      //    $('#file-modal').modal('hide')
			
      //});



			$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'ekspedisi/addAcuanOngkir')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'ekspedisi'
			$('#main-modal #modal-form').attr('action', 'ekspedisi/updateAcuanOngkir')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/editAcuanOngkir/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_ekspedisi').val(data[0].id)
					$('#main-modal #brand').val(data[0].brand)
					$('#main-modal #nama').val(data[0].nama)
					$('#main-modal #berat').val(data[0].berat)
					$('#main-modal #dimensi').val(data[0].dimensi)
					$('#main-modal #berat_dimensi').val(data[0].berat_dimensi)
					$('#main-modal #acuan_berat').val(data[0].acuan_berat)
				})
		})


		$(document).on('click', '.btn-editAcc', function() {
			var object = 'ekspedisi'
			$('#main-modal-acc #modal-form-acc').attr('action', 'ekspedisi/updateAcuanAcc')
			$('#main-modal-acc').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/editAcuanOngkir/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal-acc #id_ekspedisi').val(data[0].id)
					$('#main-modal-acc #brand').val(data[0].brand)
					$('#main-modal-acc #nama').val(data[0].nama)
					$('#main-modal-acc #berat').val(data[0].berat)
					$('#main-modal-acc #dimensi').val(data[0].dimensi)
					$('#main-modal-acc #berat_dimensi').val(data[0].berat_dimensi)
					$('#main-modal-acc #acuan_berat').val(data[0].acuan_berat)
					$('#main-modal-acc #acuan_ongkir_maksimal').val(data[0].acuan_ongkir_maksimal)
				})
		})


		


		
		

		
	})


	function updateDatatable() {
		table.ajax.reload(null, false)
	}


	</script>

	