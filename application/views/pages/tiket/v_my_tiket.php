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
			<?php if (isAdmin() || sessPenggunaId()==737 || sessPenggunaId()==15 || sessPenggunaId()==72 || sessPenggunaId()==754 || sessPenggunaId()==755 || sessPenggunaId()==757 || sessPenggunaId()==751){ ?>
				<a href="javascript:;" id="btn-show-disposTeknisi-form" class="btn btn-sm btn-success">&nbsp;Dispatch</a>
      <?php } ?>
				<a href="javascript:;" id="btn-show-logTiket-form" class="btn btn-sm btn-success">&nbsp;Update Tiket</a>
				<!--<a href="javascript:;" id="btn-show-visit-form" class="btn btn-sm btn-success">&nbsp;Ajukan Visit</a>
				<a href="javascript:;" id="btn-show-biaya-form" class="btn btn-sm btn-success">&nbsp;Submit Pengajuan Biaya</a>-->
				<a href="javascript:;" id="btn-show-laporan-form" class="btn btn-sm btn-success">&nbsp;Submit Laporan Akhir</a>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kode Tiket</th>
							<th> Pelanggan</th>
							<th> Prioritas</th>
							<th> Pembuat Tiket</th>
							<!--<th> Visit</th>
							<th>Pembuatan Surat</th>
							<th> Surat Jalan</th>
							<th> Pembiayaan</th>-->
							<th> Status</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>


<!-- Dispatch Teknisi -->
<div id="dispos-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Dispatch Teknisi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'disposTeknisi-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-tiket-form">
					<div class="form-group">
						<label for="kode_tiket_lengkap" class="form-control-label">Kode Tiket <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kode_tiket_lengkap" name="kode_tiket_lengkap" required>
							<option value="">- Pilih Kode Tiket -</option>
							<?php
							foreach ($list_kode_akhir as $row) {								
								echo "<option value='".$row->id_tiket."PengHubunG".$row->pelanggan."PengHubunG".$row->kota."PengHubunG".$row->nama_topik."PengHubunG".$row->subject."PengHubunG".$row->deskripsi."'><a class='pelanggan_link'>" . $row->kode_tiket . "</a></option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="pelanggan" class="form-control-label">Pelanggan :</label>
						<label id="tampil_pelanggan_dispos" for="tampil_pelanggan" class="form-control" readonly="readonly">Identitas Pelanggan</label>
					</div>
					<div class="form-group">
						<label for="kota_visit" class="form-control-label">Kota <span class="text-danger">*</span> :</label>
						<label id="tampil_kota_dispos" class="form-control" readonly="readonly">Kota Asal Pelanggan</label>
					</div>
					<div class="form-group">
						<label for="kategori" class="form-control-label">Kategori<span class="text-danger">*</span> :</label>
						<label id="tampil_kategori" class="form-control" readonly="readonly">Kategori</label>
					</div>
					<div class="form-group">
						<label for="subject" class="form-control-label">Subject<span class="text-danger">*</span> :</label>
						<label id="tampil_subject" class="form-control" readonly="readonly">Subject</label>
					</div>
					<div class="form-group">
						<label for="deskripsi" class="form-control-label">Deskripsi<span class="text-danger">*</span> :</label>
						<label id="tampil_deskripsi" class="form-control" readonly="readonly">Deskripsi</label>
					</div>

					<div class="form-group">
						<label for="id_penerima" class="form-control-label">Agent/PIC <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" name="id_penerima" id="id_penerima">
							<option value="">- Pilih Agent/PIC -</option>
							<?php
							foreach ($pengguna as $row) {
								echo '<option value="' . $row->pengguna_id . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>

					<div class="form-group">
						<label for="pic_support" class="form-control-label">PIC Support <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" name="pic_support[]" id="pic_support[]" data-placeholder="Pilih PIC Support" multiple>
							<?php
							foreach ($pengguna as $row) {
								echo '<option value="' . $row->pengguna_id . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>
					
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_tiket_7" name="id_tiket_7" value="">
				<input type="hidden" id="subject" name="subject" value="">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-success btn-save">Update</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<!-- Update Log Tiket -->
<div id="log-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Detail Update Tiket</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'logTiket-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-tiket-form">
					<div class="form-group">
						<label for="kode_tiket" class="form-control-label">Kode Tiket <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kode_tiket" name="kode_tiket" required>
							<option value="">- Pilih Kode Tiket -</option>
							<?php
							foreach ($list_kode_akhir as $row) {								
								echo "<option value='".$row->id_tiket."PengHubunG".$row->pelanggan."PengHubunG".$row->kota."'><a class='pelanggan_link'>" . $row->kode_tiket . "</a></option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="pelanggan" class="form-control-label">Pelanggan :</label>
						<label id="tampil_pelanggan_log" for="tampil_pelanggan" class="form-control" readonly="readonly">Identitas Pelanggan</label>
					</div>
					<div class="form-group">
						<label for="kota_visit" class="form-control-label">Kota <span class="text-danger">*</span> :</label>
						<label id="tampil_kota_log" class="form-control" readonly="readonly">Kota Asal Pelanggan</label>
					</div>
					<div class="form-group">
						<label for="deskripsi" class="form-control-label">Catatan :</label>
						<textarea class="form-control" name="deskripsi" id="deskripsi" cols="10" rows="2"></textarea>
					</div>
					<div class="form-group">
						<label for="attachment" class="form-control-label">File Pendukung <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Goggle Drive untuk data pendukung" id="attachment" name="attachment" required>
					</div>
					
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_tiket" name="id_tiket" value="">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-success btn-save">Update</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<!-- Pengajuan Visit -->
<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Detail Pengajuan Visit</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'visit-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-tiket-form">
					<div class="form-group">
						<label for="kode_tiket_visit" class="form-control-label">Kode Tiket <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kode_tiket_visit" name="kode_tiket_visit" required>
							<option value="">- Pilih Kode Tiket -</option>
							<?php
							foreach ($list_kode as $row) {								
								echo "<option value='".$row->id_tiket."PengHubunG".$row->pelanggan."PengHubunG".$row->kota."'><a class='pelanggan_link'>" . $row->kode_tiket . "</a></option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="pelanggan_visit" class="form-control-label">Pelanggan :</label>
						<label id="tampil_pelanggan_visit" for="tampil_pelanggan_visit" class="form-control" readonly="readonly">Identitas Pelanggan</label>
					</div>
					<div class="form-group">
						<label for="kota_visit" class="form-control-label">Kota <span class="text-danger">*</span> :</label>
						<label id="tampil_kota_visit" class="form-control" readonly="readonly">Kota Asal Pelanggan</label>
					</div>
					<div class="form-group">
						<label for="deskripsi_visit" class="form-control-label">Catatan :</label>
						<textarea class="form-control" name="deskripsi_visit" id="deskripsi_visit" cols="10" rows="2"></textarea>
					</div>
					<div class="form-group">
						<label for="attachment_visit" class="form-control-label">File Pendukung <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Goggle Drive untuk data pendukung" id="attachment_visit" name="attachment_visit" required>
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
				<input type="hidden" id="id_tiket_0" name="id_tiket_0" value="">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-success btn-save">Ajukan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- Pengajuan Biaya -->
<div id="biaya-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Detail Pengajuan Biaya</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'biaya-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-tiket-form">
					<div class="form-group">
						<label for="kode_tiket_biaya" class="form-control-label">Kode Tiket <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kode_tiket_biaya" name="kode_tiket_biaya" required>
							<option value="">- Pilih Kode Tiket -</option>
							<?php
							foreach ($list_kode_biaya as $row) {
								echo "<option value='".$row->stat_sudin."PengHubunG".$row->link_sudin."PengHubunG".$row->id_tiket."'><a class='biaya_link'>" . $row->kode_tiket . "</a></option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="surat_dinas" class="form-control-label">Status Surat Dinas :</label>
						<label id="tampil_surat_dinas" for="surat_dinas" class="form-control" readonly="readonly">Status Surat Dinas</label>
					</div>
<!--				
                    <div class="form-group">
						<label for="file_biaya" class="form-control-label">File Surat Pengajuan Biaya <span class="text-danger">*</span> :</label>
						<input type='text' class='form-control' placeholder='Masukkan Link Google Drive' id='file_biaya' name='file_biaya' required>
					</div>
-->
					<div class="form-group">
						<label for="pb" class="form-control-label">Kode Surat Biaya Dinas <span class="text-danger">*</span> :</label>
						<select class="form-control" id="pb" name="pb" required>
							<option value="">- Pilih Kode -</option>
							<?php
							foreach ($list_pb_awal as $row1) {
								echo "<option value='".$row1->idPB."'>" .$row1->kodePB. "</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="catatan" class="form-control-label">Catatan :</label>
						<textarea class="form-control" name="catatan" id="catatan" cols="10" rows="2"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_tiket_2" name="id_tiket_2">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-success btn-save">Ajukan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- Pengajuan Akhir -->
<div id="laporan-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Laporan Akhir Tiket</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'laporan-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-tiket-form">
					<div class="form-group">
						<label for="kategori" class="form-control-label">Kode Tiket <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kode_laporan" name="kode_laporan" required>
							<option value="">- Pilih Kode Tiket -</option>
							<?php
							foreach ($list_kode_akhir as $row) {
								echo "<option value='".$row->deadline."PengHubunG".$row->stat_visit."PengHubunG".$row->id_tiket."'><a class='biaya_link'>" . $row->kode_tiket . "</a></option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="deadline_dinas" class="form-control-label">Deadline :</label>
						<label id="tampil_deadline" for="tampil_deadline" class="form-control" readonly="readonly">Batas Waktu Tiket</label>
					</div>
					
					<div class="form-group">
						<label for="feedback_desk" class="form-control-label">Deskripsi Pekerjaan <span class="text-danger">*</span> :</label>
						<textarea class="form-control" placeholder="WAJIB memasukkan Deskripsi Pekerjaan yang telah dilakukan untuk Tiket ini" name="des_peker" id="des_peker" cols="10" rows="10"></textarea>
					</div>
					<div class="form-group">
						<label for="bukti_kerja" class="form-control-label">Bukti Kerja <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Google Drive" id="bukti_kerja" name="bukti_kerja" required>
					</div>
<!--
					<div class="form-group">
						<label for="laporan_biaya" class="form-control-label">Laporan Biaya <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Google Drive" id="laporan_biaya" name="laporan_biaya" required>
					</div>
-->
					<div class="form-group">
						<label for="laporan_pb" class="form-control-label">Kode Laporan Surat Biaya Dinas <span class="text-danger">*</span> :</label>
						<select class="form-control" id="laporan_pb" name="laporan_pb" required>
							<option value="">- Pilih Kode -</option>
							<?php
							foreach ($list_pb_akhir as $row1) {
								echo "<option value='".$row1->idPB."'>" .$row1->kodePB. "</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="feedback_cust" class="form-control-label">File Feedback Customer <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Goggle Drive File Feedback Customer" id="feedback_cust" name="feedback_cust" required>
					</div>
					<div class="form-group">
						<label for="feedback_desk" class="form-control-label">Deskripsi Feedback Customer :</label>
						<textarea class="form-control" name="feedback_desk" id="feedback_desk" cols="10" rows="2"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_tiket_3" name="id_tiket_3">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-success btn-save">Kirim Laporan</button>
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
				url: 'tiket/pagination/my_tiket',
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
		
		$('#btn-show-logTiket-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'tiket'
			$('#log-modal #logTiket-form').attr('action', 'tiket/update/logTiket')
			$('#log-modal').modal()
		})

		$('#btn-show-disposTeknisi-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'tiket'
			$('#dispos-modal #disposTeknisi-form').attr('action', 'tiket/update/disposTeknisi')
			$('#dispos-modal').modal()
		})
		
		$('#btn-show-visit-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'tiket'
			$('#main-modal #visit-form').attr('action', 'tiket/update/ajukanVisit')
			$('#main-modal').modal()
		})
		
		$('#btn-show-biaya-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'tiket'
			$('#biaya-modal #biaya-form').attr('action', 'tiket/update/ajukanBiaya')
			$('#biaya-modal').modal()
		})
			
		$('#btn-show-laporan-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'tiket'
			$('#laporan-modal #laporan-form').attr('action', 'tiket/update/kirimLaporan')
			$('#laporan-modal').modal()
		})
		
		Array.prototype.forEach.call(document.getElementsByName('kode_tiket'),
		function (elem) {
			elem.addEventListener('change', function() {
				let 	text	= this.value;
				const 	isi		= text.split("PengHubunG");
				let		idTiket		= isi[0],
						tampil_cust = isi[1],
						tampil_kota = isi[2];						
				
				if (text == ""){
					tampil_cust	= "Identitas Pelanggan";
					tampil_kota	= "Informasi Kota Asal Pelanggan";
				}
				
				document.getElementById('tampil_pelanggan_log').innerHTML = tampil_cust;
				document.getElementById('tampil_kota_log').innerHTML = tampil_kota;
				document.getElementById('id_tiket').value = idTiket;				
			});
		});

		Array.prototype.forEach.call(document.getElementsByName('kode_tiket_lengkap'),
		function (elem) {
			elem.addEventListener('change', function() {
				let 	text	= this.value;
				const 	isi		= text.split("PengHubunG");
				let		idTiket		= isi[0],
						tampil_cust = isi[1],
						tampil_kota = isi[2],
						tampil_kat = isi[3],
						tampil_sub = isi[4],
						tampil_des = isi[5];						
				
				if (text == ""){
					tampil_cust	= "Identitas Pelanggan";
					tampil_kota	= "Informasi Kota Asal Pelanggan";
					tampil_kat	= "Kategori Tiket";
					tampil_sub	= "Subject Tiket";
					tampil_des	= "Deskripsi Tiket";
				}
				
				document.getElementById('tampil_pelanggan_dispos').innerHTML = tampil_cust;
				document.getElementById('tampil_kota_dispos').innerHTML = tampil_kota;
				document.getElementById('tampil_kategori').innerHTML = tampil_kat;
				document.getElementById('tampil_subject').innerHTML = tampil_sub;
				document.getElementById('tampil_deskripsi').innerHTML = tampil_des;
				document.getElementById('subject').value = tampil_sub;
				document.getElementById('id_tiket_7').value = idTiket;				
			});
		});

		Array.prototype.forEach.call(document.getElementsByName('kode_tiket_visit'),
		function (elem) {
			elem.addEventListener('change', function() {
				let 	text	= this.value;
				const 	isi		= text.split("PengHubunG");
				let		idTiket		= isi[0],
						tampil_cust = isi[1],
						tampil_kota = isi[2];						
				
				if (text == ""){
					tampil_cust	= "Identitas Pelanggan";
					tampil_kota	= "Informasi Kota Asal Pelanggan";
				}
				
				document.getElementById('tampil_pelanggan_visit').innerHTML = tampil_cust;
				document.getElementById('tampil_kota_visit').innerHTML = tampil_kota;
				document.getElementById('id_tiket_0').value = idTiket;				
			});
		});
		
		Array.prototype.forEach.call(document.getElementsByName('kode_tiket_biaya'),
		function (elem) {
			elem.addEventListener('change', function() {
				let text 	= this.value;
				const isi 	= text.split("PengHubunG");
				let idTiket				= isi[2],
					bukaini 			= isi[1],
					status_surat_dinas 	= isi[0],
					tampil_sudin 		= '';
				
				if (text == ""){
					tampil_sudin	= "File Surat Dinas";
				}else {
					if (status_surat_dinas=="1"){
						tampil_sudin = "<b>Dalam Proses</b>";
					}else if (status_surat_dinas=="2"){
						tampil_sudin = "<b>Disetujui - </b> <a href='"+bukaini+"'> Cek File Surat Dinas </a>";
					}else if (status_surat_dinas == '3'){
						tampil_sudin = "<b>Ditolak</b>";
					}
				}
				document.getElementById('tampil_surat_dinas').innerHTML = tampil_sudin;
				document.getElementById('id_tiket_2').value = idTiket;
			});
		});
		
		Array.prototype.forEach.call(document.getElementsByName('kode_laporan'),
		function (elem) {
			elem.addEventListener('change', function() {
				let text = this.value;
				const isi = text.split("PengHubunG");
				let	idTiket			= isi[2],
					tampil_deadline = isi[0],
					status_visit	= isi[1],
					tampil_laporan	= "";
				
				if (text == ""){
					tampil_deadline	= "Batas Waktu Tiket";
					tampil_laporan 	= "Masukkan Link Google Drive"
					$("#laporan_biaya").attr("readonly", false);
				}else {
					if (status_visit=="2"){
						$("#laporan_pb").attr("disabled", false);
					}else{
						tampil_laporan = "Tidak Memerlukan Laporan Biaya"
						$("#laporan_pb").attr("disabled", true);
					}
				}
				document.getElementById('tampil_deadline').innerHTML = tampil_deadline;
				//document.getElementById('laporan_biaya').placeholder = tampil_laporan;
				document.getElementById('id_tiket_3').value = idTiket;
			});
		});

	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>