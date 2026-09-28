<header class="page-header">
    <h2><i class="fas fa-calendar-check"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    .cuti-diambil-trigger {
        border: 0;
        background: transparent;
        padding: 0;
        color: inherit;
        cursor: pointer;
    }

    .cuti-diambil-trigger:focus {
        outline: none;
    }

    .cuti-diambil-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        padding: 0.2rem 0.55rem;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 700;
        background: #e6f2ff;
        color: #0c5ea8;
        transition: all 0.2s ease;
    }

    .cuti-diambil-trigger:hover .cuti-diambil-pill {
        background: #0c5ea8;
        color: #fff;
    }

    .modal-cuti-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .modal-cuti-note {
        font-size: 0.78rem;
        border: 1px solid #dbe7f3;
        background: #f7fbff;
        color: #48627a;
        border-radius: 999px;
        padding: 0.3rem 0.7rem;
    }

    .summary-box {
        border: 1px solid #e6edf5;
        border-radius: 10px;
        padding: 0.6rem 0.75rem;
        background: #fbfdff;
        height: 100%;
    }

    .summary-box .label {
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 0.2rem;
    }

    .summary-box .value {
        color: #0f172a;
        font-weight: 700;
        font-size: 1rem;
        line-height: 1.2;
    }

    .status-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.22rem 0.58rem;
        font-size: 0.72rem;
        font-weight: 700;
        line-height: 1.2;
        white-space: nowrap;
    }

    .status-chip.diajukan { background: #fff8de; color: #8a6500; }
    .status-chip.disetujui_ga { background: #e7f7fb; color: #0f6c87; }
    .status-chip.disetujui_hr { background: #e8f1ff; color: #215fa3; }
    .status-chip.disetujui { background: #e9f9ef; color: #177245; }
    .status-chip.ditolak { background: #fdecec; color: #b42318; }
    .status-chip.default { background: #eef2f7; color: #475569; }
</style>

<div class="row">
    <div class="col-12">
        <section class="card card-modern">
            <div class="card-body">
                <form method="get" class="form-inline mb-3">
                    <label for="tahun-filter" class="mr-2">Tahun</label>
                    <input
                        type="number"
                        class="form-control mr-2"
                        id="tahun-filter"
                        name="tahun"
                        min="2000"
                        max="<?= (int) date('Y') + 1 ?>"
                        value="<?= (int) $tahun_berjalan ?>"
                    >
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>

                <small class="text-muted d-block mb-3">
                    Jatah dasar cuti tahunan: <?= max(0, 12 - (int) $pemerintah) ?> hari (sebelum penyesuaian masa kerja).
                </small>

                <div class="table-responsive">
                    <table id="table-sisa-cuti-karyawan" class="table table-striped table-sm table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th style="width:20%">Nama</th>
                                <th style="width:12%">NPP</th>
                                <th style="width:18%">Jabatan</th>
                                <th style="width:10%">Jatah Cuti</th>
                                <th style="width:10%">Cuti Diambil</th>
                                <th style="width:10%">Sisa Cuti</th>
                                <th style="width:15%">Kategori Masa Kerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $masa_kerja_label = array(
                                'A' => 'Di atas 5 tahun',
                                'B' => 'Di atas 1 tahun',
                                'C' => 'Di bawah 1 tahun',
                                'D' => 'Belum kontrak'
                            );
                            ?>

                            <?php if (!empty($rekap_sisa_cuti_karyawan)) { ?>
                                <?php foreach ($rekap_sisa_cuti_karyawan as $rekap) { ?>
                                    <?php $label_masa_kerja = isset($masa_kerja_label[$rekap->masa_kerja]) ? $masa_kerja_label[$rekap->masa_kerja] : '-'; ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($rekap->nama) ?></td>
                                        <td><?= htmlspecialchars($rekap->no_pegawai) ?></td>
                                        <td><?= htmlspecialchars($rekap->jabatan) ?></td>
                                        <td><?= $rekap->jatah_cuti ?></td>
                                        <td class="text-center">
                                            <button
                                                type="button"
                                                class="cuti-diambil-trigger"
                                                title="Lihat detail cuti diambil"
                                                data-pengguna-id="<?= (int) $rekap->pengguna_id ?>"
                                                data-nama="<?= htmlspecialchars($rekap->nama, ENT_QUOTES, 'UTF-8') ?>"
                                                data-npp="<?= htmlspecialchars($rekap->no_pegawai, ENT_QUOTES, 'UTF-8') ?>"
                                                data-tahun="<?= (int) $tahun_berjalan ?>"
                                            >
                                                <span class="cuti-diambil-pill"><?= $rekap->cuti_diambil ?></span>
                                            </button>
                                        </td>
                                        <td><?= $rekap->sisa_cuti ?></td>
                                        <td><?= $label_masa_kerja ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modal-detail-cuti-diambil" tabindex="-1" role="dialog" aria-labelledby="modal-detail-cuti-diambil-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-cuti-header mb-3">
                    <div>
                        <h5 class="mb-1" id="modal-detail-cuti-diambil-label">Detail Cuti Diambil</h5>
                        <div class="text-muted" id="detail-cuti-subtitle">Pilih angka pada kolom Cuti Diambil untuk melihat detail.</div>
                    </div>
                    <div class="modal-cuti-note">Ringkasan menampilkan semua status, tabel hanya menampilkan cuti berstatus Disetujui.</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-2 col-6 mb-2">
                        <div class="summary-box">
                            <div class="label">Total Pengajuan</div>
                            <div class="value" id="sum-total-pengajuan">0</div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-2">
                        <div class="summary-box">
                            <div class="label">Disetujui</div>
                            <div class="value" id="sum-total-disetujui">0</div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-2">
                        <div class="summary-box">
                            <div class="label">Pending</div>
                            <div class="value" id="sum-total-pending">0</div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-2">
                        <div class="summary-box">
                            <div class="label">Ditolak</div>
                            <div class="value" id="sum-total-ditolak">0</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12 mb-2">
                        <div class="summary-box">
                            <div class="label">Total Hari Disetujui</div>
                            <div class="value" id="sum-total-hari-disetujui">0</div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th style="width:17%">Kode Cuti</th>
                                <th style="width:22%">Tanggal Cuti</th>
                                <th style="width:11%">Total Hari</th>
                                <th style="width:18%">Status</th>
                                <th>Masuk Perhitungan</th>
                            </tr>
                        </thead>
                        <tbody id="detail-cuti-body">
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data ditampilkan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function escapeHtml(value) {
            return $('<div>').text(value === null || value === undefined ? '' : value).html();
        }

        function renderStatusChip(statusKey, statusLabel) {
            var map = {
                diajukan: 'diajukan',
                disetujui_ga: 'disetujui_ga',
                disetujui_hr: 'disetujui_hr',
                disetujui: 'disetujui',
                ditolak: 'ditolak'
            };
            var klass = map[statusKey] ? map[statusKey] : 'default';

            return '<span class="status-chip ' + klass + '">' + escapeHtml(statusLabel) + '</span>';
        }

        function renderMasukPerhitunganBadge(flag) {
            if (parseInt(flag, 10) === 1) {
                return '<span class="status-chip disetujui">Ya</span>';
            }

            return '<span class="status-chip default">Tidak</span>';
        }

        function renderDetailRows(rows) {
            var $tbody = $('#detail-cuti-body');

            if (!rows.length) {
                $tbody.html('<tr><td colspan="6" class="text-center text-muted">Tidak ada cuti berstatus Disetujui pada tahun ini.</td></tr>');
                return;
            }

            var html = '';
            rows.forEach(function(row, index) {
                html += '<tr>'
                    + '<td class="text-center">' + (index + 1) + '</td>'
                    + '<td>' + escapeHtml(row.kode_cuti || '-') + '</td>'
                    + '<td>' + escapeHtml(row.tanggal_label || '-') + '</td>'
                    + '<td class="text-center">' + escapeHtml(row.total_hari || 0) + '</td>'
                    + '<td class="text-center">' + renderStatusChip(row.status_key, row.status_label) + '</td>'
                    + '<td class="text-center">' + renderMasukPerhitunganBadge(row.masuk_perhitungan) + '</td>'
                    + '</tr>';
            });

            $tbody.html(html);
        }

        function setSummary(summary) {
            var totalHari = Number(summary.total_hari_disetujui || 0);

            $('#sum-total-pengajuan').text(summary.total_pengajuan || 0);
            $('#sum-total-disetujui').text(summary.total_disetujui || 0);
            $('#sum-total-pending').text(summary.total_pending || 0);
            $('#sum-total-ditolak').text(summary.total_ditolak || 0);
            $('#sum-total-hari-disetujui').text(Number.isInteger(totalHari) ? totalHari : totalHari.toFixed(2));
        }

        function setLoadingState() {
            setSummary({});
            $('#detail-cuti-body').html('<tr><td colspan="6" class="text-center text-muted">Memuat detail cuti...</td></tr>');
        }

        if ($('#table-sisa-cuti-karyawan').length) {
            $('#table-sisa-cuti-karyawan').DataTable({
                pageLength: 25,
                order: [[1, 'asc']],
                language: {
                    emptyTable: 'Data tidak ditemukan.'
                }
            });
        }

        $(document).on('click', '.cuti-diambil-trigger', function() {
            var penggunaId = $(this).data('pengguna-id');
            var nama = $(this).data('nama') || '-';
            var npp = $(this).data('npp') || '-';
            var tahun = $(this).data('tahun');

            $('#detail-cuti-subtitle').text('Karyawan: ' + nama + ' | NPP: ' + npp + ' | Tahun: ' + tahun);
            setLoadingState();
            $('#modal-detail-cuti-diambil').modal('show');

            $.ajax({
                method: 'POST',
                url: '<?= base_url('dashboard_kepegawaian/detail_cuti_diambil') ?>',
                dataType: 'JSON',
                data: {
                    pengguna_id: penggunaId,
                    tahun: tahun,
                    csrf_token: (typeof token !== 'undefined' ? token : '')
                },
                success: function(resp) {
                    if (!resp || resp.status !== 'success') {
                        $('#detail-cuti-body').html('<tr><td colspan="6" class="text-center text-danger">' + escapeHtml((resp && resp.message) ? resp.message : 'Gagal memuat data detail cuti.') + '</td></tr>');
                        setSummary({});
                        return;
                    }

                    var rows = Array.isArray(resp.data) ? resp.data : [];
                    setSummary(resp.summary || {});
                    renderDetailRows(rows);
                },
                error: function() {
                    $('#detail-cuti-body').html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data detail cuti. Silakan coba lagi.</td></tr>');
                    setSummary({});
                }
            });
        });
    });
</script>
