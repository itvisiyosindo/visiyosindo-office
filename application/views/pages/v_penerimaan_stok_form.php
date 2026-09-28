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
            <h2>Form Penerimaan Pemindahan Stok</h2>
        </div>
        <?php if ($this->uri->segment(2) == 'edit') { ?>
            <?= form_open('penerimaan_stok/update', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <?php } else { ?>
            <?= form_open('penerimaan_stok/add', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <?php } ?>
        <div class="form-group mb-2 pt-1">
            <label class="col-form-label">No Pemindahan <span class="text-danger">*</span></label>
            <?php if ($this->uri->segment(2) == 'edit') { ?>
                <input disabled class="form-control" type="text" value="<?= $pengiriman_stok[0]->no_pemindahan ?>">
            <?php } else { ?>
                <select class="form-control" name="id_pengiriman_stok" id="id_pengiriman_stok" required>
                    <option value="">...</option>
                    <?php foreach ($pengiriman_stok as $ps) { ?>
                        <option value="<?= encrypt($ps->id_pengiriman_stok) ?>"><?= $ps->no_pemindahan ?> &ensp; || &ensp; Tgl Pengiriman : <?= date_view_format($ps->tgl_pengiriman) ?></option>
                    <?php } ?>
                </select>
            <?php } ?>

        </div>
        <!-- value preview -->
        <div id="value_preview">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group  mb-2 pt-1">
                        <label class="col-form-label">Tanggal Pengiriman <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                            <input disabled type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" id="tgl_pengiriman" value="<?= $this->uri->segment(2) == 'edit' ? $pengiriman_stok[0]->tgl_pengiriman : NULL ?>" required data-plugin-datepicker>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2 pt-1">
                        <label class=" col-form-label">Ekspedisi <span class="text-danger">*</span></label>
                        <input disabled class="form-control" type="text" id="nama_ekspedisi" value="<?= $this->uri->segment(2) == 'edit' ? $pengiriman_stok[0]->nama_ekspedisi : NULL ?>">
                        <div id="danger-alert"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-2 pt-1">
                        <label class=" col-form-label">Gudang Asal <span class="text-danger">*</span></label>
                        <input disabled class="form-control" type="text" value="<?= $this->uri->segment(2) == 'edit' ? $pengiriman_stok[0]->gudang_asal : NULL ?>" id="gudang_asal">
                        <div id="danger-alert"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2 pt-1">
                        <label class=" col-form-label">Gudang Tujuan <span class="text-danger">*</span></label>
                        <input disabled class="form-control" type="text" value="<?= $this->uri->segment(2) == 'edit' ? $pengiriman_stok[0]->gudang_tujuan : NULL ?>" id="gudang_tujuan">
                        <div id="danger-alert"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-2 pt-1">
                        <label class=" col-form-label">Status Pengiriman <span class="text-danger">*</span></label>
                        <input disabled class="form-control" type="text" id="status_pengiriman" value="<?= $this->uri->segment(2) == 'edit' ? $pengiriman_stok[0]->status_pengiriman : NULL ?>">
                        <div id="danger-alert"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2 pt-1">
                        <label class=" col-form-label">No Resi <span class="text-danger">*</span></label>
                        <input disabled class="form-control" value="<?= $this->uri->segment(2) == 'edit' ? $pengiriman_stok[0]->no_resi : NULL ?>" type="text" id="no_resi">
                    </div>
                </div>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Keterangan Pengiriman <span class="text-danger">*</span></label>
                <textarea disabled class="form-control" name="keterangan_pengiriman" id="keterangan_pengiriman" required placeholder="..."><?= $this->uri->segment(2) == 'edit' ? $pengiriman_stok[0]->keterangan : NULL ?></textarea>
            </div>
            <input type="hidden" name="no_penerimaan_stok" id="no_penerimaan_stok">
        </div>
        <!-- end value preview -->
        <br>
        <div class="form-group  mb-2 pt-1">
            <label class="col-form-label">Tanggal Penerimaan Stok <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tgl_penerimaan_stok" id="tgl_penerimaan_stok" value="<?= $this->uri->segment(2) == 'edit' ? date_view_format($penerimaan_stok[0]->tgl_penerimaan_stok) : NULL ?>" required data-plugin-datepicker>
            </div>
        </div>
        <div class="form-group mb-2 pt-1">
            <label class=" col-form-label">Keterangan Penerimaan <span class="text-danger">*</span></label>
            <textarea class="form-control" name="keterangan_penerimaan" id="keterangan_penerimaan" required placeholder="..."><?= $this->uri->segment(2) == 'edit' ? $penerimaan_stok[0]->keterangan : NULL ?></textarea>
        </div>
        <br>
        <div class="text-right">
            <?php if ($this->uri->segment(2) == "edit") { ?>
                <a href="penerimaan_stok/print/<?=encrypt($penerimaan_stok[0]->id_penerimaan_stok) ?>"> Print Dokumen <i class="fas fa-print"></i></a><br>
            <?php } ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="table_detail">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Nama Barang</th>
                            <th> No Batch </th>
                            <th> Exp Date </th>
                            <th> Kuantitas </th>
                            <!-- <th> aksi </th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($this->uri->segment(2) == 'edit') { ?>
                            <?php $i = 1 ?>
                            <?php foreach ($detail_barang_penerimaan_stok as $row) { ?>
                                <tr class="value_row">
                                    <td><input type="hidden" name="id_detail_barang_pengiriman_stok[]" value="<?= encrypt($row->id_detail_barang_pengiriman_stok) ?>"><input type="hidden" name="id_detail_barang_penerimaan_stok[]" value="<?= encrypt($row->id_detail_barang_penerimaan_stok) ?>"> <input type="hidden" name="id_detail_barang_lama[]" value="<?= encrypt($row->id_detail_barang_lama) ?>"><?= $i++ ?></td>
                                    <td><input type="hidden"><?= $row->nama_barang ?></td>
                                    <td><input type="hidden"> <?= $row->no_batch ?></td>
                                    <td><?= $row->exp_date <= '2000-01-01' ? '-' : date('Y-m-d', strtotime($row->exp_date)) ?></td>
                                    <td><input class="form-control" type="number" name="qty[]" value="<?= $row->qty ?>"> </td>
                                    <!-- <td>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="<?= encrypt($row->id_detail_barang_pengiriman_stok) ?>" data-object="penerimaan_stok/delete/detail_barang"><i class="bx bx-trash"></i></button>
                                    </td> -->
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <?php if ($this->uri->segment(2) == 'edit') { ?>
                <input type="hidden" class="form-control" name="id_penerimaan_stok" value="<?= encrypt($penerimaan_stok[0]->id_penerimaan_stok) ?>">
            <?php } ?>
            <input type="hidden" name="">
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Kembali</button>
            <button type="button" class="btn btn-success btn-save">Simpan</button>
        </div>

        <?= form_close(); ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(document).on('change', '#id_pengiriman_stok', function() {
            var id = $(this).val()
            $('.value_row').remove()
            $('#value_preview .form-control').val(null)
            $.ajax({
                method: 'POST',
                url: 'pengiriman_stok/get/resume_detail_barang',
                data: {
                    id_pengiriman_stok: id,
                    csrf_token: token
                },
                dataType: 'json',
                success: function(resp) {

                    $('#no_pemindahan').val(resp['pengiriman_stok'][0].no_pemindahan)
                    $('#tgl_pengiriman').val(resp['pengiriman_stok'][0].tgl_pengiriman)
                    $('#gudang_asal').val(resp['pengiriman_stok'][0].nama_gudang_asal)
                    $('#gudang_tujuan').val(resp['pengiriman_stok'][0].nama_gudang_tujuan)
                    $('#status_pengiriman').val(resp['pengiriman_stok'][0].status_pengiriman)
                    $('#nama_ekspedisi').val(resp['pengiriman_stok'][0].nama_ekspedisi)
                    $('#no_resi').val(resp['pengiriman_stok'][0].no_resi)
                    $('#keterangan_pengiriman').val(resp['pengiriman_stok'][0].keterangan)
                    $('#no_penerimaan_stok').val(resp['pengiriman_stok'][0].no_pemindahan)

                    i = 1
                    resp['detail_barang'].forEach(data => {

                        let x = `
                                <tr class="value_row">
                                    <td><input type="hidden" name="id_detail_barang_lama[]" value="${data.id_detail_barang}"> <input type="hidden" name="id_detail_barang_pengiriman_stok[]" value="${data.id_detail_barang_pengiriman_stok}">${i++} </td>
                                    <td><input type="hidden" name="id_barang[]" value="${data.id_barang}"> ${data.nama_barang} </td>
                                    <td><input type="hidden" name="no_batch[]" value="${data.no_batch}"> ${data.no_batch}</td>
                                    <td><input class="form-control" type="number" name="qty[]" value="${data.current_qty}"> </td>
                                    <td>${data.exp_date}</td>
                                    <td>
                                        <input name="checkbox[]" type="checkbox" value="${data.id_detail_barang_pengiriman_stok}">
                                    </td>
                                </tr>
                            `;
                        $('#table_detail').append(x);
                    })
                }
            })
        })
    })

    function goBack() {
        window.history.back();
    }
</script>