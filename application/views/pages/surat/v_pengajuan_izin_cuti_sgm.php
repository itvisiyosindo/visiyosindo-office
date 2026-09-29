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
				<a href="surat_part_two/show/pengajuan/cuti_sgm" id="btn-a-gc" class="btn btn-sm btn-success">
				    <i class="fas fa-plus"></i>&nbsp;&nbsp;Ajukan Cuti Tahunan
				</a>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> No</th>
							<th> Nama</th>
							<th> Jabatan</th>
							<th> Keperluan</th>
							<th> Tanggal</th>
							<th> Total Hari</th>
							<th> Status</th>
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
				url: 'surat_part_two/pagination/permintaan_cuti_sgm',
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
		});

		$(document).on('click', '.btn-batal-cuti-sgm', function() {
			var id = $(this).data('id');
			var kode = $(this).data('kode');
			
			Swal.fire({
				title: 'Batalkan Pengajuan Cuti SGM?',
				text: 'Apakah Anda yakin ingin membatalkan pengajuan cuti ' + (kode ? '(' + kode + ')' : '') + '?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Ya, Batalkan!',
				cancelButtonText: 'Kembali'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						url: 'surat_part_two/batalCutiSgm',
						method: 'POST',
						dataType: 'JSON',
						data: {
							id: id,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp);
							if (resp.status == 'success') {
								updateDatatable();
							}
						}
					});
				}
			});
		});
	});

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>