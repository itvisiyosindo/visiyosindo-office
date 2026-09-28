<header class="page-header">
    <h2><i class="icons fas fa-share-square"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col">
        <div class="">
            <a href="<?= base_url('pengiriman_stok/show') ?>" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Data</a>
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
                    <small>Filter By Status Pengiriman:</small>
                    <select class="form-control " name="filter_status_pengiriman" id="filter_status_pengiriman">
                        <option value="">Semua</option>
                        <option value="1">Transit</option>
                        <option value="2">Diterima Seluruhnya</option>
                        <option value="3">Diterima Sebagian</option>
                        <option value="4">Belum Diterima</option>
                    </select>
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
                            <th> No Pemindahan</th>
                            <th> Tanggal Pengiriman </th>
                            <th> Status Pengiriman</th>
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
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Detail Pengiriman Stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Pemindahan <span class="text-danger">*</span></label>
                    <input disabled class="form-control" type="number" id="no_pemindahan">
                    <div id="danger-alert"></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group  mb-2 pt-1">
                            <label class="col-form-label">Tanggal Pengiriman <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                <input disabled type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" id="tgl_pengiriman" value="" required data-plugin-datepicker>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2 pt-1">
                            <label class=" col-form-label">Ekspedisi <span class="text-danger">*</span></label>
                            <input disabled class="form-control" type="text" id="nama_ekspedisi">
                            <div id="danger-alert"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2 pt-1">
                            <label class=" col-form-label">Gudang Asal <span class="text-danger">*</span></label>
                            <input disabled class="form-control" type="text" id="gudang_asal">
                            <div id="danger-alert"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2 pt-1">
                            <label class=" col-form-label">Gudang Tujuan <span class="text-danger">*</span></label>
                            <input disabled class="form-control" type="text" id="gudang_tujuan">
                            <div id="danger-alert"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2 pt-1">
                            <label class=" col-form-label">Status Pengiriman <span class="text-danger">*</span></label>
                            <input disabled class="form-control" type="text" id="status_pengiriman">
                            <div id="danger-alert"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2 pt-1">
                            <label class=" col-form-label">No Resi <span class="text-danger">*</span></label>
                            <input disabled class="form-control" type="text" id="no_resi">
                            <div id="danger-alert"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Keterangan <span class="text-danger">*</span></label>
                    <input disabled class="form-control" type="text" id="keterangan">
                    <div id="danger-alert"></div>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered table-hover" id="table_detail">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Nama Barang</th>
                                <th> No Batch </th>
                                <th> Qty Dikirim </th>
                                <th> Qty Diterima Seluruhnya </th>
                                <th> Qty Belum Diterima </th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="file-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('pengiriman_stok/update/file_pendukung', array('id' => 'file-form', 'autocomplete' => 'off')); ?>
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
                    <input class="form-control" type="hidden" id="id_pengiriman_stok" name="id_pengiriman_stok">
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
        $('#filter_gudang_asal, #filter_gudang_tujuan,  #filter_status_pengiriman, #filter_month').change(function() {
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
                url: 'pengiriman_stok/pagination',
                type: 'POST',
                data: function(e) {
                    e.filter_gudang_asal = $('#filter_gudang_asal').val()
                    e.filter_gudang_tujuan = $('#filter_gudang_tujuan').val()
                    e.filter_status_pengiriman = $('#filter_status_pengiriman').val()
                    e.filter_month = $('#filter_month').val()
                    e.csrf_token = token
                }
            },
            columnDefs: [{
                targets: [0, 2, 3, 7],
                className: 'text-center'
            }]
        })

        $(document).on('click', '.btn-detail', function() {
            $('#main-modal .form-control').val(null)
            $('#main-modal .value_row').remove()
            $('#main-modal').modal()
            var id = $(this).attr("data-id")

            $.ajax({
                method: 'POST',
                url: 'pengiriman_stok/get/resume_detail_barang',
                data: {
                    id_pengiriman_stok: id,
                    csrf_token: token
                },
                dataType: 'json',
                success: function(resp) {
                    $('#main-modal #no_pemindahan').val(resp['pengiriman_stok'][0].no_pemindahan)
                    $('#main-modal #tgl_pengiriman').val(resp['pengiriman_stok'][0].tgl_pengiriman)
                    $('#main-modal #gudang_asal').val(resp['pengiriman_stok'][0].nama_gudang_asal)
                    $('#main-modal #gudang_tujuan').val(resp['pengiriman_stok'][0].nama_gudang_tujuan)
                    $('#main-modal #status_pengiriman').val(resp['pengiriman_stok'][0].status_pengiriman)
                    $('#main-modal #nama_ekspedisi').val(resp['pengiriman_stok'][0].nama_ekspedisi)
                    $('#main-modal #no_resi').val(resp['pengiriman_stok'][0].no_resi)
                    $('#main-modal #keterangan').val(resp['pengiriman_stok'][0].keterangan)

                    i = 1
                    resp['detail_barang'].forEach(data => {
                        let x = `
                            <tr class="value_row">
                                <td>${i++} </td>
                                <td>${data.nama_barang} </td>
                                <td>${data.no_batch}</td>
                                <td>${data.qty} </td>
                                <td>${data.qty - data.current_qty} </td>
                                <td>${data.current_qty == 0 ? '-' : data.current_qty} </td>
                            </tr>
                        `;
                        $('#table_detail').append(x);
                    })
                }
            })
        })

        $(document).on('click', '.btn-edit', function() {
            var id = $(this).attr("data-id")
            var link = 'pengiriman_stok/edit/' + id
            window.location = '<?= base_url() ?>' + link
        })

        //file pendukung
        $(document).on('click', '.btn-file', function() {
            $('#file-modal .form-control').val(null)
            $('#file-modal').modal()

            var id = $(this).attr("data-id")
            var no_pemindahan = $(this).attr("no-pemindahan")

            $('#file-modal #modal-label').html('No Pemindahan : ' + no_pemindahan)
            $('#file-modal #id_pengiriman_stok').val(id)
        })

        $(document).on('click', '#btn-download', function() {
            var id = $('#file-modal #id_pengiriman_stok').val()
            var link = 'pengiriman_stok/get/download_file/' + id
            window.open('<?= base_url() ?>' + link)
        })

        $(document).on('click', '#btn-lihat', function() {
            var id = $('#file-modal #id_pengiriman_stok').val()
            $.ajax({
                method: 'POST',
                url: 'pengiriman_stok/get/lihat_file',
                dataType: 'JSON',
                data: {
                    id: id,
                    csrf_token: token
                },
                success: function(name) {
                    if (name) {
                        var link = 'uploads/pengiriman_stok/' + name
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