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
            <h2>Form Pengeluaran Barang</h2>
        </div>
        <?php if ($this->uri->segment(2) == 'edit') { ?>
            <?= form_open('pengeluaran_barang/update', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <?php } else { ?>
            <?= form_open('pengeluaran_barang/add', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <?php } ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Customer <span class="text-danger">*</span></label>
                    <?php if ($this->uri->segment(2) == 'edit') { ?>
                        <input disabled class="form-control" type="text" name="id_customer" id="id_customer" value="<?= $pengeluaran_barang[0]->nama_customer ?? NULL ?>" required>
                    <?php } else { ?>
                        <select data-plugin-selectTwo class="form-control search_customer filter-grup" name="id_customer" id="id_customer">
                            <?php if ($temp_data) { ?>
                                <option value="<?= $temp_data[0]->id_customer ?>" selected="selected"><?= $temp_data[0]->nama_customer ?></option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group  mb-2 pt-1">
                    <label class="col-form-label">Tanggal Pengiriman <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <!-- jika di akses dari form edit -->
                        <?php if (isset($pengeluaran_barang)) { ?>
                            <input type="text" data-plugin-datepicker data='{"id": "123", text: "res_data.primary_email"}' data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tgl_keluar" id="tgl_keluar" value="<?= $pengeluaran_barang[0]->tgl_keluar ?? NULL ?>" required data-plugin-datepicker>
                            <!-- jika di akses dari form yg berisi temp data -->
                        <?php } else { ?>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tgl_keluar" id="tgl_keluar" value="<?= $temp_data[0]->tgl_keluar ?? NULL ?>" required data-plugin-datepicker>
                        <?php } ?>
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
                        <?php if ($pengeluaran_barang) { ?>
                            <?php foreach ($ekspedisi as $e) { ?>
                                <option <?= $pengeluaran_barang[0]->id_ekspedisi == encrypt($e->id_ekspedisi) ? "selected" : NULL ?> value="<?= encrypt($e->id_ekspedisi) ?>"><?= $e->nama_ekspedisi ?></option>
                            <?php } ?>
                        <?php } else if ($temp_data) { ?>
                            <?php foreach ($ekspedisi as $e) { ?>
                                <option <?= $temp_data[0]->id_ekspedisi == encrypt($e->id_ekspedisi) ? "selected" : NULL ?> value="<?= encrypt($e->id_ekspedisi) ?>"><?= $e->nama_ekspedisi ?></option>
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
                    <label class=" col-form-label">No Pengiriman <span class="text-danger">*</span></label>
                    <!-- jika di akses dari form edit -->
                    <?php if (isset($pengeluaran_barang)) { ?>
                        <input class="form-control" type="text" name="no_pengiriman" id="no_pengiriman" value="<?= $pengeluaran_barang[0]->no_pengiriman ?? NULL ?>" required>
                        <!-- jika di akses dari form yg berisi temp data -->
                    <?php } else { ?>
                        <input class="form-control" type="text" name="no_pengiriman" id="no_pengiriman" value="<?= $temp_data[0]->no_pengiriman ?? NULL ?>" required>
                    <?php } ?>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Gudang <span class="text-danger">*</span></label>
                    <select class="form-control" name="id_gudang" id="id_gudang" required <?= isset($detail_barang_keluar_temp) || $this->uri->segment(2) == 'edit' ? 'disabled' : NULL ?>>
                        <option value="">...</option>
                        <?php if ($pengeluaran_barang) { ?>
                            <?php foreach ($gudang as $g) { ?>
                                <option <?= $pengeluaran_barang[0]->id_gudang == encrypt($g->id_gudang) ? "selected" : NULL ?> value="<?= encrypt($g->id_gudang) ?>"><?= $g->nama_gudang ?></option>
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
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Purchase Order (PO) <span class="text-danger">*</span></label>
                    <!-- jika di akses dari form edit -->
                    <?php if (isset($pengeluaran_barang)) { ?>
                        <input class="form-control" type="text" name="no_po" id="no_po" value="<?= $pengeluaran_barang[0]->no_po ?? NULL ?>" required>
                        <!-- jika di akses dari form yg berisi temp data -->
                    <?php } else { ?>
                        <input class="form-control" type="text" name="no_po" id="no_po" value="<?= $temp_data[0]->no_po ?? NULL ?>" required>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="form-group mb-2 pt-1">
            <label class=" col-form-label">Alamat <span class="text-danger">*</span></label>
            <?php if (isset($pengeluaran_barang)) { ?>
                <textarea class="form-control" name="alamat" id="alamat" required placeholder="..."><?= $pengeluaran_barang[0]->alamat ?? NULL ?></textarea>
            <?php } else { ?>
                <textarea class="form-control" name="alamat" id="alamat" required placeholder="..."><?= $temp_data[0]->alamat ?? NULL ?></textarea>
            <?php } ?>
        </div>

        <div class="form-group mb-2 pt-1">
            <label class=" col-form-label">Keterangan <span class="text-danger">*</span></label>
            <?php if (isset($pengeluaran_barang)) { ?>
                <textarea class="form-control" name="keterangan" id="keterangan" required placeholder="..."><?= $pengeluaran_barang[0]->keterangan ?? NULL ?></textarea>
            <?php } else { ?>
                <textarea class="form-control" name="keterangan" id="keterangan" required placeholder="..."><?= $temp_data[0]->keterangan ?? NULL ?></textarea>
            <?php } ?>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Resi <span class="text-danger">*</span></label>
                    <!-- jika di akses dari form edit -->
                    <?php if (isset($pengeluaran_barang)) { ?>
                        <input class="form-control" type="text" name="resi" id="resi" value="<?= $pengeluaran_barang[0]->resi ?? NULL ?>" required>
                        <!-- jika di akses dari form yg berisi temp data -->
                    <?php } else { ?>
                        <input class="form-control" type="text" name="resi" id="resi" value="<?= $temp_data[0]->resi ?? NULL ?>" required>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Status Pengiriman <span class="text-danger">*</span></label>
                    <!-- jika di akses dari form edit -->
                    <?php if (isset($pengeluaran_barang)) { ?>
                        <input class="form-control" type="text" name="status_pengiriman" id="status_pengiriman" value="<?= $pengeluaran_barang[0]->status_pengiriman ?? NULL ?>" required>
                        <!-- jika di akses dari form yg berisi temp data -->
                    <?php } else { ?>
                        <input class="form-control" type="text" name="status_pengiriman" id="status_pengiriman" value="<?= $temp_data[0]->status_pengiriman ?? NULL ?>" required>
                    <?php } ?>
                </div>
            </div>
        </div>
        <br>
        <div class="form-group mb-2 pt-1">
            <div class="row">
                <div class="col-md-6">
                    <!-- <div class="col-form-label">
                        <?php if (isStafAdmin() || isAdmin()) { ?>
                            <a href="javascript:;" id="btn-pilih-barang-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Barang</a>
                        <?php } ?>
                    </div> -->
                    <div class="col-form-label">
                        <?php if (isStafAdmin() || isAdmin()) { ?>
                            <?php if (isset($pengeluaran_barang)) { ?>
                                <?php if ($pengeluaran_barang[0]->from_invoice == NULL) { ?>
                                    <a href="javascript:;" id="btn-pilih-barang-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Barang</a>
                                <?php } ?>
                            <?php } else { ?>
                                <a href="javascript:;" id="btn-pilih-barang-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Barang</a>
                            <?php } ?>
                        <?php } ?>
                    </div><br>
                </div>
                <div class="col-md-6 text-right mt-4">
                    <?php if ($this->uri->segment(2) == "edit") { ?>
                        <!-- <a href="pengeluaran_barang/print/dengan_kop/<?= $pengeluaran_barang[0]->id_pengeluaran_barang ?>"> Print Dengan Kop <i class="fas fa-print"></i></a><br>
                        <a href="pengeluaran_barang/print/tanpa_kop/<?= $pengeluaran_barang[0]->id_pengeluaran_barang ?>"> Print Tanpa Kop <i class="fas fa-print"></i></a><br>
                        <a href="pengeluaran_barang/print/nama_customer/<?= $pengeluaran_barang[0]->id_pengeluaran_barang ?>"> Print Dengan Nama Pengirim <i class="fas fa-print"></i></a><br> -->
                        <button type="button" class="btn btn-sm btn-warning" id="btn-print"><i class="fas fa-print"></i> Print</button>
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
                            <th> No AKL</th>
                            <th> Kuantitasi </th>
                            <th> No Batch </th>
                            <th> Exp Date </th>
                            <?php if (isStafAdmin() || isAdmin()) { ?>
                                <th> Aksi </th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 ?>
                        <!-- menampilkan detail barang keluar yg asli -->
                        <?php if (isset($detail_barang_keluar)) { ?>
                            <?php foreach ($detail_barang_keluar as $row) { ?>
                                <tr>
                                    <td> <input type="hidden" value="<?= $row->id_detail_barang_keluar ?? NULL ?>"><?= $i++ ?></td>
                                    <td> <input type="hidden" value="<?= $row->id_barang ?>"><?= $row->nama_barang ?> </td>
                                    <td> <input type="hidden" value="<?= $row->nie ?>"><?= $row->nie ?> </td>
                                    <td> <input type="hidden" value="<?= $row->qty ?>"> <?= $row->qty ?></td>
                                    <td> <input type="hidden" value="<?= $row->no_batch ?>"> <?= $row->no_batch ?></td>
                                    <td> <input type="hidden" value="<?= $row->exp_date ?? NULL ?>"> <?= date('Y', strtotime($row->exp_date))<2000 ? '-' : date('d-m-Y', strtotime($row->exp_date)) ?></td>
                                    <?php if (isStafAdmin() || isAdmin()) { ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="<?= $row->id_detail_barang_keluar ?>"><i class="bx bx-pencil"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="<?= $row->id_detail_barang_keluar ?>" data-object="pengeluaran_barang/delete/detail_barang_keluar/" .<?= $row->id_detail_barang_keluar ?>><i class="bx bx-trash"></i></button>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            <!-- jika ada data temp baru saat edit detail barang yg asli -->
                            <?php if (isStafAdmin() || isAdmin()) { ?>
                                <?php if (isset($new_detail_barang_temp)) { ?>
                                    <?php foreach ($new_detail_barang_temp as $row) { ?>
                                        <tr>
                                            <td> <input type="hidden" name="id_detail_barang[]" value="<?= $row->id_detail_barang ?? NULL ?>"><?= $i++ ?></td>
                                            <td> <input type="hidden" name="id_barang[]" value="<?= $row->id_barang ?>"><?= $row->nama_barang ?> <span class="text-warning">(Temp)</span></td>
                                            <td> <input type="hidden" name="qty[]" value="<?= $row->qty ?>"> <?= $row->qty ?></td>
                                            <td> <input type="hidden" name="no_batch[]" value="<?= $row->no_batch ?>"> <?= $row->no_batch ?></td>
                                            <td> <input type="hidden" name="exp_date[]" value="<?= $row->exp_date ?? NULL ?>"> <?= $row->exp_date == '0000-00-00' ? '-' : date('d-m-Y', strtotime($row->exp_date)) ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary btn-edit-temp" data-id="<?= $row->id_detail_barang_keluar_temp ?>"><i class="bx bx-pencil"></i></button>
                                                <button type="button" class="btn btn-sm btn-danger delete-temp-barang" data-id="<?= $row->id_detail_barang_keluar_temp ?>"><i class="bx bx-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <!-- tampilkan jika ada detail barang temp -->
                        <?php } else if (isset($detail_barang_temp)) { ?>
                            <?php foreach ($detail_barang_temp as $row) { ?>
                                <tr>
                                    <td> <input type="hidden" name="id_detail_barang[]" value="<?= $row->id_detail_barang ?? NULL ?>"> <?= $i++ ?></td>
                                    <td> <input type="hidden" name="id_barang[]" value="<?= $row->id_barang ?>"><?= $row->nama_barang ?> </td>
                                    <td> <input type="hidden" name="qty[]" value="<?= $row->qty ?>"> <?= $row->qty ?></td>
                                    <td> <input type="hidden" name="no_batch[]" value="<?= $row->no_batch ?>"> <?= $row->no_batch ?></td>
                                    <td> <input type="hidden" name="exp_date[]" value="<?= $row->exp_date ?? NULL ?>"> <?= $row->exp_date == '0000-00-00' ? '-' : date('d-m-Y', strtotime($row->exp_date)) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-edit-temp" data-id="<?= $row->id_detail_barang_keluar_temp ?>"><i class="bx bx-pencil"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger delete-temp-barang" data-id="<?= $row->id_detail_barang_keluar_temp ?>"><i class="bx bx-trash"></i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
                <?php if (isset($data_invoice)) { ?>
                    <strong>Data sudah di gunakan di invoice</strong><a href="invoice/edit/<?= encrypt($data_invoice[0]->id_invoice) ?>"> <?= $data_invoice[0]->no_invoice ?></a>
                <?php } ?>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="id_pengeluaran_barang" name="id_pengeluaran_barang" value="<?= isset($pengeluaran_barang[0]->id_pengeluaran_barang) ?  $pengeluaran_barang[0]->id_pengeluaran_barang : NULL ?>">
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
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Form Detail Barang Keluar</h5>
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
                <label class="col-form-label">No Batch <span class="text-danger">*</span></label>
                <select class="form-control" name="no_batch" id="no_batch" required>
                    <option value="">...</option>
                </select>
                <div class="form-group  mb-2 pt-1">
                    <label class="col-form-label">Expired Date <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <input disabled type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="exp_date[]" id="exp_date" value="" required data-plugin-datepicker>
                    </div>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Kuantitas <span class="text-danger">*</span></label>
                    <input class="form-control" type="number" name="qty[]" id="qty" value="" min="1" required>
                    <div id="danger-alert"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="form-control" id="id_detail_barang_keluar_temp" name="id_detail_barang_keluar_temp[]">
                    <input type="hidden" class="form-control" id="id_detail_barang_keluar" name="id_detail_barang_keluar[]">
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
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Form Detail Barang Keluar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('pengeluaran_barang/update/detail_barang_keluar', array('id' => 'edit-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Nama Barang <span class="text-danger">*</span></label>
                    <input disabled class="form-control" type="text" id="nama_barang" value="">
                    <div id="danger-alert"></div>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">No Batch <span class="text-danger">*</span></label>
                    <input disabled class="form-control" type="text" id="no_batch" value="">
                    <div id="danger-alert"></div>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Exp Date <span class="text-danger">*</span></label>
                    <input disabled class="form-control" type="text" id="exp_date" value="">
                    <div id="danger-alert"></div>
                </div>
                <div class="form-group mb-2 pt-1">
                    <label class=" col-form-label">Kuantitas <span class="text-danger">*</span></label>
                    <input class="form-control" type="number" name="qty" id="qty" value="" min="1" required>
                    <div id="danger-alert"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="form-control" id="id_detail_barang_keluar_temp" name="id_detail_barang_keluar_temp">
                    <input type="hidden" class="form-control" id="id_detail_barang_keluar" name="id_detail_barang_keluar">
                    <input type="hidden" class="form-control" id="id_detail_barang_edit" name="id_detail_barang_edit">
                    <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success btn-save">Simpan</button>
                </div>
            </div>
            <!-- fuad -->
            <?= form_close(); ?>
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
                        <input id="con-kop" type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    Dengan Kop
                </div>
                <div>
                    <label class="switch">
                        <input id="con-nama-pengirim" type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    Dengan Nama Pengirim
                </div>
                <div>
                    <label class="switch">
                        <input id="con-alamat-form" type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    Kirim Dengan Alamat dari form
                </div>
                <br>
                <div class="text-center">
                    <a class="btn btn-sm btn-warning" id="print-config"> <i class="fas fa-print"></i> Print</a>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="id-pengeluaran-barang" value="<?= $pengeluaran_barang[0]->id_pengeluaran_barang ?>">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
            </div>
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


        $('#id_customer').on('select2:select', function (e) {
    var data = e.params.data;
    var id_customer = data.id;

    $.ajax({
        url: "<?= base_url('pengeluaran_barang/get_customer') ?>",
        method: "POST",
        data: {
            id_customer: id_customer,
            csrf_token: token
        },
        dataType: "json",
        success: function(response) {
            $('#alamat').val(response.alamat_customer || '');
        },
        error: function() {
            alert('Gagal mengambil alamat customer.');
        }
    });
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

        $(document).on('click', '#print-config', function() {
            let kop = $('#con-kop').is(':checked') ? 1 : 0;
            let nama_pengirim = $('#con-nama-pengirim').is(':checked') ? 1 : 0;
            let alamat_form = $('#con-alamat-form').is(':checked') ? 1 : 0;
            let id_pengeluaran_barang = $('#id-pengeluaran-barang').val();

            var link = 'pengeluaran_barang/print/' + kop + '/' + nama_pengirim + '/' + alamat_form + '/' + id_pengeluaran_barang
            window.location.href = '<?= base_url() ?>' + link;
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

        $(document).on('click', '#btn-print', function() {
            $('#modal-print').modal()
        })

        $('#btn-pilih-barang-form').click(function() {
            if (!$('#id_customer').val() || !$('#tgl_keluar').val() || !$('#no_pengiriman').val() || !$('#id_gudang').val() || !$('#keterangan').val() || !$('#alamat').val()) {
                alert('form tidak boleh kosong!!!')
            } else {
                $('#main-modal .form-control').val(null)
                $('#main-modal').modal()
                <?php if ($this->uri->segment(2) == "show") { ?>
                    $.ajax({
                        method: 'POST',
                        url: 'pengeluaran_barang/add/temp',
                        dataType: 'JSON',
                        data: {
                            id_customer: $('#id_customer').val(),
                            tgl_keluar: $('#tgl_keluar').val(),
                            no_pengiriman: $('#no_pengiriman').val(),
                            no_po: $('#no_po').val(),
                            id_gudang: $('#id_gudang').val(),
                            id_ekspedisi: $('#id_ekspedisi').val(),
                            keterangan: $('#keterangan').val(),
                            alamat: $('#alamat').val(),
                            resi: $('#resi').val(),
                            status_pengiriman: $('#status_pengiriman').val(),
                            csrf_token: token
                        },
                    })
                <?php } ?>
            }

        })

        $('.save-form').click(function() {

            $.ajax({
                method: 'POST',
                url: 'pengeluaran_barang/add/temp/detail_barang_keluar',
                dataType: 'JSON',
                data: {
                    id_barang: $('#id_barang').val(),
                    exp_date: $('#exp_date').val(),
                    no_batch: $('#no_batch').val(),
                    qty: $('#qty').val(),
                    id_pengeluaran_barang: $('#id_pengeluaran_barang').val(),
                    id_detail_barang_keluar_temp: $('#id_detail_barang_keluar_temp').val(),
                    id_detail_barang_keluar: $('#id_detail_barang_keluar').val(),
                    id_detail_barang: $('option:selected', $('#no_batch')).attr('id_detail_barang_attr'),
                    id_detail_barang_edit: $('#id_detail_barang_edit').val(),
                    id_barang_edit: $('#id_barang_edit').val(),
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
                url: 'pengeluaran_barang/get/detail_barang_keluar_temp',
                dataType: 'JSON',
                data: {
                    id_detail_barang_keluar_temp: id,
                    csrf_token: token
                },
                success: function(data) {
                    //////////////set value select2??////////////////////////////
                    $('#main-modal #no_batch').val(data[0].no_batch)
                    $('#main-modal #qty').val(data[0].qty)
                    $('#main-modal #exp_date').val(data[0].exp_date)
                    $('#main-modal #id_detail_barang_keluar_temp').val(data[0].id_detail_barang_keluar_temp)
                    $('#main-modal #id_detail_barang_keluar').val(data[0].id_detail_barang_keluar)
                }
            })
        })

        $(document).on('click', '.btn-edit', function() {
            $('#edit-modal .form-control').val(null)
            $('#edit-modal').modal()
            var id = $(this).attr("data-id")
            $.ajax({
                method: 'POST',
                url: 'pengeluaran_barang/get/detail_barang_keluar',
                dataType: 'JSON',
                data: {
                    id_detail_barang_keluar: id,
                    csrf_token: token
                },
                success: function(resp) {

                    $('#edit-modal #qty').val(resp['detail_barang_keluar'][0].qty)
                    $('#edit-modal #nama_barang').val(resp['detail_barang_keluar'][0].nama_barang)
                    $('#edit-modal #no_batch').val(resp['detail_barang_keluar'][0].no_batch)
                    $('#edit-modal #exp_date').val(resp['detail_barang_keluar'][0].exp_date)
                    $('#edit-modal #id_detail_barang_keluar').val(resp['detail_barang_keluar'][0].id_detail_barang_keluar)
                    $('#edit-modal #id_detail_barang_edit').val(resp['detail_barang_keluar'][0].id_detail_barang)
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
                url: 'pengeluaran_barang/delete/detail_barang_keluar_temp',
                dataType: 'JSON',
                data: {
                    id_detail_barang_keluar_temp: $(this).attr("data-id"),
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
                url: 'pengeluaran_barang/delete/dest_temp',
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