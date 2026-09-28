<header class="page-header">
    <h2><i class="icons fas fa-people-carry"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<div class="col-xl-8 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:10%">
        <div class="text-center mt-0">
            <h2>Form Penerimaan Barang</h2>
        </div>
        <?php if ($this->uri->segment(2) == 'edit') { ?>
            <?= form_open('penerimaan_barang/update', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <?php } else { ?>
            <?= form_open('penerimaan_barang/add', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <?php } ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Pemasok <span class="text-danger">*</span></label>
                    <select class="form-control" name="id_pemasok" id="id_pemasok" required>
                        <option value="">...</option>
                        <!-- jika di akses dari form edit -->
                        <?php if ($penerimaan_barang) { ?>
                            <?php foreach ($pemasok_utama as $pu) { ?>
                                <option <?= $penerimaan_barang[0]->id_pemasok == encrypt($pu->id_pemasok) ? "selected" : NULL ?> value="<?= encrypt($pu->id_pemasok) ?>"><?= $pu->nama_pemasok ?></option>
                            <?php } ?>
                            <!-- jika di akses dari form yg berisi temp data -->
                        <?php } else if ($temp_data) { ?>
                            <?php foreach ($pemasok_utama as $pu) { ?>
                                <option <?= $temp_data[0]->id_pemasok == encrypt($pu->id_pemasok) ? "selected" : NULL ?> value="<?= encrypt($pu->id_pemasok) ?>"><?= $pu->nama_pemasok ?></option>
                            <?php } ?>
                            <!-- jika form masih kosong -->
                        <?php } else { ?>
                            <?php foreach ($pemasok_utama as $pu) { ?>
                                <option value="<?= encrypt($pu->id_pemasok) ?>"><?= $pu->nama_pemasok ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group  mb-2 pt-1">
                    <label class="col-form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <!-- jika di akses dari form edit -->
                        <?php if (isset($penerimaan_barang)) { ?>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tgl_masuk" id="tgl_masuk" value="<?= $penerimaan_barang[0]->tgl_masuk ?? NULL ?>" required data-plugin-datepicker>
                            <!-- jika di akses dari form yg berisi temp data -->
                        <?php } else { ?>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tgl_masuk" id="tgl_masuk" value="<?= $temp_data[0]->tgl_masuk ?? NULL ?>" required data-plugin-datepicker>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Terima <span class="text-danger">*</span></label>
                    <!-- jika di akses dari form edit -->
                    <?php if (isset($penerimaan_barang)) { ?>
                        <input class="form-control" type="text" name="no_terima" id="no_terima" value="<?= $penerimaan_barang[0]->no_terima ?? NULL ?>" required>
                        <!-- jika di akses dari form yg berisi temp data -->
                    <?php } else { ?>
                        <input class="form-control" type="text" name="no_terima" id="no_terima" value="<?= $temp_data[0]->no_terima ?? NULL ?>" required>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Purchase Order (PO) <span class="text-danger">*</span></label>
                    <!-- jika di akses dari form edit -->
                    <?php if (isset($penerimaan_barang)) { ?>
                        <input class="form-control" type="text" name="no_po" id="no_po" value="<?= $penerimaan_barang[0]->no_po ?? NULL ?>" required>
                        <!-- jika di akses dari form yg berisi temp data -->
                    <?php } else { ?>
                        <input class="form-control" type="text" name="no_po" id="no_po" value="<?= $temp_data[0]->no_po ?? NULL ?>" required>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="form-group mb-2 pt-1">
            <label class="col-form-label">Gudang <span class="text-danger">*</span></label>
            <select class="form-control" name="id_gudang" id="id_gudang" required <?= $this->uri->segment(2) == 'edit' ? 'disabled' : '' ?>>
                <option value="">...</option>
                <?php if ($penerimaan_barang) { ?>
                    <?php foreach ($gudang as $g) { ?>
                        <option <?= $penerimaan_barang[0]->id_gudang == encrypt($g->id_gudang) ? "selected" : NULL ?> value="<?= encrypt($g->id_gudang) ?>"><?= $g->nama_gudang ?></option>
                    <?php } ?>
                <?php } else if ($temp_data) { ?>
                    <?php foreach ($gudang as $g) { ?>
                        <option <?= $temp_data[0]->id_gudang == encrypt($g->id_gudang) ? "selected" : NULL ?> value="<?= encrypt($g->id_gudang) ?>"><?= $g->nama_gudang ?></option>
                    <?php } ?>
                <?php } else { ?>
                    <?php foreach ($gudang as $g) { ?>
                        <option value="<?= encrypt($g->id_gudang) ?>"><?= $g->nama_gudang ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="form-group mb-2 pt-1">
            <label class=" col-form-label">Keterangan <span class="text-danger">*</span></label>
            <?php if (isset($penerimaan_barang)) { ?>
                <textarea class="form-control" name="keterangan" id="keterangan" required placeholder="..."><?= $penerimaan_barang[0]->keterangan ?? NULL ?></textarea>
            <?php } else { ?>
                <textarea class="form-control" name="keterangan" id="keterangan" required placeholder="..."><?= $temp_data[0]->keterangan ?? NULL ?></textarea>
            <?php } ?>
        </div>
        <br>
        <div class="form-group mb-2 pt-1">
            <div class="row">
                <div class="col-md-6">
                    <?php if (isAdmin() || isStafAdmin()) { ?>
                        <div class="col-form-label">
                            <a href="javascript:;" id="btn-pilih-barang-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Barang</a>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-6 text-right mt-4">
                    <?php if ($this->uri->segment(2) == "edit") { ?>
                        <a href="penerimaan_barang/print/<?= $penerimaan_barang[0]->id_penerimaan_barang ?>"><i class="fas fa-print"></i> Print Dokumen</a>
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
                            <th> Kuantitasi </th>
                            <th> No AKL </th>
                            <th> Fisik </th>
                            <th> No Batch </th>
                            <th> Exp Date </th>
                            <?php if (isStafAdmin() || isAdmin()) { ?>
                                <th> Aksi </th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 ?>
                        <!-- menampilkan detail barang yg asli -->
                        <?php if (isset($detail_barang)) { ?>
                            <?php foreach ($detail_barang as $row) { ?>
                                <tr>
                                    <td> <input type="hidden" value="<?= $row->id_detail_barang ?? NULL ?>"><?= $i++ ?></td>
                                    <td> <input type="hidden" value="<?= $row->id_barang ?>"><?= $row->nama_barang ?> </td>
                                    <td> <input type="hidden" value="<?= $row->qty ?>"> <?= $row->qty ?> &nbsp; (Tersisa : <?= $row->current_stock ?>) </td>
                                    <td> <input type="hidden" value="<?= $row->nie ?>"> <?= $row->nie ?></td>
                                    <td> <input type="hidden" value="<?= $row->fisik ?>"> 
                                        <?php
                                            if($row->fisik == 1){
                                                echo "Baik";
                                            }else if($row->fisik == 2){
                                                echo "Tidak Baik";
                                            }else{
                                                echo "-";
                                            }
                                        ?>
                                    </td>
                                    <td> <input type="hidden" value="<?= $row->no_batch ?>"> <?= $row->no_batch ?></td>
                                    <td> <input type="hidden" value="<?= $row->exp_date ?? NULL ?>"> <?= date('Y', strtotime($row->exp_date))<2000 ? '-' : date('d-m-Y', strtotime($row->exp_date)) ?></td>
                                    <?php if (isStafAdmin() || isAdmin()) { ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="<?= $row->id_detail_barang ?>"><i class="bx bx-pencil"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="<?= $row->id_detail_barang ?>" data-object="penerimaan_barang/delete/detail_barang/" .<?= $row->id_detail_barang ?>><i class="bx bx-trash"></i></button>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            <!-- jika ada data temp baru saat edit detail barang yg asli -->
                            <?php if (isStafAdmin() || isAdmin()) { ?>
                                <?php if (isset($new_detail_barang_temp)) { ?>
                                    <?php foreach ($new_detail_barang_temp as $row) { ?>
                                        <tr>
                                            <td> <input type="hidden" name="id_detail_barang" value="<?= $row->id_detail_barang ?? NULL ?>"><?= $i++ ?></td>
                                            <td> <input type="hidden" name="id_barang[]" value="<?= $row->id_barang ?>"><?= $row->nama_barang ?> <span class="text-warning">(Temp)</span></td>
                                            <td> <input type="hidden" name="qty[]" value="<?= $row->qty ?>"> <?= $row->qty ?></td>
                                            <td> <input type="hidden" name="nie[]" value="<?= $row->nie ?>"> <?= $row->nie ?></td>
                                            <td> <input type="hidden" name="fisik[]" value="<?= $row->fisik ?>"> 
                                                <?php 
                                                    if($row->fisik == 1){
                                                        echo "Baik";
                                                    }else if($row->fisik == 2){
                                                        echo "Tidak Baik";
                                                    }else{
                                                        echo "-";
                                                    }
                                                ?>
                                            </td>
                                            <td> <input type="hidden" name="no_batch[]" value="<?= $row->no_batch ?>"> <?= $row->no_batch ?></td>
                                            <td> <input type="hidden" name="exp_date[]" value="<?= $row->exp_date ?? NULL ?>"> <?= $row->exp_date == '0000-00-00' ? '-' : date('d-m-Y', strtotime($row->exp_date)) ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary btn-edit-temp" data-id="<?= $row->id_detail_barang_temp ?>"><i class="bx bx-pencil"></i></button>
                                                <button type="button" class="btn btn-sm btn-danger delete-temp-barang" data-id="<?= $row->id_detail_barang_temp ?>"><i class="bx bx-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <!-- tampilkan jika ada detail barang temp -->
                        <?php } else if (isset($detail_barang_temp)) { ?>
                            <?php foreach ($detail_barang_temp as $row) { ?>
                                <tr>
                                    <td> <?= $i++ ?></td>
                                    <td> <input type="hidden" name="id_barang[]" value="<?= $row->id_barang ?>"><?= $row->nama_barang ?> </td>
                                    <td> <input type="hidden" name="qty[]" value="<?= $row->qty ?>"> <?= $row->qty ?></td>
                                    <td> <input type="hidden" name="nie[]" value="<?= $row->nie ?>"> <?= $row->nie ?></td>
                                    <td> <input type="hidden" name="fisik[]" value="<?= $row->fisik ?>"> 
                                        <?php 
                                            if($row->fisik == 1){
                                                echo "Baik";
                                            }else if($row->fisik == 2){
                                                echo "Tidak Baik";
                                            }else{
                                                echo "-";
                                            }
                                        ?>
                                    </td>
                                    <td> <input type="hidden" name="no_batch[]" value="<?= $row->no_batch ?>"> <?= $row->no_batch ?></td>
                                    <td> <input type="hidden" name="exp_date[]" value="<?= $row->exp_date ?>"> <?= $row->exp_date == '0000-00-00' ? '-' : date('d-m-Y', strtotime($row->exp_date)) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-edit-temp" data-id="<?= $row->id_detail_barang_temp ?>"><i class="bx bx-pencil"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger delete-temp-barang" data-id="<?= $row->id_detail_barang_temp ?>"><i class="bx bx-trash"></i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="id_penerimaan_barang" name="id_penerimaan_barang" value="<?= isset($penerimaan_barang[0]->id_penerimaan_barang) ?  $penerimaan_barang[0]->id_penerimaan_barang : NULL ?>">
            <?php if (isStafAdmin() || isAdmin()) { ?>
                <button type="button" class="btn btn-primary" id="dest_temp">Dest Temp</button>
            <?php } ?>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Kembali</button>
            <?php if (isStafAdmin() || isAdmin()) { ?>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            <?php } ?>
        </div>

        <?= form_close(); ?>
    </div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Form Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="form-group mb-2 pt-1">
                    <div class="col-form-label">
                        <label>Pilih Barang / &nbsp;<i class="fas fa-barcode"> &nbsp;</i>Scan Barcode </label>
                        <select data-plugin-selectTwo="search_barang" class="form-control search_barang_diform filter-grup" name="id_barang[]" id="id_barang"></select>
                    </div>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No AKL <span class="text-danger"></span></label>
                    <input class="form-control" type="text" name="nie[]" id="nie" >
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Kondisi Fisik <span class="text-danger"></span></label>
                    <select class="form-control" name="fisik[]" id="fisik" >
                        <option value="">- Pilih Kondisi Fisik -</option>
                        <option value="1">Baik</option>
                        <option value="2">Tidak Baik / Rusak</option>
                    </select>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Batch <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="no_batch[]" id="no_batch" value="" required>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Kuantitas <span class="text-danger">*</span></label>
                    <input class="form-control" type="number" name="qty[]" id="qty" value="" required>
                </div>
                <div class="form-group  mb-2 pt-1">
                    <label class="col-form-label">Expired Date <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="exp_date[]" id="exp_date" value="" required data-plugin-datepicker>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="form-control" id="id_detail_barang_temp" name="id_detail_barang_temp[]">
                    <input type="hidden" class="form-control" id="id_detail_barang" name="id_detail_barang[]">
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
            <?= form_open('penerimaan_barang/update/detail_barang', array('id' => 'edit-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Nama Barang <span class="text-danger">*</span></label>
                    <input disabled class="form-control" type="text" id="nama_barang_edit" required>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">NIE <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="nie" id="nie_edit">
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Kondisi Fisik <span class="text-danger">*</span></label>
                    <select class="form-control" name="fisik" id="fisik_edit">
                        <option value="">- Pilih Kondisi Fisik -</option>
                        <option value="1">Baik</option>
                        <option value="2">Tidak Baik / Rusak</option>
                    </select>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Batch <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="no_batch" id="no_batch_edit" required>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Kuantitas <span class="text-danger">*</span></label>
                    <input class="form-control" type="number" name="qty" id="qty_edit" required>
                </div>
                <div class="form-group  mb-2 pt-1">
                    <label class="col-form-label">Expired Date <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="exp_date" id="exp_date_edit" value="" required data-plugin-datepicker>
                    </div>
                </div>
                <div class="modal-footer">
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
            if (!$('#id_pemasok').val() || !$('#tgl_masuk').val() || !$('#no_terima').val() || !$('#id_gudang').val() || !$('#keterangan').val()) {
                alert('form tidak boleh kosong!!!')
            } else {
                $('#main-modal .form-control').val(null)
                $('#main-modal').modal()
                <?php if ($this->uri->segment(2) == "show") { ?>
                    $.ajax({
                        method: 'POST',
                        url: 'penerimaan_barang/add/temp',
                        dataType: 'JSON',
                        data: {
                            id_pemasok: $('#id_pemasok').val(),
                            tgl_masuk: $('#tgl_masuk').val(),
                            no_terima: $('#no_terima').val(),
                            no_po: $('#no_po').val(),
                            id_gudang: $('#id_gudang').val(),
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
                url: 'penerimaan_barang/add/temp/detail_barang',
                dataType: 'JSON',
                data: {
                    id_barang: $('#id_barang').val(),
                    exp_date: $('#exp_date').val(),
                    no_batch: $('#no_batch').val(),
                    qty: $('#qty').val(),
                    nie: $('#nie').val(),
                    fisik: $('#fisik').val(),
                    id_penerimaan_barang: $('#id_penerimaan_barang').val(),
                    id_detail_barang_temp: $('#id_detail_barang_temp').val(),
                    id_detail_barang: $('#id_detail_barang').val(),
                    csrf_token: token
                },
                success: function(resp) {
                    handleResponse(resp)
                }
            })
        })

        // $(document).on('click', '.save-edit-form', function() {

        //     console.log('save-edit-form');

        // })


        $(document).on('click', '.btn-edit-temp', function() {
            $('#main-modal .form-control').val(null)
            $('#main-modal').modal()
            var id = $(this).attr("data-id")
            $.ajax({
                method: 'POST',
                url: 'penerimaan_barang/get/detail_barang_temp',
                dataType: 'JSON',
                data: {
                    id_detail_barang_temp: id,
                    csrf_token: token
                },
                success: function(data) {
                    //////////////set value select2??////////////////////////////
                    // $('#search_barang_diform').select2('data', {id: data[0].id_barang, text: data[0].nama_barang});
                    $('#main-modal #no_batch').val(data[0].no_batch)
                    $('#main-modal #qty').val(data[0].qty)
                    $('#main-modal #nie').val(data[0].nie)
                    $('#main-modal #fisik option[value="' +data[0].fisik+ '"]').prop("selected", true).trigger('change')
                    $('#main-modal #exp_date').val(data[0].exp_date)
                    $('#main-modal #id_detail_barang_temp').val(data[0].id_detail_barang_temp)
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
                url: 'penerimaan_barang/get/detail_barang',
                dataType: 'JSON',
                data: {
                    id_detail_barang: id,
                    csrf_token: token
                },
                success: function(data) {
                    $('#edit-modal #nama_barang_edit').val(data[0].nama_barang)
                    $('#edit-modal #no_batch_edit').val(data[0].no_batch)
                    $('#edit-modal #qty_edit').val(data[0].qty)
                    $('#edit-modal #nie_edit').val(data[0].nie)
                    //$('#edit-modal #nie_edit').val("123456")
                    $('#edit-modal #fisik_edit option[value="' +data[0].fisik+ '"]').prop("selected", true).trigger('change')
                    //$('#edit-modal #fisik_edit option[value="1"]').prop("selected", true).trigger('change')
                    $('#edit-modal #exp_date_edit').val(data[0].exp_date)
                    $('#edit-modal #id_detail_barang_edit').val(data[0].id_detail_barang)
                }
            })
        })

        $('.delete-temp-barang').click(function() {
            $.ajax({
                method: 'POST',
                url: 'penerimaan_barang/delete/detail_barang_temp',
                dataType: 'JSON',
                data: {
                    id_detail_barang_temp: $(this).attr("data-id"),
                    csrf_token: token
                },
                success: function(resp) {
                    handleResponse(resp)
                }
            })
        })

        $('#dest_temp').click(function() {
            $.ajax({
                method: 'POST',
                url: 'penerimaan_barang/delete/dest_temp',
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