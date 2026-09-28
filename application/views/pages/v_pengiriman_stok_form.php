<header class="page-header">
    <h2><i class="icons fas fa-truck-moving"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<div class="col-xl-8 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:10%">
        <div class="text-center mt-0">
            <h2>Form Pemindahan Stock Gudang</h2>
        </div>
        <?php if ($this->uri->segment(2) == 'edit') { ?>
            <?= form_open('pengiriman_stok/update', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <?php } else { ?>
            <?= form_open('pengiriman_stok/add', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <?php } ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group  mb-2 pt-1">
                    <label class="col-form-label">Tanggal <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <?php if (isset($pengiriman_stok)) { ?>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tgl_pengiriman" id="tgl_pengiriman" value="<?= $pengiriman_stok[0]->tgl_pengiriman ?? NULL ?>" required data-plugin-datepicker>
                        <?php } else if ($temp_data) { ?>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tgl_pengiriman" id="tgl_pengiriman" value="<?= $temp_data[0]->tgl_pengiriman ?? NULL ?>" required data-plugin-datepicker>
                        <?php } else { ?>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tgl_pengiriman" id="tgl_pengiriman" value="" required data-plugin-datepicker>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Pemindahan <span class="text-danger">*</span></label>
                    <?php if (isset($pengiriman_stok)) { ?>
                        <input <?= $cek_sudah_terima == TRUE ? 'disabled' : '' ?> class="form-control" type="text" name="no_pemindahan" id="no_pemindahan" value="<?= $pengiriman_stok[0]->no_pemindahan ?? NULL ?>" required>
                    <?php } else if ($temp_data) { ?>
                        <input class="form-control" type="text" name="no_pemindahan" id="no_pemindahan" value="<?= $temp_data[0]->no_pemindahan ?? NULL ?>" required>
                    <?php } else { ?>
                        <input class="form-control" type="text" name="no_pemindahan" id="no_pemindahan" value="" required>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Gudang Asal <span class="text-danger">*</span></label>

                    <!-- variable penentu select akan aktif atau tidak -->
                    <?php if (isset($cek_sudah_terima)) { ?>
                        <?php if ($cek_sudah_terima == TRUE) { ?>
                            <?php $cek = TRUE ?>
                        <?php } else { ?>
                            <?php $cek = FALSE ?>
                        <?php } ?>
                    <?php } else { ?>
                        <?php $cek = FALSE ?>
                    <?php } ?>

                    <select <?= $this->uri->segment(2) == 'edit' ? 'disabled' : '' ?> class="form-control" name="id_gudang_asal" id="id_gudang_asal" required>
                        <option value="">...</option>
                        <?php if (isset($pengiriman_stok)) { ?>
                            <?php foreach ($gudang as $g) { ?>
                                <option <?= $pengiriman_stok[0]->id_gudang_asal == encrypt($g->id_gudang) ? 'selected' : NULL ?> value="<?= encrypt($g->id_gudang) ?>"><?= $g->nama_gudang ?></option>
                            <?php } ?>
                        <?php } else if ($temp_data) { ?>
                            <?php foreach ($gudang as $g) { ?>
                                <option <?= $temp_data[0]->id_gudang_asal == encrypt($g->id_gudang) ? 'selected' : NULL ?> value="<?= encrypt($g->id_gudang) ?>"><?= $g->nama_gudang ?></option>
                            <?php } ?>
                        <?php } else { ?>
                            <?php foreach ($gudang as $g) { ?>
                                <option value="<?= encrypt($g->id_gudang) ?>"><?= $g->nama_gudang ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Gudang Tujuan <span class="text-danger">*</span></label>
                    <select <?= $this->uri->segment(2) == 'edit' ? 'disabled' : '' ?> class="form-control" name="id_gudang_tujuan" id="id_gudang_tujuan" required>
                        <option value="">...</option>
                        <?php if (isset($pengiriman_stok)) { ?>
                            <?php foreach ($gudang as $g) { ?>
                                <option <?= $pengiriman_stok[0]->id_gudang_tujuan == encrypt($g->id_gudang) ? 'selected' : NULL ?> value="<?= encrypt($g->id_gudang) ?>"><?= $g->nama_gudang ?></option>
                            <?php } ?>
                        <?php } else if ($temp_data) { ?>
                            <?php foreach ($gudang as $g) { ?>
                                <option <?= $temp_data[0]->id_gudang_tujuan == encrypt($g->id_gudang) ? 'selected' : NULL ?> value="<?= encrypt($g->id_gudang) ?>"><?= $g->nama_gudang ?></option>
                            <?php } ?>
                        <?php } else { ?>
                            <?php foreach ($gudang as $g) { ?>
                                <option value="<?= encrypt($g->id_gudang) ?>"><?= $g->nama_gudang ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Expedisi <span class="text-danger">*</span></label>
                    <select class="form-control" name="id_ekspedisi" id="id_ekspedisi" required>
                        <option value="">...</option>
                        <?php if (isset($pengiriman_stok)) { ?>
                            <?php foreach ($ekspedisi as $e) { ?>
                                <option <?= $pengiriman_stok[0]->id_ekspedisi == encrypt($e->id_ekspedisi) ? 'selected' : NULL ?> value="<?= encrypt($e->id_ekspedisi) ?>"><?= $e->nama_ekspedisi ?></option>
                            <?php } ?>
                        <?php } else if ($temp_data) { ?>
                            <?php foreach ($ekspedisi as $e) { ?>
                                <option <?= $temp_data[0]->id_ekspedisi == encrypt($e->id_ekspedisi) ? 'selected' : NULL ?> value="<?= encrypt($e->id_ekspedisi) ?>"><?= $e->nama_ekspedisi ?></option>
                            <?php } ?>
                        <?php } else { ?>
                            <?php foreach ($ekspedisi as $e) { ?>
                                <option value="<?= encrypt($e->id_ekspedisi) ?>"><?= $e->nama_ekspedisi ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Resi <span class="text-danger">*</span></label>
                    <?php if (isset($pengiriman_stok)) { ?>
                        <input class="form-control" type="text" name="no_resi" id="no_resi" value="<?= $pengiriman_stok[0]->no_resi ?? NULL ?>" required>
                    <?php } else if ($temp_data) { ?>
                        <input class="form-control" type="text" name="no_resi" id="no_resi" value="<?= $temp_data[0]->no_resi ?? NULL ?>" required>
                    <?php } else { ?>
                        <input class="form-control" type="text" name="no_resi" id="no_resi" value="" required>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="form-group mb-2 pt-1">
            <?php if (isset($pengiriman_stok)) { ?>
                <label class=" col-form-label">Status Pengiriman <span class="text-danger">*</span></label>
                <input disabled class="form-control" type="text" id="status_pengiriman" value="<?= $pengiriman_stok[0]->status_pengiriman ?? NULL ?>" required>
            <?php } else if ($temp_data) { ?>
                <label class=" col-form-label">Status Pengiriman <span class="text-danger">*</span></label>
                <input disabled class="form-control" type="text" id="status_pengiriman" value="<?= $temp_data[0]->status_pengiriman ?? NULL ?>" required>
            <?php } ?>
        </div>
        <div class="form-group mb-2 pt-1">
            <label class=" col-form-label">Keterangan <span class="text-danger">*</span></label>
            <?php if (isset($pengiriman_stok)) { ?>
                <textarea class="form-control" name="keterangan" id="keterangan" required placeholder="..."><?= $pengiriman_stok[0]->keterangan ?? NULL ?></textarea>
            <?php } else if ($temp_data) { ?>
                <textarea class="form-control" name="keterangan" id="keterangan" required placeholder="..."><?= $temp_data[0]->keterangan ?? NULL ?></textarea>
            <?php } else { ?>
                <textarea class="form-control" name="keterangan" id="keterangan" required placeholder="..."></textarea>
            <?php } ?>
        </div>
        <br>
        <div class="form-group mb-2 pt-1">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-form-label">
                        <a href="javascript:;" id="btn-pilih-barang-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Barang</a>
                    </div>
                </div>
                <div class="col-md-6 text-right mt-4">
                    <?php if ($this->uri->segment(2) == "edit") { ?>
                        <a href="pengiriman_stok/print/<?= $pengiriman_stok[0]->id_pengiriman_stok ?>"> Print Dokumen <i class="fas fa-print"></i></a><br>
                    <?php } ?>
                </div>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Nama Barang</th>
                            <th> Kuantitas </th>
                            <th> No Batch </th>
                            <th> Exp Date </th>
                            <th> Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 ?>
                        <!-- menampilkan detail barang yg asli -->
                        <?php if (isset($detail_barang_pengiriman_stok)) { ?>
                            <?php foreach ($detail_barang_pengiriman_stok as $row) { ?>
                                <tr>
                                    <td> <input type="hidden" value="<?= $row->id_detail_barang ?? NULL ?>"><?= $i++ ?></td>
                                    <td> <input type="hidden" value="<?= $row->id_barang ?>"><?= $row->nama_barang ?> </td>
                                    <td> <input type="hidden" value="<?= $row->qty ?>"> <?= $row->qty ?></td>
                                    <td> <input type="hidden" value="<?= $row->no_batch ?>"> <?= $row->no_batch ?></td>
                                    <td><?= date_view_format($row->exp_date) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="<?= $row->id_detail_barang_pengiriman_stok ?>"><i class="bx bx-pencil"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="<?= $row->id_detail_barang_pengiriman_stok ?>" data-object="pengiriman_stok/delete/detail_barang_pengiriman_stok/" .<?= $row->id_detail_barang_pengiriman_stok ?>><i class="bx bx-trash"></i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                            <!-- jika ada data temp baru saat edit detail barang yg asli -->
                            <?php if (isset($new_detail_barang_pengiriman_stok_temp)) { ?>
                                <?php foreach ($new_detail_barang_pengiriman_stok_temp as $row) { ?>
                                    <tr>
                                        <td> <input type="hidden" name="id_detail_barang[]" value="<?= $row->id_detail_barang ?? NULL ?>"><?= $i++ ?></td>
                                        <td> <input type="hidden" name="id_barang[]" value="<?= $row->id_barang ?>"><?= $row->nama_barang ?> <span class="text-warning">(Temp)</span></td>
                                        <td> <input type="hidden" name="qty[]" value="<?= $row->qty ?>"> <?= $row->qty ?></td>
                                        <td> <input type="hidden" name="no_batch[]" value="<?= $row->no_batch ?>"> <?= $row->no_batch ?></td>
                                        <td><?= date_view_format($row->exp_date) ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary btn-edit-temp" data-id="<?= $row->id_detail_barang_pengiriman_stok_temp ?>"><i class="bx bx-pencil"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger delete-temp-barang" data-id="<?= $row->id_detail_barang_pengiriman_stok_temp ?>"><i class="bx bx-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                            <!-- tampilkan jika ada detail barang temp -->
                        <?php } else if (isset($detail_barang_temp)) { ?>
                            <?php foreach ($detail_barang_temp as $row) { ?>
                                <tr>
                                    <td> <input type="hidden" name="id_detail_barang[]" value="<?= $row->id_detail_barang ?? NULL ?>"><?= $i++ ?></td>
                                    <td> <input type="hidden" name="id_barang[]" value="<?= $row->id_barang ?>"><?= $row->nama_barang ?> </td>
                                    <td> <input type="hidden" name="qty[]" value="<?= $row->qty ?>"> <?= $row->qty ?></td>
                                    <td> <input type="hidden" name="no_batch[]" value="<?= $row->no_batch ?>"> <?= $row->no_batch ?></td>
                                    <td> <?= date_view_format($row->exp_date) ?> </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-edit-temp" data-id="<?= $row->id_detail_barang_pengiriman_stok_temp ?>"><i class="bx bx-pencil"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger delete-temp-barang" data-id="<?= $row->id_detail_barang_pengiriman_stok_temp ?>"><i class="bx bx-trash"></i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="id_pengiriman_stok" name="id_pengiriman_stok" value="<?= isset($pengiriman_stok[0]->id_pengiriman_stok) ?  $pengiriman_stok[0]->id_pengiriman_stok : NULL ?>">
            <button type="button" class="btn btn-primary" id="dest_temp">Dest Temp</button>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Kembali</button>
            <button type="button" class="btn btn-success btn-save">Simpan</button>
        </div>
        <?= form_close(); ?>
    </div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Form Detail Pengiriman Stok Gudang </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="form-group mb-2 pt-1">
                    <div class="col-form-label">
                    <label>Pilih Barang / &nbsp;<i class="fas fa-barcode"> &nbsp;</i>Scan Barcode </label>
                        <select data-plugin-selectTwo="search_barang" class="form-control search_barang_diform filter-grup" name="id_barang" id="id_barang"></select>
                    </div>
                </div>
                <label class="col-form-label">No Batch <span class="text-danger">*</span></label>
                <select class="form-control" name="no_batch" id="no_batch" required>
                    <option value="">...</option>
                </select>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Kuantitas <span class="text-danger">*</span></label>
                    <input class="form-control" type="number" name="qty" id="qty" value="" required>
                    <div id="danger-alert"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="form-control" id="id_detail_barang_pengiriman_stok_temp" name="id_detail_barang_pengiriman_stok_temp">
                    <input type="hidden" class="form-control" id="id_detail_barang_pengiriman_stok" name="id_detail_barang_pengiriman_stok">
                    <input type="hidden" class="form-control" id="id_pengiriman_stok" name="id_pengiriman_stok">
                    <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                    <button type="button" id="save-form" class="btn btn-success save-form">Simpan</button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="edit-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Form Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('pengiriman_stok/update/detail_barang_pengiriman_stok', array('id' => 'edit-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Nama Barang <span class="text-danger">*</span></label>
                    <input disabled class="form-control" type="text" id="nama_barang_edit" required>
                </div>
                <label class="col-form-label">No Batch <span class="text-danger">*</span></label>
                <input disabled class="form-control" type="text" id="no_batch_edit" required>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Kuantitas <span class="text-danger">*</span></label>
                    <input class="form-control" type="number" name="qty" id="qty_edit" required>
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="form-control" id="id_detail_barang_pengiriman_stok_edit" name="id_detail_barang_pengiriman_stok">
                    <input type="hidden" class="form-control" id="id_detail_barang_edit" name="id_detail_barang">
                    <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success btn-save">Simpan</button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
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
                            id_gudang: $('#id_gudang_asal').val(),
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


        $('#btn-pilih-barang-form').click(function() {
            if (!$('#no_pemindahan').val() || !$('#id_gudang_asal').val() || !$('#tgl_pengiriman').val() || !$('#id_gudang_tujuan').val() || !$('#keterangan').val()) {
                alert('form tidak boleh kosong!!!')
                //fuadi
            } else {
                $('#main-modal .form-control').val(null)
                $('#main-modal').modal()
                <?php if ($this->uri->segment(2) == "show") { ?>
                    $.ajax({
                        method: 'POST',
                        url: 'pengiriman_stok/add/temp',
                        dataType: 'JSON',
                        data: {
                            tgl_pengiriman: $('#tgl_pengiriman').val(),
                            no_pemindahan: $('#no_pemindahan').val(),
                            id_gudang_asal: $('#id_gudang_asal').val(),
                            id_gudang_tujuan: $('#id_gudang_tujuan').val(),
                            id_ekspedisi: $('#id_ekspedisi').val(),
                            no_resi: $('#no_resi').val(),
                            keterangan: $('#keterangan').val(),
                            csrf_token: token
                        },
                    })
                <?php } ?>
            }

        })

        $('.save-form').click(function() {
            $.ajax({
                method: 'POST',
                url: 'pengiriman_stok/add/temp/detail_barang_pengiriman_stok',
                dataType: 'JSON',
                data: {
                    id_barang: $('#id_barang').val(),
                    no_batch: $('#no_batch').val(),
                    qty: $('#qty').val(),
                    id_pengiriman_stok: $('#id_pengiriman_stok').val(),
                    id_detail_barang_pengiriman_stok: $('#id_detail_barang_pengiriman_stok').val(),
                    id_detail_barang_pengiriman_stok_temp: $('#id_detail_barang_pengiriman_stok_temp').val(),
                    id_detail_barang: $('option:selected', $('#no_batch')).attr('id_detail_barang_attr'),
                    csrf_token: token
                },
                success: function(resp) {
                    handleResponse(resp)
                }
            })
        })

        $(document).on('change', '.search_barang_diform', function() {
            // $('#no_batch').empty()
            $('#no_batch').children('option:not(:first)').remove();
            $('#exp_date').empty()
            const id_barang = $('#id_barang').val()
            const id_gudang = $('#id_gudang_asal').val()
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
                    console.log(resp);

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


        $(document).on('click', '.btn-edit-temp', function() {
            $('#main-modal .form-control').val(null)
            $('#main-modal').modal()
            var id = $(this).attr("data-id")
            $.ajax({
                method: 'POST',
                url: 'pengiriman_stok/get/detail_barang_pengiriman_stok_temp',
                dataType: 'JSON',
                data: {
                    id_detail_barang_pengiriman_stok_temp: id,
                    csrf_token: token
                },
                success: function(data) {
                    //////////////set value select2??////////////////////////////
                    // $('#search_barang_diform').select2('data', {id: data[0].id_barang, text: data[0].nama_barang});
                    $('#main-modal #no_batch').val(data[0].no_batch)
                    $('#main-modal #qty').val(data[0].qty)
                    $('#main-modal #id_detail_barang_pengiriman_stok_temp').val(data[0].id_detail_barang_pengiriman_stok_temp)
                }
            })
        })

        $(document).on('click', '.btn-edit', function() {
            $('#edit-modal .form-control').val(null)
            $('#edit-modal').modal()
            // $("#save-form").removeClass("save-form")
            var id = $(this).attr("data-id")
            $.ajax({
                method: 'POST',
                url: 'pengiriman_stok/get/detail_barang_pengiriman_stok',
                dataType: 'JSON',
                data: {
                    id_detail_barang_pengiriman_stok: id,
                    csrf_token: token
                },
                success: function(data) {
                    $('#edit-modal #nama_barang_edit').val(data[0].nama_barang)
                    $('#edit-modal #no_batch_edit').val(data[0].no_batch)
                    $('#edit-modal #qty_edit').val(data[0].qty)
                    $('#edit-modal #id_detail_barang_pengiriman_stok_edit').val(data[0].id_detail_barang_pengiriman_stok)
                    $('#edit-modal #id_detail_barang_edit').val(data[0].id_detail_barang)
                }
            })
        })

        $('.delete-temp-barang').click(function() {
            $.ajax({
                method: 'POST',
                url: 'pengiriman_stok/delete/detail_barang_pengiriman_stok_temp',
                dataType: 'JSON',
                data: {
                    id_detail_barang_pengiriman_stok_temp: $(this).attr("data-id"),
                    csrf_token: token
                },
                success: function(resp) {
                    handleResponse(resp)
                }
            })
        })

        $(document).on('keyup', '#qty', function() {
            $('#danger-alert').empty();
            var stock = parseInt($('option:selected', $('#no_batch')).attr('qty_attr'));
            var qty = parseInt($('#qty').val());

            if (qty > stock) {
                let x = `
                    <strong class="text-danger">Stock tidak cukup!</strong>
                        `;
                $('#danger-alert').append(x);
            }

        })

        $('#dest_temp').click(function() {
            $.ajax({
                method: 'POST',
                url: 'pengiriman_stok/delete/dest_temp',
                dataType: 'JSON',
                data: {
                    csrf_token: token
                },
                success: function(resp) {
                    handleResponse(resp)
                }
            })
        })

    })

    function goBack() {
        window.history.back();
    }

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>