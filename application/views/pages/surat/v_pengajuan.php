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
				<a href="surat/show/pengajuan/PB" id="btn-a-pb" class="btn btn-sm btn-success">
				    <i class="fas fa-plus"></i>&nbsp;&nbsp;Ajukan Pembiayaan Dinas
				</a>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kode Surat</th>
							<th> Kategori</th>
							<th> Perihal</th>
							<th> Laporan</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Detail Pengajuan Visit</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-tiket-form">
					<div class="form-group">
						<label for="kota" class="form-control-label">Kota<span class="text-danger">*</span> :</label>
						<select class="form-control" id="kota" name="kota" required>
							<option value="">- Pilih Kota/Kabupaten-</option>
							<option value="">Pekanbaru</option>
							<option value="">Bangkinang</option>
							<option value="">Dumai</option>
							<option value="">Rokan Hulu</option>
							<option value="">Rokan Hilir</option>
						</select>
					</div>
					<div class="form-group">
						<label for="pelanggan" class="form-control-label">Pelanggan :</label>
						<label id="tampil_pelanggan" for="tampil_pelanggan" class="form-control" readonly="readonly">Identitas Pelanggan</label>
					</div>
					<div class="form-group">
						<label for="kota_visit" class="form-control-label">Kota <span class="text-danger">*</span> :</label>
						<label id="tampil_kota_visit" class="form-control" readonly="readonly">Kota Asal Pelanggan</label>
					</div>
					<div class="form-group">
						<label for="deskripsi" class="form-control-label">Catatan :</label>
						<textarea class="form-control" name="deskripsi" id="deskripsi" cols="10" rows="2"></textarea>
					</div>
					<div class="form-group">
						<label for="attachment" class="form-control-label">File Pendukung <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Goggle Drive untuk data pendukung" id="attachment" name="attachment" required>
					</div>
					<div class="form-group">
						<label for="waktu" class="form-control-label">Durasi Visit <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="start" name="start" required>
							<span class="input-group-text border-start-0 border-end-0 rounded-0">
								to
							</span>
							<input type="text" class="form-control" id="end" name="end" required>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_tiket" name="id_tiket" value="">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-success btn-save">Ajukan</button>
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
				url: 'surat/pagination/my_surat',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5],
				className: 'text-center'
			}]
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>