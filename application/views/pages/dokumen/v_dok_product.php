<header class="page-header">
	<h2><i class="far fa-newspaper "></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<style>
	.doc-ui-shell {
		background: linear-gradient(180deg, #eaf2fb 0%, #f8fbff 100%);
		border-radius: 16px;
		padding: 1.1rem;
	}

	.doc-ui-head {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		gap: 0.75rem;
		background: linear-gradient(135deg, #0d4d7f 0%, #1e7f89 100%);
		border-radius: 14px;
		color: #fff;
		padding: 1rem 1.1rem;
		margin-bottom: 0.9rem;
	}

	.doc-ui-head h3 {
		margin: 0;
		font-size: 1.15rem;
		font-weight: 700;
	}

	.doc-ui-head p {
		margin: 0.35rem 0 0;
		opacity: 1;
		color: #f8fbff;
		font-size: 0.9rem;
		font-weight: 600;
		line-height: 1.4;
		text-shadow: 0 1px 2px rgba(0, 0, 0, 0.28);
	}

	.doc-ui-meta .badge {
		font-size: 0.8rem;
		color: #0d4d7f;
		background: #f1f7ff;
		border: 1px solid #d2e4f7;
		padding: 0.42rem 0.65rem;
	}

	.doc-ui-toolbar {
		display: flex;
		flex-wrap: wrap;
		gap: 0.5rem;
		margin-bottom: 0.85rem;
	}

	.doc-ui-card {
		background: #fff;
		border: 1px solid #d8e5f2;
		border-radius: 12px;
		padding: 0.9rem;
		box-shadow: 0 6px 18px rgba(13, 77, 127, 0.06);
	}

	.doc-ui-table {
		margin-bottom: 0;
	}

	.doc-ui-table thead th {
		background: #edf4fb;
		color: #33485c;
		border-color: #d7e4f2;
		font-size: 0.8rem;
		text-transform: uppercase;
		letter-spacing: 0.02em;
		vertical-align: middle;
	}

	.doc-ui-table td {
		vertical-align: middle;
	}

	.doc-ui-modal .modal-content {
		border: none;
		border-radius: 12px;
		overflow: hidden;
	}

	.doc-ui-modal .modal-header {
		background: linear-gradient(135deg, #0f3d61 0%, #145d6d 100%);
		color: #fff;
	}

	.doc-ui-modal .form-control {
		border-radius: 10px;
		border: 1px solid #d6e4f1;
	}

	.doc-ui-modal .form-control:focus {
		border-color: #1f7a8c;
		box-shadow: 0 0 0 0.12rem rgba(31, 122, 140, 0.18);
	}

	@media (max-width: 768px) {
		.doc-ui-shell {
			padding: 0.9rem;
		}

		.doc-ui-head {
			flex-direction: column;
		}
	}
</style>

<?php
	$is_hs_code = !empty($is_hs_code);
	$selected_kategori_id = isset($selected_kategori_id) ? (int)$selected_kategori_id : 0;
?>
<div class="doc-ui-shell">
	<div class="doc-ui-head">
		<div>
			<h3><?= $is_hs_code ? 'Data HS Code' : 'Dokumen Product' ?></h3>
			<p><?= $is_hs_code ? 'Kelola data Kode HS Code, Keterangan, Dokumen Lartas, Tarif Impor, dan Link HS Code.' : 'Kelola dokumen product dengan tampilan rapi, presisi, dan konsisten untuk semua kategori termasuk ISO.' ?></p>
		</div>
		<div class="doc-ui-meta">
			<span class="badge badge-light">Kategori Aktif: <?= count($list_kategori) ?></span>
		</div>
	</div>

	<div class="doc-ui-toolbar">
		<?php if(isAdmin()  || sessPenggunaId()=='23' || sessPenggunaId()=='75' || sessPenggunaId()=='84' || sessPenggunaId()=='750' || sessPenggunaId()=='766'){ ?>
			<a href="javascript:;" id="btn-show-add-formkategori" class="btn btn-sm btn-info"><i class="icons icon-plus"></i>&nbsp;Tambah Kategori Product</a>
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;<?= $is_hs_code ? 'Tambah HS Code' : 'Tambah Dokumen Product' ?></a>
			<?php if ($is_hs_code): ?>
				<a href="javascript:;" id="btn-notif-hs-all" class="btn btn-sm btn-outline-success"><i class="fab fa-whatsapp"></i>&nbsp;Kirim Notif WA</a>
			<?php endif; ?>
		<?php } ?>
		<?php if ($is_hs_code): ?>
			<a href="<?= base_url('dokumen/export_excel_hs/' . encrypt($selected_kategori_id)) ?>" class="btn btn-sm btn-outline-success" target="_blank" title="Export Excel (.xlsx)"><i class="fas fa-file-excel"></i>&nbsp;Export Excel</a>
			<a href="<?= base_url('dokumen/export_pdf_hs/' . encrypt($selected_kategori_id)) ?>" class="btn btn-sm btn-outline-danger" target="_blank" title="Cetak / Simpan PDF"><i class="fas fa-file-pdf"></i>&nbsp;Cetak / PDF</a>
		<?php endif; ?>
	</div>

	<div class="doc-ui-card">
		<div class="table-responsive">
			<table class="table table-striped table-sm table-bordered table-hover doc-ui-table" id="kt_table_1">
				<thead>
					<tr>
						<th>#</th>
						<?php if ($is_hs_code): ?>
							<th>Kode HS Code</th>
							<th>Keterangan</th>
							<th>Dokumen Lartas</th>
							<th>Tarif Impor</th>
							<th>Link HS Code</th>
						<?php else: ?>
							<th>Nama Dokumen</th>
							<th>Masa Berlaku</th>
							<th>Kategori</th>
							<th>Link Download</th>
						<?php endif; ?>
						<?php if (isAdmin() || sessPenggunaId()=='23' || sessPenggunaId()=='75' || sessPenggunaId()=='84' || sessPenggunaId()=='750' || sessPenggunaId()=='92' || sessPenggunaId()=='766') { ?>
							<th class="text-center">Aksi</th>
						<?php } ?>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
<div id="main-modal-kategori" class="modal fade doc-ui-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Kategori Dokumen Product</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-kategori', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="keterangan" class="form-control-label">Nama Kategori Product <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="keterangan" name="keterangan" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_dokumenproduct" name="id_dokumenproduct">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>
<div id="main-modal" class="modal fade doc-ui-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> <?= $is_hs_code ? 'Form HS Code' : 'Form dokumen' ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<?php if ($is_hs_code): ?>
						<input type="hidden" id="kategori" name="kategori" value="<?= $selected_kategori_id ?>">
						<div class="form-group">
							<label for="kode_hs_code" class="form-control-label">Kode HS Code <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="kode_hs_code" name="kode_hs_code" placeholder="Contoh: 9018.12.00" required>
						</div>
						<div class="form-group">
							<label for="keterangan_hs" class="form-control-label">Keterangan :</label>
							<textarea class="form-control" id="keterangan_hs" name="keterangan_hs" rows="2" placeholder="Keterangan produk / HS Code"></textarea>
						</div>
						<div class="form-group">
							<label for="dokumen_lartas" class="form-control-label">Dokumen Lartas :</label>
							<textarea class="form-control" id="dokumen_lartas" name="dokumen_lartas" rows="2" placeholder="Contoh: AKL / Tidak Ada Lartas"></textarea>
						</div>
						<div class="form-group">
							<label for="tarif_impor" class="form-control-label">Tarif Impor :</label>
							<input type="text" class="form-control" id="tarif_impor" name="tarif_impor" placeholder="Contoh: BM 0%, PPN 11%, PPh 2.5%">
						</div>
						<div class="form-group">
							<label for="file" class="form-control-label">Link HS Code <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="file" name="file" placeholder="https://..." required>
						</div>
					<?php else: ?>
						<div class="form-group">
							<label for="nama" class="form-control-label">Nama dokumen <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="nama" name="nama" required>
						</div>
						<div class="form-group">
							<label for="kategori" class="form-control-label"> Kategori <span class="text-danger">*</span> :</label>
							<select class="form-control" id="kategori" name="kategori" required>
								<option value="">- Pilih Kategori -</option>
								<?php
									foreach ($list_kategori as $row) {
										echo "<option value='".$row->id."'>".$row->keterangan."</option>";
									}
								?>
							</select>
						</div>

						<div class="form-group">
							<label for="username" class="form-control-label">Link Download <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="file" name="file" required>
						</div>
						<div class="form-group">
							<label for="tanggal" class="form-control-label">Masa Berlaku :</label>
							<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
								<span class="input-group-text">
									<i class="fas fa-calendar-alt"></i>
								</span>
								<input type="text" class="form-control" id="tgl_mulai" name="tgl_mulai" required>
								<span class="input-group-text border-start-0 border-end-0 rounded-0">
									to
								</span>
								<input type="text" class="form-control" id="tgl_akhir" name="tgl_akhir" required>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_dokumen" name="id_dokumen">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<div id="send-modal" class="modal fade doc-ui-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Kirim Via Wa</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('dokumen/sendWa', array('id' => 'send-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="nama" class="form-control-label">No Wa Tujuan <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="wa_tujuan" name="wa_tujuan" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input class="form-control" type="hidden" id="id_dokumen" name="id_dokumen">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-success">Kirim</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<script>

	document.addEventListener('DOMContentLoaded', function() {
	   var url = window.location.pathname.split("/");
       var par = url[4];
	    var product = '12k72k'; 
	    if(par.length === 0){
	        product = '12k72k';
	    }else{
	        product = par;
	    }

		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'dokumen/paginationproduct/'+product,
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: <?= $is_hs_code ? '[0, 4, 5]' : '[0, 2, 3, 4]' ?>,
				className: 'text-center'
			}]
		})

		$('#btn-show-add-formkategori').click(function() {
			$('.form-control').val(null)
			$('#main-modal-kategori #modal-form-kategori').attr('action', 'dokumen/add/kategoriproduct')
			$('#main-modal-kategori').modal()
		})
		$('#btn-show-add-form').click(function() {
			$('#main-modal .form-control').val(null)
			<?php if ($is_hs_code): ?>
				$('#main-modal #kategori').val('<?= $selected_kategori_id ?>')
			<?php endif; ?>
			$('#main-modal #modal-form').attr('action', 'dokumen/add/product')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'dokumen'
			$('#main-modal #modal-form').attr('action', 'dokumen/update/product')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/product/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_dokumen').val(data[0].id)
					<?php if ($is_hs_code): ?>
						$('#main-modal #kategori').val(data[0].id_kategori)
						$('#main-modal #kode_hs_code').val(data[0].kode_hs_code || data[0].nama_dokumen)
						$('#main-modal #keterangan_hs').val(data[0].keterangan || '')
						$('#main-modal #dokumen_lartas').val(data[0].dokumen_lartas || '')
						$('#main-modal #tarif_impor').val(data[0].tarif_impor || '')
						$('#main-modal #file').val(data[0].file || '')
					<?php else: ?>
						$('#main-modal #nama').val(data[0].nama_dokumen)
						$('#main-modal #kategori option[value="' +data[0].id_kategori+ '"]').prop("selected", true).trigger('change')
						$('#main-modal #file').val(data[0].file)
						$('#main-modal #tgl_mulai').val(data[0].tgl_mulai)
						$('#main-modal #tgl_akhir').val(data[0].tgl_akhir)
					<?php endif; ?>
				})
		})

		$(document).on('click', '.btn-send-wa', function() {
			$('.form-control').val(null)
			$('#send-modal #id_dokumen').val($(this).attr("data-id"))
			$('#send-modal').modal()
		})

		<?php if ($is_hs_code): ?>
		function kirimNotifHs(id, label) {
			Swal.fire({
				title: 'Kirim Notifikasi WA?',
				text: 'Kirim notif "' + label + '" ke grup IZIN K3 ISO CDAKB VYM?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				confirmButtonText: 'Kirim',
				cancelButtonText: 'Batal'
			}).then(function(res) {
				if (!res.isConfirmed && !res.value) return;
				var url = '<?= base_url("dokumen/send_notif_hscode") ?>' + (id ? '/' + id : '');
				fetch(url, { credentials: 'same-origin' })
					.then(function(r) { return r.json(); })
					.then(function(d) {
						if (d && d.status === 'success') {
							Swal.fire('Terkirim', d.msg || 'Notifikasi WA berhasil dikirim.', 'success');
						} else {
							Swal.fire('Gagal', (d && d.msg) ? d.msg : 'Gagal mengirim notifikasi WA.', 'error');
						}
					})
					.catch(function() {
						Swal.fire('Gagal', 'Terjadi kesalahan saat menghubungi server.', 'error');
					});
			});
		}

		$('#btn-notif-hs-all').on('click', function() {
			kirimNotifHs('', 'Update Data HS Code');
		});

		$(document).on('click', '.btn-notif-hs', function() {
			kirimNotifHs($(this).data('id'), 'HS Code ' + ($(this).data('kode') || ''));
		});
		<?php endif; ?>
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>