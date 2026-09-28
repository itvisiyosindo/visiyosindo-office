<?php
$role_nav_selected = isset($role_nav_selected) ? $role_nav_selected : 'pengaju';
$can_access_approval_menu = isset($can_access_approval_menu) ? $can_access_approval_menu : false;
$can_manage = isset($can_manage) ? $can_manage : false;
$can_approve = isset($can_approve) ? $can_approve : false;
$detail = isset($detail) ? $detail : (object) [
    'id' => 0,
    'status' => 0,
    'kode_pengajuan' => '-',
    'nama_pengaju' => '-',
    'jabatan_pengaju' => '',
    'nama_approver' => '-',
    'jabatan_approver' => '',
    'nominal_udara' => 0,
    'link_lampiran_udara' => '',
    'nominal_darat' => 0,
    'link_lampiran_darat' => '',
    'hari_dinas' => 1,
    'catatan_pengaju' => '',
    'catatan_approver' => '',
    'rejected_reason' => '',
];
$calc = isset($calc) && is_array($calc) ? $calc : [
    'udara' => 0,
    'udara_plus_20' => 0,
    'darat' => 0,
    'biaya_bagasi' => 500000,
    'biaya_lain' => 500000,
    'uang_pulsa' => 20000,
    'penginapan' => 0,
    'hari_penginapan' => 0,
    'uang_saku_makan' => 0,
    'transport_lokasi' => 100000,
    'hari_dinas' => 1,
    'total' => 1120000,
];

$total_final = (int) preg_replace('/[^0-9]/', '', (string) ($detail->total ?? ''));
if ($total_final <= 0) {
    $total_final = (int) $calc['total'];
}

$status_badge = '<span class="badge badge-warning">Menunggu Persetujuan</span>';
if ((int) $detail->status === 5) {
    $status_badge = '<span class="badge badge-success">Disetujui</span>';
} elseif ((int) $detail->status === 99) {
    $status_badge = '<span class="badge badge-danger">Ditolak</span>';
}

function lta_link($url)
{
    if (empty($url)) {
        return '-';
    }

    $safe = htmlspecialchars($url);
    return '<a href="' . $safe . '" target="_blank" rel="noopener noreferrer">Buka lampiran</a>';
}
?>

<style>
    .lta-detail-wrap {
        display: grid;
        gap: 16px;
    }

    .lta-detail-top {
        background: linear-gradient(120deg, #0f4c5c, #2c7da0);
        color: #fff;
        border-radius: 14px;
        padding: 16px 18px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .lta-detail-top h3 {
        margin: 0 0 5px 0;
        color: #fff;
        font-weight: 700;
    }

    .lta-detail-top p {
        margin: 0;
        opacity: .98;
        color: #e8f1f8;
    }

    .lta-role-nav {
        max-width: 300px;
        margin-left: auto;
    }

    .lta-detail-grid {
        display: grid;
        gap: 16px;
        grid-template-columns: 1fr 1fr;
    }

    .lta-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 14px;
        padding: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .lta-card h4 {
        color: #1a2332;
        margin-bottom: 14px;
        font-weight: 700;
    }

    .lta-kv {
        display: grid;
        grid-template-columns: 150px 1fr;
        gap: 8px;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .lta-kv:last-child {
        border-bottom: none;
    }

    .lta-kv-key {
        font-weight: 700;
        color: #2d3748;
    }

    .lta-kv-val {
        color: #1a2332;
        line-height: 1.5;
        word-break: break-word;
    }

    .lta-kv-val strong {
        color: #1a2332;
    }

    .lta-kv-val a {
        color: #0066cc;
        text-decoration: underline;
    }

    .calc-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .9rem;
    }

    .calc-table td {
        border-bottom: 1px solid #e9ecef;
        padding: 8px 4px;
        color: #2d3748;
    }

    .calc-table td:first-child {
        color: #2d3748;
        font-weight: 500;
    }

    .calc-table td:last-child {
        text-align: right;
        font-weight: 600;
        color: #1a2332;
    }

    .calc-table tr:last-child td {
        border-bottom: none;
    }

    .calc-total {
        background: #f0f7fc;
    }

    .calc-total td {
        font-size: 1rem;
        font-weight: 800;
        color: #1a2332;
        padding: 10px 4px;
    }

    .text-muted.small {
        color: #6c757d !important;
    }

    .lta-timeline {
        position: relative;
        padding: 0;
        margin: 0;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
        padding-left: 50px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 17px;
        top: 50px;
        width: 2px;
        height: 100%;
        background: #e9ecef;
    }

    .timeline-item.active::before {
        background: #0066cc;
        box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.1);
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-circle {
        position: absolute;
        left: 0;
        top: 0;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .timeline-item.active .timeline-circle {
        border-color: #0066cc;
        background: #0066cc;
        color: #fff;
        box-shadow: 0 0 0 6px rgba(0, 102, 204, 0.15);
    }

    .timeline-content {
        background: #f9fafb;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 14px 16px;
        transition: all 0.3s ease;
    }

    .timeline-item.active .timeline-content {
        background: #ecf4fd;
        border-color: #0066cc;
    }

    .timeline-label {
        font-weight: 700;
        color: #1a2332;
        font-size: 1rem;
        margin-bottom: 4px;
    }

    .timeline-desc {
        font-size: 0.9rem;
        color: #6c757d;
        margin: 0;
    }

    .timeline-reject-reason {
        background: #fef5f5;
        border: 1px solid #facdd2;
        border-radius: 8px;
        padding: 14px 16px;
        margin-top: 20px;
    }

    .timeline-reject-title {
        color: #c41e3a;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.95rem;
    }

    .timeline-reject-text {
        color: #8b2c2c;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .lta-status-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 6px;
        width: fit-content;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .lta-status-badge i {
        font-size: 1.1rem;
    }

    .lta-status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .lta-status-badge.approved {
        background: #d4edda;
        color: #155724;
    }

    .lta-status-badge.rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .lta-btn-section {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #dee2e6;
    }

    .lta-btn-section .btn-group-vertical {
        width: 100%;
    }

    .lta-btn-section .btn {
        width: 100%;
        justify-content: flex-start;
    }

    .lta-inline-actions {
        display: flex;
        gap: 10px;
    }

    @media (max-width: 992px) {
        .lta-detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .lta-detail-top {
            padding: 14px;
        }

        .lta-detail-top h3 {
            font-size: 1.1rem;
        }

        .lta-card {
            padding: 12px;
        }

        .lta-kv {
            grid-template-columns: 1fr;
        }

        .lta-inline-actions,
        .lta-btn-section>div {
            flex-direction: column;
        }

        .lta-inline-actions .btn,
        .lta-btn-section .btn {
            width: 100%;
        }
    }
</style>

<header class="page-header">
    <h2><i class="icons icon-user-follow"></i>&nbsp;Lumpsum, Akomodasi & Transportasi</h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>Detail Pengajuan Lumpsum, Akomodasi & Transportasi : <?= htmlspecialchars($detail->kode_pengajuan) ?></span></li>
        </ol>
    </div>
</header>

<div class="lta-detail-wrap">
    <div class="lta-detail-top">
        <h3><?= htmlspecialchars($detail->kode_pengajuan) ?></h3>
        <p>Detail Pengajuan Lumpsum, Akomodasi & Transportasi <?= $status_badge ?></p>
    </div>

    <div class="lta-detail-grid">
        <div class="lta-card" style="grid-column: 1 / -1; background: linear-gradient(135deg, #f0f7fc 0%, #e8f4f8 100%); border: 2px solid #0066cc;">
            <h4 style="color: #0066cc;"><i class="bx bx-money"></i> Informasi Biaya Pengajuan</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 8px; padding: 12px;">
                    <div style="color: #6c757d; font-size: 0.9rem; margin-bottom: 4px;">💰 Total Estimasi Biaya</div>
                    <div style="font-size: 1.3rem; font-weight: 700; color: #0066cc;">
                        Rp <?= number_format((int) $calc['total'], 0, ',', '.') ?>
                    </div>
                    <?php if ((int) $detail->status !== 5): ?>
                        <small style="color: #6c757d; margin-top: 4px; display: block;">
                            <i class="bx bx-info-circle"></i> Dapat berubah sampai disetujui
                        </small>
                    <?php endif; ?>
                </div>

                <?php if ((int) $detail->status === 5): ?>
                    <div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 12px;">
                        <div style="color: #155724; font-size: 0.9rem; margin-bottom: 4px;">✅ Total Biaya Final</div>
                        <div style="font-size: 1.3rem; font-weight: 700; color: #155724;">
                            Rp <?= number_format($total_final, 0, ',', '.') ?>
                        </div>
                        <small style="color: #155724; margin-top: 4px; display: block;">
                            <i class="bx bx-check-circle"></i> Disetujui pada <?= !empty($detail->approved_at) ? date('d M Y H:i', strtotime($detail->approved_at)) : 'N/A' ?>
                        </small>
                    </div>
                <?php endif; ?>

                <?php if ((int) $detail->status === 99): ?>
                    <div style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 8px; padding: 12px;">
                        <div style="color: #721c24; font-size: 0.9rem; margin-bottom: 4px;">❌ Ditolak</div>
                        <div style="font-size: 1.3rem; font-weight: 700; color: #721c24;">
                            -
                        </div>
                        <small style="color: #721c24; margin-top: 4px; display: block;">
                            <i class="bx bx-x-circle"></i> Pengajuan tidak berlaku
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="lta-card">
            <h4><i class="bx bx-detail"></i> Informasi Pengajuan</h4>
            <div class="lta-kv">
                <div class="lta-kv-key">Pengaju</div>
                <div class="lta-kv-val"><strong><?= htmlspecialchars($detail->nama_pengaju) ?></strong> <?= !empty($detail->jabatan_pengaju) ? '| ' . htmlspecialchars($detail->jabatan_pengaju) : '' ?></div>
            </div>
            <div class="lta-kv">
                <div class="lta-kv-key">Approver</div>
                <div class="lta-kv-val"><strong><?= htmlspecialchars($detail->nama_approver) ?></strong> <?= !empty($detail->jabatan_approver) ? '| ' . htmlspecialchars($detail->jabatan_approver) : '' ?></div>
            </div>
            <div class="lta-kv">
                <div class="lta-kv-key">Transport Udara</div>
                <div class="lta-kv-val">Rp <?= number_format((int) $detail->nominal_udara, 0, ',', '.') ?></div>
            </div>
            <div class="lta-kv">
                <div class="lta-kv-key">Lampiran Udara</div>
                <div class="lta-kv-val"><?= lta_link($detail->link_lampiran_udara) ?></div>
            </div>
            <div class="lta-kv">
                <div class="lta-kv-key">Transport Darat</div>
                <div class="lta-kv-val">Rp <?= number_format((int) $detail->nominal_darat, 0, ',', '.') ?></div>
            </div>
            <div class="lta-kv">
                <div class="lta-kv-key">Lampiran Darat</div>
                <div class="lta-kv-val"><?= lta_link($detail->link_lampiran_darat) ?></div>
            </div>
            <div class="lta-kv">
                <div class="lta-kv-key">Hari Dinas</div>
                <div class="lta-kv-val"><?= (int) $detail->hari_dinas ?> hari</div>
            </div>
            <div class="lta-kv">
                <div class="lta-kv-key">Catatan Pengaju</div>
                <div class="lta-kv-val"><?= !empty($detail->catatan_pengaju) ? nl2br(htmlspecialchars($detail->catatan_pengaju)) : '-' ?></div>
            </div>
            <div class="lta-kv">
                <div class="lta-kv-key">Nama Customer</div>
                <div class="lta-kv-val"><?= !empty($detail->customer_name) ? htmlspecialchars($detail->customer_name) : '-' ?></div>
            </div>
            <div class="lta-kv">
                <div class="lta-kv-key">Alamat Customer</div>
                <div class="lta-kv-val"><?= !empty($detail->customer_address) ? nl2br(htmlspecialchars($detail->customer_address)) : '-' ?></div>
            </div>
            <div class="lta-kv">
                <div class="lta-kv-key">Nama Teknisi</div>
                <div class="lta-kv-val"><?php if (!empty($detail->teknisi_id)) {
                                            $t = $this->md_pengguna->getById($detail->teknisi_id);
                                            echo !empty($t[0]->nama) ? htmlspecialchars($t[0]->nama) : '-';
                                        } else {
                                            echo '-';
                                        } ?></div>
            </div>
            <div class="lta-kv">
                <div class="lta-kv-key">Catatan Approval</div>
                <div class="lta-kv-val"><?= !empty($detail->catatan_approver) ? nl2br(htmlspecialchars($detail->catatan_approver)) : '-' ?></div>
            </div>
            <?php if ((int) $detail->status === 99 && !empty($detail->rejected_reason)): ?>
                <div class="lta-kv">
                    <div class="lta-kv-key text-danger">Alasan Ditolak</div>
                    <div class="lta-kv-val text-danger"><?= nl2br(htmlspecialchars($detail->rejected_reason)) ?></div>
                </div>
            <?php endif; ?>
        </div>

        <div class="lta-card">
            <h4><i class="bx bx-time"></i> Status Pengajuan</h4>
            <div class="lta-timeline">
                <?php
                $timeline = [
                    ['status' => 0, 'label' => 'Menunggu Persetujuan', 'icon' => 'bx-hourglass-mid', 'active' => (int)$detail->status >= 0],
                    ['status' => 5, 'label' => 'Disetujui', 'icon' => 'bx-check-circle', 'active' => (int)$detail->status === 5],
                    ['status' => 99, 'label' => 'Ditolak', 'icon' => 'bx-x-circle', 'active' => (int)$detail->status === 99],
                ];
                foreach ($timeline as $item):
                    $is_active = $item['active'] ? 'active' : '';
                ?>
                    <div class="timeline-item <?= $is_active ?>">
                        <div class="timeline-circle">
                            <i class="bx <?= $item['icon'] ?>"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-label"><?= $item['label'] ?></div>
                            <?php if ($item['active']): ?>
                                <p class="timeline-desc"><?php
                                                            if ((int)$detail->status === 5) {
                                                                echo 'Disetujui ' . (!empty($detail->approved_at) ? 'pada ' . date('d M Y H:i', strtotime($detail->approved_at)) : '');
                                                            } elseif ((int)$detail->status === 99) {
                                                                echo 'Ditolak ' . (!empty($detail->updated_at) ? 'pada ' . date('d M Y H:i', strtotime($detail->updated_at)) : '');
                                                            } else {
                                                                echo 'Menunggu persetujuan dari ' . htmlspecialchars($detail->nama_approver);
                                                            }
                                                            ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (!empty($detail->rejected_reason) && (int)$detail->status === 99): ?>
                <div class="timeline-reject-reason">
                    <div class="timeline-reject-title"><i class="bx bx-alert-circle"></i> Alasan Penolakan</div>
                    <div class="timeline-reject-text"><?= nl2br(htmlspecialchars($detail->rejected_reason)) ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($can_approve): ?>
        <div class="lta-card">
            <h4><i class="bx bx-check-shield"></i> Aksi Persetujuan</h4>
            <input type="hidden" id="id_lta" value="<?= encrypt($detail->id) ?>">
            <div class="form-group">
                <label><strong>Catatan Persetujuan</strong></label>
                <textarea id="catatan_approver" class="form-control" rows="3" placeholder="Catatan approval (opsional)"></textarea>
            </div>
            <div class="lta-btn-section" style="margin-top: 0; padding-top: 0; border-top: none;">
                <div class="btn-group-vertical" role="group">
                    <button type="button" class="btn btn-success btn-aksi-lta" data-aksi="setujui"><i class="bx bx-check" style="margin-right:8px;"></i> Setujui Pengajuan</button>
                    <button type="button" class="btn btn-danger btn-aksi-lta" data-aksi="tolak" style="margin-top:8px;"><i class="bx bx-x" style="margin-right:8px;"></i> Tolak Pengajuan</button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($can_manage): ?>
        <div class="lta-card">
            <h4><i class="bx bx-edit"></i> Manajemen Update Data</h4>
            <p class="text-muted mb-3">Gunakan form ini untuk koreksi data pada halaman detail jika ada kesalahan dari pengajuan awal.</p>
            <?= form_open('#', ['id' => 'form-update-lta']); ?>
            <input type="hidden" id="id_lta_update" value="<?= encrypt($detail->id) ?>">

            <!-- Section: Biaya & Hari Dinas -->
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="edit_nominal_udara" style="font-weight:600;margin-bottom:8px;display:block;">Nominal Transportasi Udara</label>
                        <input type="text" class="form-control" id="edit_nominal_udara" value="<?= (int) $detail->nominal_udara ?>" style="font-size:0.95rem;">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="edit_nominal_darat" style="font-weight:600;margin-bottom:8px;display:block;">Nominal Transportasi Darat</label>
                        <input type="text" class="form-control" id="edit_nominal_darat" value="<?= (int) $detail->nominal_darat ?>" style="font-size:0.95rem;">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="edit_hari_dinas" style="font-weight:600;margin-bottom:8px;display:block;">Hari Dinas</label>
                        <input type="number" min="1" max="60" class="form-control" id="edit_hari_dinas" value="<?= (int) $detail->hari_dinas ?>" style="font-size:0.95rem;">
                    </div>
                </div>
            </div>

            <!-- Section: Customer & Teknisi -->
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="detail_customer_select" style="font-weight:600;margin-bottom:0;">Nama Customer</label>
                            <div class="form-check form-check-inline" style="margin-right: 0;">
                                <input class="form-check-input" type="checkbox" id="edit_customer_manual_check" style="cursor:pointer;">
                                <label class="form-check-label text-muted" for="edit_customer_manual_check" style="cursor:pointer; font-size: 0.85rem; font-weight: normal; margin-bottom: 0;">Input Manual</label>
                            </div>
                        </div>
                        <select id="detail_customer_select" class="form-control" style="width:100%;"></select>
                        <input type="text" class="form-control d-none" id="edit_customer_name_manual" placeholder="Masukkan Nama Customer secara manual">
                        <input type="hidden" id="detail_customer_id" name="customer_id" value="<?= !empty($detail->customer_id) ? htmlspecialchars($detail->customer_id) : '' ?>">
                        <input type="hidden" id="detail_customer_name" name="customer_name" value="<?= !empty($detail->customer_name) ? htmlspecialchars($detail->customer_name) : '' ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="detail_teknisi_select" style="font-weight:600;margin-bottom:8px;display:block;">Nama Teknisi</label>
                        <select id="detail_teknisi_select" class="form-control" style="width:100%;"></select>
                        <input type="hidden" id="detail_teknisi_id" name="teknisi_id" value="<?= !empty($detail->teknisi_id) ? htmlspecialchars($detail->teknisi_id) : '' ?>">
                    </div>
                </div>
            </div>

            <!-- Section: Alamat Customer -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="detail_customer_address" style="font-weight:600;margin-bottom:8px;display:block;">Alamat Customer</label>
                <textarea class="form-control" id="detail_customer_address" name="customer_address" rows="3" style="font-size:0.95rem;"><?= !empty($detail->customer_address) ? htmlspecialchars($detail->customer_address) : '' ?></textarea>
                <div class="alert alert-warning mt-2 d-none" id="edit-alert-manual-address" style="padding: 8px 12px; font-size: 0.85rem; margin-bottom: 0;">
                    <i class="bx bx-error-circle"></i> Alamat Customer wajib diisi untuk kerapian data ketika menginput secara manual!
                </div>
            </div>

            <!-- Section: Link Lampiran -->
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="edit_link_udara" style="font-weight:600;margin-bottom:8px;display:block;">Link Lampiran Udara</label>
                        <input type="url" class="form-control" id="edit_link_udara" value="<?= htmlspecialchars((string) $detail->link_lampiran_udara) ?>" style="font-size:0.95rem;">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="edit_link_darat" style="font-weight:600;margin-bottom:8px;display:block;">Link Lampiran Darat</label>
                        <input type="url" class="form-control" id="edit_link_darat" value="<?= htmlspecialchars((string) $detail->link_lampiran_darat) ?>" style="font-size:0.95rem;">
                    </div>
                </div>
            </div>

            <!-- Section: Rincian Biaya Breakdown -->
            <div style="background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:16px;margin-bottom:20px;">
                <h6 style="font-weight:700;margin-bottom:16px;color:#1a2332;"><i class="bx bx-calculator"></i> Rincian Biaya (Breakdown)</h6>

                <!-- Row 1: Transportasi Udara Breakdown -->
                <div class="row" style="margin-bottom:16px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit_udara" style="font-weight:600;margin-bottom:8px;display:block;">Transportasi Udara</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control breakdown-field" id="edit_udara" name="udara" value="<?= !empty($detail->udara) ? htmlspecialchars((string) $detail->udara) : htmlspecialchars((string) $calc['udara']) ?>" style="font-size:0.95rem;" data-recalc="true">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit_udara_plus_20" style="font-weight:600;margin-bottom:8px;display:block;">Surcharge Transportasi Udara (20%)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control breakdown-field" id="edit_udara_plus_20" name="udara_plus_20" value="<?= !empty($detail->udara_plus_20) ? htmlspecialchars((string) $detail->udara_plus_20) : htmlspecialchars((string) $calc['udara_plus_20']) ?>" style="font-size:0.95rem;" data-recalc="true">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Transportasi Darat & Bagasi -->
                <div class="row" style="margin-bottom:16px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit_darat" style="font-weight:600;margin-bottom:8px;display:block;">Transportasi Darat</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control breakdown-field" id="edit_darat" name="darat" value="<?= !empty($detail->darat) ? htmlspecialchars((string) $detail->darat) : htmlspecialchars((string) $calc['darat']) ?>" style="font-size:0.95rem;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit_biaya_bagasi" style="font-weight:600;margin-bottom:8px;display:block;">Biaya Bagasi</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control breakdown-field" id="edit_biaya_bagasi" name="biaya_bagasi" value="<?= !empty($detail->biaya_bagasi) ? htmlspecialchars((string) $detail->biaya_bagasi) : htmlspecialchars((string) $calc['biaya_bagasi']) ?>" style="font-size:0.95rem;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Biaya Lain-lain & Pulsa -->
                <div class="row" style="margin-bottom:16px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit_biaya_lain" style="font-weight:600;margin-bottom:8px;display:block;">Biaya Lain-lain</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control breakdown-field" id="edit_biaya_lain" name="biaya_lain" value="<?= !empty($detail->biaya_lain) ? htmlspecialchars((string) $detail->biaya_lain) : htmlspecialchars((string) $calc['biaya_lain']) ?>" style="font-size:0.95rem;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit_uang_pulsa" style="font-weight:600;margin-bottom:8px;display:block;">Uang Pulsa</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control breakdown-field" id="edit_uang_pulsa" name="uang_pulsa" value="<?= !empty($detail->uang_pulsa) ? htmlspecialchars((string) $detail->uang_pulsa) : htmlspecialchars((string) $calc['uang_pulsa']) ?>" style="font-size:0.95rem;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 4: Penginapan & Transport Lokasi -->
                <div class="row" style="margin-bottom:16px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit_penginapan" style="font-weight:600;margin-bottom:8px;display:block;">Penginapan (Hari Dinas - 1) x 500.000</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control breakdown-field" id="edit_penginapan" name="penginapan" value="<?= !empty($detail->penginapan) ? htmlspecialchars((string) $detail->penginapan) : htmlspecialchars((string) $calc['penginapan']) ?>" style="font-size:0.95rem;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit_transport_lokasi" style="font-weight:600;margin-bottom:8px;display:block;">Transport Lokasi / Grab</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control breakdown-field" id="edit_transport_lokasi" name="transport_lokasi" value="<?= !empty($detail->transport_lokasi) ? htmlspecialchars((string) $detail->transport_lokasi) : htmlspecialchars((string) $calc['transport_lokasi']) ?>" style="font-size:0.95rem;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 5: Uang Saku & Makan -->
                <div class="row" style="margin-bottom:16px;">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="edit_uang_saku_makan" style="font-weight:600;margin-bottom:8px;display:block;">Uang Saku & Makan (115.000 x Hari Dinas)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control breakdown-field" id="edit_uang_saku_makan" name="uang_saku_makan" value="<?= !empty($detail->uang_saku_makan) ? htmlspecialchars((string) $detail->uang_saku_makan) : htmlspecialchars((string) $calc['uang_saku_makan']) ?>" style="font-size:0.95rem;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 6: Total -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="edit_total" style="font-weight:700;margin-bottom:8px;display:block;color:#0b4f6c;">TOTAL FINAL (Rp)</label>
                            <div class="input-group" style="border:2px solid #0b4f6c;border-radius:6px;overflow:hidden;">
                                <span class="input-group-text" style="background:#e8f4f8;font-weight:700;">Rp</span>
                                <input type="text" class="form-control" id="edit_total" name="total" value="<?= !empty($detail->total) ? htmlspecialchars((string) $detail->total) : htmlspecialchars((string) $calc['total']) ?>" style="font-size:1.1rem;font-weight:700;color:#0b4f6c;background:#f8fafb;" readonly>
                            </div>
                            <small style="color:#6c757d;margin-top:6px;display:block;">Total otomatis terhitung dari semua komponen di atas</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Catatan Approval -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="edit_catatan_approver" style="font-weight:600;margin-bottom:8px;display:block;">Catatan Approval / Koreksi</label>
                <textarea class="form-control" id="edit_catatan_approver" rows="3" style="font-size:0.95rem;"><?= !empty($detail->catatan_approver) ? htmlspecialchars($detail->catatan_approver) : '' ?></textarea>
            </div>

            <!-- Section: Button -->
            <div class="lta-inline-actions" style="display:flex;gap:10px;">
                <button type="button" class="btn btn-primary" id="btn-update-lta"><i class="bx bx-save"></i> Simpan Perubahan</button>
                <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload();"><i class="bx bx-x"></i> Batal</button>
            </div>
            <?= form_close(); ?>
        </div>
    <?php endif; ?>

    <div class="lta-btn-section">
        <div class="lta-inline-actions" style="display:flex;gap:10px;">
            <?php if (empty($can_manage)): ?>
                <a href="<?= base_url('lta_pengajuan/show/pengajuan') ?>" class="btn btn-outline-secondary"><i class="bx bx-arrow-back" style="margin-right:6px;"></i> Kembali</a>
            <?php else: ?>
                <a href="<?= base_url('lta_pengajuan/show/persetujuan') ?>" class="btn btn-outline-primary"><i class="bx bx-arrow-back" style="margin-right:6px;"></i> Kembali ke Daftar Persetujuan</a>
                <a href="<?= base_url('lta_pengajuan/show/pengajuan') ?>" class="btn btn-outline-secondary"><i class="bx bx-home" style="margin-right:6px;"></i> Pengajuan Saya</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function formatRupiahNumber(value) {
        return 'Rp ' + (Number(value || 0)).toLocaleString('id-ID');
    }

    function computePreviewAndRender(udara, darat, hari) {
        udara = Number(udara || 0);
        darat = Number(darat || 0);
        hari = parseInt(hari || 1, 10);

        if (isNaN(hari) || hari < 1) {
            hari = 1;
        }

        var udara20 = Math.round(udara * 0.2);
        var penginapan = Math.max(hari - 1, 0) * 500000;
        var makan = hari * 115000;
        var total = udara + udara20 + darat + 500000 + 500000 + 20000 + penginapan + makan + 100000;

        $('#preview_udara_detail').text(formatRupiahNumber(udara + udara20));
        $('#preview_darat_detail').text(formatRupiahNumber(darat));
        $('#preview_penginapan_detail').text(formatRupiahNumber(penginapan));
        $('#preview_makan_detail').text(formatRupiahNumber(makan));
        $('#preview_total_detail').text(formatRupiahNumber(total));
    }

    document.addEventListener('DOMContentLoaded', function() {
        // initialize preview for detail page using server values already rendered
        try {
            // attach live-update listeners only if edit inputs are present (approver edit)
            $('#edit_nominal_udara, #edit_nominal_darat, #edit_hari_dinas').on('input change', function() {
                var udara = $('#edit_nominal_udara').val();
                var darat = $('#edit_nominal_darat').val();
                var hari = $('#edit_hari_dinas').val();
                udara = String(udara || '').replace(/[^0-9]/g, '');
                darat = String(darat || '').replace(/[^0-9]/g, '');
                computePreviewAndRender(Number(udara), Number(darat), Number(hari));
            });
        } catch (e) {
            // ignore if jQuery not ready
        }
    });

    function sendApprovalAction(aksi, alasanTolak) {
        $.ajax({
            method: 'POST',
            url: 'lta_pengajuan/proses/' + aksi,
            dataType: 'JSON',
            data: {
                id: $('#id_lta').val(),
                catatan_approver: $('#catatan_approver').val(),
                alasan_tolak: alasanTolak || '',
                csrf_token: token
            },
            success: function(resp) {
                handleResponse(resp);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        $(document).on('click', '.btn-aksi-lta', function() {
            var aksi = $(this).data('aksi');
            var reject = String(aksi) === 'tolak';

            if (!reject) {
                Swal.fire({
                    title: 'Setujui pengajuan ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, setujui',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.value) {
                        sendApprovalAction(aksi, '');
                    }
                });
                return;
            }

            Swal.fire({
                title: 'Tolak Pengajuan',
                text: 'Alasan penolakan wajib diisi.',
                input: 'textarea',
                inputPlaceholder: 'Isi alasan penolakan',
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

        $(document).on('click', '#btn-update-lta', function() {
            var isManual = $('#edit_customer_manual_check').is(':checked');
            var customerName = isManual ? $('#edit_customer_name_manual').val().trim() : $('#detail_customer_name').val().trim();
            var customerAddress = $('#detail_customer_address').val().trim();

            if (isManual) {
                if (!customerName) {
                    Swal.fire('Validasi', 'Nama Customer wajib diisi jika memilih Input Manual.', 'warning');
                    return;
                }
                if (!customerAddress) {
                    Swal.fire('Validasi', 'Alamat Customer wajib diisi jika memilih Input Manual.', 'warning');
                    return;
                }
            }

            Swal.fire({
                title: 'Simpan perubahan data?',
                text: 'Pastikan update data sudah sesuai.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (!result.value) {
                    return;
                }

                $.ajax({
                    method: 'POST',
                    url: 'lta_pengajuan/update_data',
                    dataType: 'JSON',
                    data: {
                        id: $('#id_lta_update').val(),
                        nominal_udara: $('#edit_nominal_udara').val(),
                        nominal_darat: $('#edit_nominal_darat').val(),
                        hari_dinas: $('#edit_hari_dinas').val(),
                        customer_id: $('#detail_customer_id').val(),
                        customer_name: $('#detail_customer_name').val(),
                        customer_address: $('#detail_customer_address').val(),
                        teknisi_id: $('#detail_teknisi_id').val(),
                        link_lampiran_udara: $('#edit_link_udara').val(),
                        link_lampiran_darat: $('#edit_link_darat').val(),
                        catatan_approver: $('#edit_catatan_approver').val(),
                        // Breakdown fields
                        udara: $('#edit_udara').val(),
                        udara_plus_20: $('#edit_udara_plus_20').val(),
                        darat: $('#edit_darat').val(),
                        biaya_bagasi: $('#edit_biaya_bagasi').val(),
                        biaya_lain: $('#edit_biaya_lain').val(),
                        uang_pulsa: $('#edit_uang_pulsa').val(),
                        penginapan: $('#edit_penginapan').val(),
                        uang_saku_makan: $('#edit_uang_saku_makan').val(),
                        transport_lokasi: $('#edit_transport_lokasi').val(),
                        total: $('#edit_total').val(),
                        csrf_token: token
                    },
                    success: function(resp) {
                        handleResponse(resp);
                    }
                });
            });
        });

        // Helper function to format number as Rp (without decimal)
        function formatCurrencyInput(val) {
            var num = String(val || '').replace(/[^0-9]/g, '');
            return num ? parseInt(num, 10).toLocaleString('id-ID') : '';
        }

        function stripCurrencyInput(val) {
            return String(val || '').replace(/[^0-9]/g, '');
        }

        // Attach breakdown field listeners
        $(document).ready(function() {
            // Format breakdown fields on blur
            $('.breakdown-field').on('blur', function() {
                var val = $(this).val();
                var stripped = stripCurrencyInput(val);
                $(this).val(stripped ? parseInt(stripped, 10).toLocaleString('id-ID') : '');
                recalculateTotal();
            });

            // Format on input
            $('.breakdown-field').on('input', function() {
                // Allow user to type freely, will be formatted on blur
            });

            // Recalculate total when any breakdown field changes
            $('.breakdown-field').on('change', recalculateTotal);
        });

        function recalculateTotal() {
            var fields = ['udara', 'udara_plus_20', 'darat', 'biaya_bagasi', 'biaya_lain', 'uang_pulsa', 'penginapan', 'uang_saku_makan', 'transport_lokasi'];
            var total = 0;

            fields.forEach(function(fieldName) {
                var val = stripCurrencyInput($('#edit_' + fieldName).val());
                total += parseInt(val || 0, 10);
            });

            // Update total field with formatted value
            $('#edit_total').val(total.toLocaleString('id-ID'));
        }

        // initialize select2 for detail edit
        (function() {
            try {
                if (window.$ && $.fn && $.fn.select2) {
                    $('#detail_customer_select').select2({
                        placeholder: 'Cari customer...',
                        ajax: {
                            url: 'lta_pengajuan/ajax_customers',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term
                                };
                            },
                            processResults: function(data) {
                                return data;
                            }
                        }
                    });

                    $('#detail_teknisi_select').select2({
                        placeholder: 'Pilih teknisi...',
                        ajax: {
                            url: 'lta_pengajuan/ajax_teknisi',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term
                                };
                            },
                            processResults: function(data) {
                                return data;
                            }
                        }
                    });

                    // Checkbox event handler for edit customer manual
                    $('#edit_customer_manual_check').on('change', function() {
                        var checked = $(this).is(':checked');
                        if (checked) {
                            $('#detail_customer_select').val(null).trigger('change');
                            if (window.$ && $.fn && $.fn.select2) {
                                $('#detail_customer_select').next('.select2-container').hide();
                            }
                            $('#edit_customer_name_manual').removeClass('d-none');

                            $('#detail_customer_id').val('');
                            $('#detail_customer_name').val('');

                            $('#edit-alert-manual-address').removeClass('d-none');
                        } else {
                            if (window.$ && $.fn && $.fn.select2) {
                                $('#detail_customer_select').next('.select2-container').show();
                            }
                            $('#edit_customer_name_manual').addClass('d-none').val('');

                            $('#detail_customer_id').val('');
                            $('#detail_customer_name').val('');
                            $('#detail_customer_address').val('');

                            $('#edit-alert-manual-address').addClass('d-none');
                        }
                    });

                    // Sinkronisasi manual name input
                    $('#edit_customer_name_manual').on('input', function() {
                        $('#detail_customer_name').val($(this).val());
                    });

                    // Initialization logic for edit form
                    var cId = $('#detail_customer_id').val();
                    var cName = $('#detail_customer_name').val();
                    if (!cId && cName) {
                        // Manual customer detected!
                        $('#edit_customer_manual_check').prop('checked', true).trigger('change');
                        $('#edit_customer_name_manual').val(cName);
                        $('#detail_customer_name').val(cName);
                    } else if (cId && cName) {
                        var option = new Option(cName, cId, true, true);
                        $('#detail_customer_select').append(option).trigger('change');
                    }

                    var tId = $('#detail_teknisi_id').val();
                    if (tId) {
                        $.ajax({
                            url: 'lta_pengajuan/ajax_teknisi',
                            dataType: 'json'
                        }).done(function(data) {
                            var found = (data.results || []).filter(function(r) {
                                return String(r.id) === String(tId);
                            });
                            if (found.length) {
                                var option = new Option(found[0].text, found[0].id, true, true);
                                $('#detail_teknisi_select').append(option).trigger('change');
                            }
                        });
                    }

                    $('#detail_customer_select').on('select2:select', function(e) {
                        var d = e.params.data;
                        $('#detail_customer_id').val(d.id);
                        $('#detail_customer_name').val(d.text || '');
                    });
                    $('#detail_teknisi_select').on('select2:select', function(e) {
                        var d = e.params.data;
                        $('#detail_teknisi_id').val(d.id);
                    });
                }
            } catch (e) {}
        })();
    });
</script>