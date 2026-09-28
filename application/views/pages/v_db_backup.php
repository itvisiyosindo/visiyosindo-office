<div class="row">
    <!-- PANEL KIRI: STATUS & DETAIL KONEKSI -->
    <div class="col-md-5 mb-4">
        <section class="card shadow-sm border h-100" style="border-radius: 12px; overflow: hidden; border-color: #e5e7eb;">
            <header class="card-header bg-white py-3 px-4 border-bottom">
                <h2 class="card-title font-weight-bold text-dark m-0" style="font-size: 1.1rem;">
                    <i class="fas fa-network-wired text-primary mr-2"></i> Status & Koneksi Database
                </h2>
            </header>
            <div class="card-body px-4 py-3">
                <table class="table table-striped table-borderless small mb-3">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold text-muted" style="width: 40%">Nama Database</td>
                            <td class="text-dark font-weight-bold">: <?= htmlspecialchars($db_name) ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-muted">Jumlah Tabel</td>
                            <td class="text-dark font-weight-bold">: <?= $tables_count ?> Tabel</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-muted">Ukuran Data</td>
                            <td class="text-dark font-weight-bold">: 
                                <?php 
                                if ($db_size >= 1048576) {
                                    echo number_format($db_size / 1048576, 2) . ' MB';
                                } else {
                                    echo number_format($db_size / 1024, 2) . ' KB';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-muted">Koneksi Cloud</td>
                            <td class="text-success font-weight-bold">: 
                                <span class="badge badge-success" style="font-size: 0.75rem;"><i class="fab fa-google-drive"></i> OAuth2 Terhubung</span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="alert alert-info py-2 px-3 small border mb-3" style="border-radius: 8px;">
                    <i class="fas fa-info-circle mr-1"></i> Data riwayat di bawah diambil secara otomatis dari Folder ID Google Drive yang diatur di panel kanan.
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-info font-weight-bold mr-2 shadow-sm" id="btn-test-connection" style="border-radius: 8px; padding: 8px 16px;">
                        <i class="fas fa-plug mr-1"></i> Test Koneksi
                    </button>
                    <button class="btn btn-success font-weight-bold shadow-sm" id="btn-backup-now" style="border-radius: 8px; padding: 8px 16px;">
                        <i class="fas fa-cloud-upload-alt mr-1"></i> Cadangkan Sekarang
                    </button>
                </div>
            </div>
        </section>
    </div>

    <!-- PANEL KANAN: PENGATURAN & SINKRONISASI -->
    <div class="col-md-7 mb-4">
        <section class="card shadow-sm border h-100" style="border-radius: 12px; overflow: hidden; border-color: #e5e7eb;">
            <header class="card-header bg-white py-3 px-4 border-bottom">
                <h2 class="card-title font-weight-bold text-dark m-0" style="font-size: 1.1rem;">
                    <i class="fas fa-sliders-h text-primary mr-2"></i> Pengaturan Cloud & Penjadwalan
                </h2>
            </header>
            <div class="card-body px-4 py-3">
                <form id="form-backup-config">
                    <!-- Google Drive Folder ID -->
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark mb-1 small" for="gdrive_folder_id">Folder ID Google Drive <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light"><i class="fab fa-google-drive text-primary"></i></span>
                            </div>
                            <input type="text" class="form-control" id="gdrive_folder_id" name="gdrive_folder_id" value="<?= htmlspecialchars($gdrive_folder_id) ?>" placeholder="Masukkan Folder ID Google Drive" required>
                        </div>
                        <small class="text-muted">ID folder diambil dari bagian akhir tautan folder Google Drive Anda.</small>
                    </div>

                    <!-- Cronjob Schedule -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark mb-1 small" for="cron_enabled">Backup Otomatis (Cronjob)</label>
                                <select class="form-control" id="cron_enabled" name="cron_enabled">
                                    <option value="0" <?= !$cron_enabled ? 'selected' : '' ?>>Nonaktif</option>
                                    <option value="1" <?= $cron_enabled ? 'selected' : '' ?>>Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark mb-1 small" for="cron_schedule">Interval Penjadwalan</label>
                                <select class="form-control" id="cron_schedule" name="cron_schedule" <?= !$cron_enabled ? 'disabled' : '' ?>>
                                    <option value="daily" <?= $cron_schedule === 'daily' ? 'selected' : '' ?>>Harian (Sekali Sehari)</option>
                                    <option value="weekly" <?= $cron_schedule === 'weekly' ? 'selected' : '' ?>>Mingguan (Sekali Seminggu)</option>
                                    <option value="monthly" <?= $cron_schedule === 'monthly' ? 'selected' : '' ?>>Bulanan (Sekali Sebulan)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Cron Syntax Readonly -->
                    <div class="form-group mb-3" id="cron-command-section" style="<?= !$cron_enabled ? 'display: none;' : '' ?>">
                        <label class="font-weight-bold text-dark mb-1 small">Perintah Web-Trigger (Cronjob URL)</label>
                        <div class="input-group">
                            <input type="text" class="form-control bg-light text-muted small" id="cron_url_val" value="curl -s <?= base_url('db_backup/cron_backup/' . $cron_token) ?>" readonly style="font-size: 0.8rem;">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="btn-copy-cron" title="Salin Perintah">
                                    <i class="far fa-copy"></i> Salin
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">Masukkan perintah curl di atas ke dalam Crontab CPanel/Server Anda sesuai interval terpilih.</small>
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-warning font-weight-bold shadow-sm" id="btn-save-config" style="border-radius: 8px; padding: 8px 20px;">
                            <i class="fas fa-save mr-1"></i> Simpan Konfigurasi
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <!-- PANEL BAWAH: RIWAYAT BACKUP -->
    <div class="col-md-12">
        <section class="card shadow-sm border" style="border-radius: 12px; overflow: hidden; border-color: #e5e7eb;">
            <header class="card-header bg-white py-3 px-4 border-bottom">
                <h2 class="card-title font-weight-bold text-dark m-0" style="font-size: 1.1rem;">
                    <i class="fas fa-history text-primary mr-2"></i> Riwayat Pencadangan Google Drive
                </h2>
            </header>
            <div class="card-body p-0">
                <?php if ($gdrive_error): ?>
                    <div class="alert alert-danger m-4 d-flex align-items-center" style="border-radius: 8px;">
                        <i class="fas fa-exclamation-triangle fa-2x mr-3 text-danger"></i>
                        <div>
                            <strong>Error Google Drive API:</strong><br>
                            <?= htmlspecialchars($gdrive_error) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0" style="vertical-align: middle;">
                        <thead class="thead-light">
                            <tr>
                                <th class="py-3 px-4" style="width: 5%">No</th>
                                <th class="py-3 px-4" style="width: 45%">Nama Berkas Backup (.zip)</th>
                                <th class="py-3 px-4" style="width: 15%">Ukuran File</th>
                                <th class="py-3 px-4" style="width: 20%">Tanggal Pencadangan</th>
                                <th class="py-3 px-4 text-center" style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($backups)): ?>
                                <?php $no = 1; foreach ($backups as $b): ?>
                                    <tr>
                                        <td class="py-3 px-4 text-center"><?= $no++ ?></td>
                                        <td class="py-3 px-4 font-weight-bold text-dark">
                                            <i class="far fa-file-archive text-warning mr-2" style="font-size: 1.1rem;"></i>
                                            <?= htmlspecialchars($b->name) ?>
                                        </td>
                                        <td class="py-3 px-4 text-muted">
                                            <?php 
                                            $size = (float)$b->size;
                                            if ($size >= 1048576) {
                                                echo number_format($size / 1048576, 2) . ' MB';
                                            } else {
                                                echo number_format($size / 1024, 2) . ' KB';
                                            }
                                            ?>
                                        </td>
                                        <td class="py-3 px-4 text-muted">
                                            <?= date('d M Y - H:i:s', strtotime($b->createdTime)) ?>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <a href="<?= base_url('db_backup/download/' . $b->id) ?>" 
                                               class="btn btn-sm btn-success mr-1" 
                                               title="Unduh File Backup"
                                               style="border-radius: 6px;">
                                                <i class="fas fa-download"></i> Unduh
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger btn-delete-backup" 
                                                    data-id="<?= $b->id ?>" 
                                                    data-name="<?= htmlspecialchars($b->name) ?>"
                                                    title="Hapus File Backup"
                                                    style="border-radius: 6px;">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="fas fa-box-open fa-3x mb-3 text-muted" style="opacity: 0.5;"></i>
                                        <p class="mb-0">Tidak ada berkas cadangan database di folder Google Drive ini.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<script type="text/javascript">
(function waitForJQuery() {
    if (typeof jQuery !== 'undefined') {
        initDbBackup();
    } else {
        setTimeout(waitForJQuery, 100);
    }
})();

function initDbBackup() {
    var $ = jQuery;
    var baseUrl = '<?= base_url() ?>';

    $(document).ready(function() {
        // Toggle Cron Schedule select on status change
        $('#cron_enabled').change(function() {
            var isEnabled = $(this).val() === '1';
            $('#cron_schedule').prop('disabled', !isEnabled);
            if (isEnabled) {
                $('#cron-command-section').slideDown();
            } else {
                $('#cron-command-section').slideUp();
            }
        });

        // Copy Cron Command to clipboard
        $('#btn-copy-cron').click(function() {
            var copyText = document.getElementById("cron_url_val");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            
            Swal.fire({
                icon: 'success',
                title: 'Disalin!',
                text: 'Perintah cronjob berhasil disalin ke clipboard.',
                timer: 1500,
                showConfirmButton: false
            });
        });

        // Save Config Trigger
        $('#btn-save-config').click(function() {
            var folderId = $('#gdrive_folder_id').val().trim();
            if (!folderId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Form Belum Lengkap',
                    text: 'Folder ID Google Drive tidak boleh kosong.'
                });
                return;
            }

            Swal.fire({
                title: 'Menyimpan...',
                text: 'Mohon tunggu sebentar...',
                allowOutsideClick: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: baseUrl + 'db_backup/save_config',
                type: 'POST',
                dataType: 'json',
                data: $('#form-backup-config').serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'System Error',
                        text: 'Gagal menghubungi server: ' + error
                    });
                }
            });
        });

        // Test Connection Trigger
        $('#btn-test-connection').click(function() {
            var folderId = $('#gdrive_folder_id').val().trim();
            if (!folderId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Form Belum Lengkap',
                    text: 'Folder ID Google Drive tidak boleh kosong untuk melakukan uji koneksi.'
                });
                return;
            }

            Swal.fire({
                title: 'Menguji Koneksi...',
                text: 'Menghubungi API Google Drive dan memverifikasi Folder ID...',
                allowOutsideClick: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: baseUrl + 'db_backup/test_connection',
                type: 'POST',
                dataType: 'json',
                data: { gdrive_folder_id: folderId },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Koneksi Sukses!',
                            text: response.message
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Koneksi Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'System Error',
                        text: 'Gagal menghubungi server: ' + error
                    });
                }
            });
        });

        // Trigger Manual Backup
        $('#btn-backup-now').click(function() {
            Swal.fire({
                title: 'Konfirmasi Pencadangan',
                text: 'Apakah Anda ingin mencadangkan database dan mengunggahnya ke Google Drive sekarang?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0284c7',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-cloud-upload-alt mr-1"></i> Ya, Cadangkan!',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    performBackup();
                }
            });
        });

        // Delete Backup Trigger
        $('.btn-delete-backup').click(function() {
            var fileId = $(this).data('id');
            var fileName = $(this).data('name');

            Swal.fire({
                title: 'Konfirmasi Penghapusan',
                html: 'Apakah Anda yakin ingin menghapus berkas backup:<br><strong class="text-danger">' + fileName + '</strong> dari Google Drive?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    deleteBackup(fileId);
                }
            });
        });
    });

    function performBackup() {
        Swal.fire({
            title: 'Proses Pencadangan...',
            html: 'Menghubungkan ke database, memproses kompresi ZIP, dan mengunggah berkas ke Google Drive. Mohon tunggu...',
            allowOutsideClick: false,
            didOpen: function() {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: baseUrl + 'db_backup/create_backup',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Cadangkan',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Gagal menghubungi server: ' + error
                });
            }
        });
    }

    function deleteBackup(fileId) {
        Swal.fire({
            title: 'Menghapus Berkas...',
            text: 'Menghapus berkas dari Google Drive. Mohon tunggu...',
            allowOutsideClick: false,
            didOpen: function() {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: baseUrl + 'db_backup/delete/' + fileId,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Hapus',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Gagal menghubungi server: ' + error
                });
            }
        });
    }
}
</script>
