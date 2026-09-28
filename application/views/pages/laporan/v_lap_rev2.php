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
		</div>
		<br>
		<div class="card-body">
			<button type="button" class="btn btn-success float-left btn-approval" style="margin-right: 10px;">
				<i class="fas fa-check"></i> Kirim Notif
			</button>

			<a href="javascript:;" id="btn-cetaklaporan-form" class="btn btn-primary"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp;Export Pencapaian</a>
			</br></br></br>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama</th>
							<th> NPP</th>
							<th> Jabatan</th>
							<th> Nama Penilai</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Penilai</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>

			<div class="modal-body">
				<input type="hidden" id="id_pengguna_hidden" name="id_pengguna_hidden">
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">

				<div class="dt-kategori-form">

					<div class="form-group">
						<label for="id_pengguna" class="form-control-label">Nama Pegawai <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_pengguna" name="id_pengguna" disabled>
							<?php
							foreach ($list_nama as $row) {
								echo '<option value="' . $row->pengguna_id . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>

					<div class="form-group">
						<label for="penilai_b" class="form-control-label">Nama Penilai <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="penilai_b" name="penilai_b" required>
							<option value="">- Pilih Pegawai-</option>
							<?php
							foreach ($list_nama as $row) {
								echo '<option value="' . $row->pengguna_id . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Export Pencapaian Karyawan </h5>
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
						<label class="control-label">Nama Pegawai</label>
						<select class="select-transaction input-group-sm form-control" name="namamarketing" id="namamarketing">
							<?php if ($list_nama != NULL): ?>
								<option value=''>Semua Pegawai</option>
								<?php foreach ($list_nama as $value): ?>
									<option value="<?php echo $value->pengguna_id; ?>"><?php echo $value->nama; ?></option>
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
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// 1. Inisialisasi DataTable
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'laporan/pagination/allRev2',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 2, 5],
				className: 'text-center'
			}]
		})

		// 2. Tombol Edit (Membuka Modal & Setup Form)
		$(document).on('click', '.btn-edit', function() {
			// --- A. Persiapan Modal ---
			$('.btn-isactive').remove();
			var object = 'laporan';

			// Set Action Form
			$('#main-modal #modal-form').attr('action', 'laporan/updatePenilai');

			// Tampilkan Modal
			$('#main-modal').modal();

			// --- B. Ambil Data dari Atribut Tombol ---
			var id_lp = $(this).attr("data-id");
			var id_user = $(this).attr("data-user");

			// --- C. Set Data ke Form ---
			// Visual Dropdown (disabled)
			$('#main-modal #id_pengguna').val(id_user).trigger('change');

			// Input Hidden (PENTING UNTUK CONTROLLER)
			$('#main-modal #id_pengguna_hidden').val(id_user);

			// --- D. Logika Fetch Data ---
			if (id_lp && id_lp !== "") {
				console.log("Mode Edit: Mengambil data untuk ID Laporan " + id_lp);

				fetch(object + '/editPenilai/' + id_lp)
					.then(function(resp) {
						if (!resp.ok) {
							throw new Error("HTTP Status " + resp.status);
						}
						return resp.json();
					})
					.then(function(data) {
						if (data.length > 0) {
							$('#main-modal #penilai_b').val(data[0].penilai_b).trigger('change');
							$('#main-modal #id_pelanggan').val(id_lp);
						} else {
							$('#main-modal #penilai_b').val('').trigger('change');
							$('#main-modal #id_pelanggan').val('');
						}
					})
					.catch(function(error) {
						console.error("Error Fetching: ", error);
						$('#main-modal #penilai_b').val('').trigger('change');
						$('#main-modal #id_pelanggan').val('');
					});

			} else {
				console.log("Mode Baru: Reset form.");
				$('#main-modal #penilai_b').val('').trigger('change');
				$('#main-modal #id_pelanggan').val('');
			}
		});

		// 3. TOMBOL SIMPAN
		$(document).on('click', '.btn-save', function(e) {
			e.preventDefault();

			var form = $('#modal-form');
			var url = form.attr('action');
			var data = form.serialize();

			// Debugging: Cek apakah ID Pengguna terkirim
			console.log("Mengirim Data: ", data);

			if (typeof token !== 'undefined') {
				data += '&csrf_token=' + token;
			}

			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				dataType: 'JSON',
				success: function(response) {
					if (response.status == 'success') {
						$('#main-modal').modal('hide');
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: response.message,
							timer: 1500,
							showConfirmButton: false
						});
						table.ajax.reload(null, false);
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							text: response.message
						});
					}
				},
				error: function(xhr, status, error) {
					console.error("AJAX Error:", xhr.responseText);
					Swal.fire({
						icon: 'error',
						title: 'Error Sistem',
						text: 'Terjadi kesalahan saat menyimpan data.'
					});
				}
			});
		});

		// 4. Tombol Approval & Lainnya (Tetap sama seperti kode asli Anda)
		$(document).on('click', '.btn-approval', function() {
			var id = $('#id').val();
			Swal.fire({
				title: 'Kirim Notifikasi Laporan Mingguan?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'laporan/sendNotifRev2',
						dataType: 'JSON',
						data: {
							id: id,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
						}
					})
				}
			})
		})

		$('#btn-cetaklaporan-form').click(function() {
			$('#main-modal-marketing').modal()
		})

		$("#btn-export").click(function() {
			tglawal = $("#tglawal").val();
			tglakhir = $("#tglakhir").val();
			idmarketing = $("#namamarketing").val();
			namamarketing = $("#namamarketing option:selected").text();
			window.open("<?php echo base_url(); ?>laporan/exportlaporan/search?tglawal=" + encodeURIComponent(tglawal) + "&tglakhir=" + encodeURIComponent(tglakhir) + "&idmarketing=" + encodeURIComponent(idmarketing) + "&namamarketing=" + encodeURIComponent(namamarketing), "_blank");
			$('#main-modal-marketing').modal('hide')
		});
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>