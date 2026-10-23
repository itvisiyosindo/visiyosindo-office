<header class="page-header">
    <h2><i class="fab fa-whatsapp text-success mr-2"></i><?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    .kpi-card {
        border-radius: 12px;
        border: 0;
        background: #fff;
        padding: 18px 20px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.04);
        margin-bottom: 20px;
        border-left: 4px solid #cbd5e1;
        transition: transform 0.2s ease;
    }
    .kpi-card:hover { transform: translateY(-3px); }
    .kpi-card.kpi-green { border-left-color: #10b981; }
    .kpi-card.kpi-amber { border-left-color: #f59e0b; }
    .kpi-card.kpi-red { border-left-color: #ef4444; }
    .kpi-card.kpi-blue { border-left-color: #3b82f6; }

    .kpi-num {
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1.1;
        color: #1e293b;
    }
    .kpi-label {
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .badge-session {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 20px;
    }
    .badge-active { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-expiring { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .badge-expired { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

    .webhook-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 14px 16px;
    }
</style>

<?php
$totalAll = count($sessions ?? []);
$totalActive = 0;
$totalExpiring = 0;
$totalExpired = 0;

if (!empty($sessions)) {
    foreach ($sessions as $s) {
        if ($s->session_status === 'ACTIVE') $totalActive++;
        elseif ($s->session_status === 'EXPIRING_SOON') $totalExpiring++;
        else $totalExpired++;
    }
}
?>

<div class="row">
    <div class="col-12">
        <!-- Flash Alert -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <i class="fas fa-check-circle mr-2"></i><?= $this->session->flashdata('success'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <i class="fas fa-exclamation-triangle mr-2"></i><?= $this->session->flashdata('error'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Stat Cards -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card kpi-blue">
                    <div class="kpi-label">Total Nomor Terdata</div>
                    <div class="kpi-num"><?= $totalAll ?></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card kpi-green">
                    <div class="kpi-label">Sesi Aktif (< 23 Jam)</div>
                    <div class="kpi-num text-success"><?= $totalActive ?></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card kpi-amber">
                    <div class="kpi-label">Hampir Habis (Jam 23-24)</div>
                    <div class="kpi-num text-warning"><?= $totalExpiring ?></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card kpi-red">
                    <div class="kpi-label">Kadaluarsa (> 24 Jam)</div>
                    <div class="kpi-num text-danger"><?= $totalExpired ?></div>
                </div>
            </div>
        </div>

        <!-- Info Card Webhook -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle text-primary fa-2x mr-3 mt-1"></i>
                    <div class="w-100">
                        <strong class="text-dark">Aturan 24-Hour Messaging Window WhatsApp:</strong>
                        <p class="small text-muted mb-2">
                            Pesan notifikasi pribadi hanya diizinkan terkirim jika nomor tujuan ada chat masuk ke nomor kantor dalam 24 jam terakhir. Pesan ke <strong>Grup WhatsApp</strong> bebas dikirim 24/7 tanpa batas.
                        </p>
                        <div class="webhook-box d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <small class="text-muted d-block font-weight-bold">URL Webhook Gateway (Fonnte / Convia / Whacenter):</small>
                                <code id="webhookUrl" style="font-size: 0.95rem;">https://office.visiyosindo.id/webhook</code>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2 mt-sm-0" onclick="navigator.clipboard.writeText('https://office.visiyosindo.id/webhook'); alert('URL Webhook berhasil disalin!');">
                                <i class="fas fa-copy mr-1"></i> Salin URL Webhook
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card Sessions -->
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3" style="border-bottom: 1px solid #edf2f7; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-history text-primary mr-2"></i>Daftar Sesi WhatsApp Nomor Pribadi</h5>
                <div>
                    <a href="<?= base_url('reminder_wa/deactivate_inactive_sessions') ?>" class="btn btn-outline-danger btn-sm font-weight-bold mr-1" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan seluruh sesi WhatsApp milik karyawan yang sudah tidak aktif?')">
                        <i class="fas fa-user-slash mr-1"></i> Matikan Sesi Karyawan Non-Aktif
                    </a>
                    <button type="button" class="btn btn-success btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalAktivasiManual">
                        <i class="fas fa-plus-circle mr-1"></i> Buka Sesi Manual (24 Jam)
                    </button>
                    <a href="<?= base_url('reminder_wa/check_sessions') ?>" target="_blank" class="btn btn-outline-secondary btn-sm ml-1">
                        <i class="fas fa-bell mr-1"></i> Cek & Kirim Reminder
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="table-sessions">
                        <thead class="bg-light">
                            <tr>
                                <th width="4%" class="text-center">#</th>
                                <th width="22%">Nama Pengguna / Kontak</th>
                                <th width="18%">Nomor WhatsApp</th>
                                <th width="18%">Chat Terakhir Diterima</th>
                                <th width="16%">Sisa Waktu Sesi</th>
                                <th width="12%" class="text-center">Status</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($sessions)): ?>
                                <?php $no = 1; foreach ($sessions as $row): ?>
                                    <tr>
                                        <td class="text-center text-muted small"><?= $no++ ?></td>
                                        <td>
                                            <strong class="text-dark"><?= htmlspecialchars($row->nama ?? 'Tamu / Customer') ?></strong>
                                            <?php if (isset($row->is_karyawan_active) && $row->is_karyawan_active === '0'): ?>
                                                <span class="badge badge-secondary ml-1" style="font-size: 10px;"><i class="fas fa-user-times mr-1"></i>Non-Aktif</span>
                                            <?php elseif (isset($row->is_karyawan_active) && $row->is_karyawan_active === '1'): ?>
                                                <span class="badge badge-light border text-success ml-1" style="font-size: 9px;"><i class="fas fa-check-circle mr-1"></i>Karyawan</span>
                                            <?php endif; ?>
                                            <?php if (!empty($row->last_message_text)): ?>
                                                <small class="text-muted d-block font-italic">"<?= htmlspecialchars(substr($row->last_message_text, 0, 40)) ?><?= strlen($row->last_message_text) > 40 ? '...' : '' ?>"</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <code><?= htmlspecialchars($row->phone_number) ?></code>
                                        </td>
                                        <td>
                                            <?= !empty($row->last_incoming_at) ? date('d/m/Y H:i', strtotime($row->last_incoming_at)) : '-' ?>
                                            <small class="text-muted d-block"><?= $row->elapsed_hours ?? 0 ?> jam yang lalu</small>
                                        </td>
                                        <td>
                                            <?php if ($row->session_status === 'ACTIVE' || $row->session_status === 'EXPIRING_SOON'): ?>
                                                <?php 
                                                    $rem = max(0, intval($row->remaining_minutes ?? 0));
                                                    $remHours = floor($rem / 60);
                                                    $remMins = $rem % 60;
                                                ?>
                                                <span class="font-weight-bold text-dark"><?= $remHours ?> Jam <?= $remMins ?> Menit</span>
                                            <?php else: ?>
                                                <span class="text-muted">Kadaluarsa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row->session_status === 'ACTIVE'): ?>
                                                <span class="badge-session badge-active"><i class="fas fa-check-circle mr-1"></i>Aktif</span>
                                            <?php elseif ($row->session_status === 'EXPIRING_SOON'): ?>
                                                <span class="badge-session badge-expiring"><i class="fas fa-clock mr-1"></i>Hampir Habis</span>
                                            <?php else: ?>
                                                <span class="badge-session badge-expired"><i class="fas fa-times-circle mr-1"></i>Kadaluarsa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <form action="<?= base_url('reminder_wa/activate_session') ?>" method="post" class="d-inline">
                                                <input type="hidden" name="phone" value="<?= htmlspecialchars($row->phone_number) ?>">
                                                <button type="submit" class="btn btn-xs btn-outline-success" title="Perpanjang 24 Jam">
                                                    <i class="fas fa-redo"></i> Perpanjang
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fab fa-whatsapp fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                                        <h6>Belum ada riwayat sesi nomor WhatsApp tercatat</h6>
                                        <p class="small text-muted mb-3">Ketika seseorang mengirim chat masuk ke bot kantor atau sesi dibuka manual, data akan muncul di sini.</p>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAktivasiManual">
                                            <i class="fas fa-plus mr-1"></i> Buka Sesi Manual Pertama
                                        </button>
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

<!-- Modal Aktivasi Sesi Manual -->
<div class="modal fade" id="modalAktivasiManual" tabindex="-1" role="dialog" aria-labelledby="modalAktivasiManualLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: 0;">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold text-dark" id="modalAktivasiManualLabel">
                    <i class="fas fa-user-clock text-success mr-2"></i>Buka Sesi WhatsApp Manual
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('reminder_wa/activate_session') ?>" method="post">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small">Nomor WhatsApp Penerima <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" placeholder="Contoh: 08123456789 atau 628123456789" required autofocus>
                        <small class="text-muted">Sesi 24 jam akan langsung aktif terhitung mulai saat ini sehingga sistem dapat mengirimkan pesan ke nomor ini.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success font-weight-bold">
                        <i class="fas fa-check mr-1"></i> Aktifkan Sesi Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ($.fn.DataTable && $('#table-sessions tbody tr').length > 0 && !$('#table-sessions tbody td').hasClass('py-5')) {
            $('#table-sessions').DataTable({
                "language": {
                    "search": "Cari Nomor / Nama:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                    "paginate": {
                        "next": "<i class='fas fa-chevron-right'></i>",
                        "previous": "<i class='fas fa-chevron-left'></i>"
                    }
                },
                "order": [[3, "desc"]],
                "pageLength": 25
            });
        }
    });
</script>
