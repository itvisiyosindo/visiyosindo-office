<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Buku Tamu | <?= $this->config->item('apps_name') ?></title>
    <meta name="keywords" content="Sistem Informasi" />
    <meta name="description" content="<?= $this->config->item('apps_name') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/animate/animate.compat.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/boxicons/css/boxicons.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/simple-line-icons/css/simple-line-icons.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>js/sweetalert2/sweetalert2.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/theme.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/custom.css">
    <link rel="shortcut icon" href="<?= base_url('assets/') ?>img/favicon.png" />

    <script src="<?= base_url() ?>assets/vendor/modernizr/modernizr.js"></script>
    <script src="<?= base_url() ?>assets/master/style-switcher/style.switcher.localstorage.js"></script>

    <style>
        /* =========================================
           1. GLOBAL & BACKGROUND FIX (SOLUSI MOBILE)
           ========================================= */
        html,
        body {
            min-height: 100%;
            height: auto;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            /* Mencegah scroll horizontal */
        }

        html {
            overflow-y: auto !important;
        }

        body {
            color: #fff;
            background-color: transparent;
            /* Body transparan agar layer belakang terlihat */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            overflow-y: visible;
        }

        /* LAYER BACKGROUND KHUSUS (FIXED) */
        /* Ini memisahkan gambar dari konten agar tidak ikut scroll/habis di HP */
        .bg-fixed-layer {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            /* Di belakang konten */

            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)),
                url("<?= base_url('assets/img/bg-expo-nov2025.jpeg') ?>");

            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;

            /* Optimasi rendering untuk Mobile Browser */
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
        }

        /* Override Style Bawaan Template */
        .body-sign {
            display: block;
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .center-sign {
            display: block;
            width: 100%;
        }

        /* =========================================
           2. GLASSMORPHISM CARD (PANEL UTAMA)
           ========================================= */
        .glass-panel {
            background: rgba(255, 255, 255, 0.1);
            /* Transparan Putih */
            backdrop-filter: blur(15px);
            /* Efek Blur Kaca */
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            width: 100%;
        }

        /* =========================================
           3. HEADER PROFILE
           ========================================= */
        .user-image {
            width: 90px;
            height: 90px;
            border: 3px solid rgba(255, 255, 255, 0.5);
            padding: 3px;
            margin-bottom: 15px;
            background: transparent;
        }

        .title-text {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            display: block;
        }

        .subtitle-text {
            font-size: 16px;
            font-weight: 300;
            color: #e0e0e0;
            margin-bottom: 12px;
            letter-spacing: 1px;
            display: block;
        }

        .event-meta {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-bottom: 22px;
        }

        .event-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 500;
            color: #f6fbff;
        }

        /* =========================================
           4. FORM STYLING
           ========================================= */
        .form-group-modern {
            position: relative;
            margin-bottom: 18px;
        }

        .field-label {
            display: block;
            margin-bottom: 7px;
            color: #f5fbff;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.2px;
        }

        .helper-note {
            display: block;
            margin-top: 6px;
            color: rgba(255, 255, 255, 0.72);
            font-size: 11px;
        }

        .input-shell {
            position: relative;
        }

        .form-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #fff;
            opacity: 0.7;
            z-index: 10;
        }

        .form-icon-area {
            top: 14px;
            transform: none;
        }

        .form-control-glass {
            background: rgba(255, 255, 255, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 10px !important;
            color: #fff !important;
            padding: 12px 15px 12px 45px !important;
            width: 100%;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control-glass:focus {
            background: rgba(255, 255, 255, 0.25) !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
            outline: none;
        }

        .form-control-glass::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .duplicate-warning {
            display: none;
            border-radius: 10px;
            border: 1px solid rgba(255, 206, 84, 0.45);
            background: rgba(255, 206, 84, 0.18);
            color: #ffe7a8;
            padding: 10px 12px;
            font-size: 12px;
            margin-top: 8px;
        }

        .duplicate-warning strong {
            color: #fff4c7;
        }

        /* =========================================
           5. BUTTONS & LINKS
           ========================================= */
        .card-link-produk {
            background: rgba(46, 204, 113, 0.2);
            border: 1px solid rgba(46, 204, 113, 0.4);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s;
        }

        .card-link-produk:hover {
            transform: scale(1.02);
            background: rgba(46, 204, 113, 0.3);
        }

        .btn-kunjungi {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-kunjungi:hover {
            background: #27ae60;
            color: white;
        }

        .btn-submit-modern {
            width: 100%;
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-submit-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.6);
            background: linear-gradient(45deg, #0056b3, #004494);
        }

        .btn-submit-modern:disabled {
            opacity: 0.75;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-submit-modern.is-loading::after {
            content: '';
            width: 14px;
            height: 14px;
            margin-left: 8px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.7);
            border-top-color: transparent;
            display: inline-block;
            vertical-align: middle;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .alert-glass {
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.13);
            color: #fff;
            font-size: 13px;
        }

        .alert-glass a {
            color: #ffffff;
            font-weight: 600;
            text-decoration: underline;
        }

        /* =========================================
           6. RESPONSIVE MOBILE FIX
           ========================================= */
        @media (max-width: 768px) {
            body {
                /* KUNCI AGAR TIDAK POTONG ATAS SAAT SCROLL */
                align-items: flex-start;
                padding-top: 40px;
                padding-bottom: 40px;
                height: auto;
                /* Izinkan tinggi menyesuaikan konten */
            }

            .glass-panel {
                padding: 30px 20px;
                margin-bottom: 20px;
            }

            .title-text {
                font-size: 20px;
            }
        }
    </style>
</head>

<body id="bg-bukutamu">

    <?php
    $kegiatan_id = (isset($kegiatan) && is_object($kegiatan) && !empty($kegiatan->id)) ? (int) $kegiatan->id : 0;
    $kegiatan_nama = (isset($kegiatan) && is_object($kegiatan) && !empty($kegiatan->nama)) ? $kegiatan->nama : 'Belum ada kegiatan aktif';
    $total_isi_event = isset($totalIsiEvent) ? (int) $totalIsiEvent : 0;
    $old_input = (array) $this->session->flashdata('old_input');
    ?>

    <div class="bg-fixed-layer"></div>

    <section class="body-sign">
        <div class="center-sign">

            <div class="glass-panel animate__animated animate__fadeInUp">

                <div class="text-center">
                    <img src="<?= base_url() ?>assets/img/logovym2023.png" alt="Logo VYM" class="rounded-circle user-image" />
                    <h2 class="title-text">Buku Tamu</h2>
                    <span class="subtitle-text"><?= html_escape($kegiatan_nama) ?></span>
                    <div class="event-meta">
                        <span class="event-chip"><i class="fas fa-calendar-alt"></i> Event Aktif</span>
                        <span class="event-chip"><i class="fas fa-users"></i> <?= $total_isi_event ?> Pengisi</span>
                    </div>
                </div>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-glass text-center">
                        <i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('warning')): ?>
                    <div class="alert alert-warning alert-glass text-center">
                        <i class="fas fa-exclamation-triangle"></i> <?= $this->session->flashdata('warning') ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-glass text-center">
                        <i class="fas fa-times-circle"></i> <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if ($kegiatan_id <= 0): ?>
                    <div class="alert alert-danger alert-glass text-center">
                        <i class="fas fa-ban"></i> Event belum tersedia. Hubungi admin untuk menambahkan kegiatan terlebih dahulu.
                    </div>
                <?php endif; ?>

                <form id="guestbook-form" action="<?= base_url('BukuTamu/register') ?>" method="post" autocomplete="off">

                    <input name="kode" type="hidden" />
                    <input name="kegiatan_id" id="kegiatan_id" type="hidden" value="<?= $kegiatan_id ?>" />

                    <div class="form-group-modern">
                        <label class="field-label" for="nama">Nama Lengkap <span class="text-warning">*</span></label>
                        <div class="input-shell">
                            <i class="icon-user form-icon"></i>
                            <input id="nama" name="nama" type="text" class="form-control-glass" placeholder="Nama Lengkap" value="<?= html_escape($old_input['nama'] ?? '') ?>" maxlength="120" required />
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label class="field-label" for="jabatan">Jabatan <span class="text-warning">*</span></label>
                        <div class="input-shell">
                            <i class="icon-briefcase form-icon"></i>
                            <input id="jabatan" name="jabatan" type="text" class="form-control-glass" placeholder="Jabatan" value="<?= html_escape($old_input['jabatan'] ?? '') ?>" maxlength="120" required />
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label class="field-label" for="nomorwa">Nomor WhatsApp <span class="text-warning">*</span></label>
                        <div class="input-shell">
                            <i class="fab fa-whatsapp form-icon"></i>
                            <input id="nomorwa" name="nomorwa" type="tel" inputmode="numeric" class="form-control-glass" placeholder="Contoh: 081234567890" value="<?= html_escape($old_input['nomorwa'] ?? '') ?>" maxlength="20" required />
                        </div>
                        <small class="helper-note">Nomor akan disimpan dalam format standar agar tidak terjadi duplikasi data.</small>
                        <div id="duplicate-alert" class="duplicate-warning"></div>
                    </div>

                    <div class="form-group-modern">
                        <label class="field-label" for="email">Email</label>
                        <div class="input-shell">
                            <i class="icon-envelope form-icon"></i>
                            <input id="email" name="email" type="email" class="form-control-glass" placeholder="Alamat Email" value="<?= html_escape($old_input['email'] ?? '') ?>" maxlength="160" />
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label class="field-label" for="instansi">Instansi / Perusahaan <span class="text-warning">*</span></label>
                        <div class="input-shell">
                            <i class="icon-organization form-icon"></i>
                            <input id="instansi" name="instansi" type="text" class="form-control-glass" placeholder="Instansi / Perusahaan" value="<?= html_escape($old_input['instansi'] ?? '') ?>" maxlength="160" required />
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label class="field-label" for="tipe_rs">Tipe Rumah Sakit</label>
                        <div class="input-shell">
                            <i class="fas fa-hospital form-icon"></i>
                            <select class="form-control-glass" name="tipe_rs" id="tipe_rs" style="color: #fff; background-color: rgba(255,255,255,0.15);">
                                <option value="" style="color:#000;">— Pilih Tipe RS —</option>
                                <option value="A" style="color:#000;" <?= (isset($old_input['tipe_rs']) && $old_input['tipe_rs'] === 'A') ? 'selected' : '' ?>>Tipe A</option>
                                <option value="B" style="color:#000;" <?= (isset($old_input['tipe_rs']) && $old_input['tipe_rs'] === 'B') ? 'selected' : '' ?>>Tipe B</option>
                                <option value="C" style="color:#000;" <?= (isset($old_input['tipe_rs']) && $old_input['tipe_rs'] === 'C') ? 'selected' : '' ?>>Tipe C</option>
                                <option value="D" style="color:#000;" <?= (isset($old_input['tipe_rs']) && $old_input['tipe_rs'] === 'D') ? 'selected' : '' ?>>Tipe D</option>
                                <option value="Non-RS" style="color:#000;" <?= (isset($old_input['tipe_rs']) && $old_input['tipe_rs'] === 'Non-RS') ? 'selected' : '' ?>>Non-RS</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label class="field-label" for="provinsi">Provinsi <span class="text-warning">*</span></label>
                        <div class="input-shell">
                            <i class="icon-map form-icon"></i>
                            <select class="form-control-glass" name="provinsi" id="provinsi" required style="color: #fff; background-color: rgba(255,255,255,0.15);">
                                <option value="" style="color:#000;">— Pilih Provinsi —</option>
                                <?php foreach ($provinsi as $value): ?>
                                    <option value="<?= $value->id ?>" style="color:#000;"><?= htmlspecialchars($value->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label class="field-label" for="kota">Kabupaten / Kota <span class="text-warning">*</span></label>
                        <div class="input-shell">
                            <i class="icon-location-pin form-icon"></i>
                            <select class="form-control-glass" name="kota" id="kota" required style="color: #fff; background-color: rgba(255,255,255,0.15);">
                                <option value="" style="color:#000;">- Pilih Kabupaten/Kota -</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label class="field-label" for="kebutuhan">Kebutuhan / Pesan</label>
                        <div class="input-shell">
                            <i class="icon-note form-icon form-icon-area"></i>
                            <textarea id="kebutuhan" name="kebutuhan" class="form-control-glass" placeholder="Kebutuhan / Pesan" rows="3" maxlength="300"><?= html_escape($old_input['kebutuhan'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="card-link-produk">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-box-open mr-2 text-white"></i>
                            <span style="font-weight: 500; font-size: 14px;">Lihat Katalog Produk</span>
                        </div>
                        <a href="https://linktr.ee/expovym" target="_blank" class="btn-kunjungi">
                            Klik Disini <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    <button type="submit" class="btn-submit-modern" id="btn-submit" <?= ($kegiatan_id <= 0 ? 'disabled' : '') ?>>
                        <?= ($kegiatan_id <= 0 ? 'EVENT BELUM DIBUKA' : 'SUBMIT DATA') ?>
                    </button>

                </form>

                <div class="text-center mt-4" style="font-size: 12px; color: rgba(255,255,255,0.5);">
                    &copy; <span id="currentYear"></span> PT VISI YOSINDO MEDIKAL.<br>All Rights Reserved.
                </div>

            </div>
        </div>
    </section>

    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

    <script src="<?= base_url() ?>assets/vendor/jquery/jquery.js"></script>
    <script src="<?= base_url() ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
    <script src="<?= base_url() ?>assets/vendor/jquery-cookie/jquery.cookie.js"></script>
    <script src="<?= base_url() ?>assets/vendor/popper/umd/popper.min.js"></script>
    <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="<?= base_url() ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= base_url() ?>assets/vendor/common/common.js"></script>
    <script src="<?= base_url() ?>assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="<?= base_url() ?>assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
    <script src="<?= base_url() ?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
    <script src="<?= base_url() ?>assets/js/theme.js"></script>
    <script src="<?= base_url() ?>assets/js/theme.init.js"></script>
    <script src="<?= base_url('assets/') ?>/js/sweetalert2/sweetalert2.min.js"></script>
    <script src="<?= base_url('assets/') ?>js/global.js"></script>

    <script>
        // Set Tahun Otomatis
        document.getElementById("currentYear").textContent = new Date().getFullYear();

        // Ambil Token
        let token = $('input[name=token]').val();

        document.addEventListener("DOMContentLoaded", function() {
            const $form = $('#guestbook-form');
            const $btnSubmit = $('#btn-submit');
            const $nama = $('#nama');
            const $nomorwa = $('#nomorwa');
            const $duplicateAlert = $('#duplicate-alert');
            const kegiatanId = $('#kegiatan_id').val();

            let duplicateFound = false;
            let checkTimer = null;
            let activeRequest = null;

            function normalizePhone(value) {
                const digits = (value || '').replace(/\D+/g, '');

                if (!digits) {
                    return '';
                }

                if (digits.indexOf('62') === 0) {
                    return digits;
                }

                if (digits.indexOf('0') === 0) {
                    return '62' + digits.substring(1);
                }

                if (digits.indexOf('8') === 0) {
                    return '62' + digits;
                }

                return digits;
            }

            function setDuplicateAlert(isDuplicate, message) {
                duplicateFound = isDuplicate;

                if (!isDuplicate) {
                    $duplicateAlert.hide().text('');
                    $btnSubmit.prop('disabled', kegiatanId <= 0);
                    return;
                }

                $duplicateAlert.html('<strong>Perhatian:</strong> ' + message).show();
                $btnSubmit.prop('disabled', true);
            }

            function checkDuplicate() {
                const nomorwa = $nomorwa.val().trim();

                if (!kegiatanId || !nomorwa) {
                    setDuplicateAlert(false, '');
                    return;
                }

                if (activeRequest) {
                    activeRequest.abort();
                }

                activeRequest = $.ajax({
                    method: 'POST',
                    url: '<?= base_url('BukuTamu/checkDuplicate') ?>',
                    dataType: 'JSON',
                    data: {
                        kegiatan_id: kegiatanId,
                        nomorwa: nomorwa,
                        csrf_token: token
                    }
                }).done(function(resp) {
                    if (resp && resp.status === 'ok' && resp.duplicate) {
                        setDuplicateAlert(true, resp.message || 'Nomor WhatsApp sudah terdaftar pada event yang sama.');
                        return;
                    }

                    setDuplicateAlert(false, '');
                }).fail(function(xhr, status) {
                    if (status !== 'abort') {
                        setDuplicateAlert(false, '');
                    }
                });
            }

            $nomorwa.on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                clearTimeout(checkTimer);
                checkTimer = setTimeout(checkDuplicate, 450);
            });

            $nama.on('input', function() {
                clearTimeout(checkTimer);
                checkTimer = setTimeout(checkDuplicate, 450);
            });

            $nama.on('blur', checkDuplicate);
            $nomorwa.on('blur', checkDuplicate);

            $('#provinsi').on('change', function() {
                var provId = this.value;
                var $kota = $('#kota');
                $kota.html('<option value="" style="color:#000;">Loading...</option>');
                if (provId) {
                    $.ajax({
                        url: '<?= base_url('BukuTamu/add_ajax_kota') ?>/' + provId,
                        type: 'GET',
                        success: function(html) {
                            $kota.html(html);
                            $kota.find('option').css('color', '#000');
                        },
                        error: function() {
                            $kota.html('<option value="" style="color:#000;">Gagal memuat data</option>');
                        }
                    });
                } else {
                    $kota.html('<option value="" style="color:#000;">- Pilih Kabupaten/Kota -</option>');
                }
            });

            $form.on('submit', function(e) {
                if (duplicateFound) {
                    e.preventDefault();
                    Swal.fire('Data Duplikat', 'Nomor WhatsApp ini sudah terdaftar pada event yang sama.', 'warning');
                    return false;
                }

                const normalizedNoWa = normalizePhone($nomorwa.val());
                if (!normalizedNoWa || normalizedNoWa.length < 10) {
                    e.preventDefault();
                    Swal.fire('Validasi', 'Nomor WhatsApp tidak valid. Mohon periksa kembali.', 'warning');
                    return false;
                }

                $nomorwa.val(normalizedNoWa);
                $btnSubmit.prop('disabled', true).addClass('is-loading').text('MENYIMPAN...');
            });
        });
    </script>

</body>

</html>