<header class="page-header">
    <h2><i class="fas fa-flag"></i>&nbsp;<?= $page_title ?></h2>
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
                            <th> Package </th>
                            <th> Deskripsi</th>
                            <th> Link Download</th>
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
                url: 'detail_package_mesin/pagination',
                type: 'POST',
                data: function(e) {
                    // e.tahun = $('#tahun').val()
                    e.csrf_token = token
                }
            },
            columnDefs: [{
                targets: [0, 3],
                className: 'text-center'
            }]
        })   
    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>