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
		<div class="card-body">
			<?php if (sessPenggunaId() == 15 || sessPenggunaId() == 1 || sessPenggunaId() == 769 || sessPenggunaId() == 749 || sessPenggunaId() == 7) { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;&nbsp;Stock Opname</a>
				<!-- <a href="javascript:;" id="btn-cetaklaporan-form" class="btn btn-sm btn-success"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp;Cetak Rekapan</a> -->
			<?php  } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> Tanggal</th>
							<th> Nomor </th>
							<th> Tanggal Mulai </th>
							<th> Gudang </th>
							<th> Status </th>
							<th> Keterangan </th>
							<th> Pelaksana </th>
							<th> Penanggung Jawab </th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Perintah Stock Opname</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<div class="form-group">
						<label for="id_gudang" class="form-control-label">Gudang <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_gudang" name="id_gudang" required>
							<option value="">- Pilih Gudang -</option>
							<?php
							foreach ($gudang as $row) {
								echo '<option value="' . $row->id_gudang . '">' . $row->nama_gudang . '</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="tanggal_mulai" class="form-control-label">Tanggal Mulai <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
						</div>
					</div>
					<div class="form-group">
						<label for="id_pelaksana" class="form-control-label">Dikerjakan Oleh <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_pelaksana" name="id_pelaksana" required>
							<option value="">- Pilih Pelaksana -</option>
							<?php
							foreach ($list_nama as $row) {
								echo '<option value="' . $row->pengguna_id . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="keterangan" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
						<textarea name="keterangan" class="form-control" id="keterangan" cols="15" rows="3"></textarea>
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

<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Cetak Data Pelanggan </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-marketing-form">
					<div class="form-group" style="display: flex;">
						<div style="flex: 50%;padding: 10px;">
							<input class="form-control" data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
						</div>
						<div style="flex: 50%;padding: 10px;">
							<input class="form-control" data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Nama Marketing</label>
						<select class="select-transaction input-group-sm form-control" name="namamarketing" id="namamarketing">
							<?php if ($nama_marketing != NULL): ?>
								<option value=''>Semua Marketing</option>
								<?php foreach ($nama_marketing as $value): ?>
									<option value="<?php echo $value->nama; ?>"><?php echo $value->nama; ?></option>
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
				<!-- <button type="button" id="btn-cetak" class="btn btn-primary btn-clear-form" >Cetak</button>-->
				<button type="button" id="btn-export" class="btn btn-success btn-clear-form">Export Excel</button>
				<button type="button" id="btn-exportCOM" class="btn btn-success btn-clear-form">Export Excel Commisioning</button>
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
				url: 'stock_opname/pagination',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7, 8],
				className: 'text-center'
			}]
		})

		function updateDatatable() {
			table.ajax.reload(null, false)
		}
		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'stock_opname'
			$('#main-modal #modal-form').attr('action', 'stock_opname/add')
			$('#main-modal').modal()
		})





		$('#btn-cetaklaporan-form').click(function() {
			$('#main-modal-marketing').modal()


		})

		$("#btn-export").click(function() {

			tglawal = $("#tglawal").val();
			tglakhir = $("#tglakhir").val();
			idmarketing = $("#namamarketing").val();
			namamarketing = $("#namamarketing option:selected").text();
			window.open("<?php echo base_url(); ?>pelanggan/exportlaporan/search?tglawal=" + encodeURIComponent(tglawal) + "&tglakhir=" + encodeURIComponent(tglakhir) + "&idmarketing=" + encodeURIComponent(idmarketing) + "&namamarketing=" + encodeURIComponent(namamarketing), "_blank");
			$('#main-modal-marketing').modal('hide')

		});




	})
</script>