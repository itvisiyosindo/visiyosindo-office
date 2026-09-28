<header class="page-header">
	<h2><i class="fas fa-book-reader"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
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
					<table class="table table-striped table-bordered table-hover table-sm" id="table-my-training" style="width: 100%;">
						<thead>
							<tr>
								<th width="3%">#</th>
								<th>Kode Training</th>
								<th>Rekanan</th>
								<th>CP</th>
								<th>Subject</th>
								<th>Kategori</th>
								<th>Prioritas</th>
								<th>Waktu Pelaksanaan</th>
								<th>Status</th>
								<th width="8%">Aksi</th>
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

    table = $('#table-my-training').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        ajax: {
            url: '<?= base_url("training_teknisi/pagination/my_training") ?>',
            type: 'POST',
            data: function(d) {
                d.filter_rekanan = $('#filter_rekanan').val();
                d.filter_subject = $('#filter_subject').val();
                d.csrf_token = token;
            }
        },
        columnDefs: [
            { targets: [0, 1, 6, 7, 8, 9], className: 'text-center' }
        ]
    });
});
</script>
