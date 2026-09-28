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
		<div class="mb-3">
			<a href="<?= base_url('training_teknisi/tambah') ?>" class="btn btn-sm btn-success"><i class="fas fa-plus"></i>&nbsp;Tambah Data Training</a>
		</div>
		
		<section class="card">
			<div class="card-body">
				<div class="row mb-4">
					<div class="col-md-3">
						<small class="text-muted">Filter Rekanan:</small>
						<input type="text" class="form-control form-control-sm" id="filter_rekanan" placeholder="Ketik nama rekanan...">
					</div>
					<div class="col-md-3">
						<small class="text-muted">Filter Subject:</small>
						<input type="text" class="form-control form-control-sm" id="filter_subject" placeholder="Ketik subject training...">
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover table-sm" id="table-training" style="width: 100%;">
						<thead>
							<tr>
								<th width="3%">#</th>
								<th>Kode Training</th>
								<th>Rekanan</th>
								<th>CP</th>
								<th>Subject</th>
								<th>Kategori</th>
								<th>Prioritas</th>
								<th>Teknisi</th>
								<th>Waktu Pelaksanaan</th>
								<th>Status</th>
								<th width="12%">Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>

<script>
var table;
document.addEventListener('DOMContentLoaded', function() {
    $('#filter_rekanan, #filter_subject').keyup(function() {
        table.ajax.reload(null, false);
    });

    table = $('#table-training').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        ajax: {
            url: '<?= base_url("training_teknisi/pagination/training") ?>',
            type: 'POST',
            data: function(d) {
                d.filter_rekanan = $('#filter_rekanan').val();
                d.filter_subject = $('#filter_subject').val();
                d.csrf_token = token;
            }
        },
        columnDefs: [
            { targets: [0, 1, 6, 8, 9, 10], className: 'text-center' }
        ]
    });

    // Delete Event Handler
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = $(this).data('object');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data training ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '<?= base_url() ?>' + url + '/' + id,
                    type: 'POST',
                    dataType: 'JSON',
                    data: { csrf_token: token },
                    success: function(response) {
                        if (response.status == 'success') {
                            Swal.fire('Terhapus!', response.message, 'success');
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
                    }
                });
            }
        });
    });
});
</script>
