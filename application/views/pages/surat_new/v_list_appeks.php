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
			<!-- <a href="fpp/show/pengajuan/penawaran" id="btn-a-gc" class="btn btn-sm btn-success"><i class="fas fa-plus"></i>&nbsp;&nbsp;Ajukan</a>
				<?php if (isAdmin() || isHrd() || isCRO() || isGa()) { ?>
				<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success"><i class="fas fa-print"></i>&nbsp;&nbsp;Cetak Laporan</a> -->
		<?php } ?>
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
							<th> Tujuan</th>
							<th> Nama Barang</th>
							<th> Berat Barang</th>
							<th> Tanggal</th>
							<th> Diajukan Oleh</th>
							<th> Status</th>
							<th> Aksi</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Laporan Data Permintaan Penawaran </h5>
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
						<label class="control-label">Nama Marketing</label>
						<select class="select-transaction input-group-sm form-control" name="namamarketing" id="namamarketing">
							<?php if ($nama_marketing != NULL): ?>
								<option value=''>— Pilih Nama Marketing —</option>
								<?php foreach ($nama_marketing as $value): ?>
									<option value="<?php echo $value->pengguna_id; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('nama_marketing'); ?>
					</div>
					<div class="form-group" style="display: flex;">
						<div style="flex: 30%;padding: 1px;">
							<input type="checkbox" id="csname" name="csname" checked>&nbsp;&nbsp;Nama Customer
						</div>
						<div style="flex: 30%;padding: 1px;">
							<input type="checkbox" id="kode_fpp" name="kode_fpp" checked>&nbsp;&nbsp;No FPP
						</div>
						<div style="flex: 30%;padding: 1px;">
							<input type="checkbox" id="alamat" name="alamat" checked>&nbsp;&nbsp;Alamat
						</div>
					</div>
					<div class="form-group" style="display: flex;">
						<div style="flex: 30%;padding: 1px;">
							<input type="checkbox" id="tgl" name="tgl" checked>&nbsp;&nbsp;Tanggal
						</div>
						<div style="flex: 30%;padding: 1px;">
							<input type="checkbox" id="cpname" name="cpname" checked>&nbsp;&nbsp;Contact Person
						</div>
						<div style="flex: 30%;padding: 1px;">
							<input type="checkbox" id="nocp" name="nocp" checked>&nbsp;&nbsp;No CP
						</div>
					</div>
					<div class="form-group" style="display: flex;">
						<div style="flex: 30%;padding: 1px;">
							<input type="checkbox" id="payment" name="payment" checked>&nbsp;&nbsp;Term of Payment
						</div>
						<div style="flex: 30%;padding: 1px;">
							<input type="checkbox" id="notes" name="notes" checked>&nbsp;&nbsp;Notes
						</div>
						<div style="flex: 30%;padding: 1px;">
							<input type="checkbox" id="no_sph" name="no_sph" checked>&nbsp;&nbsp;No SPH
						</div>
					</div>
					<div class="form-group" style="display: flex;">
						<div style="flex: 30%;padding: 1px;">
							<input type="checkbox" id="link_sph" name="link_sph" checked>&nbsp;&nbsp;Link SPH
						</div>
						<div style="flex: 30%;padding: 1px;">
							<input type="checkbox" id="link_approval" name="link_approval" checked>&nbsp;&nbsp;Link Approval
						</div>
						<div style="flex: 30%;padding: 1px;">
						</div>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-cetak" class="btn btn-primary btn-clear-form">Cetak</button>
				<button type="button" id="btn-export" class="btn btn-success btn-clear-form">Export Excel</button>
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
				url: 'surat_new/pagination/list_appeks',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
				className: 'text-center'
			}]
		})

		$('#btn-laporan-form').click(function() {
			$('#main-modal-marketing').modal()


		})

		$("#btn-cetak").click(function() {
			if ($('#main-modal-marketing #namamarketing').val() == '') {
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Nama Marketing tidak boleh kosong..!!',
					showConfirmButton: false,
					timer: 2000
				});
			} else {
				tglawal = $("#tglawal").val();
				tglakhir = $("#tglakhir").val();
				idmarketing = $("#namamarketing").val();
				namamarketing = $("#namamarketing option:selected").text();
				csname = $("#csname:checkbox").is(":checked") ? 1 : 0;
				kode_fpp = $("#kode_fpp:checkbox").is(":checked") ? 1 : 0;
				alamat = $("#alamat:checkbox").is(":checked") ? 1 : 0;
				tgl = $("#tgl:checkbox").is(":checked") ? 1 : 0;
				cpname = $("#cpname:checkbox").is(":checked") ? 1 : 0;
				nocp = $("#nocp:checkbox").is(":checked") ? 1 : 0;
				payment = $("#payment:checkbox").is(":checked") ? 1 : 0;
				notes = $("#notes:checkbox").is(":checked") ? 1 : 0;
				no_sph = $("#no_sph:checkbox").is(":checked") ? 1 : 0;
				link_sph = $("#link_sph:checkbox").is(":checked") ? 1 : 0;
				link_approval = $("#link_approval:checkbox").is(":checked") ? 1 : 0;
				namamarketing = $("#namamarketing option:selected").text();
				window.open("<?php echo base_url(); ?>fpp/printlaporan/search?tglawal=" + encodeURIComponent(tglawal) + "&tglakhir=" + encodeURIComponent(tglakhir) + "&idmarketing=" + encodeURIComponent(idmarketing) + "&namamarketing=" + encodeURIComponent(namamarketing) + "&csname=" + csname + "&kode_fpp=" + kode_fpp + "&alamat=" + alamat + "&tgl=" + tgl + "&cpname=" + cpname + "&nocp=" + nocp + "&payment=" + payment + "&notes=" + notes + "&no_sph=" + no_sph + "&link_sph=" + link_sph + "&link_approval=" + link_approval, "_blank");
				$('#main-modal-marketing').modal('hide')
			}
		});
		--
		$("#btn-export").click(function() {
			if ($('#main-modal-marketing #namamarketing').val() == '') {
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Nama Marketing tidak boleh kosong..!!',
					showConfirmButton: false,
					timer: 2000
				});
			} else {
				tglawal = $("#tglawal").val();
				tglakhir = $("#tglakhir").val();
				idmarketing = $("#namamarketing").val();
				namamarketing = $("#namamarketing option:selected").text();
				csname = $("#csname:checkbox").is(":checked") ? 1 : 0;
				kode_fpp = $("#kode_fpp:checkbox").is(":checked") ? 1 : 0;
				alamat = $("#alamat:checkbox").is(":checked") ? 1 : 0;
				tgl = $("#tgl:checkbox").is(":checked") ? 1 : 0;
				cpname = $("#cpname:checkbox").is(":checked") ? 1 : 0;
				nocp = $("#nocp:checkbox").is(":checked") ? 1 : 0;
				payment = $("#payment:checkbox").is(":checked") ? 1 : 0;
				notes = $("#notes:checkbox").is(":checked") ? 1 : 0;
				no_sph = $("#no_sph:checkbox").is(":checked") ? 1 : 0;
				link_sph = $("#link_sph:checkbox").is(":checked") ? 1 : 0;
				link_approval = $("#link_approval:checkbox").is(":checked") ? 1 : 0;
				namamarketing = $("#namamarketing option:selected").text();
				window.open("<?php echo base_url(); ?>fpp/exportLaporan/search?tglawal=" + encodeURIComponent(tglawal) + "&tglakhir=" + encodeURIComponent(tglakhir) + "&idmarketing=" + encodeURIComponent(idmarketing) + "&namamarketing=" + encodeURIComponent(namamarketing) + "&csname=" + csname + "&kode_fpp=" + kode_fpp + "&alamat=" + alamat + "&tgl=" + tgl + "&cpname=" + cpname + "&nocp=" + nocp + "&payment=" + payment + "&notes=" + notes + "&no_sph=" + no_sph + "&link_sph=" + link_sph + "&link_approval=" + link_approval, "_blank");
				$('#main-modal-marketing').modal('hide')
			}
		});



	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>