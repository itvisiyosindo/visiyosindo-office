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
	.card-modern-table {
		background: #ffffff;
		border-radius: 16px;
		border: 1px solid #e2e8f0;
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
		overflow: hidden;
		margin-bottom: 25px;
	}

	.table-header-toolbar {
		padding: 18px 24px;
		background: #ffffff;
		border-bottom: 1px solid #f1f5f9;
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: space-between;
		gap: 15px;
	}

	.table-header-title {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.table-header-title .icon-box {
		width: 42px;
		height: 42px;
		border-radius: 10px;
		background: #eff6ff;
		color: #2563eb;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 18px;
	}

	.table-header-title h4 {
		margin: 0;
		font-size: 17px;
		font-weight: 700;
		color: #0f172a;
	}

	.table-header-title p {
		margin: 0;
		font-size: 13px;
		color: #64748b;
	}

	#kt_table_1 {
		margin-bottom: 0 !important;
		border-collapse: separate !important;
		border-spacing: 0;
		width: 100% !important;
	}

	#kt_table_1 thead th {
		background-color: #f8fafc !important;
		color: #475569 !important;
		font-weight: 700 !important;
		font-size: 12px !important;
		text-transform: uppercase !important;
		letter-spacing: 0.5px !important;
		border-top: none !important;
		border-bottom: 2px solid #e2e8f0 !important;
		padding: 14px 16px !important;
		vertical-align: middle !important;
		white-space: nowrap !important;
	}

	#kt_table_1 tbody td {
		padding: 14px 16px !important;
		vertical-align: middle !important;
		border-top: none !important;
		border-bottom: 1px solid #f1f5f9 !important;
		font-size: 13.5px;
		color: #334155;
	}

	#kt_table_1 tbody tr:hover {
		background-color: #f8fafc !important;
	}

	#kt_table_1 tbody tr:last-child td {
		border-bottom: none !important;
	}

	.dataTables_wrapper .dataTables_length select {
		border-radius: 8px !important;
		border: 1px solid #cbd5e1 !important;
		padding: 4px 8px !important;
	}

	.dataTables_wrapper .dataTables_filter input {
		border-radius: 8px !important;
		border: 1px solid #cbd5e1 !important;
		padding: 6px 12px !important;
		outline: none !important;
	}

	.dataTables_wrapper .dataTables_filter input:focus {
		border-color: #3b82f6 !important;
		box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
	}

	.dataTables_wrapper .dataTables_paginate .paginate_button.current, 
	.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
		background: #2563eb !important;
		color: #ffffff !important;
		border: 1px solid #2563eb !important;
		border-radius: 6px !important;
	}

	.dataTables_wrapper .dataTables_paginate .paginate_button {
		border-radius: 6px !important;
		border: 1px solid transparent !important;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card-modern-table">
			<div class="table-header-toolbar">
				<div class="table-header-title">
					<div class="icon-box">
						<i class="fas fa-file-contract"></i>
					</div>
					<div>
						<h4>Daftar Berita Acara</h4>
						<p>Kelola dan pantau seluruh pengajuan Berita Acara (BA)</p>
					</div>
				</div>
				<div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
					<div class="input-group input-group-sm" style="width: auto;">
						<div class="input-group-prepend">
							<span class="input-group-text bg-light border-right-0"><i class="far fa-calendar-alt text-muted"></i></span>
						</div>
						<select class="form-control form-control-sm border-left-0" id="tahun" style="border-radius: 0 8px 8px 0; font-weight: 600;">
							<option value="">Semua Tahun</option>
							<?php
							$current_year = date('Y');
							for ($y = $current_year; $y >= $current_year - 4; $y--) {
								echo '<option value="' . $y . '">' . $y . '</option>';
							}
							?>
						</select>
					</div>
					<a href="<?= base_url('surat_part_two/show/add/ba') ?>" class="btn btn-sm btn-primary shadow-sm font-weight-bold" style="border-radius: 8px; padding: 7px 16px;">
						<i class="fas fa-plus mr-1"></i> Buat Berita Acara
					</a>
				</div>
			</div>

			<div class="p-3">
				<div class="table-responsive">
					<table class="table table-hover" id="kt_table_1">
						<thead>
							<tr>
								<th width="40"> # </th>
								<th> No. Berita Acara </th>
								<th> Pengaju </th>
								<th> Jabatan </th>
								<th> Tanggal </th>
								<th> Diketahui Oleh </th>
								<th> Disetujui Oleh </th>
								<th> Status </th>
								<th width="80"> Aksi </th>
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