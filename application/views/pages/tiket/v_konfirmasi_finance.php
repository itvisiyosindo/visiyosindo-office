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
				<a href="javascript:;" id="btn-show-pembiayaan-form" class="btn btn-sm btn-success">&nbsp;Persetujuan Pembiayaan</a>
				<a href="javascript:;" id="btn-show-laporanAkhir-form" class="btn btn-sm btn-success">&nbsp;Persetujuan Laporan Biaya</a>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kode Tiket</th>
							<th> Prioritas </th>
							<th> Teknisi</th>
							<th> Status Visit</th>
							<th> Surat Dinas</th>
							<th> Pengajuan Biaya</th>
							<th> Status</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="pembiayaan-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Persetujuan Pembiayaan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'pembiayaan-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-tiket-form">
					<div class="form-group">
						<label for="kategori" class="form-control-label">Kode Tiket <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kode_tiket_persetujuan" name="kode_tiket_persetujuan" required>
							<option value="">- Pilih Kode Tiket -</option>
							<?php
								foreach ($list_kode as $row) {
									echo "<option value='".$row->link_sudin."PengHubunG".$row->file_pengajuan_biaya."PengHubunG".$row->file_gocorp."PengHubunG".$row->id_tiket."'>".$row->kode_tiket."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="surat_dinas" class="form-control-label">Surat Dinas</label>
						<label id="status_surat_dinas" for="status_surat_dinas" class="form-control" readonly="readonly">Status Surat Dinas</label>
					</div>
					<div class="form-group">
						<label for="lbl_form_pengajuan_biaya" class="form-control-label">Form Pengajuan Biaya</label>
						<label id="form_pengajuan_biaya" for="form_pengajuan_biaya" class="form-control" readonly="readonly">Form Pengajuan Pembiayaan</label>
					</div>
<!--
					<div class="form-group">
						<label for="file_persetujuan_biaya" class="form-control-label">File Persetujuan Pembiayaan <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Goggle Drive File Persetujuan Pembiayaan" id="file_persetujuan_biaya" name="file_persetujuan_biaya" required>
					</div>
					<div class="form-group">
						<label for="bukti_tf" class="form-control-label">Bukti Transfer <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Goggle Drive untuk Bukti Transfer" id="bukti_tf" name="bukti_tf" required>
					</div>
-->
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_tiket" name="id_tiket">
				<input type="hidden" id="id_pb" name="id_pb">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-success btn-save">Setujui</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<div id="laporan-akhir-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Penyelesaian Pembiayaan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'laporan-akhir-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-tiket-form">
					<div class="form-group">
						<label for="iket_laporan_biaya" class="form-control-label">Kode Tiket <span class="text-danger">*</span> :</label>
						<select class="form-control" id="Kode_Tiket_laporan_biaya" name="Kode_Tiket_laporan_biaya" required>
							<option value="">- Pilih Kode Tiket -</option>
							<?php
								foreach ($list_kode_akhir as $row) {
									echo "<option value='".$row->kode_tiket."PengHubunG".$row->file_laporan_biaya."PengHubunG".$row->id_tiket."PengHubunG".$row->file_laporan_teknisi."'>".$row->kode_tiket."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="agent" class="form-control-label">Form Laporan Biaya</label>
						<label id="file_laporan_teknisi" for="file_laporan_teknisi" class="form-control" readonly="readonly">Form Laporan </label>
					</div>
					<div class="form-group">
						<label for="file_lap_akhir_teknisi" class="form-control-label">Status Laporan Akhir Teknisi :</label>
						<label id="file_lap_akhir_teknisi" for="file_lap_akhir_teknisi" class="form-control" readonly="readonly">Status Laporan Akhir Teknisi</label>
					</div>
<!--
					<div class="form-group">
						<label for="file_close_biaya" class="form-control-label">File Persetujuan Laporan Biaya <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Goggle Drive File Persetujuan Pembiayaan" id="file_close_biaya" name="file_close_biaya" required>
					</div>
-->
					<div class="form-group">
						<label for="catatan" class="form-control-label">Catatan :</label>
						<textarea class="form-control" name="catatan" id="catatan" cols="10" rows="2"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_tiket_2" name="id_tiket_2">
				<input type="hidden" id="id_pb_2" name="id_pb_2">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-success btn-save">Setujui Laporan</button>
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
				url: 'tiket/pagination/konfirmasi_finance',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7],
				className: 'text-center'
			}]
		})
		
		$('#btn-show-pembiayaan-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'tiket'
			$('#pembiayaan-modal #pembiayaan-form').attr('action', 'tiket/update/finance_pembiayaan')
			$('#pembiayaan-modal').modal()
		})
		
		$('#btn-show-laporanAkhir-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'tiket'
			$('#laporan-akhir-modal #laporan-akhir-form').attr('action', 'tiket/update/finance_laporan')
			$('#laporan-akhir-modal').modal()
		})
		
		Array.prototype.forEach.call(document.getElementsByName('kode_tiket_persetujuan'),
		function (elem) {
			elem.addEventListener('change', function() {
				let text		= this.value;
				const isi 		= text.split("PengHubunG");
				let idTiket		= isi[3],
					bukaini 	= 'https://youtube.com',
					link_sudin 	= isi[0],
					link_biaya 	= isi[1],
					link_gocorp	= isi[2],
					tampil_sudin	= "",
					tampil_biaya	= "",
					tampil_gocorp	= "";
					
				if (text == ""){
					tampil_sudin	= "File Surat Dinas";
					tampil_biaya	= "Form Pengajuan Biaya";
					tampil_gocorp	= "Form Pengajuan GoCorp";
				}else {
					tampil_sudin	= "<b>Disetujui - </b><a href='"+link_sudin+"'>Cek File Surat Dinas</a>";					
					if (link_biaya==""){
						tampil_biaya = "<b>Belum Diajukan Oleh Teknisi</b>";
					}else{
						tampil_biaya = "<b>Sudah Diajukan - </b><a href='surat/show/detail_surat/PB/"+link_biaya+"/1' target='blank'> Cek Form Pengajuan Biaya </a>";
					}
					
					if (link_gocorp==""){
						tampil_gocorp = "<b>Belum Diajukan Oleh Teknisi</b>";
					}else{
						tampil_gocorp = "<b>Sudah Diajukan - </b><a href='"+link_gocorp+"'> Cek Form Pengajuan GoCorp </a>";
					}
				}			

				document.getElementById('status_surat_dinas').innerHTML = tampil_sudin;
				document.getElementById('form_pengajuan_biaya').innerHTML = tampil_biaya;
				document.getElementById('id_tiket').value = idTiket;
				document.getElementById('id_pb').value = link_biaya;
			});
		});
		
		Array.prototype.forEach.call(document.getElementsByName('Kode_Tiket_laporan_biaya'),
		function (elem) {
			elem.addEventListener('change', function() {
				let text		= this.value;
				const isi 		= text.split("PengHubunG");
				let bukaini 			= 'https://youtube.com',
					idTiket				= isi[2],
					link_Laporan_biaya 	= isi[1],
					link_Laporan_tkn 	= isi[3],
					tampil_lb			= "",
					tampil_lt           = "";
					
				if (text == ""){
					tampil_lb	= "File Laporan Biaya";
				}else if (link_Laporan_biaya==""){
					tampil_lb = "<b>Belum Dilaporkan Oleh Teknisi</b>";
				}else{
					tampil_lb = "<b>Sudah Diajukan - </b><a href='surat/show/detail_surat/laporan_PB/"+link_Laporan_biaya+"/1' target='blank'> Cek Laporan Akhir Biaya </a>";
				}
				
				if (text == ""){
					tampil_lt	= "File Laporan Akhir Teknisi";
				}else if (link_Laporan_tkn==""){
					tampil_lt = "<b>Belum Dilaporkan Oleh Teknisi</b>";
				}else{
					tampil_lt = "<b>Sudah Dilaporkan - </b><a href='"+link_Laporan_tkn+"'> Cek Laporan Akhir Teknisi </a>";
				}

				document.getElementById('file_laporan_teknisi').innerHTML = tampil_lb;
				document.getElementById('file_lap_akhir_teknisi').innerHTML = tampil_lt;
				document.getElementById('id_tiket_2').value = idTiket;
				document.getElementById('id_pb_2').value = link_Laporan_biaya;
			});
		});
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>