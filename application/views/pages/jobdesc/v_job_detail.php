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
		<!-- Card Informasi Pegawai & Masa Berlaku -->
		<div class="card-body bg-light p-3 rounded mb-2 border">
			<div class="d-flex justify-content-between align-items-center">
				<div>
					<strong>Nama :</strong> <?= $data_job->pegawai ?><br>
					<strong>NPP :</strong> <?= $data_job->no_pegawai ?><br>
					<strong>Jabatan :</strong> <?= $data_job->jabatan ?><br>
					<strong>Masa Berlaku Jobdesk :</strong> 
					<span class="badge" style="background-color: #f1f5f9; color: #1e293b; border: 1px solid #cbd5e1; font-weight: 600; padding: 4px 10px; border-radius: 6px; font-size: 13px;">
						<i class="far fa-calendar-alt text-muted mr-1"></i>
						<?= (!empty($data_job->tgl_mulai) && $data_job->tgl_mulai != '0000-00-00') ? date('d/m/Y', strtotime($data_job->tgl_mulai)) : '01/01/2024' ?>
						<span style="color: #94a3b8; font-weight: 400; margin: 0 4px;">—</span>
						<?= (!empty($data_job->tgl_selesai) && $data_job->tgl_selesai != '0000-00-00' && $data_job->tgl_selesai != '2099-12-31') ? date('d/m/Y', strtotime($data_job->tgl_selesai)) : 'Seterusnya' ?>
					</span>
				</div>
				<?php if(sessPenggunaId() == 1) { ?>
					<button type="button" class="btn btn-warning btn-sm btn-edit-masa" 
						data-id="<?= encrypt($data_job->id_po) ?>" 
						data-mulai="<?= (!empty($data_job->tgl_mulai) && $data_job->tgl_mulai != '0000-00-00') ? $data_job->tgl_mulai : '2024-01-01' ?>" 
						data-selesai="<?= (!empty($data_job->tgl_selesai) && $data_job->tgl_selesai != '0000-00-00') ? $data_job->tgl_selesai : '2099-12-31' ?>">
						<i class="fas fa-edit"></i> Edit Rentang Waktu
					</button>
				<?php } ?>
			</div>
			<input type="hidden" id="id_po" value="<?= $data_job->id_pengguna ?>" />
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<div class="mb-3" style="margin-bottom: 15px;">
					<?php if(sessPenggunaId() == 1) {?>
						<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Point Jobdesk</a>
					<?php } ?>
					<a href="jobdesc/print_jobdesc/<?= encrypt($data_job->id_po) ?>" target="_blank" class="btn btn-sm btn-info text-white float-right"><i class="fas fa-print"></i>&nbsp;Cetak Jobdesk PDF</a>
				</div>

				<table id="kt_table_2" style="font-family:Times New Roman; color:black; font-size:15px" border="1" width="100%" class="table table-bordered">
					<thead>
						<tr>
							<th style="text-align:center" width="8%">No Urut</th>
							<th style="text-align:center" width="12%">Point</th>
							<th style="text-align:center">Deskripsi</th>
							<?php if(sessPenggunaId() == 1) {?>
							<th style="text-align:center" width="15%">Penilaian</th>
							<th style="text-align:center" width="10%">Aksi</th>
							<?php } ?>
						</tr>
					</thead>
										<tbody>
						<?php 
							$no = 1;
							foreach ($data_detail as $row) {
								$id = encrypt($row->id_pod);
								// Jika id_urut bernilai 0 atau kosong, gunakan urutan otomatis $no
								$no_urut_display = (!empty($row->id_urut) && $row->id_urut != 0) ? $row->id_urut : $no;
						?>
							<tr>
								<td style="text-align:center"><?= $no_urut_display ?></td>
								<td style="text-align:center"><?= $row->point ?></td>
								<td class="nama">
									<?php if ($row->nilai == 1): ?>
										<strong><?= $row->deskripsi ?></strong>
									<?php else: ?>
										<?= $row->deskripsi ?>
									<?php endif; ?>
								</td>
								<?php if(sessPenggunaId() == 1) {?>
								<td style="text-align:center">
									<?php if ($row->nilai == 1): ?>
										<span class="badge badge-success">Dinilai</span>
									<?php else: ?>
										<span class="badge badge-secondary">Tidak Dinilai</span>
									<?php endif; ?>
								</td>
								<td class="aksi" style="text-align:center">
									<button type="button" class="btn btn-sm btn-primary btn-edit" data-id="<?= $id ?>"><i class="bx bx-pencil"></i></button>
									<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="<?= $row->id_pod?>" data-object="jobdesc/delete/<?= $row->id_pod?>"><i class="bx bx-trash"></i></button>
								</td>
								<?php } ?>
							</tr>
						<?php 
							$no++;
						} 
						?>
					</tbody>
				</table>
				<br>
				<div>
					<button type="button" onclick="goBack()" class="btn btn-secondary float-right">Kembali</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Form Tambah/Edit Detail Jobdesk -->
<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Point Jobdesk</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<input type="hidden" id="id_jobdesc" name="id_jobdesc" value="<?= $data_job->id_po ?>" />
					<input type="hidden" id="id_pengguna" name="id_pengguna" value="<?= $data_job->id_pengguna ?>" />
					<input type="hidden" id="id_pelanggan" name="id_pelanggan">

					<div class="form-group">
						<label for="idurut" class="form-control-label">Nomor Urut <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="idurut" name="idurut" required>
					</div>
					<div class="form-group">
						<label for="point" class="form-control-label">Point <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="point" name="point" required>
					</div>	
					<div class="form-group">
						<label for="deskripsi" class="form-control-label">Deskripsi <span class="text-danger">*</span> :</label>
						<textarea name="deskripsi" class="form-control" id="deskripsi" cols="15" rows="3" required></textarea>
					</div>
					<div class="form-group">
						<label for="nilai" class="form-control-label">Penilaian <span class="text-danger">*</span> :</label>
						<select class="form-control" id="nilai" name="nilai" required>
							<option value="1">Dinilai</option>
							<option value="2">Tidak Dinilai</option>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- Modal Edit Masa Berlaku Rentang Waktu -->
<div id="modal-masa-berlaku" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-light">
				<h5><i class="fas fa-calendar-alt mr-2"></i> Edit Rentang Waktu Masa Berlaku Jobdesk</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
			</div>
			<form id="form-masa-berlaku" autocomplete="off">
				<div class="modal-body">
					<input type="hidden" id="edit_id_po" name="id_po">
					<div class="form-group">
						<label>Tanggal Mulai Berlaku <span class="text-danger">*</span> :</label>
						<input type="date" class="form-control" id="edit_tgl_mulai" name="tgl_mulai" required>
					</div>
					<div class="form-group">
						<label>Tanggal Selesai Berlaku :</label>
						<input type="date" class="form-control" id="edit_tgl_selesai" name="tgl_selesai">
						<small class="text-muted">* Kosongkan atau isikan 2099-12-31 jika masih aktif saat ini.</small>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary" id="btn-save-masa">Simpan Rentang Waktu</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {

		// Reset form saat klik Tambah Point Jobdesk
		$('#btn-show-add-form').click(function() {
			$('#modal-form')[0].reset();
			$('#main-modal #id_pelanggan').val('');
			$('#main-modal #idurut').val('<?= isset($next_urut) ? $next_urut : 1 ?>');
			$('#main-modal #modal-form').attr('action', 'jobdesc/addDetail');
			$('#main-modal').modal('show');
		});

		// Ambil data saat klik Edit
		$(document).on('click', '.btn-edit', function() {
			var id = $(this).attr("data-id");
			$('#main-modal #modal-form').attr('action', 'jobdesc/updateDetail');

			fetch('jobdesc/edit/' + id)
				.then(resp => resp.json())
				.then(data => {
					if(data && data.length > 0) {
						$('#main-modal #deskripsi').val(data[0].deskripsi);
						$('#main-modal #point').val(data[0].point);
						$('#main-modal #idurut').val(data[0].id_urut);
						$('#main-modal #nilai').val(data[0].nilai);
						$('#main-modal #id_pelanggan').val(id);
						$('#main-modal').modal('show');
					}
				});
		});

		// Proses Simpan Point Jobdesk (AJAX Simpan & Update)
		$(document).on('click', '.btn-save', function(e) {
			e.preventDefault();
			var form = $('#modal-form');
			var actionUrl = form.attr('action');
			var formData = form.serialize();

			$.ajax({
				url: actionUrl,
				type: 'POST',
				data: formData,
				dataType: 'json',
				success: function(response) {
					if (response.status == 'success' || response.status == true) {
						$('#main-modal').modal('hide');
						location.reload();
					} else {
						alert(response.message || 'Gagal menyimpan data!');
					}
				},
				error: function() {
					alert('Terjadi kesalahan pada koneksi server.');
				}
			});
		});

		// Buka Modal Edit Rentang Waktu
		$(document).on('click', '.btn-edit-masa', function() {
			var id = $(this).data('id');
			var mulai = $(this).data('mulai');
			var selesai = $(this).data('selesai');

			$('#edit_id_po').val(id);
			$('#edit_tgl_mulai').val(mulai);
			$('#edit_tgl_selesai').val((selesai && selesai !== '0000-00-00') ? selesai : '2099-12-31');
			$('#modal-masa-berlaku').modal('show');
		});

		// Simpan Edit Rentang Waktu via AJAX
		$('#btn-save-masa').click(function() {
			var formData = $('#form-masa-berlaku').serialize();
			$.ajax({
				url: 'jobdesc/updateTanggalMasaBerlaku',
				type: 'POST',
				data: formData,
				dataType: 'json',
				success: function(resp) {
					if (resp.status == 'success' || resp.status == true) {
						$('#modal-masa-berlaku').modal('hide');
						location.reload();
					} else {
						alert(resp.message || 'Gagal memperbarui masa berlaku.');
					}
				},
				error: function() {
					alert('Terjadi kesalahan pada koneksi server.');
				}
			});
		});

		// Hapus Point Jobdesk
		$(document).on('click', '.btn-delete', function() {
			var url = $(this).data('object');
			if(confirm('Apakah Anda yakin ingin menghapus point jobdesk ini?')) {
				$.ajax({
					url: url,
					type: 'POST',
					dataType: 'json',
					success: function(res) {
						location.reload();
					}
				});
			}
		});

	});

	function goBack() {
		window.history.back();
	}
</script>