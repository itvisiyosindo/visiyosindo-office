<!-- View: v_after_sales_list.php - Halaman List Tiket After Sales Service -->

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-primary shadow-sm h-100 py-2" style="border-left: 4px solid #4e73df; border-radius: 8px;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Layanan / Servis</div>
                        <div class="h4 mb-0 font-weight-bold text-dark"><?= $counts['total'] ?? 0 ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools fa-2x text-gray-300" style="color: #cbd5e1;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-warning shadow-sm h-100 py-2" style="border-left: 4px solid #f6c23e; border-radius: 8px;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Kroscek</div>
                        <div class="h4 mb-0 font-weight-bold text-dark"><?= $counts['menunggu_kroscek'] ?? 0 ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hourglass-half fa-2x text-gray-300" style="color: #cbd5e1;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-info shadow-sm h-100 py-2" style="border-left: 4px solid #36b9cc; border-radius: 8px;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Dalam Proses</div>
                        <div class="h4 mb-0 font-weight-bold text-dark"><?= $counts['proses'] ?? 0 ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-cogs fa-2x text-gray-300" style="color: #cbd5e1;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-success shadow-sm h-100 py-2" style="border-left: 4px solid #1cc88a; border-radius: 8px;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Selesai</div>
                        <div class="h4 mb-0 font-weight-bold text-dark"><?= $counts['selesai'] ?? 0 ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300" style="color: #cbd5e1;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Card & Filters -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 8px;">
    <div class="card-header bg-white py-3 d-flex flex-wrap align-items-center justify-content-between border-bottom">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-list-ul mr-1"></i> Data Tiket After Sales Service
        </h6>
        <div>
            <a href="<?= base_url('after_sales/tambah') ?>" class="btn btn-primary btn-sm px-3 shadow-sm" style="border-radius: 6px;">
                <i class="fas fa-plus-circle mr-1"></i> Input Tiket Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" action="<?= base_url('after_sales') ?>" class="mb-4 bg-light p-3 rounded border">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="text-xs font-weight-bold text-muted mb-1">Status Pengerjaan</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="menunggu_kroscek" <?= ($filter['status'] ?? '') == 'menunggu_kroscek' ? 'selected' : '' ?>>Menunggu Kroscek</option>
                        <option value="proses" <?= ($filter['status'] ?? '') == 'proses' ? 'selected' : '' ?>>Dalam Proses</option>
                        <option value="selesai" <?= ($filter['status'] ?? '') == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                        <option value="batal" <?= ($filter['status'] ?? '') == 'batal' ? 'selected' : '' ?>>Batal</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="text-xs font-weight-bold text-muted mb-1">Teknisi Penanggung Jawab</label>
                    <select name="id_teknisi" class="form-control form-control-sm">
                        <option value="">-- Semua Teknisi --</option>
                        <?php if (!empty($teknisi_list)) {
                            foreach ($teknisi_list as $tek) { ?>
                                <option value="<?= $tek->pengguna_id ?>" <?= ($filter['id_teknisi'] ?? '') == $tek->pengguna_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tek->nama) ?>
                                </option>
                        <?php }
                        } ?>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="text-xs font-weight-bold text-muted mb-1">Cari Tiket / Instansi / Alat</label>
                    <input type="text" name="q" class="form-control form-control-sm" placeholder="No. Tiket, Pelanggan, atau Alat..." value="<?= htmlspecialchars($filter['search'] ?? '') ?>">
                </div>
                <div class="col-md-2 mb-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm btn-block mr-1">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="<?= base_url('after_sales') ?>" class="btn btn-secondary btn-sm" title="Reset Filter">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>

        <!-- Table Data -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped align-middle" id="tableAfterSales" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th width="4%" class="text-center">No</th>
                        <th width="14%">No. Tiket & Tgl</th>
                        <th width="20%">Pelanggan / Instansi</th>
                        <th width="18%">Alat & Kendala</th>
                        <th width="12%">Teknisi</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="10%" class="text-right">Total Biaya</th>
                        <th width="12%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tickets)) { ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-3x mb-2 text-gray-300 d-block"></i>
                                Belum ada data tiket after sales service.
                            </td>
                        </tr>
                    <?php } else {
                        $no = 1;
                        foreach ($tickets as $t) {
                            $badgeStatus = 'secondary';
                            $labelStatus = 'Draft';
                            if ($t->status == 'menunggu_kroscek') {
                                $badgeStatus = 'warning text-dark';
                                $labelStatus = 'Menunggu Kroscek';
                            } elseif ($t->status == 'proses') {
                                $badgeStatus = 'info';
                                $labelStatus = 'Dalam Proses';
                            } elseif ($t->status == 'selesai') {
                                $badgeStatus = 'success';
                                $labelStatus = 'Selesai';
                            } elseif ($t->status == 'batal') {
                                $badgeStatus = 'danger';
                                $labelStatus = 'Batal';
                            }
                    ?>
                        <tr>
                            <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                            <td>
                                <strong class="text-primary"><?= htmlspecialchars($t->nomor_tiket) ?></strong>
                                <div class="small text-muted">
                                    <i class="far fa-calendar-alt mr-1"></i><?= date('d/m/Y', strtotime($t->tgl_masuk)) ?>
                                </div>
                                <span class="badge badge-light border mt-1 font-weight-normal"><?= htmlspecialchars($t->jenis_layanan) ?></span>
                            </td>
                            <td>
                                <strong class="text-dark"><?= htmlspecialchars($t->nama_pelanggan) ?></strong>
                                <?php if (!empty($t->pic_nama)) { ?>
                                    <div class="small text-muted">
                                        <i class="fas fa-user mr-1"></i>PIC: <?= htmlspecialchars($t->pic_nama) ?>
                                        <?= !empty($t->pic_kontak) ? '(' . htmlspecialchars($t->pic_kontak) . ')' : '' ?>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($t->alamat_pelanggan)) { ?>
                                    <div class="small text-muted text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars($t->alamat_pelanggan) ?>">
                                        <i class="fas fa-map-marker-alt mr-1"></i><?= htmlspecialchars($t->alamat_pelanggan) ?>
                                    </div>
                                <?php } ?>
                            </td>
                            <td>
                                <strong class="text-dark"><?= htmlspecialchars($t->nama_alat) ?></strong>
                                <?php if (!empty($t->merk_tipe) || !empty($t->nomor_seri)) { ?>
                                    <div class="small text-muted">
                                        <?= htmlspecialchars($t->merk_tipe) ?> <?= !empty($t->nomor_seri) ? '[SN: ' . htmlspecialchars($t->nomor_seri) . ']' : '' ?>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($t->keluhan)) { ?>
                                    <div class="small text-danger mt-1 text-truncate" style="max-width: 240px;" title="<?= htmlspecialchars($t->keluhan) ?>">
                                        <em>"<?= htmlspecialchars($t->keluhan) ?>"</em>
                                    </div>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (!empty($t->teknisi_nama_lengkap) || !empty($t->nama_teknisi)) { ?>
                                    <span class="font-weight-bold text-dark">
                                        <i class="fas fa-wrench mr-1 text-secondary"></i>
                                        <?= htmlspecialchars($t->teknisi_nama_lengkap ?: $t->nama_teknisi) ?>
                                    </span>
                                <?php } else { ?>
                                    <span class="badge badge-secondary font-weight-normal">Belum ditentukan</span>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-<?= $badgeStatus ?> px-2 py-1" style="font-size: 11px;">
                                    <?= $labelStatus ?>
                                </span>
                            </td>
                            <td class="text-right font-weight-bold text-dark">
                                Rp <?= number_format($t->biaya_total, 0, ',', '.') ?>
                                <div class="small text-muted font-weight-normal">
                                    <?= strtoupper($t->status_pembayaran) ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('after_sales/detail/' . encrypt($t->id)) ?>" class="btn btn-info btn-sm shadow-sm" title="Lihat Detail & Pengerjaan">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="<?= base_url('after_sales/cetak/' . encrypt($t->id)) ?>" target="_blank" class="btn btn-danger btn-sm shadow-sm" title="Cetak Berita Acara / Laporan PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof $.fn.DataTable !== 'undefined') {
            $('#tableAfterSales').DataTable({
                "pageLength": 25,
                "ordering": true,
                "order": [[0, "asc"]],
                "language": {
                    "search": "Cari Cepat:",
                    "lengthMenu": "Tampilkan _MENU_ baris",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ tiket",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": "Lanjut",
                        "previous": "Sebelumnya"
                    }
                }
            });
        }
    });
</script>
