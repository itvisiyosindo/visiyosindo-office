<?php
// Enkripsi ID hanya jika diperlukan untuk link navigasi lain
$id_kirim_enc = encrypt($data_tracking[0]->id);
?>

<header class="page-header">
    <h2><i class="fas fa-file-alt"></i>&nbsp;Detail Tracking Dokumen</h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="mb-3">
    <a href="javascript:history.back()" class="btn btn-default btn-sm border">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<style>
    .detail-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        background: #fff;
        margin-bottom: 20px;
    }
    .detail-header {
        border-bottom: 1px solid #f0f0f0;
        padding: 15px 20px;
        background: #fafafa;
        border-radius: 12px 12px 0 0;
    }
    .detail-body { padding: 20px; }
    
    /* Styling Label & Value */
    .info-group { margin-bottom: 18px; }
    .info-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }
    .info-value {
        display: block;
        font-size: 14px;
        color: #333;
        font-weight: 600;
        line-height: 1.5;
    }
    .info-value.empty { color: #ccc; font-style: italic; font-weight: 400; }
    
    /* Status Badge */
    .badge-status {
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
    }

    /* History Table Detail */
    .table-detail thead th {
        background: #f8f9fa;
        text-transform: uppercase;
        font-size: 11px;
        color: #777;
        border-bottom: 2px solid #eee;
    }
    .text-detail { font-size: 13px; color: #555; }
</style>

<div class="row">
    <div class="col-lg-4">
        <div class="detail-card border-left-primary" style="border-left: 5px solid #0088cc !important;">
            <div class="detail-header">
                <h5 class="mb-0 font-weight-bold"><i class="fas fa-info-circle text-primary mr-2"></i>Data Dokumen</h5>
            </div>
            <div class="detail-body">
                <div class="info-group">
                    <span class="info-label">Kode Tracking</span>
                    <span class="info-value text-primary h5 font-weight-bold mb-0"><?= $data_tracking[0]->kode ?></span>
                </div>

                <div class="info-group">
                    <span class="info-label">Database Customer</span>
                    <span class="info-value"><?= $data_tracking[0]->nama_customer ?: '<span class="empty">Tidak terhubung</span>' ?></span>
                </div>

                <div class="info-group">
                    <span class="info-label">PIC Penerima</span>
                    <span class="info-value"><?= $data_tracking[0]->pic ?: '-' ?></span>
                </div>

                <div class="info-group mb-0">
                    <span class="info-label">Alamat Lengkap</span>
                    <span class="info-value font-weight-normal"><?= nl2br(htmlspecialchars($data_tracking[0]->alamat)) ?></span>
                </div>
            </div>
        </div>

        <div class="detail-card border-left-success" style="border-left: 5px solid #47a447 !important;">
            <div class="detail-header">
                <h5 class="mb-0 font-weight-bold"><i class="fas fa-truck text-success mr-2"></i>Logistik & Pengiriman</h5>
            </div>
            <div class="detail-body">
                <div class="row">
                    <div class="col-6">
                        <div class="info-group">
                            <span class="info-label">Ekspedisi</span>
                            <span class="info-value"><span class="badge badge-dark font-weight-normal"><?= $data_tracking[0]->ekspedisi ?: 'Internal' ?></span></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-group">
                            <span class="info-label">No. Resi</span>
                            <span class="info-value"><?= $data_tracking[0]->no_resi ?: '-' ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="info-group mb-0">
                            <span class="info-label">Tgl Kirim</span>
                            <span class="info-value"><?= date('d/m/Y', strtotime($data_tracking[0]->tgl_kirim)) ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-group mb-0">
                            <span class="info-label">Est. Sampai</span>
                            <span class="info-value"><?= $data_tracking[0]->tgl_sampai ? date('d/m/Y', strtotime($data_tracking[0]->tgl_sampai)) : '-' ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="detail-card">
            <div class="detail-header">
                <h5 class="mb-0 font-weight-bold"><i class="fas fa-history text-info mr-2"></i>History Status Dokumen</h5>
            </div>
            <div class="detail-body p-0">
                <div class="table-responsive">
                    <table class="table table-detail mb-0">
                        <thead>
                            <tr>
                                <th class="pl-4" width="20%">Waktu</th>
                                <th width="20%">Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_status as $row) : ?>
                                <tr>
                                    <td class="pl-4">
                                        <strong><?= date('d/m/Y', strtotime($row->created_at)) ?></strong><br>
                                        <small class="text-muted"><?= date('H:i', strtotime($row->created_at)) ?> WIB</small>
                                    </td>
                                    <td>
                                        <?php 
                                            $color = ($row->id_status == 5) ? 'badge-success' : 'badge-info';
                                        ?>
                                        <span class="badge <?= $color ?> badge-status">
                                            <?= $list_status[$row->id_status] ?? 'Unknown' ?>
                                        </span>
                                    </td>
                                    <td class="pr-4">
                                        <div class="text-detail">
                                            <?= nl2br(htmlspecialchars($row->keterangan_konfirmasi)) ?>
                                        </div>
                                        <?php if ($row->id_status == 5): ?>
                                            <div class="mt-2 p-2 rounded" style="background: #f0fff4; border: 1px solid #c6f6d5;">
                                                <small class="d-block text-muted font-weight-bold uppercase">Informasi Penerimaan:</small>
                                                <span class="text-success font-weight-bold">Diterima oleh: <?= htmlspecialchars($row->nama_penerima) ?></span>
                                                <?php if($row->bukti_penerima): ?>
                                                    <br><a href="<?= $row->bukti_penerima ?>" target="_blank" class="small"><i class="fas fa-external-link-alt mr-1"></i>Lihat Bukti Foto</a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if(empty($data_status)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted italic">
                                        <i class="fas fa-box-open fa-2x d-block mb-2 opacity-2"></i>
                                        Belum ada history status untuk dokumen ini.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-right mt-3 no-print">
    <button onclick="window.print()" class="btn btn-default shadow-sm border">
        <i class="fas fa-print mr-1"></i> Cetak Halaman
    </button>
</div>