<header class="page-header">
    <h2><i class="icons fas fa-file-invoice-dollar"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<div class="col-xl-8 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:10%">
        <div class="text-center mt-0">
            <h2>Form Invoice</h2>
        </div>
        <?= form_open('invoice/add', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Customer <span class="text-danger">*</span></label>
                    <input readonly class="form-control" type="text" id="id_customer" value="<?= $nama_customer[0]->nama_customer ?>" required>
                    <input name="id_customer" type="hidden" value="<?= encrypt($nama_customer[0]->id_customer) ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group  mb-2 pt-1">
                    <label class="col-form-label">Tanggal Invoice <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tgl_invoice" id="tgl_invoice" value="" required data-plugin-datepicker>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Expedisi <span class="text-danger">*</span></label>
                    <select class="form-control" name="id_ekspedisi" id="id_ekspedisi" required>
                        <option value="">...</option>
                        <?php if ($invoice) { ?>
                            <?php foreach ($ekspedisi as $e) { ?>
                                <option <?= $invoice[0]->id_ekspedisi == encrypt($e->id_ekspedisi) ? "selected" : NULL ?> value="<?= encrypt($e->id_ekspedisi) ?>"><?= $e->nama_ekspedisi ?></option>
                            <?php } ?>
                        <?php } else if ($temp_data) { ?>
                            <?php foreach ($ekspedisi as $e) { ?>
                                <option <?= encrypt($temp_data[0]->id_ekspedisi) == encrypt($e->id_ekspedisi) ? "selected" : NULL ?> value="<?= encrypt($e->id_ekspedisi) ?>"><?= $e->nama_ekspedisi ?></option>
                            <?php } ?>
                        <?php } else { ?>
                            <?php foreach ($ekspedisi as $e) { ?>
                                <option value="<?= encrypt($e->id_ekspedisi) ?>"><?= $e->nama_ekspedisi ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div> -->
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Invoice <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="no_invoice" id="no_invoice" value="" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Syarat Pembayaran <span class="text-danger">*</span></label>
                    <select class="form-control" name="id_syarat_pembayaran" id="id_syarat_pembayaran">
                        <option value="">...</option>
                        <?php foreach ($syarat_pembayaran as $sp) { ?>
                            <option value="<?= encrypt($sp->id_syarat_pembayaran) ?>"><?= $sp->nama_syarat_pembayaran ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Marketing <span class="text-danger">*</span></label>
                    <select class="form-control" name="id_marketing" id="id_marketing">
                        <option value="">...</option>
                        <?php foreach ($marketing as $m) { ?>
                            <option value="<?= encrypt($m->pengguna_id) ?>"><?= $m->nama ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Purchase Order (PO) <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="no_po" id="no_po" value="<?= $no_po ?>" required>
                </div>
            </div>
        </div>
        <br>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Nama Barang</th>
                            <th> Kode Barang</th>
                            <th> Kuantitasi </th>
                            <th> Harga Satuan </th>
                            <th> Persentase Discount (%)</th>
                            <th> Discount </th>
                            <th> Harga Total </th>
                        </tr>
                    </thead>
                    <tbody id="detail-barang">
                        <?php $i = 1 ?>
                        <?php $x = 1 ?>
                        <!-- menampilkan detail barang keluar yg asli -->
                        <?php foreach ($detail_barang as $row) { ?>
                            <tr>
                                <!-- id_pengeluaran_barang berfungsi untuk link detail barang invoice ke pengeluran barang yg terhubung -->
                                <td> <input type="hidden" name="id_detail_barang_keluar[]" value="<?= encrypt($row->id_detail_barang_keluar) ?>"> <input type="hidden" name="id_pengeluaran_barang[]" value="<?= encrypt($row->id_pengeluaran_barang) ?>"> <input type="hidden" value="<?= $row->id_detail_barang_invoice ?? NULL ?>"><?= $i++ ?></td>
                                <td> <input type="hidden" value="<?= encrypt($row->id_barang) ?>" name="id_barang[]"><?= $row->nama_barang ?> </td>
                                <td> <input type="hidden"><?= $row->kode_barang ?> </td>
                                <td> <input type="text" class="form-control form-control-sm qty" id="qty<?= $x ?>" value="<?= $row->qty ?>" readonly name="qty[]"></td>
                                <td> <input type="text" class="form-control form-control-sm rupiah harga" id="harga<?= $x ?>" value="" name="harga[]"> </td>
                                <td> <input type="text" class="form-control form-control-sm persentase_discount" id="persentase_discount<?= $x ?>"> </td>
                                <td> <input type="text" class="form-control form-control-sm rupiah discount" id="discount<?= $x ?>" value="" name="discount[]"> </td>
                                <td> <input type="text" class="form-control form-control-sm rupiah tot-harga harga_total" id="harga_total<?= $x++ ?>" readonly name="harga_total[]"></td>
                                <!-- fuadi -->
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1 text-right">
                    <label class=" col-form-label"><strong>Sub Total </strong></label>
                    <input class="form-control form-control-sm text-right" readonly type="text" name="sub_total" id="sub_total" value="" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Tarif Pajak <span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm" name="id_tarif_pajak" id="id_tarif_pajak">
                        <option value="">...</option>
                        <?php if (isset($invoice)) { ?>
                            <?php foreach ($pajak as $p) { ?>
                                <option <?= $invoice[0]->id_tarif_pajak == encrypt($p->id_tarif_pajak) ? "selected" : NULL ?> value="<?= encrypt($p->id_tarif_pajak) ?>" persentase="<?= $p->persentase ?>"><?= $p->nama_pajak . ' ' . $p->persentase . '%'  ?></option>
                            <?php } ?>
                        <?php } else if ($temp_data) { ?>
                            <?php foreach ($pajak as $p) { ?>
                                <option <?= encrypt($temp_data[0]->id_tarif_pajak) == encrypt($p->id_tarif_pajak) ? "selected" : NULL ?> value="<?= encrypt($p->id_tarif_pajak) ?>" persentase="<?= $p->persentase ?>"><?= $p->nama_pajak . ' ' . $p->persentase . '%'  ?></option>
                            <?php } ?>
                        <?php } else { ?>
                            <?php foreach ($pajak as $p) { ?>
                                <option value="<?= encrypt($p->id_tarif_pajak) ?>" persentase="<?= $p->persentase ?>"><?= $p->nama_pajak . ' ' . $p->persentase . '%'  ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label"> <strong>Total (dengan pajak)</strong></label>
                    <input class="form-control form-control-sm text-right" readonly type="text" name="total" id="total" value="" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Ongkir <span class="text-danger">*</span></label>
                    <small class="text-danger">isi dengan "0", jika kosong</small>
                    <?php if (isset($invoice)) { ?>
                        <input class="form-control rupiah form-control-sm" type="text" name="ongkir" id="ongkir" value="<?= $invoice[0]->ongkir ?>" required>
                    <?php } else { ?>
                        <input class="form-control rupiah form-control-sm" type="text" name="ongkir" id="ongkir" value="" required>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label"><strong>Total Keseluruhan</strong></label>
                    <input class="form-control form-control-sm text-right" readonly type="text" name="total_keseluruhan" id="total_keseluruhan" value="" required>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <!-- penanda bahwa ini berasal dari form invoice by pengeluaran barang -->
            <input type="hidden" name="from_pengeluaran_barang" value="<?= $id_pengeluaran_barang ?>">
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
        $('.rupiah').mask('000.000.000.000', {
            reverse: true
        });

        $(document).on('keyup', '.persentase_discount', function() {
            ///perhitungan diskon
            ////////////////////
            var object2 = $(".persentase_discount")
            for (let a = 1; a <= object2.length; a++) {
                let persentase = $(`#persentase_discount${a}`).val()
                let harga = $(`#harga${a}`).val().replaceAll('.', '')
                let discount = harga * persentase / 100
                $(`#discount${a}`).val(discount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
            }
        })

        $(document).on('keyup', '.harga, .qty, .discount, .persentase_discount', function() {
            //cari harga total
            ///////////////
            var object = $(".harga");
            for (let i = 1; i <= object.length; i++) {
                let harga = $(`#harga${i}`).val().replaceAll('.', '')
                let qty = $(`#qty${i}`).val()
                let discount = $(`#discount${i}`).val().replaceAll('.', '')
                let harga_total = (harga - discount) * qty
                $(`#harga_total${i}`).val(harga_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
            }

            //get sub total
            ///////////////
            x = []
            var $j_object = $(".tot-harga");
            $j_object.each(function(i) {
                x.push($j_object.eq(i).val().replaceAll('.', ''));

            });
            sum = 0;
            $.each(x, function() {
                sum += parseFloat(this) || 0;
            });
            $('#sub_total').val(sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        })

        //get total (dengan pajak)
        /////////////////////////
        let pajak = $('#sub_total').val().replaceAll('.', '') * $('option:selected', $('#id_tarif_pajak')).attr('persentase') / 100
        let total = parseInt($('#sub_total').val().replaceAll('.', '')) + pajak
        $('#total').val(total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));

        $(document).on('change keyup', '#id_tarif_pajak, .harga', function() {
            let pajak = $('#sub_total').val().replaceAll('.', '') * $('option:selected', $('#id_tarif_pajak')).attr('persentase') / 100
            let total = parseInt($('#sub_total').val().replaceAll('.', '')) + pajak
            $('#total').val(total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        })
        //end get total
        ///////////////

        //get total keseluruhan
        ///////////////////////
        let t = $('#total').val().replaceAll('.', '')
        let o = $('#ongkir').val().replaceAll('.', '')
        let total_keseluruhan = parseInt(t) + parseInt(o)
        $('#total_keseluruhan').val(total_keseluruhan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));

        $(document).on('keyup change', '#ongkir, .harga, #id_tarif_pajak', function() {
            let total = $('#total').val().replaceAll('.', '')
            let ongkir = $('#ongkir').val().replaceAll('.', '')
            let total_keseluruhan = parseInt(total) + parseInt(ongkir)
            $('#total_keseluruhan').val(total_keseluruhan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        })


        $(document).on('keyup', '#harga_edit,#qty_edit', function() {
            let harga = $('#harga_edit').val().replaceAll('.', '')
            let qty = $('#qty_edit').val()
            let harga_total = harga * qty
            $('#harga_total_edit').val(harga_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        })

        $(document).on('change', '#no_batch', function() {
            var option = $('option:selected', this).attr('exp_attr');
            $('#exp_date').val(option);
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

        $(document).on('click', '.btn-clear-form', function() {

            $('#main-modal .form-control').val(null)
            $('.search_barang_diform').val(null)
        })
    })

    function goBack() {
        window.history.back();
    }

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>