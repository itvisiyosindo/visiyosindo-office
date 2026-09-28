<?php
$wfaSystemActive = isset($wfa_system_active) && (int) $wfa_system_active === 1;
$todayIsFriday = isset($today_is_friday) ? (bool) $today_is_friday : date('l') == 'Friday';
$realTodayIsFriday = isset($real_today_is_friday) ? (bool) $real_today_is_friday : date('l') == 'Friday';
$isSimulasiJumat = isset($is_simulasi_jumat) ? (bool) $is_simulasi_jumat : false;
$canUseWfa = $wfaSystemActive && $todayIsFriday;
$canToggleSimulasi = isAdmin() || isHrd() || isGa();
$previewBaseUrl = base_url('absensi/show/do_absen2/' . encrypt(sessPenggunaId()));

$attendanceButton = null;
if (!isset($data_absen[0]->waktu_absen)) {
    if (sessPenggunaId() == 94) {
        $masukA = !empty($config[0]->boleh_absen_malam) ? date($config[0]->boleh_absen_malam) : date('13:00:00');
        $masukB = date('23:00:00');
        $keluarA = !empty($config[0]->jam_keluar_malam) ? date($config[0]->jam_keluar_malam) : date('07:00:00');
        $keluarB = date('11:00:00');
        $now = date('H:i:s');

        if ($now >= $masukA && $now < $masukB) {
            if (!isset($data_absen_izin[0]->waktu_absen)) {
                $attendanceButton = [
                    'class' => 'is-masuk',
                    'label' => 'Absen Masuk',
                    'icon' => 'bx bx-log-in'
                ];
            }
        } else if ($now >= $keluarA && $now <= $keluarB) {
            $attendanceButton = [
                'class' => 'is-keluar',
                'label' => 'Absen Keluar',
                'icon' => 'bx bx-log-out'
            ];
        }
    } else {
        $hari = $todayIsFriday ? 'Friday' : date('l');
        if ($hari == 'Friday') {
            $rehatA = date($config[0]->mulai_rehat_a);
            $rehatB = date($config[0]->akhir_rehat_a);
        } else {
            $rehatA = date($config[0]->mulai_rehat_b);
            $rehatB = date($config[0]->akhir_rehat_b);
        }

        $pulang1 = date($config[0]->jam_keluar);
        $pulang2 = date('23:59:00');
        $now = date('H:i:s');

        if ($now < $rehatA) {
            if (!isset($data_absen_izin[0]->waktu_absen)) {
                $attendanceButton = [
                    'class' => 'is-masuk',
                    'label' => 'Absen Masuk',
                    'icon' => 'bx bx-log-in'
                ];
            }
        } else if ($now > $rehatA && $now < $rehatB) {
            $attendanceButton = [
                'class' => 'is-istirahat',
                'label' => 'Absen Istirahat',
                'icon' => 'bx bx-coffee-togo'
            ];
        } else if ($now > $pulang1 && $now < $pulang2) {
            $attendanceButton = [
                'class' => 'is-keluar',
                'label' => 'Absen Keluar',
                'icon' => 'bx bx-log-out'
            ];
        }
    }
}

$statusClass = 'status-ok';
$statusIcon = 'bx bx-check-circle';
$statusTitle = 'Absensi hari ini belum tercatat.';
$statusSubtitle = 'Silakan lakukan absensi pada jadwal yang tersedia.';

if (isset($data_absen[0]->waktu_absen)) {
    if ($data_absen[0]->status_absen == 'terlambat') {
        $statusClass = 'status-danger';
        $statusIcon = 'bx bx-error-circle';
        $statusTitle = 'Anda terlambat absen ' . $data_absen[0]->jenis_absen;
    } else if ($data_absen[0]->status_absen == 'tepat_waktu' || $data_absen[0]->status_absen == 'istirahat') {
        $statusClass = 'status-ok';
        $statusIcon = 'bx bx-check-circle';
        $statusTitle = 'Anda tepat waktu absen ' . $data_absen[0]->jenis_absen;
    } else {
        $statusClass = 'status-ok';
        $statusIcon = 'bx bx-badge-check';
        $statusTitle = 'Anda sudah absen ' . $data_absen[0]->type_absen;
    }

    $lokasiKerja = isset($data_absen[0]->jenis_lokasi) && $data_absen[0]->jenis_lokasi !== ''
        ? $data_absen[0]->jenis_lokasi
        : 'Kantor';
    $statusSubtitle = 'Waktu: ' . $data_absen[0]->waktu_absen . ' | Lokasi kerja: ' . $lokasiKerja;
}
?>

<header class="page-header">
    <h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap');

:root {
    --absen-bg: #f2f5f9;
    --panel: #ffffff;
    --line: #dbe3ee;
    --text: #10233f;
    --text-soft: #5b6f8c;
    --brand: #155eef;
    --brand-dark: #0f4cd6;
    --teal: #0e9384;
    --teal-soft: #d8f6f2;
    --amber: #b54708;
    --amber-soft: #fff1de;
    --danger: #b42318;
    --danger-soft: #ffe4e8;
    --ok: #067647;
    --ok-soft: #dcfae6;
    --radius-lg: 18px;
    --radius-md: 12px;
    --shadow: 0 12px 28px rgba(12, 33, 70, 0.1);
}

.absen-shell,
.absen-shell * {
    font-family: 'Manrope', 'Segoe UI', sans-serif;
}

.absen-wrap {
    background: radial-gradient(circle at 15% 0%, #e4edff 0%, #f2f5f9 45%), linear-gradient(135deg, #edf2f8, #f7f9fc);
    padding: 24px 14px 36px;
    border-radius: var(--radius-lg);
}

.absen-shell {
    max-width: 980px;
    margin: 0 auto;
    background: var(--panel);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.hero {
    background: linear-gradient(120deg, #0d4fd3 0%, #1877f2 52%, #1ea5f8 100%);
    color: #fff;
    padding: 26px 24px;
}

.hero h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 800;
    letter-spacing: 0.2px;
}

.hero p {
    margin: 6px 0 0;
    opacity: 0.95;
    font-size: 14px;
}

.hero-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 14px;
}

.preview-tools {
    margin-top: 14px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.preview-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 999px;
    padding: 8px 12px;
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
    border: 1px solid rgba(255, 255, 255, 0.26);
}

.preview-link.enable {
    color: #fff;
    background: rgba(255, 255, 255, 0.16);
}

.preview-link.enable:hover,
.preview-link.enable:focus {
    color: #fff;
    background: rgba(255, 255, 255, 0.24);
    text-decoration: none;
}

.preview-link.disable {
    color: #113074;
    background: #ecf2ff;
    border-color: #bfceee;
}

.preview-link.disable:hover,
.preview-link.disable:focus {
    color: #113074;
    background: #dfebff;
    text-decoration: none;
}

.hero-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.16);
    border: 1px solid rgba(255, 255, 255, 0.22);
    color: #fff;
    border-radius: 999px;
    padding: 7px 12px;
    font-size: 12px;
    font-weight: 700;
}

.absen-content {
    padding: 22px;
}

.status-box {
    border: 1px solid;
    border-radius: var(--radius-md);
    padding: 14px 16px;
    margin-bottom: 18px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.status-box i {
    font-size: 24px;
    margin-top: -1px;
}

.status-box h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 800;
}

.status-box p {
    margin: 3px 0 0;
    font-size: 13px;
    font-weight: 600;
}

.status-ok {
    background: var(--ok-soft);
    border-color: #a4e8c3;
    color: var(--ok);
}

.status-danger {
    background: var(--danger-soft);
    border-color: #ffc4cc;
    color: var(--danger);
}

.grid-two {
    display: grid;
    grid-template-columns: 1.25fr 1fr;
    gap: 16px;
}

.panel {
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    padding: 16px;
    background: #fff;
}

.panel h4 {
    margin: 0 0 12px;
    font-size: 15px;
    font-weight: 800;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 8px;
}

.panel-subtitle {
    color: var(--text-soft);
    font-size: 12px;
    font-weight: 600;
    margin-top: -6px;
    margin-bottom: 12px;
}

.camera-box {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #c9d8f1;
    min-height: 290px;
    background: linear-gradient(145deg, #f4f8ff, #eef4ff);
    display: flex;
    align-items: center;
    justify-content: center;
}

.camera-placeholder {
    text-align: center;
    color: #5f7394;
    font-size: 13px;
    font-weight: 700;
    padding: 18px;
}

.camera-placeholder i {
    display: block;
    font-size: 30px;
    margin-bottom: 8px;
    color: #7f94b3;
}

.my_camera,
.my_camera video {
    width: 100% !important;
    height: auto !important;
}

.my_camera video,
.my_camera canvas {
    transform: scaleX(1) !important;
    filter: none;
}

.camera-controls {
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.camera-toggle {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #c9d8f1;
    background: #f6f9ff;
    border-radius: 999px;
    padding: 6px 10px;
    font-size: 12px;
    font-weight: 700;
    color: #2b4f86;
    cursor: pointer;
    user-select: none;
}

.camera-toggle input {
    width: 15px;
    height: 15px;
    accent-color: var(--brand);
}

.camera-controls-note {
    margin-top: 8px;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-soft);
}

.clock-value {
    margin: 0;
    font-size: 48px;
    font-weight: 800;
    letter-spacing: 1px;
    color: var(--brand);
}

.clock-date {
    margin: 6px 0 0;
    color: var(--text-soft);
    font-size: 13px;
    font-weight: 700;
}

.location-panel {
    margin-top: 16px;
}

.grid-two .location-panel {
    margin-top: 0;
}

.hint {
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 12px;
}

.hint-info {
    background: #e8f0ff;
    color: #1c4fb9;
    border: 1px solid #bdd3ff;
}

.hint-warning {
    background: var(--amber-soft);
    color: var(--amber);
    border: 1px solid #ffd99f;
}

.wfa-check-wrap {
    border: 1px solid #bdd3ff;
    border-radius: 12px;
    background: #f6f9ff;
    padding: 12px;
    margin-top: 10px;
}

.wfa-check-label {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    color: #1c4fb9;
    font-weight: 800;
    cursor: pointer;
}

.wfa-check-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

.wfa-check-caption {
    margin-top: 8px;
    color: var(--text-soft);
    font-size: 12px;
    font-weight: 700;
}

.location-picker {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 10px;
}

.location-option {
    position: relative;
}

.location-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.location-option label {
    border: 1px solid #b8c8e3;
    border-radius: 12px;
    padding: 12px;
    display: block;
    cursor: pointer;
    font-size: 13px;
    font-weight: 800;
    color: #23416a;
    text-align: center;
    transition: all 0.18s ease;
    background: #f7faff;
}

.location-option label i {
    margin-right: 6px;
}

.location-option input:checked + label {
    border-color: var(--brand);
    background: #e5eeff;
    color: var(--brand);
    box-shadow: inset 0 0 0 1px var(--brand);
}

.location-preview {
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    overflow: hidden;
    background: #fafcff;
    min-height: 250px;
}

.location-preview iframe {
    width: 100%;
    height: 250px;
    border: 0;
}

.location-status {
    padding: 9px 12px;
    border-top: 1px solid var(--line);
    font-size: 12px;
    color: var(--text-soft);
    font-weight: 700;
}

.actions {
    margin-top: 16px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.btn-main {
    border: 0;
    border-radius: 10px;
    padding: 11px 16px;
    font-size: 13px;
    font-weight: 800;
    color: #fff;
    cursor: pointer;
    min-width: 180px;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.btn-main:hover {
    transform: translateY(-1px);
}

.is-masuk {
    background: linear-gradient(120deg, #0e9384, #12b59f);
    box-shadow: 0 8px 20px rgba(18, 181, 159, 0.24);
}

.is-istirahat {
    background: linear-gradient(120deg, #1570ef, #53a3ff);
    box-shadow: 0 8px 20px rgba(21, 112, 239, 0.24);
}

.is-keluar {
    background: linear-gradient(120deg, #b54708, #f38744);
    box-shadow: 0 8px 20px rgba(245, 140, 66, 0.24);
}

.is-plain {
    background: #4c6389;
    box-shadow: none;
}

.text-note {
    margin-top: 12px;
    padding: 10px 12px;
    border-radius: 10px;
    border: 1px dashed #c5d1e3;
    background: #f8fbff;
    color: #4f6488;
    font-size: 12px;
    font-weight: 700;
}

@media (max-width: 900px) {
    .grid-two {
        grid-template-columns: 1fr;
    }

    .location-picker {
        grid-template-columns: 1fr;
    }

    .clock-value {
        font-size: 40px;
    }

    .btn-main {
        width: 100%;
    }
}
</style>

<div class="absen-wrap">
    <div class="absen-shell">
        <div class="hero">
            <h3>Absensi Kantor dan WFA</h3>
            <p class="text-white">Absensi harian terintegrasi kamera dan geolokasi untuk validasi kehadiran yang akurat.</p>
            <div class="hero-chips">
                <span class="hero-chip"><i class="bx bx-calendar"></i> <?= date('d-m-Y') ?></span>
                <span class="hero-chip"><i class="bx bx-map-pin"></i> <?= $canUseWfa ? 'Centang untuk mode WFA' : 'Mode kantor aktif' ?></span>
                <?php if ($isSimulasiJumat && !$realTodayIsFriday): ?>
                    <span class="hero-chip"><i class="bx bx-test-tube"></i> Simulasi Jumat Aktif</span>
                <?php endif; ?>
                <span class="hero-chip"><i class="bx bx-shield"></i> Validasi GPS dan foto</span>
            </div>

            <?php if ($canToggleSimulasi && !$realTodayIsFriday): ?>
                <div class="preview-tools">
                    <?php if ($isSimulasiJumat): ?>
                        <a class="preview-link disable" href="<?= $previewBaseUrl ?>">
                            <i class="bx bx-reset"></i> Kembali ke Mode Hari Ini
                        </a>
                    <?php else: ?>
                        <a class="preview-link enable" href="<?= $previewBaseUrl ?>?simulasi_jumat=1">
                            <i class="bx bx-test-tube"></i> Preview Tampilan Jumat
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="absen-content">
            <div class="status-box <?= $statusClass ?>">
                <i class="<?= $statusIcon ?>"></i>
                <div>
                    <h5><?= $statusTitle ?></h5>
                    <p><?= $statusSubtitle ?></p>
                </div>
            </div>

            <?= form_open('absensi/addPhoto', ['id' => 'absen-form', 'autocomplete' => 'off']); ?>
            <input type="hidden" name="pengguna_id" id="pengguna_id" value="<?= encrypt(sessPenggunaId()) ?>">
            <input type="hidden" id="latitude" value="">
            <input type="hidden" id="longitude" value="">
            <input type="hidden" id="imageData" name="imageData" value="">

            <div class="grid-two">
                <div class="panel">
                    <h4><i class="bx bx-camera"></i> Kamera Absensi</h4>
                    <?php if (!isset($data_absen[0]->waktu_absen)): ?>
                        <div class="panel-subtitle">Foto diambil otomatis saat tombol absen ditekan.</div>
                        <div class="camera-box">
                            <div class="my_camera"></div>
                        </div>
                        <div class="camera-controls">
                            <label class="camera-toggle" for="toggle_beauty_effect">
                                <input type="checkbox" id="toggle_beauty_effect" checked>
                                <span>Beauty Effect</span>
                            </label>
                            <label class="camera-toggle" for="toggle_mirror_correction">
                                <input type="checkbox" id="toggle_mirror_correction" checked>
                                <span>Mirror Correction</span>
                            </label>
                        </div>
                        <div class="camera-controls-note">
                            Mirror Correction aktif: preview tidak terbalik. Nonaktif: mode cermin.
                        </div>
                    <?php else: ?>
                        <div class="panel-subtitle">Foto sudah diambil saat absensi diproses.</div>
                        <div class="camera-box">
                            <div class="camera-placeholder">
                                <i class="bx bx-check-shield"></i>
                                Bukti foto absensi sudah tercatat.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="panel location-panel">
                    <h4><i class="bx bx-current-location"></i> Lokasi Kerja</h4>

                <?php if ($canUseWfa): ?>
                    <input type="hidden" name="jenis_lokasi" id="jenis_lokasi_input" value="Kantor">
                    <?php if ($isSimulasiJumat && !$realTodayIsFriday): ?>
                        <div class="hint hint-warning">
                            Mode simulasi Jumat aktif untuk preview tampilan. Penyimpanan absensi tetap mengikuti hari asli server.
                        </div>
                    <?php endif; ?>
                    <div class="hint hint-info">
                        Hari ini Jumat dan fitur WFA aktif. Centang checkbox di bawah jika Anda WFA.
                    </div>
                    <div class="wfa-check-wrap mb-3">
                        <label class="wfa-check-label" for="check_wfa_mode">
                            <input type="checkbox" id="check_wfa_mode" value="1">
                            <span><i class="bx bx-wifi"></i> Saya WFA hari ini</span>
                        </label>
                        <div class="wfa-check-caption" id="wfa_check_caption">Status lokasi kerja: Kantor</div>
                    </div>
                <?php elseif ($wfaSystemActive): ?>
                    <input type="hidden" name="jenis_lokasi" id="jenis_lokasi_input" value="Kantor">
                    <div class="hint hint-warning">
                        WFA hanya berlaku pada hari Jumat. Hari ini sistem menetapkan lokasi kerja Kantor.
                    </div>
                <?php else: ?>
                    <input type="hidden" name="jenis_lokasi" id="jenis_lokasi_input" value="Kantor">
                    <div class="hint hint-info">
                        Konfigurasi WFA belum diaktifkan. Lokasi kerja menggunakan mode Kantor.
                    </div>
                <?php endif; ?>

                    <div class="location-preview" id="showlokasi"></div>
                    <div class="location-status" id="locationStatus">Membaca lokasi GPS...</div>

                    <div class="actions">
                        <?php if (isset($data_absen[0]->waktu_absen)): ?>
                            <button type="button" class="btn-main is-plain btn-lihat-posisi">
                                <i class="bx bx-map"></i> Lihat Posisi Absensi
                            </button>
                        <?php else: ?>
                            <?php if (!empty($attendanceButton)): ?>
                                <button type="button" class="btn-main <?= $attendanceButton['class'] ?> btn-absen1">
                                    <i class="<?= $attendanceButton['icon'] ?>"></i> <?= $attendanceButton['label'] ?>
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn-main is-plain" disabled>
                                    <i class="bx bx-time"></i> Menunggu Jadwal Absensi
                                </button>
                            <?php endif; ?>

                            <button type="button" class="btn-main is-plain btn-segarkan">
                                <i class="bx bx-refresh"></i> Segarkan Lokasi
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?= form_close(); ?>

            <div class="text-note">
                Absen izin tetap terintegrasi dengan surat izin resmi perusahaan. Gunakan menu surat izin untuk pengajuan tidak hadir.
            </div>
        </div>
    </div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Lokasi Absen</h4>
                <button type="button" class="close" style="color:white;margin: -1px" data-dismiss="modal" aria-label="Close"><i class="far fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div id="dvMap" style="height: 700px"></div><br>
                <div class="text-right">
                    <?php if (isset($data_absen[0]->latitude)): ?>
                        <input type="hidden" id="longitudeuser" value="<?= $data_absen[0]->longitude ?>">
                        <input type="hidden" id="latitudeuser" value="<?= $data_absen[0]->latitude ?>">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script>
(function() {
    const MAP_KEY = 'AIzaSyAFycbDEoOn8GPKQ1_fij6S1e1UpRZgKJo';
    const HAS_ATTENDANCE_TODAY = <?= isset($data_absen[0]->waktu_absen) ? 'true' : 'false' ?>;
    const IS_SIMULASI_JUMAT = <?= !empty($is_simulasi_jumat) ? 'true' : 'false' ?>;
    const REAL_TODAY_IS_FRIDAY = <?= !empty($real_today_is_friday) ? 'true' : 'false' ?>;
    const SAVED_LATITUDE = <?= isset($data_absen[0]->latitude) && $data_absen[0]->latitude !== '' ? json_encode($data_absen[0]->latitude) : 'null' ?>;
    const SAVED_LONGITUDE = <?= isset($data_absen[0]->longitude) && $data_absen[0]->longitude !== '' ? json_encode($data_absen[0]->longitude) : 'null' ?>;
    const CAMERA_PREF_KEY = 'absen_camera_pref_office_v1';

    const cameraPreference = {
        beautyEffect: true,
        mirrorCorrection: true
    };

    function loadCameraPreference() {
        try {
            const raw = localStorage.getItem(CAMERA_PREF_KEY);
            if (!raw) {
                return;
            }

            const parsed = JSON.parse(raw);
            if (typeof parsed.beautyEffect === 'boolean') {
                cameraPreference.beautyEffect = parsed.beautyEffect;
            }
            if (typeof parsed.mirrorCorrection === 'boolean') {
                cameraPreference.mirrorCorrection = parsed.mirrorCorrection;
            }
        } catch (e) {
            // Abaikan jika browser memblokir storage.
        }
    }

    function saveCameraPreference() {
        try {
            localStorage.setItem(CAMERA_PREF_KEY, JSON.stringify(cameraPreference));
        } catch (e) {
            // Abaikan jika browser memblokir storage.
        }
    }

    function applyCameraPreviewStyle() {
        const transformValue = cameraPreference.mirrorCorrection ? 'scaleX(-1)' : 'scaleX(1)';
        const filterValue = cameraPreference.beautyEffect
            ? 'brightness(1.05) contrast(1.04) saturate(1.08)'
            : 'none';

        const mediaEls = document.querySelectorAll('.my_camera video, .my_camera canvas');
        mediaEls.forEach(function(el) {
            el.style.setProperty('transform', transformValue, 'important');
            el.style.setProperty('filter', filterValue, 'important');
        });
    }

    function syncCameraToggleUI() {
        const beautyToggle = document.getElementById('toggle_beauty_effect');
        const mirrorToggle = document.getElementById('toggle_mirror_correction');

        if (beautyToggle) {
            beautyToggle.checked = cameraPreference.beautyEffect;
        }
        if (mirrorToggle) {
            mirrorToggle.checked = cameraPreference.mirrorCorrection;
        }
    }

    function bindCameraToggleEvents() {
        const beautyToggle = document.getElementById('toggle_beauty_effect');
        const mirrorToggle = document.getElementById('toggle_mirror_correction');

        if (beautyToggle) {
            beautyToggle.addEventListener('change', function() {
                cameraPreference.beautyEffect = beautyToggle.checked;
                saveCameraPreference();
                applyCameraPreviewStyle();
            });
        }

        if (mirrorToggle) {
            mirrorToggle.addEventListener('change', function() {
                cameraPreference.mirrorCorrection = mirrorToggle.checked;
                saveCameraPreference();
                applyCameraPreviewStyle();
            });
        }
    }

    function buildMapEmbedUrl(latitude, longitude, zoomLevel) {
        return 'https://www.google.com/maps/embed/v1/place?key=' + MAP_KEY +
            '&q=' + latitude + ',' + longitude +
            '&center=' + latitude + ',' + longitude +
            '&zoom=' + zoomLevel +
            '&maptype=roadmap';
    }

    function setLocationStatus(text) {
        const statusEl = document.getElementById('locationStatus');
        if (statusEl) {
            statusEl.textContent = text;
        }
    }

    function renderLocationPreview(latitude, longitude, accuracy) {
        const mapEl = document.getElementById('showlokasi');
        if (!mapEl) {
            return;
        }

        mapEl.innerHTML = '<iframe loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" src="' +
            buildMapEmbedUrl(latitude, longitude, 19) + '"></iframe>';

        if (accuracy) {
            setLocationStatus('Lokasi terdeteksi. Akurasi GPS sekitar ' + Math.round(accuracy) + ' meter.');
        } else {
            setLocationStatus('Lokasi terdeteksi.');
        }
    }

    function refreshLocation(onSuccess) {
        if (!navigator.geolocation) {
            setLocationStatus('Browser tidak mendukung geolocation.');
            return;
        }

        setLocationStatus('Membaca lokasi GPS...');

        navigator.geolocation.getCurrentPosition(function(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            $('#latitude').val(latitude);
            $('#longitude').val(longitude);
            renderLocationPreview(latitude, longitude, position.coords.accuracy);

            if (typeof onSuccess === 'function') {
                onSuccess(latitude, longitude);
            }
        }, function(error) {
            setLocationStatus('Gagal membaca lokasi. Izinkan akses GPS lalu coba lagi.');
            if (typeof onSuccess === 'function') {
                onSuccess(null, null, error);
            }
        }, {
            enableHighAccuracy: true,
            timeout: 12000,
            maximumAge: 500
        });
    }

    function initCamera() {
        const cameraEl = document.querySelector('.my_camera');
        if (!cameraEl) {
            return;
        }

        if (typeof Webcam === 'undefined') {
            cameraEl.innerHTML = '<div class="camera-placeholder"><i class="bx bx-camera-off"></i>Library kamera tidak termuat. Muat ulang halaman.</div>';
            return;
        }

        Webcam.set({
            width: 400,
            height: 300,
            image_format: 'jpeg',
            jpeg_quality: 92,
            force_flash: false,
            flip_horiz: false
        });

        Webcam.attach('.my_camera');
        setTimeout(applyCameraPreviewStyle, 60);
        setTimeout(applyCameraPreviewStyle, 240);
    }

    function applyBeautyEffect(dataUri, callback) {
        if (!dataUri) {
            callback('');
            return;
        }

        if (!cameraPreference.beautyEffect && !cameraPreference.mirrorCorrection) {
            callback(dataUri);
            return;
        }

        const img = new Image();
        img.onload = function() {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;

            const ctx = canvas.getContext('2d');
            if (!ctx) {
                callback(dataUri);
                return;
            }

            if (cameraPreference.mirrorCorrection) {
                ctx.save();
                ctx.translate(canvas.width, 0);
                ctx.scale(-1, 1);
            }

            ctx.filter = cameraPreference.beautyEffect
                ? 'brightness(1.06) contrast(1.05) saturate(1.08)'
                : 'none';
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

            if (cameraPreference.mirrorCorrection) {
                ctx.restore();
            }

            if (cameraPreference.beautyEffect) {
                ctx.globalCompositeOperation = 'soft-light';
                ctx.fillStyle = 'rgba(255, 226, 206, 0.08)';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.globalCompositeOperation = 'source-over';
            }

            callback(canvas.toDataURL('image/jpeg', 0.92));
        };

        img.onerror = function() {
            callback(dataUri);
        };

        img.src = dataUri;
    }

    function takeSnapshot(callback) {
        if (typeof Webcam === 'undefined' || !document.querySelector('.my_camera')) {
            callback('');
            return;
        }

        Webcam.snap(function(dataUri) {
            const rawPhoto = dataUri || '';
            applyBeautyEffect(rawPhoto, function(enhancedPhoto) {
                const photoData = enhancedPhoto || rawPhoto;
                $('#imageData').val(photoData);
                callback(photoData);
            });
        });
    }

    function getSelectedJenisLokasi() {
        const hiddenLokasi = document.getElementById('jenis_lokasi_input');
        if (hiddenLokasi) {
            return hiddenLokasi.value || 'Kantor';
        }

        const selected = document.querySelector('input[name="jenis_lokasi"]:checked');
        if (selected) {
            return selected.value;
        }

        const hidden = document.querySelector('input[name="jenis_lokasi"]');
        return hidden ? hidden.value : 'Kantor';
    }

    function submitAttendance(latitude, longitude, imageData) {
        Swal.fire({
            title: 'Konfirmasi Absensi',
            text: 'Pastikan Anda sudah berada di lokasi yang benar.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Proses',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (!result.value) {
                return;
            }

            $.ajax({
                method: 'POST',
                url: 'absensi/addPhoto',
                dataType: 'JSON',
                data: {
                    latitude: latitude,
                    longitude: longitude,
                    jenis_lokasi: getSelectedJenisLokasi(),
                    imageData: imageData,
                    csrf_token: token
                },
                success: function(resp) {
                    handleResponse(resp);
                },
                error: function() {
                    Swal.fire('Gagal', 'Terjadi kesalahan saat mengirim data absensi.', 'error');
                }
            });
        });
    }

    function handleAbsenClick() {
        if (IS_SIMULASI_JUMAT && !REAL_TODAY_IS_FRIDAY) {
            Swal.fire('Mode Simulasi', 'Ini mode simulasi tampilan Jumat. Proses absen nyata tetap mengikuti hari asli server.', 'info');
            return;
        }

        refreshLocation(function(latitude, longitude, error) {
            if (error || latitude === null || longitude === null) {
                Swal.fire('Lokasi tidak tersedia', 'Aktifkan GPS dan coba lagi.', 'warning');
                return;
            }

            takeSnapshot(function(imageData) {
                if (!imageData) {
                    Swal.fire('Kamera belum siap', 'Foto absensi belum berhasil diambil. Izinkan kamera lalu coba lagi.', 'warning');
                    return;
                }
                submitAttendance(latitude, longitude, imageData);
            });
        });
    }

    function handleLihatPosisi() {
        const latitude = $('#latitudeuser').val();
        const longitude = $('#longitudeuser').val();

        if (!latitude || !longitude) {
            document.getElementById('dvMap').innerHTML = '';
            Swal.fire('Informasi', 'Lokasi absensi tidak ditemukan.', 'info');
            return;
        }

        $('#main-modal').modal();
        document.getElementById('dvMap').innerHTML = '<iframe style="overflow:hidden;height:100%;width:100%" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" src="' +
            buildMapEmbedUrl(latitude, longitude, 21) + '"></iframe>';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const wfaCheckbox = document.getElementById('check_wfa_mode');
        const jenisLokasiInput = document.getElementById('jenis_lokasi_input');
        const wfaCaption = document.getElementById('wfa_check_caption');

        loadCameraPreference();
        syncCameraToggleUI();
        bindCameraToggleEvents();

        if (wfaCheckbox && jenisLokasiInput) {
            const syncWfaValue = function() {
                const isWfa = wfaCheckbox.checked;
                jenisLokasiInput.value = isWfa ? 'WFA' : 'Kantor';
                if (wfaCaption) {
                    wfaCaption.textContent = 'Status lokasi kerja: ' + (isWfa ? 'WFA' : 'Kantor');
                }
            };

            wfaCheckbox.addEventListener('change', syncWfaValue);
            syncWfaValue();
        }

        initCamera();
        applyCameraPreviewStyle();

        if (HAS_ATTENDANCE_TODAY && SAVED_LATITUDE !== null && SAVED_LONGITUDE !== null) {
            $('#latitude').val(SAVED_LATITUDE);
            $('#longitude').val(SAVED_LONGITUDE);
            renderLocationPreview(SAVED_LATITUDE, SAVED_LONGITUDE, null);
            setLocationStatus('Lokasi dari data absensi yang sudah tersimpan.');
        } else {
            refreshLocation();

            if (HAS_ATTENDANCE_TODAY) {
                setLocationStatus('Lokasi absensi tersimpan tidak ditemukan. Menampilkan lokasi saat ini.');
            }
        }

        $(document).on('click', '.btn-segarkan', function() {
            refreshLocation();
        });

        $(document).on('click', '.btn-absen1', function() {
            handleAbsenClick();
        });

        $(document).on('click', '.btn-lihat-posisi', function() {
            handleLihatPosisi();
        });
    });
})();
</script>
