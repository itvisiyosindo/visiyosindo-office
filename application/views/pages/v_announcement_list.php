<header class="page-header">
    <h2><i class="icons fas fa-bullhorn"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col-lg-10 col-xl-8 mx-auto">
        <section class="card shadow-sm mb-4" style="border: none; border-radius: 8px;">
            <header class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pt-4 px-4">
                <h4 class="card-title font-weight-bold text-dark mb-0">Daftar Pengumuman Karyawan</h4>
                <?php if (isAdmin() || isHrd() || isGa()): ?>
                    <a href="<?= base_url('announcement') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat Pengumuman Baru</a>
                <?php endif; ?>
            </header>
            <div class="card-body px-4 pb-4">
                <?php if (empty($announce)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-bullhorn fa-3x mb-3 text-light"></i>
                        <p class="mb-0">Belum ada pengumuman untuk saat ini.</p>
                    </div>
                <?php else: ?>
                    <div class="announcement-list">
                        <?php foreach ($announce as $value): ?>
                            <div class="announcement-item p-3 mb-3 border rounded bg-light" style="border-left: 4px solid #0088CC !important; border-radius: 6px;">
                                <div class="d-flex align-items-start" style="gap: 15px;">
                                    <div class="user-avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold shadow-sm" style="width: 40px; height: 40px; font-size: 16px; flex-shrink: 0; min-width: 40px;">
                                        <?= strtoupper(substr($value->nama, 0, 1)) ?>
                                    </div>
                                    <div class="w-100 pl-2">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px; margin-bottom: 8px;">
                                            <div>
                                                <h5 class="font-weight-bold text-dark mb-0" style="font-size: 14px;"><?= $value->nama ?></h5>
                                                <small class="text-muted"><i class="far fa-clock"></i> <?= date('d M Y', strtotime($value->data_created)) ?></small>
                                            </div>
                                            <?php if ($value->lampiran != ""): ?>
                                                <div class="d-flex align-items-center" style="gap: 5px;">
                                                    <a href="<?= $value->lampiran ?>" target="_blank" class="btn btn-info btn-xs"><i class="fas fa-external-link-alt"></i> Lihat Lampiran</a>
                                                    <button type="button" class="btn btn-warning btn-xs btn-preview-gdrive" data-url="<?= $value->lampiran ?>"><i class="fas fa-eye"></i> Preview</button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="announcement-message text-dark" style="font-size: 13.5px; line-height: 1.6; white-space: pre-wrap; font-family: 'Poppins', sans-serif;">
                                            <?= htmlspecialchars($value->message) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<!-- Modal Preview Google Drive -->
<div class="modal fade" id="modalPreviewGDrive" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 85%;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center" style="display: flex !important; justify-content: space-between !important; align-items: center !important; width: 100%;">
                <h5 class="modal-title" style="margin: 0;"><i class="fas fa-eye"></i> Preview Lampiran</h5>
                <div class="d-flex align-items-center" style="gap: 15px; display: flex !important; align-items: center !important;">
                    <a href="" id="btnOpenGDriveTab" target="_blank" class="btn btn-warning btn-xs text-dark" style="font-weight: 600;"><i class="fas fa-external-link-alt"></i> Buka di Tab Baru</a>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="margin: 0; padding: 0; opacity: 0.8;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body p-0" style="height: 75vh; position: relative;">
                <div class="alert alert-info py-2 px-3 m-0 rounded-0" style="font-size: 12px; border: none; border-bottom: 1px solid #bce8f1; background-color: #d9edf7; color: #31708f; margin: 0 !important; border-radius: 0 !important;">
                    <i class="fas fa-info-circle"></i> <strong>Tips:</strong> Jika file tidak muncul (masalah hak akses / akun), silakan klik tombol <strong>Buka di Tab Baru</strong> untuk melihat file secara langsung.
                </div>
                <iframe id="iframeGDrivePreview" src="" style="width: 100%; height: calc(100% - 38px); border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    function initGDrivePreview() {
        $(document).on('click', '.btn-preview-gdrive', function() {
            var rawUrl = $(this).data('url');
            var previewUrl = rawUrl;

            // Set the href for fallback button
            $('#btnOpenGDriveTab').attr('href', rawUrl);

            if (rawUrl.includes('drive.google.com')) {
                // If it's a folder link
                if (rawUrl.includes('/drive/folders/')) {
                    var folderMatch = rawUrl.match(/\/folders\/([a-zA-Z0-9_-]+)/);
                    if (folderMatch && folderMatch[1]) {
                        previewUrl = "https://drive.google.com/embeddedfolderview?id=" + folderMatch[1] + "#grid";
                    }
                } else {
                    // File link
                    var match = rawUrl.match(/\/file\/d\/([a-zA-Z0-9_-]+)/);
                    if (match && match[1]) {
                        previewUrl = "https://drive.google.com/file/d/" + match[1] + "/preview";
                    } else {
                        var matchId = rawUrl.match(/[?&]id=([a-zA-Z0-9_-]+)/);
                        if (matchId && matchId[1]) {
                            previewUrl = "https://drive.google.com/file/d/" + matchId[1] + "/preview";
                        }
                    }
                }
            }

            $('#iframeGDrivePreview').attr('src', previewUrl);
            $('#modalPreviewGDrive').modal('show');
        });

        $('#modalPreviewGDrive').on('hidden.bs.modal', function() {
            $('#iframeGDrivePreview').attr('src', '');
            $('#btnOpenGDriveTab').attr('href', '');
        });
    }

    if (typeof jQuery !== 'undefined') {
        initGDrivePreview();
    } else {
        window.addEventListener('load', initGDrivePreview);
    }
</script>