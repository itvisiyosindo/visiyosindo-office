<?php
$id_import_enc = encrypt($import_row->id_import);
$curStage      = (int)$import_row->current_stage;
$nextStage     = min(9, $curStage + 1);
$times         = $metrics['times'];
?>

<header class="page-header">
    <h2><i class="icons fas fa-ship"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="<?= base_url('tracking/tracking_import') ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Tracking Import
    </a>
    <span class="badge badge-primary p-2" style="font-size:0.86rem;">
        <i class="fas fa-warehouse mr-1"></i> Tujuan: <?= htmlspecialchars($import_row->nama_gudang_tujuan ?: 'Gudang Pusat') ?>
    </span>
</div>

<div class="card mb-4">
    <div class="card-body" style="padding: 2rem;">
        <!-- HEADER JUDUL -->
        <div class="text-center mb-4">
            <span class="badge badge-info mb-2" style="font-size:0.82rem;">
                Sistem Tracking Import (<?= htmlspecialchars($import_row->moda_transportasi ?: 'Sea Freight') ?>)
            </span>
            <h3 class="mb-1"><strong><?= htmlspecialchars($import_row->kode_import) ?> — PO Manufacture: <?= htmlspecialchars($import_row->no_po_manufacture) ?></strong></h3>
            <div class="text-muted">
                Manufacture: <strong><?= htmlspecialchars($import_row->nama_manufacture) ?></strong>
                <?= !empty($import_row->negara_asal) ? (' (' . htmlspecialchars($import_row->negara_asal) . ')') : '' ?>
                &nbsp;|&nbsp; Tahapan Saat Ini: <strong class="text-primary"><?= htmlspecialchars($stage_defs[$curStage]['label'] ?? '-') ?></strong> (<?= $metrics['progress_pct'] ?>%)
            </div>
        </div>

        <!-- VISUAL TIMELINE 9 TAHAPAN IMPORT -->
        <?php
        if (function_exists('renderApprovalTimelineBar')) {
            $approverByStage = [
                1 => $import_row->no_po_manufacture . ' (' . $import_row->nama_manufacture . ')',
                2 => !empty($import_row->nama_forwarder) ? $import_row->nama_forwarder : 'Forwarder / Ekspedisi Import',
                3 => !empty($import_row->pelabuhan_asal) ? ('Port: ' . $import_row->pelabuhan_asal) : (!empty($import_row->nama_kapal_flight) ? $import_row->nama_kapal_flight : 'Unit Loading Kapal'),
                4 => !empty($import_row->nama_kapal_flight) ? ($import_row->nama_kapal_flight . (!empty($import_row->no_bl_awb) ? ' - B/L: ' . $import_row->no_bl_awb : '')) : 'Perjalanan Laut / Udara',
                5 => !empty($import_row->pelabuhan_tujuan) ? ('Tiba di: ' . $import_row->pelabuhan_tujuan) : 'Seaport / Airport Indonesia',
                6 => !empty($import_row->no_pib) ? ('PIB: ' . $import_row->no_pib . ' (Rp ' . number_format((float)$import_row->nominal_pajak_import, 0, ',', '.') . ')') : 'Pembayaran Pajak & Bea Cukai',
                7 => 'SPPB / Barang Keluar Gudang Seaport/Airport',
                8 => !empty($import_row->nama_ekspedisi_domestik) ? ($import_row->nama_ekspedisi_domestik . (!empty($import_row->no_resi_domestik) ? ' - Resi: ' . $import_row->no_resi_domestik : '')) : 'Hand-over Ekspedisi Domestik',
                9 => ($import_row->nama_gudang_tujuan ?: 'Gudang Pusat') . (!empty($import_row->pic_penerima_gudang) ? ' (PIC: ' . $import_row->pic_penerima_gudang . ')' : '')
            ];

            $timelineStages = [];
            $lastTimestamp = $times[1];
            for ($i = 2; $i <= 9; $i++) {
                $t = $times[$i];
                $prevFilled = !empty($times[$i - 1]);
                $status = !empty($t) ? 'approved' : ($prevFilled ? 'pending' : 'waiting_prev');

                $timelineStages[] = [
                    'label'          => $stage_defs[$i]['label'],
                    'approver'       => $approverByStage[$i],
                    'role'           => $stage_defs[$i]['role'],
                    'status'         => $status,
                    'timestamp'      => $t,
                    'prev_timestamp' => $lastTimestamp
                ];

                if (!empty($t)) {
                    $lastTimestamp = $t;
                }
            }

            echo renderApprovalTimelineBar(
                'Timeline 9 Tahapan Import (PO Manufacture ➔ Forwarder ➔ Loading Kapal ➔ Perjalanan ➔ Seaport/Airport ➔ Pajak ➔ Keluar Port ➔ Domestik ➔ ' . ($import_row->nama_gudang_tujuan ?: 'Gudang VYM') . ')',
                $timelineStages,
                $times[1] ?: $import_row->created_at,
                '1. PO ke Manufacture: ' . $import_row->no_po_manufacture . ' (' . $import_row->nama_manufacture . ')'
            );
        }
        ?>

        <!-- RINGKASAN KECEPATAN PROSES IMPORT (4 SEGMEN + TOTAL + KETEPATAN) -->
        <div class="mb-4 p-3 rounded shadow-sm" style="background: #f8fafc; border: 1px solid #cbd5e1;">
            <div class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                <i class="fas fa-tachometer-alt text-primary mr-1"></i> Ringkasan Kecepatan Proses Import & Ketepatan Estimasi Gudang
            </div>

            <div class="row">
                <?php
                $segCards = [
                    ['title' => '1. Origin (PO ➔ Loading Kapal)', 'data' => $metrics['dur_origin'], 'wait' => 'Menunggu Loading Kapal'],
                    ['title' => '2. Freight (Loading ➔ Sampai Port)', 'data' => $metrics['dur_freight'], 'wait' => 'Menunggu Kapal Sampai Port'],
                    ['title' => '3. Pajak & Clearance (Port ➔ Keluar Port)', 'data' => $metrics['dur_customs'], 'wait' => 'Menunggu Pajak & Keluar Port'],
                    ['title' => '4. Domestik (Keluar Port ➔ Sampai Gudang)', 'data' => $metrics['dur_domestik'], 'wait' => 'Menunggu Sampai Gudang VYM'],
                ];
                foreach ($segCards as $sc):
                    $d = $sc['data'];
                    $bg = '#f1f5f9'; $bdr = '#cbd5e1'; $txt = '#64748b';
                    if ($d['valid']) {
                        $bg = '#e0f2fe'; $bdr = '#7dd3fc'; $txt = '#0369a1';
                    }
                ?>
                    <div class="col-md-3 col-sm-6 mb-2">
                        <div class="p-2 rounded bg-white h-100" style="border: 1px solid #e2e8f0;">
                            <div class="text-muted small font-weight-bold"><?= $sc['title'] ?></div>
                            <div class="mt-1">
                                <?php if ($d['valid']): ?>
                                    <span style="display:inline-block; background:<?= $bg ?>; border:1px solid <?= $bdr ?>; color:<?= $txt ?>; font-weight:700; font-size:0.8rem; padding:3px 9px; border-radius:6px;">
                                        <i class="fas fa-clock mr-1"></i> <?= $d['text'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small"><em><?= $sc['wait'] ?></em></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="row mt-1">
                <div class="col-md-6 mb-2 mb-md-0">
                    <div class="p-2 rounded bg-white" style="border: 1px solid #e2e8f0;">
                        <div class="text-muted small font-weight-bold">Total Lead Time Import (PO Manufacture ➔ Sampai <?= htmlspecialchars($import_row->nama_gudang_tujuan) ?>)</div>
                        <div class="mt-1">
                            <?php if ($metrics['dur_total']['valid']): ?>
                                <span class="badge badge-success p-2" style="font-size:0.84rem;">
                                    <i class="fas fa-check-circle mr-1"></i> Selesai dalam <?= $metrics['dur_total']['text'] ?>
                                </span>
                            <?php else: ?>
                                <span class="badge badge-secondary p-1">Masih Berjalan (Tahap <?= $curStage ?> dari 9)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-2 rounded bg-white" style="border: 1px solid #e2e8f0;">
                        <div class="text-muted small font-weight-bold">Ketepatan Pengiriman ke Gudang Tujuan</div>
                        <div class="mt-1 d-flex flex-wrap align-items-center justify-content-between">
                            <span style="display:inline-block; background:<?= $metrics['ketepatan']['badge_bg'] ?>; border:1px solid <?= $metrics['ketepatan']['badge_bdr'] ?>; color:<?= $metrics['ketepatan']['badge_text'] ?>; font-weight:700; font-size:0.82rem; padding:3px 10px; border-radius:6px;">
                                <i class="fas <?= $metrics['ketepatan']['icon'] ?> mr-1"></i> <?= htmlspecialchars($metrics['ketepatan']['label']) ?>
                            </span>
                            <span class="small text-muted">
                                ETA Port: <strong><?= !empty($import_row->tgl_estimasi_sampai_port) ? date('d/m/Y', strtotime($import_row->tgl_estimasi_sampai_port)) : '-' ?></strong>
                                &nbsp;|&nbsp;
                                ETA Gudang: <strong><?= !empty($import_row->tgl_estimasi_sampai_gudang) ? date('d/m/Y', strtotime($import_row->tgl_estimasi_sampai_gudang)) : '-' ?></strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DETAIL INFORMASI IMPORT & FORM UPDATE TAHAPAN -->
        <div class="row">
            <!-- KOLOM KIRI: RINCIAN DOKUMEN, FORWARDER, PAJAK & DOMESTIK -->
            <div class="col-lg-6 mb-4">
                <div class="card border h-100">
                    <div class="card-header bg-dark text-white py-2">
                        <h6 class="mb-0 font-weight-bold"><i class="fas fa-clipboard-list mr-1"></i> Rincian Data Import, Pajak & Ekspedisi</h6>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-sm table-bordered mb-0" style="font-size:0.85rem;">
                            <tbody>
                                <tr>
                                    <th width="42%" class="bg-light">Kode Tracking Import</th>
                                    <td><strong><?= htmlspecialchars($import_row->kode_import) ?></strong></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">1. No. PO ke Manufacture</th>
                                    <td><strong><?= htmlspecialchars($import_row->no_po_manufacture) ?></strong></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Manufacture & Negara Asal</th>
                                    <td><?= htmlspecialchars($import_row->nama_manufacture) ?> <?= !empty($import_row->negara_asal) ? ('(' . htmlspecialchars($import_row->negara_asal) . ')') : '' ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Daftar Unit / Barang</th>
                                    <td><?= nl2br(htmlspecialchars($import_row->daftar_unit)) ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">2. Forwarder & Moda</th>
                                    <td>
                                        <strong><?= htmlspecialchars($import_row->nama_forwarder ?: '-') ?></strong>
                                        <span class="badge badge-secondary ml-1"><?= htmlspecialchars($import_row->moda_transportasi ?: '-') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">3-4. Kapal / Flight & No. B/L / AWB</th>
                                    <td>
                                        Kapal/Flight: <strong><?= htmlspecialchars($import_row->nama_kapal_flight ?: '-') ?></strong><br>
                                        No. B/L / AWB: <strong><?= htmlspecialchars($import_row->no_bl_awb ?: '-') ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">5. Seaport / Airport (Asal ➔ Tujuan)</th>
                                    <td>
                                        <?= htmlspecialchars($import_row->pelabuhan_asal ?: '-') ?>
                                        <i class="fas fa-arrow-right mx-1 text-primary"></i>
                                        <strong><?= htmlspecialchars($import_row->pelabuhan_tujuan ?: '-') ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">6-7. No. PIB & Pembayaran Pajak</th>
                                    <td>
                                        No. PIB / Aju: <strong><?= htmlspecialchars($import_row->no_pib ?: '-') ?></strong><br>
                                        Nominal Pajak: <strong class="text-success">Rp <?= number_format((float)$import_row->nominal_pajak_import, 0, ',', '.') ?></strong>
                                        <?php if (!empty($import_row->bukti_bayar_pajak)): ?>
                                            <br><a href="<?= htmlspecialchars($import_row->bukti_bayar_pajak) ?>" target="_blank" class="btn btn-xs btn-outline-primary mt-1">
                                                <i class="fas fa-external-link-alt mr-1"></i> Lihat Bukti Pajak / SPPB
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">8. Hand-over Ekspedisi Domestik</th>
                                    <td>
                                        Ekspedisi Lokal: <strong><?= htmlspecialchars($import_row->nama_ekspedisi_domestik ?: '-') ?></strong><br>
                                        No. Resi Lokal: <strong><?= htmlspecialchars($import_row->no_resi_domestik ?: '-') ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">9. Gudang Tujuan & Penerima</th>
                                    <td>
                                        <span class="badge badge-primary"><?= htmlspecialchars($import_row->nama_gudang_tujuan ?: 'Gudang Pusat') ?></span><br>
                                        PIC Penerima: <strong><?= htmlspecialchars($import_row->pic_penerima_gudang ?: '-') ?></strong>
                                        <?php if (!empty($import_row->bukti_terima_gudang)): ?>
                                            <br><a href="<?= htmlspecialchars($import_row->bukti_terima_gudang) ?>" target="_blank" class="btn btn-xs btn-outline-success mt-1">
                                                <i class="fas fa-external-link-alt mr-1"></i> Bukti Terima Gudang
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Keterangan</th>
                                    <td><?= nl2br(htmlspecialchars($import_row->keterangan ?: '-')) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: FORM UPDATE PROGRES TAHAPAN IMPORT -->
            <div class="col-lg-6 mb-4">
                <div class="card border h-100">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0 font-weight-bold"><i class="fas fa-check-double mr-1"></i> Update Tahapan & Informasi Tracking Import</h6>
                    </div>
                    <div class="card-body p-3">
                        <form id="formUpdateImportStage" action="<?= base_url('tracking/update_import_stage') ?>" method="post">
                            <input type="hidden" name="id_import" value="<?= $id_import_enc ?>">

                            <div class="form-group">
                                <label class="font-weight-bold text-primary">Pilih Tahapan Import yang Diupdate <span class="text-danger">*</span></label>
                                <select name="stage" id="selectStageUpdate" class="form-control font-weight-bold" required>
                                    <?php foreach ($stage_defs as $num => $sd):
                                        $isDone = !empty($times[$num]);
                                    ?>
                                        <option value="<?= $num ?>" <?= ($num == $nextStage) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($sd['label']) ?> <?= $isDone ? ' (Sudah Tercatat ✔)' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Waktu tahapan akan tercatat otomatis saat Anda menyimpan perubahan.</small>
                            </div>

                            <!-- Field Forwarder & Kapal -->
                            <div class="p-2 mb-2 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                <div class="small font-weight-bold text-dark mb-2"><i class="fas fa-ship text-primary mr-1"></i> Data Forwarder, Kapal & Port (Tahap 2 - 5)</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label class="small mb-1">Nama Forwarder Import</label>
                                            <input type="text" name="nama_forwarder" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->nama_forwarder ?: '') ?>" placeholder="Nama Forwarder">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label class="small mb-1">No. B/L atau AWB</label>
                                            <input type="text" name="no_bl_awb" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->no_bl_awb ?: '') ?>" placeholder="No. Bill of Lading / AWB">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label class="small mb-1">Nama Kapal / Flight</label>
                                            <input type="text" name="nama_kapal_flight" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->nama_kapal_flight ?: '') ?>" placeholder="Nama Kapal / Nomor Flight">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label class="small mb-1">Seaport / Airport Tujuan</label>
                                            <input type="text" name="pelabuhan_tujuan" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->pelabuhan_tujuan ?: '') ?>" placeholder="Tanjung Priok / Soetta">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Field Pajak & Bea Cukai -->
                            <div class="p-2 mb-2 rounded" style="background:#fffbeb; border:1px solid #fde68a;">
                                <div class="small font-weight-bold text-dark mb-2"><i class="fas fa-file-invoice-dollar text-warning mr-1"></i> Data Pembayaran Pajak & Keluar Port (Tahap 6 - 7)</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label class="small mb-1">No. PIB / Dokumen Bea Cukai</label>
                                            <input type="text" name="no_pib" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->no_pib ?: '') ?>" placeholder="Nomor PIB">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label class="small mb-1">Nominal Pajak Impor (Rp)</label>
                                            <input type="number" step="0.01" name="nominal_pajak_import" class="form-control form-control-sm" value="<?= (float)$import_row->nominal_pajak_import > 0 ? (float)$import_row->nominal_pajak_import : '' ?>" placeholder="Nominal Bea Masuk + PPN + PPh">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-1">
                                            <label class="small mb-1">Link Bukti Pembayaran Pajak / SPPB (Google Drive)</label>
                                            <input type="text" name="bukti_bayar_pajak" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->bukti_bayar_pajak ?: '') ?>" placeholder="https://drive.google.com/...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Field Ekspedisi Domestik & Gudang Tujuan -->
                            <div class="p-2 mb-2 rounded" style="background:#f0fdf4; border:1px solid #bbf7d0;">
                                <div class="small font-weight-bold text-dark mb-2"><i class="fas fa-truck text-success mr-1"></i> Hand-over Ekspedisi Domestik & Gudang VYM (Tahap 8 - 9)</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label class="small mb-1">Ekspedisi Domestik / Lokal</label>
                                            <input type="text" name="nama_ekspedisi_domestik" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->nama_ekspedisi_domestik ?: '') ?>" placeholder="Nama Ekspedisi Lokal">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label class="small mb-1">No. Resi / Surat Jalan Domestik</label>
                                            <input type="text" name="no_resi_domestik" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->no_resi_domestik ?: '') ?>" placeholder="No. Resi Lokal">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label class="small mb-1">Gudang Tujuan VYM</label>
                                            <select name="nama_gudang_tujuan" class="form-control form-control-sm">
                                                <?php foreach (['Gudang Pusat', 'Gudang Jakarta', 'Gudang Jogja'] as $gOpt): ?>
                                                    <option value="<?= $gOpt ?>" <?= ($import_row->nama_gudang_tujuan === $gOpt) ? 'selected' : '' ?>><?= $gOpt ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label class="small mb-1">PIC Penerima di Gudang</label>
                                            <input type="text" name="pic_penerima_gudang" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->pic_penerima_gudang ?: '') ?>" placeholder="Nama Penerima Gudang">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-1">
                                            <label class="small mb-1">Link Bukti Terima Barang di Gudang (Google Drive)</label>
                                            <input type="text" name="bukti_terima_gudang" class="form-control form-control-sm" value="<?= htmlspecialchars($import_row->bukti_terima_gudang ?: '') ?>" placeholder="https://drive.google.com/...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold small">Catatan Update Tahapan</label>
                                <textarea name="catatan_log" class="form-control form-control-sm" rows="2" placeholder="Catatan progres tahapan ini..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Simpan Update Tahapan Import
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIWAYAT LOG AKTIVITAS IMPORT -->
        <div class="card border">
            <div class="card-header bg-light py-2">
                <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-history text-primary mr-1"></i> Riwayat Log Tahapan Tracking Import</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered mb-0" style="font-size:0.84rem;">
                        <thead class="thead-light">
                            <tr>
                                <th width="160">Waktu</th>
                                <th width="260">Tahapan Import</th>
                                <th>Catatan / Keterangan</th>
                                <th width="140">Bukti / Dokumen</th>
                                <th width="160">Diupdate Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada riwayat log tahapan.</td>
                                </tr>
                            <?php else: foreach ($logs as $lg): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($lg->waktu_milestone)) ?> WIB</td>
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
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#formUpdateImportStage').on('submit', function(e) {
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
                        alert(res.msg || 'Tahapan berhasil diperbarui!');
                        window.location.reload();
                    } else {
                        alert(res.msg || 'Gagal memperbarui tahapan.');
                        btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Update Tahapan Import');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan server.');
                    btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Update Tahapan Import');
                }
            });
        });
    });
</script>
