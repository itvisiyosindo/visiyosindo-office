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
			<?php if(sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 33 || sessPenggunaId() == 7 || sessPenggunaId()==73) { ?>
				<a href="inventory_new/show/pengajuan/po_pending" id="btn-a-gc" class="btn btn-sm btn-success">
				    <i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah
				</a>
			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama Customer</th>
							<th> Nama Marketing</th>
							<th style="text-align:center"> Item</th>
							<th> Tanggal PO</th>
							<th> Sistem Pembayaran</th>
							<th> Status</th>
							<th> Remarks</th>
							<th> Aksi</th>
						</tr>
					</thead>
				</table>
			</div>
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
				url: 'inventory_new/pagination/po_pending',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 4, 5, 6, 7, 8],
				className: 'text-center'
			}]
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>