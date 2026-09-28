<?php
// Enkripsi ID untuk keamanan URL/Form
$id_kirim_enc = encrypt($data_tracking[0]->id);
$is_admin = in_array(sessPenggunaId(), [1, 15, 33, 7, 73, 749, 763, 769]);

// Ambil ID Customer saat ini dari data tracking untuk pembandingan
$current_id = $data_tracking[0]->id_customer ?? '';
?>

<header class="page-header">
    <h2><i class="icons fas fa-edit"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="mb-3">
    <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<style>
    /* Pastikan mode edit tidak berantakan */
    tr.is-editing td {
        background-color: #fff9e6 !important;
    }

    .edit-col {
        display: none;
    }

    tr.is-editing .edit-col {
        display: block !important;
    }

    tr.is-editing .view-col {
        display: none !important;
    }


    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .card-header {
        border-bottom: 2px solid #f0f0f0;
        padding: 12px 20px !important;
    }

    .card-body {
        padding: 20px;
    }

    .form-control {
        border-radius: 6px;
        border: 1px solid #ddd;
        height: 38px;
    }

    textarea.form-control {
        height: auto;
        min-height: 80px;
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 4px;
    }

    /* Edit Mode - Hidden by default */
    .edit-col {
        display: none;
    }

    /* View Mode - Visible by default */
    .view-col {
        display: table-cell;
    }

    /* Panel Diterima - Hidden by default */
    .panel-diterima-inline {
        background: #f8f9fa;
        border: 1px dashed #3498db;
        padding: 10px;
        margin-top: 8px;
        border-radius: 6px;
        display: none;
    }

    /* Row sedang diedit */
    tr.is-editing .view-col {
        display: none !important;
    }

    tr.is-editing .edit-col {
        display: table-cell !important;
    }

    tr.is-editing {
        background-color: #fff9e6 !important;
    }
</style>

<form id="formEditFull">
    <input type="hidden" name="id_kirim" value="<?= $id_kirim_enc ?>">

    <div id="customerMissingAlert" class="alert alert-danger d-none" role="alert" style="<?= empty($current_id) ? 'display: block;' : 'display: none;' ?> border-left: 5px solid #dc3545;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle mr-3 fa-2x"></i>
            <div>
                <strong>⚠️ Data Customer Belum Terpaut!</strong><br>
                Silahkan pilih database customer untuk menghubungkan tracking ini.
            </div>
        </div>
    </div>

    <div class="card card-modern border-left-primary mb-4">
        <div class="card-header bg-primary">
            <h5 class="mb-0 font-weight-bold text-white">Informasi Dokumen</h5>
        </div>
        <div class="card-body">
            <div class="row my-2">
                <div class="col-md-12 mb-2 h4">
                    <span class="border border-primary p-2 rounded">
                        <strong>Kode&nbsp;:</strong><span class="text-primary ml-3" style="font-weight: 800;"><?= $data_tracking[0]->kode ?></span>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 my-2">
                    <div class="form-group">
                        <label class="font-weight-bold mb-1">Database Customer <sup class="text-danger">*</sup></label>
                        <select class="form-control select2-init" id="nama_customer" name="id_customer">
                            <option value="">- Pilih Customer -</option>
                            <?php foreach ($list_cust as $row): ?>
                                <option value="<?= $row->id ?>"
                                    data-alamat="<?= htmlspecialchars($row->alamat) ?>"
                                    data-pic="<?= htmlspecialchars($row->telp) ?>"
                                    <?= ($row->id == $current_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row->nama) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="nama_customer_text" id="nama_customer_text" value="<?= $data_tracking[0]->nama_customer ?>">
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <div class="form-group">
                        <label class="font-weight-bold mb-1 text-danger">Nama Saat Ini</label>
                        <input type="text" class="form-control bg-light" value="<?= $data_tracking[0]->nama_customer ?>" readonly>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <div class="form-group">
                        <label class="font-weight-bold mb-1">PIC Penerima</label>
                        <input type="text" class="form-control" id="pic" name="pic" value="<?= $data_tracking[0]->pic ?>">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="font-weight-bold mb-1">Alamat Penerima</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="2"><?= $data_tracking[0]->alamat ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern border-left-secondary mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0 font-weight-bold text-white">Informasi Pengiriman</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 my-2">
                    <div class="form-group">
                        <label class="font-weight-bold mb-1">Update Ekspedisi</label>
                        <select class="form-control select2-init" name="id_ekspedisi">
                            <option value="">- Pilih Ekspedisi -</option>
                            <?php foreach ($list_ekspedisi as $row):
                                $sel_eks = ($row->id_ekspedisi == $data_tracking[0]->id_ekspedisi) ? 'selected' : '';
                            ?>
                                <option value="<?= $row->id_ekspedisi ?>" <?= $sel_eks ?>><?= $row->nama_ekspedisi ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <div class="form-group">
                        <label class="font-weight-bold mb-1 text-danger">Ekspedisi Saat Ini</label>
                        <input type="text" class="form-control bg-light" value="<?= $data_tracking[0]->ekspedisi ?>" readonly>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <div class="form-group">
                        <label class="font-weight-bold mb-1">No. Resi</label>
                        <input type="text" class="form-control" name="no_resi" value="<?= $data_tracking[0]->no_resi ?>">
                    </div>
                </div>
                <div class="col-md-6 my-2">
                    <div class="form-group">
                        <label class="font-weight-bold mb-1">Tanggal Kirim</label>
                        <input type="date" class="form-control" name="tgl_kirim" value="<?= date('Y-m-d', strtotime($data_tracking[0]->tgl_kirim)) ?>">
                    </div>
                </div>
                <div class="col-md-6 my-2">
                    <div class="form-group">
                        <label class="font-weight-bold mb-1">Estimasi Sampai</label>
                        <input type="date" class="form-control" name="tgl_sampai" value="<?= $data_tracking[0]->tgl_sampai ? date('Y-m-d', strtotime($data_tracking[0]->tgl_sampai)) : '' ?>">
                    </div>
                </div>
                <div class="col-md-12 my-2">
                    <div class="form-group">
                        <label class="font-weight-bold mb-1">Link Resi / Pelacakan</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-link"></i></span>
                            </div>
                            <input type="text" class="form-control" name="link_resi" value="<?= $data_tracking[0]->link_resi ?>" placeholder="https://...">
                        </div>
                        <small class="text-muted">Masukkan link lengkap tracking dari ekspedisi jika ada.</small>
                    </div>
                </div>

                <div class="col-md-12 my-2">
                    <div class="form-group">
                        <label class="font-weight-bold mb-1">Keterangan Tambahan</label>
                        <textarea class="form-control" name="keterangan" rows="3" placeholder="Keterangan isi paket atau catatan pengiriman..."><?= $data_tracking[0]->keterangan ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern border-left-info mb-4">
        <div class="card-header bg-info d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold text-white">History Status Tracking</h5>
            <button type="button" class="btn btn-light btn-sm font-weight-bold text-info" data-toggle="modal" data-target="#modalAddStatus">
                <i class="fas fa-plus"></i> Tambah Status
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-dark text-white text-center">
                            <th width="15%">Tanggal</th>
                            <th width="15%">Status</th>
                            <th>Detail / Keterangan</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_status as $row) : ?>
                            <tr data-id="<?= $row->id ?>">
                                <!-- TANGGAL -->
                                <td>
                                    <span class="view-col"><?= date('d/m/Y H:i', strtotime($row->created_at)) ?></span>
                                    <input type="datetime-local"
                                        class="form-control form-control-sm edit-col"
                                        name="edit_tgl_<?= $row->id ?>"
                                        value="<?= date('Y-m-d\TH:i', strtotime($row->created_at)) ?>">
                                </td>

                                <!-- STATUS -->
                                <td>
                                    <span class="view-col">
                                        <span class="badge badge-info"><?= $list_status[$row->id_status] ?? 'Unknown' ?></span>
                                    </span>
                                    <select class="form-control form-control-sm edit-col"
                                        name="edit_status_<?= $row->id ?>"
                                        onchange="togglePanelDiterima(<?= $row->id ?>, this.value)">
                                        <?php foreach ($list_status as $v => $l): ?>
                                            <option value="<?= $v ?>" <?= ($row->id_status == $v) ? 'selected' : '' ?>>
                                                <?= $l ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>

                                <!-- DETAIL -->
                                <td>
                                    <div class="view-col">
                                        <?= nl2br(htmlspecialchars($row->keterangan_konfirmasi)) ?>
                                        <?php if ($row->id_status == 5 && !empty($row->nama_penerima)): ?>
                                            <br><small class="text-success font-weight-bold">
                                                <i class="fas fa-user-check"></i> Diterima oleh: <?= htmlspecialchars($row->nama_penerima) ?>
                                                <?php if (!empty($row->tgl_penerima)): ?>
                                                    <br><i class="fas fa-calendar-check"></i> Tanggal: <?= date('d-M-Y', strtotime($row->tgl_penerima)) ?>
                                                <?php endif; ?>
                                                <?php if (!empty($row->bukti_penerima)): ?>
                                                    <br><i class="fas fa-file-image"></i> Bukti:
                                                    <?php if (filter_var($row->bukti_penerima, FILTER_VALIDATE_URL)): ?>
                                                        <a href="<?= htmlspecialchars($row->bukti_penerima) ?>" target="_blank" class="text-primary">
                                                            <i class="fas fa-external-link-alt"></i> Lihat Bukti
                                                        </a>
                                                    <?php else: ?>
                                                        <?= htmlspecialchars($row->bukti_penerima) ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="edit-col">
                                        <textarea class="form-control form-control-sm mb-2"
                                            name="edit_ket_<?= $row->id ?>"
                                            rows="2"><?= htmlspecialchars($row->keterangan_konfirmasi) ?></textarea>

                                        <div class="panel-diterima-inline" id="panel_<?= $row->id ?>"
                                            style="<?= ($row->id_status == 5) ? 'display:block;' : '' ?>">
                                            <input type="text"
                                                class="form-control form-control-sm mb-1"
                                                name="edit_penerima_<?= $row->id ?>"
                                                value="<?= htmlspecialchars($row->nama_penerima ?? '') ?>"
                                                placeholder="Nama Penerima">
                                            <input type="date"
                                                class="form-control form-control-sm mb-1"
                                                name="edit_tgl_terima_<?= $row->id ?>"
                                                value="<?= $row->tgl_penerima ?? '' ?>">
                                            <input type="text"
                                                class="form-control form-control-sm"
                                                name="edit_bukti_<?= $row->id ?>"
                                                value="<?= htmlspecialchars($row->bukti_penerima ?? '') ?>"
                                                placeholder="Link Bukti/Foto">
                                        </div>
                                    </div>
                                </td>

                                <!-- AKSI -->
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-warning view-col" onclick="enableEdit(this)">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    <div class="btn-group edit-col">
                                        <button type="button" class="btn btn-sm btn-success" onclick="saveEdit(this, <?= $row->id ?>)">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="cancelEdit(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="form-footer d-flex justify-content-between mt-4 pt-3 border-top">
        <a href="<?= base_url('tracking/detail_dokumen/' . $id_kirim_enc) ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-success btn-lg px-4" id="btnSimpan">
            <i class="fas fa-save mr-1"></i> Simpan Semua Perubahan
        </button>
    </div>
</form>

<!-- Modal Tambah Status -->
<div class="modal fade" id="modalAddStatus" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">Tambah History Status Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formAddStatus">
                <div class="modal-body">
                    <input type="hidden" name="id_kirim" value="<?= $id_kirim_enc ?>">
                    <div class="form-group">
                        <label class="font-weight-bold">Tanggal & Waktu</label>
                        <input type="datetime-local" class="form-control" name="created_at" value="<?= date('Y-m-d\TH:i') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="status_add" name="status" required onchange="toggleModalPanel()">
                            <option value="">-- Pilih Status --</option>
                            <?php foreach ($list_status as $val => $label): ?>
                                <option value="<?= $val ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Keterangan</label>
                        <textarea class="form-control" name="keterangan_konfirmasi" rows="3"></textarea>
                    </div>
                    <div id="panel_modal" style="display:none; background: #eef7ff; padding: 15px; border-radius: 8px; border: 1px dashed #3498db;">
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Nama Penerima</label>
                            <input type="text" name="nama_penerima" class="form-control form-control-sm">
                        </div>
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Tanggal Terima</label>
                            <input type="date" name="tgl_penerima" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold">Link Bukti/Foto</label>
                            <textarea name="bukti_penerima" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnAddSave">Simpan Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        if ($.fn.select2) {
            $('.select2-init').select2({
                theme: 'bootstrap',
                width: '100%',
                placeholder: '-- Pilih --'
            });
        }

        // Auto-fill Customer
        $('#nama_customer').on('change', function() {
            const opt = $(this).find('option:selected');
            $('#pic').val(opt.data('pic') || '');
            $('#alamat').val(opt.data('alamat') || '');
            $('#nama_customer_text').val(opt.text().trim());

            if ($(this).val()) {
                $('#customerMissingAlert').fadeOut();
            } else {
                $('#customerMissingAlert').fadeIn();
            }
        });

        // Submit Form Utama
        $('#formEditFull').on('submit', function(e) {
            e.preventDefault();

            if (!$('#nama_customer').val()) {
                Swal.fire('Peringatan', 'Silahkan pilih customer!', 'warning');
                return;
            }

            const btn = $('#btnSimpan');
            const html = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: "<?= base_url('tracking/update_full_dokumen') ?>",
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire('Berhasil!', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                        btn.prop('disabled', false).html(html);
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan.', 'error');
                    btn.prop('disabled', false).html(html);
                }
            });
        });

        // Submit Modal Add Status
        $('#formAddStatus').on('submit', function(e) {
            e.preventDefault();

            if (!$('#status_add').val()) {
                Swal.fire('Peringatan', 'Status harus dipilih!', 'warning');
                return;
            }

            const btn = $('#btnAddSave');
            const text = btn.text();
            btn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: "<?= base_url('tracking/add_status_dokumen_admin') ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire('Berhasil!', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                        btn.prop('disabled', false).text(text);
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan.', 'error');
                    btn.prop('disabled', false).text(text);
                }
            });
        });
    });

    // ========== FUNGSI SEDERHANA ==========

    function enableEdit(button) {
        // Ambil ID dari data-id attribute di tr
        const tr = $(button).closest('tr');
        const id = tr.data('id');
        console.log('Enable edit:', id);

        // Tutup semua edit mode yang lain
        $('tr').removeClass('is-editing');

        // Buka edit mode untuk row ini
        tr.addClass('is-editing');
    }

    function cancelEdit(button) {
        // Ambil ID dari data-id attribute di tr
        const tr = $(button).closest('tr');
        const id = tr.data('id');
        console.log('Cancel edit:', id);

        // Tutup edit mode
        tr.removeClass('is-editing');
    }

    function togglePanelDiterima(id, status) {
        console.log('Toggle panel:', id, status);

        if (status == "5") {
            $('#panel_' + id).slideDown(200);
        } else {
            $('#panel_' + id).slideUp(200);
        }
    }

    function saveEdit(button, id) {
        console.log('Save edit:', id);

        const tr = $(button).closest('tr');

        // Ambil data dari form dengan name yang unik per row
        const data = {
            id_history: id,
            status_edit: $('[name="edit_status_' + id + '"]').val(),
            tgl_status: $('[name="edit_tgl_' + id + '"]').val(),
            keterangan: $('[name="edit_ket_' + id + '"]').val(),
            nama_penerima: $('[name="edit_penerima_' + id + '"]').val(),
            tgl_penerima: $('[name="edit_tgl_terima_' + id + '"]').val(),
            bukti_penerima: $('[name="edit_bukti_' + id + '"]').val(),
            type: 'dokumen'
        };

        console.log('Data:', data);

        if (!data.status_edit || !data.tgl_status) {
            Swal.fire('Peringatan', 'Status dan tanggal harus diisi!', 'warning');
            return;
        }

        const btn = tr.find('.btn-success');
        const html = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: "<?= base_url('tracking/update_status_history') ?>",
            type: "POST",
            data: data,
            dataType: "json",
            success: function(res) {
                console.log('Response:', res);

                if (res.status === 'success') {
                    Swal.fire('Berhasil!', res.message, 'success').then(() => location.reload());
                } else if (res.status === 'warning') {
                    Swal.fire('Info', res.message, 'info');
                    cancelEdit(id);
                    btn.prop('disabled', false).html(html);
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                    btn.prop('disabled', false).html(html);
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                Swal.fire('Error', 'Gagal menyimpan data.', 'error');
                btn.prop('disabled', false).html(html);
            }
        });
    }

    function toggleModalPanel() {
        if ($('#status_add').val() == "5") {
            $('#panel_modal').slideDown(200);
        } else {
            $('#panel_modal').slideUp(200);
        }
    }
</script>