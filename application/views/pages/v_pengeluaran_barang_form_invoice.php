<header class="page-header">
    <h2><i class="icons fas fa-share-square"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<div class="col-xl-8 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:10%">
        <div class="text-center mt-0">
            <h2>Form Pengeluaran Barang By Invoice</h2>
        </div>
        <?= form_open('pengeluaran_barang/add', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Customer <span class="text-danger">*</span></label>
                    <input readonly class="form-control" type="text" value="<?= $pengeluaran_barang[0]->nama_customer ?>" required>
                    <input type="hidden" name="id_customer" id="id_customer" value="<?= encrypt($pengeluaran_barang[0]->id_customer) ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group  mb-2 pt-1">
                    <label class="col-form-label">Tanggal Pengiriman <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tgl_keluar" id="tgl_keluar" value="" required data-plugin-datepicker>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Expedisi <span class="text-danger">*</span></label>
                    <select class="form-control" name="id_ekspedisi" id="id_ekspedisi" required>
                        <option value="">...</option>
                        <?php foreach ($ekspedisi as $e) { ?>
                            <option value="<?= encrypt($e->id_ekspedisi) ?>"><?= $e->nama_ekspedisi ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Pengiriman <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="no_pengiriman" id="no_pengiriman" value="" required>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Gudang <span class="text-danger">*</span></label>
                    <select readonly class="form-control" name="id_gudang" id="id_gudang" required>
                        <option value="<?= encrypt($gudang[0]->id_gudang) ?>"><?= $gudang[0]->nama_gudang ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Purchase Order (PO) <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="no_po" id="no_po" value="" required>
                </div>
            </div>
        </div>

        <div class="form-group mb-2 pt-1">
            <label class=" col-form-label">Alamat <span class="text-danger">*</span></label>
            <textarea class="form-control" name="alamat" id="alamat" required placeholder="..."><?= $temp_data[0]->alamat ?? NULL ?></textarea>
        </div>

        <div class="form-group mb-2 pt-1">
            <label class=" col-form-label">Keterangan <span class="text-danger">*</span></label>
            <textarea class="form-control" name="keterangan" id="keterangan" required placeholder="..."><?= $temp_data[0]->keterangan ?? NULL ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Resi <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="resi" id="resi" value="" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Status Pengiriman <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="status_pengiriman" id="status_pengiriman" value="" required>
                </div>
            </div>
        </div>
        <br>
        <div class="card-body">
            <div class="table-responsive">
                <strong class="text-success">READY STOCK</strong>
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Nama Barang</th>
                            <th> Kuantitasi </th>
                            <th> No Batch </th>
                            <th> Exp Date </th>
                            <th> Copy </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 ?>
                        <?php $count_exp = 1;
                        $count_batch = 1;
                        $count_id_batch = 1;
                        $count_id_detail_barang = 1;
                        $index_btn_duplicate = 1 ?>
                        <!-- menampilkan detail barang keluar yg asli -->
                        <?php foreach ($ready_stok as $key => $row) { ?>
                            <tr class="index-<?= $index_btn_duplicate ?>">
                                <td> <input class="td-1" type="hidden" name="id_detail_barang[]" value="" id="id_detail_barang<?= $count_id_detail_barang++ ?>"> <strong class="number"><?= $i++ ?> </strong><input type="hidden" name="id_detail_barang_invoice[]" value="<?= encrypt($row->id_detail_barang_invoice) ?>"></td>
                                <td> <input name="id_barang[]" type="hidden" value="<?= encrypt($row->id_barang) ?>"><?= $row->nama_barang ?></td>
                                <td> <input name="qty[]" <?= $row->current_qty == 0 ? "readonly" : "" ?> type="number" class="form-control form-control-sm" value="<?= $row->current_qty ?>"></td>
                                <td class="td-4">
                                    <select count_batch="<?= $count_batch++ ?>" class="form-control form-control-sm no_batch" name="no_batch[]" id="no_batch<?= $count_id_batch++ ?>" required>
                                        <option value="">...</option>
                                        <?php foreach ($row->batch as $row2) { ?>
                                            <option id_detail_barang_attr="<?= encrypt($row2->id_detail_barang) ?>" exp_attr="<?= date_view_format($row2->exp_date) ?>" value="<?= $row2->no_batch ?>"><?= $row2->no_batch ?> || current stok : <?= $row2->current_stock ?> || Exp : <?= date_view_format($row2->exp_date) ?> </option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td> <input name="exp_date[]" class="form-control form-control-sm td-5" readonly id="exp<?= $count_exp++ ?>" type="text" value=""> </td>
                                <td> <button class="btn text-warning btn-duplicate td-6" id="index-<?= $index_btn_duplicate ?>" key="<?= $index_btn_duplicate++ ?>" type="button">+</button></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if ($stok_habis) { ?>
            <div class="card-body">
                <div class="table-responsive">
                    <strong class="text-danger">OUT OF STOCK</strong>
                    <table class="table table-striped table-sm table-bordered table-hover">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Nama Barang</th>
                                <th> Kuantitasi </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1 ?>
                            <!-- menampilkan detail barang keluar yg asli -->
                            <?php foreach ($stok_habis as $row) { ?>
                                <tr>
                                    <td> <?= $i++ ?></td>
                                    <td> <?= $row->nama_barang ?> </td>
                                    <td> <?= $row->current_qty ?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
        <div class="modal-footer">
            <input type="hidden" id="id_pengeluaran_barang" name="id_pengeluaran_barang" value="<?= isset($pengeluaran_barang[0]->id_pengeluaran_barang) ?  $pengeluaran_barang[0]->id_pengeluaran_barang : NULL ?>">
            <input type="hidden" name="id_invoice" value="<?= encrypt($pengeluaran_barang[0]->id_invoice) ?>">
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Kembali</button>
            <?php if (isStafAdmin() || isAdmin()) { ?>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            <?php } ?>
        </div>

        <?= form_close(); ?>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(".search_barang_diform").themePluginSelect2({
            placeholder: "--- Scan Barcode / Ketik Nama Barang ---",
            allowClear: true,
            minimumInputLength: 1,
            width: '100%',
            ajax: {
                method: 'POST',
                url: "barang/get/by_search",
                dataType: 'json',
                delay: 250,
                data:

                    function(params) {
                        return {
                            q: params.term, // search term
                            id_gudang: $('#id_gudang').val(),
                            csrf_token: token
                        };
                    },
                processResults: function(data, params) {
                    return {
                        results: $.map(data.items, function(obj) {
                            return {
                                id: obj.id_barang,
                                text: `${obj.nama_barang}`
                            };
                        })
                    }
                },
                cache: true
            },
        });

        // $('.search_customer').themePluginSelect2('data', {id: '123', text: 'res_data.primary_email'});
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
                            q: params.term, // search term
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

        $(document).on('change', '.search_barang_diform', function() {
            // $('#no_batch').empty()
            $('#no_batch').children('option:not(:first)').remove();
            $('#exp_date').empty()
            const id_barang = $('#id_barang').val()
            const id_gudang = $('#id_gudang').val()
            $.ajax({
                method: 'POST',
                url: 'detail_barang/get/no_batch',
                data: {
                    id_barang: id_barang,
                    id_gudang: id_gudang,
                    csrf_token: token
                },
                dataType: 'json',
                success: function(resp) {
                    resp.forEach(data => {
                        let x = `
                            <option value="${data.no_batch}" exp_attr="${data.exp_date}" qty_attr="${data.current_stock}" id_detail_barang_attr="${data.id_detail_barang}">
                                ${data.no_batch} || Exp:  ${data.exp_date} || Stock Tersedia: ${data.current_stock}
                            </option>
                        `;
                        $('#no_batch').append(x);
                    })

                }
            })
        })

        $(document).on('change', '.no_batch', function() {
            //isi kolom exp date
            var option = $('option:selected', this).attr('exp_attr');
            var count = $(this).attr('count_batch')
            $('#exp' + count).val(option);

            //isi id_detail_baran
            var id_detail_barang = $('option:selected', this).attr('id_detail_barang_attr')
            $('#id_detail_barang' + count).val(id_detail_barang);
        })

        $('.btn-save').click(function() {
            if (!$('#id_customer').val() || !$('#id_gudang').val() || !$("#tgl_keluar").val() || !$("#no_pengiriman").val() || !$("#id_ekspedisi").val() || !$("#no_po").val() || !$("#alamat").val() || !$("#keterangan").val()) {
                alert('form tidak boleh kosong!!!')
                return false;
            }
        })

        $('.btn-duplicate').click(function() {
            //last data on foreach + 1
            let index = parseInt($('#kt_table_1 tr:last .td-6').attr('key')) + 1
            let td1_id = $('#kt_table_1 tr:last .td-1').attr('id').replace(/[0-9]/g, '') + index;
            let td4_count_batch = $('#kt_table_1 tr:last select').attr('count_batch').replace(/[0-9]/g, '') + index;
            let td4_id = $('#kt_table_1 tr:last select').attr('id').replace(/[0-9]/g, '') + index;
            let td5_id = $('#kt_table_1 tr:last .td-5').attr('id').replace(/[0-9]/g, '') + index;
            let td6_id = $('#kt_table_1 tr:last .td-6').attr('id').replace(/[0-9]/g, '') + index;
            let td6_key = $('#kt_table_1 tr:last .td-6').attr('key').replace(/[0-9]/g, '') + index;

            //append new row on table
            let key = $(this).attr('key')
            var html = $(`.index-${key}`).html();
            let x = `<tr>${html}</tr>`;
            $('#kt_table_1 tr:last').after(x);

            //new row, ikuti semua count di foreach
            $('#kt_table_1 tr:last .td-1').attr('id', td1_id)
            $('#kt_table_1 tr:last select').attr('count_batch', td4_count_batch)
            $('#kt_table_1 tr:last select').attr('id', td4_id)
            $('#kt_table_1 tr:last .td-5').attr('id', td5_id)
            $('#kt_table_1 tr:last .td-6').attr('id', td6_id)
            $('#kt_table_1 tr:last .td-6').attr('key', td6_key)
            $('#kt_table_1 tr:last .number').html(index)
            $(`#${td6_id}`).hide();
        })
    })

    function goBack() {
        window.history.back();
    }
</script>