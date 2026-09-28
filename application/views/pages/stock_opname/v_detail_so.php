<header class="page-header">
    <h2><i class="icons fas fa-truck-loading"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<div class="col-xl-8 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:10%">
        <div class="text-center mt-0">
            <h2>Perintah Stock Opname</h2>
            <h3><b>No SPK : <?= $stock_opname[0]->kode ?> </b></h3>
            <br>
        </div>
        <?= form_open('stock_opname/updateStok', array('id' => 'main-form', 'autocomplete' => 'off')); ?>

        <!-- value preview -->
        <div id="value_preview">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group  mb-2 pt-1">
                        <label class="col-form-label">Tanggal SPK <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                            <input disabled type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" id="tgl_pengiriman" value="<?= date('d-m-Y', strtotime($stock_opname[0]->created_at)) ?>" required data-plugin-datepicker>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2 pt-1">
                        <label class=" col-form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                            <input disabled type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" id="tgl_pengiriman" value="<?= date('d-m-Y', strtotime($stock_opname[0]->tanggal_mulai)) ?>" required data-plugin-datepicker>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Gudang <span class="text-danger">*</span></label>
                <input disabled class="form-control" type="text" value="<?= $stock_opname[0]->nama_gudang ?>" id="gudang_asal">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-2 pt-1">
                        <label class=" col-form-label">Pelaksana <span class="text-danger">*</span></label>
                        <input disabled class="form-control" type="text" value="<?= $stock_opname[0]->nama_pelaksana  ?>" id="gudang_asal">
                        <div id="danger-alert"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2 pt-1">
                        <label class=" col-form-label">Penanggung Jawab <span class="text-danger">*</span></label>
                        <input disabled class="form-control" type="text" value="<?= $stock_opname[0]->nama_pengaju ?>" id="gudang_tujuan">
                        <div id="danger-alert"></div>
                    </div>
                </div>
            </div>

            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Keterangan <span class="text-danger">*</span></label>
                <textarea disabled class="form-control" name="keterangan_pengiriman" id="keterangan_pengiriman" required placeholder="..."><?= $stock_opname[0]->keterangan  ?></textarea>
            </div>

            <input type="hidden" name="no_penerimaan_stok" id="no_penerimaan_stok">
        </div>
        <!-- end value preview -->
        <br>

        <br>
        <div class="text-right">
            <a href="stock_opname/print/1/<?= encrypt($stock_opname[0]->id_so) ?>" target="_blank"> Print Dokumen <i class="fas fa-print"></i></a>
            <br>

        </div>
        <div class="card-body">
            <div class="table-responsive">

                <?php if (!empty($stock_opname_detail)) { ?>
                    <table class="table table-striped table-sm table-bordered table-hover" id="table_detail">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th style="text-align: center;"> Nama Barang</th>
                                <th style="text-align: center;"> Stock Office </th>
                                <th style="text-align: center;" width="12%"> Stock Accurate </th>
                                <th style="text-align: center;" width="12%"> Stock Gudang </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1 ?>
                            <?php foreach ($stock_opname_detail as $row) { ?>
                                <tr class="value_row">
                                    <td style="text-align: center;"><input type="hidden" name="id_sodetail[]" value="<?= encrypt($row->id) ?>"><?= $i++ ?></td>
                                    <td><?= $row->nama_barang ?></td>
                                    <?php if (sessPenggunaId() == '15' || sessPenggunaId() == '769') { ?>
                                        <td style="text-align: center;"><input class="form-control" type="number" name="stok_office[]" value="<?= $row->stok_office ?>"> </td>
                                    <?php } else { ?>
                                        <td style="text-align: center;"> <?= $row->stok_office ?></td>
                                    <?php } ?>
                                    <td style="text-align: center;"><input class="form-control" type="number" name="stok_accurate[]" value="<?= $row->stok_accurate ?>"> </td>
                                    <td style="text-align: center;"><input class="form-control" type="number" name="stok_gudang[]" value="<?= $row->stok_gudang ?>"> </td>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>Stok Kosong</p>
                <?php } ?>
            </div>

            <div style="text-align: right;">
                <?php if ((sessPenggunaId() == $stock_opname[0]->id_pengaju || sessPenggunaId() == '7' || sessPenggunaId() == '1' || sessPenggunaId() == '769')) { ?>
                    <button type="button" class="btn btn-success btn-save">Simpan</button>
                <?php } ?>
            </div>

        </div>
        <div id="value_preview">
            <?php if ($stock_opname[0]->catatan != "") { ?>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Catatan PJT : <span class="text-danger">*</span></label>
                    <textarea disabled class="form-control" required placeholder="..."><?= $stock_opname[0]->catatan  ?></textarea>
                </div>
            <?php } ?>
            <?php if ((sessPenggunaId() == '7' || sessPenggunaId() == '1' || sessPenggunaId() == '769')) { ?>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Catatan PJT : <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="catatan" id="catatan" required placeholder="..."></textarea>
                </div>
            <?php } ?>
            <div class="form-group">
                <label for="invoice" class="form-control-label">File : </label>
                <?php if ($stock_opname[0]->link != "") { ?>
                    <a href="<?= $stock_opname[0]->link ?>">
                        Klik untuk cek
                    </a>
                <?php } ?>
                <?php if ((sessPenggunaId() == '7' || sessPenggunaId() == '1' || sessPenggunaId() == '769')) { ?>
                    <input type="text" class="form-control respon" id="link" name="link" value="<?= $stock_opname[0]->link ?>">
                <?php } ?>
            </div>

        </div>

        <br><br>
        <?php
        $img_path     = "uploads/file_karyawan/ttd/";
        $ttdPelaksana        = $img_path . "ttd_" . $stock_opname[0]->id_pelaksana . ".png";
        $ttd1         = $img_path . "ttd_notyet2.png";
        $ttd2         = $img_path . "ttd_notyet2.png";

        if ($stock_opname[0]->ttd_1 == '1') {
            $ttd1 = $img_path . "ttd_" . $stock_opname[0]->id_pengaju . ".png";
        } else if ($stock_opname[0]->ttd_1 == '2') {
            $ttd1 = $img_path . "ttd_not.png";
        }
        if ($stock_opname[0]->ttd_2 == '1') {
            $ttd2 = $img_path . "ttd_769.png";
        } else if ($stock_opname[0]->ttd_2 == '2') {
            $ttd2 = $img_path . "ttd_not.png";
        }
        ?>


        <div class="row">
            <div class="col-md-4">
                <div class="text-center mt-3">
                    Pelaksana,
                </div>
                <br>
                <div class="text-center">
                    <div class="text-center"> <?php echo '<img src="' . $ttdPelaksana . '" height="70">'; ?></div>
                    <span><?= $stock_opname[0]->nama_pelaksana ?></span>
                    <hr style="border-top: 1px solid;margin:auto">
                    <div class="text-center"> <?= $stock_opname[0]->jabatan_pelaksana ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center mt-3">
                    Penanggung Jawab,
                </div>
                <br>
                <div class="text-center">
                    <div class="text-center"> <?php echo '<img src="' . $ttd1 . '" height="70">'; ?></div>
                    <span><?= $stock_opname[0]->nama_pengaju ?></span>
                    <hr style="border-top: 1px solid;margin:auto">
                    <div class="text-center"> <?= $stock_opname[0]->jabatan_pengaju ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center mt-3">
                    Penanggung Jawab Teknis,
                </div>
                <br>
                <div class="text-center">
                    <div class="text-center"> <?php echo '<img src="' . $ttd2 . '" height="70">'; ?></div>
                    <span>Fitri Andriani</span>
                    <hr style="border-top: 1px solid;margin:auto">
                    <div class="text-center"> Penanggung Jawab Teknis</div>
                </div>
            </div>
        </div>



        <div class="modal-footer">
            <?php
            $ttd = "ttd_1";
            if ((sessPenggunaId() == $stock_opname[0]->id_pengaju)) {
                $ttd = 'ttd_1';
            } else if ((sessPenggunaId() == '769')) {
                $ttd = 'ttd_2';
            }
            ?>
            <input type="hidden" id="level_ttd" value="<?= $ttd ?>">
            <?php $id = $stock_opname[0]->id_so ?>
            <input type="hidden" name="id" id="id" value="<?= $stock_opname[0]->id_so ?>">



            <?php if ((sessPenggunaId() == $stock_opname[0]->id_pengaju || sessPenggunaId() == '1' || sessPenggunaId() == '769')) { ?>
                <button type="button" class="btn btn-secondary float-right btn-denial" id-Sijk="<?= encrypt($stock_opname[0]->id_so) ?>"> <i class="fas fa-times"></i> Tolak </button>
            <?php } ?>
            <?php if ((sessPenggunaId() == $stock_opname[0]->id_pengaju || sessPenggunaId() == '1' || sessPenggunaId() == '7' || sessPenggunaId() == '769')) { ?>
                <button type="button" class="btn btn-success float-right btn-approval" id-Sijk="<?= encrypt($stock_opname[0]->id_so) ?>"> <i class="fas fa-check"></i> Setujui </button>
            <?php } ?>
            <?php if ((sessPenggunaId() == '7' || sessPenggunaId() == '1' || sessPenggunaId() == '769')) { ?>
                <button type="button" class="btn btn-primary float-right btn-submit" id-Sijk="<?= encrypt($stock_opname[0]->id_so) ?>"> <i class="fas fa-check"></i> Submit </button>
            <?php } ?>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Kembali</button>
            <!--<button type="button" class="btn btn-success btn-save">Simpan</button>-->

            <?php if ($stock_opname[0]->ttd_1 != "") { ?>
                <a href="stock_opname/print/2/<?= encrypt($stock_opname[0]->id_so) ?>" target="_blank" class="btn btn-warning float-right"> <i class="fas fa-print"></i> Cetak Hasil</a>

            <?php } ?>
        </div>

        <?= form_close(); ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var level_ttd = $('#level_ttd').val();

        $(document).on('click', '.btn-approval', function() {
            var id = $('#id').val();

            Swal.fire({
                title: 'Setujui Permintaan Stock Opname?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'stock_opname/ttd_setujui/' + level_ttd,
                        dataType: 'JSON',
                        data: {
                            id: id,
                            csrf_token: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })

                }

            })
        })


        $(document).on('click', '.btn-denial', function() {
            var id = $('#id').val();

            Swal.fire({
                title: 'Tolak Permintaan Stock Opname?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'stock_opname/ttd_tolak/' + level_ttd,
                        dataType: 'JSON',
                        data: {
                            id: id,
                            csrf_token: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })

                }

            })
        })


        $(document).on('click', '.btn-submit', function() {
            var catatan = $('#catatan').val();
            var link = $('#link').val();
            var id = $('#id').val();

            Swal.fire({
                title: 'Data telah tepat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'stock_opname/submitCatatan/' + id,
                        dataType: 'JSON',
                        data: {
                            catatan: catatan,
                            link: link,
                            csrf_token: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })
                }
            })
        })

    })


    function goBack() {
        window.history.back();
    }
</script>