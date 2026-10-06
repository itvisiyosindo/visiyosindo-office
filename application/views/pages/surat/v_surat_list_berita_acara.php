<header class="page-header">
	<h2><i class="icons icon-doc"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span>Surat Menyurat</span></li>
			<li><span><?= $page_title ?></span></li>
		</ol>
	</div>
</header>

<style>
	.table-card-container {
		background: #ffffff;
		border: 1px solid #cbd5e1;
		border-radius: 12px;
		box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
		overflow: hidden;
		margin-bottom: 25px;
	}

	.table-top-bar {
		padding: 16px 20px;
		background: #f8fafc;
		border-bottom: 1px solid #cbd5e1;
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
	}

	.table-top-title {
		font-size: 16px;
		font-weight: 700;
		color: #1e293b;
		margin: 0;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	/* Simple, Crisp Table with Clear Row Separation */
	#kt_table_1 {
		width: 100% !important;
		margin-bottom: 0 !important;
		border-collapse: collapse !important;
	}

	#kt_table_1 thead th {
		background-color: #f1f5f9 !important;
		color: #334155 !important;
		font-weight: 700 !important;
		font-size: 12.5px !important;
		text-transform: uppercase !important;
		letter-spacing: 0.5px !important;
		border: 1px solid #cbd5e1 !important;
		padding: 12px 14px !important;
		vertical-align: middle !important;
		text-align: center;
	}

	#kt_table_1 tbody td {
		padding: 11px 14px !important;
		vertical-align: middle !important;
		border: 1px solid #e2e8f0 !important;
		font-size: 13px !important;
		color: #1e293b;
	}

	/* Jelas Perbedaan Setiap Baris Item (Zebra Striping Kontras) */
	#kt_table_1 tbody tr:nth-of-type(odd) {
		background-color: #ffffff !important;
	}

	#kt_table_1 tbody tr:nth-of-type(even) {
		background-color: #f8fafc !important;
	}

	/* Highlight Row Saat Diarahkan Kursor */
	#kt_table_1 tbody tr:hover {
		background-color: #e0f2fe !important;
	}

	#kt_table_1 tbody tr:hover td {
		background-color: transparent !important;
		color: #0f172a !important;
	}

	.dataTables_wrapper {
		padding: 15px !important;
	}

	.dataTables_wrapper .dataTables_length select {
		border-radius: 6px !important;
		border: 1px solid #cbd5e1 !important;
		padding: 4px 8px !important;
	}

	.dataTables_wrapper .dataTables_filter input {
		border-radius: 6px !important;
		border: 1px solid #cbd5e1 !important;
		padding: 5px 10px !important;
		outline: none !important;
	}

	.dataTables_wrapper .dataTables_filter input:focus {
		border-color: #2563eb !important;
		box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2) !important;
	}

	.dataTables_wrapper .dataTables_paginate .paginate_button.current, 
	.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
		background: #2563eb !important;
		color: #ffffff !important;
		border: 1px solid #2563eb !important;
		border-radius: 4px !important;
	}

	.dataTables_wrapper .dataTables_paginate .paginate_button {
		border-radius: 4px !important;
		border: 1px solid transparent !important;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="table-card-container">
			<div class="table-top-bar">
				<div class="table-top-title">
					<i class="fas fa-file-contract text-primary"></i> Daftar Berita Acara
				</div>
				<div class="d-flex align-items-center" style="gap: 10px;">
					<div class="input-group input-group-sm" style="width: auto;">
						<div class="input-group-prepend">
							<span class="input-group-text bg-white border-right-0"><i class="far fa-calendar-alt text-muted"></i></span>
						</div>
						<select class="form-control form-control-sm border-left-0" id="tahun" style="border-radius: 0 6px 6px 0; font-weight: 600;">
							<option value="">Semua Tahun</option>
							<?php
							$current_year = date('Y');
							for ($y = $current_year; $y >= $current_year - 4; $y--) {
								echo '<option value="' . $y . '">' . $y . '</option>';
							}
							?>
						</select>
					</div>
					<a href="<?= base_url('surat_part_two/show/add/ba') ?>" class="btn btn-sm btn-primary font-weight-bold" style="border-radius: 6px;">
						<i class="fas fa-plus mr-1"></i> Buat Berita Acara
					</a>
				</div>
			</div>

			<div class="table-responsive">
				<table class="table table-bordered" id="kt_table_1">
					<thead>
						<tr>
							<th width="40"> # </th>
							<th> No </th>
							<th> Nama </th>
							<th> Jabatan </th>
							<th> Tanggal </th>
							<th> Diketahui Oleh </th>
							<th> Disetujui Oleh </th>
							<th> Status </th>
							<th width="70"> Aksi </th>
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
				url: 'surat_part_two/pagination/list_ba',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val();
					e.csrf_token = token;
				}
			},
			columnDefs: [{
				targets: [0, 4, 7, 8],
				className: 'text-center'
			}]
		});

		$('#tahun').change(function() {
			table.ajax.reload();
		});
	});

	function updateDatatable() {
		table.ajax.reload(null, false);
	}
</script>