<!-- View: v_after_sales_detail.php - Rincian Tiket, Pengerjaan, & Logs -->

<?php
$badgeStatus = 'secondary';
$labelStatus = 'Draft';
if ($ticket->status == 'menunggu_kroscek') {
    $badgeStatus = 'warning text-dark';
    $labelStatus = 'Menunggu Kroscek';
} elseif ($ticket->status == 'proses') {
    $badgeStatus = 'info';
    $labelStatus = 'Dalam Proses Pengerjaan';
} elseif ($ticket->status == 'selesai') {
    $badgeStatus = 'success';
    $labelStatus = 'Selesai';
} elseif ($ticket->status == 'batal') {
    $badgeStatus = 'danger';
    $labelStatus = 'Dibatalkan';
}
?>

<div class="row">
    <!-- KOLOM KIRI: DETAIL TIKET & ALUR STATUS -->
    <div class="col-lg-8">
        <!-- Flash messages -->
        <?php if ($this->session->flashdata('success')) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> <?= $this->session->flashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
        <?php if ($this->session->flashdata('error')) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-1"></i> <?= $this->session->flashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>

        <!-- KARTU UTAMA INFORMASI -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 8px;">
            <div class="card-header bg-white py-3 d-flex flex-wrap align-items-center justify-content-between border-bottom">
                <div>
                    <span class="badge badge-<?= $badgeStatus ?> px-2 py-1 mr-2" style="font-size: 12px;"><?= $labelStatus ?></span>
                    <strong class="h5 font-weight-bold text-primary mb-0"><?= htmlspecialchars($ticket->nomor_tiket) ?></strong>
                </div>
                <div class="mt-2 mt-md-0">
                    <a href="<?= base_url('after_sales/cetak/' . $encrypted_id) ?>" target="_blank" class="btn btn-outline-danger btn-sm shadow-sm mr-1">
                        <i class="fas fa-file-pdf mr-1"></i> Cetak Berita Acara PDF
                    </a>
                    <a href="<?= base_url('after_sales') ?>" class="btn btn-secondary btn-sm shadow-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-6 border-right">
                        <h6 class="text-xs font-weight-bold text-muted text-uppercase mb-2">Data Pelanggan & Lokasi</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td width="35%" class="text-muted">Nama Instansi</td>
                                <td width="5%">:</td>
                                <td class="font-weight-bold text-dark"><?= htmlspecialchars($ticket->nama_pelanggan) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Alamat</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($ticket->alamat_pelanggan ?: '-') ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">PIC Pelapor</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($ticket->pic_nama ?: '-') ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kontak WhatsApp</td>
                                <td>:</td>
                                <td>
                                    <?php if (!empty($ticket->pic_kontak)) { ?>
                                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $ticket->pic_kontak) ?>" target="_blank" class="text-success font-weight-bold">
                                            <i class="fab fa-whatsapp mr-1"></i><?= htmlspecialchars($ticket->pic_kontak) ?>
                                        </a>
                                    <?php } else { echo '-'; } ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="text-xs font-weight-bold text-muted text-uppercase mb-2">Informasi Unit Alat Medis</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td width="35%" class="text-muted">Nama Alat</td>
                                <td width="5%">:</td>
                                <td class="font-weight-bold text-dark"><?= htmlspecialchars($ticket->nama_alat) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Merk / Tipe</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($ticket->merk_tipe ?: '-') ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Serial Number</td>
                                <td>:</td>
                                <td class="font-weight-bold text-primary"><?= htmlspecialchars($ticket->nomor_seri ?: '-') ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jenis Layanan</td>
                                <td>:</td>
                                <td><span class="badge badge-light border"><?= htmlspecialchars($ticket->jenis_layanan) ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Skema Bayar</td>
                                <td>:</td>
                                <td><span class="badge badge-info"><?= strtoupper($ticket->status_pembayaran) ?></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Rincian Keluhan Kerusakan:</label>
                        <div class="p-3 bg-light rounded border text-dark">
                            <?= nl2br(htmlspecialchars($ticket->keluhan ?: 'Tidak ada rincian keluhan.')) ?>
                        </div>
                    </div>

                    <?php if (!empty($ticket->tindakan_perbaikan)) { ?>
                        <div class="col-md-12 mb-3">
                            <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Tindakan / Hasil Perbaikan Teknisi:</label>
                            <div class="p-3 rounded border bg-light text-dark" style="border-left: 4px solid #1cc88a !important;">
                                <?= nl2br(htmlspecialchars($ticket->tindakan_perbaikan)) ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- DAFTAR SPAREPART & JASA SERVIS -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 8px;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-boxes mr-1"></i> Rincian Penggantian Sparepart & Jasa Servis
                </h6>
                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="collapse" data-target="#collapseEditItem">
                    <i class="fas fa-edit mr-1"></i> Kelola Item / Biaya
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="12%">Kategori</th>
                                <th width="38%">Deskripsi</th>
                                <th width="10%" class="text-center">Qty</th>
                                <th width="15%" class="text-right">Harga Satuan</th>
                                <th width="20%" class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ticket->items)) { ?>
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">Belum ada item sparepart atau jasa servis yang ditambahkan.</td>
                                </tr>
                            <?php } else {
                                $i = 1;
                                foreach ($ticket->items as $it) { ?>
                                    <tr>
                                        <td class="text-center"><?= $i++ ?></td>
                                        <td>
                                            <span class="badge badge-<?= $it->jenis == 'jasa' ? 'info' : 'secondary' ?>">
                                                <?= ucfirst($it->jenis) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($it->deskripsi) ?></td>
                                        <td class="text-center"><?= $it->qty ?> <?= htmlspecialchars($it->satuan) ?></td>
                                        <td class="text-right">Rp <?= number_format($it->harga_satuan, 0, ',', '.') ?></td>
                                        <td class="text-right font-weight-bold">Rp <?= number_format($it->subtotal, 0, ',', '.') ?></td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="5" class="text-right font-weight-bold">Total Biaya Sparepart:</td>
                                <td class="text-right font-weight-bold">Rp <?= number_format($ticket->biaya_sparepart, 0, ',', '.') ?></td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="5" class="text-right font-weight-bold">Total Biaya Jasa:</td>
                                <td class="text-right font-weight-bold">Rp <?= number_format($ticket->biaya_jasa, 0, ',', '.') ?></td>
                            </tr>
                            <tr class="bg-light text-primary font-weight-bold" style="font-size: 15px;">
                                <td colspan="5" class="text-right">TOTAL KESELURUHAN:</td>
                                <td class="text-right">Rp <?= number_format($ticket->biaya_total, 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- COLLAPSE FORM EDIT ITEMS -->
                <div class="collapse p-3 bg-light border-top" id="collapseEditItem">
                    <form action="<?= base_url('after_sales/update_items') ?>" method="POST">
                        <input type="hidden" name="id_tiket" value="<?= $encrypted_id ?>">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong class="text-dark">Form Tambah/Edit Item Sparepart & Jasa</strong>
                            <button type="button" class="btn btn-sm btn-primary" id="btnDetailAddRow"><i class="fas fa-plus mr-1"></i> Tambah Baris</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered bg-white" id="tableDetailItems">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="15%">Kategori</th>
                                        <th width="35%">Deskripsi</th>
                                        <th width="10%">Qty</th>
                                        <th width="10%">Satuan</th>
                                        <th width="15%">Harga</th>
                                        <th width="10%">Subtotal</th>
                                        <th width="5%" class="text-center">X</th>
                                    </tr>
                                </thead>
                                <tbody id="detailItemRows">
                                    <?php if (!empty($ticket->items)) {
                                        foreach ($ticket->items as $it) { ?>
                                            <tr>
                                                <td>
                                                    <select name="item_jenis[]" class="form-control form-control-sm">
                                                        <option value="sparepart" <?= $it->jenis == 'sparepart' ? 'selected' : '' ?>>Sparepart</option>
                                                        <option value="jasa" <?= $it->jenis == 'jasa' ? 'selected' : '' ?>>Jasa</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" name="item_deskripsi[]" class="form-control form-control-sm" value="<?= htmlspecialchars($it->deskripsi) ?>" required></td>
                                                <td><input type="number" name="item_qty[]" class="form-control form-control-sm row-qty" value="<?= $it->qty ?>" min="1"></td>
                                                <td><input type="text" name="item_satuan[]" class="form-control form-control-sm" value="<?= htmlspecialchars($it->satuan) ?>"></td>
                                                <td><input type="number" name="item_harga[]" class="form-control form-control-sm row-harga text-right" value="<?= (int)$it->harga_satuan ?>" min="0"></td>
                                                <td class="text-right align-middle font-weight-bold row-subtotal"><?= number_format($it->subtotal, 0, ',', '.') ?></td>
                                                <td class="text-center align-middle"><button type="button" class="btn btn-danger btn-sm btn-del-row"><i class="fas fa-times"></i></button></td>
                                            </tr>
                                    <?php }
                                    } else { ?>
                                        <tr>
                                            <td>
                                                <select name="item_jenis[]" class="form-control form-control-sm">
                                                    <option value="sparepart">Sparepart</option>
                                                    <option value="jasa">Jasa</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="item_deskripsi[]" class="form-control form-control-sm" placeholder="Nama sparepart/jasa..."></td>
                                            <td><input type="number" name="item_qty[]" class="form-control form-control-sm row-qty" value="1" min="1"></td>
                                            <td><input type="text" name="item_satuan[]" class="form-control form-control-sm" value="Pcs"></td>
                                            <td><input type="number" name="item_harga[]" class="form-control form-control-sm row-harga text-right" value="0" min="0"></td>
                                            <td class="text-right align-middle font-weight-bold row-subtotal">0</td>
                                            <td class="text-center align-middle"><button type="button" class="btn btn-danger btn-sm btn-del-row"><i class="fas fa-times"></i></button></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right mt-2">
                            <button type="submit" class="btn btn-success btn-sm px-3 shadow-sm"><i class="fas fa-save mr-1"></i> Simpan Perubahan Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: STATUS ACTION & TIMELINE LOGS -->
    <div class="col-lg-4">
        <!-- ACTION BOX: UPDATE STATUS -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 8px;">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-tasks mr-1"></i> Update Status & Penugasan
                </h6>
            </div>
            <div class="card-body">
                <form action="<?= base_url('after_sales/update_status') ?>" method="POST">
                    <input type="hidden" name="id_tiket" value="<?= $encrypted_id ?>">

                    <div class="form-group">
                        <label class="font-weight-bold text-xs text-muted text-uppercase">Status Tiket Saat Ini</label>
                        <select name="status" class="form-control font-weight-bold" required>
                            <option value="menunggu_kroscek" <?= $ticket->status == 'menunggu_kroscek' ? 'selected' : '' ?>>Menunggu Kroscek</option>
                            <option value="proses" <?= $ticket->status == 'proses' ? 'selected' : '' ?>>Proses Pengerjaan</option>
                            <option value="selesai" <?= $ticket->status == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option value="batal" <?= $ticket->status == 'batal' ? 'selected' : '' ?>>Dibatalkan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-xs text-muted text-uppercase">Teknisi Penanggung Jawab</label>
                        <select name="id_teknisi" class="form-control select2">
                            <option value="">-- Pilih Teknisi --</option>
                            <?php if (!empty($teknisi_list)) {
                                foreach ($teknisi_list as $tek) { ?>
                                    <option value="<?= $tek->id_pengguna ?>" <?= $ticket->id_teknisi == $tek->id_pengguna ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($tek->nama) ?>
                                    </option>
                            <?php }
                            } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-xs text-muted text-uppercase">Tindakan / Hasil Perbaikan</label>
                        <textarea name="tindakan_perbaikan" class="form-control" rows="3" placeholder="Tuliskan tindakan teknisi, solusi, atau pergantian unit..."><?= htmlspecialchars($ticket->tindakan_perbaikan ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-xs text-muted text-uppercase">Catatan Perubahan Status (Log)</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan opsional log alur tiket..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block shadow-sm">
                        <i class="fas fa-check-circle mr-1"></i> Simpan Status Tiket
                    </button>
                </form>
            </div>
        </div>

        <!-- TIMELINE LOGS -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 8px;">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history mr-1"></i> Riwayat & Aktivitas Tiket
                </h6>
            </div>
            <div class="card-body p-3">
                <?php if (empty($ticket->logs)) { ?>
                    <p class="text-muted text-center small mb-0">Belum ada riwayat aktivitas.</p>
                <?php } else { ?>
                    <ul class="timeline list-unstyled mb-0" style="position: relative; border-left: 2px solid #e3e6f0; margin-left: 10px; padding-left: 15px;">
                        <?php foreach (array_reverse($ticket->logs) as $lg) { ?>
                            <li class="mb-3" style="position: relative;">
                                <div style="position: absolute; left: -21px; top: 2px; width: 10px; height: 10px; border-radius: 50%; background: #4e73df;"></div>
                                <div class="font-weight-bold text-dark small">
                                    <?= htmlspecialchars($lg->user_nama ?: 'System') ?>
                                    <span class="badge badge-light border ml-1"><?= strtoupper(str_replace('_', ' ', $lg->status_baru)) ?></span>
                                </div>
                                <div class="small text-muted mb-1">
                                    <i class="far fa-clock mr-1"></i><?= date('d M Y H:i', strtotime($lg->created_at)) ?>
                                </div>
                                <div class="small text-secondary bg-light p-2 rounded border">
                                    <?= htmlspecialchars($lg->keterangan) ?>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function hitungDetailTotal() {
            $('#detailItemRows tr').each(function() {
                var qty = parseFloat($(this).find('.row-qty').val()) || 0;
                var harga = parseFloat($(this).find('.row-harga').val()) || 0;
                var sub = qty * harga;
                $(this).find('.row-subtotal').text(sub.toLocaleString('id-ID'));
            });
        }

        $(document).on('input', '.row-qty, .row-harga', function() {
            hitungDetailTotal();
        });

        $('#btnDetailAddRow').on('click', function() {
            var row = `<tr>
                <td>
                    <select name="item_jenis[]" class="form-control form-control-sm">
                        <option value="sparepart">Sparepart</option>
                        <option value="jasa">Jasa</option>
                    </select>
                </td>
                <td><input type="text" name="item_deskripsi[]" class="form-control form-control-sm" placeholder="Nama sparepart/jasa..." required></td>
                <td><input type="number" name="item_qty[]" class="form-control form-control-sm row-qty" value="1" min="1"></td>
                <td><input type="text" name="item_satuan[]" class="form-control form-control-sm" value="Pcs"></td>
                <td><input type="number" name="item_harga[]" class="form-control form-control-sm row-harga text-right" value="0" min="0"></td>
                <td class="text-right align-middle font-weight-bold row-subtotal">0</td>
                <td class="text-center align-middle"><button type="button" class="btn btn-danger btn-sm btn-del-row"><i class="fas fa-times"></i></button></td>
            </tr>`;
            $('#detailItemRows').append(row);
        });

        $(document).on('click', '.btn-del-row', function() {
            if ($('#detailItemRows tr').length > 1) {
                $(this).closest('tr').remove();
            }
        });
    });
</script>
