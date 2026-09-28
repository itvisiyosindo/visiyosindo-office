<header class="page-header">
    <h2><i class="icons icon-list"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<div class="row">
    <div class="col">
        <div class="row form-group col-md-2">
            <small>Filter By Month:</small>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Pengguna</th>
                            <th> Aksi </th>
                            <th> Keterangan </th>
                            <th> Tanggal </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    /*document.addEventListener('DOMContentLoaded', function() {
        $('#filter_month').change(function() {
            table.ajax.reload()
        })

        table = $('#kt_table_1').DataTable({
            responsive: false,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            scrollY: '50vh',
            scrollX: true,
            scrollCollapse: true,
            deferRender: true, //  ini menghemat memori
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: 'log/pagination',
                type: 'POST',
                data: function(e) {
                    // e.tahun = $('#tahun').val(),
                    e.filter_month = $('#filter_month').val()
                    e.csrf_token = token
                }
            },
            columnDefs: [{
                targets: [0],
                className: 'text-center'
            }]
        })
    })*/

            document.addEventListener('DOMContentLoaded', function () {
                // Set default bulan sekarang di filter
                const now = new Date();
                const month = now.getMonth() + 1; // getMonth dimulai dari 0
                const formattedMonth = `${now.getFullYear()}-${month.toString().padStart(2, '0')}`;
                $('#filter_month').val(formattedMonth); // set value ke input

                // Reload data saat bulan diubah
                $('#filter_month').change(function () {
                    table.ajax.reload();
                });

                // Inisialisasi DataTable
                table = $('#kt_table_1').DataTable({
                    responsive: false,
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    scrollY: '50vh',
                    scrollX: true,
                    scrollCollapse: true,
                    deferRender: true,
                    order: [[0, 'desc']],
                    ajax: {
                        url: 'log/pagination',
                        type: 'POST',
                        data: function (e) {
                            e.filter_month = $('#filter_month').val();
                            e.csrf_token = token;
                        }
                    },
                    columnDefs: [{
                        targets: [0],
                        className: 'text-center'
                    }]
                });
            });

</script>