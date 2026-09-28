<header class="page-header">
	<h2><i class="icons fas fa-paper-plane"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('success_message')): ?>
	<div class="alert alert-success alert-dismissible fade show">
		<i class="fas fa-check-circle mr-2"></i>
		<?= $this->session->flashdata('success_message') ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('error_message')): ?>
	<div class="alert alert-danger alert-dismissible fade show">
		<i class="fas fa-exclamation-circle mr-2"></i>
		<?= $this->session->flashdata('error_message') ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
<?php endif; ?>

<div class="row">
	<div class="col">
		<div class="d-flex flex-wrap gap-2 mb-3">
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success mr-2"><i class="icons icon-plus"></i>&nbsp;Tambah</a>
			<?php if (sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 33 || sessPenggunaId() == 7 || sessPenggunaId() == 73 || sessPenggunaId() == 749) { ?>
				<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success mr-2"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp;Print Rekapan</a>
			<?php } ?>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Kode</th>
							<th class="text-center">Nama Customer</th>
							<th class="text-center">PIC</th>
							<th class="text-center">Alamat Penerima</th>
							<th class="text-center">Marketing</th>
							<th class="text-center">Asal</th>
							<th class="text-center">Status</th>
							<th class="text-center">Konfirmasi TIKI</th> <!-- Checbox TIKI -->
							<th class="text-center">Aksi</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Kirim Dokumen</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<!--<div class="form-group">
						<label for="nama_customer" class="form-control-label">Nama Customer :</label>
						<select data-plugin-selectTwo class="form-control populate" id="nama_customer" name="nama_customer" required>
							<option value="">- Pilih Customer -</option>
							<?php
							foreach ($list_cust as $row) {
								echo '<option value="' . $row->identitas_pelanggan . '">' . $row->identitas_pelanggan . '</option>';
							}
							?>
						</select>
					</div>	
					<div class="form-group">
							<label for="alamat" class="form-control-label">Alamat :</label>
							<textarea type="text" class="form-control" id="alamat" name="alamat" required></textarea>
					</div>
					<div class="form-group">
							<label for="pic" class="form-control-label">PIC :</label>
							<input type="text" class="form-control" id="pic" name="pic" required>
					</div>-->


					<div class="form-group">
						<label for="nama_customer" class="form-control-label">Nama Customer :</label>
						<select data-plugin-selectTwo class="form-control populate" id="nama_customer" name="nama_customer" required>
							<option value="">- Pilih Customer -</option>
							<?php foreach ($list_cust as $row): ?>
								<option value="<?= $row->identitas_pelanggan ?>"
									data-alamat="<?= htmlspecialchars($row->alamat) ?>"
									data-pic="<?= htmlspecialchars($row->kontak) ?>">
									<?= $row->identitas_pelanggan ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group">
						<label for="alamat" class="form-control-label">Alamat :</label>
						<textarea class="form-control" id="alamat" name="alamat" required></textarea>
					</div>

					<div class="form-group">
						<label for="pic" class="form-control-label">PIC :</label>
						<input type="text" class="form-control" id="pic" name="pic" required>
					</div>




					<div class="form-group">
						<label for="asal" class="form-control-label">Pengiriman dari Kantor :</label>
						<select data-plugin-selectTwo class="form-control populate" id="asal" name="asal" required>
							<option value="">- Pilih asal pengirimannya -</option>
							<option value="1">Kantor Pekanbaru</option>
							<option value="2">Gudang Pekanbaru</option>
							<option value="3">Gudang Jakarta</option>
							<option value="4">Kantor Yogyakarta</option>
							<option value="5">Kantor Axa jakarta</option>
						</select>
					</div>
					<div class="form-group">
						<label for="marketing" class="form-control-label">Nama Marketing :</label>
						<select data-plugin-selectTwo class="form-control populate" id="marketing" name="marketing" required>
							<option value="">- Pilih Marketing -</option>
							<option value="Office / Kantor Pusat">Office / Kantor Pusat</option>
							<?php
							foreach ($list_marketing as $row) {
								echo '<option value="' . $row->nama . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="keterangan" class="form-control-label">Keterangan :</label>
						<textarea type="text" class="form-control" id="keterangan" name="keterangan" required></textarea>
					</div>
					<div class="form-group">
						<label for="link_doc" class="form-control-label">Link Dokumen (jika ada) :</label>
						<textarea type="text" class="form-control" id="link_doc" name="link_doc" required></textarea>
					</div>


				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_kirim" name="id_kirim">
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Rekapan Kirim Dokumen </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-marketing-form">
					<label for="bukti_penerima" class="form-control-label">Pilih Tanggal Kirim Dokumen</label>
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
			responsive: true,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'kirim/pagination',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 6, 7, 8],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null);
			$('#main-modal #modal-form').attr('action', 'kirim/add');

			$('#main-modal').modal();
		});

		$(document).on('click', '.btn-edit', function() {
			$('.btn-isactive').remove()
			var object = 'kirim'
			$('#main-modal #modal-form').attr('action', 'kirim/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #nama_customer').val(data[0].nama_customer).trigger('change')
					$('#main-modal #alamat').val(data[0].alamat)
					$('#main-modal #pic').val(data[0].pic)
					$('#main-modal #asal').val(data[0].asal).trigger('change')
					$('#main-modal #marketing').val(data[0].marketing).trigger('change')
					$('#main-modal #keterangan').val(data[0].keterangan)
					$('#main-modal #link_doc').val(data[0].link_doc)
					$('#main-modal #id_kirim').val(id)
				})
		})

		$('#nama_customer').on('change', function() {
			var alamat = $(this).find(':selected').data('alamat') || '';
			var pic = $(this).find(':selected').data('pic') || '';

			$('#alamat').val(alamat);
			$('#pic').val(pic);
		});

		$('#btn-laporan-form').click(function() {
			$('#main-modal-marketing').modal()
		})

		$("#btn-export").click(function() {
			tglawal = $("#tglawal").val();
			tglakhir = $("#tglakhir").val();
			window.open("<?php echo base_url(); ?>kirim/exportlaporan/search?tglawal=" + encodeURIComponent(tglawal) + "&tglakhir=" + encodeURIComponent(tglakhir), "_blank");
			$('#main-modal-marketing').modal('hide')

		});

		// Event saat checkbox TIKI diklik
		$(document).on('change', '.check-email-tiki', function() {
			var id = $(this).data('id');
			var isChecked = $(this).is(':checked');
			var $checkbox = $(this);

			if (isChecked) {
				// Gunakan SweetAlert
				Swal.fire({
					title: 'Konfirmasi Email TIKI',
					text: "Yakin data ini SUDAH di-email ke TIKI? Notifikasi WA akan dikirim ke Gudang & P. Budi.",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Ya, Proses!',
					cancelButtonText: 'Batal'
				}).then((result) => {
					if (result.isConfirmed) {
						$.ajax({
							url: 'kirim/update_status_tiki', // Sesuai route controller
							type: 'POST',
							dataType: 'JSON',
							timeout: 0, // Unlimited wait
							data: {
								id: id,
								'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
							},
							beforeSend: function() {
								Swal.fire({
									title: 'Sedang Mengirim...',
									html: 'Mohon tunggu, sistem sedang mengirim notifikasi WhatsApp.<br><b>Jangan tutup halaman ini.</b>',
									allowOutsideClick: false,
									allowEscapeKey: false,
									didOpen: () => {
										Swal.showLoading();
									}
								});
							},
							success: function(response) {
								if (response.status == 'success') {
									updateDatatable(); // Refresh tabel
									Swal.fire('Berhasil!', response.msg, 'success');
								} else {
									Swal.fire('Gagal!', response.msg, 'error');
									$checkbox.prop('checked', false);
								}
							},
							error: function(xhr, status, error) {
								Swal.fire('Error!', 'Terjadi kesalahan server.', 'error');
								$checkbox.prop('checked', false);
							}
						});
					} else {
						$checkbox.prop('checked', false); // Jika batal
					}
				});
			}
		});

		// Handler Tombol Konfirmasi Pickup
		$(document).on('click', '.btn-pickup', function() {
			var id = $(this).data('id');
			var kode = $(this).data('kode');
			var eks = $(this).data('eks'); // Ambil nama ekspedisi

			Swal.fire({
				title: 'Konfirmasi Pickup Kurir',
				text: "Apakah kurir " + eks + " SUDAH DATANG dan MEMBAWA dokumen " + kode + "? Status akan otomatis berubah menjadi 'Manifest Berangkat'.",
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#343a40', // Warna dark
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Sudah Dibawa!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: 'kirim/proses_pickup',
						type: 'POST',
						dataType: 'JSON',
						timeout: 0,
						data: {
							id: id,
							'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
						},
						beforeSend: function() {
							Swal.fire({
								title: 'Memproses...',
								html: 'Mengupdate status dan mengirim notifikasi...',
								allowOutsideClick: false,
								didOpen: () => {
									Swal.showLoading();
								}
							});
						},
						success: function(response) {
							if (response.status == 'success') {
								updateDatatable();
								Swal.fire('Berhasil!', response.msg, 'success');
							} else {
								Swal.fire('Gagal!', response.msg, 'error');
							}
						},
						error: function() {
							Swal.fire('Error!', 'Terjadi kesalahan koneksi.', 'error');
						}
					});
				}
			});
		});
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>