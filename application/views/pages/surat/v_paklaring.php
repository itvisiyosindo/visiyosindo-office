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
					<span class="badge badge-primary mr-2" style="font-size: 13px; padding: 6px 12px;"><i class="fas fa-list mr-1"></i> Data Surat Pengalaman Kerja</span>
				</div>
				<div class="table-actions">
					<?php if ( sessPenggunaId() == '1' || sessPenggunaId() == '58'){ ?>
						<a href="surat_part_two/show/pengajuan/paklaring" id="btn-a-gc" class="btn btn-sm btn-success shadow-sm">
							<i class="fas fa-plus mr-1"></i> Ajukan Pengalaman Kerja
						</a>
					<?php } ?>
				</div>
			</div>

			<div class="card-body p-3">
				<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
						<thead>
							<tr>
								<th> # </th>
								<th> Kode </th>
								<th> Nama </th>
								<th> Jabatan</th>
								<th> Tanggal Masuk</th>
								<th> Tanggal Keluar</th>
								<th> Status</th>
							</tr>
						</thead>
					</table>
				</div>
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
				url: 'surat_part_two/pagination/paklaring',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6],
				className: 'text-center'
			}]
		})
		
		

		
		
		

	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>