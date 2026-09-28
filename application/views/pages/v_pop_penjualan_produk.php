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
        <div class="">
            <?php if (isAdmin() || isStafAdmin()) { ?>
                <a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Populasi</a>
            <?php } ?>
        </div>
        <br>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Nama </th>
                            <th> Kategori</th>
                            <!--<th> Wilayah</th>-->
                            <th> Link Download</th>
                            <?php if (isAdmin() || isStafAdmin()) { ?>
                                <th> Aksi </th>
                            <?php } ?>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama" class="form-control-label">Nama <span class="text-danger">*</span> :</label>
                    <input type="text" class="form-control" id="nama_pop_penjualan_produk" name="nama_pop_penjualan_produk" required>
                </div>
                <div class="form-group">
                    <label for="nama" class="form-control-label">Kategori <span class="text-danger">*</span> :</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" required>
                </div>
                <!--<div class="form-group">-->
                <!--    <label for="nama" class="form-control-label">Wilayah <span class="text-danger">*</span> :</label>-->
                <!--    <input type="text" class="form-control" id="wilayah" name="wilayah" required>-->
                <!--</div>-->
                <div class="form-group">
                    <label for="nama" class="form-control-label">Link Download <span class="text-danger">*</span> :</label>
                    <input type="text" class="form-control" id="link_download" name="link_download" required>
                </div>
                <div class="modal-footer">
                    <input class="form-control" type="hidden" id="id_pop_penjualan_produk" name="id_pop_penjualan_produk">
                    <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                    <button type="button" id="save-form" class="btn btn-success btn-save">Simpan</button>
                </div>
            </div>
            <?= form_close(); ?>
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
                url: 'pop_penjualan_produk/pagination',
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

        $('#btn-show-add-form').click(function() {
            $('.form-control').val(null)
            $('#main-modal #modal-form').attr('action', 'pop_penjualan_produk/add')
            $('#main-modal').modal()
        })

        $(document).on('click', '.btn-edit', function() {
            var object = 'pop_penjualan_produk'
            $('.form-control').val(null)
            $('#main-modal #modal-form').attr('action', 'pop_penjualan_produk/update')
            $('#main-modal').modal()

            var id = $(this).attr("data-id")
            fetch(object + '/edit/' + id)
                .then(function(resp) {
                    return resp.json()
                })
                .then(function(data) {
                    $('#main-modal #id_pop_penjualan_produk').val(data[0].id_pop_penjualan_produk)
                    $('#main-modal #nama_pop_penjualan_produk').val(data[0].nama_pop_penjualan_produk)
                    $('#main-modal #kategori').val(data[0].kategori)
                    // $('#main-modal #wilayah').val(data[0].wilayah)
                    $('#main-modal #link_download').val(data[0].link_download)
                })
        })

    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>