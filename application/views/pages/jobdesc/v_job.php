<header class="page-header">
	<h2><i class="fas fa-tasks"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div>
			<?php if(sessPenggunaId() == 1 ) { ?>
				<a href="jobdesc/show/pengajuan/jobdesc" id="btn-a-gc" class="btn btn-sm btn-success">
				    <i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah Jobdesk
				</a>
			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th width="5%"> # </th>
							<th> Nama</th>
							<th> NPP</th>
							<th> Jabatan</th>
							<th width="20%" class="text-center"> Masa Berlaku Jobdesk </th>
							<th width="15%" class="text-center"> Aksi </th>
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
				url: 'jobdesc/pagination/all',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 2, 4, 5],
				className: 'text-center'
			}]
		});

		// Event Listener Hapus Jobdesk
		$(document).on('click', '.btn-delete-jobdesc', function() {
			var id = $(this).data('id');
			if (confirm('Apakah Anda yakin ingin menghapus seluruh data Jobdesk ini beserta poin-poinnya?')) {
				$.ajax({
					url: 'jobdesc/deleteJobdesc/' + id,
					type: 'POST',
					dataType: 'json',
					success: function(res) {
						if (res.status == 'success' || res.status == true) {
							alert(res.message || 'Jobdesk berhasil dihapus.');
							updateDatatable();
						} else {
							alert(res.message || 'Gagal menghapus Jobdesk.');
						}
					},
					error: function() {
						alert('Terjadi kesalahan pada server.');
					}
				});
			}
		});
	});

	function updateDatatable() {
		table.ajax.reload(null, false);
	}
</script>