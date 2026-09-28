<header class="page-header">
    <h2><i class="icons fas fa-calculator"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<div class="col-xl-8 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:10%">
        <div class="text-center mt-0">
            <h2>Form Kalkulasi Cicilan</h2>
        </div>
        <?= form_open('kalkulasi_cicilan/print', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Customer <span class="text-danger">*</span></label>
                    <select data-plugin-selectTwo class="form-control search_customer filter-grup" name="id_customer" id="id_customer"></select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group  mb-2 pt-1">
                    <label class="col-form-label">Tanggal Penawaran <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tgl_penawaran" id="tgl_penawaran" value="" required data-plugin-datepicker>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mb-2 pt-1">
            <label class=" col-form-label">No Penawaran <span class="text-danger">*</span></label>
            <input class="form-control" type="text" name="no_penawaran" id="no_penawaran" value="" required>
        </div>
        <!-- <div class="form-group mb-2 pt-1">
            <label class=" col-form-label">Keterangan <span class="text-danger">*</span></label>
            <textarea class="form-control" name="keterangan" id="keterangan" required placeholder="..."></textarea>
        </div> -->
        <br>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Harga Awal (Rp) <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="harga_awal" id="harga_awal" value="" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Opsi<span class="text-danger">*</span></label>
                    <select class="form-control" name="dp" id="dp" required>
                        <option value="0">0%</option>
                        <option value="30">30%</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Nilai DP </label>
                    <input class="form-control" type="text" name="nilai_dp" id="nilai_dp" value=""  required>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered table-hover text-center" id="kt_table_1">
                <thead>
                    <tr>
                        <th> Tenor</th>
                        <th> Nilai Cicilan Perbulan </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>12 Bulan <input type="hidden" name="bulan_12" id="bulan_12"></td>
                        <td class="tb-result" id="a"></td>
                    </tr>
                    <tr>
                        <td>24 Bulan <input type="hidden" name="bulan_24" id="bulan_24"></td>
                        <td class="tb-result" id="b"></td>
                    </tr>
                    <tr>
                        <td>36 Bulan <input type="hidden" name="bulan_36" id="bulan_36"></td>
                        <td class="tb-result" id="c"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <div class="form-group">
            <!-- <a href=""><i class="fas fa-print"></i> Print Dokumen</a> -->
        </div>
        <div class="modal-footer">
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Kembali</button>
            <button type="submit" class="btn btn-success">Print <i class="fas fa-print"></i></button>
        </div>
        <?= form_close(); ?>
    </div>
</div>

<script>
     
    document.addEventListener('DOMContentLoaded', function() {
        $('#harga_awal').mask('000.000.000.000', {
            reverse: true
        });

        $(".search_customer").themePluginSelect2({
            placeholder: "--- Ketik Nama Customer ---",
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

        $(document).on('keyup', '#harga_awal', function() {

            $('.tb-result').empty();
            $.ajax({
                method: 'POST',
                url: 'kalkulasi_cicilan/calculate',
                dataType: 'JSON',
                data: {
                    harga_awal: $('#harga_awal').val(),
                    dp: $('#dp').val(),
                    csrf_token: token
                },
                success: function(resp) {
                    
                    $('#a').html(resp['12_bulan']);
                    $('#b').html(resp['24_bulan']);
                    $('#c').html(resp['36_bulan']);
                    $('#bulan_12').val(resp['12_bulan']);
                    $('#bulan_24').val(resp['24_bulan']);
                    $('#bulan_36').val(resp['36_bulan']);
                    $('#nilai_dp').val(resp['nilai_dp']);
                }
            })

        })

        $('#dp').change(function() {
            $('.tb-result').empty();
            $.ajax({
                method: 'POST',
                url: 'kalkulasi_cicilan/calculate',
                dataType: 'JSON',
                data: {
                    harga_awal: $('#harga_awal').val(),
                    dp: $('#dp').val(),
                    csrf_token: token
                },
                success: function(resp) {
                    $('#a').html(resp['12_bulan']);
                    $('#b').html(resp['24_bulan']);
                    $('#c').html(resp['36_bulan']);
                    $('#bulan_12').val(resp['12_bulan']);
                    $('#bulan_24').val(resp['24_bulan']);
                    $('#bulan_36').val(resp['36_bulan']);
                    $('#nilai_dp').val(resp['nilai_dp']);
                }
            })
        })
    })

    function goBack() {
        window.history.back();
    }
</script>