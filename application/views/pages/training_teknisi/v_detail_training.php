<header class="page-header">
    <h2><i class="fas fa-book-reader"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    <!-- Form Detail / Edit -->
    <div class="col-lg-6">
        <section class="card">
            <header class="card-header bg-dark text-white">
                <h2 class="card-title text-white">Rincian Penugasan Training</h2>
            </header>
            <div class="card-body">
                <?= form_open('training_teknisi/update/detail', array('id' => 'form-detail-training', 'autocomplete' => 'off')); ?>
                <input type="hidden" id="id_training" name="id_training" value="<?= $data_training[0]->id_training ?>">

                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold">Kode Training</label>
                    <input class="form-control" type="text" value="<?= $data_training[0]->kode_training ?>" readonly>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold">Nama Rekanan</label>
                    <input class="form-control" type="text" name="rekanan" value="<?= $data_training[0]->rekanan ?>" <?= sessPenggunaId() == $data_training[0]->created_by || isAdmin() ? '' : 'readonly' ?>>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold">Contact Person</label>
                    <input class="form-control" type="text" name="contact_person" value="<?= $data_training[0]->contact_person ?>" <?= sessPenggunaId() == $data_training[0]->created_by || isAdmin() ? '' : 'readonly' ?>>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold">Subject Training</label>
                    <input class="form-control" type="text" name="subject" value="<?= $data_training[0]->subject ?>" <?= sessPenggunaId() == $data_training[0]->created_by || isAdmin() ? '' : 'readonly' ?>>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold">Kategori Alat</label>
                    <?php if (sessPenggunaId() == $data_training[0]->created_by || isAdmin()) { ?>
                        <select class="form-control" name="kategori" required>
                            <?php foreach ($kategori as $row) { ?>
                                <option value="<?= $row->id_topik ?>" <?= $row->id_topik == $data_training[0]->id_topik ? 'selected' : '' ?>><?= $row->nama ?></option>
                            <?php } ?>
                        </select>
                    <?php } else { ?>
                        <input class="form-control" type="text" value="<?= $data_training[0]->nama_topik ?>" readonly>
                    <?php } ?>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Status Training</label>
                            <?php if (isAdmin() || sessPenggunaId() == $data_training[0]->id_teknisi || sessPenggunaId() == $data_training[0]->created_by) { ?>
                                <select class="form-control" id="status_training" name="status_training">
                                    <option value="1" <?= $data_training[0]->status_training == 1 ? 'selected' : '' ?>>Baru</option>
                                    <option value="2" <?= $data_training[0]->status_training == 2 ? 'selected' : '' ?>>On Progress</option>
                                    <option value="3" <?= $data_training[0]->status_training == 3 ? 'selected' : '' ?>>Revisi</option>
                                    <option value="4" <?= $data_training[0]->status_training == 4 ? 'selected' : '' ?>>Selesai</option>
                                </select>
                            <?php } else { ?>
                                <?php
                                $status_text = '';
                                if ($data_training[0]->status_training == 1) $status_text = 'Baru';
                                elseif ($data_training[0]->status_training == 2) $status_text = 'On Progress';
                                elseif ($data_training[0]->status_training == 3) $status_text = 'Revisi';
                                else $status_text = 'Selesai';
                                ?>
                                <input class="form-control" type="text" value="<?= $status_text ?>" readonly>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Prioritas</label>
                            <?php if (isAdmin() || sessPenggunaId() == $data_training[0]->created_by) { ?>
                                <select class="form-control" id="prioritas" name="prioritas">
                                    <option value="1" <?= $data_training[0]->prioritas == 1 ? 'selected' : '' ?>>Low</option>
                                    <option value="2" <?= $data_training[0]->prioritas == 2 ? 'selected' : '' ?>>Medium</option>
                                    <option value="3" <?= $data_training[0]->prioritas == 3 ? 'selected' : '' ?>>Priority</option>
                                </select>
                            <?php } else { ?>
                                <?php
                                $prioritas_text = '';
                                if ($data_training[0]->prioritas == 1) $prioritas_text = 'Low';
                                elseif ($data_training[0]->prioritas == 2) $prioritas_text = 'Medium';
                                else $prioritas_text = 'Priority';
                                ?>
                                <input class="form-control" type="text" value="<?= $prioritas_text ?>" readonly>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold">Teknisi</label>
                    <input class="form-control" type="text" value="<?= $data_training[0]->nama_teknisi ?>" readonly>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold">Waktu Pelaksanaan</label>
                    <input class="form-control" type="text" value="<?= date('d-m-Y', strtotime($data_training[0]->waktu_mulai)) ?> s/d <?= date('d-m-Y', strtotime($data_training[0]->waktu_selesai)) ?>" readonly>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold">File Pendukung / Link Drive</label>
                    <?php if (!empty($data_training[0]->file_pendukung) && $data_training[0]->file_pendukung != '-') { ?>
                        <div class="mb-1">
                            <a href="<?= $data_training[0]->file_pendukung ?>" target="_blank" class="btn btn-xs btn-info"><i class="fas fa-external-link-alt"></i> Buka Link Drive</a>
                        </div>
                    <?php } ?>
                    <input class="form-control" type="text" name="file_pendukung" value="<?= $data_training[0]->file_pendukung ?>" <?= sessPenggunaId() == $data_training[0]->created_by || isAdmin() ? '' : 'readonly' ?>>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold">Deskripsi Penugasan</label>
                    <textarea class="form-control" name="deskripsi" rows="3" <?= sessPenggunaId() == $data_training[0]->created_by || isAdmin() ? '' : 'readonly' ?>><?= $data_training[0]->deskripsi ?></textarea>
                </div>

                <div class="mt-4">
                    <button type="button" onclick="window.history.back()" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</button>
                </div>
                <?= form_close(); ?>
            </div>
        </section>
    </div>

    <!-- Log Timeline & Update Progress -->
    <div class="col-lg-6">
        <!-- Form Update Log (Hanya untuk Admin atau Teknisi yang ditunjuk) -->
        <?php if (isAdmin() || sessPenggunaId() == $data_training[0]->id_teknisi || sessPenggunaId() == $data_training[0]->created_by) { ?>
            <section class="card mb-4">
                <header class="card-header bg-primary text-white">
                    <h2 class="card-title text-white">Update Progres Training</h2>
                </header>
                <div class="card-body">
                    <?= form_open('training_teknisi/update/logTraining', array('id' => 'form-log-training', 'autocomplete' => 'off')); ?>
                    <input type="hidden" name="id_training" value="<?= $data_training[0]->id_training ?>">

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Catatan Progres <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="deskripsi" placeholder="Masukkan laporan progres training..." rows="3" required></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Link Lampiran Dokumen / Drive</label>
                        <input class="form-control" type="text" name="attachment" placeholder="Masukkan link dokumen pendukung jika ada...">
                    </div>

                    <button type="submit" class="btn btn-success" id="btn-save-log"><i class="fas fa-paper-plane"></i> Kirim Update</button>
                    <?= form_close(); ?>
                </div>
            </section>
        <?php } ?>

        <!-- Log Timeline -->
        <section class="card">
            <header class="card-header bg-info text-white">
                <h2 class="card-title text-white">Riwayat Aktivitas Training</h2>
            </header>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="timeline-3">
                            <?php foreach ($data_update as $row) { ?>
                                <?php
                                $status = '';
                                if ($row->status == 1) $status = 'TRAINING CREATED';
                                elseif ($row->status == 2) $status = 'PROGRESS UPDATE';
                                elseif ($row->status == 3) $status = 'STATUS CHANGED';
                                ?>
                                <li>
                                    <a><b><?= $row->nama_pembuat ?></b></a>
                                    <a class="float-right text-muted"><small><?= date('d-M-Y | H:i:s', strtotime($row->waktu)) ?></small></a>
                                    <p class="mt-2 mb-1"><?= $row->update ?></p>
                                    <?php if (!empty($row->file_update) && $row->file_update != '-') { ?>
                                        <div class="mb-2">
                                            <a href="<?= $row->file_update ?>" target="_blank" class="btn btn-xs btn-outline-info"><i class="fas fa-paperclip"></i> Lihat Lampiran</a>
                                        </div>
                                    <?php } ?>
                                    <span class="text-primary font-weight-bold" style="font-size: 11px;"><?= $status ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/css/timeline.css') ?>">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ajax Status Change
    $('#status_training').change(function() {
        var status = $(this).val();
        var id = '<?= encrypt($data_training[0]->id_training) ?>';
        $.ajax({
            url: '<?= base_url("training_teknisi/update/status_training") ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_training: id,
                value: status,
                csrf_token: token
            },
            success: function(response) {
                if (response.status == 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            }
        });
    });

    // Ajax Priority Change
    $('#prioritas').change(function() {
        var priority = $(this).val();
        var id = '<?= encrypt($data_training[0]->id_training) ?>';
        $.ajax({
            url: '<?= base_url("training_teknisi/update/prioritas") ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_training: id,
                value: priority,
                csrf_token: token
            },
            success: function(response) {
                if (response.status == 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            }
        });
    });

    // Submit Log Progres
    $('#form-log-training').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var data = form.serialize();

        $('#btn-save-log').attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'JSON',
            success: function(response) {
                if (response.status == 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                    $('#btn-save-log').attr('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim Update');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
                $('#btn-save-log').attr('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim Update');
            }
        });
    });
});
</script>
