<header class="page-header">
    <h2><i class="fas fa-pencil-alt mr-2"></i><?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('knowledge/show/knowledge') ?>">Product Knowledge</a></li>
            <li><span>Ujian Online</span></li>
        </ol>
    </div>
</header>

<style>
    .cbt-container {
        max-width: 900px;
        margin: 0 auto;
    }
    .cbt-timer-box {
        position: sticky;
        top: 70px;
        z-index: 999;
        background: #0f172a;
        color: #ffffff;
        border-radius: 12px;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        margin-bottom: 20px;
    }
    .cbt-question-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
        transition: border-color 0.2s ease;
    }
    .cbt-question-card.answered {
        border-color: #86efac;
        background: #fcfffd;
    }
    .cbt-option-label {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        cursor: pointer;
        margin-bottom: 10px;
        transition: all 0.15s ease;
        background: #ffffff;
        user-select: none;
    }
    .cbt-option-label:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }
    .cbt-option-label.selected {
        background: #ecfdf5;
        border-color: #10b981;
        color: #065f46;
        font-weight: 600;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.15);
    }
    .cbt-option-radio {
        width: 20px;
        height: 20px;
        accent-color: #10b981;
        margin-right: 14px;
        cursor: pointer;
    }
    .cbt-bottom-bar {
        position: sticky;
        bottom: 20px;
        z-index: 999;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="cbt-container">
    <!-- TIMER & PROGRESS BAR -->
    <div class="cbt-timer-box">
        <div class="d-flex align-items-center">
            <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; font-size: 16px; margin-right: 12px; color: #38bdf8;">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <small class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Sisa Waktu Ujian</small>
                <div id="cbtTimerDisplay" style="font-size: 20px; font-weight: 800; color: #38bdf8; font-family: monospace;">
                    <?= sprintf('%02d:00', (int)$kuis->durasi_menit) ?>
                </div>
            </div>
        </div>
        <div class="text-right">
            <small class="text-muted d-block" style="font-size: 11px;">Progres Jawaban</small>
            <span style="font-size: 15px; font-weight: 700; color: #4ade80;">
                <span id="txtTerjawab">0</span> / <?= count($soal_list) ?> Soal
            </span>
        </div>
    </div>

    <!-- KUIS HEADER BANNER -->
    <div class="card mb-4 shadow-sm" style="border-radius: 12px; border: 1px solid #e2e8f0; background: #ffffff;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <span class="badge badge-success px-2 py-1 mb-2 font-weight-bold" style="font-size: 11px;">
                        <i class="fas fa-shield-alt mr-1"></i> Ujian Resmi Kantor
                    </span>
                    <h3 class="font-weight-bold text-dark mb-1"><?= htmlspecialchars($kuis->judul_kuis) ?></h3>
                    <p class="text-muted mb-0 small">
                        <?= !empty($kuis->deskripsi) ? htmlspecialchars($kuis->deskripsi) : 'Pilihlah salah satu jawaban yang paling tepat. Nilai akhir akan langsung tersimpan ke sistem.' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <form id="formCbtUjian">
        <input type="hidden" name="id_kuis" value="<?= $kuis->id_kuis ?>">

        <?php $nomor = 1; foreach ($soal_list as $s): ?>
            <div class="cbt-question-card" id="card_soal_<?= $s->id_soal ?>">
                <div class="d-flex align-items-start mb-3">
                    <span class="badge badge-primary px-3 py-2 mr-3 font-weight-bold" style="font-size: 13px; border-radius: 8px;">
                        No. <?= $nomor++ ?>
                    </span>
                    <div class="font-weight-bold text-dark" style="font-size: 15px; line-height: 1.5; white-space: pre-line;">
                        <?= htmlspecialchars($s->pertanyaan) ?>
                    </div>
                </div>

                <div class="options-container pl-md-4">
                    <label class="cbt-option-label" data-soal="<?= $s->id_soal ?>">
                        <input type="radio" name="jawaban[<?= $s->id_soal ?>]" value="A" class="cbt-option-radio">
                        <span class="badge badge-light border mr-2 font-weight-bold">A</span>
                        <span style="font-size: 14px;"><?= htmlspecialchars($s->pilihan_a) ?></span>
                    </label>

                    <label class="cbt-option-label" data-soal="<?= $s->id_soal ?>">
                        <input type="radio" name="jawaban[<?= $s->id_soal ?>]" value="B" class="cbt-option-radio">
                        <span class="badge badge-light border mr-2 font-weight-bold">B</span>
                        <span style="font-size: 14px;"><?= htmlspecialchars($s->pilihan_b) ?></span>
                    </label>

                    <label class="cbt-option-label" data-soal="<?= $s->id_soal ?>">
                        <input type="radio" name="jawaban[<?= $s->id_soal ?>]" value="C" class="cbt-option-radio">
                        <span class="badge badge-light border mr-2 font-weight-bold">C</span>
                        <span style="font-size: 14px;"><?= htmlspecialchars($s->pilihan_c) ?></span>
                    </label>

                    <label class="cbt-option-label" data-soal="<?= $s->id_soal ?>">
                        <input type="radio" name="jawaban[<?= $s->id_soal ?>]" value="D" class="cbt-option-radio">
                        <span class="badge badge-light border mr-2 font-weight-bold">D</span>
                        <span style="font-size: 14px;"><?= htmlspecialchars($s->pilihan_d) ?></span>
                    </label>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- BOTTOM STICKY SUBMIT BAR -->
        <div class="cbt-bottom-bar">
            <div>
                <span class="small text-muted font-weight-bold d-block">Sudah Menjawab:</span>
                <strong class="text-dark"><span id="txtTerjawabBtm">0</span> dari <?= count($soal_list) ?> Soal</strong>
            </div>
            <button type="button" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm" id="btnSubmitUjian" style="border-radius: 8px; font-size: 14px;">
                <i class="fas fa-paper-plane mr-1"></i> Kumpulkan & Selesaikan Ujian
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var baseUrl = '<?= base_url() ?>';
    var totalSoal = <?= count($soal_list) ?>;
    var durasiMenit = <?= (int)$kuis->durasi_menit ?>;
    var totalDetik = durasiMenit * 60;

    // Timer Countdown
    var timerInterval = setInterval(function() {
        totalDetik--;
        if (totalDetik <= 0) {
            clearInterval(timerInterval);
            $('#cbtTimerDisplay').text('00:00').css('color', '#ef4444');
            alert('Waktu ujian telah habis! Sistem akan mengumpulkan jawaban Anda secara otomatis.');
            lakukanSubmit();
            return;
        }

        var m = Math.floor(totalDetik / 60);
        var s = totalDetik % 60;
        var mStr = (m < 10 ? '0' : '') + m;
        var sStr = (s < 10 ? '0' : '') + s;
        $('#cbtTimerDisplay').text(mStr + ':' + sStr);

        if (totalDetik < 300) { // < 5 menit warna merah
            $('#cbtTimerDisplay').css('color', '#f87171');
        }
    }, 1000);

    // Update Counter Jawaban Terpilih
    function updateProgress() {
        var answeredCount = 0;
        $('input.cbt-option-radio:checked').each(function() {
            answeredCount++;
        });
        $('#txtTerjawab').text(answeredCount);
        $('#txtTerjawabBtm').text(answeredCount);
    }

    // Radio Option Click Animation
    $('.cbt-option-label').on('click', function() {
        var $radio = $(this).find('input[type="radio"]');
        var idSoal = $(this).data('soal');
        
        $('.cbt-option-label[data-soal="' + idSoal + '"]').removeClass('selected');
        $(this).addClass('selected');
        $('#card_soal_' + idSoal).addClass('answered');
        
        updateProgress();
    });

    // Tombol Submit
    $('#btnSubmitUjian').on('click', function() {
        var answeredCount = $('input.cbt-option-radio:checked').length;
        var confirmMsg = 'Kumpulkan jawaban sekarang?\n\n' +
            '- Terjawab: ' + answeredCount + ' dari ' + totalSoal + ' soal\n' +
            (answeredCount < totalSoal ? '⚠️ PERINGATAN: Masih ada ' + (totalSoal - answeredCount) + ' soal yang belum Anda jawab!\n' : '') +
            '\nSetelah dikumpulkan, nilai kuis akan langsung dihitung dan tidak dapat diulang.';

        if (!confirm(confirmMsg)) return;

        lakukanSubmit();
    });

    function lakukanSubmit() {
        clearInterval(timerInterval);
        var $btn = $('#btnSubmitUjian');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim & Menilai...');

        $.ajax({
            url: baseUrl + 'knowledge/ajax_submit_kuis',
            type: 'POST',
            data: $('#formCbtUjian').serialize(),
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success') {
                    window.location.href = baseUrl + 'knowledge/show/hasil/<?= $kuis->id_kuis ?>';
                } else {
                    $btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-1"></i> Kumpulkan & Selesaikan Ujian');
                    alert(res && res.msg ? res.msg : 'Gagal mengirim jawaban.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-1"></i> Kumpulkan & Selesaikan Ujian');
                alert('Terjadi kesalahan jaringan.');
            }
        });
    }
});
</script>
