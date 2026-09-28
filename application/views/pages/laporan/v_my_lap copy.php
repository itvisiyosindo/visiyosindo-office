<header class="page-header">
	<h2><i class="fas fa-clipboard-list"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="">
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a><br>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th width="3%"> # </th>
							<th> Tanggal</th>
							<th width="25%"> Jobdesk</th>
							<th width="25%"> Progress Pekerjaan</th>
							<th> Status</th>
							<th> Link Hasil</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Laporan Mingguan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					
					
					<div class="form-group">
						<label for="waktu" class="form-control-label">Tanggal <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tanggal" name="tanggal" required>
						</div>
					</div>
					
					<div class="form-group">
						<label for="jobdesc" class="form-control-label">Pilih Jobdesk <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="jobdesc" name="jobdesc" required>
							<option value="">- Pilih Jobdesk -</option>
							<?php
							foreach ($list_job as $row) {
								echo '<option value="' . $row->deskripsi . '">' . $row->deskripsi . '</option>';
							}
							?>
						</select>
					</div>
					
					<div class="form-group">
						<label for="jenis" class="form-control-label">Jenis <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="jenis" name="jenis" required>
              <option value = "JOBDESK RUTIN">JOBDESK RUTIN</option>
              <option value = "PROJECT TAMBAHAN">PROJECT TAMBAHAN</option>
						</select>
					</div>

					<div class="form-group">
						<label for="progress" class="form-control-label">Progress <span class="text-danger">*</span> :</label>
						<textarea class="form-control" placeholder="Masukkan Progress Pekerjaan Anda" name="progress" id="progress" cols="10" rows="5"></textarea>
					</div>

					<div class="form-group">
						<label for="kendala" class="form-control-label">Kendala :</label>
						<input type="text" class="form-control" placeholder="Masukkan Kendala Jika ada" id="kendala" name="kendala" required>
					</div>
					<div class="form-group">
						<label for="solusi" class="form-control-label">Solusi :</label>
						<input type="text" class="form-control" placeholder="Masukkan Solusinya" id="solusi" name="solusi" required>
					</div>
					<div class="form-group">
						<label for="status_pekerjaan" class="form-control-label">Status Pekerjaan <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="status_pekerjaan" name="status_pekerjaan" required>
              <option value = "SELESAI">SELESAI</option>
              <option value = "DALAM PROSES">DALAM PROSES</option>
              <option value = "MENUNGGU KONFIRMASI">MENUNGGU KONFIRMASI</option>
              <option value = "PENDING">PENDING</option>
              <option value = "TIDAK SELESAI">TIDAK SELESAI</option>
						</select>
					</div>
					<div class="form-group">
						<label for="link" class="form-control-label">Link Bukti Pekerjaan <span class="text-danger">*</span>:</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Hasil / Bukti Pekerjaan Anda" id="link" name="link" required>
					</div>


					<div class="form-group">
							<label for="pihak" class="form-control-label">Nama Pihak Terkait :</label>
							<select class="form-control" id="pihak" name="pihak" required>
									<option value="" selected>- TIDAK ADA -</option>
									<?php if (!empty($list_nama)): ?>
											<?php foreach ($list_nama as $row): ?>
													<option value="<?= htmlspecialchars(trim($row->nama)) ?>">
															<?= htmlspecialchars($row->nama) ?>
													</option>
											<?php endforeach; ?>
									<?php endif; ?>
							</select>
					</div>


					<div class="form-group" id="keterangan-group" style="display: none;">
							<label for="keterangan" class="form-control-label">Keterangan yang Dikerjakan oleh Pihak Terkait :</label>
							<textarea class="form-control" placeholder="Masukkan Keterangan yang Dikerjakan oleh Pihak Terkait" 
												name="keterangan" id="keterangan" cols="10" rows="2"></textarea>
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

	document.getElementById("pihak").addEventListener("change", function() {
				var selectedValue = this.value.trim(); 
				var keteranganGroup = document.getElementById("keterangan-group");


				if (selectedValue === "") {
						keteranganGroup.style.display = "none"; 
				} else {
						keteranganGroup.style.display = "block"; 
				}
		});


	document.addEventListener('DOMContentLoaded', function() {
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'laporan/pagination/my_data',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 4, 5, 6],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'laporan'
			$('#main-modal #modal-form').attr('action', 'laporan/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			$('.btn-isactive').remove()
			var object = 'laporan'
			$('#main-modal #modal-form').attr('action', 'laporan/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #tanggal').val(data[0].tanggal)
					$('#main-modal #jobdesc').val(data[0].jobdesc)
					$('#main-modal #jenis').val(data[0].jenis)
					$('#main-modal #progress').val(data[0].progress)
					$('#main-modal #kendala').val(data[0].kendala)
					$('#main-modal #solusi').val(data[0].solusi)

					$('#main-modal #status_pekerjaan').val(data[0].status_pekerjaan)
					$('#main-modal #link').val(data[0].link)
					$('#main-modal #pihak').val(data[0].pihak)
					$('#main-modal #keterangan').val(data[0].keterangan)
					$('#main-modal #id_pelanggan').val(id)
				})
		})


		
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}



		
</script>