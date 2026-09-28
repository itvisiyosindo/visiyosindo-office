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
			<a href="javascript:;" id="btn-show-suratDinas-form" class="btn btn-sm btn-success">&nbsp;Submit Surat Dinas</a>
				<!-- <a href="javascript:;" id="btn-show-closing-form" class="btn btn-sm btn-success">&nbsp;Submit Penyelesaian Tiket</a> -->
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kode Tiket </th>
							<th> Pelanggan </th>
							<th> Prioritas</th>
							<th> Penerima Tiket </th>
							<th> Visit</th>
							<th> Surat Jalan</th>
							<th> Pembiayaan</th>
							<th> Status</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="surat-dinas-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Persetujuan Surat Dinas</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'surat-dinas-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-tiket-form">
					<div class="form-group">
						<label for="kategori" class="form-control-label">Kode Tiket <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kode_tiket_sudin" name="kode_tiket_sudin" required>
							<option value="">- Pilih Kode Tiket -</option>
							<?php
								foreach ($list_kode as $row) {
									echo "<option value='".$row->catatan_visit."PengHubunG".$row->stat_sudin."PengHubunG".$row->link_sudin."PengHubunG".$row->id_tiket."'>".$row->kode_tiket."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="status_visit" class="form-control-label">Status Visit :</label>
						<label for="detail_status_visit" class="form-control" readonly="readonly"> <b>Memerlukan Visit</b> </label>
						<label for="catatan_teknisi" class="form-control-label">Catatan dari Teknisi :</label>
						<label id="isi_catatan_teknisi" for="isi_catatan_teknisi" class="form-control" readonly="readonly">Catatan dari Teknisi</label>						
					</div>
					<div class="form-group">
						<label for="surat_dinas" class="form-control-label">Status Surat Dinas :</label>
						<label id="tampil_sudin" for="surat_dinas" class="form-control" readonly="readonly">Status Surat Dinas</label>
					</div>
					<div class="form-group">
						<label for="file_surat_dinas" class="form-control-label">File Surat Dinas <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Goggle Drive File Surat Dinas" id="file_surat_dinas" name="file_surat_dinas" required>
					</div>
					<div class="form-group">
						<label for="catatan" class="form-control-label">Catatan :</label>
						<textarea class="form-control" name="catatan" id="catatan" cols="10" rows="2"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_tiket" name="id_tiket">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-success btn-save">Submit</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<div id="closingTiket-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Penyelesaian Tiket</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'closingTiket-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-tiket-form">
					<div class="form-group">
						<label for="kode_tiket_closing" class="form-control-label">Kode Tiket <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kode_tiket_closing" name="kode_tiket_closing" required>
							<option value="">- Pilih Kode Tiket -</option>
							<?php
							foreach ($list_kode_akhir as $row) {
								echo "<option value='".$row->stat_visit."PengHubunG".$row->file_laporan_teknisi."PengHubunG".$row->file_laporan_biaya."PengHubunG".$row->feedcust."PengHubunG".$row->desk_fededcust."PengHubunG".$row->id_tiket."'>".$row->kode_tiket."</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="status_visit" class="form-control-label">Status Visit :</label>
						<label id="detail_status_visit" or="detail_status_visit" class="form-control" readonly="readonly">Status Visit</label>					
					</div>
					<div class="form-group">
						<label for="laporan_teknisi" class="form-control-label">Status Laporan Akhir Teknisi :</label>
						<label id="file_laporan_teknisi" for="file_laporan_teknisi" class="form-control" readonly="readonly">Status Laporan Akhir Teknisi</label>
					</div>
					<div class="form-group">
						<label for="laporan_finance" class="form-control-label">Status Laporan Akhir Finance :</label>
						<label id="file_laporan_biaya" for="file_laporan_biaya" class="form-control" readonly="readonly">Status Laporan Akhir Finance</label>
					</div>
					<div class="form-group">
						<label for="lbl_feedback_cust" class="form-control-label">Feedback Customer :</label>
						<label id="feedback_cust" for="feedback_cust" class="form-control" readonly="readonly">File Feedback Customer</label>
						<label for="lbl_feedback_desk" class="form-control-label">Deskripsi Feedback Customer :</label>
						<label id="feedback_desk" for="feedback_desk" class="form-control" readonly="readonly">Deskripsi Feedback Customer dari Teknisi</label>
					</div>
					<div class="form-group">
						<label for="status_tiket" class="form-control-label">Status Tiket <span class="text-danger">*</span> :</label>
						<select class="form-control" id="status_tiket" name="status_tiket" required>
							<option value="0">- Pilih Status Tiket -</option>
							<option value="1">Close (Tepat Waktu)</option>
							<option value="2">Close (Terlambat)</option>
							<option value="3">Close (Bersyarat)</option>
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
				<button type="button" class="btn btn-success btn-save">Selesaikan</button>
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
				url: 'tiket/pagination/konfirmasi_ga',
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
		
		$('#btn-show-suratDinas-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'tiket'
			$('#surat-dinas-modal #surat-dinas-form').attr('action', 'tiket/update/ga_submitSudin')
			$('#surat-dinas-modal').modal()
		})
		
		$('#btn-show-closing-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'tiket'
			$('#closingTiket-modal #closingTiket-form').attr('action', 'tiket/update/ga_closeTiket')
			$('#closingTiket-modal').modal()
		})
		
		Array.prototype.forEach.call(document.getElementsByName('kode_tiket_sudin'),
		function (elem) {
			elem.addEventListener('change', function() {
				let text	= this.value;
				const isi 	= text.split("PengHubunG");
				let idTiket				= isi[3],
					bukaini 			= isi[2],
					status_surat_dinas 	= isi[1],
					tampil_catatan 		= isi[0],
					tampil_sudin 		= "";
					
				if (text == ""){
					tampil_catatan	= "Catatan Dari Teknisi";
					tampil_sudin	= "Status Surat Dinas";
				}else {
					if (status_surat_dinas=="3"){
						tampil_sudin = "<b>Ditolak</b>";
					}else if (status_surat_dinas=="1"){
						tampil_sudin = "<b>Dalam Proses</b>";
					}else if (status_surat_dinas == '2'){
						tampil_sudin = "<b>Disetujui - </b> <a href='"+bukaini+"'> Cek File Surat Dinas </a>";
					}
					
					if (tampil_catatan==""){
						tampil_catatan = "<b>Tidak Ada Catatan Dari Teknisi</b>";
					}else if (tampil_catatan==""){
						tampil_catatan = isi[0];
					}
				}			

				document.getElementById('isi_catatan_teknisi').innerHTML = tampil_catatan;
				document.getElementById('tampil_sudin').innerHTML = tampil_sudin;
				document.getElementById('id_tiket').value = idTiket;				
				
			});
		});
		
		Array.prototype.forEach.call(document.getElementsByName('kode_tiket_closing'),
		function (elem) {
			elem.addEventListener('change', function() {
				let text			= this.value;
				const isi 			= text.split("PengHubunG");
				let idTiket			= isi[5],
					bukaini 		= 'https://youtube.com',
					status_visit 	= isi[0],
					laporan_tkn 	= isi[1],
					laporan_biaya 	= isi[2],
					laporan_feedCst	= isi[3],
					desk_feedCst	= isi[4],
					tampil_visit	= "",
					tampil_lp_tkn	= "",
					tampil_lp_biaya	= "",
					tampil_feedCst	= "",
					tampil_FC_desk	= "";
					
				if (text == ""){
					tampil_visit	= "Status Visit";
					tampil_lp_tkn	= "Status Laporan Teknisi";
					tampil_lp_biaya	= "Status Laporan biaya";
					tampil_feedCst	= "File Feedback Customer";
					tampil_FC_desk	= "Deskripsi Feedback Customer";
				}else {
					if (status_visit=="1"){
						tampil_visit = "<b>Tidak Memerlukan Visit</b>";
						tampil_lp_biaya = "<b>Tidak Memerlukan Laporan Pembiayaan Karena Tidak Visit</b>";
					}else if (status_visit=="2"){
						tampil_visit = "<b>Memerlukan Visit</b>";
						if (laporan_biaya==""){
							tampil_lp_biaya = "<b>Belum Dilaporkan Oleh Staff Finance</b>";
						}else{
							tampil_lp_biaya = "<b>Sudah Dilaporkan - </b><a href='surat/show/detail_surat/laporan_PB/"+laporan_biaya+"/1' target='blank'> Cek Laporan Akhir Biaya </a>";
						}
					}
					
					if (laporan_tkn==""){
						tampil_lp_tkn   = "<b>Belum Dilaporkan Oleh Teknisi</b>";
						tampil_feedCst	= "<b>Tidak Ada</b>";
					    tampil_FC_desk	= "<b>Tidak Ada</b>";
					}else{
						tampil_lp_tkn   = "<b>Sudah Dilaporkan - </b><a href='"+laporan_tkn+"'> Cek Laporan Akhir Teknisi </a>";
					    if(laporan_feedCst==""){
					        tampil_feedCst	= "<b>Tidak Ada</b>";
					        tampil_FC_desk	= "<b>Tidak Ada</b>";
					    }else{
					        tampil_feedCst  = "<b>Ada - </b><a href='"+laporan_feedCst+"'> Cek Feedback Customer </a>";
						    tampil_FC_desk	= desk_feedCst;
					    }
					}
				}			

				document.getElementById('detail_status_visit').innerHTML = tampil_visit;
				document.getElementById('file_laporan_teknisi').innerHTML = tampil_lp_tkn;
				document.getElementById('file_laporan_biaya').innerHTML = tampil_lp_biaya;
				document.getElementById('feedback_cust').innerHTML = tampil_feedCst;
				document.getElementById('feedback_desk').innerHTML = tampil_FC_desk;
				document.getElementById('id_tiket_2').value = idTiket;
				
			});
		});
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>