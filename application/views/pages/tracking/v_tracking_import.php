<?php
$total_import = count($import_list);
$phase_counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
$cnt_selesai  = 0;

foreach ($import_list as $r) {
    $m = $metrics_map[$r->id_import];
    if ($m['is_all_done']) {
        $cnt_selesai++;
    } else {
        $ap = (int)$m['active_phase'];
        if (isset($phase_counts[$ap])) {
            $phase_counts[$ap]++;
        }
    }
}
?>

<style>
    .import-kpi-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        text-decoration: none !important;
        display: block;
    }
    .import-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }
    .import-filter-pill {
        border-radius: 8px;
        font-size: 0.81rem;
        font-weight: 600;
        padding: 5px 12px;
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
        gap: 2px;
        margin-top: 5px;
    }
    .stage-mini-dot {
        height: 7px;
        flex: 1;
        border-radius: 2px;
        background: #e2e8f0;
    }
    .stage-mini-dot.selesai { background: #10b981; }
    .stage-mini-dot.na      { background: #94a3b8; }
    .stage-mini-dot.proses  { background: #2563eb; }
</style>

<header class="page-header">
    <h2><i class="icons fas fa-ship"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<!-- RINGKASAN KPI 5 FASE + SELESAI -->
<div class="row mb-3">
    <?php foreach ($phase_defs as $pNum => $pDef):
        $isActive = ((string)$filter_phase === (string)$pNum);
        $phaseUrl = base_url('tracking/tracking_import?phase=' . $pNum . (!empty($filter_gudang) ? '&gudang=' . urlencode($filter_gudang) : ''));
    ?>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
            <a href="<?= $phaseUrl ?>" class="import-kpi-card" style="border-left: 4px solid <?= $pDef['color'] ?>; <?= $isActive ? 'background:#f0f9ff; border-color:' . $pDef['color'] . ';' : '' ?>">
                <div class="text-muted font-weight-bold" style="font-size:0.72rem; letter-spacing:0.2px;">
                    FASE <?= $pNum ?>: <?= strtoupper($pDef['short']) ?>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h4 class="mb-0 font-weight-bold" style="color:<?= $pDef['color'] ?>;">
                        <?= $phase_counts[$pNum] ?> <small class="text-muted" style="font-size:0.74rem;">PO</small>
                    </h4>
                    <i class="fas <?= $pDef['icon'] ?>" style="color:<?= $pDef['color'] ?>; opacity:0.65;"></i>
                </div>
                <div class="text-muted" style="font-size:0.7rem;">Tahap <?= min($pDef['stages']) ?>–<?= max($pDef['stages']) ?></div>
            </a>
        </div>
    <?php endforeach; ?>
    <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
        <a href="<?= base_url('tracking/tracking_import' . (!empty($filter_gudang) ? '?gudang=' . urlencode($filter_gudang) : '')) ?>" class="import-kpi-card" style="border-left: 4px solid #059669;">
            <div class="text-muted font-weight-bold" style="font-size:0.72rem;">SELESAI 14/14</div>
            <div class="d-flex justify-content-between align-items-center mt-1">
                <h4 class="mb-0 font-weight-bold text-success">
                    <?= $cnt_selesai ?> <small class="text-muted" style="font-size:0.74rem;">PO</small>
                </h4>
                <i class="fas fa-check-double text-success" style="opacity:0.7;"></i>
            </div>
            <div class="text-muted" style="font-size:0.7rem;">Total Aktif: <?= $total_import ?> PO</div>
        </a>
    </div>
</div>

<section class="card">
    <header class="card-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center" style="gap:10px;">
            <div>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal" data-target="#modalAddImport">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Tracking Import
                </button>
                <a href="<?= base_url('tracking/export_import?gudang=' . urlencode((string)$filter_gudang) . '&phase=' . urlencode((string)$filter_phase)) ?>" class="btn btn-success btn-sm font-weight-bold ml-1">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel (14 Tahap)
                </a>
                <?php if (!empty($filter_phase) || !empty($filter_gudang)): ?>
                    <a href="<?= base_url('tracking/tracking_import') ?>" class="btn btn-outline-secondary btn-sm ml-1">
                        <i class="fas fa-times mr-1"></i> Reset Filter
                    </a>
                <?php endif; ?>
            </div>

            <!-- Filter Gudang Tujuan -->
            <div class="d-flex flex-wrap align-items-center" style="gap:6px;">
                <span class="small font-weight-bold text-muted mr-1"><i class="fas fa-warehouse mr-1"></i>Gudang:</span>
                <a href="<?= base_url('tracking/tracking_import' . (!empty($filter_phase) ? '?phase=' . urlencode($filter_phase) : '')) ?>" class="import-filter-pill <?= empty($filter_gudang) ? 'active' : '' ?>">
                    Semua (<?= $total_import ?>)
                </a>
                <?php foreach (['Gudang Pusat', 'Gudang Jakarta', 'Gudang Jogja'] as $gName): ?>
                    <a href="<?= base_url('tracking/tracking_import?gudang=' . urlencode($gName) . (!empty($filter_phase) ? '&phase=' . urlencode($filter_phase) : '')) ?>" class="import-filter-pill <?= $filter_gudang === $gName ? 'active' : '' ?>">
                        <?= $gName ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </header>

    <div class="card-body">
        <!-- Info Alur 14 Tahap -->
        <div class="mb-3 p-2 rounded" style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.79rem; color:#1e3a8a;">
            <div>
                <strong><i class="fas fa-tasks text-primary mr-1"></i> Checklist 14 Tahap Staff Import (Fleksibel & Terintegrasi Office):</strong>
                Tahap 1 terhubung otomatis dengan <em>PO Supplier (Office)</em>, Tahap 4/10/14 dapat memilih <em>PBOK (Office)</em>, Tahap 5 terhubung dengan <em>Perbandingan Forwarder (Office)</em>, dan Tahap 12 dengan <em>Penerimaan Warehouse</em>. Semua dokumen mendukung <strong>Link Google Drive</strong>. Klik <strong>Detail (<i class="fas fa-folder-open"></i>)</strong> untuk update checklist tiap tahap.
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0" id="tableTrackingImport" style="font-size:0.83rem;">
                <thead class="thead-dark">
                    <tr>
                        <th width="32" class="text-center">No</th>
                        <th>Kode & PO Supplier</th>
                        <th>Daftar Unit / Barang</th>
                        <th>HS Code & Lartas</th>
                        <th>Dokumen Shipping & Pajak</th>
                        <th width="205">Progress 14 Tahap</th>
                        <th>Gudang & Jatuh Tempo Forwarder</th>
                        <th width="82" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($import_list as $row):
                        $id_enc = encrypt($row->id_import);
                        $m = $metrics_map[$row->id_import];
                        $activeStage = $m['active_stage'];
                        $stageInfo = $stage_defs[$activeStage] ?? $stage_defs[1];
                        $phaseInfo = $phase_defs[$m['active_phase']] ?? $phase_defs[1];
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
                                <div style="max-width:210px; white-space:normal; line-height:1.35;">
                                    <?= nl2br(htmlspecialchars($row->daftar_unit)) ?>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <strong>HS Code:</strong>
                                    <?= !empty($row->hs_code) ? '<span class="badge badge-light border font-weight-bold">' . htmlspecialchars($row->hs_code) . '</span>' : '<span class="text-muted">-</span>' ?>
                                </div>
                                <div class="mt-1">
                                    <?php if ($row->ada_lartas === 'ada'): ?>
                                        <span class="badge badge-danger"><i class="fas fa-exclamation-circle mr-1"></i>Ada Lartas</span>
                                    <?php elseif ($row->ada_lartas === 'tidak'): ?>
                                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Tidak Ada Lartas</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Lartas: Belum Dicek</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="small"><strong>Forwarder:</strong> <?= htmlspecialchars($row->nama_forwarder ?: '-') ?></div>
                                <div class="small"><strong>AWB/BL:</strong> <?= htmlspecialchars($row->no_bl_awb ?: '-') ?></div>
                                <div class="small"><strong>PIB:</strong> <?= htmlspecialchars($row->no_pib ?: '-') ?></div>
                                <div class="small">
                                    <strong>Pajak:</strong>
                                    <?php if ((float)$row->nominal_pajak_import > 0): ?>
                                        <span class="text-success font-weight-bold">Rp <?= number_format((float)$row->nominal_pajak_import, 0, ',', '.') ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($m['is_all_done']): ?>
                                    <span class="badge badge-success p-1" style="font-size:0.75rem;">
                                        <i class="fas fa-check-double mr-1"></i> Selesai Lengkap (14/14)
                                    </span>
                                <?php else: ?>
                                    <span class="badge p-1 text-white" style="background:<?= $phaseInfo['color'] ?>; font-size:0.74rem; white-space:normal; text-align:left;">
                                        <?= htmlspecialchars($stageInfo['label']) ?>
                                    </span>
                                <?php endif; ?>

                                <div class="stage-mini-dots" title="Progress <?= $m['completed_cnt'] ?>/14 tahap (<?= $m['progress_pct'] ?>%)">
                                    <?php for ($s = 1; $s <= 14; $s++):
                                        $stState = $m['step_statuses'][$s] ?? 'belum';
                                    ?>
                                        <span class="stage-mini-dot <?= $stState ?>" title="<?= htmlspecialchars($stage_defs[$s]['label']) ?> (<?= strtoupper($stState) ?>)"></span>
                                    <?php endfor; ?>
                                </div>
                                <div class="d-flex justify-content-between small text-muted mt-1">
                                    <span><?= $m['completed_cnt'] ?>/14 Tahap</span>
                                    <strong><?= $m['progress_pct'] ?>%</strong>
                                </div>
                                <?php if ($m['dur_to_warehouse']['valid']): ?>
                                    <div class="small text-muted">
                                        <i class="fas fa-warehouse mr-1"></i>Ke WH: <strong><?= $m['dur_to_warehouse']['text'] ?></strong>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-info" style="font-size:0.76rem;">
                                    <i class="fas fa-warehouse mr-1"></i><?= htmlspecialchars($row->nama_gudang_tujuan ?: 'Gudang Pusat') ?>
                                </span>
                                <?php if (!empty($row->tgl_siap_jemput) && strpos((string)$row->tgl_siap_jemput, '0000-00-00') === false): ?>
                                    <div class="small text-muted mt-1">
                                        Siap Jemput: <strong><?= date('d/m/Y', strtotime($row->tgl_siap_jemput)) ?></strong>
                                    </div>
                                <?php endif; ?>
                                <div class="mt-1">
                                    <span class="badge" style="background:<?= $m['jatuh_tempo']['badge_bg'] ?>; color:<?= $m['jatuh_tempo']['badge_text'] ?>; border:1px solid <?= $m['jatuh_tempo']['badge_bdr'] ?>; font-size:0.72rem;">
                                        <i class="fas <?= $m['jatuh_tempo']['icon'] ?> mr-1"></i><?= htmlspecialchars($m['jatuh_tempo']['label']) ?>
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="<?= base_url('tracking/detail_import/' . $id_enc) ?>" class="btn btn-primary btn-sm" title="Buka Checklist 14 Tahap">
                                        <i class="fas fa-folder-open"></i>
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
                    <!-- Tarik Otomatis dari PO Supplier Office -->
                    <div class="form-group p-2 rounded" style="background:#f1f5f9; border:1px solid #cbd5e1;">
                        <label class="font-weight-bold small text-primary mb-1">
                            <i class="fas fa-link mr-1"></i> Tahap 1: Tarik Otomatis dari PO Supplier (Office) — Opsional
                        </label>
                        <select name="id_po_supplier" id="selectPoSupplier" class="form-control form-control-sm">
                            <option value="">-- Input Manual atau Pilih PO Supplier dari Office --</option>
                            <?php foreach ($list_po_supplier as $po):
                                $noPoText = !empty($po->no_po) ? $po->no_po : $po->kode_po;
                                $poUrl    = base_url('purchase_order/show/detail/purchase_order/' . $po->id_po);
                            ?>
                                <option value="<?= $po->id_po ?>"
                                    data-nopo="<?= htmlspecialchars($noPoText) ?>"
                                    data-supplier="<?= htmlspecialchars($po->supplier ?: '') ?>"
                                    data-items="<?= htmlspecialchars($po->items_summary ?: '') ?>"
                                    data-items-json="<?= htmlspecialchars(json_encode($po->items ?? [], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>"
                                    data-url="<?= htmlspecialchars($poUrl) ?>">
                                    <?= htmlspecialchars($noPoText) ?> <?= !empty($po->supplier) ? (' - ' . htmlspecialchars($po->supplier)) : '' ?> (<?= htmlspecialchars(mb_strimwidth((string)$po->items_summary, 0, 55, '...')) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <div id="poProductsPickerBox" class="mt-2 pt-2 border-top" style="display:none;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="font-weight-bold small text-dark mb-0">
                                    <i class="fas fa-boxes text-primary mr-1"></i> Pilih Produk dari PO Supplier (nama produk sama persis dengan PO):
                                </label>
                                <div>
                                    <button type="button" class="btn btn-link btn-sm p-0 mr-2 small" id="btnCheckAllPoItems">Pilih Semua</button>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-muted small" id="btnUncheckAllPoItems">Batal Semua</button>
                                </div>
                            </div>
                            <div id="poProductsList" class="bg-white border rounded p-2" style="max-height:170px; overflow-y:auto; font-size:0.82rem;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">No. PO Supplier <span class="text-danger">*</span></label>
                                <input type="text" name="no_po_manufacture" id="inp_no_po" class="form-control" placeholder="Contoh: PO-IMP/2026/001" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="font-weight-bold">Nama Supplier / Manufacture <span class="text-danger">*</span></label>
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

                    <div class="form-group">
                        <label class="font-weight-bold">Link Dokumen PO Supplier (Office / Google Drive)</label>
                        <input type="text" name="doc_po_supplier" id="inp_doc_po" class="form-control form-control-sm" placeholder="Otomatis terisi jika pilih PO Office di atas, atau paste Link Drive...">
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
                                <label class="font-weight-bold">Gudang Tujuan VYM <span class="text-danger">*</span></label>
                                <select name="nama_gudang_tujuan" class="form-control" required>
                                    <option value="Gudang Pusat">Gudang Pusat</option>
                                    <option value="Gudang Jakarta">Gudang Jakarta</option>
                                    <option value="Gudang Jogja">Gudang Jogja</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Estimasi Sampai Gudang</label>
                                <input type="date" name="tgl_estimasi_sampai_gudang" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold">Catatan Awal</label>
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

        // Auto-fill dari dropdown PO Supplier Office + munculkan daftar produk persis seperti di PO
        var selPo = document.getElementById('selectPoSupplier');
        var poProductsBox = document.getElementById('poProductsPickerBox');
        var poProductsList = document.getElementById('poProductsList');

        function syncSelectedPoProducts() {
            if (!poProductsList) return;
            var checked = poProductsList.querySelectorAll('input.chk-po-product:checked');
            var parts = [];
            var firstSup = '';
            checked.forEach(function(cb) {
                var nama = cb.getAttribute('data-nama') || '';
                var qty = cb.getAttribute('data-qty') || '1';
                var sup = cb.getAttribute('data-sup') || '';
                if (nama) parts.push(nama + ' (' + qty + ')');
                if (!firstSup && sup) firstSup = sup;
            });
            document.getElementById('inp_daftar_unit').value = parts.join(', ');
            if (firstSup) {
                document.getElementById('inp_manufacture').value = firstSup;
            }
        }

        if (selPo) {
            selPo.addEventListener('change', function() {
                var opt = selPo.options[selPo.selectedIndex];
                if (!opt || !opt.value) {
                    if (poProductsBox) poProductsBox.style.display = 'none';
                    if (poProductsList) poProductsList.innerHTML = '';
                    return;
                }
                document.getElementById('inp_no_po').value = opt.getAttribute('data-nopo') || '';
                document.getElementById('inp_manufacture').value = opt.getAttribute('data-supplier') || '';
                document.getElementById('inp_doc_po').value = opt.getAttribute('data-url') || '';

                var items = [];
                try {
                    items = JSON.parse(opt.getAttribute('data-items-json') || '[]');
                } catch (e) {
                    items = [];
                }

                if (poProductsBox && poProductsList && items.length > 0) {
                    poProductsList.innerHTML = '';
                    items.forEach(function(item, idx) {
                        var wrap = document.createElement('div');
                        wrap.className = 'custom-control custom-checkbox mb-1';

                        var cb = document.createElement('input');
                        cb.type = 'checkbox';
                        cb.className = 'custom-control-input chk-po-product';
                        cb.id = 'chkPoProd_' + idx;
                        cb.checked = true;
                        cb.setAttribute('data-nama', item.nama_barang || '');
                        cb.setAttribute('data-qty', item.qty || '1');
                        cb.setAttribute('data-sup', item.supplier || '');
                        cb.addEventListener('change', syncSelectedPoProducts);

                        var lbl = document.createElement('label');
                        lbl.className = 'custom-control-label d-block';
                        lbl.htmlFor = cb.id;
                        lbl.style.cursor = 'pointer';

                        var title = document.createElement('strong');
                        title.textContent = item.nama_barang || '-';
                        lbl.appendChild(title);

                        var meta = document.createElement('span');
                        meta.className = 'text-muted ml-1';
                        var metaTxt = '(Qty: ' + (item.qty || '1') + ')';
                        if (item.kode_barang) metaTxt += ' [' + item.kode_barang + ']';
                        if (item.supplier) metaTxt += ' — ' + item.supplier;
                        meta.textContent = metaTxt;
                        lbl.appendChild(meta);

                        wrap.appendChild(cb);
                        wrap.appendChild(lbl);
                        poProductsList.appendChild(wrap);
                    });
                    poProductsBox.style.display = 'block';
                    syncSelectedPoProducts();
                } else {
                    if (poProductsBox) poProductsBox.style.display = 'none';
                    document.getElementById('inp_daftar_unit').value = opt.getAttribute('data-items') || '';
                }
            });
        }

        var btnCheckAll = document.getElementById('btnCheckAllPoItems');
        if (btnCheckAll) {
            btnCheckAll.addEventListener('click', function() {
                if (!poProductsList) return;
                poProductsList.querySelectorAll('input.chk-po-product').forEach(function(cb) { cb.checked = true; });
                syncSelectedPoProducts();
            });
        }
        var btnUncheckAll = document.getElementById('btnUncheckAllPoItems');
        if (btnUncheckAll) {
            btnUncheckAll.addEventListener('click', function() {
                if (!poProductsList) return;
                poProductsList.querySelectorAll('input.chk-po-product').forEach(function(cb) { cb.checked = false; });
                syncSelectedPoProducts();
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
