<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Kepuasan Pelanggan | PT Visi Yosindo Medikal</title>
    <link rel="shortcut icon" href="<?= base_url('assets/img/favicon.png') ?>" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/js/sweetalert2/sweetalert2.min.css') ?>" />

    <style>
        :root {
            --primary: #1d4ed8;
            --primary-hover: #1e40af;
            --primary-light: #eff6ff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --star-gold: #f59e0b;
            --star-empty: #cbd5e1;
            --card-border: #e2e8f0;
            --bg-page: #f8fafc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-dark);
            min-height: 100vh;
            padding: 20px 10px 40px;
        }

        .survey-container {
            max-width: 680px;
            margin: 0 auto;
        }

        .survey-header-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px 20px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
            border: 1px solid var(--card-border);
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .survey-header-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #1d4ed8, #0ea5e9);
        }

        .company-logo {
            max-height: 52px;
            object-fit: contain;
            margin-bottom: 12px;
        }

        .survey-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .survey-subtitle {
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .customer-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary-light);
            color: var(--primary);
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 600;
            border: 1px solid #bfdbfe;
            text-align: left;
        }

        .question-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
            border: 1px solid var(--card-border);
            margin-bottom: 16px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .question-card:hover,
        .question-card.active {
            border-color: #93c5fd;
            box-shadow: 0 4px 15px rgba(29, 78, 216, 0.07);
        }

        .question-number {
            display: inline-block;
            width: 24px;
            height: 24px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            font-size: 12px;
            font-weight: 700;
            margin-right: 8px;
        }

        .question-text {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 14px;
            display: flex;
            align-items: flex-start;
        }

        .star-rating-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .star-group {
            display: inline-flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 12px;
        }

        .star-group input {
            display: none;
        }

        .star-group label {
            font-size: 34px;
            color: var(--star-empty);
            cursor: pointer;
            margin: 0;
            transition: color 0.15s, transform 0.15s;
        }

        /* Hover and Checked state */
        .star-group label:hover,
        .star-group label:hover ~ label,
        .star-group input:checked ~ label {
            color: var(--star-gold);
            transform: scale(1.1);
        }

        .star-label-desc {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            min-height: 18px;
        }

        .textarea-box {
            border: 1.5px solid var(--card-border);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text-dark);
            width: 100%;
            resize: vertical;
            min-height: 110px;
            transition: border-color 0.2s;
        }

        .textarea-box:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12);
        }

        .btn-submit-survey {
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 16px;
            padding: 14px 28px;
            border-radius: 12px;
            border: none;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(29, 78, 216, 0.3);
            transition: all 0.2s;
            margin-top: 10px;
        }

        .btn-submit-survey:hover:not(:disabled) {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
            box-shadow: 0 6px 20px rgba(29, 78, 216, 0.4);
            transform: translateY(-2px);
        }

        .btn-submit-survey:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .survey-footer {
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 24px;
        }
    </style>
</head>

<body>
    <div class="survey-container">
        <!-- Header Card -->
        <div class="survey-header-card">
            <img src="<?= base_url('assets/img/logovym2023.png') ?>" alt="Logo Visi Yosindo Medikal" class="company-logo" onerror="this.src='<?= base_url('assets/img/logo_visi.jpg') ?>'">
            <h1 class="survey-title">Survey Kepuasan Pelanggan</h1>
            <p class="survey-subtitle">
                Bapak/Ibu yang terhormat, mohon luangkan waktu sejenak (1–2 menit) untuk mengisi penilaian layanan kami. Masukan dari Bapak/Ibu sangat berharga bagi peningkatan mutu layanan kami.
            </p>
            <div class="customer-pill">
                <i class="fas fa-hospital-alt"></i>
                <span><?= htmlspecialchars($survey->nama_pelanggan) ?><?= !empty($survey->nama_pic) ? ' &bull; Yth. ' . htmlspecialchars($survey->nama_pic) : '' ?></span>
            </div>
        </div>

        <!-- Form Survey -->
        <form id="formSurveyCustomer">
            <input type="hidden" name="token" value="<?= htmlspecialchars($survey->token) ?>">

            <!-- 1. Kualitas Pelayanan -->
            <div class="question-card" id="card_pelayanan">
                <div class="question-text">
                    <span class="question-number">1</span>
                    <span>Kualitas pelayanan secara keseluruhan</span>
                </div>
                <div class="star-rating-wrap">
                    <div class="star-group" data-target="desc_pelayanan">
                        <input type="radio" id="star1_5" name="bintang_pelayanan" value="5" required><label for="star1_5" title="Sangat Baik">&#9733;</label>
                        <input type="radio" id="star1_4" name="bintang_pelayanan" value="4"><label for="star1_4" title="Baik">&#9733;</label>
                        <input type="radio" id="star1_3" name="bintang_pelayanan" value="3"><label for="star1_3" title="Cukup">&#9733;</label>
                        <input type="radio" id="star1_2" name="bintang_pelayanan" value="2"><label for="star1_2" title="Kurang">&#9733;</label>
                        <input type="radio" id="star1_1" name="bintang_pelayanan" value="1"><label for="star1_1" title="Sangat Kurang">&#9733;</label>
                    </div>
                    <div class="star-label-desc" id="desc_pelayanan">Pilih rating bintang 1 s.d. 5</div>
                </div>
            </div>

            <!-- 2. Kecepatan Respons -->
            <div class="question-card" id="card_kecepatan">
                <div class="question-text">
                    <span class="question-number">2</span>
                    <span>Kecepatan respons terhadap kebutuhan pelanggan</span>
                </div>
                <div class="star-rating-wrap">
                    <div class="star-group" data-target="desc_kecepatan">
                        <input type="radio" id="star2_5" name="bintang_kecepatan_respons" value="5" required><label for="star2_5" title="Sangat Baik">&#9733;</label>
                        <input type="radio" id="star2_4" name="bintang_kecepatan_respons" value="4"><label for="star2_4" title="Baik">&#9733;</label>
                        <input type="radio" id="star2_3" name="bintang_kecepatan_respons" value="3"><label for="star2_3" title="Cukup">&#9733;</label>
                        <input type="radio" id="star2_2" name="bintang_kecepatan_respons" value="2"><label for="star2_2" title="Kurang">&#9733;</label>
                        <input type="radio" id="star2_1" name="bintang_kecepatan_respons" value="1"><label for="star2_1" title="Sangat Kurang">&#9733;</label>
                    </div>
                    <div class="star-label-desc" id="desc_kecepatan">Pilih rating bintang 1 s.d. 5</div>
                </div>
            </div>

            <!-- 3. Keramahan dan Profesionalisme -->
            <div class="question-card" id="card_keramahan">
                <div class="question-text">
                    <span class="question-number">3</span>
                    <span>Keramahan dan profesionalisme tim</span>
                </div>
                <div class="star-rating-wrap">
                    <div class="star-group" data-target="desc_keramahan">
                        <input type="radio" id="star3_5" name="bintang_keramahan" value="5" required><label for="star3_5" title="Sangat Baik">&#9733;</label>
                        <input type="radio" id="star3_4" name="bintang_keramahan" value="4"><label for="star3_4" title="Baik">&#9733;</label>
                        <input type="radio" id="star3_3" name="bintang_keramahan" value="3"><label for="star3_3" title="Cukup">&#9733;</label>
                        <input type="radio" id="star3_2" name="bintang_keramahan" value="2"><label for="star3_2" title="Kurang">&#9733;</label>
                        <input type="radio" id="star3_1" name="bintang_keramahan" value="1"><label for="star3_1" title="Sangat Kurang">&#9733;</label>
                    </div>
                    <div class="star-label-desc" id="desc_keramahan">Pilih rating bintang 1 s.d. 5</div>
                </div>
            </div>

            <!-- 4. Kecepatan Penanganan Keluhan -->
            <div class="question-card" id="card_keluhan">
                <div class="question-text">
                    <span class="question-number">4</span>
                    <span>Kecepatan penanganan keluhan</span>
                </div>
                <div class="star-rating-wrap">
                    <div class="star-group" data-target="desc_keluhan">
                        <input type="radio" id="star4_5" name="bintang_penanganan_keluhan" value="5" required><label for="star4_5" title="Sangat Baik">&#9733;</label>
                        <input type="radio" id="star4_4" name="bintang_penanganan_keluhan" value="4"><label for="star4_4" title="Baik">&#9733;</label>
                        <input type="radio" id="star4_3" name="bintang_penanganan_keluhan" value="3"><label for="star4_3" title="Cukup">&#9733;</label>
                        <input type="radio" id="star4_2" name="bintang_penanganan_keluhan" value="2"><label for="star4_2" title="Kurang">&#9733;</label>
                        <input type="radio" id="star4_1" name="bintang_penanganan_keluhan" value="1"><label for="star4_1" title="Sangat Kurang">&#9733;</label>
                    </div>
                    <div class="star-label-desc" id="desc_keluhan">Pilih rating bintang 1 s.d. 5</div>
                </div>
            </div>

            <!-- 5. Layanan Teknis dan Purna Jual -->
            <div class="question-card" id="card_teknis">
                <div class="question-text">
                    <span class="question-number">5</span>
                    <span>Kualitas layanan teknis dan purna jual</span>
                </div>
                <div class="star-rating-wrap">
                    <div class="star-group" data-target="desc_teknis">
                        <input type="radio" id="star5_5" name="bintang_layanan_teknis" value="5" required><label for="star5_5" title="Sangat Baik">&#9733;</label>
                        <input type="radio" id="star5_4" name="bintang_layanan_teknis" value="4"><label for="star5_4" title="Baik">&#9733;</label>
                        <input type="radio" id="star5_3" name="bintang_layanan_teknis" value="3"><label for="star5_3" title="Cukup">&#9733;</label>
                        <input type="radio" id="star5_2" name="bintang_layanan_teknis" value="2"><label for="star5_2" title="Kurang">&#9733;</label>
                        <input type="radio" id="star5_1" name="bintang_layanan_teknis" value="1"><label for="star5_1" title="Sangat Kurang">&#9733;</label>
                    </div>
                    <div class="star-label-desc" id="desc_teknis">Pilih rating bintang 1 s.d. 5</div>
                </div>
            </div>

            <!-- 6. Kepuasan Terhadap Tindak Lanjut Perusahaan -->
            <div class="question-card" id="card_tindaklanjut">
                <div class="question-text">
                    <span class="question-number">6</span>
                    <span>Kepuasan terhadap tindak lanjut perusahaan</span>
                </div>
                <div class="star-rating-wrap">
                    <div class="star-group" data-target="desc_tindaklanjut">
                        <input type="radio" id="star6_5" name="bintang_kepuasan_tindaklanjut" value="5" required><label for="star6_5" title="Sangat Baik">&#9733;</label>
                        <input type="radio" id="star6_4" name="bintang_kepuasan_tindaklanjut" value="4"><label for="star6_4" title="Baik">&#9733;</label>
                        <input type="radio" id="star6_3" name="bintang_kepuasan_tindaklanjut" value="3"><label for="star6_3" title="Cukup">&#9733;</label>
                        <input type="radio" id="star6_2" name="bintang_kepuasan_tindaklanjut" value="2"><label for="star6_2" title="Kurang">&#9733;</label>
                        <input type="radio" id="star6_1" name="bintang_kepuasan_tindaklanjut" value="1"><label for="star6_1" title="Sangat Kurang">&#9733;</label>
                    </div>
                    <div class="star-label-desc" id="desc_tindaklanjut">Pilih rating bintang 1 s.d. 5</div>
                </div>
            </div>

            <!-- 7. Saran dan Masukan -->
            <div class="question-card">
                <div class="question-text">
                    <span class="question-number"><i class="fas fa-comment-dots" style="font-size:10px;"></i></span>
                    <span>Saran dan Masukan untuk Kami</span>
                </div>
                <textarea class="textarea-box" name="saran_masukan" placeholder="Tuliskan saran, kritik, atau hal yang perlu kami tingkatkan untuk pelayanan yang lebih baik... (opsional)"></textarea>
            </div>

            <!-- Tombol Kirim -->
            <button type="submit" class="btn-submit-survey" id="btnSubmitSurvey">
                <i class="fas fa-paper-plane mr-2"></i> Kirim Penilaian Survey
            </button>
        </form>

        <div class="survey-footer">
            &copy; <?= date('Y') ?> PT Visi Yosindo Medikal. Seluruh hak cipta dilindungi.
        </div>
    </div>

    <script src="<?= base_url('assets/vendor/jquery/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/js/sweetalert2/sweetalert2.min.js') ?>"></script>

    <script>
        $(document).ready(function() {
            var labels = {
                '1': '⭐ 1 - Sangat Kurang',
                '2': '⭐⭐ 2 - Kurang',
                '3': '⭐⭐⭐ 3 - Cukup',
                '4': '⭐⭐⭐⭐ 4 - Baik',
                '5': '⭐⭐⭐⭐⭐ 5 - Sangat Baik'
            };

            // Update text label on star radio selection
            $('.star-group input[type="radio"]').on('change', function() {
                var val = $(this).val();
                var targetId = $(this).closest('.star-group').data('target');
                if (targetId && labels[val]) {
                    $('#' + targetId).html('<span style="color: #f59e0b; font-weight: 700;">' + labels[val] + '</span>');
                }
            });

            // Handle AJAX Submit
            $('#formSurveyCustomer').on('submit', function(e) {
                e.preventDefault();

                // Validate that all 6 radio questions are selected
                var qNames = [
                    'bintang_pelayanan',
                    'bintang_kecepatan_respons',
                    'bintang_keramahan',
                    'bintang_penanganan_keluhan',
                    'bintang_layanan_teknis',
                    'bintang_kepuasan_tindaklanjut'
                ];

                for (var i = 0; i < qNames.length; i++) {
                    if (!$('input[name="' + qNames[i] + '"]:checked').val()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Penilaian Belum Lengkap',
                            text: 'Mohon berikan rating bintang (1-5) untuk pertanyaan No. ' + (i + 1) + '.',
                            confirmButtonColor: '#1d4ed8'
                        });
                        $('html, body').animate({
                            scrollTop: $('input[name="' + qNames[i] + '"]').closest('.question-card').offset().top - 30
                        }, 400);
                        return;
                    }
                }

                var $btn = $('#btnSubmitSurvey');
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim Penilaian...');

                $.ajax({
                    url: '<?= base_url("survey/submit") ?>',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(resp) {
                        if (resp.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terima Kasih!',
                                text: resp.message,
                                confirmButtonColor: '#1d4ed8',
                                timer: 2500,
                                showConfirmButton: false
                            }).then(function() {
                                if (resp.redirect) {
                                    window.location.href = resp.redirect;
                                }
                            });
                            setTimeout(function() {
                                if (resp.redirect) {
                                    window.location.href = resp.redirect;
                                }
                            }, 1500);
                        } else {
                            $btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i> Kirim Penilaian Survey');
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Mengirim',
                                text: resp.message || 'Terjadi kesalahan sistem, silakan coba lagi.',
                                confirmButtonColor: '#1d4ed8'
                            });
                        }
                    },
                    error: function() {
                        $btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i> Kirim Penilaian Survey');
                        Swal.fire({
                            icon: 'error',
                            title: 'Koneksi Terganggu',
                            text: 'Gagal menghubungi server. Silakan periksa koneksi internet Anda.',
                            confirmButtonColor: '#1d4ed8'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
