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
            <?php if (isStafAdmin() || isAdmin() || sessPenggunaId() == 763 || sessPenggunaId() == 769) { ?>
                <a href="<?= base_url('invoice/show') ?>" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Data</a>
                <a class="btn btn-sm btn-primary" id="tarik-pengeluaran-barang"><i class="icons icon-plus"></i>&nbsp;Tarik dari pengluaran barang</a>
            <?php } ?>
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
                    <small>Filter By Pembuatan Invoice:</small>
                    <select class="form-control " name="filter_status_pembuatan" id="filter_status_pembuatan">
                        <option value="all">Semua</option>
                        <option value="0">Buat invoice manual</option>
                        <option value="1">Tarik dari pengeluaran barang</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <small>Filter By Barang Keluar:</small>
                    <select class="form-control " name="filter_pengeluaran_barang" id="filter_pengeluaran_barang">
                        <option value="">Semua</option>
                        <option value="1">Belum ada Pengeluaran Barang</option>
                        <option value="2">Sebagian sudah di keluarkan</option>
                        <option value="3">Sudah di keluarkan seluruhnya</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> No Invoice </th>
                            <th> Tanggal Invoice </th>
                            <th> Nama Customer </th>
                            <th> Nama Marketing </th>
                            <th> Dari Pengeluaran Barang </th>
                            <th> Pengeluaran Barang </th>
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
            <?= form_open('invoice/update/link_file_pendukung', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-2 pt-1">
                            <label class=" col-form-label">Link file Invoice <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-9">
                                    <input class="form-control" type="text" name="file_invoice" id="file_invoice" value="" required>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <a href="javascript:" action='file_invoice' id="btn-download"><i class="fas fa-download"></i> download</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-2 pt-1">
                            <label class=" col-form-label">Link file PO <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-9">
                                    <input class="form-control" type="text" name="file_po" id="file_po" value="" required>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <a href="javascript:" action='file_po' id="btn-download"><i class="fas fa-download"></i> download</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-2 pt-1">
                            <label class=" col-form-label">Link file Faktur Pajak <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-9">
                                    <input class="form-control" type="text" name="file_faktur_pajak" id="file_faktur_pajak" value="" required>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <a href="javascript:" action='file_faktur_pajak' id="btn-download"><i class="fas fa-download"></i> download</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-2 pt-1">
                            <label class=" col-form-label">Link File Lainnya <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-9">
                                    <input class="form-control" type="text" name="file_lainnya" id="file_lainnya" value="" required>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <a href="javascript:" action='file_lainnya' id="btn-download"><i class="fas fa-download"></i> download</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input class="form-control" type="hidden" id="id_invoice" name="id_invoice">
                    <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                    <?php if (isStafAdmin() || isAdmin()) { ?>
                        <button type="button" id="save-form" class="btn btn-success btn-save">Simpan</button>
                    <?php } ?>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- modal-by-pengeluaran -->
<div id="modal-by-pengeluaran" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Tarik Dari Pengeluaran Barang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-2 pt-1">
                            <label class="col-form-label">Customer <span class="text-danger">*</span></label>
                            <select data-plugin-selectTwo class="form-control search_customer filter-grup" name="id_customer" id="id_customer">
                            </select>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12">
                        <section class="card">
                            <header class="card-header text-center">
                                <h2 class="card-title">Pilih Invoice</h2>
                            </header>
                            <div class="card-body">
                                <div class="scrollable visible-slider colored-slider" data-plugin-scrollable style="height: 350px;width:auto">
                                    <div class="scrollable-content">
                                        <table class="table table-striped table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th> # </th>
                                                    <th> No Pengeluaran Barang</th>
                                                    <th> Tanggal Pengeluaran</th>
                                                    <th> Check </th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-pengeluaran-barang">
                                                <!-- isi table -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                    <button type="button" id="save-form" class="btn btn-success btn-add-pengeluaran">Simpan</button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- modal detail barang invoice -->
<div id="detail-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>info invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></bbtn btn-success btn-saveutton>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered table-hover" id="table_detail">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Nama Barang</th>
                                <th> Kode barang </th>
                                <th> Qty Invoice </th>
                                <th> Qty Barang di Keluarkan </th>
                                <th> Qty Belum di Keluarkan </th>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#filter_month, #filter_status_pembuatan, #filter_pengeluaran_barang').change(function() {
            updateDatatable()
        })

        $('#filter_customer').keyup(function() {
            updateDatatable()
        })

        $(document).on('change', '.search_customer', function() {

            $("#data-pengeluaran-barang").empty();
            id = $('.search_customer').val()
            $.ajax({
                method: 'POST',
                url: 'invoice/get/form_tarik_pengeluaran',
                dataType: 'JSON',
                data: {
                    id: id,
                    csrf_token: token
                },
                success: function(data) {
                    console.log(data);

                    let i = 1
                    data.forEach(dt => {
                        let x = `
                                <tr>
                                    <td><input type="hidden" value="">${i++} </td>
                                    <td><input type="hidden" value="">${dt.no_pengiriman}</td>
                                    <td><input type="hidden" value="">${dt.tgl_keluar}</td>
                                    <td><input type="hidden" value=""><input value='${dt.id_pengeluaran_barang}' type="checkbox" name="check"></td>
                                </tr>
                                `
                        $('#data-pengeluaran-barang').append(x)
                    })
                }
            })
        });

        table = $('#kt_table_1').DataTable({
            responsive: false,
            processing: true,
            serverSide: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: 'invoice/pagination',
                type: 'POST',
                data: function(e) {
                    e.filter_month = $('#filter_month').val()
                    e.filter_pengeluaran_barang = $('#filter_pengeluaran_barang').val()
                    e.filter_status_pembuatan = $('#filter_status_pembuatan').val()
                    e.csrf_token = token
                }
            },
            columnDefs: [{
                targets: [0, 2, 5, 6],
                className: 'text-center'
            }]
        })

        $(".search_customer").themePluginSelect2({
            placeholder: "--- Ketik Nama Customer ---",
            // data: 'fuadi',
            data: {
                id: '123',
                text: 'fuadi'
            },
            allowClear: true,
            minimumInputLength: 1,
            width: '100%',
            ajax: {
                method: 'POST',
                url: "customer/get/by_search",
                dataType: 'json',
                delay: 250,
                data:

                    function(params) {
                        return {
                            q: params.term,
                            csrf_token: token
                        };
                    },
                processResults: function(data, params) {
                    return {
                        results: $.map(data.items, function(obj) {
                            return {
                                id: obj.id_customer,
                                text: `${obj.nama_customer}`
                            };
                        })
                    }
                },
                cache: true
            },
        });

        $(document).on('click', '.btn-edit', function() {
            var id = $(this).attr("data-id")
            var link = 'invoice/edit/' + id
            window.location = '<?= base_url() ?>' + link
        })

        $(document).on('click', '.btn-add-pengeluaran', function() {
            if (!$('#id_customer').val()) {
                alert('form tidak boleh kosong!!!')
            } else {

                var id_customer = $('.search_customer').val()
                var id_pengeluaran_barang = []
                $("input:checkbox[name=check]:checked").each(function() {
                    id_pengeluaran_barang.push($(this).val());
                });

                var tmp = 'invoice/show/' + id_customer + '/' + id_pengeluaran_barang
                link = tmp.replaceAll(",", "-")

                window.location = '<?= base_url() ?>' + link
            }
        })

        $(document).on('click', '#tarik-pengeluaran-barang', function() {
            $('#modal-by-pengeluaran .form-control').val(null)
            $("#data-pengeluaran-barang").empty();
            $(".search_customer").empty();
            $('#modal-by-pengeluaran').modal()
        })

        //Link file pendukung
        $(document).on('click', '.btn-file', function() {
            $('#main-modal .form-control').val(null)
            $('#main-modal').modal()

            var id = $(this).attr("data-id")
            var no_invoice = $(this).attr("no-invoice")
            $.ajax({
                method: 'POST',
                url: 'invoice/get/link_file_pendukung',
                dataType: 'JSON',
                data: {
                    id: id,
                    csrf_token: token
                },
                success: function(data) {
                    $('#main-modal #file_invoice').val(data['file_invoice'])
                    $('#main-modal #file_po').val(data['file_po'])
                    $('#main-modal #file_faktur_pajak').val(data['file_faktur_pajak'])
                    $('#main-modal #file_lainnya').val(data['file_lainnya'])
                }
            })

            $('#main-modal #modal-label').html('No Invoice : ' + no_invoice)
            $('#main-modal #id_invoice').val(id)
        })

        $(document).on('click', '#btn-download', function() {
            var id = $('#main-modal #id_invoice').val()
            var action = $(this).attr("action")

            $.ajax({
                method: 'POST',
                url: 'invoice/get/download_file',
                dataType: 'JSON',
                data: {
                    id: id,
                    action: action,
                    csrf_token: token
                },
                success: function(link) {
                    window.open(link)
                }
            })

        })

        $(document).on('click', '#btn-lihat', function() {
            var id = $('#main-modal #id_pengeluaran_barang').val()
            $.ajax({
                method: 'POST',
                url: 'pengeluaran_barang/get/lihat_file',
                dataType: 'JSON',
                data: {
                    id: id,
                    csrf_token: token
                },
                success: function(name) {
                    if (name) {
                        var link = 'uploads/pengeluaran_barang/' + name
                        window.open('<?= base_url() ?>' + link)
                    } else {
                        alert('file tidak ada!')
                    }
                }
            })
        })

        $(document).on('click', '.btn-detail', function() {
            console.log('fuadi');

            $('#detail-modal .form-control').val(null)
            $('#detail-modal .value_row').remove()
            $('#detail-modal').modal()
            var id = $(this).attr("data-id")

            $.ajax({
                method: 'POST',
                url: 'invoice/get/resume_detail_invoice',
                data: {
                    id_invoice: id,
                    csrf_token: token
                },
                dataType: 'json',
                success: function(resp) {
                    // $('#detail-modal #no_pemindahan').val(resp['pengiriman_stok'][0].no_pemindahan)
                    // $('#detail-modal #tgl_pengiriman').val(resp['pengiriman_stok'][0].tgl_pengiriman)
                    // $('#detail-modal #gudang_asal').val(resp['pengiriman_stok'][0].nama_gudang_asal)
                    // $('#detail-modal #gudang_tujuan').val(resp['pengiriman_stok'][0].nama_gudang_tujuan)
                    // $('#detail-modal #status_pengiriman').val(resp['pengiriman_stok'][0].status_pengiriman)
                    // $('#detail-modal #nama_ekspedisi').val(resp['pengiriman_stok'][0].nama_ekspedisi)
                    // $('#detail-modal #no_resi').val(resp['pengiriman_stok'][0].no_resi)
                    // $('#detail-modal #keterangan').val(resp['pengiriman_stok'][0].keterangan)
                    $('#detail-modal #modal-label').html("info invoice : " + resp['invoice'][0].no_invoice)
                    i = 1
                    resp['detail_barang'].forEach(data => {
                        let x = `
                            <tr class="value_row">
                                <td>${i++} </td>
                                <td>${data.nama_barang} </td>
                                <td>${data.kode_barang}</td>
                                <td>${data.qty} </td>
                                <td>${data.qty - data.current_qty} </td>
                                <td>${data.current_qty == null ? '-' : data.current_qty} </td>
                            </tr>
                        `;
                        $('#table_detail').append(x);
                    })
                }
            })
        })
    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>