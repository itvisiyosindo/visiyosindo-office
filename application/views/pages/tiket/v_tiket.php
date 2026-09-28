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
			<?php if (isEksetkutif() || isKepalaDivisi() || isAdminDivisi() || sessPenggunaId() == 72 || sessPenggunaId() == 85 || sessPenggunaId() == 755 || sessPenggunaId() == 751) { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Tiket</a>
			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="row">
				<div class="col-md-2">
					<small>Filter By Customer:</small>
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" id="filter_pelanggan" placeholder="Ketik nama Pelanggan...">
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<small>Filter By Subject:</small>
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" id="filter_subject" placeholder="Ketik Subject tiket...">
						</div>
					</div>
				</div>


				<div class="col-md-2">
					<small>Export:</small>
					<div class="form-group">
						<a href="javascript:;" id="btn-cetaklaporan-form" class="btn btn-sm btn-success"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp;Cetak Rekapan</a>
					</div>
				</div>
			</div>
			<br>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th width="5%"> # </th>
							<th> Kode Tiket</th>
							<th> Tanggal Terbit</th>
							<th> Pelanggan</th>
							<th> Subject</th>
							<th> Tanggal Berangkat</th>
							<th> Teknisi</th>
							<th> Deskripsi</th>
							<th> Laporan Teknisi</th>

							<?php
							// LOGIKA KOLOM KONDISIONAL
							if (isAdmin() || sessPenggunaId() == 755) { ?>
								<th> Log Aktivitas </th>
								<th> Respond Time </th>

							<?php } ?>
							<th> Status</th>
							<th width="10%"> Aksi </th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Tiket</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-tiket-form">
					<div class="form-group">
						<label for="list_pelanggan" class="form-control-label">Pelanggan <span class="text-danger">*</span> :</label>
						<div class="custom-control custom-checkbox mb-2">
							<input type="checkbox" class="custom-control-input" id="chk_pelanggan_manual" name="pelanggan_manual_flag" value="1">
							<label class="custom-control-label" for="chk_pelanggan_manual">
								<small class="text-info"><i class="fas fa-user-edit"></i> Pelanggan tidak terdaftar (input manual)</small>
							</label>
						</div>
						<div id="wrap_select_pelanggan">
							<select data-plugin-selectTwo class="form-control populate" id="list_pelanggan" name="list_pelanggan" required>
								<option value="">- Pilih Pelanggan -</option>
								<?php
								foreach ($pelanggan as $row) {
									echo '<option value="' . $row->id_pelanggan . '" data-cpname="' . htmlspecialchars($row->cpname ?? '', ENT_QUOTES) . '" data-kontak="' . htmlspecialchars($row->kontak ?? '', ENT_QUOTES) . '">' . $row->identitas_pelanggan . '</option>';
								}
								?>
							</select>
						</div>
						<div id="wrap_input_pelanggan" style="display:none;">
							<input type="text" class="form-control" placeholder="Ketik nama pelanggan..." id="pelanggan_manual" name="pelanggan_manual">
						</div>
					</div>
					<div class="form-group">
						<label for="nama_contact_person" class="form-control-label">Nama Contact Person :</label>
						<input type="text" class="form-control" placeholder="Masukkan Nama CP Customer untuk Notifikasi" id="nama_contact_person" name="nama_contact_person" readonly>
					</div>
					<div class="form-group">
						<label for="contact_person" class="form-control-label">Contact Person :</label>
						<input type="text" class="form-control" placeholder="Masukkan Nomer WA CP Customer untuk Notifikasi" id="contact_person" name="contact_person" readonly>
					</div>
					<div class="form-group">
						<label for="subject" class="form-control-label">Subject <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Subject (Instalasi,Trouble, Ukes, Upar, etc)" id="subject" name="subject" required>
					</div>
					<div class="form-group">
						<label for="kategori" class="form-control-label">Kategori Tiket <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="kategori" name="kategori" required>
							<option value="">- Pilih Kategori -</option>
							<?php
							foreach ($kategori as $row) {
								echo '<option value="' . $row->id_topik . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>

					<div class="form-group">
						<label for="prioritas" class="form-control-label">Prioritas <span class="text-danger">*</span> :</label>
						<select class="form-control" id="prioritas" name="prioritas" required>
							<option value="">- Pilih Prioritas -</option>
							<option value="1">Low</option>
							<option value="2">Medium</option>
							<option value="3">High</option>
							<option value="4">Urgent</option>
						</select>
					</div>

					<?php /*
					<div class="form-group">
						<label for="agent" class="form-control-label">Agent/PIC <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" name="agent" id="agent">
							<option value="">- Pilih Agent/PIC -</option>
							<?php
							foreach ($pengguna as $row) {
								echo '<option value="' . $row->pengguna_id . '">' . $row->nama . " | " . $row->jabatan . '</option>';
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
					</div> */ ?>


					<div class="form-group">
						<label for="deskripsi" class="form-control-label">Deskripsi <span class="text-danger">*</span> :</label>
						<textarea class="form-control" name="deskripsi" placeholder="Masukkan Deskripsi (Instalasi,Trouble, Ukes, Upar, etc)" id="deskripsi" cols="10" rows="2"></textarea>
					</div>

					<div class="form-group">
						<label for="attachment" class="form-control-label">File Pendukung :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Goggle Drive untuk data pendukung" id="attachment" name="attachment" required>
					</div>

					<div class="form-group">
						<label for="invoice" class="form-control-label">File Invoice :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Goggle Drive untuk file invoice" id="invoice" name="invoice" required>
					</div>

					<div class="form-group">
						<label for="waktu" class="form-control-label">Waktu Pengerjaan <span class="text-danger">*</span> :</label>
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

					<div class="form-group">
						<label for="id_visilab" class="form-control-label">Apakah ada UKES atau UPAR ? <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control" id="id_visilab" name="id_visilab" required>
							<option value="1">Tidak</option>
							<option value="2">Ada</option>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Cetak Tiket </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-marketing-form">
					<?php
					$tanggal = date('d');
					$bulanArray = [
						1 => 'Januari',
						2 => 'Februari',
						3 => 'Maret',
						4 => 'April',
						5 => 'Mei',
						6 => 'Juni',
						7 => 'Juli',
						8 => 'Agustus',
						9 => 'September',
						10 => 'Oktober',
						11 => 'November',
						12 => 'Desember'
					];
					$bulan = $bulanArray[date('n')];
					$tahun = date('Y');
					?>
					<div class="form-group" style="display: flex;">
						Cetak Data Tiket Tanggal : <?php echo $tanggal . ' ' . $bulan . ' ' . $tahun; ?>
					</div>
					<!--<div class="form-group" style="display: flex;">
				        <div style="flex: 50%;padding: 10px;">
						<input class="form-control"  data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
						</div>
						<div style="flex: 50%;padding: 10px;">
						<input class="form-control"  data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
						</div>
					</div>-->
					<div class="form-group">
						<label class="control-label">Nama Alat / Topik</label>
						<select class="select-transaction input-group-sm form-control" name="namamarketing" id="namamarketing">
							<?php if ($kategori != NULL): ?>
								<option value=''>Semua Alat</option>
								<?php foreach ($kategori as $value): ?>
									<option value="<?php echo $value->id_topik; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('nama_marketing'); ?>
					</div>

					<div class="form-group">
						<label class="control-label">Kategori</label>
						<select class="select-transaction input-group-sm form-control" name="idkat" id="idkat">
							<option value=''>Semua Kategori</option>
							<option value='Instalasi'>Instalasi</option>
							<option value='Trouble'>Trouble</option>
							<option value='Uji Kesesuaian'>Uji Kesesuaian (UKES)</option>
							<option value='Uji Paparan'>Uji Paparan (UPAR)</option>
						</select>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-export" class="btn btn-success btn-clear-form">Export Excel</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<div id="modal-log-aktivitas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLogLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-info text-white">
				<h5 class="modal-title" id="modalLogLabel"><i class="fas fa-history"></i> Log Aktivitas Tiket</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="konten-log-aktivitas">
					<div class="text-center p-4">
						<i class="fas fa-spinner fa-spin fa-2x"></i>
						<p>Memuat data log...</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		$('#filter_subject, #filter_pelanggan').keyup(function() {
			updateDatatable()
		})
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'tiket/pagination',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.filter_pelanggan = $('#filter_pelanggan').val()
					e.filter_subject = $('#filter_subject').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 4, 5, 6, 7, 8, 9, 10],
				className: 'text-center'
			}]
		})

		// Event Add
		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			$('#chk_pelanggan_manual').prop('checked', false).trigger('change');
			$('#main-modal #modal-form').attr('action', 'tiket/add')
			$('#main-modal').modal()
		})

		// Toggle pelanggan manual di form tambah
		$('#chk_pelanggan_manual').change(function() {
			if ($(this).is(':checked')) {
				$('#wrap_select_pelanggan').hide();
				$('#list_pelanggan').prop('required', false).val('').trigger('change');
				$('#wrap_input_pelanggan').show();
				$('#pelanggan_manual').prop('required', true);
				// Remove readonly when manual customer is checked
				$('#nama_contact_person').prop('readonly', false);
				$('#contact_person').prop('readonly', false);
			} else {
				$('#wrap_select_pelanggan').show();
				$('#list_pelanggan').prop('required', true).trigger('change');
				$('#wrap_input_pelanggan').hide();
				$('#pelanggan_manual').prop('required', false).val('');
				// Make readonly back when registered customer is checked
				$('#nama_contact_person').prop('readonly', true);
				$('#contact_person').prop('readonly', true);
			}
		});

		// Auto dynamic contact person details on customer select
		$('#list_pelanggan').change(function() {
			var selectedOption = $(this).find('option:selected');
			var cpname = selectedOption.data('cpname') || '';
			var kontak = selectedOption.data('kontak') || '';
			$('#nama_contact_person').val(cpname);
			$('#contact_person').val(kontak);
		});

		// Event Log Activity
		$(document).on('click', '.btn-log-activity', function() {
			var id = $(this).data('id');
			$('#konten-log-aktivitas').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Memuat data log...</p></div>');
			$('#modal-log-aktivitas').modal('show');

			$.ajax({
				url: 'tiket/get_log_history', // Sesuaikan URL
				type: 'POST',
				data: {
					id: id
				},
				success: function(response) {
					$('#konten-log-aktivitas').html(response);
				},
				error: function(xhr, status, error) {
					$('#konten-log-aktivitas').html('<div class="alert alert-danger">Gagal memuat data log.</div>');
				}
			});
		});

		$(document).on('click', '.btn-edit', function() {
			$('.btn-isactive').remove()
			var object = 'kategori_Tiket'
			$('#main-modal #modal-form').attr('action', 'kategori_Tiket/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #topik_id').val(data[0].id_topik)
					$('#main-modal #nama').val(data[0].nama)
					$('#main-modal #deskripsi').val(data[0].deskripsi)


					let text_isaktif = data[0].is_active == 1 ? "Nonaktifkan" : "Aktifkan"
					let color = data[0].is_active == 1 ? "danger" : "success"
					let value = data[0].is_active == 1 ? 0 : 1
					let x = `
					<button type="button" class="btn btn-${color} btn-isactive" id="${data[0].id_topik}" value_isactive="${value}">${text_isaktif}</button>
					`;
					$('.is_aktif').append(x)
				})
		})

		// 2. EVENT HANDLER UNTUK TOMBOL CLOSE TIKET
		$(document).on('click', '.btn-close-ticket', function() {
			var id = $(this).data('id');

			Swal.fire({
				title: 'Konfirmasi Close Tiket',
				text: "Apakah Anda yakin ingin menutup tiket ini? Status akan berubah menjadi Selesai.",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Close Tiket!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						// Pastikan URL ini mengarah ke fungsi update status_tiket di controller
						url: '<?php echo base_url("tiket/update/status_tiket"); ?>',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id_tiket: id, // ID terenkripsi dikirim disini
							value: 4, // 4 = Status Selesai
							csrf_token: token
						},
						success: function(response) {
							if (response.status == 'success') {
								Swal.fire('Berhasil!', 'Tiket berhasil ditutup.', 'success');
								table.ajax.reload(null, false); // Reload tabel otomatis
							} else {
								Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');
							}
						},
						error: function() {
							Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
						}
					});
				}
			});
		});


		$('#btn-cetaklaporan-form').click(function() {
			$('#main-modal-marketing').modal()


		})

		$("#btn-export").click(function() {

			idmarketing = $("#namamarketing").val();
			namamarketing = $("#namamarketing option:selected").text();
			idkat = $("#idkat").val();
			window.open("<?php echo base_url(); ?>tiket/exportlaporan/search?idmarketing=" + encodeURIComponent(idmarketing) + "&namamarketing=" + encodeURIComponent(namamarketing) + "&idkat=" + encodeURIComponent(idkat), "_blank");
			$('#main-modal-marketing').modal('hide')

		});


	})



	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>