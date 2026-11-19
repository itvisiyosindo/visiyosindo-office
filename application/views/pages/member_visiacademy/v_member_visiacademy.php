<header class="page-header">
    <h2><i class="fas fa-users"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    .va-stat-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 18px 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .va-stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .va-stat-icon.blue { background: #eff6ff; color: #2563eb; }
    .va-stat-icon.purple { background: #faf5ff; color: #9333ea; }
    .va-stat-icon.green { background: #f0fdf4; color: #16a34a; }
    .va-stat-icon.amber { background: #fffbeb; color: #d97706; }
    .va-stat-val { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.2; }
    .va-stat-label { font-size: 13px; color: #64748b; margin: 2px 0 0; }
    .badge-deal { background: #dcfce7; color: #15803d; font-weight: 600; padding: 4px 10px; border-radius: 6px; }
    .badge-pending { background: #fef3c7; color: #b45309; font-weight: 600; padding: 4px 10px; border-radius: 6px; }
    .badge-batal { background: #fee2e2; color: #b91c1c; font-weight: 600; padding: 4px 10px; border-radius: 6px; }
    .panel-modern { border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04); margin-bottom: 24px; overflow: hidden; }
    .panel-modern .panel-heading { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; }
    .panel-modern .panel-title { font-size: 16px; font-weight: 700; color: #1e293b; margin: 0; }
</style>

<!-- Summary Cards -->
<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="va-stat-card">
            <div class="va-stat-icon blue"><i class="fas fa-user-check"></i></div>
            <div>
                <p class="va-stat-val"><?= number_format($summary['total_member']) ?></p>
                <p class="va-stat-label">Total Member Aktif</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="va-stat-card purple"><i class="fas fa-handshake"></i></div>
        <div>
            <p class="va-stat-val"><?= number_format($summary['total_deal']) ?></p>
            <p class="va-stat-label">Total Info Kebutuhan/Deal</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="va-stat-card green"><i class="fas fa-money-bill-wave"></i></div>
        <div>
            <p class="va-stat-val">Rp <?= number_format($summary['total_nilai_deal'], 0, ',', '.') ?></p>
            <p class="va-stat-label">Total Nilai Deal Terlaksana</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="va-stat-card amber"><i class="fas fa-award"></i></div>
        <div>
            <p class="va-stat-val">Rp <?= number_format($summary['total_komisi_deal'], 0, ',', '.') ?></p>
            <p class="va-stat-label">Total Komisi Terhitung (Deal)</p>
        </div>
    </div>
</div>

<!-- Panel 1: Data Member Visi Academy -->
<section class="panel panel-modern">
    <header class="panel-heading">
        <h2 class="panel-title"><i class="fas fa-id-card text-primary"></i>&nbsp; Daftar Member Visi Academy</h2>
        <div>
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-member">
                <i class="fas fa-user-plus"></i>&nbsp; Tambah Member Baru
            </button>
        </div>
    </header>
    <div class="panel-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th>Kode Member</th>
                        <th>Nama Member</th>
                        <th>No. Telp / WA</th>
                        <th>Email</th>
                        <th>Institusi / Asal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Belum ada data member yang didaftarkan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($members as $m): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><span class="badge badge-primary font-weight-bold"><?= htmlspecialchars($m['kode_member']) ?></span></td>
                                <td class="font-weight-bold text-dark"><?= htmlspecialchars($m['nama_member']) ?></td>
                                <td>
                                    <?php if (!empty($m['no_telp'])): ?>
                                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $m['no_telp']) ?>" target="_blank" class="text-success">
                                            <i class="fab fa-whatsapp"></i> <?= htmlspecialchars($m['no_telp']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($m['email'] ?: '-') ?></td>
                                <td><?= htmlspecialchars($m['institusi_asal'] ?: '-') ?></td>
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
</section>

<!-- Panel 2: Data Referral & Deal Produk Alkes -->
<section class="panel panel-modern">
    <header class="panel-heading">
        <h2 class="panel-title"><i class="fas fa-handshake text-success"></i>&nbsp; Tracking Kebutuhan Produk & Deal Komisi</h2>
        <div>
            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-deal">
                <i class="fas fa-plus-circle"></i>&nbsp; Input Deal Kebutuhan Alkes
            </button>
        </div>
    </header>
    <div class="panel-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th>Member (Referrer)</th>
                        <th>Kebutuhan Alkes / Produk</th>
                        <th class="text-right">Nilai Transaksi (Rp)</th>
                        <th class="text-center">Komisi (%)</th>
                        <th class="text-right">Estimasi Komisi (Rp)</th>
                        <th class="text-center">Status Deal</th>
                        <th>Catatan</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($deals)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-clipboard-list fa-2x mb-2 d-block"></i>
                                Belum ada riwayat deal kebutuhan produk dari member.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $nd = 1; foreach ($deals as $d): ?>
                            <tr>
                                <td class="text-center"><?= $nd++ ?></td>
                                <td>
                                    <strong class="text-dark"><?= htmlspecialchars($d['nama_member'] ?? '-') ?></strong>
                                    <br><small class="text-muted"><?= htmlspecialchars($d['kode_member'] ?? '') ?> - <?= htmlspecialchars($d['institusi_asal'] ?? '') ?></small>
                                </td>
                                <td>
                                    <span class="font-weight-semibold text-primary"><?= htmlspecialchars($d['nama_produk']) ?></span>
                                    <br><small class="text-muted"><i class="far fa-clock"></i> <?= date('d M Y', strtotime($d['created_at'])) ?></small>
                                </td>
                                <td class="text-right font-weight-bold">
                                    Rp <?= number_format($d['estimasi_nilai'], 0, ',', '.') ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info"><?= $d['persen_komisi'] ?>%</span>
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
                                <td>
                                    <small><?= htmlspecialchars($d['catatan'] ?: '-') ?></small>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-warning btn-edit-status" 
                                            data-id="<?= $d['id_deal'] ?>" 
                                            data-status="<?= $d['status_deal'] ?>" 
                                            title="Ubah Status Deal">
                                        <i class="fas fa-edit"></i> Status
                                    </button>
                                    <button type="button" class="btn btn-xs btn-danger btn-delete" 
                                            data-id="<?= $d['id_deal'] ?>" 
                                            data-object="Member_visiacademy/deleteDeal"
                                            title="Hapus Deal">
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
</section>

<!-- MODAL: Tambah Member -->
<div id="modal-member" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-user-plus"></i>&nbsp; Pendaftaran Member Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('Member_visiacademy/add', ['id' => 'form-member', 'autocomplete' => 'off']); ?>
            <div class="modal-body p-4">
                <div class="form-group mb-3">
                    <label class="font-weight-semibold">Kode Member <span class="text-danger">*</span></label>
                    <input type="text" name="kode_member" class="form-control" value="<?= $next_kode ?>" required>
                    <small class="text-muted">Kode unik otomatis atau dapat disesuaikan.</small>
                </div>
                <div class="form-group mb-3">
                    <label class="font-weight-semibold">Nama Lengkap Member <span class="text-danger">*</span></label>
                    <input type="text" name="nama_member" class="form-control" placeholder="Contoh: dr. Budi Santoso, Sp.Rad" required>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-semibold">No. WhatsApp / HP</label>
                        <input type="text" name="no_telp" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-semibold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@domain.com">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="font-weight-semibold">Institusi / RS / Klinik Asal</label>
                    <input type="text" name="institusi_asal" class="form-control" placeholder="Contoh: RSUD Pasar Minggu / Alumni PPR 2026">
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-save">
                    <i class="fas fa-save"></i>&nbsp; Simpan Member
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- MODAL: Input Deal Alkes -->
<div id="modal-deal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none;">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-handshake"></i>&nbsp; Input Kebutuhan Alkes & Deal Komisi</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('Member_visiacademy/addDeal', ['id' => 'form-deal', 'autocomplete' => 'off']); ?>
            <div class="modal-body p-4">
                <div class="form-group mb-3">
                    <label class="font-weight-semibold">Member yang Mereferensikan <span class="text-danger">*</span></label>
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
                    <label class="font-weight-semibold">Produk Alkes yang Dibutuhkan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Mobile X-Ray, Apron Pb, Viewer LED" required>
                </div>
                <div class="row">
                    <div class="col-md-7 form-group mb-3">
                        <label class="font-weight-semibold">Estimasi Nilai Transaksi (Rp) <span class="text-danger">*</span></label>
                        <input type="text" id="deal-nilai" name="estimasi_nilai" class="form-control" placeholder="Contoh: 150000000" required>
                    </div>
                    <div class="col-md-5 form-group mb-3">
                        <label class="font-weight-semibold">Komisi (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" id="deal-persen" name="persen_komisi" class="form-control" value="5" required>
                    </div>
                </div>
                <div class="alert alert-info py-2 px-3 mb-3" style="border-radius: 8px;">
                    <i class="fas fa-calculator text-primary"></i>&nbsp; Estimasi Komisi: <strong id="preview-komisi" class="text-success">Rp 0</strong>
                </div>
                <div class="form-group mb-3">
                    <label class="font-weight-semibold">Status Deal Saat Ini</label>
                    <select name="status_deal" class="form-control">
                        <option value="Pending">Pending (Sedang Prospek / Negosiasi)</option>
                        <option value="Deal">Deal (Transaksi Berhasil)</option>
                        <option value="Batal">Batal (Tidak Deal)</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="font-weight-semibold">Catatan Kebutuhan / Keterangan</label>
                    <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan spesifikasi, kontak PIC instansi, atau progres penawaran"></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success btn-save">
                    <i class="fas fa-save"></i>&nbsp; Simpan Deal
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- MODAL: Update Status Deal -->
<div id="modal-status-deal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none;">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-exchange-alt"></i>&nbsp; Update Status Deal</h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('Member_visiacademy/updateDealStatus', ['id' => 'form-status-deal']); ?>
            <div class="modal-body p-3">
                <input type="hidden" name="id_deal" id="edit-id-deal">
                <div class="form-group mb-2">
                    <label class="font-weight-semibold">Pilih Status Baru:</label>
                    <select name="status_deal" id="edit-status-deal" class="form-control">
                        <option value="Pending">Pending</option>
                        <option value="Deal">Deal (Selesai/Lunas)</option>
                        <option value="Batal">Batal</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary btn-save">Simpan Status</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kalkulasi komisi live di modal deal
        function hitungKomisiLive() {
            var rawNilai = $('#deal-nilai').val() || '0';
            var nilai = parseFloat(rawNilai.replace(/[^0-9]/g, '')) || 0;
            var persen = parseFloat($('#deal-persen').val()) || 0;
            var komisi = (nilai * persen) / 100;
            
            $('#preview-komisi').text('Rp ' + komisi.toLocaleString('id-ID'));
        }

        $('#deal-nilai, #deal-persen').on('input change keyup', hitungKomisiLive);

        // Handler tombol ubah status deal
        $(document).on('click', '.btn-edit-status', function() {
            var id = $(this).data('id');
            var status = $(this).data('status');
            $('#edit-id-deal').val(id);
            $('#edit-status-deal').val(status);
            $('#modal-status-deal').modal('show');
        });
    });
</script>
