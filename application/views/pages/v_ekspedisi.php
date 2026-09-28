<header class="page-header">
	<h2><i class="icons fas fa-database"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="">
			<?php if (sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 33 || sessPenggunaId() == 7 || sessPenggunaId() == 749 || sessPenggunaId() == 73 || sessPenggunaId() == 23 || sessPenggunaId() == 763 || sessPenggunaId() == 769) { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Ekspedisi</a>
				<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp;Print Rekapan</a>
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
							<th> Nama PIC </th>
							<th> Jabatan </th>
							<th> Contact Person (HP) </th>
							<th> MOU </th>
							<th> Legalitas </th>
							<th> Identitas </th>
							<th> Tracking </th>
							<th> Keterangan </th>
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
						<input type="text" class="form-control" id="alamat_ekspedisi" name="alamat_ekspedisi" required>
					</div>
					<div class="form-group">
						<label for="nama_pic" class="form-control-label">Nama PIC <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="nama_pic" name="nama_pic" required></textarea>
					</div>
					<div class="form-group">
						<label for="jabatan" class="form-control-label">Jabatan <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="jabatan" name="jabatan" required></textarea>
					</div>
					<div class="form-group">
						<label for="contact" class="form-control-label">Contact Person (HP) <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="contact" name="contact" required></textarea>
					</div>
					<div class="form-group">
						<label for="mou" class="form-control-label">Link MOU <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="mou" name="mou" required></textarea>
					</div>
					<div class="form-group">
						<label for="legalitas" class="form-control-label">Link Dokumen Legalitas Expedisi <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="legalitas" name="legalitas" required></textarea>
					</div>
					<div class="form-group">
						<label for="identitas" class="form-control-label">Link Dokumen Identitas Expedisi <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="identitas" name="identitas" required></textarea>
					</div>
					<div class="form-group">
						<label for="link_tracking" class="form-control-label">Link Tracking (Jika Ada) <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="link_tracking" name="link_tracking" required></textarea>
					</div>
					<div class="form-group">
						<label for="keterangan" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="keterangan" name="keterangan" required></textarea>
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


<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Rekapan Ekspedisi </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-marketing-form">
					<label for="bukti_penerima" class="form-control-label">Pilih Tanggal Input Data Ekspedisi</label>
					<div class="form-group" style="display: flex;">
						<div style="flex: 50%;padding: 10px;">
							<input class="form-control" data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
						</div>
						<div style="flex: 50%;padding: 10px;">
							<input class="form-control" data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
						</div>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<!-- <button type="button" id="btn-cetak" class="btn btn-primary btn-clear-form" >Cetak</button>-->
				<button type="button" id="btn-export" class="btn btn-success btn-clear-form">Export Excel</button>
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
				url: 'ekspedisi/pagination',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 4, 5, 6, 7, 8, 9],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'ekspedisi/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'ekspedisi'
			$('#main-modal #modal-form').attr('action', 'ekspedisi/update')
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
					$('#main-modal #nama_pic').val(data[0].nama_pic)
					$('#main-modal #jabatan').val(data[0].jabatan)
					$('#main-modal #mou').val(data[0].mou)
					$('#main-modal #legalitas').val(data[0].legalitas)
					$('#main-modal #identitas').val(data[0].identitas)
					$('#main-modal #link_tracking').val(data[0].link_tracking)
					$('#main-modal #keterangan').val(data[0].keterangan)
				})
		})

		$('#btn-laporan-form').click(function() {
			$('#main-modal-marketing').modal()


		})

		$("#btn-export").click(function() {

			tglawal = $("#tglawal").val();
			tglakhir = $("#tglakhir").val();
			window.open("<?php echo base_url(); ?>ekspedisi/exportlaporan/search?tglawal=" + encodeURIComponent(tglawal) + "&tglakhir=" + encodeURIComponent(tglakhir), "_blank");
			$('#main-modal-marketing').modal('hide')

		});

	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>