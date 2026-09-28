<header class="page-header">
	<h2><i class="icons fa fa-file-invoice-dollar"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<style>
/* Untuk Chrome, Safari, Edge, Opera */
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Untuk Firefox */
input[type=number] {
    -moz-appearance: textfield;
}
</style>


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
									<th> # </th>
									<th> Nomor</th>
									<th> Nama Customer</th>
									<th> Nomor PO/INV</th>
									<th> Marketing </th>
									<th> Pembayaran </th>
									<th> Diajukan Oleh </th>
									<th> Tanggal </th>
									<th> Status </th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Pengajuan Approval Faktur Pajak</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<div class="form-group">
						<label for="tanggal" class="form-control-label">Tanggal <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tanggal" name="tanggal" required>
						</div>
					</div>
					<div class="form-group">
						<label for="id_marketing" class="form-control-label">Nama Marketing <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_marketing" name="id_marketing" required>
							<option value="">- Pilih Marketing -</option>
							<option value = "1">Office / Kantor Pusat</option>
							<?php
							foreach ($list_marketing as $row) {
								echo '<option value="' . $row->pengguna_id . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>	
					<!--<div class="form-group">
						<label for="id_customer" class="form-control-label">Nama Customer <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_customer" name="id_customer" required>
							<option value="">- Pilih Customer -</option>
							<?php
							foreach ($list_cust as $row) {
								echo '<option value="' . $row->id_customer . '">' . $row->nama_customer . '</option>';
							}
							?>
						</select>
					</div>	-->
					<div class="form-group">
						<label for="id_customer" class="form-control-label">Nama Customer <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_customer" name="id_customer" required>
							<option value="">- Pilih Customer -</option>
							<?php
							foreach ($list_cust as $row) {
								echo '<option value="' . $row->id_pelanggan . '">' . $row->identitas_pelanggan . '</option>';
							}
							?>
						</select>
					</div>	
					<div class="form-group">
						<label for="no_po" class="form-control-label">Nomor PO / Invoice <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="no_po" name="no_po" required>
					</div>
					<br>
					<strong> Nominal PO / Invoice </strong>
					<div class="form-group">
						<label for="dpp" class="form-control-label">DPP <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="dpp" name="dpp" required>
						<small class="form-text text-muted">Gunakan tanda titik (.) untuk desimal, bukan koma (,)</small>
					</div>
					<div class="form-group">
						<label for="ppn" class="form-control-label">PPN <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="ppn" name="ppn" required>    
						<small class="form-text text-muted">Gunakan tanda titik (.) untuk desimal, bukan koma (,)</small>
					</div>
					<div class="form-group">
						<label for="pembayaran" class="form-control-label">Sistem Pembayaran<span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="pembayaran" name="pembayaran" required>
							<option value="">- Pilih Sistem Pembayaran -</option>
							<option value = "CREDIT">CREDIT</option>
							<option value = "NET 30">NET 30</option>
              <option value = "CASH">CASH</option>
							<option value = "COD">COD</option>
						</select>
					</div>	
					<div class="form-group">
						<label for="alasan" class="form-control-label">Alasan Permintaan Penerbitan Faktur Pajak Dimuka<span class="text-danger">*</span> :</label>
							<textarea type="text" class="form-control" id="alasan" name="alasan" cols="10" rows="3"></textarea>
					</div>
					<div class="form-group">
						<label for="link_lampiran" class="form-control-label">Link Lampiran (SPH, PO, Inv, Bukti Permintaan Customer, dll)<span class="text-danger">*</span> :</label>
							<textarea type="text" class="form-control" id="link_lampiran" name="link_lampiran" cols="10" rows="3"></textarea>
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
				url: 'surat_new/pagination/app_pajak',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				 targets: [0, 1, 3, 4, 5, 6, 7, 8],
				 className: 'text-center'
			}]
		})
		function updateDatatable() {
		table.ajax.reload(null, false)
	}
		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'surat_new'
			$('#main-modal #modal-form').attr('action', 'surat_new/addSrt/app_pajak')
			$('#main-modal').modal()
		})

		

		
		
	})

	
</script>
