<header class="page-header">
	<h2><i class="icons fas fa-box"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>
<div class="row">
	<div class="col">
		<div class="">
			<?php if (isStafAdmin() || isAdmin() || sessPenggunaId() == 763 || sessPenggunaId() == 769) { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Data</a>


				<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success"><i class="fas fa-database"></i>&nbsp;&nbsp;&nbsp;Master Data</a>

				<!--<form method="post" enctype="multipart/form-data" action="barang/import">
            <div class="box-body">
              <div class="form-group">
                  <label for="exampleInputFile">File Upload</label>
                  <input type="file" name="berkas_excel" class="form-control" id="exampleInputFile">
              </div>
              <button type="submit" class="btn btn-primary">Import</button>
            </div>
          </form>-->

			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="row">
				<div class="col-md-2">
					<small>Filter By Kategori:</small>
					<select class="form-control " name="filter_kategori" id="filter_kategori">
						<option value="">Semua</option>
						<?php foreach ($kategori_barang as $row) { ?>
							<option value="<?= encrypt($row->id_kategori) ?>"><?= $row->nama_kategori ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-2">
					<small>Scan Barcode:</small>
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" id="scan_barcode" placeholder="-- Scan Barcode --">
						</div>
					</div>
				</div>
			</div>
			<br>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama Barang</th>
							<th> Kategori </th>
							<th> Satuan </th>
							<th> ID Produk </th>
							<th> No AKL </th>
							<th> Tipe </th>
							<th> Kode Produk </th>
							<!--<th> Kode barang </th>-->
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Baranng</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="form-group mb-2 pt-1">
					<label class="col-form-label">Kategori Barang <span class="text-danger">*</span></label>
					<select class="form-control" name="id_kategori" id="id_kategori" required>
						<option value="">...</option>
						<?php foreach ($kategori_barang as $kb) { ?>
							<option value="<?= encrypt($kb->id_kategori) ?>"><?= $kb->nama_kategori ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group mb-2 pt-1">
					<label class=" col-form-label">Nama Barang <span class="text-danger">*</span></label>
					<input class="form-control" type="text" name="nama_barang" id="nama_barang" value="" required>
				</div>
				<!-- <div class="form-group mb-2 pt-1">
					<label class=" col-form-label">Kode Barang <span class="text-danger">*</span></label>
					<input class="form-control" type="text" name="kode_barang" id="kode_barang" value="" placeholder="--- Scan Barcode --- " required>
				</div> -->
				<!-- <div class="form-group mb-2 pt-1">
					<label class=" col-form-label">Konfirmasi Kode Barang <span class="text-danger">*</span></label>
					<input class="form-control" type="text" name="c_kode_barang" id="c_kode_barang" value="" placeholder="--- Scan Ulang Barcode --- " required>
				</div> -->
				<div class="form-gro mb-2 pt-1">
					<label class="col-form-label">Jenis Barang <span class="text-danger">*</span></label>
					<select class="form-control" name="jenis_barang" id="jenis_barang" required>
						<option value="">...</option>
						<option value="persediaan">Persediaan</option>
						<option value="jasa">Jasa</option>
					</select>
				</div>
				<div class="form-group mb-2 pt-1">
					<label class="col-form-label">Satuan Barang<span class="text-danger">*</span></label>
					<select class="form-control" name="id_satuan_barang" id="id_satuan_barang" required>
						<option value="">...</option>
						<?php foreach ($satuan_barang as $sb) { ?>
							<option value="<?= encrypt($sb->id_satuan) ?>"><?= $sb->nama_satuan ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group mb-2 pt-1">
					<label class="col-form-label">Dipakai di Cabang <span class="text-danger">*</span></label>
					<select class="form-control" name="id_cabang" id="id_cabang" required>
						<option value="">...</option>
						<?php foreach ($cabang as $c) { ?>
							<option value="<?= encrypt($c->id_cabang) ?>"><?= $c->nama_cabang ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group mb-2 pt-1">
					<label class="col-form-label">Batas Minimal Stock</label>
					<input class="form-control" type="number" name="batas_min_stock" id="batas_min_stock" value="">
				</div>
				<div class="form-group mb-2 pt-1">
					<label for="InputExperience" class="col-form-label">Keterangan</label>
					<textarea class="form-control" name="keterangan" id="keterangan" placeholder="..."></textarea>
				</div>
				<!--<div class="form-group mb-2 pt-1">
					<label for="InputExperience" class="col-form-label">Masukan Gambar</label>
					<input type="file" class="form-control" name="gambar">
				</div>-->


				<div class="form-group mb-2 pt-1">
					<label class=" col-form-label">ID Produk </label>
					<input class="form-control" type="text" name="id_produk" id="id_produk" value="" required>
				</div>
				<div class="form-group mb-2 pt-1">
					<label for="InputExperience" class="col-form-label">Tipe</label>
					<textarea class="form-control" name="tipe" id="tipe" placeholder="..."></textarea>
				</div>
				<div class="form-group mb-2 pt-1">
					<label class=" col-form-label">Nomor AKL</label>
					<input class="form-control" type="text" name="akl" id="akl" value="" required>
				</div>
				<div class="form-group mb-2 pt-1">
					<label class=" col-form-label">Kode Produk </label>
					<input class="form-control" type="text" name="kode_produk" id="kode_produk" value="" required>
				</div>


				<div class="modal-footer">
					<div class="is_aktif"></div>
					<input type="hidden" id="id_barang" name="id_barang">
					<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
					<?php if (isAdmin() || isStafAdmin()) { ?>
						<button type="button" class="btn btn-success btn-save">Simpan</button>
					<?php } ?>
				</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
</div>

<div id="file-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Master Data Barang </h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<!-- Form with enctype for file upload -->
			<?= form_open('#', array('id' => 'file-form', 'enctype' => 'multipart/form-data', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<!--<div class="form-group mb-2 pt-1">
                        <label for="InputExperience" class="col-form-label">Upload File :</label>
                        <input type="file" class="form-control" name="file" id="file_input">
                        <small class="text-danger">upload file .xlsx ONLY</small>
                    </div>-->
				<div>
					<form method="post" enctype="multipart/form-data" action="barang/import">
						<div class="box-body">
							<div class="form-group">
								<label for="exampleInputFile">Upload File :</label>
								<input type="file" name="berkas_excel" class="form-control" id="exampleInputFile">
								<small class="text-danger">upload file .xlsx ONLY</small>
							</div>
							<!--<button type="submit" class="btn btn-primary">Import</button>-->
							<button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i>&nbsp;&nbsp; Import</button>
						</div>
					</form>
				</div>
				<div>
					<br>
					<div class="text-left mb-2">
						<label for="InputExperience" class="col-form-label">Download Format :</label>&nbsp;&nbsp;&nbsp;
						<a href="javascript:" id="btn-download"><i class="fas fa-download"></i> Download File</a>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
					<!--<button type="button" id="save-form" class="btn btn-success btn-save">Simpan</button>-->
				</div>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>



<script>
	document.addEventListener('DOMContentLoaded', function() {
		$('#filter_kategori, #scan_barcode').change(function() {
			updateDatatable()
		})
		$('#scan_barcode').keyup(function() {
			updateDatatable()
		})

		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'barang/pagination',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.filter_kategori = $('#filter_kategori').val()
					e.scan_barcode = $('#scan_barcode').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 8],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'barang/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'barang'
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'barang/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_barang').val(data[0].id_barang)
					$("#main-modal #id_kategori").val(data[0].id_kategori);
					$('#main-modal #nama_barang').val(data[0].nama_barang)
					$('#main-modal #kode_barang').val(data[0].kode_barang)
					$('#main-modal #batas_min_stock').val(data[0].batas_min_stock)
					$('#main-modal #id_kategori').val(data[0].id_kategori)
					$('#main-modal #jenis_barang').val(data[0].jenis_barang)
					$('#main-modal #id_satuan_barang').val(data[0].id_satuan_barang)
					$('#main-modal #id_cabang').val(data[0].id_cabang)
					$('#main-modal #keterangan').val(data[0].keterangan)
					$('#main-modal #id_produk').val(data[0].id_produk)
					$('#main-modal #tipe').val(data[0].tipe)
					$('#main-modal #akl').val(data[0].akl)
					$('#main-modal #kode_produk').val(data[0].kode_produk)
				})
		})

		$('#btn-laporan-form').click(function() {
			$('#file-modal .form-control').val(null);
			$('#file-modal').modal();
			$('#file-modal #file-form').attr('action', 'barang/import');

			$('#file-modal #object').val(object);
		});


		$("#btn-download").click(function() {
			window.open("<?php echo base_url(); ?>barang/export", "_blank");
			$('#file-modal').modal('hide')

		});

	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>