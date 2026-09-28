<!-- 
	Create by KURNIAWAN  
	23-06-2025
-->

<header class="page-header">
	<h2><i class="icons fas fa-building"></i>&nbsp;<?= $page_title ?></h2>
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
		<div class="d-flex justify-content-between align-items-center flex-wrap">
			<div>
				<?php if (sessPenggunaId() == 1) { ?>
					<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success"><i class="fas fa-database"></i>&nbsp;&nbsp;&nbsp;Master Data</a>
				<?php } ?>
				<?php if (sessPenggunaId() == 1 || sessPenggunaId() == 58 || sessPenggunaId() == 29) { ?>
					<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a>
					<a href="javascript:;" id="btn-laporan-cetak-form" class="btn btn-sm btn-primary"><i class="fas fa-print"></i>&nbsp;Cetak</a>
				<?php } ?>
			</div>

			<div class="d-flex align-items-center" style="min-width: 280px;">
				<label for="filter_kategori" class="mb-0 mr-2" style="white-space: nowrap; font-weight: bold; color: #000;">Filter Kategori:</label>
				<select class="form-control form-control-sm select2-local" id="filter_kategori" style="width: 100%;">
					<option value="">— Semua Kategori —</option>
					<?php foreach ($kategori_aset as $kat) { ?>
						<option value="<?= htmlspecialchars($kat->kategori) ?>"><?= htmlspecialchars($kat->kategori) ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kode / Serial Number</th>
							<th> Kategori </th>
							<th> Nama </th>
							<th> Nilai </th>
							<th> Tanggal Pembelian </th>
							<th> Link Pembelian </th>
							<th> Link Foto </th>
							<th> Keterangan </th>
							<th> Posisi </th>
							<th> PIC </th>
							<th> Dijual </th>
							<th> Tanggal Dijual </th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Aset Perusahaan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="kategori" class="form-control-label">Kategori <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="kategori" name="kategori" required>
					</div>
					<div class="form-group">
						<label for="kode" class="form-control-label">Kode / Serial Number <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="kode" name="kode" required>
					</div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama<span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama" name="nama" required>
					</div>
					<div class="form-group">
						<label for="nilai" class="form-control-label">Nilai <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="nilai" name="nilai" required>
						Isi Koma (,) dengan Titik (.)
					</div>
					<div class="form-group">
						<label for="tgl_pembelian" class="form-control-label">Tanggal Pembelian<span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tgl_pembelian" name="tgl_pembelian" required>
						</div>
					</div>
					<div class="form-group">
						<label for="link_pembelian" class="form-control-label">Link Pembelian <span class="text-danger">*</span> :</label>
						<textarea class="form-control" placeholder="Masukkan Link Pembelian" name="link_pembelian" id="link_pembelian" cols="10" rows="2"></textarea>
					</div>
					<div class="form-group">
						<label for="link_foto" class="form-control-label">Link Foto <span class="text-danger">*</span> :</label>
						<textarea class="form-control" placeholder="Masukkan Link Foto" name="link_foto" id="link_foto" cols="10" rows="2"></textarea>
					</div>
					<div class="form-group">
						<label for="keterangan" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
						<textarea class="form-control" placeholder="Masukkan keterangan" name="keterangan" id="keterangan" cols="10" rows="2"></textarea>
					</div>
					<div class="form-group">
						<label for="posisi" class="form-control-label">Posisi Aset Saat ini<span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="posisi" name="posisi" required>
					</div>

					<div class="form-group">
						<label for="dijual" class="form-control-label">Apakah Aset Sudah Dijual? <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control" id="dijual" name="dijual" required>
							<option value="1">Tidak</option>
							<option value="2">Sudah</option>
						</select>
					</div>
					<div class="form-group">
						<label for="tgl_dijual" class="form-control-label">Tanggal Dijual<span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tgl_dijual" name="tgl_dijual" required>
						</div>
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
				<h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Master Data Aset</h4>
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

<div id="main-modal-marketing-cetak" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Cetak Aset Perusahaan </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-marketing-form">

					<div class="form-group">
						<label class="control-label">Kategori Aset</label>
						<select data-plugin-selectTwo class="select-transaction input-group-sm form-control" name="namamarketing" id="namamarketing">
							<?php if ($kategori_aset != NULL): ?>
								<option value=''>— Pilih Semua —</option>
								<?php foreach ($kategori_aset as $value): ?>
									<option value="<?php echo $value->kategori; ?>"><?php echo $value->kategori; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('nama_marketing'); ?>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-cetak" class="btn btn-primary btn-clear-form">Cetak</button>
				<!--<button type="button" id="btn-export" class="btn btn-success btn-clear-form" >Export Excel</button>-->
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
				url: 'aset/paginationAset',
				type: 'POST',
				data: function(e) {
					e.csrf_token = token;
					e.filter_kategori = $('#filter_kategori').val();
				}
			},
			order: [
				[3, 'asc']
			],
			columnDefs: [{
					targets: [0, 2, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13],
					className: 'text-center',
					searchable: false
				},
				{
					targets: [1],
					className: 'text-left',
					searchable: false
				},
				{
					targets: [4],
					className: 'text-right',
					searchable: false
				},
				{
					targets: [1, 2, 3],
					searchable: true
				},
			]

		});

		$('#filter_kategori').change(function() {
			table.ajax.reload();
		});



		$('#btn-laporan-form').click(function() {
			$('#file-modal .form-control').val(null);
			$('#file-modal').modal();
			$('#file-modal #file-form').attr('action', 'aset/importAset');

			$('#file-modal #object').val(object);
		});





		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'aset/addAset')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'aset'
			$('#main-modal #modal-form').attr('action', 'aset/updateAset')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/editAset/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_ekspedisi').val(data[0].id)
					$('#main-modal #kategori').val(data[0].kategori)
					$('#main-modal #kode').val(data[0].kode)
					$('#main-modal #nama').val(data[0].nama)
					$('#main-modal #nilai').val(data[0].nilai)
					$('#main-modal #tgl_pembelian').val(data[0].tgl_pembelian)
					$('#main-modal #link_pembelian').val(data[0].link_pembelian)
					$('#main-modal #link_foto').val(data[0].link_foto)
					$('#main-modal #keterangan').val(data[0].keterangan)
					$('#main-modal #posisi').val(data[0].posisi)
					$('#main-modal #dijual').val(data[0].dijual)
					$('#main-modal #tgl_dijual').val(data[0].tgl_dijual)

				})
		})


		$('#btn-laporan-cetak-form').click(function() {
			$('#main-modal-marketing-cetak').modal()
		})

		$("#btn-cetak").click(function() {


			idmarketing = $("#namamarketing").val();
			namamarketing = $("#namamarketing option:selected").text();
			window.open("<?php echo base_url(); ?>aset/printlaporan/search?idmarketing=" + encodeURIComponent(idmarketing) + "&namamarketing=" + encodeURIComponent(namamarketing), "_blank");
			$('#main-modal-marketing-cetak').modal('hide')

		});
	})


	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>