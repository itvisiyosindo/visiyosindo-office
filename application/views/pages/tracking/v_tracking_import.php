<?php
$total_import    = count($import_list);
$cnt_origin_ship = 0; // Tahap 1-4
$cnt_port_pajak  = 0; // Tahap 5-7
$cnt_domestik    = 0; // Tahap 8
$cnt_selesai     = 0; // Tahap 9

foreach ($import_list as $r) {
    $st = (int)$r->current_stage;
    if ($st <= 4) {
        $cnt_origin_ship++;
    } elseif ($st <= 7) {
        $cnt_port_pajak++;
    } elseif ($st == 8) {
        $cnt_domestik++;
    } else {
        $cnt_selesai++;
    }
}
?>

<style>
    .import-kpi-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }
    .import-filter-pill {
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 6px 13px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        transition: all 0.15s ease;
    }
    .import-filter-pill:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    .import-filter-pill.active {
        background: #1e3a8a;
        color: #fff !important;
        border-color: #1e3a8a;
    }
    .stage-mini-dots {
        display: flex;
        gap: 3px;
        margin-top: 5px;
    }
    .stage-mini-dot {
        height: 6px;
        flex: 1;
        border-radius: 3px;
        background: #e2e8f0;
    }
    .stage-mini-dot.done {
        background: #10b981;
    }
    .stage-mini-dot.current {
        background: #2563eb;
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

<!-- RINGKASAN KPI -->
<div class="row mb-3">
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="import-kpi-card" style="border-left: 4px solid #2563eb;">
            <div class="text-muted small font-weight-bold">1-4. PO / FORWARDER / KAPAL</div>
            <div class="d-flex justify-content-between align-items-center mt-1">
                <h3 class="mb-0 font-weight-bold text-primary"><?= $cnt_origin_ship ?> <small class="text-muted" style="font-size:0.8rem;">Pengiriman</small></h3>
                <i class="fas fa-ship fa-lg text-primary" style="opacity:0.65;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="import-kpi-card" style="border-left: 4px solid #d97706;">
            <div class="text-muted small font-weight-bold">5-7. PORT / PAJAK PIB / KELUAR PORT</div>
            <div class="d-flex justify-content-between align-items-center mt-1">
                <h3 class="mb-0 font-weight-bold text-warning"><?= $cnt_port_pajak ?> <small class="text-muted" style="font-size:0.8rem;">Clearance</small></h3>
                <i class="fas fa-file-invoice-dollar fa-lg text-warning" style="opacity:0.75;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="import-kpi-card" style="border-left: 4px solid #0284c7;">
            <div class="text-muted small font-weight-bold">8. HAND-OVER EKSPEDISI DOMESTIK</div>
            <div class="d-flex justify-content-between align-items-center mt-1">
                <h3 class="mb-0 font-weight-bold text-info"><?= $cnt_domestik ?> <small class="text-muted" style="font-size:0.8rem;">Transit Lokal</small></h3>
                <i class="fas fa-truck-moving fa-lg text-info" style="opacity:0.7;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="import-kpi-card" style="border-left: 4px solid #16a34a;">
            <div class="text-muted small font-weight-bold">9. SAMPAI GUDANG PUSAT / JKT / JOGJA</div>
            <div class="d-flex justify-content-between align-items-center mt-1">
                <h3 class="mb-0 font-weight-bold text-success"><?= $cnt_selesai ?> <small class="text-muted" style="font-size:0.8rem;">Selesai</small></h3>
                <i class="fas fa-warehouse fa-lg text-success" style="opacity:0.7;"></i>
            </div>
        </div>
    </div>
</div>

<section class="card">
    <header class="card-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center" style="gap:10px;">
            <div>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal" data-target="#modalAddImport">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Tracking Import
                </button>
                <a href="<?= base_url('tracking/export_import?gudang=' . urlencode((string)$filter_gudang) . '&stage=' . urlencode((string)$filter_stage)) ?>" class="btn btn-success btn-sm font-weight-bold ml-1">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel Import
                </a>
            </div>

            <!-- Filter Gudang Tujuan -->
            <div class="d-flex flex-wrap align-items-center" style="gap:6px;">
                <span class="small font-weight-bold text-muted mr-1"><i class="fas fa-warehouse mr-1"></i>Gudang Tujuan:</span>
                <a href="<?= base_url('tracking/tracking_import') ?>" class="import-filter-pill <?= empty($filter_gudang) ? 'active' : '' ?>">
                    Semua (<?= $total_import ?>)
                </a>
                <a href="<?= base_url('tracking/tracking_import?gudang=' . urlencode('Gudang Pusat')) ?>" class="import-filter-pill <?= $filter_gudang === 'Gudang Pusat' ? 'active' : '' ?>">
                    <i class="fas fa-building mr-1"></i> Gudang Pusat
                </a>
                <a href="<?= base_url('tracking/tracking_import?gudang=' . urlencode('Gudang Jakarta')) ?>" class="import-filter-pill <?= $filter_gudang === 'Gudang Jakarta' ? 'active' : '' ?>">
                    <i class="fas fa-city mr-1"></i> Gudang Jakarta
                </a>
                <a href="<?= base_url('tracking/tracking_import?gudang=' . urlencode('Gudang Jogja')) ?>" class="import-filter-pill <?= $filter_gudang === 'Gudang Jogja' ? 'active' : '' ?>">
                    <i class="fas fa-map-marker-alt mr-1"></i> Gudang Jogja
                </a>
            </div>
        </div>
    </header>

    <div class="card-body">
        <!-- Alur 9 Tahapan Info Banner -->
        <div class="mb-3 p-2 rounded" style="background:#f8fafc; border:1px solid #cbd5e1; font-size:0.78rem; color:#334155;">
            <strong><i class="fas fa-route text-primary mr-1"></i> Alur 9 Tahapan Tracking Import:</strong>
            <span class="badge badge-light border">1. PO ke Manufacture</span> <i class="fas fa-arrow-right text-muted mx-1"></i>
            <span class="badge badge-light border">2. Order Forwarder</span> <i class="fas fa-arrow-right text-muted mx-1"></i>
            <span class="badge badge-light border">3. Unit Loading Kapal</span> <i class="fas fa-arrow-right text-muted mx-1"></i>
            <span class="badge badge-light border">4. Perjalanan Kapal</span> <i class="fas fa-arrow-right text-muted mx-1"></i>
            <span class="badge badge-light border">5. Sampai Seaport/Airport</span> <i class="fas fa-arrow-right text-muted mx-1"></i>
            <span class="badge badge-light border">6. Pembayaran Pajak</span> <i class="fas fa-arrow-right text-muted mx-1"></i>
            <span class="badge badge-light border">7. Keluar Gudang Port</span> <i class="fas fa-arrow-right text-muted mx-1"></i>
            <span class="badge badge-light border">8. Hand-over Ekspedisi Domestik</span> <i class="fas fa-arrow-right text-muted mx-1"></i>
            <span class="badge badge-light border">9. Sampai Gudang Pusat / Jakarta / Jogja</span>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0" id="tableTrackingImport" style="font-size:0.84rem;">
                <thead class="thead-dark">
                    <tr>
                        <th width="35" class="text-center">No</th>
                        <th>Kode & PO Manufacture</th>
                        <th>Daftar Unit / Barang</th>
                        <th>Forwarder & Kapal / Flight</th>
                        <th>Pajak Impor & Domestik</th>
                        <th width="190">Progress 9 Tahapan Import</th>
                        <th width="215">Kecepatan Proses (Lead Time)</th>
                        <th>Gudang Tujuan & Ketepatan</th>
                        <th width="85" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($import_list as $row):
                        $id_enc = encrypt($row->id_import);
                        $m = $metrics_map[$row->id_import];
                        $curStage = (int)$row->current_stage;
                        $stageInfo = $stage_defs[$curStage] ?? $stage_defs[1];

                        $stageBadgeClass = 'badge-primary';
                        if ($curStage == 9) $stageBadgeClass = 'badge-success';
                        elseif ($curStage >= 5 && $curStage <= 7) $stageBadgeClass = 'badge-warning';
                        elseif ($curStage == 8) $stageBadgeClass = 'badge-info';
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td>
                                <a href="<?= base_url('tracking/detail_import/' . $id_enc) ?>" class="font-weight-bold text-primary">
                                    <?= htmlspecialchars($row->kode_import) ?>
                                </a>
                                <div class="font-weight-bold text-dark mt-1">PO: <?= htmlspecialchars($row->no_po_manufacture) ?></div>
                                <div class="text-muted small">
                                    <i class="fas fa-industry mr-1"></i><?= htmlspecialchars($row->nama_manufacture) ?>
                                    <?php if (!empty($row->negara_asal)): ?>
                                        <span class="badge badge-light border ml-1"><?= htmlspecialchars($row->negara_asal) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div style="max-width:220px; white-space:normal; line-height:1.35;">
                                    <?= nl2br(htmlspecialchars($row->daftar_unit)) ?>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="badge badge-secondary"><?= htmlspecialchars($row->moda_transportasi ?: 'Sea Freight') ?></span>
                                </div>
                                <div class="font-weight-bold mt-1"><?= htmlspecialchars($row->nama_forwarder ?: '-') ?></div>
                                <?php if (!empty($row->no_bl_awb)): ?>
                                    <div class="small text-muted">B/L / AWB: <strong><?= htmlspecialchars($row->no_bl_awb) ?></strong></div>
                                <?php endif; ?>
                                <?php if (!empty($row->nama_kapal_flight)): ?>
                                    <div class="small text-muted"><i class="fas fa-anchor mr-1"></i><?= htmlspecialchars($row->nama_kapal_flight) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($row->pelabuhan_asal) || !empty($row->pelabuhan_tujuan)): ?>
                                    <div class="small text-info">
                                        <?= htmlspecialchars($row->pelabuhan_asal ?: '?') ?> <i class="fas fa-arrow-right mx-1"></i> <?= htmlspecialchars($row->pelabuhan_tujuan ?: '?') ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="small">
                                    <strong>PIB:</strong> <?= htmlspecialchars($row->no_pib ?: '-') ?>
                                </div>
                                <div class="small">
                                    <strong>Pajak:</strong>
                                    <?php if ((float)$row->nominal_pajak_import > 0): ?>
                                        <span class="text-success font-weight-bold">Rp <?= number_format((float)$row->nominal_pajak_import, 0, ',', '.') ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Belum diisi</span>
                                    <?php endif; ?>
                                </div>
                                <hr class="my-1">
                                <div class="small">
                                    <strong>Domestik:</strong> <?= htmlspecialchars($row->nama_ekspedisi_domestik ?: '-') ?>
                                </div>
                                <?php if (!empty($row->no_resi_domestik)): ?>
                                    <div class="small text-muted">Resi: <strong><?= htmlspecialchars($row->no_resi_domestik) ?></strong></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $stageBadgeClass ?> p-1" style="font-size:0.76rem; white-space:normal; text-align:left;">
                                    <?= htmlspecialchars($stageInfo['label']) ?>
                                </span>
                                <div class="stage-mini-dots" title="Tahap <?= $curStage ?> dari 9 (<?= $m['progress_pct'] ?>%)">
                                    <?php for ($s = 1; $s <= 9; $s++):
                                        $dotCls = '';
                                        if (!empty($m['times'][$s])) {
                                            $dotCls = ($s == $curStage && $curStage < 9) ? 'current' : 'done';
                                        }
                                    ?>
                                        <span class="stage-mini-dot <?= $dotCls ?>" title="<?= htmlspecialchars($stage_defs[$s]['label']) ?>"></span>
                                    <?php endfor; ?>
                                </div>
                                <div class="d-flex justify-content-between small text-muted mt-1">
                                    <span>Tahap <?= $curStage ?>/9</span>
                                    <strong><?= $m['progress_pct'] ?>%</strong>
                                </div>
                            </td>
                            <td>
                                <div class="small" style="line-height:1.45;">
                                    <div><span class="text-muted">Origin (1➔3):</span> <strong><?= $m['dur_origin']['valid'] ? $m['dur_origin']['text'] : '-' ?></strong></div>
                                    <div><span class="text-muted">Kapal (3➔5):</span> <strong><?= $m['dur_freight']['valid'] ? $m['dur_freight']['text'] : '-' ?></strong></div>
                                    <div><span class="text-muted">Pajak/Port (5➔7):</span> <strong><?= $m['dur_customs']['valid'] ? $m['dur_customs']['text'] : '-' ?></strong></div>
                                    <div><span class="text-muted">Domestik (7➔9):</span> <strong><?= $m['dur_domestik']['valid'] ? $m['dur_domestik']['text'] : '-' ?></strong></div>
                                    <div class="mt-1 pt-1" style="border-top:1px dashed #cbd5e1;">
                                        <span class="text-dark font-weight-bold">Total (1➔9):</span>
                                        <span class="badge badge-dark"><?= $m['dur_total']['valid'] ? $m['dur_total']['text'] : 'Berjalan' ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info" style="font-size:0.78rem;">
                                    <i class="fas fa-warehouse mr-1"></i><?= htmlspecialchars($row->nama_gudang_tujuan ?: 'Gudang Pusat') ?>
                                </span>
                                <?php if (!empty($row->tgl_estimasi_sampai_gudang)): ?>
                                    <div class="small text-muted mt-1">
                                        ETA Gudang: <strong><?= date('d/m/Y', strtotime($row->tgl_estimasi_sampai_gudang)) ?></strong>
                                    </div>
                                <?php endif; ?>
                                <div class="mt-1">
                                    <span class="badge" style="background:<?= $m['ketepatan']['badge_bg'] ?>; color:<?= $m['ketepatan']['badge_text'] ?>; border:1px solid <?= $m['ketepatan']['badge_bdr'] ?>; font-size:0.73rem;">
                                        <i class="fas <?= $m['ketepatan']['icon'] ?> mr-1"></i><?= htmlspecialchars($m['ketepatan']['label']) ?>
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="<?= base_url('tracking/detail_import/' . $id_enc) ?>" class="btn btn-info btn-sm" title="Detail & Update Tahapan Import">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete-import" data-id="<?= $id_enc ?>" data-kode="<?= htmlspecialchars($row->kode_import) ?>" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- MODAL TAMBAH TRACKING IMPORT -->
<div class="modal fade" id="modalAddImport" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formAddImport" action="<?= base_url('tracking/add_import') ?>" method="post">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-ship mr-1"></i> Tambah Data Tracking Import Baru
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Ambil dari PO Supplier (Opsional) -->
                    <div class="form-group p-2 rounded" style="background:#f1f5f9; border:1px solid #cbd5e1;">
                        <label class="font-weight-bold small text-primary mb-1">
                            <i class="fas fa-link mr-1"></i> Tarik Otomatis dari Pengajuan PO Supplier (Opsional)
                        </label>
                        <select name="id_po_supplier" id="selectPoSupplier" class="form-control form-control-sm">
                            <option value="">-- Input Manual atau Pilih PO Supplier yang Sudah Ada --</option>
                            <?php foreach ($list_po_supplier as $po):
                                $noPoText = !empty($po->no_po) ? $po->no_po : $po->kode_po;
                            ?>
                                <option value="<?= $po->id_po ?>"
                                    data-nopo="<?= htmlspecialchars($noPoText) ?>"
                                    data-supplier="<?= htmlspecialchars($po->supplier ?: '') ?>"
                                    data-items="<?= htmlspecialchars($po->items_summary ?: '') ?>"
                                    data-tgl="<?= !empty($po->tgl_pengajuan) ? date('Y-m-d\TH:i', strtotime($po->tgl_pengajuan)) : '' ?>">
                                    <?= htmlspecialchars($noPoText) ?> <?= !empty($po->supplier) ? (' - ' . htmlspecialchars($po->supplier)) : '' ?> (<?= htmlspecialchars(mb_strimwidth((string)$po->items_summary, 0, 60, '...')) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">1. No. PO ke Manufacture <span class="text-danger">*</span></label>
                                <input type="text" name="no_po_manufacture" id="inp_no_po" class="form-control" placeholder="Contoh: PO-IMP/2026/001" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="font-weight-bold">Nama Manufacture / Pabrik <span class="text-danger">*</span></label>
                                <input type="text" name="nama_manufacture" id="inp_manufacture" class="form-control" placeholder="Nama Manufacture Luar Negeri" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Negara Asal</label>
                                <input type="text" name="negara_asal" class="form-control" placeholder="China / Jerman / Korea">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Daftar Unit / Barang Import <span class="text-danger">*</span></label>
                        <textarea name="daftar_unit" id="inp_daftar_unit" class="form-control" rows="2" placeholder="Contoh: 2 Unit Mesin Ultrasound, 5 Set Probe..." required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Moda Transportasi</label>
                                <select name="moda_transportasi" class="form-control">
                                    <option value="Sea Freight (Kapal)">Sea Freight (Kapal Laut)</option>
                                    <option value="Air Freight (Pesawat)">Air Freight (Pesawat Udara)</option>
                                    <option value="Multimoda (Sea & Air)">Multimoda (Sea & Air)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">2. Ekspedisi / Forwarder Import</label>
                                <select name="id_forwarder" id="selectForwarderImport" class="form-control">
                                    <option value="">-- Pilih / Ketik Manual di Bawah --</option>
                                    <?php foreach ($list_forwarder as $fw): ?>
                                        <option value="<?= $fw->id ?>" data-nama="<?= htmlspecialchars($fw->nama) ?>"><?= htmlspecialchars($fw->nama) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" name="nama_forwarder" id="inp_nama_forwarder" class="form-control form-control-sm mt-1" placeholder="Atau ketik nama Forwarder...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">9. Gudang Tujuan VYM <span class="text-danger">*</span></label>
                                <select name="nama_gudang_tujuan" class="form-control" required>
                                    <option value="Gudang Pusat">Gudang Pusat</option>
                                    <option value="Gudang Jakarta">Gudang Jakarta</option>
                                    <option value="Gudang Jogja">Gudang Jogja</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">No. B/L atau AWB</label>
                                <input type="text" name="no_bl_awb" class="form-control" placeholder="Bill of Lading / Air Waybill">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Nama Kapal / Flight</label>
                                <input type="text" name="nama_kapal_flight" class="form-control" placeholder="Contoh: MV. Ever Given / V.102">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Pelabuhan / Airport Tujuan</label>
                                <input type="text" name="pelabuhan_tujuan" class="form-control" placeholder="Tanjung Priok / Soekarno-Hatta">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Tahapan Awal Saat Ini</label>
                                <select name="current_stage" class="form-control">
                                    <?php foreach ($stage_defs as $num => $sd): ?>
                                        <option value="<?= $num ?>"><?= htmlspecialchars($sd['label']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Estimasi Sampai Seaport/Airport</label>
                                <input type="date" name="tgl_estimasi_sampai_port" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Estimasi Sampai Gudang VYM</label>
                                <input type="date" name="tgl_estimasi_sampai_gudang" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold">Catatan / Keterangan Import</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan pengiriman import..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Simpan Tracking Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            $('#tableTrackingImport').DataTable({
                pageLength: 25,
                order: [[0, 'asc']]
            });
        }

        // Auto-fill dari dropdown PO Supplier
        var selPo = document.getElementById('selectPoSupplier');
        if (selPo) {
            selPo.addEventListener('change', function() {
                var opt = selPo.options[selPo.selectedIndex];
                if (!opt || !opt.value) return;
                document.getElementById('inp_no_po').value = opt.getAttribute('data-nopo') || '';
                document.getElementById('inp_manufacture').value = opt.getAttribute('data-supplier') || '';
                document.getElementById('inp_daftar_unit').value = opt.getAttribute('data-items') || '';
            });
        }

        // Auto-fill nama forwarder
        var selFw = document.getElementById('selectForwarderImport');
        if (selFw) {
            selFw.addEventListener('change', function() {
                var opt = selFw.options[selFw.selectedIndex];
                if (opt && opt.value) {
                    document.getElementById('inp_nama_forwarder').value = opt.getAttribute('data-nama') || '';
                }
            });
        }

        // Submit Form Add Import via AJAX
        $('#formAddImport').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        alert(res.msg || 'Berhasil ditambahkan!');
                        window.location.reload();
                    } else {
                        alert(res.msg || 'Gagal menyimpan data.');
                        btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Tracking Import');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan server.');
                    btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Tracking Import');
                }
            });
        });

        // Hapus Tracking Import
        $(document).on('click', '.btn-delete-import', function() {
            var idEnc = $(this).data('id');
            var kode = $(this).data('kode');
            if (!confirm('Hapus data Tracking Import ' + kode + '?')) return;

            $.ajax({
                url: '<?= base_url('tracking/delete_import/') ?>' + idEnc,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        window.location.reload();
                    } else {
                        alert(res.msg || 'Gagal menghapus data.');
                    }
                }
            });
        });
    });
</script>
