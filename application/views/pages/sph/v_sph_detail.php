<!-- View: v_sph_detail.php - Detail Surat Penawaran Harga -->

<div class="row">
    <div class="col-lg-8">
        <!-- Info SPH -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-invoice mr-2"></i><?= $sph->nomor_surat ?>
                    <?php if (!empty($sph->is_visilab)): ?>
                        <span class="badge badge-info ml-2">Visilab</span>
                    <?php endif; ?>
                </h6>
                <div>
                    <?php
                    $statusClass = [
                        'draft' => 'badge-secondary',
                        'final' => 'badge-info',
                        'signed' => 'badge-success',
                        'cancelled' => 'badge-danger'
                    ];
                    ?>
                    <span class="badge <?= $statusClass[$sph->status] ?? 'badge-secondary' ?> px-3 py-2">
                        <?= ucfirst($sph->status) ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%"><strong>Tanggal</strong></td>
                                <td><?= $this->md_sph->formatTanggalSurat($sph->tanggal_surat, $sph->kota) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Hal</strong></td>
                                <td><?= $sph->hal ?></td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat Oleh</strong></td>
                                <td><?= $sph->created_by_nama ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Dibuat</strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($sph->created_at)) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%"><strong>Kepada</strong></td>
                                <td><?= $sph->sapaan ?></td>
                            </tr>
                            <tr>
                                <td><strong>Instansi</strong></td>
                                <td><?= $sph->nama_penerima ?></td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td><?= $sph->alamat_penerima ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Produk -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table mr-2"></i>Daftar Produk
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Deskripsi</th>
                                <th class="text-right">Harga Pricelist</th>
                                <th class="text-center">Disc</th>
                                <th class="text-right">Harga Penawaran</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Subtotal</th>
                                <?php if (!empty($sph->dynamic_columns)): ?>
                                    <?php foreach ($sph->dynamic_columns as $col): ?>
                                        <th><?= $col->nama_kolom ?></th>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($sph->items as $item): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= $item->deskripsi ?></td>
                                    <td class="text-right">
                                        <?php if ($item->jenis_harga == 'free'): ?>
                                            <span class="badge badge-success">FREE</span>
                                        <?php else: ?>
                                            Rp <?= number_format($item->harga_pricelist, 0, ',', '.') ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($item->tipe_diskon == 'persen' && $item->diskon_persen > 0): ?>
                                            <?= number_format($item->diskon_persen, 2, ',', '.') ?>%
                                        <?php elseif ($item->tipe_diskon == 'nominal' && $item->diskon_nominal > 0): ?>
                                            Rp <?= number_format($item->diskon_nominal, 0, ',', '.') ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">Rp <?= number_format($item->harga_penawaran, 0, ',', '.') ?></td>
                                    <td class="text-center"><?= $item->qty ?></td>
                                    <td class="text-right">Rp <?= number_format($item->subtotal, 0, ',', '.') ?></td>
                                    <?php if (!empty($sph->dynamic_columns)): ?>
                                        <?php foreach ($sph->dynamic_columns as $col): ?>
                                            <td>
                                                <?php
                                                $val = '';
                                                if (!empty($item->dynamic_values)) {
                                                    foreach ($item->dynamic_values as $dv) {
                                                        if ($dv->sph_column_id == $col->id) {
                                                            $val = $dv->nilai;
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo $val;
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-light font-weight-bold">
                                <td colspan="6" class="text-right">TOTAL:</td>
                                <td class="text-right">Rp <?= number_format($sph->total_harga, 0, ',', '.') ?></td>
                                <?php if (!empty($sph->dynamic_columns)): ?>
                                    <td colspan="<?= count($sph->dynamic_columns) ?>"></td>
                                <?php endif; ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sistem Pembayaran -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-money-bill-wave mr-2"></i>Sistem Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <?php if ($sph->sistem_pembayaran == 'cash'): ?>
                    <p><strong>Cash</strong> - Pembayaran dilakukan secara tunai/langsung</p>
                <?php elseif ($sph->sistem_pembayaran == 'tempo'): ?>
                    <p><strong>Tempo</strong> - Jangka waktu pembayaran: <strong><?= $sph->tempo_hari ?> hari</strong></p>
                <?php elseif ($sph->sistem_pembayaran == 'cicilan'): ?>
                    <p><strong>Cicilan</strong></p>
                    <ul>
                        <li>DP: <?= number_format($sph->dp_persen, 0, ',', '.') ?>% = <strong>Rp <?= number_format($sph->dp_nominal, 0, ',', '.') ?></strong></li>
                        <li>Sisa Cicilan: <?= $sph->cicilan_bulan ?> bulan</li>
                        <li>Per Bulan: <strong>Rp <?= number_format($sph->cicilan_per_bulan, 0, ',', '.') ?></strong></li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list-ul mr-2"></i>Keterangan
                </h6>
            </div>
            <div class="card-body">
                <ol>
                    <?php if (!empty($sph->keterangan['selected'])): ?>
                        <?php foreach ($sph->keterangan['selected'] as $ket): ?>
                            <li><?= $ket->keterangan ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if (!empty($sph->keterangan['custom'])): ?>
                        <?php foreach ($sph->keterangan['custom'] as $ket): ?>
                            <li><?= $ket->keterangan ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ol>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Aksi -->
    <div class="col-lg-4">
        <!-- Aksi -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-cogs mr-2"></i>Aksi
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('sph') ?>" class="btn btn-secondary btn-block mb-2">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                    </a>

                    <?php if ($sph->status == 'draft'): ?>
                        <a href="<?= base_url('sph/edit/' . encrypt($sph->id)) ?>" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit mr-1"></i> Edit SPH
                        </a>
                        <button type="button" class="btn btn-info btn-block mb-2" id="btnFinalize">
                            <i class="fas fa-check mr-1"></i> Finalisasi
                        </button>
                    <?php elseif ($sph->status == 'final'): ?>
                        <button type="button" class="btn btn-warning btn-block mb-2" id="btnRevertEdit">
                            <i class="fas fa-edit mr-1"></i> Edit SPH (Revisi)
                        </button>
                    <?php endif; ?>

                    <?php if ($sph->status == 'draft' || $sph->status == 'final'): ?>
                        <button type="button" class="btn btn-success btn-block mb-2" id="btnSign">
                            <i class="fas fa-signature mr-1"></i> Tandatangani
                        </button>
                    <?php endif; ?>

                    <a href="<?= base_url('sph/print/' . encrypt($sph->id)) ?>" class="btn btn-primary btn-block mb-2" target="_blank">
                        <i class="fas fa-print mr-1"></i> Cetak / Print
                    </a>

                    <a href="<?= base_url('sph/download/' . encrypt($sph->id)) ?>" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-download mr-1"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- TTD Info -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-signature mr-2"></i>Tanda Tangan
                </h6>
            </div>
            <div class="card-body text-center">
                <p class="mb-1">Hormat kami,</p>
                <p class="font-weight-bold mb-1">PT. Visi Yosindo Medikal</p>

                <?php if ($sph->status == 'signed'): ?>
                    <div class="my-3">
                        <img src="<?= $sph->is_visilab ? base_url('uploads/file_karyawan/ttd/ttd_mega_cap.png') : base_url('uploads/file_karyawan/ttd/ttd_stample_gm.png') ?>" alt="TTD" style="max-height: 60px;" onerror="this.style.display='none'">
                        <p class="text-success mb-0"><i class="fas fa-check-circle"></i> Sudah ditandatangani</p>
                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($sph->signed_at)) ?></small>
                    </div>
                <?php else: ?>
                    <div class="my-3 text-muted" style="height: 50px; border-bottom: 1px solid #ddd;">
                        <small>(Belum ditandatangani)</small>
                    </div>
                <?php endif; ?>

                <p class="font-weight-bold mb-0">(<?= $sph->nama_ttd ?>)</p>
                <p class="text-muted"><?= $sph->jabatan_ttd ?></p>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history mr-2"></i>Timeline
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <i class="fas fa-circle text-primary mr-2"></i>
                        <strong>Dibuat</strong><br>
                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($sph->created_at)) ?></small>
                    </li>
                    <?php if ($sph->updated_at): ?>
                        <li class="mb-3">
                            <i class="fas fa-circle text-warning mr-2"></i>
                            <strong>Terakhir diupdate</strong><br>
                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($sph->updated_at)) ?></small>
                        </li>
                    <?php endif; ?>
                    <?php if ($sph->signed_at): ?>
                        <li class="mb-3">
                            <i class="fas fa-circle text-success mr-2"></i>
                            <strong>Ditandatangani</strong><br>
                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($sph->signed_at)) ?></small>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toastr fallback using PNotify
        if (typeof toastr === 'undefined') {
            window.toastr = {
                success: function(msg, title) {
                    new PNotify({
                        title: title || 'Sukses',
                        text: msg,
                        type: 'success',
                        delay: 3000
                    });
                },
                error: function(msg, title) {
                    new PNotify({
                        title: title || 'Error',
                        text: msg,
                        type: 'error',
                        delay: 4000
                    });
                },
                warning: function(msg, title) {
                    new PNotify({
                        title: title || 'Peringatan',
                        text: msg,
                        type: 'warning',
                        delay: 3500
                    });
                },
                info: function(msg, title) {
                    new PNotify({
                        title: title || 'Info',
                        text: msg,
                        type: 'info',
                        delay: 3000
                    });
                }
            };
        }

        // Finalize
        $('#btnFinalize').on('click', function() {
            Swal.fire({
                title: 'Finalisasi SPH?',
                text: 'Setelah difinalisasi, perubahan akan terbatas.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Finalisasi',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('sph/finalize') ?>',
                        type: 'POST',
                        data: {
                            id: '<?= encrypt($sph->id) ?>'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memproses permintaan.'
                            });
                        }
                    });
                }
            });
        });

        // Revert to Draft & Edit (untuk status final)
        $('#btnRevertEdit').on('click', function() {
            Swal.fire({
                title: 'Revisi SPH?',
                text: 'Status SPH akan dikembalikan ke Draft untuk dapat diedit. Lanjutkan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Edit SPH',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('sph/revertToDraft') ?>',
                        type: 'POST',
                        data: {
                            id: '<?= encrypt($sph->id) ?>'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 'success') {
                                // Redirect ke halaman edit
                                window.location.href = response.redirect_url;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memproses permintaan.'
                            });
                        }
                    });
                }
            });
        });

        // Sign
        $('#btnSign').on('click', function() {
            Swal.fire({
                title: 'Tandatangani SPH?',
                text: 'Setelah ditandatangani, SPH tidak dapat diubah lagi.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tandatangani',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('sph/sign') ?>',
                        type: 'POST',
                        data: {
                            id: '<?= encrypt($sph->id) ?>'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memproses permintaan.'
                            });
                        }
                    });
                }
            });
        });
    });
</script>