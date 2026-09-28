<header class="page-header">
    <h2><i class="bx bx-bell"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                        <th> # </th>
                                <th> Keterangan </th>
                                <th> Judul </th>
                                <th> Pengguna</th>
                                <th> Tanggal </th>
                                <th> Pukul </th>
                                <th> Status </th>
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
            scrollY: '50vh',
            scrollX: true,
            scrollCollapse: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: 'notifikasi/pagination',
                type: 'POST',
				data: function(e) {
					e.csrf_token = token
				}
            },
            columnDefs: [{
                targets: [0, 2],
                className: 'text-center'
            }]
        })
    })
</script>