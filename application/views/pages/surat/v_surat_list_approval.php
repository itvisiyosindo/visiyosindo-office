<header class="page-header">
	<h2><i class="icons icon-user-follow"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col-12">
		<div class="table-card-container">
			<div class="table-top-bar">
				<div class="d-flex align-items-center">
					<span class="badge badge-primary mr-2" style="font-size: 13px; padding: 6px 12px;"><i class="fas fa-list mr-1"></i> Data Approval</span>
				</div>
				<div class="table-actions">
					<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success shadow-sm">
						<i class="fas fa-print mr-1"></i> Print Rekapan
					</a>
				</div>
			</div>

			<div class="px-3 pt-3">
				<div class="row">
					<div class="col-md-4 col-sm-12">
						<label class="small font-weight-bold text-dark mb-1"><i class="fas fa-filter text-primary mr-1"></i> Filter By Status :</label>
						<select class="form-control form-control-sm" name="filter_status" id="filter_status">
							<option value="">Semua Status</option>
							<option value="1">Baru diajukan</option>
							<option value="2">Pengajuan disetujui</option>
						</select>
					</div>
				</div>
			</div>

			<div class="card-body p-3">
				<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
						<thead>
							<tr>
								<th> # </th>
								<th> Kode Surat</th>
								<th> Kategori</th>
								<th> Nama Marketing</th>
								<th> Nama Customer</th>
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
</div>

<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Rekapan Approval </h5>
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
	// $('#filter_status').change(function() {
	//             updateDatatable()
	//         })
	document.addEventListener('DOMContentLoaded', function() {
		$('#filter_status').change(function() {
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
				url: 'surat/pagination/list_approval',
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


		$('#btn-laporan-form').click(function() {
			$('#main-modal-marketing').modal()


		})

		$("#btn-export").click(function() {

			tglawal = $("#tglawal").val();
			tglakhir = $("#tglakhir").val();
			window.open("<?php echo base_url(); ?>surat/exportlaporan/search?tglawal=" + encodeURIComponent(tglawal) + "&tglakhir=" + encodeURIComponent(tglakhir), "_blank");
			$('#main-modal-marketing').modal('hide')

		});



	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>