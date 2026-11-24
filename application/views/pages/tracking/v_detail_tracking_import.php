<?php
$id_import_enc = encrypt($import_row->id_import);
$activeStage   = (int)$metrics['active_stage'];

$statusMeta = [
    'belum'   => ['label' => 'Belum',   'badge' => 'badge-secondary', 'border' => '#cbd5e1', 'bg' => '#f8fafc', 'icon' => 'fa-circle'],
    'proses'  => ['label' => 'Proses',  'badge' => 'badge-primary',   'border' => '#93c5fd', 'bg' => '#eff6ff', 'icon' => 'fa-spinner'],
    'selesai' => ['label' => 'Selesai', 'badge' => 'badge-success',   'border' => '#86efac', 'bg' => '#f0fdf4', 'icon' => 'fa-check-circle'],
    'na'      => ['label' => 'N/A (Tidak Ada Lartas)', 'badge' => 'badge-dark', 'border' => '#94a3b8', 'bg' => '#f1f5f9', 'icon' => 'fa-ban'],
];
?>

<style>
    .phase-nav-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 12px;
        display: block;
        text-decoration: none !important;
        transition: all 0.15s ease;
    }
    .phase-nav-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.07);
    }
    .step-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 10px;
        overflow: hidden;
        background: #fff;
    }
    .step-card-header {
        padding: 11px 15px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        user-select: none;
    }
    .step-card-header:hover {
        filter: brightness(0.98);
    }
    .step-num-badge {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.82rem;
        margin-right: 10px;
        flex-shrink: 0;
    }
    .step-body {
        padding: 16px;
        border-top: 1px solid #e2e8f0;
        background: #fff;
    }
    .doc-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 10px;
    }
</style>

<header class="page-header">
    <h2><i class="icons fas fa-ship"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="mb-3 d-flex flex-wrap justify-content-between align-items-center" style="gap:8px;">
    <div>
        <a href="<?= base_url('tracking/tracking_import') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
        <button type="button" class="btn btn-outline-primary btn-sm ml-1" data-toggle="modal" data-target="#modalEditHeader">
            <i class="fas fa-edit mr-1"></i> Edit Info Utama
        </button>
    </div>
    <div class="d-flex flex-wrap align-items-center" style="gap:8px;">
        <span class="badge badge-info p-2" style="font-size:0.82rem;">
            <i class="fas fa-warehouse mr-1"></i> <?= htmlspecialchars($import_row->nama_gudang_tujuan ?: 'Gudang Pusat') ?>
        </span>
        <span class="badge p-2" style="background:<?= $metrics['jatuh_tempo']['badge_bg'] ?>; color:<?= $metrics['jatuh_tempo']['badge_text'] ?>; border:1px solid <?= $metrics['jatuh_tempo']['badge_bdr'] ?>; font-size:0.82rem;">
            <i class="fas <?= $metrics['jatuh_tempo']['icon'] ?> mr-1"></i> Inv. Forwarder: <?= htmlspecialchars($metrics['jatuh_tempo']['label']) ?>
        </span>
    </div>
</div>

<!-- RINGKASAN HEADER & PROGRESS 14 TAHAP -->
<div class="card mb-3">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <div class="d-flex flex-wrap align-items-center" style="gap:8px;">
                    <span class="badge badge-primary"><?= htmlspecialchars($import_row->kode_import) ?></span>
                    <span class="badge badge-secondary"><?= htmlspecialchars($import_row->moda_transportasi ?: 'Sea Freight') ?></span>
                    <?php if ($import_row->ada_lartas === 'ada'): ?>
                        <span class="badge badge-danger"><i class="fas fa-exclamation-triangle mr-1"></i>Ada Lartas (Koordinasi Regulatory)</span>
                    <?php elseif ($import_row->ada_lartas === 'tidak'): ?>
                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Tidak Ada Lartas</span>
                    <?php endif; ?>
                </div>
                <h4 class="mt-2 mb-1 font-weight-bold text-dark">
                    PO: <?= htmlspecialchars($import_row->no_po_manufacture) ?> — <?= htmlspecialchars($import_row->nama_manufacture) ?>
                    <?= !empty($import_row->negara_asal) ? ('<small class="text-muted">(' . htmlspecialchars($import_row->negara_asal) . ')</small>') : '' ?>
                </h4>
                <div class="text-muted small mb-2">
                    <strong>Unit/Barang:</strong> <?= nl2br(htmlspecialchars($import_row->daftar_unit)) ?>
                </div>
                <div class="d-flex flex-wrap small text-dark" style="gap:14px;">
                    <span><strong>HS Code:</strong> <?= htmlspecialchars($import_row->hs_code ?: '-') ?></span>
                    <span><strong>Forwarder:</strong> <?= htmlspecialchars($import_row->nama_forwarder ?: '-') ?></span>
                    <span><strong>AWB/BL:</strong> <?= htmlspecialchars($import_row->no_bl_awb ?: '-') ?></span>
                    <span><strong>PIB:</strong> <?= htmlspecialchars($import_row->no_pib ?: '-') ?></span>
                    <span><strong>Pajak:</strong> <?= (float)$import_row->nominal_pajak_import > 0 ? ('Rp ' . number_format((float)$import_row->nominal_pajak_import, 0, ',', '.')) : '-' ?></span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #cbd5e1;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small font-weight-bold text-dark">Progress Keseluruhan</span>
                        <span class="font-weight-bold text-primary"><?= $metrics['completed_cnt'] ?>/14 (<?= $metrics['progress_pct'] ?>%)</span>
                    </div>
                    <div class="progress mb-2" style="height:10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $metrics['progress_pct'] ?>%;"></div>
                    </div>
                    <div class="small text-muted">
                        <div>Lead Time ke Warehouse (1➔12): <strong class="text-dark"><?= $metrics['dur_to_warehouse']['valid'] ? $metrics['dur_to_warehouse']['text'] : 'Berjalan' ?></strong></div>
                        <div>Total End-to-End (1➔14): <strong class="text-dark"><?= $metrics['dur_end_to_end']['valid'] ? $metrics['dur_end_to_end']['text'] : 'Berjalan' ?></strong></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NAVIGASI 5 FASE -->
        <div class="row mt-3">
            <?php foreach ($phase_defs as $pNum => $pDef):
                $ps = $metrics['phase_stats'][$pNum];
            ?>
                <div class="col-md col-sm-6 mb-2 mb-md-0">
                    <a href="#fase-<?= $pNum ?>" class="phase-nav-card" style="border-top: 3px solid <?= $pDef['color'] ?>;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold" style="font-size:0.75rem; color:<?= $pDef['color'] ?>;">
                                <i class="fas <?= $pDef['icon'] ?> mr-1"></i>Fase <?= $pNum ?>
                            </span>
                            <span class="badge <?= $ps['state'] === 'selesai' ? 'badge-success' : ($ps['state'] === 'proses' ? 'badge-primary' : 'badge-light border') ?>" style="font-size:0.7rem;">
                                <?= $ps['done'] ?>/<?= $ps['total'] ?>
                            </span>
                        </div>
                        <div class="small font-weight-bold text-dark mt-1" style="line-height:1.25;"><?= htmlspecialchars($pDef['short']) ?></div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- CHECKLIST 14 TAHAPAN (DIKELOMPOKKAN PER 5 FASE) -->
<?php foreach ($phase_defs as $pNum => $pDef):
    $ps = $metrics['phase_stats'][$pNum];
?>
    <div id="fase-<?= $pNum ?>" class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
            <h5 class="mb-0 font-weight-bold" style="color:<?= $pDef['color'] ?>;">
                <i class="fas <?= $pDef['icon'] ?> mr-1"></i> <?= htmlspecialchars($pDef['name']) ?>
            </h5>
            <span class="badge badge-light border" style="font-size:0.78rem;">
                Selesai: <strong><?= $ps['done'] ?> / <?= $ps['total'] ?></strong> Tahap
            </span>
        </div>

        <?php foreach ($pDef['stages'] as $stNum):
            $def     = $stage_defs[$stNum];
            $stepObj = $steps_map[$stNum];
            $stState = $metrics['step_statuses'][$stNum] ?? 'belum';
            $meta    = $statusMeta[$stState] ?? $statusMeta['belum'];
            $isOpen  = ($stNum === $activeStage && !$metrics['is_all_done']);
            $stepData = $stepObj->data ?? [];
            $stepDocs = $stepObj->docs ?? [];

            // Hitung dokumen terisi
            $filledDocs = [];
            foreach ($def['docs'] as $dk => $dmeta) {
                if (!empty($stepDocs[$dk])) {
                    $filledDocs[$dk] = $stepDocs[$dk];
                }
            }
        ?>
            <div class="step-card" style="border-left: 4px solid <?= $meta['border'] ?>;">
                <div class="step-card-header" style="background:<?= $meta['bg'] ?>;" data-toggle="collapse" data-target="#collapseStage<?= $stNum ?>" aria-expanded="<?= $isOpen ? 'true' : 'false' ?>">
                    <div class="d-flex align-items-center" style="min-width:260px;">
                        <span class="step-num-badge <?= $stState === 'selesai' ? 'bg-success text-white' : ($stState === 'proses' ? 'bg-primary text-white' : ($stState === 'na' ? 'bg-dark text-white' : 'bg-secondary text-white')) ?>">
                            <?= $stState === 'selesai' ? '<i class="fas fa-check"></i>' : $stNum ?>
                        </span>
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size:0.9rem;"><?= htmlspecialchars($def['label']) ?></div>
                            <div class="small text-muted">
                                <?= htmlspecialchars($def['role']) ?>
                                <?php if (!empty($metrics['times'][$stNum])): ?>
                                    &nbsp;•&nbsp; <i class="fas fa-clock mr-1"></i><?= date('d/m/Y H:i', strtotime($metrics['times'][$stNum])) ?> WIB
                                <?php endif; ?>
                                <?php if (!empty($stepObj->nama_pengguna)): ?>
                                    &nbsp;•&nbsp; oleh <strong><?= htmlspecialchars($stepObj->nama_pengguna) ?></strong>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center flex-wrap" style="gap:6px;">
                        <?php if (!empty($filledDocs)): ?>
                            <span class="badge badge-light border text-primary" style="font-size:0.74rem;">
                                <i class="fas fa-paperclip mr-1"></i><?= count($filledDocs) ?>/<?= count($def['docs']) ?> Dokumen
                            </span>
                        <?php endif; ?>
                        <span class="badge <?= $meta['badge'] ?> px-2 py-1" style="font-size:0.76rem;">
                            <i class="fas <?= $meta['icon'] ?> mr-1"></i><?= $meta['label'] ?>
                        </span>
                        <i class="fas fa-chevron-down text-muted ml-1"></i>
                    </div>
                </div>

                <div id="collapseStage<?= $stNum ?>" class="collapse <?= $isOpen ? 'show' : '' ?>">
                    <div class="step-body">
                        <?php if (!empty($def['desc'])): ?>
                            <div class="alert alert-light border py-2 px-3 mb-3 small text-muted">
                                <i class="fas fa-info-circle text-primary mr-1"></i> <?= htmlspecialchars($def['desc']) ?>
                            </div>
                        <?php endif; ?>

                        <form class="form-save-step" action="<?= base_url('tracking/update_import_stage') ?>" method="post">
                            <input type="hidden" name="id_import" value="<?= $id_import_enc ?>">
                            <input type="hidden" name="stage" value="<?= $stNum ?>">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold small text-primary">Status Tahap <?= $stNum ?></label>
                                        <select name="status" class="form-control form-control-sm font-weight-bold">
                                            <option value="belum" <?= $stState === 'belum' ? 'selected' : '' ?>>Belum Dikerjakan</option>
                                            <option value="proses" <?= $stState === 'proses' ? 'selected' : '' ?>>Sedang Proses / Follow Up</option>
                                            <option value="selesai" <?= $stState === 'selesai' ? 'selected' : '' ?>>Selesai ✔</option>
                                            <?php if ($stNum === 9): ?>
                                                <option value="na" <?= $stState === 'na' ? 'selected' : '' ?>>Tidak Ada Lartas (N/A)</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold small">Waktu Aktual Selesai (Opsional)</label>
                                        <input type="datetime-local" name="completed_at" class="form-control form-control-sm"
                                            value="<?= !empty($metrics['times'][$stNum]) ? date('Y-m-d\TH:i', strtotime($metrics['times'][$stNum])) : '' ?>">
                                        <small class="text-muted">Kosongkan agar otomatis diisi waktu saat simpan.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- FIELD DATA SPESIFIK TAHAP -->
                            <?php if (!empty($def['fields'])): ?>
                                <div class="row">
                                    <?php foreach ($def['fields'] as $fk => $fmeta):
                                        $val = $stepData[$fk] ?? '';
                                        // Fallback dari header jika kosong di stepData
                                        if ($val === '' && isset($import_row->$fk)) {
                                            $val = (string)$import_row->$fk;
                                        }
                                        $colClass = ($fmeta['type'] === 'textarea') ? 'col-md-12' : 'col-md-6';
                                    ?>
                                        <div class="<?= $colClass ?>">
                                            <div class="form-group">
                                                <label class="font-weight-bold small"><?= htmlspecialchars($fmeta['label']) ?></label>
                                                <?php if ($fmeta['type'] === 'select'): ?>
                                                    <select name="data[<?= $fk ?>]" class="form-control form-control-sm">
                                                        <?php foreach ($fmeta['options'] as $optVal => $optLabel): ?>
                                                            <option value="<?= htmlspecialchars($optVal) ?>" <?= ((string)$val === (string)$optVal) ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($optLabel) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                <?php elseif ($fmeta['type'] === 'textarea'): ?>
                                                    <textarea name="data[<?= $fk ?>]" class="form-control form-control-sm" rows="2" placeholder="<?= htmlspecialchars($fmeta['placeholder'] ?? '') ?>"><?= htmlspecialchars($val) ?></textarea>
                                                <?php elseif ($fmeta['type'] === 'date'): ?>
                                                    <input type="date" name="data[<?= $fk ?>]" class="form-control form-control-sm" value="<?= htmlspecialchars($val) ?>">
                                                <?php elseif ($fmeta['type'] === 'number'): ?>
                                                    <input type="number" step="0.01" name="data[<?= $fk ?>]" class="form-control form-control-sm" value="<?= htmlspecialchars($val) ?>" placeholder="<?= htmlspecialchars($fmeta['placeholder'] ?? '') ?>">
                                                <?php else: ?>
                                                    <input type="text" name="data[<?= $fk ?>]" class="form-control form-control-sm" value="<?= htmlspecialchars($val) ?>" placeholder="<?= htmlspecialchars($fmeta['placeholder'] ?? '') ?>">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- DOKUMEN / LINK GOOGLE DRIVE & INTEGRASI OFFICE -->
                            <?php if (!empty($def['docs'])): ?>
                                <div class="mt-1 mb-2">
                                    <div class="small font-weight-bold text-dark mb-2">
                                        <i class="fas fa-link text-primary mr-1"></i> Dokumen Lampiran (Link Google Drive / Office)
                                    </div>
                                    <?php foreach ($def['docs'] as $dk => $dmeta):
                                        $docUrl  = $stepDocs[$dk] ?? '';
                                        $inputId = 'doc_' . $stNum . '_' . $dk;
                                    ?>
                                        <div class="doc-box">
                                            <label class="small font-weight-bold mb-1 d-block"><?= htmlspecialchars($dmeta['label']) ?></label>

                                            <?php if ($dmeta['type'] === 'office_po'): ?>
                                                <select class="form-control form-control-sm mb-1 sel-office-picker" data-target="#<?= $inputId ?>">
                                                    <option value="">-- Pilih dari Office PO Supplier (atau tempel link Drive di bawah) --</option>
                                                    <?php foreach ($list_po_supplier as $po):
                                                        $poUrl = base_url('purchase_order/show/detail/purchase_order/' . $po->id_po);
                                                        $poLbl = (!empty($po->no_po) ? $po->no_po : $po->kode_po) . (!empty($po->supplier) ? ' - ' . $po->supplier : '');
                                                    ?>
                                                        <option value="<?= htmlspecialchars($poUrl) ?>" <?= ($docUrl === $poUrl) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($poLbl) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php elseif ($dmeta['type'] === 'office_pbok'): ?>
                                                <select class="form-control form-control-sm mb-1 sel-office-picker" data-target="#<?= $inputId ?>">
                                                    <option value="">-- Pilih PBOK dari Office (atau tempel link Drive di bawah) --</option>
                                                    <?php foreach ($list_pbok as $pb):
                                                        $pbUrl = base_url('surat/show/detail_surat/pbok/' . $pb->id_pbok . '/1');
                                                        $pbLbl = $pb->kode_pbok . ' (' . ($pb->nama_pengaju ?: '-') . ') - ' . mb_strimwidth((string)$pb->ringkasan, 0, 45, '...');
                                                    ?>
                                                        <option value="<?= htmlspecialchars($pbUrl) ?>" <?= ($docUrl === $pbUrl) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($pbLbl) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php elseif ($dmeta['type'] === 'office_forwarder'): ?>
                                                <select class="form-control form-control-sm mb-1 sel-office-picker" data-target="#<?= $inputId ?>">
                                                    <option value="">-- Pilih Perbandingan Forwarder dari Office (atau tempel link Drive di bawah) --</option>
                                                    <?php foreach ($list_app_forwarder as $af):
                                                        $afUrl = base_url('forwarder/show/detail/' . $af->id);
                                                        $afLbl = $af->kode . ' - ' . mb_strimwidth((string)$af->nama, 0, 50, '...') . (!empty($af->nama_forwarder) ? ' [' . $af->nama_forwarder . ']' : '');
                                                    ?>
                                                        <option value="<?= htmlspecialchars($afUrl) ?>" <?= ($docUrl === $afUrl) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($afLbl) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php endif; ?>

                                            <div class="input-group input-group-sm">
                                                <input type="text" id="<?= $inputId ?>" name="docs[<?= $dk ?>]" class="form-control" value="<?= htmlspecialchars($docUrl) ?>" placeholder="https://drive.google.com/...">
                                                <?php if (!empty($docUrl)): ?>
                                                    <div class="input-group-append">
                                                        <a href="<?= htmlspecialchars($docUrl) ?>" target="_blank" class="btn btn-outline-primary font-weight-bold">
                                                            <i class="fas fa-external-link-alt mr-1"></i> Buka
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="form-group mb-2">
                                <label class="font-weight-bold small">Catatan Tahap <?= $stNum ?></label>
                                <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Catatan tambahan / kendala / progres tahap ini..."><?= htmlspecialchars($stepObj->catatan ?: '') ?></textarea>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary btn-sm font-weight-bold px-3">
                                    <i class="fas fa-save mr-1"></i> Simpan Tahap <?= $stNum ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<!-- RIWAYAT LOG AKTIVITAS IMPORT -->
<div class="card border mb-4">
    <div class="card-header bg-light py-2">
        <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-history text-primary mr-1"></i> Riwayat Log Aktivitas Tracking Import</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-striped table-bordered mb-0" style="font-size:0.83rem;">
                <thead class="thead-light">
                    <tr>
                        <th width="150">Waktu</th>
                        <th width="270">Tahapan Import</th>
                        <th>Catatan / Keterangan</th>
                        <th width="120">Dokumen</th>
                        <th width="150">Diupdate Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada riwayat log tahapan.</td>
                        </tr>
                    <?php else: foreach ($logs as $lg): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($lg->created_at)) ?> WIB</td>
                            <td><span class="badge badge-primary"><?= htmlspecialchars($lg->stage_label) ?></span></td>
                            <td><?= nl2br(htmlspecialchars($lg->catatan ?: '-')) ?></td>
                            <td>
                                <?php if (!empty($lg->bukti_link)): ?>
                                    <a href="<?= htmlspecialchars($lg->bukti_link) ?>" target="_blank" class="btn btn-xs btn-outline-info">
                                        <i class="fas fa-external-link-alt mr-1"></i> Buka Link
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($lg->nama_pengguna ?: 'System') ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL EDIT INFO UTAMA HEADER -->
<div class="modal fade" id="modalEditHeader" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formEditHeader" action="<?= base_url('tracking/update_import_header') ?>" method="post">
                <input type="hidden" name="id_import" value="<?= $id_import_enc ?>">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-1"></i> Edit Informasi Utama Import</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold">No. PO Supplier</label>
                                <input type="text" name="no_po_manufacture" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->no_po_manufacture) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="small font-weight-bold">Nama Manufacture / Supplier</label>
                                <input type="text" name="nama_manufacture" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->nama_manufacture) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="small font-weight-bold">Negara Asal</label>
                                <input type="text" name="negara_asal" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->negara_asal ?: '') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Daftar Unit / Barang</label>
                        <textarea name="daftar_unit" class="form-control form-control-sm" rows="2" required><?= htmlspecialchars($import_row->daftar_unit) ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold">Moda Transportasi</label>
                                <select name="moda_transportasi" class="form-control form-control-sm">
                                    <?php foreach (['Sea Freight (Kapal)', 'Air Freight (Pesawat)', 'Multimoda (Sea & Air)'] as $mOpt): ?>
                                        <option value="<?= $mOpt ?>" <?= ($import_row->moda_transportasi === $mOpt) ? 'selected' : '' ?>><?= $mOpt ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold">Nama Forwarder</label>
                                <input type="text" name="nama_forwarder" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->nama_forwarder ?: '') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold">Gudang Tujuan VYM</label>
                                <select name="nama_gudang_tujuan" class="form-control form-control-sm">
                                    <?php foreach (['Gudang Pusat', 'Gudang Jakarta', 'Gudang Jogja'] as $gOpt): ?>
                                        <option value="<?= $gOpt ?>" <?= ($import_row->nama_gudang_tujuan === $gOpt) ? 'selected' : '' ?>><?= $gOpt ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold">Pelabuhan / Airport Asal</label>
                                <input type="text" name="pelabuhan_asal" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->pelabuhan_asal ?: '') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold">Pelabuhan / Airport Tujuan</label>
                                <input type="text" name="pelabuhan_tujuan" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->pelabuhan_tujuan ?: '') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold">Estimasi Sampai Gudang</label>
                                <input type="date" name="tgl_estimasi_sampai_gudang" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->tgl_estimasi_sampai_gudang ?: '') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Keterangan Umum</label>
                        <textarea name="keterangan" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($import_row->keterangan ?: '') ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Picker Office (PO Supplier, PBOK, Approval Forwarder) -> auto isi input link
        $(document).on('change', '.sel-office-picker', function() {
            var url = $(this).val();
            var target = $(this).data('target');
            if (url && target) {
                $(target).val(url);
            }
        });

        // Simpan per Tahap (AJAX)
        $(document).on('submit', '.form-save-step', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn  = form.find('button[type="submit"]');
            var origHtml = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        alert(res.msg || 'Tahap berhasil disimpan!');
                        window.location.reload();
                    } else {
                        alert(res.msg || 'Gagal menyimpan tahap.');
                        btn.prop('disabled', false).html(origHtml);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan server.');
                    btn.prop('disabled', false).html(origHtml);
                }
            });
        });

        // Simpan Edit Header (AJAX)
        $('#formEditHeader').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn  = form.find('button[type="submit"]');
            var origHtml = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        alert(res.msg || 'Berhasil diperbarui!');
                        window.location.reload();
                    } else {
                        alert(res.msg || 'Gagal memperbarui informasi.');
                        btn.prop('disabled', false).html(origHtml);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan server.');
                    btn.prop('disabled', false).html(origHtml);
                }
            });
        });
    });
</script>
