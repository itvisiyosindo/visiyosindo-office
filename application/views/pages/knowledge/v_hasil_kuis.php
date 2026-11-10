<header class="page-header">
    <h2><i class="fas fa-award mr-2"></i><?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('knowledge/show/knowledge') ?>">Product Knowledge</a></li>
            <li><span>Hasil & Review Ujian</span></li>
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

$jawabanAudit = !empty($peserta->jawaban_json) ? json_decode($peserta->jawaban_json, true) : [];
?>

<style>
    .result-wrap {
        max-width: 860px;
        margin: 0 auto;
        padding: 0 4px;
    }
    .score-banner {
        border-radius: 16px;
        border: 1.5px solid <?= $gradeBorder ?>;
        background: #ffffff;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    .score-big {
        font-size: 54px;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -1px;
    }
    .stat-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 8px;
        transition: all 0.2s ease;
    }
    .stat-box:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    .stat-num {
        font-size: 24px;
        font-weight: 800;
        display: block;
        line-height: 1.2;
    }
    .stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 4px;
        display: block;
    }
    .review-q-card {
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        margin-bottom: 18px;
        transition: all 0.2s ease;
    }
    .review-opt-row {
        display: flex;
        align-items: center;
        padding: 11px 16px;
        margin-bottom: 9px;
        border-radius: 10px;
        font-size: 14px;
        line-height: 1.5;
        transition: all 0.15s ease;
    }
    .review-opt-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        white-space: nowrap;
        margin-left: auto;
    }

    @media (max-width: 768px) {
        .result-wrap {
            padding: 0;
        }
        .score-big {
            font-size: 40px;
        }
        .stat-box {
            padding: 10px 4px;
            border-radius: 10px;
        }
        .stat-num {
            font-size: 18px;
        }
        .stat-label {
            font-size: 9.5px;
        }
        .review-opt-row {
            flex-wrap: wrap;
            padding: 10px 12px;
            font-size: 13.5px;
        }
        .review-opt-badge {
            margin-left: 0;
            margin-top: 6px;
            width: 100%;
            text-align: left;
            display: inline-block;
        }
        .bottom-actions {
            flex-direction: column !important;
            gap: 10px !important;
        }
        .bottom-actions .btn {
            width: 100% !important;
            text-align: center;
        }
    }
</style>

<div class="result-wrap">
    <?php if (!empty($is_admin_view)): ?>
        <div class="alert alert-info border mb-3 d-flex align-items-center shadow-sm" style="border-radius: 12px;">
            <i class="fas fa-user-shield mr-3" style="font-size: 24px;"></i>
            <div>
                <strong>Mode Review HRD / Admin:</strong> Sedang memeriksa lembar jawaban milik 
                <strong><?= htmlspecialchars($peserta->nama) ?></strong> (<?= htmlspecialchars($peserta->nama_divisi ?: $peserta->jabatan) ?>)
            </div>
        </div>
    <?php endif; ?>

    <!-- RESULT SUMMARY CARD -->
    <div class="score-banner mb-4">
        <!-- RESULT HEADER BANNER -->
        <div class="text-center p-4 p-md-5" style="background: <?= $gradeBg ?>; border-bottom: 1.5px solid <?= $gradeBorder ?>;">
            <div style="width: 70px; height: 70px; border-radius: 50%; background: #ffffff; border: 2.5px solid <?= $gradeBorder ?>; display: inline-flex; align-items: center; justify-content: center; font-size: 30px; color: <?= $gradeColor ?>; margin-bottom: 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.06);">
                <i class="fas <?= $gradeIcon ?>"></i>
            </div>
            <h4 class="font-weight-bold text-dark mb-1">Hasil Ujian Kuis</h4>
            <p class="text-muted small mb-3"><?= htmlspecialchars($kuis->judul_kuis) ?></p>

            <div class="score-big" style="color: <?= $gradeColor ?>;">
                <?= $skor ?> <span style="font-size: 20px; font-weight: 600; color: #64748b;">/ 100</span>
            </div>
            <div class="mt-2">
                <span class="badge px-3 py-1 font-weight-bold shadow-sm" style="font-size: 13.5px; background: #ffffff; color: <?= $gradeColor ?>; border: 1.5px solid <?= $gradeBorder ?>; border-radius: 20px;">
                    Grade <?= $grade ?> &bull; <?= $gradeText ?>
                </span>
            </div>
        </div>

        <div class="card-body p-3 p-md-4">
            <!-- STATS ROW -->
            <div class="row text-center mb-3">
                <div class="col-4 px-1 px-md-3">
                    <div class="stat-box">
                        <span class="stat-label">Jawaban Benar</span>
                        <span class="stat-num" style="color: #16a34a;">
                            <i class="fas fa-check-circle mr-1"></i> <?= (int)$peserta->total_benar ?>
                        </span>
                    </div>
                </div>
                <div class="col-4 px-1 px-md-3">
                    <div class="stat-box">
                        <span class="stat-label">Jawaban Salah</span>
                        <span class="stat-num" style="color: #dc2626;">
                            <i class="fas fa-times-circle mr-1"></i> <?= (int)$peserta->total_salah ?>
                        </span>
                    </div>
                </div>
                <div class="col-4 px-1 px-md-3">
                    <div class="stat-box">
                        <span class="stat-label">Durasi Pengerjaan</span>
                        <span class="stat-num" style="color: #0284c7;">
                            <i class="far fa-clock mr-1"></i> <?= round((int)$peserta->durasi_detik / 60) ?> Mnt
                        </span>
                    </div>
                </div>
            </div>

            <!-- SYNC KPI INFO ALERT -->
            <div class="alert alert-success d-flex align-items-center mb-0 p-3" style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 12px; color: #166534;">
                <i class="fas fa-sync-alt fa-2x mr-3 text-success d-none d-sm-inline-block"></i>
                <div class="small" style="line-height: 1.5;">
                    <strong>Tersinkronisasi Otomatis ke Leaderboard KPI:</strong><br>
                    Skor ujian Product Knowledge sebesar <strong><?= $skor ?></strong> telah otomatis tersimpan ke rekap penilaian <strong>Bulan <?= (int)$kuis->bulan ?> / Tahun <?= (int)$kuis->tahun ?></strong> dan menjadi parameter penilaian mutu kinerja resmi.
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         SECTION REVIEW LEMBAR JAWABAN (DETAIL SOAL, JAWABAN PESERTA, & KUNCI)
         ========================================================================= -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 px-1">
        <div>
            <h5 class="font-weight-bold text-dark mb-1">
                <i class="fas fa-clipboard-check text-primary mr-2"></i> Review Lembar Jawaban & Pembahasan
            </h5>
            <small class="text-muted">Periksa jawaban pada setiap nomor soal. Kunci jawaban dan pembahasan ditampilkan untuk bahan evaluasi.</small>
        </div>
        <div class="d-flex align-items-center mt-2 mt-md-0" style="gap: 6px;">
            <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Benar: <?= (int)$peserta->total_benar ?></span>
            <span class="badge badge-danger px-2 py-1"><i class="fas fa-times mr-1"></i> Salah: <?= (int)$peserta->total_salah ?></span>
        </div>
    </div>

    <?php if (empty($soal_list)): ?>
        <div class="card p-4 text-center text-muted mb-4" style="border-radius: 12px;">
            Tidak ada rincian soal untuk kuis ini.
        </div>
    <?php else: ?>
        <?php $no = 1; foreach ($soal_list as $soal): 
            $idSoal = $soal->id_soal;
            $audit = isset($jawabanAudit[$idSoal]) ? $jawabanAudit[$idSoal] : null;
            $userAns = $audit && isset($audit['jawaban_user']) ? strtoupper(trim((string)$audit['jawaban_user'])) : '';
            $kunci = strtoupper(trim((string)$soal->kunci_jawaban));
            $isBenar = ($audit && !empty($audit['is_benar']));
            $isKosong = empty($userAns);

            // Warna Card Header
            if ($isBenar) {
                $hdrBg = '#f0fdf4';
                $hdrBorder = '#86efac';
                $statusBadge = '<span class="badge badge-success px-2 py-1" style="font-size:11px;"><i class="fas fa-check-circle mr-1"></i> Jawaban Benar</span>';
            } elseif ($isKosong) {
                $hdrBg = '#f8fafc';
                $hdrBorder = '#cbd5e1';
                $statusBadge = '<span class="badge badge-secondary px-2 py-1" style="font-size:11px;"><i class="fas fa-minus-circle mr-1"></i> Tidak Dijawab</span>';
            } else {
                $hdrBg = '#fef2f2';
                $hdrBorder = '#fca5a5';
                $statusBadge = '<span class="badge badge-danger px-2 py-1" style="font-size:11px;"><i class="fas fa-times-circle mr-1"></i> Jawaban Salah</span>';
            }
        ?>
            <div class="review-q-card" style="border: 1.5px solid <?= $hdrBorder ?>;">
                <div class="d-flex justify-content-between align-items-center py-2 px-3" style="background: <?= $hdrBg ?>; border-bottom: 1px solid <?= $hdrBorder ?>;">
                    <div class="font-weight-bold text-dark" style="font-size: 13.5px;">
                        <span class="mr-1 text-muted">Soal #<?= $no++ ?></span>
                    </div>
                    <div>
                        <?= $statusBadge ?>
                    </div>
                </div>

                <div class="p-3 p-md-4">
                    <!-- PERTANYAAN -->
                    <div class="mb-3 font-weight-bold text-dark" style="font-size: 14.5px; line-height: 1.6;">
                        <?= nl2br(htmlspecialchars($soal->pertanyaan)) ?>
                    </div>

                    <!-- PILIHAN JAWABAN A, B, C, D -->
                    <div class="mb-2">
                        <?php 
                        $opsi = [
                            'A' => $soal->pilihan_a,
                            'B' => $soal->pilihan_b,
                            'C' => $soal->pilihan_c,
                            'D' => $soal->pilihan_d,
                        ];
                        foreach ($opsi as $k => $teksOpsi): 
                            $isKunci = ($k === $kunci);
                            $isUser  = ($k === $userAns);

                            if ($isKunci && $isUser) {
                                // Jawaban peserta BENAR
                                $rowStyle = 'background: #ecfdf5; border: 2px solid #10b981; color: #065f46; font-weight: 600;';
                                $badgeTag = '<span class="badge badge-success review-opt-badge"><i class="fas fa-check-circle mr-1"></i> Pilihan Anda (Benar)</span>';
                                $iconLeft = '<i class="fas fa-check-circle text-success mr-2" style="font-size: 16px;"></i>';
                            } elseif ($isKunci && !$isUser) {
                                // KUNCI JAWABAN (Peserta salah pilih atau tidak jawab)
                                $rowStyle = 'background: #f0fdf4; border: 2px dashed #10b981; color: #065f46; font-weight: 600;';
                                $badgeTag = '<span class="badge badge-success review-opt-badge"><i class="fas fa-check mr-1"></i> Kunci Jawaban</span>';
                                $iconLeft = '<i class="fas fa-check text-success mr-2" style="font-size: 16px;"></i>';
                            } elseif ($isUser && !$isKunci) {
                                // Jawaban peserta SALAH
                                $rowStyle = 'background: #fef2f2; border: 2px solid #ef4444; color: #991b1b; font-weight: 600;';
                                $badgeTag = '<span class="badge badge-danger review-opt-badge"><i class="fas fa-times-circle mr-1"></i> Pilihan Anda (Salah)</span>';
                                $iconLeft = '<i class="fas fa-times-circle text-danger mr-2" style="font-size: 16px;"></i>';
                            } else {
                                // Opsi Netral
                                $rowStyle = 'background: #ffffff; border: 1px solid #e2e8f0; color: #334155;';
                                $badgeTag = '';
                                $iconLeft = '<span class="badge badge-light border text-muted mr-2" style="width: 22px; text-align: center; font-size: 11px;">' . $k . '</span>';
                            }
                        ?>
                            <div class="review-opt-row" style="<?= $rowStyle ?>">
                                <?= $iconLeft ?>
                                <span class="mr-2" style="flex: 1;"><?= htmlspecialchars($teksOpsi) ?></span>
                                <?= $badgeTag ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- PEMBAHASAN -->
                    <?php if (!empty($soal->pembahasan)): ?>
                        <div class="mt-3 p-3 rounded small" style="background: #f0f9ff; border-left: 4px solid #0284c7; color: #0369a1; border-radius: 8px;">
                            <strong><i class="fas fa-lightbulb text-warning mr-1"></i> Pembahasan / Penjelasan:</strong><br>
                            <?= nl2br(htmlspecialchars($soal->pembahasan)) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- BOTTOM ACTION BUTTONS -->
    <div class="card shadow-sm p-3 mb-4 bottom-actions d-flex flex-row justify-content-between align-items-center" style="border-radius: 12px; background: #ffffff; border: 1px solid #e2e8f0;">
        <a href="<?= base_url('knowledge/show/knowledge') ?>" class="btn btn-light border font-weight-bold px-4 py-2">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Menu Kuis
        </a>
        <a href="<?= base_url('task_proyek') ?>" class="btn btn-success font-weight-bold px-4 py-2 shadow-sm">
            <i class="fas fa-trophy mr-1"></i> Lihat Leaderboard KPI Saya
        </a>
    </div>
</div>
