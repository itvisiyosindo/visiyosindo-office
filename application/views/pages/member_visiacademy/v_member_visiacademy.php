<?php
$resolveKatalogUrl = function ($val) {
    $val = trim((string) $val);
    if ($val === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $val)) {
        return $val;
    }
    return base_url(ltrim($val, '/'));
};

$katalogList     = (isset($katalog) && is_array($katalog)) ? $katalog : [];
$katalogTotal    = count($katalogList);
$katalogAktif    = 0;
$katalogNonaktif = 0;
$katalogBrosur   = 0;
foreach ($katalogList as $kp) {
    if (($kp['status'] ?? 'aktif') === 'aktif') {
        $katalogAktif++;
    } else {
        $katalogNonaktif++;
    }
    if (!empty($kp['url_brosur'])) {
        $katalogBrosur++;
    }
}
$isKatalogTab = (isset($active_tab) && $active_tab === 'katalog');
?>

<header class="page-header">
    <h2><i class="fas fa-users"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>Home</span></li>
            <li><span><?= $page_title ?></span></li>
        </ol>
    </div>
</header>

<!-- STATS CARDS -->
<div class="row mb-3">
    <!-- Total Member -->
    <div class="col-xl-3 col-md-6 col-sm-6 mb-3">
        <div class="card shadow-sm border-0 stat-card" style="background: linear-gradient(135deg, #4f46e5, #6366f1) !important; border-radius: 12px; overflow: hidden;">
            <div class="card-body p-3" style="background: transparent !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-0 small font-weight-semibold" style="color: rgba(255,255,255,0.85) !important; margin: 0; font-size: 11px;">Total Member Aktif</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #ffffff !important; margin: 0; font-size: 22px;"><?= number_format($summary['total_member']) ?></h3>
                    </div>
                    <div style="background: rgba(255,255,255,0.2) !important; padding: 10px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-check text-white" style="font-size: 20px; color: #ffffff !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Kebutuhan/Deal -->
    <div class="col-xl-3 col-md-6 col-sm-6 mb-3">
        <div class="card shadow-sm border-0 stat-card" style="background: linear-gradient(135deg, #0d9488, #14b8a6) !important; border-radius: 12px; overflow: hidden;">
            <div class="card-body p-3" style="background: transparent !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-0 small font-weight-semibold" style="color: rgba(255,255,255,0.85) !important; margin: 0; font-size: 11px;">Total Info Kebutuhan/Deal</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #ffffff !important; margin: 0; font-size: 22px;"><?= number_format($summary['total_deal']) ?></h3>
                    </div>
                    <div style="background: rgba(255,255,255,0.2) !important; padding: 10px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-handshake text-white" style="font-size: 20px; color: #ffffff !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Nilai Deal -->
    <div class="col-xl-3 col-md-6 col-sm-6 mb-3">
        <div class="card shadow-sm border-0 stat-card" style="background: linear-gradient(135deg, #059669, #10b981) !important; border-radius: 12px; overflow: hidden;">
            <div class="card-body p-3" style="background: transparent !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-0 small font-weight-semibold" style="color: rgba(255,255,255,0.85) !important; margin: 0; font-size: 11px;">Nilai Transaksi Deal</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #ffffff !important; margin: 0; font-size: 18px;">Rp <?= number_format($summary['total_nilai_deal'], 0, ',', '.') ?></h3>
                    </div>
                    <div style="background: rgba(255,255,255,0.2) !important; padding: 10px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-money-bill-wave text-white" style="font-size: 20px; color: #ffffff !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Komisi Deal -->
    <div class="col-xl-3 col-md-6 col-sm-6 mb-3">
        <div class="card shadow-sm border-0 stat-card" style="background: linear-gradient(135deg, #d97706, #f59e0b) !important; border-radius: 12px; overflow: hidden;">
            <div class="card-body p-3" style="background: transparent !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <p class="mb-0 small font-weight-semibold" style="color: rgba(255,255,255,0.85) !important; margin: 0; font-size: 11px;">Total Komisi Terhitung (Deal)</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #ffffff !important; margin: 0; font-size: 18px;">Rp <?= number_format($summary['total_komisi_deal'], 0, ',', '.') ?></h3>
                    </div>
                    <div style="background: rgba(255,255,255,0.2) !important; padding: 10px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-award text-white" style="font-size: 20px; color: #ffffff !important;"></i>
                    </div>
                </div>
                <div class="pt-2 border-top" style="border-color: rgba(255,255,255,0.25) !important; font-size: 11px; color: #ffffff;">
                    <span class="mr-2"><i class="fas fa-check-circle"></i> Cair: <strong>Rp <?= number_format($summary['total_komisi_terbayar'] ?? 0, 0, ',', '.') ?></strong></span>
                    <span><i class="fas fa-clock"></i> Belum: <strong>Rp <?= number_format($summary['total_komisi_siap_bayar'] ?? 0, 0, ',', '.') ?></strong></span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .badge-deal { background: #dcfce7; color: #15803d; font-weight: 600; padding: 4px 10px; border-radius: 6px; font-size: 12px; }
    .badge-pending { background: #fef3c7; color: #b45309; font-weight: 600; padding: 4px 10px; border-radius: 6px; font-size: 12px; }
    .badge-batal { background: #fee2e2; color: #b91c1c; font-weight: 600; padding: 4px 10px; border-radius: 6px; font-size: 12px; }
    .card-modern { border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04); margin-bottom: 24px; }
    .card-modern .card-header { background: #ffffff; border-bottom: 1px solid #edf2f7; padding: 16px 20px; border-top-left-radius: 12px; border-top-right-radius: 12px; }
    .card-modern .card-title { font-size: 16px; font-weight: 700; color: #1e293b; margin: 0; }
    .table thead th { background: #f8fafc; font-size: 12px; text-transform: uppercase; letter-spacing: 0.03em; color: #475569; border-bottom: 2px solid #e2e8f0; vertical-align: middle; }
    .table td { vertical-align: middle !important; font-size: 13px; }
    .nav-tabs-va { border-bottom: 2px solid #e2e8f0; margin-bottom: 20px; }
    .nav-tabs-va .nav-link { border: none; color: #64748b; font-weight: 600; padding: 10px 18px; border-radius: 8px 8px 0 0; margin-right: 4px; position: relative; background: transparent; }
    .nav-tabs-va .nav-link:hover { color: #1e293b; background: #f8fafc; }
    .nav-tabs-va .nav-link.active { color: #2563eb; background: #ffffff; border: 1px solid #e2e8f0; border-bottom-color: #ffffff; margin-bottom: -2px; }
    .katalog-thumb { width: 56px; height: 56px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; }
    .katalog-thumb-placeholder { width: 56px; height: 56px; border-radius: 8px; border: 1px dashed #cbd5e1; background: #f8fafc; display: inline-flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 18px; }
</style>

<!-- NAV TABS -->
<ul class="nav nav-tabs nav-tabs-va" id="memberVaTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= !$isKatalogTab ? ' active' : '' ?>" id="tab-link-member-deal" data-toggle="tab" href="#tab-member-deal" role="tab" aria-controls="tab-member-deal" aria-selected="<?= !$isKatalogTab ? 'true' : 'false' ?>">
            <i class="fas fa-users mr-1"></i> Member &amp; Tracking Deal
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $isKatalogTab ? ' active' : '' ?>" id="tab-link-katalog" data-toggle="tab" href="#tab-katalog" role="tab" aria-controls="tab-katalog" aria-selected="<?= $isKatalogTab ? 'true' : 'false' ?>">
            <i class="fas fa-book-medical mr-1"></i> Katalog Produk Alkes
            <span class="badge badge-pill badge-primary ml-1"><?= $katalogAktif ?>/<?= $katalogTotal ?></span>
        </a>
    </li>
</ul>

<div class="tab-content" id="memberVaTabContent">
    <div class="tab-pane fade<?= !$isKatalogTab ? ' show active' : '' ?>" id="tab-member-deal" role="tabpanel" aria-labelledby="tab-link-member-deal">

<!-- CARD 1: DAFTAR MEMBER -->
<div class="card card-modern shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title"><i class="fas fa-id-card text-primary mr-2"></i> Daftar Member Visi Academy</h5>
        <div>
            <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modal-member">
                <i class="fas fa-user-plus mr-1"></i> Tambah Member Baru
            </button>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0" id="table-member">
                <thead>
                    <tr>
                        <th style="width: 40px;" class="text-center">#</th>
                        <th>Kode Member</th>
                        <th>Nama Member</th>
                        <th>No. Telp / WhatsApp</th>
                        <th>Email</th>
                        <th>Institusi / Asal</th>
                        <th>Rekening Pencairan (Bank)</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($members)): ?>
                        <?php $no = 1; foreach ($members as $m): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><span class="badge badge-primary font-weight-bold" style="font-size: 12px;"><?= htmlspecialchars($m['kode_member']) ?></span></td>
                                <td class="font-weight-bold text-dark"><?= htmlspecialchars($m['nama_member']) ?></td>
                                <td>
                                    <?php if (!empty($m['no_telp'])): ?>
                                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $m['no_telp']) ?>" target="_blank" class="text-success font-weight-bold">
                                            <i class="fab fa-whatsapp"></i> <?= htmlspecialchars($m['no_telp']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($m['email'] ?: '-') ?></td>
                                <td><?= htmlspecialchars($m['institusi_asal'] ?: '-') ?></td>
                                <td>
                                    <?php if (!empty($m['no_rekening'])): ?>
                                        <span class="badge badge-light border text-dark font-weight-bold"><i class="fas fa-university text-primary mr-1"></i><?= htmlspecialchars($m['nama_bank'] ?: 'Bank') ?></span>
                                        <div class="font-weight-bold text-dark mt-1" style="font-size: 13px;">
                                            <?= htmlspecialchars($m['no_rekening']) ?>
                                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-1 ml-1 btn-copy-rek" data-rek="<?= htmlspecialchars($m['no_rekening']) ?>" title="Salin Rekening">
                                                <i class="far fa-copy"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">a.n <?= htmlspecialchars($m['atas_nama_rekening'] ?: $m['nama_member']) ?></small>
                                    <?php else: ?>
                                        <span class="badge badge-warning text-dark"><i class="fas fa-exclamation-triangle mr-1"></i> Belum Diisi</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $m['status'] == 'aktif' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($m['status']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-danger btn-delete" 
                                            data-id="<?= $m['id_member'] ?>" 
                                            data-object="Member_visiacademy/deleteMember"
                                            title="Hapus Member">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- CARD 2: DATA DEAL PRODUK -->
<div class="card card-modern shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title"><i class="fas fa-handshake text-success mr-2"></i> Tracking Kebutuhan Alkes & Deal Komisi</h5>
        <div>
            <button type="button" class="btn btn-sm btn-success shadow-sm" data-toggle="modal" data-target="#modal-deal">
                <i class="fas fa-plus-circle mr-1"></i> Input Deal Kebutuhan Alkes
            </button>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0" id="table-deal">
                <thead>
                    <tr>
                        <th style="width: 40px;" class="text-center">#</th>
                        <th>Member & Rekening (Penerima)</th>
                        <th>Kebutuhan Alkes / Produk</th>
                        <th class="text-right">Nilai Transaksi (Rp)</th>
                        <th class="text-center">Komisi (%)</th>
                        <th class="text-right">Nominal Komisi (Rp)</th>
                        <th class="text-center">Status Prospek</th>
                        <th class="text-center">Status Pembayaran</th>
                        <th>Catatan</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($deals)): ?>
                        <?php $nd = 1; foreach ($deals as $d): ?>
                            <tr>
                                <td class="text-center"><?= $nd++ ?></td>
                                <td>
                                    <strong class="text-dark"><?= htmlspecialchars($d['nama_member'] ?? '-') ?></strong>
                                    <br><small class="text-muted"><span class="badge badge-light border"><?= htmlspecialchars($d['kode_member'] ?? '') ?></span> <?= htmlspecialchars($d['institusi_asal'] ?? '') ?></small>
                                    <?php if (!empty($d['no_rekening'])): ?>
                                        <div class="mt-1 p-1 px-2 bg-light border rounded" style="font-size: 11px;">
                                            <i class="fas fa-university text-primary mr-1"></i><strong><?= htmlspecialchars($d['nama_bank'] ?: 'Bank') ?>:</strong> 
                                            <span class="text-dark font-weight-bold"><?= htmlspecialchars($d['no_rekening']) ?></span>
                                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-1 ml-1 btn-copy-rek" data-rek="<?= htmlspecialchars($d['no_rekening']) ?>" title="Salin No. Rekening">
                                                <i class="far fa-copy"></i>
                                            </button>
                                            <br><span class="text-muted">a.n <?= htmlspecialchars($d['atas_nama_rekening'] ?: $d['nama_member']) ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="mt-1"><span class="badge badge-warning text-dark" style="font-size: 10px;"><i class="fas fa-exclamation-triangle"></i> Rekening Belum Diisi</span></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-primary"><?= htmlspecialchars($d['nama_produk']) ?></span>
                                    <br><small class="text-muted"><i class="far fa-clock"></i> <?= date('d M Y', strtotime($d['created_at'])) ?></small>
                                </td>
                                <td class="text-right font-weight-bold text-dark">
                                    Rp <?= number_format($d['estimasi_nilai'], 0, ',', '.') ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info font-weight-bold"><?= $d['persen_komisi'] ?>%</span>
                                </td>
                                <td class="text-right font-weight-bold text-success">
                                    Rp <?= number_format($d['nominal_komisi'], 0, ',', '.') ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($d['status_deal'] == 'Deal'): ?>
                                        <span class="badge-deal"><i class="fas fa-check-circle"></i> Deal</span>
                                    <?php elseif ($d['status_deal'] == 'Batal'): ?>
                                        <span class="badge-batal"><i class="fas fa-times-circle"></i> Batal</span>
                                    <?php else: ?>
                                        <span class="badge-pending"><i class="fas fa-clock"></i> Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($d['status_deal'] == 'Deal'): ?>
                                        <?php if (($d['status_bayar'] ?? '') == 'Sudah Ditransfer'): ?>
                                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check-double mr-1"></i> Sudah Ditransfer</span>
                                            <?php if (!empty($d['tgl_transfer'])): ?>
                                                <br><small class="text-muted"><i class="far fa-calendar-check"></i> <?= date('d/m/Y H:i', strtotime($d['tgl_transfer'])) ?></small>
                                            <?php endif; ?>
                                            <?php if (!empty($d['bukti_transfer'])): ?>
                                                <br><a href="<?= base_url('uploads/bukti_komisi/' . $d['bukti_transfer']) ?>" target="_blank" class="btn btn-xs btn-outline-primary py-0 px-1 mt-1 shadow-sm">
                                                    <i class="fas fa-receipt mr-1"></i> Bukti Transfer
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-hourglass-half mr-1"></i> Belum Ditransfer</span>
                                            <br><small class="text-danger font-weight-bold">Siap Dicairkan</small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?= htmlspecialchars($d['catatan'] ?: '-') ?></small>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <?php
                                        $dealNilaiNum   = (float)($d['estimasi_nilai'] ?? 0);
                                        $dealNilaiClean = (fmod($dealNilaiNum, 1) == 0)
                                            ? number_format($dealNilaiNum, 0, '.', '')
                                            : rtrim(rtrim(number_format($dealNilaiNum, 2, '.', ''), '0'), '.');
                                        $dealPersenNum   = (float)($d['persen_komisi'] ?? 2);
                                        $dealPersenClean = (fmod($dealPersenNum, 1) == 0)
                                            ? number_format($dealPersenNum, 0, '.', '')
                                            : rtrim(rtrim(number_format($dealPersenNum, 2, '.', ''), '0'), '.');
                                        $dealMemberLabel = ($d['nama_member'] ?? '-') . (!empty($d['kode_member']) ? ' (' . $d['kode_member'] . ')' : '');
                                        ?>
                                        <button type="button" class="btn btn-xs btn-warning btn-edit-status mr-1" 
                                                data-id="<?= (int)$d['id_deal'] ?>" 
                                                data-status="<?= htmlspecialchars($d['status_deal'] ?? 'Pending', ENT_QUOTES, 'UTF-8') ?>"
                                                data-member="<?= htmlspecialchars($dealMemberLabel, ENT_QUOTES, 'UTF-8') ?>"
                                                data-produk="<?= htmlspecialchars($d['nama_produk'] ?? '-', ENT_QUOTES, 'UTF-8') ?>"
                                                data-nilai="<?= htmlspecialchars($dealNilaiClean, ENT_QUOTES, 'UTF-8') ?>"
                                                data-persen="<?= htmlspecialchars($dealPersenClean, ENT_QUOTES, 'UTF-8') ?>"
                                                data-catatan="<?= htmlspecialchars($d['catatan'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                title="Ubah Status &amp; Nilai/Komisi Deal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($d['status_deal'] == 'Deal'): ?>
                                            <button type="button" class="btn btn-xs btn-success btn-bayar-komisi mr-1"
                                                    data-id="<?= $d['id_deal'] ?>"
                                                    data-member="<?= htmlspecialchars($d['nama_member']) ?>"
                                                    data-komisi="Rp <?= number_format($d['nominal_komisi'], 0, ',', '.') ?>"
                                                    data-bank="<?= htmlspecialchars($d['nama_bank'] ?: 'Belum diisi') ?>"
                                                    data-rek="<?= htmlspecialchars($d['no_rekening'] ?: '-') ?>"
                                                    data-an="<?= htmlspecialchars($d['atas_nama_rekening'] ?: $d['nama_member']) ?>"
                                                    data-status-bayar="<?= $d['status_bayar'] ?: 'Belum Ditransfer' ?>"
                                                    data-tgl="<?= !empty($d['tgl_transfer']) ? date('Y-m-d\TH:i', strtotime($d['tgl_transfer'])) : date('Y-m-d\TH:i') ?>"
                                                    data-bukti="<?= htmlspecialchars($d['bukti_transfer'] ?? '') ?>"
                                                    data-catatan="<?= htmlspecialchars($d['catatan_transfer'] ?? '') ?>"
                                                    title="Konfirmasi / Upload Bukti Transfer Finance">
                                                <i class="fas fa-money-check-alt"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-xs btn-danger btn-delete" 
                                                data-id="<?= $d['id_deal'] ?>" 
                                                data-object="Member_visiacademy/deleteDeal"
                                                title="Hapus Deal">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    </div><!-- /#tab-member-deal -->

    <!-- TAB 2: KATALOG PRODUK ALKES -->
    <div class="tab-pane fade<?= $isKatalogTab ? ' show active' : '' ?>" id="tab-katalog" role="tabpanel" aria-labelledby="tab-link-katalog">
        <div class="card card-modern shadow-sm border-0">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h5 class="card-title"><i class="fas fa-book-medical text-primary mr-2"></i> Katalog Produk Alkes &amp; Brosur</h5>
                    <small class="text-muted d-block mt-1">Produk berstatus <strong>Aktif</strong> tampil otomatis di portal afiliator (<code>https://visiyosindo.id/katalog.php</code>) dengan kontrak path relatif <code>uploads/katalog/...</code> &amp; <code>uploads/brosur/...</code>.</small>
                </div>
                <div class="mt-2 mt-md-0">
                    <button type="button" class="btn btn-sm btn-primary shadow-sm" id="btn-tambah-katalog">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Produk Katalog
                    </button>
                </div>
            </div>
            <div class="card-body p-3">
                <!-- Compact Katalog Summary Strip -->
                <div class="d-flex flex-wrap align-items-center justify-content-between bg-light border rounded p-2 px-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center" style="gap: 16px;">
                        <span class="small text-dark"><i class="fas fa-boxes text-primary mr-1"></i> Total Produk: <strong><?= number_format($katalogTotal) ?></strong></span>
                        <span class="small text-success"><i class="fas fa-check-circle mr-1"></i> Aktif di Portal: <strong><?= number_format($katalogAktif) ?></strong></span>
                        <span class="small text-secondary"><i class="fas fa-eye-slash mr-1"></i> Nonaktif: <strong><?= number_format($katalogNonaktif) ?></strong></span>
                        <span class="small text-danger"><i class="fas fa-file-pdf mr-1"></i> Ada Brosur PDF: <strong><?= number_format($katalogBrosur) ?></strong></span>
                    </div>
                    <small class="text-muted mt-1 mt-md-0"><i class="fas fa-info-circle mr-1"></i> Estimasi komisi awal portal terkunci di <strong>2%</strong></small>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0" id="table-katalog">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 70px;">Urutan</th>
                                <th class="text-center" style="width: 80px;">Gambar</th>
                                <th>Produk &amp; Deskripsi</th>
                                <th class="text-right" style="width: 150px;">Estimasi Harga</th>
                                <th class="text-center" style="width: 150px;">Brosur PDF</th>
                                <th class="text-center" style="width: 100px;">Status</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($katalogList)): ?>
                                <?php foreach ($katalogList as $kp): ?>
                                    <?php
                                    $gambarRaw = trim((string)($kp['url_gambar'] ?? ''));
                                    $gambarUrl = $resolveKatalogUrl($gambarRaw);
                                    $brosurRaw = trim((string)($kp['url_brosur'] ?? ''));
                                    $brosurUrl = $resolveKatalogUrl($brosurRaw);
                                    $hargaNum  = (float)($kp['estimasi_harga'] ?? 0);
                                    $hargaClean = (fmod($hargaNum, 1) == 0)
                                        ? number_format($hargaNum, 0, '.', '')
                                        : rtrim(rtrim(number_format($hargaNum, 2, '.', ''), '0'), '.');
                                    $kpStatus  = ($kp['status'] ?? 'aktif') === 'nonaktif' ? 'nonaktif' : 'aktif';
                                    ?>
                                    <tr>
                                        <td class="text-center font-weight-bold" data-order="<?= (int)$kp['urutan'] ?>">
                                            <?= (int)$kp['urutan'] ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($gambarUrl !== ''): ?>
                                                <a href="<?= htmlspecialchars($gambarUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" title="Lihat Gambar">
                                                    <img src="<?= htmlspecialchars($gambarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($kp['nama_produk'], ENT_QUOTES, 'UTF-8') ?>" class="katalog-thumb">
                                                </a>
                                            <?php else: ?>
                                                <span class="katalog-thumb-placeholder" title="Belum ada gambar">
                                                    <i class="fas fa-image"></i>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong class="text-dark d-block"><?= htmlspecialchars($kp['nama_produk'], ENT_QUOTES, 'UTF-8') ?></strong>
                                            <span class="badge badge-info mb-1"><?= htmlspecialchars($kp['kategori'] ?: 'Radiologi', ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php if (!empty($kp['deskripsi_singkat'])): ?>
                                                <div class="text-muted small"><?= nl2br(htmlspecialchars($kp['deskripsi_singkat'], ENT_QUOTES, 'UTF-8')) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right font-weight-bold text-dark" data-order="<?= $hargaNum ?>">
                                            <?php if ($hargaNum > 0): ?>
                                                Rp <?= number_format($hargaNum, 0, ',', '.') ?>
                                            <?php else: ?>
                                                <span class="badge badge-light border text-muted">On Request</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($brosurUrl !== ''): ?>
                                                <a href="<?= htmlspecialchars($brosurUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-xs btn-outline-danger shadow-sm">
                                                    <i class="fas fa-file-pdf mr-1"></i> Lihat Brosur
                                                </a>
                                                <br><small class="text-muted"><?= preg_match('#^https?://#i', $brosurRaw) ? 'URL Eksternal' : 'File Lokal' ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($kpStatus === 'aktif'): ?>
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary px-2 py-1"><i class="fas fa-eye-slash mr-1"></i> Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <button type="button" class="btn btn-xs btn-warning btn-edit-katalog mr-1"
                                                        data-id="<?= (int)$kp['id_produk'] ?>"
                                                        data-nama="<?= htmlspecialchars($kp['nama_produk'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                        data-kategori="<?= htmlspecialchars($kp['kategori'] ?? 'Radiologi', ENT_QUOTES, 'UTF-8') ?>"
                                                        data-deskripsi="<?= htmlspecialchars($kp['deskripsi_singkat'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                        data-harga="<?= htmlspecialchars($hargaClean, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-urutan="<?= (int)($kp['urutan'] ?? 0) ?>"
                                                        data-status="<?= htmlspecialchars($kpStatus, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-gambar-raw="<?= htmlspecialchars($gambarRaw, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-gambar-url="<?= htmlspecialchars($gambarUrl, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-brosur-raw="<?= htmlspecialchars($brosurRaw, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-brosur-url="<?= htmlspecialchars($brosurUrl, ENT_QUOTES, 'UTF-8') ?>"
                                                        title="Edit Produk Katalog">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-<?= $kpStatus === 'aktif' ? 'secondary' : 'success' ?> btn-toggle-katalog mr-1"
                                                        data-id="<?= (int)$kp['id_produk'] ?>"
                                                        data-nama="<?= htmlspecialchars($kp['nama_produk'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                        data-status="<?= htmlspecialchars($kpStatus, ENT_QUOTES, 'UTF-8') ?>"
                                                        title="<?= $kpStatus === 'aktif' ? 'Nonaktifkan Produk' : 'Aktifkan Produk' ?>">
                                                    <i class="fas fa-power-off"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-danger btn-delete"
                                                        data-id="<?= (int)$kp['id_produk'] ?>"
                                                        data-object="<?= base_url('Member_visiacademy/deleteKatalog') ?>"
                                                        title="Hapus Produk Katalog">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- /#tab-katalog -->
</div><!-- /.tab-content -->

<!-- MODAL: Tambah Member -->
<div id="modal-member" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-user-plus mr-1"></i> Pendaftaran Member Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('Member_visiacademy/add', ['id' => 'form-member', 'autocomplete' => 'off']); ?>
            <div class="modal-body p-4">
                <div class="form-group mb-3">
                    <label class="font-weight-semibold text-dark">Kode Member <span class="text-danger">*</span></label>
                    <input type="text" name="kode_member" class="form-control" value="<?= $next_kode ?>" required>
                    <small class="text-muted">Kode unik otomatis atau dapat disesuaikan.</small>
                </div>
                <div class="form-group mb-3">
                    <label class="font-weight-semibold text-dark">Nama Lengkap Member <span class="text-danger">*</span></label>
                    <input type="text" name="nama_member" class="form-control" placeholder="Contoh: dr. Budi Santoso, Sp.Rad" required>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-semibold text-dark">No. WhatsApp / HP</label>
                        <input type="text" name="no_telp" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-semibold text-dark">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@domain.com">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="font-weight-semibold text-dark">Institusi / RS / Klinik Asal</label>
                    <input type="text" name="institusi_asal" class="form-control" placeholder="Contoh: RSUD Pasar Minggu / Alumni PPR 2026">
                </div>
                <div class="border-top pt-3 mt-2">
                    <label class="font-weight-bold text-primary mb-2"><i class="fas fa-university mr-1"></i> Data Rekening Pencairan Komisi (Opsional)</label>
                    <div class="row">
                        <div class="col-md-5 form-group mb-2">
                            <label class="small font-weight-semibold text-dark">Nama Bank</label>
                            <input type="text" name="nama_bank" class="form-control form-control-sm" placeholder="Contoh: BCA / Mandiri / BRI">
                        </div>
                        <div class="col-md-7 form-group mb-2">
                            <label class="small font-weight-semibold text-dark">Nomor Rekening</label>
                            <input type="text" name="no_rekening" class="form-control form-control-sm" placeholder="Nomor rekening">
                        </div>
                        <div class="col-12 form-group mb-1">
                            <label class="small font-weight-semibold text-dark">Atas Nama Rekening</label>
                            <input type="text" name="atas_nama_rekening" class="form-control form-control-sm" placeholder="Nama pemilik rekening">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-save">
                    <i class="fas fa-save mr-1"></i> Simpan Member
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- MODAL: Input Deal Alkes -->
<div id="modal-deal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-success text-white py-3">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-handshake mr-1"></i> Input Kebutuhan Alkes &amp; Deal Komisi</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('Member_visiacademy/addDeal', ['id' => 'form-deal', 'autocomplete' => 'off']); ?>
            <div class="modal-body p-4">
                <div class="form-group mb-3">
                    <label class="font-weight-semibold text-dark">Member yang Mereferensikan <span class="text-danger">*</span></label>
                    <select name="id_member" class="form-control" required>
                        <option value="">-- Pilih Member --</option>
                        <?php foreach ($members as $m): ?>
                            <option value="<?= $m['id_member'] ?>">
                                <?= htmlspecialchars($m['nama_member']) ?> (<?= htmlspecialchars($m['kode_member']) ?>) - <?= htmlspecialchars($m['institusi_asal'] ?: 'Umum') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="font-weight-semibold text-dark">Produk Alkes yang Dibutuhkan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_produk" list="katalog-produk-list" class="form-control" placeholder="Contoh: Mobile X-Ray, Apron Pb, Viewer LED" required>
                    <datalist id="katalog-produk-list">
                        <?php foreach ($katalogList as $kp): ?>
                            <?php if (($kp['status'] ?? 'aktif') === 'aktif'): ?>
                                <option value="<?= htmlspecialchars($kp['nama_produk'], ENT_QUOTES, 'UTF-8') ?>"></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="row">
                    <div class="col-md-7 form-group mb-3">
                        <label class="font-weight-semibold text-dark">Estimasi Nilai Transaksi (Rp) <span class="text-danger">*</span></label>
                        <input type="text" id="deal-nilai" name="estimasi_nilai" class="form-control" placeholder="Contoh: 150000000" required>
                    </div>
                    <div class="col-md-5 form-group mb-3">
                        <label class="font-weight-semibold text-dark">Komisi (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" max="100" id="deal-persen" name="persen_komisi" class="form-control" value="2" required>
                        <small class="text-muted">Default estimasi awal 2% (dapat disesuaikan saat Deal)</small>
                    </div>
                </div>
                <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center" style="border-radius: 8px;">
                    <i class="fas fa-calculator text-primary mr-2 fa-lg"></i>
                    <div>
                        <small class="d-block text-muted">Kalkulasi Otomatis Komisi:</small>
                        <strong id="preview-komisi" class="text-success" style="font-size: 15px;">Rp 0</strong>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="font-weight-semibold text-dark">Status Deal Saat Ini</label>
                    <select name="status_deal" class="form-control">
                        <option value="Pending">Pending (Sedang Prospek / Negosiasi)</option>
                        <option value="Deal">Deal (Transaksi Berhasil)</option>
                        <option value="Batal">Batal (Tidak Deal)</option>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label class="font-weight-semibold text-dark">Catatan Kebutuhan / Keterangan</label>
                    <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan spesifikasi, kontak PIC instansi, atau progres penawaran"></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success btn-save">
                    <i class="fas fa-save mr-1"></i> Simpan Deal
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- MODAL: Update Status & Komisi Final Deal -->
<div id="modal-status-deal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-warning text-dark py-3">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-exchange-alt mr-1"></i> Update Status &amp; Nilai Komisi Deal</h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('Member_visiacademy/updateDealStatus', ['id' => 'form-status-deal', 'autocomplete' => 'off']); ?>
            <div class="modal-body p-4">
                <input type="hidden" name="id_deal" id="edit-id-deal">

                <div class="card bg-light border-0 shadow-none mb-3 p-3" style="border-radius: 8px;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small">Member (Referrer):</span>
                        <strong class="text-dark small" id="edit-deal-member">-</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Produk Alkes:</span>
                        <strong class="text-primary small" id="edit-deal-produk">-</strong>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-semibold text-dark">Pilih Status Prospek <span class="text-danger">*</span></label>
                    <select name="status_deal" id="edit-status-deal" class="form-control">
                        <option value="Pending">Pending (Sedang Prospek / Negosiasi)</option>
                        <option value="Deal">Deal (Transaksi Berhasil / Selesai)</option>
                        <option value="Batal">Batal (Tidak Deal)</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-7 form-group mb-3">
                        <label class="font-weight-semibold text-dark">Nilai Transaksi Final (Rp)</label>
                        <input type="text" name="estimasi_nilai" id="edit-deal-nilai" class="form-control" placeholder="Contoh: 150000000">
                    </div>
                    <div class="col-md-5 form-group mb-3">
                        <label class="font-weight-semibold text-dark">Komisi Final (%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="persen_komisi" id="edit-deal-persen" class="form-control" value="2">
                    </div>
                </div>

                <div class="alert alert-info py-2 px-3 mb-2 d-flex align-items-center" style="border-radius: 8px;">
                    <i class="fas fa-calculator text-primary mr-2 fa-lg"></i>
                    <div>
                        <small class="d-block text-muted">Nominal Komisi Final Terhitung:</small>
                        <strong id="edit-preview-komisi" class="text-success" style="font-size: 15px;">Rp 0</strong>
                    </div>
                </div>
                <small class="text-muted d-block mb-3">
                    <i class="fas fa-info-circle mr-1"></i> Estimasi awal dari portal afiliator dikunci di <strong>2%</strong>. Sales/Admin dapat menyesuaikan nilai transaksi &amp; persentase komisi final saat status diubah menjadi <strong>Deal</strong>.
                </small>

                <div class="form-group mb-1">
                    <label class="font-weight-semibold text-dark">Catatan Progres / Keterangan</label>
                    <textarea name="catatan" id="edit-deal-catatan" class="form-control" rows="2" placeholder="Catatan penawaran, negosiasi, atau detail PIC instansi"></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-save">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- MODAL: Tambah / Edit Produk Katalog Alkes -->
<div id="modal-katalog" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title font-weight-bold" id="modal-katalog-title"><i class="fas fa-book-medical mr-1"></i> Tambah Produk Katalog Alkes</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart('Member_visiacademy/saveKatalog', ['id' => 'form-katalog', 'autocomplete' => 'off']); ?>
            <div class="modal-body p-4">
                <input type="hidden" name="id_produk" id="katalog-id-produk" value="0">

                <div class="row">
                    <div class="col-md-8 form-group mb-3">
                        <label class="font-weight-semibold text-dark">Nama Produk Alkes <span class="text-danger">*</span></label>
                        <input type="text" name="nama_produk" id="katalog-nama-produk" class="form-control" maxlength="200" placeholder="Contoh: Mobile X-Ray Digital DR 32kW" required>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="font-weight-semibold text-dark">Kategori</label>
                        <input type="text" name="kategori" id="katalog-kategori" list="katalog-kategori-list" class="form-control" maxlength="100" value="Radiologi" placeholder="Radiologi">
                        <datalist id="katalog-kategori-list">
                            <?php if (!empty($katalog_categories)): ?>
                                <?php foreach ($katalog_categories as $kat): ?>
                                    <option value="<?= htmlspecialchars($kat, ENT_QUOTES, 'UTF-8') ?>"></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </datalist>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 form-group mb-3">
                        <label class="font-weight-semibold text-dark">Estimasi Harga (Rp)</label>
                        <input type="text" name="estimasi_harga" id="katalog-harga" class="form-control" placeholder="0 (isi 0 jika On Request)">
                        <small class="text-muted">Isi 0 jika harga bersifat On Request / Call.</small>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label class="font-weight-semibold text-dark">Urutan Tampil</label>
                        <input type="number" name="urutan" id="katalog-urutan" class="form-control" value="0">
                        <small class="text-muted">Angka kecil tampil lebih dulu.</small>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="font-weight-semibold text-dark">Status Tampil</label>
                        <select name="status" id="katalog-status" class="form-control">
                            <option value="aktif">Aktif (Tampil di Portal)</option>
                            <option value="nonaktif">Nonaktif (Disembunyikan)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-semibold text-dark">Deskripsi Singkat Produk</label>
                    <textarea name="deskripsi_singkat" id="katalog-deskripsi" class="form-control" rows="2" placeholder="Spesifikasi utama, keunggulan, atau target rumah sakit/klinik"></textarea>
                </div>

                <div class="row">
                    <!-- Boxed Gambar Produk -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="border rounded p-3 bg-light h-100">
                            <label class="font-weight-bold text-primary d-block mb-2"><i class="fas fa-image mr-1"></i> Gambar Produk</label>

                            <div id="katalog-gambar-preview-wrap" class="bg-white border rounded p-2 mb-2" style="display: none;">
                                <div class="d-flex align-items-center mb-2">
                                    <img id="katalog-gambar-preview-img" src="" alt="Preview" style="width: 56px; height: 56px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;" class="mr-2">
                                    <div style="min-width: 0; flex: 1;">
                                        <small class="text-muted d-block">Gambar saat ini:</small>
                                        <span id="katalog-gambar-path-text" class="badge badge-light border text-truncate d-inline-block" style="max-width: 100%;">-</span>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hapus_gambar" id="katalog-hapus-gambar" value="1">
                                    <label class="form-check-label small text-danger font-weight-semibold" for="katalog-hapus-gambar">Hapus gambar saat ini</label>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <label class="small font-weight-semibold text-dark mb-1">Upload File Gambar (JPG, PNG, WEBP max 4MB)</label>
                                <input type="file" name="file_gambar" id="katalog-file-gambar" accept=".jpg,.jpeg,.png,.webp" class="form-control-file border bg-white p-1 rounded w-100">
                                <small class="text-muted">Disimpan ke <code>uploads/katalog/</code>.</small>
                            </div>
                            <div class="form-group mb-0">
                                <label class="small font-weight-semibold text-dark mb-1">Atau URL Gambar Eksternal</label>
                                <input type="url" name="url_gambar_external" id="katalog-url-gambar-ext" class="form-control form-control-sm" maxlength="255" placeholder="https://... (opsional jika tidak upload file)">
                            </div>
                        </div>
                    </div>

                    <!-- Boxed Brosur PDF -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light h-100">
                            <label class="font-weight-bold text-danger d-block mb-2"><i class="fas fa-file-pdf mr-1"></i> Brosur Produk (PDF)</label>

                            <div id="katalog-brosur-preview-wrap" class="bg-white border rounded p-2 mb-2" style="display: none;">
                                <div class="mb-2">
                                    <a href="#" target="_blank" rel="noopener noreferrer" id="katalog-brosur-preview-link" class="badge badge-danger px-2 py-1">
                                        <i class="fas fa-external-link-alt mr-1"></i> Lihat Brosur Saat Ini
                                    </a>
                                    <div class="mt-1">
                                        <span id="katalog-brosur-path-text" class="badge badge-light border text-truncate d-inline-block" style="max-width: 100%;">-</span>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hapus_brosur" id="katalog-hapus-brosur" value="1">
                                    <label class="form-check-label small text-danger font-weight-semibold" for="katalog-hapus-brosur">Hapus brosur saat ini</label>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <label class="small font-weight-semibold text-dark mb-1">Upload File Brosur (PDF max 4MB)</label>
                                <input type="file" name="file_brosur" id="katalog-file-brosur" accept=".pdf" class="form-control-file border bg-white p-1 rounded w-100">
                                <small class="text-muted">Disimpan ke <code>uploads/brosur/</code>.</small>
                            </div>
                            <div class="form-group mb-0">
                                <label class="small font-weight-semibold text-dark mb-1">Atau URL Brosur Eksternal</label>
                                <input type="url" name="url_brosur_external" id="katalog-url-brosur-ext" class="form-control form-control-sm" maxlength="255" placeholder="https://... (opsional jika tidak upload PDF)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-save">
                    <i class="fas fa-save mr-1"></i> Simpan Produk
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- MODAL: Konfirmasi Pembayaran Komisi (Finance) -->
<div id="modal-bayar-komisi" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-success text-white py-3">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-money-check-alt mr-1"></i> Pembayaran Komisi Member</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart('Member_visiacademy/konfirmasiBayar', ['id' => 'form-bayar-komisi', 'autocomplete' => 'off']); ?>
            <div class="modal-body p-4">
                <input type="hidden" name="id_deal" id="bayar-id-deal">
                
                <!-- Ringkasan Komisi & Rekening Tujuan -->
                <div class="card bg-light border-0 shadow-none mb-3 p-3" style="border-radius: 8px;">
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <span class="text-muted small">Member Penerima:</span>
                        <strong class="text-dark" id="bayar-nama-member">-</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <span class="text-muted small">Nominal Komisi:</span>
                        <h4 class="text-success font-weight-bold mb-0" id="bayar-nominal-komisi">Rp 0</h4>
                    </div>
                    <div>
                        <span class="text-muted small d-block mb-1">Rekening Tujuan Transfer:</span>
                        <div class="d-flex align-items-center justify-content-between bg-white p-2 border rounded">
                            <div>
                                <span class="badge badge-primary mr-1" id="bayar-bank">-</span>
                                <strong class="text-dark" style="font-size: 14px;" id="bayar-rekening">-</strong>
                                <br><small class="text-muted">a.n <span id="bayar-an">-</span></small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" id="bayar-btn-copy" title="Salin Nomor Rekening">
                                <i class="far fa-copy mr-1"></i> Salin
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-semibold text-dark">Status Pembayaran Transfer <span class="text-danger">*</span></label>
                    <select name="status_bayar" id="bayar-status-bayar" class="form-control" required>
                        <option value="Sudah Ditransfer">Sudah Ditransfer (Lunas Cair)</option>
                        <option value="Belum Ditransfer">Belum Ditransfer (Pending)</option>
                    </select>
                </div>

                <div class="form-group mb-3" id="group-tgl-transfer">
                    <label class="font-weight-semibold text-dark">Tanggal & Waktu Transfer</label>
                    <input type="datetime-local" name="tgl_transfer" id="bayar-tgl-transfer" class="form-control" value="<?= date('Y-m-d\TH:i') ?>">
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-semibold text-dark">Upload Bukti Transfer / Resi (JPG, PNG, PDF max 5MB)</label>
                    <input type="file" name="bukti_transfer" id="bayar-bukti-file" class="form-control-file border p-2 rounded w-100">
                    <div id="bayar-bukti-current" class="mt-1" style="display: none;">
                        <small class="text-muted">Bukti saat ini: </small>
                        <a href="#" target="_blank" id="bayar-bukti-link" class="badge badge-info"><i class="fas fa-file-invoice"></i> Lihat File</a>
                    </div>
                </div>

                <div class="form-group mb-1">
                    <label class="font-weight-semibold text-dark">Catatan Transfer (Opsional)</label>
                    <input type="text" name="catatan_transfer" id="bayar-catatan" class="form-control" placeholder="Contoh: Transfer via KlikBCA Ref #12345">
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success btn-save">
                    <i class="fas fa-check-circle mr-1"></i> Simpan Pembayaran
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTables with Indonesian language & clean options
        if ($.fn.DataTable) {
            $('#table-member').DataTable({
                responsive: true,
                language: {
                    search: "Cari Member:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ member",
                    infoEmpty: "Tidak ada data",
                    zeroRecords: "Tidak ada member yang cocok",
                    paginate: { first: "Awal", last: "Akhir", next: "→", previous: "←" }
                }
            });

            $('#table-deal').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                language: {
                    search: "Cari Deal/Produk:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ deal",
                    infoEmpty: "Tidak ada data",
                    zeroRecords: "Tidak ada data deal yang cocok",
                    paginate: { first: "Awal", last: "Akhir", next: "→", previous: "←" }
                }
            });

            $('#table-katalog').DataTable({
                responsive: true,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [1, 4, 6] }
                ],
                language: {
                    search: "Cari Produk Katalog:",
                    lengthMenu: "Tampilkan _MENU_ produk",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ produk",
                    infoEmpty: "Belum ada produk katalog",
                    zeroRecords: "Tidak ada produk katalog yang cocok",
                    paginate: { first: "Awal", last: "Akhir", next: "→", previous: "←" }
                }
            });
        }

        // Persistensi Tab (query ?tab=, hash #tab-..., dan sessionStorage)
        (function initMemberTabs() {
            var storageKey = 'va_member_active_tab';
            var params = new URLSearchParams(window.location.search);
            var queryTab = params.get('tab');
            var hashTab = window.location.hash;
            var targetTab = '';

            if (queryTab === 'katalog') {
                targetTab = '#tab-katalog';
            } else if (queryTab === 'member-deal') {
                targetTab = '#tab-member-deal';
            } else if (hashTab === '#tab-katalog' || hashTab === '#tab-member-deal') {
                targetTab = hashTab;
            } else {
                try {
                    var savedTab = sessionStorage.getItem(storageKey);
                    if (savedTab === '#tab-katalog' || savedTab === '#tab-member-deal') {
                        targetTab = savedTab;
                    }
                } catch (e) {}
            }

            if (targetTab && $('#memberVaTabs a[href="' + targetTab + '"]').length) {
                $('#memberVaTabs a[href="' + targetTab + '"]').tab('show');
            }

            $('#memberVaTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var activeHref = $(e.target).attr('href');
                try {
                    sessionStorage.setItem(storageKey, activeHref);
                } catch (err) {}
                if ($.fn.DataTable) {
                    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust().responsive.recalc();
                }
            });
        })();

        // Helper parse input Rupiah (mendukung format 150000000, 150000000.00, 150.000.000, atau 150.000.000,50)
        function parseRupiahInput(raw) {
            var str = String(raw || '').replace(/rp\.?|\s+/gi, '').trim();
            if (!str) return 0;
            if (/^\d+\.\d{1,2}$/.test(str)) {
                return parseFloat(str) || 0;
            }
            if (/^\d{1,3}(\.\d{3})+(,\d{1,2})?$/.test(str)) {
                return parseFloat(str.replace(/\./g, '').replace(',', '.')) || 0;
            }
            var digits = str.replace(/[^0-9]/g, '');
            return parseFloat(digits) || 0;
        }

        function updateKomisiPreview(nilaiSelector, persenSelector, outputSelector) {
            var nilai = parseRupiahInput($(nilaiSelector).val());
            var persen = parseFloat(String($(persenSelector).val() || '0').replace(',', '.')) || 0;
            var komisi = (nilai * persen) / 100;
            $(outputSelector).text('Rp ' + komisi.toLocaleString('id-ID', { maximumFractionDigits: 2 }));
        }

        // Live calculation di form tambah deal & edit deal
        function hitungKomisiLive() {
            updateKomisiPreview('#deal-nilai', '#deal-persen', '#preview-komisi');
        }

        function hitungKomisiEditLive() {
            updateKomisiPreview('#edit-deal-nilai', '#edit-deal-persen', '#edit-preview-komisi');
        }

        $('#deal-nilai, #deal-persen').on('input change keyup', hitungKomisiLive);
        $('#edit-deal-nilai, #edit-deal-persen').on('input change keyup', hitungKomisiEditLive);

        // Copy rekening button handler
        $(document).on('click', '.btn-copy-rek', function() {
            var rek = $(this).data('rek');
            if (rek && rek !== '-') {
                navigator.clipboard.writeText(rek).then(function() {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Nomor rekening ' + rek + ' berhasil disalin!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        alert('Nomor rekening ' + rek + ' berhasil disalin!');
                    }
                });
            }
        });

        $('#bayar-btn-copy').on('click', function() {
            var rek = $('#bayar-rekening').text().trim();
            if (rek && rek !== '-') {
                navigator.clipboard.writeText(rek).then(function() {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Nomor rekening berhasil disalin!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        alert('Nomor rekening berhasil disalin!');
                    }
                });
            }
        });

        // Edit status & nilai komisi final deal modal handler
        $(document).on('click', '.btn-edit-status', function() {
            var $btn    = $(this);
            var id      = $btn.attr('data-id') || '';
            var status  = $btn.attr('data-status') || 'Pending';
            var member  = $btn.attr('data-member') || '-';
            var produk  = $btn.attr('data-produk') || '-';
            var nilai   = $btn.attr('data-nilai') || '';
            var persen  = $btn.attr('data-persen') || '2';
            var catatan = $btn.attr('data-catatan') || '';

            $('#edit-id-deal').val(id);
            $('#edit-status-deal').val(status);
            $('#edit-deal-member').text(member);
            $('#edit-deal-produk').text(produk);
            $('#edit-deal-nilai').val(nilai);
            $('#edit-deal-persen').val(persen);
            $('#edit-deal-catatan').val(catatan);

            hitungKomisiEditLive();
            $('#modal-status-deal').modal('show');
        });

        // Tambah Produk Katalog modal handler
        $('#btn-tambah-katalog').on('click', function() {
            $('#modal-katalog-title').html('<i class="fas fa-plus-circle mr-1"></i> Tambah Produk Katalog Alkes');
            $('#katalog-id-produk').val('0');
            $('#katalog-nama-produk').val('');
            $('#katalog-kategori').val('Radiologi');
            $('#katalog-harga').val('');
            $('#katalog-urutan').val('0');
            $('#katalog-status').val('aktif');
            $('#katalog-deskripsi').val('');

            $('#katalog-file-gambar').val('');
            $('#katalog-url-gambar-ext').val('');
            $('#katalog-hapus-gambar').prop('checked', false);
            $('#katalog-gambar-preview-wrap').hide();
            $('#katalog-gambar-preview-img').attr('src', '');
            $('#katalog-gambar-path-text').text('-');

            $('#katalog-file-brosur').val('');
            $('#katalog-url-brosur-ext').val('');
            $('#katalog-hapus-brosur').prop('checked', false);
            $('#katalog-brosur-preview-wrap').hide();
            $('#katalog-brosur-preview-link').attr('href', '#');
            $('#katalog-brosur-path-text').text('-');

            $('#modal-katalog').modal('show');
        });

        // Edit Produk Katalog modal handler
        $(document).on('click', '.btn-edit-katalog', function() {
            var $btn      = $(this);
            var id        = $btn.attr('data-id') || '0';
            var nama      = $btn.attr('data-nama') || '';
            var kategori  = $btn.attr('data-kategori') || 'Radiologi';
            var deskripsi = $btn.attr('data-deskripsi') || '';
            var harga     = $btn.attr('data-harga') || '0';
            var urutan    = $btn.attr('data-urutan') || '0';
            var status    = $btn.attr('data-status') || 'aktif';
            var gambarRaw = $btn.attr('data-gambar-raw') || '';
            var gambarUrl = $btn.attr('data-gambar-url') || '';
            var brosurRaw = $btn.attr('data-brosur-raw') || '';
            var brosurUrl = $btn.attr('data-brosur-url') || '';

            $('#modal-katalog-title').html('<i class="fas fa-edit mr-1"></i> Edit Produk Katalog Alkes');
            $('#katalog-id-produk').val(id);
            $('#katalog-nama-produk').val(nama);
            $('#katalog-kategori').val(kategori);
            $('#katalog-deskripsi').val(deskripsi);
            $('#katalog-harga').val(harga);
            $('#katalog-urutan').val(urutan);
            $('#katalog-status').val(status);

            $('#katalog-file-gambar').val('');
            $('#katalog-hapus-gambar').prop('checked', false);
            if (gambarRaw) {
                $('#katalog-gambar-preview-img').attr('src', gambarUrl);
                $('#katalog-gambar-path-text').text(gambarRaw);
                $('#katalog-gambar-preview-wrap').show();
                $('#katalog-url-gambar-ext').val(/^https?:\/\//i.test(gambarRaw) ? gambarRaw : '');
            } else {
                $('#katalog-gambar-preview-wrap').hide();
                $('#katalog-gambar-preview-img').attr('src', '');
                $('#katalog-gambar-path-text').text('-');
                $('#katalog-url-gambar-ext').val('');
            }

            $('#katalog-file-brosur').val('');
            $('#katalog-hapus-brosur').prop('checked', false);
            if (brosurRaw) {
                $('#katalog-brosur-preview-link').attr('href', brosurUrl);
                $('#katalog-brosur-path-text').text(brosurRaw);
                $('#katalog-brosur-preview-wrap').show();
                $('#katalog-url-brosur-ext').val(/^https?:\/\//i.test(brosurRaw) ? brosurRaw : '');
            } else {
                $('#katalog-brosur-preview-wrap').hide();
                $('#katalog-brosur-preview-link').attr('href', '#');
                $('#katalog-brosur-path-text').text('-');
                $('#katalog-url-brosur-ext').val('');
            }

            $('#modal-katalog').modal('show');
        });

        // Otomatis uncheck hapus_gambar / hapus_brosur jika user memilih file baru atau mengisi URL eksternal
        $('#katalog-file-gambar, #katalog-url-gambar-ext').on('change input', function() {
            if ($(this).val()) {
                $('#katalog-hapus-gambar').prop('checked', false);
            }
        });
        $('#katalog-file-brosur, #katalog-url-brosur-ext').on('change input', function() {
            if ($(this).val()) {
                $('#katalog-hapus-brosur').prop('checked', false);
            }
        });

        // Quick toggle Aktif/Nonaktif produk katalog
        $(document).on('click', '.btn-toggle-katalog', function() {
            var $btn      = $(this);
            var id        = $btn.attr('data-id');
            var nama      = $btn.attr('data-nama') || 'produk ini';
            var curStatus = $btn.attr('data-status') || 'aktif';
            var actionLbl = (curStatus === 'aktif') ? 'Nonaktifkan' : 'Aktifkan';

            if (!id) return;

            Swal.fire({
                title: actionLbl + ' Produk?',
                text: 'Ubah status tampil "' + nama + '" di portal afiliator?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: curStatus === 'aktif' ? '#64748b' : '#10b981',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, ' + actionLbl,
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.value || result.isConfirmed) {
                    var postData = {};
                    if (typeof token !== 'undefined') {
                        postData.csrf_token = token;
                    }
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: function() {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        url: '<?= base_url('Member_visiacademy/toggleKatalogStatus/') ?>' + id,
                        method: 'POST',
                        dataType: 'JSON',
                        data: postData,
                        success: function(resp) {
                            handleResponse(resp);
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat mengubah status produk.',
                                icon: 'error',
                                confirmButtonColor: '#ef4444',
                                confirmButtonText: 'Tutup'
                            });
                        }
                    });
                }
            });
        });

        // Open Modal Pembayaran Komisi
        $(document).on('click', '.btn-bayar-komisi', function() {
            var id = $(this).data('id');
            var member = $(this).data('member');
            var komisi = $(this).data('komisi');
            var bank = $(this).data('bank');
            var rek = $(this).data('rek');
            var an = $(this).data('an');
            var statusBayar = $(this).data('status-bayar');
            var tgl = $(this).data('tgl');
            var bukti = $(this).data('bukti');
            var catatan = $(this).data('catatan');

            $('#bayar-id-deal').val(id);
            $('#bayar-nama-member').text(member);
            $('#bayar-nominal-komisi').text(komisi);
            $('#bayar-bank').text(bank);
            $('#bayar-rekening').text(rek);
            $('#bayar-an').text(an);
            $('#bayar-status-bayar').val(statusBayar);
            $('#bayar-tgl-transfer').val(tgl);
            $('#bayar-catatan').val(catatan);
            $('#bayar-bukti-file').val('');

            if (bukti) {
                $('#bayar-bukti-current').show();
                $('#bayar-bukti-link').attr('href', '<?= base_url("uploads/bukti_komisi/") ?>' + bukti);
            } else {
                $('#bayar-bukti-current').hide();
            }

            $('#modal-bayar-komisi').modal('show');
        });
    });
</script>
