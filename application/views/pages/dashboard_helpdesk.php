<header class="page-header">
	<h2><i class="icons icon-list"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
	</div>
</header>
<div class="row">
	<div class="col-md-6">
		<div class="text-center">
			<h2>Log Aktivitas Anda</h2>
		</div>
		<div class="card-body">
			<div class="row form-group col-md-4">
				<small>Filter By Month:</small>
				<div class="input-group">
					<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
					<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Pengguna</th>
							<th> Aksi </th>
							<th> Keterangan </th>
							<th> Tanggal </th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="text-center">
			<h2>&nbsp;</h2>
		</div>
		<?php if (isHrd() || isAdmin()) { ?>
			<div class="card card-modern">
				<a href="javascript:;" id="btn-show-hadir">
					<div class="card-body py-4">
						<div class="row align-items-center">
							<div class="col-6 col-md-4">
								<h3 class="text-4-1 my-0"></h3>
								<strong class="text-6 text-color-dark"><?= $present[0]->total ?></strong>
							</div>
							<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
								<h3 class="text-4-1 text-color-success line-height-2 my-0">Karyawan <strong>Hadir &uarr;</strong></h3>

							</div>
							<div class="col-md-4 text-left text-md-right pe-md-4 mt-4 mt-md-0">
								<i class="bx bx-user icon icon-inline icon-xl bg-primary rounded-circle text-color-light"></i>
							</div>
						</div>
					</div>
				</a>
			</div>


			<div class="card card-modern">
				<a href="javascript:;" id="btn-show-izin">
					<div class="card-body py-4">
						<div class="row align-items-center">
							<div class="col-6 col-md-4">
								<h3 class="text-4-1 my-0"></h3>
								<strong class="text-6 text-color-dark"><?= $izin[0]->total ?></strong>
							</div>
							<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
								<h3 class="text-4-1 text-color-danger line-height-2 my-0">Karyawan <strong>Izin &darr;</strong></h3>

							</div>
							<div class="col-md-4 text-left text-md-right pe-md-4 mt-4 mt-md-0">
								<i class="bx bx-user icon icon-inline icon-xl bg-primary rounded-circle text-color-light"></i>
							</div>
						</div>
					</div>
				</a>
			</div>


			<div class="card card-modern">
				<a href="javascript:;" id="btn-show-cuti">
					<div class="card-body py-4">
						<div class="row align-items-center">
							<div class="col-6 col-md-4">
								<h3 class="text-4-1 my-0"></h3>
								<strong class="text-6 text-color-dark"><?= $cuti[0]->total ?></strong>
							</div>
							<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
								<h3 class="text-4-1 text-color-danger line-height-2 my-0">Karyawan <strong>Cuti &darr;</strong></h3>

							</div>
							<div class="col-md-4 text-left text-md-right pe-md-4 mt-4 mt-md-0">
								<i class="bx bx-user icon icon-inline icon-xl bg-primary rounded-circle text-color-light"></i>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="card card-modern">
				<a href="javascript:;" id="btn-show-sakit">
					<div class="card-body py-4">
						<div class="row align-items-center">
							<div class="col-6 col-md-4">
								<h3 class="text-4-1 my-0"></h3>
								<strong class="text-6 text-color-dark"><?= $sakit[0]->total ?></strong>
							</div>
							<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
								<h3 class="text-4-1 text-color-danger line-height-2 my-0">Karyawan <strong>Sakit &darr;</strong></h3>

							</div>
							<div class="col-md-4 text-left text-md-right pe-md-4 mt-4 mt-md-0">
								<i class="bx bx-user icon icon-inline icon-xl bg-primary rounded-circle text-color-light"></i>
							</div>
						</div>
					</div>
				</a>
			</div>
		<?php } else { ?>
			<img style="width: 100%;height: 75vh" src="<?= base_url('/assets/img/gedung.jpg') ?>" alt="">

		<?php } ?>


		<!-- <br><br><br> -->
	</div>
</div>


<div id="main-modal-hadir" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Hadir</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_hadir as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>

<div id="main-modal-sakit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Sakit</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable1" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_sakit as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>

<div id="main-modal-cuti" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Cuti</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable2" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_cuti as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>

<div id="main-modal-izin" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Izin</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable3" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_izin as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		$('#filter_month').change(function() {
			table.ajax.reload()
		})
		$('#myTable').DataTable();
		$('#myTable1').DataTable();
		$('#myTable2').DataTable();
		$('#myTable3').DataTable();

		table = $('#kt_table_1').DataTable({
			responsive: false,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			scrollY: '50vh',
			scrollX: true,
			scrollCollapse: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'dashboard/pagination_log',
				type: 'POST',
				data: function(e) {
					e.filter_month = $('#filter_month').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0],
				className: 'text-center'
			}]
		})

		$('#btn-show-izin').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-izin').modal()
		})

		$('#btn-show-hadir').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-hadir').modal()
		})

		
		$('#btn-show-sakit').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-sakit').modal()
		})

		
		$('#btn-show-cuti').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-cuti').modal()
		})
	})
</script>