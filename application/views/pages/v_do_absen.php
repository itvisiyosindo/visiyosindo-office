<header class="page-header">
    <h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
.absen-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.absen-card {
    background: white;
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    margin: 0 auto;
}

.absen-header {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px 12px 0 0;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.absen-header h2 {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.absen-header p {
    margin: 0.5rem 0 0 0;
    opacity: 1;
    font-size: 0.95rem;
    font-weight: 500;
    letter-spacing: 0.3px;
}

.absen-body {
    padding: 2.5rem 2rem;
    background-color: #fff;
    border-radius: 0 0 12px 12px;
}

.absen-section {
    margin-bottom: 2rem;
}

.absen-section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #343a40;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e9ecef;
}

.location-group {
    display: flex;
    gap: 1rem;
    margin: 1.25rem 0;
}

.location-item {
    flex: 1;
}

.location-btn {
    width: 100%;
    padding: 0.85rem 1.5rem;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
    border: 2px solid;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
}

.location-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.location-btn.kantor {
    background-color: #e7f1ff;
    color: #0d6efd;
    border-color: #0d6efd;
}

.location-btn.kantor:hover {
    background-color: #0d6efd;
    color: white;
}

.location-btn.wfa {
    background-color: #e0f7ff;
    color: #17a2b8;
    border-color: #17a2b8;
}

.location-btn.wfa:hover {
    background-color: #17a2b8;
    color: white;
}

.location-btn.disabled {
    background-color: #f8f9fa;
    color: #6c757d;
    border-color: #dee2e6;
    cursor: not-allowed;
    opacity: 0.6;
}

.info-box {
    background-color: #e7f3ff;
    border-left: 4px solid #0d6efd;
    border-radius: 6px;
    padding: 1rem;
    margin: 1rem 0;
    color: #084298;
    font-size: 0.9rem;
}

.info-box i {
    margin-right: 8px;
}

.warning-box {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
    border-radius: 6px;
    padding: 1rem;
    margin: 1rem 0;
    color: #664d03;
    font-size: 0.9rem;
}

.warning-box i {
    margin-right: 8px;
}

.clock-display {
    font-size: 3.5rem;
    font-weight: 700;
    color: #0d6efd;
    margin: 1.5rem 0;
    font-family: 'Courier New', monospace;
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin: 2rem 0;
}

.btn-primary-custom {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 8px;
    border: none;
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
}

.btn-warning-custom {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 8px;
    border: none;
    background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    color: #000;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
}

.btn-warning-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
}

.status-message {
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
    font-weight: 500;
    margin: 1rem 0;
}

.status-success {
    background-color: #d1e7dd;
    color: #0f5132;
    border: 1px solid #badbcc;
}

.status-error {
    background-color: #f8d7da;
    color: #842029;
    border: 1px solid #f5c2c7;
}

.location-preview {
    min-height: 300px;
    background-color: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    border: 2px dashed #dee2e6;
}

.location-preview iframe {
    width: 100%;
    height: 100%;
}

@keyframes pulse {
    0%, 100% { width: 10%; }
    50% { width: 100%; }
}

@media (max-width: 768px) {
    .absen-body {
        padding: 1.5rem 1rem;
    }

    .location-group {
        flex-direction: column;
    }

    .clock-display {
        font-size: 2.5rem;
    }

    .action-buttons {
        grid-template-columns: 1fr;
    }

    .absen-header h2 {
        font-size: 1.25rem;
    }
    
    .absen-section-title {
        font-size: 1rem;
    }
    
    [style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}

/* Override Bootstrap button styling */
.btn-primary-custom, .btn-warning-custom {
    width: 100%;
    border: none;
    padding: 0.85rem 1.5rem;
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
}

/* Ensure buttons don't get overridden by bootstrap */
.btn-primary-custom i, .btn-warning-custom i {
    font-size: 1.1rem;
}
</style>

<div class="absen-container">
    <div class="col-xl-8 mb-8 mb-xl-0" style="margin: auto; max-width: 900px;">
        <div class="absen-card">
            <!-- Card Header -->
            <div class="absen-header">
                <h2>
                    <i class="bx bx-log-in-circle"></i>
                    Formulir Absensi Karyawan
                </h2>
                <p style="opacity: 1; font-weight: 500; font-size: 1.05rem;">Silakan absen untuk mencatat kehadiran Anda</p>
            </div>

            <!-- Card Body -->
            <div class="absen-body">
            <!-- Lokasi Preview Section -->
            <?php
            if (isset($data_absen[0]->waktu_absen)) {
                echo '<div class="text-center mt-0" id="showlokasi" hidden></div>';
            } else {
                echo '<div class="absen-section" id="showlokasi"></div>';
            }
            ?>

            <!-- Status Messages -->
            <?php if (isset($data_absen_izin[0]->waktu_absen)): ?>
                <div class="status-message status-success">
                    <i class="fas fa-check-circle"></i>
                    <strong> Anda Absen <?= $data_absen_izin[0]->type_absen ?></strong>
                </div>
            <?php endif; ?>

            <?php if (isset($data_absen[0]->waktu_absen)): ?>
                <div class="status-message <?= ($data_absen[0]->status_absen == 'terlambat') ? 'status-error' : 'status-success' ?>">
                    <i class="<?= ($data_absen[0]->status_absen == 'terlambat') ? 'fas fa-times-circle' : 'fas fa-check-circle' ?>"></i>
                    <?php if ($data_absen[0]->status_absen == 'terlambat'): ?>
                        <strong> Anda Terlambat Absen <?= $data_absen[0]->jenis_absen ?></strong>
                    <?php elseif ($data_absen[0]->status_absen == 'tepat_waktu'): ?>
                        <strong> Anda Tepat Waktu Absen <?= $data_absen[0]->jenis_absen ?></strong>
                    <?php else: ?>
                        <strong> Anda Absen <?= $data_absen[0]->type_absen ?></strong>
                    <?php endif; ?>
                    <br>
                    <span style="font-size: 0.9rem;">Waktu: <?= $data_absen[0]->waktu_absen ?></span>
                </div>
            <?php endif; ?>

            <!-- Camera & Clock Section -->
            <div class="absen-section">
                <div class="absen-section-title">
                    <i class="bx bx-camera"></i>
                    Capture Foto & Waktu
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <!-- Camera Preview -->
                    <div>
                        <label style="font-weight: 600; font-size: 0.9rem; color: #343a40; display: block; margin-bottom: 0.75rem;">
                            <i class="bx bx-camera-home"></i> Camera Preview
                        </label>
                        <div style="background-color: #000; border-radius: 8px; overflow: hidden; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; border: 2px solid #dee2e6;">
                            <video id="camera-preview" style="width: 100%; height: 100%; object-fit: cover;"></video>
                            <canvas id="camera-canvas" style="width: 100%; height: 100%; display: none;"></canvas>
                            <div id="camera-placeholder" style="color: #6c757d; text-align: center; display: none;">
                                <i class="bx bx-camera" style="font-size: 2.5rem; color: #adb5bd;"></i>
                                <p style="font-size: 0.85rem; margin-top: 0.5rem;" id="camera-status">Membuka Kamera...</p>
                            </div>
                        </div>
                        <div style="margin-top: 0.75rem; display: flex; gap: 0.5rem;">
                            <button type="button" id="btn-start-camera" class="btn-primary-custom" style="flex: 1; padding: 0.65rem; font-size: 0.85rem;">
                                <i class="bx bx-stop"></i> Tutup Kamera
                            </button>
                            <button type="button" id="btn-test-capture" class="btn-primary-custom" style="flex: 1; padding: 0.65rem; font-size: 0.85rem;">
                                <i class="bx bx-camera"></i> Test Capture
                            </button>
                        </div>
                    </div>
                    
                    <!-- Clock Display -->
                    <div>
                        <label style="font-weight: 600; font-size: 0.9rem; color: #343a40; display: block; margin-bottom: 0.75rem;">
                            <i class="bx bx-time"></i> Waktu Saat Ini
                        </label>
                        <div style="background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%); border-radius: 8px; padding: 2rem; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 200px; border: 2px solid #dee2e6;">
                            <div class="clock-display" id="clock" style="font-size: 2.75rem; margin-bottom: 0.5rem;">00:00:00</div>
                            <div id="date-display" style="font-size: 0.9rem; color: #6c757d; font-weight: 500;">
                                Senin, 6 April 2026
                            </div>
                            <div style="margin-top: 1rem; width: 100%; height: 4px; background-color: #dee2e6; border-radius: 2px;">
                                <div style="height: 100%; background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%); border-radius: 2px; animation: pulse 1s infinite;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?= form_open('absensi/add', array('id' => 'absen-form', 'autocomplete' => 'off')); ?>
        <div class="text-center">
            <input type="hidden" name="pengguna_id" id="pengguna_id" value="<?= encrypt(sessPenggunaId()) ?>">

            <!-- Lokasi Kerja Section - WFA Feature -->
            <?php
            $wfa_system_active = false;
            $today_is_friday = date('l') == 'Friday'; // Cek apakah hari ini Jumat

            // Cek WFA system active
            if (isset($config) && count($config) > 0 && isset($config[0]->is_wfa_active)) {
                $wfa_system_active = $config[0]->is_wfa_active == 1;
            }
            ?>

            <?php if ($wfa_system_active): ?>
                <div class="absen-section">
                    <div class="absen-section-title">
                        <i class="bx bx-map"></i>
                        Lokasi Kerja
                    </div>

                    <?php if (!$today_is_friday): ?>
                        <!-- Tampilkan pesan jika bukan Jumat -->
                        <div class="warning-box">
                            <i class="bx bx-info-circle"></i>
                            <strong>Perhatian:</strong> Fitur WFA hanya tersedia pada hari <strong>Jumat</strong>. Hari ini Anda akan menggunakan absensi <strong>Kantor</strong>.
                        </div>

                        <!-- Lokasi otomatis Kantor untuk hari Senin-Kamis & weekend -->
                        <input type="hidden" name="jenis_lokasi" id="jenis_lokasi_legacy" value="Kantor">
                        <div class="location-group">
                            <div class="location-item">
                                <button type="button" class="location-btn kantor disabled" disabled>
                                    <i class="bx bx-building-house"></i>
                                    <span>Bekerja di Kantor</span>
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="jenis_lokasi" id="jenis_lokasi_legacy" value="Kantor">
                        <div class="info-box">
                            <i class="bx bx-info-circle"></i>
                            Hari ini Jumat dan konfigurasi WFA aktif. Centang checkbox jika Anda WFA.
                        </div>
                        <div class="text-left" style="max-width: 420px; margin: 0 auto;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="lokasi_wfa_check" value="1">
                                <label class="form-check-label" for="lokasi_wfa_check" style="font-weight: 600; color: #0d6efd;">
                                    Checklist jika hari ini WFA (tidak dicentang = Kantor)
                                </label>
                            </div>
                        </div>
                        <div class="info-box" id="wfa_status_legacy">
                            <i class="bx bx-map-pin"></i>
                            Status lokasi kerja saat ini: <strong>Kantor</strong>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <input type="hidden" name="jenis_lokasi" id="jenis_lokasi_legacy" value="Kantor">
            <?php endif; ?>

            <!-- 12:00:00 dan 17:00:00 master data -->
            <?php
            $pulang = date('l') == 'Saturday' ? date($config[0]->jam_keluar_sabtu) : date($config[0]->jam_keluar); ?>
            <!-- Action Buttons Section -->
            <div class="absen-section">
                <div class="action-buttons">
                    <?php if (isset($data_absen[0]->waktu_absen)): ?>
                        <!-- Already absented - Show view position button -->
                        <button type="button" class="btn-primary-custom btn-lihat-posisi">
                            <i class="bx bx-map"></i>
                            Lihat Lokasi Absensi
                        </button>
                    <?php else: ?>
                        <!-- Not yet absented - Show absen button -->
                        <?php if (sessPenggunaId() == 94) {
                            $pulangA = date("07:00:00");
                            $pulangB = date("11:00:00");
                            $masukA = date("15:00:00");
                            $masukB = date("23:00:00");
                            $pulangC = date("H:i:s");

                            if ($pulangC > $masukA && $pulangC < $masukB) {
                                if (!isset($data_absen_izin[0]->waktu_absen)) {
                        ?>
                                    <button type="button" class="btn-primary-custom btn-absen1">
                                        <i class="bx bx-log-in"></i>
                                        Absen Masuk
                                    </button>
                        <?php }
                            } else {
                        ?>
                                    <button type="button" class="btn-warning-custom btn-absen1">
                                        <i class="bx bx-log-out"></i>
                                        Absen Keluar
                                    </button>
                        <?php }
                        } else {
                            $pulang = date('l') == 'Saturday' ? date($config[0]->jam_keluar_sabtu) : date($config[0]->jam_keluar);

                            if (date("H:i:s") < $pulang) {
                                if (!isset($data_absen_izin[0]->waktu_absen)) {
                        ?>
                                    <button type="button" class="btn-primary-custom btn-absen1">
                                        <i class="bx bx-log-in"></i>
                                        Absen Masuk
                                    </button>
                        <?php }
                            } else {
                        ?>
                                    <button type="button" class="btn-warning-custom btn-absen1">
                                        <i class="bx bx-log-out"></i>
                                        Absen Keluar
                                    </button>
                        <?php }
                        } ?>

                        <button type="button" class="btn-primary-custom btn-segarkan">
                            <i class="bx bx-map-pin"></i>
                            Update Lokasi
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <?= form_close(); ?>

            <!-- Info Footer -->
            <div style="background-color: #f0f2f5; border-radius: 8px; padding: 1rem; margin-top: 2rem; font-size: 0.85rem; color: #6c757d;">
                <i class="bx bx-info-circle"></i>
                <strong>Catatan:</strong> Absensi Izin terintegrasi dengan Surat Izin resmi. Untuk membuat izin, silakan buat surat izin terlebih dahulu.
            </div>
            </div>
        </div>
    </div>
</div>

    <div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-dark text-light">
                    <h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Lokasi Absen</h4>
                    <button type="button" class="close" style="color:white;margin: -1px" data-dismiss="modal" aria-label="Close"><i class="far fa-times-circle"></i></button>
                </div>
                <div class="modal-body">
                    <div id="dvMap" style="height: 700px"></div><br>
                    <div class="text-right">
                        <?php if (isset($data_absen[0]->latitude)) { ?>
                            <input type="hidden" id='longitudeuser' value="<?= $data_absen[0]->longitude ?>">
                            <input type="hidden" id='latitudeuser' value="<?= $data_absen[0]->latitude ?>">
                            <input type="hidden" id='longitude'>
                            <input type="hidden" id='latitude'>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Manual Input Lokasi (Fallback untuk Testing) -->
    <div id="manual-location-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bx bx-test-tube"></i> Input Manual Lokasi (Mode Testing)
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle"></i>
                        <strong>Perhatian:</strong> Mode fallback karena GPS tidak tersedia. 
                        Gunakan koordinat testing atau izinkan akses GPS di browser.
                    </div>

                    <div class="form-group">
                        <label for="manual_latitude"><strong>Latitude</strong></label>
                        <input type="number" class="form-control" id="manual_latitude" 
                               placeholder="Contoh: -6.371706" step="0.000001" value="0.4588367">
                        <small class="form-text text-muted">Kantor: 0.4588367</small>
                    </div>

                    <div class="form-group">
                        <label for="manual_longitude"><strong>Longitude</strong></label>
                        <input type="number" class="form-control" id="manual_longitude" 
                               placeholder="Contoh: 106.910909" step="0.000001" value="101.4262010">
                        <small class="form-text text-muted">Kantor: 101.4262010</small>
                    </div>

                    <div class="form-group">
                        <label><strong>Template Koordinat:</strong></label>
                        <div class="btn-group-vertical w-100" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary text-start template-location" 
                                    data-lat="0.4588367" data-lng="101.4262010">
                                📍 VYM Kantor Pusat
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary text-start template-location" 
                                    data-lat="0.4447627" data-lng="101.4202365">
                                📦 VYM Gudang Pusat
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary text-start template-location" 
                                    data-lat="-6.371706" data-lng="106.910909">
                                🏢 Jakarta
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary text-start template-location" 
                                    data-lat="-7.822234" data-lng="110.442379">
                                🏢 Yogyakarta
                            </button>
                        </div>
                    </div>

                    <div id="manual-map" style="height: 300px; margin-top: 1rem; border: 1px solid #ddd; border-radius: 4px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="btn-confirm-manual-location">
                        <i class="bx bx-check"></i> Gunakan Koordinat Ini
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Absen Izin -->
    <div id="absen-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-dark text-light">
                    <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Absen Izin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
                <div class="modal-body">
                    <div>
                        <div class="form-group">
                            <label for="nama" class="form-control-label">Jenis Izin <span class="text-danger">*</span> :</label>
                            <select name="jenis_absen" id="" class="form-control" required>
                                <option value=""> --- Pilih Jenis Izin ---</option>
                                <option value="sakit">Sakit</option>
                                <option value="izin">Izin Urusan Pribadi</option>
                                <option value="cuti">Cuti</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="username" class="form-control-label">Keterangan Izin <span class="text-danger">*</span> :</label>
                            <textarea name="keterangan" class="form-control" id="keterangan" cols="30" rows="2" placeholder="Keterangan Izin" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="waktu" class="form-control-label">Lama Absen <span class="text-danger">*</span> :</label>
                            <div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
                                <span class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="text" class="form-control" id="start" name="start" required>
                                <span class="input-group-text border-start-0 border-end-0 rounded-0">
                                    to
                                </span>
                                <input type="text" class="form-control" id="end" name="end" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="link_surat" class="form-control-label">Link File Surat <span class="text-danger">*</span> :</label>
                            <input type="text" class="form-control" id="link_surat" name="link_surat" placeholder="Link file surat izin" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="is_aktif"></div>
                    <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success btn-save">Simpan</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFycbDEoOn8GPKQ1_fij6S1e1UpRZgKJo"></script>
    <script>
        // ===== CLOCK & DATE FUNCTION (Perbaikan Total) =====
        function updateClockDisplay() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            
            const clockEl = document.getElementById('clock');
            if (clockEl) {
                clockEl.innerHTML = `${h}:${m}:${s}`;
            }
            
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = new Date().toLocaleDateString('id-ID', options);
            const dateEl = document.getElementById('date-display');
            if (dateEl) {
                dateEl.innerHTML = dateStr.charAt(0).toUpperCase() + dateStr.slice(1);
            }
        }
        
        // Start clock immediately, don't wait for DOMContentLoaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                updateClockDisplay();
                setInterval(updateClockDisplay, 1000);
            });
        } else {
            updateClockDisplay();
            setInterval(updateClockDisplay, 1000);
        }
        
        // ===== CHECK ATTENDANCE TIME WINDOW =====
        function checkAttendanceTimeWindow() {
            const config = <?php echo json_encode($config[0] ?? null, JSON_UNESCAPED_SLASHES); ?>;
            
            if (!config || !config.jam_masuk || !config.jam_keluar) {
                console.log('[TIME] No config, allowing access');
                return true;
            }
            
            const now = new Date();
            const currentTime = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;
            const isOpen = currentTime >= config.jam_masuk && currentTime <= config.jam_keluar;
            
            console.log(`[TIME-CHECK] Current: ${currentTime}, Window: ${config.jam_masuk}-${config.jam_keluar}, Allowed: ${isOpen}`);
            
            return isOpen;
        }
        
        // ===== DISPLAY TIME STATUS =====
        document.addEventListener('DOMContentLoaded', function() {
            console.log('[INIT] DOM Content Loaded');

            const wfaCheckboxLegacy = document.getElementById('lokasi_wfa_check');
            const jenisLokasiLegacy = document.getElementById('jenis_lokasi_legacy');
            const wfaStatusLegacy = document.getElementById('wfa_status_legacy');

            if (wfaCheckboxLegacy && jenisLokasiLegacy) {
                const syncLegacyWfa = function() {
                    const isWfa = wfaCheckboxLegacy.checked;
                    jenisLokasiLegacy.value = isWfa ? 'WFA' : 'Kantor';
                    if (wfaStatusLegacy) {
                        wfaStatusLegacy.innerHTML = '<i class="bx bx-map-pin"></i>Status lokasi kerja saat ini: <strong>' + (isWfa ? 'WFA' : 'Kantor') + '</strong>';
                    }
                };

                wfaCheckboxLegacy.addEventListener('change', syncLegacyWfa);
                syncLegacyWfa();
            }
            
            // Check attendance window on load
            const canAttend = checkAttendanceTimeWindow();
            if (!canAttend) {
                const attendanceForm = document.getElementById('absen-form');
                if (attendanceForm) {
                    const message = document.createElement('div');
                    message.className = 'alert alert-danger';
                    message.innerHTML = `<i class="bx bx-time"></i> <strong>SISTEM TERTUTUP:</strong> Absensi hanya bisa dilakukan pada jam kerja yang ditentukan.`;
                    attendanceForm.parentElement.insertBefore(message, attendanceForm);
                    attendanceForm.style.display = 'none';
                    console.log('[TIME] Form diberitahu: outside working hours');
                }
            }
            
            // Initialize camera
            initCamera();
            
            // Initialize map
            setTimeout(function() {
                initMap();
            }, 500);
            
            // Setup all event handlers
            setupEventHandlers();
            
            console.log('[INIT] Initialization complete');
        });
        // ===== CAMERA FUNCTION =====
        // Global variables
        var gManualLocationForAbsensi = false;
        let stream = null;
        let capturedImage = null;
        
        // Map initialization function
        function initMap() {
            const showlokasiEl = document.getElementById("showlokasi");
            if (!showlokasiEl) return;
            
            showlokasiEl.innerHTML = '';
            $('#latitude').val(null);
            $('#longitude').val(null);

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, errorCallback, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 1
                });
            } else {
                showlokasiEl.innerHTML = "Geolocation tidak didukung oleh browser Anda.";
            }
        }

        function showPosition(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            $('#latitude').val(latitude);
            $('#longitude').val(longitude);
            const showlokasiEl = document.getElementById("showlokasi");
            if (showlokasiEl) {
                showlokasiEl.innerHTML = `<div><iframe style='overflow:hidden;height:100%;width:100%' loading='lazy' allowfullscreen referrerpolicy='no-referrer-when-downgrade' src='https://www.google.com/maps/embed/v1/place?key=AIzaSyAFycbDEoOn8GPKQ1_fij6S1e1UpRZgKJo&q=${latitude},${longitude}&center=${latitude},${longitude}&zoom=20&maptype=roadmap'></iframe><br/>Akurasi: ${position.coords.accuracy}m</div>`;
            }
        }

        function errorCallback(error) {
            console.warn('Geolocation Error:', error);
            $('#manual-location-modal').modal('show');
        }
        
        // ===== CAMERA FUNCTION =====
        
        function initCamera() {
            const video = document.getElementById('camera-preview');
            const canvas = document.getElementById('camera-canvas');
            const btnStart = document.getElementById('btn-start-camera');
            const btnTest = document.getElementById('btn-test-capture');
            const placeholder = document.getElementById('camera-placeholder');
            
            // Auto-start camera on init
            function startCamera() {
                console.log('[CAMERA] Attempting to start camera...');
                navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: false
                }).then(function(mediaStream) {
                    console.log('[CAMERA] Permission granted, stream obtained');
                    stream = mediaStream;
                    video.srcObject = mediaStream;
                    video.play();
                    video.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                    btnStart.innerHTML = '<i class="bx bx-stop"></i> Tutup Kamera';
                    console.log('[CAMERA] Camera started successfully');
                }).catch(function(err) {
                    console.error('[CAMERA] Error details:', err);
                    video.style.display = 'none';
                    if (placeholder) placeholder.style.display = 'flex';
                    const statusEl = document.getElementById('camera-status');
                    if (statusEl) {
                        statusEl.textContent = `Kamera Error: ${err.name} (Gunakan localhost/HTTPS)`;
                        statusEl.style.color = '#dc3545';
                    }
                });
            }
            
            console.log('[CAMERA] initCamera() called, video element:', video ? 'OK' : 'MISSING');
            // Start camera immediately
            if (video) {
                setTimeout(startCamera, 500);
            } else {
                console.error('[CAMERA] Video element not found!');
            }
            
            // Toggle button - stop/start camera
            btnStart.addEventListener('click', function() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                    video.style.display = 'none';
                    if (placeholder) placeholder.style.display = 'flex';
                    const statusEl = document.getElementById('camera-status');
                    if (statusEl) statusEl.textContent = 'Kamera Ditutup';
                    btnStart.innerHTML = '<i class="bx bx-play"></i> Buka Kamera';
                } else {
                    startCamera();
                }
            });
            
            // Test capture button - manual capture for testing
            if (btnTest) {
                btnTest.addEventListener('click', function() {
                    if (stream && video.style.display !== 'none') {
                        const ctx = canvas.getContext('2d');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        ctx.drawImage(video, 0, 0);
                        capturedImage = canvas.toDataURL('image/jpeg');
                        Swal.fire('Sukses!', 'Test foto berhasil diambil.', 'success');
                    } else {
                        Swal.fire('Error!', 'Kamera belum aktif atau recording.', 'error');
                    }
                });
            }
        }
        
        // Function untuk auto capture saat absen
        function captureForAbsen() {
            return new Promise(function(resolve, reject) {
                const video = document.getElementById('camera-preview');
                const canvas = document.getElementById('camera-canvas');
                
                if (stream && video.style.display !== 'none') {
                    try {
                        const ctx = canvas.getContext('2d');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        ctx.drawImage(video, 0, 0);
                        capturedImage = canvas.toDataURL('image/jpeg');
                        console.log('Photo captured for attendance');
                        resolve(capturedImage);
                    } catch(err) {
                        console.error('Capture error:', err);
                        resolve(null);
                    }
                } else {
                    console.warn('Camera not available for capture');
                    resolve(null);
                }
            });
        }
        
        // Event handlers inside DOMContentLoaded
        function setupEventHandlers() {
                // Capture foto terlebih dahulu (Absen button click)
                $(document).on('click', '.btn-absen1', function() {
                    captureForAbsen().then(function(photoData) {
                        // Konfirmasi posisi dengan warning
                        Swal.fire({
                            title: 'Konfirmasi Absensi',
                            html: 'Apakah Anda sudah di posisi yang benar?<br><small>Foto telah diambil secara otomatis.</small>',
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Proses Absensi',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#0d6efd',
                            cancelButtonColor: '#6c757d'
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                // Get GPS location
                                navigator.geolocation.getCurrentPosition(function(p) {
                                    var latitude = p.coords.latitude;
                                    var longitude = p.coords.longitude;

                                    $.ajax({
                                        method: 'POST',
                                        url: 'absensi/add',
                                        dataType: 'JSON',
                                        data: {
                                            latitude: latitude,
                                            longitude: longitude,
                                            jenis_lokasi: $('input[name="jenis_lokasi"]:checked').val() || $('input[name="jenis_lokasi"]').val() || 'Kantor',
                                            csrf_token: token
                                        },
                                        success: function(resp) {
                                            handleResponse(resp)
                                        },
                                        error: function(err) {
                                            console.error('Attendance error:', err);
                                            Swal.fire('Error!', 'Gagal memproses absensi.', 'error');
                                        }
                                    });
                                }, function(error) {
                                    console.warn('GPS Error:', error);
                                    // Fallback ke manual location input
                                    gManualLocationForAbsensi = true;
                                    Swal.fire({
                                        title: 'GPS Tidak Tersedia',
                                        html: 'GPS tidak bisa diakses. Gunakan input manual koordinat?',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Ya, Input Manual',
                                        cancelButtonText: 'Tidak'
                                    }).then(function(result) {
                                        if (result.isConfirmed) {
                                            $('#manual-location-modal').modal('show');
                                        } else {
                                            gManualLocationForAbsensi = false;
                                        }
                                    });
                                });
                            }
                        });
                    });
                });

            $(document).on('click', '.btn-lihat-posisi', function() {
                /*
                $('#main-modal').modal()
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(p) {
                        var latitude = $('#latitude').val();
                        var longitude = $('#longitude').val();
                        var LatLng = new google.maps.LatLng(latitude, longitude);
                        console.log(LatLng);
                        var mapOptions = {
                            center: LatLng,
                            zoom: 19,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        console.log(mapOptions);
                        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
                        var marker = new google.maps.Marker({
                            position: LatLng,
                            map: map,
                            title: "Latitude: " + p.coords.latitude + "     Longitude: " + p.coords.longitude
                        });
                        google.maps.event.addListener(marker, "click", function(e) {
                            var infoWindow = new google.maps.InfoWindow();
                            infoWindow.setContent(marker.title);
                            infoWindow.open(map, marker);
                        });
                    });
                } else {
                    alert('Geo Location feature is not supported in this browser.');
                }*/
                var latitude = $('#latitudeuser').val();
                var longitude = $('#longitudeuser').val();
                if ((latitude != null) && (longitude != null)) {
                    $('#main-modal').modal()
                    document.getElementById("dvMap").innerHTML = "<iframe style='overflow:hidden;height:100%;width:100%' loading='lazy' allowfullscreen referrerpolicy='no-referrer-when-downgrade' src='https://www.google.com/maps/embed/v1/place?key=AIzaSyAFycbDEoOn8GPKQ1_fij6S1e1UpRZgKJo &q=" + latitude + "," + longitude + " &zoom=21 &maptype=roadmap'></iframe>"
                } else {
                    document.getElementById("dvMap").innerHTML = "";
                }
            })

            //cek current position
            $(document).on('click', '.btn-cek', function() {
                $('#main-modal').modal()
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(p) {
                        var LatLng = new google.maps.LatLng(p.coords.latitude, p.coords.longitude);
                        console.log(LatLng);

                        var mapOptions = {
                            center: LatLng,
                            zoom: 19,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        console.log(mapOptions);
                        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
                        var marker = new google.maps.Marker({
                            position: LatLng,
                            map: map,
                            title: "Latitude: " + p.coords.latitude + "Longitude: " + p.coords.longitude
                        });
                        google.maps.event.addListener(marker, "click", function(e) {
                            var infoWindow = new google.maps.InfoWindow();
                            infoWindow.setContent(marker.title);
                            infoWindow.open(map, marker);
                        });
                    });
                } else {
                    alert('Geo Location feature is not supported in this browser.');
                }
            })

            $('#btn-show-add-form').click(function() {
                $('.form-control').val(null)
                $('#absen-modal #modal-form').attr('action', 'absensi/addAbsenIzin')
                $('#absen-modal').modal()
            })

            $(document).on('click', '.btn-segarkan', function() {
                //window.open('https://maps.google.com/')
                //var latitude = $('#latitude').val();
                //var longitude = $('#longitude').val();
                //document.getElementById("showlokasi").innerHTML = "<iframe style='overflow:hidden;height:100%;width:100%' loading='lazy' allowfullscreen referrerpolicy='no-referrer-when-downgrade' src='https://www.google.com/maps/embed/v1/place?key=AIzaSyAFycbDEoOn8GPKQ1_fij6S1e1UpRZgKJo &q="+ latitude + "," + longitude + " &zoom=21 &maptype=roadmap'></iframe>"
                initMap()

            })

            // ===== Manual Location Fallback Handlers =====
            $(document).on('click', '.template-location', function() {
                var lat = $(this).data('lat');
                var lng = $(this).data('lng');
                $('#manual_latitude').val(lat);
                $('#manual_longitude').val(lng);
                
                // Update preview map
                updateManualMap(lat, lng);
                
                // Highlight selected button
                $('.template-location').removeClass('active');
                $(this).addClass('active');
            });

            function updateManualMap(lat, lng) {
                var mapElement = document.getElementById('manual-map');
                if (!mapElement) return;
                
                mapElement.innerHTML = `
                    <iframe style="overflow:hidden;height:100%;width:100%" 
                        loading="lazy" 
                        allowfullscreen 
                        referrerpolicy="no-referrer-when-downgrade" 
                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAFycbDEoOn8GPKQ1_fij6S1e1UpRZgKJo
                        &q=${lat},${lng}
                        &center=${lat},${lng}
                        &zoom=18
                        &maptype=roadmap">
                    </iframe>
                `;
            }

            $(document).on('change', '#manual_latitude, #manual_longitude', function() {
                var lat = parseFloat($('#manual_latitude').val());
                var lng = parseFloat($('#manual_longitude').val());
                if (lat && lng && !isNaN(lat) && !isNaN(lng)) {
                    updateManualMap(lat, lng);
                }
            });

            $(document).on('click', '#btn-confirm-manual-location', function() {
                var latitude = parseFloat($('#manual_latitude').val());
                var longitude = parseFloat($('#manual_longitude').val());
                
                if (!latitude || !longitude || isNaN(latitude) || isNaN(longitude)) {
                    Swal.fire('Error!', 'Silakan input latitude dan longitude yang valid', 'error');
                    return;
                }
                
                // Set nilai ke hidden input
                $('#latitude').val(latitude);
                $('#longitude').val(longitude);
                
                // Update showlokasi display
                document.getElementById("showlokasi").innerHTML = `
                    <div>
                        <div class="alert alert-success">
                            <i class="bx bx-check-circle"></i> Lokasi berhasil diset: ${latitude.toFixed(6)}, ${longitude.toFixed(6)}
                        </div>
                        <iframe style="overflow:hidden;height:100%;width:100%" 
                            loading="lazy" 
                            allowfullscreen 
                            referrerpolicy="no-referrer-when-downgrade" 
                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAFycbDEoOn8GPKQ1_fij6S1e1UpRZgKJo
                            &q=${latitude},${longitude}
                            &center=${latitude},${longitude}
                            &zoom=18
                            &maptype=roadmap">
                        </iframe>
                    </div>
                `;
                
                $('#manual-location-modal').modal('hide');
                Swal.fire('Sukses!', 'Koordinat lokasi berhasil digunakan untuk absensi', 'success');
                
                // Jika dalam flow absensi, langsung submit
                if (gManualLocationForAbsensi) {
                    setTimeout(function() {
                        Swal.fire({
                            title: 'Apakah anda sudah di posisi yg tepat?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Sudah',
                            cancelButtonText: 'Belum'
                        }).then(function(result) {
                            if (result.value) {
                                $.ajax({
                                    method: 'POST',
                                    url: 'absensi/add',
                                    dataType: 'JSON',
                                    data: {
                                        latitude: latitude,
                                        longitude: longitude,
                                        jenis_lokasi: $('input[name="jenis_lokasi"]:checked').val() || $('input[name="jenis_lokasi"]').val() || 'Kantor',
                                        csrf_token: token
                                    },
                                    success: function(resp) {
                                        handleResponse(resp)
                                    }
                                })
                            }
                            gManualLocationForAbsensi = false;
                        });
                    }, 500);
                }
            });
        }
        
        function goBack() {
            window.history.back();
        }
    </script>

    <style>
        .btn-group-toggle .btn {
            border-radius: 0.375rem;
            margin-right: 5px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-group-toggle .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-group-toggle .btn.active {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-outline-info.active {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white !important;
        }

        .btn-outline-primary.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white !important;
        }

        .form-label {
            margin-bottom: 0.75rem;
            color: #333;
        }

        .btn-group.btn-group-toggle {
            display: flex;
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .btn-group.btn-group-toggle .btn {
            border: 1px solid #dee2e6;
            flex: 1;
            margin-right: 0;
            border-right: none;
        }

        .btn-group.btn-group-toggle .btn:last-child {
            border-right: 1px solid #dee2e6;
        }
    </style>
    </script>