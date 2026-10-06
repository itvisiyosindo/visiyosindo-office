<header class="page-header">
	<h2><i class="icons icon-doc"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span>Surat Menyurat</span></li>
			<li><span><?= $page_title ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col-12">
		<div class="table-card-container">
			<div class="table-top-bar">
				<div class="table-top-title">
					<i class="fas fa-clock text-primary"></i> <?= $page_title ?>
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
							<th> Keperluan </th>
							<th> Pukul </th>
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
				url: 'surat_part_two/pagination/list_izin_jam_kerja',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val();
					e.csrf_token = token;
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7],
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