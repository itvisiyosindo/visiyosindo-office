<header class="page-header">
	<h2><i class="fas fa-file-invoice-dollar"></i>&nbsp;<?= $page_title ?></h2>
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

	.label-col,
	.value-col {
		min-width: 220px;
		font-weight: bold;
		color: #343a40;
		position: relative;
	}

	.label-col::after {
		content: ":";
		position: absolute;
		right: -10px;
	}

	.text-right {
		text-align: right;
	}

	.text-center {
		text-align: center;
	}
</style>


<div class="row">
	<div class="col">
		<div class="">

			<?php if (sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 33 || sessPenggunaId() == 7 || sessPenggunaId() == 749 || sessPenggunaId() == 73 || sessPenggunaId() == 23 || sessPenggunaId() == '769') { ?>
				<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success"><i class="fas fa-database"></i>&nbsp;&nbsp;&nbsp;Master Data</a>
			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="row mb-1">
				<div class="col-auto label-col">Nama Ekspedisi</div>
				<div class="col value-col"><?= $data_ekspedisi->nama_ekspedisi ?></div>
			</div>
			<div class="row mb-1">
				<div class="col-auto label-col">Minimal Berat</div>
				<div class="col value-col"><?= $data_ekspedisi->berat != 0 ? $data_ekspedisi->berat . ' Kg' : 'Tidak Ada' ?></div>
			</div><br>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Provinsi</th>
							<th> Kab/Kota </th>
							<th> Ongkir dari Gudang PKU (Per Kg) </th>
							<th> Estimasi (Hari) </th>
							<th> Ongkir dari Gudang JKT (Per Kg) </th>
							<th> Estimasi (Hari) </th>
							<th> Ongkir dari Gudang Jogja (Per Kg) </th>
							<th> Estimasi (Hari) </th>
							<th> Aksi </th>
						</tr>
					</thead>
				</table>

				Note : Harga atau estimasi yang dicetak <strong class="fw-bold text-dark">tebal(Bold)</strong> menandakan data tersebut sudah dikonfirmasi dan bersifat final.
			</div>
			<br><br>
			<div role="document">
				<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left">Kembali</button>

				<br>
				<br>
			</div>
		</div>
	</div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Pricelist</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="provinsi" class="form-control-label">Provinsi <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="provinsi" name="provinsi" readonly>
					</div>
					<div class="form-group">
						<label for="kab_kota" class="form-control-label">Kabupaten / Kota <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="kab_kota" name="kab_kota" readonly>
					</div>
					<div class="form-group">
						<label for="pku" class="form-control-label">Ongkir dari Gudang PKU (Per Kg) <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="pku" name="pku" required>
					</div>
					<div class="form-group">
						<label for="fix_pku" class="form-control-label">Detail Harga <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control" id="fix_pku" name="fix_pku" required>
							<option value="1">Fix</option>
							<option value="2">Estimasi</option>
						</select>
					</div>
					<div class="form-group">
						<label for="est_pku" class="form-control-label">Estimasi (Hari) <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="est_pku" name="est_pku" required>
					</div>
					<div class="form-group">
						<label for="jkt" class="form-control-label">Ongkir dari Gudang JKT (Per Kg) <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="jkt" name="jkt" required>
					</div>
					<div class="form-group">
						<label for="fix_jkt" class="form-control-label">Detail Harga <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control" id="fix_jkt" name="fix_jkt" required>
							<option value="1">Fix</option>
							<option value="2">Estimasi</option>
						</select>
					</div>
					<div class="form-group">
						<label for="est_jkt" class="form-control-label">Estimasi (Hari) <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="est_jkt" name="est_jkt" required>
					</div>
					<div class="form-group">
						<label for="jogja" class="form-control-label">Ongkir dari Gudang Jogja (Per Kg) <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="jogja" name="jogja" required>
					</div>
					<div class="form-group">
						<label for="fix_jogja" class="form-control-label">Detail Harga <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control" id="fix_jogja" name="fix_jogja" required>
							<option value="1">Fix</option>
							<option value="2">Estimasi</option>
						</select>
					</div>
					<div class="form-group">
						<label for="est_jogja" class="form-control-label">Estimasi (Hari) <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="est_jogja" name="est_jogja" required>
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
				<h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Master Data Pricelist</h4>
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
							<input type="hidden" name="id_ekspedisi2" id="id_ekspedisi2" value="<?= $id_ekspedisi ?>">

							<br>
						</div>
					</form>
				</div>
				<div>
					<div class="text-left mb-2">
						<label for="InputExperience" class="col-form-label">Download Format :</label>&nbsp;&nbsp;&nbsp;
						<a href="javascript:" id="btn-download"><i class="fas fa-download"></i> Download File</a>
					</div>
				</div>
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
	document.querySelectorAll('input[type="number"]').forEach(function(input) {
		input.addEventListener('input', function() {
			this.value = this.value.replace(/[^0-9]/g, ''); // hanya angka 0-9
		});
	});


	document.addEventListener('DOMContentLoaded', function() {

		let id_ekspedisi = "<?= $id_ekspedisi ?>";

		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[1, 'asc'],
				[2, 'asc']
			],
			ajax: {
				url: 'ekspedisi/paginationDetailPricelist',
				type: 'POST',
				data: function(e) {
					e.csrf_token = token;
					e.id_ekspedisi = id_ekspedisi; // kirim ke server
				}
			},
			columnDefs: [{
					targets: [0, 3, 4, 5, 6, 7, 8, 9],
					className: 'text-center',
					searchable: false
				},
				{
					targets: [1, 2],
					className: 'text-left'
				},
				{
					targets: [1, 2],
					searchable: true
				}
			]
		});




		$('#btn-laporan-form').click(function() {
			$('#file-modal .form-control').val(null);
			$('#file-modal').modal();
			$('#file-modal #file-form').attr('action', 'ekspedisi/importPricelist');

			$('#file-modal #object').val(object);
		});

		$("#btn-download").click(function() {
			let id = id_ekspedisi; // atau bisa dari element lain: $('#id_ekspedisi').val()
			window.open("<?= base_url(); ?>ekspedisi/exportPricelist?id_ekspedisi=" + id, "_blank");
			$('#file-modal').modal('hide');
		});




		$(document).on('click', '.btn-edit', function() {
			var object = 'ekspedisi'
			$('#main-modal #modal-form').attr('action', 'ekspedisi/updatePricelist')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/editPricelist/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_ekspedisi').val(data[0].idPrice)
					$('#main-modal #provinsi').val(data[0].provinsi)
					$('#main-modal #kab_kota').val(data[0].kab_kota)
					$('#main-modal #pku').val(data[0].pku)
					$('#main-modal #jkt').val(data[0].jkt)
					$('#main-modal #jogja').val(data[0].jogja)
					$('#main-modal #fix_pku').val(data[0].fix_pku)
					$('#main-modal #fix_jkt').val(data[0].fix_jkt)
					$('#main-modal #fix_jogja').val(data[0].fix_jogja)
					$('#main-modal #est_pku').val(data[0].est_pku)
					$('#main-modal #est_jkt').val(data[0].est_jkt)
					$('#main-modal #est_jogja').val(data[0].est_jogja)
				})
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}

	function goBack() {
		window.history.back();
	}
</script>