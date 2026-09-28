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
				<a href="purchase_order/show/pengajuan/purchase_order" id="btn-a-gc" class="btn btn-sm btn-success">
				    <i class="fas fa-plus"></i>&nbsp;&nbsp;Ajukan Purchase Order
				</a>
		</div>
		<br>
		<div class="card-body">
			<div class="row">
				<div class="col-md-2">
					<small>Filter By Supplier :</small>
					<select class="form-control " name="filter_supplier" id="filter_supplier">
						<option value="">Semua</option>
						<?php foreach ($list_supplier as $row) { ?>
							<option value="<?= $row->nama_pemasok ?>"><?= $row->nama_pemasok ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-2">
					<small>Filter By Status :</small>
					<select class="form-control " name="filter_status" id="filter_status">
						<option value="">Semua</option>
						<option value="0">Baru Diajukan</option>
						<option value="10">Disetujui oleh General Manager</option>
						<option value="1">Disetujui oleh Penanggung Jawab Teknis</option>
						<option value="2">Disetujui oleh Senior Accounting & Finance</option>
						<option value="3">Disetujui oleh Director of Corporate Planning & Business Management</option>
						<option value="4">Disetujui oleh Director</option>
						<option value="6">Ditolak</option>
						<option value="7">PO Supplier Sudah Terbit</option>
						<option value="8">Barang Diterima Seluruh</option>
						<option value="9">Barang Diterima Sebagian</option>
					</select>
				</div>
			</div><br>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kode</th>
							<th> Nama</th>
							<th> Jabatan</th>
							<th> Tanggal</th>
							<th> Supplier</th>
							<th> No PO</th>
							<th> Status</th>
							<!--<th> Aksi</th>-->
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		$('#filter_supplier, #filter_status').change(function() {
			table.ajax.reload()
		})

		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'purchase_order/pagination/permintaan_po',
				type: 'POST',
				data: function(e) {
					//e.tahun = $('#tahun').val()
					e.filter_supplier = $('#filter_supplier').val()
					e.filter_status = $('#filter_status').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7],
				className: 'text-center'
			}]
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>