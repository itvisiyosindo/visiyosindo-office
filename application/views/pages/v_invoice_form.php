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
        <?php if ($this->uri->segment(2) == 'edit') { ?>
            <?= form_open('invoice/update', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <?php } else { ?>
            <?= form_open('invoice/add', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <?php } ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Customer <span class="text-danger">*</span></label>
                    <?php if ($this->uri->segment(2) == 'edit') { ?>
                        <input disabled class="form-control" type="text" name="id_customer" id="id_customer" value="<?= $invoice[0]->nama_customer ?? NULL ?>" required>
                    <?php } else { ?>
                        <select data-plugin-selectTwo class="form-control search_customer filter-grup" name="id_customer" id="id_customer">
                            <?php if ($temp_data) { ?>
                                <option value="<?= encrypt($temp_data[0]->id_customer) ?>" selected="selected"><?= $temp_data[0]->nama_customer ?></option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group  mb-2 pt-1">
                    <label class="col-form-label">Tanggal Invoice <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <?php if (isset($invoice)) { ?>
                            <input type="text" data-plugin-datepicker data='{"id": "123", text: "res_data.primary_email"}' data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tgl_invoice" id="tgl_invoice" value="<?= $invoice[0]->tgl_invoice ?? NULL ?>" required data-plugin-datepicker>
                        <?php } else { ?>
                            <input type="text" data-plugin-datepicker data='{"id": "123", text: "res_data.primary_email"}' data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tgl_invoice" id="tgl_invoice" value="<?= $temp_data[0]->tgl_invoice ?? NULL ?>" required data-plugin-datepicker>
                        <?php } ?>
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
                    <!-- jika di akses dari form edit -->
                    <?php if (isset($invoice)) { ?>
                        <input class="form-control" type="text" name="no_invoice" id="no_invoice" value="<?= $invoice[0]->no_invoice ?>" required>
                        <!-- jika di akses dari form yg berisi temp data -->
                    <?php } else { ?>
                        <input class="form-control" type="text" name="no_invoice" id="no_invoice" value="<?= $temp_data[0]->no_invoice ?? NULL ?>" required>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Syarat Pembayaran <span class="text-danger">*</span></label>
                    <select class="form-control" name="id_syarat_pembayaran" id="id_syarat_pembayaran">
                        <option value="">...</option>
                        <?php if (isset($invoice)) { ?>
                            <?php foreach ($syarat_pembayaran as $sp) { ?>
                                <option <?= $invoice[0]->id_syarat_pembayaran == encrypt($sp->id_syarat_pembayaran) ? "selected" : NULL ?> value="<?= encrypt($sp->id_syarat_pembayaran) ?>"><?= $sp->nama_syarat_pembayaran ?></option>
                            <?php } ?>
                        <?php } else if ($temp_data) { ?>
                            <?php foreach ($syarat_pembayaran as $sp) { ?>
                                <option <?= encrypt($temp_data[0]->id_syarat_pembayaran) == encrypt($sp->id_syarat_pembayaran) ? "selected" : NULL ?> value="<?= encrypt($sp->id_syarat_pembayaran) ?>"><?= $sp->nama_syarat_pembayaran ?></option>
                            <?php } ?>
                        <?php } else { ?>
                            <?php foreach ($syarat_pembayaran as $sp) { ?>
                                <option value="<?= encrypt($sp->id_syarat_pembayaran) ?>"><?= $sp->nama_syarat_pembayaran ?></option>
                            <?php } ?>
                        <?php  } ?>
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
                        <?php if ($invoice) { ?>
                            <?php foreach ($marketing as $m) { ?>
                                <option <?= $invoice[0]->id_marketing == encrypt($m->pengguna_id) ? "selected" : NULL ?> value="<?= encrypt($m->pengguna_id) ?>"><?= $m->nama ?></option>
                            <?php } ?>
                        <?php } else if ($temp_data) { ?>
                            <?php foreach ($marketing as $m) { ?>
                                <option <?= encrypt($temp_data[0]->id_marketing) == encrypt($m->pengguna_id) ? "selected" : NULL ?> value="<?= encrypt($m->pengguna_id) ?>"><?= $m->nama ?></option>
                            <?php } ?>
                        <?php } else { ?>
                            <?php foreach ($marketing as $m) { ?>
                                <option value="<?= encrypt($m->pengguna_id) ?>"><?= $m->nama ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Purchase Order (PO) <span class="text-danger">*</span></label>
                    <!-- jika di akses dari form edit -->
                    <?php if (isset($invoice)) { ?>
                        <input class="form-control" type="text" name="no_po" id="no_po" value="<?= $invoice[0]->no_po ?? NULL ?>" required>
                        <!-- jika di akses dari form yg berisi temp data -->
                    <?php } else { ?>
                        <input class="form-control" type="text" name="no_po" id="no_po" value="<?= $temp_data[0]->no_po ?? NULL ?>" required>
                    <?php } ?>
                </div>
            </div>
        </div>
        <br>
        <div class="form-group mb-2 pt-1">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-form-label">
                        <?php if (isStafAdmin() || isAdmin()) { ?>
                            <?php if (isset($invoice)) { ?>
                                <?php if ($invoice[0]->from_pengeluaran_barang == 0) { ?>
                                    <a href="javascript:;" id="btn-pilih-barang-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Barang</a>
                                <?php } ?>
                            <?php } else { ?>
                                <a href="javascript:;" id="btn-pilih-barang-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Barang</a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <?php if ($this->uri->segment(2) == "edit") { ?>
                        <div class="col-form-label">
                            <button type="button" class="btn btn-sm btn-warning" id="btn-print"><i class="fas fa-print"></i> Print</button>
                        </div>
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
                            <th> Kode Barang</th>
                            <th> Kuantitas </th>
                            <th> Harga Satuan </th>
                            <th> Discount Satuan </th>
                            <th> Harga Total </th>
                            <?php if (isStafAdmin() || isAdmin()) { ?>
                                <th> Aksi </th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody id="detail-barang">
                        <?php $i = 1 ?>
                        <!-- menampilkan detail barang keluar yg asli -->
                        <?php if (isset($detail_barang_invoice)) { ?>
                            <?php foreach ($detail_barang_invoice as $row) { ?>
                                <tr>
                                    <td> <input type="hidden" value="<?= $row->id_detail_barang_invoice ?? NULL ?>"><?= $i++ ?></td>
                                    <td> <input type="hidden" value="<?= $row->id_barang ?>"><?= $row->nama_barang ?> </td>
                                    <td> <input type="hidden"><?= $row->kode_barang ?> </td>
                                    <td> <input type="hidden" value="<?= $row->qty ?>"> <?= $row->qty ?> (Tersisa : <?= $row->current_qty ?? '-' ?>)</td>
                                    <td> <input type="hidden" value="<?= $row->harga ?>"> <?= $row->harga ?></td>
                                    <td> <input type="hidden" value="<?= $row->discount ?>"> <?= $row->discount ?></td>
                                    <td> <input type="hidden" value="<?= $row->harga_total ?>" class="tot-harga"> <?= $row->harga_total ?></td>
                                    <?php if (isStafAdmin() || isAdmin()) { ?>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="<?= $row->id_detail_barang_invoice ?>"><i class="bx bx-pencil"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="<?= $row->id_detail_barang_invoice ?>" data-object="invoice/delete/detail_barang_invoice/" .<?= $row->id_detail_barang_invoice ?>><i class="bx bx-trash"></i></button>
                                            <?php if ($row->id_pengeluaran_barang) { ?>
                                                <a class="btn btn-sm btn-warning mt-1" href="pengeluaran_barang/edit/<?= encrypt($row->id_pengeluaran_barang) ?>"><i class="fas fa-truck-moving"></i> <?= $row->no_pengeluaran_barang ?></a>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-sm btn-warning btn_pengeluaran_barang" data-id="<?= $row->id_detail_barang_invoice ?>"><i class="fas fa-truck-moving"></i></button>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            <!-- jika ada data temp baru saat edit detail barang yg asli -->
                            <?php if (isStafAdmin() || isAdmin()) { ?>
                                <?php if (isset($new_detail_barang_temp)) { ?>
                                    <?php foreach ($new_detail_barang_temp as $row) { ?>
                                        <tr>
                                            <td> <?= $i++ ?></td>
                                            <td> <input type="hidden" name="id_barang[]" value="<?= $row->id_barang ?>"><?= $row->nama_barang ?> <span class="text-warning">(Temp)</span></td>
                                            <td> <input type="hidden"><?= $row->kode_barang ?> </td>
                                            <td> <input type="hidden" name="qty[]" value="<?= $row->qty ?>"> <?= $row->qty ?></td>
                                            <td> <input type="hidden" name="harga[]" value="<?= $row->harga ?>"> <?= $row->harga ?></td>
                                            <td> <input type="hidden" name="discount[]" value="<?= $row->discount ?>"> <?= $row->discount ?></td>
                                            <td> <input type="hidden" name="harga_total[]" value="<?= $row->harga_total ?>"> <?= $row->harga_total ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary btn-edit-temp" data-id="<?= $row->id_detail_barang_invoice_temp ?>"><i class="bx bx-pencil"></i></button>
                                                <button type="button" class="btn btn-sm btn-danger delete-temp-barang" data-id="<?= $row->id_detail_barang_invoice_temp ?>"><i class="bx bx-trash"></i></button>
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
                                    <td> <input type="hidden" name="id_barang[]" value="<?= encrypt($row->id_barang) ?>"><?= $row->nama_barang ?> </td>
                                    <td> <input type="hidden"><?= $row->kode_barang ?> </td>
                                    <td> <input type="hidden" name="qty[]" value="<?= $row->qty ?>"> <?= $row->qty ?></td>
                                    <td> <input type="hidden" name="harga[]" value="<?= $row->harga ?>"> <?= $row->harga ?></td>
                                    <td> <input type="hidden" name="discount[]" value="<?= $row->discount ?>"> <?= $row->discount ?></td>
                                    <td> <input type="hidden" name="harga_total[]" value="<?= $row->harga_total ?>" class="tot-harga"> <?= $row->harga_total ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-edit-temp" data-id="<?= encrypt($row->id_detail_barang_invoice_temp) ?>"><i class="bx bx-pencil"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger delete-temp-barang" data-id="<?= encrypt($row->id_detail_barang_invoice_temp) ?>"><i class="bx bx-trash"></i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
                <small><strong>Keterangan :</strong> "Tersisa" pada kolom Kuantitas adalah sisa stok barang invoice yg belum di buatkan pengeluaran barangnya</small>
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
                    <div class="text-right">Total Pajak : Rp <span id="pajak_saja"></span></div>
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
                    <label class=" col-form-label">Ongkir <span class="text-danger">*</span></label> <small class="text-danger">isi dengan "0", jika kosong</small>
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
            <input type="hidden" id="id_invoice" name="id_invoice" value="<?= isset($invoice[0]->id_invoice) ?  $invoice[0]->id_invoice : NULL ?>">
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
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Form Detail Barang Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="form-group mb-2 pt-1">
                    <div class="col-form-label">
                        <label>Pilih Barang / &nbsp;<i class="fas fa-barcode"> &nbsp;</i>Scan Barcode </label>
                        <select data-plugin-selectTwo="search_barang" class="form-control search_barang_diform filter-grup" name="id_barang[]" id="id_barang"></select>
                    </div>
                    <input class="form-group" type="hidden" id='id_barang_edit' name="id_barang_edit[]">
                    <input class="form-group" type="hidden" id='id_detail_barang_edit' name="id_detail_barang_edit[]">
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Harga <span class="text-danger">*</span></label>
                    <input class="form-control rupiah" type="text" name="harga[]" id="harga" value="" required>
                    <div id="danger-alert"></div>
                </div>
                <div class="form-group mb-2 pt-1">
                    <div class="row">
                        <!-- fuadi -->
                        <div class="col-md-6">
                            <label class=" col-form-label">Persentase Discount (%) <span class="text-danger">*</span></label>
                            <input class="form-control" type="number" id="persentase_discount" value="" required>
                        </div>
                        <div class="col-md-6">
                            <label class=" col-form-label">Total Discount <span class="text-danger">*</span></label>
                            <input class="form-control rupiah" type="text" name="discount[]" id="discount" value="" required>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Kuantitas <span class="text-danger">*</span></label>
                    <input class="form-control" type="number" name="qty[]" id="qty" value="" min="1" required>
                    <div id="danger-alert"></div>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Harga Total <span class="text-danger">*</span></label>
                    <input readonly class="form-control rupiah" type="text" name="harga_total[]" id="harga_total" value="" required>
                    <div id="danger-alert"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="form-control" id="id_detail_barang_invoice_temp" name="id_detail_barang_invoice_temp[]">
                    <input type="hidden" class="form-control" id="id_detail_barang_invoice" name="id_detail_barang_invoice[]">
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
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Detail Barang Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('invoice/update/detail_barang_invoice', array('id' => 'edit-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Nama Barang <span class="text-danger">*</span></label>
                    <input disabled class="form-control" type="text" id="nama_barang_edit" value="">
                    <div id="danger-alert"></div>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Harga <span class="text-danger">*</span></label>
                    <input class="form-control rupiah " type="text" name="harga" id="harga_edit" value="" required>
                    <div id="danger-alert"></div>
                </div>
                <div class="form-group mb-2 pt-1">
                    <div class="row">
                        <div class="col-md-6">
                            <label class=" col-form-label">Persentase Discount (%)<span class="text-danger">*</span></label>
                            <input class="form-control" max="2" type="number" id="persentase_discount_edit" value="" required>
                        </div>
                        <div class="col-md-6">
                            <label class=" col-form-label">Total Discount <span class="text-danger">*</span></label>
                            <input class="form-control rupiah" type="text" name="discount" id="discount_edit" value="" required>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Kuantitas <span class="text-danger">*</span></label>
                    <input <?= $invoice[0]->from_pengeluaran_barang == 1 ? 'disabled' : NULL ?> class="form-control" type="number" name="qty" id="qty_edit" value="" min="1" required>
                    <div id="danger-alert"></div>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Harga Total <span class="text-danger">*</span></label>
                    <input style="pointer-events: none;" class="form-control rupiah" type="text" name="harga_total" id="harga_total_edit" value="" required>
                    <div id="danger-alert"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="from_pengeluaran_barang" value="<?= $invoice[0]->from_pengeluaran_barang ?>">
                    <input type="hidden" class="form-control" id="id_detail_barang_invoice_temp" name="id_detail_barang_invoice_temp">
                    <input type="hidden" class="form-control" id="id_detail_barang_invoice_edit" name="id_detail_barang_invoice">
                    <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                    <button type="button" id="save-form" class="btn btn-success  btn-save">Simpan</button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="list-pengeluaran-barang-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>List Pengeluaran Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body isi-list">
                <!-- <div class="mb-3">
                    <strong>No Pengeluaran Barang :</strong> <a href="">23048209348</a><br>
                    <strong>NO Batch :</strong> 23748923ws <br>
                    <strong>Qty :</strong> 34 <br>
                </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-print" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Print Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <strong>Pilih Konfigurasi :</strong><br><br>
                <div>
                    <label class="switch">
                        <input id="con-batch" type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    Dengan Batch
                </div>
                <div>
                    <label class="switch">
                        <input id="con-pajak" type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    Tampilkan Nominal Pajak
                </div>
                <div>
                    <label class="switch">
                        <input id="con-ttd-digital" type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    Dengan TTD digital
                </div>
                <br>
                <div class="text-center">
                    <a class="btn btn-sm btn-warning" id="print-config"> <i class="fas fa-print"></i> Print</a>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="id-invoice" value="<?= $invoice[0]->id_invoice?>">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.rupiah').mask('000.000.000.000', {
            reverse: true
        });

        //agar kolom discount tidak bisa di isi lebih dari 2 karakter
        var max_chars = 2;
        $('#persentase_discount_edit, #persentase_discount').keydown(function(e) {
            if ($(this).val().length >= max_chars) {
                $(this).val($(this).val().substr(0, max_chars));
            }
        });
        $('#persentase_discount_edit, #persentase_discount').keyup(function(e) {
            if ($(this).val().length >= max_chars) {
                $(this).val($(this).val().substr(0, max_chars));
            }
        });


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
        //end get sub total
        ///////////////////


        //get total (dengan pajak)
        /////////////////////////
        let pajak = $('#sub_total').val().replaceAll('.', '') * $('option:selected', $('#id_tarif_pajak')).attr('persentase') / 100
        let total = parseInt($('#sub_total').val().replaceAll('.', '')) + pajak
        $('#total').val(total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));

        $(document).on('change', '#id_tarif_pajak', function() {
            let pajak = $('#sub_total').val().replaceAll('.', '') * $('option:selected', $('#id_tarif_pajak')).attr('persentase') / 100
            let total = parseInt($('#sub_total').val().replaceAll('.', '')) + pajak
            $('#pajak_saja').html(pajak.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."))
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

        $(document).on('keyup', '#ongkir', function() {
            let total = $('#total').val().replaceAll('.', '')
            let ongkir = $('#ongkir').val().replaceAll('.', '')
            let total_keseluruhan = parseInt(total) + parseInt(ongkir)
            $('#total_keseluruhan').val(total_keseluruhan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        })
        //get total keseluruhan
        ///////////////////////

        //tampilkan jumlah pajak pada harga
        let pajakk = $('#sub_total').val().replaceAll('.', '') * $('option:selected', $('#id_tarif_pajak')).attr('persentase') / 100
        $('#pajak_saja').html(pajakk.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."))

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
        //perhitungan diskon
        $(document).on('keyup', '#persentase_discount', function() {
            let persentase = $('#persentase_discount').val()
            let harga = $('#harga').val().replaceAll('.', '')

            let discount = harga * persentase / 100
            $('#discount').val(discount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        })


        $(document).on('keyup', '#harga,#qty,#discount,#persentase_discount', function() {
            let harga = $('#harga').val().replaceAll('.', '')
            let qty = $('#qty').val()
            let discount = $('#discount').val().replaceAll('.', '')
            let harga_total = (harga - discount) * qty
            
            $('#harga_total').val(harga_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        })

        //perhitungan diskon edit
        $(document).on('keyup', '#persentase_discount_edit', function() {
            let persentase = $('#persentase_discount_edit').val()
            let harga = $('#harga_edit').val().replaceAll('.', '')

            let discount = harga * persentase / 100
            $('#discount_edit').val(discount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        })

        $(document).on('keyup', '#harga_edit,#qty_edit,#discount_edit,#persentase_discount_edit', function() {

            let harga = $('#harga_edit').val().replaceAll('.', '')
            let qty = $('#qty_edit').val()
            let discount = $('#discount_edit').val().replaceAll('.', '')
            let harga_total = (harga - discount) * qty

            $('#harga_total_edit').val(harga_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        })



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

        $('#btn-pilih-barang-form').click(function() {
            if (!$('#id_customer').val() || !$('#tgl_invoice').val() || !$('#no_invoice').val() || !$('#id_marketing').val() || !$('#id_syarat_pembayaran').val()) {
                alert('form tidak boleh kosong!!!')
            } else {
                $('#main-modal .form-control').val(null)
                $('#main-modal').modal()
                <?php if ($this->uri->segment(2) == "show") { ?>
                    $.ajax({
                        method: 'POST',
                        url: 'invoice/add/temp',
                        dataType: 'JSON',
                        data: {
                            id_customer: $('#id_customer').val(),
                            tgl_invoice: $('#tgl_invoice').val(),
                            no_pengiriman: $('#no_pengiriman').val(),
                            no_po: $('#no_po').val(),
                            // id_ekspedisi: $('#id_ekspedisi').val(),
                            id_marketing: $('#id_marketing').val(),
                            no_invoice: $('#no_invoice').val(),
                            id_syarat_pembayaran: $('#id_syarat_pembayaran').val(),
                            csrf_token: token
                        },
                    })
                <?php } ?>
            }
        })

        $('.btn_pengeluaran_barang').click(function() {
            $('#list-pengeluaran-barang-modal .isi-list').empty()
            $('#list-pengeluaran-barang-modal').modal()

            $.ajax({
                method: 'POST',
                url: 'pengeluaran_barang/get/ByIdDetailInvoice',
                dataType: 'JSON',
                data: {
                    id_detail_barang_invoice: $(this).attr("data-id"),
                    csrf_token: token
                },
                success: function(resp) {
                    resp.forEach(data => {
                        let x = `
                        <div class="mb-3">
                            <strong>No Pengeluaran Barang :</strong> <a href="pengeluaran_barang/edit/${data.id_pengeluaran_barang}"> ${data.no_pengiriman} </a><br>
                            <strong>NO Batch :</strong> ${data.no_batch} <br>
                            <strong>Qty :</strong> ${data.qty} <br>
                            <hr style="border: 1px solid;">
                        </div>
                    `;
                        $('.isi-list').append(x);
                    })

                }
            })
        })

        $('.save-form').click(function() {
            $.ajax({
                method: 'POST',
                url: 'invoice/add/temp/detail_barang_invoice',
                dataType: 'JSON',
                data: {
                    id_barang: $('#id_barang').val(),
                    qty: $('#qty').val(),
                    harga: $('#harga').val(),
                    harga_total: $('#harga_total').val(),
                    id_detail_barang_invoice_temp: $('#id_detail_barang_invoice_temp').val(),
                    id_invoice: $('#id_invoice').val(),
                    discount: $('#discount').val(),
                    // id_pengeluaran_barang: $('#id_invoice').val(),
                    // id_detail_barang_keluar_temp: $('#id_detail_barang_keluar_temp').val(),
                    // id_detail_barang_keluar: $('#id_detail_barang_keluar').val(),
                    // id_detail_barang: $('option:selected', $('#no_batch')).attr('id_detail_barang_attr'),
                    // id_detail_barang_edit: $('#id_detail_barang_edit').val(),
                    // id_barang_edit: $('#id_barang_edit').val(),
                    csrf_token: token
                },
                success: function(resp) {
                    handleResponse(resp)
                }
            })
        })

        $(document).on('click', '.btn-edit-temp', function() {
            $('#main-modal .form-control').val(null)
            $('#main-modal').modal()
            var id = $(this).attr("data-id")

            $.ajax({
                method: 'POST',
                url: 'invoice/get/detail_barang_invoice_temp',
                dataType: 'JSON',
                data: {
                    id_detail_barang_invoice_temp: id,
                    csrf_token: token
                },
                success: function(data) {
                    $('#main-modal #qty').val(data[0].qty)
                    $('#main-modal #harga').val(data[0].harga)
                    $('#main-modal #harga_total').val(data[0].harga_total)
                    $('#main-modal #discount').val(data[0].discount)
                    $('#main-modal #id_detail_barang_invoice_temp').val(data[0].id_detail_barang_invoice_temp)
                }
            })
        })

        $(document).on('click', '#btn-print', function() {
            $('#modal-print').modal()
        })

        $(document).on('click', '#print-config', function() {
            let batch = $('#con-batch').is(':checked') ? 1 : 0;
            let pajak = $('#con-pajak').is(':checked') ? 1 : 0;
            let ttd_digital = $('#con-ttd-digital').is(':checked') ? 1 : 0;
            let id_invoice = $('#id-invoice').val();

            var link = 'invoice/print/' + batch + '/' + pajak + '/' + ttd_digital + '/' + id_invoice
            window.location.href = '<?= base_url() ?>' + link;
        })

        $(document).on('click', '.btn-edit', function() {
            $('#edit-modal .form-control').val(null)
            $('#edit-modal').modal()
            var id = $(this).attr("data-id")

            $.ajax({
                method: 'POST',
                url: 'invoice/get/detail_barang_invoice',
                dataType: 'JSON',
                data: {
                    id_detail_barang_invoice: id,
                    csrf_token: token
                },
                success: function(resp) {

                    // $('#edit-modal #qty').val(resp['detail_barang_keluar'][0].qty)
                    $('#edit-modal #nama_barang_edit').val(resp['detail_barang_invoice'][0].nama_barang)
                    $('#edit-modal #harga_edit').val(resp['detail_barang_invoice'][0].harga)
                    $('#edit-modal #discount_edit').val(resp['detail_barang_invoice'][0].discount)
                    $('#edit-modal #harga_total_edit').val(resp['detail_barang_invoice'][0].harga_total)
                    $('#edit-modal #qty_edit').val(resp['detail_barang_invoice'][0].qty)
                    $('#edit-modal #id_detail_barang_invoice_edit').val(resp['detail_barang_invoice'][0].id_detail_barang_invoice)
                    // $('#edit-modal #no_batch').val(resp['detail_barang_keluar'][0].no_batch)
                    // $('#edit-modal #exp_date').val(resp['detail_barang_keluar'][0].exp_date)
                    // $('#edit-modal #id_detail_barang_keluar').val(resp['detail_barang_keluar'][0].id_detail_barang_keluar)
                    // $('#edit-modal #id_detail_barang_edit').val(resp['detail_barang_keluar'][0].id_detail_barang)
                }
            })
        })

        $(document).on('click', '.btn-clear-form', function() {

            $('#main-modal .form-control').val(null)
            $('.search_barang_diform').val(null)
        })

        $('.delete-temp-barang').click(function() {
            $.ajax({
                method: 'POST',
                url: 'invoice/delete/detail_barang_invoice_temp',
                dataType: 'JSON',
                data: {
                    id_detail_barang_invoice_temp: $(this).attr("data-id"),
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
                url: 'invoice/delete/dest_temp',
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