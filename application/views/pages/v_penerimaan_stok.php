<header class="page-header">
    <h2><i class="icons fas fa-truck-loading"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col">
        <div class="">
            <a href="<?= base_url('penerimaan_stok/show') ?>" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Data</a>
        </div>
        <br>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <small>Filter By Month:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <small>Filter By Gudang Asal:</small>
                    <select class="form-control " name="filter_gudang_asal" id="filter_gudang_asal">
                        <option value="">Semua</option>
                        <?php foreach ($gudang as $row) { ?>
                            <option value="<?= encrypt($row->id_gudang) ?>"><?= $row->nama_gudang ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <small>Filter By Gudang Tujuan:</small>
                    <select class="form-control " name="filter_gudang_tujuan" id="filter_gudang_tujuan">
                        <option value="">Semua</option>
                        <?php foreach ($gudang as $row) { ?>
                            <option value="<?= encrypt($row->id_gudang) ?>"><?= $row->nama_gudang ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> No Penerimaan Stok </th>
                            <th> Tanggal Penerimaan Stok </th>
                            <th> Gudang Asal </th>
                            <th> Gudang Tujuan </th>
                            <th> Keterangan </th>
                            <th> Aksi </th>
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
            <?= form_open('penerimaan_stok/update/file_pendukung', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="form-group mb-2 pt-1">
                    <label for="InputExperience" class="col-form-label">File Pendukung :</label>
                    <input type="file" class="form-control" name="file_pendukung">
                    <small class="text-danger">upload file jpg, jpeg, png, pdf, doc dan xls, max 4mb</small>
                </div>
                <div>
                    <div class="text-center mb-2">
                        <button type="button" id="btn-lihat" class="btn btn-primary">Lihat File</button>
                    </div>
                    <div class="text-center">
                        <a href="javascript:" id="btn-download"><i class="fas fa-download"></i> Download File</a>
                    </div>

                </div>
                <div class="modal-footer">
                    <input class="form-control" type="hidden" id="id_penerimaan_stok" name="id_penerimaan_stok">
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
        $('#filter_gudang_asal, #filter_gudang_tujuan, #filter_month').change(function() {
            updateDatatable()
        })
        table = $('#kt_table_1').DataTable({
            responsive: false,
            processing: true,
            serverSide: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: 'penerimaan_stok/pagination',
                type: 'POST',
                data: function(e) {
                    e.filter_gudang_asal = $('#filter_gudang_asal').val()
                    e.filter_gudang_tujuan = $('#filter_gudang_tujuan').val()
                    e.filter_month = $('#filter_month').val()
                    e.csrf_token = token
                }
            },
            columnDefs: [{
                targets: [0, 2, 6],
                className: 'text-center'
            }]
        })

        $(document).on('click', '.btn-edit', function() {
            var id = $(this).attr("data-id")
            var link = 'penerimaan_stok/edit/' + id
            window.location = '<?= base_url() ?>' + link
        })

        //file pendukung
        $(document).on('click', '.btn-file', function() {
            
            $('#main-modal .form-control').val(null)
            $('#main-modal').modal()

            var id = $(this).attr("data-id")
            var no_penerimaan_stok = $(this).attr("no-penerimaan-stok")

            $('#main-modal #modal-label').html('No Penerimaan Stok : ' + no_penerimaan_stok)
            $('#main-modal #id_penerimaan_stok').val(id)
        })

        $(document).on('click', '#btn-download', function() {
            var id = $('#main-modal #id_penerimaan_stok').val()
            var link = 'penerimaan_stok/get/download_file/' + id
            window.open('<?= base_url() ?>' + link)
        })

        $(document).on('click', '#btn-lihat', function() {
            var id = $('#main-modal #id_penerimaan_stok').val()
            $.ajax({
                method: 'POST',
                url: 'penerimaan_stok/get/lihat_file',
                dataType: 'JSON',
                data: {
                    id: id,
                    csrf_token: token
                },
                success: function(name) {
                    if (name) {
                        var link = 'uploads/penerimaan_stok/' + name
                        window.open('<?= base_url() ?>' + link)
                    } else {
                        alert('file tidak ada!')
                    }
                }
            })
        })
        //end file pendukung
    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>