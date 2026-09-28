<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <button class="btn btn-success btn-sm" id="btn-refresh-all">
                        <i class="fas fa-sync-alt"></i> Refresh Semua
                    </button>
                </div>
                <h2 class="card-title">
                    <i class="fab fa-whatsapp text-success"></i> WhatsApp API Configuration
                </h2>
                <p class="card-subtitle">
                    Kelola dan monitor device WhatsApp yang terhubung dengan WhacCenter API
                </p>
            </header>
            <div class="card-body">

                <!-- Alert Info -->
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle fa-lg mr-3 mt-1"></i>
                        <div>
                            <strong>Panduan Penggunaan:</strong>
                            <ul class="mb-0 mt-2 pl-3">
                                <li><strong>Cek Status</strong> - Memastikan device WhatsApp terhubung dengan server</li>
                                <li><strong>Relog</strong> - Refresh koneksi jika device bermasalah (tetap connected, tanpa scan QR ulang)</li>
                                <li><strong>Scan QR</strong> - Menampilkan QR Code untuk menghubungkan ulang WhatsApp</li>
                                <li><strong>Test Kirim</strong> - Mengirim pesan test untuk memastikan device berfungsi</li>
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Loading State -->
                <div id="loading-devices" class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x text-success"></i>
                    <p class="mt-3">Memuat daftar device dari API...</p>
                </div>

                <!-- Error State -->
                <div id="error-devices" class="text-center py-5" style="display: none;">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                    <p class="mt-3 text-danger" id="error-message">Gagal memuat daftar device</p>
                    <button class="btn btn-primary" id="btn-retry-load">
                        <i class="fas fa-redo"></i> Coba Lagi
                    </button>
                </div>

                <!-- Empty State -->
                <div id="empty-devices" class="text-center py-5" style="display: none;">
                    <i class="fab fa-whatsapp fa-3x text-muted"></i>
                    <p class="mt-3 text-muted">Tidak ada device yang terdaftar</p>
                </div>

                <!-- Device Cards Container -->
                <div class="row" id="device-cards" style="display: none;">
                    <!-- Cards will be populated by JavaScript -->
                </div>

            </div>
        </section>
    </div>
</div>

<!-- Modal QR Code -->
<div class="modal fade" id="modal-qrcode" tabindex="-1" role="dialog" aria-labelledby="modal-qrcode-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modal-qrcode-label">
                    <i class="fas fa-qrcode"></i> Scan QR Code
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Device Name -->
                <div class="alert alert-light border mb-3">
                    <small class="text-muted">Device:</small>
                    <div class="font-weight-bold" id="qr-device-name">-</div>
                </div>

                <!-- QR Loading -->
                <div id="qr-loading" class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x text-success"></i>
                    <p class="mt-3">Memuat QR Code...</p>
                </div>

                <!-- QR Content -->
                <div id="qr-content" style="display: none;" class="text-center">
                    <div class="mb-3 p-3 bg-white border rounded">
                        <img id="qr-image" src="" alt="QR Code" class="img-fluid" style="max-width: 280px;">
                    </div>

                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <strong>QR Code akan expired dalam 45 detik!</strong>
                        <div id="qr-countdown" class="mt-1">Waktu tersisa: <span class="badge badge-warning">45</span> detik</div>
                    </div>

                    <div class="card bg-light">
                        <div class="card-body py-2">
                            <h6 class="mb-2"><i class="fab fa-whatsapp text-success"></i> Cara Scan:</h6>
                            <ol class="text-left small mb-0 pl-3">
                                <li>Buka <strong>WhatsApp</strong> di HP Anda</li>
                                <li>Tap menu <strong>⋮</strong> (titik tiga) di kanan atas</li>
                                <li>Pilih <strong>Linked Devices</strong> / Perangkat Tertaut</li>
                                <li>Tap <strong>Link a Device</strong> / Tautkan Perangkat</li>
                                <li>Arahkan kamera ke QR Code di atas</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- QR Connected -->
                <div id="qr-connected" style="display: none;" class="text-center py-4">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h5 class="text-success">Device Sudah Terhubung!</h5>
                    <p class="text-muted mb-0">Tidak perlu scan QR Code lagi</p>
                </div>

                <!-- QR Error -->
                <div id="qr-error" style="display: none;" class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p class="text-danger mb-2" id="qr-error-message">Gagal memuat QR Code</p>
                    <small class="text-muted">Coba klik tombol "Refresh QR" atau "Relog Device"</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
                <button type="button" class="btn btn-warning" id="btn-relog-from-qr" style="display: none;">
                    <i class="fas fa-redo"></i> Relog Device
                </button>
                <button type="button" class="btn btn-success" id="btn-refresh-qr">
                    <i class="fas fa-sync-alt"></i> Refresh QR
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Send Test -->
<div class="modal fade" id="modal-send-test" tabindex="-1" role="dialog" aria-labelledby="modal-send-test-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal-send-test-label">
                    <i class="fas fa-paper-plane"></i> Test Kirim Pesan WhatsApp
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Device Info -->
                <div class="alert alert-light border mb-3">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Device:</small>
                            <div class="font-weight-bold" id="test-device-name">-</div>
                        </div>
                        <div class="col-6 text-right">
                            <small class="text-muted">Status:</small>
                            <div id="test-device-status">
                                <span class="badge badge-secondary">Checking...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="form-send-test">
                    <input type="hidden" id="test-device-id" name="device_id">

                    <!-- Send Type Selection -->
                    <div class="form-group">
                        <label class="font-weight-bold">Tipe Tujuan <span class="text-danger">*</span></label>
                        <div class="d-flex">
                            <div class="custom-control custom-radio mr-3">
                                <input type="radio" id="send-type-individual" name="send_type" class="custom-control-input" value="individual" checked>
                                <label class="custom-control-label" for="send-type-individual"><i class="fas fa-user text-primary"></i> Nomor WhatsApp (Personal)</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="send-type-group" name="send_type" class="custom-control-input" value="group">
                                <label class="custom-control-label" for="send-type-group"><i class="fas fa-users text-success"></i> Grup WhatsApp</label>
                            </div>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group" id="group-phone-input">
                        <label for="test-phone" class="font-weight-bold">
                            <i class="fas fa-phone text-primary"></i> Nomor Tujuan
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-success text-white">
                                    <i class="fab fa-whatsapp"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control form-control-lg" id="test-phone" name="phone"
                                placeholder="Contoh: 08123456789" required
                                pattern="[0-9]{10,15}" title="Masukkan nomor telepon yang valid (10-15 digit)">
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i>
                            Format: 08xxx atau 628xxx (akan otomatis dikonversi ke format internasional)
                        </small>
                    </div>

                    <!-- Group Name -->
                    <div class="form-group" id="group-name-input" style="display: none;">
                        <label for="test-group" class="font-weight-bold">
                            <i class="fas fa-users text-success"></i> Nama Grup WhatsApp
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-success text-white">
                                    <i class="fas fa-users"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control form-control-lg" id="test-group" name="group_name"
                                placeholder="Contoh: TEKNISI MEDIKAL PT. VYM">
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i>
                            Masukkan nama grup WhatsApp yang terdaftar di kontak WhatsApp device Anda.
                        </small>
                    </div>

                    <!-- Message -->
                    <div class="form-group">
                        <label for="test-message" class="font-weight-bold">
                            <i class="fas fa-comment text-primary"></i> Pesan
                            <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="test-message" name="message" rows="4"
                            placeholder="Ketik pesan yang akan dikirim..." required
                            maxlength="1000">Halo! Ini adalah pesan test dari sistem WhatsApp API - Office Visiyosindo.

Jika Anda menerima pesan ini, berarti koneksi WhatsApp berfungsi dengan baik. ✅</textarea>
                        <small class="form-text text-muted">
                            <span id="char-count">0</span>/1000 karakter
                    </div>

                    <!-- File Attachment Section -->
                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-paperclip text-info"></i> Lampiran Media (Opsional)
                        </label>
                        <div class="btn-group btn-group-toggle d-flex mb-2" data-toggle="buttons">
                            <label class="btn btn-outline-info active btn-sm w-50" id="btn-mode-url">
                                <input type="radio" name="attachment_mode" value="url" checked> <i class="fas fa-link"></i> URL Publik
                            </label>
                            <label class="btn btn-outline-info btn-sm w-50" id="btn-mode-upload">
                                <input type="radio" name="attachment_mode" value="upload"> <i class="fas fa-upload"></i> Upload Lokal
                            </label>
                        </div>

                        <!-- Mode URL Input -->
                        <div id="container-attachment-url">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-info text-white"><i class="fas fa-link"></i></span>
                                </div>
                                <input type="url" class="form-control" id="test-file-url" name="file_url"
                                    placeholder="Contoh: https://i.ibb.co/S5GYRNL/bird-thumbnail.jpg">
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Tautan publik langsung (direct link berakhiran .jpg, .png, .pdf, dll).
                            </small>
                        </div>

                        <!-- Mode Upload Input -->
                        <div id="container-attachment-upload" style="display: none;">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="test-file-upload" name="file_upload" accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip,application/x-rar-compressed">
                                <label class="custom-file-label" id="label-file-upload" for="test-file-upload">Pilih File dari Komputer...</label>
                            </div>
                            <small class="form-text text-warning mt-1">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Catatan Localhost:</strong> Upload lokal membutuhkan server online (cPanel) agar WhaCenter dapat men-download file Anda. Di localhost, gunakan mode <strong>URL Publik</strong>.
                            </small>
                        </div>
                    </div>

                    <!-- Quick Templates -->
                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-bolt text-warning"></i> Template Cepat:
                        </label>
                        <div class="btn-group btn-group-sm d-flex flex-wrap" role="group">
                            <button type="button" class="btn btn-outline-secondary template-btn"
                                data-template="Halo! Ini adalah pesan test dari WhatsApp API.">
                                Test Singkat
                            </button>
                            <button type="button" class="btn btn-outline-secondary template-btn"
                                data-template="Selamat pagi! Kami dari PT. Visi Yosindo Medikal ingin menginformasikan bahwa pesan WhatsApp otomatis sedang diuji coba.">
                                Formal
                            </button>
                            <button type="button" class="btn btn-outline-secondary template-btn"
                                data-template="Test koneksi WhatsApp ✓">
                                Minimal
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Warning -->
                <div class="alert alert-warning mb-0 small">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Pastikan nomor tujuan benar dan device dalam status <span class="badge badge-success">Connected</span> sebelum mengirim pesan.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-primary btn-lg" id="btn-submit-test">
                    <i class="fas fa-paper-plane"></i> Kirim Pesan Test
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Device Info -->
<div class="modal fade" id="modal-device-info" tabindex="-1" role="dialog" aria-labelledby="modal-device-info-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modal-device-info-label">
                    <i class="fas fa-info-circle"></i> Detail Status Device
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="device-info-loading" class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-info"></i>
                    <p class="mt-2">Memuat informasi device...</p>
                </div>
                <div id="device-info-content" style="display: none;">
                    <table class="table table-bordered table-striped mb-0">
                        <tbody id="device-info-table">
                        </tbody>
                    </table>
                </div>
                <div id="device-info-error" style="display: none;" class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    <p class="mt-2 text-danger" id="device-info-error-msg">Gagal memuat informasi</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Store base URL for JavaScript -->
<input type="hidden" id="base-url" value="<?= base_url() ?>">

<style>
    .device-card {
        transition: all 0.3s ease;
    }

    .device-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .device-card .status-indicator {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    .status-indicator.connected {
        background: #28a745;
    }

    .status-indicator.disconnected {
        background: #dc3545;
    }

    .status-indicator.pending {
        background: #ffc107;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
        }
    }

    .btn-action {
        min-width: 80px;
    }

    .qr-countdown-warning {
        animation: blink 1s infinite;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }
</style>

<script type="text/javascript">
    // Wait for jQuery to be available
    (function waitForJQuery() {
        if (typeof jQuery !== 'undefined') {
            initWhatsAppConfig();
        } else {
            setTimeout(waitForJQuery, 100);
        }
    })();

    function initWhatsAppConfig() {
        var $ = jQuery;
        var baseUrl = $('#base-url').val();
        var currentQrDeviceId = '';
        var currentQrDeviceName = '';
        var devicesData = [];
        var qrCountdownTimer = null;

        // Load devices on page ready
        $(document).ready(function() {
            loadDevices();
            bindEvents();
        });

        // Load devices from API
        function loadDevices() {
            $('#loading-devices').show();
            $('#error-devices').hide();
            $('#empty-devices').hide();
            $('#device-cards').hide();

            $.ajax({
                url: baseUrl + 'whatsapp_config/get_devices',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    $('#loading-devices').hide();

                    if (response.success && response.devices && response.devices.length > 0) {
                        devicesData = response.devices;
                        renderDeviceCards(response.devices);
                        $('#device-cards').show();
                    } else if (response.success && (!response.devices || response.devices.length === 0)) {
                        $('#empty-devices').show();
                    } else {
                        $('#error-message').text(response.message || 'Gagal memuat daftar device');
                        $('#error-devices').show();
                    }
                },
                error: function(xhr, status, error) {
                    $('#loading-devices').hide();
                    $('#error-message').text('Gagal terhubung ke server: ' + error);
                    $('#error-devices').show();
                }
            });
        }

        // Render device cards
        function renderDeviceCards(devices) {
            var container = $('#device-cards');
            container.empty();

            devices.forEach(function(device) {
                var deviceId = device.device_id || device.id || '';
                var deviceName = device.name || device.device_name || 'Unknown Device';
                var deviceNumber = device.nomor || device.number || device.phone || '-';
                var deviceStatus = device.status || 'unknown';
                var deviceDescription = device.description || '';
                var namaWa = device.nama_wa || device.nama || '';

                var statusClass = 'badge-secondary';
                var statusText = deviceStatus;
                var statusIcon = 'fa-question-circle';
                var indicatorClass = 'pending';

                var normalizedStatus = (deviceStatus || '').toString().toUpperCase();

                if (normalizedStatus === 'CONNECTED') {
                    statusClass = 'badge-success';
                    statusText = 'Connected';
                    statusIcon = 'fa-check-circle';
                    indicatorClass = 'connected';
                } else if (normalizedStatus === 'NOT CONNECTED' || normalizedStatus === 'DISCONNECTED') {
                    statusClass = 'badge-danger';
                    statusText = 'Not Connected';
                    statusIcon = 'fa-times-circle';
                    indicatorClass = 'disconnected';
                } else if (normalizedStatus === 'PENDING' || normalizedStatus === 'WAITING') {
                    statusClass = 'badge-warning';
                    statusText = 'Pending';
                    statusIcon = 'fa-clock';
                    indicatorClass = 'pending';
                }

                var cardHtml =
                    '<div class="col-md-6 col-lg-4 mb-4">' +
                    '<div class="card device-card h-100 shadow-sm position-relative">' +
                    '<div class="status-indicator ' + indicatorClass + '" title="' + statusText + '"></div>' +
                    '<div class="card-header bg-white">' +
                    '<h5 class="card-title mb-1">' +
                    '<i class="fab fa-whatsapp text-success"></i> ' +
                    escapeHtml(deviceName) +
                    '</h5>' +
                    (deviceDescription ? '<small class="text-muted">' + escapeHtml(deviceDescription) + '</small>' : '') +
                    '</div>' +
                    '<div class="card-body">' +
                    '<table class="table table-sm table-borderless mb-0">' +
                    '<tr>' +
                    '<td class="text-muted" style="width:100px;"><i class="fas fa-fingerprint"></i> ID:</td>' +
                    '<td><code class="small">' + escapeHtml(deviceId.substring(0, 12)) + '...</code></td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td class="text-muted"><i class="fas fa-phone"></i> Nomor:</td>' +
                    '<td class="font-weight-bold">' + escapeHtml(deviceNumber) + '</td>' +
                    '</tr>' +
                    (namaWa ? '<tr><td class="text-muted"><i class="fas fa-user"></i> Nama:</td><td>' + escapeHtml(namaWa) + '</td></tr>' : '') +
                    '<tr>' +
                    '<td class="text-muted"><i class="fas fa-signal"></i> Status:</td>' +
                    '<td>' +
                    '<span class="device-status badge ' + statusClass + '" data-device-id="' + deviceId + '">' +
                    '<i class="fas ' + statusIcon + '"></i> ' + statusText +
                    '</span>' +
                    '</td>' +
                    '</tr>' +
                    '</table>' +
                    '</div>' +
                    '<div class="card-footer bg-light">' +
                    '<div class="row">' +
                    '<div class="col-6 pr-1">' +
                    '<button type="button" class="btn btn-info btn-sm btn-block btn-check-status" ' +
                    'data-device-id="' + deviceId + '" ' +
                    'data-device-name="' + escapeHtml(deviceName) + '" ' +
                    'title="Cek status terbaru dari API">' +
                    '<i class="fas fa-sync-alt"></i> Cek Status' +
                    '</button>' +
                    '</div>' +
                    '<div class="col-6 pl-1">' +
                    '<button type="button" class="btn btn-warning btn-sm btn-block btn-relog" ' +
                    'data-device-id="' + deviceId + '" ' +
                    'data-device-name="' + escapeHtml(deviceName) + '" ' +
                    'title="Reset koneksi device">' +
                    '<i class="fas fa-redo"></i> Relog' +
                    '</button>' +
                    '</div>' +
                    '</div>' +
                    '<div class="row mt-2">' +
                    '<div class="col-6 pr-1">' +
                    '<button type="button" class="btn btn-success btn-sm btn-block btn-scan-qr" ' +
                    'data-device-id="' + deviceId + '" ' +
                    'data-device-name="' + escapeHtml(deviceName) + '" ' +
                    'title="Tampilkan QR Code untuk scan">' +
                    '<i class="fas fa-qrcode"></i> Scan QR' +
                    '</button>' +
                    '</div>' +
                    '<div class="col-6 pl-1">' +
                    '<button type="button" class="btn btn-primary btn-sm btn-block btn-send-test" ' +
                    'data-device-id="' + deviceId + '" ' +
                    'data-device-name="' + escapeHtml(deviceName) + '" ' +
                    'title="Kirim pesan test">' +
                    '<i class="fas fa-paper-plane"></i> Test Kirim' +
                    '</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                container.append(cardHtml);
            });

            bindCardEvents();
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            if (!text) return '';
            var div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Bind global events
        function bindEvents() {
            // Refresh All
            $('#btn-refresh-all').click(function() {
                var btn = $(this);
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memuat...');
                loadDevices();
                setTimeout(function() {
                    btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Refresh Semua');
                }, 1000);
            });

            // Retry Load
            $('#btn-retry-load').click(function() {
                loadDevices();
            });

            // Refresh QR
            $('#btn-refresh-qr').click(function() {
                if (currentQrDeviceId) {
                    showQrLoading();
                    getQRCode(currentQrDeviceId);
                }
            });

            // Relog from QR modal
            $('#btn-relog-from-qr').click(function() {
                if (currentQrDeviceId) {
                    $('#modal-qrcode').modal('hide');
                    relogDevice(currentQrDeviceId, currentQrDeviceName);
                }
            });

            // Character counter for message
            $('#test-message').on('input', function() {
                var len = $(this).val().length;
                $('#char-count').text(len);
                if (len > 900) {
                    $('#char-count').addClass('text-danger font-weight-bold');
                } else {
                    $('#char-count').removeClass('text-danger font-weight-bold');
                }
            });

            // Template buttons
            $(document).on('click', '.template-btn', function() {
                var template = $(this).data('template');
                $('#test-message').val(template).trigger('input');
            });

            // Destination type toggle logic
            $('input[name="send_type"]').change(function() {
                if (this.value === 'group') {
                    $('#group-phone-input').hide();
                    $('#test-phone').prop('required', false);
                    $('#group-name-input').show();
                    $('#test-group').prop('required', true);
                } else {
                    $('#group-name-input').hide();
                    $('#test-group').prop('required', false);
                    $('#group-phone-input').show();
                    $('#test-phone').prop('required', true);
                }
            });

            // Phone number formatting
            $('#test-phone').on('input', function() {
                var val = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(val);
            });

            // Toggle attachment mode
            $('input[name="attachment_mode"]').change(function() {
                if (this.value === 'upload') {
                    $('#container-attachment-url').hide();
                    $('#container-attachment-upload').show();
                } else {
                    $('#container-attachment-upload').hide();
                    $('#container-attachment-url').show();
                }
            });

            // Update file upload input label filename
            $(document).on('change', '#test-file-upload', function() {
                var fileName = $(this).val().split('\\').pop();
                $('#label-file-upload').html(fileName || 'Pilih File dari Komputer...');
            });

            // Submit Send Test
            $('#btn-submit-test').click(function() {
                var deviceId = $('#test-device-id').val();
                var sendType = $('input[name="send_type"]:checked').val();
                var phone = $('#test-phone').val().trim();
                var groupName = $('#test-group').val().trim();
                var message = $('#test-message').val().trim();

                var attachmentMode = $('input[name="attachment_mode"]:checked').val();
                var fileUrl = $('#test-file-url').val().trim();
                var fileUploadName = '';

                if (attachmentMode === 'upload') {
                    var fileInput = $('#test-file-upload')[0];
                    if (fileInput.files.length > 0) {
                        fileUploadName = fileInput.files[0].name;
                    }
                }

                // Validation
                if (sendType === 'individual') {
                    if (!phone) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Nomor Kosong',
                            text: 'Silakan masukkan nomor telepon tujuan'
                        });
                        $('#test-phone').focus();
                        return;
                    }

                    if (phone.length < 10 || phone.length > 15) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Nomor Tidak Valid',
                            text: 'Nomor telepon harus 10-15 digit'
                        });
                        $('#test-phone').focus();
                        return;
                    }
                } else {
                    if (!groupName) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Nama Grup Kosong',
                            text: 'Silakan masukkan nama grup WhatsApp tujuan'
                        });
                        $('#test-group').focus();
                        return;
                    }
                }

                if (!message) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pesan Kosong',
                        text: 'Silakan masukkan pesan yang akan dikirim'
                    });
                    $('#test-message').focus();
                    return;
                }

                // Confirm before send
                var confirmHtml = '<div class="text-left">';
                if (sendType === 'individual') {
                    confirmHtml += '<p><strong>Nomor Tujuan:</strong> ' + phone + '</p>';
                } else {
                    confirmHtml += '<p><strong>Grup Tujuan:</strong> ' + escapeHtml(groupName) + '</p>';
                }

                if (attachmentMode === 'upload' && fileUploadName) {
                    confirmHtml += '<p><strong>File Upload:</strong> <span class="text-info text-break">' + escapeHtml(fileUploadName) + '</span></p>';
                } else if (attachmentMode === 'url' && fileUrl) {
                    confirmHtml += '<p><strong>URL File:</strong> <span class="text-info text-break">' + escapeHtml(fileUrl) + '</span></p>';
                }

                confirmHtml += '<p><strong>Pesan:</strong></p>' +
                    '<div class="bg-light p-2 rounded border" style="max-height:100px;overflow-y:auto;">' +
                    escapeHtml(message).substring(0, 200) + (message.length > 200 ? '...' : '') +
                    '</div></div>';

                Swal.fire({
                    title: 'Konfirmasi Kirim Pesan',
                    html: confirmHtml,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-paper-plane"></i> Ya, Kirim!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        sendTestMessage(deviceId, sendType, phone, groupName, message, attachmentMode, fileUrl);
                    }
                });
            });

            // Clear QR countdown when modal closes
            $('#modal-qrcode').on('hidden.bs.modal', function() {
                if (qrCountdownTimer) {
                    clearInterval(qrCountdownTimer);
                    qrCountdownTimer = null;
                }
            });
        }

        // Send test message
        function sendTestMessage(deviceId, sendType, phone, groupName, message, attachmentMode, fileUrl) {
            var btn = $('#btn-submit-test');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');

            var formData = new FormData();
            formData.append('device_id', deviceId);
            formData.append('send_type', sendType);
            formData.append('phone', phone);
            formData.append('group_name', groupName);
            formData.append('message', message);
            formData.append('attachment_mode', attachmentMode);

            if (attachmentMode === 'upload') {
                var fileInput = $('#test-file-upload')[0];
                if (fileInput.files.length > 0) {
                    formData.append('file_upload', fileInput.files[0]);
                }
            } else {
                formData.append('file_url', fileUrl);
            }

            $.ajax({
                url: baseUrl + 'whatsapp_config/send_test',
                type: 'POST',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim Pesan Test');

                    if (response.success) {
                        $('#modal-send-test').modal('hide');
                        var targetText = sendType === 'individual' ? phone : groupName;
                        Swal.fire({
                            icon: 'success',
                            title: 'Pesan Terkirim!',
                            html: '<p>Pesan berhasil dikirim ke:</p><p class="font-weight-bold">' + escapeHtml(targetText) + '</p>',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Mengirim',
                            text: response.message || 'Terjadi kesalahan saat mengirim pesan'
                        });
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim Pesan Test');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal terhubung ke server'
                    });
                }
            });
        }

        // Bind events for dynamically created cards
        function bindCardEvents() {
            // Check single device status
            $('.btn-check-status').off('click').on('click', function() {
                var deviceId = $(this).data('device-id');
                var deviceName = $(this).data('device-name');
                checkDeviceStatus(deviceId, deviceName, true);
            });

            // Relog device
            $('.btn-relog').off('click').on('click', function() {
                var deviceId = $(this).data('device-id');
                var deviceName = $(this).data('device-name');

                Swal.fire({
                    title: 'Konfirmasi Relog Device',
                    html: '<div class="text-left">' +
                        '<p>Device: <strong>' + escapeHtml(deviceName) + '</strong></p>' +
                        '<div class="alert alert-info"><i class="fas fa-info-circle"></i> ' +
                        'Relog akan me-refresh koneksi device tanpa memutus koneksi. Device tetap connected dan tidak perlu scan QR ulang.</div>' +
                        '</div>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#f0ad4e',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-redo"></i> Ya, Relog Device',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        relogDevice(deviceId, deviceName);
                    }
                });
            });

            // Scan QR Code
            $('.btn-scan-qr').off('click').on('click', function() {
                var deviceId = $(this).data('device-id');
                var deviceName = $(this).data('device-name');
                currentQrDeviceId = deviceId;
                currentQrDeviceName = deviceName;

                $('#qr-device-name').text(deviceName);
                showQrLoading();
                $('#btn-relog-from-qr').hide();
                $('#modal-qrcode').modal('show');

                getQRCode(deviceId);
            });

            // Send Test
            $('.btn-send-test').off('click').on('click', function() {
                var deviceId = $(this).data('device-id');
                var deviceName = $(this).data('device-name');

                currentQrDeviceId = deviceId;
                $('#test-device-id').val(deviceId);
                $('#test-device-name').text(deviceName);
                $('#test-device-status').html('<span class="badge badge-secondary"><i class="fas fa-spinner fa-spin"></i> Checking...</span>');
                $('#test-phone').val('');
                $('#test-group').val('');
                $('#test-file-url').val('');
                $('#test-file-upload').val('').next('.custom-file-label').html('Pilih File dari Komputer...');
                $('input[name="attachment_mode"][value="url"]').prop('checked', true).trigger('change');
                $('#btn-mode-url').addClass('active').siblings().removeClass('active');
                $('#char-count').text($('#test-message').val().length);

                $('#modal-send-test').modal('show');

                // Check device status
                checkDeviceStatus(deviceId, deviceName, false);
            });
        }

        // Show QR loading state
        function showQrLoading() {
            $('#qr-loading').show();
            $('#qr-content').hide();
            $('#qr-connected').hide();
            $('#qr-error').hide();
            if (qrCountdownTimer) {
                clearInterval(qrCountdownTimer);
                qrCountdownTimer = null;
            }
        }

        // Start QR countdown
        function startQrCountdown() {
            var seconds = 45;
            var countdownEl = $('#qr-countdown span');

            countdownEl.text(seconds).removeClass('qr-countdown-warning');

            if (qrCountdownTimer) clearInterval(qrCountdownTimer);

            qrCountdownTimer = setInterval(function() {
                seconds--;
                countdownEl.text(seconds);

                if (seconds <= 10) {
                    countdownEl.addClass('qr-countdown-warning badge-danger').removeClass('badge-warning');
                }

                if (seconds <= 0) {
                    clearInterval(qrCountdownTimer);
                    $('#qr-error-message').text('QR Code expired. Silakan refresh untuk mendapatkan QR baru.');
                    $('#qr-content').hide();
                    $('#qr-error').show();
                    $('#btn-relog-from-qr').show();
                }
            }, 1000);
        }

        // Function: Check Device Status (FIXED: TOASTR REMOVED)
        function checkDeviceStatus(deviceId, deviceName, showFeedback) {
            var badge = $('.device-status[data-device-id="' + deviceId + '"]');
            badge.html('<i class="fas fa-spinner fa-spin"></i> Checking...');
            badge.removeClass('badge-success badge-danger badge-warning badge-secondary').addClass('badge-secondary');

            $.ajax({
                url: baseUrl + 'whatsapp_config/check_status',
                type: 'POST',
                dataType: 'json',
                data: {
                    device_id: deviceId
                },
                success: function(response) {
                    if (response.success && response.data) {
                        updateStatusBadge(deviceId, response.data.status, response.data);

                        // Update test modal status if open
                        var normalizedStatus = (response.data.status || '').toString().toUpperCase();
                        if ($('#modal-send-test').is(':visible')) {
                            if (normalizedStatus === 'CONNECTED') {
                                $('#test-device-status').html('<span class="badge badge-success"><i class="fas fa-check-circle"></i> Connected</span>');
                            } else {
                                $('#test-device-status').html('<span class="badge badge-danger"><i class="fas fa-times-circle"></i> ' + (response.data.status || 'Disconnected') + '</span>');
                            }
                        }

                        if (showFeedback) {
                            if (normalizedStatus === 'CONNECTED') {
                                // REPLACED toastr.success
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Status OK',
                                    text: 'Device ' + deviceName + ' terhubung',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                // REPLACED toastr.warning
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Perlu Perhatian',
                                    text: 'Device ' + deviceName + ': ' + (response.data.status || 'Tidak terhubung')
                                });
                            }
                        }
                    } else {
                        badge.html('<i class="fas fa-times"></i> Error');
                        badge.removeClass('badge-secondary').addClass('badge-danger');
                        if (showFeedback) {
                            // REPLACED toastr.error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Gagal cek status'
                            });
                        }
                    }
                },
                error: function() {
                    badge.html('<i class="fas fa-times"></i> Error');
                    badge.removeClass('badge-secondary').addClass('badge-danger');
                    if (showFeedback) {
                        // REPLACED toastr.error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal terhubung ke server'
                        });
                    }
                }
            });
        }

        // Function: Update Status Badge
        function updateStatusBadge(deviceId, status, data) {
            var badge = $('.device-status[data-device-id="' + deviceId + '"]');
            var indicator = badge.closest('.device-card').find('.status-indicator');

            badge.removeClass('badge-success badge-danger badge-warning badge-secondary');
            indicator.removeClass('connected disconnected pending');

            var normalizedStatus = (status || '').toString().toUpperCase();

            if (normalizedStatus === 'CONNECTED') {
                badge.addClass('badge-success').html('<i class="fas fa-check-circle"></i> Connected');
                indicator.addClass('connected');
                if (data && data.nomor && data.nomor !== '-') {
                    badge.html('<i class="fas fa-check-circle"></i> ' + data.nomor);
                }
            } else if (normalizedStatus === 'NOT CONNECTED' || normalizedStatus === 'DISCONNECTED') {
                badge.addClass('badge-danger').html('<i class="fas fa-times-circle"></i> Not Connected');
                indicator.addClass('disconnected');
            } else if (normalizedStatus === 'PENDING' || normalizedStatus === 'WAITING') {
                badge.addClass('badge-warning').html('<i class="fas fa-clock"></i> Pending');
                indicator.addClass('pending');
            } else {
                badge.addClass('badge-secondary').html('<i class="fas fa-question-circle"></i> ' + (status || 'Unknown'));
                indicator.addClass('pending');
            }
        }

        // Function: Relog Device
        function relogDevice(deviceId, deviceName) {
            Swal.fire({
                title: 'Proses Relog...',
                html: 'Sedang melakukan relog device <strong>' + escapeHtml(deviceName) + '</strong>...',
                allowOutsideClick: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: baseUrl + 'whatsapp_config/relog_device',
                type: 'POST',
                dataType: 'json',
                data: {
                    device_id: deviceId
                },
                success: function(response) {
                    Swal.close();

                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Relog Berhasil!',
                            html: '<p>Device <strong>' + escapeHtml(deviceName) + '</strong> telah di-relog.</p>' +
                                '<p class="text-info"><i class="fas fa-qrcode"></i> QR Code baru sudah tersedia. Silakan scan untuk menghubungkan.</p>',
                            showCancelButton: true,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: '<i class="fas fa-qrcode"></i> Scan QR Code Sekarang',
                            cancelButtonText: 'Nanti Saja'
                        }).then(function(result) {
                            // Refresh status
                            checkDeviceStatus(deviceId, deviceName, false);

                            if (result.isConfirmed) {
                                // Buka modal QR dan load QR baru
                                currentQrDeviceId = deviceId;
                                currentQrDeviceName = deviceName;
                                $('#qr-device-name').text(deviceName);
                                showQrLoading();
                                $('#modal-qrcode').modal('show');
                                getQRCode(deviceId);
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Relog Gagal',
                            text: response.message || 'Terjadi kesalahan'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal terhubung ke server'
                    });
                }
            });
        }

        // Function: Get QR Code
        function getQRCode(deviceId) {
            $.ajax({
                url: baseUrl + 'whatsapp_config/get_qrcode',
                type: 'POST',
                dataType: 'json',
                data: {
                    device_id: deviceId
                },
                success: function(response) {
                    $('#qr-loading').hide();

                    if (response.success && response.data) {
                        var qrData = response.data;
                        var status = (qrData.status || '').toUpperCase();

                        // Device sudah connected - tidak perlu scan QR
                        if (status === 'CONNECTED') {
                            $('#qr-connected').show();
                            $('#btn-relog-from-qr').hide();
                            return;
                        }

                        // QR timeout - perlu relog dulu
                        if (qrData.need_relog || qrData.qr_status === 'timeout') {
                            $('#qr-error-message').html(
                                '<i class="fas fa-clock text-warning"></i> <strong>Session QR sudah expired/timeout</strong><br>' +
                                '<small class="text-muted">Klik tombol "Relog Device" di bawah untuk mendapatkan QR Code baru.</small>'
                            );
                            $('#qr-error').show();
                            $('#btn-relog-from-qr').show();
                            return;
                        }

                        // QR Code tersedia - tampilkan gambar
                        if (qrData.qrcode) {
                            $('#qr-image').attr('src', qrData.qrcode);
                            $('#qr-content').show();
                            $('#btn-relog-from-qr').hide();
                            startQrCountdown();
                        } else {
                            // Tidak ada QR dan tidak connected
                            $('#qr-error-message').html('<i class="fas fa-info-circle"></i> QR Code tidak tersedia. Silakan coba relog device.');
                            $('#qr-error').show();
                            $('#btn-relog-from-qr').show();
                        }
                    } else {
                        $('#qr-error-message').text(response.message || 'Gagal memuat QR Code');
                        $('#qr-error').show();
                        $('#btn-relog-from-qr').show();
                    }
                },
                error: function() {
                    $('#qr-loading').hide();
                    $('#qr-error-message').text('Gagal terhubung ke server');
                    $('#qr-error').show();
                    $('#btn-relog-from-qr').show();
                }
            });
        }
    }
</script>