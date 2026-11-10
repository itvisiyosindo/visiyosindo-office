<header class="page-header">
    <h2><i class="fas fa-award mr-2"></i><?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('knowledge/show/knowledge') ?>">Product Knowledge</a></li>
            <li><span>Hasil Ujian</span></li>
        </ol>
    </div>
</header>

<?php
$skor = (float)$peserta->skor_akhir;
if ($skor >= 85) {
    $grade = 'A';
    $gradeText = 'Sangat Baik / Istimewa';
    $gradeColor = '#059669';
    $gradeBg = '#ecfdf5';
    $gradeBorder = '#a7f3d0';
    $gradeIcon = 'fa-trophy';
} elseif ($skor >= 75) {
    $grade = 'B';
    $gradeText = 'Baik / Memuaskan';
    $gradeColor = '#0284c7';
    $gradeBg = '#f0f9ff';
    $gradeBorder = '#bae6fd';
    $gradeIcon = 'fa-medal';
} elseif ($skor >= 65) {
    $grade = 'C';
    $gradeText = 'Cukup / Perlu Peningkatan';
    $gradeColor = '#d97706';
    $gradeBg = '#fffbeb';
    $gradeBorder = '#fde68a';
    $gradeIcon = 'fa-star-half-alt';
} else {
    $grade = 'D';
    $gradeText = 'Kurang / Perlu Belajar Ulang';
    $gradeColor = '#dc2626';
    $gradeBg = '#fef2f2';
    $gradeBorder = '#fecaca';
    $gradeIcon = 'fa-exclamation-triangle';
}
?>

<div class="container" style="max-width: 800px; margin: 0 auto;">
    <div class="card shadow-lg mb-4" style="border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; background: #ffffff;">
        <!-- RESULT HEADER BANNER -->
        <div class="text-center p-5" style="background: <?= $gradeBg ?>; border-bottom: 1.5px solid <?= $gradeBorder ?>;">
            <div style="width: 72px; height: 72px; border-radius: 50%; background: #ffffff; border: 2px solid <?= $gradeBorder ?>; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; color: <?= $gradeColor ?>; margin-bottom: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                <i class="fas <?= $gradeIcon ?>"></i>
            </div>
            <h4 class="font-weight-bold text-dark mb-1">Ujian Kuis Selesai!</h4>
            <p class="text-muted small mb-3"><?= htmlspecialchars($kuis->judul_kuis) ?></p>

            <div style="font-size: 54px; font-weight: 900; color: <?= $gradeColor ?>; line-height: 1;">
                <?= $skor ?> <span style="font-size: 24px; font-weight: 600; color: #64748b;">/ 100</span>
            </div>
            <div class="mt-2">
                <span class="badge px-3 py-1 font-weight-bold" style="font-size: 14px; background: #ffffff; color: <?= $gradeColor ?>; border: 1.5px solid <?= $gradeBorder ?>;">
                    Grade <?= $grade ?> — <?= $gradeText ?>
                </span>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- STATS ROW -->
            <div class="row text-center mb-4">
                <div class="col-4">
                    <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                        <small class="text-muted d-block font-weight-bold text-uppercase">Jawaban Benar</small>
                        <span style="font-size: 24px; font-weight: 800; color: #16a34a;">
                            <i class="fas fa-check-circle mr-1"></i> <?= (int)$peserta->total_benar ?>
                        </span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                        <small class="text-muted d-block font-weight-bold text-uppercase">Jawaban Salah</small>
                        <span style="font-size: 24px; font-weight: 800; color: #dc2626;">
                            <i class="fas fa-times-circle mr-1"></i> <?= (int)$peserta->total_salah ?>
                        </span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                        <small class="text-muted d-block font-weight-bold text-uppercase">Durasi Ujian</small>
                        <span style="font-size: 24px; font-weight: 800; color: #0284c7;">
                            <i class="far fa-clock mr-1"></i> <?= round((int)$peserta->durasi_detik / 60) ?> Mnt
                        </span>
                    </div>
                </div>
            </div>

            <!-- SYNC KPI INFO ALERT -->
            <div class="alert alert-success d-flex align-items-center mb-4" style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 10px; color: #166534;">
                <i class="fas fa-sync-alt fa-2x mr-3 text-success"></i>
                <div class="small">
                    <strong>Tersinkronisasi Otomatis ke Leaderboard KPI:</strong><br>
                    Skor ujian Product Knowledge sebesar <strong><?= $skor ?></strong> telah berhasil tersimpan ke rekap penilaian Anda untuk <strong>Bulan <?= (int)$kuis->bulan ?> / Tahun <?= (int)$kuis->tahun ?></strong> dan menjadi parameter penilaian kinerja resmi.
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
                <a href="<?= base_url('knowledge/show/knowledge') ?>" class="btn btn-light border font-weight-bold px-4 py-2">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Menu Product Knowledge
                </a>
                <a href="<?= base_url('task_proyek') ?>" class="btn btn-success font-weight-bold px-4 py-2 shadow-sm">
                    <i class="fas fa-trophy mr-1"></i> Lihat Leaderboard KPI Saya
                </a>
            </div>
        </div>
    </div>
</div>
