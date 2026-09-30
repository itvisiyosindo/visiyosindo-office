<header class="page-header">
    <h2><i class="icons fas fa-ticket-alt"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <!-- LEFT COLUMN: TICKET DETAILS -->
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Detail Tiket</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" style="font-size: 13px;">
                        <tr>
                            <th style="width: 35%;">Kode Tiket</th>
                            <td><strong><?= $ticket->kode_tiket ?></strong></td>
                        </tr>
                        <tr>
                            <th>Pelapor</th>
                            <td><?= $ticket->nama_pembuat ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <td><?= date('d-m-Y H:i', strtotime($ticket->created_at)) ?></td>
                        </tr>
                        <tr>
                            <th>Aset Terkait</th>
                            <td>
                                <?php if (!empty($ticket->nama_aset)) { ?>
                                    <strong><?= $ticket->nama_aset ?></strong> (<?= $ticket->kode_aset ?>)<br>
                                    <small class="text-muted">Kategori: <?= $ticket->kategori_aset ?></small>
                                <?php } else { ?>
                                    <span class="text-muted">Lainnya / Umum</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Subjek Kendala</th>
                            <td><strong><?= $ticket->subject ?></strong></td>
                        </tr>
                        <tr>
                            <th>Tingkat Prioritas</th>
                            <td>
                                <?php
                                $prio = isset($ticket->prioritas) ? $ticket->prioritas : 'Rendah';
                                $prio_badges = [
                                    'Rendah' => 'badge-info',
                                    'Sedang' => 'badge-warning',
                                    'Tinggi' => 'badge-danger'
                                ];
                                $badge_class = isset($prio_badges[$prio]) ? $prio_badges[$prio] : 'badge-secondary';
                                ?>
                                <span class="badge <?= $badge_class ?> font-weight-bold" style="font-size: 11px;"><?= $prio ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Deskripsi Masalah</th>
                            <td><?= nl2br(htmlentities($ticket->deskripsi)) ?></td>
                        </tr>
                        <tr>
                            <th>File Lampiran</th>
                            <td>
                                <?php if (!empty($ticket->file_pendukung)) {
                                    $is_url = filter_var($ticket->file_pendukung, FILTER_VALIDATE_URL) !== FALSE;
                                    $link = $is_url ? $ticket->file_pendukung : base_url('uploads/' . $ticket->file_pendukung);
                                ?>
                                    <a href="<?= $link ?>" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-external-link-alt"></i> Buka Lampiran / Google Drive
                                    </a>
                                <?php } else { ?>
                                    <span class="text-muted">-</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Teknisi Ditugaskan</th>
                            <td>
                                <?php if (!empty($ticket->nama_penerima)) { ?>
                                    <span class="badge badge-success font-weight-bold" style="font-size: 12px;"><?= $ticket->nama_penerima ?></span>
                                <?php } else { ?>
                                    <span class="badge badge-danger font-weight-bold" style="font-size: 12px;">Belum Ditugaskan</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Status Tiket</th>
                            <td>
                                <?php
                                $badges = [
                                    1 => '<span class="badge badge-warning" style="font-size: 12px;">Open</span>',
                                    2 => '<span class="badge badge-info" style="font-size: 12px;">In Progress</span>',
                                    3 => '<span class="badge badge-success" style="font-size: 12px;">Solved</span>',
                                    4 => '<span class="badge badge-secondary" style="font-size: 12px;">Closed</span>',
                                    5 => '<span class="badge badge-danger" style="font-size: 12px;">Rejected</span>'
                                ];
                                echo isset($badges[$ticket->status_tiket]) ? $badges[$ticket->status_tiket] : '';
                                ?>
                            </td>
                        </tr>
                        <?php if (!empty($ticket->solusi)) { ?>
                            <tr class="table-success">
                                <th>Solusi Akhir</th>
                                <td><strong><?= nl2br(htmlentities($ticket->solusi)) ?></strong></td>
                            </tr>
                        <?php } ?>
                    </table>

                    <!-- Back Buttons -->
                    <div class="mt-3">
                        <a href="<?= base_url('it_maintenance/my_ticket') ?>" class="btn btn-default btn-block"><i class="fas fa-arrow-left"></i> Kembali ke Tiket Saya</a>
                        <?php
                        $isITStaff = isAdmin() || isCRO() || isGa() || sessPenggunaId() == '755' || sessPenggunaId() == '769' || sessPenggunaId() == '107';
                        if ($isITStaff) {
                        ?>
                            <a href="<?= base_url('it_maintenance/data_ticket') ?>" class="btn btn-dark btn-block mt-2"><i class="fas fa-list"></i> Lihat Semua Tiket (Admin)</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: ACTIONS & TIMELINE -->
        <div class="col-md-7">
            <!-- 1. ASSIGNMENT FORM (IT STAFF ONLY & OPEN STATUS) -->
            <?php if ($isITStaff && $ticket->status_tiket == 1) { ?>
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-user-plus"></i> Tugaskan Teknisi IT</h6>
                    </div>
                    <div class="card-body">
                        <?= form_open('it_maintenance/assign_ticket', array('id' => 'form-assign-ticket')); ?>
                        <input type="hidden" name="id_ticket" value="<?= encrypt($ticket->id_ticket) ?>">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Pilih Teknisi IT <span class="text-danger">*</span></label>
                            <select class="form-control" name="id_penerima" required>
                                <option value="">-- Pilih Teknisi --</option>
                                <?php foreach ($technicians as $tech) { ?>
                                    <option value="<?= $tech->pengguna_id ?>"><?= htmlspecialchars($tech->nama) ?><?= !empty($tech->jabatan) ? ' (' . htmlspecialchars($tech->jabatan) . ')' : '' ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-submit-assign btn-block"><i class="fas fa-check"></i> Tugaskan & Ubah Status Jadi In Progress</button>
                        <?= form_close(); ?>
                    </div>
                </div>
            <?php } ?>

            <!-- 2. UPDATE STATUS & PROGRESS FORM (IT STAFF ONLY & ACTIVE STATUSES) -->
            <?php if ($isITStaff && in_array($ticket->status_tiket, [1, 2])) { ?>
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-edit"></i> Update Progress & Status Tiket</h6>
                    </div>
                    <div class="card-body">
                        <?= form_open_multipart('it_maintenance/save_ticket_update', array('id' => 'form-update-ticket')); ?>
                        <input type="hidden" name="id_ticket" value="<?= encrypt($ticket->id_ticket) ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Ubah Status Ke <span class="text-danger">*</span></label>
                                    <select class="form-control" name="status_tiket" id="select-status" required>
                                        <option value="2" <?= $ticket->status_tiket == 2 ? 'selected' : '' ?>>In Progress</option>
                                        <option value="3">Solved (Selesai)</option>
                                        <option value="4">Closed (Selesai & Ditutup)</option>
                                        <option value="5">Rejected (Ditolak)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Link Google Drive Lampiran Update (Opsional)</label>
                                    <input type="url" class="form-control" name="file_update" placeholder="https://drive.google.com/...">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Catatan Progress / Update <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="detail" rows="3" placeholder="Sebutkan tindakan yang dilakukan, part yang diganti, dll." required></textarea>
                        </div>

                        <!-- Solusi field (hanya tampil jika status Solved/Closed) -->
                        <div class="form-group mb-3 d-none" id="solusi-wrapper">
                            <label class="font-weight-bold">Solusi / Langkah Perbaikan Akhir <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="solusi" id="input-solusi" rows="3" placeholder="Deskripsikan solusi akhir agar dapat dibaca oleh pelapor."></textarea>
                        </div>

                        <button type="submit" class="btn btn-success btn-submit-update btn-block"><i class="fas fa-save"></i> Simpan Update Progress</button>
                        <?= form_close(); ?>
                    </div>
                </div>
            <?php } ?>

            <!-- 3. TIMELINE OF UPDATES -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="fas fa-history"></i> Log Progress & Timeline Tiket</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($updates)) { ?>
                        <div class="timeline timeline-simple mt-3">
                            <?php foreach ($updates as $up) { ?>
                                <div class="tm-datetime">
                                    <span class="tm-datetime-date"><?= date('d-m-Y', strtotime($up->created_at)) ?></span>
                                    <span class="tm-datetime-time"><?= date('H:i', strtotime($up->created_at)) ?></span>
                                </div>
                                <div class="tm-title" style="padding-left: 20px; border-left: 3px solid #0088cc; margin-bottom: 25px; position: relative;">
                                    <div style="font-weight: bold; font-size: 14px;">
                                        <?= $up->nama_pembuat ?> <span class="text-muted" style="font-weight: normal; font-size: 11px;">(<?= $up->level_pembuat ?>)</span>
                                    </div>
                                    <div class="text-muted" style="font-size: 11px; margin-bottom: 5px;">
                                        Ubah Status ke:
                                        <?php
                                        $up_badges = [
                                            1 => '<span class="badge badge-warning">Open</span>',
                                            2 => '<span class="badge badge-info">In Progress</span>',
                                            3 => '<span class="badge badge-success">Solved</span>',
                                            4 => '<span class="badge badge-secondary">Closed</span>',
                                            5 => '<span class="badge badge-danger">Rejected</span>'
                                        ];
                                        echo isset($up_badges[$up->status]) ? $up_badges[$up->status] : '';
                                        ?>
                                    </div>
                                    <div style="font-size: 13px; background-color: #f7f7f7; padding: 10px; border-radius: 4px;">
                                        <?= nl2br($up->detail) ?>

                                        <?php if (!empty($up->file_update)) {
                                            $is_url = filter_var($up->file_update, FILTER_VALIDATE_URL) !== FALSE;
                                            $link = $is_url ? $up->file_update : base_url('uploads/' . $up->file_update);
                                        ?>
                                            <div class="mt-2">
                                                <a href="<?= $link ?>" target="_blank" class="btn btn-xs btn-primary">
                                                    <i class="fas fa-external-link-alt"></i> Buka Lampiran Update / Google Drive
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-folder-open fa-2x"></i>
                            <p class="mt-2">Belum ada log progress atau update untuk tiket ini.</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Solusi field based on status choice
        $('#select-status').change(function() {
            var status = $(this).val();
            if (status == '3' || status == '4') {
                $('#solusi-wrapper').removeClass('d-none');
                $('#input-solusi').prop('required', true);
            } else {
                $('#solusi-wrapper').addClass('d-none');
                $('#input-solusi').prop('required', false).val('');
            }
        });

        // Submit Assignment
        $('#form-assign-ticket').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = $('.btn-submit-assign');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'JSON',
                success: function(resp) {
                    if (resp.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: resp.message || 'Teknisi berhasil ditugaskan',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', resp.message || 'Gagal menugaskan teknisi', 'error');
                        btn.prop('disabled', false).html('<i class="fas fa-check"></i> Tugaskan & Ubah Status');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error!', 'Terjadi kesalahan sistem: ' + error, 'error');
                    btn.prop('disabled', false).html('<i class="fas fa-check"></i> Tugaskan & Ubah Status');
                }
            });
        });

        // Submit Progress Update
        $('#form-update-ticket').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = new FormData(form[0]);
            var btn = $('.btn-submit-update');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                success: function(resp) {
                    if (resp.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: resp.message || 'Progress tiket berhasil diperbarui',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', resp.message || 'Gagal menyimpan update', 'error');
                        btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Update Progress');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error!', 'Terjadi kesalahan sistem: ' + error, 'error');
                    btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Update Progress');
                }
            });
        });
    });
</script>