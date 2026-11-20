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
</style>

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
                                        <button type="button" class="btn btn-xs btn-warning btn-edit-status mr-1" 
                                                data-id="<?= $d['id_deal'] ?>" 
                                                data-status="<?= $d['status_deal'] ?>" 
                                                title="Ubah Status Prospek">
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
                <h5 class="modal-title font-weight-bold"><i class="fas fa-handshake mr-1"></i> Input Kebutuhan Alkes & Deal Komisi</h5>
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
                    <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Mobile X-Ray, Apron Pb, Viewer LED" required>
                </div>
                <div class="row">
                    <div class="col-md-7 form-group mb-3">
                        <label class="font-weight-semibold text-dark">Estimasi Nilai Transaksi (Rp) <span class="text-danger">*</span></label>
                        <input type="text" id="deal-nilai" name="estimasi_nilai" class="form-control" placeholder="Contoh: 150000000" required>
                    </div>
                    <div class="col-md-5 form-group mb-3">
                        <label class="font-weight-semibold text-dark">Komisi (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" id="deal-persen" name="persen_komisi" class="form-control" value="5" required>
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

<!-- MODAL: Update Status Deal -->
<div id="modal-status-deal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-warning text-dark py-3">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-exchange-alt mr-1"></i> Update Status Deal</h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('Member_visiacademy/updateDealStatus', ['id' => 'form-status-deal']); ?>
            <div class="modal-body p-3">
                <input type="hidden" name="id_deal" id="edit-id-deal">
                <div class="form-group mb-2">
                    <label class="font-weight-semibold text-dark">Pilih Status Baru:</label>
                    <select name="status_deal" id="edit-status-deal" class="form-control">
                        <option value="Pending">Pending (Prospek)</option>
                        <option value="Deal">Deal (Selesai/Lunas)</option>
                        <option value="Batal">Batal</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary btn-save">Simpan Status</button>
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
        }

        // Live calculation di form deal
        function hitungKomisiLive() {
            var rawNilai = $('#deal-nilai').val() || '0';
            var nilai = parseFloat(rawNilai.replace(/[^0-9]/g, '')) || 0;
            var persen = parseFloat($('#deal-persen').val()) || 0;
            var komisi = (nilai * persen) / 100;
            
            $('#preview-komisi').text('Rp ' + komisi.toLocaleString('id-ID'));
        }

        $('#deal-nilai, #deal-persen').on('input change keyup', hitungKomisiLive);

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

        // Edit status deal modal handler
        $(document).on('click', '.btn-edit-status', function() {
            var id = $(this).data('id');
            var status = $(this).data('status');
            $('#edit-id-deal').val(id);
            $('#edit-status-deal').val(status);
            $('#modal-status-deal').modal('show');
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
