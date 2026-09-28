<!-- View: v_pm_detail.php - Detail & History Preventif Maintenance -->

<?php
$details = isset($details) ? $details : [];
$pm = isset($pm) ? $pm : null;
?>

<style>
    .pm-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 22px 24px;
        border-radius: 10px;
        margin-bottom: 25px;
    }

    .pm-header h2 {
        margin: 0 0 10px 0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-top: 14px;
        align-items: start;
    }

    .info-item {
        background: rgba(255, 255, 255, 0.1);
        padding: 12px 14px;
        border-radius: 5px;
        min-height: 78px;
    }

    .info-label {
        font-size: 11px;
        opacity: 0.9;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 600;
        line-height: 1.35;
    }

    .detail-summary {
        margin-bottom: 14px;
    }

    .detail-summary .btn {
        min-width: 150px;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #ddd;
    }

    .timeline-item {
        margin-bottom: 18px;
        position: relative;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -25px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #667eea;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #667eea;
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 14px;
        border-radius: 5px;
        border-left: 3px solid #667eea;
    }

    .timeline-content img,
    .timeline-content video,
    .timeline-content table {
        max-width: 100%;
        height: auto;
    }

    .timeline-meta {
        font-size: 12px;
        color: #999;
        margin-bottom: 8px;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.open {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-badge.progress {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .status-badge.closed {
        background-color: #d4edda;
        color: #155724;
    }
</style>

<header class="page-header">
    <h2><i class="icons icon-user-follow"></i>&nbsp;Preventif Maintenance</h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>Detail Preventif Maintenance : <?= htmlspecialchars($pm->kode_pm) ?></span></li>
        </ol>
    </div>
</header>

<div class="container-fluid">
    <!-- Header -->
    <div class="pm-header">
        <h2><?= htmlspecialchars($pm->subject) ?></h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Kode PM</div>
                <div class="info-value"><?= $pm->kode_pm ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <?php
                    $statusClass = '';
                    if ($pm->status_pm == 1) $statusClass = 'open';
                    elseif ($pm->status_pm == 2) $statusClass = 'progress';
                    elseif ($pm->status_pm == 3) $statusClass = 'closed';
                    ?>
                    <span class="status-badge <?= $statusClass ?>">
                        <?php
                        $labels = [1 => 'Open', 2 => 'In Progress', 3 => 'Closed', 4 => 'Cancelled'];
                        echo $labels[$pm->status_pm] ?? 'Unknown';
                        ?>
                    </span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Pihak Ketiga</div>
                <div class="info-value"><?= htmlspecialchars($pm->nama_pihak_ketiga_full ?? $pm->nama_pihak_ketiga ?? '-') ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Penerima PM</div>
                <div class="info-value"><?= htmlspecialchars($pm->nama_penerima ?? '-') ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Pelanggan</div>
                <div class="info-value"><?= htmlspecialchars($pm->nama_pelanggan ?? '-') ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Kategori</div>
                <div class="info-value"><?= htmlspecialchars($pm->nama_topik ?? '-') ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Prioritas</div>
                <div class="info-value">
                    <?php
                    $priorityLabels = [1 => 'Low', 2 => 'Medium', 3 => 'High', 4 => 'Urgent'];
                    echo $priorityLabels[$pm->prioritas] ?? 'N/A';
                    ?>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Contact Person</div>
                <div class="info-value"><?= htmlspecialchars($pm->nomer_cp ?? '-') ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Tanggal PM</div>
                <div class="info-value"><?= date('d-m-Y', strtotime($pm->tanggal_pm)) ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Pembuat</div>
                <div class="info-value"><?= htmlspecialchars($pm->nama_pembuat) ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Dibuat Pada</div>
                <div class="info-value"><?= date('d-m-Y H:i', strtotime($pm->created_at)) ?></div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Deskripsi -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Deskripsi</h6>
                </div>
                <div class="card-body">
                    <?= $pm->deskripsi ? html_entity_decode($pm->deskripsi) : '<em class="text-muted">Tidak ada deskripsi</em>' ?>
                </div>
            </div>

            <!-- Catatan Visit -->
            <?php if ($pm->catatan_visit): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Catatan Visit</h6>
                    </div>
                    <div class="card-body">
                        <?= html_entity_decode($pm->catatan_visit) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- History/Timeline -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">History & Update</h6>
                </div>
                <div class="card-body">
                    <div class="timeline" id="timelineUpdateContent">
                        <?php if (count($details) > 0): ?>
                            <?php foreach ($details as $detail): ?>
                                <div class="timeline-item">
                                    <div class="timeline-meta">
                                        <strong><?= htmlspecialchars($detail->nama_pembuat) ?></strong> -
                                        <?= date('d-m-Y H:i', strtotime($detail->created_at)) ?>
                                    </div>
                                    <div class="timeline-content">
                                        <?= html_entity_decode($detail->detail) ?>
                                        <?php if ($detail->file_update): ?>
                                            <div class="mt-2">
                                                <a href="<?= base_url('uploads/' . $detail->file_update) ?>" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-download"></i> Download File
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">Belum ada update</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Aksi</h6>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-success btn-block mb-2" id="btn-show-add-form" data-id="<?= encrypt($pm->id_pm) ?>">
                        <i class="fas fa-plus"></i> Tambah Update
                    </button>
                    <a href="<?= base_url('preventif-maintenance/edit/' . encrypt($pm->id_pm)) ?>" class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-edit"></i> Edit PM
                    </a>
                    <a href="<?= base_url('preventif-maintenance') ?>" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Kembali ke List
                    </a>
                </div>
            </div>

            <!-- Info Detail -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Informasi Detail</h6>
                </div>
                <div class="card-body" style="font-size: 13px;">
                    <div class="mb-3">
                        <strong>Waktu Mulai:</strong>
                        <br><?= $pm->waktu_mulai ? date('d-m-Y H:i', strtotime($pm->waktu_mulai)) : '-' ?>
                    </div>
                    <div class="mb-3">
                        <strong>Waktu Selesai:</strong>
                        <br><?= $pm->waktu_selesai ? date('d-m-Y H:i', strtotime($pm->waktu_selesai)) : '-' ?>
                    </div>
                    <div class="mb-3">
                        <strong>Lokasi:</strong>
                        <br><?= htmlspecialchars(($pm->kota ?? '') . ', ' . ($pm->provinsi ?? '')) ?>
                    </div>
                    <div class="mb-3">
                        <strong>File Pendukung:</strong>
                        <br><?= $pm->file_pendukung ? '<a href="' . htmlspecialchars($pm->file_pendukung) . '" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file"></i> Buka</a>' : '-' ?>
                    </div>
                    <div class="mb-3">
                        <strong>File Invoice:</strong>
                        <br><?= $pm->file_invoice ? '<a href="' . htmlspecialchars($pm->file_invoice) . '" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file"></i> Buka</a>' : '-' ?>
                    </div>
                    <div class="mb-3">
                        <strong>Nama CP:</strong>
                        <br><?= htmlspecialchars($pm->nama_cp ?? '-') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Tambah Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAddUpdate" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_pm" value="<?= encrypt($pm->id_pm) ?>">
                    <div class="form-group mb-3">
                        <label class="form-control-label">Update/Progress <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote respon" name="detail" placeholder="Masukkan update progress PM"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-control-label">Ubah Status (Opsional)</label>
                        <select class="form-control respon" name="status_pm">
                            <option value="">-- Tidak Ubah Status --</option>
                            <option value="1" <?= $pm->status_pm == 1 ? 'selected' : '' ?>>Open</option>
                            <option value="2" <?= $pm->status_pm == 2 ? 'selected' : '' ?>>In Progress</option>
                            <option value="3">Closed</option>
                            <option value="4">Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-control-label">File Pendukung</label>
                        <input type="file" class="form-control respon" name="file_update" accept=".pdf,.doc,.docx,.jpg,.png,.xls,.xlsx">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success btn-save">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function initializePMDetail() {
        if (typeof $ === 'undefined') {
            setTimeout(initializePMDetail, 100);
            return;
        }

        // Pastikan tombol dan form selalu aktif meskipun plugin editor belum siap.
        $(document).off('click.pmDetail', '#btn-show-add-form').on('click.pmDetail', '#btn-show-add-form', function() {
            $('#formAddUpdate')[0].reset();
            $('#main-modal').modal('show');
        });

        $(document).off('submit.pmDetail', '#formAddUpdate').on('submit.pmDetail', '#formAddUpdate', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: '<?= base_url('preventif-maintenance/addUpdate') ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: res.message || res.msg || 'Update berhasil ditambahkan',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: res.message || res.msg || 'Gagal menyimpan update',
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Terjadi kesalahan!',
                        text: xhr.responseText || 'Error system',
                        icon: 'error'
                    });
                }
            });
        });

        if (typeof $.fn.summernote !== 'undefined') {
            $('.summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
        }

        $.ajax({
            url: '<?= base_url('preventif-maintenance/pagination/detail') ?>',
            type: 'POST',
            data: {
                id_pm: '<?= encrypt($pm->id_pm) ?>'
            },
            success: function(response) {
                $('#timelineUpdateContent').html(response);
            }
        });
    }

    // Initialize when ready
    initializePMDetail();
</script>