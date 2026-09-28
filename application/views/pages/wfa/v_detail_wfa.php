<header class="page-header">
    <h2><i class="fas fa-file-signature"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    :root {
        --detail-primary: #0f4c81;
        --detail-cyan: #1f7a8c;
        --detail-bg: #f3f7fb;
        --detail-card: #fff;
        --detail-text: #1f2937;
        --detail-muted: #64748b;
    }

    .detail-shell {
        background: linear-gradient(180deg, #eaf2fb 0%, #f7f9fc 100%);
        border-radius: 16px;
        padding: 1.15rem;
    }

    .detail-top {
        background: linear-gradient(140deg, var(--detail-primary) 0%, var(--detail-cyan) 100%);
        color: #fff;
        border-radius: 14px;
        padding: 1.1rem 1.2rem;
        margin-bottom: 1rem;
    }

    .detail-top h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .detail-top p {
        margin: 0.38rem 0 0;
        opacity: 1;
        color: #f8fbff;
        font-size: 0.9rem;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.28);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1.25fr 1fr;
        gap: 1rem;
    }

    .detail-card {
        background: var(--detail-card);
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 7px 18px rgba(15, 76, 129, 0.08);
        margin-bottom: 1rem;
    }

    .detail-card h4 {
        margin: 0 0 0.8rem;
        font-size: 1rem;
        font-weight: 700;
        color: var(--detail-text);
    }

    .kv-row {
        display: grid;
        grid-template-columns: 160px 1fr;
        gap: 0.6rem;
        border-bottom: 1px dashed #d8e3ef;
        padding: 0.5rem 0;
    }

    .kv-row:last-child {
        border-bottom: 0;
    }

    .kv-key {
        color: var(--detail-muted);
        font-size: 0.85rem;
        font-weight: 600;
    }

    .kv-value {
        color: var(--detail-text);
        font-size: 0.9rem;
    }

    .timeline {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .timeline li {
        display: flex;
        gap: 0.6rem;
        padding: 0.65rem 0;
        border-bottom: 1px dashed #d8e3ef;
    }

    .timeline li:last-child {
        border-bottom: 0;
    }

    .timeline i {
        margin-top: 0.12rem;
        font-size: 1.1rem;
    }

    .tl-ok i { color: #137a44; }
    .tl-wait i { color: #8a6500; }
    .tl-stop i { color: #b42318; }

    .badge-state {
        display: inline-flex;
        border-radius: 999px;
        padding: 0.22rem 0.62rem;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .badge-state.pending { background: #fff6df; color: #8a6500; }
    .badge-state.progress { background: #e8f2ff; color: #0f4c81; }
    .badge-state.approved { background: #e8f7ef; color: #137a44; }
    .badge-state.rejected { background: #fdecec; color: #b42318; }

    .action-box .form-control {
        border-radius: 10px;
        border: 1px solid #d6e2ef;
    }

    .action-box .form-control:focus {
        box-shadow: 0 0 0 0.14rem rgba(31, 122, 140, 0.2);
        border-color: #1f7a8c;
    }

    @media (max-width: 991px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .kv-row {
            grid-template-columns: 1fr;
            gap: 0.2rem;
        }
    }
</style>

<?php
$status_badge = '<span class="badge-state pending">Menunggu Mengetahui</span>';
if ((int) $detail->status === 1) {
    $status_badge = '<span class="badge-state progress">Menunggu Memverifikasi</span>';
} elseif ((int) $detail->status === 2) {
    $status_badge = '<span class="badge-state progress">Menunggu Menyetujui</span>';
} elseif ((int) $detail->status === 5) {
    $status_badge = '<span class="badge-state approved">Disetujui</span>';
} elseif ((int) $detail->status === 99) {
    $status_badge = '<span class="badge-state rejected">Ditolak</span>';
}

$mengetahui_step_class = 'tl-wait';
$mengetahui_step_text = 'Menunggu tindakan Mengetahui';
if ((int) $detail->status_mengetahui === 1) {
    $mengetahui_step_class = 'tl-ok';
    $mengetahui_step_text = 'Disetujui oleh Mengetahui';
} elseif ((int) $detail->status_mengetahui === 2) {
    $mengetahui_step_class = 'tl-stop';
    $mengetahui_step_text = 'Ditolak oleh Mengetahui';
}

$memverifikasi_step_class = 'tl-wait';
$memverifikasi_step_text = 'Menunggu tahap Mengetahui';
if ((int) $detail->status_mengetahui === 2) {
    $memverifikasi_step_class = 'tl-stop';
    $memverifikasi_step_text = 'Tidak diproses karena ditolak Mengetahui';
} elseif ((int) $detail->status_mengetahui === 1) {
    if ((int) $detail->status_menyetujui === 1) {
        $memverifikasi_step_class = 'tl-ok';
        $memverifikasi_step_text = 'Disetujui oleh Memverifikasi';
    } elseif ((int) $detail->status_menyetujui === 2) {
        $memverifikasi_step_class = 'tl-stop';
        $memverifikasi_step_text = 'Ditolak oleh Memverifikasi';
    } else {
        $memverifikasi_step_class = 'tl-wait';
        $memverifikasi_step_text = 'Menunggu tindakan Memverifikasi';
    }
}

$menyetujui_step_class = 'tl-wait';
$menyetujui_step_text = 'Menunggu tahap Memverifikasi';
if ((int) $detail->status === 5) {
    $menyetujui_step_class = 'tl-ok';
    $menyetujui_step_text = 'Disetujui oleh Menyetujui';
} elseif ((int) $detail->status === 2) {
    $menyetujui_step_class = 'tl-wait';
    $menyetujui_step_text = 'Menunggu tindakan Menyetujui';
} elseif ((int) $detail->status === 99 && $detail->rejected_role === 'menyetujui') {
    $menyetujui_step_class = 'tl-stop';
    $menyetujui_step_text = 'Ditolak oleh Menyetujui';
} elseif ((int) $detail->status === 99) {
    $menyetujui_step_class = 'tl-stop';
    $menyetujui_step_text = 'Tidak diproses karena ditolak pada tahap sebelumnya';
}
?>

<div class="detail-shell">
    <div class="detail-top">
        <h3><?= $detail->kode_pengajuan ?></h3>
        <p>Pengajuan WFA untuk tanggal <?= date('d-m-Y', strtotime($detail->tanggal_wfa)) ?> &nbsp;<?= $status_badge ?></p>
    </div>

    <div class="detail-grid">
        <div>
            <div class="detail-card">
                <h4><i class="bx bx-detail"></i> Informasi Pengajuan</h4>
                <div class="kv-row">
                    <div class="kv-key">Pengaju</div>
                    <div class="kv-value"><strong><?= $detail->nama_pengaju ?></strong> <?= !empty($detail->jabatan_pengaju) ? ' | ' . $detail->jabatan_pengaju : '' ?></div>
                </div>
                <div class="kv-row">
                    <div class="kv-key">Tanggal WFA</div>
                    <div class="kv-value"><?= date('d-m-Y', strtotime($detail->tanggal_wfa)) ?></div>
                </div>
                <div class="kv-row">
                    <div class="kv-key">Mengetahui</div>
                    <div class="kv-value"><?= $detail->nama_mengetahui ?><?= !empty($detail->jabatan_mengetahui) ? ' | ' . $detail->jabatan_mengetahui : '' ?></div>
                </div>
                <div class="kv-row">
                    <div class="kv-key">Memverifikasi</div>
                    <div class="kv-value"><?= $detail->nama_memverifikasi ?><?= !empty($detail->jabatan_memverifikasi) ? ' | ' . $detail->jabatan_memverifikasi : '' ?></div>
                </div>
                <div class="kv-row">
                    <div class="kv-key">Menyetujui</div>
                    <div class="kv-value"><?= $detail->nama_menyetujui ?><?= !empty($detail->jabatan_menyetujui) ? ' | ' . $detail->jabatan_menyetujui : '' ?></div>
                </div>
                <div class="kv-row">
                    <div class="kv-key">Lokasi Kerja</div>
                    <div class="kv-value"><?= !empty($detail->lokasi_kerja) ? nl2br(htmlspecialchars($detail->lokasi_kerja)) : 'Rumah' ?></div>
                </div>
                <div class="kv-row">
                    <div class="kv-key">Rencana Pekerjaan</div>
                    <div class="kv-value"><?= !empty($detail->rencana_pekerjaan) ? nl2br(htmlspecialchars($detail->rencana_pekerjaan)) : '-' ?></div>
                </div>
                <?php if ((int) $detail->status === 99 && !empty($detail->rejected_reason)): ?>
                    <div class="kv-row">
                        <div class="kv-key text-danger">Alasan Ditolak</div>
                        <div class="kv-value text-danger"><?= nl2br(htmlspecialchars($detail->rejected_reason)) ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="detail-card action-box">
                <h4><i class="bx bx-task"></i> Aksi Persetujuan</h4>
                <input type="hidden" id="id_wfa" value="<?= encrypt($detail->id) ?>">
                <textarea id="catatan_approval" class="form-control" rows="3" placeholder="Catatan persetujuan (opsional)"></textarea>

                <div class="mt-3">
                    <?php if ($can_approve_mengetahui): ?>
                        <button type="button" class="btn btn-success btn-sm btn-aksi-wfa" data-aksi="setujui_mengetahui">
                            <i class="bx bx-check"></i> Setujui sebagai Mengetahui
                        </button>
                        <button type="button" class="btn btn-danger btn-sm btn-aksi-wfa" data-aksi="tolak_mengetahui">
                            <i class="bx bx-x"></i> Tolak sebagai Mengetahui
                        </button>
                    <?php elseif ($can_approve_memverifikasi): ?>
                        <button type="button" class="btn btn-success btn-sm btn-aksi-wfa" data-aksi="setujui_memverifikasi">
                            <i class="bx bx-check"></i> Setujui sebagai Memverifikasi
                        </button>
                        <button type="button" class="btn btn-danger btn-sm btn-aksi-wfa" data-aksi="tolak_memverifikasi">
                            <i class="bx bx-x"></i> Tolak sebagai Memverifikasi
                        </button>
                    <?php elseif ($can_approve_menyetujui): ?>
                        <button type="button" class="btn btn-success btn-sm btn-aksi-wfa" data-aksi="setujui_menyetujui">
                            <i class="bx bx-check"></i> Setujui sebagai Menyetujui
                        </button>
                        <button type="button" class="btn btn-danger btn-sm btn-aksi-wfa" data-aksi="tolak_menyetujui">
                            <i class="bx bx-x"></i> Tolak sebagai Menyetujui
                        </button>
                    <?php else: ?>
                        <div class="alert alert-light mb-0">
                            Tidak ada aksi approval yang tersedia untuk Anda pada tahap ini.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div>
            <div class="detail-card">
                <h4><i class="bx bx-git-branch"></i> Progress Approval</h4>
                <ul class="timeline">
                    <li class="tl-ok">
                        <i class="bx bx-check-circle"></i>
                        <div>
                            <strong>Diajukan</strong><br>
                            <small class="text-muted"><?= date('d-m-Y H:i', strtotime($detail->created_at)) ?></small>
                        </div>
                    </li>
                    <li class="<?= $mengetahui_step_class ?>">
                        <i class="bx bx-check-shield"></i>
                        <div>
                            <strong>Tahap Mengetahui</strong><br>
                            <small class="text-muted"><?= $mengetahui_step_text ?></small>
                        </div>
                    </li>
                    <li class="<?= $memverifikasi_step_class ?>">
                        <i class="bx bx-user-check"></i>
                        <div>
                            <strong>Tahap Memverifikasi</strong><br>
                            <small class="text-muted"><?= $memverifikasi_step_text ?></small>
                        </div>
                    </li>
                    <li class="<?= $menyetujui_step_class ?>">
                        <i class="bx bx-user-voice"></i>
                        <div>
                            <strong>Tahap Menyetujui</strong><br>
                            <small class="text-muted"><?= $menyetujui_step_text ?></small>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="detail-card">
                <h4><i class="bx bx-comment-detail"></i> Catatan Approval</h4>
                <div class="kv-row">
                    <div class="kv-key">Catatan Mengetahui</div>
                    <div class="kv-value"><?= !empty($detail->catatan_mengetahui) ? nl2br(htmlspecialchars($detail->catatan_mengetahui)) : '-' ?></div>
                </div>
                <div class="kv-row">
                    <div class="kv-key">Catatan Memverifikasi</div>
                    <div class="kv-value"><?= !empty($detail->catatan_menyetujui) ? nl2br(htmlspecialchars($detail->catatan_menyetujui)) : '-' ?></div>
                </div>
                <div class="mt-3 text-right">
                    <a href="<?= base_url('wfa_pengajuan/show/persetujuan') ?>" class="btn btn-outline-secondary btn-sm">Kembali ke Persetujuan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function sendApprovalAction(aksi, alasanTolak = '') {
        const id = $('#id_wfa').val();
        const catatan = $('#catatan_approval').val();

        $.ajax({
            method: 'POST',
            url: 'wfa_pengajuan/proses/' + aksi,
            dataType: 'JSON',
            data: {
                id: id,
                catatan: catatan,
                alasan_tolak: alasanTolak,
                csrf_token: token
            },
            success: function(resp) {
                handleResponse(resp);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        $(document).on('click', '.btn-aksi-wfa', function() {
            const aksi = $(this).data('aksi');
            const isReject = String(aksi).indexOf('tolak_') === 0;

            if (!isReject) {
                Swal.fire({
                    title: 'Setujui pengajuan ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Setujui',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.value) {
                        sendApprovalAction(aksi);
                    }
                });
                return;
            }

            Swal.fire({
                title: 'Tolak Pengajuan WFA',
                text: 'Alasan penolakan wajib diisi.',
                input: 'textarea',
                inputPlaceholder: 'Tulis alasan penolakan...',
                showCancelButton: true,
                confirmButtonText: 'Tolak',
                cancelButtonText: 'Batal',
                inputValidator: function(value) {
                    if (!value) {
                        return 'Alasan penolakan wajib diisi';
                    }
                }
            }).then(function(result) {
                if (result.value) {
                    sendApprovalAction(aksi, result.value);
                }
            });
        });
    });
</script>
